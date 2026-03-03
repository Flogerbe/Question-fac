@extends('layouts.fac')
@section('title', 'Jouer - FAC Quiz')
@section('styles')
<style>
.quiz-entry{max-width:500px;margin:4rem auto;text-align:center;padding:2rem;}
.quiz-entry h1{color:var(--orange);font-size:2.5rem;font-weight:900;margin-bottom:.5rem;}
.quiz-entry p{color:#aaa;margin-bottom:2rem;}
.form-group{margin-bottom:1.5rem;text-align:left;}
.form-group label{display:block;color:#ccc;font-weight:600;margin-bottom:.5rem;}
.form-group input{width:100%;padding:.9rem 1.2rem;background:var(--gris-card);border:2px solid rgba(244,120,32,.3);border-radius:8px;color:var(--blanc);font-size:1.1rem;outline:none;transition:border-color .2s;}
.form-group input:focus{border-color:var(--orange);}
.form-group input::placeholder{color:#666;}
.error-msg{color:var(--rouge);font-size:.85rem;margin-top:.4rem;}
</style>
@endsection

@section('content')
<div class="quiz-entry">

    @if(session('already_played'))
        <div style="font-size:3rem;margin-bottom:1rem;">🚫</div>
        <h1 style="color:var(--rouge);">Déjà participé !</h1>
        @if(session('already_played') === 'par_jour')
            <p>Vous avez déjà joué aujourd'hui. Revenez demain pour une nouvelle partie !</p>
        @else
            <p>Vous avez déjà participé au quiz. Une seule participation est autorisée par personne.</p>
        @endif
        <div style="margin-top:2rem;display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('classement') }}" class="btn btn-orange">🏆 Voir le classement</a>
            <a href="{{ route('home') }}" class="btn btn-outline">🏠 Accueil</a>
        </div>
    @else
        <div style="font-size:3rem;margin-bottom:1rem;">🏃</div>
        <h1>Prêt à jouer ?</h1>
        <p>Entrez votre prénom pour commencer le quiz de 20 questions !</p>

        <div class="card">
            <form action="{{ route('quiz.demarrer') }}" method="POST">
                @csrf
                <input type="hidden" name="browser_token" id="browser_token" value="">
                <div class="form-group">
                    <label for="prenom">Votre prénom</label>
                    <input type="text" id="prenom" name="prenom" placeholder="Ex: Thomas" value="{{ old('prenom') }}" required autofocus autocomplete="off">
                    @error('prenom')
                        <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-orange" style="width:100%;font-size:1.1rem;padding:1rem;">
                    🎮 C'est parti !
                </button>
            </form>
        </div>

        <div style="margin-top:1.5rem;display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('regles') }}" class="btn btn-outline btn-sm">📖 Règles du jeu</a>
            <a href="{{ route('classement') }}" class="btn btn-outline btn-sm">🏆 Classement</a>
        </div>
    @endif

</div>
@endsection

@section('scripts')
<script>
(function() {
    let token = localStorage.getItem('fac_quiz_token');
    if (!token) {
        token = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            const r = Math.random() * 16 | 0;
            return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16);
        });
        localStorage.setItem('fac_quiz_token', token);
    }
    document.getElementById('browser_token').value = token;
})();
</script>
@endsection
