@extends('layouts.app')

@section('content')

<main class="ticket-create" style="margin-top:100px; width:100%;">

<div class="ticket-layout">

    <div class="ticket-form">

        <h1>{{ $project->name }}</h1>

        <p>{{ $project->description }}</p>

        <h3>Tickets du projet</h3>

        @if($project->tickets->isEmpty())
            <p>Aucun ticket lié à ce projet pour le moment.</p>
        @else
            @foreach($project->tickets as $ticket)
                <div style="padding:0.5rem;">
                    🎫 {{ $ticket->title }} ({{ $ticket->status }})
                </div>
            @endforeach
        @endif

        <a href="/projects">⬅ Retour</a>

    </div>

    <aside class="ticket-info-box">
        <h2>Détail projet</h2>
        <p>Voir tous les tickets liés.</p>
    </aside>

</div>

</main>

@endsection