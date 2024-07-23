

<?php
    require_once "../../models/Certificacion.php";
    require_once "../../models/Concursos.php";
    require_once "../../models/Usuario.php";

    if (isset($_GET['ID'])) {
        $id_solicitud = $_GET["ID"];
        $id_solicitud = str_replace(' ', '+', $id_solicitud);
        $key = "mi_key_secret";
        $cipher = "aes-256-cbc";
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
        $iv_dec = substr(base64_decode($id_solicitud), 0, openssl_cipher_iv_length($cipher));
        $cifradoSinIV = substr(base64_decode($id_solicitud), openssl_cipher_iv_length($cipher));
        $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);

       
        
    } else {

        $decifrado = '';
    }
?>
  
<input type="hidden" id="tramite_id" value="<?php echo $decifrado ?>" > 

<input type="hidden" id="tipo_solicitud" value="solidaridad">

<div class="container-fluid" id="formulario">

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                     
                        <label for="nombre" >Nombre
                            completo</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre"
                             />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="apellido" >Apellido
                            completo</label>
                        <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Apellido"
                            />
                    </div>
                </div>
        
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="documento_identidad" >Documento
                            de identidad</label>
                        <input type="text" class="form-control" id="documento_identidad" name="documento_identidad"
                            placeholder="Documento de identidad"  />
                    </div>
                </div>
                <div class="col-md-3">
            
                    <div class="form-group">
                        <label for="telefono" >Teléfono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Telefono"
                        /> 
                    </div>
                </div>
                
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                    <input type= "hidden" id="departamento_nombre" name="departamento_nombre" />
                        <label for="departamento_id">Departamento</label>
                        <select class="form-control" id="departamento_id" name="departamento_id"> 
                            <option value="-1">Seleccione Departamento</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <input type= "hidden" id="ciudad_nombre" name="ciudad_nombre" />
                        <label for="ciudad_id">Ciudad</label>
                        <select class="form-control" id="ciudad_id" name="ciudad_id">
                            <option value="">Seleccione Ciudad</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="direccion_domicilio">Dirección de domicilio</label>
                        <input type="text" class="form-control" id="direccion_domicilio" name="direccion_domicilio"  
                        placeholder="Dirección de su domicilio" style="width: 100%;" />
                    </div>
                </div>
             
            </div>

       
    </div>


