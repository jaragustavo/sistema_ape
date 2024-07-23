<?php
require_once ("../../config/conexion.php");
if (isset($_SESSION["usuario_id"])) {
    ?>
    <!DOCTYPE html>
    <html>
    <?php require_once ("../MainHead/head.php"); ?>
    <link rel="stylesheet" href="../../public/css/separate/pages/widgets.min.css">

    <title>APE | Mi perfil</title>
    </head>

    <body class="with-side-menu">

        <?php require_once ("../MainHeader/header.php"); ?>

        <div class="mobile-menu-left-overlay"></div>

        <?php require_once ("../MainNav/nav.php"); ?>

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
                                                $cantidades_perfil = Publicacion::cantidades_perfil($_SESSION["usuario_id"]);

                                                ?>
                                                <div class="tbl-cell">

                                                </div>
                                                <div class="tbl-cell tbl-cell-stat">
                                                    <div class="inline-block">
                                                        <p class="title">
                                                            <?php echo $cantidades_perfil["total_seguidores"] ?>
                                                        </p>
                                                        <p>Seguidores</p>
                                                    </div>
                                                </div>
                                                <div class="tbl-cell tbl-cell-stat">
                                                    <div class="inline-block">
                                                        <p class="title"><?php echo $cantidades_perfil["total_adjuntos"] ?>
                                                        </p>
                                                        <p>Adjuntos</p>
                                                    </div>
                                                </div>
                                                <div class="tbl-cell tbl-cell-stat">
                                                    <div class="inline-block">
                                                        <p class="title">
                                                            <?php echo $cantidades_perfil["total_publicaciones"] ?>
                                                        </p>
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
            </div><!--.profile-header-photo-->

            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-3 col-lg-4">
                        <aside class="profile-side">
                            <section class="box-typical profile-side-user" id="seccion_foto_perfil">
                                <button type="button" class="avatar-preview avatar-preview-128">
                                    <?php
                                    $foto_perfil = Publicacion::get_foto_perfil($_SESSION["usuario_id"]);
                                    ?>
                                    <img src="../<?php echo ($foto_perfil != null && $foto_perfil != '') ? $foto_perfil : "assets/assets-main/images/icons/user2.png"; ?>"
                                        alt="" />
                                    <span class="update">
                                        <i class="font-icon font-icon-picture-double"></i>
                                        Cambiar foto
                                    </span>
                                    <input id="foto_perfil" type="file" onchange="guardarFoto()" />
                                </button>
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
                                <header class="box-typical-header-sm bordered">Acerca de mí</header>
                                <div class="box-typical-inner">
                                    <p id="parrafo_acerca_de_mi"></p>
                                </div>
                            </section>

                            <section class="box-typical">
                                <header class="box-typical-header-sm bordered">Información laboral</header>
                                <div class="box-typical-inner">
                                    <div id="div_ciudad_trabajo">
                                        <p class="line-with-icon" id="parrafo_ciudad_trabajo">
                                            <i class="font-icon font-icon-pin-2"></i>
                                        </p>
                                    </div>
                                    <div id="div_lugar_trabajo">

                                        <p class="line-with-icon" id="parrafo_lugar_trabajo">
                                            <i class="font-icon font-icon-case-3"></i>
                                        </p>
                                    </div>
                                    <div id="div_educacion">
                                        <p class="line-with-icon" id="parrafo_educacion">
                                            <i class="font-icon font-icon-learn"></i>
                                        </p>
                                    </div>
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
                                            <span class="nav-link-in">Mis publicaciones</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#tabs-2-tab-4" role="tab" data-toggle="tab">
                                            <span class="nav-link-in">Mis datos</span>
                                        </a>
                                    </li>
                                </ul>
                            </div><!--.tabs-section-nav-->


                            <div class="tab-content no-styled profile-tabs">
                                <div role="tabpanel" class="tab-pane active" id="tabs-2-tab-1">

                                    <form class="box-typical" id="data_nuevo_post">
                                        <textarea rows="5" class="form-control" id="texto_publicacion"
                                            name="texto_publicacion"
                                            placeholder="¿Nueva publicación? Escriba algo..."></textarea>
                                        <div class="box-typical-footer">
                                            <div class="tbl">
                                                <div class="tbl-row">
                                                    <div class="tbl-cell">
                                                        <div class="tbl-cell">
                                                            <button type="button" class="btn-icon" id="selectIconButton"
                                                                title="Privacidad">
                                                                <i class="font-icon font-icon-earth" id="icon"></i>
                                                            </button>

                                                            <select id="visibilitySelect" style="display:none">
                                                                <option value="public">Público</option>
                                                                <option value="private">Sólo para mí</option>
                                                            </select>

                                                            <script>
                                                                document.getElementById('selectIconButton').addEventListener('click', function () {
                                                                    document.getElementById('visibilitySelect').setAttribute('style', 'display:true');
                                                                    document.getElementById('visibilitySelect').click();
                                                                });

                                                                document.getElementById('visibilitySelect').addEventListener('change', function () {
                                                                    var selectedValue = this.value;
                                                                    var iconElement = document.getElementById('icon');

                                                                    // Change the icon based on the selected option
                                                                    if (selectedValue === 'public') {
                                                                        iconElement.className = 'font-icon font-icon-earth';
                                                                    } else if (selectedValue === 'private') {
                                                                        // Assuming there is an icon class for the user, replace 'font-icon-user' with the appropriate class
                                                                        iconElement.className = 'font-icon font-icon-user';
                                                                    }
                                                                    document.getElementById('visibilitySelect').setAttribute('style', 'display:none');
                                                                });
                                                            </script>
                                                            <button type="button" class="btn-icon" id="choosePhotoBtn"
                                                                title="adjuntar archivos">
                                                                <i class="font-icon font-icon-clip"></i>
                                                                <input type="file" id="photoInput" style="display: none;"
                                                                    multiple />
                                                            </button>
                                                            <div class="" style="padding:5px;">Adjuntos</div>
                                                            <div id="filePreviewContainer" class="file-preview-container">
                                                            </div>


                                                        </div>
                                                    </div>


                                                </div>
                                                <div class="tbl-row">
                                                    <div class="tbl-cell tbl-cell-action">
                                                        <button type="button" class="btn btn-rounded" onclick="postear()"
                                                            style="float:right; margin-left: 5px;">Postear</button>
                                                        <!-- <button type="button" class="btn btn-secondary btn-rounded" onclick="window.location.reload();"
                                                        style="float:right;">Descartar</button> -->
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </form><!--.box-typical-->

                                    <?php
                                    require_once ("misPublicaciones.php");
                                    ?>
                                </div><!--.tab-pane-->

                                <div role="tabpanel" class="tab-pane" id="tabs-2-tab-4">
                                    <section class="box-typical profile-settings">
                                        <form method="post" id="datos_perfil_form">
                                            <section class="box-typical-section">
                                                <div class="form-group row">
                                                    <div class="col-xl-3">
                                                        <label class="form-label">Nombre</label>
                                                    </div>
                                                    <div class="col-xl-4">
                                                        <input class="form-control" type="text" id="nombre_perfil"
                                                            name="nombre_perfil" />
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-xl-3">
                                                        <label class="form-label">Profesión principal</label>
                                                    </div>
                                                    <div class="col-xl-4">
                                                        <select class="select2 form-control" id="profesion_principal"
                                                            name="profesion_principal">
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-xl-3">
                                                        <label class="form-label">Acerca de mí</label>
                                                    </div>
                                                    <div class="col-xl-6">
                                                        <textarea rows="3" class="form-control" id="acerca_de_mi"
                                                            name="acerca_de_mi"></textarea>
                                                    </div>
                                                </div>
                                            </section>
                                            <section class="box-typical-section">
                                                <header class="box-typical-header-sm">Información laboral</header>
                                                <div class="form-group row">
                                                    <div class="col-xl-3">
                                                        <label class="form-label">
                                                            <i class="font-icon font-icon-pin-2"></i>
                                                            Ciudad
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-4">
                                                        <select class="select2 form-control" id="ciudad_trabajo"
                                                            name="ciudad_trabajo">
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-xl-3">
                                                        <label class="form-label">
                                                            <i class="font-icon font-icon-case-3"></i>
                                                            Lugar de trabajo
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-4">
                                                        <select class="select2 form-control" id="lugar_trabajo"
                                                            name="lugar_trabajo">
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-xl-3">
                                                        <label class="form-label">
                                                            <i class="font-icon font-icon-learn"></i>
                                                            Educación
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-6">
                                                        <input class="form-control" type="text" id="educacion"
                                                            name="educacion" />
                                                    </div>
                                                </div>

                                            </section>
                                            <section class="box-typical-section profile-settings-btns">
                                                <button type="button" class="btn btn-rounded" id="guardar_datos_perfil"
                                                    onclick="guardarDatosPerfil()">Guardar cambios</button>
                                                <button type="button" class="btn btn-rounded btn-grey"
                                                    onclick="location.reload()">Cancelar</button>
                                            </section>
                                        </form>
                                    </section>
                                </div><!--.tab-pane-->
                            </div><!--.tab-content-->
                        </section><!--.tabs-section-->
                    </div>
                </div><!--.row-->
            </div><!--.container-fluid-->
        </div><!--.page-content-->

        <?php require_once ("../MainJs/js.php"); ?>
        <script src="../../public/js/lib/salvattore/salvattore.min.js"></script>
        <script src="../../public/js/lib/fancybox/jquery.fancybox.pack.js"></script>
        <script src="../MainJs/file-uploading.js"></script>
        <script src="publicaciones.js?v=<?php echo time(); ?>"></script>
        <script src="../Perfiles/perfil_profesional.js?v=<?php echo time(); ?>"></script>

    </body>

    </html>
    <?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>