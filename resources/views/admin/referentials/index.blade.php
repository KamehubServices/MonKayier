@extends('layouts.admin')

@section('title', 'Référentiels Scolaires du Togo')
@section('header-title', 'Cycles, Niveaux, Séries et Coefficients')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- Cycles Scolaires -->
    <div class="mk-card">
        <h3 style="font-size: 17px; font-weight: 700; color: var(--mk-night); margin-bottom: 16px;">
            1. Cycles de l'Enseignement au Togo
        </h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px;">
            @foreach($cycles as $cycle)
                <div style="padding: 16px; border: 1px solid var(--mk-border); border-radius: 14px; background: var(--mk-surface);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span class="mk-badge mk-badge-lavender">{{ $cycle->code }}</span>
                        <span style="font-size: 11px; color: var(--mk-text-muted);">Ordre : {{ $cycle->pedagogical_order }}</span>
                    </div>
                    <div style="font-size: 16px; font-weight: 700; color: var(--mk-night);">{{ $cycle->name }}</div>
                    <div style="font-size: 12px; color: var(--mk-text-muted); margin-top: 4px;">{{ $cycle->description }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Niveaux Scolaires & Classes -->
    <div class="mk-card">
        <h3 style="font-size: 17px; font-weight: 700; color: var(--mk-night); margin-bottom: 16px;">
            2. Classes & Niveaux d'Examen (6e à Terminale)
        </h3>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 14px; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--mk-border); color: var(--mk-text-muted);">
                        <th style="padding: 12px 16px;">Code</th>
                        <th style="padding: 12px 16px;">Libellé Officiel</th>
                        <th style="padding: 12px 16px;">Cycle</th>
                        <th style="padding: 12px 16px;">Examen National</th>
                        <th style="padding: 12px 16px;">Ordre</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($levels as $level)
                        <tr style="border-bottom: 1px solid var(--mk-border);">
                            <td style="padding: 12px 16px; font-weight: 700; color: var(--mk-indigo);">{{ $level->code }}</td>
                            <td style="padding: 12px 16px; font-weight: 600; color: var(--mk-night);">{{ $level->name }}</td>
                            <td style="padding: 12px 16px;">
                                <span class="mk-badge mk-badge-draft">{{ $level->cycle }}</span>
                            </td>
                            <td style="padding: 12px 16px;">
                                @if($level->is_exam_level)
                                    <span class="mk-badge mk-badge-lime">Examen Officiel</span>
                                @else
                                    <span style="color: var(--mk-text-muted); font-size: 12px;">Passage continu</span>
                                @endif
                            </td>
                            <td style="padding: 12px 16px; color: var(--mk-text-muted);">{{ $level->sort_order }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Matières & Coefficients Officiels -->
    <div class="mk-card">
        <h3 style="font-size: 17px; font-weight: 700; color: var(--mk-night); margin-bottom: 16px;">
            3. Matières Officielles & Coefficients (Classe de 3e & Terminale)
        </h3>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 14px; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--mk-border); color: var(--mk-text-muted);">
                        <th style="padding: 12px 16px;">Classe / Série</th>
                        <th style="padding: 12px 16px;">Matière</th>
                        <th style="padding: 12px 16px;">Coefficient</th>
                        <th style="padding: 12px 16px;">Volume Hebdomadaire</th>
                        <th style="padding: 12px 16px;">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($levelSubjects as $item)
                        <tr style="border-bottom: 1px solid var(--mk-border);">
                            <td style="padding: 12px 16px; font-weight: 700; color: var(--mk-night);">
                                {{ $item->educationLevel->code }}
                                @if($item->series)
                                    <span class="mk-badge mk-badge-lavender">{{ $item->series->code }}</span>
                                @endif
                            </td>
                            <td style="padding: 12px 16px; font-weight: 600;">{{ $item->subject->name }}</td>
                            <td style="padding: 12px 16px;">
                                <strong style="color: var(--mk-indigo); font-size: 15px;">Coeff {{ $item->coefficient }}</strong>
                            </td>
                            <td style="padding: 12px 16px; color: var(--mk-text-muted);">{{ $item->weekly_hours }}h / semaine</td>
                            <td style="padding: 12px 16px;">
                                <span class="mk-badge mk-badge-official">Obligatoire</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
