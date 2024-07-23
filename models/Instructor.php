<?php
class Instructor extends Conectar
{
    /*=============================================
        CURSOS
        =============================================*/
    public static function get_cursos_x_instructor($usuario_ci)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT 
        id AS curso_id, nombre as nombre_curso,
        (SELECT t.tipo_solicitud || ': ' || t.nombre
        FROM tramites t WHERE t.id = cursos.tramite_id) AS capacitacion,
        (SELECT nombre FROM cursos_categorias WHERE id = categoria_id) AS nombre_categoria,
        TO_CHAR(fecha_crea::timestamp, 'DD/MM/YYYY') AS fecha
        FROM cursos
        WHERE instructor_id = (SELECT id FROM cursos_instructores WHERE documento_identidad = '$usuario_ci')
        AND activo=true
        ORDER BY nombre_curso;
        ";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function mostrar_curso($curso_id, $usuario_id)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT 
        id AS curso_id, nombre as nombre_curso, tramite_id AS tipo_tramite,
        categoria_id AS categoria_curso, descripcion, aprendizaje,
        (SELECT nombre FROM cursos_categorias WHERE id = categoria_id) AS nombre_categoria,
        TO_CHAR(fecha_crea::timestamp, 'DD/MM/YYYY') AS fecha, imagen_portada

