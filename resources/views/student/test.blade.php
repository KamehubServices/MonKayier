@extends('layouts.student')

@section('title', "Test de Niveau Général — MonKayier")

@section('content')
<style>

/* ==========================================================================
   TEST DE NIVEAU GÉNÉRAL — EXPÉRIENCE IMMERSIVE SEAMLESS PLEINE PAGE
   (ZÉRO GRAND BOX, FOND UNIQUE 100% BLANC, RESPONSIVE & SANS RUPTURE VISUELLE)
   ========================================================================== */

/* Force fond unique blanc sur l'ensemble de la page et du layout */
body.page-test-niveau,
body.page-test-niveau .p,
body.page-test-niveau .lay,
body.page-test-niveau .lay .w {
    background: #FFFFFF !important;
}

.tn-immersive-wrapper {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    background: #FFFFFF !important;
    width: 100% !important;
    max-width: 100% !important;
    margin: 0 !important;
    padding: 0 !important;
    box-sizing: border-box !important;
    overflow-x: hidden !important;
}

/* --------------------------------------------------------------------------
   EN-TÊTE IMMERSIF PLEINE LARGEUR HORIZONTALE (PLEIN ÉCRAN FLUIDE)
   -------------------------------------------------------------------------- */
.tn-header-bar {
    position: sticky;
    top: 0;
    z-index: 100;
    height: 50px;
    background: #FFFFFF;
    border-bottom: 1px solid #E2E8F0;
    padding: 0 24px;
    display: grid;
    grid-template-columns: 1fr auto 1fr;
    align-items: center;
    box-sizing: border-box;
    width: 100% !important;
    max-width: 100% !important;
}

.thb-left {
    display: flex;
    align-items: center;
    gap: 12px;
    justify-self: start;
}
.thb-brand {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    color: inherit;
    transition: opacity 0.15s ease;
}
.thb-brand:hover {
    opacity: 0.85;
}
.thb-logo {
    height: 30px;
    width: auto;
}
.thb-brand-name {
    font-size: 17px;
    font-weight: 850;
    color: #0E1022;
    letter-spacing: -0.02em;
}
.thb-divider {
    width: 1px;
    height: 18px;
    background: #E2E8F0;
}
.thb-subtext {
    font-size: 13.5px;
    font-weight: 700;
    color: #475569;
    letter-spacing: -0.01em;
    white-space: nowrap;
}
.wizard-back-btn {
    background: transparent;
    border: none;
    color: #64748B;
    font-size: 12.5px;
    font-weight: 750;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 10px;
    border-radius: 6px;
    transition: all 0.15s ease;
    margin-left: 6px;
}
.wizard-back-btn:hover {
    color: #0E1022;
    background: #F1F5F9;
}

.thb-center {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    justify-self: center;
}
.wizard-step-info {
    font-size: 13px;
    font-weight: 850;
    color: #0E1022;
    letter-spacing: -0.01em;
    text-align: center;
}
.thb-progress-rail {
    width: 240px;
    height: 6px;
    background: #F1F5F9;
    border-radius: 999px;
    overflow: hidden;
}
.wizard-progress-fill {
    height: 100%;
    background: #0E1022;
    border-radius: 999px;
    transition: width 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

.thb-right {
    display: flex;
    align-items: center;
    gap: 12px;
    justify-self: end;
}
.btn-fullscreen-toggle {
    background: #FFFFFF;
    border: 1px solid #CBD5E1;
    border-radius: 7px;
    padding: 5px 12px;
    font-size: 11.5px;
    font-weight: 750;
    color: #475569;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.15s ease;
}
.btn-fullscreen-toggle:hover {
    background: #0E1022;
    color: #D4F25C;
    border-color: #0E1022;
}
.wizard-step-pct {
    font-size: 11.5px;
    font-weight: 800;
    color: #0284c7;
    background: #EFF6FF;
    padding: 4px 10px;
    border-radius: 6px;
}
.btn-thb-exit {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    border-radius: 7px;
    background: #FEF2F2;
    border: 1px solid #FECACA;
    color: #DC2626;
    font-size: 11.5px;
    font-weight: 750;
    text-decoration: none;
    transition: all 0.15s ease;
}
.btn-thb-exit:hover {
    background: #FEE2E2;
    color: #991B1B;
}

/* --------------------------------------------------------------------------
   SCÈNE CENTRALE IMMERSIVE — PARFAITEMENT CENTRÉE, ERGONOMIQUE & RESPONSIVE
   -------------------------------------------------------------------------- */
.tn-stage {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: center;
    min-height: calc(100vh - 50px);
    padding: 16px 28px 36px;
    box-sizing: border-box;
    width: 100% !important;
    max-width: 100% !important;
    margin: 0 auto !important;
    background: #FFFFFF !important;
}

.wizard-step-pane {
    display: none;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 100%;
    margin: auto 0;
    animation: stepSlideFade 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    box-sizing: border-box;
}
.wizard-step-pane.active {
    display: flex;
}
@keyframes stepSlideFade {
    from { opacity: 0; transform: translateY(6px); }
    to { opacity: 1; transform: translateY(0); }
}

/* CONTENEUR CENTRÉ HARMONISÉ PAR ÉTAPE */
.wizard-card-container {
    width: 100%;
    background: transparent !important;
    border: none !important;
    border-radius: 0 !important;
    box-shadow: none !important;
    padding: 0 !important;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    margin: 0 auto;
}

#stepPane1 .wizard-card-container {
    max-width: 880px;
}
#stepPane2 .wizard-card-container {
    max-width: 1160px;
}
#stepPane3 .wizard-card-container {
    max-width: 1100px;
}
#stepPane4 .wizard-card-container {
    max-width: 1040px;
}
#stepPane5 .wizard-card-container,
#stepPane5 {
    max-width: 1040px;
    width: 100%;
    margin: auto auto;
}
#sectionTest {
    max-width: 1360px;
    width: 100%;
    margin: 12px auto 32px;
}
#sectionReports {
    max-width: 1000px;
    width: 100%;
    margin: auto auto;
}
#sectionResults {
    max-width: 960px;
    width: 100%;
    margin: auto auto;
}

.wizard-step-header {
    text-align: center;
    max-width: 820px;
    margin: 0 auto 28px;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.wizard-step-badge {
    display: inline-flex;
    align-items: center;
    background: #0E1022;
    color: #D4F25C;
    font-size: 11px;
    font-weight: 850;
    padding: 4px 12px;
    border-radius: 6px;
    letter-spacing: 0.03em;
    margin-bottom: 12px;
}
.wizard-step-title {
    font-size: clamp(22px, 2.5vw, 30px);
    font-weight: 900;
    color: #0E1022;
    margin: 0 0 10px 0;
    letter-spacing: -0.025em;
    line-height: 1.25;
    text-align: center;
}
.wizard-step-subtitle {
    font-size: clamp(13.5px, 1.2vw, 15px);
    color: #64748B;
    margin: 0 auto;
    line-height: 1.55;
    max-width: 700px;
    text-align: center;
}

/* --------------------------------------------------------------------------
   GRILLES ET CARTES DE CHOIX ERGONOMIQUES ET RESPONSIVES
   -------------------------------------------------------------------------- */
.immersive-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 26px;
    width: 100%;
    box-sizing: border-box;
}
.immersive-grid-4 {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
    width: 100%;
    box-sizing: border-box;
}
.immersive-grid-3 {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 22px;
    width: 100%;
    box-sizing: border-box;
}

@media (max-width: 1120px) {
    .immersive-grid-4 {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width: 960px) {
    .immersive-grid-3 {
        grid-template-columns: 1fr;
    }
}
@media (max-width: 768px) {
    .immersive-grid-2,
    .immersive-grid-4 {
        grid-template-columns: 1fr;
    }
    .tn-stage {
        padding: 20px 16px;
    }
}

.immersive-choice-card {
    background: #FAFAFC;
    border: 2px solid #E2E8F0;
    border-radius: 16px;
    padding: 32px 28px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-height: 220px;
    cursor: pointer;
    transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
    box-sizing: border-box;
    text-align: left;
    position: relative;
}
.immersive-choice-card:hover {
    background: #FFFFFF;
    border-color: #0E1022;
    transform: translateY(-3px);
    box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
}
.immersive-choice-card.selected {
    background: #0E1022;
    border-color: #0E1022;
    box-shadow: 0 16px 36px rgba(14, 16, 34, 0.22);
}

.icc-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}
.icc-tag {
    font-size: 11.5px;
    font-weight: 800;
    background: #EDF2F7;
    color: #475569;
    padding: 4px 10px;
    border-radius: 6px;
    letter-spacing: 0.02em;
}
.immersive-choice-card.selected .icc-tag {
    background: #D4F25C;
    color: #0E1022;
}

.icc-radio-indicator {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    border: 2px solid #CBD5E1;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    background: #FFFFFF;
}
.immersive-choice-card.selected .icc-radio-indicator {
    border-color: #D4F25C;
    background: #D4F25C;
}
.icc-radio-inner {
    width: 9px;
    height: 9px;
    border-radius: 50%;
    background: #0E1022;
    display: none;
}
.immersive-choice-card.selected .icc-radio-inner {
    display: block;
}

.icc-body {
    display: flex;
    flex-direction: column;
    flex: 1;
}
.icc-title {
    font-size: 19px;
    font-weight: 850;
    color: #0E1022;
    margin: 0 0 10px 0;
    letter-spacing: -0.015em;
    min-height: 28px;
    display: flex;
    align-items: center;
}
.immersive-choice-card.selected .icc-title {
    color: #FFFFFF;
}

.icc-desc {
    font-size: 14px;
    color: #64748B;
    line-height: 1.55;
    margin: 0;
    min-height: 48px;
}
.immersive-choice-card.selected .icc-desc {
    color: #94A3B8;
}

.wizard-footer-action {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 16px;
    margin-top: 38px;
    width: 100%;
}
.btn-wizard-next {
    background: #D4F25C;
    color: #0E1022;
    border: none;
    border-radius: 10px !important;
    box-shadow: none !important;
    font-size: 15.5px;
    font-weight: 850;
    padding: 16px 44px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: background 0.15s ease, transform 0.15s ease;
}
.btn-wizard-next:hover {
    background: #cbf046;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(212, 242, 92, 0.4) !important;
}

/* --------------------------------------------------------------------------
   ÉTAPE 4 : RÉCAPITULATIF INTERMÉDIAIRE
   -------------------------------------------------------------------------- */
.recap-summary-cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    width: 100%;
    box-sizing: border-box;
    margin-bottom: 26px;
}
@media (max-width: 820px) {
    .recap-summary-cards {
        grid-template-columns: 1fr;
    }
}
.recap-item-card {
    background: #FAFAFC;
    border: 1.5px solid #E2E8F0;
    border-radius: 14px;
    padding: 22px 22px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: 14px;
    box-sizing: border-box;
    text-align: left;
}
.ric-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.ric-step-tag {
    font-size: 11px;
    font-weight: 800;
    background: #EFF6FF;
    color: #0284c7;
    padding: 3px 8px;
    border-radius: 5px;
}
.ric-value-title {
    font-size: 16px;
    font-weight: 850;
    color: #0E1022;
    margin-bottom: 4px;
    min-height: 36px;
    display: flex;
    align-items: center;
}
.ric-desc {
    font-size: 13px;
    color: #64748B;
    line-height: 1.48;
    margin: 0;
    min-height: 44px;
}
.ric-edit-btn {
    background: #FFFFFF;
    border: 1px solid #CBD5E1;
    border-radius: 6px;
    padding: 4px 10px;
    font-size: 11px;
    font-weight: 750;
    color: #0E1022;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: all 0.15s ease;
}
.ric-edit-btn:hover {
    background: #0E1022;
    color: #D4F25C;
    border-color: #0E1022;
}

.recap-decision-box {
    background: #FAFAFC;
    border: 1.5px solid #CBD5E1;
    border-radius: 14px;
    padding: 24px 28px;
    display: flex;
    flex-direction: column;
    gap: 16px;
    box-sizing: border-box;
    width: 100%;
    text-align: left;
}
.rdb-top {
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.rdb-title {
    font-size: 16.5px;
    font-weight: 850;
    color: #0E1022;
}
.rdb-sub {
    font-size: 13.5px;
    color: #475569;
    line-height: 1.48;
}
.rdb-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
    padding-top: 14px;
    border-top: 1px solid #E2E8F0;
}
.btn-recap-back {
    background: #FFFFFF;
    color: #334155;
    border: 1.5px solid #CBD5E1;
    border-radius: 8px !important;
    padding: 13px 22px;
    font-size: 13.5px;
    font-weight: 750;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.15s ease;
}
.btn-recap-back:hover {
    background: #F1F5F9;
    color: #0E1022;
    border-color: #0E1022;
}
.btn-recap-confirm {
    background: #D4F25C;
    color: #0E1022;
    border: none;
    border-radius: 8px !important;
    box-shadow: none !important;
    padding: 14px 28px;
    font-size: 14.5px;
    font-weight: 850;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: background 0.15s ease;
}
.btn-recap-confirm:hover {
    background: #cbf046;
}

/* --------------------------------------------------------------------------
   ÉTAPE 5 : CHOIX DU MODE D'ÉVALUATION & BANDEAU DE PROFIL
   -------------------------------------------------------------------------- */
.tn-profile-recap-banner {
    background: #FAFAFC;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    padding: 14px 22px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 14px;
    box-sizing: border-box;
    width: 100%;
    margin-bottom: 22px;
}
.tn-prb-left {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    flex-wrap: wrap;
}
.tn-prb-tag {
    color: #64748B;
    font-weight: 600;
}
.tn-prb-val {
    font-weight: 850;
    color: #0E1022;
}
.tn-prb-edit-btn {
    background: #FFFFFF;
    border: 1px solid #CBD5E1;
    border-radius: 6px;
    padding: 4px 10px;
    font-size: 11px;
    font-weight: 750;
    color: #0E1022;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: all 0.15s ease;
}
.tn-prb-edit-btn:hover {
    background: #0E1022;
    color: #D4F25C;
    border-color: #0E1022;
}

