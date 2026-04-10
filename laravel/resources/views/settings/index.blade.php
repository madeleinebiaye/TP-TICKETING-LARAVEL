@extends('layouts.app')

@section('content')

<div class="tickets" style="width:100%; padding:2rem;">

    <div class="tickets-header">
        <h1 class="tickets-title">Paramètres</h1>
    </div>

    @if(session('success'))
        <div class="auth-alert auth-alert-success" style="margin-bottom:1rem;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="auth-alert auth-alert-error" style="margin-bottom:1rem;">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap:1rem;">

        <section class="panel" style="padding:1rem;">
            <h2 style="margin-bottom:0.8rem;">Profil</h2>

            <form method="POST" action="{{ route('settings.profile.update') }}" style="display:grid; gap:0.75rem;">
                @csrf
                @method('PUT')

                <label for="name">Nom</label>
                <input id="name" name="name" type="text" value="{{ old('name', $user?->name) }}" required>

                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $user?->email) }}" required>

                <button type="submit">Mettre à jour le profil</button>
            </form>
        </section>

        <section class="panel" style="padding:1rem;">
            <h2 style="margin-bottom:0.8rem;">Sécurité</h2>

            <form method="POST" action="{{ route('settings.password.update') }}" style="display:grid; gap:0.75rem;">
                @csrf
                @method('PUT')

                <label for="current_password">Mot de passe actuel</label>
                <input id="current_password" name="current_password" type="password" required>

                <label for="password">Nouveau mot de passe</label>
                <input id="password" name="password" type="password" required>

                <label for="password_confirmation">Confirmation</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required>

                <button type="submit">Mettre à jour le mot de passe</button>
            </form>
        </section>

    </div>

</div>

@endsection
