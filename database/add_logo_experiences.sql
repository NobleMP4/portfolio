-- Ajout du champ logo pour les expériences
-- Compatible MySQL/MariaDB pour MAMP

USE portfolio;

-- Ajouter le champ logo à la table experiences
ALTER TABLE experiences 
ADD COLUMN logo VARCHAR(255) NULL 
AFTER company;

-- Ajouter un index pour optimiser les performances
CREATE INDEX idx_experiences_logo ON experiences(logo);

-- Mettre à jour les expériences existantes avec des logos d'exemple (optionnel)
-- Vous pouvez modifier ces URLs selon vos besoins
UPDATE experiences 
SET logo = 'assets/images/logos/techcorp.png' 
WHERE company = 'TechCorp Solutions';

UPDATE experiences 
SET logo = 'assets/images/logos/webagency.png' 
WHERE company = 'WebAgency Pro';

UPDATE experiences 
SET logo = 'assets/images/logos/startuptech.png' 
WHERE company = 'StartupTech';

-- Vérifier la structure de la table
DESCRIBE experiences;
