// JavaScript pour rendre les sous-menus interactifs
document.querySelectorAll('.submenu-icon').forEach(function(icon) {
    icon.addEventListener('click', function() {
        var submenu = icon.closest('li').querySelector('.submenu');
        submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block'; // Toggle visibility

        // Rotation de l'icône
        icon.classList.toggle('rotate');
    });
});

