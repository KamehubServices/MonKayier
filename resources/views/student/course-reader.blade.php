@extends('layouts.student')

@section('title', $course->title . ' — Référentiel Officiel MonKayier')

@section('content')
<div id="courseReaderContainer">
    {{-- Barre supérieure de navigation & Mode Focus --}}
    <div class="r" style="margin-bottom: 16px; flex-wrap: wrap;">
        <a href="{{ route('student.library') }}" class="bt g" style="font-size: 13px; padding: 7px 16px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
            ← Retour à mes cours
        </a>

        <div style="display: flex; gap: 8px; align-items: center;">
            <button type="button" class="bt g" onclick="toggleZenMode()" id="zenToggleBtn" style="font-size: 13px; padding: 7px 16px; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                <span id="zenBtnText">Mode Focus (Plein écran)</span>
            </button>
            <a href="{{ route('student.chat', ['course' => $course->id]) }}" class="bt" style="font-size: 13px; padding: 7px 16px; text-decoration: none;">
                Poser une question à l'IA
            </a>
        </div>
    </div>

    {{-- En-tête du cours avec badges nationaux --}}
    <div class="c" style="padding: 24px 28px; margin-bottom: 20px;">
        <div style="display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 8px;">
            <span class="tg k" style="font-size: 11.5px;">Programme Officiel Togo (MEPST)</span>
            <span class="tg" style="font-size: 11.5px;">{{ $course->subject_name }}</span>
            <span class="tg" style="font-size: 11.5px; background: #ECEBFF; color: #4F46E5; font-weight: 700;">{{ $course->level_name }}</span>
            <span class="tg o" style="font-size: 11.5px;">Référentiel National</span>
        </div>
        <h1 style="font-size: 28px; font-weight: 800; margin: 0 0 8px 0; line-height: 1.25; color: #0E1022;">{{ $course->title }}</h1>
        <div class="mu" style="font-size: 14.5px; line-height: 1.55; max-width: 860px;">
            {{ $course->summary ?? "Ensemble didactique officiel comprenant les objectifs pédagogiques ministériels, les compétences exigibles au BAC et les notions clés." }}
        </div>
    </div>

    {{-- Triptyque Unifié : 3 Onglets Contextuels (Cours -> Exercices -> Examens) --}}
    <div style="display: flex; align-items: center; gap: 8px; background: #fff; border: 1px solid #E8EAF2; padding: 6px 10px; border-radius: 20px; width: fit-content; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(14,16,34,0.04); flex-wrap: wrap;">
        <button type="button" class="pill on" id="tabBtn1" onclick="switchTripTab(1)" style="border:none; cursor:pointer; font-size: 13.5px; padding: 10px 18px; display:inline-flex; align-items:center; gap:8px;">
            1. Cours & Synthèse
        </button>
        <button type="button" class="pill" id="tabBtn2" onclick="switchTripTab(2)" style="border:none; cursor:pointer; font-size: 13.5px; padding: 10px 18px; display:inline-flex; align-items:center; gap:8px; background:transparent;">
            2. Exercices & Quiz
            <span style="background: #4F46E5; color: #fff; padding: 1px 7px; border-radius: 999px; font-size: 11px; font-weight: 700;">3</span>
        </button>
        <button type="button" class="pill" id="tabBtn3" onclick="switchTripTab(3)" style="border:none; cursor:pointer; font-size: 13.5px; padding: 10px 18px; display:inline-flex; align-items:center; gap:8px; background:transparent;">
            3. Annales BAC 2 Togo
            <span style="background: #D4F25C; color: #0E1022; padding: 1px 7px; border-radius: 999px; font-size: 11px; font-weight: 700;">{{ $relatedExams->count() }}</span>
        </button>
    </div>

    {{-- SECTION 1 : COURS & SYNTHÈSE OFFICIELLE --}}
    <div id="tripTab1" class="trip-section" style="display: block;">
        <div class="g2" style="grid-template-columns: 2fr 1fr; align-items: start; gap: 24px;">
            <div style="display: flex; flex-direction: column; gap: 20px;">
                {{-- Structure des chapitres et objectifs --}}
                <div class="c">
                    <div class="r" style="margin-bottom: 14px;">
                        <div>
                            <span class="lb">Structure du Programme</span>
                            <h2 style="font-size: 18px; font-weight: 700; margin: 0;">Plan & Chapitres Officiels</h2>
                        </div>
                        <span class="tg">{{ $chapters->count() > 0 ? $chapters->count() . ' chapitres' : 'Module officiel' }}</span>
                    </div>

                    @if($chapters->count() > 0)
                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            @foreach($chapters as $chap)
                                <div style="padding: 14px 16px; border-radius: 14px; background: #FAFBFD; border: 1px solid #E8EAF2; display: flex; flex-direction: column; gap: 6px;">
                                    <div class="r">
                                        <b style="font-size: 15px; color: #0E1022;">
                                            Chapitre {{ $loop->iteration }} · {{ $chap->title }}
                                        </b>
                                        <span class="tg k" style="font-size: 10.5px;">Validé MEPST</span>
                                    </div>
                                    @if(!empty($chap->objective_desc))
                                        <div class="mu" style="font-size: 13px; line-height: 1.45;">
                                            <b style="color: #4F46E5;">Compétence visée au BAC :</b> {{ $chap->objective_desc }}
                                        </div>
                                    @else
                                        <div class="mu" style="font-size: 13px;">{{ $chap->description }}</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="padding: 16px; border-radius: 12px; background: #f8fafc; border: 1px dashed #CBD6E6;">
                            <div class="mu" style="font-size: 13.5px; line-height: 1.5;">
                                Ce module d'enseignement officiel du Ministère est structuré sous forme de fiche directoire d'objectifs et de notions à acquérir pour les examens nationaux.
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Fiche de synthèse & Notions clés --}}
                <div class="c">
                    <span class="lb">Contenu Didactique</span>
                    <h2 style="font-size: 18px; font-weight: 700; margin: 0 0 12px 0;">Synthèse du Cours & Formules Clés</h2>
                    
                    <div style="line-height: 1.7; font-size: 14px; color: #1E293B; background: #FCFCFD; padding: 20px; border-radius: 14px; border: 1px solid #E8EAF2;">
                        @if($version && !empty($version->content_text))
                            <div style="white-space: pre-line;">{!! nl2br(e($version->content_text)) !!}</div>
                        @else
                            <p style="margin: 0 0 12px 0;">
                                Ce cours est rigoureusement conforme au programme officiel togolais de <b>{{ $course->level_name }}</b> en <b>{{ $course->subject_name }}</b>.
                            </p>
                            <p style="margin: 0 0 12px 0;">
                                <b>Points incontournables pour l'examen :</b><br>
                                • Maîtriser les définitions fondamentales et le domaine de validité.<br>
                                • Connaître les théorèmes et propriétés remarquables avec leurs conditions d'application.<br>
                                • Soigner la rédaction méthodologique (hypothèse -> règle -> calcul -> conclusion encadrée).
                            </p>
                        @endif
                    </div>

                    <div style="margin-top: 16px; display: flex; gap: 10px; flex-wrap: wrap;">
                        <button type="button" class="bt l" onclick="switchTripTab(2)">
                            Passer aux Exercices d'application →
                        </button>
                        <button type="button" class="bt g" onclick="switchTripTab(3)">
                            Voir les sujets du BAC associés →
                        </button>
                    </div>
                </div>
            </div>

            {{-- Fiche latérale : Origine & Source officielle --}}
            <div style="display: flex; flex-direction: column; gap: 20px;">
                <div class="c dk">
                    <span class="lb">Fiche de Référence</span>
                    <b class="hd" style="font-size: 17px; margin: 6px 0 12px 0; display: block;">Informations Officielles</b>

                    <div style="display: flex; flex-direction: column; gap: 10px; font-size: 13px;">
                        <div class="r" style="border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 6px;">
                            <span style="opacity: 0.8;">Discipline :</span>
                            <b>{{ $course->subject_name }}</b>
                        </div>
                        <div class="r" style="border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 6px;">
                            <span style="opacity: 0.8;">Classe :</span>
                            <b>{{ $course->level_name }}</b>
                        </div>
                        <div class="r" style="border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 6px;">
                            <span style="opacity: 0.8;">Origine :</span>
                            <b>MEPST Togo</b>
                        </div>
                        <div class="r">
                            <span style="opacity: 0.8;">Statut :</span>
                            <span class="tg k" style="font-size: 10.5px;">Validé</span>
                        </div>
                    </div>

                    @if(!empty($course->curriculum_source_url))
                        <div style="margin-top: 16px; border-top: 1px solid rgba(255,255,255,0.15); padding-top: 12px;">
                            <a href="{{ $course->curriculum_source_url }}" target="_blank" class="bt l" style="display: block; text-align: center; font-size: 12.5px;">
                                Consulter sur ecole.gouv.tg ↗
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION 2 : EXERCICES & QUIZ D'ENTRAÎNEMENT DIRECT --}}
    <div id="tripTab2" class="trip-section" style="display: none;">
        <div class="c" style="padding: 24px; margin-bottom: 20px;">
            <div class="r" style="margin-bottom: 16px; flex-wrap: wrap;">
                <div>
                    <span class="lb">Entraînement Guidé</span>
                    <h2 style="font-size: 20px; font-weight: 700; margin: 0;">Quiz & Questions d'Examen — {{ $course->title }}</h2>
                    <div class="mu" style="font-size: 13.5px; margin-top: 4px;">Réponds aux 3 questions contextuelles pour évaluer ta maîtrise immédiate.</div>
                </div>
                <span class="tg k">Barème APC Officiel</span>
            </div>

            <div style="display: flex; flex-direction: column; gap: 20px;">
                @foreach($exercises as $ex)
                    <div class="c" style="background: #FAFAFD; border: 1.5px solid #E2E6F2; padding: 20px; border-radius: 18px;" id="qcard-{{ $ex['id'] }}">
                        <div class="r" style="margin-bottom: 12px;">
                            <span class="tg" style="font-weight: 700;">Question {{ $loop->iteration }} / {{ count($exercises) }}</span>
                            <span class="mu" style="font-size: 12px;">Compétence BAC Togo</span>
                        </div>

                        <b style="font-size: 15.5px; line-height: 1.45; color: #0E1022; display: block; margin-bottom: 14px;">
                            {{ $ex['question'] }}
                        </b>

                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            @foreach($ex['options'] as $optIdx => $optText)
                                <label style="display: flex; align-items: center; gap: 10px; padding: 12px 14px; border-radius: 12px; background: #fff; border: 1.5px solid #CBD6E6; cursor: pointer; transition: all 0.2s ease;" class="opt-label" id="lbl-{{ $ex['id'] }}-{{ $optIdx }}">
                                    <input type="radio" name="q_{{ $ex['id'] }}" value="{{ $optIdx }}" onchange="validateExercise({{ $ex['id'] }}, {{ $optIdx }}, {{ $ex['correct'] }}, '{{ addslashes($ex['explanation']) }}')" style="accent-color: #4F46E5;">
                                    <span style="font-size: 14px; font-weight: 500;">{{ $optText }}</span>
                                </label>
                            @endforeach
                        </div>

                        {{-- Bloc d'explication pédagogique --}}
                        <div id="feedback-{{ $ex['id'] }}" style="display: none; margin-top: 14px; padding: 14px 16px; border-radius: 12px; font-size: 13.5px; line-height: 1.5;"></div>
                    </div>
                @endforeach
            </div>

            <div style="margin-top: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                <div class="mu" style="font-size: 13.5px;">Besoin d'approfondir sur un calcul précis ?</div>
                <div style="display: flex; gap: 10px;">
                    <a href="{{ route('student.exercises', ['subject' => $course->subject_id]) }}" class="bt g">
                        Ouvrir le générateur d'exercices complet →
                    </a>
                    <button type="button" class="bt l" onclick="switchTripTab(3)">
                        Passer aux épreuves réelles du BAC →
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION 3 : ANNALES BAC 2 TOGO ASSOCIÉES --}}
    <div id="tripTab3" class="trip-section" style="display: none;">
        <div class="c" style="padding: 24px; margin-bottom: 20px;">
            <div class="r" style="margin-bottom: 16px; flex-wrap: wrap;">
                <div>
                    <span class="lb">Épreuves Réelles du BAC</span>
                    <h2 style="font-size: 20px; font-weight: 700; margin: 0;">Annales Officielles Togo — {{ $course->subject_name }} ({{ $course->level_name }})</h2>
                    <div class="mu" style="font-size: 13.5px; margin-top: 4px;">Sujets tombés lors des dernières sessions nationales portant sur cette discipline.</div>
                </div>
                <a href="{{ route('student.examens', ['subject_id' => $course->subject_id]) }}" class="tg k" style="text-decoration:none; font-weight:700;">
                    Voir tout le catalogue ({{ $relatedExams->count() }})
                </a>
            </div>

            @if($relatedExams->count() > 0)
                <div class="g3" style="grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px;">
                    @foreach($relatedExams as $re)
                        <div class="c" style="padding: 16px; border-left: 4px solid #4F46E5; justify-content: space-between; gap: 10px;">
                            <div>
                                <span class="tg" style="font-size: 11px; margin-bottom: 6px;">Session Officielle</span>
                                <b style="font-size: 14.5px; line-height: 1.35; color: #0E1022; display: block;">
                                    {{ Str::limit($re->title, 70) }}
                                </b>
                                <div class="mu" style="font-size: 12px; margin-top: 4px;">Format PDF original · Togo</div>
                            </div>
                            
                            <div class="r" style="margin-top: 8px;">
                                @if(!empty($re->file_id))
                                    <button type="button" onclick="openReaderImmersion('{{ route('student.examens.preview', $re->file_id) }}', '{{ addslashes($re->title) }}', '{{ route('student.examens.download', $re->file_id) }}')" class="bt l" style="font-size: 12px; padding: 6px 12px; border: none; cursor: pointer;">
                                        Prévisualiser en Immersion 👁
                                    </button>
                                    <a href="{{ route('student.examens.download', $re->file_id) }}" class="bt g" style="font-size: 12px; padding: 6px 12px; text-decoration: none;">
                                        Télécharger
                                    </a>
                                @else
                                    <span class="mu" style="font-size: 12px;">Archivé</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 32px 20px;">
                    <div class="mu">Toutes les annales générales de cette discipline sont disponibles dans la rubrique Examens nationaux.</div>
                    <a href="{{ route('student.examens') }}" class="bt" style="margin-top: 12px; display: inline-block;">Consulter les épreuves</a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
