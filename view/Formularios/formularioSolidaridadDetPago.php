<section class="box-typical steps-icon-block" id="formulario">

    <!-- Información personal -->
    <div id="parte_2" class="row parte_2">
        <header class="steps-numeric-title">Datos para el desembolso</header>
        <div class="row">
            <div class="col-xl-12">
                <div class="form-group">
                    <label for="forma_cobro">¿Cómo desea cobrar?</label>
                    <select class="form-control " id="forma_cobro" name="forma_cobro" onchange="mostrarFormsPago()"
                        data-placeholder="Seleccionar">
                        <option label="Seleccionar"></option>
                        <option value='1'>Por transferencia</option>
                        <option value='2'>Retirar en efectivo de la APE</option>
                    </select>
                </div>
            </div>
        </div>
        <div id="bloque_transferencia" style="display:none">
            <div class="row">
                <div class="col-xl-6">
                    <div class="form-group">
                        <label for="banco" style="text-align:left;margin:5px;margin-left:30px;">Banco</label>
                        <select class="form-control " id="banco" name="banco" data-placeholder="Seleccionar">
                            <option label="Seleccionar"></option>
                        </select>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="form-group">
                        <label for="tipo_cuenta" style="text-align:left;margin:5px;margin-left:30px;">Tipo de
                            Cuenta</label>
                        <select class="form-control " id="tipo_cuenta" name="tipo_cuenta"
                            data-placeholder="Seleccionar">
                            <option label="Seleccionar"></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6">
                    <div class="form-group">
                        <label for="numero_cuenta" style="text-align:left;margin:5px;margin-left:30px;">Número de
                            cuenta</label>
                        <input type="text" class="form-control" id="numero_cuenta" name="numero_cuenta"
                            placeholder="Número de cuenta" />
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="form-group">
                        <label for="denominacion_cuenta"
                            style="text-align:left;margin:5px;margin-left:30px;">Denominación de la Cuenta</label>
                            <input type="text" class="form-control" id="denominacion_cuenta" name="denominacion_cuenta"
                            placeholder="Denominación de la cuenta" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6">
                    <label for="doc_identidad_cuenta" style="text-align:left;margin:5px;margin-left:30px;">Documento
                        de identidad</label>
                    <input type="text" class="form-control" id="doc_identidad_cuenta" name="doc_identidad_cuenta"
                        placeholder="Documento de identidad"/>
                </div>
                <div class="col-xl-6">
                    <div class="form-group">
                        <label for="telefono_cuenta" style="text-align:left;margin:5px;margin-left:30px;">Teléfono</label>
                        <input type="text" class="form-control" id="telefono_cuenta" name="telefono_cuenta" placeholder="Telefono"
                            />
                    </div>
                </div>
            </div>
        </div>
        <div id="bloque_filial" style="display:none">
            <label>Seleccione la filial de la APE de donde desea retirar el monto</label>
            <div class="col-xl-6">
                <div class="form-group">
                    <label for="filial" style="text-align:left;margin:5px;margin-left:30px;">Filial</label>
                    <select class="form-control " id="filial" name="filial" data-placeholder="Seleccionar">
                        <option label="Seleccionar"></option>
                    </select>
                </div>
            </div>
        </div>

    </div>

</section><!--.steps-icon-block-->

