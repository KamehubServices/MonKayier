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

        <!-- 1b. Ta série -->
        <div class="form-group">
            <label class="form-label">Ta série</label>
            <div class="chips-container" id="series-group">
                <span class="chip selected" onclick="selectSingleChip(this, 'serie')">Série D</span>
                <span class="chip" onclick="selectSingleChip(this, 'serie')">Série C</span>
                <span class="chip" onclick="selectSingleChip(this, 'serie')">Série A4</span>
            </div>
        </div>

        <!-- 2. Tes matières -->
        <div class="form-group">
            <label class="form-label">Tes matières prioritaires</label>
            <div class="chips-container" id="subjects-group">
                <span class="chip selected" onclick="toggleMultiChip(this)">Maths</span>
                <span class="chip selected" onclick="toggleMultiChip(this)">Physique-Chimie</span>
                <span class="chip selected" onclick="toggleMultiChip(this)">SVT</span>
                <span class="chip" onclick="toggleMultiChip(this)">Français</span>
                <span class="chip" onclick="toggleMultiChip(this)">Philosophie</span>
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
            Continuer vers le Test de niveau
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

    async function handleStudentOnboarding(e) {
        e.preventDefault();
        const btn = document.getElementById('btn-continue-student');
        btn.textContent = "Finalisation de ton profil...";
        btn.disabled = true;

        const levelEl = document.querySelector('#levels-group .chip.selected');
        const serieEl = document.querySelector('#series-group .chip.selected');
        const objEl = document.querySelector('#objectives-group .chip.selected');
        const subjEls = document.querySelectorAll('#subjects-group .chip.selected');

        const level = levelEl ? levelEl.textContent.trim() : 'Terminale';
        const serieRaw = serieEl ? serieEl.textContent.trim().replace(/^Série\s+/i, '') : 'D';
        const objective = objEl ? objEl.textContent.trim() : 'Préparer le BAC';
        const subjects = Array.from(subjEls).map(el => el.textContent.trim());

        try {
            const res = await fetch('/onboarding/student', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    level: level,
                    serie: serieRaw,
                    objective: objective,
                    subjects: subjects
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
        window.location.href = '/auth/success?type=student';
    }
</script>
@endpush
