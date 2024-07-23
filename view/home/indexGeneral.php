<?php
require_once ("../../config/conexion.php");
if (isset($_SESSION["usuario_id"])) {
    ?>

    <!doctype html>
    <html lang="es" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
        data-sidebar-image="none">
    <?php require_once ("../MainHead/head.php"); ?>
    <title>APE | Home</title>

    <link rel="stylesheet" href="../../public/css/separate/elements/ribbons.min.css">
    <link rel="stylesheet" href="../../public/css/separate/pages/ribbons.min.css">
    </head>

    <body class="with-side-menu">
        <div class="mobile-menu-left-overlay"></div>
        <?php require_once ("../MainHeader/header.php"); ?>
        <?php require_once ("../MainNav/nav.php"); ?>

        <div class="page-content">
            <div class="container-fluid">

                <div class="alert alert-danger" role="alert" style="font-size:20px;text-align: center;">
                    <i class="font-icon font-icon-warning" style="color:#e06666;"></i>
                    Usted aún no se encuentra asociado a la APE. Para acceder a más beneficios, puede acudir a las oficinas
                    de la APE para solicitar ser socio.

                </div>
                <header class="section-header">
                    <div class="tbl">
                        <div class="tbl-row">
                            <div class="tbl-cell">
                                <h3>Inscripciones disponibles para estas certificaciones</h3>
                            </div>
                        </div>
                    </div>
                </header>
                <div class="row">
                    <div class="col-xl-3">
                        <a href="/sistema_ape/view/Certificaciones/inscripcion.php?code=35">
                            <div class="ribbon-block round relative text-center with-image">
                                <div class="background-image" style="background-image: url(../../public/img/adultos.png)">
                                    <!-- -->
                                </div>

                                <div class="ribbon green right-top">
                                    <i class="fa fa-heart"></i>
                                </div>
                                <span class="title">
                                    <strong>Enfermería en Área Crítica Adultos</strong>
                                </span>
                            </div>
                        </a>
                    </div><!--.col-->
                    <div class="col-xl-3">
                        <a href="/sistema_ape/view/Certificaciones/inscripcion.php?code=24">
                            <div class="ribbon-block round relative text-center with-image">
                                <div class="background-image" style="background-image: url(../../public/img/oncologia.jpg)">
                                    <!-- -->
                                </div>
                                <div class="ribbon purple right-top">
                                    <i class="fa fa-heart"></i>
                                </div>
                                <span class="title">
                                    <strong>Enfermería en Oncología</strong>
                                </span>
                            </div>
                        </a>
                    </div><!--.col-->
                    <div class="col-xl-3">
                        <a href="/sistema_ape/view/Certificaciones/inscripcion.php?code=26">
                            <div class="ribbon-block round relative text-center with-image">
                                <div class="background-image"
                                    style="background-image: url(../../public/img/enfermeros-geriatricos.jpg)"><!-- -->
                                </div>
                                <div class="ribbon yellow right-top">
                                    <i class="fa fa-heart"></i>
                                </div>
                                <span class="title">
                                    <strong>Enfermería en Geriatría</strong>
                                </span>
                            </div>
                        </a>
                    </div><!--.col-->
                    <div class="col-xl-3">
                        <a href="/sistema_ape/view/Certificaciones/inscripcion.php?code=22">
                            <div class="ribbon-block round relative text-center with-image">
                                <div class="background-image"
                                    style="background-image: url(../../public/img/salud_mental.jpg)">
                                    <!-- -->
                                </div>
                                <div class="ribbon red right-top">
                                    <i class="fa fa-heart"></i>
                                </div>
                                <span class="title">
                                    <strong>Enfermería en Salud Mental y Psiquiatría</strong>
                                </span>
                            </div>
                        </a>
                    </div><!--.col-->
                </div>
                <header class="section-header">
                    <div class="tbl">
                        <div class="tbl-row">
                            <div class="tbl-cell">
                                <h3>Más acciones</h3>
                            </div>
                        </div>
                    </div>
                </header>
                <div class="row">
                    <div class="col-xl-6">
                    <a href="docsAsociacion.php">
                            <div class="ribbon-block round relative text-center with-image">
                                <div class="background-image"
                                    style="background-image: url(../../public/img/Portal_Enfermería.jpeg)"><!-- --></div>
                                <div class="ribbon transparent right-top">
                                    <i class="fa fa-plus"></i>
                                    <span>APE</span>
                                </div>
                                <i class="block-icon fa fa-plus"></i>
                                <span class="title">
                                    <strong>Asociarme a la APE</strong>
                                </span>
                            </div>
                        </a>
                    </div><!--.col-->
                    <div class="col-xl-6">
                        <a href="../Perfiles/datosPersonales.php">
                            <div class="ribbon-block round relative text-center with-image">
                                <div class="background-image"
                                    style="background-image: url(../../public/img/perfil_index.jpeg)"><!-- --></div>
                                <div class="ribbon transparent right-top">
                                    <i class="fa fa-heart"></i>
                                </div>
                                <i class="block-icon fa fa-user"></i>
                                <span class="title">
                                    <strong>Mi perfil</strong>
                                </span>
                            </div>
                        </a>
                    </div><!--.col-->
                </div>
            </div>
        </div>

        <?php require_once ("../MainJs/js.php"); ?>
        <script src="home.js"></script>
        <?php require_once ("../html/footer.php"); ?>
    </body>

    </html>
    <?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>