<?php
require_once ("../../config/conexion.php");
if (isset($_SESSION["usuario_id"])) {

    ?>
    <!doctype html>
    <html class="no-js" lang="en">
    <?php require_once ("html/head.php"); ?>
    <title>APE </title>
    <link rel="stylesheet" href="plugins/dropzone/dropzone.css" type="text/css">
    </head>

    <body>
        <?php
        require ("../../models/Certificacion.php");
        $key = "mi_key_secret";
        $cipher = "aes-256-cbc";
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
        if (isset($_GET["IDTAREA"])) {
            $id_tarea = $_GET["IDTAREA"];
            $id_tarea = str_replace(' ', '+', $id_tarea);
            $iv_dec = substr(base64_decode($id_tarea), 0, openssl_cipher_iv_length($cipher));
            $cifradoSinIV = substr(base64_decode($id_tarea), openssl_cipher_iv_length($cipher));
            $id_tarea = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);
            $info_tarea = Certificacion::get_info_tarea($id_tarea[0], $_SESSION['usuario_id']);

        } elseif (isset($_GET["IDENTREGA"])) {
            $id_entrega = $_GET["IDENTREGA"];
            $id_entrega = str_replace(' ', '+', $id_entrega);
            $iv_dec = substr(base64_decode($id_entrega), 0, openssl_cipher_iv_length($cipher));
            $cifradoSinIV = substr(base64_decode($id_entrega), openssl_cipher_iv_length($cipher));
            $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);
            $tarea = Certificacion::get_tarea_id($decifrado);
            $cifrado = openssl_encrypt($tarea['tarea_id'], $cipher, $key, OPENSSL_RAW_DATA, $iv);
            $id_tarea = base64_encode($iv . $cifrado);

            $info_tarea = Certificacion::get_info_tarea($tarea['tarea_id'], $tarea['alumno']);
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
                            <div class="contact-form-wrap">
                                <input type="hidden" id="tarea_id" value="<?php echo $id_tarea; ?>">
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
                                            <h6 class="title"><?php echo $info_tarea[0]['cantidad_intentos']; ?></h6>
                                        </div>
                                        <div class="col-md-4 col-xl-4">
                                            <span>Intentos Realizados</span>
                                            <h6 class="title"><?php if ($info_tarea[0]['intentos_realizados_tp'] == null) {
                                                $info_tarea[0]['intentos_realizados_tp'] = 0;
                                            }
                                            echo $info_tarea[0]['intentos_realizados_tp']; ?></h6>
                                        </div>
                                    </div>

                                </div>
                                <?php if (isset($_GET["IDENTREGA"]) == false){
                                ?>
                                <div class="instructor__details-Skill">
                                    <h4 class="title">Resultado</h4>
                                    <div class="instructor__progress-wrap">
                                        
                                        <div class="progress-item">
                                            <?php
                                            if($info_tarea[0]['puntos_logrados_tp'] >= 0){
                                            ?>
                                            <h6 class="title">Porcentaje logrado <span><?php 
                                            
                                                $porcentaje = round($info_tarea[0]['puntos_logrados_tp'] / $info_tarea[0]['total_puntos']*100);
                                                echo $porcentaje . '%' . '  (' . $info_tarea[0]['puntos_logrados_tp'] . ' de '. $info_tarea[0]['total_puntos'] . ')'; ?>
                                            </span></h6>
                                            <div class="progress" role="progressbar" aria-label="Example with label" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                                <div class="progress-bar" style="width: <?php echo $porcentaje; ?>%"></div>
                                            </div>
                                            <?php    
                                            }
                                            else{ ?>
                                            
                                                <h6 class="title"><?php echo 'No corregido'; ?> <span>
                                                <?php    
                                            }
                                            ?>
                                        </div>
                                </div>
                                <?php
                                } ?>
                            </div>
                                <p id="descripcion">
                                    <?php if (isset($_GET["IDENTREGA"]) == false)
                                        echo $info_tarea[0]['descripcion']; ?>
                                </p>
                                <?php
                                if ($info_tarea[0]['archivo_url'] != '' || $info_tarea[0]['archivo_url'] != null) {
                                    ?>
                                    <div class="rc-post-item" style="margin-top:2%;">
                                        <div class="rc-post-thumb">
                                            <a href="blog-details.html">
                                                <img src="../../public/skillgro/img/blog/multimedia.png" alt="img"
                                                    style="max-width:80%;">
                                            </a>
                                        </div>
                                        <div class="rc-post-content">
                                            <span class="date"><i class="flaticon-calendar"></i>
                                                <?php echo $info_tarea[0]["hora_entrega"] . ", " . $info_tarea[0]["fecha_entrega"]; ?></span>
                                            <h6><a href="../<?php echo $info_tarea[0]["archivo_url"]; ?>">
                                                    <?php echo basename($info_tarea[0]["archivo_url"]); ?></a>
                                            </h6>
                                        </div>
                                    </div>

                                    <?php
                                }
                                ?>
                                <form class="account__form" id="trabajo_practico_form">
                                    <p style="font-weight:bold;" id="p_redactar">Redactar</p>
                                    <div class="form-grp">
                                        <textarea name="trabajo_texto" id="trabajo_texto" placeholder=""><?php if ($info_tarea[0]['trabajo_texto'] != null) {
                                            echo $info_tarea[0]['trabajo_texto'];
                                        } ?>
                                                                </textarea>
                                    </div>
                                    <div class="form-group agregarMultimedia" id="div_entrega_doc">
                                        <p style="font-weight:bold;">Adjuntar</p>

                                        <div class="multimediaFisica needsclick dz-clickable" id="adjunto">
                                            <div class="dz-message needsclick">
                                                Arrastrar o dar click para subir el documento.
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <p style="font-weight:bold;font-size:20px; padding-top:2%; color:#c90076;">Documentos
                                    adjuntos:</p>
                                <?php
                                if ($info_tarea[0]['adjunto'] != '' || $info_tarea[0]['adjunto'] != null) {
                                    ?>
                                    <div class="rc-post-item" style="margin-top:2%;">
                                        <div class="rc-post-thumb">
                                            <a href="blog-details.html">
                                                <img src="../../public/skillgro/img/blog/multimedia.png" alt="img"
                                                    style="max-width:80%;">
                                            </a>
                                        </div>
                                        <div class="rc-post-content">
                                            <span class="date"><i class="flaticon-calendar"></i>
                                                <?php echo $info_tarea[0]["hora_entrega"] . ", " . $info_tarea[0]["fecha_entrega"]; ?></span>
                                            <h6><a href="../<?php echo $info_tarea[0]["adjunto"]; ?>">
                                                    <?php echo basename($info_tarea[0]["adjunto"]); ?></a>
                                            </h6>
                                        </div>
                                    </div>

                                    <?php
                                }
                                if ($info_tarea[0]['intentos_realizados_tp'] < $info_tarea[0]['cantidad_intentos']) {
                                    ?>
                                    <button type="button" class="btn btn-two arrow-btn" onclick="entregarTP()"
                                        style="width:auto;">Entregar<img src="../../public/skillgro/img/icons/right_arrow.svg"
                                            alt="img" class="injectable"></button>
                                    <?php
                                } else {
                                    ?>
                                    <p style="font-weight:bold; color:#c90076; margin-top: 2%;" id="sin_modif">
                                        Ya no puede realizar modificaciones a su entrega.</p>
                                    <script>
                                        document.getElementById('trabajo_texto').disabled = true;
                                        document.getElementById('div_entrega_doc').style.display = "none";
                                    </script>
                                    <?php
                                }
                                ?>
                                <form class="account__form" id="correccion_form" style="display:none;">
                                <?php if(isset($id_entrega)){
                                ?>
                                    <input type="hidden" id="entregaEncrypted" name="entregaEncrypted" value="<?php echo $id_entrega; ?>">
                                <?php 
                                }  ?>
                                    <p style="font-weight:bold;">Observaciones para el alumno</p>
                                    <div class="form-grp">
                                        <textarea name="observacion_instructor" id="observacion_instructor"
                                            placeholder=""><?php if ($info_tarea[0]['observacion_instructor'] != null) {
                                                echo $info_tarea[0]['observacion_instructor'];
                                            } ?></textarea>
                                    </div>
                                    <label for="puntos_logrados_tp">Puntos logrados de
                                        <?php echo basename($info_tarea[0]["total_puntos"]); ?></label>
                                    <div class="form-grp col-lg-2 col-md-2 col-xl-2">

                                        <input id="puntos_logrados_tp" name="puntos_logrados_tp" type="number" placeholder="20"
                                        value="<?php echo $info_tarea[0]['puntos_logrados_tp']; ?>">
                                    </div>
                                    <button type="button" class="btn btn-two arrow-btn" onclick="corregirTP()"
                                        style="width:auto;">Entregar<img
                                            src="../../public/skillgro/img/icons/right_arrow.svg" alt="img"
                                            class="injectable"></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- singUp-area-end -->

        </main>
        <!-- main-area-end -->

        <!-- footer-area -->
        <?php require_once ("html/footer.php"); ?>
        <!-- footer-area-end -->

        <!-- JS here -->
        <?php require_once ("html/js.php"); ?>
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
        <?php
        if (isset($_GET["IDENTREGA"])) {
            ?>
            document.getElementById('div_entrega_doc').style.display = "none";
            document.getElementById('sin_modif').style.display = "none";
            document.getElementById('correccion_form').style.display = "block";
            document.getElementById('p_redactar').textContent = 'Redacción del alumno';
                    <?php
                    // error_log('encuentra IDENTREGA');
        }
        
        ?>

                </script>

                </html>
                <?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>