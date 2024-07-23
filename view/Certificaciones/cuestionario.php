<?php
require_once ("../../config/conexion.php");
if (isset($_SESSION["usuario_id"])) {

    ?>
    <!doctype html>
    <html class="no-js" lang="en">
    <?php require_once ("html/head.php"); ?>
    <title>APE </title>
    <link rel="stylesheet" href="plugins/dropzone/dropzone.css" type="text/css">
    <style>
        #timer {
            font-size: 3rem;
        }
    </style>
    </head>

    <body>
        <?php
        if (isset($_GET["IDTAREA"])) {
            $id_tarea = $_GET["IDTAREA"];
            $id_tarea = str_replace(' ', '+', $id_tarea);
            $key = "mi_key_secret";
            $cipher = "aes-256-cbc";
            $iv_dec = substr(base64_decode($id_tarea), 0, openssl_cipher_iv_length($cipher));
            $cifradoSinIV = substr(base64_decode($id_tarea), openssl_cipher_iv_length($cipher));
            $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);
            require ("../../models/Certificacion.php");
            $info_tarea = Certificacion::get_info_tarea($decifrado, $_SESSION['usuario_id']);
        }
        ?>
        <!--Preloader-->
        <div id="preloader">
            <div id="loader" class="loader">
                <div class="loader-container">
                    <div class="loader-icon"><img src="assets/img/logo/preloader.svg" alt="Preloader"></div>
                </div>
            </div>
        </div>
        <!--Preloader-end -->

        <!-- Scroll-top -->
        <button class="scroll__top scroll-to-target" data-target="html">
            <i class="tg-flaticon-arrowhead-up"></i>
        </button>
        <!-- Scroll-top-end-->

        <!-- header-area -->
        <?php require_once ("html/header.php"); ?>
        <!-- header-area-end -->

        <!-- main-area -->
        <main class="main-area fix">

            <!-- singUp-area -->
            <section class="singUp-area section-py-120">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-xl-10 col-lg-10">
                            <div class="contact-form-wrap" id="inicio_cuestionario">
                                <input type="hidden" id="tarea_id" value="<?php echo $decifrado; ?>">
                                <input type="hidden" id="tareaEncrypted" value="<?php echo $id_tarea; ?>">
                                <input type="hidden" id="seccion_id" value="<?php echo $info_tarea[0]['seccion_id']; ?>">
                                <input type="hidden" id="curso_id" value="<?php echo $info_tarea[0]['curso_id']; ?>">
                                <h2 class="title" id="titulo"><?php echo $info_tarea[0]['titulo_tarea']; ?></h2>
                                <div class="courses__cost-wrap" style="background-color:#351c75 !important;">
                                    <div class="row">
                                        <div class="col-md-4 col-xl-4">
                                            <span>Fecha límite de entrega</span>
                                            <h6 class="title"><?php echo $info_tarea[0]['fecha_limite']; ?></h6>
                                        </div>
                                        <div class="col-md-4 col-xl-4">
                                            <span>Intentos Permitidos</span>
                                            <input type="hidden" id="intentos_permitidos" value="<?php echo $info_tarea[0]['cantidad_intentos']; ?>">

                                            <h6 class="title" id="intentos_permitidos_texto">
                                                <?php echo $info_tarea[0]['cantidad_intentos']; ?></h6>
                                        </div>
                                        <div class="col-md-4 col-xl-4">
                                            <span>Intentos Realizados</span>
                                            <input type="hidden" id="intentos_realizados_c" value="<?php echo $info_tarea[0]['intentos_realizados_c']; ?>">

                                            <h6 class="title" id="intentos_realizados_c_texto"><?php if ($info_tarea[0]['intentos_realizados_c'] == null) {
                                                $info_tarea[0]['intentos_realizados_c'] = 0;
                                            }
                                            echo $info_tarea[0]['intentos_realizados_c']; ?>
                                            </h6>
                                        </div>
                                    </div>
                                </div>

                                <?php if ($info_tarea[0]['intentos_realizados_c'] >= 1) { ?>
                                    <div class="instructor__details-Skill">
                                        <h4 class="title">Resultado</h4>
                                        <div class="instructor__progress-wrap">
                                            <div class="progress-item">
                                                <?php if ($info_tarea[0]['puntos_logrados_c'] >= 0) { ?>
                                                    <h6 class="title">Porcentaje logrado
                                                        <span><?php
                                                        $porcentaje = round($info_tarea[0]['puntos_logrados_c'] / $info_tarea[0]['total_puntos'] * 100);
                                                        echo $porcentaje . '%' . '  (' . $info_tarea[0]['puntos_logrados_c'] . ' de ' . $info_tarea[0]['total_puntos'] . ')'; ?>
                                                        </span></h6>
                                                    <div class="progress" role="progressbar" aria-label="Example with label"
                                                        aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                                        <div class="progress-bar" style="width: <?php echo $porcentaje; ?>%"></div>
                                                    </div>
                                                <?php } else { ?>
                                                    <h6 class="title"><?php echo 'No corregido'; ?> <span></span></h6>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                                <p id="descripcion"><?php echo $info_tarea[0]['descripcion']; ?></p>
                                <div class="account__form">
                                    <button type="button" class="btn btn-two arrow-btn" onclick="comenzarCuestionario()"
                                        style="width:auto;">Comenzar<img
                                            src="../../public/skillgro/img/icons/right_arrow.svg" alt="img"
                                            class="injectable"></button>
                                </div>
                            </div>

                            <form class="account__form" id="cuestionario_form">
                                <div id="countdown">
                                    <input type="hidden" id="tiempo_limite"
                                        value="<?php echo $info_tarea[0]['tiempo_limite']; ?>">
                                </div>

                                <div class="contact-form-wrap" id="seccion_preguntas" style="display:none;">
                                    <div class="">
                                        <ul class="list-wrap">
                                            <?php
                                            $ejercicios = Certificacion::get_ejercicios_x_tarea($decifrado);
                                            foreach ($ejercicios as $ejercicio) {
                                                ?>
                                                <li>
                                                    <div class="comments-box">
                                                        <div class="comments-text">
                                                            <div class="avatar-name">
                                                                <input class="ejercicio" type="hidden"
                                                                    id="<?php echo $ejercicio['id']; ?>">
                                                                <h6 class="name">Ejercicio
                                                                    <?php echo $ejercicio['numero_ejercicio']; ?></h6>
                                                            </div>
                                                            <p><?php echo $ejercicio['texto_ejercicio']; ?></p>
                                                            <?php
                                                            $opciones = Certificacion::get_opciones_ejercicio($ejercicio['id']);
                                                            if ($opciones) {
                                                                foreach ($opciones as $opcion) {
                                                                    switch ($ejercicio['tipo_ejercicio']) {
                                                                        case "seleccion_multiple":
                                                                            ?>
                                                                            <div class="courses-cat-list">
                                                                                <ul class="list-wrap">
                                                                                    <li>
                                                                                        <div class="form-check">
                                                                                            <input class="form-check-input respuesta"
                                                                                                type="checkbox"
                                                                                                id="op<?php echo $opcion['opcion_id']; ?>">
                                                                                            <label
                                                                                                class="form-check-label"><?php echo $opcion['opcion_texto']; ?></label>
                                                                                        </div>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                            <?php break;
                                                                        case "seleccion_simple":
                                                                            ?>
                                                                            <div class="courses-cat-list">
                                                                                <ul class="list-wrap">
                                                                                    <li>
                                                                                        <div class="form-check">
                                                                                            <input class="form-check-input respuesta"
                                                                                                type="radio"
                                                                                                id="op<?php echo $opcion['opcion_id']; ?>"
                                                                                                name="ejer_<?php echo $ejercicio['id']; ?>">
                                                                                            <label class="form-check-label"
                                                                                                for=""><?php echo $opcion['opcion_texto']; ?></label>
                                                                                        </div>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                            <?php break;
                                                                    }
                                                                }
                                                            } else {
                                                                switch ($ejercicio['tipo_ejercicio']) {
                                                                    case "verdadero_falso":
                                                                        ?>
                                                                        <div class="courses-cat-list">
                                                                            <ul class="list-wrap">
                                                                                <li>
                                                                                    <div class="">
                                                                                        <input class="form-check-input" type="radio"
                                                                                            name="v_f"
                                                                                            id="v_f<?php echo $ejercicio['id']; ?>"
                                                                                            true_false="true">
                                                                                        <label class="form-check-label"
                                                                                            for="v_f">Verdadero</label>
                                                                                    </div>
                                                                                </li>
                                                                                <li>
                                                                                    <div class="">
                                                                                        <input class="form-check-input" type="radio"
                                                                                            name="v_f"
                                                                                            id="v_f<?php echo $ejercicio['id']; ?>"
                                                                                            true_false="false">
                                                                                        <label class="form-check-label"
                                                                                            for="v_f">Falso</label>
                                                                                    </div>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                        <?php break;
                                                                    case "respuesta_corta":
                                                                        ?>
                                                                        <input id="<?php echo $ejercicio['id']; ?>" type="text"
                                                                            placeholder="respuesta">
                                                                        <?php break;
                                                                    case "completar":
                                                                        ?>
                                                                        <input id="<?php echo $ejercicio['id']; ?>" type="text"
                                                                            placeholder="completa">
                                                                        <?php break;
                                                                }
                                                            }
                                                            ?>
                                                            <div class="comments-avatar">
                                                                <?php if ($ejercicio['imagen_url'] != "" || $ejercicio['imagen_url'] != null) { ?>
                                                                    <img src="../<?php echo $ejercicio['imagen_url']; ?>" alt="img">
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <br>
                                                <?php
                                            }
                                            ?>
                                            <li>
                                                <button type="button" class="btn btn-two arrow-btn"
                                                    onclick="entregarCuestionario()" style="width:auto;">Entregar
                                                    Cuestionario<img src="../../public/skillgro/img/icons/right_arrow.svg"
                                                        alt="img" class="injectable"></button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </form>
                        </div> <!-- Missing closing div -->

                    </div>
                </div>
            </section>
            <!-- singUp-area-end -->

        </main>
        <!-- main-area-end -->

        <!-- footer-area -->
        <?php require_once "html/footer.php"; ?>
        <!-- footer-area-end -->

        <!-- JS here -->
        <?php require_once "html/js.php"; ?>
        <script src="plugins/dropzone/dropzone.js"></script>
        <script type="text/javascript" src="certificaciones.js?v=<?php echo time(); ?>"></script>
    </body>
    <script>
        var arrayFiles = [];
        var arrayId = [];
        /*=============================================
        AGREGAR MULTIMEDIA CON DROPZONE
        =============================================*/
        $(".multimediaFisica").dropzone({
            url: "plugins/dropzone/dropzone.js",
            addRemoveLinks: true,
            acceptedFiles: ".docx, .txt, .pdf, application/pdf",
            maxFilesize: 10,
            maxFiles: 1,
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

    </html>
    <?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>