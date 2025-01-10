function verifierMotDePasseEnDirect() {
    const motDePasse = document.getElementById("motDePasse").value;
    const feedback = document.getElementById("feedbackMotDePasse");
    const submitBtn = document.getElementById("submitBtn");

    // Critères de vérification
    const longueur = 8;
    const contientMinuscule = /[a-z]/;
    const contientMajuscule = /[A-Z]/;
    const contientChiffre = /[0-9]/;
    const contientSpecial = /[\W_]/;

    let messages = [];

    if (motDePasse.length < longueur) {
        messages.push("Doit contenir 8 caractères.");
    }
    if (motDePasse.length > longueur) {
        messages.push("Doit contenir 8 caractères.");
    }
    if (!contientMinuscule.test(motDePasse)) {
        messages.push("Au moins une lettre minuscule.");
    }
    if (!contientMajuscule.test(motDePasse)) {
        messages.push("Au moins une lettre majuscule.");
    }
    if (!contientChiffre.test(motDePasse)) {
        messages.push("Au moins un chiffre.");
    }
    if (!contientSpecial.test(motDePasse)) {
        messages.push("Au moins un caractère spécial.");
    }

    // Affiche les messages ou indique que le mot de passe est valide
    if (messages.length > 0) {
        feedback.innerHTML = messages.join("<br>");
        feedback.style.color = "red";
        submitBtn.disabled = true; // Désactive le bouton
    } else {
        feedback.innerHTML = "";
        submitBtn.disabled = false; // Active le bouton
    }
}

// Affiche ou masque le champ de mot de passe
document.getElementById('modifierMotDePasse').addEventListener('change', function() {
    const motDePasseContainer = document.getElementById('motDePasseContainer');
    if (this.checked) {
        motDePasseContainer.classList.remove('d-none'); // Affiche le champ
    } else {
        motDePasseContainer.classList.add('d-none'); // Masque le champ
    }
});

// Affiche ou masque le champ de mot de passe
function togglePasswordVisibility(inputId, iconElement) {
    const passwordInput = document.getElementById(inputId);
    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        iconElement.classList.remove("fa-eye-slash");
        iconElement.classList.add("fa-eye");
    } else {
        passwordInput.type = "password";
        iconElement.classList.remove("fa-eye");
        iconElement.classList.add("fa-eye-slash");
    }

}