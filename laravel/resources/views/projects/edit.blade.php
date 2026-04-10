@extends('layouts.app')

@section('content')

<main class="ticket-create" style="margin-top:100px; width:100%;">

<div class="ticket-layout">

    <!-- FORMULAIRE -->
    <form method="POST" action="/projects/{{ $project->id }}" class="ticket-form">
        @csrf
        @method('PUT')

        <h1>Modifier le projet</h1>

        @if ($errors->any())
            <div class="error">
                Tous les champs sont obligatoires
            </div>
        @endif

        <!-- TITRE -->
        <div class="form-group">
            <label for="title">Titre</label>
            <input type="text" id="title" name="title" value="{{ $project->name }}" required>
        </div>

        <!-- DESCRIPTION -->
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4" required>{{ $project->description }}</textarea>
        </div>

        <!-- CLIENT -->
        <div class="form-group">
            <label for="client_id">Client</label>
            <select id="client_id" name="client_id" required>
                <option value="">— Sélectionner un client —</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ $project->client_id == $client->id ? 'selected' : '' }}>
                        {{ $client->name }}{{ $client->company ? ' ('.$client->company.')' : '' }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- BOUTON -->
        <button type="submit">Mettre à jour</button>

    </form>

    <!-- BLOC INFO -->
    <aside class="ticket-info-box">

        <img src="{{ asset('assets/image de projet.jpg') }}" alt="Projet">

        <h2>Modifier un projet</h2>

        <p>
            Vous pouvez modifier le nom et la description du projet.
        </p>

        <p>
            Cela impactera tous les tickets liés à ce projet.
        </p>

    </aside>

</div>

</main>

@endsection