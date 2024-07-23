
<section  id="formulario">
    <!-- Información personal -->
         
        <div class="row">
            <div class="container">
                <header class="steps-numeric-title text-center" style="font-weight: bold;padding:0px 0px 10px 0px">Datos Ficha Técnica</header>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nombre_autor">Nombre del Autor</label>
                            <input type="text" class="form-control" id="nombre_autor" name="nombre_autor" placeholder="Nombre del autor o nombres de los autores"
                                   value="<?php echo htmlspecialchars($nombre_autor); ?>" />
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="documento_identidad">Documento Id.</label>
                            <input type="text" class="form-control" id="documento_identidad" name="documento_identidad" placeholder="Documento de identidad"
                                   value="<?php echo htmlspecialchars($documento_identidad); ?>" />
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Teléfono"
                                   value="<?php echo htmlspecialchars($telefono); ?>" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="correo">Correo</label>
                            <input type="email" class="form-control" id="correo" name="correo" placeholder="Correo"
                                   value="<?php echo htmlspecialchars($correo); ?>" />
                        </div>
                    </div>

                </div>
              

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="titulo_investigacion">Título de la Investigación</label>
                            <input type="text" class="form-control" id="titulo_investigacion" name="titulo_investigacion" placeholder="Título de la Investigación"
                                   value="<?php echo htmlspecialchars($titulo_investigacion); ?>" />
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="anio_trabajo">Año</label>
                            <input type="number" class="form-control" id="anio_trabajo" name="anio_trabajo" placeholder="Año del Trabajo"
                                   value="<?php echo htmlspecialchars($anio_trabajo); ?>" />
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tipo_vinculo">Vínculo</label>
                            <select class="form-control" id="tipo_vinculo" name="tipo_vinculo">
                                <!-- <option value="-1">Seleccione tipo vinculo</option> -->
                                <option value="Asociación" <?php if ($tipo_vinculo == 'Asociación') echo 'selected'; ?>>Asociación</option>
                                <!-- <option value="Federación" <?php //if ($tipo_vinculo == 'Federación') echo 'selected'; ?>>Federación</option>
                                <option value="Colegiado" <?php //if ($tipo_vinculo == 'Colegiado') echo 'selected'; ?>>Colegiado</option> -->
                            </select>
                        </div>
                    </div>

         
                </div>

                <div class="row">
                   <div class="col-md-6">
                        <div class="form-group">
                            <label for="institucion_autor">Institución del Autor</label>
                            <input type="text" class="form-control" id="institucion_autor" name="institucion_autor" placeholder="Institución del Autor"
                                   value="<?php echo htmlspecialchars($institucion_autor); ?>" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="pais">País:</label>
                            <select class="form-control" id="pais" name="pais">
                                <option value="<?php echo htmlspecialchars($pais); ?>">Seleccione un país</option>
                            </select>
<!-- 



                            <label for="pais">País</label>
                            <input type="text" class="form-control" id="pais" name="pais" placeholder="País"
                                   value="<?php //echo htmlspecialchars($pais); ?>" /> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
   
</section><!--.steps-icon-block-->