// JavaScript pour la validation du formulaire de contact

document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    
    if (contactForm) {
        // Validation en temps réel
        const inputs = contactForm.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
            
            input.addEventListener('input', function() {
                clearFieldError(this);
            });
        });
        
        // Validation lors de la soumission
        contactForm.addEventListener('submit', function(e) {
            if (!validateContactForm()) {
                e.preventDefault();
            }
        });
    }
});

function validateContactForm() {
    const form = document.getElementById('contactForm');
    const name = document.getElementById('name');
    const email = document.getElementById('email');
    const subject = document.getElementById('subject');
    const message = document.getElementById('message');
    
    let isValid = true;
    
    // Validation du nom
    if (!validateField(name)) {
        isValid = false;
    }
    
    // Validation de l'email
    if (!validateField(email)) {
        isValid = false;
    }
    
    // Validation du sujet
    if (!validateField(subject)) {
        isValid = false;
    }
    
    // Validation du message
    if (!validateField(message)) {
        isValid = false;
    }
    
    return isValid;
}

function validateField(field) {
    const value = field.value.trim();
    const fieldName = field.getAttribute('name');
    let isValid = true;
    let errorMessage = '';
    
    // Validation selon le type de champ
    switch(fieldName) {
        case 'name':
            if (value === '') {
                errorMessage = 'Le nom est requis';
                isValid = false;
            } else if (value.length < 2) {
                errorMessage = 'Le nom doit contenir au moins 2 caractères';
                isValid = false;
            } else if (value.length > 100) {
                errorMessage = 'Le nom ne peut pas dépasser 100 caractères';
                isValid = false;
            }
            break;
            
        case 'email':
            if (value === '') {
                errorMessage = 'L\'adresse email est requise';
                isValid = false;
            } else if (!isValidEmail(value)) {
                errorMessage = 'Veuillez saisir une adresse email valide';
                isValid = false;
            }
            break;
            
        case 'subject':
            if (value === '') {
                errorMessage = 'Le sujet est requis';
                isValid = false;
            } else if (value.length < 3) {
                errorMessage = 'Le sujet doit contenir au moins 3 caractères';
                isValid = false;
            } else if (value.length > 200) {
                errorMessage = 'Le sujet ne peut pas dépasser 200 caractères';
                isValid = false;
            }
            break;
            
        case 'message':
            if (value === '') {
                errorMessage = 'Le message est requis';
                isValid = false;
            } else if (value.length < 10) {
                errorMessage = 'Le message doit contenir au moins 10 caractères';
                isValid = false;
            } else if (value.length > 2000) {
                errorMessage = 'Le message ne peut pas dépasser 2000 caractères';
                isValid = false;
            }
            break;
    }
    
    if (isValid) {
        clearFieldError(field);
    } else {
        showFieldError(field, errorMessage);
    }
    
    return isValid;
}

function showFieldError(field, message) {
    const errorElement = document.getElementById(field.id + '-error');
    field.classList.add('error');
    
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.style.display = 'block';
    }
    
    // Animation d'erreur
    field.style.borderColor = '#ef4444';
    field.style.animation = 'shake 0.5s ease-in-out';
    
    setTimeout(() => {
        field.style.animation = '';
    }, 500);
}

function clearFieldError(field) {
    const errorElement = document.getElementById(field.id + '-error');
    field.classList.remove('error');
    
    if (errorElement) {
        errorElement.textContent = '';
        errorElement.style.display = 'none';
    }
    
    field.style.borderColor = '#e2e8f0';
}

function isValidEmail(email) {
    const emailRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
    return emailRegex.test(email);
}

// Animation shake pour les erreurs
const style = document.createElement('style');
style.textContent = `
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }
    
    .form-group input.error,
    .form-group textarea.error {
        border-color: #ef4444 !important;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }
    
    .error-message {
        display: none;
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
`;
document.head.appendChild(style);

// Compteur de caractères pour le message
document.addEventListener('DOMContentLoaded', function() {
    const messageField = document.getElementById('message');
    if (messageField) {
        const maxLength = 2000;
        
        // Créer l'élément compteur
        const counter = document.createElement('div');
        counter.className = 'character-counter';
        counter.style.cssText = `
            text-align: right;
            font-size: 0.875rem;
            color: #64748b;
            margin-top: 0.25rem;
        `;
        
        messageField.parentNode.appendChild(counter);
        
        function updateCounter() {
            const currentLength = messageField.value.length;
            counter.textContent = `${currentLength}/${maxLength} caractères`;
            
            if (currentLength > maxLength * 0.9) {
                counter.style.color = '#f59e0b';
            } else if (currentLength > maxLength * 0.95) {
                counter.style.color = '#ef4444';
            } else {
                counter.style.color = '#64748b';
            }
        }
        
        messageField.addEventListener('input', updateCounter);
        updateCounter(); // Initial count
    }
});

// Auto-resize du textarea
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('message');
    if (textarea) {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    }
});

// Sauvegarde automatique du brouillon
let draftTimer;
const DRAFT_KEY = 'contact_form_draft';

function saveDraft() {
    const form = document.getElementById('contactForm');
    if (form) {
        const formData = new FormData(form);
        const draftData = {
            name: formData.get('name'),
            email: formData.get('email'),
            subject: formData.get('subject'),
            message: formData.get('message'),
            timestamp: Date.now()
        };
        
        localStorage.setItem(DRAFT_KEY, JSON.stringify(draftData));
    }
}

function loadDraft() {
    const draftData = localStorage.getItem(DRAFT_KEY);
    if (draftData) {
        try {
            const data = JSON.parse(draftData);
            const form = document.getElementById('contactForm');
            
            if (form && data.timestamp > Date.now() - 24 * 60 * 60 * 1000) { // 24h
                const fields = ['name', 'email', 'subject', 'message'];
                fields.forEach(field => {
                    const input = form.querySelector(`[name="${field}"]`);
                    if (input && data[field]) {
                        input.value = data[field];
                    }
                });
                
                // Afficher une notification
                showDraftNotification();
            }
        } catch (e) {
            console.error('Erreur lors du chargement du brouillon:', e);
        }
    }
}

function clearDraft() {
    localStorage.removeItem(DRAFT_KEY);
}

function showDraftNotification() {
    const notification = document.createElement('div');
    notification.className = 'draft-notification';
    notification.innerHTML = `
        <i class="fas fa-info-circle"></i>
        Brouillon restauré automatiquement
        <button onclick="this.parentNode.remove()" style="margin-left: 1rem; background: none; border: none; color: inherit; cursor: pointer;">×</button>
    `;
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background: #3b82f6;
        color: white;
        padding: 1rem;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        z-index: 1000;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Initialiser la sauvegarde automatique
document.addEventListener('DOMContentLoaded', function() {
    loadDraft();
    
    const form = document.getElementById('contactForm');
    if (form) {
        const inputs = form.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                clearTimeout(draftTimer);
                draftTimer = setTimeout(saveDraft, 1000);
            });
        });
        
        // Supprimer le brouillon après envoi réussi
        form.addEventListener('submit', function() {
            if (validateContactForm()) {
                clearDraft();
            }
        });
    }
});
