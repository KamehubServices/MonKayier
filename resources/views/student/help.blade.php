@extends('layouts.student')

@section('title', 'Aide et contact — Espace Élève MonKayier')

@section('content')
<style>
/* ==========================================================================
   AIDE & CONTACT — REBRANDING MONKAYIER
   CHARTE : SORA / INTER · ZÉRO EMOJI · ICÔNES VECTORIELLES SVG · NAVY & LIME
   ========================================================================== */

.help-container {
    max-width: 1100px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 20px;
    box-sizing: border-box;
    padding-bottom: 50px;
}

/* En-tête */
.help-header {
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.help-page-title {
    font-family: 'Sora', sans-serif;
    font-size: 26px;
    font-weight: 800;
    color: #0E1022;
    margin: 0;
    letter-spacing: -0.02em;
}
.help-page-subtitle {
    font-size: 13.5px;
    color: #64748B;
    margin: 0;
}

/* Grille 2 colonnes */
.help-main-grid {
    display: grid;
    grid-template-columns: 1.25fr 1fr;
    gap: 22px;
    align-items: start;
}
@media (max-width: 900px) {
    .help-main-grid {
        grid-template-columns: 1fr;
    }
}

/* Carte standard */
.help-card {
    background: #FFFFFF;
    border: 1.5px solid #E2E8F0;
    border-radius: 14px;
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 16px;
    box-sizing: border-box;
}
.help-card-title {
    font-family: 'Sora', sans-serif;
    font-size: 17px;
    font-weight: 800;
    color: #0E1022;
    margin: 0;
}

/* Liste FAQ interactive */
.help-faq-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.help-faq-item {
    background: #F8FAFC;
    border: 1.5px solid #E2E8F0;
    border-radius: 10px;
    padding: 14px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    cursor: pointer;
    transition: all 0.15s ease;
}
.help-faq-item:hover {
    background: #FFFFFF;
    border-color: #0E1022;
    box-shadow: 0 3px 10px rgba(14, 16, 34, 0.04);
}
.hfi-content {
    display: flex;
    flex-direction: column;
    gap: 3px;
}
.hfi-question {
    font-family: 'Sora', sans-serif;
    font-size: 13.5px;
    font-weight: 800;
    color: #0E1022;
}
.hfi-answer {
    font-size: 12.5px;
    color: #64748B;
}
.hfi-action {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    font-weight: 700;
    color: #0E1022;
    flex: none;
}
.hfi-action svg {
    width: 14px;
    height: 14px;
    stroke: currentColor;
    stroke-width: 2.2;
    fill: none;
}

/* Formulaire Contact */
.help-form {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.help-label {
    display: flex;
    flex-direction: column;
    gap: 6px;
    font-size: 13px;
    font-weight: 700;
    color: #334155;
}
.help-input, .help-textarea {
    font-family: 'Inter', sans-serif;
    font-size: 13.5px;
    border: 1.5px solid #CBD5E1;
    border-radius: 8px;
    padding: 10px 14px;
    background: #FFFFFF;
    color: #0E1022;
    box-sizing: border-box;
    width: 100%;
    transition: all 0.15s ease;
}
.help-input:focus, .help-textarea:focus {
    outline: none;
    border-color: #0E1022;
    box-shadow: 0 0 0 3px rgba(14, 16, 34, 0.08);
}
.help-textarea {
    min-height: 120px;
    resize: none;
}
.help-send-btn {
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
    margin-top: 4px;
}
.help-send-btn:hover {
    background: #1C2038;
}
.help-send-btn svg {
    width: 16px;
    height: 16px;
    stroke: #D4F25C;
    stroke-width: 2.4;
    fill: none;
}
</style>

<div class="help-container">

    {{-- EN-TÊTE --}}
    <div class="help-header">
        <h1 class="help-page-title">Centre d'Aide & Assistance</h1>
        <p class="help-page-subtitle">Une question sur tes cours ou ton compte ? Nous sommes là pour t’accompagner</p>
    </div>

    {{-- NAVIGATION SOUS-PAGES --}}
    <div class="mk-subnav">
        <a href="{{ route('student.profile') }}" class="mk-subnav-link">
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
        <a href="{{ route('student.help') }}" class="mk-subnav-link active">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
            <span>Aide & Contact</span>
        </a>
    </div>

    {{-- GRILLE FAQ & CONTACT --}}
    <div class="help-main-grid">

        {{-- FAQ --}}
        <div class="help-card">
            <h3 class="help-card-title">Questions fréquentes</h3>
            <div class="help-faq-list">

                <div class="help-faq-item">
                    <div class="hfi-content">
                        <span class="hfi-question">Comment importer un cours ?</span>
                        <span class="hfi-answer">Depuis la bibliothèque, dépose ton PDF ou les photos de ton cahier de cours.</span>
                    </div>
                    <div class="hfi-action">
                        <span>Consulter</span>
                        <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </div>

                <div class="help-faq-item">
                    <div class="hfi-content">
                        <span class="hfi-question">Comment fonctionne l’assistant IA ?</span>
                        <span class="hfi-answer">Il s’appuie exclusivement sur tes cours officiels togolais pour t'expliquer pas-à-pas.</span>
                    </div>
                    <div class="hfi-action">
                        <span>Consulter</span>
                        <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </div>

                <div class="help-faq-item">
                    <div class="hfi-content">
                        <span class="hfi-question">Mes données scolaires sont-elles protégées ?</span>
                        <span class="hfi-answer">Oui, conformes aux exigences de confidentialité pour les élèves et les familles.</span>
                    </div>
                    <div class="hfi-action">
                        <span>Consulter</span>
                        <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </div>

            </div>
        </div>

        {{-- FORMULAIRE DE CONTACT --}}
        <div class="help-card">
            <h3 class="help-card-title">Nous écrire directement</h3>
            <form class="help-form" onsubmit="event.preventDefault(); alert('Message envoyé à l’équipe pédagogique MonKayier.');">
                <label class="help-label">
                    <span>Adresse e-mail</span>
                    <input type="email" placeholder="nom@exemple.com" required class="help-input">
                </label>
                <label class="help-label">
                    <span>Votre message</span>
                    <textarea placeholder="Expliquez-nous votre problème ou suggestion..." required class="help-textarea"></textarea>
                </label>
                <button type="submit" class="help-send-btn">
                    <svg viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                    <span>Envoyer le message</span>
                </button>
            </form>
        </div>

    </div>

</div>
@endsection
