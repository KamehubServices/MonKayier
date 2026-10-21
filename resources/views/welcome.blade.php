<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MONKAYIER — Plateforme d'apprentissage intelligente</title>
    <link rel="stylesheet" href="{{ asset('resources/css/tokens.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background-color: #F7F9FC;
            color: #14325C;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        header {
            padding: 24px 48px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #FFFFFF;
            border-bottom: 1px solid #E8EAF2;
        }
        .logo-text {
            font-family: 'Poppins', sans-serif;
            font-size: 24px;
            font-weight: 800;
            color: #14325C;
            letter-spacing: -0.02em;
        }
        .logo-tag {
            font-size: 10px;
            letter-spacing: 0.2em;
            color: #3C5578;
            font-weight: 600;
        }
        .hero {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 64px 24px;
            text-align: center;
        }
        h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 48px;
            font-weight: 800;
            color: #14325C;
            max-width: 800px;
            line-height: 1.15;
            margin-bottom: 20px;
        }
        p.subtitle {
            font-size: 18px;
            color: #4B506A;
            max-width: 640px;
            line-height: 1.6;
            margin-bottom: 36px;
        }
        .btn-cta {
            display: inline-block;
            background-color: #FFB703;
            color: #14325C;
            font-weight: 700;
            padding: 16px 36px;
            border-radius: 999px;
            text-decoration: none;
            font-size: 16px;
            box-shadow: 0 10px 25px -5px rgba(255, 183, 3, 0.4);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px -5px rgba(255, 183, 3, 0.5);
        }
        .badge {
            background-color: #ECEBFF;
            color: #3730A3;
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 24px;
            display: inline-block;
        }
        footer {
            padding: 24px;
            text-align: center;
            font-size: 13px;
            color: #6F7182;
            border-top: 1px solid #E8EAF2;
            background: #FFFFFF;
        }
    </style>
</head>
<body>
    <header>
        <div>
            <div class="logo-text">MONKAYIER</div>
            <div class="logo-tag">PLATEFORME D'APPRENTISSAGE · TOGO</div>
        </div>
        <div>
            <a href="/api/v1/health" class="btn-cta" style="padding: 10px 22px; font-size: 14px;">API v1 Status</a>
        </div>
    </header>

    <main class="hero">
        <span class="badge">PROJET OFFICIEL · KAMEHUB SERVICES SARL-U</span>
        <h1>Apprendre, réviser et réussir avec un tuteur IA souverain</h1>
        <p class="subtitle">Conçu pour les élèves de la 6e à la Terminale au Togo. Cours officiels validés, évaluations, calendrier scolaire et assistant pédagogique RAG respectueux de votre vie privée.</p>
        <a href="#demarrer" class="btn-cta">Commencer gratuitement</a>
    </main>

    <footer>
        &copy; 2026 MONKAYIER · KAMEHUB SERVICES SARL-U · Tous droits réservés.
    </footer>
</body>
</html>
