@extends('layouts.admin')
@section('title', 'Paramètres du site')
@section('page-title', '⚙️ Paramètres du site')

@section('content')
<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;align-items:start;">

        {{-- Couleurs --}}
        <div style="background:white;border-radius:12px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,.06);">
            <h2 style="font-size:1rem;font-weight:700;color:#333;margin-bottom:1.2rem;border-bottom:2px solid var(--orange);padding-bottom:.5rem;">🎨 Couleurs</h2>

            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:.85rem;font-weight:600;color:#555;margin-bottom:.5rem;">Bleu principal</label>
                <div style="display:flex;align-items:center;gap:.8rem;">
                    <input type="color" name="couleur_bleu" value="{{ $settings['couleur_bleu'] }}" style="width:50px;height:40px;border:none;cursor:pointer;border-radius:6px;">
                    <input type="text" id="txt_bleu" value="{{ $settings['couleur_bleu'] }}" oninput="document.querySelector('[name=couleur_bleu]').value=this.value" style="flex:1;padding:.5rem;border:1px solid #e5e7eb;border-radius:6px;font-family:monospace;font-size:.9rem;">
                </div>
                @error('couleur_bleu')<p style="color:red;font-size:.8rem;margin-top:.3rem;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:.85rem;font-weight:600;color:#555;margin-bottom:.5rem;">Bleu foncé (dégradé)</label>
                <div style="display:flex;align-items:center;gap:.8rem;">
                    <input type="color" name="couleur_bleu_fonce" value="{{ $settings['couleur_bleu_fonce'] }}" style="width:50px;height:40px;border:none;cursor:pointer;border-radius:6px;">
                    <input type="text" id="txt_bleu_fonce" value="{{ $settings['couleur_bleu_fonce'] }}" oninput="document.querySelector('[name=couleur_bleu_fonce]').value=this.value" style="flex:1;padding:.5rem;border:1px solid #e5e7eb;border-radius:6px;font-family:monospace;font-size:.9rem;">
                </div>
                @error('couleur_bleu_fonce')<p style="color:red;font-size:.8rem;margin-top:.3rem;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom:1.2rem;">
                <label style="display:block;font-size:.85rem;font-weight:600;color:#555;margin-bottom:.5rem;">Orange</label>
                <div style="display:flex;align-items:center;gap:.8rem;">
                    <input type="color" name="couleur_orange" value="{{ $settings['couleur_orange'] }}" style="width:50px;height:40px;border:none;cursor:pointer;border-radius:6px;">
                    <input type="text" id="txt_orange" value="{{ $settings['couleur_orange'] }}" oninput="document.querySelector('[name=couleur_orange]').value=this.value" style="flex:1;padding:.5rem;border:1px solid #e5e7eb;border-radius:6px;font-family:monospace;font-size:.9rem;">
                </div>
                @error('couleur_orange')<p style="color:red;font-size:.8rem;margin-top:.3rem;">{{ $message }}</p>@enderror
            </div>

            {{-- Preview --}}
            <div id="color-preview" style="border-radius:10px;padding:1rem;text-align:center;font-weight:700;font-size:.9rem;transition:all .3s;">
                Aperçu des couleurs
            </div>
            <button type="button" onclick="updatePreview()" style="width:100%;margin-top:.5rem;background:#f3f4f6;border:1px solid #e5e7eb;padding:.4rem;border-radius:6px;cursor:pointer;font-size:.8rem;color:#555;">🔄 Aperçu</button>
        </div>

        {{-- Textes & Logo --}}
        <div>
            <div style="background:white;border-radius:12px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,.06);margin-bottom:1.5rem;">
                <h2 style="font-size:1rem;font-weight:700;color:#333;margin-bottom:1.2rem;border-bottom:2px solid var(--orange);padding-bottom:.5rem;">✏️ Textes</h2>

                <div style="margin-bottom:.8rem;">
                    <label style="display:block;font-size:.85rem;font-weight:600;color:#555;margin-bottom:.3rem;">Titre principal <span style="color:red;">*</span></label>
                    <input type="text" name="site_titre" value="{{ $settings['site_titre'] }}" required maxlength="100" style="width:100%;padding:.6rem;border:1px solid #e5e7eb;border-radius:6px;font-size:.9rem;">
                    @error('site_titre')<p style="color:red;font-size:.8rem;margin-top:.3rem;">{{ $message }}</p>@enderror
                </div>

                <div style="margin-bottom:.8rem;">
                    <label style="display:block;font-size:.85rem;font-weight:600;color:#555;margin-bottom:.3rem;">Sous-titre <span style="color:red;">*</span></label>
                    <textarea name="site_sous_titre" rows="2" required maxlength="200" style="width:100%;padding:.6rem;border:1px solid #e5e7eb;border-radius:6px;font-size:.9rem;resize:vertical;">{{ $settings['site_sous_titre'] }}</textarea>
                    @error('site_sous_titre')<p style="color:red;font-size:.8rem;margin-top:.3rem;">{{ $message }}</p>@enderror
                </div>
            </div>

            <div style="background:white;border-radius:12px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,.06);">
                <h2 style="font-size:1rem;font-weight:700;color:#333;margin-bottom:1.2rem;border-bottom:2px solid var(--orange);padding-bottom:.5rem;">🖼️ Logo</h2>

                <div style="text-align:center;margin-bottom:1rem;">
                    <img src="{{ asset($settings['logo_path']) }}" alt="Logo actuel" style="height:60px;width:auto;display:inline-block;">
                    <p style="font-size:.75rem;color:#888;margin-top:.4rem;">Logo actuel</p>
                </div>

                <div>
                    <label style="display:block;font-size:.85rem;font-weight:600;color:#555;margin-bottom:.3rem;">Nouveau logo (JPG/PNG/GIF, max 2Mo)</label>
                    <input type="file" name="logo" accept="image/*" style="width:100%;font-size:.85rem;">
                </div>
            </div>
        </div>
    </div>

    <div style="display:flex;gap:1rem;margin-top:1.5rem;">
        <button type="submit" style="background:var(--orange);color:white;padding:.8rem 2rem;border-radius:8px;border:none;cursor:pointer;font-weight:700;font-size:.95rem;">💾 Enregistrer</button>
        <a href="{{ route('admin.settings.reset') }}" onclick="return confirm('Réinitialiser les couleurs du club ?')" style="background:#f3f4f6;color:#555;padding:.8rem 1.5rem;border-radius:8px;text-decoration:none;font-size:.85rem;display:inline-flex;align-items:center;">🔄 Réinitialiser les couleurs</a>
    </div>
</form>

<script>
function updatePreview() {
    const bleu = document.querySelector('[name=couleur_bleu]').value;
    const orange = document.querySelector('[name=couleur_orange]').value;
    const preview = document.getElementById('color-preview');
    preview.style.background = `linear-gradient(135deg, ${bleu}, ${orange})`;
    preview.style.color = '#fff';
    preview.textContent = 'FAC Andrézieux';
}
// Sync text inputs → color pickers
['bleu','bleu_fonce','orange'].forEach(k => {
    const txt = document.getElementById('txt_' + k);
    const picker = document.querySelector('[name=couleur_' + k + ']');
    if (txt && picker) {
        picker.addEventListener('input', () => txt.value = picker.value);
    }
});
updatePreview();
</script>
@endsection
