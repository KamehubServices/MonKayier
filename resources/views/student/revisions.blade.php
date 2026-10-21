@extends('layouts.app')

@section('title', 'Plan de Révision Adaptatif · BEPC Togo')
@section('header-title', 'Mon Plan de Révision Adaptatif')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- Carte Objectif Examen National BEPC 2027 -->
    <div style="background: linear-gradient(135deg, var(--mk-night) 0%, var(--mk-navy) 100%); border-radius: 20px; padding: 28px; color: #ffffff; display: flex; justify-content: space-between; align-items: center; box-shadow: var(--mk-shadow-lg);">
        <div>
            <span class="mk-badge" style="background: rgba(200, 243, 74, 0.2); color: var(--mk-lime); border: 1px solid rgba(200, 243, 74, 0.4); margin-bottom: 8px;">
                Objectif National : BEPC 2027 (Togo)
            </span>
            <h1 style="font-size: 24px; font-weight: 800; color: #ffffff; margin: 4px 0 6px 0;">
                {{ $plan?->title ?? 'Plan de Révision Personnalisé · BEPC 2027' }}
            </h1>
            <p style="font-size: 14px; opacity: 0.85; margin: 0; max-width: 650px;">
                Ce plan réorganise dynamiquement vos séances de révision en fonction de vos scores aux évaluations formatives.
            </p>
        </div>

        <div style="background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 16px; padding: 18px 24px; text-align: center; min-width: 140px;">
            <div style="font-size: 11px; text-transform: uppercase; font-weight: 800; letter-spacing: 0.5px; opacity: 0.8;">
                Échéance Examen
            </div>
            <div style="font-size: 20px; font-weight: 900; color: var(--mk-lime); margin-top: 4px;">
                08 Juin 2027
            </div>
            <div style="font-size: 12px; opacity: 0.8; margin-top: 2px;">
                Session Officielle MEPST
            </div>
        </div>
    </div>

    <!-- Tâches de révision prioritaires -->
    <div class="mk-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
            <div>
                <h2 style="font-size: 18px; font-weight: 800; color: var(--mk-night); margin: 0;">
                    Tâches & Notions Recommandées
                </h2>
                <p style="font-size: 13px; color: var(--mk-text-muted); margin: 4px 0 0 0;">
                    Classées par niveau de priorité pédagogique
                </p>
            </div>
            <span class="mk-badge mk-badge-lavender">
                {{ $plan ? $plan->pendingTasks->count() : 0 }} tâche(s) en attente
            </span>
        </div>

        @if($plan && $plan->tasks->isNotEmpty())
            <div style="display: flex; flex-direction: column; gap: 12px;">
                @foreach($plan->tasks as $task)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px; border: 1px solid var(--mk-border); border-radius: 14px; background: #ffffff; transition: all 0.2s ease;">
                        <div style="display: flex; align-items: center; gap: 16px;">
                            <!-- Checkbox / Statut -->
                            <form method="POST" action="/api/v1/me/revision-plan/tasks/{{ $task->id }}/complete" style="margin: 0;">
                                @csrf
                                <button type="button"
                                        class="mk-btn mk-btn-outline mk-btn-sm"
                                        style="width: 32px; height: 32px; padding: 0; border-radius: 50%; display: flex; align-items: center; justify-content: center; {{ $task->status === 'completed' ? 'background: #10b981; color: #ffffff; border-color: #10b981;' : '' }}"
                                        title="{{ $task->status === 'completed' ? 'Tâche terminée' : 'Marquer comme terminée' }}">
                                    @if($task->status === 'completed')
                                        ✓
                                    @else
                                        ○
                                    @endif
                                </button>
                            </form>

                            <div>
                                <div style="display: flex; gap: 8px; align-items: center;">
                                    <span class="mk-badge {{ $task->priority === 1 ? 'mk-badge-lime' : 'mk-badge-lavender' }}">
                                        Priorité {{ $task->priority === 1 ? '1 · Haute' : '2 · Normale' }}
                                    </span>
                                    <span style="font-size: 12px; color: var(--mk-text-muted);">
                                        Échéance : {{ $task->due_at ? $task->due_at->format('d/m/Y') : 'Sous 48h' }}
                                    </span>
                                </div>

                                <div style="font-weight: 700; color: var(--mk-night); font-size: 15px; margin-top: 4px;">
                                    {{ $task->resource?->title ?? $task->objective?->description ?? 'Révision générale de géométrie' }}
                                </div>
                                <div style="font-size: 12px; color: var(--mk-text-muted); margin-top: 2px;">
                                    Matière : <strong>{{ $task->resource?->subject?->name ?? 'Mathématiques' }}</strong> · Origine : Auto-remédiation suite à évaluation
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; gap: 10px;">
                            @if($task->resource_id)
                                <a href="/student/reader/{{ $task->resource_id }}" class="mk-btn mk-btn-lavender mk-btn-sm">
                                    📖 Ouvrir la fiche de cours
                                </a>
                            @endif
                            <a href="/student/exercises" class="mk-btn mk-btn-outline mk-btn-sm">
                                ✍️ S’exercer à nouveau
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Plan vide ou à jour -->
            <div style="text-align: center; padding: 40px; background: var(--mk-bg-subtle); border-radius: 14px;">
                <div style="font-size: 36px; margin-bottom: 10px;">🎯</div>
                <h3 style="font-size: 16px; font-weight: 700; color: var(--mk-night); margin-bottom: 4px;">
                    Aucune tâche urgente en attente
                </h3>
                <p style="font-size: 13.5px; color: var(--mk-text-muted); margin: 0 auto; max-width: 480px;">
                    Toutes vos notions prioritaires sont validées. Poursuivez votre préparation en réalisant un nouveau quiz formatif.
                </p>
                <div style="margin-top: 16px;">
                    <a href="/student/exercises" class="mk-btn mk-btn-primary mk-btn-sm">
                        Découvrir les exercices du BEPC
                    </a>
                </div>
            </div>
        @endif
    </div>

</div>
@endsection
