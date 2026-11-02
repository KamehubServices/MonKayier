<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'MonKayier — Espace Élève')</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        body { margin: 0; }
        a { color: inherit; text-decoration: none; }
        .p {
            min-height: 960px;
            background: #F5F6FA;
            color: #0E1022;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            font-size: 14px;
            -webkit-font-smoothing: antialiased;
        }
        .p h1, .p h2, .p h3, .hd {
            font-family: 'Sora', sans-serif;
            letter-spacing: -.035em;
            margin: 0;
            font-weight: 700;
        }
        .tb {
            position: sticky;
            top: 12px;
            z-index: 5;
            margin: 12px auto 0;
            max-width: 1280px;
            width: calc(100% - 48px);
            box-sizing: border-box;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 8px 10px 8px 16px;
            border-radius: 999px;
            background: rgba(255, 255, 255, .78);
            backdrop-filter: blur(18px);
            border: 1px solid #E6E8F1;
            box-shadow: 0 10px 30px -18px rgba(14, 16, 34, .25);
            flex-wrap: wrap;
        }
        .tb nav { background: #EEF0F6; border-radius: 999px; padding: 4px; }
        .pill {
            padding: 8px 16px;
            border-radius: 999px;
            font-weight: 600;
            font-size: 13px;
            color: #5A5F78;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .pill.on { background: #0E1022; color: #fff; }
        .tb .pill.on { background: #fff; color: #0E1022; box-shadow: 0 1px 3px rgba(14, 16, 34, .14); }
        .w {
            max-width: 1280px;
            margin: 0 auto;
            padding: 24px 24px 56px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .c {
            background: #fff;
            border-radius: 14px;
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            box-sizing: border-box;
            border: 1px solid #E8EAF2;
            box-shadow: 0 1px 2px rgba(14, 16, 34, .04), 0 18px 40px -28px rgba(14, 16, 34, .18);
        }
        .dk { background: #0E1022; border-color: #0E1022; color: #fff; }
        .ind { background: #4F46E5; border-color: #4F46E5; color: #fff; }
        .mu { color: #5A5F78; font-size: 12.5px; }
        .dk .mu, .ind .mu { color: #BDC0E6; }
        .lb {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: #5A5F78;
        }
        .dk .lb, .ind .lb { color: #BDC0E6; }
        .bt {
            display: inline-block;
            text-align: center;
            padding: 10px 20px;
            border-radius: 8px;
            background: #0E1022;
            color: #fff;
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            font-size: 13.5px;
            min-height: 20px;
            box-shadow: none;
            cursor: pointer;
            transition: all 0.15s ease;
            box-sizing: border-box;
            border: none;
        }
        .bt:hover { background: #1C2038; filter: none; }
        .bt.l { background: #D4F25C; color: #0E1022; }
        .bt.l:hover { background: #C5E44A; }
        .bt.g { background: #FFFFFF; border: 1.5px solid #CBD5E1; color: #0E1022; box-shadow: none; }
        .bt.g:hover { background: #F8FAFC; border-color: #94A3B8; }
        .tg {
            display: inline-block;
            padding: 3px 9px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            background: #F1F5F9;
            color: #475569;
        }
        .tg.o { background: #FEF3C7; color: #92400E; }
        .tg.k { background: #ECFDF5; color: #065F46; }
        .bar { height: 6px; border-radius: 3px; background: #E9EBF5; }
        .bar i { display: block; height: 6px; border-radius: 3px; background: #D4F25C; }
        .mk-subnav {
            display: flex;
            align-items: center;
            gap: 6px;
            background: #FFFFFF;
            border: 1.5px solid #E2E8F0;
            padding: 6px;
            border-radius: 12px;
            width: fit-content;
            flex-wrap: wrap;
            box-sizing: border-box;
        }
        .mk-subnav-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            color: #475569;
            transition: all 0.15s ease;
        }
        .mk-subnav-link:hover {
            background: #F8FAFC;
            color: #0E1022;
        }
        .mk-subnav-link.active {
            background: #0E1022;
            color: #FFFFFF;
        }
        .mk-subnav-link.active svg {
            stroke: #D4F25C;
        }
        .mk-subnav-link .badge-count {
            background: #EF4444;
            color: #FFFFFF;
            border-radius: 999px;
            padding: 2px 7px;
            font-size: 11px;
            font-weight: 800;
            line-height: 1;
        }
        .r { display: flex; align-items: center; justify-content: space-between; gap: 12px; }
        .g2, .g3, .g4 { display: grid; gap: 20px; }
        .g2 { grid-template-columns: 1.6fr 1fr; }
        .g3 { grid-template-columns: repeat(3, 1fr); }
        .g4 { grid-template-columns: repeat(4, 1fr); }
        .in {
            border: 1px solid #E1E4EF;
            border-radius: 16px;
            padding: 12px 16px;
            font-weight: 500;
            background: #fff;
            box-sizing: border-box;
        }
        .hl { background: #E7F8A8; border-radius: 4px; padding: 0 3px; }
        .cmd {
            display: flex;
            align-items: center;
            gap: 14px;
            background: #fff;
            border: 1px solid #E1E4EF;
            border-radius: 999px;
            padding: 10px 10px 10px 22px;
            box-shadow: 0 18px 40px -26px rgba(79, 70, 229, .45);
        }
        .lay {
            max-width: 100%;
            margin: 0;
            display: flex;
            align-items: flex-start;
            padding: 0 16px 0 10px;
            box-sizing: border-box;
            width: 100%;
        }
        .sb {
            position: sticky;
            top: 14px;
            width: 228px;
            flex: none;
            margin-top: 14px;
            margin-bottom: 14px;
            margin-left: 0;
            background: #fff;
            border: 1px solid #E2E8F0;
            border-radius: 14px;
            padding: 14px 12px;
            box-shadow: 0 1px 3px rgba(14, 16, 34, .04);
            display: flex;
            flex-direction: column;
            gap: 3px;
            box-sizing: border-box;
            min-height: calc(100vh - 28px);
            align-self: stretch;
        }
        .sb .sl {
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: #5A5F78;
            padding: 14px 12px 6px;
        }
        .sb a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 13.5px;
            color: #3A3E57;
            transition: all 0.2s ease;
        }
        .sb a:hover { background: #F3F4FA; }
        .sb a.on { background: #0E1022; color: #fff; }
        .sb a.on svg { stroke: #D4F25C; }
        .sb svg { flex: none; width: 20px; height: 20px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
        .sb .hp {
            margin-top: 12px;
            padding: 14px;
            border-radius: 18px;
            background: #ECEBFF;
            font-size: 12.5px;
            color: #3730A3;
            line-height: 1.5;
        }
        .lay .w {
            flex: 1;
            min-width: 0;
            max-width: none;
            margin: 0;
            padding: 14px 0 40px 14px;
        }
        @media (max-width: 1000px) {
            .g2, .g3, .g4 { grid-template-columns: 1fr; }
            .tb { border-radius: 28px; }
            .w { padding-left: 16px; padding-right: 16px; }
            .sb { display: none; }
            .lay { padding: 0; }
            .lay .w { padding-left: 16px; padding-right: 16px; }
        }

        /* ========================================================= */
        /* MODE FOCUS IMMERSION & PASTILLE D'ANGLE FLOTTANTE         */
        /* ========================================================= */
        body.is-focus-mode {
            overflow: hidden !important;
            height: 100vh !important;
            max-height: 100vh !important;
            background: #F8FAFC !important;
        }
        body.is-focus-mode .p {
            min-height: 0 !important;
            height: 100vh !important;
            max-height: 100vh !important;
            overflow: hidden !important;
            background: #F8FAFC !important;
            display: flex !important;
            flex-direction: column !important;
        }
        body.is-focus-mode .tb,
        body.is-focus-mode .sb {
            display: none !important;
        }
        body.is-focus-mode .lay {
            max-width: 100vw !important;
            width: 100vw !important;
            padding: 0 !important;
            margin: 0 !important;
            height: 100vh !important;
            max-height: 100vh !important;
            display: flex !important;
            flex-direction: column !important;
            flex: 1 !important;
            min-height: 0 !important;
            overflow: hidden !important;
        }
        body.is-focus-mode .lay .w {
            flex: 1;
            min-width: 0;
            height: 100vh;
            max-height: 100vh;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            box-sizing: border-box;
            overflow: hidden;
            background: #FFFFFF;
        }

        /* Pastille Flottante d'Angle (Corner Quick-Dock) - Visible UNIQUEMENT en Mode Plein Écran Focus */
        .corner-quick-dock {
            display: none !important;
            position: relative;
            z-index: 100;
        }
        body.is-focus-mode .corner-quick-dock {
            display: inline-block !important;
            position: fixed !important;
            top: 18px !important;
            left: 20px !important;
            z-index: 99999 !important;
        }
        body.page-test-niveau .corner-quick-dock {
            display: none !important;
        }
        body.page-test-niveau,
        body.page-test-niveau .p,
        body.page-test-niveau .lay,
        body.page-test-niveau .lay .w,
        body.page-test-niveau .w {
            width: 100vw !important;
            max-width: 100vw !important;
            min-width: 100vw !important;
            margin: 0 !important;
            padding: 0 !important;
            background: #FFFFFF !important;
            box-sizing: border-box !important;
        }
        body.page-test-niveau .sb {
            display: none !important;
        }
        body.is-focus-mode .lay .w {
            overflow-y: auto !important;
            background: #F8FAFC !important;
        }
        .corner-dock-trigger {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 5px 12px 5px 6px;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(12px);
            border: 1.5px solid #CBD5E1;
            border-radius: 999px;
            cursor: pointer;
            box-shadow: 0 4px 16px rgba(14, 16, 34, 0.12);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            color: #0E1022;
        }
        .corner-dock-trigger:hover {
            background: #FFFFFF;
            border-color: #3fa9f5;
            box-shadow: 0 6px 20px rgba(63, 169, 245, 0.25);
            transform: scale(1.03);
        }
        .corner-dock-logo {
            height: 22px;
            width: auto;
            border-radius: 50%;
        }
        .corner-dock-pill-text {
            font-size: 11.5px;
            font-weight: 750;
            color: #0E1022;
            letter-spacing: -0.01em;
        }
        .corner-dock-chevron {
            color: #64748B;
            transition: transform 0.2s ease;
        }
        .corner-quick-dock.is-open .corner-dock-chevron {
            transform: rotate(180deg);
        }

        .corner-dock-menu {
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            width: 236px;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border: 1.5px solid #E2E8F0;
            border-radius: 18px;
            box-shadow: 0 18px 40px -8px rgba(14, 16, 34, 0.26);
            padding: 8px;
            box-sizing: border-box;
            animation: dockMenuPop 0.18s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .corner-quick-dock:hover .corner-dock-menu,
        .corner-quick-dock.is-open .corner-dock-menu {
            display: block;
        }
        @keyframes dockMenuPop {
            from { opacity: 0; transform: translateY(-6px) scale(0.96); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .corner-dock-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 10px 10px;
            border-bottom: 1px solid #F1F5F9;
            margin-bottom: 6px;
        }
        .corner-dock-mode-badge {
            font-size: 10px;
            font-weight: 800;
            background: #D4F25C;
            color: #0E1022;
            padding: 2px 7px;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .corner-dock-links {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }
        .corner-dock-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 10px;
            border-radius: 10px;
            text-decoration: none;
            color: #3A3E57;
            font-size: 12.5px;
            font-weight: 650;
            transition: all 0.15s ease;
        }
        .corner-dock-link svg {
            width: 16px;
            height: 16px;
            stroke: currentColor;
            stroke-width: 2;
            fill: none;
            flex: none;
        }
        .corner-dock-link:hover {
            background: #F1F5F9;
            color: #0E1022;
        }
        .corner-dock-link.active {
            background: #0E1022;
            color: #FFFFFF;
        }
        .corner-dock-link.active svg {
            stroke: #D4F25C;
        }

        .corner-dock-footer {
            border-top: 1px solid #F1F5F9;
            margin-top: 6px;
            padding-top: 6px;
        }
        .corner-dock-exit-btn {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 10px;
            border-radius: 10px;
            background: #FEF2F2;
            border: 1px solid #FECACA;
            color: #DC2626;
            font-size: 11.5px;
            font-weight: 750;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .corner-dock-exit-btn:hover {
            background: #FEE2E2;
            color: #991B1B;
        }

        /* ========================================================= */
        /* FORMATAGE STRUCTURÉ DES RÉPONSES IA & AUDIO NEURAL HUMAIN */
        /* ========================================================= */
        .mk-ai-formatted {
            display: flex;
            flex-direction: column;
            gap: 8px;
            line-height: 1.6;
            font-size: 13px;
            color: inherit;
        }
        .mk-ai-heading {
            font-family: 'Sora', sans-serif;
            font-size: 13.5px;
            font-weight: 850;
            color: #0284C7;
            margin-top: 6px;
            margin-bottom: 2px;
            padding-bottom: 3px;
            border-bottom: 1px solid rgba(2, 132, 199, 0.18);
            letter-spacing: -0.01em;
        }
        .mk-ai-p {
            margin: 0;
            line-height: 1.6;
        }
        .mk-ai-bold {
            font-weight: 800;
            color: #0E1022;
        }
        .theme-night .mk-ai-bold {
            color: #D4F25C;
        }
        .mk-ai-ul, .mk-ai-ol {
            margin: 2px 0 4px 0;
            padding-left: 18px;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        .mk-ai-li {
            line-height: 1.55;
        }
        .mk-ai-li::marker {
            color: #0284C7;
            font-weight: 800;
        }
        .mk-tts-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #F1F5F9;
            border: 1px solid #CBD5E1;
            color: #0E1022;
            padding: 3px 9px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 750;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .mk-tts-btn:hover {
            background: #D4F25C;
            border-color: #0E1022;
            color: #0E1022;
        }
        .mk-tts-btn.is-speaking {
            background: #0E1022;
            border-color: #0E1022;
            color: #D4F25C;
        }
        .mk-tts-wave {
            display: inline-flex;
            align-items: flex-end;
            gap: 2px;
            height: 11px;
        }
        .mk-tts-wave i {
            display: block;
            width: 2px;
            background: #D4F25C;
            border-radius: 1px;
            animation: mkWaveBar 0.7s ease-in-out infinite alternate;
        }
        .mk-tts-wave i:nth-child(1) { height: 5px; animation-delay: 0s; }
        .mk-tts-wave i:nth-child(2) { height: 10px; animation-delay: 0.2s; }
        .mk-tts-wave i:nth-child(3) { height: 6px; animation-delay: 0.4s; }
        @keyframes mkWaveBar {
            0% { height: 3px; }
            100% { height: 11px; }
        }

        /* =========================================================
           BARRE DE REDIMENSIONNEMENT INTERACTIVE (SPLIT-PANE RESIZER)
           Permet d'agrandir / réduire naturellement la colonne Tuteur IA
           ========================================================= */
        .immersion-ai-pane,
        .atelier-tutor-column {
            position: relative !important;
            overflow-y: hidden !important;
            overflow-x: visible !important;
        }
        .atelier-tutor-column.is-collapsed {
            width: 0 !important;
            min-width: 0 !important;
            max-width: 0 !important;
            flex: 0 0 0px !important;
            overflow: hidden !important;
        }
        .atelier-tutor-column.is-collapsed .mk-split-resizer {
            display: none !important;
        }
        .mk-split-resizer {
            position: absolute;
            top: 0;
            bottom: 0;
            left: -6px;
            width: 12px;
            cursor: col-resize;
            z-index: 80;
            display: flex;
            align-items: center;
            justify-content: center;
            user-select: none;
            touch-action: none;
        }
        /* Ligne verticale lumineuse au survol ou pendant le glissement */
        .mk-split-resizer::before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            left: 5px;
            width: 2px;
            background: transparent;
            transition: background 0.15s ease, width 0.15s ease, left 0.15s ease;
        }
        /* Poignée centrale visible pour indiquer que la bordure est étirable */
        .mk-split-resizer-grip {
            position: relative;
            width: 4px;
            height: 44px;
            border-radius: 999px;
            background: #CBD5E1;
            transition: all 0.18s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 3px;
            pointer-events: none;
        }
        .mk-split-resizer:hover::before,
        .mk-split-resizer.is-dragging::before {
            background: #4F46E5;
            width: 3px;
            left: 4.5px;
        }
        .mk-split-resizer:hover .mk-split-resizer-grip,
        .mk-split-resizer.is-dragging .mk-split-resizer-grip {
            height: 64px;
            width: 6px;
            background: #4F46E5;
            box-shadow: 0 0 12px rgba(79, 70, 229, 0.45);
        }
        body.mk-is-resizing-pane,
        body.mk-is-resizing-pane * {
            cursor: col-resize !important;
            user-select: none !important;
        }
        body.mk-is-resizing-pane iframe {
            pointer-events: none !important;
        }
        .mk-no-grid-transition {
            transition: none !important;
        }

        /* =========================================================
           CHAMP DE SAISIE CHAT MULTI-LIGNES AUTO-EXTENSIBLE (TEXTAREA)
           S'agrandit automatiquement quand l'élève écrit un long texte
           ========================================================= */
        .immersion-chat-input-bar,
        .tutor-input-box {
            align-items: flex-end !important;
            padding: 7px 10px !important;
            border-radius: 12px !important;
        }
        textarea.immersion-input-field,
        textarea.tutor-text-input,
        textarea.mk-auto-textarea {
            resize: none !important;
            overflow-y: hidden;
            min-height: 24px !important;
            max-height: 140px !important;
            line-height: 1.45 !important;
            padding: 4px 6px !important;
            margin: 0 !important;
            box-sizing: border-box !important;
            white-space: pre-wrap !important;
            word-break: break-word !important;
            font-family: inherit !important;
            display: block !important;
            width: 100% !important;
        }
        textarea.immersion-input-field::-webkit-scrollbar,
        textarea.tutor-text-input::-webkit-scrollbar,
        textarea.mk-auto-textarea::-webkit-scrollbar {
            width: 5px;
        }
        textarea.immersion-input-field::-webkit-scrollbar-thumb,
        textarea.tutor-text-input::-webkit-scrollbar-thumb,
        textarea.mk-auto-textarea::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 4px;
        }

        /* =========================================================
           MODE APPEL VOCAL CONTINU MAINS-LIBRES (STYLE CHATGPT VOICE)
           & BULLE CONTEXTUELLE DE SÉLECTION DU COURS
           ========================================================= */
        .mk-voice-call-dock {
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 16px 14px;
            margin-bottom: 6px;
            border-radius: 16px;
            background: linear-gradient(145deg, #0E1022 0%, #1E1B4B 100%);
            color: #FFFFFF;
            border: 1.5px solid rgba(212, 242, 92, 0.35);
            box-shadow: 0 12px 28px -8px rgba(14, 16, 34, 0.45);
            position: relative;
            flex-shrink: 0;
        }
        .mk-voice-call-dock.is-active {
            display: flex;
        }
        .mk-voice-orb-container {
            position: relative;
            width: 78px;
            height: 78px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .mk-voice-orb-halo {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(212, 242, 92, 0.45) 0%, rgba(79, 70, 229, 0) 70%);
            animation: mkOrbPulse 1.8s infinite ease-in-out;
        }
        .mk-voice-orb-core {
            position: relative;
            z-index: 2;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #4F46E5;
            border: 2.5px solid #D4F25C;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.25s ease;
            box-shadow: 0 0 20px rgba(79, 70, 229, 0.6);
        }
        .mk-voice-call-dock[data-state="listening"] .mk-voice-orb-core {
            background: #EF4444;
            border-color: #FCA5A5;
            box-shadow: 0 0 24px rgba(239, 68, 68, 0.75);
            transform: scale(1.07);
        }
        .mk-voice-call-dock[data-state="thinking"] .mk-voice-orb-core {
            background: #F59E0B;
            border-color: #FDE68A;
            box-shadow: 0 0 22px rgba(245, 158, 11, 0.65);
        }
        .mk-voice-call-dock[data-state="speaking"] .mk-voice-orb-core {
            background: #10B981;
            border-color: #D4F25C;
            box-shadow: 0 0 26px rgba(16, 185, 129, 0.8);
            animation: mkOrbSpeakWave 0.9s infinite alternate ease-in-out;
        }
        @keyframes mkOrbPulse {
            0%, 100% { transform: scale(0.95); opacity: 0.55; }
            50% { transform: scale(1.28); opacity: 0.95; }
        }
        @keyframes mkOrbSpeakWave {
            0% { transform: scale(0.96); }
            100% { transform: scale(1.12); }
        }
        .mk-selection-popover {
            position: fixed;
            z-index: 999999;
            display: none;
            align-items: center;
            gap: 6px;
            background: #0E1022;
            color: #FFFFFF;
            padding: 6px 8px;
            border-radius: 10px;
            border: 1.5px solid #D4F25C;
            box-shadow: 0 10px 25px rgba(14, 16, 34, 0.35);
            transform: translate(-50%, -100%);
            margin-top: -10px;
        }
        .mk-selection-popover.is-visible {
            display: inline-flex;
        }
        .mk-selection-popover button {
            background: rgba(255, 255, 255, 0.1);
            color: #FFFFFF;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 7px;
            padding: 4px 9px;
            font-size: 11.5px;
            font-weight: 800;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.15s ease;
        }
        .mk-selection-popover button:hover {
            background: #D4F25C;
            color: #0E1022;
            border-color: #D4F25C;
        }

        /* =========================================================
           BARRE D'OUTILS REPLIABLE : SUGGESTIONS IA & CLAVIER SCIENTIFIQUE (fx)
           Permet d'afficher ou masquer à la demande les suggestions et les symboles
           ========================================================= */
        .mk-chat-tools-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 6px;
            padding: 2px 0;
            flex-shrink: 0 !important;
            user-select: none;
        }
        .mk-chat-tools-left {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }
        .mk-tool-toggle-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 750;
            cursor: pointer;
            transition: all 0.16s cubic-bezier(0.16, 1, 0.3, 1);
            border: 1px solid var(--immersion-card-border, #CBD5E1);
            background: var(--immersion-card, #F8FAFC);
            color: var(--immersion-subtext, #475569);
            line-height: 1.2;
            box-shadow: none !important;
        }
        .mk-tool-toggle-btn:hover {
            border-color: #0E1022;
            color: #0E1022;
            background: #FFFFFF;
        }
        .mk-tool-toggle-btn.is-active {
            background: #0E1022;
            color: #FFFFFF;
            border-color: #0E1022;
        }
        .mk-tool-toggle-btn.is-active .mk-tool-badge {
            background: #D4F25C;
            color: #0E1022;
        }
        .mk-tool-toggle-btn.is-active svg.mk-tool-icon {
            stroke: #D4F25C;
        }
        .mk-tool-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 1px 5px;
            border-radius: 999px;
            font-size: 9.5px;
            font-weight: 850;
            background: #E2E8F0;
            color: #0E1022;
            line-height: 1.1;
        }
        .mk-tool-fx-badge {
            font-family: 'Sora', Georgia, serif;
            font-style: italic;
            font-weight: 850;
            font-size: 11px;
            padding: 0 4px;
            border-radius: 4px;
            background: rgba(79, 70, 229, 0.12);
            color: #4F46E5;
        }
        .mk-tool-toggle-btn.is-active .mk-tool-fx-badge {
            background: #D4F25C;
            color: #0E1022;
        }
        .mk-tool-chevron {
            transition: transform 0.2s ease;
            opacity: 0.75;
        }
        .mk-tool-toggle-btn.is-active .mk-tool-chevron {
            transform: rotate(180deg);
            opacity: 1;
        }

        /* Tiroir des Suggestions Revisitées */
        .mk-suggestions-drawer {
            display: flex;
            flex-direction: column;
            gap: 6px;
            background: var(--immersion-card, #F8FAFC);
            border: 1px solid var(--immersion-card-border, #E2E8F0);
            border-radius: 12px;
            padding: 8px 10px;
            flex-shrink: 0 !important;
            max-height: 138px;
            overflow-y: auto;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .mk-suggestions-drawer.is-collapsed {
            display: none !important;
        }
        .mk-suggestions-drawer::-webkit-scrollbar {
            width: 4px;
        }
        .mk-suggestions-drawer::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 4px;
        }
        .mk-drawer-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            padding-bottom: 4px;
            border-bottom: 1px dashed var(--immersion-card-border, #E2E8F0);
        }
        .mk-drawer-title {
            font-size: 10px;
            font-weight: 850;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--immersion-subtext, #64748B);
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .mk-drawer-close-btn {
            background: transparent;
            border: none;
            color: var(--immersion-subtext, #64748B);
            font-size: 10.5px;
            font-weight: 750;
            cursor: pointer;
            padding: 1px 6px;
            border-radius: 5px;
            display: inline-flex;
            align-items: center;
            gap: 3px;
            transition: all 0.15s ease;
        }
        .mk-drawer-close-btn:hover {
            background: rgba(14, 16, 34, 0.08);
            color: var(--immersion-text, #0E1022);
        }

        /* Grille de puces de suggestions modernes */
        .mk-suggestions-list {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }
        .mk-sug-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--immersion-pane, #FFFFFF);
            border: 1px solid var(--immersion-card-border, #CBD5E1);
            color: var(--immersion-text, #0E1022);
            font-size: 11.5px;
            font-weight: 650;
            padding: 5px 10px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.16s cubic-bezier(0.16, 1, 0.3, 1);
            text-align: left;
            line-height: 1.3;
            box-shadow: 0 1px 2px rgba(14, 16, 34, 0.03) !important;
            max-width: 100%;
        }
        .mk-sug-pill:hover {
            border-color: #0E1022;
            background: #0E1022;
            color: #FFFFFF;
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(14, 16, 34, 0.12) !important;
        }
        .mk-sug-pill .mk-sug-arrow {
            font-size: 11px;
            color: #64748B;
            font-weight: 800;
            transition: transform 0.15s ease, color 0.15s ease;
            flex: none;
        }
        .mk-sug-pill:hover .mk-sug-arrow {
            color: #D4F25C;
            transform: translateX(2px);
        }
        .mk-sug-pill--quiz {
            background: #D4F25C !important;
            border: 1.5px solid #0E1022 !important;
            color: #0E1022 !important;
            font-weight: 850 !important;
        }
        .mk-sug-pill--quiz:hover {
            background: #0E1022 !important;
            color: #D4F25C !important;
            border-color: #0E1022 !important;
        }
        .mk-sug-pill--quiz .mk-sug-quiz-tag {
            background: #0E1022;
            color: #D4F25C;
            font-size: 9.5px;
            font-weight: 850;
            padding: 1px 5px;
            border-radius: 4px;
            letter-spacing: 0.02em;
        }
        .mk-sug-pill--quiz:hover .mk-sug-quiz-tag {
            background: #D4F25C;
            color: #0E1022;
        }

        /* Tiroir du Clavier Scientifique Repliable (Masqué par défaut pour libérer l'écran) */
        .mk-scikbd-drawer {
            display: flex;
            flex-direction: column;
            gap: 6px;
            background: var(--immersion-card, #F8FAFC);
            border: 1.5px solid var(--immersion-card-border, #CBD5E1);
            border-radius: 12px;
            padding: 8px 10px;
            flex-shrink: 0 !important;
            box-shadow: 0 6px 16px -6px rgba(14, 16, 34, 0.1);
            animation: dockMenuPop 0.16s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .mk-scikbd-drawer.is-collapsed {
            display: none !important;
        }
        .mk-scikbd-tabs {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .mk-scikbd-tab {
            background: transparent;
            border: 1px solid transparent;
            color: var(--immersion-subtext, #64748B);
            font-size: 10px;
            font-weight: 800;
            padding: 2px 7px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .mk-scikbd-tab:hover {
            color: var(--immersion-text, #0E1022);
            background: rgba(14, 16, 34, 0.05);
        }
        .mk-scikbd-tab.is-active {
            background: #0E1022;
            color: #D4F25C;
        }
        .mk-scikbd-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(42px, 1fr));
            gap: 4px;
        }
        .mk-scikbd-key {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 28px;
            padding: 0 6px;
            background: var(--immersion-pane, #FFFFFF);
            border: 1px solid var(--immersion-card-border, #CBD5E1);
            border-bottom: 2px solid #94A3B8;
            border-radius: 7px;
            color: var(--immersion-text, #0E1022);
            font-family: 'Sora', sans-serif;
            font-size: 11.5px;
            font-weight: 750;
            cursor: pointer;
            transition: all 0.12s ease;
            user-select: none;
            box-shadow: none !important;
        }
        .mk-scikbd-key:hover {
            background: #0E1022;
            color: #D4F25C;
            border-color: #0E1022;
            border-bottom-color: #0E1022;
            transform: translateY(-1px);
        }
        .mk-scikbd-key:active {
            transform: translateY(1px);
            border-bottom-width: 1px;
        }
        /* Bouton fx rapide intégré dans la barre de saisie */
        .immersion-fx-btn {
            background: var(--immersion-card, #F1F5F9);
            border: 1px solid var(--immersion-card-border, #CBD5E1);
            color: #4F46E5;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            flex: none;
            font-family: 'Sora', Georgia, serif;
            font-style: italic;
            font-weight: 850;
            font-size: 13px;
            transition: all 0.15s ease;
            box-shadow: none !important;
        }
        .immersion-fx-btn:hover,
        .immersion-fx-btn.is-active {
            background: #0E1022;
            color: #D4F25C;
            border-color: #0E1022;
        }

        /* =========================================================
           MODE APPEL VOCAL CONTINU MAINS-LIBRES (STYLE CHATGPT VOICE)
           & BULLE CONTEXTUELLE DE SÉLECTION DU COURS
           ========================================================= */
        .mk-voice-call-dock {
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 16px 14px;
            margin-bottom: 6px;
            border-radius: 16px;
            background: linear-gradient(145deg, #0E1022 0%, #1E1B4B 100%);
            color: #FFFFFF;
            border: 1.5px solid rgba(212, 242, 92, 0.35);
            box-shadow: 0 12px 28px -8px rgba(14, 16, 34, 0.45);
            position: relative;
            flex-shrink: 0;
        }
        .mk-voice-call-dock.is-active {
            display: flex;
        }
        .mk-voice-orb-container {
            position: relative;
            width: 78px;
            height: 78px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .mk-voice-orb-halo {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(212, 242, 92, 0.45) 0%, rgba(79, 70, 229, 0) 70%);
            animation: mkOrbPulse 1.8s infinite ease-in-out;
        }
        .mk-voice-orb-core {
            position: relative;
            z-index: 2;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #4F46E5;
            border: 2.5px solid #D4F25C;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.25s ease;
            box-shadow: 0 0 20px rgba(79, 70, 229, 0.6);
        }
        .mk-voice-call-dock[data-state="listening"] .mk-voice-orb-core {
            background: #EF4444;
            border-color: #FCA5A5;
            box-shadow: 0 0 24px rgba(239, 68, 68, 0.75);
            transform: scale(1.07);
        }
        .mk-voice-call-dock[data-state="thinking"] .mk-voice-orb-core {
            background: #F59E0B;
            border-color: #FDE68A;
            box-shadow: 0 0 22px rgba(245, 158, 11, 0.65);
        }
        .mk-voice-call-dock[data-state="speaking"] .mk-voice-orb-core {
            background: #10B981;
            border-color: #D4F25C;
            box-shadow: 0 0 26px rgba(16, 185, 129, 0.8);
            animation: mkOrbSpeakWave 0.9s infinite alternate ease-in-out;
        }
        @keyframes mkOrbPulse {
            0%, 100% { transform: scale(0.95); opacity: 0.55; }
            50% { transform: scale(1.28); opacity: 0.95; }
        }
        @keyframes mkOrbSpeakWave {
            0% { transform: scale(0.96); }
            100% { transform: scale(1.12); }
        }
        .mk-selection-popover {
            position: fixed;
            z-index: 999999;
            display: none;
            align-items: center;
            gap: 6px;
            background: #0E1022;
            color: #FFFFFF;
            padding: 6px 8px;
            border-radius: 10px;
            border: 1.5px solid #D4F25C;
            box-shadow: 0 10px 25px rgba(14, 16, 34, 0.35);
            transform: translate(-50%, -100%);
            margin-top: -10px;
        }
        .mk-selection-popover.is-visible {
            display: inline-flex;
        }
        .mk-selection-popover button {
            background: rgba(255, 255, 255, 0.1);
            color: #FFFFFF;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 7px;
            padding: 4px 9px;
            font-size: 11.5px;
            font-weight: 800;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.15s ease;
        }
        .mk-selection-popover button:hover {
            background: #D4F25C;
            color: #0E1022;
            border-color: #D4F25C;
        }

        /* =========================================================
           BARRE D'OUTILS REPLIABLE : SUGGESTIONS IA & CLAVIER SCIENTIFIQUE (fx)
           Permet d'afficher ou masquer à la demande les suggestions et les symboles
           ========================================================= */
        .mk-chat-tools-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 6px;
            padding: 2px 0;
            flex-shrink: 0 !important;
            user-select: none;
        }
        .mk-chat-tools-left {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }
        .mk-tool-toggle-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 750;
            cursor: pointer;
            transition: all 0.16s cubic-bezier(0.16, 1, 0.3, 1);
            border: 1px solid var(--immersion-card-border, #CBD5E1);
            background: var(--immersion-card, #F8FAFC);
            color: var(--immersion-subtext, #475569);
            line-height: 1.2;
            box-shadow: none !important;
        }
        .mk-tool-toggle-btn:hover {
            border-color: #0E1022;
            color: #0E1022;
            background: #FFFFFF;
        }
        .mk-tool-toggle-btn.is-active {
            background: #0E1022;
            color: #FFFFFF;
            border-color: #0E1022;
        }
        .mk-tool-toggle-btn.is-active .mk-tool-badge {
            background: #D4F25C;
            color: #0E1022;
        }
        .mk-tool-toggle-btn.is-active svg.mk-tool-icon {
            stroke: #D4F25C;
        }
        .mk-tool-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 1px 5px;
            border-radius: 999px;
            font-size: 9.5px;
            font-weight: 850;
            background: #E2E8F0;
            color: #0E1022;
            line-height: 1.1;
        }
        .mk-tool-fx-badge {
            font-family: 'Sora', Georgia, serif;
            font-style: italic;
            font-weight: 850;
            font-size: 11px;
            padding: 0 4px;
            border-radius: 4px;
            background: rgba(79, 70, 229, 0.12);
            color: #4F46E5;
        }
        .mk-tool-toggle-btn.is-active .mk-tool-fx-badge {
            background: #D4F25C;
            color: #0E1022;
        }
        .mk-tool-chevron {
            transition: transform 0.2s ease;
            opacity: 0.75;
        }
        .mk-tool-toggle-btn.is-active .mk-tool-chevron {
            transform: rotate(180deg);
            opacity: 1;
        }

        /* Tiroir des Suggestions Revisitées */
        .mk-suggestions-drawer {
            display: flex;
            flex-direction: column;
            gap: 6px;
            background: var(--immersion-card, #F8FAFC);
            border: 1px solid var(--immersion-card-border, #E2E8F0);
            border-radius: 12px;
            padding: 8px 10px;
            flex-shrink: 0 !important;
            max-height: 138px;
            overflow-y: auto;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .mk-suggestions-drawer.is-collapsed {
            display: none !important;
        }
        .mk-suggestions-drawer::-webkit-scrollbar {
            width: 4px;
        }
        .mk-suggestions-drawer::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 4px;
        }
        .mk-drawer-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            padding-bottom: 4px;
            border-bottom: 1px dashed var(--immersion-card-border, #E2E8F0);
        }
        .mk-drawer-title {
            font-size: 10px;
            font-weight: 850;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--immersion-subtext, #64748B);
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .mk-drawer-close-btn {
            background: transparent;
            border: none;
            color: var(--immersion-subtext, #64748B);
            font-size: 10.5px;
            font-weight: 750;
            cursor: pointer;
            padding: 1px 6px;
            border-radius: 5px;
            display: inline-flex;
            align-items: center;
            gap: 3px;
            transition: all 0.15s ease;
        }
        .mk-drawer-close-btn:hover {
            background: rgba(14, 16, 34, 0.08);
            color: var(--immersion-text, #0E1022);
        }

        /* Grille de puces de suggestions modernes */
        .mk-suggestions-list {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }
        .mk-sug-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--immersion-pane, #FFFFFF);
            border: 1px solid var(--immersion-card-border, #CBD5E1);
            color: var(--immersion-text, #0E1022);
            font-size: 11.5px;
            font-weight: 650;
            padding: 5px 10px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.16s cubic-bezier(0.16, 1, 0.3, 1);
            text-align: left;
            line-height: 1.3;
            box-shadow: 0 1px 2px rgba(14, 16, 34, 0.03) !important;
            max-width: 100%;
        }
        .mk-sug-pill:hover {
            border-color: #0E1022;
            background: #0E1022;
            color: #FFFFFF;
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(14, 16, 34, 0.12) !important;
        }
        .mk-sug-pill .mk-sug-arrow {
            font-size: 11px;
            color: #64748B;
            font-weight: 800;
            transition: transform 0.15s ease, color 0.15s ease;
            flex: none;
        }
        .mk-sug-pill:hover .mk-sug-arrow {
            color: #D4F25C;
            transform: translateX(2px);
        }
        .mk-sug-pill--quiz {
            background: #D4F25C !important;
            border: 1.5px solid #0E1022 !important;
            color: #0E1022 !important;
            font-weight: 850 !important;
        }
        .mk-sug-pill--quiz:hover {
            background: #0E1022 !important;
            color: #D4F25C !important;
            border-color: #0E1022 !important;
        }
        .mk-sug-pill--quiz .mk-sug-quiz-tag {
            background: #0E1022;
            color: #D4F25C;
            font-size: 9.5px;
            font-weight: 850;
            padding: 1px 5px;
            border-radius: 4px;
            letter-spacing: 0.02em;
        }
        .mk-sug-pill--quiz:hover .mk-sug-quiz-tag {
            background: #D4F25C;
            color: #0E1022;
        }

        /* Tiroir du Clavier Scientifique Repliable (Masqué par défaut pour libérer l'écran) */
        .mk-scikbd-drawer {
            display: flex;
            flex-direction: column;
            gap: 6px;
            background: var(--immersion-card, #F8FAFC);
            border: 1.5px solid var(--immersion-card-border, #CBD5E1);
            border-radius: 12px;
            padding: 8px 10px;
            flex-shrink: 0 !important;
            box-shadow: 0 6px 16px -6px rgba(14, 16, 34, 0.1);
            animation: dockMenuPop 0.16s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .mk-scikbd-drawer.is-collapsed {
            display: none !important;
        }
        .mk-scikbd-tabs {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .mk-scikbd-tab {
            background: transparent;
            border: 1px solid transparent;
            color: var(--immersion-subtext, #64748B);
            font-size: 10px;
            font-weight: 800;
            padding: 2px 7px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .mk-scikbd-tab:hover {
            color: var(--immersion-text, #0E1022);
            background: rgba(14, 16, 34, 0.05);
        }
        .mk-scikbd-tab.is-active {
            background: #0E1022;
            color: #D4F25C;
        }
        .mk-scikbd-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(42px, 1fr));
            gap: 4px;
        }
        .mk-scikbd-key {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 28px;
            padding: 0 6px;
            background: var(--immersion-pane, #FFFFFF);
            border: 1px solid var(--immersion-card-border, #CBD5E1);
            border-bottom: 2px solid #94A3B8;
            border-radius: 7px;
            color: var(--immersion-text, #0E1022);
            font-family: 'Sora', sans-serif;
            font-size: 11.5px;
            font-weight: 750;
            cursor: pointer;
            transition: all 0.12s ease;
            user-select: none;
            box-shadow: none !important;
        }
        .mk-scikbd-key:hover {
            background: #0E1022;
            color: #D4F25C;
            border-color: #0E1022;
            border-bottom-color: #0E1022;
            transform: translateY(-1px);
        }
        .mk-scikbd-key:active {
            transform: translateY(1px);
            border-bottom-width: 1px;
        }
        /* Bouton fx rapide intégré dans la barre de saisie */
        .immersion-fx-btn {
            background: var(--immersion-card, #F1F5F9);
            border: 1px solid var(--immersion-card-border, #CBD5E1);
            color: #4F46E5;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            flex: none;
            font-family: 'Sora', Georgia, serif;
            font-style: italic;
            font-weight: 850;
            font-size: 13px;
            transition: all 0.15s ease;
            box-shadow: none !important;
        }
        .immersion-fx-btn:hover,
        .immersion-fx-btn.is-active {
            background: #0E1022;
            color: #D4F25C;
            border-color: #0E1022;
        }
    </style>
    @stack('styles')
</head>
<body class="{{ request()->is('student/test-niveau*') ? 'is-focus-mode page-test-niveau' : '' }}">
<div class="p">
    <!-- Pastille Flottante d'Angle en Mode Immersion Plein Écran -->
    <div class="corner-quick-dock" id="cornerQuickDock">
        <div class="corner-dock-trigger" onclick="toggleCornerDockMenu(event)" title="Menu rapide">
            <img src="/assets/9cf747549f194e113309855a69037902.png" alt="Logo" class="corner-dock-logo">
            <span class="corner-dock-pill-text">MonKayier</span>
            <svg class="corner-dock-chevron" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
        </div>
        <div class="corner-dock-menu">
            <div class="corner-dock-header">
                <span style="font-size:12px;font-weight:850;color:#0E1022">Navigation</span>
                <span class="corner-dock-mode-badge">Immersion</span>
            </div>
            <div class="corner-dock-links">
                <a href="/student/parcours" class="corner-dock-link">
                    <svg viewBox="0 0 24 24"><path d="M4 19c4 0 4-6 8-6s4 6 8 6"></path><circle cx="4" cy="19" r="2"></circle><circle cx="20" cy="19" r="2"></circle><path d="M12 4v4"></path><path d="M10 6h4"></path></svg>
                    <span>Tableau de bord (Parcours)</span>
                </a>
                <a href="/student/test-niveau" class="corner-dock-link">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"></circle><circle cx="12" cy="12" r="4"></circle></svg>
                    <span>Test de niveau</span>
                </a>
                <a href="/student/chat" class="corner-dock-link">
                    <svg viewBox="0 0 24 24"><path d="M12 2a8 8 0 0 0-8 8c0 3.37 2.1 6.25 5 7.42V19a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2v-1.58c2.9-1.17 5-4.05 5-7.42a8 8 0 0 0-8-8z"></path><path d="M9 22h6"></path><path d="M10 12h4"></path></svg>
                    <span>Révision IA</span>
                </a>
                <a href="/student/exercises" class="corner-dock-link">
                    <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    <span>Banque d'exercices</span>
                </a>
                <a href="/student/examens" class="corner-dock-link">
                    <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="9" y1="15" x2="15" y2="15"></line></svg>
                    <span>Examens & Annales</span>
                </a>
            </div>
            <div class="corner-dock-footer">
                <a href="/student/parcours" class="corner-dock-exit-btn" onclick="return confirm('Voulez-vous quitter le mode immersion et retourner au tableau de bord ?')">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    <span>Quitter le mode immersion</span>
                </a>
            </div>
        </div>
    </div>

    <div class="lay">
        <!-- Sidebar Navigation Élève -->
        <aside class="sb">
            <div style="display:flex;align-items:center;gap:10px;padding:6px 12px 14px">
                <img src="/assets/9cf747549f194e113309855a69037902.png" alt="Logo" style="height:36px;width:auto">
                <b class="hd" style="font-size:19px">MonKayier</b>
            </div>

            <!-- 1. Parcours (Tableau de bord & Suivi de révision unifié) -->
            <a href="/student/parcours" class="{{ (request()->is('student/parcours*') || request()->is('student/dashboard*') || request()->is('student/seance*')) ? 'on' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M4 19c4 0 4-6 8-6s4 6 8 6"></path><circle cx="4" cy="19" r="2"></circle><circle cx="20" cy="19" r="2"></circle><path d="M12 4v4"></path><path d="M10 6h4"></path></svg>
                Parcours
            </a>

            <!-- 3. Révision IA (Tout-en-un Cours et Tuteur IA) -->
            <a href="/student/chat" class="{{ (request()->is('student/chat*') || request()->is('student/assistant*') || request()->is('student/revision*') || request()->is('student/vocal*')) ? 'on' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M12 2a8 8 0 0 0-8 8c0 3.37 2.1 6.25 5 7.42V19a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2v-1.58c2.9-1.17 5-4.05 5-7.42a8 8 0 0 0-8-8z"></path><path d="M9 22h6"></path><path d="M10 12h4"></path></svg>
                Révision IA
            </a>

            <!-- 4. Banque d'exercices (Générateur & Atelier Immersif Anti-Triche) -->
            <a href="/student/exercises" class="{{ request()->is('student/exercises*') ? 'on' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                Banque d'exercices
            </a>

            <!-- 5. Examens nationaux & Annales BAC (458 épreuves officielles) -->
            <a href="/student/examens" class="{{ request()->is('student/examens*') ? 'on' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="9" y1="15" x2="15" y2="15"></line></svg>
                Examens & Annales
            </a>

            <!-- 6. Bibliothèque (Cours officiels) -->
            <a href="/student/library" class="{{ (request()->is('student/library*') || request()->is('student/courses*') || request()->is('student/bibliotheque*')) ? 'on' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path><path d="M12 6v10"></path></svg>
                Bibliothèque
            </a>

            <!-- 8. Calendrier officiel -->
            <a href="/student/calendar" class="{{ request()->is('student/calendar*') ? 'on' : '' }}">
                <svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2"></rect><path d="M3 10h18M8 3v4M16 3v4"></path></svg>
                Calendrier officiel
            </a>

            <!-- 2. Test de niveau -->
            <a href="/student/test-niveau" class="{{ request()->is('student/test-niveau*') ? 'on' : '' }}">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"></circle><circle cx="12" cy="12" r="4"></circle></svg>
                Test de niveau
            </a>

            <!-- 9. Paramètres -->
            <a href="/student/profile" class="{{ (request()->is('student/profile*') || request()->is('student/history*') || request()->is('student/notifications*') || request()->is('student/help*') || request()->is('student/aide*')) ? 'on' : '' }}">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                Paramètres
            </a>

            <a href="/student/profile" style="display:flex;align-items:center;gap:10px;margin-top:auto;padding:12px;border-radius:12px;background:#F8FAFC;border:1px solid #E2E8F0;text-decoration:none;color:inherit;transition:all 0.2s ease;">
                <div style="width:40px;height:40px;border-radius:50%;background:#D4F25C;font-weight:800;display:flex;align-items:center;justify-content:center;flex:none">{{ $currentStudent->initials ?? 'KM' }}</div>
                <div style="line-height:1.3">
                    <b>{{ $currentStudent->full_name ?? 'Kodjo Mensah' }}</b>
                    <div class="mu">{{ $currentStudent->class_name ?? 'Terminale D' }} · Mon profil</div>
                </div>
            </a>
        </aside>

        <!-- Contenu dynamique de la page -->
        <main class="w">
            @yield('content')
        </main>
    </div>
</div>
<script>
// =========================================================
// GESTION DU MODE FOCUS IMMERSION & DU CORNER QUICK-DOCK
// =========================================================
function toggleFocusMode(forceState) {
    const isCurrentlyFocus = document.body.classList.contains('is-focus-mode');
    const newState = (forceState !== undefined) ? forceState : !isCurrentlyFocus;

    if (newState) {
        document.body.classList.add('is-focus-mode');
        try { localStorage.setItem('mk_focus_mode', '1'); } catch(e){}
    } else {
        document.body.classList.remove('is-focus-mode');
        try { localStorage.setItem('mk_focus_mode', '0'); } catch(e){}
    }

    // Synchronisation du bouton toggle si présent dans la page
    const toggleLabel = document.getElementById('focusToggleLabel');
    if (toggleLabel) {
        toggleLabel.textContent = newState ? 'Quitter le Focus' : 'Plein Écran Focus';
    }
    const toggleBtn = document.getElementById('focusModeToggleBtn');
    if (toggleBtn) {
        if (newState) {
            toggleBtn.classList.add('active-focus');
        } else {
            toggleBtn.classList.remove('active-focus');
        }
    }

    const dock = document.getElementById('cornerQuickDock');
    if (dock) {
        dock.classList.remove('is-open');
    }
}

function toggleCornerDockMenu(e) {
    if (e) {
        e.preventDefault();
        e.stopPropagation();
    }
    const dock = document.getElementById('cornerQuickDock');
    if (dock) {
        dock.classList.toggle('is-open');
    }
}

// Fermeture du menu d'angle au clic extérieur
document.addEventListener('click', function(e) {
    const dock = document.getElementById('cornerQuickDock');
    if (dock && !dock.contains(e.target)) {
        dock.classList.remove('is-open');
    }
});

// Écoute des raccourcis clavier globaux
document.addEventListener('keydown', function(e) {
    // Ne pas intercepter si l'élève est en train d'écrire dans un champ de saisie
    const activeEl = document.activeElement;
    const isTyping = activeEl && (['INPUT', 'TEXTAREA'].includes(activeEl.tagName) || activeEl.isContentEditable);
    
    if (isTyping) {
        // En champ de saisie, seul Échap défocus le champ
        if (e.key === 'Escape') {
            activeEl.blur();
        }
        return;
    }

    // Touche F : Bascule en Mode Focus Immersion
    if ((e.key === 'f' || e.key === 'F') && !e.ctrlKey && !e.metaKey && !e.altKey) {
        const focusBtn = document.getElementById('focusModeToggleBtn');
        if (focusBtn) {
            e.preventDefault();
            toggleFocusMode();
        }
    }

    // Touche Échap : Sortie immédiate du Mode Focus
    if (e.key === 'Escape' && document.body.classList.contains('is-focus-mode')) {
        e.preventDefault();
        toggleFocusMode(false);
    }
});

// =========================================================
// MOTEUR UNIFIÉ : FORMATAGE RÉPONSES IA & VOIX NEURALE HUMAINE
// =========================================================
window.mkCurrentAudio = null;
window.mkCurrentTtsBtn = null;

window.mkFormatAiHtml = function(rawText) {
    if (!rawText) return '';
    let text = String(rawText).trim();
    // Si déjà formaté côté serveur avec nos classes .mk-ai-, envelopper simplement
    if (text.includes('class="mk-ai-')) {
        return `<div class="mk-ai-formatted">${text}</div>`;
    }

    // 1. Protéger les blocs mathématiques LaTeX avant le parsing Markdown
    const mathBlocks = [];
    text = text.replace(/(\$\$[\s\S]*?\$\$|\\\[[\s\S]*?\\\]|\$[^\$\n]+?\$|\\\([\s\S]*?\\\))/g, function(m) {
        const key = '@@MK_MATH_' + mathBlocks.length + '@@';
        mathBlocks.push(m);
        return key;
    });

    // 2. Convertir le gras **texte** et l'italique *texte*
    text = text.replace(/\*\*(.+?)\*\*/gs, '<strong class="mk-ai-bold">$1</strong>');
    text = text.replace(/__(.+?)__/gs, '<strong class="mk-ai-bold">$1</strong>');
    text = text.replace(/(?<!\*)\*([^\*\n]+)\*(?!\*)/g, '<em>$1</em>');
    text = text.replace(/\*\*/g, '');

    // 3. Structurer ligne par ligne (Titres, Listes, Paragraphes)
    const lines = text.split(/\r?\n/);
    const out = [];
    let inUl = false;
    let inOl = false;

    const closeLists = () => {
        if (inUl) { out.push('</ul>'); inUl = false; }
        if (inOl) { out.push('</ol>'); inOl = false; }
    };

    for (let rawLine of lines) {
        let line = rawLine.trim();
        if (!line || line === '---' || line === '***') {
            closeLists();
            continue;
        }
        const hMatch = line.match(/^#{1,4}\s*(.+)$/);
        if (hMatch) {
            closeLists();
            out.push(`<div class="mk-ai-heading">${hMatch[1].replace(/^[\*#\s]+|[\*#:\s]+$/g, '')}</div>`);
            continue;
        }
        const ulMatch = line.match(/^(?:[-*•])\s+(.+)$/);
        if (ulMatch) {
            if (inOl) { out.push('</ol>'); inOl = false; }
            if (!inUl) { out.push('<ul class="mk-ai-ul">'); inUl = true; }
            out.push(`<li class="mk-ai-li">${ulMatch[1]}</li>`);
            continue;
        }
        const olMatch = line.match(/^(\d+)[\.\)]\s+(.+)$/);
        if (olMatch) {
            if (inUl) { out.push('</ul>'); inUl = false; }
            if (!inOl) { out.push('<ol class="mk-ai-ol">'); inOl = true; }
            out.push(`<li class="mk-ai-li">${olMatch[2]}</li>`);
            continue;
        }
        closeLists();
        out.push(`<p class="mk-ai-p">${line}</p>`);
    }
    closeLists();

    let finalHtml = `<div class="mk-ai-formatted">${out.join('\n')}</div>`;
    mathBlocks.forEach((latex, idx) => {
        finalHtml = finalHtml.replace('@@MK_MATH_' + idx + '@@', latex);
    });
    return finalHtml;
};

window.mkPrepareSpeechText = function(rawText) {
    if (!rawText) return '';
    let s = String(rawText).replace(/<\/(p|div|li|h[1-6])>/gi, '. ');
    s = s.replace(/<[^>]+>/g, ' ');
    s = s.replace(/\\lim_\{?\s*x\s*\\to\s*\+\\infty\s*\}?/g, " la limite quand x tend vers plus l'infini de ");
    s = s.replace(/\\lim_\{?\s*x\s*\\to\s*-\\infty\s*\}?/g, " la limite quand x tend vers moins l'infini de ");
    s = s.replace(/\\frac\{([^\}]+)\}\{([^\}]+)\}/g, ' $1 sur $2 ');
    s = s.replace(/\\sqrt\{([^\}]+)\}/g, ' racine carrée de $1 ');
    s = s.replace(/\^\{2\}|\^2|²/g, ' au carré ');
    s = s.replace(/\^\{3\}|\^3|³/g, ' au cube ');
    s = s.replace(/\\in\s*\\mathbb\{R\}/g, " appartient à l'ensemble R ");
    s = s.replace(/\\mathbb\{R\}/g, ' R ');
    s = s.replace(/\\Rightarrow|⇒/g, ' implique que ');
    s = s.replace(/\\Leftrightarrow|⇔/g, ' équivaut à ');
    s = s.replace(/\\to|→/g, ' tend vers ');
    s = s.replace(/\\infty|∞/g, " l'infini ");
    s = s.replace(/\$\$|\$|\\\[|\\\]|\\\(|\\\)|\*\*|###|##|#|`|~|\{|\}|\\|•/g, ' ');
    s = s.replace(/\s+/g, ' ').trim();
    return s;
};

window.mkResetTtsBtn = function(btn) {
    if (!btn) return;
    btn.classList.remove('is-speaking');
    btn.innerHTML = `<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon><path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path></svg><span>Écouter</span>`;
};

window.mkSpeechSessionId = 0;

window.mkStopSpeech = function() {
    window.mkSpeechSessionId++;
    if (window.mkSpeechKeepAliveTimer) {
        clearInterval(window.mkSpeechKeepAliveTimer);
        window.mkSpeechKeepAliveTimer = null;
    }
    if (window.mkCurrentAudio) {
        try {
            window.mkCurrentAudio.onended = null;
            window.mkCurrentAudio.onerror = null;
            window.mkCurrentAudio.pause();
            window.mkCurrentAudio.currentTime = 0;
        } catch (e) {}
        window.mkCurrentAudio = null;
    }
    if ('speechSynthesis' in window) {
        window.speechSynthesis.cancel();
    }
    if (window.mkCurrentTtsBtn) {
        window.mkResetTtsBtn(window.mkCurrentTtsBtn);
        window.mkCurrentTtsBtn = null;
    }
};

// Découpe intelligente d'un texte long en segments naturels (phrases entières)
// Le tout premier segment est volontairement court (~165 car.) pour démarrer la voix en moins de 0,8 seconde !
window.mkSplitTextIntoSpeechChunks = function(rawText, maxChunkLen) {
    const limit = maxChunkLen || 460;
    const firstChunkLimit = Math.min(limit, 165);
    const cleaned = window.mkPrepareSpeechText(rawText);
    if (!cleaned) return [];
    if (cleaned.length <= firstChunkLimit) return [cleaned];

    // Découper par phrases (. ! ? ; :)
    const sentences = cleaned.match(/[^.!?;:]+[.!?;:]+\s*|[^.!?;:]+$/g) || [cleaned];
    const chunks = [];
    let current = '';

    for (let i = 0; i < sentences.length; i++) {
        const s = sentences[i].trim();
        if (!s) continue;

        const activeLimit = (chunks.length === 0) ? firstChunkLimit : limit;

        if ((current + ' ' + s).trim().length <= activeLimit) {
            current = (current ? current + ' ' : '') + s;
        } else {
            if (current) chunks.push(current);
            const nextLimit = (chunks.length === 0) ? firstChunkLimit : limit;
            if (s.length <= nextLimit) {
                current = s;
            } else {
                // Si une seule phrase dépasse la limite, découper aux virgules ou espaces
                const subParts = s.split(/,\s*/);
                let subCurr = '';
                for (let j = 0; j < subParts.length; j++) {
                    const part = subParts[j] + (j < subParts.length - 1 ? ',' : '');
                    const subLimit = (chunks.length === 0) ? firstChunkLimit : limit;
                    if ((subCurr + ' ' + part).trim().length <= subLimit) {
                        subCurr = (subCurr ? subCurr + ' ' : '') + part;
                    } else {
                        if (subCurr) chunks.push(subCurr);
                        subCurr = part;
                    }
                }
                current = subCurr;
            }
        }
    }
    if (current) chunks.push(current);
    return chunks;
};

window.mkFallbackBrowserNeuralSpeech = function(text, voiceKey, onEndCallback, sessionId) {
    const activeSession = sessionId !== undefined ? sessionId : window.mkSpeechSessionId;
    if (!('speechSynthesis' in window)) {
        if (onEndCallback) onEndCallback();
        return;
    }
    window.speechSynthesis.cancel();

    // Découper en phrases courtes (max 180 caractères) pour contourner le bug Chromium des 14 secondes
    const chunks = window.mkSplitTextIntoSpeechChunks(text, 180);
    if (chunks.length === 0) {
        if (onEndCallback) onEndCallback();
        return;
    }

    const voices = window.speechSynthesis.getVoices().filter(v => v.lang && v.lang.toLowerCase().startsWith('fr'));
    const preferred = voices.find(v => /natural|neural|online|remy|vivienne|henri|denise|google/i.test(v.name)) || voices[0];

    let idx = 0;
    if (window.mkSpeechKeepAliveTimer) clearInterval(window.mkSpeechKeepAliveTimer);
    window.mkSpeechKeepAliveTimer = setInterval(() => {
        if (window.speechSynthesis.speaking && !window.speechSynthesis.paused) {
            window.speechSynthesis.pause();
            window.speechSynthesis.resume();
        }
    }, 10000);

    const speakNext = () => {
        if (activeSession !== window.mkSpeechSessionId) return;
        if (idx >= chunks.length) {
            if (window.mkSpeechKeepAliveTimer) {
                clearInterval(window.mkSpeechKeepAliveTimer);
                window.mkSpeechKeepAliveTimer = null;
            }
            if (onEndCallback) onEndCallback();
            return;
        }
        const utter = new SpeechSynthesisUtterance(chunks[idx++]);
        utter.lang = 'fr-FR';
        if (preferred) utter.voice = preferred;
        utter.rate = 1.02;
        utter.pitch = voiceKey === 'afi' ? 1.06 : 1.0;
        utter.onend = speakNext;
        utter.onerror = speakNext;
        window.speechSynthesis.speak(utter);
    };

    speakNext();
};

window.mkSpeakHuman = function(text, btnEl, voiceOverride, onComplete) {
    // Si on clique sur le bouton déjà en cours de lecture -> Arrêter
    if (btnEl && window.mkCurrentTtsBtn === btnEl && (window.mkCurrentAudio || (window.speechSynthesis && window.speechSynthesis.speaking))) {
        window.mkStopSpeech();
        return;
    }

    window.mkStopSpeech();
    const mySessionId = window.mkSpeechSessionId;

    const voiceKey = voiceOverride || localStorage.getItem('mk_tutor_voice') || 'kodjo';
    if (btnEl) {
        window.mkCurrentTtsBtn = btnEl;
        btnEl.classList.add('is-speaking');
        btnEl.innerHTML = `<span class="mk-tts-wave"><i></i><i></i><i></i></span><span>Arrêter</span>`;
    }

    const finish = () => {
        if (mySessionId !== window.mkSpeechSessionId) return;
        if (btnEl) window.mkResetTtsBtn(btnEl);
        if (window.mkCurrentTtsBtn === btnEl) window.mkCurrentTtsBtn = null;
        window.mkCurrentAudio = null;
        if (typeof onComplete === 'function') onComplete();
    };

    // Découper le texte en segments de ~450 caractères pour que :
    // 1) Le 1er segment démarre en moins d'une seconde
    // 2) Les segments suivants soient préchargés en arrière-plan pendant l'écoute
    // 3) Toute la réponse (même très longue avec des listes) soit lue jusqu'au dernier mot sans coupure
    const chunks = window.mkSplitTextIntoSpeechChunks(text, 450);
    if (chunks.length === 0) {
        finish();
        return;
    }

    const fetchChunkAudioUrl = (chunkText) => {
        return fetch('/student/chat/tts', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                text: chunkText,
                voice: voiceKey
            })
        })
        .then(r => r.json())
        .then(data => (data && data.success && data.audio_url) ? data.audio_url : null)
        .catch(() => null);
    };

    // Précharger le premier segment immédiatement, puis lancer le suivant pendant la lecture
    let currentIdx = 0;
    const cachedPromises = new Array(chunks.length);
    cachedPromises[0] = fetchChunkAudioUrl(chunks[0]);
    if (chunks.length > 1) {
        cachedPromises[1] = fetchChunkAudioUrl(chunks[1]);
    }

    const playChunkAt = (index) => {
        if (mySessionId !== window.mkSpeechSessionId) return;
        if (index >= chunks.length) {
            finish();
            return;
        }

        // Précharger le segment index + 2 en avance
        if (index + 1 < chunks.length && !cachedPromises[index + 1]) {
            cachedPromises[index + 1] = fetchChunkAudioUrl(chunks[index + 1]);
        }
        if (index + 2 < chunks.length && !cachedPromises[index + 2]) {
            cachedPromises[index + 2] = fetchChunkAudioUrl(chunks[index + 2]);
        }

        cachedPromises[index].then(audioUrl => {
            if (mySessionId !== window.mkSpeechSessionId) return;
            if (audioUrl) {
                const audio = new Audio(audioUrl);
                audio.preload = 'auto';
                window.mkCurrentAudio = audio;
                audio.onended = () => playChunkAt(index + 1);
                audio.onerror = () => {
                    window.mkFallbackBrowserNeuralSpeech(chunks.slice(index).join(' '), voiceKey, finish, mySessionId);
                };
                audio.play().catch(() => {
                    window.mkFallbackBrowserNeuralSpeech(chunks.slice(index).join(' '), voiceKey, finish, mySessionId);
                });
            } else {
                window.mkFallbackBrowserNeuralSpeech(chunks.slice(index).join(' '), voiceKey, finish, mySessionId);
            }
        });
    };

    playChunkAt(0);
};

// =========================================================
// AUTO-REDIMENSIONNEMENT VERTICAL DES CHAMPS DE SAISIE CHAT
// =========================================================
window.mkAutoResizeTextarea = function(el) {
    if (!el || el.tagName !== 'TEXTAREA') return;
    el.style.height = 'auto';
    const maxH = 140;
    const nextH = Math.min(maxH, Math.max(24, el.scrollHeight));
    el.style.height = nextH + 'px';
    el.style.overflowY = el.scrollHeight > maxH ? 'auto' : 'hidden';
};

document.addEventListener('input', function(e) {
    if (e.target && e.target.tagName === 'TEXTAREA' && (
        e.target.classList.contains('immersion-input-field') ||
        e.target.classList.contains('tutor-text-input') ||
        e.target.classList.contains('mk-auto-textarea')
    )) {
        window.mkAutoResizeTextarea(e.target);
    }
});

// =========================================================
// REDIMENSIONNEMENT FLUIDE DE LA COLONNE TUTEUR IA (SPLIT-PANE)
// =========================================================
window.mkGetSavedAiWidth = function(defaultPx) {
    const saved = parseInt(localStorage.getItem('mk_ai_pane_width') || '', 10);
    if (!isNaN(saved) && saved >= 280 && saved <= 1100) {
        return saved;
    }
    return defaultPx || 390;
};

window.mkApplyAiPaneWidth = function(splitContainer, aiPane, widthPx) {
    if (!splitContainer || !aiPane) return;
    const rect = splitContainer.getBoundingClientRect();
    const totalW = rect.width || window.innerWidth;
    const minW = 280;
    const maxW = Math.max(420, Math.floor(totalW * 0.72));
    const clampedW = Math.min(maxW, Math.max(minW, Math.round(widthPx)));

    const computedDisplay = window.getComputedStyle(splitContainer).display;
    if (computedDisplay === 'grid') {
        splitContainer.style.gridTemplateColumns = `1fr ${clampedW}px`;
    } else {
        aiPane.style.width = `${clampedW}px`;
        aiPane.style.minWidth = `${clampedW}px`;
        aiPane.style.maxWidth = `${clampedW}px`;
        aiPane.style.flex = `0 0 ${clampedW}px`;
    }
    return clampedW;
};

window.mkInitSplitResizers = function() {
    const configs = [
        { splitId: 'workshopSplitGrid', paneId: 'workshopAiColumn' },
        { splitId: 'immersionSplitBody', paneId: 'immersionAiColumn' },
        { splitSelector: '.atelier-body', paneId: 'atelierTutorSidebar' }
    ];

    configs.forEach(cfg => {
        const aiPane = document.getElementById(cfg.paneId);
        if (!aiPane) return;
        const splitContainer = cfg.splitId ? document.getElementById(cfg.splitId) : aiPane.closest(cfg.splitSelector);
        if (!splitContainer) return;

        // Créer la barre de redimensionnement si elle n'existe pas encore
        let resizer = aiPane.querySelector('.mk-split-resizer');
        if (!resizer) {
            resizer = document.createElement('div');
            resizer.className = 'mk-split-resizer';
            resizer.title = "Glisser vers la gauche ou la droite pour agrandir ou réduire l'espace du Tuteur IA (Double-clic pour alterner large / standard)";
            resizer.innerHTML = '<div class="mk-split-resizer-grip"></div>';
            aiPane.insertBefore(resizer, aiPane.firstChild);
        }

        if (resizer.dataset.mkBound === '1') return;
        resizer.dataset.mkBound = '1';

        // Appliquer la largeur mémorisée au chargement si le volet est visible
        const savedW = window.mkGetSavedAiWidth(390);
        if (aiPane.style.display !== 'none' && !aiPane.classList.contains('is-collapsed')) {
            window.mkApplyAiPaneWidth(splitContainer, aiPane, savedW);
        }

        let isDragging = false;

        const onMove = function(clientX) {
            if (!isDragging) return;
            const rect = splitContainer.getBoundingClientRect();
            const newWidth = rect.right - clientX;
            const applied = window.mkApplyAiPaneWidth(splitContainer, aiPane, newWidth);
            if (applied) {
                try { localStorage.setItem('mk_ai_pane_width', String(applied)); } catch(e) {}
            }
        };

        const onMouseMove = function(e) {
            onMove(e.clientX);
        };

        const onTouchMove = function(e) {
            if (e.touches && e.touches.length > 0) {
                onMove(e.touches[0].clientX);
            }
        };

        const stopDrag = function() {
            if (!isDragging) return;
            isDragging = false;
            resizer.classList.remove('is-dragging');
            document.body.classList.remove('mk-is-resizing-pane');
            splitContainer.classList.remove('mk-no-grid-transition');
            aiPane.classList.remove('mk-no-grid-transition');
            window.removeEventListener('mousemove', onMouseMove);
            window.removeEventListener('mouseup', stopDrag);
            window.removeEventListener('touchmove', onTouchMove);
            window.removeEventListener('touchend', stopDrag);
        };

        const startDrag = function(e) {
            e.preventDefault();
            e.stopPropagation();
            isDragging = true;
            resizer.classList.add('is-dragging');
            document.body.classList.add('mk-is-resizing-pane');
            splitContainer.classList.add('mk-no-grid-transition');
            aiPane.classList.add('mk-no-grid-transition');
            window.addEventListener('mousemove', onMouseMove);
            window.addEventListener('mouseup', stopDrag);
            window.addEventListener('touchmove', onTouchMove, { passive: false });
            window.addEventListener('touchend', stopDrag);
        };

        resizer.addEventListener('mousedown', startDrag);
        resizer.addEventListener('touchstart', startDrag, { passive: false });

        // Double-clic sur la ligne : basculer entre grande largeur de lecture (600px) et largeur standard (390px)
        resizer.addEventListener('dblclick', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const currentW = aiPane.getBoundingClientRect().width;
            const targetW = currentW < 480 ? 600 : 390;
            const applied = window.mkApplyAiPaneWidth(splitContainer, aiPane, targetW);
            if (applied) {
                try { localStorage.setItem('mk_ai_pane_width', String(applied)); } catch(err) {}
            }
        });
    });
};

// =========================================================
// BULLE CONTEXTUELLE DE SÉLECTION DU COURS ("EXPLIQUER CE PASSAGE")
// =========================================================
window.mkInitSelectionAssistant = function() {
    if (document.getElementById('mkSelectionPopover')) return;

    const pop = document.createElement('div');
    pop.id = 'mkSelectionPopover';
    pop.className = 'mk-selection-popover';
    pop.innerHTML = `
        <button type="button" data-action="explain" title="Demander au Tuteur IA d'expliquer ce passage avec une analogie simple">Expliquer simplement</button>
        <button type="button" data-action="example" title="Demander un exemple concret sur ce passage">Exemple concret</button>
        <button type="button" data-action="listen" title="Écouter ce passage avec la voix neurale">Écouter</button>
    `;
    document.body.appendChild(pop);

    let currentSelectionText = '';

    const hidePopover = () => {
        pop.classList.remove('is-visible');
    };

    pop.addEventListener('mousedown', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const btn = e.target.closest('button');
        if (!btn || !currentSelectionText) return;
        const action = btn.getAttribute('data-action');
        const snippet = currentSelectionText.length > 260 ? currentSelectionText.substring(0, 260) + '...' : currentSelectionText;

        if (action === 'listen') {
            window.mkSpeakHuman(snippet);
            hidePopover();
            return;
        }

        const prompt = (action === 'explain')
            ? `Explique-moi très simplement et avec une analogie ce passage du cours : « ${snippet} »`
            : `Donne-moi un exemple concret et corrigé pour bien comprendre ce passage : « ${snippet} »`;

        hidePopover();
        window.getSelection().removeAllRanges();

        if (typeof window.sendSuggestion === 'function') {
            window.sendSuggestion(prompt);
        } else if (typeof window.askBiblioPrompt === 'function') {
            window.askBiblioPrompt(prompt);
        } else if (typeof window.askTutorPrompt === 'function') {
            window.askTutorPrompt(prompt);
        }
    });

    document.addEventListener('mouseup', function(e) {
        if (pop.contains(e.target)) return;
        setTimeout(() => {
            const sel = window.getSelection();
            const text = sel ? sel.toString().trim() : '';
            if (!text || text.length < 5) {
                hidePopover();
                return;
            }

            // Vérifier que la sélection se trouve dans une zone de cours ou d'exercice
            const anchorNode = sel.anchorNode;
            const elem = anchorNode ? (anchorNode.nodeType === 1 ? anchorNode : anchorNode.parentElement) : null;
            if (!elem || !elem.closest('#workshopLeftCol, #immersionCourseSheet, .course-sheet-container, .atelier-subject-pane')) {
                hidePopover();
                return;
            }

            currentSelectionText = text;
            const range = sel.getRangeAt(0);
            const rect = range.getBoundingClientRect();
            if (rect.width === 0 && rect.height === 0) {
                hidePopover();
                return;
            }

            const top = Math.max(48, rect.top);
            const left = Math.min(window.innerWidth - 160, Math.max(160, rect.left + rect.width / 2));
            pop.style.top = `${top}px`;
            pop.style.left = `${left}px`;
            pop.classList.add('is-visible');
        }, 20);
    });

    document.addEventListener('scroll', hidePopover, true);
};

// =========================================================
// GESTIONNAIRE GLOBAL DES TIROIRS REPLIABLES (SUGGESTIONS & CLAVIER SCIENTIFIQUE fx)
// =========================================================
window.mkToggleChatDrawer = function(drawerType, prefix, forceState) {
    const pfx = prefix || 'chat';
    const sugDrawer = document.getElementById(pfx + 'SuggestionsDrawer');
    const kbdDrawer = document.getElementById(pfx + 'SciKbdDrawer');
    const sugBtn = document.getElementById(pfx + 'BtnToggleSug');
    const kbdBtn = document.getElementById(pfx + 'BtnToggleKbd');
    const fxInlineBtn = document.getElementById(pfx + 'FxInlineBtn');

    if (drawerType === 'suggestions' && sugDrawer) {
        const currentlyOpen = !sugDrawer.classList.contains('is-collapsed');
        const nextOpen = (typeof forceState === 'boolean') ? forceState : !currentlyOpen;
        sugDrawer.classList.toggle('is-collapsed', !nextOpen);
        if (sugBtn) {
            sugBtn.classList.toggle('is-active', nextOpen);
            const labelSpan = sugBtn.querySelector('.mk-tool-state-text');
            if (labelSpan) labelSpan.textContent = nextOpen ? 'Masquer idées' : 'Idées & Quiz';
        }
    } else if (drawerType === 'scikbd' && kbdDrawer) {
        const currentlyOpen = !kbdDrawer.classList.contains('is-collapsed');
        const nextOpen = (typeof forceState === 'boolean') ? forceState : !currentlyOpen;
        kbdDrawer.classList.toggle('is-collapsed', !nextOpen);
        if (kbdBtn) {
            kbdBtn.classList.toggle('is-active', nextOpen);
            const labelSpan = kbdBtn.querySelector('.mk-tool-state-text');
            if (labelSpan) labelSpan.textContent = nextOpen ? 'Masquer clavier' : 'Clavier scientifique';
        }
        if (fxInlineBtn) {
            fxInlineBtn.classList.toggle('is-active', nextOpen);
        }
    }
};

window.mkFilterSciKbd = function(tabBtn, category, gridId) {
    const parentTabs = tabBtn ? tabBtn.closest('.mk-scikbd-tabs') : null;
    if (parentTabs) {
        parentTabs.querySelectorAll('.mk-scikbd-tab').forEach(b => b.classList.remove('is-active'));
        tabBtn.classList.add('is-active');
    }
    const grid = document.getElementById(gridId);
    if (!grid) return;
    grid.querySelectorAll('.mk-scikbd-key').forEach(key => {
        const cat = key.getAttribute('data-cat') || 'all';
        if (category === 'all' || cat === category) {
            key.style.display = 'inline-flex';
        } else {
            key.style.display = 'none';
        }
    });
};

window.mkCreateSuggestionPill = function(text, onClickFn) {
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'mk-sug-pill';
    const textSpan = document.createElement('span');
    textSpan.textContent = text;
    const arrowSpan = document.createElement('span');
    arrowSpan.className = 'mk-sug-arrow';
    arrowSpan.textContent = '→';
    btn.appendChild(textSpan);
    btn.appendChild(arrowSpan);
    if (typeof onClickFn === 'function') {
        btn.onclick = onClickFn;
    }
    return btn;
};

window.mkInsertSymbolToInput = function(inputId, symbol) {
    const el = document.getElementById(inputId);
    if (!el) return;
    const start = el.selectionStart || 0;
    const end = el.selectionEnd || 0;
    const val = el.value || '';
    el.value = val.substring(0, start) + symbol + val.substring(end);
    el.focus();
    const newPos = start + symbol.length;
    try { el.setSelectionRange(newPos, newPos); } catch (e) {}
    if (window.mkAutoResizeTextarea) window.mkAutoResizeTextarea(el);
};

// =========================================================
// BULLE CONTEXTUELLE DE SÉLECTION DU COURS ("EXPLIQUER CE PASSAGE")
// =========================================================
window.mkInitSelectionAssistant = function() {
    if (document.getElementById('mkSelectionPopover')) return;

    const pop = document.createElement('div');
    pop.id = 'mkSelectionPopover';
    pop.className = 'mk-selection-popover';
    pop.innerHTML = `
        <button type="button" data-action="explain" title="Demander au Tuteur IA d'expliquer ce passage avec une analogie simple">Expliquer simplement</button>
        <button type="button" data-action="example" title="Demander un exemple concret sur ce passage">Exemple concret</button>
        <button type="button" data-action="listen" title="Écouter ce passage avec la voix neurale">Écouter</button>
    `;
    document.body.appendChild(pop);

    let currentSelectionText = '';

    const hidePopover = () => {
        pop.classList.remove('is-visible');
    };

    pop.addEventListener('mousedown', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const btn = e.target.closest('button');
        if (!btn || !currentSelectionText) return;
        const action = btn.getAttribute('data-action');
        const snippet = currentSelectionText.length > 260 ? currentSelectionText.substring(0, 260) + '...' : currentSelectionText;

        if (action === 'listen') {
            window.mkSpeakHuman(snippet);
            hidePopover();
            return;
        }

        const prompt = (action === 'explain')
            ? `Explique-moi très simplement et avec une analogie ce passage du cours : « ${snippet} »`
            : `Donne-moi un exemple concret et corrigé pour bien comprendre ce passage : « ${snippet} »`;

        hidePopover();
        window.getSelection().removeAllRanges();

        if (typeof window.sendSuggestion === 'function') {
            window.sendSuggestion(prompt);
        } else if (typeof window.askBiblioPrompt === 'function') {
            window.askBiblioPrompt(prompt);
        } else if (typeof window.askTutorPrompt === 'function') {
            window.askTutorPrompt(prompt);
        }
    });

    document.addEventListener('mouseup', function(e) {
        if (pop.contains(e.target)) return;
        setTimeout(() => {
            const sel = window.getSelection();
            const text = sel ? sel.toString().trim() : '';
            if (!text || text.length < 5) {
                hidePopover();
                return;
            }

            // Vérifier que la sélection se trouve dans une zone de cours ou d'exercice
            const anchorNode = sel.anchorNode;
            const elem = anchorNode ? (anchorNode.nodeType === 1 ? anchorNode : anchorNode.parentElement) : null;
            if (!elem || !elem.closest('#workshopLeftCol, #immersionCourseSheet, .course-sheet-container, .atelier-subject-pane')) {
                hidePopover();
                return;
            }

            currentSelectionText = text;
            const range = sel.getRangeAt(0);
            const rect = range.getBoundingClientRect();
            if (rect.width === 0 && rect.height === 0) {
                hidePopover();
                return;
            }

            const top = Math.max(48, rect.top);
            const left = Math.min(window.innerWidth - 160, Math.max(160, rect.left + rect.width / 2));
            pop.style.top = `${top}px`;
            pop.style.left = `${left}px`;
            pop.classList.add('is-visible');
        }, 20);
    });

    document.addEventListener('scroll', hidePopover, true);
};

// =========================================================
// GESTIONNAIRE GLOBAL DES TIROIRS REPLIABLES (SUGGESTIONS & CLAVIER SCIENTIFIQUE fx)
// =========================================================
window.mkToggleChatDrawer = function(drawerType, prefix, forceState) {
    const pfx = prefix || 'chat';
    const sugDrawer = document.getElementById(pfx + 'SuggestionsDrawer');
    const kbdDrawer = document.getElementById(pfx + 'SciKbdDrawer');
    const sugBtn = document.getElementById(pfx + 'BtnToggleSug');
    const kbdBtn = document.getElementById(pfx + 'BtnToggleKbd');
    const fxInlineBtn = document.getElementById(pfx + 'FxInlineBtn');

    if (drawerType === 'suggestions' && sugDrawer) {
        const currentlyOpen = !sugDrawer.classList.contains('is-collapsed');
        const nextOpen = (typeof forceState === 'boolean') ? forceState : !currentlyOpen;
        sugDrawer.classList.toggle('is-collapsed', !nextOpen);
        if (sugBtn) {
            sugBtn.classList.toggle('is-active', nextOpen);
            const labelSpan = sugBtn.querySelector('.mk-tool-state-text');
            if (labelSpan) labelSpan.textContent = nextOpen ? 'Masquer idées' : 'Idées & Quiz';
        }
    } else if (drawerType === 'scikbd' && kbdDrawer) {
        const currentlyOpen = !kbdDrawer.classList.contains('is-collapsed');
        const nextOpen = (typeof forceState === 'boolean') ? forceState : !currentlyOpen;
        kbdDrawer.classList.toggle('is-collapsed', !nextOpen);
        if (kbdBtn) {
            kbdBtn.classList.toggle('is-active', nextOpen);
            const labelSpan = kbdBtn.querySelector('.mk-tool-state-text');
            if (labelSpan) labelSpan.textContent = nextOpen ? 'Masquer clavier' : 'Clavier scientifique';
        }
        if (fxInlineBtn) {
            fxInlineBtn.classList.toggle('is-active', nextOpen);
        }
    }
};

window.mkFilterSciKbd = function(tabBtn, category, gridId) {
    const parentTabs = tabBtn ? tabBtn.closest('.mk-scikbd-tabs') : null;
    if (parentTabs) {
        parentTabs.querySelectorAll('.mk-scikbd-tab').forEach(b => b.classList.remove('is-active'));
        tabBtn.classList.add('is-active');
    }
    const grid = document.getElementById(gridId);
    if (!grid) return;
    grid.querySelectorAll('.mk-scikbd-key').forEach(key => {
        const cat = key.getAttribute('data-cat') || 'all';
        if (category === 'all' || cat === category) {
            key.style.display = 'inline-flex';
        } else {
            key.style.display = 'none';
        }
    });
};

window.mkCreateSuggestionPill = function(text, onClickFn) {
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'mk-sug-pill';
    const textSpan = document.createElement('span');
    textSpan.textContent = text;
    const arrowSpan = document.createElement('span');
    arrowSpan.className = 'mk-sug-arrow';
    arrowSpan.textContent = '→';
    btn.appendChild(textSpan);
    btn.appendChild(arrowSpan);
    if (typeof onClickFn === 'function') {
        btn.onclick = onClickFn;
    }
    return btn;
};

window.mkInsertSymbolToInput = function(inputId, symbol) {
    const el = document.getElementById(inputId);
    if (!el) return;
    const start = el.selectionStart || 0;
    const end = el.selectionEnd || 0;
    const val = el.value || '';
    el.value = val.substring(0, start) + symbol + val.substring(end);
    el.focus();
    const newPos = start + symbol.length;
    try { el.setSelectionRange(newPos, newPos); } catch (e) {}
    if (window.mkAutoResizeTextarea) window.mkAutoResizeTextarea(el);
};

document.addEventListener('DOMContentLoaded', function() {
    window.mkInitSplitResizers();
    window.mkInitSelectionAssistant();
});
</script>
@stack('scripts')
</body>
</html>
