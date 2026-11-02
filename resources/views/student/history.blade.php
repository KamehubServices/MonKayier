@extends('layouts.student')

@section('title', 'Historique et progression — MonKayier')

@section('content')
<style>
/* ==========================================================================
   HISTORIQUE & PROGRESSION — REBRANDING MONKAYIER
   CHARTE : SORA / INTER · ZÉRO EMOJI · ICÔNES VECTORIELLES SVG · NAVY & LIME
   ========================================================================== */

.hist-container {
    max-width: 1100px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 20px;
    box-sizing: border-box;
    padding-bottom: 50px;
}

/* En-tête */
.hist-header {
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.hist-page-title {
    font-family: 'Sora', sans-serif;
    font-size: 26px;
    font-weight: 800;
    color: #0E1022;
    margin: 0;
    letter-spacing: -0.02em;
}
.hist-page-subtitle {
    font-size: 13.5px;
    color: #64748B;
    margin: 0;
}

/* Grille des statistiques clés */
.hist-stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
}
@media (max-width: 768px) {
    .hist-stats-grid {
        grid-template-columns: 1fr;
    }
}
.hist-stat-card {
    background: #FFFFFF;
    border: 1.5px solid #E2E8F0;
    border-radius: 14px;
    padding: 18px 20px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    transition: all 0.15s ease;
}
.hist-stat-card:hover {
    border-color: #CBD5E1;
    box-shadow: 0 4px 14px rgba(14, 16, 34, 0.04);
}
.hsc-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.hsc-label {
    font-family: 'Sora', sans-serif;
    font-size: 11.5px;
    font-weight: 800;
    color: #64748B;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
.hsc-icon-badge {
    width: 28px;
    height: 28px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex: none;
}
.hsc-icon-badge svg {
    width: 14px;
    height: 14px;
    stroke: currentColor;
    stroke-width: 2.2;
    fill: none;
}
.hsc-val {
    font-family: 'Sora', sans-serif;
    font-size: 38px;
    font-weight: 900;
    color: #0E1022;
    line-height: 1;
    letter-spacing: -0.03em;
}
.hsc-sub {
    font-size: 12px;
    color: #64748B;
    line-height: 1.4;
}

/* Grille principale 2 colonnes */
.hist-main-grid {
    display: grid;
    grid-template-columns: 1.35fr 1fr;
    gap: 20px;
    align-items: start;
}
@media (max-width: 900px) {
    .hist-main-grid {
        grid-template-columns: 1fr;
    }
}

/* Carte de liste : Dernières séances */
.hist-card {
    background: #FFFFFF;
    border: 1.5px solid #E2E8F0;
    border-radius: 14px;
    padding: 22px 24px;
    display: flex;
    flex-direction: column;
    gap: 16px;
    box-sizing: border-box;
}
.hc-title {
    font-family: 'Sora', sans-serif;
    font-size: 16px;
    font-weight: 800;
    color: #0E1022;
    margin: 0;
}

/* Liste des séances */
.hist-session-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.hist-session-item {
    background: #F8FAFC;
    border: 1.5px solid #E2E8F0;
    border-radius: 10px;
    padding: 14px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    transition: all 0.15s ease;
}
.hist-session-item:hover {
    background: #FFFFFF;
    border-color: #0E1022;
    box-shadow: 0 3px 10px rgba(14, 16, 34, 0.04);
}
.hsi-left {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}
.hsi-icon {
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
.hsi-icon svg {
    width: 17px;
    height: 17px;
    stroke: currentColor;
    stroke-width: 2.2;
    fill: none;
}
.hsi-info {
    display: flex;
    flex-direction: column;
    gap: 3px;
    min-width: 0;
}
.hsi-title {
    font-family: 'Sora', sans-serif;
    font-size: 13.5px;
    font-weight: 800;
    color: #0E1022;
    margin: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.hsi-meta {
    font-size: 12px;
    color: #64748B;
    display: flex;
    align-items: center;
    gap: 8px;
}
.hsi-meta-pill {
    background: #FFFFFF;
    border: 1px solid #CBD5E1;
    border-radius: 4px;
    padding: 1px 6px;
    font-size: 11px;
    font-weight: 700;
    color: #475569;
}
.hsi-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #FFFFFF;
    border: 1.5px solid #CBD5E1;
    border-radius: 8px;
    padding: 7px 14px;
    font-family: 'Inter', sans-serif;
    font-size: 12.5px;
    font-weight: 750;
    color: #0E1022;
    text-decoration: none;
    transition: all 0.15s ease;
    white-space: nowrap;
}
.hsi-btn:hover {
    background: #0E1022;
    border-color: #0E1022;
    color: #FFFFFF;
}
.hsi-btn svg {
    width: 13px;
    height: 13px;
    stroke: currentColor;
    stroke-width: 2.4;
    fill: none;
}

/* Carte des notions */
.notions-chips-grid {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}
.notion-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
    border: 1px solid transparent;
}
.notion-tag-mastered {
    background: #ECFDF5;
    border-color: #A7F3D0;
    color: #065F46;
}
.notion-tag-inprogress {
    background: #EFF6FF;
    border-color: #BFDBFE;
    color: #1D4ED8;
}
.notion-tag-review {
    background: #FFFBEB;
    border-color: #FDE68A;
    color: #B45309;
}
.notion-legend-row {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-top: 10px;
    padding-top: 14px;
    border-top: 1px solid #E2E8F0;
}
.notion-legend-item {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11.5px;
    color: #64748B;
    font-weight: 600;
}
.notion-legend-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
}
</style>

