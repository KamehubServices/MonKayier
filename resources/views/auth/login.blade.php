@extends('layouts.auth')

@section('title', 'Connexion — MONKAYIER')

@section('content')
    <div>
        <h2 class="auth-title">Content de te revoir.</h2>
        <p class="auth-subtitle">Connecte-toi pour retrouver ton espace.</p>
    </div>

    <form id="login-form" onsubmit="handleLogin(event)" style="display: flex; flex-direction: column; gap: 16px;">
        <div class="form-group">
            <label class="form-label" for="login-email">Adresse e-mail</label>
            <input type="email" id="login-email" class="form-input" placeholder="nom@exemple.com" required autofocus>
        </div>

        <div class="form-group">
            <label class="form-label" for="login-password">Mot de passe</label>
            <input type="password" id="login-password" class="form-input" placeholder="Ton mot de passe" required>
        </div>

        <div style="display: flex; justify-content: flex-start; margin-top: -6px;">
            <a href="/forgot-password" style="font-size: 13.5px; font-weight: 600; color: var(--auth-indigo); text-decoration: none;">
                Mot de passe oublié ?
            </a>
        </div>

        <button type="submit" id="btn-login" class="btn-auth-primary">
            Se connecter
        </button>
    </form>

    <div class="auth-footer-text">
        Pas encore de compte ? <a href="/register">Créer un compte</a>
    </div>
@endsection

@push('scripts')
<script>
    async function handleLogin(e) {
        e.preventDefault();
        const email = document.getElementById('login-email').value.trim().toLowerCase();
        const password = document.getElementById('login-password').value;
        const btn = document.getElementById('btn-login');
        btn.textContent = "Envoi du code OTP...";
        btn.style.opacity = "0.75";
        btn.disabled = true;

        try {
            const res = await fetch('/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ email: email, password: password })
            });
            const data = await res.json();
            if (data && data.redirect_url) {
                window.location.href = data.redirect_url;
                return;
            }
        } catch (err) {
            // Fallback
        }
        window.location.href = '/verify-email?type=login&email=' + encodeURIComponent(email);
    }
</script>
@endpush
