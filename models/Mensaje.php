<?php
class Mensaje extends Conectar
{

    /* TODO:Trae los mensajes nuevos que tiene el usuario en la sección de Mensajes del header */
    public function get_mensajes_x_usu($usu_id)
    {
        $conectar = parent::conexion();
        $sql = " select mensajes.id as mensaje_id, mensaje, usuario_envio_id as remitente_id,
            usuarios.nombre || ' ' || usuarios.apellido AS nombre_remitente,
            ind_estado, mensajes.fecha_crea as fecha,
            COUNT(*) OVER () AS cant_mensajes_nuevos
            from mensajes
            join usuarios on usuarios.id = mensajes.usuario_envio_id
            where usuario_destino_id = $usu_id
            AND mensajes.activo = true
            AND ind_estado = 'No leido';";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        $conectar = null;
        return $sql->fetchAll();
    }

    public static function get_user_info($usuario_id){
        $conectar = parent::conexion();
        $sql = "SELECT nombre || ' ' || apellido FROM usuarios
        WHERE id = $usuario_id;";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        $conectar = null;
        return $sql->fetch();
    }

    /* TODO:Trae el último mensaje (recibido o enviado) de cada chat que tiene el usuario */
    public function get_chats_x_usuario($usu_id)
    {
        $conectar = parent::conexion();
        $sql = "SELECT * FROM (SELECT DISTINCT ON (chat_id)
            mensaje_id, mensaje, chat_id,
            nombre_chat, (SELECT ci FROM usuarios WHERE id=chat_id) AS cedula_chat,
            ind_estado, foto_perfil, fecha, 
            conectado,
            hora, (SELECT COUNT(*) FROM mensajes 
                WHERE usuario_envio_id = chat_id 
                    AND ind_estado = 'No leido'
                    AND usuario_destino_id = $usu_id) AS cant_mensajes_nuevos_x_chat
        FROM (
            SELECT 
                mensajes.id AS mensaje_id,
                mensaje,
                COALESCE(NULLIF(mensajes.usuario_envio_id, $usu_id), mensajes.usuario_destino_id) AS chat_id,
                (SELECT nombre || ' ' || apellido FROM usuarios WHERE id = COALESCE(NULLIF(mensajes.usuario_envio_id, $usu_id), mensajes.usuario_destino_id)) AS nombre_chat,
                ind_estado,
                (SELECT foto_perfil FROM usuarios WHERE id = COALESCE(NULLIF(mensajes.usuario_envio_id, $usu_id), mensajes.usuario_destino_id)) as foto_perfil,
                mensajes.fecha_crea AS fecha,
                (SELECT conectado FROM usuarios WHERE id = COALESCE(NULLIF(mensajes.usuario_envio_id, $usu_id), mensajes.usuario_destino_id)) as conectado,
                TO_CHAR(mensajes.fecha_crea, 'HH24:MI') AS hora
            FROM mensajes
            JOIN usuarios ON usuarios.id = mensajes.usuario_envio_id OR usuarios.id = mensajes.usuario_destino_id
            WHERE (usuario_envio_id = $usu_id OR usuario_destino_id = $usu_id)
            AND mensajes.activo = true
            ORDER BY COALESCE(NULLIF(mensajes.usuario_envio_id, $usu_id), mensajes.usuario_destino_id), mensajes.fecha_crea DESC
        ) AS Dato
        ORDER BY chat_id, fecha DESC, mensaje_id DESC
        ) Dato ORDER BY fecha DESC";
                
