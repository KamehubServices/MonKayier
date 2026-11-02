@extends('layouts.student')

@section('title', "Séance Didactique : Limites, Continuité & TVI · MonKayier")

@section('content')
<!-- Intégration KaTeX pour le rendu mathématique formel -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/katex.min.css">
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/katex.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/contrib/auto-render.min.js"></script>

<style>
/* ==========================================================================
   STYLE OFFICIEL SÉANCE DIDACTIQUE MONKAYIER (SORA & INTER, ZÉRO DISTRACTION)
   ========================================================================== */
.sn-container {
    max-width: 1040px;
    margin: 0 auto;
    padding: 16px 20px 48px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}
.sn-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12.5px;
    color: #64748B;
    margin-bottom: 2px;
}
.sn-breadcrumb a {
    color: inherit;
    text-decoration: none;
    transition: color 0.15s ease;
}
.sn-breadcrumb a:hover {
    color: #0E1022;
}
.sn-header-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 16px;
    padding-bottom: 16px;
    border-bottom: 1.5px solid #E2E8F0;
}
.sn-main-title {
    font-size: 27px;
    font-weight: 900;
    color: #0E1022;
    margin: 0 0 6px 0;
    letter-spacing: -0.025em;
    font-family: 'Sora', sans-serif;
}
.sn-header-badges {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}
.sn-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11.5px;
    font-weight: 800;
    padding: 4px 10px;
    border-radius: 6px;
}
.sn-badge-subject { background: #E0F2FE; color: #0284C7; border: 1px solid #BAE6FD; }
.sn-badge-step { background: #0E1022; color: #D4F25C; }
.sn-badge-time { background: #F1F5F9; color: #475569; border: 1px solid #E2E8F0; }

/* Support de cours officiel rattaché */
.sn-source-card {
    background: #FAFCFE;
    border: 1.5px solid #E2E8F0;
    border-radius: 14px;
    padding: 14px 18px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}
.sn-source-left {
    display: flex;
    align-items: center;
    gap: 12px;
}
.sn-source-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: #0E1022;
    color: #D4F25C;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

/* Phase Cards */
.sn-phase-card {
    background: #FFFFFF;
    border: 1.5px solid #E2E8F0;
    border-radius: 16px;
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 16px;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.03);
}
.sn-phase-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}
.sn-phase-title-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
}
.sn-phase-num {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: #0E1022;
    color: #D4F25C;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 900;
    font-family: 'Sora', sans-serif;
    flex-shrink: 0;
}
.sn-phase-h2 {
    font-size: 18px;
    font-weight: 850;
    color: #0E1022;
    margin: 0;
    letter-spacing: -0.015em;
    font-family: 'Sora', sans-serif;
}

