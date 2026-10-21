# MONKAYIER — Plateforme Éducative Numérique avec Assistant IA

> **Porteur de projet** : KAMEHUB SERVICES SARL-U  
> **Marché de lancement** : Togo (extensible aux pays de la zone francophone et d'Afrique subsaharienne)  
> **Publics cibles** : Élèves de la 6e à la Terminale (priorité 3e, 1ère, Tle), Enseignants, Parents/Tuteurs, Établissements  
> **Version** : 1.0 (Octobre 2026)

---

## 1. Présentation du Projet

MONKAYIER est une plateforme d'apprentissage intelligente et souveraine, conçue pour accompagner la réussite scolaire des élèves togolais et africains.  
Elle associe :
1. **Un assistant pédagogique IA RAG** qui répond en priorité à partir des cours officiels validés et cite systématiquement ses sources (matière, document, page).
2. **Une bibliothèque numérique structurée** selon le programme officiel togolais (niveaux, séries A4/CD/F, matières, chapitres, compétences).
3. **Un moteur d'évaluations et de révisions** adaptatif (quiz, QCM, examens blancs du BEPC, BAC 1 et BAC 2).
4. **Des espaces dédiés aux professeurs et aux parents** garantissant la protection des mineurs et la confidentialité des échanges d'apprentissage.
5. **Un calendrier scolaire et officiel** centralisant les dates d'examens et de rentrées confirmées par les ministères.
6. **Un programme de solidarité** permettant l'octroi de bourses d'accès gratuit aux élèves défavorisés.

L'application est **mobile-first**, légère, optimisée pour le faible débit et conçue pour fonctionner en mode dégradé/PWA hors-ligne.

---

## 2. Principes Fondamentaux & Non Négociables

* **Intégrité pédagogique** : L'IA ne doit jamais inventer une date, un cours, une source officielle ou un résultat scolaire.
* **Respect de la vie privée des mineurs** : Les conversations avec l'assistant IA sont privées. Le parent a accès à la progression, mais pas aux chats de l'élève.
* **Sécurité & Isolation** : Droits vérifiés au niveau objet pour chaque ressource (UUIDv4). Aucun secret ou mot de passe dans Git.
* **Fidélité visuelle absolue** : Respect rigoureux du fichier officiel `MonKayier Maquette.pdf` (78 pages) et des tokens CSS centralisés (`resources/css/tokens.css`).

---

## 3. Structure du Dépôt

```
MonKayier/
├── app/                  # Logique applicative Laravel (Modèles, Services, Policies, Jobs)
├── database/             # Schémas PostgreSQL, Migrations et Seeders
│   ├── migrations/       # Migrations versionnées append-only
│   ├── seeders/          # Données de démonstration et référentiels officiels Togo
│   └── schema_v1_postgresql.sql # Schéma DDL complet V1
├── docs/                 # Documentation d'architecture et de conception
│   ├── data_dictionary_v1.md # Dictionnaire exhaustif des données (13 lots)
│   ├── decisions.md      # Journal des décisions architecturales (ADR)
│   ├── erd_v1.md         # Diagramme Entité-Relation Mermaid
│   └── backlog_v1.md     # Backlog priorisé des phases
├── resources/            # Vues Blade, composants légers et styles
│   ├── css/              # Styles et tokens CSS officiels (tokens.css)
│   └── views/            # Vues Blade légères et accessibles
├── public/               # Point d'entrée web et assets statiques
├── MONKAYIER RESOURCES DOCUMENT DESCRIPTION/ # Dossier des spécifications et maquettes
├── .env.example          # Gabarit de configuration sans secret
└── .gitignore            # Exclusions strictes de sécurité
```

---

## 4. Prérequis et Installation Locale

### Prérequis
* **PHP** : 8.2 ou 8.3 avec extensions `pdo_pgsql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `redis`
* **Composer** : 2.x
* **PostgreSQL** : 15 ou 16
* **Redis** : 6.x ou 7.x
* **Node.js** : 18+ ou 20+

### Démarrage rapide
```bash
# 1. Cloner et préparer la configuration
cp .env.example .env

# 2. Installer les dépendances backend (lorsque PHP/Composer sont activés)
composer install

# 3. Générer la clé applicative
php artisan key:generate

# 4. Exécuter les migrations et les seeders de référence Togo
php artisan migrate --seed

# 5. Lancer le serveur de développement
php artisan serve
```

---

## 5. Suivi des Phases de Développement

| Phase | Intitulé | Statut |
| :--- | :--- | :--- |
| **Phase 0** | Cadrage, initialisation Git & structure | **En cours / Réalisée** |
| **Phase 1** | Schéma PostgreSQL V1 & Dictionnaire | **En cours / Livrée** |
| **Phase 2** | Socle Backend Laravel & Authentification | Planifiée |
| **Phase 3** | Référentiels scolaires Togo & Contenus | Planifiée |
| **Phase 4** | API Laravel & Administration | Planifiée |
| **Phase 5** | Interface Élève (Web / Mobile PWA) | Planifiée |
| **Phase 6** | Évaluations, Quiz & Révisions | Planifiée |
| **Phase 7** | Moteur IA Pédagogique & RAG | Planifiée |
| **Phase 8** | Espace Enseignants | Planifiée |
| **Phase 9** | Espace Parents & Tuteurs | Planifiée |
| **Phase 10** | Tests de charge & Sécurité | Planifiée |
| **Phase 11** | Pilote & Lancement Togo | Planifiée |
