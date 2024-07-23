<?php
  require_once("../../config/conexion.php"); 
  if(isset($_SESSION["usuario_id"])){ 
?>
<!DOCTYPE html>
<html>
    <?php require_once("../MainHead/head.php");?>
	<title>Sirepro::Datos Personales</title>
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
							<h3>Documentos Personales</h3>
							<ol class="breadcrumb breadcrumb-simple">
								<li><a href="../home/indexCurriculumVirtual.php">Currículum Virtual</a></li>
								<li class="active">Documentos Personales</li>
							</ol>
						</div>
						<div class="tbl-cell">
							<div class="col-lg-6"></div>
								<div class="col-lg-6">
									<fieldset class="form-group">
										<label class="form-label" for="btnfiltrar">&nbsp;</label>
										<a href= "nuevoDocsPersonales.php"><button class="btn btn-rounded btn-primary btn-block">Nuevo Documento</button></a>
									</fieldset>
								</div>
							</div>
						</div>
					</div>
				</div>
			</header>
			<div class="box-typical box-typical-padding">
				
				<div class="row">
					<div class="col-lg-3">
						<fieldset class="form-group">
							<label class="form-label" for="tipo_documento">Tipo Documento</label>
							<select class="select2" id="tipo_documento" name="tipo_documento" data-placeholder="Seleccionar">
								<option label="Seleccionar"></option>

							</select>
						</fieldset>
					</div>

					<div class="col-lg-3">
						<fieldset class="form-group">
							<label class="form-label" for="fecha">Fecha</label>
							<input type="date" placeholder="DD-MM-YYYY" class="form-control" id="fecha" name="fecha">
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
					<table id="datos_personales_data" class="table table-bordered table-striped table-vcenter js-dataTable-full">
						<thead>
							<tr>
								<th style="width: 5%;">Tipo de Documento</th>
								<th class="d-none d-sm-table-cell" style="width: 5%;">Fecha</th>
								<th class="d-none d-sm-table-cell" style="width: 1%;">Acciones</th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>

			</div>



			<!-- <div class="box-typical box-typical-padding">
				
				<div class="row">
					<div class="col-md-3">
						<div class="col-mb-3">
							<fieldset class="form-group">
								<label class="form-label" for="tipo_documento">Tipo de Documento</label>
								<select id="tipo_documento" name="tipo_documento" class="form-control select2">
							</fieldset>
						</div>

					</div>
					
					<div class="col-md-3">

						<div class="col-mb-3">
							<fieldset class="form-group">
								<label class="form-label" for="fecha">Fecha</label>
								<input type="date" placeholder="DD-MM-YYYY" class="form-control" id="fecha" name="fecha">

							</fieldset>
						</div>
					</div>
					
						
						
				</div>

				<div class="box-typical box-typical-padding" id="table">
					<table id="datos_personales_data" class="table table-bordered table-striped table-vcenter js-dataTable-full">
						<thead>
							<tr>
								<th style="width: 5%;">Tipo de Documento</th>
								
								<th class="d-none d-sm-table-cell" style="width: 5%;">Fecha</th>
								<th class="d-none d-sm-table-cell" style="width: 1%;">Acciones</th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>

			</div> -->

		</div>
	</div>
	<!-- Contenido -->

	<?php require_once("../MainJs/js.php");?>

	<script type="text/javascript" src="docsPersonales.js?v=<?php echo time();?>"></script>
	<?php require_once("../html/footer.php");?>

	<!-- <script type="text/javascript" src="../notificacion.js"></script> -->

</body>
</html>
<?php
  } else {
    header("Location:".Conectar::ruta()."index.php");
  }
?>