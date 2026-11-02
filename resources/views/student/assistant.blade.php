@extends('layouts.student')

@section('title', 'Assistant IA Pédagogique — MonKayier')

@section('content')
<!-- En-tête Assistant IA avec bouton d'activation du Mode Vocal -->
<div class="r" style="margin-bottom:6px;flex-wrap:wrap;gap:12px">
    <div>
        <h1 style="font-size:26px;margin:0">Assistant IA Pédagogique</h1>
        <div class="mu" style="font-size:13.5px;margin-top:4px">Tuteur interactif pour t'aider à comprendre par toi-même sans blocage</div>
    </div>
    <div style="display:flex;align-items:center;gap:10px">
        <a href="{{ route('student.vocal') }}" class="bt" style="background:#0E1022;color:#fff;display:inline-flex;align-items:center;gap:8px;text-decoration:none;font-weight:700;box-shadow:0 4px 14px rgba(14,16,34,0.25)">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#D4F25C" stroke-width="2.2"><rect x="9" y="3" width="6" height="12" rx="3"></rect><path d="M5 11a7 7 0 0 0 14 0M12 18v3"></path></svg>
            Activer l'Assistant Vocal
        </a>
    </div>
</div>

<div class="g3" style="grid-template-columns:260px 1fr 300px">
    <!-- Colonne gauche : Conversations -->
    <div class="c">
        <h3 style="font-size:17px">Conversations</h3>
        <button type="button" class="bt" onclick="startNewDiscussion()" style="border:none;cursor:pointer">Nouvelle discussion</button>
        <div id="convList" style="display:flex;flex-direction:column;gap:4px;margin-top:6px">
            <div id="conv1" style="padding:10px 12px;border-radius:14px;background:#ECEBFF;cursor:pointer" onclick="loadDiscussion('inequation')">
                <b>Résoudre une inéquation</b>
                <div class="mu">Aujourd’hui</div>
            </div>
            <div id="conv2" style="padding:10px 12px;border-radius:14px;cursor:pointer" onclick="loadDiscussion('newton')">
                <b>Les lois de Newton</b>
                <div class="mu">Hier</div>
            </div>
            <div id="conv3" style="padding:10px 12px;border-radius:14px;cursor:pointer" onclick="loadDiscussion('dissertation')">
                <b>Plan de dissertation</b>
                <div class="mu">Lundi</div>
            </div>
        </div>
    </div>

    <!-- Colonne centrale : Chat socratique -->
    <div class="c" style="gap:14px;display:flex;flex-direction:column;min-height:580px">
        <div class="r">
            <h3 id="assistantTitle" style="font-size:17px">Résoudre une inéquation</h3>
            <span class="tg k">Mode indices progressifs</span>
        </div>

        <div id="assistantFeed" style="display:flex;flex-direction:column;gap:12px;overflow-y:auto;flex:1;padding-right:4px">
            <div style="align-self:flex-end;background:#0E1022;color:#fff;padding:12px 16px;border-radius:20px 20px 4px 20px;max-width:70%;font-size:14px">
                Je n’arrive pas à résoudre 3x − 5 &gt; 4.
            </div>

            <div id="initialHintBlock" style="background:#F3F4FA;padding:16px;border-radius:4px 20px 20px 20px;max-width:85%;line-height:1.6;font-size:14px">
                Pas de souci, on y va ensemble. <b>Indice 1 sur 3 :</b> que peux-tu faire pour isoler le terme en x ? Essaie d'ajouter 5 des deux côtés de l'inégalité.
            </div>
        </div>

        <div id="assistantChips" style="display:flex;gap:8px;flex-wrap:wrap">
            <span class="tg" style="cursor:pointer" onclick="requestNextHint()">Indice suivant</span>
            <span class="tg" style="cursor:pointer" onclick="requestNextHint(3)">Montre-moi la méthode</span>
            <span class="tg o" style="cursor:pointer" onclick="requestNextHint(3)">Donne-moi la solution complète</span>
        </div>

        <div class="in" style="margin-top:auto;display:flex;align-items:center;justify-content:space-between;background:#fff;border:1.5px solid #CBD6E6;border-radius:14px;padding:8px 14px">
            <input type="text" id="assistantInput" placeholder="Écris ta réponse ou ta question…" style="border:none;outline:none;width:85%;font:inherit;background:transparent;font-size:14px" onkeydown="if(event.key==='Enter') sendAssistantQuestion()">
            <button type="button" onclick="sendAssistantQuestion()" style="background:none;border:none;color:#4F46E5;font-weight:700;cursor:pointer;font-size:14px">Envoyer</button>
        </div>
    </div>

    <!-- Colonne droite : Plan du soir & niveau -->
    <div class="c">
        <span class="lb">Plan de travail du soir</span>
        <b style="font-size:15px">1. Inéquations · 15 min</b>
        <b style="font-size:15px">2. Quiz de 5 questions</b>
        <b style="font-size:15px">3. Relire le chapitre 3</b>
        <a class="bt l" href="/student/exercises" style="margin-top:6px;text-align:center">Démarrer le plan</a>
        <span class="lb" style="margin-top:14px">Niveau d’explication</span>
        <div class="mu" style="line-height:1.5">Adapté à Terminale D · explications progressives sans donner la réponse brute pour favoriser l'autonomie.</div>
    </div>
