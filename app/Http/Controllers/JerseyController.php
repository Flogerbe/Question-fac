<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JerseyOption;
use App\Models\JerseyVote;
use App\Models\SiteSetting;

class JerseyController extends Controller
{
    public function index(Request $request)
    {
        if (SiteSetting::get('vote_visible') === '0') {
            return redirect()->route('home');
        }

        $options = JerseyOption::where('is_active', true)->withCount('votes')->get();
        $totalVotes = JerseyVote::count();

        $ipHash = hash('sha256', $request->ip() . config('app.key'));
        $hasVoted = JerseyVote::where('ip_hash', $ipHash)->exists();

        $userVoteOptionId = null;
        if ($hasVoted) {
            $userVote = JerseyVote::where('ip_hash', $ipHash)->first();
            $userVoteOptionId = $userVote?->jersey_option_id;
        }

        return view('jersey', compact('options', 'totalVotes', 'hasVoted', 'userVoteOptionId'));
    }

    public function voter(Request $request)
    {
        $request->validate(['option_id' => 'required|exists:jersey_options,id']);

        $ipHash = hash('sha256', $request->ip() . config('app.key'));

        if (!JerseyVote::where('ip_hash', $ipHash)->exists()) {
            JerseyVote::create([
                'jersey_option_id' => $request->option_id,
                'ip_hash' => $ipHash,
            ]);
        }

        return redirect()->route('jersey.index')->with('success', 'Merci pour votre vote !');
    }
}
