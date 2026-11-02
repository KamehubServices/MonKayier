@extends('layouts.auth')

@section('title', 'Créons votre première classe — Onboarding Enseignant')

@section('content')
    <div class="step-badge">
        <div class="step-pills">
            <span class="step-pill active"></span>
            <span class="step-pill inactive"></span>
        </div>
        <span>Étape 1 sur 2</span>
    </div>

    <div>
        <h2 class="auth-title">Créons votre première classe.</h2>
        <p class="auth-subtitle">Vous pourrez en ajouter d’autres plus tard.</p>
    </div>

    <form onsubmit="handleTeacherOnboarding(event)" style="display: flex; flex-direction: column; gap: 18px;">
        <div class="form-group">
            <label class="form-label" for="class-name">Nom de la classe</label>
            <input type="text" id="class-name" class="form-input" placeholder="Ex. : Terminale D" value="Classe de 3e A" required autofocus>
        </div>

        <div class="form-group">
            <label class="form-label">Niveau</label>
            <div class="chips-container" id="teacher-levels">
                <span class="chip" onclick="selectLevelChip(this)">Seconde</span>
                <span class="chip" onclick="selectLevelChip(this)">Première</span>
                <span class="chip selected" onclick="selectLevelChip(this)">Terminale</span>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="subject-name">Matière enseignée</label>
            <input type="text" id="subject-name" class="form-input" placeholder="Ex. : Mathématiques" value="Mathématiques" required>
        </div>

        <button type="submit" id="btn-create-class" class="btn-auth-primary" style="margin-top: 6px;">
            Créer la classe
        </button>

        <a href="/auth/success?type=teacher" class="btn-auth-secondary" style="margin-top: 4px;">
            Passer cette étape
        </a>
    </form>
@endsection

@push('scripts')
<script>
    function selectLevelChip(el) {
        document.querySelectorAll('#teacher-levels .chip').forEach(c => c.classList.remove('selected'));
        el.classList.add('selected');
    }

    function handleTeacherOnboarding(e) {
        e.preventDefault();
        const btn = document.getElementById('btn-create-class');
        btn.textContent = "Création de la classe...";
        btn.disabled = true;

        setTimeout(() => {
            window.location.href = '/auth/success?type=teacher';
        }, 350);
    }
</script>
@endpush
