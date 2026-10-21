# MONKAYIER — Backlog Priorisé de Réalisation V1

> **Projet** : MONKAYIER  
> **Porteur** : KAMEHUB SERVICES SARL-U  
> **Méthodologie** : Livraison par petits lots testables, vérifiables et documentés

---

## Vue d'Ensemble des Phases & Jalons

```
[Phase 0 : Init & Cadrage] ──────> FAIT
[Phase 1 : Schéma PostgreSQL V1] ─> FAIT (DDL, Dictionnaire, Seeders, ERD)
      │
      ▼
[Phase 2 : Socle Backend Laravel & Auth V1] ───> PROCHAINE ÉTAPE
      │
      ▼
[Phase 3 : Référentiels Togo & Gestion Éditoriale]
      │
      ▼
[Phase 4 : API Laravel & Administration]
      │
      ▼
[Phase 5 : Interface Élève (Web / Mobile PWA)]
      │
      ▼
[Phase 6 : Évaluations, Quiz & Révisions Adaptatives]
      │
      ▼
[Phase 7 : Moteur IA Pédagogique & RAG Togo]
      │
      ▼
[Phase 8 : Espaces Enseignants]
      │
      ▼
[Phase 9 : Espaces Parents & Tuteurs]
      │
      ▼
[Phases 10 & 11 : Sécurité, Performance, Pilote Togo]
```

---

## Détail des Tâches par Phase

### Phase 0 : Initialisation & Cadrage (TERMINÉ)
- [x] Initialisation du dépôt Git propre avec `.gitignore` de sécurité.
- [x] Configuration du gabarit d'environnement `.env.example` sans secrets réels.
- [x] Mise en place des Design Tokens officiels `resources/css/tokens.css` (Palette Marque & Application).
- [x] Rédaction du `README.md` d'orientation et d'exploitation.

### Phase 1 : Schéma PostgreSQL V1 & Dictionnaire (TERMINÉ)
- [x] Rédaction du schéma SQL DDL exécutable `database/schema_v1_postgresql.sql` (Lots DB-00 à DB-12, 55 tables).
- [x] Élaboration du dictionnaire de données complet `docs/data_dictionary_v1.md`.
- [x] Formalisation du journal des décisions d'architecture (ADR) `docs/decisions.md`.
- [x] Création du diagramme ERD Mermaid `docs/erd_v1.md`.
- [x] Création du script de seeders officiels Togo `database/seeders_togo_v1.sql` (Niveaux, Séries A4/CD/F, Matières, Examens BEPC/BAC 1/BAC 2).
- [x] Validation automatisée de l'intégrité topologique et des clés étrangères.

### Phase 2 : Socle Backend Laravel & Authentification V1 (À VENIR)
- [ ] 2.1 — Configuration de l'environnement Laravel (version stable supportée) et connexion PostgreSQL.
- [ ] 2.2 — Création des migrations Laravel ordonnées correspondant au schéma Phase 1.
- [ ] 2.3 — Modèle utilisateur, hachage sécurisé, statuts de compte (`pending`, `active`, `suspended`).
- [ ] 2.4 — Authentification par session et tokens (Sanctum), limitation de débit anti-bruteforce.
- [ ] 2.5 — Rôles et autorisations (Policies / Gates) : attribution par défaut stricte au rôle `student`.
- [ ] 2.6 — Endpoints `/api/v1/auth/*` (`register`, `login`, `logout`, `forgot-password`, `reset-password`).
- [ ] 2.7 — Endpoint `/api/v1/me` (lecture et mise à jour sécurisée du profil).
- [ ] 2.8 — Standardisation des réponses API `{ "data": ..., "meta": ... }` et des erreurs HTTP.
- [ ] 2.9 — Journalisation d'audit des actions sensibles dans `audit_logs`.
- [ ] 2.10 — Tests automatisés d'authentification, de contrôle d'accès horizontal et de non-régression.

### Phase 3 : Référentiels Scolaires & Gestion Éditoriale
- [ ] Ingestion structurée du programme togolais (6e à Terminale).
- [ ] Découpage en thèmes, chapitres et compétences évaluables.
- [ ] Workflow éditorial pour les fiches de cours (`draft` -> `review` -> `approved` -> `published`).
- [ ] Pipeline d'extraction textuelle des manuels et attribution des sources vérifiées.

### Phase 4 : API Laravel & Back-office d'Administration
- [ ] CRUD API sécurisé pour les référentiels, classes et cours.
- [ ] Interface d'administration et de relecture pour les inspecteurs/éditeurs.
- [ ] Gestion des rôles, suspensions de comptes et modération.

### Phase 5 : Interface Élève (Web & Mobile PWA)
- [ ] Tableau de bord conforme à la maquette (Séance du jour, Anneau de progression, Mes matières).
- [ ] Bibliothèque des cours avec fiches téléchargeables hors-ligne.
- [ ] Interface de dialogue avec l'assistant IA (badges de source, surlignage de citations, panneau d'explication).
- [ ] Navigation mobile fidèle (78px, 5 onglets) et navigation desktop (sidebar 236px).

### Phase 6 : Évaluations, Quiz & Révisions
- [ ] Moteur de QCM et de questions à réponses courtes.
- [ ] Génération de quiz de diagnostic initial (test de niveau).
- [ ] Calcul des scores et mise à jour de la table `student_competencies`.
- [ ] Générateur algorithmique de plans de révision personnalisés.

### Phase 7 : Moteur IA Pédagogique & RAG Togo
- [ ] Indexation sémantique des chunks de cours officiels dans PostgreSQL.
- [ ] Moteur de prompting pédagogique (guidage socratique sans donner la solution brute).
- [ ] Détection du hors-programme et coloration saumonée explicite.
- [ ] Évaluation des métriques de précision et de latence sur matériel local.

### Phases 8 & 9 : Espaces Enseignants & Parents
- [ ] Espace profs : création de devoirs, pré-correction assistée par IA avec arbitrage obligatoire du professeur.
- [ ] Espace parents : liaison sécurisée par code éphémère, consultation des progrès sans accès aux chats privés.

### Phases 10 & 11 : Sécurité, Performance & Déploiement Pilote
- [ ] Tests de charge pour 1 000 à 10 000 inscrits.
- [ ] Tests de restauration de sauvegarde PostgreSQL.
- [ ] Déploiement pilote restreint au Togo avec collecte des retours d'expérience.
