<?php
session_start();
include ("../../fonction/fonctionsModification.php");
include ("../../fonction/fonctionsAuthentification.php");
include ("../../fonction/fonctionsFiltres.php");


if(isset($_SESSION['loginAdmin']) && $_SESSION['loginAdmin'] != "") {
        $login = $_SESSION['loginAdmin'];
        $admin = true;
    } elseif (isset($_SESSION["loginEmploye"]) && $_SESSION['loginEmploye'] != "") {
        $login = $_SESSION["loginEmploye"];
    } else {
        header('Location: ../../index.php');
        exit();
    }
try {
    $idVisite = "";
    $pdo = connecterBd();
    $expositionsId = getIdExposition($pdo);
    $conferencierId = getIdConferencier($pdo);
    $employeId = getIdEmployeAjout($pdo);
    $idConferencier = "";
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['id_Visite']) && !empty($_POST['id_Visite'])) {
            $idVisite = $_POST['id_Visite'];
        } else {
        }
    } else {
        echo "Méthode de requête incorrecte.";
    }

    $tableauModifVisite = recherheVisiteParId($pdo,$idVisite);
    if (isset($_SESSION["modificationOk"])) {
        if ($_SESSION["modificationOk"]) {
            echo "<script src='../../javaScript/scriptModification/scriptModificationEmpOk.js'></script>";
        } else {
            echo "<script src='../../javaScript/scriptModification/scriptModificationEmpKo.js'></script>";
        }
        unset($_SESSION["modificationOk"]);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actionModification']) && $_POST['actionModification'] === 'modificationVisite') {

        $idVisite = $_POST['id_visite'];
        $dateVisite = $_POST['dateVisite'];
        $heureDebutVisite = $_POST['heureDebutVisite'];
        $intituleVisite = $_POST['intituleVisite'];
        $numTelVisite = $_POST['numTelVisite'];
        $idExposition = $_POST['idExposition'];
        $idConferencier = $_POST['IdConferencier'];
        $idEmploye = $_POST['IdEmploye'];

        $idVisite = htmlspecialchars($idVisite);
        $dateVisite = htmlspecialchars($dateVisite);
        $heureDebutVisite = htmlspecialchars($heureDebutVisite);
        $intituleVisite = htmlspecialchars($intituleVisite);
        $numTelVisite = htmlspecialchars($numTelVisite);
        $idExposition = htmlspecialchars($idExposition);
        $idConferencier = htmlspecialchars($idConferencier);
        $idEmploye = htmlspecialchars($idEmploye);

        $idVisite = htmlentities($idVisite, ENT_QUOTES);
        $dateVisite = htmlentities($dateVisite, ENT_QUOTES);
        $heureDebutVisite = htmlentities($heureDebutVisite, ENT_QUOTES);
        $intituleVisite = htmlentities($intituleVisite, ENT_QUOTES);
        $numTelVisite = htmlentities($numTelVisite, ENT_QUOTES);
        $idExposition = htmlentities($idExposition, ENT_QUOTES);
        $idConferencier = htmlentities($idConferencier, ENT_QUOTES);
        $idEmploye = htmlentities($idEmploye, ENT_QUOTES);
        
        $visiteActuelle = recherheVisiteParId($pdo, $idVisite);
        
        if($visiteActuelle === $_SESSION['saveVisite']) {
            try {
                $updateExpoOk = modifierVisite($pdo, $idVisite, $dateVisite, $heureDebutVisite, $intituleVisite, $numTelVisite, $idExposition, $idConferencier, $idEmploye);
                $_SESSION["modificationOk"] = $updateExpoOk;
                header('Location: page_modif_visites.php');
                exit;
    
            } catch (PDOException $e) {
                echo "Erreur lors de la modification de la visites : " . $e->getMessage();
            }
        } else {
            echo '<script src="../../javaScript/scriptModification/scriptModificationErreur.js"></script>';
        }
    } else {
        $_SESSION['saveVisite'] = recherheVisiteParId($pdo, $idVisite);
    }
} catch (PDOException $e) {
    header('Location: ../erreurConnexion.php');
    exit;
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
                <h2 class="text-center mb-4">Modifier les informations</h2>

                <form action="page_modif_visites.php" method="post" id="formConferencier">
                    <input type="hidden" name="actionModification" value="modificationVisite"  >
                    <input type="hidden" name="id_visite" value="<?php echo html_entity_decode($_POST['id_Visite'], ENT_QUOTES, 'UTF-8'); ?>">

                    <!--Champ date visite-->
                    <div class="mb-3">
                        <label for="dateVisite" class="form-label fw-bold">Date de la Visite</label>
                        <input type="date" class="form-control" id="dateVisite" name="dateVisite" placeholder="Entrez la date de la visite"
                               value="<?php echo html_entity_decode($tableauModifVisite['dateVisite'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <!-- Champ Heure Début Visite -->
                    <div class="mb-3">
                        <label for="heureDebutVisite" class="form-label fw-bold">Heure du Debut de la Visite</label>
                        <input type="time" class="form-control" id="heureDebutVisite" name="heureDebutVisite" placeholder="Entrez l'Heure de Debut de la Visite"
                               value="<?php echo html_entity_decode($tableauModifVisite['heureDebutVisite'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <!-- Champ Intitulé Visite -->
                    <div class="mb-3">
                        <label for="intituleVisite" class="form-label fw-bold">Intitule de la Visite</label>
                        <input type="text" class="form-control" id="intituleVisite" name="intituleVisite" placeholder="Entrez l'intitulé de la visite" maxlength="100"
                               value="<?php echo html_entity_decode($tableauModifVisite['intituleVisite'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <!-- Champ numTelVisite -->
                    <div class="mb-3">
                        <label for="numTelVisite" class="form-label fw-bold">Numéro de Télèphone de la Visite</label>
                        <input type="text" class="form-control" id="numTelVisite" name="numTelVisite" placeholder="Entrez le Numéro de Télèphone de la Visite" maxlength="10"
                               value="<?php echo html_entity_decode($tableauModifVisite['numTelVisite'], ENT_QUOTES, 'UTF-8'); ?>" pattern="\d{10}" title="Le numéro de téléphone doit contenir exactement 10 chiffres." required>
                    </div>

                    <!-- Champ ID Exposition -->
                    <div class="mb-3">
                        <label for="idExposition" class="form-label fw-bold">ID Exposition</label>
                        <select class="form-select" id="idExposition" name="idExposition" required>
                            <option value="">Sélectionnez une exposition</option>
                            <?php
                            // Afficher les options pour chaque exposition disponible
                            foreach ($expositionsId as $expo) {
                                $selected = ($expo['idExposition'] == $tableauModifVisite['idExposition']) ? 'selected' : '';
                                echo "<option value='" . $expo['idExposition'] . "' $selected>" . $expo['idExposition'] . " - " . html_entity_decode($expo['intitule'], ENT_QUOTES, 'UTF-8') . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Champ ID Conférencier -->
                    <div class="mb-3">
                        <label for="IdConferencier" class="form-label fw-bold">ID Conférencier</label>
                        <select class="form-select" id="IdConferencier" name="IdConferencier" required>
                            <option value="">Sélectionnez un Conférencier</option>
                            <?php
                            // Afficher les options pour chaque conférencier disponible
                            foreach ($conferencierId as $conf) {
                                $selected = ($conf['idConferencier'] == $tableauModifVisite['IdConferencier']) ? 'selected' : '';
                                echo "<option value='" . $conf['idConferencier'] . "' $selected>" . $conf['idConferencier'] . " - " . html_entity_decode($conf['nomConferencier'], ENT_QUOTES, 'UTF-8') . " - " . $conf['prenomConferencier'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Champ ID Employe -->
                    <div class="mb-3">
                        <label for="IdEmploye" class="form-label fw-bold">ID Employe</label>
                        <select class="form-select" id="IdEmploye" name="IdEmploye" required>
                            <option value="">Sélectionnez un Employe</option>
                            <?php
                            // Afficher les options pour chaque employe disponible
                            foreach ($employeId as $emp) {
                                $selected = ($emp['idEmploye'] == $tableauModifVisite['IdEmploye']) ? 'selected' : '';
                                echo "<option value='" . $emp['idEmploye'] . "' $selected>" . $emp['idEmploye'] . " - " . html_entity_decode($emp['nomEmploye'], ENT_QUOTES, 'UTF-8') . " - " . $emp['prenomEmploye'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Boutons -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary fw-bold px-4">
                            <i class="fas fa-save"></i>&nbsp;&nbsp;Enregistrer
                        </button>
                        <a href="page_visites.php?page=1" class="btn btn-danger fw-bold px-4">
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
                La modification da la visite a été effectuée avec succès.
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
<div class="modal fade" id="updateKoModal" tabindex="-1" aria-labelledby="updateKoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateKoModalLabel">Erreur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                La modification da le visite a échoué.<br>Veuillez réessayer.
            </div>
            <div class="modal-footer">
                <form method="post" action="page_visites.php?page=1">
                    <button type="submit" class="btn btn-danger" id="closeModalButton" data-bs-dismiss="modal">Fermer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Bootstrap Recharger-->
<div class="modal fade" id="rechargerPage" tabindex="-1" aria-labelledby="rechargerPageLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rechargerPageLabel">Erreur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                Il semblerait que quelqu'un ait préalablement modifié ces données. </br>Rechargez la page pour accéder aux dernières informations.
            </div>
            <div class="modal-footer">
                <form method="post" action="page_visites.php?page=1">
                    <button type="submit" class="btn btn-danger" id="closeModalButton" data-bs-dismiss="modal">Recharger</button>
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

</body>
</html>