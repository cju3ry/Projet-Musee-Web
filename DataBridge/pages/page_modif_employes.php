<?php
    $nom = isset($_POST['nom_employe']) ? $_POST['nom_employe'] : '';
    $prenom = isset($_POST['prenom_employe']) ? $_POST['prenom_employe'] : '';
    $telephone = isset($_POST['telephone_employe']) ? $_POST['telephone_employe'] : '';
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
            <a class="navbar-brand logo-size mb-0 fs-1 text-white" href="#">DataBridge</a>
        </div>

        <!-- Bouton et Login -->
        <div class="text-center">
            <span class="me-3 mt-3 text-white">Adrian CAZOR--BONNET</span><br>
            <form method="post" action="logout.php">
                <button type="submit" class="btn btn-danger mt-3">Se déconnecter</button><br><br><br>
            </form>
        </div>
    </div>
</nav>
<!-- Formulaire de modification -->
<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <br><br><br><br>
            <div class="card shadow p-4 rounded-3">
                <h2 class="text-center mb-4">Modifier les informations</h2>

                <form action="modifier_employe.php" method="post">
                    <!-- Champ Nom -->
                    <div class="mb-3">
                        <label for="nom" class="form-label fw-bold">Nom</label>
                        <input type="text" class="form-control" id="nom" name="nom" placeholder="Entrez le nom"
                               value="<?php echo htmlentities($nom, ENT_QUOTES); ?>" required>
                    </div>

                    <!-- Champ Prénom -->
                    <div class="mb-3">
                        <label for="prenom" class="form-label fw-bold">Prénom</label>
                        <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Entrez le prénom"
                               value="<?php echo htmlentities($prenom, ENT_QUOTES); ?>" required>
                    </div>

                    <!-- Champ Téléphone -->
                    <div class="mb-3">
                        <label for="telephone" class="form-label fw-bold">Téléphone</label>
                        <input type="tel" class="form-control" id="telephone" name="telephone" placeholder="5432" pattern="^[0-9]{4}$"
                               value="<?php echo htmlentities($telephone, ENT_QUOTES); ?>" required>
                        <small class="form-text text-muted">Format: 4 chiffres</small>
                    </div>

                    <!-- Bouton de soumission -->
                    <div class="text-center position-relative">
                        <button type="submit" class="btn btn-primary fw-bold px-4">
                            <i class="fas fa-save"></i>&nbsp;&nbsp;Enregistrer
                        </button>
                    </div>
                </form>
                    <!-- Bouton Annuler en dehors du formulaire principal -->
                <form method="post" action="page_employes.php">
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
</body>
</html>