.tn-choice-cards-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 26px;
    width: 100%;
}
@media (max-width: 880px) {
    .tn-choice-cards-grid {
        grid-template-columns: 1fr;
    }
}
.tn-method-card {
    background: #FAFAFC;
    border: 2px solid #E2E8F0;
    border-radius: 16px;
    padding: 30px 28px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: 20px;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    box-sizing: border-box;
    text-align: left;
}
.tn-method-card:hover {
    background: #FFFFFF;
    border-color: #0E1022;
    transform: translateY(-3px);
    box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
}
.tn-method-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.tn-method-badge {
    font-size: 11.5px;
    font-weight: 800;
    padding: 3.5px 9px;
    border-radius: 5px;
}
.tn-badge-qcm {
    background: #EFF6FF;
    color: #1D4ED8;
}
.tn-badge-bulletin {
    background: #F0FDF4;
    color: #15803D;
}
.tn-method-body {
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.tn-method-title {
    font-size: 19px;
    font-weight: 850;
    color: #0E1022;
    margin: 0;
}
.tn-method-desc {
    font-size: 14px;
    color: #64748B;
    line-height: 1.52;
    margin: 0;
}
.tn-method-features {
    display: flex;
    flex-direction: column;
    gap: 6px;
    border-top: 1px solid #E2E8F0;
    padding-top: 14px;
}
.tn-feat-line {
    font-size: 12.5px;
    color: #475569;
    display: flex;
    align-items: center;
    gap: 8px;
}
.btn-method-action {
    border: none;
    border-radius: 8px !important;
    box-shadow: none !important;
    padding: 14px 24px;
    font-size: 14px;
    font-weight: 850;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.15s ease;
}
.btn-method-qcm {
    background: #0E1022;
    color: #FFFFFF;
}
.btn-method-qcm:hover {
    background: #1e2238;
}
.btn-method-reports {
    background: #D4F25C;
    color: #0E1022;
}
.btn-method-reports:hover {
    background: #cbf046;
}

/* --------------------------------------------------------------------------
   BARRE DE RETOUR EN MODE ÉPREUVE (QUAND UN MODE EST ACTIF)
   -------------------------------------------------------------------------- */
.tn-back-bar {
    position: sticky;
    top: 0;
    z-index: 100;
    height: 46px;
    background: #FFFFFF;
    border-bottom: 1px solid #E2E8F0;
    padding: 0 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-sizing: border-box;
    width: 100% !important;
    max-width: 100% !important;
}
.btn-back-home {
    background: #FFFFFF;
    border: 1px solid #CBD5E1;
    border-radius: 6px;
    padding: 6px 14px;
    font-size: 12px;
    font-weight: 750;
    color: #334155;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.15s ease;
}
.btn-back-home:hover {
    background: #0E1022;
    color: #D4F25C;
    border-color: #0E1022;
}

/* --------------------------------------------------------------------------
   SECTION QCM & CONTENEURS
   -------------------------------------------------------------------------- */
.tn-test-section {
    display: none;
    flex-direction: column;
    gap: 20px;
    width: 100%;
}
.tn-blocs-progress-bar {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
}
.tn-bloc-step {
    background: #FAFAFC;
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    padding: 12px 16px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.tn-bloc-step.active {
    border-color: #0E1022;
    background: #0E1022;
    color: #FFFFFF;
}
.tn-bloc-step.completed {
    border-color: #CBD5E1;
    background: #F8FAFC;
}
.tn-bloc-num {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #F1F5F9;
    color: #0E1022;
    font-size: 11px;
    font-weight: 850;
    display: flex;
    align-items: center;
    justify-content: center;
}
.tn-bloc-step.active .tn-bloc-num {
    background: #D4F25C;
    color: #0E1022;
}
.tn-bloc-info {
    display: flex;
    flex-direction: column;
}
.tn-bloc-title {
    font-size: 12px;
    font-weight: 800;
}
.tn-bloc-sub {
    font-size: 10.5px;
    color: #64748B;
}
.tn-bloc-step.active .tn-bloc-sub {
    color: #94A3B8;
}

.tn-question-card {
    background: #FAFAFC;
    border: 1.5px solid #E2E8F0;
    border-radius: 16px;
    padding: 32px 36px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: 20px;
    box-sizing: border-box;
}
.tn-question-meta-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 14px;
}
.tn-subject-badge {
    background: #0E1022;
    color: #D4F25C;
    font-size: 11.5px;
    font-weight: 850;
    padding: 4px 10px;
    border-radius: 5px;
}
.tn-q-counter {
    font-size: 12px;
    font-weight: 750;
    color: #64748B;
}
.tn-prompt-box {
    margin-bottom: 18px;
}
.tn-question-prompt {
    font-size: 18px;
    font-weight: 800;
    color: #0E1022;
    line-height: 1.45;
    margin: 0;
}
.tn-options-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.tn-option-row {
    background: #FFFFFF;
    border: 1.5px solid #E2E8F0;
    border-radius: 10px;
    padding: 13px 18px;
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    transition: all 0.15s ease;
}
.tn-option-row:hover {
    border-color: #0E1022;
    background: #F8FAFC;
}
.tn-option-row.selected {
    background: #0E1022;
    border-color: #0E1022;
    color: #FFFFFF;
}
.tn-option-row.is-unknown {
    background: #F8FAFC;
    border-color: #CBD5E1;
    color: #475569;
}
.tn-option-row.is-unknown.selected {
    background: #334155;
    border-color: #334155;
    color: #FFFFFF;
}
.tn-opt-letter {
    width: 24px;
    height: 24px;
    border-radius: 5px;
    background: #F1F5F9;
    color: #0E1022;
    font-size: 11px;
    font-weight: 850;
    display: flex;
    align-items: center;
    justify-content: center;
    flex: none;
}
.tn-option-row.selected .tn-opt-letter {
    background: #D4F25C;
    color: #0E1022;
}
.tn-opt-text {
    font-size: 14px;
    font-weight: 600;
    line-height: 1.4;
}

.tn-actions-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 18px;
    border-top: 1px solid #E2E8F0;
    flex-wrap: wrap;
    gap: 12px;
}
.tn-kbd-hint {
    display: inline-flex;
    align-items: center;
    gap: 3px;
    font-size: 11px;
    color: #475569;
    background: #F1F5F9;
    padding: 2px 7px;
    border-radius: 5px;
    border: 1px solid #E2E8F0;
    font-weight: 650;
}
.tn-kbd-key {
    background: #FFFFFF;
    border: 1px solid #CBD5E1;
    border-radius: 3px;
    padding: 0 4px;
    font-size: 10px;
    font-weight: 850;
    color: #0E1022;
    box-shadow: 0 1px 1px rgba(0,0,0,0.05);
    font-family: inherit;
}
.btn-validate-step {
    background: #D4F25C;
    color: #0E1022;
    border: none;
    border-radius: 8px !important;
    box-shadow: none !important;
    padding: 13px 30px;
    font-size: 14px;
    font-weight: 850;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: background 0.15s ease;
}
.btn-validate-step:disabled {
    background: #E2E8F0;
    color: #94A3B8;
    cursor: not-allowed;
}
.btn-validate-step:not(:disabled):hover {
    background: #cbf046;
}

/* --------------------------------------------------------------------------
   SECTION BULLETINS SCOLAIRES
   -------------------------------------------------------------------------- */
.tn-reports-section {
    display: none;
    flex-direction: column;
    gap: 20px;
    width: 100%;
}
.tn-reports-card {
    background: #FAFAFC;
    border: 1.5px solid #E2E8F0;
    border-radius: 16px;
    padding: 32px 36px;
    display: flex;
    flex-direction: column;
    gap: 20px;
    box-sizing: border-box;
}
.tn-dropzone {
    border: 2px dashed #CBD5E1;
    border-radius: 12px;
    padding: 30px;
    text-align: center;
    background: #FFFFFF;
    cursor: pointer;
    transition: all 0.2s ease;
}
.tn-dropzone:hover {
    border-color: #0E1022;
    background: #F8FAFC;
}
.btn-submit-report {
    background: #D4F25C;
    color: #0E1022;
    border: none;
    border-radius: 8px !important;
    box-shadow: none !important;
    padding: 12px 26px;
    font-size: 13.5px;
    font-weight: 850;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: background 0.15s ease;
}
.btn-submit-report:hover {
    background: #cbf046;
}
.tn-saved-reports {
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.tn-report-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 14px;
    border-radius: 8px;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    font-size: 12.5px;
}

/* --------------------------------------------------------------------------
   SECTION RÉSULTATS & SCORE DU CALIBRAGE
   -------------------------------------------------------------------------- */
.tn-results-section {
    display: none;
    flex-direction: column;
    align-items: center;
    gap: 24px;
    width: 100%;
}
.tn-results-card {
    background: #FAFAFC;
    border: 1.5px solid #E2E8F0;
    border-radius: 18px;
    padding: 36px 40px;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 22px;
    width: 100%;
    box-sizing: border-box;
}
.tn-score-circle {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: #0E1022;
    color: #FFFFFF;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border: 4px solid #D4F25C;
    box-shadow: 0 10px 24px rgba(14, 16, 34, 0.18);
}
.tn-score-val {
    font-size: 34px;
    font-weight: 900;
    color: #D4F25C;
    line-height: 1;
}
.tn-score-denom {
    font-size: 11px;
    font-weight: 700;
    color: #94A3B8;
}
.tn-domains-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 12px;
    width: 100%;
}
.tn-domain-stat-box {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    padding: 12px 14px;
    display: flex;
    flex-direction: column;
    gap: 4px;
    text-align: left;
}
.tn-dsb-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.tn-dsb-title {
    font-size: 12px;
    font-weight: 800;
    color: #0E1022;
}
.tn-dsb-badge {
    font-size: 9.5px;
    font-weight: 800;
    padding: 2px 5px;
    border-radius: 4px;
}
.badge-priority {
    background: #FEF2F2;
    color: #EF4444;
}
.badge-solid {
    background: #F0FDF4;
    color: #16A34A;
}
.tn-dsb-score {
    font-size: 18px;
    font-weight: 900;
    color: #0E1022;
}
.tn-dsb-sub {
    font-size: 11px;
    color: #64748B;
}

.tn-upsell-box {
    background: #FFFFFF;
    border: 1.5px solid #CBD5E1;
    border-radius: 12px;
    padding: 18px 22px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    width: 100%;
    box-sizing: border-box;
    text-align: left;
    flex-wrap: wrap;
}
.btn-upsell-cta {
    background: #0E1022;
    color: #D4F25C;
    border: none;
    border-radius: 8px !important;
    box-shadow: none !important;
    padding: 10px 18px;
    font-size: 13px;
    font-weight: 850;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.btn-upsell-skip {
    background: transparent;
    border: none;
    color: #64748B;
    font-size: 12.5px;
    font-weight: 750;
    cursor: pointer;
    padding: 8px 14px;
    border-radius: 6px;
}
.btn-upsell-skip:hover {
    color: #0E1022;
    background: #E2E8F0;
}

.btn-open-parcours {
    background: #D4F25C;
    color: #0E1022;
    border: none;
    padding: 15px 40px;
    border-radius: 10px !important;
    box-shadow: none !important;
    font-size: 15.5px;
    font-weight: 850;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: background 0.15s ease, transform 0.15s ease;
}
.btn-open-parcours:hover {
    background: #cbf046;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(212, 242, 92, 0.4) !important;
}

/* --------------------------------------------------------------------------
   RESTITUTION COMPLÈTE DU DESIGN DU TEST DE NIVEAU (RECAP, QCM, SIDEBAR, RÉSULTATS)
   -------------------------------------------------------------------------- */

/* 1. ÉTAPE 4 : GRILLE RÉCAPITULATIF 3 COLONNES */
.recap-grid-3 {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    width: 100%;
    margin-top: 20px;
    box-sizing: border-box;
}
@media (max-width: 860px) {
    .recap-grid-3 {
        grid-template-columns: 1fr;
    }
}
.recap-item-card {
    background: #FAFCFE;
    border: 1.5px solid #E2E8F0;
    border-radius: 12px;
    padding: 20px 18px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: 14px;
    box-sizing: border-box;
    transition: all 0.15s ease;
}
.recap-item-card:hover {
    border-color: #CBD5E1;
    box-shadow: 0 4px 12px rgba(14, 16, 34, 0.04);
}
.ric-top {
    display: flex;
    align-items: center;
    gap: 8px;
}
.ric-num {
    width: 24px;
    height: 24px;
    border-radius: 6px;
    background: #0E1022;
    color: #D4F25C;
    font-family: 'Sora', sans-serif;
    font-size: 12px;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    flex: none;
}
.ric-category {
    font-size: 12px;
    font-weight: 800;
    color: #64748B;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}
.ric-body {
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.ric-val {
    font-family: 'Sora', sans-serif;
    font-size: 15px;
    font-weight: 800;
    color: #0E1022;
}
.ric-desc {
    font-size: 12px;
    color: #64748B;
    line-height: 1.4;
}
.ric-edit-btn {
    background: #FFFFFF;
    border: 1px solid #CBD5E1;
    border-radius: 6px;
    padding: 6px 12px;
    font-size: 11.5px;
    font-weight: 700;
    color: #0E1022;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    transition: all 0.15s ease;
    align-self: flex-start;
}
.ric-edit-btn:hover {
    background: #F1F5F9;
    border-color: #0E1022;
}

/* 2. ÉPREUVE QCM : GRILLE 2 COLONNES AVEC CARTE PRINCIPALE ET SIDEBAR */
.tn-quiz-grid {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 24px;
    align-items: start;
    width: 100%;
    box-sizing: border-box;
}
@media (max-width: 960px) {
    .tn-quiz-grid {
        grid-template-columns: 1fr;
    }
}
.tn-question-card {
    background: #FFFFFF;
    border: 1.5px solid #E2E8F0;
    border-radius: 14px;
    padding: 28px 32px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-height: 480px;
    box-sizing: border-box;
}

/* OPTIONS DE RÉPONSE INTERACTIVES DU QCM */
.tn-option-card {
    background: #FFFFFF;
    border: 1.5px solid #E2E8F0;
    border-radius: 10px;
    padding: 14px 18px;
    display: flex;
    align-items: center;
    gap: 14px;
    cursor: pointer;
    transition: all 0.15s ease;
    user-select: none;
    box-sizing: border-box;
}
.tn-option-card:hover {
    border-color: #CBD5E1;
    background: #F8FAFC;
}
.tn-option-card.selected {
    border-color: #0E1022;
    background: #FAFCFE;
    box-shadow: 0 0 0 1px #0E1022;
}
.tn-option-indicator {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    border: 2px solid #CBD5E1;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Sora', sans-serif;
    font-size: 11.5px;
    font-weight: 850;
    color: #64748B;
    flex-shrink: 0;
    transition: all 0.15s ease;
}
.tn-option-card.selected .tn-option-indicator {
    border-color: #0E1022;
    background: #0E1022;
    color: #D4F25C;
}
.tn-option-text {
    font-size: 14px;
    font-weight: 650;
    color: #1E293B;
    line-height: 1.4;
}

/* 5ÈME OPTION INTÉGRÉE : JE NE SAIS PAS ENCORE */
.tn-option-card.tn-option-unknown {
    background: #FAFCFE;
    border: 1.5px dashed #CBD5E1;
    margin-top: 6px;
}
.tn-option-card.tn-option-unknown:hover {
    border-color: #94A3B8;
    background: #F1F5F9;
}
.tn-option-card.tn-option-unknown.selected {
    border: 1.5px solid #0E1022;
    background: #F8FAFC;
    box-shadow: 0 0 0 1px #0E1022;
}
.tn-option-card.tn-option-unknown .tn-unknown-indicator {
    border-color: #94A3B8;
    color: #64748B;
    background: transparent;
    font-size: 13px;
    font-weight: 900;
}
.tn-option-card.tn-option-unknown.selected .tn-unknown-indicator {
    background: #0E1022;
    border-color: #0E1022;
    color: #D4F25C;
}

/* VOLET D'ACCOMPAGNEMENT LATÉRAL DU QCM (REPÈRES) */
.tn-side-card {
    background: #FFFFFF;
    border: 1.5px solid #E2E8F0;
    border-radius: 14px;
    padding: 22px 20px;
    display: flex;
    flex-direction: column;
    gap: 16px;
    box-sizing: border-box;
}
.tn-side-title {
    font-family: 'Sora', sans-serif;
    font-size: 13.5px;
    font-weight: 850;
    color: #0E1022;
    border-bottom: 1px solid #F1F5F9;
    padding-bottom: 8px;
}
.tn-timer-pill {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 12.5px;
    font-weight: 700;
    color: #475569;
}
.tn-timer-val {
    font-family: 'Sora', monospace;
    font-size: 14px;
    font-weight: 850;
    color: #0E1022;
    margin-left: auto;
}
.tn-discipline-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.tn-discipline-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 12.5px;
    padding: 5px 0;
    border-bottom: 1px solid #F8FAFC;
}
.tn-disp-name {
    font-weight: 700;
    color: #334155;
}
.tn-disp-coeff {
    font-size: 11px;
    color: #64748B;
    background: #F1F5F9;
    padding: 2px 7px;
    border-radius: 4px;
    font-weight: 600;
}
.tn-pedagogical-note {
    font-size: 12px;
    color: #64748B;
    background: #FFFBEB;
    border: 1px solid #FDE68A;
    border-radius: 8px;
    padding: 10px 12px;
    line-height: 1.45;
}

/* --------------------------------------------------------------------------
   3. SECTION RÉSULTATS : IMMERSIVE, CENTRÉE, DYNAMIQUE & SANS GRAND BOX
   -------------------------------------------------------------------------- */
#sectionResults,
.tn-results-card {
    display: none;
    background: transparent !important;
    border: none !important;
    border-radius: 0 !important;
    box-shadow: none !important;
    padding: 0 !important;
    box-sizing: border-box;
    flex-direction: column;
    align-items: center;
    width: 100%;
    max-width: 1240px;
    margin: auto 0 !important;
    gap: 22px;
    text-align: center;
}

/* Grand badge de note centré, prestigieux et dynamique */
.tn-score-hero-circle {
    width: 126px;
    height: 126px;
    border-radius: 50%;
    background: #0E1022;
    border: 4px solid #D4F25C;
    color: #FFFFFF;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    box-shadow: 0 12px 32px rgba(14, 16, 34, 0.25);
}
.tn-score-hero-circle .tn-score-large {
    font-size: 44px;
    font-weight: 900;
    line-height: 1;
    color: #D4F25C;
    font-family: 'Sora', sans-serif;
}
.tn-score-hero-circle .tn-score-denom {
    font-size: 13px;
    font-weight: 750;
    color: #94A3B8;
    margin-top: 3px;
}

/* Titre et contexte pédagogique */
.tn-res-titles-wrap {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
}
.tn-res-big-title {
    font-size: 30px;
    font-weight: 900;
    color: #0E1022;
    margin: 0;
    letter-spacing: -0.025em;
}
.tn-res-pedago-sub {
    font-size: 15.5px;
    color: #475569;
    max-width: 740px;
    margin: 0 auto;
    line-height: 1.6;
}

/* Grille des 5 disciplines : STRICTEMENT 5 COLONNES SUR 1 SEULE RANGÉE */
.tn-domain-summary-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr) !important;
    gap: 16px;
    width: 100%;
    box-sizing: border-box;
    margin: 6px 0;
}
.tn-domain-res-box {
    background: #FAFAFC;
    border: 1.5px solid #E2E8F0;
    border-radius: 14px;
    padding: 16px 18px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: 8px;
    box-sizing: border-box;
    text-align: left;
    min-height: 98px;
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}
.tn-domain-res-box:hover {
    background: #FFFFFF;
    border-color: #0E1022;
    transform: translateY(-2px);
    box-shadow: 0 8px 22px rgba(15, 23, 42, 0.08);
}
.tn-res-box-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    font-size: 13px;
    font-weight: 800;
    color: #0E1022;
    gap: 6px;
}
.tn-res-status-tag {
    font-size: 10px;
    font-weight: 800;
    padding: 2.5px 7px;
    border-radius: 5px;
    text-transform: uppercase;
    flex-shrink: 0;
}
.res-acquis { background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0; }
.res-consolider { background: #FFFBEB; color: #B45309; border: 1px solid #FDE68A; }
.res-urgent { background: #FEF2F2; color: #DC2626; border: 1px solid #FECACA; }

/* Actions principales d'activation */
.tn-res-actions-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    width: 100%;
    margin-top: 8px;
}
.btn-open-parcours {
    background: #D4F25C;
    color: #0E1022;
    border: none;
    border-radius: 12px;
    padding: 17px 50px;
    font-size: 16.5px;
    font-weight: 850;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: transform 0.15s ease, background 0.15s ease;
    box-shadow: 0 4px 18px rgba(212, 242, 92, 0.45);
}
.btn-open-parcours:hover {
    background: #cbf046;
    transform: translateY(-1px);
    box-shadow: 0 8px 26px rgba(212, 242, 92, 0.5);
}
.btn-direct-reports-link {
    background: transparent;
    border: none;
    color: #475569;
    font-size: 14px;
    font-weight: 750;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 16px;
    border-radius: 8px;
    transition: all 0.15s ease;
}
.btn-direct-reports-link:hover {
    color: #0E1022;
    background: #F1F5F9;
}
.tn-res-cta-sub {
    font-size: 13.5px;
    color: #64748B;
}

/* POP-UP MODAL : INTERFACE DE DÉCISION POUR AJOUTER LES BULLETINS */
.tn-modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(14, 16, 34, 0.65);
    backdrop-filter: blur(4px);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    box-sizing: border-box;
    animation: modalFadeIn 0.2s ease;
}
@keyframes modalFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
.tn-modal-dialog {
    background: #FFFFFF;
    border: 1.5px solid #CBD5E1;
    border-radius: 18px;
    padding: 32px 36px;
    max-width: 580px;
    width: 100%;
    box-shadow: 0 20px 45px rgba(14, 16, 34, 0.25);
    text-align: center;
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    box-sizing: border-box;
    animation: modalSlideUp 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}
