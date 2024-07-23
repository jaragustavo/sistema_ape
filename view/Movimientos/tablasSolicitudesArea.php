<?php

?>
<tbody >
    <thead>
        <tr>
            <th >Elegir</th>
            <th style="width: 20%;">Solicitado por</th>
            <th style="width: 14%;">Fecha de solicitud</th>
            <th style="width: 15%;">Avance</th>
            <th style="width: 16%;">Asignado a</th>
            <th style="width: 15%;">Estado actual</th>
            <th style="width: 14%;">Último movimiento</th>
           
        </tr>
    </thead>
    <?php
    require_once "../../models/Movimiento.php";
    $datos = Movimiento::get_tramites($_SESSION["area_id"], "area", $_SESSION["usuario_id"]);
    $data = array();
    foreach ($datos as $row) {
        if ($row["usuario_asignado"] == "") {
            $row["usuario_asignado"] = "sin fiscalizador";
        }
        ?>
        <tr class="table-warning table-tramites" data-tramite="<?php echo htmlspecialchars($row["tramite_id"]); ?>">
             <td class="table-check">
                <div class="checkbox checkbox-center">
                    <input type="checkbox" class="tramite_area_checkbox" id="<?php echo $row["tramite_gestionado_id"] ?>" />
                    <label for="<?php echo $row["tramite_gestionado_id"] ?>"></label>
                </div>
            </td>
          
            <td>
                <?php echo $row["usuario_solicitante"] ?>
                <div class="font-11 color-blue-grey-lighter uppercase">
                    <?php echo $row["tramite_nombre"] ?>
                </div>
            </td>
            <td>
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
            <td width="150">
                <?php
                require("../Formularios/avance.php");
                ?>
                <div class="progress-with-amount">
                    <progress class="progress progress<?php echo $color ?> progress-no-margin" value="<?php echo $avance ?>"
                        max="100">
                        <?php echo $avance ?>%
                    </progress>
                    <div class="progress-with-amount-number">
                        <?php echo $avance ?>%
                    </div>
                </div>
            </td>

            <td nowrap="nowrap">
                <?php echo $row["usuario_asignado"] ?>
                <div class="font-11 color-blue-grey-lighter uppercase">
                    <?php echo $row["area_asignada"] ?>
                </div>
            </td>
            <td>
                <div class="color-blue-grey-lighter uppercase">
                    <?php echo $row["estado_actual"] ?>
                </div>
            </td>
            <td>
                <div class="color-blue-grey-lighter uppercase">
                    <?php echo date("d/m/Y", strtotime($row["ultimo_movimiento"])) ?>
                </div>
            </td>
           
            <td>
                <?php

                $cifrado = openssl_encrypt($row["tramite_gestionado_id"], $cipher, $key, OPENSSL_RAW_DATA, $iv);
                $textoCifrado = base64_encode($iv . $cifrado);
                ?>
                <!-- <button title="Verificar solicitud" style="padding: 0;border: none;background: none;" type="button" data-ciphertext="'<?php echo $textoCifrado ?>'" id="'<?php echo $textoCifrado ?>'" class="btn-open-pdf"><i class="glyphicon glyphicon-eye-open" style="color:#2986cc; font-size:large; margin: 3px;" aria-hidden="true"></button></i> -->

            </td>
           
        </tr>
        <?php
    }
    ?>
</tbody>