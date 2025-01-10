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
    $idConferencier = "";
    $pdo = connecterBd();
    $idConferencier = "";
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['id_conferencier']) && !empty($_POST['id_conferencier'])) {
            $idConferencier = $_POST['id_conferencier'];
        } else {
        }
    } else {
        echo "Méthode de requête incorrecte.";
    }

    $tableauModifConferencier = rechercheConferencierParId($pdo,$idConferencier);
    if (isset($_SESSION["modificationOk"])) {
        if ($_SESSION["modificationOk"]) {
            echo "<script src='../../javaScript/scriptModification/scriptModificationEmpOk.js'></script>";
        } else {
            echo "<script src='../../javaScript/scriptModification/scriptModificationEmpKo.js'></script>";
        }
        unset($_SESSION["modificationOk"]);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actionModification']) && $_POST['actionModification'] === 'modificationConferencier') {
        $idConferencier = $_POST['idConferencier'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $estEmploye = $_POST['estEmploye'];
        $specialites = $_POST['specialites'];
        $indisponibilites = $_POST['indisponibilites'];
        $telephone = $_POST['telephone'];
        $idConferencier = htmlspecialchars($idConferencier);
        $nom = htmlspecialchars($nom);
        $prenom = htmlspecialchars($prenom);
        $estEmploye = htmlspecialchars($estEmploye);
        $specialites = htmlspecialchars($specialites);
        $indisponibilites = htmlspecialchars($indisponibilites);
        $idConferencier = htmlentities($idConferencier, ENT_QUOTES);
        $nom = htmlentities($nom, ENT_QUOTES);
        $prenom = htmlentities($prenom, ENT_QUOTES);
        $estEmploye = htmlentities($estEmploye, ENT_QUOTES);
        $specialites = htmlentities($specialites, ENT_QUOTES);
        $indisponibilites = htmlentities($indisponibilites, ENT_QUOTES);
        $conferencierActuel = rechercheConferencierParId($pdo, $idConferencier);
        try {
            if($conferencierActuel === $_SESSION['saveConferencier']) {
            $updateExpoOk = modifierConferencier($pdo, $idConferencier, $nom, $prenom,$telephone, $estEmploye, $specialites, $indisponibilites);
            $_SESSION["modificationOk"] = $updateExpoOk;
            header('Location: page_modif_conferenciers.php');
            exit;
            } else {
                echo '<script src="../../javaScript/scriptModification/scriptModificationErreur.js"></script>';
            }

        } catch (PDOException $e) {
            echo "Erreur lors de la modification de l'exposition : " . $e->getMessage();
        }
    } else {
        $_SESSION['saveConferencier'] = rechercheConferencierParId($pdo, $idConferencier);
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

                <form action="page_modif_conferenciers.php" method="post" id="formConferencier">
                    <input type="hidden" name="actionModification" value="modificationConferencier"  >
                    <input type="hidden" name="idConferencier" value="<?php echo $tableauModifConferencier['idConferencier']; ?>">


                    <!--Champ nom-->
                    <div class="mb-3">
                        <label for="nom" class="form-label fw-bold">Nom</label>
                        <input type="text" class="form-control" id="nom" name="nom" placeholder="Entrez le nom"
                               value="<?php echo html_entity_decode($tableauModifConferencier['nomConferencier'], ENT_QUOTES, 'UTF-8'); ?>" maxlength="25" required>
                    </div>
                    <!--Champ prenom-->
                    <div class="mb-3">
                        <label for="prenom" class="form-label fw-bold">prenom</label>
                        <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Entrez le prenom"
                               value="<?php echo html_entity_decode($tableauModifConferencier['prenomConferencier'], ENT_QUOTES, 'UTF-8'); ?>" maxlength="25" required>
                    </div>
                    <!-- Champ numTel -->
                    <div class="mb-3">
                        <label for="telephone" class="form-label fw-bold">Numéro de Télèphone de la Visite</label>
                        <input type="text" class="form-control" id="telephone" name="telephone" placeholder="Entrez le Numéro de Téléphone"
                               value="<?php echo html_entity_decode($tableauModifConferencier['telephone'], ENT_QUOTES, 'UTF-8'); ?>" pattern="\d{10}" title="Le numéro de téléphone doit contenir exactement 10 chiffres." required>
                    </div>
                    <!--Champ est employe-->
                    <div class="mb-3">
                        <label for="estEmploye" class="form-label fw-bold">Est Employe ?</label>
                        <input type="text" class="form-control" id="estEmploye" name="estEmploye" placeholder="Entrez OUI ou NON"
                               value="<?php echo html_entity_decode($tableauModifConferencier['estEmploye'], ENT_QUOTES, 'UTF-8'); ?>" maxlength="3" required>
                    </div>
                    <!--Champ spécialités-->
                    <div class="mb-3">
                        <label for="specialites" class="form-label fw-bold">Specialité(s)</label>
                        <input type="text" class="form-control" id="specialites" name="specialites" placeholder="Entrez la ou les spécialitée(s)"
                               value="<?php echo html_entity_decode($tableauModifConferencier['specialites'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <!--Champ indisponibilites-->
                    <div class="mb-3">
                        <label for="indisponibilites" class="form-label fw-bold">Indisponibilité(s)</label>
                        <input type="text" class="form-control" id="indisponibilites" name="indisponibilites" placeholder="Entrez la ou les Indisponibilitée(s)"
                               value="<?php echo html_entity_decode($tableauModifConferencier['indisponibilites'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <!-- Boutons -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary fw-bold px-4">
                            <i class="fas fa-save"></i>&nbsp;&nbsp;Enregistrer
                        </button>
                        <a href="page_conferenciers.php?page=1" class="btn btn-danger fw-bold px-4">
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
                La modification du conférencier a été effectuée avec succès.
            </div>
            <div class="modal-footer">
                <form method="post" action="page_conferenciers.php?page=1">
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
                La modification du conférencier a échoué.<br>Veuillez réessayer.
            </div>
            <div class="modal-footer">
                <form method="post" action="page_conferenciers.php?page=1">
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
                <form method="post" action="page_conferenciers.php?page=1">
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
<script src="../../javaScript/scriptRedirection/scriptRedirectionPC.js"></script>
<script src="../../javaScript/scriptValidation/validationIndisponibilites.js"></script>
<script src="../../javaScript/scriptValidation/validationEstEmploye.js"></script>



</body>
</html>