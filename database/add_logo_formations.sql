-- Ajout du champ logo pour les formations
-- Exécuter ce script pour ajouter la colonne logo à la table formations

ALTER TABLE formations
ADD COLUMN logo VARCHAR(255) NULL
AFTER school;

-- Optionnel: Mettre à jour les formations existantes avec des logos de démonstration
-- UPDATE formations SET logo = 'assets/images/logos/universite-paris.png' WHERE school = 'Université Paris-Saclay';
-- UPDATE formations SET logo = 'assets/images/logos/iut-orsay.png' WHERE school = 'IUT d\'Orsay';
-- UPDATE formations SET logo = 'assets/images/logos/lycee-technique.png' WHERE school = 'Lycée Technique';
