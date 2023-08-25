<?php
$url_base = "http://localhost/app/";
session_start();
?>
<!doctype html>
<html lang="en">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="../templates/style.css">
    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <script src="http://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
    <header>

        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarTogglerDemo01">

                    <ul class="nav navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" href="<?php echo $url_base ?>index.php" aria-current="page">Proyecto7400</a>
                        </li>
                        <?php if (isset($_SESSION['loggeado'])) { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo $url_base ?>secciones/inscripcion/">Inscripcion</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo $url_base ?>cerrar.php">Cerrar Sesion</a>
                            </li>
                        <?php } else { ?>

                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo $url_base ?>secciones/abono/">Abonos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo $url_base ?>secciones/cliente/">Clientes</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo $url_base ?>secciones/clase/">Clases</a>
                            </li>
                        <?php }
                        ?>

                    </ul>
                </div>
            </div>
        </nav>
    </header>


    <main class="container">