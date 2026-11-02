@extends('layouts.student')

@section('title', 'Calendrier Scolaire Officiel — MonKayier')

@section('content')
<style>
/* ==========================================================================
   CALENDRIER SCOLAIRE OFFICIEL — MONKAYIER
   DESIGN SOBER & ÉPURÉ · SORA / INTER · ZÉRO EMOJI · ICÔNES VECTORIELLES SVG
   ========================================================================== */

.cal-container {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 22px;
    box-sizing: border-box;
    padding-bottom: 50px;
}

/* --------------------------------------------------------------------------
   BLOC 1 : EN-TÊTE ÉPURÉ & SÉLECTEUR RAPIDE DE CLASSE
   -------------------------------------------------------------------------- */
.cal-header-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
    background: #FFFFFF;
    border: 1.5px solid #E2E8F0;
    border-radius: 14px;
    padding: 16px 24px;
    box-sizing: border-box;
}
.chb-left {
    display: flex;
    align-items: center;
    gap: 12px;
}
.chb-icon-badge {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: #0E1022;
    color: #D4F25C;
    display: flex;
    align-items: center;
    justify-content: center;
    flex: none;
}
.chb-icon-badge svg {
    width: 18px;
    height: 18px;
    stroke: currentColor;
    stroke-width: 2.2;
    fill: none;
}
.chb-title {
    font-family: 'Sora', sans-serif;
    font-size: 20px;
    font-weight: 800;
    color: #0E1022;
    margin: 0;
    letter-spacing: -0.02em;
}

.chb-right {
    display: flex;
    align-items: center;
    gap: 10px;
}
.chb-select-label {
    font-size: 13px;
    font-weight: 700;
    color: #64748B;
    white-space: nowrap;
}
.chb-select-wrapper {
    position: relative;
    display: inline-flex;
    align-items: center;
}
.chb-class-select {
    appearance: none;
    background: #F8FAFC;
    border: 1.5px solid #CBD5E1;
    border-radius: 8px;
    padding: 8px 36px 8px 14px;
    font-family: 'Inter', sans-serif;
    font-size: 13.5px;
    font-weight: 700;
    color: #0E1022;
    cursor: pointer;
    transition: all 0.15s ease;
}
.chb-class-select:focus {
    outline: none;
    border-color: #0E1022;
    background: #FFFFFF;
}
.chb-select-chevron {
    position: absolute;
    right: 12px;
    pointer-events: none;
    color: #64748B;
}

/* --------------------------------------------------------------------------
   BLOC 2 : CARTE HÉROÏQUE D'EXAMEN (DESIGN SOBRE, AÉRÉ, SANS SURCHARGE)
   -------------------------------------------------------------------------- */
.cal-hero-exam-card {
    background: #0E1022;
    color: #FFFFFF;
    border-radius: 16px;
    padding: 26px 30px;
    display: grid;
    grid-template-columns: 1.15fr 1fr;
    gap: 32px;
    align-items: center;
    box-shadow: 0 12px 32px -8px rgba(14, 16, 34, 0.2);
    box-sizing: border-box;
}
@media (max-width: 960px) {
    .cal-hero-exam-card {
        grid-template-columns: 1fr;
        padding: 22px 20px;
        gap: 22px;
    }
}

.chec-left {
    display: flex;
    flex-direction: column;
    gap: 14px;
}
.chec-title {
    font-family: 'Sora', sans-serif;
    font-size: 22px;
    font-weight: 800;
    color: #FFFFFF;
    margin: 0;
    letter-spacing: -0.02em;
    line-height: 1.3;
}
.chec-countdown-row {
    display: flex;
    align-items: baseline;
    gap: 14px;
    flex-wrap: wrap;
}
.chec-days-num {
    font-family: 'Sora', sans-serif;
    font-size: 46px;
    font-weight: 900;
    color: #D4F25C;
    line-height: 1;
    letter-spacing: -0.03em;
}
.chec-countdown-info {
    display: flex;
    flex-direction: column;
    gap: 3px;
}
.chec-days-label {
    font-family: 'Sora', sans-serif;
    font-size: 15px;
    font-weight: 750;
    color: #FFFFFF;
}
.chec-date-meta {
    font-size: 12.5px;
    color: #94A3B8;
    display: flex;
    align-items: center;
    gap: 6px;
}
.chec-date-meta svg {
    width: 14px;
    height: 14px;
    stroke: currentColor;
    stroke-width: 2;
    fill: none;
    flex: none;
}

