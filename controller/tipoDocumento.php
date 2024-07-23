<?php
    /* TODO:Cadena de Conexion */
    require_once("../config/conexion.php");
    /* TODO:Modelo Categoria */
    require_once("../models/TipoDocumento.php");
    $tipoDocumento = new TipoDocumento();

    /*TODO: opciones del controlador Tipo Documento*/
    switch($_GET["op"]){
        /* TODO: Guardar y editar, guardar si el campo cat_id esta vacio */
        case "guardaryeditar":
            /* TODO:Actualizar si el campo cat_id tiene informacion */
            if(empty($_POST["tipo_documento"])){       
                $tipoDocumento->insert_tipo_documento($_POST["documento"]);     
            }
            else {
                $tipoDocumento->update_tipo_documento($_POST["tipo_documento_id"],$_POST["documento"]);
            }
            break;

        /* TODO: Listado de tipo de documento segun formato json para el datatable */
        case "listar":
            $datos=$tipoDocumento->get_tipo_documento();
            $data= Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array[] = $row["cat_nom"];
                $sub_array[] = '<button type="button" onClick="editar('.$row["tipo_documento_id"].');"  id="'.$row["tipo_documento_id"].'" class="btn btn-inline btn-warning btn-sm ladda-button"><i class="fa fa-edit"></i></button>';
                $sub_array[] = '<button type="button" onClick="eliminar('.$row["tipo_documento_id"].');"  id="'.$row["tipo_documento_id"].'" class="btn btn-inline btn-danger btn-sm ladda-button"><i class="fa fa-trash"></i></button>';
                $data[] = $sub_array;
            }

            $results = array(
                "sEcho"=>1,
                "iTotalRecords"=>count($data),
                "iTotalDisplayRecords"=>count($data),
                "aaData"=>$data);
            echo json_encode($results);
            break;

        /* TODO: Actualizar estado a 0 segun id de categoria */
        case "eliminar":
            $tipoDocumento->delete_tipo_documento($_POST["tipo_documento_id"]);
            break;

        /* TODO: Mostrar en formato JSON segun cat_id */
        case "mostrar";
            $datos=$tipoDocumento->get_tipodocumento_x_id($_POST["tipo_documento_id"]);  
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row)
                {
                    $output["tipo_documento_id"] = $row["tipo_documento_id"];
                    $output["documento"] = $row["documento"];
                }
                echo json_encode($output);
            }
            break;

        /* TODO: Formato para llenar combo en formato HTML */
        case "combo":
            
            $datos = $tipoDocumento->get_tipodocumento_x_tipo_id('P');
            $html="";
            $html.="<option label='Seleccionar'></option>";
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row)
                {
                    $html.= "<option value='".$row['tipo_documento_id']."'>".$row['documento']."</option>";
                }
                echo $html;
            }
            break;

        case "comboAcademicos":
            
            $datos = $tipoDocumento->get_tipodocumento_x_tipo_id('A');
            $html="";
            $html.="<option label='Seleccionar'></option>";
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row)
                {
                    $html.= "<option value='".$row['tipo_documento_id']."'>".$row['documento']."</option>";
                }
                echo $html;
            }
            break;
    }
?>