@keyframes modalSlideUp {
    from { opacity: 0; transform: translateY(16px); }
    to { opacity: 1; transform: translateY(0); }
}
.tn-modal-close {
    position: absolute;
    top: 16px;
    right: 16px;
    background: #F1F5F9;
    border: none;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: #64748B;
    transition: all 0.15s ease;
}
.tn-modal-close:hover {
    background: #E2E8F0;
    color: #0E1022;
}
.tn-modal-icon-wrap {
    width: 54px;
    height: 54px;
    border-radius: 14px;
    background: #FAFAFC;
    border: 1.5px solid #E2E8F0;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 14px;
    color: #0E1022;
}
.tn-modal-badge {
    font-size: 11.5px;
    font-weight: 800;
    background: #EFF6FF;
    color: #0284c7;
    padding: 3px 10px;
    border-radius: 5px;
    margin-bottom: 10px;
}
.tn-modal-title {
    font-size: 20px;
    font-weight: 850;
    color: #0E1022;
    margin: 0 0 10px 0;
    line-height: 1.35;
    letter-spacing: -0.015em;
}
.tn-modal-desc {
    font-size: 13.5px;
    color: #64748B;
    line-height: 1.55;
    margin: 0 0 24px 0;
}
.tn-modal-choices {
    display: flex;
    flex-direction: column;
    gap: 10px;
    width: 100%;
}
.btn-modal-yes {
    background: #0E1022;
    color: #FFFFFF;
    border: none;
    border-radius: 10px;
    padding: 14px 24px;
    font-size: 14px;
    font-weight: 850;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.15s ease;
    width: 100%;
}
.btn-modal-yes:hover {
    background: #1C2038;
}
.btn-modal-no {
    background: transparent;
    color: #64748B;
    border: none;
    font-size: 13px;
    font-weight: 700;
    padding: 8px 16px;
    cursor: pointer;
    transition: color 0.15s ease;
}
.btn-modal-no:hover {
    color: #0E1022;
    text-decoration: underline;
}

@media (max-width: 900px) {
    .tn-domain-summary-grid {
        grid-template-columns: repeat(3, 1fr) !important;
    }
}
@media (max-width: 600px) {
    .tn-domain-summary-grid {
        grid-template-columns: 1fr 1fr !important;
    }
}

/* --------------------------------------------------------------------------
   SCANNER DE BULLETINS SCOLAIRES
   -------------------------------------------------------------------------- */
.tn-reports-section {
    display: none;
    flex-direction: column;
    gap: 20px;
}

.tn-reports-grid {
    display: grid;
    grid-template-columns: 1.2fr 1fr;
    gap: 20px;
}
@media (max-width: 990px) {
    .tn-reports-grid {
        grid-template-columns: 1fr;
    }
}

.tn-upload-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    padding: 24px;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    gap: 16px;
}
.tn-dropzone {
    border: 2px dashed #CBD5E1;
    border-radius: 10px;
    padding: 30px 20px;
    text-align: center;
    background: #FAFCFE;
    cursor: pointer;
    transition: all 0.15s ease;
}
.tn-dropzone:hover {
    border-color: #0E1022;
    background: #F1F5F9;
}
.tn-dropzone-icon {
    width: 44px;
    height: 44px;
    margin: 0 auto 10px auto;
    color: #14325c;
}
.tn-dropzone-text {
    font-size: 13.5px;
    font-weight: 750;
    color: #0E1022;
}
.tn-dropzone-hint {
    font-size: 11.5px;
    color: #64748B;
    margin-top: 4px;
}

.tn-grades-input-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
    gap: 10px;
    margin-top: 10px;
}
.tn-grade-field {
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.tn-grade-label {
    font-size: 11px;
    font-weight: 750;
    color: #475569;
}
.tn-grade-input {
    border: 1px solid #CBD5E1;
    border-radius: 6px;
    padding: 8px 10px;
    font-size: 13px;
    font-weight: 750;
    color: #0E1022;
    box-sizing: border-box;
}

.tn-saved-reports-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    padding: 24px;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    gap: 16px;
}
.tn-report-item {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 8px;
    padding: 12px 14px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
}
.tn-report-name {
    font-size: 13px;
    font-weight: 800;
    color: #0E1022;
}
.tn-report-status {
    font-size: 11px;
    font-weight: 750;
    padding: 2px 7px;
    border-radius: 4px;
    background: #EFF6FF;
    color: #1E40AF;
}
.tn-delete-btn {
    background: transparent;
    border: none;
    color: #DC2626;
    cursor: pointer;
    font-size: 11.5px;
    font-weight: 750;
    padding: 4px 6px;
}



/* --------------------------------------------------------------------------
   SCANNER DE BULLETINS SCOLAIRES
   -------------------------------------------------------------------------- */
.tn-reports-section {
    display: none;
    flex-direction: column;
    gap: 20px;
}

.tn-reports-grid {
    display: grid;
    grid-template-columns: 1.2fr 1fr;
    gap: 20px;
}
@media (max-width: 990px) {
    .tn-reports-grid {
        grid-template-columns: 1fr;
    }
}

.tn-upload-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    padding: 24px;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    gap: 16px;
}
.tn-dropzone {
    border: 2px dashed #CBD5E1;
    border-radius: 10px;
    padding: 30px 20px;
    text-align: center;
    background: #FAFCFE;
    cursor: pointer;
    transition: all 0.15s ease;
}
.tn-dropzone:hover {
    border-color: #0E1022;
    background: #F1F5F9;
}
.tn-dropzone-icon {
    width: 44px;
    height: 44px;
    margin: 0 auto 10px auto;
    color: #14325c;
}
.tn-dropzone-text {
    font-size: 13.5px;
    font-weight: 750;
    color: #0E1022;
}
.tn-dropzone-hint {
    font-size: 11.5px;
    color: #64748B;
    margin-top: 4px;
}

.tn-grades-input-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
    gap: 10px;
    margin-top: 10px;
}
.tn-grade-field {
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.tn-grade-label {
    font-size: 11px;
    font-weight: 750;
    color: #475569;
}
.tn-grade-input {
    border: 1px solid #CBD5E1;
    border-radius: 6px;
    padding: 8px 10px;
    font-size: 13px;
    font-weight: 750;
    color: #0E1022;
    box-sizing: border-box;
}

.tn-saved-reports-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    padding: 24px;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    gap: 16px;
}
.tn-report-item {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 8px;
    padding: 12px 14px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
}
.tn-report-name {
    font-size: 13px;
    font-weight: 800;
    color: #0E1022;
}
.tn-report-status {
    font-size: 11px;
    font-weight: 750;
    padding: 2px 7px;
    border-radius: 4px;
    background: #EFF6FF;
    color: #1E40AF;
}
.tn-delete-btn {
    background: transparent;
    border: none;
    color: #DC2626;
    cursor: pointer;
    font-size: 11.5px;
    font-weight: 750;
    padding: 4px 6px;
}



/* --------------------------------------------------------------------------
   SCANNER DE BULLETINS SCOLAIRES
   -------------------------------------------------------------------------- */
.tn-reports-section {
    display: none;
    flex-direction: column;
    gap: 20px;
}

.tn-reports-grid {
    display: grid;
    grid-template-columns: 1.2fr 1fr;
    gap: 20px;
}
@media (max-width: 990px) {
    .tn-reports-grid {
        grid-template-columns: 1fr;
    }
}

.tn-upload-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    padding: 24px;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    gap: 16px;
}
.tn-dropzone {
    border: 2px dashed #CBD5E1;
    border-radius: 10px;
    padding: 30px 20px;
    text-align: center;
    background: #FAFCFE;
    cursor: pointer;
    transition: all 0.15s ease;
}
.tn-dropzone:hover {
    border-color: #0E1022;
    background: #F1F5F9;
}
.tn-dropzone-icon {
    width: 44px;
    height: 44px;
    margin: 0 auto 10px auto;
    color: #14325c;
}
.tn-dropzone-text {
    font-size: 13.5px;
    font-weight: 750;
    color: #0E1022;
}
.tn-dropzone-hint {
    font-size: 11.5px;
    color: #64748B;
    margin-top: 4px;
}

.tn-grades-input-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
    gap: 10px;
    margin-top: 10px;
}
.tn-grade-field {
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.tn-grade-label {
    font-size: 11px;
    font-weight: 750;
    color: #475569;
}
.tn-grade-input {
    border: 1px solid #CBD5E1;
    border-radius: 6px;
    padding: 8px 10px;
    font-size: 13px;
    font-weight: 750;
    color: #0E1022;
    box-sizing: border-box;
}

.tn-saved-reports-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    padding: 24px;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    gap: 16px;
}
.tn-report-item {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 8px;
    padding: 12px 14px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
}
.tn-report-name {
    font-size: 13px;
    font-weight: 800;
    color: #0E1022;
}
.tn-report-status {
    font-size: 11px;
    font-weight: 750;
    padding: 2px 7px;
    border-radius: 4px;
    background: #EFF6FF;
    color: #1E40AF;
}
.tn-delete-btn {
    background: transparent;
    border: none;
    color: #DC2626;
    cursor: pointer;
    font-size: 11.5px;
    font-weight: 750;
    padding: 4px 6px;
}



/* --------------------------------------------------------------------------
   ÉTAPE 5 SUR 5 : CHOIX DE LA MÉTHODE D'ÉVALUATION (LES 2 GRANDES BOXES)
   -------------------------------------------------------------------------- */
.tn-profile-recap-banner {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    padding: 12px 18px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-sizing: border-box;
}
.tn-prb-left {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12.5px;
}
.tn-prb-tag {
    font-weight: 800;
    color: #64748B;
}
.tn-prb-val {
    font-weight: 850;
    color: #0E1022;
}
.tn-prb-edit-btn {
    background: #FFFFFF;
    border: 1px solid #CBD5E1;
    border-radius: 6px;
    padding: 5px 12px;
    font-size: 11.5px;
    font-weight: 750;
    color: #0E1022;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all 0.15s ease;
}
.tn-prb-edit-btn:hover {
    background: #0E1022;
    color: #D4F25C;
    border-color: #0E1022;
}

.tn-choice-cards-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 22px;
}
@media (max-width: 880px) {
    .tn-choice-cards-grid {
        grid-template-columns: 1fr;
    }
}

.tn-method-card {
    background: #FFFFFF;
    border: 2px solid #E2E8F0;
    border-radius: 14px;
    padding: 32px 30px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: 22px;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    box-sizing: border-box;
}
.tn-method-card:hover {
    border-color: #0E1022;
    transform: translateY(-2px);
    box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
}
.tn-method-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.tn-method-badge {
    font-size: 11.5px;
    font-weight: 800;
    padding: 4px 10px;
    border-radius: 6px;
}
.badge-qcm {
    background: #EFF6FF;
    color: #1E40AF;
}
.badge-reports {
    background: #ECFDF5;
    color: #047857;
}
.tn-method-time {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    font-weight: 700;
    color: #64748B;
}

