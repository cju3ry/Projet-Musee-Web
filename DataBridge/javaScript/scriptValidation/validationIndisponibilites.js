// Fonction pour vérifier si une date est cohérente
function estDateValide(dateString) {
    const date = new Date(dateString);
    const [annee, mois, jour] = dateString.split('-').map(Number);

    // Vérifier que les composants de la date sont valides
    return (
        date.getFullYear() === annee &&
        date.getMonth() + 1 === mois && // Les mois commencent à 0 dans l'objet Date
        date.getDate() === jour
    );
}

// Fonction pour valider les dates d'indisponibilité
function validerIndisponibilites() {
    const datesInput = document.getElementById('indisponibilites').value;

    // Diviser les dates entrées par des virgules
    const datesArray = datesInput.split(',');

    // Vérifier que chaque paire de dates est correcte
    for (let i = 0; i < datesArray.length; i += 2) {
        // Vérifier qu'il y a bien une paire de dates (debut, fin)
        if (!datesArray[i] || !datesArray[i + 1]) {
            alert('Veuillez entrer des paires de dates valides (ex: 2024-12-10,2024-12-12).');
            return false;
        }

        const dateDebutStr = datesArray[i].trim();
        const dateFinStr = datesArray[i + 1].trim();

        // Vérifier que les dates sont valides et cohérentes
        if (!estDateValide(dateDebutStr) || !estDateValide(dateFinStr)) {
            alert(`Les dates entrées (${dateDebutStr} ou ${dateFinStr}) ne sont pas cohérentes. Veuillez entrer des dates réelles au format YYYY-MM-DD ou valides.`);
            return false;
        }

        // Convertir les dates en objets Date
        const dateDebut = new Date(dateDebutStr);
        const dateFin = new Date(dateFinStr);

        // Vérifier que la date de début est antérieure à la date de fin
        if (dateDebut > dateFin) {
            alert('La date de début ne doit pas être après la date de fin.');
            return false;
        }
    }

    // Si toutes les paires de dates sont valides
    return true;
}

// Attacher l'événement de validation à la soumission du formulaire
document.getElementById('formConferencier').addEventListener('submit', function (event) {
    if (!validerIndisponibilites()) {
        event.preventDefault(); // Empêche l'envoi du formulaire si la validation échoue
    }
});
