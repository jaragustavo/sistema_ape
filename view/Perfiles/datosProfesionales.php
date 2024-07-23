<style>

    /* Estilo para el ícono de agregar trabajo */
    .glyphicon.agregar-trabajo {
        color: #8DC26F; /* Color verde claro */
        font-size: large;
        padding: 10px 5px 0px 0px !important;
        margin: 3px;
        cursor: pointer; /* Cambia el cursor a una manita */
    }

   /* Estilo cuando el mouse está encima del ícono */
    .glyphicon.agregar-trabajo:hover {
        color: #5A904E !important; /* Azul oscuro cuando se pasa el mouse */
    }
   /* Estilo base para el ícono de eliminar fila */
    .glyphicon.eliminar-fila {
        float: left;
        color: #e06666;
        font-size: large;
        padding: 10px 0px 0px 5px;
        margin: 3px;
        cursor: pointer; /* Cambia el cursor a una manita */
    }

    /* Estilo cuando el mouse está encima del ícono de eliminar fila */
    .glyphicon.eliminar-fila:hover {
         color: #8B0000 !important; /* Azul oscuro cuando se pasa el mouse */
    }


    .el-card-avatar {
    position: relative;
    width: 150%;
    height: 150px; /* Fija la altura del contenedor de la imagen */
    overflow: hidden;
    display: flex;
    justify-content: center;
    align-items: center;
    }

    .el-card-avatar img, .el-card-avatar iframe {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain; /* Mantiene la relación de aspecto de la imagen */
    }

    .waves-effect {
        margin-top: 20px; /* Ajusta el margen superior para separar el botón de la imagen */
        display: flex;
        justify-content: center; /* Centra el botón horizontalmente */
    }




    .el-card-avatar {
        position: relative;
        width: 100%;
        height: 100px; /* Fija la altura del contenedor de la imagen */
        overflow: hidden;
        display: flex;
      
    }

    .el-card-avatar img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain; /* Mantiene la relación de aspecto de la imagen */
    }

    .waves-effect {
        margin-top: 5px; /* Ajusta el margen superior para separar el botón de la imagen */
        display: flex;
        
    }
</style>
<?php
require_once ("../../config/conexion.php");

