@extends('layouts.app')

@section('content')

<main class="ticket-create" style="margin-top:100px; width:100%;">

<div class="ticket-layout">

    <form method="POST" action="/projects" class="ticket-form">
        @csrf

        <h1>Creer un projet</h1>

        @if ($errors->any())
            <div class="error">Tous les champs sont obligatoires</div>
        @endif

        @if(session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        <div class="form-group">
            <label for="title">Titre</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" required>{{ old('description') }}</textarea>
        </div>

        <button type="submit">Creer le projet</button>

    </form>

    <aside class="ticket-info-box">
        <h2>Creer un projet</h2>
        <p>Un projet permet d'organiser les tickets.</p>
    </aside>

</div>

</main>

@endsection
