-- Base de données Portfolio
-- Compatible MySQL/MariaDB pour MAMP

CREATE DATABASE IF NOT EXISTS portfolio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE portfolio;

-- Table des utilisateurs administrateurs
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table des projets
CREATE TABLE IF NOT EXISTS projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(255),
    link VARCHAR(255),
    technologies VARCHAR(500),
    featured BOOLEAN DEFAULT FALSE,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table des expériences professionnelles
CREATE TABLE IF NOT EXISTS experiences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    company VARCHAR(200) NOT NULL,
    location VARCHAR(200),
    start_date DATE NOT NULL,
    end_date DATE NULL,
    current_position BOOLEAN DEFAULT FALSE,
    description TEXT NOT NULL,
    technologies VARCHAR(500),
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table des formations
CREATE TABLE IF NOT EXISTS formations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    school VARCHAR(200) NOT NULL,
    location VARCHAR(200),
    start_date DATE NOT NULL,
    end_date DATE NULL,
    current_formation BOOLEAN DEFAULT FALSE,
    description TEXT,
    diploma VARCHAR(200),
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table des compétences
CREATE TABLE IF NOT EXISTS skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    level INT NOT NULL CHECK (level >= 0 AND level <= 100),
    category VARCHAR(100) DEFAULT 'Technique',
    icon VARCHAR(100),
    featured BOOLEAN DEFAULT FALSE,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table des messages de contact
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    read_status BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertion des données de test

-- Utilisateur administrateur par défaut (mot de passe: admin123)
INSERT INTO users (username, password, email) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@portfolio.com');

-- Compétences organisées selon les nouvelles catégories
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

-- Langues (pas d'icônes FontAwesome)
('Français', 100, 'Langues', 'flag', TRUE, 12),
('Anglais', 80, 'Langues', 'flag', TRUE, 13),
('Espagnol', 40, 'Langues', 'flag', FALSE, 14),

-- Outils
('Git', 85, 'Outils', 'git-alt', FALSE, 15),
('ChatGPT', 90, 'Outils', 'robot', FALSE, 16),
('Proxmox', 70, 'Outils', 'server', FALSE, 17),
('Docker', 65, 'Outils', 'docker', FALSE, 18),
('Photoshop', 60, 'Outils', 'adobe', FALSE, 19),

-- Loisirs
('Sport Auto', 95, 'Loisirs', 'car', FALSE, 20),
('Jeux Vidéo', 90, 'Loisirs', 'gamepad', FALSE, 21),
('Simracing', 85, 'Loisirs', 'flag-checkered', FALSE, 22),
('Technologie', 80, 'Loisirs', 'microchip', FALSE, 23);

-- Projets de démonstration
INSERT INTO projects (title, description, image, link, technologies, featured, sort_order) VALUES
('Site E-commerce', 'Développement d\'un site e-commerce complet avec panier, paiement en ligne et gestion des commandes. Interface moderne et responsive.', 'assets/images/projects/ecommerce.jpg', 'https://github.com/username/ecommerce', 'PHP, MySQL, JavaScript, Bootstrap', TRUE, 1),
('Application Web de Gestion', 'Application de gestion d\'entreprise permettant de gérer les clients, factures et projets. Dashboard interactif et rapports détaillés.', 'assets/images/projects/gestion.jpg', 'https://github.com/username/gestion-app', 'PHP, MySQL, Chart.js, jQuery', TRUE, 2),
('Portfolio Photographe', 'Site vitrine pour un photographe professionnel avec galerie interactive, système de réservation et blog intégré.', 'assets/images/projects/photo.jpg', 'https://github.com/username/photo-portfolio', 'WordPress, PHP, JavaScript', TRUE, 3),
('API REST', 'Développement d\'une API REST complète pour une application mobile avec authentification JWT et documentation Swagger.', 'assets/images/projects/api.jpg', 'https://github.com/username/rest-api', 'PHP, MySQL, JWT, Swagger', FALSE, 4);

-- Expériences professionnelles de démonstration
INSERT INTO experiences (title, company, location, start_date, end_date, current_position, description, technologies, sort_order) VALUES
('Développeur Web Full-Stack', 'TechCorp Solutions', 'Paris, France', '2023-01-15', NULL, TRUE, 'Développement d\'applications web complexes pour des clients variés. Responsable de l\'architecture technique et de la formation des développeurs junior. Gestion de projets de A à Z.', 'PHP, JavaScript, MySQL, React, Node.js', 1),
('Développeur Front-End', 'WebAgency Pro', 'Lyon, France', '2021-06-01', '2022-12-31', FALSE, 'Création d\'interfaces utilisateur modernes et responsives. Collaboration étroite avec les designers UX/UI. Optimisation des performances et accessibilité.', 'HTML5, CSS3, JavaScript, Vue.js, Sass', 2),
('Développeur Junior', 'StartupTech', 'Remote', '2020-09-01', '2021-05-31', FALSE, 'Premier poste en tant que développeur. Maintenance et évolution d\'applications existantes. Apprentissage des bonnes pratiques de développement.', 'PHP, MySQL, jQuery, Bootstrap', 3);

-- Formations de démonstration
INSERT INTO formations (title, school, location, start_date, end_date, current_formation, description, diploma, sort_order) VALUES
('Master Informatique - Développement Web', 'Université de Technologie', 'Paris, France', '2018-09-01', '2020-06-30', FALSE, 'Formation spécialisée en développement web et mobile. Projets pratiques en équipe. Stage de 6 mois en entreprise.', 'Master 2', 1),
('Licence Informatique', 'Université Paris-Sud', 'Orsay, France', '2015-09-01', '2018-06-30', FALSE, 'Formation générale en informatique : algorithmique, programmation, bases de données, réseaux.', 'Licence', 2),
('Baccalauréat Scientifique', 'Lycée Victor Hugo', 'Paris, France', '2012-09-01', '2015-06-30', FALSE, 'Spécialité Mathématiques avec option Informatique et Sciences du Numérique.', 'Baccalauréat S', 3);

-- Index pour optimiser les performances
CREATE INDEX idx_projects_featured ON projects(featured);
CREATE INDEX idx_skills_featured ON skills(featured);
CREATE INDEX idx_experiences_current ON experiences(current_position);
CREATE INDEX idx_formations_current ON formations(current_formation);
CREATE INDEX idx_contact_read ON contact_messages(read_status);
CREATE INDEX idx_created_at ON contact_messages(created_at);
