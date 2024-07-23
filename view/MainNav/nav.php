/* TODO: Rol 1 es de Usuario */

<nav class="side-menu">
    <ul class="side-menu-list">
    <?php // if ($_SESSION["es_asociado"] == 'si') { ?>
        <li class="blue-dirty with-sub">
        <span>
                <span class="glyphicon glyphicon-home"></span>
                <span class="lbl">Inicio</span>
            </span>
            <ul>
                <li>
                    <a href="../home"><span class="lbl">Home</span></a>
                </li>
                <li>
                    <a href="../home/<?php echo $_SESSION["inicio"] ?>"><span class="lbl">Tablero informativo</span></a>
                </li>
            </ul>

        </li>
        <?php
   // }
            ?>

        <li class="grey with-sub">
            <span>
                <span class="glyphicon glyphicon-user"></span>
                <span class="lbl">Perfil</span>
            </span>
            <ul>
                <li>
                    <a href="../Perfiles/datosPersonales.php"><span class="lbl">Datos personales</span></a>
                </li>
                <li>
                    <a href="../Perfiles/datosProfesionales.php"><span class="lbl">Datos profesionales</span></a>
                </li>
            </ul>
        </li>

        <!-- <li class="magenta with-sub">
            <span>
                <span class="glyphicon glyphicon-folder-open"></span>
                <span class="lbl">Currículum Virtual</span>
            </span>
            <ul>
                <a href="..\DocsPersonales\listarDocsPersonales.php"><span class="lbl">Personales</span></a></li>
                <a href="..\DocsAcademicos\listarDocsAcademicos.php"><span class="lbl">Académicos</span></a></li>
                <a href="..\DocsAcademicos\listarCapacitaciones.php"><span class="lbl">Capacitaciones</span></a></li>
                <a href="..\DocsAcademicos\listarLaborales.php"><span class="lbl">Laborales</span></a></li>
            </ul>
        </li> -->
        <?php
        require_once ("../../config/conexion.php");
        require_once ("../../models/Usuario.php");
        $usuario = new Usuario();

        $permisos = $usuario->get_permisos_x_roles($_SESSION["usuario_id"]);

        foreach ($permisos as $key => $value) {
            $permisos_ordenados = array();
            switch ($value["nombre_permiso"]) {
                case "Investigaciones":
                    $permisos_ordenados[0] = $value;
                    break;
                case "Reposos Emitidos":
                    $permisos_ordenados[1] = $value;
                    break;
                case "Tramites en linea":
                    $permisos_ordenados[2] = $value;
                    break;
                case "Administrar":
                    $permisos_ordenados[3] = $value;
                    break;
                case "Consultas":
                    $permisos_ordenados[4] = $value;
                    break;
            }
        }
        // error_log(count($permisos_ordenados));
        foreach ($permisos as $key => $value) {

            if ($value["nombre_permiso"] == "Publicaciones") { ?>

            <?php if ($_SESSION["es_asociado"] == 'si') { ?>
                            <li class="aquamarine with-sub">
                                <span>
                                    <span class="glyphicon glyphicon-plus"></span>
                                    <span class="lbl">Mis publicaciones</span>
                                </span>
                                <ul>
                                    <a href="..\Publicaciones\miPerfil.php"><span class="lbl">Administrar</span></a>
                            </li>
                        </ul>
                        </li>
                        <?php } ?>

        <?php }
            if ($value["nombre_permiso"] == "Tramites en linea") { ?>
            <li class="gold with-sub">
                <span>
                    <span class="fa fa-laptop" style="font-size:20px;"></span>
                    <span class="lbl">Trámites</span>
                </span>
                <ul>
                    <?php if ($_SESSION["es_asociado"] == 'si') { ?>
                        <li>
                            <a href="..\Tramites\listarTramites.php"><span class="lbl">Solidaridad</span></a>
                        </li>
                        <li>
                            <a href="..\Concursos\listarConcursos.php"><span class="lbl">Concursos</span></a>
                        </li>
                    <?php } ?>

                    <li>
                        <a href="..\Certificaciones\listarCertificacionesDisponibles.php"><span
                                class="lbl">Certificaciones</span></a>
                    </li>
                    <?php if ($_SESSION["es_asociado"] == 'no') { ?>
                        <li>
                            <a href="..\home\docsAsociacion.php"><span
                                    class="lbl">Asociarme a la APE</span></a>
                        </li>
                    <?php } ?>
                    <?php if ($_SESSION["es_asociado"] == 'si') { ?>
                        <li>
                            <a href="..\Certificaciones\listarCursosDisponibles.php"><span class="lbl">Cursos</span></a>
                        </li>
                        <li>
                            <a href="..\SedeSocial\listarActividadesSociales.php"><span class="lbl">Sede Social</span></a>
                        </li>
                        <li>
                            <a href="..\Tramites\listarSolicitudesAyuda.php"><span class="lbl">Ayuda al socio</span></a>
                        </li>
                    <?php } ?>
                </ul>
            </li>
        <?php }
            if ($value["nombre_permiso"] == "Consultas" || $value["nombre_permiso"] == "Tramites") { ?>

            <li class="red with-sub">
                <span>
                    <span class="glyphicon glyphicon-list-alt" style="font-size:20px;"></span>
                    <span class="lbl">Administrar</span>
                </span>
                <?php

                if ($value["nombre_permiso"] == "Tramites") {
                    ?>
                    <ul>
                        <li>
                            <a href="..\Movimientos\listarSolicitudes.php"><span class="lbl">Trámites</span></a>
                        </li>
                        <li>
                            <a href="..\Movimientos\administrarInscripciones.php"><span class="lbl">Inscripciones</span></a>
                        </li>
                    </ul>
                <?php } elseif ($value["nombre_permiso"] == "Consultas") {
                    ?>
                    <ul>
                        <a href="..\Consultas\listarTramites.php"><span class="lbl">Movimientos de trámites</span></a>
                    </ul>
                    <?php
                }
                ?>
            </li>
        <?php }
            if ($value["nombre_permiso"] == "Rendimiento de Departamentos") { ?>
            <li class="blue-darker with-sub">
                <span>
                    <span class="glyphicon glyphicon-stats" style="font-size:20px;"></span>
                    <span class="lbl">Indicadores de Gestión</span>
                </span>
                <ul>
                    <a href="..\Tramites\listarTramites.php"><span class="lbl">Rendimiento por Departamento</span></a>
            </li>
            </ul>
            </li>
        <?php }
            if ($value["nombre_permiso"] == "Cursos instructores") { ?>
            <li class="green with-sub">
                <span>
                    <span class="glyphicon glyphicon-education" style="font-size:20px;"></span>
                    <span class="lbl">Mis cursos</span>
                </span>
                <ul>
                    <li>
                        <a href="..\Instructores\cursosInstructores.php"><span class="lbl">Ir</span></a>
                    </li>
                    <li>
                        <a href="..\Movimientos\administrarInscripciones.php"><span class="lbl">Inscripciones</span></a>
                    </li>
                </ul>
            </li>
        <?php }
            if ($value["nombre_permiso"] == "Alumno") { ?>
            <li class="red with-sub">
                <span>
                    <span class="glyphicon glyphicon-education" style="font-size:20px;"></span>
                    <span class="lbl">Mi aprendizaje</span>
                </span>
                <ul>
                    <li>
                        <a href="..\Certificaciones\listarCertificaciones.php"><span class="lbl">Certificaciones</span></a>
                    </li>
                    <li>
                        <a href="..\Certificaciones\listarCursos.php"><span class="lbl">Cursos</span></a>
                    </li>
                </ul>
            </li>
            <!-- <li class="blue-darker">
                <a href="..\Certificaciones\listarCertificaciones.php"><span class="glyphicon glyphicon-education"></span>
                    <span class="lbl">Certificaciones</span></a>
            </li>
            <li class="red">
                <a href="..\Certificaciones\listarCursos.php"><span class="glyphicon glyphicon-blackboard"></span>
                    <span class="lbl">Cursos</span></a>
            </li> -->
        <?php }
            if ($value["nombre_permiso"] == "Administración") { ?>
            <li class="gold with-sub">
                <span>
                    <span class="glyphicon glyphicon-cog"></span>
                    <span class="lbl">Administración</span>
                </span>
                <ul>
                    <li>
                        <a href="../Administracion/listarTramites.php"><span class="lbl">Trámites</span></a>
                    </li>
                </ul>
            </li>
        <?php }

        }
        ?>





</nav>