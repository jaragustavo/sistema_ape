<?php
/* TODO:Cadena de Conexion */
require_once ("../config/conexion.php");
/* TODO:Clases Necesarias */
require_once ("../models/Administracion.php");
$administracion = new Administracion();

require_once ("../models/Usuario.php");
$usuario = new Usuario();

$key = "mi_key_secret";
$cipher = "aes-256-cbc";
$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));

/*TODO: opciones del controlador Reposos*/
switch ($_GET["op"]) {

    /* TODO: Listado de reposos segun usuario,formato json para Datatable JS */
    case "listar_tramites":
        $datos = $administracion->listar_tramites();
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row["tipo_tramite"];
            $sub_array[] = $row["nombre_tramite"];
            if ($row["cantidad_pasos"] == null) {
                $row["cantidad_pasos"] = 0;
            }
            $sub_array[] = $row["cantidad_pasos"];
            $cifrado = openssl_encrypt($row["id_tramite"], $cipher, $key, OPENSSL_RAW_DATA, $iv);
            $textoCifrado = base64_encode($iv . $cifrado);
            $sub_array[] = '<button title="Editar tramite" type="button" 
                style="padding: 0;border: none;background: none;" data-ciphertext="' . $textoCifrado . '" 
                id="' . $textoCifrado . '" class="btn-editar-tramite"><i  class="glyphicon glyphicon-edit" 
                style="color:#6aa84f; font-size:large; margin: 3px;" aria-hidden="true"></i></button>';
            $data[] = $sub_array;
        }

        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        echo json_encode($results);
        break;

    case "insertEstadoTramite":
        /*=============================================
        CREAR ESTADO TRÁMITE
        =============================================*/

        date_default_timezone_set('America/Asuncion');

        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $fecha_hora = $fecha . ' ' . $hora;
        $id_curso = str_replace(' ', '+', $_POST["tramite_id"]);
        $iv_dec = substr(base64_decode($id_curso), 0, openssl_cipher_iv_length($cipher));
        $cifradoSinIV = substr(base64_decode($id_curso), openssl_cipher_iv_length($cipher));
        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);
        try {
            $datos = array(
                // Para todas las tablas
                "usuario_id" => $_SESSION["usuario_id"],
                "fecha_crea" => $fecha_hora,
                "tramite_id" => $decifrado,
                "activo" => "true",

                // Datos para el estado del trámite 
                "estado_id" => $_POST["estado"],
                "paso_estado" => $_POST["paso_estado"],
                "duracion_estimada" => $_POST["duracion_estimada"]
            );

            $respuesta = $administracion->insert_estados_tramites($datos);

        } catch (Exception $e) {

            echo $respuesta;

        }
        echo $respuesta;

        break;

    /* TODO: Mostrar informacion del Trámite en formato JSON para la vista */
    case "mostrarTramite":

        $iv_dec = substr(base64_decode($_POST["tramite_id"]), 0, openssl_cipher_iv_length($cipher));
        $cifradoSinIV = substr(base64_decode($_POST["tramite_id"]), openssl_cipher_iv_length($cipher));
        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);

        $datos = $administracion->mostrar($decifrado);
        if (is_array($datos) == true and count($datos) > 0) {

            $output["nombre_tramite"] = $datos["nombre_tramite"];
            $output["tipo_tramite"] = $datos["tipo_tramite"];

            echo json_encode($output);
        }
        break;

    case "mostrarEstadoTramite":

        $iv_dec = substr(base64_decode($_POST["estado_id"]), 0, openssl_cipher_iv_length($cipher));
        $cifradoSinIV = substr(base64_decode($_POST["estado_id"]), openssl_cipher_iv_length($cipher));
        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);

        $datos = $administracion->mostrar_estado_tramite($decifrado);
        if (is_array($datos)) {
            $output = array();

            foreach ($datos as $row) {
                $item = array();

                // Iterate through each key-value pair in $row
                foreach ($row as $key => $value) {
                    // Add each key-value pair to the $item array
                    $item[$key] = $value;
                }

                // Append each item to the output array
                $output[] = $item;
            }

            echo json_encode($output);
        }
        break;
    case "updateEstadoTramite":
        /*=============================================
        CREAR ESTADO TRÁMITE
        =============================================*/

        date_default_timezone_set('America/Asuncion');

        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $fecha_hora = $fecha . ' ' . $hora;
        try {
            $datos = array(
                // Para todas las tablas
                "usuario_id" => $_SESSION["usuario_id"],
                "fecha_crea" => $fecha_hora,
                "activo" => "true",

                // Datos para el estado del trámite 
                "estado_tramite_id" => $_POST["estado_tramite_id"],
                "estado_id" => $_POST["estado"],
                "paso_estado" => $_POST["paso_estado"],
                "duracion_estimada" => $_POST["duracion_estimada"]
            );

            $respuesta = $administracion->update_estados_tramites($datos);

        } catch (Exception $e) {

            echo $respuesta;

        }
        echo $respuesta;

        break;
    
    case "comboEstados":
        $iv_dec = substr(base64_decode($_POST["idEncrypted"]), 0, openssl_cipher_iv_length($cipher));
        $cifradoSinIV = substr(base64_decode($_POST["idEncrypted"]), openssl_cipher_iv_length($cipher));
        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);

        $datos = $administracion->get_estados($decifrado);
        $html = "";
        $html .= "<option label='Seleccionar'></option>";
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $html .= "<option value='" . $row['estado_id'] . "'>" . $row['estado'] . "</option>";
            }
            echo $html;
        }
        break;

    case "agregarComboEstados":
        $iv_dec = substr(base64_decode($_POST["idEncrypted"]), 0, openssl_cipher_iv_length($cipher));
        $cifradoSinIV = substr(base64_decode($_POST["idEncrypted"]), openssl_cipher_iv_length($cipher));
        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);

        $datos = $administracion->get_estado_seleccionado($decifrado);
        if (is_array($datos) == true and count($datos) > 0) {

            $output["nombre_estado"] = $datos[0]["nombre_estado"];
            $output["estado_id"] = $datos[0]["estado_id"];
            echo json_encode($output);
        }
        break;
    case "deleteEstadoTramite":
        $iv_dec = substr(base64_decode($_POST["ciphertext"]), 0, openssl_cipher_iv_length($cipher));
        $cifradoSinIV = substr(base64_decode($_POST["ciphertext"]), openssl_cipher_iv_length($cipher));
        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);
        date_default_timezone_set('America/Asuncion');
        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $fecha_hora = $fecha . ' ' . $hora;

        $respuesta = $administracion->delete_estado_tramite($decifrado, $_SESSION["usuario_id"], $fecha_hora);
        echo $respuesta;
        break;

    case "igualarEstadosTipoTramite":
        $iv_dec = substr(base64_decode($_POST["tramite_id"]), 0, openssl_cipher_iv_length($cipher));
        $cifradoSinIV = substr(base64_decode($_POST["tramite_id"]), openssl_cipher_iv_length($cipher));
        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);
        date_default_timezone_set('America/Asuncion');
        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $fecha_hora = $fecha . ' ' . $hora;

        $respuesta = $administracion->igualar_estados_tipo_tramite($decifrado, $_POST['tipo_tramite'], $_SESSION["usuario_id"], $fecha_hora);
        echo $respuesta;
        break;

}
?>