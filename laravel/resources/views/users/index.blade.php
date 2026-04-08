@extends('layouts.app')

@section('content')

<div class="tickets" style="width:100%; padding:2rem;">

    <div class="tickets-header">
        <h1 class="tickets-title">Gestion des utilisateurs (Admin)</h1>
    </div>

    @if(session('success'))
        <div class="auth-alert auth-alert-success" style="margin-bottom:1rem;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="auth-alert auth-alert-error" style="margin-bottom:1rem;">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="auth-alert auth-alert-error" style="margin-bottom:1rem;">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <table class="tickets-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Rôle actuel</th>
                <th>Créer le</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                    <td>{{ optional($user->created_at)->format('d/m/Y H:i') }}</td>
                    <td>
                        <form method="POST" action="/users/{{ $user->id }}" style="display:flex; gap:0.5rem; align-items:center;">
                            @csrf
                            @method('PUT')
                            <select name="role" style="min-width:150px;">
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>admin</option>
                                <option value="collaborateur" {{ $user->role === 'collaborateur' ? 'selected' : '' }}>collaborateur</option>
                            </select>
                            <button type="submit">Mettre à jour</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Aucun utilisateur trouvé.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>

@endsection