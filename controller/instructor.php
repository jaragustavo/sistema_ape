<?php
/* TODO:Cadena de Conexion */
require_once ("../config/conexion.php");
require_once ("../models/Certificacion.php");
$certificacion = new Certificacion();
require_once ("../models/Instructor.php");
$instructor = new Instructor();

$key = "mi_key_secret";
$cipher = "aes-256-cbc";
$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));

switch ($_GET["op"]) {
    /*=============================================
        CURSOS
        =============================================*/


    case "insertPortadaCurso":
        $doc1 = $_FILES['file']['tmp_name'];
        if ($doc1 != "") {
            $curso_id = decodeId($_POST['curso_id']);
            $ruta = "../docs/documents/" . $_SESSION["cedula"] . "/" . "cursos_instructor/curso" .
                $curso_id . '/';

            /* TODO: Preguntamos si la ruta existe, en caso no exista la creamos */
            if (!file_exists($ruta)) {
                mkdir($ruta, 0777, true);
            }
            $fileExtension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            $docNombre = date('YmdHi') . "." . $fileExtension;
            $destino = $ruta . $docNombre;
            /* TODO: Movemos los archivos hacia la carpeta creada */
            move_uploaded_file($doc1, $destino);
            echo $destino;
        }
        break;

    case "insertCurso":

        date_default_timezone_set('America/Asuncion');

        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $fecha_hora = $fecha . ' ' . $hora;
        $datos = array(
            "fecha_hora" => $fecha_hora,
            "activo" => "true",
            "nombre_curso" => $_POST["nombre_curso"],
            "descripcion" => $_POST["descripcion"],
            "aprendizaje" => $_POST['aprendizaje'],
            "categoria_curso" => $_POST['categoria_curso'],
            "imagen_portada" => $_POST['imagen_portada'],
            "tramite_id" => $_POST['tipo_tramite']
        );

        $resultado = $instructor->insert_datos_curso($_SESSION["cedula"], $_SESSION["usuario_id"], $datos);
        echo $resultado;
        break;

    case "listar_cursos_x_instructor":
        $datos = $instructor->get_cursos_x_instructor($_SESSION["cedula"]);
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row["nombre_categoria"];
            $sub_array[] = $row["capacitacion"];
            $sub_array[] = $row["nombre_curso"];
            $sub_array[] = $row["fecha"];
            $cifrado = openssl_encrypt($row["curso_id"], $cipher, $key, OPENSSL_RAW_DATA, $iv);
            $textoCifrado = base64_encode($iv . $cifrado);
            $sub_array[] = '<button title="Editar capacitacion" type="button" style="padding: 0;border: none;background: none;" data-ciphertext="' . $textoCifrado . '" id="' . $textoCifrado . '" class="btn-editar-curso"><i  class="glyphicon glyphicon-edit" style="color:#6aa84f; font-size:large; margin: 3px;" aria-hidden="true"></i></button>' .
                '<button title="Admnistrar secciones" type="button" style="padding: 0;border: none;background: none;" data-ciphertext="' . $textoCifrado . '" id="' . $textoCifrado . '" class="btn-sections"><i class="glyphicon glyphicon-book" style="color:#9989c9; font-size:large; margin: 3px;" aria-hidden="true"></i></button>' .
                '<button title="Eliminar capacitacion" type="button" style="padding: 0;border: none;background: none;" data-ciphertext="' . $textoCifrado . '" id="' . $textoCifrado . '" class="btn-delete-curso"><i class="glyphicon glyphicon-trash" style="color:#e06666; font-size:large; margin: 3px;" aria-hidden="true"></i></button>';

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

    /* TODO: Mostrar informacion de la solicitud de inscripción del curso en formato JSON para la vista */
    case "mostrarCurso":

        $iv_dec = substr(base64_decode($_POST["curso_id"]), 0, openssl_cipher_iv_length($cipher));
        $cifradoSinIV = substr(base64_decode($_POST["curso_id"]), openssl_cipher_iv_length($cipher));
        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);

        $datos = $instructor->mostrar_curso($decifrado, $_SESSION["usuario_id"]);
        if (is_array($datos) && count($datos) > 0) {
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

    case "updateCurso":

        $iv_dec = substr(base64_decode($_POST["curso_id"]), 0, openssl_cipher_iv_length($cipher));
        $cifradoSinIV = substr(base64_decode($_POST["curso_id"]), openssl_cipher_iv_length($cipher));
        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);

        date_default_timezone_set('America/Asuncion');

        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $fecha_hora = $fecha . ' ' . $hora;
        $datos = array(
            "fecha_hora" => $fecha_hora,
            "activo" => "true",
            "nombre_curso" => $_POST["nombre_curso"],
            "curso_id" => $decifrado,
            "descripcion" => $_POST["descripcion"],
            "aprendizaje" => $_POST['aprendizaje'],
            "categoria_curso" => $_POST['categoria_curso'],
            "certificacion" => 'true',
            "imagen_portada" => $_POST["imagen_portada"]

        );
        $resultado = $instructor->update_datos_curso($decifrado, $_SESSION["usuario_id"], $datos);
        echo $resultado;
        break;

    case "comboCategoriasCursos":
        $datos = $instructor->get_opciones_categorias();
        $html = "";
        $html .= "<option label='Seleccionar'></option>";
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $html .= "<option value='" . $row['categoria_id'] . "'>" . $row['categoria'] . "</option>";
            }
            echo $html;
        }
        break;

    case "deleteCurso":
        $iv_dec = substr(base64_decode($_POST["ciphertext"]), 0, openssl_cipher_iv_length($cipher));
        $cifradoSinIV = substr(base64_decode($_POST["ciphertext"]), openssl_cipher_iv_length($cipher));
        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);
        date_default_timezone_set('America/Asuncion');
        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $fecha_hora = $fecha . ' ' . $hora;

        $respuesta = $instructor->delete_curso($decifrado, $_SESSION["usuario_id"], $fecha_hora);
        echo $respuesta ? "Curso eliminado" : "El curso se pudo eliminar.";
        break;



    /*=============================================
    SECCIONES
    =============================================*/

    case "listar_secciones_x_curso":
        $id_curso = $_POST["idEncrypted"];
        $id_curso = str_replace(' ', '+', $id_curso);
        $iv_dec = substr(base64_decode($id_curso), 0, openssl_cipher_iv_length($cipher));
        $cifradoSinIV = substr(base64_decode($id_curso), openssl_cipher_iv_length($cipher));
        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);
        $datos = $instructor->get_secciones_x_curso($decifrado);
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row["orden"];
            $sub_array[] = $row["titulo"];
            $sub_array[] = $row["lecciones"];
            $sub_array[] = $row["recursos"];
            $sub_array[] = $row["tareas"];
            $cifrado = openssl_encrypt($row["seccion_id"], $cipher, $key, OPENSSL_RAW_DATA, $iv);
            $textoCifrado = base64_encode($iv . $cifrado);
            $sub_array[] = '<button title="Editar sección y sus lecciones" type="button" style="padding: 0;border: none;background: none;" data-ciphertext="' . $textoCifrado . '" id="' . $textoCifrado . '" class="btn-editar-seccion"><i  class="glyphicon glyphicon-edit" style="color:#6aa84f; font-size:large; margin: 3px;" aria-hidden="true"></i></button>' .
                '<button title="Materiales de la sección" type="button" style="padding: 0;border: none;background: none;" data-ciphertext="' . $textoCifrado . '" id="' . $textoCifrado . '" class="btn-materiales"><i  class="glyphicon glyphicon-file" style="color:#f6b26b; font-size:large; margin: 3px;" aria-hidden="true"></i></button>' .
                '<button title="Tareas de la sección" type="button" style="padding: 0;border: none;background: none;" data-ciphertext="' . $textoCifrado . '" id="' . $textoCifrado . '" class="btn-tasks"><i  class="glyphicon glyphicon-pencil" style="color:#6fa8dc; font-size:large; margin: 3px;" aria-hidden="true"></i></button>' .
                '<button title="Cancelar sección" type="button" style="padding: 0;border: none;background: none;" data-ciphertext="' . $textoCifrado . '" id="' . $textoCifrado . '" class="btn-delete-seccion"><i class="glyphicon glyphicon-trash" style="color:#e06666; font-size:large; margin: 3px;" aria-hidden="true"></i></button>';

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

    case "insertSeccion":

        date_default_timezone_set('America/Asuncion');

        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $fecha_hora = $fecha . ' ' . $hora;
        $curso_id = $_POST["curso_id"];

        $iv_dec = substr(base64_decode($curso_id), 0, openssl_cipher_iv_length($cipher));
        $cifradoSinIV = substr(base64_decode($curso_id), openssl_cipher_iv_length($cipher));
        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);

        $datos = array(
            "fecha_hora" => $fecha_hora,
            "activo" => "true",
            "titulo" => $_POST["titulo_leccion"],
            "orden" => $_POST["orden_leccion"],
            "curso_id" => $decifrado
        );
        $resultado = $instructor->insert_datos_seccion($_SESSION["usuario_id"], $datos);
        echo $resultado;
        break;

    case "updateSeccion":

        $iv_dec = substr(base64_decode($_POST["seccion_id"]), 0, openssl_cipher_iv_length($cipher));
        $cifradoSinIV = substr(base64_decode($_POST["seccion_id"]), openssl_cipher_iv_length($cipher));
        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);

        date_default_timezone_set('America/Asuncion');

        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $fecha_hora = $fecha . ' ' . $hora;
        $datos = array(
            "fecha_hora" => $fecha_hora,
            "activo" => "true",
            "titulo" => $_POST["titulo"],
            "orden" => $_POST["orden"],
            "seccion_id" => $decifrado
        );

        $resultado = $instructor->update_datos_seccion($_SESSION["usuario_id"], $datos);
        echo $resultado;
        break;

    case "deleteSeccion":
        date_default_timezone_set('America/Asuncion');

        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $fecha_hora = $fecha . ' ' . $hora;
        $iv_dec = substr(base64_decode($_POST["seccion_id"]), 0, openssl_cipher_iv_length($cipher));
        $cifradoSinIV = substr(base64_decode($_POST["seccion_id"]), openssl_cipher_iv_length($cipher));
        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);

        $resultado = $instructor->delete_seccion($_SESSION["usuario_id"], $decifrado, $fecha_hora);
        echo $resultado;
        break;

    case "insertMaterialesLeccion":

        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $fecha_hora = $fecha . ' ' . $hora;
        $iv_dec = substr(base64_decode($_POST["seccion_id"]), 0, openssl_cipher_iv_length($cipher));
        $cifradoSinIV = substr(base64_decode($_POST["seccion_id"]), openssl_cipher_iv_length($cipher));
        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);
        $curso_id = $instructor->get_curso_id($decifrado);

        $ruta = "../docs/documents/" . $_SESSION["cedula"] . "/" . "cursos_instructor/curso" .
            $curso_id["curso_id"] . "_seccion" . $decifrado . "/materiales" . "/";
        /* TODO: Preguntamos si la ruta existe, en caso no exista la creamos */
        if (!file_exists($ruta)) {
            mkdir($ruta, 0777, true);
        }
        $docNombre = $_FILES['file']['tmp_name'];
        $destino = $ruta . basename($_FILES['file']['name']);
        /* TODO: Movemos los archivos hacia la carpeta creada */
        move_uploaded_file($docNombre, $destino);
        $resultado = $instructor->insert_material_seccion($destino, $decifrado, $_SESSION["usuario_id"], $fecha_hora);
        echo $resultado;
        break;

    case "getCursoId":
        $iv_dec = substr(base64_decode($_POST["seccion_id"]), 0, openssl_cipher_iv_length($cipher));
        $cifradoSinIV = substr(base64_decode($_POST["seccion_id"]), openssl_cipher_iv_length($cipher));
        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);

        $curso_id = $instructor->get_curso_id($decifrado);
        $cifrado = openssl_encrypt($curso_id[0], $cipher, $key, OPENSSL_RAW_DATA, $iv);
        $textoCifrado = base64_encode($iv . $cifrado);
        echo $textoCifrado;
        break;

    case "deleteMaterial":
        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $fecha_hora = $fecha . ' ' . $hora;

        if (file_exists($_POST["archivo"])) {
            if (unlink($_POST["archivo"])) {
                $resultado = $instructor->delete_material_seccion($_POST["material_id"], $fecha_hora, $_SESSION["usuario_id"]);
                echo $resultado;
            } else {
                echo $resultado;
            }
        } else {
            echo "El archivo no se encuentra en el disco.";
        }

        break;

    /*=============================================
    LECCIONES
    =============================================*/

    case "insertVideoLeccion":
        $doc1 = $_FILES['file']['tmp_name'];
        if ($doc1 != "") {
            $iv_dec = substr(base64_decode($_POST["seccion_id"]), 0, openssl_cipher_iv_length($cipher));
            $cifradoSinIV = substr(base64_decode($_POST["seccion_id"]), openssl_cipher_iv_length($cipher));
            $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);

            $iv_dec = substr(base64_decode($_POST["idEncryptedCurso"]), 0, openssl_cipher_iv_length($cipher));
            $cifradoSinIV = substr(base64_decode($_POST["idEncryptedCurso"]), openssl_cipher_iv_length($cipher));
            $decifradoCurso = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);

            $ruta = "../docs/documents/" . $_SESSION["cedula"] . "/" . "cursos_instructor/curso" .
                $decifradoCurso . "_seccion" . $decifrado . "/videos_lecciones" . "/";
            /* TODO: Preguntamos si la ruta existe, en caso no exista la creamos */
            if (!file_exists($ruta)) {
                mkdir($ruta, 0777, true);
            }
            $fileExtension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            $docNombre = date('YmdHi') . "." . $fileExtension;
            $destino = $ruta . $docNombre;
            /* TODO: Movemos los archivos hacia la carpeta creada */
            move_uploaded_file($doc1, $destino);
            echo $destino;
        }
        break;

    case "insertLeccion":

        date_default_timezone_set('America/Asuncion');

        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $fecha_hora = $fecha . ' ' . $hora;
        $seccion_id = $_POST["seccion_id"];

        $iv_dec = substr(base64_decode($seccion_id), 0, openssl_cipher_iv_length($cipher));
        $cifradoSinIV = substr(base64_decode($seccion_id), openssl_cipher_iv_length($cipher));
        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);
        $datos = array(
            "fecha_hora" => $fecha_hora,
            "activo" => "true",
            "titulo" => $_POST["titulo_leccion"],
            "orden" => $_POST["orden_leccion"],
            "descripcion" => $_POST["descripcion"],
            "seccion_id" => $decifrado,
            "video_url" => $_POST["video_leccion"]
        );
        $resultado = $instructor->insert_datos_leccion($datos, $_SESSION["usuario_id"]);
        echo $resultado;
        break;

    case "updateLeccion":

        date_default_timezone_set('America/Asuncion');

        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $fecha_hora = $fecha . ' ' . $hora;
        $leccion_id = $_POST["leccion_id"];

        $iv_dec = substr(base64_decode($leccion_id), 0, openssl_cipher_iv_length($cipher));
        $cifradoSinIV = substr(base64_decode($leccion_id), openssl_cipher_iv_length($cipher));
        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);
        $datos = array(
            "fecha_hora" => $fecha_hora,
            "titulo" => $_POST["titulo_leccion"],
            "orden" => $_POST["orden_leccion"],
            "descripcion" => $_POST["descripcion"],
            "leccion_id" => $decifrado,
            "video_url" => $_POST["video_leccion"]
        );
        $resultado = $instructor->update_datos_leccion($datos, $_SESSION["usuario_id"]);
        echo $resultado;
        break;

    case "mostrarLeccion":
        $leccion_id = $_POST["leccion_id"];

        $iv_dec = substr(base64_decode($leccion_id), 0, openssl_cipher_iv_length($cipher));
        $cifradoSinIV = substr(base64_decode($leccion_id), openssl_cipher_iv_length($cipher));
        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);

        $datos = $instructor->get_info_leccion($decifrado);
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

    case "deleteLeccion":
        date_default_timezone_set('America/Asuncion');

        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $fecha_hora = $fecha . ' ' . $hora;
        $iv_dec = substr(base64_decode($_POST["leccion_id"]), 0, openssl_cipher_iv_length($cipher));
        $cifradoSinIV = substr(base64_decode($_POST["leccion_id"]), openssl_cipher_iv_length($cipher));
        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);

        $resultado = $instructor->delete_leccion($_SESSION["usuario_id"], $decifrado, $fecha_hora);
        echo $resultado;
        break;

    case "listar_tareas_x_seccion":
        $seccion_id = decodeId($_POST["seccion_id"]);
        $datos = $instructor->get_tareas_x_secciones($seccion_id);
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row["tipo_tarea"];
            $sub_array[] = $row["titulo"];
            $sub_array[] = date("d/m/Y", strtotime($row["fecha_limite"]));
            $cifrado = openssl_encrypt($row["tarea_id"], $cipher, $key, OPENSSL_RAW_DATA, $iv);
            $textoCifrado = base64_encode($iv . $cifrado);
            $sub_array[] = '<button title="Editar tarea" type="button" 
                style="padding: 0;border: none;background: none;" data-ciphertext="' . $textoCifrado . '" 
                id="' . $textoCifrado . '" class="btn-editar-tarea"><i  class="glyphicon glyphicon-edit" 
                style="color:#6aa84f; font-size:large; margin: 3px;" aria-hidden="true"></i></button>' .
                '<button title="Ver entregas" type="button" 
                style="padding: 0;border: none;background: none;" data-ciphertext="' . $textoCifrado . '" 
                id="' . $textoCifrado . '" class="btn-ver-entregas"><i  class="glyphicon glyphicon-list" 
                style="color:#8e7cc3; font-size:large; margin: 3px;" aria-hidden="true"></i></button>' .
                '<button title="Eliminar tarea" type="button" style="padding: 0;border: none;background: none;" data-ciphertext="' .
                $textoCifrado . '" id="' . $textoCifrado . '" class="btn-delete-tarea"><i class="glyphicon glyphicon-trash" style="color:#e06666; font-size:large; margin: 3px;" aria-hidden="true"></i></button>';
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
    case "insertTarea":
        $fecha_hora = getLocalDateTime();
        $decifrado = decodeId($_POST["seccion_id"]);

        $datos = array(
            "fecha_hora" => $fecha_hora,
            "activo" => "true",
            "titulo" => $_POST["titulo"],
            "fecha_limite" => $_POST["fecha_limite"],
            "cantidad_intentos" => $_POST["cantidad_intentos"],
            "tipo_tarea" => $_POST["tipo_tarea"],
            "tiempo_limite" => $_POST["tiempo_limite"],
            "total_puntos" => $_POST["total_puntos"],
            "seccion_id" => $decifrado,
            "archivo_url" => $_POST["adjuntoTP"],
            "descripcion" => $_POST["descripcion"]
        );
        $resultado = $instructor->insert_tareas($datos, $_SESSION["usuario_id"]);
        echo $resultado;
        break;

    case "insertAdjuntoTP":
        $doc1 = $_FILES['file']['tmp_name'];
        if ($doc1 != "") {
            $decifrado = decodeId($_POST["seccion_id"]);
            $decifradoCurso = decodeId($_POST["idEncryptedCurso"]);

            $ruta = "../docs/documents/" . $_SESSION["cedula"] . "/" . "cursos_instructor/"
                . "curso" . $decifradoCurso . "/seccion" . $decifrado . $_POST["carpeta"];
            /* TODO: Preguntamos si la ruta existe, en caso no exista la creamos */
            if (!file_exists($ruta)) {
                mkdir($ruta, 0777, true);
            } else {
                // Get a list of all files in the directory
                $files = glob($ruta . '*');
                // Iterate through the list and delete each file
                foreach ($files as $file_existente) {
                    if (is_file($file_existente)) {
                        unlink($file_existente);
                    }
                }
            }
            $fileExtension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            $docNombre = date('YmdHi') . "." . $fileExtension;
            $destino = $ruta . $docNombre;
            /* TODO: Movemos los archivos hacia la carpeta creada */
            move_uploaded_file($doc1, $destino);
            echo $destino;
        }
        break;

    case "mostrarTarea":
        $decifrado = decodeId($_POST["tarea_id"]);
        $datos = $instructor->get_info_tarea($decifrado);
        if (is_array($datos) && count($datos) > 0) {
            $output = array();
            foreach ($datos as $row) {
                $item = array();
                // Iterate through each key-value pair in $row
                foreach ($row as $key => $value) {
                    // Add each key-value pair to the $item array
                    $item[$key] = $value;
                    if ($key == "seccion_id") {
                        $item["seccionEncrypted"] = encryptId($value);
                    }
                }

                // Append each item to the output array
                $output[] = $item;
            }

            echo json_encode($output);
        }
        break;

    case "updateTarea":
        $fecha_hora = getLocalDateTime();
        $decifrado = decodeId($_POST["tarea_id"]);
        if (isset($_POST["total_puntos"]) == false) {
            $_POST["total_puntos"] = 0;
        }
        $datos = array(
            "fecha_hora" => $fecha_hora,
            "activo" => "true",
            "titulo" => $_POST["titulo"],
            "fecha_limite" => $_POST["fecha_limite"],
            "cantidad_intentos" => $_POST["cantidad_intentos"],
            "tipo_tarea" => $_POST["tipo_tarea"],
            "total_puntos" => $_POST["total_puntos"],
            "tiempo_limite" => $_POST["tiempo_limite"],
            "tarea_id" => $decifrado,
            "archivo_url" => $_POST["adjuntoTP"],
            "descripcion" => $_POST["descripcion"]
        );
        $resultado = $instructor->update_tareas($datos, $_SESSION["usuario_id"]);
        echo $resultado;
        break;

    case "deleteTarea":
        $fecha_hora = getLocalDateTime();
        $decifrado = decodeId($_POST["tarea_id"]);
        echo $instructor->delete_tareas($decifrado, $_SESSION["usuario_id"], $fecha_hora);
        break;



    /*=============================================
    CUESTIONARIOS
    =============================================*/
    case "insertEjercicio":
        $fecha_hora = getLocalDateTime();
        $decifrado = decodeId($_POST["tarea_id"]);
        $datos = array(
            "fecha_hora" => $fecha_hora,
            "usuario_id" => $_SESSION["usuario_id"],
            "activo" => "true",
            "texto_ejercicio" => $_POST["texto_ejercicio"],
            "tipo_ejercicio" => $_POST["tipo_ejercicio"],
            "numero_ejercicio" => $_POST["numero_ejercicio"],
            "tarea_id" => $decifrado,
            "imagen_url" => $_POST["imagen_url"],
            "puntaje" => $_POST["puntaje"], // puntaje del ejercicio
            "total_puntos" => $_POST["total_puntos"], // puntaje total de la tarea
            "respuesta_correcta" => isset($_POST["respuesta_correcta"]) ? $_POST["respuesta_correcta"] : null, // Set to null if not provided
        );


        // Collecting additional form inputs
        $additionalData = json_decode($_POST['additional_data'], true);

        // Adding the additional data to the main datos array
        $datos['additional_data'] = json_encode($additionalData);

        // Call the model function to insert the exercise and its options
        $result = $instructor->insert_ejercicios($datos);

        echo $result;
        break;



    case "mostrarEjercicio":

        break;

    case "corregirTP":
        $fecha_hora = getLocalDateTime();
        $decifrado = decodeId($_POST["entregaEncrypted"]);
        $datos = array(
            "puntos_logrados" => $_POST["puntos_logrados"],
            "observacion_instructor" => $_POST["observacion_instructor"],
            "id_entrega" => $decifrado
        );
        echo $instructor->guardar_correccion($datos);
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