        FROM cursos
        WHERE instructor_id = 2
        AND id = $curso_id;
        ";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_opciones_categorias()
    {
        $conectar = parent::Conexion();
        $sql = "SELECT 
        id AS categoria_id, nombre AS categoria
        FROM cursos_categorias
        WHERE activo = true;
        ";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert_datos_curso($usuario_ci, $usuario_id, $datos)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT id FROM cursos_instructores WHERE documento_identidad = '$usuario_ci';";
        $query = $conectar->prepare($sql);
        $query->execute();
        $data = $query->fetch();
        $instructor_id = $data['0'];

        $sql = "SELECT CASE 
        WHEN tipo_solicitud = 'CERT' THEN true 
        WHEN tipo_solicitud = 'CURSO' THEN false 
        WHEN tipo_solicitud = 'CONCURSO' THEN false 
        ELSE false 
        END AS es_certificacion
        FROM tramites 
        WHERE id = :tramite_id";

        $query = $conectar->prepare($sql);
        $query->bindParam(':tramite_id', $datos['tramite_id'], PDO::PARAM_INT);
        $query->execute();
        $data = $query->fetch(PDO::FETCH_ASSOC);

        $es_certificacion = $data['es_certificacion'];
        $certificacion = $es_certificacion ? 'true' : 'false';
        $sql = "INSERT INTO public.cursos(
            nombre, categoria_id, 
            instructor_id, aprendizaje, 
            descripcion, fecha_crea, 
            fecha_mod, user_crea, 
            user_mod, activo,
            certificacion, imagen_portada,
            tramite_id)
            VALUES (
            '" . $datos['nombre_curso'] . "', " . $datos['categoria_curso'] . ", 
            $instructor_id, '" . $datos['aprendizaje'] . "',
            '" . $datos['descripcion'] . "', '" . $datos['fecha_hora'] . "'::timestamp, 
            '" . $datos['fecha_hora'] . "'::timestamp, $usuario_id, 
            $usuario_id, " . $datos['activo'] . ", 
            $certificacion, '" . $datos['imagen_portada'] . "',
            " . $datos['tramite_id'] . ");
        ";
     
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return 'ok';
    }

    public function update_datos_curso($curso_id, $usuario_id, $datos)
    {
        $conectar = parent::Conexion();
        $sql = "UPDATE public.cursos
        SET nombre='" . $datos['nombre_curso'] . "', categoria_id=" . $datos['categoria_curso'] . ",
        descripcion='" . $datos['descripcion'] . "', fecha_mod='" . $datos['fecha_hora'] . "'::timestamp, 
        user_mod=$usuario_id, aprendizaje='" . $datos['aprendizaje'] . "', 
        certificacion=" . $datos['certificacion'] . ", imagen_portada='" . $datos['imagen_portada'] . "'
        WHERE id = $curso_id;
        ";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return 'ok';
    }

    public function delete_curso($id_curso, $usuario_id, $fecha_hora)
    {
        try {
            $db = parent::Conexion();
            $respuesta = $db;
            $db->beginTransaction();

            $sql = "UPDATE public.cursos
                SET activo = false
                WHERE id = $id_curso;";

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
            return true;

        }
    }

    public static function get_solicitudes_inscripcion($table, $usuario_id)
    {
        $conectar = parent::Conexion();
        if ($table == "pendientes") {
            $tabla_estado = "PENDIENTE DE APROBACIÓN";
        } elseif ($table == "aprobadas") {
            $tabla_estado = "APROBADO";
        } elseif ($table == "rechazadas") {
            $tabla_estado = "RECHAZADO";
        }

        $sql = "SELECT DISTINCT ON (tramite_gestionado_id) 
                tg.id AS tramite_gestionado_id,
                tg.tramite_id,
                c.nombre AS seccion_curso,
                usuarios.nombre || ' ' || usuarios.apellido AS usuario_solicitante,
                tramites.tipo_solicitud,
                tg.fecha_crea as fecha_solicitud,
                e.nombre AS estado_actual,
                tg.fecha_mod AS ultimo_movimiento,
                tramites.nombre as tramite_nombre,
                areas.nombre as area_asignada,
                ROUND(EXTRACT(EPOCH FROM (NOW() AT TIME ZONE 'America/Asuncion' - tg.fecha_mod)) / 3600) AS horas_transcurridas,
                (SELECT COUNT(DISTINCT et.paso) 
                FROM estados_tramites et 
                WHERE et.activo = true AND et.tramite_id = tg.tramite_id) AS cantidad_pasos,
                (SELECT paso FROM estados_tramites et 
                WHERE et.tramite_id = tg.tramite_id AND et.estado_id = tg.estado_tramite_id AND et.activo =true) AS paso
                FROM movimientos_tramites
                JOIN tramites_gestionados tg on tg.id = movimientos_tramites.tramite_gestionado_id
                JOIN tramites on tramites.id = tg.tramite_id
                JOIN estados AS e on e.id = COALESCE(tg.estado_tramite_id,movimientos_tramites.estado_tramite_id)
                JOIN usuarios ON usuarios.id = tg.usuario_id
                JOIN areas on areas.id = movimientos_tramites.area_asignada_id
                JOIN cursos AS c ON c.tramite_id = tg.tramite_id
                AND movimientos_tramites.activo = true
                AND tg.activo = true 
                AND (tramites.tipo_solicitud = 'CURSO' OR tramites.tipo_solicitud = 'CERT')
                AND e.nombre = '$tabla_estado'
                -- AND c.instructor_id = (SELECT id from cursos_instructores where documento_identidad = (SELECT ci FROM usuarios where id = 11))
                ORDER BY tramite_gestionado_id, seccion_curso, movimientos_tramites.fecha_mod DESC;";
                
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /*=============================================
    SECCIONES
    =============================================*/

    public static function get_secciones_x_curso($curso_id)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT id as seccion_id, titulo,
        orden, (SELECT COUNT(m.id) FROM cursos_secciones_materiales as m
        WHERE m.seccion_id = cursos_secciones.id and m.activo = true) AS recursos,
        (SELECT COUNT(t.id) FROM cursos_secciones_tareas as t
        WHERE t.seccion_id = cursos_secciones.id and t.activo = true) AS tareas,
        (SELECT COUNT(l.id) FROM cursos_lecciones as l
        WHERE l.curso_seccion_id = cursos_secciones.id and l.activo = true) AS lecciones
        FROM cursos_secciones
        WHERE curso_id = $curso_id
        AND activo = true
        ORDER BY orden;
        ";

        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function get_lecciones_x_seccion($seccion_id)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT id as leccion_id, titulo,
        orden, video_url
        FROM cursos_lecciones
        WHERE curso_seccion_id = $seccion_id
        AND activo = true
        ORDER BY orden ASC;";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert_datos_seccion($usuario_id, $datos)
    {
        try {
            $conectar = parent::Conexion();
            $respuesta = $conectar;
            $conectar->beginTransaction();
            $sql = "SELECT
            id, orden,
            (SELECT id FROM cursos_secciones 
            WHERE orden = " . $datos['orden'] . " 
            AND curso_id = " . $datos['curso_id'] . " AND curso_id = " . $datos['curso_id'] . "
            AND activo = true) AS id_orden_repetido
            FROM cursos_secciones
            WHERE orden >= " . $datos['orden'] . " 
            AND curso_id = " . $datos['curso_id'] . " 
            AND activo = true;
            ";
            $query = $conectar->prepare($sql);
            $query->execute();
            $secciones_a_reordenar = $query->fetchAll(PDO::FETCH_ASSOC);
            if (count($secciones_a_reordenar) > 0) {
                if ($secciones_a_reordenar[0]["id_orden_repetido"] > 1) {
                    foreach ($secciones_a_reordenar as $seccion) {
                        $nuevo_orden = $seccion['orden'] + 1;
                        $sql = "UPDATE cursos_secciones
                        SET orden = $nuevo_orden, fecha_mod = '" . $datos['fecha_hora'] . "'::timestamp
                        WHERE id = " . $seccion['id'] . ";
                        ";
                        $query = $conectar->prepare($sql);
                        $query->execute();
                    }
                }
            }
            $sql = "INSERT INTO public.cursos_secciones(
                titulo, orden, fecha_crea, 
                fecha_mod, user_crea,
                user_mod, activo, curso_id)
                VALUES ('" . $datos['titulo'] . "', " . $datos['orden'] . ", '" . $datos['fecha_hora'] . "'::timestamp, 
                        '" . $datos['fecha_hora'] . "'::timestamp, $usuario_id, 
                        $usuario_id, " . $datos['activo'] . ", " . $datos['curso_id'] . ");
            ";
            $query = $conectar->prepare($sql);
            $query->execute();

        } catch (e) {
            return 'error';
        }
        if ($respuesta === $conectar) {
            $conectar->commit();
            $conectar = null;
            return "ok";
        }

    }

    public function update_datos_seccion($usuario_id, $datos)
    {
        try {
            $conectar = parent::Conexion();
            $respuesta = $conectar;
            $conectar->beginTransaction();
            $sql = "SELECT
            cs.id, cs.orden,
            (SELECT id FROM cursos_secciones 
            WHERE orden = " . $datos['orden'] . " 
            AND id <> " . $datos['seccion_id'] . " AND curso_id = 
            (SELECT curso_id FROM cursos_secciones WHERE id = " . $datos['seccion_id'] . ")
            AND activo = true) AS id_orden_repetido
            FROM cursos_secciones AS cs
            WHERE (cs.orden >= " . $datos['orden'] . " 
            AND cs.id<>" . $datos['seccion_id'] . ")
            AND cs.curso_id = (SELECT curso_id FROM cursos_secciones WHERE id = " . $datos['seccion_id'] . ") 
            AND cs.activo = true;";
            $query = $conectar->prepare($sql);
            $query->execute();
            $secciones_a_reordenar = $query->fetchAll(PDO::FETCH_ASSOC);
            if (count($secciones_a_reordenar) > 0) {
                if ($secciones_a_reordenar[0]["id_orden_repetido"] > 1) {
                    foreach ($secciones_a_reordenar as $seccion) {
                        $nuevo_orden = $seccion['orden'] + 1;
                        $sql = "UPDATE cursos_secciones
                        SET orden = $nuevo_orden, fecha_mod = '" . $datos['fecha_hora'] . "'::timestamp
                        WHERE id = " . $seccion['id'] . ";
                        ";
                        $query = $conectar->prepare($sql);
                        $query->execute();
                    }
                }
            }
            $sql = "UPDATE public.cursos_secciones
                SET titulo='" . $datos['titulo'] . "', orden=" . $datos['orden'] . ", 
                fecha_mod='" . $datos['fecha_hora'] . "'::timestamp, 
                user_mod=$usuario_id
                WHERE id = " . $datos['seccion_id'] . ";
                ";
            $query = $conectar->prepare($sql);
            $query->execute();

        } catch (e) {
            return 'error';
        }
        if ($respuesta === $conectar) {
            $conectar->commit();
            $conectar = null;
            return "ok";
        }
    }

    public static function get_info_seccion($seccion_id)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT id as seccion_id, titulo,
        orden, curso_id
        FROM cursos_secciones
        WHERE id = $seccion_id;
        ";

        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;

        return $query->fetch();
    }

    public function delete_seccion($usuario_id, $seccion_id, $fecha_hora)
    {
        try {
            $conectar = parent::Conexion();
            $respuesta = $conectar;
            $conectar->beginTransaction();
            $sql = "UPDATE cursos_secciones SET
            activo = false, fecha_mod = '$fecha_hora'::timestamp, user_mod = $usuario_id
            WHERE id = $seccion_id;";
            $query = $conectar->prepare($sql);
            $query->execute();

            $sql = "UPDATE cursos_lecciones SET
            activo = false, fecha_mod = '$fecha_hora'::timestamp, user_mod = $usuario_id
            WHERE curso_seccion_id = $seccion_id;";
            $query = $conectar->prepare($sql);
            $query->execute();
        } catch (Exception $e) {
            $conectar->rollBack();

            $men = str_replace('SQLSTATE[P0001]: Raise exception: 7 ERROR:', '', $e->getMessage());
            error_log($men . ' ' . $sql);
            echo $men . ' ' . $sql;

            return "error";
        }
        if ($respuesta === $conectar) {
            $conectar->commit();
            $conectar = null;
            return "ok";
        }
    }


    public static function get_curso_id($seccion_id)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT curso_id
        FROM cursos_secciones
        WHERE id = $seccion_id;";

        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;

        return $query->fetch();
    }

    public function insert_material_seccion($nombre_archivo, $seccion_id, $usuario_id, $fecha_hora)
    {
        try {
            $conectar = parent::Conexion();
            $respuesta = $conectar;
            $conectar->beginTransaction();
            $sql = "INSERT INTO public.cursos_secciones_materiales(
                archivo, seccion_id, 
                fecha_crea, fecha_mod, 
                user_crea, user_mod, 
                activo)
                VALUES ('$nombre_archivo', $seccion_id, 
                        '$fecha_hora'::timestamp, '$fecha_hora'::timestamp, 
                        $usuario_id, $usuario_id, 
                        true);";
            $query = $conectar->prepare($sql);
            $query->execute();
        } catch (Exception $e) {
            $conectar->rollBack();

            $men = str_replace('SQLSTATE[P0001]: Raise exception: 7 ERROR:', '', $e->getMessage());
            error_log($men . ' ' . $sql);
            echo $men . ' ' . $sql;

            return "error";
        }
        if ($respuesta === $conectar) {
            $conectar->commit();
            $conectar = null;
            return "ok";
        }
    }

    public static function get_materiales_x_seccion($seccion_id)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT id as material_id, archivo, 
            TO_CHAR(fecha_mod, 'DD Mon YYYY') AS fecha_formato_doc,
            TO_CHAR(fecha_mod, 'HH24:MI') AS hora_formato_doc
            FROM cursos_secciones_materiales
            WHERE seccion_id = $seccion_id
            AND activo = true;";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete_material_seccion($material_id, $fecha_hora, $usuario_id)
    {
        try {
            $db = parent::Conexion();
            $respuesta = $db;
            $db->beginTransaction();

            $sql = "UPDATE public.cursos_secciones_materiales
            SET fecha_mod='$fecha_hora'::timestamp, user_mod=$usuario_id, activo=false
            WHERE id=$material_id;";

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
            return 'ok';

        }
    }

    /*=============================================
    LECCIONES
    =============================================*/
    public function insert_datos_leccion($datos, $usuario_id)
    {
        $conectar = parent::Conexion();
        $sql = "INSERT INTO public.cursos_lecciones(
            titulo, orden, 
            descripcion, curso_seccion_id, 
            fecha_crea, fecha_mod, 
            user_crea, user_mod,
            activo, video_url)
            VALUES ('" . $datos['titulo'] . "', " . $datos['orden'] . ", 
            '" . $datos['descripcion'] . "', " . $datos['seccion_id'] . ", 
            '" . $datos['fecha_hora'] . "'::timestamp, '" . $datos['fecha_hora'] . "'::timestamp, 
            $usuario_id, $usuario_id, 
            " . $datos['activo'] . ", '" . $datos['video_url'] . "');";

        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return 'ok';

    }

    public function update_datos_leccion($datos, $usuario_id)
    {
        $conectar = parent::Conexion();
        $sql = "UPDATE public.cursos_lecciones
            SET titulo='" . $datos['titulo'] . "', orden=" . $datos['orden'] . ", 
            descripcion='" . $datos['descripcion'] . "', fecha_mod='" . $datos['fecha_hora'] . "'::timestamp, 
            user_mod=$usuario_id, video_url='" . $datos['video_url'] . "'
            WHERE id =" . $datos['leccion_id'] . ";";

        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return 'ok';

    }

    public static function get_info_leccion($leccion_id)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT id as leccion_id, titulo AS titulo_leccion,
        orden AS orden_leccion, descripcion
        FROM cursos_lecciones
        WHERE id = $leccion_id;
        ";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete_leccion($usuario_id, $leccion_id, $fecha_hora)
    {
        try {
            $conectar = parent::Conexion();
            $respuesta = $conectar;
            $conectar->beginTransaction();
            $sql = "UPDATE cursos_lecciones SET
            activo = false, fecha_mod = '$fecha_hora'::timestamp, user_mod = $usuario_id
            WHERE id = $leccion_id;";
            $query = $conectar->prepare($sql);
            $query->execute();
        } catch (Exception $e) {
            $conectar->rollBack();

            $men = str_replace('SQLSTATE[P0001]: Raise exception: 7 ERROR:', '', $e->getMessage());
            error_log($men . ' ' . $sql);
            echo $men . ' ' . $sql;

            return "error";
        }
        if ($respuesta === $conectar) {
            $conectar->commit();
            $conectar = null;
            return "ok";
        }
    }

    public function get_tareas_x_secciones($seccion_id)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT id as tarea_id, 
        titulo,
        CASE 
            WHEN tipo_tarea = 1 THEN 'Trabajo Práctico' 
            ELSE 'Cuestionario' 
        END AS tipo_tarea,
        fecha_limite 
        FROM cursos_secciones_tareas 
        WHERE seccion_id = $seccion_id 
        AND activo = true;";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function get_informacion_curso_seccion($id_seccion)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT (SELECT nombre FROM tramites WHERE cursos.tramite_id = tramites.id)
        || ' - ' ||cursos.nombre as nombre_curso, 
        (SELECT nombre FROM cursos_categorias WHERE id = categoria_id) AS nombre_categoria,
        (SELECT nombre || ' ' || apellido FROM cursos_instructores WHERE id = instructor_id) AS instructor,
        cursos.descripcion AS descripcion_curso, 
        validacion, certificacion, imagen_portada,
        aprendizaje, TO_CHAR(cursos.fecha_mod::timestamp, 'DD/MM/YYYY') AS fecha_curso,
        (SELECT count(*) FROM cursos_secciones WHERE curso_id = cursos.id) AS cantidad_lecciones,
        (SELECT count(*) FROM cursos_inscriptos WHERE curso_id = cursos.id) AS cantidad_inscriptos,
        (SELECT count(*) FROM cursos_secciones_tareas cst 
        WHERE cst.seccion_id = cursos_secciones.id) AS cantidad_tareas,
        cursos_secciones.titulo AS titulo_seccion
        FROM cursos
        JOIN cursos_secciones ON cursos_secciones.curso_id = cursos.id
        WHERE cursos.activo = true
        AND cursos_secciones.id = $id_seccion;";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return $query->fetch();
    }

    public static function get_informacion_curso($id_curso)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT  (SELECT nombre FROM tramites WHERE cursos.tramite_id = tramites.id)
        || ' - ' ||cursos.nombre as nombre_curso, 
        (SELECT nombre FROM cursos_categorias WHERE id = categoria_id) AS nombre_categoria,
        (SELECT nombre || ' ' || apellido FROM cursos_instructores WHERE id = instructor_id) AS instructor,
        cursos.descripcion AS descripcion_curso, 
        validacion, certificacion, imagen_portada,
        aprendizaje, TO_CHAR(cursos.fecha_mod::timestamp, 'DD/MM/YYYY') AS fecha_curso,
        (SELECT count(*) FROM cursos_lecciones WHERE curso_seccion_id IN (SELECT cursos_secciones.id FROM cursos_secciones 
								 WHERE cursos_secciones.curso_id = cursos.id) 
        AND cursos_lecciones.activo = true) AS cantidad_lecciones,
        (SELECT count(*) FROM cursos_inscriptos WHERE curso_id = cursos.id) AS cantidad_inscriptos,
        (SELECT count(*) FROM cursos_secciones_tareas cst 
        WHERE cst.seccion_id IN (SELECT cursos_secciones.id FROM cursos_secciones 
								 WHERE cursos_secciones.curso_id = cursos.id)
		 AND cst.activo = true) AS cantidad_tareas
        FROM cursos
        WHERE cursos.activo = true
        AND cursos.id = $id_curso
		;";

        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return $query->fetch();
    }


    /*=============================================
    TAREAS
    =========================================*/

    public function insert_tareas($datos, $usuario_id)
    {
        $conectar = parent::Conexion();
        if ($datos["tipo_tarea"] == 2) {
            $sql = "INSERT INTO public.cursos_secciones_tareas(
                titulo, descripcion, 
                seccion_id, fecha_crea, 
                fecha_mod, user_crea, 
                user_mod, activo, 
                fecha_limite, tiempo_limite, 
                cantidad_intentos, tipo_tarea,
                archivo_url, total_puntos)
                VALUES ('" . $datos["titulo"] . "', '" . $datos["descripcion"] . "', 
                        " . $datos["seccion_id"] . ", '" . $datos["fecha_hora"] . "'::timestamp, 
                        '" . $datos["fecha_hora"] . "'::timestamp, $usuario_id, 
                        $usuario_id, " . $datos["activo"] . ", 
                        '" . $datos["fecha_limite"] . "'::timestamp, " . $datos["tiempo_limite"] . ", 
                        " . $datos["cantidad_intentos"] . ", " . $datos["tipo_tarea"] . ",
                        '" . $datos["archivo_url"] . "', " . $datos["total_puntos"] . ");";
        } else {
            $sql = "INSERT INTO public.cursos_secciones_tareas(
                titulo, descripcion, 
                seccion_id, fecha_crea, 
                fecha_mod, user_crea, 
                user_mod, activo, 
                fecha_limite, cantidad_intentos, 
                tipo_tarea, archivo_url,
                total_puntos)
                VALUES ('" . $datos["titulo"] . "', '" . $datos["descripcion"] . "', 
                        " . $datos["seccion_id"] . ", '" . $datos["fecha_hora"] . "'::timestamp, 
                        '" . $datos["fecha_hora"] . "'::timestamp, $usuario_id, 
                        $usuario_id, " . $datos["activo"] . ", 
                        '" . $datos["fecha_limite"] . "'::timestamp," . $datos["cantidad_intentos"] . ", 
                        " . $datos["tipo_tarea"] . ", '" . $datos["archivo_url"] . "',
                        " . $datos["total_puntos"] . ");";
        }

        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return 'ok';
    }

    public static function get_info_tarea($tarea_id)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT id as tarea_id, titulo,
        cantidad_intentos, fecha_limite, 
        tiempo_limite, seccion_id, tipo_tarea,
        descripcion, total_puntos
        FROM cursos_secciones_tareas
        WHERE id = $tarea_id;
        ";

        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update_tareas($datos, $usuario_id)
    {
        error_log($datos["total_puntos"]);
        $conectar = parent::Conexion();
        if ($datos["tipo_tarea"] == 2) {
            $sql = "UPDATE public.cursos_secciones_tareas
            SET titulo='" . $datos["titulo"] . "', descripcion='" . $datos["descripcion"] . "', 
            fecha_mod='" . $datos["fecha_hora"] . "'::timestamp, user_mod=$usuario_id, 
            fecha_limite='" . $datos["fecha_limite"] . "'::timestamp, tiempo_limite='" . $datos["tiempo_limite"] . "', 
            cantidad_intentos=" . $datos["cantidad_intentos"] . ", tipo_tarea=" . $datos["tipo_tarea"] . ",
            archivo_url = '', total_puntos=" . $datos["total_puntos"] . "
            WHERE id =" . $datos["tarea_id"] . ";";
        } else {
            $sql = "UPDATE public.cursos_secciones_tareas
            SET titulo='" . $datos["titulo"] . "', descripcion='" . $datos["descripcion"] . "', 
            fecha_mod='" . $datos["fecha_hora"] . "'::timestamp, user_mod=$usuario_id, 
            fecha_limite='" . $datos["fecha_limite"] . "'::timestamp, archivo_url = '" . $datos["archivo_url"] . "', 
            cantidad_intentos=" . $datos["cantidad_intentos"] . ", tipo_tarea=" . $datos["tipo_tarea"] . ", 
            total_puntos=" . $datos["total_puntos"] . "
            WHERE id =" . $datos["tarea_id"] . ";";
        }

        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return 'ok';
    }

    public function delete_tareas($tarea_id, $usuario_id, $fecha_hora)
    {
        try {
            $conectar = parent::Conexion();
            $respuesta = $conectar;
            $conectar->beginTransaction();
            $sql = "UPDATE public.cursos_secciones_tareas
            SET fecha_mod='$fecha_hora'::timestamp, user_mod=$usuario_id, activo=false
            WHERE id =$tarea_id;";
            $query = $conectar->prepare($sql);
            $query->execute();
        } catch (Exception $e) {
            $conectar->rollBack();

            $men = str_replace('SQLSTATE[P0001]: Raise exception: 7 ERROR:', '', $e->getMessage());
            error_log($men . ' ' . $sql);

            return "error";

        }
        if ($respuesta === $conectar) {
            $conectar->commit();
            $conectar = null;
            return "ok";

        }
    }

    public static function get_seccion_id($tarea_id)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT seccion_id 
        FROM cursos_secciones_tareas 
        WHERE id = $tarea_id 
        AND activo = true;";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return $query->fetch();
    }

    public static function get_entregas_x_tarea($id_tarea)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT cttp.id AS id_entrega,
        cttp.puntos_logrados, TO_CHAR(cttp.fecha_crea::timestamp, 'DD/MM/YYYY') AS fecha_entrega, 
        (SELECT nombre || ' ' || apellido FROM usuarios u WHERE u.id = cttp.user_crea) AS alumno, 
        intentos_realizados, cst.total_puntos
        FROM public.cursos_tareas_trabajos_practicos cttp
        JOIN cursos_secciones_tareas cst ON cst.id = cttp.tarea_id
        WHERE cttp.tarea_id = $id_tarea       
        AND cttp.activo = true;";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function guardar_correccion($datos)
    {
        $conectar = parent::Conexion();
        $sql = "UPDATE cursos_tareas_trabajos_practicos SET
        puntos_logrados = " . $datos['puntos_logrados'] . ",
        observacion_instructor = '" . $datos['observacion_instructor'] . "'
        WHERE id = " . $datos['id_entrega'] . ";";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return 'ok';

    }

    /*=============================================
    CUESTIONARIOS
    =============================================*/

    public static function get_ejercicios_x_cuestionario($tarea_id)
    {

        $conectar = parent::Conexion();
        $sql = "SELECT id AS ejercicio_id, LEFT(texto_ejercicio, 100) AS texto_ejercicio, 
        tipo_ejercicio, imagen_url, numero_ejercicio,
        respuesta_correcta, puntaje
        FROM public.cursos_tareas_ejercicios
        WHERE tarea_id = $tarea_id;";
        $query = $conectar->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert_ejercicios($datos)
    {
        try {
            $conectar = parent::Conexion();
            $respuesta = $conectar;
            $conectar->beginTransaction();
    
            // Update total points
            $acumulado = $datos['total_puntos'] + $datos['puntaje'];
            $sql = "UPDATE cursos_secciones_tareas SET total_puntos = :acumulado WHERE id = :tarea_id;";
            $query = $conectar->prepare($sql);
            $query->bindParam(':acumulado', $acumulado);
            $query->bindParam(':tarea_id', $datos['tarea_id']);
            $query->execute();
    
            // Insert the main exercise record
            $sql = "INSERT INTO public.cursos_tareas_ejercicios(
                        texto_ejercicio, tipo_ejercicio, 
                        tarea_id, fecha_crea, fecha_mod, 
                        user_crea, user_mod, 
                        activo, imagen_url, 
                        respuesta_correcta, puntaje, 
                        numero_ejercicio)
                    VALUES (
                        :texto_ejercicio, :tipo_ejercicio, 
                        :tarea_id, :fecha_crea, :fecha_crea, 
                        :usuario_id, :usuario_id, 
                        :activo, :imagen_url, 
                        :respuesta_correcta, :puntaje, 
                        :numero_ejercicio)";
    
            $query = $conectar->prepare($sql);
            $query->bindParam(':texto_ejercicio', $datos["texto_ejercicio"]);
            $query->bindParam(':tipo_ejercicio', $datos["tipo_ejercicio"]);
            $query->bindParam(':tarea_id', $datos["tarea_id"]);
            $query->bindParam(':fecha_crea', $datos["fecha_hora"]);
            $query->bindParam(':usuario_id', $datos["usuario_id"]);
            $query->bindParam(':activo', $datos["activo"]);
            $query->bindParam(':imagen_url', $datos["imagen_url"]);
            $query->bindParam(':respuesta_correcta', $datos["respuesta_correcta"]);
            $query->bindParam(':puntaje', $datos["puntaje"]);
            $query->bindParam(':numero_ejercicio', $datos["numero_ejercicio"]);
            $query->execute();
    
            // Get the generated ejercicio_id
            $sql = "SELECT currval('cursos_tareas_ejercicios_id_seq')::BIGINT as ejercicio_id;";
            $stmt = $conectar->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetch();
            $ejercicio_id = $data['ejercicio_id'];
    
            // Insert options for the exercise
            $additionalData = json_decode($datos['additional_data'], true);
            foreach ($additionalData as $key => $value) {
                if (!empty($value) && strpos($key, '_correct') === false) {
                    // Determine if this option is correct based on the key (e.g., 'multiple1', 'radio1')
                    $is_correct = isset($additionalData[$key . '_correct']) ? $additionalData[$key . '_correct'] : false;
    
                    $sql = "INSERT INTO public.cursos_ejercicios_opciones(
                                ejercicio_id, opcion_texto, 
                                es_correcto, fecha_crea, 
                                fecha_mod, user_crea, 
                                user_mod, activo)
                            VALUES (
                                :ejercicio_id, :opcion_texto, 
                                :es_correcto, :fecha_crea, 
                                :fecha_crea, :user_crea, 
                                :user_mod, :activo)";
    
                    $query = $conectar->prepare($sql);
                    $query->bindParam(':ejercicio_id', $ejercicio_id);
                    $query->bindParam(':opcion_texto', $value);
                    $query->bindParam(':es_correcto', $is_correct, PDO::PARAM_BOOL);
                    $query->bindParam(':fecha_crea', $datos["fecha_hora"]);
                    $query->bindParam(':user_crea', $datos["usuario_id"]);
                    $query->bindParam(':user_mod', $datos["usuario_id"]);
                    $query->bindParam(':activo', $datos["activo"]);
                    $query->execute();
                }
            }
    
        } catch (Exception $e) {
            $conectar->rollBack();
            $men = str_replace('SQLSTATE[P0001]: Raise exception: 7 ERROR:', '', $e->getMessage());
            error_log($men . ' ' . $sql);
            return "error";
        }
        if ($respuesta === $conectar) {
            $conectar->commit();
            $conectar = null;
            return "ok";
        }
    }
    
    



}

?>