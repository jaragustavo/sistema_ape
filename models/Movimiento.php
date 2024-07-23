<?php
class Movimiento extends Conectar
{
    /* TODO: Listar trámites según el área del usuario*/
    public static function get_tramites($area_id, $table, $usuario_id)
    {
        $conectar = parent::Conexion();
        $condicion_tabla = "";
        if ($table == "usuario") {
            $condicion_tabla = " AND movimientos_tramites.usuario_asignado_id = $usuario_id";
        }
        $sql = "SELECT DISTINCT ON (tg.id) 
                    tg.id AS tramite_gestionado_id,
                    tramites.id AS tramite_id,
                    usuarios.nombre || ' ' || usuarios.apellido AS usuario_solicitante,
                    (SELECT nombre || ' ' || apellido FROM usuarios WHERE id = movimientos_tramites.usuario_asignado_id) AS usuario_asignado,
                    tg.fecha_crea AS fecha_solicitud,
                    e.nombre AS estado_actual,
                    tg.fecha_mod AS ultimo_movimiento,
                    tramites.nombre AS tramite_nombre,
                    areas.nombre AS area_asignada,
                    ROUND(EXTRACT(EPOCH FROM (NOW() AT TIME ZONE 'America/Asuncion' - tg.fecha_mod)) / 3600) AS horas_transcurridas,
                    (SELECT COUNT(DISTINCT et.paso) 
                    FROM estados_tramites et 
                    WHERE et.activo = true AND et.tramite_id = tg.tramite_id) AS cantidad_pasos,
                    (SELECT paso FROM estados_tramites et 
                    WHERE et.tramite_id = tg.tramite_id AND et.estado_id = tg.estado_tramite_id AND et.activo = true) AS paso
                FROM movimientos_tramites
                JOIN tramites_gestionados tg ON tg.id = movimientos_tramites.tramite_gestionado_id
                JOIN tramites ON tramites.id = tg.tramite_id
                JOIN estados e ON e.id = COALESCE(tg.estado_tramite_id, movimientos_tramites.estado_tramite_id)
                JOIN usuarios ON usuarios.id = tg.usuario_id
                JOIN areas ON areas.id = movimientos_tramites.area_asignada_id
                WHERE movimientos_tramites.area_asignada_id = $area_id
                AND movimientos_tramites.activo = true
                AND e.id <> 5 -- estados.id = 5 -> PENDIENTE ENVÍO
                AND tg.activo = true    AND tg.activo = true $condicion_tabla
                ORDER BY tg.id, movimientos_tramites.fecha_mod DESC;";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
     public static function requisitos_tramite($id)
     {
         $conectar = parent::Conexion();
      
         $sql = "select *
                 from  public.tramites_gestionados
                 where id = $id";

         $query = $conectar->prepare($sql);
         $query->execute();
         $db = null;
         return $query->fetch(PDO::FETCH_ASSOC);
     }

 
     public static function tramite_gestionado_jdato($id)
      {
            $conectar = parent::Conexion();

            $sql = "SELECT datos FROM public.tramites_gestionados_datos WHERE tramite_gestionado_id = :id";
            $query = $conectar->prepare($sql);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);

            return $result['datos'];
       
      }

