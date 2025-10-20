document.addEventListener('DOMContentLoaded', () => {
    handleNavbarScroll();
    handleScrollAnimations();
    // handleCounterAnimation() tidak memiliki elemen, tapi tetap aman
});

// 1. Logika Sticky Navbar
function handleNavbarScroll() {
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
}

// 2. Logika Scroll Animation (Fade-in-Up)
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

// 3. Logika Counter Angka (Hanya placeholder)
function handleCounterAnimation() {
    const counterElements = document.querySelectorAll('.stat-number');
    const duration = 1500; 

    const animateCounter = (el) => {
        const target = parseInt(el.getAttribute('data-target'));
        let startTime = null;

        const step = (timestamp) => {
            if (!startTime) startTime = timestamp;
            const progress = timestamp - startTime;
            const percentage = Math.min(progress / duration, 1);
            
            el.textContent = Math.floor(percentage * target);

            if (percentage < 1) {
                window.requestAnimationFrame(step);
            }
        };

        window.requestAnimationFrame(step);
    };

    const counterObserverOptions = { root: null, rootMargin: '0px', threshold: 0.5 };
    
    const counterObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, counterObserverOptions);

    counterElements.forEach(el => {
        counterObserver.observe(el);
    });
}

// Tambahkan fungsi baru di script.js
function checkSubmissionStatus() {
    // Objek URLSearchParams memudahkan pembacaan parameter di URL
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');
    const notificationBox = document.getElementById('success-notification');
    
    // 1. Cek apakah status adalah 'success_contact'
    if (status === 'success_contact' && notificationBox) {
        // Tampilkan kotak
        notificationBox.style.display = 'block';
        
        // Timeout untuk menjalankan animasi fade-in setelah sedikit penundaan
        setTimeout(() => {
            notificationBox.classList.add('show');
        }, 50);

        // 2. Sembunyikan notifikasi setelah 4 detik
        setTimeout(() => {
            notificationBox.classList.remove('show');
            // Hapus dari DOM setelah animasi selesai
            setTimeout(() => {
                notificationBox.style.display = 'none';
                
                // 3. (PENTING) Bersihkan URL dari parameter status agar tidak muncul lagi saat refresh
                history.replaceState(null, null, window.location.pathname);
                
            }, 500); 
        }, 4000); 
    }
}

document.addEventListener('DOMContentLoaded', () => {
    handleNavbarScroll();
    handleScrollAnimations();
    handleCounterAnimation(); 
    
    // Panggil fungsi baru ini saat DOM dimuat
    checkSubmissionStatus(); 
});