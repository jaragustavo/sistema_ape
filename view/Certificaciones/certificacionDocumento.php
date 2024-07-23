<div class="form-group" id="documentos_requeridos">
    <input type="hidden" id="tramite_id">
    <!-- <div class="form-group"> -->
    <div class="row" style="margin-left:25px;">

        <?php
        require_once "../../models/Certificacion.php";
        $tiposDocumentos = Certificacion::get_docsrequeridos_x_tramite($_GET['code']);
        $cantidad_actual = 0;
        foreach ($tiposDocumentos as $key => $value) {
            if ($cantidad_actual - 1 % 2 == 0) {
                ?>
                <div class="row" style="margin-left:25px;">
                <?php
            }
            ?>
        
                <div class="col-md-6">
                    <div class="row" style="width:100%;">
                        <div class="form-group agregarMultimedia">
                            <b style="font-size:18px;color:#1e4568;">
                                <?php echo $value["tipo_documento"] ?> 
                                <button class="verifyButton" style="padding: 0;border: none;background: none; color:#3faab9" type="button">
                                    <span class="glyphicon glyphicon-question-sign" title="Verificar con entidad correspondiente"></span>
                                </button>
                            </b>
                            <div class="multimediaFisica needsclick dz-clickable" data-tipo-documento-id="<?php echo $value["tipo_documento_id"]; ?>">
                                <div class="dz-message needsclick">
                                    Arrastrar o dar click para subir imágenes.
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
                

                <?php
                //
                if ($cantidad_actual - 1 % 2 == 0) {
                    ?>
                </div>
            <?php
                }

                $cantidad_actual += 1;
        }
        ?>
    </div>
</div>