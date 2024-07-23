<?php
    /* TODO:Cadena de Conexion */
    require_once("../config/conexion.php");
    /* TODO:Clases Necesarias */
    require_once("../models/DocumentoPersonal.php");
    $documentoPersonal = new DocumentoPersonal();

    require_once("../models/Usuario.php");
    $usuario = new Usuario();

    $key="mi_key_secret";
    $cipher="aes-256-cbc";
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));

    /*TODO: opciones del controlador Documento Personal*/
    switch($_GET["op"]){

        /* TODO: Listado de documentos personales segun usuario,formato json para Datatable JS */
        case "listar_x_usu":
            $datos=$documentoPersonal->listar_dato_personal_x_usu($_SESSION["usuario_id"]);
            $data= Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array[] = $row["tipo_documento"];
                $sub_array[] = date("d/m/Y", strtotime($row["fecha"]));
                $ruta = "http://localhost:90/homesirepro/docs/documents/".$_SESSION["cedula"]."/"."personales/".$row["documento"];

                $cifrado = openssl_encrypt($row["dato_personal_id"], $cipher, $key, OPENSSL_RAW_DATA, $iv);
                $textoCifrado = base64_encode($iv . $cifrado);

                $sub_array[] = '<button title="Abrir documento" style="padding: 0;border: none;background: none;" type="button" data-ciphertext="'.$ruta.'" id="'.$textoCifrado.'" class="btn-open-pdf"><i class="glyphicon glyphicon-file" style="color:#2986cc; font-size:large; margin: 3px;" aria-hidden="true"></button></i>
                <button title="Editar documento" type="button" style="padding: 0;border: none;background: none;" data-ciphertext="'.$textoCifrado.'" id="'.$textoCifrado.'" class="btn-inline"><i  class="glyphicon glyphicon-edit" style="color:#6aa84f; font-size:large; margin: 3px;" aria-hidden="true"></i></button>
                <button title="Eliminar documento" type="button" style="padding: 0;border: none;background: none;" data-ciphertext="'.$textoCifrado.'" id="'.$textoCifrado.'" class="btn-delete-row"><i class="glyphicon glyphicon-trash" style="color:#e06666; font-size:large; margin: 3px;" aria-hidden="true"></i></button>';
                
                $data[] = $sub_array;
            }

            $results = array(
                "sEcho"=>1,
                "iTotalRecords"=>count($data),
                "iTotalDisplayRecords"=>count($data),
                "aaData"=>$data);
            echo json_encode($results);
            break;
        
        /* TODO: Listado de documentos personales,formato json para Datatable JS, filtro avanzado*/
        case "listar_filtro":
            if($_POST["fecha"] != ""){
                $_POST["fecha"] = date('Y-m-d', strtotime($_POST["fecha"]));
            }
            
            $datos=$documentoPersonal->filtrar_doc_personal($_POST["tipo_documento"],$_POST["fecha"]);
            $data= Array();
            foreach($datos as $row){

                $sub_array = array();
                $sub_array[] = $row["tipo_documento"];
                $sub_array[] = date("d/m/Y", strtotime($row["fecha"]));
                $ruta = "http://localhost:90/homesirepro/docs/documents/".$_SESSION["cedula"]."/"."personales/".$row["documento"];

                $cifrado = openssl_encrypt($row["dato_personal_id"], $cipher, $key, OPENSSL_RAW_DATA, $iv);
                $textoCifrado = base64_encode($iv . $cifrado);

                $sub_array[] = '<button title="Abrir documento" style="padding: 0;border: none;background: none;" type="button" data-ciphertext="'.$ruta.'" id="'.$textoCifrado.'" class="btn-open-pdf"><i class="glyphicon glyphicon-file" style="color:#2986cc; font-size:large; margin: 3px;" aria-hidden="true"></button></i>
                <button title="Editar documento" type="button" style="padding: 0;border: none;background: none;" data-ciphertext="'.$textoCifrado.'" id="'.$textoCifrado.'" class="btn-inline"><i  class="glyphicon glyphicon-edit" style="color:#6aa84f; font-size:large; margin: 3px;" aria-hidden="true"></i></button>
                <button title="Eliminar documento" type="button" style="padding: 0;border: none;background: none;" data-ciphertext="'.$textoCifrado.'" id="'.$textoCifrado.'" class="btn-delete-row"><i class="glyphicon glyphicon-trash" style="color:#e06666; font-size:large; margin: 3px;" aria-hidden="true"></i></button>';
                
                $data[] = $sub_array;
                
            }

            $results = array(
                "sEcho"=>1,
                "iTotalRecords"=>count($data),
                "iTotalDisplayRecords"=>count($data),
                "aaData"=>$data);
            echo json_encode($results);
            break;

        
        
            /* TODO: Insertar nuevo Documento Personal */
        case "insert":
            $doc1 = $_FILES['imagen']['tmp_name'];
            if($doc1 != ""){
                $ruta = "../docs/documents/".$_SESSION["cedula"]."/"."personales/";

                /* TODO: Preguntamos si la ruta existe, en caso no exista la creamos */
                if (!file_exists($ruta)) {
                    mkdir($ruta, 0777, true);
                }
                $fileExtension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
                $docNombre = $_SESSION["cedula"]."-".date('YmdHis')."-".
                $_POST["tipo_documento"].".".$fileExtension;
                $destino = $ruta.$docNombre;
                /* TODO: Movemos los archivos hacia la carpeta creada */
                move_uploaded_file($doc1,$destino);
                /* Se inserta el registro en la BD */
                $datos=$documentoPersonal->insert_doc_personal($_POST["usuario_id"],$_POST["tipo_documento"],
                $docNombre,$_POST["fecha"],$_POST["dato_adic"]);
            }
            echo $datos ? "Documento agregado" : "El documento no se pudo agregar.";
            break;

        /* TODO: Actualizamos el Documento Personal a cerrado y adicionamos una linea adicional */
        case "update":

            $iv_dec = substr(base64_decode($_POST["idEncrypted"]), 0, openssl_cipher_iv_length($cipher));
            $cifradoSinIV = substr(base64_decode($_POST["idEncrypted"]), openssl_cipher_iv_length($cipher));
            $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);

            $docNombre = basename($_FILES["imagen"]["name"]);
            $datos = "";
            if ($decifrado === false) {
                echo "Decryption failed with error: $error";
            } else {

                $doc1 = $_FILES['imagen']['tmp_name'];
                if($doc1 != "" && $_GET["img"] == 0){
                    $ruta = "../docs/documents/".$_SESSION["cedula"]."/"."personales/";

                    /* TODO: Preguntamos si la ruta existe, en caso no exista la creamos */
                    if (!file_exists($ruta)) {
                        mkdir($ruta, 0777, true);
                    }
                    $fileExtension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
                    $docNombre = $_SESSION["cedula"]."-".date('YmdHis')."-".
                    $_POST["tipo_documento"].".".$fileExtension;
                    $destino = $ruta.$docNombre;
                    /* TODO: Movemos los archivos hacia la carpeta creada */
                    move_uploaded_file($doc1,$destino);
                }
                /* Se inserta el registro en la BD */
                $datos = $documentoPersonal->update_doc_personal($decifrado,$_POST["usuario_id"],
                $_POST["tipo_documento"],$docNombre,$_POST["fecha"],$_POST["dato_adic"]);
            } 
            echo $datos ? "Documento agregado" : "El documento no se pudo agregar.";
            break;
        /* TODO: Mostrar informacion de Documento Personal en formato JSON para la vista */
        case "mostrar":
            
            $iv_dec = substr(base64_decode($_POST["doc_personal_id"]), 0, openssl_cipher_iv_length($cipher));
            $cifradoSinIV = substr(base64_decode($_POST["doc_personal_id"]), openssl_cipher_iv_length($cipher));
            $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);

            $datos=$documentoPersonal->mostrar($decifrado);
            $ruta = "../../docs/documents/".$_SESSION["cedula"]."/"."personales/";
            if(is_array($datos)==true and count($datos)>0){
                
                foreach($datos as $row)
                {
                    $output["dato_personal_id"] = $row["dato_personal_id"];
                    $output["usuario_id"] = $row["usuario_id"];
                    $output["tipo_documento"] = $row["tipo_documento"];
                    $output["imagenmuestra"] = $row["documento"];
                    $output["documento_ruta"] = $ruta.$row["documento"];
                    $output["dato_adic"] = $row["dato_adic"];

                    $output["fecha"] = $row["fecha"];
                }
                echo json_encode($output);
            }
            break;

        case "delete": 
            $iv_dec = substr(base64_decode($_POST["ciphertext"]), 0, openssl_cipher_iv_length($cipher));
            $cifradoSinIV = substr(base64_decode($_POST["ciphertext"]), openssl_cipher_iv_length($cipher));
            $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);
            
            $respuesta = $documentoPersonal->delete_doc_personal($decifrado, $_SESSION["usuario_id"]);
            echo $respuesta ? "Documento eliminado" : "El documento se pudo eliminar.";
            break;

        case "grafico":
            $datos=$documentoPersonal->get_documentos_grafico();  
            echo json_encode($datos);
            break;

        }
?>