// Affiche ou masque le champ de mot de passe
document.getElementById('modifierMotDePasse').addEventListener('change', function() {
    const motDePasseContainer = document.getElementById('motDePasseContainer');
    if (this.checked) {
        motDePasseContainer.classList.remove('d-none'); // Affiche le champ
    } else {
        motDePasseContainer.classList.add('d-none'); // Masque le champ
    }
});