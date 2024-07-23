<?php
/* TODO:Cadena de Conexion */
require_once ("../config/conexion.php");
/* TODO:Clases Necesarias */
require_once ("../models/Tramite.php");
$tramite = new Tramite();

require_once ("../models/FormaCobro.php");

require_once ("../models/Filial.php");

require_once ("../models/Banco.php");

require_once ("../models/TipoCuentaBancaria.php");

require_once ("../models/Usuario.php");

$usuario = new Usuario();


$key = "mi_key_secret";
$cipher = "aes-256-cbc";
$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
$docs_lista = array();
/*TODO: opciones del controlador Trámites*/
switch ($_GET["op"]) {

    /* TODO: Listado de trámites por usuario,formato json para Datatable JS */
    case "listar_x_usu":
        $datos = $tramite->get_tramites_gestionados_x_usuario($_SESSION["usuario_id"]);
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

            $sub_array[] = date("d/m/Y", strtotime($row["ultimo_movimiento"]));

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
                    $btn = "btn-ver-solSolidaridad";
                    $icon = "eye-open";
                    $color_icon = "2986cc";
                    $title = "Ver solicitud";
                } elseif ($permiso == 'M') {
                    $btn = "btn-editar-solSolidaridad";
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

    case "listar_ayuda_x_usu":
        $datos = $tramite->get_solicitudes_ayuda_x_usuario($_SESSION["usuario_id"]);
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

            $sub_array[] = date("d/m/Y", strtotime($row["ultimo_movimiento"]));

            $cifrado = openssl_encrypt($row["ayuda_id"], $cipher, $key, OPENSSL_RAW_DATA, $iv);
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
                    $btn = "btn-abrir-solAyuda";
                    $icon = "eye-open";
                    $color_icon = "2986cc";
                    $title = "Ver solicitud";
                } elseif ($permiso == 'M') {
                    $btn = "btn-abrir-solAyuda";
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

    /* TODO: Listado de documentos personales,formato json para Datatable JS, filtro avanzado*/
    case "listar_filtro":
        if ($_POST["fecha"] != "") {
            $_POST["fecha"] = date('Y-m-d', strtotime($_POST["fecha"]));
        }

        $datos = $documentoPersonal->filtrar_doc_personal($_POST["tipo_documento"], $_POST["fecha"]);
        $data = array();
        foreach ($datos as $row) {

            $sub_array = array();
            $sub_array[] = $row["tipo_documento"];
            $sub_array[] = date("d/m/Y", strtotime($row["fecha"]));
            $ruta = "http://localhost:90/homesirepro/docs/documents/" . $_SESSION["cedula"] . "/" . "personales/" . $row["documento"];

            $cifrado = openssl_encrypt($row["dato_personal_id"], $cipher, $key, OPENSSL_RAW_DATA, $iv);
            $textoCifrado = base64_encode($iv . $cifrado);

            $sub_array[] = '<button title="Abrir documento" style="padding: 0;border: none;background: none;" type="button" data-ciphertext="' . $ruta . '" id="' . $textoCifrado . '" class="btn-open-pdf"><i class="glyphicon glyphicon-file" style="color:#2986cc; font-size:large; margin: 3px;" aria-hidden="true"></button></i>
                <button title="Editar documento" type="button" style="padding: 0;border: none;background: none;" data-ciphertext="' . $textoCifrado . '" id="' . $textoCifrado . '" class="btn-abrir-solicitud"><i  class="glyphicon glyphicon-edit" style="color:#6aa84f; font-size:large; margin: 3px;" aria-hidden="true"></i></button>
                <button title="Eliminar documento" type="button" style="padding: 0;border: none;background: none;" data-ciphertext="' . $textoCifrado . '" id="' . $textoCifrado . '" class="btn-delete-row"><i class="glyphicon glyphicon-trash" style="color:#e06666; font-size:large; margin: 3px;" aria-hidden="true"></i></button>';

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

           if (isset( $_POST['tramite_code'])) { 
                if (isset( $_POST['tramite_gestionado_idEncrypted'])) { 
                    $iv_dec = substr(base64_decode($_POST["tramite_gestionado_idEncrypted"]), 0, openssl_cipher_iv_length($cipher));
                    $cifradoSinIV = substr(base64_decode($_POST["tramite_gestionado_idEncrypted"]), openssl_cipher_iv_length($cipher));
                    $tramite_gestionado_id = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);
                         // Obtener la conexión a la base de datos
                    $db = Conectar::Conexion(); // Llama a la función estática Conexion() de la clase Conectar

                    // Consulta SQL para obtener tramite_id basado en id de tramite_gestionado
                    $sql = "SELECT tramite_id FROM tramites_gestionados WHERE id = :id";
                    $query = $db->prepare($sql);
                    $query->bindParam(':id',$tramite_gestionado_id, PDO::PARAM_INT);
                    $query->execute();

                    // Obtiene el resultado de la consulta como un arreglo asociativo
                    $result = $query->fetch(PDO::FETCH_ASSOC); // Utiliza fetch en lugar de fetchAll para obtener una sola fila
                }
             // Verifica si se obtuvo algún resultado
               if ($result) {
                   // Si hay resultado, asigna el valor de tramite_id a la variable $tramite_id
                   $_POST['tramite_code'] = $result['tramite_id'];
                   // Ahora puedes usar $tramite_id según lo necesites
               } else {
                   // Maneja el caso cuando no hay resultados
                   echo "No se encontró el tramite_id para el id especificado.";
               }
           }
            // Detalles del archivo
            if ($id > 0) {
                $datos = $tramite->get_datos_directorio($id, $_POST['tramite_code'], "archivosDisco");
                $data = array();
                foreach ($datos as $row) {
                    $tramite_nom = $row['tramite_nombre_corto'];
                    $doc_nom = $row['tipo_doc_nombre_corto'];
                }
                $ruta = "../docs/documents/" . $_SESSION["cedula"] . "/" . $tramite_nom . "/" . $doc_nom . "/";

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
                
               // Obtener fecha y hora con milisegundos
                $now = new DateTime();
                $docNombre = $now->format('mdHisv') . "." . $fileExtension;
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
           
            if ($_POST["tipo_solicitud"] == 'solidaridad') {

                $forma_cobro = new FormaCobro();
               
                $forma_cobro_nombre = $forma_cobro->get_tipo_cobro($_POST["forma_cobro"]);

                if($_POST["forma_cobro"] == 2){
                   
                    $filial = new Filial();
                    $filial_data = $filial->get_filial($_POST["filial"]);
                    $filial_nombre = $filial_data['nombre_filial']; 

                    $banco_nombre = ''; 
                    $tipo_cuenta_bancaria_nombre = '';

                }else if($_POST["forma_cobro"] == 1){
                    $banco = new Banco();
                    $banco_data = $banco->get_banco($_POST["banco"]);
                    $banco_nombre = $banco_data['nombre']; 
                    $filial_nombre = '';
                    $tipoCuenta = new TipoCuentaBancaria();
                    $tipoCuenta_data = $tipoCuenta->get_tipoCuentaBancaria($_POST["tipo_cuenta"]);
                    $tipo_cuenta_bancaria_nombre = $tipoCuenta_data['nombre']; 

                } 

                $datos_especificos = json_encode([
                    "tipo_solicitud" => $_POST["tipo_solicitud"],
                    "nombre" => $_POST["nombre"],
                    "apellido" => $_POST["apellido"],
                    "documento_identidad" => $_POST["documento_identidad"],
                    "telefono" => $_POST["telefono"],
                    "ciudad_id" => $_POST["ciudad_id"],
                    "ciudad_nombre" => $_POST["ciudad_nombre"],
                    "departamento_id" => $_POST["departamento_id"],
                    "departamento_nombre" => $_POST["departamento_nombre"],
                    "observacion" => $_POST["observacion"],
                    "direccion_domicilio" => $_POST["direccion_domicilio"],
                    "forma_cobro" => $_POST["forma_cobro"],
                    "forma_cobro_nombre" => $forma_cobro_nombre,

                    // Si elige cobrar por transferencia
                    "banco" => $_POST["banco"],
                    "banco_nombre" => $banco_nombre,
                    "tipo_cuenta" => $_POST["tipo_cuenta"],
                    "tipo_cuenta_bancaria_nombre" => $tipo_cuenta_bancaria_nombre,
                    "numero_cuenta" => $_POST["numero_cuenta"],
                    "denominacion_cuenta" => $_POST["denominacion_cuenta"],
                    "doc_identidad_cuenta" => $_POST["doc_identidad_cuenta"],
                    "telefono_cuenta" => $_POST["telefono_cuenta"],
                    // Si elige cobrar en efectivo retirando de la filial
                    "filial" => $_POST["filial"],
                    "filial_nombre" => $filial_nombre
                ]);
             
                $datos = array(
                    // Para todas las tablas
                    "usuario_id" => $_SESSION["usuario_id"],
                    "fecha_crea" => $fecha_hora,
                    "tramite_id" => $_POST["tramite_code"],
                    "activo" => "true",

                    // Datos para el trámite gestionado
                    "estado_tramite_id" => $_POST["estado_tramite"],
                    "forma_solicitud" => "definitiva",

                    // Datos para documentos del trámite gestionado
                    "tramite_code" => $_POST["tramite_code"],
                    "cedula_user" => $_POST["documento_identidad"],
                    "tiposDocumentos" => $_POST['tiposDocumentos'],
                    "estado_docs_tramite_id" => 2,

                    //Datos para el formulario de datos del Solicitante
                    "tipo_solicitud" => $_POST["tipo_solicitud"],
                    "nombre" => $_POST["nombre"],
                    "apellido" => $_POST["apellido"],
                    "documento_identidad" => $_POST["documento_identidad"],
                    "telefono" => $_POST["telefono"],
                    "ciudad_solicitante" => "",
                    "barrio_solicitante" => "",
                    "celular" => "",
                    "observacion" => $_POST["observacion"],

                    // Datos para el desembolso
                    "forma_cobro" => $_POST["forma_cobro"],
                    // Si elige cobrar por transferencia
                    "banco" => $_POST["banco"],
                    "tipo_cuenta" => $_POST["tipo_cuenta"],
                    "numero_cuenta" => $_POST["numero_cuenta"],
                    "denominacion_cuenta" => $_POST["denominacion_cuenta"],
                    "doc_identidad_cuenta" => $_POST["doc_identidad_cuenta"],
                    "telefono_cuenta" => $_POST["telefono_cuenta"],
                    // Si elige cobrar en efectivo retirando de la filial
                    "filial" => $_POST["filial"],
                    // Datos específicos del concurso en JSON
                    "datos_especificos_json" => $datos_especificos

                );
            } else {
                $datos = array(
                    // Para todas las tablas
                    "usuario_id" => $_SESSION["usuario_id"],
                    "fecha_crea" => $fecha_hora,
                    "tramite_id" => $_POST["tramite_code"],
                    "activo" => "true",

                    // Datos para el trámite gestionado
                    "estado_tramite_id" => $_POST["estado_tramite"],
                    "forma_solicitud" => "definitiva",

                    // Datos para documentos del trámite gestionado
                    "tramite_code" => $_POST["tramite_code"],
                    "cedula_user" => $_POST["documento_identidad"],
                    "tiposDocumentos" => $_POST['tiposDocumentos'],
                    "estado_docs_tramite_id" => 2
                );
            }
           
            $respuesta = $tramite->insertar_tramites($datos);

        } catch (Exception $e) {

            echo $e->getMessage();

        }
        break;


    case "insertSolicitudAyuda":
        /*=============================================
        CREAR TRÁMITE
        =============================================*/

        date_default_timezone_set('America/Asuncion');

        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $fecha_hora = $fecha . ' ' . $hora;
        try {
            $item = 0;

            $datos = array(
                // Para todas las tablas
                "usuario_id" => $_SESSION["usuario_id"],
                "fecha_crea" => $fecha_hora,
                "tramite_id" => $_POST["tramite_code"],
                "activo" => "true",

                // Datos para el trámite gestionado
                "estado_tramite_id" => 6,
                "forma_solicitud" => "definitiva",

                //Datos para la ayuda
                "observacion" => $_POST["observacion"]

            );



            $respuesta = $tramite->insertar_tramites_ayuda($datos);

        } catch (Exception $e) {

            echo $e->getMessage();

        }
        break;

    case "updateSolicitudAyuda":
        $iv_dec = substr(base64_decode($_POST["idEncrypted"]), 0, openssl_cipher_iv_length($cipher));
        $cifradoSinIV = substr(base64_decode($_POST["idEncrypted"]), openssl_cipher_iv_length($cipher));
        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);
        date_default_timezone_set('America/Asuncion');

        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $fecha_hora = $fecha . ' ' . $hora;
        try {
            $item = 0;

            $datos = array(
                // Para todas las tablas
                "usuario_id" => $_SESSION["usuario_id"],
                "fecha_crea" => $fecha_hora,
                "tramite_id" => $_POST["tramite_code"],
                "activo" => "true",

                // Datos para el trámite gestionado
                "solicitud_ayuda_id" => $decifrado,
                "estado_tramite_id" => $_POST["estado_tramite"],
                "forma_solicitud" => "definitiva",

                //Datos para la ayuda
                "observacion" => $_POST["observacion"]

            );



            $respuesta = $tramite->update_tramites_ayuda($datos);

        } catch (Exception $e) {

            echo $e->getMessage();

        }
        break;
    /* TODO: Actualizamos el Documento Personal a cerrado y adicionamos una linea adicional */
    case "update":

        $iv_dec = substr(base64_decode($_POST["idEncrypted"]), 0, openssl_cipher_iv_length($cipher));
        $cifradoSinIV = substr(base64_decode($_POST["idEncrypted"]), openssl_cipher_iv_length($cipher));
        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);
        if ($decifrado === false) {
            // echo "Decryption failed with error: $error";
        } else {

            date_default_timezone_set('America/Asuncion');

            $fecha = date('Y-m-d');
            $hora = date('H:i:s');
            $fecha_hora = $fecha . ' ' . $hora;
            try {
                $item = 0;
                $tiposDocumentos = json_decode($_POST['tiposDocumentos']);
                if ($_POST["tipo_solicitud"] == 'solidaridad') {

                    $forma_cobro = new FormaCobro();
               
                    $forma_cobro_nombre = $forma_cobro->get_tipo_cobro($_POST["forma_cobro"]);
    
                    if($_POST["forma_cobro"] == 2){
                       
                        $filial = new Filial();
                        $filial_data = $filial->get_filial($_POST["filial"]);
                        $filial_nombre = $filial_data['nombre_filial']; 
    
                        $banco_nombre = ''; 
                        $tipo_cuenta_bancaria_nombre = '';
    
                    }else if($_POST["forma_cobro"] == 1){
                        $banco = new Banco();
                        $banco_data = $banco->get_banco($_POST["banco"]);
                        $banco_nombre = $banco_data['nombre']; 
                        $filial_nombre = '';
                        $tipoCuenta = new TipoCuentaBancaria();
                        $tipoCuenta_data = $tipoCuenta->get_tipoCuentaBancaria($_POST["tipo_cuenta"]);
                        $tipo_cuenta_bancaria_nombre = $tipoCuenta_data['nombre']; 
    
                    } 
    
                    $datos_especificos = json_encode([
                        "tipo_solicitud" => $_POST["tipo_solicitud"],
                        "nombre" => $_POST["nombre"],
                        "apellido" => $_POST["apellido"],
                        "documento_identidad" => $_POST["documento_identidad"],
                        "telefono" => $_POST["telefono"],
                        "ciudad_id" => $_POST["ciudad_id"],
                        "ciudad_nombre" => $_POST["ciudad_nombre"],
                        "departamento_id" => $_POST["departamento_id"],
                        "departamento_nombre" => $_POST["departamento_nombre"],
                        "observacion" => $_POST["observacion"],
                        "direccion_domicilio" => $_POST["direccion_domicilio"],
                        "forma_cobro" => $_POST["forma_cobro"],
                        "forma_cobro_nombre" => $forma_cobro_nombre,
    
                        // Si elige cobrar por transferencia
                        "banco" => $_POST["banco"],
                        "banco_nombre" => $banco_nombre,
                        "tipo_cuenta" => $_POST["tipo_cuenta"],
                        "tipo_cuenta_bancaria_nombre" => $tipo_cuenta_bancaria_nombre,
                        "numero_cuenta" => $_POST["numero_cuenta"],
                        "denominacion_cuenta" => $_POST["denominacion_cuenta"],
                        "doc_identidad_cuenta" => $_POST["doc_identidad_cuenta"],
                        "telefono_cuenta" => $_POST["telefono_cuenta"],
                        // Si elige cobrar en efectivo retirando de la filial
                        "filial" => $_POST["filial"],
                        "filial_nombre" => $filial_nombre
                    ]);
                 
                    $datos = array(
                        // Para todas las tablas
                        "usuario_id" => $_SESSION["usuario_id"],
                        "fecha_crea" => $fecha_hora,
                        "tramite_id" => $_POST["tramite_code"],
                        "activo" => "true",
    
                        // Datos para el trámite gestionado
                        "tramite_gestionado_id" => $decifrado,
                        "estado_tramite_id" => $_POST["estado_tramite"],
                        "forma_solicitud" => "definitiva",
    
                        // Datos para documentos del trámite gestionado
                        "tramite_code" => $_POST["tramite_code"],
                        "cedula_user" => $_POST["documento_identidad"],
                        "tiposDocumentos" => $_POST['tiposDocumentos'],
                        "estado_docs_tramite_id" => 2,
    
                        //Datos para el formulario de datos del Solicitante
                        "tipo_solicitud" => $_POST["tipo_solicitud"],
                        "nombre" => $_POST["nombre"],
                        "apellido" => $_POST["apellido"],
                        "documento_identidad" => $_POST["documento_identidad"],
                        "telefono" => $_POST["telefono"],
                        "ciudad_solicitante" => "",
                        "barrio_solicitante" => "",
                        "celular" => "",
                        "observacion" => $_POST["observacion"],
    
                        // Datos para el desembolso
                        "forma_cobro" => $_POST["forma_cobro"],
                        // Si elige cobrar por transferencia
                        "banco" => $_POST["banco"],
                        "tipo_cuenta" => $_POST["tipo_cuenta"],
                        "numero_cuenta" => $_POST["numero_cuenta"],
                        "denominacion_cuenta" => $_POST["denominacion_cuenta"],
                        "doc_identidad_cuenta" => $_POST["doc_identidad_cuenta"],
                        "telefono_cuenta" => $_POST["telefono_cuenta"],
                        // Si elige cobrar en efectivo retirando de la filial
                        "filial" => $_POST["filial"],
                        // Datos específicos del concurso en JSON
                        "datos_especificos_json" => $datos_especificos
                    );
                
                }

                $respuesta = $tramite->actualizar_tramites($datos);

            } catch (Exception $e) {

                echo $e->getMessage();

            }
        }

        break;
    /* TODO: Mostrar informacion de Documento Personal en formato JSON para la vista */
    case "mostrar":

        $iv_dec = substr(base64_decode($_POST["tramite_gestionado_id"]), 0, openssl_cipher_iv_length($cipher));
        $cifradoSinIV = substr(base64_decode($_POST["tramite_gestionado_id"]), openssl_cipher_iv_length($cipher));
        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);

        $datos = $tramite->mostrar($decifrado, $_SESSION["usuario_id"]);
        if (is_array($datos) == true and count($datos) > 0) {
            $output = array();
            foreach ($datos as $row) {
                $item = array(
                    "nombre_tramite" => $row["nombre_tramite"],
                    "tramite_id" => $row["tramite_id"],
                    "tramite_gestionado_id" => $row["tramite_gestionado_id"],
                    "documento_id" => $row["documento_id"],
                    "tipo_doc_id" => $row["tipo_doc_id"],
                    "documento" => $row["documento"]
                );

                // Append each item to the output array
                $output[] = $item;
            }
            echo json_encode($output);
        }
        break;

    case "delete":
        $iv_dec = substr(base64_decode($_POST["ciphertext"]), 0, openssl_cipher_iv_length($cipher));
        $cifradoSinIV = substr(base64_decode($_POST["ciphertext"]), openssl_cipher_iv_length($cipher));
        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);
        // error_log('$$$$$$$$$$$$$$$$'.$decifrado);
        date_default_timezone_set('America/Asuncion');
        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $fecha_hora = $fecha . ' ' . $hora;

        $respuesta = $tramite->delete_tramite_gestionado($decifrado, $_SESSION["usuario_id"], $fecha_hora);
        echo $respuesta;
        break;

    case "comboTramites":

        $datos = $tramite->get_tramites_solidaridad();
        $html = "";
        $html .= "<option label='Seleccionar'></option>";
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $html .= "<option value='" . $row['tramite_id'] . "'>" . $row['tramite'] . "</option>";
            }
            echo $html;
        }
        break;
   

    case "comboCursos":

        $datos = $tramite->get_cursos();
        $html = "";
        $html .= "<option label='Seleccionar'></option>";
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $html .= "<option value='" . $row['tramite_id'] . "'>" . $row['tramite'] . "</option>";
            }
            echo $html;
        }
        break;

    case "comboTramitesSedeSocial":

        $datos = $tramite->get_tramites_sede_social();
        $html = "";
        $html .= "<option label='Seleccionar'></option>";
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $html .= "<option value='" . $row['tramite_id'] . "'>" . $row['tramite'] . "</option>";
            }
            echo $html;
        }
        break;
    case "comboLocales":

        $datos = $tramite->get_locales();
        $html = "";
        $html .= "<option label='Seleccionar'></option>";
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $html .= "<option value='" . $row['local_id'] . "'>" . $row['nombre_local'] . "</option>";
            }
            echo $html;
        }
        break;

    case "comboAyuda":

        $datos = $tramite->get_tramites_ayuda();
        $html = "";
        $html .= "<option label='Seleccionar'></option>";
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $html .= "<option value='" . $row['tramite_id'] . "'>" . $row['tramite'] . "</option>";
            }
            echo $html;
        }
        break;

    case "comboEstadosTramites":

        $datos = $tramite->get_estados_tramites($_POST["tramite_code"]);
        $html = "";
        $html .= "<option label='Seleccionar'></option>";
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $html .= "<option value='" . $row['estado_tramite_id'] . "'>" . $row['estado_tramite'] . "</option>";
            }
            echo $html;
        }
        break;

    case "code":
        $code = $tramite->get_tramite_code($_POST["tramite_id"]);
        echo $code[0]["url"];
        break;

    case "cargarTitulo":
        $code = $tramite->get_tramite_name($_POST["titulo"]);
        echo $code[0]["tramite_nombre"];
        break;
    case "comboEstadoCivil":
        $datos = $tramite->get_estados_civiles();
        $html = "";
        $html .= "<option label='Seleccionar'></option>";
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $html .= "<option value='" . $row['estado_civil_id'] . "'>" . $row['estado_civil'] . "</option>";
            }
            echo $html;
        }
        break;

    case "comboPaises":
        $datos = $tramite->get_paises();
        $html = "";
        $html .= "<option label='Seleccionar'></option>";
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $html .= "<option value='" . $row['pais_id'] . "'>" . $row['pais'] . "</option>";
            }
            echo $html;
        }
        break;
    case "comboDepartamentos":
        $datos = $tramite->get_departamentos($_POST["pais"]);
        $html = "";
        $html .= "<option label='Seleccionar'></option>";
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $html .= "<option value='" . $row['departamento_id'] . "'>" . $row['departamento'] . "</option>";
            }
            echo $html;
        }
        break;
    case "comboCiudades":
        if (isset($_POST["departamento"])) {
            $dpto = $_POST["departamento"];
        } else {
            $dpto = "";
        }
        $datos = $tramite->get_ciudades($dpto);
        $html = "";
        $html .= "<option label='Seleccionar'></option>";
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $html .= "<option value='" . $row['ciudad_id'] . "'>" . $row['ciudad'] . "</option>";
            }
            echo $html;
        }
        break;
    case "comboBarrios":
        $datos = $tramite->get_barrios($_POST["ciudad"]);
        $html = "";
        $html .= "<option label='Seleccionar'></option>";
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $html .= "<option value='" . $row['barrio_id'] . "'>" . $row['barrio'] . "</option>";
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
    TRÁMITES ADMINISTRATIVOS PARA SUBSIDIOS
    =============================================*/
    case "comboFiliales":
        $datos = $tramite->get_filiales();
        $html = "";
        $html .= "<option label='Seleccionar'></option>";
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $html .= "<option value='" . $row['filial_id'] . "'>" . $row['nombre_filial'] . "</option>";
            }
            echo $html;
        }
        break;

    case "comboBancos":
        $datos = $tramite->get_bancos();
        $html = "";
        $html .= "<option label='Seleccionar'></option>";
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $html .= "<option value='" . $row['banco_id'] . "'>" . $row['nombre_banco'] . "</option>";
            }
            echo $html;
        }
        break;

    case "comboTiposCuentas":
        $datos = $tramite->get_tipos_cuentas();
        $html = "";
        $html .= "<option label='Seleccionar'></option>";
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $html .= "<option value='" . $row['tipo_cuenta_id'] . "'>" . $row['nombre_tipo_cuenta'] . "</option>";
            }
            echo $html;
        }
        break;
 
    case "mostrarSolicSolidaridad":
        $iv_dec = substr(base64_decode($_POST["idSolicitud"]), 0, openssl_cipher_iv_length($cipher));
        $cifradoSinIV = substr(base64_decode($_POST["idSolicitud"]), openssl_cipher_iv_length($cipher));
        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);
        $datos = $tramite->get_tramites_gestionados_datos($decifrado);
        if (is_array($datos) && count($datos) > 0) {
            echo json_encode($datos);
        } else {
            echo json_encode([]);
        }
        break;

    /*=============================================
    TRÁMITES ADMINISTRATIVOS PARA RESERVAS
    =============================================*/

    case "listar_reservas_x_usu":
        $datos = $tramite->get_reservas_x_usuario($_SESSION["usuario_id"]);
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row["local_nombre"];
            $sub_array[] = date("d/m/Y", strtotime($row["fecha_solicitud"]));
            $sub_array[] = date("d/m/Y", strtotime($row["fecha_reserva"]));
            require ("../view/Formularios/avance.php");
            $sub_array[] = '<div class="progress-with-amount">
                                    <progress class="progress progress' . $color . ' progress-no-margin" value="' . $avance . '" max="100">' . $avance . '%</progress>
                                    <div class="progress-with-amount-number">' . $avance . '%</div>
                                </div><div class="font-11 color-blue-grey-lighter uppercase" style="margin-top:2px;">'. $row["estado_actual"].
                                '</div>';
                
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
                    $btn = "btn-abrir-reserva";
                    $icon = "eye-open";
                    $color_icon = "2986cc";
                    $title = "Ver solicitud";
                } elseif ($permiso == 'M') {
                    $btn = "btn-abrir-reserva";
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
    case "insertReserva":

        date_default_timezone_set('America/Asuncion');

        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $fecha_hora = $fecha . ' ' . $hora;
        try {
            $item = 0;

            //Datos para la reserva
            $datos_especificos = json_encode([
                "hora_desde" => $_POST["hora_desde"],
                "hora_hasta" => $_POST["hora_hasta"],
                "local" => $_POST["local"],
                "fecha_reserva" => $_POST["fecha_reserva"],
                "cantidad_personas" => $_POST["cantidad_personas"]
            ]);

            $datos = array(
                // Para todas las tablas
                "usuario_id" => $_SESSION["usuario_id"],
                "fecha_crea" => $fecha_hora,
                "tramite_id" => $_POST["tramite_code"],
                "activo" => "true",

                // Datos para el trámite gestionado
                "estado_tramite_id" => $_POST["estado_tramite_id"],
                "forma_solicitud" => "definitiva",

                //Datos para la reserva
               
                 "datos_especificos_json" => $datos_especificos

            );



            $respuesta = $tramite->insertar_tramites($datos);

        } catch (Exception $e) {

            echo $e->getMessage();

        }
        break;

    case "obtener_datos_tramite":
        $datos = $tramite->get_datos_tramites($_POST["tramite_id"]);
     
        if (is_array($datos) && count($datos) > 0) {
            echo json_encode($datos);
        } else {
            echo json_encode([]);
        }
        break;
      
    case "updateReserva":
        $iv_dec = substr(base64_decode($_POST["idSolicitud"]), 0, openssl_cipher_iv_length($cipher));
        $cifradoSinIV = substr(base64_decode($_POST["idSolicitud"]), openssl_cipher_iv_length($cipher));
        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);
        date_default_timezone_set('America/Asuncion');

        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $fecha_hora = $fecha . ' ' . $hora;
        try {
            $item = 0;

            $datos = array(
                // Para todas las tablas
                "usuario_id" => $_SESSION["usuario_id"],
                "fecha_crea" => $fecha_hora,
                "activo" => "true",

                // Datos para el trámite gestionado
                "reserva_id" => $decifrado,
                "estado_tramite_id" => $_POST["estado_tramite_id"],
                "forma_solicitud" => "definitiva",

                //Datos para la reserva
                "hora_desde" => $_POST["hora_desde"],
                "hora_hasta" => $_POST["hora_hasta"],
                "local" => $_POST["local"],
                "fecha_reserva" => $_POST["fecha_reserva"],
                "cantidad_personas" => $_POST["cantidad_personas"]

            );



            $respuesta = $tramite->update_tramites_reservas($datos);

        } catch (Exception $e) {

            echo $e->getMessage();


        }
        break;
    case "deleteReserva":
        $iv_dec = substr(base64_decode($_POST["ciphertext"]), 0, openssl_cipher_iv_length($cipher));
        $cifradoSinIV = substr(base64_decode($_POST["ciphertext"]), openssl_cipher_iv_length($cipher));
        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);
        date_default_timezone_set('America/Asuncion');
        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $fecha_hora = $fecha . ' ' . $hora;

        $respuesta = $tramite->delete_reserva($decifrado, $_SESSION["usuario_id"], $fecha_hora);
        break;


    case "observacionAyuda":
        $iv_dec = substr(base64_decode($_POST["idEncrypted"]), 0, openssl_cipher_iv_length($cipher));
        $cifradoSinIV = substr(base64_decode($_POST["idEncrypted"]), openssl_cipher_iv_length($cipher));
        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);
        $code = $tramite->get_observacion_ayuda($decifrado);
        echo $code[0]["observacion"];
        break;

    case "deleteSolicitudAyuda":
        $iv_dec = substr(base64_decode($_POST["ciphertext"]), 0, openssl_cipher_iv_length($cipher));
        $cifradoSinIV = substr(base64_decode($_POST["ciphertext"]), openssl_cipher_iv_length($cipher));
        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);
        date_default_timezone_set('America/Asuncion');
        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $fecha_hora = $fecha . ' ' . $hora;
        $anulado = 10;
        $respuesta = $tramite->delete_tramites_ayuda($decifrado, $_SESSION["usuario_id"], $fecha_hora, $anulado);
        break;
}

?>