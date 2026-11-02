<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MonKayier – Accueil</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { margin: 0; background: #F7F9FC; }
        a { color: inherit; text-decoration: none; }
        .p {
            background: #F7F9FC;
            color: #14325C;
            font-family: 'Poppins', system-ui, -apple-system, sans-serif;
            font-size: 15px;
            line-height: 1.55;
            -webkit-font-smoothing: antialiased;
        }
        .p h1, .p h2, .p h3, .p p { margin: 0; }
        .p h1, .p h2, .p h3 { font-weight: 700; letter-spacing: -.02em; line-height: 1.15; }
        .wrap { max-width: 1240px; margin: 0 auto; padding: 0 32px; box-sizing: border-box; }
        
        /* En-tête Navigation */
        .hd {
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(247, 249, 252, 0.94);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid #E3E9F2;
        }
        .hd .wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            height: 76px;
            flex-wrap: wrap;
        }
        .nv { display: flex; gap: 28px; font-weight: 500; color: #3C5578; }
        .nv a { transition: color 0.2s; }
        .nv a:hover { color: #14325C; }

        /* Boutons */
        .bt {
            display: inline-block;
            text-align: center;
            padding: 13px 24px;
            border-radius: 12px;
            background: #14325C;
            color: #fff;
            font-weight: 600;
            font-size: 15px;
            box-sizing: border-box;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: inherit;
            border: none;
        }
        .bt:hover { background: #1D447D; }
        .bt.y { background: #FFB703; color: #14325C; }
        .bt.y:hover { background: #FFC533; }
        .bt.o { background: #fff; color: #14325C; border: 1.5px solid #CBD6E6; }
        .bt.o:hover { border-color: #14325C; }

        .eb { font-size: 12.5px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: #2E5FA3; }
        
        .card {
            background: #fff;
            border: 1px solid #E3E9F2;
            border-radius: 20px;
            padding: 28px;
            box-sizing: border-box;
            box-shadow: 0 18px 40px -30px rgba(20, 50, 92, .35);
        }
        .ic {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: #DCEFFE;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .ic.y { background: #FFF0C7; }
        .ic.n { background: #14325C; }

        .g3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
        .fl { display: flex; flex-direction: column; gap: 6px; font-weight: 600; font-size: 13px; color: #3C5578; }
        .fl input {
            font: inherit;
            font-weight: 500;
            font-size: 15px;
            color: #14325C;
            border: 1.5px solid #CBD6E6;
            border-radius: 12px;
            padding: 12px 14px;
            background: #fff;
            min-height: 44px;
            box-sizing: border-box;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .fl input:focus {
            outline: none;
            border-color: #3FA9F5;
            box-shadow: 0 0 0 3px rgba(63, 169, 245, 0.2);
        }

        .tab {
            flex: 1;
            border: 0;
            font: inherit;
            font-weight: 600;
            font-size: 14px;
            padding: 11px 8px;
            border-radius: 10px;
            cursor: pointer;
            min-height: 44px;
            transition: all 0.2s ease;
            background: transparent;
            color: #3C5578;
        }
        .tab.active {
            background: #14325C;
            color: #fff;
        }

        .mu { color: #3C5578; }

        /* Responsive */
        @media (max-width: 1000px) {
            .g3, .hero { grid-template-columns: 1fr !important; }
            .nv { display: none; }
            .wrap { padding: 0 20px; }
            .hero h1 { font-size: 40px !important; }
        }
    </style>
</head>
<body>
<div class="p">
    <!-- Barre de Navigation Sticky -->
    <header class="hd">
        <div class="wrap">
            <a href="#top" style="display:flex;align-items:center;gap:12px">
                <img src="/assets/9cf747549f194e113309855a69037902.png" alt="Logo MONKAYIER" style="height:44px;width:auto">
                <b style="font-size:24px;font-weight:800;letter-spacing:-.01em">MONKAYIER</b>
            </a>
            <nav class="nv">
                <a href="#roles">Pour qui</a>
                <a href="#fonctions">Fonctionnalités</a>
                <a href="#etapes">Comment ça marche</a>
            </nav>
            <div style="display:flex;gap:10px">
                <a class="bt o" href="/login">Se connecter</a>
                <a class="bt" href="#inscription">Créer un compte</a>
            </div>
        </div>
    </header>

    <!-- Section Héro -->
    <main id="top" class="wrap hero" style="display:grid;grid-template-columns:1.1fr 1fr;gap:56px;align-items:center;padding-top:72px;padding-bottom:88px">
        <div style="display:flex;flex-direction:column;gap:24px">
            <span class="eb">Plateforme d’apprentissage</span>
            <h1 style="font-size:58px">Réviser, préparer vos examens en toute sérénité.</h1>
            <p class="mu" style="font-size:18px;max-width:520px">MonKayier réunit élèves, enseignants et établissements autour de cours, d’exercices et d’un assistant IA axée sur le programme éducatif officiels et qui s’adapte à chacun.</p>
            <div style="display:flex;gap:12px;flex-wrap:wrap">
                <a class="bt y" href="#inscription" style="padding:15px 28px">Commencer gratuitement</a>
                <a class="bt o" href="#fonctions" style="padding:15px 28px">Découvrir la plateforme</a>
            </div>
            <div style="display:flex;gap:6px;height:6px;max-width:420px;margin-top:8px">
                <i style="flex:5;background:#14325C;border-radius:3px"></i>
                <i style="flex:3;background:#3FA9F5;border-radius:3px"></i>
                <i style="flex:2;background:#FFB703;border-radius:3px"></i>
            </div>
        </div>

        <!-- Carte Inscription interactive (Split-hero) -->
        <div id="inscription" class="card" style="padding:32px;display:flex;flex-direction:column;gap:18px">
            <div>
                <h2 style="font-size:24px">Créer mon compte</h2>
                <p class="mu" style="font-size:14px;margin-top:4px">Choisis ton profil pour commencer.</p>
            </div>
            
            <div style="display:flex;gap:6px;background:#EEF2F8;padding:5px;border-radius:14px">
                <button type="button" class="tab active" id="tab-eleve" onclick="selectRole('eleve')">Élève</button>
                <button type="button" class="tab" id="tab-prof" onclick="selectRole('prof')">Enseignant</button>
                <button type="button" class="tab" id="tab-etab" onclick="selectRole('etab')">Établissement</button>
            </div>

            <p class="mu" id="role-text" style="font-size:14px;min-height:44px">Accède à tes cours, tes exercices et ton assistant IA.</p>

            <form onsubmit="handleQuickRegister(event)" style="display:flex;flex-direction:column;gap:16px">
                <label class="fl">
                    <span id="name-label">Nom complet</span>
                    <input type="text" id="name-input" placeholder="Ton prénom et ton nom" required>
                </label>
                <label class="fl">
                    <span>Adresse e-mail</span>
                    <input type="email" id="email-input" placeholder="nom@exemple.com" required>
                </label>
                <label class="fl">
                    <span>Mot de passe</span>
                    <input type="password" id="pass-input" placeholder="8 caractères minimum" minlength="8" required>
                </label>
                <button type="submit" class="bt" id="cta-btn" style="padding:15px 24px;width:100%">
                    Créer mon compte élève
                </button>
            </form>

            <p class="mu" style="font-size:13px;text-align:center">
                Déjà inscrit ? <a href="/login" style="font-weight:700;color:#14325C;text-decoration:underline">Se connecter</a>
            </p>
        </div>
    </main>

    <!-- Section 1 : Pour qui -->
    <section id="roles" style="background:#fff;border-top:1px solid #E3E9F2;border-bottom:1px solid #E3E9F2">
        <div class="wrap" style="padding-top:88px;padding-bottom:88px;display:flex;flex-direction:column;gap:40px">
            <div style="max-width:640px;display:flex;flex-direction:column;gap:12px">
                <span class="eb">Pour qui</span>
                <h2 style="font-size:40px">Une plateforme, trois façons de l’utiliser.</h2>
            </div>
            <div class="g3">
                <div class="card" style="display:flex;flex-direction:column;gap:14px;background:#F7F9FC;box-shadow:none">
                    <div class="ic">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#14325C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 9l10-5 10 5-10 5L2 9Z"></path><path d="M6 11.5V16c0 1.5 2.7 3 6 3s6-1.5 6-3v-4.5"></path></svg>
                    </div>
                    <h3 style="font-size:22px">Élève</h3>
                    <p class="mu">Range tes cours, discute avec eux, fais des exercices corrigés et prépare tes examens avec un parcours sur mesure.</p>
                    <a href="#inscription" onclick="selectRole('eleve'); document.getElementById('inscription').scrollIntoView({behavior:'smooth'}); return false;" style="font-weight:700;margin-top:auto;color:#14325C">Je suis élève</a>
                </div>

                <div class="card" style="display:flex;flex-direction:column;gap:14px;background:#F7F9FC;box-shadow:none">
                    <div class="ic y">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#14325C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="12" rx="2"></rect><path d="M8 20h8M12 16v4"></path></svg>
                    </div>
                    <h3 style="font-size:22px">Enseignant</h3>
                    <p class="mu">Génère des sujets et des corrigés, partage tes supports avec tes classes et repère qui a besoin d’aide.</p>
                    <a href="#inscription" onclick="selectRole('prof'); document.getElementById('inscription').scrollIntoView({behavior:'smooth'}); return false;" style="font-weight:700;margin-top:auto;color:#14325C">Je suis enseignant</a>
                </div>

                <div class="card" style="display:flex;flex-direction:column;gap:14px;background:#F7F9FC;box-shadow:none">
                    <div class="ic n">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 21h18M5 21V9l7-5 7 5v12"></path><path d="M9 21v-6h6v6"></path></svg>
                    </div>
                    <h3 style="font-size:22px">Établissement</h3>
                    <p class="mu">Réunis tes classes et tes équipes, suis la progression globale et garde le lien avec les parents.</p>
                    <a href="#inscription" onclick="selectRole('etab'); document.getElementById('inscription').scrollIntoView({behavior:'smooth'}); return false;" style="font-weight:700;margin-top:auto;color:#14325C">Je représente un établissement</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 2 : Fonctionnalités -->
    <section id="fonctions" class="wrap" style="padding-top:88px;padding-bottom:88px;display:flex;flex-direction:column;gap:40px">
        <div style="max-width:640px;display:flex;flex-direction:column;gap:12px">
            <span class="eb">Fonctionnalités</span>
            <h2 style="font-size:40px">Tout ce qu’il faut pour progresser.</h2>
        </div>
        <div class="g3">
            <div class="card" style="display:flex;flex-direction:column;gap:12px">
                <div class="ic">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#14325C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 5a2 2 0 0 1 2-2h13v16H6a2 2 0 0 0-2 2V5Z"></path></svg>
                </div>
                <h3 style="font-size:19px">Bibliothèque de cours</h3>
                <p class="mu">Importe tes PDF et les photos de ton cahier, tout reste classé par matière.</p>
            </div>

            <div class="card" style="display:flex;flex-direction:column;gap:12px">
                <div class="ic y">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#14325C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12a8 8 0 0 1-11.6 7.1L4 20l1-4.4A8 8 0 1 1 21 12Z"></path></svg>
                </div>
                <h3 style="font-size:19px">Discuter avec son cours</h3>
                <p class="mu">Pose tes questions à ton cours et obtiens des réponses claires.</p>
            </div>

            <div class="card" style="display:flex;flex-direction:column;gap:12px">
                <div class="ic">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#14325C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 11l3 3 8-8"></path><path d="M20 12v7a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h9"></path></svg>
                </div>
                <h3 style="font-size:19px">Exercices corrigés</h3>
                <p class="mu">Des exercices adaptés à ton niveau, avec une correction détaillée.</p>
            </div>

            <div class="card" style="display:flex;flex-direction:column;gap:12px">
                <div class="ic y">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#14325C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"></circle><circle cx="12" cy="12" r="4"></circle></svg>
                </div>
                <h3 style="font-size:19px">Test de niveau</h3>
                <p class="mu">Identifie tes points forts et ce qu’il reste à renforcer.</p>
            </div>

            <div class="card" style="display:flex;flex-direction:column;gap:12px">
                <div class="ic">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#14325C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"></rect><path d="M3 10h18M8 3v4M16 3v4"></path></svg>
                </div>
                <h3 style="font-size:19px">Calendrier des examens</h3>
                <p class="mu">Les dates officielles s’affichent dès leur publication, avec leur source.</p>
            </div>

            <div class="card" style="display:flex;flex-direction:column;gap:12px">
                <div class="ic y">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#14325C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="9" y="3" width="6" height="12" rx="3"></rect><path d="M5 11a7 7 0 0 0 14 0M12 18v3"></path></svg>
                </div>
                <h3 style="font-size:19px">Assistant vocal</h3>
                <p class="mu">Révise à l’oral en parlant à ton assistant, où que tu sois.</p>
            </div>
        </div>
    </section>

    <!-- Section 3 : Comment ça marche -->
    <section id="etapes" style="background:#fff;border-top:1px solid #E3E9F2">
        <div class="wrap" style="padding-top:88px;padding-bottom:88px;display:flex;flex-direction:column;gap:40px">
            <div style="max-width:640px;display:flex;flex-direction:column;gap:12px">
                <span class="eb">Comment ça marche</span>
                <h2 style="font-size:40px">Prêt en trois étapes.</h2>
            </div>
            <div class="g3">
                <div style="display:flex;flex-direction:column;gap:12px">
                    <b style="font-size:44px;font-weight:800;color:#3FA9F5;line-height:1">1</b>
                    <h3 style="font-size:20px">Crée ton compte</h3>
                    <p class="mu">Choisis ton profil : élève, enseignant ou établissement.</p>
                </div>
                <div style="display:flex;flex-direction:column;gap:12px">
                    <b style="font-size:44px;font-weight:800;color:#FFB703;line-height:1">2</b>
                    <h3 style="font-size:20px">Ajoute tes cours</h3>
                    <p class="mu">Importe tes documents ou passe un test de niveau pour démarrer.</p>
                </div>
                <div style="display:flex;flex-direction:column;gap:12px">
                    <b style="font-size:44px;font-weight:800;color:#14325C;line-height:1">3</b>
                    <h3 style="font-size:20px">Progresse chaque jour</h3>
                    <p class="mu">Suis ton parcours, révise et vois ta progression évoluer.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Bannière d'Appel à l'action -->
    <section style="background:#14325C">
        <div class="wrap" style="padding-top:72px;padding-bottom:72px;display:flex;align-items:center;justify-content:space-between;gap:32px;flex-wrap:wrap">
            <div style="max-width:620px;display:flex;flex-direction:column;gap:12px">
                <h2 style="font-size:36px;color:#fff">Rejoins MonKayier dès aujourd’hui.</h2>
                <p style="color:#C9D8EE;font-size:16px">Un compte suffit pour accéder à toute la plateforme.</p>
            </div>
            <a class="bt y" href="#inscription" style="padding:16px 32px">Créer un compte</a>
        </div>
    </section>

    <!-- Pied de Page -->
    <footer class="wrap" style="padding-top:36px;padding-bottom:36px;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap">
        <div style="display:flex;align-items:center;gap:10px">
            <img src="/assets/9cf747549f194e113309855a69037902.png" alt="Logo MONKAYIER" style="height:32px;width:auto">
            <b style="font-weight:800">MONKAYIER</b>
            <span class="mu" style="font-size:13px">Plateforme d’apprentissage</span>
        </div>
        <div class="mu" style="display:flex;gap:24px;font-size:13px">
            <a href="#top">Mentions légales</a>
            <a href="#top">Confidentialité</a>
            <a href="mailto:contact@monkayier.tg">Contact</a>
        </div>
    </footer>
</div>

<script>
    const roleDefinitions = {
        eleve: {
            text: 'Accède à tes cours, tes exercices et ton assistant IA.',
            label: 'Nom complet',
            placeholder: 'Ton prénom et ton nom',
            cta: 'Créer mon compte élève',
            redirect: '/onboarding/student'
        },
        prof: {
            text: 'Prépare tes sujets et suis la progression de tes classes.',
            label: 'Nom complet',
            placeholder: 'Votre prénom et votre nom',
            cta: 'Créer mon compte enseignant',
            redirect: '/onboarding/teacher'
        },
        etab: {
            text: 'Gère tes classes, tes enseignants et le lien avec les familles.',
            label: 'Nom de l’établissement',
            placeholder: 'Nom de votre établissement',
            cta: 'Créer le compte établissement',
            redirect: '/auth/success?type=register'
        }
    };

    let currentRole = 'eleve';

    function selectRole(roleId) {
        currentRole = roleId;
        const def = roleDefinitions[roleId];
        if (!def) return;

        document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
        const activeBtn = document.getElementById('tab-' + roleId);
        if (activeBtn) activeBtn.classList.add('active');

        document.getElementById('role-text').textContent = def.text;
        document.getElementById('name-label').textContent = def.label;
        document.getElementById('name-input').placeholder = def.placeholder;
        document.getElementById('cta-btn').textContent = def.cta;
    }

    function handleQuickRegister(e) {
        e.preventDefault();
        const def = roleDefinitions[currentRole];
        const btn = document.getElementById('cta-btn');
        btn.textContent = "Création du compte...";
        btn.style.opacity = "0.75";
        btn.disabled = true;

        setTimeout(() => {
            window.location.href = def.redirect;
        }, 350);
    }
</script>
</body>
</html>
