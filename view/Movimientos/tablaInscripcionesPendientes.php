<?php

?>
<tbody>
    <thead>
        <tr>
            <th style="width: 5%;"></th>
            <th style="width: 16%;">Inscripción a</th>
            <th style="width: 16%;">Sección</th>
            <th style="width: 14%;">Fecha de solicitud</th>
            <th style="width: 15%;">Solicitante</th>
            <th style="width: 15%;">Estado actual</th>
            <th style="width: 15%;">Acciones</th>
        </tr>
    </thead>
    <?php

    foreach ($datos as $row) {
        $tipo_capacitacion = "";
        $btn="";
        if ($row["tipo_solicitud"] == "CERT") {
            $tipo_capacitacion = "Certificación";
            $btn = "btn-abrir-inscripcion";
        } elseif ($row["tipo_solicitud"] == "CURSO") {
            $tipo_capacitacion = "Curso";
            $btn = "btn-abrir-inscripcion-curso";
        }
        ?>
        <tr class="table-warning">
            <td class="table-check">
                <div class="checkbox checkbox-only">
                    <input type="checkbox" class="tramite_area_checkbox" id="<?php echo $row["tramite_gestionado_id"] ?>" />
                    <label for="<?php echo $row["tramite_gestionado_id"] ?>"></label>
                </div>
            </td>
            <td>
                <?php echo $tipo_capacitacion; ?>
                <div class="font-11 color-blue-grey-lighter uppercase">
                    <?php echo $row["tramite_nombre"]; ?>
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
            <td>
                <?php
                $key = "mi_key_secret";
                $cipher = "aes-256-cbc";
                $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
                $cifrado = openssl_encrypt($row["tramite_gestionado_id"], $cipher, $key, OPENSSL_RAW_DATA, $iv);
                $textoCifrado = base64_encode($iv . $cifrado);
                
                $icon = "eye-open";
                $color_icon = "2986cc";
                $title = "Ver solicitud";
                echo 
                '<button title="' . $title . '" type="button" code="' . $row["tramite_id"] . '" style="padding: 0;border: none;background: none;" 
                data-ciphertext="' . $textoCifrado . '" id="' . $textoCifrado . '" class="' . $btn . '"><i  
                class="glyphicon glyphicon-' . $icon . '" style="color:#' . $color_icon . '; font-size:large; margin: 3px;" aria-hidden="true"></i></button>';
                ?>
            </td>
        </tr>
        <?php
    }
    ?>
</tbody>