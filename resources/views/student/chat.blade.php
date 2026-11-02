@extends('layouts.student')

@section('title', 'Révision IA & Atelier d\'Examen — MonKayier')

@section('content')
<!-- Intégration KaTeX pour le rendu des formules mathématiques et scientifiques -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/katex.min.css">
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/katex.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/contrib/auto-render.min.js"></script>
<style>

/* ==========================================================================
   CLAVIER VIRTUEL SCIENTIFIQUE (MATHS & PCT) - DESIGN SYSTEM MONKAYIER
   ========================================================================== */
.sc-kbd-toolbar {
    background: #F8FAFC;
    border: 1.5px solid #CBD5E1;
    border-radius: 9px;
    padding: 7px 10px;
    margin-bottom: 8px;
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.sc-kbd-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #E2E8F0;
    padding-bottom: 5px;
}
.sc-kbd-tabs {
    display: flex;
    gap: 6px;
}
.sc-kbd-tab {
    background: transparent;
    border: none;
    font-size: 11px;
    font-weight: 800;
    color: #64748B;
    cursor: pointer;
    padding: 3px 8px;
    border-radius: 5px;
    transition: all 0.15s ease;
}
.sc-kbd-tab.active {
    background: #0E1022;
    color: #D4F25C;
}
.sc-kbd-hint {
    font-size: 10px;
    font-weight: 700;
    color: #94A3B8;
}
.sc-kbd-keys {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
}
.sc-key {
    background: #FFFFFF;
    border: 1px solid #CBD5E1;
    border-radius: 5px;
    padding: 3px 8px;
    font-size: 12px;
    font-weight: 800;
    color: #0E1022;
    cursor: pointer;
    user-select: none;
    box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    transition: all 0.1s ease;
}
.sc-key:hover {
    background: #F1F5F9;
    border-color: #94A3B8;
}
.sc-key:active {
    background: #E2E8F0;
    transform: translateY(1px);
}
.sc-chat-quick-pills {
    display: flex;
    flex-wrap: nowrap;
    overflow-x: auto;
    gap: 4px;
    padding: 4px 6px;
    background: #F8FAFC;
    border-top: 1px solid var(--immersion-border);
    scrollbar-width: none;
}
.sc-chat-quick-pills::-webkit-scrollbar {
    display: none;
}
.sc-chat-pill {
    background: #FFFFFF;
    border: 1px solid #CBD5E1;
    border-radius: 4px;
    padding: 2px 6px;
    font-size: 11px;
    font-weight: 800;
    color: #0E1022;
    cursor: pointer;
    white-space: nowrap;
    flex-shrink: 0;
}
.sc-chat-pill:hover {
    background: #0E1022;
    color: #D4F25C;
}
/* ==========================================================================
   DESIGN SYSTEM RÉVISION IA & ATELIER D'EXAMEN (MONKAYIER PREMIUM)
   ========================================================================== */

/* 1. Stepper & Piste de Navigation Segmentée */
.rev-top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 12px;
}
.rev-nav-track {
    display: inline-flex;
    align-items: center;
    background: #FFFFFF;
    border: 1.5px solid #E2E8F0;
    border-radius: 999px;
    padding: 3px 6px;
    gap: 4px;
    box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
}
.rev-nav-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
    color: #64748B;
    background: transparent;
    border: none;
    cursor: pointer;
    transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
    white-space: nowrap;
    text-decoration: none;
}
.rev-nav-btn:hover {
    background: #F8FAFC;
    color: #0E1022;
}
.rev-nav-btn.active {
    background: #0E1022;
    color: #FFFFFF;
    box-shadow: 0 3px 10px rgba(14, 16, 34, 0.16);
}
.rev-nav-btn.completed {
    background: #F1F5F9;
    color: #059669;
}
.rev-nav-num {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 10.5px;
    font-weight: 800;
    background: rgba(100, 116, 139, 0.15);
    color: inherit;
}
.rev-nav-btn.active .rev-nav-num {
    background: #D4F25C;
    color: #0E1022;
}
.rev-nav-btn.completed .rev-nav-num {
    background: #D1FAE5;
    color: #059669;
}
.rev-nav-divider {
    color: #CBD5E1;
    font-size: 12px;
    font-weight: 700;
    padding: 0 2px;
}

/* Boutons d'actions d'en-tête */
.bt-action-vocal {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    padding: 7px 15px;
    text-decoration: none;
    font-weight: 700;
    border-radius: 999px;
    background: #0E1022;
    color: #FFFFFF;
    box-shadow: 0 3px 10px rgba(14, 16, 34, 0.15);
    transition: transform 0.18s ease, box-shadow 0.18s ease;
}
.bt-action-vocal:hover {
    transform: translateY(-1px);
    box-shadow: 0 5px 14px rgba(14, 16, 34, 0.22);
    color: #FFFFFF;
}
.bt-action-bank {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    padding: 7px 15px;
    text-decoration: none;
    font-weight: 700;
    border-radius: 999px;
    background: #D4F25C;
    color: #0E1022;
    box-shadow: 0 3px 10px rgba(212, 242, 92, 0.3);
    transition: transform 0.18s ease, box-shadow 0.18s ease;
}
.bt-action-bank:hover {
    transform: translateY(-1px);
    box-shadow: 0 5px 14px rgba(212, 242, 92, 0.4);
    color: #0E1022;
}

/* 2. Cartes Matières (Étape 1) - Flip Card 3D Recto / Verso Interactive */
.subject-grid-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(235px, 1fr));
    gap: 12px;
}

.subject-flip-card {
    background-color: transparent;
    perspective: 1000px;
    height: 195px;
    cursor: pointer;
    user-select: none;
    position: relative;
    border-radius: 14px;
}

.subject-flip-inner {
    position: relative;
    width: 100%;
    height: 100%;
    transition: transform 0.5s cubic-bezier(0.2, 0.85, 0.32, 1.15);
    transform-style: preserve-3d;
    border-radius: 14px;
}

/* Effet retourné au survol ou par interaction */
.subject-flip-card:hover .subject-flip-inner,
.subject-flip-card.is-flipped .subject-flip-inner {
    transform: rotateY(180deg);
}

.subject-flip-front, 
.subject-flip-back {
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

/* Face Avant : Recto blanc, informations principales & maîtrise */
.subject-flip-front {
    background: #FFFFFF;
    border: 1.5px solid #E2E8F0;
    box-shadow: 0 1px 4px rgba(15, 23, 42, 0.04);
    color: #0E1022;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}
.subject-flip-card:hover .subject-flip-front {
    border-color: #3fa9f5;
    box-shadow: 0 8px 20px -4px rgba(20, 50, 92, 0.12);
}

/* Face Arrière : Verso Bleu Signature, Impact BAC, Diagnostic IA & CTA */
.subject-flip-back {
    background: linear-gradient(145deg, #14325c 0%, #1e4a84 100%);
    border: 1.5px solid #14325c;
    color: #FFFFFF;
    transform: rotateY(180deg);
    box-shadow: 0 8px 24px -4px rgba(20, 50, 92, 0.28);
}

.subject-card-icon-wrap {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #F8FAFC;
    border: 1.5px solid #E2E8F0;
    flex: none;
}
.subject-card-icon-wrap svg {
    width: 18px;
    height: 18px;
}

/* 3. Hero Bannière Matière (Étape 2) - Bords arrondis harmonisés */
.subject-hero-card {
    border-radius: 14px;
    border: 1.5px solid #E2E8F0;
    background: #FFFFFF;
    padding: 12px 18px;
    margin-bottom: 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    box-shadow: 0 1px 4px rgba(15, 23, 42, 0.03);
    position: relative;
    overflow: hidden;
}
.subject-hero-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #F8FAFC;
    border: 1.5px solid #E2E8F0;
    flex: none;
}
.subject-hero-icon svg {
    width: 20px;
    height: 20px;
}
.btn-change-subject {
    font-size: 12px;
    font-weight: 700;
    padding: 7px 14px;
    border-radius: 8px;
    background: #FFFFFF;
    color: #334155;
    border: 1.5px solid #CBD5E1;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.18s ease;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
}
.btn-change-subject:hover {
    background: #0E1022;
    color: #FFFFFF;
    border-color: #0E1022;
}

/* 4. Barre de filtres et recherche */
.filter-pill {
    padding: 6px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
    border: 1.5px solid #E2E8F0;
    background: #FFFFFF;
    color: #64748B;
    cursor: pointer;
    transition: all 0.18s ease;
    white-space: nowrap;
}
.filter-pill.active {
    background: #0E1022;
    color: #FFFFFF;
    border-color: #0E1022;
}

/* 5. Grille de Cartes Chapitres en BOX 3D avec retournement dynamique */
.subject-chapters-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(185px, 1fr));
    gap: 14px;
    width: 100%;
}
@media (max-width: 640px) {
    .subject-chapters-container {
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }
}

.chapter-flip-card {
    background-color: transparent;
    perspective: 1000px;
    height: 175px;
    cursor: pointer;
    user-select: none;
    position: relative;
    border-radius: 14px;
}

.chapter-flip-inner {
    position: relative;
    width: 100%;
    height: 100%;
    text-align: center;
    transition: transform 0.5s cubic-bezier(0.2, 0.85, 0.32, 1.15);
    transform-style: preserve-3d;
    border-radius: 14px;
}

/* Effet retourné au survol ou par interaction */
.chapter-flip-card:hover .chapter-flip-inner,
.chapter-flip-card.is-flipped .chapter-flip-inner {
    transform: rotateY(180deg);
}

.chapter-flip-front, 
.chapter-flip-back {
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
    padding: 14px 14px;
}

/* Face 1 (Avant) : BOX blanche épurée avec grand numéro */
.chapter-flip-front {
    background: #FFFFFF;
    border: 1.5px solid #E2E8F0;
    box-shadow: 0 1px 4px rgba(15, 23, 42, 0.04);
    color: #0E1022;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}
.chapter-flip-card:hover .chapter-flip-front {
    border-color: #3fa9f5;
    box-shadow: 0 6px 16px -4px rgba(20, 50, 92, 0.12);
}

/* Face 2 (Arrière) : Révélation du Titre du chapitre & Action Réviser */
.chapter-flip-back {
    background: linear-gradient(145deg, #14325c 0%, #1e4a84 100%);
    border: 1.5px solid #14325c;
    color: #FFFFFF;
    transform: rotateY(180deg);
    box-shadow: 0 8px 20px -4px rgba(20, 50, 92, 0.25);
}

/* 6. Mode Atelier & Paliers Gradués (Étape 3) */
.workshop-nav-btn {
    padding: 6px 14px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
    border: 1.5px solid transparent;
    background: transparent;
    color: #475569;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s ease;
    white-space: nowrap;
}
.workshop-nav-btn:hover {
    background: #FFFFFF;
    color: #0E1022;
}
.workshop-nav-btn.active {
    background: #0E1022;
    color: #FFFFFF;
    border-color: #0E1022;
    box-shadow: 0 2px 8px rgba(14, 16, 34, 0.16);
}
.workshop-tab-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    font-size: 12.5px;
    font-weight: 700;
    color: var(--immersion-text);
    background: transparent;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.15s ease;
    user-select: none;
}
.workshop-tab-btn:hover {
    background: rgba(14, 16, 34, 0.08);
}
.workshop-tab-btn.active {
    background: #0E1022;
    color: #D4F25C;
}
.course-theorem-card {
    background: var(--immersion-pane);
    border: 1.5px solid var(--immersion-border);
    border-radius: 12px;
    padding: 14px clamp(12px, 2vw, 20px);
    transition: transform 0.15s ease, border-color 0.15s ease;
    max-width: 100%;
    box-sizing: border-box;
    overflow: hidden;
}
.course-theorem-card:hover {
    border-color: #0284C7 !important;
}

.course-def-card {
    background: var(--immersion-pane);
    border: 1.5px solid var(--immersion-border);
    border-radius: 10px;
    padding: 12px clamp(12px, 2vw, 18px);
    max-width: 100%;
    box-sizing: border-box;
    overflow: hidden;
}

.course-formula-box {
    background: var(--immersion-card);
    border: 1px solid var(--immersion-border);
    border-radius: 8px;
    padding: 10px clamp(8px, 1.5vw, 16px);
    margin: 8px 0;
    max-width: 100%;
    box-sizing: border-box;
    overflow-x: auto;
    overflow-y: hidden;
    -webkit-overflow-scrolling: touch;
    text-align: center;
    position: relative;
}

/* Scrollbars ultra-fines pour les formules mathématiques qui dépassent */
.course-formula-box::-webkit-scrollbar,
.katex-display::-webkit-scrollbar {
    height: 4px !important;
}
.course-formula-box::-webkit-scrollbar-track,
.katex-display::-webkit-scrollbar-track {
    background: rgba(14, 16, 34, 0.04) !important;
    border-radius: 4px !important;
}
.course-formula-box::-webkit-scrollbar-thumb,
.katex-display::-webkit-scrollbar-thumb {
    background: rgba(14, 16, 34, 0.22) !important;
    border-radius: 4px !important;
}
.course-formula-box::-webkit-scrollbar-thumb:hover,
.katex-display::-webkit-scrollbar-thumb:hover {
    background: #0284C7 !important;
}

/* Règle globale KaTeX : affichage fluide sans débordement */
.katex-display {
    display: block !important;
    margin: 0.35em 0 !important;
    text-align: center !important;
    max-width: 100% !important;
    overflow-x: auto !important;
    overflow-y: hidden !important;
    -webkit-overflow-scrolling: touch !important;
    padding: 4px 2px !important;
    box-sizing: border-box !important;
    scrollbar-width: thin;
    scrollbar-color: rgba(14, 16, 34, 0.22) transparent;
}
.katex-display > .katex {
    display: inline-block !important;
    text-align: center !important;
    max-width: 100% !important;
}
.katex {
    font-size: clamp(0.86em, 1.5vw, 1.05em) !important;
    text-rendering: optimizeLegibility !important;
}

/* Sécurisation anti-débordement sur tout le contenu textuel et mathématique */
.course-theorem-card,
.course-def-card,
.mk-ai-formatted,
.chat-msg,
#workshopModeCours,
#workshopModeExercices {
    overflow-wrap: break-word;
    word-break: break-word;
}

.tier-tab {
    padding: 12px 18px;
    border-radius: 12px;
    font-size: 13.5px;
    font-weight: 700;
    background: #F8FAFC;
    color: #475569;
    border: 1.5px solid #E2E8F0;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.18s ease;
}
.tier-tab.active {
    background: #0E1022;
    color: #FFFFFF;
    border-color: #0E1022;
}

/* Stepper Séquentiel par Paliers (MonKayier) */
.mk-tier-stepper {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 22px;
    background: var(--immersion-pane);
    border: 1.5px solid var(--immersion-border);
    border-radius: 14px;
    padding: 8px 14px;
    overflow-x: auto;
}
.mk-tier-step {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 14px;
    border-radius: 10px;
    cursor: default;
    user-select: none;
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    flex: 1;
    min-width: 170px;
}
.mk-tier-step.unlocked {
    cursor: pointer;
}
.mk-tier-step.active {
    background: #0E1022;
    color: #FFFFFF;
    box-shadow: 0 4px 14px rgba(14, 16, 34, 0.15);
}
.mk-tier-step.locked {
    opacity: 0.5;
    background: transparent;
    cursor: not-allowed;
}
.mk-tier-step.completed {
    background: rgba(16, 185, 129, 0.08);
    border: 1px solid rgba(16, 185, 129, 0.28);
    cursor: pointer;
}
.mk-step-circle {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12.5px;
    font-weight: 900;
    flex: none;
    background: #E2E8F0;
    color: #475569;
    transition: all 0.2s ease;
}
.mk-tier-step.active .mk-step-circle {
    background: #D4F25C;
    color: #0E1022;
}
.mk-tier-step.completed .mk-step-circle {
    background: #10B981;
    color: #FFFFFF;
}
.mk-step-connector {
    width: 20px;
    height: 2px;
    background: #E2E8F0;
    flex: none;
    transition: background 0.2s ease;
}
.mk-step-connector.active {
    background: #10B981;
}

/* Branding Compte à Rebours (Timer MonKayier) */
.mk-branded-timer {
    display: flex;
    align-items: center;
    gap: 12px;
    background: #0E1022;
    border: 1.5px solid rgba(212, 242, 92, 0.4);
    box-shadow: 0 4px 16px rgba(14, 16, 34, 0.2);
    border-radius: 12px;
    padding: 8px 18px;
    color: #FFFFFF;
}
.mk-timer-dial {
    font-family: 'JetBrains Mono', ui-monospace, monospace;
    font-size: 20px;
    font-weight: 900;
    color: #D4F25C;
    letter-spacing: 0.04em;
    min-width: 44px;
    transition: color 0.25s ease;
}
.mk-timer-badge {
    font-size: 9.5px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #94A3B8;
}

/* Zone de Dépôt de Copie Manuscrite (Palier 3) */
.mk-copy-dropzone {
    border: 2px dashed #CBD5E1;
    border-radius: 14px;
    padding: 28px 20px;
    text-align: center;
    background: var(--immersion-card);
    cursor: pointer;
    transition: all 0.2s ease;
}
.mk-copy-dropzone:hover {
    border-color: #0E1022;
    background: rgba(14, 16, 34, 0.02);
}

.timer-pulse {
    animation: timerPulse 1s infinite alternate;
}
@keyframes timerPulse {
    0% { transform: scale(1); opacity: 0.9; }
    100% { transform: scale(1.05); opacity: 1; }
}
.lock-banner {
    background: linear-gradient(135deg, #FFFBEB 0%, #FEF3C7 100%);
    border: 1.5px dashed #F59E0B;
    border-radius: 14px;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    color: #92400E;
    font-size: 13.5px;
    font-weight: 600;
}
.solution-unlocked-box {
    background: #F0FDF4;
    border: 1.5px solid #86EFAC;
    border-radius: 14px;
    padding: 20px;
    color: #14532D;
    line-height: 1.65;
    font-size: 14px;
}
.mic-recording-pulse {
    animation: micPulse 1s infinite alternate;
    background: #EF4444 !important;
    color: #FFFFFF !important;
}
@keyframes micPulse {
    0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.6); }
    100% { box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
}

