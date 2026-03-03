<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\GameSession;

class LeaderboardController extends Controller
{
    public function index()
    {
        $sessions = GameSession::with('player')
            ->where('completed', true)
            ->orderByDesc('score')
            ->orderBy('temps_total')
            ->paginate(20);

        return view('admin.leaderboard', compact('sessions'));
    }

    public function destroy(GameSession $session)
    {
        $session->delete();
        return back()->with('success', 'Entrée supprimée du classement.');
    }
}
