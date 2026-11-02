@extends('layouts.student')

@section('title', 'Examens nationaux — MonKayier')

@section('content')
@php
    $shortSubjects = [
        'Éducation Civique et Morale' => 'ECM',
        'Physique-Chimie et Technologie' => 'PCT',
        'Sciences de la Vie et de la Terre' => 'SVT',
        'Histoire et Géographie' => 'Hist-Géo',
        'Philosophie' => 'Philo',
        'Mathématiques' => 'Maths',
        'Français' => 'Français',
        'Anglais' => 'Anglais',
        'Allemand' => 'Allemand',
        'Espagnol' => 'Espagnol',
        'Informatique' => 'Info',
        'Technologie' => 'Techno',
        'Éducation Ménagère' => 'Éd. Ménagère',
    ];

    $subjectThemes = [
        'Mathématiques' => [
            'color' => '#2563EB',
            'bg' => '#EFF6FF',
            'glow' => 'rgba(37, 99, 235, 0.35)',
            'icon' => '<svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M4 21V4l16 17H4z"/><path d="M7 17h3"/><path d="M7 13h5"/></svg>',
        ],
        'Physique-Chimie et Technologie' => [
            'color' => '#0284C7',
            'bg' => '#F0F9FF',
            'glow' => 'rgba(2, 132, 199, 0.35)',
            'icon' => '<svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="#0284C7" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><ellipse cx="12" cy="12" rx="9" ry="4" transform="rotate(30 12 12)"/><ellipse cx="12" cy="12" rx="9" ry="4" transform="rotate(-30 12 12)"/></svg>',
        ],
        'Sciences de la Vie et de la Terre' => [
            'color' => '#059669',
            'bg' => '#ECFDF5',
            'glow' => 'rgba(5, 150, 105, 0.35)',
            'icon' => '<svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>',
        ],
        'Français' => [
            'color' => '#E11D48',
            'bg' => '#FFF1F2',
            'glow' => 'rgba(225, 29, 72, 0.35)',
            'icon' => '<svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="#E11D48" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/><path d="M8 7h8"/><path d="M8 11h6"/></svg>',
        ],
        'Philosophie' => [
            'color' => '#7C3AED',
            'bg' => '#F5F3FF',
            'glow' => 'rgba(124, 58, 237, 0.35)',
            'icon' => '<svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="#7C3AED" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18h6"/><path d="M10 22h4"/><path d="M12 2a7 7 0 0 0-7 7c0 2.38 1.19 4.47 3 5.74V17a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-2.26c1.81-1.27 3-3.36 3-5.74a7 7 0 0 0-7-7z"/></svg>',
        ],
        'Anglais' => [
            'color' => '#4F46E5',
            'bg' => '#EEF2FF',
            'glow' => 'rgba(79, 70, 229, 0.35)',
            'icon' => '<svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="#4F46E5" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>',
        ],
        'Histoire et Géographie' => [
            'color' => '#D97706',
            'bg' => '#FFFBEB',
            'glow' => 'rgba(217, 119, 6, 0.35)',
            'icon' => '<svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="#D97706" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/><line x1="8" y1="2" x2="8" y2="18"/><line x1="16" y1="6" x2="16" y2="22"/></svg>',
        ],
        'Éducation Civique et Morale' => [
            'color' => '#0D9488',
            'bg' => '#F0FDFA',
            'glow' => 'rgba(13, 148, 136, 0.35)',
            'icon' => '<svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="#0D9488" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v18"/><path d="M3 7l9-4 9 4"/><path d="M5 10l-3 6h6l-3-6z"/><path d="M19 10l-3 6h6l-3-6z"/></svg>',
        ],
        'Allemand' => [
            'color' => '#EA580C',
            'bg' => '#FFF7ED',
            'glow' => 'rgba(234, 88, 12, 0.35)',
            'icon' => '<svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="#EA580C" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m5 8 6 6"/><path d="m4 14 6-6 2-3"/><path d="M2 5h12"/><path d="M7 2h1"/><path d="m22 22-5-10-5 10"/><path d="M14 18h6"/></svg>',
        ],
        'Espagnol' => [
            'color' => '#C026D3',
            'bg' => '#FDF4FF',
            'glow' => 'rgba(192, 38, 211, 0.35)',
            'icon' => '<svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="#C026D3" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>',
        ],
        'Informatique' => [
            'color' => '#0891B2',
            'bg' => '#ECFEFF',
            'glow' => 'rgba(8, 145, 178, 0.35)',
            'icon' => '<svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="#0891B2" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>',
        ],
        'Technologie' => [
            'color' => '#475569',
            'bg' => '#F8FAFC',
            'glow' => 'rgba(71, 85, 105, 0.35)',
            'icon' => '<svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>',
        ],
    ];

    $defaultTheme = [
        'color' => '#4F46E5',
        'bg' => '#EEF2FF',
        'glow' => 'rgba(79, 70, 229, 0.35)',
        'icon' => '<svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="#4F46E5" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>',
    ];
