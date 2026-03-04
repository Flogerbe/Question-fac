@extends('layouts.fac')
@section('title', 'Règles du jeu - FAC Quiz')
@section('content')
<div class="container">
    <h1 style="color:var(--orange);font-size:2.2rem;margin-bottom:2rem;text-align:center;">📖 Règles du jeu</h1>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:1.5rem;">
        <div class="card">
            <h2 style="color:var(--orange);margin-bottom:1rem;">🎯 Objectif</h2>
            <p style="color:#ccc;line-height:1.7;">Répondez aux <strong style="color:var(--orange);">20 questions</strong> le plus rapidement possible. Votre score dépend de la <strong>justesse</strong> de vos réponses ET de votre <strong>vitesse</strong>.</p>
        </div>
        <div class="card">
            <h2 style="color:var(--orange);margin-bottom:1rem;">⏱️ Chronomètre</h2>
            <p style="color:#ccc;line-height:1.7;">Chaque question dispose de <strong style="color:var(--orange);">30 secondes</strong> pour répondre. Plus vous répondez vite, plus vous gagnez de points bonus.</p>
        </div>
        <div class="card">
            <h2 style="color:var(--orange);margin-bottom:1rem;">🏆 Score</h2>
            <p style="color:#ccc;line-height:1.7;">Bonne réponse : <strong style="color:var(--vert);">500 points</strong> de base + jusqu'à <strong style="color:var(--vert);">500 points de bonus</strong> selon la vitesse. Mauvaise réponse = 0 point.</p>
        </div>
        <div class="card">
            <h2 style="color:var(--orange);margin-bottom:1rem;">🃏 Les 3 Jokers</h2>
            <ul style="color:#ccc;line-height:2;list-style:none;">
                <li>✂️ <strong style="color:var(--or);">50/50</strong> — Élimine 2 mauvaises réponses</li>
                <li>👥 <strong style="color:var(--or);">Vote du public</strong> — Affiche ce que "le public" aurait voté</li>
                <li>🎓 <strong style="color:var(--or);">Question au coach</strong> — Le coach vous donne un indice</li>
            </ul>
            <p style="color:#888;font-size:.85rem;margin-top:.8rem;">Chaque joker ne peut être utilisé qu'une seule fois par partie.</p>
        </div>
        <div class="card">
            <h2 style="color:var(--orange);margin-bottom:1rem;">👤 Participation</h2>
            <p style="color:#ccc;line-height:1.7;">Saisissez simplement votre <strong style="color:var(--orange);">prénom et nom</strong> pour commencer. <strong>Une seule participation</strong> par personne est prise en compte pour le classement et les tirages.</p>
        </div>
        <div class="card">
            <h2 style="color:var(--orange);margin-bottom:1rem;">🎲 Tirage au sort</h2>
            <ul style="color:#ccc;line-height:2;list-style:none;">
                <li>🎟️ <strong style="color:var(--or);">Esprit Club</strong> — Tous les participants sont tirés au sort</li>
                <li>🏅 <strong style="color:var(--or);">100% Champion</strong> — Réservé aux participants sans faute</li>
                <li>🎁 <strong style="color:var(--or);">Bonus</strong> — Une 2e chance pour tous</li>
            </ul>
            <p style="color:#888;font-size:.85rem;margin-top:.8rem;">Les gagnants sont désignés par tirage au sort parmi les participants éligibles.</p>
        </div>
    </div>
    <div style="text-align:center;margin-top:2.5rem;">
        <a href="{{ route('quiz.index') }}" class="btn btn-orange" style="font-size:1.1rem;padding:1rem 3rem;">🎮 Jouer maintenant</a>
    </div>
</div>
@endsection
