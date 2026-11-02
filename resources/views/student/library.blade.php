@extends('layouts.student')

@section('title', 'Bibliothèque — MonKayier')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/katex.min.css">
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/katex.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/contrib/auto-render.min.js"></script>
@php
    $shortSubjects = [
        'Éducation Civique et Morale' => 'ECM',
        'Physique-Chimie et Technologie' => 'PCT',
        'Sciences de la Vie et de la Terre' => 'SVT',
        'Histoire et Géographie' => 'Hist-Géo',
        'Philosophie' => 'Philo',
        'Mathématiques' => 'Maths',
        'Français' => 'Français',
        'Anglais' => 'Anglais',
        'Allemand' => 'Allemand',
        'Espagnol' => 'Espagnol',
        'Informatique' => 'Info',
        'Technologie' => 'Techno',
    ];

    $subjectIcons = [
        'Mathématiques' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="9" x2="20" y2="9"></line><line x1="4" y1="15" x2="20" y2="15"></line><line x1="10" y1="3" x2="8" y2="21"></line><line x1="16" y1="3" x2="14" y2="21"></line></svg>',
        'Physique-Chimie et Technologie' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 2v7.527a2 2 0 0 1-.211.896L4.72 20.55a1 1 0 0 0 .9 1.45h12.76a1 1 0 0 0 .9-1.45l-5.069-10.127A2 2 0 0 1 14 9.527V2"></path><path d="M8.5 2h7"></path><path d="M7 16h10"></path></svg>',
        'Sciences de la Vie et de la Terre' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a10 10 0 0 0-7.07 17.07C7.5 21 12 22 12 22s4.5-1 7.07-2.93A10 10 0 0 0 12 2z"></path><path d="M12 6v10"></path><path d="M8 10l4-4 4 4"></path></svg>',
        'Français' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>',
        'Philosophie' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a7 7 0 0 0-7 7c0 2.38 1.19 4.47 3 5.74V17a2 2 0 0 0 2 2h4a2 2 0 0 0 2-2v-2.26c1.81-1.27 3-3.36 3-5.74a7 7 0 0 0-7-7z"></path><path d="M9 21h6"></path></svg>',
        'Anglais' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>',
        'Histoire et Géographie' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>',
        'Éducation Civique et Morale' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v18"></path><path d="M3 7l9-4 9 4"></path><path d="M5 11l-3 7h6l-3-7z"></path><path d="M19 11l-3 7h6l-3-7z"></path></svg>',
        'Allemand' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m5 8 6 6"></path><path d="m4 14 6-6 2-3"></path><path d="M2 5h12"></path><path d="M7 2h1"></path><path d="m22 22-5-10-5 10"></path><path d="M14 18h6"></path></svg>',
        'Espagnol' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line></svg>',
        'Technologie' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>',
    ];

    $defaultIcon = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>';
@endphp

<style>
/* ==========================================================================
   DESIGN SYSTEM BIBLIOTHÈQUE INTERACTIVE & CONFORT VISUEL (6ème à Terminale)
   ========================================================================== */

/* 1. Grille et Cartes 3D Flip Interactives */
.biblio-grid-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(285px, 1fr));
    gap: 14px;
    margin-bottom: 24px;
}

.biblio-flip-card {
    background-color: transparent;
    perspective: 1000px;
    height: 205px;
    cursor: pointer;
    user-select: none;
    position: relative;
    border-radius: 14px;
}
.biblio-flip-inner {
    position: relative;
    width: 100%;
    height: 100%;
    transition: transform 0.45s cubic-bezier(0.16, 1, 0.3, 1);
    transform-style: preserve-3d;
    border-radius: 14px;
}
.biblio-flip-card:hover .biblio-flip-inner,
.biblio-flip-card.is-flipped .biblio-flip-inner {
    transform: rotateY(180deg);
}
.biblio-flip-front, 
.biblio-flip-back {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    border-radius: 14px;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 14px 16px;
}

/* Face 1 (Recto) : Présentation aérée, épurée et très lisible */
.biblio-flip-front {
    background: #FFFFFF;
    border: 1.5px solid #E2E8F0;
    box-shadow: 0 1px 4px rgba(15, 23, 42, 0.04);
    color: #0E1022;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}
.biblio-flip-card:hover .biblio-flip-front {
    border-color: #3fa9f5;
    box-shadow: 0 6px 18px -4px rgba(20, 50, 92, 0.12);
}

/* Face 2 (Verso) : Fiche de synthèse sombre MonKayier avec boutons d'action */
.biblio-flip-back {
    background: linear-gradient(145deg, #14325c 0%, #1e4a84 100%);
    border: 1.5px solid #14325c;
    color: #FFFFFF;
    transform: rotateY(180deg);
    box-shadow: 0 8px 22px -4px rgba(20, 50, 92, 0.28);
}

/* 2. Thèmes de Confort Visuel pour le lecteur immersif */
#libraryImmersionModal.theme-day {
    --immersion-bg: #F8FAFC;
    --immersion-top: #FFFFFF;
    --immersion-border: #E2E8F0;
    --immersion-divider: rgba(14, 16, 34, 0.12);
    --immersion-text: #0E1022;
    --immersion-pane: #FFFFFF;
    --immersion-card: #F8FAFC;
    --immersion-card-border: #E2E8F0;
    --immersion-subtext: #64748B;
    --immersion-input-bg: #FFFFFF;
    --immersion-input-border: #CBD5E1;
}
#libraryImmersionModal.theme-sepia {
    --immersion-bg: #F5EFE0;
    --immersion-top: #EDE4D0;
    --immersion-border: #DCD0B6;
    --immersion-divider: rgba(60, 45, 30, 0.15);
    --immersion-text: #382B1E;
    --immersion-pane: #FAF4E8;
    --immersion-card: #EFE6D2;
    --immersion-card-border: #DCD0B6;
    --immersion-subtext: #6D5B4B;
    --immersion-input-bg: #FAF5E9;
    --immersion-input-border: #CFC2A5;
}
#libraryImmersionModal.theme-night {
    --immersion-bg: #0B0E1B;
    --immersion-top: #14172B;
    --immersion-border: rgba(255, 255, 255, 0.1);
    --immersion-divider: rgba(255, 255, 255, 0.15);
    --immersion-text: #F1F5F9;
    --immersion-pane: #121526;
    --immersion-card: #181D33;
    --immersion-card-border: rgba(255, 255, 255, 0.12);
    --immersion-subtext: #94A3B8;
    --immersion-input-bg: #181D33;
    --immersion-input-border: rgba(255, 255, 255, 0.2);
}

.comfort-mode-wrap {
    display: inline-flex;
    align-items: center;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.18);
    border-radius: 8px;
    padding: 2px 3px;
    gap: 2px;
}
.comfort-btn {
    border: none;
    background: transparent;
    color: var(--immersion-text);
    opacity: 0.75;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11.5px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.15s ease;
    white-space: nowrap;
    box-shadow: none !important;
}
.comfort-btn.active {
    background: #D4F25C;
    color: #0E1022;
    opacity: 1;
    font-weight: 850;
    box-shadow: none !important;
}

/* 3. Zone IA de l'Immersion (Split Screen à Droite - Hauteur Verrouillée au Viewport) */
.immersion-ai-pane {
    background: var(--immersion-pane);
    border-left: 1.5px solid var(--immersion-border);
    border-right: none;
    display: flex;
    flex-direction: column;
    height: 100% !important;
    max-height: 100% !important;
    min-height: 0 !important;
    box-sizing: border-box;
    padding: 12px;
    gap: 8px;
    overflow: hidden !important;
}
.immersion-chat-feed {
    display: flex;
    flex-direction: column;
    gap: 8px;
    overflow-y: auto !important;
    overflow-x: hidden;
    flex: 1 1 0% !important;
    min-height: 0 !important;
    padding-right: 4px;
}
.immersion-chat-feed::-webkit-scrollbar {
    width: 6px;
}
.immersion-chat-feed::-webkit-scrollbar-thumb {
    background: rgba(148, 163, 184, 0.4);
    border-radius: 6px;
}
.immersion-chips-container {
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
    flex-shrink: 0 !important;
    max-height: 86px;
    overflow-y: auto;
}
.biblio-chip-btn {
    background: var(--immersion-card);
    border: 1px solid var(--immersion-card-border);
    color: var(--immersion-text);
    font-size: 11px;
    font-weight: 600;
    padding: 5px 10px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.15s ease;
    text-align: left;
    box-shadow: none !important;
}
.biblio-chip-btn:hover {
    background: #D4F25C;
    color: #0E1022;
    border-color: #D4F25C;
}
.immersion-chat-input-bar {
    margin-top: auto;
    flex-shrink: 0 !important;
    display: flex;
    gap: 6px;
    align-items: center;
    background: var(--immersion-input-bg);
    border: 1.5px solid var(--immersion-input-border);
    border-radius: 8px;
    padding: 5px 8px;
    box-shadow: none !important;
}
.immersion-mic-btn {
    background: var(--immersion-card);
    border: 1px solid var(--immersion-card-border);
    color: var(--immersion-text);
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    flex: none;
    transition: background 0.15s ease;
    box-shadow: none !important;
}
.immersion-input-field {
    border: none;
    outline: none;
    width: 100%;
    font: inherit;
    background: transparent;
    font-size: 12.5px;
    color: var(--immersion-text);
}
.immersion-send-btn {
    background: #0E1022;
    color: #FFFFFF;
    border: none;
    border-radius: 8px;
    padding: 6px 12px;
    font-weight: 750;
    cursor: pointer;
    font-size: 12px;
    flex: none;
    box-shadow: none !important;
}
.immersion-send-btn:hover {
    background: #14325c;
}
.ai-status-pill {
    font-size: 10.5px;
    font-weight: 750;
    color: #059669;
    background: rgba(16, 185, 129, 0.12);
    padding: 2px 7px;
    border-radius: 999px;
    white-space: nowrap;
}

