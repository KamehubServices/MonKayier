@extends('layouts.auth')

@section('title', 'Confirmation — MONKAYIER')

@section('content')
    <div style="text-align: center; padding: 12px 0;">
        <div style="width: 72px; height: 72px; border-radius: 50%; background: #ECFDF5; border: 2px solid #A7F3D0; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px;">
            <svg style="width: 38px; height: 38px; color: #059669;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <h2 class="auth-title" id="success-title" style="margin-bottom: 10px;">Profil configuré avec succès !</h2>
        <p class="auth-subtitle" id="success-desc" style="max-width: 380px; margin: 0 auto 28px auto;">
            Votre compte MonKayier est opérationnel. Toutes vos préférences ont été enregistrées avec succès.
        </p>

        <div style="background: #F8FAFC; border: 1.5px solid var(--auth-border); border-radius: 16px; padding: 20px; text-align: left; margin-bottom: 24px;">
            <div style="font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: #64748B; margin-bottom: 12px;">
                Récapitulatif de la session :
            </div>
            <div style="display: flex; flex-direction: column; gap: 10px; font-size: 14px;">
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--auth-text-muted);">Statut du compte :</span>
                    <span style="font-weight: 600; color: #059669;">● Actif & Vérifié</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--auth-text-muted);">Contexte pays :</span>
                    <span style="font-weight: 600; color: #1E293B;">Togo (TG) — MEPST</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--auth-text-muted);">Type d'opération :</span>
                    <span style="font-weight: 600; color: var(--auth-indigo);" id="success-operation">Connexion sécurisée</span>
                </div>
            </div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 10px;">
            <a href="/student/dashboard" id="btn-success-primary" class="btn-auth-primary">
                Accéder à mon tableau de bord
            </a>
            <a href="/" id="btn-success-secondary" class="btn-auth-secondary">
                Retour à l'accueil
            </a>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const params = new URLSearchParams(window.location.search);
    const type = params.get('type') || 'login';
    const email = params.get('email') || '';
    const titleEl = document.getElementById('success-title');
    const descEl = document.getElementById('success-desc');
    const opEl = document.getElementById('success-operation');
    const primaryBtn = document.getElementById('btn-success-primary');
    const secondaryBtn = document.getElementById('btn-success-secondary');

    if (type === 'student') {
        titleEl.textContent = "Profil Élève créé — Place au Test de niveau !";
        descEl.textContent = "Pour construire ton parcours d'études sur mesure à partir d'une page vierge, la première étape obligatoire est ton Test de niveau initial.";
        opEl.textContent = "Onboarding Élève validé · Calibration requise";
        primaryBtn.textContent = "Passer mon Test de niveau initial";
        primaryBtn.href = "/student/test-niveau?first_time=1";
    } else if (type === 'teacher') {
        titleEl.textContent = "Espace Enseignant prêt !";
        descEl.textContent = "Votre première classe a été créée avec vos matières d'enseignement.";
        opEl.textContent = "Onboarding Enseignant validé";
        primaryBtn.textContent = "Accéder au tableau de bord";
        primaryBtn.href = "/student/dashboard";
    } else if (type === 'parent') {
        titleEl.textContent = "Liaison Parent validée !";
        descEl.textContent = "Le compte de votre enfant est désormais rattaché à votre espace de supervision.";
        opEl.textContent = "Onboarding Parent validé";
        primaryBtn.textContent = "Accéder au tableau de bord";
        primaryBtn.href = "/student/dashboard";
    } else if (type === 'register') {
        titleEl.textContent = "Compte créé avec succès !";
        descEl.textContent = "Votre adresse e-mail a été vérifiée par code OTP. Vous pouvez désormais vous connecter.";
        opEl.textContent = "Inscription & Vérification OTP";
        primaryBtn.textContent = "Se connecter";
        primaryBtn.href = "/login";
    } else {
        // type === 'login'
        titleEl.textContent = "Connexion confirmée !";
        descEl.textContent = "Votre code OTP a été validé avec succès. Votre session sécurisée est active.";
        opEl.textContent = "Connexion & Code OTP vérifié";
        primaryBtn.textContent = "Accéder à mon tableau de bord élève";
        primaryBtn.href = "/student/dashboard";
        secondaryBtn.textContent = "Se déconnecter";
        secondaryBtn.href = "/login";
    }
</script>
@endpush
