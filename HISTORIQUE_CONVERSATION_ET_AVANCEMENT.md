# Journal et Sauvegarde des Travaux — MonKayier
*Date de sauvegarde : 24 Octobre 2026*  
*Identifiant de conversation : `1db65915-65b2-4c6a-95f8-211207fe7388`*

---

## 1. Résumé Exécutif
Cette conversation a permis la finalisation complète des parcours d'authentification, la reproduction fidèle de l'ensemble des maquettes visuelles (Élève, Enseignant, Parent, Profil/Paramètres), ainsi que la dynamisation du module élève avec la base de données SQLite.

---

## 2. Travaux Réalisés

### A. Parcours d'authentification et sécurité
- **Correction du flux de connexion** :
  - La route `/login` redirige désormais obligatoirement vers la vérification OTP `/verify-email?type=login` à 6 chiffres.
  - La validation du code OTP redirige vers l'écran de succès `/auth/success?type=login` avant d'accéder au tableau de bord.
- **Ajout de l'accès aux paramètres / aide / notifications** dans tous les layouts (`layouts.student`, `layouts.teacher`, `layouts.parent`).

### B. Intégration complète des 34 interfaces fidèles au design
1. **Module Élève (11 interfaces)** :
   - Tableau de bord (`/student/dashboard`)
   - Bibliothèque de cours (`/student/library`)
   - Discuter avec mon cours (`/student/chat`)
   - Assistant pédagogique IA (`/student/assistant`)
   - Assistant vocal (`/student/vocal`)
   - Test de niveau initial (`/student/test-niveau`)
   - Exercices et corrections pas-à-pas (`/student/exercises`)
   - Examens nationaux (`/student/examens`)
   - Parcours de révision (`/student/parcours`)
   - Historique et progression (`/student/history`)
   - Calendrier officiel (`/student/calendar`)

2. **Module Enseignant (7 interfaces)** :
   - Tableau de bord enseignant (`/teacher/dashboard`)
   - Mes classes (`/teacher/classes`)
   - Mes supports de cours (`/teacher/supports`)
   - Bibliothèque partagée (`/teacher/library`)
   - Générateur de sujets d'examen (`/teacher/generator`)
   - Corrections des copies (`/teacher/corrections`)
   - Suivi des élèves (`/teacher/suivi`)

3. **Module Parent (6 interfaces)** :
   - Tableau de bord parent (`/parent/dashboard`)
   - Mes enfants (`/parent/children`)
   - Rapport hebdomadaire (`/parent/report`)
   - Échéances & calendrier (`/parent/deadlines`)
   - Donner mon avis (`/parent/feedback`)
   - Aide et contact (`/parent/help`)

4. **Module Paramètres, Profil & Notifications (7 interfaces)** :
   - Profil et préférences élève (`/student/profile`)
   - Profil et paramètres enseignant (`/teacher/profile`)
   - Profil et paramètres parent (`/parent/profile`)
   - Centre de notifications élève (`/student/notifications`)
   - Notifications enseignant (`/teacher/notifications`)
   - Notifications parent (`/parent/notifications`)
   - Aide et assistance globale (`/student/help`, `/teacher/help`, `/parent/help`)

---

## 3. Dynamisation du Module Élève (Base de données SQLite)

### A. Données réelles configurées
- **Élève principal** : Kodjo Mensah (`koffi.student@monkayier.tg`), élève en Terminale D au Lycée de Tokoin (Lomé, Togo).
- **Matières réelles** : Mathématiques, Physique-Chimie, SVT, Philosophie, Français, Anglais, Histoire-Géo, ECM.
- **Ressources pédagogiques** : Cours réels insérés en base (Limites et Continuité, Cinématique, Génétique, etc.).
- **Notifications** : Alertes réelles stockées en table `notifications`.

### B. Contrôleur et Logique Métier
- **Fichier** : `app/Http/Controllers/StudentController.php`
- **Méthodes actives** :
  - `dashboard()` : Tableau de bord dynamique avec salutation, statistiques et cours récents.
  - `library()` : Recherche par mot-clé, filtrage dynamique par matière, compteur réel.
  - `uploadCourse()` : Enregistrement de nouveaux cours et fichiers dans `learning_resources` et `resource_files`.
  - `chat()` : Discussion interactive avec support du paramètre `?course={id}`.
  - `notifications()` & `markAllNotificationsRead()` : Gestion complète des alertes.
  - `profile()` & `updateProfile()` : Modification en temps réel du prénom, nom, email et bio.
  - `exercises()` & `test()` : Connexion aux matières et questions d'évaluation.