.tn-method-body {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.tn-method-icon-wrap {
    width: 54px;
    height: 54px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.icon-qcm {
    background: #F1F5F9;
}
.icon-reports {
    background: #F0FDF4;
}
.tn-method-title {
    font-size: 22px;
    font-weight: 900;
    color: #0E1022;
    margin: 0;
    letter-spacing: -0.015em;
    line-height: 1.25;
}
.tn-method-desc {
    font-size: 13.5px;
    color: #475569;
    line-height: 1.45;
    margin: 0;
}
.tn-method-features {
    display: flex;
    flex-direction: column;
    gap: 8px;
    background: #F8FAFC;
    border-radius: 8px;
    padding: 12px 14px;
}
.tn-feat-line {
    font-size: 12px;
    color: #334155;
    font-weight: 650;
    display: flex;
    align-items: center;
    gap: 6px;
}

.btn-method-action {
    width: 100%;
    padding: 13px 20px;
    border-radius: 8px !important;
    font-size: 13.5px;
    font-weight: 850;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    border: none;
    box-shadow: none !important;
    transition: all 0.15s ease;
}
.btn-method-qcm {
    background: #D4F25C;
    color: #0E1022;
}
.btn-method-qcm:hover {
    background: #cbf046;
}
.btn-method-reports {
    background: #0E1022;
    color: #FFFFFF;
}
.btn-method-reports:hover {
    background: #1a1e3a;
}



/* --------------------------------------------------------------------------
   ESPACE DE TEST QCM INTERACTIF : LARGEUR OPTIMISÉE & TYPOGRAPHIE VISIBLE
   -------------------------------------------------------------------------- */
#sectionTest,
.tn-test-section {
    display: none;
    flex-direction: column;
    gap: 18px;
    width: 100%;
    max-width: 1360px;
    margin: 12px auto 32px;
    box-sizing: border-box;
}

.tn-blocs-progress-bar {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    width: 100%;
    margin-bottom: 6px;
}
.tn-bloc-step {
    background: #FAFAFC;
    border: 1.5px solid #E2E8F0;
    border-radius: 12px;
    padding: 12px 18px;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: all 0.2s ease;
}
.tn-bloc-step.active {
    background: #0E1022;
    border-color: #0E1022;
    box-shadow: 0 4px 16px rgba(14, 16, 34, 0.12);
}
.tn-bloc-num {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    background: #E2E8F0;
    color: #0E1022;
    font-size: 12px;
    font-weight: 850;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.tn-bloc-step.active .tn-bloc-num {
    background: #D4F25C;
    color: #0E1022;
}
.tn-bloc-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}
.tn-bloc-title {
    font-size: 13.5px;
    font-weight: 800;
    color: #0E1022;
}
.tn-bloc-step.active .tn-bloc-title {
    color: #FFFFFF;
}
.tn-bloc-sub {
    font-size: 11.5px;
    color: #64748B;
    font-weight: 500;
}
.tn-bloc-step.active .tn-bloc-sub {
    color: #94A3B8;
}

.tn-quiz-grid {
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 22px;
    align-items: start;
    width: 100%;
    box-sizing: border-box;
}
@media (max-width: 1024px) {
    .tn-quiz-grid {
        grid-template-columns: 1fr;
    }
}

.tn-question-card {
    background: #FFFFFF;
    border: 1.5px solid #E2E8F0;
    border-radius: 16px;
    padding: 28px 34px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-height: 480px;
    box-sizing: border-box;
}
.tn-question-meta-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}
.tn-subject-badge {
    background: #0E1022;
    color: #D4F25C;
    font-size: 12px;
    font-weight: 850;
    padding: 4px 12px;
    border-radius: 6px;
    letter-spacing: 0.02em;
}
.tn-q-counter {
    font-size: 13px;
    font-weight: 700;
    color: #64748B;
}
.tn-prompt-box {
    margin-bottom: 18px;
}
.tn-question-prompt {
    font-size: 19px;
    font-weight: 850;
    color: #0E1022;
    line-height: 1.45;
    margin: 0;
}
.tn-options-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 18px;
}
.tn-option-card {
    background: #FFFFFF;
    border: 1.5px solid #E2E8F0;
    border-radius: 12px;
    padding: 13px 18px;
    display: flex;
    align-items: center;
    gap: 14px;
    cursor: pointer;
    transition: all 0.15s ease;
    user-select: none;
    box-sizing: border-box;
}
.tn-option-card:hover {
    border-color: #CBD5E1;
    background: #F8FAFC;
}
.tn-option-card.selected {
    border-color: #0E1022;
    background: #FAFCFE;
    box-shadow: 0 0 0 1px #0E1022;
}
.tn-option-indicator {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    border: 2px solid #CBD5E1;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Sora', sans-serif;
    font-size: 12px;
    font-weight: 850;
    color: #64748B;
    flex-shrink: 0;
    transition: all 0.15s ease;
}
.tn-option-card.selected .tn-option-indicator {
    border-color: #0E1022;
    background: #0E1022;
    color: #D4F25C;
}
.tn-option-text {
    font-size: 14.5px;
    font-weight: 650;
    color: #1E293B;
    line-height: 1.45;
}
.tn-option-unknown {
    background: #FAFCFE;
    border: 1.5px dashed #CBD5E1;
    margin-top: 6px;
}
.tn-actions-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 14px;
    border-top: 1px solid #F1F5F9;
    gap: 14px;
    flex-wrap: wrap;
}
.btn-validate-step {
    background: #0E1022;
    color: #FFFFFF;
    border: none;
    border-radius: 10px;
    padding: 13px 28px;
    font-family: 'Sora', sans-serif;
    font-size: 14px;
    font-weight: 850;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.15s ease;
}
.btn-validate-step:hover:not(:disabled) {
    background: #1C2038;
}
.btn-validate-step:disabled {
    opacity: 0.45;
    cursor: not-allowed;
}

.tn-side-card {
    background: #FFFFFF;
    border: 1.5px solid #E2E8F0;
    border-radius: 16px;
    padding: 22px 20px;
    display: flex;
    flex-direction: column;
    gap: 16px;
    box-sizing: border-box;
}

</style>

