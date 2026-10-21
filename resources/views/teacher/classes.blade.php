@extends('layouts.app')

@section('title', 'Gestion des Classes · Enseignant')
@section('header-title', 'Mes Classes & Effectifs')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-size: 22px; font-weight: 800; color: var(--mk-night); margin: 0;">
                Classes & Effectifs Scolaires
            </h1>
            <p style="font-size: 13.5px; color: var(--mk-text-muted); margin: 4px 0 0 0;">
                Année Scolaire 2026-2027 · Établissements partenaires du Togo
            </p>
        </div>
        <a href="/teacher/assignments" class="mk-btn mk-btn-primary">
            + Assigner un devoir à une classe
        </a>
    </div>

    <!-- Grille des classes -->
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 20px;">
        @forelse($classes as $c)
            <div class="mk-card" style="border-top: 4px solid var(--mk-indigo);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                    <div>
                        <span class="mk-badge mk-badge-lavender">{{ $c->educationLevel?->name ?? 'Classe de 3e' }}</span>
                        @if($c->series)
                            <span class="mk-badge" style="background: var(--mk-soft-coral); color: #c2410c;">Série {{ $c->series->code }}</span>
                        @endif
                    </div>
                    <span class="mk-badge mk-badge-lime">{{ $c->students_count }} élève(s)</span>
                </div>

                <h3 style="font-size: 19px; font-weight: 800; color: var(--mk-night); margin: 0 0 6px 0;">
                    {{ $c->label }}
                </h3>
                <p style="font-size: 13px; color: var(--mk-text-muted); margin: 0 0 16px 0;">
                    Établissement : <strong>{{ $c->school?->name ?? 'Collège Protestant de Lomé' }}</strong>
                </p>

                <!-- Liste abrégée des élèves inscrits -->
                <div style="background: var(--mk-bg-subtle); border-radius: 10px; padding: 12px; margin-bottom: 16px;">
                    <div style="font-size: 12px; font-weight: 700; color: var(--mk-night); text-transform: uppercase; margin-bottom: 6px;">
                        Exemples d'élèves inscrits :
                    </div>
                    @forelse($c->students->take(3) as $st)
                        <div style="font-size: 13px; color: var(--mk-text); margin-bottom: 3px;">
                            • {{ $st->profile ? ($st->profile->first_name . ' ' . $st->profile->last_name) : $st->email }}
                        </div>
                    @empty
                        <div style="font-size: 12px; color: var(--mk-text-muted);">Inscriptions actives via référentiel national.</div>
                    @endforelse
                </div>

                <div style="display: flex; gap: 10px;">
                    <a href="/teacher/assignments" class="mk-btn mk-btn-outline mk-btn-sm" style="flex: 1; justify-content: center;">
                        Devoirs de la classe
                    </a>
                </div>
            </div>
        @empty
            <div class="mk-card" style="grid-column: 1 / -1; text-align: center; padding: 40px;">
                <p style="color: var(--mk-text-muted);">Aucune classe assignée pour le moment.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
