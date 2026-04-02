@extends('layouts.app')

@section('content')

<!-- PANNEAU BLEU -->
<section style="
    flex: 1;
    background: linear-gradient(135deg, #2c7be5, #2d7bd4);
    color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 3rem;
">
    <div style="max-width: 420px;">
        <h1 style="font-size: 2.5rem;">Bienvenue 👋</h1>
        <p>Accédez à votre espace de gestion des projets et tickets.</p>
    </div>
</section>

<!-- CONNEXION -->
<section style="
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
">

    <div style="
        background-color: white;
        padding: 2.5rem;
        width: 100%;
        max-width: 420px;
        border-radius: 12px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.12);
    ">

        <h1 style="text-align: center; color: #2c7be5;">Connexion</h1>

        <form method="POST" action="#">
            @csrf

            <input type="email" name="email" placeholder="Email"><br><br>
            <input type="password" name="password" placeholder="Mot de passe"><br><br>

            <button type="submit">Se connecter</button>
        </form>

    </div>
</section>

@endsection