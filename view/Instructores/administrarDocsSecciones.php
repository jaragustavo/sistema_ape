<?php
require_once ("../../config/conexion.php");
if (isset($_SESSION["usuario_id"])) {
    ?>
    <!DOCTYPE html>
    <html>
    <link rel="stylesheet" href="../Tramites/plugins/dropzone/dropzone.css" type="text/css">
    <?php require_once ("../MainHead/head.php"); ?>


    <title>Carga de materiales</title>
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
                                    Materiales de la sección
                                </div>
                            </section><!--.proj-page-section-->

                            <section class="proj-page-section">

                                <div class="proj-page-txt">
                                    <header class="proj-page-subtitle">
                                        <h3 class="tramite_nombre"></h3>
                                    </header>
                                    <p>En el campo a continuación podrá cargar todos los materiales de la sección.<br>
                                        Sólo se permiten archivos con formato PDF, docx (Word) y txt.<br>
                                        En la siguiente sección, se listan los archivos ya agregados. Los puedes ver o
                                        eliminar.
                                    </p>
                                </div>
                            </section><!--.proj-page-section-->
                            <form method="post" id="datos_solicitud_form">
                                <section class="proj-page-section">
                                    <div class="form-group agregarMultimedia">
                                        <div class="multimediaFisica needsclick dz-clickable" id="materialesLecciones">
                                            <div class="dz-message needsclick">
                                                Arrastrar o dar click para subir los materiales.
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </form>

                            <section class="proj-page-section">
                                <header class="proj-page-subtitle with-del">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h3>Materiales cargados</h3>
                                        </div>
                                    </div>

                                </header>
                                <div class="form-group" id="materiales_seccion">
                                    <input type="hidden" id="tramite_id">
                                    <!-- <div class="form-group"> -->
                                    <div class="row" style="margin-left:25px;">

                                        <?php
                                        require "../../models/Instructor.php";

                                        $id = rawurldecode($_GET['IDSECCION']);
                                        $id = str_replace(' ', '+', $id);
                                        $key = "mi_key_secret";
                                        $cipher = "aes-256-cbc";
                                        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
                                        $iv_dec = substr(base64_decode($id), 0, openssl_cipher_iv_length($cipher));
                                        $cifradoSinIV = substr(base64_decode($id), openssl_cipher_iv_length($cipher));
                                        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);
                                        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);
                                        $documentos = Instructor::get_materiales_x_seccion($decifrado);

                                        $curso_id = Instructor::get_curso_id($decifrado);
                                        $cifrado = openssl_encrypt($curso_id[0], $cipher, $key, OPENSSL_RAW_DATA, $iv);
                                        $curso_id = base64_encode($iv . $cifrado);
                                        foreach ($documentos as $key => $value) {
                                            ?>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="proj-page-attach">
                                                        <i class="font-icon font-icon-doc"></i>
                                                        <p class="name">
                                                            <?php echo basename($value["archivo"]) ?>
                                                        </p>
                                                        <p class="date">
                                                            <?php echo $value["hora_formato_doc"] . ", " . $value["fecha_formato_doc"] ?>
                                                        </p>
                                                        <p>
                                                            <a href="<?php echo '../' . $value["archivo"] ?>"
                                                                target="_blank">Ver</a>
                                                            <!--<a href="
                                                            <?php
                                                            // echo '../' . $value["archivo"] 
                                                            ?>
                                                            ">Descargar</a>-->
                                                            <a style="color:#0082c6;"
                                                                onclick="eliminarMaterialSeccion(<?php echo $value['material_id'] ?>, '<?php echo $value['archivo'] ?>')">Eliminar</a>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </section><!--.proj-page-section-->
                        </section><!-- box-typical proj-page -->
                    </div>

                    <div class="col-xxl-3 col-lg-12 col-xl-4 col-md-4">
                        <section class="box-typical proj-page">
                            <input type="hidden" id="idEncrypted" name="idEncrypted">
                            <input type="hidden" id="idEncryptedCurso" name="idEncryptedCurso"
                                value="<?php echo $curso_id ?>">
                            <input type="hidden" id="curso_id" name="curso_id">
                            <input type="hidden" id="tipo_solicitud" name="tipo_solicitud" value="">
                            <section class="proj-page-section">
                                <ul class="proj-page-actions-list">
                                    <li onclick="guardarMateriales()"><a><i
                                                class="font-icon font-icon-check-square"></i>Guardar archivos</a></li>
                                    <li><a class="cancelar" id="cancelarMateriales"><i
                                                class="glyphicon glyphicon-trash"></i> Cancelar</a></li>
                                </ul>
                            </section><!--.proj-page-section-->

                        </section><!--.proj-page-->
                    </div>
                </div><!--.row-->
            </div><!--.container-fluid-->
        </div><!--.page-content-->

        <?php require_once ("../MainJs/js.php"); ?>
        <script type="text/javascript" src="instructores.js?v=<?php echo time(); ?>"></script>
        <?php require_once ("../html/footer.php"); ?>
        <script src="../Tramites/plugins/dropzone/dropzone.js"></script>

    </body>

    </html>
    <?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>
<script>
    cancelarReturnPage();
    /*=============================================
 AGREGAR MULTIMEDIA CON DROPZONE
 =============================================*/
    $("#materialesLecciones").dropzone({
        url: "../Tramites/plugins/dropzone/dropzone.js",
        addRemoveLinks: true,
        acceptedFiles: ".docx, .txt, .pdf, application/pdf",
        maxFilesize: 10,
        maxFiles: 15,
        init: function () {
            this.on("addedfile", function (file) {
                arrayFiles.push(file);
            })

            this.on("removedfile", function (file) {
                var index = arrayFiles.indexOf(file);
                arrayFiles.splice(index, 1);
            })
        }
    });
</script>