<div class="tn-immersive-wrapper">
    
    {{-- EN-TÊTE IMMERSIF PLEINE LARGEUR INTÉGRÉ --}}
    <header class="tn-header-bar" id="wizardStepperBar">
        <div class="thb-left">
            <a href="/student/parcours" class="thb-brand" title="Retour au tableau de bord">
                <img src="/assets/9cf747549f194e113309855a69037902.png" alt="Logo" class="thb-logo">
                <span class="thb-brand-name">MonKayier</span>
            </a>
            <div class="thb-divider"></div>
            <span class="thb-subtext">Test de Niveau Général</span>
            <button type="button" class="wizard-back-btn" id="wizardBackBtn" onclick="goToPreviousWizardStep()" style="visibility:hidden">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                <span>Retour</span>
            </button>
        </div>

        <div class="thb-center">
            <div class="wizard-step-info" id="wizardStepInfo">
                Étape 1 sur 5 · Situation académique
            </div>
            <div class="thb-progress-rail">
                <div class="wizard-progress-fill" id="wizardProgressFill" style="width: 20%;"></div>
            </div>
        </div>

        <div class="thb-right">
            <button type="button" class="btn-fullscreen-toggle" onclick="toggleNativeFullscreen()" title="Basculer en plein écran navigateur (Raccourci: F)">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/></svg>
                <span>Plein écran</span>
            </button>
            <div class="wizard-step-pct" id="wizardStepPct">
                20%
            </div>
            @if(empty($isFirstTime))
            <a href="/student/parcours" class="btn-thb-exit" onclick="return confirm('Voulez-vous quitter le test de niveau et retourner au tableau de bord ?')" title="Quitter le test">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                <span>Quitter</span>
            </a>
            @else
            <span style="font-size:11.5px;font-weight:800;background:#FEF9C3;color:#854D0E;border:1px solid #FDE047;padding:4px 10px;border-radius:6px;">Étape initiale obligatoire</span>
            @endif
        </div>
    </header>

    {{-- BARRE DE RETOUR / BREADCRUMB COMPACTE PLEINE LARGEUR (AU SOMMET) --}}
    <header id="tnBackBar" class="tn-back-bar" style="display:none">
        <button type="button" class="btn-back-home" onclick="returnToMethodChoice()">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
            <span>Changer de mode d'évaluation</span>
        </button>
        <div style="font-size:12px;font-weight:800;color:#0E1022" id="currentModeLabel">
            Mode actif
        </div>
    </header>

    {{-- ZONE D'IMMERSION CENTRÉE (STAGE) --}}
    <main class="tn-stage">

    {{-- ========================================================================= --}}
    {{-- ÉTAPE 1 SUR 5 : SITUATION ACADÉMIQUE DE L'ÉLÈVE                          --}}
    {{-- ========================================================================= --}}
    <div id="stepPane1" class="wizard-step-pane active">
        <div class="wizard-card-container">
            @if(!empty($isFirstTime) || request('first_time'))
            <div style="background:#0E1022;color:#FFFFFF;border-radius:12px;padding:14px 18px;margin-bottom:8px;display:flex;align-items:center;justify-content:space-between;gap:14px;border:1px solid #1E293B;flex-wrap:wrap;">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:36px;height:36px;border-radius:8px;background:#D4F25C;color:#0E1022;display:flex;align-items:center;justify-content:center;font-weight:900;font-size:14px;flex-shrink:0;">01</div>
                    <div>
                        <div style="font-size:13.5px;font-weight:850;color:#D4F25C;">Bienvenue {{ $student->display_name ?? 'sur MonKayier' }} — Première étape de ton espace élève</div>
                        <div style="font-size:12px;color:#CBD5E1;margin-top:2px;">Ton profil démarre vierge. Ce diagnostic initial va déterminer ton jalon de départ et l'ordre de tes chapitres sur ton Parcours.</div>
                    </div>
                </div>
                <span style="font-size:11px;font-weight:800;background:rgba(212,242,92,0.14);color:#D4F25C;padding:4px 9px;border-radius:6px;white-space:nowrap;">Parcours vierge prêt à calibrer</span>
            </div>
            @endif
            <div class="wizard-step-header">
                <div class="wizard-step-badge">Calibrage Pédagogique · Étape 1 / 5</div>
                <h1 class="wizard-step-title">Quelle est votre situation académique en Terminale ?</h1>
                <p class="wizard-step-subtitle">L'algorithme adapte la profondeur des rappels de cours et le rythme de vos 16 jalons de révision selon votre expérience.</p>
            </div>

            <div class="immersive-grid-2">
                {{-- Choix 1 : Nouveau --}}
                <div class="immersive-choice-card selected" id="cardStatusNouveau" onclick="selectWizardOption('status', 'nouveau', this)">
                    <div class="icc-top">
                        <span class="icc-tag">Premier passage</span>
                        <div class="icc-radio-indicator"><div class="icc-radio-inner"></div></div>
                    </div>
                    <div class="icc-body">
                        <h2 class="icc-title">Nouvel élève de Terminale D</h2>
                        <p class="icc-desc">Vous découvrez le programme de Terminale cette année. Le parcours est progressif et suit l'apprentissage méthodique de chaque chapitre.</p>
                    </div>
                </div>

                {{-- Choix 2 : Redoublant --}}
                <div class="immersive-choice-card" id="cardStatusRedoublant" onclick="selectWizardOption('status', 'redoublant', this)">
                    <div class="icc-top">
                        <span class="icc-tag">Consolidation & Revanche</span>
                        <div class="icc-radio-indicator"><div class="icc-radio-inner"></div></div>
                    </div>
                    <div class="icc-body">
                        <h2 class="icc-title">Élève Redoublant</h2>
                        <p class="icc-desc">Vous avez déjà suivi la Terminale. L'algorithme cible directement vos blocages antérieurs pour éliminer vos lacunes et viser la mention.</p>
                    </div>
                </div>
            </div>

            <div class="wizard-footer-action">
                <button type="button" class="btn-wizard-next" onclick="goToWizardStep(2)">
                    <span>Continuer vers la période d'étude</span>
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- ========================================================================= --}}
    {{-- ÉTAPE 2 SUR 5 : PÉRIODE D'ÉTUDE ACTUELLE                                 --}}
    {{-- ========================================================================= --}}
    <div id="stepPane2" class="wizard-step-pane">
        <div class="wizard-card-container">
            <div class="wizard-step-header">
                <div class="wizard-step-badge">Calendrier Scolaire · Étape 2 / 5</div>
                <h1 class="wizard-step-title">À quelle période de l'année effectuez-vous cette évaluation ?</h1>
                <p class="wizard-step-subtitle">Le diagnostic tient compte des notions réellement étudiées en classe pour ne pas vous pénaliser sur les cours non encore vus.</p>
            </div>

            <div class="immersive-grid-4">
                {{-- Choix 1 : Début d'année --}}
                <div class="immersive-choice-card" onclick="selectWizardOption('period', 'debut', this)">
                    <div class="icc-top">
                        <span class="icc-tag">Sept - Nov</span>
                        <div class="icc-radio-indicator"><div class="icc-radio-inner"></div></div>
                    </div>
                    <div class="icc-body">
                        <h2 class="icc-title">Début d'année</h2>
                        <p class="icc-desc">Validation des pré-requis de Première et bases du Trimestre 1.</p>
                    </div>
                </div>

                {{-- Choix 2 : Milieu d'année (Défaut) --}}
                <div class="immersive-choice-card selected" onclick="selectWizardOption('period', 'milieu', this)">
                    <div class="icc-top">
                        <span class="icc-tag">Déc - Mars</span>
                        <div class="icc-radio-indicator"><div class="icc-radio-inner"></div></div>
                    </div>
                    <div class="icc-body">
                        <h2 class="icc-title">Milieu d'année</h2>
                        <p class="icc-desc">Trimestre 2 : fonctions, suites, cinématique et chimie organique.</p>
                    </div>
                </div>

                {{-- Choix 3 : Fin d'année --}}
                <div class="immersive-choice-card" onclick="selectWizardOption('period', 'fin', this)">
                    <div class="icc-top">
                        <span class="icc-tag">Avr - Juin</span>
                        <div class="icc-radio-indicator"><div class="icc-radio-inner"></div></div>
                    </div>
                    <div class="icc-body">
                        <h2 class="icc-title">Fin d'année & Sprint BAC</h2>
                        <p class="icc-desc">Révision intensive type BAC et consolidation des dissertations.</p>
                    </div>
                </div>

                {{-- Choix 4 : Vacances --}}
                <div class="immersive-choice-card" onclick="selectWizardOption('period', 'vacances', this)">
                    <div class="icc-top">
                        <span class="icc-tag">Juil - Août</span>
                        <div class="icc-radio-indicator"><div class="icc-radio-inner"></div></div>
                    </div>
                    <div class="icc-body">
                        <h2 class="icc-title">Grandes Vacances</h2>
                        <p class="icc-desc">Remise à niveau estivale et anticipation de la rentrée.</p>
                    </div>
                </div>
            </div>

            <div class="wizard-footer-action">
                <button type="button" class="btn-wizard-next" onclick="goToWizardStep(3)">
                    <span>Continuer vers mon objectif BAC</span>
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- ========================================================================= --}}
    {{-- ÉTAPE 3 SUR 5 : OBJECTIF & AMBITION VISÉE AU BAC 2                       --}}
    {{-- ========================================================================= --}}
    <div id="stepPane3" class="wizard-step-pane">
        <div class="wizard-card-container">
            <div class="wizard-step-header">
                <div class="wizard-step-badge">Objectif de Réussite · Étape 3 / 5</div>
                <h1 class="wizard-step-title">Quelle est votre ambition visée au BAC 2 Série D ?</h1>
                <p class="wizard-step-subtitle">Le niveau d'exigence des exercices et le seuil de déblocage de vos étapes s'ajustent à votre cible.</p>
            </div>

            <div class="immersive-grid-3">
                {{-- Choix 1 : Sécuriser --}}
                <div class="immersive-choice-card" onclick="selectWizardOption('ambition', 'reussite', this)">
                    <div class="icc-top">
                        <span class="icc-tag">Moyenne 10 - 12</span>
                        <div class="icc-radio-indicator"><div class="icc-radio-inner"></div></div>
                    </div>
                    <div class="icc-body">
                        <h2 class="icc-title">Sécuriser le BAC</h2>
                        <p class="icc-desc">Focus absolu sur les questions récurrentes, les définitions clés et la méthode pour assurer l'admission sans stress.</p>
                    </div>
                </div>

                {{-- Choix 2 : Viser la Mention (Défaut) --}}
                <div class="immersive-choice-card selected" onclick="selectWizardOption('ambition', 'mention', this)">
                    <div class="icc-top">
                        <span class="icc-tag">Moyenne 14 - 17</span>
                        <div class="icc-radio-indicator"><div class="icc-radio-inner"></div></div>
                    </div>
                    <div class="icc-body">
                        <h2 class="icc-title">Viser la Mention</h2>
                        <p class="icc-desc">Approfondissement méthodique, rigueur scientifique et maîtrise des problèmes de synthèse à plusieurs paliers.</p>
                    </div>
                </div>

                {{-- Choix 3 : Excellence --}}
                <div class="immersive-choice-card" onclick="selectWizardOption('ambition', 'excellence', this)">
                    <div class="icc-top">
                        <span class="icc-tag">Moyenne 18+</span>
                        <div class="icc-radio-indicator"><div class="icc-radio-inner"></div></div>
                    </div>
                    <div class="icc-body">
                        <h2 class="icc-title">Excellence & Concours</h2>
                        <p class="icc-desc">Préparation d'élite pour les filières très sélectives (Médecine, Écoles d'Ingénieurs, Bourses internationales).</p>
                    </div>
                </div>
            </div>

            <div class="wizard-footer-action">
                <button type="button" class="btn-wizard-next" onclick="goToWizardStep(4)">
                    <span>Vérifier mon récapitulatif de profil</span>
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- ========================================================================= --}}
    {{-- ÉTAPE 4 SUR 5 : INTERFACE INTERMÉDIAIRE DE RÉCAPITULATIF DU PROFIL        --}}
    {{-- ========================================================================= --}}
    <div id="stepPane4" class="wizard-step-pane">
        <div class="wizard-card-container">
            <div class="wizard-step-header">
                <div class="wizard-step-badge">Calibrage Pédagogique · Étape 4 / 5</div>
                <h1 class="wizard-step-title">Récapitulatif de votre profil d'apprentissage</h1>
                <p class="wizard-step-subtitle">Vérifiez les paramètres de votre diagnostic. L'algorithme MonKayier utilisera ces critères pour calibrer le niveau des questions et la priorité de vos 16 jalons.</p>
            </div>

            {{-- Grille des 3 paramètres récapitulatifs --}}
            <div class="recap-grid-3">
                
                {{-- Box 1 : Situation académique --}}
                <div class="recap-item-card">
                    <div class="ric-top">
                        <span class="ric-num">1</span>
                        <span class="ric-category">Situation en classe</span>
                    </div>
                    <div class="ric-body">
                        <div class="ric-val" id="recapStatusTitle">Nouvel élève de Terminale D</div>
                        <div class="ric-desc" id="recapStatusDesc">Premier passage en Terminale · Parcours standard d'apprentissage progressif</div>
                    </div>
                    <button type="button" class="ric-edit-btn" onclick="goToWizardStep(1)">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                        <span>Modifier</span>
                    </button>
                </div>

                {{-- Box 2 : Période d'évaluation --}}
                <div class="recap-item-card">
                    <div class="ric-top">
                        <span class="ric-num">2</span>
                        <span class="ric-category">Calendrier scolaire</span>
                    </div>
                    <div class="ric-body">
                        <div class="ric-val" id="recapPeriodTitle">Milieu d'année (Trimestre 2)</div>
                        <div class="ric-desc" id="recapPeriodDesc">Prise en compte des chapitres en cours d'étude (fonctions, suites, cinématique)</div>
                    </div>
                    <button type="button" class="ric-edit-btn" onclick="goToWizardStep(2)">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                        <span>Modifier</span>
                    </button>
                </div>

                {{-- Box 3 : Ambition ciblée --}}
                <div class="recap-item-card">
                    <div class="ric-top">
                        <span class="ric-num">3</span>
                        <span class="ric-category">Objectif au BAC 2</span>
                    </div>
                    <div class="ric-body">
                        <div class="ric-val" id="recapAmbitionTitle">Viser la Mention (14 à 17 / 20)</div>
                        <div class="ric-desc" id="recapAmbitionDesc">Approfondissement méthodique, rigueur scientifique et problèmes de synthèse</div>
                    </div>
                    <button type="button" class="ric-edit-btn" onclick="goToWizardStep(3)">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                        <span>Modifier</span>
                    </button>
                </div>

            </div>

            {{-- Encadré de décision interactif --}}
            <div class="recap-decision-box">
                <div class="rdb-top">
                    <div class="rdb-title">Ces paramètres correspondent-ils à votre situation ?</div>
                    <div class="rdb-sub">Vous pouvez valider ce profil pour passer au choix de votre mode d'évaluation, ou retourner en arrière pour modifier vos réponses.</div>
                </div>
                <div class="rdb-actions">
                    <button type="button" class="btn-recap-back" onclick="goToWizardStep(3)">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                        <span>Modifier mes critères</span>
                    </button>
                    <button type="button" class="btn-wizard-next" onclick="goToWizardStep(5)">
                        <span>Continuer avec ce profil et choisir mon mode d'évaluation</span>
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                    </button>
                </div>
            </div>

        </div>
    </div>

    {{-- ========================================================================= --}}
    {{-- ÉTAPE 5 SUR 5 : CHOIX DE LA MÉTHODE D'ÉVALUATION (LES 2 GRANDES BOXES)    --}}
    {{-- ========================================================================= --}}
        <div id="stepPane5" class="wizard-step-pane">

        <div class="wizard-card-container">
            <div class="wizard-step-header">
                <div class="wizard-step-badge">Diagnostic Initial · Étape 5 / 5</div>
                <h1 class="wizard-step-title">Comment souhaitez-vous évaluer votre niveau pour le BAC 2 ?</h1>
                <p class="wizard-step-subtitle">Choisissez votre méthode pour mesurer vos compétences et générer votre parcours d'apprentissage personnalisé.</p>
            </div>

            <div class="tn-choice-cards-grid">
                
                {{-- GRANDE BOX 1 : ÉVALUATION PAR QCM --}}
                <div class="tn-method-card" onclick="startTestMode()">
                    <div class="tn-method-top">
                        <span class="tn-method-badge badge-qcm">Évaluation Directe</span>
                        <span class="tn-method-time">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <span>15 à 20 min</span>
                        </span>
                    </div>

                    <div class="tn-method-body">
                        <div class="tn-method-icon-wrap icon-qcm">
                            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#14325c" stroke-width="2.2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                        </div>
                        <h2 class="tn-method-title">Épreuve Diagnostique QCM</h2>
                        <p class="tn-method-desc">
                            16 questions ciblées selon le programme officiel togolais (Maths, PC, SVT, Français, Philo).
                        </p>
                    </div>

                    <div class="tn-method-features">
                        <div class="tn-feat-line">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                            <span>Questions calibrées sur votre trimestre</span>
                        </div>
                        <div class="tn-feat-line">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                            <span>Résultat immédiat et parcours instantané</span>
                        </div>
                    </div>

                    <button type="button" class="btn-method-action btn-method-qcm">
                        <span>Commencer l'épreuve QCM</span>
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                    </button>
                </div>

                {{-- GRANDE BOX 2 : ANALYSE DE BULLETINS --}}
                <div class="tn-method-card" onclick="startReportsMode()">
                    <div class="tn-method-top">
                        <span class="tn-method-badge badge-reports">Historique de Classe</span>
                        <span class="tn-method-time">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <span>2 minutes</span>
                        </span>
                    </div>

                    <div class="tn-method-body">
                        <div class="tn-method-icon-wrap icon-reports">
                            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                        </div>
                        <h2 class="tn-method-title">Analyse de Bulletins Scolaires</h2>
                        <p class="tn-method-desc">
                            Importez la photo ou le scan de vos bulletins pour croiser vos notes de classe réelles et vos appréciations pédagogiques.
                        </p>
                    </div>

                    <div class="tn-method-features">
                        <div class="tn-feat-line">● Prise en compte de votre progression continue</div>
                        <div class="tn-feat-line">● Analyse documentaire par IA de vos appréciations</div>
                        <div class="tn-feat-line">● Précision historique de 90% pour votre parcours</div>
                    </div>

                    <button type="button" class="btn-method-action btn-method-reports">
                        <span>Importer mes bulletins scolaires</span>
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                    </button>
                </div>

            </div>
        </div>
    </div>

    

    {{-- ========================================================================= --}}
    {{-- ÉCRAN DE L'ÉPREUVE DIAGNOSTIQUE QCM (16 QUESTIONS)                        --}}
    {{-- ========================================================================= --}}
    <div id="sectionTest" class="tn-test-section">
        
        {{-- Barre des 3 Blocs --}}
        <div class="tn-blocs-progress-bar">
            <div class="tn-bloc-step active" id="blocStep1">
                <div class="tn-bloc-num">1</div>
                <div class="tn-bloc-info">
                    <span class="tn-bloc-title">Sciences Exactes</span>
                    <span class="tn-bloc-sub">Maths & Phys.-Chimie · Q1 à Q8</span>
                </div>
            </div>
            <div class="tn-bloc-step" id="blocStep2">
                <div class="tn-bloc-num">2</div>
                <div class="tn-bloc-info">
                    <span class="tn-bloc-title">Sciences du Vivant</span>
                    <span class="tn-bloc-sub">SVT · Q9 à Q12</span>
                </div>
            </div>
            <div class="tn-bloc-step" id="blocStep3">
                <div class="tn-bloc-num">3</div>
                <div class="tn-bloc-info">
                    <span class="tn-bloc-title">Expression & Méthode</span>
                    <span class="tn-bloc-sub">Français & Philo · Q13 à Q16</span>
                </div>
            </div>
        </div>

        {{-- Grille Question + Volet Latéral --}}
        <div class="tn-quiz-grid">
            
            {{-- Carte de question interactive --}}
            <div class="tn-question-card" id="questionCard">
                <div>
                    <div class="tn-question-meta-row">
                        <span class="tn-subject-badge" id="subjectBadge">Mathématiques · Coeff. 4</span>
                        <span class="tn-q-counter" id="qCounterText">Question 1 sur 16</span>
                    </div>

                    <div class="tn-prompt-box">
                        <h2 class="tn-question-prompt" id="qPromptText">Chargement de la question...</h2>
                    </div>

                    <div class="tn-options-list" id="optionsContainer">
                        <!-- Options A, B, C, D + Option Je ne sais pas encore injectées dynamiquement par JS -->
                    </div>
                </div>

                <div class="tn-actions-row">
                    <div style="font-size: 12px; color: #64748B; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        <span>Sélectionnez ou naviguez au clavier :</span>
                        <span class="tn-kbd-hint"><span class="tn-kbd-key">↑</span><span class="tn-kbd-key">↓</span> défiler</span>
                        <span class="tn-kbd-hint"><span class="tn-kbd-key">A</span><span class="tn-kbd-key">B</span><span class="tn-kbd-key">C</span><span class="tn-kbd-key">D</span> choix</span>
                        <span class="tn-kbd-hint"><span class="tn-kbd-key">Entrée ↵</span> valider</span>
                    </div>

                    <button type="button" class="btn-validate-step" id="validateBtn" onclick="nextQuestion()" disabled>
                        <span>Valider et continuer</span>
                        <span style="font-size: 11px; opacity: 0.7; font-weight: 600; margin-left: 2px;">(Entrée ↵)</span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                    </button>
                </div>
            </div>

            {{-- Volet d'accompagnement latéral --}}
            <div class="tn-side-card">
                <div class="tn-side-title">Repères du Test de Niveau Général</div>

                <div class="tn-timer-pill">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <span>Temps écoulé :</span>
                    <span class="tn-timer-val" id="timerDisplay">00:00</span>
                </div>

                <div class="tn-discipline-list">
                    <div class="tn-discipline-item">
                        <span class="tn-disp-name">Mathématiques</span>
                        <span class="tn-disp-coeff">Coeff. 4 (4 Q)</span>
                    </div>
                    <div class="tn-discipline-item">
                        <span class="tn-disp-name">Physique-Chimie</span>
                        <span class="tn-disp-coeff">Coeff. 4 (4 Q)</span>
                    </div>
                    <div class="tn-discipline-item">
                        <span class="tn-disp-name">SVT</span>
                        <span class="tn-disp-coeff">Coeff. 4 (4 Q)</span>
                    </div>
                    <div class="tn-discipline-item">
                        <span class="tn-disp-name">Français</span>
                        <span class="tn-disp-coeff">Coeff. 2 (2 Q)</span>
                    </div>
                    <div class="tn-discipline-item">
                        <span class="tn-disp-name">Philosophie</span>
                        <span class="tn-disp-coeff">Coeff. 2 (2 Q)</span>
                    </div>
                </div>

                <div class="tn-pedagogical-note">
                    <strong>Règle d'or :</strong> Ne répondez pas au hasard. Si une notion n'a pas encore été vue en classe, cliquez sur <em>« Je ne sais pas encore »</em>. Cela permet à l'algorithme d'insérer le bon rappel de cours au bon endroit dans votre parcours.
                </div>
            </div>

        </div>

    </div>

    {{-- ========================================================================= --}}
    {{-- SCANNER DE BULLETINS SCOLAIRES                                            --}}
    {{-- ========================================================================= --}}
    <div id="sectionReports" class="tn-reports-section">
        
        <div class="tn-reports-grid">
            
                        {{-- Formulaire de saisie confidentielle et directe des notes de bulletin --}}
            <div class="tn-upload-card" style="padding: 24px 26px;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 14px;">
                    <div>
                        <div class="tn-side-title" style="margin-bottom: 3px;">Saisie Confidentielle des Notes de Classe</div>
                        <div style="font-size: 12px; color: #64748B;">Renseignez directement vos moyennes de cours. Vos données restent strictement privées et sécurisées.</div>
                    </div>
                    <span style="background: #F0FDF4; border: 1px solid #BBF7D0; color: #166534; font-size: 11px; font-weight: 800; padding: 3px 8px; border-radius: 6px;">100% Confidentiel</span>
                </div>

                {{-- Message d'enregistrement fluide (sans reload) --}}
                <div id="reportCardSuccessAlert" style="display:none;background:#ECFDF5;border:1.5px solid #A7F3D0;color:#065F46;padding:12px 16px;border-radius:10px;margin-bottom:14px;font-size:13px;font-weight:700;align-items:center;gap:10px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    <span id="reportCardSuccessText">Bulletin enregistré avec succès ! Vos notes ont été prises en compte.</span>
                </div>
                
                <form id="reportCardForm" onsubmit="handleUploadReportCard(event)">
                    @csrf
                    
                    {{-- 1. Système d'évaluation & Période --}}
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;background:#F8FAFC;padding:12px;border-radius:10px;border:1px solid #E2E8F0;">
                        <div>
                            <label class="tn-grade-label" style="font-size: 11.5px; font-weight: 800; margin-bottom: 5px; display: block;">Organisation de l'établissement</label>
                            <select id="systemTypeSelect" class="tn-grade-input" style="width:100%" onchange="updatePeriodOptions()">
                                <option value="trimestre" selected>Système Trimestriel (3 trimestres)</option>
                                <option value="semestre">Système Semestriel (2 semestres)</option>
                            </select>
                        </div>
                        <div>
                            <label class="tn-grade-label" style="font-size: 11.5px; font-weight: 800; margin-bottom: 5px; display: block;">Période du bulletin</label>
                            <select name="term" id="termPeriodSelect" class="tn-grade-input" style="width:100%">
                                <option value="Trimestre 1" selected>1er Trimestre</option>
                                <option value="Trimestre 2">2ème Trimestre</option>
                                <option value="Trimestre 3">3ème Trimestre</option>
                                <option value="Bilan Annuel">Bilan Annuel Complet</option>
                            </select>
                        </div>
                    </div>

                    <input type="hidden" name="school_year" value="2026-2027">
                    <input type="hidden" name="class_level" value="Terminale D">

                    {{-- 2. Grille des matières officielles de la série avec coefficients et notes --}}
                    <div style="margin-bottom: 14px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <span style="font-size: 12.5px; font-weight: 850; color: #0E1022;">Matières officielles (Terminale D) :</span>
                            <span style="font-size: 11.5px; color: #64748B;">Moyenne pondérée : <strong id="liveCalculatedAvg" style="color:#0E1022;">-- / 20</strong></span>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            {{-- Maths --}}
                            <div style="display: flex; align-items: center; justify-content: space-between; background: #FFFFFF; border: 1px solid #E2E8F0; padding: 10px 14px; border-radius: 8px;">
                                <div>
                                    <div style="font-size: 13px; font-weight: 800; color: #0E1022;">Mathématiques</div>
                                    <div style="font-size: 11px; color: #64748B; font-weight: 600;">Coefficient 4 · Épreuve majeure</div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <input type="number" step="0.25" min="0" max="20" name="grades[Mathématiques]" class="tn-grade-input bullet-input" data-coeff="4" placeholder="ex: 13.5" style="width: 85px; text-align: center; font-size: 14px; font-weight: 850;" oninput="computeLiveAverage()">
                                    <span style="font-size: 12px; font-weight: 750; color: #64748B;">/ 20</span>
                                </div>
                            </div>

                            {{-- Physique-Chimie --}}
                            <div style="display: flex; align-items: center; justify-content: space-between; background: #FFFFFF; border: 1px solid #E2E8F0; padding: 10px 14px; border-radius: 8px;">
                                <div>
                                    <div style="font-size: 13px; font-weight: 800; color: #0E1022;">Physique-Chimie</div>
                                    <div style="font-size: 11px; color: #64748B; font-weight: 600;">Coefficient 4 · Épreuve majeure</div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <input type="number" step="0.25" min="0" max="20" name="grades[Physique-Chimie]" class="tn-grade-input bullet-input" data-coeff="4" placeholder="ex: 12.0" style="width: 85px; text-align: center; font-size: 14px; font-weight: 850;" oninput="computeLiveAverage()">
                                    <span style="font-size: 12px; font-weight: 750; color: #64748B;">/ 20</span>
                                </div>
                            </div>

                            {{-- SVT --}}
                            <div style="display: flex; align-items: center; justify-content: space-between; background: #FFFFFF; border: 1px solid #E2E8F0; padding: 10px 14px; border-radius: 8px;">
                                <div>
                                    <div style="font-size: 13px; font-weight: 800; color: #0E1022;">Sciences de la Vie et de la Terre (SVT)</div>
                                    <div style="font-size: 11px; color: #64748B; font-weight: 600;">Coefficient 4 · Épreuve majeure</div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <input type="number" step="0.25" min="0" max="20" name="grades[SVT]" class="tn-grade-input bullet-input" data-coeff="4" placeholder="ex: 14.0" style="width: 85px; text-align: center; font-size: 14px; font-weight: 850;" oninput="computeLiveAverage()">
                                    <span style="font-size: 12px; font-weight: 750; color: #64748B;">/ 20</span>
                                </div>
                            </div>

                            {{-- Français --}}
                            <div style="display: flex; align-items: center; justify-content: space-between; background: #FFFFFF; border: 1px solid #E2E8F0; padding: 10px 14px; border-radius: 8px;">
                                <div>
                                    <div style="font-size: 13px; font-weight: 800; color: #0E1022;">Français</div>
                                    <div style="font-size: 11px; color: #64748B; font-weight: 600;">Coefficient 2 · Écrit & Méthodologie</div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <input type="number" step="0.25" min="0" max="20" name="grades[Français]" class="tn-grade-input bullet-input" data-coeff="2" placeholder="ex: 11.5" style="width: 85px; text-align: center; font-size: 14px; font-weight: 850;" oninput="computeLiveAverage()">
                                    <span style="font-size: 12px; font-weight: 750; color: #64748B;">/ 20</span>
                                </div>
                            </div>

                            {{-- Philosophie --}}
                            <div style="display: flex; align-items: center; justify-content: space-between; background: #FFFFFF; border: 1px solid #E2E8F0; padding: 10px 14px; border-radius: 8px;">
                                <div>
                                    <div style="font-size: 13px; font-weight: 800; color: #0E1022;">Philosophie</div>
                                    <div style="font-size: 11px; color: #64748B; font-weight: 600;">Coefficient 2 · Écrit & Réflexion</div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <input type="number" step="0.25" min="0" max="20" name="grades[Philosophie]" class="tn-grade-input bullet-input" data-coeff="2" placeholder="ex: 13.0" style="width: 85px; text-align: center; font-size: 14px; font-weight: 850;" oninput="computeLiveAverage()">
                                    <span style="font-size: 12px; font-weight: 750; color: #64748B;">/ 20</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" id="submitReportBtn" class="btn-validate-step" style="width:100%;justify-content:center;margin-top:14px;padding:14px 20px;font-size:14.5px;">
                        <span>Enregistrer ces notes & calibrer mon parcours à 95%</span>
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                    </button>
                </form>
            </div>

            {{-- Liste des bulletins enregistrés --}}
            <div class="tn-saved-reports-card">
                <div class="tn-side-title" id="savedReportsTitle">Mes Bulletins Enregistrés ({{ count($reportCards) }})</div>

                <div style="margin-top: 10px; padding-bottom: 10px; border-bottom: 1px solid #E2E8F0;">
                    <button type="button" class="btn-method-action btn-method-qcm" id="btnActivateParcoursWithReports" onclick="goToPersonalizedParcoursWithReports()">
                        <span>Activer et ouvrir mon parcours calibré avec mes bulletins</span>
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                    </button>
                </div>

                <div style="display:{{ count($reportCards) > 0 ? 'flex' : 'none' }};flex-direction:column;gap:10px" id="savedReportsList">
                    @foreach($reportCards as $rc)
                        <div class="tn-report-item" id="reportCardRow-{{ $rc->id }}">
                            <div>
                                <div class="tn-report-name">{{ $rc->title }}</div>
                                <div style="font-size:11px;color:#64748B;margin-top:2px">
                                    {{ $rc->school_year }} · 
                                    @if($rc->overall_average)
                                        Moyenne : <strong>{{ $rc->overall_average }}/20</strong> ·
                                    @endif
                                    <span class="tn-report-status">{{ $rc->ai_status === 'pending_analysis' ? 'Analyse IA programmée' : 'Vérifié' }}</span>
                                </div>
                            </div>
                            <div class="tn-del-wrap" style="display:flex;align-items:center;">
                                <button type="button" class="tn-delete-btn" id="delBtn-{{ $rc->id }}" onclick="askDeleteReportCard('{{ $rc->id }}')">
                                    Supprimer
                                </button>
                                <div id="delConfirmBox-{{ $rc->id }}" style="display:none;align-items:center;gap:6px;">
                                    <span style="font-size:11px;font-weight:800;color:#DC2626;">Supprimer ?</span>
                                    <button type="button" onclick="executeDeleteReportCard('{{ $rc->id }}')" style="background:#DC2626;color:#FFFFFF;border:none;border-radius:4px;padding:3px 8px;font-size:11px;font-weight:800;cursor:pointer;">Oui</button>
                                    <button type="button" onclick="cancelDeleteReportCard('{{ $rc->id }}')" style="background:#E2E8F0;color:#0E1022;border:none;border-radius:4px;padding:3px 8px;font-size:11px;font-weight:700;cursor:pointer;">Non</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div style="display:{{ count($reportCards) === 0 ? 'block' : 'none' }};padding:24px;text-align:center;color:#64748B;font-size:12.5px" id="emptyReportsMsg">
                    Aucun bulletin scolaire importé pour le moment. Vous pouvez en importer un pour affiner la précision de votre parcours à 95%.
                </div>

                <div class="tn-pedagogical-note" style="margin-top:auto">
                    <strong>Indice de fiabilité :</strong> Un test de niveau seul calibre votre parcours avec une précision de <strong>82%</strong>. L'ajout de 1 ou 2 bulletins scolaires porte la fiabilité à <strong>95%</strong> en intégrant la régularité de vos résultats en classe.
                </div>
            </div>

        </div>

    </div>

    {{-- ========================================================================= --}}
    {{-- ÉCRAN DE BILAN & VALIDATION DU PARCOURS (CENTRÉ, DYNAMIQUE & SANS GRAND BOX) --}}
    <div id="sectionResults" class="tn-results-card">
        
        {{-- 1. Grand badge de note centré, prestigieux et dynamique --}}
        <div class="tn-score-hero-circle">
            <span class="tn-score-large" id="resScoreVal">13.5</span>
            <span class="tn-score-denom">sur 20</span>
        </div>

        {{-- 2. Titre et diagnostic pédagogique officiel --}}
        <div class="tn-res-titles-wrap">
            <h1 class="tn-res-big-title">Test de Niveau Général Validé !</h1>
            <div class="tn-res-pedago-sub">
                Votre niveau initial a été évalué avec succès pour le programme du <strong>BAC 2 Série D</strong>.<br>
                Mention estimée : <strong style="color:#0E1022" id="resMentionVal">Renforcement impératif</strong> · 
                Indice de fiabilité : <strong style="color:#059669" id="resReliabilityVal">82%</strong>.
            </div>
        </div>

        {{-- 3. Synthèse des 5 matières : 5 colonnes spacieuses sur UNE SEULE ligne --}}
        <div class="tn-domain-summary-grid" id="resDomainsGrid">
            <!-- Injecté par JavaScript -->
        </div>

        {{-- 4. Actions d'activation du parcours & passerelle bulletins --}}
        <div class="tn-res-actions-container">
            <button type="button" class="btn-open-parcours" onclick="handleFinishWithModal()">
                <span>Activer et ouvrir mon parcours personnalisé sur le tableau de bord</span>
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
            </button>
            
            <button type="button" class="btn-direct-reports-link" onclick="startReportsMode()">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                <span>Ajouter mes bulletins scolaires pour affiner à 95% de précision</span>
            </button>

            <div class="tn-res-cta-sub">
                Les 16 jalons de révision ont été automatiquement calibrés selon vos forces et faiblesses.
            </div>
        </div>

    </div>

    {{-- MODAL POP-UP : DEMANDE D'AJOUT DES BULLETINS POUR MEILLEUR RÉSULTAT --}}
    <div id="reportsUpsellModal" class="tn-modal-backdrop" style="display:none" onclick="handleModalBackdropClick(event)">
        <div class="tn-modal-dialog">
            <button type="button" class="tn-modal-close" onclick="closeReportsModal()" title="Fermer">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
            
            <div class="tn-modal-icon-wrap">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#0E1022" stroke-width="2.2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            </div>

            <div class="tn-modal-badge">Recommandé pour un meilleur résultat</div>
            
            <h2 class="tn-modal-title">Souhaitez-vous ajouter vos bulletins scolaires pour affiner votre résultat à 95% ?</h2>
            
            <p class="tn-modal-desc">
                Votre test QCM a estimé votre niveau à <strong id="modalScoreDisplay">0 / 20</strong> avec une fiabilité de <strong>82%</strong>.<br>
                En ajoutant 1 ou 2 bulletins de classe, notre IA croise vos notes trimestrielles réelles et vos appréciations pour <strong>éliminer toute marge d'erreur</strong> et calibrer parfaitement vos 16 jalons de révision.
            </p>

            <div class="tn-modal-choices">
                {{-- Choix 1 : OUI --}}
                <button type="button" class="btn-modal-yes" onclick="confirmAddReportsFromModal()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#D4F25C" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    <span>Oui, ajouter mes bulletins (+13% de précision)</span>
                </button>
                
                {{-- Choix 2 : NON --}}
                <button type="button" class="btn-modal-no" onclick="proceedDirectlyToParcours()">
                    <span>Non merci, accéder directement à mon parcours sur le tableau de bord →</span>
                </button>
            </div>
        </div>
    </div>
    </main>
</div>

{{-- SCRIPT INTERACTIF DE GESTION DU FLUX WIZARD & QUIZ --}}
<script>
// Activer automatiquement l'immersion pleine page pour le test de niveau
document.addEventListener('DOMContentLoaded', function() {
    if (typeof toggleFocusMode === 'function') {
        toggleFocusMode(true);
    }
    document.body.classList.add('is-focus-mode', 'page-test-niveau');
});

// Bascule du plein écran navigateur natif
function toggleNativeFullscreen() {
    if (!document.fullscreenElement) {
        const docEl = document.documentElement;
        if (docEl.requestFullscreen) {
            docEl.requestFullscreen().catch(err => console.log(err));
        } else if (docEl.webkitRequestFullscreen) {
            docEl.webkitRequestFullscreen();
        }
    } else {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        }
    }
}

// Données injectées depuis le backend
const questionsData = @json($questions);

// Contexte d'apprentissage de l'élève (5 étapes totales dans l'onboarding)
let currentWizardStep = 1;
let userContext = {
    status: 'nouveau',
    period: 'milieu',
    ambition: 'mention'
};
let lastScoreResult = null;

const wizardMeta = {
    1: { title: "Étape 1 sur 5 · Situation académique", pct: "20%", width: "20%" },
    2: { title: "Étape 2 sur 5 · Période d'évaluation", pct: "40%", width: "40%" },
    3: { title: "Étape 3 sur 5 · Ambition visée au BAC", pct: "60%", width: "60%" },
    4: { title: "Étape 4 sur 5 · Récapitulatif du profil", pct: "80%", width: "80%" },
    5: { title: "Étape 5 sur 5 · Méthode d'évaluation", pct: "100%", width: "100%" }
};

const contextLabels = {
    status: {
        title: {
            'nouveau': 'Nouvel élève de Terminale D',
            'redoublant': 'Élève Redoublant'
        },
        desc: {
            'nouveau': "Premier passage en Terminale · Parcours standard d'apprentissage progressif",
            'redoublant': "Consolidation & Revanche · Détection ciblée des blocages pour viser la mention"
        }
    },
    period: {
        title: {
            'debut': "Début d'année (Sept - Nov)",
            'milieu': "Milieu d'année (Trimestre 2)",
            'fin': "Fin d'année & Sprint BAC",
            'vacances': "Grandes Vacances"
        },
        desc: {
            'debut': "Validation des pré-requis de Première et bases du 1er trimestre",
            'milieu': "Prise en compte des chapitres en cours (fonctions, suites, cinématique, chimie)",
            'fin': "Révision intensive type BAC et consolidation des dissertations scientifiques",
            'vacances': "Remise à niveau estivale et anticipation de la rentrée des classes"
        }
    },
    ambition: {
        title: {
            'reussite': "Sécuriser le BAC (10-12 / 20)",
            'mention': "Viser la Mention (14-17 / 20)",
            'excellence': "Excellence & Concours (18+ / 20)"
        },
        desc: {
            'reussite': "Focus sur les questions récurrentes, les définitions clés et la méthode d'admission",
            'mention': "Approfondissement méthodique, rigueur scientifique et problèmes de synthèse",
            'excellence': "Préparation d'élite pour facultés de médecine, écoles d'ingénieurs et bourses"
        }
    }
};

function selectWizardOption(key, val, cardEl) {
    userContext[key] = val;
    const parent = cardEl.parentElement;
    if (parent) {
        parent.querySelectorAll('.immersive-choice-card').forEach(c => c.classList.remove('selected'));
    }
    cardEl.classList.add('selected');
}

function goToWizardStep(stepNum) {
    if (stepNum < 1 || stepNum > 5) return;
    currentWizardStep = stepNum;

    // Masquer toutes les étapes
    for (let s = 1; s <= 5; s++) {
        const pane = document.getElementById(`stepPane${s}`);
        if (pane) pane.classList.remove('active');
    }

    // Afficher l'étape cible
    const targetPane = document.getElementById(`stepPane${stepNum}`);
    if (targetPane) targetPane.classList.add('active');

    // Mettre à jour la barre de progression
    const meta = wizardMeta[stepNum];
    document.getElementById('wizardStepInfo').innerText = meta.title;
    document.getElementById('wizardStepPct').innerText = meta.pct;
    document.getElementById('wizardProgressFill').style.width = meta.width;

    // Gérer l'affichage du bouton retour
    const backBtn = document.getElementById('wizardBackBtn');
    if (stepNum > 1) {
        backBtn.style.visibility = 'visible';
    } else {
        backBtn.style.visibility = 'hidden';
    }

    // Si on arrive à l'étape 4 (RÉCAPITULATIF), peupler les 3 cartes
    if (stepNum === 4) {
        document.getElementById('recapStatusTitle').innerText = contextLabels.status.title[userContext.status];
        document.getElementById('recapStatusDesc').innerText = contextLabels.status.desc[userContext.status];

        document.getElementById('recapPeriodTitle').innerText = contextLabels.period.title[userContext.period];
        document.getElementById('recapPeriodDesc').innerText = contextLabels.period.desc[userContext.period];

        document.getElementById('recapAmbitionTitle').innerText = contextLabels.ambition.title[userContext.ambition];
        document.getElementById('recapAmbitionDesc').innerText = contextLabels.ambition.desc[userContext.ambition];
    }

    // Si on arrive à l'étape 5 (MÉTHODE), mettre à jour le bandeau récapitulatif
    if (stepNum === 5) {
        const recapText = `${contextLabels.status.title[userContext.status]} · ${contextLabels.period.title[userContext.period]} · ${contextLabels.ambition.title[userContext.ambition]}`;
        const recapEl = document.getElementById('wizardRecapText'); if (recapEl) recapEl.innerText = recapText;
    }

    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function goToPreviousWizardStep() {
    if (currentWizardStep > 1) {
        goToWizardStep(currentWizardStep - 1);
    }
}

// Lancement du Mode QCM (depuis l'étape 5)
function startTestMode() {
    const wrapper = document.querySelector('.tn-immersive-wrapper');
    if (wrapper) wrapper.classList.add('allow-scroll');
    document.getElementById('wizardStepperBar').style.display = 'none';
    for (let s = 1; s <= 5; s++) {
        const pane = document.getElementById(`stepPane${s}`);
        if (pane) pane.classList.remove('active');
    }
    document.getElementById('sectionReports').style.display = 'none';
    document.getElementById('sectionResults').style.display = 'none';
    
    document.getElementById('tnBackBar').style.display = 'flex';
    document.getElementById('currentModeLabel').innerText = "Mode : Épreuve Diagnostique QCM (16 Questions)";
    
    document.getElementById('sectionTest').style.display = 'flex';
    
    if (questionsData && questionsData.length > 0) {
        renderQuestion(currentIndex);
        if (!timerInterval) startTimer();
    }
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Lancement du Mode Bulletins (depuis l'étape 5)
function startReportsMode() {
    const wrapper = document.querySelector('.tn-immersive-wrapper');
    if (wrapper) wrapper.classList.add('allow-scroll');
    document.getElementById('wizardStepperBar').style.display = 'none';
    for (let s = 1; s <= 5; s++) {
        const pane = document.getElementById(`stepPane${s}`);
        if (pane) pane.classList.remove('active');
    }
    document.getElementById('sectionTest').style.display = 'none';
    document.getElementById('sectionResults').style.display = 'none';
    
    document.getElementById('tnBackBar').style.display = 'flex';
    document.getElementById('currentModeLabel').innerText = "Mode : Scanner & Analyse de Bulletins Scolaires";
    
    document.getElementById('sectionReports').style.display = 'flex';
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Retour à l'étape 5 (choix du mode)
function returnToMethodChoice() {
    const wrapper = document.querySelector('.tn-immersive-wrapper');
    if (wrapper) wrapper.classList.remove('allow-scroll');
    document.getElementById('tnBackBar').style.display = 'none';
    document.getElementById('sectionTest').style.display = 'none';
    document.getElementById('sectionReports').style.display = 'none';
    document.getElementById('sectionResults').style.display = 'none';
    
    document.getElementById('wizardStepperBar').style.display = 'flex';
    goToWizardStep(5);
}

// Gestion du pop-up de validation et proposition de bulletins
function handleFinishWithModal() {
    // Récupérer la note affichée à l'écran
    const scoreValEl = document.getElementById('resScoreVal');
    const currentScore = scoreValEl ? scoreValEl.innerText.trim() : (lastScoreResult ? lastScoreResult.bac_score : '0');
    
    const modalScoreEl = document.getElementById('modalScoreDisplay');
    if (modalScoreEl) modalScoreEl.innerText = `${currentScore} / 20`;

    // Toujours ouvrir le modal pop-up pour que l'élève choisisse OUI ou NON
    const modal = document.getElementById('reportsUpsellModal');
    if (modal) {
        modal.style.display = 'flex';
    }
}

function closeReportsModal() {
    const modal = document.getElementById('reportsUpsellModal');
    if (modal) modal.style.display = 'none';
}

function handleModalBackdropClick(e) {
    if (e.target.id === 'reportsUpsellModal') {
        closeReportsModal();
    }
}

function confirmAddReportsFromModal() {
    closeReportsModal();
    startReportsMode();
}

function proceedDirectlyToParcours() {
    closeReportsModal();
    goToPersonalizedParcours();
}

function goToPersonalizedParcours() {
    const score = lastScoreResult ? lastScoreResult.bac_score : '0.0';
    const mention = lastScoreResult ? lastScoreResult.mention : 'Calibré';
    const reliability = lastScoreResult ? lastScoreResult.reliability : '82';
    const p = new URLSearchParams({
        revealed: '1',
        status: userContext.status,
        period: userContext.period,
        ambition: userContext.ambition,
        score: score,
        mention: mention,
        reliability: reliability
    });
    window.location.href = `/student/parcours?${p.toString()}`;
}

function goToPersonalizedParcoursWithReports() {
    const p = new URLSearchParams({
        revealed: '1',
        has_reports: '1',
        status: userContext.status,
        period: userContext.period,
        ambition: userContext.ambition,
        reliability: '95'
    });
    window.location.href = `/student/parcours?${p.toString()}`;
}

let currentIndex = 0;
let userAnswers = {}; // { question_id: option_id ou 'unknown' }
let secondsElapsed = 0;
let timerInterval = null;

// Initialisation au chargement
document.addEventListener('DOMContentLoaded', () => {
    goToWizardStep(1);
    document.getElementById('tnBackBar').style.display = 'none';
    document.getElementById('sectionTest').style.display = 'none';
    document.getElementById('sectionReports').style.display = 'none';
    document.getElementById('sectionResults').style.display = 'none';
    setupReportCardKeyboardNav();
});

// Chronomètre ascendant
function startTimer() {
    timerInterval = setInterval(() => {
        secondsElapsed++;
        const mins = String(Math.floor(secondsElapsed / 60)).padStart(2, '0');
        const secs = String(secondsElapsed % 60).padStart(2, '0');
        const display = document.getElementById('timerDisplay');
        if (display) display.innerText = `${mins}:${secs}`;
    }, 1000);
}

// Rendu de la question active
function renderQuestion(index) {
    if (!questionsData || index >= questionsData.length) return;
    const q = questionsData[index];

    // Métadonnées
    const meta = q.meta || {};
    const blocNum = meta.bloc || 1;
    const coeff = meta.coeff || 4;
    const subject = meta.subject_name || 'Mathématiques';

    // Mise à jour de la barre des blocs
    updateBlocsProgress(blocNum);

    // Badges et compteur
    document.getElementById('subjectBadge').innerText = `${subject} · Coeff. ${coeff}`;
    document.getElementById('qCounterText').innerText = `Question ${index + 1} sur ${questionsData.length}`;
    document.getElementById('qPromptText').innerText = q.prompt;

    // Rendu des options
    const container = document.getElementById('optionsContainer');
    container.innerHTML = '';

    const letters = ['A', 'B', 'C', 'D'];
    (q.options || []).forEach((opt, idx) => {
        const letter = letters[idx] || (idx + 1);
        const isSelected = (userAnswers[q.id] === opt.id);

        const card = document.createElement('div');
        card.className = `tn-option-card ${isSelected ? 'selected' : ''}`;
        card.onclick = () => selectOption(q.id, opt.id, card);

        card.innerHTML = `
            <div class="tn-option-indicator">${letter}</div>
            <div class="tn-option-text">${opt.option_text}</div>
        `;
        container.appendChild(card);
    });

    // 5ème option intégrée : Je ne sais pas encore
    const isUnknown = (userAnswers[q.id] === 'unknown');
    const unknownCard = document.createElement('div');
    unknownCard.className = `tn-option-card tn-option-unknown ${isUnknown ? 'selected' : ''}`;
    unknownCard.onclick = () => selectUnknownOption(q.id, unknownCard);
    unknownCard.innerHTML = `
        <div class="tn-option-indicator tn-unknown-indicator">?</div>
        <div class="tn-option-text" style="color: #64748B; font-weight: 700;">Je ne sais pas encore / Notion non abordée en classe</div>
    `;
    container.appendChild(unknownCard);

    // État du bouton de validation
    const validateBtn = document.getElementById('validateBtn');
    validateBtn.disabled = !userAnswers[q.id];
}

// Mise à jour visuelle des 3 blocs
function updateBlocsProgress(activeBloc) {
    for (let b = 1; b <= 3; b++) {
        const stepEl = document.getElementById(`blocStep${b}`);
        if (!stepEl) continue;
        stepEl.classList.remove('active', 'completed');
        if (b < activeBloc) {
            stepEl.classList.add('completed');
        } else if (b === activeBloc) {
            stepEl.classList.add('active');
        }
    }
}

// Sélection d'une réponse A, B, C, D
function selectOption(qId, optId, cardEl) {
    userAnswers[qId] = optId;
    document.querySelectorAll('.tn-option-card').forEach(c => c.classList.remove('selected'));
    cardEl.classList.add('selected');
    document.getElementById('validateBtn').disabled = false;
}

// Sélection de la 5ème option "Je ne sais pas encore"
function selectUnknownOption(qId, cardEl) {
    userAnswers[qId] = 'unknown';
    document.querySelectorAll('.tn-option-card').forEach(c => c.classList.remove('selected'));
    cardEl.classList.add('selected');
    document.getElementById('validateBtn').disabled = false;
}

// Question suivante ou soumission
function nextQuestion() {
    if (currentIndex < questionsData.length - 1) {
        currentIndex++;
        renderQuestion(currentIndex);
    } else {
        submitFullAssessment();
    }
}

// Soumission du test complet au contrôleur
function submitFullAssessment() {
    clearInterval(timerInterval);

    const validateBtn = document.getElementById('validateBtn');
    if (validateBtn) {
        validateBtn.disabled = true;
        validateBtn.innerText = "Calcul du diagnostic...";
    }

    fetch("{{ route('student.test.submit') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json"
        },
        body: JSON.stringify({ answers: userAnswers, context: userContext })
    })
    .then(r => {
        if (!r.ok) {
            throw new Error(`Erreur HTTP ${r.status}`);
        }
        return r.json();
    })
    .then(data => {
        if (data.success) {
            lastScoreResult = data;
            displayResults(data);
        } else {
            const errBanner = document.getElementById('tnGlobalErrorBanner');
            if (errBanner) {
                errBanner.innerText = data.message || "Erreur lors de la validation du diagnostic.";
                errBanner.style.display = 'block';
            } else {
                console.warn(data.message || "Erreur lors de la validation du diagnostic.");
            }
            if (validateBtn) {
                validateBtn.disabled = false;
                validateBtn.innerText = "Valider et continuer";
            }
        }
    })
    .catch(err => {
        console.error("Erreur lors de la soumission :", err);
        const errBanner = document.getElementById('tnGlobalErrorBanner');
        if (errBanner) {
            errBanner.innerText = "Une erreur est survenue lors de la validation de vos réponses. Veuillez vérifier votre connexion et réessayer.";
            errBanner.style.display = 'block';
        } else {
            console.warn("Erreur réseau lors de la validation.");
        }
        if (validateBtn) {
            validateBtn.disabled = false;
            validateBtn.innerText = "Valider et continuer";
        }
    });
}

// Affichage du grand écran de bilan avec proposition d'ajouter les bulletins
function displayResults(data) {
    const wrapper = document.querySelector(".tn-immersive-wrapper");
    if (wrapper) wrapper.classList.add("allow-scroll");
    
    const stepper = document.getElementById('wizardStepperBar');
    if (stepper) stepper.style.display = 'none';

    for (let s = 1; s <= 5; s++) {
        const pane = document.getElementById(`stepPane${s}`);
        if (pane) pane.classList.remove('active');
    }
    
    const backBar = document.getElementById('tnBackBar');
    if (backBar) backBar.style.display = 'none';

    const secTest = document.getElementById('sectionTest');
    if (secTest) secTest.style.display = 'none';

    const secRep = document.getElementById('sectionReports');
    if (secRep) secRep.style.display = 'none';

    const resultsEl = document.getElementById('sectionResults');
    if (resultsEl) resultsEl.style.display = 'flex';

    // Remplissage sécurisé des scores et mentions
    const scoreValEl = document.getElementById('resScoreVal');
    if (scoreValEl) scoreValEl.innerText = data.bac_score;

    const mentionValEl = document.getElementById('resMentionVal');
    if (mentionValEl) mentionValEl.innerText = data.mention;

    const relValEl = document.getElementById('resReliabilityVal');
    if (relValEl) relValEl.innerText = `${data.reliability}%`;

    const upsellScoreEl = document.getElementById('resUpsellScore');
    if (upsellScoreEl) upsellScoreEl.innerText = `${data.bac_score} / 20`;

    const modalScoreEl = document.getElementById('modalScoreDisplay');
    if (modalScoreEl) modalScoreEl.innerText = `${data.bac_score} / 20`;

    // Rendu des 5 disciplines dans la grille
    const domainsGrid = document.getElementById('resDomainsGrid');
    if (domainsGrid) {
        domainsGrid.innerHTML = '';

        for (const [key, d] of Object.entries(data.domains || {})) {
            let tagClass = 'res-acquis';
            let statusLabel = 'Acquis';
            if (d.status === 'urgent') {
                tagClass = 'res-urgent';
                statusLabel = 'Prioritaire';
            } else if (d.status === 'a_consolider') {
                tagClass = 'res-consolider';
                statusLabel = 'À consolider';
            }

            const box = document.createElement('div');
            box.className = 'tn-domain-res-box';
            box.innerHTML = `
                <div class="tn-res-box-top">
                    <div>
                        <div style="font-weight:800;color:#0E1022;font-size:13.5px;line-height:1.2">${d.name}</div>
                        <div style="font-size:11px;color:#64748B;font-weight:600;margin-top:2px">Coeff. ${d.coeff}</div>
                    </div>
                    <span class="tn-res-status-tag ${tagClass}">${statusLabel}</span>
                </div>
                <div style="font-size:22px;font-weight:900;color:#0E1022;line-height:1.1;margin-top:6px">${d.score_on_20} <span style="font-size:12px;font-weight:600;color:#64748B">/ 20</span></div>
                <div style="font-size:11.5px;color:#64748B;margin-top:3px">${d.percent}% d'objectifs maîtrisés</div>
            `;
            domainsGrid.appendChild(box);
        }
    }
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Prévisualisation fichier bulletin
function previewFile(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        document.getElementById('dropzoneText').innerText = `Fichier sélectionné : ${file.name} (${Math.round(file.size / 1024)} Ko)`;
    }
}

// Enregistrement d'un bulletin scolaire

// Mise à jour dynamique des périodes selon le système (Trimestre vs Semestre)
function updatePeriodOptions() {
    const sysSelect = document.getElementById('systemTypeSelect');
    const termSelect = document.getElementById('termPeriodSelect');
    if (!sysSelect || !termSelect) return;

    const isSemestre = (sysSelect.value === 'semestre');
    termSelect.innerHTML = '';

    if (isSemestre) {
        termSelect.innerHTML = `
            <option value="Semestre 1" selected>1er Semestre</option>
            <option value="Semestre 2">2ème Semestre</option>
            <option value="Bilan Annuel">Bilan Annuel Complet</option>
        `;
    } else {
        termSelect.innerHTML = `
            <option value="Trimestre 1" selected>1er Trimestre</option>
            <option value="Trimestre 2">2ème Trimestre</option>
            <option value="Trimestre 3">3ème Trimestre</option>
            <option value="Bilan Annuel">Bilan Annuel Complet</option>
        `;
    }
}

// Calcul en direct de la moyenne pondérée de classe saisie
function computeLiveAverage() {
    const inputs = document.querySelectorAll('.bullet-input');
    let totalWeighted = 0;
    let totalCoeffs = 0;
    let hasAtLeastOne = false;

    inputs.forEach(input => {
        const val = parseFloat(input.value);
        const coeff = parseFloat(input.dataset.coeff || 1);
        if (!isNaN(val) && val >= 0 && val <= 20) {
            totalWeighted += (val * coeff);
            totalCoeffs += coeff;
            hasAtLeastOne = true;
        }
    });

    const display = document.getElementById('liveCalculatedAvg');
    if (!display) return;

    if (hasAtLeastOne && totalCoeffs > 0) {
        const avg = (totalWeighted / totalCoeffs).toFixed(2);
        display.innerHTML = `<span style="color:#059669;font-weight:900">${avg} / 20</span>`;
    } else {
        display.innerText = '-- / 20';
    }
}

function showReportCardFeedback(msg, isSuccess = true) {
    const alertBox = document.getElementById('reportCardSuccessAlert');
    const alertText = document.getElementById('reportCardSuccessText');
    if (!alertBox || !alertText) return;

    alertText.innerText = msg;
    if (isSuccess) {
        alertBox.style.background = '#ECFDF5';
        alertBox.style.borderColor = '#A7F3D0';
        alertBox.style.color = '#065F46';
    } else {
        alertBox.style.background = '#FEF2F2';
        alertBox.style.borderColor = '#FECACA';
        alertBox.style.color = '#DC2626';
    }
    alertBox.style.display = 'flex';
    alertBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function renderNewReportCardItem(card, totalCards) {
    // 1. Mettre à jour le compteur du titre
    const titleEl = document.getElementById('savedReportsTitle');
    const count = (totalCards !== undefined && totalCards !== null) ? totalCards : (document.querySelectorAll('.tn-report-item').length + 1);
    if (titleEl) {
        titleEl.innerText = `Mes Bulletins Enregistrés (${count})`;
    }

    // 2. Masquer le message vide s'il existe
    const emptyMsg = document.getElementById('emptyReportsMsg');
    if (emptyMsg) {
        emptyMsg.style.display = 'none';
    }

    // 3. Récupérer ou créer le conteneur de liste
    let listContainer = document.getElementById('savedReportsList');
    if (!listContainer) {
        listContainer = document.createElement('div');
        listContainer.id = 'savedReportsList';
        listContainer.style.display = 'flex';
        listContainer.style.flexDirection = 'column';
        listContainer.style.gap = '10px';
        const cardBox = document.querySelector('.tn-saved-reports-card');
        const note = cardBox ? cardBox.querySelector('.tn-pedagogical-note') : null;
        if (cardBox && note) {
            cardBox.insertBefore(listContainer, note);
        } else if (cardBox) {
            cardBox.appendChild(listContainer);
        }
    } else {
        listContainer.style.display = 'flex';
    }

    // 4. Supprimer un ancien élément avec le même id s'il existait déjà
    const existing = document.getElementById(`reportCardRow-${card.id}`);
    if (existing) {
        existing.remove();
    }

    // 5. Créer la ligne du bulletin
    const item = document.createElement('div');
    item.className = 'tn-report-item';
    item.id = `reportCardRow-${card.id}`;

    const avgStr = (card.overall_average !== null && card.overall_average !== undefined)
        ? `Moyenne : <strong>${card.overall_average}/20</strong> · `
        : '';
    const statusText = (card.ai_status === 'pending_analysis') ? 'Analyse IA programmée' : 'Vérifié';

    item.innerHTML = `
        <div>
            <div class="tn-report-name">${card.title}</div>
            <div style="font-size:11px;color:#64748B;margin-top:2px">
                ${card.school_year || '2026-2027'} · 
                ${avgStr}
                <span class="tn-report-status">${statusText}</span>
            </div>
        </div>
        <div class="tn-del-wrap" style="display:flex;align-items:center;">
            <button type="button" class="tn-delete-btn" id="delBtn-${card.id}" onclick="askDeleteReportCard('${card.id}')">
                Supprimer
            </button>
            <div id="delConfirmBox-${card.id}" style="display:none;align-items:center;gap:6px;">
                <span style="font-size:11px;font-weight:800;color:#DC2626;">Supprimer ?</span>
                <button type="button" onclick="executeDeleteReportCard('${card.id}')" style="background:#DC2626;color:#FFFFFF;border:none;border-radius:4px;padding:3px 8px;font-size:11px;font-weight:800;cursor:pointer;">Oui</button>
                <button type="button" onclick="cancelDeleteReportCard('${card.id}')" style="background:#E2E8F0;color:#0E1022;border:none;border-radius:4px;padding:3px 8px;font-size:11px;font-weight:700;cursor:pointer;">Non</button>
            </div>
        </div>
    `;

    listContainer.prepend(item);

    // 6. Mettre en valeur le bouton d'activation du parcours
    const actBtn = document.getElementById('btnActivateParcoursWithReports');
    if (actBtn) {
        actBtn.style.boxShadow = '0 0 0 3px rgba(212, 242, 92, 0.55)';
        actBtn.style.transform = 'scale(1.02)';
        actBtn.style.transition = 'all 0.25s ease';
        setTimeout(() => {
            actBtn.style.boxShadow = '';
            actBtn.style.transform = '';
        }, 1500);
    }
}

function handleUploadReportCard(e) {
    e.preventDefault();
    const form = document.getElementById('reportCardForm');
    const formData = new FormData(form);
    const submitBtn = document.getElementById('submitReportBtn');
    const origBtnHtml = submitBtn.innerHTML;

    submitBtn.disabled = true;
    submitBtn.innerText = "Enregistrement en cours...";

    fetch("{{ route('student.test.report_card.upload') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json"
        },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = origBtnHtml;

        if (data.success) {
            // Affichage sans reload de la confirmation
            showReportCardFeedback(data.message || "Bulletin enregistré avec succès ! Vos notes ont été prises en compte.");

            // Mise à jour de la liste des bulletins dans le DOM
            if (data.card) {
                renderNewReportCardItem(data.card, data.total_cards);
            }
        } else {
            showReportCardFeedback(data.message || "Erreur lors de l'enregistrement du bulletin.", false);
        }
    })
    .catch(err => {
        console.error(err);
        submitBtn.disabled = false;
        submitBtn.innerHTML = origBtnHtml;
        showReportCardFeedback("Erreur réseau lors de l'enregistrement.", false);
    });
}

// Gestion de la suppression sans alerte bloquante système
function askDeleteReportCard(id) {
    const btn = document.getElementById(`delBtn-${id}`);
    const box = document.getElementById(`delConfirmBox-${id}`);
    if (btn) btn.style.display = 'none';
    if (box) box.style.display = 'flex';
}

function cancelDeleteReportCard(id) {
    const btn = document.getElementById(`delBtn-${id}`);
    const box = document.getElementById(`delConfirmBox-${id}`);
    if (btn) btn.style.display = 'inline-block';
    if (box) box.style.display = 'none';
}

function executeDeleteReportCard(id) {
    fetch(`/student/test-niveau/report-card/${id}`, {
        method: "DELETE",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json"
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const row = document.getElementById(`reportCardRow-${id}`);
            if (row) row.remove();

            const remaining = (data.total_cards !== undefined && data.total_cards !== null)
                ? data.total_cards
                : document.querySelectorAll('.tn-report-item').length;

            const titleEl = document.getElementById('savedReportsTitle');
            if (titleEl) {
                titleEl.innerText = `Mes Bulletins Enregistrés (${remaining})`;
            }

            if (remaining === 0) {
                const listContainer = document.getElementById('savedReportsList');
                if (listContainer) listContainer.style.display = 'none';

                let emptyMsg = document.getElementById('emptyReportsMsg');
                if (emptyMsg) {
                    emptyMsg.style.display = 'block';
                }
            }

            showReportCardFeedback("Bulletin supprimé.", true);
        } else {
            showReportCardFeedback("Erreur lors de la suppression.", false);
        }
    })
    .catch(err => {
        console.error(err);
        showReportCardFeedback("Erreur réseau lors de la suppression.", false);
    });
}

// Navigation au clavier sur les notes : la touche Entrée passe à la note suivante au lieu d'enregistrer
function setupReportCardKeyboardNav() {
    const reportCardForm = document.getElementById('reportCardForm');
    if (!reportCardForm) return;

    reportCardForm.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            const isBulletInput = e.target.classList.contains('bullet-input');
            const isSelect = e.target.tagName === 'SELECT';

            if (isBulletInput || isSelect) {
                e.preventDefault();
                e.stopPropagation();

                const inputs = Array.from(reportCardForm.querySelectorAll('.bullet-input'));
                const currentIndex = inputs.indexOf(e.target);

                if (currentIndex >= 0 && currentIndex < inputs.length - 1) {
                    // Aller au champ de note suivant
                    inputs[currentIndex + 1].focus();
                    inputs[currentIndex + 1].select();
                } else if (currentIndex === inputs.length - 1) {
                    // Dernière note : focaliser le bouton d'enregistrement
                    const submitBtn = document.getElementById('submitReportBtn');
                    if (submitBtn) {
                        submitBtn.focus();
                    }
                } else if (isSelect) {
                    // Depuis les sélecteurs, aller au premier champ de note
                    if (inputs.length > 0) {
                        inputs[0].focus();
                        inputs[0].select();
                    }
                }
            }
        }
    });
}
setupReportCardKeyboardNav();

// NAVIGATION ET VALIDATION GLOBALE AU CLAVIER
document.addEventListener('keydown', function(e) {
    // Ne pas intercepter si l'utilisateur est dans un champ de formulaire
    if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.tagName === 'SELECT') {
        return;
    }

    // 1. Si le Pop-up Modal est ouvert
    const modal = document.getElementById('reportsUpsellModal');
    if (modal && modal.style.display === 'flex') {
        if (e.key === 'Escape') {
            e.preventDefault();
            closeReportsModal();
        } else if (e.key === 'Enter') {
            e.preventDefault();
            confirmAddReportsFromModal();
        }
        return;
    }

    // 2. Si on est sur l'écran du QCM (Section Test active)
    const sectionTest = document.getElementById('sectionTest');
    if (sectionTest && sectionTest.style.display === 'flex') {
        const optionCards = Array.from(document.querySelectorAll('#optionsContainer .tn-option-card'));
        const validateBtn = document.getElementById('validateBtn');
        const currentIndexSelected = optionCards.findIndex(c => c.classList.contains('selected'));

        // Flèche Bas / Flèche Droite : passer au choix suivant
        if (e.key === 'ArrowDown' || e.key === 'ArrowRight') {
            e.preventDefault();
            if (optionCards.length > 0) {
                const nextIdx = (currentIndexSelected === -1 || currentIndexSelected >= optionCards.length - 1) ? 0 : currentIndexSelected + 1;
                optionCards[nextIdx].click();
                optionCards[nextIdx].scrollIntoView({ block: 'nearest', behavior: 'smooth' });
            }
            return;
        }

        // Flèche Haut / Flèche Gauche : passer au choix précédent
        if (e.key === 'ArrowUp' || e.key === 'ArrowLeft') {
            e.preventDefault();
            if (optionCards.length > 0) {
                const prevIdx = (currentIndexSelected <= 0) ? optionCards.length - 1 : currentIndexSelected - 1;
                optionCards[prevIdx].click();
                optionCards[prevIdx].scrollIntoView({ block: 'nearest', behavior: 'smooth' });
            }
            return;
        }

        // Touche Entrée : Valider la réponse sélectionnée et passer immédiatement à la question suivante
        if (e.key === 'Enter') {
            e.preventDefault();
            if (validateBtn && !validateBtn.disabled) {
                validateBtn.style.transform = 'scale(0.97)';
                setTimeout(() => {
                    validateBtn.style.transform = '';
                    nextQuestion();
                }, 80);
            } else {
                // Si aucune option n'est encore sélectionnée, sélectionner la première par défaut
                if (optionCards.length > 0 && currentIndexSelected === -1) {
                    optionCards[0].click();
                }
            }
            return;
        }

        // Raccourcis directs de touches : A, B, C, D (ou 1, 2, 3, 4)
        const keyLower = e.key.toLowerCase();
        if (keyLower === 'a' || e.key === '1') {
            if (optionCards[0]) { e.preventDefault(); optionCards[0].click(); }
            return;
        }
        if (keyLower === 'b' || e.key === '2') {
            if (optionCards[1]) { e.preventDefault(); optionCards[1].click(); }
            return;
        }
        if (keyLower === 'c' || e.key === '3') {
            if (optionCards[2]) { e.preventDefault(); optionCards[2].click(); }
            return;
        }
        if (keyLower === 'd' || e.key === '4') {
            if (optionCards[3]) { e.preventDefault(); optionCards[3].click(); }
            return;
        }
        // 5ème option "Je ne sais pas encore" : E, 5, ?, ou U
        if (keyLower === 'e' || e.key === '5' || e.key === '?' || keyLower === 'u') {
            const unknownCard = document.querySelector('#optionsContainer .tn-option-unknown');
            if (unknownCard) { e.preventDefault(); unknownCard.click(); }
            return;
        }
    }

    // 3. Si on est sur l'écran des Résultats (Section Results active)
    const sectionResults = document.getElementById('sectionResults');
    if (sectionResults && sectionResults.style.display === 'flex') {
        if (e.key === 'Enter') {
            e.preventDefault();
            handleFinishWithModal();
            return;
        }
    }

    // 4. Si on est sur le Wizard onboarding (Étapes 1 à 4)
    const activePane = document.querySelector('.wizard-step-pane.active');
    if (activePane) {
        const choiceCards = Array.from(activePane.querySelectorAll('.immersive-choice-card'));
        const currentChoiceIdx = choiceCards.findIndex(c => c.classList.contains('selected'));

        // Naviguer entre les choix du wizard avec les flèches
        if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
            if (choiceCards.length > 0) {
                e.preventDefault();
                const nextIdx = (currentChoiceIdx === -1 || currentChoiceIdx >= choiceCards.length - 1) ? 0 : currentChoiceIdx + 1;
                choiceCards[nextIdx].click();
            }
            return;
        }
        if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
            if (choiceCards.length > 0) {
                e.preventDefault();
                const prevIdx = (currentChoiceIdx <= 0) ? choiceCards.length - 1 : currentChoiceIdx - 1;
                choiceCards[prevIdx].click();
            }
            return;
        }

        // Valider l'étape active avec Entrée
        if (e.key === 'Enter') {
            e.preventDefault();
            const nextBtn = activePane.querySelector('.btn-wizard-next');
            if (nextBtn) {
                nextBtn.click();
            }
            return;
        }
    }
});

</script>
@endsection
