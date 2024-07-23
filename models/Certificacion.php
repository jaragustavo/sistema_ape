<?php
class Certificacion extends Conectar
{

    public function get_tramites_academicos($tipo_solicitud)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT id as tramite_id, nombre as tramite, 
        url from tramites where tipo_tramite_id = 1 and activo =true
        AND tipo_solicitud = '$tipo_solicitud'
        AND (SELECT COUNT(*) FROM cursos WHERE tramite_id = tramites.id) > 0;";
        
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /*  Listar estados de trámites */
    public function get_estados_tramites()
    {
        $conectar = parent::Conexion();
        $sql = "SELECT id as estado_id, nombre as estado_tramite 
        from estados where activo = true;";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_tipo_solicitud($tramite_id)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT tipo_solicitud 
        from tramites where activo = true AND id =$tramite_id;";

        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_secciones($tipo_solicitud, $tramite_code, $usuario_id)
    {
        $certificacion = 'false';
        if ($tipo_solicitud == 'CERT') {
            $certificacion = 'true';
        } elseif ($tipo_solicitud == 'CURSO') {
            $certificacion = 'false';
        }
        $conectar = parent::Conexion();

        // $sql = "SELECT 'Instructor: ' ||
        // CI.nombre || ' ' || CI.apellido || ' - ' || cursos.nombre as seccion_nombre, 
        // cursos.id AS seccion_id 
        // from cursos 
		// left join cursos_instructores CI on CI.id = cursos.instructor_id
		// where cursos.activo = true AND certificacion = $certificacion
        // AND cursos.tramite_id = $tramite_code;";

        $sql = "SELECT  cursos.nombre as seccion_nombre, 
        cursos.id AS seccion_id 
        from cursos 
		left join cursos_instructores CI on CI.id = cursos.instructor_id
		where cursos.activo = true AND certificacion = $certificacion
        AND cursos.tramite_id = $tramite_code;";
        $query = $conectar->prepare($sql);

        $query->execute();
        $conectar = null;
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* TODO: Listar documentos requeridos según el trámite a gestionar */
    static public function get_docsrequeridos_x_tramite($tramite)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT tipos_documentos.documento AS tipo_documento, tramites.nombre as tramite_nombre,
                tipos_documentos.id AS tipo_documento_id
                FROM tramites_docs_requeridos
                JOIN tipos_documentos ON tipos_documentos.id = tramites_docs_requeridos.tipo_documento_id
                JOIN tramites on tramites.id = tramites_docs_requeridos.tramite_id
                WHERE tramite_id = $tramite
                AND tramites_docs_requeridos.activo = true;";

        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_datos_directorio($tipo_doc_id, $tramite_id, $db)
    {
        if ($db == "archivosDisco") {
            $db = parent::Conexion();
        }

        $sql = "SELECT tipos_documentos.nombre_corto AS tipo_doc_nombre_corto, 
            tramites.nombre_corto as tramite_nombre_corto
            FROM tramites_docs_requeridos
            JOIN tipos_documentos ON tipos_documentos.id = tramites_docs_requeridos.tipo_documento_id
            JOIN tramites on tramites.id = tramites_docs_requeridos.tramite_id
            WHERE tramites_docs_requeridos.tipo_documento_id = $tipo_doc_id
            AND tramites_docs_requeridos.tramite_id = $tramite_id
            AND tramites_docs_requeridos.activo = true;";
        $query = $db->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /*=============================================
    EXTRAER NOMBRE ARCHIVO
    =============================================*/
    public function buscarNombreArchivo($idTypeFile, $tramite_id, $cedula, $db)
    {
        $datosPath = $this->get_datos_directorio($idTypeFile, $tramite_id, $db);

        foreach ($datosPath as $row) {
            $filePath = '../docs/documents/' . $cedula . '/' . $row["tramite_nombre_corto"] . '/' . $row["tipo_doc_nombre_corto"] . '/';
        }
        $fileName = "";
        foreach (glob($filePath . '/*.*') as $file) {
            $fileName = basename($file);
        }
        $db = null;

        return $filePath . $fileName;
    }

    /*=============================================
    CREAR TRÁMITE
    =============================================*/
    public function insertar_tramites($datos)
    {
        try {
            $db = parent::Conexion();
            $respuesta = $db;
            $db->beginTransaction();

            $sql = "INSERT INTO tramites_gestionados (
                    usuario_id, estado_tramite_id, 
                    tramite_id, fecha_crea, 
                    fecha_mod, user_crea, user_mod, 
                    activo, forma_solicitud,
                    observacion, curso_id)
                VALUES (
                    " . $datos['usuario_id'] . ", " . $datos['estado_tramite_id'] . ",
                    " . $datos['tramite_id'] . ",'" . $datos['fecha_crea'] . "'::timestamp,
                    '" . $datos['fecha_crea'] . "'::timestamp,'" . $datos['usuario_id'] . "','" . $datos['usuario_id'] . "',
                    " . $datos['activo'] . ",'" . $datos['forma_solicitud'] . "',
                '" . $datos['observacion'] . "', " . $datos['curso_id'] . ");";

            $db->exec($sql);

            /*******************************
             * OBTENER TRÁMITE GESTIONADO ID
             **********************************/
            $sql = "select currval( 'tramites_gestionados_id_seq' )::BIGINT;";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetch();
            $tramite_gestionado_id = $data['0'];

            $sql = "INSERT INTO public.movimientos_tramites(
                tramite_gestionado_id, area_asignada_id,
                usuario_asignado_id, fecha_crea, 
                fecha_mod, user_crea, user_mod, 
                activo, estado_tramite_id)
                VALUES ($tramite_gestionado_id, " . $datos['area_id'] . ",
                " . $datos['usuario_id'] . ", '" . $datos['fecha_crea'] . "'::timestamp, 
                '" . $datos['fecha_crea'] . "'::timestamp, " . $datos['usuario_id'] . ", 
                " . $datos['usuario_id'] . ", true, " . $datos['estado_tramite_id'] . ");";
            error_log($sql);
            $query = $db->prepare($sql);
            $query->execute();

            $item = 0;

            $tiposDocumentos = json_decode($datos['tiposDocumentos']);

            if (is_array($tiposDocumentos)) {

                $tramite_id = $datos['tramite_id'];
                $item = 0;
                $nombre_archivo = array();
                while ($item < count($tiposDocumentos)) {
                    $idTypeFile = $tiposDocumentos[$item];
                    $cedula = $datos['cedula_user'];
                    $nombre_archivo = Certificacion::buscarNombreArchivo($idTypeFile, $tramite_id, $cedula, $db);

                    $sql = "INSERT INTO tramites_gestionados_docs (
                            tramite_gestionado_id, documento, 
                            estado_docs_tramite_id, tipo_documento_id, 
                            fecha_crea, fecha_mod, 
                            user_crea, user_mod, activo)
                        VALUES (
                            " . $tramite_gestionado_id . ",'" . $nombre_archivo . "',
                            " . $datos['estado_docs_tramite_id'] . "," . $idTypeFile . ",
                            '" . $datos['fecha_crea'] . "'::timestamp,'" . $datos['fecha_crea'] . "'::timestamp,
                            " . $datos['usuario_id'] . "," . $datos['usuario_id'] . "," . $datos['activo'] . ");";
                    $item++;
                    $db->exec($sql);

                }
            }
            // Se inserta la notificación de ser ingresada la inscripción
            $sql = "INSERT INTO public.notificaciones(
                usuario_notificado_id, mensaje_completo,
                mensaje_notificacion, leido, 
                fecha_crea, fecha_mod, user_crea, 
                user_mod, activo)
                VALUES (" . $datos['usuario_id'] . ", 'Revise su correo para mas detalle', 
                        'La inscripción fue realizada con éxito.', false, 
                        '" . $datos['fecha_crea'] . "'::timestamp, 
                        '" . $datos['fecha_crea'] . "'::timestamp, 5, 
                        5, true);"; //El usuario con id 5 es el de SIREPRO
            // error_log($sql);
            $query = $db->prepare($sql);
            $query->execute();
        } catch (Exception $e) {
            $db->rollBack();

            $men = str_replace('SQLSTATE[P0001]: Raise exception: 7 ERROR:', '', $e->getMessage());
            error_log($men . ' ' . $sql);

            return "error";

        }
        if ($respuesta === $db) {
            $db->commit();
            $db = null;
            return "ok";

        }

    }
    /*=============================================
    LISTAR TRÁMITES 
    =============================================*/
    public function get_tramites_gestionados_x_usuario($usuario_id)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT DISTINCT ON (tg.id) 
        tg.id AS tramite_gestionado_id,
        (select nombre from tramites where tramites.id = tg.tramite_id) AS nombre_tramite,
        u.nombre || ' ' || u.apellido AS usuario_solicitante,
        (SELECT nombre || ' ' || apellido FROM usuarios WHERE id = mt.usuario_asignado_id) AS usuario_asignado,
        tg.fecha_crea AS fecha_solicitud,
        e.nombre AS estado_actual,
        tg.fecha_mod AS ultimo_movimiento,
        tg.tramite_id as tramite_id,
        a.nombre AS area_asignada,
        e.permisos, 
        (SELECT COUNT(DISTINCT et.paso) 
        FROM estados_tramites et 
        WHERE et.activo = true AND et.tramite_id = tg.tramite_id) AS cantidad_pasos,
        (SELECT paso FROM estados_tramites et 
         WHERE et.tramite_id = tg.tramite_id AND et.estado_id = tg.estado_tramite_id AND et.activo =true) AS paso
        FROM tramites_gestionados tg
        LEFT JOIN movimientos_tramites mt ON tg.id = mt.tramite_gestionado_id AND mt.area_asignada_id = 1 AND mt.activo = true
        JOIN estados e ON e.id = COALESCE(tg.estado_tramite_id,mt.estado_tramite_id)
        JOIN usuarios u ON u.id = tg.usuario_id
        LEFT JOIN areas a ON a.id = mt.area_asignada_id
        LEFT JOIN tramites t ON t.id = tg.tramite_id
        WHERE tg.activo = true 
        AND t.tipo_solicitud = 'CERT'
        AND tg.usuario_id = $usuario_id
        ORDER BY tg.id, COALESCE(mt.fecha_crea, tg.fecha_mod) DESC;";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_cursos_x_usuario($usuario_id)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT DISTINCT ON (tg.id) 
        tg.id AS tramite_gestionado_id,
        (select nombre from tramites where tramites.id = tg.tramite_id) AS nombre_tramite,
        u.nombre || ' ' || u.apellido AS usuario_solicitante,
        (SELECT nombre || ' ' || apellido FROM usuarios WHERE id = mt.usuario_asignado_id) AS usuario_asignado,
        tg.fecha_crea AS fecha_solicitud,
        e.nombre AS estado_actual,
        tg.fecha_mod AS ultimo_movimiento,
        tg.tramite_id as tramite_id,
        a.nombre AS area_asignada,
        e.permisos, 
        (SELECT COUNT(DISTINCT et.paso) 
        FROM estados_tramites et 
        WHERE et.activo = true AND et.tramite_id = tg.tramite_id) AS cantidad_pasos,
        (SELECT paso FROM estados_tramites et 
         WHERE et.tramite_id = tg.tramite_id AND et.estado_id = tg.estado_tramite_id AND et.activo =true) AS paso
        FROM tramites_gestionados tg
        LEFT JOIN movimientos_tramites mt ON tg.id = mt.tramite_gestionado_id AND mt.area_asignada_id = 1 AND mt.activo = true
        JOIN estados e ON e.id = COALESCE(tg.estado_tramite_id,mt.estado_tramite_id)
        JOIN usuarios u ON u.id = tg.usuario_id
        LEFT JOIN areas a ON a.id = mt.area_asignada_id
        LEFT JOIN tramites t ON t.id = tg.tramite_id
        WHERE tg.activo = true 
        AND t.tipo_solicitud = 'CURSO'
        AND tg.usuario_id = $usuario_id
        ORDER BY tg.id, COALESCE(mt.fecha_crea, tg.fecha_mod) DESC;";

        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function mostrar($tramite_gestionado_id, $usuario_id)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT curso_id, observacion FROM tramites_gestionados WHERE id = $tramite_gestionado_id;";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    // Trae los documentos que fueron observados por un fiscalizador 
    // para poder ser modificados por el solicitante
    public static function get_docs_x_tramite_gestionado($tramite_gestionado_id)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT  TGD.id as documento_id,
            TGD.documento as documento,
            TGD.tipo_documento_id as tipo_doc_id,
            tipos_documentos.documento as tipo_doc,
            tramites_gestionados.id as tramite_gestionado_id,
            tramites.nombre AS nombre_tramite, 
            tramites_gestionados.fecha_crea as fecha_solicitud,
            estados_tramites.nombre as estado_actual,
            tramites_gestionados.fecha_mod as ultimo_movimiento,
			(SELECT nombre from estados_docs_tramites where id = TGD.estado_docs_tramite_id) as estado_doc_nombre
            FROM tramites_gestionados_docs as TGD
            JOIN tramites_gestionados ON tramites_gestionados.id = TGD.tramite_gestionado_id
            JOIN estados_tramites on estados_tramites.id = tramites_gestionados.estado_tramite_id
            JOIN tipos_documentos on tipos_documentos.id = TGD.tipo_documento_id
            JOIN tramites on tramites.id = tramites_gestionados.tramite_id
			JOIN estados_docs_tramites on estados_docs_tramites.id = TGD.estado_docs_tramite_id
            WHERE tramites_gestionados.id = $tramite_gestionado_id
            AND tramites_gestionados.activo = true
			AND TGD.estado_docs_tramite_id not in (1);";
        error_log($sql);
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_observacion_tramite($id_tramite_gestionado)
    {
        $conectar = parent::Conexion();
        $sql = "select observacion from tramites_gestionados where id='$id_tramite_gestionado' and activo = true;";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizar_tramites($datos)
    {
        try {
            $db = parent::Conexion();
            $respuesta = $db;
            $db->beginTransaction();

            $sql = "UPDATE public.tramites_gestionados
                SET 
                estado_tramite_id=" . $datos['estado_tramite_id'] . ", 
                fecha_mod='" . $datos['fecha_crea'] . "'::timestamp, user_mod=" . $datos['usuario_id'] . ",
                observacion='" . $datos["observacion"] . "'
                WHERE id = " . $datos['tramite_gestionado_id'] . ";";

            $db->exec($sql);

            $sql = "INSERT INTO public.movimientos_tramites(
                tramite_gestionado_id, area_asignada_id, 
                fecha_crea, fecha_mod, 
                user_crea, user_mod, estado_tramite_id)
                VALUES (" . $datos['tramite_gestionado_id'] . ", 1, 
                '" . $datos['fecha_crea'] . "'::timestamp,'" . $datos['fecha_crea'] . "'::timestamp,"
                . $datos['usuario_id'] . "," . $datos['usuario_id'] . ", " . $datos["estado_tramite_id"] . ");";

            $db->exec($sql);

            $item = 0;
            $tiposDocumentos = json_decode($datos['tiposDocumentos']);

            if (is_array($tiposDocumentos)) {
                // Consulta SQL para obtener tramite_id basado en id de tramite_gestionado
                $sql = "SELECT tramite_id FROM tramites_gestionados WHERE id = :id";

                // Prepara la consulta SQL
                $query = $db->prepare($sql);

                // Vincula el parámetro :id con el valor de tramite_gestionado_id en $datos
                $query->bindParam(':id', $datos['tramite_gestionado_id'], PDO::PARAM_INT);

                // Ejecuta la consulta SQL
                $query->execute();

                // Obtiene el resultado de la consulta como un arreglo asociativo
                $result = $query->fetch(PDO::FETCH_ASSOC); // Utiliza fetch en lugar de fetchAll para obtener una sola fila

                // Verifica si se obtuvo algún resultado
                if ($result) {
                    // Si hay resultado, asigna el valor de tramite_id a la variable $tramite_id
                    $tramite_id = $result['tramite_id'];
                    // Ahora puedes usar $tramite_id según lo necesites
                } else {
                    // Maneja el caso cuando no hay resultados
                    echo "No se encontró el tramite_id para el id especificado.";
                }

                $item = 0;
                $nombre_archivo = array();
                while ($item < count($tiposDocumentos)) {
                   // $tramite_id = $datos['tramite_id'];
                    $idTypeFile = $tiposDocumentos[$item];
                    $cedula = $datos['cedula_user'];
                 
                    $nombre_archivo = Certificacion::buscarNombreArchivo($idTypeFile, $tramite_id, $cedula, $db);

                    $sql = "SELECT id FROM tramites_gestionados_docs 
                        where tramite_gestionado_id = " . $datos['tramite_gestionado_id'] . "
                        AND tipo_documento_id = $idTypeFile;";
                    $query = $db->prepare($sql);
                    $query->execute();
                    $doc_existente = $query->fetchAll(PDO::FETCH_ASSOC);
                    if (count($doc_existente) > 0) {
                        $sql = "UPDATE public.tramites_gestionados_docs
                            SET documento='$nombre_archivo', fecha_mod=current_timestamp, 
                            user_mod=" . $datos['usuario_id'] . "
                            WHERE id = " . $doc_existente[0]["id"] . ";";
                    } else {
                        $sql = "INSERT INTO tramites_gestionados_docs (
                                tramite_gestionado_id, documento, 
                                estado_docs_tramite_id, tipo_documento_id, 
                                fecha_crea, fecha_mod, 
                                user_crea, user_mod, activo)
                            VALUES (
                                " . $datos['tramite_gestionado_id'] . ",'" . $nombre_archivo . "',
                                " . $datos['estado_docs_tramite_id'] . "," . $idTypeFile . ",
                                '" . $datos['fecha_crea'] . "'::timestamp,'" . $datos['fecha_crea'] . "'::timestamp,
                                " . $datos['usuario_id'] . "," . $datos['usuario_id'] . "," . $datos['activo'] . ");";
                    }

                    $item++;
                    $db->exec($sql);

                }
            }
        } catch (Exception $e) {
            $db->rollBack();

            $men = str_replace('SQLSTATE[P0001]: Raise exception: 7 ERROR:', '', $e->getMessage());
            error_log($men . ' ' . $sql);

            return "error";

        }
        if ($respuesta === $db) {
            $db->commit();
            $db = null;
            return "ok";

        }
    }

    public function delete_tramite_gestionado($id_tramite_gestionado, $usuario_id, $fecha_hora, $estado_tramite_id)
    {
        try {
            $db = parent::Conexion();
            $respuesta = $db;
            $db->beginTransaction();

            $sql = "UPDATE public.tramites_gestionados
                SET activo = false, estado_tramite_id = $estado_tramite_id
                WHERE id = $id_tramite_gestionado;";

            $db->exec($sql);

            // Se inserta un movimiento para registrar la anulación del trámite por parte del solicitante
            $sql = "INSERT INTO public.movimientos_tramites(
                    tramite_gestionado_id, area_asignada_id, 
                    fecha_crea, fecha_mod, 
                    user_crea, user_mod, estado_tramite_id)
                    VALUES ($id_tramite_gestionado, 8, 
                    '$fecha_hora'::timestamp,'$fecha_hora'::timestamp,
                    $usuario_id,$usuario_id, $estado_tramite_id);";

            $db->exec($sql);

            $sql = "SELECT id FROM tramites_gestionados_docs 
                    where tramite_gestionado_id = $id_tramite_gestionado ";
            $query = $db->prepare($sql);
            $query->execute();
            $docs_existentes = $query->fetchAll(PDO::FETCH_ASSOC);

            foreach ($docs_existentes as $doc_existente) {
                $sql = "UPDATE public.tramites_gestionados_docs
                    SET estado_docs_tramite_id=5, fecha_mod='$fecha_hora'::timestamp, 
                    user_mod=$usuario_id, activo = false
                    WHERE id = " . $doc_existente["id"] . ";";

                $db->exec($sql);

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

    public static function get_mi_aprendizaje_cursos($usuario_id)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT cursos.id as curso_id, cursos.nombre as nombre_curso, 
        (SELECT nombre FROM cursos_categorias WHERE id = categoria_id) AS nombre_categoria,
        (SELECT nombre || ' ' || apellido FROM cursos_instructores WHERE id = instructor_id) AS instructor,
        validacion, certificacion, imagen_portada,
        (SELECT count(*) FROM cursos_secciones_tareas cst WHERE cst.seccion_id IN 
        (SELECT id FROM cursos_secciones WHERE curso_id = cursos.id)) as cantidad_tareas
        FROM cursos
        LEFT JOIN tramites t ON t.id = cursos.tramite_id
        WHERE cursos.activo = true
        AND t.tipo_solicitud = 'CURSO'
        AND cursos.id in (SELECT curso_id from cursos_inscriptos WHERE usuario_id = $usuario_id);
        ";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function get_mi_aprendizaje_certificaciones($usuario_id)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT cursos.id as curso_id, cursos.nombre as nombre_curso, 
        (SELECT nombre FROM cursos_categorias WHERE id = categoria_id) AS nombre_categoria,
        (SELECT nombre || ' ' || apellido FROM cursos_instructores WHERE id = instructor_id) AS instructor,
        validacion, certificacion, imagen_portada,
        (SELECT count(*) FROM cursos_secciones_tareas cst WHERE cst.seccion_id IN 
        (SELECT id FROM cursos_secciones WHERE curso_id = cursos.id)) as cantidad_tareas
        FROM cursos
        LEFT JOIN tramites t ON t.id = cursos.tramite_id
        WHERE cursos.activo = true
        AND t.tipo_solicitud = 'CERT'
        AND cursos.id in (SELECT curso_id from cursos_inscriptos WHERE usuario_id = $usuario_id);
        ";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function get_mi_aprendizaje_filtrado($categories, $tipo_solicitud, $usuario_id)
    {
        $condicion_categorias = "";
        $params = [$tipo_solicitud, $usuario_id];

        if (count($categories) > 0) {
            // Create placeholders for each category
            $placeholders = implode(',', array_fill(0, count($categories), '?'));
            $condicion_categorias = " AND cc.nombre IN ($placeholders)";
            $params = array_merge($params, $categories);
        }

        $conectar = parent::Conexion();
        $sql = "SELECT cursos.id as curso_id, cursos.nombre as nombre_curso, 
        (SELECT nombre FROM cursos_categorias WHERE id = categoria_id) AS nombre_categoria,
        (SELECT nombre || ' ' || apellido FROM cursos_instructores WHERE id = instructor_id) AS instructor,
        validacion, certificacion, imagen_portada,
        (SELECT count(*) FROM cursos_secciones_tareas cst WHERE cst.seccion_id IN 
        (SELECT id FROM cursos_secciones WHERE curso_id = cursos.id)) as cantidad_tareas
        FROM cursos
        JOIN cursos_categorias cc ON cc.id = cursos.categoria_id
        LEFT JOIN tramites t ON t.id = cursos.tramite_id
        WHERE cursos.activo = true
        AND t.tipo_solicitud = ?
        AND cursos.id in (SELECT curso_id from cursos_inscriptos WHERE usuario_id = ?)
        $condicion_categorias";

        // Create a string for logging the final query
        $finalQuery = $sql;
        foreach ($params as $param) {
            $finalQuery = preg_replace('/\?/', is_numeric($param) ? $param : "'" . $param . "'", $finalQuery, 1);
        }
        $query = $conectar->prepare($sql);
        $query->execute($params);
        $conectar = null;

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function get_informacion_curso($id_curso)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT nombre as nombre_curso, 
        (SELECT nombre FROM cursos_categorias WHERE id = categoria_id) AS nombre_categoria,
        (SELECT nombre || ' ' || apellido FROM cursos_instructores WHERE id = instructor_id) AS instructor,
        descripcion AS descripcion_curso, user_crea
        validacion, certificacion, imagen_portada,
        aprendizaje, TO_CHAR(fecha_mod::timestamp, 'DD/MM/YYYY') AS fecha_curso,
        (SELECT count(*) FROM cursos_secciones WHERE curso_id = cursos.id) AS cantidad_lecciones,
        (SELECT count(*) FROM cursos_inscriptos WHERE curso_id = cursos.id) AS cantidad_inscriptos,
        (SELECT count(*) FROM cursos_secciones_tareas cst WHERE cst.seccion_id IN 
        (SELECT id FROM cursos_secciones WHERE curso_id = cursos.id)) as cantidad_tareas
        FROM cursos
        WHERE activo = true
        AND id = $id_curso;";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;

        return $query->fetch();
    }

    public static function get_categorias_cursos($usuario_id)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT id as id_categoria,
        nombre, 
		(SELECT count(*) FROM cursos WHERE cursos.activo = true
		AND cursos.id in (SELECT curso_id FROM cursos_inscriptos
					WHERE usuario_id = $usuario_id ) 
                    AND tramite_id IN (SELECT id 
                        FROM tramites 
                        WHERE tipo_solicitud = 'CURSO')) AS cantidad_total_cursos,
        (SELECT count(*) FROM cursos 
        WHERE categoria_id = cursos_categorias.id AND cursos.activo = true
		AND id in (SELECT curso_id FROM cursos_inscriptos
					WHERE usuario_id = $usuario_id)
                    AND tramite_id IN (SELECT id 
                        FROM tramites 
                        WHERE tipo_solicitud = 'CURSO')) AS cantidad_cursos_categoria
        FROM cursos_categorias;
        ";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function get_categorias_certificaciones($usuario_id)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT id as id_categoria,
        nombre, 
		(SELECT count(*) FROM cursos WHERE cursos.activo = true
		AND cursos.id in (SELECT curso_id FROM cursos_inscriptos
					WHERE usuario_id = $usuario_id ) 
                    AND tramite_id IN (SELECT id 
                        FROM tramites 
                        WHERE tipo_solicitud = 'CERT')) AS cantidad_total_cursos,
        (SELECT count(*) FROM cursos 
        WHERE categoria_id = cursos_categorias.id AND cursos.activo = true
		AND id in (SELECT curso_id FROM cursos_inscriptos
					WHERE usuario_id = $usuario_id)
                    AND tramite_id IN (SELECT id 
                        FROM tramites 
                        WHERE tipo_solicitud = 'CERT')) AS cantidad_cursos_categoria
        FROM cursos_categorias;
        ";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function get_tramites_cert_cursos()
    {
        $conectar = parent::Conexion();
        $sql = "SELECT id as capacitacion_id,
        tipo_solicitud || ': ' || nombre as capacitacion_nombre
        FROM tramites
        WHERE tipo_solicitud = 'CURSO'  OR tipo_solicitud = 'CONCURSO'
        OR tipo_solicitud = 'CERT';";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function get_mis_cursos_estados($usuario_id)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT 
        (SELECT COUNT(*) 
         FROM cursos_inscriptos 
         WHERE activo = true
           AND usuario_id = 2 
           AND estado_curso_id = 2 
           AND curso_id IN (SELECT id 
                            FROM cursos 
                            WHERE tramite_id IN (SELECT id 
                                                 FROM tramites 
                                                 WHERE tipo_solicitud = 'CURSO'))
        ) AS cantidad_en_curso,
        
        (SELECT COUNT(*) 
         FROM cursos_inscriptos 
         WHERE activo = true
           AND usuario_id = 2 
           AND estado_curso_id = 3
           AND curso_id IN (SELECT id 
                            FROM cursos 
                            WHERE tramite_id IN (SELECT id 
                                                 FROM tramites 
                                                 WHERE tipo_solicitud = 'CURSO'))
        ) AS cantidad_finalizados;
        ";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;

        return $query->fetch();
    }

    public static function get_mis_certificaciones_estados($usuario_id)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT 
        (SELECT COUNT(*) 
         FROM cursos_inscriptos 
         WHERE activo = true
           AND usuario_id = 2 
           AND estado_curso_id = 2 
           AND curso_id IN (SELECT id 
                            FROM cursos 
                            WHERE tramite_id IN (SELECT id 
                                                 FROM tramites 
                                                 WHERE tipo_solicitud = 'CERT'))
        ) AS cantidad_en_curso,
        
        (SELECT COUNT(*) 
         FROM cursos_inscriptos 
         WHERE activo = true
           AND usuario_id = 2 
           AND estado_curso_id = 3
           AND curso_id IN (SELECT id 
                            FROM cursos 
                            WHERE tramite_id IN (SELECT id 
                                                 FROM tramites 
                                                 WHERE tipo_solicitud = 'CERT'))
        ) AS cantidad_finalizados;
        ";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;

        return $query->fetch();
    }



    /*=============================================
    TAREAS
    =============================================*/
    public static function get_tareas_x_seccion($seccion_id)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT id as tarea_id, 
        titulo AS titulo_tarea,
        CASE 
            WHEN tipo_tarea = 1 THEN 'Trabajo Práctico' 
            ELSE 'Cuestionario' 
        END AS tipo_tarea_nombre, tipo_tarea,
        to_char(fecha_limite, 'DD/MM/YYYY') as fecha_limite,
        total_puntos,
        CASE WHEN tipo_tarea = 1 THEN
        (SELECT puntos_logrados FROM cursos_tareas_trabajos_practicos
        WHERE tarea_id = cursos_secciones_tareas.id)
        ELSE (SELECT puntos_logrados FROM cursos_tareas_respuestas ctr 
        WHERE ctr.tarea_id = cursos_secciones_tareas.id) END AS entrega
        FROM cursos_secciones_tareas 
        WHERE seccion_id = $seccion_id 
        AND activo = true;";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function get_info_tarea($tarea_id, $usuario_id)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT titulo AS titulo_tarea,
        CASE 
            WHEN tipo_tarea = 1 THEN 'Trabajo Práctico' 
            ELSE 'Cuestionario' 
        END AS tipo_tarea_nombre,
        tipo_tarea, descripcion, archivo_url,
        to_char(fecha_limite, 'DD/MM/YYYY') as fecha_limite,
        to_char(cttp.fecha_mod, 'DD/MM/YYYY') as fecha_entrega,
        to_char(cttp.fecha_mod, 'HH24:MI') as hora_entrega,
        cantidad_intentos, seccion_id, cst.total_puntos,
        cttp.adjunto AS adjunto, tiempo_limite,
        cttp.trabajo_texto AS trabajo_texto, cttp.observacion_instructor,
        (SELECT curso_id FROM cursos_secciones cs WHERE cs.id = cst.seccion_id) AS curso_id,
        cttp.intentos_realizados AS intentos_realizados_tp, cttp.puntos_logrados as puntos_logrados_tp,
        ctr.puntos_logrados AS puntos_logrados_c,
        ctr.intentos_realizados AS intentos_realizados_c
        FROM cursos_secciones_tareas cst
        LEFT JOIN cursos_tareas_trabajos_practicos cttp ON cttp.tarea_id = cst.id
        LEFT JOIN cursos_tareas_respuestas ctr ON ctr.tarea_id = cst.id
        WHERE cst.id = $tarea_id 
            AND cst.activo = true
            AND (
                NOT EXISTS (
                    SELECT 1 
                    FROM cursos_tareas_trabajos_practicos sub_cttp
                    WHERE sub_cttp.tarea_id = cst.id
                )
                OR cttp.user_crea = $usuario_id
            );
        ";

        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function get_ejercicios_x_tarea($id_tarea)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT id, texto_ejercicio, 
        tipo_ejercicio, imagen_url, 
        respuesta_correcta, puntaje, 
        numero_ejercicio
            FROM public.cursos_tareas_ejercicios
            WHERE tarea_id = $id_tarea 
            AND activo=true
            ORDER BY numero_ejercicio;";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function get_opciones_ejercicio($id_ejercicio)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT id AS opcion_id, opcion_texto
        FROM public.cursos_ejercicios_opciones
            WHERE ejercicio_id = $id_ejercicio
            AND activo=true;";
        // error_log($sql);
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }



    public static function get_tarea_id($id_entrega)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT tarea_id, user_crea as alumno 
            FROM public.cursos_tareas_trabajos_practicos
            WHERE id = $id_entrega
            AND activo=true;";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return $query->fetch();
    }



    /*=============================================
    ENTREGAS
    =============================================*/

    public function insert_entrega_tp($datos)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT cttp.id AS entrega_id, intentos_realizados, 
        cantidad_intentos AS intentos_permitidos
        FROM cursos_tareas_trabajos_practicos cttp
        JOIN cursos_secciones_tareas cst ON cst.id = cttp.tarea_id
        WHERE tarea_id = " . $datos['tarea_id'] . "
        AND cttp.user_crea = " . $datos['usuario_id'] . ";";
        $query = $conectar->prepare($sql);
        $query->execute();
        $data = $query->fetch();
        if ($data) {
            $entrega_id = $data['0'];
            $intentos_realizados = $data['1'];
            $intentos_permitidos = $data['2'];
            if ($intentos_realizados < $intentos_permitidos) {
                $intentos_realizados++;
                $sql = "UPDATE public.cursos_tareas_trabajos_practicos
                SET trabajo_texto= :trabajo_texto, 
                adjunto='" . $datos['adjunto'] . "', 
                fecha_mod='" . $datos['fecha_hora'] . "'::timestamp, user_mod= " . $datos['usuario_id'] . ", 
                intentos_realizados=$intentos_realizados
                WHERE id = $entrega_id;";
            } else {
                return 'Ya no tiene intentos disponibles.';
            }
        } else {
            $sql = "INSERT INTO public.cursos_tareas_trabajos_practicos(
                tarea_id, trabajo_texto, 
                adjunto, fecha_crea, 
                fecha_mod, user_crea, 
                user_mod, activo, 
                intentos_realizados)
                VALUES (" . $datos['tarea_id'] . ", :trabajo_texto, 
                        '" . $datos['adjunto'] . "', '" . $datos['fecha_hora'] . "'::timestamp, 
                        '" . $datos['fecha_hora'] . "'::timestamp, " . $datos['usuario_id'] . ", 
                        " . $datos['usuario_id'] . ", " . $datos['activo'] . ", 
                        1); ";
        }

        // error_log($sql);
        $query = $conectar->prepare($sql);
        $query->bindParam(':trabajo_texto', $datos["trabajo_texto"]);
        $query->execute();
        $conectar = null;
        return 'ok';
    }

    public function insert_cuestionario($datos)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT id, intentos_realizados FROM cursos_tareas_respuestas 
                WHERE user_crea = :user_crea
                AND tarea_id = :tarea_id;";
        $query = $conectar->prepare($sql);
        $query->bindParam(':tarea_id', $datos["tarea_id"]);
        $query->bindParam(':user_crea', $datos["usuario_id"]);
        $query->execute();
        $data = $query->fetch();
        if (is_array($data)) {
            $respuesta_id = $data['0'];
            $intentos = $data['1'];
            $sql = "UPDATE public.cursos_tareas_respuestas
            SET fecha_mod=:fecha_mod::timestamp, user_mod=:user_mod, 
            intentos_realizados=$intentos+1
            WHERE id = $respuesta_id;";
            $query = $conectar->prepare($sql);
            $query->bindParam(':fecha_mod', $datos["fecha_hora"]);
            $query->bindParam(':user_mod', $datos["usuario_id"]);

        } else {
            $sql = "INSERT INTO cursos_tareas_respuestas(
                tarea_id, fecha_crea, 
                fecha_mod, user_crea, 
                user_mod, activo, intentos_realizados 
            ) VALUES (:tarea_id, :fecha_crea::timestamp, 
                    :fecha_mod::timestamp, :user_crea, 
                    :user_mod, :activo, 
                    1);";
            $query = $conectar->prepare($sql);
            $query->bindParam(':tarea_id', $datos["tarea_id"]);
            $query->bindParam(':fecha_crea', $datos["fecha_hora"]);
            $query->bindParam(':fecha_mod', $datos["fecha_hora"]);
            $query->bindParam(':user_crea', $datos["usuario_id"]);
            $query->bindParam(':user_mod', $datos["usuario_id"]);
            $query->bindParam(':activo', $datos["activo"]);
        }



        // error_log($sql);

        $query->execute();
        $conectar = null;
        return 'ok';
    }

    public function insert_respuestas_cuestionarios($datos)
    {
        try {
            $conectar = parent::Conexion();
            $respuesta = $conectar;
            $conectar->beginTransaction();
            $sql = "UPDATE public.cursos_tareas_respuestas
                SET respuestas=:respuestas, fecha_mod=:fecha_mod::timestamp, 
                user_mod=:user_mod, puntos_logrados=:puntos_logrados
                WHERE user_crea = :user_mod
                AND tarea_id = :tarea_id;";

            $query = $conectar->prepare($sql);
            $query->bindParam(':tarea_id', $datos["tarea_id"]);
            $query->bindParam(':respuestas', $datos["respuestas"]);
            $query->bindParam(':fecha_mod', $datos["fecha_hora"]);
            $query->bindParam(':user_mod', $datos["usuario_id"]);
            $query->bindParam(':puntos_logrados', $datos["puntos_logrados"]);
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

    public function get_puntaje_ejercicio($ejercicio_id)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT puntaje,
        (SELECT count(*) FROM cursos_ejercicios_opciones
        WHERE ejercicio_id = $ejercicio_id) AS total_opciones
        from cursos_tareas_ejercicios
        where id = $ejercicio_id  
        AND activo = true;";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return $query->fetch();
    }

    public function get_opcion_es_correcto($opcion_id)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT es_correcto, 
        opcion_texto
        FROM cursos_ejercicios_opciones ceo
        WHERE id = $opcion_id  
        AND activo = true;";

        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return $query->fetch();
    }

    public static function get_respuesta_correcta($id_ejercicio)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT respuesta_correcta
        FROM public.cursos_tareas_ejercicios
            WHERE id = $id_ejercicio
            AND activo=true;";
        // error_log($sql);
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;

        return $query->fetch()[0];
    }

    public static function get_calificaciones_curso($id_curso)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT cttp.id AS id_entrega, 
        cs.titulo || ' - ' || cst.titulo AS nombre_curso,
        cttp.puntos_logrados, TO_CHAR(cttp.fecha_crea::timestamp, 'DD/MM/YYYY') AS fecha_entrega, 
        ctr.intentos_realizados,cttp.intentos_realizados, cst.total_puntos
        FROM cursos_secciones_tareas cst
        JOIN cursos_secciones cs ON cs.id = cst.seccion_id
        LEFT JOIN public.cursos_tareas_trabajos_practicos cttp ON cst.id = cttp.tarea_id
        LEFT JOIN cursos_tareas_respuestas ctr ON ctr.id = cttp.tarea_id
        WHERE cs.curso_id = $id_curso  
        AND cttp.activo = true
        AND ctr.activo = true;";
        error_log($sql);

        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>