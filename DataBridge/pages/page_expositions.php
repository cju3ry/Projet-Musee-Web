<?php
session_start();
include("../fonction/fonctionsFiltres.php");
include("../fonction/fonctionsAuthentification.php");
include ("../fonction/fonctionsSupression.php");
include("../fonction/fonctionInsert.php");

$tableauEmployes = "";
try {
    $pdo = connecterBd();
	$tabPeriodeDebut = tabPeriodeDebut($pdo);
	$tabPeriodeFin = tabPeriodeFin($pdo);
	$tabNombreOeuvres = tabNombreOeuvres($pdo);
	$tabMotsCles = tabMotsCles($pdo);
	$tabDebutExposition = tabDebutExposition($pdo);
	$tabFinExposition = tabFintExposition($pdo);
    $intitule = "";
	$periodeDebut = $_POST['periodeDebut'] ?? "";
	$periodeFin = $_POST['periodeFin'] ?? ""; // ca marche pas pour le moment
	$nombreOeuvres = $_POST['nombreOeuvres'] ?? "";
	$resume = "";                             // ca marche pas pour le moment
	$debutExpoTemp = $_POST['debutExposition'] ?? "";
	$finExpoTemp = $_POST['finExposition'] ?? "";    // ca marche bizzarement
	$motCle = $_POST['motsCles'] ?? "";
    $tableauExpositions = rechercheExposition($pdo, $intitule,$periodeDebut,$periodeFin,$nombreOeuvres,$resume
                       ,$debutExpoTemp,$finExpoTemp,$motCle);
    $suppressionOk = false;
    $admin = false;

    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

    @$page=$_GET["page"];
    $nbr_elements_par_page = 6;
    $nbr_total_pages = ceil(count($tableauExpositions)/$nbr_elements_par_page);
    $debut=($page-1)*$nbr_elements_par_page;

	$tableauExpositions = rechercheExpositionPage($pdo, $intitule, $periodeDebut, $periodeFin, $nombreOeuvres, $resume
		, $debutExpoTemp, $finExpoTemp, $motCle, $debut, $nbr_elements_par_page);

    $middle_page = max(2, min($current_page, $nbr_total_pages - 1));

    if (isset($_POST['actionSuppression']) && $_POST['actionSuppression'] === 'supprimerExposition') {
        $idExposition = $_POST['id_expo'] ?? '';
        $suppressionOk = suppressionExposition($pdo, $idExposition);
        $_SESSION["suppressionOk"] = $suppressionOk;
        $_SESSION["idExpositionSuppr"] = $_POST['id_expo'];
        $_SESSION["afficherModale"] = true; // Activer l'affichage
        $_SESSION["afficherModaleSuppOk"] = true; // Activer l'affichage
        header('Location: page_expositions.php?page=1');
        exit();
    }
    
    if(isset($_SESSION['loginAdmin']) && $_SESSION['loginAdmin'] != "") {
        $login = $_SESSION['loginAdmin'];
        $admin = true;
    } elseif (isset($_SESSION["loginEmploye"]) && $_SESSION['loginEmploye'] != "") {
        $login = $_SESSION["loginEmploye"];
    } else {
        header('Location: index.php');
        exit();
    }

} catch (PDOException $e) {
    header('Location: erreurConnexion.php');
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
            <span class="me-3 mt-3 text-white"><?php echo $login;?></span><br>
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
                    <a href="<?php echo 'accueil_' . ($admin ? 'admin' : 'employe') . '.php'; ?>" class="text-decoration-none d-flex justify-content-between align-items-center">
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
                        <li><a href="page_ajout_employes.php" class="text-decoration-none">Ajouter un employé</a></li>
                        <li><a href="page_employes.php?page=1" class="text-decoration-none">Consulter les employés</a></li>
                    </ul>
                </li>
            <?php endif; ?>

            <li>
                <a href="#" class="text-decoration-none d-flex justify-content-between align-items-center">
                    Gestion des Conférenciers
                    <i class="submenu-icon fa fa-chevron-right"></i>
                </a>
                <ul class="submenu">
                    <li><a href="#" class="text-decoration-none">Ajouter un Conférencier</a></li>
                    <li><a href="page_expositions.php?page=1" class="text-decoration-none">Consulter les Conférenciers</a></li>
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
<!-- Main Content -->
<div class="container text-center mt-5">
    <h1 class="mb-5 fs-1 text-white fw-bold"><br><br><br>Bienvenue dans la section Gestion des Expostions</h1>
    <!-- Add this at the bottom of the page, before the closing </body> tag -->
    <!-- Bouton pour exporter les employés en CSV -->
    <form method="post" action="export_expositions_csv.php"
          style="position: fixed; bottom: 10px; left: 10px; z-index: 9999;">
        <button type="submit" class="btn btn-success btn-lg margeBtnSeConnecter">Exporter les empositions</button>
    </form>
    <form method="post" action="page_ajout_expositions.php" target="_blank">
        <button class="position-absolute top-0 end-0 m-2 text-primary border-0 bg-transparent">
            <a class="btn btn-primary btn-lg position-fixed btn-flottant">
                <i class="fas fa-user-plus"></i>Ajouter une exposition
            </a>
        </button>
    </form>
    <div class="row row-cols-1 row-cols-md-8 g-4 justify-content-center rounded-3 mb-5 pt-2  navbar-transparent">
        <form method="post" action="page_expositions.php" class="row justify-content-center">
            <div class="details-box pt-2 pb-2 rounded-3 text-white">
                Afficher/masquer les filtres &nbsp;
                <div id="btn">
                    <ion-icon id="btn-icon" name="chevron-down"></ion-icon>
                </div>
            </div>
            <div class="row " id="details">
                <div class="col-md-3">
                    <label for="periodeDebut" class="form-label text-white ">Periode Debut de l'Exposition</label>
                    <select id="periodeDebut" name="periodeDebut" class="form-select">
                        <option value="">Tous</option>
		                <?php foreach ($tabPeriodeDebut as $periodeDebutExp): ?>
                            <option value="<?php echo htmlentities($periodeDebutExp, ENT_QUOTES); ?>" <?php echo ($periodeDebut == $periodeDebutExp) ? 'selected' : ''; ?>>
				                <?php echo htmlentities($periodeDebutExp, ENT_QUOTES); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 mb-5">
                    <label for="periodeFin" class="form-label text-white">Période Fin Exposition</label>
                    <select id="periodeFin" name="periodeFin" class="form-select">
                        <option value="">Tous</option>
		                <?php foreach ($tabPeriodeFin as $periodeFinExp): ?>
                            <option value="<?php echo htmlentities($periodeFinExp, ENT_QUOTES); ?>" <?php echo ($periodeFin == $periodeFinExp) ? 'selected' : ''; ?>>
				                <?php echo htmlentities($periodeFinExp, ENT_QUOTES); ?>
                            </option>
		                <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="nombreOeuvres" class="form-label text-white">Nombre Oeuvres</label>
                    <select id="nombreOeuvres" name="nombreOeuvres" class="form-select">
                        <option value="">Tous</option>
		                <?php foreach ($tabNombreOeuvres as $nombreOeuvresExp): ?>
                            <option value="<?php echo htmlentities($nombreOeuvresExp, ENT_QUOTES); ?>"
				                <?php echo ($nombreOeuvres == $nombreOeuvresExp) ? 'selected' : ''; ?>>
				                <?php echo htmlentities($nombreOeuvresExp, ENT_QUOTES); ?>
                            </option>
		                <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="motsCles" class="form-label text-white">Mots Clés </label>
                    <select id="motsCles" name="motsCles" class="form-select">
                        <option value="">Tous</option>
			            <?php foreach ($tabMotsCles as $motsClesExp): ?>
                            <option value="<?php echo htmlentities($motsClesExp, ENT_QUOTES); ?>"
					            <?php echo ($motCle == $motsClesExp) ? 'selected' : ''; ?>>
					            <?php echo htmlentities($motsClesExp, ENT_QUOTES); ?>
                            </option>
			            <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="debutExposition" class="form-label text-white">Début Exposition</label>
                    <select id="debutExposition" name="debutExposition" class="form-select">
                        <option value="">Tous</option>
			            <?php foreach ($tabDebutExposition as $debutExpositionExp): ?>
                            <option value="<?php echo htmlentities($debutExpositionExp, ENT_QUOTES); ?>"
					            <?php echo ($debutExpoTemp == $debutExpositionExp) ? 'selected' : ''; ?>>
					            <?php echo htmlentities($debutExpositionExp, ENT_QUOTES); ?>
                            </option>
			            <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="finExposition" class="form-label text-white">Fin Exposition</label>
                    <select id="finExposition" name="finExposition" class="form-select">
                        <option value="">Tous</option>
			            <?php foreach ($tabFinExposition as $finExpositionExp): ?>
                            <option value="<?php echo htmlentities($finExpositionExp, ENT_QUOTES); ?>"
					            <?php echo ($finExpoTemp == $finExpositionExp) ? 'selected' : ''; ?>>
					            <?php echo htmlentities($finExpositionExp, ENT_QUOTES); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 align-self-end mb-5">
                    <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                </div>
            </div>
    </div>
    <!-- bouton pour changer de mode d'affichage -->
    <div class="row justify-content-center my-4">
        <div class="col-md-4">
            <div class="details-boxx rounded-3 text-white text-center p-3 d-flex justify-content-center">
                Affichage en tableau&nbsp;&nbsp;&nbsp;&nbsp;
                <div class="switch d-flex justify-content-center">
                    <input type="checkbox" id="toggle">
                    <label for="toggle"></label>
                </div>
            </div>
        </div>
    </div>
    </form>
</div>


<div id="cardContainer">
    <div class="row row-cols-1 row-cols-md-4 g-4 justify-content-center">
        <?php foreach ($tableauExpositions as $expostions): ?>
            <div class="card shadow-sm border-0 h-100 position-relative me-3">
                <!-- Icône crayon en haut à droite -->
                <form method="POST" action="page_modif_expositions.php">
                    <?php
                    $idExpo = $expostions['idExposition'];
                    echo "<input type='hidden' name='id_expo' value='" . htmlspecialchars($idExpo) . "'>";
                    ?>
                    <button type="submit" class="position-absolute top-0 end-0 m-2 text-primary border-0 bg-transparent">
                        <i class="fas fa-pencil-alt fs-4"></i>
                    </button>

                </form>

                <!-- Contenu de la card -->
                <div class="card-body text-black rounded text-start">
                    <span class="fs-5 fw-bold">Identifiant :</span> <?php echo($expostions['idExposition']) ?>
                    <br><br><span class="fs-5 fw-bold">Intitulé : </span><?php echo($expostions['intitule']) ?>
                    <br><br><span class="fs-5 fw-bold">Période de Début : </span><?php echo($expostions['periodeDebut']) ?>
                    <br><br><span class="fs-5 fw-bold">Période de Fin : </span><?php echo($expostions['periodeFin']) ?>
                    <br><br><span class="fs-5 fw-bold">Nombre Oeuvres : </span><?php echo($expostions['nombreOeuvres']) ?>
                    <br><br><span class="fs-5 fw-bold">Resume : </span><?php echo($expostions['resume']) ?>
                    <br><br><span class="fs-5 fw-bold">Debut Exposition : </span><?php echo($expostions['debutExpoTemp']) ?>
                    <br><br><span class="fs-5 fw-bold">Fin Exposition : </span><?php echo($expostions['finExpoTemp']) ?>
                    <br><br><span class="fs-5 fw-bold">Mot Cle de l'Exposition: </span><?php echo($expostions['motsCles']) ?>


                </div>

                <!-- Bouton poubelle avec modal -->
                <a href="#" class="position-absolute bottom-0 end-0 m-2 text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $idExpo; ?>">
                    <i class="fas fa-trash-alt fs-4"></i>
                </a>

                <!-- Delete Confirmation Modal -->
                <div class="modal fade" id="deleteModal<?php echo $idExpo; ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?php echo $idExpo; ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel<?php echo $idExpo; ?>">Confirmation de suppression</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                            </div>
                            <div class="modal-body">
                                Êtes-vous sûr de vouloir supprimer cette exposition ?
                            </div>
                            <div class="modal-footer">
                                <!-- Bouton Annuler -->
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                <!-- Bouton Confirmer -->
                                <form method="post" action="page_expositions.php?page=1">
                                    <input type="hidden" name="actionSuppression" value="supprimerExposition">
                                    <input type="hidden" name="id_expo" value="<?php echo htmlentities($idExpo, ENT_QUOTES); ?>">
                                    <button type="submit" class="btn btn-danger">Confirmer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<div id="tableau">
    <div class="d-flex justify-content-center align-items-center">
        <table class="table-style">
            <thead>
            <tr>
                <th class="text-center">Identifiant</th>
                <th class="text-center">Intitulé</th>
                <th class="text-center">Période de Début</th>
                <th class="text-center">Période de Fin</th>
                <th class="text-center">Nombre d'Oeuvres</th>
                <th class="text-center">Resume</th>
                <th class="text-center">Debut Exposition</th>
                <th class="text-center">Fin Exposition</th>
                <th class="text-center">Mots Clés</th>

                <th class="text-center">Modifier</th>
                <th class="text-center">Supprimer</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($tableauExpositions as $expostions): ?>
                <tr>
                    <td><?php echo($expostions['idExposition']) ?></td>
                    <td><?php echo($expostions['intitule']) ?></td>
                    <td><?php echo($expostions['periodeDebut']) ?></td>
                    <td><?php echo($expostions['periodeFin']) ?></td>
                    <td><?php echo($expostions['nombreOeuvres']) ?></td>
                    <td><?php echo($expostions['resume']) ?></td>
                    <td><?php echo($expostions['debutExpoTemp']) ?></td>
                    <td><?php echo($expostions['finExpoTemp']) ?></td>
                    <td><?php echo($expostions['motsCles']) ?></td>
                    <td class="text-center">
                        <form method="post" action="page_modif_expositions.php">
                            <button class="btn btn-link text-primary p-0">
                                <i class="fas fa-pencil-alt fs-4"></i>
                            </button>
                            <?php
                            $idExpo = $expostions['idExposition'];
                            echo "<input type='hidden' name='id_expo' value='" . htmlspecialchars($idExpo) . "'>";
                            ?>
                        </form>
                    </td>
                    <td class="text-center">
                        <a href="#" class="text-danger" data-bs-toggle="modal"
                           data-bs-target="#deleteModalTableau<?php echo $idExpo; ?>">
                            <i class="fas fa-trash-alt fs-4"></i>
                        </a>
                        <!-- Delete Confirmation Modal -->
                        <div class="modal fade" id="deleteModalTableau<?php echo $idExpo; ?>" tabindex="-1"
                             aria-labelledby="deleteModalLabel<?php echo $idExpo; ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel<?php echo $idExpo; ?>">Confirmation
                                            de suppression</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                    </div>
                                    <div class="modal-body">
                                        Êtes-vous sûr de vouloir supprimer cette exposition ?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                        <form method="post" action="page_expositions.php?page=1">
                                            <input type="hidden" name="actionSuppression" value="supprimerExposition">
                                            <input type="hidden" name="id_expo"
                                                   value="<?php echo htmlentities($idExpo, ENT_QUOTES); ?>">
                                            <button type="submit" class="btn btn-danger">Confirmer</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>

        </table>
    </div>
</div>

<!-- Modal de suppression Bootstrap -->
</div>

<?php if (isset($_SESSION["afficherModaleSuppOk"]) && $_SESSION["afficherModaleSuppOk"]): ?>
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Résultat de la suppression</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <?php if ($_SESSION["suppressionOk"] == true): ?>
                        <p class="text-success">L'exposition <strong><?php echo htmlentities($_SESSION["idExpositionSuppr"], ENT_QUOTES); ?></strong> a bien été supprimé.</p>
                    <?php else: ?>
                        <p class="text-danger">L'exposition <strong><?php echo htmlentities($_SESSION["idExpositionSuppr"], ENT_QUOTES); ?></strong> n'a pas pu être supprimé.</p>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
    <?php
    $_SESSION["afficherModale"] = false; // Activer l'affichage
    $_SESSION["afficherModaleSuppOk"] = false; // Activer l'affichage
    unset($_SESSION["afficherModale"]); // Désactiver l'affichage
    ?>
<?php endif; ?>
</div>

<!-- JavaScript files (Popper.js & Bootstrap JS) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

<!-- Fichier JS externe -->
<script src="../javaScript/scriptBurger.js"></script>
<script src="../javaScript/scriptFiltre.js"></script>
<script src="../javaScript/scriptSuppressionEmpOk.js"></script>
<script src="../javaScript/scriptTableau.js"></script>

<script
        type="module"
        src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"
></script>
<script
        nomodule
        src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"
></script>
</body>
<footer class="mt-5 text-center py-3 text-white">
    <nav aria-label="Pages">
        <ul class="pagination justify-content-center">
            <?php if ($current_page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $current_page - 1 ?>">Previous</a>
                </li>
            <?php else: ?>
                <li class="page-item disabled">
                    <span class="page-link">Previous</span>
                </li>
            <?php endif; ?>

            <?php
            // Afficher un nombre limité de pages autour de la page actuelle
            $range = 5; // Nombre de pages à afficher avant et après la page courante
            $start = max(1, $current_page - $range);
            $end = min($nbr_total_pages, $current_page + $range);

            // Afficher les pages avant et après
            for ($i = $start; $i <= $end; $i++): ?>
                <li class="page-item <?= $i == $current_page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($current_page < $nbr_total_pages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $current_page + 1 ?>">Next</a>
                </li>
            <?php else: ?>
                <li class="page-item disabled">
                    <span class="page-link">Next</span>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
    <p>&copy; 2024 DataBridge. Tous droits réservés.</p>
</footer>
</html>