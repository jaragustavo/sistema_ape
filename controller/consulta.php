<?php
/* TODO:Cadena de Conexion */
require_once("../config/conexion.php");
/* TODO:Clases Necesarias */
require_once("../models/Consulta.php");
$consulta = new Consulta();

require_once("../models/Usuario.php");
$usuario = new Usuario();

$key = "mi_key_secret";
$cipher = "aes-256-cbc";
$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
$docs_lista = array();
/*TODO: opciones del controlador Trámites*/
switch ($_GET["op"]) {
    case "cargarMovimientosTramite":
        $idTramiteGestionado = $_POST['idTramiteGestionado'];
        $idTramiteGestionado = str_replace('%27', '', $idTramiteGestionado);
        $iv_dec = substr(base64_decode($idTramiteGestionado), 0, openssl_cipher_iv_length($cipher));
        $cifradoSinIV = substr(base64_decode($idTramiteGestionado), openssl_cipher_iv_length($cipher));
        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);
        $datos = $consulta->get_movimientos_x_tramite($decifrado);

        $data = array();
        $cantidad_actual = 1;
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row["fecha_hora_mov"];
            $difHora = 0;
            error_log(count($datos) - $cantidad_actual);
            date_default_timezone_set('America/Asuncion');

            if (count($datos) - $cantidad_actual > 0) {
                $d1 = new DateTime($datos[$cantidad_actual]["fecha_solicitud"]);
                $d2 = new DateTime($row["fecha_solicitud"]);
                $difHora = $d1->diff($d2);
            } else {
                $d1 = new DateTime(date('Y-m-d H:i:s'));
                $d2 = new DateTime($row["fecha_solicitud"]);
                $difHora = $d1->diff($d2);
            }
            $sub_array[] = $row["area_asignada"];
            if ($row["usuario_asignado"] == "") {
                $row["usuario_asignado"] = "Solicitante";
            }
            $sub_array[] = $row["usuario_asignado"];
            $sub_array[] = $row["estado_mov"];
            $sub_array[] = $difHora->format('%d días, %h horas, %i minutos') . PHP_EOL;
            $data[] = $sub_array;
            $cantidad_actual++;
        }
        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        echo json_encode($results);
        break;

    case "cargarDatosTramiteGestionado":
        $idTramiteGestionado = $_POST['idEncrypted'];
        $idTramiteGestionado = str_replace('%27', '', $idTramiteGestionado);
        $iv_dec = substr(base64_decode($idTramiteGestionado), 0, openssl_cipher_iv_length($cipher));
        $cifradoSinIV = substr(base64_decode($idTramiteGestionado), openssl_cipher_iv_length($cipher));
        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);
        $datos = $consulta->get_info_tramite($decifrado);
        $data = array();
        foreach ($datos as $dato) {
            $data["usuario_solicitante"] = $dato["usuario_solicitante"];
            if($dato["usuario_asignado"] == ""){
                $dato["usuario_asignado"] = "No asignado";
            }
            $data["usuario_asignado"] = $dato["usuario_asignado"];
            $data["nombre_tramite"] = $dato["nombre_tramite"];
            $data["fecha_hora_crea"] = $dato["fecha_hora_crea"];
            $data["fecha_ultimo_mov"] = $dato["fecha_ultimo_mov"];
            $data["estado_actual"] = $dato["estado_actual"];
        }
        echo json_encode($data);
        break;
}
?>