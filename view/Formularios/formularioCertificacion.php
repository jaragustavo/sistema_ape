
<section  id="formulario">
    <!-- Información personal -->
         
        <div class="row">
            <div class="container">
           
                <div class="row">
                    <div class="col-md-9">
                        <div class="form-group">
                            <label for="nombre_autor">Solicita optar por la Certificación de la Especialidad</label>
                            <input type="text" class="form-control" id="especialidad" name="especialidad" placeholder="Certificación de la Especialida"
                            value="<?php echo $especialidad;?>" disabled/>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre"  value="<?php echo $nombre;?>" disabled/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="documento_identidad">Nro.Documento</label>
                            <input type="text" class="form-control" id="documento_identidad" name="documento_identidad" placeholder="Documento de identidad" 
                            value="<?php echo $documento_identidad;?>" disabled/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_nacimiento">Fecha de nacimiento</label>
                            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" placeholder="dd/mm/yyyy" 
                            value="<?php echo $fecha_nacimiento;?>" disabled/>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="telefono">Telefono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" 
                            value="<?php echo $telefono;?>" disabled/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="correo">Telefono</label>
                            <input type="text" class="form-control" id="correo" name="correo" 
                            value="<?php echo $correo;?>" disabled/>
                        </div>
                    </div>
                     
                    
                    <div class="col-md-9">
                        <div class="form-group">
                            <label for="institucion">Cursado en la Institución</label>
                            <input type="text" class="form-control" id="institucion" name="institucion" placeholder="Nombre de la Institución"
                                   value="<?php echo htmlspecialchars($institucion); ?>" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_culminacion">Culminado en fecha</label>
                            <input type="date" class="form-control" id="fecha_culminacion" name="fecha_culminacion" placeholder="Fecha de Culminación"
                                   value="<?php echo htmlspecialchars($fecha_culminacion); ?>" />
                        </div>
                    </div>
                 
                </div>
             
              
                
            </div>
        </div>
   
</section><!--.steps-icon-block-->