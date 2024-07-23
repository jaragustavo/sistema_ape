<?php
    require_once("../../config/conexion.php");
    if (isset($_POST["enviar"]) and $_POST["enviar"]=="si"){
        require_once("../../models/Usuario.php");
        $usuario = new Usuario();
        $usuario->login();
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
                <!-- Añadir justify-content-center para centrar horizontalmente -->
                <div class="col-lg-6">
                    <div class="rbt-contact-form contact-form-style-1 max-width-auto mx-auto">
                        <!-- Añadir mx-auto para centrar horizontalmente -->
                        
                        <h3 class="text-center">Ingreso al Sistema</h3>

                        <form class="max-width-auto" action="" method="post" id="register_form">
                             <div class="mb-2">
                                <?php
                                    if (isset($_GET["m"])){
                                        switch($_GET["m"]){
                                            case "1";
                                                ?>
                                                <!-- alert alert-warning alert-icon alert-close alert-dismissible fade in -->
                                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    <i class="font-icon font-icon-warning"></i>
                                                    El Usuario y/o Contraseña son incorrectos.
                                                </div>
                                                <?php
                                            break;

                                            case "2";
                                                ?>
                                                 <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <!-- <span aria-hidden="true">×</span> -->
                                                    </button>
                                                    <i class="font-icon font-icon-warning"></i>
                                                    Los campos estan vacios.
                                                </div>
                                                <?php
                                            break;
                                            case "3";
                                            ?>
                                              <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <!-- <span aria-hidden="true">×</span> -->
                                                </button>
                                                <i class="font-icon font-icon-warning"></i>
                                                Los campos ingresados ya existen.
                                            </div>
                                            <?php
                                        break;
                                        }
                                    }
                                ?>
                            </div>  

                            <div class="form-group">
                                
                                <input name="ci" type="text" autocomplete="off"  >
                                <label>Cédula sin puntos *</label>
                                <span class="focus-border"></span>
                            </div>
                            <div class="form-group" style="position: relative;">
                                <input id="password" name="password" type="password" autocomplete="new-password">
                                <label>Password *</label>
                                <span class="focus-border"></span>
                                <span id="togglePassword" class="eye-icon" onclick="togglePasswordVisibility()" 
                                style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; font-size: 20px; background-color: #fff; padding: 5px; border-radius: 50%;">&#x1F441;</span>
                            </div>

                            <div class="row mb--30">
                               
                                <div class="col-lg-12">
                                    <div class="rbt-lost-password text-end">
                                        <a class="rbt-btn-link" href="send_reset_pass.php">¿Olvidaste la contraseña?</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-submit-group">
                                <input type="hidden" name="enviar" value="si">
                                <button type="submit" class="rbt-btn btn-md btn-gradient hover-icon-reverse w-100">
                                    <span class="icon-reverse-wrapper">
                                        <span class="btn-text">Acceder</span>
                                        <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                        <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                    </span>
                                </button>
                            </div>
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

    <script>
        function togglePasswordVisibility() {
            var passwordField = document.getElementById("password");
            var toggleButton = document.getElementById("togglePassword");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleButton.innerHTML = "&#x1F576;"; // Cambiar el ícono a un ojo tachado cuando la contraseña es visible
            } else {
                passwordField.type = "password";
                toggleButton.innerHTML = "&#128065;"; // Cambiar el ícono a un ojo abierto cuando la contraseña está oculta
            }
        }
    </script>

</body>

</html>