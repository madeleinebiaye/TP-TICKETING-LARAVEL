@extends('layouts.app')

@section('content')

<div class="tickets" style="width:100%; padding:2rem;">

    <div class="tickets-header">
        <h1 class="tickets-title">Gestion des tickets</h1>
    </div>

    <table class="tickets-table">
        <thead>
            <tr>
                <th>Titre</th>
                <th>Statut</th>
                <th>Type</th>
                <th>Projet</th>
                <th>Priorité</th>
                <th>Restantes</th>
                <th>Facturables</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>

        @if($tickets->isEmpty())
            <tr>
                <td colspan="8">Aucun ticket créé pour le moment.</td>
            </tr>
        @else

            @foreach($tickets as $ticket)
                <tr>
                    <td>{{ $ticket->title }}</td>
                    <td>{{ $ticket->status }}</td>
                    <td>{{ $ticket->type }}</td>
                    <td>{{ optional($ticket->project)->name ?? 'Aucun' }}</td>
                    <td>{{ $ticket->priority ?? 'Aucune' }}</td>
                    <td>{{ $ticket->remaining_hours }} h</td>
                    <td>{{ $ticket->billable_hours }} h</td>
                    <td>
                        <a href="/tickets/{{ $ticket->id }}">
                            Voir détail
                        </a>
                        <a href="/tickets/{{ $ticket->id }}/edit">Modifier</a>
                    </td>
                </tr>
            @endforeach

        @endif

        </tbody>
    </table>

</div>

@endsection