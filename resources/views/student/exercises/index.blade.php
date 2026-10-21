@extends('layouts.app')

@section('title', 'Exercices & Évaluations · BEPC Togo')
@section('header-title', 'Exercices & Évaluations')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- En-tête avec filtres de matières -->
    <div style="background: linear-gradient(135deg, var(--mk-night) 0%, var(--mk-navy) 100%); padding: 24px; border-radius: 18px; color: #ffffff; display: flex; justify-content: space-between; align-items: center; box-shadow: var(--mk-shadow-lg);">
        <div>
            <span class="mk-badge" style="background: rgba(200, 243, 74, 0.2); color: var(--mk-lime); border: 1px solid rgba(200, 243, 74, 0.4); margin-bottom: 8px;">
                Préparation BEPC 2027 · Togo
            </span>
            <h1 style="font-size: 24px; font-weight: 800; margin: 6px 0 4px 0; color: #ffffff;">
                Entraînements & Auto-Évaluations Conformes
            </h1>
            <p style="font-size: 14px; opacity: 0.85; margin: 0; max-width: 600px;">
                Quiz formatifs, devoirs types et exercices interactifs conformes aux directives pédagogiques du MEPST Togo.
            </p>
        </div>
        <div style="text-align: right;">
            <a href="/student/revisions" class="mk-btn mk-btn-lime">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                Mon Plan de Révision
            </a>
        </div>
    </div>

    <!-- Filtres rapides par matière -->
    <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
        <a href="/student/exercises" class="mk-btn {{ empty($selectedSubject) ? 'mk-btn-primary' : 'mk-btn-outline' }} mk-btn-sm">
            Toutes les matières
        </a>
        @foreach($subjects as $sub)
            <a href="/student/exercises?subject={{ $sub->code }}"
               class="mk-btn {{ $selectedSubject === $sub->code ? 'mk-btn-primary' : 'mk-btn-outline' }} mk-btn-sm">
                {{ $sub->name }}
            </a>
        @endforeach
    </div>

    <!-- Grille des Évaluations -->
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 20px;">
        @forelse($assessments as $item)
            <div class="mk-card" style="display: flex; flex-direction: column; justify-content: space-between; border-top: 4px solid var(--mk-indigo); transition: transform 0.2s ease, box-shadow 0.2s ease;">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                        <span class="mk-badge mk-badge-lavender">
                            {{ $item->subject?->name ?? 'Général' }}
                        </span>
                        <span style="font-size: 12px; font-weight: 700; color: var(--mk-text-muted); display: flex; align-items: center; gap: 4px;">
                            ⏱ {{ round(($item->duration_seconds ?? 900) / 60) }} min
                        </span>
                    </div>

                    <h3 style="font-size: 17px; font-weight: 800; color: var(--mk-night); margin-bottom: 8px; line-height: 1.35;">
                        {{ $item->title }}
                    </h3>

                    <p style="font-size: 13px; color: var(--mk-text-muted); margin-bottom: 16px; line-height: 1.4;">
                        {{ $item->resource ? 'Rattaché au cours : ' . $item->resource->title : 'Exercice d’entraînement ciblé sur les annales de fin de cycle.' }}
                    </p>

                    <div style="display: flex; gap: 12px; font-size: 12px; color: var(--mk-text-muted); margin-bottom: 18px; padding: 10px; background: var(--mk-bg-subtle); border-radius: 10px;">
                        <div><strong>{{ $item->questions->count() }}</strong> questions</div>
                        <div>·</div>
                        <div>Total : <strong>{{ $item->totalPoints() }} pts</strong></div>
                        <div>·</div>
                        <div>Type : <span style="text-transform: capitalize;">{{ $item->assessment_type }}</span></div>
                    </div>
                </div>

                <div style="display: flex; gap: 10px;">
                    <a href="/student/exercises/{{ $item->id }}" class="mk-btn mk-btn-primary" style="flex: 1; justify-content: center;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Démarrer le quiz
                    </a>
                    @if($item->resource_id)
                        <a href="/student/reader/{{ $item->resource_id }}" class="mk-btn mk-btn-outline mk-btn-sm" title="Revoir le cours associé">
                            📖
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="mk-card" style="grid-column: 1 / -1; text-align: center; padding: 48px;">
                <div style="font-size: 40px; margin-bottom: 12px;">📝</div>
                <h3 style="font-size: 18px; font-weight: 700; color: var(--mk-night);">Aucune évaluation trouvée</h3>
                <p style="font-size: 14px; color: var(--mk-text-muted); margin-top: 6px;">
                    Aucun quiz publié ne correspond au filtre sélectionné pour le moment.
                </p>
                <a href="/student/exercises" class="mk-btn mk-btn-outline" style="margin-top: 14px;">
                    Réinitialiser le filtre
                </a>
            </div>
        @endforelse
    </div>

</div>
@endsection
