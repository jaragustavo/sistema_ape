<?php
  require_once("../config/conexion.php");
    class Banco extends Conectar{

            public static function get_banco($id){
            $conectar=parent::Conexion();
            $sql="SELECT *
            FROM bancos WHERE id = $id";
        
            $query=$conectar->prepare($sql);
            $query->execute();
            $conectar = null;
            return $query->fetch(PDO::FETCH_ASSOC);
        }

       
    }
 
?>