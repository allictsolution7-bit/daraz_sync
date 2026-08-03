document.addEventListener('DOMContentLoaded', function() {
    // Mobile Menu Toggle
    const mobileMenuToggles = document.querySelectorAll('.mobile-menu-toggle');
    const mobileMenu = document.querySelector('.mobile-menu');
    const overlay = document.querySelector('.overlay');
    const mobileMenuClose = document.querySelector('.mobile-menu-close');

    if (mobileMenu && overlay) {
        mobileMenuToggles.forEach(toggle => {
            toggle.addEventListener('click', function (e) {
                e.preventDefault();
                mobileMenu.classList.add('active');
                overlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
        });

        if (mobileMenuClose) {
            mobileMenuClose.addEventListener('click', function (e) {
                e.preventDefault();
                mobileMenu.classList.remove('active');
                overlay.classList.remove('active');
                document.body.style.overflow = '';
            });
        }

        overlay.addEventListener('click', function () {
            mobileMenu.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        });
    }

    // Submenu functionality
    const hasSubmenuLinks = document.querySelectorAll('.has-submenu');
    const backBtns = document.querySelectorAll('.back-btn');

    hasSubmenuLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const submenu = this.nextElementSibling;
            if (submenu) {
                submenu.classList.add('active');
            }
        });
    });

    backBtns.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const submenu = this.closest('.submenu');
            if (submenu) {
                submenu.classList.remove('active');
            }
        });
    });
});
