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
									Solicitud de Inscripción
									<i class="font-icon font-icon-pencil"></i>
								</div>
								<?php
								require_once "../../models/Certificacion.php";
								require_once "../../models/Movimiento.php";
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
									$info_solicitante = Movimiento::get_info_solicitud($decifrado);
								} else {
									$info_solicitante['solicitante'] = $_SESSION['usuario_id'];
								}

								?>
							</section><!--.proj-page-section-->

							<section class="proj-page-section" id="seccion_explicativo">

								<div class="proj-page-txt">
									<header class="proj-page-subtitle">
										<h3 class="tramite_nombre"></h3>
									</header>
									<p>Puede completar el cuadro de texto si desea hacer alguna observación a
										su solicitud.<br>
										En la siguiente sección, verá los documentos que se requieren adjuntar para
										presentar la inscripción de forma completa.<br>
									</p>
								</div>
							</section><!--.proj-page-section-->

							<div class="container-fluid" id="datos_solicitante">
								<section class="card card-orange">
									<header class="card-header">
										Datos del Solicitante
										<label class="card-text inline-label" style="color:#444444;"
											id="nombre_tramite"></label>
									</header>

									<input type="hidden" id="idEncrypted" value=<?php echo $id_solicitud ?>>
									<div class="row" style="font-size: 15px;">
										<div class="col-md-4">
											<div class="card-block">
												<div class="project"><b>Solicitante: </b> <label
														class="card-text inline-label"
														id="nombre_apellido"><?php echo $info_solicitante["nombre_apellido"] ?></label>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="card-block">
												<div class="project"><b>Correo: </b> <label class="card-text inline-label"
														id="email"><?php echo $info_solicitante["email"] ?></label>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="card-block">
												<div class="project"><b>Documento de Identidad: </b> <label
														class="card-text inline-label"
														id="documento_identidad"><?php echo $info_solicitante["documento_identidad"] ?></label>
												</div>
											</div>
										</div>
									</div>
									<div style="margin-left:3%;">
										<div class="project"><b>Curso solicitado: </b> <label
												class="card-text inline-label"><?php echo $info_solicitante["nombre_curso"] ?></label>
										</div>
										<div class="project"><b>Observación: </b></label>
											<?php echo $info_solicitante["observacion"] ?>
										</div>
									</div>

								</section>
							</div>

							<!-- formulario -->
							<div class="container-fluid">
								<form method="post" id="inscripcion_form">
									<div class="row">
										<div class="col-xl-6">
											<div class="form-group">
												<label class="form-label" for="seccion_curso"
													style="text-align:left;margin:5px;">Curso</label>
												<select class="form-control " id="seccion_curso" name="seccion_curso"
													data-placeholder="Seleccionar">
													<option label="Seleccionar"></option>
												</select>
											</div>
										</div>
									</div>
									<div class="row">
										<input type="hidden" id="idEncrypted" name="idEncrypted">
										<input type="hidden" id="tipo_solicitud" name="tipo_solicitud">
										<input type="hidden" id="user_logged" name="user_logged">
										<input type="hidden" id="tramite_code" name="tramite_code">
										<section class="proj-page-section">
											<label class="form-label semibold" for="observacion">Observación</label>
											<div class="summernote-theme-1">
												<textarea id="observacion" name="observacion" class="summernote descripcion"
													name="name"></textarea>
											</div>
										</section>
									</div><!--.row-->
								</form>
							</div><!--.container-fluid-->
							<section class="proj-page-section" id="seccion_explicativo">

								<div class="proj-page-txt">
									
									<p>
										Descargue de aquí el formulario de inscripción. Luego de imprimirlo, completarlo y escanearlo,
										debe adjuntarlo en el campo que se indica en la siguiente sección.
									</p>
									<div class="row">
											<div class="col-md-5">
												<div class="proj-page-attach">
													<i class="font-icon font-icon-doc"></i>
													<p class="name">
														Formularios
													</p>
													<p>
														<a href="<?php echo $root_path_assets ?>docs/ape/formularios certificación.pdf"
														target="_blank">Ver</a>
													</p>
												</div>
											</div>
										</div>
								</div>
							</section><!--.proj-page-section-->

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

						</section><!-- box-typical proj-page -->
					</div>

					<div class="col-xxl-3 col-lg-12 col-xl-4 col-md-4">
						<section class="box-typical proj-page">
							<!-- <section class="proj-page-section proj-page-time-info">
								<div class="tbl">
									<div class="tbl-row">
										<div class="tbl-cell">Duración del curso:
										</div>
										<div class="tbl-cell tbl-cell-time">4 semanas
										</div>
									</div>
								</div>
							</section> -->
							<section class="proj-page-section" id="btn_solicitante">
								<ul class="proj-page-actions-list">
									<li onclick="enviarSolicitud(11)" id="enviar_inscripcion_btn"><a><i
												class="font-icon font-icon-check-square"></i>Enviar inscripcion</a></li>
									<li><a class="cancelar" id="cancelarInscripcion" id="cancelar_btn"><i
												class="glyphicon glyphicon-trash"></i> Cancelar</a></li>
								</ul>
							</section><!--.proj-page-section-->

							<section class="proj-page-section" id="btn_administrativo">
								<ul class="proj-page-actions-list">
									<li onclick="aprobarSolicitud(3)" id="aprobar_inscripcion_btn"><a><i
												class="font-icon font-icon-check-square"></i>Aprobar</a></li>
									<li onclick="rechazarSolicitud(6)" id="rechazar_inscripcion_btn"><a class="cancelar"><i
												class="font-icon font-icon-close"></i>Rechazar</a></li>
									<li><a class="cancelar" id="cancelarInscripcion" id="cancelar_btn"><i
												class="glyphicon glyphicon-trash"></i> Cancelar</a></li>
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
		<script type="text/javascript" src="certificaciones.js?v=<?php echo time(); ?>"></script>
		<?php require_once ("../html/footer.php"); ?>

	</body>
	<script>
		function cancelarReturnPage() {
			var targetElement = document.getElementById("cancelarInscripcion");
			// Change its styles
			if (targetElement) {
				if ($("#tipo_solicitud").val() == 'CERT') {
					targetElement.href = "listarCertificacionesDisponibles.php";
				}
				else {
					targetElement.href = "listarCursosDisponibles.php";
				}

			}

		}
	</script>
	<?php

	if ($_SESSION['usuario_id'] == $info_solicitante['solicitante']) {
		?>
		<script>
			document.getElementById('datos_solicitante').style.display = "none";
			document.getElementById('btn_solicitante').style.display = "block";
			document.getElementById('btn_administrativo').style.display = "none";
		</script>
		<?php
	} else {
		?>
		<script>
			// console.log('entra ko');

			document.getElementById('enviar_inscripcion_btn').style.display = "none";
			document.getElementById('seccion_explicativo').style.display = "none";
			document.getElementById('seccion_adjuntar').style.display = "none";
			document.getElementById('inscripcion_form').style.display = "none";
			document.getElementById('btn_solicitante').style.display = "none";
			document.getElementById('btn_administrativo').style.display = "block";
			document.getElementById("cancelarInscripcion").href = "../Movimiento/administrarInscripciones.php";
		</script>
		<?php
	}
	?>

	</html>
	<?php
} else {
	header("Location:" . Conectar::ruta() . "index.php");
}
?>