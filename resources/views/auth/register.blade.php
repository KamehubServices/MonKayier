@extends('layouts.auth')

@section('title', 'Créer un compte — MONKAYIER')

@section('content')
    <div>
        <h2 class="auth-title">Créer mon compte</h2>
        <p class="auth-subtitle">Choisis ton profil pour commencer.</p>
    </div>

    <!-- Sélecteur de profil -->
    <div class="role-tabs" role="tablist">
        <button type="button" class="role-tab active" id="tab-eleve" onclick="switchRole('eleve')">Élève</button>
        <button type="button" class="role-tab" id="tab-prof" onclick="switchRole('prof')">Enseignant</button>
        <button type="button" class="role-tab" id="tab-parent" onclick="switchRole('parent')">Parent</button>
        <button type="button" class="role-tab" id="tab-etab" onclick="switchRole('etab')">Établissement</button>
    </div>

    <!-- Description dynamique du profil -->
    <p id="role-hint" style="font-size: 13.5px; color: var(--auth-text-muted); min-height: 40px; display: flex; align-items: center;">
        Accède à tes cours, tes exercices et ton assistant IA.
    </p>

    <!-- Formulaire d'inscription -->
    <form id="register-form" onsubmit="submitRegistration(event)" style="display: flex; flex-direction: column; gap: 16px;">
        <div class="form-group">
            <label class="form-label" id="label-name" for="reg-name">Nom complet</label>
            <input type="text" id="reg-name" class="form-input" placeholder="Ton prénom et ton nom" required autofocus>
        </div>

        <div class="form-group">
            <label class="form-label" for="reg-email">Adresse e-mail</label>
            <input type="email" id="reg-email" class="form-input" placeholder="nom@exemple.com" required>
        </div>

        <div class="form-group">
            <label class="form-label" for="reg-password">Mot de passe</label>
            <input type="password" id="reg-password" class="form-input" placeholder="8 caractères minimum" minlength="8" required>
        </div>

        <button type="submit" id="reg-submit-btn" class="btn-auth-primary">
            Créer mon compte élève
        </button>
    </form>

    <div class="auth-footer-text">
        Déjà inscrit ? <a href="/login">Se connecter</a>
    </div>
@endsection

@push('scripts')
<script>
    const roleConfig = {
        eleve: {
            hint: "Accède à tes cours, tes exercices et ton assistant IA.",
            labelName: "Nom complet",
            placeholderName: "Ton prénom et ton nom",
            btnText: "Créer mon compte élève",
            onboardingUrl: "/onboarding/student"
        },
        prof: {
            hint: "Prépare tes sujets et suis la progression de tes classes.",
            labelName: "Nom complet",
            placeholderName: "Votre prénom et votre nom",
            btnText: "Créer mon compte enseignant",
            onboardingUrl: "/onboarding/teacher"
        },
        parent: {
            hint: "Suis les progrès, les devoirs et les résultats de votre enfant.",
            labelName: "Nom complet du parent / tuteur",
            placeholderName: "Votre prénom et votre nom",
            btnText: "Créer mon compte parent",
            onboardingUrl: "/onboarding/parent"
        },
        etab: {
            hint: "Gère tes classes, tes enseignants et le lien avec les familles.",
            labelName: "Nom de l’établissement scolaire",
            placeholderName: "Nom de votre établissement (ex : Lycée Tokoin)",
            btnText: "Créer le compte établissement",
            onboardingUrl: "/auth/success?type=register"
        }
    };

    let selectedRole = 'eleve';

    // Lire paramètre URL ?role=...
    const urlParams = new URLSearchParams(window.location.search);
    const initialRole = urlParams.get('role');
    if (initialRole && roleConfig[initialRole]) {
        switchRole(initialRole);
    }

    function switchRole(roleKey) {
        selectedRole = roleKey;
        const cfg = roleConfig[roleKey];
        if (!cfg) return;

        document.querySelectorAll('.role-tab').forEach(btn => btn.classList.remove('active'));
        const activeTab = document.getElementById('tab-' + roleKey);
        if (activeTab) activeTab.classList.add('active');

        document.getElementById('role-hint').textContent = cfg.hint;
        document.getElementById('label-name').textContent = cfg.labelName;
        document.getElementById('reg-name').placeholder = cfg.placeholderName;
        document.getElementById('reg-submit-btn').textContent = cfg.btnText;
    }

    async function submitRegistration(e) {
        e.preventDefault();
        const nameInput = document.getElementById('reg-name');
        const emailInput = document.getElementById('reg-email');
        const passInput = document.getElementById('reg-password');
        const name = nameInput ? nameInput.value.trim() : '';
        const email = emailInput ? emailInput.value.trim() : '';
        const password = passInput ? passInput.value : '';
        const btn = document.getElementById('reg-submit-btn');
        btn.textContent = "Création du compte...";
        btn.style.opacity = "0.75";
        btn.disabled = true;

        try {
            const res = await fetch('/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    name: name,
                    email: email,
                    password: password,
                    role: selectedRole
                })
            });
            const data = await res.json();
            if (data && data.redirect_url) {
                window.location.href = data.redirect_url;
                return;
            }
        } catch (err) {
            // Fallback
        }
        window.location.href = '/verify-email?type=register&role=' + encodeURIComponent(selectedRole) + (email ? '&email=' + encodeURIComponent(email) : '');
    }
</script>
@endpush
