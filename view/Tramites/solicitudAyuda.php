<?php
require_once ("../../config/conexion.php");
if (isset($_SESSION["usuario_id"])) {
    ?>
    <!DOCTYPE html>
    <html>
    <link rel="stylesheet" href="plugins/dropzone/dropzone.css" type="text/css">
    <?php require_once ("../MainHead/head.php"); ?>

    <!-- <link rel="stylesheet" href="plugins/css/dropzone.css"> -->

    <title>Solicitud de ayuda</title>
    </head>

    <body class="with-side-menu">

        <?php require_once ("../MainHeader/header.php"); ?>

        <div class="mobile-menu-left-overlay"></div>

        <?php require_once ("../MainNav/nav.php"); ?>

        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xxl-9 col-lg-12 col-xl-8 col-md-8">
                        <section class="box-typical proj-page">
                            <section class="proj-page-section proj-page-header">
                                <div class="title">
                                    Nueva solicitud
                                    <i class="font-icon font-icon-pencil"></i>
                                </div>
                            </section><!--.proj-page-section-->
                            
                            <form method="post" id="datos_solicitud_form">
                                <section class="proj-page-section">
                                    <label class="form-label semibold" for="observacion">Descripción de la situación</label>
                                    <div class="summernote-theme-1">
                                        <textarea id="observacion" name="observacion" class="summernote descripcion"
                                            name="name"></textarea>
                                    </div>
                                </section>
                            </form>
                        </section><!-- box-typical proj-page -->
                    </div>

                    <div class="col-xxl-3 col-lg-12 col-xl-4 col-md-4">
                        <section class="box-typical proj-page">
                            <input type="hidden" id="idEncrypted" name="idEncrypted">
                            <input type="hidden" id="tramite_code" name="tramite_code">
                            <section class="proj-page-section">
                                <ul class="proj-page-actions-list">
                                    <li onclick="guardarSolicitudAyuda(1)"><a><i
                                                class="font-icon font-icon-check-square"></i>Enviar solicitud</a></li>
                                    <li><a class="cancelar" href="listarSolicitudesAyuda.php"><i
                                                class="glyphicon glyphicon-trash"></i> Cancelar</a></li>
                                </ul>
                            </section><!--.proj-page-section-->
                        </section><!--.proj-page-->
                    </div>
                </div><!--.row-->
            </div><!--.container-fluid-->
        </div><!--.page-content-->

        <?php require_once ("../MainJs/js.php"); ?>
        <script type="text/javascript" src="tramites.js?v=<?php echo time(); ?>"></script>
        <?php require_once ("../html/footer.php"); ?>
		<script src="plugins/dropzone/dropzone.js"></script>

    </body>

    </html>
    <?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>