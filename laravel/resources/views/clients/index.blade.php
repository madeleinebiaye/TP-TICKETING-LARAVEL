@extends('layouts.app')

@section('content')

<main class="ticket-create" style="margin-top:100px; width:100%;">

<div class="ticket-layout">

    <div class="ticket-form">

        <h1>Liste des clients</h1>

        <a href="/clients/create" style="margin-bottom:1rem; display:inline-block;">+ Ajouter un client</a>

        @if(session('success'))
            <div class="success" style="margin-bottom:1rem; padding:0.8rem; background:#22c55e; color:white; border-radius:6px;">
                {{ session('success') }}
            </div>
        @endif

        @if($clients->isEmpty())
            <p style="margin-top:1rem;">Aucun client enregistré pour le moment.</p>
        @else
            <table class="tickets-table" style="margin-top:1rem;">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Entreprise</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Projets</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clients as $client)
                        <tr>
                            <td>{{ $client->name }}</td>
                            <td>{{ $client->company ?? '—' }}</td>
                            <td>{{ $client->email ?? '—' }}</td>
                            <td>{{ $client->phone ?? '—' }}</td>
                            <td>{{ $client->projects_count }}</td>
                            <td>
                                <a href="/clients/{{ $client->id }}">Voir</a>
                                <a href="/clients/{{ $client->id }}/edit">Modifier</a>
                                <form method="POST" action="/clients/{{ $client->id }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

    </div>

    <aside class="ticket-info-box">
        <h2>Clients</h2>
        <p>Gérez vos clients et consultez leurs projets associés.</p>
    </aside>

</div>

</main>

@endsection
