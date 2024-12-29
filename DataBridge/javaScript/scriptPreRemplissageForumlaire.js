document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("expositionForm");

    // Liste des champs à sauvegarder
    const fields = ["intitule", "periodeDebut", "periodeFin", "nombreOeuvres", "resume", "debutExpoTemp", "finExpoTemp", "motsCles"];

    // Charger les valeurs depuis le localStorage
    fields.forEach(field => {
        const fieldElement = document.getElementById(field);
        if (fieldElement && localStorage.getItem(field)) {
            fieldElement.value = localStorage.getItem(field);
        }
    });

    // Sauvegarder les valeurs dans le localStorage lors de la modification
    form.addEventListener("input", (event) => {
        const { id, value } = event.target;
        if (fields.includes(id)) {
            localStorage.setItem(id, value);
        }
    });

    // Nettoyer le localStorage lors de la soumission du formulaire
    form.addEventListener("submit", () => {
        fields.forEach(field => localStorage.removeItem(field));
    });
});
