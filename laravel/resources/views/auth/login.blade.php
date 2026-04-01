<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion | TP Ticketing</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body style="background-color: #eaf2ff;">

<header class="main-header">
    <div class="header-content">

        <div class="logo">
            <a href="/">
                <img src="{{ asset('assets/logo.png') }}">
            </a>
        </div>

    </div>
</header>

<main style="display:flex;margin-top:80px;">

    <!-- GAUCHE -->
    <section style="flex:1;background:#2c7be5;color:white;">
        <h1>Bienvenue 👋</h1>
    </section>

    <!-- DROITE -->
    <section style="flex:1;">
        <h1>Connexion</h1>

        @if(session('error'))
            <div style="color:red;">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="/login">
            @csrf

            <input type="email" name="email"><br>
            <input type="password" name="password"><br>

            <a href="/forgot-password">Mot de passe oublié ?</a><br>

            <button>Se connecter</button>

        </form>

    </section>

</main>

</body>
</html>