---

## 4. Phase 1 & 2 : Moteur Pédagogique et Interactivité

### A. Phase 1 : Boucle Interactive Test & Tableau de bord
- **Test de niveau initial (`/student/test-niveau`)** :
  - 5 questions réelles de Terminale D (Algèbre, Analyse, Géométrie, Probabilités) insérées dans `assessments`, `assessment_questions` et `question_options`.
  - Choix cliquables, validation immédiate (vert émeraude / orange), explications détaillées et barre de progression dynamique.
  - Enregistrement des essais dans `assessment_attempts` et `attempt_answers`.
  - Recalcul adaptatif des compétences réelles dans `student_competencies`.
- **Tableau de bord adaptatif (`/student/dashboard`)** :
  - Jauge circulaire conique CSS (`conic-gradient`) recalculée dynamiquement selon la moyenne réelle des compétences.
  - Barres de compétences synchronisées avec la base de données.
  - Coach IA adaptatif orienté vers le domaine le plus fragile de l'élève.

### B. Phase 2 : Moteur Socratique et Tuteur de Cours
- **Discuter avec son cours (`/student/chat`)** :
  - Chat interactif en temps réel avec persistance dans `ai_conversations` et `ai_messages`.
  - Double réponse pédagogique (*D'après ton cours* avec citation de page + *Explication complémentaire intuitive*).
  - Synchronisation en direct de l'extrait de cours avec surlignage dans la colonne latérale droite (*Passage utilisé*).
  - Puces de questions rapides cliquables et filtre de recherche de document en direct.
- **Assistant Pédagogique Socratique (`/student/assistant`)** :
  - Accompagnement progressif par indices (*Indice 1, Indice 2, Méthode complète*).
- **Assistant Vocal (`/student/vocal`)** :
  - Synthèse vocale française intégrée (`speechSynthesis`) avec sélection de voix (*Coach Kodjo* / *Prof Mawuli*).
  - Séance de révision orale interactive avec progression sur les questions de cours.

---

## 5. Statut du Serveur et Environnement
- **Serveur local** : `http://127.0.0.1:8000` (Actif)
- **Base de données** : `database/database.sqlite` (Opérationnelle, 86 tables)
- **Framework** : Laravel 11 sur PHP 8.2 (Windows)

---

## 6. Moteur de Parcours Personnalisé IA (Adossé aux Cours Réels & Coefficients Officiels)

### A. Architecture Modulaire (`app/Services/PathEngine/`)
1. **`GapAnalyzerService.php`** :
   - Calcule l'écart entre le niveau réel de l'élève (`student_competencies`) et les exigences du programme officiel (`curriculum_objectives`).
   - Applique les coefficients officiels nationaux du BAC (Mathématiques : Coeff 4, PCT : Coeff 4, SVT : Coeff 4, Philosophie : Coeff 2).
   - Produit l'indice d'urgence mathématique : `(100 - score_maitrise) × coefficient_officiel`.
2. **`CurriculumCourseMatcher.php`** :
   - Recherche et associe le document de cours réel le plus pertinent (`learning_resources`) à chaque objectif officiel.
   - Hiérarchie stricte : 1. Cours uploadé par l'élève lui-même, 2. Cours de son enseignant/établissement, 3. Cours officiel national validé.
3. **`PathOrchestratorService.php`** :
   - Gère le plan officiel de révision (`revision_plans`) et génère/ordonne les tâches de 25 minutes dans la table `revision_tasks`.
4. **`LearningSessionGenerator.php`** :
   - Structure une séance didactique interactive de 25 minutes en 3 étapes basées directement sur le cours :
     - Étape 1 : Synthèse du cours & Définitions fondamentales.
     - Étape 2 : Exemple type BAC résolu pas-à-pas avec démarche.
     - Étape 3 : Quiz interactif de validation issu des notions du cours avec options et explications immédiates.
5. **`AdaptivePathEngine.php`** :
   - Façade unifiée orchestrant `getDailySession($studentUserId)`, `getFullPath($studentUserId)` et `completeDailyTask($studentUserId, $taskId, $score)`.

### B. Contrôleur & Vues Intégrées
- **`app/Http/Controllers/StudentController.php`** :
  - `dashboard()` : Connecté au moteur pour afficher la séance active du jour, la durée et l'adossement au cours réel.
  - `parcours()` : Affiche la feuille de route complète, le planning hebdomadaire sur 7 jours et l'explication du plan (explicabilité de l'algorithme).
  - `seance()` : Vue dédiée `/student/seance/{taskId?}` pour vivre la séance didactique en 3 étapes.
  - `completeSeance()` : Endpoint POST `/student/seance/{taskId}/complete` qui valide la tâche, augmente la note de compétence dans `student_competencies` et envoie une notification de succès.
