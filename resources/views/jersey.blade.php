@extends('layouts.fac')
@section('title', 'Vote Maillot - FAC Andrézieux')
@section('styles')
<style>
.jersey-hero{background:linear-gradient(135deg,var(--bleu),var(--gris-dark));padding:3rem 2rem;text-align:center;border-bottom:2px solid rgba(244,120,32,.2);}
.jersey-hero h1{color:var(--orange);font-size:2.2rem;font-weight:900;margin-bottom:.5rem;}
.jersey-hero p{color:var(--texte-mut);font-size:1rem;}
.options-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1.5rem;padding:2rem;}
.option-card{background:var(--gris-card);border-radius:14px;overflow:hidden;border:2px solid rgba(244,120,32,.2);transition:all .25s;position:relative;}
.option-card:hover{border-color:var(--orange);transform:translateY(-4px);box-shadow:0 8px 25px rgba(244,120,32,.2);}
.option-card.voted{border-color:var(--vert);}
.option-img{height:200px;display:flex;align-items:center;justify-content:center;font-size:5rem;background:linear-gradient(135deg,var(--gris-dark),var(--gris-card));}
.option-img img{width:100%;height:100%;object-fit:cover;}
.option-body{padding:1.2rem;}
.option-name{font-size:1.1rem;font-weight:700;color:var(--blanc);margin-bottom:.3rem;}
.option-colors{font-size:.8rem;color:var(--texte-mut);margin-bottom:.8rem;font-family:monospace;}
.option-desc{font-size:.9rem;color:var(--texte-mut);line-height:1.5;margin-bottom:1rem;}
.vote-bar-wrap{background:rgba(255,255,255,.1);border-radius:4px;height:8px;margin-bottom:.4rem;overflow:hidden;}
.vote-bar{height:100%;background:linear-gradient(90deg,var(--orange),var(--orange-lt));border-radius:4px;transition:width 1s ease;}
.vote-pct{font-size:.8rem;color:var(--texte-mut);margin-bottom:.8rem;}
.voted-badge{position:absolute;top:1rem;right:1rem;background:var(--vert);color:#fff;font-size:.75rem;font-weight:700;padding:.2rem .6rem;border-radius:20px;}
.total-votes{text-align:center;color:var(--texte-mut);font-size:.85rem;padding:.5rem 0 1.5rem;}
</style>
@endsection

@section('content')
<div class="jersey-hero">
    <h1>👕 Vote pour le nouveau maillot</h1>
    <p>Choisissez votre design préféré pour le prochain maillot du FAC Andrézieux</p>
    <div style="margin-top:.8rem;color:#666;font-size:.85rem;">
        {{ $totalVotes }} vote{{ $totalVotes > 1 ? 's' : '' }} au total
        @if($hasVoted) &bull; <span style="color:var(--vert);">Vous avez déjà voté ✓</span> @endif
    </div>
</div>

<div class="total-votes">
    @if($hasVoted)
        <strong style="color:var(--vert);">Merci pour votre vote !</strong> Les résultats seront annoncés lors de l'AG.
    @else
        <strong style="color:var(--orange);">Votez pour votre maillot préféré !</strong>
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
                <img src="{{ asset($option->image_path) }}" alt="{{ $option->nom }}" onerror="this.parentElement.innerHTML='👕'">
            @else
                <span>👕</span>
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
                <div class="vote-pct">{{ $pct }}% ({{ $option->votes_count }} vote{{ $option->votes_count > 1 ? 's' : '' }})</div>
            @else
                <form action="{{ route('jersey.voter') }}" method="POST">
                    @csrf
                    <input type="hidden" name="option_id" value="{{ $option->id }}">
                    <button type="submit" class="btn btn-orange" style="width:100%;">Voter pour ce maillot</button>
                </form>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endsection