/* ========================================================= */
/* ATELIER & MODE FOCUS : BARRE D'IMMERSION & PLEIN ÉCRAN    */
/* ========================================================= */
.btn-focus-toggle {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 14px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 750;
    background: #0E1022;
    color: #FFFFFF;
    border: 1.5px solid #0E1022;
    cursor: pointer;
    transition: all 0.15s ease;
    box-shadow: none !important;
}
.btn-focus-toggle:hover {
    background: #1e4a84;
    border-color: #1e4a84;
    box-shadow: none !important;
}
.btn-focus-toggle.active-focus,
body.is-focus-mode .btn-focus-toggle {
    background: #3fa9f5;
    border-color: #3fa9f5;
    color: #FFFFFF;
    box-shadow: none !important;
}

/* Bouton d'Action Vert Citron Appelant : Exercices (Charte MonKayier - Sans Ombre de Forme) */
.btn-exercices-cta {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 7px 16px;
    border-radius: 8px;
    font-size: 12.5px;
    font-weight: 850;
    background: #D4F25C;
    color: #0E1022;
    border: 1.5px solid #D4F25C;
    cursor: pointer;
    box-shadow: none !important;
    transition: all 0.15s ease;
    white-space: nowrap;
}
.btn-exercices-cta:hover {
    background: #cbf046;
    border-color: #cbf046;
    box-shadow: none !important;
}
.btn-exercices-cta.mode-b-active {
    background: #0E1022;
    color: #FFFFFF;
    border-color: #0E1022;
    box-shadow: none !important;
}
.btn-exercices-cta.mode-b-active svg {
    stroke: #D4F25C;
}

/* Confort Visuel pour la Révision (Jour, Sépia, Nuit - Zéro Emoji) */
.comfort-mode-wrap {
    display: inline-flex;
    align-items: center;
    background: #F1F5F9;
    border: 1px solid #CBD5E1;
    border-radius: 8px;
    padding: 2px 3px;
    gap: 2px;
}
.comfort-btn {
    border: none;
    background: transparent;
    color: #64748B;
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
    font-weight: 850;
    box-shadow: none !important;
}

/* 2. Thèmes de Confort Visuel pour le lecteur immersif de Révision IA */
#stepView3.theme-day {
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
#stepView3.theme-sepia {
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
#stepView3.theme-night {
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

/* Boutons d'Action & En-tête Immersif */
.immersion-close-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: transparent;
    border: 1px solid var(--immersion-border);
    color: var(--immersion-text);
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.15s ease;
    white-space: nowrap;
    box-shadow: none !important;
}
.immersion-close-btn:hover {
    background: var(--immersion-card);
}
.immersion-btn-action {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--immersion-card);
    border: 1px solid var(--immersion-border);
    color: var(--immersion-text);
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.15s ease;
    text-decoration: none;
    white-space: nowrap;
    box-shadow: none !important;
}
.immersion-btn-action:hover {
    filter: brightness(0.96);
}
.immersion-btn-action.active-ai {
    background: #0E1022;
    color: #FFFFFF;
    border-color: #0E1022;
}
.immersion-btn-action.active-ai svg {
    stroke: #D4F25C;
}

/* Zone IA Latérale Droite (Immersion - Hauteur Fixe Viewport, Saisie Toujours Visible En Bas) */
.immersion-ai-pane {
    background: var(--immersion-pane);
    border-left: 1.5px solid var(--immersion-border);
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
.ai-status-pill {
    font-size: 10.5px;
    font-weight: 750;
    color: #059669;
    background: rgba(16, 185, 129, 0.12);
    padding: 2px 7px;
    border-radius: 999px;
    white-space: nowrap;
}
@media (max-width: 980px) {
    .workshop-split-grid {
        grid-template-columns: 1fr;
        height: auto;
    }
}

/* Mode B : Exercices par Paliers & Quiz (Défilement fluide de la souris) */
#workshopModeB {
    max-width: 960px;
    margin: 0 auto;
    width: 100%;
}
body.is-focus-mode #workshopModeB {
    display: block;
    flex: 1 1 0% !important;
    min-height: 0 !important;
    height: 100% !important;
    max-height: 100% !important;
    overflow-y: auto !important;
    overflow-x: hidden !important;
    -webkit-overflow-scrolling: touch;
    padding: 6px 12px 100px 4px !important;
    box-sizing: border-box !important;
}
body.is-focus-mode #workshopModeB::-webkit-scrollbar {
    width: 8px;
}
body.is-focus-mode #workshopModeB::-webkit-scrollbar-track {
    background: #F1F5F9;
    border-radius: 999px;
}
body.is-focus-mode #workshopModeB::-webkit-scrollbar-thumb {
    background: #CBD5E1;
    border-radius: 999px;
}
body.is-focus-mode #workshopModeB::-webkit-scrollbar-thumb:hover {
    background: #94A3B8;
}

/* Adaptation de l'Atelier en Mode Immersion Focus */
body.is-focus-mode #stepView3 {
    position: fixed !important;
    inset: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    max-height: 100vh !important;
    min-height: 0 !important;
    overflow: hidden !important;
}
body.is-focus-mode .atelier-top-controls {
    flex: none !important;
    margin-bottom: 6px !important;
}
body.is-focus-mode #workshopModeA {
    display: flex !important;
    flex-direction: column !important;
    flex: 1 !important;
    min-height: 0 !important;
    height: 100% !important;
    max-height: 100% !important;
    overflow: hidden !important;
}
body.is-focus-mode .workshop-split-grid {
    display: grid !important;
    grid-template-columns: 1fr 380px !important;
    gap: 12px !important;
    flex: 1 !important;
    min-height: 0 !important;
    height: 100% !important;
    max-height: 100% !important;
    overflow: hidden !important;
}
body.is-focus-mode .workshop-col-pdf,
body.is-focus-mode .workshop-col-chat {
    min-height: 0 !important;
    height: 100% !important;
    max-height: 100% !important;
    display: flex !important;
    flex-direction: column !important;
    overflow: hidden !important;
    box-sizing: border-box !important;
    padding: 10px 14px 10px 14px !important;
    gap: 6px !important;
}
body.is-focus-mode #officialCoursePdfFrame {
    min-height: 0 !important;
    height: 100% !important;
    flex: 1 1 0% !important;
}
body.is-focus-mode #chatFeed {
    flex: 1 1 0% !important;
    min-height: 60px !important;
    max-height: none !important;
    overflow-y: auto !important;
}
body.is-focus-mode #chipsContainer {
    flex: none !important;
    margin: 2px 0 !important;
}
body.is-focus-mode .chat-input-bar {
    flex: none !important;
    margin-top: auto !important;
    margin-bottom: 0 !important;
}

</style>

