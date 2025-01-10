<?php
session_start();
include("../../fonction/fonctionsFiltres.php");
include("../../fonction/fonctionsAuthentification.php");
include ("../../fonction/fonctionsSupression.php");

try {
    $pdo = connecterBd();
    $tabNomConferencier = tabNomConferencier($pdo);
    $tabPrenomConferencier = tabPrenomConferencier($pdo);
    $tabTelephoneConferencier = tabTelephone($pdo);
    $tabSpecialitesConferencier = tabSpecialitesConferencier($pdo);
    $tabDebutIndispoConferencier = tabDebutIndispoConferencier($pdo);
    $tabFinIndispoConferencier = tabFinIndispoConferencier($pdo);
    $tabEstEmploye = tabEstEmployeConferencier($pdo);

    $nomConferencier = "";
    $prenomConferencier = "";
    $telephone = "";
    $estEmploye = "";
    $specialite = "";
    $indispoDebut = "";
    $indispoFin = "";

    $nomConferencier = $_POST['nom'] ?? '';
    $prenomConferencier = $_POST['prenom'] ?? '';
    $specialite = $_POST['specialites'] ?? '';
    $telephone = $_POST['telephone'] ?? '';
    $indispoDebut = $_POST['dateDebutIndispo'] ?? '';
    $indispoFin = $_POST['dateFinIndispo'] ?? '';
    $estEmploye = $_POST['estEmploye'] ?? '';

    $nomConferencier = htmlspecialchars($nomConferencier);
    $prenomConferencier = htmlspecialchars($prenomConferencier);
    $specialite = htmlspecialchars($specialite);
    $telephone = htmlspecialchars($telephone);
    $indispoDebut = htmlspecialchars($indispoDebut);
    $indispoFin = htmlspecialchars($indispoFin);
    $estEmploye = htmlspecialchars($estEmploye);

    $nomConferencier = htmlentities($nomConferencier, ENT_QUOTES);
    $prenomConferencier = htmlentities($prenomConferencier, ENT_QUOTES);
    $specialite = htmlentities($specialite, ENT_QUOTES);
    $telephone = htmlentities($telephone, ENT_QUOTES);
    $indispoDebut = htmlentities($indispoDebut, ENT_QUOTES);
    $indispoFin = htmlentities($indispoFin, ENT_QUOTES);
    $estEmploye = htmlentities($estEmploye, ENT_QUOTES);
    
    $tableauConferenciers = rechercheConferencier($pdo, $nomConferencier, $prenomConferencier,
        $estEmploye, $telephone, $specialite, $indispoDebut, $indispoFin);


    $suppressionOk = false;
    $admin = false;

    @$page=$_GET["page"];
    $nbr_elements_par_page = 6;
    $nbr_total_pages = ceil(count($tableauConferenciers)/$nbr_elements_par_page);
    $debut=($page-1)*$nbr_elements_par_page;

    $tableauConferenciers = rechercheConferencierPage($pdo, $nomConferencier, $prenomConferencier,
        $estEmploye, $specialite, $telephone, $indispoDebut, $indispoFin, $debut, $nbr_elements_par_page);

    if (isset($_POST['actionSuppression']) && $_POST['actionSuppression'] === 'supprimerConferencier') {
        $suppressionOk = suppressionConferencier($pdo, $_POST['id_conferencier']); // Suppression
        $_SESSION["suppressionOk"] = $suppressionOk;
        $_SESSION["idConferencierSupprimer"] = $_POST['id_conferencier'];
        $_SESSION["afficherModale"] = true; // Activer l'affichage
        $_SESSION["afficherModaleSuppOk"] = true; // Activer l'affichage
        header('Location: page_conferenciers.php?page=1');
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
    <h1 class="mb-5 fs-1 text-white fw-bold"><br><br><br>Bienvenue dans la section Gestion des Conferenciers</h1>

    <!-- Bouton pour exporter les conférenciers en CSV -->
    <form method="post" action="../csv/export_conferenciers_csv.php"
          style="position: fixed; bottom: 10px; left: 10px; z-index: 1;">
        <button type="submit" class="btn btn-success btn-lg margeBtnSeConnecter">Exporter les conférenciers</button>
    </form>

    <form method="post" action="page_ajout_conferenciers.php">
        <button class="position-absolute top-0 end-0 m-2 text-primary border-0 bg-transparent">
            <a class="btn btn-primary btn-lg position-fixed btn-flottant">
                <i class="fas fa-user-plus"></i>Ajouter un conférencier
            </a>
        </button>
    </form>
    <div class="row row-cols-1 row-cols-md-8 g-4 justify-content-center rounded-3 mb-5 pt-2  navbar-transparent">
        <form method="post" action="../conferenciers/page_conferenciers.php?page=1" class="row justify-content-center">
            <div class="details-box pt-2 pb-2 rounded-3 text-white">
                Afficher/masquer les filtres &nbsp;
                <div id="btn">
                    <ion-icon id="btn-icon" name="chevron-down"></ion-icon>
                </div>
            </div>
            <div class="row " id="details">
                <div class="col-md-3">
                    <label for="nom" class="form-label text-white ">Nom</label>
                    <select id="nom" name="nom" class="form-select">
                        <option value="">Tous</option>
                        <?php foreach ($tabNomConferencier as $nomConf): ?>
                            <option value="<?php echo htmlentities($nomConf, ENT_QUOTES); ?>" <?php echo ($nomConf == $nomConferencier) ? 'selected' : ''; ?>>
                                <?php echo htmlentities($nomConf, ENT_QUOTES); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 mb-5">
                    <label for="prenom" class="form-label text-white">Prénom</label>
                    <select id="prenom" name="prenom" class="form-select">
                        <option value="">Tous</option>
                        <?php foreach ($tabPrenomConferencier as $prenomConf): ?>
                            <option value="<?php echo htmlentities($prenomConf, ENT_QUOTES); ?>" <?php echo ($prenomConf == $prenomConferencier) ? 'selected' : ''; ?>>
                                <?php echo htmlentities($prenomConf, ENT_QUOTES); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 mb-5">
                    <label for="telephone" class="form-label text-white">Téléphone</label>
                    <select id="telephone" name="telephone" class="form-select">
                        <option value="">Tous</option>
                        <?php foreach ($tabTelephoneConferencier as $telephoneConf): ?>
                            <option value="<?php echo htmlentities($telephoneConf, ENT_QUOTES); ?>" <?php echo ($telephoneConf == $telephone) ? 'selected' : ''; ?>>
                                <?php echo htmlentities($telephoneConf, ENT_QUOTES); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="specialites" class="form-label text-white">Specialités</label>
                    <select id="specialites" name="specialites" class="form-select">
                        <option value="">Tous</option>
                        <?php foreach ($tabSpecialitesConferencier as $specialitesConf): ?>
                            <option value="<?php echo htmlentities($specialitesConf, ENT_QUOTES); ?>"
                                <?php echo ($specialitesConf == $specialite) ? 'selected' : ''; ?>>
                                <?php echo htmlentities($specialitesConf, ENT_QUOTES); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="dateDebutIndispo" class="form-label text-white">Debut Indisponibilité Conferencier</label>
                    <select id="dateDebutIndispo" name="dateDebutIndispo" class="form-select">
                        <option value="">Tous</option>
                        <?php foreach ($tabDebutIndispoConferencier as $debutIndispoConf): ?>
                            <option value="<?php echo htmlentities($debutIndispoConf, ENT_QUOTES); ?>"
                                <?php echo ($debutIndispoConf == $indispoDebut) ? 'selected' : ''; ?>>
                                <?php echo htmlentities($debutIndispoConf, ENT_QUOTES); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="dateFinIndispo" class="form-label text-white">Fin Indisponibilité Conferencier</label>
                    <select id="dateFinIndispo" name="dateFinIndispo" class="form-select">
                        <option value="">Tous</option>
                        <?php foreach ($tabFinIndispoConferencier as $finIndispoConf): ?>
                            <option value="<?php echo htmlentities($finIndispoConf, ENT_QUOTES); ?>"
                                <?php echo ($finIndispoConf == $indispoFin) ? 'selected' : ''; ?>>
                                <?php echo htmlentities($finIndispoConf, ENT_QUOTES); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="estEmploye" class="form-label text-white">Est Employé(e) ?</label>
                    <select id="estEmploye" name="estEmploye" class="form-select">
                        <option value="">Tous</option>
                        <?php foreach ($tabEstEmploye as $estEmployeConf): ?>
                            <option value="<?php echo htmlentities($estEmployeConf, ENT_QUOTES); ?>"
                                <?php echo ($estEmployeConf == $estEmploye) ? 'selected' : ''; ?>>
                                <?php echo htmlentities($estEmployeConf, ENT_QUOTES); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <!-- Bouton pour filtresr -->
                <div class="col-md-3 offset-md-9 align-self-end mb-5">
                    <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                </div>
                <!-- Bouton de réinitialisation des filtres -->
                <div class="col-md-3 align-self-end mb-5 text-end offset-md-9">
                    <a href="page_conferenciers.php?page=1" class="btn btn-danger w-100">Réinitialiser</a>
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
        <?php if (count($tableauConferenciers) == 0) { ?>
            <div class="card shadow-sm border-0 h-100 position-relative me-3">
                <!-- Contenu de la card -->
                <div class="card-body text-black rounded text-center">
                    <br>
                    <span class=" text-center fw-bold">Aucun résultat ne correspond à votre recherche </span>
                    <br><br>
                </div>
            </div>
        <?php } else { ?>
            <?php foreach ($tableauConferenciers as $conferencier): ?>
                <div class="cardAffichage shadow-sm border-0 h-100 position-relative me-3">
                    <!-- Icône crayon en haut à droite pour la modification -->
                    <form method="post" action="page_modif_conferenciers.php">
                        <button class="position-absolute top-0 end-0 m-2 text-primary border-0 bg-transparent">
                            <i class="fas fa-pencil-alt fs-4"></i>
                        </button>
                        <?php
                        $idConferencier = $conferencier['idConferencier'];
                        $nom = $conferencier['nomConferencier'];
                        $prenom = $conferencier['prenomConferencier'];
                        $telephone = $conferencier['telephone'];
                        $estEmploye = $conferencier['estEmploye'];
                        $specialites = $conferencier['specialites'];
                        $indisponibilites = $conferencier['indisponibilites'];

                        echo '<input type="hidden" name="id_conferencier" value="' . html_entity_decode($idConferencier, ENT_QUOTES, 'UTF-8') . '">';
                        echo '<input type="hidden" name="nom_conferencier" value="' . html_entity_decode($nom, ENT_QUOTES, 'UTF-8') . '">';
                        echo '<input type="hidden" name="prenom_conferencier" value="' . html_entity_decode($prenom, ENT_QUOTES, 'UTF-8') . '">';
                        echo '<input type="hidden" name="est_employe" value="' . html_entity_decode($estEmploye, ENT_QUOTES, 'UTF-8') . '">';
                        echo '<input type="hidden" name="specialites" value="' . html_entity_decode($specialites, ENT_QUOTES, 'UTF-8') . '">';
                        echo '<input type="hidden" name="indisponibilites" value="' . html_entity_decode($indisponibilites ?: 'Aucune', ENT_QUOTES, 'UTF-8') . '">';
                        ?>
                    </form>

                    <!-- Contenu de la card -->
                    <div class="cardAffichage-body text-black rounded text-start">
                        <span class="fs-5 fw-bold">Identifiant :</span> <?php echo html_entity_decode($idConferencier, ENT_QUOTES, 'UTF-8'); ?>
                        <br><br><span class="fs-5 fw-bold">Nom :</span> <?php echo html_entity_decode($nom, ENT_QUOTES, 'UTF-8'); ?>
                        <br><br><span class="fs-5 fw-bold">Prénom :</span> <?php echo html_entity_decode($prenom, ENT_QUOTES, 'UTF-8'); ?>
                        <br><br><span class="fs-5 fw-bold">Téléphone :</span> <?php echo html_entity_decode($telephone, ENT_QUOTES, 'UTF-8'); ?>
                        <br><br>

                        <!-- Contenu supplémentaire caché par défaut -->
                        <div id="extraContent<?php echo $idConferencier; ?>" style="display: none;">
                            <!-- Informations supplémentaires sur le conférencier -->
                            <br><span class="fs-5 fw-bold">Détails supplémentaires :</span> <br>
                            <!-- Ajoutez ici les détails supplémentaires -->
                            <span class="fs-5 fw-bold">Employé :</span> <?php echo ($estEmploye === 'OUI' ? 'Oui' : 'Non'); ?>
                            <br><br><span class="fs-5 fw-bold">Spécialités :</span> <?php echo html_entity_decode($specialites ?: 'Aucune', ENT_QUOTES, 'UTF-8'); ?>
                            <br><br><span class="fs-5 fw-bold">Indisponibilités :</span> <br><?php echo html_entity_decode($indisponibilites ?: 'Aucune', ENT_QUOTES, 'UTF-8'); ?>
                        </div>

                        <button id="toggleContent<?php echo $idConferencier; ?>"
                                class="btn btn-link p-0"
                                onclick="toggleCardContent('<?php echo $idConferencier; ?>')">
                            Plus
                        </button>
                    </div>

                    <!-- Bouton poubelle avec modal pour suppression -->
                    <a href="#" class="position-absolute bottom-0 end-0 m-2 text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $idConferencier; ?>">
                        <i class="fas fa-trash-alt fs-4"></i>
                    </a>

                    <!-- Modal de confirmation de suppression -->
                    <div class="modal fade" id="deleteModal<?php echo $idConferencier; ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?php echo $idConferencier; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel<?php echo $idConferencier; ?>">Confirmation de suppression</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                </div>
                                <div class="modal-body">
                                    Êtes-vous sûr de vouloir supprimer ce conférencier ?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    <form method="post" action="page_conferenciers.php?page=1">
                                        <input type="hidden" name="actionSuppression" value="supprimerConferencier">
                                        <input type="hidden" name="id_conferencier" value="<?php echo htmlentities($idConferencier, ENT_QUOTES); ?>">
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
    <?php if (count($tableauConferenciers) == 0) { ?>
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
                <th class="text-center">Nom</th>
                <th class="text-center">Prénom</th>
                <th class="text-center">Numéro de Téléphone</th>
                <th class="text-center">Login</th>

                <th class="text-center">Modifier</th>
                <th class="text-center">Supprimer</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($tableauConferenciers as $conferencier): ?>
                <tr>
                    <td><?php echo(html_entity_decode($conferencier['idConferencier'], ENT_QUOTES, 'UTF-8')) ?></td>
                    <td><?php echo(html_entity_decode($conferencier['nomConferencier'], ENT_QUOTES, 'UTF-8')) ?></td>
                    <td><?php echo(html_entity_decode($conferencier['prenomConferencier'], ENT_QUOTES, 'UTF-8')) ?></td>
                    <td><?php echo(html_entity_decode($conferencier['estEmploye'], ENT_QUOTES, 'UTF-8')) ?></td>
                    <td><?php echo(html_entity_decode($conferencier['specialites'], ENT_QUOTES, 'UTF-8')) ?></td>
                    <td class="text-center">
                        <form method="post" action="page_modif_conferenciers.php">
                            <button class="btn btn-link text-primary p-0">
                                <i class="fas fa-pencil-alt fs-4"></i>
                            </button>
                            <?php
                            $idConferencier = $conferencier['idConferencier'];
                            $nom = $conferencier['nomConferencier'];
                            $prenom = $conferencier['prenomConferencier'];
                            $estEmploye = $conferencier['estEmploye'];
                            $specialites = $conferencier['specialites'];
                            $indisponibilites = $conferencier['indisponibilites'];

                            echo '<input type="hidden" name="id_conferencier" value="' . html_entity_decode($idConferencier, ENT_QUOTES, 'UTF-8') . '">';
                            echo '<input type="hidden" name="nom_conferencier" value="' . html_entity_decode($nom, ENT_QUOTES, 'UTF-8') . '">';
                            echo '<input type="hidden" name="prenom_conferencier" value="' . html_entity_decode($prenom, ENT_QUOTES, 'UTF-8') . '">';
                            echo '<input type="hidden" name="est_employe" value="' . html_entity_decode($estEmploye, ENT_QUOTES, 'UTF-8') . '">';
                            echo '<input type="hidden" name="specialites" value="' . html_entity_decode($specialites, ENT_QUOTES, 'UTF-8') . '">';
                            echo '<input type="hidden" name="indisponibilites" value="' . html_entity_decode($indisponibilites ?: 'Aucune', ENT_QUOTES, 'UTF-8') . '">';
                            ?>
                        </form>
                    </td>
                    <td class="text-center">
                        <a href="#" class="text-danger" data-bs-toggle="modal" data-bs-target="#deleteModalTableau<?php echo $idEmp; ?>">
                            <i class="fas fa-trash-alt fs-4"></i>
                        </a>
                        <!-- Delete Confirmation Modal -->
                        <div class="modal fade" id="deleteModal<?php echo $idConferencier; ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?php echo $idConferencier; ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel<?php echo $idConferencier; ?>">Confirmation de suppression</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                    </div>
                                    <div class="modal-body">
                                        Êtes-vous sûr de vouloir supprimer ce conférencier ?
                                    </div>
                                    <div class="modal-footer">
                                        <!-- Bouton Annuler -->
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                        <!-- Bouton Confirmer -->
                                        <form method="post" action="page_conferenciers.php?page=1">
                                            <input type="hidden" name="actionSuppression" value="supprimerConferencier">
                                            <input type="hidden" name="id_conferencier" value="<?php echo htmlentities($idConferencier, ENT_QUOTES); ?>">
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
                        <p class="text-success">Le conférencier numéro <strong><?php echo htmlentities($_SESSION["idConferencierSupprimer"], ENT_QUOTES); ?></strong> a bien été supprimé.</p>
                    <?php else: ?>
                        <p class="text-danger">Le conférencier numéro <strong><?php echo htmlentities($_SESSION["idConferencierSupprimer"], ENT_QUOTES); ?></strong> n'a pas pu être supprimé.</p>
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