<?php
   
    require_once("../../config/conexion.php");
    if (isset($_POST["enviar"]) and $_POST["enviar"]=="si"){

        require_once("../../models/Usuario.php");
        $usuario = new Usuario();
        $usuario->reEnvioCorreo();
    }
    if (isset($_GET["correo"])){
        
        $correo = $_GET["correo"];

    }
?>

<!DOCTYPE html>
<html lang="en">
    
<?php require_once('../../portal/html/head.php'); ?>

</head>

<body class="rbt-header-sticky">

      <!-- Start Header Area -->
      <?php require_once('../../portal/html/header.php'); ?>

    <a class="close_side_menu" href="javascript:void(0);"></a>
    <!-- Start breadcrumb Area -->
    <div class="rbt-breadcrumb-default ptb--100 ptb_md--50 ptb_sm--30 bg-gradient-1">
        <div class="container">
            <div class="row justify-content-center">
         
                <div class="col-lg-6">
                    <div class="rbt-contact-form contact-form-style-1 max-width-auto mx-auto">
                       

                        <form class="max-width-auto" action="" method="post" id="register_form">

                             <div class="mb-2">
                                <?php
                                    if (isset($_GET["m"])){
                                        switch($_GET["m"]){
                                            case "1";
                                                ?>
                                                <!-- alert alert-warning alert-icon alert-close alert-dismissible fade in -->
                                                 
                                                        <h6>Correo Enviado. Verifique su correo para confirmar e ingresar al sistema</h6>
                                                        <div class="form-submit-group">
                                                            <input type="hidden" name="enviar" value="si"> 
                                                            <input type="hidden" name="correo" value="<?php echo $correo; ?>">                  
                                                            <button type="submit" class="rbt-btn btn-md btn-gradient hover-icon-reverse" id="reEnvioCorreo" style="float: right;">
                                                                <span class="icon-reverse-wrapper">
                                                                    <span class="btn-text">Reenviar correo</span>
                                                                    <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                                                    <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                                                </span>
                                                            </button>
                                                            <span id="countdown"></span>

                                                        </div>
                                                        <br>
                                               <?php
                                            break;

                                            case "2";
                                            
                                                 ?>
                                                 <!-- <h6>Antes de continuar, verifique su correo electrónico para ver si hay un enlace de verificación. Si no recibió el correo electrónico</h6> -->
                                                    <h6>Correo ya esta confirmado confirmado.</h6>
                                                    <button type="button" class="rbt-btn btn-md btn-gradient hover-icon-reverse" onclick="window.location.href='login.php';" style="float: right;">
                                                            <span class="icon-reverse-wrapper">
                                                                <span class="btn-text">Ingresar</span>
                                                                <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                                                <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                                            </span>
                                                        </button>
                                                    <br>
                                                <?php
                                            break;
                                            case "3";
                                            ?>
                                            <div class="alert alert-warning alert-icon alert-close alert-dismissible " role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <!-- <span aria-hidden="true">×</span> -->
                                                </button>
                                                <i class="font-icon font-icon-warning"></i>
                                                No se encuentra sus datos, por favor registrese al sistema.
                                            </div>
                                            <?php
                                        break;
                                        }
                                    }
                                ?>
                            </div>  
                           
                        </form>
                    </div>
                </div>
               
            </div>
        </div>
    </div>
    <!-- End Breadcrumb Area -->


    <?php require_once('../../portal/html/footer.php'); ?>
    <?php require_once('../../portal/html/js.php'); ?>


    <!-- End Page Wrapper Area -->
    <div class="rbt-progress-parent">
        <svg class="rbt-back-circle svg-inner" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
    </div>
    <script>
        const button = document.getElementById('reEnvioCorreo');
        const countdown = document.getElementById('countdown');
        let timeLeft = 60;
        let timer;

        function updateCountdown() {
            countdown.textContent = `Tiempo restante: ${timeLeft} segundos`;
            if (timeLeft === 0) {
                clearInterval(timer);
                countdown.style.display = 'none';
                button.disabled = false; // Habilitar el botón después del tiempo límite
            }
            timeLeft--;
        }

        // Agregar evento de clic al botón "Reenviar correo"
        button.addEventListener('click', function() {
            button.disabled = true; // Deshabilitar el botón al hacer clic
            timeLeft = 60; // Reiniciar el temporizador
            countdown.style.display = 'inline'; // Mostrar el contador
            timer = setInterval(updateCountdown, 1000); // Iniciar el temporizador

            // Después de 60 segundos, habilitar nuevamente el botón
            setTimeout(function() {
                button.disabled = false;
            }, 60000);
        });

        // Agregar evento de clic al botón "Reenviar correo"
        button.addEventListener('click', function() {
            document.getElementById('register_form').submit(); // Enviar el formulario cuando se hace clic en el botón
        });
    </script>



</body>

</html>