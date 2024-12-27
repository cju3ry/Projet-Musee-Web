<?php
session_start();
include ("../fonction/fonctionsModification.php");
include ("../fonction/fonctionsAuthentification.php");

if (!isset($_SESSION['loginAdmin'])) {
    header("location: ../index.php");
    exit();
}

$nom = isset($_POST['nom']) ? $_POST['nom'] : (isset($_POST['nom_employe']) ? $_POST['nom_employe'] : '');
$prenom = isset($_POST['prenom']) ? $_POST['prenom'] : (isset($_POST['prenom_employe']) ? $_POST['prenom_employe'] : '');
$telephone = isset($_POST['telephone']) ? $_POST['telephone'] : (isset($_POST['telephone_employe']) ? $_POST['telephone_employe'] : '');
$idEmploye = isset($_POST['id_employe']) ? $_POST['id_employe'] : '';
$login = isset($_POST['login']) ? $_POST['login'] : (isset($_POST['login_employe']) ? $_POST['login_employe'] : '');
$pdo = connecterBd();

if (isset($_SESSION["modificationOk"])) {
    if ($_SESSION["modificationOk"]) {
        echo "<script src='../javaScript/scriptModificationEmpOk.js'></script>";
    } else {
        echo "<script src='../javaScript/scriptModificationEmpKo.js'></script>";
    }
    unset($_SESSION["modificationOk"]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actionModification']) && $_POST['actionModification'] === 'modificationEmploye') {
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $telephone = $_POST['telephone'] ?? '';
    $idEmploye = $_POST['id_employe'] ?? '';
    $login = $_POST['login'] ?? '';
    $pwd = null;

    // Vérifiez si le mot de passe doit être modifié
    if (isset($_POST['modifierMotDePasse']) && $_POST['modifierMotDePasse'] === 'on') {
        $pwd = $_POST['motDePasse'] ?? '';
        if (empty($pwd)) {
            echo "<div class='alert alert-danger'>Le mot de passe est requis lorsque vous cochez 'Modifier le mot de passe'.</div>";
            exit; // Arrêt de l'exécution pour éviter une mise à jour incomplète.
        }
        $pwd = md5($pwd); 
    }

    if (!empty($nom) && !empty($prenom) && !empty($telephone) && !empty($idEmploye)) {
        $updateEmpOk = modifierEmploye($pdo, $idEmploye, $nom, $prenom, $telephone, $login, $pwd);
        $_SESSION["modificationOk"] = $updateEmpOk;
        header('Location: page_modif_employes.php');
        exit;
    } else {
        echo "<div class='alert alert-danger'>Tous les champs sont requis. Veuillez les remplir correctement.</div>";
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
                    <li><a href="page_conferenciers.php?page=1" class="text-decoration-none">Consulter les Conférenciers</a></li>
                </ul>
            </li>

            <li>
                <a href="#" class="text-decoration-none d-flex justify-content-between align-items-center">
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
<!-- Formulaire de modification -->
<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <br><br><br><br>
            <div class="card shadow p-4 rounded-3">
                <h2 class="text-center mb-4">Modifier les informations</h2>

                <form action="page_modif_employes.php" method="post">
                    <input type="hidden" name="actionModification" value="modificationEmploye">
                    <input type="hidden" name="id_employe" value="<?php echo htmlentities($idEmploye, ENT_QUOTES); ?>">

                    <!-- Champ Nom -->
                    <div class="mb-3">
                        <label for="nom" class="form-label fw-bold">Nom</label>
                        <input type="text" class="form-control" id="nom" name="nom" placeholder="Entrez le nom"
                               value="<?php echo htmlentities($_POST['nom'] ?? $nom, ENT_QUOTES); ?>" required>
                    </div>

                    <!-- Champ Prénom -->
                    <div class="mb-3">
                        <label for="prenom" class="form-label fw-bold">Prénom</label>
                        <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Entrez le prénom"
                               value="<?php echo htmlentities($_POST['prenom'] ?? $prenom, ENT_QUOTES); ?>" required>
                    </div>

                    <!-- Champ Téléphone -->
                    <div class="mb-3">
                        <label for="telephone" class="form-label fw-bold">Téléphone</label>
                        <input type="tel" class="form-control" id="telephone" name="telephone" placeholder="5432" pattern="^[0-9]{4}$"
                               value="<?php echo htmlentities($_POST['telephone'] ?? $telephone, ENT_QUOTES); ?>" required>
                        <small class="form-text text-muted">Format: 4 chiffres</small>
                    </div>

                    <!-- Champ Login -->
                    <div class="mb-3">
                        <label for="login" class="form-label fw-bold">Login</label>
                        <input type="text" class="form-control" id="login" name="login" placeholder="Entrez le login"
                               value="<?php echo htmlentities($_POST['login'] ?? $login, ENT_QUOTES); ?>" required>
                    </div>

                    <!-- Checkbox pour modifier le mot de passe -->
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="modifierMotDePasse" name="modifierMotDePasse">
                        <label class="form-check-label" for="modifierMotDePasse">Modifier le mot de passe</label>
                    </div>

                    <!-- Champ Mot de passe -->
                    <div class="mb-3" id="motDePasseContainer" style="display: none;">
                        <label for="motDePasse" class="form-label fw-bold">Mot de passe</label>
                        <input type="password" class="form-control" id="motDePasse" name="motDePasse" placeholder="Entrez le mot de passe">
                    </div>

                    <!-- Boutons -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary fw-bold px-4">
                            <i class="fas fa-save"></i>&nbsp;&nbsp;Enregistrer
                        </button>
                        <a href="page_employes.php?page=1" class="btn btn-danger fw-bold px-4">
                            <i class="fas fa-times text-white "></i>&nbsp;&nbsp;Annuler
                        </a>
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
<div class="modal fade" id="updateOkModal" tabindex="-1" aria-labelledby="updateOkModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateOkModalLabel">Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                La modification de l'employé a été effectuée avec succès.
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
<div class="modal fade" id="updateKoModal" tabindex="-1" aria-labelledby="updateKoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateKoModalLabel">Erreur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                La modification de l'employé a échoué.<br>Veuillez réessayer.
            </div>
            <div class="modal-footer">
                <form method="post" action="page_employes.php?page=1">
                    <button type="submit" class="btn btn-danger" id="closeModalButton" data-bs-dismiss="modal">Fermer</button>
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
<script src="../javaScript/scriptAffichageMdp.js"></script>
<script src="../javaScript/scriptChampMdp.js"></script>



</body>
</html>