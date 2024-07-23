<?php
require_once ("../../config/conexion.php");
if (isset($_SESSION["usuario_id"])) {
	?>
	<!DOCTYPE html>
	<html>
	<?php require_once ("../MainHead/head.php"); ?>
	<title>Trámites</title>
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
									Revisión de solicitud
									<i class="font-icon font-icon-pencil"></i>
								</div>
								<div class="project">Trámite: <a href="#" class="tramite_nombre"></a></div>
							</section><!--.proj-page-section-->

							<!-- formulario -->
							<div id="form-container" class="container-fluid">
								<div class="row">
									<!-- Campos dinámicos del formulario se generarán aquí -->
								</div>
							</div>
							
							<div class="container-fluid">
								<div class="row">
									<form method="post" id="inscripcion_registro_form">
										<input type="hidden" id="idEncrypted" name="idEncrypted">
										<input type="hidden" id="tramite_code" name="tramite_code">
										<input type="hidden" id="area_id" name="area_id" value="<?php echo $_SESSION['area_id'] ?>">

										<section class="proj-page-section">
											<label class="form-label semibold" for="observacion_inscripcion">Observaciones del solicitante</label>
											<div class="summernote-theme-1">
												<textarea id="observacion_inscripcion" name="observacion_inscripcion" class="summernote observacion"
													name="name"></textarea>
											</div>
										</section>
										
									</form>
								</div><!--.row-->
							</div><!--.container-fluid-->

							<!-- listado de documentos requeridos -->
							<section class="proj-page-section">
								<header class="proj-page-subtitle with-del">
									<div class="row">
										<div class="col-md-6">
											<h3>Documentos presentados</h3>
										</div>
										<div class="col-md-5">
											<h3>Observación del documento</h3>
										</div>
									</div>

								</header>
								<div class="form-group" id="documentos_presentados">
									<input type="hidden" id="tramite_id">
									<!-- <div class="form-group"> -->
									<div class="row" style="margin-left:25px;">

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
											$tipo_solicitud = $value["tipo_solicitud"];
											$tramite_id  = $value["tramite_id"];
											$tramite_json_requisito = $value["tramite_json_requisito"];
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
															<a href="<?php echo '../' . htmlspecialchars($value["documento"]); ?>" target="_blank">Ver</a>
															<a href="<?php echo '../' . htmlspecialchars($value["documento"]); ?>" download>Descargar</a>
															
														</p>
													</div>
												</div>
												<div class="col-md-6">
													<select class="form-control estado_documento"
														id="estado_documento<?php echo $value["documento_id"] ?>"
														name="estado_documento" data-placeholder="Estado del documento">
														<option label="Observación del documento"></option>
														<?php
														$datos = Movimiento::get_estados_documentos_id();
														$html = "";

														if (is_array($datos) && count($datos) > 0) {
															foreach ($datos as $row) {
																$selected = ($row['estado_documento_id'] == $value["estado_doc_id"]) ? 'selected' : '';
																$html .= "<option value='" . $row['estado_documento_id'] . "' $selected>" . $row['estado_documento'] . "</option>";
															}
															echo $html;
														}
														?>
													</select>
												</div>
											</div>
											<?php
										}
										?>
									</div>
								</div>
							</section><!--.proj-page-section-->

							<section class="proj-page-section">
						
								
								<?php require_once "../Formularios/informeAdministracion.php"; ?>
							
							</section>

							<section class="proj-page-section">
								<label class="form-label semibold" for="observacion">Observaciones</label>
								<div class="summernote-theme-1">
									<textarea id="observacion" name="observacion" class="summernote observacion" name="name"></textarea>
								</div>
							</section>

						</section><!-- box-typical proj-page -->
					</div>

					<div class="col-xxl-3 col-lg-12 col-xl-4 col-md-4">
						<section class="box-typical proj-page">
							<section class="proj-page-section">
									<ul class="proj-page-actions-list">
										<?php
										$permisos = explode("-", $permisos);
										foreach ($permisos as $permiso) {
											if($estado_actual == "EN REVISIÓN"){
												if($permiso == "OA" && $paso_comite > 0){
													?>
													<li onclick="aprobarSolicitud(7, 1)" id="aprobar_para_comite"><a><i class="glyphicon glyphicon-check"></i>
															Aprobar para revisión del Comité</a></li>
													<?php
												}
												
											}
											
											elseif($estado_actual == "EN REVISIÓN DEL COMITÉ"){
												?>
												<li onclick="enviarObservaciones(9)" id="obs_comite"><a><i class="glyphicon glyphicon-send"></i> Enviar
												observaciones a Administración</a></li>
												<?php
											}
										}
										?>		
										<li onclick="aprobarSolicitud(3, 1)" id="aprobacion_final"><a><i class="glyphicon glyphicon-check"></i>
													Aprobación Final</a></li>					
										<li onclick="enviarObservaciones(4)" id="obs_administrativo"><a><i class="glyphicon glyphicon-send"></i> Enviar
												observaciones al solicitante</a></li>
										<li><a class="cancelar" href="listarSolicitudes.php"><i
													class="glyphicon glyphicon-remove"></i> Cancelar</a></li>
									</ul>
								</section><!--.proj-page-section-->
							</section><!--.proj-page-->
						</div>
					</div><!--.row-->
				</div><!--.container-fluid-->
			</div><!--.page-content-->
			<!-- Contenido -->

			<?php
				$jsonData = Movimiento::tramite_gestionado_jdato($decifrado);
			?>
			<script>
				// Obtener los datos JSON desde PHP
				var jsonData = <?php echo $jsonData; ?>;

				// Obtener el contenedor del formulario
				var formContainer = document.getElementById('form-container').querySelector('.row');

				// Función para formatear las etiquetas
				function formatLabel(key) {
					// Reemplazar los guiones bajos con espacios
					var formattedKey = key.replace(/_/g, ' ');
					// Convertir a mayúscula la primera letra de cada palabra y reemplazar "nio" por "ño"
					return formattedKey.replace(/\b\w/g, function (l) {
						return l.toUpperCase();
					}).replace(/nio/g, 'ño');
				}
				// Función para verificar si el valor es numérico
				function isNumeric(value) {
					return !isNaN(value) && !isNaN(parseFloat(value));
				}

				// Función para generar un campo de formulario
				function createFormField(key, value) {
					// Solo crear el campo si el valor no está vacío
					  // Solo crear el campo si el valor no está vacío y es una cadena
   
					if (!isNumeric(value) && value !== 'null' && value !== '') {
						// Crear el contenedor para la columna
						var fieldDiv = document.createElement('div');
						fieldDiv.className = 'col-md-3';
						
						// Crear el contenedor para el grupo del formulario
						var formGroup = document.createElement('div');
						formGroup.className = 'form-group';

						// Crear el label del campo
						var label = document.createElement('label');
						label.className = 'form-label';
						// Formatear la clave antes de asignarla al label
						label.textContent = formatLabel(key);

						// Crear el input del campo
						var input = document.createElement('input');
						input.type = 'text';
						input.className = 'form-control';
						input.name = key;
						input.value = value;
						input.disabled = true; // Deshabilitar el campo

						// Añadir el label y el input al grupo del formulario
						formGroup.appendChild(label);
						formGroup.appendChild(input);
						// Añadir el grupo del formulario a la columna
						fieldDiv.appendChild(formGroup);
						// Añadir la columna al contenedor del formulario
						formContainer.appendChild(fieldDiv);
					}
				}

				// Iterar sobre las claves y valores del JSON para generar los campos del formulario
				for (var key in jsonData) {
					if (jsonData.hasOwnProperty(key)) {
						// Crear un campo de formulario para cada clave y valor
						createFormField(key, jsonData[key]);
					}
				}
			</script>
			<?php require_once ("../MainJs/js.php"); ?>
			<script type="text/javascript" src="movimientos.js?v=<?php echo time(); ?>"></script>
			<?php require_once ("../html/footer.php"); ?>

		</body>
		</html>
		<?php

		
} else {
	header("Location:" . Conectar::ruta() . "index.php");
}
?>
