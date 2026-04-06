@extends('layouts.app')

@section('content')

<div style="padding:2rem; width:100%;">

    <h1>Modifier le ticket</h1>

    <form method="POST" action="/tickets/{{ $ticket->id }}">
        @csrf
        @method('PUT')

        <label for="title">Titre</label><br>
        <input type="text" id="title" name="title" value="{{ $ticket->title }}" required><br><br>

        <label for="description">Description</label><br>
        <textarea id="description" name="description">{{ $ticket->description }}</textarea><br><br>

        <label for="status">Statut</label><br>
        <select id="status" name="status" required>
            <option value="Nouveau" {{ $ticket->status === 'Nouveau' ? 'selected' : '' }}>Nouveau</option>
            <option value="En cours" {{ $ticket->status === 'En cours' ? 'selected' : '' }}>En cours</option>
            <option value="Terminé" {{ $ticket->status === 'Terminé' ? 'selected' : '' }}>Terminé</option>
        </select><br><br>

        <label for="type">Type</label><br>
        <select id="type" name="type" required>
            <option value="Inclus" {{ $ticket->type === 'Inclus' ? 'selected' : '' }}>Inclus</option>
            <option value="Facturable" {{ $ticket->type === 'Facturable' ? 'selected' : '' }}>Facturable</option>
        </select><br><br>

        <label for="priority">Priorité</label><br>
        <select id="priority" name="priority">
            <option value="" {{ empty($ticket->priority) ? 'selected' : '' }}>Aucune</option>
            <option value="Basse" {{ $ticket->priority === 'Basse' ? 'selected' : '' }}>Basse</option>
            <option value="Moyenne" {{ $ticket->priority === 'Moyenne' ? 'selected' : '' }}>Moyenne</option>
            <option value="Haute" {{ $ticket->priority === 'Haute' ? 'selected' : '' }}>Haute</option>
        </select><br><br>

        <label for="project_id">Projet</label><br>
        <select id="project_id" name="project_id">
            <option value="">Aucun</option>
            @foreach($projects as $project)
                <option value="{{ $project->id }}" {{ (int) $ticket->project_id === (int) $project->id ? 'selected' : '' }}>
                    {{ $project->name }}
                </option>
            @endforeach
        </select><br><br>

        <label for="hours_estimated">Heures estimées</label><br>
        <input type="number" id="hours_estimated" name="hours_estimated" min="0" value="{{ $ticket->hours_estimated }}"><br><br>

        <label for="hours_spent">Heures passées</label><br>
        <input type="number" id="hours_spent" name="hours_spent" min="0" value="{{ $ticket->hours_spent }}"><br><br>

        @php
            $collaboratorOptions = ['Madeleine Biaye', 'Jean Dupont', 'Marie Martin', 'Paul Durand'];
            $selectedCollaborators = $ticket->collaborators ?? [];
        @endphp

        <label for="collaborators">Collaborateurs</label><br>
        <select id="collaborators" name="collaborators[]" multiple>
            @foreach($collaboratorOptions as $name)
                <option value="{{ $name }}" {{ in_array($name, $selectedCollaborators, true) ? 'selected' : '' }}>{{ $name }}</option>
            @endforeach
        </select><br><br>

        <button type="submit">Mettre à jour</button>
    </form>

</div>

@endsection