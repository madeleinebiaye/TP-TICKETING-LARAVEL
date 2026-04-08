# TP Ticketing Laravel

<p align="center">
  <strong>Application de gestion de tickets, projets et clients construite avec Laravel 12.</strong>
</p>

<p align="center">
  <a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12"></a>
  <a href="https://www.php.net/"><img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2+"></a>
  <a href="https://laravel.com/docs/12.x/sanctum"><img src="https://img.shields.io/badge/Auth-Sanctum-111827?style=for-the-badge" alt="Sanctum"></a>
  <a href="https://tailwindcss.com/"><img src="https://img.shields.io/badge/Tailwind-4.0-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="Tailwind 4"></a>
  <a href="https://vitejs.dev/"><img src="https://img.shields.io/badge/Vite-7.0-646CFF?style=for-the-badge&logo=vite&logoColor=white" alt="Vite 7"></a>
</p>

## Apercu

TP Ticketing Laravel est une application web de suivi d'activite qui permet de centraliser:

- la gestion des clients,
- le pilotage des projets,
- le suivi des tickets,
- le controle des heures estimees et consommees,
- l'acces web et API avec authentification.

Le projet propose une interface Blade pour les utilisateurs metier et une API REST protegee par Sanctum pour l'integration ou les usages front/mobile.

## Fonctionnalites

- Authentification web complete: inscription, connexion, deconnexion, mot de passe oublie et reinitialisation.
- Compatibilite legacy pour les mots de passe stockes en clair ou en MD5, avec rehash automatique a la premiere connexion valide.
- Gestion CRUD des clients.
- Gestion CRUD des projets lies aux clients.
- Gestion CRUD des tickets relies aux projets.
- Suivi des tickets par statut, type et priorite.
- Calcul des heures restantes et des heures facturables.
- Tableau de bord avec statistiques globales et indicateurs projet/client.
- Gestion des utilisateurs avec roles `admin` et `collaborateur`.
- API REST securisee avec tokens Sanctum.

## Regles metier principales

### Tickets

- Statuts: `Nouveau`, `En cours`, `Termine`
- Types: `Inclus`, `Facturable`
- Priorites: `Basse`, `Moyenne`, `Haute`

### Relations

- Un client possede plusieurs projets.
- Un projet appartient a un client.
- Un projet possede plusieurs tickets.
- Un ticket appartient a un projet.

## Stack technique

- Backend: Laravel 12
- Langage: PHP 8.2+
- Auth API: Laravel Sanctum
- Frontend: Blade + Tailwind CSS 4
- Bundler: Vite 7
- Tests: PHPUnit 11
- Outils dev: Laravel Pint, Laravel Pail, Concurrently

## Structure du depot

Le depot contient l'application dans le sous-dossier `laravel/`.

```text
TP-TICKETING-LARAVEL/
|- README.md
|- laravel/
|  |- app/
|  |- config/
|  |- database/
|  |- public/
|  |- resources/
|  |- routes/
|  |- tests/
|  |- artisan
|  |- composer.json
|  `- package.json
`- vendor/
```

Toutes les commandes d'installation et d'execution ci-dessous sont a lancer depuis `laravel/`.

## Installation

### 1. Cloner le depot

```bash
git clone https://github.com/madeleinebiaye/TP-TICKETING-LARAVEL.git
cd TP-TICKETING-LARAVEL/laravel
```

### 2. Installation rapide

```bash
composer run setup
```

Cette commande installe les dependances PHP et JS, cree le fichier `.env` si necessaire, genere la cle applicative, lance les migrations et produit le build frontend.

### 3. Base de donnees

Par defaut, le squelette Laravel est configure pour fonctionner facilement avec SQLite. Vous pouvez aussi utiliser MySQL en adaptant les variables du fichier `.env`.

Exemple MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ticketing
DB_USERNAME=root
DB_PASSWORD=
```

Puis:

```bash
php artisan migrate
```

### 4. Donnees de demonstration

```bash
php artisan db:seed
```

Compte cree par le seeder:

- Email: `test@example.com`
- Mot de passe: `password`

## Lancer le projet

### Mode developpement complet

```bash
composer run dev
```

Cette commande demarre:

- le serveur Laravel,
- l'ecoute de la queue,
- le flux de logs,
- Vite en mode developpement.

### Build frontend uniquement

```bash
npm run build
```

## Parcours principaux

### Interface web

- `/` : landing page
- `/login` : connexion
- `/register` : inscription
- `/accueil` : page d'accueil applicative
- `/dashboard` : tableau de bord
- `/clients` : gestion des clients
- `/projects` : gestion des projets
- `/tickets` : gestion des tickets
- `/users` : administration des utilisateurs

### API REST

Authentification:

- `POST /api/login`
- `GET /api/me`
- `POST /api/logout`

Ressources protegees par Sanctum:

- `GET|POST|PUT|DELETE /api/tickets`
- `GET /api/tickets/stats`
- `GET|POST|PUT|DELETE /api/projects`
- `GET|POST|PUT|DELETE /api/clients`

Exemple de connexion API:

```bash
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password",
    "device_name": "postman"
  }'
```

Puis utiliser le token retourne:

```bash
curl http://127.0.0.1:8000/api/me \
  -H "Accept: application/json" \
  -H "Authorization: Bearer VOTRE_TOKEN"
```

## Schema fonctionnel

| Entite | Champs notables |
| --- | --- |
| Users | `name`, `email`, `password`, `role` |
| Clients | `name`, `email`, `phone`, `company` |
| Projects | `name`, `description`, `client_id` |
| Tickets | `title`, `description`, `status`, `type`, `priority`, `collaborators`, `hours_estimated`, `hours_spent`, `project_id` |

Expose par le modele `Ticket`:

- `remaining_hours`
- `billable_hours`

## Tests et qualite

Executer les tests:

```bash
composer test
```

## Points d'attention

- L'application normalise encore la valeur legacy `ouvert` vers `Nouveau` pour conserver la compatibilite des donnees existantes.
- Les collaborateurs d'un ticket sont stockes en JSON, pas dans une table pivot dediee.
- Les suppressions observees dans le code sont des suppressions definitives.
- Les routes web de gestion sont protegees par `auth` et par un middleware de role selon les modules.
- Les routes API sont protegees par `auth:sanctum` apres connexion.

## Valeur du projet

Ce depot montre une base Laravel moderne pour un TP ou un mini-produit de gestion interne, avec une separation claire entre:

- interface web,
- logique metier,
- modele de donnees,
- API securisee.

Il peut servir de point de depart pour ajouter:

- commentaires sur les tickets,
- affectation fine par utilisateur,
- reporting avance,
- export PDF ou CSV,
- notifications mail.