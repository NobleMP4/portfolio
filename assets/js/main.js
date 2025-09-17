// Portfolio JavaScript - Interactions et animations

document.addEventListener('DOMContentLoaded', function() {
    // Initialisation des composants
    initNavigation();
    initAnimations();
    initSkillBars();
    initScrollEffects();
    initLazyLoading();
    
    // Nouvelles fonctions pour le design développeur
    createCodeRain();
    animateSkillBarsNew();
    animateStats();
    initSmoothScroll();
    initParallax();
    initCardAnimations();
    initTerminalNav();
    initKonamiCode();
    
    // Animate code editor after a delay
    setTimeout(animateCodeEditor, 2000);
    
    // Add loading effect
    document.body.style.opacity = '0';
    setTimeout(() => {
        document.body.style.transition = 'opacity 0.5s ease';
        document.body.style.opacity = '1';
    }, 100);
});

// Navigation mobile
function initNavigation() {
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('.nav-menu');
    const navLinks = document.querySelectorAll('.nav-link');

    if (hamburger && navMenu) {
        hamburger.addEventListener('click', function() {
            hamburger.classList.toggle('active');
            navMenu.classList.toggle('active');
        });

        // Fermer le menu mobile lors du clic sur un lien
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                hamburger.classList.remove('active');
                navMenu.classList.remove('active');
            });
        });
    }

    // Navigation sticky avec effet de transparence
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 100) {
                navbar.style.background = 'rgba(255, 255, 255, 0.95)';
                navbar.style.backdropFilter = 'blur(10px)';
            } else {
                navbar.style.background = 'rgba(255, 255, 255, 0.95)';
            }
        });
    }

    // Mise en surbrillance du lien actif selon la section
    const sections = document.querySelectorAll('section[id]');
    if (sections.length > 0) {
        window.addEventListener('scroll', highlightActiveNav);
    }
}

function highlightActiveNav() {
    const scrollPos = window.scrollY + 100;
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.nav-link');

    sections.forEach(section => {
        const top = section.offsetTop;
        const height = section.offsetHeight;
        const id = section.getAttribute('id');

        if (scrollPos >= top && scrollPos < top + height) {
            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === `#${id}`) {
                    link.classList.add('active');
                }
            });
        }
    });
}

// Animations au scroll
function initAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in-up');
                
                // Animation spéciale pour les cartes
                if (entry.target.classList.contains('skill-card') || 
                    entry.target.classList.contains('project-card') ||
                    entry.target.classList.contains('value-card')) {
                    setTimeout(() => {
                        entry.target.style.transform = 'translateY(0)';
                        entry.target.style.opacity = '1';
                    }, Math.random() * 300);
                }
            }
        });
    }, observerOptions);

    // Observer tous les éléments à animer
    const animatedElements = document.querySelectorAll(
        '.skill-card, .project-card, .value-card, .timeline-item, .education-card, .certification-card'
    );
    
    animatedElements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'all 0.6s ease';
        observer.observe(el);
    });
}

// Animation des barres de compétences
function initSkillBars() {
    const skillBars = document.querySelectorAll('.skill-progress');
    
    const skillObserver = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const bar = entry.target;
                const level = bar.getAttribute('data-level');
                
                setTimeout(() => {
                    bar.style.width = level + '%';
                }, 500);
                
                skillObserver.unobserve(bar);
            }
        });
    }, { threshold: 0.5 });

    skillBars.forEach(bar => {
        bar.style.transition = 'width 1.5s ease';
        skillObserver.observe(bar);
    });
}

// Effets de scroll
function initScrollEffects() {
    // Parallax pour l'image de profil
    const profileImg = document.querySelector('.profile-img');
    if (profileImg) {
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.5;
            profileImg.style.transform = `translateY(${rate}px)`;
        });
    }

    // Smooth scroll pour les liens d'ancrage
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                const offsetTop = targetElement.offsetTop - 80;
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });
}

// Lazy loading des images
function initLazyLoading() {
    const images = document.querySelectorAll('img[data-src]');
    
    const imageObserver = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });

    images.forEach(img => {
        imageObserver.observe(img);
    });
}

// Fonctions utilitaires
function debounce(func, wait, immediate) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            timeout = null;
            if (!immediate) func(...args);
        };
        const callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func(...args);
    };
}

