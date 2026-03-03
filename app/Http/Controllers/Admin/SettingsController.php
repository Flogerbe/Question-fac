<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::all_settings();
        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'couleur_bleu'        => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'couleur_bleu_fonce'  => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'couleur_orange'      => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'site_titre'          => ['required', 'max:100'],
            'site_sous_titre'     => ['required', 'max:200'],
            'logo'                => ['nullable', 'image', 'max:2048'],
            'participation_mode'  => ['required', 'in:once,par_jour,illimite'],
            'participation_nb'    => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        SiteSetting::set('couleur_bleu',       $request->couleur_bleu);
        SiteSetting::set('couleur_bleu_fonce', $request->couleur_bleu_fonce);
        SiteSetting::set('couleur_orange',     $request->couleur_orange);
        SiteSetting::set('site_titre',         $request->site_titre);
        SiteSetting::set('site_sous_titre',    $request->site_sous_titre);
        SiteSetting::set('participation_mode', $request->participation_mode);
        SiteSetting::set('participation_nb',   $request->participation_nb);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->storeAs('', 'logo.gif', 'public_img');
            // Store in public/img/logo.gif
            $request->file('logo')->move(public_path('img'), 'logo.gif');
            SiteSetting::set('logo_path', 'img/logo.gif');
        }

        return back()->with('success', 'Paramètres enregistrés avec succès !');
    }

    public function resetColors()
    {
        $defaults = SiteSetting::defaults();
        SiteSetting::set('couleur_bleu',       $defaults['couleur_bleu']);
        SiteSetting::set('couleur_bleu_fonce', $defaults['couleur_bleu_fonce']);
        SiteSetting::set('couleur_orange',     $defaults['couleur_orange']);

        return back()->with('success', 'Couleurs réinitialisées aux couleurs du club.');
    }
}
