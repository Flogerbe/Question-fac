<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GameSession;
use App\Models\Question;
use App\Models\SiteSetting;
use App\Models\TirageResult;
use Illuminate\Http\Request;

class TirageController extends Controller
{
    private const TYPES = ['esprit_club', 'champion', 'bonus'];

    public function index()
    {
        $totalQuestions = Question::active()->count();

        // Pool de base : parties comptées et complètes
        $base = GameSession::with(['player', 'gameAnswers'])
            ->where('completed', true)
            ->where('counted', true)
            ->get();

        // Esprit Club : tous les participants
        $espritClub = $base;

        // 100% Champion : uniquement les sans-faute
        $champions = $base->filter(function ($session) use ($totalQuestions) {
            return $session->gameAnswers->where('is_correct', true)->count() === $totalQuestions;
        });

        // Bonus : tous les participants (même pool qu'Esprit Club)
        $bonus = $base;

        // Résultats déjà tirés, groupés par type
        $drawn = TirageResult::with(['gameSession.player', 'gameSession.gameAnswers'])
            ->orderBy('rang')
            ->get()
            ->groupBy('type');

        $settings = SiteSetting::all_settings();

        return view('admin.tirage', compact(
            'espritClub', 'champions', 'bonus', 'drawn', 'totalQuestions', 'settings'
        ));
    }

    public function draw(Request $request, string $type)
    {
        if (!in_array($type, self::TYPES)) {
            return response()->json(['error' => 'Type invalide'], 400);
        }

        $totalQuestions = Question::active()->count();
        $nb = max(1, (int)(SiteSetting::get('tirage_' . str_replace('_club', '_club', $type) . '_nb') ?? 1));

        // Récupérer le bon nb de gagnants selon le type
        $nbKey = match ($type) {
            'esprit_club' => 'tirage_esprit_club_nb',
            'champion'    => 'tirage_champion_nb',
            'bonus'       => 'tirage_bonus_nb',
        };
        $nb = max(1, (int)(SiteSetting::get($nbKey) ?? 1));

        // Pool éligible
        $pool = GameSession::with(['player', 'gameAnswers'])
            ->where('completed', true)
            ->where('counted', true)
            ->get();

        if ($type === 'champion') {
            $pool = $pool->filter(function ($session) use ($totalQuestions) {
                return $session->gameAnswers->where('is_correct', true)->count() === $totalQuestions;
            });
        }

        if ($pool->count() === 0) {
            return response()->json(['error' => 'Aucun participant éligible pour ce tirage.'], 422);
        }

        // Limiter nb au nombre de participants disponibles
        $nb = min($nb, $pool->count());

        // Effacer les résultats précédents pour ce type
        TirageResult::where('type', $type)->delete();

        // Tirage aléatoire
        $winners = $pool->shuffle()->take($nb);
        $rang = 1;
        foreach ($winners as $session) {
            TirageResult::create([
                'type'            => $type,
                'game_session_id' => $session->id,
                'rang'            => $rang++,
                'drawn_at'        => now(),
            ]);
        }

        // Rechargement avec relations pour la réponse JSON
        $results = TirageResult::with(['gameSession.player', 'gameSession.gameAnswers'])
            ->where('type', $type)
            ->orderBy('rang')
            ->get();

        return response()->json([
            'winners' => $results->map(function ($r) use ($totalQuestions) {
                return [
                    'rang'          => $r->rang,
                    'nom_complet'   => $r->gameSession->player->full_name,
                    'score'         => $r->gameSession->score,
                    'bonnes_rep'    => $r->gameSession->gameAnswers->where('is_correct', true)->count(),
                    'total_q'       => $totalQuestions,
                ];
            }),
        ]);
    }

    public function reset(string $type)
    {
        if (!in_array($type, self::TYPES)) {
            return back()->with('error', 'Type invalide.');
        }

        TirageResult::where('type', $type)->delete();

        return back()->with('success', 'Tirage réinitialisé.');
    }
}
