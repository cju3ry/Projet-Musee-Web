function validateMotsCles(input) {
    // Récupère la valeur actuelle du champ
    const motsCles = input.value;

    // Sépare les mots-clés en utilisant la virgule comme délimiteur
    const motsArray = motsCles.split(',').map(mot => mot.trim());

    // Vérifie si le nombre de mots-clés dépasse la limite
    if (motsArray.length > 10) {
        // Supprime les mots-clés excédentaires
        input.value = motsArray.slice(0, 10).join(', ');

        // Affiche un message d'avertissement ou une notification
        const helpText = document.getElementById('motsClesHelp');
        helpText.classList.add('text-danger');
        helpText.textContent = "Vous ne pouvez pas entrer plus de 10 mots-clés.";
    } else {
        // Réinitialise le message d'aide si tout est correct
        const helpText = document.getElementById('motsClesHelp');
        helpText.classList.remove('text-danger');
        helpText.textContent = "Vous pouvez entrer jusqu'à 10 mots-clés maximum.";
    }
}
