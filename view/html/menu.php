<?php
    $_GET['op']="cargarMenu";
    require_once("../../controller/usuario.php");

    // cargarMenu();
    /* TODO: Obtener listado de acceso por ROL ID del Usuario */
    
?>

<div class="app-menu navbar-menu">

    <div class="navbar-brand-box">

        <a href="index.html">
            <span>
                <img src="../../assets/images/stethoscope.png" alt="" height="50">
            </span>
        </a>

        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>

    </div>

    <div id="scrollbar">

        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Área Personal</span></li>

                <?php
                    if($_SESSION['Curriculum Virtual']==1){
                        ?>
                        <li class="nav-item">
                                    <a class="nav-link menu-link" href="#">
                                        <i class="ri-honour-line"></i> <span data-key="t-widgets">Currículum</span>
                                    </a>
                                </li>
                                <?php
                    }
                    if($_SESSION['Investigaciones']==1){
                        ?>
                        <li class="nav-item">
                                    <a class="nav-link menu-link" href="#">
                                        <i class="ri-honour-line"></i> <span data-key="t-widgets">Investigaciones</span>
                                    </a>
                                </li>
                                <?php
                    }
                    ?>
                

                <li class="menu-title"><span data-key="t-menu">Aportes Científicos</span></li>

                
            </ul>
        </div>

    </div>

    <div class="sidebar-background"></div>
</div>

<div class="vertical-overlay"></div>