@extends('layouts.app')

@section('content')

<main class="ticket-create" style="margin-top:100px; width:100%;">

<div class="ticket-layout">

    <form method="POST" action="/projects" class="ticket-form">
        @csrf

        <h1>Créer un projet</h1>

        @if ($errors->any())
            <div class="error">Tous les champs sont obligatoires</div>
        @endif

        @if(session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        <div class="form-group">
            <label for="title">Titre</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" required>{{ old('description') }}</textarea>
        </div>

        @if(isset($clients) && $clients->isNotEmpty())
            <div class="form-group">
                <label for="client_id">Client</label>
                <select id="client_id" name="client_id" required>
                    <option value="">-- Sélectionner un client --</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ (string) old('client_id', $selectedClientId ?? '') === (string) $client->id ? 'selected' : '' }}>
                            {{ $client->name }}{{ $client->company ? ' ('.$client->company.')' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif
        <button type="submit">Créer le projet</button>

    </form>

    <aside class="ticket-info-box">
        <h2>Informations</h2>
        <p>
            Un projet est le cadre de pilotage: il centralise les tickets, structure les priorites
            et facilite le suivi operationnel avec le client.
        </p>

        <ul style="margin:0.8rem 0 0 1.1rem; color:#334155; line-height:1.6;">
            <li><strong>Contexte metier:</strong> chaque ticket appartient a un projet, donc a un objectif clair.</li>
            <li><strong>Visibilite client:</strong> en reliant un client, la communication et les validations deviennent plus fluides.</li>
            <li><strong>Pilotage des couts:</strong> le projet sert de reference pour suivre charge, temps et facturation.</li>
            <li><strong>Organisation:</strong> vous pouvez filtrer les tickets par projet pour eviter un suivi disperse.</li>
            <li><strong>Workflow:</strong> priorite, statut, validation et relance se lisent mieux au niveau projet.</li>
        </ul>
    </aside>

</div>

</main>

@endsection
