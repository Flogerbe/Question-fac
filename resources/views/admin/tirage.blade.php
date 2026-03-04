@extends('layouts.admin')
@section('title', 'Tirage au sort')
@section('page-title', '🎲 Tirage au sort')

@section('content')
<style>
.tirage-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
@media(max-width: 1100px) { .tirage-grid { grid-template-columns: 1fr 1fr; } }
@media(max-width: 700px)  { .tirage-grid { grid-template-columns: 1fr; } }
.tirage-card { background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,.06); border-top: 4px solid var(--orange); display: flex; flex-direction: column; }
.tirage-card h2 { font-size: 1rem; font-weight: 800; color: #111; margin-bottom: .3rem; }
.tirage-card .desc { font-size: .82rem; color: #888; margin-bottom: 1rem; line-height: 1.5; }
.tirage-card .eligibles { font-size: .85rem; color: #555; margin-bottom: 1rem; }
.tirage-card .eligibles strong { color: #111; }
.btn-draw { background: var(--orange); color: white; border: none; padding: .75rem 1.2rem; border-radius: 8px; font-weight: 700; font-size: .9rem; cursor: pointer; width: 100%; transition: background .2s; }
.btn-draw:hover { background: var(--orange-dark); }
.btn-draw:disabled { opacity: .5; cursor: not-allowed; }
.winners-list { margin-top: 1rem; flex: 1; }
.winners-list h3 { font-size: .85rem; font-weight: 700; color: #333; margin-bottom: .6rem; border-top: 1px solid #f3f4f6; padding-top: .8rem; }
.winner-item { display: flex; align-items: center; gap: .7rem; padding: .55rem .8rem; border-radius: 8px; background: #fef9f0; border: 1px solid #fde9c4; margin-bottom: .4rem; }
.winner-rank { font-size: 1.2rem; width: 28px; text-align: center; flex-shrink: 0; }
.winner-name { font-weight: 700; color: #111; font-size: .9rem; flex: 1; }
.winner-score { font-size: .82rem; color: #666; }
.no-winners { font-size: .82rem; color: #aaa; font-style: italic; text-align: center; padding: 1rem 0; }
.btn-reset { background: none; border: 1px solid #e5e7eb; color: #888; padding: .4rem .8rem; border-radius: 6px; font-size: .78rem; cursor: pointer; width: 100%; margin-top: .6rem; transition: all .2s; }
.btn-reset:hover { background: #fee2e2; border-color: #dc2626; color: #dc2626; }
.spinner { display: inline-block; width: 16px; height: 16px; border: 2px solid rgba(255,255,255,.4); border-top-color: white; border-radius: 50%; animation: spin .6s linear infinite; vertical-align: middle; margin-right: .4rem; }
@keyframes spin { to { transform: rotate(360deg); } }
.alert-info { background: #eff6ff; border: 1px solid #3b82f6; color: #1d4ed8; padding: .8rem 1.2rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: .9rem; }
</style>

@if($settings['classement_mode'] !== 'tirage')
<div class="alert-info">
    ℹ️ Le mode classement actuel est <strong>par points</strong>. Pour activer les tirages au sort, passez le mode classement en <strong>Tirage au sort</strong> dans les <a href="{{ route('admin.settings.index') }}" style="color:#1d4ed8;font-weight:700;">Paramètres</a>.
</div>
@endif

<div class="tirage-grid">

    {{-- ESPRIT CLUB --}}
    <div class="tirage-card" id="card-esprit_club">
        <h2>🎯 Tirage Esprit Club</h2>
        <p class="desc">Pour tous les participants, quel que soit le nombre de bonnes réponses.</p>
        <p class="eligibles">Éligibles : <strong>{{ $espritClub->count() }}</strong> participant(s) &nbsp;·&nbsp; Nb gagnants : <strong>{{ $settings['tirage_esprit_club_nb'] }}</strong></p>
        <button class="btn-draw" onclick="lancerTirage('esprit_club', this)" {{ $espritClub->count() === 0 ? 'disabled' : '' }}>
            🎲 Lancer le tirage
        </button>
        <div class="winners-list" id="winners-esprit_club">
            @include('admin.tirage-winners', ['results' => $drawn->get('esprit_club'), 'totalQuestions' => $totalQuestions, 'type' => 'esprit_club'])
        </div>
    </div>

    {{-- 100% CHAMPION --}}
    <div class="tirage-card" id="card-champion">
        <h2>🏅 Tirage 100% Champion</h2>
        <p class="desc">Réservé aux participants ayant répondu correctement à <strong>toutes</strong> les questions ({{ $totalQuestions }}/{{ $totalQuestions }}).</p>
        <p class="eligibles">Éligibles : <strong>{{ $champions->count() }}</strong> participant(s) &nbsp;·&nbsp; Nb gagnants : <strong>{{ $settings['tirage_champion_nb'] }}</strong></p>
        <button class="btn-draw" onclick="lancerTirage('champion', this)" {{ $champions->count() === 0 ? 'disabled' : '' }}>
            🎲 Lancer le tirage
        </button>
        <div class="winners-list" id="winners-champion">
            @include('admin.tirage-winners', ['results' => $drawn->get('champion'), 'totalQuestions' => $totalQuestions, 'type' => 'champion'])
        </div>
    </div>

    {{-- BONUS --}}
    <div class="tirage-card" id="card-bonus">
        <h2>🎁 Tirage Bonus</h2>
        <p class="desc">2e chance pour tous les participants, même ceux qui ont fait des erreurs.</p>
        <p class="eligibles">Éligibles : <strong>{{ $bonus->count() }}</strong> participant(s) &nbsp;·&nbsp; Nb gagnants : <strong>{{ $settings['tirage_bonus_nb'] }}</strong></p>
        <button class="btn-draw" onclick="lancerTirage('bonus', this)" {{ $bonus->count() === 0 ? 'disabled' : '' }}>
            🎲 Lancer le tirage
        </button>
        <div class="winners-list" id="winners-bonus">
            @include('admin.tirage-winners', ['results' => $drawn->get('bonus'), 'totalQuestions' => $totalQuestions, 'type' => 'bonus'])
        </div>
    </div>

</div>

<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
const medals = ['🥇', '🥈', '🥉', '4️⃣', '5️⃣', '6️⃣', '7️⃣', '8️⃣', '9️⃣', '🔟'];

async function lancerTirage(type, btn) {
    if (!confirm('Lancer le tirage "' + type.replace('_', ' ') + '" ? Cela remplacera le tirage précédent.')) return;

    const original = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner"></span> Tirage en cours…';

    try {
        const res = await fetch(`{{ url('admin/tirage') }}/${type}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
        });
        const data = await res.json();

        if (!res.ok) {
            alert(data.error ?? 'Erreur lors du tirage.');
            btn.disabled = false;
            btn.innerHTML = original;
            return;
        }

        // Afficher les gagnants
        const container = document.getElementById('winners-' + type);
        container.innerHTML = buildWinnersList(data.winners, type);
        btn.innerHTML = '🔄 Relancer le tirage';
        btn.disabled = false;

    } catch (e) {
        alert('Erreur réseau.');
        btn.disabled = false;
        btn.innerHTML = original;
    }
}

function buildWinnersList(winners, type) {
    if (!winners || winners.length === 0) {
        return '<p class="no-winners">Aucun gagnant tiré.</p>';
    }
    let html = '<h3>🎉 Gagnants tirés</h3>';
    winners.forEach((w, i) => {
        const medal = medals[i] ?? (i + 1);
        html += `<div class="winner-item">
            <span class="winner-rank">${medal}</span>
            <span class="winner-name">${w.nom_complet}</span>
            <span class="winner-score">${w.score.toLocaleString('fr-FR')} pts · ${w.bonnes_rep}/${w.total_q} ✓</span>
        </div>`;
    });
    html += resetForm(type);
    return html;
}

function resetForm(type) {
    return `<form method="POST" action="{{ url('admin/tirage') }}/${type}" onsubmit="return confirm('Réinitialiser ce tirage ?')">
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" name="_token" value="${CSRF}">
        <button type="submit" class="btn-reset">🗑️ Réinitialiser ce tirage</button>
    </form>`;
}
</script>
@endsection
