<section class="box-typical steps-icon-block" id="formulario">
    <div class="steps-icon-progress">
        <ul>
            <li class="active progress-1" id="progress-1">
                <div class="icon progress-1">
                    <i class="font-icon font-icon-notebook-lines"></i>
                </div>
                <div class="caption">Datos generales</div>
            </li>
            <li class="progress-2" id="progress-2">
                <div class="icon progress-2">
                    <i class="font-icon font-icon-notebook-lines"></i>
                </div>
                <div class="caption">Datos Personales</div>
            </li>
            <li class="progress-3" id="progress-3">
                <div class="icon progress-3">
                    <i class="font-icon font-icon-home"></i>
                </div>
                <div class="caption">Residencia Permanente</div>
            </li>
            <li class="progress-4" id="progress-4">
                <div class="icon progress-4">
                    <i class="glyphicon glyphicon-briefcase"></i>
                </div>
                <div class="caption">Datos Laborales</div>
            </li>
            <li class="progress-5" id="progress-5">
                <div class="icon progress-5">
                    <i class="glyphicon glyphicon-education"></i>
                </div>
                <div class="caption">Antecedentes Académicos</div>
            </li>
            <li class="progress-6 progress-6" id="progress-6">
                <div class="icon">
                    <i class="glyphicon glyphicon-education"></i>
                </div>
                <div class="caption">Post-grado</div>
            </li>
            <li class="progress-7" id="progress-7">
                <div class="icon progress-7">
                    <i class="glyphicon glyphicon-pencil"></i>
                </div>
                <div class="caption">Firma</div>
            </li>
        </ul>
    </div>
    <!-- Checks globales -->
    <div id="parte_1" class="row">
        <header class="steps-numeric-title">Datos Generales del Formulario</header>
        <div class="row" id="nivelContainer">
            <label for="nivel" style="text-align:center;margin:5px;">Nivel</label>
            <div class="col-xl-3">
                <div class="radio">
                    <input type="radio" name="optionsRadios" id="universitario" value="universitario">
                    <label for="universitario">Universitario</label>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="radio">
                    <input type="radio" name="optionsRadios" id="licenciado" value="licenciado">
                    <label for="licenciado">Licenciado</label>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="radio">
                    <input type="radio" name="optionsRadios" id="tecnico" value="tecnico">
                    <label for="tecnico">Técnico</label>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="radio">
                    <input type="radio" name="optionsRadios" id="auxiliar" value="auxiliar">
                    <label for="auxiliar">Auxiliar</label>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-rounded float-right" id="next-1">Siguiente →</button>
    </div>
    <!-- Checks globales -->

    <!-- Información personal -->
    <div id="parte_2" class="row parte_2">
        <header class="steps-numeric-title">Datos Personales</header>
        <div class="row">
            <div class="col-xl-6">
                <div class="form-group">
                    <label for="nombre" style="text-align:left;margin:5px;margin-left:30px;">Nombre
                        completo</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre"
                        value="<?php echo $_SESSION["nombre"] ?>" />
                </div>
            </div>
            <div class="col-xl-6">
                <div class="form-group">
                    <label for="apellido" style="text-align:left;margin:5px;margin-left:30px;">Apellido
                        completo</label>
                    <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Apellido"
                        value="<?php echo $_SESSION["apellido"] ?>" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group">
                <div class="col-xl-6">
                    <label for="documento_identidad" style="text-align:left;margin:5px;margin-left:30px;">Documento
                        de identidad</label>
                    <input type="text" class="form-control" id="documento_identidad" name="documento_identidad"
                        placeholder="Documento de identidad" value="<?php echo $_SESSION["cedula"] ?>" />
                </div>
            </div>
            <div class="col-xl-6">
                <div class="form-group">
                    <label class="form-label" for="estado_civil"
                        style="text-align:left;margin:5px;margin-left:30px;">Estado
                        Civil</label>
                    <select class="form-control " id="estado_civil" name="estado_civil" data-placeholder="Seleccionar">
                        <option label="Seleccionar"></option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6">
                <div class="form-group">
                    <label for="fecha_nacimiento" style="text-align:left;margin:5px;margin-left:30px;">Fecha
                        de nacimiento</label>
                    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento"
                        placeholder="" />
                </div>
            </div>
            <div class="col-xl-6">
                <div class="form-group">
                    <label for="pais_nacimiento" style="text-align:left;margin:5px;margin-left:30px;">País de
                        nacimiento</label>
                    <select class="form-control " id="pais_nacimiento" name="pais_nacimiento" onchange="cargarDptos()"
                        data-placeholder="Seleccionar">
                        <option label="Seleccionar"></option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6">
                <div class="form-group">
                    <label for="departamento_nacimiento" style="text-align:left;margin:5px;margin-left:30px;">Departamento
                        de nacimiento</label>
                    <select class="form-control " id="departamento_nacimiento" name="departamento_nacimiento"
                        onchange="cargarCiudades(this.id)" data-placeholder="Seleccionar">
                        <option label="Seleccionar"></option>
                    </select>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="form-group">
                    <label for="ciudad_nacimiento" style="text-align:left;margin:5px;margin-left:30px;">Ciudad
                        de nacimiento</label>
                    <select class="form-control " id="ciudad_nacimiento" name="ciudad_nacimiento" data-placeholder="Seleccionar">
                        <option label="Seleccionar"></option>
                    </select>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-rounded btn-grey float-left" id="back-1">← Atrás</button>
        <button type="button" class="btn btn-rounded float-right" id="next-2">Siguiente →</button>
    </div>
    <!-- Información Personal -->
    <!-- Residencia permanente -->
    <div id="parte_3" class="row parte_3">
        <header class="steps-numeric-title">Residencia Permantente</header>
        <div class="row">
            <div class="col-xl-6">
                <label for="direccion_residencia" style="text-align:left;margin:5px;margin-left:30px;">Dirección</label>
                <input type="text" class="form-control" id="direccion_residencia" name="direccion_residencia"
                    placeholder="Calle principal, número de casa y calle secundaria" />
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6">
                <div class="form-group">
                    <label for="departamento_residencia"
                        style="text-align:left;margin:5px;margin-left:30px;">Departamento</label>
                    <select class="form-control " id="departamento_residencia" name="departamento_residencia"
                        onchange="cargarCiudades(this.id)" data-placeholder="Seleccionar">
                        <option label="Seleccionar"></option>
                    </select>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="form-group">
                    <label for="ciudad_residencia" style="text-align:left;margin:5px;margin-left:30px;">Ciudad</label>
                    <select class="form-control " id="ciudad_residencia" name="ciudad_residencia"
                    onchange="cargarBarrios(this)" data-placeholder="Seleccionar">
                        <option label="Seleccionar"></option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-6">
                <div class="form-group">
                    <label for="barrio_residencia" style="text-align:left;margin:5px;margin-left:30px;">Barrio</label>
                    <select class="form-control " id="barrio_residencia" name="barrio_residencia"
                        data-placeholder="Seleccionar">
                        <option label="Seleccionar"></option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6">
                <div class="form-group">
                    <label for="telefono_residencia"
                        style="text-align:left;margin:5px;margin-left:30px;">Teléfono</label>
                    <input type="text" class="form-control" id="telefono_residencia" name="telefono_residencia"
                        placeholder="Telefono" value="<?php echo $_SESSION["telefono"] ?>" />
                </div>
            </div>
            <div class="col-xl-6">
                <div class="form-group">
                    <label for="celular_principal_residencia"
                        style="text-align:left;margin:5px;margin-left:30px;">Celular</label>
                    <input type="text" class="form-control" id="celular_principal_residencia"
                        name="celular_principal_residencia" placeholder="Celular" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6">
                <div class="form-group">
                    <label for="celular_secundario_residencia"
                        style="text-align:left;margin:5px;margin-left:30px;">Celular</label>
                    <input type="text" class="form-control" id="celular_secundario_residencia"
                        name="celular_secundario_residencia" placeholder="Celular" />
                </div>
            </div>
            <div class="col-xl-6">
                <div class="form-group">
                    <label for="email_residencia" style="text-align:left;margin:5px;margin-left:30px;">Email</label>
                    <input type="text" class="form-control" id="email_residencia" name="email_residencia"
                        placeholder="E-mail" value="<?php echo $_SESSION["email"] ?>" />
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-rounded btn-grey float-left" id="back-2">← Atrás</button>
        <button type="button" class="btn btn-rounded float-right" id="next-3">Siguiente →</button>
    </div>
    <!-- Residencia permanente -->
    <!-- Datos Laborales -->
    <div id="parte_4" class="row parte_4">
        <header class="steps-numeric-title">Datos Laborales</header>
        <header class="">Público</header>
        <div class="row">
            <div class="col-xl-6">
                <label for="servicio_publico" style="text-align:left;margin:5px;margin-left:30px;">Servicio</label>
                <input type="text" class="form-control" id="servicio_publico" name="servicio_publico"
                    placeholder="Servicio" />
            </div>

            <div class="col-xl-6">
                <label for="direccion_publico" style="text-align:left;margin:5px;margin-left:30px;">Dirección</label>
                <input type="text" class="form-control" id="direccion_publico" name="direccion_publico"
                    placeholder="Calle principal, número de casa y calle secundaria" />
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6">
                <div class="form-group">
                    <label for="departamento_publico"
                        style="text-align:left;margin:5px;margin-left:30px;">Departamento</label>
                    <select class="form-control " id="departamento_publico" name="departamento_publico"
                        onchange="cargarCiudades(this.id)" data-placeholder="Seleccionar">
                        <option label="Seleccionar"></option>
                    </select>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="form-group">
                    <label for="ciudad_publico" style="text-align:left;margin:5px;margin-left:30px;">Ciudad</label>
                    <select class="form-control " id="ciudad_publico" name="ciudad_publico"
                        data-placeholder="Seleccionar">
                        <option label="Seleccionar"></option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6">
                <div class="form-group">
                    <label for="telefono_publico" style="text-align:left;margin:5px;margin-left:30px;">Teléfono</label>
                    <input type="text" class="form-control" id="telefono_publico" name="telefono_publico"
                        placeholder="Telefono" value="<?php echo $_SESSION["telefono"] ?>" />
                </div>
            </div>
            <div class="col-xl-6">
                <div class="form-group">
                    <label for="email_publico" style="text-align:left;margin:5px;margin-left:30px;">Email</label>
                    <input type="text" class="form-control" id="email_publico" name="email_publico" placeholder="E-mail"
                        value="<?php echo $_SESSION["email"] ?>" />
                </div>
            </div>
        </div>
        <header class="">Privado</header>
        <div class="row">
            <div class="col-xl-6">
                <label for="servicio_privado" style="text-align:left;margin:5px;margin-left:30px;">Servicio</label>
                <input type="text" class="form-control" id="servicio_privado" name="servicio_privado"
                    placeholder="Servicio" />
            </div>
            <div class="col-xl-6">
                <label for="direccion_privado" style="text-align:left;margin:5px;margin-left:30px;">Dirección</label>
                <input type="text" class="form-control" id="direccion_privado" name="direccion_privado"
                    placeholder="Calle principal, número de casa y calle secundaria" />
            </div>

        </div>
        <div class="row">
            <div class="col-xl-6">
                <div class="form-group">
                    <label for="departamento_privado"
                        style="text-align:left;margin:5px;margin-left:30px;">Departamento</label>
                    <select class="form-control " id="departamento_privado" name="departamento_privado"
                        onchange="cargarCiudades(this.id)" data-placeholder="Seleccionar">
                        <option label="Seleccionar"></option>
                    </select>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="form-group">
                    <label for="ciudad_privado" style="text-align:left;margin:5px;margin-left:30px;">Ciudad</label>
                    <select class="form-control " id="ciudad_privado" name="ciudad_privado"
                        data-placeholder="Seleccionar">
                        <option label="Seleccionar"></option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6">
                <div class="form-group">
                    <label for="telefono_privado" style="text-align:left;margin:5px;margin-left:30px;">Teléfono</label>
                    <input type="text" class="form-control" id="telefono_privado" name="telefono_privado"
                        placeholder="Telefono" value="<?php echo $_SESSION["telefono"] ?>" />
                </div>
            </div>
            <div class="col-xl-6">
                <div class="form-group">
                    <label for="email_privado" style="text-align:left;margin:5px;margin-left:30px;">Email</label>
                    <input type="text" class="form-control" id="email_privado" name="email_privado" placeholder="E-mail"
                        value="<?php echo $_SESSION["email"] ?>" />
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-rounded btn-grey float-left" id="back-3">← Atrás</button>
        <button type="button" class="btn btn-rounded float-right" id="next-4">Siguiente →</button>
    </div>
    <!-- Datos Laborales -->

    <!-- Antecedentes Académicos -->
    <div id="parte_5" class="row parte_5">
        <header class="steps-numeric-title">Título de Salud obtenido</header>
        <div class="row">
            <div class="col-xl-6">
                <div class="form-group">
                    <label for="titulo_obtenido" style="text-align:left;margin:5px;margin-left:30px;">Título
                        (Profesión)</label>
                    <select class="form-control " id="titulo_obtenido" name="titulo_obtenido"
                        data-placeholder="Seleccionar">
                        <option label="Seleccionar"></option>
                    </select>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="form-group">
                    <label for="institucion_titulo"
                        style="text-align:left;margin:5px;margin-left:30px;">Institución/Universidad</label>
                    <select class="form-control " id="institucion_titulo" name="institucion_titulo"
                        data-placeholder="Seleccionar">
                        <option label="Seleccionar"></option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6">
                <div class="form-group">
                    <label for="pais" style="text-align:left;margin:5px;margin-left:30px;">País</label>
                    <select class="form-control " id="pais_titulo" name="pais_titulo"
                        data-placeholder="Seleccionar">
                        <option label="Seleccionar"></option>
                    </select>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-rounded btn-grey float-left" id="back-4">← Atrás</button>
        <button type="button" class="btn btn-rounded float-right" id="next-5">Siguiente →</button>
    </div>
    <!-- Antecedentes Académicos -->
    <!-- Post-grado -->
    <div id="parte_6" class="row parte_6">
        <header class="steps-numeric-title">Post-grado</header>
        <div class="row">
            <div class="col-xl-6">
                <div class="form-group">
                    <label for="titulo_postgrado" style="text-align:left;margin:5px;margin-left:30px;">Título
                        (Profesión)</label>
                    <select class="form-control " id="titulo_postgrado" name="titulo_postgrado"
                        data-placeholder="Seleccionar">
                        <option label="Seleccionar"></option>
                    </select>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="form-group">
                    <label for="institucion_postgrado"
                        style="text-align:left;margin:5px;margin-left:30px;">Institución/Universidad</label>
                    <select class="form-control " id="institucion_postgrado" name="institucion_postgrado"
                        data-placeholder="Seleccionar">
                        <option label="Seleccionar"></option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6">
                <div class="form-group">
                    <label for="pais" style="text-align:left;margin:5px;margin-left:30px;">País</label>
                    <select class="form-control " id="pais_postgrado" name="pais_postgrado"
                        data-placeholder="Seleccionar">
                        <option label="Seleccionar"></option>
                    </select>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-rounded btn-grey float-left" id="back-5">← Atrás</button>
        <button type="button" class="btn btn-rounded float-right" id="next-6">Siguiente →</button>
    </div>
    <!-- Firma -->
    <div id="parte_7" class="row parte_7">
        <header class="steps-numeric-title">Favor, ingrese su firma</header>
        <div class="form-floating mb-2 col-6">
            <div class="input-group col-md-6">
                <canvas id="canvas" style="border: 1px solid black;margin:30px"></canvas>
                <button type="button" style="padding: 0;border: none;background: none; color:#3faab9"
                    onclick="limpiarFirma()">
                    <i class="glyphicon glyphicon-erase" title="Borrar firma"></i>
            </div>
        </div>
        <button type="button" class="btn btn-rounded btn-grey float-left" id="back-6">← Atrás</button>

    </div>
    <!-- Firma -->

</section><!--.steps-icon-block-->