<?php
  require_once("../../config/conexion.php"); 
  if(isset($_SESSION["usuario_id"])){ 
?>
<!DOCTYPE html>
<html>
    <?php require_once("../MainHead/head.php");?>
	<title>Sirepro::Movimientos</title>
</head>
<style>
    .inline-label {
        display: inline-block;
    }
</style>
<body class="with-side-menu">

    <?php require_once("../MainHeader/header.php");?>

    <div class="mobile-menu-left-overlay"></div>
    
    <?php require_once("../MainNav/nav.php");?>

	<!-- Contenido -->
	<div class="page-content">
		<div class="container-fluid">

			<header class="section-header" style="height:10% !important;">
				<div class="tbl">
					<div class="tbl-row">
						<div class="tbl-cell">
							<h4>Movimientos del Trámite</h4>
							<ol class="breadcrumb breadcrumb-simple">
								<li><a href="listarTramites.php">Listado de Trámites</a></li>
								<li class="active">Movimientos</li>
							</ol>
						</div>
					</div>
				</div>
			</header>
			<section class="card card-blue">
				<header class="card-header">
					Trámite Gestionado: 
					<label class="card-text inline-label" style="color:#444444;" id="nombre_tramite"></label>
				</header>
				<div class="row" style="font-size: 14px;">
					<div class="col-md-4">
						<div class="card-block">
							<div class="project"><b>Solicitante: </b> <label class="card-text inline-label" id="usuario_solicitante"></label></div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="card-block">
							<div class="project"><b>Fecha solicitud: </b> <label class="card-text inline-label" id="fecha_hora_crea"></label></div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="card-block">
							<div class="project"><b>Estado actual: </b> <label class="card-text inline-label" id="estado_actual"></label></div>
						</div>
					</div>
				</div>
				<div class="row" style="font-size: 14px;">
					<div class="col-md-4">
						<div class="card-block">
							<div class="project"><b>Último movimiento: </b> <label class="card-text inline-label" id="fecha_ultimo_mov"></label></div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="card-block">
							<div class="project"><b>Usuario Asignado: </b> <label class="card-text inline-label" id="usuario_asignado"></label></div>
						</div>
					</div>
				</div>
			</section>
			<div class="box-typical box-typical-padding">
				<div class="box-typical box-typical-padding" id="table">
					<table id="movimientos_tramite_data" class="table table-bordered table-striped table-vcenter js-dataTable-full">
						<thead>
                        <tr>
								<th style="width: 5%;">Fecha y hora</th>
								<th class="d-none d-sm-table-cell" style="width: 5%">Área asignada</th>
								<th class="d-none d-sm-table-cell" style="width: 10%;">Usuario</th>
								<th class="d-none d-sm-table-cell" style="width: 10%;">Estado del trámite</th>
								<th style="width: 5%;">Tiempo en este estado</th>
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

	<script type="text/javascript" src="consultas.js?v=<?php echo time();?>"></script>
	<?php require_once("../html/footer.php");?>

	<!-- <script type="text/javascript" src="../notificacion.js"></script> -->

</body>
</html>
<?php
  } else {
    header("Location:".Conectar::ruta()."index.php");
  }
?>