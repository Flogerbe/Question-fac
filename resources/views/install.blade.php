<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation — FAC Andrézieux Quiz AG</title>
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', system-ui, sans-serif; background: #0e0e1a; color: #fff; min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 2rem 1rem; }

        .install-wrap { width: 100%; max-width: 620px; }

        .install-header { text-align: center; margin-bottom: 2rem; }
        .install-header .logo-emoji { font-size: 3.5rem; display: block; margin-bottom: .8rem; }
        .install-header h1 { font-size: 1.8rem; font-weight: 900; color: #F7941D; }
        .install-header p { color: #888; font-size: .9rem; margin-top: .4rem; }

        .step { background: #1a1a2e; border-radius: 14px; padding: 1.8rem; margin-bottom: 1.5rem; border: 1px solid rgba(247,148,29,.15); }
        .step-title { font-size: 1rem; font-weight: 700; color: #F7941D; margin-bottom: 1.3rem; display: flex; align-items: center; gap: .5rem; border-bottom: 1px solid rgba(247,148,29,.15); padding-bottom: .8rem; }

        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .form-grid .full { grid-column: 1 / -1; }
        .form-group { display: flex; flex-direction: column; gap: .4rem; }
        .form-group label { font-size: .82rem; font-weight: 600; color: #aaa; }
        .form-group input { background: #111128; border: 1px solid rgba(255,255,255,.12); border-radius: 7px; padding: .65rem .9rem; color: #fff; font-size: .95rem; outline: none; transition: border-color .2s; }
        .form-group input:focus { border-color: #F7941D; }
        .form-group input::placeholder { color: #444; }
        .form-group .hint { font-size: .75rem; color: #555; }

        .error-box { background: rgba(239,68,68,.12); border: 1px solid #ef4444; border-radius: 8px; padding: .8rem 1rem; margin-bottom: 1.5rem; color: #fca5a5; font-size: .85rem; }
        .error-box ul { padding-left: 1.2rem; margin-top: .3rem; }
        .error-field { color: #f87171; font-size: .78rem; margin-top: .25rem; }

        .btn-install { width: 100%; background: #F7941D; color: #fff; border: none; padding: 1rem; border-radius: 10px; font-size: 1.05rem; font-weight: 800; cursor: pointer; transition: background .2s, transform .1s; margin-top: .5rem; letter-spacing: .3px; }
        .btn-install:hover { background: #d97b10; transform: translateY(-1px); }
        .btn-install:active { transform: translateY(0); }

        .already-installed { text-align: center; padding: 3rem; }
        .already-installed h2 { color: #F7941D; font-size: 1.4rem; margin-bottom: 1rem; }

        @media(max-width: 540px) {
            .form-grid { grid-template-columns: 1fr; }
            .form-grid .full { grid-column: 1; }
        }
    </style>
</head>
<body>

<div class="install-wrap">

    <div class="install-header">
        <span class="logo-emoji">🏆</span>
        <h1>FAC Andrézieux — Quiz AG</h1>
        <p>Assistant d'installation — configurez votre serveur en quelques secondes</p>
    </div>

    @if($errors->any())
    <div class="error-box">
        <strong>⚠️ Erreur lors de l'installation :</strong>
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="/install">
        @csrf

        {{-- BASE DE DONNÉES --}}
        <div class="step">
            <div class="step-title">🗄️ Base de données MySQL</div>
            <div class="form-grid">
                <div class="form-group full">
                    <label>Hôte</label>
                    <input type="text" name="db_host" value="{{ old('db_host', '127.0.0.1') }}" placeholder="127.0.0.1" required>
                    @error('db_host')<div class="error-field">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label>Port</label>
                    <input type="number" name="db_port" value="{{ old('db_port', '3306') }}" placeholder="3306" required>
                </div>
                <div class="form-group">
                    <label>Nom de la base</label>
                    <input type="text" name="db_name" value="{{ old('db_name', 'fac_quiz') }}" placeholder="fac_quiz" required>
                    @error('db_name')<div class="error-field">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label>Utilisateur</label>
                    <input type="text" name="db_user" value="{{ old('db_user', 'root') }}" placeholder="root" required>
                </div>
                <div class="form-group">
                    <label>Mot de passe <span style="color:#555;">(laisser vide si aucun)</span></label>
                    <input type="password" name="db_password" placeholder="••••••••" autocomplete="new-password">
                </div>
            </div>
        </div>

        {{-- APPLICATION --}}
        <div class="step">
            <div class="step-title">🌐 URL de l'application</div>
            <div class="form-group">
                <label>URL publique du site</label>
                <input type="url" name="app_url" value="{{ old('app_url', request()->getSchemeAndHttpHost()) }}" placeholder="https://quiz.fac-andrezieux.fr" required>
                <span class="hint">Ex : http://question-fac.test ou https://quiz.monclub.fr</span>
                @error('app_url')<div class="error-field">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- COMPTE ADMIN --}}
        <div class="step">
            <div class="step-title">👤 Compte Administrateur</div>
            <div class="form-grid">
                <div class="form-group full">
                    <label>Nom d'affichage</label>
                    <input type="text" name="admin_name" value="{{ old('admin_name', 'Admin FAC') }}" placeholder="Admin FAC" required>
                    @error('admin_name')<div class="error-field">{{ $message }}</div>@enderror
                </div>
                <div class="form-group full">
                    <label>Adresse e-mail</label>
                    <input type="email" name="admin_email" value="{{ old('admin_email', 'admin@fac-andrezieux.fr') }}" placeholder="admin@fac-andrezieux.fr" required>
                    @error('admin_email')<div class="error-field">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label>Mot de passe <span style="color:#555;">(min. 8 caractères)</span></label>
                    <input type="password" name="admin_password" placeholder="••••••••" required autocomplete="new-password">
                    @error('admin_password')<div class="error-field">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label>Confirmer le mot de passe</label>
                    <input type="password" name="admin_password_confirmation" placeholder="••••••••" required autocomplete="new-password">
                </div>
            </div>
        </div>

        <button type="submit" class="btn-install">🚀 Installer le projet</button>
        <p style="text-align:center;color:#444;font-size:.78rem;margin-top:1rem;">
            Cette page disparaîtra automatiquement après l'installation et ne sera plus accessible.
        </p>
    </form>

</div>
</body>
</html>
