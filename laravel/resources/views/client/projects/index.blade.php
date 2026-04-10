@extends('layouts.app')

@section('content')

<main class="ticket-create" style="margin-top:100px; width:100%;">

<div class="ticket-layout">

    <div class="ticket-form">

        <h1>Mes projets - {{ $client->name }}</h1>

        <p>Vous consultez uniquement les projets et tickets rattachés à votre compte client.</p>

        @if($projects->isEmpty())
            <p>Aucun projet n'est encore rattaché à votre compte.</p>
        @else
            @foreach($projects as $project)
                <div style="border:1px solid #ddd; border-radius:8px; padding:1rem; margin-bottom:1rem;">
                    <p><strong>{{ $project->name }}</strong></p>
                    @if($project->description)
                        <p>{{ $project->description }}</p>
                    @endif

                    <p><strong>Tickets:</strong> {{ $project->tickets->count() }}</p>

                    @if($project->tickets->isEmpty())
                        <p style="color:#555;">Aucun ticket pour ce projet.</p>
                    @else
                        <div style="overflow-x:auto;">
                            <table class="tickets-table" style="margin-top:0.5rem;">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Statut</th>
                                        <th>Type</th>
                                        <th>Heures passées</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($project->tickets as $ticket)
                                        <tr>
                                            <td>{{ $ticket->title }}</td>
                                            <td>{{ $ticket->status }}</td>
                                            <td>{{ $ticket->type }}</td>
                                            <td>{{ $ticket->hours_spent }} h</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            @endforeach
        @endif

    </div>

    <aside class="ticket-info-box">
        <h2>Espace client</h2>
        <p>
            Pour les tickets facturables, vous pouvez valider ou refuser depuis la page Tickets client.
        </p>
        <p>
            <a href="{{ route('client.tickets.index') }}">Aller aux tickets facturables</a>
        </p>
    </aside>

</div>

</main>

@endsection
