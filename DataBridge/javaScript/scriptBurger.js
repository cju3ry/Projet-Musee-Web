// Gestion du bouton burger pour afficher/masquer le menu offcanvas
var burgerButton = document.querySelector('.navbar-toggler');
var offcanvasMenu = document.querySelector('.offcanvas-start');

// Lorsque l'utilisateur clique sur le bouton hamburger
burgerButton.addEventListener('click', function() {
    // Afficher ou masquer le menu offcanvas
    offcanvasMenu.classList.toggle('show');

    // Cacher visuellement le bouton burger quand le menu est affiché
    if (offcanvasMenu.classList.contains('show')) {
        burgerButton.style.visibility = 'hidden';
    } else {
        burgerButton.style.visibility = 'visible';
    }
});

// Lorsque l'utilisateur ferme le menu offcanvas
document.querySelector('.btn-close').addEventListener('click', function() {
    offcanvasMenu.classList.remove('show');
    burgerButton.style.visibility = 'visible';
});

// Gestion des sous-menus
document.querySelectorAll('.submenu-icon').forEach(function(icon) {
    icon.addEventListener('click', function() {
        // Trouver l'élément <ul> associé à l'icône
        var submenu = this.closest('li').querySelector('.submenu');

        // Toggle (afficher/masquer) le sous-menu
        if (submenu.style.display === 'block') {
            submenu.style.display = 'none';  // Masquer le sous-menu
        } else {
            submenu.style.display = 'block'; // Afficher le sous-menu
        }

        // Rotation de l'icône
        this.classList.toggle('rotate');
    });
});

// Réafficher le bouton burger lorsque l'utilisateur clique en dehors du menu offcanvas
document.addEventListener('click', function(event) {
    // Si le clic n'est ni sur le menu offcanvas ni sur le bouton burger, on réaffiche le bouton burger
    if (!offcanvasMenu.contains(event.target) && !burgerButton.contains(event.target)) {
        if (offcanvasMenu.classList.contains('show')) {
            offcanvasMenu.classList.remove('show');
            burgerButton.style.visibility = 'visible';  // Réafficher le bouton hamburger
        }
    }
});
