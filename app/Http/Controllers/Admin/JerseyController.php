<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\JerseyOption;
use App\Models\JerseyVote;

class JerseyController extends Controller
{
    public function index()
    {
        $options = JerseyOption::withCount('votes')->get();
        $totalVotes = JerseyVote::count();
        return view('admin.jersey', compact('options', 'totalVotes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'couleurs' => 'nullable|string|max:100',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('jerseys', 'public');
        }

        JerseyOption::create([
            'nom' => $data['nom'],
            'description' => $data['description'] ?? null,
            'couleurs' => $data['couleurs'] ?? null,
            'image_path' => $imagePath,
        ]);

        return back()->with('success', 'Option de maillot créée.');
    }

    public function update(Request $request, JerseyOption $option)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'couleurs' => 'nullable|string|max:100',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('jerseys', 'public');
        }

        $option->update($data);
        return back()->with('success', 'Option mise à jour.');
    }

    public function destroy(JerseyOption $option)
    {
        $option->delete();
        return back()->with('success', 'Option supprimée.');
    }

    public function resetVotes(JerseyOption $option)
    {
        $option->votes()->delete();
        return back()->with('success', 'Votes réinitialisés.');
    }
}
