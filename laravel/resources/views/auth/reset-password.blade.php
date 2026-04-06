<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouveau mot de passe | TP Ticketing</title>
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
            <a href="/login">Se connecter</a>
            <a href="/register">S'inscrire</a>
        </nav>
    </div>
</header>

<main class="auth-shell">
    <section class="auth-hero auth-hero-register">
        <span class="auth-kicker">Sécurité</span>
        <h1>Choisissez un nouveau mot de passe.</h1>
        <p>
            Après validation, vous pourrez vous reconnecter immédiatement avec votre nouveau mot de passe.
        </p>
    </section>

    <section class="auth-panel-wrap">
        <div class="auth-panel">
            <h2>Réinitialiser le mot de passe</h2>

            @if ($errors->any())
                <div class="auth-alert auth-alert-error">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="/reset-password" class="auth-form">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <label for="email">Adresse email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>

                <label for="password">Nouveau mot de passe</label>
                <input type="password" id="password" name="password" required>

                <label for="password_confirmation">Confirmer le mot de passe</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>

                <button type="submit" class="auth-submit">Mettre à jour</button>
            </form>

            <p class="auth-panel-footer">
                Retour à la <a href="/login">connexion</a>
            </p>
        </div>
    </section>
</main>

</body>
</html>