// Gestion des 3 onglets du triptyque unifié
function switchTripTab(tabNumber) {
    for (let i = 1; i <= 3; i++) {
        const sec = document.getElementById('tripTab' + i);
        const btn = document.getElementById('tabBtn' + i);
        if (sec && btn) {
            if (i === tabNumber) {
                sec.style.display = 'block';
                btn.className = 'pill on';
                btn.style.background = '#0E1022';
                btn.style.color = '#ffffff';
            } else {
                sec.style.display = 'none';
                btn.className = 'pill';
                btn.style.background = 'transparent';
                btn.style.color = '#5A5F78';
            }
        }
    }
}

// Validation interactive des questions d'entraînement
function validateExercise(qId, selectedIdx, correctIdx, explanation) {
    const feedbackBox = document.getElementById('feedback-' + qId);
    if (!feedbackBox) return;

    if (selectedIdx === correctIdx) {
        feedbackBox.style.display = 'block';
        feedbackBox.style.background = '#EBFDF5';
        feedbackBox.style.border = '1px solid #A7F3D0';
        feedbackBox.style.color = '#065F46';
        feedbackBox.innerHTML = '<b>Bonne réponse !</b> ' + explanation;
    } else {
        feedbackBox.style.display = 'block';
        feedbackBox.style.background = '#FFF4F0';
        feedbackBox.style.border = '1px solid #FECACA';
        feedbackBox.style.color = '#991B1B';
        feedbackBox.innerHTML = '<b>Attention :</b> ' + explanation;
    }
}

