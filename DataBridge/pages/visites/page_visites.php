<?php
session_start();
include("../../fonction/fonctionsFiltres.php");
include("../../fonction/fonctionsAuthentification.php");
include ("../../fonction/fonctionsSupression.php");
try {
    $pdo = connecterBd();

    $tabDatesVisite = getDistinctValues($pdo, 'dateVisite', 'visite');
    $tabHeuresDebutVisite = getDistinctValues($pdo, 'heureDebutVisite', 'visite');
    $tabIntitulesVisite = getDistinctValues($pdo, 'intituleVisite', 'visite');
    $tabNumTelVisite = getDistinctValues($pdo, 'numTelVisite', 'visite');
    $tabIdsExposition = getDistinctValues($pdo, 'idExposition', 'visite');
    $tabIdsConferencier = getDistinctValues($pdo, 'idConferencier', 'visite');
    $tabIdsEmploye = getDistinctValues($pdo, 'idEmploye', 'visite');

    $ident= "";
    $exposition= "";
    $conferencier= "";
    $employe = "";
    $date = "";
    $heuredebut = "";
    $intitule = "";
    $telephone = "";
    
    $date = $_POST['date'] ?? '';
    $heuredebut = $_POST['heureDebut'] ?? '';
    $intitule = $_POST['intitule'] ?? '';
    $telephone = $_POST['telephone'] ?? '';
    $exposition = $_POST['idExposition'] ?? '';
    $conferencier = $_POST['idConferencier'] ?? '';
    $employe = $_POST['idEmploye'] ?? '';
    
    $ident = htmlspecialchars($ident);
    $exposition = htmlspecialchars($exposition);
    $conferencier = htmlspecialchars($conferencier);
    $employe = htmlspecialchars($employe);
    $date = htmlspecialchars($date);
    $heuredebut = htmlspecialchars($heuredebut);
    $intitule = htmlspecialchars($intitule);
    $telephone = htmlspecialchars($telephone);
    
    $date = htmlentities($date, ENT_QUOTES);
    $heuredebut = htmlentities($heuredebut, ENT_QUOTES);
    $intitule = htmlentities($intitule, ENT_QUOTES);
    $telephone = htmlentities($telephone, ENT_QUOTES);
    $exposition = htmlentities($exposition, ENT_QUOTES);
    $conferencier = htmlentities($conferencier, ENT_QUOTES);
    $employe = htmlentities($employe, ENT_QUOTES);
    
    $tableauVisites = rechercheVisite($pdo, $ident, $date, $heuredebut , $intitule , $exposition, $conferencier, $employe, $telephone);

    $suppressionOk = false;
    $admin = false;
    

    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    @$page=$_GET["page"];
    $nbr_elements_par_page = 6;
    $nbr_total_pages = ceil(count($tableauVisites)/$nbr_elements_par_page);
    $debut=($page-1)*$nbr_elements_par_page;

    $tableauVisites = rechercheVisitePage($pdo, $ident, $date, $heuredebut , $intitule , $exposition, $conferencier, $employe, $telephone, $debut, $nbr_elements_par_page);


    $middle_page = max(2, min($current_page, $nbr_total_pages - 1));

    if (isset($_POST['actionSuppression']) && $_POST['actionSuppression'] === 'supprimerVisite') {
        $suppressionOk = suppressionVisite($pdo, $_POST['idVisite']);
        $_SESSION["suppressionOk"] = $suppressionOk;
        $_SESSION["idVisiteSupprime"] = $_POST['idVisite'];
        $_SESSION["afficherModale"] = true; // Activer l'affichage
        $_SESSION["afficherModaleSuppOk"] = true; // Activer l'affichage
        header('Location: page_visites.php?page=1');
        exit();
    }
    
    if(isset($_SESSION['loginAdmin']) && $_SESSION['loginAdmin'] != "") {
        $login = $_SESSION['loginAdmin'];
        $admin = true;
    } elseif (isset($_SESSION["loginEmploye"]) && $_SESSION['loginEmploye'] != "") {
        $login = $_SESSION["loginEmploye"];
    } else {
        header('Location: ../../index.php');
        exit();
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
<!-- Main Content -->
<div class="container text-center mt-5">
    <h1 class="mb-5 fs-1 text-white fw-bold"><br><br><br>Bienvenue dans la section Gestion des Visites</h1>

    <!-- Bouton pour exporter les visites en CSV -->
    <form method="post" action="../csv/export_visites_csv.php"
          style="position: fixed; bottom: 10px; left: 10px; z-index: 1;">
        <button type="submit" class="btn btn-success btn-lg margeBtnSeConnecter">Exporter les visites</button>
    </form>

    <form method="post" action="page_ajout_visites.php">
        <button class="position-absolute top-0 end-0 m-2 text-primary border-0 bg-transparent">
            <a class="btn btn-primary btn-lg position-fixed btn-flottant">
                <i class="fas fa-user-plus"></i>Ajouter une visite
            </a>
        </button>
    </form>
    <div class="row row-cols-1 row-cols-md-8 g-4 justify-content-center rounded-3 mb-5 pt-2  navbar-transparent">
        <form method="post" action="page_visites.php?page=1" class="row justify-content-center">
            <div class="details-box pt-2 pb-2 rounded-3 text-white">
                Afficher/masquer les filtres &nbsp;
                <div id="btn">
                    <ion-icon id="btn-icon" name="chevron-down"></ion-icon>
                </div>
            </div>
            <div class="row " id="details">
                <div class="col-md-3">
                    <label for="date" class="form-label text-white ">Date Visite</label>
                    <select id="date" name="date" class="form-select">
                        <option value="">Tous</option>
                        <?php foreach ($tabDatesVisite as $dateVisite): ?>
                            <option value="<?php echo htmlentities($dateVisite, ENT_QUOTES); ?>"
                                <?php echo ($dateVisite == $date) ? 'selected' : ''; ?>>
                                <?php echo htmlentities($dateVisite, ENT_QUOTES); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 mb-5">
                    <label for="heureDebut" class="form-label text-white">Heure de Début de la Visite</label>
                    <select id="heureDebut" name="heureDebut" class="form-select">
                        <option value="">Tous</option>
                        <?php foreach ($tabHeuresDebutVisite as $heuresDebutVisite): ?>
                            <option value="<?php echo htmlentities($heuresDebutVisite, ENT_QUOTES); ?>" <?php echo ($heuresDebutVisite == $heuredebut) ? 'selected' : ''; ?>>
                                <?php echo htmlentities($heuresDebutVisite, ENT_QUOTES); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="intitule" class="form-label text-white">Intitules de la Visite</label>
                    <select id="intitule" name="intitule" class="form-select">
                        <option value="">Tous</option>
                        <?php foreach ($tabIntitulesVisite as $intitulesVisite): ?>
                            <option value="<?php echo htmlentities($intitulesVisite, ENT_QUOTES); ?>"
                                <?php echo ($intitulesVisite == $intitule) ? 'selected' : ''; ?>>
                                <?php echo htmlentities($intitulesVisite, ENT_QUOTES); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="telephone" class="form-label text-white">Téléphone de la Visite</label>
                    <select id="telephone" name="telephone" class="form-select">
                        <option value="">Tous</option>
                        <?php foreach ($tabNumTelVisite as $numTelVisite): ?>
                            <option value="<?php echo htmlentities($numTelVisite, ENT_QUOTES); ?>"
                                <?php echo ($numTelVisite == $telephone) ? 'selected' : ''; ?>>
                                <?php echo htmlentities($numTelVisite, ENT_QUOTES); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="idExposition" class="form-label text-white">Identifiant de l'Exposition</label>
                    <select id="idExposition" name="idExposition" class="form-select">
                        <option value="">Tous</option>
                        <?php foreach ($tabIdsExposition as $idExposition): ?>
                            <option value="<?php echo htmlentities($idExposition, ENT_QUOTES); ?>"
                                <?php echo ($idExposition == $exposition) ? 'selected' : ''; ?>>
                                <?php echo htmlentities($idExposition, ENT_QUOTES); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="idConferencier" class="form-label text-white">Identifiant du Conférencier</label>
                    <select id="idConferencier" name="idConferencier" class="form-select">
                        <option value="">Tous</option>
                        <?php foreach ($tabIdsConferencier as $idConferencier): ?>
                            <option value="<?php echo htmlentities($idConferencier, ENT_QUOTES); ?>"
                                <?php echo ($idConferencier == $conferencier) ? 'selected' : ''; ?>>
                                <?php echo htmlentities($idConferencier, ENT_QUOTES); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="idEmploye" class="form-label text-white">Identifiant de l'Employé(e)</label>
                    <select id="idEmploye" name="idEmploye" class="form-select">
                        <option value="">Tous</option>
                        <?php foreach ($tabIdsEmploye as $idEmploye): ?>
                            <option value="<?php echo htmlentities($idEmploye, ENT_QUOTES); ?>"
                                <?php echo ($idEmploye == $employe) ? 'selected' : ''; ?>>
                                <?php echo htmlentities($idEmploye, ENT_QUOTES); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <!-- Bouton pour filtrer -->
                <div class="col-md-3 align-self-end mb-5">
                    <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                </div>
                <!-- Bouton de réinitialisation des filtres -->
                <div class="col-md-3 align-self-end mb-5 text-end offset-md-9">
                    <a href="page_visites.php?page=1" class="btn btn-danger w-100">Réinitialiser</a>
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
        <?php if (count($tableauVisites) == 0) { ?>
            <div class="card shadow-sm border-0 h-100 position-relative me-3">
                <!-- Contenu de la card -->
                <div class="card-body text-black rounded text-center">
                    <br>
                    <span class=" text-center fw-bold">Aucun résultat ne correspond à votre recherche </span>
                    <br><br>
                </div>
            </div>
        <?php } else { ?>
            <?php foreach ($tableauVisites as $visite): ?>
                <div class="card cardAffichage shadow-sm border-0 h-100 position-relative me-3">
                    <!-- Icône crayon en haut à droite pour la modification -->
                    <form method="post" action="page_modif_visites.php">
                        <button class="position-absolute top-0 end-0 m-2 text-primary border-0 bg-transparent">
                            <i class="fas fa-pencil-alt fs-4"></i>
                        </button>
                        <?php
                        $idVisites = $visite['idVisite'];
                        echo '<input type="hidden" name="id_Visite" value="' . htmlentities($idVisites, ENT_QUOTES) . '">';
                        ?>
                    </form>

                    <!-- Contenu de la card -->
                    <div class="cardAffichage-body text-black rounded text-start">
                        <span class="fs-5 fw-bold">Identifiant :</span> <?php echo(html_entity_decode($visite['idVisite'], ENT_QUOTES, 'UTF-8')) ?>
                        <br><br><span class="fs-5 fw-bold">Date Visite :</span> <?php echo(html_entity_decode($visite['dateVisite'], ENT_QUOTES, 'UTF-8')) ?>
                        <br><br><span class="fs-5 fw-bold">Heure du Début de la Visite :</span> <?php echo(html_entity_decode($visite['heureDebutVisite'], ENT_QUOTES, 'UTF-8')) ?>
                        <br><br><span class="fs-5 fw-bold">Intitulé de la Visite :</span> <?php echo(html_entity_decode($visite['intituleVisite'], ENT_QUOTES, 'UTF-8')) ?>
                        <br><br><span class="fs-5 fw-bold">Numéro de Téléphone de la Visite :</span> <?php echo(html_entity_decode($visite['numTelVisite'], ENT_QUOTES, 'UTF-8')) ?>
                        <br><br>

                        <!-- Contenu supplémentaire caché par défaut -->
                        <div id="extraContent<?php echo $visite['idVisite']; ?>" style="display: none;">
                            <!-- Tu peux mettre ici d'autres informations ou détails sur la visite -->
                            <br><span class="fs-5 fw-bold">Identifiant de l'Exposition :</span> <?php echo(html_entity_decode($visite['idExposition'], ENT_QUOTES, 'UTF-8')) ?>
                            <br><br><span class="fs-5 fw-bold">Intitulé de l'Exposition :</span> <?php echo(html_entity_decode($visite['intitule'], ENT_QUOTES, 'UTF-8')) ?>
                            <br><br><span class="fs-5 fw-bold">Id, Nom et Prénom du Conférencier :</span> <?php echo($visite['idConferencier']) . ' ' . html_entity_decode($visite['nomConferencier'], ENT_QUOTES, 'UTF-8') . ' ' . html_entity_decode($visite['prenomConferencier'], ENT_QUOTES, 'UTF-8') ?>
                            <br><br><span class="fs-5 fw-bold">Id, Nom et Prénom de l'Employé(e) :</span> <?php echo($visite['IdEmploye']) .' ' . html_entity_decode($visite['nomEmploye'], ENT_QUOTES, 'UTF-8') . ' ' . html_entity_decode($visite['prenomEmploye'], ENT_QUOTES, 'UTF-8') ?>
                        </div>

                        <button id="toggleContent<?php echo $visite['idVisite']; ?>"
                                class="btn btn-link p-0"
                                onclick="toggleCardContent('<?php echo $visite['idVisite']; ?>')">
                            Plus
                        </button>
                    </div>

                    <!-- Bouton poubelle avec modal pour suppression -->
                    <a href="#" class="position-absolute bottom-0 end-0 m-2 text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $visite['idVisite']; ?>">
                        <i class="fas fa-trash-alt fs-4"></i>
                    </a>

                    <!-- Modal de confirmation de suppression -->
                    <div class="modal fade" id="deleteModal<?php echo $visite['idVisite']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?php echo $visite['idVisite']; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel<?php echo $visite['idVisite']; ?>">Confirmation de suppression</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                </div>
                                <div class="modal-body">
                                    Êtes-vous sûr de vouloir supprimer cette visite ?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    <form method="post" action="page_visites.php?page=1">
                                        <input type="hidden" name="actionSuppression" value="supprimerVisite">
                                        <input type="hidden" name="idVisite" value="<?php echo htmlentities($visite['idVisite'], ENT_QUOTES); ?>">
                                        <button type="submit" class="btn btn-danger">Confirmer</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach;
        }?>
    </div>
</div>
<div id="tableau">
    <?php if (count($tableauVisites) == 0) { ?>
        <div class="row row-cols-1 row-cols-md-4 g-4 justify-content-center">
            <div class="card shadow-sm border-0 h-100 position-relative me-3">
                <!-- Contenu de la card -->
                <div class="card-body text-black rounded text-center">
                    <br>
                    <span class=" text-center fw-bold">Aucun résultat ne correspond à votre recherche </span>
                    <br><br>
                </div>
            </div>
        </div>
    <?php } else { ?>
    <div class="d-flex justify-content-center align-items-center">
        <table class="table-style">
            <thead>
            <tr>
                <th class="text-center">Identifiant</th>
                <th class="text-center">Date Visite</th>
                <th class="text-center">Heure de Début</th>
                <th class="text-center">Intitule</th>
                <th class="text-center">Numéro de Téléphone</th>
                <th class="text-center">Identifiant de l'Exposition</th>
                <th class="text-center">Intitule de l'Exposition</th>
                <th class="text-center">Nom du conferencier</th>
                <th class="text-center">Prenom du conferencier</th>

                <th class="text-center">Modifier</th>
                <th class="text-center">Supprimer</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($tableauVisites as $visite): ?>
                <tr>
                    <td><?php echo(html_entity_decode($visite['idVisite'], ENT_QUOTES, 'UTF-8')) ?></td>
                    <td><?php echo(html_entity_decode($visite['dateVisite'], ENT_QUOTES, 'UTF-8')) ?></td>
                    <td><?php echo(html_entity_decode($visite['heureDebutVisite'], ENT_QUOTES, 'UTF-8')) ?></td>
                    <td><?php echo(html_entity_decode($visite['intituleVisite'], ENT_QUOTES, 'UTF-8')) ?></td>
                    <td><?php echo(html_entity_decode($visite['numTelVisite'], ENT_QUOTES, 'UTF-8')) ?></td>
                    <td><?php echo(html_entity_decode($visite['idExposition'], ENT_QUOTES, 'UTF-8')) ?></td>
                    <td><?php echo(html_entity_decode($visite['intitule'], ENT_QUOTES, 'UTF-8')) ?></td>
                    <td><?php echo(html_entity_decode($visite['nomConferencier'], ENT_QUOTES, 'UTF-8')) ?></td>
                    <td><?php echo(html_entity_decode($visite['prenomConferencier'], ENT_QUOTES, 'UTF-8')) ?></td>
                    <td class="text-center">
                        <form method="post" action="page_modif_visites.php">
                            <button class="btn btn-link text-primary p-0">
                                <i class="fas fa-pencil-alt fs-4"></i>
                            </button>
                            <?php
                            ?>
                        </form>
                    </td>
                    <td class="text-center">
                        <a href="#" class="text-danger" data-bs-toggle="modal" data-bs-target="#deleteModalTableau<?php echo $visite['idVisite']; ?>">
                            <i class="fas fa-trash-alt fs-4"></i>
                        </a>
                        <!-- Delete Confirmation Modal -->
                        <div class="modal fade" id="deleteModalTableau<?php echo $visite['idVisite']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?php echo $visite['idVisite']; ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel<?php echo $visite['idVisite'];; ?>">Confirmation de suppression</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                    </div>
                                    <div class="modal-body">
                                        Êtes-vous sûr de vouloir supprimer cette visite ?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                        <form method="post" action="page_employes.php?page=1">
                                            <input type="hidden" name="actionSuppression" value="supprimerEmploye">
                                            <input type="hidden" name="id_employe" value="<?php echo htmlentities($visite['idVisite'], ENT_QUOTES); ?>">
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
        <?php } ?>
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
                    <?php if ($_SESSION["suppressionOk"]): ?>
                        <p class="text-success">La Visite numéro <strong><?php echo htmlentities($_SESSION["idVisiteSupprime"], ENT_QUOTES); ?></strong> a bien été supprimée.</p>
                    <?php else: ?>
                        <p class="text-danger">La Visite numéro <strong><?php echo htmlentities($_SESSION["idVisiteSupprime"], ENT_QUOTES); ?></strong> n'a pas pu être supprimée.</p>
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
<script src="../../javaScript/scriptGeneraux/scriptBurger.js"></script>
<script src="../../javaScript/scriptGeneraux/scriptFiltre.js"></script>
<script src="../../javaScript/scriptSuppression/scriptSuppressionEmpOk.js"></script>
<script src="../../javaScript/scriptGeneraux/scriptTableau.js"></script>
<script src="../../javaScript/scriptGeneraux/scriptMontrerPlus.js"></script>

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
                    <a class="page-link" href="?page=<?= $current_page - 1 ?>">Précédent</a>
                </li>
            <?php else: ?>
                <li class="page-item disabled">
                    <span class="page-link">Précédent</span>
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
                    <a class="page-link" href="?page=<?= $current_page + 1 ?>">Suivant</a>
                </li>
            <?php else: ?>
                <li class="page-item disabled">
                    <span class="page-link">Suivant</span>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
    <p>&copy; 2024 DataBridge. Tous droits réservés.</p>
</footer>
</html>