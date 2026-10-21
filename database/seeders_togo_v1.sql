-- ==============================================================================
-- MONKAYIER — Données Initiales de Référence Togo (Seeders V1)
-- Porteur : KAMEHUB SERVICES SARL-U
-- ==============================================================================

-- 1. PAYS TOGO
INSERT INTO countries (id, code, name, default_locale, timezone, is_active)
VALUES ('00000000-0000-0000-0001-000000000001', 'TG', 'Togo', 'fr', 'Africa/Lome', true)
ON CONFLICT (code) DO NOTHING;

-- 2. ANNÉE SCOLAIRE 2026-2027
INSERT INTO academic_years (id, country_id, label, starts_on, ends_on, is_current)
VALUES ('00000000-0000-0000-0002-000000000001', '00000000-0000-0000-0001-000000000001', '2026-2027', '2026-09-15', '2027-06-30', true)
ON CONFLICT (country_id, label) DO NOTHING;

-- 3. NIVEAUX SCOLAIRES DU TOGO (Collège & Lycée)
INSERT INTO education_levels (id, country_id, code, name, cycle, sort_order) VALUES
('00000000-0000-0000-0003-000000000006', '00000000-0000-0000-0001-000000000001', '6E', 'Sixième', 'college', 1),
('00000000-0000-0000-0003-000000000005', '00000000-0000-0000-0001-000000000001', '5E', 'Cinquième', 'college', 2),
('00000000-0000-0000-0003-000000000004', '00000000-0000-0000-0001-000000000001', '4E', 'Quatrième', 'college', 3),
('00000000-0000-0000-0003-000000000003', '00000000-0000-0000-0001-000000000001', '3E', 'Troisième (BEPC)', 'college', 4),
('00000000-0000-0000-0003-000000000002', '00000000-0000-0000-0001-000000000001', '2NDE', 'Seconde', 'lycee', 5),
('00000000-0000-0000-0003-000000000001', '00000000-0000-0000-0001-000000000001', '1ERE', 'Première (BAC 1)', 'lycee', 6),
('00000000-0000-0000-0003-000000000000', '00000000-0000-0000-0001-000000000001', 'TLE', 'Terminale (BAC 2)', 'lycee', 7)
ON CONFLICT (country_id, code) DO NOTHING;

-- 4. SÉRIES DU LYCÉE AU TOGO
INSERT INTO school_series (id, country_id, education_level_id, code, name, description) VALUES
('00000000-0000-0000-0004-000000000001', '00000000-0000-0000-0001-000000000001', '00000000-0000-0000-0003-000000000000', 'A4', 'Série Littéraire (A4)', 'Lettres, Langues, Histoire-Géo, Philosophie'),
('00000000-0000-0000-0004-000000000002', '00000000-0000-0000-0001-000000000001', '00000000-0000-0000-0003-000000000000', 'CD', 'Série Scientifique (C / D)', 'Mathématiques, Sciences Physiques (PCT), Sciences de la Vie et de la Terre (SVT)'),
('00000000-0000-0000-0004-000000000003', '00000000-0000-0000-0001-000000000001', '00000000-0000-0000-0003-000000000000', 'F', 'Séries Techniques Industrielles', 'Génie Mécanique, Électrotechnique, Génie Civil')
ON CONFLICT (country_id, education_level_id, code) DO NOTHING;

-- 5. MATIÈRES OFFICIELLES
INSERT INTO subjects (id, country_id, code, name, description, is_active) VALUES
('00000000-0000-0000-0005-000000000001', '00000000-0000-0000-0001-000000000001', 'MATHS', 'Mathématiques', 'Algèbre, Géométrie, Analyse, Probabilités', true),
('00000000-0000-0000-0005-000000000002', '00000000-0000-0000-0001-000000000001', 'PCT', 'Physique-Chimie et Technologie', 'Mécanique, Électricité, Optique, Chimie générale et organique', true),
('00000000-0000-0000-0005-000000000003', '00000000-0000-0000-0001-000000000001', 'SVT', 'Sciences de la Vie et de la Terre', 'Biologie, Génétique, Écologie, Géologie', true),
('00000000-0000-0000-0005-000000000004', '00000000-0000-0000-0001-000000000001', 'FRANCAIS', 'Français', 'Grammaire, Vocabulaire, Expression écrite, Littérature', true),
('00000000-0000-0000-0005-000000000005', '00000000-0000-0000-0001-000000000001', 'PHILO', 'Philosophie', 'Épistémologie, Métaphysique, Éthique, Logique', true),
('00000000-0000-0000-0005-000000000006', '00000000-0000-0000-0001-000000000001', 'ANGLAIS', 'Anglais', 'Compréhension, Grammaire, Communication orale et écrite', true),
('00000000-0000-0000-0005-000000000007', '00000000-0000-0000-0001-000000000001', 'HIST_GEO', 'Histoire et Géographie', 'Histoire générale de l''Afrique et du Togo, Géographie économique', true),
('00000000-0000-0000-0005-000000000008', '00000000-0000-0000-0001-000000000001', 'ECM', 'Éducation Civique et Morale', 'Citoyenneté, Droits de l''Homme, Institutions de la République', true)
ON CONFLICT (country_id, code) DO NOTHING;

