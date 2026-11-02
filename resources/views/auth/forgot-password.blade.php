@extends('layouts.auth')

@section('title', 'Mot de passe oublié — MONKAYIER')

@section('content')
    <div>
        <h2 class="auth-title">Mot de passe oublié ?</h2>
        <p class="auth-subtitle">Indique ton e-mail, nous t'envoyons un lien pour le changer.</p>
    </div>

    <form id="forgot-form" onsubmit="handleForgot(event)" style="display: flex; flex-direction: column; gap: 16px;">
        <div class="form-group">
            <label class="form-label" for="forgot-email">Adresse e-mail</label>
            <input type="email" id="forgot-email" class="form-input" placeholder="nom@exemple.com" required autofocus>
        </div>

        <button type="submit" id="btn-forgot" class="btn-auth-primary">
            Envoyer le lien
        </button>
    </form>

    <div id="forgot-success" style="display: none; background: #ECFDF5; border: 1px solid #A7F3D0; border-radius: 12px; padding: 14px; color: #065F46; font-size: 13.5px; text-align: center;">
        ✓ Un lien sécurisé de réinitialisation a été envoyé sur votre adresse e-mail.
    </div>

    <div style="text-align: center; margin-top: 14px;">
        <a href="/login" class="btn-auth-secondary" style="color: var(--auth-indigo); font-weight: 600;">
            Retour à la connexion
        </a>
    </div>
@endsection

@push('scripts')
<script>
    function handleForgot(e) {
        e.preventDefault();
        const btn = document.getElementById('btn-forgot');
        btn.textContent = "Envoi en cours...";
        btn.disabled = true;

        setTimeout(() => {
            btn.style.display = "none";
            document.getElementById('forgot-email').disabled = true;
            document.getElementById('forgot-success').style.display = "block";
        }, 500);
    }
</script>
@endpush
