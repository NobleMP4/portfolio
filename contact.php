<?php
session_start();
require_once 'config/database.php';
require_once 'config/email.php';
require_once 'includes/functions.php';

$success_message = '';
$error_message = '';

// Traitement du formulaire
if ($_POST && isset($_POST['submit_contact'])) {
    // Vérification du token CSRF
    if (!verify_csrf_token($_POST['csrf_token'])) {
        $error_message = 'Token de sécurité invalide. Veuillez réessayer.';
    } else {
        // Validation des données
        $name = sanitize_input($_POST['name']);
        $email = sanitize_input($_POST['email']);
        $subject = sanitize_input($_POST['subject']);
        $message = sanitize_input($_POST['message']);
        
        $errors = [];
        
        if (empty($name)) {
            $errors[] = 'Le nom est requis.';
        }
        
        if (empty($email) || !validate_email($email)) {
            $errors[] = 'Une adresse email valide est requise.';
        }
        
        if (empty($subject)) {
            $errors[] = 'Le sujet est requis.';
        }
        
        if (empty($message)) {
            $errors[] = 'Le message est requis.';
        }
        
        if (empty($errors)) {
            try {
                // Enregistrer le message en base
                $sql = "INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$name, $email, $subject, $message]);
                
                // Envoyer l'email de notification
                $email_subject = "[" . SITE_NAME . "] Nouveau message de contact : " . $subject;
                
                $email_sent = sendContactEmail(ADMIN_EMAIL, $email_subject, $message, $name, $email);
                
                if ($email_sent) {
                    $success_message = 'Votre message a été envoyé avec succès ! Je vous répondrai dans les plus brefs délais.';
                } else {
                    $success_message = 'Votre message a été enregistré. Je vous répondrai dans les plus brefs délais.';
                    // Log l'erreur d'envoi d'email (optionnel)
                    error_log("Erreur d'envoi d'email de contact pour : " . $email);
                }
                
                // Réinitialiser les champs
                $name = $email = $subject = $message = '';
                
            } catch (Exception $e) {
                $error_message = 'Une erreur est survenue lors de l\'envoi du message. Veuillez réessayer.';
                // Log l'erreur (optionnel)
                error_log("Erreur formulaire de contact : " . $e->getMessage());
            }
        } else {
            $error_message = implode('<br>', $errors);
        }
    }
}

