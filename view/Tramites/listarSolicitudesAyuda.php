<?php
  require_once("../../config/conexion.php"); 
  if(isset($_SESSION["usuario_id"])){ 
?>
<!DOCTYPE html>
<html>
    <?php require_once("../MainHead/head.php");?>
	<title>APE::Ayuda</title>
</head>
<body class="with-side-menu">

    <?php require_once("../MainHeader/header.php");?>

    <div class="mobile-menu-left-overlay"></div>
    
    <?php require_once("../MainNav/nav.php");?>

	<!-- Contenido -->
	<div class="page-content">
		<div class="container-fluid">

			<header class="section-header">
				<div class="tbl">
					<div class="tbl-row">
						<div class="tbl-cell">
							<h3>Solicitudes de Ayuda</h3>
							<ol class="breadcrumb breadcrumb-simple">
							<li><a href="../home/">Trámites en línea</a></li>
								<li class="active">Solicitudes de Ayuda</li>
							</ol>
						</div>
					</div>
				</div>
			</header>

			<div class="box-typical box-typical-padding">
				<!-- opciones de trámites para solicitar -->
				<div class="row" style="margin-bottom:50px;">
					<div class="col-lg-6">
						<fieldset class="form-group">
							<label class="form-label" for="ayuda_nueva">Solicitar ayuda</label>
							<select class="select2" id="ayuda_nueva" name="ayuda_nueva" data-placeholder="Seleccionar">
								<option label="Seleccionar"></option>

							</select>
						</fieldset>
					</div>

					<div class="col-lg-2">
						<fieldset class="form-group">
							<label class="form-label" for="btnnuevo">&nbsp;</label>
							<button type="button" class="btn btn-rounded btn-success btn-block" onclick="abrirNuevaAyuda()" id="btnnuevo">Nuevo Trámite</button>
						</fieldset>
					</div>
				</div>	

				<div class="row">
					<div class="col-lg-4">
						<fieldset class="form-group">
							<label class="form-label" for="tramite">Tipo de trámite</label>
							<select class="select2" id="tramite" name="tramite" data-placeholder="Seleccionar">
								<option label="Seleccionar"></option>

							</select>
						</fieldset>
					</div>
					<div class="col-lg-3">
						<fieldset class="form-group">
							<label class="form-label" for="estado_tramite">Estado del trámite</label>
							<select class="select2" id="estado_tramite" name="estado_tramite" data-placeholder="Seleccionar">
								<option label="Seleccionar"></option>

							</select>
						</fieldset>
					</div>

					<div class="col-lg-2">
						<fieldset class="form-group">
							<label class="form-label" for="btnfiltrar">&nbsp;</label>
							<button type="submit" class="btn btn-rounded btn-primary btn-block" id="btnfiltrar">Filtrar</button>
						</fieldset>
					</div>

					<div class="col-lg-2">
						<fieldset class="form-group">
							<label class="form-label" for="btntodo">&nbsp;</label>
							<button class="btn btn-rounded btn-primary btn-block" id="btntodo">Ver Todo</button>
						</fieldset>
					</div>
				</div>

				<div class="box-typical box-typical-padding" id="table">
					<table id="ayuda_data" class="table table-bordered table-striped table-vcenter js-dataTable-full">
						<thead>
							<tr>
								<th style="width: 5%;">Tipo de trámite</th>
								<th style="width: 5%;">Fecha de solicitud</th>
								<th class="d-none d-sm-table-cell" style="width: 10%">Estado actual</th>
								<th class="d-none d-sm-table-cell" style="width: 15%">Avance</th>
								<th class="d-none d-sm-table-cell" style="width: 7%;">Último movimiento</th>
								<th class="d-none d-sm-table-cell" style="width: 5%;">Acciones</th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>

			</div>

		</div>
	</div>
	<!-- Contenido -->

	<?php require_once("../MainJs/js.php");?>

	<script type="text/javascript" src="tramites.js?v=<?php echo time();?>"></script>
	<script src="plugins/dropzone/dropzone.js"></script>

	<?php require_once("../html/footer.php");?>

	<!-- <script type="text/javascript" src="../notificacion.js"></script> -->

</body>
</html>
<?php
  } else {
    header("Location:".Conectar::ruta()."index.php");
  }
?>