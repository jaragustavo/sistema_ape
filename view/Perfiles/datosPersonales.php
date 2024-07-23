<?php
require_once ("../../config/conexion.php");
if (isset($_SESSION["usuario_id"])) {
    ?>
    <!DOCTYPE html>
    <html>
    <link rel="stylesheet" href="plugins/dropzone/dropzone.css" type="text/css">
    <?php require_once ("../MainHead/head.php"); ?>

    <!-- <link rel="stylesheet" href="plugins/css/dropzone.css"> -->

    <title>Datos Personales</title>
    </head>

    <body class="with-side-menu">
    <?php require_once ("../MainHeader/header.php"); ?>

    <div class="mobile-menu-left-overlay"></div>
    <?php require_once ("../MainNav/nav.php"); ?>

    <style>
        
        .el-card-avatar {
        position: relative;
        width: 100%;
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
        margin-top: 5px; /* Ajusta el margen superior para separar el botón de la imagen */
        display: flex;
        justify-content: center;
    }
    </style>
    <!-- Contenido -->
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <!-- Columna principal para el formulario de datos personales -->
                <div class="col-xxl-9 col-lg-12 col-xl-8 col-md-8">
                    <section class="box-typical profile-side-user" id="seccion_foto_perfil">
                        <div class="row">
                            <!-- Foto de perfil -->
                            <div class="col-lg-3">
                                <button type="button" class="avatar-preview avatar-preview-128">

                                     <?php

                                        $imagenes_perfil = Publicacion::get_imagenes_perfil($_SESSION["usuario_id"]);

                                        $foto_registro_profesional = $imagenes_perfil['foto_registro_profesional'];
                                        $foto_perfil = $imagenes_perfil['foto_perfil'];
                                        $foto_ci = $imagenes_perfil['foto_ci'];

                                        $esImagen = false;
                                        $esPDF = false;

                                        if ($foto_ci != null && $foto_ci != '') {
                                            $extension = pathinfo($foto_ci, PATHINFO_EXTENSION);
                                            if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png'])) {
                                                $esImagen = true;
                                            } elseif (strtolower($extension) == 'pdf') {
                                                $esPDF = true;
                                            }
                                        } else {
                                            $foto_ci = "assets/assets-main/images/icons/user2.png";
                                            $esImagen = true; // Para mostrar la imagen predeterminada
                                        }
                                    ?>
                                    
                                    <img src="../<?php echo ($foto_perfil != null && $foto_perfil != '') ? $foto_perfil : "assets/assets-main/images/icons/user2.png"; ?>" alt="" />
                                    <span class="update">
                                        <i class="font-icon font-icon-picture-double"></i>
                                        Cambiar foto
                                    </span>
                                    <input id="foto_perfil" type="file" onchange="guardarFoto()" />
                                </button>
                            </div>
                            <!-- Documento del usuario -->
                            <div class="col-lg-5">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="nombre">Nombre</label>
                                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" disabled/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="apellido">Apellido</label>
                                            <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Apellido" disabled/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fecha_nacimiento">Fecha de nacimiento</label>
                                            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" placeholder="dd/mm/yyyy" disabled/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="documento_identidad">Nro.Documento</label>
                                            <input type="text" class="form-control" id="documento_identidad" name="documento_identidad" placeholder="Documento de identidad" disabled/>
                                        </div>
                                    </div>
                                </div>   
                            </div>
                            <!-- Documento del usuario -->
                            <div class="col-md-4">
                                <div class="col-lg-12">
                                    <fieldset class="form-group text-center">
                                        <label class="form-label semibold" for="documento">Imagen Documento Identidad </label>
                                        <p class="help-block text-small-red" style="font-size: 12px; color: red;">Ambos lados</p>
                                        <div class="el-element-overlay">
                                            <div class="el-card-item">
                                                <div class="el-card-avatar el-overlay-1 rectangular-preview" id="contenedor-preview">
                                                    <?php if ($esImagen) { ?>
                                                        <a id="imagen-enlace" href="../<?php echo $foto_ci; ?>" target="_blank">
                                                            <img id="imagenmuestra" name="imagenmuestra" class="previsualizar" title="Imagen de la cedula"
                                                                src="../<?php echo $foto_ci; ?>" alt="Imagen Ci.">
                                                        </a>
                                                    <?php } elseif ($esPDF) { ?>
                                                        <a id="pdf-enlace" href="../<?php echo $foto_ci; ?>" target="_blank">
                                                            <iframe id="pdfmuestra" name="pdfmuestra" class="previsualizar" src="../<?php echo $foto_ci; ?>" style="display: block;" width="100%" height="300px"></iframe>
                                                        </a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                      
                                        <div class="waves-effect waves-light">
                                            <label for="imagen" class="custom-file-upload">Adjuntar Documento</label>
                                            <input type="file" class="nuevaImagen" name="imagen" id="imagen" onchange="guardarFotoCi()">
                                            <input type="hidden" name="imagenactual" id="imagenactual">
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="box-typical proj-page">
                        <div class="row">
                            <form method="post" id="datos_personales_form">
                                <section class="proj-page-section">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="telefono">Teléfono</label>
                                            <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Telefono" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="email">Correo electrónico</label>
                                            <input type="text" class="form-control" id="email" name="email" placeholder="email" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="cantidad_hijo">Cantidad de Hijos</label>
                                            <input type="number" class="form-control" id="cantidad_hijo" name="cantidad_hijo" placeholder="Cantidad de Hijos" style="width: 100%;" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="estado_civil">Estado Civil</label>
                                            <select class="form-control" id="estado_civil" name="estado_civil">
                                                <option value="">Seleccione Estado Civil</option>
                                                <option value="CA">Casada/o</option>
                                                <option value="SO">Soltera/o</option>
                                                <option value="DI">Divorciada/o</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="departamento_id">Departamento</label>
                                            <select class="form-control" id="departamento_id" name="departamento_id">
                                                <option value="-1">Seleccione Departamento</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="ciudad_id">Ciudad</label>
                                            <select class="form-control" id="ciudad_id" name="ciudad_id">
                                                <option value="">Seleccione Ciudad</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="direccion_domicilio">Dirección de domicilio</label>
                                            <input type="text" class="form-control" id="direccion_domicilio" name="direccion_domicilio" placeholder="Dirección de su domicilio" style="width: 100%;" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="contacto">Contacto</label>
                                            <input type="text" class="form-control" id="contacto" name="contacto" placeholder="Contacto alternativo" style="width: 100%;" />
                                        </div>
                                    </div>
                                </section>
                            </form>
                        </div>
                    </section>
                </div>

                <!-- Columna lateral para acciones adicionales -->
                <div class="col-xxl-3 col-lg-12 col-xl-4 col-md-4 align-self-start">
                    <section class="box-typical proj-page">
                        <section class="proj-page-section">
                            <ul class="proj-page-actions-list">
                                <li onclick="guardarDatosPersonales()" id="guardar_datos_btn"><a><i class="font-icon font-icon-check-square"></i>Guardar cambios</a></li>
                                <li><a class="cancelar" href="../home/"><i class="glyphicon glyphicon-trash"></i> Cancelar</a></li>
                            </ul>
                        </section>
                    </section>
                </div>
            </div>
        </div>
    </div>


    <?php require_once ("../MainJs/js.php"); ?>
    <script type="text/javascript" src="perfiles.js?v=<?php echo time(); ?>"></script>
    <?php require_once ("../html/footer.php"); ?>

</body>

    </html>
    <?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>