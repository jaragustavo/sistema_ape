<?php
/*TODO: llamada a las clases necesarias */
require_once ("../config/conexion.php");
require_once ("../models/Mensaje.php");
$mensaje = new Mensaje();

$key = "mi_key_secret";
$cipher = "aes-256-cbc";
$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
$mensajes_nuevos = 0;
$active = "";
/*TODO: opciones del controlador */
switch ($_GET["op"]) {

    case "cargarChat":
        $conversaciones = $mensaje->get_conversacion_x_usuario($_POST["chat_id"], $_SESSION["usuario_id"]);
        $data = array();
        if (is_array($conversaciones) == true and count($conversaciones) > 0) {

            foreach ($conversaciones as $row) {
                $sub_array = array();
                $sub_array["mensaje_id"] = $row["mensaje_id"];
                $sub_array["mensaje"] = $row["mensaje"];
                $sub_array["remitente_id"] = $row["remitente_id"];
                $sub_array["usuario_id"] = $_SESSION["usuario_id"];
                $sub_array["nombre_remitente"] = $row["nombre_remitente"];
                $sub_array["nombre_destinatario"] = $row["nombre_destinatario"];
                $sub_array["ind_estado"] = $row["ind_estado"];
                $sub_array["hora"] = $row["hora"];
                $sub_array["fecha"] = $row["fecha"];
                $sub_array["cedula_usuario"] = $row["cedula_usuario"];
                $sub_array["cedula_chat"] = $row["cedula_chat"];
                $sub_array["foto_perfil"] = $row["foto_perfil"];
                $sub_array["conectado"] = $row["conectado"];
                $sub_array["fecha_conexion"] = $row["fecha_conexion"];
                $data[] = $sub_array;
            }
            echo json_encode($data);
        }
        break;

    case "user_info":
        $user_id = decodeId($_POST["match_chat_id"]);
        $user_info = $mensaje->get_user_info($user_id);
        $data = [];
        $data["user_id"] = $user_id;
        $data["user_info"] = $user_info;
        echo json_encode($data);
        break;

    case "ultimaConexion":
        $datos_conexion = $mensaje->get_datos_conexion($_POST["chat_id"]);
        echo json_encode($datos_conexion);
        break;

    /* TODO:Actualizar estado segun not_id */
    case "actualizarEstado":
        $mensaje->update_mensaje_estado($_SESSION["usuario_id"], $_POST["mensaje_id"], $_POST["nuevo_estado"]);
        break;

    case "usuariosSistema":
        $datos = $mensaje->get_usuarios($_SESSION["usuario_id"]);
        $data = array();
        foreach ($datos as $row) {
            $data[] = array(
                'id' => $row["usuario_buscado_id"],
                'name' => $row["usuario_nombre"],
                'foto_perfil' => $row["foto_perfil"],
                'conectado' => $row["conectado"],
                'fecha_conexion' => $row["fecha_conexion"]
            );
        }
        echo json_encode($data);
        break;

    case "enviarMensaje":
        date_default_timezone_set('America/Asuncion');

        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $fecha_hora = $fecha . ' ' . $hora;
        $nuevo_mensaje = $mensaje->enviar_mensaje($_SESSION["usuario_id"], $_POST["destinatario_id"], $_POST["nuevo_mensaje"], $fecha_hora);
        $data = array();
        if (is_array($nuevo_mensaje) == true and count($nuevo_mensaje) > 0) {
            foreach ($nuevo_mensaje as $row) {
                $data["mensaje_id"] = $row["mensaje_id"];
                $data["mensaje"] = $row["mensaje"];
                $data["remitente_id"] = $row["remitente_id"];
                $data["usuario_id"] = $_SESSION["usuario_id"];
                $data["nombre_remitente"] = $row["nombre_remitente"];
                $data["nombre_destinatario"] = $row["nombre_destinatario"];
                $data["ind_estado"] = $row["ind_estado"];
                $data["hora"] = $row["hora"];
                $data["fecha"] = $row["fecha"];
                $data["cedula_usuario"] = $row["cedula_usuario"];
                $data["cedula_chat"] = $row["cedula_chat"];
                $data["foto_perfil"] = $row["foto_perfil"];
            }
            echo json_encode($data);
        }
        break;
}
function decodeId($encrypted)
{
    $key = "mi_key_secret";
    $cipher = "aes-256-cbc";

    $iv_dec = substr(base64_decode($encrypted), 0, openssl_cipher_iv_length($cipher));
    $cifradoSinIV = substr(base64_decode($encrypted), openssl_cipher_iv_length($cipher));
    return openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);
}

function encryptId($id)
{
    $key = "mi_key_secret";
    $cipher = "aes-256-cbc";
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));

    $cifrado = openssl_encrypt($id, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    return base64_encode($iv . $cifrado);
}

function getLocalDateTime()
{
    date_default_timezone_set('America/Asuncion');

    $fecha = date('Y-m-d');
    $hora = date('H:i:s');
    return $fecha . ' ' . $hora;
}
?>