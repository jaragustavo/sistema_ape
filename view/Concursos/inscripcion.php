<?php
require_once ("../../config/conexion.php");
if (isset($_SESSION["usuario_id"])) {
    $root_path_assets = "/sistema_ape/";
	?>
	<!DOCTYPE html>
	<html>
	<link rel="stylesheet" href="plugins/dropzone/dropzone.css" type="text/css">
	<?php require_once ("../MainHead/head.php"); ?>

	<!-- <link rel="stylesheet" href="plugins/css/dropzone.css"> -->

	<title>Inscripción</title>
	</head>
	<style>
		#parte_2,
		#parte_3,
		#parte_4,
		#parte_5,
		#parte_6,
		#parte_7,
		#progress-5,
		#progress-6,
		#progress-7 {
			display: none;
		}
	</style>

	<body class="with-side-menu">
	<style>
        label {
            font-weight: bold;
        }
    </style>

		<?php require_once ("../MainHeader/header.php"); ?>

		<div class="mobile-menu-left-overlay"></div>

		<?php require_once ("../MainNav/nav.php"); ?>

		<!-- Contenido -->
		<div class="page-content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-xxl-9 col-lg-12 col-xl-8 col-md-8">
						<section class="box-typical proj-page">
							
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

									$info_solicitante = Concursos::get_tramites_gestionados_datos($decifrado);

									$datos_json = json_decode($info_solicitante['datos'], true);

									$observacion = isset($info_solicitante['observacion']) ? $info_solicitante['observacion'] : '';
									$nombre_autor = isset($datos_json['nombre_autor']) ? $datos_json['nombre_autor'] : $_SESSION["nombre"] .' '.$_SESSION["apellido"] ;
									$institucion_autor = isset($datos_json['institucion_autor']) ? $datos_json['institucion_autor'] : '';
									$pais = isset($datos_json['pais']) ? $datos_json['pais'] : '';
									$documento_identidad = isset($datos_json['documento_identidad']) ? $datos_json['documento_identidad'] : '';
									$tipo_vinculo = isset($datos_json['tipo_vinculo']) ? $datos_json['tipo_vinculo'] : '';
									$telefono = isset($datos_json['telefono']) ? $datos_json['telefono'] : '';
									$correo = isset($datos_json['correo']) ? $datos_json['correo'] : '';
									$titulo_investigacion = isset($datos_json['titulo_investigacion']) ? $datos_json['titulo_investigacion'] : '';
									$anio_trabajo = isset($datos_json['anio_trabajo']) ? $datos_json['anio_trabajo'] : '';

									$agregar = 'NO';
									
								} else {
									
									$agregar = 'SI';

									$observacion = '';
									$nombre_autor = $_SESSION["nombre"] .' '.$_SESSION["apellido"] ;
									$institucion_autor = '';
									$pais = '';
									$documento_identidad = $_SESSION["cedula"];
									$tipo_vinculo =  '';
									$telefono = $_SESSION["telefono"];
									$correo = $_SESSION["email"];
									$titulo_investigacion =  '';
									$anio_trabajo =  '';

									
									$info_solicitante['solicitante'] = $_SESSION['usuario_id'];
								}
				      			?>
							
							<div class="container">
								<section class="proj-page-section" id="seccion_explicativo">

									<div class="proj-page-txt">
										
											<h4 class=" text-center">Inscripción al V Concurso de Investigación</h4>
									
										<p>Puede completar el cuadro de texto si desea hacer alguna observación a
											su solicitud.<br>
											En la siguiente sección, verá los documentos que se requieren adjuntar para
											presentar la inscripción de forma completa.<br>
										</p>
									</div>
								</section><!--.proj-page-section-->
							</div>

						
							<!-- formulario -->
							<div class="container-fluid">
								<form method="post" id="inscripcion_form">
								   <section class="proj-page-section" >
										<div class="row" id="datos_solicitante">
																						
												<?php
												require_once "../Formularios/formularioConcurso.php";
												?>
											
										</div>
												
										<section class="proj-page-section" id="bloque_curso">
											<div class="row" >
										    	<input type="hidden" id="idEncrypted" name="idEncrypted">
												<input type="hidden" id="tipo_solicitud" name="tipo_solicitud" value="CONCURSOS">
												<input type="hidden" id="tramite_code" name="tramite_code">
							
												<div class="col-xl-6">
													<div class="form-group">
														<label class="form-label" for="seccion_curso"
															style="text-align:left;margin:5px;">Concurso</label>
														<select class="form-control " id="seccion_curso" name="seccion_curso"
															data-placeholder="Seleccionar">
															<option label="Seleccionar"></option>
														</select>
													</div>
												</div>
											</div>
										</section><!--.proj-page-section-->
										
									</section>
			
									
								</form>
							</div><!--.container-fluid-->
							

							<!-- listado de documentos requeridos -->
							<section class="proj-page-section" id="seccion_adjuntar">
								<header class="proj-page-subtitle with-del">
									<h3>Documentos requeridos</h3>
								</header>
								<?php
								require_once "certificacionDocumento.php";
								?>
							</section><!--.proj-page-section-->

							<?php
							if (isset($_GET["ID"])) {
								?>
								<section class="proj-page-section" id="seccion_adjuntos">
									<header class="proj-page-subtitle with-del">
										<h3>Documentos adjuntos</h3>
									</header>
									<?php
									require_once "../../models/Movimiento.php";

									$id = rawurldecode($_GET['ID']);
									$id = str_replace(' ', '+', $id);
									$key = "mi_key_secret";
									$cipher = "aes-256-cbc";
									$iv_dec = substr(base64_decode($id), 0, openssl_cipher_iv_length($cipher));
									$cifradoSinIV = substr(base64_decode($id), openssl_cipher_iv_length($cipher));
									$decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);
									$decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);
									$tiposDocumentos = Movimiento::revisar_solicitud($decifrado);
									$permisos = "";
									$paso_comite = 0;
									$estado_actual = "";
									foreach ($tiposDocumentos as $key => $value) {
										$permisos = $value["permisos"];
										$paso_comite = $value["paso_comite"];
										$estado_actual = $value["estado_actual"];
										?>
										<div class="row">
											<div class="col-md-5">
												<div class="proj-page-attach">
													<i class="font-icon font-icon-doc"></i>
													<p class="name">
														<?php echo $value["tipo_doc"] ?>
													</p>
													<p class="date">
														<?php echo $value["hora_formato_doc"] . ", " . $value["fecha_formato_doc"] ?>
													</p>
													<p>
														<a href="<?php echo '../' . $value["documento"] ?>">Ver</a>
													</p>
												</div>
											</div>
										</div>
										<?php
									}
									?>
								</section><!--.proj-page-section-->
								<?php
							}
							?>
							
							
								<section class="proj-page-section">
									<label class="form-label semibold" for="observacion">Observación</label>
									<div class="summernote-theme-1">
										<textarea id="observacion" name="observacion" class="summernote descripcion"
										value="<?php echo htmlspecialchars($observacion); ?>"> </textarea>
									</div>
								</section>
							

						</section><!-- box-typical proj-page -->
						

					</div>

					<div class="col-xxl-3 col-lg-12 col-xl-4 col-md-4">
						<section class="box-typical proj-page">
					
							<section class="proj-page-section" id="btn_solicitante">
								<ul class="proj-page-actions-list">
									<li onclick="enviarSolicitud(11)" id="enviar_inscripcion_btn"><a><i
												class="font-icon font-icon-check-square"></i>Enviar inscripcion</a></li>
								</ul>
							</section><!--.proj-page-section-->

							<section class="proj-page-section" id="btn_administrativo">

								<ul class="proj-page-actions-list">
									<li onclick="aprobarSolicitud(3)" id="aprobar_inscripcion_btn"><a><i
												class="font-icon font-icon-check-square"></i>Aprobar</a></li>
									<li onclick="rechazarSolicitud(6)" id="rechazar_inscripcion_btn"><a class="cancelar"><i
												class="font-icon font-icon-close"></i>Rechazar</a></li>
									
								</ul>
								
							</section><!--.proj-page-section-->
							<section class="proj-page-section" >
								<ul class="proj-page-actions-list">
									<li>
										<a class="cancelar" id="cancelarInscripcion" id="cancelar_btn">
											<i class="font-icon font-icon-close"></i> Cancelar
										</a>
									</li>
								</ul>
							</section><!--.proj-page-section-->
						</section><!--.proj-page-->
					</div>
				</div><!--.row-->
			</div><!--.container-fluid-->
		</div><!--.page-content-->
		<!-- Contenido -->

		<?php require_once ("../MainJs/js.php"); ?>
		<script src="plugins/dropzone/dropzone.js"></script>
		<script src="plugins/js/multimediaFisica.js"></script>
		<script type="text/javascript" src="concursos.js?v=<?php echo time(); ?>"></script>
		<?php require_once ("../html/footer.php"); ?>

	</body>
	<script>
		function cancelarReturnPage() {
			var targetElement = document.getElementById("cancelarInscripcion");
			// Change its styles
			if (targetElement) {
				if ($("#tipo_solicitud").val() == 'CONCURSO') {
					targetElement.href = "listarConcursos.php";
				}
				else {
					targetElement.href = "listarConcursos.php";
				}

			}

		}
		
	</script>
	<?php

	if ($agregar == 'NO') {
		?>
		<script>
			
			document.getElementById('datos_solicitante').style.display = "block";
			document.getElementById('btn_solicitante').style.display = "none";
			document.getElementById('btn_administrativo').style.display = "none";
			document.getElementById('seccion_adjuntar').style.display = "none";
			document.getElementById('bloque_curso').style.display = "none";
			

		</script>
		<?php
	} else {
		?>
		<script>
		
			// console.log('entra ko');
			document.getElementById('datos_solicitante').style.display = "block";
			document.getElementById('btn_solicitante').style.display = "block";
			document.getElementById('btn_administrativo').style.display = "none";
			document.getElementById('seccion_explicativo').style.display = "block";
			document.getElementById('bloque_curso').style.display = "none";
			document.getElementById('seccion_adjuntar').style.display = "block";

			document.getElementById("cancelarInscripcion").href = "../Movimiento/administrarInscripciones.php";
			const paisSeleccionado = "<?php echo $pais; ?>";
			
		</script>
		<?php
	}
	?>
<script>
	       document.addEventListener("DOMContentLoaded", function() {
            // Inicializar Select2
            $('#pais').select2();

            // Ruta al archivo JSON
            const url = '../../json/paises.json'; // Asegúrate de que este archivo esté en la misma ubicación que el HTML

            // Obtener el valor seleccionado en PHP
            const paisSeleccionado = "<?php echo $pais; ?>";

            // Obtener el elemento select
            const selectPais = document.getElementById('pais');

            // Fetch para obtener los datos del archivo JSON
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    data.forEach(pais => {
                        const option = document.createElement('option');
                        option.value = pais.code;
                        option.textContent = `${pais.name} (${pais.code})`;

                        // Establecer el valor seleccionado
                        if (pais.code === paisSeleccionado) {
                            option.selected = true;
                        }

                        selectPais.appendChild(option);
                    });
                    // Actualizar Select2 después de agregar las opciones
                    $('#pais').trigger('change');
                })
                .catch(error => console.error('Error al cargar la lista de países:', error));
        });
			</script>
	</html>
	<?php
} else {
	header("Location:" . Conectar::ruta() . "index.php");
}
?>