- **Interfaces Blade** :
  - `resources/views/student/dashboard.blade.php` : Carte héro dynamique avec lien direct vers la séance du jour.
  - `resources/views/student/parcours.blade.php` : Planning hebdomadaire dynamique et explicabilité du calcul algorithmique.
  - `resources/views/student/seance.blade.php` : Expérience interactive de révision de 25 minutes avec correction instantanée du quiz.

---

## 7. Collecte & Intégration des Ressources Officielles Nationales (MEPST Togo)

### A. Crawling et Inventaire Exhaustif (`ecole.gouv.tg`)
- **Portails parcourus en accès invité** :
  - Collège : `https://ecole.gouv.tg/college/` (Classes de 6e, 5e, 4e, 3e / BEPC)
  - Lycée : `https://ecole.gouv.tg/lycee/` (Classes de Seconde, Première / BAC 1, Terminale / BAC 2 - Séries A4, C, D, F, TI)
- **Fichiers d'inventaire générés** :
  - [`storage/app/official_resources/inventory_national.json`](file:///c:/MonKayier/storage/app/official_resources/inventory_national.json) (544 Ko)
  - [`docs/inventaire_national_togo.md`](file:///c:/MonKayier/docs/inventaire_national_togo.md)

### B. Téléchargement des Épreuves Officielles & Corrigés
- **Volume total** : **458 épreuves nationales en PDF** (0 échec, 100% de succès)
  - BAC 1 (Première) : 295 épreuves & remplacements (sessions 2016 à 2025, séries A4, C, D)
  - BAC 2 (Terminale) : 33 épreuves & corrigés (sessions 2023 à 2025, séries A4, C, D, F, TI)
  - BEPC (Troisième) : 118 épreuves & remplacements
  - Seconde : 12 épreuves régionales
- **Stockage physique structuré** :
  - Emplacement : `storage/app/official_resources/exams/{portal}/{exam_level}/{year}/{series}/{file_name}`
  - Manifeste d'intégrité : [`storage/app/official_resources/downloaded_exams_manifest.json`](file:///c:/MonKayier/storage/app/official_resources/downloaded_exams_manifest.json) (tailles et URLs sources)

### C. Injection & Adossement dans la Base de Données MonKayier (`database/database.sqlite`)
- **Matières officielles (`subjects`)** : 14 disciplines canoniques (ajout d'Allemand, Espagnol, Informatique, Technologie, Éducation Ménagère, Guides Officiels).
- **Séries (`school_series`)** : 7 séries configurées (A4, CD, F pour Terminale ; A4, CD pour Première ; A4, CD pour Seconde).
- **Curricula officiels (`curricula`)** : 10 programmes nationaux officialisés (6e, 5e, 4e, 3e, 2nde, 1ère A4, 1ère CD, Tle A4, Tle CD, Tle F).
- **Thèmes & Chapitres (`curriculum_topics`)** : **2 252 enregistrements** hiérarchisés.
- **Objectifs pédagogiques évaluables (`curriculum_objectives`)** : **1 781 compétences officielles**.
- **Ressources d'apprentissage (`learning_resources`)** : **943 ressources** :
  - **484 cours officiels nationaux** indexés et découpés.
  - **458 épreuves et corrigés nationaux** indexés (`resource_type = 'exam_paper'`).
- **Fichiers rattachés (`resource_files`)** : **459 fichiers PDF** liés avec empreinte cryptographique SHA-256 et taille en octets.

---

## 8. Chantier 2 : Moteur IA Open-Weights & Claviers Scientifiques Virtuels

### A. Architecture Double-Modèle Cloud Open-Weights (Option 1 via OpenRouter)
- **Modèle Sciences (Mathématiques, Physique-Chimie)** : `qwen/qwen-2.5-72b-instruct`
  - Raisonnement mathématique poussé, manipulation formelle des expressions, support natif de la syntaxe LaTeX (`$...$` et `$$...$$`).
- **Modèle Humanités (Français, Philosophie, Histoire-Géo, SVT)** : `mistralai/mistral-nemo`
  - Maîtrise de la rhétorique française, structuration d'axes de dissertation (thèse, antithèse, synthèse) et rigueur linguistique.
- **Service IA (`app/Services/Ai/OpenSourceAiService.php`)** :
  - Routage automatique selon la matière de la séance de révision.
  - Prompts pédagogiques socratiques conformes aux standards officiels du MEPST Togo (zéro émoji, ton académique et stimulant).
  - Évaluation de démarche scientifique (`evaluateStepDemonstration()`) pour les paliers 2 et 3.
  - Fallback pédagogique déterministe intégré en cas d'indisponibilité réseau ou d'absence de connectivité.

### B. Interface Révision IA & Claviers Scientifiques Virtuels (`resources/views/student/chat.blade.php`)
- **Moteur Typographique KaTeX** : Intégration CDN et extension auto-render pour formules inline (`$...$`) et bloc (`$$...$$`).
- **Clavier Virtuel Scientifique à Deux Onglets** :
  - Onglet Mathématiques : Symboles usuels ($\lim$, $\frac{a}{b}$, $\sqrt{x}$, $\int$, $\sum$, $\infty$, $\vec{u}$, etc.).
  - Onglet Physique-Chimie : Notations d'unités et grandeurs ($mol/L$, $\Delta t$, $\Omega$, $\mu F$, réactifs fléchés $\rightarrow$, etc.).
- **Barre d'Insertion Rapide (Quick Pills)** : Accès direct en un clic au-dessus de la zone de saisie du Tuteur IA.
- **Vérification en Ligne Validée** : Tests directs avec OpenRouter réussis à 100% sur les deux modèles.

---

## 9. Chantier 3 : Intégration des 458 Annales Officielles au Palier 3 et Portail Examens

### A. Portail Dédié des Examens Nationaux (`/student/examens`)
- **Contrôleur (`app/Http/Controllers/StudentController.php`)** :
  - `examens(Request $request)` : Rendu direct de la vue dédiée `resources/views/student/examens.blade.php`.
  - Filtres multicritères combinables : Matière, Classe, Série (D, C4, A4, Collège), Session (2016 à 2025), Type d'examen (BAC 2, BAC 1, BEPC, Régionales).
  - Scope zéro-bruit : Filtrage automatique ciblé sur la classe et la série de l'élève connecté.
  - Prévisualisation directe (`/student/examens/file/{fileId}/preview`) et téléchargement sécurisé (`/student/examens/file/{fileId}/download`) avec stratégie de fallback garantissant 0% d'erreur 404.

### B. Adossement Dynamique des Annales au Palier 3 (Révision IA & Synthèse BAC)
- **Méthode `findOfficialExamForCourse()`** :
  - Détecte et rattache automatiquement la meilleure épreuve officielle du BAC (avec son fichier PDF physique présent sur disque) au cours en cours de travail.
- **Interface Révision IA (`resources/views/student/chat.blade.php`)** :
  - Intégration d'un bandeau officiel au Palier 3 : « Épreuve Officielle du BAC Togo Rattachée ».
  - Accès direct en 1 clic pour consulter l'épreuve originale en PDF (`preview`) ou la télécharger (`download`).
- **Séance Didactique du Jour (`resources/views/student/seance.blade.php`)** :
  - Rapprochement direct de l'épreuve nationale du BAC avec lien direct vers le sujet officiel.

### C. Navigation Globale Élève (`resources/views/layouts/student.blade.php`)
- Ajout de l'entrée « Examens & Annales » dans la barre latérale de navigation élève et dans le menu d'accès rapide (Corner Quick-Dock).
- Distinction claire entre la « Bibliothèque » (cours nationaux) et le portail « Examens & Annales » (458 épreuves et corrigés certifiés).