// Animation de typing pour les titres
function typeWriter(element, text, speed = 100) {
    let i = 0;
    element.innerHTML = '';
    
    function type() {
        if (i < text.length) {
            element.innerHTML += text.charAt(i);
            i++;
            setTimeout(type, speed);
        }
    }
    
    type();
}

// Compteur animé
function animateCounter(element, target, duration = 2000) {
    const start = 0;
    const increment = target / (duration / 16);
    let current = start;
    
    const timer = setInterval(() => {
        current += increment;
        element.textContent = Math.floor(current);
        
        if (current >= target) {
            element.textContent = target;
            clearInterval(timer);
        }
    }, 16);
}

// Gestion des modales
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
        
        // Animation d'entrée
        modal.style.opacity = '0';
        setTimeout(() => {
            modal.style.opacity = '1';
        }, 10);
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.opacity = '0';
        setTimeout(() => {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }, 300);
    }
}

// Fermer la modale en cliquant à l'extérieur
window.addEventListener('click', function(event) {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        if (event.target === modal) {
            closeModal(modal.id);
        }
    });
});

// Gestion des touches pour les modales
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const openModals = document.querySelectorAll('.modal[style*="block"]');
        openModals.forEach(modal => {
            closeModal(modal.id);
        });
    }
});

// Validation des formulaires côté client
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    const inputs = form.querySelectorAll('input[required], textarea[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        const errorElement = document.getElementById(input.id + '-error');
        
        // Reset des erreurs précédentes
        if (errorElement) {
            errorElement.textContent = '';
        }
        input.classList.remove('error');
        
        // Validation
        if (!input.value.trim()) {
            showFieldError(input, 'Ce champ est requis');
            isValid = false;
        } else if (input.type === 'email' && !isValidEmail(input.value)) {
            showFieldError(input, 'Veuillez saisir une adresse email valide');
            isValid = false;
        }
    });
    
    return isValid;
}

function showFieldError(input, message) {
    input.classList.add('error');
    const errorElement = document.getElementById(input.id + '-error');
    if (errorElement) {
        errorElement.textContent = message;
    }
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Animation des éléments au survol
function initHoverEffects() {
    const cards = document.querySelectorAll('.project-card, .skill-card, .value-card');
    
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px) scale(1.02)';
            this.style.boxShadow = '0 20px 40px rgba(0,0,0,0.1)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
            this.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)';
        });
    });
}

// Initialiser les effets de survol après le chargement
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(initHoverEffects, 1000);
});

// Fonction pour précharger les images
function preloadImages(urls) {
    urls.forEach(url => {
        const img = new Image();
        img.src = url;
    });
}

// Performance monitoring
function measurePerformance() {
    if ('performance' in window) {
        window.addEventListener('load', function() {
            const loadTime = performance.timing.loadEventEnd - performance.timing.navigationStart;
            console.log(`Page loaded in ${loadTime}ms`);
        });
    }
}

// Initialiser le monitoring des performances
measurePerformance();

// === NOUVELLES FONCTIONS POUR LE DESIGN DÉVELOPPEUR ===

// Code rain background
function createCodeRain() {
    const codeRain = document.getElementById('codeRain');
    if (!codeRain) return;
    
    const codeSnippets = [
        'function créerSiteWeb() {',
        'const développeur = "passionné";',
        'if (projet.intéressant) {',
        'return "C\'est parti !";',
        '} else { continue(); }',
        'créer une belle interface',
        'optimiser les performances',
        'while(apprendre) { coder(); }',
        'const mission = "vous aider";',
        'console.log("En cours...");',
        'export default MonTravail;',
        'import Créativité from "passion";',
        'echo "Développement web !";',
        'SELECT projets FROM portfolio;',
        'design { moderne: true; }',
        '.responsive { mobile-first; }',
        'déployer le site en ligne',
        'async function livrer() {',
        'throw new Success("Réussi!");',
        'catch(bonheur) { célébrer(); }'
    ];

    // Créer 15 lignes de code qui bougent
    for (let i = 0; i < 15; i++) {
        const line = document.createElement('div');
        line.className = 'code-line';
        line.textContent = codeSnippets[Math.floor(Math.random() * codeSnippets.length)];
        line.style.top = Math.random() * 100 + 'vh';
        line.style.animationDelay = Math.random() * 20 + 's';
        line.style.animationDuration = (Math.random() * 10 + 15) + 's';
        codeRain.appendChild(line);
    }
}

