@extends('layouts.app')

@section('title', 'Assistant Pédagogique IA')
@section('header-title', 'Assistant IA MonKayier')

@section('content')
<div class="mk-chat-layout">

    <!-- Volet Latéral : Contexte Scolaire Actif -->
    <div class="mk-chat-sidebar">
        <div style="font-size: 13px; font-weight: 700; text-transform: uppercase; color: var(--mk-text-muted); margin-bottom: 12px; letter-spacing: 0.5px;">
            Contexte d'Apprentissage
        </div>

        <div class="mk-form-group">
            <label class="mk-label">Classe / Niveau</label>
            <select class="mk-select" id="chat-level">
                <option value="3E" selected>Troisième (BEPC Togo)</option>
                <option value="1ERE">Première (BAC 1)</option>
                <option value="TLE">Terminale (BAC 2)</option>
            </select>
        </div>

        <div class="mk-form-group">
            <label class="mk-label">Matière</label>
            <select class="mk-select" id="chat-subject">
                <option value="MATHS" selected>Mathématiques</option>
                <option value="PCT">Physique-Chimie (PCT)</option>
                <option value="SVT">SVT</option>
                <option value="FRANCAIS">Français</option>
            </select>
        </div>

        <div class="mk-form-group">
            <label class="mk-label">Chapitre Actif</label>
            <select class="mk-select" id="chat-chapter">
                <option value="thales" selected>Théorème de Thalès & Réciproque</option>
                <option value="pgcd">Arithmétique & PGCD</option>
                <option value="litteral">Calcul Littéral & Équations</option>
            </select>
        </div>

        <div style="margin-top: auto; padding-top: 16px; border-top: 1px solid var(--mk-border);">
            <div style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: var(--mk-text-muted); margin-bottom: 6px;">
                Source Documentaire Indexée :
            </div>
            <div style="padding: 10px; background: var(--mk-lavender); border-radius: 10px; font-size: 12px; color: var(--mk-indigo); font-weight: 600;">
                📘 Guide MEPST 3e Mathématiques
            </div>
        </div>
    </div>

    <!-- Zone Centrale de Conversation -->
    <div class="mk-chat-main">
        <!-- Header de la conversation -->
        <div class="mk-chat-header">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--mk-indigo); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 14px;">
                    IA
                </div>
                <div>
                    <div style="font-weight: 700; font-size: 14px; color: var(--mk-night);">Assistant Pédagogique MonKayier</div>
                    <div style="font-size: 11px; color: var(--mk-text-muted);">Calibré sur le programme officiel du Togo · Réponses vérifiées</div>
                </div>
            </div>
            <span class="mk-badge mk-badge-lime">RAG Actif</span>
        </div>

        <!-- Messages Stream -->
        <div class="mk-chat-messages" id="chat-messages">
            <!-- Message de Bienvenue IA -->
            <div class="mk-chat-bubble-ai">
                Bonjour Koffi ! Je suis ton assistant pédagogique MonKayier pour la classe de <strong>3e</strong>. 
                Je m'appuie sur le programme officiel du Togo pour t'aider à réviser le BEPC.<br><br>
                Sur quel point du <strong>Théorème de Thalès</strong> souhaites-tu travailler aujourd'hui ?
            </div>

            <!-- Question Élève -->
            <div class="mk-chat-bubble-user">
                Peux-tu m'expliquer clairement quand est-ce qu'on utilise le théorème direct de Thalès et quand est-ce qu'on utilise sa réciproque au BEPC ?
            </div>

            <!-- Réponse IA fondée sur le cours -->
            <div class="mk-chat-bubble-ai">
                C'est une excellente question, très fréquente au BEPC au Togo ! Voici la règle d'or pour ne jamais hésiter :<br><br>

                <strong>1. Utilise le Théorème DIRECT de Thalès :</strong><br>
                👉 <em>Quand tu sais déjà que deux droites sont parallèles</em> (c'est une donnée de l'énoncé), et que tu cherches à <strong>calculer une longueur inconnue</strong>.<br>
                Formule : <code>AM / AB = AN / AC = MN / BC</code>.<br><br>

                <strong>2. Utilise la RÉCIPROQUE du Théorème de Thalès :</strong><br>
                👉 <em>Quand tu connais toutes les longueurs</em>, et que la consigne te demande de <strong>démontrer que deux droites sont parallèles</strong>.<br>
                Méthode : Tu calcules séparément les rapports <code>AM / AB</code> et <code>AN / AC</code>. S'ils sont égaux et que les points sont alignés dans le même ordre, tu conclus que <code>(MN) // (BC)</code>.<br>

                <div class="mk-citation-chip">
                    📄 Source officielle : Guide d'Exécution National de Mathématiques 3e · Page 1 (MEPST Togo)
                </div>
            </div>

            <!-- Question Élève Hors Programme -->
            <div class="mk-chat-bubble-user">
                Et pour calculer l'aire d'un triangle quelconque, est-ce que je peux utiliser la formule de Héron ?
            </div>

            <!-- Réponse IA avec note hors cours -->
            <div class="mk-chat-bubble-ai">
                Au collège en classe de 3e au Togo, la formule de base exigée à l'examen est :<br>
                <code>Aire = (Base × Hauteur) / 2</code>.<br>

                <div class="mk-off-course-note">
                    <strong>⚠️ Explication complémentaire · hors de ton cours :</strong><br>
                    La formule de Héron <code>√(p(p-a)(p-b)(p-c))</code> permet effectivement de calculer l'aire à partir des trois côtés, mais elle <strong>ne fait pas partie du programme officiel du BEPC au Togo</strong>. Il est fortement conseillé d'utiliser la hauteur relative pour éviter toute pénalité de barème à l'examen.
                </div>
            </div>
        </div>

        <!-- Pilules d'Actions Rapides -->
        <div style="padding: 8px 24px; display: flex; gap: 8px; overflow-x: auto; background: var(--mk-surface);">
            <button class="mk-btn mk-btn-lavender mk-btn-sm" onclick="sendQuickPrompt('Pose-moi un exercice type BEPC sur Thalès')">
                📝 Pose-moi un exercice type BEPC
            </button>
            <button class="mk-btn mk-btn-lavender mk-btn-sm" onclick="sendQuickPrompt('Donne-moi un exemple rédigé pas à pas')">
                ✍️ Exemple de rédaction complète
            </button>
            <button class="mk-btn mk-btn-lavender mk-btn-sm" onclick="sendQuickPrompt('Quels sont les pièges à éviter ?')">
                ⚠️ Pièges fréquents à l'examen
            </button>
        </div>

        <!-- Input Bar -->
        <div class="mk-chat-input-bar">
            <input type="text" class="mk-input" id="chat-input" placeholder="Pose ta question en mathématiques, physique, SVT ou français..." style="border-radius: var(--mk-radius-pill);">
            <button class="mk-btn mk-btn-primary" id="chat-send" style="padding: 12px 20px;">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </button>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function sendQuickPrompt(text) {
    const input = document.getElementById('chat-input');
    input.value = text;
    sendMessage();
}

