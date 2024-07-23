<?php
require_once ("../../config/conexion.php");
if (isset ($_SESSION["usuario_id"])) {
    ?>
    <!DOCTYPE html>
    <html>
    <?php require_once ("../MainHead/head.php"); ?>
    <link rel="stylesheet" href="../Certificaciones/plugins/dropzone/dropzone.css" type="text/css">

    <!-- <link rel="stylesheet" href="plugins/css/dropzone.css"> -->

    <title>Nuevo curso</title>
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
                                    Nuevo curso
                                    <i class="font-icon font-icon-pencil"></i>
                                </div>
                            </section><!--.proj-page-section-->

                            <section class="proj-page-section">

                                <div class="proj-page-txt">
                                    <header class="proj-page-subtitle">
                                        <h3 class="tramite_nombre"></h3>
                                    </header>
                                    <p>Asegúrese de completar correctamente el formulario a que se encuentra a
                                        continuación.<br>
                                    </p>
                                </div>
                            </section><!--.proj-page-section-->

                            <!-- formulario -->
                            <section class="box-typical steps-icon-block" id="formulario">
                                <div class="container-fluid">
                                    <div class="row">
                                        <form method="post" id="datos_curso_form">
                                            <input type="hidden" id="idEncrypted" name="idEncrypted">
                                            <input type="hidden" id="tramite_code" name="tramite_code">
                                            <input type="hidden" id="tipo_solicitud" name="tipo_solicitud" value="">
                                            <div id="parte_2" class="row parte_2">
                                                <header class="steps-numeric-title">Datos del curso</header>
                                                <div class="row">
                                                    <div class="col-xl-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="tipo_tramite"
                                                                style="text-align:left;margin:5px;margin-left:30px;">Curso o certificación</label>
                                                            <select class="form-control " id="tipo_tramite"
                                                                name="tipo_tramite" data-placeholder="Seleccionar">
                                                                <option label="Seleccionar"></option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group agregarMultimedia">
                                                            <label class="form-label" for="imagen_portada"
                                                                style="text-align:left;margin:5px;margin-left:30px;">Imagen
                                                                de portada del curso</label>
                                                            <div class="multimediaFisica needsclick dz-clickable">
                                                                <div class="dz-message needsclick">
                                                                    Arrastrar o dar click para subir imagenes.
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xl-6">
                                                        <div class="form-group">
                                                            <label for="nombre_curso"
                                                                style="text-align:left;margin:5px;margin-left:30px;">Nombre
                                                                del curso</label>
                                                            <input type="text" class="form-control" id="nombre_curso"
                                                                name="nombre_curso" placeholder="Nombre" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="categoria_curso"
                                                                style="text-align:left;margin:5px;margin-left:30px;">Categoría</label>
                                                            <select class="form-control " id="categoria_curso"
                                                                name="categoria_curso" data-placeholder="Seleccionar">
                                                                <option label="Seleccionar"></option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">

                                                </div>

                                            </div>
                                        </form>
                                    </div><!--.row-->
                                </div><!--.container-fluid-->
                            </section>
                            <section class="proj-page-section">
                                <label class="form-label semibold" for="descripcion">Descripción del curso</label>
                                <div class="summernote-theme-1">
                                    <textarea id="descripcion" name="descripcion" class="summernote descripcion" name="name"></textarea>
                                </div>
                            </section>
                            <section class="proj-page-section">
                                <label class="form-label semibold" for="aprendizaje">¿Qué aprenderán?</label>
                                <div class="summernote-theme-1">
                                    <textarea id="aprendizaje" name="aprendizaje" class="summernote" name="name"></textarea>
                                </div>
                            </section>

                        </section><!-- box-typical proj-page -->
                    </div>

                    <div class="col-xxl-3 col-lg-12 col-xl-4 col-md-4">
                        <section class="box-typical proj-page">
                            <section class="proj-page-section proj-page-time-info">
                                <div class="tbl">
                                    <div class="tbl-row">
                                        <div class="tbl-cell">Duración del curso:
                                        </div>
                                        <div class="tbl-cell tbl-cell-time">4 semanas
                                        </div>
                                    </div>
                                </div>
                            </section><!--.proj-page-section-->

                            <section class="proj-page-section">
                                <ul class="proj-page-actions-list">
                                    <li onclick="guardarCurso()"><a><i class="font-icon font-icon-check-square"></i>Crear
                                            curso</a></li>
                                    <li><a class="cancelar" href="cursosInstructores.php"><i
                                                class="glyphicon glyphicon-trash"></i> Cancelar</a></li>
                                </ul>
                            </section><!--.proj-page-section-->
                        </section><!--.proj-page-->
                    </div>
                </div><!--.row-->
            </div><!--.container-fluid-->
        </div><!--.page-content-->
        <!-- Contenido -->

        <?php require_once ("../MainJs/js.php"); ?>
        <script src="../Tramites/plugins/dropzone/dropzone.js"></script>
        <script src="../Certificaciones/plugins/js/portada.js"></script>
        <script type="text/javascript" src="instructores.js?v=<?php echo time(); ?>"></script>
        <?php require_once ("../html/footer.php"); ?>

    </body>

    </html>
    <?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>