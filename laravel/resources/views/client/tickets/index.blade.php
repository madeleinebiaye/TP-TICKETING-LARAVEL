@extends('layouts.app')

@section('content')

<main class="ticket-create" style="margin-top:100px; width:100%;">

<div class="ticket-layout">

    <div class="ticket-form">

        <h1>Validation client - {{ $client->name }}</h1>

        <p>Vous voyez uniquement les tickets facturables de vos projets.</p>

        @if(session('success'))
            <div class="success" style="margin-bottom:1rem;">{{ session('success') }}</div>
        @endif

        @if($tickets->isEmpty())
            <p>Aucun ticket facturable à valider pour le moment.</p>
        @else
            @foreach($tickets as $ticket)
                <div style="border:1px solid #ddd; border-radius:8px; padding:1rem; margin-bottom:1rem;">
                    <p><strong>{{ $ticket->title }}</strong> — {{ $ticket->project?->name }}</p>
                    <p><strong>Statut:</strong> {{ $ticket->status }}</p>
                    <p><strong>Heures passées:</strong> {{ $ticket->hours_spent }} h</p>

                    @if($ticket->validation_comment)
                        <p><strong>Commentaire actuel:</strong> {{ $ticket->validation_comment }}</p>
                    @endif

                    @if(!in_array($ticket->status, ['Validé', 'Refusé'], true))
                        <form method="POST" action="{{ route('client.tickets.validate', $ticket->id) }}" style="margin-top:0.5rem; display:grid; gap:0.5rem;">
                            @csrf
                            @method('PATCH')
                            <textarea name="comment" rows="2" placeholder="Commentaire (optionnel)"></textarea>
                            <button type="submit">Valider ce ticket</button>
                        </form>

                        <form method="POST" action="{{ route('client.tickets.refuse', $ticket->id) }}" style="margin-top:0.5rem; display:grid; gap:0.5rem;">
                            @csrf
                            @method('PATCH')
                            <textarea name="comment" rows="2" required placeholder="Expliquez pourquoi vous refusez la facturation"></textarea>
                            <button type="submit">Refuser ce ticket</button>
                        </form>
                    @else
                        <p style="color:#555;">Décision déjà enregistrée pour ce ticket.</p>
                    @endif
                </div>
            @endforeach
        @endif

    </div>

    <aside class="ticket-info-box">
        <h2>Validation</h2>
        <p>
            Un refus renvoie le ticket dans un état métier clair pour reprise par l'équipe.
        </p>
    </aside>

</div>

</main>

@endsection
