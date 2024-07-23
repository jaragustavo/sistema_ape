<?php
    /*TODO: llamada a las clases necesarias */
    require_once("../config/conexion.php");
    require_once("../models/Notificacion.php");
    $notificacion = new Notificacion();

    $key="mi_key_secret";
    $cipher="aes-256-cbc";
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));

    /*TODO: opciones del controlador */
    switch($_GET["op"]){
        case "abrirNotificacion":
            $resultado = $notificacion->update_leido_notificaciones($_SESSION["usuario_id"]);
            echo $resultado ? "ok":"error";
            break;
    }
?>