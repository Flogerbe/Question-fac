<?php

namespace App\Http\Controllers;

use App\Models\GameSession;
use App\Models\Question;
use App\Models\SiteSetting;
use App\Models\TirageResult;

class HomeController extends Controller
{
    public function index()
    {
        $topScores = GameSession::with('player')
            ->where('completed', true)
            ->where('counted', true)
            ->orderByDesc('score')
            ->orderBy('temps_total')
            ->take(5)
            ->get();

        $totalQuestions = Question::active()->count();
        $maxScore = $totalQuestions * 1000;

        $tirageWinners = TirageResult::with('gameSession.player')
            ->orderBy('rang')
            ->get()
            ->groupBy('type');

        $totalParticipants = GameSession::where('completed', true)->where('counted', true)->count();

        return view('home', compact('topScores', 'maxScore', 'tirageWinners', 'totalParticipants'));
    }

    public function regles()
    {
        return view('regles');
    }

    public function classement()
    {
        $mode = SiteSetting::get('classement_mode') ?? 'points';
        $totalQuestions = Question::active()->count();

        $query = GameSession::with(['player', 'gameAnswers'])
            ->where('completed', true)
            ->where('counted', true);

        if ($mode === 'points') {
            $query->orderByDesc('score')->orderBy('temps_total')->take(50);
        }

        $scores = $query->get();

        return view('classement', compact('scores', 'mode', 'totalQuestions'));
    }
}