/* Boutons et Utilitaires Communs */
.biblio-tab-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 9px 18px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 750;
    text-decoration: none;
    border: 1.5px solid transparent;
    cursor: pointer;
    transition: all 0.18s ease;
    box-shadow: none !important;
    white-space: nowrap;
    line-height: 1;
}
.biblio-tab-btn.active {
    background: linear-gradient(135deg, #14325c 0%, #1e4a84 100%);
    color: #FFFFFF;
    border-color: #14325c;
    box-shadow: 0 3px 10px rgba(20, 50, 92, 0.22) !important;
}
.biblio-tab-btn:not(.active) {
    background: #FFFFFF;
    color: #14325c;
    border-color: #E2E8F0;
}
.biblio-tab-btn:not(.active):hover {
    background: #F8FAFC;
    border-color: #3fa9f5;
    color: #0284c7;
}

.biblio-input {
    border: 1.5px solid #E2E8F0;
    border-radius: 8px;
    background: #FFFFFF;
    color: #0E1022;
    font-size: 12.5px;
    font-weight: 600;
    padding: 8px 12px;
    outline: none;
    box-shadow: none !important;
    box-sizing: border-box;
    transition: border-color 0.18s ease;
}
.biblio-input:focus {
    border-color: #3fa9f5;
}

.biblio-btn-submit {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    background: linear-gradient(135deg, #14325c 0%, #1e4a84 100%);
    color: #FFFFFF;
    border: 1.5px solid #14325c;
    border-radius: 8px;
    padding: 8px 18px;
    font-size: 12.5px;
    font-weight: 750;
    cursor: pointer;
    box-shadow: 0 2px 6px rgba(20, 50, 92, 0.2) !important;
    transition: all 0.18s ease;
    text-decoration: none;
    white-space: nowrap;
}
.biblio-btn-submit:hover {
    background: linear-gradient(135deg, #1b3d6e 0%, #2968a8 100%);
    border-color: #3fa9f5;
    box-shadow: 0 4px 12px rgba(20, 50, 92, 0.28) !important;
    transform: translateY(-1px);
}

.biblio-btn-reset {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    background: #FFFFFF;
    color: #64748B;
    border: 1.5px solid #E2E8F0;
    border-radius: 8px;
    padding: 8px 14px;
    font-size: 12.5px;
    font-weight: 700;
    cursor: pointer;
    box-shadow: none !important;
    transition: all 0.18s ease;
    text-decoration: none;
    white-space: nowrap;
}
.biblio-btn-reset:hover {
    background: #F1F5F9;
    color: #0E1022;
    border-color: #CBD5E1;
}

.biblio-badge {
    display: inline-flex;
    align-items: center;
    font-size: 10.5px;
    font-weight: 750;
    padding: 2.5px 7px;
    border-radius: 6px;
    border: 1px solid #E2E8F0;
    background: #F8FAFC;
    color: #475569;
    white-space: nowrap;
}

.immersion-btn-action {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.18);
    color: var(--immersion-text);
    font-size: 12px;
    font-weight: 700;
    padding: 6px 12px;
    border-radius: 8px;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.18s ease;
}
.immersion-btn-action.active-ai {
    background: #D4F25C;
    color: #0E1022;
    border-color: #D4F25C;
    font-weight: 850;
}
.immersion-close-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: var(--immersion-text);
    font-size: 12.5px;
    font-weight: 750;
    padding: 6px 14px;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.18s ease;
}
.immersion-close-btn:hover {
    background: rgba(255, 255, 255, 0.22);
}
@keyframes timerPulse {
    0% { transform: scale(1); opacity: 0.9; }
    100% { transform: scale(1.15); opacity: 1; }
}
</style>

{{-- ========================================================= --}}
{{-- EN-TÊTE PRINCIPAL INTÉGRÉ : TITRE, SWITCH & CTA RÉVISION  --}}
{{-- ========================================================= --}}
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; flex-wrap: wrap; gap: 12px;">
    <div>
        <h1 style="font-size: 22px; font-weight: 850; margin: 0; color: #0E1022; letter-spacing: -0.02em; white-space: nowrap;">
            Bibliothèque
        </h1>
    </div>

    {{-- Switch Cours & Examens Centré dans la rangée du haut --}}
    <div style="display: flex; justify-content: center; flex: 1; min-width: 280px;">
        <div style="display: inline-flex; align-items: center; justify-content: center; gap: 6px; background: #F1F5F9; padding: 4px; border-radius: 12px; border: 1.5px solid #E2E8F0; box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04); flex-wrap: wrap;">
            <a href="{{ route('student.library', array_merge(request()->except('page'), ['tab' => 'courses'])) }}" 
               class="biblio-tab-btn {{ $tab === 'courses' ? 'active' : '' }}" style="padding: 7px 16px;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" style="display: block;"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                <span style="display: inline-block;">Cours & Manuels Officiels</span>
                <span style="font-size: 11px; padding: 2px 7px; border-radius: 999px; font-weight: 800; display: inline-flex; align-items: center; {{ $tab === 'courses' ? 'background: rgba(63, 169, 245, 0.3); color: #FFFFFF;' : 'background: #FFFFFF; color: #14325c; border: 1px solid #E2E8F0;' }}">
                    {{ $totalCoursesCount }}
                </span>
            </a>

            <a href="{{ route('student.library', array_merge(request()->except('page'), ['tab' => 'examens'])) }}" 
               class="biblio-tab-btn {{ $tab === 'examens' ? 'active' : '' }}" style="padding: 7px 16px;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" style="display: block;"><path d="M5 21V4M5 4h12l-2 4 2 4H5"></path></svg>
                <span style="display: inline-block;">Examens Nationaux & Annales</span>
                <span style="font-size: 11px; padding: 2px 7px; border-radius: 999px; font-weight: 800; display: inline-flex; align-items: center; {{ $tab === 'examens' ? 'background: rgba(63, 169, 245, 0.3); color: #FFFFFF;' : 'background: #FFFFFF; color: #14325c; border: 1px solid #E2E8F0;' }}">
                    {{ $totalExamsCount }}
                </span>
            </a>
        </div>
    </div>

    <div>
        <a href="{{ route('student.chat') }}" class="biblio-btn-submit" style="background:#D4F25C; color:#0E1022; border-color:#D4F25C;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><path d="M12 2a8 8 0 0 0-8 8c0 3.37 2.1 6.25 5 7.42V19a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2v-1.58c2.9-1.17 5-4.05 5-7.42a8 8 0 0 0-8-8z"></path></svg>
            <span>Passer en Révision IA</span>
            <span>→</span>
        </a>
    </div>
</div>

{{-- ========================================================= --}}
{{-- BARRE DE FILTRES MULTI-CRITÈRES (CLASSE, SÉRIE, ANNÉE...) --}}
{{-- ========================================================= --}}
<div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 12px; box-shadow: none !important; padding: 8px 12px; margin-bottom: 12px;">
    <form method="GET" action="{{ route('student.library') }}" style="margin: 0;">
        <input type="hidden" name="tab" value="{{ $tab }}">

        <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
            {{-- Recherche textuelle --}}
            <div style="flex: 2; min-width: 200px; position: relative;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="2.2" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); pointer-events: none;"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" name="search" value="{{ $search }}" placeholder="Rechercher par titre, matière, série, code officiel..." 
                       class="biblio-input" style="width: 100%; padding-left: 32px;">
            </div>

            {{-- Filtre Classe --}}
            <div style="flex: 1; min-width: 120px;">
                <select name="level_id" class="biblio-input" style="width: 100%;">
                    <option value="">Toutes les classes</option>
                    @foreach($educationLevels as $lvl)
                        <option value="{{ $lvl->id }}" {{ ($levelId ?? '') == $lvl->id ? 'selected' : '' }}>
                            {{ $lvl->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filtre Série --}}
            <div style="flex: 1; min-width: 110px;">
                <select name="serie" class="biblio-input" style="width: 100%;">
                    <option value="">Toutes séries</option>
                    @foreach($seriesList as $s)
                        <option value="{{ $s }}" {{ ($serie ?? '') == $s ? 'selected' : '' }}>
                            {{ $s == 'COLLEGE' ? 'Collège' : 'Série ' . $s }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filtre Matière --}}
            <div style="flex: 1.2; min-width: 130px;">
                <select name="subject_id" class="biblio-input" style="width: 100%;">
                    <option value="">Toutes matières</option>
                    @foreach($subjects as $sb)
                        <option value="{{ $sb->id }}" {{ ($subjectId ?? '') == $sb->id ? 'selected' : '' }}>
                            {{ $sb->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filtre Année (présent sur l'onglet Examens ou disponible) --}}
            @if($tab === 'examens')
            <div style="flex: 0.8; min-width: 100px;">
                <select name="year" class="biblio-input" style="width: 100%;">
                    <option value="">Toutes années</option>
                    @foreach($yearsList as $y)
                        <option value="{{ $y }}" {{ ($year ?? '') == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            {{-- Boutons d'action --}}
            <div style="display: flex; gap: 6px;">
                <button type="submit" class="biblio-btn-submit">
                    Filtrer
                </button>
                <a href="{{ route('student.library', ['tab' => $tab, 'all' => 1]) }}" class="biblio-btn-reset">
                    Réinitialiser
                </a>
            </div>
        </div>
    </form>
</div>

{{-- ========================================================= --}}
{{-- CONTENU ONGLET 1 : COURS & MANUELS OFFICIELS             --}}
{{-- ========================================================= --}}
@if($tab === 'courses')
    @if(!$courses || $courses->isEmpty())
        <div style="background: #FFFFFF; border: 1.5px dashed #CBD5E1; padding: 40px 20px; text-align: center; color: #64748B;">
            <h3 style="font-size: 16px; font-weight: 800; color: #0E1022; margin: 0 0 6px 0;">Aucun cours officiel trouvé</h3>
            <p style="font-size: 13px; margin: 0 0 14px 0;">Aucun cours ne correspond à cette combinaison de filtres.</p>
            <a href="{{ route('student.library', ['tab' => 'courses', 'all' => 1]) }}" class="biblio-btn-submit">
                Voir tous les cours
            </a>
        </div>
    @else
        <div class="biblio-grid-container">
            @foreach($courses as $c)
                @php
                    $iconSvg = $subjectIcons[$c->subject_name] ?? $defaultIcon;
                    $short = $shortSubjects[$c->subject_name] ?? $c->subject_name;
                    $pdfUrl = route('student.examens.preview', $c->file_id ?? '01a161a5-8b7b-7390-bd95-fce05b0de8f0');
                    $downloadUrl = !empty($c->file_id) ? route('student.examens.download', $c->file_id) : '#';
                    $levelMeta = ($c->level_name ?? 'Lycée') . ' · ' . (in_array(strtoupper($c->serie ?? ''), ['COLLEGE', 'BEPC']) ? 'Collège' : 'Série ' . ($c->serie ?? 'D'));
                @endphp
                <div class="biblio-flip-card" 
                     onclick="handleBiblioCardClick(this, '{{ $pdfUrl }}', '{{ addslashes($c->title) }}', '{{ $c->file_code }}', '{{ addslashes($c->subject_name) }}', '{{ addslashes($levelMeta) }}', '{{ $downloadUrl }}', '{{ $c->id }}', '{{ addslashes($c->summary ?? '') }}', false, event)">
                    <div class="biblio-flip-inner">
                        {{-- FACE 1 (RECTO) : Lisible, aérée, grand confort visuel --}}
                        <div class="biblio-flip-front">
                            <div>
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                                    <span style="font-size: 13.5px; font-weight: 850; color: #0E1022;">
                                        {{ $c->subject_name }}
                                    </span>
                                    <div style="width: 28px; height: 28px; border: 1.5px solid #E2E8F0; background: #F8FAFC; color: #0E1022; display: flex; align-items: center; justify-content: center; border-radius: 7px; flex: none;">
                                        {!! $iconSvg !!}
                                    </div>
                                </div>

                                <div style="margin-bottom: 6px;">
                                    <span class="biblio-badge" style="font-family: monospace; font-size: 9.5px; margin-bottom: 3px; display: inline-block;">
                                        {{ $c->file_code }}
                                    </span>
                                    <h3 style="font-size: 13.5px; font-weight: 800; color: #0E1022; margin: 0; line-height: 1.3; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                        {{ $c->title }}
                                    </h3>
                                </div>
                            </div>

                            <div>
                                <div style="display: flex; gap: 5px; align-items: center; flex-wrap: wrap; margin-bottom: 8px;">
                                    <span class="biblio-badge" style="{{ in_array(strtoupper($c->serie ?? ''), ['COLLEGE', 'BEPC']) || str_contains($c->level_name ?? '', 'Collège') ? 'background: #DCFCE7; border-color: #86EFAC; color: #166534;' : 'background: #EDE9FE; border-color: #DDD6FE; color: #6D28D9;' }}">
                                        {{ $c->level_name ?? 'Lycée' }}
                                    </span>
                                    <span class="biblio-badge" style="background: #FEF3C7; border-color: #FDE68A; color: #B45309;">
                                        {{ in_array(strtoupper($c->serie ?? ''), ['COLLEGE', 'BEPC']) ? 'Collège' : 'Série ' . ($c->serie ?? 'Générale') }}
                                    </span>
                                </div>
                                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 10.5px; color: #3fa9f5; font-weight: 700;">
                                    <span>↺ Survoler pour détails</span>
                                    <span style="color: #059669;">Tuteur IA connecté</span>
                                </div>
                            </div>
                        </div>

                        {{-- FACE 2 (VERSO) : Résumé du cours + Bouton Immersion en Grand avec Tuteur IA --}}
                        <div class="biblio-flip-back">
                            <div>
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                    <span style="font-size: 10px; font-weight: 800; background: rgba(63, 169, 245, 0.22); color: #3fa9f5; padding: 2px 7px; border-radius: 5px;">
                                        MEPST Officiel
                                    </span>
                                    <span style="font-size: 10.5px; color: #D4F25C; font-weight: 800;">
                                        {{ $short }}
                                    </span>
                                </div>
                                <div style="font-size: 11.5px; color: #E2E8F0; line-height: 1.45; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; margin-bottom: 8px;">
                                    {{ !empty($c->summary) ? $c->summary : "Ensemble didactique officiel comprenant objectifs ministériels et compétences du BAC." }}
                                </div>
                            </div>

                            <div style="display: flex; gap: 6px; align-items: center; width: 100%;">
                                <button type="button" 
                                        onclick="event.stopPropagation(); openLibraryImmersion('{{ $pdfUrl }}', '{{ addslashes($c->title) }}', '{{ $c->file_code }}', '{{ addslashes($c->subject_name) }}', '{{ addslashes($levelMeta) }}', '{{ $downloadUrl }}', '{{ $c->id }}', '{{ addslashes($c->summary ?? '') }}', false);" 
                                        style="flex: 1; display: inline-flex; align-items: center; justify-content: center; gap: 6px; background: #D4F25C; color: #0E1022; font-size: 12px; font-weight: 850; padding: 8px 12px; border-radius: 8px; border: none; cursor: pointer; transition: transform 0.15s ease;">
                                    <span>Consulter & Tuteur IA</span>
                                    <span style="font-size: 13px;">→</span>
                                </button>
                                @if(!empty($c->file_id))
                                    <a href="{{ $downloadUrl }}" onclick="event.stopPropagation();" class="biblio-btn-reset" title="Télécharger le document PDF" style="padding: 7px 10px; background: rgba(255,255,255,0.15); border-color: rgba(255,255,255,0.25); color: #fff;">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination Cours --}}
        <div style="display: flex; justify-content: center; margin-bottom: 24px;">
            <div style="display: flex; gap: 6px; align-items: center;">
                @if($courses->onFirstPage())
                    <span class="biblio-btn-reset" style="opacity: 0.4; pointer-events: none;">← Précédent</span>
                @else
                    <a href="{{ $courses->previousPageUrl() }}" class="biblio-btn-reset">← Précédent</a>
                @endif

                <span style="font-size: 12px; font-weight: 700; color: #64748B; padding: 0 8px;">
                    Page {{ $courses->currentPage() }} / {{ $courses->lastPage() }}
                </span>

                @if($courses->hasMorePages())
                    <a href="{{ $courses->nextPageUrl() }}" class="biblio-btn-reset">Suivant →</a>
                @else
                    <span class="biblio-btn-reset" style="opacity: 0.4; pointer-events: none;">Suivant →</span>
                @endif
            </div>
        </div>
    @endif

{{-- ========================================================= --}}
{{-- CONTENU ONGLET 2 : EXAMENS NATIONAUX & ANNALES            --}}
{{-- ========================================================= --}}
@else
    @if(!$exams || $exams->isEmpty())
        <div style="background: #FFFFFF; border: 1.5px dashed #CBD5E1; padding: 40px 20px; text-align: center; color: #64748B;">
            <h3 style="font-size: 16px; font-weight: 800; color: #0E1022; margin: 0 0 6px 0;">Aucune épreuve nationale trouvée</h3>
            <p style="font-size: 13px; margin: 0 0 14px 0;">Aucun sujet officiel ne correspond à ces critères de recherche.</p>
            <a href="{{ route('student.library', ['tab' => 'examens', 'all' => 1]) }}" class="biblio-btn-submit">
                Voir toutes les épreuves
            </a>
        </div>
    @else
        <div class="biblio-grid-container">
            @foreach($exams as $exam)
                @php
                    $iconSvg = $subjectIcons[$exam->subject_name] ?? $defaultIcon;
                    $short = $shortSubjects[$exam->subject_name] ?? $exam->subject_name;
                    $pdfUrl = route('student.examens.preview', $exam->file_id ?? '01a161a5-8b7b-7390-bd95-fce05b0de8f0');
                    $downloadUrl = !empty($exam->file_id) ? route('student.examens.download', $exam->file_id) : '#';
                    $levelMeta = $exam->level_code . ' · ' . (in_array(strtoupper($exam->serie ?? ''), ['BEPC', 'COLLEGE']) ? 'Collège' : 'Série ' . ($exam->serie ?? 'D')) . ' · Session ' . ($exam->year ?? 2024);
                @endphp
                <div class="biblio-flip-card" 
                     onclick="handleBiblioCardClick(this, '{{ $pdfUrl }}', '{{ addslashes($exam->title) }}', '{{ $exam->file_code }}', '{{ addslashes($exam->subject_name) }}', '{{ addslashes($levelMeta) }}', '{{ $downloadUrl }}', '{{ $exam->resource_id ?? '' }}', '{{ addslashes($exam->summary ?? '') }}', true, event)">
                    <div class="biblio-flip-inner">
                        {{-- FACE 1 (RECTO) : Épreuve nationale claire et contrastée --}}
                        <div class="biblio-flip-front">
                            <div>
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                                    <span style="font-size: 13.5px; font-weight: 850; color: #0E1022;">
                                        {{ $exam->subject_name }}
                                    </span>
                                    <div style="width: 28px; height: 28px; border: 1.5px solid #E2E8F0; background: #F8FAFC; color: #0E1022; display: flex; align-items: center; justify-content: center; border-radius: 7px; flex: none;">
                                        {!! $iconSvg !!}
                                    </div>
                                </div>

                                <div style="margin-bottom: 6px;">
                                    <span class="biblio-badge" style="font-family: monospace; font-size: 9.5px; margin-bottom: 3px; display: inline-block;">
                                        {{ $exam->file_code }}
                                    </span>
                                    <h3 style="font-size: 13px; font-weight: 800; color: #0E1022; margin: 0; line-height: 1.3; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                        {{ $exam->title }}
                                    </h3>
                                </div>
                            </div>

                            <div>
                                <div style="display: flex; gap: 5px; align-items: center; flex-wrap: wrap; margin-bottom: 8px;">
                                    <span class="biblio-badge">
                                        {{ $exam->level_code }} · {{ in_array(strtoupper($exam->serie ?? ''), ['BEPC', 'COLLEGE']) || in_array($exam->level_code, ['3E', '4E', '5E', '6E']) ? 'Collège' : 'Série ' . ($exam->serie ?? 'D') }}
                                    </span>
                                    <span class="biblio-badge" style="background: #FEF3C7; border-color: #FDE68A; color: #B45309;">
                                        Session {{ $exam->year ?? 2024 }}
                                    </span>
                                    @if($exam->is_solution_restricted)
                                        <span class="biblio-badge" style="background: #FFE4E6; border-color: #FECDD3; color: #E11D48;">Corrigé</span>
                                    @else
                                        <span class="biblio-badge" style="background: #EDE9FE; border-color: #DDD6FE; color: #6D28D9;">Sujet</span>
                                    @endif
                                </div>
                                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 10.5px; color: #3fa9f5; font-weight: 700;">
                                    <span>↺ Survoler pour détails</span>
                                    <span style="color: #059669;">Tuteur IA connecté</span>
                                </div>
                            </div>
                        </div>

                        {{-- FACE 2 (VERSO) : Contexte de l'épreuve + Action Immersion Sujet & Tuteur IA --}}
                        <div class="biblio-flip-back">
                            <div>
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                    <span style="font-size: 10px; font-weight: 800; background: rgba(63, 169, 245, 0.22); color: #3fa9f5; padding: 2px 7px; border-radius: 5px;">
                                        Épreuve MEPST
                                    </span>
                                    <span style="font-size: 10.5px; color: #D4F25C; font-weight: 800;">
                                        Session {{ $exam->year ?? 2024 }}
                                    </span>
                                </div>
                                <div style="font-size: 11.5px; color: #E2E8F0; line-height: 1.45; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; margin-bottom: 8px;">
                                    {{ !empty($exam->summary) ? $exam->summary : "Épreuve nationale officielle avec consignes ministérielles et barème indicatif." }}
                                </div>
                            </div>

                            <div style="display: flex; gap: 6px; align-items: center; width: 100%;">
                                <button type="button" 
                                        onclick="event.stopPropagation(); openLibraryImmersion('{{ $pdfUrl }}', '{{ addslashes($exam->title) }}', '{{ $exam->file_code }}', '{{ addslashes($exam->subject_name) }}', '{{ addslashes($levelMeta) }}', '{{ $downloadUrl }}', '{{ $exam->resource_id ?? '' }}', '{{ addslashes($exam->summary ?? '') }}', true);" 
                                        style="flex: 1; display: inline-flex; align-items: center; justify-content: center; gap: 6px; background: #D4F25C; color: #0E1022; font-size: 12px; font-weight: 850; padding: 8px 12px; border-radius: 8px; border: none; cursor: pointer; transition: transform 0.15s ease;">
                                    <span>Consulter & Tuteur IA</span>
                                    <span style="font-size: 13px;">→</span>
                                </button>
                                @if(!empty($exam->file_id))
                                    <a href="{{ $downloadUrl }}" onclick="event.stopPropagation();" class="biblio-btn-reset" title="Télécharger le sujet PDF" style="padding: 7px 10px; background: rgba(255,255,255,0.15); border-color: rgba(255,255,255,0.25); color: #fff;">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination Examens --}}
        <div style="display: flex; justify-content: center; margin-bottom: 24px;">
            <div style="display: flex; gap: 6px; align-items: center;">
                @if($exams->onFirstPage())
                    <span class="biblio-btn-reset" style="opacity: 0.4; pointer-events: none;">← Précédent</span>
                @else
                    <a href="{{ $exams->previousPageUrl() }}" class="biblio-btn-reset">← Précédent</a>
                @endif

                <span style="font-size: 12px; font-weight: 700; color: #64748B; padding: 0 8px;">
                    Page {{ $exams->currentPage() }} / {{ $exams->lastPage() }}
                </span>

                @if($exams->hasMorePages())
                    <a href="{{ $exams->nextPageUrl() }}" class="biblio-btn-reset">Suivant →</a>
                @else
                    <span class="biblio-btn-reset" style="opacity: 0.4; pointer-events: none;">Suivant →</span>
                @endif
            </div>
        </div>
    @endif
@endif

{{-- ========================================================= --}}
{{-- LECTEUR D'IMMERSION GRAND ÉCRAN AVEC TUTEUR IA À GAUCHE   --}}
{{-- ========================================================= --}}
<div id="libraryImmersionModal" class="theme-day" style="display: none; position: fixed; inset: 0; z-index: 99999; flex-direction: column; width: 100vw; height: 100vh;">
    {{-- En-tête de la barre d'immersion (Zero-Displacement garanti avec flex-wrap: nowrap et flex-shrink: 0) --}}
    <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 18px; background: var(--immersion-top); border-bottom: 1.5px solid var(--immersion-border); flex-wrap: nowrap; gap: 12px; flex-shrink: 0; width: 100%; box-sizing: border-box;">
        <div style="display: flex; align-items: center; gap: 12px; min-width: 0; flex: 1; overflow: hidden;">
            <button type="button" onclick="closeLibraryImmersion()" class="immersion-close-btn" title="Fermer l'immersion (Raccourci: Échap)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
                <span>Fermer (Échap)</span>
            </button>

            <div style="width: 1px; height: 22px; background: var(--immersion-divider); flex-shrink: 0;"></div>

            {{-- Titre et Métadonnées avec troncature sécurisée pour ne jamais bousculer les boutons d'action --}}
            <div style="display: flex; align-items: center; gap: 8px; min-width: 0; flex: 1; overflow: hidden;">
                <span id="immersionDocSubject" class="biblio-badge" style="font-size: 11px; font-weight: 800; flex-shrink: 0;"></span>
                <span id="immersionDocTitle" style="font-size: 14px; font-weight: 850; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 45vw; color: var(--immersion-text);"></span>
                <span id="immersionDocMeta" style="font-size: 11.5px; opacity: 0.75; font-weight: 600; flex-shrink: 0; color: var(--immersion-text); overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"></span>
            </div>
        </div>

        <div style="display: flex; align-items: center; gap: 8px; flex-shrink: 0; margin-left: auto;">
            {{-- Bouton Toggle Tuteur IA (Afficher / Masquer la colonne droite) --}}
            <button type="button" id="btnToggleAiPane" onclick="toggleImmersionAiPane()" class="immersion-btn-action active-ai" title="Afficher ou masquer la zone Tuteur IA">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><path d="M12 2a8 8 0 0 0-8 8c0 3.37 2.1 6.25 5 7.42V19a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2v-1.58c2.9-1.17 5-4.05 5-7.42a8 8 0 0 0-8-8z"></path></svg>
                <span id="aiToggleText">Tuteur IA Actif</span>
            </button>

            {{-- Sélecteur Confort Visuel pour les yeux (Jour, Sépia doux, Nuit - ZÉRO EMOJI) --}}
            <div class="comfort-mode-wrap" title="Confort visuel pour lecture prolongée">
                <button type="button" onclick="setImmersionComfort('day')" class="comfort-btn active" id="comfortBtn_day">Jour</button>
                <button type="button" onclick="setImmersionComfort('sepia')" class="comfort-btn" id="comfortBtn_sepia">Sépia</button>
                <button type="button" onclick="setImmersionComfort('night')" class="comfort-btn" id="comfortBtn_night">Nuit</button>
            </div>

            {{-- Bouton Passer aux Exercices 3 Paliers (affiché pour les cours) --}}
            <a id="immersionPracticeBtn" href="{{ route('student.chat') }}" class="immersion-btn-action" style="display: none; background: #D4F25C; color: #0E1022; border-color: #D4F25C; font-weight: 850;" title="S'entraîner sur les 3 Paliers d'exercices de ce chapitre">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>
                <span>Exercices (3 Paliers)</span>
            </a>

            {{-- Téléchargement du PDF officiel --}}
            <a id="immersionDownloadBtn" href="#" class="immersion-btn-action" title="Télécharger le document PDF original">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                <span>Télécharger</span>
            </a>

            {{-- Plein Écran Plein Format --}}
            <button type="button" onclick="toggleImmersionFullscreen()" class="immersion-btn-action" title="Plein écran (F)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"></path></svg>
            </button>
        </div>
    </div>

    {{-- Corps de l'Immersion : Split-Screen avec Visualiseur Cours / Document à GAUCHE & Zone IA à DROITE --}}
    <div id="immersionSplitBody" style="flex: 1 1 0%; min-height: 0; width: 100%; height: 100%; max-height: 100%; overflow: hidden; display: grid; grid-template-columns: 1fr 390px; grid-template-rows: minmax(0, 1fr); background: var(--immersion-bg); transition: grid-template-columns 0.25s cubic-bezier(0.16, 1, 0.3, 1);">
        
        {{-- COLONNE GAUCHE : VISUALISEUR DE COURS MAGISTRAL OU D'ÉPREUVE PDF --}}
        <div id="immersionDocColumn" style="position: relative; height: 100%; max-height: 100%; min-height: 0; width: 100%; background: var(--immersion-bg); overflow: hidden; display: flex; flex-direction: column;">
            <div id="immersionCourseSheet" style="display: none; flex: 1 1 0%; min-height: 0; width: 100%; height: 100%; max-height: 100%; overflow-y: auto !important; padding: 28px 34px; box-sizing: border-box; background: var(--immersion-card); color: var(--immersion-text);"></div>
            <iframe id="immersionIframe" src="about:blank" style="width: 100%; height: 100%; border: none; display: block; flex: 1;"></iframe>
        </div>

        {{-- COLONNE DROITE : ZONE TUTEUR IA DÉDIÉ AU COURS OU À L'EXAMEN --}}
        <div id="immersionAiColumn" class="immersion-ai-pane">
            <div class="mk-split-resizer" title="Glisser vers la gauche ou la droite pour agrandir ou réduire l'espace du Tuteur IA (Double-clic pour alterner large / standard)">
                <div class="mk-split-resizer-grip"></div>
            </div>
            {{-- En-tête Tuteur IA --}}
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--immersion-border); padding-bottom: 8px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 28px; height: 28px; border-radius: 8px; background: #D4F25C; color: #0E1022; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 13px;">
                        IA
                    </div>
                    <div>
                        <b style="font-size: 13px; color: var(--immersion-text); display: block;">Tuteur IA MonKayier</b>
                        <div style="font-size: 11px; opacity: 0.7; color: var(--immersion-text);">Assistance & Méthode · MEPST</div>
                    </div>
                </div>
                <span class="ai-status-pill">● En ligne</span>
            </div>

            {{-- Flux de messages avec le tuteur IA --}}
            <div id="biblioChatFeed" class="immersion-chat-feed">
                {{-- Message d'accueil généré dynamiquement --}}
            </div>

            {{-- Indicateur de réflexion IA --}}
            <div id="biblioTypingIndicator" style="display: none; align-items: center; gap: 8px; font-size: 12px; color: #64748B; padding: 4px 8px;">
                <span style="display: inline-block; width: 6px; height: 6px; border-radius: 50%; background: #4F46E5; animation: timerPulse 0.8s infinite alternate;"></span>
                <span>Analyse pédagogique en cours…</span>
            </div>

            {{-- TIROIR 1 : SUGGESTIONS RAPIDES ADAPTÉES (Affichable / Masquable) --}}
            <div id="biblioSuggestionsDrawer" class="mk-suggestions-drawer">
                <div class="mk-drawer-header">
                    <span class="mk-drawer-title">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#4F46E5" stroke-width="2.5"><path d="M12 2a7 7 0 0 1 7 7c0 2.38-1.19 4.47-3 5.74V17a1 1 0 0 1-1 1H9a1 1 0 0 1-1-1v-2.26C6.19 13.47 5 11.38 5 9a7 7 0 0 1 7-7z"></path><line x1="9" y1="21" x2="15" y2="21"></line></svg>
                        Questions suggérées
                    </span>
                    <button type="button" class="mk-drawer-close-btn" onclick="window.mkToggleChatDrawer('suggestions', 'biblio', false)">
                        <span>Masquer</span>
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                </div>
                <div id="biblioChipsContainer" class="mk-suggestions-list">
                    {{-- Puces dynamiques --}}
                </div>
            </div>

            {{-- TIROIR 2 : CLAVIER SCIENTIFIQUE (Masqué par défaut) --}}
            <div id="biblioSciKbdDrawer" class="mk-scikbd-drawer is-collapsed">
                <div class="mk-drawer-header">
                    <div class="mk-scikbd-tabs">
                        <button type="button" class="mk-scikbd-tab is-active" onclick="window.mkFilterSciKbd(this, 'all', 'biblioSciKbdGrid')">Tout</button>
                        <button type="button" class="mk-scikbd-tab" onclick="window.mkFilterSciKbd(this, 'math', 'biblioSciKbdGrid')">Maths</button>
                        <button type="button" class="mk-scikbd-tab" onclick="window.mkFilterSciKbd(this, 'chem', 'biblioSciKbdGrid')">Chimie & Phys</button>
                    </div>
                    <button type="button" class="mk-drawer-close-btn" onclick="window.mkToggleChatDrawer('scikbd', 'biblio', false)">
                        <span>Masquer</span>
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <div id="biblioSciKbdGrid" class="mk-scikbd-grid">
                    <button type="button" class="mk-scikbd-key" data-cat="math" onclick="window.mkInsertSymbolToInput('biblioChatInput', '√(')">√x</button>
                    <button type="button" class="mk-scikbd-key" data-cat="math" onclick="window.mkInsertSymbolToInput('biblioChatInput', '²')">x²</button>
                    <button type="button" class="mk-scikbd-key" data-cat="math" onclick="window.mkInsertSymbolToInput('biblioChatInput', '^')">xⁿ</button>
                    <button type="button" class="mk-scikbd-key" data-cat="math" onclick="window.mkInsertSymbolToInput('biblioChatInput', 'lim(x→) ')">lim</button>
                    <button type="button" class="mk-scikbd-key" data-cat="math" onclick="window.mkInsertSymbolToInput('biblioChatInput', 'ln(')">ln</button>
                    <button type="button" class="mk-scikbd-key" data-cat="math" onclick="window.mkInsertSymbolToInput('biblioChatInput', 'e^')">eˣ</button>
                    <button type="button" class="mk-scikbd-key" data-cat="math" onclick="window.mkInsertSymbolToInput('biblioChatInput', '∫')">∫</button>
                    <button type="button" class="mk-scikbd-key" data-cat="math" onclick="window.mkInsertSymbolToInput('biblioChatInput', '∞')">∞</button>
                    <button type="button" class="mk-scikbd-key" data-cat="math" onclick="window.mkInsertSymbolToInput('biblioChatInput', 'π')">π</button>
                    <button type="button" class="mk-scikbd-key" data-cat="math" onclick="window.mkInsertSymbolToInput('biblioChatInput', 'Δ')">Δ</button>
                    <button type="button" class="mk-scikbd-key" data-cat="chem" onclick="window.mkInsertSymbolToInput('biblioChatInput', '[H₃O⁺]')">[H₃O⁺]</button>
                    <button type="button" class="mk-scikbd-key" data-cat="chem" onclick="window.mkInsertSymbolToInput('biblioChatInput', 'pH')">pH</button>
                    <button type="button" class="mk-scikbd-key" data-cat="chem" onclick="window.mkInsertSymbolToInput('biblioChatInput', '⇄')">⇄</button>
                    <button type="button" class="mk-scikbd-key" data-cat="chem" onclick="window.mkInsertSymbolToInput('biblioChatInput', '→')">→</button>
                </div>
            </div>

            {{-- BARRE D'OUTILS COMPACTE : AFFICHER / MASQUER SUGGESTIONS & CLAVIER SCIENTIFIQUE --}}
            <div class="mk-chat-tools-bar">
                <div class="mk-chat-tools-left">
                    <button type="button" id="biblioBtnToggleSug" class="mk-tool-toggle-btn is-active" onclick="window.mkToggleChatDrawer('suggestions', 'biblio')">
                        <svg class="mk-tool-icon" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M12 2a7 7 0 0 1 7 7c0 2.38-1.19 4.47-3 5.74V17a1 1 0 0 1-1 1H9a1 1 0 0 1-1-1v-2.26C6.19 13.47 5 11.38 5 9a7 7 0 0 1 7-7z"></path><line x1="9" y1="21" x2="15" y2="21"></line></svg>
                        <span class="mk-tool-state-text">Masquer idées</span>
                        <svg class="mk-tool-chevron" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <button type="button" id="biblioBtnToggleKbd" class="mk-tool-toggle-btn" onclick="window.mkToggleChatDrawer('scikbd', 'biblio')">
                        <span class="mk-tool-fx-badge">fx</span>
                        <span class="mk-tool-state-text">Clavier scientifique</span>
                        <svg class="mk-tool-chevron" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                </div>
            </div>

            {{-- Barre de saisie élève multi-lignes auto-extensible avec dictée vocale et bouton fx --}}
            <div class="immersion-chat-input-bar">
                <button type="button" id="biblioMicBtn" onclick="toggleBiblioSpeechDictation()" class="immersion-mic-btn" title="Dicter la question au micro">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path><path d="M19 10v2a7 7 0 0 1-14 0v-2"></path><line x1="12" y1="19" x2="12" y2="23"></line><line x1="8" y1="23" x2="16" y2="23"></line></svg>
                </button>
                <button type="button" id="biblioFxInlineBtn" onclick="window.mkToggleChatDrawer('scikbd', 'biblio')" class="immersion-fx-btn" title="Afficher / Masquer le clavier scientifique (fx)">
                    fx
                </button>
                <textarea id="biblioChatInput" rows="1" placeholder="Pose ta question ou dicte au micro…" 
                          onkeydown="if(event.key==='Enter' && !event.shiftKey){ event.preventDefault(); submitBiblioChatMessage(); }" class="immersion-input-field"></textarea>
                <button type="button" onclick="submitBiblioChatMessage()" class="immersion-send-btn">
                    Envoyer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentImmersionResourceId = null;
let currentImmersionIsExam = false;
let currentImmersionCourseContext = '';
let isBiblioAiPaneVisible = true;
let biblioSpeechRecognition = null;
let isBiblioVoiceRecording = false;

function renderBiblioKaTeX() {
    if (typeof renderMathInElement === 'function') {
        const sheet = document.getElementById('immersionCourseSheet');
        const feed = document.getElementById('biblioChatFeed');
        const opts = {
            delimiters: [
                {left: "$$", right: "$$", display: true},
                {left: "\\[", right: "\\]", display: true},
                {left: "$", right: "$", display: false},
                {left: "\\(", right: "\\)", display: false}
            ],
            throwOnError: false
        };
        if (sheet) renderMathInElement(sheet, opts);
        if (feed) renderMathInElement(feed, opts);
    }
}

function askBiblioTutorAboutTheorem(title) {
    const input = document.getElementById('biblioChatInput');
    if (!input) return;
    input.value = "Peux-tu m'expliquer le théorème « " + title + " » et me donner un exemple d'application au BAC ?";
    submitBiblioChatMessage();
}

function renderImmersionCourseHtml(data) {
    const sheet = document.getElementById('immersionCourseSheet');
    if (!sheet) return;
    const p = data.pedagogy || {};
    const esc = (s) => String(s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');

    let objHtml = '';
    if (p.objectives && p.objectives.length) {
        objHtml = `<div style="margin-bottom: 24px; background: var(--immersion-pane); border: 1.5px solid var(--immersion-border); border-left: 4.5px solid #0284C7; border-radius: 12px; padding: 16px 20px;">
            <div style="font-size: 12px; font-weight: 850; text-transform: uppercase; letter-spacing: 0.04em; color: #0284C7; margin-bottom: 10px;">Objectifs Pédagogiques & Exigences du BAC</div>
            <ul style="margin: 0; padding-left: 18px; display: flex; flex-direction: column; gap: 6px; font-size: 13.5px; line-height: 1.55; color: var(--immersion-text);">
                ${p.objectives.map(o => `<li>${o}</li>`).join('')}
            </ul>
        </div>`;
    }

    let defHtml = '';
    if (p.definitions && p.definitions.length) {
        defHtml = `<div style="margin-bottom: 26px;">
            <div style="font-size: 12px; font-weight: 850; text-transform: uppercase; letter-spacing: 0.04em; color: var(--immersion-text); margin-bottom: 12px;">1. Définitions Fondamentales du Cours</div>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                ${p.definitions.map(d => `<div style="background: var(--immersion-pane); border: 1.5px solid var(--immersion-border); border-radius: 12px; padding: 15px 18px;">
                    <div style="font-size: 14px; font-weight: 850; color: #0E1022; margin-bottom: 6px;"><span style="display:inline-block; background:#E0F2FE; color:#0369A1; font-size:11px; padding:2px 8px; border-radius:5px; margin-right:6px;">DÉFINITION</span>${d.term || ''}</div>
                    <div style="font-size: 13.5px; line-height: 1.65; color: var(--immersion-text);">${d.definition || d.text || ''}</div>
                </div>`).join('')}
            </div>
        </div>`;
    }

    let thmHtml = '';
    const thms = (p.theorems && p.theorems.length) ? p.theorems : (p.rules || []);
    if (thms.length) {
        thmHtml = `<div style="margin-bottom: 26px;">
            <div style="font-size: 12px; font-weight: 850; text-transform: uppercase; letter-spacing: 0.04em; color: var(--immersion-text); margin-bottom: 12px;">2. Théorèmes, Propriétés & Formules Officiels</div>
            <div style="display: flex; flex-direction: column; gap: 14px;">
                ${thms.map(t => `<div style="background: var(--immersion-pane); border: 1.5px solid #BAE6FD; border-left: 5px solid #0284C7; border-radius: 12px; padding: 16px 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; gap: 10px; margin-bottom: 8px; flex-wrap: wrap;">
                        <span style="font-size: 13px; font-weight: 850; color: #0284C7; text-transform: uppercase;">${t.title || 'Théorème'}</span>
                        <button type="button" onclick="askBiblioTutorAboutTheorem('${esc(t.title || 'Théorème').replace(/'/g, "\\'")}')" style="background: rgba(2,132,199,0.08); color: #0284C7; border: 1px solid rgba(2,132,199,0.25); border-radius: 6px; padding: 4px 10px; font-size: 11px; font-weight: 800; cursor: pointer;">Demander une explication au Tuteur IA →</button>
                    </div>
                    <div style="background: var(--immersion-card); border: 1px solid var(--immersion-border); border-radius: 8px; padding: 12px 16px; margin-bottom: 8px; font-size: 14px; font-weight: 700; color: var(--immersion-text); overflow-x: auto;">$$${t.latex || t.formula || ''}$$</div>
                    ${t.conditions ? `<div style="font-size: 12.5px; line-height: 1.55; color: var(--immersion-text); margin-bottom: 4px;"><b>Conditions d'application :</b> ${t.conditions}</div>` : ''}
                    <div style="font-size: 13px; line-height: 1.6; color: var(--immersion-subtext);">${t.comment || t.explanation || t.desc || ''}</div>
                </div>`).join('')}
            </div>
        </div>`;
    }

    let exHtml = '';
    if (p.demonstration_example) {
        const ex = p.demonstration_example;
        exHtml = `<div style="margin-bottom: 26px;">
            <div style="font-size: 12px; font-weight: 850; text-transform: uppercase; letter-spacing: 0.04em; color: var(--immersion-text); margin-bottom: 12px;">3. Exemple de Cours Résolu Pas-à-Pas (Modèle de Rédaction BAC)</div>
            <div style="background: var(--immersion-pane); border: 1.5px solid var(--immersion-border); border-radius: 12px; padding: 18px 22px;">
                <div style="background: var(--immersion-card); border-left: 3.5px solid #0E1022; padding: 10px 14px; border-radius: 0 8px 8px 0; font-size: 13.5px; line-height: 1.6; color: var(--immersion-text); margin-bottom: 14px;"><b>Énoncé :</b> ${ex.statement || ex.problem || ''}</div>
                <div style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 12px;">
                    ${(ex.steps || []).map((st, idx) => `<div style="display: flex; gap: 10px; align-items: flex-start; font-size: 13.5px; line-height: 1.6; color: var(--immersion-text);">
                        <span style="background: #0E1022; color: #D4F25C; font-size: 11px; font-weight: 850; width: 22px; height: 22px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px;">${idx + 1}</span>
                        <div>${st}</div>
                    </div>`).join('')}
                </div>
                ${ex.conclusion ? `<div style="background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.25); border-radius: 8px; padding: 10px 14px; font-size: 12.5px; font-weight: 700; color: #065F46;">Conclusion : ${ex.conclusion}</div>` : ''}
            </div>
        </div>`;
    }

    let trapHtml = '';
    if (p.trap || p.redaction_tip) {
        trapHtml = `<div style="margin-bottom: 26px; display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 12px;">
            ${p.trap ? `<div style="background: #FFFBEB; border: 1.5px solid #FDE68A; border-radius: 12px; padding: 14px 18px;">
                <div style="font-size: 11.5px; font-weight: 850; text-transform: uppercase; color: #92400E; margin-bottom: 6px;">Piège Classique au BAC</div>
                <div style="font-size: 12.5px; line-height: 1.55; color: #78350F;">${p.trap}</div>
            </div>` : ''}
            ${p.redaction_tip ? `<div style="background: #F0FDF4; border: 1.5px solid #BBF7D0; border-radius: 12px; padding: 14px 18px;">
                <div style="font-size: 11.5px; font-weight: 850; text-transform: uppercase; color: #166534; margin-bottom: 6px;">Conseil des Jurys MEPST</div>
                <div style="font-size: 12.5px; line-height: 1.55; color: #14532D;">${p.redaction_tip}</div>
            </div>` : ''}
        </div>`;
    }

    sheet.innerHTML = `
        <div style="margin-bottom: 22px; padding-bottom: 16px; border-bottom: 1.5px solid var(--immersion-border);">
            <div style="display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 10px; align-items: center;">
                <span style="font-size: 11px; font-weight: 850; background: #0E1022; color: #D4F25C; padding: 3px 10px; border-radius: 6px; text-transform: uppercase;">${p.code || 'COURS OFFICIEL'}</span>
                <span style="font-size: 11px; font-weight: 800; background: var(--immersion-pane); color: var(--immersion-text); border: 1px solid var(--immersion-border); padding: 3px 10px; border-radius: 6px;">${p.coeff_badge || 'Programme National Lycée'}</span>
                <span style="font-size: 11.5px; font-weight: 700; color: var(--immersion-subtext);">${p.duration || '40 min'} · ${p.ref || 'MEPST Togo'}</span>
            </div>
            <h1 style="font-size: 24px; font-weight: 900; margin: 0 0 8px 0; color: var(--immersion-text); font-family: 'Sora', sans-serif;">${p.title || data.title || 'Cours officiel'}</h1>
            <p style="margin: 0; font-size: 13.5px; line-height: 1.6; color: var(--immersion-subtext);">${data.summary || 'Leçon magistrale conforme aux exigences pédagogiques de l\'examen national du BAC.'}</p>
        </div>
        ${objHtml}
        ${defHtml}
        ${thmHtml}
        ${exHtml}
        ${trapHtml}
        <div style="margin-top: 28px; padding: 20px; background: #0E1022; border-radius: 14px; display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap;">
            <div>
                <div style="font-size: 15px; font-weight: 900; color: #FFFFFF; font-family: 'Sora', sans-serif; margin-bottom: 4px;">Prêt à valider tes connaissances sur ce cours ?</div>
                <div style="font-size: 12px; color: rgba(255,255,255,0.75);">Passe aux 3 Paliers gradués : Quiz Flash (60s), Application Méthode et Synthèse BAC.</div>
            </div>
            <a href="${data.chat_url || '/student/chat'}" style="background: #D4F25C; color: #0E1022; text-decoration: none; border-radius: 8px; padding: 11px 20px; font-size: 12.5px; font-weight: 850;">Passer aux Exercices (3 Paliers) →</a>
        </div>
    `;

    setTimeout(renderBiblioKaTeX, 60);
}

// 1. Gestion du confort visuel (Jour, Sépia doux anti-fatigue, Nuit)
function setImmersionComfort(theme) {
    const modal = document.getElementById('libraryImmersionModal');
    if (!modal) return;
    modal.classList.remove('theme-day', 'theme-sepia', 'theme-night');
    modal.classList.add('theme-' + theme);
    document.querySelectorAll('.comfort-btn').forEach(btn => btn.classList.remove('active'));
    const activeBtn = document.getElementById('comfortBtn_' + theme);
    if (activeBtn) activeBtn.classList.add('active');
    try { localStorage.setItem('mk_biblio_comfort_theme', theme); } catch(e){}
}

// 2. Afficher ou masquer la colonne droite du tuteur IA
function toggleImmersionAiPane() {
    isBiblioAiPaneVisible = !isBiblioAiPaneVisible;
    const splitBody = document.getElementById('immersionSplitBody');
    const aiCol = document.getElementById('immersionAiColumn');
    const toggleBtn = document.getElementById('btnToggleAiPane');
    const toggleText = document.getElementById('aiToggleText');

    if (isBiblioAiPaneVisible) {
        const w = window.mkGetSavedAiWidth ? window.mkGetSavedAiWidth(390) : 390;
        splitBody.style.gridTemplateColumns = `1fr ${w}px`;
        aiCol.style.display = 'flex';
        toggleBtn.classList.add('active-ai');
        toggleText.textContent = 'Tuteur IA Actif';
    } else {
        splitBody.style.gridTemplateColumns = '1fr 0';
        aiCol.style.display = 'none';
        toggleBtn.classList.remove('active-ai');
        toggleText.textContent = 'Afficher Tuteur IA';
    }
}

// 3. Ouverture immersive du Cours Magistral ou de l'Épreuve Officielle (Gauche : Cours/PDF, Droite : Tuteur IA)
function openLibraryImmersion(url, title, code, subject, meta, downloadUrl, resourceId, summary, isExam) {
    const modal = document.getElementById('libraryImmersionModal');
    const iframe = document.getElementById('immersionIframe');
    const courseSheet = document.getElementById('immersionCourseSheet');
    const practiceBtn = document.getElementById('immersionPracticeBtn');
    const downloadBtn = document.getElementById('immersionDownloadBtn');

    currentImmersionResourceId = resourceId;
    currentImmersionIsExam = !!isExam;
    currentImmersionCourseContext = (title || '') + ' (' + (subject || '') + ') : ' + (summary || '');

    const titleEl = document.getElementById('immersionDocTitle');
    if (titleEl) {
        titleEl.textContent = title || 'Document';
        titleEl.title = title || '';
    }
    const docCode = document.getElementById('immersionDocCode');
    if (docCode) docCode.textContent = code || 'MEPST';
    
    const subjEl = document.getElementById('immersionDocSubject');
    if (subjEl) subjEl.textContent = subject || '';

    const metaEl = document.getElementById('immersionDocMeta');
    if (metaEl) metaEl.textContent = meta ? '· ' + meta : '';

    // Rétablir thème confort mémorisé
    const savedTheme = localStorage.getItem('mk_biblio_comfort_theme') || 'day';
    setImmersionComfort(savedTheme);

    // Initialiser les messages du tuteur IA
    initBiblioAiFeed(title, subject, isExam);

    if (currentImmersionIsExam) {
        if (courseSheet) courseSheet.style.display = 'none';
        if (practiceBtn) practiceBtn.style.display = 'none';
        if (downloadBtn) {
            downloadBtn.style.display = 'inline-flex';
            downloadBtn.href = downloadUrl || '#';
        }
        if (iframe) {
            iframe.style.display = 'block';
            iframe.src = url + '#toolbar=1&navpanes=0&scrollbar=1';
        }
    } else {
        if (iframe) {
            iframe.src = 'about:blank';
            iframe.style.display = 'none';
        }
        if (downloadBtn) {
            downloadBtn.style.display = (downloadUrl && downloadUrl !== '#') ? 'inline-flex' : 'none';
            downloadBtn.href = downloadUrl || '#';
        }
        if (practiceBtn) {
            practiceBtn.style.display = 'inline-flex';
            practiceBtn.href = '/student/chat?course=' + encodeURIComponent(resourceId);
        }
        if (courseSheet) {
            courseSheet.style.display = 'block';
            courseSheet.innerHTML = '<div style="padding:40px;text-align:center;color:var(--immersion-subtext);font-weight:700;">Chargement du cours magistral officiel...</div>';
            fetch('/student/courses/' + encodeURIComponent(resourceId) + '/sheet')
                .then(r => r.json())
                .then(data => {
                    if (data && data.success) {
                        currentImmersionCourseContext = data.context_text || currentImmersionCourseContext;
                        if (practiceBtn && data.chat_url) practiceBtn.href = data.chat_url;
                        renderImmersionCourseHtml(data);
                        if (data.suggestions && data.suggestions.length) {
                            const chipsBox = document.getElementById('biblioChipsContainer');
                            if (chipsBox) {
                                chipsBox.innerHTML = '';
                                data.suggestions.forEach(s => {
                                    const chip = document.createElement('button');
                                    chip.type = 'button';
                                    chip.className = 'biblio-chip-btn';
                                    chip.innerText = s;
                                    chip.onclick = () => sendBiblioSuggestion(s);
                                    chipsBox.appendChild(chip);
                                });
                            }
                        }
                    }
                })
                .catch(() => {});
        }
    }

    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function initBiblioAiFeed(title, subject, isExam) {
    const feed = document.getElementById('biblioChatFeed');
    const chipsBox = document.getElementById('biblioChipsContainer');
    feed.innerHTML = '';

    const welcomeDiv = document.createElement('div');
    welcomeDiv.style.cssText = 'background: var(--immersion-card); border: 1.5px solid var(--immersion-card-border); padding: 12px 14px; border-radius: 4px 14px 14px 14px; line-height: 1.55; font-size: 13px; color: var(--immersion-text);';

    if (isExam) {
        welcomeDiv.innerHTML = `Bonjour Kodjo ! Je suis ton tuteur IA pour cette épreuve officielle : <b>« ${title} »</b> (${subject}). Tu peux me poser des questions sur la méthode de résolution, les pièges du barème ou me demander de t'aider sur un exercice précis.`;
    } else {
        welcomeDiv.innerHTML = `Bonjour Kodjo ! Je suis ton tuteur IA pour le cours : <b>« ${title} »</b> (${subject}). Tu peux me poser tes questions, me demander un résumé express, des formules clés ou une analogie concrète.`;
    }

    feed.appendChild(welcomeDiv);

    // Suggestions rapides selon le type de ressource
    chipsBox.innerHTML = '';
    const suggestions = isExam 
        ? ["Quels sont les pièges fréquents au BAC ?", "Méthode pour l'Exercice 1", "Barème et notation MEPST", "Conseils de rédaction"]
        : ["Explique-moi les concepts clés", "Donne-moi une analogie simple", "Fais-moi un quiz rapide sur ce cours", "Formules incontournables"];

    suggestions.forEach(sug => {
        const chip = window.mkCreateSuggestionPill
            ? window.mkCreateSuggestionPill(sug, () => sendBiblioSuggestion(sug))
            : null;
        if (chip) chipsBox.appendChild(chip);
    });
}

function closeLibraryImmersion() {
    const modal = document.getElementById('libraryImmersionModal');
    const iframe = document.getElementById('immersionIframe');
    iframe.src = 'about:blank';
    modal.style.display = 'none';
    document.body.style.overflow = '';
}

function toggleImmersionFullscreen() {
    const modal = document.getElementById('libraryImmersionModal');
    if (!document.fullscreenElement) {
        modal.requestFullscreen().catch(() => {});
    } else {
        document.exitFullscreen().catch(() => {});
    }
}

// 4. Interaction clic / tap sur cartes 3D
function handleBiblioCardClick(card, url, title, code, subject, meta, downloadUrl, resourceId, summary, isExam, event) {
    if (window.matchMedia('(hover: hover)').matches) {
        openLibraryImmersion(url, title, code, subject, meta, downloadUrl, resourceId, summary, isExam);
    } else {
        if (card.classList.contains('is-flipped')) {
            openLibraryImmersion(url, title, code, subject, meta, downloadUrl, resourceId, summary, isExam);
        } else {
            document.querySelectorAll('.biblio-flip-card.is-flipped').forEach(c => {
                if (c !== card) c.classList.remove('is-flipped');
            });
            card.classList.add('is-flipped');
        }
    }
}

// 5. Envoi message tuteur IA dans l'immersion
function sendBiblioSuggestion(text) {
    const input = document.getElementById('biblioChatInput');
    if (input) {
        input.value = text;
        submitBiblioChatMessage();
    }
}

function submitBiblioChatMessage() {
    const input = document.getElementById('biblioChatInput');
    const text = (input ? input.value : '').trim();
    if (!text) return;

    input.value = '';
    if (window.mkAutoResizeTextarea) window.mkAutoResizeTextarea(input);
    if (window.mkToggleChatDrawer) window.mkToggleChatDrawer('suggestions', 'biblio', false);
    const feed = document.getElementById('biblioChatFeed');
    const typing = document.getElementById('biblioTypingIndicator');

    // Message élève
    const userDiv = document.createElement('div');
    userDiv.style.cssText = 'align-self: flex-end; background: #0E1022; color: #FFFFFF; padding: 10px 14px; border-radius: 14px 14px 4px 14px; line-height: 1.45; font-size: 12.5px; max-width: 90%;';
    userDiv.textContent = text;
    feed.appendChild(userDiv);
    feed.scrollTop = feed.scrollHeight;

    if (typing) typing.style.display = 'flex';

    fetch('{{ route("student.chat.message") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            course_id: currentImmersionResourceId,
            message: text,
            active_tab: currentImmersionIsExam ? 'annale' : 'cours',
            context_text: currentImmersionCourseContext || ((document.getElementById('immersionDocTitle')?.textContent || '') + ' · ' + (document.getElementById('immersionDocSubject')?.textContent || ''))
        })
    })
    .then(res => res.json())
    .then(data => {
        if (typing) typing.style.display = 'none';

        const aiDiv = document.createElement('div');
        aiDiv.style.cssText = 'align-self: flex-start; display: flex; flex-direction: column; gap: 6px; max-width: 94%;';

        const resp = data.assistant_response || data;
        const sourceContent = resp.source_text || data.source_text || "Je t'écoute. Pose-moi toute question pour progresser sur ce document officiel.";
        const compText = resp.complementary_text || data.complementary_text || '';
        const formattedSource = window.mkFormatAiHtml ? window.mkFormatAiHtml(sourceContent) : sourceContent;
        const formattedComp = compText ? (window.mkFormatAiHtml ? window.mkFormatAiHtml(compText) : compText) : '';
        const rawSpeech = resp.spoken_text || (sourceContent + (compText ? '. ' + compText : ''));
        const analogy = formattedComp ? `<div style="background: rgba(254,243,199,0.5); border: 1px solid rgba(253,230,138,0.8); padding: 8px 10px; border-radius: 8px; font-size: 11.5px; color: #92400E; margin-top: 6px;"><b>Méthode & Barème :</b> ${formattedComp}</div>` : '';

        aiDiv.innerHTML = `
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <span class="tg" style="font-size:10px;background:#F1F5F9;color:#475569;">${resp.citation || 'Tuteur IA MonKayier'}</span>
                <button type="button" class="mk-tts-btn" title="Écouter avec la voix neurale humaine">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon><path d="M15.54 8.46a5 5 0 0 1 0 7.07"></path></svg>
                    <span>Écouter</span>
                </button>
            </div>
            <div style="background: var(--immersion-card); border: 1.5px solid var(--immersion-card-border); padding: 12px 14px; border-radius: 4px 14px 14px 14px; line-height: 1.55; font-size: 12.5px; color: var(--immersion-text);">
                ${formattedSource}
                ${analogy}
            </div>
        `;

        const ttsBtn = aiDiv.querySelector('.mk-tts-btn');
        if (ttsBtn) {
            ttsBtn.dataset.speech = rawSpeech;
            ttsBtn.onclick = function() {
                if (window.mkSpeakHuman) window.mkSpeakHuman(this.dataset.speech, this);
            };
        }

        feed.appendChild(aiDiv);
        setTimeout(renderBiblioKaTeX, 50);
        feed.scrollTop = feed.scrollHeight;

        if (window.mkSpeakHuman) {
            window.mkSpeakHuman(rawSpeech, ttsBtn);
        }

        const suggestions = resp.suggestions || data.suggestions || [];
        if (suggestions.length > 0) {
            const chipsBox = document.getElementById('biblioChipsContainer');
            if (chipsBox) {
                chipsBox.innerHTML = '';
                suggestions.forEach(s => {
                    const chip = window.mkCreateSuggestionPill
                        ? window.mkCreateSuggestionPill(s, () => sendBiblioSuggestion(s))
                        : null;
                    if (chip) chipsBox.appendChild(chip);
                });
            }
        }
    })
    .catch(() => {
        if (typing) typing.style.display = 'none';
    });
}

// 6. Dictée vocale pour l'IA
function toggleBiblioSpeechDictation() {
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    if (!SpeechRecognition) {
        alert("La reconnaissance vocale n'est pas supportée sur ce navigateur. Utilise Chrome ou Edge.");
        return;
    }

    const micBtn = document.getElementById('biblioMicBtn');
    const input = document.getElementById('biblioChatInput');

    if (isBiblioVoiceRecording && biblioSpeechRecognition) {
        biblioSpeechRecognition.stop();
        isBiblioVoiceRecording = false;
        if (micBtn) {
            micBtn.style.background = '';
            micBtn.style.color = '';
        }
        return;
    }

    biblioSpeechRecognition = new SpeechRecognition();
    biblioSpeechRecognition.lang = 'fr-FR';
    biblioSpeechRecognition.continuous = false;
    biblioSpeechRecognition.interimResults = false;

    biblioSpeechRecognition.onstart = function() {
        isBiblioVoiceRecording = true;
        if (micBtn) {
            micBtn.style.background = '#EF4444';
            micBtn.style.color = '#FFFFFF';
        }
    };

    biblioSpeechRecognition.onresult = function(event) {
        const transcript = event.results[0][0].transcript;
        if (input) {
            input.value = transcript;
            submitBiblioChatMessage();
        }
    };

    biblioSpeechRecognition.onerror = function() {
        isBiblioVoiceRecording = false;
        if (micBtn) {
            micBtn.style.background = '';
            micBtn.style.color = '';
        }
    };

    biblioSpeechRecognition.onend = function() {
        isBiblioVoiceRecording = false;
        if (micBtn) {
            micBtn.style.background = '';
            micBtn.style.color = '';
        }
    };

    biblioSpeechRecognition.start();
}

// 7. Raccourcis clavier (Échap pour fermer)
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('libraryImmersionModal');
        if (modal && modal.style.display === 'flex') {
            closeLibraryImmersion();
        }
    }
});

// 8. Auto-ouverture si un paramètre d'ouverture directe est présent
document.addEventListener('DOMContentLoaded', function() {
    @if(isset($openResource) && $openResource)
        openLibraryImmersion(
            '{{ route('student.examens.preview', $openResource->file_id ?? '01a161a5-8b7b-7390-bd95-fce05b0de8f0') }}',
            '{{ addslashes($openResource->title) }}',
            '{{ $openResource->file_code }}',
            '{{ addslashes($openResource->subject_name ?? '') }}',
            '{{ $openResource->level_name ?? '' }} · {{ in_array(strtoupper($openResource->serie ?? ''), ['COLLEGE', 'BEPC']) ? 'Collège' : 'Série '.($openResource->serie ?? 'D') }}',
            '{{ !empty($openResource->file_id) ? route('student.examens.download', $openResource->file_id) : '#' }}',
            '{{ $openResource->id }}',
            '{{ addslashes($openResource->summary ?? '') }}',
            {{ $openResource->resource_type === 'exam_paper' ? 'true' : 'false' }}
        );
    @endif
});
</script>

@endsection
