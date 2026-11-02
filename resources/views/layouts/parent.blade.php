<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Espace Parent — MonKayier')</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="shortcut icon" href="/favicon.ico">
    
    <!-- Google Fonts Inter & Sora -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">
    
    <!-- Stylesheet officiel MonKayier -->
    <link rel="stylesheet" href="/assets/b4f78924008600b81fa2b26aac0f06db.css">
    
    <style>
        body {
            margin: 0;
            background: #F5F6FA;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: #0E1022;
            -webkit-font-smoothing: antialiased;
        }
        .lay {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            align-items: flex-start;
            padding: 0 24px;
            box-sizing: border-box;
        }
        .sb {
            position: sticky;
            top: 24px;
            width: 236px;
            flex: none;
            margin-top: 24px;
            background: #fff;
            border: 1px solid #E8EAF2;
            border-radius: 28px;
            padding: 14px;
            box-shadow: 0 1px 2px rgba(14, 16, 34, .04), 0 18px 40px -28px rgba(14, 16, 34, .18);
            display: flex;
            flex-direction: column;
            gap: 2px;
            box-sizing: border-box;
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
            border-radius: 14px;
            font-weight: 600;
            font-size: 13.5px;
            color: #3A3E57;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .sb a:hover {
            background: #F3F4FA;
        }
        .sb a.on {
            background: #0E1022;
            color: #fff;
        }
        .sb a.on svg {
            stroke: #D4F25C;
        }
        .sb svg {
            flex: none;
            width: 20px;
            height: 20px;
            fill: none;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }
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
            padding: 24px 0 56px 20px;
        }
        @media (max-width: 1000px) {
            .sb { display: none; }
            .lay { padding: 0; }
            .lay .w { padding-left: 16px; padding-right: 16px; }
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="p">
    <div class="lay">
        <!-- Barre latérale Parent -->
        <aside class="sb">
            <div style="display:flex;align-items:center;gap:10px;padding:6px 12px 14px">
                <img src="/assets/9cf747549f194e113309855a69037902.png" alt="Logo MonKayier" style="height:36px;width:auto">
                <b class="hd" style="font-size:19px">MonKayier</b>
            </div>

            <!-- Rapport de la semaine -->
            <a href="/parent/dashboard" class="{{ (request()->is('parent/dashboard') || request()->is('parent/rapport*')) ? 'on' : '' }}">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 20V10M12 20V4M19 20v-7"></path></svg>
                Rapport de la semaine
            </a>

            <!-- Catégorie Suivi -->
            <div class="sl">Suivi</div>
            <a href="/parent/enfants" class="{{ request()->is('parent/enfants*') ? 'on' : '' }}">
                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="9" cy="8" r="3.5"></circle><path d="M2 20a7 7 0 0 1 14 0M17 5a3.5 3.5 0 0 1 0 7M22 20a7 7 0 0 0-4-6"></path></svg>
                Mes enfants
            </a>
            <a href="/parent/echeances" class="{{ request()->is('parent/echeances*') ? 'on' : '' }}">
                <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"></rect><path d="M3 10h18M8 3v4M16 3v4"></path></svg>
                Échéances
            </a>
            <a href="/parent/notifications" class="{{ request()->is('parent/notifications*') ? 'on' : '' }}">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 8a6 6 0 1 1 12 0c0 7 3 8 3 8H3s3-1 3-8"></path><path d="M10 20a2 2 0 0 0 4 0"></path></svg>
                Notifications
            </a>

            <!-- Catégorie Aide & Compte -->
            <div class="sl">Aide & Compte</div>
            <a href="/parent/avis" class="{{ request()->is('parent/avis*') ? 'on' : '' }}">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s-8-5.3-8-11a4.5 4.5 0 0 1 8-2.8A4.5 4.5 0 0 1 20 10c0 5.7-8 11-8 11Z"></path></svg>
                Donner mon avis
            </a>
            <a href="/parent/aide" class="{{ (request()->is('parent/aide*') || request()->is('parent/help*')) ? 'on' : '' }}">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 12a8 8 0 0 1-11.6 7.1L4 20l1-4.4A8 8 0 1 1 21 12Z"></path></svg>
                Aide et contact
            </a>
            <a href="/parent/profile" class="{{ (request()->is('parent/profile*') || request()->is('parent/settings*')) ? 'on' : '' }}">
                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                Paramètres
            </a>

            <div class="hp">
                Une question sur le suivi de ton enfant ? Écris-nous.
            </div>

            <!-- Profil Parent -->
            <a href="/parent/profile" style="display:flex;align-items:center;gap:10px;margin-top:12px;padding:12px;border-radius:18px;background:#F3F4FA;text-decoration:none;color:inherit;transition:background 0.2s ease;">
                <div style="width:40px;height:40px;border-radius:50%;background:#D4F25C;font-weight:800;display:flex;align-items:center;justify-content:center;flex:none">PM</div>
                <div style="line-height:1.3">
                    <b>M. Mensah</b>
                    <div class="mu">Parent de Kodjo · Mon profil</div>
                </div>
            </a>
        </aside>

        <!-- Contenu dynamique -->
        <main class="w">
            <!-- Barre d'accès rapide supérieure : Notifications, Aide, Paramètres -->
            <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;padding:4px 0 12px 0;flex-wrap:wrap">
                <div style="display:flex;align-items:center;gap:8px">
                    <span class="tg" style="font-size:11.5px;padding:5px 12px;background:#ECEBFF;color:#4F46E5;font-weight:700">Portail Parent</span>
                    <span class="mu" style="font-size:13px;font-weight:600">Suivi · Kodjo Mensah (Terminale D)</span>
                </div>
                <div style="display:flex;align-items:center;gap:10px">
                    <a href="/parent/notifications" class="{{ request()->is('parent/notifications*') ? 'ch on' : 'ch' }}" style="display:inline-flex;align-items:center;gap:7px;text-decoration:none" title="Mes notifications">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 8a6 6 0 1 1 12 0c0 7 3 8 3 8H3s3-1 3-8"></path><path d="M10 20a2 2 0 0 0 4 0"></path></svg>
                        <span>Notifications</span>
                        <span style="display:inline-block;width:7px;height:7px;border-radius:50%;background:#EF4444"></span>
                    </a>
                    <a href="/parent/aide" class="{{ (request()->is('parent/aide*') || request()->is('parent/help*')) ? 'ch on' : 'ch' }}" style="display:inline-flex;align-items:center;gap:7px;text-decoration:none" title="Aide et assistance">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                        <span>Aide</span>
                    </a>
                    <a href="/parent/profile" class="{{ (request()->is('parent/profile*') || request()->is('parent/settings*')) ? 'ch on' : 'ch' }}" style="display:inline-flex;align-items:center;gap:7px;text-decoration:none" title="Paramètres et profil">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                        <span>Paramètres</span>
                    </a>
                </div>
            </div>
            @yield('content')
        </main>
    </div>
</div>
@stack('scripts')
</body>
</html>
