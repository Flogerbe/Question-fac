@extends('layouts.admin')
@section('title', 'Questions')
@section('page-title', '❓ Gestion des questions')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
    <p style="color:#666;font-size:.9rem;">{{ $questions->count() }} question(s) au total</p>
    <a href="{{ route('admin.questions.create') }}" style="background:var(--orange);color:white;padding:.6rem 1.4rem;border-radius:8px;text-decoration:none;font-weight:700;font-size:.9rem;">+ Nouvelle question</a>
</div>

<div style="background:white;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,.06);overflow:hidden;">
    <table style="width:100%;border-collapse:collapse;font-size:.9rem;">
        <thead style="background:#f9fafb;border-bottom:2px solid var(--orange);">
            <tr>
                <th style="padding:.8rem 1rem;text-align:left;color:#555;width:40px;">Ordre</th>
                <th style="padding:.8rem 1rem;text-align:left;color:#555;">Question</th>
                <th style="padding:.8rem 1rem;text-align:center;color:#555;">Statut</th>
                <th style="padding:.8rem 1rem;text-align:center;color:#555;">Réponses</th>
                <th style="padding:.8rem 1rem;text-align:center;color:#555;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($questions as $question)
            <tr style="border-bottom:1px solid #f0f0f0;">
                <td style="padding:.8rem 1rem;text-align:center;font-weight:700;color:var(--orange);">{{ $question->ordre }}</td>
                <td style="padding:.8rem 1rem;">
                    <div style="font-weight:600;color:#111;line-height:1.4;">{{ Str::limit($question->question, 80) }}</div>
                    @if($question->coach_hint)
                    <div style="font-size:.8rem;color:#888;margin-top:.2rem;">🎓 {{ Str::limit($question->coach_hint, 60) }}</div>
                    @endif
                </td>
                <td style="padding:.8rem 1rem;text-align:center;">
                    <form action="{{ route('admin.questions.toggle', $question) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" style="padding:.3rem .8rem;border-radius:20px;border:none;cursor:pointer;font-size:.8rem;font-weight:600;background:{{ $question->is_active ? '#dcfce7' : '#fee2e2' }};color:{{ $question->is_active ? '#15803d' : '#dc2626' }};">
                            {{ $question->is_active ? '✅ Active' : '❌ Inactive' }}
                        </button>
                    </form>
                </td>
                <td style="padding:.8rem 1rem;text-align:center;color:#888;">{{ $question->answers->count() }} rép.</td>
                <td style="padding:.8rem 1rem;text-align:center;">
                    <a href="{{ route('admin.questions.edit', $question) }}" style="background:#e5e7eb;color:#333;padding:.3rem .8rem;border-radius:6px;text-decoration:none;font-size:.85rem;margin-right:.4rem;">✏️ Modifier</a>
                    <form action="{{ route('admin.questions.destroy', $question) }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer cette question ?');">
                        @csrf @method('DELETE')
                        <button type="submit" style="background:#fee2e2;color:#dc2626;padding:.3rem .8rem;border-radius:6px;border:none;cursor:pointer;font-size:.85rem;">🗑️ Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
