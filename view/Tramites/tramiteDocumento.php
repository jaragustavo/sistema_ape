<div class="form-group" id="documentos_requeridos">
    <input type="hidden" id="tramite_id">
    <!-- <div class="form-group"> -->
    <div class="row" style="margin-left:25px;">

        <?php
        require_once "../../models/Tramite.php";
        $tiposDocumentos = Tramite::get_docsrequeridos_x_tramite($_GET["code"]);
        $docsAdjuntos =[];
        if(isset($_GET['ID'])){
            $id_tramite_gestionado = $_GET["ID"];
            $id_tramite_gestionado = str_replace(' ', '+', $id_tramite_gestionado);
            $key = "mi_key_secret";
            $cipher = "aes-256-cbc";
            $iv_dec = substr(base64_decode($id_tramite_gestionado), 0, openssl_cipher_iv_length($cipher));
            $cifradoSinIV = substr(base64_decode($id_tramite_gestionado), openssl_cipher_iv_length($cipher));
            $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);
            $docsAdjuntos = Tramite::get_docsadjuntos_x_tramite($decifrado);
        }
        
        $cantidad_actual = 0;
        foreach ($tiposDocumentos as $key => $value) {
            if ($cantidad_actual - 1 % 2 == 0) {
                ?>
                <div class="row" style="margin-left:25px;">
                    <?php
            }
            ?>
                <div class="col-md-6" onclick="cargarIdDoc(this.id)" id="<?php echo $value["tipo_documento_id"] ?>">
                    <div class="row" style="width:80%;">
                        <div class="form-group agregarMultimedia">
                            <b style="font-size:18px;color:#1e4568;">
                                <?php echo $value["tipo_documento"] ?> <button class="verifyButton"
                                    style="padding: 0;border: none;background: none; color:#3faab9" type="button">
                                    <span class="glyphicon glyphicon-question-sign"
                                        title="Verificar con entidad correspondiente"></span>
                                </button>
                            </b>

                            <div class="multimediaFisica needsclick dz-clickable">
                                <div class="dz-message needsclick">
                                    Arrastrar o dar click para subir el documento.
                                </div>
                            </div>

                        </div>
                        <?php
                        foreach ($docsAdjuntos as $docAdjunto) {
                            if (str_contains($docAdjunto['documento'], $value['nombre_corto'])) {
                                ?>
                                <div class="proj-page-attach">
                                    <i class="font-icon font-icon-doc"></i>
                                    <p class="name">
                                        <?php echo basename($docAdjunto["documento"]) ?>
                                    </p>
                                    <p class="date">
                                        <?php echo $docAdjunto["hora_formato_doc"] . ", " . $docAdjunto["fecha_formato_doc"] ?>
                                    </p>
                                    <p>
                                        <a href="<?php echo '../' . $docAdjunto["documento"] ?>" target="_blank">Ver</a>
                                    </p>
                                </div>
                                <?php
                            }
                        }
                        ?>
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