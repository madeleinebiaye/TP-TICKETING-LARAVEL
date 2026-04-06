<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue | TP Ticketing</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="landing-body">

<header class="landing-header">
    <div class="landing-header-inner">
        <a href="/" class="landing-logo">
            <img src="{{ asset('assets/logo.png') }}" alt="Logo TP Ticketing">
        </a>

        <nav class="landing-nav">
            <a href="/login">Se connecter</a>
            <a href="/register" class="landing-nav-pill">S'inscrire</a>
        </nav>
    </div>
</header>

<main class="landing-page">
    <section class="landing-hero-card">
        <div class="landing-hero-copy">
            <span class="landing-kicker">Page de garde</span>
            <h1>Connectez-vous pour accéder à votre espace de ticketing.</h1>

            @if(session('error'))
                <div class="landing-alert landing-alert-error">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="landing-alert landing-alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <p>
                Centralisez vos projets, suivez vos tickets, rattachez vos clients et gardez une vision nette de votre activité depuis une seule plateforme.
            </p>

            <div class="landing-actions">
                <a href="/login" class="landing-button landing-button-primary">Se connecter</a>
                <a href="/register" class="landing-button landing-button-secondary">Créer un compte</a>
                @if($isAuthenticated)
                    <a href="/accueil" class="landing-button landing-button-ghost">Entrer dans l'accueil</a>
                @endif
            </div>

            @if($isAuthenticated)
                <p class="landing-session-note">
                    Session active pour {{ $userName }}. Vous pouvez rejoindre directement l'accueil de l'application.
                </p>
            @endif
        </div>

        <div class="landing-hero-visual">
            <div class="landing-visual-panel">
                <img src="{{ asset('assets/ticket-illustration.png') }}" alt="Illustration ticketing">
                <div class="landing-visual-badge">Connexion, inscription, accès rapide</div>
            </div>
        </div>
    </section>

    <section class="landing-feature-grid">
        <article class="landing-feature-card">
            <h2>Connexion</h2>
            <p>Accédez à votre espace personnel pour suivre les tickets et l'avancement des projets.</p>
        </article>

        <article class="landing-feature-card">
            <h2>Accueil applicatif</h2>
            <p>Une fois connecté, vous arrivez sur un accueil plus riche avec statistiques et accès rapides.</p>
        </article>

        <article class="landing-feature-card">
            <h2>Inscription</h2>
            <p>Créez un compte si vous n'en avez pas encore, puis connectez-vous immédiatement.</p>
        </article>
    </section>
</main>

</body>
</html>