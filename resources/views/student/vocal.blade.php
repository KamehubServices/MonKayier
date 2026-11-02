@extends('layouts.student')

@section('title', 'Assistant Vocal Neurale IA — MonKayier')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js"></script>

<div class="r" style="margin-bottom:10px;flex-wrap:wrap;gap:10px">
    <div>
        <a href="{{ route('student.chat') }}" style="color:#4F46E5;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:6px;font-size:13.5px">
            ← Revenir à l'Atelier Cours & Révision IA
        </a>
    </div>
    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
        <label for="vocalCourseSelect" style="font-size:12px;font-weight:700;color:#475569;">Chapitre actif :</label>
        <select id="vocalCourseSelect" onchange="onVocalCourseChange(this.value)" style="padding:6px 12px;border-radius:10px;border:1.5px solid #CBD5E1;background:#FFFFFF;font-size:12.5px;font-weight:700;color:#0E1022;max-width:340px;">
            @if(isset($courses) && count($courses) > 0)
                @foreach($courses as $c)
                    <option value="{{ $c->id }}" data-title="{{ $c->title }}" data-subject="{{ $c->subject_code }}">
                        [{{ $c->subject_code }}] {{ $c->title }}
                    </option>
                @endforeach
            @else
                <option value="1" data-title="Limites, continuité et asymptotes" data-subject="MATHS">[MATHS] Limites, continuité et asymptotes</option>
            @endif
        </select>
        <button type="button" id="btnContinuousLoop" onclick="toggleContinuousVocalLoop()" class="tg" style="background:#0E1022;color:#D4F25C;font-weight:800;border:none;cursor:pointer;padding:7px 12px;" title="Réactiver automatiquement le micro dès que le Tuteur IA a fini de parler">
            Conversation Mains-Libres : Active
        </button>
    </div>
</div>

