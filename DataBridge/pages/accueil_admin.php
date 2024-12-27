<?php
session_start();
if (!isset($_SESSION['loginAdmin']) && !isset($_SESSION['loginEmploye'])) {
    header("location: ../index.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataBridge</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../CSS/styleAccueil.css">
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-transparent fixed-top">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <!-- Bouton Hamburger -->
        <button id="burgerButton" class="navbar-toggler bg-light" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu" aria-controls="offcanvasMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Logo et Titre alignés et centrés -->
        <div class="d-flex align-items-center justify-content-center mx-auto text-center" style="position: absolute; left: 50%; transform: translateX(-50%);">
            <img src="../images/logo.png" class="logo-size me-2 w-25" alt="Logo">
            <a class="navbar-brand logo-size mb-0 fs-1 text-white" href="#">DataBridge</a>
        </div>
        <!-- Bouton et Login -->
        <div class="text-center">
            <span class="me-3 mt-3 text-white"><?php echo ($_SESSION['loginAdmin']);?></span><br>
            <form method="post" action="logout.php">
                <button type="submit" class="btn btn-danger mt-3">Se déconnecter</button><br><br><br>
            </form>
        </div>
    </div>
</nav>

<!-- Sidebar (Offcanvas) -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasMenu" aria-labelledby="offcanvasMenuLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasMenuLabel">Menu</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="list-unstyled">
            <!-- Menu principal -->
            <li>
                <a href="accueil_admin.php" class="text-decoration-none d-flex justify-content-between align-items-center">
                    Accueil
                    <i class="fas fa-home"></i>
                </a>
            </li>
            <li>
                <a class="text-decoration-none d-flex justify-content-between align-items-center">
                    Gestion des Comptes Employés
                    <i class="submenu-icon fa fa-chevron-right"></i>
                </a>
                <ul class="submenu">
                    <li><a href="page_ajout_employes.php" class="text-decoration-none">Ajouter un employes</a></li>
                    <li><a href="page_employes.php?page=1" class="text-decoration-none">Consulter les employes</a></li>
                </ul>
            </li>
            <li>
                <a class="text-decoration-none d-flex justify-content-between align-items-center">
                    Gestion des Conférenciers
                    <i class="submenu-icon fa fa-chevron-right"></i>
                </a>
                <ul class="submenu">
                    <li><a href="#" class="text-decoration-none">Ajouter un Conférencier</a></li>
                    <li><a href="page_conferenciers.php?page=1" class="text-decoration-none">Consulter les Conférenciers</a></li>
                </ul>
            </li>

            <li>
                <a class="text-decoration-none d-flex justify-content-between align-items-center">
                    Gestion des Expositions
                    <i class="submenu-icon fa fa-chevron-right"></i>
                </a>
                <ul class="submenu">
                    <li><a href="#" class="text-decoration-none">Ajouter une Exposition</a></li>
                    <li><a href="page_expositions.php?page=1" class="text-decoration-none">Consulter les Expositions</a></li>
                </ul>
            </li>

            <li>
                <a href="#" class="text-decoration-none d-flex justify-content-between align-items-center">
                    Gestion des Visites
                    <i class="submenu-icon fa fa-chevron-right"></i>
                </a>
                <ul class="submenu">
                    <li><a href="#" class="text-decoration-none">Ajouter une Visite</a></li>
                    <li><a href="page_visites.php?page=1" class="text-decoration-none">Consulter les Visites</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>

<!-- Main Content -->
<div class="container text-center mt-5">
    <h1 class="mb-5 fs-1 text-white fw-bold"><br><br><br>Bienvenue dans le portail de gestion DataBridge</h1>
    <p class="mb-5 fs-4 text-white fw-bold">Sélectionnez une catégorie pour commencer.</p>

    <div class="row row-cols-1 row-cols-md-4 g-4">
        <!-- Gestion des Comptes Employés -->
        <div class="col">
            <a href="page_employes.php?page=1" target="_blank" class="text-decoration-none">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body bg-primary text-white rounded">
                        <h5 class="card-title fs-3 mb-5 text-white">Gestion des Comptes Employés</h5>
                        <i class="fa fa-user fa-5x mb-2"></i>
                    </div>
                </div>
            </a>
        </div>
        <!-- Gestion des Conférenciers -->
        <div class="col">
            <a href="page_conferenciers.php?page=1" target="_blank" class="text-decoration-none">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body bg-primary text-white rounded">
                        <h5 class="card-title fs-3 mb-5">Gestion des Conférenciers</h5>
                        <i class="fas fa-chalkboard-teacher fa-5x mb-3"></i>
                    </div>
                </div>  
            </a>
        </div>

        <!-- Gestion des Expositions -->
        <div class="col">
            <a href="page_expositions.php?page=1" target="_blank" class="text-decoration-none">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body bg-primary text-white rounded">
                        <h5 class="card-title fs-3 mb-5">Gestion des Expositions</h5>
                        <i class="fas fa-image fa-5x mb-3"></i>
                    </div>
                </div>
            </a>
        </div>

        <!-- Gestion des Visites -->
        <div class="col">
            <a href="page_visites.php?page=1" target="_blank" class="text-decoration-none">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body bg-primary text-white rounded">
                        <h5 class="card-title fs-3 mb-5">Gestion des Visites</h5>
                        <i class="fas fa-clock fa-5x mb-3"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="mt-5 text-center py-3 text-white">
    <p>&copy; 2024 DataBridge. Tous droits réservés.</p>
</footer>

<!-- JavaScript files (Popper.js & Bootstrap JS) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

<!-- Fichier JS externe -->
<script src="../javaScript/scriptBurger.js"></script>

</body>
</html>
