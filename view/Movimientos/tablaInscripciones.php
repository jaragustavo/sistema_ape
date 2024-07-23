<?php

?>
<tbody>
    <thead>
        <tr>
            <th style="width: 20%;">Inscripción a</th>
            <th style="width: 20%;">Sección</th>
            <th style="width: 14%;">Fecha de solicitud</th>
            <th style="width: 15%;">Solicitante</th>
            <th style="width: 10%;">Estado actual</th>
        </tr>
    </thead>
    <?php
    
    foreach ($datos as $row) {
        if ($row["tipo_solicitud"] == "CERT") {
            $row["tipo_solicitud"] = "Certificación";
        }
        elseif ($row["tipo_solicitud"] == "CURSO"){
            $row["tipo_solicitud"] = "Curso";
        }
        ?>
        <tr class="table-warning">
            <td>
                <?php echo $row["tipo_solicitud"] ?>
                <div class="font-11 color-blue-grey-lighter uppercase">
                    <?php echo $row["tramite_nombre"] ?>
                </div>
            </td>
            <td>
                <?php echo $row["seccion_curso"] ?>                
            </td>
            <td width="150">
                <?php echo date("d/m/Y", strtotime($row["fecha_solicitud"])) ?>
                <div class="font-11 color-blue-grey-lighter uppercase">
                    <?php
                    $tiempo_transcurrido = "";
                    date_default_timezone_set('America/Asuncion');
                    if ($row["horas_transcurridas"] > 24) {
                        $dias = floor($row["horas_transcurridas"] / 24);
                        $horas = $row["horas_transcurridas"] - ($dias*24);
                        $tiempo_transcurrido = $dias . " d " . $horas . " hs transcurridos";
                    } else {
                        $tiempo_transcurrido = $row["horas_transcurridas"] . " hs transcurridas";
                    }
                    echo $tiempo_transcurrido ?>
                </div>
            </td>

            <td nowrap="nowrap">
                <?php echo $row["usuario_solicitante"] ?>
            </td>
            <td>
                <div class="color-blue-grey-lighter uppercase">
                    <?php echo $row["estado_actual"] ?>
                </div>
            </td>
            
        </tr>
        <?php
    }
    ?>
</tbody>