document.addEventListener('DOMContentLoaded', () => {
    handleNavbarScroll();
    handleScrollAnimations();
    checkSubmissionStatus();
});

function handleNavbarScroll() {
    const navbar = document.querySelector('.navbar');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.classList.add('bg-white', 'shadow');
        } else {
            navbar.classList.remove('bg-white', 'shadow');
        }
    });
}

function handleScrollAnimations() {
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.fade-in-up').forEach(el => {
        observer.observe(el);
    });
}

function checkSubmissionStatus() {
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');
    const notificationBox = document.getElementById('success-notification');
    
    if (status === 'success_contact' && notificationBox) {
        notificationBox.style.display = 'block';
        
        setTimeout(() => {
            notificationBox.classList.add('show');
        }, 50);

        setTimeout(() => {
            notificationBox.classList.remove('show');
            setTimeout(() => {
                notificationBox.style.display = 'none';
                history.replaceState(null, null, window.location.pathname);
            }, 500);
        }, 4000);
    }
}