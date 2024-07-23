<?php
/* TODO:Cadena de Conexion */
require_once ("../config/conexion.php");
/* TODO:Clases Necesarias */
require_once ("../models/Movimiento.php");
$movimiento = new Movimiento();

require_once ("../models/Usuario.php");
$usuario = new Usuario();

$key = "mi_key_secret";
$cipher = "aes-256-cbc";
$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));

date_default_timezone_set('America/Asuncion');

/*TODO: opciones del controlador Trámites*/
switch ($_GET["op"]) {
    case "asignarmeTramites":
        if (isset($_POST['selectedRows'])) {
            $tramites_autoasignados = $_POST['selectedRows'];

            $fecha = date('Y-m-d');
            $hora = date('H:i:s');
            $fecha_hora = $fecha . ' ' . $hora;
            $resultado = $movimiento->update_usuario_asignado_tramite($tramites_autoasignados, $fecha_hora, $_SESSION["usuario_id"], $_SESSION["area_id"]);
            echo $resultado;
        }
        break;



    case "enviarObservaciones":
        // Recibir datos del formulario
        $estadosDocs = json_decode($_POST['estadosDocs'], true);
        $idTramiteGestionado = $_POST['idTramiteGestionado'];
        $observacion = $_POST['observacion'];
        $estadoTramite = $_POST['estadoTramiteGestionado'];
        $tramiteJsonRequisito = json_decode($_POST['tramite_json_requisito'], true); // Decodificar tramite_json_requisito

        // Decodificar el idTramiteGestionado
        $idTramiteGestionado = str_replace('%27', '', $idTramiteGestionado);
        $iv_dec = substr(base64_decode($idTramiteGestionado), 0, openssl_cipher_iv_length($cipher));
        $cifradoSinIV = substr(base64_decode($idTramiteGestionado), openssl_cipher_iv_length($cipher));
        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);

        // Obtener fecha y hora actual
        date_default_timezone_set('America/Asuncion');
        $fecha_hora = date('Y-m-d H:i:s');

        // Llamar al método para actualizar estados de documentos
        $resultado = $movimiento->update_estados_documentos($estadosDocs, $decifrado, $observacion, $fecha_hora, $_SESSION["usuario_id"], $estadoTramite, $tramiteJsonRequisito);

        if ($resultado === "ok") {
            echo "ok";
        } else {
            echo $resultado; // Puedes devolver un mensaje de error específico si lo deseas
        }
        break;

    case "aprobarSolicitud":
        $estadoDoc = $_POST["estado_doc"];
        $estadoTramite = $_POST["estado_tramite"];
        error_log($_POST["idTramiteGestionado"]);
        $iv_dec = substr(base64_decode($_POST["idTramiteGestionado"]), 0, openssl_cipher_iv_length($cipher));
        $cifradoSinIV = substr(base64_decode($_POST["idTramiteGestionado"]), openssl_cipher_iv_length($cipher));
        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);
        date_default_timezone_set('America/Asuncion');
        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $fecha_hora = $fecha . ' ' . $hora;
        $resultado = $movimiento->update_tramite_aprobado($estadoDoc, $estadoTramite, $decifrado, $fecha_hora, $_SESSION["usuario_id"]);
        break;

    case "cargarObs":
        $iv_dec = substr(base64_decode($_POST["tramite_gestionado_id"]), 0, openssl_cipher_iv_length($cipher));
        $cifradoSinIV = substr(base64_decode($_POST["tramite_gestionado_id"]), openssl_cipher_iv_length($cipher));
        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);

        $datos = $movimiento->get_observacion($decifrado);
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $item = array(
                    "observacion" => $row["observacion_evaluador"],
                    "observacion_inscripcion" => $row["observacion_inscripcion"]
                );

                // Append each item to the output array
                $item;
            }
            echo json_encode($item);
        }
        break;

    case "cargarTitulo":
        $iv_dec = substr(base64_decode($_POST["idTramiteGestionado"]), 0, openssl_cipher_iv_length($cipher));
        $cifradoSinIV = substr(base64_decode($_POST["idTramiteGestionado"]), openssl_cipher_iv_length($cipher));
        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);
        $code = $movimiento->get_tipo_tramite($decifrado);
        echo $code["nombre_tramite"];
        break;

    case "aprobarInscripciones":
        if (isset($_POST['selectedRows'])) {
            $tramites_autoasignados = $_POST['selectedRows'];

            $fecha = date('Y-m-d');
            $hora = date('H:i:s');
            $fecha_hora = $fecha . ' ' . $hora;
            $resultado = $movimiento->aprobar_inscripciones($tramites_autoasignados, $fecha_hora, $_SESSION["usuario_id"], $_SESSION["area_id"], $_POST["estado_tramite"]);
            echo $resultado;
        }
        break;

    case "aprobarInscripcion":
        $tramite_gestionado_id = decodeId($_POST['idEncrypted']);

        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $fecha_hora = $fecha . ' ' . $hora;
        $resultado = $movimiento->aprobar_inscripcion($tramite_gestionado_id, $fecha_hora, $_SESSION["usuario_id"], $_SESSION["area_id"], $_POST["estado_tramite"]);
        echo $resultado;
        break;

    case "rechazarInscripcion":
        $tramite_gestionado_id = decodeId($_POST['idEncrypted']);
        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $fecha_hora = $fecha . ' ' . $hora;
        $resultado = $movimiento->rechazar_inscripcion($tramite_gestionado_id, $fecha_hora, $_SESSION["usuario_id"], $_SESSION["area_id"], $_POST["estado_tramite"]);
        echo $resultado;

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