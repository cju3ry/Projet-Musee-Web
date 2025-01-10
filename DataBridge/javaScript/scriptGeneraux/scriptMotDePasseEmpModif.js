function verifierMotDePasseEnDirect() {
    const motDePasseContainer = document.getElementById("motDePasseContainer");
    const motDePasse = document.getElementById("motDePasse").value;
    const feedback = document.getElementById("feedbackMotDePasse");
    const submitBtn = document.getElementById("submitBtn");
    const modifierMotDePasse = document.getElementById("modifierMotDePasse").checked;

    // Critères de vérification
    const longueur = 8;
    const contientMinuscule = /[a-z]/;
    const contientMajuscule = /[A-Z]/;
    const contientChiffre = /[0-9]/;
    const contientSpecial = /[\W_]/;

    let messages = [];

    if (motDePasse.length < longueur || motDePasse.length > longueur) {
        messages.push("Doit contenir au moins 8 caractères.");
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

    // Validation
    if (modifierMotDePasse) {
        // Si la case "Modifier le mot de passe" est cochée
        if (messages.length > 0) {
            feedback.innerHTML = messages.join("<br>");
            feedback.style.color = "red";
            submitBtn.disabled = true; // Désactive le bouton
        } else {
            feedback.innerHTML = "";
            submitBtn.disabled = false; // Active le bouton
        }
    } else {
        // Si la case n'est pas cochée, activer le bouton
        feedback.innerHTML = "";
        submitBtn.disabled = false;
    }
}

// Activer/Désactiver le champ "Mot de passe" en fonction de la case à cocher
document.getElementById("modifierMotDePasse").addEventListener("change", function () {
    const motDePasseContainer = document.getElementById("motDePasseContainer");
    const submitBtn = document.getElementById("submitBtn");
    const feedback = document.getElementById("feedbackMotDePasse");

    if (this.checked) {
        motDePasseContainer.style.display = "block"; // Affiche le champ
        submitBtn.disabled = true; // Désactive le bouton tant que le mot de passe n'est pas validé
    } else {
        motDePasseContainer.style.display = "none"; // Cache le champ
        feedback.innerHTML = ""; // Supprime les messages d'erreur
        submitBtn.disabled = false; // Active le bouton
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