{{-- ========================================================= --}}
{{-- EN-TÊTE PRINCIPAL INTÉGRÉ (Masqué uniquement à l'Étape 3) --}}
{{-- ========================================================= --}}
<div id="revisionMainHeader" style="{{ $activeStep == 3 ? 'display:none;' : 'display:block;' }}; margin-bottom: 12px;">
    {{-- Rangée unique compacte : Titre à gauche, Stepper au milieu, Actions à droite --}}
    <div class="rev-top-bar" style="display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 0;">
        <div>
            <h1 style="font-size: 19px; font-weight: 850; margin: 0; color: #0E1022; letter-spacing: -0.02em; white-space: nowrap;">
                Révision IA & Atelier d'Examen
            </h1>
        </div>

        <div style="display: flex; justify-content: center; flex: 1; min-width: 280px;">
            <nav class="rev-nav-track" aria-label="Étapes de révision">
                <button type="button" class="rev-nav-btn {{ $activeStep == 1 ? 'active' : ($activeStep > 1 ? 'completed' : '') }}" id="stepTab1" onclick="goToStep(1)">
                    <span class="rev-nav-num">1</span>
                    <span>Choisir la matière</span>
                </button>

                <span class="rev-nav-divider">→</span>

                <button type="button" class="rev-nav-btn {{ $activeStep == 2 ? 'active' : ($activeStep > 2 ? 'completed' : '') }}" id="stepTab2" onclick="goToStep(2)">
                    <span class="rev-nav-num">2</span>
                    <span id="step2TitleLabel">{{ $activeSubject ? $activeSubject->short : 'Choisir le chapitre' }}</span>
                </button>

                <span class="rev-nav-divider">→</span>

                <button type="button" class="rev-nav-btn {{ $activeStep == 3 ? 'active' : '' }}" id="stepTab3" onclick="goToStep(3)">
                    <span class="rev-nav-num">3</span>
                    <span>Atelier & Défi IA</span>
                </button>
            </nav>
        </div>

        <div style="display: flex; gap: 8px; align-items: center; flex-shrink: 0;">
            <a href="{{ route('student.vocal') }}" class="bt-action-vocal">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#D4F25C" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path><path d="M19 10v2a7 7 0 0 1-14 0v-2"></path><line x1="12" y1="19" x2="12" y2="23"></line><line x1="8" y1="23" x2="16" y2="23"></line></svg>
                Assistant Vocal
            </a>
            <a href="{{ route('student.exercises') }}" class="bt-action-bank">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#0E1022" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3 8-8"></path><path d="M20 12v7a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h9"></path></svg>
                Banque d'Exercices
            </a>
        </div>
    </div>
</div>

{{-- ========================================================= --}}
{{-- ÉTAPE 1 : GRILLE DES MATIÈRES DE LA CLASSE (TERMINALE D)  --}}
{{-- ========================================================= --}}
<div id="stepView1" style="{{ $activeStep == 1 ? 'display:block;' : 'display:none;' }}">
    <div class="subject-grid-container">
        @foreach($subjectsWithCourses as $s)
        @php
            $coeffNum = (int) filter_var($s->coeff, FILTER_SANITIZE_NUMBER_INT) ?: 2;
        @endphp
        <div class="subject-flip-card" 
             onclick="handleSubjectCardClick(this, '{{ $s->id }}', '{{ addslashes($s->name) }}', '{{ addslashes($s->short) }}', '{{ addslashes($s->coeff) }}', {{ $s->courses_count }}, '{{ addslashes($s->icon) }}', '{{ $s->bg }}', '{{ $s->color }}', '{{ $s->pdf_file_id }}', event)">
            
            <div class="subject-flip-inner">
                {{-- FACE AVANT (RECTO) : INFORMATIONS MAJEURES & MAÎTRISE --}}
                <div class="subject-flip-front">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 10.5px; font-weight: 800; background: #F1F5F9; color: #475569; padding: 2px 7px; border-radius: 6px; border: 1px solid #E2E8F0;">
                            {{ $s->coeff }}
                        </span>
                        <div class="subject-card-icon-wrap" style="color: {{ $s->color }};">
                            {!! $s->icon !!}
                        </div>
                    </div>

                    <div style="margin: 4px 0;">
                        <h3 style="font-size: 15.5px; font-weight: 850; color: #14325c; margin: 0 0 3px 0; letter-spacing: -0.01em;">
                            {{ $s->name }}
                        </h3>
                        <div style="font-size: 11.5px; color: #64748B; font-weight: 500;">
                            {{ $s->courses_count }} {{ $s->courses_count > 1 ? 'chapitres & cours officiels' : 'chapitre officiel' }}
                        </div>
                    </div>

                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 11px; margin-bottom: 4px;">
                            <span style="color: #64748B; font-weight: 600;">Maîtrise estimée</span>
                            <b id="masteryText_{{ $s->id }}" style="color: {{ $s->color }}; font-weight: 850;">{{ $s->mastery_percent }}%</b>
                        </div>
                        <div style="height: 5px; background: #F1F5F9; border-radius: 999px; overflow: hidden; margin-bottom: 7px;">
                            <div id="masteryBar_{{ $s->id }}" style="height: 100%; width: {{ $s->mastery_percent }}%; background: {{ $s->color }}; border-radius: 999px; transition: width 0.7s cubic-bezier(0.16, 1, 0.3, 1);"></div>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; gap: 4px;">
                            <span style="font-size: 10px; font-weight: 700; color: #3fa9f5; display: inline-flex; align-items: center; gap: 4px;">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
                                <span>Bilan & priorités</span>
                            </span>
                            <span style="font-size: 10px; font-weight: 750; background: #F8FAFC; border: 1px solid #E2E8F0; padding: 1.5px 6px; border-radius: 6px; color: #475569;">
                                {{ $s->short }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- FACE ARRIÈRE (VERSO) : IMPACT BAC, DIAGNOSTIC IA & BOUTON D'ACCÈS --}}
                <div class="subject-flip-back">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 10.5px; font-weight: 800; background: rgba(63, 169, 245, 0.2); color: #3fa9f5; border: 1px solid rgba(63, 169, 245, 0.4); padding: 2px 7px; border-radius: 6px;">
                            {{ $s->short }}
                        </span>
                        <span style="font-size: 10.5px; font-weight: 800; color: #D4F25C; background: rgba(212, 242, 92, 0.15); border: 1px solid rgba(212, 242, 92, 0.35); padding: 2px 7px; border-radius: 6px;">
                            {{ $coeffNum * 20 }} pts au BAC
                        </span>
                    </div>

                    <div style="margin: auto 0; text-align: left;">
                        <div style="font-size: 10.5px; font-weight: 750; color: #D4F25C; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">
                            Diagnostic Officiel
                        </div>
                        <div style="font-size: 12px; font-weight: 700; color: #FFFFFF; line-height: 1.35; margin-bottom: 3px;">
                            @if($s->mastery_percent < 60)
                                <span style="color: #FED7AA;">Notions clés à renforcer en priorité</span>
                            @elseif($s->mastery_percent < 75)
                                <span style="color: #BAE6FD;">Bonne dynamique, consolide les acquis</span>
                            @else
                                <span style="color: #BBF7D0;">Maîtrise solide, vise la mention !</span>
                            @endif
                        </div>
                        <div style="font-size: 10.5px; color: #CBD5E1; line-height: 1.3;">
                            {{ $s->courses_count }} chapitres conformes aux épreuves d'État
                        </div>
                    </div>

                    <div>
                        <button type="button" 
                                onclick="event.stopPropagation(); selectSubject('{{ $s->id }}', '{{ addslashes($s->name) }}', '{{ addslashes($s->short) }}', '{{ addslashes($s->coeff) }}', {{ $s->courses_count }}, '{{ addslashes($s->icon) }}', '{{ $s->bg }}', '{{ $s->color }}', '{{ $s->pdf_file_id }}');" 
                                style="display: flex; align-items: center; justify-content: center; gap: 6px; width: 100%; padding: 7px 12px; border-radius: 8px; background: #D4F25C; color: #0E1022; font-size: 11.5px; font-weight: 850; border: none; cursor: pointer; box-sizing: border-box; transition: transform 0.15s ease;">
                            <span>Explorer les chapitres</span>
                            <span>→</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- ========================================================= --}}
{{-- ÉTAPE 2 : LISTE DES CHAPITRES DU COURS SÉLECTIONNÉ        --}}
{{-- ========================================================= --}}
<div id="stepView2" style="{{ $activeStep == 2 ? 'display:block;' : 'display:none;' }}">
    {{-- Bannière Discipline Hero Immersion Compacte & Neutre --}}
    <div id="subjectBannerHero" class="subject-hero-card" style="background: #FFFFFF; border: 1.5px solid #E2E8F0;">
        <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
            <div id="subjectBannerIcon" class="subject-hero-icon" style="background: #F8FAFC; border: 1.5px solid #E2E8F0; color: #0E1022;">
                {!! $activeSubject->icon ?? '' !!}
            </div>
            <div>
                <h2 id="subjectBannerTitle" style="font-size: 16px; font-weight: 850; color: #0E1022; margin: 0; letter-spacing: -0.01em;">
                    {{ $activeSubject->name ?? 'Mathématiques' }}
                </h2>
                <div id="subjectBannerDesc" style="font-size: 11.5px; color: #64748B; font-weight: 500; margin-top: 2px;">
                    Programme officiel Terminale D • <span id="subjectBannerCount" style="color: #0E1022; font-weight: 700;">{{ $activeSubject->courses_count ?? 0 }} chapitres disponibles</span>
                </div>
            </div>
        </div>

        <div>
            <button type="button" class="btn-change-subject" onclick="goToStep(1)">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
                Changer de matière
            </button>
        </div>
    </div>

    {{-- Barre de recherche et filtres rapides compacts --}}
    {{-- Barre de recherche et filtres rapides - Sans bordure ronde --}}
    <div style="display: flex; justify-content: space-between; align-items: center; gap: 10px; margin-bottom: 10px; flex-wrap: wrap;">
        <div style="position: relative; width: 100%; max-width: 400px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="2.3" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); pointer-events: none;"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            <input type="text" id="chapterSearchInput" placeholder="Rechercher un chapitre, une formule, un concept..." 
                   style="width: 100%; padding: 8px 14px 8px 38px; border-radius: 8px; border: 1.5px solid #E2E8F0; font-size: 12.5px; outline: none; background: #FFFFFF; box-sizing: border-box; transition: all 0.2s ease;"
                   oninput="filterChapters(this.value)"
                   onfocus="this.style.borderColor='#0E1022'; this.style.boxShadow='0 0 0 2px rgba(14,16,34,0.08)';"
                   onblur="this.style.borderColor='#E2E8F0'; this.style.boxShadow='none';">
        </div>

        <div style="display: flex; align-items: center; gap: 6px;">
            <span class="filter-pill active" onclick="filterByTag('all', this)">Tous</span>
            <span class="filter-pill" onclick="filterByTag('bac', this)">Incontournables BAC 2</span>
            <span class="filter-pill" onclick="filterByTag('method', this)">Méthodologie</span>
        </div>
    </div>

    {{-- Conteneurs des chapitres par matière (En grille de BOX 3D qui se retournent) --}}
    @foreach($subjectsWithCourses as $s)
    <div class="subject-chapters-container" id="chaptersOfSubject_{{ $s->id }}" 
         style="{{ ($activeSubject && $activeSubject->id == $s->id) ? 'display:grid;grid-template-columns:repeat(auto-fill, minmax(185px, 1fr));gap:14px;width:100%;' : 'display:none;' }}">
        @forelse($s->courses as $c)
        <div class="chapter-flip-card chapter-card-v2" 
             data-title="{{ strtolower($c->clean_title ?? $c->title) }}" 
             data-notions="{{ strtolower($c->subtopics ?? '') }}"
             data-tag="{{ str_contains(strtolower($c->clean_title ?? $c->title), 'méthod') || str_contains(strtolower($c->clean_title ?? $c->title), 'commentaire') || str_contains(strtolower($c->clean_title ?? $c->title), 'dissertation') || str_contains(strtolower($c->clean_title ?? $c->title), 'contraction') ? 'method' : 'bac' }}" 
             onclick="handleChapterCardClick(this, '{{ $s->id }}', '{{ $c->id }}', event)">
            
            <div class="chapter-flip-inner">
                {{-- FACE 1 (AVANT) : BOX Blanche Numérotée --}}
                <div class="chapter-flip-front">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: #64748B; background: #F1F5F9; padding: 2px 7px; border-radius: 6px; border: 1px solid #E2E8F0;">
                            Chapitre
                        </span>
                        <span style="display: inline-flex; align-items: center; gap: 3px; font-size: 10.5px; font-weight: 700; color: #64748B;">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            {{ $c->duration ?? '35 min' }}
                        </span>
                    </div>

                    <div style="margin: auto 0; text-align: center;">
                        <div style="font-size: 44px; font-weight: 900; color: #14325c; line-height: 1; letter-spacing: -0.03em;">
                            {{ $c->formatted_number ?? sprintf("%02d", $c->chapter_number ?? 1) }}
                        </div>
                        <div style="font-size: 11.5px; font-weight: 700; color: #64748B; margin-top: 5px;">
                            {{ $s->short }}
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; justify-content: center; gap: 4px; font-size: 10.5px; font-weight: 700; color: #3fa9f5;">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
                        <span>Survoler pour voir</span>
                    </div>
                </div>

                {{-- FACE 2 (ARRIÈRE) : Titre du Chapitre & Bouton de Lancement --}}
                <div class="chapter-flip-back">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 10px; font-weight: 800; color: #3fa9f5; background: rgba(63, 169, 245, 0.18); border: 1px solid rgba(63, 169, 245, 0.35); padding: 2px 7px; border-radius: 6px;">
                            CH-{{ $c->formatted_number ?? sprintf("%02d", $c->chapter_number ?? 1) }}
                        </span>
                        <span style="font-size: 10.5px; font-weight: 750; color: #D4F25C;">
                            {{ $c->duration ?? '35 min' }}
                        </span>
                    </div>

                    <div style="margin: auto 0; text-align: center;">
                        <h4 style="font-size: 13.5px; font-weight: 850; color: #FFFFFF; margin: 0 0 5px 0; line-height: 1.3; letter-spacing: -0.01em; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $c->clean_title ?? $c->title }}
                        </h4>
                        @if(!empty($c->subtopics))
                        <div style="font-size: 10px; color: #CBD5E1; line-height: 1.35; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $c->subtopics }}
                        </div>
                        @endif
                    </div>

                    <div style="width: 100%;">
                        <button type="button" 
                                onclick="event.stopPropagation(); selectCourse('{{ $s->id }}', '{{ $c->id }}');" 
                                style="display: flex; align-items: center; justify-content: center; gap: 5px; width: 100%; padding: 6px 10px; border-radius: 8px; background: #D4F25C; color: #0E1022; font-size: 11.5px; font-weight: 850; border: none; cursor: pointer; box-sizing: border-box; transition: transform 0.15s ease;">
                            <span>Lancer la séance</span>
                            <span>→</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div style="grid-column: 1 / -1; padding: 32px; text-align: center; background: #FFFFFF; border: 1.5px dashed #CBD5E1; color: #64748B; border-radius: 14px;">
            Aucun chapitre indexé pour cette matière.
        </div>
        @endforelse
    </div>
    @endforeach
</div>

{{-- ========================================================= --}}
{{-- ÉTAPE 3 : IMMERSION COURS & ATELIER RÉVISION IA (PLEIN ÉCRAN NAVIGATEUR & MATÉRIEL) --}}
{{-- ========================================================= --}}
<div id="stepView3" class="theme-day" style="{{ $activeStep == 3 ? 'display:flex;' : 'display:none;' }} position: fixed; inset: 0; z-index: 99999; flex-direction: column; width: 100vw; height: 100vh; background: var(--immersion-bg); overflow: hidden;">
    {{-- En-tête de la barre d'immersion (Identique à la Bibliothèque, Zéro-Displacement garanti) --}}
    <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 18px; background: var(--immersion-top); border-bottom: 1.5px solid var(--immersion-border); flex-wrap: nowrap; gap: 12px; flex-shrink: 0; width: 100%; box-sizing: border-box;">
        <div style="display: flex; align-items: center; gap: 12px; min-width: 0; flex: 1; overflow: hidden;">
            {{-- Bouton Retour aux chapitres (Raccourci: Échap) --}}
            <button type="button" onclick="handleExitAttempt(2)" class="immersion-close-btn" title="Retourner aux chapitres de la matière">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
                <span>Retour aux chapitres</span>
            </button>

            <div style="width: 1px; height: 22px; background: var(--immersion-divider); flex-shrink: 0;"></div>

            {{-- Badges et Titre du cours avec troncature sécurisée --}}
            <div style="display: flex; align-items: center; gap: 8px; min-width: 0; flex: 1; overflow: hidden;">
                <span class="biblio-badge" style="font-size: 11px; font-weight: 800; flex-shrink: 0; background: rgba(14,16,34,0.06); color: var(--immersion-text); border: 1px solid var(--immersion-border); padding: 3px 8px; border-radius: 6px;">
                    {{ $activeSubject->name ?? 'Matière' }}
                </span>
                <span id="crumbCourseName" title="{{ $activeCourse->clean_title ?? ($activeCourse->title ?? 'Chapitre') }}" 
                      style="font-size: 14px; font-weight: 850; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 42vw; color: var(--immersion-text);">
                    {{ $activeCourse->clean_title ?? ($activeCourse->title ?? 'Chapitre') }}
                </span>
                <span style="font-size: 11.5px; opacity: 0.75; font-weight: 600; flex-shrink: 0; color: var(--immersion-text); overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                    · {{ $student->level->name ?? 'Terminale' }} (BAC 2) · Série D
                </span>
            </div>
        </div>

        <div style="display: flex; align-items: center; gap: 8px; flex-shrink: 0; margin-left: auto;">
            {{-- Bouton Appelant Exercices (3 Paliers) - Zéro ombre, arrondi sobre 8px --}}
            {{-- Navigation Onglets Principaux : Cours Magistral / Exercices / Annale BAC --}}
            <div class="workshop-tabs-nav" style="display: flex; gap: 4px; background: rgba(14,16,34,0.06); padding: 3px; border-radius: 8px; border: 1px solid var(--immersion-border);">
                <button type="button" id="tabBtnCours" onclick="switchWorkshopTab('cours')" class="workshop-tab-btn active" title="Leçon magistrale, définitions et théorèmes">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                    <span>Cours Magistral</span>
                </button>
                <button type="button" id="tabBtnExercices" onclick="switchWorkshopTab('exercices')" class="workshop-tab-btn" title="Exercices gradués sur 3 paliers">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>
                    <span>Exercices (3 Paliers)</span>
                </button>
                <button type="button" id="tabBtnAnnale" onclick="switchWorkshopTab('annale')" class="workshop-tab-btn" title="Épreuve officielle d'annale du BAC en PDF">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                    <span>Sujet BAC (PDF)</span>
                </button>
            </div>

            {{-- Bouton Toggle Tuteur IA (Afficher / Masquer la colonne droite) --}}
            <button type="button" id="btnToggleWorkshopAi" onclick="toggleWorkshopAiCol()" class="immersion-btn-action active-ai" title="Afficher ou masquer la zone Tuteur IA">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><path d="M12 2a8 8 0 0 0-8 8c0 3.37 2.1 6.25 5 7.42V19a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2v-1.58c2.9-1.17 5-4.05 5-7.42a8 8 0 0 0-8-8z"></path></svg>
                <span id="workshopAiToggleLabel">Tuteur IA Actif</span>
            </button>

            {{-- Sélecteur Confort Visuel pour les yeux (Jour, Sépia doux, Nuit - ZÉRO EMOJI) --}}
            <div class="comfort-mode-wrap" title="Confort visuel pour lecture prolongée">
                <button type="button" onclick="setWorkshopComfort('day')" class="comfort-btn active" id="wComfortBtn_day">Jour</button>
                <button type="button" onclick="setWorkshopComfort('sepia')" class="comfort-btn" id="wComfortBtn_sepia">Sépia</button>
                <button type="button" onclick="setWorkshopComfort('night')" class="comfort-btn" id="wComfortBtn_night">Nuit</button>
            </div>

            {{-- Téléchargement du PDF officiel --}}
            <a href="{{ route('student.examens.download', $pdfFileId) }}" class="immersion-btn-action" title="Télécharger le document PDF original">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                <span>Télécharger</span>
            </a>

            {{-- Plein Écran TOTAL Ordinateur (Matériel - Native HTML5 Fullscreen) --}}
            <button type="button" onclick="toggleWorkshopFullscreen()" class="immersion-btn-action" title="Plein écran total de l'ordinateur (Raccourci: F)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"></path></svg>
            </button>
        </div>
    </div>

    {{-- Corps de l'Immersion : Split-Screen avec Document / Exercices à GAUCHE & Zone IA à DROITE --}}
    <div id="workshopSplitGrid" style="flex: 1 1 0%; min-height: 0; width: 100%; height: 100%; max-height: 100%; overflow: hidden; display: grid; grid-template-columns: minmax(0, 1fr) 390px; grid-template-rows: minmax(0, 1fr); background: var(--immersion-bg); transition: grid-template-columns 0.25s cubic-bezier(0.16, 1, 0.3, 1);">
        
        {{-- COLONNE GAUCHE : VISUALISEUR DU COURS MAGISTRAL, EXERCICES 3 PALIERS OU ANNALES PDF --}}
        <div id="workshopLeftCol" style="position: relative; height: 100%; max-height: 100%; min-height: 0; min-width: 0; width: 100%; max-width: 100%; background: var(--immersion-bg); overflow: hidden; display: flex; flex-direction: column;">
            
            {{-- MODE 1 : COURS MAGISTRAL & NOTIONS CLÉS (ACTIF PAR DÉFAUT - ZÉRO BRUIT, GRAND CONFORT) --}}
            <div id="workshopModeCours" style="display: block; flex: 1 1 0%; min-height: 0; min-width: 0; height: 100%; max-height: 100%; width: 100%; max-width: 100%; overflow-y: auto !important; overflow-x: hidden; -webkit-overflow-scrolling: touch; padding: 22px clamp(14px, 2.5vw, 32px); box-sizing: border-box; background: var(--immersion-card);">
                
                {{-- En-tête officiel du cours --}}
                <div style="margin-bottom: 22px; padding-bottom: 16px; border-bottom: 1.5px solid var(--immersion-border);">
                    <div style="display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 10px; align-items: center;">
                        <span style="font-size: 11px; font-weight: 850; background: #0E1022; color: #D4F25C; padding: 3px 10px; border-radius: 6px; text-transform: uppercase;">
                            {{ $chapterPedagogy['code'] ?? 'COURS OFFICIEL' }}
                        </span>
                        <span style="font-size: 11px; font-weight: 800; background: var(--immersion-pane); color: var(--immersion-text); border: 1px solid var(--immersion-border); padding: 3px 10px; border-radius: 6px;">
                            {{ $chapterPedagogy['coeff_badge'] ?? 'Programme National Lycée' }}
                        </span>
                        <span style="font-size: 11.5px; font-weight: 700; color: var(--immersion-subtext);">
                            {{ $chapterPedagogy['duration'] ?? '40 min d\'assimilation' }} · {{ $chapterPedagogy['ref'] ?? 'MEPST Togo' }}
                        </span>
                    </div>
                    <h1 style="font-size: 24px; font-weight: 900; margin: 0 0 8px 0; color: var(--immersion-text); font-family: 'Sora', sans-serif; letter-spacing: -0.02em;">
                        {{ $chapterPedagogy['title'] ?? ($activeCourse->clean_title ?? ($activeCourse->title ?? 'Cours officiel')) }}
                    </h1>
                    <p style="margin: 0; font-size: 13.5px; line-height: 1.6; color: var(--immersion-subtext);">
                        {{ $activeCourse->summary ?? 'Leçon magistrale conforme aux exigences pédagogiques de l\'examen national du BAC.' }}
                    </p>
                </div>

                {{-- Objectifs officiels du programme togolais --}}
                @if(!empty($chapterPedagogy['objectives']))
                <div style="margin-bottom: 24px; background: var(--immersion-pane); border: 1.5px solid var(--immersion-border); border-left: 4.5px solid #0284C7; border-radius: 12px; padding: 16px 20px;">
                    <div style="font-size: 11.5px; font-weight: 850; text-transform: uppercase; letter-spacing: 0.04em; color: #0284C7; margin-bottom: 8px;">
                        Objectifs du Programme Officiel (MEPST Togo)
                    </div>
                    <ul style="margin: 0; padding-left: 20px; font-size: 13px; line-height: 1.65; color: var(--immersion-text);">
                        @foreach($chapterPedagogy['objectives'] as $obj)
                            <li>{{ $obj }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- Définitions et Vocabulaire Fondamental --}}
                @if(!empty($chapterPedagogy['definitions']))
                <div style="margin-bottom: 26px;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
                        <div style="width: 22px; height: 22px; border-radius: 6px; background: #0E1022; color: #D4F25C; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 900;">1</div>
                        <h2 style="font-size: 16px; font-weight: 850; margin: 0; color: var(--immersion-text); font-family: 'Sora', sans-serif;">
                            Définitions et Notions Clés
                        </h2>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        @foreach($chapterPedagogy['definitions'] as $def)
                        <div class="course-def-card">
                            <b style="font-size: 13.5px; color: var(--immersion-text); display: block; margin-bottom: 4px;">{{ $def['term'] }}</b>
                            <div style="font-size: 13px; line-height: 1.55; color: var(--immersion-subtext);">
                                {!! $def['definition'] !!}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Théorèmes, Propriétés & Formules Fondamentales (KaTeX) --}}
                @if(!empty($chapterPedagogy['theorems']))
                <div style="margin-bottom: 26px;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
                        <div style="width: 22px; height: 22px; border-radius: 6px; background: #0E1022; color: #D4F25C; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 900;">2</div>
                        <h2 style="font-size: 16px; font-weight: 850; margin: 0; color: var(--immersion-text); font-family: 'Sora', sans-serif;">
                            Théorèmes et Propriétés Fondamentales
                        </h2>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 14px;">
                        @foreach($chapterPedagogy['theorems'] as $th)
                        <div class="course-theorem-card">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 6px; gap: 10px; flex-wrap: wrap;">
                                <span style="font-size: 12px; font-weight: 850; color: #0284C7; text-transform: uppercase; letter-spacing: 0.03em;">
                                    {{ $th['title'] }}
                                </span>
                                <button type="button" onclick="askTutorAboutTheorem('{{ addslashes($th['title']) }}', '{{ addslashes($th['latex'] ?? '') }}')" 
                                        style="background: rgba(14,16,34,0.06); color: var(--immersion-text); border: 1px solid var(--immersion-border); border-radius: 6px; padding: 4px 10px; font-size: 11px; font-weight: 800; cursor: pointer; display: inline-flex; align-items: center; gap: 5px;">
                                    <span>Demander au Tuteur IA</span> →
                                </button>
                            </div>
                            <div class="course-formula-box">
                                $${!! $th['latex'] !!}$$
                            </div>
                            <div style="font-size: 12.5px; line-height: 1.55; color: var(--immersion-text); margin-bottom: 4px;">
                                <b>Conditions d'application :</b> {{ $th['conditions'] }}
                            </div>
                            @if(!empty($th['comment']))
                            <div style="font-size: 12px; color: var(--immersion-subtext); font-style: italic;">
                                Note méthodologique : {{ $th['comment'] }}
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Exemple Démonstratif Résolu Pas à Pas --}}
                @if(!empty($chapterPedagogy['demonstration_example']))
                @php $ex = $chapterPedagogy['demonstration_example']; @endphp
                <div style="margin-bottom: 26px;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
                        <div style="width: 22px; height: 22px; border-radius: 6px; background: #0E1022; color: #D4F25C; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 900;">3</div>
                        <h2 style="font-size: 16px; font-weight: 850; margin: 0; color: var(--immersion-text); font-family: 'Sora', sans-serif;">
                            Exemple Démonstratif Résolu Pas à Pas (Type BAC)
                        </h2>
                    </div>
                    <div style="background: var(--immersion-pane); border: 1.5px solid var(--immersion-border); border-radius: 12px; padding: 18px 20px;">
                        <div style="font-size: 13.5px; font-weight: 700; color: var(--immersion-text); margin-bottom: 12px; line-height: 1.55;">
                            <b>Énoncé :</b> {!! $ex['statement'] !!}
                        </div>
                        <div style="border-left: 3px solid #10B981; padding-left: 14px; margin: 12px 0; display: flex; flex-direction: column; gap: 8px;">
                            <b style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.03em; color: #059669;">Démonstration officielle rédigée :</b>
                            @foreach($ex['steps'] as $s)
                                <div style="font-size: 13px; line-height: 1.6; color: var(--immersion-text);">{!! $s !!}</div>
                            @endforeach
                        </div>
                        @if(!empty($ex['conclusion']))
                        <div style="background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.25); border-radius: 8px; padding: 10px 14px; font-size: 12.5px; font-weight: 700; color: #065F46;">
                            Conclusion : {!! $ex['conclusion'] !!}
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Méthode Officielle & Pièges Classiques au BAC --}}
                <div style="margin-bottom: 26px; display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 250px), 1fr)); gap: 12px;">
                    @if(!empty($chapterPedagogy['trap']))
                    <div style="background: #FFFBEB; border: 1.5px solid #FDE68A; border-radius: 12px; padding: 14px 18px; box-sizing: border-box; overflow: hidden;">
                        <div style="font-size: 11.5px; font-weight: 850; text-transform: uppercase; letter-spacing: 0.03em; color: #92400E; margin-bottom: 6px;">
                            Piège Classique au BAC
                        </div>
                        <div style="font-size: 12.5px; line-height: 1.55; color: #78350F;">
                            {!! $chapterPedagogy['trap'] !!}
                        </div>
                    </div>
                    @endif

                    @if(!empty($chapterPedagogy['redaction_tip']))
                    <div style="background: #F0FDF4; border: 1.5px solid #BBF7D0; border-radius: 12px; padding: 14px 18px; box-sizing: border-box; overflow: hidden;">
                        <div style="font-size: 11.5px; font-weight: 850; text-transform: uppercase; letter-spacing: 0.03em; color: #166534; margin-bottom: 6px;">
                            Conseil des Jurys MEPST
                        </div>
                        <div style="font-size: 12.5px; line-height: 1.55; color: #14532D;">
                            {!! $chapterPedagogy['redaction_tip'] !!}
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Formulaire de Synthèse --}}
                @if(!empty($chapterPedagogy['formula_sheet']))
                <div style="margin-bottom: 26px;">
                    <div style="font-size: 12px; font-weight: 850; text-transform: uppercase; letter-spacing: 0.04em; color: var(--immersion-text); margin-bottom: 10px;">
                        Formules Incontournables à Mémoriser
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 200px), 1fr)); gap: 10px;">
                        @foreach($chapterPedagogy['formula_sheet'] as $f)
                        <div style="background: var(--immersion-pane); border: 1px solid var(--immersion-border); border-radius: 8px; padding: 10px 14px; box-sizing: border-box; overflow: hidden;">
                            <div style="font-size: 11px; font-weight: 700; color: var(--immersion-subtext);">{{ $f['name'] }}</div>
                            <div class="course-formula-box" style="margin: 4px 0 0; padding: 6px 10px; font-size: 13px;">$${!! $f['formula'] !!}$$</div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Bouton d'appel vers les 3 Paliers d'Exercices --}}
                <div style="margin-top: 28px; padding: 20px; background: #0E1022; border-radius: 14px; display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap;">
                    <div>
                        <div style="font-size: 15px; font-weight: 900; color: #FFFFFF; font-family: 'Sora', sans-serif; margin-bottom: 4px;">
                            Prêt à valider tes connaissances ?
                        </div>
                        <div style="font-size: 12px; color: rgba(255,255,255,0.75);">
                            Passe aux 3 Paliers gradués : Quiz Flash (60s), Application Méthode et Sujet BAC.
                        </div>
                    </div>
                    <button type="button" onclick="switchWorkshopTab('exercices')" 
                            style="background: #D4F25C; color: #0E1022; border: none; border-radius: 8px; padding: 11px 20px; font-size: 12.5px; font-weight: 850; cursor: pointer;">
                        Passer aux Exercices (3 Paliers) →
                    </button>
                </div>

            </div>

            {{-- MODE 2 : ANNALES OFFICIELLES DU BAC (PDF ORIGINAL) --}}
            <div id="workshopModeAnnale" style="display: none; width: 100%; height: 100%; max-height: 100%; flex-direction: column; flex: 1 1 0%; min-height: 0; overflow: hidden;">
                <iframe id="officialCoursePdfFrame" src="{{ route('student.examens.preview', $pdfFileId) }}#toolbar=1" 
                        style="width: 100%; height: 100%; border: none; display: block; flex: 1;"></iframe>
            </div>

            {{-- MODE 3 : EXERCICES & DÉFI DES 3 PALIERS --}}
            <div id="workshopModeExercices" style="display: none; flex: 1 1 0%; min-height: 0; height: 100%; max-height: 100%; width: 100%; overflow-y: auto !important; -webkit-overflow-scrolling: touch; padding: 24px 30px; box-sizing: border-box; background: var(--immersion-card);">
{{-- Stepper Séquentiel par Paliers (Navigation Sécurisée sans Saut) --}}
                <div class="mk-tier-stepper">
                    <div class="mk-tier-step active unlocked" id="stepIndicator1" onclick="navigateToTier(1)">
                        <div class="mk-step-circle" id="circleTier1">1</div>
                        <div>
                            <div style="font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; opacity: 0.8;">Palier 1</div>
                            <div style="font-size: 12.5px; font-weight: 800;">Quiz Réflexes (60s)</div>
                        </div>
                    </div>
                    <div class="mk-step-connector" id="connector1_2"></div>
                    <div class="mk-tier-step locked" id="stepIndicator2" onclick="navigateToTier(2)">
                        <div class="mk-step-circle" id="circleTier2">🔒</div>
                        <div>
                            <div style="font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; opacity: 0.8;">Palier 2</div>
                            <div style="font-size: 12.5px; font-weight: 800;">Application Méthode</div>
                        </div>
                    </div>
                    <div class="mk-step-connector" id="connector2_3"></div>
                    <div class="mk-tier-step locked" id="stepIndicator3" onclick="navigateToTier(3)">
                        <div class="mk-step-circle" id="circleTier3">🔒</div>
                        <div>
                            <div style="font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; opacity: 0.8;">Palier 3</div>
                            <div style="font-size: 12.5px; font-weight: 800;">Synthèse BAC Togo</div>
                        </div>
                    </div>
                </div>

                {{-- PALIER 1 : QUIZ FLASH CHRONOMÉTRÉ (60s) --}}
                <div id="tierContent1" style="display: block; background: var(--immersion-pane); border-radius: 14px; border: 1.5px solid var(--immersion-border); padding: 24px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px; border-bottom: 1.5px solid var(--immersion-border); padding-bottom: 16px;">
                        <div>
                            <h3 style="font-size: 19px; font-weight: 850; color: var(--immersion-text); margin: 0 0 4px 0;">
                                {{ $chapterPedagogy['tier1']['title'] ?? 'Palier 1 : Quiz Flash Réflexes & Définitions' }}
                            </h3>
                            <div style="font-size: 13px; color: var(--immersion-subtext);">
                                3 questions rapides pour tester tes automatismes avant d'aborder les exercices types BAC.
                            </div>
                        </div>

                        {{-- Chronomètre MonKayier Branded 60s --}}
                        <div class="mk-branded-timer" id="qcmTimerContainer">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#D4F25C" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" class="timer-pulse">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                            <div>
                                <div class="mk-timer-badge">CHRONO RÉFLEXES BAC</div>
                                <div id="qcmCountdown" class="mk-timer-dial">60s</div>
                            </div>
                        </div>
                    </div>

                    <form id="tier1Form">
                        @if(isset($chapterPedagogy['tier1']['questions']))
                            @foreach($chapterPedagogy['tier1']['questions'] as $qIdx => $q)
                            <div style="margin-bottom: 22px; background: var(--immersion-card); border: 1.5px solid var(--immersion-card-border); border-radius: 12px; padding: 18px;">
                                <div style="font-size: 15px; font-weight: 850; color: var(--immersion-text); margin-bottom: 12px;">
                                    Question {{ $qIdx + 1 }} : {{ $q['prompt'] }}
                                </div>

                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                                    @foreach($q['options'] as $optIdx => $opt)
                                    <label style="display: flex; align-items: center; gap: 10px; background: var(--immersion-pane); border: 1.5px solid var(--immersion-border); border-radius: 8px; padding: 10px 14px; cursor: pointer; font-size: 13.5px; font-weight: 600; color: var(--immersion-text); transition: border-color 0.15s ease;">
                                        <input type="radio" name="qcm_{{ $qIdx }}" value="{{ $optIdx }}" style="width: 17px; height: 17px; accent-color: #0E1022;">
                                        <span>{{ $opt }}</span>
                                    </label>
                                    @endforeach
                                </div>
                                <div id="qcmExpl_{{ $qIdx }}" style="display: none; margin-top: 10px; padding: 10px 12px; border-radius: 8px; font-size: 12.5px; font-weight: 600;"></div>
                            </div>
                            @endforeach
                        @endif

                        <div id="tier1ResultFeedback" style="margin-bottom: 14px; font-size: 13.5px; color: var(--immersion-text);"></div>

                        <div style="display: flex; justify-content: flex-end; gap: 10px; flex-wrap: wrap;">
                            <button type="button" id="btnSubmitTier1" onclick="submitTier1()" class="tg" 
                                    style="background: #D4F25C; color: #0E1022; font-size: 13px; font-weight: 850; padding: 10px 20px; border-radius: 8px; border: none; cursor: pointer; box-shadow: none !important;">
                                Valider le Quiz Réflexes
                            </button>
                            <button type="button" id="btnNextTier2" onclick="navigateToTier(2)" 
                                    style="display: none; background: #0E1022; color: #D4F25C; font-size: 13px; font-weight: 850; padding: 10px 20px; border-radius: 8px; border: none; cursor: pointer; box-shadow: none !important;">
                                Passer au Palier 2 (Application Méthode) →
                            </button>
                        </div>
                    </form>
                </div>

                {{-- PALIER 2 : EXERCICES D'APPLICATION & CORRECTION MÉTHODOLOGIQUE --}}
                <div id="tierContent2" style="display: none; background: var(--immersion-pane); border-radius: 14px; border: 1.5px solid var(--immersion-border); padding: 24px;">
                    <div style="margin-bottom: 20px; border-bottom: 1.5px solid var(--immersion-border); padding-bottom: 16px;">
                        <h3 style="font-size: 19px; font-weight: 850; color: var(--immersion-text); margin: 0 0 4px 0;">
                            {{ $chapterPedagogy['tier2']['title'] ?? 'Palier 2 : Exercice d\'Application Guidé' }}
                        </h3>
                        <div style="font-size: 13px; color: var(--immersion-subtext);">
                            Résous l'exercice sur ton cahier. Le Tuteur IA analyse ta copie manuscrite selon le barème officiel du BAC.
                        </div>
                    </div>

                    <div style="margin-bottom: 20px; background: var(--immersion-card); border: 1.5px solid var(--immersion-card-border); border-radius: 12px; padding: 18px; line-height: 1.6; font-size: 14px; color: var(--immersion-text);">
                        {!! $chapterPedagogy['tier2']['statement'] ?? $chapterPedagogy['tier2']['prompt'] ?? 'Résous l\'exercice ci-dessous.' !!}
                    </div>

                    {{-- ZONE DE DÉPÔT / PHOTO DE LA COPIE MANUSCRITE PALIER 2 --}}
                    <div style="margin-bottom: 22px;">
                        <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 8px;">
                            <label style="font-size: 13.5px; font-weight: 850; color: var(--immersion-text);">
                                Ta Démarche Manuscrite (Photo ou Scan de ton cahier) :
                            </label>
                            <span style="font-size: 11.5px; font-weight: 700; color: #166534; background: #DCFCE7; padding: 2px 8px; border-radius: 6px;">Recommandé · Conditions d'Examen</span>
                        </div>

                        <div style="background: rgba(22, 101, 52, 0.04); border: 1.5px solid rgba(22, 101, 52, 0.16); border-radius: 12px; padding: 12px 16px; margin-bottom: 14px; font-size: 12.5px; color: var(--immersion-text); line-height: 1.55;">
                            <b>Méthode de travail :</b> Rédige tes calculs et ta démonstration sur ton cahier ou une feuille de brouillon avec ton stylo. Prends une photo nette de ta résolution pour la faire corriger par l'IA.
                        </div>

                        {{-- Input fichier invisible avec capture caméra native --}}
                        <input type="file" id="tier2CopyInput" accept="image/*,application/pdf" capture="environment" style="display: none;" onchange="handleCopyFileSelected(this, 2)">

                        {{-- Zone de dépôt intuitive --}}
                        <div id="tier2Dropzone" class="mk-copy-dropzone" onclick="document.getElementById('tier2CopyInput').click()">
                            <div style="width: 48px; height: 48px; border-radius: 50%; background: #0E1022; color: #D4F25C; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                                    <circle cx="12" cy="13" r="4"></circle>
                                </svg>
                            </div>
                            <div style="font-size: 14.5px; font-weight: 850; color: var(--immersion-text); margin-bottom: 4px;">
                                Prendre en photo ou importer ma copie traitée
                            </div>
                            <div style="font-size: 12px; color: var(--immersion-subtext);">
                                Clique ici pour ouvrir l'appareil photo du téléphone ou déposer un fichier (JPG, PNG, PDF)
                            </div>
                        </div>

                        {{-- Aperçu de la copie sélectionnée --}}
                        <div id="tier2CopyPreviewBox" style="display: none; margin-top: 14px; background: var(--immersion-pane); border: 1.5px solid var(--immersion-border); border-radius: 12px; padding: 12px 16px; align-items: center; justify-content: space-between; gap: 14px; flex-wrap: wrap;">
                            <div style="display: flex; align-items: center; gap: 14px;">
                                <img id="tier2CopyPreviewImg" src="" alt="Aperçu copie" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px; border: 1.5px solid #CBD5E1; display: none;">
                                <div id="tier2PdfIcon" style="width: 52px; height: 52px; border-radius: 8px; background: #DC2626; color: #fff; font-weight: 900; font-size: 12px; display: none; align-items: center; justify-content: center;">
                                    PDF
                                </div>
                                <div>
                                    <div id="tier2CopyFileName" style="font-size: 13.5px; font-weight: 800; color: var(--immersion-text);">copie.jpg</div>
                                    <div id="tier2CopyFileSize" style="font-size: 11.5px; color: #166534; font-weight: 700;">Prête pour analyse IA</div>
                                </div>
                            </div>
                            <button type="button" onclick="document.getElementById('tier2CopyInput').click()" 
                                    style="background: #F1F5F9; color: #0E1022; border: 1.5px solid #CBD5E1; border-radius: 8px; padding: 8px 14px; font-size: 12px; font-weight: 700; cursor: pointer;">
                                Changer la photo
                            </button>
                        </div>

                        {{-- Option repliable : Saisie écrite au clavier si besoin --}}
                        <details style="margin-top: 14px; font-size: 12.5px; color: var(--immersion-subtext);">
                            <summary style="cursor: pointer; font-weight: 700; color: #475569;">
                                Ou saisir ta démarche par écrit au clavier (optionnel)
                            </summary>
                            <div style="margin-top: 10px;">
                                <textarea id="tier2StudentInput" rows="4" 
                                          style="width: 100%; border: 1.5px solid var(--immersion-border); border-radius: 8px; padding: 10px 12px; font-family: inherit; font-size: 13px; box-sizing: border-box; outline: none; background: var(--immersion-pane); color: var(--immersion-text);" 
                                          placeholder="Tu peux également taper ou préciser ta démarche ici…"></textarea>
                            </div>
                        </details>
                    </div>

                    {{-- Feedback & Rapport d'Évaluation IA Palier 2 --}}
                    <div id="tier2FeedbackBox" style="display: none; margin-bottom: 20px;">
                        <div id="tier2FeedbackText"></div>
                    </div>

                    {{-- Solution et Barème déverrouillés Palier 2 --}}
                    <div id="tier2UnlockedBox" class="solution-unlocked-box" style="display: none; margin-bottom: 18px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <b style="font-size: 15px; color: #166534;">Corrigé Modèle & Grille de Notation Déverrouillés</b>
                            <span id="tier2BaremeTag" style="font-size: 11.5px; font-weight: 850; background: #DCFCE7; color: #166534; padding: 3px 8px; border-radius: 6px;"></span>
                        </div>
                        <div id="tier2SolutionContent"></div>
                    </div>

                    <div style="display: flex; justify-content: flex-end; gap: 10px;">
                        <button type="button" id="btnSubmitTier2" onclick="submitTier2()" 
                                style="background: #0E1022; color: #D4F25C; border: none; border-radius: 8px; padding: 12px 22px; font-size: 13px; font-weight: 850; cursor: pointer; box-shadow: none !important;">
                            Soumettre ma copie au Tuteur IA
                        </button>
                        <button type="button" id="btnNextTier3" onclick="navigateToTier(3)" style="display: none; background: #D4F25C; color: #0E1022; border: none; border-radius: 8px; padding: 12px 22px; font-size: 13px; font-weight: 850; cursor: pointer; box-shadow: none !important;">
                            Passer au Palier 3 (Synthèse BAC) →
                        </button>
                    </div>
                </div>

                {{-- PALIER 3 : PROBLÈME DE SYNTHÈSE TYPE BAC --}}
                <div id="tierContent3" style="display: none; background: var(--immersion-pane); border-radius: 14px; border: 1.5px solid var(--immersion-border); padding: 24px;">
                    <div style="margin-bottom: 20px; border-bottom: 1.5px solid var(--immersion-border); padding-bottom: 16px;">
                        <h3 style="font-size: 19px; font-weight: 850; color: var(--immersion-text); margin: 0 0 4px 0;">
                            {{ $chapterPedagogy['tier3']['title'] ?? 'Palier 3 : Problème de Synthèse National' }}
                        </h3>
                        <div style="font-size: 13px; color: var(--immersion-subtext);">
                            Problème complet extrait des annales officielles du Togo. Entraîne-toi en conditions réelles.
                        </div>
                    </div>

                    <div style="margin-bottom: 20px; background: var(--immersion-card); border: 1.5px solid var(--immersion-card-border); border-radius: 12px; padding: 18px; line-height: 1.6; font-size: 14px; color: var(--immersion-text);">
                        {!! $chapterPedagogy['tier3']['statement'] ?? $chapterPedagogy['tier3']['prompt'] ?? 'Traite le problème de synthèse ci-dessous.' !!}
                    </div>

                    @if(!empty($chapterPedagogy['tier3']['official_exam']))
                        <div style="margin-bottom: 20px; background: rgba(37, 99, 235, 0.05); border: 1.5px solid rgba(37, 99, 235, 0.22); border-radius: 12px; padding: 14px 18px; display: flex; align-items: center; justify-content: space-between; gap: 14px; flex-wrap: wrap;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 40px; height: 40px; border-radius: 10px; background: #2563EB; color: #FFFFFF; display: flex; align-items: center; justify-content: center; font-weight: 850; font-size: 13px; flex: none;">
                                    BAC
                                </div>
                                <div>
                                    <div style="font-size: 11px; text-transform: uppercase; font-weight: 800; color: #2563EB; letter-spacing: 0.04em;">
                                        Épreuve Officielle du BAC Togo Rattachée
                                    </div>
                                    <b style="font-size: 14px; color: var(--immersion-text); display: block; margin-top: 2px;">
                                        {{ $chapterPedagogy['tier3']['official_exam']['title'] ?? 'Session Nationale' }}
                                    </b>
                                    <span style="font-size: 11.5px; font-family: monospace; color: #64748B; font-weight: 700;">
                                        Code : {{ $chapterPedagogy['tier3']['official_exam']['file_code'] }} · Session {{ $chapterPedagogy['tier3']['official_exam']['year'] }} (Série {{ $chapterPedagogy['tier3']['official_exam']['serie'] }})
                                    </span>
                                </div>
                            </div>
                            <div style="display: flex; gap: 8px; align-items: center;">
                                <a href="{{ $chapterPedagogy['tier3']['official_exam']['preview_url'] }}" target="_blank" 
                                   style="background: #2563EB; color: #FFFFFF; text-decoration: none; font-size: 12.5px; font-weight: 700; padding: 8px 16px; border-radius: 8px; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);">
                                    <span>Consulter l'épreuve complète (PDF)</span>
                                    <span>↗</span>
                                </a>
                                <a href="{{ $chapterPedagogy['tier3']['official_exam']['download_url'] }}" 
                                   title="Télécharger l'épreuve officielle"
                                   style="background: #F1F5F9; color: #0E1022; text-decoration: none; font-size: 14px; font-weight: 800; padding: 8px 12px; border-radius: 8px; border: 1.5px solid #CBD5E1;">
                                    ↓
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- ZONE DE DÉPÔT DE COPIE MANUSCRITE SUR CAHIER (PHOTO / SCAN) --}}
                    <div style="margin-bottom: 22px;">
                        <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 8px;">
                            <label style="font-size: 13.5px; font-weight: 850; color: var(--immersion-text);">
                                Ta Copie Manuscrite (Photo ou Scan de ton cahier) :
                            </label>
                            <span style="font-size: 11.5px; font-weight: 700; color: #2563EB;">Entraînement en Conditions d'Examen</span>
                        </div>

                        <div style="background: rgba(37, 99, 235, 0.04); border: 1.5px solid rgba(37, 99, 235, 0.16); border-radius: 12px; padding: 14px 16px; margin-bottom: 14px; font-size: 12.5px; color: var(--immersion-text); line-height: 1.55;">
                            <b>Consigne d'épreuve :</b> Rédige l'exercice au propre sur ta feuille de copie ou ton cahier d'exercices (comme au BAC). Prends ensuite une photo nette ou scanne ta copie pour la soumettre au Tuteur IA.
                        </div>

                        {{-- Input fichier invisible avec capture caméra native --}}
                        <input type="file" id="tier3CopyInput" accept="image/*,application/pdf" capture="environment" style="display: none;" onchange="handleCopyFileSelected(this)">

                        {{-- Zone de dépôt intuitive --}}
                        <div id="tier3Dropzone" class="mk-copy-dropzone" onclick="document.getElementById('tier3CopyInput').click()">
                            <div style="width: 50px; height: 50px; border-radius: 50%; background: #0E1022; color: #D4F25C; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 12px;">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                                    <circle cx="12" cy="13" r="4"></circle>
                                </svg>
                            </div>
                            <div style="font-size: 14.5px; font-weight: 850; color: var(--immersion-text); margin-bottom: 4px;">
                                Prendre en photo ou déposer ma copie de cahier
                            </div>
                            <div style="font-size: 12px; color: var(--immersion-subtext);">
                                Clique ici pour ouvrir l'appareil photo ou importer une photo/scan (JPG, PNG, PDF)
                            </div>
                        </div>

                        {{-- Aperçu de la copie sélectionnée --}}
                        <div id="tier3CopyPreviewBox" style="display: none; margin-top: 14px; background: var(--immersion-pane); border: 1.5px solid var(--immersion-border); border-radius: 12px; padding: 12px 16px; align-items: center; justify-content: space-between; gap: 14px; flex-wrap: wrap;">
                            <div style="display: flex; align-items: center; gap: 14px;">
                                <img id="tier3CopyPreviewImg" src="" alt="Aperçu copie" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px; border: 1.5px solid #CBD5E1; display: none;">
                                <div id="tier3PdfIcon" style="width: 52px; height: 52px; border-radius: 8px; background: #DC2626; color: #fff; font-weight: 900; font-size: 12px; display: none; align-items: center; justify-content: center;">
                                    PDF
                                </div>
                                <div>
                                    <div id="tier3CopyFileName" style="font-size: 13.5px; font-weight: 800; color: var(--immersion-text);">copie.jpg</div>
                                    <div id="tier3CopyFileSize" style="font-size: 11.5px; color: #166534; font-weight: 700;">Prête pour évaluation</div>
                                </div>
                            </div>
                            <button type="button" onclick="document.getElementById('tier3CopyInput').click()" 
                                    style="background: #F1F5F9; color: #0E1022; border: 1.5px solid #CBD5E1; border-radius: 8px; padding: 8px 14px; font-size: 12px; font-weight: 700; cursor: pointer;">
                                Changer la photo
                            </button>
                        </div>

                        {{-- Remarque facultative --}}
                        <div style="margin-top: 12px;">
                            <input type="text" id="tier3StudentNotes" placeholder="Une remarque ou question sur ta rédaction ? (facultatif)" 
                                   style="width: 100%; border: 1.5px solid var(--immersion-border); border-radius: 8px; padding: 10px 14px; font-size: 13px; outline: none; background: var(--immersion-pane); color: var(--immersion-text); box-sizing: border-box;">
                        </div>
                    </div>

                    {{-- Feedback & Rapport d'Évaluation IA Palier 3 --}}
                    <div id="tier3FeedbackBox" style="display: none; margin-bottom: 20px;">
                        <div id="tier3FeedbackText"></div>
                    </div>

                    {{-- Zone de Corrigé Intégral Déverrouillé --}}
                    <div id="tier3UnlockedBox" class="solution-unlocked-box" style="display: none; margin-bottom: 18px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <b style="font-size: 15px; color: #166534;">Corrigé National Intégral & Barème Déverrouillés</b>
                            <span style="font-size: 11.5px; font-weight: 850; background: #DCFCE7; color: #166534; padding: 3px 8px; border-radius: 6px;">Palier 3 Validé</span>
                        </div>
                        <div id="tier3SolutionContent"></div>
                    </div>

                    <div style="display: flex; justify-content: flex-end;">
                        <button type="button" id="btnSubmitTier3" onclick="submitTier3()" 
                                style="background: #0E1022; color: #D4F25C; border: none; border-radius: 8px; padding: 12px 24px; font-size: 13.5px; font-weight: 850; cursor: pointer; box-shadow: none !important;">
                            Soumettre ma copie pour évaluation officielle MEPST →
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- COLONNE DROITE : ZONE TUTEUR IA DÉDIÉ (390px par défaut, Redimensionnable à la souris & Repliable) --}}
        <div id="workshopAiColumn" class="immersion-ai-pane">
            <div class="mk-split-resizer" title="Glisser vers la gauche ou la droite pour agrandir ou réduire l'espace du Tuteur IA (Double-clic pour alterner large / standard)">
                <div class="mk-split-resizer-grip"></div>
            </div>
            {{-- En-tête Tuteur IA avec Appel Vocal Mains-Libres (style ChatGPT), Simulation Oral BAC & Réinitialisation --}}
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--immersion-border); padding-bottom: 8px; flex-wrap: wrap; gap: 6px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 28px; height: 28px; border-radius: 8px; background: #D4F25C; color: #0E1022; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 13px;">
                        IA
                    </div>
                    <div>
                        <b style="font-size: 13px; color: var(--immersion-text); display: block;">Tuteur IA MonKayier</b>
                        <div style="font-size: 11px; opacity: 0.7; color: var(--immersion-text);">Connecté au cours · {{ $activeSubject->short ?? 'Matière' }}</div>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 5px; flex-wrap: wrap;">
                    <select id="tutorVoiceSelect" onchange="localStorage.setItem('mk_tutor_voice', this.value)" title="Choisir la voix neurale humaine"
                            style="font-size: 11px; font-weight: 700; background: #F1F5F9; color: #0E1022; border: 1px solid #CBD5E1; padding: 3px 6px; border-radius: 6px; cursor: pointer;">
                        <option value="kodjo">Voix : Kodjo (H)</option>
                        <option value="afi">Voix : Afi (F)</option>
                        <option value="mawuli">Voix : Mawuli (H)</option>
                    </select>
                    <button type="button" id="btnLiveVoiceCall" onclick="toggleLiveVoiceCall()" 
                            style="display: inline-flex; align-items: center; gap: 5px; font-size: 11px; font-weight: 800; background: #0E1022; color: #D4F25C; border: 1px solid #0E1022; padding: 4px 9px; border-radius: 6px; cursor: pointer;" title="Démarrer une conversation vocale continue mains-libres (style ChatGPT Voice)">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path><path d="M19 10v2a7 7 0 0 1-14 0v-2"></path><line x1="12" y1="19" x2="12" y2="23"></line></svg>
                        <span id="liveVoiceBtnLabel">Appel Vocal</span>
                    </button>
                    <button type="button" id="btnOralExamMode" onclick="startOralBacSimulation()" 
                            style="display: inline-flex; align-items: center; gap: 4px; font-size: 11px; font-weight: 750; background: #EEF2FF; color: #4338CA; border: 1px solid #C7D2FE; padding: 4px 8px; border-radius: 6px; cursor: pointer;" title="Le Tuteur IA t'interroge comme à l'oral du BAC et note ta réponse">
                        <span>Oral BAC</span>
                    </button>
                    <button type="button" onclick="clearChapterChatHistory()" 
                            style="display: inline-flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; background: #F8FAFC; color: #64748B; border: 1px solid #CBD5E1; padding: 4px 7px; border-radius: 6px; cursor: pointer;" title="Nouvelle discussion (effacer l'historique de ce chapitre)">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="1 4 1 10 7 10"></polyline><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path></svg>
                    </button>
                </div>
            </div>

            {{-- DOCK APPEL VOCAL CONTINU MAINS-LIBRES (STYLE CHATGPT VOICE) --}}
            <div id="liveVoiceCallDock" class="mk-voice-call-dock" data-state="idle">
                <div style="display:flex;justify-content:space-between;align-items:center;width:100%;">
                    <span id="liveVoiceStateBadge" style="font-size:10.5px;font-weight:800;text-transform:uppercase;letter-spacing:0.05em;background:rgba(212,242,92,0.18);color:#D4F25C;padding:3px 8px;border-radius:999px;">
                        Conversation Vocale Mains-Libres
                    </span>
                    <button type="button" onclick="toggleLiveVoiceCall(false)" style="background:rgba(255,255,255,0.12);color:#FFFFFF;border:none;border-radius:6px;padding:3px 8px;font-size:11px;font-weight:700;cursor:pointer;">
                        Terminer
                    </button>
                </div>

                <div class="mk-voice-orb-container" onclick="onLiveVoiceOrbClick()" title="Cliquer pour parler ou interrompre la voix">
                    <div class="mk-voice-orb-halo"></div>
                    <div class="mk-voice-orb-core">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#D4F25C" stroke-width="2.3"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path><path d="M19 10v2a7 7 0 0 1-14 0v-2"></path><line x1="12" y1="19" x2="12" y2="23"></line></svg>
                    </div>
                </div>

                <div id="liveVoiceTranscript" style="font-size:12.5px;font-weight:600;text-align:center;line-height:1.4;color:#E2E8F0;max-width:95%;min-height:18px;">
                    Parle librement : je t'écoute et te réponds automatiquement à la voix.
                </div>
            </div>

            {{-- Flux de discussion Socratique (avec restauration de la mémoire par chapitre) --}}
            <div id="chatFeed" class="immersion-chat-feed">
                <div class="chat-msg assistant" style="display: flex; flex-direction: column; gap: 6px; max-width: 95%;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span class="tg" style="align-self: flex-start; font-size: 10px; background: #F1F5F9; color: #475569;">Tuteur Vocal & Écrit</span>
                        <button type="button" class="mk-tts-btn" data-speech="Bonjour {{ ($student->first_name ?? '') !== 'Nouvel' ? ($student->first_name ?? 'toi') : 'toi' }}. Je suis ton tuteur IA pour le chapitre {{ $activeCourse->clean_title ?? ($activeCourse->title ?? 'ce cours') }}. Tu peux me poser des questions à l'écrit, surligner une phrase du cours ou lancer un appel vocal en direct." onclick="window.mkSpeakHuman(this.dataset.speech, this)">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon><path d="M15.54 8.46a5 5 0 0 1 0 7.07"></path></svg>
                            <span>Écouter</span>
                        </button>
                    </div>
                    <div class="mk-ai-formatted" style="background: var(--immersion-card); border: 1.5px solid var(--immersion-card-border); padding: 12px 14px; border-radius: 4px 14px 14px 14px; line-height: 1.55; font-size: 13px; color: var(--immersion-text);">
                        <p class="mk-ai-p">Bonjour {{ ($student->first_name ?? '') !== 'Nouvel' ? ($student->first_name ?? 'toi') : 'toi' }}. Je suis ton tuteur IA pour le chapitre <strong class="mk-ai-bold">« {{ $activeCourse->clean_title ?? ($activeCourse->title ?? 'ce cours') }} »</strong>. Tu peux me poser tes questions, surligner un passage du cours à gauche pour que je te l'explique, ou cliquer sur <strong class="mk-ai-bold">Appel Vocal</strong> pour discuter de vive voix.</p>
                    </div>
                </div>

                @if(isset($aiMessages) && count($aiMessages) > 0)
                    @foreach($aiMessages as $histMsg)
                        @if($histMsg->role === 'user')
                            <div class="chat-msg user" style="align-self:flex-end;background:#0E1022;color:#fff;padding:12px 16px;border-radius:18px 18px 4px 18px;max-width:85%;line-height:1.45;font-size:13px;word-break:break-word;">
                                {{ $histMsg->content }}
                            </div>
                        @else
                            <div class="chat-msg assistant" style="display:flex;flex-direction:column;gap:6px;max-width:95%;">
                                <div style="display:flex;justify-content:space-between;align-items:center;">
                                    <span class="tg" style="align-self:flex-start;font-size:10px;background:#F1F5F9;color:#475569;">Mémoire du chapitre</span>
                                    <button type="button" class="mk-tts-btn" data-speech="{{ $histMsg->spoken_text ?? '' }}" onclick="speakText(this.dataset.speech, this)">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon><path d="M15.54 8.46a5 5 0 0 1 0 7.07"></path></svg>
                                        <span>Écouter</span>
                                    </button>
                                </div>
                                <div style="background:var(--immersion-card, #F8FAFC);border:1.5px solid var(--immersion-card-border, #E2E8F0);padding:12px 14px;border-radius:4px 14px 14px 14px;line-height:1.55;font-size:13px;color:var(--immersion-text, #0E1022);">
                                    {!! $histMsg->formatted_html !!}
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>

            {{-- Indicateur de frappe / écoute IA --}}
            <div id="typingIndicator" style="display: none; align-items: center; gap: 8px; color: var(--immersion-subtext); font-size: 12px; padding: 4px 8px;">
                <span style="display: inline-block; width: 7px; height: 7px; border-radius: 50%; background: #4F46E5; animation: timerPulse 0.8s infinite alternate;"></span>
                <span id="typingIndicatorText">Consultation du cours officiel…</span>
            </div>

            {{-- TIROIR 1 : SUGGESTIONS RAPIDES & QUIZ FLASH (Masqué par défaut, affichable à volonté) --}}
            <div id="chatSuggestionsDrawer" class="mk-suggestions-drawer is-collapsed">
                <div class="mk-drawer-header">
                    <span class="mk-drawer-title">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#4F46E5" stroke-width="2.5"><path d="M12 2a7 7 0 0 1 7 7c0 2.38-1.19 4.47-3 5.74V17a1 1 0 0 1-1 1H9a1 1 0 0 1-1-1v-2.26C6.19 13.47 5 11.38 5 9a7 7 0 0 1 7-7z"></path><line x1="9" y1="21" x2="15" y2="21"></line></svg>
                        Questions suggérées & Quiz
                    </span>
                    <button type="button" class="mk-drawer-close-btn" onclick="window.mkToggleChatDrawer('suggestions', 'chat', false)" title="Masquer les suggestions pour agrandir la discussion">
                        <span>Masquer</span>
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                </div>
                <div id="chipsContainer" class="mk-suggestions-list">
                    <button type="button" class="mk-sug-pill mk-sug-pill--quiz" onclick="switchWorkshopMode('B')">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        <span>Faire le Quiz Flash</span>
                        <span class="mk-sug-quiz-tag">60s</span>
                    </button>
                    @if(isset($chapterPedagogy['suggestions']))
                        @foreach($chapterPedagogy['suggestions'] as $sug)
                        <button type="button" class="mk-sug-pill" onclick="sendSuggestion('{{ addslashes($sug) }}')">
                            <span>{{ $sug }}</span>
                            <span class="mk-sug-arrow">→</span>
                        </button>
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- TIROIR 2 : CLAVIER SCIENTIFIQUE COMPLET (Masqué par défaut, s'affiche au clic sur Clavier fx) --}}
            <div id="chatSciKbdDrawer" class="mk-scikbd-drawer is-collapsed">
                <div class="mk-drawer-header">
                    <div class="mk-scikbd-tabs">
                        <button type="button" class="mk-scikbd-tab is-active" onclick="window.mkFilterSciKbd(this, 'all', 'chatSciKbdGrid')">Tout</button>
                        <button type="button" class="mk-scikbd-tab" onclick="window.mkFilterSciKbd(this, 'math', 'chatSciKbdGrid')">Maths</button>
                        <button type="button" class="mk-scikbd-tab" onclick="window.mkFilterSciKbd(this, 'chem', 'chatSciKbdGrid')">Chimie & Phys</button>
                    </div>
                    <button type="button" class="mk-drawer-close-btn" onclick="window.mkToggleChatDrawer('scikbd', 'chat', false)" title="Masquer le clavier scientifique">
                        <span>Masquer</span>
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <div id="chatSciKbdGrid" class="mk-scikbd-grid">
                    <button type="button" class="mk-scikbd-key" data-cat="math" onclick="insertSymbol('chatInput', '√(')">√x</button>
                    <button type="button" class="mk-scikbd-key" data-cat="math" onclick="insertSymbol('chatInput', '²')">x²</button>
                    <button type="button" class="mk-scikbd-key" data-cat="math" onclick="insertSymbol('chatInput', '^')">xⁿ</button>
                    <button type="button" class="mk-scikbd-key" data-cat="math" onclick="insertSymbol('chatInput', 'lim(x→) ')">lim</button>
                    <button type="button" class="mk-scikbd-key" data-cat="math" onclick="insertSymbol('chatInput', 'ln(')">ln</button>
                    <button type="button" class="mk-scikbd-key" data-cat="math" onclick="insertSymbol('chatInput', 'e^')">eˣ</button>
                    <button type="button" class="mk-scikbd-key" data-cat="math" onclick="insertSymbol('chatInput', '∫')">∫</button>
                    <button type="button" class="mk-scikbd-key" data-cat="math" onclick="insertSymbol('chatInput', '∑')">∑</button>
                    <button type="button" class="mk-scikbd-key" data-cat="math" onclick="insertSymbol('chatInput', '∞')">∞</button>
                    <button type="button" class="mk-scikbd-key" data-cat="math" onclick="insertSymbol('chatInput', 'π')">π</button>
                    <button type="button" class="mk-scikbd-key" data-cat="math" onclick="insertSymbol('chatInput', 'Δ')">Δ</button>
                    <button type="button" class="mk-scikbd-key" data-cat="math" onclick="insertSymbol('chatInput', '≥')">≥</button>
                    <button type="button" class="mk-scikbd-key" data-cat="math" onclick="insertSymbol('chatInput', '≤')">≤</button>
                    <button type="button" class="mk-scikbd-key" data-cat="chem" onclick="insertSymbol('chatInput', '[H₃O⁺]')">[H₃O⁺]</button>
                    <button type="button" class="mk-scikbd-key" data-cat="chem" onclick="insertSymbol('chatInput', '[OH⁻]')">[OH⁻]</button>
                    <button type="button" class="mk-scikbd-key" data-cat="chem" onclick="insertSymbol('chatInput', 'pH')">pH</button>
                    <button type="button" class="mk-scikbd-key" data-cat="chem" onclick="insertSymbol('chatInput', 'pKa')">pKa</button>
                    <button type="button" class="mk-scikbd-key" data-cat="chem" onclick="insertSymbol('chatInput', '⇄')">⇄</button>
                    <button type="button" class="mk-scikbd-key" data-cat="chem" onclick="insertSymbol('chatInput', '→')">→</button>
                    <button type="button" class="mk-scikbd-key" data-cat="chem" onclick="insertSymbol('chatInput', 'λ')">λ</button>
                    <button type="button" class="mk-scikbd-key" data-cat="chem" onclick="insertSymbol('chatInput', 'Ω')">Ω</button>
                </div>
            </div>

            {{-- BARRE D'OUTILS COMPACTE : BOUTONS POUR AFFICHER / MASQUER LES SUGGESTIONS ET LE CLAVIER SCIENTIFIQUE --}}
            <div class="mk-chat-tools-bar">
                <div class="mk-chat-tools-left">
                    <button type="button" id="chatBtnToggleSug" class="mk-tool-toggle-btn" onclick="window.mkToggleChatDrawer('suggestions', 'chat')" title="Afficher ou masquer les suggestions de questions">
                        <svg class="mk-tool-icon" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M12 2a7 7 0 0 1 7 7c0 2.38-1.19 4.47-3 5.74V17a1 1 0 0 1-1 1H9a1 1 0 0 1-1-1v-2.26C6.19 13.47 5 11.38 5 9a7 7 0 0 1 7-7z"></path><line x1="9" y1="21" x2="15" y2="21"></line></svg>
                        <span class="mk-tool-state-text">Idées & Quiz</span>
                        <span class="mk-tool-badge" id="chatSugCountBadge">{{ isset($chapterPedagogy['suggestions']) ? count($chapterPedagogy['suggestions']) + 1 : 4 }}</span>
                        <svg class="mk-tool-chevron" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>

                    <button type="button" id="chatBtnToggleKbd" class="mk-tool-toggle-btn" onclick="window.mkToggleChatDrawer('scikbd', 'chat')" title="Afficher ou masquer les symboles du clavier scientifique">
                        <span class="mk-tool-fx-badge">fx</span>
                        <span class="mk-tool-state-text">Clavier scientifique</span>
                        <svg class="mk-tool-chevron" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                </div>
            </div>

            {{-- Champ de saisie multi-lignes auto-extensible avec micro et raccourci fx intégrés --}}
            <div class="immersion-chat-input-bar">
                <button type="button" id="micInputBtn" onclick="toggleSpeechDictation()" class="immersion-mic-btn" title="Parler au micro">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path><path d="M19 10v2a7 7 0 0 1-14 0v-2"></path><line x1="12" y1="19" x2="12" y2="23"></line><line x1="8" y1="23" x2="16" y2="23"></line></svg>
                </button>
                <button type="button" id="chatFxInlineBtn" onclick="window.mkToggleChatDrawer('scikbd', 'chat')" class="immersion-fx-btn" title="Afficher / Masquer le clavier scientifique (fx)">
                    fx
                </button>
                <textarea id="chatInput" rows="1" placeholder="Pose ta question ou dicte au micro…" 
                          onkeydown="if(event.key==='Enter' && !event.shiftKey){ event.preventDefault(); submitChatMessage(); }" class="immersion-input-field"></textarea>
                <button type="button" id="sendBtn" onclick="submitChatMessage()" class="immersion-send-btn">
                    Envoyer
                </button>
            </div>
        </div>
    </div>
</div>

{{-- JAVASCRIPT INTERACTIF DE L'ATELIER RÉVISION IA --}}
<script>

// Fonctions du Clavier Virtuel Scientifique
function switchKbdTab(btn, type) {
    const toolbar = btn.closest('.sc-kbd-toolbar');
    if (!toolbar) return;
    const tabs = toolbar.querySelectorAll('.sc-kbd-tab');
    tabs.forEach(t => t.classList.remove('active'));
    btn.classList.add('active');

    const mathsKeys = toolbar.querySelector('.sc-kbd-maths');
    const pctKeys = toolbar.querySelector('.sc-kbd-pct');
    if (type === 'maths') {
        if (mathsKeys) mathsKeys.style.display = 'flex';
        if (pctKeys) pctKeys.style.display = 'none';
    } else {
        if (mathsKeys) mathsKeys.style.display = 'none';
        if (pctKeys) pctKeys.style.display = 'flex';
    }
}

function insertSymbol(targetId, symbol) {
    const el = document.getElementById(targetId);
    if (!el) return;
    const start = el.selectionStart || 0;
    const end = el.selectionEnd || 0;
    const text = el.value || '';
    el.value = text.substring(0, start) + symbol + text.substring(end);
    el.focus();
    el.selectionStart = el.selectionEnd = start + symbol.length;
    if (window.mkAutoResizeTextarea) window.mkAutoResizeTextarea(el);
}

function renderKaTeX() {
    if (typeof renderMathInElement === 'function') {
        renderMathInElement(document.body, {
            delimiters: [
                {left: "$$", right: "$$", display: true},
                {left: "\\[", right: "\\]", display: true},
                {left: "$", right: "$", display: false},
                {left: "\\(", right: "\\)", display: false}
            ],
            throwOnError: false
        });
    }
}

document.addEventListener("DOMContentLoaded", function() {
    setTimeout(renderKaTeX, 350);
});
let currentStep = {{ $activeStep ?? 1 }};
let currentSubjectId = "{{ $activeSubject->id ?? '' }}";
let currentCourseId = "{{ $activeCourse->id ?? '' }}";
let qcmTimerInterval = null;
let qcmTimeLeft = 60;

// Chronométrage et enregistrement du temps d'étude effectif sur le cours
let studyTimeTrackerInterval = null;
function startStudyTimer() {
    if (studyTimeTrackerInterval) return;
    studyTimeTrackerInterval = setInterval(() => {
        if (currentStep === 3 && currentCourseId && !document.hidden) {
            fetch('{{ route("student.chat.track_time") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    course_id: currentCourseId,
                    subject_id: currentSubjectId,
                    duration_seconds: 30
                })
            }).catch(() => {});
        }
    }, 30000);
}

function stopStudyTimer() {
    if (studyTimeTrackerInterval) {
        clearInterval(studyTimeTrackerInterval);
        studyTimeTrackerInterval = null;
    }
}

if (currentStep === 3) {
    startStudyTimer();
}

// Variables pour la dictée vocale (Web Speech API)
let recognition = null;
let isRecordingVoice = false;

// Navigation Stepper 1 -> 2 -> 3
function goToStep(step) {
    currentStep = step;

    const mainHeader = document.getElementById('revisionMainHeader');
    if (mainHeader) {
        mainHeader.style.display = (step === 3) ? 'none' : 'block';
    }

    // Mise à jour visuelle des onglets du stepper
    for (let i = 1; i <= 3; i++) {
        const tab = document.getElementById('stepTab' + i);
        const view = document.getElementById('stepView' + i);

        if (tab) {
            tab.classList.remove('active', 'completed');
            if (i === step) tab.classList.add('active');
            else if (i < step) tab.classList.add('completed');
        }

        if (view) {
            view.style.display = (i === step) ? (i === 3 ? 'flex' : 'block') : 'none';
        }
    }

    // Gestion de l'Immersion & verrouillage du scroll d'arrière-plan
    if (step === 3) {
        document.body.style.overflow = 'hidden';
        startStudyTimer();
    } else {
        document.body.style.overflow = '';
        stopStudyTimer();
        if (document.fullscreenElement) {
            document.exitFullscreen().catch(() => {});
        }
    }

    // Scroll fluide vers le haut
    window.scrollTo({ top: 0, behavior: 'smooth' });

    // Mise à jour de l'URL sans rechargement
    updateUrlParams();
}

// Interaction 3D sur BOX de Matière (retournement tactile et ouverture vers l'étape 2)
function handleSubjectCardClick(card, subjectId, subjectName, subjectShort, subjectCoeff, subjectCount, subjectIcon, subjectBg, subjectColor, pdfFileId, event) {
    if (window.matchMedia('(hover: hover)').matches) {
        // Sur ordinateur (souris) : le survol a déjà retourné la carte, le clic ouvre immédiatement l'étape 2
        selectSubject(subjectId, subjectName, subjectShort, subjectCoeff, subjectCount, subjectIcon, subjectBg, subjectColor, pdfFileId);
    } else {
        // Sur tactile (mobile/tablette)
        if (card.classList.contains('is-flipped')) {
            selectSubject(subjectId, subjectName, subjectShort, subjectCoeff, subjectCount, subjectIcon, subjectBg, subjectColor, pdfFileId);
        } else {
            document.querySelectorAll('.subject-flip-card.is-flipped').forEach(c => {
                if (c !== card) c.classList.remove('is-flipped');
            });
            card.classList.add('is-flipped');
        }
    }
}

// Sélection d'une Matière (Passe de l'Étape 1 à l'Étape 2 avec mise à jour immédiate de la bannière)
function selectSubject(subjectId, subjectName, subjectShort, subjectCoeff, subjectCount, subjectIcon, subjectBg, subjectColor, pdfFileId) {
    currentSubjectId = subjectId;

    // Mise à jour dynamique de la bannière Hero à l'étape 2 (Design épuré sans bordures colorées)
    const bannerTitle = document.getElementById('subjectBannerTitle');
    const bannerCount = document.getElementById('subjectBannerCount');
    const bannerIcon = document.getElementById('subjectBannerIcon');
    const heroCard = document.getElementById('subjectBannerHero');
    const step2Label = document.getElementById('step2TitleLabel');
    const crumbSubject = document.getElementById('crumbSubjectName');

    if (bannerTitle) bannerTitle.innerText = subjectName;
    if (bannerCount) bannerCount.innerText = `${subjectCount} chapitres disponibles`;
    if (bannerIcon) {
        bannerIcon.innerHTML = subjectIcon;
        bannerIcon.style.background = '#F8FAFC';
        bannerIcon.style.borderColor = '#E2E8F0';
        bannerIcon.style.color = '#0E1022';
    }
    if (heroCard) {
        heroCard.style.background = '#FFFFFF';
        heroCard.style.borderColor = '#E2E8F0';
    }
    if (step2Label) step2Label.innerText = subjectShort;
    if (crumbSubject) crumbSubject.innerText = subjectName;

    // Afficher uniquement le conteneur de chapitres de la matière sélectionnée (en grille de BOX 3D)
    document.querySelectorAll('.subject-chapters-container').forEach(el => {
        el.style.display = 'none';
    });
    const targetChapters = document.getElementById('chaptersOfSubject_' + subjectId);
    if (targetChapters) {
        targetChapters.style.display = 'grid';
        targetChapters.style.gridTemplateColumns = 'repeat(auto-fill, minmax(185px, 1fr))';
        targetChapters.style.gap = '14px';
        targetChapters.style.width = '100%';
    }

    goToStep(2);
}

// Interaction 3D sur BOX de Chapitre (retournement tactile et ouverture vers l'étape suivante)
function handleChapterCardClick(card, subjectId, courseId, event) {
    if (window.matchMedia('(hover: hover)').matches) {
        // Sur ordinateur (souris), le survol a déjà retourné la carte : le clic ouvre le chapitre
        selectCourse(subjectId, courseId);
    } else {
        // Sur tactile (mobile/tablette)
        if (card.classList.contains('is-flipped')) {
            selectCourse(subjectId, courseId);
        } else {
            document.querySelectorAll('.chapter-flip-card.is-flipped').forEach(c => {
                if (c !== card) c.classList.remove('is-flipped');
            });
            card.classList.add('is-flipped');
        }
    }
}

// Sélection d'un Chapitre (Passe à l'Étape 3 avec chargement du cours et du PDF correspondant)
function selectCourse(subjectId, courseId) {
    currentSubjectId = subjectId;
    currentCourseId = courseId;
    window.location.href = `/student/chat?subject=${encodeURIComponent(subjectId)}&course=${encodeURIComponent(courseId)}`;
}

// Recherche dynamique des chapitres dans la matière active
function filterChapters(query) {
    const q = query.toLowerCase().trim();
    const container = document.getElementById('chaptersOfSubject_' + currentSubjectId);
    const scope = container ? container.querySelectorAll('.chapter-card-v2') : document.querySelectorAll('.chapter-card-v2');
    scope.forEach(card => {
        const title = card.getAttribute('data-title') || '';
        const notions = card.getAttribute('data-notions') || '';
        card.style.display = (title.includes(q) || notions.includes(q)) ? 'block' : 'none';
    });
}

// Filtrage par tags dans la matière active
function filterByTag(tag, element) {
    document.querySelectorAll('.filter-pill').forEach(pill => pill.classList.remove('active'));
    if (element) element.classList.add('active');

    const container = document.getElementById('chaptersOfSubject_' + currentSubjectId);
    const scope = container ? container.querySelectorAll('.chapter-card-v2') : document.querySelectorAll('.chapter-card-v2');
    scope.forEach(card => {
        const cardTag = card.getAttribute('data-tag') || 'bac';
        if (tag === 'all' || cardTag === tag) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

// Suivi de validation du Quiz & Système de Rappel avant de quitter
let hasCompletedTier1 = false;
let pendingExitStep = null;

function handleExitAttempt(targetStep) {
    if (hasCompletedTier1 || currentStep !== 3) {
        goToStep(targetStep);
        return;
    }
    pendingExitStep = targetStep;
    const modal = document.getElementById('quizReminderModal');
    if (modal) {
        modal.style.display = 'flex';
    } else {
        goToStep(targetStep);
    }
}

function closeReminderAndStartQuiz() {
    const modal = document.getElementById('quizReminderModal');
    if (modal) modal.style.display = 'none';
    pendingExitStep = null;
    switchWorkshopMode('B');
    switchTier(1);
}

function proceedExitSession() {
    const modal = document.getElementById('quizReminderModal');
    if (modal) modal.style.display = 'none';
    if (pendingExitStep) {
        goToStep(pendingExitStep);
        pendingExitStep = null;
    }
}

// Basculer entre Mode A (Document Officiel & Chat IA) et Mode B (Exercices par Paliers)
let currentWorkshopTab = 'cours';
let currentWorkshopMode = 'cours';

function switchWorkshopTab(tab) {
    currentWorkshopTab = tab;
    currentWorkshopMode = tab;
    const btnCours = document.getElementById('tabBtnCours');
    const btnExercices = document.getElementById('tabBtnExercices');
    const btnAnnale = document.getElementById('tabBtnAnnale');

    const viewCours = document.getElementById('workshopModeCours');
    const viewExercices = document.getElementById('workshopModeExercices');
    const viewAnnale = document.getElementById('workshopModeAnnale');

    if (btnCours) btnCours.classList.toggle('active', tab === 'cours');
    if (btnExercices) btnExercices.classList.toggle('active', tab === 'exercices');
    if (btnAnnale) btnAnnale.classList.toggle('active', tab === 'annale');

    if (viewCours) viewCours.style.display = (tab === 'cours') ? 'block' : 'none';
    if (viewExercices) viewExercices.style.display = (tab === 'exercices') ? 'block' : 'none';
    if (viewAnnale) viewAnnale.style.display = (tab === 'annale') ? 'flex' : 'none';

    if (tab === 'exercices') {
        startQcmCountdown();
    } else {
        clearInterval(qcmTimerInterval);
    }

    setTimeout(renderKaTeX, 60);
}

function switchWorkshopMode(mode) {
    if (mode === 'A') switchWorkshopTab('annale');
    else if (mode === 'B') switchWorkshopTab('exercices');
    else switchWorkshopTab(mode);
}

let isWorkshopAiVisible = true;
function toggleWorkshopAiCol() {
    isWorkshopAiVisible = !isWorkshopAiVisible;
    const grid = document.getElementById('workshopSplitGrid');
    const aiCol = document.getElementById('workshopAiColumn');
    const btn = document.getElementById('btnToggleWorkshopAi');
    const label = document.getElementById('workshopAiToggleLabel');

    if (isWorkshopAiVisible) {
        const w = window.mkGetSavedAiWidth ? window.mkGetSavedAiWidth(390) : 390;
        if (grid) grid.style.gridTemplateColumns = `1fr ${w}px`;
        if (aiCol) aiCol.style.display = 'flex';
        if (btn) btn.classList.add('active-ai');
        if (label) label.textContent = 'Tuteur IA Actif';
    } else {
        if (grid) grid.style.gridTemplateColumns = '1fr 0px';
        if (aiCol) aiCol.style.display = 'none';
        if (btn) btn.classList.remove('active-ai');
        if (label) label.textContent = 'Afficher Tuteur IA';
    }
}

function setWorkshopComfort(theme) {
    const view3 = document.getElementById('stepView3');
    if (view3) {
        view3.classList.remove('theme-day', 'theme-sepia', 'theme-night');
        view3.classList.add('theme-' + (theme || 'day'));
    }
    ['day', 'sepia', 'night'].forEach(t => {
        const b = document.getElementById('wComfortBtn_' + t);
        if (b) b.classList.toggle('active', t === theme);
    });
    try { localStorage.setItem('mk_workshop_comfort_theme', theme || 'day'); } catch(e) {}
}

function toggleWorkshopFullscreen() {
    const view3 = document.getElementById('stepView3') || document.documentElement;
    if (!document.fullscreenElement) {
        if (view3.requestFullscreen) view3.requestFullscreen().catch(() => {});
    } else {
        if (document.exitFullscreen) document.exitFullscreen().catch(() => {});
    }
}

function toggleWorkshopMode() {
    if (currentWorkshopTab === 'cours') {
        switchWorkshopTab('exercices');
    } else if (currentWorkshopTab === 'exercices') {
        switchWorkshopTab('annale');
    } else {
        switchWorkshopTab('cours');
    }
}

function askTutorAboutTheorem(title, latex) {
    const input = document.getElementById('chatInput');
    if (!input) return;
    input.value = "Peux-tu m'expliquer le théorème « " + title + " » et me montrer concrètement comment l'appliquer sur une question d'examen ?";
    submitChatMessage();
}

function getActiveScreenContext() {
    if (currentWorkshopTab === 'cours') {
        const coursEl = document.getElementById('workshopModeCours');
        return coursEl ? coursEl.innerText.substring(0, 1800) : "Leçon magistrale en cours de lecture.";
    } else if (currentWorkshopTab === 'exercices') {
        let activeTierEl = document.getElementById('tierContent1');
        let draft = '';
        const t2 = document.getElementById('tierContent2');
        const t3 = document.getElementById('tierContent3');
        if (t2 && t2.style.display !== 'none') {
            activeTierEl = t2;
            const inp2 = document.getElementById('tier2StudentInput');
            if (inp2 && inp2.value.trim()) draft = "\nBrouillon actuel de l'élève (Palier 2) : " + inp2.value.trim();
        } else if (t3 && t3.style.display !== 'none') {
            activeTierEl = t3;
            const inp3 = document.getElementById('tier3StudentInput');
            if (inp3 && inp3.value.trim()) draft = "\nBrouillon actuel de l'élève (Palier 3) : " + inp3.value.trim();
        }
        return (activeTierEl ? activeTierEl.innerText.substring(0, 1500) : "Exercices d'entraînement par paliers.") + draft;
    } else {
        return "Épreuve officielle d'annale du BAC national ouverte dans le lecteur PDF.";
    }
}
// Variables de progression stricte par palier
let completedTier1 = false;
let completedTier2 = false;
let currentActiveTier = 1;
let selectedCopyFile = null;
let selectedCopyFileTier2 = null;
let selectedCopyFileTier3 = null;

// Navigation séquentielle verrouillée entre les 3 Paliers
function navigateToTier(tier) {
    if (tier === 2 && !completedTier1) {
        showChatToast("Palier 2 verrouillé : Valide d'abord le Quiz Réflexes (Palier 1) !", true);
        return;
    }
    if (tier === 3 && !completedTier2) {
        showChatToast("Palier 3 verrouillé : Valide d'abord l'application méthodologique (Palier 2) !", true);
        return;
    }

    currentActiveTier = tier;
    for (let t = 1; t <= 3; t++) {
        const step = document.getElementById('stepIndicator' + t);
        const content = document.getElementById('tierContent' + t);
        if (step) {
            step.classList.toggle('active', t === tier);
        }
        if (content) {
            content.style.display = (t === tier) ? 'block' : 'none';
        }
    }

    if (tier === 1) {
        startQcmCountdown();
    } else {
        clearInterval(qcmTimerInterval);
    }

    setTimeout(renderKaTeX, 60);
}

// Rétrocompatibilité
function switchTier(tier) {
    navigateToTier(tier);
}

// Chronomètre MonKayier Branded (60s)
function startQcmCountdown() {
    clearInterval(qcmTimerInterval);
    qcmTimeLeft = 60;
    const cdEl = document.getElementById('qcmCountdown');
    if (cdEl) {
        cdEl.innerText = '60s';
        cdEl.style.color = '#D4F25C';
    }

    qcmTimerInterval = setInterval(() => {
        qcmTimeLeft--;
        if (cdEl) {
            cdEl.innerText = (qcmTimeLeft < 10 ? '0' : '') + qcmTimeLeft + 's';
            if (qcmTimeLeft <= 10) {
                cdEl.style.color = '#EF4444';
            } else if (qcmTimeLeft <= 25) {
                cdEl.style.color = '#F59E0B';
            } else {
                cdEl.style.color = '#D4F25C';
            }
        }
        if (qcmTimeLeft <= 0) {
            clearInterval(qcmTimerInterval);
            if (cdEl) {
                cdEl.innerText = '00s';
                cdEl.style.color = '#EF4444';
            }
        }
    }, 1000);
}

// Gestion de la photo / scan de copie pour le Palier 2 et le Palier 3
function handleCopyFileSelected(input, tier = 3) {
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];
    if (tier === 2) {
        selectedCopyFileTier2 = file;
    } else {
        selectedCopyFileTier3 = file;
        selectedCopyFile = file;
    }

    const t = (tier === 2) ? 'tier2' : 'tier3';
    const previewBox = document.getElementById(t + 'CopyPreviewBox');
    const previewImg = document.getElementById(t + 'CopyPreviewImg');
    const pdfIcon = document.getElementById(t + 'PdfIcon');
    const nameEl = document.getElementById(t + 'CopyFileName');
    const sizeEl = document.getElementById(t + 'CopyFileSize');

    if (nameEl) nameEl.textContent = file.name;
    const sizeStr = file.size > 1048576 
        ? (file.size / 1048576).toFixed(1) + ' Mo' 
        : Math.round(file.size / 1024) + ' Ko';
    if (sizeEl) sizeEl.textContent = sizeStr + ' · Prête pour analyse IA';

    if (previewBox) previewBox.style.display = 'flex';

    if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            if (previewImg) {
                previewImg.src = e.target.result;
                previewImg.style.display = 'block';
            }
            if (pdfIcon) pdfIcon.style.display = 'none';
        };
        reader.readAsDataURL(file);
    } else {
        if (previewImg) previewImg.style.display = 'none';
        if (pdfIcon) pdfIcon.style.display = 'flex';
    }
}

function updateDynamicMasteryUI(subjectId, newPercent) {
    if (!newPercent) return;
    const sid = subjectId || currentSubjectId;
    if (!sid) return;
    const bar = document.getElementById('masteryBar_' + sid);
    const txt = document.getElementById('masteryText_' + sid);
    if (bar) {
        bar.style.width = newPercent + '%';
    }
    if (txt) {
        txt.textContent = newPercent + '%';
    }
}

// Soumission du Palier 1 (Quiz Flash QCM)
function submitTier1() {
    clearInterval(qcmTimerInterval);

    const answers = [];
    for (let i = 0; i < 3; i++) {
        const selected = document.querySelector(`input[name="qcm_${i}"]:checked`);
        answers.push(selected ? parseInt(selected.value) : -1);
    }

    const btn = document.getElementById('btnSubmitTier1');
    btn.disabled = true;
    btn.innerText = 'Évaluation en cours…';

    fetch('{{ route("student.chat.evaluate") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            course_id: currentCourseId,
            tier: 1,
            answers: answers
        })
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        hasCompletedTier1 = true;
        completedTier1 = true;

        btn.innerText = 'Quiz Validé ✓';
        btn.style.background = '#059669';
        btn.style.color = '#fff';

        // Mise à jour visuelle du Stepper
        const step1 = document.getElementById('stepIndicator1');
        if (step1) {
            step1.classList.add('completed');
            const c1 = document.getElementById('circleTier1');
            if (c1) c1.innerHTML = '✓';
        }
        const conn1 = document.getElementById('connector1_2');
        if (conn1) conn1.classList.add('active');

        // Déverrouillage du Palier 2
        const step2 = document.getElementById('stepIndicator2');
        if (step2) {
            step2.classList.remove('locked');
            step2.classList.add('unlocked');
            const c2 = document.getElementById('circleTier2');
            if (c2) c2.innerHTML = '2';
        }

        const btnNext = document.getElementById('btnNextTier2');
        if (btnNext) btnNext.style.display = 'inline-block';

        if (data.updated_mastery) {
            updateDynamicMasteryUI(data.subject_id, data.updated_mastery);
        }

        const fb = document.getElementById('tier1ResultFeedback');
        if (fb) {
            fb.innerHTML = `<b>Score : ${data.correct_count}/${data.total_questions} (${data.score_percent}%)</b> · ${data.feedback}`;
        }

        if (data.details) {
            data.details.forEach((d, idx) => {
                const expl = document.getElementById('qcmExpl_' + idx);
                if (expl) {
                    expl.style.display = 'block';
                    if (d.is_correct) {
                        expl.style.background = '#DCFCE7';
                        expl.style.color = '#14532D';
                        expl.innerHTML = `Exact. ${d.explanation}`;
                    } else {
                        expl.style.background = '#FEE2E2';
                        expl.style.color = '#991B1B';
                        expl.innerHTML = `Réponse incorrecte. Explication officielle : ${d.explanation}`;
                    }
                }
            });
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerText = 'Valider le Quiz Réflexes';
    });
}

function showChatToast(msg, isError = false) {
    let toast = document.getElementById('mkChatToast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'mkChatToast';
        toast.style.cssText = 'position:fixed;top:20px;left:50%;transform:translateX(-50%);background:#0E1022;color:#F8FAFC;padding:12px 20px;border-radius:12px;font-size:13px;font-weight:600;box-shadow:0 10px 25px -5px rgba(0,0,0,0.3);z-index:99999;transition:all 0.3s cubic-bezier(0.16, 1, 0.3, 1);opacity:0;pointer-events:none;display:flex;align-items:center;gap:10px;border:1px solid rgba(255,255,255,0.1);max-width:90%;';
        toast.innerHTML = `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="${isError ? '#EF4444' : '#F59E0B'}" stroke-width="2.2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg><span id="mkChatToastText"></span>`;
        document.body.appendChild(toast);
    }
    const textEl = document.getElementById('mkChatToastText');
    if (textEl) textEl.textContent = msg;
    toast.style.opacity = '1';
    toast.style.transform = 'translateX(-50%) translateY(0)';
    if (window._mkChatToastTimer) clearTimeout(window._mkChatToastTimer);
    window._mkChatToastTimer = setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(-50%) translateY(-10px)';
    }, 3800);
}