-- 6. RÔLES SYSTÈME CONTRÔLÉS
INSERT INTO roles (id, code, label, description) VALUES
('00000000-0000-0000-0006-000000000001', 'student', 'Élève', 'Accès aux cours, exercices, quiz et assistant IA'),
('00000000-0000-0000-0006-000000000002', 'teacher', 'Enseignant', 'Création de devoirs, corrections assistées et suivi des classes'),
('00000000-0000-0000-0006-000000000003', 'parent', 'Parent / Tuteur', 'Consultation des rapports de progression et des échéances de ses enfants'),
('00000000-0000-0000-0006-000000000004', 'school_admin', 'Administrateur d''Établissement', 'Gestion des classes, enseignants et effectifs de son école'),
('00000000-0000-0000-0006-000000000005', 'support', 'Support et Modération', 'Gestion des signalements et assistance aux utilisateurs'),
('00000000-0000-0000-0006-000000000006', 'solidarity_agent', 'Agent de Solidarité', 'Instruction des demandes de bourses et d''accès gratuit'),
('00000000-0000-0000-0006-000000000007', 'admin', 'Administrateur Système', 'Supervision globale de la plateforme')
ON CONFLICT (code) DO NOTHING;

-- 7. SOURCE OFFICIELLE DU CALENDRIER SCOLAIRE DU TOGO
INSERT INTO calendar_sources (id, title, source_url, authority, checked_at, verification_status)
VALUES (
    '00000000-0000-0000-0007-000000000001',
    'Découpage officiel de l''année scolaire et calendrier des examens nationaux 2026-2027',
    'https://education.gouv.tg/calendrier-scolaire-2026-2027',
    'Ministère des Enseignements Primaire, Secondaire et Technique (MEPST Togo)',
    '2026-10-01',
    'verified'
) ON CONFLICT DO NOTHING;

-- 8. SESSIONS D'EXAMENS NATIONAUX DU TOGO
INSERT INTO exam_sessions (id, country_id, academic_year_id, education_level_id, code, title, source_id, written_exam_starts_on, written_exam_ends_on, results_proclaimed_on)
VALUES
(
    '00000000-0000-0000-0008-000000000001',
    '00000000-0000-0000-0001-000000000001',
    '00000000-0000-0000-0002-000000000001',
    '00000000-0000-0000-0003-000000000003',
    'BEPC',
    'Brevet d''Études du Premier Cycle (BEPC 2027)',
    '00000000-0000-0000-0007-000000000001',
    '2027-06-08',
    '2027-06-11',
    '2027-06-25'
),
(
    '00000000-0000-0000-0008-000000000002',
    '00000000-0000-0000-0001-000000000001',
    '00000000-0000-0000-0002-000000000001',
    '00000000-0000-0000-0003-000000000001',
    'BAC1',
    'Baccalauréat Première Partie (BAC 1 2027)',
    '00000000-0000-0000-0007-000000000001',
    '2027-05-25',
    '2027-05-29',
    '2027-06-15'
),
(
    '00000000-0000-0000-0008-000000000003',
    '00000000-0000-0000-0001-000000000001',
    '00000000-0000-0000-0002-000000000001',
    '00000000-0000-0000-0003-000000000000',
    'BAC2',
    'Baccalauréat Deuxième Partie (BAC 2 2027)',
    '00000000-0000-0000-0007-000000000001',
    '2027-06-21',
    '2027-06-26',
    '2027-07-10'
) ON CONFLICT DO NOTHING;
