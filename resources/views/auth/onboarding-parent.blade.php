@extends('layouts.auth')

@section('title', 'Liez le compte de votre enfant — Onboarding Parent')

@section('content')
    <div class="step-badge">
        <div class="step-pills">
            <span class="step-pill active"></span>
            <span class="step-pill inactive"></span>
        </div>
        <span>Étape 1 sur 2</span>
    </div>

    <div>
        <h2 class="auth-title">Liez le compte de votre enfant.</h2>
        <p class="auth-subtitle">Saisissez le code de liaison affiché dans son compte MonKayier.</p>
    </div>

    <form onsubmit="handleParentLink(event)" style="display: flex; flex-direction: column; gap: 18px;">
        <div class="form-group">
            <label class="form-label" for="link-code">Code de liaison</label>
            <input type="text" id="link-code" class="form-input" placeholder="[CODE DE LIAISON]" value="KOF-2026-TG" required autofocus style="text-transform: uppercase; font-family: monospace; letter-spacing: 0.1em; font-size: 16px;">
        </div>

        <p style="font-size: 13px; color: var(--auth-text-muted); line-height: 1.5; margin-top: -4px;">
            Vous verrez sa progression et ses échéances. Ses conversations avec ses cours restent privées.
        </p>

        <button type="submit" id="btn-link-child" class="btn-auth-primary" style="margin-top: 6px;">
            Lier le compte
        </button>

        <a href="/auth/success?type=parent" class="btn-auth-secondary" style="margin-top: 4px;">
            Plus tard
        </a>
    </form>
@endsection

@push('scripts')
<script>
    function handleParentLink(e) {
        e.preventDefault();
        const btn = document.getElementById('btn-link-child');
        btn.textContent = "Liaison du compte...";
        btn.disabled = true;

        setTimeout(() => {
            window.location.href = '/auth/success?type=parent';
        }, 350);
    }
</script>
@endpush
