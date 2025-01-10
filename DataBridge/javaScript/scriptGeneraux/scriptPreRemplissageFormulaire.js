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

document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("expositionFormSupp");

    // Liste des champs à sauvegarder
    const fields = ["intitule", "periodeDebut", "periodeFin", "nombreOeuvres", "resume", "debutExpoTemp", "finExpoTemp", "motsCles"];


    // Nettoyer le localStorage lors de la soumission du formulaire
    form.addEventListener("submit", () => {
        fields.forEach(field => localStorage.removeItem(field));
    });
    
});

document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("employeForm");

    // Liste des champs à sauvegarder
    const fields = ["nom", "prenom", "telephone", "login"];

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

// Permet de ne pas reproposser les champs si clic sur annuler
document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("employeFormSupp");

    // Liste des champs à sauvegarder
    const fields = ["nom", "prenom", "telephone", "login" ];

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

document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("conferencierForm");

    // Liste des champs à sauvegarder
    const fields = ["nom", "prenom", "telephone","estEmploye", "specialites", "indisponibilites"];

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

// Permet de ne pas reproposser les chmaps si clic sur annuler
document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("conferencierFormSupp");

    // Liste des champs à sauvegarder
    const fields = ["nom", "prenom", "telephone","estEmploye", "specialites", "indisponibilites"];


    // Nettoyer le localStorage lors de la soumission du formulaire
    form.addEventListener("submit", () => {
        fields.forEach(field => localStorage.removeItem(field));
    });
});

document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("visiteForm");

    // Liste des champs à sauvegarder
    const fields = ["dateVisite", "heureDebutVisite", "intituleVisite","numTelVisite", "idExposition", "idConferencier","idEmploye"];

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

    // Nettoye le localStorage lors de la soumission du formulaire
    form.addEventListener("submit", () => {
        fields.forEach(field => localStorage.removeItem(field));
    });
});

// Permet de ne pas reproposser les champs en cas d'annulation
document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("visiteFormSupp");



    // Nettoye le localStorage lors de la soumission du formulaire
    form.addEventListener("submit", () => {
        fields.forEach(field => localStorage.removeItem(field));
    });
});
