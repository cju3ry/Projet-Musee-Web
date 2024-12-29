<?php
include("../fonction/fonctionsAuthentification.php");
include("../fonction/fonctionInsert.php");
session_start();

if (isset($_SESSION["insertionOk"]) && $_SESSION["insertionOk"] === true) {
    echo "<script src='../javaScript/scriptInsertionEmpOk.js'></script>";
    unset($_SESSION["insertionOk"]);
}
if (isset($_SESSION["insertionOk"]) && $_SESSION["insertionOk"] === false)
{
    echo "<script src='../javaScript/scriptInsertionEmpKo.js'></script>";
    unset($_SESSION["insertionOk"]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = connecterBd();
    } catch (PDOException $e) {
        echo "Erreur de connexion à la base de données " . $e->getMessage();
        header('Location: erreurConnexion.php');
    }

    $nom = $_POST['nom'] ?? null;
    $prenom = $_POST['prenom'] ?? null;
    $telephone = $_POST['telephone'] ?? null;
    $login = $_POST['login'] ?? null;
    $pwd = $_POST['motDePasse'] ?? null;
    $pwd = md5($pwd);

    if (!empty($nom) && !empty($prenom) && !empty($telephone)) {
        try {
            insertEmploye($pdo, $nom, $prenom, $telephone,$login,$pwd);
            $insertionOk = true;
            $_SESSION["insertionOk"] = $insertionOk;
            header('Location: page_ajout_employes.php');

        } catch (Exception $e) {
            $insertionOk = false;
            $_SESSION["insertionOk"] = $insertionOk;
            header('Location: page_ajout_employes.php');
        }
    } else {
        header('Location: page_ajout_employes.php');
        exit();
    }
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
            <a class="navbar-brand logo-size mb-0 fs-1 text-white">DataBridge</a>
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
                <a href="accueil.php" class="text-decoration-none d-flex justify-content-between align-items-center">
                    Accueil
                    <i class="fas fa-home"></i>
                </a>
            </li>
            <li>
                <a href="#" class="text-decoration-none d-flex justify-content-between align-items-center">
                    Gestion des Comptes Employés
                    <i class="submenu-icon fa fa-chevron-right"></i>
                </a>
                <ul class="submenu">
                    <li><a href="page_ajout_employes.php" class="text-decoration-none">Ajouter un employes</a></li>
                    <li><a href="page_employes.php?page=1" class="text-decoration-none">Consulter les employes</a></li>
                </ul>
            </li>
            <li>
                <a href="#" class="text-decoration-none d-flex justify-content-between align-items-center">
                    Gestion des Conférenciers
                    <i class="submenu-icon fa fa-chevron-right"></i>
                </a>
                <ul class="submenu">
                    <li><a href="#" class="text-decoration-none">Ajouter un Conférencier</a></li>
                    <li><a href="#" class="text-decoration-none">Consulter les Conférenciers</a></li>
                </ul>
            </li>

            <li>
                <a href="#" class="text-decoration-none d-flex justify-content-between align-items-center">
                    Gestion des Expositions
                    <i class="submenu-icon fa fa-chevron-right"></i>
                </a>
                <ul class="submenu">
                    <li><a href="#" class="text-decoration-none">Ajouter une Exposition</a></li>
                    <li><a href="#" class="text-decoration-none">Consulter les Expositions</a></li>
                </ul>
            </li>

            <li>
                <a href="#" class="text-decoration-none d-flex justify-content-between align-items-center">
                    Gestion des Visites
                    <i class="submenu-icon fa fa-chevron-right"></i>
                </a>
                <ul class="submenu">
                    <li><a href="#" class="text-decoration-none">Ajouter une Visite</a></li>
                    <li><a href="#" class="text-decoration-none">Consulter les Visites</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>
<!-- Formulaire de modification -->
<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <br><br><br><br>
            <div class="card shadow p-4 rounded-3">
                <h2 class="text-center mb-4">Ajouter les informations</h2>

                <form action="page_ajout_employes.php" method="post">
                    <!-- Champ Nom -->
                    <div class="mb-3">
                        <label for="nom" class="form-label fw-bold">Nom</label>
                        <input type="text" class="form-control" id="nom" name="nom" placeholder="Entrez le nom" required>
                    </div>

                    <!-- Champ Prénom -->
                    <div class="mb-3">
                        <label for="prenom" class="form-label fw-bold">Prénom</label>
                        <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Entrez le prénom" required>
                    </div>

                    <!-- Champ Téléphone -->
                    <div class="mb-3">
                        <label for="telephone" class="form-label fw-bold">Téléphone</label>
                        <input type="tel" class="form-control" id="telephone" name="telephone" placeholder="5432" pattern="^[0-9]{4}$" required>
                        <small class="form-text text-muted">Format: 4 chiffres</small>
                    </div>

                    <!-- Champ Login -->
                    <div class="mb-3">
                        <label for="login" class="form-label fw-bold">Login</label>
                        <input type="text" class="form-control" id="login" name="login" placeholder="Entrez un login" required>
                    </div>

                    <!-- Champ Mot de passe -->
                    <div class="mb-3">
                        <label for="motDePasse" class="form-label fw-bold">Mot de passe</label>
                        <input type="text" class="form-control" id="motDePasse" name="motDePasse" placeholder="Entrez un mot de passe" required>
                    </div>

                    <!-- Bouton de soumission -->
                    <div class="text-center position-relative">
                        <button type="submit" class="btn btn-primary fw-bold px-4">
                            <i class="fas fa-save"></i>&nbsp;&nbsp;Enregistrer
                        </button>
                    </div>
                </form>
                    <!-- Bouton Annuler en dehors du formulaire principal -->
                <form method="post" action="page_employes.php?page=1">
                    <div class="position-absolute bottom-0 end-0 mb-4 me-3">
                        <button type="submit" class="btn btn-danger fw-bold px-4">
                            <i class="fas fa-times text-white"></i>&nbsp;&nbsp;Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="mt-5 text-center py-3 text-white">
    <p>&copy; 2024 DataBridge. Tous droits r&eacute;serv&eacute;s.</p>
</footer>


<!-- Modal Bootstrap Ok-->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                L'insertion de l'employé a été effectuée avec succès.
            </div>
            <div class="modal-footer">
                <form method="post" action="page_employes.php?page=1">
                    <button type="button" class="btn btn-success" id="closeModalButton" data-bs-dismiss="modal">Fermer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Bootstrap NOk-->
<div class="modal fade" id="deleteKoModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Erreur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                L'insertion de l'employé a échoué.<br>Veuillez réessayer.
            </div>
            <div class="modal-footer">
                <form method="post" action="page_ajout_employes.php">
                    <button type="button" class="btn btn-danger" id="closeModalButton" data-bs-dismiss="modal">Fermer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript files (Popper.js & Bootstrap JS) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

<!-- Fichier JS externe -->
<script src="../javaScript/scriptBurger.js"></script>
<script src="../javaScript/scriptRedirectionPE.js"></script>
</html>