// Mode Focus Plein Écran (Zen Reader)
let isZenMode = false;
function toggleZenMode() {
    isZenMode = !isZenMode;
    const sb = document.querySelector('.sb');
    const mainWrap = document.querySelector('.lay .w');
    const btnText = document.getElementById('zenBtnText');

    if (isZenMode) {
        if (sb) sb.style.display = 'none';
        if (mainWrap) mainWrap.style.paddingLeft = '0';
        if (btnText) btnText.innerText = 'Quitter le mode focus';
    } else {
        if (sb) sb.style.display = '';
        if (mainWrap) mainWrap.style.paddingLeft = '';
        if (btnText) btnText.innerText = 'Mode Focus (Plein écran)';
    }
}

// Lecteur d'Immersion In-App pour les annales liées
function openReaderImmersion(url, title, downloadUrl) {
    const modal = document.getElementById('readerImmersionModal');
    const iframe = document.getElementById('readerImmersionIframe');
    document.getElementById('readerImmersionTitle').textContent = title || 'Épreuve Officielle';
    document.getElementById('readerImmersionDownloadBtn').href = downloadUrl || '#';
    iframe.src = url + '#toolbar=1&navpanes=0&scrollbar=1';
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeReaderImmersion() {
    const modal = document.getElementById('readerImmersionModal');
    const iframe = document.getElementById('readerImmersionIframe');
    iframe.src = 'about:blank';
    modal.style.display = 'none';
    document.body.style.overflow = '';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('readerImmersionModal');
        if (modal && modal.style.display === 'flex') {
            closeReaderImmersion();
        }
    }
});
</script>

