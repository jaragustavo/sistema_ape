    <?php
        require_once("../../config/conexion.php");
        if (isset($_GET["token"])){
            $token = $_GET["token"];
            require_once("../../models/Usuario.php");
            $usuario = new Usuario();
            $usuario->confirmarCuenta($token);

        }

        
    ?>
    <!DOCTYPE html>
    <html lang="en">
        
    <?php require_once('../../portal/html/head.php'); ?>

    </head>


    </head>

    <body class="rbt-header-sticky">

      <!-- Start Header Area -->
      <?php require_once('../../portal/html/header.php'); ?>
    <!-- End Side Vav -->
    <a class="close_side_menu" href="javascript:void(0);"></a>
    <!-- Start breadcrumb Area -->
    <div class="rbt-breadcrumb-default ptb--100 ptb_md--50 ptb_sm--30 bg-gradient-1">
        <div class="container">
            <div class="row justify-content-center">
         
                <div class="col-lg-6">
                    <div class="rbt-contact-form contact-form-style-1 max-width-auto mx-auto">
                      

                        <form class="max-width-auto" action="login.php" method="post" id="confirmacion_cuenta_form">
                            <div class="form-submit-group">
                                <div class="mb-2">
                                    <?php
                                        if (isset($_GET["m"])){
                                            switch($_GET["m"]){
                                                case "1";
                                                    ?>
                                                        <h6>Se confirmó correctamente el correo, ingresar al sistema</h6>
                                                        <button type="button" class="rbt-btn btn-md btn-gradient hover-icon-reverse" onclick="window.location.href='login.php';" style="float: right;">
                                                            <span class="icon-reverse-wrapper">
                                                                <span class="btn-text">Ingresar</span>
                                                                <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                                                <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                                            </span>
                                                        </button>
                                                    
                                                    <?php
                                                break;

                                                case "2";
                                                    ?>
                                                      <h6>Cuenta ya fue confirmada</h6>
                                                      <button type="button" class="rbt-btn btn-md btn-gradient hover-icon-reverse" onclick="window.location.href='login.php';" style="float: right;">
                                                            <span class="icon-reverse-wrapper">
                                                                <span class="btn-text">Ingresar</span>
                                                                <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                                                <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                                            </span>
                                                    </button>
                                                     
                                                   
                                                    <?php
                                                break;
                                                case "3";
                                                ?>
                                                    <h6>Token no existe o a expirado</h6>
                                                <?php
                                            break;
                                            }
                                        }
                                    ?>
                                </div>              
                                

                            </div>
                            <br>
                        </form>
                    </div>
                </div>
               
            </div>
        </div>
    </div>
    

    <!-- End Page Wrapper Area -->
    <div class="rbt-progress-parent">
        <svg class="rbt-back-circle svg-inner" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
    </div>

    <?php require_once('../../portal/html/footer.php'); ?>
    <?php require_once('../../portal/html/js.php'); ?>




</body>

</html>