@extends('layouts.student')

@section('title', 'Profil et paramètres — Espace Élève MonKayier')

@section('content')
<style>
/* ==========================================================================
   PROFIL & PARAMÈTRES — REBRANDING MONKAYIER
   CHARTE : SORA / INTER · ZÉRO EMOJI · ICÔNES VECTORIELLES SVG · NAVY & LIME
   ========================================================================== */

.prof-container {
    max-width: 1100px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 20px;
    box-sizing: border-box;
    padding-bottom: 50px;
}

/* En-tête */
.prof-header {
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.prof-page-title {
    font-family: 'Sora', sans-serif;
    font-size: 26px;
    font-weight: 800;
    color: #0E1022;
    margin: 0;
    letter-spacing: -0.02em;
}
.prof-page-subtitle {
    font-size: 13.5px;
    color: #64748B;
    margin: 0;
}

/* Grille principale 2 colonnes */
.prof-main-grid {
    display: grid;
    grid-template-columns: 1.25fr 1fr;
    gap: 22px;
    align-items: start;
}
@media (max-width: 900px) {
    .prof-main-grid {
        grid-template-columns: 1fr;
    }
}

/* Carte Formulaire Profil */
.prof-card {
    background: #FFFFFF;
    border: 1.5px solid #E2E8F0;
    border-radius: 14px;
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 18px;
    box-sizing: border-box;
}

/* En-tête profil élève */
.prof-user-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding-bottom: 16px;
    border-bottom: 1px solid #E2E8F0;
}
.prof-avatar {
    width: 54px;
    height: 54px;
    border-radius: 12px;
    background: #D4F25C;
    color: #0E1022;
    font-family: 'Sora', sans-serif;
    font-size: 19px;
    font-weight: 900;
    display: flex;
    align-items: center;
    justify-content: center;
    flex: none;
    border: 1.5px solid rgba(14, 16, 34, 0.1);
}
.prof-user-meta {
    display: flex;
    flex-direction: column;
    gap: 3px;
}
.prof-user-name {
    font-family: 'Sora', sans-serif;
    font-size: 18px;
    font-weight: 800;
    color: #0E1022;
    margin: 0;
}
.prof-user-sub {
    font-size: 13px;
    color: #64748B;
}

/* Champs de saisie */
.prof-form {
    display: flex;
    flex-direction: column;
    gap: 14px;
}
.prof-field-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.prof-field-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}
@media (max-width: 520px) {
    .prof-field-row {
        grid-template-columns: 1fr;
    }
}
.prof-label {
    font-family: 'Inter', sans-serif;
    font-size: 13px;
    font-weight: 700;
    color: #334155;
}
.prof-input {
    font-family: 'Inter', sans-serif;
    font-size: 13.5px;
    border: 1.5px solid #CBD5E1;
    border-radius: 8px;
    padding: 10px 14px;
    background: #FFFFFF;
    color: #0E1022;
    box-sizing: border-box;
    width: 100%;
    min-height: 44px;
    transition: all 0.15s ease;
}
.prof-input:focus {
    outline: none;
    border-color: #0E1022;
    box-shadow: 0 0 0 3px rgba(14, 16, 34, 0.08);
}
.prof-input[readonly] {
    background: #F8FAFC;
    color: #64748B;
    border-color: #E2E8F0;
    cursor: not-allowed;
}

/* Bouton principal de soumission MonKayier */
.prof-submit-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: #0E1022;
    color: #FFFFFF;
    border: none;
    border-radius: 8px;
    padding: 12px 20px;
    font-family: 'Sora', sans-serif;
    font-size: 14px;
    font-weight: 750;
    cursor: pointer;
    transition: all 0.15s ease;
    margin-top: 6px;
    min-height: 46px;
}
.prof-submit-btn:hover {
    background: #1C2038;
}
.prof-submit-btn svg {
    width: 16px;
    height: 16px;
    stroke: #D4F25C;
    stroke-width: 2.4;
    fill: none;
}

