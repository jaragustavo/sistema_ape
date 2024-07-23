<?php
	$tipoDoc = 'P';
	require_once("../../config/conexion.php"); 
	if(isset($_SESSION["usuario_id"])){ 
	
?>
<!DOCTYPE html>
<html>
		<?php require_once("../MainHead/head.php");?>
		<title>Documentos Personales</title>
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
							<h3>Editar Documento</h3>
							<ol class="breadcrumb breadcrumb-simple">
								<li><a href="listarDocsPersonales.php">Documentos Personales</a></li>
								<li class="active">Editar Documento</li>
							</ol>
						</div>
					</div>
				</div>
			</header>

			<div class="box-typical box-typical-padding">

				<!-- <h5 class="m-t-lg with-border">Ingresar Información</h5> -->

				<div class="row">
					<form method="post" id="dato_personal_form">

						<input type="hidden" id="usuario_id" name="usuario_id" value="<?php echo $_SESSION["usuario_id"] ?>">
						<input type="hidden" id="idEncrypted" name="idEncrypted">
						<div class="col-lg-6">
							<fieldset class="form-group">
								<label class="form-label semibold" for="tipo_documento">Tipo Documento</label>
								<select id="tipo_documento" name="tipo_documento" class="form-control select2" >
							</fieldset>
						</div>

						<div class="col-lg-6">
							<fieldset class="form-group">
								<label class="form-label semibold" for="x">Tipo Documento</label>
								<select id="x" name="x" class="form-control select2">
							</fieldset>
						</div>
						<div class="col-lg-4">
							<fieldset class="form-group">
								<label class="form-label semibold" for="fecha">Fecha</label>
								<input type="date" placeholder="DD-MM-YYYY" class="form-control" id="fecha" name="fecha">

								</select>
							</fieldset>
						</div>
						<div class="col-lg-6">
							<fieldset class="form-group">
								<label class="form-label semibold" for="documento">Documento</label>
                                    <div class="el-element-overlay">
                                        <div class="el-card-item">
                                            <div class="el-card-avatar el-overlay-1"> 
                                                <embed id="imagenmuestra" name="imagenmuestra" class="previsualizar" width="400px" title="Imagen del artículo">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="waves-effect waves-light"><span>Subir una imagen</span>
                                        <input type="file" class="nuevaImagen" name="imagen" id="imagen">
                                        <input type="hidden" name="imagenactual" id="imagenactual">
                                        <p class="help-block">Peso máximo 2MB</p>
                                    </div>       
							</fieldset>
						</div>

						<!-- Inicio subir imagen -->    
						

						<div class="col-lg-12">
							<fieldset class="form-group">
								<label class="form-label semibold" for="dato_adic">Datos Adicionales</label>
								<div class="summernote-theme-1">
									<textarea id="dato_adic" name="dato_adic" class="summernote" name="name"></textarea>
								</div>
							</fieldset>
						</div>
						<div class="col-lg-12">
							<!-- <input type="hidden" name="enviar" value="si"> -->
							<button type="submit" name="action" value="add" class="btn btn-rounded btn-primary">Guardar</button>
							<a href="listarDocsPersonales.php"><button type="button" name="cancel" class="btn btn-rounded btn-secondary">Cancelar</button></a>
						</div>
					</form>
				</div>

			</div>
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