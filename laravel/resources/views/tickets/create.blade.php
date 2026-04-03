@extends('layouts.app')

@section('content')

<main class="ticket-create" style="margin-top:100px; width:100%;">

    <div class="ticket-layout">

        <!-- FORMULAIRE -->
        <form method="POST" action="/tickets" class="ticket-form">
            @csrf

            <h1>Créer un ticket</h1>

            <!-- MESSAGE -->
            @if ($errors->any())
                <div id="form-message" class="error" style="display:block;">
                    Tous les champs obligatoires doivent être remplis.
                </div>
            @endif

            @if(session('success'))
                <div id="form-message" class="success" style="display:block;">
                    {{ session('success') }}
                </div>
            @endif

            <!-- TITRE -->
            <div class="form-group">
                <label for="title">Titre</label>
                <input type="text" id="title" name="title" required>
            </div>

            <!-- DESCRIPTION -->
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4" required></textarea>
            </div>

            <!-- STATUT -->
            <div class="form-group">
                <label for="status">Statut</label>
                <select name="status">
                   <option>Nouveau</option>
                   <option>En cours</option>
                   <option>Terminé</option>
                </select>
            </div>

            <!-- PRIORITÉ -->
            <div class="form-group">
                <label for="priority">Priorité</label>
                <select id="priority" name="priority">
                    <option>Basse</option>
                    <option>Moyenne</option>
                    <option>Haute</option>
                </select>
            </div>

            <!-- TYPE -->
            <div class="form-group">
                <label for="type">Type</label>
                <select id="type" name="type">
                    <option>Inclus</option>
                    <option>Facturable</option>
                </select>
            </div>

            <!-- TEMPS ESTIMÉ -->
            <div class="form-group">
                <label for="estimated_time">Temps estimé</label>
                <input type="number" id="estimated_time" name="estimated_time" min="0">
            </div>

            <!-- TEMPS PASSÉ -->
            <div class="form-group">
                <label for="spent_time">Temps réel passé</label>
                <input type="number" id="spent_time" name="spent_time" min="0">
            </div>

            <!-- COLLABORATEURS -->
            <div class="form-group">
                <label for="collaborators">Collaborateurs</label>
                <select id="collaborators" name="collaborators[]" multiple>
                    <option>Madeleine Biaye</option>
                    <option>Jean Dupont</option>
                    <option>Marie Martin</option>
                    <option>Paul Durand</option>
                </select>
            </div>

            <button type="submit">Créer le ticket</button>

        </form>

        <!-- BLOC IMAGE / INFO -->
        <aside class="ticket-info-box">

            <img src="{{ asset('assets/ticket-illustration.png') }}" alt="Illustration ticket">

            <h2>À quoi sert un ticket ?</h2>

            <p>
                Un ticket formalise une demande client et permet de suivre le travail réalisé.
            </p>

            <p>
                Il permet également d’évaluer le temps passé et de distinguer les tâches
                incluses du travail facturable.
            </p>

        </aside>

    </div>

</main>

@endsection