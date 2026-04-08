@extends('layouts.app')

@section('content')

<div style="padding:2rem; width:100%;">

    <h1>Détail du ticket</h1>

    <p><strong>Titre :</strong> {{ $ticket->title }}</p>

    <p><strong>Description :</strong> {{ $ticket->description }}</p>

    <p><strong>Statut :</strong> {{ $ticket->status }}</p>

    <p><strong>Type :</strong> {{ $ticket->type }}</p>

    <p><strong>Priorité :</strong> {{ $ticket->priority ?? 'Aucune' }}</p>

    <p><strong>Projet :</strong> {{ optional($ticket->project)->name ?? 'Aucun' }}</p>

    <p><strong>Heures estimées :</strong> {{ $ticket->hours_estimated }} h</p>

    <p><strong>Heures passées :</strong> {{ $ticket->hours_spent }} h</p>

    <p><strong>Heures restantes :</strong> {{ $ticket->remaining_hours }} h</p>

    <p><strong>Heures facturables :</strong> {{ $ticket->billable_hours }} h</p>

    <p><strong>Collaborateurs :</strong>
        @if(empty($ticket->collaborator_labels))
            Aucun
        @else
            {{ implode(', ', $ticket->collaborator_labels) }}
        @endif
    </p>

    <br>

    <a href="/tickets">⬅ Retour</a>

</div>

@endsection