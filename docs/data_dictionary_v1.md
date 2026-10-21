# MONKAYIER — Dictionnaire de Données V1 (PostgreSQL)

> **Projet** : MONKAYIER — Plateforme éducative numérique avec assistant IA  
> **Porteur** : KAMEHUB SERVICES SARL-U  
> **Phase** : Phase 1 — Modèle de données & Schéma relationnel PostgreSQL  
> **Version** : 1.0 (Octobre 2026)

---

## 1. Conventions de Schéma
* **Identifiants exposés** : `UUIDv4` (`gen_random_uuid()`) systématique pour toute entité manipulée via l'API.
* **Nomenclature** : `snake_case`, noms de tables au pluriel cohérent (`users`, `learning_resources`, `assessments`).
* **Horodatage** : `TIMESTAMPTZ` stocké en UTC. Fuseau local (`Africa/Lome`) appliqué à l'affichage.
* **Traçabilité** : Colonnes `created_at` et `updated_at` obligatoires sur toutes les entités mutables.
* **Intégrité stricte** : Clés étrangères explicites avec règles `ON DELETE` adaptées (`CASCADE`, `RESTRICT` ou `SET NULL`), contraintes `CHECK` sur les statuts, scores bornés.

---

## 2. Dictionnaire des Tables par Lot Fonctionnel

### DB-01 : Référentiels Scolaires (Togo & Extension)
| Table | Description | Champs Clés | Contraintes & Règles |
| :--- | :--- | :--- | :--- |
| `countries` | Pays supportés par la plateforme | `id`, `code` (ISO), `name`, `default_locale`, `timezone`, `is_active` | `code` UNIQUE. Togo ('TG') par défaut. |
| `academic_years` | Découpage des années scolaires | `id`, `country_id`, `label`, `starts_on`, `ends_on`, `is_current` | `UNIQUE(country_id, label)`. `starts_on < ends_on`. |
| `education_levels` | Niveaux d'enseignement | `id`, `country_id`, `code`, `name`, `cycle`, `sort_order` | `UNIQUE(country_id, code)`. 6e, 5e, 4e, 3e (collège) ; 2nde, 1ère, Tle (lycée). |
| `school_series` | Séries du second cycle | `id`, `country_id`, `education_level_id`, `code`, `name` | `UNIQUE(country_id, education_level_id, code)`. A4, CD, F... |
| `subjects` | Matières enseignées | `id`, `country_id`, `code`, `name`, `description`, `is_active` | `UNIQUE(country_id, code)`. MATHS, PCT, SVT, FRANCAIS... |
| `curricula` | Programmes scolaires officiels | `id`, `country_id`, `academic_year_id`, `education_level_id`, `series_id`, `title`, `source_url`, `status`, `version` | `status` IN ('draft', 'review', 'approved', 'published'). Source traçable obligatoire. |
| `curriculum_topics` | Domaines et chapitres d'un programme | `id`, `curriculum_id`, `subject_id`, `parent_topic_id`, `code`, `title`, `sort_order` | Arborescence hiérarchique parent/enfant. |
| `curriculum_objectives` | Objectifs pédagogiques mesurables | `id`, `topic_id`, `code`, `description`, `sort_order` | Unités d'apprentissage servant à évaluer la maîtrise de l'élève. |

### DB-02 : Identité, Sécurité & Profils
| Table | Description | Champs Clés | Contraintes & Règles |
| :--- | :--- | :--- | :--- |
| `users` | Comptes utilisateurs | `id`, `email`, `phone`, `password_hash`, `status`, `email_verified_at`, `phone_verified_at`, `last_login_at` | Email ou téléphone obligatoire. `status` IN ('pending', 'active', 'suspended', 'closed'). |
| `user_profiles` | Données publiques et d'usage | `user_id`, `first_name`, `last_name`, `birth_date`, `preferred_language`, `avatar_key` | Relation 1-to-1 avec `users`. Collecte minimale de données. |
| `roles` | Rôles système | `id`, `code`, `label` | Codes fixes : `student`, `teacher`, `parent`, `school_admin`, `support`, `solidarity_agent`, `admin`. |
| `user_roles` | Attribution des rôles aux utilisateurs | `user_id`, `role_id`, `assigned_by`, `created_at` | Clé primaire composite `(user_id, role_id)`. Attribution auditée. |
| `user_preferences` | Préférences d'interface et d'accessibilité | `user_id`, `locale`, `timezone`, `notification_settings`, `accessibility_settings` | Paramètres d'accessibilité (contraste, taille texte, synthèse vocale). |
| `user_devices` | Terminaux déclarés pour PWA et notifications | `id`, `user_id`, `device_label`, `platform`, `fcm_token`, `last_seen_at`, `revoked_at` | Révocable par l'utilisateur. |
| `audit_logs` | Traces d'audit des actions sensibles | `id`, `actor_user_id`, `action`, `target_type`, `target_id`, `metadata`, `ip_hash` | Zéro secret, mot de passe ou contenu intime dans les logs. |

