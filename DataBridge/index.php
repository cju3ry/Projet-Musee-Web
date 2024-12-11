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
    <div class="container w-50 d-flex align-items-center min-vh-100">
        <div class="row  w-100">
            <!-- Faire le responsivitée -->
            <div class="col-1 "></div>
            <div class="col-10">
                <div class="row bordure w-100 fondGris">
                    <div class="col-2">
                        <img src="logo.png" class="logo-size" alt="image logo">
                    </div>
                    <div class="col-8 fs-1 text-center align-self-center fw-bold">Data Bridge</div>
                    <div class="col-2"></div>
                    <div class="col-1 ">
                    </div>
                    <!--
                    <div class="col-8  text-center fs-1 text fw-bold text-bg-info raduisTitle">
                        Authentification
                        <i class="fa-solid fa-question center-icon " style="color: #ffffff; "></i>
                    </div>
                    -->
                    <div class="col-10 d-flex align-items-center justify-content-center position-relative text-center fs-1 fw-bold text-bg-info raduisTitle marginTopDiv">
                        Authentification
                        <i class="fa-solid fa-question position-absolute end-0 top-50 translate-middle-y" style="color: #ffffff;">&nbsp;</i>
                    </div>

                    <div class="col-1 ">
                    </div>
                    <form method="post" action="index.php">
                        <div class="col-12 text-center fs-3 marginTopDiv marginBottomDiv">
                            Identifiant
                        </div>
                        <div class="col-12 text-center fs-5">
                            <i class="fa fa-user">&nbsp;</i>
                            <input type="text" name="Identifiant" id="Identifiant" placeholder="Identifiant"
                                   class="raduisInput <?= $erreurConnexion ? 'bordure-rouge' : '' ?>">
                        </div>
                        <div class="col-12 text-center fs-3 marginTopDiv marginBottomDiv">
                            Mot de passe
                        </div>
                        <div class="col-12 text-center fs-5">
                            <i class="fa fa-lock">&nbsp;</i>
                            <input type="password" name="motDePasse" id="motDePasse" placeholder="Mot de passe"
                                   class="raduisInput <?= $erreurConnexion ? 'bordure-rouge' : '' ?>">
                        </div>
                        <?php if ($erreurConnexion): ?>
                            <div class="col-12 text-center text-danger fs-6">
                                <br/>
                                Identifiant ou mot de passe incorrect.
                            </div>
                        <?php endif; ?>

                        <div class="col-12  text-center margeHautBtn">
                            <br>
                            <button type="submit" class="btn btn-info btn-lg marginBtn">Se Connecter</button>
                            <br>
                            <br>
                        </div>
                    </form>
                </div>
            </div>
            <!-- <div class="col-1"></div> -->
        </div>
    </div>
</form>
</body>
</html>