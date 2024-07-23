<?php
require_once ("../../config/conexion.php");
if (isset ($_SESSION["usuario_id"])) {

    ?>
    <!doctype html>
    <html class="no-js" lang="en">
    <?php require_once ("html/head.php"); ?>
    <title>APE </title>
<style>a {
    display: flex;
    align-items: center; /* Align items vertically in the center */
    text-decoration: none; /* Optional: remove underline from links */
}

a .task-title, a .course-item-meta {
    display: inline-block;
    margin-right: 10px; /* Adjust the spacing between elements as needed */
}

a .course-item-meta {
    display: inline-block;
}

.item-meta.duration {
    white-space: nowrap; /* Prevents the date from wrapping to a new line */
}

/* Optional: Style the link when hovered */
a:hover {
    text-decoration: underline; /* Optional: add underline on hover */
}</style>
    </head>

    <body>

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
            <?php
            require "../../models/Certificacion.php";
            require "../../models/Instructor.php";
            $id_curso = 1;
            if (isset ($_GET["IDCURSO"])) {
                $id_curso = $_GET["IDCURSO"];
                $id_curso = str_replace(' ', '+', $id_curso);
                $key = "mi_key_secret";
                $cipher = "aes-256-cbc";
                $iv_dec = substr(base64_decode($id_curso), 0, openssl_cipher_iv_length($cipher));
                $cifradoSinIV = substr(base64_decode($id_curso), openssl_cipher_iv_length($cipher));
                $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);
                $secciones = Instructor::get_secciones_x_curso($decifrado);
                $info_curso = Instructor::get_informacion_curso($decifrado);
            }

            ?>
            <!-- breadcrumb-area -->
            <section class="breadcrumb__area breadcrumb__bg" data-background="assets/img/bg/breadcrumb_bg.jpg">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="breadcrumb__content">
                                <h3 class="title">
                                    <?php echo $info_curso["nombre_curso"] ?>
                                </h3>
                                <nav class="breadcrumb">
                                    <span property="itemListElement" typeof="ListItem">
                                        <a href="listarCertificaciones.php">Inicio</a>
                                    </span>
                                    <span class="breadcrumb-separator"><i class="fas fa-angle-right"></i></span>
                                    <a href="listarCertificaciones.php"><span property="itemListElement"
                                            typeof="ListItem"></span></a>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="breadcrumb__shape-wrap">
                    <img src="../../public/skillgro/img/others/breadcrumb_shape01.svg" alt="img" class="alltuchtopdown">
                    <img src="../../public/skillgro/img/others/breadcrumb_shape02.svg" alt="img" data-aos="fade-right"
                        data-aos-delay="300">
                    <img src="../../public/skillgro/img/others/breadcrumb_shape03.svg" alt="img" data-aos="fade-up"
                        data-aos-delay="400">
                    <img src="../../public/skillgro/img/others/breadcrumb_shape04.svg" alt="img" data-aos="fade-down-left"
                        data-aos-delay="400">
                    <img src="../../public/skillgro/img/others/breadcrumb_shape05.svg" alt="img" data-aos="fade-left"
                        data-aos-delay="400">
                </div>
            </section>
            <!-- breadcrumb-area-end -->

            <!-- courses-details-area -->
            <section class="courses__details-area section-py-120">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-9 col-lg-8">
                            <div class="courses__details-thumb">
                                <img src="../<?php echo $info_curso["imagen_portada"] ?>" alt="img">
                            </div>

                            <div class="courses__details-content">
                                <ul class="courses__item-meta list-wrap">
                                    <li class="courses__item-tag">
                                        <a href="course.html">
                                            <?php echo $info_curso["nombre_categoria"] ?>
                                        </a>
                                    </li>
                                    <li class="avg-rating"><i class="fas fa-star"></i> (
                                        <?php echo $info_curso["validacion"] ?> Reviews)
                                    </li>
                                </ul>
                                <h2 class="title">
                                    <?php echo $info_curso["nombre_curso"] ?>
                                </h2>
                                <div class="courses__details-meta">
                                    <ul class="list-wrap">
                                        <li class="author-two">
                                            <img src="assets/img/courses/course_author001.png" alt="img">
                                            Por
                                            <a href="#">
                                                <?php echo $info_curso["instructor"] ?>
                                            </a>
                                        </li>
                                        <li class="date"><i class="flaticon-calendar"></i>
                                            <?php echo $info_curso["fecha_curso"] ?>
                                        </li>
                                        <li><i class="flaticon-mortarboard"></i>
                                            <?php echo $info_curso["cantidad_inscriptos"] ?> Inscriptos
                                        </li>
                                    </ul>
                                </div>
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="overview-tab" data-bs-toggle="tab"
                                            data-bs-target="#overview-tab-pane" type="button" role="tab"
                                            aria-controls="overview-tab-pane" aria-selected="true">Resumen</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="instructors-tab" data-bs-toggle="tab"
                                            data-bs-target="#instructors-tab-pane" type="button" role="tab"
                                            aria-controls="instructors-tab-pane" aria-selected="false">Instructor</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="curriculum-tab" data-bs-toggle="tab"
                                            data-bs-target="#curriculum-tab-pane" type="button" role="tab"
                                            aria-controls="curriculum-tab-pane" aria-selected="false">Videos</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="reviews-tab" data-bs-toggle="tab"
                                            data-bs-target="#reviews-tab-pane" type="button" role="tab"
                                            aria-controls="reviews-tab-pane" aria-selected="false">Materiales</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="tareas-tab" data-bs-toggle="tab"
                                            data-bs-target="#tareas-tab-pane" type="button" role="tab"
                                            aria-controls="tareas-tab-pane" aria-selected="false">Tareas</button>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">


                                    <!-- RESUMEN -->
                                    <div class="tab-pane fade show active" id="overview-tab-pane" role="tabpanel"
                                        aria-labelledby="overview-tab" tabindex="0">
                                        <div class="courses__overview-wrap">
                                            <h3 class="title">Descripción del curso</h3>
                                            <p>
                                                <?php echo $info_curso["descripcion_curso"] ?>
                                            </p>
                                            <h3 class="title">Qué aprenderás?</h3>
                                            <p>
                                                <?php echo $info_curso["aprendizaje"] ?>
                                            </p>
                                        </div>
                                    </div>


                                    <!-- CONTENIDO -->
                                    <div class="tab-pane fade" id="curriculum-tab-pane" role="tabpanel"
                                        aria-labelledby="curriculum-tab" tabindex="0">
                                        <div class="courses__curriculum-wrap">
                                            <h3 class="title">Contenido del curso</h3>
                                            <p></p>
                                            <div class="accordion" id="accordionExample">
                                                <?php
                                                
                                                foreach ($secciones as $seccion) {


                                                    ?>
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingOne">
                                                            <button class="accordion-button" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $seccion["seccion_id"] ?>"
                                                                aria-expanded="true" aria-controls="collapse<?php echo $seccion["seccion_id"] ?>">
                                                                <?php echo $seccion["titulo"] ?>
                                                            </button>
                                                        </h2>
                                                        <?php
                                                        $lecciones = Instructor::get_lecciones_x_seccion($seccion["seccion_id"]);
                                                        if(is_array($lecciones)){
                                                        ?>
                                                        
                                                            <div id="collapse<?php echo $seccion["seccion_id"] ?>" class="accordion-collapse collapse show"
                                                                aria-labelledby="heading<?php echo $seccion["seccion_id"] ?>" data-bs-parent="#accordionExample">
                                                                <div class="accordion-body">
                                                                    <ul class="list-wrap">
                                                                        <?php
                                                                        foreach($lecciones as $leccion){
                                                                            
                                                                        ?>
                                                                        <li class="course-item open-item">
                                                                            <a href="../<?php echo $leccion["video_url"] ?>" class="course-item-link popup-video">
                                                                                <span class="item-name"><?php echo $leccion["titulo"] ?></span>
                                                                                <div class="course-item-meta">
                                                                                    <span class="item-meta duration" id="duracion<?php echo $leccion["leccion_id"] ?>"></span>
                                                                                </div>
                                                                            </a>
                                                                        </li>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        <?php
                                                        }
                                                        ?>
                                                    </div>

                                                <?php
                                                }

                                                ?>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- INSTRUCTOR -->
                                    <div class="tab-pane fade" id="instructors-tab-pane" role="tabpanel"
                                        aria-labelledby="instructors-tab" tabindex="0">
                                        <div class="courses__instructors-wrap">
                                            <div class="courses__instructors-thumb">
                                                <img src="assets/img/courses/course_instructors.png" alt="img">
                                            </div>
                                            <div class="courses__instructors-content">
                                                <h2 class="title">
                                                    <?php echo $info_curso["instructor"] ?>
                                                </h2>
                                                <span class="designation">UX Design Lead</span>
                                                <p class="avg-rating"><i class="fas fa-star"></i>(
                                                    <?php echo $info_curso["validacion"] ?> Calificaciones)
                                                </p>
                                                <p>Dorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                                                    tempor incididunt ut labore et dolore magna aliqua Quis ipsum
                                                    suspendisse ultrices gravida. Risus commodo viverra maecenas accumsan.
                                                </p>
                                                <div class="instructor__social">
                                                    <ul class="list-wrap justify-content-start">
                                                        <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                                        <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                                        <li><a href="#"><i class="fab fa-whatsapp"></i></a></li>
                                                        <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- MATERIALES -->
                                    <div class="tab-pane fade" id="reviews-tab-pane" role="tabpanel"
                                        aria-labelledby="reviews-tab" tabindex="0">
                                        <div class="courses__curriculum-wrap">
                                            <h3 class="title">Materiales del curso</h3>
                                            <p></p>
                                            <div class="accordion" id="accordionExample">
                                                <?php
                                                $secciones = Instructor::get_secciones_x_curso($decifrado);
                                                foreach ($secciones as $seccion) {


                                                    ?>
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingOne">
                                                            <button class="accordion-button" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $seccion["seccion_id"] ?>"
                                                                aria-expanded="true" aria-controls="collapse<?php echo $seccion["seccion_id"] ?>">
                                                                <?php echo $seccion["titulo"] ?>
                                                            </button>
                                                        </h2>
                                                        <?php
                                                        $materiales = Instructor::get_materiales_x_seccion($seccion["seccion_id"]);
                                                        if(is_array($materiales)){

                                                        ?>
                                                        <?php
                                                        foreach($materiales as $material){                                                 
                                                        ?>
                                                        <div id="collapse<?php echo $seccion["seccion_id"] ?>" class="accordion-collapse collapse show"
                                                            aria-labelledby="heading<?php echo $seccion["seccion_id"] ?>" data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <ul class="list-wrap">
                                                                    
                                                                    <li class="course-item open-item">
                                                                        <a href="../<?php echo $material["archivo"] ?>">
                                                                            <span class=""><?php echo basename($material["archivo"]) ?></span>
                                                                        </a>
                                                                    </li>
                                                                    
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                    </div>

                                                    <?php
                                                }

                                                ?>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- TAREAS -->
                                    <div class="tab-pane fade" id="tareas-tab-pane" role="tabpanel"
                                        aria-labelledby="tareas-tab" tabindex="5">
                                        <div class="courses__curriculum-wrap">
                                            <h3 class="title">Tareas</h3>
                                            <p></p>
                                            <div class="accordion" id="accordionTareas">
                                                <?php
                                                foreach ($secciones as $seccion) {
                                                    ?>
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header">
                                                            <button class="accordion-button" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $seccion["seccion_id"] ?>"
                                                                aria-expanded="true" aria-controls="collapse<?php echo $seccion["seccion_id"] ?>">
                                                                <?php echo $seccion["titulo"] ?></a>
                                                            </button>
                                                        </h2>
                                                        <?php
                                                        require_once ("../../models/Certificacion.php");
                                                        $tareas = Certificacion::get_tareas_x_seccion($seccion["seccion_id"]);
                                                        if(is_array($tareas)){

                                                        ?>
                                                        <?php
                                                        foreach($tareas as $tarea){   
                                                        ?>
                                                        <div id="collapse<?php echo $seccion["seccion_id"] ?>" class="accordion-collapse collapse show"
                                                            aria-labelledby="heading<?php echo $seccion["seccion_id"] ?>" data-bs-parent="#accordionTareas">
                                                            <div class="accordion-body">
                                                                <ul class="list-wrap">
                                                                    
                                                                    <li class="course-item open-item">
                                                                        <?php  
                                                                        $key = "mi_key_secret";
                                                                        $cipher = "aes-256-cbc";
                                                                        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
                                                                        $cifrado = openssl_encrypt($tarea["tarea_id"], $cipher, $key, OPENSSL_RAW_DATA, $iv);
                                                                        $tareaEncrypted = base64_encode($iv . $cifrado);
                                                                        $link="";
                                                                        if($tarea['tipo_tarea'] == 1){
                                                                            $link="trabajoPractico";
                                                                        }
                                                                        else{
                                                                            $link="cuestionario";
                                                                        }
                                                                        ?>
                                                                        <a href="../Certificaciones/<?php echo $link; ?>.php?IDTAREA=<?php echo $tareaEncrypted; ?>" target="_blank">
                                                                            <span class="task-title"><?php echo $tarea["titulo_tarea"]; ?></span>
                                                                            <div class="course-item-meta">
                                                                                <?php
                                                                                if($tarea["entrega"] == null){
                                                                                    ?>
                                                                                <span class="item-meta duration"><?php echo 'Fecha límite: ' . $tarea["fecha_limite"]; ?></span>
                                                                                <?php
                                                                                }
                                                                                elseif($tarea["entrega"] >= 0){
                                                                                ?>
                                                                                <span class="item-meta duration"><?php echo $tarea["entrega"]. ' de '.$tarea["total_puntos"]; ?></span>

                                                                                <?php
                                                                                }
                                                                                else{
                                                                                    ?>
                                                                                <span class="item-meta duration">No corregido</span>

                                                                                <?php
                                                                                }
                                                                                ?>
                                                                            </div>
                                                                        </a>
                                                                    </li>
                                                                    
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                    </div>

                                                    <?php
                                                }

                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4">
                            <div class="courses__details-sidebar">

                                <div class="courses__information-wrap">
                                    <h5 class="title">Datos del curso:</h5>
                                    <ul class="list-wrap">
                                        <li>
                                            <img src="../../public/skillgro/img/icons/course_icon01.svg" alt="img"
                                                class="injectable">
                                            Nivel
                                            <span>Experto</span>
                                        </li>
                                        <li>
                                            <img src="../../public/skillgro/img/icons/course_icon02.svg" alt="img"
                                                class="injectable">
                                            Duración
                                            <span>11h 20m</span>
                                        </li>
                                        <li>
                                            <img src="../../public/skillgro/img/icons/course_icon03.svg" alt="img"
                                                class="injectable">
                                            Lecciones
                                            <span>
                                                <?php echo $info_curso["cantidad_lecciones"] ?>
                                            </span>
                                        </li>
                                        <li>
                                            <img src="../../public/skillgro/img/icons/course_icon04.svg" alt="img"
                                                class="injectable">
                                            Tareas
                                            <span>
                                                <?php echo $info_curso["cantidad_tareas"] ?>
                                            </span>
                                        </li>
                                        <li>
                                            <img src="../../public/skillgro/img/icons/course_icon05.svg" alt="img"
                                                class="injectable">
                                            Certificación
                                            <?php
                                            if ($info_curso["certificacion"]) {


                                                ?>
                                                <span>Sí</span>
                                                <?php
                                            } else {
                                                ?>
                                                <span>No</span>
                                                <?php
                                            }
                                            ?>
                                        </li>
                                        <li>
                                            <img src="../../public/skillgro/img/icons/course_icon06.svg" alt="img"
                                                class="injectable">
                                            Avance
                                            <span>
                                                <?php echo $info_curso["cantidad_tareas"] ?>%
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                                <!-- SI NO ESTÁ INSCRIPTO -->
                                <div class="courses__details-enroll">
                                    <div class="tg-button-wrap">
                                        <a href="calificaciones.php?IDCURSO=<?php echo $id_curso ?>" class="btn btn-two arrow-btn">
                                            Calificaciones
                                            <img src="../../public/skillgro/img/icons/right_arrow.svg" alt="img"
                                                class="injectable">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- courses-details-area-end -->

        </main>
        <!-- main-area-end -->

        <!-- footer-area -->
        <?php require_once ("html/footer.php"); ?>
        <!-- footer-area-end -->

        <!-- JS here -->
        <?php require_once ("html/js.php"); ?>
    </body>

    </html>
    <?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all anchor elements with the specified class
        var videoLinks = document.querySelectorAll('.course-item-link.popup-video');

        // Iterate over each anchor element
        videoLinks.forEach(function(videoLink) {
            // Get the href attribute value of the anchor element (video URL)
            var videoUrl = videoLink.getAttribute('href');

            // Create a video element dynamically
            var video = document.createElement('video');
            video.src = videoUrl;
            video.preload = 'metadata'; // Load metadata only

            // Wait for metadata to load
            video.addEventListener('loadedmetadata', function() {
                // Get the duration of the video
                var duration = video.duration;

                // Format the duration in minutes and seconds
                var formattedDuration = formatDuration(duration);

                // Update the content of the span element with the formatted duration
                var spanId = videoLink.querySelector('.item-meta.duration').id;
                document.getElementById(spanId).textContent = formattedDuration;
            });
        });
    });

    // Function to format duration in minutes and seconds
    function formatDuration(durationInSeconds) {
        var minutes = Math.floor(durationInSeconds / 60);
        var seconds = Math.floor(durationInSeconds % 60);
        // Pad single-digit seconds with a leading zero
        var formattedMinutes = (minutes < 10) ? "0" + minutes : minutes;
        var formattedSeconds = (seconds < 10) ? "0" + seconds : seconds;
        return formattedMinutes + ':' + formattedSeconds;
    }
</script>