### DB-03 : Établissements & Classes
| Table | Description | Champs Clés | Contraintes & Règles |
| :--- | :--- | :--- | :--- |
| `schools` | Établissements scolaires partenaires | `id`, `country_id`, `name`, `school_type`, `city`, `status` | Écoles publiques, privées ou confessionnelles. |
| `school_memberships` | Appartenance d'un utilisateur à une école | `id`, `school_id`, `user_id`, `membership_role`, `status` | `UNIQUE(school_id, user_id, membership_role)`. |
| `classes` | Classes physiques ou virtuelles | `id`, `school_id`, `academic_year_id`, `education_level_id`, `series_id`, `label`, `created_by` | Ex : 3e B, Terminale D1. Peut être autonome si `school_id` nul. |
| `class_memberships` | Inscription des élèves et enseignants | `id`, `class_id`, `user_id`, `membership_role`, `status` | `UNIQUE(class_id, user_id, membership_role)`. |
| `class_subjects` | Matières enseignées dans une classe | `id`, `class_id`, `subject_id`, `teacher_user_id` | `UNIQUE(class_id, subject_id)`. |

### DB-04 : Ressources Pédagogiques & Pipeline RAG
| Table | Description | Champs Clés | Contraintes & Règles |
| :--- | :--- | :--- | :--- |
| `learning_resources` | Fiches de cours, résumés, annales | `id`, `country_id`, `curriculum_id`, `subject_id`, `education_level_id`, `title`, `summary`, `resource_type`, `status` | Statuts : `draft`, `review`, `approved`, `published`, `retired`. |
| `resource_versions` | Versions immuables du contenu textuel | `id`, `resource_id`, `version_number`, `content_text`, `checksum` | `UNIQUE(resource_id, version_number)`. Historique non destructif. |
| `resource_files` | Fichiers PDF/documents originaux | `id`, `resource_version_id`, `storage_key`, `original_name`, `mime_type`, `size_bytes`, `checksum` | Stockage objet privé. URLs pré-signées temporaires. |
| `resource_pages` | Découpage page par page du document | `id`, `resource_version_id`, `page_number`, `extracted_text`, `extraction_status` | `UNIQUE(resource_version_id, page_number)`. |
| `resource_chunks` | Fragments sémantiques pour le RAG | `id`, `resource_version_id`, `page_id`, `chunk_index`, `content_text`, `token_estimate` | Unité fondamentale d'interrogation pour l'IA RAG. |
| `resource_sources` | Références bibliographiques officielles | `id`, `resource_id`, `source_title`, `publisher`, `source_url`, `verification_status` | Indispensable pour la citation des sources par l'IA. |
| `resource_topics` | Rattachement d'une ressource aux chapitres | `resource_id`, `topic_id`, `relevance_score` | Clé composite `(resource_id, topic_id)`. |
| `resource_reviews` | Validation éditoriale humaine | `id`, `resource_id`, `reviewer_user_id`, `status`, `comment` | Approbation avant publication. |

### DB-05 : Évaluations, Questions & Tentatives
| Table | Description | Champs Clés | Contraintes & Règles |
| :--- | :--- | :--- | :--- |
| `assessments` | Quiz, devoirs, examens blancs | `id`, `subject_id`, `title`, `assessment_type`, `duration_seconds`, `status`, `created_by` | Types : `quiz`, `exam_prep`, `homework`, `diagnostic`. |
| `assessment_questions` | Questions individuelles | `id`, `assessment_id`, `question_type`, `prompt`, `explanation`, `points`, `position` | Types : `single_choice`, `multiple_choice`, `short_answer`, `true_false`. `points >= 0`. |
| `question_options` | Propositions de réponses QCM | `id`, `question_id`, `option_text`, `position`, `is_correct`, `explanation` | `is_correct` non divulgué au client avant soumission. |
| `question_answers` | Clés de correction et barèmes | `id`, `question_id`, `answer_key`, `grading_rule` | Accès restreint côté serveur. |
| `assessment_attempts` | Sessions de passage d'un élève | `id`, `assessment_id`, `student_user_id`, `status`, `score`, `max_score`, `idempotency_key` | `UNIQUE(student_user_id, idempotency_key)` contre double soumission. |
| `attempt_answers` | Réponses enregistrées par l'élève | `id`, `attempt_id`, `question_id`, `answer_payload`, `awarded_points`, `feedback` | `UNIQUE(attempt_id, question_id)`. |
| `attempt_events` | Télémétrie d'effort et d'assiduité | `id`, `attempt_id`, `event_type`, `occurred_at`, `metadata` | Détection d'abandon, temps par question. |

