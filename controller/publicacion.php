<?php
/*TODO: llamada a las clases necesarias */
require_once ("../config/conexion.php");
require_once ("../models/Publicacion.php");
$publicacion = new Publicacion();

$key = "mi_key_secret";
$cipher = "aes-256-cbc";
$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));

/*TODO: opciones del controlador */
switch ($_GET["op"]) {
    case "subirArchivo":
        if (isset($_FILES['file'])) {
            $file = $_FILES['file'];
            // Detalles del archivo
            if (isset($file)) {
 		$root = $_SERVER["DOCUMENT_ROOT"];

                 $ruta = $root."/sistema_ape/docs/documents/" . $_SESSION["cedula"] . "/" . "publicaciones/" . $_POST["folder_name"] . "/";
                /* TODO: Preguntamos si la ruta existe, en caso no exista la creamos */
                if (!file_exists($ruta)) {
 //error_log( exec('whoami'));
                  

                   mkdir($ruta, 0755, true);

                }
                $uploadedFile = $file['tmp_name'];
                $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
                // Get current Unix timestamp with microseconds
                $microtime = microtime(true);
                // Extract seconds and microseconds
                $seconds = floor($microtime);
                $microseconds = ($microtime - $seconds) * 1000000;
                $docNombre = date('His', $seconds) . sprintf('%03d', $microseconds) . "." . $fileExtension;
                $destino = $ruta . $docNombre;
                /* TODO: Movemos los archivos hacia la carpeta creada */
                move_uploaded_file($uploadedFile, $destino);
            }
        } else {
            echo "No se ha enviado ninguna imagen.";
        }
        break;
    case "subirPublicacion":
        /*=============================================
        CREAR PUBLICACIÓN
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

                // Datos para la nueva publicación
                "publico" => $_POST["publico"],
                "forma_solicitud" => "definitiva",
                "texto_publicacion" => $_POST["texto_publicacion"],

                // Datos para documentos de la publicación
                "folder_name" => $_POST["folder_name"],
                "cedula_user" => $_SESSION["cedula"]
            );

            $respuesta = $publicacion->insertar_publicacion($datos);

        } catch (Exception $e) {

            echo $e->getMessage();

        }

        echo $respuesta ? "Publicado exitosamente" : "No se pudo publicar";
        break;

    case "deletePublicacion":
        $fecha_hora = getLocalDateTime();
        echo $publicacion->delete_publicacion($fecha_hora, $_SESSION['usuario_id'], $_POST['publicacion_id']);
        break;

    case "guardarFotoPerfil":

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {

            $doc1 = $_FILES['file']['tmp_name'];

            if ($doc1 != "") {

              
                $ruta = "../docs/documents/" . $_SESSION["cedula"] . "/" . "foto_perfil/";
                
                // Crear el directorio si no existe
                if (!file_exists($ruta)) {
                    mkdir($ruta, 0777, true);
                } else {
                    // Obtener una lista de todos los archivos en el directorio
                    $files = glob($ruta . '*');
                    // Iterar a través de la lista y eliminar cada archivo
                    foreach ($files as $file_existente) {
                        if (is_file($file_existente)) {
                            error_log($file_existente);
                            unlink($file_existente);
                        }
                    }
                }
            
                // Obtener la extensión del archivo y generar un nuevo nombre de archivo
                $fileExtension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                $docNombre = date('YmdHis') . "." . $fileExtension;
                $destino = $ruta . $docNombre;
                

                 // Verificar que la ruta destino es correcta
    
                // Mover el archivo subido al destino
                if (move_uploaded_file($doc1, $destino)) {
                    $fecha_hora = getLocalDateTime();
                    $publicacion->update_foto_perfil($destino, $_SESSION['usuario_id'], $fecha_hora);
                    // Responder con la nueva ruta de la imagen en formato JSON
                  
                    echo json_encode(["status" => "ok", "new_image_path" => '../'.$destino]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Error al mover el archivo subido."]);
                }
            } else {
                echo json_encode(["status" => "error", "message" => "No se subió ningún archivo."]);
            }

        }
        break;

    case "traerMisPublicaciones":
        $datos = $publicacion->get_publicaciones_x_usuario($_SESSION["usuario_id"]);
        $data = array();
        foreach ($datos as $row) {
            $sub_array["publicacion_id"] = $row["publicacion_id"];
            $sub_array["texto"] = $row["texto"];
            $sub_array["fecha_publicacion"] = $row["fecha_publicacion"];
            $sub_array["publico"] = $row["publico"];
            $data[] = $sub_array;
        }
        echo json_encode($data);
        break;

    // case "usuariosSistema":
    //     // $datos = $publicacion->get_usuarios($_POST["usuario_buscado"]);
    //     $datos = $publicacion->get_usuarios();
    //     $data = array();
    //     foreach ($datos as $row) {
    //         $sub_array["usuario_buscado_id"] = $row["usuario_buscado_id"];
    //         $sub_array["usuario_nombre"] = $row["usuario_nombre"];
    //         $data[] = $sub_array;
    //     }
    //     echo json_encode($data);
    //     break;

    case "usuariosSistema":
        $datos = $publicacion->get_usuarios();
        $data = array();
        foreach ($datos as $row) {
            $data[] = array(
                'id' => $row["usuario_buscado_id"],
                'name' => $row["usuario_nombre"],
                'foto_perfil' => $row["foto_perfil"]
            );
        }
        echo json_encode($data);
        break;    

    case "likePublicacion":
        $fecha_hora = getLocalDateTime();

        $datos = $publicacion->update_like_post($_POST["publicacion_id"], $fecha_hora, $_SESSION["usuario_id"]);
        echo $datos;
        break;

    case "seguirUsuario":
        date_default_timezone_set('America/Asuncion');

        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $fecha_hora = $fecha . ' ' . $hora;
        $datos = $publicacion->follow_user($_POST["usuario_ci"], $fecha_hora, $_SESSION["usuario_id"]);
        echo $datos;
        break;

    case "crearPerfil":
        $fecha_hora = getLocalDateTime();

        $datos = $publicacion->crearPerfil($fecha_hora, $_SESSION["usuario_id"]);
        echo $datos;
        break;

    case "actualizarPerfil":
        $fecha_hora = getLocalDateTime();
        $datos = array(
            'fecha_hora' => $fecha_hora,
            'usuario_id' => $_SESSION["usuario_id"],
            'nombre_perfil' => $_POST['nombre_perfil'],
            'acerca_de_mi' => $_POST['acerca_de_mi'],
            'ciudad_trabajo' => $_POST['ciudad_trabajo'],
            'profesion_principal' => $_POST['profesion_principal'],
            'educacion' => $_POST['educacion'],
            'lugar_trabajo' => $_POST['lugar_trabajo']

        );
        $resultado = $publicacion->update_perfil($datos);
        echo $resultado;
        break;

    case "comboCiudades":
        $datos = $publicacion->get_ciudades();
        $html = "";
        $html .= "<option label='Seleccionar'></option>";
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $html .= "<option value='" . $row['ciudad_id'] . "'>" . $row['ciudad'] . "</option>";
            }
            echo $html;
        }
        break;
    case "comboProfesiones":
        $datos = $publicacion->get_profesiones();
        $html = "";
        $html .= "<option label='Seleccionar'></option>";
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $html .= "<option value='" . $row['profesion_id'] . "'>" . $row['profesion'] . "</option>";
            }
            echo $html;
        }
        break;

    case "comboEstablecimientos":
        $datos = $publicacion->get_establecimientos();
        $html = "";
        $html .= "<option label='Seleccionar'></option>";
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $html .= "<option value='" . $row['establecimiento_id'] . "'>" . $row['nombre_establecimiento'] . "</option>";
            }
            echo $html;
        }
        break;

    case "datosPerfil":
        $datos = $publicacion->datos_perfil($_SESSION["usuario_id"]);
        echo json_encode($datos);
        break;
    case "datosPerfilVisitado":
        $datos = $publicacion->datos_perfil($_POST["usuario_visitado_id"]);
        echo json_encode($datos);
        break;

    case "comentarPosteo":
        $fecha_hora = getLocalDateTime();
        $datos = $publicacion->insert_comentario($_POST["publicacion_id"], $_POST["nuevo_comentario"], $fecha_hora, $_SESSION["usuario_id"]);
        echo $datos;
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