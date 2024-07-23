<?php

?>
<tbody>
    <thead>
        <tr>
            <th></th>
            <th style="width: 16%;">Solicitado por</th>
            <th style="width: 14%;">Fecha de solicitud</th>
            <th style="width: 15%;">Avance</th>
            <th style="width: 16%;">Asignado a</th>
            <th style="width: 15%;">Estado actual</th>
            <th style="width: 14%;">Último movimiento</th>
            <th style="width: 10%;">Acciones</th>
        </tr>
    </thead>
    <?php
    require_once "../../models/Movimiento.php";
    $datos = Movimiento::get_tramites($_SESSION["area_id"], "usuario", $_SESSION["usuario_id"]);
    $data = array();
    foreach ($datos as $row) {
        if ($row["usuario_asignado"] == "") {
            $row["usuario_asignado"] = "sin fiscalizador";
        }
        ?>
        <tr class="table-warning table-asignadas" data-tramite="<?php echo htmlspecialchars($row["tramite_id"]); ?>">
            <td class="table-check">
                <div class="checkbox checkbox-only">
                    <input type="checkbox" id="<?php echo $row["tramite_gestionado_id"] ?>" />
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
                $key = "mi_key_secret";
                $cipher = "aes-256-cbc";
                $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));

                $cifrado = openssl_encrypt($row["tramite_gestionado_id"], $cipher, $key, OPENSSL_RAW_DATA, $iv);
                $textoCifrado = base64_encode($iv . $cifrado);
                ?>
                <button title="Verificar solicitud" style="padding: 0;border: none;background: none;" type="button"
                    data-ciphertext="<?php echo $textoCifrado ?>" id="<?php echo $textoCifrado ?>"
                    class="btn-open-solicitud"><i class="glyphicon glyphicon-eye-open"
                        style="color:#2986cc; font-size:large; margin: 3px;" aria-hidden="true"></button></i>

            </td>
        </tr>
        <?php
    }
    ?>

    <!-- <tr class="table-danger">
        <td class="table-check">
            <div class="checkbox checkbox-only">
                <input type="checkbox" id="tbl-check-1"/>
                <label for="tbl-check-1"></label>
            </div>
        </td>
        <td>Maite Astigarraga</td>
        <td>02/02/2024</td>
        <td width="150">
            <div class="progress-with-amount">
                <progress class="progress progress-danger progress-no-margin" value="25" max="100">25%</progress>
                <div class="progress-with-amount-number">25%</div>
            </div>
        </td>
        <td nowrap="nowrap">Fiscalización<span class="caret color-red"></span></td>
        <td>
            <div class="color-blue-grey-lighter uppercase">En revisión</div>
        </td>
        <td>
            <div class="color-blue-grey-lighter uppercase">02/02/2024</div>
        </td>
        <td>
        </td>
    </tr>
    <tr class="table-info">
        <td class="table-check">
            <div class="checkbox checkbox-only">
                <input type="checkbox" id="tbl-check-1"/>
                <label for="tbl-check-1"></label>
            </div>
        </td>
        <td>Maite Astigarraga</td>
        <td>02/02/2024</td>
        <td width="150">
            <div class="progress-with-amount">
                <progress class="progress progress progress-no-margin" value="50" max="100">50%</progress>
                <div class="progress-with-amount-number">50%</div>
            </div>
        </td>
        <td nowrap="nowrap">Fiscalización<span class="caret color-red"></span></td>
        <td>
            <div class="color-blue-grey-lighter uppercase">En revisión</div>
        </td>
        <td>
            <div class="color-blue-grey-lighter uppercase">02/02/2024</div>
        </td>
        <td>
        </td>
    </tr>
    <tr class="table-success">
        <td class="table-check">
            <div class="checkbox checkbox-only">
                <input type="checkbox" id="tbl-check-1"/>
                <label for="tbl-check-1"></label>
            </div>
        </td>
        <td>Maite Astigarraga</td>
        <td>02/02/2024</td>
        <td width="150">
            <div class="progress-with-amount">
                <progress class="progress progress-success progress-no-margin" value="75" max="100">75%</progress>
                <div class="progress-with-amount-number">75%</div>
            </div>
        </td>
        <td nowrap="nowrap">Fiscalización<span class="caret color-red"></span></td>
        <td>
            <div class="color-blue-grey-lighter uppercase">En revisión</div>
        </td>
        <td>
            <div class="color-blue-grey-lighter uppercase">02/02/2024</div>
        </td>
        <td>
        </td>
    </tr> -->
</tbody>