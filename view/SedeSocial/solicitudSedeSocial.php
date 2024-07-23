<?php
require_once ("../../config/conexion.php");
if (isset($_SESSION["usuario_id"])) {
    ?>
    <!DOCTYPE html>
    <html>
    <?php require_once ("../MainHead/head.php"); ?>
    <link rel="stylesheet" href="../../public/css/separate/carousel/carousel.css">
    <!-- <link rel="stylesheet" href="plugins/css/dropzone.css"> -->

    <title>Reservar</title>
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
                                    Datos para la reserva
                                </div>
                            </section><!--.proj-page-section-->

                            <!-- formulario -->
                            <div class="container-fluid">
                                <div class="row">
                                    <main class="carousel-container">
                                        <div class="carousel">
                                            <div class="item active">
                                                <img src="../../public/img/quincho 1.jpeg" alt="Image 1" />
                                                <p class="caption">Quincho 1</p>
                                            </div>
                                            <div class="item">
                                                <img src="../../public/img/quincho 2.png" alt="Image 2" />
                                                <p class="caption">Quincho 2</p>
                                            </div>
                                            <div class="item">
                                                <img src="../../public/img/piscina.jpg" alt="Image 3" />
                                                <p class="caption">Piscina 1</p>
                                            </div>
                                            <div class="item">
                                                <img src="../../public/img/piscina 2.jpg" alt="Image 4" />
                                                <p class="caption">Piscina 2</p>
                                            </div>
                                        </div>
                                        <button class="btn-carousel prev-car" style="margin-left:-7%;">
                                            <i class='glyphicon glyphicon-arrow-left'></i>
                                        </button>
                                        <button class="btn-carousel next-car" style="margin-right:-7%;"><i class='glyphicon glyphicon-arrow-right'></i></button>
                                        <div class="dots"></div>
                                    </main>
                                    <section class="proj-page-section">

                                        <div class="proj-page-txt">
                                            <header class="proj-page-subtitle">
                                                <h3 class="tramite_nombre"></h3>
                                            </header>
                                            <p>Mediante la galería, puede apreciar los diferentes salones, quinchos,
                                                piscinas y canchas que tenemos disponibles en la Sede Social.<br>
                                                Debe seleccionar la fecha, lugar y hora de inicio de su reserva, al igual
                                                que
                                                indicar la cantidad de personas que invitará.
                                                En caso de tener una hora de finalización, lo puede poner. En caso
                                                contrario,
                                                tendremos en cuenta que el lugar se reservará durante todo el día.<br>
                                                Tenga en cuenta que son muchos los socios que desean hacer usufructo de las
                                                instalaciones con las que cuenta la APE. Si no utilizará el lugar hasta el
                                                final del día,
                                                agradecemos de antemano su aclaración en su reserva, completando el campo
                                                necesario.<br>
                                                No se aceptan eventos con más de 200 invitados.
                                            </p>
                                        </div>
                                    </section><!--.proj-page-section-->
                                    <input type="hidden" id="idEncrypted" name="idEncrypted">
                                    <input type="hidden" id="tramite_id" name="tramite_id">
                                    <form method="post" id="datos_reserva_form">
                                        <section class="proj-page-section proj-page-header">

                                        </section>
                                        <section class="box-typical steps-icon-block" id="formulario">
                                            <input type="hidden" id="tramite_code">
                                            <input type="hidden" id="idEncrypted">
                                            <!-- Información personal -->
                                            <div class="row">
                                                <div class="col-xl-6">
                                                    <div class="form-group">
                                                        <label for="cantidad_personas"
                                                            style="text-align:left;margin:5px;margin-left:30px;">Cantidad
                                                            de personas (aprox) *</label>
                                                        <input type="number" class="form-control" id="cantidad_personas"
                                                            name="cantidad_personas" placeholder="Cantidad de personas" />
                                                    </div>
                                                </div>
                                                <div class="col-xl-6">
                                                    <div class="form-group">
                                                        <label class="form-label" for="local"
                                                            style="text-align:left;margin:5px;margin-left:30px;">Local
                                                            *</label>
                                                        <select class="form-control " id="local" name="local"
                                                            data-placeholder="Seleccionar">
                                                            <option label="Seleccionar"></option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-xl-4">
                                                    <div class="form-group">
                                                        <label for="fecha_reserva"
                                                            style="text-align:left;margin:5px;margin-left:30px;">Fecha a
                                                            reservar *</label>
                                                        <input class="form-control" type="date" id="fecha_reserva"
                                                            name="fecha_reserva">
                                                    </div>
                                                </div>
                                                <div class="col-xl-4">
                                                    <div class="form-group">
                                                        <label for="hora_desde"
                                                            style="text-align:left;margin:5px;margin-left:30px;">Hora
                                                            desde *</label>
                                                        <input class="form-control" type="time" id="hora_desde"
                                                            name="hora_desde">
                                                    </div>
                                                </div>
                                                <div class="col-xl-4">
                                                    <div class="form-group">
                                                        <label for="hora_hasta"
                                                            style="text-align:left;margin:5px;margin-left:30px;">Hora
                                                            hasta</label>
                                                        <input class="form-control" type="time" id="hora_hasta"
                                                            name="hora_hasta">
                                                    </div>
                                                </div>
                                            </div>

                                        </section><!--.steps-icon-block-->

                                    </form>
                                </div><!--.row-->
                            </div><!--.container-fluid-->
                        </section><!-- box-typical proj-page -->
                    </div>



                    <div class="col-xxl-3 col-lg-12 col-xl-4 col-md-4">
                        <section class="box-typical proj-page">
                            <input type="hidden" id="estado_actual" name="estado_actual">
                            <input type="hidden" id="permisos" name="permisos">

                            <section class="proj-page-section">
                                <ul class="proj-page-actions-list">
                                    <li onclick="guardarSolicitud(2)" id="guardar_datos_btn" style="display:none"><a><i
                                                class="font-icon font-icon-check-square"></i>Enviar solicitud</a></li>
                                    <li><a class="cancelar" href="listarActividadesSociales.php"><i
                                                class="glyphicon glyphicon-trash"></i>
                                            Cancelar</a>
                                    </li>
                                </ul>
                            </section><!--.proj-page-section-->
                        </section><!--.proj-page-->
                    </div>
                </div><!--.row-->
            </div><!--.container-fluid-->
        </div><!--.page-content-->
        <!-- Contenido -->

        <?php require_once ("../MainJs/js.php"); ?>
        <script type="text/javascript" src="sedeSocial.js?v=<?php echo time(); ?>"></script>
        <?php require_once ("../html/footer.php"); ?>
        <script src="../../public/js/lib/input-mask/jquery.mask.min.js"></script>
        <script src="../../public/js/lib/input-mask/input-mask-init.js"></script>
        <script src="../../public/js/lib/carousel/carousel.js"></script>

    </body>

    </html>
    <?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>
<script>
    var cantidadPersonasInput = document.getElementById("cantidad_personas");
    cantidadPersonasInput.addEventListener("focus", function () {
        this.value = "";
    });

    cantidadPersonasInput.addEventListener("blur", function () {
        var enteredNumber = parseInt(this.value);
        if (enteredNumber > 200) {
            this.value = "200";
        }
    });
    // Get the input element
    var horaDesdeInput = document.getElementById("hora_desde");
    var horaHastaInput = document.getElementById("hora_hasta");

    // Add an event listener to listen for changes in the input value
    horaDesdeInput.addEventListener("input", function () {
        // Get the entered time value
        var enteredTime = this.value;

        // Convert the entered time string to a Date object
        var enteredDateTime = new Date("2000-01-01T" + enteredTime);

        // Get the minimum and maximum times as Date objects
        var minTime = new Date("2000-01-01T07:00");
        var maxTime = new Date("2000-01-01T17:00");

        // Check if the entered time is within the range
        if (enteredDateTime < minTime || enteredDateTime > maxTime) {
            // If the entered time is not within the range, reset the input value to the minimum time
            this.value = "07:00";
        }
    });

    // Add an event listener to listen for changes in the input value
    horaHastaInput.addEventListener("input", function () {
        // Get the entered time value
        var enteredTime = this.value;

        // Convert the entered time string to a Date object
        var enteredDateTime = new Date("2000-01-01T" + enteredTime);

        // Get the minimum and maximum times as Date objects
        var minTime = new Date("2000-01-01T13:00");
        var maxTime = new Date("2000-01-01T23:00");

        // Check if the entered time is within the range
        if (enteredDateTime < minTime || enteredDateTime > maxTime) {
            // If the entered time is not within the range, reset the input value to the minimum time
            this.value = "13:00";
        }
    });
</script>