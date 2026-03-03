<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\GameSession;
use Illuminate\Http\Request;

class PlayersController extends Controller
{
    public function index()
    {
        $players = Player::with(['gameSessions' => function ($q) {
            $q->orderByDesc('score');
        }])
        ->whereNotNull('played_at')
        ->orderByDesc('played_at')
        ->get();

        return view('admin.players', compact('players'));
    }

    public function destroy(Player $player)
    {
        $player->gameSessions()->each(function ($session) {
            $session->gameAnswers()->delete();
            $session->delete();
        });
        $player->delete();

        return back()->with('success', 'Joueur supprimé.');
    }
}
