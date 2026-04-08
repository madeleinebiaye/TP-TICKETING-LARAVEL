@extends('layouts.app')

@section('content')

<main class="ticket-create" style="margin-top:100px; width:100%;">

<div class="ticket-layout">

    <div class="ticket-form">

        <h1>{{ $project->name }}</h1>

        <p>{{ $project->description }}</p>

        <h3>Contrat du projet</h3>
        @if($project->contract)
            <p><strong>Heures incluses :</strong> {{ $project->contract->included_hours }} h</p>
            <p><strong>Taux horaire :</strong> {{ number_format((float) $project->contract->hourly_rate, 2, ',', ' ') }} EUR</p>
            <p><strong>Période :</strong>
                {{ $project->contract->starts_at?->format('d/m/Y') ?? '—' }}
                au
                {{ $project->contract->ends_at?->format('d/m/Y') ?? '—' }}
            </p>
        @else
            <p>Aucun contrat défini pour ce projet.</p>
        @endif

        <p><strong>Temps inclus consommé :</strong> {{ intdiv($consumedIncludedMinutes, 60) }} h {{ $consumedIncludedMinutes % 60 }} min</p>
        <p><strong>Temps inclus restant :</strong> {{ intdiv($remainingIncludedMinutes, 60) }} h {{ $remainingIncludedMinutes % 60 }} min</p>
        <p><strong>Temps facturable :</strong> {{ intdiv($billableMinutes, 60) }} h {{ $billableMinutes % 60 }} min</p>

        @if(auth()->user()?->role === 'admin')
            <a href="{{ route('contracts.edit', $project->id) }}">Modifier le contrat</a>
        @endif

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