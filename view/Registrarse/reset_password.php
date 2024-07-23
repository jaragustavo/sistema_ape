<?php
    require_once("../../config/conexion.php");
    if (isset($_POST["enviar"]) and $_POST["enviar"]=="si"){
        require_once("../../models/Usuario.php");
        $usuario = new Usuario();
        $usuario->resetPassword();
    }
    if (isset($_GET["token"])){
        
        $token = $_GET["token"];
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
                <!-- Añadir justify-content-center para centrar horizontalmente -->
                <div class="col-lg-6">
                    <div class="rbt-contact-form contact-form-style-1 max-width-auto mx-auto">
                        <!-- Añadir mx-auto para centrar horizontalmente -->
                        
                      

                        <form class="max-width-auto" action="" method="post" id="register_form">
                             <div class="mb-2">
                                <?php
                                    if (isset($_GET["m"])){
                                        switch($_GET["m"]){
                                            case "1";
                                                ?>
                                                  <h3 class="text-center">Aviso</h3>
                                                <!-- alert alert-warning alert-icon alert-close alert-dismissible fade in -->
                                                    <div class="alert alert-warning alert-icon alert-close alert-dismissible " role="alert">
                                                       <i class="font-icon font-icon-warning"></i>
                                                       Contraseña restablecida correctamente.
                                                    </div>
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
                                                 <h3 class="text-center">Aviso</h3>
                                                <div class="alert alert-warning alert-icon alert-close alert-dismissible " role="alert">
                                                    <i class="font-icon font-icon-warning"></i>
                                                    Error al restablecer la contraseña.
                                                </div>
                                                <?php
                                            break;
                                            case "3";
                                            ?>
                                               
                                               <h3 class="text-center">Ingrese nueva Contraseña</h3>
                                                <label>Correo Electrónico *</label>
                                                <input name="correo" type="text" readonly value="<?php echo $correo; ?>" style="border: none; border-bottom: 1px solid #ccc; background-color: transparent; color: #000; padding: 8px 0; outline: none; margin-bottom: 20px;">
                                              
                                                <div class="form-group" style="position: relative;">
                                                    <input id="password" name="password" type="password" autocomplete="new-password">
                                                    <label>Password *</label>
                                                    <span class="focus-border"></span>
                                                    <span id="togglePassword" class="eye-icon" onclick="togglePasswordVisibility()" 
                                                    style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; font-size: 20px; background-color: #fff; padding: 5px; border-radius: 50%;">&#x1F441;</span>
                                                </div>

                                            
                                                <div class="form-group" style="position: relative;">
                                                    <input id="conpassword" name="conpassword" type="password" autocomplete="new-password">
                                                    <label>Repetir Password *</label>
                                                    <span class="focus-border"></span>
                                                    <span id="conTogglePassword" class="eye-icon" onclick="toggleConPasswordVisibility()" 
                                                    style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; font-size: 20px; background-color: #fff; padding: 5px; border-radius: 50%;">&#x1F441;</span>
                                                </div>
                                                
                                                <div class="form-submit-group">
                                                    <input type="hidden" name="token" value="<?php echo $token; ?>"> 
                                                    <input type="hidden" name="enviar" value="si">
                                                    <button type="submit" class="rbt-btn btn-md btn-gradient hover-icon-reverse w-100">
                                                        <span class="icon-reverse-wrapper">
                                                            <span class="btn-text">Recuperar</span>
                                                            <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                                            <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                                        </span>
                                                    </button>
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