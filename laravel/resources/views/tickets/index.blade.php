@extends('layouts.app')

@section('content')

<div class="tickets">

<h1>Liste des tickets</h1>

<a href="/tickets/create">Créer un ticket</a>

<table class="tickets-table">

<tr>
    <th>Titre</th>
    <th>Heures prévues</th>
    <th>Heures utilisées</th>
    <th>Restantes</th>
    <th>Facturables</th>
    <th>Actions</th>
</tr>

@foreach($tickets as $ticket)
<tr>
    <td>{{ $ticket->title }}</td>
    <td>{{ $ticket->hours_estimated }}</td>
    <td>{{ $ticket->hours_spent }}</td>
    <td>{{ $ticket->remaining_hours }}</td>
    <td>{{ $ticket->billable_hours }}</td>
    <td>
        <a href="/tickets/{{ $ticket->id }}/edit">Modifier</a>

        <form action="/tickets/{{ $ticket->id }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit">Supprimer</button>
        </form>
    </td>
</tr>
@endforeach

</table>

</div>

@endsection