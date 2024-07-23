<?php
class Concurso extends Conectar
{
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
        AND t.tipo_solicitud = 'CONCURSO'
        AND tg.usuario_id = $usuario_id
        ORDER BY tg.id, COALESCE(mt.fecha_crea, tg.fecha_mod) DESC;";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_tramites_concursos()
    {
        $conectar = parent::Conexion();
        $sql = "SELECT id as tramite_id, nombre as tramite, 
        url from tramites where tipo_tramite_id = 1 and activo =true
        AND tipo_solicitud = 'CONCURSO'";
        
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
                    observacion)
                VALUES (
                    " . $datos['usuario_id'] . ", " . $datos['estado_tramite_id'] . ",
                    " . $datos['tramite_id'] . ",'" . $datos['fecha_crea'] . "'::timestamp,
                    '" . $datos['fecha_crea'] . "'::timestamp,'" . $datos['usuario_id'] . "','" . $datos['usuario_id'] . "',
                    " . $datos['activo'] . ",'" . $datos['forma_solicitud'] . "',
                '" . $datos['observacion'] . "');";

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
            $db->exec($sql);

            $documentos_adjuntos = json_decode($datos['documentos_adjuntos'], true);
            error_log(count($documentos_adjuntos));
            if (is_array($documentos_adjuntos)) {

                foreach($documentos_adjuntos as $documento_adjunto){
                    
                    $sql = "INSERT INTO tramites_gestionados_docs (
                        tramite_gestionado_id, documento, 
                        estado_docs_tramite_id, tipo_documento_id, 
                        fecha_crea, fecha_mod, 
                        user_crea, user_mod, activo)
                    VALUES (
                        " . $tramite_gestionado_id . ",'" . $documento_adjunto['value'] . "',
                        " . $datos['estado_docs_tramite_id'] . "," . $documento_adjunto['id'] . ",
                        '" . $datos['fecha_crea'] . "'::timestamp,'" . $datos['fecha_crea'] . "'::timestamp,
                        " . $datos['usuario_id'] . "," . $datos['usuario_id'] . "," . $datos['activo'] . ");";
                        error_log($sql);
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

            $documentos_adjuntos = json_decode($datos['documentos_adjuntos'], true);
            error_log(count($documentos_adjuntos));
            if (is_array($documentos_adjuntos)) {

                foreach($documentos_adjuntos as $documento_adjunto){

                    $sql = "SELECT id FROM tramites_gestionados_docs 
                        where tramite_gestionado_id = " . $datos['tramite_gestionado_id'] . "
                        AND tipo_documento_id =" . $documento_adjunto['id'] . ";";
                    $query = $db->prepare($sql);
                    $query->execute();
                    $doc_existente = $query->fetchAll(PDO::FETCH_ASSOC);

                    if (count($doc_existente) > 0) {
                        $sql = "UPDATE public.tramites_gestionados_docs
                            SET documento='" . $documento_adjunto['value'] . "', fecha_mod='" . $datos['fecha_crea'] . "'::timestamp, 
                            user_mod=" . $datos['usuario_id'] . "
                            WHERE id = " . $doc_existente[0]["id"] . ";";
                    } else {
                        $sql = "INSERT INTO tramites_gestionados_docs (
                            tramite_gestionado_id, documento, 
                            estado_docs_tramite_id, tipo_documento_id, 
                            fecha_crea, fecha_mod, 
                            user_crea, user_mod, activo)
                        VALUES (
                            " . $datos['tramite_gestionado_id'] . ",'" . $documento_adjunto['value'] . "',
                            " . $datos['estado_docs_tramite_id'] . "," . $documento_adjunto['id'] . ",
                            '" . $datos['fecha_crea'] . "'::timestamp,'" . $datos['fecha_crea'] . "'::timestamp,
                            " . $datos['usuario_id'] . "," . $datos['usuario_id'] . "," . $datos['activo'] . ");";
                    }
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
}
?>