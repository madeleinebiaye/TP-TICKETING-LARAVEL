-- Seed SQL pour faire tourner le workflow complet.
-- Prerequis: lancer d'abord les migrations Laravel.
-- Commande conseillee: php artisan migrate
-- Ensuite importer ce fichier dans la base ticketing.

USE ticketing;

SET @now = NOW();
SET @password_hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

-- Comptes de demonstration.
INSERT INTO users (name, email, password, role, remember_token, created_at, updated_at)
SELECT 'Admin Demo', 'admin.demo@ticketing.local', @password_hash, 'admin', NULL, @now, @now
WHERE NOT EXISTS (
    SELECT 1 FROM users WHERE email = 'admin.demo@ticketing.local'
);

INSERT INTO users (name, email, password, role, remember_token, created_at, updated_at)
SELECT 'Collaborateur Demo', 'collab.demo@ticketing.local', @password_hash, 'collaborateur', NULL, @now, @now
WHERE NOT EXISTS (
    SELECT 1 FROM users WHERE email = 'collab.demo@ticketing.local'
);

INSERT INTO users (name, email, password, role, remember_token, created_at, updated_at)
SELECT 'Client Demo', 'client.demo@ticketing.local', @password_hash, 'client', NULL, @now, @now
WHERE NOT EXISTS (
    SELECT 1 FROM users WHERE email = 'client.demo@ticketing.local'
);

SET @admin_id = (SELECT id FROM users WHERE email = 'admin.demo@ticketing.local' LIMIT 1);
SET @collab_id = (SELECT id FROM users WHERE email = 'collab.demo@ticketing.local' LIMIT 1);
SET @client_user_id = (SELECT id FROM users WHERE email = 'client.demo@ticketing.local' LIMIT 1);

SET @collaborators_single = CONCAT('[', @collab_id, ']');
SET @collaborators_team = CONCAT('[', @admin_id, ',', @collab_id, ']');

-- Client rattache au compte client pour que la validation fonctionne.
INSERT INTO clients (user_id, name, email, phone, company, created_at, updated_at)
SELECT @client_user_id, 'Client Acme', 'client.demo@ticketing.local', '770000000', 'Acme Services', @now, @now
WHERE NOT EXISTS (
    SELECT 1 FROM clients WHERE email = 'client.demo@ticketing.local'
);

SET @client_id = (SELECT id FROM clients WHERE email = 'client.demo@ticketing.local' LIMIT 1);

-- Projet de test relie au client.
INSERT INTO projects (name, description, client_id, created_at, updated_at)
SELECT 'Portail SAV Acme', 'Projet de demonstration pour tester le cycle complet ticket contrat et validation client.', @client_id, @now, @now
WHERE NOT EXISTS (
    SELECT 1 FROM projects WHERE name = 'Portail SAV Acme' AND client_id = @client_id
);

SET @project_id = (SELECT id FROM projects WHERE name = 'Portail SAV Acme' AND client_id = @client_id LIMIT 1);

-- Contrat du projet pour suivre les heures incluses.
INSERT INTO contracts (project_id, included_hours, hourly_rate, starts_at, ends_at, created_at, updated_at)
SELECT @project_id, 12, 75.00, '2026-04-01', '2026-12-31', @now, @now
WHERE NOT EXISTS (
    SELECT 1 FROM contracts WHERE project_id = @project_id
);

-- Ticket inclus pour tester le suivi du temps sur le forfait.
INSERT INTO tickets (title, description, hours_estimated, hours_spent, created_at, updated_at, status, project_id, type, priority, collaborators, validation_comment, validated_at)
SELECT
    'Correction page connexion',
    'Ticket inclus dans le forfait pour verifier le suivi des heures consommees.',
    4,
    3,
    @now,
    @now,
    'En cours',
    @project_id,
    'Inclus',
    'Haute',
    @collaborators_single,
    NULL,
    NULL
WHERE NOT EXISTS (
    SELECT 1 FROM tickets WHERE title = 'Correction page connexion' AND project_id = @project_id
);

-- Ticket facturable en attente de validation client.
INSERT INTO tickets (title, description, hours_estimated, hours_spent, created_at, updated_at, status, project_id, type, priority, collaborators, validation_comment, validated_at)
SELECT
    'Audit incidents production',
    'Ticket facturable qui doit apparaitre dans l espace client pour validation.',
    2,
    3,
    @now,
    @now,
    'À valider',
    @project_id,
    'Facturable',
    'Haute',
    @collaborators_team,
    NULL,
    NULL
WHERE NOT EXISTS (
    SELECT 1 FROM tickets WHERE title = 'Audit incidents production' AND project_id = @project_id
);

-- Ticket deja refuse pour visualiser le retour client.
INSERT INTO tickets (title, description, hours_estimated, hours_spent, created_at, updated_at, status, project_id, type, priority, collaborators, validation_comment, validated_at)
SELECT
    'Recette sprint avril',
    'Exemple de ticket facture puis refuse par le client pour reprendre le travail.',
    2,
    2,
    @now,
    @now,
    'Refusé',
    @project_id,
    'Facturable',
    'Moyenne',
    @collaborators_team,
    'Merci de corriger les derniers points avant facturation.',
    @now
