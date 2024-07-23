<?php
class Consulta extends Conectar
{
    /* TODO: Listar trámites abiertos (sin finalizar) para los directivos. */
    public static function get_tramites()
    {
        $conectar = parent::Conexion();
        
        $sql = "SELECT DISTINCT ON (tramite_gestionado_id) 
            tramites_gestionados.id AS tramite_gestionado_id,
            usuarios.nombre || ' ' || usuarios.apellido AS usuario_solicitante,
            (select nombre || ' ' || apellido from usuarios where id = movimientos_tramites.usuario_asignado_id) AS usuario_asignado,
            tramites_gestionados.fecha_crea as fecha_solicitud,
            estados_tramites.nombre AS estado_actual,
            tramites_gestionados.fecha_mod AS ultimo_movimiento,
            tramites.nombre as tramite_nombre,
            areas.nombre as area_asignada,
            ROUND(EXTRACT(EPOCH FROM (NOW() AT TIME ZONE 'America/Asuncion' - tramites_gestionados.fecha_crea)) / 3600) AS horas_transcurridas
            FROM movimientos_tramites
            JOIN tramites_gestionados on tramites_gestionados.id = movimientos_tramites.tramite_gestionado_id
            JOIN tramites on tramites.id = tramites_gestionados.tramite_id
            JOIN estados_tramites on estados_tramites.id = COALESCE(tramites_gestionados.estado_tramite_id,movimientos_tramites.estado_tramite_id)
            JOIN usuarios ON usuarios.id = tramites_gestionados.usuario_id
            JOIN areas on areas.id = movimientos_tramites.area_asignada_id
            WHERE movimientos_tramites.activo = true
            ORDER BY tramite_gestionado_id, movimientos_tramites.fecha_mod DESC;";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_movimientos_x_tramite($tramite_gestionado_id){
        $conectar = parent::Conexion();
        $sql = "SELECT MT.id AS movimiento_id,
        MT.fecha_crea AS fecha_solicitud,
        TO_CHAR(MT.fecha_crea, 'DD Mon YYYY HH24:MI') AS fecha_hora_mov,
        (SELECT nombre from areas where areas.id = MT.area_asignada_id) AS area_asignada,
        (select nombre || ' ' || apellido from usuarios where usuarios.id = MT.usuario_asignado_id) AS usuario_asignado,
        (SELECT nombre FROM estados_tramites WHERE estados_tramites.id = MT.estado_tramite_id) AS estado_mov
        FROM movimientos_tramites AS MT
        WHERE tramite_gestionado_id = $tramite_gestionado_id;";

        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_info_tramite($tramite_gestionado_id){
        $conectar = parent::Conexion();
        $sql = "SELECT DISTINCT ON (TG.id)
        TO_CHAR(TG.fecha_crea, 'DD Mon YYYY HH24:MI') AS fecha_hora_crea,
        TO_CHAR(TG.fecha_mod, 'DD Mon YYYY HH24:MI') AS fecha_ultimo_mov,
        (SELECT nombre from areas where areas.id = MT.area_asignada_id) AS area_asignada,
        (select nombre from tramites where id = TG.tramite_id) AS nombre_tramite,
        (select nombre || ' ' || apellido from usuarios where usuarios.id = TG.usuario_id) AS usuario_solicitante,
        (select nombre || ' ' || apellido from usuarios where usuarios.id = MT.usuario_asignado_id) AS usuario_asignado,
        (SELECT nombre FROM estados_tramites WHERE estados_tramites.id = TG.estado_tramite_id) AS estado_actual
        FROM tramites_gestionados AS TG
        JOIN movimientos_tramites AS MT ON MT.tramite_gestionado_id = TG.id
        WHERE tramite_gestionado_id = $tramite_gestionado_id;";

        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>