/* Colonne droite : Préférences et Sécurité */
.prof-right-col {
    display: flex;
    flex-direction: column;
    gap: 18px;
}
.prof-pref-title {
    font-family: 'Sora', sans-serif;
    font-size: 16px;
    font-weight: 800;
    color: #0E1022;
    margin: 0 0 4px 0;
}
.prof-pref-list {
    display: flex;
    flex-direction: column;
    gap: 0;
}
.prof-pref-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    padding: 13px 0;
    border-top: 1px solid #E2E8F0;
}
.prof-pref-item:first-child {
    border-top: none;
    padding-top: 6px;
}
.ppi-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}
.ppi-name {
    font-family: 'Sora', sans-serif;
    font-size: 13.5px;
    font-weight: 750;
    color: #0E1022;
}
.ppi-desc {
    font-size: 12px;
    color: #64748B;
}

/* Toggles MonKayier Rebrandés */
.mk-toggle-switch {
    width: 44px;
    height: 24px;
    border-radius: 999px;
    background: #0E1022;
    display: inline-block;
    position: relative;
    flex: none;
    cursor: pointer;
    transition: background 0.2s ease;
}
.mk-toggle-switch i {
    position: absolute;
    right: 2px;
    top: 2px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #D4F25C;
    transition: transform 0.2s ease;
}

/* Badge sécurisé / actif */
.prof-badge-pill {
    font-size: 11.5px;
    font-weight: 750;
    padding: 3px 9px;
    border-radius: 6px;
}
.pbp-active {
    background: #ECFDF5;
    color: #065F46;
}
.pbp-secure {
    background: #EFF6FF;
    color: #1D4ED8;
}

/* Bouton Déconnexion */
.prof-logout-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #FFFFFF;
    border: 1.5px solid #CBD5E1;
    border-radius: 8px;
    padding: 7px 14px;
    font-family: 'Inter', sans-serif;
    font-size: 12.5px;
    font-weight: 700;
    color: #DC2626;
    text-decoration: none;
    transition: all 0.15s ease;
}
.prof-logout-btn:hover {
    background: #FEF2F2;
    border-color: #F87171;
}
.prof-logout-btn svg {
    width: 14px;
    height: 14px;
    stroke: currentColor;
    stroke-width: 2.2;
    fill: none;
}
</style>

