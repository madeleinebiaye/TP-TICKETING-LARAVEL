@extends('layouts.app')

@section('content')

<div class="tickets" style="width:100%; padding:2rem;">

    <div class="tickets-header">
        <h1 class="tickets-title">Gestion des tickets</h1>
    </div>

    <div style="display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:1rem;margin:1rem 0 1.25rem 0;">
        <div class="panel" style="padding:1rem;"><strong>Total</strong><div id="api-total">-</div></div>
        <div class="panel" style="padding:1rem;"><strong>Nouveaux</strong><div id="api-nouveau">-</div></div>
        <div class="panel" style="padding:1rem;"><strong>En cours</strong><div id="api-encours">-</div></div>
        <div class="panel" style="padding:1rem;"><strong>Terminés</strong><div id="api-termine">-</div></div>
    </div>

    <form id="api-ticket-form" style="display:grid;grid-template-columns:2fr 1.5fr 1fr 1fr auto;gap:0.6rem;align-items:end;margin-bottom:1rem;">
        <div>
            <label for="api-title">Titre</label>
            <input id="api-title" name="title" type="text" required style="width:100%;">
        </div>
        <div>
            <label for="api-description">Description</label>
            <input id="api-description" name="description" type="text" style="width:100%;">
        </div>
        <div>
            <label for="api-status">Statut</label>
            <select id="api-status" name="status" style="width:100%;">
                <option value="Nouveau">Nouveau</option>
                <option value="En cours">En cours</option>
                <option value="Terminé">Terminé</option>
            </select>
        </div>
        <div>
            <label for="api-type">Type</label>
            <select id="api-type" name="type" style="width:100%;">
                <option value="Inclus">Inclus</option>
                <option value="Facturable">Facturable</option>
            </select>
        </div>
        <button type="submit">Ajouter (API)</button>
    </form>

    <div id="api-ticket-message" style="margin-bottom:1rem;color:#1d4ed8;"></div>

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

        <tbody id="tickets-tbody">

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