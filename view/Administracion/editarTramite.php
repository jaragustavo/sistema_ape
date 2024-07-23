<?php
require_once ("../../config/conexion.php");
if (isset($_SESSION["usuario_id"])) {
    ?>
    <!DOCTYPE html>
    <html>
    <?php require_once ("../MainHead/head.php"); ?>

    <title>Trámite</title>
    </head>

    <body class="with-side-menu">

        <?php require_once ("../MainHeader/header.php"); ?>

        <div class="mobile-menu-left-overlay"></div>

        <?php require_once ("../MainNav/nav.php"); ?>

        <!-- Contenido -->
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xxl-9 col-lg-12 col-xl-8 col-md-8">
                        <section class="box-typical proj-page">
                            <section class="proj-page-section proj-page-header">
                                <div class="title">
                                    Editar trámite
                                </div>
                            </section><!--.proj-page-section-->

                            <!-- formulario -->
                            <div class="container-fluid">
                                <div class="row">
                                    <input type="hidden" id="idEncrypted" name="idEncrypted">
                                    <?php
                                    $id_seccion = $_GET["ID"];
                                    $id_seccion = str_replace(' ', '+', $id_seccion);
                                    $key = "mi_key_secret";
                                    $cipher = "aes-256-cbc";
                                    $iv_dec = substr(base64_decode($id_seccion), 0, openssl_cipher_iv_length($cipher));
                                    $cifradoSinIV = substr(base64_decode($id_seccion), openssl_cipher_iv_length($cipher));
                                    $decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);
                                    ?>
                                    <form method="post" id="datos_reserva_form">
                                        <section class="box-typical steps-icon-block" id="formulario">
                                            <!-- Información personal -->
                                            <div class="row">
                                                <div class="col-xl-6">
                                                    <div class="form-group">
                                                        <label for="nombre_tramite"
                                                            style="text-align:left;margin:5px;margin-left:30px;">Nombre</label>
                                                        <input type="text" class="form-control" id="nombre_tramite"
                                                            name="nombre_tramite" placeholder="Nombre del trámite" />
                                                    </div>
                                                </div>
                                                <div class="col-xl-6">
                                                    <div class="form-group">
                                                        <label class="form-label" for="tipo_tramite"
                                                            style="text-align:left;margin:5px;margin-left:30px;">Tipo de trámite</label>
                                                        <select class="form-control " id="tipo_tramite" name="tipo_tramite"
                                                            data-placeholder="Seleccionar">
                                                            <option label="Seleccionar"></option>
                                                            <option label="Administrativo" value="ADMIN"></option>
                                                            <option label="Curso" value="CURSO"></option>
                                                            <option label="Concurso" value="CONCURSO"></option>
                                                            <option label="Certificación" value="CERT"></option>
                                                            <option label="Ayuda al socio" value="AYUDA"></option>
                                                            <option label="Sede Social" value="SOCIAL"></option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                        </section><!--.steps-icon-block-->

                                    </form>
                                </div><!--.row-->
                                
                            </div><!--.container-fluid-->
                        </section><!-- box-typical proj-page -->
                    </div>



                    <div class="col-xxl-3 col-lg-12 col-xl-4 col-md-4">
                        <section class="box-typical proj-page">

                            <section class="proj-page-section">
                                <ul class="proj-page-actions-list">
                                    <li onclick="guardarTramite()" id="guardar_datos_btn"><a><i
                                                class="font-icon font-icon-check-square"></i>guardar tramite</a></li>
                                    <li><a class="cancelar" href="listarTramites.php"><i
                                                class="glyphicon glyphicon-trash"></i>
                                            Cancelar</a>
                                    </li>
                                </ul>
                            </section><!--.proj-page-section-->
                        </section><!--.proj-page-->
                    </div>
                </div><!--.row-->
                <div class="row">
                    <header class="section-header" style="margin-top: -40px;margin-bottom: -30px;">
                    <div class="tbl">
                        <div class="tbl-row">
                            <div class="tbl-cell">
                                <h3>Estados de este trámite</h3>
                            </div>
                            <div class="tbl-cell">
                                <div class="col-lg-6"></div>
                                <div class="col-lg-6">
                                    <fieldset class="form-group">
                                        
                                        <button onclick="openBloqueEstado()"
                                            class="btn btn-rounded btn-primary btn-block">Nuevo Estado</button>
                                    </fieldset>
                                    <script>

                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                    </header>
                </div>
                <div class="box-typical-body" id="estados_area">
                    <div class="table-responsive tableFixHead">
                        <table class="table table-hover" id="estados_data">

                            <tbody>
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">Paso</th>
                                        <th style="width: 16%;">Estado</th>
                                        <th style="width: 16%;">Duración estimada (hs)</th>
                                        <th style="width: 10%;">Acciones</th>
                                    </tr>
                                </thead>
                                <?php
                                require_once "../../models/Administracion.php";

                                if (isset ($_GET["ID"])) {
                                    $datos = Administracion::get_estados_x_tramite($decifrado);
                                    $data = array();
                                    foreach ($datos as $row) {
                                        ?>
                                        <tr class="table-warning">
                                            <td>
                                                <?php echo $row["paso_estado"] ?>
                                            </td>
                                            <td>
                                                <?php echo $row["nombre_estado"] ?>
                                            </td>
                                            <td>
                                                <?php echo $row["duracion_estimada"] ?>
                                            </td>
                                            <td>
                                                <?php

                                                $cifrado = openssl_encrypt($row["estado_tramite_id"], $cipher, $key, OPENSSL_RAW_DATA, $iv);
                                                $textoCifrado = base64_encode($iv . $cifrado);
                                                ?>
                                                <button title="Editar" onclick="mostrarEstado(this.id)"
                                                    style="padding: 0;border: none;background: none;" type="button"
                                                    data-ciphertext="'<?php echo $textoCifrado ?>'"
                                                    id="'<?php echo $textoCifrado ?>'"
                                                    class="btn-editar-leccion abrir-leccion-form"><i
                                                        class="glyphicon glyphicon-edit"
                                                        style="color:#6aa84f; font-size:large; margin: 3px;"
                                                        aria-hidden="true"></button></i>
                                                <button title="Eliminar" style="padding: 0;border: none;background: none;"
                                                    type="button" data-ciphertext="'<?php echo $textoCifrado ?>'"
                                                    id="'<?php echo $textoCifrado ?>'" class="btn-delete-estado"><i
                                                        class="glyphicon glyphicon-trash"
                                                        style="color:#e06666; font-size:large; margin: 3px;"
                                                        aria-hidden="true"></button></i>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }

                                ?>
                            </tbody>
                        </table>
                    </div>
                </div><!--.box-typical-body-->


                <div class="row" style="margin-top:20px;">
                    <div class="col-xxl-12 col-lg-12 col-xl-12 col-md-12" id="bloque_estado_tramite" style="display:none">
                        <section class="box-typical proj-page">
                            <section class="proj-page-section proj-page-header">
                                <div class="title">
                                    Estado del Trámite
                                </div>
                            </section><!--.proj-page-section-->

                            <!-- formulario -->
                            <section class="box-typical steps-icon-block" id="formulario_estado_tramite">
                                <div class="container-fluid">
                                    <div class="row">
                                        <form method="post" id="datos_estado_tramite_form">
                                            <input type="hidden" id="estado_tramite_id" name="estado_tramite_id">
                                            <div id="parte_2" class="row parte_2">
                                                <div class="row">
                                                    <div class="col-xl-4">
                                                        <div class="form-group">
                                                            <label for="estado">Estado</label>
                                                            <select class="form-control " id="estado" name="estado"
                                                            data-placeholder="Seleccionar">
                                                            <option label="Seleccionar"></option>
                                                        </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4">
                                                        <div class="form-group">
                                                            <label for="paso_estado">Paso</label>
                                                            <input type="text" class="form-control" id="paso_estado"
                                                                name="paso_estado" placeholder="paso" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4">
                                                        <div class="form-group">
                                                            <label for="duracion_estimada">Duración estimada (en horas)</label>
                                                            <input type="number" class="form-control" id="duracion_estimada"
                                                                name="duracion_estimada" placeholder="Duración estimada" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xxl-12 col-lg-12 col-xl-12 col-md-12">
                                                <button type="button" name="action" value="add" onclick="guardarEstadoTramite()"
                                                    class="btn btn-rounded btn-primary">Guardar</button>
                                                <button type="button" name="cancel" class="btn btn-rounded btn-secondary"
                                                    onclick="close_bloque_estado()">Cancelar</button>
                                            </div>
                                        </form>
                                        <script>
                                            function close_bloque_estado() {
                                                var targetElement = document.getElementById("bloque_estado_tramite");
                                                // Change its styles
                                                targetElement.style.display = "none";
                                            }
                                        </script>
                                    </div><!--.row-->
                                </div>

                            </section>
                        </section><!-- box-typical proj-page -->
                    </div>
                </div>
                <section class="card" id="panel_igualar_estados">
				<header class="card-header">
                    ¿Desea asignar estos mismos estados a los otros trámites de este tipo?
					<button type="button" class="modal-close" onclick="closePanel()">
						<i class="font-icon-close-2"></i>
					</button>
				</header>
				<div class="card-block">
                <button onclick="generalizarEstadosTipoTramite()"
                    class="btn btn-rounded btn-success">Igualar Estados</button>
				</div>
			</section>
            </div><!--.container-fluid-->
        </div><!--.page-content-->
        <!-- Contenido -->

        <?php require_once ("../MainJs/js.php"); ?>
        <script type="text/javascript" src="administracion.js?v=<?php echo time(); ?>"></script>
        <?php require_once ("../html/footer.php"); ?>
        <script src="../../public/js/lib/input-mask/jquery.mask.min.js"></script>
        <script src="../../public/js/lib/input-mask/input-mask-init.js"></script>

    </body>

    </html>
    <?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>