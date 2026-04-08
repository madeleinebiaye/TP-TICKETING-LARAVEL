@extends('layouts.app')

@section('content')

<main class="ticket-create" style="margin-top:100px; width:100%;">

<div class="ticket-layout">

    <form method="POST" action="/clients/{{ $client->id }}" class="ticket-form">
        @csrf
        @method('PUT')

        <h1>Modifier le client</h1>

        @if ($errors->any())
            <div class="error" style="margin-bottom:1rem; padding:0.8rem; background:#ef4444; color:white; border-radius:6px;">
                Veuillez corriger les erreurs ci-dessous.
            </div>
        @endif

        <div class="form-group">
            <label for="name">Nom *</label>
            <input type="text" id="name" name="name" value="{{ $client->name }}" required>
        </div>

        <div class="form-group">
            <label for="user_id">Compte client associé</label>
            <select id="user_id" name="user_id">
                <option value="">-- Aucun compte associé --</option>
                @foreach($clientUsers as $clientUser)
                    <option value="{{ $clientUser->id }}" {{ (int) old('user_id', $client->user_id) === (int) $clientUser->id ? 'selected' : '' }}>
                        {{ $clientUser->name }} ({{ $clientUser->email }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="company">Entreprise</label>
            <input type="text" id="company" name="company" value="{{ $client->company }}">
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ $client->email }}">
        </div>

        <div class="form-group">
            <label for="phone">Téléphone</label>
            <input type="text" id="phone" name="phone" value="{{ $client->phone }}">
        </div>

        <button type="submit">Mettre à jour</button>

    </form>

    <aside class="ticket-info-box">
        <h2>Modifier un client</h2>
        <p>Modifiez les informations de contact du client.</p>
    </aside>

</div>

</main>

@endsection