<div class="g2" style="grid-template-columns:1.35fr 1fr;align-items:start">
    <!-- Zone d'interaction vocale centrale -->
    <div class="c" style="align-items:stretch;padding:28px">
        <div class="r">
            <span class="lb" id="oralThemeBadge">Dialogue Vocal IA · Programme Officiel Terminale D</span>
            <span id="listeningBadge" class="tg k" style="transition:all 0.3s ease">Prêt à t'écouter</span>
        </div>

        <div id="micRing" class="vocal-orb-wrap" style="align-self:center;position:relative;width:260px;height:260px;margin:14px 0;cursor:pointer" onclick="toggleMic()">
            <div class="ring-pulse r1" style="position:absolute;inset:0px;border-radius:50%;border:2px solid rgba(79,70,229,0.55);transition:all 0.4s ease"></div>
            <div class="ring-pulse r2" style="position:absolute;inset:30px;border-radius:50%;border:2px solid rgba(79,70,229,0.41);transition:all 0.4s ease"></div>
            <div class="ring-pulse r3" style="position:absolute;inset:60px;border-radius:50%;border:2px solid rgba(79,70,229,0.27);transition:all 0.4s ease"></div>
            <div class="ring-pulse r4" style="position:absolute;inset:88px;border-radius:50%;border:2px solid rgba(79,70,229,0.13);transition:all 0.4s ease"></div>
            <div id="micCenter" style="position:absolute;inset:84px;border-radius:50%;background:#4F46E5;display:flex;align-items:center;justify-content:center;box-shadow:0 12px 30px rgba(79,70,229,0.4);transition:all 0.25s ease">
                <svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="#D4F25C" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                    <rect x="9" y="3" width="6" height="12" rx="3"></rect>
                    <path d="M5 11a7 7 0 0 0 14 0M12 18v3"></path>
                </svg>
            </div>
        </div>

        <div style="text-align:center">
            <b id="oralQuestionText" class="hd" style="font-size:19px;line-height:1.4;display:block;max-width:560px;margin:0 auto">
                « Bonjour Kodjo. Clique sur le microphone pour me poser une question à voix haute ou tester tes connaissances sur ce chapitre. »
            </b>
            <div id="oralInstruction" class="mu" style="margin-top:6px">
                Clique sur l'orbe central pour parler, ou choisis une action rapide ci-dessous.
            </div>
        </div>

        <!-- Raccourcis d'interaction vocale en direct avec l'IA -->
        <div style="display:flex;gap:8px;justify-content:center;flex-wrap:wrap;margin-top:12px">
            <span class="tg" style="cursor:pointer;background:#F1F5F9;border:1px solid #CBD5E1" onclick="sendVocalPrompt('Interroge-moi à l\'oral avec une question flash sur ce chapitre.')">M'interroger à l'oral</span>
            <span class="tg" style="cursor:pointer;background:#F1F5F9;border:1px solid #CBD5E1" onclick="sendVocalPrompt('Explique-moi simplement la notion clé et le théorème principal de ce cours.')">Expliquer le théorème clé</span>
            <span class="tg" style="cursor:pointer;background:#F1F5F9;border:1px solid #CBD5E1" onclick="sendVocalPrompt('Quelles sont les erreurs classiques à éviter au BAC sur ce chapitre ?')">Pièges fréquents au BAC</span>
            <span class="tg o" style="cursor:pointer" onclick="sendVocalPrompt('Donne-moi un indice méthodologique sans me donner la réponse finale.')">Demander un indice</span>
        </div>

        <!-- Zone de saisie directe multi-lignes auto-extensible -->
        <form onsubmit="event.preventDefault(); submitVocalTextInput();" style="display:flex;gap:8px;margin-top:14px;align-items:flex-end">
            <textarea id="vocalTextInput" rows="1" class="mk-auto-textarea" placeholder="Parle au micro ou écris ta réponse / ta question orale ici..." 
                      onkeydown="if(event.key==='Enter' && !event.shiftKey){ event.preventDefault(); submitVocalTextInput(); }"
                      style="flex:1;padding:11px 14px !important;border-radius:12px;border:1.5px solid #CBD5E1;font-size:13.5px;color:#0E1022;background:#F8FAFC"></textarea>
            <button type="submit" class="bt p" style="border:none;cursor:pointer;padding:11px 18px;flex:none">Envoyer</button>
        </form>

        <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;margin-top:12px">
            <button type="button" class="bt g" onclick="repeatLastAiSpeech()" style="border:none;cursor:pointer">Réécouter la réponse</button>
            <button type="button" class="bt g" onclick="window.mkStopSpeech && window.mkStopSpeech()" style="border:none;cursor:pointer">Arrêter la voix</button>
            <button type="button" class="bt g" onclick="sendVocalPrompt('Reformule ton explication plus simplement avec un exemple concret.')" style="border:none;cursor:pointer">Reformuler</button>
        </div>
    </div>

    <!-- Voix Neurales, Transcription formatée et Contexte -->
    <div style="display:flex;flex-direction:column;gap:16px">
        <div class="c">
            <span class="lb">Voix neurale humaine de mon tuteur</span>
            <div id="voice_kodjo" class="in" style="border:2px solid #4F46E5;background:#F3F2FF;cursor:pointer" onclick="selectVoice('kodjo')">
                <b>Coach Kodjo (Voix Masculine Multilingue)</b>
                <div class="mu">Voix chaleureuse, naturelle et dynamique · Haute définition neurale</div>
            </div>
            <div id="voice_afi" class="in" style="cursor:pointer;border:1.5px solid #CBD6E6;background:#fff" onclick="selectVoice('afi')">
                <b>Tutrice Afi (Voix Féminine Multilingue)</b>
                <div class="mu">Voix claire, bienveillante et pédagogique · Haute définition neurale</div>
            </div>
            <div id="voice_mawuli" class="in" style="cursor:pointer;border:1.5px solid #CBD6E6;background:#fff" onclick="selectVoice('mawuli')">
                <b>Prof Mawuli (Voix Masculine Posée)</b>
                <div class="mu">Voix calme et académique · Idéale pour les démonstrations</div>
            </div>
        </div>

        <div class="c" style="max-height:420px;display:flex;flex-direction:column">
            <div class="r">
                <span class="lb">Transcription structurée en temps réel</span>
                <span class="mu" style="font-size:11px">Formules & synthèse vocale synchronisées</span>
            </div>
            <div id="transcriptionLog" style="display:flex;flex-direction:column;gap:10px;overflow-y:auto;padding-right:4px;max-height:340px">
                <div style="background:#ECEBFF;padding:12px 16px;border-radius:14px;font-size:13px;line-height:1.55;color:#0E1022">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px">
                        <b style="color:#4F46E5">Tuteur IA MonKayier</b>
                        <button type="button" class="mk-tts-btn" data-speech="Bonjour Kodjo. Je suis prêt pour notre séance orale sur le programme officiel de Terminale D. Clique sur le micro ou choisis un sujet pour commencer." onclick="window.mkSpeakHuman(this.dataset.speech, this, currentVoice)">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon><path d="M15.54 8.46a5 5 0 0 1 0 7.07"></path></svg>
                            <span>Écouter</span>
                        </button>
                    </div>
                    <div class="mk-ai-formatted">
                        <p class="mk-ai-p">Bonjour Kodjo. Je suis prêt pour notre séance orale sur le programme officiel de Terminale D. Clique sur le micro ou choisis une question pour commencer.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let isListening = false;
