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

    <p><strong>Validation client :</strong>
        @if($ticket->type === 'Facturable')
            {{ $ticket->status }}
        @else
            Non concerné (ticket inclus)
        @endif
    </p>

    @if($ticket->validation_comment)
        <p><strong>Commentaire client :</strong> {{ $ticket->validation_comment }}</p>
    @endif

    <p><strong>Collaborateurs :</strong>
        @if(empty($ticket->collaborator_labels))
            Aucun
        @else
            {{ implode(', ', $ticket->collaborator_labels) }}
        @endif
    </p>

    <hr style="margin:1.2rem 0;">

    <h2>Entrées de temps</h2>

    @if(auth()->user()?->role === 'admin' || auth()->user()?->role === 'collaborateur')
        <form method="POST" action="{{ route('time-entries.store', $ticket->id) }}" style="display:grid; gap:0.75rem; max-width:420px;">
            @csrf

            <label for="entry_date">Date</label>
            <input id="entry_date" type="date" name="entry_date" value="{{ old('entry_date', now()->format('Y-m-d')) }}" required>

            <label for="duration_minutes">Durée (minutes)</label>
            <input id="duration_minutes" type="number" min="1" max="1440" name="duration_minutes" value="{{ old('duration_minutes') }}" required>

            <label for="comment">Commentaire</label>
            <textarea id="comment" name="comment" rows="2">{{ old('comment') }}</textarea>

            <button type="submit">Ajouter une entrée</button>
        </form>

        <p style="margin-top:0.6rem; color:#555;">
            Petite astuce: note chaque intervention, même courte, pour garder un historique propre.
        </p>
    @endif

    @if($ticket->timeEntries->isEmpty())
        <p>Aucune entrée de temps pour ce ticket.</p>
    @else
        <table class="tickets-table" style="margin-top:1rem;">
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

    <br>

    <a href="/tickets">⬅ Retour</a>

</div>

@endsection