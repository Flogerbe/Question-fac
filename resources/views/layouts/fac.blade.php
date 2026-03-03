<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'FAC Andrézieux - Quiz AG')</title>
    <style>
        :root {
            --bleu:      {{ $S['couleur_bleu'] }};
            --bleu-mid:  {{ $S['couleur_bleu_fonce'] }};
            --bleu-light:color-mix(in srgb, {{ $S['couleur_bleu'] }} 70%, white 30%);
            --orange:    {{ $S['couleur_orange'] }};
            --orange-dk: color-mix(in srgb, {{ $S['couleur_orange'] }} 80%, black 20%);
            --orange-lt: color-mix(in srgb, {{ $S['couleur_orange'] }} 75%, white 25%);
            --blanc:     #FFFFFF;
            --gris-card: color-mix(in srgb, {{ $S['couleur_bleu_fonce'] }} 40%, black 60%);
            --gris-dark: color-mix(in srgb, {{ $S['couleur_bleu_fonce'] }} 20%, black 80%);
            --texte-mut: color-mix(in srgb, {{ $S['couleur_bleu'] }} 55%, white 45%);
            --vert:      #22C55E;
            --rouge:     #EF4444;
            --or:        #FFD700;
        }
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; background: var(--gris-dark); }
        body { font-family: 'Segoe UI', system-ui, sans-serif; background: var(--gris-dark); color: var(--blanc); min-height: 100vh; display: flex; flex-direction: column; }
        header { background: linear-gradient(135deg, var(--bleu-mid) 0%, var(--gris-dark) 100%); border-bottom: 3px solid var(--orange); padding: .6rem 1.5rem; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 100; box-shadow: 0 2px 20px rgba(0,0,0,.5); }
        .header-logo { display: flex; align-items: center; gap: .8rem; text-decoration: none; flex-shrink: 0; }
        .header-logo img { height: 48px; width: auto; filter: drop-shadow(0 0 6px rgba(234,79,42,.4)); }
        .logo-txt .t1 { font-size: 1rem; font-weight: 900; color: var(--orange); letter-spacing: 1px; text-transform: uppercase; line-height: 1.2; }
        .logo-txt .t2 { font-size: .65rem; color: var(--texte-mut); letter-spacing: .5px; }
        .nav-desktop { display: flex; gap: .3rem; align-items: center; }
        .nav-desktop a { color: var(--texte-mut); text-decoration: none; padding: .45rem .9rem; border-radius: 6px; font-size: .85rem; font-weight: 500; transition: all .2s; white-space: nowrap; }
        .nav-desktop a:hover { color: var(--orange); background: rgba(234,79,42,.12); }
        .nav-cta { background: var(--orange) !important; color: var(--blanc) !important; border-radius: 8px !important; font-weight: 700 !important; margin-left: .3rem !important; }
        .nav-cta:hover { background: var(--orange-dk) !important; }
        .nav-toggle { display: none; background: none; border: none; cursor: pointer; padding: 6px; }
        .nav-toggle span { display: block; width: 24px; height: 2px; background: var(--texte-mut); margin: 5px 0; border-radius: 2px; transition: all .3s; }
        .nav-mobile { display: none; position: absolute; top: 100%; left: 0; right: 0; background: var(--bleu); border-bottom: 2px solid var(--orange); flex-direction: column; padding: .5rem; box-shadow: 0 8px 20px rgba(0,0,0,.5); }
        .nav-mobile.open { display: flex; }
        .nav-mobile a { color: var(--texte-mut); text-decoration: none; padding: .75rem 1rem; border-radius: 6px; font-size: .9rem; transition: all .2s; }
        .nav-mobile a:hover { color: var(--orange); background: rgba(234,79,42,.12); }
        main { flex: 1; }
        footer { background: var(--bleu); border-top: 2px solid var(--orange); padding: 1.2rem 1.5rem; text-align: center; color: var(--texte-mut); font-size: .78rem; }
        footer p + p { margin-top: .3rem; }
        footer strong { color: var(--orange); }
        .container { max-width: 1100px; margin: 0 auto; padding: 2rem 1.5rem; }
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: .4rem; padding: .75rem 1.8rem; border-radius: 8px; font-weight: 700; font-size: .95rem; text-decoration: none; cursor: pointer; border: none; transition: all .2s; -webkit-tap-highlight-color: transparent; }
        .btn-orange { background: var(--orange); color: var(--blanc); }
        .btn-orange:hover { background: var(--orange-dk); transform: translateY(-2px); box-shadow: 0 4px 15px rgba(234,79,42,.4); }
        .btn-outline { background: transparent; border: 2px solid var(--orange); color: var(--orange); }
        .btn-outline:hover { background: var(--orange); color: var(--blanc); transform: translateY(-2px); }
        .btn-danger { background: var(--rouge); color: #fff; }
        .btn-danger:hover { background: #DC2626; }
        .btn-sm { padding: .4rem 1rem; font-size: .82rem; }
        .btn-lg { padding: 1rem 2.5rem; font-size: 1.1rem; }
        .alert { padding: .9rem 1.4rem; border-radius: 8px; margin-bottom: 1rem; font-weight: 600; font-size: .9rem; }
        .alert-success { background: rgba(34,197,94,.15); border: 1px solid var(--vert); color: var(--vert); }
        .alert-error { background: rgba(239,68,68,.15); border: 1px solid var(--rouge); color: #F87171; }
        .card { background: var(--gris-card); border-radius: 12px; padding: 1.5rem; border: 1px solid rgba(244,120,32,.2); }
        @media (max-width: 768px) {
            .nav-desktop { display: none; }
            .nav-toggle { display: block; }
            .container { padding: 1.5rem 1rem; }
            .btn-lg { padding: .85rem 1.8rem; font-size: 1rem; }
        }
        @media (max-width: 480px) {
            .logo-txt .t1 { font-size: .85rem; }
            .logo-txt .t2 { display: none; }
            .header-logo img { height: 36px; }
            header { padding: .5rem 1rem; }
            footer { padding: .9rem 1rem; font-size: .72rem; }
        }
    </style>
    @yield('styles')
</head>
<body>
<header>
    <a href="{{ route('home') }}" class="header-logo">
        <img src="{{ asset($S['logo_path']) }}" alt="FAC Andrézieux">
        <div class="logo-txt">
            <span class="t1">FAC Andrézieux</span>
            <span class="t2">FOREZ ATHLETIC CLUB — QUIZ AG {{ date('Y') }}</span>
        </div>
    </a>
    <nav class="nav-desktop">
        <a href="{{ route('home') }}">🏠 Accueil</a>
        <a href="{{ route('regles') }}">📖 Règles</a>
        <a href="{{ route('classement') }}">🏆 Classement</a>
        <a href="{{ route('jersey.index') }}">👕 Vote</a>
        <a href="{{ route('quiz.index') }}" class="nav-cta">🎮 Jouer</a>
    </nav>
    <button class="nav-toggle" id="nav-toggle" aria-label="Menu"><span></span><span></span><span></span></button>
    <nav class="nav-mobile" id="nav-mobile">
        <a href="{{ route('home') }}">🏠 Accueil</a>
        <a href="{{ route('regles') }}">📖 Règles du jeu</a>
        <a href="{{ route('classement') }}">🏆 Classement</a>
        <a href="{{ route('jersey.index') }}">👕 Vote Maillot</a>
        <a href="{{ route('quiz.index') }}" style="color:var(--orange);font-weight:700;">🎮 Jouer au Quiz</a>
    </nav>
</header>
<main>
    @if(session('success'))<div class="container" style="padding-bottom:0;"><div class="alert alert-success">{{ session('success') }}</div></div>@endif
    @if(session('error'))<div class="container" style="padding-bottom:0;"><div class="alert alert-error">{{ session('error') }}</div></div>@endif
    @yield('content')
</main>
<footer>
    <p><strong>FAC Andrézieux</strong> — Forez Athletic Club — <em>"Une autre idée de l'athlé"</em></p>
    <p>Quiz Assemblée Générale {{ date('Y') }} &bull; Andrézieux-Bouthéon, Loire (42)</p>
</footer>
<script>
const t = document.getElementById('nav-toggle'), n = document.getElementById('nav-mobile');
if (t && n) {
    t.addEventListener('click', () => n.classList.toggle('open'));
    document.addEventListener('click', e => { if (!t.contains(e.target) && !n.contains(e.target)) n.classList.remove('open'); });
}
</script>
@yield('scripts')
</body>
</html>
