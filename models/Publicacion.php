<?php
class Publicacion extends Conectar
{

    /*=============================================
    CREAR PUBLICACIÓN
    =============================================*/
    public function insertar_publicacion($datos)
    {
        try {
            $db = parent::Conexion();
            $respuesta = $db;
            $db->beginTransaction();

            $sql = "INSERT INTO public.publicaciones(
                    texto, fecha_crea, 
                    fecha_mod, user_crea, 
                    user_mod, activo, publico)
                    VALUES ('" . $datos['texto_publicacion'] . "', '" . $datos['fecha_crea'] . "'::timestamp, 
                    '" . $datos['fecha_crea'] . "'::timestamp, " . $datos['usuario_id'] . ", 
                    " . $datos['usuario_id'] . ",  " . $datos['activo'] . ",  " . $datos['publico'] . ");";
            $db->exec($sql);

            /********************************* 
             * INSERCIÓN DE ARCHIVOS ADJUNTOS
             **********************************/
            $sql = "select currval( 'publicaciones_id_seq' )::BIGINT;";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetch();
            $publicacion_id = $data['0'];
            $filePath = '../docs/documents/' . $datos['cedula_user'] . '/' . "publicaciones/" . $datos['folder_name'] . '/';
            $nombres_archivos = Publicacion::buscarArchivos($filePath);

            if (is_array($nombres_archivos)) {
                foreach ($nombres_archivos as $archivo) {
                    $url = "../" . $filePath . $archivo;
                    $sql = "INSERT INTO public.publicaciones_adjuntos(
                            publicacion_id, url_documento_adjunto, 
                            fecha_crea, fecha_mod, 
                            user_crea, user_mod, activo)
                            VALUES ($publicacion_id, '$url', 
                            '" . $datos['fecha_crea'] . "'::timestamp, '" . $datos['fecha_crea'] . "'::timestamp,
                            " . $datos['usuario_id'] . ", " . $datos['usuario_id'] . ", " . $datos['activo'] . ");";
                    $db->exec($sql);
                }
            }

        } catch (Exception $e) {
            $db->rollBack();

            $men = str_replace('SQLSTATE[P0001]: Raise exception: 7 ERROR:', '', $e->getMessage());
            error_log($men . ' ' . $sql);
            echo $men . ' ' . $sql;

            return "error";

        }
        if ($respuesta === $db) {
            $db->commit();
            $db = null;
            return "ok";

        }

    }

    public function delete_publicacion($fecha_hora, $usuario_id, $publicacion_id)
    {
        try {
            $db = parent::Conexion();
            $respuesta = $db;
            $db->beginTransaction();
            $sql = "UPDATE public.publicaciones
            SET fecha_mod='$fecha_hora'::timestamp, user_mod=$usuario_id, activo=false
            WHERE id =$publicacion_id;";
            $db->exec($sql);

            $sql = "UPDATE public.publicaciones_adjuntos
            SET fecha_mod='$fecha_hora'::timestamp, user_mod=$usuario_id, activo=false
            WHERE publicacion_id =$publicacion_id;";
            $db->exec($sql);

        } catch (Exception $e) {
            $db->rollBack();

            $men = str_replace('SQLSTATE[P0001]: Raise exception: 7 ERROR:', '', $e->getMessage());
            error_log($men . ' ' . $sql);
            echo $men . ' ' . $sql;

            return "error";

        }
        if ($respuesta === $db) {
            $db->commit();
            $db = null;
            return "ok";

        }
    }

    /*=============================================
    OBTENER NOMBRES ARCHIVOS
    =============================================*/
    public function buscarArchivos($filePath)
    {
        $filesName = array();
        $i = 0;
        error_log($filePath);
        foreach (glob($filePath . '/*.*') as $file) {
            $filesName[$i] = basename($file);
            $i++;
        }
        return $filesName;
    }
    /*=============================================
    TRAER PUBLICACIONES DEL USUARIO EN SU PERFIL
    =============================================*/
    public static function get_publicaciones_x_usuario($usuario_id)
    {
        $conectar = parent::conexion();
        $sql = "SELECT id as publicacion_id, texto, 
            fecha_mod as fecha_publicacion, publico,
            (select foto_perfil from usuarios where id = $usuario_id) as foto_perfil,
            (SELECT COUNT(*) from publicaciones_adjuntos where publicacion_id = publicaciones.id) AS adjuntos
            FROM publicaciones
            WHERE user_crea = $usuario_id
            AND activo = true
            ORDER BY fecha_mod DESC;";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        return $resultado = $sql->fetchAll();
    }

    /*=============================================
    TRAER PUBLICACIONES DE TODOS LOS USUARIOS DEL SISTEMA
    =============================================*/
    public static function get_publicaciones()
    {
        $conectar = parent::conexion();
        $sql = "SELECT id as publicacion_id, texto,
            user_crea AS publicante_id, 
			(SELECT nombre || ' ' || apellido FROM usuarios where id = publicaciones.user_crea) AS publicante,
			(SELECT foto_perfil FROM usuarios where id = publicaciones.user_crea) AS publicante_foto_perfil,
			(SELECT ci FROM usuarios where id = publicaciones.user_crea) AS publicante_ci,
            fecha_mod as fecha_publicacion, publico,
            (SELECT COUNT(*) from publicaciones_adjuntos where publicacion_id = publicaciones.id) AS adjuntos
            FROM publicaciones
            WHERE activo = true
            ORDER BY fecha_mod DESC;";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        return $sql->fetchAll();
    }

    /*=============================================
    TRAER ADJUNTOS POR CADA PUBLICACIÓN
    =============================================*/
    public static function get_adjuntos_x_publicacion($publicacion_id)
    {
        $conectar = parent::conexion();
        $sql = "SELECT url_documento_adjunto AS url_doc
            FROM publicaciones_adjuntos
            WHERE publicacion_id = $publicacion_id;";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        return $resultado = $sql->fetchAll();
    }

    /*=============================================
    BUSCADOR DE USUARIOS EN EL SISTEMA
    =============================================*/
    public static function get_usuarios()
    {
        $conectar = parent::conexion();
        // $sql = "SELECT id AS usuario_buscado_id,
        //     nombre || ' ' || apellido AS usuario_nombre 
        //     FROM usuarios
        //     where nombre ilike '$usuario_buscado%'
        //     OR apellido ilike '$usuario_buscado%';";
        $sql = "SELECT id AS usuario_buscado_id,
            nombre || ' ' || apellido AS usuario_nombre,
            CASE
                WHEN usuarios.foto_perfil = null OR usuarios.foto_perfil = '' 
                THEN 'assets/assets-main/images/icons/user2.png'
                ELSE usuarios.foto_perfil
            END AS foto_perfil,
            FROM usuarios
            where activo = true;";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        return $resultado = $sql->fetchAll();
    }

    public static function get_info_perfil($usuario_id)
    {
        $conectar = parent::conexion();
        $sql = "SELECT ci AS usuario_ci,
            nombre || ' ' || apellido AS usuario_perfil_nombre,
            foto_perfil           
            FROM usuarios
            where id = $usuario_id;";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        return $resultado = $sql->fetchAll();
    }

    public static function update_like_post($publicacion_id, $fecha_hora, $usuario_id)
    {
        try {
            $conectar = parent::conexion();
            $conectar->beginTransaction();
            $sql = "SELECT activo      
                FROM publicaciones_likes
                where user_crea = $usuario_id
                AND publicacion_id = $publicacion_id;";
            $sql = $conectar->prepare($sql);
            $sql->execute();
            $data = $sql->fetch();
            if (!$data) {
                $sql = "INSERT INTO public.publicaciones_likes(
                        publicacion_id, fecha_crea, 
                        fecha_mod, user_crea, 
                        user_mod, activo)
                        VALUES ($publicacion_id, '$fecha_hora'::timestamp, 
                        '$fecha_hora'::timestamp, $usuario_id, 
                        $usuario_id, true);
                    ";
                $sql = $conectar->prepare($sql);
                $sql->execute();
            } else {
                if ($data[0]) {
                    $sql = "UPDATE public.publicaciones_likes
                    SET fecha_mod='$fecha_hora'::timestamp, activo=false
                    WHERE user_crea = $usuario_id AND
                    publicacion_id = $publicacion_id;
                    ";
                    $sql = $conectar->prepare($sql);
                    $sql->execute();
                } else {
                    $sql = "UPDATE public.publicaciones_likes
                    SET fecha_mod='$fecha_hora'::timestamp, activo=true
                    WHERE user_crea = $usuario_id AND
                    publicacion_id = $publicacion_id;
                    ";
                    $sql = $conectar->prepare($sql);
                    $sql->execute();
                }

            }
        } catch (Exception $e) {
            $conectar->rollBack();

            $men = str_replace('SQLSTATE[P0001]: Raise exception: 7 ERROR:', '', $e->getMessage());
            error_log($men . ' ' . $sql);

            return "error";

        }
        $conectar->commit();
        return "ok";
    }

    public static function count_likes_comments_x_publicacion($publicacion_id, $usuario_id)
    {
        $conectar = parent::conexion();
        $sql = "SELECT count(id) AS likes,
            (SELECT id FROM publicaciones_likes 
            WHERE publicacion_id = '$publicacion_id'
            AND user_crea = $usuario_id
            AND activo = true) AS me_gusta,
            (SELECT count(id) FROM publicaciones_comentarios
            WHERE publicacion_id = '$publicacion_id') AS comentarios
            FROM publicaciones_likes
            where publicacion_id = '$publicacion_id'
            AND activo = true;";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        $data = $sql->fetch();
        return $data;
    }

    public static function follow_user($usuario_seguido_ci, $fecha_hora, $usuario_id)
    {
        try {
            $conectar = parent::conexion();
            $conectar->beginTransaction();
            $sql = "SELECT usuarios.id, seguidores.activo
            FROM seguidores
            JOIN usuarios on usuarios.id = seguidores.seguido_id
            WHERE seguido_id = (SELECT id FROM usuarios WHERE ci = '$usuario_seguido_ci')
            AND seguidores.user_crea = $usuario_id;";
            $sql = $conectar->prepare($sql);
            $sql->execute();
            $seguido = $sql->fetch();
            if (!is_array($seguido)) {
                $sql = "SELECT id FROM usuarios WHERE ci = '$usuario_seguido_ci';";
                $sql = $conectar->prepare($sql);
                $sql->execute();
                $usuario_seguido_id = $sql->fetch();

                $sql = "INSERT INTO public.seguidores(
                    seguido_id, fecha_crea, 
                    fecha_mod, user_crea, 
                    user_mod, activo)
                    VALUES (" . $usuario_seguido_id[0] . ", '$fecha_hora', 
                            '$fecha_hora', $usuario_id, 
                            $usuario_id, true);
                    ";
            } else {
                if ($seguido[1]) {
                    $sql = "UPDATE public.seguidores
                    SET fecha_mod='$fecha_hora'::timestamp, activo=false
                    WHERE user_crea = $usuario_id AND
                    seguido_id = " . $seguido[0] . ";
                    ";

                } else {
                    $sql = "UPDATE public.seguidores
                    SET fecha_mod='$fecha_hora'::timestamp, activo=true
                    WHERE user_crea = $usuario_id AND
                    seguido_id = " . $seguido[0] . ";
                    ";

                }
            }
            $sql = $conectar->prepare($sql);
            $sql->execute();
            $conectar->commit();
            return "ok";
        } catch (Exception $e) {
            $conectar->rollBack();

            $men = str_replace('SQLSTATE[P0001]: Raise exception: 7 ERROR:', '', $e->getMessage());
            error_log($men . ' ' . $sql);

            return "error";

        }

    }

    //Verifica si el usuario logueado sigue o no al usuario del perfil que está viendo
    public static function siguiendo($usuario_seguido_ci, $usuario_id)
    {
        $conectar = parent::conexion();
        $sql = "SELECT activo 
        FROM seguidores
        WHERE seguido_id = (SELECT id FROM usuarios WHERE ci = '$usuario_seguido_ci')
		AND user_crea = $usuario_id;";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        $data = $sql->fetch();
        if (is_array($data)) {
            return $data[0];
        }

    }

    public static function get_comentarios_x_post($publicacion_id)
    {
        $conectar = parent::conexion();
        $sql = "SELECT id as id_comentario, texto_comentario,
                    TO_CHAR(fecha_crea, 'DD Mon YYYY HH24:MI') AS fecha_comentario,
                    (SELECT ci FROM usuarios 
                    WHERE id = publicaciones_comentarios.user_crea) AS ci_usuario_comentario,
                (SELECT nombre || ' ' || apellido FROM usuarios 
                    WHERE id = publicaciones_comentarios.user_crea) AS nombre_usuario_comentario,
                (SELECT foto_perfil FROM usuarios WHERE id = publicaciones_comentarios.user_crea) as foto_perfil
                FROM publicaciones_comentarios
                WHERE publicacion_id = $publicacion_id;
        ";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        return $sql->fetchAll();
    }

    //se crea el perfil del usuario si no existe aún
    public function crearPerfil($fecha_hora, $usuario_id)
    {
        $conectar = parent::conexion();
        $sql = "SELECT nombre || ' ' || apellido 
        FROM usuarios
        WHERE id = $usuario_id;";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        $nombre_usuario = $sql->fetch();
        if (is_array($nombre_usuario)) {
            $sql = "INSERT INTO public.perfiles_informaciones(
                nombre_perfil, fecha_crea, 
                user_crea, activo)
                VALUES ('" . $nombre_usuario[0] . "', '$fecha_hora'::timestamp, 
                        $usuario_id, true);";
            $sql = $conectar->prepare($sql);
            $sql->execute();
            return $nombre_usuario[0];
        }
    }

    public function update_perfil($datos)
    {
        $conectar = parent::conexion();
        $sql = "SELECT id 
        FROM perfiles_informaciones
        WHERE user_crea = " . $datos["usuario_id"] . ";";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        $perfil_id = $sql->fetch();
        error_log($perfil_id[0]);
        if (is_array($perfil_id)) {
            $ciudad_trabajo = "";
            $profesion_principal = "";
            $lugar_trabajo = "";
            if ($datos["ciudad_trabajo"] > 0) {
                $ciudad_trabajo = ", ciudad_trabajo_id=" . $datos["ciudad_trabajo"];
            }
            if ($datos["profesion_principal"] > 0) {
                $profesion_principal = ", profesion_principal_id=" . $datos["profesion_principal"];
            }
            if ($datos["lugar_trabajo"] > 0) {
                $lugar_trabajo = ", lugar_trabajo_id=" . $datos["lugar_trabajo"];
            }
            $sql = "UPDATE public.perfiles_informaciones
            SET nombre_perfil='" . $datos['nombre_perfil'] . "', acerca_de_mi='" . $datos['acerca_de_mi'] . "'
            $ciudad_trabajo $profesion_principal, 
            fecha_crea='" . $datos["fecha_hora"] . "'::timestamp, user_crea=" . $datos["usuario_id"] . ", 
            fecha_mod='" . $datos["fecha_hora"] . "'::timestamp, user_mod=" . $datos["usuario_id"] . ", 
            activo=true, educacion='" . $datos['educacion'] . "' $lugar_trabajo 
            WHERE id = " . $perfil_id[0] . ";";
            error_log($sql);
            $sql = $conectar->prepare($sql);
            $sql->execute();
            return "ok";
        } else {
            return "error";
        }
    }

    public function update_foto_perfil($foto_perfil, $usuario_id, $fecha_hora)
    {
        $conectar = parent::conexion();
        $sql ="UPDATE usuarios SET foto_perfil = '$foto_perfil',
        user_mod = $usuario_id, fecha_mod = '$fecha_hora'::timestamp
        WHERE id = $usuario_id;";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        return 'ok';
    }

    public function get_ciudades()
    {
        $conectar = parent::conexion();
        $sql = "SELECT id as ciudad_id, nombre as ciudad
        FROM ciudades;";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        return $resultado = $sql->fetchAll();
    }

    public function get_profesiones()
    {
        $conectar = parent::conexion();
        $sql = "SELECT id as profesion_id, profesion
        FROM profesiones;";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        return $resultado = $sql->fetchAll();
    }
    public function get_establecimientos()
    {
        $conectar = parent::conexion();
        $sql = "SELECT id as establecimiento_id, nombre_establecimiento
        FROM establecimientos_salud;";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        return $resultado = $sql->fetchAll();
    }

    public function datos_perfil($usuario_id)
    {
        $conectar = parent::conexion();
        $sql = "SELECT nombre_perfil, acerca_de_mi, ciudad_trabajo_id, 
                (SELECT nombre FROM ciudades WHERE id = ciudad_trabajo_id) AS ciudad_trabajo_nombre, 
                profesion_principal_id, educacion, lugar_trabajo_id,
                (SELECT nombre_establecimiento FROM establecimientos_salud WHERE id = lugar_trabajo_id) AS lugar_trabajo_nombre 
                FROM perfiles_informaciones
                WHERE user_crea = $usuario_id;";
        $sql = $conectar->prepare($sql);
        $sql->execute();

        $resultado = $sql->fetch();  // Fetch the result once

        if (is_array($resultado)) {
            return $resultado;
        } else {
            return "error";
        }
    }
   
    public static function get_foto_perfil($usuario_id)
    {
        $conectar = parent::conexion();
        $sql = "SELECT foto_perfil
        FROM usuarios WHERE id = $usuario_id;";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        return $sql->fetch()[0];
    }

    public static function get_imagenes_perfil($usuario_id)
    {
        $conectar = parent::conexion();
        $sql = "SELECT foto_perfil, foto_ci, foto_registro_profesional
                FROM usuarios WHERE id = :usuario_id;";
        $sql = $conectar->prepare($sql);
        $sql->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }
    public static function cantidades_perfil($usuario_id)
    {
        $conectar = parent::conexion();
        $sql = "SELECT
        (SELECT COUNT(*) FROM publicaciones WHERE user_crea = $usuario_id AND activo=true) AS total_publicaciones,
        (SELECT COUNT(*) FROM seguidores WHERE seguido_id = $usuario_id AND activo=true) AS total_seguidores,
        (SELECT COUNT(*) FROM seguidores WHERE user_crea = $usuario_id AND activo=true) AS total_siguiendo,
        (SELECT COUNT(*) FROM publicaciones_adjuntos WHERE user_crea = $usuario_id AND activo=true) AS total_adjuntos;";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        return $sql->fetch();
    }

    public function insert_comentario($publicacion_id, $nuevo_comentario, $fecha_hora, $usuario_id)
    {
        try {
            $conectar = parent::conexion();
            $conectar->beginTransaction();
            $sql = "INSERT INTO public.publicaciones_comentarios(
                publicacion_id, texto_comentario,
                fecha_crea, fecha_mod, 
                user_crea, user_mod, activo)
                VALUES ($publicacion_id, '$nuevo_comentario', 
                        '$fecha_hora'::timestamp, '$fecha_hora'::timestamp,
                        $usuario_id, $usuario_id, true);";
            $sql = $conectar->prepare($sql);
            $sql->execute();
            $conectar->commit();
            return "ok";
        } catch (Exception $e) {
            return $e->getMessage();

        }
    }
}
?>