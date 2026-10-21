@extends('layouts.app')

@section('title', 'Mes Matières — Classe de 3e')
@section('header-title', 'Programme & Matières (Collège 3e)')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <div style="background: var(--mk-surface); border-radius: var(--mk-radius-card); padding: 24px; border: 1px solid var(--mk-border);">
        <h1 style="font-size: 22px; font-weight: 800; color: var(--mk-night); margin-bottom: 6px;">
            Matières Officielles au BEPC 2027 (Togo)
        </h1>
        <p style="font-size: 14px; color: var(--mk-text-muted); line-height: 1.6;">
            Consulte les chapitres de chaque discipline, les objectifs observables exigibles à l'examen national, et accède aux fiches de cours correspondantes.
        </p>
    </div>

    <!-- Détail des Matières et Chapitres -->
    <div style="display: flex; flex-direction: column; gap: 20px;">
        @foreach($subjects as $item)
            <div class="mk-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid var(--mk-border); padding-bottom: 14px;">
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <div class="mk-subject-icon">
                            {{ $item['icon'] ?? '📖' }}
                        </div>
                        <div>
                            <h2 style="font-size: 18px; font-weight: 800; color: var(--mk-night);">{{ $item['name'] }}</h2>
                            <div style="font-size: 12.5px; color: var(--mk-text-muted); margin-top: 2px;">
                                Coefficient : <strong style="color: var(--mk-indigo);">{{ $item['coefficient'] }}</strong> · 
                                {{ $item['hours'] }}h de cours hebdomadaire · Épreuve obligatoire au BEPC
                            </div>
                        </div>
                    </div>

                    <a href="/student/assistant?subject={{ $item['code'] }}" class="mk-btn mk-btn-lavender mk-btn-sm">
                        Poser une question en {{ $item['name'] }}
                    </a>
                </div>

                <!-- Chapitres du programme -->
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <div style="font-size: 13px; font-weight: 700; text-transform: uppercase; color: var(--mk-text-muted); letter-spacing: 0.5px;">
                        Chapitres Clés du Programme Togo :
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 12px;">
                        @foreach($item['chapters'] ?? [] as $chapter)
                            <div style="padding: 12px 16px; border: 1px solid var(--mk-border); border-radius: 12px; background: var(--mk-page);">
                                <div style="font-weight: 700; font-size: 14px; color: var(--mk-night);">{{ $chapter['title'] }}</div>
                                <div style="font-size: 12px; color: var(--mk-text-muted); margin-top: 4px;">{{ $chapter['desc'] }}</div>
                                <div style="margin-top: 8px;">
                                    <span class="mk-badge mk-badge-lime" style="font-size: 10px;">{{ $chapter['status'] ?? 'Au programme' }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection
