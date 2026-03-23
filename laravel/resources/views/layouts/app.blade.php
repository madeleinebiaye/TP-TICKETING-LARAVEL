<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ticketing App</title>

    <!-- TON CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

<header class="main-header">
    <div class="header-content">

        <div class="logo">
            <a href="/">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo">
            </a>
        </div>

        <nav>
            <a href="/dashboard">Dashboard</a>
            <a href="/projects">Projets</a>
            <a href="/tickets">Tickets</a>
            <a href="/tickets/create">Créer un ticket</a>
        </nav>

    </div>
</header>

<main style="margin-top: 80px;">
    @yield('content')
</main>

</body>
</html>