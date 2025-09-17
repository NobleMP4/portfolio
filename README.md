# Portfolio Développeur Web

Un portfolio complet développé en HTML, CSS, JavaScript et PHP avec un système d'administration intégré.

## 🚀 Fonctionnalités

### Front-end
- **Page d'accueil** : Présentation, photo de profil, compétences principales
- **À propos** : Description détaillée, CV téléchargeable, valeurs
- **Portfolio** : Galerie de projets avec modal détaillée
- **Expériences** : Timeline des expériences professionnelles
- **Formations** : Parcours académique et certifications
- **Contact** : Formulaire de contact avec validation JavaScript et PHP

### Back-office (Administration)
- **Dashboard** : Statistiques et aperçu général
- **Gestion des projets** : CRUD complet avec upload d'images
- **Gestion des expériences** : Ajout/modification/suppression
- **Gestion des formations** : Gestion complète du parcours académique
- **Gestion des compétences** : Niveaux et catégories
- **Messages de contact** : Consultation et gestion
- **Système de connexion** : Authentification sécurisée

### Fonctionnalités techniques
- Design responsive (mobile-first)
- Animations CSS et JavaScript
- Upload et gestion d'images
- Sécurité : prepared statements, CSRF protection, hash des mots de passe
- Compatible MAMP et serveur de production

## 📋 Prérequis

- **MAMP** (ou XAMPP/WAMP) pour le développement local
- **PHP 7.4+**
- **MySQL 5.7+**
- **Apache** ou **Nginx**

## 🛠️ Installation

### 1. Installation locale avec MAMP

