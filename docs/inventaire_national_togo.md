# Inventaire National des Ressources Éducatives du Togo
*Source officielle : [`ecole.gouv.tg/lycee`](https://ecole.gouv.tg/lycee/) et [`ecole.gouv.tg/college`](https://ecole.gouv.tg/college/)*  
*Date de recensement : 27 Octobre 2026*  
*Fichier de données brut : `storage/app/official_resources/inventory_national.json`*

---

## 1. Synthèse Globale Chiffrée

| Indicateur | Volume Recensé | Détails |
| :--- | :---: | :--- |
| **Épreuves d'Examens Officielles (PDF)** | **458** | BAC 1, BAC 2, BEPC & Compositions régionales (2016-2025) |
| **Cours & Modules Officiels** | **475** | Tous niveaux de la 6ème à la Terminale |
| **Chapitres & Objectifs Pédagogiques** | **1 771** | Notions, compétences et thématiques officielles |
| **Niveaux Scolaires Couverts** | **7** | 6e, 5e, 4e, 3e, Seconde, Première, Terminale |

---

## 2. Pôle 1 : Les Examens et Annales (458 fichiers PDF)

### A. Lycée — BAC 1 & BAC 2 (282 épreuves)
- **Années disponibles** : 2016, 2017, 2018, 2019, 2020, 2021, 2022, 2023, 2024, 2025
- **Séries** : A4, C, D, E, F, G
- **Matières répertoriées** :
  - Mathématiques (Séries A4, C, D)
  - Sciences Physiques / PCT (Séries C, D)
  - Sciences de la Vie et de la Terre (Séries D, C)
  - Philosophie (Toutes séries)
  - Français (Toutes séries)
  - Anglais (A4, C, D)
  - Allemand, Espagnol (Série A4)
  - Histoire-Géographie & ECM
  - Épreuves normales et épreuves de remplacement

### B. Lycée — Compositions Régionales (58 épreuves)
- Sujets types régionaux d'entraînement trimestriels pour le Lycée.

### C. Collège — BEPC (118 épreuves)
- Épreuves nationales du Brevet d'Études du Premier Cycle (BEPC) de 2016 à 2024 par matière :
  - Mathématiques, PCT, SVT, Français, Anglais, Histoire-Géo, ECM.

---

## 3. Pôle 2 : Le Lycée (337 cours · 1 015 chapitres)

### A. Terminale (135 cours · 408 chapitres)
- **Série Terminale D** :
  - *Mathématiques* (Calculs algébriques, Analyse, Trigonométrie, Géométrie du plan, Géométrie de l'espace, Organisation des données).
  - *Physique-Chimie* (Mécanique, Électricité, Optique, Chimie organique, Chimie minérale).
  - *SVT* (Géologie, Matière des êtres vivants, La cellule, Nutrition animale et humaine).
  - *Philosophie*, *Français*, *Anglais*, *Histoire-Géo*, *ECM*.
- **Série Terminale C** :
  - Arithmétique, Analyse avancée, Applications du plan, Mécanique céleste, Électricité.
- **Série Terminale A4** :
  - Philosophie approfondie, Français (littérature, mutations africaines), Langues vivantes (Allemand, Espagnol, Anglais).

### B. Première (126 cours · 416 chapitres)
- **Série Première D & C** :
  - Fonctions numériques, Barycentres, Suites, Électrostatique, Optique, Chimie générale.
- **Série Première A4** :
  - Littérature, Histoire coloniale du Togo, Langues.

### C. Seconde (76 cours · 191 chapitres)
- **Seconde C & D** : Fondations scientifiques du second cycle.
- **Seconde A4** : Fondations littéraires et sciences humaines.

---

## 4. Pôle 3 : Le Collège (138 cours · 756 chapitres)

Chaque niveau intègre la catégorie stratégique **`PROGRAMMES EDUCATIFS & GUIDES D'EXÉCUTION`** qui formalise les compétences officielles exigées par le ministère togolais :

| Classe | Cours | Chapitres | Programmes & Guides Nationaux Inclus |
| :--- | :---: | :---: | :--- |
| **Sixième (6e)** | 37 | 193 | Programme Maths, PCT, SVT, FR, ANG, HG, ECM 6e |
| **Cinquième (5e)**| 34 | 175 | Programme Maths, PCT, SVT, FR, ANG, HG, ECM 5e |
| **Quatrième (4e)**| 32 | 189 | Programme Maths, PCT, SVT, FR, ANG, HG, ECM 4e |
| **Troisième (3e)**| 35 | 199 | Programme Maths, PCT, SVT, FR, ANG, HG, ECM 3e |

---

## 5. Exploitation pour le Moteur Pédagogique MonKayier

Grâce à cet inventaire complet, l'intégration dans notre base de données SQLite s'articule comme suit :
1. **Tables de Structure Officielle** :
   - `curricula` : 1 programme officiel par niveau et série.
   - `curriculum_topics` : Les **1 771 chapitres répertoriés**.
   - `curriculum_objectives` : Les compétences clés associées.
2. **Tables de Contenu Réel** :
   - `learning_resources` : Les **475 cours** et les **458 épreuves d'examen**.
   - `resource_files` : Le lien direct de téléchargement ou le PDF stocké localement.
3. **Moteur d'Entraînement Adaptatif IA** :
   - L'IA peut désormais proposer des séances et des quiz en citant textuellement l'épreuve d'origine (ex. : *"Question tirée de l'épreuve officielle de Mathématiques BAC 2 Série D - Session 2023"*).
