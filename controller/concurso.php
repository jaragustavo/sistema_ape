<?php
/* TODO:Cadena de Conexion */
require_once("../config/conexion.php");
/* TODO:Clases Necesarias */
require_once("../models/Concurso.php");
$concurso = new Concurso();

require_once("../models/Usuario.php");
$usuario = new Usuario();

$key = "mi_key_secret";
$cipher = "aes-256-cbc";
$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));

date_default_timezone_set('America/Asuncion');

/*TODO: opciones del controlador Trámites*/
switch ($_GET["op"]) {
    case "listar_concursos":
        $datos = $concurso->get_tramites_gestionados_x_usuario($_SESSION["usuario_id"]);
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row["nombre_tramite"];
            $sub_array[] = date("d/m/Y", strtotime($row["fecha_solicitud"]));
            $sub_array[] = $row["estado_actual"];
            require ("../view/Formularios/avance.php");
            $sub_array[] = '<div class="progress-with-amount">
                                    <progress class="progress progress' . $color . ' progress-no-margin" value="' . $avance . '" max="100">' . $avance . '%</progress>
                                    <div class="progress-with-amount-number">' . $avance . '%</div>
                                </div>';


            $cifrado = openssl_encrypt($row["tramite_gestionado_id"], $cipher, $key, OPENSSL_RAW_DATA, $iv);
            $textoCifrado = base64_encode($iv . $cifrado);

            $boton_mostrado = "";
            $string = $row["permisos"];
            $permisos = explode("-", $string);
            foreach ($permisos as $permiso) {
                $btn = "";
                $icon = "";
                $color_icon = "";
                $title = "";
                if ($permiso == 'R') {
                    $btn = "btn-abrir-postulacion";
                    $icon = "eye-open";
                    $color_icon = "2986cc";
                    $title = "Ver postulacion";
                } elseif ($permiso == 'M') {
                    $btn = "btn-abrir-postulacion";
                    $icon = "edit";
                    $color_icon = "6aa84f";
                    $title = "Editar postulacion";
                } elseif ($permiso == 'D') {
                    $btn = "btn-delete-row";
                    $icon = "trash";
                    $color_icon = "e06666";
                    $title = "Eliminar postulacion";
                } elseif ($permiso == 'X') {
                    $btn = "btn-ver-observaciones";
                    $icon = "alert";
                    $color_icon = "e69138";
                    $title = "Ver observaciones";
                }
                $boton_mostrado = $boton_mostrado .
                    '<button title="' . $title . '" type="button" code="' . $row["tramite_id"] . '" style="padding: 0;border: none;background: none;" 
                data-ciphertext="' . $textoCifrado . '" id="' . $textoCifrado . '" class="' . $btn . '"><i  
                class="glyphicon glyphicon-' . $icon . '" style="color:#' . $color_icon . '; font-size:large; margin: 3px;" aria-hidden="true"></i></button>';
            }
            $sub_array[] = $boton_mostrado;
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

    case "comboTramites":

        $datos = $concurso->get_tramites_concursos();
        $html = "";
        $html .= "<option label='Seleccionar'></option>";
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $html .= "<option value='" . $row['tramite_id'] . "'>" . $row['tramite'] . "</option>";
            }
            echo $html;
        }
        break;

    /*=============================================
    GUARDAR DOCUMENTOS EN DIRECTORIOS CORRESPONDIENTES
    =============================================*/
    case "insertDocumentos":

        if (isset($_FILES['file'])) {
            $file = $_FILES['file'];
            $id = $_POST['id'];
            // Detalles del archivo
            if ($id > 0) {
                $datos = $concurso->get_datos_directorio($id, $_POST['tramite_code'], "archivosDisco");
                $data = array();
                foreach ($datos as $row) {
                    $tramite_nom = $row['tramite_nombre_corto'];
                    $doc_nom = $row['tipo_doc_nombre_corto'];
                }
                $ruta = "../docs/documents/" . $_SESSION["cedula"] . "/concursos" . "/" . $tramite_nom . "/" . $doc_nom . "/";

                /* TODO: Preguntamos si la ruta existe, en caso no exista la creamos */
                if (!file_exists($ruta)) {
                    mkdir($ruta, 0777, true);
                } 
                // else {
                //     // Get a list of all files in the directory
                //     $files = glob($ruta . '*');
                //     // Iterate through the list and delete each file
                //     foreach ($files as $file_existente) {
                //         if (is_file($file_existente)) {
                //             unlink($file_existente);
                //         }
                //     }
                // }
                $uploadedFile = $file['tmp_name'];
                $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $docNombre = date('mdHis') . "-" .
                    $doc_nom . "." . $fileExtension;
                $destino = $ruta . $docNombre;
                /* TODO: Movemos los archivos hacia la carpeta creada */
                if(move_uploaded_file($uploadedFile, $destino)){
                    error_log($destino);
                    echo $destino;
                }
                else{
                    echo "Error";
                }
                
            }
        } else {
            echo "No se ha enviado ninguna imagen.";
        }

        break;

    case "insertPostulacion":
        $fecha_hora = getLocalDateTime();
        try {
            $datos = array(
                // Para todas las tablas
                "usuario_id" => $_SESSION["usuario_id"],
                "area_id" => $_SESSION["area_id"],
                "fecha_crea" => $fecha_hora,
                "tramite_id" => $_POST["tramite_code"],
                "activo" => "true",

                // Datos para el trámite gestionado
                "estado_tramite_id" => $_POST["estado_tramite"],
                "forma_solicitud" => "definitiva",

                // Datos para documentos del trámite gestionado
                "tramite_code" => $_POST["tramite_code"],
                "cedula_user" => $_SESSION["cedula"],
                "documentos_adjuntos" => $_POST['documentos_adjuntos'],
                "estado_docs_tramite_id" => 2,
                "observacion" => $_POST['observacion']
            );

            $respuesta = $concurso->insertar_tramites($datos);

        } catch (Exception $e) {

            echo $respuesta;

        }
        echo $respuesta;
        break;
    case "updatePostulacion":
        $iv_dec = substr(base64_decode($_POST["idEncrypted"]), 0, openssl_cipher_iv_length($cipher));
        $cifradoSinIV = substr(base64_decode($_POST["idEncrypted"]), openssl_cipher_iv_length($cipher));
        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);

        date_default_timezone_set('America/Asuncion');

        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $fecha_hora = $fecha . ' ' . $hora;
        $datos = array(
            // Para todas las tablas
            "usuario_id" => $_SESSION["usuario_id"],
            "fecha_crea" => $fecha_hora,
            "activo" => "true",

            // Datos para el trámite gestionado
            "tramite_gestionado_id" => $decifrado,
            "estado_tramite_id" => $_POST["estado_tramite"],
            "forma_solicitud" => "definitiva",
            "observacion" => $_POST['observacion'],

            // Datos para documentos del trámite gestionado
            "tramite_code" => $_POST["tramite_code"],
            "cedula_user" => $_SESSION["cedula"],
            "documentos_adjuntos" => $_POST['documentos_adjuntos'],
            "estado_docs_tramite_id" => 2
        );

        echo $respuesta = $concurso->actualizar_tramites($datos);
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