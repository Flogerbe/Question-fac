<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class InstallController extends Controller
{
    private const LOCK_FILE = 'installed';

    public function index()
    {
        if ($this->isInstalled()) {
            return redirect('/');
        }
        return view('install');
    }

    public function store(Request $request)
    {
        if ($this->isInstalled()) {
            return redirect('/');
        }

        $request->validate([
            'db_host'          => ['required', 'string'],
            'db_port'          => ['required', 'integer', 'min:1', 'max:65535'],
            'db_name'          => ['required', 'string', 'regex:/^[a-zA-Z0-9_]+$/'],
            'db_user'          => ['required', 'string'],
            'db_password'      => ['nullable', 'string'],
            'app_url'          => ['required', 'url'],
            'admin_name'       => ['required', 'string', 'max:100'],
            'admin_email'      => ['required', 'email'],
            'admin_password'   => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'db_name.regex'          => 'Le nom de la base ne doit contenir que des lettres, chiffres et underscores.',
            'admin_password.min'     => 'Le mot de passe doit contenir au moins 8 caractères.',
            'admin_password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        // 1. Tester la connexion BDD avant d'écrire quoi que ce soit
        try {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
                $request->db_host,
                $request->db_port,
                $request->db_name
            );
            new \PDO($dsn, $request->db_user, $request->db_password ?? '');
        } catch (\PDOException $e) {
            return back()->withInput()
                ->withErrors(['db_host' => 'Connexion impossible : ' . $e->getMessage()]);
        }

        // 2. Écrire le .env
        $this->updateEnv([
            'APP_URL'     => $request->app_url,
            'DB_HOST'     => $request->db_host,
            'DB_PORT'     => (string) $request->db_port,
            'DB_DATABASE' => $request->db_name,
            'DB_USERNAME' => $request->db_user,
            'DB_PASSWORD' => $request->db_password ?? '',
        ]);

        // 3. Mettre à jour la config runtime et se reconnecter
        config([
            'database.connections.mysql.host'     => $request->db_host,
            'database.connections.mysql.port'     => $request->db_port,
            'database.connections.mysql.database' => $request->db_name,
            'database.connections.mysql.username' => $request->db_user,
            'database.connections.mysql.password' => $request->db_password ?? '',
        ]);
        DB::purge('mysql');
        DB::reconnect('mysql');

        // 4. Générer la clé app si absente
        if (empty(config('app.key'))) {
            Artisan::call('key:generate', ['--force' => true]);
        }

        // 5. Migrations
        try {
            Artisan::call('migrate', ['--force' => true, '--no-interaction' => true]);
        } catch (\Throwable $e) {
            return back()->withInput()
                ->withErrors(['db_host' => 'Erreur lors des migrations : ' . $e->getMessage()]);
        }

        // 6. Seeders (questions, options maillot)
        try {
            Artisan::call('db:seed', ['--force' => true, '--no-interaction' => true]);
        } catch (\Throwable $e) {
            // Non bloquant : si le seed échoue, l'install continue
        }

        // 7. Créer le compte admin
        User::create([
            'name'              => $request->admin_name,
            'email'             => $request->admin_email,
            'password'          => bcrypt($request->admin_password),
            'email_verified_at' => now(),
        ]);

        // 8. Marquer comme installé
        file_put_contents(storage_path('app/' . self::LOCK_FILE), now()->toDateTimeString());

        return redirect('/login')->with('status', 'Installation réussie ! Connectez-vous avec votre compte admin.');
    }

    private function isInstalled(): bool
    {
        return file_exists(storage_path('app/' . self::LOCK_FILE));
    }

    private function updateEnv(array $values): void
    {
        $envPath = base_path('.env');

        if (!file_exists($envPath)) {
            $example = base_path('.env.example');
            copy(file_exists($example) ? $example : $envPath, $envPath);
        }

        $content = file_get_contents($envPath);

        foreach ($values as $key => $value) {
            // Entourer de guillemets si la valeur contient des espaces
            $escaped = str_contains((string) $value, ' ') ? '"' . $value . '"' : $value;

            if (preg_match('/^' . preg_quote($key, '/') . '=/m', $content)) {
                $content = preg_replace(
                    '/^' . preg_quote($key, '/') . '=.*/m',
                    $key . '=' . $escaped,
                    $content
                );
            } else {
                $content .= "\n" . $key . '=' . $escaped;
            }
        }

        file_put_contents($envPath, $content);
    }
}
