<?php
    /* TODO: Llamando Clases */
    require_once("../config/conexion.php");
    require_once("../models/EstablecimientoSalud.php");
    /* TODO: Inicializando clase */
    $establecimientoSalud = new EstablecimientoSalud();

    switch($_GET["op"]){
        case "mostrar":
            // Leer el cuerpo de la solicitud POST
            $input = file_get_contents("php://input");
            $data = json_decode($input, true);
    
            if (isset($data['id'])) {
                
                $datos = $establecimientoSalud->get_establecimiento_salud($data['id']);
                header('Content-Type: application/json');
                echo json_encode($datos);
            } else {
                // Manejar el caso en que el ID no esté presente en los datos POST
                echo json_encode(['error' => 'ID no proporcionado']);
            }
            break;
    }
?>