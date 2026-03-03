@extends('layouts.admin')
@section('title', 'Vote Maillot')
@section('page-title', '👕 Gestion du vote maillot')

@section('content')
<div style="display:grid;grid-template-columns:1fr 340px;gap:1.5rem;align-items:start;">
    {{-- Liste des options --}}
    <div>
        <h2 style="font-size:1rem;font-weight:700;color:#333;margin-bottom:1rem;">Options actuelles ({{ $totalVotes }} votes au total)</h2>
        @foreach($options as $option)
        @php $pct = $totalVotes > 0 ? round(($option->votes_count / $totalVotes) * 100) : 0; @endphp
        <div style="background:white;border-radius:12px;padding:1.2rem;margin-bottom:1rem;box-shadow:0 2px 8px rgba(0,0,0,.05);border-left:4px solid var(--orange);">
            <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:.8rem;">
                <div>
                    <div style="font-weight:700;font-size:1rem;color:#111;">{{ $option->nom }}</div>
                    @if($option->couleurs) <div style="font-size:.8rem;color:#888;font-family:monospace;">{{ $option->couleurs }}</div> @endif
                    @if($option->description) <div style="font-size:.85rem;color:#666;margin-top:.3rem;">{{ $option->description }}</div> @endif
                </div>
                <div style="text-align:right;flex-shrink:0;margin-left:1rem;">
                    <div style="font-size:1.5rem;font-weight:900;color:var(--orange);">{{ $pct }}%</div>
                    <div style="font-size:.8rem;color:#888;">{{ $option->votes_count }} vote(s)</div>
                </div>
            </div>
            <div style="background:#f0f0f0;border-radius:4px;height:8px;overflow:hidden;margin-bottom:.8rem;">
                <div style="height:100%;width:{{ $pct }}%;background:linear-gradient(90deg,#F7941D,#ffa940);border-radius:4px;"></div>
            </div>
            <div style="display:flex;gap:.5rem;flex-wrap:wrap;">
                <form action="{{ route('admin.jersey.resetVotes', $option) }}" method="POST" onsubmit="return confirm('Réinitialiser les votes de cette option ?');" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit" style="background:#fff3cd;color:#856404;border:none;padding:.3rem .7rem;border-radius:6px;cursor:pointer;font-size:.8rem;">🔄 Reset votes</button>
                </form>
                <form action="{{ route('admin.jersey.destroy', $option) }}" method="POST" onsubmit="return confirm('Supprimer cette option ?');" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit" style="background:#fee2e2;color:#dc2626;border:none;padding:.3rem .7rem;border-radius:6px;cursor:pointer;font-size:.8rem;">🗑️ Supprimer</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Formulaire ajout --}}
    <div style="background:white;border-radius:12px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,.06);position:sticky;top:1rem;">
        <h2 style="font-size:1rem;font-weight:700;color:#333;margin-bottom:1.2rem;border-bottom:2px solid var(--orange);padding-bottom:.5rem;">+ Ajouter une option</h2>
        <form action="{{ route('admin.jersey.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="margin-bottom:.8rem;">
                <label style="display:block;font-size:.85rem;font-weight:600;color:#555;margin-bottom:.3rem;">Nom <span style="color:red;">*</span></label>
                <input type="text" name="nom" required placeholder="Ex: Option A" style="width:100%;padding:.6rem;border:1px solid #e5e7eb;border-radius:6px;font-size:.9rem;">
            </div>
            <div style="margin-bottom:.8rem;">
                <label style="display:block;font-size:.85rem;font-weight:600;color:#555;margin-bottom:.3rem;">Couleurs</label>
                <input type="text" name="couleurs" placeholder="Ex: Orange #F7941D / Noir" style="width:100%;padding:.6rem;border:1px solid #e5e7eb;border-radius:6px;font-size:.9rem;font-family:monospace;">
            </div>
            <div style="margin-bottom:.8rem;">
                <label style="display:block;font-size:.85rem;font-weight:600;color:#555;margin-bottom:.3rem;">Description</label>
                <textarea name="description" rows="2" placeholder="Description du design..." style="width:100%;padding:.6rem;border:1px solid #e5e7eb;border-radius:6px;font-size:.9rem;resize:vertical;"></textarea>
            </div>
            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:.85rem;font-weight:600;color:#555;margin-bottom:.3rem;">Image (JPG/PNG, max 2Mo)</label>
                <input type="file" name="image" accept="image/*" style="width:100%;font-size:.85rem;">
            </div>
            <button type="submit" style="width:100%;background:var(--orange);color:white;padding:.7rem;border-radius:8px;border:none;cursor:pointer;font-weight:700;">Ajouter l'option</button>
        </form>
    </div>
</div>
@endsection
