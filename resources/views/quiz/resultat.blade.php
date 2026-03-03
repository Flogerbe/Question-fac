@extends('layouts.fac')
@section('title', 'Résultat - FAC Quiz')
@section('styles')
<style>
.result-wrap{max-width:650px;margin:2rem auto;padding:1.5rem;text-align:center;}
.result-icon{font-size:4rem;margin-bottom:1rem;}
.result-score{font-size:3.5rem;font-weight:900;color:var(--orange);line-height:1;margin:.5rem 0;}
.result-pts{font-size:1rem;color:#888;margin-bottom:1.5rem;}
.result-rank{background:linear-gradient(135deg,#2a1a00,#3d2800);border:2px solid var(--or);border-radius:12px;padding:1.2rem;margin-bottom:1.5rem;}
.result-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:1.5rem;}
.stat-box{background:var(--gris-card);border-radius:10px;padding:1rem;border:1px solid rgba(244,120,32,.2);}
.stat-val{font-size:1.5rem;font-weight:700;color:var(--orange);}
.stat-lbl{font-size:.8rem;color:#888;margin-top:.2rem;}
.result-actions{display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;margin-top:1.5rem;}
@keyframes confetti{0%{transform:translateY(-10px);opacity:0;}100%{transform:translateY(0);opacity:1;}}
.result-title{animation:confetti .5s ease;}
</style>
@endsection

@section('content')
<div class="result-wrap">
    @if($correctAnswers >= 15)
        <div class="result-icon">🏆</div>
        <h1 style="color:var(--or);font-size:2rem;" class="result-title">Excellent !</h1>
    @elseif($correctAnswers >= 10)
        <div class="result-icon">🥈</div>
        <h1 style="color:#C0C0C0;font-size:2rem;" class="result-title">Bien joué !</h1>
    @else
        <div class="result-icon">🏃</div>
        <h1 style="color:var(--orange);font-size:2rem;" class="result-title">Bonne participation !</h1>
    @endif

    <div class="result-score">{{ number_format($gameSession->score, 0, ',', ' ') }}</div>
    <div class="result-pts">points au total</div>

    @if($rank && $gameSession->counted)
    <div class="result-rank">
        <span style="font-size:1.5rem;">🏅</span>
        <div style="font-size:1.1rem;color:var(--or);font-weight:700;margin-top:.3rem;">
            {{ $rank === 1 ? '🥇 1ère place !' : ($rank === 2 ? '🥈 2ème place !' : ($rank === 3 ? '🥉 3ème place !' : $rank.'ème place')) }}
        </div>
        <div style="font-size:.85rem;color:#aaa;margin-top:.3rem;">au classement général</div>
    </div>
    @elseif(!$gameSession->counted)
    <div style="background:var(--gris-dark);border-radius:10px;padding:1rem;margin-bottom:1.5rem;color:var(--texte-mut);font-size:.9rem;">
        Votre score a bien été enregistré pour la soirée.
    </div>
    @endif

    <div class="result-stats">
        <div class="stat-box">
            <div class="stat-val">{{ $correctAnswers }}/{{ $totalQuestions }}</div>
            <div class="stat-lbl">Bonnes réponses</div>
        </div>
        <div class="stat-box">
            <div class="stat-val">{{ floor($gameSession->temps_total/60) }}m {{ $gameSession->temps_total%60 }}s</div>
            <div class="stat-lbl">Temps total</div>
        </div>
        <div class="stat-box">
            <div class="stat-val">
                {{ ($gameSession->joker_fifty ? 1 : 0) + ($gameSession->joker_public ? 1 : 0) + ($gameSession->joker_coach ? 1 : 0) }}/3
            </div>
            <div class="stat-lbl">Jokers utilisés</div>
        </div>
    </div>

    <div style="background:var(--gris-card);border-radius:10px;padding:1rem;margin-bottom:1.5rem;font-size:.9rem;color:var(--texte-mut);">
        <strong style="color:var(--orange);">{{ $gameSession->player->prenom }}</strong>, merci d'avoir participé au Quiz FAC Andrézieux !
        @if($correctAnswers >= 15)
        Vous faites partie des meilleurs joueurs de la soirée — des cadeaux vous attendent peut-être ! 🎁
        @else
        Consultez le classement pour voir votre position finale !
        @endif
    </div>

    <div class="result-actions">
        <a href="{{ route('classement') }}" class="btn btn-orange">🏆 Voir le classement</a>
        <a href="{{ route('home') }}" class="btn btn-outline">🏠 Accueil</a>
        <a href="{{ route('jersey.index') }}" class="btn btn-outline">👕 Voter pour le maillot</a>
    </div>
</div>
@endsection
