<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un compte | TP Ticketing</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body class="login-page">

<header class="main-header auth-header">
    <div class="header-content">
        <div class="logo">
            <a href="/">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo">
            </a>
        </div>
        <nav class="auth-top-nav">
            <a href="/">Page de garde</a>
            <a href="/login">Se connecter</a>
        </nav>
    </div>
</header>

<main class="auth-shell">
    <section class="auth-hero auth-hero-register">
        <span class="auth-kicker">Inscription</span>
        <h1>Créez votre compte et démarrez immédiatement.</h1>
        <p>
            Ouvrez votre accès, reliez vos projets à vos clients et commencez à suivre les demandes dans un espace centralisé.
        </p>
        <ul class="auth-benefits">
            <li>Création rapide de votre espace</li>
            <li>Accès direct à l'accueil de l'application</li>
            <li>Navigation fluide entre projets, tickets et clients</li>
        </ul>
    </section>

    <section class="auth-panel-wrap">
        <div class="auth-panel">
            <h2>Créer un compte</h2>

            @if ($errors->any())
                <div class="auth-alert auth-alert-error">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="/register" class="auth-form">
                @csrf

                <label for="name">Nom</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>

                <label for="role">Rôle</label>
                <select id="role" name="role" required>
                    <option value="collaborateur" {{ old('role', 'collaborateur') === 'collaborateur' ? 'selected' : '' }}>Collaborateur</option>
                    <option value="client" {{ old('role') === 'client' ? 'selected' : '' }}>Client</option>
                </select>

                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>

                <label for="password_confirmation">Confirmer le mot de passe</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>

                <button type="submit" class="auth-submit">Créer mon compte</button>
            </form>

            <p class="auth-panel-footer">
                Vous avez déjà un compte ? <a href="/login">Se connecter</a>
            </p>
        </div>
    </section>
</main>

</body>
</html>