// Script d'installation PWA pour Portfolio Admin
class PWAInstaller {
    constructor() {
        this.deferredPrompt = null;
        this.isInstalled = false;
        this.init();
    }

    init() {
        // Vérifier si déjà installé
        this.checkIfInstalled();
        
        // Enregistrer le Service Worker
        this.registerServiceWorker();
        
        // Écouter l'événement beforeinstallprompt
        this.setupInstallPrompt();
        
        // Créer le bouton d'installation
        this.createInstallButton();
        
        // Détecter l'installation
        this.detectInstallation();
    }

    checkIfInstalled() {
        // Vérifier si l'app est déjà installée
        if (window.matchMedia('(display-mode: standalone)').matches || 
            window.navigator.standalone === true) {
            this.isInstalled = true;
            console.log('[PWA] Application déjà installée');
        }
    }

    async registerServiceWorker() {
        if ('serviceWorker' in navigator) {
            try {
                const registration = await navigator.serviceWorker.register('/admin/sw.js', {
                    scope: '/admin/'
                });
                
                console.log('[PWA] Service Worker enregistré:', registration.scope);
                
                // Écouter les mises à jour
                registration.addEventListener('updatefound', () => {
                    console.log('[PWA] Mise à jour disponible');
                    const newWorker = registration.installing;
                    
                    newWorker.addEventListener('statechange', () => {
                        if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                            this.showUpdateNotification();
                        }
                    });
                });
                
            } catch (error) {
                console.error('[PWA] Erreur Service Worker:', error);
            }
        }
    }

    setupInstallPrompt() {
        window.addEventListener('beforeinstallprompt', (e) => {
            console.log('[PWA] Événement beforeinstallprompt déclenché');
            e.preventDefault();
            this.deferredPrompt = e;
            this.showInstallButton();
        });
    }

    createInstallButton() {
        // Créer le bouton d'installation
        const installButton = document.createElement('button');
        installButton.id = 'pwa-install-btn';
        installButton.innerHTML = `
            <i class="fas fa-download"></i>
            <span>Installer l'app</span>
        `;
        installButton.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: var(--accent-blue);
            color: var(--bg-primary);
            border: none;
            border-radius: 50px;
            padding: 12px 20px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(88, 166, 255, 0.3);
            transition: all 0.3s ease;
            z-index: 1000;
            display: none;
            align-items: center;
            gap: 8px;
        `;
        
        installButton.addEventListener('mouseenter', () => {
            installButton.style.transform = 'translateY(-2px)';
            installButton.style.boxShadow = '0 6px 20px rgba(88, 166, 255, 0.4)';
        });
        
        installButton.addEventListener('mouseleave', () => {
            installButton.style.transform = 'translateY(0)';
            installButton.style.boxShadow = '0 4px 15px rgba(88, 166, 255, 0.3)';
        });
        
        installButton.addEventListener('click', () => {
            this.installApp();
        });
        
        document.body.appendChild(installButton);
        this.installButton = installButton;
    }

    showInstallButton() {
        if (!this.isInstalled && this.installButton) {
            this.installButton.style.display = 'flex';
            
            // Animation d'apparition
            setTimeout(() => {
                this.installButton.style.opacity = '1';
                this.installButton.style.transform = 'translateY(0)';
            }, 100);
        }
    }

    hideInstallButton() {
        if (this.installButton) {
            this.installButton.style.display = 'none';
        }
    }

    async installApp() {
        if (!this.deferredPrompt) {
            this.showManualInstallInstructions();
            return;
        }

        try {
            // Afficher le prompt d'installation
            this.deferredPrompt.prompt();
            
            // Attendre la réponse de l'utilisateur
            const { outcome } = await this.deferredPrompt.userChoice;
            
            if (outcome === 'accepted') {
                console.log('[PWA] Installation acceptée');
                this.hideInstallButton();
            } else {
                console.log('[PWA] Installation refusée');
            }
            
            this.deferredPrompt = null;
            
        } catch (error) {
            console.error('[PWA] Erreur installation:', error);
            this.showManualInstallInstructions();
        }
    }

    detectInstallation() {
        window.addEventListener('appinstalled', () => {
            console.log('[PWA] Application installée avec succès');
            this.isInstalled = true;
            this.hideInstallButton();
            this.showSuccessNotification();
        });
    }

    showManualInstallInstructions() {
        const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent);
        const isAndroid = /Android/.test(navigator.userAgent);
        
        let instructions = '';
        
        if (isIOS) {
            instructions = `
                <div style="text-align: left;">
                    <strong>📱 Installation sur iOS :</strong><br>
                    1. Appuyez sur <i class="fas fa-share"></i> (Partager)<br>
                    2. Sélectionnez "Sur l'écran d'accueil"<br>
                    3. Appuyez sur "Ajouter"
                </div>
            `;
        } else if (isAndroid) {
            instructions = `
                <div style="text-align: left;">
                    <strong>📱 Installation sur Android :</strong><br>
                    1. Appuyez sur ⋮ (Menu)<br>
                    2. Sélectionnez "Ajouter à l'écran d'accueil"<br>
                    3. Confirmez l'installation
                </div>
            `;
        } else {
            instructions = `
                <div style="text-align: left;">
                    <strong>💻 Installation sur ordinateur :</strong><br>
                    1. Cliquez sur l'icône d'installation dans la barre d'adresse<br>
                    2. Ou utilisez le menu du navigateur<br>
                    3. Sélectionnez "Installer Portfolio Admin"
                </div>
            `;
        }
        
        this.showNotification(instructions, 'info', 8000);
    }

    showSuccessNotification() {
        this.showNotification(`
            <div style="text-align: center;">
                <i class="fas fa-check-circle" style="color: var(--success); font-size: 2rem; margin-bottom: 0.5rem;"></i><br>
                <strong>Application installée !</strong><br>
                Vous pouvez maintenant accéder à l'administration depuis votre écran d'accueil.
            </div>
        `, 'success', 5000);
    }

    showUpdateNotification() {
        const updateNotification = document.createElement('div');
        updateNotification.innerHTML = `
            <div style="display: flex; align-items: center; gap: 1rem;">
                <i class="fas fa-sync-alt"></i>
                <div>
                    <strong>Mise à jour disponible</strong><br>
                    <small>Redémarrez l'app pour appliquer</small>
                </div>
                <button onclick="window.location.reload()" style="background: var(--accent-blue); color: var(--bg-primary); border: none; padding: 0.5rem 1rem; border-radius: 4px; cursor: pointer;">
                    Redémarrer
                </button>
            </div>
        `;
        updateNotification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            color: var(--text-primary);
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            z-index: 1001;
            max-width: 300px;
        `;
        
        document.body.appendChild(updateNotification);
        
        setTimeout(() => {
            if (updateNotification.parentNode) {
                updateNotification.remove();
            }
        }, 10000);
    }

    showNotification(message, type = 'info', duration = 5000) {
        const notification = document.createElement('div');
        notification.innerHTML = message;
        
        const colors = {
            info: 'var(--accent-blue)',
            success: 'var(--success)',
            warning: 'var(--warning)',
            error: 'var(--error)'
        };
        
        notification.style.cssText = `
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: var(--bg-secondary);
            border: 2px solid ${colors[type]};
            color: var(--text-primary);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            z-index: 1002;
            max-width: 400px;
            text-align: center;
            font-family: 'JetBrains Mono', monospace;
            line-height: 1.5;
        `;
        
        document.body.appendChild(notification);
        
        // Fermer en cliquant à l'extérieur
        const overlay = document.createElement('div');
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1001;
        `;
        
        overlay.addEventListener('click', () => {
            notification.remove();
            overlay.remove();
        });
        
        document.body.appendChild(overlay);
        
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
                overlay.remove();
            }
        }, duration);
    }
}

// Initialiser la PWA seulement dans l'admin
if (window.location.pathname.startsWith('/admin/')) {
    document.addEventListener('DOMContentLoaded', () => {
        new PWAInstaller();
    });
}