/* Côté droit épuré : Paliers de mention intégrés sans empilement de boîtes */
.chec-right {
    display: flex;
    flex-direction: column;
    gap: 12px;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 12px;
    padding: 16px 20px;
    box-sizing: border-box;
}
.chec-right-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.chec-right-title {
    font-family: 'Sora', sans-serif;
    font-size: 11px;
    font-weight: 800;
    color: #94A3B8;
    letter-spacing: 0.06em;
    text-transform: uppercase;
}
.chec-mentions-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
}
@media (max-width: 520px) {
    .chec-mentions-row {
        grid-template-columns: repeat(2, 1fr);
    }
}
.chec-mention-item {
    display: flex;
    flex-direction: column;
    gap: 2px;
}
.cmi-grade {
    font-size: 11px;
    font-weight: 600;
    color: #94A3B8;
}
.cmi-val {
    font-family: 'Sora', sans-serif;
    font-size: 13.5px;
    font-weight: 800;
    color: #FFFFFF;
}
.chec-coeff-row {
    font-size: 12px;
    color: #CBD5E1;
    display: flex;
    align-items: center;
    gap: 7px;
    padding-top: 10px;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
}
.chec-coeff-row svg {
    width: 14px;
    height: 14px;
    stroke: #D4F25C;
    stroke-width: 2.2;
    fill: none;
    flex: none;
}

/* --------------------------------------------------------------------------
   BLOC 3 : LA LIGNE DE VIE DE L'ANNÉE (LES 3 TRIMESTRES)
   -------------------------------------------------------------------------- */
.cal-timeline-section {
    background: #FFFFFF;
    border: 1.5px solid #E2E8F0;
    border-radius: 14px;
    padding: 22px 24px;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    gap: 18px;
}
.cts-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}
.cts-title {
    font-family: 'Sora', sans-serif;
    font-size: 16px;
    font-weight: 800;
    color: #0E1022;
    margin: 0;
}
.cts-badge {
    font-size: 11.5px;
    font-weight: 700;
    color: #64748B;
    background: #F1F5F9;
    padding: 4px 10px;
    border-radius: 6px;
}

.cal-trimester-track {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
}
@media (max-width: 800px) {
    .cal-trimester-track {
        grid-template-columns: 1fr;
    }
}
.cal-trimester-card {
    background: #F8FAFC;
    border: 1.5px solid #E2E8F0;
    border-radius: 12px;
    padding: 16px 18px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    transition: all 0.2s ease;
}
.cal-trimester-card.active-trimester {
    background: #FFFFFF;
    border-color: #0E1022;
    box-shadow: 0 4px 14px rgba(14, 16, 34, 0.06);
}
.ctc-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.ctc-name {
    font-family: 'Sora', sans-serif;
    font-size: 14px;
    font-weight: 800;
    color: #0E1022;
}
.ctc-status-pill {
    font-size: 10px;
    font-weight: 800;
    padding: 2.5px 7px;
    border-radius: 5px;
    text-transform: uppercase;
    letter-spacing: 0.03em;
}
.status-completed {
    background: #F1F5F9;
    color: #64748B;
}
.status-current {
    background: #D4F25C;
    color: #0E1022;
}
.status-upcoming {
    background: #EFF6FF;
    color: #0284c7;
}
.ctc-dates {
    font-size: 12.5px;
    font-weight: 700;
    color: #475569;
}
.ctc-desc {
    font-size: 12px;
    color: #64748B;
    line-height: 1.45;
    margin: 0;
}

