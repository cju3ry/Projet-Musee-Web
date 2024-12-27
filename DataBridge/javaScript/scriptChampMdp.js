document.addEventListener("DOMContentLoaded", function () {
    const checkbox = document.getElementById("modifierMotDePasse");
    const motDePasseContainer = document.getElementById("motDePasseContainer");
    const motDePasseInput = document.getElementById("motDePasse");

    checkbox.addEventListener("change", function () {
        if (checkbox.checked) {
            motDePasseContainer.style.display = "block";
            motDePasseInput.setAttribute("required", "required");
        } else {
            motDePasseContainer.style.display = "none";
            motDePasseInput.removeAttribute("required");
        }
    });
});