<div class="prof-container">

    {{-- EN-TÊTE --}}
    <div class="prof-header">
        <h1 class="prof-page-title">Paramètres & Compte</h1>
        <p class="prof-page-subtitle">Gère tes informations personnelles, ton historique et tes préférences</p>
    </div>

    {{-- BARRE DE NAVIGATION SOUS-PAGES --}}
    <div class="mk-subnav">
        <a href="{{ route('student.profile') }}" class="mk-subnav-link active">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
            <span>Mon Profil</span>
        </a>
        <a href="{{ route('student.history') }}" class="mk-subnav-link">
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

    {{-- MESSAGE DE CONFIRMATION --}}
    @if(session('success'))
    <div style="background:#ECFDF5;border:1.5px solid #A7F3D0;color:#065F46;padding:12px 18px;border-radius:10px;display:flex;align-items:center;gap:10px;font-size:13.5px;font-weight:600">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
        <span><b>Modifications enregistrées :</b> {{ session('success') }}</span>
    </div>
    @endif

    {{-- GRILLE PRINCIPALE --}}
    <div class="prof-main-grid">

        {{-- FORMULAIRE PROFIL ÉLÈVE --}}
        <div class="prof-card">
            <div class="prof-user-header">
                <div class="prof-avatar">
                    {{ strtoupper(substr($student->first_name ?? 'K', 0, 1) . substr($student->last_name ?? 'M', 0, 1)) }}
                </div>
                <div class="prof-user-meta">
                    <h2 class="prof-user-name">{{ $student->full_name }}</h2>
                    <span class="prof-user-sub">{{ $student->class_name }} · {{ $student->bio ?: 'Programme officiel MEPST Togo' }}</span>
                </div>
            </div>

            <form method="POST" action="{{ route('student.profile.update') }}" class="prof-form">
                @csrf
                <div class="prof-field-row">
                    <div class="prof-field-group">
                        <label class="prof-label">Prénom *</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $student->first_name) }}" required class="prof-input">
                    </div>
                    <div class="prof-field-group">
                        <label class="prof-label">Nom *</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $student->last_name) }}" required class="prof-input">
                    </div>
                </div>

                <div class="prof-field-group">
                    <label class="prof-label">Adresse e-mail *</label>
                    <input type="email" name="email" value="{{ old('email', $student->email) }}" required class="prof-input">
                </div>

                <div class="prof-field-group">
                    <label class="prof-label">Bio / Établissement scolaire</label>
                    <input type="text" name="bio" value="{{ old('bio', $student->bio) }}" placeholder="Ex: Élève en Terminale D au Lycée de Tokoin (Lomé)" class="prof-input">
                </div>

                <div class="prof-field-group">
                    <label class="prof-label">Niveau scolaire actuel</label>
                    <input type="text" readonly value="{{ $student->level_name }}" class="prof-input">
                </div>

                <button type="submit" class="prof-submit-btn">
                    <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    <span>Enregistrer les modifications</span>
                </button>
            </form>
        </div>

        {{-- COLONNE DROITE : PRÉFÉRENCES & SÉCURITÉ --}}
        <div class="prof-right-col">

            {{-- Préférences d'apprentissage --}}
            <div class="prof-card">
                <h3 class="prof-pref-title">Préférences d'apprentissage</h3>
                <div class="prof-pref-list">
                    
                    <div class="prof-pref-item">
                        <div class="ppi-info">
                            <span class="ppi-name">Langue du tuteur</span>
                            <span class="ppi-desc">Français (officiel)</span>
                        </div>
                        <span class="prof-badge-pill pbp-active">Actif</span>
                    </div>

                    <div class="prof-pref-item">
                        <div class="ppi-info">
                            <span class="ppi-name">Rappels quotidiens de révision</span>
                            <span class="ppi-desc">Chaque jour à 18 h</span>
                        </div>
                        <span class="mk-toggle-switch">
                            <i></i>
                        </span>
                    </div>

                    <div class="prof-pref-item">
                        <div class="ppi-info">
                            <span class="ppi-name">Partage du rapport hebdomadaire</span>
                            <span class="ppi-desc">Partagé automatiquement avec mes parents</span>
                        </div>
                        <span class="mk-toggle-switch">
                            <i></i>
                        </span>
                    </div>

                </div>
            </div>

            {{-- Compte et Sécurité --}}
            <div class="prof-card">
                <h3 class="prof-pref-title">Compte et Sécurité</h3>
                <div class="prof-pref-list">

                    <div class="prof-pref-item">
                        <div class="ppi-info">
                            <span class="ppi-name">Mot de passe & Sécurité OTP</span>
                            <span class="ppi-desc">Authentification par code email à 6 chiffres</span>
                        </div>
                        <span class="prof-badge-pill pbp-secure">Sécurisé</span>
                    </div>

                    <div class="prof-pref-item">
                        <div class="ppi-info">
                            <span class="ppi-name">Réinitialiser mon parcours (État vierge)</span>
                            <span class="ppi-desc">Effacer les scores et repartir du Test de niveau initial</span>
                        </div>
                        <form method="POST" action="{{ route('student.reset') }}" onsubmit="return confirm('Confirmer la remise à zéro complète de ton parcours et de tes résultats ?');">
                            @csrf
                            <button type="submit" class="prof-logout-btn" style="cursor:pointer;">
                                <svg viewBox="0 0 24 24"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
                                <span>Remettre à zéro</span>
                            </button>
                        </form>
                    </div>

                    <div class="prof-pref-item">
                        <div class="ppi-info">
                            <span class="ppi-name">Se déconnecter</span>
                            <span class="ppi-desc">Quitter la session sur cet appareil</span>
                        </div>
                        <a href="/logout" class="prof-logout-btn">
                            <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                            <span>Déconnexion</span>
                        </a>
                    </div>

                </div>
            </div>

        </div>

    </div>

</div>
@endsection
