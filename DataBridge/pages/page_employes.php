<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataBridge</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../CSS/stylePageEmployes.css">

</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-transparent fixed-top">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <!-- Bouton Hamburger -->
        <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Logo et Titre alignés et centrés -->
        <div class="d-flex align-items-center justify-content-center mx-auto text-center" style="position: absolute; left: 50%; transform: translateX(-50%);">
            <img src="../images/logo.png" class="logo-size me-2 w-25" alt="Logo">
            <a class="navbar-brand logo-size mb-0 fs-1 text-white" href="#">DataBridge</a>
        </div>

        <!-- Bouton et Login -->
        <div class="text-center">
            <span class="me-3 mt-3 text-white">Adrian CAZOR--BONNET</span><br>
            <a href="logout.php" class="btn btn-danger mt-3">Se Déconnecter</a><br><br><br>
        </div>
    </div>
</nav>
<!-- Main Content -->
<div class="container text-center mt-5">
    <h1 class="mb-5 fs-1 text-white fw-bold"><br><br><br>Bienvenue dans la section Gestion des Comptes Employés</h1>
    <p class="mb-5 fs-4 text-white fw-bold">Sélectionnez un employé pour voir ou modifier ses informations.</p></p>

    <!-- Bouton au dessus des cadres pour ajouter un employé -->
    <div class="text-center my-3 mb-5">
        <a href="ajouter_employe.html" class="btn btn-primary fw-bold">
            <i class="fas fa-user-plus"></i> Ajouter un employé
        </a>
    </div>

    <a href="ajouter_employe.html" class="btn btn-primary btn-lg position-fixed btn-flottant">
        <i class="fas fa-user-plus"></i>Ajouter un employé
    </a>

    <div class="row row-cols-1 row-cols-md-4 g-4 justify-content-center">
        <!-- Cardre de l'affichage -->
        <div class="card shadow-sm border-0 h-100 position-relative me-3">
            <!-- Icône crayon en haut à droite -->
            <a href="modification_employe.html?id=123" class="position-absolute top-0 end-0 m-2 text-primary">
                <i class="fas fa-pencil-alt fs-4"></i>
            </a>

            <!-- Contenu de la card -->
            <div class="card-body text-black rounded text-start">
                <span class="fs-5 fw-bold">Identifiant :</span> Mr de blé
                <br><br><span class="fs-5 fw-bold">Nom :</span> De blé
                <br><br><span class="fs-5 fw-bold">Prenom :</span> Tonton Farine
                <br><br><span class="fs-5 fw-bold">Telephone :</span> 06 06 06 06 06
                <br><br>
            </div>

            <!-- Icône poubelle en bas à droite -->
            <a href="suppression_employe.html?id=123"
               class="position-absolute bottom-0 end-0 m-2 text-danger">
                <i class="fas fa-trash-alt fs-4"></i>
            </a>

        </div>


        <!-- Cardre de l'affichage -->
        <div class="card shadow-sm border-0 h-100 position-relative me-3">
            <!-- Icône crayon en haut à droite -->
            <a href="modification_employe.html?id=123" class="position-absolute top-0 end-0 m-2 text-primary">
                <i class="fas fa-pencil-alt fs-4"></i>
            </a>

            <!-- Contenu de la card -->
            <div class="card-body text-black rounded text-start">
                <span class="fs-5 fw-bold">Identifiant :</span> Mr de blé
                <br><br><span class="fs-5 fw-bold">Nom :</span> De blé
                <br><br><span class="fs-5 fw-bold">Prenom :</span> Tonton Farine
                <br><br><span class="fs-5 fw-bold">Telephone :</span> 06 06 06 06 06
            </div>
        </div>

        <!-- Cardre de l'affichage -->
        <div class="card shadow-sm border-0 h-100 position-relative me-3">
            <!-- Icône crayon en haut à droite -->
            <a href="modification_employe.html?id=123" class="position-absolute top-0 end-0 m-2 text-primary">
                <i class="fas fa-pencil-alt fs-4"></i>
            </a>

            <!-- Contenu de la card -->
            <div class="card-body text-black rounded text-start">
                <span class="fs-5 fw-bold">Identifiant :</span> Mr de blé
                <br><br><span class="fs-5 fw-bold">Nom :</span> De blé
                <br><br><span class="fs-5 fw-bold">Prenom :</span> Tonton Farine
                <br><br><span class="fs-5 fw-bold">Telephone :</span> 06 06 06 06 06
            </div>
        </div>

        <!-- Cardre de l'affichage -->
        <div class="card shadow-sm border-0 h-100 position-relative me-3">
            <!-- Icône crayon en haut à droite -->
            <a href="modification_employe.html?id=123" class="position-absolute top-0 end-0 m-2 text-primary">
                <i class="fas fa-pencil-alt fs-4"></i>
            </a>

            <!-- Contenu de la card -->
            <div class="card-body text-black rounded text-start">
                <span class="fs-5 fw-bold">Identifiant :</span> Mr de blé
                <br><br><span class="fs-5 fw-bold">Nom :</span> De blé
                <br><br><span class="fs-5 fw-bold">Prenom :</span> Tonton Farine
                <br><br><span class="fs-5 fw-bold">Telephone :</span> 06 06 06 06 06
            </div>
        </div>





    </div>
</div>
<!-- Footer -->
<footer class="mt-5 text-center py-3 text-white">
    <p>&copy; 2024 DataBridge. Tous droits r&eacute;serv&eacute;s.</p>
</footer>
</body>
</html>
