# Guide d'Installation - Portfolio Développeur Web

## 🎯 Installation Rapide avec MAMP

### Étape 1 : Préparation
1. **Télécharger MAMP** : [https://www.mamp.info/en/downloads/](https://www.mamp.info/en/downloads/)
2. **Installer MAMP** en suivant les instructions
3. **Copier le dossier `portfolio-dev`** dans `/Applications/MAMP/htdocs/`

### Étape 2 : Configuration MAMP
1. **Démarrer MAMP**
2. **Vérifier les ports** :
   - Apache : 80 ou 8080
   - MySQL : 3306 ou 8889
3. **Cliquer sur "Start Servers"**

### Étape 3 : Base de données
1. **Accéder à phpMyAdmin** : `http://localhost/phpMyAdmin/` ou `http://localhost:8080/phpMyAdmin/`
2. **Créer une nouvelle base** nommée `portfolio`
3. **Importer le fichier** `database/portfolio.sql`
4. **Vérifier** que les tables sont créées avec les données de test

### Étape 4 : Test
1. **Site public** : `http://localhost/portfolio-dev/`
2. **Administration** : `http://localhost/portfolio-dev/admin/`
3. **Connexion admin** : 
   - Utilisateur : `admin`
   - Mot de passe : `admin123`

---

## 🌐 Déploiement sur Serveur

### Étape 1 : Upload des fichiers
```bash
# Via FTP/SFTP, uploader tous les fichiers sauf :
- README.md
- INSTALLATION.md
- .git/ (si présent)
```

### Étape 2 : Configuration de la base
1. **Créer une base MySQL** via votre hébergeur
2. **Noter les informations** :
   - Nom de la base
   - Utilisateur
   - Mot de passe
   - Serveur (généralement localhost)

### Étape 3 : Configuration PHP
Modifier `config/database.php` :
```php
// Configuration pour serveur de production
$host = 'localhost';        // ou l'adresse de votre serveur MySQL
$port = '3306';
$dbname = 'votre_base';     // nom de votre base
$username = 'votre_user';   // utilisateur MySQL
$password = 'votre_pass';   // mot de passe MySQL
```

### Étape 4 : Permissions
```bash
chmod 755 assets/images/uploads/
chmod 755 assets/images/projects/
chmod 755 assets/cv/
```

### Étape 5 : Import de la base
1. **Via phpMyAdmin** de votre hébergeur
2. **Ou via ligne de commande** :
```bash
mysql -u username -p nom_base < database/portfolio.sql
```

---

## 🔧 Configuration Avancée

### Variables d'environnement (optionnel)
Créer un fichier `.env` :
```
DB_HOST=localhost
DB_PORT=3306
DB_NAME=portfolio
DB_USER=root
DB_PASS=root
ADMIN_EMAIL=admin@votre-domaine.com
```

### Configuration email
Dans `includes/functions.php`, modifier la fonction `sendEmail()` :
```php
function sendEmail($to, $subject, $message, $from_name = 'Portfolio', $from_email = 'noreply@votre-domaine.com') {
    // Configuration SMTP si nécessaire
}
```

### Personnalisation
1. **Images** :
   - Remplacer `assets/images/profile.jpg`
   - Remplacer `assets/images/profile-large.jpg`
   - Ajouter vos images de projets dans `assets/images/projects/`

2. **CV** :
   - Ajouter votre CV en PDF dans `assets/cv/cv.pdf`

3. **Informations personnelles** :
   - Modifier les textes dans chaque page PHP
   - Adapter les informations de contact

---

## 🐛 Résolution de Problèmes

### Erreur de connexion à la base
```
Erreur de connexion à la base de données
```
**Solutions** :
1. Vérifier les paramètres dans `config/database.php`
2. S'assurer que MySQL est démarré
3. Vérifier que la base `portfolio` existe
4. Tester la connexion avec phpMyAdmin

### Erreur 404 sur l'admin
```
Page non trouvée : /admin/
```
**Solutions** :
1. Vérifier que le dossier `admin/` existe
2. Vérifier les permissions (755)
3. Vérifier la configuration Apache (.htaccess si nécessaire)

### Images non affichées
```
Images cassées dans les projets
```
**Solutions** :
1. Vérifier les permissions du dossier `assets/images/`
2. S'assurer que les chemins sont corrects
3. Vérifier que les images existent

### Session/Login non fonctionnel
```
Impossible de se connecter à l'admin
```
**Solutions** :
1. Vérifier que les sessions PHP sont activées
2. Vérifier les permissions d'écriture pour les sessions
3. Tester avec les identifiants par défaut : `admin` / `admin123`

### Upload d'images impossible
```
Erreur lors de l'upload
```
**Solutions** :
1. Vérifier `upload_max_filesize` dans php.ini
2. Vérifier `post_max_size` dans php.ini
3. Vérifier les permissions du dossier de destination

---

## 📊 Vérifications Post-Installation

### ✅ Checklist de fonctionnement

- [ ] **Page d'accueil** s'affiche correctement
- [ ] **Navigation** fonctionne sur toutes les pages
- [ ] **Formulaire de contact** envoie les messages
- [ ] **Admin accessible** avec login/password
- [ ] **Dashboard admin** affiche les statistiques
- [ ] **CRUD projets** fonctionne (ajout/modification/suppression)
- [ ] **Upload d'images** opérationnel
- [ ] **Site responsive** sur mobile/tablette
- [ ] **Animations** et effets visuels fonctionnent

### 🔍 Tests recommandés

1. **Test de sécurité** :
   - Tentative d'accès admin sans connexion
   - Test d'injection SQL (doit être bloquée)
   - Upload de fichiers non autorisés (doit échouer)

2. **Test de performance** :
   - Temps de chargement < 3 secondes
   - Images optimisées
   - CSS/JS sans erreurs console

3. **Test de compatibilité** :
   - Chrome, Firefox, Safari, Edge
   - Mobile (iOS/Android)
   - Tablette

---

## 🚀 Mise en Production

### Avant la mise en ligne

1. **Changer le mot de passe admin** :
   ```sql
   UPDATE users SET password = '$2y$10$VotreNouveauHashMotDePasse' WHERE username = 'admin';
   ```

2. **Configurer HTTPS** (recommandé)

3. **Optimiser les images** pour le web

4. **Configurer les sauvegardes** automatiques

5. **Tester toutes les fonctionnalités**

### Maintenance

- **Sauvegardes régulières** de la base de données
- **Mises à jour PHP** et MySQL
- **Monitoring** des logs d'erreur
- **Optimisation** des performances

---

## 📞 Support Technique

### Logs à vérifier en cas d'erreur

1. **Logs Apache** : `/var/log/apache2/error.log`
2. **Logs PHP** : Vérifier `error_log` dans php.ini
3. **Logs MySQL** : `/var/log/mysql/error.log`

### Commandes utiles

```bash
# Vérifier la version PHP
php -v

# Tester la connexion MySQL
mysql -u root -p -e "SHOW DATABASES;"

# Vérifier les permissions
ls -la assets/images/

# Redémarrer Apache (Linux)
sudo systemctl restart apache2
```

### Contact

Pour un support personnalisé, vous pouvez :
1. Vérifier les logs d'erreur
2. Tester sur un environnement de développement
3. Consulter la documentation PHP/MySQL officielle

---

**Installation réussie ? Félicitations ! 🎉**

Votre portfolio est maintenant prêt à être personnalisé et utilisé.
