@extends('layouts.app')

@section('content')

@php
    $statusClass = [
        'Nouveau' => 'ticket-badge ticket-badge-blue',
        'En cours' => 'ticket-badge ticket-badge-amber',
        'En attente client' => 'ticket-badge ticket-badge-violet',
        'Terminé' => 'ticket-badge ticket-badge-slate',
        'À valider' => 'ticket-badge ticket-badge-orange',
        'Validé' => 'ticket-badge ticket-badge-green',
        'Refusé' => 'ticket-badge ticket-badge-red',
        'ouvert' => 'ticket-badge ticket-badge-blue',
    ];

    $typeClass = [
        'Inclus' => 'ticket-pill ticket-pill-cyan',
        'Facturable' => 'ticket-pill ticket-pill-rose',
    ];

    $priorityClass = [
        'Basse' => 'ticket-pill ticket-pill-green',
        'Moyenne' => 'ticket-pill ticket-pill-gold',
        'Haute' => 'ticket-pill ticket-pill-red',
    ];
@endphp

<div class="ticket-detail-page">

    <div class="ticket-detail-hero">
        <div>
            <p class="tickets-kicker">Fiche ticket</p>
            <h1>Détail du ticket</h1>
            <p class="tickets-subtitle">Les informations importantes ressortent mieux: statut, type, priorité et validation client.</p>
        </div>
        <div class="ticket-detail-badges">
            <span class="{{ $statusClass[$ticket->status] ?? 'ticket-badge ticket-badge-slate' }}">{{ $ticket->status }}</span>
            <span class="{{ $typeClass[$ticket->type] ?? 'ticket-pill ticket-pill-slate' }}">{{ $ticket->type ?? 'Aucun' }}</span>
            <span class="{{ $priorityClass[$ticket->priority] ?? 'ticket-pill ticket-pill-slate' }}">{{ $ticket->priority ?? 'Aucune' }}</span>
        </div>
    </div>

    <div class="ticket-detail-grid">
        <section class="ticket-detail-card ticket-detail-card-main">
            <h2>{{ $ticket->title }}</h2>
            <p class="ticket-detail-description">{{ $ticket->description }}</p>

            <div class="ticket-info-grid">
                <div class="ticket-info-item"><span>Projet</span><strong>{{ optional($ticket->project)->name ?? 'Aucun' }}</strong></div>
                <div class="ticket-info-item"><span>Heures estimées</span><strong>{{ $ticket->hours_estimated }} h</strong></div>
                <div class="ticket-info-item"><span>Heures passées</span><strong>{{ $ticket->hours_spent }} h</strong></div>
                <div class="ticket-info-item"><span>Heures restantes</span><strong>{{ $ticket->remaining_hours }} h</strong></div>
                <div class="ticket-info-item"><span>Heures facturables</span><strong>{{ $ticket->billable_hours }} h</strong></div>
                <div class="ticket-info-item"><span>Collaborateurs</span><strong>
                    @if(empty($ticket->collaborator_labels))
                        Aucun
                    @else
                        {{ implode(', ', $ticket->collaborator_labels) }}
                    @endif
                </strong></div>
            </div>
        </section>

        <aside class="ticket-detail-card ticket-detail-card-side">
            <h3>Validation client</h3>
            <p class="ticket-side-highlight">
                @if($ticket->type === 'Facturable')
                    {{ $ticket->status }}
                @else
                    Non concerné
                @endif
            </p>

            @if($ticket->validation_comment)
                <div class="ticket-validation-note">
                    <strong>Commentaire client</strong>
                    <p>{{ $ticket->validation_comment }}</p>
                </div>
            @endif
        </aside>
    </div>

    <section class="ticket-detail-card ticket-time-board">
        <h2>Entrées de temps</h2>

    @if(auth()->user()?->role === 'admin' || auth()->user()?->role === 'collaborateur')
        <form method="POST" action="{{ route('time-entries.store', $ticket->id) }}" class="ticket-time-form">
            @csrf

            <label for="entry_date">Date</label>
            <input id="entry_date" type="date" name="entry_date" value="{{ old('entry_date', now()->format('Y-m-d')) }}" required>

            <label for="duration_minutes">Durée (minutes)</label>
            <input id="duration_minutes" type="number" min="1" max="1440" name="duration_minutes" value="{{ old('duration_minutes') }}" required>

            <label for="comment">Commentaire</label>
            <textarea id="comment" name="comment" rows="2">{{ old('comment') }}</textarea>

            <button type="submit">Ajouter une entrée</button>
        </form>

        <p class="ticket-time-tip">
            Petite astuce: note chaque intervention, même courte, pour garder un historique propre.
        </p>
    @endif

    @if($ticket->timeEntries->isEmpty())
        <p>Aucune entrée de temps pour ce ticket.</p>
    @else
        <table class="tickets-table tickets-table-colorful" style="margin-top:1rem;">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Durée</th>
                    <th>Collaborateur</th>
                    <th>Commentaire</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ticket->timeEntries as $entry)
                    <tr>
                        <td>{{ $entry->entry_date?->format('d/m/Y') }}</td>
                        <td>{{ $entry->duration_minutes }} min</td>
                        <td>{{ $entry->user?->name ?? 'N/A' }}</td>
                        <td>{{ $entry->comment ?? '—' }}</td>
                        <td>
                            @if(auth()->user()?->role === 'admin' || (int) auth()->id() === (int) $entry->user_id)
                                <form method="POST" action="{{ route('time-entries.destroy', $entry->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Supprimer</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    </section>

    <a href="/tickets" class="ticket-back-link">Retour a la liste</a>

</div>

@endsection