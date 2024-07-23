<?php
require_once ("../../config/conexion.php");
if (isset($_SESSION["usuario_id"])) {
	?>
	<!DOCTYPE html>
	<html>
	<?php require_once ("../MainHead/head.php"); ?>
	<link rel="stylesheet" href="plugins/dropzone/dropzone.css" type="text/css">


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
									<p>Verifique las observaciones hechas, tanto a cada documento como la observación
										general.
										El formulario podría tener datos cargados que no son válidos.<br>
										Favor, reemplazar por documentos válidos, según lo requerido.<br>
										Si desea completar la solicitud en otro momento, puede guardarla como borrador.
									</p>
								</div>
								<input type="hidden" id="idEncrypted" name="idEncrypted">
								<input type="hidden" id="tramite_code" name="tramite_code">
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
									<li id="guardarDocs" onclick="guardarDocsTramites(7)"><a><i
												class="font-icon font-icon-archive"></i>Guardar borrador</a></li>
									<li id="enviarSolicitud" onclick="enviarSolicitud(9)"><a href="#"><i
												class="font-icon font-icon-check-square"></i>Enviar modificaciones</a></li>
									<li><a class="cancelar" href="listarTramites.php"><i
												class="glyphicon glyphicon-remove"></i> Atrás</a></li>
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
		<script type="text/javascript" src="tramites.js?v=<?php echo time(); ?>"></script>
		<?php require_once ("../html/footer.php"); ?>

	</body>

	</html>
	<?php
} else {
	header("Location:" . Conectar::ruta() . "index.php");
}
?>