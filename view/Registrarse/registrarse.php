<?php
    require_once("../../config/conexion.php");
    if (isset($_POST["enviar"]) and $_POST["enviar"]=="si"){
        require_once("../../models/Usuario.php");
        $usuario = new Usuario();
        $usuario->register();
    }
   
?>

<!DOCTYPE html>
<html lang="en">

<?php require_once('../../portal/html/head.php'); ?>


    <style>
        /* Ocultar el placeholder del campo de teléfono */
        
        #register_telefono::placeholder {
            opacity: 0;
        }
        
        .iti__selected-flag {
            background-color: rgba(255, 255, 255, 0.1) !important;
            /* Blanco con 50% de transparencia */
        }
        
        input {
            background-color: rgba(255, 255, 255, 0.1) !important;
            /* Blanco con 50% de transparencia */
        }
    </style>


</head>

<body class="rbt-header-sticky">
    

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
                        <h3>Registrarse</h3>

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
                                            <!-- alert alert-warning alert-icon alert-close alert-dismissible fade in -->
                                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <i class="font-icon font-icon-warning"></i>
                                                Los campos estan vacios.
                                            </div>
                                            <?php
                                            break;
       
                                            case "3";
                                            ?>
                                            <!-- alert alert-warning alert-icon alert-close alert-dismissible fade in -->
                                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <i class="font-icon font-icon-warning"></i>
                                                El campo ya existe. <?php echo $_GET["tipo"] ?>
                                            </div>
                                            <?php
                                            break;
                                      
                                            case "4";
                                                ?>
                                                <!-- alert alert-warning alert-icon alert-close alert-dismissible fade in -->
                                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    <i class="font-icon font-icon-warning"></i>
                                                    Los correos ingresados no coinciden.
                                                </div>
                                                <?php
                                            break;

                                            case "5";
                                            ?>
                                            <!-- alert alert-warning alert-icon alert-close alert-dismissible fade in -->
                                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <i class="font-icon font-icon-warning"></i>
                                                Las contraseñas no coinciden coinciden.
                                            </div>
                                            <?php
                                            break;
                                            case "6";
                                            ?>
                                            <!-- alert alert-warning alert-icon alert-close alert-dismissible fade in -->
                                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <i class="font-icon font-icon-warning"></i>
                                                El número de teléfono no es valido.
                                            </div>
                                            <?php
                                            break;
                                            case "7";
                                            ?>
                                            <!-- alert alert-warning alert-icon alert-close alert-dismissible fade in -->
                                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <i class="font-icon font-icon-warning"></i>
                                                Correo no es valido.
                                            </div>
                                            <?php
                                            break;
                                            case "7";
                                            ?>
                                            <!-- alert alert-warning alert-icon alert-close alert-dismissible fade in -->
                                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <i class="font-icon font-icon-warning"></i>
                                                El número de Ci. no existe en la base de datos de la policia nacional.
                                            </div>
                                            <?php
                                            break;
                                          
                                        }
                                    }
                                ?>
                            </div>  

                            <div class="form-group">
                                <input name="register_user" type="text" autocomplete="off">
                                <label>Cédula sin puntos *</label>
                                <span class="focus-border"></span>
                            </div>

                            <div class="form-group">
                                <input name="register_correo" type="text" autocomplete="off" >
                                <label>Correo Electrónico *</label>
                                <span class="focus-border"></span>
                            </div>
                            <div class="form-group">
                                <input name="register_concorreo" type="text" autocomplete="off">
                                <label>Repetir Correo Electrónico *</label>
                                <span class="focus-border"></span>
                            </div>

                            <div class="form-group" style="position: relative;">
                                <input id="register_password" name="register_password" type="password" autocomplete="new-password">
                                <label>Password *</label>
                                <span class="focus-border"></span>
                                <span id="togglePassword" class="eye-icon" onclick="togglePasswordVisibility()" 
                                style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; font-size: 20px; background-color: #fff; padding: 5px; border-radius: 50%;">&#x1F441;</span>
                            </div>

                            <div class="form-group" style="position: relative;">
                                <input id="register_conpassword" name="register_conpassword" type="password" autocomplete="new-password">
                                <label>Password *</label>
                                <span class="focus-border"></span>
                                <span id="conTogglePassword" class="eye-icon" onclick="toggleConPasswordVisibility()" 
                                style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; font-size: 20px; background-color: #fff; padding: 5px; border-radius: 50%;">&#x1F441;</span>
                            </div>
  
                            <div class="form-group">
                                <input name="register_telefono" type="text" id="register_telefono" style="padding: 10px 6px 8px 97px !important " autocomplete="off" >
                                <div id="telefono_container" style="cursor: pointer;">
                                    <label id="telefono" for="register_telefono" style="padding-left: 100px;">Teléfono (Ej. 961876432) *</label>
                                </div>
                                <!-- Campo oculto para el código de país -->
                                <input type="hidden" name="dial_code" id="dial_code">
                        
                                <span class="focus-border"></span>

                            </div>
                            <div class="form-submit-group">
                                
                                <input type="hidden" name="enviar" value="si">
                                <button type="submit" class="rbt-btn btn-md btn-gradient hover-icon-reverse" id="registrarse" style="float: right;">
                                    <span class="icon-reverse-wrapper">
                                        <span class="btn-text">Registrarse</span>
                                        <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                        <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                    </span>
                                </button>
                                <div id="loader" style="display: none;">
                                    <!-- Icono de reloj de arena -->
                                    <i class="fa fa-spinner fa-spin"></i>
                                    <!-- Mensaje de estado -->
                                    Enviando Registro...
                                </div>
              
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
        
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <!-- Inicializar intl-tel-input -->


    <script>

        $(document).ready(function() {
            var input = document.querySelector("#register_telefono");

            // Configuración de intlTelInput
            var iti = window.intlTelInput(input, {
                initialCountry: "auto",
                separateDialCode: true,
                geoIpLookup: function(callback) {
                    $.get("https://ipinfo.io", function() {}, "jsonp").always(function(resp) {
                        var countryCode = (resp && resp.country) ? resp.country : "us";
                        callback(countryCode);
                    });
                },
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
            });

            // Ocultar el placeholder
            input.setAttribute('placeholder', '');

            // Guardar el estilo original del padding
            const originalPadding = document.getElementById('telefono').style.paddingLeft;

            // Añadir evento de focus al input de teléfono
            input.addEventListener('focus', function() {
                document.getElementById('telefono').style.paddingLeft = '10px';


            });

            // Añadir evento de blur al input de teléfono
            input.addEventListener('blur', function() {
                document.getElementById('telefono').style.paddingLeft = originalPadding; // Volver al padding original
            });

            // Añadir evento de clic al contenedor del label de teléfono
            document.getElementById('telefono_container').addEventListener('click', function(event) {
                event.preventDefault(); // Prevenir el comportamiento predeterminado del clic
                document.getElementById('telefono').style.paddingLeft = '10px'; // Volver al padding original
            });

            // Cuando el campo de teléfono pierde el foco, establecer el padding del label en 10px
            input.addEventListener('blur', function(event) {
                event.preventDefault(); // Prevenir el comportamiento predeterminado del clic
                document.getElementById('telefono').style.paddingLeft = '10px'; // Volver al padding original
            });

            $('#registrarse').click(function() {
                    // Deshabilitar el botón
                    $(this).hide();
                    // Mostrar el reloj de arena y el mensaje
                    $('#loader').show();
                    // Realizar la acción del formulario
                    $('#register_form').submit();
             });
             // Establecer el valor por defecto del código de país al cargar la página
            $('#dial_code').val(iti.getSelectedCountryData().dialCode);

            // Actualizar el campo oculto cuando el usuario cambia el código de país
            input.addEventListener('countrychange', function() {
                $('#dial_code').val(iti.getSelectedCountryData().dialCode);
            });


         
        });

        function togglePasswordVisibility() {
            var passwordField = document.getElementById("register_password");
            var toggleButton = document.getElementById("togglePassword");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleButton.innerHTML = "&#x1F576;"; // Cambiar el ícono a un ojo tachado cuando la contraseña es visible
            } else {
                passwordField.type = "password";
                toggleButton.innerHTML = "&#128065;"; // Cambiar el ícono a un ojo abierto cuando la contraseña está oculta
            }
        }

        function toggleConPasswordVisibility() {
            var passwordField = document.getElementById("register_conpassword");
            var toggleButton = document.getElementById("contogglePassword");

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