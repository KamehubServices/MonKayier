@extends('layouts.admin')

@section('title', 'Imports Batch de Référentiels')
@section('header-title', 'Importation CSV des Programmes et Données')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- Zone de Téléversement CSV -->
    <div class="mk-card">
        <h3 style="font-size: 17px; font-weight: 700; color: var(--mk-night); margin-bottom: 8px;">
            Importer un nouveau lot de référentiels (CSV / UTF-8)
        </h3>
        <p style="font-size: 13.5px; color: var(--mk-text-muted); margin-bottom: 20px;">
            Le fichier est vérifié avec empreinte SHA-256 pour prévenir les doublons. Activez le mode simulation pour valider la structure sans altérer la base de production.
        </p>

        <form method="POST" action="/admin/imports/upload" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 16px;">
            @csrf
            <div style="border: 2px dashed var(--mk-border); border-radius: 16px; padding: 36px; text-align: center; background: #fafbff; cursor: pointer;">
                <svg width="40" height="40" fill="none" stroke="var(--mk-indigo)" viewBox="0 0 24 24" style="margin: 0 auto 12px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                <div style="font-weight: 700; font-size: 15px; color: var(--mk-night);">Cliquez pour choisir votre fichier CSV ou glissez-le ici</div>
                <div style="font-size: 12px; color: var(--mk-text-muted); margin-top: 4px;">Formats acceptés : .csv (UTF-8) · Taille max : 25 Mo</div>
                <input type="file" name="csv_file" accept=".csv" required style="margin-top: 14px;">
            </div>

            <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 10px;">
                <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; cursor: pointer;">
                    <input type="checkbox" name="dry_run" value="1" checked>
                    <span>Mode simulation (Dry-Run) sans écriture en base</span>
                </label>

                <div style="display: flex; gap: 12px;">
                    <a href="/templates/referentiels_togo_modele.csv" download class="mk-btn mk-btn-outline mk-btn-sm">
                        Télécharger le modèle CSV
                    </a>
                    <button type="submit" class="mk-btn mk-btn-primary mk-btn-sm">
                        Lancer l'analyse et l'import
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Historique des Imports Batch -->
    <div class="mk-card">
        <h3 style="font-size: 17px; font-weight: 700; color: var(--mk-night); margin-bottom: 16px;">
            Historique des Traitements Batch
        </h3>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 14px; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--mk-border); color: var(--mk-text-muted);">
                        <th style="padding: 12px 16px;">Nom du Fichier</th>
                        <th style="padding: 12px 16px;">Statut</th>
                        <th style="padding: 12px 16px;">Lignes Totales</th>
                        <th style="padding: 12px 16px;">Traitées</th>
                        <th style="padding: 12px 16px;">Erreurs</th>
                        <th style="padding: 12px 16px;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($imports ?? [] as $imp)
                        <tr style="border-bottom: 1px solid var(--mk-border);">
                            <td style="padding: 12px 16px; font-weight: 600; color: var(--mk-night);">{{ $imp->file_name }}</td>
                            <td style="padding: 12px 16px;">
                                @if($imp->status === 'completed')
                                    <span class="mk-badge mk-badge-published">Succès</span>
                                @elseif($imp->status === 'failed')
                                    <span class="mk-badge mk-badge-danger">Échec</span>
                                @else
                                    <span class="mk-badge mk-badge-review">{{ $imp->status }}</span>
                                @endif
                            </td>
                            <td style="padding: 12px 16px;">{{ $imp->total_rows }}</td>
                            <td style="padding: 12px 16px;">{{ $imp->processed_rows }}</td>
                            <td style="padding: 12px 16px; color: {{ $imp->error_count > 0 ? '#dc2626' : 'var(--mk-text-muted)' }};">
                                {{ $imp->error_count }}
                            </td>
                            <td style="padding: 12px 16px; font-size: 12px; color: var(--mk-text-muted);">
                                {{ $imp->created_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 24px; text-align: center; color: var(--mk-text-muted);">
                                Aucun import récent. Téléversez un fichier CSV pour commencer.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