// Animer les barres de compétences (nouvelle version)
function animateSkillBarsNew() {
    const skillBars = document.querySelectorAll('.skill-progress');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const bar = entry.target;
                const level = bar.getAttribute('data-level');
                setTimeout(() => {
                    bar.style.width = level + '%';
                }, Math.random() * 500);
            }
        });
    }, { threshold: 0.5 });

    skillBars.forEach(bar => observer.observe(bar));
}

// Animation des stats
function animateStats() {
    const statNumbers = document.querySelectorAll('.stat-number');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const stat = entry.target;
                const finalNumber = stat.textContent.replace('+', '').replace('∞', '999').replace('%', '');
                
                if (finalNumber !== '999' && !isNaN(finalNumber)) {
                    let current = 0;
                    const target = parseInt(finalNumber);
                    const increment = target / 50;
                    
                    const counter = setInterval(() => {
                        current += increment;
                        if (current >= target) {
                            stat.textContent = target + (stat.textContent.includes('%') ? '%' : '+');
                            clearInterval(counter);
                        } else {
                            stat.textContent = Math.floor(current) + (stat.textContent.includes('%') ? '%' : '');
                        }
                    }, 50);
                }
            }
        });
    }, { threshold: 0.5 });

    statNumbers.forEach(stat => observer.observe(stat));
}

// Effet de frappe sur le code editor
function animateCodeEditor() {
    const codeLines = document.querySelectorAll('.editor-content .code-line .code-text');
    let delay = 1000;
    
    codeLines.forEach((line, index) => {
        const originalText = line.innerHTML;
        line.innerHTML = '';
        
        setTimeout(() => {
            let i = 0;
            const text = originalText;
            
            function typeCode() {
                if (i < text.length) {
                    if (text[i] === '<') {
                        // Skip HTML tags
                        const nextClose = text.indexOf('>', i);
                        line.innerHTML += text.substring(i, nextClose + 1);
                        i = nextClose + 1;
                    } else {
                        line.innerHTML += text[i];
                        i++;
                    }
                    setTimeout(typeCode, 50);
                }
            }
            typeCode();
        }, delay);
        
        delay += 300;
    });
}

// Smooth scroll pour les ancres (nouvelle version)
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// Effet de parallax léger
function initParallax() {
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const codeRain = document.getElementById('codeRain');
        if (codeRain) {
            codeRain.style.transform = `translateY(${scrolled * 0.3}px)`;
        }
    });
}

// Animation des cartes au survol (nouvelle version)
function initCardAnimations() {
    const cards = document.querySelectorAll('.stat-card, .skill-card, .project-card');
    
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
}

// Terminal navigation effect
function initTerminalNav() {
    const commands = document.querySelectorAll('.nav-command');
    
    commands.forEach(command => {
        command.addEventListener('mouseenter', function() {
            this.style.backgroundColor = 'rgba(88, 166, 255, 0.1)';
            this.style.paddingLeft = '8px';
        });
        
        command.addEventListener('mouseleave', function() {
            this.style.backgroundColor = 'transparent';
            this.style.paddingLeft = '0';
        });
    });
}

// Konami code easter egg
function initKonamiCode() {
    let konamiCode = [];
    const konami = [38, 38, 40, 40, 37, 39, 37, 39, 66, 65]; // ↑↑↓↓←→←→BA
    
    document.addEventListener('keydown', (e) => {
        konamiCode.push(e.keyCode);
        if (konamiCode.length > konami.length) {
            konamiCode.shift();
        }
        
        if (JSON.stringify(konamiCode) === JSON.stringify(konami)) {
            // Easter egg: Matrix mode
            document.body.style.filter = 'hue-rotate(120deg) saturate(1.5)';
            setTimeout(() => {
                document.body.style.filter = '';
            }, 5000);
            konamiCode = [];
        }
    });
}

// Console Easter Egg
console.log(`
🚀 BIENVENUE DANS MON PORTFOLIO !

Vous êtes curieux ? C'est parfait ! 
En tant que développeur, j'adore quand les gens explorent...

💻 Ce que je fais : Sites web, applications, e-commerce
🎯 Mon objectif : Créer des solutions sur mesure pour vous
⚡ Ma spécialité : Allier technique et créativité

🎮 Easter egg : Tapez ↑↑↓↓←→←→BA sur votre clavier
📞 Contactez-moi si vous avez un projet en tête !

PS: Si vous voyez ce message, vous avez l'esprit curieux... 
On va bien s'entendre ! 😉
`);
