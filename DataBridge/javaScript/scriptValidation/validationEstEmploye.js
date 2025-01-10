// Fonction pour valider les valeurs de "Est Employé"
function validerEstEmploye() {
    const estEmploye = document.getElementById('estEmploye').value.trim().toUpperCase();

    // Vérifie si la valeur est soit "OUI", soit "NON"
    if (estEmploye !== 'OUI' && estEmploye !== 'NON') {
        alert('Veuillez entrer "OUI" ou "NON" pour le champ "Est Employé(e)".');
        return false;
    }

    // Si la valeur est correcte
    return true;
}

// Attacher l'événement de validation à la soumission du formulaire
document.getElementById('formConferencier').addEventListener('submit', function (event) {
    // Vérifie si la validation est correcte
    if (!validerEstEmploye()) {
        event.preventDefault(); // Empêche l'envoi du formulaire si la validation échoue
    }
});