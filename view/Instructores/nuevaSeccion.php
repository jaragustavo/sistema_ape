<?php
require_once ("../../config/conexion.php");
if (isset ($_SESSION["usuario_id"])) {
    ?>
    <!DOCTYPE html>
    <html>
    <link rel="stylesheet" href="../Certificaciones/plugins/dropzone/dropzone.css" type="text/css">
    <?php require_once ("../MainHead/head.php"); ?>

    <!-- <link rel="stylesheet" href="plugins/css/dropzone.css"> -->

    <title>Nueva sección</title>
    </head>

    <body class="with-side-menu">

        <?php require_once ("../MainHeader/header.php"); ?>

        <div class="mobile-menu-left-overlay"></div>

        <?php require_once ("../MainNav/nav.php"); ?>

        <!-- Contenido -->
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xxl-9 col-lg-12 col-xl-8 col-md-8">
                        <section class="box-typical proj-page">
                            <section class="proj-page-section proj-page-header">
                                <div class="title">
                                    Nueva Seccion
                                    <i class="font-icon font-icon-pencil"></i>
                                </div>
                            </section><!--.proj-page-section-->

                            <!-- formulario -->
                            <section class="box-typical steps-icon-block" id="formulario_seccion">
                                <div class="container-fluid">
                                    <div class="row">
                                        <form method="post" id="datos_seccion_form">
                                            <input type="hidden" id="idEncrypted" name="idEncrypted">
                                            <input type="hidden" id="tramite_code" name="tramite_code" value="7">
                                            <div id="parte_2" class="row parte_2">
                                                <header class="steps-numeric-title">Datos de la seccion</header>
                                                <div class="row">
                                                    <div class="col-xl-6">
                                                        <div class="form-group">
                                                            <label for="titulo_leccion"
                                                                style="text-align:left;margin:5px;margin-left:30px;">Título</label>
                                                            <input type="text" class="form-control" id="titulo_leccion"
                                                                name="titulo_leccion" placeholder="Titulo" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-6">
                                                        <div class="form-group">
                                                            <label for="orden_leccion"
                                                                style="text-align:left;margin:5px;margin-left:30px;">Orden</label>
                                                            <input type="text" class="form-control" id="orden_leccion"
                                                                name="orden_leccion" placeholder="Orden" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div><!--.row-->
                                </div><!--.container-fluid-->
                            </section>
                        </section><!-- box-typical proj-page -->
                    </div>

                    <div class="col-xxl-3 col-lg-12 col-xl-4 col-md-4">
                        <section class="box-typical proj-page">
                            <section class="proj-page-section proj-page-time-info">
                                <div class="tbl">
                                    <div class="tbl-row">
                                        <div class="tbl-cell">Curso:
                                        </div>
                                        <div class="tbl-cell tbl-cell-time">4 semanas
                                        </div>
                                    </div>
                                </div>
                            </section><!--.proj-page-section-->

                            <section class="proj-page-section">
                                <ul class="proj-page-actions-list">
                                    <li onclick="guardarSeccion()"><a><i class="font-icon font-icon-check-square"></i>Crear
                                            sección</a></li>
                                            <li><a class="cancelar" id="cancelarSeccion" href="#"><i class="glyphicon glyphicon-trash"></i> Cancelar</a></li>
                                </ul>
                            </section><!--.proj-page-section-->
                        </section><!--.proj-page-->
                    </div>
                </div><!--.row-->
                
            </div><!--.container-fluid-->
        </div><!--.page-content-->
        <!-- Contenido -->

        <?php require_once ("../MainJs/js.php"); ?>
        <script src="../Certificaciones/plugins/dropzone/dropzone.js"></script>
        <script src="plugins/js/video-leccion.js"></script>
        <script type="text/javascript" src="instructores.js?v=<?php echo time(); ?>"></script>
        <?php require_once ("../html/footer.php"); ?>

    </body>

    </html>
    <?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>