@endphp

{{-- Animation du drapeau togolais flottant au vent --}}
<style>
@keyframes togoWave {
    0% {
        transform: perspective(300px) rotateY(0deg) rotateZ(0deg) translateY(0);
    }
    25% {
        transform: perspective(300px) rotateY(14deg) rotateZ(-2deg) translateY(-2px);
    }
    50% {
        transform: perspective(300px) rotateY(-8deg) rotateZ(1.5deg) translateY(1.5px);
    }
    75% {
        transform: perspective(300px) rotateY(11deg) rotateZ(-1deg) translateY(-1px);
    }
    100% {
        transform: perspective(300px) rotateY(0deg) rotateZ(0deg) translateY(0);
    }
}
.togo-flag-anim {
    display: inline-flex;
    align-items: center;
    transform-origin: left center;
    animation: togoWave 3.2s ease-in-out infinite;
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.18);
    border-radius: 4px;
    overflow: hidden;
    border: 1px solid rgba(0, 0, 0, 0.08);
    vertical-align: middle;
}
</style>

{{-- En-tête : Titre agrandi avec drapeau togolais animé & Boutons d'action (espace resserré) --}}
<div class="r" style="margin-bottom: 10px; flex-wrap: wrap; gap: 12px; align-items: center;">
    <div style="display: flex; align-items: center; gap: 12px;">
        <h1 style="font-size: 28px; font-weight: 900; margin: 0; color: #0E1022; letter-spacing: -0.03em; display: inline-flex; align-items: center; gap: 12px;">
            Examens nationaux
            <span class="togo-flag-anim" title="République Togolaise">
                <svg width="34" height="22" viewBox="0 0 100 60" style="display: block;">
                    {{-- 5 bandes alternées vertes et jaunes --}}
                    <rect y="0" width="100" height="12" fill="#006A4E" />
                    <rect y="12" width="100" height="12" fill="#FFCE00" />
                    <rect y="24" width="100" height="12" fill="#006A4E" />
                    <rect y="36" width="100" height="12" fill="#FFCE00" />
                    <rect y="48" width="100" height="12" fill="#006A4E" />
                    {{-- Canton rouge carré supérieur gauche --}}
                    <rect x="0" y="0" width="36" height="36" fill="#D21034" />
                    {{-- Étoile blanche à 5 branches --}}
                    <polygon fill="#FFFFFF" points="18,7 20.6,14.4 28.5,14.6 22.3,19.4 24.5,26.9 18,22.5 11.5,26.9 13.7,19.4 7.5,14.6 15.4,14.4" />
                </svg>
            </span>
        </h1>
    </div>
    <div style="display: flex; gap: 10px; align-items: center;">
        <a class="bt l" href="{{ route('student.seance') }}" style="display: inline-flex; align-items: center; gap: 8px; font-size: 13px; padding: 9px 18px; text-decoration: none; font-weight: 700; border-radius: 999px; box-shadow: 0 4px 14px rgba(212,242,92,0.35);">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#0E1022" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            Examen Blanc
        </a>
        <a class="bt" href="{{ route('student.parcours') }}" style="display: inline-flex; align-items: center; gap: 8px; font-size: 13px; padding: 9px 18px; text-decoration: none; font-weight: 600; border-radius: 999px; background: #0E1022; color: #fff; box-shadow: 0 4px 14px rgba(14,16,34,0.18);">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#D4F25C" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"></polygon><line x1="9" y1="3" x2="9" y2="18"></line><line x1="15" y1="6" x2="15" y2="21"></line></svg>
            Feuille de route
        </a>
    </div>
</div>

{{-- 1. Filtres structurés (Matière, Classe, Série, Année, Examen) --}}
<div id="catalogue-annales" class="c" style="margin-bottom: 14px; padding: 11px 16px;">
    <form method="GET" action="{{ route('student.examens') }}#catalogue-annales" style="margin: 0;">
        <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
            {{-- Recherche --}}
            <div style="flex: 1.2; min-width: 140px;">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Ex: MATHS_TLE_D, 2023, Savanes..." 
                       style="width: 100%; padding: 7px 10px; border-radius: 8px; border: 1.5px solid #CBD6E6; background: #fff; font-size: 12px; box-sizing: border-box; outline: none;">
            </div>

            {{-- 1. Filtre Matière (Avec sigles courts : ECM, PCT, SVT...) --}}
            <div style="flex: 0.8; min-width: 95px;">
                <select name="subject_id" style="width: 100%; padding: 7px 8px; border-radius: 8px; border: 1.5px solid #CBD6E6; background: #fff; font-size: 12px; outline: none;">
                    <option value="">Matières</option>
                    @foreach($subjects as $sbj)
                        @php $sShort = $shortSubjects[$sbj->name] ?? $sbj->name; @endphp
                        <option value="{{ $sbj->id }}" {{ ($subjectId ?? '') == $sbj->id ? 'selected' : '' }}>
                            {{ $sShort }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- 2. Filtre Classe --}}
            <div style="flex: 0.9; min-width: 105px;">
                <select name="level_id" style="width: 100%; padding: 7px 8px; border-radius: 8px; border: 1.5px solid #CBD6E6; background: #fff; font-size: 12px; outline: none;">
                    <option value="">Classes</option>
                    @foreach($educationLevels as $lvl)
                        <option value="{{ $lvl->id }}" {{ ($levelId ?? '') == $lvl->id ? 'selected' : '' }}>
                            {{ $lvl->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- 3. Filtre Série --}}
            <div style="flex: 0.7; min-width: 80px;">
                <select name="serie" style="width: 100%; padding: 7px 8px; border-radius: 8px; border: 1.5px solid #CBD6E6; background: #fff; font-size: 12px; outline: none;">
                    <option value="">Séries</option>
                    <option value="D" {{ ($serie ?? '') == 'D' ? 'selected' : '' }}>Série D</option>
                    <option value="C4" {{ ($serie ?? '') == 'C4' ? 'selected' : '' }}>Série C4</option>
                    <option value="A4" {{ ($serie ?? '') == 'A4' ? 'selected' : '' }}>Série A4</option>
                    <option value="COLLEGE" {{ in_array(($serie ?? ''), ['COLLEGE', 'BEPC']) ? 'selected' : '' }}>Collège</option>
                </select>
            </div>

            {{-- 4. Filtre Année --}}
            <div style="flex: 0.7; min-width: 80px;">
                <select name="year" style="width: 100%; padding: 7px 8px; border-radius: 8px; border: 1.5px solid #CBD6E6; background: #fff; font-size: 12px; outline: none;">
                    <option value="">Sessions</option>
                    @foreach(['2025', '2024', '2023', '2022', '2021', '2020', '2019', '2018', '2016'] as $y)
                        <option value="{{ $y }}" {{ ($year ?? '') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>

            {{-- 5. Filtre Examen / Région --}}
            <div style="flex: 0.8; min-width: 95px;">
                <select name="exam_type" style="width: 100%; padding: 7px 8px; border-radius: 8px; border: 1.5px solid #CBD6E6; background: #fff; font-size: 12px; outline: none;">
                    <option value="">Examens</option>
                    <option value="BAC2" {{ ($examType ?? '') == 'BAC2' ? 'selected' : '' }}>BAC 2</option>
                    <option value="BAC1" {{ ($examType ?? '') == 'BAC1' ? 'selected' : '' }}>BAC 1</option>
                    <option value="BEPC" {{ ($examType ?? '') == 'BEPC' ? 'selected' : '' }}>BEPC</option>
                    <option value="SAVANES" {{ ($examType ?? '') == 'SAVANES' ? 'selected' : '' }}>Savanes</option>
                    <option value="KARA" {{ ($examType ?? '') == 'KARA' ? 'selected' : '' }}>Kara</option>
                    <option value="CENTRALE" {{ ($examType ?? '') == 'CENTRALE' ? 'selected' : '' }}>Centrale</option>
                    <option value="PLATEAUX" {{ ($examType ?? '') == 'PLATEAUX' ? 'selected' : '' }}>Plateaux</option>
                    <option value="MARITIME" {{ ($examType ?? '') == 'MARITIME' ? 'selected' : '' }}>Maritime</option>
                    <option value="LOME" {{ ($examType ?? '') == 'LOME' ? 'selected' : '' }}>Lomé</option>
                </select>
            </div>

            {{-- Groupe de boutons d'action alignés --}}
            <div style="display: flex; gap: 6px; align-items: center; flex: none;">
                <button type="submit" class="bt" style="padding: 7px 14px; font-size: 12.5px; font-weight: 700; border: none; cursor: pointer; white-space: nowrap;">Filtrer</button>
                @if(!empty($search) || !empty($levelId) || !empty($subjectId) || !empty($serie) || !empty($year) || !empty($examType))
                    <a href="{{ route('student.examens') }}#catalogue-annales" class="bt g" style="padding: 7px 10px; font-size: 12px; text-decoration: none; white-space: nowrap;">Réinitialiser</a>
                @endif
            </div>
        </div>
    </form>
</div>

{{-- 2. Grille des box d'affichage : Nom de fichier officiel MATIERE_CLASSE_SERIE_ANNEE_EXAMEN --}}
@if($exams->isEmpty())
    <div class="c" style="text-align: center; padding: 36px 18px;">
        <h3 style="font-size:16px; font-weight:600; margin-bottom:4px;">Aucune épreuve trouvée</h3>
        <p class="mu" style="font-size:13px; max-width:380px; margin:0 auto 12px auto;">
            Aucun sujet officiel ne correspond à ces critères. Essaye de réinitialiser les filtres.
        </p>
        <a href="{{ route('student.examens') }}#catalogue-annales" class="bt" style="font-size:12.5px; padding:7px 14px;">Voir tous les examens</a>
    </div>
@else
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(275px, 1fr)); gap: 14px; margin-bottom: 24px;">
        @foreach($exams as $exam)
            @php
                $thm = $subjectThemes[$exam->subject_name] ?? $defaultTheme;
                $sbjShort = $shortSubjects[$exam->subject_name] ?? $exam->subject_name;
            @endphp
            <div class="c-exam" style="background: #fff; border-radius: 20px; border: 1.5px solid #E2E8F0; box-shadow: -4px 5px 0px 0px {{ $thm['color'] }}; padding: 18px 20px; display: flex; flex-direction: column; justify-content: space-between; gap: 12px; transition: transform 0.15s ease, box-shadow 0.15s ease;">
                <div>
                    {{-- 1. En-tête : Matière seule (en texte net et gras) + Icône spéciale de la matière (espace encadré jaune) --}}
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; gap: 8px;">
                        <b style="font-size: 19px; font-weight: 800; color: #0E1022; font-family: 'Sora', system-ui, sans-serif; letter-spacing: -0.02em;">
                            {{ $sbjShort }}
                        </b>
                        {{-- Icône dédiée à la matière --}}
                        <div style="width: 36px; height: 36px; border-radius: 10px; background: {{ $thm['bg'] }}; border: 1px solid {{ $thm['color'] }}25; display: flex; align-items: center; justify-content: center; flex: none;" title="{{ $exam->subject_name }}">
                            {!! $thm['icon'] !!}
                        </div>
                    </div>

                    {{-- 2. Box centrale : Nom officiel du document (sur 1 seule ligne, pas de coupure de mot) + Icône document --}}
                    <div style="background: #F1F5F9; border: 1.5px solid #E2E8F0; padding: 11px 12px; border-radius: 12px; display: flex; justify-content: space-between; align-items: center; gap: 8px;">
                        <span style="font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace; font-size: 11.5px; font-weight: 800; color: #0E1022; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; letter-spacing: -0.2px;" title="{{ $exam->file_code }}">
                            {{ $exam->file_code }}
                        </span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex: none;">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                        </svg>
                    </div>
                </div>

                {{-- 3. Métadonnées (Badges Classe, Série, Année à gauche + Sujet/Corrigé à droite) & Boutons d'action --}}
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; gap: 6px;">
                        {{-- Badges descendus ici selon la flèche rouge --}}
                        <div style="display: flex; gap: 5px; align-items: center; flex-wrap: wrap;">
                            <span style="font-size: 10.5px; font-weight: 700; background: {{ $thm['bg'] }}; color: {{ $thm['color'] }}; padding: 3px 8px; border-radius: 999px;">
                                {{ $exam->level_code }} · {{ in_array(strtoupper($exam->serie ?? ''), ['BEPC', 'COLLEGE']) || in_array($exam->level_code, ['3E', '4E', '5E', '6E']) ? 'COLLÈGE' : 'SÉRIE ' . ($exam->serie ?? 'D') }}
                            </span>
                            <span style="font-size: 10.5px; font-weight: 800; background: #FEF3C7; color: #B45309; padding: 3px 8px; border-radius: 999px;">
                                {{ $exam->year ?? 2023 }}
                            </span>
                        </div>

                        {{-- Statut Sujet / Corrigé --}}
                        <div>
                            @if($exam->is_solution_restricted)
                                <span style="font-size: 10px; font-weight: 700; background: #FFE4E6; color: #E11D48; padding: 3px 9px; border-radius: 999px;">Corrigé</span>
                            @else
                                <span style="font-size: 10px; font-weight: 700; background: #EDE9FE; color: #6D28D9; padding: 3px 9px; border-radius: 999px;">Sujet</span>
                            @endif
                        </div>
                    </div>

                    <div style="display: flex; gap: 8px; align-items: center;">
                        @if(!empty($exam->file_id))
                            <a href="{{ route('student.examens.preview', $exam->file_id) }}" 
                               target="_blank" 
                               class="bt" 
                               style="flex: 1; text-align: center; font-size: 12.5px; padding: 10px 16px; text-decoration: none; font-weight: 700; border-radius: 999px; background: {{ $thm['color'] }}; color: #fff; box-shadow: 0 4px 14px {{ $thm['glow'] }};">
                                Voir l'épreuve ↗
                            </a>
                            <a href="{{ route('student.examens.download', $exam->file_id) }}" 
                               title="Télécharger l'épreuve"
                               style="width: 40px; height: 40px; border-radius: 50%; background: #F1F5F9; border: 1.5px solid #E2E8F0; color: #0E1022; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 800; text-decoration: none; flex: none; transition: all 0.15s ease;">
                                ↓
                            </a>
                        @else
                            <span style="flex: 1; text-align: center; font-size: 12px; background: #F1F5F9; color: #94A3B8; padding: 10px 16px; border-radius: 999px; font-weight: 600;">
                                En cours
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Pagination compacte --}}
    <div style="display: flex; justify-content: center; margin-bottom: 28px;">
        <div style="display: flex; gap: 6px; align-items: center;">
            @if($exams->onFirstPage())
                <span class="bt g" style="opacity: 0.4; pointer-events: none; font-size: 12px; padding: 6px 12px;">← Précédent</span>
            @else
                <a href="{{ $exams->previousPageUrl() }}#catalogue-annales" class="bt g" style="font-size: 12px; padding: 6px 12px; text-decoration: none;">← Précédent</a>
            @endif

            <span class="mu" style="font-size: 12px; padding: 0 8px;">
                Page <b>{{ $exams->currentPage() }}</b> / <b>{{ $exams->lastPage() }}</b>
            </span>

            @if($exams->hasMorePages())
                <a href="{{ $exams->nextPageUrl() }}#catalogue-annales" class="bt g" style="font-size: 12px; padding: 6px 12px; text-decoration: none;">Suivant →</a>
            @else
                <span class="bt g" style="opacity: 0.4; pointer-events: none; font-size: 12px; padding: 6px 12px;">Suivant →</span>
            @endif
        </div>
    </div>
@endif
@endsection
