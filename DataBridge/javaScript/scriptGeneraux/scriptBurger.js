// Gestion du bouton burger pour afficher/masquer le menu offcanvas
const burgerButton = document.querySelector('.navbar-toggler');
const offcanvasMenu = document.querySelector('.offcanvas-start');
const closeButton = document.querySelector('.btn-close');

let isTransitioning = false; // Empêche les interactions multiples pendant la transition
let isMenuOpen = false; // État du menu

// Fonction pour afficher ou masquer le menu
function toggleOffcanvasMenu() {
    if (isTransitioning) return; // Bloquer les actions multiples pendant la transition
    isTransitioning = true;

    // Basculer l'état du menu
    if (!isMenuOpen) {
        // Ouvrir le menu
        offcanvasMenu.classList.add('show');
        burgerButton.style.visibility = 'hidden';
        isMenuOpen = true;
    } else {
        // Fermer le menu
        offcanvasMenu.classList.remove('show');
        burgerButton.style.visibility = 'visible';
        isMenuOpen = false;
    }
}

// Gestion des clics sur le bouton burger
burgerButton.addEventListener('click', toggleOffcanvasMenu);

// Gestion des clics sur le bouton de fermeture
closeButton.addEventListener('click', function() {
    if (isTransitioning) return;
    offcanvasMenu.classList.remove('show');
    burgerButton.style.visibility = 'visible'; // Rétablir la visibilité du bouton burger
    isMenuOpen = false;
});

// Gestion des clics en dehors du menu
document.addEventListener('click', function(event) {
    if (isTransitioning) return; // Ignorer les clics pendant la transition

    // Vérifie si le clic est à l'extérieur
    const clickedInsideMenu = offcanvasMenu.contains(event.target);
    const clickedOnButton = burgerButton.contains(event.target);

    if (!clickedInsideMenu && !clickedOnButton && isMenuOpen) {
        // Fermer le menu si ouvert
        offcanvasMenu.classList.remove('show');
        burgerButton.style.visibility = 'visible';
        isMenuOpen = false;
    }
});

// Empêcher les clics en dehors d'agir pendant la transition
offcanvasMenu.addEventListener('transitionstart', () => {
    isTransitioning = true; // Bloquer les clics extérieurs au début de la transition
});

offcanvasMenu.addEventListener('transitionend', () => {
    isTransitioning = false; // Débloquer les clics extérieurs à la fin de la transition
    if (!offcanvasMenu.classList.contains('show')) {
        // Si le menu est fermé à la fin de la transition, rétablir la visibilité du bouton
        burgerButton.style.visibility = 'visible';
    }
});

// Gestion des sous-menus
document.querySelectorAll('.submenu-icon').forEach(function(icon) {
    icon.addEventListener('click', function() {
        const submenu = this.closest('li').querySelector('.submenu');
        submenu.style.display = (submenu.style.display === 'block') ? 'none' : 'block';
        this.classList.toggle('rotate');
    });
});
