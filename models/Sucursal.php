<?php
    class Sucursal extends Conectar{
        /* TODO: Listar Registros */
        public function get_sucursal_x_aso_id($aso_id){
            $conectar=parent::Conexion();
            // Preparar la llamada a la función almacenada
            $stmt = $conectar->prepare("SELECT * FROM SP_L_SUCURSAL_01(:aso_id)");
            $stmt->bindParam(':aso_id', $aso_id, PDO::PARAM_INT);
            // Ejecutar la consulta
            $stmt->execute();
            // Obtener todos los resultados
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        /* TODO: Listar Registro por ID en especifico */
        public function get_sucursal_x_suc_id($suc_id){
            $conectar=parent::Conexion();
            $sql="SP_L_SUCURSAL_02 ?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$suc_id);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        /* TODO: Eliminar o cambiar estado a eliminado */
        public function delete_sucursal($suc_id){
            $conectar=parent::Conexion();
            $sql="SP_D_SUCURSAL_01 ?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$suc_id);
            $query->execute();
        }

        /* TODO: Registro de datos */
        public function insert_sucursal($aso_id,$nombre){
            $conectar=parent::Conexion();
            $sql="SP_I_SUCURSAL_01 ?,?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$aso_id);
            $query->bindValue(2,$nombre);
            $query->execute();
        }

        /* TODO:Actualizar Datos */
        public function update_sucursal($suc_id,$aso_id,$nombre){
            $conectar=parent::Conexion();
            $sql="SP_U_SUCURSAL_01 ?,?,?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$suc_id);
            $query->bindValue(2,$aso_id);
            $query->bindValue(3,$nombre);
            $query->execute();
        }
    }
?>