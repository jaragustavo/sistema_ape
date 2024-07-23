<?php
require_once("../../config/conexion.php");
if (isset($_SESSION["usuario_id"])) {
	?>
	<!DOCTYPE html>
	<html>
	<?php require_once("../MainHead/head.php"); ?>
	<link rel="stylesheet" href="plugins/dropzone/dropzone.css" type="text/css">


	<title>Trámites</title>
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

		<?php require_once("../MainHeader/header.php"); ?>

		<div class="mobile-menu-left-overlay"></div>

		<?php require_once("../MainNav/nav.php"); ?>

		<!-- Contenido -->
		<div class="page-content">
			<div class="container-fluid">
				<form method="post" id="inscripcion_form">
					<div class="row">
						<div class="col-xxl-9 col-lg-12 col-xl-8 col-md-8">
							<section class="box-typical proj-page">
								<section class="proj-page-section proj-page-header">
									<div class="title">
										Revisión de observaciones
										<i class="font-icon font-icon-pencil"></i>
									</div>
									<div class="project">Trámite: <a href="#" class="tramite_nombre"></a></div>
								</section><!--.proj-page-section-->
								<section class="proj-page-section">

									<div class="proj-page-txt">
										<header class="proj-page-subtitle">
											<h3 class="tramite_nombre"></h3>
										</header>

										<?php
												require_once "../../models/Tramite.php";
												$key="mi_key_secret";
												$cipher="aes-256-cbc";
												$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
												$id = rawurldecode($_GET['ID']);
												$id = str_replace(' ', '+', $id);
												$idEncrypted = $id;
												$iv_dec = substr(base64_decode($id), 0, openssl_cipher_iv_length($cipher));
												$cifradoSinIV = substr(base64_decode($id), openssl_cipher_iv_length($cipher));
												$decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);
												error_log($decifrado);
												$observacion = Tramite::get_observacion_tramite($decifrado);
												if ($observacion) {
													// Asegúrate de que la observación esté en el array devuelto
													if (isset($observacion['observacion_evaluador'])) {
														$observacion_text = $observacion['observacion_evaluador'];
													} else {
														error_log('La observación no está presente en la fila devuelta.');
													}
												} else {
													error_log('No se encontró ninguna observación para el ID proporcionado.');
												}
											
											
											?>

										<p>Verifique las observaciones hechas, tanto a cada documento como la observación
											general.
											El formulario podría tener datos cargados que no son válidos.<br>
											Favor, reemplazar por documentos válidos, según lo requerido.<br>
											Si desea completar la solicitud en otro momento, puede guardarla como borrador.<br>
											
										</p>
										
									</div>

									<div class="title">
									    Observacion del Fiscalizador
										
									</div>

									<p style="color: lightcoral; font-size: smaller;">
										<?php echo $observacion_text; ?>
									</p>

									<input type="hidden" id="idEncrypted" name="idEncrypted" value="<?php echo $idEncrypted ?>">
									<input type="hidden" id="tramite_code" name="tramite_code">
									<input type="hidden" id="tipo_solicitud" name="tipo_solicitud" value="CERT">

									<!-- listado de documentos requeridos -->
									<section class="proj-page-section">
										<header class="proj-page-subtitle with-del">
											<div class="row">
												<div class="col-md-4">
													<h3>Documentos presentados</h3>
												</div>
												<div class="col-md-4">
													<h3>Observación del documento</h3>
												</div>
												
											</div>

										</header>
										<div class="form-group" id="documentos_presentados">
											<input type="hidden" id="tramite_id">
											<!-- <div class="form-group"> -->
											<div class="row" style="margin-left:25px;">

												<?php
												require_once "../../models/Tramite.php";
												$key="mi_key_secret";
												$cipher="aes-256-cbc";
												$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
												$id = rawurldecode($_GET['ID']);
												$id = str_replace(' ', '+', $id);
												$iv_dec = substr(base64_decode($id), 0, openssl_cipher_iv_length($cipher));
												$cifradoSinIV = substr(base64_decode($id), openssl_cipher_iv_length($cipher));
												$decifrado = openssl_decrypt($cifradoSinIV, $cipher, $key, OPENSSL_RAW_DATA, $iv_dec);
												$tiposDocumentos = Tramite::get_docs_x_tramite_gestionado($decifrado);
												foreach ($tiposDocumentos as $key => $value) {
													?>
													<div class="row">
														<div class="col-md-4" onclick="cargarIdDoc(this.id)"
															id="<?php echo $value["tipo_doc_id"] ?>">
															<div class="row" style="width:80%;">
																<div class="form-group agregarMultimedia">
																	<b style="font-size:14px;color:#1e4568;">
																		<?php echo $value["tipo_doc"] ?>
																	</b>
																	<a href="<?php echo '../' . $value["documento"] ?>" target="_blank">Ver</a>
																	<div class="multimediaFisica needsclick dz-clickable">
																		<div class="dz-message needsclick">
																			Arrastrar o dar click para subir imagenes.
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-md-4">
															<input type="text" class="form-control"
																style="font-size: 13px; margin-top:20px;"
																value="<?php echo $value['estado_doc_nombre'] ?>" disabled>

														</div>
													</div>
													<p>
														
													</p>
													<?php
												}
												?>
											</div>
										</div>
									</section><!--.proj-page-section-->
									<section class="proj-page-section">
										<label class="form-label semibold" for="observacion">Observaciones de su
											solicitud</label>
										<div class="summernote-theme-1">
											<textarea id="observacion" name="observacion" class="summernote"
												name="name"></textarea>
										</div>
									</section>
								</section><!-- box-typical proj-page -->
						</div>

						<div class="col-xxl-3 col-lg-12 col-xl-4 col-md-4">
							<section class="box-typical proj-page">

								<section class="proj-page-section">
									<ul class="proj-page-actions-list">
										<!-- <li id="guardarDocs" onclick="guardarSolicitud(7)"><a><i
													class="font-icon font-icon-archive"></i>Guardar borrador</a></li> -->
										<li id="enviarSolicitud" onclick="enviarSolicitud(9)"><a href="#"><i
													class="font-icon font-icon-check-square"></i>Enviar modificaciones</a></li>
										<li><a class="cancelar" href="listarCertificacionesDisponibles.php"><i
											class="glyphicon glyphicon-remove"></i> Atrás</a></li>
									</ul>
								</section><!--.proj-page-section-->
							</section><!--.proj-page-->
						</div>
					</div><!--.row-->
				</form>	
			</div><!--.container-fluid-->
		</div><!--.page-content-->
		<!-- Contenido -->

		<?php require_once("../MainJs/js.php"); ?>
		<script src="plugins/dropzone/dropzone.js"></script>
		<script src="plugins/js/multimediaFisica.js"></script> 
		<script type="text/javascript" src="certificaciones.js?v=<?php echo time(); ?>"></script>
		<?php require_once("../html/footer.php"); ?>

	</body>

	</html>
	<?php
} else {
	header("Location:" . Conectar::ruta() . "index.php");
}
?>