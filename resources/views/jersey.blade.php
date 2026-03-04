@extends('layouts.fac')
@section('title', '{{ $S["vote_label"] }} - FAC Andrézieux')
@section('styles')
<style>
.jersey-hero{background:linear-gradient(135deg,var(--bleu),var(--gris-dark));padding:3rem 2rem;text-align:center;border-bottom:2px solid rgba(244,120,32,.2);}
.jersey-hero h1{color:var(--orange);font-size:2.2rem;font-weight:900;margin-bottom:.5rem;}
.jersey-hero p{color:var(--texte-mut);font-size:1rem;}

.options-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:2rem;padding:2rem;}

.option-card{background:var(--gris-card);border-radius:16px;overflow:hidden;border:2px solid rgba(244,120,32,.2);transition:all .25s;position:relative;display:flex;flex-direction:column;}
.option-card:hover{border-color:var(--orange);transform:translateY(-4px);box-shadow:0 10px 30px rgba(244,120,32,.25);}
.option-card.voted{border-color:var(--vert);box-shadow:0 0 0 3px rgba(34,197,94,.15);}

/* Zone image — fond blanc pour voir le maillot sur fond clair */
.option-img{height:320px;display:flex;align-items:center;justify-content:center;background:#fff;position:relative;overflow:hidden;}
.option-img img{width:100%;height:100%;object-fit:contain;padding:1.2rem;transition:transform .3s;}
.option-card:hover .option-img img{transform:scale(1.04);}
.option-img-placeholder{font-size:6rem;display:flex;align-items:center;justify-content:center;width:100%;height:100%;background:linear-gradient(135deg,var(--gris-dark),var(--gris-card));}

.option-body{padding:1.4rem;flex:1;display:flex;flex-direction:column;}
.option-name{font-size:1.15rem;font-weight:800;color:var(--blanc);margin-bottom:.3rem;}
.option-colors{font-size:.8rem;color:var(--texte-mut);margin-bottom:.6rem;font-family:monospace;}
.option-desc{font-size:.9rem;color:var(--texte-mut);line-height:1.6;margin-bottom:1rem;flex:1;}

.vote-bar-wrap{background:rgba(255,255,255,.1);border-radius:4px;height:10px;margin-bottom:.5rem;overflow:hidden;}
.vote-bar{height:100%;background:linear-gradient(90deg,var(--orange),var(--orange-lt));border-radius:4px;transition:width 1.2s ease;}
.vote-pct{font-size:.85rem;color:var(--texte-mut);margin-bottom:1rem;font-weight:600;}

.voted-badge{position:absolute;top:.9rem;right:.9rem;background:var(--vert);color:#fff;font-size:.75rem;font-weight:700;padding:.3rem .7rem;border-radius:20px;z-index:2;}

.total-votes{text-align:center;color:var(--texte-mut);font-size:.9rem;padding:.6rem 0 1.2rem;}

@media(max-width:700px){
    .options-grid{grid-template-columns:1fr;padding:1.2rem;}
    .option-img{height:260px;}
}
@media(max-width:400px){
    .jersey-hero h1{font-size:1.7rem;}
    .option-img{height:220px;}
}
</style>
@endsection

@section('content')
<div class="jersey-hero">
    <h1>👕 {{ $S['vote_label'] }}</h1>
    <p>Choisissez votre préféré parmi les options ci-dessous</p>
    <div style="margin-top:.8rem;color:#888;font-size:.85rem;">
        {{ $totalVotes }} vote{{ $totalVotes > 1 ? 's' : '' }} au total
        @if($hasVoted) &bull; <span style="color:var(--vert);">Vous avez déjà voté ✓</span> @endif
    </div>
</div>

<div class="total-votes">
    @if($hasVoted)
        <strong style="color:var(--vert);">Merci pour votre vote !</strong> Les résultats seront annoncés lors de l'AG.
    @else
        <strong style="color:var(--orange);">Votez pour votre choix préféré !</strong>
    @endif
</div>

<div class="options-grid">
    @foreach($options as $option)
    @php
        $pct = $totalVotes > 0 ? round(($option->votes_count / $totalVotes) * 100) : 0;
        $isMyVote = $userVoteOptionId == $option->id;
    @endphp
    <div class="option-card {{ $isMyVote ? 'voted' : '' }}">
        @if($isMyVote)
            <div class="voted-badge">Votre choix ✓</div>
        @endif

        <div class="option-img">
            @if($option->image_path)
                <img src="{{ asset('storage/' . $option->image_path) }}" alt="{{ $option->nom }}">
            @else
                <div class="option-img-placeholder">👕</div>
            @endif
        </div>

        <div class="option-body">
            <div class="option-name">{{ $option->nom }}</div>
            @if($option->couleurs)
                <div class="option-colors">{{ $option->couleurs }}</div>
            @endif
            @if($option->description)
                <div class="option-desc">{{ $option->description }}</div>
            @endif

            @if($hasVoted)
                <div class="vote-bar-wrap">
                    <div class="vote-bar" style="width:{{ $pct }}%"></div>
                </div>
                <div class="vote-pct">{{ $pct }}% — {{ $option->votes_count }} vote{{ $option->votes_count > 1 ? 's' : '' }}</div>
            @else
                <form action="{{ route('jersey.voter') }}" method="POST" style="margin-top:auto;">
                    @csrf
                    <input type="hidden" name="option_id" value="{{ $option->id }}">
                    <button type="submit" class="btn btn-orange" style="width:100%;">Voter pour ce choix</button>
                </form>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endsection