// Générer un nouveau token CSRF
$csrf_token = generate_csrf_token();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Portfolio</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    
    <!-- Terminal Navigation -->
    <div class="terminal-nav">
        <div class="terminal-header">
            <div class="terminal-dots">
                <div class="dot red"></div>
                <div class="dot yellow"></div>
                <div class="dot green"></div>
            </div>
            <div class="terminal-title">Navigation</div>
        </div>
        <div class="terminal-body">
            <a href="index.php" class="nav-command" data-tooltip="Retour à la page d'accueil">Accueil</a>
            <a href="about.php" class="nav-command" data-tooltip="Découvrir mon parcours">À propos</a>
            <a href="portfolio.php" class="nav-command" data-tooltip="Voir mes réalisations">Mes projets</a>
            <a href="experiences.php" class="nav-command" data-tooltip="Mon expérience professionnelle">Expériences</a>
            <a href="formations.php" class="nav-command" data-tooltip="Mon parcours de formation">Formations</a>
            <a href="contact.php" class="nav-command active" data-tooltip="Page actuelle">Contact</a>
        </div>
    </div>

    <!-- Main Container -->
    <div class="main-container">
        <!-- Contact Header -->
        <section style="text-align: center; padding: 4rem 0 2rem; max-width: 1000px; margin: 0 auto;">
            <h1 style="font-family: 'JetBrains Mono', monospace; color: var(--text-primary); margin-bottom: 1rem; font-size: 2.5rem;">
                <span style="color: var(--text-muted);">// </span>Me Contacter
            </h1>
            <p style="color: var(--text-secondary); font-size: 1.1rem; max-width: 600px; margin: 0 auto; line-height: 1.6;">
                N'hésitez pas à me contacter !<br>
            </p>
        </section>

        <!-- Contact Section -->
        <section style="padding: 2rem 0; max-width: 1200px; margin: 0 auto;">
            <div style="display: grid; grid-template-columns: 400px 1fr; gap: 3rem; align-items: start;" class="contact-layout">
                
                <!-- Contact Info Card -->
                <div style="background: var(--bg-secondary); border: 1px solid var(--border); border-radius: 12px; padding: 2rem;">
                    <h2 style="font-family: 'JetBrains Mono', monospace; color: var(--text-primary); margin-bottom: 2rem; font-size: 1.3rem;">
                        <span style="color: var(--text-muted);">// </span>Informations
                    </h2>
                    
                    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: var(--bg-primary); border: 1px solid var(--border); border-radius: 8px;">
                            <div style="width: 40px; height: 40px; background: var(--accent-blue); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--bg-primary);">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <div style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 0.25rem;">Email</div>
                                <div style="color: var(--text-primary); font-family: 'JetBrains Mono', monospace; font-size: 0.9rem;">votre-email@domain.com</div>
                            </div>
                        </div>
                        
                        <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: var(--bg-primary); border: 1px solid var(--border); border-radius: 8px;">
                            <div style="width: 40px; height: 40px; background: var(--accent-green); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--bg-primary);">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div>
                                <div style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 0.25rem;">Téléphone</div>
                                <div style="color: var(--text-primary); font-family: 'JetBrains Mono', monospace; font-size: 0.9rem;">+33 1 23 45 67 89</div>
                            </div>
                        </div>
                        
                        <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: var(--bg-primary); border: 1px solid var(--border); border-radius: 8px;">
                            <div style="width: 40px; height: 40px; background: var(--accent-purple); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--bg-primary);">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <div style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 0.25rem;">Localisation</div>
                                <div style="color: var(--text-primary); font-family: 'JetBrains Mono', monospace; font-size: 0.9rem;">Paris, France</div>
                            </div>
                        </div>
                        
                        <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: var(--bg-primary); border: 1px solid var(--border); border-radius: 8px;">
                            <div style="width: 40px; height: 40px; background: var(--warning); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--bg-primary);">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <div style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 0.25rem;">Disponibilité</div>
                                <div style="color: var(--text-primary); font-family: 'JetBrains Mono', monospace; font-size: 0.9rem;">Lun - Ven : 9h - 18h</div>
                            </div>
                        </div>
                    </div>
                    
                    <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border);">
                        <h3 style="font-family: 'JetBrains Mono', monospace; color: var(--text-primary); margin-bottom: 1rem; font-size: 1rem;">
                            <span style="color: var(--text-muted);">// </span>Réseaux Sociaux
                        </h3>
                        <div style="display: flex; gap: 1rem;">
                            <a href="#" style="width: 40px; height: 40px; background: var(--bg-primary); border: 1px solid var(--border); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--text-secondary); text-decoration: none; transition: all 0.3s ease;" onmouseover="this.style.borderColor='var(--accent-blue)'; this.style.color='var(--accent-blue)'" onmouseout="this.style.borderColor='var(--border)'; this.style.color='var(--text-secondary)'">
                                <i class="fab fa-linkedin"></i>
                            </a>
                            <a href="#" style="width: 40px; height: 40px; background: var(--bg-primary); border: 1px solid var(--border); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--text-secondary); text-decoration: none; transition: all 0.3s ease;" onmouseover="this.style.borderColor='var(--accent-blue)'; this.style.color='var(--accent-blue)'" onmouseout="this.style.borderColor='var(--border)'; this.style.color='var(--text-secondary)'">
                                <i class="fab fa-github"></i>
                            </a>
                            <a href="#" style="width: 40px; height: 40px; background: var(--bg-primary); border: 1px solid var(--border); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--text-secondary); text-decoration: none; transition: all 0.3s ease;" onmouseover="this.style.borderColor='var(--accent-blue)'; this.style.color='var(--accent-blue)'" onmouseout="this.style.borderColor='var(--border)'; this.style.color='var(--text-secondary)'">
                                <i class="fab fa-twitter"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Contact Form Card -->
                <div style="background: var(--bg-secondary); border: 1px solid var(--border); border-radius: 12px; padding: 2rem;">
                    <h2 style="font-family: 'JetBrains Mono', monospace; color: var(--text-primary); margin-bottom: 2rem; font-size: 1.3rem;">
                        <span style="color: var(--text-muted);">// </span>Envoyer un Message
                    </h2>
                    
                    <?php if ($success_message): ?>
                    <div style="background: rgba(35, 134, 54, 0.1); border: 1px solid var(--success); border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-check-circle" style="color: var(--success);"></i>
                        <span style="color: var(--success); font-size: 0.9rem;"><?php echo $success_message; ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($error_message): ?>
                    <div style="background: rgba(248, 81, 73, 0.1); border: 1px solid var(--error); border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-exclamation-circle" style="color: var(--error);"></i>
                        <span style="color: var(--error); font-size: 0.9rem;"><?php echo $error_message; ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <form method="POST" id="contactForm" novalidate>
                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;" class="form-grid">
                            <div>
                                <label for="name" style="display: block; margin-bottom: 0.5rem; color: var(--text-primary); font-family: 'JetBrains Mono', monospace; font-size: 0.9rem;">Nom complet *</label>
                                <input type="text" id="name" name="name" value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>" required style="width: 100%; padding: 0.875rem; border: 1px solid var(--border); border-radius: 6px; background: var(--bg-primary); color: var(--text-primary); font-family: 'JetBrains Mono', monospace; font-size: 0.9rem; transition: all 0.3s ease;" onfocus="this.style.borderColor='var(--accent-blue)'; this.style.boxShadow='0 0 0 3px rgba(88, 166, 255, 0.1)'" onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'">
                                <span class="error-message" id="name-error" style="color: var(--error); font-size: 0.8rem; margin-top: 0.25rem; display: block;"></span>
                            </div>
                            <div>
                                <label for="email" style="display: block; margin-bottom: 0.5rem; color: var(--text-primary); font-family: 'JetBrains Mono', monospace; font-size: 0.9rem;">Adresse email *</label>
                                <input type="email" id="email" name="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required style="width: 100%; padding: 0.875rem; border: 1px solid var(--border); border-radius: 6px; background: var(--bg-primary); color: var(--text-primary); font-family: 'JetBrains Mono', monospace; font-size: 0.9rem; transition: all 0.3s ease;" onfocus="this.style.borderColor='var(--accent-blue)'; this.style.boxShadow='0 0 0 3px rgba(88, 166, 255, 0.1)'" onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'">
                                <span class="error-message" id="email-error" style="color: var(--error); font-size: 0.8rem; margin-top: 0.25rem; display: block;"></span>
                            </div>
                        </div>
                        
                        <div style="margin-bottom: 1.5rem;">
                            <label for="subject" style="display: block; margin-bottom: 0.5rem; color: var(--text-primary); font-family: 'JetBrains Mono', monospace; font-size: 0.9rem;">Sujet *</label>
                            <input type="text" id="subject" name="subject" value="<?php echo isset($subject) ? htmlspecialchars($subject) : ''; ?>" required style="width: 100%; padding: 0.875rem; border: 1px solid var(--border); border-radius: 6px; background: var(--bg-primary); color: var(--text-primary); font-family: 'JetBrains Mono', monospace; font-size: 0.9rem; transition: all 0.3s ease;" onfocus="this.style.borderColor='var(--accent-blue)'; this.style.boxShadow='0 0 0 3px rgba(88, 166, 255, 0.1)'" onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'">
                            <span class="error-message" id="subject-error" style="color: var(--error); font-size: 0.8rem; margin-top: 0.25rem; display: block;"></span>
                        </div>
                        
                        <div style="margin-bottom: 2rem;">
                            <label for="message" style="display: block; margin-bottom: 0.5rem; color: var(--text-primary); font-family: 'JetBrains Mono', monospace; font-size: 0.9rem;">Message *</label>
                            <textarea id="message" name="message" rows="6" required style="width: 100%; padding: 0.875rem; border: 1px solid var(--border); border-radius: 6px; background: var(--bg-primary); color: var(--text-primary); font-family: 'JetBrains Mono', monospace; font-size: 0.9rem; transition: all 0.3s ease; resize: vertical;" onfocus="this.style.borderColor='var(--accent-blue)'; this.style.boxShadow='0 0 0 3px rgba(88, 166, 255, 0.1)'" onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'"><?php echo isset($message) ? htmlspecialchars($message) : ''; ?></textarea>
                            <span class="error-message" id="message-error" style="color: var(--error); font-size: 0.8rem; margin-top: 0.25rem; display: block;"></span>
                        </div>
                        
                        <button type="submit" name="submit_contact" style="width: 100%; padding: 1rem 1.5rem; background: var(--accent-blue); border: 1px solid var(--accent-blue); border-radius: 6px; color: var(--bg-primary); font-family: 'JetBrains Mono', monospace; font-size: 1rem; font-weight: 500; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; gap: 0.5rem;" onmouseover="this.style.background='var(--accent-purple)'; this.style.borderColor='var(--accent-purple)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='var(--accent-blue)'; this.style.borderColor='var(--accent-blue)'; this.style.transform='translateY(0)'">
                            <i class="fas fa-paper-plane"></i>
                            Envoyer le message
                        </button>
                    </form>
                </div>
            </div>
        </section>

    </div>

    <script src="assets/js/main.js"></script>
    <script src="assets/js/contact.js"></script>
</body>
</html>
