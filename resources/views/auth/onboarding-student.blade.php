@extends('layouts.auth')

@section('title', 'Bienvenue sur MonKayier — Onboarding Élève')

@section('content')
    <div class="step-badge">
        <div class="step-pills">
            <span class="step-pill active"></span>
            <span class="step-pill inactive"></span>
            <span class="step-pill inactive"></span>
        </div>
        <span>Étape 1 sur 3</span>
    </div>

    <div>
        <h2 class="auth-title">Bienvenue sur MonKayier.</h2>
        <p class="auth-subtitle">Dis-nous en plus pour préparer ton parcours.</p>
    </div>

    <form onsubmit="handleStudentOnboarding(event)" style="display: flex; flex-direction: column; gap: 20px;">
        <!-- 1. Ton niveau -->
        <div class="form-group">
            <label class="form-label">Ton niveau</label>
            <div class="chips-container" id="levels-group">
                <span class="chip" onclick="selectSingleChip(this, 'level')">Seconde</span>
                <span class="chip" onclick="selectSingleChip(this, 'level')">Première</span>
                <span class="chip selected" onclick="selectSingleChip(this, 'level')">Terminale</span>
            </div>
        </div>

        <!-- 2. Tes matières -->
        <div class="form-group">
            <label class="form-label">Tes matières</label>
            <div class="chips-container" id="subjects-group">
                <span class="chip selected" onclick="toggleMultiChip(this)">Maths</span>
                <span class="chip" onclick="toggleMultiChip(this)">Physique-Chimie</span>
                <span class="chip" onclick="toggleMultiChip(this)">SVT</span>
                <span class="chip" onclick="toggleMultiChip(this)">Français</span>
                <span class="chip" onclick="toggleMultiChip(this)">Anglais</span>
            </div>
        </div>

        <!-- 3. Ton objectif -->
        <div class="form-group">
            <label class="form-label">Ton objectif</label>
            <div class="chips-container" id="objectives-group">
                <span class="chip" onclick="selectSingleChip(this, 'objective')">Réussir mon année</span>
                <span class="chip selected" onclick="selectSingleChip(this, 'objective')">Préparer le BAC</span>
                <span class="chip" onclick="selectSingleChip(this, 'objective')">Combler mes lacunes</span>
            </div>
        </div>

        <button type="submit" id="btn-continue-student" class="btn-auth-primary" style="margin-top: 10px;">
            Continuer
        </button>
    </form>
@endsection

@push('scripts')
<script>
    function selectSingleChip(el, groupName) {
        const parent = el.parentElement;
        parent.querySelectorAll('.chip').forEach(c => c.classList.remove('selected'));
        el.classList.add('selected');
    }

    function toggleMultiChip(el) {
        el.classList.toggle('selected');
    }

    function handleStudentOnboarding(e) {
        e.preventDefault();
        const btn = document.getElementById('btn-continue-student');
        btn.textContent = "Finalisation de ton profil...";
        btn.disabled = true;

        setTimeout(() => {
            window.location.href = '/auth/success?type=student';
        }, 350);
    }
</script>
@endpush