// Soumission du Palier 2 (Application Méthodologique & Déverrouillage Palier 3)
function submitTier2() {
    const text = (document.getElementById('tier2StudentInput') ? document.getElementById('tier2StudentInput').value : '').trim();
    if (!selectedCopyFileTier2 && (!text || text.length < 8)) {
        showChatToast("Tu dois d'abord prendre en photo ta copie d'exercice ou saisir ta démarche.", true);
        return;
    }

    const btn = document.getElementById('btnSubmitTier2');
    btn.disabled = true;
    btn.innerText = 'Analyse de ta copie manuscrite par le Tuteur IA…';

    const formData = new FormData();
    formData.append('course_id', currentCourseId);
    formData.append('tier', 2);
    if (selectedCopyFileTier2) {
        formData.append('copy_file', selectedCopyFileTier2);
    }
    if (text) {
        formData.append('student_response', text);
    }

    fetch('{{ route("student.chat.evaluate") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        if (data.success) {
            completedTier2 = true;
            btn.innerText = 'Copie Validée ✓';
            btn.style.background = '#059669';
            btn.style.color = '#fff';

            // Mise à jour visuelle du Stepper
            const step2 = document.getElementById('stepIndicator2');
            if (step2) {
                step2.classList.add('completed');
                const c2 = document.getElementById('circleTier2');
                if (c2) c2.innerHTML = '✓';
            }
            const conn2 = document.getElementById('connector2_3');
            if (conn2) conn2.classList.add('active');

            // Déverrouillage du Palier 3
            const step3 = document.getElementById('stepIndicator3');
            if (step3) {
                step3.classList.remove('locked');
                step3.classList.add('unlocked');
                const c3 = document.getElementById('circleTier3');
                if (c3) c3.innerHTML = '3';
            }

            const btnNext = document.getElementById('btnNextTier3');
            if (btnNext) btnNext.style.display = 'inline-block';

            if (data.updated_mastery) {
                updateDynamicMasteryUI(data.subject_id, data.updated_mastery);
            }

            const unlBox = document.getElementById('tier2UnlockedBox');
            if (unlBox) unlBox.style.display = 'block';
            const solEl = document.getElementById('tier2SolutionContent');
            if (solEl) solEl.innerHTML = data.unlocked_solution;
            const barEl = document.getElementById('tier2BaremeTag');
            if (barEl) barEl.innerText = data.bareme;

            const fbBox = document.getElementById('tier2FeedbackBox');
            const fbText = document.getElementById('tier2FeedbackText');
            if (fbBox && fbText) {
                if (data.feedback_html) {
                    fbText.innerHTML = data.feedback_html;
                } else if (data.feedback) {
                    fbText.innerHTML = window.mkFormatAiHtml ? window.mkFormatAiHtml(data.feedback) : data.feedback;
                }
                fbBox.style.display = 'block';
            }
            setTimeout(renderKaTeX, 60);
        } else {
            showChatToast(data.error || "Erreur de validation", true);
            btn.innerText = 'Soumettre ma copie au Tuteur IA';
        }
    })
    .catch(() => {
        btn.disabled = false;
        showChatToast("Erreur lors de l'analyse de ta copie.", true);
        btn.innerText = 'Soumettre ma copie au Tuteur IA';
    });
}