if (isset($_SESSION["usuario_id"])) {

     ?>
    <!DOCTYPE html>
    <html>
    <?php require_once ("../MainHead/head.php"); ?>


    <title>Datos Profesionales</title>
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
                                    Mis datos profesionales
                                </div>
                            </section><!-- .proj-page-section -->

                            <?php
                                require_once ("../../config/conexion.php");

                                $imagenes_perfil = Publicacion::get_imagenes_perfil($_SESSION["usuario_id"]);
                                $foto_registro_profesional = $imagenes_perfil['foto_registro_profesional'];

                                $esImagen = false;
                                $esPDF = false;

                                if ($foto_registro_profesional != null && $foto_registro_profesional != '') {
                                    $extension = pathinfo($foto_registro_profesional, PATHINFO_EXTENSION);
                                    if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png'])) {
                                        $esImagen = true;
                                    } elseif (strtolower($extension) == 'pdf') {
                                        $esPDF = true;
                                    }
                                } else {
                                    $foto_registro_profesional = "assets/assets-main/images/icons/user2.png";
                                    $esImagen = true; // Para mostrar la imagen predeterminada
                                }
                            ?>
                             
                            <!-- Formulario -->
                            <div class="container-fluid">
                                <div class="row">
                                    <form method="post" id="datos_profesionales_form">
                                            <section class="proj-page-section" id="formulario">
                                                <!-- Sección de Datos Personales -->
                                                <div class="row">
                                                    <div class="col-md-7">
                                                       <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="profesion_id">Profesión</label>
                                                                    <select class="form-control" id="profesion_id" name="profesion_id" data-placeholder="Seleccionar">
                                                                        <option value="" selected>Seleccionar</option>
                                                                        <!-- Opciones de profesión -->
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <div class="form-group">
                                                                    <label for="lugar_egreso">Lugar de Egreso</label>
                                                                    <input type="text" class="form-control" id="lugar_egreso" name="lugar_egreso" placeholder="Lugar o Institución de egreso">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="anio_egreso">Año Egreso</label>
                                                                    <input type="number" class="form-control" id="anio_egreso" name="anio_egreso" placeholder="Año de Egreso">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-5">
                                                        <div class="col-lg-12">
                                                            <fieldset class="form-group text-center"> <!-- Clase text-center para centrar el contenido -->
                                                                <label class="form-label semibold" for="documento">Registro Profesional </label>
                                                                <p class="help-block text-small-red" style="font-size: 12px; color: red;">Ambos lados</p>
                                                                <div class="el-element-overlay">
                                                                    <div class="el-card-item">
                                                                        <div class="el-card-avatar el-overlay-1 rectangular-preview">
                                                                            <?php if ($esImagen) { ?>
                                                                                <img id="imagenmuestra" name="imagenmuestra" class="previsualizar" title="Imagen de la cedula"
                                                                                    src="../<?php echo $foto_registro_profesional; ?>" alt="Imagen Registro Profesional.">
                                                                            <?php } elseif ($esPDF) { ?>
                                                                                <iframe id="pdfmuestra" name="pdfmuestra" class="previsualizar" src="../<?php echo $foto_registro_profesional; ?>" style="display: block;" width="100%" height="300px"></iframe>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="waves-effect waves-light">
                                                                    <label for="imagen" class="custom-file-upload">Seleccionar Documento</label>
                                                                    <input type="file" class="nuevaImagen" name="imagen" id="imagen" onchange="guardarFotoRegistro()">
                                                                    <input type="hidden" name="imagenactual" id="imagenactual">
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                    </div>

                                                </div>
                                       
                                                <div id="trabajo-container">
                                                    <div class="row trabajo-row col-12">
                                                        <div class="col-md-6" >
                                                            <div class="form-group">
                                                                <label for="lugar_trabajo">Lugar de Trabajo</label>
                                                                <select class="form-control" name="lugar_trabajo[]">
                                                                    <!-- Opciones se cargarán dinámicamente -->
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="tipo_contrato">Contrato</label>
                                                                <select class="form-control" name="tipo_contrato[]">
                                                                    <option value=""></option>
                                                                    <option value="PER">Permanente</option>
                                                                    <option value="CON">Contratado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label for="vinculo">Vínculo</label>
                                                                <select class="form-control" name="vinculo[]">
                                                                    <option value=""></option>
                                                                    <option value="1">1</option>
                                                                    <option value="2">2</option>
                                                                    <option value="3">3</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1"  style="padding: 20px 15px 10px 15px !important">
                                                            <i class="glyphicon glyphicon-plus agregar-trabajo"
                                                            style="float:left;color:#8DC26F; font-size:large; padding: 10px 0px 15px 10px; margin: 0px; cursor: pointer;" aria-hidden="true" title="Agregar lugar de trabajo"></i>
                                                        </div>

                                                    </div>
                                                </div>
                                        </section><!-- .box-typical.steps-icon-block -->

                                        <!-- Datos Post Grado -->
                                        <div class="container-fluid">
                                            <div class="row">
                                                <section class="proj-page-section proj-page-header">
                                                    <div class="title">
                                                        Post Grados
                                                    </div>
                                                </section><!-- .proj-page-section -->

                                                <section class="proj-page-section">
                                                    <div id="estudio-container">
                                                        <div class="row estudio-row col-12">
                                                             <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="titulo">Titulo</label>
                                                                    <select class="form-control" name="titulo[]">
                                                                        <option value=""></option>
                                                                        <option value="Doctorado">Doctorado</option>
                                                                        <option value="Masterado">Masterado</option>
                                                                        <option value="Diplomado">Diplomado</option>
                                                                        <option value="Otro">Otro</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="titulo_descripcion">Descripción</label>
                                                                    <input type="text" class="form-control" id="titulo_descripcion[]" name="titulo_descripcion[]" placeholder="Descripcion">
                                                                </div>
                                                            </div>
    
                                                            <div class="col-md-1"  style="padding: 20px 0px 10px 15px !important">
                                                                <i class="glyphicon glyphicon-plus agregar-estudio"
                                                                style="float:left;color:#8DC26F; font-size:large;  padding: 10px 0px 10px 10px; margin: 0px; cursor: pointer;" aria-hidden="true" title="Agregar Post Grado"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </section><!-- .box-typical.steps-icon-block -->
                                            </div><!-- .row -->
                                        </div><!-- .container-fluid -->

                                    </form>
                                </div><!-- .row -->
                            </div><!-- .container-fluid -->
                        </section><!-- .box-typical.proj-page -->
                    </div><!-- .col-xxl-9.col-lg-12.col-xl-8.col-md-8 -->

                    <div class="col-xxl-3 col-lg-12 col-xl-4 col-md-4">
                        <section class="box-typical proj-page">

                            <section class="proj-page-section">
                                <ul class="proj-page-actions-list">
                                    <li onclick="guardarDatosProfesionales()" id="guardar_datos_btn"><a><i
                                                class="font-icon font-icon-check-square"></i>Guardar cambios</a></li>
                                    <li><a class="cancelar" href="../home/"><i class="glyphicon glyphicon-trash"></i>
                                            Cancelar</a></li>
                                </ul>
                            </section><!--.proj-page-section-->
                        </section><!--.proj-page-->
                    </div>

   


                </div><!--.row-->
            </div><!--.container-fluid-->
        </div><!--.page-content-->
        <!-- Contenido -->

        <?php require_once ("../MainJs/js.php"); ?>
        <script type="text/javascript" src="perfil_profesional.js?v=<?php echo time(); ?>"></script>
        <?php require_once ("../html/footer.php"); ?>

    </body>

    </html>
    <?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>