@extends('layouts.app')

@section('content')

<main class="ticket-create" style="margin-top:100px; width:100%;">

<div class="ticket-layout">

    <div class="ticket-form">

        <h1>Liste des projets</h1>

        <a href="/projects/create">+ Creer un projet</a>

        @if($projects->isEmpty())
            <p style="margin-top:1rem;">Aucun projet cree pour le moment.</p>
        @else
            @foreach($projects as $project)
                <div style="border-bottom:1px solid #ccc; padding:1rem 0;">

                    <h3>{{ $project->name }}</h3>

                    <p>{{ $project->description }}</p>

                    <a href="/projects/{{ $project->id }}">Voir</a>
                    <a href="/projects/{{ $project->id }}/edit">Modifier</a>

                    <form method="POST" action="/projects/{{ $project->id }}" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Supprimer</button>
                    </form>

                </div>
            @endforeach
        @endif

    </div>

    <aside class="ticket-info-box">
        <h2>Projets</h2>
        <p>Liste des projets existants.</p>
    </aside>

</div>

</main>

@endsection
