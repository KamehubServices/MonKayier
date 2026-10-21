@extends('layouts.app')

@section('title', 'Gestion des Devoirs · Enseignant')
@section('header-title', 'Devoirs & Évaluations de Classe')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- Formulaire d'assignation d'un nouveau devoir -->
    <div class="mk-card" style="border-top: 4px solid var(--mk-lime);">
        <h2 style="font-size: 18px; font-weight: 800; color: var(--mk-night); margin: 0 0 16px 0;">
            + Programmer un Devoir pour une Classe
        </h2>

        <form method="POST" action="/api/v1/teacher/assignments" id="assignment-form" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; align-items: flex-end;">
            @csrf
            <div>
                <label style="display: block; font-size: 13px; font-weight: 700; color: var(--mk-night); margin-bottom: 6px;">
                    Classe ciblée
                </label>
                <select name="class_id" required style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--mk-border); border-radius: 10px; font-size: 14px;">
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}">{{ $c->label }} ({{ $c->educationLevel?->name }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label style="display: block; font-size: 13px; font-weight: 700; color: var(--mk-night); margin-bottom: 6px;">
                    Sujet / Évaluation certifiée
                </label>
                <select name="assessment_id" required style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--mk-border); border-radius: 10px; font-size: 14px;">
                    @foreach($assessments as $ass)
                        <option value="{{ $ass->id }}">{{ $ass->title }} ({{ $ass->subject?->name }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label style="display: block; font-size: 13px; font-weight: 700; color: var(--mk-night); margin-bottom: 6px;">
                    Titre du devoir
                </label>
                <input type="text" name="title" required placeholder="Ex: Devoir de maison n°1 · Thalès" style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--mk-border); border-radius: 10px; font-size: 14px;">
            </div>

            <div>
                <label style="display: block; font-size: 13px; font-weight: 700; color: var(--mk-night); margin-bottom: 6px;">
                    Date limite de remise
                </label>
                <input type="date" name="due_at" value="{{ now()->addDays(7)->format('Y-m-d') }}" style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--mk-border); border-radius: 10px; font-size: 14px;">
            </div>

            <div>
                <button type="submit" class="mk-btn mk-btn-primary" style="width: 100%; justify-content: center;">
                    Publier le devoir
                </button>
            </div>
        </form>
    </div>

    <!-- Liste des Devoirs Actifs -->
    <div class="mk-card">
        <h2 style="font-size: 18px; font-weight: 800; color: var(--mk-night); margin: 0 0 16px 0;">
            Historique des Devoirs Programmés
        </h2>

        @if($assignments->isNotEmpty())
            <div style="display: flex; flex-direction: column; gap: 12px;">
                @foreach($assignments as $assign)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px; border: 1px solid var(--mk-border); border-radius: 12px; background: #ffffff;">
                        <div>
                            <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 4px;">
                                <span class="mk-badge mk-badge-lavender">{{ $assign->schoolClass?->label }}</span>
                                <span class="mk-badge {{ $assign->isPastDue() ? 'mk-badge-danger' : 'mk-badge-lime' }}">
                                    {{ $assign->isPastDue() ? 'Échéance dépassée' : 'En cours' }}
                                </span>
                            </div>
                            <h3 style="font-size: 16px; font-weight: 800; color: var(--mk-night); margin: 0;">
                                {{ $assign->title }}
                            </h3>
                            <div style="font-size: 12px; color: var(--mk-text-muted); margin-top: 4px;">
                                Matière : <strong>{{ $assign->assessment?->subject?->name }}</strong>
                                · Date limite : <strong>{{ $assign->due_at ? $assign->due_at->format('d/m/Y') : 'N/A' }}</strong>
                                · <strong>{{ $assign->submissions_count }}</strong> copie(s) remise(s)
                            </div>
                        </div>

                        <div style="display: flex; gap: 10px;">
                            <a href="/teacher/assignments/{{ $assign->id }}/submissions" class="mk-btn mk-btn-primary mk-btn-sm">
                                📝 Consulter et Noter les copies ({{ $assign->submissions_count }})
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 36px; background: var(--mk-bg-subtle); border-radius: 12px;">
                <p style="color: var(--mk-text-muted); margin: 0;">Aucun devoir créé pour le moment.</p>
            </div>
        @endif
    </div>

</div>
@endsection
