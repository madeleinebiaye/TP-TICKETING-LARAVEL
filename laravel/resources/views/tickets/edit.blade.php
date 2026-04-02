@extends('layouts.app')

@section('content')

<div style="padding:2rem; width:100%;">

    <h1>Modifier le ticket</h1>

    <form method="POST" action="/tickets/{{ $ticket->id }}">
        @csrf
        @method('PUT')

        <input type="text" name="title" value="{{ $ticket->title }}"><br><br>

        <textarea name="description">{{ $ticket->description }}</textarea><br><br>

        <input type="text" name="status" value="{{ $ticket->status }}"><br><br>

        <input type="number" name="hours_estimated" value="{{ $ticket->hours_estimated }}"><br><br>

        <input type="number" name="hours_spent" value="{{ $ticket->hours_spent }}"><br><br>

        <button type="submit">Mettre à jour</button>
    </form>

</div>

@endsection