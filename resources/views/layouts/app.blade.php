<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MONKAYIER — Plateforme éducative souveraine avec IA pédagogique pour le Togo">
    <title>@yield('title', 'MONKAYIER') — Mon Carnet Éducatif</title>
    <link rel="stylesheet" href="/css/tokens.css">
    <link rel="stylesheet" href="/css/monkayier.css">
    @stack('styles')
</head>
<body>
    <div class="mk-app-layout">
        <!-- Sidebar Navigation Desktop -->
        <aside class="mk-sidebar">
            <div class="mk-logo-area">
                <div class="mk-logo-icon">M</div>
                <div class="mk-logo-text">MonKayier</div>
                <span class="mk-logo-badge">TOGO</span>
            </div>

            <!-- Catégorie : APPRENDRE -->
            <div class="mk-nav-section">
                <div class="mk-nav-heading">Apprendre</div>
                <ul class="mk-nav-list">
                    <li>
                        <a href="/student/dashboard" class="mk-nav-link {{ request()->is('student/dashboard*') ? 'active' : '' }}">
                            <svg class="mk-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Tableau de bord
                        </a>
                    </li>
                    <li>
                        <a href="/student/subjects" class="mk-nav-link {{ request()->is('student/subjects*') ? 'active' : '' }}">
                            <svg class="mk-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            Mes matières
                        </a>
                    </li>
                    <li>
                        <a href="/student/library" class="mk-nav-link {{ request()->is('student/library*') ? 'active' : '' }}">
                            <svg class="mk-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            Bibliothèque
                        </a>
                    </li>
                    <li>
                        <a href="/student/assistant" class="mk-nav-link {{ request()->is('student/assistant*') ? 'active' : '' }}">
                            <svg class="mk-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                            Assistant IA
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Catégorie : RÉVISER -->
            <div class="mk-nav-section">
                <div class="mk-nav-heading">Réviser</div>
                <ul class="mk-nav-list">
                    <li>
                        <a href="/student/exercises" class="mk-nav-link {{ request()->is('student/exercises*') ? 'active' : '' }}">
                            <svg class="mk-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                            Exercices & Quiz
                        </a>
                    </li>
                    <li>
                        <a href="/student/revisions" class="mk-nav-link {{ request()->is('student/revisions*') ? 'active' : '' }}">
                            <svg class="mk-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Planning d'examen
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Catégorie : SUIVRE -->
            <div class="mk-nav-section">
                <div class="mk-nav-heading">Suivre</div>
                <ul class="mk-nav-list">
                    <li>
                        <a href="/student/progress" class="mk-nav-link {{ request()->is('student/progress*') ? 'active' : '' }}">
                            <svg class="mk-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                            Progression BEPC
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Administration (Raccourci Back-Office si autorisé) -->
            <div class="mk-nav-section">
                <div class="mk-nav-heading">Gestion</div>
                <ul class="mk-nav-list">
                    <li>
                        <a href="/admin/dashboard" class="mk-nav-link {{ request()->is('admin*') ? 'active' : '' }}">
                            <svg class="mk-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Back-Office Admin
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Footer Utilisateur -->
            <div class="mk-sidebar-footer">
                <div class="mk-user-chip">
                    <div class="mk-avatar">K</div>
                    <div class="mk-user-info">
                        <span class="mk-user-name">Koffi Mensah</span>
                        <span class="mk-user-sub">Classe de 3e · Lomé</span>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Workspace Area -->
        <div class="mk-main-area">
            <header class="mk-header">
                <div class="mk-header-title">
                    @yield('header-title', 'Tableau de bord')
                </div>
                <div class="mk-header-actions">
                    <span class="mk-badge mk-badge-official">Togo · 2026-2027</span>
                    <a href="/student/assistant" class="mk-btn mk-btn-primary mk-btn-sm">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Poser une question
                    </a>
                </div>
            </header>

            <main class="mk-content">
                @yield('content')
            </main>
        </div>

        <!-- Mobile Bottom TabBar -->
        <nav class="mk-mobile-nav">
            <a href="/student/dashboard" class="mk-mobile-tab {{ request()->is('student/dashboard*') ? 'active' : '' }}">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Accueil
            </a>
            <a href="/student/subjects" class="mk-mobile-tab {{ request()->is('student/subjects*') ? 'active' : '' }}">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                Matières
            </a>
            <a href="/student/assistant" class="mk-mobile-tab {{ request()->is('student/assistant*') ? 'active' : '' }}">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
                Assistant
            </a>
            <a href="/student/library" class="mk-mobile-tab {{ request()->is('student/library*') ? 'active' : '' }}">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                Bibliothèque
            </a>
        </nav>
    </div>
    @stack('scripts')
</body>
</html>
