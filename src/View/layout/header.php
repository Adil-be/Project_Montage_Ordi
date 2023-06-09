<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $pageTitle ?>
    </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <?php
    ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-3">
        <div class="container-fluid">
            <a class="navbar-brand" href="?">Montage Ordi</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText"
                aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 justify-content-between">
                    <?php
                    // - - - - - - NAV CONCEPTEUR - - - - - -
                    if (isset($_SESSION['user']) && ($_SESSION['user']->getRole() == "concepteur")) {
                        ?>
                        <li class="nav-item">
                            <a class="nav-link" href="?page=listPiece">Liste des pièces</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="?page=listModel">liste des modèles</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="?page=modelCreation">Creation d'un modèle</a>
                        </li>

                    <?php }
                    // - - - - - - NAV MONTEUR - - - - - -
                    if (isset($_SESSION['user']) && $_SESSION['user']->getRole() == "monteur") {
                        ?>
                        <li class="nav-item">
                            <a class="nav-link" href="?page=listModel">liste des modèles</a>
                        </li>
                    <?php } ?>
                    <!-- - - - - - - NAV UTILISATEUR NON CONNECTE - - - - - - -->
                    <li class="nav-item">
                        <?php if (isset($_SESSION['user'])) { ?>
                            <a class="nav-link active" aria-current="page" href="?page=login">
                                <?= $_SESSION['user']->getUsername(); ?>
                            </a>
                        <?php } else { ?>
                            <a class="nav-link active" aria-current="page" href="?page=login">Login</a>
                        <?php } ?>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?page=logout">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>