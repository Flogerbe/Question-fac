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
        <div style="background:white;border-radius:12px;margin-bottom:1rem;box-shadow:0 2px 8px rgba(0,0,0,.05);border-left:4px solid var(--orange);overflow:hidden;">

            {{-- Carte principale --}}
            <div style="display:flex;gap:1rem;padding:1.2rem;">

                {{-- Miniature image --}}
                <div style="flex-shrink:0;width:90px;height:90px;background:#f3f4f6;border-radius:8px;overflow:hidden;display:flex;align-items:center;justify-content:center;">
                    @if($option->image_path)
                        <img src="{{ asset('storage/' . $option->image_path) }}" alt="{{ $option->nom }}" style="width:100%;height:100%;object-fit:contain;padding:4px;">
                    @else
                        <span style="font-size:2rem;">👕</span>
                    @endif
                </div>

                {{-- Infos --}}
                <div style="flex:1;min-width:0;">
                    <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:.4rem;">
                        <div style="font-weight:700;font-size:1rem;color:#111;">{{ $option->nom }}</div>
                        <div style="text-align:right;flex-shrink:0;margin-left:1rem;">
                            <div style="font-size:1.4rem;font-weight:900;color:var(--orange);">{{ $pct }}%</div>
                            <div style="font-size:.75rem;color:#888;">{{ $option->votes_count }} vote(s)</div>
                        </div>
                    </div>
                    @if($option->couleurs)
                        <div style="font-size:.78rem;color:#888;font-family:monospace;margin-bottom:.2rem;">{{ $option->couleurs }}</div>
                    @endif
                    @if($option->description)
                        <div style="font-size:.83rem;color:#666;">{{ $option->description }}</div>
                    @endif

                    {{-- Barre de progression --}}
                    <div style="background:#f0f0f0;border-radius:4px;height:6px;overflow:hidden;margin-top:.6rem;">
                        <div style="height:100%;width:{{ $pct }}%;background:linear-gradient(90deg,#F7941D,#ffa940);border-radius:4px;"></div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div style="display:flex;gap:.5rem;flex-wrap:wrap;padding:.8rem 1.2rem;border-top:1px solid #f3f4f6;background:#fafafa;">
                <button onclick="toggleEdit({{ $option->id }})"
                    style="background:#e0f0ff;color:#0369a1;border:none;padding:.3rem .7rem;border-radius:6px;cursor:pointer;font-size:.8rem;font-weight:600;">
                    ✏️ Modifier
                </button>
                <form action="{{ route('admin.jersey.resetVotes', $option) }}" method="POST" onsubmit="return confirm('Réinitialiser les votes de cette option ?');" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit" style="background:#fff3cd;color:#856404;border:none;padding:.3rem .7rem;border-radius:6px;cursor:pointer;font-size:.8rem;">🔄 Reset votes</button>
                </form>
                <form action="{{ route('admin.jersey.destroy', $option) }}" method="POST" onsubmit="return confirm('Supprimer cette option ?');" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit" style="background:#fee2e2;color:#dc2626;border:none;padding:.3rem .7rem;border-radius:6px;cursor:pointer;font-size:.8rem;">🗑️ Supprimer</button>
                </form>
            </div>

            {{-- Formulaire d'édition (masqué par défaut) --}}
            <div id="edit-{{ $option->id }}" style="display:none;border-top:2px solid var(--orange);">
                <form action="{{ route('admin.jersey.update', $option) }}" method="POST" enctype="multipart/form-data" style="padding:1.2rem;">
                    @csrf @method('PUT')
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:.8rem;margin-bottom:.8rem;">
                        <div>
                            <label style="display:block;font-size:.82rem;font-weight:600;color:#555;margin-bottom:.3rem;">Nom <span style="color:red;">*</span></label>
                            <input type="text" name="nom" value="{{ $option->nom }}" required
                                style="width:100%;padding:.55rem;border:1px solid #e5e7eb;border-radius:6px;font-size:.9rem;">
                        </div>
                        <div>
                            <label style="display:block;font-size:.82rem;font-weight:600;color:#555;margin-bottom:.3rem;">Couleurs</label>
                            <input type="text" name="couleurs" value="{{ $option->couleurs }}" placeholder="Ex: Orange / Noir"
                                style="width:100%;padding:.55rem;border:1px solid #e5e7eb;border-radius:6px;font-size:.9rem;font-family:monospace;">
                        </div>
                    </div>
                    <div style="margin-bottom:.8rem;">
                        <label style="display:block;font-size:.82rem;font-weight:600;color:#555;margin-bottom:.3rem;">Description</label>
                        <textarea name="description" rows="2"
                            style="width:100%;padding:.55rem;border:1px solid #e5e7eb;border-radius:6px;font-size:.9rem;resize:vertical;">{{ $option->description }}</textarea>
                    </div>
                    <div style="margin-bottom:1rem;">
                        <label style="display:block;font-size:.82rem;font-weight:600;color:#555;margin-bottom:.3rem;">Nouvelle image (JPG/PNG, max 2Mo) — laisser vide pour conserver l'actuelle</label>
                        @if($option->image_path)
                        <div style="margin-bottom:.5rem;">
                            <img src="{{ asset('storage/' . $option->image_path) }}" alt="Image actuelle"
                                style="height:80px;width:auto;border-radius:6px;border:1px solid #e5e7eb;object-fit:contain;background:#f9f9f9;padding:4px;">
                        </div>
                        @endif
                        <input type="file" name="image" accept="image/*" style="width:100%;font-size:.85rem;">
                    </div>
                    <div style="display:flex;gap:.6rem;">
                        <button type="submit"
                            style="background:var(--orange);color:white;padding:.6rem 1.4rem;border-radius:8px;border:none;cursor:pointer;font-weight:700;font-size:.9rem;">
                            💾 Enregistrer
                        </button>
                        <button type="button" onclick="toggleEdit({{ $option->id }})"
                            style="background:#f3f4f6;color:#555;padding:.6rem 1rem;border-radius:8px;border:1px solid #e5e7eb;cursor:pointer;font-size:.9rem;">
                            Annuler
                        </button>
                    </div>
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

<script>
function toggleEdit(id) {
    const el = document.getElementById('edit-' + id);
    el.style.display = el.style.display === 'none' ? 'block' : 'none';
}
</script>
@endsection
