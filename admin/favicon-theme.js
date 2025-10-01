// Script pour changer le favicon selon le thème (version admin)
function updateFavicon() {
    // Récupérer tous les liens favicon
    const favicons = document.querySelectorAll('link[rel*="icon"]');
    const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    
    const newHref = isDark ? '../assets/logo/logo-clair.png?v=5' : '../assets/logo/logo-sombre.png?v=5';
    
    // Changer tous les favicons
    favicons.forEach(favicon => {
        favicon.href = newHref;
    });
    
    // Forcer le changement en créant un nouveau lien
    const newFavicon = document.createElement('link');
    newFavicon.rel = 'icon';
    newFavicon.type = 'image/png';
    newFavicon.href = newHref;
    
    // Supprimer l'ancien et ajouter le nouveau
    const oldFavicon = document.querySelector('link[rel="icon"]');
    if (oldFavicon) {
        document.head.removeChild(oldFavicon);
    }
    document.head.appendChild(newFavicon);
    
    console.log('Favicon admin changé:', isDark ? 'logo-clair (thème sombre)' : 'logo-sombre (thème clair)');
}

// Changer le favicon immédiatement
updateFavicon();

// Changer le favicon au chargement
document.addEventListener('DOMContentLoaded', updateFavicon);

// Changer le favicon dès que possible (pour PWA)
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', updateFavicon);
} else {
    updateFavicon();
}

// Écouter les changements de thème
window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', updateFavicon);