// Soumission du Palier 3 (Copie Manuscrite Photographiée / Scannée)
function submitTier3() {
    const copyFile = selectedCopyFileTier3 || selectedCopyFile;
    const notes = document.getElementById('tier3StudentNotes');
    const noteText = notes ? notes.value.trim() : '';

    if (!copyFile && (!noteText || noteText.length < 8)) {
        showChatToast("Tu dois d'abord prendre en photo ou déposer ta copie manuscrite traitée sur cahier.", true);
        return;
    }

    const btn = document.getElementById('btnSubmitTier3');
    btn.disabled = true;
    btn.innerText = 'Évaluation de ta copie selon les critères officiels MEPST…';

    const formData = new FormData();
    formData.append('course_id', currentCourseId);
    formData.append('tier', 3);
    if (copyFile) {
        formData.append('copy_file', copyFile);
    }
    if (noteText) {
        formData.append('student_response', noteText);
    }

    fetch('{{ route("student.chat.evaluate") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        if (data.success) {
            btn.innerText = 'Copie Validée · Barème Déverrouillé ✓';
            btn.style.background = '#10B981';
            btn.style.color = '#fff';

            const step3 = document.getElementById('stepIndicator3');
            if (step3) {
                step3.classList.add('completed');
                const c3 = document.getElementById('circleTier3');
                if (c3) c3.innerHTML = '✓';
            }

            if (data.updated_mastery) {
                updateDynamicMasteryUI(data.subject_id, data.updated_mastery);
            }

            const fbBox = document.getElementById('tier3FeedbackBox');
            const fbText = document.getElementById('tier3FeedbackText');
            if (fbBox && fbText) {
                if (data.feedback_html) {
                    fbText.innerHTML = data.feedback_html;
                } else if (data.feedback) {
                    fbText.innerHTML = window.mkFormatAiHtml ? window.mkFormatAiHtml(data.feedback) : data.feedback;
                }
                fbBox.style.display = 'block';
            }

            const unlBox = document.getElementById('tier3UnlockedBox');
            if (unlBox) unlBox.style.display = 'block';
            const solEl = document.getElementById('tier3SolutionContent');
            if (solEl) solEl.innerHTML = data.unlocked_solution;
            setTimeout(renderKaTeX, 60);
        } else {
            showChatToast(data.error || "Erreur lors de l'envoi de la copie", true);
            btn.innerText = 'Soumettre ma copie pour évaluation officielle MEPST →';
        }
    })
    .catch(() => {
        btn.disabled = false;
        showChatToast("Une erreur est survenue lors de l'envoi de la copie.", true);
        btn.innerText = 'Soumettre ma copie pour évaluation officielle MEPST →';
    });
}

// =========================================================
// MODE APPEL VOCAL CONTINU MAINS-LIBRES (STYLE CHATGPT VOICE)
// & SIMULATION D'INTERROGATION ORALE DU BAC
// =========================================================
let isLiveVoiceCallActive = false;
let currentInteractionMode = 'standard';
let liveVoiceSilenceTimer = null;
let liveVoiceAccumulatedText = '';

function setLiveVoiceDockState(state, message) {
    const dock = document.getElementById('liveVoiceCallDock');
    const badge = document.getElementById('liveVoiceStateBadge');
    const transcriptEl = document.getElementById('liveVoiceTranscript');
    if (!dock) return;

    dock.setAttribute('data-state', state);
    if (badge) {
        if (state === 'listening') badge.innerText = 'Micro ouvert · Parle librement';
        else if (state === 'thinking') badge.innerText = 'Réflexion du Tuteur IA...';
        else if (state === 'speaking') badge.innerText = 'Le Tuteur te parle (Clique pour interrompre)';
        else badge.innerText = 'Appel Vocal en pause';
    }
    if (transcriptEl && message) {
        transcriptEl.innerText = message;
    }
}

function toggleLiveVoiceCall(forceState) {
    const dock = document.getElementById('liveVoiceCallDock');
    const btn = document.getElementById('btnLiveVoiceCall');
    const label = document.getElementById('liveVoiceBtnLabel');

    const nextState = (typeof forceState === 'boolean') ? forceState : !isLiveVoiceCallActive;
    isLiveVoiceCallActive = nextState;

    if (!isLiveVoiceCallActive) {
        currentInteractionMode = 'standard';
        if (liveVoiceSilenceTimer) {
            clearTimeout(liveVoiceSilenceTimer);
            liveVoiceSilenceTimer = null;
        }
        if (recognition) {
            try { recognition.stop(); } catch (e) {}
        }
        isRecordingVoice = false;
        if (window.mkStopSpeech) window.mkStopSpeech();
        if (dock) dock.classList.remove('is-active');
        if (btn) {
            btn.style.background = '#0E1022';
            btn.style.color = '#D4F25C';
        }
        if (label) label.innerText = 'Appel Vocal';
        return;
    }

    currentInteractionMode = 'voice_call';
    if (dock) dock.classList.add('is-active');
    if (btn) {
        btn.style.background = '#EF4444';
        btn.style.color = '#FFFFFF';
    }
    if (label) label.innerText = 'En Appel...';

    startLiveVoiceListening();
}