### DB-06 : Compétences & Révision Adaptative
| Table | Description | Champs Clés | Contraintes & Règles |
| :--- | :--- | :--- | :--- |
| `student_competencies` | Niveau de maîtrise par objectif | `id`, `student_user_id`, `objective_id`, `mastery_score`, `evidence_count`, `updated_at` | `UNIQUE(student_user_id, objective_id)`. Score borné entre 0 et 100%. |
| `revision_plans` | Plans d'entraînement générés | `id`, `student_user_id`, `title`, `target_date`, `status`, `generated_by` | Ajustable par l'élève et son enseignant. |
| `revision_tasks` | Tâches de révision concrètes | `id`, `plan_id`, `objective_id`, `resource_id`, `due_at`, `status`, `priority` | `status` IN ('pending', 'in_progress', 'completed'). |
| `study_sessions` | Historique du temps d'étude effectif | `id`, `student_user_id`, `subject_id`, `started_at`, `ended_at`, `duration_seconds` | Mesure d'assiduité pour le tableau de bord élève et parent. |

### DB-07 : Assistant IA Pédagogique & RAG
| Table | Description | Champs Clés | Contraintes & Règles |
| :--- | :--- | :--- | :--- |
| `ai_models` | Modèles de langage configurés | `id`, `provider`, `model_code`, `capabilities`, `enabled` | Zéro secret API dans cette table. |
| `ai_assistants` | Profils d'assistants pédagogiques | `id`, `name`, `pedagogical_scope`, `status`, `created_by` | Spécialisation par niveau et matière. |
| `ai_conversations` | Fils de discussion de l'élève | `id`, `user_id`, `assistant_id`, `subject_id`, `title`, `status` | **Strictement privé** : inaccessible aux parents et camarades. |
| `ai_messages` | Messages échangés | `id`, `conversation_id`, `role`, `content`, `status`, `input_tokens`, `output_tokens`, `latency_ms` | `role` IN ('user', 'assistant'). |
| `ai_message_sources` | Sources documentaires citées par l'IA | `id`, `message_id`, `resource_chunk_id`, `citation_label`, `relevance_score` | Lien direct vers le fragment et la page du cours officiel. |
| `ai_feedback` | Évaluation de la réponse par l'élève | `id`, `message_id`, `user_id`, `rating`, `reason`, `comment` | `rating` (+1 ou -1) pour le monitoring qualité. |
| `ai_usage_events` | Suivi de consommation et coûts | `id`, `user_id`, `model_id`, `event_type`, `tokens`, `estimated_cost` | Métriques de coûts sans journaliser les prompts sensibles. |

### DB-08 : Espace Enseignants & Devoirs
| Table | Description | Champs Clés | Contraintes & Règles |
| :--- | :--- | :--- | :--- |
| `assignments` | Devoirs assignés à une classe | `id`, `class_id`, `teacher_user_id`, `assessment_id`, `title`, `due_at`, `status` | Date limite et suivi des rendus. |
| `assignment_submissions` | Copies rendues par les élèves | `id`, `assignment_id`, `student_user_id`, `attempt_id`, `status`, `teacher_feedback` | `UNIQUE(assignment_id, student_user_id)`. |
| `grading_rubrics` & `rubric_items` | Barèmes et critères d'évaluation | `id`, `assessment_id`, `total_points`, `criterion`, `max_points` | Grille critériée pour une correction transparente. |
| `submission_grades` & `grade_details` | Notes officielles attribuées | `id`, `submission_id`, `graded_by`, `final_score`, `is_official` | Seul l'enseignant valide la note officielle. |
| `ai_grading_suggestions` | Pré-correction proposée par l'IA | `id`, `submission_id`, `suggested_score`, `justification`, `status` | L'enseignant doit accepter, modifier ou rejeter la suggestion. |
| `teacher_reviews` | Historique de décision de l'enseignant | `id`, `suggestion_id`, `teacher_user_id`, `action`, `teacher_comment` | Traçabilité complète du contrôle humain sur l'IA. |

