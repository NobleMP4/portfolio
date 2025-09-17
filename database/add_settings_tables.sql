-- Ajout des tables pour les paramètres du site

-- Table pour les informations de profil
CREATE TABLE IF NOT EXISTS `profile` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `title` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `phone` varchar(50) DEFAULT NULL,
    `location` varchar(255) DEFAULT NULL,
    `bio` text DEFAULT NULL,
    `linkedin` varchar(255) DEFAULT NULL,
    `github` varchar(255) DEFAULT NULL,
    `website` varchar(255) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table pour les paramètres SEO
CREATE TABLE IF NOT EXISTS `seo_settings` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `site_title` varchar(255) DEFAULT NULL,
    `site_description` text DEFAULT NULL,
    `site_keywords` text DEFAULT NULL,
    `google_analytics` varchar(100) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertion des données par défaut pour le profil
INSERT INTO `profile` (`name`, `title`, `email`, `phone`, `location`, `bio`, `linkedin`, `github`, `website`) VALUES
('Votre Nom', 'Développeur Web Full-Stack', 'votre.email@exemple.com', '', 'Votre Ville, France', 
'Développeur web passionné avec une expertise en PHP, JavaScript et MySQL. Spécialisé dans la création de sites web modernes et d\'applications web performantes.', 
'', '', '');

-- Insertion des données par défaut pour le SEO
INSERT INTO `seo_settings` (`site_title`, `site_description`, `site_keywords`, `google_analytics`) VALUES
('Portfolio - Développeur Web', 
'Portfolio professionnel d\'un développeur web full-stack spécialisé en PHP, JavaScript, MySQL et technologies modernes.', 
'développeur web, php, javascript, mysql, portfolio, full-stack, création site web', 
'');
