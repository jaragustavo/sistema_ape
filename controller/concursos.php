<?php
/* TODO:Cadena de Conexion */
require_once ("../config/conexion.php");
/* TODO:Clases Necesarias */
require_once ("../models/Tramite.php");
$tramite = new Tramite();

require_once ("../models/Concursos.php");
$certificacion = new Concursos();

$concurso = new Concursos();

require_once ("../models/Usuario.php");
$usuario = new Usuario();

$key = "mi_key_secret";
$cipher = "aes-256-cbc";
$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
$docs_lista = array();
/*TODO: opciones del controlador Trámites*/
switch ($_GET["op"]) {

        case "insert_tramite_concurso":
            /*=============================================
            CREAR TRÁMITE
            =============================================*/

            date_default_timezone_set('America/Asuncion');

            $fecha = date('Y-m-d');
            $hora = date('H:i:s');
            $fecha_hora = $fecha . ' ' . $hora;
            try {
                // Recoger datos específicos del formulario
                $nombre_autor = $_POST['nombre_autor'];
                
                $institucion_autor = $_POST['institucion_autor'];
                $pais = $_POST['pais'];
                $documento_identidad = $_POST['documento_identidad'];
                $tipo_vinculo = $_POST['tipo_vinculo'];
                $telefono = $_POST['telefono'];
                $correo = $_POST['correo'];
                $titulo_investigacion = $_POST['titulo_investigacion'];
                $anio_trabajo = $_POST['anio_trabajo'];
                $observacion = $_POST['observacion'];
                 // Construir los datos JSON
                 $datos_especificos = json_encode([
                    'nombre_autor' => $nombre_autor,
                    'institucion_autor' => $institucion_autor,
                    'pais' => $pais,
                    'documento_identidad' => $documento_identidad,
                    'tipo_vinculo' => $tipo_vinculo,
                    'telefono' => $telefono,
                    'correo' => $correo,
                    'titulo_investigacion' => $titulo_investigacion,
                    'anio_trabajo' => $anio_trabajo
                ]);
        

               $datos = array(
                    // Para todas las tablas
                    "usuario_id" => $_SESSION["usuario_id"],
                    "fecha_crea" => $fecha_hora,
                    "tramite_id" => $_POST["tramite_code"],
                    "activo" => "true",
                    "tipo_solicitud" => $_POST["tipo_solicitud"],
                    "observacion" => $_POST["observacion"],
                    

                    // Datos para el trámite gestionado
                    "estado_tramite_id" => $_POST["estado_tramite"],
                    "forma_solicitud" => "definitiva",

                    // Datos para documentos del trámite gestionado
                    "tramite_code" => $_POST["tramite_code"],
                    "cedula_user" => $_POST["documento_identidad"],
                    "tiposDocumentos" => $_POST['tiposDocumentos'],
                    "estado_docs_tramite_id" => 2,

                    // Datos específicos del concurso en JSON
                    "datos_especificos_json" => $datos_especificos
                );
        
                // Insertar los datos
                $respuesta = $tramite->insertar_tramites($datos);

            } catch (Exception $e) {

                echo $e->getMessage();

            }
        break;

    /* TODO: Listado de trámites por usuario,formato json para Datatable JS */
    case "listar_x_usu":
        $datos = $certificacion->get_tramites_gestionados_x_usuario($_SESSION["usuario_id"]);
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
                    $btn = "btn-abrir-inscripcion";
                    $icon = "eye-open";
                    $color_icon = "2986cc";
                    $title = "Ver solicitud";
                } elseif ($permiso == 'M') {
                    $btn = "btn-abrir-inscripcion";
                    $icon = "edit";
                    $color_icon = "6aa84f";
                    $title = "Editar solicitud";
                } elseif ($permiso == 'D') {
                    $btn = "btn-delete-row";
                    $icon = "trash";
                    $color_icon = "e06666";
                    $title = "Eliminar solicitud";
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

    /*=============================================
    GUARDAR DOCUMENTOS EN DIRECTORIOS CORRESPONDIENTES
    =============================================*/
    case "insertDocumentos":

        if (isset($_FILES['file'])) {
            $file = $_FILES['file'];
            $id = $_POST['id'];
            // Detalles del archivo
            if ($id > 0) {
                $datos = $certificacion->get_datos_directorio($id, $_POST['tramite_code'], "archivosDisco");
                $data = array();
                foreach ($datos as $row) {
                    $tramite_nom = $row['tramite_nombre_corto'];
                    $doc_nom = $row['tipo_doc_nombre_corto'];
                }
                $ruta = "../docs/documents/" . $_SESSION["cedula"] . "/certificaciones" . "/" . $tramite_nom . "/" . $doc_nom . "/";

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
                $uploadedFile = $file['tmp_name'];
                $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $docNombre = date('mdHis') . "-" .
                    $doc_nom . "." . $fileExtension;
                $destino = $ruta . $docNombre;
                /* TODO: Movemos los archivos hacia la carpeta creada */
                move_uploaded_file($uploadedFile, $destino);
            }
        } else {
            echo "No se ha enviado ninguna imagen.";
        }

        break;

    case "insert":
        /*=============================================
        CREAR TRÁMITE
        =============================================*/

        date_default_timezone_set('America/Asuncion');

        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $fecha_hora = $fecha . ' ' . $hora;
        try {
            $item = 0;
            $tiposDocumentos = json_decode($_POST['tiposDocumentos']);
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
                "tiposDocumentos" => $_POST['tiposDocumentos'],
                "estado_docs_tramite_id" => 2,
                "observacion" => $_POST['observacion'],
                "curso_id" => $_POST['seccion_curso']
            );

            $respuesta = $certificacion->insertar_tramites($datos);

        } catch (Exception $e) {

            echo $respuesta;

        }
        echo $respuesta;

        break;


    /* TODO: Mostrar informacion de la solicitud de inscripción del curso en formato JSON para la vista */
    case "mostrar":

        $iv_dec = substr(base64_decode($_POST["tramite_gestionado_id"]), 0, openssl_cipher_iv_length($cipher));
        $cifradoSinIV = substr(base64_decode($_POST["tramite_gestionado_id"]), openssl_cipher_iv_length($cipher));
        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);

        $datos = $certificacion->mostrar($decifrado, $_SESSION["usuario_id"]);
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

    case "update":
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
           
            "cedula_user" => $_SESSION["cedula"],
            "tiposDocumentos" => $_POST['tiposDocumentos'],
            "estado_docs_tramite_id" => 2
        );
      
        echo $respuesta = $certificacion->actualizar_tramites($datos);
        break;

    case "getTipoSolicitud":
        $tipo_solicitud = $certificacion->get_tipo_solicitud($_POST["tramite_code"]);
        echo $tipo_solicitud[0]["tipo_solicitud"];
        break;

    case "comboCursos":

        $datos = $certificacion->get_secciones($_POST["tipo_solicitud"], $_POST["tramite_code"], $_SESSION["usuario_id"]);
        $html = "";
        $html .= "<option label='Seleccionar'></option>";
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $html .= "<option value='" . $row['seccion_id'] . "'>" . $row['seccion_nombre'] . "</option>";
            }
            echo $html;
        }
        break;

    case "delete":
        $iv_dec = substr(base64_decode($_POST["ciphertext"]), 0, openssl_cipher_iv_length($cipher));
        $cifradoSinIV = substr(base64_decode($_POST["ciphertext"]), openssl_cipher_iv_length($cipher));
        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);
        date_default_timezone_set('America/Asuncion');
        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $fecha_hora = $fecha . ' ' . $hora;
        $estado_tramite_id = $_POST["estado_tramite_id"];

        $respuesta = $certificacion->delete_tramite_gestionado($decifrado, $_SESSION["usuario_id"], $fecha_hora, $estado_tramite_id);
        echo $respuesta;
        break;

    case "comboTramites":

        $datos = $certificacion->get_tramites_academicos($_POST["tipo_solicitud"]);
        $html = "";
        $html .= "<option label='Seleccionar'></option>";
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $html .= "<option value='" . $row['tramite_id'] . "'>" . $row['tramite'] . "</option>";
            }
            echo $html;
        }
        break;

    case "comboTramitesConcursos":
        $datos = $certificacion->get_tramites_concursos($_POST["tipo_solicitud"]);
        $html = "";
        $html .= "<option label='Seleccionar'></option>";
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $html .= "<option value='" . $row['tramite_id'] . "'>" . $row['tramite'] . "</option>";
            }
            echo $html;
        }
        break;


    case "comboTramitesCapacitaciones":

        $datos = $certificacion->get_tramites_cert_cursos();
        $html = "";
        $html .= "<option label='Seleccionar'></option>";
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $html .= "<option value='" . $row['capacitacion_id'] . "'>" . $row['capacitacion_nombre'] . "</option>";
            }
            echo $html;
        }
        break;

    case "comboEstadosTramites":

        $datos = $certificacion->get_estados_tramites();
        $html = "";
        $html .= "<option label='Seleccionar'></option>";
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $html .= "<option value='" . $row['estado_id'] . "'>" . $row['estado_tramite'] . "</option>";
            }
            echo $html;
        }
        break;

    case "comboTitulos":

        $datos = $tramite->get_titulos();
        $html = "";
        $html .= "<option label='Seleccionar'></option>";
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $html .= "<option value='" . $row['titulo_id'] . "'>" . $row['nombre_titulo'] . "</option>";
            }
            echo $html;
        }
        break;

    case "observacionTramite":
        $iv_dec = substr(base64_decode($_POST["idEncrypted"]), 0, openssl_cipher_iv_length($cipher));
        $cifradoSinIV = substr(base64_decode($_POST["idEncrypted"]), openssl_cipher_iv_length($cipher));
        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);
        $code = $tramite->get_observacion_tramite($decifrado);
        echo $code[0]["observacion"];
        break;

    /*=============================================
    CURSOS DEL SOCIO
    =============================================*/
    case "listar_cursos_x_usu":
        $datos = $certificacion->get_cursos_x_usuario($_SESSION["usuario_id"]);
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
                    $btn = "btn-abrir-inscripcion";
                    $icon = "eye-open";
                    $color_icon = "2986cc";
                    $title = "Ver solicitud";
                } elseif ($permiso == 'M') {
                    $btn = "btn-abrir-inscripcion";
                    $icon = "edit";
                    $color_icon = "6aa84f";
                    $title = "Editar solicitud";
                } elseif ($permiso == 'D') {
                    $btn = "btn-delete-row";
                    $icon = "trash";
                    $color_icon = "e06666";
                    $title = "Eliminar solicitud";
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

    case "cursosFiltrados":
        if (isset($_POST['categories'])) {
            $categories = $_POST['categories'];
            $userId = $_SESSION['usuario_id'];

            // Fetch filtered courses
            $cursos = Certificacion::get_mi_aprendizaje_filtrado($_POST['categories'], $_POST['tipo_solicitud'], $_SESSION['usuario_id']);
            // Generate the HTML for the filtered courses
            foreach ($cursos as $curso) {
                echo '<div class="col">';
                echo '    <div class="courses__item shine__animate-item">';
                echo '        <div class="courses__item-thumb">';
                echo '            <a href="course-details.html" class="shine__animate-link">';
                echo '                <img src="../' . htmlspecialchars($curso["imagen_portada"]) . '" alt="img">';
                echo '            </a>';
                echo '        </div>';
                echo '        <div class="courses__item-content">';
                echo '            <ul class="courses__item-meta list-wrap">';
                echo '                <li class="courses__item-tag">';
                echo '                    <a href="#">' . htmlspecialchars($curso["nombre_categoria"]) . '</a>';
                echo '                </li>';
                echo '                <li class="avg-rating"><i class="fas fa-star"></i> (' . htmlspecialchars($curso["validacion"]) . ' Valoración)</li>';
                echo '            </ul>';
                echo '            <h5 class="title"><a href="course-details.html">' . htmlspecialchars($curso["nombre_curso"]) . '</a></h5>';
                echo '            <p class="author">Por <a href="#">' . htmlspecialchars($curso["instructor"]) . '</a></p>';
                echo '            <div class="courses__item-bottom">';

                // Encryption logic
                $key = "mi_key_secret";
                $cipher = "aes-256-cbc";
                $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
                $cifrado = openssl_encrypt($curso["curso_id"], $cipher, $key, OPENSSL_RAW_DATA, $iv);
                $textoCifrado = base64_encode($iv . $cifrado);

                echo '                <div class="button">';
                echo '                    <a href="curso.php?IDCURSO=' . urlencode($textoCifrado) . '">';
                echo '                        <span class="text">Ir al aula virtual</span>';
                echo '                        <i class="flaticon-arrow-right"></i>';
                echo '                    </a>';
                echo '                </div>';
                echo '            </div>';
                echo '        </div>';
                echo '    </div>';
                echo '</div>';
            }
        }
        break;

    /*=============================================
    ENTREGAS DE TP Y CUESTIONARIOS
    =============================================*/

    case "insertTrabajoPractico":
        $fecha_hora = getLocalDateTime();

        $datos = array(
            "fecha_hora" => $fecha_hora,
            "activo" => "true",
            "adjunto" => $_POST["doc_guardado"],
            "trabajo_texto" => $_POST["trabajo_texto"],
            "tarea_id" => $_POST["tarea_id"],
            "usuario_id" => $_SESSION["usuario_id"]
        );
        $resultado = $certificacion->insert_entrega_tp($datos);
        echo $resultado;
        break;

    case "insertDocumentosVarios":
        $doc1 = $_FILES['file']['tmp_name'];
        if ($doc1 != "") {
            $ruta = "../docs/documents/" . $_SESSION["cedula"] . "/" . "cursos_alumno/"
                . "curso" . $_POST["curso_id"] . "/" . $_POST["carpeta"] . "/"; // Ensure the path ends with a slash

            // Check if the directory exists, if not create it
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

            // Move the uploaded file to the destination
            if (move_uploaded_file($doc1, $destino)) {
                echo $destino; // Return the path of the saved file
            } else {
                echo "Error moving the uploaded file.";
            }
        } else {
            echo "No file uploaded.";
        }
        break;

    case "insertCuestionario":
        $fecha_hora = getLocalDateTime();
        $_POST["tarea_id"] = decodeId($_POST["tarea_id"]);
        $datos = array(
            "fecha_hora" => $fecha_hora,
            "activo" => "true",
            "tarea_id" => $_POST["tarea_id"],
            "usuario_id" => $_SESSION["usuario_id"]
        );
        $resultado = $certificacion->insert_cuestionario($datos);
        echo $resultado;
        break;

    case "guardarCuestionario":
        $fecha_hora = getLocalDateTime();
        $seccion_id = $_POST['seccion_id'];
        $tarea_id = $_POST['tarea_id'];

        // Initialize variables
        $puntos_logrados = 0;
        $respuestas_json = [];

        // Exercise data
        $exercise_data = json_decode($_POST['exercise_data'], true);

        foreach ($exercise_data as $exercise) {
            $exerciseId = $exercise['exerciseId'];
            $options = $exercise['options'];
            $sub_total = 0;
            $puntaje = $certificacion->get_puntaje_ejercicio($exerciseId)['puntaje'];
            // error_log(count($options));
            // error_log(json_encode($options));
            // Initialize exercise responses array
            $exercise_responses = [
                'pregunta_id' => $exerciseId,
                'respuestas' => []
            ];

            foreach ($options as $option) {
                if (isset($option['checked']) === true) {
                    $optionId = $option['id'];
                    $checked = $option['checked'];
                    $optionId = substr($optionId, strlen('op'));
                    // Fetch whether the option is correct
                    $opcion_respuesta = $certificacion->get_opcion_es_correcto($optionId);
                    error_log($opcion_respuesta['es_correcto'] . ' / ' . $checked);

                    if ($opcion_respuesta['es_correcto'] != $checked) {
                        $sub_total = 1;
                    }
                    else{
                        $sub_total = 0;
                        break;
                    }

                    // Append the response to the exercise responses array
                    $exercise_responses['respuestas'][] = [
                        'respuesta_id' => $optionId,
                        'eleccion' => $checked
                    ];
                } else if (isset($option['text'])) {
                    $optionId = $option['id'];
                    $text = $option['text'];
                    $string = $option['id'];
                    $substring = 'v_f';

                    if (substr($string, 0, strlen($substring)) === $substring) {
                        $optionId = substr($string, strlen($substring));
                    }
                    $respuesta_correcta = $certificacion->get_respuesta_correcta($optionId);
                    error_log($respuesta_correcta . ' / ' . $text);
                    if (areStringsSimilar($respuesta_correcta, $text)) {
                        $sub_total = 1;
                    } else {
                        $sub_total = 0;
                    }
                    $exercise_responses['respuestas'][] = [
                        'respuesta_id' => $optionId,
                        'respuesta' => $text
                    ];
                }
            }


            // Append the exercise responses to the main responses array
            $respuestas_json[] = $exercise_responses;

            // Calculate points for the current exercise
            $current_points = 0;
            $current_points = $puntaje * $sub_total;

            $puntos_logrados += $current_points;
        }

        // Encode the responses as JSON
        $respuestas_json = json_encode($respuestas_json);
        $fecha_hora = getLocalDateTime();
        // Prepare data for insertion
        $datos = [
            "fecha_hora" => $fecha_hora,
            "activo" => "true",
            "tarea_id" => $tarea_id,
            "usuario_id" => $_SESSION["usuario_id"],
            "puntos_logrados" => $puntos_logrados,
            "respuestas" => $respuestas_json
        ];

        // Insert the data
        $resultado = $certificacion->insert_respuestas_cuestionarios($datos);
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

function areStringsSimilar($str1, $str2, $threshold = 80)
{
    // Case insensitive comparison
    if (strcasecmp($str1, $str2) === 0) {
        return true;
    }

    // Split strings into words
    $words1 = str_word_count($str1, 1);
    $words2 = str_word_count($str2, 1);

    // Check the number of words in each string
    if (count($words1) > 3 && count($words2) > 3) {
        // Calculate similarity percentage
        similar_text($str1, $str2, $percent);
        return $percent >= $threshold;
    }

    return false;
}

?>