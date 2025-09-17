# 📧 Configuration Email - Portfolio

Ce guide vous explique comment configurer l'envoi d'emails pour votre formulaire de contact.

## 🚀 Configuration Rapide

### 1. Modifier la configuration de base

Ouvrez le fichier `config/email.php` et modifiez :

```php
// Remplacez par votre vraie adresse email
define('ADMIN_EMAIL', 'votre-email@exemple.com');
```

### 2. Tester la configuration

1. Connectez-vous à l'administration : `/admin/`
2. Allez dans **Paramètres**
3. Cliquez sur **"Tester l'envoi d'email"**
4. Envoyez un email de test

## 🔧 Configuration Avancée (SMTP)

Si l'envoi simple ne fonctionne pas, configurez SMTP :

### Pour Gmail :

```php
define('USE_SMTP', true);
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'votre-email@gmail.com');
define('SMTP_PASSWORD', 'votre-mot-de-passe-app');
define('SMTP_ENCRYPTION', 'tls');
```

**⚠️ Important pour Gmail :**
- Activez l'authentification à 2 facteurs
- Générez un "mot de passe d'application"
- Utilisez ce mot de passe, pas votre mot de passe Gmail

### Pour autres fournisseurs :

**OVH :**
```php
define('SMTP_HOST', 'ssl0.ovh.net');
define('SMTP_PORT', 587);
```

**Ionos (1&1) :**
```php
define('SMTP_HOST', 'smtp.ionos.fr');
define('SMTP_PORT', 587);
```

**Orange :**
```php
define('SMTP_HOST', 'smtp.orange.fr');
define('SMTP_PORT', 587);
```

## 🧪 Test en Local (MAMP)

### Option 1 : MailHog (Recommandé)
```bash
# Installer MailHog
brew install mailhog

# Lancer MailHog
mailhog

# Interface web : http://localhost:8025
```

Puis configurez PHP pour utiliser MailHog :
```ini
; Dans php.ini
sendmail_path = "/usr/local/bin/MailHog sendmail test@test"
```

### Option 2 : Mailtrap
1. Créez un compte sur [mailtrap.io](https://mailtrap.io)
2. Récupérez vos paramètres SMTP
3. Configurez dans `config/email.php`

## 🛠️ Dépannage

### ❌ "L'email n'est pas envoyé"

**Vérifications :**
1. La fonction `mail()` est-elle activée sur votre serveur ?
2. Votre hébergeur bloque-t-il l'envoi d'emails ?
3. L'email arrive-t-il dans les spams ?

**Solutions :**
- Utilisez SMTP au lieu de `mail()`
- Contactez votre hébergeur
- Configurez SPF/DKIM sur votre domaine

### ❌ "Erreur SMTP"

**Vérifications :**
1. Vos identifiants SMTP sont-ils corrects ?
2. Le port est-il ouvert ?
3. L'encryption est-elle correcte (TLS/SSL) ?

### ❌ "En local ça ne marche pas"

**Normal !** En local, l'envoi d'email est souvent bloqué.

**Solutions :**
- Utilisez MailHog ou Mailtrap
- Testez directement sur votre serveur de production

## 📋 Fonctionnalités

### ✅ Ce qui fonctionne :
- **Sauvegarde en base** : Tous les messages sont enregistrés
- **Email de notification** : Vous recevez une copie par email
- **Design moderne** : Email HTML avec le style de votre portfolio
- **Informations complètes** : Nom, email, IP, date
- **Sécurité** : Protection CSRF, validation des données

### 🎨 Template Email :
- Design cohérent avec votre portfolio
- Informations du visiteur
- Message formaté
- Instructions pour répondre

## 🔐 Sécurité

### Protection incluse :
- ✅ **Token CSRF** contre les attaques
- ✅ **Validation des emails**
- ✅ **Échappement HTML** des données
- ✅ **Logging des erreurs**

### Recommandations :
- Utilisez HTTPS en production
- Configurez un rate limiting
- Surveillez les logs d'erreur

## 🚀 Mise en Production

### Avant de mettre en ligne :
1. **Testez** l'envoi d'email sur votre serveur
2. **Configurez** votre vraie adresse email
3. **Vérifiez** que les emails n'arrivent pas en spam
4. **Activez** les logs d'erreur

### Hébergeurs populaires :

**OVH :**
- SMTP généralement disponible
- Utilisez vos identifiants email OVH

**Ionos :**
- SMTP inclus avec les hébergements
- Configuration automatique possible

**Hostinger :**
- SMTP disponible
- Vérifiez les limites d'envoi

## 📞 Support

Si vous avez des problèmes :

1. **Testez** avec la page de test intégrée
2. **Vérifiez** les logs d'erreur PHP
3. **Contactez** votre hébergeur si nécessaire
4. **Utilisez** un service SMTP externe (SendGrid, Mailgun)

---

💡 **Astuce :** Commencez toujours par la configuration simple, puis passez à SMTP si nécessaire !
