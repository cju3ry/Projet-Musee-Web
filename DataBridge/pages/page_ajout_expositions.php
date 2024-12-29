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

    $intituleExpo = $_POST['intitule'] ?? null;
    $periodeDebut = $_POST['periodeDebut'] ?? null;
    $periodeFin = $_POST['periodeFin'] ?? null;
    $nombreOeuvres = $_POST['nombreOeuvres'] ?? null;
    $resume = $_POST['resume'] ?? null;
    $debutExpoTemp = $_POST['debutExpoTemp'] ?? null;
    $finExpoTemp = $_POST['finExpoTemp'] ?? null;
    $motsCles = $_POST['motsCles'] ?? null;
    $intituleExpo = htmlentities($intituleExpo);
    $periodeDebut = htmlentities($periodeDebut);
    $periodeFin = htmlentities($periodeFin);
    $nombreOeuvres = htmlentities($nombreOeuvres);
    $resume = htmlentities($resume);
    $debutExpoTemp = htmlentities($debutExpoTemp);
    $finExpoTemp = htmlentities($finExpoTemp);
    $motsCles = htmlentities($motsCles);

    // Convertion en int
    $periodeDebut = intval($periodeDebut);
    $periodeFin = intval($periodeFin);

    if (!empty($intituleExpo) && !empty($periodeDebut) && !empty($periodeFin) && !empty($nombreOeuvres) && !empty($resume) && !empty($motsCles)) {
        try {
            insertExposition(
                $pdo,
                $intituleExpo,
                $periodeDebut,
                $periodeFin,
                $nombreOeuvres,
                $resume,
                $debutExpoTemp,
                $finExpoTemp,
                $motsCles
            );
            $insertionOk = true;
            $_SESSION["insertionOk"] = $insertionOk;
            header('Location: page_ajout_expositions.php');

        } catch (Exception $e) {
            $insertionOk = false;
            $_SESSION["insertionOk"] = $insertionOk;
            header('Location: page_ajout_expositions.php');
        }
    } else {
        header('Location: page_ajout_expositions.php');
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

                <form method="post" action="page_ajout_expositions.php" id="expositionForm">
                    <!--Champ intitulé-->
                    <div class="mb-3">
                        <label for="intitule" class="form-label fw-bold">Intitulé</label>
                        <input type="text" class="form-control" id="intitule" name="intitule" placeholder="Entrez l'intitulé" required>
                    </div>

                    <!-- Champ Période de Début -->
                    <div class="mb-3">
                        <label for="periodeDebut" class="form-label fw-bold">Période de Début</label>
                        <input type="number" max="9999" step="1" class="form-control" id="periodeDebut" name="periodeDebut" placeholder="Entrez la Période de Début (ex : 1920)" required>
                    </div>

                    <!-- Champ Période de Fin -->
                    <div class="mb-3">
                        <label for="periodeFin" class="form-label fw-bold">Période de Fin</label>
                        <input type="number" max="9999" step="1" class="form-control" id="periodeFin" name="periodeFin" placeholder="Entrez la Période de Fin (ex : 2020)" required>
                    </div>

                    <!-- Champ Nombre Oeuvres -->
                    <div class="mb-3">
                        <label for="nombreOeuvres" class="form-label fw-bold">Nombre d'Oeuvres</label>
                        <input type="number" min="1" max="9999" step="1" class="form-control" id="nombreOeuvres" name="nombreOeuvres" placeholder="Entrez le Nombre d'Oeuvres (ex : 20)" required>
                    </div>

                    <!-- Champ Résumé -->
                    <div class="mb-3">
                        <label for="resume" class="form-label fw-bold">Résumé</label>
                        <input type="text" class="form-control" id="resume" name="resume" placeholder="Entrez le Résumé" required>
                    </div>

                    <!-- Champ debut exposition -->
                    <div class="mb-3">
                        <label for="debutExpoTemp" class="form-label fw-bold">Debut Exposition</label>
                        <input type="date" class="form-control" id="debutExpoTemp" name="debutExpoTemp" placeholder="Entrez le Début de l'Exposition (non obligatoire si exposition permanente)">
                    </div>

                    <!-- Champ fin exposition -->
                    <div class="mb-3">
                        <label for="finExpoTemp" class="form-label fw-bold">Fin Exposition</label>
                        <input type="date" class="form-control" id="finExpoTemp" name="finExpoTemp" placeholder="Entrez la Fin de l'Exposition (non obligatoire si exposition permanente)">
                    </div>

                    <!-- Champ mot(s) clé(s) -->
                    <div class="mb-3">
                        <label for="motsCles" class="form-label fw-bold">Mot(s) Clé(s)</label>
                        <input
                                type="text"
                                class="form-control"
                                id="motsCles"
                                name="motsCles"
                                placeholder="Entrez le(s) mot(s) clé(s) séparés par des , "
                                oninput="validateMotsCles(this)"
                                required
                        >
                        <small id="motsClesHelp" class="text-muted">Vous pouvez entrer jusqu'à 10 mots-clés maximum.</small>
                    </div>

                    <!-- Bouton de soumission -->
                    <div class="text-center position-relative">
                        <button type="submit" class="btn btn-primary fw-bold px-4">
                            <i class="fas fa-save"></i>&nbsp;&nbsp;Enregistrer
                        </button>
                    </div>
                </form>
                <!-- Bouton Annuler en dehors du formulaire principal -->
                <form method="post" action="page_expositions.php?page=1">
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
                L'insertion de l'exposition a été effectuée avec succès.
            </div>
            <div class="modal-footer">
                <form method="post" action="page_expositions.php?page=1">
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
                L'insertion de l'exposition a échoué.<br>Veuillez réessayer.
            </div>
            <div class="modal-footer">
                <form method="post" action="page_ajout_expositions.php">
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
<script src="../javaScript/scriptRedirectionPEx.js"></script>
<script src="../javaScript/scriptValidationMotsCles.js"></script>
<script src="../javaScript/scriptPreRemplissageForumlaire.js"></script>


</html>