</div>

<script>
let currentStep = 1;
let currentTopic = 'inequation';

function requestNextHint(forcedStep = null) {
    if (forcedStep) {
        currentStep = forcedStep;
    } else {
        currentStep = Math.min(3, currentStep + 1);
    }
    
    const feed = document.getElementById('assistantFeed');
    const loadingDiv = document.createElement('div');
    loadingDiv.className = 'mu';
    loadingDiv.innerText = "L'assistant prépare ton indice...";
    feed.appendChild(loadingDiv);
    feed.scrollTop = feed.scrollHeight;

    fetch('{{ route("student.assistant.message") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            message: '3x - 5 > 4',
            step: currentStep
        })
    })
    .then(res => res.json())
    .then(data => {
        loadingDiv.remove();
        if (data.success) {
            const hintBubble = document.createElement('div');
            hintBubble.style.cssText = 'background:#F3F4FA;padding:16px;border-radius:4px 20px 20px 20px;max-width:85%;line-height:1.6;font-size:14px';
            hintBubble.innerHTML = data.reply;
            feed.appendChild(hintBubble);
            feed.scrollTop = feed.scrollHeight;
        }
    })
    .catch(() => {
        loadingDiv.remove();
    });
}

function sendAssistantQuestion() {
    const input = document.getElementById('assistantInput');
    const msg = input.value.trim();
    if (!msg) return;

    input.value = '';
    const feed = document.getElementById('assistantFeed');

    // Bulle élève
    const userBubble = document.createElement('div');
    userBubble.style.cssText = 'align-self:flex-end;background:#0E1022;color:#fff;padding:12px 16px;border-radius:20px 20px 4px 20px;max-width:70%;font-size:14px;word-break:break-word';
    userBubble.innerText = msg;
    feed.appendChild(userBubble);
    feed.scrollTop = feed.scrollHeight;

    // Appel API
    fetch('{{ route("student.assistant.message") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            message: msg,
            step: currentStep
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const botBubble = document.createElement('div');
            botBubble.style.cssText = 'background:#F3F4FA;padding:16px;border-radius:4px 20px 20px 20px;max-width:85%;line-height:1.6;font-size:14px';
            botBubble.innerHTML = data.reply;
            feed.appendChild(botBubble);
            feed.scrollTop = feed.scrollHeight;
        }
    });
}

function startNewDiscussion() {
    document.getElementById('assistantTitle').innerText = "Nouvelle discussion";
    const feed = document.getElementById('assistantFeed');
    feed.innerHTML = `
        <div style="background:#F3F4FA;padding:16px;border-radius:4px 20px 20px 20px;max-width:85%;line-height:1.6;font-size:14px">
            Bonjour Kodjo ! Sur quel sujet ou devoir souhaites-tu travailler ensemble ce soir ? Tu peux m'écrire un énoncé d'exercice en Maths, Physique ou SVT.
        </div>
    `;
    currentStep = 1;
}

function loadDiscussion(type) {
    startNewDiscussion();
    if (type === 'newton') {
        document.getElementById('assistantTitle').innerText = "Les lois de Newton";
        document.getElementById('assistantInput').value = "Explique-moi le bilan des forces d'un projectile";
        sendAssistantQuestion();
    } else if (type === 'dissertation') {
        document.getElementById('assistantTitle').innerText = "Plan de dissertation";
        document.getElementById('assistantInput').value = "Aide-moi à trouver la problématique pour le sujet : L'art transforme-t-il le monde ?";
        sendAssistantQuestion();
    } else {
        document.getElementById('assistantTitle').innerText = "Résoudre une inéquation";
        document.getElementById('assistantInput').value = "Je n'arrive pas à résoudre 3x - 5 > 4";
        sendAssistantQuestion();
    }
}
</script>
@endsection
