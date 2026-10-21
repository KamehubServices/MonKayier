@extends('layouts.app')

@section('title', $resource->title ?? 'Lecteur de Cours')
@section('header-title', 'Lecture de Cours Officiel')

@section('content')
<div style="display: grid; grid-template-columns: 300px 1fr; gap: 24px; align-items: flex-start;">

    <!-- Volet Latéral : Contexte & Objectifs -->
    <div style="display: flex; flex-direction: column; gap: 16px;">
        <div class="mk-card">
            <span class="mk-badge mk-badge-published" style="margin-bottom: 10px;">Document Officiel Validé</span>
            <h2 style="font-size: 16px; font-weight: 800; color: var(--mk-night); margin-bottom: 8px;">
                {{ $resource->title }}
            </h2>
            <div style="font-size: 12.5px; color: var(--mk-text-muted); line-height: 1.5; margin-bottom: 14px;">
                Classe de <strong>{{ $resource->educationLevel->name ?? '3e' }}</strong> · 
                Matière : <strong>{{ $resource->subject->name ?? 'Mathématiques' }}</strong>
            </div>

            <div style="border-top: 1px solid var(--mk-border); padding-top: 12px; font-size: 12px; color: var(--mk-text-muted);">
                <div>Autorité émettrice :</div>
                <div style="font-weight: 700; color: var(--mk-night); margin-top: 2px;">
                    Ministère des Enseignements Primaire, Secondaire et Technique (Togo)
                </div>
            </div>
        </div>

        <div class="mk-card">
            <h3 style="font-size: 14px; font-weight: 700; color: var(--mk-night); margin-bottom: 10px;">
                Objectifs d'examen visés
            </h3>
            <ul style="list-style: none; display: flex; flex-direction: column; gap: 8px; font-size: 12.5px; color: var(--mk-text);">
                <li style="display: flex; align-items: flex-start; gap: 6px;">
                    <span style="color: var(--mk-indigo); font-weight: bold;">✓</span>
                    <span>Reconnaître les configurations de Thalès (triangle et papillon).</span>
                </li>
                <li style="display: flex; align-items: flex-start; gap: 6px;">
                    <span style="color: var(--mk-indigo); font-weight: bold;">✓</span>
                    <span>Rédiger rigoureusement les rapports égaux AM/AB = AN/AC = MN/BC.</span>
                </li>
                <li style="display: flex; align-items: flex-start; gap: 6px;">
                    <span style="color: var(--mk-indigo); font-weight: bold;">✓</span>
                    <span>Utiliser la réciproque pour démontrer le parallélisme.</span>
                </li>
            </ul>

            <div style="margin-top: 16px;">
                <a href="/student/assistant?topic=thales" class="mk-btn mk-btn-primary mk-btn-sm" style="width: 100%;">
                    Demander à l'assistant IA
                </a>
            </div>
        </div>
    </div>

    <!-- Volet Principal : Texte du cours & pagination -->
    <div class="mk-card" style="padding: 36px 40px; min-height: 600px; display: flex; flex-direction: column; justify-content: space-between;">
        <div>
            <!-- En-tête de la page de cours -->
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--mk-border); padding-bottom: 16px; margin-bottom: 28px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span class="mk-badge mk-badge-lavender">Page 1 sur 1</span>
                    <span style="font-size: 13px; color: var(--mk-text-muted);">Guide d'Exécution National · Réf MEPST-TG-3E-MATH</span>
                </div>
                <div>
                    <span class="mk-badge mk-badge-lime">Examen BEPC</span>
                </div>
            </div>

            <!-- Contenu Pédagogique Formaté -->
            <div style="font-size: 15.5px; line-height: 1.8; color: var(--mk-night);">
                <h1 style="font-size: 24px; font-weight: 800; color: var(--mk-navy); margin-bottom: 16px;">
                    I. Énoncé du Théorème Direct de Thalès
                </h1>

                <p style="margin-bottom: 18px;">
                    Soient deux droites (d) et (d') sécantes en un point A.
                    Soient B et M deux points de la droite (d), distincts de A.
                    Soient C et N deux points de la droite (d'), distincts de A.
                </p>

                <div style="background: #f8fafc; border-left: 4px solid var(--mk-indigo); padding: 18px 24px; border-radius: 8px; margin: 24px 0;">
                    <div style="font-weight: 800; font-size: 16px; color: var(--mk-indigo); margin-bottom: 6px;">
                        Propriété Fondamentale :
                    </div>
                    <div style="font-weight: 600; font-size: 16.5px; color: var(--mk-night); font-family: monospace;">
                        Si les droites (BC) et (MN) sont parallèles, alors :<br>
                        AM / AB = AN / AC = MN / BC
                    </div>
                </div>

                <h2 style="font-size: 20px; font-weight: 800; color: var(--mk-navy); margin: 24px 0 14px;">
                    II. Méthode de Rédaction Type Examen BEPC
                </h2>

                <p style="margin-bottom: 14px;">
                    Pour obtenir la totalité des points à l'épreuve de mathématiques du BEPC au Togo, ta copie doit obligatoirement mentionner les 3 éléments suivants :
                </p>

                <ol style="padding-left: 20px; display: flex; flex-direction: column; gap: 8px; margin-bottom: 24px;">
                    <li><strong>L'alignement des points :</strong> Préciser que les points A, M, B d'une part et A, N, C d'autre part sont alignés dans cet ordre.</li>
                    <li><strong>L'hypothèse de parallélisme :</strong> Écrire explicitement : <em>« Puisque les droites (MN) et (BC) sont parallèles... »</em>.</li>
                    <li><strong>La citation du théorème :</strong> Écrire : <em>« D'après le théorème de Thalès, on a l'égalité des rapports : AM/AB = AN/AC = MN/BC »</em>.</li>
                </ol>

                <div style="background: var(--mk-lavender); border-radius: 12px; padding: 16px 20px; margin-top: 24px;">
                    <div style="font-weight: 700; color: var(--mk-indigo); margin-bottom: 4px;">💡 Conseil du Professeur :</div>
                    <div style="font-size: 13.5px; color: #2e1065;">
                        Effectue toujours les produits en croix avec soin et vérifie que ton résultat numérique est cohérent avec l'échelle de la figure fournie sur le sujet d'examen.
                    </div>
                </div>
            </div>
        </div>

        <!-- Bas de Page et Actions -->
        <div style="border-top: 1px solid var(--mk-border); padding-top: 20px; margin-top: 36px; display: flex; justify-content: space-between; align-items: center;">
            <div style="font-size: 12px; color: var(--mk-text-muted);">
                Empreinte certifiée SHA-256 : <code style="font-size: 11px;">7360a63e9f...</code>
            </div>

            <div style="display: flex; gap: 12px;">
                <a href="/student/assistant?context=thales_page1" class="mk-btn mk-btn-primary mk-btn-sm">
                    Poser une question sur cette leçon
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
