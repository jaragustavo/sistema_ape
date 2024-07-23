<?php
require_once ("../../config/conexion.php");
if (isset($_SESSION["usuario_id"])) {
    ?>
    <!DOCTYPE html>
    <html>
    <link rel="stylesheet" href="../Certificaciones/plugins/dropzone/dropzone.css" type="text/css">
    <?php require_once ("../MainHead/head.php"); ?>

    <!-- <link rel="stylesheet" href="plugins/css/dropzone.css"> -->

    <title>Editar sección</title>
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
                            <input type="hidden" id="tipo_solicitud" name="tipo_solicitud" value="">

                            <!-- formulario -->
                            <section class="box-typical steps-icon-block" id="formulario_seccion">
                                <div class="container-fluid">
                                    <?php
                                    $id_seccion = $_GET["IDSECCION"];
                                    $id_seccion = str_replace(' ', '+', $id_seccion);
                                    $key = "mi_key_secret";
                                    $cipher = "aes-256-cbc";
                                    $iv_dec = substr(base64_decode($id_seccion), 0, openssl_cipher_iv_length($cipher));
                                    $cifradoSinIV = substr(base64_decode($id_seccion), openssl_cipher_iv_length($cipher));
                                    $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);
                                    require ("../../models/Instructor.php");
                                    $seccion = Instructor::get_info_seccion($decifrado);
                                    $cifrado = openssl_encrypt($seccion['curso_id'], $cipher, $key, OPENSSL_RAW_DATA, $iv);
                                    $textoCifrado = base64_encode($iv . $cifrado);
                                    $id_curso = $textoCifrado;
                                    ?>
                                    <div class="row">
                                        <form method="post" id="datos_seccion_form">
                                            <input type="hidden" id="idEncrypted" name="idEncrypted">
                                            <input type="hidden" id="idEncryptedCurso" name="idEncryptedCurso"
                                                value="<?php echo $id_curso ?>">
                                            <input type="hidden" id="tramite_code" name="tramite_code">
                                            <div id="parte_2" class="row parte_2">
                                                <header class="steps-numeric-title">Datos de la seccion</header>
                                                <div class="row">
                                                    <div class="col-xl-6">
                                                        <div class="form-group">
                                                            <label for="titulo"
                                                                style="text-align:left;margin:5px;margin-left:30px;">Título</label>
                                                            <input type="text" class="form-control" id="titulo"
                                                                name="titulo" placeholder="Titulo"
                                                                value="<?php echo $seccion['titulo'] ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-6">
                                                        <div class="form-group">
                                                            <label for="orden"
                                                                style="text-align:left;margin:5px;margin-left:30px;">Orden</label>
                                                            <input type="text" class="form-control" id="orden" name="orden"
                                                                placeholder="Orden"
                                                                value="<?php echo $seccion['orden'] ?>" />
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
                                    <li onclick="guardarSeccion()"><a><i
                                                class="font-icon font-icon-check-square"></i>Guardar
                                            sección</a></li>
                                    <li><a class="cancelar" id="cancelar"
                                            href="administrarSecciones.php?IDCURSO=<?php echo $id_curso ?>"><i
                                                class="glyphicon glyphicon-trash"></i> Cancelar</a></li>
                                </ul>
                            </section><!--.proj-page-section-->
                        </section><!--.proj-page-->
                    </div>
                </div><!--.row-->
                <div class="row">
                    <header class="section-header" style="margin-top: -40px;margin-bottom: -30px;">
                        <div class="tbl">
                            <div class="tbl-row">
                                <div class="tbl-cell">
                                    <h3>Lecciones de esta sección</h3>
                                </div>
                                <div class="tbl-cell">
                                    <div class="col-lg-6"></div>
                                    <div class="col-lg-6">
                                        <fieldset class="form-group">
                                            <label class="form-label" for="btnfiltrar">&nbsp;</label>
                                            <button onclick="openBloqueLeccion()"
                                                class="btn btn-rounded btn-primary btn-block">Nueva Lección</button>
                                        </fieldset>
                                        <script>

                                        </script>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </header>
                </div>
                <div class="box-typical-body" id="lecciones_area">
                    <div class="table-responsive tableFixHead">
                        <table class="table table-hover" id="lecciones_area_data">

                            <thead>
                                <tr>
                                    <th style="width: 5%;">Lección N°</th>
                                    <th style="width: 16%;">Título</th>
                                    <th style="width: 10%;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php

                                if (isset($_GET["IDSECCION"])) {
                                    $datos = Instructor::get_lecciones_x_seccion($decifrado);
                                    $data = array();
                                    foreach ($datos as $row) {
                                        ?>
                                        <tr class="table-warning">
                                            <td>
                                                <?php echo $row["orden"] ?>
                                            </td>
                                            <td>
                                                <?php echo $row["titulo"] ?>
                                            </td>
                                            <td>
                                                <?php

                                                $cifrado = openssl_encrypt($row["leccion_id"], $cipher, $key, OPENSSL_RAW_DATA, $iv);
                                                $textoCifrado = base64_encode($iv . $cifrado);
                                                ?>
                                                <button title="Editar" onclick="mostrarLeccion(this.id)"
                                                    style="padding: 0;border: none;background: none;" type="button"
                                                    data-ciphertext="'<?php echo $textoCifrado ?>'"
                                                    id="'<?php echo $textoCifrado ?>'"
                                                    class="btn-editar-leccion abrir-leccion-form"><i
                                                        class="glyphicon glyphicon-edit"
                                                        style="color:#6aa84f; font-size:large; margin: 3px;"
                                                        aria-hidden="true"></button></i>
                                                <button title="Eliminar" style="padding: 0;border: none;background: none;"
                                                    type="button" data-ciphertext="'<?php echo $textoCifrado ?>'"
                                                    id="'<?php echo $textoCifrado ?>'" class="btn-delete-leccion"><i
                                                        class="glyphicon glyphicon-trash"
                                                        style="color:#e06666; font-size:large; margin: 3px;"
                                                        aria-hidden="true"></button></i>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }

                                ?>
                            </tbody>
                        </table>
                    </div>
                </div><!--.box-typical-body-->


                <div class="row" style="margin-top:20px;">
                    <div class="col-xxl-12 col-lg-12 col-xl-12 col-md-12" id="bloque_leccion" style="display:none">
                        <section class="box-typical proj-page">
                            <section class="proj-page-section proj-page-header">
                                <div class="title">
                                    Lección
                                </div>
                            </section><!--.proj-page-section-->

                            <!-- formulario -->
                            <div class="container-fluid">

                                <section class="box-typical steps-icon-block" id="formulario_leccion">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <form method="post" id="datos_leccion_form">
                                                <input type="hidden" id="idEncryptedLeccion" name="idEncryptedLeccion">
                                                <div id="parte_2" class="row parte_2">
                                                    <header class="steps-numeric-title">Datos de la lección</header>
                                                    <div class="row">
                                                        <div class="col-xl-6">
                                                            <div class="form-group">
                                                                <label for="titulo_leccion">Título</label>
                                                                <input type="text" class="form-control" id="titulo_leccion"
                                                                    name="titulo_leccion" placeholder="Titulo" />
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-6">
                                                            <div class="form-group">
                                                                <label for="orden_leccion">Orden</label>
                                                                <input type="text" class="form-control" id="orden_leccion"
                                                                    name="orden_leccion" placeholder="Orden" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6" onclick="">
                                                            <div class="form-group agregarMultimedia">
                                                                <label class="form-label" for="video_leccion">Video
                                                                    principal</label>
                                                                <div class="multimediaFisica needsclick dz-clickable">
                                                                    <div class="dz-message needsclick">
                                                                        Arrastrar o dar click para agregar el video
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div><!--.row-->
                                    </div><!--.container-fluid-->

                                </section>
                                <section class="proj-page-section">
                                    <label class="form-label semibold" for="descripcion">Descripción de la lección
                                    </label>
                                    <div class="summernote-theme-1">
                                        <textarea id="descripcion" name="descripcion"
                                            class="summernote descripcion"></textarea>
                                    </div>
                                    <div class="col-xxl-12 col-lg-12 col-xl-12 col-md-12" style="margin-bottom:2%">
                                        <button type="button" name="action" value="add" onclick="guardarLeccion()"
                                            class="btn btn-rounded btn-primary">Guardar</button>
                                        <button type="button" name="cancel" class="btn btn-rounded btn-secondary"
                                            onclick="close_bloque_leccion()">Cancelar</button>
                                    </div>
                                </section>

                                <script>
                                    function close_bloque_leccion() {
                                        var targetElement = document.getElementById("bloque_leccion");
                                        // Change its styles
                                        targetElement.style.display = "none";
                                    }
                                </script>
                            </div>

                        </section><!-- box-typical proj-page -->
                    </div>
                </div>
            </div><!--.container-fluid-->
        </div><!--.page-content-->
        <!-- Contenido -->
        <?php require_once ("../MainJs/js.php"); ?>
        <script src="../Certificaciones/plugins/dropzone/dropzone.js"></script>
        <script src="../Certificaciones/plugins/js/video-leccion.js"></script>
        <script type="text/javascript" src="instructores.js?v=<?php echo time(); ?>"></script>
        <?php require_once ("../html/footer.php"); ?>

    </body>

    </html>
    <?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>