<?php
class Administracion extends Conectar
{

    /* TODO: Listar reposos segun CI del usuario (médico) */
    public function listar_tramites()
    {
        $conectar = parent::conexion();
        $sql = "SELECT t.id AS id_tramite,
        t.nombre AS nombre_tramite,
        t.tipo_solicitud AS tipo_tramite,
        COUNT(DISTINCT CASE WHEN et.activo = true THEN et.paso END) AS cantidad_pasos
        FROM tramites t
        LEFT JOIN estados_tramites et ON t.id = et.tramite_id
        AND t.activo = true
        GROUP BY t.id, t.nombre, t.tipo_solicitud
        ORDER BY t.tipo_solicitud;";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    /* TODO: Mostrar trámite */
    public function mostrar($tramite_id)
    {
        $conectar = parent::conexion();
        $sql = "SELECT nombre as nombre_tramite,
        tipo_solicitud as tipo_tramite
        FROM tramites
        WHERE id = $tramite_id;";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        $conectar = null;

        return $sql->fetch();
    }

    public function mostrar_estado_tramite($estado_tramite_id)
    {
        $conectar = parent::conexion();
        $sql = "SELECT id AS estado_tramite_id, estado_id, 
        paso as paso_estado, duracion_estimada
        FROM estados_tramites
        WHERE id = $estado_tramite_id;";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function get_estados_x_tramite($tramite_id)
    {
        $conectar = parent::conexion();
        $sql = "SELECT estado_id, 
                (SELECT nombre FROM estados WHERE id = estado_id
                AND tramite_id = $tramite_id) as nombre_estado,
                id as estado_tramite_id,
                paso as paso_estado,
                duracion_estimada
                FROM estados_tramites
                WHERE activo = true
                AND tramite_id =$tramite_id
                ORDER BY paso, id;";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        $conectar = null;

        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert_estados_tramites($datos)
    {
        try {
            $conectar = parent::Conexion();
            $respuesta = $conectar;
            $conectar->beginTransaction();
            $sql = "INSERT INTO public.estados_tramites(
                estado_id, tramite_id, 
                fecha_crea, 
                fecha_mod, user_crea, 
                user_mod, activo, 
                paso, duracion_estimada)
                VALUES (" . $datos['estado_id'] . ", " . $datos['tramite_id'] . ",
                        '" . $datos['fecha_crea'] . "'::timestamp, 
                        '" . $datos['fecha_crea'] . "'::timestamp, " . $datos['usuario_id'] . ", 
                        " . $datos['usuario_id'] . ", " . $datos['activo'] . ", 
                        " . $datos['paso_estado'] . ", " . $datos['duracion_estimada'] . ");";

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

    public function get_estados($tramite_id)
    {
        $conectar = parent::conexion();
        $sql = "SELECT nombre as estado,
        id as estado_id
        FROM estados
		WHERE id NOT IN(SELECT estado_id
					   FROM estados_tramites
					   WHERE tramite_id = $tramite_id
                       AND activo = true);";
        
        $sql = $conectar->prepare($sql);
        $sql->execute();
        $conectar = null;

        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_estado_seleccionado($estado_tramite_id)
    {
        $conectar = parent::conexion();
        $sql = "SELECT nombre as nombre_estado,
        id as estado_id
        FROM estados
		WHERE id =(SELECT estado_id
					   FROM estados_tramites
					   WHERE id = $estado_tramite_id);";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        $conectar = null;

        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update_estados_tramites($datos)
    {
        try {
            $conectar = parent::Conexion();
            $respuesta = $conectar;
            $conectar->beginTransaction();
            $sql = "UPDATE public.estados_tramites
            SET estado_id=" . $datos['estado_id'] . ", fecha_mod='" . $datos['fecha_crea'] . "'::timestamp, 
            user_mod=" . $datos['usuario_id'] . ", paso=" . $datos['paso_estado'] . ", 
            duracion_estimada=" . $datos['duracion_estimada'] . "
            WHERE id =" . $datos['estado_tramite_id'] . ";";

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

    public function delete_estado_tramite($estado_tramite_id, $usuario_id, $fecha_hora)
    {
        try {
            $conectar = parent::Conexion();
            $respuesta = $conectar;
            $conectar->beginTransaction();
            $sql = "UPDATE public.estados_tramites
            SET fecha_mod='$fecha_hora'::timestamp, user_mod=$usuario_id, 
            activo=false
            WHERE id = $estado_tramite_id;";
            $sql = $conectar->prepare($sql);
            $sql->execute();
        } catch (Exception $e) {
            error_log("exception");
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

    public function igualar_estados_tipo_tramite($tramite_id, $tipo_solicitud, $usuario_id, $fecha_hora)
    {
        try {
            $conectar = parent::Conexion();
            $respuesta = $conectar;
            $conectar->beginTransaction();
            //Se eliminan todos los estados del mismo tipo_solicitud, excepto los de aquel que serán tomados para igualar
            $sql = "DELETE FROM public.estados_tramites
            WHERE tramite_id IN (SELECT id FROM tramites WHERE tipo_solicitud = '$tipo_solicitud' AND id <> $tramite_id AND activo = true);";
            $query = $conectar->prepare($sql);
            $query->execute();
            //se crean todos los estados necesarios para igualar al que se toma como parámetro
            $sql= "SELECT paso, duracion_estimada, estado_id FROM estados_tramites WHERE tramite_id = $tramite_id AND activo = true;";
            $stmt = $conectar->prepare($sql);
            $stmt->execute();
            $estados_tramites = $stmt->fetchAll();

            $sql= "SELECT id FROM tramites WHERE tipo_solicitud = '$tipo_solicitud' AND id <> $tramite_id AND activo = true;";
            $query = $conectar->prepare($sql);
            $query->execute();
            $tramites = $query->fetchAll();
            foreach($tramites as $tramite){
                foreach($estados_tramites as $estado_tramite){
                    $sql = "INSERT INTO public.estados_tramites(
                    estado_id, tramite_id, 
                    fecha_crea, 
                    fecha_mod, user_crea, 
                    user_mod, activo, 
                    paso, duracion_estimada)
                    VALUES (" . $estado_tramite['estado_id'] . ", " . $tramite['id'] . ",
                            '" . $fecha_hora . "'::timestamp, 
                            '" . $fecha_hora . "'::timestamp, " . $usuario_id . ", 
                            " . $usuario_id . ", true, 
                            " . $estado_tramite['paso'] . ", " . $estado_tramite['duracion_estimada'] . ");";
                    $query = $conectar->prepare($sql);
                    $query->execute();
                }
            }
            
        } catch (Exception $e) {
            error_log("exception");
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