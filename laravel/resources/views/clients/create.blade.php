@extends('layouts.app')

@section('content')

<main class="ticket-create" style="margin-top:100px; width:100%;">

<div class="ticket-layout">

    <form method="POST" action="/clients" class="ticket-form">
        @csrf

        <h1>Ajouter un client</h1>

        @if ($errors->any())
            <div class="error" style="margin-bottom:1rem; padding:0.8rem; background:#ef4444; color:white; border-radius:6px;">
                Veuillez corriger les erreurs ci-dessous.
            </div>
        @endif

        <div class="form-group">
            <label for="name">Nom *</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
        </div>

        <div class="form-group">
            <label for="user_id">Compte client associé</label>
            <select id="user_id" name="user_id">
                <option value="">-- Aucun compte associé --</option>
                @foreach($clientUsers as $clientUser)
                    <option value="{{ $clientUser->id }}" {{ old('user_id') == $clientUser->id ? 'selected' : '' }}>
                        {{ $clientUser->name }} ({{ $clientUser->email }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="company">Entreprise</label>
            <input type="text" id="company" name="company" value="{{ old('company') }}">
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}">
        </div>

        <div class="form-group">
            <label for="phone">Téléphone</label>
            <input type="text" id="phone" name="phone" value="{{ old('phone') }}">
        </div>

        <button type="submit">Ajouter le client</button>

    </form>

    <aside class="ticket-info-box">
        <h2>Nouveau client</h2>
        <p>Un client peut être associé à plusieurs projets.</p>
    </aside>

</div>

</main>

@endsection