function sendMessage() {
    const input = document.getElementById('chat-input');
    const text = input.value.trim();
    if (!text) return;

    const messages = document.getElementById('chat-messages');

    // Add user message
    const userDiv = document.createElement('div');
    userDiv.className = 'mk-chat-bubble-user';
    userDiv.textContent = text;
    messages.appendChild(userDiv);

    input.value = '';
    messages.scrollTop = messages.scrollHeight;

    // Simulate pedagogical AI response
    setTimeout(() => {
        const aiDiv = document.createElement('div');
        aiDiv.className = 'mk-chat-bubble-ai';
        aiDiv.innerHTML = `Très bonne question sur <strong>${text}</strong> ! D'après le programme officiel de 3e au Togo, voici l'explication méthodique :<br><br>
        1. Identifie toujours les hypothèses fournies dans le texte.<br>
        2. Rédige clairement l'égalité des rapports proportionnels.<br>
        3. Conclus en précisant l'unité.<br>
        <div class="mk-citation-chip">📄 Référence : Guide Pédagogique MEPST 3e · Page 1</div>`;
        messages.appendChild(aiDiv);
        messages.scrollTop = messages.scrollHeight;
    }, 600);
}

document.getElementById('chat-send').addEventListener('click', sendMessage);
document.getElementById('chat-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') sendMessage();
});
</script>
@endpush
