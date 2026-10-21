# MONKAYIER — Journal des Décisions Architecturales (ADR)

> **Projet** : MONKAYIER  
> **Porteur** : KAMEHUB SERVICES SARL-U  
> **Statut** : Document vivant de référence

---

### ADR-001 : Choix du SGBD PostgreSQL et des Identifiants UUIDv4
* **Date** : Octobre 2026
* **Statut** : Validé (Phase 1)
* **Contexte** : MONKAYIER manipule des données scolaires sensibles, des relations riches, des arborescences de compétences et des fragments RAG. Les entités seront exposées via une API REST et une PWA hors-ligne.
* **Décision** :
  1. Utilisation de PostgreSQL en version supportée (15+ / 16+).
  2. Utilisation systématique de clés primaires `UUIDv4` (`gen_random_uuid()`) pour toutes les entités exposées aux clients API afin d'empêcher l'énumération horizontale d'identifiants et de permettre la génération d'identifiants côté client en mode déconnecté.
  3. Rejet de tout partitionnement prématuré au lancement : le schéma est optimisé pour 1 000 à 10 000 utilisateurs inscrits avec des index ciblés et mesurés.
* **Conséquences** : Protection native contre l'énumération, synchronisation offline facilitée, intégrité référentielle garantie par clés étrangères strictes.

---

### ADR-002 : Monolithe Modulaire Laravel et Rendu Hybride sans SPA Lourd
* **Date** : Octobre 2026
* **Statut** : Validé (Phase 0 / Phase 2)
* **Contexte** : Le public cible utilise des smartphones d'entrée de gamme avec des débits 3G/4G instables au Togo. Les frameworks SPA lourds (React/Vue volumineux) génèrent des bundles JS de plusieurs mégaoctets ralentissant le premier rendu.
* **Décision** :
  1. Architecture backend en monolithe modulaire PHP 8.x / Laravel.
  2. Rendu principal avec Laravel Blade, HTML sémantique, CSS moderne vanille (design tokens centralisés) et micro-interactions JavaScript légères.
  3. API REST versionnée obligatoirement sous `/api/v1` consommée par les composants dynamiques et la PWA.
* **Conséquences** : Temps de chargement initial minimal, faible consommation de data, compatibilité maximale avec les navigateurs mobiles modestes.

---

### ADR-003 : Fidélité Visuelle à la Maquette Officielle (Rejet de la Palette Verte)
* **Date** : Octobre 2026
* **Statut** : Validé (Charte Graphique Officielle V1.0)
* **Contexte** : Une proposition verte historique ne correspondait pas aux maquettes officielles fournies dans `MonKayier Maquette.pdf`.
* **Décision** :
  1. Adopter strictement la charte officielle à deux contextes :
     * **Site vitrine / Marque** : Bleu nuit (`#14325C`), Bleu ciel (`#3FA9F5`), Jaune (`#FFB703` / `#F6B900`).
     * **Application connectée** : Encre sombre (`#0E1022`), Indigo (`#4F46E5`), Citron (`#D4F25C`), Lavande (`#ECEBFF`) et Fond doux (`#F5F6FA`).
  2. Centraliser tous les paramètres visuels dans `resources/css/tokens.css`.
  3. Ne jamais publier d'écran sans comparaison visuelle préalable avec le PDF officiel.
* **Conséquences** : Alignement rigoureux avec la direction artistique validée par le porteur de projet.

---

### ADR-004 : Architecture RAG Strict & Intégrité Pédagogique
* **Date** : Octobre 2026
* **Statut** : Validé (Phase 1 / Phase 7)
* **Contexte** : Les modèles de langage généralistes ont tendance à halluciner et ignorent les spécificités du programme scolaire togolais.
* **Décision** :
  1. Le moteur IA ne répond pas à l'aveugle : il applique un pipeline RAG basé sur les cours et manuels togolais validés éditorialement (`status = 'published'`).
  2. Chaque réponse issue d'un cours affiche obligatoirement un badge avec le titre de la ressource et le numéro de page.
  3. Tout complément hors programme scolaire est affiché sur fond saumon doux (`#FFF0EA`) avec la mention *"Explication complémentaire hors de ton cours"*.
  4. L'IA ne doit en aucun cas inventer une date officielle d'examen ou une décision ministérielle.
* **Conséquences** : Fiabilité éducative maximale, confiance des parents et des enseignants, traçabilité des sources.

---

### ADR-005 : Confidentialité des Conversations d'Élèves & Protection des Mineurs
* **Date** : Octobre 2026
* **Statut** : Validé (Phase 1 / Phase 9)
* **Contexte** : Les élèves doivent se sentir en confiance pour poser des questions naïves ou répétitives à l'assistant IA sans craindre le jugement de leurs parents ou enseignants.
* **Décision** :
  1. Les conversations avec l'assistant IA (`ai_conversations`, `ai_messages`) sont **strictement privées**.
  2. L'espace parent donne accès aux rapports de travail (temps passé, matières travaillées, notions maîtrisées, devoirs à rendre), mais ne permet en aucun cas la lecture des chats individuels.
  3. Les dossiers de solidarité et pièces d'état civil sont confinés dans des tables et buckets de stockage privés, inaccessibles aux rôles non autorisés.
* **Conséquences** : Respect strict du bien-être de l'élève, conformité aux principes de protection des mineurs et aux exigences APDP/RGPD.

---

### ADR-006 : Synchronisation PWA & Traitement Idempotent
* **Date** : Octobre 2026
* **Statut** : Validé (Phase 1 / Phase 6)
* **Contexte** : Des coupures réseau fréquentes au Togo peuvent provoquer des déconnexions pendant un quiz ou une séance de révision.
* **Décision** :
  1. Utilisation de la table `assessment_attempts` avec contrainte unique `UNIQUE(student_user_id, idempotency_key)`.
  2. Table `sync_operations` stockant les mutations en attente avec `client_operation_uuid`.
  3. L'application enregistre localement dans IndexedDB et rejoue la synchronisation dès le retour du réseau sans risque de double soumission ou d'écrasement silencieux.
* **Conséquences** : Zéro perte de travail pour l'élève lors d'une instabilité réseau.
