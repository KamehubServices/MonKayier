@extends('layouts.app')

@section('title', 'Bibliothèque Pédagogique')
@section('header-title', 'Bibliothèque Numérique Officielle')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- En-tête et Recherche -->
    <div class="mk-card">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
            <div>
                <h1 style="font-size: 22px; font-weight: 800; color: var(--mk-night);">Ressources Pédagogiques Validées</h1>
                <p style="font-size: 13.5px; color: var(--mk-text-muted); margin-top: 4px;">
                    Tous les cours, fiches synthétiques et annales d'examens validés par l'inspection générale du Togo.
                </p>
            </div>
            <span class="mk-badge mk-badge-published">Corpus Souverain Togo</span>
        </div>

        <!-- Filtres rapides -->
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="/student/library" class="mk-btn mk-btn-primary mk-btn-sm">Tous les cours</a>
            <a href="/student/library?subject=MATHS" class="mk-btn mk-btn-outline mk-btn-sm">Mathématiques</a>
            <a href="/student/library?subject=PCT" class="mk-btn mk-btn-outline mk-btn-sm">Physique-Chimie (PCT)</a>
            <a href="/student/library?subject=SVT" class="mk-btn mk-btn-outline mk-btn-sm">SVT</a>
            <a href="/student/library?subject=FRANCAIS" class="mk-btn mk-btn-outline mk-btn-sm">Français</a>
        </div>
    </div>

    <!-- Grille des Cours Disponibles -->
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px;">
        @foreach($resources as $res)
            <div class="mk-card mk-card-hover" style="display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <span class="mk-badge mk-badge-lavender">{{ $res->subject->name ?? 'Général' }}</span>
                        <span class="mk-badge mk-badge-official">Officiel MEPST</span>
                    </div>

                    <h2 style="font-size: 16.5px; font-weight: 800; color: var(--mk-night); margin-bottom: 8px; line-height: 1.35;">
                        {{ $res->title }}
                    </h2>
                    <p style="font-size: 13px; color: var(--mk-text-muted); line-height: 1.5; margin-bottom: 16px;">
                        {{ Str::limit($res->summary, 120) }}
                    </p>
                </div>

                <div>
                    <div style="padding: 10px 14px; background: var(--mk-page); border-radius: 10px; font-size: 12px; color: var(--mk-text-muted); margin-bottom: 16px;">
                        Source certifiée : <strong>MEPST Togo</strong> · Guide officiel 3e
                    </div>

                    <div style="display: flex; gap: 10px;">
                        <a href="/student/reader/{{ $res->id }}" class="mk-btn mk-btn-primary mk-btn-sm" style="flex: 1;">
                            Lire le cours
                        </a>
                        <a href="/student/assistant?resource_id={{ $res->id }}" class="mk-btn mk-btn-lavender mk-btn-sm" title="Poser une question sur ce cours">
                            IA 💬
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection
