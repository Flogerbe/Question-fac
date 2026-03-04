@extends('layouts.fac')
@section('title', 'FAC Andrézieux - Quiz AG')

@section('styles')
<style>
.home-wrap { max-width: 760px; margin: 0 auto; padding: 3rem 1.5rem 4rem; text-align: center; }

.home-logo { height: 120px; width: auto; filter: drop-shadow(0 2px 16px rgba(234,79,42,.45)); margin-bottom: 1.5rem; }

.home-title { font-size: 2.4rem; font-weight: 900; color: #fff; letter-spacing: -.5px; margin-bottom: .5rem; }
.home-title span { color: var(--orange); }
.home-sub { font-size: 1rem; color: var(--texte-mut); margin-bottom: 2.5rem; }

.home-btns { display: flex; flex-direction: column; gap: .9rem; max-width: 380px; margin: 0 auto 2.5rem; }
.home-btns .btn { width: 100%; justify-content: center; font-size: 1rem; padding: .9rem 1.5rem; border-radius: 10px; }

.divider { border: none; border-top: 1px solid rgba(255,255,255,.07); margin: 2.5rem 0; }

.prize-box { background: rgba(255,215,0,.06); border: 1px solid rgba(255,215,0,.25); border-radius: 12px; padding: 1.2rem 1.5rem; margin-bottom: 2.5rem; }
.prize-box p { font-size: .9rem; color: rgba(255,255,255,.7); line-height: 1.6; }
.prize-box strong { color: var(--or); }

.scores-title { font-size: 1.3rem; font-weight: 800; color: #fff; margin-bottom: .4rem; }
.scores-title span { color: var(--orange); }
.max-score-hint { font-size: .8rem; color: var(--texte-mut); margin-bottom: 1.2rem; }
.max-score-hint strong { color: var(--orange); }

.scores-list { display: flex; flex-direction: column; gap: .6rem; }
.score-row { display: flex; align-items: center; gap: 1rem; background: var(--gris-card); border-radius: 10px; padding: .85rem 1.2rem; border: 1px solid rgba(255,255,255,.06); }
.score-row.gold   { border-color: var(--or); }
.score-row.silver { border-color: #aaa; }
.score-row.bronze { border-color: #cd7f32; }
.score-medal { font-size: 1.4rem; width: 32px; text-align: center; flex-shrink: 0; }
.score-name { flex: 1; font-weight: 700; color: #fff; text-align: left; }
.score-pts  { font-weight: 900; color: var(--orange); font-size: 1.05rem; }
.score-time { font-size: .78rem; color: var(--texte-mut); margin-left: .5rem; }

/* Tirage winners */
.tirage-section { margin-top: 2.5rem; }
.tirage-title { font-size: 1.3rem; font-weight: 800; color: #fff; margin-bottom: 1.2rem; }
.tirage-title span { color: var(--or); }
.tirage-group { background: var(--gris-card); border-radius: 12px; padding: 1.2rem 1.4rem; margin-bottom: 1rem; border: 1px solid rgba(255,215,0,.2); text-align: left; }
.tirage-group-label { font-size: .78rem; font-weight: 700; color: var(--or); text-transform: uppercase; letter-spacing: .5px; margin-bottom: .7rem; }
.tirage-winner-row { display: flex; align-items: center; gap: .8rem; padding: .4rem 0; border-bottom: 1px solid rgba(255,255,255,.05); }
.tirage-winner-row:last-child { border-bottom: none; }
.tirage-rang { font-size: 1.2rem; width: 28px; flex-shrink: 0; }
.tirage-name { flex: 1; font-weight: 700; color: #fff; }
.tirage-pts { font-weight: 900; color: var(--orange); font-size: .95rem; }

.participants-hint { font-size: .85rem; color: var(--texte-mut); margin-bottom: 1.2rem; }

.empty-cta { color: var(--texte-mut); font-size: .95rem; margin-bottom: 1.2rem; }

@media(max-width: 600px) {
    .home-wrap { padding: 2rem 1rem 3rem; }
    .home-sub { font-size: .9rem; margin-bottom: 2rem; }
    .home-btns { max-width: 100%; }
    .prize-box { padding: 1rem; }
    .prize-box p { font-size: .85rem; }
    .score-row { padding: .7rem 1rem; gap: .7rem; }
    .score-medal { font-size: 1.2rem; width: 26px; }
    .score-pts { font-size: .95rem; }
}
@media(max-width: 480px) {
    .home-title { font-size: 1.7rem; }
    .home-logo { height: 80px; }
    .home-btns .btn { padding: .85rem 1.2rem; font-size: .95rem; }
    .score-time { display: none; }
}
</style>
@endsection

@section('content')
@php
$typeLabels = [
    'esprit_club' => ['emoji' => '🎟️', 'label' => 'Esprit Club'],
    'champion'    => ['emoji' => '🏅', 'label' => '100% Champion'],
    'bonus'       => ['emoji' => '🎁', 'label' => 'Bonus'],
];
$medals = ['🥇','🥈','🥉','4️⃣','5️⃣','6️⃣','7️⃣','8️⃣','9️⃣','🔟'];
@endphp

<div class="home-wrap">

    <img src="{{ asset($S['logo_path']) }}" alt="FAC Andrézieux" class="home-logo">

    <h1 class="home-title">{{ $S['site_titre'] }}</h1>
    <p class="home-sub">{{ $S['site_sous_titre'] }} &mdash; AG {{ date('Y') }}</p>

    <div class="home-btns">
        <a href="{{ route('quiz.index') }}" class="btn btn-orange btn-lg">🎮 Jouer au Quiz</a>
        @if($S['vote_visible'] === '1')
        <a href="{{ route('jersey.index') }}" class="btn btn-outline">👕 {{ $S['vote_label'] }}</a>
        @endif
        <a href="{{ route('regles') }}" class="btn btn-outline">📖 Règles du jeu</a>
    </div>

    <div class="prize-box">
        <strong>🎁 Des cadeaux à gagner !</strong>
        <p>Les meilleurs scores remporteront des lots surprises.<br>Répondez vite et bien pour maximiser vos points !</p>
    </div>

    {{-- Gagnants du tirage au sort --}}
    @if($tirageWinners->isNotEmpty())
    <div class="tirage-section">
        <h2 class="tirage-title">🎉 <span>Gagnants</span> du tirage</h2>
        @foreach($typeLabels as $type => $info)
            @if($tirageWinners->has($type))
            <div class="tirage-group">
                <div class="tirage-group-label">{{ $info['emoji'] }} {{ $info['label'] }}</div>
                @foreach($tirageWinners[$type] as $i => $result)
                <div class="tirage-winner-row">
                    <div class="tirage-rang">{{ $medals[$i] ?? ($i+1) }}</div>
                    <div class="tirage-name">{{ $result->gameSession->player->full_name }}</div>
                    <div class="tirage-pts">{{ number_format($result->gameSession->score, 0, ',', ' ') }} pts</div>
                </div>
                @endforeach
            </div>
            @endif
        @endforeach
    </div>
    <hr class="divider">
    @endif

    {{-- Classement / participants --}}
    @if($S['classement_mode'] === 'tirage')
        <h2 class="scores-title">👥 <span>Participants</span> au tirage</h2>
        <p class="participants-hint">
            <strong style="color:var(--orange);">{{ $totalParticipants }}</strong> participant{{ $totalParticipants > 1 ? 's' : '' }} inscrits
            &bull; score max possible : <strong style="color:var(--orange);">{{ number_format($maxScore, 0, ',', ' ') }} pts</strong>
        </p>
        <a href="{{ route('classement') }}" class="btn btn-outline btn-sm">Voir tous les participants</a>
    @else
        @if($topScores->count() > 0)
            <h2 class="scores-title">🏆 <span>Classement</span> en cours</h2>
            <p class="max-score-hint">Score max possible : <strong>{{ number_format($maxScore, 0, ',', ' ') }} pts</strong></p>
            <div class="scores-list">
                @foreach($topScores as $i => $session)
                @php $cls = ['gold','silver','bronze'][$i] ?? ''; @endphp
                <div class="score-row {{ $cls }}">
                    <div class="score-medal">{{ ['🥇','🥈','🥉','4️⃣','5️⃣'][$i] ?? ($i+1) }}</div>
                    <div class="score-name">{{ $session->player->full_name }}</div>
                    <div class="score-pts">{{ number_format($session->score, 0, ',', ' ') }} pts</div>
                    <div class="score-time">{{ floor($session->temps_total/60) }}m {{ $session->temps_total%60 }}s</div>
                </div>
                @endforeach
            </div>
            <div style="margin-top:1.2rem;">
                <a href="{{ route('classement') }}" class="btn btn-outline btn-sm">Voir tout le classement</a>
            </div>
        @else
            <p class="empty-cta">Aucune partie jouée pour l'instant — soyez le premier !</p>
            <a href="{{ route('quiz.index') }}" class="btn btn-orange">Commencer le quiz</a>
        @endif
    @endif

</div>
@endsection
