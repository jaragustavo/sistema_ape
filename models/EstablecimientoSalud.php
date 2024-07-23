<?php
  require_once("../config/conexion.php");
    class EstablecimientoSalud extends Conectar{

            public static function get_establecimiento_salud($id){
            $conectar=parent::Conexion();
            $sql="SELECT *
            FROM establecimientos_salud WHERE id = $id";
        
            $query=$conectar->prepare($sql);
            $query->execute();
            $conectar = null;
            return $query->fetch(PDO::FETCH_ASSOC);
        }

       
    }
 
?>