/* --------------------------------------------------------------------------
   BLOC 4 : PROCHAINES ÉCHÉANCES OFFICIELLES (LISTE SOBRE)
   -------------------------------------------------------------------------- */
.cal-milestones-section {
    display: flex;
    flex-direction: column;
    gap: 14px;
}
.cms-header-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}
.cms-title {
    font-family: 'Sora', sans-serif;
    font-size: 16px;
    font-weight: 800;
    color: #0E1022;
    margin: 0;
}
.cms-filters {
    display: flex;
    gap: 6px;
}
.cms-filter-btn {
    background: #FFFFFF;
    border: 1px solid #CBD5E1;
    border-radius: 8px;
    padding: 6px 12px;
    font-size: 12px;
    font-weight: 700;
    color: #475569;
    cursor: pointer;
    transition: all 0.15s ease;
}
.cms-filter-btn.active {
    background: #0E1022;
    border-color: #0E1022;
    color: #FFFFFF;
}

.cms-cards-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.cal-milestone-row {
    background: #FFFFFF;
    border: 1.5px solid #E2E8F0;
    border-radius: 12px;
    padding: 14px 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    box-sizing: border-box;
    transition: all 0.15s ease;
}
.cal-milestone-row:hover {
    border-color: #CBD5E1;
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.03);
}
.cmr-left {
    display: flex;
    align-items: center;
    gap: 14px;
}
.cmr-date-badge {
    width: 52px;
    height: 52px;
    border-radius: 10px;
    background: #F8FAFC;
    border: 1.5px solid #E2E8F0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    flex: none;
    line-height: 1;
}
.cdb-day {
    font-family: 'Sora', sans-serif;
    font-size: 18px;
    font-weight: 900;
    color: #0E1022;
}
.cdb-month {
    font-size: 9.5px;
    font-weight: 850;
    color: #64748B;
    text-transform: uppercase;
    margin-top: 3px;
}
.cmr-info {
    display: flex;
    flex-direction: column;
    gap: 3px;
}
.cmr-title-row {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}
.cmr-title {
    font-family: 'Sora', sans-serif;
    font-size: 14px;
    font-weight: 800;
    color: #0E1022;
}
.cmr-tag {
    font-size: 9.5px;
    font-weight: 800;
    padding: 2px 6px;
    border-radius: 4px;
    text-transform: uppercase;
}
.tag-exam {
    background: #EFF6FF;
    color: #1D4ED8;
}
.tag-holiday {
    background: #F0FDF4;
    color: #15803D;
}
.tag-official {
    background: #FEF3C7;
    color: #92400E;
}
.cmr-meta {
    font-size: 12px;
    color: #64748B;
    display: flex;
    align-items: center;
    gap: 8px;
}
.cmr-right {
    display: flex;
    align-items: center;
    gap: 10px;
}
.cmr-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11px;
    font-weight: 750;
    color: #15803D;
    background: #F0FDF4;
    border: 1px solid #BBF7D0;
    padding: 3px 8px;
    border-radius: 6px;
    white-space: nowrap;
}
.cmr-status-badge svg {
    width: 12px;
    height: 12px;
    stroke: currentColor;
    stroke-width: 2.5;
    fill: none;
}
</style>

