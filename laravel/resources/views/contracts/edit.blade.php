@extends('layouts.app')

@section('content')

<main class="ticket-create" style="margin-top:100px; width:100%;">

<div class="ticket-layout">

    <form method="POST" action="{{ route('contracts.update', $project->id) }}" class="ticket-form">
        @csrf
        @method('PUT')

        <h1>Contrat du projet: {{ $project->name }}</h1>

        <div class="form-group">
            <label for="included_hours">Heures incluses</label>
            <input type="number" min="0" id="included_hours" name="included_hours" value="{{ old('included_hours', $project->contract?->included_hours ?? 0) }}" required>
        </div>

        <div class="form-group">
            <label for="hourly_rate">Taux horaire (EUR)</label>
            <input type="number" min="0" step="0.01" id="hourly_rate" name="hourly_rate" value="{{ old('hourly_rate', $project->contract?->hourly_rate ?? 0) }}" required>
        </div>

        <div class="form-group">
            <label for="starts_at">Début de validité</label>
            <input type="date" id="starts_at" name="starts_at" value="{{ old('starts_at', optional($project->contract?->starts_at)->format('Y-m-d')) }}">
        </div>

        <div class="form-group">
            <label for="ends_at">Fin de validité</label>
            <input type="date" id="ends_at" name="ends_at" value="{{ old('ends_at', optional($project->contract?->ends_at)->format('Y-m-d')) }}">
        </div>

        <button type="submit">Enregistrer le contrat</button>
        <a href="/projects/{{ $project->id }}" style="margin-left:0.8rem;">Annuler</a>

    </form>

    <aside class="ticket-info-box">
        <h2>Pourquoi ce contrat?</h2>
        <p>
            Ce bloc sert à suivre clairement les heures incluses et à préparer la facturation
            des dépassements sans ambiguïté.
        </p>
    </aside>

</div>

</main>

@endsection
