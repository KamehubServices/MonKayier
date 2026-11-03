@extends('layouts.student')

@section('title', 'Tableau de bord élève — MonKayier')

@section('content')
{{-- Barre de commande et question rapide à l'IA --}}
<div class="cmd" style="margin-bottom: 20px;">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#4F46E5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3l1.9 5.1L19 10l-5.1 1.9L12 17l-1.9-5.1L5 10l5.1-1.9L12 3Z"></path><path d="M19 17v4M17 19h4"></path></svg>
    <span class="mu" style="flex:1;font-size:14.5px">Demande à ton assistant : un résumé, un quiz, une explication…</span>
    <a class="bt" href="/student/assistant" style="padding: 9px 18px; font-size: 13.5px;">Demander</a>
</div>

{{-- Blocs d'action prioritaires réduits et 100% dynamiques (Séance du jour & Progression) --}}
<div class="g2" style="margin-bottom: 20px; grid-template-columns: 1.8fr 1fr; gap: 18px;">
    {{-- Carte Séance du Jour (Couleurs du Logo MonKayier sur fond blanc) --}}
    <div class="c" style="padding: 22px 26px; gap: 12px; justify-content: space-between; background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 20px; color: #0E1022;">
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-wrap: wrap;">
            <span class="lb" style="font-size: 11px; color: #64748B;">Séance prioritaire · Coeff. 4 · {{ $dailySession['duration'] ?? '25 min' }}</span>
            <span class="tg" style="background: rgba(63, 169, 245, 0.12); color: #0284c7; font-size: 10.5px; padding: 4px 10px; border: 1px solid rgba(63, 169, 245, 0.3);">
                {{ $dailySession['subject'] ?? 'Mathématiques' }}
            </span>
        </div>
        
        <h2 style="font-size: 24px; font-weight: 850; line-height: 1.25; margin: 0; color: #14325c;">
            Bonjour {{ $student->first_name ?? 'Kodjo' }} · On révise « {{ Str::limit($dailySession['course_title'] ?? $stats['priority_topic'], 45) }} » ?
        </h2>

        <div class="mu" style="font-size: 13.5px; line-height: 1.45; color: #64748B;">
            Séance adaptée à ton niveau actuel en <b style="color: #14325c;">{{ $student->class_name ?? 'Terminale D' }}</b> avec l'IA pédagogique ({{ $dailySession['duration'] ?? '25 minutes' }} d'entraînement ciblé).
        </div>

        <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 4px;">
            <a class="bt l" href="{{ route('student.chat', array_filter(['course' => $dailySession['resource_id'] ?? ($dailySession['course_id'] ?? null), 'subject' => $dailySession['subject_id'] ?? null])) }}" style="padding: 10px 20px; font-size: 13.5px; font-weight: 800; background: linear-gradient(135deg, #14325c 0%, #1e4a84 100%); color: #FFFFFF; border: 1.5px solid #14325c;">
                Lancer la séance · {{ $dailySession['duration'] ?? '25 min' }}
            </a>
            <a class="bt" href="/student/parcours" style="background: #F1F5F9; border: 1px solid #CBD5E1; color: #14325c; padding: 10px 18px; font-size: 13.5px;">
                Mon parcours →
            </a>
        </div>
    </div>

    {{-- Carte Progression du Parcours (Couleurs du Logo MonKayier sur fond blanc) --}}
    <div class="c" style="padding: 22px 20px; align-items: center; justify-content: center; text-align: center; gap: 10px; background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 20px; color: #0E1022;">
        <div style="width: 105px; height: 105px; border-radius: 50%; background: conic-gradient(#3fa9f5 0 {{ $stats['overall_mastery'] ?? 62 }}%, #E2E8F0 0); display: flex; align-items: center; justify-content: center; flex: none;">
            <div style="width: 78px; height: 78px; border-radius: 50%; background: #14325c; display: flex; flex-direction: column; align-items: center; justify-content: center; border: 1px solid #E2E8F0;">
                <b class="hd" style="font-size: 24px; color: #fff; line-height: 1;">{{ $stats['overall_mastery'] ?? 62 }}%</b>
                <span class="mu" style="font-size: 10px; color: #3fa9f5;">maîtrisé</span>
            </div>
        </div>
        <b class="hd" style="font-size: 15px; margin-top: 2px; color: #14325c;">Parcours {{ $student->class_name ?? 'Terminale D' }}</b>
        <span class="mu" style="font-size: 12px; color: #64748B;">{{ $stats['weak_count'] ?? 0 }} notion(s) prioritaire(s) à renforcer</span>
    </div>
</div>

{{-- Message flash succès si action réalisée --}}
@if(session('success'))
<div class="c" style="background:#EBFDF5;border-color:#A7F3D0;color:#065F46;padding:14px 18px;border-radius:16px;display:flex;align-items:center;gap:10px;margin-bottom:18px;">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
    <span><b>Félicitations !</b> {{ session('success') }}</span>
</div>
@endif

{{-- Grille des 4 widgets d'évaluation et de coaching --}}
<div class="g4">
    {{-- Widget 1 : Compétences --}}
    <div class="c" style="padding: 18px 20px;">
        <div class="r" style="margin-bottom: 6px;">
            <span class="lb">Compétences</span>
            <a href="/student/test-niveau" style="font-size:11.5px;font-weight:700;color:#4F46E5;text-decoration:none">Test</a>
        </div>
        <div style="display:flex;flex-direction:column;gap:6px">
            <div class="r"><span>Algèbre</span><b>{{ $competencies['algebre'] ?? 72 }} %</b></div>
            <div class="bar"><i style="width:{{ $competencies['algebre'] ?? 72 }}%"></i></div>
        </div>
        <div style="display:flex;flex-direction:column;gap:6px">
            <div class="r"><span>Analyse</span><b>{{ $competencies['analyse'] ?? 48 }} %</b></div>
            <div class="bar"><i style="width:{{ $competencies['analyse'] ?? 48 }}%"></i></div>
        </div>
        <div style="display:flex;flex-direction:column;gap:6px">
            <div class="r"><span>Géométrie</span><b>{{ $competencies['geometrie'] ?? 81 }} %</b></div>
            <div class="bar"><i style="width:{{ $competencies['geometrie'] ?? 81 }}%"></i></div>
        </div>
        <div style="display:flex;flex-direction:column;gap:6px">
            <div class="r"><span>Probabilités</span><b>{{ $competencies['probabilites'] ?? 35 }} %</b></div>
            <div class="bar"><i style="width:{{ $competencies['probabilites'] ?? 35 }}%"></i></div>
        </div>
        <span class="tg o" style="align-self:flex-start; margin-top: 4px; font-size: 10.5px;">{{ $stats['weak_count'] ?? 2 }} à renforcer</span>
    </div>

    {{-- Widget 2 : Régularité --}}
    <div class="c" style="padding: 18px 20px; justify-content: space-between;">
        <div>
            <span class="lb">Régularité</span>
            <b class="hd" style="font-size:30px; display:block; margin: 4px 0 8px 0;">{{ $stats['streak_days'] ?? 1 }} jour(s)</b>
            <div style="display:flex;gap:5px; margin-bottom: 10px;">
                @for($d = 1; $d <= 7; $d++)
                    <div style="flex:1;height:24px;border-radius:6px;background:{{ $d <= ($stats['streak_days'] ?? 1) ? '#4F46E5' : '#ECEEF6' }}" title="Jour {{ $d }}"></div>
                @endfor
            </div>
        </div>
        <div class="mu" style="font-size: 12px;">Séances validées cette semaine : <b>{{ $stats['sessions_done'] ?? 0 }}</b></div>
    </div>

    {{-- Widget 3 : Prochain Examen --}}
    <div class="c" style="padding: 18px 20px; justify-content: space-between;">
        <div>
            <span class="lb">Prochain Examen</span>
            <b class="hd" style="font-size:20px; display:block; margin: 4px 0 8px 0;">BAC 2 · Terminale D</b>
            <span class="tg o" style="font-size: 10.5px; align-self: flex-start;">Session Officielle Togo</span>
            <div class="mu" style="font-size: 12px; margin-top: 8px;">Conforme aux référentiels et coefficients du Ministère.</div>
        </div>
        <a class="bt g" href="/student/calendar" style="padding: 8px 14px; font-size: 12.5px; text-align: center; margin-top: 8px;">Voir le calendrier</a>
    </div>

    {{-- Widget 4 : Coach IA --}}
    <div class="c" style="padding: 18px 20px; justify-content: space-between;">
        <div>
            <span class="lb">Coach IA</span>
            <b style="font-size: 13.5px; display:block; margin: 4px 0 6px 0; color: #0E1022;">{{ $stats['coach_title'] ?? '« Priorité adaptative »' }}</b>
            <div class="mu" style="font-size: 12px; line-height: 1.45;">{{ $stats['coach_body'] ?? 'Programme d\'apprentissage personnalisé selon tes résultats récents.' }}</div>
        </div>
        <a class="bt" href="{{ route('student.chat', array_filter(['course' => $dailySession['resource_id'] ?? ($dailySession['course_id'] ?? null), 'subject' => $dailySession['subject_id'] ?? null])) }}" style="padding: 8px 14px; font-size: 12.5px; text-align: center; margin-top: 8px;">Démarrer</a>
    </div>
</div>
@endsection
