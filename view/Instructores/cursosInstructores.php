<?php
require_once("../../config/conexion.php");
if (isset($_SESSION["usuario_id"])) {
    ?>
    <!DOCTYPE html>
    <html>
    <?php require_once("../MainHead/head.php"); ?>
    <title>APE - Mis capacitaciones</title>
    </head>

    <body class="with-side-menu">

        <?php require_once("../MainHeader/header.php"); ?>

        <div class="mobile-menu-left-overlay"></div>

        <?php require_once("../MainNav/nav.php"); ?>

        <!-- Contenido -->
        <div class="page-content">
            <div class="container-fluid">
            <input type="hidden" id="tipo_solicitud" name="tipo_solicitud" value="CURSO">
                <header class="section-header">
                    <div class="tbl">
                        <div class="tbl-row">
                            <div class="tbl-cell">
                                <h3>Mis capacitaciones</h3>
                            </div>
                            <div class="tbl-cell">
                                <div class="col-lg-6"></div>
                                <div class="col-lg-6">
                                    <fieldset class="form-group">
                                        <label class="form-label" for="btnfiltrar">&nbsp;</label>
                                        <a href="nuevoCurso.php"><button class="btn btn-rounded btn-primary btn-block">Nueva
                                                Capacitación</button></a>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
            <div class="box-typical box-typical-padding" id="table">
                <table id="mis_cursos_data"
                    class="table table-bordered table-striped table-vcenter js-dataTable-full">
                    <thead>
                        <tr>
                            <th style="width: 3%;">Categoría</th>
                            <th style="width: 10%;">Capacitacion</th>
                            <th style="width: 10%;">Curso</th>
                            <th class="d-none d-sm-table-cell" style="width: 5%;">Fecha creación</th>
                            <th class="d-none d-sm-table-cell" style="width: 1%;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
        </div>
        <!-- Contenido -->

        <?php require_once("../MainJs/js.php"); ?>

        <script type="text/javascript" src="instructores.js?v=<?php echo time(); ?>"></script>
        <?php require_once("../html/footer.php"); ?>

    </body>

    </html>
    <?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>