let isContinuousVocalLoop = true;
let vocalSilenceTimer = null;
let currentVoice = localStorage.getItem('mk_tutor_voice') || 'kodjo';
let currentVocalCourseId = null;
let currentVocalCourseContext = '';
let lastAiSpokenText = "Bonjour Kodjo. Clique sur le microphone pour me poser une question à voix haute ou tester tes connaissances sur ce chapitre.";
let vocalRecognition = null;

function toggleContinuousVocalLoop() {
    isContinuousVocalLoop = !isContinuousVocalLoop;
    const btn = document.getElementById('btnContinuousLoop');
    if (btn) {
        if (isContinuousVocalLoop) {
            btn.style.background = '#0E1022';
            btn.style.color = '#D4F25C';
            btn.innerText = 'Conversation Mains-Libres : Active';
        } else {
            btn.style.background = '#E2E8F0';
            btn.style.color = '#475569';
            btn.innerText = 'Conversation Mains-Libres : Manuelle';
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    highlightSelectedVoice(currentVoice);
    const sel = document.getElementById('vocalCourseSelect');
    if (sel && sel.value) {
        onVocalCourseChange(sel.value);
    }
});

function onVocalCourseChange(courseId) {
    currentVocalCourseId = courseId;
    const sel = document.getElementById('vocalCourseSelect');
    const opt = sel ? sel.options[sel.selectedIndex] : null;
    const title = opt ? opt.getAttribute('data-title') : 'Chapitre au programme';
    const subject = opt ? opt.getAttribute('data-subject') : 'MATHS';
    const badge = document.getElementById('oralThemeBadge');
    if (badge) badge.innerText = 'Dialogue Vocal IA · ' + subject + ' — ' + title;

    fetch('/student/courses/' + encodeURIComponent(courseId) + '/sheet', {
        headers: { 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (data && data.success && data.ai_context) {
            currentVocalCourseContext = data.ai_context;
        }
    })
    .catch(() => {});
}

function highlightSelectedVoice(v) {
    ['kodjo', 'afi', 'mawuli'].forEach(key => {
        const el = document.getElementById('voice_' + key);
        if (!el) return;
        if (key === v) {
            el.style.border = '2px solid #4F46E5';
            el.style.background = '#F3F2FF';
        } else {
            el.style.border = '1.5px solid #CBD6E6';
            el.style.background = '#FFFFFF';
        }
    });
}

function selectVoice(v) {
    currentVoice = v;
    localStorage.setItem('mk_tutor_voice', v);
    highlightSelectedVoice(v);

    const intros = {
        kodjo: "Bonjour Kodjo, ici Coach Kodjo. Je t'accompagne à l'oral avec enthousiasme pour réussir ton Baccalauréat.",
        afi: "Bonjour Kodjo, je suis Afi, ta tutrice IA. Travaillons ensemble chaque étape de ce chapitre avec clarté.",
        mawuli: "Bonjour Kodjo, ici le professeur Mawuli. Concentrons-nous sur la rigueur scientifique de tes démonstrations."
    };
    speakHumanVocal(intros[v] || intros.kodjo);
}

function setOrbState(state) {
    const badge = document.getElementById('listeningBadge');
    const center = document.getElementById('micCenter');
    const instruction = document.getElementById('oralInstruction');
    const ring = document.getElementById('micRing');

    if (state === 'listening') {
        badge.innerText = 'Écoute vocale en cours...';
        badge.className = 'tg o';
        center.style.background = '#EF4444';
        instruction.innerText = 'Parle maintenant, je transcris ta voix en direct...';
        if (ring) ring.classList.add('is-speaking');
    } else if (state === 'thinking') {
        badge.innerText = 'Analyse pédagogique...';
        badge.className = 'tg';
        center.style.background = '#0E1022';
        instruction.innerText = 'Consultation de la fiche de cours officielle...';
    } else if (state === 'speaking') {
        badge.innerText = 'Le Tuteur IA te répond';
        badge.className = 'tg k';
        center.style.background = '#059669';
        instruction.innerText = 'Écoute la réponse neurale ou clique pour interrompre.';
        if (ring) ring.classList.add('is-speaking');
    } else {
        badge.innerText = 'Prêt à t\'écouter';
        badge.className = 'tg k';
        center.style.background = '#4F46E5';
        instruction.innerText = 'Clique sur l\'orbe central pour parler, ou choisis une action rapide ci-dessous.';
        if (ring) ring.classList.remove('is-speaking');
    }
}

function toggleMic(forceStart) {
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    if (!SpeechRecognition) {
        alert("La reconnaissance vocale directe nécessite Chrome ou Edge. Tu peux utiliser le champ de saisie ou les boutons rapides juste en dessous.");
        return;
    }

    if (!forceStart && isListening && vocalRecognition) {
        if (vocalSilenceTimer) {
            clearTimeout(vocalSilenceTimer);
            vocalSilenceTimer = null;
        }
        vocalRecognition.stop();
        isListening = false;
        setOrbState('idle');
        return;
    }

    if (window.mkStopSpeech) window.mkStopSpeech();
    if (vocalRecognition) {
        try { vocalRecognition.stop(); } catch (e) {}
    }

    vocalRecognition = new SpeechRecognition();
    vocalRecognition.lang = 'fr-FR';
    vocalRecognition.continuous = true;
    vocalRecognition.interimResults = true;

    vocalRecognition.onstart = function() {
        isListening = true;
        setOrbState('listening');
    };

    vocalRecognition.onresult = function(event) {
        let transcript = '';
        for (let i = 0; i < event.results.length; ++i) {
            transcript += event.results[i][0].transcript;
        }
        transcript = transcript.trim();
        const inp = document.getElementById('vocalTextInput');
        if (inp) {
            inp.value = transcript;
            if (window.mkAutoResizeTextarea) window.mkAutoResizeTextarea(inp);
        }
        if (transcript) {
            document.getElementById('oralQuestionText').innerText = '« ' + transcript + ' »';
            if (vocalSilenceTimer) clearTimeout(vocalSilenceTimer);
            vocalSilenceTimer = setTimeout(() => {
                if (isListening && inp && inp.value.trim().length > 2) {
                    isListening = false;
                    try { vocalRecognition.stop(); } catch (e) {}
                    submitVocalTextInput();
                }
            }, 1350);
        }
    };

    vocalRecognition.onerror = function() {
        isListening = false;
        setOrbState('idle');
    };

    vocalRecognition.onend = function() {
        isListening = false;
        const inp = document.getElementById('vocalTextInput');
        if (inp && inp.value.trim().length > 1) {
            submitVocalTextInput();
        } else {
            setOrbState('idle');
        }
    };

    try {
        vocalRecognition.start();
    } catch (e) {}
}

function submitVocalTextInput() {
    if (vocalSilenceTimer) {
        clearTimeout(vocalSilenceTimer);
        vocalSilenceTimer = null;
    }
    const inp = document.getElementById('vocalTextInput');
    if (!inp) return;
    const text = inp.value.trim();
    if (!text) return;
    inp.value = '';
    if (window.mkAutoResizeTextarea) window.mkAutoResizeTextarea(inp);
    sendVocalPrompt(text);
}

function speakHumanVocal(text, btnEl, restartLoopAfter) {
    lastAiSpokenText = text;
    setOrbState('speaking');
    if (window.mkSpeakHuman) {
        window.mkSpeakHuman(text, btnEl || null, currentVoice, function() {
            setOrbState('idle');
            if (restartLoopAfter && isContinuousVocalLoop) {
                setTimeout(() => {
                    if (isContinuousVocalLoop) toggleMic(true);
                }, 400);
            }
        });
    } else {
        setOrbState('idle');
    }
}

function repeatLastAiSpeech() {
    if (lastAiSpokenText) {
        speakHumanVocal(lastAiSpokenText, null, false);
    }
}

function sendVocalPrompt(studentWords) {
    const log = document.getElementById('transcriptionLog');
    const userDiv = document.createElement('div');
    userDiv.style.cssText = 'background:#F3F4FA;padding:10px 14px;border-radius:14px;font-size:13px;color:#0E1022;align-self:flex-end;max-width:90%';
    userDiv.innerHTML = '<b>Toi :</b> ' + (studentWords.replace(/</g, '&lt;').replace(/>/g, '&gt;'));
    log.appendChild(userDiv);
    log.scrollTop = log.scrollHeight;

    setOrbState('thinking');

    fetch('{{ route("student.chat.message") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            course_id: currentVocalCourseId,
            message: studentWords,
            active_tab: 'cours',
            interaction_mode: 'voice_call',
            context_text: currentVocalCourseContext
        })
    })
    .then(r => r.json())
    .then(data => {
        const resp = data.assistant_response || data;
        const rawSource = resp.source_text || "Concentrons-nous sur la définition principale du cours.";
        const rawComp = resp.complementary_text || '';
        const spokenText = resp.spoken_text || (rawSource + (rawComp ? '. ' + rawComp : ''));
        const formattedHtml = window.mkFormatAiHtml ? window.mkFormatAiHtml(rawSource) : rawSource;

        // Mise à jour du texte principal sous l'orbe (résumé court de la première phrase parlée)
        const firstSentence = spokenText.split(/(?<=[.?!])\s+/)[0] || spokenText;
        document.getElementById('oralQuestionText').innerText = '« ' + (firstSentence.length > 160 ? firstSentence.substring(0, 157) + '...' : firstSentence) + ' »';

        const botDiv = document.createElement('div');
        botDiv.style.cssText = 'background:#ECEBFF;padding:12px 16px;border-radius:14px;font-size:13px;line-height:1.55;color:#0E1022';
        botDiv.innerHTML = `
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px">
                <b style="color:#4F46E5">${resp.citation || 'Tuteur Vocal IA'}</b>
                <button type="button" class="mk-tts-btn" title="Écouter cette réponse">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon><path d="M15.54 8.46a5 5 0 0 1 0 7.07"></path></svg>
                    <span>Écouter</span>
                </button>
            </div>
            <div>${formattedHtml}</div>
        `;
        const ttsBtn = botDiv.querySelector('.mk-tts-btn');
        if (ttsBtn) {
            ttsBtn.dataset.speech = spokenText;
            ttsBtn.onclick = function() {
                speakHumanVocal(this.dataset.speech, this);
            };
        }

        log.appendChild(botDiv);
        if (typeof renderMathInElement === 'function') {
            renderMathInElement(botDiv, {
                delimiters: [
                    {left: '$$', right: '$$', display: true},
                    {left: '$', right: '$', display: false},
                    {left: '\\(', right: '\\)', display: false},
                    {left: '\\[', right: '\\]', display: true}
                ],
                throwOnError: false
            });
        }
        log.scrollTop = log.scrollHeight;

        speakHumanVocal(spokenText, ttsBtn, true);
    })
    .catch(() => {
        setOrbState('idle');
    });
}
</script>

<style>
.ring-pulse {
    animation: ringGlow 2.5s infinite ease-in-out;
}
.vocal-orb-wrap.is-speaking .ring-pulse {
    border-color: rgba(5, 150, 105, 0.65) !important;
    animation: ringGlowActive 1.1s infinite ease-in-out;
}
.r1 { animation-delay: 0s; }
.r2 { animation-delay: 0.4s; }
.r3 { animation-delay: 0.8s; }
.r4 { animation-delay: 1.2s; }

@keyframes ringGlow {
    0% { transform: scale(0.96); opacity: 0.4; }
    50% { transform: scale(1.04); opacity: 0.9; }
    100% { transform: scale(0.96); opacity: 0.4; }
}
@keyframes ringGlowActive {
    0% { transform: scale(0.94); opacity: 0.5; }
    50% { transform: scale(1.09); opacity: 1; }
    100% { transform: scale(0.94); opacity: 0.5; }
}
</style>
@endsection
