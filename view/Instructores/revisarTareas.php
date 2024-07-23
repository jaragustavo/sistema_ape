<?php
require_once ("../../config/conexion.php");
if (isset($_SESSION["usuario_id"])) {

    ?>
    <!doctype html>
    <html class="no-js" lang="en">
    <?php require_once ("../Certificaciones/html/head.php"); ?>
    <link rel="stylesheet" href="../../assets/assets-main/css/plugins/feather.css">
    <link rel="stylesheet" href="../../assets/assets-main/css/style.css">

    <title>APE </title>

    <style>
        a {
            display: flex;
            align-items: center;
            /* Align items vertically in the center */
            text-decoration: none;
            /* Optional: remove underline from links */
        }

        a .task-title,
        a .course-item-meta {
            display: inline-block;
            margin-right: 10px;
            /* Adjust the spacing between elements as needed */
        }

        a .course-item-meta {
            display: inline-block;
        }

        .item-meta.duration {
            white-space: nowrap;
            /* Prevents the date from wrapping to a new line */
        }

        /* Optional: Style the link when hovered */
        a:hover {
            text-decoration: underline;
            /* Optional: add underline on hover */
        }
    </style>

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
        <?php require_once ("../Certificaciones/html/header.php"); ?>
        <!-- header-area-end -->



        <!-- main-area -->
        <main class="main-area fix">
            <?php
            require "../../models/Certificacion.php";
            require "../../models/Instructor.php";


            ?>
            <div class="rbt-dashboard-table table-responsive mobile-table-750 mt--30">
                <table class="rbt-table table table-borderless">
                    <thead>
                        <tr>
                            <th>Alumnos</th>
                            <th>Fecha de entrega</th>
                            <th>Puntos logrados</th>
                            <th>Porcentaje logrado</th>
                            <th>Resultado</th>
                            <th>Ver entrega</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $key = "mi_key_secret";
                        $cipher = "aes-256-cbc";
                        $_GET["IDTAREA"] = str_replace(' ', '+', $_GET["IDTAREA"]);
                        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
                        $iv_dec = substr(base64_decode($_GET['IDTAREA']), 0, openssl_cipher_iv_length($cipher));
                        $cifradoSinIV = substr(base64_decode($_GET['IDTAREA']), openssl_cipher_iv_length($cipher));
                        $_GET['IDTAREA'] = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);
                        $entregas = Instructor::get_entregas_x_tarea($_GET['IDTAREA']);
                        foreach ($entregas as $entrega) {
                            ?>
                            <tr>
                                <th>
                                    <!-- <p class="b3 mb--5">December 26, 2022</p>
                                    <span class="h6 mb--5">Write a short essay on yourself using the 5</span> -->
                                    <p class="b3"><a href="#"><?php echo $entrega['alumno']; ?></a></p>
                                </th>
                                <td>
                                    <p class="b3"><?php echo $entrega['fecha_entrega']; ?></p>
                                </td>
                                <td>
                                    <p class="b3">

                                        <?php
                                        // $entrega['puntos_logrados'] = 15;
                                        if ($entrega['puntos_logrados'] >= 0) {
                                            echo $entrega['puntos_logrados'] . '/' . $entrega['total_puntos'];
                                        } else {
                                            echo 'No corregido';
                                        }
                                        ?>
                                    </p>
                                </td>
                                <td>
                                    <p class="b3"><?php if ($entrega['puntos_logrados'] >= 0) {
                                        if ($entrega['total_puntos'] > 0) {
                                            $porcentaje = $entrega['puntos_logrados'] / $entrega['total_puntos'] * 100;
                                            echo $porcentaje . '%';
                                        } else {
                                            echo '-';
                                        }
                                    } else {
                                        $porcentaje = null;
                                        echo '-';
                                    }
                                    ?></p>
                                </td>
                                <td>
                                    <?php $tag = ""; $color ="";
                                    if ($porcentaje >= 60) {
                                        $tag = 'Aprobado';
                                        $color = 'bg-color-success-opacity color-success';
                                    } else if ($porcentaje < 60 && $porcentaje != null) {
                                        $tag = 'Reprobado';
                                        $color= 'bg-color-danger-opacity color-danger';
                                    } else if ($porcentaje == null) {
                                        $tag = '-';
                                    }
                                    ?>
                                    <span class="rbt-badge-5 <?php echo $color ?>">
                                    <?php echo $tag ?></span>
                                </td>
                                <td>
                                    <?php
                                    $cifrado = openssl_encrypt($entrega["id_entrega"], $cipher, $key, OPENSSL_RAW_DATA, $iv);
                                    $id_entrega = base64_encode($iv . $cifrado);
                                    ?>
                                    <div class="rbt-button-group justify-content-end">
                                        <button title="Ver entrega" type="button" data-ciphertext="<?php echo $id_entrega; ?>"
                                            id="<?php echo $id_entrega; ?>"
                                            class="rbt-btn btn-xs bg-primary-opacity radius-round btn-ver-entrega"><i
                                                class="feather-eye pl--0" aria-hidden="true"></i></button>
                                    </div>
                                </td>
                            </tr>

                            <?php
                        }
                        ?>


                    </tbody>

                </table>
            </div>

        </main>
        <!-- main-area-end -->

        <!-- footer-area -->
        <?php require_once ("../Certificaciones/html/footer.php"); ?>
        <!-- footer-area-end -->

        <!-- JS here -->
        <?php require_once ("../Certificaciones/html/js.php"); ?>
        <script type="text/javascript" src="instructores.js?v=<?php echo time(); ?>"></script>

    </body>

    </html>
    <?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>