<div class="hist-container">

    {{-- EN-TÊTE --}}
    <div class="hist-header">
        <h1 class="hist-page-title">Historique et progression</h1>
        <p class="hist-page-subtitle">Reprends où tu t'étais arrêté et analyse tes acquis</p>
    </div>

    {{-- NAVIGATION SOUS-PAGES REBRANDÉE --}}
    <div class="mk-subnav">
        <a href="{{ route('student.profile') }}" class="mk-subnav-link">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
            <span>Mon Profil</span>
        </a>
        <a href="{{ route('student.history') }}" class="mk-subnav-link active">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"></circle><path d="M12 7v5l3 2"></path></svg>
            <span>Historique & Activités</span>
        </a>
        <a href="{{ route('student.notifications') }}" class="mk-subnav-link">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 8a6 6 0 1 1 12 0c0 7 3 8 3 8H3s3-1 3-8"></path><path d="M10 20a2 2 0 0 0 4 0"></path></svg>
            <span>Notifications</span>
            @if(isset($currentStudent) && $currentStudent->unread_notifs > 0)
            <span class="badge-count">{{ $currentStudent->unread_notifs }}</span>
            @endif
        </a>
        <a href="{{ route('student.help') }}" class="mk-subnav-link">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
            <span>Aide & Contact</span>
        </a>
    </div>

    {{-- STATS CLÉS --}}
    <div class="hist-stats-grid">
        <div class="hist-stat-card">
            <div class="hsc-top">
                <span class="hsc-label">Notions maîtrisées</span>
                <div class="hsc-icon-badge" style="background:#ECFDF5;color:#065F46;">
                    <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </div>
            </div>
            <div class="hsc-val">24</div>
            <div class="hsc-sub">Notions validées avec succès sur ton programme</div>
        </div>

        <div class="hist-stat-card">
            <div class="hsc-top">
                <span class="hsc-label">À retravailler</span>
                <div class="hsc-icon-badge" style="background:#FFFBEB;color:#B45309;">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                </div>
            </div>
            <div class="hsc-val" style="color:#B45309;">7</div>
            <div class="hsc-sub">Chapitres ciblés pour tes prochains rappels IA</div>
        </div>

        <div class="hist-stat-card">
            <div class="hsc-top">
                <span class="hsc-label">Séances manquées</span>
                <div class="hsc-icon-badge" style="background:#EFF6FF;color:#1D4ED8;">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                </div>
            </div>
            <div class="hsc-val">2</div>
            <div class="hsc-sub">Tu peux les reprendre à tout moment sans pénalité</div>
        </div>
    </div>

    {{-- GRILLE PRINCIPALE --}}
    <div class="hist-main-grid">

        {{-- COLONNE GAUCHE : DERNIÈRES SÉANCES --}}
        <div class="hist-card">
            <h3 class="hc-title">Mes dernières séances</h3>
            <div class="hist-session-list">

                {{-- Séance 1 --}}
                <div class="hist-session-item">
                    <div class="hsi-left">
                        <div class="hsi-icon">
                            <svg viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                        </div>
                        <div class="hsi-info">
                            <h4 class="hsi-title">Limites de fonctions · discussion avec le cours</h4>
                            <div class="hsi-meta">
                                <span>Aujourd’hui</span>
                                <span>·</span>
                                <span class="hsi-meta-pill">25 min</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('student.assistant') }}" class="hsi-btn">
                        <span>Reprendre</span>
                        <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </a>
                </div>

                {{-- Séance 2 --}}
                <div class="hist-session-item">
                    <div class="hsi-left">
                        <div class="hsi-icon" style="background:#0284c7;color:#FFFFFF;">
                            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle></svg>
                        </div>
                        <div class="hsi-info">
                            <h4 class="hsi-title">Exercices de dérivation</h4>
                            <div class="hsi-meta">
                                <span>Hier</span>
                                <span>·</span>
                                <span class="hsi-meta-pill">Score 4/5</span>
                                <span class="hsi-meta-pill">18 min</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('student.exercises') }}" class="hsi-btn">
                        <span>Reprendre</span>
                        <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </a>
                </div>

                {{-- Séance 3 --}}
                <div class="hist-session-item">
                    <div class="hsi-left">
                        <div class="hsi-icon" style="background:#15803D;color:#D4F25C;">
                            <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        </div>
                        <div class="hsi-info">
                            <h4 class="hsi-title">Test de niveau · Mathématiques</h4>
                            <div class="hsi-meta">
                                <span>Lundi</span>
                                <span>·</span>
                                <span class="hsi-meta-pill">Terminé</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('student.test') }}" class="hsi-btn">
                        <span>Revoir</span>
                        <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </a>
                </div>

                {{-- Séance 4 --}}
                <div class="hist-session-item">
                    <div class="hsi-left">
                        <div class="hsi-icon" style="background:#4338CA;color:#FFFFFF;">
                            <svg viewBox="0 0 24 24"><path d="M18 8h1a4 4 0 0 1 0 8h-1"></path><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path><line x1="6" y1="1" x2="6" y2="4"></line><line x1="10" y1="1" x2="10" y2="4"></line><line x1="14" y1="1" x2="14" y2="4"></line></svg>
                        </div>
                        <div class="hsi-info">
                            <h4 class="hsi-title">Lois de Newton · Quiz de Physique</h4>
                            <div class="hsi-meta">
                                <span>Dimanche</span>
                                <span>·</span>
                                <span class="hsi-meta-pill">Score 8/10</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('student.exercises') }}" class="hsi-btn">
                        <span>Reprendre</span>
                        <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </a>
                </div>

            </div>
        </div>

        {{-- COLONNE DROITE : CARTE DES NOTIONS --}}
        <div class="hist-card">
            <h3 class="hc-title">Carte des notions</h3>
            
            <div class="notions-chips-grid">
                <span class="notion-tag notion-tag-mastered">Équations</span>
                <span class="notion-tag notion-tag-mastered">Dérivées</span>
                <span class="notion-tag notion-tag-mastered">Géométrie</span>
                <span class="notion-tag notion-tag-inprogress">Suites</span>
                <span class="notion-tag notion-tag-inprogress">Intégrales</span>
                <span class="notion-tag notion-tag-review">Limites</span>
                <span class="notion-tag notion-tag-review">Probabilités</span>
                <span class="notion-tag notion-tag-inprogress">Newton</span>
            </div>

            <div class="notion-legend-row">
                <div class="notion-legend-item">
                    <span class="notion-legend-dot" style="background:#10B981;"></span>
                    <span>Maîtrisé</span>
                </div>
                <div class="notion-legend-item">
                    <span class="notion-legend-dot" style="background:#3B82F6;"></span>
                    <span>En cours</span>
                </div>
                <div class="notion-legend-item">
                    <span class="notion-legend-dot" style="background:#F59E0B;"></span>
                    <span>À travailler</span>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
