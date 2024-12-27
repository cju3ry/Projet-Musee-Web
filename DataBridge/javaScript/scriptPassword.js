document.addEventListener('DOMContentLoaded', function () {


    // Sélection des éléments nécessaires
    const togglePassword = document.querySelector('.toggle-password');
    const passwordInput = document.querySelector('#motDePasse');

    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function () {
            // Change le type du champ mot de passe
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');

            const icon = this.querySelector('i');
            if (icon) {
                icon.classList.toggle('fa-eye', isPassword);
                icon.classList.toggle('fa-eye-slash', !isPassword); 
                console.log("Icône changée");
            } 
        });
    } 
});
