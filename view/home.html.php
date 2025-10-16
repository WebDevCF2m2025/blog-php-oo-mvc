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
                    <a class="nav-link disabled text-white" href="#">
                        <i class="bi bi-tags me-1"></i>Catégories :
                    </a>
                </li>
                <?php
                foreach ($categoriesMenu as $category) :
                ?>
                <li class="nav-item">
                    <a class="nav-link" href="./category/<?=$category->getCategorySlug()?>">
                        <?=$category->getCategoryTitle()?>
                    </a>
                <?php
                endforeach;
                ?>
                <li class="nav-item">
                    <a class="nav-link" href="./connection/">
                        <i class="bi bi-gear me-1"></i>Connexion
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
<!-- Vérification si la liste des articles est vide -->
    <?php
    if(empty($articles)):
    ?>
        <!-- État vide avec style moderne -->
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <div class="card border-0 shadow-sm">
                    <div class="card-body py-5">
                        <i class="bi bi-file-text display-1 text-muted mb-4"></i>
                        <h3 class="text-muted mb-3">Aucun article disponible</h3>
                        <p class="text-muted">Les premiers articles seront bientôt publiés !</p>
                        <a href="./?p=admin" class="btn btn-primary mt-3">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php
    else:
    $nbArticles = count($articles);
    $pluriel = ($nbArticles>1) ? "s" : "";
    ?>
        <!-- Section des articles avec compteur -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h2 class="fw-bold text-primary mb-0">
                        <i class="bi bi-collection me-2"></i>Nos Articles
                    </h2>
                    <span class="badge bg-primary rounded-pill fs-6">
                        <?=$nbArticles?> Article<?=$pluriel?>
                    </span>
                </div>
            </div>
        </div>
    <!-- Grid des articles -->
    <div class="row g-4">
        <?php
        foreach ($articles as $article) :
        ?>

                <div class="col-lg-6">
                    <div class="card h-100 border-0 shadow-sm hover-card">
                        <div class="card-header bg-white border-0 pb-0">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                <?php
                                // affichage des catégories de l'article
                                $categories = $article->getCategories();
                                if(!empty($categories)):
                                    foreach ($categories as $category):
                                ?>
                                <span class="badge bg-primary  "><a class="text-white" href="./category/<?=$category->getCategorySlug()?>">
                                        <?=$category->getCategoryTitle()?></a></span>
                                <?php
                                    endforeach;
                                endif;
                                ?>
                                </div>
                                <small class="text-muted">
                                    <i class="bi bi-calendar me-1"></i><?=$article->getArticleDatePublish()?></small>
                            </div>
                        </div>
                        <div class="card-body">
                            <h4 class="card-title text-primary fw-bold mb-3">
                                <?=html_entity_decode($article->getArticleTitle())?></h4>
                            <p class="card-text text-muted">
                                <?=html_entity_decode($article->getArticleText())?><span class="text-primary"> <a href="./article/<?=$article->getArticleSlug()?>">Lire la suite</a></span>
                            </p>
                        </div>
                        <div class="card-footer bg-white border-0">
                            <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-light text-primary">
                                <i class="bi bi-eye me-1"></i>
                                Publié par <?php
                                // affichage du UserRealName, si null (??) on affiche UserLogin
                                echo $article->getUser()->getUserRealName() ?? $article->getUser()->getUserLogin()?></span>
                                <small class="text-muted">
                                    <i class="bi bi-clock me-1"></i>Lecture rapide
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

    <?php
        endforeach;
    endif;
    ?>
<?php
//var_dump($articles);
?>

    </div>
</div>
<!-- Footer -->
<footer class="bg-dark text-white mt-5 py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <p class="mb-0">© <?=date("Y")?> Mon Blog d'exemple. Aucun droit réservé.</p>
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

