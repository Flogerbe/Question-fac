@extends('layouts.fac')
@section('title', 'Classement - FAC Quiz')

@section('styles')
<style>
.classement-wrap{max-width:700px;margin:0 auto;padding:2.5rem 1.5rem;}
.classement-wrap h1{text-align:center;color:#fff;font-size:2rem;font-weight:900;margin-bottom:2.5rem;}
.classement-wrap h1 span{color:var(--orange);}

/* Podium top 3 */
.podium{display:flex;align-items:flex-end;justify-content:center;gap:1rem;margin-bottom:2.5rem;}
.podium-item{flex:1;max-width:200px;text-align:center;}
.podium-card{border-radius:12px 12px 0 0;padding:1.2rem .8rem .8rem;position:relative;}
.podium-item.p1 .podium-card{background:linear-gradient(135deg,#2a1e00,#1a1200);border:2px solid var(--or);padding-top:1.8rem;}
.podium-item.p2 .podium-card{background:var(--gris-card);border:2px solid #a0a0a0;border-bottom:none;}
.podium-item.p3 .podium-card{background:var(--gris-card);border:2px solid #a0522d;border-bottom:none;}
.podium-medal{font-size:2rem;display:block;margin-bottom:.4rem;}
.podium-name{font-weight:800;font-size:1rem;color:#fff;margin-bottom:.2rem;}
.podium-pts{font-weight:900;font-size:1.15rem;color:var(--orange);}
.podium-time{font-size:.75rem;color:var(--texte-mut);margin-top:.2rem;}
.podium-base{height:12px;border-radius:0 0 6px 6px;}
.podium-item.p1 .podium-base{background:var(--or);height:18px;}
.podium-item.p2 .podium-base{background:#a0a0a0;}
.podium-item.p3 .podium-base{background:#a0522d;}
/* Taille des colonnes du podium */
.podium-item.p2{margin-bottom:24px;}
.podium-item.p3{margin-bottom:8px;}

/* Liste 4+ */
.score-list{display:flex;flex-direction:column;gap:.5rem;}
.score-row{display:flex;align-items:center;gap:.8rem;background:var(--gris-card);border-radius:10px;padding:.75rem 1rem;border:1px solid rgba(255,255,255,.05);}
.score-row:hover{background:color-mix(in srgb,var(--gris-card) 80%,white 20%);}
.sr-rank{width:32px;text-align:center;font-weight:700;color:var(--texte-mut);font-size:.9rem;flex-shrink:0;}
.sr-name{flex:1;font-weight:600;color:#fff;}
.sr-pts{font-weight:900;color:var(--orange);font-size:1rem;}
.sr-time{font-size:.78rem;color:var(--texte-mut);margin-left:.4rem;}
.sr-jokers{font-size:.85rem;margin-left:.3rem;}

.empty-state{text-align:center;padding:4rem 1.5rem;color:var(--texte-mut);}
.empty-state p{font-size:3rem;margin-bottom:1rem;}

@media(max-width:600px){
    .classement-wrap{padding:1.5rem 1rem;}
    .classement-wrap h1{font-size:1.6rem;margin-bottom:1.5rem;}
    .podium{gap:.5rem;margin-bottom:1.5rem;}
    .podium-item{max-width:none;}
    .podium-card{padding:.9rem .5rem .6rem;}
    .podium-item.p1 .podium-card{padding-top:1.4rem;}
    .podium-medal{font-size:1.5rem;}
    .podium-name{font-size:.85rem;}
    .podium-pts{font-size:1rem;}
    .podium-time{font-size:.7rem;}
    .score-row{padding:.6rem .8rem;gap:.6rem;}
    .sr-name{font-size:.9rem;}
    .sr-pts{font-size:.9rem;}
    .sr-time{display:none;}
}
@media(max-width:400px){
    .podium-item.p2{margin-bottom:16px;}
    .podium-item.p3{margin-bottom:4px;}
    .podium-name{font-size:.8rem;}
    .podium-pts{font-size:.9rem;}
}
</style>
@endsection

@section('content')
<div class="classement-wrap">
    <h1>🏆 <span>Classement</span> général</h1>

    @if($scores->count() === 0)
        <div class="empty-state">
            <p>🎮</p>
            <span>Aucune partie terminée pour l'instant — soyez le premier !</span><br><br>
            <a href="{{ route('quiz.index') }}" class="btn btn-orange" style="margin-top:1rem;">Jouer maintenant</a>
        </div>
    @else

        @php $top3 = $scores->take(3); $rest = $scores->skip(3); @endphp

        {{-- Podium top 3 --}}
        <div class="podium">
            {{-- 2e place (gauche) --}}
            @if($top3->count() >= 2)
            @php $s = $top3->get(1); @endphp
            <div class="podium-item p2">
                <div class="podium-card">
                    <span class="podium-medal">🥈</span>
                    <div class="podium-name">{{ $s->player->prenom }}</div>
                    <div class="podium-pts">{{ number_format($s->score, 0, ',', ' ') }} pts</div>
                    <div class="podium-time">{{ floor($s->temps_total/60) }}m {{ $s->temps_total%60 }}s</div>
                </div>
                <div class="podium-base"></div>
            </div>
            @endif

            {{-- 1re place (centre, plus haute) --}}
            @php $s = $top3->get(0); @endphp
            <div class="podium-item p1">
                <div class="podium-card">
                    <span class="podium-medal">🥇</span>
                    <div class="podium-name">{{ $s->player->prenom }}</div>
                    <div class="podium-pts">{{ number_format($s->score, 0, ',', ' ') }} pts</div>
                    <div class="podium-time">{{ floor($s->temps_total/60) }}m {{ $s->temps_total%60 }}s</div>
                </div>
                <div class="podium-base"></div>
            </div>

            {{-- 3e place (droite) --}}
            @if($top3->count() >= 3)
            @php $s = $top3->get(2); @endphp
            <div class="podium-item p3">
                <div class="podium-card">
                    <span class="podium-medal">🥉</span>
                    <div class="podium-name">{{ $s->player->prenom }}</div>
                    <div class="podium-pts">{{ number_format($s->score, 0, ',', ' ') }} pts</div>
                    <div class="podium-time">{{ floor($s->temps_total/60) }}m {{ $s->temps_total%60 }}s</div>
                </div>
                <div class="podium-base"></div>
            </div>
            @endif
        </div>

        {{-- Reste du classement (4e et au-delà) --}}
        @if($rest->count() > 0)
        <div class="score-list">
            @foreach($rest as $i => $session)
            <div class="score-row">
                <div class="sr-rank">{{ $i + 4 }}</div>
                <div class="sr-name">{{ $session->player->prenom }}</div>
                <div class="sr-pts">{{ number_format($session->score, 0, ',', ' ') }} pts</div>
                <div class="sr-time">{{ floor($session->temps_total/60) }}m {{ $session->temps_total%60 }}s</div>
                <div class="sr-jokers">
                    {{ $session->joker_fifty ? '✂️' : '' }}{{ $session->joker_public ? '👥' : '' }}{{ $session->joker_coach ? '🎓' : '' }}
                </div>
            </div>
            @endforeach
        </div>
        @endif

    @endif
</div>
@endsection
