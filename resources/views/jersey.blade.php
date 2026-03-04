@extends('layouts.fac')
@section('title', $S['vote_label'] . ' - FAC Andrézieux')
@section('styles')
<style>
/* ── Hero ── */
.jersey-hero{background:linear-gradient(135deg,var(--bleu),var(--gris-dark));padding:2.5rem 2rem 1.8rem;text-align:center;border-bottom:2px solid rgba(244,120,32,.2);}
.jersey-hero h1{color:var(--orange);font-size:2.2rem;font-weight:900;margin-bottom:.4rem;}
.jersey-hero p{color:var(--texte-mut);font-size:.95rem;}

/* ── Barre de contrôle ── */
.vote-bar-top{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;padding:1rem 2rem;background:rgba(0,0,0,.2);border-bottom:1px solid rgba(244,120,32,.1);}
.vote-status{font-size:.9rem;color:var(--texte-mut);}
.view-toggle{display:flex;gap:.3rem;background:rgba(0,0,0,.3);border-radius:8px;padding:3px;}
.view-btn{background:transparent;border:none;color:var(--texte-mut);padding:.35rem .65rem;border-radius:6px;cursor:pointer;font-size:.85rem;transition:all .2s;white-space:nowrap;}
.view-btn.active{background:var(--orange);color:#fff;}
.view-btn:hover:not(.active){background:rgba(255,255,255,.1);color:var(--blanc);}

/* ── Conteneur options ── */
#options-container{padding:1.5rem 2rem 2rem;}

/* ══ MODE GRILLE ══ */
.mode-grille{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1.8rem;}
.mode-grille .opt{background:var(--gris-card);border-radius:16px;overflow:hidden;border:2px solid rgba(244,120,32,.15);transition:all .25s;position:relative;display:flex;flex-direction:column;}
.mode-grille .opt:hover{border-color:var(--orange);transform:translateY(-4px);box-shadow:0 12px 32px rgba(244,120,32,.22);}
.mode-grille .opt.voted{border-color:var(--vert);box-shadow:0 0 0 3px rgba(34,197,94,.15);}
.mode-grille .opt-img-wrap{height:280px;background:#f8f8f8;position:relative;overflow:hidden;display:flex;align-items:center;justify-content:center;}
.mode-grille .opt-img-wrap img{width:100%;height:100%;object-fit:contain;padding:1rem;transition:transform .3s;}
.mode-grille .opt:hover .opt-img-wrap img{transform:scale(1.05);}
.mode-grille .opt-body{padding:1.2rem;flex:1;display:flex;flex-direction:column;}

/* ══ MODE LISTE ══ */
.mode-liste{display:flex;flex-direction:column;gap:1rem;}
.mode-liste .opt{background:var(--gris-card);border-radius:14px;overflow:hidden;border:2px solid rgba(244,120,32,.15);transition:all .2s;position:relative;display:flex;flex-direction:row;}
.mode-liste .opt:hover{border-color:var(--orange);box-shadow:0 6px 20px rgba(244,120,32,.18);}
.mode-liste .opt.voted{border-color:var(--vert);}
.mode-liste .opt-img-wrap{width:180px;flex-shrink:0;background:#f8f8f8;display:flex;align-items:center;justify-content:center;overflow:hidden;}
.mode-liste .opt-img-wrap img{width:100%;height:100%;object-fit:contain;padding:.8rem;}
.mode-liste .opt-body{padding:1.2rem;flex:1;display:flex;flex-direction:column;justify-content:center;}

/* ══ MODE COMPARAISON ══ */
.mode-comparaison{display:grid;grid-template-columns:repeat(auto-fit,minmax(340px,1fr));gap:0;border-radius:16px;overflow:hidden;border:2px solid rgba(244,120,32,.2);}
.mode-comparaison .opt{background:var(--gris-card);position:relative;display:flex;flex-direction:column;border-right:1px solid rgba(244,120,32,.15);}
.mode-comparaison .opt:last-child{border-right:none;}
.mode-comparaison .opt.voted{background:linear-gradient(180deg,rgba(34,197,94,.07),var(--gris-card));}
.mode-comparaison .opt-img-wrap{height:400px;background:#f8f8f8;display:flex;align-items:center;justify-content:center;overflow:hidden;}
.mode-comparaison .opt-img-wrap img{width:100%;height:100%;object-fit:contain;padding:1.5rem;transition:transform .4s;}
.mode-comparaison .opt:hover .opt-img-wrap img{transform:scale(1.04);}
.mode-comparaison .opt-body{padding:1.5rem;flex:1;display:flex;flex-direction:column;border-top:2px solid rgba(244,120,32,.15);}

/* ── Éléments communs ── */
.opt-name{font-size:1.1rem;font-weight:800;color:var(--blanc);margin-bottom:.25rem;}
.opt-colors{font-size:.78rem;color:var(--texte-mut);margin-bottom:.4rem;font-family:monospace;}
.opt-desc{font-size:.88rem;color:var(--texte-mut);line-height:1.6;margin-bottom:.8rem;flex:1;}
.opt-bar-wrap{background:rgba(255,255,255,.1);border-radius:4px;height:8px;margin-bottom:.4rem;overflow:hidden;}
.opt-bar{height:100%;background:linear-gradient(90deg,var(--orange),var(--orange-lt));border-radius:4px;transition:width 1s ease;}
.opt-pct{font-size:.82rem;color:var(--texte-mut);margin-bottom:.8rem;font-weight:600;}
.voted-badge{position:absolute;top:.8rem;right:.8rem;background:var(--vert);color:#fff;font-size:.72rem;font-weight:700;padding:.25rem .6rem;border-radius:20px;z-index:2;}
.img-placeholder{font-size:5rem;display:none;align-items:center;justify-content:center;width:100%;height:100%;background:linear-gradient(135deg,var(--gris-dark),var(--gris-card));color:var(--texte-mut);}
.mode-comparaison .img-placeholder{font-size:7rem;}

@media(max-width:768px){
    #options-container{padding:1rem;}
    .vote-bar-top{padding:.8rem 1rem;}
    .mode-grille{grid-template-columns:1fr;}
    .mode-grille .opt-img-wrap{height:240px;}
    .mode-liste .opt-img-wrap{width:120px;}
    .mode-comparaison{grid-template-columns:1fr;}
    .mode-comparaison .opt-img-wrap{height:280px;}
    .view-btn span.label{display:none;}
}
</style>
@endsection

@section('content')

{{-- Hero --}}
<div class="jersey-hero">
    <h1>👕 {{ $S['vote_label'] }}</h1>
    <p>Choisissez votre préféré parmi les options ci-dessous</p>
    <div style="margin-top:.6rem;color:#aaa;font-size:.85rem;">
        {{ $totalVotes }} vote{{ $totalVotes > 1 ? 's' : '' }} au total
        @if($hasVoted) &bull; <span style="color:var(--vert);">Vous avez déjà voté ✓</span> @endif
    </div>
</div>

{{-- Barre contrôle --}}
<div class="vote-bar-top">
    <div class="vote-status">
        @if($hasVoted)
            <strong style="color:var(--vert);">✓ Merci pour votre vote !</strong> Les résultats seront annoncés lors de l'AG.
        @else
            <strong style="color:var(--orange);">Votez pour votre choix préféré !</strong>
        @endif
    </div>
    <div class="view-toggle">
        <button class="view-btn" data-mode="grille" onclick="setMode('grille')">🔲 <span class="label">Grille</span></button>
        <button class="view-btn" data-mode="liste" onclick="setMode('liste')">☰ <span class="label">Liste</span></button>
        <button class="view-btn" data-mode="comparaison" onclick="setMode('comparaison')">⚖️ <span class="label">Comparaison</span></button>
    </div>
</div>

{{-- Options --}}
<div id="options-container">
    <div id="options-inner" class="mode-grille">
        @foreach($options as $option)
        @php
            $pct = $totalVotes > 0 ? round(($option->votes_count / $totalVotes) * 100) : 0;
            $isMyVote = $userVoteOptionId == $option->id;
        @endphp
        <div class="opt {{ $isMyVote ? 'voted' : '' }}">
            @if($isMyVote)
                <div class="voted-badge">⭐ Votre choix</div>
            @endif

            {{-- Image avec fallback si image cassée --}}
            <div class="opt-img-wrap">
                @if($option->image_path)
                    <img src="{{ asset('storage/' . $option->image_path) }}"
                         alt="{{ $option->nom }}"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="img-placeholder">👕</div>
                @else
                    <div class="img-placeholder" style="display:flex;">👕</div>
                @endif
            </div>

            {{-- Infos --}}
            <div class="opt-body">
                <div class="opt-name">{{ $option->nom }}</div>
                @if($option->couleurs)
                    <div class="opt-colors">{{ $option->couleurs }}</div>
                @endif
                @if($option->description)
                    <div class="opt-desc">{{ $option->description }}</div>
                @endif

                @if($hasVoted)
                    <div class="opt-bar-wrap">
                        <div class="opt-bar" style="width:{{ $pct }}%"></div>
                    </div>
                    <div class="opt-pct">{{ $pct }}% — {{ $option->votes_count }} vote{{ $option->votes_count > 1 ? 's' : '' }}</div>
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
</div>
@endsection

@section('scripts')
<script>
const MODES = ['grille', 'liste', 'comparaison'];

function setMode(mode) {
    const inner = document.getElementById('options-inner');
    MODES.forEach(m => inner.classList.remove('mode-' + m));
    inner.classList.add('mode-' + mode);
    document.querySelectorAll('.view-btn').forEach(b => b.classList.toggle('active', b.dataset.mode === mode));
}

// Le mode affiché à l'arrivée est toujours celui défini par l'admin
setMode('{{ $S["vote_display_mode"] }}');
</script>
@endsection
