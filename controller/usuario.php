<?php
    /* TODO: Llamando Clases */
    require_once("../config/conexion.php");
    require_once("../models/Usuario.php");
    require_once("../models/Menu.php");
    /* TODO: Inicializando clase */
    $usuario = new Usuario();
    $menu = new Menu();

    switch($_GET["op"]){

        case "guardarFotoCi":

            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    
                $doc1 = $_FILES['file']['tmp_name'];
    
                if ($doc1 != "") {
    
                  
                    $ruta = "../docs/documents/" . $_SESSION["cedula"] . "/" . "foto_ci/";
                    
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
                     
                        $usuario->update_foto_ci($destino, $_SESSION['usuario_id'], $fecha_hora);
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

            case "guardarFotoRegistro":

                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
        
                    $doc1 = $_FILES['file']['tmp_name'];
        
                    if ($doc1 != "") {
        
                      
                        $ruta = "../docs/documents/" . $_SESSION["cedula"] . "/" . "foto_registro_profesional/";
                        
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
                         
                            $usuario->update_foto_registro($destino, $_SESSION['usuario_id'], $fecha_hora);
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
        

        /* TODO: Listado de registros formato JSON para Datatable JS */
        case "listar":
            $datos=$usuario->get_usuario_x_suc_id($_POST["suc_id"]);
            $data=Array();
            foreach($datos as $row){
                $sub_array = array();

                // if ($row["USU_IMG"] != ''){
                //     $sub_array[] =
                //     "<div class='d-flex align-items-center'>" .
                //         "<div class='flex-shrink-0 me-2'>".
                //             "<img src='../../assets/usuario/".$row["USU_IMG"]."' alt='' class='avatar-xs rounded-circle'>".
                //         "</div>".
                //     "</div>";
                // }else{
                //     $sub_array[] =
                //     "<div class='d-flex align-items-center'>" .
                //         "<div class='flex-shrink-0 me-2'>".
                //             "<img src='../../assets/usuario/no_imagen.png' alt='' class='avatar-xs rounded-circle'>".
                //         "</div>".
                //     "</div>";
                // }
                $sub_array[] = $row["email"];
                $sub_array[] = $row["nombre"];
                $sub_array[] = $row["apellido"];
                $sub_array[] = $row["ci"];
                $sub_array[] = $row["password"];
                $sub_array[] = $row["fech_crea"];
                $sub_array[] = '<button type="button" onClick="editar('.$row["id"].')" id="'.$row["id"].'" class="btn btn-warning btn-icon waves-effect waves-light"><i class="ri-edit-2-line"></i></button>';
                $sub_array[] = '<button type="button" onClick="eliminar('.$row["id"].')" id="'.$row["id"].'" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-delete-bin-5-line"></i></button>';
                $data[] = $sub_array;
            }

            $results = array(
                "sEcho"=>1,
                "iTotalRecords"=>count($data),
                "iTotalDisplayRecords"=>count($data),
                "aaData"=>$data);
            echo json_encode($results);
            break;

        /* TODO:Mostrar informacion de registro segun su ID */
        case "mostrar":
            $datos=$usuario->get_usuario_x_usu_id($_POST["id"]);
           
            if (is_array($datos)==true and count($datos)>0){
                foreach($datos as $row){
                    $output["id"] = $row["id"];
                    $output["suc_id"] = $row["suc_id"];
                    $output["nombre"] = $row["nombre"];
                    $output["apellido"] = $row["apellido"];
                    $output["email"] = $row["email"];
                    $output["direccion_domicilio"] = $row["direccion_domicilio"];
                    $output["ci"] = $row["ci"];
                    $output["telefono"] = $row["telefono"];
                    $output["password"] = $row["password"];
                    $output["foto_ci"] = $row["foto_ci"];
                    $output["foto_registro_profesional"] = $row["foto_registro_profesional"];
                    error_log($row["nombre"]);
                }
                echo json_encode($output);
            }
            break;

        /* TODO: Cambiar Estado a 0 del Registro */
        case "eliminar";
            $usuario->delete_usuario($_POST["id"]);
            break;
        /* TODO:Actualizar contraseña del Usuario */
        case "actualizar";
            $usuario->update_usuario_pass($_POST["id"],$_POST["password"]);
            break;
        case "cargarMenu";
            $permisos = array();
            $datos = $menu->get_menu_x_permisos_id($_SESSION["usuario_id"]);
            foreach ($datos as $row) {
                array_push($permisos, $row['nombre_permiso']);
            }

            in_array('Curriculum Virtual', $permisos)?$_SESSION['Curriculum Virtual']=1:$_SESSION['Curriculum Virtual']=0;
            in_array('Investigaciones', $permisos)?$_SESSION['Investigaciones']=1:$_SESSION['Investigaciones']=0;
            in_array('Documentos personales', $permisos)?$_SESSION['Documentos personales']=1:$_SESSION['Documentos personales']=0;
            
            // error_log('################## '.count($datos));
            
            break;
        case "grafico";
            $datos=$usuario->get_usuario_grafico($_SESSION["usuario_id"]);  
            echo json_encode($datos);
            break;

        case "cantidadesTramites":
            $datos=$usuario->get_cantidades_tramites($_SESSION["usuario_id"]);  
            if(is_array($datos)==true and count($datos)>0){
                
                foreach($datos as $row)
                {
                    $output["lbltramitesrealizados"] = $row["cantidad_tramites"];
                }
                echo json_encode($output);
            }
            break;

        case "cantidadesTramitesSistema":
            $datos=$usuario->get_cantidades_tramites_sistema();  
            if(is_array($datos)==true and count($datos)>0){
                
                foreach($datos as $row)
                {
                    $output["cantidad_tramites"] = $row["cantidad_tramites"];
                }
                echo json_encode($output);
            }
            break;

        case "cantidadUsuarios":
            $datos=$usuario->get_cantidad_usuarios();  
            if(is_array($datos)==true and count($datos)>0){
                
                $output["cantidad_usuarios"] = $datos["cantidad_usuarios"];

                echo json_encode($output);
            }
            break;

        case "cantidadPublicaciones":
            $datos=$usuario->get_cantidad_publicaciones();  
            if(is_array($datos)==true and count($datos)>0){
                
                $output["cantidad_publicaciones"] = $datos["cantidad_publicaciones"];

                echo json_encode($output);
            }
            break;

        case "cantidadesReposos":
            $datos=$usuario->get_cantidades_reposos($_SESSION["cedula"]);  
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row)
                {
                    $output["lblreposos"] = $row["cant_reposos"];
                }
                echo json_encode($output);
            }
            break;

        case "updateDatosPersonales":
            date_default_timezone_set('America/Asuncion');

            $fecha = date('Y-m-d');
            $hora = date('H:i:s');
            $fecha_hora = $fecha.' '.$hora;
            try {
                $datos = array(
                    "fecha_hora"=>$fecha_hora,
                    "usuario_id"=>$_SESSION["usuario_id"],
              //      "nombre"=>$_POST["nombre"],
         //           "apellido"=>$_POST["apellido"],
                    "email"=>$_POST["email"],
          //          "fecha_nacimiento"=>$_POST["fecha_nacimiento"],
                    "direccion_domicilio"=>$_POST["direccion_domicilio"],
                    "telefono"=>$_POST["telefono"],
                    "ciudad_id"=>$_POST["ciudad_id"],
                    "departamento_id"=>$_POST["departamento_id"],
                    "estado_civil"=>$_POST["estado_civil"],
                    "cantidad_hijo"=>$_POST["cantidad_hijo"],
                    "contacto"=>$_POST["contacto"]

                );
                $resultado = $usuario->update_usuario($datos);
            }
            catch(Exception $e){
                echo $e;
            }

         break;
        case "mostrarDatosPersonales":
            $datos = $usuario->get_datos_personales($_SESSION['usuario_id']);
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
        
        case "comboEstablecimientosSalud":
            $datos = $usuario->get_establecimientos_salud();
            $html="";
            $html.="<option label='Seleccionar'></option>";
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row)
                {
                    $html.= "<option value='".$row['establecimiento_id']."'>".$row['nombre_establecimiento']."</option>";
                }
                echo $html;
            }
            break;
        case "comboProfesiones":
            $datos = $usuario->get_profesiones();
            $html="";
            $html.="<option label='Seleccionar'></option>";
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row)
                {
                    $html.= "<option value='".$row['profesion_id']."'>".$row['nombre_profesion']."</option>";
                }
                echo $html;
            }
            break;
         case "updateDatosProfesionales":

                date_default_timezone_set('America/Asuncion');
                $fecha_hora = date('Y-m-d H:i:s');
                
                try {
                    // Recibir los datos del formulario
                    $profesion_id = $_POST["profesion_id"];
                    $jsonDatosProfesionales = $_POST["jsonDatosProfesionales"];
                    $jsonDatosProfesionalesArray = json_decode($jsonDatosProfesionales, true);
        
                    // Aquí deberías tener una instancia de tu modelo o clase Usuario
                    $usuario = new Usuario(); // Asume que tienes una clase Usuario para manejar la lógica de la base de datos
        
                    // Agregar los datos adicionales al JSON si es necesario
                    $jsonDatosProfesionalesArray['lugar_egreso'] = $_POST["lugar_egreso"];
                    $jsonDatosProfesionalesArray['anio_egreso'] = $_POST["anio_egreso"];
          
                    // Armar el array con los datos a actualizar en la base de datos
                    $datos = array(
                        "fecha_hora" => $fecha_hora,
                        "usuario_id" => $_SESSION["usuario_id"],
                        "profesion_id" => $profesion_id,
                        "jsonDatosProfesionales" => json_encode($jsonDatosProfesionalesArray, JSON_UNESCAPED_UNICODE) // Convertir de nuevo a JSON
                    );
        
                    // Actualizar los datos profesionales del usuario
                    $resultado = $usuario->update_datos_profesionales($datos);
        
                    // Asumiendo que update_datos_profesionales retorna "ok" si se actualiza correctamente
                    if ($resultado === "ok") {
                        echo "ok";
                    } else {
                        echo "error";
                    }
        
                } catch (Exception $e) {
                    echo $e->getMessage(); // Manejo de errores, puedes personalizar según tu aplicación
                }
        
                break;
          case "mostrarDatosProfesionales":
            $datos = $usuario->get_datos_profesionales($_SESSION['usuario_id']);
            if (is_array($datos) && count($datos) > 0) {
                $output = array();
        
                foreach ($datos as $row) {
                    $item = array();
        
                    // Decode the JSON string
                    $row['jsonDatosProfesionales'] = json_decode($row['jsonDatosProfesionales'], true);
        
                    // Iterate through each key-value pair in $row
                    foreach ($row as $key => $value) {
                        // Add each key-value pair to the $item array
                        $item[$key] = $value;
                    }
        
                    // Append each item to the output array
                    $output[] = $item;
                }
        
                echo json_encode($output);
            } else {
                echo json_encode([]); // Return an empty array if there are no data
            }
            break;
    }
    
function getLocalDateTime()
{
    date_default_timezone_set('America/Asuncion');

    $fecha = date('Y-m-d');
    $hora = date('H:i:s');
    return $fecha . ' ' . $hora;
}
?>