@extends('layouts.app')

@section('title', 'Tableau de bord Élève')
@section('header-title', 'Bonjour, Koffi 👋')

@section('content')
<div style="display: flex; flex-direction: column; gap: 28px;">

    <!-- TOP SECTION : Hero "Séance du jour" + Anneau de progression -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
        <!-- Grande Carte d'Accueil Sombre (Séance du jour) -->
        <div class="mk-hero-card">
            <div class="mk-hero-content">
                <span class="mk-hero-badge">
                    <span style="display: inline-block; width: 6px; height: 6px; background: var(--mk-lime); border-radius: 50%;"></span>
                    Séance recommandée · BEPC 2027
                </span>
                <h1 class="mk-hero-title">Théorème de Thalès & sa Réciproque</h1>
                <p class="mk-hero-desc">
                    Revois la configuration papillon et entraîne-toi sur 3 exercices types de l'examen national du Togo.
                </p>
                <div style="display: flex; gap: 12px; align-items: center;">
                    <a href="/student/reader/{{ $featuredResource->id ?? '' }}" class="mk-btn mk-btn-lime">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Lancer la séance (15 min)
                    </a>
                    <a href="/student/assistant?topic=thales" class="mk-btn mk-btn-lavender">
                        Poser une question à l'IA
                    </a>
                </div>
            </div>
        </div>

        <!-- Carte de Progression Anneau (Indigo + Citron) -->
        <div class="mk-progress-ring-card">
            <div class="mk-ring-circle">
                <svg width="80" height="80" viewBox="0 0 36 36">
                    <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                          fill="none" stroke="rgba(255, 255, 255, 0.2)" stroke-width="3.5" />
                    <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                          fill="none" stroke="var(--mk-lime)" stroke-width="3.5" stroke-dasharray="72, 100" />
                </svg>
                <div class="mk-ring-label">72%</div>
            </div>
            <div>
                <div style="font-size: 13px; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; opacity: 0.85;">
                    Objectif BEPC
                </div>
                <div style="font-size: 18px; font-weight: 800; margin-top: 2px;">
                    18 / 25 chapitres
                </div>
                <div style="font-size: 12px; opacity: 0.8; margin-top: 4px;">
                    +3 notions validées cette semaine
                </div>
            </div>
        </div>
    </div>

    <!-- MES MATIÈRES (Classe de 3e) -->
    <div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
            <h2 style="font-size: 20px; font-weight: 800; color: var(--mk-night);">Mes Matières (Classe de Troisième)</h2>
            <a href="/student/subjects" class="mk-btn mk-btn-outline mk-btn-sm">Voir tout le programme</a>
        </div>
        <p style="font-size: 13.5px; color: var(--mk-text-muted); margin-bottom: 12px;">
            Conforme au découpage officiel du Ministère des Enseignements Primaire, Secondaire et Technique (MEPST Togo).
        </p>

        <div class="mk-subjects-grid">
            <!-- Mathématiques -->
            <a href="/student/subjects/MATHS" class="mk-subject-card">
                <div class="mk-subject-header">
                    <div class="mk-subject-icon">📐</div>
                    <div>
                        <div class="mk-subject-name">Mathématiques</div>
                        <div class="mk-subject-meta">Coeff 4 · 4h/semaine</div>
                    </div>
                </div>
                <div style="font-size: 13px; color: var(--mk-text); font-weight: 600;">Progression globale</div>
                <div class="mk-progress-bar-bg">
                    <div class="mk-progress-bar-fill" style="width: 80%;"></div>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 12px; color: var(--mk-text-muted);">
                    <span>8 / 10 chapitres</span>
                    <strong>80%</strong>
                </div>
            </a>

            <!-- Physique-Chimie (PCT) -->
            <a href="/student/subjects/PCT" class="mk-subject-card">
                <div class="mk-subject-header">
                    <div class="mk-subject-icon" style="background: #e0f2fe; color: #0284c7;">⚡</div>
                    <div>
                        <div class="mk-subject-name">Physique-Chimie (PCT)</div>
                        <div class="mk-subject-meta">Coeff 3 · 3h/semaine</div>
                    </div>
                </div>
                <div style="font-size: 13px; color: var(--mk-text); font-weight: 600;">Progression globale</div>
                <div class="mk-progress-bar-bg">
                    <div class="mk-progress-bar-fill" style="width: 65%; background: #0284c7;"></div>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 12px; color: var(--mk-text-muted);">
                    <span>5 / 8 chapitres</span>
                    <strong>65%</strong>
                </div>
            </a>

            <!-- SVT -->
            <a href="/student/subjects/SVT" class="mk-subject-card">
                <div class="mk-subject-header">
                    <div class="mk-subject-icon" style="background: #ecfdf5; color: #059669;">🌱</div>
                    <div>
                        <div class="mk-subject-name">SVT</div>
                        <div class="mk-subject-meta">Coeff 3 · 3h/semaine</div>
                    </div>
                </div>
                <div style="font-size: 13px; color: var(--mk-text); font-weight: 600;">Progression globale</div>
                <div class="mk-progress-bar-bg">
                    <div class="mk-progress-bar-fill" style="width: 70%; background: #059669;"></div>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 12px; color: var(--mk-text-muted);">
                    <span>6 / 8 chapitres</span>
                    <strong>70%</strong>
                </div>
            </a>

            <!-- Français -->
            <a href="/student/subjects/FRANCAIS" class="mk-subject-card">
                <div class="mk-subject-header">
                    <div class="mk-subject-icon" style="background: #fef3c7; color: #d97706;">📚</div>
                    <div>
                        <div class="mk-subject-name">Français</div>
                        <div class="mk-subject-meta">Coeff 4 · 5h/semaine</div>
                    </div>
                </div>
                <div style="font-size: 13px; color: var(--mk-text); font-weight: 600;">Progression globale</div>
                <div class="mk-progress-bar-bg">
                    <div class="mk-progress-bar-fill" style="width: 75%; background: #d97706;"></div>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 12px; color: var(--mk-text-muted);">
                    <span>9 / 12 chapitres</span>
                    <strong>75%</strong>
                </div>
            </a>

            <!-- Anglais -->
            <a href="/student/subjects/ANGLAIS" class="mk-subject-card">
                <div class="mk-subject-header">
                    <div class="mk-subject-icon" style="background: #f3e8ff; color: #7e22ce;">🌍</div>
                    <div>
                        <div class="mk-subject-name">Anglais</div>
                        <div class="mk-subject-meta">Coeff 2 · 3h/semaine</div>
                    </div>
                </div>
                <div style="font-size: 13px; color: var(--mk-text); font-weight: 600;">Progression globale</div>
                <div class="mk-progress-bar-bg">
                    <div class="mk-progress-bar-fill" style="width: 60%; background: #7e22ce;"></div>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 12px; color: var(--mk-text-muted);">
                    <span>4 / 7 chapitres</span>
                    <strong>60%</strong>
                </div>
            </a>

            <!-- Histoire-Géographie -->
            <a href="/student/subjects/HIST_GEO" class="mk-subject-card">
                <div class="mk-subject-header">
                    <div class="mk-subject-icon" style="background: #ffedd5; color: #c2410c;">🗺️</div>
                    <div>
                        <div class="mk-subject-name">Histoire-Géographie</div>
                        <div class="mk-subject-meta">Coeff 2 · 3h/semaine</div>
                    </div>
                </div>
                <div style="font-size: 13px; color: var(--mk-text); font-weight: 600;">Progression globale</div>
                <div class="mk-progress-bar-bg">
                    <div class="mk-progress-bar-fill" style="width: 85%; background: #c2410c;"></div>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 12px; color: var(--mk-text-muted);">
                    <span>6 / 7 chapitres</span>
                    <strong>85%</strong>
                </div>
            </a>
        </div>
    </div>

    <!-- DERNIÈRES RESSOURCES OFFICIELLES PUBLIÉES -->
    <div class="mk-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h2 style="font-size: 18px; font-weight: 800; color: var(--mk-night);">Cours & Fiches Récents (MEPST Togo)</h2>
            <a href="/student/library" class="mk-btn mk-btn-outline mk-btn-sm">Ouvrir la bibliothèque</a>
        </div>

        <div style="display: flex; flex-direction: column; gap: 12px;">
            @foreach($publishedResources as $res)
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 14px; border: 1px solid var(--mk-border); border-radius: 14px; background: #ffffff;">
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <div style="width: 40px; height: 40px; border-radius: 10px; background: var(--mk-lavender); color: var(--mk-indigo); display: flex; align-items: center; justify-content: center; font-size: 18px;">
                            📄
                        </div>
                        <div>
                            <div style="font-weight: 700; color: var(--mk-night); font-size: 15px;">{{ $res->title }}</div>
                            <div style="font-size: 12px; color: var(--mk-text-muted); margin-top: 2px;">
                                {{ $res->subject->name ?? 'Maths' }} · {{ $res->educationLevel->name ?? '3e' }} · Source : <strong>MEPST Togo</strong>
                            </div>
                        </div>
                    </div>
                    <div style="display: flex; gap: 8px;">
                        <a href="/student/reader/{{ $res->id }}" class="mk-btn mk-btn-lavender mk-btn-sm">Lire le cours</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
