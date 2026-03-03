@extends('layouts.admin')
@section('title', 'Modifier la question')
@section('page-title', '✏️ Modifier la question')

@section('content')
<div style="max-width:700px;">
    <a href="{{ route('admin.questions.index') }}" style="color:var(--orange);text-decoration:none;font-size:.9rem;">&larr; Retour à la liste</a>

    <div style="background:white;border-radius:12px;padding:2rem;margin-top:1rem;box-shadow:0 2px 8px rgba(0,0,0,.06);">
        <form action="{{ route('admin.questions.update', $question) }}" method="POST">
            @csrf @method('PUT')
            @include('admin.questions._form')
            <div style="display:flex;gap:1rem;margin-top:1.5rem;">
                <button type="submit" style="background:var(--orange);color:white;padding:.7rem 2rem;border-radius:8px;border:none;cursor:pointer;font-weight:700;">Enregistrer</button>
                <a href="{{ route('admin.questions.index') }}" style="background:#e5e7eb;color:#555;padding:.7rem 1.5rem;border-radius:8px;text-decoration:none;">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
