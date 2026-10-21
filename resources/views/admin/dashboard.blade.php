@extends('layouts.admin')

@section('title', 'Tableau de bord Administrateur')
@section('header-title', 'Supervision des Référentiels & Contenus')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- KPI Metrics Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
        <div class="mk-card" style="display: flex; align-items: center; gap: 16px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--mk-lavender); color: var(--mk-indigo); display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 800;">
                {{ $stats['cycles_count'] ?? 4 }}
            </div>
            <div>
                <div style="font-size: 13px; color: var(--mk-text-muted);">Cycles Scolaires</div>
                <div style="font-size: 20px; font-weight: 800; color: var(--mk-night);">Primaire à Lycée</div>
            </div>
        </div>

        <div class="mk-card" style="display: flex; align-items: center; gap: 16px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 800;">
                {{ $stats['levels_count'] ?? 7 }}
            </div>
            <div>
                <div style="font-size: 13px; color: var(--mk-text-muted);">Classes & Niveaux</div>
                <div style="font-size: 20px; font-weight: 800; color: var(--mk-night);">6e à Terminale</div>
            </div>
        </div>

        <div class="mk-card" style="display: flex; align-items: center; gap: 16px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: #ecfdf5; color: #059669; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 800;">
                {{ $stats['subjects_count'] ?? 8 }}
            </div>
            <div>
                <div style="font-size: 13px; color: var(--mk-text-muted);">Matières Officielles</div>
                <div style="font-size: 20px; font-weight: 800; color: var(--mk-night);">Coefficients Togo</div>
            </div>
        </div>

        <div class="mk-card" style="display: flex; align-items: center; gap: 16px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 800;">
                {{ $stats['pending_reviews_count'] ?? 1 }}
            </div>
            <div>
                <div style="font-size: 13px; color: var(--mk-text-muted);">En Relecture</div>
                <div style="font-size: 20px; font-weight: 800; color: var(--mk-night);">À valider</div>
            </div>
        </div>
    </div>

    <!-- Actions Rapides & Workflow -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
        <!-- Derniers Programmes & Chapitres -->
        <div class="mk-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="font-size: 17px; font-weight: 700; color: var(--mk-night);">Programmes Officiels Actifs (Togo MEPST)</h3>
                <a href="/admin/curricula" class="mk-btn mk-btn-outline mk-btn-sm">Gérer les programmes</a>
            </div>

            <div style="display: flex; flex-direction: column; gap: 12px;">
                <div style="padding: 14px; border: 1px solid var(--mk-border); border-radius: 14px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-weight: 700; font-size: 15px; color: var(--mk-night);">Mathématiques — Classe de Troisième (BEPC)</div>
                        <div style="font-size: 12px; color: var(--mk-text-muted); margin-top: 2px;">
                            4 Domaines · 12 Chapitres · 38 Objectifs observables · Version 1.0 (2026-2027)
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span class="mk-badge mk-badge-published">Publié</span>
                        <a href="/admin/curricula" class="mk-btn mk-btn-lavender mk-btn-sm">Arborescence</a>
                    </div>
                </div>

                <div style="padding: 14px; border: 1px solid var(--mk-border); border-radius: 14px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-weight: 700; font-size: 15px; color: var(--mk-night);">Physique-Chimie et Technologie (PCT) — 3e (BEPC)</div>
                        <div style="font-size: 12px; color: var(--mk-text-muted); margin-top: 2px;">
                            3 Domaines · 8 Chapitres · 24 Objectifs observables · Version 1.0 (2026-2027)
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span class="mk-badge mk-badge-published">Publié</span>
                        <a href="/admin/curricula" class="mk-btn mk-btn-lavender mk-btn-sm">Arborescence</a>
                    </div>
                </div>

                <div style="padding: 14px; border: 1px solid var(--mk-border); border-radius: 14px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-weight: 700; font-size: 15px; color: var(--mk-night);">Mathématiques — Terminale Série CD (BAC 2)</div>
                        <div style="font-size: 12px; color: var(--mk-text-muted); margin-top: 2px;">
                            Analyse, Algèbre linéaire, Probabilités · Coeff 6 · 6h/semaine
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span class="mk-badge mk-badge-review">En Relecture</span>
                        <a href="/admin/curricula" class="mk-btn mk-btn-outline mk-btn-sm">Examiner</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panneau de Conformité Éditoriale -->
        <div class="mk-card" style="display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <h3 style="font-size: 16px; font-weight: 700; color: var(--mk-night); margin-bottom: 12px;">Règles de Conformité</h3>
                <p style="font-size: 13px; color: var(--mk-text-muted); line-height: 1.6; margin-bottom: 16px;">
                    Chaque contenu pédagogique officiel doit respecter les standards souverains de KAMEHUB SERVICES :
                </p>

                <ul style="list-style: none; display: flex; flex-direction: column; gap: 10px; font-size: 13px;">
                    <li style="display: flex; align-items: center; gap: 8px;">
                        <svg width="18" height="18" fill="none" stroke="var(--mk-indigo)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <strong>Séparation des rôles :</strong> Anti-auto-validation active.
                    </li>
                    <li style="display: flex; align-items: center; gap: 8px;">
                        <svg width="18" height="18" fill="none" stroke="var(--mk-indigo)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <strong>Intégrité SHA-256 :</strong> Détection des doublons.
                    </li>
                    <li style="display: flex; align-items: center; gap: 8px;">
                        <svg width="18" height="18" fill="none" stroke="var(--mk-indigo)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <strong>Provenance MEPST :</strong> Liens officiels requis.
                    </li>
                    <li style="display: flex; align-items: center; gap: 8px;">
                        <svg width="18" height="18" fill="none" stroke="var(--mk-indigo)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <strong>Cloisonnement :</strong> Zéro fuite de brouillons aux élèves.
                    </li>
                </ul>
            </div>

            <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--mk-border);">
                <a href="/admin/resources" class="mk-btn mk-btn-primary" style="width: 100%;">
                    Accéder à la file de validation
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
