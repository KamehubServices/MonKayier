@extends('layouts.student')

@section('title', "Parcours d'Études & Cap Examen — MonKayier")

@section('content')
<style>
/* ==========================================================================
   CADRAGE DU TABLEAU DE BORD (PARCOURS) : ZERO ESPACEMENTS & PLEIN ÉCRAN
   Ces règles s'appliquent EXCLUSIVEMENT au Tableau de Bord (Parcours)
   ========================================================================== */
html, body {
    height: 100vh !important;
    max-height: 100vh !important;
    overflow: hidden !important;
    background: #FFFFFF !important;
}
.p {
    height: 100vh !important;
    max-height: 100vh !important;
    min-height: 0 !important;
    overflow: hidden !important;
    background: #FFFFFF !important;
}
.lay {
    padding: 0 !important;
    height: 100vh !important;
    max-height: 100vh !important;
    overflow: hidden !important;
}
.sb {
    margin: 0 !important;
    border-radius: 0 !important;
    height: 100vh !important;
    max-height: 100vh !important;
    border: none !important;
    border-right: 1px solid #E2E8F0 !important;
    box-shadow: none !important;
    padding: 10px 8px !important;
}
.lay .w {
    padding: 0 !important;
    height: 100vh !important;
    max-height: 100vh !important;
    overflow: hidden !important;
    background: #FFFFFF !important;
}
/* ==========================================================================
   DESIGN SYSTEM COCKPIT DOCKÉ (ZÉRO ESPACEMENT, PLEIN ÉCRAN, ULTRA-ÉCONOMIQUE)
   ========================================================================== */

/* 1. Grand Cadre d'En-tête Unifié (Collé, sans espacements extérieurs) */
.top-unified-header-frame {
    background: #FFFFFF;
    border: none;
    border-bottom: 1px solid #E2E8F0;
    border-radius: 0;
    display: grid;
    grid-template-columns: 1.3fr 1fr 1fr;
    box-shadow: none;
    margin: 0;
    padding: 0;
    height: 165px;
    flex: none;
    box-sizing: border-box;
    overflow: hidden;
}

@media (max-width: 1080px) {
    .top-unified-header-frame {
        grid-template-columns: 1fr;
        height: auto;
    }
    .top-header-section {
        border-right: none !important;
        border-bottom: 1px solid #E2E8F0;
    }
    .top-header-section:last-child {
        border-bottom: none;
    }
}

.top-header-section {
    padding: 22px 28px 18px 28px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-sizing: border-box;
    border-right: 1px solid #E2E8F0;
    background: #FFFFFF;
    height: 100%;
}
.top-header-section:last-child {
    border-right: none;
}
.top-header-section:hover {
    background: #FAFCFE;
}

.ths-badge-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 4px;
}

