@extends('layouts.app')

@section('content')

<main class="ticket-create" style="margin-top:100px; width:100%;">

    <div class="ticket-layout">

        <form method="POST" action="/tickets" class="ticket-form">
            @csrf

            <h1>Créer un ticket</h1>

            @if ($errors->any())
                <div class="form-alert form-alert-error">
                    Tous les champs obligatoires doivent être remplis.
                </div>
            @endif

            @if(session('success'))
                <div class="form-alert form-alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="form-group">
                <label for="title">Titre</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label for="status">Statut</label>
                <select id="status" name="status" required>
                   <option value="Nouveau" {{ old('status', 'Nouveau') == 'Nouveau' ? 'selected' : '' }}>Nouveau</option>
                   <option value="En cours" {{ old('status') == 'En cours' ? 'selected' : '' }}>En cours</option>
                   <option value="En attente client" {{ old('status') == 'En attente client' ? 'selected' : '' }}>En attente client</option>
                   <option value="Terminé" {{ old('status') == 'Terminé' ? 'selected' : '' }}>Terminé</option>
                   <option value="À valider" {{ old('status') == 'À valider' ? 'selected' : '' }}>À valider</option>
                   <option value="Validé" {{ old('status') == 'Validé' ? 'selected' : '' }}>Validé</option>
                   <option value="Refusé" {{ old('status') == 'Refusé' ? 'selected' : '' }}>Refusé</option>
                </select>
            </div>

            <div class="form-group">
                <label for="priority">Priorité</label>
                <select id="priority" name="priority">
                    <option value="">Aucune</option>
                    <option value="Basse" {{ old('priority') == 'Basse' ? 'selected' : '' }}>Basse</option>
                    <option value="Moyenne" {{ old('priority') == 'Moyenne' ? 'selected' : '' }}>Moyenne</option>
                    <option value="Haute" {{ old('priority') == 'Haute' ? 'selected' : '' }}>Haute</option>
                </select>
            </div>

            <div class="form-group">
                <label for="type">Type</label>
                <select id="type" name="type" required>
                    <option value="Inclus" {{ old('type', 'Inclus') == 'Inclus' ? 'selected' : '' }}>Inclus</option>
                    <option value="Facturable" {{ old('type') == 'Facturable' ? 'selected' : '' }}>Facturable</option>
                </select>
            </div>

            <div class="form-group">
                <label for="client_id">Client</label>
                <select id="client_id" name="client_id">
                    <option value="">Sélectionner un client (optionnel)</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ (string) old('client_id', $selectedClientId ?? '') === (string) $client->id ? 'selected' : '' }}>
                            {{ $client->name }}{{ $client->company ? ' ('.$client->company.')' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="project_id">Projet</label>
                <select id="project_id" name="project_id">
                    <option value="">Aucun</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" data-client-id="{{ $project->client_id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="estimated_time">Temps estimé</label>
                <input type="number" id="estimated_time" name="estimated_time" min="0" value="{{ old('estimated_time') }}">
            </div>

            <div class="form-group">
                <label for="spent_time">Temps réel passé</label>
                <input type="number" id="spent_time" name="spent_time" min="0" value="{{ old('spent_time') }}">
            </div>

            <div class="form-group">
                <label for="collaborators">Collaborateurs</label>
                <select id="collaborators" name="collaborators[]" multiple>
                    @foreach($collaborators as $collaborator)
                        <option value="{{ $collaborator->id }}" {{ in_array($collaborator->id, old('collaborators', [])) ? 'selected' : '' }}>
                            {{ $collaborator->name }} ({{ $collaborator->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit">Créer le ticket</button>

        </form>

        <aside class="ticket-info-box">

            <img src="{{ asset('assets/ticket-illustration.png') }}" alt="Illustration ticket">

            <h2>Informations</h2>

            <p>
                Un ticket formalise une demande client et permet de suivre le travail réalisé.
            </p>

            <p>
                Le fait de lier un ticket à un projet est essentiel, car le ticket ne vit pas seul:
                il appartient à un projet précis, ce qui donne immédiatement son contexte.
            </p>

            <ul style="margin:0.8rem 0 0 1.1rem; color:#334155; line-height:1.6;">
                <li><strong>Relier au client:</strong> comme le projet est lié à un client, chaque ticket est indirectement relié à ce client pour le suivi et la communication.</li>
                <li><strong>Suivre le temps et les coûts:</strong> les heures passées sur les tickets remontent au projet pour voir rapidement les dépassements de contrat ou de budget.</li>
                <li><strong>Organiser le travail:</strong> les tickets peuvent être regroupés, filtrés et analysés par projet, ce qui est plus lisible qu'un mélange global.</li>
                <li><strong>Gérer le workflow métier:</strong> validation client, facturation et priorités se pilotent au niveau du projet, pas du ticket seul.</li>
            </ul>

        </aside>

    </div>

</main>

<script>
    (function () {
        const clientSelect = document.getElementById('client_id');
        const projectSelect = document.getElementById('project_id');
        if (!clientSelect || !projectSelect) return;

        const options = Array.from(projectSelect.querySelectorAll('option'));

        function filterProjectsByClient() {
            const selectedClientId = clientSelect.value;

            options.forEach((option) => {
                const optionClientId = option.getAttribute('data-client-id');

                if (!option.value) {
                    option.hidden = false;
                    return;
                }

                option.hidden = !!selectedClientId && optionClientId !== selectedClientId;
            });

            const selectedOption = projectSelect.options[projectSelect.selectedIndex];
            if (selectedOption && selectedOption.hidden) {
                projectSelect.value = '';
            }
        }

        clientSelect.addEventListener('change', filterProjectsByClient);
        filterProjectsByClient();
    })();
</script>

@endsection