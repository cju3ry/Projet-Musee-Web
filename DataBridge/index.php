<?php
session_start();
include("fonction/fonction.php");
$login = "";
$mdp = "";
$tableauLoginEmploye = [];
$tableauLoginAdmin = [];
$erreurConnexion = false;
$_SESSION["loginEmploye"] = "";
$_SESSION["loginAdmin"] = "";

if(isset($_POST['Identifiant'])) {
    $login = htmlspecialchars($_POST['Identifiant']);
}

if(isset($_POST['motDePasse'])) {
    $mdp = htmlspecialchars($_POST['motDePasse']);
}

try {
    $pdo = connecterBd('databridge');
    if($mdp != "" && $login !="") {
        $tableauLoginEmploye = loginEmploye($pdo, $login, $mdp);
        $tableauLoginAdmin = loginAdmin($pdo, $login, $mdp);

        if(count($tableauLoginEmploye) != 0) {
            $_SESSION["loginEmploye"] = $login;
            header('Location: pages/accueil.php');
        } elseif(count($tableauLoginAdmin) != 0) {
            $_SESSION["loginAdmin"] = $login;
            header('Location: pages/accueil.php');
        } else {
            $erreurConnexion = true;
        }
    }
} catch (PDOException $e) {
    header('Location: pages/erreurConnexion.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Page d'authentification</title>
    <link rel="stylesheet" href="outils/bootstrap-5.3.2-dist/css/bootstrap.css">
    <link rel="stylesheet" href="CSS/styleIndex.css">
    <link rel="stylesheet" href="outils/fontawesome-free-6.5.1-web/css/all.css">
</head>
<body>
<form method="post" action="index.php">
    <div class="container-fluid min-vh-100">
        <div class="row min-vh-100">
            <!-- Colonne gauche avec le formulaire -->
            <div class="col-lg-6 d-flex align-items-center justify-content-center">
                <div class="w-75">
                    <div class="d-flex justify-content-center align-items-center mb-4">
                        <img src="images/logo.png" class="logo-size margeTitre">
                        <h1 class="margeTitre ms-3 text-center align-self-center"><span class="fw-bold">Data</span>Bridge</h1>
                    </div>
                    <div class="text-center fs-2 text-bg-info py-2 rounded mt-3 d-flex align-items-center justify-content-center position-relative text-center fs-1 fw-bold text-bg-info">
                        Authentification
                        <i class="fa-solid fa-question position-absolute end-0 top-50 translate-middle-y" style="color: #ffffff;">&nbsp;</i>
                    </div>
                    <div class="mt-4">
                        <label for="Identifiant" class="form-label fs-3">Identifiant</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fa fa-user"></i></span>
                            <input type="text" name="Identifiant" id="Identifiant" placeholder="Identifiant"
                                   class="form-control <?= $erreurConnexion ? 'is-invalid' : '' ?>">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="motDePasse" class="form-label fs-3">Mot de passe</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fa fa-lock"></i></span>
                            <input type="password" name="motDePasse" id="motDePasse" placeholder="Mot de passe"
                                   class="form-control <?= $erreurConnexion ? 'is-invalid' : '' ?>">
                        </div>
                    </div>
                    <?php if ($erreurConnexion): ?>
                        <div class="text-danger fs-6 text-center">
                            Identifiant ou mot de passe incorrect.
                        </div>
                    <?php endif; ?>
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-info btn-lg margeBtnSeConnecter">Se Connecter</button>
                    </div>
                </div>
            </div>
            <!-- Colonne droite avec l'image -->
            <div class="col-lg-6 large-image d-none d-sm-block">
            </div>
        </div>
    </div>
</form>
</body>
</html>
