// JavaScript pour l'administration

// Fonctions d'upload d'images
function previewLogo(input) {
    const preview = document.getElementById('logoPreview');
    const previewImg = document.getElementById('previewImg');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'inline-block';
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

function removeLogo() {
    const input = document.getElementById('logo');
    const preview = document.getElementById('logoPreview');
    
    input.value = '';
    preview.style.display = 'none';
}

// Fonctions utilitaires pour l'admin
function confirmDelete(message = 'Êtes-vous sûr de vouloir supprimer cet élément ?') {
    return confirm(message);
}

// Initialisation des tooltips et autres éléments interactifs
document.addEventListener('DOMContentLoaded', function() {
    // Ajouter des tooltips aux boutons d'action
    const actionButtons = document.querySelectorAll('.table-actions .btn');
    actionButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
    
    // Animation des cartes au survol
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 8px 25px rgba(88, 166, 255, 0.15)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 4px 6px -1px rgba(0, 0, 0, 0.1)';
        });
    });
});