{{-- MODAL D'IMMERSION IN-APP SANS OUVERTURE EXTERNE --}}
<div id="readerImmersionModal" style="display: none; position: fixed; inset: 0; z-index: 99999; background: #0E1022; flex-direction: column; width: 100vw; height: 100vh;">
    <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px 18px; background: #14172B; border-bottom: 1px solid rgba(255, 255, 255, 0.1); flex-wrap: wrap; gap: 10px; flex-shrink: 0;">
        <div style="display: flex; align-items: center; gap: 12px; min-width: 0; flex: 1;">
            <button type="button" onclick="closeReaderImmersion()" 
                    style="display: inline-flex; align-items: center; gap: 6px; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.18); color: #FFFFFF; font-size: 12.5px; font-weight: 750; padding: 6px 14px; border-radius: 8px; cursor: pointer;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
                <span>Fermer (Échap)</span>
            </button>
            <div style="width: 1px; height: 22px; background: rgba(255, 255, 255, 0.15);"></div>
            <span id="readerImmersionTitle" style="font-size: 14px; font-weight: 800; color: #FFFFFF; letter-spacing: -0.01em; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"></span>
        </div>
        <div style="display: flex; align-items: center; gap: 8px; flex-shrink: 0;">
            <a id="readerImmersionDownloadBtn" href="#" class="bt g" style="font-size: 12px; padding: 6px 14px; text-decoration: none; color: #fff; background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.2);">
                Télécharger le PDF
            </a>
            <a href="{{ route('student.chat', ['course' => $course->id]) }}" class="bt" style="font-size: 12px; padding: 6px 14px; text-decoration: none; background: #D4F25C; color: #0E1022;">
                Réviser avec l'IA →
            </a>
        </div>
    </div>
    <div style="flex: 1; min-height: 0; width: 100%; height: 100%; position: relative; background: #525659;">
        <iframe id="readerImmersionIframe" src="about:blank" style="width: 100%; height: 100%; border: none; display: block;"></iframe>
    </div>
</div>
@endsection
