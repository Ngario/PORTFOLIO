/**
 * MAIN JAVASCRIPT FILE
 * Purpose: Handle interactions, animations, and dynamic behaviors
 * Uses: Vanilla JavaScript (no jQuery needed)
 */

// ============================================
// 1. WAIT FOR DOM TO LOAD
// ============================================
// DOMContentLoaded ensures all HTML is loaded before running JS
document.addEventListener('DOMContentLoaded', function() {
    
    // ============================================
    // 2. SMOOTH SCROLL FOR ANCHOR LINKS
    // ============================================
    // When clicking links with #, scroll smoothly instead of jumping
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault(); // Prevent default jump
            
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // ============================================
    // 3. NAVBAR BACKGROUND ON SCROLL
    // ============================================
    // Make navbar background more opaque when scrolling down
    const navbar = document.querySelector('.navbar');
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
    
    // ============================================
    // 4. FORM VALIDATION FEEDBACK
    // ============================================
    // Add Bootstrap validation classes to forms
    const forms = document.querySelectorAll('.needs-validation');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        });
    });
    
    // ============================================
    // 5. ANIMATE ELEMENTS ON SCROLL (Intersection Observer)
    // ============================================
    // Add fade-in animation when elements come into view
    const observerOptions = {
        threshold: 0.1, // Trigger when 10% of element is visible
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
                observer.unobserve(entry.target); // Stop observing once animated
            }
        });
    }, observerOptions);
    
    // Observe all elements with 'animate-on-scroll' class
    document.querySelectorAll('.animate-on-scroll').forEach(element => {
        observer.observe(element);
    });
    
    // ============================================
    // 6. MOBILE MENU AUTO-CLOSE
    // ============================================
    // Close mobile menu when clicking a link
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            // Check if menu is open (mobile view)
            if (navbarCollapse.classList.contains('show')) {
                navbarToggler.click(); // Close menu
            }
        });
    });
    
    // ============================================
    // 7. BACK TO TOP BUTTON
    // ============================================
    // Create back-to-top button dynamically
    const backToTopBtn = document.createElement('button');
    backToTopBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
    backToTopBtn.className = 'btn btn-primary back-to-top';
    backToTopBtn.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        display: none;
        z-index: 1000;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        border: none;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    `;
    document.body.appendChild(backToTopBtn);
    
    // Show/hide back-to-top button based on scroll position
    window.addEventListener('scroll', function() {
        if (window.scrollY > 300) {
            backToTopBtn.style.display = 'block';
        } else {
            backToTopBtn.style.display = 'none';
        }
    });
    
    // Scroll to top when button is clicked
    backToTopBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
    
    // ============================================
    // 8. ALERT AUTO-DISMISS
    // ============================================
    // Auto-close Bootstrap alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000); // 5000ms = 5 seconds
    });
    
    // ============================================
    // 9. LOADING SPINNER FOR AJAX/FORMS
    // ============================================
    // Show loading spinner when form is submitted
    const formsWithLoader = document.querySelectorAll('form[data-loading]');
    
    formsWithLoader.forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
            }
        });
    });
    
    // ============================================
    // 10. CONSOLE MESSAGE (Developer signature)
    // ============================================
    console.log('%c👋 Welcome to the Portfolio!', 'color: #007bff; font-size: 20px; font-weight: bold;');
    console.log('%cBuilt with ❤️ using CodeIgniter 4', 'color: #28a745; font-size: 14px;');
    
});

// ============================================
// 11. GLOBAL UTILITY FUNCTIONS
// ============================================

/**
 * Show toast notification
 * @param {string} message - Message to display
 * @param {string} type - 'success', 'error', 'info', 'warning'
 */
function showToast(message, type = 'info') {
    // Create toast container if it doesn't exist
    let toastContainer = document.querySelector('.toast-container');
    
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }
    
    // Toast color based on type
    const bgColor = {
        'success': 'bg-success',
        'error': 'bg-danger',
        'info': 'bg-info',
        'warning': 'bg-warning'
    }[type] || 'bg-info';
    
    // Create toast element
    const toastEl = document.createElement('div');
    toastEl.className = `toast align-items-center text-white ${bgColor} border-0`;
    toastEl.setAttribute('role', 'alert');
    toastEl.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    toastContainer.appendChild(toastEl);
    
    // Initialize and show toast
    const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
    toast.show();
    
    // Remove from DOM after hiding
    toastEl.addEventListener('hidden.bs.toast', function() {
        toastEl.remove();
    });
}

/**
 * Format currency (Kenyan Shilling)
 * @param {number} amount 
 * @returns {string}
 */
function formatCurrency(amount) {
    return 'KSh ' + parseFloat(amount).toLocaleString('en-KE', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

/**
 * Debounce function (prevents excessive function calls)
 * Useful for search inputs, window resize events
 * @param {function} func 
 * @param {number} wait 
 * @returns {function}
 */
function debounce(func, wait = 300) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}
