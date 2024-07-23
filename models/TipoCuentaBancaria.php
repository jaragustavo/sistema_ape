<?php
  require_once("../config/conexion.php");
    class TipoCuentaBancaria extends Conectar{

            public static function get_tipoCuentaBancaria($id){
            $conectar=parent::Conexion();
            $sql="SELECT *
            FROM tipos_cuentas_bancarias WHERE id = $id";
        
            $query=$conectar->prepare($sql);
            $query->execute();
            $conectar = null;
            return $query->fetch(PDO::FETCH_ASSOC);
        }

       
    }
 
?>