    // Actualizar el usuario asignado de cada trámite cuando se asigna a sí mismo
    public function update_usuario_asignado_tramite($tramites_autoasignados, $fecha_hora, $usuario_id, $area_id)
    {

        try {
            $db = parent::Conexion();
            $respuesta = $db;
            $db->beginTransaction();
            foreach ($tramites_autoasignados as $tramite_autoasignado) {

                $estado_tramite_id = 2;
                
                $sql = "UPDATE public.tramites_gestionados
                        SET estado_tramite_id=$estado_tramite_id, fecha_mod='$fecha_hora'::timestamp, 
                        user_mod=$usuario_id
                        WHERE id = $tramite_autoasignado;";
                $query = $db->prepare($sql);
                $query->execute();
             
                if ($estado_tramite_id != 12) { //estado = 12 OBS. CORREGIDAS
                  
                    $estado_tramite_id = 2; //estado = 2 EN REVISIÓN
              
                    $sql = "INSERT INTO public.movimientos_tramites(
                        tramite_gestionado_id, area_asignada_id, 
                        usuario_asignado_id, fecha_crea, 
                        fecha_mod, user_crea, user_mod, 
                        activo, estado_tramite_id)
                        VALUES ($tramite_autoasignado, $area_id, 
                        $usuario_id, '$fecha_hora'::timestamp, 
                        '$fecha_hora'::timestamp, $usuario_id, $usuario_id, true, $estado_tramite_id);";
                    $query = $db->prepare($sql);
                    $query->execute();
                }
                
            }

        } catch (Exception $e) {
            error_log("$$$$$$$$$$$" . $e->getMessage());
            $db->rollBack();

            $men = str_replace('SQLSTATE[P0001]: Raise exception: 7 ERROR:', '', $e->getMessage());
            error_log($men . ' ' . $sql);

            return "error";

        }
        if ($respuesta === $db) {
            $db->commit();
            return 'ok';
        }
        $db = null;
    }

    // Muestra la pantalla para que el fiscalizador pueda abrir la solicitud
    public static function revisar_solicitud($tramite_gestionado_id)
    {
        
        $db = parent::Conexion();
        $sql = "SELECT 
                    TGD.id as documento_id,
                    TGD.documento as documento,
                    TGD.tipo_documento_id as tipo_doc_id,
                    tramites.tipo_solicitud,
                    tramites.id tramite_id,
                    tramites.tramite_json_requisito,
                    tipos_documentos.documento as tipo_doc,
                    tramites_gestionados.id as tramite_gestionado_id,
                    tramites.nombre AS nombre_tramite,
                    tramites_gestionados.fecha_mod as fecha_solicitud,
                    TO_CHAR(TGD.fecha_mod, 'DD Mon YYYY') AS fecha_formato_doc,
                    TO_CHAR(TGD.fecha_mod, 'HH24:MI') AS hora_formato_doc,
                    (SELECT COUNT(et.id) 
                    FROM estados_tramites et 
                    WHERE et.estado_id = 7 
                    AND et.tramite_id = tramites_gestionados.tramite_id
                    AND et.activo = true) AS paso_comite,
                    e.permisos AS permisos,
                    e.nombre as estado_actual,
                    tramites_gestionados.observacion AS observacion_inscripcion,
                    tramites_gestionados.fecha_mod as ultimo_movimiento,
                    TGD.estado_docs_tramite_id as estado_doc_id,
                    (SELECT nombre 
                    FROM estados_docs_tramites 
                    WHERE id = TGD.estado_docs_tramite_id) as estado_doc_nombre
                FROM tramites_gestionados
                LEFT JOIN tramites_gestionados_docs AS TGD 
                    ON tramites_gestionados.id = TGD.tramite_gestionado_id
                LEFT JOIN estados AS e 
                    ON e.id = tramites_gestionados.estado_tramite_id
                LEFT JOIN tipos_documentos 
                    ON tipos_documentos.id = TGD.tipo_documento_id
                LEFT JOIN tramites 
                    ON tramites.id = tramites_gestionados.tramite_id
                WHERE tramites_gestionados.id = $tramite_gestionado_id
                AND tramites_gestionados.activo = true;";
      
        $query = $db->prepare($sql);
        $query->execute();
        $db = null;
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function get_estados_documentos_id()
    {
        $db = parent::Conexion();
        $sql = "SELECT id as estado_documento_id, nombre as estado_documento FROM estados_docs_tramites;";
        $query = $db->prepare($sql);
        $query->execute();
        $db = null;
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    // Actualizar los estados de los documentos de la solicitud, 
    // además del estado del trámite gestionado y se agrega el movimiento generado
   
    public function update_estados_documentos($estadosDocs, $idTramiteGestionado, $observacion, $fecha_hora, $usuario_id, $estado_tramite, $tramiteJsonRequisito)
    {
        try {
            $db = parent::Conexion();
            $respuesta = $db;
            $db->beginTransaction();
    
            // Convertir tramiteJsonRequisito a JSON válido para almacenarlo en la base de datos
            $tramiteJsonRequisito = json_encode($tramiteJsonRequisito, JSON_UNESCAPED_UNICODE);
    
            $sql = "UPDATE public.tramites_gestionados
            SET estado_tramite_id=$estado_tramite, fecha_mod='$fecha_hora'::timestamp, user_mod=$usuario_id, 
            observacion_evaluador='$observacion' ,
            tramite_json_requisito = '$tramiteJsonRequisito'
            WHERE id = $idTramiteGestionado;";

            error_log('$$$$$$$$$$$$$$$ '.$sql);
            $query = $db->prepare($sql);
            $query->execute();
        
            $sql = "INSERT INTO public.movimientos_tramites(
                tramite_gestionado_id, area_asignada_id,
                usuario_asignado_id, fecha_crea, 
                fecha_mod, user_crea, user_mod, 
                activo, estado_tramite_id)
                VALUES ($idTramiteGestionado, 1,
                $usuario_id, '$fecha_hora'::timestamp, 
                current_timestamp, $usuario_id, $usuario_id, true, $estado_tramite);";
 
            $query = $db->prepare($sql);
            $query->execute();
         
           

                foreach ($estadosDocs as $key => $value) {
                   
                    $idDoc = str_replace('estado_documento', '', $key);
                    if ($value > 0) {
                       
                        $sql = "UPDATE public.tramites_gestionados_docs
                            SET estado_docs_tramite_id=$value, fecha_mod='$fecha_hora'::timestamp, user_mod=$usuario_id
                            WHERE id = $idDoc;";
                        $query = $db->prepare($sql);
                        $query->execute();
                    }
                }
            $db->commit();
            $resultado = "ok";
        } catch (Exception $e) {
            $db->rollback();
            $resultado = "error";
            error_log($e->getMessage());
        }
    
        $db = null;
        return $resultado;
    }



    public function update_tramite_aprobado($estadoDoc, $estadoTramite, $idTramiteGestionado, $fecha_hora, $usuario_id)
    {
        try {
            $db = parent::Conexion();
            $respuesta = $db;
            $db->beginTransaction();
            $sql = "UPDATE public.tramites_gestionados
                SET estado_tramite_id=$estadoTramite, fecha_mod='$fecha_hora'::timestamp, user_mod=$usuario_id
                WHERE id = $idTramiteGestionado;";
            $query = $db->prepare($sql);
            $query->execute();

            $sql = "INSERT INTO public.movimientos_tramites(
                    tramite_gestionado_id, area_asignada_id, 
                    fecha_crea, 
                    fecha_mod, user_crea, user_mod, 
                    activo, estado_tramite_id)
                    VALUES ($idTramiteGestionado, 3,
                    '$fecha_hora'::timestamp, 
                    '$fecha_hora'::timestamp, $usuario_id, $usuario_id, 
                    true, $estadoTramite);";
            $query = $db->prepare($sql);
            $query->execute();

            $sql = "SELECT id FROM tramites_gestionados_docs
                    WHERE tramite_gestionado_id = $idTramiteGestionado;";
            $query = $db->prepare($sql);
            $query->execute();
            // Fetch the results into an associative array
            $results = $query->fetchAll(PDO::FETCH_ASSOC);

            // Now you can iterate through the results using a foreach loop
            foreach ($results as $row) {
                $sql = "UPDATE public.tramites_gestionados_docs
                    SET estado_docs_tramite_id=$estadoDoc, fecha_mod='$fecha_hora'::timestamp, user_mod=$usuario_id
                    WHERE id = " . $row["id"] . ";";
                $query = $db->prepare($sql);
                $query->execute();
            }

            // Se busca el usuario al cual pertenece el trámite gestionado, de modo a enviarle una solicitud
            $sql = "SELECT usuario_id FROM tramites_gestionados
                    WHERE id = $idTramiteGestionado LIMIT 1;";
            $query = $db->prepare($sql);
            $query->execute();
            // Fetch the results into an associative array
            $usuario_tramite = $query->fetchAll(PDO::FETCH_ASSOC);
            // Se inserta la notificación de la cita al ser aprobada la solicitud
            $sql = "INSERT INTO public.notificaciones(
                usuario_notificado_id, mensaje_completo,
                mensaje_notificacion, leido, 
                fecha_crea, fecha_mod, user_crea, 
                user_mod, activo)
                VALUES (" . $usuario_tramite[0]["usuario_id"] . ", 'Revise su correo para mas detalle', 
                        'Solicitud aprobada', false, 
                        '$fecha_hora'::timestamp, '$fecha_hora'::timestamp, 5, 
                        5, true);"; //El usuario con id 5 es el de SIREPRO
            $query = $db->prepare($sql);
            $query->execute();
            $resultado = "";
        } catch (Exception $e) {
            $db->rollback();
            $resultado = "error";
            error_log($e->getMessage());
        }
        if ($respuesta === $db) {
            $db->commit();
            $resultado = "ok";
            echo 'ok';
        }
        $db = null;
        return $resultado;
    }

    public function get_observacion($idTramiteGestionado)
    {
        $db = parent::Conexion();
        $sql = "SELECT observacion_evaluador, observacion AS observacion_inscripcion FROM tramites_gestionados
            WHERE id = $idTramiteGestionado;";
            
        $query = $db->prepare($sql);
        $query->execute();
        $db = null;
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function get_tipo_tramite($idTramiteGestionado)
    {
        $db = parent::Conexion();
        $sql = "SELECT (SELECT T.nombre FROM tramites T WHERE T.id = TG.tramite_id) AS nombre_tramite
        FROM tramites_gestionados TG
        WHERE id = $idTramiteGestionado;
        ";

        $query = $db->prepare($sql);
        $query->execute();
        $db = null;
        return $query->fetch();
    }

    public function get_tramite_name($tramite_id)
    {
        $conectar = parent::Conexion();
        $sql = "select nombre as tramite_nombre from tramites where id='$tramite_id' and activo = true;";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function aprobar_inscripciones($inscripciones_aprobadas, $fecha_hora, $usuario_id, $area_id, $estado_tramite_id)
    {

        try {
            $db = parent::Conexion();
            $respuesta = $db;
            $db->beginTransaction();
            foreach ($inscripciones_aprobadas as $inscripcion_aprobada) {
                $sql = "UPDATE public.tramites_gestionados
                        SET estado_tramite_id=$estado_tramite_id, fecha_mod='$fecha_hora'::timestamp, 
                        user_mod=$usuario_id
                        WHERE id = $inscripcion_aprobada;";
                $query = $db->prepare($sql);
                $query->execute();

                $sql = "INSERT INTO public.movimientos_tramites(
                            tramite_gestionado_id, area_asignada_id, 
                            usuario_asignado_id, fecha_crea, 
                            fecha_mod, user_crea, user_mod, 
                            activo, estado_tramite_id)
                            VALUES ($inscripcion_aprobada, $area_id, 
                            $usuario_id, '$fecha_hora'::timestamp, 
                            '$fecha_hora'::timestamp, $usuario_id, $usuario_id, true, $estado_tramite_id);";
                $query = $db->prepare($sql);
                $query->execute();

                $sql = "SELECT user_crea, curso_id FROM public.tramites_gestionados
                WHERE id = $inscripcion_aprobada;";
                $stmt = $db->prepare($sql);
                $stmt->execute();
                $data = $stmt->fetch();
                $curso_id = $data['curso_id'];
                $user_crea = $data['user_crea'];

                $sql="INSERT INTO public.cursos_inscriptos(
                    usuario_id, curso_id, 
                    fecha_crea, fecha_mod, 
                    user_crea, user_mod, 
                    activo, estado_curso_id)
                    VALUES ($user_crea, $curso_id, 
                        '$fecha_hora'::timestamp, '$fecha_hora'::timestamp, 
                            $usuario_id, $usuario_id, 
                            true, 2);"; //estado_curso_id = 2: EN CURSO
                $query = $db->prepare($sql);
                $query->execute();    
            }

        } catch (Exception $e) {
            error_log("$$$$$$$$$$$" . $e->getMessage());
            $db->rollBack();

            $men = str_replace('SQLSTATE[P0001]: Raise exception: 7 ERROR:', '', $e->getMessage());
            error_log($men . ' ' . $sql);

            return "error";

        }
        if ($respuesta === $db) {
            $db->commit();
            return 'ok';
        }
        $db = null;
    }

    public function aprobar_inscripcion($tramite_gestionado_id, $fecha_hora, $usuario_id, $area_id, $estado_tramite_id)
    {

        try {
            $db = parent::Conexion();
            $respuesta = $db;
            $db->beginTransaction();
            
            $sql = "UPDATE public.tramites_gestionados
                    SET estado_tramite_id=$estado_tramite_id, fecha_mod='$fecha_hora'::timestamp, 
                    user_mod=$usuario_id
                    WHERE id = $tramite_gestionado_id;";
            $query = $db->prepare($sql);
            $query->execute();

            $sql = "INSERT INTO public.movimientos_tramites(
                        tramite_gestionado_id, area_asignada_id, 
                        usuario_asignado_id, fecha_crea, 
                        fecha_mod, user_crea, user_mod, 
                        activo, estado_tramite_id)
                        VALUES ($tramite_gestionado_id, $area_id, 
                        $usuario_id, '$fecha_hora'::timestamp, 
                        '$fecha_hora'::timestamp, $usuario_id, $usuario_id, true, $estado_tramite_id);";
            $query = $db->prepare($sql);
            $query->execute();

            $sql = "SELECT user_crea, curso_id FROM public.tramites_gestionados
            WHERE id = $tramite_gestionado_id;";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetch();
            $curso_id = $data['curso_id'];
            $user_crea = $data['user_crea'];

            $sql="INSERT INTO public.cursos_inscriptos(
                usuario_id, curso_id, 
                fecha_crea, fecha_mod, 
                user_crea, user_mod, 
                activo, estado_curso_id)
                VALUES ($user_crea, $curso_id, 
                    '$fecha_hora'::timestamp, '$fecha_hora'::timestamp, 
                        $usuario_id, $usuario_id, 
                        true, 2);"; //estado_curso_id = 2: EN CURSO
            $query = $db->prepare($sql);
            $query->execute();    

        } catch (Exception $e) {
            error_log("$$$$$$$$$$$" . $e->getMessage());
            $db->rollBack();

            $men = str_replace('SQLSTATE[P0001]: Raise exception: 7 ERROR:', '', $e->getMessage());
            error_log($men . ' ' . $sql);

            return "error";

        }
        if ($respuesta === $db) {
            $db->commit();
            return 'ok';
        }
        $db = null;
    }

    public function rechazar_inscripciones($inscripciones_rechazadas, $fecha_hora, $usuario_id, $area_id, $estado_tramite_id)
    {

        try {
            $db = parent::Conexion();
            $respuesta = $db;
            $db->beginTransaction();
            foreach ($inscripciones_rechazadas as $inscripcion_rechazada) {
                $sql = "UPDATE public.tramites_gestionados
                        SET estado_tramite_id=$estado_tramite_id, fecha_mod='$fecha_hora'::timestamp, 
                        user_mod=$usuario_id
                        WHERE id = $inscripcion_rechazada;";
                $query = $db->prepare($sql);
                $query->execute();

                $sql = "INSERT INTO public.movimientos_tramites(
                            tramite_gestionado_id, area_asignada_id, 
                            usuario_asignado_id, fecha_crea, 
                            fecha_mod, user_crea, user_mod, 
                            activo, estado_tramite_id)
                            VALUES ($inscripcion_rechazada, $area_id, 
                            $usuario_id, '$fecha_hora'::timestamp, 
                            '$fecha_hora'::timestamp, $usuario_id, $usuario_id, true, $estado_tramite_id);";
                $query = $db->prepare($sql);
                $query->execute();
            }

        } catch (Exception $e) {
            error_log("$$$$$$$$$$$" . $e->getMessage());
            $db->rollBack();

            $men = str_replace('SQLSTATE[P0001]: Raise exception: 7 ERROR:', '', $e->getMessage());
            error_log($men . ' ' . $sql);

            return "error";

        }
        if ($respuesta === $db) {
            $db->commit();
            return 'ok';
        }
        $db = null;
    }

    public function rechazar_inscripcion($tramite_gestionado_id, $fecha_hora, $usuario_id, $area_id, $estado_tramite_id)
    {

        try {
            $db = parent::Conexion();
            $respuesta = $db;
            $db->beginTransaction();
            
            $sql = "UPDATE public.tramites_gestionados
                    SET estado_tramite_id=$estado_tramite_id, fecha_mod='$fecha_hora'::timestamp, 
                    user_mod=$usuario_id
                    WHERE id = $tramite_gestionado_id;";
            $query = $db->prepare($sql);
            $query->execute();

            $sql = "INSERT INTO public.movimientos_tramites(
                        tramite_gestionado_id, area_asignada_id, 
                        usuario_asignado_id, fecha_crea, 
                        fecha_mod, user_crea, user_mod, 
                        activo, estado_tramite_id)
                        VALUES ($tramite_gestionado_id, $area_id, 
                        $usuario_id, '$fecha_hora'::timestamp, 
                        '$fecha_hora'::timestamp, $usuario_id, $usuario_id, true, $estado_tramite_id);";
            $query = $db->prepare($sql);
            $query->execute();

        } catch (Exception $e) {
            error_log("$$$$$$$$$$$" . $e->getMessage());
            $db->rollBack();

            $men = str_replace('SQLSTATE[P0001]: Raise exception: 7 ERROR:', '', $e->getMessage());
            error_log($men . ' ' . $sql);

            return "error";

        }
        if ($respuesta === $db) {
            $db->commit();
            return 'ok';
        }
        $db = null;
    }

    public static function get_info_solicitud($id_solicitud){
        $conectar = parent::Conexion();
        $sql = "SELECT nombre, apellido, 
        nombre || ' ' || apellido AS nombre_apellido,
        ci AS documento_identidad, fecha_nacimiento,
        telefono, email, tg.user_crea AS solicitante,
        direccion_domicilio, ciudad_id AS ciudad,
        (SELECT c.nombre FROM cursos c WHERE tg.curso_id = c.id) AS nombre_curso,
        tg.observacion
        FROM usuarios
        JOIN tramites_gestionados tg ON tg.user_crea = usuarios.id
        WHERE tg.id = $id_solicitud;";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;

        return $query->fetch();
    }
}
?>