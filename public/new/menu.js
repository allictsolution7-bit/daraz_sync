
    // Mobile Menu Toggle
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const mobileMenu = document.querySelector('.mobile-menu');
    const overlay = document.querySelector('.overlay');
    const mobileMenuClose = document.querySelector('.mobile-menu-close');

    mobileMenuToggle.addEventListener('click', function () {
        mobileMenu.classList.add('active');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    });

    mobileMenuClose.addEventListener('click', function () {
        mobileMenu.classList.remove('active');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    });

    overlay.addEventListener('click', function () {
        mobileMenu.classList.remove('active');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    });

    // Submenu functionality
    const hasSubmenuLinks = document.querySelectorAll('.has-submenu');
    const backBtns = document.querySelectorAll('.back-btn');

    hasSubmenuLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const submenu = this.nextElementSibling;
            submenu.classList.add('active');
        });
    });

    backBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const submenu = this.closest('.submenu');
            submenu.classList.remove('active');
        });
    });