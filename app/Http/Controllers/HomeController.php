<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameSession;
use App\Models\Player;

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

        return view('home', compact('topScores'));
    }

    public function regles()
    {
        return view('regles');
    }

    public function classement()
    {
        $scores = GameSession::with('player')
            ->where('completed', true)
            ->where('counted', true)
            ->orderByDesc('score')
            ->orderBy('temps_total')
            ->take(50)
            ->get();

        return view('classement', compact('scores'));
    }
}
