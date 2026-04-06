@extends('layouts.app')

@section('content')

<main class="ticket-create" style="margin-top:100px; width:100%;">

<div class="ticket-layout">

    <div class="ticket-form">

        <h1>{{ $client->name }}</h1>

        @if ($client->company)
            <p><strong>Entreprise :</strong> {{ $client->company }}</p>
        @endif
        @if ($client->email)
            <p><strong>Email :</strong> {{ $client->email }}</p>
        @endif
        @if ($client->phone)
            <p><strong>Téléphone :</strong> {{ $client->phone }}</p>
        @endif

        <h3 style="margin-top:1.5rem;">Projets associés</h3>

        @if($client->projects->isEmpty())
            <p>Aucun projet lié à ce client.</p>
        @else
            @foreach($client->projects as $project)
                <div style="border-bottom:1px solid #ccc; padding:0.7rem 0;">
                    <strong>{{ $project->name }}</strong> — {{ $project->description }}
                    <a href="/projects/{{ $project->id }}" style="margin-left:0.5rem;">Voir</a>
                    <span style="margin-left:0.5rem; color:#666;">({{ $project->tickets->count() }} ticket(s))</span>
                </div>
            @endforeach
        @endif

        <div style="margin-top:1.5rem;">
            <a href="/clients/{{ $client->id }}/edit">Modifier</a>
            &nbsp;|&nbsp;
            <a href="/clients">⬅ Retour</a>
        </div>

    </div>

    <aside class="ticket-info-box">
        <h2>Détail client</h2>
        <p>Consultez les projets liés à ce client.</p>
    </aside>

</div>

</main>

@endsection
