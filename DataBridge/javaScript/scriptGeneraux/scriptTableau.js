document.addEventListener("DOMContentLoaded", function() {
    const toggleCheckbox = document.getElementById('toggle');
    const vignette = document.getElementById("cardContainer");
    const tableau = document.getElementById("tableau");

    // Vérifier si un état précédent a été enregistré dans le localStorage
    if (localStorage.getItem('toggleState') === 'true') {
        toggleCheckbox.checked = true;
    } else {
        toggleCheckbox.checked = false;
    }

    // Appliquer l'affichage en fonction de l'état de la case au chargement de la page
    if (toggleCheckbox.checked) {
        vignette.style.display = "none";  // Cache la vignette
        tableau.style.display = "block";  // Affiche le tableau
    } else {
        vignette.style.display = "block"; // Affiche la vignette
        tableau.style.display = "none";   // Cache le tableau
    }

    // Enregistrer l'état de la case à cocher chaque fois qu'elle change
    toggleCheckbox.addEventListener('change', function() {
        localStorage.setItem('toggleState', toggleCheckbox.checked);

        // Met à jour l'affichage en fonction de l'état de la case
        if (toggleCheckbox.checked) {
            vignette.style.display = "none";  // Cache la vignette
            tableau.style.display = "block";  // Affiche le tableau
        } else {
            vignette.style.display = "block"; // Affiche la vignette
            tableau.style.display = "none";   // Cache le tableau
        }
    });
});


