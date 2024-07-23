<?php
require_once("../../config/conexion.php"); 
if(isset($_SESSION["usuario_id"])){ 
?>
<!DOCTYPE html>
<html>
		<?php require_once("../MainHead/head.php");?>
        <link rel="stylesheet" href="../../public/css/lib/bootstrap-table/bootstrap-table.min.css">

		<title>Solicitudes</title>
	</head>


    <style>
        .tableFixHead thead th {
            position: sticky;
            top: 0;
        }
        .tbl-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .tbl-cell {
            flex: 1;
        }
        .search-input {
            margin-left: 20px;
        }
    </style>

    <body class="with-side-menu">
        <?php require_once("../MainHeader/header.php");?>

        <div class="mobile-menu-left-overlay"></div>

        <?php require_once("../MainNav/nav.php");?>

        <!-- Contenido -->
        <div class="page-content">
            <div class="container-fluid">
                <section class="box-typical box-typical-max-280 scrollable">
                <header class="box-typical-header">
                        <section class="proj-page-section" style="padding:15px 15px 0px 15px !important">
                            <div class="row">
                               
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input type="text" id="searchInput" class="form-control" placeholder="Buscar...">
                                    </div>
                                 </div>
                                 <div class="col-md-1">
                                    <button type="button" class="btn btn-rounded btn-success" onclick="asignarmeTramites()" id="asignarme">Asignarme</button>
                                </div>
                        
                                <!-- <div class="col-lg-4" style="padding:15px 0px 0px 20px  !important">
                                    <h5>Trámites en Fiscalización</h5>
                                </div> -->
                              
                               
                            </div>
                        </section>

                  
                </header>
                    <div class="box-typical-body" id="solicitudes_area">
                        <div class="table-responsive tableFixHead">
                            <table class="table table-hover" id="solicitudes_area_data">
                            
                                <?php
                                    require_once("tablasSolicitudesArea.php"); 
                                ?>
                            </table>
                        </div>
                    </div><!--.box-typical-body-->
                </section><!--.box-typical-->
                <section class="box-typical box-typical-max-280 scrollable">
                    <header class="box-typical-header">
                        
                         <section class="proj-page-section" style="padding:15px 15px 0px 15px !important">
                            <div class="row">
                                <div class="col-lg-2" style="padding:10px 0px 0px 20px  !important">
                                    <h5>Solicitudes asignadas a mí</h5>
                                </div>
                        
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" id="searchAsignada" class="form-control" placeholder="Buscar...">
                                    </div>
                                
                                
                                </div>
                            </div>
                        </section>
                        
                    </header>
                    <div class="box-typical-body" id="solicitudes_asignadas_a_mi">
                    <div class="table-responsive">
                            <table class="table table-hover" id="solicitudes_asignadas_a_mi_data">
                                    
                                    <?php
                                        require_once("tablasSolicitudesUsuario.php"); 
                                    ?>
                            </table>
                        </div>
                    </div><!--.box-typical-body-->
                </section><!--.box-typical-->
            </div>
        </div>
        <?php require_once("../MainJs/js.php");?>
        <script src="../../public/js/lib/peity/jquery.peity.min.js"></script>
        <script src="../../public/js/lib/table-edit/jquery.tabledit.min.js"></script>
        <script type="text/javascript" src="movimientos.js?v=<?php echo time();?>"></script>
        <?php require_once("../html/footer.php");?>
    </body>
</html>
<?php
} else {
    header("Location:".Conectar::ruta()."index.php");
  }
?>