function onLiveVoiceOrbClick() {
    if (!isLiveVoiceCallActive) {
        toggleLiveVoiceCall(true);
        return;
    }
    const dock = document.getElementById('liveVoiceCallDock');
    const state = dock ? dock.getAttribute('data-state') : 'idle';

    // Si le Tuteur IA est en train de parler, un clic l'interrompt et redonne immédiatement la parole à l'élève ("Barge-in")
    if (state === 'speaking') {
        if (window.mkStopSpeech) window.mkStopSpeech();
        startLiveVoiceListening();
    } else if (state === 'listening') {
        if (liveVoiceAccumulatedText.trim().length > 2) {
            finalizeLiveVoiceTurn();
        } else {
            if (recognition) { try { recognition.stop(); } catch (e) {} }
            setLiveVoiceDockState('idle', 'Micro en pause. Clique sur l\'orbe pour reprendre la parole.');
        }
    } else {
        startLiveVoiceListening();
    }
}

function finalizeLiveVoiceTurn() {
    if (liveVoiceSilenceTimer) {
        clearTimeout(liveVoiceSilenceTimer);
        liveVoiceSilenceTimer = null;
    }
    const spoken = liveVoiceAccumulatedText.trim();
    liveVoiceAccumulatedText = '';
    if (recognition) {
        try { recognition.stop(); } catch (e) {}
    }
    isRecordingVoice = false;

    if (spoken.length > 1) {
        const input = document.getElementById('chatInput');
        if (input) input.value = spoken;
        setLiveVoiceDockState('thinking', '« ' + spoken + ' »');
        submitChatMessage('voice_call');
    } else if (isLiveVoiceCallActive) {
        startLiveVoiceListening();
    }
}

function startLiveVoiceListening() {
    if (!isLiveVoiceCallActive) return;

    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    if (!SpeechRecognition) {
        setLiveVoiceDockState('idle', 'Utilise Chrome ou Edge pour la reconnaissance vocale continue, ou écris ci-dessous.');
        return;
    }

    if (window.mkStopSpeech) window.mkStopSpeech();
    if (recognition) {
        try { recognition.stop(); } catch (e) {}
    }

    liveVoiceAccumulatedText = '';
    recognition = new SpeechRecognition();
    recognition.lang = 'fr-FR';
    recognition.continuous = true;
    recognition.interimResults = true;

    recognition.onstart = function() {
        isRecordingVoice = true;
        setLiveVoiceDockState('listening', 'Je t\'écoute... Parle naturellement (envoi automatique dès que tu fais une pause).');
    };

    recognition.onresult = function(event) {
        let fullTranscript = '';
        for (let i = 0; i < event.results.length; ++i) {
            fullTranscript += event.results[i][0].transcript;
        }
        liveVoiceAccumulatedText = fullTranscript.trim();
        if (liveVoiceAccumulatedText) {
            setLiveVoiceDockState('listening', '« ' + liveVoiceAccumulatedText + ' »');
            const input = document.getElementById('chatInput');
            if (input) {
                input.value = liveVoiceAccumulatedText;
                if (window.mkAutoResizeTextarea) window.mkAutoResizeTextarea(input);
            }

            // Détection automatique de fin de phrase après 0,70 seconde de silence (rythme d'une vraie conversation vivante)
            if (liveVoiceSilenceTimer) clearTimeout(liveVoiceSilenceTimer);
            liveVoiceSilenceTimer = setTimeout(() => {
                if (isLiveVoiceCallActive && liveVoiceAccumulatedText.length > 2) {
                    finalizeLiveVoiceTurn();
                }
            }, 700);
        }
    };

    recognition.onerror = function() {
        isRecordingVoice = false;
    };

    recognition.onend = function() {
        isRecordingVoice = false;
        if (isLiveVoiceCallActive && liveVoiceAccumulatedText.trim().length > 2) {
            finalizeLiveVoiceTurn();
        }
    };

    try {
        recognition.start();
    } catch (e) {}
}

// Dictée Vocale Ponctuelle depuis le bouton micro de la barre de saisie
function toggleSpeechDictation() {
    const micBtn = document.getElementById('micInputBtn');
    const input = document.getElementById('chatInput');
    const typing = document.getElementById('typingIndicator');
    const typingText = document.getElementById('typingIndicatorText');

    if (!('webkitSpeechRecognition' in window || 'SpeechRecognition' in window)) {
        showChatToast("La reconnaissance vocale n'est pas supportée par ce navigateur. Tu peux taper directement ta question.", false);
        return;
    }

    if (isRecordingVoice) {
        if (recognition) recognition.stop();
        isRecordingVoice = false;
        if (micBtn) micBtn.classList.remove('mic-recording-pulse');
        if (typing) typing.style.display = 'none';
        return;
    }

    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    recognition = new SpeechRecognition();
    recognition.lang = 'fr-FR';
    recognition.continuous = false;
    recognition.interimResults = true;

    recognition.onstart = function() {
        isRecordingVoice = true;
        if (micBtn) micBtn.classList.add('mic-recording-pulse');
        if (typing) {
            typing.style.display = 'flex';
            if (typingText) typingText.innerText = "À l'écoute de ta question…";
        }
    };

    recognition.onresult = function(event) {
        let transcript = '';
        for (let i = event.resultIndex; i < event.results.length; ++i) {
            transcript += event.results[i][0].transcript;
        }
        if (input) {
            input.value = transcript;
            if (window.mkAutoResizeTextarea) window.mkAutoResizeTextarea(input);
        }
    };

    recognition.onerror = function() {
        isRecordingVoice = false;
        if (micBtn) micBtn.classList.remove('mic-recording-pulse');
        if (typing) typing.style.display = 'none';
    };

    recognition.onend = function() {
        isRecordingVoice = false;
        if (micBtn) micBtn.classList.remove('mic-recording-pulse');
        if (typing) typing.style.display = 'none';

        if (input && input.value.trim().length > 2) {
            submitChatMessage();
        }
    };

    recognition.start();
}

// Mode Simulation Oral du BAC (Le Tuteur IA interroge l'élève et note ses réponses sur 10)
function startOralBacSimulation() {
    currentInteractionMode = 'oral_exam';
    const btn = document.getElementById('btnOralExamMode');
    if (btn) {
        btn.style.background = '#4338CA';
        btn.style.color = '#FFFFFF';
    }
    const input = document.getElementById('chatInput');
    if (input) {
        input.value = "Interroge-moi comme à l'oral du BAC sur ce chapitre : pose-moi une première question précise et évalue ma future réponse sur 10.";
    }
    submitChatMessage('oral_exam');
}

// Réinitialiser l'historique de discussion du chapitre ("Nouvelle discussion")
function clearChapterChatHistory() {
    if (window.mkStopSpeech) window.mkStopSpeech();
    fetch('{{ route("student.chat.clear") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ course_id: currentCourseId })
    }).then(() => {
        const feed = document.getElementById('chatFeed');
        if (!feed) return;
        const firstWelcome = feed.querySelector('.chat-msg.assistant');
        feed.innerHTML = '';
        if (firstWelcome) feed.appendChild(firstWelcome);
        currentInteractionMode = isLiveVoiceCallActive ? 'voice_call' : 'standard';
    }).catch(() => {});
}

// Synthèse Vocale Neurale Humaine (Lecture audio de la réponse de l'IA)
function speakText(text, btnEl, onDoneCallback) {
    const sel = document.getElementById('tutorVoiceSelect');
    const voice = sel ? sel.value : (localStorage.getItem('mk_tutor_voice') || 'kodjo');

    // En appel vocal en direct, priorité absolue à la réactivité humaine (démarrage instantané en ~30ms via la synthèse locale du navigateur)
    if (isLiveVoiceCallActive && window.mkFallbackBrowserNeuralSpeech && ('speechSynthesis' in window)) {
        window.mkFallbackBrowserNeuralSpeech(text, voice, onDoneCallback, window.mkSpeechSessionId, btnEl);
        return;
    }

    if (window.mkSpeakHuman) {
        window.mkSpeakHuman(text, btnEl, voice, onDoneCallback);
    } else if (typeof onDoneCallback === 'function') {
        onDoneCallback();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const sel = document.getElementById('tutorVoiceSelect');
    if (sel && localStorage.getItem('mk_tutor_voice')) {
        sel.value = localStorage.getItem('mk_tutor_voice');
    }
    const feed = document.getElementById('chatFeed');
    if (feed) {
        feed.scrollTop = feed.scrollHeight;
    }
});

// Chat Socratique IA
function sendSuggestion(text) {
    const inp = document.getElementById('chatInput');
    if (inp) inp.value = text;
    submitChatMessage();
}

function submitChatMessage(modeOverride) {
    const input = document.getElementById('chatInput');
    const msg = (input.value || '').trim();
    if (!msg) return;

    const activeMode = modeOverride || (isLiveVoiceCallActive ? 'voice_call' : currentInteractionMode);

    input.value = '';
    if (window.mkAutoResizeTextarea) window.mkAutoResizeTextarea(input);
    // Replier automatiquement les suggestions pendant la lecture de la réponse pour libérer tout l'espace vertical
    if (window.mkToggleChatDrawer) window.mkToggleChatDrawer('suggestions', 'chat', false);
    const feed = document.getElementById('chatFeed');
    const typing = document.getElementById('typingIndicator');
    const typingText = document.getElementById('typingIndicatorText');

    const userBubble = document.createElement('div');
    userBubble.className = 'chat-msg user';
    userBubble.style.cssText = 'align-self:flex-end;background:#0E1022;color:#fff;padding:12px 16px;border-radius:18px 18px 4px 18px;max-width:85%;line-height:1.45;font-size:13px;word-break:break-word';
    userBubble.innerText = msg;
    feed.appendChild(userBubble);
    feed.scrollTop = feed.scrollHeight;

    if (typingText) {
        typingText.innerText = (activeMode === 'voice_call')
            ? "Le Tuteur IA prépare sa réponse vocale…"
            : "MonKayier consulte le cours et prépare ton explication…";
    }
    typing.style.display = 'flex';
    if (isLiveVoiceCallActive) {
        setLiveVoiceDockState('thinking', 'Préparation de la réponse vocale...');
    }

    fetch('{{ route("student.chat.message") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            course_id: currentCourseId,
            message: msg,
            active_tab: currentWorkshopTab,
            interaction_mode: activeMode,
            context_text: getActiveScreenContext()
        })
    })
    .then(res => res.json())
    .then(data => {
        typing.style.display = 'none';
        if (data.success && data.assistant_response) {
            const resp = data.assistant_response;

            const assistantBlock = document.createElement('div');
            assistantBlock.className = 'chat-msg assistant';
            assistantBlock.style.cssText = 'display:flex;flex-direction:column;gap:6px;max-width:95%';

            const formattedSource = window.mkFormatAiHtml ? window.mkFormatAiHtml(resp.source_text) : resp.source_text;
            const formattedComp = resp.complementary_text ? (window.mkFormatAiHtml ? window.mkFormatAiHtml(resp.complementary_text) : resp.complementary_text) : '';
            const rawSpeech = (resp.spoken_text || resp.source_text) + (resp.complementary_text && activeMode !== 'voice_call' ? '. ' + resp.complementary_text : '');

            assistantBlock.innerHTML = `
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <span class="tg" style="align-self:flex-start;font-size:10px;background:#F1F5F9;color:#475569;">
                        ${resp.citation || 'Programme Officiel Terminale D'}
                    </span>
                    <button type="button" class="mk-tts-btn" title="Écouter avec la voix neurale humaine">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon><path d="M15.54 8.46a5 5 0 0 1 0 7.07"></path></svg>
                        <span>Écouter</span>
                    </button>
                </div>
                <div style="background:var(--immersion-card, #F8FAFC);border:1.5px solid var(--immersion-card-border, #E2E8F0);padding:12px 14px;border-radius:4px 14px 14px 14px;line-height:1.55;font-size:13px;color:var(--immersion-text, #0E1022)">
                    ${formattedSource}
                </div>
                ${formattedComp ? `<div style="background:#FFFBEB;border:1px solid #FDE68A;padding:10px 12px;border-radius:10px;font-size:12.5px;line-height:1.45;color:#92400E">
                    <b>Méthode & Barème :</b> ${formattedComp}
                </div>` : ''}
            `;

            // Retour de l'élève sur la réponse (sert à ajuster la longueur des réponses du tuteur)
            const fbRow = document.createElement('div');
            fbRow.style.cssText = 'display:flex;gap:6px;flex-wrap:wrap;align-items:center;font-size:11px;color:#64748B';
            fbRow.innerHTML = '<span>Cette réponse était :</span>';
            [['good', 'Utile'], ['too_long', 'Trop longue'], ['too_short', 'Trop courte'], ['unclear', 'Pas claire']].forEach(([rating, label]) => {
                const b = document.createElement('button');
                b.type = 'button';
                b.textContent = label;
                b.style.cssText = 'border:1px solid #CBD5E1;background:#fff;color:#334155;border-radius:999px;padding:2px 9px;font-size:11px;cursor:pointer';
                b.onclick = () => {
                    fetch('{{ route("student.chat.feedback") }}', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json'},
                        body: JSON.stringify({
                            rating: rating,
                            intent: resp.intent || null,
                            length_profile: resp.length_profile || null,
                            question: msg,
                            answer_excerpt: String(resp.raw_text || resp.source_text || '').replace(/<[^>]+>/g, ' ').substring(0, 600),
                            course_id: currentCourseId ? String(currentCourseId) : null
                        })
                    }).catch(() => {});
                    fbRow.innerHTML = '<span>Merci pour ton retour.</span>';
                };
                fbRow.appendChild(b);
            });
            assistantBlock.appendChild(fbRow);

            const ttsBtn = assistantBlock.querySelector('.mk-tts-btn');
            if (ttsBtn) {
                ttsBtn.dataset.speech = rawSpeech;
                ttsBtn.onclick = function() { speakText(this.dataset.speech, this); };
            }

            feed.appendChild(assistantBlock);
            setTimeout(renderKaTeX, 50);
            feed.scrollTop = feed.scrollHeight;

            // Lecture vocale automatique UNIQUEMENT en mode Appel Vocal Mains-Libres
            if (isLiveVoiceCallActive) {
                setLiveVoiceDockState('speaking', 'Le Tuteur IA te répond à voix haute...');
                speakText(rawSpeech, ttsBtn, function() {
                    if (isLiveVoiceCallActive) {
                        setTimeout(() => {
                            if (isLiveVoiceCallActive) startLiveVoiceListening();
                        }, 350);
                    }
                });
            }

            if (resp.suggestions && resp.suggestions.length > 0) {
                const chipsBox = document.getElementById('chipsContainer');
                if (chipsBox) {
                    chipsBox.innerHTML = `
                        <button type="button" class="mk-sug-pill mk-sug-pill--quiz" onclick="switchWorkshopMode('B')">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            <span>Faire le Quiz Flash</span>
                            <span class="mk-sug-quiz-tag">60s</span>
                        </button>
                    `;
                    resp.suggestions.forEach(s => {
                        const pill = window.mkCreateSuggestionPill
                            ? window.mkCreateSuggestionPill(s, () => sendSuggestion(s))
                            : null;
                        if (pill) chipsBox.appendChild(pill);
                    });
                    const countBadge = document.getElementById('chatSugCountBadge');
                    if (countBadge) countBadge.textContent = String(resp.suggestions.length + 1);
                }
            }
        }
    })
    .catch(() => {
        typing.style.display = 'none';
        if (isLiveVoiceCallActive) {
            setLiveVoiceDockState('idle', 'Erreur réseau temporaire. Clique sur l\'orbe pour réessayer.');
        }
    });
}

function updateUrlParams() {
    const url = new URL(window.location);
    if (currentSubjectId) url.searchParams.set('subject', currentSubjectId);
    if (currentCourseId && currentStep === 3) url.searchParams.set('course', currentCourseId);
    else url.searchParams.delete('course');
    window.history.replaceState({}, '', url);
}

// Raccourcis clavier (Échap pour retour et F pour Plein Écran)
document.addEventListener('keydown', function(e) {
    if (currentStep === 3) {
        if (e.key === 'Escape') {
            handleExitAttempt(2);
        } else if ((e.key === 'f' || e.key === 'F') && !['INPUT', 'TEXTAREA'].includes(document.activeElement?.tagName)) {
            e.preventDefault();
            toggleWorkshopFullscreen();
        }
    }
});

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    if (currentStep === 3) {
        document.body.style.overflow = 'hidden';
    }
    const savedTheme = localStorage.getItem('mk_workshop_comfort_theme') || 'day';
    setWorkshopComfort(savedTheme);
});
</script>

{{-- ========================================================= --}}
{{-- MODALE DE RAPPEL BIENVEILLANTE : VALIDER LE QUIZ AVANT SORTIE --}}
{{-- ========================================================= --}}
<div id="quizReminderModal" style="display: none; position: fixed; inset: 0; z-index: 999999; background: rgba(14, 16, 34, 0.72); backdrop-filter: blur(5px); align-items: center; justify-content: center; padding: 20px;">
    <div style="background: #FFFFFF; border-radius: 22px; border: 1.5px solid #E2E8F0; max-width: 460px; width: 100%; padding: 30px; box-shadow: 0 24px 48px -12px rgba(14, 16, 34, 0.28); text-align: center; animation: dockMenuPop 0.2s cubic-bezier(0.16, 1, 0.3, 1);">
        <div style="width: 56px; height: 56px; border-radius: 50%; background: #EFF6FF; border: 2px solid #BFDBFE; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; color: #2563EB;">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        </div>
        <h3 style="font-size: 20px; font-weight: 850; color: #0E1022; margin: 0 0 8px 0; letter-spacing: -0.01em;">
            Valide tes acquis avant de partir !
        </h3>
        <p style="font-size: 13.5px; color: #64748B; line-height: 1.55; margin: 0 0 24px 0;">
            Tu viens de consulter le cours officiel. Prendre <b>60 secondes chrono</b> pour répondre aux 3 questions réflexes ancre 80% des notions pour l'examen national.
        </p>
        <div style="display: flex; flex-direction: column; gap: 10px;">
            <button type="button" onclick="closeReminderAndStartQuiz()" 
                    style="background: #D4F25C; color: #0E1022; border: 1.5px solid #D4F25C; font-size: 14px; font-weight: 850; padding: 12px 20px; border-radius: 8px; cursor: pointer; box-shadow: none !important; transition: transform 0.15s ease;">
                Faire le Quiz Flash maintenant (60s)
            </button>
            <button type="button" onclick="proceedExitSession()" 
                    style="background: transparent; color: #64748B; border: none; font-size: 12.5px; font-weight: 600; padding: 8px; cursor: pointer;">
                Quitter la séance sans valider
            </button>
        </div>
    </div>
</div>
@endsection