<div class="cal-container">

    {{-- BLOC 1 : EN-TÊTE ÉPURÉ & SÉLECTEUR DE CLASSE SANS SURCHARGE --}}
    <div class="cal-header-bar">
        <div class="chb-left">
            <div class="chb-icon-badge">
                <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            </div>
            <h1 class="chb-title">Calendrier Scolaire Officiel</h1>
        </div>

        <div class="chb-right">
            <span class="chb-select-label">Classe consultée :</span>
            <div class="chb-select-wrapper">
                <select id="classSelector" class="chb-class-select" onchange="onClassChange(this.value)">
                    <option value="terminale_d" selected>Terminale D (BAC 2)</option>
                    <option value="terminale_a4">Terminale A4 (BAC 2)</option>
                    <option value="terminale_c">Terminale C (BAC 2)</option>
                    <option value="premiere_d">Première D (BAC 1)</option>
                    <option value="troisieme">Troisième (BEPC)</option>
                    <option value="cm2">CM2 (CEPD)</option>
                    <option value="seconde">Seconde (Passage en 1ère)</option>
                </select>
                <svg class="chb-select-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
            </div>
        </div>
    </div>

    {{-- BLOC 2 : CARTE HÉROÏQUE D'EXAMEN (DESIGN SOBRE & COMPTE À REBOURS PRÉCIS) --}}
    <div class="cal-hero-exam-card" id="heroExamCard">
        <div class="chec-left">
            <h2 class="chec-title" id="heroExamTitle">Baccalauréat Deuxième Partie (BAC 2 Série D)</h2>
            
            <div class="chec-countdown-row">
                <span class="chec-days-num" id="countdownDays">--</span>
                <div class="chec-countdown-info">
                    <span class="chec-days-label" id="countdownLabel">Jours restants avant les épreuves</span>
                    <div class="chec-date-meta">
                        <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        <span id="heroDateString">Session officielle : Juin 2027</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="chec-right">
            <div class="chec-right-top">
                <span class="chec-right-title" id="heroBoxRightTitle">Paliers de Mention au BAC 2</span>
            </div>

            <div class="chec-mentions-row" id="mentionsGrid">
                <!-- Rempli dynamiquement -->
            </div>

            <div class="chec-coeff-row">
                <svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                <span id="heroCoeffPill">Coefficients phares : Maths (4) · PC (4) · SVT (4)</span>
            </div>
        </div>
    </div>

    {{-- BLOC 3 : LA LIGNE DE VIE DE L'ANNÉE (LES 3 TRIMESTRES SCOLAIRES) --}}
    <div class="cal-timeline-section">
        <div class="cts-top">
            <h3 class="cts-title">Découpage Trimestriel Officiel</h3>
            <span class="cts-badge">Année Scolaire 2026 – 2027</span>
        </div>

        <div class="cal-trimester-track">
            {{-- Trimestre 1 --}}
            <div class="cal-trimester-card">
                <div class="ctc-head">
                    <span class="ctc-name">Trimestre 1</span>
                    <span class="ctc-status-pill status-completed">Clôturé</span>
                </div>
                <div class="ctc-dates">15 Septembre — 19 Décembre</div>
                <p class="ctc-desc">Fondamentaux et devoirs 1 & 2. Clôturé par les congés de fin d'année.</p>
            </div>

            {{-- Trimestre 2 (En cours) --}}
            <div class="cal-trimester-card active-trimester">
                <div class="ctc-head">
                    <span class="ctc-name">Trimestre 2</span>
                    <span class="ctc-status-pill status-current">Actuel</span>
                </div>
                <div class="ctc-dates">05 Janvier — 26 Mars</div>
                <p class="ctc-desc">Trimestre pivot : devoirs 3 & 4, examens blancs régionaux. Suivi des congés de Pâques.</p>
            </div>

            {{-- Trimestre 3 --}}
            <div class="cal-trimester-card">
                <div class="ctc-head">
                    <span class="ctc-name">Trimestre 3</span>
                    <span class="ctc-status-pill status-upcoming">Sprint Final</span>
                </div>
                <div class="ctc-dates">12 Avril — 25 Juin</div>
                <p class="ctc-desc">Consolidation intensive, révisions ciblées, épreuves officielles et délibérations.</p>
            </div>
        </div>
    </div>

    {{-- BLOC 4 : PROCHAINES ÉCHÉANCES OFFICIELLES (CLAIRES & SANS SURCHARGE) --}}
    <div class="cal-milestones-section">
        <div class="cms-header-row">
            <h3 class="cms-title">Prochaines Échéances Officielles</h3>
            <div class="cms-filters">
                <button type="button" class="cms-filter-btn active" onclick="filterMilestones('all', this)">Toutes</button>
                <button type="button" class="cms-filter-btn" onclick="filterMilestones('exam', this)">Examens</button>
                <button type="button" class="cms-filter-btn" onclick="filterMilestones('holiday', this)">Vacances & Fériés</button>
            </div>
        </div>

        <div class="cms-cards-list" id="milestonesList">
            
            {{-- Échéance 1 : Examens Blancs --}}
            <div class="cal-milestone-row" data-type="exam">
                <div class="cmr-left">
                    <div class="cmr-date-badge">
                        <span class="cdb-day">18</span>
                        <span class="cdb-month">Mars</span>
                    </div>
                    <div class="cmr-info">
                        <div class="cmr-title-row">
                            <span class="cmr-title">Examens Blancs Régionaux</span>
                            <span class="cmr-tag tag-exam">Évaluation d'État</span>
                        </div>
                        <div class="cmr-meta">
                            <span>Évaluation officielle de mi-parcours type examen national</span>
                        </div>
                    </div>
                </div>
                <div class="cmr-right">
                    <span class="cmr-status-badge">
                        <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        <span>Confirmé</span>
                    </span>
                </div>
            </div>

            {{-- Échéance 2 : Congés de Pâques --}}
            <div class="cal-milestone-row" data-type="holiday">
                <div class="cmr-left">
                    <div class="cmr-date-badge">
                        <span class="cdb-day">26</span>
                        <span class="cdb-month">Mars</span>
                    </div>
                    <div class="cmr-info">
                        <div class="cmr-title-row">
                            <span class="cmr-title">Début des Congés de Pâques</span>
                            <span class="cmr-tag tag-holiday">Congés Scolaires</span>
                        </div>
                        <div class="cmr-meta">
                            <span>Du 26 Mars après les cours au 11 Avril inclus · Période recommandée de révision</span>
                        </div>
                    </div>
                </div>
                <div class="cmr-right">
                    <span class="cmr-status-badge">
                        <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        <span>Arrêté officiel</span>
                    </span>
                </div>
            </div>

            {{-- Échéance 3 : Fête de l'Indépendance --}}
            <div class="cal-milestone-row" data-type="holiday">
                <div class="cmr-left">
                    <div class="cmr-date-badge">
                        <span class="cdb-day">27</span>
                        <span class="cdb-month">Avr</span>
                    </div>
                    <div class="cmr-info">
                        <div class="cmr-title-row">
                            <span class="cmr-title">Fête Nationale de l'Indépendance</span>
                            <span class="cmr-tag tag-official">Férié Légal</span>
                        </div>
                        <div class="cmr-meta">
                            <span>Journée fériée et chômée sur toute l'étendue du territoire</span>
                        </div>
                    </div>
                </div>
                <div class="cmr-right">
                    <span class="cmr-status-badge">
                        <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        <span>Férié national</span>
                    </span>
                </div>
            </div>

            {{-- Échéance 4 : Épreuves écrites du BAC 2 --}}
            <div class="cal-milestone-row" data-type="exam">
                <div class="cmr-left">
                    <div class="cmr-date-badge" style="background:#0E1022;border-color:#0E1022;">
                        <span class="cdb-day" style="color:#D4F25C;">15</span>
                        <span class="cdb-month" style="color:#FFFFFF;">Juin</span>
                    </div>
                    <div class="cmr-info">
                        <div class="cmr-title-row">
                            <span class="cmr-title">Début des Épreuves Écrites du BAC 2</span>
                            <span class="cmr-tag tag-exam">Examen National</span>
                        </div>
                        <div class="cmr-meta">
                            <span>Centre d'examen national · Épreuves écrites obligatoires toutes séries</span>
                        </div>
                    </div>
                </div>
                <div class="cmr-right">
                    <span class="cmr-status-badge">
                        <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        <span>Calendrier officiel</span>
                    </span>
                </div>
            </div>

        </div>
    </div>

</div>

{{-- SCRIPT DU CALENDRIER (COMPTE À REBOURS DYNAMIQUE & SÉLECTEUR DE CLASSE) --}}
<script>
// Fonction utilitaire pour calculer la date cible de la session à venir
function getUpcomingDate(monthIndex, day) {
    const now = new Date();
    let year = now.getFullYear();
    let target = new Date(year, monthIndex, day, 8, 0, 0);
    // Si la date de l'année en cours est déjà passée, on vise la session de l'année suivante
    if (target < now) {
        target = new Date(year + 1, monthIndex, day, 8, 0, 0);
    }
    return target;
}

// Base de données pédagogique des classes et examens
const classData = {
    'terminale_d': {
        isExam: true,
        title: "Baccalauréat Deuxième Partie (BAC 2 Série D)",
        monthIndex: 5, // Juin
        day: 15,
        datePrefix: "Épreuves officielles : Mardi 15 Juin",
        coeffText: "Coefficients phares : Maths (4) · PC (4) · SVT (4)",
        boxRightTitle: "Paliers de Mention au BAC 2",
        mentions: [
            { name: "Passable", score: "10,00" },
            { name: "Assez-Bien", score: "12,00" },
            { name: "Bien", score: "14,00" },
            { name: "Très Bien", score: "≥ 16,00" }
        ]
    },
    'terminale_a4': {
        isExam: true,
        title: "Baccalauréat Deuxième Partie (BAC 2 Série A4)",
        monthIndex: 5,
        day: 15,
        datePrefix: "Épreuves officielles : Mardi 15 Juin",
        coeffText: "Coefficients phares : Philo (4) · Français (3) · Anglais (3)",
        boxRightTitle: "Paliers de Mention au BAC 2",
        mentions: [
            { name: "Passable", score: "10,00" },
            { name: "Assez-Bien", score: "12,00" },
            { name: "Bien", score: "14,00" },
            { name: "Très Bien", score: "≥ 16,00" }
        ]
    },
    'terminale_c': {
        isExam: true,
        title: "Baccalauréat Deuxième Partie (BAC 2 Série C)",
        monthIndex: 5,
        day: 15,
        datePrefix: "Épreuves officielles : Mardi 15 Juin",
        coeffText: "Coefficients phares : Maths (5) · PC (5) · SVT (2)",
        boxRightTitle: "Paliers de Mention au BAC 2",
        mentions: [
            { name: "Passable", score: "10,00" },
            { name: "Assez-Bien", score: "12,00" },
            { name: "Bien", score: "14,00" },
            { name: "Très Bien", score: "≥ 16,00" }
        ]
    },
    'premiere_d': {
        isExam: true,
        title: "Baccalauréat Première Partie (BAC 1 Série D)",
        monthIndex: 4, // Mai
        day: 25,
        datePrefix: "Épreuves officielles : Fin Mai",
        coeffText: "Coefficients phares : Maths (3) · PC (3) · SVT (3)",
        boxRightTitle: "Paliers de Réussite au BAC 1",
        mentions: [
            { name: "Admis", score: "≥ 10,00" },
            { name: "Assez-Bien", score: "12,00" },
            { name: "Bien", score: "14,00" },
            { name: "Très Bien", score: "≥ 16,00" }
        ]
    },
    'troisieme': {
        isExam: true,
        title: "Brevet d'Études du Premier Cycle (BEPC)",
        monthIndex: 5,
        day: 8,
        datePrefix: "Épreuves officielles : Début Juin",
        coeffText: "Coefficients phares : Français (3) · Maths (3) · PC (2)",
        boxRightTitle: "Paliers de Réussite au BEPC",
        mentions: [
            { name: "Admis", score: "≥ 10,00" },
            { name: "Assez-Bien", score: "12,00" },
            { name: "Bien", score: "14,00" },
            { name: "Très Bien", score: "≥ 16,00" }
        ]
    },
    'cm2': {
        isExam: true,
        title: "Certificat d'Études du Premier Degré (CEPD)",
        monthIndex: 5,
        day: 1,
        datePrefix: "Épreuves officielles : Début Juin",
        coeffText: "Matières phares : Calcul · Dictée · ECM",
        boxRightTitle: "Paliers de Passage en 6ème",
        mentions: [
            { name: "Admis", score: "≥ 10,00" },
            { name: "T. Honneur", score: "≥ 14,00" },
            { name: "Félicitations", score: "≥ 16,00" },
            { name: "Excellence", score: "≥ 18,00" }
        ]
    },
    'seconde': {
        isExam: false,
        title: "Classe de Seconde (Année de Détermination)",
        monthIndex: 5,
        day: 25,
        datePrefix: "Fin de l'année scolaire : 25 Juin",
        coeffText: "Objectif 1ère : Maths + PC ≥ 11 (1ère D) · ≥ 13 (1ère C)",
        boxRightTitle: "Critères de Passage en 1ère",
        mentions: [
            { name: "Admis en 1ère", score: "≥ 10,00" },
            { name: "Voie Série D", score: "M+PC ≥ 11" },
            { name: "Voie Série C", score: "M+PC ≥ 13" },
            { name: "Voie Série A", score: "Fr+Ang ≥ 11" }
        ]
    }
};

// Mise à jour dynamique de l'interface
function onClassChange(classKey) {
    const data = classData[classKey];
    if (!data) return;

    // Titres et textes
    document.getElementById('heroExamTitle').innerText = data.title;
    document.getElementById('heroBoxRightTitle').innerText = data.boxRightTitle;
    document.getElementById('heroCoeffPill').innerText = data.coeffText;

    // Calcul dynamique de la session et du compte à rebours
    const targetDate = getUpcomingDate(data.monthIndex, data.day);
    const sessionYear = targetDate.getFullYear();
    document.getElementById('heroDateString').innerText = `${data.datePrefix} ${sessionYear}`;

    const now = new Date();
    const diffMs = targetDate - now;
    const diffDays = Math.max(1, Math.ceil(diffMs / (1000 * 60 * 60 * 24)));
    
    document.getElementById('countdownDays').innerText = diffDays;
    document.getElementById('countdownLabel').innerText = data.isExam 
        ? "Jours restants avant les épreuves" 
        : "Jours restants avant l'orientation";

    // Paliers de mention sobres (affichage horizontal sans boîtes lourdes)
    const grid = document.getElementById('mentionsGrid');
    grid.innerHTML = '';
    data.mentions.forEach(m => {
        const item = document.createElement('div');
        item.className = 'chec-mention-item';
        item.innerHTML = `
            <span class="cmi-grade">${m.name}</span>
            <span class="cmi-val">${m.score}</span>
        `;
        grid.appendChild(item);
    });
}

// Filtrage instantané des échéances
function filterMilestones(type, btn) {
    document.querySelectorAll('.cms-filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const rows = document.querySelectorAll('.cal-milestone-row');
    rows.forEach(row => {
        if (type === 'all') {
            row.style.display = 'flex';
        } else {
            row.style.display = (row.dataset.type === type) ? 'flex' : 'none';
        }
    });
}

// Initialisation au chargement
document.addEventListener('DOMContentLoaded', function() {
    onClassChange('terminale_d');
});
</script>
@endsection
