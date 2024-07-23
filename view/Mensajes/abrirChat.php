<?php
require_once ("../../config/conexion.php");
if (isset($_SESSION["usuario_id"])) {
    require_once ("../../models/Mensaje.php");
    $mensaje = new Mensaje();

    $key = "mi_key_secret";
    $cipher = "aes-256-cbc";
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
    $mensajes_nuevos = 0;
    $active = "";
    $chats = $mensaje->get_chats_x_usuario($_SESSION["usuario_id"]);
    $mensajes_nuevos = count($chats);

    ?>

    <!doctype html>
    <html lang="es" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
        data-sidebar-image="none">

    <head>
        <?php require_once ("../MainHead/head.php"); ?>

        <link rel="stylesheet" href="../../public/css/separate/pages/chat.css">
        <link rel="stylesheet" href="../../public/css/separate/pages/messenger.css">
        <link rel="stylesheet" href="../../public/css/separate/vendor/select2.min.css">
        <link rel="stylesheet" href="../../public/css/separate/vendor/typeahead.min.css">
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

            .typeahead-suggestion {
                display: flex;
                align-items: center;
            }

            .typeahead-suggestion img.profile-picture {
                width: 30px;
                /* Adjust size as needed */
                height: 30px;
                /* Adjust size as needed */
                border-radius: 50%;
                margin-right: 10px;
                /* Adjust spacing as needed */
            }
        </style>
        <title>APE | Mensajería</title>
    </head>

    <body class="with-side-menu">
        <div class="mobile-menu-left-overlay"></div>
        <?php require_once ("../MainHeader/header.php"); ?>
        <?php require_once ("../MainNav/nav.php"); ?>

        <div class="page-content">
            <div class="container-fluid messenger">

                <div class="box-typical chat-container">
                    <section class="chat-list">
                        <div class="chat-list-search chat-list-settings-header">
                            <div class="typeahead-container">
                                <div class="typeahead-field">
                                    <span class="typeahead-query">
                                        <input id="search-friends" class="form-control" name="q" type="search"
                                            autocomplete="off" placeholder="Buscar amigos...">
                                    </span>
                                </div>
                            </div>
                        </div><!--.chat-list-search-->
                        <div class="chat-list-in scrollable-block" id="chat-list-item">
                            <?php
                            foreach ($chats as $row) {
                                ?>
                                <div class="chat-list-item
                                <?php
                                if ($row['conectado']) {
                                    echo "online";
                                }
                                ?>
                                " onclick="cargarChat(<?php echo $row['chat_id'] ?>)">
                                    <div class="chat-list-item-photo">
                                        <img src="../<?php if ($row['foto_perfil'] == null || $row['foto_perfil'] == '') {
                                            echo "../assets/assets-main/images/icons/user2.png";
                                        } else {
                                            echo $row['foto_perfil'];
                                        }
                                        ?>">
                                    </div>
                                    <div class="chat-list-item-header">
                                        <div class="chat-list-item-name">
                                            <span class="name"><?php echo $row["nombre_chat"] ?></span>
                                        </div>
                                        <div class="chat-list-item-date"><?php echo $row["hora"] ?></div>
                                    </div>
                                    <div class="chat-list-item-cont">
                                        <div class="chat-list-item-txt"><?php echo $row["mensaje"] ?></div>
                                        <?php if ($row["cant_mensajes_nuevos_x_chat"] > 0) { ?>
                                            <div class="chat-list-item-count"><?php echo $row["cant_mensajes_nuevos_x_chat"] ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                            <!-- <div class="chat-list-item selected">
                        Cuando selecciona el chat
                    </div> -->

                        </div><!--.chat-list-in-->
                    </section><!--.chat-list-->

                    <section class="chat-area">
                        <div class="chat-area-in">
                            <div class="chat-area-header">
                                <div class="chat-list-item" style="text-align: center;" id="header_chat">
                                    <div class="chat-list-item-name">
                                        <span class="name_chat"></span>
                                    </div>
                                    <div class="chat-list-item-txt writing" id="ultima_conexion"></div>
                                </div>
                            </div><!--.chat-area-header-->

                            <!-- Área en donde se carga el chat por persona -->
                            <div class="chat-dialog-area" id="body_chat" style="overflow:auto; max-width:auto; z-index: 10;">

                            <div class="messenger-dialog-area" id="listado_mensajes">
                                </div>
                            </div>

                            <div class="chat-area-bottom" id="escribir_mensaje">
                                <form class="write-message">
                                    <div class="form-group">
                                        <textarea rows="1" class="form-control" placeholder="Type a message"
                                            id="nuevo_mensaje"></textarea>
                                        <div class="dropdown dropdown-typical dropup attach">
                                            <a class="dropdown-toggle dropdown-toggle-txt" id="dd-chat-attach"
                                                data-target="#" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                <span class="font-icon fa fa-file-o"></span>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dd-chat-attach">
                                                <a class="dropdown-item" href="#"><i
                                                        class="font-icon font-icon-cam-photo"></i>Photo</a>
                                                <a class="dropdown-item" href="#"><i
                                                        class="font-icon font-icon-cam-video"></i>Video</a>
                                                <a class="dropdown-item" href="#"><i
                                                        class="font-icon font-icon-sound"></i>Audio</a>
                                                <a class="dropdown-item" href="#"><i
                                                        class="font-icon font-icon-page"></i>Document</a>
                                                <a class="dropdown-item" href="#"><i
                                                        class="font-icon font-icon-earth"></i>Map</a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div><!--.chat-area-bottom-->
                        </div><!--.chat-area-in-->
                    </section><!--.chat-area-->
                </div><!--.chat-container-->

            </div><!--.container-fluid-->
        </div><!--.page-content-->

        <!-- <script type="text/javascript" src="mensajes.js"></script> -->
        <?php require_once ("../MainJs/js.php"); ?>
        <?php require_once ("../html/footer.php"); ?>
        <script src="../../public/js/lib/select2/select2.full.min.js"></script>
        <script src="../../public/js/lib/typeahead/typeahead-init.js"></script>
        <script type="text/javascript" src="mensajes.js?v=<?php echo time(); ?>"></script>
        <script src="../../public/js/lib/typeahead/jquery.typeahead.min.js"></script>
    </body>

    </html>
    <?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>