WHERE NOT EXISTS (
    SELECT 1 FROM tickets WHERE title = 'Recette sprint avril' AND project_id = @project_id
);

-- Ticket deja valide pour verifier l historique.
INSERT INTO tickets (title, description, hours_estimated, hours_spent, created_at, updated_at, status, project_id, type, priority, collaborators, validation_comment, validated_at)
SELECT
    'Mise en production',
    'Exemple de ticket deja accepte et bon pour la facturation.',
    1,
    1,
    @now,
    @now,
    'Validé',
    @project_id,
    'Facturable',
    'Haute',
    @collaborators_team,
    'Bon pour facturation.',
    @now
WHERE NOT EXISTS (
    SELECT 1 FROM tickets WHERE title = 'Mise en production' AND project_id = @project_id
);

SET @ticket_inclus_id = (SELECT id FROM tickets WHERE title = 'Correction page connexion' AND project_id = @project_id LIMIT 1);
SET @ticket_pending_id = (SELECT id FROM tickets WHERE title = 'Audit incidents production' AND project_id = @project_id LIMIT 1);
SET @ticket_refused_id = (SELECT id FROM tickets WHERE title = 'Recette sprint avril' AND project_id = @project_id LIMIT 1);
SET @ticket_validated_id = (SELECT id FROM tickets WHERE title = 'Mise en production' AND project_id = @project_id LIMIT 1);

-- Saisies de temps coherentes avec les heures affichees.
INSERT INTO time_entries (ticket_id, user_id, entry_date, duration_minutes, comment, created_at, updated_at)
SELECT @ticket_inclus_id, @collab_id, '2026-04-06', 120, 'Analyse et correction du formulaire de connexion.', @now, @now
WHERE NOT EXISTS (
    SELECT 1 FROM time_entries WHERE ticket_id = @ticket_inclus_id AND comment = 'Analyse et correction du formulaire de connexion.'
);

INSERT INTO time_entries (ticket_id, user_id, entry_date, duration_minutes, comment, created_at, updated_at)
SELECT @ticket_inclus_id, @collab_id, '2026-04-07', 60, 'Tests complementaires sur le parcours utilisateur.', @now, @now
WHERE NOT EXISTS (
    SELECT 1 FROM time_entries WHERE ticket_id = @ticket_inclus_id AND comment = 'Tests complementaires sur le parcours utilisateur.'
);

INSERT INTO time_entries (ticket_id, user_id, entry_date, duration_minutes, comment, created_at, updated_at)
SELECT @ticket_pending_id, @admin_id, '2026-04-07', 90, 'Audit initial des journaux de production.', @now, @now
WHERE NOT EXISTS (
    SELECT 1 FROM time_entries WHERE ticket_id = @ticket_pending_id AND comment = 'Audit initial des journaux de production.'
);

INSERT INTO time_entries (ticket_id, user_id, entry_date, duration_minutes, comment, created_at, updated_at)
SELECT @ticket_pending_id, @collab_id, '2026-04-08', 90, 'Correctifs et verification finale avant validation client.', @now, @now
WHERE NOT EXISTS (
    SELECT 1 FROM time_entries WHERE ticket_id = @ticket_pending_id AND comment = 'Correctifs et verification finale avant validation client.'
);

INSERT INTO time_entries (ticket_id, user_id, entry_date, duration_minutes, comment, created_at, updated_at)
SELECT @ticket_refused_id, @collab_id, '2026-04-08', 120, 'Preparation de la recette fonctionnelle.', @now, @now
WHERE NOT EXISTS (
    SELECT 1 FROM time_entries WHERE ticket_id = @ticket_refused_id AND comment = 'Preparation de la recette fonctionnelle.'
);

INSERT INTO time_entries (ticket_id, user_id, entry_date, duration_minutes, comment, created_at, updated_at)
SELECT @ticket_validated_id, @admin_id, '2026-04-08', 60, 'Verification de mise en production et compte rendu.', @now, @now
WHERE NOT EXISTS (
    SELECT 1 FROM time_entries WHERE ticket_id = @ticket_validated_id AND comment = 'Verification de mise en production et compte rendu.'
);

-- Recalage simple des heures stockees pour rester coherent avec les saisies de temps.
UPDATE tickets
SET hours_spent = 3
WHERE id = @ticket_inclus_id;

UPDATE tickets
SET hours_spent = 3
WHERE id = @ticket_pending_id;

UPDATE tickets
SET hours_spent = 2
WHERE id = @ticket_refused_id;

UPDATE tickets
SET hours_spent = 1
WHERE id = @ticket_validated_id;

-- Comptes de test:
-- admin.demo@ticketing.local / password
-- collab.demo@ticketing.local / password
-- client.demo@ticketing.local / password