.top-min-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 800;
    padding: 5px 12px;
    border-radius: 6px;
    white-space: nowrap;
    letter-spacing: 0.02em;
}
.badge-class { background: #0E1022; color: #D4F25C; }
.badge-exam { background: #EFF6FF; color: #1E40AF; }
.badge-exam-alert { font-size: 11.5px; font-weight: 750; color: #D97706; }
.badge-mastery { background: #ECFDF5; color: #047857; }
.badge-step-count { font-size: 11.5px; font-weight: 800; color: #0284c7; }
.ths-inst-tag { font-size: 11.5px; font-weight: 750; color: #64748B; }

.ths-profile-mid-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    width: 100%;
}

.ths-student-side {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    min-width: 0;
}

.ths-student-name {
    font-size: 25px;
    font-weight: 850;
    color: #0E1022;
    margin: 0;
    letter-spacing: -0.02em;
    line-height: 1.2;
    white-space: nowrap;
}

.ths-school-clean-side {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    text-align: right;
    gap: 3px;
    flex-shrink: 0;
}

.ths-school-clean-header {
    display: flex;
    align-items: center;
    gap: 6px;
}

.ths-school-name-pure {
    font-size: 14.5px;
    font-weight: 800;
    color: #0E1022;
    letter-spacing: -0.01em;
    white-space: nowrap;
}

.ths-school-geo-pure {
    font-size: 12px;
    font-weight: 600;
    color: #64748B;
    white-space: nowrap;
}

.ths-body-main {
    margin: 2px 0;
}

.ths-kpi-row {
    display: flex;
    align-items: baseline;
    gap: 8px;
}

.top-min-kpi-val {
    font-size: 35px;
    font-weight: 850;
    line-height: 1;
    letter-spacing: -0.03em;
    color: #0E1022;
}

.ths-kpi-label {
    font-size: 13px;
    font-weight: 650;
    color: #64748B;
}

.ths-exam-footer {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #64748B;
}
.ths-exam-footer svg {
    flex-shrink: 0;
}
.ths-exam-footer strong {
    color: #0E1022;
    font-weight: 750;
}

.ths-progress-track {
    height: 7px;
    background: #E2E8F0;
    border-radius: 999px;
    overflow: hidden;
    margin-top: 7px;
}
.ths-progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #10B981 0%, #059669 100%);
    border-radius: 999px;
}

.ths-footer-note {
    border-top: 1px solid #F1F5F9;
    padding-top: 7px;
    font-size: 11.5px;
    color: #64748B;
    line-height: 1.35;
}


/* ==========================================================================
   2. GRAND CADRE DU PARCOURS (COLLÉ, AUCUN ESPACEMENT, AUCUN DÉFILEMENT GLOBAL)
   ========================================================================== */
.main-parcours-cockpit-frame {
    background: #FFFFFF;
    border: none;
    border-radius: 0;
    margin: 0;
    padding: 0;
    flex: 1;
    min-height: 0;
    height: calc(100vh - 165px);
    max-height: calc(100vh - 165px);
    display: grid;
    grid-template-columns: 1fr 345px;
    overflow: hidden;
    box-sizing: border-box;
}

@media (max-width: 1180px) {
    .main-parcours-cockpit-frame {
        grid-template-columns: 1fr 310px;
    }
}
@media (max-width: 980px) {
    .main-parcours-cockpit-frame {
        grid-template-columns: 1fr;
    }
    .cockpit-parcours-pane {
        border-right: none !important;
        border-bottom: 1px solid #E2E8F0;
    }
}

/* VOLET GAUCHE : LE PARCOURS DRAGON */
.cockpit-parcours-pane {
    position: relative;
    background: linear-gradient(180deg, #FFFFFF 0%, #F8FAFD 50%, #EEF2F8 100%);
    border-right: 1px solid #E2E8F0;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: center;
    height: 100%;
    max-height: 100%;
    box-sizing: border-box;
}

/* Navigation intégrée dans le cadre du dragon */
.dragon-floating-nav {
    position: absolute;
    top: 10px;
    right: 14px;
    z-index: 28;
    display: flex;
    align-items: center;
    gap: 4px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border: 1px solid #E2E8F0;
    border-radius: 999px;
    padding: 3px 5px;
    box-shadow: 0 2px 8px rgba(14, 16, 34, 0.06);
}

.nav-arrow-btn {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    border: none;
    background: #F1F5F9;
    color: #0E1022;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.15s ease;
    padding: 0;
}
.nav-arrow-btn:hover {
    background: #0E1022;
    color: #D4F25C;
}

.nav-target-btn {
    padding: 2px 8px;
    border-radius: 999px;
    border: none;
    background: #0E1022;
    color: #D4F25C;
    font-size: 10.5px;
    font-weight: 800;
    cursor: pointer;
    line-height: 1.2;
}

.nav-replay-btn {
    display: inline-flex;
    align-items: center;
    gap: 3px;
    padding: 2px 8px;
    border-radius: 999px;
    border: none;
    background: #F1F5F9;
    color: #475569;
    font-size: 10px;
    font-weight: 700;
    cursor: pointer;
    line-height: 1.2;
}
.nav-replay-btn:hover {
    background: #E2E8F0;
    color: #0E1022;
}

/* Nuages décoratifs bord gauche et droit */
.cloud-boundary-left {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    width: 100px;
    pointer-events: none;
    z-index: 2;
    opacity: 0.45;
}
.cloud-boundary-right {
    position: absolute;
    top: 0;
    bottom: 0;
    right: 0;
    width: 160px;
    pointer-events: none;
    z-index: 2;
    opacity: 0.55;
}
.floating-cloud-puff {
    position: absolute;
    pointer-events: none;
    z-index: 2;
    opacity: 0.40;
    animation: cloudDrift 14s ease-in-out infinite alternate;
}
@keyframes cloudDrift {
    0% { transform: translateY(0px) translateX(0px); }
    100% { transform: translateY(-6px) translateX(10px); }
}

/* Piste de défilement horizontal */
.dragon-scroll-track {
    overflow-x: auto;
    overflow-y: hidden;
    scroll-behavior: smooth;
    padding: 10px 40px 20px;
    cursor: grab;
    user-select: none;
    scrollbar-width: thin;
    scrollbar-color: #CBD5E1 #F1F5F9;
    position: relative;
    z-index: 6;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    box-sizing: border-box;
}
.dragon-scroll-track::-webkit-scrollbar {
    height: 5px;
}
.dragon-scroll-track::-webkit-scrollbar-track {
    background: #F1F5F9;
    border-radius: 999px;
}
.dragon-scroll-track::-webkit-scrollbar-thumb {
    background: #CBD5E1;
    border-radius: 999px;
}
.dragon-scroll-track:active {
    cursor: grabbing;
}

.dragon-path-stage {
    position: relative;
    width: 3500px;
    height: 440px;
    flex: none;
}

/* Bulles des jalons du dragon */
.dragon-node-bubble {
    position: absolute;
    width: 54px;
    height: 54px;
    border-radius: 50%;
    transform: translate(-50%, -50%);
    background: #FFFFFF;
    border: none;
    box-shadow: 0 4px 10px rgba(15, 23, 42, 0.08);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 12;
    transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), background 0.2s ease;
}
.dragon-node-bubble:hover {
    transform: translate(-50%, -50%) scale(1.08);
}
.dragon-node-bubble.completed {
    background: #059669;
    color: #FFFFFF;
}
.dragon-node-bubble.active {
    background: #0E1022;
    color: #D4F25C;
    transform: translate(-50%, -50%) scale(1.12);
    box-shadow: 0 0 0 3px #D4F25C, 0 6px 20px rgba(14, 16, 34, 0.18);
    animation: activeDragonPulse 1.8s infinite alternate;
}
@keyframes activeDragonPulse {
    0% { transform: translate(-50%, -50%) scale(1.10); }
    100% { transform: translate(-50%, -50%) scale(1.15); }
}
.dragon-node-bubble.locked {
    background: #FFFFFF;
    color: #94A3B8;
    cursor: not-allowed;
}
.dragon-node-bubble.boss {
    width: 68px;
    height: 68px;
    background: #B45309;
    color: #FFFFFF;
    box-shadow: 0 5px 16px rgba(180, 83, 9, 0.22);
}

.dragon-node-tag {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    padding: 7px 11px;
    width: 190px;
    box-sizing: border-box;
    z-index: 14;
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
    text-align: center;
}
.dragon-node-tag.tag-top {
    bottom: 66px;
}
.dragon-node-tag.tag-bottom {
    top: 66px;
}

.runner-tilt-wrapper {
    transform-origin: bottom center;
    transition: transform 0.05s linear;
}
.runner-body-bounce {
    position: relative;
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    transform-origin: bottom center;
    will-change: transform;
}
.real-3d-runner-img {
    position: absolute;
    inset: 0;
    margin: auto;
    height: 80px;
    width: auto;
    object-fit: contain;
    filter: drop-shadow(0 4px 6px rgba(0,0,0,0.18));
    transition: opacity 0.12s ease-in-out;
    will-change: opacity, transform;
}
.runner-speech-bubble {
    background: #0E1022;
    color: #FFFFFF;
    font-size: 9px;
    font-weight: 750;
    padding: 2px 7px;
    border-radius: 999px;
    white-space: nowrap;
    border: none;
    margin-bottom: 2px;
    text-align: center;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}

.study-desk-animated {
    animation: deskPopIn 0.45s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    transform-origin: bottom center;
}
@keyframes deskPopIn {
    0% { transform: translate(-50%, -100%) scale(0.7); opacity: 0; }
    100% { transform: translate(-50%, -100%) scale(1); opacity: 1; }
}
.study-desk-pill {
    background: #064E3B;
    color: #D4F25C;
    font-size: 9.5px;
    font-weight: 800;
    padding: 2px 8px;
    border-radius: 999px;
    margin: 0 auto 4px auto;
    text-align: center;
    line-height: 1.2;
    border: none;
    box-shadow: 0 3px 8px rgba(6, 78, 59, 0.22);
    white-space: nowrap;
    position: relative;
    z-index: 35;
    display: inline-block;
    pointer-events: none;
}

.btn-lime-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    padding: 5px 10px;
    border-radius: 7px;
    font-size: 11px;
    font-weight: 850;
    background: #D4F25C;
    color: #0E1022;
    border: none;
    text-decoration: none;
    box-shadow: none !important;
    transition: background 0.15s ease;
    white-space: nowrap;
}
.btn-lime-action:hover {
    background: #cbf046;
    color: #0E1022;
}


/* ==========================================================================
   VOLET DROIT : COURS & PROGRESSION (COMPACT, ÉCONOMIQUE, SCROLL INTERNE)
   ========================================================================== */
.cockpit-courses-pane {
    background: #FFFFFF;
    display: flex;
    flex-direction: column;
    gap: 9px;
    padding: 10px 12px;
    box-sizing: border-box;
    overflow-y: auto;
    overflow-x: hidden;
    height: 100%;
    max-height: 100%;
    scrollbar-width: thin;
    scrollbar-color: #CBD5E1 #F8FAFC;
}
.cockpit-courses-pane::-webkit-scrollbar {
    width: 4px;
}
.cockpit-courses-pane::-webkit-scrollbar-track {
    background: #F8FAFC;
}
.cockpit-courses-pane::-webkit-scrollbar-thumb {
    background: #CBD5E1;
    border-radius: 999px;
}

/* Carte 1 : Séance Clé Prioritaire */
.cockpit-priority-session-card {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    padding: 11px 13px;
    box-sizing: border-box;
    flex: none;
}
.cp-header-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 6px;
}
.cp-priority-badge {
    font-size: 9.5px;
    font-weight: 800;
    background: #0E1022;
    color: #D4F25C;
    padding: 2px 7px;
    border-radius: 5px;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}
.cp-coeff-badge {
    font-size: 10.5px;
    font-weight: 800;
    color: #D97706;
}
.cp-session-title {
    font-size: 13.5px;
    font-weight: 850;
    color: #0E1022;
    margin: 0 0 3px 0;
    letter-spacing: -0.01em;
    line-height: 1.25;
}
.cp-session-sub {
    font-size: 11px;
    color: #64748B;
    margin-bottom: 6px;
}
.cp-session-goal {
    font-size: 10.5px;
    color: #475569;
    line-height: 1.35;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 7px;
    padding: 6px 9px;
    margin: 0 0 8px 0;
}
.btn-continue-revision {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    width: 100%;
    padding: 8px 12px;
    background: #D4F25C;
    color: #0E1022;
    border-radius: 7px;
    font-size: 11.5px;
    font-weight: 850;
    text-decoration: none;
    box-sizing: border-box;
    transition: all 0.15s ease;
    box-shadow: none !important;
}
.btn-continue-revision:hover {
    background: #cbf046;
}

/* Carte 2 : Progression des Cours par Matière */
.cockpit-subjects-progress-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    padding: 10px 12px;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    gap: 8px;
    flex: 1;
}
.cs-header-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 6px;
    border-bottom: 1px solid #F1F5F9;
}
.cs-title {
    font-size: 12px;
    font-weight: 850;
    color: #0E1022;
    letter-spacing: -0.01em;
}
.cs-sub {
    font-size: 10px;
    font-weight: 700;
    color: #64748B;
}

