<?php
    class Notificacion extends Conectar{

        /* TODO:Todos los registros */
        public static function get_notificaciones_x_usu($usuario_id){
            $conectar= parent::conexion();
            $sql="SELECT 
                user_crea AS notificante,
                (SELECT nombre || ' ' || apellido FROM usuarios WHERE id = notificaciones.user_crea) AS nombre_notificante,
                mensaje_notificacion,
                fecha_crea AS fecha_notificacion,
                TO_CHAR(fecha_crea, 'DD Mon YYYY') AS fecha_formato_doc,
                TO_CHAR(fecha_crea, 'HH24:MI') AS hora_formato_doc,
                (SELECT count(*) from notificaciones where usuario_notificado_id = $usuario_id
                and leido = false) AS no_leidas
            FROM 
                notificaciones 
            WHERE 
                usuario_notificado_id = $usuario_id
                AND activo = true;";
            $sql=$conectar->prepare($sql);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

        /* TODO: Actualizar estado de la notificacion luego de ser mostrado */
        public function update_leido_notificaciones($usuario_id){
            $conectar= parent::conexion();
            $sql="UPDATE notificaciones SET leido=true WHERE usuario_notificado_id = $usuario_id;";
            $sql=$conectar->prepare($sql);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

        /* TODO: Actualizar notificacion luego de ser leido */
        public function update_notificacion_estado_read($not_id){
            $conectar= parent::conexion();
            $sql="UPDATE tm_notificacion SET est=0 WHERE not_id = ?;";
            $sql=$conectar->prepare($sql);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

    }
?>