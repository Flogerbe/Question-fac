@php
$answers = $question->answers ?? collect();
$correctIndex = $answers->search(fn($a) => $a->is_correct);
@endphp

<div style="margin-bottom:1.2rem;">
    <label style="display:block;font-weight:600;color:#333;margin-bottom:.4rem;">Question <span style="color:red;">*</span></label>
    <textarea name="question" rows="3" required style="width:100%;padding:.7rem;border:1px solid #e5e7eb;border-radius:8px;font-size:1rem;resize:vertical;">{{ old('question', $question->question ?? '') }}</textarea>
    @error('question') <div style="color:red;font-size:.85rem;margin-top:.2rem;">{{ $message }}</div> @enderror
</div>

<div style="margin-bottom:1.2rem;">
    <label style="display:block;font-weight:600;color:#333;margin-bottom:.4rem;">Indice "Question au coach"</label>
    <input type="text" name="coach_hint" value="{{ old('coach_hint', $question->coach_hint ?? '') }}" placeholder="Ex: C'est dans le nom du club..." style="width:100%;padding:.7rem;border:1px solid #e5e7eb;border-radius:8px;font-size:.95rem;">
    <div style="font-size:.8rem;color:#888;margin-top:.2rem;">Affiché quand un joueur utilise le joker "Question au coach"</div>
</div>

<div style="margin-bottom:1.5rem;">
    <label style="display:block;font-weight:600;color:#333;margin-bottom:.4rem;">Ordre d'affichage <span style="color:red;">*</span></label>
    <input type="number" name="ordre" value="{{ old('ordre', $question->ordre ?? 1) }}" min="1" max="20" required style="width:100px;padding:.7rem;border:1px solid #e5e7eb;border-radius:8px;font-size:.95rem;">
    <span style="color:#888;font-size:.85rem;margin-left:.5rem;">(1 à 20)</span>
</div>

<div style="background:#f9fafb;border-radius:10px;padding:1.2rem;border:1px solid #e5e7eb;">
    <h3 style="font-size:.95rem;font-weight:700;color:#333;margin-bottom:1rem;">Les 4 réponses <span style="color:red;">*</span></h3>
    <div style="font-size:.85rem;color:#888;margin-bottom:1rem;">Cochez la bonne réponse</div>

    @php $letters = ['A','B','C','D']; @endphp
    @for($i = 0; $i < 4; $i++)
    @php $ans = $answers[$i] ?? null; @endphp
    <div style="display:flex;align-items:center;gap:.8rem;margin-bottom:.8rem;background:white;border-radius:8px;padding:.7rem .8rem;border:1px solid #e5e7eb;">
        <div style="width:24px;height:24px;border-radius:50%;background:#F7941D;display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:.8rem;flex-shrink:0;">{{ $letters[$i] }}</div>
        <input type="text" name="answers[{{ $i }}][reponse]" value="{{ old("answers.$i.reponse", $ans->reponse ?? '') }}" required placeholder="Réponse {{ $letters[$i] }}" style="flex:1;padding:.5rem;border:1px solid #e5e7eb;border-radius:6px;font-size:.9rem;">
        <label style="display:flex;align-items:center;gap:.4rem;white-space:nowrap;font-size:.85rem;color:#555;cursor:pointer;">
            <input type="radio" name="correct_answer" value="{{ $i }}" {{ old('correct_answer', $correctIndex !== false ? $correctIndex : 0) == $i ? 'checked' : '' }} required>
            Bonne réponse
        </label>
    </div>
    @endfor
</div>
@error('answers') <div style="color:red;font-size:.85rem;margin-top:.5rem;">{{ $message }}</div> @enderror
@error('correct_answer') <div style="color:red;font-size:.85rem;margin-top:.2rem;">{{ $message }}</div> @enderror
