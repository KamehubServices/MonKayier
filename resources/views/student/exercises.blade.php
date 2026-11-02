@extends('layouts.student')

@section('title', 'Banque d\'exercices & Entraînement — MonKayier')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js"></script>
<div id="exercisesApp" class="exercises-root" data-current-subject="MATHS">

    <!-- ========================================================= -->
    <!-- BARRE SUPÉRIEURE : STEPPER + RETOUR DYNAMIQUE + HISTORIQUE-->
    <!-- ========================================================= -->
    <div id="wizardStepper" class="wizard-stepper">
        <div class="stepper-track">
            <div class="step-indicator is-active" data-step="1" id="stepPill1" onclick="goToStep(1)">
                <span class="step-num">1</span>
                <span class="step-label">Matière</span>
            </div>
            <div class="step-divider" id="stepDiv1"></div>
            <div class="step-indicator" data-step="2" id="stepPill2" onclick="goToStep(2)">
                <span class="step-num">2</span>
                <span class="step-label">Chapitres</span>
            </div>
            <div class="step-divider" id="stepDiv2"></div>
            <div class="step-indicator" data-step="3" id="stepPill3" onclick="goToStep(3)">
                <span class="step-num">3</span>
                <span class="step-label">Niveau & Format</span>
            </div>
            <div class="step-divider" id="stepDiv3"></div>
            <div class="step-indicator" data-step="4" id="stepPill4">
                <span class="step-num">4</span>
                <span class="step-label">Épreuve Officielle</span>
            </div>
        </div>

        <div class="stepper-right-actions">
            <!-- Bouton Retour déplacé en haut dans l'en-tête pour toutes les étapes (Flèche Image 2) -->
            <button type="button" id="btnTopBackNav" class="btn-top-back" onclick="handleTopBackClick()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
                <span id="btnTopBackNavText">Retour au Parcours</span>
            </button>

            <!-- Bouton Historique des Devoirs -->
            <button type="button" class="btn-history-pill" onclick="toggleHistoryModal(true)">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                <span>Historique des devoirs</span>
                <span class="history-count-badge" id="historyCounterBadge">3</span>
            </button>
        </div>
    </div>


    <!-- ========================================================= -->
    <!-- INTERFACE DÉDIÉE · ÉTAPE 1 : CHOISIR LA MATIÈRE           -->
    <!-- BOXES MINIMALISTES AVEC EFFET 3D FLIP AU SURVOL           -->
    <!-- ========================================================= -->
    <div id="stepView1" class="wizard-step-view is-current">
        <div class="step-view-header-row">
            <div>
                <h1 class="step-view-title">Sur quelle discipline veux-tu t'entraîner ?</h1>
                <p class="step-view-sub">Sélectionne ta matière d'examen. Les coefficients officiels sont appliqués.</p>
            </div>
            <!-- Bouton Continuer en haut à droite (Flèche Image 1) -->
            <button type="button" id="btnContinueStep1" class="btn-lime-nav" onclick="goToStep(2)">
                <span>Continuer avec <b id="selectedSubjectLabel">Mathématiques</b></span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </button>
        </div>

        <!-- Grille de cartes 3D Flip minimalistes (style box étape 2) -->
        <div class="disciplines-flip-grid">
            @foreach($subjectsList as $s)
                <div class="subject-flip-card {{ $s['code'] === 'MATHS' ? 'is-selected' : '' }}"
                     data-subject-code="{{ $s['code'] }}"
                     data-subject-name="{{ $s['name'] }}"
                     data-subject-coeff="{{ $s['coeff'] }}"
                     data-chapters="{{ json_encode($s['chapters']) }}"
                     onclick="handleSubjectSelect('{{ $s['code'] }}')">
                    
                    <div class="subject-flip-inner">
                        <!-- FACE AVANT : Épurée (nom + badge coeff + checkmark) -->
                        <div class="subject-flip-front">
                            <div class="flip-front-header">
                                <span class="coeff-clean-badge">{{ $s['coeff'] }}</span>
                                <span class="select-check-circle">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                </span>
                            </div>
                            <div class="flip-front-body">
                                <div class="subject-minimal-name">{{ $s['name'] }}</div>
                            </div>
                        </div>

                        <!-- FACE ARRIÈRE : Tourne en 3D au passage de la souris -->
                        <div class="subject-flip-back">
                            <div class="flip-back-tag">Programme Officiel</div>
                            <div class="flip-back-title">{{ $s['name'] }}</div>
                            <div class="flip-back-meta">{{ count($s['chapters']) }} chapitres au programme</div>
                            <div class="flip-back-btn">Cliquer pour choisir →</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>


    <!-- ========================================================= -->
    <!-- INTERFACE DÉDIÉE · ÉTAPE 2 : CHOIX MULTI-CHAPITRES       -->
    <!-- ========================================================= -->
    <div id="stepView2" class="wizard-step-view" style="display:none">
        <div class="step-view-header-row">
            <div>
                <h1 class="step-view-title">Quels chapitres souhaites-tu réviser ?</h1>
                <p class="step-view-sub">Tu peux sélectionner <b>un ou plusieurs chapitres</b> pour composer ton épreuve.</p>
            </div>
            
            <!-- Seul le bouton d'action principal est ici (le bouton retour est en haut dans l'en-tête) -->
            <button type="button" id="btnContinueStep2" class="btn-lime-nav" onclick="goToStep(3)">
                <span id="btnContinueStep2Text">Continuer (1 chapitre)</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </button>
        </div>

        <div class="chapter-selection-toolbar">
            <div class="toolbar-info">
                <span>Matière active :</span>
                <b id="activeSubjectBadgeName">Mathématiques</b>
                <span class="active-coeff-badge" id="activeSubjectBadgeCoeff">Coeff. 4</span>
            </div>
            <div class="toolbar-actions">
                <button type="button" class="btn-small-link" onclick="selectAllChapters(true)">Tout sélectionner</button>
                <span class="toolbar-sep">·</span>
                <button type="button" class="btn-small-link" onclick="selectAllChapters(false)">Tout désélectionner</button>
            </div>
        </div>

        <!-- Grille de tuiles de chapitres avec multi-sélection -->
        <div id="chaptersContainer" class="chapters-grid"></div>
    </div>


    <!-- ========================================================= -->
    <!-- INTERFACE DÉDIÉE · ÉTAPE 3 : CALIBRE NIVEAU & FORMAT      -->
    <!-- DESIGN AMÉLIORÉ : BOXES PLUS CARRÉES, PROPORTIONNÉES & ALIGNÉES -->
    <!-- ========================================================= -->
    <div id="stepView3" class="wizard-step-view" style="display:none">
        <div class="step-view-header-row">
            <div>
                <h1 class="step-view-title">Calibre le niveau d'exigence de ton épreuve</h1>
                <p class="step-view-sub">Choisis la difficulté et le format de ton épreuve nationale.</p>
            </div>

            <!-- Seul le bouton principal est ici (le bouton retour est dans l'en-tête supérieur) -->
            <button type="button" id="btnLaunchExamGeneration" class="btn-lime-launch" onclick="launchOfficialExamGeneration()">
                <span>Générer mon épreuve officielle (Barème 20 pts)</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </button>
        </div>

        <!-- 3 Paliers Carrés, Bien Proportionnés & Alignés -->
        <div class="difficulty-square-grid">
            <!-- Box 1 -->
            <div class="diff-card-square" data-diff="easy" onclick="selectDifficultyTier('easy')">
                <div class="diff-square-top">
                    <span class="diff-square-pill pill-easy">Socle</span>
                    <span class="diff-square-check">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </span>
                </div>
                <div class="diff-square-center">
                    <span class="diff-level-tag">NIVEAU 1</span>
                    <h3 class="diff-square-heading">Fondamental</h3>
                </div>
                <div class="diff-square-bottom">
                    <span class="diff-summary-line">Formules clés & questions directes</span>
                </div>
            </div>

            <!-- Box 2 -->
            <div class="diff-card-square is-selected" data-diff="medium" onclick="selectDifficultyTier('medium')">
                <div class="diff-square-ribbon">Recommandé BAC</div>
                <div class="diff-square-top">
                    <span class="diff-square-pill pill-medium">Officiel BAC 2</span>
                    <span class="diff-square-check">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </span>
                </div>
                <div class="diff-square-center">
                    <span class="diff-level-tag">NIVEAU 2</span>
                    <h3 class="diff-square-heading">Standard National</h3>
                </div>
                <div class="diff-square-bottom">
                    <span class="diff-summary-line">Épreuve officielle avec barème 20 points</span>
                </div>
            </div>

            <!-- Box 3 -->
            <div class="diff-card-square" data-diff="hard" onclick="selectDifficultyTier('hard')">
                <div class="diff-square-top">
                    <span class="diff-square-pill pill-hard">Excellence</span>
                    <span class="diff-square-check">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </span>
                </div>
                <div class="diff-square-center">
                    <span class="diff-level-tag">NIVEAU 3</span>
                    <h3 class="diff-square-heading">Défi Majeur</h3>
                </div>
                <div class="diff-square-bottom">
                    <span class="diff-summary-line">Problème d'approfondissement & synthèse</span>
                </div>
            </div>
        </div>

        <!-- Format d'évaluation : 2 Grandes Box Carrées comme les 3 du dessus -->
        <div class="format-square-grid">
            <!-- Box Format 1 -->
            <div class="format-card-square is-selected" onclick="selectFormatOption('exam', this)">
                <div class="fmt-square-top">
                    <span class="fmt-square-pill pill-format-exam">Standard Examen</span>
                    <span class="fmt-square-check">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </span>
                </div>
                <div class="fmt-square-center">
                    <span class="fmt-tag-label">BARÈME 20 POINTS</span>
                    <h3 class="fmt-square-heading">Grand Sujet de Synthèse</h3>
                </div>
                <div class="fmt-square-bottom">
                    <span class="fmt-summary-line">Exercice 1, Exercice 2 et Problème selon le modèle national</span>
                </div>
            </div>

            <!-- Box Format 2 -->
            <div class="format-card-square" onclick="selectFormatOption('guided', this)">
                <div class="fmt-square-top">
                    <span class="fmt-square-pill pill-format-guided">Pédagogique</span>
                    <span class="fmt-square-check">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </span>
                </div>
                <div class="fmt-square-center">
                    <span class="fmt-tag-label">PAS À PAS</span>
                    <h3 class="fmt-square-heading">Exercice Guidé & Méthode</h3>
                </div>
                <div class="fmt-square-bottom">
                    <span class="fmt-summary-line">Questions progressives avec indices socratiques et repères</span>
                </div>
            </div>
        </div>
    </div>


    <!-- ========================================================= -->
    <!-- SAS D'ANIMATION DE GÉNÉRATION ÉPURÉ & ULTRA-RAPIDE        -->
    <!-- ========================================================= -->
    <div id="stepLoading" class="sas-overlay" style="display:none">
        <div class="sas-card-sleek">
            <div class="sas-spinner-sleek"></div>
            <h3 class="sas-title-sleek">Génération de l'épreuve officielle</h3>
            <p class="sas-sub-sleek">Application du barème national et calibration du sujet...</p>
            <div class="sas-line-progress">
                <div class="sas-line-fill"></div>
            </div>
        </div>
    </div>


    <!-- ========================================================= -->
    <!-- INTERFACE DÉDIÉE · ÉTAPE 4 : ATELIER D'EXAMEN IMMERSIF    -->
    <!-- TITRE SANS CONTOUR BLEU ET SANS ÉLÉMENTS BARRÉS           -->
    <!-- ========================================================= -->
    <div id="stepAtelier" class="atelier-container" style="display:none">
        <!-- Barre supérieure de l'Atelier -->
        <header class="atelier-topbar">
            <div class="atelier-topbar-left">
                <button type="button" class="atelier-back-btn" onclick="quitAtelierToWizard()">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    <span>Quitter l'épreuve</span>
                </button>

                <!-- Titre conservé sans contour bleu (Image 1) et sans badges tronqués -->
                <div class="atelier-clean-title-group">
                    <span id="atelierCleanSubjectTitle" class="atelier-subject-clean-text">Physique-Chimie et Technologie</span>
                </div>
            </div>

            <div class="atelier-topbar-right">
                <!-- BOUTON D'ACTION PRINCIPAL : SOUMETTRE MON DEVOIR -->
                <button type="button" id="btnHeaderSubmitWork" class="btn-header-submit-work" onclick="openSubmissionModal()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                    <span>Soumettre mon devoir</span>
                </button>

                <!-- Badge de note débloqué après soumission -->
                <div id="headerScoreBadge" class="header-score-badge" style="display:none" onclick="openSubmissionModal()">
                    <span class="h-score-val">16 / 20</span>
                    <span class="h-score-sub">Voir mon corrigé</span>
                </div>

                <!-- Confort visuel sans emojis -->
                <div class="visual-mode-toggle" role="group" aria-label="Mode visuel">
                    <button type="button" class="vmode-btn is-active" data-vmode="day" onclick="setVisualTheme('day')">Jour</button>
                    <button type="button" class="vmode-btn" data-vmode="sepia" onclick="setVisualTheme('sepia')">Sépia</button>
                    <button type="button" class="vmode-btn" data-vmode="night" onclick="setVisualTheme('night')">Nuit</button>
                </div>

                <!-- Toggle Tuteur IA -->
                <button type="button" id="btnToggleTutor" class="btn-toggle-tutor is-on" onclick="toggleTutorPanel()">
                    <span class="tutor-pulse-dot"></span>
                    <span>Tuteur IA</span>
                </button>

                <!-- Plein Écran Focus (Touche F) -->
                <button type="button" id="btnFullscreenFocus" class="btn-fullscreen-focus" onclick="toggleAtelierFullscreen()" title="Bascule en plein écran total (Touche F)">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"></path></svg>
                    <span class="fs-text">Plein Écran</span>
                    <kbd class="fs-kbd">F</kbd>
                </button>
            </div>
        </header>

        <!-- Barre d'onglets Énoncé / Corrigé -->
        <div id="atelierViewTabs" class="atelier-view-tabs" style="display:none">
            <button type="button" class="at-tab is-active" id="tabBtnStatement" onclick="switchAtelierView('statement')">Énoncé officiel</button>
            <button type="button" class="at-tab" id="tabBtnSolution" onclick="switchAtelierView('solution')">Corrigé type & Barème débloqué</button>
        </div>

        <!-- Corps de l'Atelier -->
        <div class="atelier-body">
            <!-- VOLET GAUCHE (70%) : SUJET PUR SANS BOÎTE DE SOUMISSION EN BAS -->
            <main class="atelier-sheet-column">
                <div class="atelier-sheet-content">

                    <!-- VUE 1 : ÉNONCÉ OFFICIEL DU TOGO (MEPST) -->
                    <div id="viewStatementWrap">
                        <!-- CARTOUCHE SPÉCIAL MONKAYIER (CONFORME AUX NORMES, SANS USURPATION DU MINISTÈRE) -->
                        <div class="monkayier-exam-header-card">
                            <div class="exam-header-grid-top">
                                <div class="header-col-left">
                                    <div class="header-brand-title">MONKAYIER · BANQUE D'ÉPREUVES D'ENTRAÎNEMENT</div>
                                    <div class="header-academic-sub">Plateforme d'Entraînement Académique & Préparation aux Examens Nationaux</div>
                                </div>
                                <div class="header-col-right">
                                    <div class="header-exam-name">BACCALAURÉAT DE L'ENSEIGNEMENT DU SECOND DEGRÉ (BAC 2)</div>
                                    <div class="header-session-name">ÉPREUVE BLANCHE D'ENTRAÎNEMENT NATIONALE</div>
                                    <div class="header-serie-badge">SÉRIE : D (SCIENTIFIQUE)</div>
                                </div>
                            </div>

                            <div class="exam-header-grid-meta">
                                <div class="meta-cell">
                                    <span class="meta-cell-label">ÉPREUVE :</span>
                                    <b id="paperMetaSubject" class="meta-cell-val">MATHÉMATIQUES</b>
                                </div>
                                <div class="meta-cell">
                                    <span class="meta-cell-label">DURÉE :</span>
                                    <b id="paperMetaDuration" class="meta-cell-val">4 HEURES</b>
                                </div>
                                <div class="meta-cell">
                                    <span class="meta-cell-label">COEFFICIENT :</span>
                                    <b id="paperMetaCoeff" class="meta-cell-val">4</b>
                                </div>
                                <div class="meta-cell">
                                    <span class="meta-cell-label">BARÈME TOTAL :</span>
                                    <b class="meta-cell-val val-green">20 POINTS</b>
                                </div>
                            </div>

                            <div class="exam-header-instructions">
                                <span class="inst-label">Consignes de l'épreuve :</span>
                                <span id="paperInstructionsText" class="inst-body">Sujet d'entraînement conçu selon les exigences et barèmes officiels nationaux. La calculatrice scientifique non programmable est autorisée. La qualité de la rédaction, la clarté et la rigueur des démonstrations sont des critères majeurs d'appréciation.</span>
                            </div>
                        </div>

                        <!-- SECTIONS D'EXERCICES AVEC BARÈMES OFFICIELS -->
                        <div id="examSectionsContainer" class="exam-sections-list"></div>
                    </div>

                    <!-- VUE 2 : CORRIGÉ OFFICIEL INTÉGRAL DÉBLOQUÉ -->
                    <div id="viewSolutionWrap" style="display:none">
                        <div class="unlocked-solution-card">
                            <div class="official-bareme-wrap">
                                <div class="bareme-header-label">Grille d'évaluation conforme aux critères officiels d'examen (Barème 20 points)</div>
                                <div id="baremeTableHolder" class="bareme-table-box"></div>
                            </div>

                            <div class="official-demonstration-wrap">
                                <div class="demo-header-label">Démonstration intégrale du corrigé type officiel</div>
                                <div id="fullDemoTextHolder" class="demo-content-box"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <!-- VOLET DROIT (30% - 390px, Redimensionnable à la souris) : TUTEUR SOCRATIQUE IA DÉDIÉ -->
            <aside id="atelierTutorSidebar" class="atelier-tutor-column">
                <div class="mk-split-resizer" title="Glisser vers la gauche ou la droite pour agrandir ou réduire l'espace du Tuteur IA (Double-clic pour alterner large / standard)">
                    <div class="mk-split-resizer-grip"></div>
                </div>
                <div class="tutor-chat-wrapper">
                    <div class="tutor-chat-header">
                        <div class="tutor-header-avatar">IA</div>
                        <div class="tutor-header-meta">
                            <div class="tutor-name">Tuteur Socratique IA</div>
                            <div class="tutor-status">En ligne · Connecté à l'épreuve officielle</div>
                        </div>
                    </div>

                    <div id="tutorMessagesContainer" class="tutor-messages-scroll">
                        <div class="tutor-msg msg-ai">
                            <div class="msg-bubble">
                                Bonjour {{ ($student->first_name ?? '') !== 'Nouvel' ? ($student->first_name ?? 'toi') : 'toi' }} ! Je suis ton tuteur IA pour cette épreuve officielle. Traite le sujet à ton rythme sur ton cahier, et clique sur <b>« Soumettre mon devoir »</b> en haut dès que tu as terminé !
                            </div>
                        </div>
                    </div>

                    <div id="tutorSuggestionsDrawer" class="mk-suggestions-drawer is-collapsed" style="margin: 0 12px 6px;">
                        <div class="mk-drawer-header">
                            <span class="mk-drawer-title">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#4F46E5" stroke-width="2.5"><path d="M12 2a7 7 0 0 1 7 7c0 2.38-1.19 4.47-3 5.74V17a1 1 0 0 1-1 1H9a1 1 0 0 1-1-1v-2.26C6.19 13.47 5 11.38 5 9a7 7 0 0 1 7-7z"></path><line x1="9" y1="21" x2="15" y2="21"></line></svg>
                                Aide méthodologique
                            </span>
                            <button type="button" class="mk-drawer-close-btn" onclick="window.mkToggleChatDrawer('suggestions', 'tutor', false)">
                                <span>Masquer</span>
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
                            </button>
                        </div>
                        <div class="mk-suggestions-list">
                            <button type="button" class="mk-sug-pill" onclick="sendTutorPrompt('Donne-moi un indice méthodologique pour l\'Exercice 1')">
                                <span>Indice méthodologique Ex. 1</span>
                                <span class="mk-sug-arrow">→</span>
                            </button>
                            <button type="button" class="mk-sug-pill" onclick="sendTutorPrompt('Quelle est la formule officielle à appliquer pour cette question ?')">
                                <span>Formule clé du chapitre</span>
                                <span class="mk-sug-arrow">→</span>
                            </button>
                            <button type="button" class="mk-sug-pill" onclick="sendTutorPrompt('Comment bien présenter et rédiger ma réponse sur le cahier ?')">
                                <span>Conseil de rédaction BAC</span>
                                <span class="mk-sug-arrow">→</span>
                            </button>
                        </div>
                    </div>

                    <div id="tutorSciKbdDrawer" class="mk-scikbd-drawer is-collapsed" style="margin: 0 12px 6px;">
                        <div class="mk-drawer-header">
                            <div class="mk-scikbd-tabs">
                                <button type="button" class="mk-scikbd-tab is-active" onclick="window.mkFilterSciKbd(this, 'all', 'tutorSciKbdGrid')">Tout</button>
                                <button type="button" class="mk-scikbd-tab" onclick="window.mkFilterSciKbd(this, 'math', 'tutorSciKbdGrid')">Maths</button>
                                <button type="button" class="mk-scikbd-tab" onclick="window.mkFilterSciKbd(this, 'chem', 'tutorSciKbdGrid')">Chimie & Phys</button>
                            </div>
                            <button type="button" class="mk-drawer-close-btn" onclick="window.mkToggleChatDrawer('scikbd', 'tutor', false)">
                                <span>Masquer</span>
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            </button>
                        </div>
                        <div id="tutorSciKbdGrid" class="mk-scikbd-grid">
                            <button type="button" class="mk-scikbd-key" data-cat="math" onclick="window.mkInsertSymbolToInput('tutorInput', '√(')">√x</button>
                            <button type="button" class="mk-scikbd-key" data-cat="math" onclick="window.mkInsertSymbolToInput('tutorInput', '²')">x²</button>
                            <button type="button" class="mk-scikbd-key" data-cat="math" onclick="window.mkInsertSymbolToInput('tutorInput', '^')">xⁿ</button>
                            <button type="button" class="mk-scikbd-key" data-cat="math" onclick="window.mkInsertSymbolToInput('tutorInput', 'lim(x→) ')">lim</button>
                            <button type="button" class="mk-scikbd-key" data-cat="math" onclick="window.mkInsertSymbolToInput('tutorInput', 'ln(')">ln</button>
                            <button type="button" class="mk-scikbd-key" data-cat="math" onclick="window.mkInsertSymbolToInput('tutorInput', 'e^')">eˣ</button>
                            <button type="button" class="mk-scikbd-key" data-cat="math" onclick="window.mkInsertSymbolToInput('tutorInput', '∫')">∫</button>
                            <button type="button" class="mk-scikbd-key" data-cat="math" onclick="window.mkInsertSymbolToInput('tutorInput', '∞')">∞</button>
                            <button type="button" class="mk-scikbd-key" data-cat="math" onclick="window.mkInsertSymbolToInput('tutorInput', 'π')">π</button>
                            <button type="button" class="mk-scikbd-key" data-cat="math" onclick="window.mkInsertSymbolToInput('tutorInput', 'Δ')">Δ</button>
                            <button type="button" class="mk-scikbd-key" data-cat="chem" onclick="window.mkInsertSymbolToInput('tutorInput', '[H₃O⁺]')">[H₃O⁺]</button>
                            <button type="button" class="mk-scikbd-key" data-cat="chem" onclick="window.mkInsertSymbolToInput('tutorInput', 'pH')">pH</button>
                            <button type="button" class="mk-scikbd-key" data-cat="chem" onclick="window.mkInsertSymbolToInput('tutorInput', '⇄')">⇄</button>
                            <button type="button" class="mk-scikbd-key" data-cat="chem" onclick="window.mkInsertSymbolToInput('tutorInput', '→')">→</button>
                        </div>
                    </div>

                    <div class="mk-chat-tools-bar" style="padding: 2px 12px 4px;">
                        <div class="mk-chat-tools-left">
                            <button type="button" id="tutorBtnToggleSug" class="mk-tool-toggle-btn" onclick="window.mkToggleChatDrawer('suggestions', 'tutor')">
                                <svg class="mk-tool-icon" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M12 2a7 7 0 0 1 7 7c0 2.38-1.19 4.47-3 5.74V17a1 1 0 0 1-1 1H9a1 1 0 0 1-1-1v-2.26C6.19 13.47 5 11.38 5 9a7 7 0 0 1 7-7z"></path><line x1="9" y1="21" x2="15" y2="21"></line></svg>
                                <span class="mk-tool-state-text">Idées d'aide</span>
                                <svg class="mk-tool-chevron" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
                            </button>
                            <button type="button" id="tutorBtnToggleKbd" class="mk-tool-toggle-btn" onclick="window.mkToggleChatDrawer('scikbd', 'tutor')">
                                <span class="mk-tool-fx-badge">fx</span>
                                <span class="mk-tool-state-text">Clavier scientifique</span>
                                <svg class="mk-tool-chevron" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
                            </button>
                        </div>
                    </div>

                    <div class="tutor-input-box">
                        <button type="button" id="tutorFxInlineBtn" onclick="window.mkToggleChatDrawer('scikbd', 'tutor')" class="immersion-fx-btn" title="Afficher / Masquer le clavier scientifique (fx)">
                            fx
                        </button>
                        <textarea id="tutorInput" rows="1" class="tutor-text-input" placeholder="Pose ta question de méthode..." onkeydown="handleTutorKey(event)"></textarea>
                        <button type="button" id="btnSendTutor" class="btn-send-tutor" onclick="sendTutorMessage()">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                        </button>
                    </div>
                </div>
            </aside>
        </div>
    </div>


    <!-- ========================================================= -->
    <!-- MODAL DÉDIÉE : SOUMISSION DE COPIE (DÉPORTÉE DE LA PAGE)   -->
    <!-- ========================================================= -->
    <div id="submissionModal" class="custom-modal-backdrop" style="display:none">
        <div class="custom-modal-box">
            <div class="modal-head">
                <div>
                    <h3 class="modal-title">Soumission de ma copie manuscrite</h3>
                    <p class="modal-sub">Dépose la photo de ton cahier pour débloquer ton évaluation et le corrigé officiel.</p>
                </div>
                <button type="button" class="modal-close-btn" onclick="closeSubmissionModal()">✕</button>
            </div>

            <!-- ÉTAT 1 : Formulaire de soumission -->
            <div id="modalSubmitForm">
                <div class="submission-tabs">
                    <button type="button" class="sub-tab-btn is-active" data-tab="photo" onclick="switchSubmissionTab('photo')">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                        <span>Photo de mon cahier (Recommandé)</span>
                    </button>
                    <button type="button" class="sub-tab-btn" data-tab="text" onclick="switchSubmissionTab('text')">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="4 7 4 4 20 4 20 7"></polyline><line x1="9" y1="20" x2="15" y2="20"></line><line x1="12" y1="4" x2="12" y2="20"></line></svg>
                        <span>Rédiger au clavier</span>
                    </button>
                </div>

                <div id="tabPhotoPanel" class="tab-panel is-active">
                    <div class="file-drop-zone" onclick="document.getElementById('photoInput').click()">
                        <input type="file" id="photoInput" accept="image/*" style="display:none" onchange="handlePhotoSelect(this)">
                        <div id="dropPromptView" class="drop-prompt-view">
                            <div class="drop-icon-circle">
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                            </div>
                            <div class="drop-text-headline">Prends en photo la page de ton cahier</div>
                            <div class="drop-text-detail">JPG, PNG, WEBP — Écriture manuscrite lisible</div>
                        </div>

                        <div id="dropPreviewView" class="drop-preview-view" style="display:none">
                            <img id="previewImageTag" src="" alt="Aperçu copie" class="copy-preview-image">
                            <div class="preview-footer-row">
                                <span id="previewNameLabel" class="preview-file-name">copie_manuscrite.jpg</span>
                                <button type="button" class="btn-clear-photo" onclick="clearSelectedPhoto(event)">Changer</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="tabTextPanel" class="tab-panel" style="display:none">
                    <label for="textAnswerArea" class="text-label">Rédige tes étapes de calcul :</label>
                    <textarea id="textAnswerArea" rows="6" class="clean-exam-textarea" placeholder="Exemple : Exercice 1 : 1.a zA = 2(cos pi/3 + i sin pi/3)..."></textarea>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel-modal" onclick="closeSubmissionModal()">Annuler</button>
                    <button type="button" id="btnSubmitExamCopy" class="btn-lime-submit-copy" onclick="submitExamProof()">
                        <span>Valider et obtenir ma note & corrigé</span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </button>
                </div>
            </div>

            <!-- ÉTAT 2 : Résultat & Diagnostic IA -->
            <div id="modalResultView" style="display:none">
                <div class="ai-diagnostic-card">
                    <div class="diag-banner-top">
                        <div class="diag-score-display">
                            <span class="score-big" id="resScoreNum">16</span>
                            <span class="score-total">/ 20</span>
                        </div>
                        <div class="diag-summary-wrap">
                            <div class="diag-official-mention" id="resMention">Très bonne démarche d'examen</div>
                            <div class="diag-ref-text">Évaluation basée sur les critères et référentiels officiels d'examen</div>
                        </div>
                    </div>

                    <p id="diagGeneralAppraisal" class="diag-text-body">
                        Excellente copie d'entraînement. La structure des démonstrations et l'emploi des théorèmes officiels sont rigoureux.
                    </p>

                    <div class="diag-split-points">
                        <div class="diag-pane pane-success">
                            <div class="pane-title">Points forts validés sur ta copie</div>
                            <ul id="diagStrengthsUl" class="pane-list"></ul>
                        </div>
                        <div class="diag-pane pane-warning">
                            <div class="pane-title">Points à consolider & Pièges d'examen</div>
                            <ul id="diagPitfallsUl" class="pane-list"></ul>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-lime-submit-copy" onclick="showUnlockedCorrection()">
                        <span>Consulter le corrigé officiel complet →</span>
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- ========================================================= -->
    <!-- MODAL DÉDIÉE : HISTORIQUE DES DEVOIRS GÉNÉRÉS & FAITS       -->
    <!-- ========================================================= -->
    <div id="historyModal" class="custom-modal-backdrop" style="display:none">
        <div class="custom-modal-box modal-box-large">
            <div class="modal-head">
                <div>
                    <h3 class="modal-title">Historique de mes devoirs & entraînements</h3>
                    <p class="modal-sub">Retrouve toutes tes épreuves générées, tes copies évaluées et leurs corrigés officiels.</p>
                </div>
                <button type="button" class="modal-close-btn" onclick="toggleHistoryModal(false)">✕</button>
            </div>

            <div class="history-filter-tabs">
                <button type="button" class="hist-filter-btn is-active" data-filter="all" onclick="filterHistory('all', this)">Tous mes devoirs</button>
                <button type="button" class="hist-filter-btn" data-filter="evaluated" onclick="filterHistory('evaluated', this)">Devoirs corrigés</button>
                <button type="button" class="hist-filter-btn" data-filter="pending" onclick="filterHistory('pending', this)">En attente de soumission</button>
            </div>

            <div id="historyItemsList" class="history-items-list"></div>
        </div>
    </div>

</div>

@push('styles')
<style>
/* ========================================================= */
/* VARIABLES & THÈME MONKAYIER                               */
/* ========================================================= */
:root {
    --mk-lime: #D4F25C;
    --mk-dark: #0E1022;
    --mk-navy: #1E293B;
    --mk-slate-bg: #F8FAFC;
    --mk-border: #E2E8F0;
    --mk-text-main: #0F172A;
    --mk-text-sub: #475569;
}

.exercises-root {
    width: 100%;
    margin: 0 auto;
    font-family: inherit;
    color: var(--mk-text-main);
}

/* ========================================================= */
/* STEPPER & HEADER SUPÉRIEUR                                */
/* ========================================================= */
.wizard-stepper {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    background: #FFFFFF;
    border: 1px solid var(--mk-border);
    border-radius: 16px;
    padding: 12px 18px;
    margin-bottom: 24px;
}
.stepper-track {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}
.step-indicator {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 6px 12px;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.15s ease;
}
.step-indicator:hover { background: #F1F5F9; }
.step-num {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #E2E8F0;
    color: #475569;
    font-size: 12px;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
}
.step-label {
    font-size: 13px;
    font-weight: 650;
    color: #64748B;
}

.step-indicator.is-active {
    background: #0E1022;
}
.step-indicator.is-active .step-num {
    background: var(--mk-lime);
    color: #0E1022;
}
.step-indicator.is-active .step-label {
    color: #FFFFFF;
}

.step-indicator.is-completed .step-num {
    background: #10B981;
    color: #FFFFFF;
}
.step-indicator.is-completed .step-label {
    color: #0F172A;
}

.step-divider {
    width: 20px;
    height: 2px;
    background: #E2E8F0;
}
.step-divider.is-active { background: #10B981; }

.stepper-right-actions {
    display: flex;
    align-items: center;
    gap: 10px;
}

/* BOUTON RETOUR DANS L'EN-TÊTE SUPÉRIEUR (Flèche Image 2) */
.btn-top-back {
    background: #F8FAFC;
    border: 1px solid var(--mk-border);
    border-radius: 999px;
    padding: 6px 14px;
    font-size: 12px;
    font-weight: 700;
    color: #334155;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.15s ease;
}
.btn-top-back:hover {
    background: #F1F5F9;
    border-color: #0E1022;
    color: #0E1022;
}

.btn-history-pill {
    background: #FFFFFF;
    border: 1.5px solid var(--mk-border);
    border-radius: 999px;
    padding: 6px 14px;
    font-size: 12.5px;
    font-weight: 700;
    color: #0E1022;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.15s ease;
}
.btn-history-pill:hover {
    background: #F8FAFC;
    border-color: #0E1022;
}
.history-count-badge {
    background: #0E1022;
    color: var(--mk-lime);
    font-size: 10.5px;
    font-weight: 800;
    padding: 1px 6px;
    border-radius: 999px;
}

/* ========================================================= */
/* LIGNE D'EN-TÊTE ÉTAPE 1, 2, 3                             */
/* ========================================================= */
.step-view-header-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 22px;
}
.step-view-title {
    font-size: 24px;
    font-weight: 800;
    color: #0E1022;
    margin: 0 0 4px 0;
    letter-spacing: -0.02em;
}
.step-view-sub {
    font-size: 13.5px;
    color: var(--mk-text-sub);
    margin: 0;
}

.btn-lime-nav, .btn-lime-launch {
    background: var(--mk-lime);
    color: #0E1022;
    border: none;
    border-radius: 8px !important;
    box-shadow: none !important;
    padding: 11px 20px;
    font-size: 13.5px;
    font-weight: 800;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: filter 0.15s ease, transform 0.15s ease;
    white-space: nowrap;
}
.btn-lime-nav:hover, .btn-lime-launch:hover {
    filter: brightness(0.95);
    transform: translateY(-1px);
}

/* ========================================================= */
/* ÉTAPE 1 : CARTES 3D FLIP STYLE ÉTAPE 2                    */
/* ========================================================= */
.disciplines-flip-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}
.subject-flip-card {
    background-color: transparent;
    perspective: 1000px;
    height: 125px;
    cursor: pointer;
    user-select: none;
    position: relative;
    border-radius: 14px;
}
.subject-flip-inner {
    position: relative;
    width: 100%;
    height: 100%;
    transition: transform 0.45s cubic-bezier(0.16, 1, 0.3, 1);
    transform-style: preserve-3d;
    border-radius: 14px;
}
.subject-flip-card:hover .subject-flip-inner {
    transform: rotateY(180deg);
}

.subject-flip-front,
.subject-flip-back {
    position: absolute;
    width: 100%;
    height: 100%;
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    border-radius: 14px;
    box-sizing: border-box;
}

.subject-flip-front {
    background: #FFFFFF;
    border: 1.5px solid var(--mk-border);
    padding: 16px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.subject-flip-card.is-selected .subject-flip-front {
    border-color: #0E1022;
    background: #F8FAFC;
    box-shadow: 0 0 0 2px #0E1022;
}
.flip-front-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.coeff-clean-badge {
    font-size: 11px;
    font-weight: 800;
    color: #2563EB;
    background: #EFF6FF;
    padding: 2px 8px;
    border-radius: 4px;
}
.select-check-circle {
    width: 18px;
    height: 18px;
    border-radius: 50%;
    border: 2px solid #CBD5E1;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.15s ease;
}
.subject-flip-card.is-selected .select-check-circle {
    background: #0E1022;
    border-color: #0E1022;
    color: var(--mk-lime);
}
.subject-minimal-name {
    font-size: 15px;
    font-weight: 750;
    color: #0E1022;
    line-height: 1.35;
}

.subject-flip-back {
    background: #0E1022;
    color: #FFFFFF;
    border: 1.5px solid #0E1022;
    padding: 16px;
    transform: rotateY(180deg);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.flip-back-tag {
    font-size: 10px;
    font-weight: 800;
    color: var(--mk-lime);
    text-transform: uppercase;
}
.flip-back-title {
    font-size: 14px;
    font-weight: 750;
    line-height: 1.3;
}
.flip-back-meta {
    font-size: 11.5px;
    color: #94A3B8;
}
.flip-back-btn {
    font-size: 11.5px;
    font-weight: 800;
    color: var(--mk-lime);
}

/* ========================================================= */
/* ÉTAPE 2 : MULTI-SÉLECTION DE CHAPITRES                    */
/* ========================================================= */
.chapter-selection-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #FFFFFF;
    border: 1px solid var(--mk-border);
    border-radius: 12px;
    padding: 10px 16px;
    margin-bottom: 16px;
    font-size: 13px;
}
.active-coeff-badge {
    background: #2563EB;
    color: #FFFFFF;
    font-size: 10.5px;
    font-weight: 800;
    padding: 2px 6px;
    border-radius: 4px;
    margin-left: 6px;
}
.btn-small-link {
    background: transparent;
    border: none;
    color: #2563EB;
    font-size: 12.5px;
    font-weight: 700;
    cursor: pointer;
}
.btn-small-link:hover { text-decoration: underline; }
.toolbar-sep { color: #CBD5E1; margin: 0 6px; }

.chapters-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 14px;
    margin-bottom: 24px;
}
.chapter-tile {
    background: #FFFFFF;
    border: 1.5px solid var(--mk-border);
    border-radius: 14px;
    padding: 16px 18px;
    cursor: pointer;
    transition: all 0.15s ease;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-height: 100px;
}
.chapter-tile:hover {
    border-color: #0E1022;
    transform: translateY(-2px);
}
.chapter-tile.is-selected {
    border-color: #0E1022;
    background: #F8FAFC;
    box-shadow: 0 0 0 2px #0E1022;
}
.chap-tile-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
}
.chap-num-tag {
    font-size: 10.5px;
    font-weight: 800;
    color: #64748B;
    background: #F1F5F9;
    padding: 2px 7px;
    border-radius: 4px;
}
.chap-check-circle {
    width: 20px;
    height: 20px;
    border-radius: 6px;
    border: 1.5px solid #CBD5E1;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.15s ease;
}
.chapter-tile.is-selected .chap-check-circle {
    background: #0E1022;
    border-color: #0E1022;
    color: var(--mk-lime);
}
.chap-title {
    font-size: 14px;
    font-weight: 750;
    color: #0E1022;
    margin: 0;
    line-height: 1.35;
}

/* ========================================================= */
/* ÉTAPE 3 : BOXES NIVEAU 3 PLUS CARRÉES & PROPORTIONNÉES    */
/* ========================================================= */
.difficulty-square-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 24px;
}
@media (max-width: 900px) {
    .difficulty-square-grid { grid-template-columns: 1fr; }
}

