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

                <div class="alert alert-success" role="alert" style="font-size:20px;text-align: center;">
                    Si desea asociarse a la APE, verifique en las secciones más abajo los documentos que necesita según
                    corresponda. <br>
                    Puede descargarlos, completarlos y firmarlos, para luego presentar su solicitud en las oficinas
                    de la APE.
                </div>
                <header class="section-header">
                    <div class="tbl">
                        <div class="tbl-row">
                            <div class="tbl-cell">
                                <h3>Formularios para Funcionarios de IPS</h3>
                            </div>
                        </div>
                    </div>
                </header>
                <div class="row">
                    <div class="col-xl-3">
                        <a href="/sistema_ape/docs/ape/Autorización desc. Automático IPS.pdf" target="_blank">
                            <div class="ribbon-block round relative text-center with-image">
                                <div class="background-image" style="background-image: url(../../public/img/pdf.png)">
                                    <!-- -->
                                </div>
                                <span class="title">
                                    <strong>Autorización de Descuento Automático</strong>
                                </span>
                            </div>
                        </a>
                    </div><!--.col-->
                    <div class="col-xl-3">
                        <a href="/sistema_ape/docs/ape/Formulario IPS 2024.pdf" target="_blank">
                            <div class="ribbon-block round relative text-center with-image">
                                <div class="background-image" style="background-image: url(../../public/img/pdf.png)">
                                    <!-- -->
                                </div>
                                <span class="title">
                                    <strong>Inscripción</strong>
                                </span>
                            </div>
                        </a>
                    </div><!--.col-->
                </div>
                <header class="section-header">
                    <div class="tbl">
                        <div class="tbl-row">
                            <div class="tbl-cell">
                                <h3>Formularios para Funcionarios del MSPyBS</h3>
                            </div>
                        </div>
                    </div>
                </header>
                <div class="row">
                    <div class="col-xl-3">
                        <a href="/sistema_ape/docs/ape/LIC. PERMANENTE.pdf" target="_blank">
                            <div class="ribbon-block round relative text-center with-image">
                                <div class="background-image" style="background-image: url(../../public/img/pdf.png)">
                                    <!-- -->
                                </div>
                                <span class="title">
                                    <strong>Licenciado Permanente</strong>
                                </span>
                            </div>
                        </a>
                    </div><!--.col-->
                    <div class="col-xl-3">
                        <a href="/sistema_ape/docs/ape/LIC.CONTRATADO.pdf" target="_blank">
                            <div class="ribbon-block round relative text-center with-image">
                                <div class="background-image" style="background-image: url(../../public/img/pdf.png)">
                                    <!-- -->
                                </div>
                                <span class="title">
                                    <strong>Licenciado Contratado</strong>
                                </span>
                            </div>
                        </a>
                    </div><!--.col-->
                </div>
                
                <div class="row">
                    <div class="col-xl-3">
                        <a href="/sistema_ape/docs/ape/LIC. PERMANENTE.pdf" target="_blank">
                            <div class="ribbon-block round relative text-center with-image">
                                <div class="background-image" style="background-image: url(../../public/img/pdf.png)">
                                    <!-- -->
                                </div>
                                <span class="title">
                                    <strong>Técnico Permanente</strong>
                                </span>
                            </div>
                        </a>
                    </div><!--.col-->
                    <div class="col-xl-3">
                        <a href="/sistema_ape/docs/ape/LIC.CONTRATADO.pdf" target="_blank">
                            <div class="ribbon-block round relative text-center with-image">
                                <div class="background-image" style="background-image: url(../../public/img/pdf.png)">
                                    <!-- -->
                                </div>
                                <span class="title">
                                    <strong>Técnico Contratado</strong>
                                </span>
                            </div>
                        </a>
                    </div><!--.col-->
                </div>

                
                <div class="row">
                    <div class="col-xl-3">
                        <a href="/sistema_ape/docs/ape/LIC. PERMANENTE.pdf" target="_blank">
                            <div class="ribbon-block round relative text-center with-image">
                                <div class="background-image" style="background-image: url(../../public/img/pdf.png)">
                                    <!-- -->
                                </div>
                                <span class="title">
                                    <strong>Auxiliar Permanente</strong>
                                </span>
                            </div>
                        </a>
                    </div><!--.col-->
                    <div class="col-xl-3">
                        <a href="/sistema_ape/docs/ape/LIC.CONTRATADO.pdf" target="_blank">
                            <div class="ribbon-block round relative text-center with-image">
                                <div class="background-image" style="background-image: url(../../public/img/pdf.png)">
                                    <!-- -->
                                </div>
                                <span class="title">
                                    <strong>Auxiliar Contratado</strong>
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