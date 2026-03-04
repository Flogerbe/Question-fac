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
            $msg = $output ?: 'Base de données déjà à jour.';
            return back()->with('success', '✅ Migration réussie : ' . $msg);
        } catch (\Throwable $e) {
            return back()->with('error', 'Erreur lors de la migration : ' . $e->getMessage());
        }
    }
}
