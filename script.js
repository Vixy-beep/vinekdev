// Registro de GSAP plugins
gsap.registerPlugin(ScrollTrigger);

// Configuración de partículas
particlesJS('particles-js', {
    particles: {
        number: { value: 80, density: { enable: true, value_area: 800 } },
        color: { value: '#00d4ff' },
        shape: { type: 'circle' },
        opacity: { value: 0.5, random: false },
        size: { value: 3, random: true },
        line_linked: {
            enable: true,
            distance: 150,
            color: '#bf00ff',
            opacity: 0.4,
            width: 1
        },
        move: {
            enable: true,
            speed: 2,
            direction: 'none',
            random: false,
            straight: false,
            out_mode: 'out',
            bounce: false
        }
    },
    interactivity: {
        detect_on: 'canvas',
        events: {
            onhover: { enable: true, mode: 'repulse' },
            onclick: { enable: true, mode: 'push' },
            resize: true
        }
    },
    retina_detect: true
});

document.addEventListener('DOMContentLoaded', function() {
    // Cursor personalizado
    const cursor = document.querySelector('.cursor');
    const cursorFollower = document.querySelector('.cursor-follower');
    
    document.addEventListener('mousemove', (e) => {
        cursor.style.left = e.clientX + 'px';
        cursor.style.top = e.clientY + 'px';
        
        setTimeout(() => {
            cursorFollower.style.left = e.clientX + 'px';
            cursorFollower.style.top = e.clientY + 'px';
        }, 100);
    });

    // Efectos hover para elementos interactivos
    const interactiveElements = document.querySelectorAll('a, button, .service-card');
    interactiveElements.forEach(el => {
        el.addEventListener('mouseenter', () => {
            cursor.style.transform = 'scale(1.5)';
            cursorFollower.style.transform = 'scale(1.5)';
        });
        el.addEventListener('mouseleave', () => {
            cursor.style.transform = 'scale(1)';
            cursorFollower.style.transform = 'scale(1)';
        });
    });

    // Header scroll effect
    const header = document.getElementById('header');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 100) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });

    // Mobile menu toggle
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const mobileMenu = document.querySelector('.mobile-menu');
    
    mobileMenuBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('active');
        mobileMenuBtn.classList.toggle('active');
    });

    // Cerrar mobile menu al hacer click en un enlace
    const mobileLinks = document.querySelectorAll('.mobile-menu a');
    mobileLinks.forEach(link => {
        link.addEventListener('click', () => {
            mobileMenu.classList.remove('active');
            mobileMenuBtn.classList.remove('active');
        });
    });

    // Animaciones de scroll con GSAP
    gsap.utils.toArray('.animate-on-scroll').forEach(element => {
        gsap.fromTo(element, 
            {
                opacity: 0,
                y: 50,
                scale: 0.9
            },
            {
                opacity: 1,
                y: 0,
                scale: 1,
                duration: 1,
                ease: "power2.out",
                scrollTrigger: {
                    trigger: element,
                    start: "top 85%",
                    end: "bottom 15%",
                    toggleActions: "play none none reverse"
                }
            }
        );
    });

    // Animación de números contador
    function animateCounter(element, target) {
        let current = 0;
        const increment = target / 100;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            element.textContent = Math.floor(current);
        }, 20);
    }

    // Observer para contadores
    const statNumbers = document.querySelectorAll('.stat-number');
    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const target = parseInt(entry.target.dataset.target);
                animateCounter(entry.target, target);
                statsObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    statNumbers.forEach(stat => statsObserver.observe(stat));

    // Smooth scroll para enlaces de navegación
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

    // Parallax effect para hero
    gsap.to('.hero::before', {
        yPercent: -50,
        ease: "none",
        scrollTrigger: {
            trigger: '.hero',
            start: "top bottom",
            end: "bottom top",
            scrub: true
        }
    });

    // Configuración de formulario de contacto
    const contactForm = document.getElementById('contact-form');
    const formStatus = document.getElementById('form-status');
    const submitBtn = contactForm.querySelector('button[type="submit"]');
    const btnText = submitBtn.querySelector('.btn-text');
    
    // Determinar si usar EmailJS o FormSubmit
    const useEmailJS = false; // Cambiar a true cuando configures EmailJS
    
    if (useEmailJS) {
        // Manejo con EmailJS
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // UI Loading state
            submitBtn.disabled = true;
            btnText.innerHTML = '<div class="loader"></div>Enviando...';
            formStatus.style.display = 'none';
            
            // Animación de envío
            gsap.to(contactForm, {
                scale: 0.98,
                duration: 0.2,
                yoyo: true,
                repeat: 1
            });
            
            emailjs.sendForm('TU_SERVICE_ID_AQUI', 'TU_TEMPLATE_ID_AQUI', this)
                .then(function() {
                    formStatus.textContent = '🚀 ¡Mensaje enviado con éxito! Nuestro equipo te contactará en las próximas 24 horas para iniciar la revolución digital.';
                    formStatus.className = 'status-success';
                    formStatus.style.display = 'block';
                    contactForm.reset();
                    
                    // Animación de éxito
                    gsap.fromTo(formStatus, 
                        { opacity: 0, y: 20 },
                        { opacity: 1, y: 0, duration: 0.5 }
                    );
                    
                }, function(error) {
                    formStatus.textContent = '❌ Error temporal en el sistema. Por favor, intenta nuevamente o contáctanos directamente.';
                    formStatus.className = 'status-error';
                    formStatus.style.display = 'block';
                    console.error('Error al enviar el formulario:', error);
                })
                .finally(() => {
                    // Restaurar botón
                    submitBtn.disabled = false;
                    btnText.textContent = 'Iniciar Transformación Digital';
                });
        });
    } else {
        // Manejo con FormSubmit (funciona inmediatamente)
        contactForm.addEventListener('submit', function(e) {
            // UI Loading state
            submitBtn.disabled = true;
            btnText.innerHTML = '<div class="loader"></div>Enviando...';
            formStatus.style.display = 'none';
            
            // Animación de envío
            gsap.to(contactForm, {
                scale: 0.98,
                duration: 0.2,
                yoyo: true,
                repeat: 1
            });
            
            // FormSubmit manejará el envío automáticamente
            // No preventDefault() para permitir el envío normal del formulario
        });
    }

    // Efecto de hover para service cards
    const serviceCards = document.querySelectorAll('.service-card');
    serviceCards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            gsap.to(card, {
                scale: 1.05,
                duration: 0.3,
                ease: "power2.out"
            });
        });
        
        card.addEventListener('mouseleave', () => {
            gsap.to(card, {
                scale: 1,
                duration: 0.3,
                ease: "power2.out"
            });
        });
    });

    // Texto typing effect para el hero
    const heroTitle = document.querySelector('.hero h1 .highlight');
    if (heroTitle) {
        const text = heroTitle.textContent;
        heroTitle.textContent = '';
        
        let i = 0;
        const typeWriter = () => {
            if (i < text.length) {
                heroTitle.textContent += text.charAt(i);
                i++;
                setTimeout(typeWriter, 100);
            }
        };
        
        setTimeout(typeWriter, 1000);
    }

    // Scroll reveal animations
    ScrollReveal({
        reset: false,
        distance: '60px',
        duration: 2000,
        delay: 200,
    });

    // Observer para animaciones de scroll personalizadas
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, observerOptions);

    // Observar elementos que necesitan animación
    const animatedElements = document.querySelectorAll('.animate-on-scroll');
    animatedElements.forEach(el => observer.observe(el));

    // Efecto de partículas en hover sobre el hero
    const hero = document.querySelector('.hero');
    hero.addEventListener('mousemove', (e) => {
        const rect = hero.getBoundingClientRect();
        const x = ((e.clientX - rect.left) / rect.width) * 100;
        const y = ((e.clientY - rect.top) / rect.height) * 100;
        
        hero.style.background = `
            radial-gradient(circle at ${x}% ${y}%, 
                rgba(0, 212, 255, 0.1) 0%, 
                transparent 50%
            ),
            var(--gradient-primary)
        `;
    });

    // Preloader (si existe)
    const preloader = document.querySelector('.preloader');
    if (preloader) {
        window.addEventListener('load', () => {
            gsap.to(preloader, {
                opacity: 0,
                duration: 1,
                onComplete: () => preloader.remove()
            });
        });
    }

    // Lazy loading para imágenes
    const images = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                imageObserver.unobserve(img);
            }
        });
    });

    images.forEach(img => imageObserver.observe(img));

    // Efecto de escritura para elementos de texto
    function typeWriter(element, text, speed = 50) {
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

    // Aplicar efecto de escritura a elementos específicos cuando aparezcan
    const typingElements = document.querySelectorAll('[data-typing]');
    const typingObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const element = entry.target;
                const text = element.textContent;
                const speed = parseInt(element.dataset.typing) || 50;
                
                setTimeout(() => {
                    typeWriter(element, text, speed);
                }, 500);
                
                typingObserver.unobserve(element);
            }
        });
    }, { threshold: 0.5 });

    typingElements.forEach(el => typingObserver.observe(el));

    // Efectos de sonido hover (opcional - descomentado por defecto)
    /*
    const playHoverSound = () => {
        const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+n1wXkpBSl+zO/eizEIHm3A7+OZUQ0PU6Pk7rhlHgg2jdXzzn0vBSF7xe/hjjaIGmq+8OimWBALTKXs87ZmIAU7k9n'))');
        audio.volume = 0.1;
        audio.play().catch(() => {});
    };

    // Agregar sonidos a elementos interactivos
    interactiveElements.forEach(el => {
        el.addEventListener('mouseenter', playHoverSound);
    });
    */

    // Optimización de rendimiento para scroll
    let ticking = false;
    function updateScrollEffects() {
        // Actualizar efectos basados en scroll aquí
        ticking = false;
    }

    window.addEventListener('scroll', () => {
        if (!ticking) {
            requestAnimationFrame(updateScrollEffects);
            ticking = true;
        }
    });

    // Easter egg - Konami code
    let konamiCode = [];
    const konamiSequence = [38, 38, 40, 40, 37, 39, 37, 39, 66, 65]; // ↑↑↓↓←→←→BA

    document.addEventListener('keydown', (e) => {
        konamiCode.push(e.keyCode);
        konamiCode = konamiCode.slice(-10);
        
        if (konamiCode.join(',') === konamiSequence.join(',')) {
            // Activar modo matrix
            document.body.style.filter = 'hue-rotate(120deg)';
            setTimeout(() => {
                document.body.style.filter = '';
            }, 3000);
        }
    });

    // Detectar dispositivo táctil
    const isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
    
    if (isTouchDevice) {
        // Desactivar efectos de cursor en dispositivos táctiles
        cursor.style.display = 'none';
        cursorFollower.style.display = 'none';
        
        // Agregar clase para estilos específicos de touch
        document.body.classList.add('touch-device');
    }

    // Analytics de interacciones (placeholder)
    function trackInteraction(action, element) {
        // Aquí se podría integrar con Google Analytics, etc.
        console.log(`Interacción: ${action} en ${element}`);
    }

    // Rastrear clicks en botones importantes
    document.querySelectorAll('.btn').forEach(btn => {
        btn.addEventListener('click', () => {
            trackInteraction('click', btn.textContent);
        });
    });

    console.log('🚀 VinekDev - Sistema inicializado correctamente');
});