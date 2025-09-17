<?php
/**
 * Configuration Email
 * Modifiez ces paramètres selon votre configuration
 */

// IMPORTANT : Remplacez par votre vraie adresse email
define('ADMIN_EMAIL', 'derenneesteban@gmail.com');

// Nom qui apparaîtra comme expéditeur du site
define('SITE_NAME', 'Portfolio - Contact');

// Configuration SMTP (optionnel - pour un envoi plus fiable)
// Si vous voulez utiliser SMTP au lieu de mail() PHP
define('USE_SMTP', true);
define('SMTP_HOST', 'smtp.hostinger.com');
define('SMTP_PORT', 465);
define('SMTP_USERNAME', 'contact@edperso.fr');
define('SMTP_PASSWORD', 'Pipefeu2.');
define('SMTP_ENCRYPTION', 'SSL');

/**
 * Instructions de configuration :
 * 
 * 1. CONFIGURATION BASIQUE (recommandée) :
 *    - Changez ADMIN_EMAIL par votre vraie adresse
 *    - Laissez USE_SMTP à false
 *    - Vérifiez que votre serveur peut envoyer des emails
 * 
 * 2. CONFIGURATION SMTP (plus fiable) :
 *    - Mettez USE_SMTP à true
 *    - Configurez les paramètres SMTP de votre fournisseur
 *    - Pour Gmail : utilisez un mot de passe d'application
 * 
 * 3. TEST LOCAL (MAMP) :
 *    - Installez un serveur de test comme MailHog
 *    - Ou utilisez un service comme Mailtrap pour les tests
 * 
 * 4. PRODUCTION :
 *    - Utilisez les paramètres SMTP de votre hébergeur
 *    - Ou un service comme SendGrid, Mailgun, etc.
 */
?>
