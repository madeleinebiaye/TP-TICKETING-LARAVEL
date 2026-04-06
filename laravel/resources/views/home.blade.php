@extends('layouts.app')

@section('content')

<main class="home-page">

	<section class="home-hero">
		<div class="home-hero-copy">
			<span class="home-kicker">Gestion de ticketing</span>
			<h1>Pilotez vos projets, vos tickets et vos clients depuis un seul espace.</h1>
			<p>
				Suivez l'avancement de vos demandes, répartissez le travail et gardez une vue claire
				sur l'activité de votre plateforme.
			</p>

			<div class="home-actions">
				<a href="/dashboard" class="home-button home-button-primary">Ouvrir le dashboard</a>
				<a href="/tickets/create" class="home-button home-button-secondary">Créer un ticket</a>
			</div>
		</div>

		<div class="home-hero-panel">
			<div class="home-hero-visual">
				<img src="{{ asset('assets/ticket-illustration.png') }}" alt="Illustration de ticketing">
				<div class="home-hero-badge">Suivi en temps réel</div>
			</div>

			<div class="home-hero-card home-hero-card-accent">
				<span class="home-metric-label">Projets</span>
				<strong>{{ $totalProjects }}</strong>
				<p>Espaces de travail structurés pour organiser les demandes.</p>
			</div>

			<div class="home-hero-card">
				<span class="home-metric-label">Tickets actifs</span>
				<strong>{{ $openTickets }}</strong>
				<p>Demandes actuellement en cours de traitement.</p>
			</div>
		</div>
	</section>

	<section class="home-stats-grid">
		<article class="home-stat-card">
			<span>Tickets enregistrés</span>
			<strong>{{ $totalTickets }}</strong>
		</article>

		<article class="home-stat-card">
			<span>Projets créés</span>
			<strong>{{ $totalProjects }}</strong>
		</article>

		<article class="home-stat-card">
			<span>Clients suivis</span>
			<strong>{{ $totalClients }}</strong>
		</article>
	</section>

	<section class="home-sections-grid">
		<article class="home-feature-card">
			<h2>Dashboard</h2>
			<p>Consultez les indicateurs clés, les projets récents et les statistiques client.</p>
			<a href="/dashboard">Voir le tableau de bord</a>
		</article>

		<article class="home-feature-card">
			<h2>Projets</h2>
			<p>Créez des projets, rattachez-les à des clients et centralisez les tickets.</p>
			<a href="/projects">Gérer les projets</a>
		</article>

		<article class="home-feature-card">
			<h2>Tickets</h2>
			<p>Suivez les demandes, les priorités, le temps estimé et le travail facturable.</p>
			<a href="/tickets">Voir les tickets</a>
		</article>

		<article class="home-feature-card">
			<h2>Clients</h2>
			<p>Retrouvez les clients, leurs projets associés et l'activité qui leur est liée.</p>
			<a href="/clients">Accéder aux clients</a>
		</article>
	</section>

</main>

@endsection