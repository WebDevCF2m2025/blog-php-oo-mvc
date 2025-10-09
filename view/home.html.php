<?php
// path: view/home.html.php
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mon Blog - Accueil</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
<!-- Navigation moderne pour articles -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="./">
            <i class="bi bi-newspaper me-2"></i>Mon Blog
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="./">
                        <i class="bi bi-house-door me-1"></i>Accueil
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./?p=admin">
                        <i class="bi bi-gear me-1"></i>Admin Articles
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./?c=admin">
                        <i class="bi bi-tags me-1"></i>Admin Catégories
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<div class="bg-primary text-white py-5 mb-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">
                    <i class="bi bi-journal-text me-3"></i>Bienvenue sur Mon Blog
                </h1>
                <p class="lead">Découvrez nos derniers articles et contenus exclusifs</p>
            </div>
        </div>
    </div>
</div>

<div class="container">

        <!-- État vide avec style moderne -->
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <div class="card border-0 shadow-sm">
                    <div class="card-body py-5">
                        <i class="bi bi-file-text display-1 text-muted mb-4"></i>
                        <h3 class="text-muted mb-3">Aucun article disponible</h3>
                        <p class="text-muted">Les premiers articles seront bientôt publiés !</p>
                        <a href="./?p=admin" class="btn btn-primary mt-3">
                            <i class="bi bi-plus-circle me-2"></i>Créer le premier article
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section des articles avec compteur -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h2 class="fw-bold text-primary mb-0">
                        <i class="bi bi-collection me-2"></i>Nos Articles
                    </h2>
                    <span class="badge bg-primary rounded-pill fs-6">
                        0 Article
                    </span>
                </div>
            </div>
        </div>

        <!-- Grid des articles -->
        <div class="row g-4">

                <div class="col-lg-6">
                    <div class="card h-100 border-0 shadow-sm hover-card">
                        <div class="card-header bg-white border-0 pb-0">
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="badge bg-primary">Article #</span>
                                <small class="text-muted">
                                    <i class="bi bi-calendar me-1"></i>
                                </small>
                            </div>
                        </div>
                        <div class="card-body">
                            <h4 class="card-title text-primary fw-bold mb-3">

                            </h4>
                            <p class="card-text text-muted">

                                    <span class="text-primary">... Lire la suite</span>

                            </p>
                        </div>
                        <div class="card-footer bg-white border-0">
                            <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-light text-primary">
                                <i class="bi bi-eye me-1"></i>
                                0 vue
                            </span>
                                <small class="text-muted">
                                    <i class="bi bi-clock me-1"></i>Lecture rapide
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

        </div>

</div>

<!-- Footer -->
<footer class="bg-dark text-white mt-5 py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <p class="mb-0">© <?=date("Y")?> Mon Blog. Tous droits réservés.</p>
            </div>
            <div class="col-md-4 text-md-end">
                <small class="text-muted">Propulsé par Bootstrap 5</small>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

