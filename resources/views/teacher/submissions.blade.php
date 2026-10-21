@extends('layouts.app')

@section('title', 'Copies & Notations · ' . $assignment->title)
@section('header-title', 'Notation des Devoirs')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- En-tête du Devoir -->
    <div style="background: linear-gradient(135deg, var(--mk-night) 0%, var(--mk-navy) 100%); border-radius: 18px; padding: 24px; color: #ffffff; display: flex; justify-content: space-between; align-items: center; box-shadow: var(--mk-shadow-lg);">
        <div>
            <div style="display: flex; gap: 8px; margin-bottom: 6px;">
                <span class="mk-badge" style="background: rgba(200, 243, 74, 0.2); color: var(--mk-lime);">
                    {{ $assignment->schoolClass?->label }} · {{ $assignment->assessment?->subject?->name }}
                </span>
            </div>
            <h1 style="font-size: 22px; font-weight: 800; color: #ffffff; margin: 0 0 6px 0;">
                {{ $assignment->title }}
            </h1>
            <p style="font-size: 13.5px; opacity: 0.85; margin: 0;">
                Date limite de remise : {{ $assignment->due_at ? $assignment->due_at->format('d/m/Y') : 'Non définie' }}
                · Barème : {{ $assignment->assessment?->totalPoints() ?? 10 }} points
            </p>
        </div>
        <div>
            <a href="/teacher/assignments" class="mk-btn mk-btn-outline" style="color: #ffffff; border-color: rgba(255, 255, 255, 0.3);">
                ← Retour aux devoirs
            </a>
        </div>
    </div>

    <!-- Tableau des copies remises -->
    <div class="mk-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h2 style="font-size: 18px; font-weight: 800; color: var(--mk-night); margin: 0;">
                Copies Remises par les Élèves ({{ $assignment->submissions->count() }})
            </h2>
        </div>

        @if($assignment->submissions->isNotEmpty())
            <div style="display: flex; flex-direction: column; gap: 14px;">
                @foreach($assignment->submissions as $sub)
                    <div style="border: 1px solid var(--mk-border); border-radius: 14px; padding: 18px; background: #ffffff; display: flex; justify-content: space-between; align-items: center; gap: 20px;">
                        <div style="flex: 1;">
                            <div style="display: flex; gap: 10px; align-items: center;">
                                <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--mk-lavender); color: var(--mk-indigo); display: flex; align-items: center; justify-content: center; font-weight: 800;">
                                    {{ strtoupper(substr($sub->student?->profile?->first_name ?? 'E', 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-size: 16px; font-weight: 800; color: var(--mk-night);">
                                        {{ $sub->student?->profile ? ($sub->student->profile->first_name . ' ' . $sub->student->profile->last_name) : $sub->student?->email }}
                                    </div>
                                    <div style="font-size: 12px; color: var(--mk-text-muted);">
                                        Remis le {{ $sub->submitted_at ? $sub->submitted_at->format('d/m/Y à H:i') : 'Récemment' }}
                                    </div>
                                </div>
                            </div>

                            @if($sub->teacher_feedback)
                                <div style="margin-top: 10px; font-size: 13px; color: var(--mk-text); background: var(--mk-bg-subtle); padding: 8px 12px; border-radius: 8px;">
                                    <strong>Appréciation :</strong> {{ $sub->teacher_feedback }}
                                </div>
                            @endif
                        </div>

                        <!-- Formulaire d'attribution de la note -->
                        <div style="min-width: 280px; background: var(--mk-bg-subtle); padding: 14px; border-radius: 12px;">
                            <form method="POST" action="/teacher/submissions/{{ $sub->id }}/grade" style="display: flex; flex-direction: column; gap: 10px;">
                                @csrf
                                <div style="display: flex; gap: 10px; align-items: center;">
                                    <label style="font-size: 13px; font-weight: 700; color: var(--mk-night); min-width: 50px;">
                                        Note :
                                    </label>
                                    <input type="number" step="0.5" min="0" max="20" name="final_score" value="{{ $sub->grade ? (float) $sub->grade->final_score : 10.0 }}" required style="width: 80px; padding: 6px 10px; border: 1.5px solid var(--mk-border); border-radius: 8px; font-weight: 800; text-align: center;">
                                    <span style="font-size: 13px; font-weight: 700; color: var(--mk-text-muted);">/ 20</span>
                                </div>
                                <input type="text" name="feedback" placeholder="Appréciation..." value="{{ $sub->teacher_feedback }}" style="width: 100%; padding: 6px 10px; border: 1.5px solid var(--mk-border); border-radius: 8px; font-size: 13px;">
                                <button type="submit" class="mk-btn mk-btn-primary mk-btn-sm" style="justify-content: center;">
                                    {{ $sub->status === 'graded' ? 'Mettre à jour la note' : 'Enregistrer la note' }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 36px; background: var(--mk-bg-subtle); border-radius: 12px;">
                <p style="color: var(--mk-text-muted); margin: 0;">Aucune copie remise par les élèves pour l'instant.</p>
            </div>
        @endif
    </div>

</div>
@endsection
