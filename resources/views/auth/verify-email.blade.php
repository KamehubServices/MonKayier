@extends('layouts.auth')

@section('title', 'Vérification de l’e-mail — MONKAYIER')

@push('styles')
<style>
    .otp-container {
        display: flex;
        gap: 10px;
        justify-content: space-between;
        margin: 6px 0 10px 0;
    }
    .otp-input {
        flex: 1;
        width: 100%;
        max-width: 62px;
        height: 56px;
        border: 1.5px solid var(--auth-border, #CBD6E6);
        border-radius: 12px;
        background: #FFFFFF;
        font-family: inherit;
        font-size: 22px;
        font-weight: 700;
        text-align: center;
        color: var(--auth-text-dark, #14325C);
        box-sizing: border-box;
        transition: all 0.2s ease;
    }
    .otp-input:focus {
        outline: none;
        border-color: var(--auth-indigo, #5143E8);
        box-shadow: 0 0 0 3px rgba(81, 67, 232, 0.16);
        background: #FFFFFF;
    }
    .resend-alert {
        display: none;
        background: #ECFDF5;
        border: 1px solid #A7F3D0;
        border-radius: 12px;
        padding: 12px 14px;
        color: #065F46;
        font-size: 13.5px;
        text-align: center;
        margin-top: -6px;
    }
</style>
@endpush

@section('content')
    <div>
        <h2 class="auth-title" style="font-size: 30px;">Vérifie ta boîte mail.</h2>
        <p class="auth-subtitle" style="margin-top: 6px;">
            Nous avons envoyé un code à 6 chiffres à <b id="target-email" style="color: var(--auth-indigo, #5143E8);">ton adresse e-mail</b>.
        </p>
    </div>

    <form id="otp-form" onsubmit="handleOtpSubmit(event)" style="display: flex; flex-direction: column; gap: 20px;">
        <!-- Grille des 6 chiffres OTP -->
        <div class="otp-container" id="otp-inputs">
            <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-input" data-index="0" autofocus required>
            <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-input" data-index="1" required>
            <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-input" data-index="2" required>
            <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-input" data-index="3" required>
            <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-input" data-index="4" required>
            <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-input" data-index="5" required>
        </div>

        <div id="resend-message" class="resend-alert">
            ✓ Un nouveau code de vérification à 6 chiffres a été envoyé à votre e-mail.
        </div>

        <button type="submit" id="btn-validate-otp" class="btn-auth-primary">
            Valider et continuer
        </button>
    </form>

    <div class="auth-footer-text" style="margin-top: 2px;">
        Tu n’as rien reçu ? 
        <a href="javascript:void(0)" onclick="resendOtpCode()" style="font-weight: 700; color: #111126; text-decoration: underline;">
            Renvoyer le code
        </a>
    </div>
@endsection

@push('scripts')
<script>
    const inputs = document.querySelectorAll('.otp-input');
    const form = document.getElementById('otp-form');
    const validateBtn = document.getElementById('btn-validate-otp');

    const urlParams = new URLSearchParams(window.location.search);
    const flowType = urlParams.get('type') || 'login';
    const userRole = urlParams.get('role') || 'student';
    const userEmail = urlParams.get('email');

    if (userEmail) {
        const targetEl = document.getElementById('target-email');
        if (targetEl) targetEl.textContent = userEmail;
    }

    // Gestion de la saisie automatique sur les 6 cases
    inputs.forEach((input, index) => {
        input.addEventListener('input', (e) => {
            const val = e.target.value;
            // Ne garder que le dernier chiffre
            if (val.length > 1) {
                e.target.value = val.slice(-1);
            }
            if (e.target.value !== '') {
                if (index < inputs.length - 1) {
                    inputs[index + 1].focus();
                } else {
                    // Dernier chiffre rempli : auto validation optionnelle si toutes les cases sont pleines
                    let allFilled = true;
                    inputs.forEach(i => { if (!i.value) allFilled = false; });
                    if (allFilled) {
                        setTimeout(() => handleOtpSubmit(new Event('submit')), 150);
                    }
                }
            }
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && e.target.value === '' && index > 0) {
                inputs[index - 1].focus();
            } else if (e.key === 'ArrowLeft' && index > 0) {
                inputs[index - 1].focus();
            } else if (e.key === 'ArrowRight' && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        });

        // Prise en charge du copier-coller (ex: coller "482910")
        input.addEventListener('paste', (e) => {
            e.preventDefault();
            const pasteData = (e.clipboardData || window.clipboardData).getData('text').trim();
            const digits = pasteData.replace(/\D/g, '').split('').slice(0, 6);
            if (digits.length > 0) {
                digits.forEach((digit, i) => {
                    if (inputs[i]) inputs[i].value = digit;
                });
                const nextIndex = Math.min(digits.length, inputs.length - 1);
                inputs[nextIndex].focus();
                if (digits.length >= 6) {
                    setTimeout(() => handleOtpSubmit(new Event('submit')), 150);
                }
            }
        });
    });

    // Validation du formulaire OTP
    function handleOtpSubmit(e) {
        if (e && e.preventDefault) e.preventDefault();
        let code = '';
        inputs.forEach(i => code += i.value);

        if (code.length < 6) {
            inputs.forEach(i => {
                if (!i.value) { i.focus(); return false; }
            });
            return;
        }

        validateBtn.textContent = "Code validé ! Redirection...";
        validateBtn.style.opacity = "0.85";
        validateBtn.disabled = true;

        setTimeout(() => {
            // Parcours de connexion : validation OTP -> Page de confirmation
            if (flowType === 'login') {
                window.location.href = '/auth/success?type=login' + (userEmail ? '&email=' + encodeURIComponent(userEmail) : '');
            } else if (flowType === 'register') {
                if (userRole === 'teacher' || userRole === 'prof') {
                    window.location.href = '/onboarding/teacher';
                } else if (userRole === 'parent') {
                    window.location.href = '/onboarding/parent';
                } else if (userRole === 'etab') {
                    window.location.href = '/auth/success?type=register';
                } else {
                    window.location.href = '/onboarding/student';
                }
            } else {
                // Par défaut si type non spécifié
                if (userRole === 'teacher' || userRole === 'prof') {
                    window.location.href = '/onboarding/teacher';
                } else if (userRole === 'parent') {
                    window.location.href = '/onboarding/parent';
                } else {
                    window.location.href = '/auth/success?type=login' + (userEmail ? '&email=' + encodeURIComponent(userEmail) : '');
                }
            }
        }, 350);
    }

    // Renvoi du code avec confirmation visuelle
    function resendOtpCode() {
        const msg = document.getElementById('resend-message');
        msg.style.display = 'block';
        inputs.forEach(i => i.value = '');
        inputs[0].focus();
        setTimeout(() => {
            msg.style.display = 'none';
        }, 4000);
    }
</script>
@endpush
