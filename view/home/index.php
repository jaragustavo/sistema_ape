<?php
require_once ("../../config/conexion.php");
if (isset($_SESSION["usuario_id"])) {
    ?>
    <!DOCTYPE html>
    <html>
    <?php require_once ("../MainHead/head.php"); ?>
    <link rel="stylesheet" href="../../public/css/separate/pages/widgets.min.css">
    <link rel="stylesheet" href="../../public/css/separate/pages/profile.min.css">

    <title>APE | Home</title>
    <style>
            .amigos-section {
                display: none;
                /* Oculto por defecto */
            }

            .amigos-section.expanded {
                display: block;
                /* Mostrar cuando se expande */
            }

            .friends-list-item {
                display: block;
                /* Mostrar por defecto */
            }
        </style>
    </head>

    <body class="with-side-menu">

        <?php require_once "../MainHeader/header.php"; ?>

        <div class="mobile-menu-left-overlay"></div>

        <?php require_once "../MainNav/nav.php"; ?>

        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-3 col-lg-4">
                        <section class="box-typical">
                            <div class="profile-card">
                                <div class="avatar-preview avatar-preview-128">
                                    <?php
                                    $foto_perfil = Publicacion::get_foto_perfil($_SESSION["usuario_id"]);
                                    ?>
                                    <img src="../<?php if ($foto_perfil !== null && $foto_perfil !== '') {
                                        echo $foto_perfil;
                                    } else {
                                        echo "../assets/assets-main/images/icons/user2.png";
                                    }
                                    ?>" alt="">
                                </div>
                                <div class="profile-card-name">
                                    <?php echo $_SESSION["nombre"] . " " . $_SESSION["apellido"] ?>
                                </div>
                                <div class="profile-card-status"></div>
                            </div>
                        </section><!--.box-typical-->

                        <section class="box-typical">
                            <?php
                            require_once "../../models/Usuario.php";
                            $usuarios = Usuario::get_usuarios($_SESSION["usuario_id"]);
                            ?>
                            <header class="box-typical-header-sm" id="amigos-header">
                                Amigos
                                &nbsp;
                                <a href="#" class="full-count">
                                    <?php echo count($usuarios) ?>
                                </a>
                            </header>
                            <div class="amigos-section">
                                <input type="text" id="searchInput" placeholder="Buscar amigos..." class="form-control">
                                <div class="friends-list">
                                    <?php
                                    foreach ($usuarios as $usuario) {
                                        ?>
                                        <article class="friends-list-item">
                                            <div class="user-card-row">
                                                <div class="tbl-row">
                                                    <div class="tbl-cell tbl-cell-photo">
                                                        <a
                                                            href="../Publicaciones/perfilUsuario.php?ID=<?php echo $usuario["id"] ?>">
                                                            <img src="../<?php if ($usuario["amigos_foto_perfil"] !== null && $usuario["amigos_foto_perfil"] !== '') {
                                                                echo $usuario["amigos_foto_perfil"];
                                                            } else {
                                                                echo "../assets/assets-main/images/icons/user2.png";
                                                            }
                                                            ?>" alt="">
                                                        </a>
                                                    </div>
                                                    <div class="tbl-cell">
                                                        <p class="user-card-row-name
                                                        <?php
                                                        if ($usuario['conectado']) {
                                                            echo "status-online";
                                                        }
                                                        ?>"><a
                                                                href="../Publicaciones/perfilUsuario.php?ID=<?php echo $usuario["id"] ?>">
                                                                <?php echo $usuario["usuario"] ?>
                                                            </a></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </article>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </section><!--.box-typical-->
                    </div><!--.col- -->

                    <div class="col-xl-9 col-lg-8">
                        <section class="tabs-section">
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
                                    require_once ("../Publicaciones/publicaciones.php");
                                    ?>
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
        <script src="../Publicaciones/publicaciones.js?v=<?php echo time(); ?>"></script>
        <script src="../Perfiles/perfil_profesional.js?v=<?php echo time(); ?>"></script>

    </body>

    </html>
    <?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>