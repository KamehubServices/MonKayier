<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MONKAYIER — Administration des Référentiels Scolaires et Gestion Éditoriale">
    <title>@yield('title', 'Administration') — Back-Office MONKAYIER</title>
    <link rel="stylesheet" href="/css/tokens.css">
    <link rel="stylesheet" href="/css/monkayier.css">
    @stack('styles')
</head>
<body>
    <div class="mk-app-layout">
        <!-- Sidebar Back-Office -->
        <aside class="mk-sidebar">
            <div class="mk-logo-area">
                <div class="mk-logo-icon" style="background: var(--mk-indigo);">A</div>
                <div class="mk-logo-text">Admin KAYIER</div>
                <span class="mk-badge mk-badge-lime" style="font-size: 9px;">MEPST</span>
            </div>

            <!-- GESTION RÉFÉRENTIELS -->
            <div class="mk-nav-section">
                <div class="mk-nav-heading">Référentiels Scolaires</div>
                <ul class="mk-nav-list">
                    <li>
                        <a href="/admin/dashboard" class="mk-nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                            <svg class="mk-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                            Vue d'ensemble
                        </a>
                    </li>
                    <li>
                        <a href="/admin/referentials" class="mk-nav-link {{ request()->is('admin/referentials*') ? 'active' : '' }}">
                            <svg class="mk-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            Cycles & Matières
                        </a>
                    </li>
                    <li>
                        <a href="/admin/curricula" class="mk-nav-link {{ request()->is('admin/curricula*') ? 'active' : '' }}">
                            <svg class="mk-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            Programmes & Arbres
                        </a>
                    </li>
                </ul>
            </div>

            <!-- GESTION CONTENUS & WORKFLOW -->
            <div class="mk-nav-section">
                <div class="mk-nav-heading">Gestion Éditoriale</div>
                <ul class="mk-nav-list">
                    <li>
                        <a href="/admin/resources" class="mk-nav-link {{ request()->is('admin/resources*') ? 'active' : '' }}">
                            <svg class="mk-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Validation & Publication
                        </a>
                    </li>
                    <li>
                        <a href="/admin/imports" class="mk-nav-link {{ request()->is('admin/imports*') ? 'active' : '' }}">
                            <svg class="mk-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            Imports CSV Batch
                        </a>
                    </li>
                    <li>
                        <a href="/admin/audit" class="mk-nav-link {{ request()->is('admin/audit*') ? 'active' : '' }}">
                            <svg class="mk-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Journal d'audit
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Retour Espace Élève -->
            <div class="mk-nav-section" style="margin-top: auto;">
                <ul class="mk-nav-list">
                    <li>
                        <a href="/student/dashboard" class="mk-nav-link">
                            <svg class="mk-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Espace Élève
                        </a>
                    </li>
                </ul>
            </div>

            <div class="mk-sidebar-footer">
                <div class="mk-user-chip">
                    <div class="mk-avatar" style="background: var(--mk-night);">A</div>
                    <div class="mk-user-info">
                        <span class="mk-user-name">Administrateur</span>
                        <span class="mk-user-sub">Superviseur Référentiels</span>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="mk-main-area">
            <header class="mk-header">
                <div class="mk-header-title">
                    @yield('header-title', 'Administration des Référentiels')
                </div>
                <div class="mk-header-actions">
                    <span class="mk-badge mk-badge-official">Année 2026-2027</span>
                    <a href="/admin/imports" class="mk-btn mk-btn-primary mk-btn-sm">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Importer CSV
                    </a>
                </div>
            </header>

            <main class="mk-content">
                @if (session('success'))
                    <div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 12px 16px; border-radius: 12px; margin-bottom: 20px;">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 12px 16px; border-radius: 12px; margin-bottom: 20px;">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
