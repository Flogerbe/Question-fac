<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — FAC Andrézieux</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --orange: #F7941D; --orange-dark: #d97b10; }
        .admin-sidebar { background: #1a1a1a; min-height: 100vh; width: 240px; position: fixed; left: 0; top: 0; border-right: 2px solid var(--orange); padding: 1rem 0; z-index: 100; }
        .admin-sidebar .brand { padding: 1rem 1.5rem 1.5rem; border-bottom: 1px solid #333; }
        .admin-sidebar .brand-title { color: var(--orange); font-weight: 900; font-size: 1rem; letter-spacing: 1px; }
        .admin-sidebar .brand-sub { color: #666; font-size: .7rem; margin-top: 2px; }
        .admin-sidebar nav { padding: 1rem 0; }
        .admin-sidebar nav a { display: flex; align-items: center; gap: .6rem; padding: .7rem 1.5rem; color: #aaa; text-decoration: none; font-size: .9rem; transition: all .2s; border-left: 3px solid transparent; }
        .admin-sidebar nav a:hover, .admin-sidebar nav a.active { color: var(--orange); background: rgba(247,148,29,.08); border-left-color: var(--orange); }
        .admin-main { margin-left: 240px; min-height: 100vh; background: #f9f9f9; }
        .admin-topbar { background: white; border-bottom: 1px solid #e5e7eb; padding: .8rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        .admin-topbar h1 { font-size: 1.2rem; font-weight: 700; color: #111; }
        .admin-topbar .user-info { display: flex; align-items: center; gap: 1rem; font-size: .85rem; color: #666; }
        .admin-content { padding: 2rem; }
        .alert { padding: .8rem 1.2rem; border-radius: 6px; margin-bottom: 1rem; font-weight: 600; font-size: .9rem; }
        .alert-success { background: #dcfce7; border: 1px solid #16a34a; color: #15803d; }
        .alert-error { background: #fee2e2; border: 1px solid #dc2626; color: #dc2626; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <aside class="admin-sidebar">
        <div class="brand">
            <div class="brand-title">FAC ANDRÉZIEUX</div>
            <div class="brand-sub">Back-office Administrateur</div>
        </div>
        <nav>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                📊 Tableau de bord
            </a>
            <a href="{{ route('admin.questions.index') }}" class="{{ request()->routeIs('admin.questions*') ? 'active' : '' }}">
                ❓ Questions & Réponses
            </a>
            <a href="{{ route('admin.leaderboard') }}" class="{{ request()->routeIs('admin.leaderboard') ? 'active' : '' }}">
                🏆 Classement
            </a>
            <a href="{{ route('admin.jersey.index') }}" class="{{ request()->routeIs('admin.jersey*') ? 'active' : '' }}">
                👕 Vote Maillot
            </a>
            <a href="{{ route('admin.players.index') }}" class="{{ request()->routeIs('admin.players*') ? 'active' : '' }}">
                👥 Joueurs
            </a>
            <a href="{{ route('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                ⚙️ Paramètres
            </a>
            <hr style="border-color:#333;margin:1rem 0;">
            <a href="{{ route('home') }}" target="_blank">🌐 Voir le site</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">🚪 Déconnexion</a>
            </form>
        </nav>
    </aside>

    <div class="admin-main">
        <div class="admin-topbar">
            <h1>@yield('page-title', 'Administration')</h1>
            <div class="user-info">
                <span>👤 {{ auth()->user()->name }}</span>
            </div>
        </div>

        <div class="admin-content">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            @yield('content')
        </div>
    </div>
</body>
</html>
