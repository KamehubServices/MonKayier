@extends('layouts.app')

@section('title', 'Suivi de Progression des Compétences')
@section('header-title', 'Progression & Maîtrise Scolaire')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- En-tête -->
    <div style="background: linear-gradient(135deg, var(--mk-night) 0%, var(--mk-navy) 100%); border-radius: 20px; padding: 26px; color: #ffffff; display: flex; justify-content: space-between; align-items: center; box-shadow: var(--mk-shadow-lg);">
        <div>
            <span class="mk-badge" style="background: rgba(200, 243, 74, 0.2); color: var(--mk-lime); border: 1px solid rgba(200, 243, 74, 0.4); margin-bottom: 8px;">
                Référentiel National · Togo
            </span>
            <h1 style="font-size: 24px; font-weight: 800; color: #ffffff; margin: 4px 0 6px 0;">
                Maîtrise des Compétences du Cycle 1 (Collège - 3e)
            </h1>
            <p style="font-size: 14px; opacity: 0.85; margin: 0; max-width: 600px;">
                Suivi objectif de l'assimilation des notions clés du programme togolais de Troisième.
            </p>
        </div>
        <div style="text-align: right;">
            <a href="/student/exercises" class="mk-btn mk-btn-lime">
                S'évaluer sur une compétence
            </a>
        </div>
    </div>

    <!-- Grille des Compétences -->
    <div class="mk-card">
        <h2 style="font-size: 18px; font-weight: 800; color: var(--mk-night); margin-bottom: 16px;">
            Objectifs Pédagogiques Clés du BEPC
        </h2>

        <div style="display: flex; flex-direction: column; gap: 14px;">
            @php
                $mockCompetencies = [
                    ['code' => 'OBJ-THALES-1', 'title' => 'Calcul de longueurs avec le théorème direct de Thalès', 'subject' => 'Mathématiques', 'mastery' => 85, 'status' => 'Acquis'],
                    ['code' => 'OBJ-THALES-2', 'title' => 'Démonstration du parallélisme avec la réciproque de Thalès', 'subject' => 'Mathématiques', 'mastery' => 70, 'status' => 'En cours d\'acquisition'],
                    ['code' => 'OBJ-PGCD-1', 'title' => 'Calcul du PGCD par l’algorithme d’Euclide', 'subject' => 'Mathématiques', 'mastery' => 90, 'status' => 'Acquis'],
                    ['code' => 'OBJ-PGCD-2', 'title' => 'Fractions irréductibles et simplification rationnelle', 'subject' => 'Mathématiques', 'mastery' => 80, 'status' => 'Acquis'],
                    ['code' => 'OBJ-PCT-OHM', 'title' => 'Application de la loi d’Ohm U = R × I et calculs d’énergie', 'subject' => 'PCT', 'mastery' => 65, 'status' => 'À renforcer'],
                    ['code' => 'OBJ-PCT-PH', 'title' => 'Identification des solutions acides, basiques et neutralisation', 'subject' => 'PCT', 'mastery' => 75, 'status' => 'Acquis'],
                ];
            @endphp

            @foreach($mockCompetencies as $comp)
                <div style="padding: 16px; border: 1px solid var(--mk-border); border-radius: 12px; display: flex; justify-content: space-between; align-items: center; background: #ffffff;">
                    <div style="flex: 1; margin-right: 20px;">
                        <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 4px;">
                            <span class="mk-badge mk-badge-lavender">{{ $comp['subject'] }}</span>
                            <span style="font-size: 12px; font-weight: 700; color: var(--mk-text-muted);">{{ $comp['code'] }}</span>
                        </div>
                        <div style="font-size: 15px; font-weight: 700; color: var(--mk-night);">
                            {{ $comp['title'] }}
                        </div>

                        <!-- Barre de progression -->
                        <div style="margin-top: 10px; max-width: 380px;">
                            <div class="mk-progress-bar-bg" style="height: 7px;">
                                <div class="mk-progress-bar-fill" style="width: {{ $comp['mastery'] }}%; background: {{ $comp['mastery'] >= 75 ? 'var(--mk-lime)' : ($comp['mastery'] >= 65 ? 'var(--mk-indigo)' : '#f59e0b') }};"></div>
                            </div>
                        </div>
                    </div>

                    <div style="text-align: right; min-width: 140px;">
                        <div style="font-size: 18px; font-weight: 800; color: var(--mk-night);">
                            {{ $comp['mastery'] }}%
                        </div>
                        <div style="font-size: 12px; font-weight: 600; color: {{ $comp['mastery'] >= 75 ? '#059669' : ($comp['mastery'] >= 65 ? 'var(--mk-indigo)' : '#d97706') }};">
                            {{ $comp['status'] }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