### DB-09 : Espace Parents & Tuteurs
| Table | Description | Champs Clés | Contraintes & Règles |
| :--- | :--- | :--- | :--- |
| `parent_student_links` | Association parent-enfant | `id`, `parent_user_id`, `student_user_id`, `status`, `relationship_type`, `verified_at`, `revoked_at` | `UNIQUE(parent_user_id, student_user_id)`. Révocable à tout moment. |
| `parent_link_codes` | Codes de liaison temporaires | `id`, `student_user_id`, `code_hash`, `expires_at`, `used_at` | Code à usage unique haché en base. |
| `student_reports` | Rapports de synthèse périodiques | `id`, `student_user_id`, `period_start`, `period_end`, `summary_data` | Temps d'étude, chapitres travaillés, notes moyennes. |
| `student_deadlines` | Échéances scolaires visibles du parent | `id`, `student_user_id`, `title`, `due_at`, `source_type`, `is_completed` | Visibilité sur les devoirs et examens à venir. |
| `parent_report_access_logs` | Journal des consultations parentales | `id`, `parent_user_id`, `student_user_id`, `accessed_at` | Traçabilité de consultation respectueuse des mineurs. |

### DB-10 : Calendrier Scolaire Officiel & Examens Nationaux (Togo)
| Table | Description | Champs Clés | Contraintes & Règles |
| :--- | :--- | :--- | :--- |
| `calendar_sources` | Actes et décrets ministériels officiels | `id`, `title`, `source_url`, `authority`, `checked_at`, `verification_status` | MEPST Togo. Seule autorité pour déclarer une date officielle. |
| `calendar_events` | Événements scolaires (rentrées, congés) | `id`, `country_id`, `academic_year_id`, `title`, `starts_at`, `ends_at`, `event_type`, `status` | `status` IN ('verified', 'tentative'). |
| `exam_sessions` | Sessions d'examens nationaux | `id`, `country_id`, `academic_year_id`, `education_level_id`, `code`, `title`, `written_exam_starts_on` | BEPC, BAC 1, BAC 2, CEPD. |
| `exam_results` | Résultats d'examens publiés | `id`, `exam_session_id`, `student_user_id`, `candidate_number`, `candidate_name`, `decision` | Provenance officielle obligatoire. Données privées sécurisées. |

### DB-11 : Bourses & Programme de Solidarité
| Table | Description | Champs Clés | Contraintes & Règles |
| :--- | :--- | :--- | :--- |
| `solidarity_requests` | Demandes de bourse / accès gratuit | `id`, `applicant_user_id`, `student_user_id`, `status`, `reason_category`, `submitted_at`, `decided_at` | Catégories : orphelinat, zone rurale, situation précaire. |
| `solidarity_documents` | Pièces justificatives chiffrées | `id`, `request_id`, `storage_key`, `document_type`, `uploaded_at`, `retention_until` | Stockage hautement restreint, accessible uniquement aux agents habilités. |
| `solidarity_decisions` | Décisions d'octroi ou de refus | `id`, `request_id`, `decided_by`, `decision`, `public_reason`, `internal_notes`, `granted_duration_days` | Décision motivée et tracée. |
| `solidarity_reviews` | Recours et réexamens | `id`, `request_id`, `reviewer_user_id`, `action`, `notes` | Double regard possible. |

### DB-12 : Notifications, Synchronisation Offline & Système
| Table | Description | Champs Clés | Contraintes & Règles |
| :--- | :--- | :--- | :--- |
| `notifications` | Messages et alertes utilisateurs | `id`, `user_id`, `category`, `title`, `body`, `is_read`, `read_at` | Catégories : devoirs, rappels, examens, système. |
| `sync_devices` | Terminaux pour la synchronisation PWA | `id`, `user_id`, `client_device_id`, `last_synced_at`, `is_revoked` | `UNIQUE(user_id, client_device_id)`. |
| `sync_operations` | Journal des mutations hors-ligne | `id`, `device_id`, `user_id`, `client_operation_uuid`, `operation_type`, `payload`, `status` | `UNIQUE(device_id, client_operation_uuid)` pour idempotence stricte. |
| `sync_conflicts` | Conflits de synchronisation | `id`, `operation_id`, `conflict_type`, `server_data`, `client_data`, `resolution_status` | Résolution déterministe sans écrasement silencieux. |
| `system_settings` | Paramètres généraux de la plateforme | `key`, `value`, `description`, `is_public` | Clé primaire textuelle. Aucun secret stocké. |
| `feature_flags` | Activation progressive des fonctionnalités | `name`, `is_enabled`, `rules` | Déploiement graduel par niveau, pays ou cohorte. |
| `data_exports` & `deletion_requests` | Droits RGPD / Protection des données | `id`, `user_id`, `status`, `scheduled_for`, `executed_at` | Droit à l'effacement et portabilité pour les mineurs. |
