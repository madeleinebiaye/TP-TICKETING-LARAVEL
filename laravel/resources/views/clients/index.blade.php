@extends('layouts.app')

@section('content')

<main class="ticket-create client-page" style="margin-top:100px; width:100%; max-width:none;">

<div class="ticket-layout client-layout">

    <div class="ticket-form client-main-card">

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
            <div class="client-table-wrap" style="margin-top:1rem;">
            <table class="tickets-table client-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Entreprise</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Compte client</th>
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
                            <td>{{ $client->user?->email ?? '—' }}</td>
                            <td>{{ $client->projects_count }}</td>
                            <td class="client-actions">
                                <a class="client-link" href="/clients/{{ $client->id }}">Voir</a>
                                <a class="client-link" href="/clients/{{ $client->id }}/edit">Modifier</a>
                                <form method="POST" action="/clients/{{ $client->id }}" style="display:inline-flex;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="client-delete-btn">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        @endif

    </div>

    <aside class="ticket-info-box client-side-card">
        <h2>Clients</h2>
        <p>
            Cette vue centralise votre portefeuille client pour faciliter le suivi commercial et operationnel.
        </p>
        <ul style="margin:0.8rem 0 0 1.1rem; color:#334155; line-height:1.6;">
            <li><strong>Vision globale:</strong> chaque fiche client regroupe les projets et les tickets associes.</li>
            <li><strong>Communication:</strong> le compte client relie permet un dialogue plus clair sur les validations.</li>
            <li><strong>Pilotage:</strong> vous identifiez rapidement les clients actifs et leur niveau de charge.</li>
            <li><strong>Organisation:</strong> la liste sert de point d'entree pour prioriser les actions par client.</li>
        </ul>
    </aside>

</div>

</main>

@endsection
