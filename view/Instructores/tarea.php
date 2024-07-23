<?php
require_once ("../../config/conexion.php");
if (isset($_SESSION["usuario_id"])) {
    ?>
    <!DOCTYPE html>
    <html>
    <link rel="stylesheet" href="../Tramites/plugins/dropzone/dropzone.css" type="text/css">
    <?php require_once ("../MainHead/head.php"); ?>

    <!-- <link rel="stylesheet" href="plugins/css/dropzone.css"> -->
    <style>
        .inline {
            display: flex;
            align-items: center;
            margin-left: 100px;

        }

        .inline .form-control {
            width: auto;
            margin-left: 20px;
        }
    </style>

    <title>Tarea</title>
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
                                    Tarea
                                </div>
                            </section><!--.proj-page-section-->
                            <input type="hidden" id="tipo_solicitud" name="tipo_solicitud" value="">

                            <!-- formulario -->
                            <section class="box-typical" id="formulario_tarea" style="padding: 20px 35px;">
                                <div class="container-fluid">
                                    <?php
                                    $seccion_id = "";
                                    if (isset($_GET["IDTAREA"])) {
                                        $id_tarea = $_GET["IDTAREA"];
                                        $id_tarea = str_replace(' ', '+', $id_tarea);
                                        $key = "mi_key_secret";
                                        $cipher = "aes-256-cbc";
                                        $iv_dec = substr(base64_decode($id_tarea), 0, openssl_cipher_iv_length($cipher));
                                        $cifradoSinIV = substr(base64_decode($id_tarea), openssl_cipher_iv_length($cipher));
                                        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);
                                        require_once ("../../models/Instructor.php");
                                        $seccion_id = Instructor::get_seccion_id($decifrado);
                                        $cifrado = openssl_encrypt($seccion_id[0], $cipher, $key, OPENSSL_RAW_DATA, $iv);
                                        $seccion_id = base64_encode($iv . $cifrado);
                                    }
                                    ?>
                                    <div class="row">
                                        <form method="post" id="datos_tarea_form">
                                            <input type="hidden" id="idEncrypted" name="idEncrypted"
                                            value="<?php if(isset($id_tarea)) echo $id_tarea; ?>">
                                            <input type="hidden" id="idEncryptedSeccion" name="idEncryptedSeccion"
                                                value="<?php echo $seccion_id; ?>">
                                            <input type="hidden" id="tramite_code" name="tramite_code">
                                            <div id="parte_2" class="row parte_2">
                                                <div class="row">
                                                    <div class="col-xl-12">
                                                        <div class="form-group">
                                                            <label for="titulo" style="">Título</label>
                                                            <input type="text" class="form-control" id="titulo"
                                                                name="titulo" placeholder="Titulo" required />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xl-6">
                                                        <div class="form-group">
                                                            <label for="fecha_limite" style="">Fecha
                                                                límite de entrega</label>
                                                            <input class="form-control" type="date" id="fecha_limite"
                                                                name="fecha_limite"required>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-6">
                                                        <div class="form-group">
                                                            <label for="cantidad_intentos" style="">Cantidad de
                                                                Intentos</label>
                                                            <input type="number" class="form-control" id="cantidad_intentos"
                                                                name="cantidad_intentos" placeholder="1" required/>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="tipo_tarea" style="">Tipo de
                                                                tarea</label>
                                                            <select class="form-control " id="tipo_tarea" name="tipo_tarea"
                                                                data-placeholder="Seleccionar" onchange="mostrarFormTP()"required>
                                                                <option label="Seleccionar"></option>
                                                                <option label="Trabajo Práctico" value="1"></option>
                                                                <option label="Cuestionario" value="2"></option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                                        <div class="form-group">
                                                            <label for="total_puntos" style="">Total de Puntos</label>
                                                            <input type="number" class="form-control" id="total_puntos"
                                                                name="total_puntos" placeholder="10" value="0" required/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div><!--.row-->

                                    <div id="bloque_cuestionario" style="display:none;">
                                        <div class="row">
                                            <div class="col-xl-6 col-md-6">
                                                <div class="form-group">
                                                    <label for="tiempo_limite" style="">Duración Límite (en minutos)</label>
                                                    <input type="number" class="form-control" id="tiempo_limite"
                                                        name="tiempo_limite" placeholder="Duración límite" value="" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="bloque_trabajo_practico" style="display:none;">
                                        <div class="col-md-6" onclick="">
                                            <div class="form-group agregarMultimedia">
                                                <label class="form-label" for="adjunto_tp">Documento
                                                    relacionado al Trabajo (Guía o Intructivo)</label>
                                                <div class="multimediaFisica needsclick dz-clickable">
                                                    <div class="dz-message needsclick">
                                                        Arrastrar o dar click para subir un archivo.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!--.container-fluid-->
                            </section>

                            <section class="proj-page-section">
                                <label class="form-label semibold" for="descripcion">Descripción</label>
                                <div class="summernote-theme-1">
                                    <textarea id="descripcion" name="descripcion" class="summernote descripcion"
                                        name="name"></textarea>
                                </div>
                            </section>
                        </section><!-- box-typical proj-page -->
                    </div>

                    <div class="col-xxl-3 col-lg-12 col-xl-4 col-md-4">
                        <section class="box-typical proj-page">
                            <section class="proj-page-section">
                                <ul class="proj-page-actions-list">
                                    <li onclick="guardarTarea()"><a><i class="font-icon font-icon-check-square"></i>Guardar
                                            tarea</a></li>
                                    <li><a class="cancelar" id="cancelar" href="administrarTareas.php?IDSECCION=<?php
                                    if (isset($_GET["IDSECCION"])) {
                                        echo $_GET["IDSECCION"];
                                    } elseif (isset($seccion_id)) {
                                        echo $seccion_id;
                                    } ?>"><i class="glyphicon glyphicon-trash"></i> Cancelar</a></li>
                                </ul>
                            </section><!--.proj-page-section-->
                        </section><!--.proj-page-->
                    </div>
                </div><!--.row-->

                <div id="block_preguntas" style="display:none;">

                    <div class="row">
                        <header class="section-header" style="margin-top: -40px;margin-bottom: -30px;">
                            <div class="tbl">
                                <div class="tbl-row">
                                    <div class="tbl-cell">
                                        <h3>Ejercicios de este Cuestionario</h3>
                                    </div>
                                    <div class="tbl-cell">
                                        <div class="col-lg-6"></div>
                                        <div class="col-lg-6">
                                            <fieldset class="form-group">
                                                <label class="form-label" for="btnfiltrar">&nbsp;</label>
                                                <button onclick="openBloqueEjercicio()"
                                                    class="btn btn-rounded btn-primary btn-block">Nuevo Ejercicio</button>
                                            </fieldset>
                                            <script>

                                            </script>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </header>
                    </div>
                    <div class="box-typical-body" id="ejercicios_area">
                        <div class="table-responsive tableFixHead">
                            <table class="table table-hover" id="ejercicios_area_data">

                                <tbody>
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;">Ejercicio N°</th>
                                            <th style="width: 5%;">Tipo ejercicio</th>
                                            <th style="width: 15%;">Ejercicio</th>
                                            <th style="width: 10%;">Acciones</th>
                                        </tr>
                                    </thead>
                                    <?php
                                    require_once "../../models/Instructor.php";

                                    if (isset($_GET["IDTAREA"])) {
                                        $key = "mi_key_secret";
                                        $cipher = "aes-256-cbc";
                                        $encrypted = str_replace(' ', '+', $_GET["IDTAREA"]);
                                        $iv_dec = substr(base64_decode($encrypted), 0, openssl_cipher_iv_length($cipher));
                                        $cifradoSinIV = substr(base64_decode($encrypted), openssl_cipher_iv_length($cipher));
                                        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);
                                        $datos = Instructor::get_ejercicios_x_cuestionario($decifrado);
                                        $data = array();
                                        foreach ($datos as $row) {
                                            ?>
                                            <tr class="table-warning">
                                                <td>
                                                    <?php echo $row["numero_ejercicio"] ?>
                                                </td>
                                                <td>
                                                    <?php echo $row["tipo_ejercicio"] ?>
                                                </td>
                                                <td>
                                                    <?php echo $row["texto_ejercicio"] ?>
                                                </td>
                                                <td>
                                                    <?php

                                                    $cifrado = openssl_encrypt($row["ejercicio_id"], $cipher, $key, OPENSSL_RAW_DATA, $iv);
                                                    $textoCifrado = base64_encode($iv . $cifrado);
                                                    ?>
                                                    <button title="Editar" onclick="mostrarEjercicio(this.id)"
                                                        style="padding: 0;border: none;background: none;" type="button"
                                                        data-ciphertext="'<?php echo $textoCifrado ?>'"
                                                        id="'<?php echo $textoCifrado ?>'"
                                                        class="btn-editar-ejercicio abrir-ejercicio-form"><i
                                                            class="glyphicon glyphicon-edit"
                                                            style="color:#6aa84f; font-size:large; margin: 3px;"
                                                            aria-hidden="true"></button></i>
                                                    <button title="Eliminar" style="padding: 0;border: none;background: none;"
                                                        type="button" data-ciphertext="'<?php echo $textoCifrado ?>'"
                                                        id="'<?php echo $textoCifrado ?>'" class="btn-delete-ejercicio"><i
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
                </div>


                <div class="row" style="margin-top:20px;">
                    <div class="col-xxl-12 col-lg-12 col-xl-12 col-md-12" id="bloque_ejercicio" style="display:none">
                        <section class="box-typical proj-page">
                            <section class="proj-page-section proj-page-header">
                                <div class="title">
                                    Ejercicios
                                </div>
                            </section><!--.proj-page-section-->

                            <!-- formulario -->
                            <section class="box-typical steps-icon-block">
                                <div class="container-fluid">
                                    <div class="row">
                                        <form method="post" id="datos_ejercicio_form">
                                            <input type="hidden" id="idEncryptedEjercicio" name="idEncryptedEjercicio">
                                            <div id="parte_2" class="row parte_2">
                                                <div class="row">
                                                    <header class="steps-numeric-title">Datos del ejercicio</header>
                                                    <div class="col-xl-4">
                                                        <div class="form-group">
                                                            <div class="form-group">
                                                                <label class="form-label" for="tipo_ejercicio" style="">Tipo
                                                                    de
                                                                    ejercicio</label>
                                                                <select class="form-control " id="tipo_ejercicio"
                                                                    name="tipo_ejercicio" data-placeholder="Seleccionar"
                                                                    onchange="mostrarFormEjercicio()">
                                                                    <option label="Seleccionar"></option>
                                                                    <option label="Selección Múltiple"
                                                                        value="seleccion_multiple"></option>
                                                                    <option label="Selección Simple"
                                                                        value="seleccion_simple"></option>
                                                                    <option label="Verdadero o Falso"
                                                                        value="verdadero_falso"></option>
                                                                    <option label="Completa" value="completar"></option>
                                                                    <option label="Respuesta Corta" value="respuesta_corta">
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4">
                                                        <div class="form-group">
                                                            <label for="numero_ejercicio">Número</label>
                                                            <input type="number" class="form-control" id="numero_ejercicio"
                                                                name="numero_ejercicio" placeholder="Orden" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4">
                                                        <div class="form-group">
                                                            <label for="puntaje">Puntaje</label>
                                                            <input type="number" class="form-control" id="puntaje"
                                                                name="puntaje" placeholder="1" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xl-6">
                                                        <div class="form-group">
                                                            <label for="texto_ejercicio">Ejercicio</label>
                                                            <textarea rows="4" class="form-control"
                                                                placeholder="Escriba la pregunta o planteamiento"
                                                                id="texto_ejercicio" name="texto_ejercicio"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group agregarMultimedia">
                                                            <label class="form-label" for="imagen_ejercicio">Imagen
                                                                (opcional)</label>
                                                            <div class="multimediaFisica needsclick dz-clickable"
                                                                id="imagen_ejercicio">
                                                                <div class="dz-message needsclick">
                                                                    Arrastrar o dar click para agregar la imagen
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                        </form>
                                        <div id="multiple_choice" class="row tipo-ejercicio" style="display:none;">
                                            <div class="col-md-6">
                                                <div class="form-section">
                                                    <fieldset id="fieldset_mc">
                                                        <div class="inline">
                                                            <input type="checkbox" name="multiple1">
                                                            <input type="text" class="form-control" name="multiple1"
                                                                placeholder="Opción 1">
                                                        </div>
                                                        <br>
                                                        <div class="inline">
                                                            <input type="checkbox" name="multiple2">
                                                            <input type="text" class="form-control" name="multiple2"
                                                                placeholder="Opción 2">
                                                        </div>
                                                        <br>
                                                        <div class="inline">
                                                            <input type="checkbox" name="multiple3">
                                                            <input type="text" class="form-control" name="multiple3"
                                                                placeholder="Opción 3">
                                                        </div>
                                                        <br>
                                                        <div class="inline">
                                                            <input type="checkbox" name="multiple4">
                                                            <input type="text" class="form-control" name="multiple4"
                                                                placeholder="Opción 4">
                                                        </div>
                                                        <br>
                                                    </fieldset>
                                                </div>
                                            </div>
                                            <div class="col-md-2 form-group">
                                                <button type="button" class="btn btn-inline btn-primary-outline"
                                                    id="addOption">Agregar opción</button>
                                                <button type="button" class="btn btn-inline btn-danger-outline"
                                                    id="removeOption">Eliminar opción</button>
                                            </div>
                                        </div>

                                        <div id="simple_choice" class="row form-section tipo-ejercicio"
                                            style="display:none;">
                                            <div class="col-md-6">
                                                <div class="form-section">
                                                    <fieldset id="fieldset_sc">
                                                        <div class="inline">
                                                            <input type="radio" name="optionsRadios" id="radio1">
                                                            <input type="text" class="form-control" name="radio1"
                                                                placeholder="Opción 1">
                                                        </div>
                                                        <br>
                                                        <div class="inline">
                                                            <input type="radio" name="optionsRadios" id="radio2">
                                                            <input type="text" class="form-control" name="radio2"
                                                                placeholder="Opción 2">
                                                        </div>
                                                        <br>
                                                        <div class="inline">
                                                            <input type="radio" name="optionsRadios" id="radio3">
                                                            <input type="text" class="form-control" name="radio3"
                                                                placeholder="Opción 3">
                                                        </div>
                                                        <br>
                                                        <div class="inline">
                                                            <input type="radio" name="optionsRadios" id="radio4">
                                                            <input type="text" class="form-control" name="radio4"
                                                                placeholder="Opción 4">
                                                        </div>
                                                        <br>
                                                    </fieldset>
                                                </div>
                                            </div>
                                            <div class="col-md-2 form-group">
                                                <button type="button" class="btn btn-inline btn-primary-outline"
                                                    id="addRadio">Agregar opción</button>
                                                <button type="button" class="btn btn-inline btn-danger-outline"
                                                    id="removeRadio">Eliminar opción</button>
                                            </div>
                                        </div>

                                        <div id="true_false" class="row form-section tipo-ejercicio" style="display:none;">
                                            <div class="col-md-6">
                                                <div class="form-section">
                                                    <fieldset id="fieldset_tf">
                                                        <div class="inline">
                                                            <input type="radio" name="trueFalseOption" id="true">
                                                            <label class="form-label" for="true"> Verdadero</label>
                                                        </div>
                                                        <br>
                                                        <div class="inline">
                                                            <input type="radio" name="trueFalseOption" id="false">
                                                            <label class="form-label" for="false"> Falso</label>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="short_answer" class="form-section tipo-ejercicio" style="display:none;">
                                            <input type="text" class="form-control" name="short_answer"
                                                placeholder="respuesta corta">
                                        </div>

                                        <div id="complete" class="form-section tipo-ejercicio" style="display:none;">
                                            <input type="text" class="form-control" name="complete" placeholder="completa">

                                        </div>
                                    </div>


                                    <div class="col-xxl-12 col-lg-12 col-xl-12 col-md-12">
                                        <button type="button" name="action" value="add" onclick="guardarEjercicio()"
                                            class="btn btn-rounded btn-primary">Guardar</button>
                                        <button type="button" name="cancel" class="btn btn-rounded btn-secondary"
                                            onclick="close_bloque_ejercicio()">Cancelar</button>
                                    </div>
                                    <script>
                                        function close_bloque_ejercicio() {
                                            var targetElement = document.getElementById("bloque_ejercicio");
                                            // Change its styles
                                            targetElement.style.display = "none";
                                        }
                                    </script>
                                </div><!--.row-->
                    </div>

                    </section>
                    </section><!-- box-typical proj-page -->
                </div>
            </div>
        </div><!--.container-fluid-->
        </div><!--.page-content-->
        <!-- Contenido -->
        <?php require_once ("../MainJs/js.php"); ?>
        <script src="../Tramites/plugins/dropzone/dropzone.js"></script>
        <script type="text/javascript" src="instructores.js?v=<?php echo time(); ?>"></script>
        <?php require_once ("../html/footer.php"); ?>
        <script>
            function mostrarFormTP() {
                var selectElement = document.getElementById("tipo_tarea");
                // Get the selected option's value
                var selectedValue = selectElement.value;
                var bloque_trabajo_practico = document.getElementById("bloque_trabajo_practico");
                var block_preguntas = document.getElementById("block_preguntas");
                var bloque_cuestionario = document.getElementById("bloque_cuestionario");
                if (selectedValue == 2) {
                    bloque_trabajo_practico.style.display = "none";
                    bloque_cuestionario.style.display = "block";
                    if ($("#idEncrypted").val() != '') {
                        block_preguntas.style.display = "block";
                    }
                    document.getElementById("total_puntos").disabled = true;
                    document.getElementById("total_puntos").placeholder = "0";
                    document.getElementById("total_puntos").value = 0;
                    document.getElementById("total_puntos").title = "Se acumularán los puntos de cada ejercicio del cuestionario.";
                }
                else if (selectedValue == 1) {
                    bloque_trabajo_practico.style.display = "block";
                    block_preguntas.style.display = "none";
                    bloque_cuestionario.style.display = "none";
                    document.getElementById("total_puntos").disabled = false;
                    document.getElementById("total_puntos").placeholder = "10";
                }

            }

            function mostrarFormEjercicio() {
                var selectElement = document.getElementById("tipo_ejercicio");
                var selectedValue = selectElement.value;
                var sections = ["multiple_choice", "simple_choice", "true_false", "short_answer", "complete"];

                sections.forEach(function (section) {
                    document.getElementById(section).style.display = "none";
                });

                if (selectedValue === "seleccion_multiple") {
                    document.getElementById("multiple_choice").style.display = "block";
                } else if (selectedValue === "seleccion_simple") {
                    document.getElementById("simple_choice").style.display = "block";
                } else if (selectedValue === "verdadero_falso") {
                    document.getElementById("true_false").style.display = "block";
                } else if (selectedValue === "completar") {
                    document.getElementById("complete").style.display = "block";
                } else if (selectedValue === "respuesta_corta") {
                    document.getElementById("short_answer").style.display = "block";
                }
            }
        </script>
    </body>

    </html>
    <?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>