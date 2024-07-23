<style>
    /* Style the dropdown */
    .dropdown-busqueda {
        position: relative;
        display: inline-block;
    }

    .dropdown-content-busqueda {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        z-index: 1;
    }

    .dropdown-item-busqueda {
        padding: 12px;
        cursor: pointer;
    }

    /* Clase para mantener la imagen rectangular */
    .rectangular-preview img {
        border-radius: 0 !important;
        /* Elimina el redondeado */
        width: 150px;
        /* Ajusta el ancho deseado */
        height: auto;
        /* Ajusta la altura manteniendo la proporción */
    }

    /* Ocultar el campo de entrada del archivo */
    input[type="file"].nuevaImagen {
        display: none;
    }

    /* Estilizar el botón personalizado */
    .custom-file-upload {
        display: inline-block;
        padding: 6px 12px;
        cursor: pointer;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
    }

    .custom-file-upload:hover {
        background-color: #0056b3;
    }

    .d-flex {
        display: flex;
    }

    .align-items-stretch {
        align-items: stretch;
    }

    .flex-column {
        flex
    }
</style>
<header class="site-header">
    <div class="container-fluid">
        <a href="../home/<?php echo $_SESSION["inicio"]; ?>" class="site-logo">
            <img src="../../public/img/logo_ape.jpg">
        </a>

        <button id="show-hide-sidebar-toggle" class="show-hide-sidebar">
            <span>toggle menu</span>
        </button>

        <button class="hamburger hamburger--htla">
            <span>toggle menu</span>
        </button>

        <div class="site-header-content">
            <div class="site-header-content-in">
                <div class="site-header-shown">
                    <?php
                    require_once ('mensajes.php');
                    ?>

                    <?php
                    require_once ('notificaciones.php');
                    ?>
                    <div class="dropdown user-menu">
                        <button class="dropdown-toggle" id="dd-user-menu" type="button" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <?php
                            require ("../../models/Publicacion.php");

                            $foto_perfil = Publicacion::get_foto_perfil($_SESSION["usuario_id"]);

                            ?>
                            <img src="../<?php if ($foto_perfil != null && $foto_perfil != '') {
                                echo $foto_perfil;
                            } else {
                                echo "../assets/assets-main/images/icons/user2.png";
                            }
                            ?>" alt="">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dd-user-menu">
                            <a class="dropdown-item" href="../Publicaciones/miPerfil.php"><span
                                    class="font-icon glyphicon glyphicon-user"></span>
                                <?php echo $_SESSION["nombre"] ?>
                                <?php echo $_SESSION["apellido"] ?>
                            </a>
                            <a class="dropdown-item" href="#"><span
                                    class="font-icon glyphicon glyphicon-question-sign"></span>Ayuda</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="../Logout/logout.php"><span
                                    class="font-icon glyphicon glyphicon-log-out"></span>Cerrar Sesion</a>
                        </div>
                    </div>
                </div>

                <!-- <div class="mobile-menu-right-overlay"></div> -->

                <input type="hidden" id="user_idx" value="<?php echo $_SESSION["usuario_id"] ?>"><!-- ID del Usuario-->


                <div class="site-header-search-container">
                    <form class="site-header-search closed">
                        <!-- <input type="text" placeholder="Buscar profesional"/> -->
                        <input class="search-input" type="text" placeholder="Buscar..."
                            oninput="performSearch(this.value)">
                        <div class="dropdown-busqueda" id="searchResults" style="display:block;">
                        </div>
                        <button type="button">
                            <span class="font-icon-search"></span>
                        </button>
                        <div class="overlay"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
<script>
    function performSearch(usuario_buscado) {
        // You can perform actions based on the search query here
        $.post("../../controller/Publicacion.php?op=usuariosSistema", { usuario_buscado: usuario_buscado }, function (data) {
            // Parse the JSON response
            var userData = JSON.parse(data);

            // Get the dropdown container
            var dropdown = document.getElementById("searchResults");
            // Clear previous results
            dropdown.innerHTML = "";

            // Create and append dropdown items
            userData.forEach(function (user) {

                var item = document.createElement("div");
                item.className = "dropdown-item-busqueda";
                item.textContent = user.usuario_nombre;
                // Add an event listener to handle item selection
                item.addEventListener("click", function () {
                    // Set the selected user in the search input
                    document.querySelector(".search-input").value = user.usuario_nombre;
                    // Clear the dropdown
                    dropdown.innerHTML = "";
                });
                dropdown.appendChild(item);
                console.log(item);
            });

            // Show the dropdown
            dropdown.style.display = "block";
        });
        // Close dropdown when clicking outside of it
        $(document).on("click", function (event) {
            var dropdown = $("#searchResults");
            if (!$(event.target).closest(".site-header-search-container").length) {
                dropdown.hide();
            }
        });
    }
</script>