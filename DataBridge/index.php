<?php
session_start();
include("fonction/fonctionsAuthentification.php");

$login = "";
$mdp = "";
$tableauLoginEmploye = [];
$tableauLoginAdmin = [];
$erreurConnexion = false;

if (isset($_COOKIE['login']) && isset($_COOKIE['mdp'])) {
    $login = $_COOKIE['login'];
    $mdp = $_COOKIE['mdp'];
}

if (isset($_POST['Identifiant']) && isset($_POST['motDePasse'])) {
    $login = htmlspecialchars($_POST['Identifiant']);
    $mdp = htmlspecialchars($_POST['motDePasse']);

    try {
        $pdo = connecterBd();
        if ($mdp != "" && $login != "") {
            $tableauLoginEmploye = loginEmploye($pdo, $login, $mdp);
            $tabIdEmployeAuthentifier = getIdEmploye($pdo, $login);
            setIdEmploye($pdo, $tabIdEmployeAuthentifier['idEmploye']);
            $tableauLoginAdmin = loginAdmin($pdo, $login, $mdp);

            if (count($tableauLoginEmploye) != 0) {
                $_SESSION["loginEmploye"] = $login;

                setcookie('login', $login, time() + (86400 * 30), "/");
                setcookie('mdp', $mdp, time() + (86400 * 30), "/");

                header('Location: pages/accueil/accueil_employe.php');
                exit();
            } elseif (count($tableauLoginAdmin) != 0) {
                $_SESSION["loginAdmin"] = $login;

                setcookie('login', $login, time() + (86400 * 30), "/");
                setcookie('mdp', $mdp, time() + (86400 * 30), "/");

                header('Location: pages/accueil/accueil_admin.php');
                exit();
            } else {
                $erreurConnexion = true;
            }
        }
    } catch (PDOException $e) {
        header('Location: pages/erreurConnexion.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Page d'authentification</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/styleIndex.css">
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
                        <i class="fa-solid fa-question position-absolute end-0 top-50 translate-middle-y" style="color: #ffffff;" title="Entrez votre identifiant et votre mot de passe">&nbsp;</i>
                    </div>
                    <div class="mt-4">
                        <label for="Identifiant" class="form-label fs-3">Identifiant</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fa fa-user"></i></span>
                            <input 
                                type="text" 
                                name="Identifiant" 
                                id="Identifiant" 
                                placeholder="Identifiant"
                                class="form-control <?= $erreurConnexion ? 'is-invalid' : '' ?>" 
                                value="<?= htmlspecialchars($login) ?>">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="motDePasse" class="form-label fs-3">Mot de passe</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fa fa-lock"></i></span>
                            <input type="password" name="motDePasse" id="motDePasse" placeholder="Mot de passe"
                                   class="form-control <?= $erreurConnexion ? 'is-invalid' : '' ?>" 
                                   value="<?= htmlspecialchars($mdp) ?>">
                            <span class="input-group-text toggle-password">
                                <i class="fa fa-eye-slash"></i>
                            </span>
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
<script src="javaScript/scriptGeneraux/scriptMotDePasseIndex.js"></script>
</body>
</html>
