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

<div class="tickets" style="width:100%; padding:2rem;">

    <div class="tickets-header">
        <div>
            <p class="tickets-kicker">Pilotage support</p>
            <h1 class="tickets-title">Gestion des tickets</h1>
            <p class="tickets-subtitle">Un peu plus lisible, avec des repères visuels pour suivre les priorités et les validations.</p>
        </div>
        <a href="/tickets/create" class="tickets-header-action">Nouveau ticket</a>
    </div>

    <div class="tickets-stats-grid">
        <div class="panel ticket-stat-card ticket-stat-card-total"><strong>Total</strong><div id="api-total" class="ticket-stat-value">-</div></div>
        <div class="panel ticket-stat-card ticket-stat-card-new"><strong>Nouveaux</strong><div id="api-nouveau" class="ticket-stat-value">-</div></div>
        <div class="panel ticket-stat-card ticket-stat-card-progress"><strong>En cours</strong><div id="api-encours" class="ticket-stat-value">-</div></div>
        <div class="panel ticket-stat-card ticket-stat-card-done"><strong>Terminés</strong><div id="api-termine" class="ticket-stat-value">-</div></div>
    </div>

    <form id="api-ticket-form" class="ticket-api-form">
        <div class="ticket-api-field">
            <label for="api-title">Titre</label>
            <input id="api-title" name="title" type="text" required style="width:100%;">
        </div>
        <div class="ticket-api-field">
            <label for="api-description">Description</label>
            <input id="api-description" name="description" type="text" style="width:100%;">
        </div>
        <div class="ticket-api-field">
            <label for="api-status">Statut</label>
            <select id="api-status" name="status" style="width:100%;">
                <option value="Nouveau">Nouveau</option>
                <option value="En cours">En cours</option>
                <option value="Terminé">Terminé</option>
            </select>
        </div>
        <div class="ticket-api-field">
            <label for="api-type">Type</label>
            <select id="api-type" name="type" style="width:100%;">
                <option value="Inclus">Inclus</option>
                <option value="Facturable">Facturable</option>
            </select>
        </div>
        <button type="submit" class="ticket-api-button">Ajouter (API)</button>
    </form>

    <div id="api-ticket-message" class="ticket-api-message"></div>

    <div class="tickets-table-wrap">
    <table class="tickets-table tickets-table-colorful">
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

        <tbody id="tickets-tbody">

        @if($tickets->isEmpty())
            <tr>
                <td colspan="8">Aucun ticket créé pour le moment.</td>
            </tr>
        @else

            @foreach($tickets as $ticket)
                <tr>
                    <td>
                        <div class="ticket-cell-title">{{ $ticket->title }}</div>
                        <div class="ticket-cell-subtitle">{{ \Illuminate\Support\Str::limit($ticket->description, 64) }}</div>
                    </td>
                    <td><span class="{{ $statusClass[$ticket->status] ?? 'ticket-badge ticket-badge-slate' }}">{{ $ticket->status }}</span></td>
                    <td><span class="{{ $typeClass[$ticket->type] ?? 'ticket-pill ticket-pill-slate' }}">{{ $ticket->type ?? 'Aucun' }}</span></td>
                    <td>{{ optional($ticket->project)->name ?? 'Aucun' }}</td>
                    <td><span class="{{ $priorityClass[$ticket->priority] ?? 'ticket-pill ticket-pill-slate' }}">{{ $ticket->priority ?? 'Aucune' }}</span></td>
                    <td>{{ $ticket->remaining_hours }} h</td>
                    <td>{{ $ticket->billable_hours }} h</td>
                    <td class="ticket-actions-cell">
                        <a class="ticket-action-link" href="/tickets/{{ $ticket->id }}">
                            Voir détail
                        </a>
                        <a class="ticket-action-link" href="/tickets/{{ $ticket->id }}/edit">Modifier</a>
                    </td>
                </tr>
            @endforeach

        @endif

        </tbody>
    </table>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('api-ticket-form');
    const message = document.getElementById('api-ticket-message');
    const tbody = document.getElementById('tickets-tbody');

    async function loadStats() {
        const res = await fetch('/api/tickets/stats');
        if (!res.ok) return;
        const stats = await res.json();
        document.getElementById('api-total').textContent = stats.total;
        document.getElementById('api-nouveau').textContent = stats.nouveau;
        document.getElementById('api-encours').textContent = stats.encours;
        document.getElementById('api-termine').textContent = stats.termine;
    }

    function appendRow(ticket) {
        const emptyRow = tbody.querySelector('td[colspan="8"]');
        if (emptyRow) {
            emptyRow.parentElement.remove();
        }

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${ticket.title}</td>
            <td>${ticket.status}</td>
            <td>${ticket.type}</td>
            <td>${ticket.project_name ?? 'Aucun'}</td>
            <td>${ticket.priority ?? 'Aucune'}</td>
            <td>${ticket.remaining_hours} h</td>
            <td>${ticket.billable_hours} h</td>
            <td>
                <a href="/tickets/${ticket.id}">Voir détail</a>
                <a href="/tickets/${ticket.id}/edit">Modifier</a>
            </td>
        `;
        tbody.prepend(tr);
    }

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        message.textContent = 'Envoi en cours...';

        const payload = {
            title: document.getElementById('api-title').value,
            description: document.getElementById('api-description').value,
            status: document.getElementById('api-status').value,
            type: document.getElementById('api-type').value,
        };

        const res = await fetch('/api/tickets', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        if (!res.ok) {
            const data = await res.json().catch(() => ({}));
            message.textContent = data.message ?? 'Erreur API lors de la création';
            return;
        }

        const ticket = await res.json();
        appendRow(ticket);
        form.reset();
        message.textContent = 'Ticket créé via API sans rechargement.';
        loadStats();
    });

    loadStats();
});
</script>

@endsection