@extends('layouts.app')

@section('title', 'Espace Parents & Supervision · BEPC Togo')
@section('header-title', 'Espace Parents & Tuteurs')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- TOP SECTION : Hero Suivi Enfant & Compte à Rebours BEPC -->
    <div style="background: linear-gradient(135deg, var(--mk-night) 0%, var(--mk-navy) 100%); border-radius: 20px; padding: 28px; color: #ffffff; display: flex; justify-content: space-between; align-items: center; box-shadow: var(--mk-shadow-lg);">
        <div>
            <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 6px;">
                <span class="mk-badge" style="background: rgba(200, 243, 74, 0.2); color: var(--mk-lime); border: 1px solid rgba(200, 243, 74, 0.4);">
                    {{ $report['class'] }}
                </span>
                <span class="mk-badge" style="background: rgba(255, 255, 255, 0.15); color: #ffffff;">
                    {{ $report['school'] }}
                </span>
            </div>
            <h1 style="font-size: 24px; font-weight: 800; color: #ffffff; margin: 4px 0 6px 0;">
                Suivi Pédagogique de {{ $report['student_name'] }}
            </h1>
            <p style="font-size: 14px; opacity: 0.85; margin: 0; max-width: 600px;">
                Synthèse officielle de la scolarité certifiée par les enseignants. Zéro intrusion dans les brouillons privés de l'élève.
            </p>
        </div>

        <!-- Compte à Rebours Examen National BEPC 2027 -->
        <div style="background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 16px; padding: 18px 24px; text-align: center; min-width: 170px;">
            <div style="font-size: 11px; text-transform: uppercase; font-weight: 800; letter-spacing: 0.5px; opacity: 0.85;">
                Épreuve du BEPC 2027
            </div>
            <div style="font-size: 26px; font-weight: 900; color: var(--mk-lime); margin: 4px 0;">
                J - {{ $report['days_remaining'] }}
            </div>
            <div style="font-size: 12px; opacity: 0.8;">
                08 Juin 2027 · Togo
            </div>
        </div>
    </div>

    <!-- Indicateurs Clés Scolaires (KPIs) -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
        <div class="mk-card" style="padding: 20px; border-left: 4px solid var(--mk-lime);">
            <div style="font-size: 12px; font-weight: 700; color: var(--mk-text-muted); text-transform: uppercase;">Moyenne Générale</div>
            <div style="font-size: 32px; font-weight: 900; color: var(--mk-night); margin-top: 4px;">
                {{ $report['global_average'] }} <span style="font-size: 18px; font-weight: 600; color: var(--mk-text-muted);">/ 20</span>
            </div>
            <div style="font-size: 12px; color: #059669; margin-top: 4px; font-weight: 700;">
                ✓ Mention Bien
            </div>
        </div>

        <div class="mk-card" style="padding: 20px; border-left: 4px solid var(--mk-indigo);">
            <div style="font-size: 12px; font-weight: 700; color: var(--mk-text-muted); text-transform: uppercase;">Évaluations Réalisées</div>
            <div style="font-size: 32px; font-weight: 900; color: var(--mk-night); margin-top: 4px;">
                {{ $report['completed_assessments'] }}
            </div>
            <div style="font-size: 12px; color: var(--mk-indigo); margin-top: 4px; font-weight: 600;">
                Notions officielles validées
            </div>
        </div>

        <div class="mk-card" style="padding: 20px; border-left: 4px solid #10b981;">
            <div style="font-size: 12px; font-weight: 700; color: var(--mk-text-muted); text-transform: uppercase;">Assiduité & Devoirs</div>
            <div style="font-size: 32px; font-weight: 900; color: var(--mk-night); margin-top: 4px;">
                100%
            </div>
            <div style="font-size: 12px; color: #059669; margin-top: 4px; font-weight: 600;">
                Tous les devoirs rendus dans les temps
            </div>
        </div>
    </div>

    <!-- Section Principale : Dernières Notes & Échéances à Venir -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">

        <!-- Dernières Notes Certifiées avec Appréciations Enseignants -->
        <div class="mk-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h2 style="font-size: 18px; font-weight: 800; color: var(--mk-night); margin: 0;">
                    Dernières Évaluations & Notes des Enseignants
                </h2>
                <span class="mk-badge mk-badge-official">Conforme MEPST</span>
            </div>

            <div style="display: flex; flex-direction: column; gap: 14px;">
                @foreach($report['grades'] as $g)
                    <div style="padding: 16px; border: 1px solid var(--mk-border); border-radius: 12px; background: #ffffff;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <div>
                                <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 4px;">
                                    <span class="mk-badge mk-badge-lavender">{{ $g['subject'] }}</span>
                                    <span style="font-size: 12px; color: var(--mk-text-muted);">Le {{ $g['date'] }}</span>
                                </div>
                                <div style="font-size: 15px; font-weight: 800; color: var(--mk-night);">
                                    {{ $g['title'] }}
                                </div>
                            </div>

                            <div style="text-align: right;">
                                <div style="font-size: 22px; font-weight: 900; color: {{ $g['score'] >= 14 ? 'var(--mk-indigo)' : '#d97706' }};">
                                    {{ $g['score'] }} <span style="font-size: 14px; font-weight: 600; color: var(--mk-text-muted);">/ 20</span>
                                </div>
                            </div>
                        </div>

                        @if(!empty($g['feedback']))
                            <div style="margin-top: 10px; padding: 10px 12px; background: var(--mk-bg-subtle); border-radius: 8px; font-size: 13px; color: var(--mk-text);">
                                <strong>Appréciation de l'enseignant :</strong> {{ $g['feedback'] }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Colonne de Droite : Échéances + Formulaire de Liaison Code -->
        <div style="display: flex; flex-direction: column; gap: 20px;">

            <!-- Prochains devoirs à rendre -->
            <div class="mk-card">
                <h3 style="font-size: 16px; font-weight: 800; color: var(--mk-night); margin: 0 0 12px 0;">
                    📅 Échéances Scolaires à Venir
                </h3>
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    @foreach($report['upcoming_deadlines'] as $dl)
                        <div style="padding: 12px; border-radius: 10px; background: var(--mk-bg-subtle); border: 1px solid var(--mk-border);">
                            <div style="font-weight: 700; font-size: 13.5px; color: var(--mk-night);">
                                {{ $dl['title'] }}
                            </div>
                            <div style="font-size: 12px; color: #d97706; font-weight: 700; margin-top: 2px;">
                                ⏱ À rendre : {{ $dl['due'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Carte : Relier un autre enfant via code à 6 caractères -->
            <div class="mk-card" style="border-top: 4px solid var(--mk-indigo);">
                <h3 style="font-size: 16px; font-weight: 800; color: var(--mk-night); margin: 0 0 6px 0;">
                    + Relier un Autre Élève
                </h3>
                <p style="font-size: 12.5px; color: var(--mk-text-muted); margin: 0 0 12px 0;">
                    Entrez le code à 6 caractères fourni par l'élève ou son établissement.
                </p>

                <form method="POST" action="/parent/link" style="display: flex; gap: 8px;">
                    @csrf
                    <input type="text" name="code" placeholder="Ex: TG782X" maxlength="10" required style="flex: 1; padding: 10px 12px; border: 1.5px solid var(--mk-border); border-radius: 8px; font-size: 14px; text-transform: uppercase; font-weight: 700; letter-spacing: 1px;">
                    <button type="submit" class="mk-btn mk-btn-primary mk-btn-sm">
                        Valider
                    </button>
                </form>
            </div>

        </div>

    </div>

</div>
@endsection
