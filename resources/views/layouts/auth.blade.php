<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'MONKAYIER — Connexion & Inscription')</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="shortcut icon" href="/favicon.ico">
    <meta name="description" content="Plateforme d'apprentissage intelligente pour le Togo.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/tokens.css">
    <style>
        :root {
            --auth-navy: #14325C;
            --auth-indigo: #5143E8;
            --auth-indigo-hover: #4338CA;
            --auth-sky: #3FA9F5;
            --auth-yellow: #FFB703;
            --auth-text-dark: #111126;
            --auth-text-muted: #5A6275;
            --auth-border: #CBD6E6;
            --auth-bg-input: #FFFFFF;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Poppins', system-ui, -apple-system, sans-serif;
            color: var(--auth-text-dark);
            background: #FFFFFF;
            min-height: 100vh;
            display: flex;
            -webkit-font-smoothing: antialiased;
        }

        .auth-container {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* ── Left Hero Panel (Dark Navy) ── */
        .auth-side-panel {
            width: 38%;
            min-width: 380px;
            max-width: 540px;
            background: var(--auth-navy);
            color: #FFFFFF;
            padding: 64px 52px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.06);
            flex-shrink: 0;
        }

        .auth-logo-link {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: #FFFFFF;
        }
        .auth-logo-img {
            height: 44px;
            width: auto;
            display: block;
        }
        .auth-logo-text {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.01em;
            color: #FFFFFF;
        }

        .auth-side-content {
            margin: auto 0;
            padding: 48px 0;
        }
        .auth-side-title {
            font-size: 44px;
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -0.02em;
            color: #FFFFFF;
            margin-bottom: 20px;
        }
        .auth-side-subtitle {
            font-size: 16px;
            color: #C9D8EE;
            line-height: 1.6;
            max-width: 380px;
            font-weight: 400;
        }

        .auth-side-bars {
            display: flex;
            align-items: center;
            gap: 6px;
            height: 6px;
            max-width: 240px;
        }
        .bar-white { width: 90px; height: 5px; border-radius: 3px; background: #FFFFFF; }
        .bar-blue { width: 55px; height: 5px; border-radius: 3px; background: var(--auth-sky); }
        .bar-yellow { width: 40px; height: 5px; border-radius: 3px; background: var(--auth-yellow); }

        /* ── Right Content Form Panel ── */
        .auth-main-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 32px;
            background: #FFFFFF;
            overflow-y: auto;
        }

        .auth-form-card {
            max-width: 480px;
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 20px;
            animation: fadeInAuth 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes fadeInAuth {
            0% { opacity: 0; transform: translateY(8px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        /* ── Common Typography & Form Elements ── */
        .auth-title {
            font-size: 32px;
            font-weight: 800;
            color: var(--auth-text-dark);
            letter-spacing: -0.02em;
            line-height: 1.15;
            margin-bottom: 6px;
        }
        .auth-subtitle {
            font-size: 14.5px;
            color: var(--auth-text-muted);
            line-height: 1.5;
            margin-bottom: 8px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 7px;
            text-align: left;
        }
        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #3C5578;
        }
        .form-input {
            font-family: inherit;
            font-weight: 500;
            font-size: 15px;
            color: var(--auth-text-dark);
            border: 1.5px solid var(--auth-border);
            border-radius: 12px;
            padding: 13px 16px;
            background: #FFFFFF;
            min-height: 48px;
            box-sizing: border-box;
            transition: all 0.2s ease;
            width: 100%;
        }
        .form-input::placeholder {
            color: #94A3B8;
            font-weight: 400;
        }
        .form-input:focus {
            outline: none;
            border-color: var(--auth-indigo);
            box-shadow: 0 0 0 3px rgba(81, 67, 232, 0.16);
        }

        /* ── Buttons ── */
        .btn-auth-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 14px 24px;
            background: var(--auth-indigo);
            color: #FFFFFF;
            font-family: inherit;
            font-weight: 700;
            font-size: 15px;
            border-radius: 9999px;
            border: none;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            text-decoration: none;
            box-sizing: border-box;
            margin-top: 8px;
        }
        .btn-auth-primary:hover {
            background: var(--auth-indigo-hover);
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(81, 67, 232, 0.3);
        }
        .btn-auth-primary:active {
            transform: translateY(0);
        }

        .btn-auth-secondary {
            display: inline-block;
            text-align: center;
            width: 100%;
            font-size: 14px;
            font-weight: 600;
            color: #3C5578;
            text-decoration: none;
            margin-top: 8px;
            transition: color 0.2s;
            cursor: pointer;
            background: none;
            border: none;
            font-family: inherit;
        }
        .btn-auth-secondary:hover {
            color: var(--auth-text-dark);
        }

        .auth-footer-text {
            font-size: 13.5px;
            color: var(--auth-text-muted);
            text-align: center;
            margin-top: 12px;
        }
        .auth-footer-text a {
            color: var(--auth-text-dark);
            font-weight: 700;
            text-decoration: none;
            transition: color 0.2s;
        }
        .auth-footer-text a:hover {
            color: var(--auth-indigo);
            text-decoration: underline;
        }

        /* ── Step Indicators (Onboarding) ── */
        .step-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            font-weight: 600;
            color: #5A6275;
            margin-bottom: 12px;
        }
        .step-pills {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .step-pill {
            height: 6px;
            border-radius: 3px;
            background: #E2E8F0;
            transition: all 0.3s ease;
        }
        .step-pill.active {
            width: 24px;
            background: var(--auth-indigo);
        }
        .step-pill.inactive {
            width: 8px;
            background: #CBD5E1;
        }

        /* ── Selectable Chips / Pills ── */
        .chips-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 4px;
        }
        .chip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 20px;
            border-radius: 999px;
            font-size: 14px;
            font-weight: 600;
            background: #F1F4F9;
            color: #1E293B;
            border: 1px solid transparent;
            cursor: pointer;
            user-select: none;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .chip:hover {
            background: #E2E8F0;
        }
        .chip.selected {
            background: #111126;
            color: #FFFFFF;
            border-color: #111126;
            box-shadow: 0 4px 14px rgba(17, 17, 38, 0.18);
        }

        /* ── Role Switcher in Registration ── */
        .role-tabs {
            display: flex;
            gap: 6px;
            background: #EEF2F8;
            padding: 5px;
            border-radius: 14px;
            margin-bottom: 6px;
        }
        .role-tab {
            flex: 1;
            border: none;
            font-family: inherit;
            font-weight: 600;
            font-size: 14px;
            padding: 10px 8px;
            border-radius: 10px;
            cursor: pointer;
            background: transparent;
            color: #3C5578;
            transition: all 0.2s ease;
            text-align: center;
        }
        .role-tab.active {
            background: var(--auth-navy);
            color: #FFFFFF;
            box-shadow: 0 4px 12px rgba(20, 50, 92, 0.25);
        }

        /* ── Mobile Layout ── */
        @media (max-width: 900px) {
            .auth-container { flex-direction: column; }
            .auth-side-panel {
                width: 100%;
                min-width: 100%;
                max-width: 100%;
                padding: 32px 24px;
            }
            .auth-side-title { font-size: 28px; }
            .auth-side-subtitle { display: none; }
            .auth-side-content { padding: 18px 0; }
            .auth-main-panel { padding: 36px 20px; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="auth-container">
        <!-- Volet Gauche (Bleu nuit officiel avec logo & signature) -->
        <aside class="auth-side-panel">
            <div>
                <a href="/" class="auth-logo-link">
                    <img src="/assets/9cf747549f194e113309855a69037902.png" alt="MONKAYIER" class="auth-logo-img" onerror="this.src='/assets/logo-monkayier.png'">
                    <span class="auth-logo-text">MONKAYIER</span>
                </a>
            </div>

            <div class="auth-side-content">
                <h1 class="auth-side-title">Apprendre, enseigner et suivre, au même endroit.</h1>
                <p class="auth-side-subtitle">Une plateforme pour les élèves, les enseignants et les parents.</p>
            </div>

            <div class="auth-side-bars" aria-hidden="true">
                <span class="bar-white"></span>
                <span class="bar-blue"></span>
                <span class="bar-yellow"></span>
            </div>
        </aside>

        <!-- Volet Droit (Contenu dynamique du parcours) -->
        <main class="auth-main-panel">
            <div class="auth-form-card">
                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
