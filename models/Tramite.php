<?php
class Tramite extends Conectar
{
    /* Listar trámites según su tipo*/
    public function get_tramites_administrativos()
    {
        $conectar = parent::Conexion();
        $sql = "select id as tramite_id, nombre as tramite, 
        url from tramites where tipo_tramite_id = 1 and activo =true
        AND tipo_solicitud = 'ADMIN';";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_tramites_sede_social()
    {
        $conectar = parent::Conexion();
        $sql = "SELECT id as tramite_id, nombre as tramite, 
        url from tramites where tipo_tramite_id = 1 and activo =true
        AND tipo_solicitud = 'SOCIAL';";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_locales()
    {
        $conectar = parent::Conexion();
        $sql = "SELECT id as local_id,
        nombre as nombre_local
        FROM sede_social_locales;";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_tramites_solidaridad()
    {
        $conectar = parent::Conexion();
        $sql = "select id as tramite_id, nombre as tramite, 
        url from tramites where tipo_tramite_id = 1 and activo =true
        AND tipo_solicitud = 'ADMIN'
        AND nombre ILIKE '%subsidio%';";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_tramites_ayuda()
    {
        $conectar = parent::Conexion();
        $sql = "select id as tramite_id, nombre as tramite, 
        url from tramites where tipo_tramite_id = 1 and activo =true
        AND tipo_solicitud = 'AYUDA';";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }


    public function get_cursos()
    {
        $conectar = parent::Conexion();
        $sql = "select id as tramite_id, nombre as tramite, 
        url from tramites where tipo_tramite_id = 1 and activo =true
        AND tipo_solicitud = 'CURSO';";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /*  Listar estados de trámites */
    public function get_estados_tramites($tramite_id)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT et.estado_id as estado_tramite_id,
        (SELECT nombre FROM estados WHERE estados.id = et.estado_id) estado_tramite
        FROM estados_tramites et
        WHERE activo = true
        AND et.tramite_id = $tramite_id;";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_titulos()
    {
        $conectar = parent::Conexion();
        $sql = "select id as titulo_id, nombre_titulo from titulos where activo = true;";
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
                tipos_documentos.id AS tipo_documento_id, tipos_documentos.nombre_corto
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

    static public function get_docsadjuntos_x_tramite($tramite)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT id as id_adjunto, documento,
            TO_CHAR(fecha_mod, 'DD Mon YYYY') AS fecha_formato_doc,
            TO_CHAR(fecha_mod, 'HH24:MI') AS hora_formato_doc
        from tramites_gestionados_docs 
        where tramite_gestionado_id = $tramite
        AND activo = true;";

        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_tramite_code($tramite_id)
    {
        $conectar = parent::Conexion();
        $sql = "select url from tramites where id='$tramite_id' and activo = true;";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return $query->fetchAll(PDO::FETCH_ASSOC);
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

    /* TODO: Listar estados civiles */
    public function get_estados_civiles()
    {
        $conectar = parent::Conexion();
        $sql = "select id as estado_civil_id, nombre as estado_civil from estados_civiles where activo = true;";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* TODO: Listar paises */
    public function get_paises()
    {
        $conectar = parent::Conexion();
        $sql = "select id as pais_id, nombre as pais from paises where activo = true;";
        $query = $conectar->prepare($sql);
        $query->execute();
        $db = null;
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* TODO: Listar departamentos, provincias o estados según el país seleccionado */
    public function get_departamentos($pais)
    {
        $condicionPais = "";
        if ($pais != "Paraguay") {
            $condicionPais = "pais_id =$pais";
        } else {
            $condicionPais = "paises.nombre = '$pais'";
        }
        $conectar = parent::Conexion();
        $sql = "select departamentos.id as departamento_id, departamentos.nombre as departamento 
            from departamentos 
            join paises on paises.id = departamentos.pais_id
            where $condicionPais
            and departamentos.activo = true;";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* TODO: Listar ciudades según departamento seleccionado */
    public function get_ciudades($departamento)
    {
        $conectar = parent::Conexion();
        if ($departamento == "") {
            $sql = "select id as ciudad_id, nombre as ciudad from ciudades;";
        } else {
            $sql = "select id as ciudad_id, nombre as ciudad from ciudades where departamento_id = $departamento;";
        }
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_filiales()
    {
        $conectar = parent::Conexion();

        $sql = "select id as filial_id, nombre_filial from filiales;";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_bancos()
    {
        $conectar = parent::Conexion();

        $sql = "select id as banco_id, nombre_banco from bancos;";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_tipos_cuentas()
    {
        $conectar = parent::Conexion();

        $sql = "select id as tipo_cuenta_id, nombre as nombre_tipo_cuenta from tipos_cuentas_bancarias;";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_barrios($ciudad)
    {
        $conectar = parent::Conexion();
        $sql = "select id as barrio_id, nombre as barrio from barrios where ciudad_id =$ciudad;";
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
        $conectar = null;
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

        $conectar = null;
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
                    activo, forma_solicitud,observacion)
                VALUES (
                    " . $datos['usuario_id'] . "," . $datos['estado_tramite_id'] . ",
                    " . $datos['tramite_id'] . ",'" . $datos['fecha_crea'] . "'::timestamp,
                    '" . $datos['fecha_crea'] . "'::timestamp,'" . $datos['usuario_id'] . "','" . $datos['usuario_id'] . "',
                    " . $datos['activo'] . ",'" . $datos['forma_solicitud'] . "','" . $datos['observacion'] . "');";

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
                fecha_crea, fecha_mod, 
                user_crea, user_mod, estado_tramite_id)
                VALUES ($tramite_gestionado_id, 1, 
                '" . $datos['fecha_crea'] . "'::timestamp,'" . $datos['fecha_crea'] . "'::timestamp,"
                . $datos['usuario_id'] . "," . $datos['usuario_id'] . "," . $datos['estado_tramite_id'] . ");";
            $db->exec($sql);

            // Se ingresa el primer movimiento del trámite gestionado si es que se envió la solicitud
            if ($datos["estado_tramite_id"] == 1) {

                $sql = "INSERT INTO public.notificaciones(
                    usuario_notificado_id, mensaje_completo,
                    mensaje_notificacion, leido, 
                    fecha_crea, fecha_mod, user_crea, 
                    user_mod, activo)
                    VALUES (" . $datos['usuario_id'] . ", 'Revise su correo para mas detalle', 
                            'La solicitud ha sido enviada con éxito.', false, 
                            '" . $datos['fecha_crea'] . "'::timestamp, 
                            '" . $datos['fecha_crea'] . "'::timestamp, 5, 
                            5, true);"; //El usuario con id 5 es el de SIREPRO
                $query = $db->prepare($sql);
                $query->execute();
            }

            $item = 0;

            $tiposDocumentos = json_decode($datos['tiposDocumentos']);

            if (is_array($tiposDocumentos)) {

                $tramite_id = $datos['tramite_id'];
                $item = 0;
                $nombre_archivo = array();
                while ($item < count($tiposDocumentos)) {
                    $idTypeFile = $tiposDocumentos[$item];
                    $cedula = $datos['cedula_user'];
                    $nombre_archivo = Tramite::buscarNombreArchivo($idTypeFile, $tramite_id, $cedula, $db);

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


            /******************************** 
             * SI EL TIPO DE TRÁMITE ES UNA SOLICITUD, SE INSERTA EN ESTAS TABLAS
             **********************************/
            if ($datos["tipo_solicitud"] == "solidaridad") {
                //Se inserta el formulario de datos del solicitante
                /*  $sql = "INSERT INTO public.formularios_datos_personales(
                  nombre, apellido, 
                  cedula_identidad, ciudad_nacimiento_id, 
                  tramite_gestionado_id, fecha_crea, 
                  fecha_mod, user_crea, 
                  user_mod, activo, 
                  barrio_id, telefono, celular)
                  VALUES ('" . $datos['nombre'] . "', '" . $datos['apellido'] . "', 
                          '" . $datos['documento_identidad'] . "', " . $datos['ciudad_solicitante'] . ", 
                          $tramite_gestionado_id, '" . $datos['fecha_crea'] . "'::timestamp, 
                          '" . $datos['fecha_crea'] . "'::timestamp, " . $datos['usuario_id'] . ", 
                          " . $datos['usuario_id'] . ", " . $datos['activo'] . ", 
                          " . $datos['barrio_solicitante'] . ", '" . $datos['telefono'] . "', '" . $datos['celular'] . "');";
                  $query = $db->prepare($sql);
                  $query->execute();
               */
                //Se inserta los datos para el desembolso según lo ingresado por el usuario
                if ($datos["forma_cobro"] == 1) {
                    $sql = "INSERT INTO public.datos_desembolsos(
                        medio_desembolso, banco_id, 
                        tipo_cuenta_id, numero_cuenta, 
                        denominacion_cuenta, documento_identidad, 
                        filial_id, tramite_gestionado_id,
                        telefono, fecha_crea, 
                        fecha_mod, user_crea, 
                        user_mod, activo)
                        VALUES (" . $datos["forma_cobro"] . ", " . $datos["banco"] . ", 
                                " . $datos["tipo_cuenta"] . ", '" . $datos["numero_cuenta"] . "', 
                                '" . $datos["denominacion_cuenta"] . "', '" . $datos["documento_identidad"] . "', 
                                null, $tramite_gestionado_id,
                                '" . $datos["telefono"] . "', '" . $datos["fecha_crea"] . "'::timestamp, 
                                '" . $datos["fecha_crea"] . "'::timestamp, " . $datos['usuario_id'] . ", 
                                " . $datos['usuario_id'] . ", " . $datos['activo'] . ");";
                } elseif ($datos["forma_cobro"] == 2) {

                    $sql = "INSERT INTO public.datos_desembolsos(
                        medio_desembolso, filial_id,
                        banco_id, telefono,
                        tipo_cuenta_id, numero_cuenta, 
                        denominacion_cuenta, documento_identidad, 
                        fecha_crea, tramite_gestionado_id,
                        fecha_mod, user_crea, 
                        user_mod, activo)
                        VALUES (" . $datos["forma_cobro"] . ", " . $datos["filial"] . ", 
                                null, null,
                                null, null,
                                null, null,
                                '" . $datos["fecha_crea"] . "'::timestamp, $tramite_gestionado_id,
                                '" . $datos["fecha_crea"] . "'::timestamp, " . $datos['usuario_id'] . ", 
                                " . $datos['usuario_id'] . ", " . $datos['activo'] . ");";
                }
                $query = $db->prepare($sql);
                $query->execute();
            }

            // Insertar datos específicos del trámite (en formato JSONB)

            $sqlDatosEspecificos = "INSERT INTO tramites_gestionados_datos (
                    tramite_gestionado_id, datos, fecha_crea, fecha_mod, user_crea, user_mod
                )
                VALUES (
                    :tramite_gestionado_id, :datos, :fecha_crea, :fecha_mod, :user_crea, :user_mod
                )";

            $stmtDatosEspecificos = $db->prepare($sqlDatosEspecificos);
            $stmtDatosEspecificos->bindParam(':tramite_gestionado_id', $tramite_gestionado_id, PDO::PARAM_INT);
            $stmtDatosEspecificos->bindParam(':datos', $datos['datos_especificos_json'], PDO::PARAM_STR);
            $stmtDatosEspecificos->bindParam(':fecha_crea', $datos['fecha_crea'], PDO::PARAM_STR);
            $stmtDatosEspecificos->bindParam(':fecha_mod', $datos['fecha_crea'], PDO::PARAM_STR);
            $stmtDatosEspecificos->bindParam(':user_crea', $datos['usuario_id'], PDO::PARAM_INT);
            $stmtDatosEspecificos->bindParam(':user_mod', $datos['usuario_id'], PDO::PARAM_INT);
            $stmtDatosEspecificos->execute();

            $db->commit();

            echo "ok";


        } catch (PDOException $e) {
            $db->rollBack();
            return "error: " . $e->getMessage();
        }

    }

    /*=============================================
    LISTAR TRÁMITES 
    =============================================*/
    public function get_tramites_gestionados_x_usuario($usuario_id)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT 
                    tg.id AS tramite_gestionado_id,
                    (SELECT nombre FROM tramites WHERE tramites.id = tg.tramite_id) AS nombre_tramite,
                    u.nombre || ' ' || u.apellido AS usuario_solicitante,
                    (SELECT nombre || ' ' || apellido FROM usuarios WHERE id = mt.usuario_asignado_id LIMIT 1) AS usuario_asignado,
                    tg.fecha_crea AS fecha_solicitud,
                    e.nombre AS estado_actual,
                    tg.fecha_mod AS ultimo_movimiento,
                    tg.tramite_id AS tramite_id,
                    ar.nombre AS area_asignada,
                    tr.nombre AS nombre_tramite,
                    e.permisos, 
                    (SELECT COUNT(DISTINCT et.paso) 
                    FROM estados_tramites et 
                    WHERE et.activo = true AND et.tramite_id = tg.tramite_id) AS cantidad_pasos,
                    (SELECT paso FROM estados_tramites et 
                    WHERE et.tramite_id = tg.tramite_id AND et.estado_id = tg.estado_tramite_id AND et.activo = true LIMIT 1) AS paso
                FROM 
                    tramites_gestionados tg
                LEFT JOIN 
                    (SELECT mt1.*
                    FROM movimientos_tramites mt1
                    WHERE mt1.area_asignada_id = 1 AND mt1.activo = true
                    AND mt1.fecha_mod = (
                        SELECT MAX(mt2.fecha_mod)
                        FROM movimientos_tramites mt2
                        WHERE mt2.tramite_gestionado_id = mt1.tramite_gestionado_id
                        AND mt2.activo = true
                    )
                    ) mt ON tg.id = mt.tramite_gestionado_id
                JOIN 
                    estados e ON e.id = COALESCE(tg.estado_tramite_id, mt.estado_tramite_id)
                JOIN 
                    usuarios u ON u.id = tg.usuario_id
                LEFT JOIN 
                    areas ar ON ar.id = mt.area_asignada_id
                LEFT JOIN 
                    tramites tr ON tr.id = tg.tramite_id
                WHERE 
                    tg.activo = true 
                    AND tg.usuario_id = $usuario_id
                    AND tr.tipo_solicitud = 'ADMIN'
                GROUP BY 
                    tg.id, tg.tramite_id, u.nombre, u.apellido, mt.usuario_asignado_id, tg.fecha_crea,
                    e.nombre, tg.fecha_mod, ar.nombre, tr.nombre, e.permisos;";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_solicitudes_ayuda_x_usuario($usuario_id)
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
        t.nombre AS nombre_tramite,
        sa.id AS ayuda_id,
        e.permisos, 
        (SELECT COUNT(DISTINCT et.paso) 
         FROM estados_tramites et 
         WHERE et.activo = true AND et.tramite_id = tg.tramite_id) AS cantidad_pasos,
        (SELECT paso FROM estados_tramites et 
         WHERE et.tramite_id = tg.tramite_id AND et.estado_id = tg.estado_tramite_id AND et.activo =true) AS paso
        FROM 
        solicitudes_ayuda sa 
        JOIN tramites_gestionados tg ON tg.id = sa.tramite_gestionado_id
        LEFT JOIN movimientos_tramites mt ON tg.id = mt.tramite_gestionado_id AND mt.area_asignada_id = 1 AND mt.activo = true
        JOIN estados e ON e.id = COALESCE(tg.estado_tramite_id,mt.estado_tramite_id)
        JOIN usuarios u ON u.id = tg.usuario_id
        LEFT JOIN areas a ON a.id = mt.area_asignada_id
        LEFT JOIN tramites AS t ON t.id = tg.tramite_id
        WHERE tg.activo = true 
        AND tg.usuario_id = 2
        AND t.tipo_solicitud = 'AYUDA'
        ORDER BY tg.id, COALESCE(mt.fecha_crea, tg.fecha_mod) DESC;";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_observacion_ayuda($ayuda_id)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT observacion FROM solicitudes_ayuda WHERE id = $ayuda_id and activo = true;";
        error_log($sql);
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function mostrar($tramite_gestionado_id, $usuario_id)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT  TGD.id as documento_id,
            TGD.documento as documento,
            TGD.tipo_documento_id as tipo_doc_id,
            tramites_gestionados.id as tramite_gestionado_id,
            tramites_gestionados.tramite_id as tramite_id,
            tramites.nombre AS nombre_tramite, 
            tramites_gestionados.fecha_crea as fecha_solicitud,
            estados_tramites.nombre as estado_actual,
            tramites_gestionados.fecha_mod as ultimo_movimiento
            FROM tramites_gestionados_docs as TGD
            JOIN tramites_gestionados ON tramites_gestionados.id = TGD.tramite_gestionado_id
            JOIN estados_tramites on estados_tramites.id = tramites_gestionados.estado_tramite_id
            JOIN tramites on tramites.id = tramites_gestionados.tramite_id
            WHERE tramites_gestionados.usuario_id = $usuario_id
            AND tramites_gestionados.id = $tramite_gestionado_id
            AND tramites_gestionados.activo = true;";
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
           -- estados_tramites.nombre as estado_actual,
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

        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function get_observacion_tramite($id_tramite_gestionado)
    {
        $conectar = parent::Conexion();
        $sql = "select observacion, observacion_evaluador from tramites_gestionados where id='$id_tramite_gestionado' and activo = true;";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;

        return $query->fetch(PDO::FETCH_ASSOC);
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
                fecha_mod='" . $datos['fecha_crea'] . "'::timestamp, user_mod=" . $datos['usuario_id'] . "
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

            // Se ingresa el primer movimiento del trámite gestionado si es que se envió la solicitud
            if ($datos["estado_tramite_id"] == 1) {

                $sql = "INSERT INTO public.notificaciones(
                    usuario_notificado_id, mensaje_completo,
                    mensaje_notificacion, leido, 
                    fecha_crea, fecha_mod, user_crea, 
                    user_mod, activo)
                    VALUES (" . $datos['usuario_id'] . ", 'Revise su correo para mas detalle', 
                            'La solicitud ha sido enviada con éxito.', false, 
                            '" . $datos['fecha_crea'] . "'::timestamp, 
                            '" . $datos['fecha_crea'] . "'::timestamp, 5, 
                            5, true);"; //El usuario con id 5 es el de SIREPRO
                $query = $db->prepare($sql);
                $query->execute();
            }

            $item = 0;
            $tiposDocumentos = json_decode($datos['tiposDocumentos']);

            if (is_array($tiposDocumentos)) {

                $item = 0;
                $nombre_archivo = array();
                while ($item < count($tiposDocumentos)) {
                    $tramite_id = $datos['tramite_id'];
                    $idTypeFile = $tiposDocumentos[$item];
                    $cedula = $datos['cedula_user'];
                    $nombre_archivo = Tramite::buscarNombreArchivo($idTypeFile, $tramite_id, $cedula, $db);

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

            if ($datos["tipo_solicitud"] == "solidaridad") {

                //Se inserta los datos para el desembolso según lo ingresado por el usuario
                if ($datos["forma_cobro"] == 1) {
                    $sql = "UPDATE public.datos_desembolsos
                    SET medio_desembolso=" . $datos['forma_cobro'] . ", banco_id=" . $datos['banco'] . ", 
                    tipo_cuenta_id=" . $datos['tipo_cuenta'] . ", numero_cuenta='" . $datos['numero_cuenta'] . "', 
                    denominacion_cuenta='" . $datos['denominacion_cuenta'] . "', documento_identidad='" . $datos['doc_identidad_cuenta'] . "', 
                    telefono='" . $datos['telefono_cuenta'] . "', filial_id=null, 
                    fecha_mod='" . $datos['fecha_crea'] . "'::timestamp, user_mod=" . $datos['usuario_id'] . "
                    WHERE tramite_gestionado_id=" . $datos['tramite_gestionado_id'] . "
                    AND activo = true;
                    ";
                } else {
                    $sql = "UPDATE public.datos_desembolsos
                    SET medio_desembolso=" . $datos['forma_cobro'] . ", banco_id=null, 
                    tipo_cuenta_id=null, numero_cuenta=null, 
                    denominacion_cuenta=null, documento_identidad=null, 
                    telefono=null, filial_id=" . $datos['filial'] . ", 
                    fecha_mod='" . $datos['fecha_crea'] . "'::timestamp, user_mod=" . $datos['usuario_id'] . "
                    WHERE tramite_gestionado_id=" . $datos['tramite_gestionado_id'] . "
                    AND activo = true;
                    ";
                }
                $db->exec($sql);

            }

            // Insertar datos específicos del trámite (en formato JSONB)

            $sqlDatosEspecificos = "UPDATE tramites_gestionados_datos 
                set datos = :datos, fecha_mod = :fecha_mod, user_mod = :user_mod
                where tramite_gestionado_id = :tramite_gestionado_id";

            $stmtDatosEspecificos = $db->prepare($sqlDatosEspecificos);
            $stmtDatosEspecificos->bindParam(':tramite_gestionado_id', $datos['tramite_gestionado_id'], PDO::PARAM_INT);
            $stmtDatosEspecificos->bindParam(':datos', $datos['datos_especificos_json'], PDO::PARAM_STR);
            $stmtDatosEspecificos->bindParam(':fecha_mod', $datos['fecha_crea'], PDO::PARAM_STR);
            $stmtDatosEspecificos->bindParam(':user_mod', $datos['usuario_id'], PDO::PARAM_INT);
            $stmtDatosEspecificos->execute();


        } catch (Exception $e) {
            $db->rollBack();

            $men = str_replace('SQLSTATE[P0001]: Raise exception: 7 ERROR:', '', $e->getMessage());
            error_log($men . ' ' . $sql);
            echo $men . ' ' . $sql;


        }
        if ($respuesta === $db) {
            $db->commit();
            echo 'ok';
            $db = null;
            return $db;

        }
    }

    public function delete_tramite_gestionado($id_tramite_gestionado, $usuario_id, $fecha_hora)
    {
        try {
            $db = parent::Conexion();
            $respuesta = $db;
            $db->beginTransaction();

            $sql = "UPDATE public.tramites_gestionados
                SET activo = false, estado_tramite_id = 10, --Estado_id 10= ANULADO--
                fecha_mod = '$fecha_hora'::timestamp
                WHERE id = $id_tramite_gestionado;";

            $db->exec($sql);

            // Se inserta un movimiento para registrar la anulación del trámite por parte del solicitante
            $sql = "INSERT INTO public.movimientos_tramites(
                    tramite_gestionado_id, area_asignada_id, 
                    fecha_crea, fecha_mod, 
                    user_crea, user_mod, estado_tramite_id)
                    VALUES ($id_tramite_gestionado, 8, 
                    '$fecha_hora'::timestamp,'$fecha_hora'::timestamp,
                    $usuario_id,$usuario_id, 10);";

            $db->exec($sql);

            $sql = "SELECT id FROM tramites_gestionados_docs 
                    where tramite_gestionado_id = $id_tramite_gestionado ";
            $query = $db->prepare($sql);
            $query->execute();
            $docs_existentes = $query->fetchAll(PDO::FETCH_ASSOC);

            foreach ($docs_existentes as $doc_existente) {
                $sql = "UPDATE public.tramites_gestionados_docs
                    SET estado_docs_tramite_id=5, fecha_mod='$fecha_hora',  --Estado_doc_id 5= ELIMINADO
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


    /*=============================================
    SOLICITUDES SUBSIDIO
    =============================================*/
    public function get_datos_solicitud_subsidio($id_solicitud)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT nombre, apellido, 
        cedula_identidad AS documento_identidad, FDP.telefono,
        ciudad_nacimiento_id AS ciudad_solicitante, 
        barrio_id AS barrio_solicitante, celular,
		banco_id AS banco, tipo_cuenta_id AS tipo_cuenta,
		numero_cuenta, denominacion_cuenta, 
		documento_identidad AS doc_identidad_cuenta, 
		DD.telefono AS telefono_cuenta, medio_desembolso,
        filial_id AS filial,
        TG.estado_tramite_id
        FROM formularios_datos_personales AS FDP
		JOIN datos_desembolsos AS DD ON DD.tramite_gestionado_id = 
		FDP.tramite_gestionado_id
        LEFT JOIN tramites_gestionados AS TG ON TG.id = FDP.tramite_gestionado_id
        WHERE FDP.tramite_gestionado_id = $id_solicitud
        AND FDP.activo = true;
        ";

        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /*=============================================
    SOLICITUDES RESERVAS DE LOCALES EN SEDES SOCIALES
    =============================================*/

    public function get_reservas_x_usuario($usuario_id)
    {
        $conectar = parent::Conexion();
        $sql = "select tramite.*, locales.nombre as local_nombre
                from (
                    select tramite_gestionado_id, datos, dato.tramite_id tramite_id, estado_tramite_id, user_crea, activo,
                        cantidad_pasos, estado_actual, permisos,
                        jsonb_extract_path_text(datos, 'local')::bigint as local_id,
                        jsonb_extract_path_text(datos, 'hora_desde')::time as hora_desde,
                        jsonb_extract_path_text(datos, 'hora_hasta')::time as hora_hasta,
                        jsonb_extract_path_text(datos, 'fecha_reserva')::date as fecha_reserva,
                        jsonb_extract_path_text(datos, 'cantidad_personas')::bigint as cantidad_personas,
                        dato.fecha_solicitud
                    from (
                        select tramite_gestionado_id, datos, dato.tramite_id, estado_tramite_id, user_crea, activo, cantidad_pasos, fecha_solicitud
                        from (
                            select tramite_gestionado_id, datos, tramite_id, estado_tramite_id, user_crea, activo, fecha_solicitud
                            from (
                                select tramite_gestionado_id, datos, fecha_crea as fecha_solicitud 
                                from tramites_gestionados_datos
                            ) datos
                            inner join (
                                select id, tramite_id, estado_tramite_id, user_crea, activo 
                                from tramites_gestionados
                            ) tramite_gestionado
                            on datos.tramite_gestionado_id = tramite_gestionado.id
                            and tramite_gestionado.user_crea = $usuario_id
                            and tramite_gestionado.activo = true
                        ) dato
                        inner join (
                            SELECT count(DISTINCT paso) cantidad_pasos, tramite_id
                            FROM estados_tramites 
                            group by tramite_id
                        ) tramite_pasos
                        on tramite_pasos.tramite_id = dato.tramite_id
                        and dato.tramite_id = 18
                    ) dato
                    inner join (
                        select id estado_id, nombre estado_actual, permisos 
                        from estados
                    ) estados
                    on dato.estado_tramite_id = estados.estado_id
                ) tramite
                inner join (
                    select estado_id, tramite_id, paso, duracion_estimada
                    from estados_tramites
                ) paso
                on paso.estado_id = tramite.estado_tramite_id
                and paso.tramite_id = tramite.tramite_id
                left join sede_social_locales locales
                on tramite.local_id = locales.id;

        ";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    public function insertar_tramites_reservas($datos)
    {
        try {
            $db = parent::Conexion();
            $respuesta = $db;
            $db->beginTransaction();

            $sql = "INSERT INTO tramites_gestionados (
                    usuario_id, estado_tramite_id, 
                    tramite_id, fecha_crea, 
                    fecha_mod, user_crea, user_mod, 
                    activo, forma_solicitud)
                VALUES (
                    " . $datos['usuario_id'] . "," . $datos['estado_tramite_id'] . ",
                    " . $datos['tramite_id'] . ",'" . $datos['fecha_crea'] . "'::timestamp,
                    '" . $datos['fecha_crea'] . "'::timestamp,'" . $datos['usuario_id'] . "','" . $datos['usuario_id'] . "',
                    " . $datos['activo'] . ",'" . $datos['forma_solicitud'] . "');";

            $db->exec($sql);

            /*******************************
             * OBTENER TRÁMITE GESTIONADO ID
             **********************************/
            $sql = "select currval( 'tramites_gestionados_id_seq' )::BIGINT;";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetch();
            $tramite_gestionado_id = $data['0'];

            // Se ingresa el primer movimiento del trámite gestionado si es que se envió la solicitud
            $sql = "INSERT INTO public.movimientos_tramites(
                tramite_gestionado_id, area_asignada_id, 
                fecha_crea, fecha_mod, 
                user_crea, user_mod)
                VALUES ($tramite_gestionado_id, 1, 
                '" . $datos['fecha_crea'] . "'::timestamp,'" . $datos['fecha_crea'] . "'::timestamp,"
                . $datos['usuario_id'] . "," . $datos['usuario_id'] . ");";

            $db->exec($sql);
            if ($datos["estado_tramite_id"] == 2) {
                $sql = "INSERT INTO public.notificaciones(
                    usuario_notificado_id, mensaje_completo,
                    mensaje_notificacion, leido, 
                    fecha_crea, fecha_mod, user_crea, 
                    user_mod, activo)
                    VALUES (" . $datos['usuario_id'] . ", 'Revise su correo para mas detalle', 
                            'La solicitud ha sido enviada con éxito.', false, 
                            '" . $datos['fecha_crea'] . "'::timestamp, 
                            '" . $datos['fecha_crea'] . "'::timestamp, 5, 
                            5, true);"; //El usuario con id 5 es el de SIREPRO
                $query = $db->prepare($sql);
                $query->execute();
            }

            $sql = "INSERT INTO public.sede_social_reservas(
                hora_desde, hora_hasta, 
                local_id, fecha_reserva, 
                fecha_crea, fecha_mod, 
                user_crea, user_mod, 
                activo, tramite_gestionado_id,
                cantidad_personas)
                VALUES ('" . $datos['hora_desde'] . "', '" . $datos['hora_hasta'] . "', 
                        " . $datos['local'] . ", '" . $datos['fecha_reserva'] . "'::timestamp, 
                        '" . $datos['fecha_crea'] . "'::timestamp, '" . $datos['fecha_crea'] . "'::timestamp, 
                        " . $datos['usuario_id'] . ", " . $datos['usuario_id'] . ", 
                        " . $datos['activo'] . ", $tramite_gestionado_id,
                        " . $datos['cantidad_personas'] . ");";
            $query = $db->prepare($sql);
            $query->execute();

        } catch (Exception $e) {
            $db->rollBack();

            $men = str_replace('SQLSTATE[P0001]: Raise exception: 7 ERROR:', '', $e->getMessage());
            error_log($men . ' ' . $sql);
            echo $men . ' ' . $sql;

            return "error";

        }
        if ($respuesta === $db) {
            $db->commit();
            echo 'ok';
            $db = null;
            return "ok";

        }

    }

    public static function get_tramites_gestionados_datos($id_solicitud)
    {
        $conectar = parent::Conexion();
        $sql = "select b.*, a.observacion, a.tramite_id from tramites_gestionados_datos b, tramites_gestionados a
                where a.id = b.tramite_gestionado_id
                and   a.id =  $id_solicitud;";

        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;

        return $query->fetch();
    }

    public function get_datos_tramites($id_solicitud)
    {
        $conectar = parent::Conexion();
        $sql = "select b.*, a.observacion, a.tramite_id from tramites_gestionados_datos b, tramites_gestionados a
                where a.id = b.tramite_gestionado_id
                and   a.id =  $id_solicitud;";

        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;

        return $query->fetch();

    }

    public static function get_tramite($tramite_id)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT * FROM tramites WHERE id = :tramite_id";
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(':tramite_id', $tramite_id, PDO::PARAM_INT);

        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        $conectar = null;
        return $data;

    }

    public function update_tramites_reservas($datos)
    {
        try {
            $db = parent::Conexion();
            $respuesta = $db;
            $db->beginTransaction();
            /*******************************
             * OBTENER TRÁMITE GESTIONADO ID
             **********************************/
            $sql = "select tramite_gestionado_id FROM sede_social_reservas
            WHERE id = " . $datos['reserva_id'] . ";";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetch();
            $tramite_gestionado_id = $data['0'];

            $sql = "UPDATE public.tramites_gestionados
            SET estado_tramite_id=" . $datos["estado_tramite_id"] . ", fecha_mod='" .
                $datos['fecha_crea'] . "'::timestamp, 
            user_mod= " . $datos['usuario_id'] . "
            WHERE id =$tramite_gestionado_id;";

            $db->exec($sql);



            // Se ingresa el primer movimiento del trámite gestionado si es que se envió la solicitud
            if ($datos["estado_tramite_id"] == 6) {

                $sql = "INSERT INTO public.movimientos_tramites(
                        tramite_gestionado_id, area_asignada_id, 
                        fecha_crea, fecha_mod, 
                        user_crea, user_mod)
                        VALUES ($tramite_gestionado_id, 1, 
                        '" . $datos['fecha_crea'] . "'::timestamp,'" . $datos['fecha_crea'] . "'::timestamp,"
                    . $datos['usuario_id'] . "," . $datos['usuario_id'] . ");";

                $db->exec($sql);

                $sql = "INSERT INTO public.notificaciones(
                    usuario_notificado_id, mensaje_completo,
                    mensaje_notificacion, leido, 
                    fecha_crea, fecha_mod, user_crea, 
                    user_mod, activo)
                    VALUES (" . $datos['usuario_id'] . ", 'Revise su correo para mas detalle', 
                            'La solicitud ha sido enviada con éxito.', false, 
                            '" . $datos['fecha_crea'] . "'::timestamp, 
                            '" . $datos['fecha_crea'] . "'::timestamp, 5, 
                            5, true);"; //El usuario con id 5 es el de SIREPRO
                $query = $db->prepare($sql);
                $query->execute();
            }

            $sql = "UPDATE public.sede_social_reservas
            SET hora_desde='" . $datos['hora_desde'] . "', hora_hasta='" . $datos['hora_hasta'] . "', 
            local_id=" . $datos['local'] . ", fecha_reserva='" . $datos['fecha_reserva'] . "'::timestamp, 
            fecha_mod='" . $datos['fecha_crea'] . "'::timestamp, user_mod=" . $datos['usuario_id'] . ", 
            cantidad_personas=" . $datos['cantidad_personas'] . "
            WHERE id = " . $datos['reserva_id'] . ";";
            $query = $db->prepare($sql);
            $query->execute();

        } catch (Exception $e) {
            $db->rollBack();

            $men = str_replace('SQLSTATE[P0001]: Raise exception: 7 ERROR:', '', $e->getMessage());
            error_log($men . ' ' . $sql);
            echo $men . ' ' . $sql;

            return "error";

        }
        if ($respuesta === $db) {
            $db->commit();
            echo 'ok';
            $db = null;
            return "ok";

        }

    }

    public function delete_reserva($tramite_gestionado_id, $usuario_id, $fecha_hora)
    {
        try {
            $db = parent::Conexion();
            $respuesta = $db;
            $db->beginTransaction();

            $sql = "UPDATE public.tramites_gestionados
            SET activo = false, estado_tramite_id = 10,
            fecha_mod = '$fecha_hora'::timestamp
            WHERE id = $tramite_gestionado_id;";

            $db->exec($sql);

            // Se inserta un movimiento para registrar la anulación del trámite por parte del solicitante
            $sql = "INSERT INTO public.movimientos_tramites(
                    tramite_gestionado_id, area_asignada_id, 
                    fecha_crea, fecha_mod, 
                    user_crea, user_mod, estado_tramite_id)
                    VALUES ($tramite_gestionado_id, 8, 
                    '$fecha_hora'::timestamp,'$fecha_hora'::timestamp,
                    $usuario_id,$usuario_id, 10);";

            $db->exec($sql);

            $sql = "SELECT id FROM tramites_gestionados_docs 
                    where tramite_gestionado_id = $tramite_gestionado_id ";
            $query = $db->prepare($sql);
            $query->execute();
            $docs_existentes = $query->fetchAll(PDO::FETCH_ASSOC);

            foreach ($docs_existentes as $doc_existente) {
                $sql = "UPDATE public.tramites_gestionados_docs
                    SET estado_docs_tramite_id=5, fecha_mod='$fecha_hora', 
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
            echo 'ok';
            $db = null;
            return "ok";

        }
    }

    /*=============================================
    SOLICITUDES AYUDA PARA SOCIOS
    =============================================*/
    public function insertar_tramites_ayuda($datos)
    {
        try {
            $db = parent::Conexion();
            $respuesta = $db;
            $db->beginTransaction();

            $sql = "INSERT INTO tramites_gestionados (
                    usuario_id, estado_tramite_id, 
                    tramite_id, fecha_crea, 
                    fecha_mod, user_crea, user_mod, 
                    activo, forma_solicitud)
                VALUES (
                    " . $datos['usuario_id'] . "," . $datos['estado_tramite_id'] . ",
                    " . $datos['tramite_id'] . ",'" . $datos['fecha_crea'] . "'::timestamp,
                    '" . $datos['fecha_crea'] . "'::timestamp,'" . $datos['usuario_id'] . "','" . $datos['usuario_id'] . "',
                    " . $datos['activo'] . ",'" . $datos['forma_solicitud'] . "');";

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
                fecha_crea, fecha_mod, 
                user_crea, user_mod)
                VALUES ($tramite_gestionado_id, 1, 
                '" . $datos['fecha_crea'] . "'::timestamp,'" . $datos['fecha_crea'] . "'::timestamp,"
                . $datos['usuario_id'] . "," . $datos['usuario_id'] . ");";

            $db->exec($sql);
            // Se ingresa el primer movimiento del trámite gestionado si es que se envió la solicitud
            if ($datos["estado_tramite_id"] == 1) {
                $sql = "INSERT INTO public.notificaciones(
                    usuario_notificado_id, mensaje_completo,
                    mensaje_notificacion, leido, 
                    fecha_crea, fecha_mod, user_crea, 
                    user_mod, activo)
                    VALUES (" . $datos['usuario_id'] . ", 'Revise su correo para mas detalle', 
                            'La solicitud ha sido enviada con éxito.', false, 
                            '" . $datos['fecha_crea'] . "'::timestamp, 
                            '" . $datos['fecha_crea'] . "'::timestamp, 5, 
                            5, true);"; //El usuario con id 5 es el de SIREPRO
                $query = $db->prepare($sql);
                $query->execute();
            }

            $sql = "INSERT INTO public.solicitudes_ayuda(
                observacion, fecha_crea, 
                fecha_mod, user_crea,
                user_mod, activo,
                tramite_gestionado_id)
                VALUES ('" . $datos['observacion'] . "', '" . $datos['fecha_crea'] . "'::timestamp, 
                        '" . $datos['fecha_crea'] . "'::timestamp, " . $datos['usuario_id'] . ", 
                        " . $datos['usuario_id'] . ", " . $datos['activo'] . ",
                        $tramite_gestionado_id);";
            $query = $db->prepare($sql);
            $query->execute();

        } catch (Exception $e) {
            $db->rollBack();

            $men = str_replace('SQLSTATE[P0001]: Raise exception: 7 ERROR:', '', $e->getMessage());
            error_log($men . ' ' . $sql);
            echo $men . ' ' . $sql;

            return "error";

        }
        if ($respuesta === $db) {
            $db->commit();
            echo 'ok';
            $db = null;
            return "ok";

        }

    }

    public function update_tramites_ayuda($datos)
    {
        try {
            $db = parent::Conexion();
            $respuesta = $db;
            $db->beginTransaction();

            /*******************************
             * OBTENER TRÁMITE GESTIONADO ID
             **********************************/
            $sql = "select tramite_gestionado_id FROM solicitudes_ayuda
            WHERE id = " . $datos['solicitud_ayuda_id'] . ";";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetch();
            $tramite_gestionado_id = $data['0'];

            $sql = "UPDATE public.tramites_gestionados
            SET estado_tramite_id=" . $datos["estado_tramite_id"] . ", fecha_mod='" .
                $datos['fecha_crea'] . "'::timestamp, 
            user_mod= " . $datos['usuario_id'] . "
            WHERE id =$tramite_gestionado_id;";

            $db->exec($sql);


            $sql = "INSERT INTO public.movimientos_tramites(
                tramite_gestionado_id, area_asignada_id, 
                fecha_crea, fecha_mod, 
                user_crea, user_mod, estado_tramite_id)
                VALUES ($tramite_gestionado_id, 1, 
                '" . $datos['fecha_crea'] . "'::timestamp,'" . $datos['fecha_crea'] . "'::timestamp,"
                . $datos['usuario_id'] . "," . $datos['usuario_id'] .
                "," . $datos["estado_tramite_id"] . ");";

            $db->exec($sql);
            // Se ingresa el primer movimiento del trámite gestionado si es que se envió la solicitud
            if ($datos["estado_tramite_id"] == 1) {
                $sql = "INSERT INTO public.notificaciones(
                    usuario_notificado_id, mensaje_completo,
                    mensaje_notificacion, leido, 
                    fecha_crea, fecha_mod, user_crea, 
                    user_mod, activo)
                    VALUES (" . $datos['usuario_id'] . ", 'Revise su correo para mas detalle', 
                            'La solicitud ha sido enviada con éxito.', false, 
                            '" . $datos['fecha_crea'] . "'::timestamp, 
                            '" . $datos['fecha_crea'] . "'::timestamp, 5, 
                            5, true);"; //El usuario con id 5 es el de SIREPRO
                $query = $db->prepare($sql);
                $query->execute();
            }

            $sql = "UPDATE public.solicitudes_ayuda
            SET observacion='" . $datos['observacion'] . "', 
            fecha_mod='" . $datos['fecha_crea'] . "'::timestamp, 
            user_mod=" . $datos['usuario_id'] . "
            WHERE id =" . $datos['solicitud_ayuda_id'] . ";";
            $query = $db->prepare($sql);
            $query->execute();

        } catch (Exception $e) {
            $db->rollBack();

            $men = str_replace('SQLSTATE[P0001]: Raise exception: 7 ERROR:', '', $e->getMessage());
            error_log($men . ' ' . $sql);
            echo $men . ' ' . $sql;

            return "error";

        }
        if ($respuesta === $db) {
            $db->commit();
            echo 'ok';
            $db = null;
            return "ok";

        }
    }

    public function delete_tramites_ayuda($ayuda_id, $usuario_id, $fecha_hora, $estado_anulado)
    {
        try {
            $db = parent::Conexion();
            $respuesta = $db;
            $db->beginTransaction();

            $sql = "select tramite_gestionado_id FROM solicitudes_ayuda
            WHERE id = $ayuda_id;";
            error_log($sql);
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetch();
            $tramite_gestionado_id = $data['0'];

            $sql = "UPDATE public.tramites_gestionados
                SET activo = false, estado_tramite_id = $estado_anulado,
                fecha_mod = '$fecha_hora'::timestamp
                WHERE id = $tramite_gestionado_id;";

            $db->exec($sql);

            // Se inserta un movimiento para registrar la anulación del trámite por parte del solicitante
            $sql = "INSERT INTO public.movimientos_tramites(
                    tramite_gestionado_id, area_asignada_id, 
                    fecha_crea, fecha_mod, 
                    user_crea, user_mod, estado_tramite_id)
                    VALUES ($tramite_gestionado_id, 8, 
                    '$fecha_hora'::timestamp,'$fecha_hora'::timestamp,
                    $usuario_id,$usuario_id, $estado_anulado);";

            $db->exec($sql);

            $sql = "SELECT id FROM tramites_gestionados_docs 
                    where tramite_gestionado_id = $tramite_gestionado_id ";
            $query = $db->prepare($sql);
            $query->execute();
            $docs_existentes = $query->fetchAll(PDO::FETCH_ASSOC);

            foreach ($docs_existentes as $doc_existente) {
                $sql = "UPDATE public.tramites_gestionados_docs
                    SET estado_docs_tramite_id=5, fecha_mod='$fecha_hora', 
                    user_mod=$usuario_id, activo = false
                    WHERE id = " . $doc_existente["id"] . ";";

                $db->exec($sql);

            }

            $sql = "UPDATE public.solicitudes_ayuda
            SET 
            fecha_mod='$fecha_hora'::timestamp, user_mod=$usuario_id, 
            activo = false
            WHERE id = $ayuda_id";
            $query = $db->prepare($sql);
            $query->execute();

        } catch (Exception $e) {
            $db->rollBack();

            $men = str_replace('SQLSTATE[P0001]: Raise exception: 7 ERROR:', '', $e->getMessage());
            error_log($men . ' ' . $sql);
            echo $men . ' ' . $sql;

            return "error";

        }
        if ($respuesta === $db) {
            $db->commit();
            echo 'ok';
            $db = null;
            return "ok";

        }
    }
}
?>