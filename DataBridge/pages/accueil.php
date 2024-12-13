<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataBridge</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="outils/bootstrap-5.3.2-dist/css/bootstrap.css">
    <link rel="stylesheet" href="CSS/styleIndex.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-light bg-light">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <!-- Bouton Hamburger -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Logo et Titre alignés et centrés -->
        <div class="d-flex align-items-center justify-content-center mx-auto text-center" style="position: absolute; left: 50%; transform: translateX(-50%);">
            <img src="../images/logo.png" class="logo-size me-2 w-25" alt="Logo">
            <a class="navbar-brand logo-size mb-0 fs-1" href="#">DataBridge</a>
        </div>

        <!-- Bouton et Login -->
        <div class="text-center">
            <span class="me-3 mt-3">Nom du login</span><br>
            <a href="logout.php" class="btn btn-danger mt-3">Se Déconnecter</a><br><br><br>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container text-center mt-5">
    <h1 class="mb-5 fs-1 ">Bienvenue dans le portail de gestion DataBridge</h1>
    <p class="mb-5 fs-4">S&eacute;lectionnez une cat&eacute;gorie pour commencer.</p>

    <div class="row row-cols-1 row-cols-md-4 g-4">
        <!-- Gestion des Comptes Employés -->
        <div class="col">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body bg-primary text-white rounded">
                    <h5 class="card-title fs-3 mb-5">Gestion des Comptes Employ&eacute;s</h5>
                    <i class="fa fa-user fa-5x mb-2"></i>
                </div>
            </div>
        </div>

        <!-- Gestion des Conférenciers -->
        <div class="col">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body bg-primary text-white rounded">
                    <h5 class="card-title fs-3 mb-5">Gestion des Conf&eacute;renciers</h5>
                    <i class="fas fa-chalkboard-teacher fa-5x mb-3"></i>
                </div>
            </div>
        </div>

        <!-- Gestion des Expositions -->
        <div class="col">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body bg-primary text-white rounded">
                    <h5 class="card-title fs-3 mb-5">Gestion des Expositions</h5>
                    <i class="fas fa-image fa-5x mb-3"></i>
                </div>
            </div>
        </div>

        <!-- Gestion des Visites -->
        <div class="col">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body bg-primary text-white rounded">
                    <h5 class="card-title fs-3 mb-5">Gestion des <br>Visites</h5>
                    <i class="fas fa-clock fa-5x mb-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="mt-5 fixed-bottom bg-light text-center py-3">
    <p>&copy; 2024 DataBridge. Tous droits r&eacute;serv&eacute;s.</p>
</footer>
</body>
</html>
