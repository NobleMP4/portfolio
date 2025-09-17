<?php
/**
 * Classe pour l'envoi d'emails
 * Supporte l'envoi via mail() PHP ou SMTP
 */
class EmailSender {
    
    private $use_smtp;
    private $smtp_config;
    
    public function __construct() {
        $this->use_smtp = defined('USE_SMTP') ? USE_SMTP : false;
        $this->smtp_config = [
            'host' => defined('SMTP_HOST') ? SMTP_HOST : '',
            'port' => defined('SMTP_PORT') ? SMTP_PORT : 587,
            'username' => defined('SMTP_USERNAME') ? SMTP_USERNAME : '',
            'password' => defined('SMTP_PASSWORD') ? SMTP_PASSWORD : '',
            'encryption' => defined('SMTP_ENCRYPTION') ? SMTP_ENCRYPTION : 'tls'
        ];
    }
    
    /**
     * Envoyer un email de contact
     */
    public function sendContactEmail($to_email, $subject, $message, $from_name, $from_email) {
        if ($this->use_smtp) {
            return $this->sendViaSMTP($to_email, $subject, $message, $from_name, $from_email);
        } else {
            return $this->sendViaPHPMail($to_email, $subject, $message, $from_name, $from_email);
        }
    }
    
    /**
     * Envoi via mail() PHP (méthode simple)
     */
    private function sendViaPHPMail($to_email, $subject, $message, $from_name, $from_email) {
        $headers = array(
            'MIME-Version' => '1.0',
            'Content-type' => 'text/html; charset=UTF-8',
            'From' => $from_name . ' <' . $from_email . '>',
            'Reply-To' => $from_email,
            'X-Mailer' => 'PHP/' . phpversion()
        );
        
        $html_message = $this->buildHTMLMessage($subject, $message, $from_name, $from_email);
        
        // Conversion des headers en format string
        $header_string = '';
        foreach ($headers as $key => $value) {
            $header_string .= $key . ': ' . $value . "\r\n";
        }
        
        return mail($to_email, $subject, $html_message, $header_string);
    }
    
    /**
     * Envoi via SMTP (plus fiable)
     */
    private function sendViaSMTP($to_email, $subject, $message, $from_name, $from_email) {
        // Cette méthode nécessiterait une bibliothèque comme PHPMailer
        // Pour l'instant, on utilise la méthode PHP mail() comme fallback
        return $this->sendViaPHPMail($to_email, $subject, $message, $from_name, $from_email);
    }
    
    /**
     * Construire le message HTML
     */
    private function buildHTMLMessage($subject, $message, $from_name, $from_email) {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>' . htmlspecialchars($subject) . '</title>
            <style>
                body { 
                    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; 
                    line-height: 1.6; 
                    color: #333; 
                    margin: 0; 
                    padding: 0; 
                }
                .container { 
                    max-width: 600px; 
                    margin: 0 auto; 
                    background: #ffffff; 
                }
                .header { 
                    background: linear-gradient(135deg, #0d1117, #161b22); 
                    color: #f0f6fc; 
                    padding: 30px 20px; 
                    text-align: center; 
                }
                .header h1 { 
                    margin: 0; 
                    font-family: "JetBrains Mono", monospace; 
                    font-size: 24px; 
                    font-weight: 600; 
                }
                .content { 
                    padding: 30px 20px; 
                    background: #ffffff; 
                }
                .info-section {
                    background: #f8f9fa;
                    padding: 20px;
                    border-radius: 8px;
                    margin-bottom: 25px;
                    border-left: 4px solid #58a6ff;
                }
                .info-row { 
                    margin-bottom: 12px; 
                    display: flex;
                    align-items: center;
                }
                .label { 
                    font-weight: 600; 
                    color: #58a6ff; 
                    min-width: 80px;
                    font-size: 14px;
                }
                .value {
                    color: #24292f;
                    font-size: 14px;
                }
                .message-section {
                    margin-top: 25px;
                }
                .message-label {
                    font-weight: 600;
                    color: #24292f;
                    margin-bottom: 15px;
                    font-size: 16px;
                }
                .message-content { 
                    background: #ffffff; 
                    padding: 20px; 
                    border-radius: 8px; 
                    border: 1px solid #d0d7de;
                    border-left: 4px solid #58a6ff;
                    font-size: 14px;
                    line-height: 1.6;
                    color: #24292f;
                }
                .footer {
                    background: #f6f8fa;
                    padding: 20px;
                    text-align: center;
                    font-size: 12px;
                    color: #656d76;
                    border-top: 1px solid #d0d7de;
                }
                .badge {
                    background: #58a6ff;
                    color: white;
                    padding: 4px 8px;
                    border-radius: 12px;
                    font-size: 11px;
                    font-weight: 500;
                    text-transform: uppercase;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>// Nouveau Message de Contact</h1>
                    <div style="margin-top: 10px;">
                        <span class="badge">Portfolio</span>
                    </div>
                </div>
                <div class="content">
                    <div class="info-section">
                        <div class="info-row">
                            <span class="label">👤 Nom :</span>
                            <span class="value">' . htmlspecialchars($from_name) . '</span>
                        </div>
                        <div class="info-row">
                            <span class="label">📧 Email :</span>
                            <span class="value">' . htmlspecialchars($from_email) . '</span>
                        </div>
                        <div class="info-row">
                            <span class="label">📅 Date :</span>
                            <span class="value">' . date('d/m/Y à H:i') . '</span>
                        </div>
                        <div class="info-row">
                            <span class="label">🌐 IP :</span>
                            <span class="value">' . ($_SERVER['REMOTE_ADDR'] ?? 'Non disponible') . '</span>
                        </div>
                    </div>
                    
                    <div class="message-section">
                        <div class="message-label">💬 Message :</div>
                        <div class="message-content">
                            ' . nl2br(htmlspecialchars($message)) . '
                        </div>
                    </div>
                </div>
                <div class="footer">
                    <p>Ce message a été envoyé depuis votre portfolio via le formulaire de contact.</p>
                    <p>Pour répondre, utilisez directement l\'adresse : <strong>' . htmlspecialchars($from_email) . '</strong></p>
                </div>
            </div>
        </body>
        </html>';
    }
    
    /**
     * Tester la configuration email
     */
    public function testConfiguration() {
        $test_result = [
            'php_mail_available' => function_exists('mail'),
            'smtp_configured' => $this->use_smtp && !empty($this->smtp_config['host']),
            'current_method' => $this->use_smtp ? 'SMTP' : 'PHP Mail',
            'recommendations' => []
        ];
        
        if (!$test_result['php_mail_available']) {
            $test_result['recommendations'][] = 'La fonction mail() PHP n\'est pas disponible sur ce serveur.';
        }
        
        if ($this->use_smtp && empty($this->smtp_config['host'])) {
            $test_result['recommendations'][] = 'Configuration SMTP incomplète. Vérifiez vos paramètres.';
        }
        
        return $test_result;
    }
}
?>
