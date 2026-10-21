@extends('layouts.app')

@section('title', 'Résultats de l’Évaluation')
@section('header-title', 'Résultats & Corrigé Pédagogique')

@section('content')
<div style="max-width: 860px; margin: 0 auto; display: flex; flex-direction: column; gap: 24px;">

    <!-- Carte Récapitulative du Score -->
    <div style="background: linear-gradient(135deg, var(--mk-night) 0%, var(--mk-navy) 100%); border-radius: 20px; padding: 28px; color: #ffffff; display: flex; justify-content: space-between; align-items: center; box-shadow: var(--mk-shadow-lg);">
        <div>
            <span class="mk-badge" style="background: rgba(255, 255, 255, 0.15); color: #ffffff; margin-bottom: 8px;">
                {{ $attempt->assessment->subject?->name ?? 'Mathématiques' }} · Évaluation Terminée
            </span>
            <h1 style="font-size: 24px; font-weight: 800; color: #ffffff; margin: 4px 0 8px 0;">
                {{ $attempt->assessment->title }}
            </h1>
            <p style="font-size: 14px; opacity: 0.85; margin: 0;">
                Soumis le {{ $attempt->submitted_at ? $attempt->submitted_at->format('d/m/Y à H:i') : now()->format('d/m/Y à H:i') }}
            </p>
        </div>

        <!-- Score Badge & Pourcentage -->
        <div style="background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 16px; padding: 18px 24px; text-align: center; min-width: 150px;">
            <div style="font-size: 12px; text-transform: uppercase; font-weight: 800; letter-spacing: 0.5px; color: var(--mk-lime);">
                Note Obtenue
            </div>
            <div style="font-size: 32px; font-weight: 900; color: #ffffff; margin: 4px 0;">
                {{ (float) $attempt->score }} <span style="font-size: 18px; font-weight: 600; opacity: 0.7;">/ {{ (float) $attempt->max_score }}</span>
            </div>
            <div style="font-size: 13px; font-weight: 700; color: {{ $attempt->scorePercentage() >= 70 ? 'var(--mk-lime)' : '#fbbf24' }};">
                {{ round($attempt->scorePercentage()) }}% de réussite
            </div>
        </div>
    </div>

    <!-- Bannière de remédiation adaptative -->
    @if($attempt->scorePercentage() < 70)
        <div class="mk-card" style="border-left: 5px solid #f59e0b; background: #fffbeb; padding: 18px 22px;">
            <div style="display: flex; gap: 14px; align-items: flex-start;">
                <div style="font-size: 24px;">💡</div>
                <div>
                    <h3 style="font-size: 16px; font-weight: 800; color: #92400e; margin: 0 0 4px 0;">
                        Recommandation adaptative générée automatiquement
                    </h3>
                    <p style="font-size: 13.5px; color: #78350f; margin: 0; line-height: 1.45;">
                        Votre score est inférieur au seuil de maîtrise de 70%. Une fiche de révision ciblée sur cette notion a été programmée dans votre
                        <strong>Plan de Révision Personnel</strong> sous 48 heures.
                    </p>
                    <div style="margin-top: 10px;">
                        <a href="/student/revisions" class="mk-btn mk-btn-sm" style="background: #92400e; color: #ffffff;">
                            Consulter mon plan de révision →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="mk-card" style="border-left: 5px solid #10b981; background: #ecfdf5; padding: 18px 22px;">
            <div style="display: flex; gap: 14px; align-items: center;">
                <div style="font-size: 24px;">🎉</div>
                <div>
                    <h3 style="font-size: 16px; font-weight: 800; color: #065f46; margin: 0 0 4px 0;">
                        Félicitations ! Notion validée
                    </h3>
                    <p style="font-size: 13.5px; color: #047857; margin: 0;">
                        Vous maîtrisez cette compétence du programme officiel togolais de 3e.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Détail des Réponses & Corrigé commenté -->
    <div style="display: flex; flex-direction: column; gap: 16px;">
        <h2 style="font-size: 18px; font-weight: 800; color: var(--mk-night); margin-top: 8px;">
            Corrigé Détaillé Question par Question
        </h2>

        @foreach($attempt->answers as $index => $ans)
            @php
                $q = $ans->question;
                $isFullPoints = (float) $ans->awarded_points >= (float) ($q?->points ?? 1);
            @endphp
            <div class="mk-card" style="border-left: 4px solid {{ $isFullPoints ? '#10b981' : '#ef4444' }};">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <span class="mk-badge {{ $isFullPoints ? 'mk-badge-lime' : 'mk-badge-lavender' }}">
                        Question {{ $index + 1 }} · {{ $isFullPoints ? 'Validé' : 'À revoir' }}
                    </span>
                    <span style="font-size: 13px; font-weight: 800; color: {{ $isFullPoints ? '#059669' : '#dc2626' }};">
                        {{ (float) $ans->awarded_points }} / {{ (float) ($q?->points ?? 0) }} pt{{ ($q?->points ?? 0) > 1 ? 's' : '' }}
                    </span>
                </div>

                <div style="font-size: 15px; font-weight: 700; color: var(--mk-night); margin-bottom: 12px; line-height: 1.45;">
                    {{ $q?->prompt }}
                </div>

                <!-- Explication pédagogique -->
                <div style="background: var(--mk-bg-subtle); padding: 14px 16px; border-radius: 10px; font-size: 13.5px; line-height: 1.45; color: var(--mk-text);">
                    <div style="font-weight: 700; color: var(--mk-night); margin-bottom: 4px;">
                        Explication & Commentaire du Correcteur :
                    </div>
                    {{ $ans->feedback ?: $q?->explanation ?: 'Réponse enregistrée.' }}
                </div>
            </div>
        @endforeach
    </div>

    <!-- Actions suivantes -->
    <div style="display: flex; gap: 14px; margin-top: 12px;">
        <a href="/student/exercises" class="mk-btn mk-btn-outline" style="flex: 1; justify-content: center;">
            ← Retour aux exercices
        </a>
        @if($attempt->assessment->resource_id)
            <a href="/student/reader/{{ $attempt->assessment->resource_id }}" class="mk-btn mk-btn-lavender" style="flex: 1; justify-content: center;">
                📖 Relire le cours officiel
            </a>
        @endif
        <a href="/student/revisions" class="mk-btn mk-btn-primary" style="flex: 1; justify-content: center;">
            Mon Plan de Révision →
        </a>
    </div>

</div>
@endsection