1. **Télécharger et installer MAMP** : [https://www.mamp.info/](https://www.mamp.info/)

2. **Cloner/Copier le projet** dans le dossier `htdocs` de MAMP :
   ```
   /Applications/MAMP/htdocs/portfolio-dev/
   ```

3. **Démarrer MAMP** et vérifier la configuration :
   - Port Apache : 80 (ou 8080)
   - Port MySQL : 3306 (ou 8889)
   - Version PHP : 7.4 ou supérieure

4. **Créer la base de données** :
   - Accéder à phpMyAdmin : `http://localhost/phpMyAdmin/` (ou `http://localhost:8080/phpMyAdmin/`)
   - Importer le fichier `database/portfolio.sql`
   - Ou exécuter le script SQL manuellement

5. **Configurer la base de données** :
   - Le fichier `config/database.php` est déjà configuré pour MAMP
   - Vérifier les paramètres si nécessaire :
     ```php
     $host = 'localhost';
     $port = '8889'; // ou 3306
     $username = 'root';
     $password = 'root';
     ```

6. **Accéder au site** :
   - Site public : `http://localhost/portfolio-dev/`
   - Administration : `http://localhost/portfolio-dev/admin/`
   - Identifiants par défaut : `admin` / `admin123`

### 2. Déploiement sur serveur

1. **Uploader les fichiers** sur votre serveur web

2. **Créer la base de données** :
   - Créer une nouvelle base MySQL
   - Importer le fichier `database/portfolio.sql`

3. **Configurer la base de données** :
   - Modifier `config/database.php` avec vos paramètres :
     ```php
     $host = 'localhost';
     $port = '3306';
     $dbname = 'votre_base';
     $username = 'votre_utilisateur';
     $password = 'votre_mot_de_passe';
     ```

4. **Configurer les permissions** :
   ```bash
   chmod 755 assets/images/uploads/
   chmod 755 assets/images/projects/
   chmod 755 assets/cv/
   ```

5. **Tester la connexion** et l'accès au site

## 📁 Structure du projet

```
portfolio-dev/
├── admin/                      # Interface d'administration
│   ├── index.php              # Dashboard
│   ├── login.php              # Connexion
│   ├── logout.php             # Déconnexion
│   ├── projects.php           # Gestion des projets
│   ├── experiences.php        # Gestion des expériences
│   ├── formations.php         # Gestion des formations
│   ├── skills.php             # Gestion des compétences
│   └── messages.php           # Messages de contact
├── assets/                    # Ressources statiques
│   ├── css/
│   │   ├── style.css          # Styles du site
│   │   └── admin.css          # Styles de l'admin
│   ├── js/
│   │   ├── main.js            # JavaScript principal
│   │   └── contact.js         # Validation du formulaire
│   ├── images/                # Images du site
│   └── cv/                    # CV téléchargeable
├── config/
│   └── database.php           # Configuration BDD
├── database/
│   └── portfolio.sql          # Structure et données
├── includes/
│   └── functions.php          # Fonctions utilitaires
├── index.php                  # Page d'accueil
├── about.php                  # Page à propos
├── portfolio.php              # Page portfolio
├── experiences.php            # Page expériences
├── formations.php             # Page formations
├── contact.php                # Page contact
└── README.md                  # Ce fichier
```

## 🔧 Configuration

### Personnalisation

1. **Modifier les informations personnelles** :
   - Éditer les pages PHP pour vos informations
   - Remplacer les images de profil dans `assets/images/`
   - Ajouter votre CV dans `assets/cv/`

2. **Personnaliser les couleurs** :
   - Modifier les variables CSS dans `assets/css/style.css` :
     ```css
     :root {
         --primary-color: #2563eb;
         --secondary-color: #64748b;
         /* ... */
     }
     ```

3. **Configurer l'envoi d'emails** :
   - Modifier la fonction `sendEmail()` dans `includes/functions.php`
   - Configurer SMTP si nécessaire

### Base de données

La base de données comprend les tables suivantes :
- `users` : Utilisateurs administrateurs
- `projects` : Projets du portfolio
- `experiences` : Expériences professionnelles
- `formations` : Formations et diplômes
- `skills` : Compétences techniques
- `contact_messages` : Messages du formulaire de contact

### Sécurité

- Mots de passe hashés avec `password_hash()`
- Protection CSRF sur tous les formulaires
- Prepared statements pour toutes les requêtes SQL
- Validation et sanitisation des données
- Upload d'images sécurisé

## 🎨 Personnalisation du design

### Couleurs principales
```css
--primary-color: #2563eb;    /* Bleu principal */
--secondary-color: #64748b;  /* Gris secondaire */
--accent-color: #f59e0b;     /* Orange accent */
```

### Typographie
- Police principale : Inter (Google Fonts)
- Tailles responsives
- Hiérarchie claire

### Animations
- Animations CSS au scroll
- Effets de hover
- Transitions fluides
- Barres de progression animées

## 📱 Responsive Design

Le site est entièrement responsive avec :
- Grid CSS et Flexbox
- Mobile-first approach
- Breakpoints optimisés
- Navigation mobile avec hamburger
- Images adaptatives

## 🔍 SEO et Performance

- HTML5 sémantique
- Meta tags optimisés
- Images optimisées
- Code CSS/JS minifiable
- Structure URL propre

## 🛡️ Sécurité

### Mesures implementées :
- Authentification sécurisée
- Protection CSRF
- Validation des données
- Upload de fichiers sécurisé
- Requêtes SQL préparées
- Sessions sécurisées

### Recommandations supplémentaires :
- Utiliser HTTPS en production
- Configurer des sauvegardes régulières
- Mettre à jour PHP et MySQL
- Utiliser un firewall web

## 🚀 Optimisations possibles

### Performance :
- Mise en cache des requêtes
- Compression des images
- CDN pour les ressources statiques
- Minification CSS/JS

### Fonctionnalités :
- Système de blog
- Multilingue
- API REST
- PWA (Progressive Web App)

## 📞 Support

Pour toute question ou problème :
1. Vérifier les logs d'erreur PHP
2. Tester la connexion à la base de données
3. Vérifier les permissions des dossiers
4. Consulter la documentation PHP/MySQL

## 📄 Licence

Ce projet est libre d'utilisation pour vos projets personnels et professionnels.

---

**Développé avec ❤️ pour créer des portfolios professionnels**
