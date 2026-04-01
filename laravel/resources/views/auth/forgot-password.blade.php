@extends('layouts.app')

@section('content')

<div class="ticket-create" style="margin-top:100px;">

    <h1>Mot de passe oublié</h1>

    <!-- MESSAGE -->
    @if(session('success'))
        <div style="
            margin-bottom:1rem;
            padding:0.8rem;
            border-radius:6px;
            color:white;
            background-color:#22c55e;
        ">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div style="
            margin-bottom:1rem;
            padding:0.8rem;
            border-radius:6px;
            color:white;
            background-color:#ef4444;
        ">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="/forgot-password" class="ticket-form">
        @csrf

        <div class="form-group">
            <label>Adresse email</label>
            <input 
                type="email" 
                name="email" 
                placeholder="exemple@email.com"
                value="{{ old('email') }}"
            >
        </div>

        <button type="submit">Réinitialiser</button>

    </form>

</div>

@endsection