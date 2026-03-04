<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

use App\Models\Question;
use App\Models\GameSession;
use App\Models\Player;
use App\Models\JerseyVote;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_questions' => Question::count(),
            'questions_actives' => Question::where('is_active', true)->count(),
            'total_parties' => GameSession::where('completed', true)->count(),
            'parties_valides' => GameSession::where('completed', true)->where('counted', true)->count(),
            'total_joueurs' => Player::count(),
            'total_votes_maillot' => JerseyVote::count(),
        ];

        $topScores = GameSession::with('player')
            ->where('completed', true)
            ->where('counted', true)
            ->orderByDesc('score')
            ->orderBy('temps_total')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'topScores'));
    }

    public function migrate()
    {
        try {
            Artisan::call('migrate', ['--force' => true, '--no-interaction' => true]);
            $output = trim(Artisan::output());
            $alreadyUpToDate = empty($output) || str_contains($output, 'Nothing to migrate');
            $msg = $alreadyUpToDate ? 'La base de données est déjà à jour.' : 'Migrations appliquées avec succès.';
            return back()->with('success', '✅ ' . $msg);
        } catch (\Throwable $e) {
            return back()->with('error', '❌ Erreur lors de la migration : ' . $e->getMessage());
        }
    }

    public function composerInstall()
    {
        if (!function_exists('exec')) {
            return back()->with('error', '❌ La fonction exec() est désactivée sur ce serveur. Réinstallez le dossier vendor/ manuellement via FTP ou SSH avec : composer install --no-dev');
        }

        $projectRoot = base_path();

        // Détecter le binaire composer disponible sur le serveur
        $composer = 'composer';
        foreach (['/usr/bin/composer', '/usr/local/bin/composer', '/usr/bin/composer.phar', '/usr/local/bin/composer.phar'] as $path) {
            if (file_exists($path)) {
                $composer = escapeshellcmd($path);
                break;
            }
        }

        $cmd = 'cd ' . escapeshellarg($projectRoot) . ' && ' . $composer . ' install --no-dev --optimize-autoloader --no-interaction 2>&1';
        $output = [];
        $returnCode = 0;
        exec($cmd, $output, $returnCode);

        if ($returnCode === 0) {
            return back()->with('success', '✅ Dépendances réinstallées avec succès (composer install --no-dev).');
        }

        $errorLines = implode("\n", array_slice($output, -8));
        return back()->with('error', '❌ Erreur Composer : ' . $errorLines);
    }
}
