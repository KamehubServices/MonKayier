@extends('layouts.admin')

@section('title', 'Bibliothèque Pédagogique & Validation')
@section('header-title', 'Workflow Éditorial & Validation des Ressources')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- Alerte sur la séparation des rôles -->
    <div style="background: #eef2ff; border-left: 4px solid var(--mk-indigo); padding: 14px 20px; border-radius: 12px; display: flex; align-items: center; justify-content: space-between;">
        <div style="font-size: 13.5px; color: #312e81;">
            <strong>Règle de Gouvernance Souveraine :</strong> Un auteur ne peut jamais valider son propre cours officiel. Toute publication exige une validation par un pair ou un inspecteur pédagogique agréé.
        </div>
        <span class="mk-badge mk-badge-official">Sécurité Active</span>
    </div>

    <!-- Table des Ressources Pédagogiques -->
    <div class="mk-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="font-size: 17px; font-weight: 700; color: var(--mk-night);">Ressources Pédagogiques Enregistrées</h3>
            <div style="display: flex; gap: 8px;">
                <span class="mk-badge mk-badge-published">Publié : {{ $resources->where('status', 'published')->count() }}</span>
                <span class="mk-badge mk-badge-draft">Brouillon : {{ $resources->where('status', 'draft')->count() }}</span>
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 14px; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--mk-border); color: var(--mk-text-muted);">
                        <th style="padding: 12px 16px;">Titre de la Ressource</th>
                        <th style="padding: 12px 16px;">Niveau & Matière</th>
                        <th style="padding: 12px 16px;">Type / Origine</th>
                        <th style="padding: 12px 16px;">Statut Éditorial</th>
                        <th style="padding: 12px 16px;">Auteur</th>
                        <th style="padding: 12px 16px; text-align: right;">Actions Workflow</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($resources as $res)
                        <tr style="border-bottom: 1px solid var(--mk-border);">
                            <td style="padding: 14px 16px; max-width: 320px;">
                                <div style="font-weight: 700; color: var(--mk-night); font-size: 14.5px;">{{ $res->title }}</div>
                                <div style="font-size: 12px; color: var(--mk-text-muted); margin-top: 2px;">
                                    {{ Str::limit($res->summary, 80) }}
                                </div>
                            </td>
                            <td style="padding: 14px 16px;">
                                <span class="mk-badge mk-badge-lavender">{{ $res->educationLevel->code ?? '3E' }}</span>
                                <div style="font-weight: 600; font-size: 13px; margin-top: 4px;">{{ $res->subject->name ?? 'Général' }}</div>
                            </td>
                            <td style="padding: 14px 16px;">
                                <span class="mk-badge mk-badge-official">{{ $res->resource_type }}</span>
                                <div style="font-size: 11px; color: var(--mk-text-muted); margin-top: 2px;">Origine : {{ $res->origin }}</div>
                            </td>
                            <td style="padding: 14px 16px;">
                                @if($res->status === 'published')
                                    <span class="mk-badge mk-badge-published">✓ Publié</span>
                                @elseif($res->status === 'approved')
                                    <span class="mk-badge mk-badge-lime">Approuvé</span>
                                @elseif($res->status === 'review')
                                    <span class="mk-badge mk-badge-review">En Relecture</span>
                                @elseif($res->status === 'draft')
                                    <span class="mk-badge mk-badge-draft">Brouillon</span>
                                @else
                                    <span class="mk-badge">{{ $res->status }}</span>
                                @endif
                            </td>
                            <td style="padding: 14px 16px; font-size: 13px; color: var(--mk-text-muted);">
                                {{ $res->author->email ?? 'Professeur Togo' }}
                            </td>
                            <td style="padding: 14px 16px; text-align: right;">
                                <div style="display: inline-flex; gap: 6px;">
                                    @if($res->status === 'draft')
                                        <form method="POST" action="/admin/resources/{{ $res->id }}/submit" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="mk-btn mk-btn-outline mk-btn-sm" style="font-size: 12px; padding: 6px 12px;">
                                                Soumettre
                                            </button>
                                        </form>
                                    @endif

                                    @if($res->status === 'review')
                                        <form method="POST" action="/admin/resources/{{ $res->id }}/approve" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="mk-btn mk-btn-lime mk-btn-sm" style="font-size: 12px; padding: 6px 12px;">
                                                Valider
                                            </button>
                                        </form>
                                    @endif

                                    @if($res->status === 'approved')
                                        <form method="POST" action="/admin/resources/{{ $res->id }}/publish" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="mk-btn mk-btn-primary mk-btn-sm" style="font-size: 12px; padding: 6px 12px;">
                                                Publier
                                            </button>
                                        </form>
                                    @endif

                                    <a href="/student/reader/{{ $res->id }}" target="_blank" class="mk-btn mk-btn-lavender mk-btn-sm" style="font-size: 12px; padding: 6px 12px;">
                                        Aperçu
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