.cockpit-subjects-list {
    display: flex;
    flex-direction: column;
    gap: 7px;
}
.cockpit-subject-item {
    background: #F8FAFC;
    border-radius: 7px;
    padding: 7px 9px;
    display: flex;
    flex-direction: column;
    gap: 4px;
    transition: background 0.15s ease;
}
.cockpit-subject-item:hover {
    background: #F1F5F9;
}
.cs-item-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.cs-item-name {
    font-size: 11.5px;
    font-weight: 800;
    color: #0E1022;
}
.cs-item-val {
    font-size: 11.5px;
    font-weight: 900;
}
.cs-item-coeff {
    font-size: 9.5px;
    color: #64748B;
}
.cs-item-track {
    height: 4px;
    background: #E2E8F0;
    border-radius: 999px;
    overflow: hidden;
}
.cs-item-fill {
    height: 100%;
    border-radius: 999px;
}
.cs-item-bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.cs-status-pill {
    font-size: 9px;
    font-weight: 750;
    padding: 1px 5px;
    border-radius: 4px;
}
.pill-alert { background: #FEF3C7; color: #B45309; }
.pill-ok { background: #ECFDF5; color: #059669; }
.pill-neutral { background: #F1F5F9; color: #64748B; border: 1px solid #CBD5E1; }

.cs-review-link {
    font-size: 10.5px;
    font-weight: 800;
    color: #0E1022;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 2px;
}
.cs-review-link:hover {
    text-decoration: underline;
}

/* ==========================================================================
   MODAL DE DÉBRIEF DU PARCOURS CALIBRÉ (SUITE AU TEST DE NIVEAU GÉNÉRAL)
   ========================================================================== */
.cpm-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(14, 16, 34, 0.65);
    backdrop-filter: blur(4px);
    z-index: 99999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    box-sizing: border-box;
    animation: cpmFadeIn 0.25s ease-out;
}
@keyframes cpmFadeIn {
    from { opacity: 0; transform: scale(0.98); }
    to { opacity: 1; transform: scale(1); }
}
.cpm-modal {
    background: #FFFFFF;
    border-radius: 14px;
    width: 100%;
    max-width: 580px;
    padding: 30px 34px;
    box-sizing: border-box;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.18);
    display: flex;
    flex-direction: column;
    gap: 18px;
}
.cpm-header {
    text-align: center;
}
.cpm-badge {
    display: inline-flex;
    align-items: center;
    background: #0E1022;
    color: #D4F25C;
    font-size: 11px;
    font-weight: 850;
    padding: 4px 12px;
    border-radius: 6px;
    letter-spacing: 0.03em;
    margin-bottom: 8px;
}
.cpm-title {
    font-size: 22px;
    font-weight: 900;
    color: #0E1022;
    margin: 0 0 6px 0;
    letter-spacing: -0.02em;
    line-height: 1.25;
}
.cpm-subtitle {
    font-size: 13px;
    color: #64748B;
    margin: 0;
    line-height: 1.45;
}
.cpm-context-row {
    display: flex;
    gap: 8px;
    justify-content: center;
    flex-wrap: wrap;
}
.cpm-ctx-pill {
    background: #F1F5F9;
    color: #334155;
    border: 1px solid #E2E8F0;
    border-radius: 6px;
    padding: 5px 10px;
    font-size: 11.5px;
    font-weight: 750;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
.cpm-kpis-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
}
.cpm-kpi-card {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 8px;
    padding: 12px 10px;
    text-align: center;
    display: flex;
    flex-direction: column;
    gap: 3px;
}
.cpm-kpi-label {
    font-size: 10.5px;
    font-weight: 750;
    color: #64748B;
    text-transform: uppercase;
    letter-spacing: 0.02em;
}
.cpm-kpi-val {
    font-size: 20px;
    font-weight: 900;
    color: #0E1022;
}
.cpm-kpi-val small {
    font-size: 12px;
    font-weight: 700;
    color: #64748B;
}
.cpm-kpi-sub {
    font-size: 11px;
    font-weight: 700;
    color: #475569;
}
.cpm-steps-summary {
    display: flex;
    flex-direction: column;
    gap: 8px;
    background: #FAFCFE;
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    padding: 12px 14px;
}
.cpm-step-item {
    display: flex;
    align-items: center;
    gap: 10px;
}
.cpm-si-icon {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 900;
    flex-shrink: 0;
}
.step-done .cpm-si-icon {
    background: #ECFDF5;
    color: #059669;
    border: 1.5px solid #A7F3D0;
}
.step-current .cpm-si-icon {
    background: #0E1022;
    color: #D4F25C;
    border: 1.5px solid #0E1022;
}
.step-locked .cpm-si-icon {
    background: #F1F5F9;
    color: #94A3B8;
    border: 1.5px solid #CBD5E1;
}
.cpm-si-text {
    display: flex;
    flex-direction: column;
    font-size: 12px;
}
.cpm-si-text strong {
    font-weight: 800;
    color: #0E1022;
}
.cpm-si-text span {
    font-size: 11px;
    color: #64748B;
}
.cpm-actions {
    display: flex;
    justify-content: center;
}
.btn-start-curriculum {
    width: 100%;
    background: #D4F25C;
    color: #0E1022;
    border: none;
    border-radius: 8px !important;
    box-shadow: none !important;
    padding: 13px 20px;
    font-size: 14px;
    font-weight: 850;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: background 0.15s ease;
}
.btn-start-curriculum:hover {
    background: #cbf046;
}

</style>

{{-- ========================================================================= --}}
{{-- 1. GRAND CADRE D'EN-TÊTE UNIFIÉ (COLLÉ, AUCUN ESPACEMENT, 3 BOXES)         --}}
{{-- ========================================================================= --}}
<div class="top-unified-header-frame">
    
    {{-- BOX 1 : PROFIL ÉLÈVE & ÉTABLISSEMENT --}}
    <div class="top-header-section">
        {{-- NIVEAU 1 : TITRE DE BOX / BADGE DE CLASSE EN HAUT --}}
        <div class="ths-badge-row">
            <span class="top-min-badge badge-class">
                {{ $student->class_name ?? 'Terminale D' }}
            </span>
        </div>

        {{-- NIVEAU 2 : NOM DE L'ÉLÈVE AU CENTRE (BIEN ESPACÉ DU TITRE) & ÉTABLISSEMENT EN FACE --}}
        <div class="ths-body-main">
            <div class="ths-profile-mid-row">
                <div class="ths-student-side">
                    <h1 class="ths-student-name">
                        {{ !empty($student->full_name) ? $student->full_name : 'Élève' }}
                    </h1>
                </div>

                <div class="ths-school-clean-side">
                    <div class="ths-school-clean-header">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#14325c" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        <span class="ths-school-name-pure">{{ $student->school_name ?? 'Lycée de Tokoin' }}</span>
                    </div>
                    <div class="ths-school-geo-pure">{{ ($student->city ?? 'Lomé') . ' · ' . ($student->country ?? 'Togo') }}</div>
                </div>
            </div>
        </div>

        {{-- NIVEAU 3 : PIED DU BOX --}}
        <div class="ths-footer-note">
            Candidat Série Scientifique · Programme BAC 2 Série D
        </div>
    </div>

    {{-- BOX 2 : COMPTE À REBOURS EXAMEN "JOUR J" (ÉPURÉ, INTUITIF, SANS SURCHARGE) --}}
    <div class="top-header-section">
        <div class="ths-badge-row">
            <span class="top-min-badge badge-exam">
                {{ $examCountdown['name'] }}
            </span>
        </div>

        <div class="ths-body-main">
            <div class="ths-kpi-row">
                <span class="top-min-kpi-val">
                    J-{{ $examCountdown['days_left'] }}
                </span>
                <span class="ths-kpi-label">
                    jours avant l'examen
                </span>
            </div>
        </div>

        <div class="ths-footer-note ths-exam-footer">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2.2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            <span>Date de session le <strong>{{ $examCountdown['date_str'] }}</strong></span>
        </div>
    </div>

    {{-- BOX 3 : NIVEAU DE PROGRESSION GLOBALE --}}
    <div class="top-header-section">
        <div class="ths-badge-row">
            <span class="top-min-badge badge-mastery">
                Maîtrise du Programme
            </span>
            <span class="badge-step-count">
                Étape {{ ($activeNode['step'] ?? 5) < 10 ? '0' : '' }}{{ $activeNode['step'] ?? 5 }} / 16
            </span>
        </div>

        <div class="ths-body-main">
            <div class="ths-kpi-row">
                <span class="top-min-kpi-val">
                    {{ $overallMasteryPercent }}%
                </span>
                <span class="ths-kpi-label">
                    programme maîtrisé
                </span>
            </div>
            <div class="ths-progress-track">
                <div class="ths-progress-bar" style="width: {{ $overallMasteryPercent }}%;"></div>
            </div>
        </div>

        <div class="ths-footer-note" style="display: flex; justify-content: space-between; align-items: center;">
            <span>16 jalons de révision · {{ $completedNodesCount ?? 4 }} validés</span>
            <a href="/student/test-niveau" style="color: #0E1022; background: #D4F25C; font-weight: 800; font-size: 11px; padding: 3px 8px; border-radius: 5px; text-decoration: none;">
                Test de niveau
            </a>
        </div>
    </div>

</div>

{{-- ========================================================================= --}}
{{-- 2. GRAND CADRE DU PARCOURS COLLÉ DIRECTEMENT (AUCUN ESPACEMENT, AUCUN SCROLL) --}}
{{-- ========================================================================= --}}
<div class="main-parcours-cockpit-frame">
    
    {{-- VOLET GAUCHE : LE PARCOURS DRAGON INTERACTIF --}}
    <div class="cockpit-parcours-pane">
        {{-- Navigation intégrée dans le cadre (flèches et commandes compactes) --}}
        <div class="dragon-floating-nav">
            <button type="button" class="nav-arrow-btn" onclick="scrollDragonTrack(-480)" title="Défiler à gauche" aria-label="Défiler à gauche">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
            </button>
            <button type="button" class="nav-target-btn" onclick="centerOnActiveNode()" title="Centrer sur l'étape active">
                Étape {{ sprintf('%02d', $activeNode['step'] ?? 1) }}
            </button>
            <button type="button" class="nav-arrow-btn" onclick="scrollDragonTrack(480)" title="Défiler à droite" aria-label="Défiler à droite">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </button>
            <button type="button" class="nav-replay-btn" onclick="startAvatarJourney(true)" title="Rejouer la foulée 3D">
                <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
                <span>Rejouer</span>
            </button>
        </div>

        {{-- Nuages décoratifs bord gauche --}}
        <div class="cloud-boundary-left">
            <svg viewBox="0 0 110 440" preserveAspectRatio="none" style="width: 100%; height: 100%;">
                <path d="M0,0 L60,0 Q110,40 80,90 Q130,140 90,190 Q140,250 90,310 Q120,370 70,410 Q100,440 0,440 Z" fill="#FFFFFF"/>
                <circle cx="20" cy="90" r="35" fill="#FFFFFF"/>
                <circle cx="35" cy="240" r="45" fill="#FFFFFF"/>
                <circle cx="25" cy="380" r="40" fill="#FFFFFF"/>
            </svg>
        </div>

        {{-- Nuages décoratifs bord droit --}}
        <div class="cloud-boundary-right">
            <svg viewBox="0 0 180 440" preserveAspectRatio="none" style="width: 100%; height: 100%;">
                <path d="M180,0 L100,0 Q40,50 80,110 Q20,170 70,230 Q10,290 60,350 Q30,410 90,440 L180,440 Z" fill="#FFFFFF"/>
                <circle cx="150" cy="100" r="45" fill="#FFFFFF"/>
                <circle cx="140" cy="260" r="55" fill="#FFFFFF"/>
                <circle cx="155" cy="400" r="40" fill="#FFFFFF"/>
            </svg>
        </div>

        {{-- Nuages flottants d'ambiance --}}
        <div class="floating-cloud-puff" style="left: 450px; top: 30px;">
            <svg width="110" height="45" viewBox="0 0 120 50" fill="none"><path d="M15 35 Q10 20 25 15 Q35 5 50 15 Q65 0 85 10 Q105 5 110 25 Q115 40 95 40 L20 40 Z" fill="#FFFFFF"/></svg>
        </div>
        <div class="floating-cloud-puff" style="left: 1250px; bottom: 30px; animation-duration: 18s;">
            <svg width="130" height="50" viewBox="0 0 140 60" fill="none"><path d="M20 45 Q15 25 35 20 Q45 10 65 20 Q85 5 110 15 Q130 10 135 35 Q140 50 115 50 L25 50 Z" fill="#FFFFFF"/></svg>
        </div>
        <div class="floating-cloud-puff" style="left: 2100px; top: 25px; animation-duration: 16s;">
            <svg width="120" height="48" viewBox="0 0 130 55" fill="none"><path d="M18 40 Q12 22 30 18 Q40 8 60 18 Q78 5 100 12 Q120 8 125 30 Q130 45 105 45 L22 45 Z" fill="#FFFFFF"/></svg>
        </div>
        <div class="floating-cloud-puff" style="left: 2950px; bottom: 35px; animation-duration: 20s;">
            <svg width="150" height="60" viewBox="0 0 160 70" fill="none"><path d="M25 55 Q18 30 40 25 Q55 12 80 25 Q100 8 130 20 Q150 15 155 45 Q160 62 130 62 L30 62 Z" fill="#FFFFFF"/></svg>
        </div>

        {{-- Piste de défilement horizontal du dragon --}}
        <div class="dragon-scroll-track" id="dragonScrollTrack">
            <div class="dragon-path-stage">
                
                @php
                    $activeStepNumber = (int) ($activeNode['step'] ?? 1);
                    $bezierSegments = [
                        'C 300,140 300,140 400,140',
                        'C 500,140 500,300 600,300',
                        'C 700,300 700,150 800,150',
                        'C 900,150 900,290 1000,290',
                        'C 1100,290 1100,150 1200,150',
                        'C 1300,150 1300,300 1400,300',
                        'C 1500,300 1500,150 1600,150',
                        'C 1700,150 1700,290 1800,290',
                        'C 1900,290 1900,150 2000,150',
                        'C 2100,150 2100,300 2200,300',
                        'C 2300,300 2300,150 2400,150',
                        'C 2500,150 2500,290 2600,290',
                        'C 2700,290 2700,150 2800,150',
                        'C 2900,150 2900,280 3000,280',
                        'C 3120,280 3120,200 3240,200',
                    ];
                    $completedSegments = array_slice($bezierSegments, 0, max(0, $activeStepNumber - 1));
                    $completedPathD = count($completedSegments) > 0
                        ? ('M 200,220 ' . implode(' ', $completedSegments))
                        : 'M 195,220 L 200,220';
                    $deskX = (int) ($activeNode['x'] ?? 200);
                    $deskY = (int) ($activeNode['y'] ?? 220) - 38;
                @endphp

                {{-- Tracé SVG de la courbe de dragon serpentant en zig-zag --}}
                <svg width="3500" height="440" style="position: absolute; left: 0; top: 0; pointer-events: none; z-index: 4;">
                    <defs>
                        <linearGradient id="dragonRoadCompletedGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" stop-color="#059669"/>
                            <stop offset="100%" stop-color="#10B981"/>
                        </linearGradient>
                        <linearGradient id="dragonRoadRemainingGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" stop-color="#CBD5E1"/>
                            <stop offset="85%" stop-color="#E2E8F0"/>
                            <stop offset="100%" stop-color="#FFFFFF"/>
                        </linearGradient>
                    </defs>

                    {{-- Tracé total de la route du dragon --}}
                    <path d="M 200,220 C 300,140 300,140 400,140 C 500,140 500,300 600,300 C 700,300 700,150 800,150 C 900,150 900,290 1000,290 C 1100,290 1100,150 1200,150 C 1300,150 1300,300 1400,300 C 1500,300 1500,150 1600,150 C 1700,150 1700,290 1800,290 C 1900,290 1900,150 2000,150 C 2100,150 2100,300 2200,300 C 2300,300 2300,150 2400,150 C 2500,150 2500,290 2600,290 C 2700,290 2700,150 2800,150 C 2900,150 2900,280 3000,280 C 3120,280 3120,200 3240,200" 
                          fill="none" stroke="url(#dragonRoadRemainingGrad)" stroke-width="15" stroke-linecap="round" stroke-linejoin="round"/>

                    {{-- Portion franchie par l'élève (dynamique selon le jalon actif) --}}
                    <path d="{{ $completedPathD }}" 
                          fill="none" stroke="url(#dragonRoadCompletedGrad)" stroke-width="15" stroke-linecap="round" stroke-linejoin="round"/>

                    {{-- Ligne centrale pointillée --}}
                    <path d="M 200,220 C 300,140 300,140 400,140 C 500,140 500,300 600,300 C 700,300 700,150 800,150 C 900,150 900,290 1000,290 C 1100,290 1100,150 1200,150 C 1300,150 1300,300 1400,300 C 1500,300 1500,150 1600,150 C 1700,150 1700,290 1800,290 C 1900,290 1900,150 2000,150 C 2100,150 2100,300 2200,300 C 2300,300 2300,150 2400,150 C 2500,150 2500,290 2600,290 C 2700,290 2700,150 2800,150 C 2900,150 2900,280 3000,280 C 3120,280 3120,200 3240,200" 
                          fill="none" stroke="#94A3B8" stroke-width="3" stroke-dasharray="8 8" stroke-linecap="round"/>

                    {{-- Écailles illuminées sur la portion validée --}}
                    <path d="{{ $completedPathD }}" 
                          fill="none" stroke="#D4F25C" stroke-width="3.5" stroke-dasharray="8 8" stroke-linecap="round"/>
                </svg>

                {{-- Personnage en course 3D fluide et réaliste --}}
                <div id="dragonRunnerFigure" style="position: absolute; left: 200px; top: 220px; z-index: 25; transform: translate(-50%, -100%); pointer-events: none;">
                    <div id="runnerTag" class="runner-speech-bubble">
                        En route vers le jalon {{ sprintf('%02d', $activeStepNumber) }}...
                    </div>
                    <div id="runnerTiltWrapper" class="runner-tilt-wrapper">
                        <div class="runner-body-bounce" id="runnerBounceBox">
                            <img src="/assets/avatars/boy_run_a.png" alt="Course foulée pied 1" id="runnerImgA" class="real-3d-runner-img" style="opacity: 1;">
                            <img src="/assets/avatars/boy_run_b.png" alt="Course foulée pied 2" id="runnerImgB" class="real-3d-runner-img" style="opacity: 0;">
                        </div>
                    </div>
                </div>

                {{-- Table d'études avec bulle au-dessus du personnage --}}
                <div id="dragonStudyDeskSetup" style="position: absolute; left: {{ $deskX }}px; top: {{ $deskY }}px; transform: translate(-50%, -100%); z-index: 28; display: none; flex-direction: column; align-items: center; pointer-events: none;">
                    <div class="study-desk-pill">
                        Prêt pour le BAC !
                    </div>
                    <div class="study-desk-figure" style="position: relative; width: 80px; height: auto; display: flex; align-items: center; justify-content: center;">
                        <img src="/assets/avatars/boy_study.png" alt="Élève assis à la table d'études" id="studyDeskImg" style="width: 100%; height: auto; max-height: 80px; object-fit: contain; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.14)); display: block;">
                    </div>
                </div>

                {{-- 16 Jalons positionnés le long du dragon --}}
                @foreach($gameNodes as $node)
                    @php
                        $tagClass = ($node['y'] > 220) ? 'tag-bottom' : 'tag-top';
                    @endphp

                    <div style="position: absolute; left: {{ $node['x'] }}px; top: {{ $node['y'] }}px; z-index: 10;">
                        {{-- Bulle du Jalon --}}
                        <div class="dragon-node-bubble {{ $node['status'] }} {{ $node['type'] == 'boss' ? 'boss' : '' }}" 
                             id="node-{{ $node['step'] }}"
                             onclick="handleNodeClick('{{ $node['status'] }}', '{{ $node['subject_id'] }}', '{{ $node['resource_id'] ?? '' }}', '{{ addslashes($node['title']) }}', {{ $node['step'] }})">
                            
                            @if($node['status'] === 'completed')
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span style="font-size: 8px; font-weight: 850; margin-top: 1px;">0{{ $node['step'] }}</span>
                            @elseif($node['status'] === 'active')
                                <span style="font-size: 14px; font-weight: 900; line-height: 1;">0{{ $node['step'] }}</span>
                                <span style="font-size: 8px; font-weight: 850; color: #D4F25C;">ICI</span>
                            @elseif($node['type'] === 'boss')
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="2.3"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path><path d="M4 22h16"></path><path d="M10 14.66V17c0 .55-.45 1-1 1H7.5"></path><path d="M14 14.66V17c0 .55.45 1 1 1h1.5"></path><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"></path></svg>
                                <span style="font-size: 8.5px; font-weight: 850; color: #FFFFFF;">BAC 2</span>
                            @else
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="2.2"><rect x="3" y="11" width="18" height="11" rx="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                <span style="font-size: 8px; font-weight: 750; color: #94A3B8;">{{ $node['step'] < 10 ? '0' : '' }}{{ $node['step'] }}</span>
                            @endif
                        </div>

                        {{-- Étiquette descriptive reliée au jalon --}}
                        <div class="dragon-node-tag {{ $tagClass }}" style="{{ $node['status'] == 'active' ? 'box-shadow: 0 0 0 2px #0E1022, 0 6px 18px rgba(14, 16, 34, 0.15) !important;' : '' }}">
                            <div style="display: flex; justify-content: space-between; align-items: center; gap: 4px; margin-bottom: 2px;">
                                <span style="font-size: 9px; font-weight: 800; color: {{ $node['status'] == 'completed' ? '#059669' : ($node['status'] == 'active' ? '#0E1022' : '#64748B') }};">
                                    {{ $node['subject'] }}
                                </span>
                                @if($node['status'] === 'completed')
                                    <span style="font-size: 9px; font-weight: 800; color: #059669;">
                                        Validé {{ $node['score'] }}%
                                    </span>
                                @elseif($node['status'] === 'active')
                                    <span style="font-size: 8.5px; font-weight: 800; background: #0E1022; color: #FFFFFF; padding: 1px 5px; border-radius: 4px;">
                                        Actif
                                    </span>
                                @endif
                            </div>

                            <div style="font-size: 10.5px; font-weight: 800; color: #0E1022; line-height: 1.25; margin-bottom: 4px;">
                                {{ $node['title'] }}
                            </div>

                            @if($node['status'] === 'active' || $node['status'] === 'completed')
                                <a href="{{ route('student.chat', ['subject' => $node['subject_id'], 'course' => $node['resource_id'] ?? '', 'step' => $node['step']]) }}" class="btn-lime-action" style="padding: 3px 8px; font-size: 9.5px; width: 100%; box-sizing: border-box; text-decoration: none;">
                                    <span>{{ $node['status'] === 'active' ? "Lancer l'atelier →" : "Revoir l'atelier →" }}</span>
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- VOLET DROIT : COURS & PROGRESSION --}}
    <div class="cockpit-courses-pane">
        
        {{-- BLOC 1 : SÉANCE CLÉ PRIORITAIRE DU JOUR --}}
        <div class="cockpit-priority-session-card">
            <div class="cp-header-row">
                <span class="cp-priority-badge">Séance Clé Prioritaire</span>
                <span class="cp-coeff-badge">Coeff. {{ $activeNode['coeff'] ?? 4 }} · Majeure</span>
            </div>
            <h3 class="cp-session-title">{{ $activeNode['title'] ?? 'Chapitre du jour' }}</h3>
            <div class="cp-session-sub">{{ $activeNode['subject'] ?? 'Général' }} · {{ $student->class_name ?? 'Terminale D' }} · {{ $activeNode['duration'] ?? '30 min' }} estimées</div>
            <div class="cp-session-goal">
                <b>Objectif d'examen :</b> Maîtriser le chapitre et valider les 3 paliers d'entraînement au BAC 2 Togo.
            </div>
            <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                <a href="{{ route('student.seance') }}" class="btn-continue-revision" style="background: #D4F25C; color: #0E1022; flex: 1.2;">
                    <span>Séance du jour (25 min)</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </a>
                <a href="{{ route('student.chat', ['subject' => $activeNode['subject_id'], 'course' => $activeNode['resource_id'] ?? '', 'step' => $activeNode['step']]) }}" class="btn-continue-revision" style="background: #0E1022; color: #FFFFFF; flex: 1;">
                    <span>Révision IA</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </a>
            </div>
        </div>

        {{-- BLOC 2 : PROGRESSION OFFICIELLE PAR MATIÈRE --}}
        <div class="cockpit-subjects-progress-card">
            <div class="cs-header-row">
                <span class="cs-title">Progression des Matières</span>
                <span class="cs-sub">Programme BAC 2 Série D</span>
            </div>
            <div class="cockpit-subjects-list">
                @foreach($officialSubjects as $sb)
                    <div class="cockpit-subject-item">
                        <div class="cs-item-top">
                            <span class="cs-item-name">{{ $sb['name'] }}</span>
                            <span class="cs-item-val" style="color: {{ $sb['color'] }};">{{ $sb['mastery'] }}%</span>
                        </div>
                        <div class="cs-item-coeff">{{ $sb['coeff'] }}</div>
                        <div class="cs-item-track">
                            <div class="cs-item-fill" style="width: {{ $sb['mastery'] }}%; background: {{ $sb['color'] }};"></div>
                        </div>
                        <div class="cs-item-bottom">
                            <span class="cs-status-pill {{ $sb['mastery'] == 0 ? 'pill-neutral' : ($sb['mastery'] < 65 ? 'pill-alert' : 'pill-ok') }}">
                                {{ $sb['mastery'] == 0 ? 'À débuter' : ($sb['mastery'] < 65 ? 'Points à consolider' : 'Acquis satisfaisant') }}
                            </span>
                            <a href="{{ route('student.chat', ['subject' => $sb['id']]) }}" class="cs-review-link">
                                Réviser →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

</div>

{{-- ========================================================================= --}}
{{-- 3. LOGIQUE JAVASCRIPT : ANIMATION RÉALISTE & DÉFILEMENT DU PARCOURS       --}}
{{-- ========================================================================= --}}
<script>
// Points de passage dynamiques le long du dragon jusqu'au jalon actif (calculé par le Test de niveau)
const targetActiveStep = {{ (int) ($activeNode['step'] ?? 1) }};
const allDragonWaypoints = [
    { x: 145, y: 220, tilt: 0 },
    { x: 200, y: 220, node: 1, tilt: -12 },
    { x: 300, y: 170, tilt: -18 },
    { x: 400, y: 140, node: 2, tilt: 0 },
    { x: 500, y: 220, tilt: 16 },
    { x: 600, y: 300, node: 3, tilt: 0 },
    { x: 700, y: 220, tilt: -18 },
    { x: 800, y: 150, node: 4, tilt: 0 },
    { x: 900, y: 220, tilt: 16 },
    { x: 1000, y: 290, node: 5, tilt: 0 },
    { x: 1100, y: 220, tilt: -16 },
    { x: 1200, y: 150, node: 6, tilt: 0 },
    { x: 1300, y: 225, tilt: 16 },
    { x: 1400, y: 300, node: 7, tilt: 0 },
    { x: 1500, y: 225, tilt: -16 },
    { x: 1600, y: 150, node: 8, tilt: 0 },
    { x: 1700, y: 220, tilt: 16 },
    { x: 1800, y: 290, node: 9, tilt: 0 },
    { x: 1900, y: 220, tilt: -16 },
    { x: 2000, y: 150, node: 10, tilt: 0 },
    { x: 2100, y: 225, tilt: 16 },
    { x: 2200, y: 300, node: 11, tilt: 0 },
    { x: 2300, y: 225, tilt: -16 },
    { x: 2400, y: 150, node: 12, tilt: 0 },
    { x: 2500, y: 220, tilt: 16 },
    { x: 2600, y: 290, node: 13, tilt: 0 },
    { x: 2700, y: 220, tilt: -16 },
    { x: 2800, y: 150, node: 14, tilt: 0 },
    { x: 2900, y: 215, tilt: 14 },
    { x: 3000, y: 280, node: 15, tilt: 0 },
    { x: 3120, y: 240, tilt: -12 },
    { x: 3240, y: 200, node: 16, tilt: 0 }
];
const stopIdx = allDragonWaypoints.findIndex(w => w.node === targetActiveStep);
const runWaypoints = stopIdx >= 1 ? allDragonWaypoints.slice(0, stopIdx + 1) : allDragonWaypoints.slice(0, 2);

let isRunningJourney = false;
let journeyAnimFrame = null;
let currentGender = localStorage.getItem('student_avatar_gender') || 'boy';

// Préchargement des sprites de course
const preloadedRunSprites = {
    boy_a: new Image(),
    boy_b: new Image(),
    girl_a: new Image(),
    girl_b: new Image()
};
preloadedRunSprites.boy_a.src = '/assets/avatars/boy_run_a.png';
preloadedRunSprites.boy_b.src = '/assets/avatars/boy_run_b.png';
preloadedRunSprites.girl_a.src = '/assets/avatars/girl_run_a.png';
preloadedRunSprites.girl_b.src = '/assets/avatars/girl_run_b.png';

function startAvatarJourney(forceReplay = false) {
    if (isRunningJourney) return;
    isRunningJourney = true;

    const runner = document.getElementById('dragonRunnerFigure');
    const runnerImgA = document.getElementById('runnerImgA');
    const runnerImgB = document.getElementById('runnerImgB');
    const runnerTilt = document.getElementById('runnerTiltWrapper');
    const runnerBounceBox = document.getElementById('runnerBounceBox');
    const studyDesk = document.getElementById('dragonStudyDeskSetup');
    const studyImg = document.getElementById('studyDeskImg');
    const track = document.getElementById('dragonScrollTrack');

    if (!runner || !studyDesk || !track) return;

    if (currentGender === 'girl') {
        if (runnerImgA) runnerImgA.src = '/assets/avatars/girl_run_a.png';
        if (runnerImgB) runnerImgB.src = '/assets/avatars/girl_run_b.png';
        if (studyImg) studyImg.src = '/assets/avatars/girl_study.png';
    } else {
        if (runnerImgA) runnerImgA.src = '/assets/avatars/boy_run_a.png';
        if (runnerImgB) runnerImgB.src = '/assets/avatars/boy_run_b.png';
        if (studyImg) studyImg.src = '/assets/avatars/boy_study.png';
    }

    studyDesk.style.display = 'none';
    studyDesk.classList.remove('study-desk-animated');
    runner.style.display = 'block';
    if (runnerImgA) runnerImgA.style.opacity = '1';
    if (runnerImgB) runnerImgB.style.opacity = '0';
    track.scrollTo({ left: 0, behavior: 'auto' });

    const totalDuration = 2400;
    const startTime = performance.now();
    const totalWaypoints = runWaypoints.length - 1;

    function animateStep(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(1, elapsed / totalDuration);

        const scaledIndex = progress * totalWaypoints;
        const baseIndex = Math.min(totalWaypoints - 1, Math.floor(scaledIndex));
        const segProgress = scaledIndex - baseIndex;

        const p1 = runWaypoints[baseIndex];
        const p2 = runWaypoints[baseIndex + 1];

        const curX = p1.x + (p2.x - p1.x) * segProgress;
        const curY = p1.y + (p2.y - p1.y) * segProgress;
        const curTilt = p1.tilt + (p2.tilt - p1.tilt) * segProgress;

        runner.style.left = curX + 'px';
        runner.style.top = curY + 'px';

        const stepDuration = 260;
        const cycleDuration = 520;
        const strideStep = Math.floor(elapsed / stepDuration);
        const isStepA = (strideStep % 2 === 0);

        if (runnerImgA && runnerImgB) {
            runnerImgA.style.opacity = isStepA ? '1' : '0';
            runnerImgB.style.opacity = isStepA ? '0' : '1';
        }

        const stepProgress = (elapsed % stepDuration) / stepDuration;
        const verticalBounce = -Math.sin(stepProgress * Math.PI) * 5.0;

        const cycleProgress = (elapsed % cycleDuration) / cycleDuration;
        const armSwingAngle = Math.sin(cycleProgress * 2 * Math.PI) * 3.5;

        if (runnerTilt) {
            runnerTilt.style.transform = 'rotate(' + (curTilt + armSwingAngle) + 'deg)';
        }
        if (runnerBounceBox) {
            runnerBounceBox.style.transform = 'translateY(' + verticalBounce + 'px)';
        }

        const targetScroll = curX - (track.clientWidth / 2);
        track.scrollLeft = Math.max(0, targetScroll);

        if (p2.node) {
            const nodeElem = document.getElementById('node-' + p2.node);
            if (nodeElem && segProgress > 0.85) {
                nodeElem.style.transform = 'translate(-50%, -50%) scale(1.15)';
                setTimeout(() => {
                    nodeElem.style.transform = '';
                }, 220);
            }
        }

        if (progress < 1) {
            journeyAnimFrame = requestAnimationFrame(animateStep);
        } else {
            runner.style.display = 'none';
            studyDesk.style.display = 'flex';
            studyDesk.classList.add('study-desk-animated');
            isRunningJourney = false;

            setTimeout(() => {
                centerOnActiveNode();
                if (typeof window.onJourneyCompleted === 'function') {
                    window.onJourneyCompleted();
                }
            }, 100);
        }
    }

    journeyAnimFrame = requestAnimationFrame(animateStep);
}

function scrollDragonTrack(amount) {
    const track = document.getElementById('dragonScrollTrack');
    if (track) {
        track.scrollBy({ left: amount, behavior: 'smooth' });
    }
}

function centerOnActiveNode() {
    const track = document.getElementById('dragonScrollTrack');
    const activeEl = document.querySelector('.dragon-node-bubble.active');
    if (track && activeEl) {
        const offsetLeft = activeEl.parentElement.offsetLeft;
        const targetX = offsetLeft - (track.clientWidth / 2) + 28;
        track.scrollTo({ left: Math.max(0, targetX), behavior: 'smooth' });
    } else if (track) {
        track.scrollTo({ left: 400, behavior: 'smooth' });
    }
}

function showParcoursToast(msg) {
    let toast = document.getElementById('mkParcoursToast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'mkParcoursToast';
        toast.style.cssText = 'position:fixed;bottom:24px;left:50%;transform:translateX(-50%);background:#0E1022;color:#F8FAFC;padding:12px 20px;border-radius:12px;font-size:13px;font-weight:600;box-shadow:0 10px 25px -5px rgba(0,0,0,0.3);z-index:99999;transition:all 0.3s cubic-bezier(0.16, 1, 0.3, 1);opacity:0;pointer-events:none;display:flex;align-items:center;gap:10px;border:1px solid rgba(255,255,255,0.1);max-width:90%;';
        toast.innerHTML = `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#F59E0B" stroke-width="2.2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg><span id="mkParcoursToastText"></span>`;
        document.body.appendChild(toast);
    }
    const textEl = document.getElementById('mkParcoursToastText');
    if (textEl) textEl.textContent = msg;
    toast.style.opacity = '1';
    toast.style.transform = 'translateX(-50%) translateY(0)';
    if (window._mkToastTimer) clearTimeout(window._mkToastTimer);
    window._mkToastTimer = setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(-50%) translateY(10px)';
    }, 3500);
}

function handleNodeClick(status, subjectId, resourceId, title, step) {
    if (status === 'locked') {
        showParcoursToast("Ce jalon se débloquera dès que tu auras validé le jalon précédent.");
        return;
    }
    let url = '/student/chat?subject=' + encodeURIComponent(subjectId);
    if (resourceId && resourceId !== '') {
        url += '&course=' + encodeURIComponent(resourceId);
    }
    if (step) {
        url += '&step=' + step;
    }
    window.location.href = url;
}

// GESTION DU MODE RÉVÉLATION DU PARCOURS PERSONNALISÉ (?revealed=1)
(function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('revealed') === '1' || urlParams.get('calibrated') === '1') {
        const status = urlParams.get('status') || 'nouveau';
        const period = urlParams.get('period') || 'milieu';
        const ambition = urlParams.get('ambition') || 'mention';
        const score = urlParams.get('score') || '{{ $calibData["combined_score"] ?? 0 }}';
        const mention = urlParams.get('mention') || '{{ $calibData["mention"] ?? "Calibré" }}';
        const reliability = urlParams.get('reliability') || '{{ $calibData["reliability"] ?? 82 }}';
        const hasReports = (urlParams.get('has_reports') === '1' || parseInt(reliability) >= 95);

        // Profil labels
        const statusMap = {
            'nouveau': 'Nouvel élève de Terminale D',
            'redoublant': 'Élève Redoublant (Consolidation)'
        };
        const periodMap = {
            'debut': "Début d'année (Sept - Nov)",
            'milieu': "Milieu d'année (Trimestre 2)",
            'fin': "Sprint BAC (Avr - Juin)",
            'vacances': "Grandes Vacances"
        };
        const ambitionMap = {
            'reussite': "Sécuriser le BAC (10-12)",
            'mention': "Viser la Mention (14-17)",
            'excellence': "Excellence & Concours (18+)"
        };

        const profEl = document.getElementById('modalProfileText');
        if (profEl) profEl.innerText = statusMap[status] || 'Élève de Terminale D';

        const perEl = document.getElementById('modalPeriodText');
        if (perEl) perEl.innerText = periodMap[period] || "Milieu d'année (Trimestre 2)";

        const ambEl = document.getElementById('modalAmbitionText');
        if (ambEl) ambEl.innerText = ambitionMap[ambition] || "Viser la Mention (14-17)";

        const scoreEl = document.getElementById('modalScoreVal');
        if (scoreEl) scoreEl.innerText = score;

        const mentEl = document.getElementById('modalMentionVal');
        if (mentEl) mentEl.innerText = mention;

        const relEl = document.getElementById('modalReliabilityVal');
        if (relEl) relEl.innerText = reliability;

        const relSubEl = document.getElementById('modalReliabilitySub');
        if (relSubEl) {
            relSubEl.innerText = hasReports ? 'Basé sur test QCM & bulletins' : 'Basé sur test QCM';
        }

        // Adaptation personnalisée pour élève REDOUBLANT
        if (status === 'redoublant') {
            const subTitleEl = document.querySelector('.cpm-subtitle');
            if (subTitleEl) {
                subTitleEl.innerText = "Profil Redoublant activé : vos lacunes ciblées sont prioritaires dès l'étape 01 pour viser la mention.";
            }
        }

        // Définition du déclencheur de modal à l'arrivée
        window.onJourneyCompleted = function() {
            setTimeout(() => {
                const overlay = document.getElementById('calibratedParcoursOverlay');
                if (overlay) overlay.style.display = 'flex';
            }, 300);
        };
    }
})();

function closeCalibratedModal() {
    const overlay = document.getElementById('calibratedParcoursOverlay');
    if (overlay) overlay.style.display = 'none';

    if (window.history.replaceState) {
        window.history.replaceState(null, '', window.location.pathname);
    }

    const revBtn = document.querySelector('.btn-continue-revision');
    if (revBtn) {
        revBtn.style.transform = 'scale(1.05)';
        revBtn.style.transition = 'transform 0.25s ease';
        setTimeout(() => {
            revBtn.style.transform = '';
        }, 500);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const isRevealed = (new URLSearchParams(window.location.search).get('revealed') === '1');
    setTimeout(() => {
        startAvatarJourney(isRevealed);
    }, 350);

    const track = document.getElementById('dragonScrollTrack');
    if (track) {
        let isDown = false;
        let startX, scrollLeft;

        track.addEventListener('mousedown', (e) => {
            isDown = true;
            startX = e.pageX - track.offsetLeft;
            scrollLeft = track.scrollLeft;
        });
        track.addEventListener('mouseleave', () => { isDown = false; });
        track.addEventListener('mouseup', () => { isDown = false; });
        track.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - track.offsetLeft;
            const walk = (x - startX) * 1.5;
            track.scrollLeft = scrollLeft - walk;
        });
    }
});
</script>

{{-- MODAL DE DÉBRIEF DU PARCOURS CALIBRÉ SUITE AU TEST DE NIVEAU --}}
<div id="calibratedParcoursOverlay" class="cpm-overlay" style="display: none;">
    <div class="cpm-modal">
        <div class="cpm-header">
            <div class="cpm-badge">Parcours Calibré avec Succès</div>
            <h2 class="cpm-title">Votre Feuille de Route Personnalisée BAC 2</h2>
            <p class="cpm-subtitle">L'algorithme pédagogique a analysé vos résultats et structuré vos 16 jalons de révision.</p>
        </div>

        {{-- Badges de profil contextuel --}}
        <div class="cpm-context-row">
            <div class="cpm-ctx-pill" id="modalProfilePill">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                <span id="modalProfileText">Nouvel élève de Terminale D</span>
            </div>
            <div class="cpm-ctx-pill" id="modalPeriodPill">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                <span id="modalPeriodText">Milieu d'année (Trimestre 2)</span>
            </div>
            <div class="cpm-ctx-pill" id="modalAmbitionPill">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3"/></svg>
                <span id="modalAmbitionText">Viser la Mention (14-17)</span>
            </div>
        </div>

        {{-- 3 KPI Cards --}}
        <div class="cpm-kpis-grid">
            <div class="cpm-kpi-card">
                <span class="cpm-kpi-label">Diagnostic Initial</span>
                <div class="cpm-kpi-val"><span id="modalScoreVal">{{ $calibData['combined_score'] ?? 0 }}</span> <small>/ 20</small></div>
                <span class="cpm-kpi-sub" id="modalMentionVal">{{ $calibData['mention'] ?? 'Calibré' }}</span>
            </div>
            <div class="cpm-kpi-card">
                <span class="cpm-kpi-label">Fiabilité du Calibrage</span>
                <div class="cpm-kpi-val" style="color: #059669;"><span id="modalReliabilityVal">{{ $calibData['reliability'] ?? 82 }}</span>%</div>
                <span class="cpm-kpi-sub" id="modalReliabilitySub">Basé sur test QCM</span>
            </div>
            <div class="cpm-kpi-card">
                <span class="cpm-kpi-label">Point d'Ancrage Actif</span>
                <div class="cpm-kpi-val" style="color: #0E1022;">Étape {{ sprintf('%02d', $activeNode['step'] ?? 1) }}</div>
                <span class="cpm-kpi-sub">{{ $activeNode['title'] ?? 'Fondations' }} ({{ $activeNode['subject'] ?? 'Général' }})</span>
            </div>
        </div>

        {{-- Étapes Débloquées vs Jalons Futurs --}}
        <div class="cpm-steps-summary">
            <div class="cpm-step-item step-done">
                <div class="cpm-si-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg></div>
                <div class="cpm-si-text">
                    <strong>{{ $completedNodesCount ?? 0 }} {{ ($completedNodesCount ?? 0) > 1 ? 'Jalons Validés' : 'Jalon Validé' }}</strong>
                    <span>{{ ($completedNodesCount ?? 0) > 0 ? 'Acquis validés au cours de votre apprentissage' : 'Nouveau parcours · Tout commence au jalon initial (Étape 01)' }}</span>
                </div>
            </div>
            <div class="cpm-step-item step-current">
                <div class="cpm-si-icon">{{ sprintf('%02d', $activeNode['step'] ?? 1) }}</div>
                <div class="cpm-si-text">
                    <strong>Étape Active Actuelle (Priorité 1)</strong>
                    <span>{{ $activeNode['title'] ?? 'Chapitre initial' }} ({{ $activeNode['subject'] ?? 'Général' }}) · Prêt à démarrer</span>
                </div>
            </div>
            <div class="cpm-step-item step-locked">
                <div class="cpm-si-icon">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </div>
                <div class="cpm-si-text">
                    <strong>{{ max(0, 16 - ($activeNode['step'] ?? 1)) }} Jalons Suivants Cadenassés</strong>
                    <span>Déblocage séquentiel automatique au fil de vos réussites</span>
                </div>
            </div>
        </div>

        {{-- Action de démarrage --}}
        <div class="cpm-actions">
            <button type="button" class="btn-start-curriculum" onclick="closeCalibratedModal()">
                <span>Commencer ma première séance de révision</span>
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
            </button>
        </div>
    </div>
</div>

@endsection
