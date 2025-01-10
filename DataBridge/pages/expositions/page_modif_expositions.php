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

$idExposition = "";
$pdo = connecterBd();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id_expo']) && !empty($_POST['id_expo'])) {
        $idExposition = $_POST['id_expo'];
        // echo "Identifiant de l'exposition reçu : " . htmlspecialchars($id_exposition);
    } else {
        // echo "Identifiant non transmis ou vide.";
    }
} else {
    echo "Méthode de requête incorrecte.";
}

// $idExposition = "E0000002";
$tableauModifExpositions = rechercheExpositionParId($pdo,$idExposition);
if (isset($_SESSION["modificationOk"])) {
    if ($_SESSION["modificationOk"]) {
        echo "<script src='../../javaScript/scriptModification/scriptModificationEmpOk.js'></script>";
    } else {
        echo "<script src='../../javaScript/scriptModification/scriptModificationEmpKo.js'></script>";
    }
    unset($_SESSION["modificationOk"]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actionModification']) && $_POST['actionModification'] === 'modificationExposition') {

        $idExposition = $_POST['idExposition'];
        $intitule = $_POST['intitule'];
        $periodeDebut = $_POST['periodeDebut'];
        $periodeFin = $_POST['periodeFin'];
        $nombreOeuvres = $_POST['nombreOeuvres'];
        $resume = $_POST['resume'];
        $debutExpoTemp = $_POST['debutExpoTemp'];
        $finExpoTemp = $_POST['finExpoTemp'];
        $motsCles = $_POST['motsCles'];
        
        $idExposition = htmlspecialchars($idExposition);
        $intitule = htmlspecialchars($intitule);
        $periodeDebut = htmlspecialchars($periodeDebut);
        $periodeFin = htmlspecialchars($periodeFin);
        $nombreOeuvres = htmlspecialchars($nombreOeuvres);
        $resume = htmlspecialchars($resume);
        $debutExpoTemp = htmlspecialchars($debutExpoTemp);
        $finExpoTemp = htmlspecialchars($finExpoTemp);
        $motsCles = htmlspecialchars($motsCles);

        $idExposition = htmlentities($idExposition, ENT_QUOTES);
        $intitule = htmlentities($intitule, ENT_QUOTES);
        $periodeDebut = htmlentities($periodeDebut, ENT_QUOTES);
        $periodeFin = htmlentities($periodeFin, ENT_QUOTES);
        $nombreOeuvres = htmlentities($nombreOeuvres, ENT_QUOTES);
        $resume = htmlentities($resume, ENT_QUOTES);
        $debutExpoTemp = htmlentities($debutExpoTemp, ENT_QUOTES);
        $finExpoTemp = htmlentities($finExpoTemp, ENT_QUOTES);
        $motsCles = htmlentities($motsCles, ENT_QUOTES);
        
        // Convertion en int
        $periodeDebut = intval($periodeDebut);
        $periodeFin = intval($periodeFin);
        $nombreOeuvres = intval($nombreOeuvres);
        
        $expositionsActuelle = rechercheExpositionParId($pdo, $idExposition);
        if($expositionsActuelle === $_SESSION['saveExpositions']) {
            try {
                    
                $updateExpoOk = modifierExposition($pdo, $idExposition, $intitule, $periodeDebut,
                    $periodeFin, $nombreOeuvres, $resume, $debutExpoTemp, $finExpoTemp,$motsCles);
                $_SESSION["modificationOk"] = $updateExpoOk;
                header('Location: page_modif_expositions.php');
                exit;
    
            } catch (PDOException $e) {
                echo "Erreur lors de la modification de l'exposition : " . $e->getMessage();
            }
        } else {
            echo '<script src="../../javaScript/scriptModification/scriptModificationErreur.js"></script>';  
        }
} else {
    $_SESSION['saveExpositions'] = rechercheExpositionParId($pdo, $idExposition);
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

                <form action="page_modif_expositions.php" method="post">
                    <input type="hidden" name="actionModification" value="modificationExposition">
                    <input type="hidden" name="idExposition" value="<?php echo $tableauModifExpositions['idExposition']; ?>">

                    <!-- Champ intitule
                    Nombre Oeuvres
                    Resume
                    Debut Exposition
                    Fin Exposition
                    Mot Cle de l'Exposition: -->
                    <!--Champ intitulé-->
                    <div class="mb-3">
                        <label for="nom" class="form-label fw-bold">Intitulé</label>
                        <input type="text" class="form-control" id="intitule" name="intitule" placeholder="Entrez l'intitulé"
                               value="<?php echo html_entity_decode($tableauModifExpositions['intitule'], ENT_QUOTES, 'UTF-8'); ?>" maxlength="100" required>
                    </div>

                    <!-- Champ Période de Début -->
                    <div class="mb-3">
                        <label for="periodeDebut" class="form-label fw-bold">Période de Début</label>
                        <input type="text" class="form-control" id="periodeDebut" name="periodeDebut" placeholder="Entrez la Période de Début"
                               value="<?php echo html_entity_decode($tableauModifExpositions['periodeDebut'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <!-- Champ Période de Fin -->
                    <div class="mb-3">
                        <label for="periodeFin" class="form-label fw-bold">Période de Fin</label>
                        <input type="text" class="form-control" id="periodeFin" name="periodeFin" placeholder="Entrez la Période de Fin"
                               value="<?php echo html_entity_decode($tableauModifExpositions['periodeFin'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <!-- Champ Nombre Oeuvres -->
                    <div class="mb-3">
                        <label for="nombreOeuvres" class="form-label fw-bold">Nombre d'Oeuvres</label>
                        <input type="text" class="form-control" id="nombreOeuvres" name="nombreOeuvres" placeholder="Entrez le Nombre d'Oeuvres"
                               value="<?php echo html_entity_decode($tableauModifExpositions['nombreOeuvres'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <!-- Champ Résumé -->
                    <div class="mb-3">
                        <label for="resume" class="form-label fw-bold">Résumé</label>
                        <input type="text" class="form-control" id="resume" name="resume" placeholder="Entrez le Résumé"
                               value="<?php echo html_entity_decode($tableauModifExpositions['resume'], ENT_QUOTES, 'UTF-8'); ?>" maxlength="250" required>
                    </div>

                    <!-- Champ debut exposition -->
                    <div class="mb-3">
                        <label for="debutExpoTemp" class="form-label fw-bold">Debut Exposition</label>
                        <?php if ($tableauModifExpositions['debutExpoTemp'] == null) {
                            $tableauModifExpositions['debutExpoTemp'] = "";
                        }?>
                        <input type="text" class="form-control" id="debutExpoTemp" name="debutExpoTemp" placeholder="Entrez le Début de l'Exposition (non obligatoire si exposition permanente)"
                               value="<?php echo html_entity_decode($tableauModifExpositions['debutExpoTemp'], ENT_QUOTES, 'UTF-8'); ?>">
                    </div>

                    <!-- Champ fin exposition -->
                    <div class="mb-3">
                        <label for="finExpoTemp" class="form-label fw-bold">Fin Exposition</label>
                        <?php if ($tableauModifExpositions['finExpoTemp'] == null) {
                            $tableauModifExpositions['finExpoTemp'] = "";
                        }?>
                        <input type="text" class="form-control" id="finExpoTemp" name="finExpoTemp" placeholder="Entrez la Fin de l'Exposition (non obligatoire si exposition permanente)"
                               value="<?php echo html_entity_decode($tableauModifExpositions['finExpoTemp'], ENT_QUOTES, 'UTF-8'); ?>">
                    </div>

                    <!-- Champ mot(s) clé(s) -->
                    <div class="mb-3">
                        <label for="motsCles" class="form-label fw-bold">Mot(s) Clé(s)</label>
                        <input type="text" class="form-control" id="motsCles" name="motsCles" placeholder="Entrez le(s) mot(s) clé(s)"
                               value="<?php echo html_entity_decode($tableauModifExpositions['motsCles'], ENT_QUOTES, 'UTF-8'); ?>">
                    </div>

                    <!-- Boutons -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary fw-bold px-4">
                            <i class="fas fa-save"></i>&nbsp;&nbsp;Enregistrer
                        </button>
                        <a href="page_expositions.php?page=1" class="btn btn-danger fw-bold px-4">
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
                La modification de l'exposition a été effectuée avec succès.
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
<div class="modal fade" id="updateKoModal" tabindex="-1" aria-labelledby="updateKoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateKoModalLabel">Erreur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                La modification de l'exposition a échoué.<br>Veuillez réessayer.
            </div>
            <div class="modal-footer">
                <form method="post" action="page_expositions.php?page=1">
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
                <form method="post" action="page_expositions.php?page=1">
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
<script src="../../javaScript/scriptRedirection/scriptRedirectionPEx.js"></script>



</body>
</html>