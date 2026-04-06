<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>TP Ticketing</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body style="background-color: #eaf2ff;">

<header class="main-header"
        style="position: fixed; top: 0; left: 0; right: 0; background-color: #2c7be5; z-index: 1000;">
    <div class="header-content">

        <!-- Logo -->
        <div class="logo">
            <a href="/accueil">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo">
            </a>
        </div>

        <!-- Menu -->
        <nav class="main-nav">
            <a href="/accueil">Accueil</a>
            <a href="/dashboard">Dashboard</a>
            <a href="/projects">Projets</a>
            <a href="/tickets">Tickets</a>
            <a href="/clients">Clients</a>
            <a href="/tickets/create">Créer un ticket</a>
            <a href="/projects/create">Créer un projet</a>
            @if(auth()->user()?->role === 'admin')
                <a href="/users">Utilisateurs</a>
            @endif
            <span class="nav-user-badge">{{ auth()->user()?->name ?? 'Utilisateur' }} ({{ auth()->user()?->role ?? 'N/A' }})</span>
            <form method="POST" action="/logout" class="nav-logout-form">
                @csrf
                <button type="submit" class="nav-logout-button">Déconnexion</button>
            </form>
        </nav>

    </div>
</header>

<main style="
    min-height: calc(100vh - 80px);
    display: flex;
    margin-top: 80px;
">

    @yield('content')

</main>

</body>
</html>