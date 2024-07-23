<?php
require_once("../../config/conexion.php");
if (isset($_SESSION["usuario_id"])) {
    ?>
    <!DOCTYPE html>
    <html>
    <?php require_once("../MainHead/head.php"); ?>
    <link rel="stylesheet" href="../../public/css/separate/pages/widgets.min.css">
    <style>
        .pendiente {
            color: red;
        }
    </style>
    <title>APE | Mi perfil</title>
    </head>

    <body class="with-side-menu">

        <?php require_once("../MainHeader/header.php"); ?>

        <div class="mobile-menu-left-overlay"></div>

        <?php require_once("../MainNav/nav.php"); ?>

        <div class="page-content">
            <div class="profile-header-photo gradient" style="background-image: url(../../public/img/profile-bg.jpg)">
                <div class="profile-header-photo-in">
                    <div class="tbl-cell">
                        <div class="info-block">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-xl-9 col-xl-offset-3 col-lg-8 col-lg-offset-4 col-md-offset-0">
                                        <div class="tbl info-tbl">
                                            <div class="tbl-row">
                                                <?php
                                                require_once "../../models/Publicacion.php";
                                                $cantidades_perfil = Publicacion::cantidades_perfil($_GET["ID"]);
                                                
                                                ?>
                                                <input type="hidden" id="usuario_visitado_id" value="<?php echo $_GET["ID"] ?>">
                                                <div class="tbl-cell">

                                                </div>
                                                <div class="tbl-cell tbl-cell-stat">
                                                    <div class="inline-block">
                                                        <p class="title"><?php echo $cantidades_perfil["total_seguidores"] ?></p>
                                                        <p>Seguidores</p>
                                                    </div>
                                                </div>
                                                <div class="tbl-cell tbl-cell-stat">
                                                    <div class="inline-block">
                                                        <p class="title"><?php echo $cantidades_perfil["total_adjuntos"] ?></p>
                                                        <p>Adjuntos</p>
                                                    </div>
                                                </div>
                                                <div class="tbl-cell tbl-cell-stat">
                                                    <div class="inline-block">
                                                        <p class="title"><?php echo $cantidades_perfil["total_publicaciones"] ?></p>
                                                        <p>Publicaciones</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <button type="button" class="change-cover">
                    <i class="font-icon font-icon-picture-double"></i>
                   cambiar imagen
                    <input type="file" />
                </button> -->
            </div><!--.profile-header-photo-->

            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-3 col-lg-4">
                        <aside class="profile-side">
                            <section class="box-typical profile-side-user" id="profile_side_user">
                         
                                    <button type="button" class="avatar-preview avatar-preview-128">

                                     <?php
                                        require_once "../../models/Publicacion.php";
                                        require_once "../../models/Usuario.php";

                                        $info_perfil = Publicacion::get_info_perfil($_GET["ID"]);

                                        $imagenes_perfil = Publicacion::get_imagenes_perfil($_GET["ID"]);

                                        /* Datos profesional */
                                        $userData = Usuario::get_usuario($_GET["ID"]);

                                        $jsonDatosProfesionales = !empty($userData['jsonDatosProfesionales']) ? json_decode($userData['jsonDatosProfesionales'], true) : null;

                                        $foto_registro_profesional = $imagenes_perfil['foto_registro_profesional'];
                                        $foto_perfil = $imagenes_perfil['foto_perfil'];
                                        $foto_ci = $imagenes_perfil['foto_ci'];

                                        $esImagen = false;
                                        $esPDF = false;

                                        if ($foto_ci != null && $foto_ci != '') {
                                            $extension = pathinfo($foto_ci, PATHINFO_EXTENSION);
                                            if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png'])) {
                                                $esImagen = true;
                                            } elseif (strtolower($extension) == 'pdf') {
                                                $esPDF = true;
                                            }
                                        } else {
                                            $foto_ci = "assets/assets-main/images/icons/user2.png";
                                            $esImagen = true; // Para mostrar la imagen predeterminada
                                        }
                                    ?>
                                    <script>
                                     //   var jsonDatosProfesionales = <?php echo $jsonDatosProfesionales ? $jsonDatosProfesionales : 'null'; ?>;
  

                                    //    console.log('&&&&&&&&& '+jsonDatosProfesionales);
                                    </script>

                                    <script>

                                        var jsonDatosProfesionales = <?php echo json_encode($jsonDatosProfesionales, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE); ?>;

                                        console.log('Datos Profesionales:', jsonDatosProfesionales);
                                    </script>
                                                                        
                                    <img src="../<?php echo ($foto_perfil != null && $foto_perfil != '') ? $foto_perfil : "assets/assets-main/images/icons/user2.png"; ?>" alt="" />
                                  
                                </button>
                          
                                    <?php 
                                        require_once "../../models/Publicacion.php";
                                        $siguiendo = Publicacion::siguiendo($info_perfil[0]["usuario_ci"], $_SESSION['usuario_id']);
                                    ?>
                                <button type="button" class="btn btn-rounded">Enviar un mensaje</button>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-rounded"
                                         aria-expanded="false" onclick="seguirUsuario(<?php echo $info_perfil[0]['usuario_ci'] ?>)">
                                        <?php 
                                        if(isset($siguiendo)) {
                                            if($siguiendo){
                                                echo 'Dejar de seguir';
                                            }
                                            else{
                                                echo 'Seguir';
                                            }
                                        }
                                        else{
                                            echo 'Seguir';
                                        }
                                        ?>
                                    </button>
                                </div>
                            </section>

                            <section class="box-typical profile-side-stat">
                                <div class="tbl">
                                    <div class="tbl-row">
                                    <div class="tbl-cell">
                                            <span class="number"><?php echo $cantidades_perfil["total_seguidores"] ?></span>
                                            seguidores
                                        </div>
                                        <div class="tbl-cell">
                                            <span class="number"><?php echo $cantidades_perfil["total_siguiendo"] ?></span>
                                            siguiendo
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <section class="box-typical">
                                <header class="box-typical-header-sm bordered">Acerca de <?php echo $info_perfil[0]['usuario_perfil_nombre'] ?></header>
                                <div class="box-typical-inner">
                                    <p id="parrafo_acerca_de_mi"></p>
                                </div>
                            </section>

                            <section class="box-typical">
                                <header class="box-typical-header-sm bordered">Información Profesional</header>
                                <div class="box-typical-inner">
                                    <p class="line-with-icon">
                                        <i class="font-icon font-icon-pin-2"></i>
                                        <span id="profesion" style="<?php echo empty($userData['profesion']) ? 'color: red;' : ''; ?>">
                                            <?php echo !empty($userData['profesion']) ? $userData['profesion'] : 'Pendiente'; ?>
                                        </span>
                                    </p>
                                   
                                    <p class="line-with-icon">
                                        <i class="font-icon font-icon-case-3"></i>
                                        <span id="lugares_trabajo"></span>
                                    </p>
                                  
                                </div>
                                <header class="box-typical-header-sm bordered">Información Académica</header>
                                <div class="box-typical-inner">
                                    <p class="line-with-icon">
                                        <i class="font-icon font-icon-pin-2"></i>
                                        <span id="lugar_egreso"></span>
                                    </p>
                                    <p class="line-with-icon">
                                        <i class="font-icon font-icon-pin-2"></i>
                                        <span id="anio_egreso"></span>
                                    </p>
                                     <p class="line-with-icon">
                                        <i class="font-icon font-icon-learn"></i>
                                        <span id="estudios"></span>
                                    </p>
                                </div>
                            </section>

                        </aside><!--.profile-side-->
                    </div>

                    <div class="col-xl-9 col-lg-8">
                        <section class="tabs-section">
                            <div class="tabs-section-nav tabs-section-nav-left">
                                <ul class="nav" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#tabs-2-tab-1" role="tab" data-toggle="tab">
                                            <span class="nav-link-in">Publicaciones</span>
                                        </a>
                                    </li>
                                </ul>
                            </div><!--.tabs-section-nav-->


                            <div class="tab-content no-styled profile-tabs">
                                <div role="tabpanel" class="tab-pane active" id="tabs-2-tab-1">

                                    <?php
                                        require("publicacionesUsuario.php");
                                    ?>
                            </div><!--.tab-content-->
                        </section><!--.tabs-section-->
                    </div>
                </div><!--.row-->
            </div><!--.container-fluid-->
        </div><!--.page-content-->

        <?php require_once("../MainJs/js.php"); ?>
        <script src="../../public/js/lib/salvattore/salvattore.min.js"></script>
        <script src="../../public/js/lib/fancybox/jquery.fancybox.pack.js"></script>
        <script src="../MainJs/file-uploading.js"></script>
        <script src="publicaciones.js?v=<?php echo time(); ?>"></script>

    </body>

    </html>
    <?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>