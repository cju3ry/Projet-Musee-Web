<?php
include("../../fonction/fonctionsAuthentification.php");
include("../../fonction/fonctionInsert.php");
include("../../fonction/fonctionsFiltres.php");
session_start();

if(isset($_SESSION['loginAdmin']) && $_SESSION['loginAdmin'] != "") {
        $login = $_SESSION['loginAdmin'];
        $admin = true;
    } elseif (isset($_SESSION["loginEmploye"]) && $_SESSION['loginEmploye'] != "") {
        $login = $_SESSION["loginEmploye"];
    } else {
        header('Location: ../../index.php');
        exit();
    }
    
if (isset($_SESSION["insertionOk"]) && $_SESSION["insertionOk"] === true) {
    echo "<script src='../../javaScript/scriptInsertion/scriptInsertionEmpOk.js'></script>";
    unset($_SESSION["insertionOk"]);
}
if (isset($_SESSION["insertionOk"]) && $_SESSION["insertionOk"] === false)
{
    echo "<script src='../../javaScript/scriptInsertion/scriptInsertionEmpKo.js'></script>";
    unset($_SESSION["insertionOk"]);
}
try {
    $pdo = connecterBd();
    $expositionsId = getIdExposition($pdo);
    $conferencierId = getIdConferencier($pdo);
    $employeId = getIdEmployeAjout($pdo);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $dateVisite = $_POST['dateVisite'] ?? null;
        $heureDebutVisite = $_POST['heureDebutVisite'] ?? null;
        $intituleVisite = $_POST['intituleVisite'] ?? null;
        $numTelVisite = $_POST['numTelVisite'] ?? null;
        $idExposition = $_POST['idExposition'] ?? null;
        $idConferencier = $_POST['idConferencier'] ?? null;
        $idEmploye = $_POST['idEmploye'] ?? null;
        
        if (!empty($dateVisite) && !empty($heureDebutVisite) && !empty($intituleVisite) && !empty($numTelVisite) && !empty($idExposition) && !empty($idConferencier) && !empty($idEmploye)) {
            try {
                $dateVisite = htmlspecialchars($dateVisite);
                $heureDebutVisite = htmlspecialchars($heureDebutVisite);
                $intituleVisite = htmlspecialchars($intituleVisite);
                $numTelVisite = htmlspecialchars($numTelVisite);
                $idExposition = htmlspecialchars($idExposition);
                $idConferencier = htmlspecialchars($idConferencier);
                $idEmploye = htmlspecialchars($idEmploye);
                
                $dateVisite = htmlentities($dateVisite, ENT_QUOTES);
                $heureDebutVisite = htmlentities($heureDebutVisite, ENT_QUOTES);
                $intituleVisite = htmlentities($intituleVisite, ENT_QUOTES);
                $numTelVisite = htmlentities($numTelVisite, ENT_QUOTES);
                $idExposition = htmlentities($idExposition, ENT_QUOTES);
                $idConferencier = htmlentities($idConferencier, ENT_QUOTES);
                $idEmploye = htmlentities($idEmploye, ENT_QUOTES);
                
                $insertionOk =  insererVisite(
                    $pdo,
                    $dateVisite,
                    $heureDebutVisite,
                    $intituleVisite,
                    $numTelVisite,
                    $idExposition,
                    $idConferencier,
                    $idEmploye
                );
                
                $_SESSION["insertionOk"] = $insertionOk;
                header('Location: page_ajout_visites.php');
            } catch (Exception $e) {
                $insertionOk = false;
                $_SESSION["insertionOk"] = $insertionOk;
                header('Location: page_ajout_visites.php');
            }
        } else {
            header('Location: page_ajout_visites.php');
            exit();
        }
    }
}catch (PDOException $e) {
    echo "Erreur de connexion à la base de données " . $e->getMessage();
    header('Location: ../erreurConnexion.php');
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
    <link rel="stylesheet" href="../../CSS/stylePage.css">

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
            <img src="../../images/logo.png" class="logo-size me-2 w-25" alt="Logo">
            <a class="navbar-brand logo-size mb-0 fs-1 text-white" href="#">DataBridge</a>
        </div>
        <!-- Bouton et Login -->
        <div class="text-center">
            <span class="me-3 mt-3 text-white"><?php echo $login;?></span><br>
            <form method="post" action="../logout.php">
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
                    <a href="<?php echo '../accueil/accueil_' . ($admin ? 'admin' : 'employe') . '.php'; ?>" class="text-decoration-none d-flex justify-content-between align-items-center">
                    Accueil
                    <i class="fas fa-home"></i>
                </a>
            </li>
            <?php if ($admin): ?>
                <li>
                    <a href="#" class="text-decoration-none d-flex justify-content-between align-items-center">
                        Gestion des Comptes Employés
                        <i class="submenu-icon fa fa-chevron-right"></i>
                    </a>
                    <ul class="submenu">
                        <li><a href="../employes/page_ajout_employes.php" class="text-decoration-none">Ajouter un employé</a></li>
                        <li><a href="../employes/page_employes.php?page=1" class="text-decoration-none">Consulter les employés</a></li>
                    </ul>
                </li>
            <?php endif; ?>
            <li>
                <a href="#" class="text-decoration-none d-flex justify-content-between align-items-center">
                    Gestion des Conférenciers
                    <i class="submenu-icon fa fa-chevron-right"></i>
                </a>
                <ul class="submenu">
                    <li><a href="../conferenciers/page_ajout_conferenciers.php" class="text-decoration-none">Ajouter un Conférencier</a></li>
                    <li><a href="../conferenciers/page_conferenciers.php?page=1" class="text-decoration-none">Consulter les Conférenciers</a></li>
                </ul>
            </li>

            <li>
                <a href="#" class="text-decoration-none d-flex justify-content-between align-items-center">
                    Gestion des Expositions
                    <i class="submenu-icon fa fa-chevron-right"></i>
                </a>
                <ul class="submenu">
                    <li><a href="../expositions/page_ajout_expositions.php" class="text-decoration-none">Ajouter une Exposition</a></li>
                    <li><a href="../expositions/page_expositions.php?page=1" class="text-decoration-none">Consulter les Expositions</a></li>
                </ul>
            </li>

            <li>
                <a href="#" class="text-decoration-none d-flex justify-content-between align-items-center">
                    Gestion des Visites
                    <i class="submenu-icon fa fa-chevron-right"></i>
                </a>
                <ul class="submenu">
                    <li><a href="../visites/page_ajout_visites.php" class="text-decoration-none">Ajouter une Visite</a></li>
                    <li><a href="../visites/page_visites.php?page=1" class="text-decoration-none">Consulter les Visites</a></li>
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

                <form method="post" action="page_ajout_visites.php" id="visiteForm">

                    <!-- Champ Date Visite -->
                    <div class="mb-3">
                        <label for="dateVisite" class="form-label fw-bold">Date de la Visite</label>
                        <input type="date" class="form-control" id="dateVisite" name="dateVisite" required>
                    </div>

                    <!-- Champ Heure Début Visite -->
                    <div class="mb-3">
                        <label for="heureDebutVisite" class="form-label fw-bold">Heure de Début</label>
                        <input type="time" class="form-control" id="heureDebutVisite" name="heureDebutVisite" required>
                    </div>

                    <!-- Champ Intitulé Visite -->
                    <div class="mb-3">
                        <label for="intituleVisite" class="form-label fw-bold">Intitulé de la Visite</label>
                        <input type="text" class="form-control" id="intituleVisite" name="intituleVisite" maxlength="100" placeholder="Entrez l'intitulé de la visite" required>
                    </div>

                    <!-- Champ Numéro de Téléphone -->
                    <div class="mb-3">
                        <label for="numTelVisite" class="form-label fw-bold">Numéro de Téléphone</label>
                        <input type="text" class="form-control" id="numTelVisite" name="numTelVisite" maxlength="10" pattern="\d{10}" placeholder="Entrez le numéro de téléphone (ex : 0600000000)" required>
                    </div>

                    <!-- Champ ID Exposition -->
                    <div class="mb-3">
                        <label for="idExposition" class="form-label fw-bold">ID Exposition</label>
                        <select class="form-select" id="idExposition" name="idExposition" required>
                            <option value="">Sélectionnez une exposition</option>
                            <?php
                            // Afficher les options pour chaque exposition disponible
                            foreach ($expositionsId as $expo) {
                                echo "<option value='" . $expo['idExposition'] . "'>" . $expo['idExposition'] . " - " . $expo['intitule'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Champ ID Conférencier -->
                    <div class="mb-3">
                        <label for="idConferencier" class="form-label fw-bold">ID Conférencier</label>
                        <select class="form-select" id="idConferencier" name="idConferencier" required>
                            <option value="">Sélectionnez un Conférencier</option>
                            <?php
                            // Afficher les options pour chaque exposition disponible
                            foreach ($conferencierId as $conf) {
                                echo "<option value='" . $conf['idConferencier'] . "'>" . $conf['idConferencier'] . " - "
                                    . $conf['nomConferencier'] ." - " . $conf['prenomConferencier'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Champ ID Employé -->
                    <div class="mb-3">
                        <label for="idEmploye" class="form-label fw-bold">ID Employé</label>
                        <select class="form-select" id="idEmploye" name="idEmploye" required>
                            <option value="">Sélectionnez un Employé</option>
                            <?php
                            // Afficher les options pour chaque exposition disponible
                            foreach ($employeId as $emp) {
                                echo "<option value='" . $emp['idEmploye'] . "'>" . $emp['idEmploye'] . " - "
                                    . $emp['nomEmploye'] ." - " . $emp['prenomEmploye'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Bouton de soumission -->
                    <div class="text-center position-relative">
                        <button type="submit" class="btn btn-primary fw-bold px-4">
                            <i class="fas fa-save"></i>&nbsp;&nbsp;Enregistrer
                        </button>
                    </div>
                </form>
                <!-- Bouton Annuler en dehors du formulaire principal -->
                <form method="post" action="page_visites.php?page=1" id="visiteFormSupp">
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
                L'insertion de la visite a été effectuée avec succès.
            </div>
            <div class="modal-footer">
                <form method="post" action="page_visites.php?page=1">
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
                L'insertion de visite a échoué. Veuillez vérifier les disponibilitées du conférencier et/ou de l'exposition choisie.<br>Veuillez réessayer.
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
<script src="../../javaScript/scriptGeneraux/scriptBurger.js"></script>
<script src="../../javaScript/scriptRedirection/scriptRedirectionPV.js"></script>
<script src="../../javaScript/scriptValidation/scriptValidationMotsCles.js"></script>
<script src="../../javaScript/scriptGeneraux/scriptPreRemplissageFormulaire.js"></script>
</html>


