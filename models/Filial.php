<?php
  require_once("../config/conexion.php");
    class Filial extends Conectar{

            public static function get_filial($id){
            $conectar=parent::Conexion();
            $sql="SELECT *
            FROM filiales WHERE id = $id";
        
            $query=$conectar->prepare($sql);
            $query->execute();
            $conectar = null;
            return $query->fetch(PDO::FETCH_ASSOC);
        }

       
    }
 
?>