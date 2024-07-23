
<?php require_once ('../html/head.php'); ?>
<style>
    .team-form {
        display: inline-block;
        position: relative;
    }

    .location {
        display: inline-block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 20ch;
        /* Show only 20 characters initially */
        vertical-align: bottom;
    }

    .location.expanded {
        white-space: normal;
        max-width: none;
    }

    .read-more {
        cursor: pointer;
        color: blue;
        text-decoration: underline;
        margin-left: 5px;
        vertical-align: bottom;
    }
</style>
</head>

<body class="rbt-header-sticky">

    <!-- Start Header Area -->
    <?php require_once ('../html/header.php'); ?>
    <div class="rbt-cart-side-menu">
        <div class="inner-wrapper">
            <div class="inner-top">
                <div class="content">
                    <div class="title">
                        <h4 class="title mb--0">Your shopping cart</h4>
                    </div>
                    <div class="rbt-btn-close" id="btn_sideNavClose">
                        <button class="minicart-close-button rbt-round-btn"><i class="feather-x"></i></button>
                    </div>
                </div>
            </div>
            <nav class="side-nav w-100">
                <ul class="rbt-minicart-wrapper">
                    <li class="minicart-item">
                        <div class="thumbnail">
                            <a href="#">
                                <img src="assets/images/product/1.jpg" alt="Product Images">
                            </a>
                        </div>
                        <div class="product-content">
                            <h6 class="title"><a href="single-product.html">Miracle Morning</a></h6>

                            <span class="quantity">1 * <span class="price">$22</span></span>
                        </div>
                        <div class="close-btn">
                            <button class="rbt-round-btn"><i class="feather-x"></i></button>
                        </div>
                    </li>

                    <li class="minicart-item">
                        <div class="thumbnail">
                            <a href="#">
                                <img src="assets/images/product/7.jpg" alt="Product Images">
                            </a>
                        </div>
                        <div class="product-content">
                            <h6 class="title"><a href="single-product.html">Happy Strong</a></h6>

                            <span class="quantity">1 * <span class="price">$30</span></span>
                        </div>
                        <div class="close-btn">
                            <button class="rbt-round-btn"><i class="feather-x"></i></button>
                        </div>
                    </li>

                    <li class="minicart-item">
                        <div class="thumbnail">
                            <a href="#">
                                <img src="assets/images/product/3.jpg" alt="Product Images">
                            </a>
                        </div>
                        <div class="product-content">
                            <h6 class="title"><a href="single-product.html">Rich Dad Poor Dad</a></h6>

                            <span class="quantity">1 * <span class="price">$50</span></span>
                        </div>
                        <div class="close-btn">
                            <button class="rbt-round-btn"><i class="feather-x"></i></button>
                        </div>
                    </li>

                    <li class="minicart-item">
                        <div class="thumbnail">
                            <a href="#">
                                <img src="assets/images/product/4.jpg" alt="Product Images">
                            </a>
                        </div>
                        <div class="product-content">
                            <h6 class="title"><a href="single-product.html">Momentum Theorem</a></h6>

                            <span class="quantity">1 * <span class="price">$50</span></span>
                        </div>
                        <div class="close-btn">
                            <button class="rbt-round-btn"><i class="feather-x"></i></button>
                        </div>
                    </li>
                </ul>
            </nav>

            <div class="rbt-minicart-footer">
                <hr class="mb--0">
                <div class="rbt-cart-subttotal">
                    <p class="subtotal"><strong>Subtotal:</strong></p>
                    <p class="price">$121</p>
                </div>
                <hr class="mb--0">
                <div class="rbt-minicart-bottom mt--20">
                    <div class="view-cart-btn">
                        <a class="rbt-btn btn-border icon-hover w-100 text-center" href="#">
                            <span class="btn-text">View Cart</span>
                            <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                        </a>
                    </div>
                    <div class="checkout-btn mt--20">
                        <a class="rbt-btn btn-gradient icon-hover w-100 text-center" href="#">
                            <span class="btn-text">Checkout</span>
                            <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- End Side Vav -->
    <a class="close_side_menu" href="javascript:void(0);"></a>
    <div class="rbt-page-banner-wrapper">
        <!-- Start Banner BG Image  -->
        <div class="rbt-banner-image"></div>
        <!-- End Banner BG Image  -->
        <div class="rbt-banner-content">

            <!-- Start Banner Content Top  -->
            <div class="rbt-banner-content-top">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <!-- Start Breadcrumb Area  -->
                            <ul class="page-list">
                                <li class="rbt-breadcrumb-item"><a href="../../index.php">Inicio</a></li>
                                <li>
                                    <div class="icon-right"><i class="feather-chevron-right"></i></div>
                                </li>
                                <li class="rbt-breadcrumb-item active">TEI 2022</li>
                            </ul>
                            <!-- End Breadcrumb Area  -->

                            <div class=" title-wrapper">
                                <h1 class="title mb--0">TEI 2022</h1>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- End Banner Content Top  -->

            <!-- Start Course Top  -->
            <!-- End Course Top  -->
        </div>
    </div>
    <div class="rbt-shop-area rbt-section-overlayping-top rbt-section-gapBottom">

        <div class="container">
                <h4>RESOLUCIONES</h4>

                <div class="row g-5">
                <!-- Start Single Product  -->
                <div class="col-lg-3 col-md-4 col-12">
                        <div class="rbt-default-card style-three rbt-hover">
                        <div class="inner">
                                <div class="content pt--0 pb--10">
                                        <h6 class=""><a
                                                href="https://ape.org.py/wp-content/uploads/2022/04/Resolucion-N%C2%B01-2022-TEI.pdf">Resolución T.E.I. A.P.E. N°: 1 / 2022</a></h6>
                                </div>
                                <div class="thumbnail"><a
                                        style="display: flex; justify-content: center; align-items: center;"
                                        href="https://ape.org.py/wp-content/uploads/2022/04/Resolucion-N%C2%B01-2022-TEI.pdf"><img
                                        style="max-width:20%" src="../../assets/assets-main/images/logo/pdf-file.png"
                                        alt="Histudy Book Image"></a></div>
                                </div>
                        </div>
                </div>
                <!-- End Single Product  -->

                <!-- Start Single Product  -->
                <div class="col-lg-3 col-md-4 col-12">
                        <div class="rbt-default-card style-three rbt-hover">
                        <div class="inner">
                                <div class="content pt--0 pb--10">
                                <h6 class=""><a
                                        href="https://ape.org.py/wp-content/uploads/2022/04/Resolucion-N%C2%B02-2022-del-TEI_4.pdf">Resolución T.E.I. A.P.E. N°: 2 / 2022</a></h6>


                                </div>
                                <div class="thumbnail"><a
                                        style="display: flex; justify-content: center; align-items: center;"
                                        href="https://ape.org.py/wp-content/uploads/2022/04/Resolucion-N%C2%B02-2022-del-TEI_4.pdf"><img
                                        style="max-width:20%" src="../../assets/assets-main/images/logo/pdf-file.png"
                                        alt="Histudy Book Image"></a></div>
                        </div>
                        </div>
                </div>
                <!-- End Single Product  -->

                <!-- Start Single Product  -->
                <div class="col-lg-3 col-md-4 col-12">
                        <div class="rbt-default-card style-three rbt-hover">
                        <div class="inner">
                                <div class="content pt--0 pb--10">
                                <h6 class=""><a
                                        href="https://ape.org.py/wp-content/uploads/2022/04/Resolucion-N%C2%B03-del-TEI_3.pdf">Resolución T.E.I. A.P.E. N°: 3 / 2022</a></h6>


                                </div>
                                <div class="thumbnail"><a
                                        style="display: flex; justify-content: center; align-items: center;"
                                        href="https://ape.org.py/wp-content/uploads/2022/04/Resolucion-N%C2%B03-del-TEI_3.pdf"><img
                                        style="max-width:20%" src="../../assets/assets-main/images/logo/pdf-file.png"
                                        alt="Histudy Book Image"></a></div>
                        </div>
                        </div>
                </div>
                <!-- End Single Product  -->

                <!-- Start Single Product  -->
                <div class="col-lg-3 col-md-4 col-12">
                        <div class="rbt-default-card style-three rbt-hover">
                        <div class="inner">
                                <div class="content pt--0 pb--10">
                                <h6 class=""><a
                                        href="https://ape.org.py/wp-content/uploads/2022/04/Resolucion-N%C2%B0-4-del-TEI.pdf">Resolución T.E.I. A.P.E. N°: 4 / 2022</a></h6>


                                </div>
                                <div class="thumbnail"><a
                                        style="display: flex; justify-content: center; align-items: center;"
                                        href="https://ape.org.py/wp-content/uploads/2022/04/Resolucion-N%C2%B0-4-del-TEI.pdf"><img
                                        style="max-width:20%" src="../../assets/assets-main/images/logo/pdf-file.png"
                                        alt="Histudy Book Image"></a></div>
                        </div>
                        </div>
                </div>
                <!-- End Single Product  -->

                <!-- Start Single Product  -->
                <div class="col-lg-3 col-md-4 col-12">
                        <div class="rbt-default-card style-three rbt-hover">
                        <div class="inner">
                                <div class="content pt--0 pb--10">
                                <h6 class=""><a
                                        href="https://ape.org.py/wp-content/uploads/2022/05/Resolucion-N%C2%B05-Designacion-de-los-Miembros-de-Mesas-Receptoras-de-votos.pdf">Resolución T.E.I. A.P.E. N°: 5 / 2022</a></h6>


                                </div>
                                <div class="thumbnail"><a
                                        style="display: flex; justify-content: center; align-items: center;"
                                        href="https://ape.org.py/wp-content/uploads/2022/05/Resolucion-N%C2%B05-Designacion-de-los-Miembros-de-Mesas-Receptoras-de-votos.pdf"><img
                                        style="max-width:20%" src="../../assets/assets-main/images/logo/pdf-file.png"
                                        alt="Histudy Book Image"></a></div>
                        </div>
                        </div>
                </div>
                <!-- End Single Product  -->

                <!-- Start Single Product  -->
                <div class="col-lg-3 col-md-4 col-12">
                        <div class="rbt-default-card style-three rbt-hover">
                        <div class="inner">
                                <div class="content pt--0 pb--10">
                                <h6 class=""><a
                                        href="https://ape.org.py/wp-content/uploads/2022/05/Resolucion-N%C2%B06-del-TEI.pdf">Resolución T.E.I. A.P.E. N°: 6 / 2022</a></h6>


                                </div>
                                <div class="thumbnail"><a
                                        style="display: flex; justify-content: center; align-items: center;"
                                        href="https://ape.org.py/wp-content/uploads/2022/05/Resolucion-N%C2%B06-del-TEI.pdf"><img
                                        style="max-width:20%" src="../../assets/assets-main/images/logo/pdf-file.png"
                                        alt="Histudy Book Image"></a></div>
                        </div>
                        </div>
                </div>
                <!-- End Single Product  -->

                <!-- Start Single Product  -->
                <div class="col-lg-3 col-md-4 col-12">
                        <div class="rbt-default-card style-three rbt-hover">
                        <div class="inner">
                                <div class="content pt--0 pb--10">
                                <h6 class=""><a
                                        href="https://ape.org.py/wp-content/uploads/2022/05/Resolucion-N%C2%B07-Proclamacion-de-Autoridades-Electas-2022.pdf">Resolución T.E.I. A.P.E. N°: 7 / 2022</a></h6>


                                </div>
                                <div class="thumbnail"><a
                                        style="display: flex; justify-content: center; align-items: center;"
                                        href="https://ape.org.py/wp-content/uploads/2022/05/Resolucion-N%C2%B07-Proclamacion-de-Autoridades-Electas-2022.pdf"><img
                                        style="max-width:20%" src="../../assets/assets-main/images/logo/pdf-file.png"
                                        alt="Histudy Book Image"></a></div>
                        </div>
                        </div>
                </div>
                <!-- End Single Product  -->
                </div>
            <!-- <div class="row">
                <div class="col-lg-12 mt--60">
                    <nav>
                        <ul class="rbt-pagination">
                            <li><a href="#" aria-label="Previous"><i class="feather-chevron-left"></i></a></li>
                            <li><a href="#">1</a></li>
                            <li class="active"><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#" aria-label="Next"><i class="feather-chevron-right"></i></a></li>
                        </ul>
                    </nav>
                </div>
            </div> -->
        </div>

        <div class="container" style="padding-top:3%;">
                <h4>PRE – PADRÓN SOCIOS APE – 2022</h4>

                <div class="row g-5">
                <!-- Start Single Product  -->
                <div class="col-lg-3 col-md-4 col-12">
                        <div class="rbt-default-card style-three rbt-hover">
                        <div class="inner">
                                <div class="content pt--0 pb--10">
                                <h6 class=""><a
                                        href="https://ape.org.py/pre-padron-socios-ape-2022/">VER PRE – PADRÓN SOCIOS APE – 2022</a></h6>
                                </div>
                        </div>
                        </div>
                </div>
                <!-- End Single Product  -->
                </div>
            <!-- <div class="row">
                <div class="col-lg-12 mt--60">
                    <nav>
                        <ul class="rbt-pagination">
                            <li><a href="#" aria-label="Previous"><i class="feather-chevron-left"></i></a></li>
                            <li><a href="#">1</a></li>
                            <li class="active"><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#" aria-label="Next"><i class="feather-chevron-right"></i></a></li>
                        </ul>
                    </nav>
                </div>
            </div> -->
        </div>
        
        <div class="container" style="padding-top:3%;">
                <h4>PADRÓN GENERAL APE – 2022</h4>

                <div class="row g-5">
                <!-- Start Single Product  -->
                <div class="col-lg-3 col-md-4 col-12">
                        <div class="rbt-default-card style-three rbt-hover">
                        <div class="inner">
                                <div class="content pt--0 pb--10">
                                <h6 class=""><a
                                        href="https://ape.org.py/padron-general-de-socios-ape-2022/">VER PADRÓN GENERAL APE – 2022</a></h6>
                                </div>
                        </div>
                        </div>
                </div>
                <!-- End Single Product  -->
                </div>
            <!-- <div class="row">
                <div class="col-lg-12 mt--60">
                    <nav>
                        <ul class="rbt-pagination">
                            <li><a href="#" aria-label="Previous"><i class="feather-chevron-left"></i></a></li>
                            <li><a href="#">1</a></li>
                            <li class="active"><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#" aria-label="Next"><i class="feather-chevron-right"></i></a></li>
                        </ul>
                    </nav>
                </div>
            </div> -->
        </div>

        <div class="container" style="padding-top:3%;">
                <h4>PRE – PADRÓN SOCIOS APE – 2022</h4>

                <div class="row g-5">
                <!-- Start Single Product  -->
                <div class="col-lg-3 col-md-4 col-12">
                        <div class="rbt-default-card style-three rbt-hover">
                        <div class="inner">
                                <div class="content pt--0 pb--10">
                                        <h6 class=""><a
                                        href="https://ape.org.py/wp-content/uploads/2022/04/Calendario-Electoral-APE.pdf">PDF – CALENDARIO ELECTORAL 2022</a></h6>
                                </div>
                                <div class="thumbnail"><a
                                        style="display: flex; justify-content: center; align-items: center;"
                                        href="https://ape.org.py/wp-content/uploads/2022/04/Calendario-Electoral-APE.pdf"><img
                                        style="max-width:20%" src="../../assets/assets-main/images/logo/pdf-file.png"
                                        alt="Histudy Book Image"></a>
                                </div>
                        </div>
                        </div>
                </div>
                <!-- End Single Product  -->
                </div>
            <!-- <div class="row">
                <div class="col-lg-12 mt--60">
                    <nav>
                        <ul class="rbt-pagination">
                            <li><a href="#" aria-label="Previous"><i class="feather-chevron-left"></i></a></li>
                            <li><a href="#">1</a></li>
                            <li class="active"><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#" aria-label="Next"><i class="feather-chevron-right"></i></a></li>
                        </ul>
                    </nav>
                </div>
            </div> -->
        </div>

        <div class="container" style="padding-top:3%;">
                <h4>PROVIDENCIAS DEL TEI</h4>

                <div class="row g-5">
                <!-- Start Single Product  -->
                <div class="col-lg-3 col-md-4 col-12">
                        <div class="rbt-default-card style-three rbt-hover">
                        <div class="inner">
                                <div class="content pt--0 pb--10">
                                <h6 class=""><a
                                        href="http://ape.org.py/wp-content/uploads/2022/04/IMG-20220401-WA0010.jpg">01- 1 de abril de 2022</a></h6>
                                </div>
                        </div>
                        </div>
                </div>
                <!-- End Single Product  -->

                                <!-- Start Single Product  -->
                                <div class="col-lg-3 col-md-4 col-12">
                        <div class="rbt-default-card style-three rbt-hover">
                        <div class="inner">
                                <div class="content pt--0 pb--10">
                                <h6 class=""><a
                                        href="http://ape.org.py/wp-content/uploads/2022/04/Providenciade-fecha-22-de-abril-2022.pdf">02- 22 de abril de 2022</a></h6>
                                </div>
                        </div>
                        </div>
                </div>
                <!-- End Single Product  -->
                </div>
            <!-- <div class="row">
                <div class="col-lg-12 mt--60">
                    <nav>
                        <ul class="rbt-pagination">
                            <li><a href="#" aria-label="Previous"><i class="feather-chevron-left"></i></a></li>
                            <li><a href="#">1</a></li>
                            <li class="active"><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#" aria-label="Next"><i class="feather-chevron-right"></i></a></li>
                        </ul>
                    </nav>
                </div>
            </div> -->
        </div>
    </div>

    <!-- End Page Wrapper Area -->
    <div class="rbt-progress-parent">
        <svg class="rbt-back-circle svg-inner" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
    </div>

    <?php require_once ('../html/footer.php'); ?>
    <?php require_once ('../html/js.php'); ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var readMoreLinks = document.querySelectorAll('.read-more');
            readMoreLinks.forEach(function (readMore) {
                readMore.addEventListener('click', function (event) {
                    var locationSpan = this.previousElementSibling;
                    locationSpan.classList.toggle('expanded');
                    this.textContent = locationSpan.classList.contains('expanded') ? 'Leer menos' : 'Leer más';
                });
            });
        });
    </script>

</body>

</html>