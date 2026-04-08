@extends('layouts.app')

@section('content')

<main class="ticket-create" style="margin-top:100px; width:100%;">

<div class="ticket-layout">

    <div class="ticket-form">

        <div class="tickets-header" style="margin-bottom:1.2rem;">
            <div>
                <p class="tickets-kicker">Pilotage projet</p>
                <h1 class="tickets-title" style="font-size:2rem;">Liste des projets</h1>
                <p class="tickets-subtitle">Regroupez les tickets par projet pour garder une vision claire des priorités, des coûts et des validations.</p>
            </div>
            <a href="/projects/create" class="tickets-header-action">Nouveau projet</a>
        </div>

        @if($projects->isEmpty())
            <p style="margin-top:1rem; color:#475569;">Aucun projet enregistré pour le moment.</p>
        @else
            @foreach($projects as $project)
                <div style="border:1px solid #dbeafe; border-radius:14px; padding:1rem; margin-bottom:0.9rem; background:#f8fbff;">
                    <h3 style="margin-bottom:0.45rem; color:#12335f;">{{ $project->name }}</h3>
                    <p style="margin-bottom:0.7rem; color:#334155;">{{ $project->description }}</p>
                    <p style="margin-bottom:0.8rem; color:#475569; font-size:0.9rem;">
                        <strong>Client:</strong> {{ optional($project->client)->name ?? 'Non affecté' }}
                    </p>

                    <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
                        <a class="ticket-action-link" href="/projects/{{ $project->id }}">Voir</a>
                        <a class="ticket-action-link" href="/projects/{{ $project->id }}/edit">Modifier</a>
                        <form method="POST" action="/projects/{{ $project->id }}" style="display:inline-flex;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="client-delete-btn">Supprimer</button>
                        </form>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <aside class="ticket-info-box">
        <h2>Informations projet</h2>
        <p>
            Le projet est votre unite de pilotage: il donne du sens aux tickets et structure la collaboration avec le client.
        </p>

        <ul style="margin:0.8rem 0 0 1.1rem; color:#334155; line-height:1.6;">
            <li><strong>Contexte:</strong> un ticket rattache a un projet est plus simple a comprendre et a prioriser.</li>
            <li><strong>Relation client:</strong> le projet sert de point de communication et de validation.</li>
            <li><strong>Suivi charge/couts:</strong> les heures passees sur les tickets remontent au niveau projet.</li>
            <li><strong>Workflow metier:</strong> facturation, validation et arbitrage se pilotent projet par projet.</li>
        </ul>
    </aside>
</div>

</main>

@endsection