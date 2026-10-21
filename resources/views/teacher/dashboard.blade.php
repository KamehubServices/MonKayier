@extends('layouts.app')

@section('title', 'Espace Enseignants · Tableau de bord')
@section('header-title', 'Espace Enseignants & Devoirs')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- Bannière Bienvenue Enseignant -->
    <div style="background: linear-gradient(135deg, var(--mk-night) 0%, var(--mk-navy) 100%); border-radius: 20px; padding: 26px; color: #ffffff; display: flex; justify-content: space-between; align-items: center; box-shadow: var(--mk-shadow-lg);">
        <div>
            <span class="mk-badge" style="background: rgba(200, 243, 74, 0.2); color: var(--mk-lime); border: 1px solid rgba(200, 243, 74, 0.4); margin-bottom: 8px;">
                Année Scolaire 2026-2027 · Togo
            </span>
            <h1 style="font-size: 24px; font-weight: 800; color: #ffffff; margin: 4px 0 6px 0;">
                Gestion Pédagogique & Évaluations de Classe
            </h1>
            <p style="font-size: 14px; opacity: 0.85; margin: 0; max-width: 600px;">
                Assignez des devoirs types BEPC/BAC, suivez les remises de copies et attribuez les notes officielles conformes au barème national.
            </p>
        </div>
        <div>
            <a href="/teacher/assignments" class="mk-btn mk-btn-lime">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Assigner un nouveau devoir
            </a>
        </div>
    </div>

    <!-- Statistiques rapides (KPIs) -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
        <div class="mk-card" style="padding: 20px; border-left: 4px solid var(--mk-indigo);">
            <div style="font-size: 12px; font-weight: 700; color: var(--mk-text-muted); text-transform: uppercase;">Classes en charge</div>
            <div style="font-size: 28px; font-weight: 900; color: var(--mk-night); margin-top: 4px;">{{ $classes->count() }}</div>
            <div style="font-size: 12px; color: var(--mk-indigo); margin-top: 4px; font-weight: 600;">Collège & Lycée</div>
        </div>
        <div class="mk-card" style="padding: 20px; border-left: 4px solid var(--mk-lime);">
            <div style="font-size: 12px; font-weight: 700; color: var(--mk-text-muted); text-transform: uppercase;">Devoirs programmés</div>
            <div style="font-size: 28px; font-weight: 900; color: var(--mk-night); margin-top: 4px;">{{ $assignments->count() }}</div>
            <div style="font-size: 12px; color: #059669; margin-top: 4px; font-weight: 600;">En cours de réalisation</div>
        </div>
        <div class="mk-card" style="padding: 20px; border-left: 4px solid #f59e0b;">
            <div style="font-size: 12px; font-weight: 700; color: var(--mk-text-muted); text-transform: uppercase;">Copies à évaluer</div>
            <div style="font-size: 28px; font-weight: 900; color: var(--mk-night); margin-top: 4px;">{{ $recentSubmissions->count() }}</div>
            <div style="font-size: 12px; color: #d97706; margin-top: 4px; font-weight: 600;">Attente de notation</div>
        </div>
    </div>

    <!-- Section Deux Colonnes : Devoirs récents & Mes Classes -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
        <!-- Devoirs récents -->
        <div class="mk-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h2 style="font-size: 18px; font-weight: 800; color: var(--mk-night); margin: 0;">
                    Devoirs Récents & Remises de Copies
                </h2>
                <a href="/teacher/assignments" class="mk-btn mk-btn-outline mk-btn-sm">Tous les devoirs</a>
            </div>

            @if($assignments->isNotEmpty())
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    @foreach($assignments as $assign)
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 14px; border: 1px solid var(--mk-border); border-radius: 12px; background: #ffffff;">
                            <div>
                                <div style="display: flex; gap: 8px; align-items: center;">
                                    <span class="mk-badge mk-badge-lavender">{{ $assign->schoolClass?->label ?? '3e A' }}</span>
                                    <span style="font-size: 12px; color: var(--mk-text-muted);">
                                        Matière : {{ $assign->assessment?->subject?->name ?? 'Mathématiques' }}
                                    </span>
                                </div>
                                <div style="font-size: 15px; font-weight: 700; color: var(--mk-night); margin-top: 4px;">
                                    {{ $assign->title }}
                                </div>
                                <div style="font-size: 12px; color: var(--mk-text-muted); margin-top: 2px;">
                                    Date limite : <strong>{{ $assign->due_at ? $assign->due_at->format('d/m/Y') : 'Non définie' }}</strong>
                                    · <strong>{{ $assign->submissions_count }}</strong> copie(s) remise(s)
                                </div>
                            </div>
                            <div>
                                <a href="/teacher/assignments/{{ $assign->id }}/submissions" class="mk-btn mk-btn-primary mk-btn-sm">
                                    Corriger les copies
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 30px; background: var(--mk-bg-subtle); border-radius: 12px;">
                    <p style="color: var(--mk-text-muted); margin: 0;">Aucun devoir actif pour le moment.</p>
                </div>
            @endif
        </div>

        <!-- Mes Classes -->
        <div class="mk-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h2 style="font-size: 18px; font-weight: 800; color: var(--mk-night); margin: 0;">
                    Mes Classes
                </h2>
                <a href="/teacher/classes" class="mk-btn mk-btn-outline mk-btn-sm">Gérer</a>
            </div>

            <div style="display: flex; flex-direction: column; gap: 10px;">
                @foreach($classes as $c)
                    <div style="padding: 12px 14px; border: 1px solid var(--mk-border); border-radius: 10px; background: var(--mk-bg-subtle);">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-weight: 800; color: var(--mk-night); font-size: 15px;">{{ $c->label }}</span>
                            <span class="mk-badge mk-badge-lime" style="font-size: 11px;">{{ $c->students_count }} élèves</span>
                        </div>
                        <div style="font-size: 12px; color: var(--mk-text-muted); margin-top: 3px;">
                            {{ $c->school?->name ?? 'Lycée de Tokoin, Lomé' }} · {{ $c->educationLevel?->name ?? '3e' }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</div>
@endsection