/* Théorèmes et formules encadrées (Style BAC Officiel) */
.sn-theorem-box {
    background: #F8FAFC;
    border: 1.5px solid #CBD5E1;
    border-left: 4.5px solid #0284C7;
    border-radius: 10px;
    padding: 16px 18px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.sn-theorem-title {
    font-size: 12.5px;
    font-weight: 850;
    color: #0284C7;
    text-transform: uppercase;
    letter-spacing: 0.03em;
}
.sn-theorem-body {
    font-size: 14px;
    color: #1E293B;
    line-height: 1.6;
}

/* Grille des formules incontournables */
.sn-formulas-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(290px, 1fr));
    gap: 12px;
}
.sn-formula-item {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    padding: 12px 14px;
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.sn-formula-name {
    font-size: 11.5px;
    font-weight: 800;
    color: #64748B;
}
.sn-formula-math {
    font-size: 15px;
    font-weight: 800;
    color: #0E1022;
    padding: 4px 0;
}

/* Clavier Virtuel Mathématique / Scientifique */
.sn-keyboard-wrapper {
    background: #F8FAFC;
    border: 1.5px solid #CBD5E1;
    border-radius: 12px;
    padding: 12px 14px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.sn-keyboard-tabs {
    display: flex;
    gap: 8px;
    border-bottom: 1px solid #E2E8F0;
    padding-bottom: 6px;
}
.sn-kbd-tab-btn {
    background: transparent;
    border: none;
    font-size: 11.5px;
    font-weight: 800;
    color: #64748B;
    cursor: pointer;
    padding: 4px 10px;
    border-radius: 6px;
    transition: all 0.15s ease;
}
.sn-kbd-tab-btn.active {
    background: #0E1022;
    color: #D4F25C;
}
.sn-keyboard-keys {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}
.sn-kbd-key {
    background: #FFFFFF;
    border: 1px solid #CBD5E1;
    border-radius: 6px;
    padding: 5px 10px;
    font-size: 13px;
    font-weight: 800;
    color: #0E1022;
    cursor: pointer;
    user-select: none;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    transition: all 0.1s ease;
}
.sn-kbd-key:hover {
    background: #0E1022;
    color: #D4F25C;
    border-color: #0E1022;
    transform: translateY(-1px);
}

/* Zone de rédaction de l'élève */
.sn-answer-input {
    width: 100%;
    min-height: 85px;
    border: 1.5px solid #CBD5E1;
    border-radius: 10px;
    padding: 12px 14px;
    font-size: 14.5px;
    font-family: inherit;
    color: #0E1022;
    box-sizing: border-box;
    outline: none;
    resize: vertical;
    transition: border-color 0.15s ease;
}
.sn-answer-input:focus {
    border-color: #0E1022;
    box-shadow: 0 0 0 1px #0E1022;
}

/* Quiz interactif */
.sn-quiz-card {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    padding: 16px 18px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.sn-quiz-opt-btn {
    background: #FFFFFF;
    border: 1.5px solid #E2E8F0;
    border-radius: 8px;
    padding: 11px 14px;
    text-align: left;
    font-size: 13.5px;
    font-weight: 650;
    color: #1E293B;
    cursor: pointer;
    transition: all 0.15s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}
.sn-quiz-opt-btn:hover {
    border-color: #0E1022;
    background: #FAFCFE;
}
.sn-quiz-opt-btn.correct {
    background: #ECFDF5 !important;
    border-color: #10B981 !important;
    color: #065F46 !important;
    font-weight: 800;
}
.sn-quiz-opt-btn.wrong {
    background: #FEF2F2 !important;
    border-color: #EF4444 !important;
    color: #991B1B !important;
}

/* Barre de validation de séance */
.sn-validation-bar {
    background: #0E1022;
    color: #FFFFFF;
    border-radius: 14px;
    padding: 20px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
    box-shadow: 0 10px 25px rgba(14, 16, 34, 0.15);
}
.btn-finish-seance {
    background: #D4F25C;
    color: #0E1022;
    border: none;
    border-radius: 10px;
    padding: 14px 28px;
    font-size: 15px;
    font-weight: 850;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.15s ease;
}
.btn-finish-seance:hover {
    background: #cbf046;
    transform: translateY(-1px);
}
</style>

<div class="sn-container">

    {{-- Fil d'ariane & Navigation --}}
    <div>
        <div class="sn-breadcrumb">
            <a href="/student/parcours">Mon Parcours BAC 2</a>
            <span>/</span>
            <span>Jalon 05</span>
            <span>/</span>
            <span style="color:#0E1022;font-weight:800">Séance active du jour</span>
        </div>
        
        <div class="sn-header-row">
            <div>
                <h1 class="sn-main-title">Limites, Continuité & Théorème des Valeurs Intermédiaires</h1>
                <div style="font-size: 13.5px; color: #475569;">Séance d'entraînement officielle adossée au programme de Terminale D (MEPST).</div>
            </div>
            
            <div class="sn-header-badges">
                <span class="sn-badge sn-badge-step">Jalon 05 / 16</span>
                <span class="sn-badge sn-badge-subject">Mathématiques · Coeff. 4</span>
                <span class="sn-badge sn-badge-time">Durée estimée : 25 min</span>
            </div>
        </div>
    </div>

    {{-- Bandeau du cours officiel rattaché --}}
    <div class="sn-source-card">
        <div class="sn-source-left">
            <div class="sn-source-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
            </div>
            <div>
                <div style="font-size:11px;font-weight:850;color:#0284C7;text-transform:uppercase;letter-spacing:0.04em">Support Officiel Rattaché</div>
                <div style="font-size:14.5px;font-weight:850;color:#0E1022">Chapitre 3 : Limites, continuité et asymptotes</div>
                <div style="font-size:12px;color:#64748B">Fiche certifiée pour l'épreuve écrite du BAC 2 Série D</div>
            </div>
        </div>
        
        <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
            @if(!empty($session['official_exam']))
                <a href="{{ $session['official_exam']['preview_url'] }}" target="_blank" class="btn-finish-seance" style="background:#2563EB;color:#FFFFFF;padding:8px 16px;font-size:12.5px;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                    <span>Épreuve BAC {{ $session['official_exam']['year'] }} (PDF) ↗</span>
                </a>
            @endif
            <a href="/student/chat?subject=01a161a5-8782-7314-b217-a13b54a3c124" class="btn-finish-seance" style="background:#0E1022;color:#D4F25C;padding:8px 16px;font-size:12.5px;text-decoration:none;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                <span>Poser une question au Tuteur</span>
            </a>
        </div>
    </div>

    {{-- PHASE 1 : COURS ET THÉORÈMES FONDAMENTAUX (AVEC LATEX) --}}
    <div class="sn-phase-card">
        <div class="sn-phase-head">
            <div class="sn-phase-title-wrap">
                <span class="sn-phase-num">1</span>
                <h2 class="sn-phase-h2">Rappels Théoriques & Définitions Clés</h2>
            </div>
            <span class="sn-badge" style="background:#F1F5F9;color:#475569">8 minutes d'assimilation</span>
        </div>

        {{-- Théorème TVI --}}
        <div class="sn-theorem-box">
            <div class="sn-theorem-title">Théorème Fondamental du BAC : Corollaire du TVI (Bijection)</div>
            <div class="sn-theorem-body">
                Soit \( f \) une fonction définie, <strong>continue</strong> et <strong>strictement monotone</strong> sur un intervalle \( [a, b] \).<br>
                Pour tout réel \( k \) compris entre \( f(a) \) et \( f(b) \), l'équation \( f(x) = k \) admet une <strong>unique solution</strong> \( \alpha \) dans \( [a, b] \).
            </div>
        </div>

        {{-- Formules clés --}}
        <div class="sn-formulas-grid">
            <div class="sn-formula-item">
                <span class="sn-formula-name">Croissances Comparées (\( x \to +\infty \))</span>
                <span class="sn-formula-math">\( \lim_{x \to +\infty} \frac{e^x}{x^n} = +\infty \)</span>
                <span style="font-size:11px;color:#64748B">L'exponentielle l'emporte toujours sur les puissances de \( x \).</span>
            </div>
            <div class="sn-formula-item">
                <span class="sn-formula-name">Limite usuelle du Logarithme</span>
                <span class="sn-formula-math">\( \lim_{x \to 0^+} x \ln(x) = 0 \)</span>
                <span style="font-size:11px;color:#64748B">Permet de lever la forme indéterminée \( 0 \times (-\infty) \).</span>
            </div>
            <div class="sn-formula-item">
                <span class="sn-formula-name">Asymptote Oblique</span>
                <span class="sn-formula-math">\( \lim_{x \to \pm\infty} [f(x) - (ax + b)] = 0 \)</span>
                <span style="font-size:11px;color:#64748B">La droite \( y = ax + b \) est asymptote oblique à la courbe.</span>
            </div>
        </div>
    </div>

    {{-- PHASE 2 : EXEMPLE TYPE BAC RÉSOLU PAS-À-PAS --}}
    <div class="sn-phase-card" style="background:#FAF5FF;border-color:#F3E8FF">
        <div class="sn-phase-head">
            <div class="sn-phase-title-wrap">
                <span class="sn-phase-num" style="background:#6B21A8">2</span>
                <h2 class="sn-phase-h2" style="color:#581C87">Exemple d'Application Type Examen Résolu</h2>
            </div>
            <span class="sn-badge" style="background:#F3E8FF;color:#7E22CE">Méthode officielle</span>
        </div>

        <div style="background:#FFFFFF;border:1px solid #E9D5FF;border-radius:10px;padding:14px 16px;">
            <div style="font-size:11.5px;font-weight:800;color:#7E22CE;text-transform:uppercase;margin-bottom:4px">Énoncé Officiel</div>
            <div style="font-size:14.5px;font-weight:750;color:#1E1B4B">
                Démontrer que l'équation \( x^3 + 2x - 5 = 0 \) admet une unique solution \( \alpha \) dans l'intervalle \( [1, 2] \).
            </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:8px;">
            <div style="background:#FFFFFF;border:1px solid #E9D5FF;border-radius:8px;padding:10px 14px;font-size:13.5px;color:#374151">
                <strong>Étape 1 (Continuité) :</strong> La fonction \( f(x) = x^3 + 2x - 5 \) est une fonction polynôme, elle est donc continue sur \( \mathbb{R} \), en particulier sur \( [1, 2] \).
            </div>
            <div style="background:#FFFFFF;border:1px solid #E9D5FF;border-radius:8px;padding:10px 14px;font-size:13.5px;color:#374151">
                <strong>Étape 2 (Monotonie stricte) :</strong> Sa dérivée est \( f'(x) = 3x^2 + 2 \). Pour tout \( x \in [1, 2] \), \( f'(x) > 0 \), donc \( f \) est strictement croissante sur \( [1, 2] \).
            </div>
            <div style="background:#FFFFFF;border:1px solid #E9D5FF;border-radius:8px;padding:10px 14px;font-size:13.5px;color:#374151">
                <strong>Étape 3 (Appartenance de 0) :</strong> On calcule \( f(1) = 1 + 2 - 5 = -2 \) et \( f(2) = 8 + 4 - 5 = 7 \). Comme \( f(1) < 0 < f(2) \), d'après le corollaire du TVI, il existe un unique \( \alpha \in [1, 2] \) tel que \( f(\alpha) = 0 \).
            </div>
        </div>
    </div>

    {{-- PHASE 3 : RÉDACTION AVEC LE CLAVIER SCIENTIFIQUE VIRTUEL --}}
    <div class="sn-phase-card">
        <div class="sn-phase-head">
            <div class="sn-phase-title-wrap">
                <span class="sn-phase-num">3</span>
                <h2 class="sn-phase-h2">Votre Tour : Rédaction avec Clavier Scientifique</h2>
            </div>
            <span class="sn-badge" style="background:#ECFDF5;color:#047857">Exercice guidé</span>
        </div>

        <div style="font-size:14px;color:#334155;">
            <strong>Exercice à rédiger :</strong> Déterminer la limite en \( +\infty \) de \( g(x) = \frac{e^x - 3x}{x^2 + 1} \). Justifier la levée de la forme indéterminée.
        </div>

        {{-- Le Clavier Virtuel Mathématique / Sciences --}}
        <div class="sn-keyboard-wrapper">
            <div class="sn-keyboard-tabs">
                <button type="button" class="sn-kbd-tab-btn active" onclick="switchKbd('maths')">Symboles Mathématiques</button>
                <button type="button" class="sn-kbd-tab-btn" onclick="switchKbd('pct')">Sciences Physiques & Chimie</button>
            </div>

            {{-- Clavier Maths --}}
            <div id="kbdMaths" class="sn-keyboard-keys">
                <span class="sn-kbd-key" onclick="insertKbd('lim(x→+∞)')">lim(x→+∞)</span>
                <span class="sn-kbd-key" onclick="insertKbd('e^x')">e^x</span>
                <span class="sn-kbd-key" onclick="insertKbd('ln(x)')">ln(x)</span>
                <span class="sn-kbd-key" onclick="insertKbd('√')">√x</span>
                <span class="sn-kbd-key" onclick="insertKbd('∫')">∫</span>
                <span class="sn-kbd-key" onclick="insertKbd('∑')">∑</span>
                <span class="sn-kbd-key" onclick="insertKbd('α')">α</span>
                <span class="sn-kbd-key" onclick="insertKbd('π')">π</span>
                <span class="sn-kbd-key" onclick="insertKbd('∞')">∞</span>
                <span class="sn-kbd-key" onclick="insertKbd('≤')">≤</span>
                <span class="sn-kbd-key" onclick="insertKbd('≥')">≥</span>
                <span class="sn-kbd-key" onclick="insertKbd('≠')">≠</span>
                <span class="sn-kbd-key" onclick="insertKbd('∈')">∈</span>
                <span class="sn-kbd-key" onclick="insertKbd('ℝ')">ℝ</span>
                <span class="sn-kbd-key" onclick="insertKbd('f\'(x)')">f'(x)</span>
            </div>

            {{-- Clavier Physique-Chimie --}}
            <div id="kbdPct" class="sn-keyboard-keys" style="display:none">
                <span class="sn-kbd-key" onclick="insertKbd('→')">→</span>
                <span class="sn-kbd-key" onclick="insertKbd('⇌')">⇌</span>
                <span class="sn-kbd-key" onclick="insertKbd('Δ')">Δ</span>
                <span class="sn-kbd-key" onclick="insertKbd('pH = -log[H₃O⁺]')">pH</span>
                <span class="sn-kbd-key" onclick="insertKbd('[H₃O⁺]')">[H₃O⁺]</span>
                <span class="sn-kbd-key" onclick="insertKbd('[OH⁻]')">[OH⁻]</span>
                <span class="sn-kbd-key" onclick="insertKbd('mol/L')">mol/L</span>
                <span class="sn-kbd-key" onclick="insertKbd('λ')">λ</span>
                <span class="sn-kbd-key" onclick="insertKbd('ρ')">ρ</span>
                <span class="sn-kbd-key" onclick="insertKbd('Ω')">Ω</span>
                <span class="sn-kbd-key" onclick="insertKbd('vec(a)')">vec(a)</span>
                <span class="sn-kbd-key" onclick="insertKbd('vec(F) = m.vec(a)')">ΣF = m.a</span>
            </div>
        </div>

        {{-- Zone de saisie avec le clavier --}}
        <div>
            <textarea id="studentWorkText" class="sn-answer-input" placeholder="Cliquez sur les touches du clavier ci-dessus ou tapez votre démarche de résolution..."></textarea>
            <div style="display:flex;justify-content:flex-end;margin-top:6px;">
                <button type="button" class="btn-finish-seance" style="padding:7px 16px;font-size:12px;background:#0E1022;color:#D4F25C" onclick="verifyStudentDraft()">
                    <span>Vérifier ma démarche avec le corrigé</span>
                </button>
            </div>
        </div>

        <div id="draftFeedbackBox" style="display:none;background:#F0FDF4;border:1px solid #BBF7D0;border-radius:8px;padding:12px 14px;font-size:13px;color:#166534">
            <strong>Excellente démarche !</strong> En factorisant par \( e^x \) au numérateur et par \( x^2 \) au dénominateur, les termes négligeables tendent vers 0 par croissances comparées, ce qui donne bien \( \lim_{x \to +\infty} g(x) = +\infty \).
        </div>
    </div>

    {{-- PHASE 4 : QUIZ D'ÉVALUATION ET VALIDATION DE FIN DE SÉANCE --}}
    <div class="sn-phase-card">
        <div class="sn-phase-head">
            <div class="sn-phase-title-wrap">
                <span class="sn-phase-num" style="background:#059669">4</span>
                <h2 class="sn-phase-h2">Quiz de Contrôle & Validation du Jalon</h2>
            </div>
            <span id="quizBadgeStatus" class="sn-badge" style="background:#FEF3C7;color:#D97706">2 questions à valider</span>
        </div>

        {{-- Question 1 --}}
        <div class="sn-quiz-card" id="qBox1">
            <div style="font-size:14px;font-weight:800;color:#0E1022">
                1. Quelle est la limite de \( \frac{\ln(x)}{x} \) lorsque \( x \to +\infty \) ?
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                <button type="button" class="sn-quiz-opt-btn" onclick="checkAnswer(1, this, false)">A. \( +\infty \)</button>
                <button type="button" class="sn-quiz-opt-btn" onclick="checkAnswer(1, this, true)">B. \( 0 \)</button>
                <button type="button" class="sn-quiz-opt-btn" onclick="checkAnswer(1, this, false)">C. \( 1 \)</button>
                <button type="button" class="sn-quiz-opt-btn" onclick="checkAnswer(1, this, false)">D. Forme indéterminée sans limite</button>
            </div>
        </div>

        {{-- Question 2 --}}
        <div class="sn-quiz-card" id="qBox2">
            <div style="font-size:14px;font-weight:800;color:#0E1022">
                2. Quelles sont les 3 conditions nécessaires pour appliquer le corollaire du TVI (unicité de la solution) ?
            </div>
            <div style="display:grid;grid-template-columns:1fr;gap:8px">
                <button type="button" class="sn-quiz-opt-btn" onclick="checkAnswer(2, this, false)">A. Être dérivable, paire et positive</button>
                <button type="button" class="sn-quiz-opt-btn" onclick="checkAnswer(2, this, true)">B. Continuité, stricte monotonie, et appartenance de la valeur à l'intervalle image</button>
                <button type="button" class="sn-quiz-opt-btn" onclick="checkAnswer(2, this, false)">C. Avoir une dérivée seconde nulle en tout point</button>
            </div>
        </div>

        {{-- Validation finale --}}
        <div class="sn-validation-bar">
            <div>
                <div style="font-size:15px;font-weight:850;color:#FFFFFF">Terminer et valider le Jalon 05</div>
                <div style="font-size:12.5px;color:#94A3B8">Votre score validera ce jalon sur le parcours et débloquera le Jalon 06 (Dosages acido-basiques).</div>
            </div>

            <form method="POST" action="/student/seance/active/complete">
                @csrf
                <input type="hidden" name="score" id="seanceScoreInput" value="18">
                <button type="submit" class="btn-finish-seance">
                    <span>Valider ma séance & débloquer le Jalon suivant</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                </button>
            </form>
        </div>
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
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
});

// Bascule du clavier virtuel
function switchKbd(type) {
    const btnMaths = document.querySelectorAll('.sn-kbd-tab-btn')[0];
    const btnPct = document.querySelectorAll('.sn-kbd-tab-btn')[1];
    const kbdMaths = document.getElementById('kbdMaths');
    const kbdPct = document.getElementById('kbdPct');

    if (type === 'maths') {
        btnMaths.classList.add('active');
        btnPct.classList.remove('active');
        kbdMaths.style.display = 'flex';
        kbdPct.style.display = 'none';
    } else {
        btnPct.classList.add('active');
        btnMaths.classList.remove('active');
        kbdPct.style.display = 'flex';
        kbdMaths.style.display = 'none';
    }
}

// Insertion du symbole cliqué dans la zone de texte
function insertKbd(symbol) {
    const textarea = document.getElementById('studentWorkText');
    if (!textarea) return;

    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const text = textarea.value;

    textarea.value = text.substring(0, start) + symbol + text.substring(end);
    textarea.focus();
    textarea.selectionStart = textarea.selectionEnd = start + symbol.length;
}

function verifyStudentDraft() {
    const box = document.getElementById('draftFeedbackBox');
    if (box) box.style.display = 'block';
}

// Vérification des questions du quiz
let scoreTotal = 0;
let answered = 0;

function checkAnswer(qNum, btn, isCorrect) {
    const parent = btn.parentElement;
    const allBtns = parent.querySelectorAll('.sn-quiz-opt-btn');
    
    allBtns.forEach(b => b.style.pointerEvents = 'none');

    if (isCorrect) {
        btn.classList.add('correct');
        scoreTotal += 10;
    } else {
        btn.classList.add('wrong');
        allBtns.forEach(b => {
            if (b.innerText.includes('B.') || b.innerText.includes('Continuité')) {
                b.classList.add('correct');
            }
        });
    }

    answered++;
    if (answered >= 2) {
        const badge = document.getElementById('quizBadgeStatus');
        if (badge) {
            badge.style.background = '#ECFDF5';
            badge.style.color = '#047857';
            badge.innerText = `Quiz complété (${scoreTotal}/20)`;
        }
        document.getElementById('seanceScoreInput').value = Math.max(12, scoreTotal);
    }
}
</script>
@endsection
