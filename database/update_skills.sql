-- Script de mise à jour des compétences
-- À exécuter après avoir importé portfolio.sql

-- Supprimer les anciennes compétences
DELETE FROM skills;

-- Réinitialiser l'auto-increment
ALTER TABLE skills AUTO_INCREMENT = 1;

-- Insérer les nouvelles compétences organisées
INSERT INTO skills (name, level, category, icon, featured, sort_order) VALUES
-- Technologies
('HTML5', 95, 'Technologies', 'html5', TRUE, 1),
('CSS3', 90, 'Technologies', 'css3-alt', TRUE, 2),
('JavaScript', 85, 'Technologies', 'js-square', TRUE, 3),
('PHP', 80, 'Technologies', 'php', TRUE, 4),
('MySQL', 75, 'Technologies', 'database', TRUE, 5),
('React', 70, 'Technologies', 'react', TRUE, 6),
('Node.js', 65, 'Technologies', 'node-js', FALSE, 7),

-- Framework et CMS
('WordPress', 85, 'Framework et CMS', 'wordpress', TRUE, 8),
('Bootstrap', 80, 'Framework et CMS', 'bootstrap', FALSE, 9),
('Laravel', 70, 'Framework et CMS', 'laravel', FALSE, 10),
('Vue.js', 65, 'Framework et CMS', 'vuejs', FALSE, 11),

-- Langues (niveaux ajustés pour les nouveaux labels)
('Français', 100, 'Langues', 'flag', TRUE, 12),
('Anglais', 75, 'Langues', 'flag', TRUE, 13),
('Espagnol', 35, 'Langues', 'flag', FALSE, 14),

-- Outils
('Git', 85, 'Outils', 'git-alt', FALSE, 15),
('GitHub', 90, 'Outils', 'github', FALSE, 16),
('ChatGPT', 90, 'Outils', 'robot', FALSE, 17),
('Proxmox', 70, 'Outils', 'server', FALSE, 18),
('Docker', 65, 'Outils', 'docker', FALSE, 19),
('Photoshop', 60, 'Outils', 'adobe', FALSE, 20),

-- Loisirs
('Sport Auto', 95, 'Loisirs', 'car', FALSE, 21),
('Jeux Vidéo', 90, 'Loisirs', 'gamepad', FALSE, 22),
('Simracing', 85, 'Loisirs', 'flag-checkered', FALSE, 23),
('Technologie', 80, 'Loisirs', 'microchip', FALSE, 24);