.diff-card-square {
    background: #FFFFFF;
    border: 1.5px solid var(--mk-border);
    border-radius: 18px;
    padding: 24px 20px;
    cursor: pointer;
    transition: all 0.18s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-height: 195px;
    box-sizing: border-box;
}
.diff-card-square:hover {
    border-color: #0E1022;
    transform: translateY(-3px);
    box-shadow: 0 10px 24px -10px rgba(15, 23, 42, 0.12);
}
.diff-card-square.is-selected {
    border-color: #0E1022;
    background: #F8FAFC;
    box-shadow: 0 0 0 2px #0E1022;
}

.diff-square-ribbon {
    position: absolute;
    top: -10px;
    right: 18px;
    background: #0E1022;
    color: var(--mk-lime);
    font-size: 10px;
    font-weight: 800;
    padding: 2px 10px;
    border-radius: 999px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.diff-square-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
}
.diff-square-pill {
    font-size: 11px;
    font-weight: 800;
    padding: 3px 8px;
    border-radius: 6px;
    text-transform: uppercase;
    letter-spacing: 0.03em;
}
.pill-easy { background: #ECFDF5; color: #047857; }
.pill-medium { background: #EFF6FF; color: #1D4ED8; }
.pill-hard { background: #FEF3C7; color: #B45309; }

.diff-square-check {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 1.5px solid #CBD5E1;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.15s ease;
}
.diff-card-square.is-selected .diff-square-check {
    background: #0E1022;
    border-color: #0E1022;
    color: var(--mk-lime);
}

.diff-square-center {
    margin: 8px 0;
}
.diff-level-tag {
    font-size: 10.5px;
    font-weight: 800;
    color: #64748B;
    letter-spacing: 0.08em;
    display: block;
    margin-bottom: 2px;
}
.diff-square-heading {
    font-size: 19px;
    font-weight: 800;
    color: #0E1022;
    margin: 0;
    letter-spacing: -0.01em;
}

.diff-square-bottom {
    border-top: 1px solid #F1F5F9;
    padding-top: 10px;
    margin-top: 10px;
}
.diff-summary-line {
    font-size: 12.5px;
    color: var(--mk-text-sub);
    line-height: 1.4;
}

/* Format : 2 Grandes Box Carrées comme les 3 du dessus */
.format-square-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 24px;
}
@media (max-width: 800px) {
    .format-square-grid { grid-template-columns: 1fr; }
}

.format-card-square {
    background: #FFFFFF;
    border: 1.5px solid var(--mk-border);
    border-radius: 18px;
    padding: 24px 20px;
    cursor: pointer;
    transition: all 0.18s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-height: 195px;
    box-sizing: border-box;
}
.format-card-square:hover {
    border-color: #0E1022;
    transform: translateY(-3px);
    box-shadow: 0 10px 24px -10px rgba(15, 23, 42, 0.12);
}
.format-card-square.is-selected {
    border-color: #0E1022;
    background: #F8FAFC;
    box-shadow: 0 0 0 2px #0E1022;
}

.fmt-square-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
}
.fmt-square-pill {
    font-size: 11px;
    font-weight: 800;
    padding: 3px 8px;
    border-radius: 6px;
    text-transform: uppercase;
    letter-spacing: 0.03em;
}
.pill-format-exam { background: #EFF6FF; color: #1D4ED8; }
.pill-format-guided { background: #ECFDF5; color: #047857; }

.fmt-square-check {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 1.5px solid #CBD5E1;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.15s ease;
}
.format-card-square.is-selected .fmt-square-check {
    background: #0E1022;
    border-color: #0E1022;
    color: var(--mk-lime);
}

.fmt-square-center {
    margin: 8px 0;
}
.fmt-tag-label {
    font-size: 10.5px;
    font-weight: 800;
    color: #64748B;
    letter-spacing: 0.08em;
    display: block;
    margin-bottom: 2px;
}
.fmt-square-heading {
    font-size: 19px;
    font-weight: 800;
    color: #0E1022;
    margin: 0;
    letter-spacing: -0.01em;
}

.fmt-square-bottom {
    border-top: 1px solid #F1F5F9;
    padding-top: 10px;
    margin-top: 10px;
}
.fmt-summary-line {
    font-size: 12.5px;
    color: var(--mk-text-sub);
    line-height: 1.4;
}

/* ========================================================= */
/* SAS D'ANIMATION DE GÉNÉRATION ÉPURÉ                       */
/* ========================================================= */
.sas-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(14, 16, 34, 0.7);
    backdrop-filter: blur(6px);
    z-index: 99999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    box-sizing: border-box;
}
.sas-card-sleek {
    background: #FFFFFF;
    border-radius: 20px;
    padding: 32px 36px;
    width: 100%;
    max-width: 400px;
    text-align: center;
    box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.3);
}
.sas-spinner-sleek {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    border: 3.5px solid #F1F5F9;
    border-top-color: #0E1022;
    animation: sasSpin 0.7s linear infinite;
    margin: 0 auto 16px;
}
@keyframes sasSpin {
    to { transform: rotate(360deg); }
}
.sas-title-sleek {
    font-size: 18px;
    font-weight: 800;
    color: #0E1022;
    margin: 0 0 4px 0;
}
.sas-sub-sleek {
    font-size: 12.5px;
    color: #64748B;
    margin: 0 0 18px 0;
}
.sas-line-progress {
    width: 100%;
    height: 4px;
    background: #F1F5F9;
    border-radius: 999px;
    overflow: hidden;
}
.sas-line-fill {
    height: 100%;
    width: 30%;
    background: var(--mk-lime);
    animation: sasLineAnim 0.6s ease-in-out infinite alternate;
}
@keyframes sasLineAnim {
    from { width: 20%; transform: translateX(0); }
    to { width: 75%; transform: translateX(35%); }
}

/* ========================================================= */
/* ÉTAPE 4 : ATELIER SANS CONTOUR BLEU ET SANS ÉLÉMENTS BARRÉS*/
/* ========================================================= */
.atelier-container {
    background: #FFFFFF;
    border: 1px solid var(--mk-border);
    border-radius: 20px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: calc(100vh - 48px);
    max-height: calc(100vh - 48px);
    min-height: 540px;
}
.atelier-container.is-fullscreen-immersion {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw !important;
    height: 100vh !important;
    max-height: 100vh !important;
    border-radius: 0 !important;
    border: none !important;
    z-index: 999999;
}

.atelier-topbar {
    background: #0E1022;
    color: #FFFFFF;
    padding: 12px 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
}
.atelier-topbar-left {
    display: flex;
    align-items: center;
    gap: 16px;
    min-width: 0;
}
.atelier-back-btn {
    background: rgba(255, 255, 255, 0.1);
    color: #FFFFFF;
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 8px;
    padding: 6px 12px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    flex: none;
}
.atelier-back-btn:hover { background: rgba(255, 255, 255, 0.2); }

/* TITRE SANS CONTOUR BLEU (Image 1) */
.atelier-clean-title-group {
    display: flex;
    align-items: center;
    min-width: 0;
}
.atelier-subject-clean-text {
    font-size: 15px;
    font-weight: 750;
    color: #FFFFFF;
    letter-spacing: 0.01em;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 440px;
}

.atelier-topbar-right {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: none;
}

.btn-header-submit-work {
    background: var(--mk-lime);
    color: #0E1022;
    border: none;
    border-radius: 8px !important;
    box-shadow: none !important;
    padding: 7px 14px;
    font-size: 12.5px;
    font-weight: 800;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: filter 0.15s ease;
}
.btn-header-submit-work:hover { filter: brightness(0.95); }

.header-score-badge {
    background: #166534;
    color: #FFFFFF;
    border-radius: 8px;
    padding: 5px 12px;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.h-score-val { font-size: 13px; font-weight: 900; }
.h-score-sub { font-size: 10px; color: #BBF7D0; }

.visual-mode-toggle {
    display: flex;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    padding: 2px;
}
.vmode-btn {
    background: transparent;
    border: none;
    color: #94A3B8;
    padding: 5px 10px;
    border-radius: 6px;
    font-size: 11.5px;
    font-weight: 700;
    cursor: pointer;
}
.vmode-btn.is-active {
    background: #FFFFFF;
    color: #0E1022;
}

.btn-toggle-tutor {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.18);
    border-radius: 8px;
    color: #FFFFFF;
    padding: 6px 12px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 7px;
}
.btn-toggle-tutor.is-on {
    background: var(--mk-lime);
    color: #0E1022;
    border-color: var(--mk-lime);
}
.tutor-pulse-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #0E1022;
}

.btn-fullscreen-focus {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 8px;
    color: #FFFFFF;
    padding: 6px 10px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.fs-kbd {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 4px;
    padding: 1px 5px;
    font-size: 10px;
    font-weight: 800;
}

.atelier-view-tabs {
    display: flex;
    background: #0E1022;
    padding: 0 18px 8px;
    gap: 8px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}
.at-tab {
    background: rgba(255, 255, 255, 0.1);
    border: none;
    color: #CBD5E1;
    font-size: 12.5px;
    font-weight: 700;
    padding: 6px 14px;
    border-radius: 6px;
    cursor: pointer;
}
.at-tab.is-active {
    background: var(--mk-lime);
    color: #0E1022;
}

.atelier-body {
    display: flex;
    flex: 1 1 0%;
    height: 100%;
    max-height: 100%;
    min-height: 0;
    overflow: hidden;
}
.atelier-sheet-column {
    flex: 1 1 0%;
    min-width: 0;
    min-height: 0;
    height: 100%;
    max-height: 100%;
    overflow-y: auto;
    background: #F8FAFC;
    padding: 24px 28px;
    box-sizing: border-box;
}
.atelier-sheet-content {
    max-width: 860px;
    margin: 0 auto;
}

/* CARTOUCHE SPÉCIAL MONKAYIER (SANS MENTION DU MINISTÈRE, BASÉ SUR LES SOURCES OFFICIELLES) */
.monkayier-exam-header-card {
    background: #FFFFFF;
    border: 2px solid #0E1022;
    border-radius: 12px;
    padding: 18px 22px;
    margin-bottom: 24px;
    color: #0E1022;
    box-shadow: 0 2px 6px rgba(14, 16, 34, 0.04);
}
.exam-header-grid-top {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    border-bottom: 1.5px solid #0E1022;
    padding-bottom: 12px;
    margin-bottom: 12px;
}
.header-col-left { text-align: left; }
.header-brand-title {
    font-size: 14px;
    font-weight: 850;
    letter-spacing: 0.04em;
    color: #0E1022;
}
.header-academic-sub {
    font-size: 11px;
    color: #475569;
    margin: 2px 0 6px 0;
}

.header-col-right { text-align: right; }
.header-exam-name {
    font-size: 12.5px;
    font-weight: 850;
    letter-spacing: 0.02em;
    color: #0E1022;
}
.header-session-name {
    font-size: 10.5px;
    font-weight: 750;
    color: #2563EB;
    margin-top: 2px;
}
.header-serie-badge {
    font-size: 11px;
    font-weight: 800;
    background: #0E1022;
    color: #FFFFFF;
    padding: 2px 8px;
    border-radius: 4px;
    display: inline-block;
    margin-top: 4px;
}

.exam-header-grid-meta {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    border-bottom: 1px solid #CBD5E1;
    padding-bottom: 10px;
    margin-bottom: 10px;
}
@media (max-width: 700px) { .exam-header-grid-meta { grid-template-columns: 1fr 1fr; } }
.meta-cell { display: flex; flex-direction: column; gap: 2px; }
.meta-cell-label { font-size: 10px; font-weight: 750; color: #64748B; }
.meta-cell-val { font-size: 13px; font-weight: 800; color: #0E1022; }
.meta-cell-val.val-green { color: #059669; }

.exam-header-instructions { font-size: 11.5px; color: #334155; line-height: 1.45; }
.inst-label { font-weight: 800; color: #0E1022; }

.exam-sections-list { display: flex; flex-direction: column; gap: 20px; margin-bottom: 28px; }
.exam-section-card { background: #FFFFFF; border: 1px solid var(--mk-border); border-radius: 12px; padding: 20px; }
.exam-sec-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid #E2E8F0;
    padding-bottom: 8px;
    margin-bottom: 12px;
}
.exam-sec-title { font-size: 14px; font-weight: 850; color: #0E1022; margin: 0; }
.exam-sec-points { font-size: 11.5px; font-weight: 800; background: #0E1022; color: #FFFFFF; padding: 3px 8px; border-radius: 4px; }
.exam-sec-intro {
    font-size: 13.5px;
    color: #1E293B;
    line-height: 1.5;
    margin-bottom: 16px;
    background: #F8FAFC;
    padding: 10px 14px;
    border-left: 3px solid #2563EB;
    border-radius: 0 8px 8px 0;
}
.exam-questions-list { display: flex; flex-direction: column; gap: 12px; }
.exam-q-row { display: flex; align-items: flex-start; justify-content: space-between; gap: 14px; }
.exam-q-left { display: flex; align-items: flex-start; gap: 10px; font-size: 13.5px; color: #0F172A; line-height: 1.5; }
.exam-q-num { font-weight: 800; color: #0E1022; min-width: 28px; }
.exam-q-pts { font-size: 11px; font-weight: 750; color: #2563EB; background: #EFF6FF; border-radius: 4px; padding: 2px 6px; flex: none; }

/* TUTEUR IA (Hauteur Verrouillée au Viewport, Champ de Saisie Toujours Visible en Bas) */
.atelier-tutor-column {
    width: 390px;
    flex: none;
    height: 100%;
    max-height: 100%;
    min-height: 0;
    overflow: hidden;
    border-left: 1px solid var(--mk-border);
    background: #FFFFFF;
    display: flex;
    flex-direction: column;
    transition: width 0.2s ease;
}
.atelier-tutor-column.is-collapsed { width: 0 !important; overflow: hidden; border-left: none; }
.tutor-chat-wrapper { display: flex; flex-direction: column; flex: 1 1 0%; height: 100%; max-height: 100%; min-height: 0; overflow: hidden; }
.tutor-chat-header {
    padding: 12px 16px;
    border-bottom: 1px solid var(--mk-border);
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}
.tutor-header-avatar {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: #0E1022;
    color: var(--mk-lime);
    font-weight: 800;
    font-size: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex: none;
}
.tutor-name { font-size: 13.5px; font-weight: 750; color: #0E1022; }
.tutor-status { font-size: 11px; color: #10B981; font-weight: 600; }
.tutor-messages-scroll {
    flex: 1 1 0%;
    min-height: 0;
    overflow-y: auto;
    padding: 14px 16px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.tutor-msg { display: flex; flex-direction: column; max-width: 90%; }
.tutor-msg.msg-ai { align-self: flex-start; }
.tutor-msg.msg-student { align-self: flex-end; }
.msg-bubble { padding: 11px 14px; border-radius: 14px; font-size: 13px; line-height: 1.45; }
.msg-ai .msg-bubble { background: #F1F5F9; color: #0F172A; border-bottom-left-radius: 4px; }
.msg-student .msg-bubble { background: #0E1022; color: #FFFFFF; border-bottom-right-radius: 4px; }
.tutor-chips-bar { padding: 8px 12px; border-top: 1px solid #F1F5F9; display: flex; flex-direction: column; gap: 4px; flex-shrink: 0; }
.tutor-chip {
    background: #F8FAFC;
    border: 1px solid var(--mk-border);
    border-radius: 6px;
    padding: 6px 10px;
    font-size: 11.5px;
    font-weight: 650;
    color: #334155;
    text-align: left;
    cursor: pointer;
}
.tutor-chip:hover { background: #E2E8F0; color: #0E1022; }
.tutor-input-box {
    padding: 10px 14px;
    border-top: 1px solid var(--mk-border);
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
    margin-top: auto;
    background: #FFFFFF;
}
.tutor-text-input {
    flex: 1;
    border: 1.5px solid var(--mk-border);
    border-radius: 8px;
    padding: 9px 12px;
    font-size: 13px;
    outline: none;
}
.tutor-text-input:focus { border-color: #0E1022; }
.btn-send-tutor {
    background: #0E1022;
    color: var(--mk-lime);
    border: none;
    border-radius: 8px;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    flex: none;
}

/* MODALES */
.custom-modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(14, 16, 34, 0.7);
    backdrop-filter: blur(6px);
    z-index: 999999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    box-sizing: border-box;
}
.custom-modal-box {
    background: #FFFFFF;
    border-radius: 20px;
    width: 100%;
    max-width: 620px;
    max-height: 90vh;
    overflow-y: auto;
    padding: 26px 28px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
}
.modal-box-large { max-width: 760px; }
.modal-head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 20px;
    border-bottom: 1px solid #F1F5F9;
    padding-bottom: 12px;
}
.modal-title { font-size: 18px; font-weight: 800; color: #0E1022; margin: 0 0 4px 0; }
.modal-sub { font-size: 12.5px; color: #64748B; margin: 0; }
.modal-close-btn {
    background: #F1F5F9;
    border: none;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 700;
}

.submission-tabs { display: flex; gap: 8px; margin-bottom: 16px; }
.sub-tab-btn {
    background: #F1F5F9;
    border: 1px solid var(--mk-border);
    border-radius: 8px;
    padding: 8px 14px;
    font-size: 12.5px;
    font-weight: 700;
    color: #475569;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 7px;
}
.sub-tab-btn.is-active { background: #0E1022; color: #FFFFFF; border-color: #0E1022; }

.file-drop-zone {
    border: 2px dashed #CBD5E1;
    border-radius: 14px;
    padding: 26px 20px;
    background: #F8FAFC;
    cursor: pointer;
    text-align: center;
    transition: all 0.18s ease;
    margin-bottom: 18px;
}
.file-drop-zone:hover { border-color: #2563EB; background: #EFF6FF; }
.drop-icon-circle {
    width: 46px;
    height: 46px;
    border-radius: 50%;
    background: #EFF6FF;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 8px;
}
.drop-text-headline { font-size: 13.5px; font-weight: 750; color: #0E1022; margin-bottom: 4px; }
.drop-text-detail { font-size: 11.5px; color: #64748B; }

.copy-preview-image { max-height: 180px; max-width: 100%; border-radius: 10px; border: 1px solid var(--mk-border); margin-bottom: 10px; }
.preview-footer-row { display: flex; align-items: center; justify-content: center; gap: 12px; }
.preview-file-name { font-size: 12px; font-weight: 650; color: #0E1022; }
.btn-clear-photo {
    background: #FEE2E2;
    color: #991B1B;
    border: none;
    border-radius: 6px;
    padding: 4px 10px;
    font-size: 11px;
    font-weight: 700;
    cursor: pointer;
}

.clean-exam-textarea {
    width: 100%;
    box-sizing: border-box;
    border: 1.5px solid var(--mk-border);
    border-radius: 12px;
    padding: 12px 14px;
    font-family: inherit;
    font-size: 13px;
    outline: none;
    line-height: 1.5;
    margin-bottom: 16px;
}
.text-label { display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 6px; }

.modal-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 12px;
    border-top: 1px solid #F1F5F9;
    padding-top: 16px;
}
.btn-cancel-modal {
    background: #FFFFFF;
    border: 1px solid var(--mk-border);
    border-radius: 8px;
    padding: 10px 16px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
}
.btn-lime-submit-copy {
    background: var(--mk-lime);
    color: #0E1022;
    border: none;
    border-radius: 8px !important;
    box-shadow: none !important;
    padding: 11px 22px;
    font-size: 13.5px;
    font-weight: 800;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.ai-diagnostic-card { background: #F0FDF4; border: 1px solid #BBF7D0; border-radius: 14px; padding: 18px; margin-bottom: 18px; }
.diag-banner-top { display: flex; align-items: center; gap: 14px; margin-bottom: 10px; }
.diag-score-display {
    background: #166534;
    color: #FFFFFF;
    border-radius: 12px;
    padding: 6px 14px;
    display: flex;
    align-items: baseline;
    gap: 3px;
}
.score-big { font-size: 22px; font-weight: 900; }
.score-total { font-size: 12.5px; font-weight: 700; opacity: 0.85; }
.diag-official-mention { font-size: 15px; font-weight: 800; color: #166534; }
.diag-ref-text { font-size: 11.5px; color: #15803D; }
.diag-text-body { font-size: 13px; color: #1F2937; line-height: 1.5; margin: 0 0 14px 0; }
.diag-split-points { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
@media (max-width: 600px) { .diag-split-points { grid-template-columns: 1fr; } }
.diag-pane { background: #FFFFFF; border-radius: 8px; padding: 12px 14px; }
.pane-success { border-left: 3px solid #10B981; }
.pane-warning { border-left: 3px solid #F59E0B; }
.pane-title { font-size: 11px; font-weight: 800; text-transform: uppercase; margin-bottom: 6px; }
.pane-success .pane-title { color: #047857; }
.pane-warning .pane-title { color: #B45309; }
.pane-list { margin: 0; padding-left: 16px; font-size: 12px; color: #374151; line-height: 1.45; }

.unlocked-solution-card { background: #FFFFFF; border: 1px solid var(--mk-border); border-radius: 12px; padding: 20px; }
.official-bareme-wrap { margin-bottom: 20px; }
.bareme-header-label, .demo-header-label { font-size: 14px; font-weight: 800; color: #0E1022; margin-bottom: 10px; }
.official-bareme-table { width: 100%; border-collapse: collapse; font-size: 12.5px; }
.official-bareme-table th, .official-bareme-table td { border: 1px solid var(--mk-border); padding: 8px 12px; text-align: left; }
.official-bareme-table th { background: #F1F5F9; font-weight: 750; color: #0E1022; }
.demo-content-box { background: #F8FAFC; border: 1px solid var(--mk-border); border-radius: 10px; padding: 16px; font-size: 13px; line-height: 1.6; white-space: pre-line; color: #1E293B; }

.history-filter-tabs { display: flex; gap: 8px; margin-bottom: 16px; }
.hist-filter-btn {
    background: #F1F5F9;
    border: 1px solid var(--mk-border);
    border-radius: 8px;
    padding: 6px 12px;
    font-size: 12px;
    font-weight: 700;
    color: #475569;
    cursor: pointer;
}
.hist-filter-btn.is-active { background: #0E1022; color: #FFFFFF; border-color: #0E1022; }

.history-items-list { display: flex; flex-direction: column; gap: 10px; max-height: 480px; overflow-y: auto; }
.hist-item-row {
    background: #FFFFFF;
    border: 1px solid var(--mk-border);
    border-radius: 12px;
    padding: 14px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    transition: all 0.15s ease;
}
.hist-item-row:hover { border-color: #0E1022; transform: translateY(-1px); }
.hist-item-left { display: flex; align-items: center; gap: 12px; }
.hist-subject-pill { font-size: 11px; font-weight: 800; background: #EFF6FF; color: #2563EB; padding: 3px 8px; border-radius: 6px; }
.hist-title { font-size: 13.5px; font-weight: 750; color: #0E1022; margin: 0 0 2px 0; }
.hist-meta { font-size: 11.5px; color: #64748B; margin: 0; }
.hist-score-tag {
    font-size: 13px;
    font-weight: 850;
    color: #166534;
    background: #DCFCE7;
    padding: 4px 10px;
    border-radius: 6px;
}
.hist-pending-tag {
    font-size: 11.5px;
    font-weight: 750;
    color: #92400E;
    background: #FEF3C7;
    padding: 4px 10px;
    border-radius: 6px;
}
.btn-hist-open {
    background: #0E1022;
    color: #FFFFFF;
    border: none;
    border-radius: 6px;
    padding: 6px 12px;
    font-size: 11.5px;
    font-weight: 750;
    cursor: pointer;
}
.btn-hist-open:hover { background: #1E293B; }

/* Thèmes visuels Sépia et Nuit */
.exercises-root.theme-sepia .atelier-sheet-column { background: #FAF6ED !important; }
.exercises-root.theme-sepia .monkayier-exam-header-card,
.exercises-root.theme-sepia .exam-section-card,
.exercises-root.theme-sepia .unlocked-solution-card { background: #FFFDF8 !important; border-color: #D6CCA8 !important; }

.exercises-root.theme-night .atelier-sheet-column { background: #0B0E14 !important; color: #E2E8F0 !important; }
.exercises-root.theme-night .monkayier-exam-header-card,
.exercises-root.theme-night .exam-section-card,
.exercises-root.theme-night .unlocked-solution-card { background: #151922 !important; border-color: #272E3F !important; color: #E2E8F0 !important; }
.exercises-root.theme-night .header-brand-title,
.exercises-root.theme-night .meta-cell-val,
.exercises-root.theme-night .exam-sec-title,
.exercises-root.theme-night .exam-q-left { color: #F1F5F9 !important; }
</style>
@endpush

@push('scripts')
<script>
// =========================================================
// ÉTAT DE L'APPLICATION & HISTORIQUE
// =========================================================
const initialSubjects = @json($subjectsList);
let currentStepNumber = 1;
let activeSubjectCode = 'MATHS';
let selectedChapters = ['Limites, continuité et TVI'];
let activeDifficulty = 'medium';
let activeFormat = 'exam';
let currentExercise = @json($defaultExercise);
let uploadedPhotoFile = null;
let currentSubmissionAnalysis = null;
let homeworkHistory = @json($examHistory ?? []);

document.addEventListener('DOMContentLoaded', function() {
    renderChaptersForSubject(activeSubjectCode);
    updateHistoryCounter();
    updateTopBackButton(1);
});

// =========================================================
// NAVIGATION DANS LE TUNNEL & BOUTON RETOUR EN HAUT
// =========================================================
function goToStep(step) {
    currentStepNumber = step;
    document.getElementById('stepView1').style.display = 'none';
    document.getElementById('stepView2').style.display = 'none';
    document.getElementById('stepView3').style.display = 'none';
    document.getElementById('stepAtelier').style.display = 'none';
    document.getElementById('wizardStepper').style.display = 'flex';

    if (step === 1) document.getElementById('stepView1').style.display = 'block';
    else if (step === 2) document.getElementById('stepView2').style.display = 'block';
    else if (step === 3) document.getElementById('stepView3').style.display = 'block';

    [1, 2, 3, 4].forEach(n => {
        const pill = document.getElementById('stepPill' + n);
        const div = document.getElementById('stepDiv' + n);
        if (pill) {
            pill.classList.remove('is-active', 'is-completed');
            if (n === step) pill.classList.add('is-active');
            else if (n < step) pill.classList.add('is-completed');
        }
        if (div) div.classList.toggle('is-active', n < step);
    });

    updateTopBackButton(step);
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function updateTopBackButton(step) {
    const btn = document.getElementById('btnTopBackNav');
    const label = document.getElementById('btnTopBackNavText');
    if (!btn || !label) return;

    if (step === 1) {
        label.textContent = "Retour au Parcours";
    } else if (step === 2) {
        label.textContent = "Changer de matière";
    } else if (step === 3) {
        label.textContent = "Modifier les chapitres";
    }
}

function handleTopBackClick() {
    if (currentStepNumber === 1) {
        window.location.href = "{{ route('student.parcours') }}";
    } else if (currentStepNumber === 2) {
        goToStep(1);
    } else if (currentStepNumber === 3) {
        goToStep(2);
    }
}

// =========================================================
// ÉTAPE 1 : CHOIX DE LA MATIÈRE
// =========================================================
function handleSubjectSelect(code) {
    activeSubjectCode = code;
    document.querySelectorAll('.subject-flip-card').forEach(c => {
        c.classList.toggle('is-selected', c.dataset.subjectCode === code);
    });

    const sub = initialSubjects.find(s => s.code === code);
    if (sub) {
        document.getElementById('selectedSubjectLabel').textContent = sub.name;
        document.getElementById('activeSubjectBadgeName').textContent = sub.name;
        document.getElementById('activeSubjectBadgeCoeff').textContent = sub.coeff;
        renderChaptersForSubject(code);
    }

    setTimeout(() => { goToStep(2); }, 220);
}

// =========================================================
// ÉTAPE 2 : MULTI-SÉLECTION DE CHAPITRES
// =========================================================
function renderChaptersForSubject(code) {
    const sub = initialSubjects.find(s => s.code === code) || initialSubjects[0];
    const container = document.getElementById('chaptersContainer');
    if (!container || !sub) return;

    container.innerHTML = '';
    const chapters = sub.chapters || ['Notions Fondamentales', 'Méthode d\'Analyse', 'Synthèse Nationale'];
    
    selectedChapters = [chapters[0]];
    updateSelectedChaptersButton();

    chapters.forEach((ch, idx) => {
        const tile = document.createElement('div');
        const isChecked = selectedChapters.includes(ch);
        tile.className = 'chapter-tile ' + (isChecked ? 'is-selected' : '');
        tile.dataset.chapterName = ch;
        tile.onclick = () => toggleChapterSelection(ch, tile);

        tile.innerHTML = `
            <div class="chap-tile-head">
                <span class="chap-num-tag">Chapitre ${idx + 1}</span>
                <span class="chap-check-circle">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </span>
            </div>
            <h4 class="chap-title">${escapeHtml(ch)}</h4>
        `;
        container.appendChild(tile);
    });
}

function toggleChapterSelection(chName, element) {
    const idx = selectedChapters.indexOf(chName);
    if (idx > -1) {
        if (selectedChapters.length > 1) {
            selectedChapters.splice(idx, 1);
            element.classList.remove('is-selected');
        } else {
            return;
        }
    } else {
        selectedChapters.push(chName);
        element.classList.add('is-selected');
    }
    updateSelectedChaptersButton();
}

function selectAllChapters(selectState) {
    const sub = initialSubjects.find(s => s.code === activeSubjectCode);
    if (!sub || !sub.chapters) return;

    if (selectState) {
        selectedChapters = [...sub.chapters];
        document.querySelectorAll('.chapter-tile').forEach(t => t.classList.add('is-selected'));
    } else {
        selectedChapters = [sub.chapters[0]];
        document.querySelectorAll('.chapter-tile').forEach((t, i) => {
            t.classList.toggle('is-selected', i === 0);
        });
    }
    updateSelectedChaptersButton();
}

function updateSelectedChaptersButton() {
    const count = selectedChapters.length;
    const txt = count <= 1 ? 'Continuer (1 chapitre)' : `Continuer (${count} chapitres)`;
    const btnText = document.getElementById('btnContinueStep2Text');
    if (btnText) btnText.textContent = txt;
}

// =========================================================
// ÉTAPE 3 : CALIBRE NIVEAU & FORMAT
// =========================================================
function selectDifficultyTier(diff) {
    activeDifficulty = diff;
    document.querySelectorAll('.diff-card-square').forEach(c => {
        c.classList.toggle('is-selected', c.dataset.diff === diff);
    });
}

function selectFormatOption(fmt, element) {
    activeFormat = fmt;
    document.querySelectorAll('.format-card-square').forEach(t => t.classList.remove('is-selected'));
    if (element) element.classList.add('is-selected');
}

// =========================================================
// GÉNÉRATION DE L'ÉPREUVE (LOADER ÉPURÉ & ULTRA-RAPIDE)
// =========================================================
function launchOfficialExamGeneration() {
    const sas = document.getElementById('stepLoading');
    if (sas) sas.style.display = 'flex';

    fetch('/student/exercises/generate', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            subject_code: activeSubjectCode,
            chapters: selectedChapters,
            difficulty: activeDifficulty,
            format: activeFormat
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success && data.exercise) {
            currentExercise = data.exercise;
            recordNewGeneratedHomework(currentExercise);

            // Transition fluide et instantanée
            setTimeout(() => {
                if (sas) sas.style.display = 'none';
                renderOfficialExamAtelier(currentExercise);
                openAtelierScreen();
            }, 350);
        } else {
            if (sas) sas.style.display = 'none';
            alert("Une erreur est survenue lors de la calibration. Veuillez réessayer.");
        }
    })
    .catch(err => {
        console.error(err);
        if (sas) sas.style.display = 'none';
        alert("Erreur de connexion.");
    });
}

// =========================================================
// RENDU DE L'ÉPREUVE DANS L'ATELIER (TITRE SANS CONTOUR BLEU)
// =========================================================
function renderOfficialExamAtelier(ex) {
    // Titre conservé en texte clair sans contour bleu ni barème tronqué (Image 1)
    const titleEl = document.getElementById('atelierCleanSubjectTitle');
    if (titleEl) {
        titleEl.textContent = ex.subject_name || 'Physique-Chimie et Technologie';
    }

    document.getElementById('paperMetaSubject').textContent = (ex.subject_name || 'MATHEMATIQUES').toUpperCase();
    document.getElementById('paperMetaDuration').textContent = (ex.academic_header?.duration || '4 HEURES').toUpperCase();
    document.getElementById('paperMetaCoeff').textContent = (ex.academic_header?.coeff || 'Coeff. 4').replace(/[^0-9]/g, '') || '4';
    if (ex.academic_header?.instructions) {
        document.getElementById('paperInstructionsText').textContent = ex.academic_header.instructions;
    }

    document.getElementById('btnHeaderSubmitWork').style.display = 'inline-flex';
    document.getElementById('headerScoreBadge').style.display = 'none';
    document.getElementById('atelierViewTabs').style.display = 'none';
    switchAtelierView('statement');

    // Rendu des sections
    const container = document.getElementById('examSectionsContainer');
    if (container) {
        container.innerHTML = '';
        if (ex.sections && ex.sections.length) {
            ex.sections.forEach(sec => {
                const secCard = document.createElement('div');
                secCard.className = 'exam-section-card';

                let qRows = '';
                if (sec.questions && sec.questions.length) {
                    sec.questions.forEach(q => {
                        qRows += `
                            <div class="exam-q-row">
                                <div class="exam-q-left">
                                    <span class="exam-q-num">${escapeHtml(q.num)}</span>
                                    <span>${escapeHtml(q.label)}</span>
                                </div>
                                <span class="exam-q-pts">${q.points} pt${q.points > 1 ? 's' : ''}</span>
                            </div>
                        `;
                    });
                }

                secCard.innerHTML = `
                    <div class="exam-sec-header">
                        <h3 class="exam-sec-title">${escapeHtml(sec.title)}</h3>
                        <span class="exam-sec-points">${sec.points} points</span>
                    </div>
                    ${sec.intro ? `<div class="exam-sec-intro">${escapeHtml(sec.intro)}</div>` : ''}
                    <div class="exam-questions-list">${qRows}</div>
                `;
                container.appendChild(secCard);
            });
        }
    }

    const studentName = '{{ ($student->first_name ?? '') !== "Nouvel" ? ($student->first_name ?? "toi") : "toi" }}';
    const msgBox = document.getElementById('tutorMessagesContainer');
    if (msgBox) {
        msgBox.innerHTML = `
            <div class="tutor-msg msg-ai">
                <div class="msg-bubble">
                    Bonjour ${studentName} ! Je suis ton tuteur IA pour l'épreuve officielle : <b>« ${escapeHtml(ex.title)} »</b>. Traite le sujet à ton rythme sur ton cahier, et clique sur <b>« Soumettre mon devoir »</b> en haut dès que tu as terminé !
                </div>
            </div>
        `;
    }
}

function openAtelierScreen() {
    document.getElementById('wizardStepper').style.display = 'none';
    document.getElementById('stepView1').style.display = 'none';
    document.getElementById('stepView2').style.display = 'none';
    document.getElementById('stepView3').style.display = 'none';
    document.getElementById('stepAtelier').style.display = 'flex';

    [1, 2, 3].forEach(n => {
        const pill = document.getElementById('stepPill' + n);
        if (pill) pill.classList.add('is-completed');
    });
    const pill4 = document.getElementById('stepPill4');
    if (pill4) pill4.classList.add('is-active');

    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function quitAtelierToWizard() {
    document.getElementById('stepAtelier').style.display = 'none';
    goToStep(3);
    exitFullscreenMode();
}

function switchAtelierView(view) {
    const isStatement = view === 'statement';
    document.getElementById('viewStatementWrap').style.display = isStatement ? 'block' : 'none';
    document.getElementById('viewSolutionWrap').style.display = isStatement ? 'none' : 'block';

    const tBtnStat = document.getElementById('tabBtnStatement');
    const tBtnSol = document.getElementById('tabBtnSolution');
    if (tBtnStat) tBtnStat.classList.toggle('is-active', isStatement);
    if (tBtnSol) tBtnSol.classList.toggle('is-active', !isStatement);
}

// =========================================================
// MODAL DÉDIÉE DE SOUMISSION DU DEVOIR
// =========================================================
function openSubmissionModal() {
    const modal = document.getElementById('submissionModal');
    if (!modal) return;

    if (currentSubmissionAnalysis) {
        document.getElementById('modalSubmitForm').style.display = 'none';
        document.getElementById('modalResultView').style.display = 'block';
    } else {
        document.getElementById('modalSubmitForm').style.display = 'block';
        document.getElementById('modalResultView').style.display = 'none';
    }
    modal.style.display = 'flex';
}

function closeSubmissionModal() {
    const modal = document.getElementById('submissionModal');
    if (modal) modal.style.display = 'none';
}

function switchSubmissionTab(tab) {
    document.querySelectorAll('.sub-tab-btn').forEach(b => {
        b.classList.toggle('is-active', b.dataset.tab === tab);
    });
    document.getElementById('tabPhotoPanel').style.display = tab === 'photo' ? 'block' : 'none';
    document.getElementById('tabTextPanel').style.display = tab === 'text' ? 'block' : 'none';
}

function handlePhotoSelect(input) {
    if (input.files && input.files[0]) {
        uploadedPhotoFile = input.files[0];
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImageTag').src = e.target.result;
            document.getElementById('previewNameLabel').textContent = uploadedPhotoFile.name;
            document.getElementById('dropPromptView').style.display = 'none';
            document.getElementById('dropPreviewView').style.display = 'block';
        };
        reader.readAsDataURL(uploadedPhotoFile);
    }
}

function clearSelectedPhoto(e) {
    if (e) e.stopPropagation();
    uploadedPhotoFile = null;
    const pInput = document.getElementById('photoInput');
    if (pInput) pInput.value = '';
    document.getElementById('dropPromptView').style.display = 'block';
    document.getElementById('dropPreviewView').style.display = 'none';
}

function submitExamProof() {
    const textAns = document.getElementById('textAnswerArea').value.trim();
    if (!uploadedPhotoFile && !textAns) {
        alert("Veuillez déposer une photo de votre copie de cahier ou rédiger votre réponse au clavier.");
        return;
    }

    const btn = document.getElementById('btnSubmitExamCopy');
    btn.disabled = true;
    btn.innerHTML = '<span>Évaluation ministérielle en cours...</span>';

    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('exercise_id', currentExercise.id || '');
    formData.append('subject_code', currentExercise.subject_code || 'MATHS');
    formData.append('student_answer', textAns);
    if (uploadedPhotoFile) {
        formData.append('student_photo', uploadedPhotoFile);
    }

    fetch('/student/exercises/submit', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<span>Valider et obtenir ma note & corrigé</span>';

        if (data.success && data.analysis) {
            currentSubmissionAnalysis = data.analysis;
            updateHomeworkStatusInHistory(currentExercise.id, data.analysis.score, data.analysis.mention);
            displaySubmissionResults(data.analysis, currentExercise.model_solution);
        } else {
            alert("Erreur lors de l'analyse.");
        }
    })
    .catch(err => {
        console.error(err);
        btn.disabled = false;
        btn.innerHTML = '<span>Valider et obtenir ma note & corrigé</span>';
        alert("Erreur de transmission.");
    });
}

function displaySubmissionResults(analysis, modelSolution) {
    document.getElementById('modalSubmitForm').style.display = 'none';
    document.getElementById('modalResultView').style.display = 'block';

    document.getElementById('resScoreNum').textContent = analysis.score;
    document.getElementById('resMention').textContent = analysis.mention || 'Très bonne démarche';

    if (analysis.summary) {
        document.getElementById('diagGeneralAppraisal').textContent = analysis.summary;
    }

    const stUl = document.getElementById('diagStrengthsUl');
    if (stUl && analysis.strengths) {
        stUl.innerHTML = '';
        analysis.strengths.forEach(s => {
            const li = document.createElement('li');
            li.textContent = s;
            stUl.appendChild(li);
        });
    }

    const ptUl = document.getElementById('diagPitfallsUl');
    if (ptUl && analysis.pitfalls) {
        ptUl.innerHTML = '';
        analysis.pitfalls.forEach(p => {
            const li = document.createElement('li');
            li.textContent = p;
            ptUl.appendChild(li);
        });
    }

    const holder = document.getElementById('baremeTableHolder');
    if (holder && modelSolution && modelSolution.bareme_grid) {
        let rows = '';
        for (const [qKey, crit] of Object.entries(modelSolution.bareme_grid)) {
            rows += `<tr>
                <td style="font-weight:750;width:150px">${escapeHtml(qKey)}</td>
                <td>${escapeHtml(crit)}</td>
            </tr>`;
        }
        holder.innerHTML = `
            <table class="official-bareme-table">
                <thead><tr><th>Partie / Question</th><th>Critère & Barème officiel attribué</th></tr></thead>
                <tbody>${rows}</tbody>
            </table>
        `;
    }

    const demoHolder = document.getElementById('fullDemoTextHolder');
    if (demoHolder && modelSolution && modelSolution.full_demonstration) {
        demoHolder.textContent = modelSolution.full_demonstration;
    }

    document.getElementById('btnHeaderSubmitWork').style.display = 'none';
    const sBadge = document.getElementById('headerScoreBadge');
    sBadge.style.display = 'flex';
    sBadge.querySelector('.h-score-val').textContent = analysis.score + ' / 20';

    document.getElementById('atelierViewTabs').style.display = 'flex';
}

function showUnlockedCorrection() {
    closeSubmissionModal();
    switchAtelierView('solution');
}

// =========================================================
// HISTORIQUE DES DEVOIRS GÉNÉRÉS & FAITS
// =========================================================
function toggleHistoryModal(openState) {
    const modal = document.getElementById('historyModal');
    if (!modal) return;
    modal.style.display = openState ? 'flex' : 'none';
    if (openState) {
        renderHistoryList('all');
    }
}

function filterHistory(filter, btn) {
    document.querySelectorAll('.hist-filter-btn').forEach(b => b.classList.remove('is-active'));
    if (btn) btn.classList.add('is-active');
    renderHistoryList(filter);
}

function renderHistoryList(filter) {
    const container = document.getElementById('historyItemsList');
    if (!container) return;
    container.innerHTML = '';

    let items = homeworkHistory;
    if (filter === 'evaluated') items = items.filter(i => i.status === 'evaluated');
    else if (filter === 'pending') items = items.filter(i => i.status === 'pending');

    if (!items.length) {
        container.innerHTML = '<div style="text-align:center;padding:30px;color:#64748B;font-size:13px">Aucun devoir dans cette catégorie.</div>';
        return;
    }

    items.forEach(it => {
        const row = document.createElement('div');
        row.className = 'hist-item-row';

        const isEval = it.status === 'evaluated';
        const badgeHtml = isEval 
            ? `<span class="hist-score-tag">${it.score} / ${it.max_score || 20}</span>`
            : `<span class="hist-pending-tag">En attente</span>`;

        row.innerHTML = `
            <div class="hist-item-left">
                <span class="hist-subject-pill">${escapeHtml(it.subject_name || it.subject_code)}</span>
                <div>
                    <h4 class="hist-title">${escapeHtml(it.title)}</h4>
                    <p class="hist-meta">${escapeHtml(it.chapters || '')} · Date : ${escapeHtml(it.date || 'Récemment')}</p>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:12px">
                ${badgeHtml}
                <button type="button" class="btn-hist-open" onclick="openExerciseFromHistory('${it.id}')">
                    ${isEval ? 'Revoir le corrigé' : 'Traiter l\'épreuve'}
                </button>
            </div>
        `;
        container.appendChild(row);
    });
}

function recordNewGeneratedHomework(ex) {
    const newItem = {
        id: ex.id,
        title: ex.title,
        subject_code: ex.subject_code,
        subject_name: ex.subject_name,
        chapters: selectedChapters.join(' · '),
        date: 'Aujourd\'hui',
        status: 'pending',
        score: null,
        max_score: 20,
        mention: 'Non soumis',
        full_data: ex
    };
    homeworkHistory.unshift(newItem);
    updateHistoryCounter();
}

function updateHomeworkStatusInHistory(exId, score, mention) {
    const item = homeworkHistory.find(i => i.id === exId);
    if (item) {
        item.status = 'evaluated';
        item.score = score;
        item.mention = mention;
    }
    updateHistoryCounter();
}

function updateHistoryCounter() {
    const b = document.getElementById('historyCounterBadge');
    if (b) b.textContent = homeworkHistory.length;
}

function openExerciseFromHistory(id) {
    toggleHistoryModal(false);
    const item = homeworkHistory.find(i => i.id === id);
    if (!item) return;

    fetch('/student/exercises/generate', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            subject_code: item.subject_code || 'MATHS',
            chapter: item.chapters || 'Limites',
            difficulty: 'medium',
            format: 'exam'
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success && data.exercise) {
            currentExercise = data.exercise;
            renderOfficialExamAtelier(currentExercise);
            openAtelierScreen();

            if (item.status === 'evaluated') {
                const mockAnalysis = {
                    score: item.score || 16,
                    max_score: 20,
                    mention: item.mention || 'Très bonne démarche',
                    summary: 'Copie déjà évaluée et archivée dans ton historique personnel.',
                    strengths: ['Démonstration validée par le jury ministériel.'],
                    pitfalls: ['Prends soin de relire la rédaction de l\'asymptote.']
                };
                currentSubmissionAnalysis = mockAnalysis;
                displaySubmissionResults(mockAnalysis, currentExercise.model_solution);
                switchAtelierView('solution');
            } else {
                currentSubmissionAnalysis = null;
            }
        }
    });
}

// =========================================================
// TUTEUR SOCRATIQUE IA
// =========================================================
function toggleTutorPanel() {
    const sidebar = document.getElementById('atelierTutorSidebar');
    const btn = document.getElementById('btnToggleTutor');
    sidebar.classList.toggle('is-collapsed');
    const isNowOpen = !sidebar.classList.contains('is-collapsed');
    btn.classList.toggle('is-on', isNowOpen);
}

function handleTutorKey(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendTutorMessage();
    }
}

function sendTutorPrompt(text) {
    const inp = document.getElementById('tutorInput');
    if (inp) {
        inp.value = text;
        sendTutorMessage();
    }
}

function sendTutorMessage() {
    const inp = document.getElementById('tutorInput');
    const text = inp.value.trim();
    if (!text) return;

    appendTutorMsg(text, 'student');
    inp.value = '';
    if (window.mkAutoResizeTextarea) window.mkAutoResizeTextarea(inp);

    const box = document.getElementById('tutorMessagesContainer');
    const loadingDiv = document.createElement('div');
    loadingDiv.className = 'tutor-msg msg-ai';
    loadingDiv.innerHTML = '<div class="msg-bubble" style="color:#64748B;font-style:italic">Analyse de l\'épreuve en cours...</div>';
    if (box) {
        box.appendChild(loadingDiv);
        box.scrollTop = box.scrollHeight;
    }

    const statementEl = document.getElementById('viewStatementWrap');
    const screenText = statementEl ? statementEl.innerText.substring(0, 1800) : (currentExercise ? JSON.stringify(currentExercise.sections || '') : '');

    fetch('{{ route("student.chat.message") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            message: text,
            active_tab: 'exercices',
            current_exercise: (currentExercise ? (currentExercise.title + ' (' + (currentExercise.subject_name || '') + ')') : 'Épreuve officielle') + "\n" + screenText,
            context_text: screenText
        })
    })
    .then(res => res.json())
    .then(data => {
        if (loadingDiv && loadingDiv.parentNode) loadingDiv.parentNode.removeChild(loadingDiv);
        const resp = data.assistant_response || data;
        const reply = resp.source_text || data.source_text || "Identifie d'abord les données de l'énoncé et la formule du cours correspondante.";
        const spoken = resp.spoken_text || reply;
        appendTutorMsg(reply, 'ai', spoken);
    })
    .catch(() => {
        if (loadingDiv && loadingDiv.parentNode) loadingDiv.parentNode.removeChild(loadingDiv);
        appendTutorMsg("Identifie d'abord les hypothèses de l'énoncé et énonce le théorème officiel correspondant avant de passer à l'application numérique.", 'ai');
    });
}

function appendTutorMsg(content, sender, spokenText) {
    const box = document.getElementById('tutorMessagesContainer');
    if (!box) return;
    const div = document.createElement('div');
    div.className = `tutor-msg msg-${sender}`;
    if (sender === 'ai') {
        const formattedHtml = window.mkFormatAiHtml ? window.mkFormatAiHtml(content) : content;
        div.innerHTML = `
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
                <span style="font-size:10px;font-weight:700;color:#64748B;">Coach Socratique IA</span>
                <button type="button" class="mk-tts-btn" title="Écouter l'explication">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon><path d="M15.54 8.46a5 5 0 0 1 0 7.07"></path></svg>
                    <span>Écouter</span>
                </button>
            </div>
            <div class="msg-bubble">${formattedHtml}</div>
        `;
        const ttsBtn = div.querySelector('.mk-tts-btn');
        if (ttsBtn) {
            ttsBtn.dataset.speech = spokenText || content;
            ttsBtn.onclick = function() {
                if (window.mkSpeakHuman) window.mkSpeakHuman(this.dataset.speech, this);
            };
        }
        box.appendChild(div);
        if (typeof renderMathInElement === 'function') {
            renderMathInElement(div, {
                delimiters: [
                    {left: '$$', right: '$$', display: true},
                    {left: '$', right: '$', display: false},
                    {left: '\\(', right: '\\)', display: false},
                    {left: '\\[', right: '\\]', display: true}
                ],
                throwOnError: false
            });
        }
    } else {
        div.innerHTML = `<div class="msg-bubble">${escapeHtml(content)}</div>`;
        box.appendChild(div);
    }
    box.scrollTop = box.scrollHeight;
}

// =========================================================
// CONFORT VISUEL & PLEIN ÉCRAN
// =========================================================
function setVisualTheme(theme) {
    const root = document.getElementById('exercisesApp');
    root.classList.remove('theme-sepia', 'theme-night');
    if (theme === 'sepia') root.classList.add('theme-sepia');
    if (theme === 'night') root.classList.add('theme-night');

    document.querySelectorAll('.vmode-btn').forEach(b => {
        b.classList.toggle('is-active', b.dataset.vmode === theme);
    });
}

function toggleAtelierFullscreen() {
    const atelier = document.getElementById('stepAtelier');
    if (!document.fullscreenElement) {
        if (atelier.requestFullscreen) atelier.requestFullscreen();
        else if (atelier.webkitRequestFullscreen) atelier.webkitRequestFullscreen();
        atelier.classList.add('is-fullscreen-immersion');
    } else {
        exitFullscreenMode();
    }
}

function exitFullscreenMode() {
    if (document.fullscreenElement && document.exitFullscreen) {
        document.exitFullscreen();
    }
    const atelier = document.getElementById('stepAtelier');
    if (atelier) atelier.classList.remove('is-fullscreen-immersion');
}

document.addEventListener('keydown', function(e) {
    const activeEl = document.activeElement;
    const isTyping = activeEl && (['INPUT', 'TEXTAREA'].includes(activeEl.tagName) || activeEl.isContentEditable);

    if (isTyping) {
        if (e.key === 'Escape') activeEl.blur();
        return;
    }

    if ((e.key === 'f' || e.key === 'F') && !e.ctrlKey && !e.metaKey && !e.altKey) {
        const atelier = document.getElementById('stepAtelier');
        if (atelier && atelier.style.display !== 'none') {
            e.preventDefault();
            toggleAtelierFullscreen();
        }
    }

    if (e.key === 'Escape') {
        const subModal = document.getElementById('submissionModal');
        const histModal = document.getElementById('historyModal');
        if (subModal && subModal.style.display !== 'none') {
            closeSubmissionModal();
            return;
        }
        if (histModal && histModal.style.display !== 'none') {
            toggleHistoryModal(false);
            return;
        }
        exitFullscreenMode();
    }
});

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/&/g, "&amp;")
              .replace(/</g, "&lt;")
              .replace(/>/g, "&gt;")
              .replace(/"/g, "&quot;")
              .replace(/'/g, "&#039;");
}
</script>
@endpush
@endsection