        $sql = $conectar->prepare($sql);
        $sql->execute();
        $conectar = null;
        return $sql->fetchAll();
    }

    /* TODO:Trae toda la conversación que el usuario logueado tiene con otros */
    public function get_conversacion_x_usuario($chat_id, $usuario_id)
    {
        $conectar = parent::conexion();
        $sql = "SELECT 
                    mensajes.id AS mensaje_id,
                    mensaje,
                    usuario_envio_id AS remitente_id,
                    usuarios.nombre || ' ' || usuarios.apellido AS nombre_remitente,
                    ind_estado,
                    foto_perfil, conectado, fecha_conexion,
                    mensajes.fecha_crea AS fecha,
                    TO_CHAR(mensajes.fecha_crea, 'HH24:MI') AS hora,
                    (select ci from usuarios where id =$usuario_id) as cedula_usuario, 
                    (select ci from usuarios where id =$chat_id) as cedula_chat,
                    (select nombre || ' ' || apellido from usuarios where id =$chat_id) as nombre_destinatario
                FROM mensajes
                JOIN usuarios ON usuarios.id = mensajes.usuario_envio_id
                WHERE (usuario_destino_id = $chat_id or usuario_envio_id = $chat_id)
                AND (usuario_destino_id = $usuario_id or usuario_envio_id = $usuario_id)
                AND mensajes.activo = true
                ORDER BY fecha ASC;";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        $conectar = null;
        return $sql->fetchAll();
    }

    public static function get_datos_conexion($chat_id){
        $conectar = parent::conexion();
        $sql ="SELECT conectado, fecha_conexion
            FROM usuarios
            WHERE id = $chat_id;";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        $conectar = null;
        return $sql->fetch();
    }
    
    public static function get_usuarios($usuario_id)
    {
        $conectar = parent::conexion();
        $sql = "SELECT 
                    id AS usuario_buscado_id,
                    nombre || ' ' || apellido AS usuario_nombre,
                    foto_perfil, conectado, fecha_conexion
                FROM usuarios
                WHERE activo = true
                AND id <> $usuario_id;
                ";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        $conectar = null;
        return $sql->fetchAll();
    }


    /* TODO: Actualizar estado de la notificacion luego de ser leído */
    public function update_mensaje_estado($usuario_id, $mensaje_id, $nuevo_estado)
    {
        $conectar = parent::conexion();
        $sql = "UPDATE mensajes SET ind_estado='$nuevo_estado' 
            WHERE usuario_destino_id = $usuario_id;";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        $conectar = null;
        return $sql->fetchAll();
    }

    /* TODO: Crea un nuevo mensaje remitido por el usuario logueado */
    public function enviar_mensaje($usuario_id, $destinatario_id, $nuevo_mensaje, $fecha_hora)
    {
        $conectar = parent::conexion();
        $sql = "INSERT INTO public.mensajes(
                mensaje, tipo_mensaje, usuario_envio_id, usuario_destino_id, ind_estado, fecha_crea, usu_crea)
               VALUES ('$nuevo_mensaje', 2, $usuario_id, $destinatario_id, 'No leido', '$fecha_hora'::timestamp, '$usuario_id');
            ";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        $sql1 = "SELECT mensajes.id AS mensaje_id,
                    mensaje,
                    usuario_envio_id AS remitente_id,
                    usuarios.nombre || ' ' || usuarios.apellido AS nombre_remitente,
                    ind_estado,
                    usuarios.foto_perfil as foto_perfil,
                    mensajes.fecha_crea AS fecha,
                    TO_CHAR(mensajes.fecha_crea, 'HH24:MI') AS hora,
                    (select ci from usuarios where id =$usuario_id) as cedula_usuario, 
                    (select ci from usuarios where id =$destinatario_id) as cedula_chat,
                    (select nombre || ' ' || apellido from usuarios where id =$destinatario_id) as nombre_destinatario
                FROM mensajes
                JOIN usuarios ON usuarios.id = mensajes.usuario_envio_id
                AND mensajes.id = (select last_value from mensajes_id_seq)
                AND mensajes.activo = true
                ORDER BY fecha ASC;
            ";
        $sql1 = $conectar->prepare($sql1);
        $sql1->execute();
        $conectar = null;
        return $sql1->fetchAll(pdo::FETCH_ASSOC);
    }

}
?>