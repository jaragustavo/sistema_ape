<?php
require_once ("../../config/conexion.php");
if (isset ($_SESSION["usuario_id"])) {
    ?>
    <!DOCTYPE html>
    <html>
    <?php require_once ("../MainHead/head.php"); ?>
    <title>Sirepro::Lecciones</title>
    </head>
    <style>
        .inline-label {
            display: inline-block;
        }
    </style>

    <body class="with-side-menu">

        <?php require_once ("../MainHeader/header.php"); ?>

        <div class="mobile-menu-left-overlay"></div>

        <?php require_once ("../MainNav/nav.php"); ?>

        <!-- Contenido -->
        <div class="page-content">
            <div class="container-fluid">
                <header class="section-header" style="margin-top: -40px;margin-bottom: -30px;">
                    <div class="tbl">
                        <input type="hidden" id="tipo_solicitud" name="tipo_solicitud" value="">

                        <div class="tbl-row">
                            <div class="tbl-cell">
                                <h3>Secciones</h3>
                            </div>
                            <div class="tbl-cell">
                                <div class="col-lg-6">
                                    <fieldset class="form-group">
                                        <label class="form-label" for="btnfiltrar">&nbsp;</label>
                                        <a href="cursosInstructores.php"><button
                                                class="btn btn-rounded btn-secondary btn-block">
                                                Volver al listado</button></a>
                                    </fieldset>
                                </div>
                                <div class="col-lg-6">
                                    <fieldset class="form-group">
                                        <label class="form-label" for="btnfiltrar">&nbsp;</label>
                                        <button onclick="nuevaSeccion()"
                                                class="btn btn-rounded btn-primary btn-block">Nueva Sección</button>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
                <section class="card card-blue">
                    <header class="card-header">
                        Datos del Curso
                        <label class="card-text inline-label" style="color:#444444;" id="nombre_tramite"></label>
                    </header>
                    <?php
                    require_once "../../models/Certificacion.php";
                    $id_curso = $_GET["IDCURSO"];
                    $id_curso = str_replace(' ', '+', $id_curso);
                    $key = "mi_key_secret";
                    $cipher = "aes-256-cbc";
                    $iv_dec = substr(base64_decode($id_curso), 0, openssl_cipher_iv_length($cipher));
                    $cifradoSinIV = substr(base64_decode($id_curso), openssl_cipher_iv_length($cipher));
                    $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);
                    $curso = Certificacion::get_informacion_curso($decifrado);
                    ?>
                    <input type="hidden" id="idEncrypted" value=<?php echo $id_curso ?>>
                    <div class="row" style="font-size: 18px;">
                        <div class="col-md-12">
                            <div class="card-block">
                                <div class="project"><b>Curso: </b> <label class="card-text inline-label"
                                        id="nombre_curso"><?php echo $curso["nombre_curso"] ?></label></div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="font-size: 14px;">
                        
                        <div class="col-md-4">
                            <div class="card-block">
                                <div class="project"><b>Fecha de creación: </b> <label class="card-text inline-label"
                                        id="fecha_creacion"><?php echo $curso["fecha_curso"] ?></label></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card-block">
                                <div class="project"><b>Categoría: </b> <label class="card-text inline-label"
                                        id="categoria"><?php echo $curso["nombre_categoria"] ?></label></div>
                            </div>
                        </div>
                    </div>
                </section>
                <div class="box-typical box-typical-padding">
                    <div class="box-typical box-typical-padding" id="table">
                        <table id="secciones_data"
                            class="table table-bordered table-striped table-vcenter js-dataTable-full">
                            <thead>
                                <tr>
                                    <th style="width: 1%;">Sección N°</th>
                                    <th class="d-none d-sm-table-cell" style="width: 5%">Título</th>
                                    <th class="d-none d-sm-table-cell" style="width: 1%;">Lecciones</th>
                                    <th class="d-none d-sm-table-cell" style="width: 1%;">Materiales</th>
                                    <th class="d-none d-sm-table-cell" style="width: 1%;">Tareas</th>
                                    <th class="d-none d-sm-table-cell" style="width: 1%">Acciones</th>

                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>

                </div>

            </div>
        </div>
        <!-- Contenido -->

        <?php require_once ("../MainJs/js.php"); ?>

        <script src="dropzone/dropzone.js"></script>
        <script type="text/javascript" src="instructores.js?v=<?php echo time(); ?>"></script>
        <?php require_once ("../html/footer.php"); ?>

        <!-- <script type="text/javascript" src="../notificacion.js"></script> -->

    </body>

    </html>
    <?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>