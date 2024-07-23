<?php
require_once ("../../config/conexion.php");
if (isset($_SESSION["usuario_id"])) {

    ?>
    <!doctype html>
    <html class="no-js" lang="en">
    <?php require_once ("html/head.php"); ?>
    <title>APE - certificaciones</title>

    </head>

    <body>

        <!--Preloader-->
        <div id="preloader">
            <div id="loader" class="loader">
                <div class="loader-container">
                    <div class="loader-icon"><img src="" alt="Preloader"></div>
                </div>
            </div>
        </div>
        <!--Preloader-end -->

        <!-- Scroll-top -->
        <button class="scroll__top scroll-to-target" data-target="html">
            <i class="tg-flaticon-arrowhead-up"></i>
        </button>
        <!-- Scroll-top-end-->
        <?php require_once ("html/header.php"); ?>
        <!-- main-area -->
        <main class="main-area fix">

            <!-- breadcrumb-area -->
            <section class="breadcrumb__area breadcrumb__bg" data-background="">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="breadcrumb__content">
                                <h3 class="title">Mi aprendizaje</h3>
                                <nav class="breadcrumb">
                                    <span property="itemListElement" typeof="ListItem">
                                        <a href="../home/">Inicio</a>
                                    </span>
                                    <span class="breadcrumb-separator"><i class="fas fa-angle-right"></i></span>
                                    <a href="listarCertificaciones.php"><span property="itemListElement"
                                            typeof="ListItem">Certificaciones</span></a>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="breadcrumb__shape-wrap">
                    <img src="../../public/skillgro/img/others/breadcrumb_shape01.svg" alt="img" class="alltuchtopdown">
                    <img src="../../public/skillgro/img/others/breadcrumb_shape02.svg" alt="img" data-aos="fade-right"
                        data-aos-delay="300">
                    <img src="../../public/skillgro/img/others/breadcrumb_shape03.svg" alt="img" data-aos="fade-up"
                        data-aos-delay="400">
                    <img src="../../public/skillgro/img/others/breadcrumb_shape04.svg" alt="img" data-aos="fade-down-left"
                        data-aos-delay="400">
                    <img src="../../public/skillgro/img/others/breadcrumb_shape05.svg" alt="img" data-aos="fade-left"
                        data-aos-delay="400">
                </div>
            </section>
            <!-- breadcrumb-area-end -->

            <!-- all-courses -->
            <section class="all-courses-area section-py-120">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-3 col-lg-4 order-2 order-lg-0">
                            <aside class="courses__sidebar">
                                <div class="courses-widget">
                                    <?php
                                    require "../../models/Certificacion.php";
                                    $categorias = Certificacion::get_categorias_certificaciones($_SESSION['usuario_id']);
                                    ?>
                                    <h4 class="widget-title">Categorías</h4>
                                    <div class="courses-cat-list">
                                        <ul class="list-wrap">
                                            <li>
                                                <div class="form-check">
                                                    <input class="form-check-input categoria" onclick="allSelection()"
                                                        type="checkbox" value="all" id="cat_all">
                                                    <label class="form-check-label" for="cat_all">Todos (
                                                        <?php echo $categorias[0]["cantidad_total_cursos"] ?>)
                                                    </label>
                                                </div>
                                            </li>
                                            <?php
                                            foreach ($categorias as $categoria) {
                                                ?>
                                                <li>
                                                    <div class="form-check">
                                                        <input class="form-check-input categoria" type="checkbox"
                                                            value="<?php echo $categoria["nombre"] ?>"
                                                            id="cat_<?php echo $categoria["id_categoria"] ?>">
                                                        <label class="form-check-label"
                                                            for="cat_<?php echo $categoria["id_categoria"] ?>">
                                                            <?php echo $categoria["nombre"] ?> (
                                                            <?php echo $categoria["cantidad_cursos_categoria"] ?>)
                                                        </label>
                                                    </div>
                                                </li>
                                                <?php
                                            }
                                            ?>

                                        </ul>
                                    </div>
                                </div>
                                <!-- <div class="courses-widget">
                                    <h4 class="widget-title">Mis certificaciones</h4>
                                    <div class="courses-cat-list">
                                        <?php
                                        // require_once "../../models/Certificacion.php";
                                        // $cursos_estados = Certificacion::get_mis_certificaciones_estados($_SESSION["usuario_id"]);
                                        ?>
                                        <ul class="list-wrap">
                                            <li>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="" id="lang_1">
                                                    <label class="form-check-label" for="lang_1">En curso (
                                                        <?php 
                                                            // echo $cursos_estados["cantidad_en_curso"] 
                                                            ?>)
                                                    </label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="" id="lang_1">
                                                    <label class="form-check-label" for="lang_1">Finalizados (
                                                        <?php 
                                                            // echo $cursos_estados["cantidad_finalizados"]
                                                             ?>)
                                                    </label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div> -->
                            </aside>
                        </div>
                        <div class="col-xl-9 col-lg-8">
                            <div class="courses-top-wrap courses-top-wrap">
                                <div class="row align-items-center">
                                    <div class="col-md-5">
                                        <div class="courses-top-left">
                                            <p id="result-count"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div
                                            class="d-flex justify-content-center justify-content-md-end align-items-center flex-wrap">

                                            <ul class="nav nav-tabs courses__nav-tabs" id="myTab" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link active" id="grid-tab" data-bs-toggle="tab"
                                                        data-bs-target="#grid" type="button" role="tab" aria-controls="grid"
                                                        aria-selected="true">
                                                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M6 1H2C1.44772 1 1 1.44772 1 2V6C1 6.55228 1.44772 7 2 7H6C6.55228 7 7 6.55228 7 6V2C7 1.44772 6.55228 1 6 1Z"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path
                                                                d="M16 1H12C11.4477 1 11 1.44772 11 2V6C11 6.55228 11.4477 7 12 7H16C16.5523 7 17 6.55228 17 6V2C17 1.44772 16.5523 1 16 1Z"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path
                                                                d="M6 11H2C1.44772 11 1 11.4477 1 12V16C1 16.5523 1.44772 17 2 17H6C6.55228 17 7 16.5523 7 16V12C7 11.4477 6.55228 11 6 11Z"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path
                                                                d="M16 11H12C11.4477 11 11 11.4477 11 12V16C11 16.5523 11.4477 17 12 17H16C16.5523 17 17 16.5523 17 16V12C17 11.4477 16.5523 11 16 11Z"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $get_all = true;
                            ?>
                            <div class="tab-content" id="myTabContent">
                                <?php
                                if ($get_all) {
                                    $cursos = Certificacion::get_mi_aprendizaje_certificaciones($_SESSION["usuario_id"]);
                                }
                                ?>
                                <div class="tab-pane fade show active" id="grid" role="tabpanel" aria-labelledby="grid-tab">
                                    <div id="courses_grid_wrap"
                                        class="row courses__grid-wrap row-cols-1 row-cols-xl-3 row-cols-lg-2 row-cols-md-2 row-cols-sm-1">
                                        <?php
                                        foreach ($cursos as $curso) {
                                            ?>

                                            <div class="col">
                                                <div class="courses__item shine__animate-item">
                                                    <div class="courses__item-thumb">
                                                        <a href="course-details.html" class="shine__animate-link">
                                                            <img src="../<?php echo $curso["imagen_portada"] ?>" alt="img">
                                                        </a>
                                                    </div>
                                                    <div class="courses__item-content">
                                                        <ul class="courses__item-meta list-wrap">
                                                            <li class="courses__item-tag">
                                                                <a href="#">
                                                                    <?php echo $curso["nombre_categoria"] ?>
                                                                </a>
                                                            </li>
                                                            <li class="avg-rating"><i class="fas fa-star"></i> (
                                                                <?php echo $curso["validacion"] ?> Valoración)
                                                            </li>
                                                        </ul>
                                                        <h5 class="title"><a href="course-details.html">
                                                                <?php echo $curso["nombre_curso"] ?>
                                                            </a>
                                                        </h5>
                                                        <p class="author">Por <a href="#">
                                                                <?php echo $curso["instructor"] ?>
                                                            </a></p>
                                                        <div class="courses__item-bottom">
                                                            <!-- Encriptar el id -->

                                                            <?php
                                                            $key = "mi_key_secret";
                                                            $cipher = "aes-256-cbc";
                                                            $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
                                                            $cifrado = openssl_encrypt($curso["curso_id"], $cipher, $key, OPENSSL_RAW_DATA, $iv);
                                                            $textoCifrado = base64_encode($iv . $cifrado);

                                                            ?>
                                                            <div class="button">
                                                                <a href="curso.php?IDCURSO='<?php echo $textoCifrado ?>'">
                                                                    <span class="text">Ir al aula virtual</span>
                                                                    <i class="flaticon-arrow-right"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <nav class="pagination__wrap mt-30">
                                        <ul class="list-wrap">
                                            <li class="active"><a href="#">1</a></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- all-courses-end -->

        </main>
        <!-- main-area-end -->



        <!-- footer-area -->
        <?php require_once ("html/footer.php"); ?>
        <!-- footer-area-end -->


        <!-- JS here -->
        <?php require_once ("html/js.php"); ?>
    </body>
    <script>
        var tipo_solicitud = "";
        updateResultCount();
        console.log(tipo_solicitud);
        if (window.location.href.indexOf('listarCertificaciones') !== -1) {
            tipo_solicitud = 'CERT';
        }
        else {
            tipo_solicitud = 'CURSO';
        }
        document.addEventListener('DOMContentLoaded', function () {
            // Get all checkbox elements
            const checkboxesCategories = document.querySelectorAll('.categoria');

            // Add event listeners to each checkbox
            checkboxesCategories.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    filterCategories();
                });
            });

            function filterCategories() {
                // Get checked categories
                const selectedCategories = [];
                checkboxesCategories.forEach(checkbox => {
                    if (checkbox.checked && checkbox.value !== 'all') {
                        selectedCategories.push(checkbox.value);
                    }
                });



                // Your code to filter the items based on selectedCategories
                displayFilteredCategories(selectedCategories);
            }

            function displayAllCategories() {
                // Implement this function to show all categories
                console.log('Displaying all categories');
                $("#myTabContent").load(" #myTabContent" + " > *");
            }
            function displayFilteredCategories(categories) {

                console.log('tipo_solicitud : ', tipo_solicitud);
                $.ajax({
                    url: '../../controller/certificacion.php?op=cursosFiltrados',
                    type: 'POST',
                    data: { categories: categories, tipo_solicitud: tipo_solicitud },
                    success: function (response) {
                        $('#courses_grid_wrap').html(response);
                        updateResultCount();
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching filtered categories:', error);
                    }
                });
            }
        });
        function allSelection() {
            // Get all checkbox elements
            const checkboxesCategories = document.querySelectorAll('.form-check-input');

            // If 'Todos' is checked, select all categories
            if (document.getElementById('cat_all').checked) {
                checkboxesCategories.forEach(checkbox => {
                    checkbox.checked = true;
                });
                // Your code to display all items
                <?php $get_all = true; ?>
                displayAllCategories();
                return;
            }
            else {
                checkboxesCategories.forEach(checkbox => {
                    checkbox.checked = false;
                });
                <?php $get_all = false; ?>
                return;
            }
            $("#myTabContent").load(" #myTabContent" + " > *");
            updateResultCount();
        }
        function updateResultCount() {
            // Select all div elements with the class 'shine__animate-item'
            const shineAnimateItems = document.querySelectorAll('div.shine__animate-item');

            // Get the count of the selected elements
            const count = shineAnimateItems.length;

            // Select the p element by its ID
            const resultCountElement = document.getElementById('result-count');

            // Update the content of the p element
            resultCountElement.textContent = `Mostrando ${count} ${count === 1 ? 'resultado' : 'resultados'}`;
        }
    </script>

    </html>
    <?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>