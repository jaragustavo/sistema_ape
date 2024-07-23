<?php
// Obtener la ruta relativa del directorio actual
$root_path = "/sistema_ape/portal/";
$root_path_main = "/sistema_ape/";
?>
<header class="rbt-header rbt-header-10">
    <div class="rbt-sticky-placeholder"></div>
    <!-- Start Header Top  -->
    <div
        class="rbt-header-top rbt-header-top-1 header-space-betwween bg-not-transparent bg-color-darker top-expended-activation">
        <div class="container-fluid">
            <div class="top-expended-wrapper">
                <div class="top-expended-inner rbt-header-sec align-items-center ">
                    <div class="rbt-header-sec-col rbt-header-left d-none d-xl-block">
                        <div class="rbt-header-content">
                            <!-- Start Header Information List  -->
                            <div class="header-info">
                                <ul class="rbt-information-list">
                                    <li>
                                        <a href="#"><i class="feather-globe"></i><span class="d-none d-xxl-block">EEUU
                                                N° 1063 c/ República de Colombia</span></a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="feather-phone"></i>0983-859-952 / 021-224-940</a>
                                    </li>
                                </ul>
                            </div>
                            <!-- End Header Information List  -->
                        </div>
                    </div>
                    <div class="rbt-header-sec-col rbt-header-center">
                        <div class="rbt-header-content justify-content-start justify-content-xl-center">
                            <div class="header-info">
                                <div class="rbt-header-top-news">
                                    <div class="inner">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="rbt-header-sec-col rbt-header-right mt_md--10 mt_sm--10">
                        <div class="rbt-header-content justify-content-start justify-content-lg-end">
                            <div class="header-info d-none d-xl-block">
                                <ul class="social-share-transparent">
                                    <li>
                                        <a href="https://www.facebook.com/apeparaguaypy" target="_blank"><i
                                                class="fab fa-facebook-f"></i></a>
                                    </li>
                                    <li>
                                        <a href="https://x.com/apeparaguaypy" target="_blank"><i
                                                class="fab fa-twitter"></i></a>
                                    </li>
                                    <li>
                                        <a href="https://www.youtube.com/@asociacionparaguayadeenfer6720"
                                            target="_blank"><i class="fab fa-youtube"></i></a>
                                    </li>
                                    <li>
                                        <a href="https://www.instagram.com/apeparaguaypy/" target="_blank"><i
                                                class="fab fa-instagram"></i></a>
                                    </li>
                                </ul>
                            </div>

                            <div class="rbt-separator d-none d-xl-block"></div>




                        </div>
                    </div>
                </div>
                <div class="header-info">
                    <div class="top-bar-expended d-block d-lg-none">
                        <button class="topbar-expend-button rbt-round-btn"><i class="feather-plus"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Header Top  -->
    <div class="rbt-header-wrapper header-space-betwween header-sticky">
        <div class="container-fluid">
            <div class="mainbar-row rbt-navigation-center align-items-center">
                <div class="header-left rbt-header-content">
                    <div class="header-info">
                        <div class="logo">
                            <a href="<?php echo $root_path_main ?>index.php">
                                <img src="<?php echo $root_path_main ?>assets/assets-main/images/logo/logo.png"
                                    alt="Education Logo Images">
                            </a>
                        </div>
                    </div>
                    <div class="header-info">
                        <div class="rbt-category-menu-wrapper">
                            <div class="rbt-category-btn rbt-side-offcanvas-activation">
                                <div class="rbt-offcanvas-trigger md-size icon">
                                    <span class="d-none d-xl-block">
                                        <i class="feather-grid"></i>
                                    </span>
                                    <i title="Category" class="feather-grid d-block d-xl-none"></i>
                                </div>
                                <span class="category-text d-none d-xl-block">APE</span>
                            </div>

                            <div class="category-dropdown-menu d-none d-xl-block">
                                <div class="category-menu-item">
                                    <div class="rbt-vertical-nav">
                                        <ul class="rbt-vertical-nav-list-wrapper vertical-nav-menu">
                                            <li class="vertical-nav-item active">
                                                <a href="<?php echo $root_path ?>APE/misionVision.php">Misión y
                                                    Visión</a>
                                            </li>
                                            <li class="vertical-nav-item">
                                                <a href="<?php echo $root_path ?>APE/quienesSomos.php">Quiénes somos</a>
                                            </li>
                                            <li class="vertical-nav-item">
                                                <a href="<?php echo $root_path ?>APE/presidenta.php">Presidenta</a>
                                            </li>
                                            <li class="vertical-nav-item">
                                                <a href="<?php echo $root_path ?>APE/juntaDirectiva.php">Junta
                                                    Directiva</a>
                                            </li>
                                            <li class="vertical-nav-item">
                                                <a href="<?php echo $root_path ?>APE/filiales.php">Filiales</a>
                                            </li>
                                            <li class="vertical-nav-item">
                                                <a href="<?php echo $root_path ?>APE/personalAdministrativo.php">Personal
                                                    Administrativo</a>
                                            </li>
                                            <li class="vertical-nav-item">
                                                <a href="<?php echo $root_path_main ?>docs/website/memoria.pdf"
                                                    target="_blank">Memoria
                                                    2018-2021</a>
                                            </li>
                                        </ul>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="rbt-main-navigation d-none d-xl-block">
                    <nav class="mainmenu-nav">
                        <ul class="mainmenu">
                            <li class="with-megamenu has-menu-child-item">
                                <a href="#">Boletines <i class="feather-chevron-down"></i></a>
                                <!-- Start Mega Menu  -->
                                <div class="rbt-megamenu grid-item-2">
                                    <div class="wrapper">
                                        <div class="row row--15">
                                            <ul class="mega-menu-item">
                                                <li><a href="<?php echo $root_path ?>Boletines/boletinApe.php">BOLETÍN
                                                        APE - Edición
                                                        Publicada</a></li>
                                                <li><a
                                                        href="http://ape.org.py/wp-content/uploads/2023/10/Censo-Enfermeria_Ene-05.pdf">CENSO</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Mega Menu  -->
                            </li>

                            <li class="with-megamenu has-menu-child-item">
                                <a href="#">Biblioteca <i class="feather-chevron-down"></i></a>
                                <!-- Start Mega Menu  -->
                                <div class="rbt-megamenu grid-item-2">
                                    <div class="wrapper">
                                        <div class="row row--15">
                                            <ul class="mega-menu-item">
                                                <li><a
                                                        href="<?php echo $root_path ?>Biblioteca/imagenes.php">Imagenes</a>
                                                </li>
                                                <li><a href="https://www.youtube.com/channel/UChg1Wpyp1gW0i_utD65dz8g/videos"
                                                        target="_blank">Videos (YouTube)</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Mega Menu  -->
                            </li>

                            <li class="with-megamenu has-menu-child-item">
                                <a href="#">TEI <i class="feather-chevron-down"></i></a>
                                <!-- Start Mega Menu  -->
                                <div class="rbt-megamenu grid-item-2">
                                    <div class="wrapper">
                                        <div class="row row--15">
                                            <ul class="mega-menu-item">
                                                <li><a href="<?php echo $root_path ?>TEI/2022.php">2022</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Mega Menu  -->
                            </li>

                            <li class="with-megamenu has-menu-child-item">
                                <a href="#">Legislación <i class="feather-chevron-down"></i></a>
                                <!-- Start Mega Menu  -->
                                <div class="rbt-megamenu grid-item-2">
                                    <div class="wrapper">
                                        <div class="row row--15">
                                            <ul class="mega-menu-item">
                                                <li><a href="<?php echo $root_path ?>Legislacion/leyesEnfermeria.php">Leyes
                                                        de Enfermería</a>
                                                </li>
                                                <li><a
                                                        href="<?php echo $root_path ?>Legislacion/decretos.php">Decretos</a>
                                                </li>
                                                <li><a
                                                        href="<?php echo $root_path ?>Legislacion/resoluciones.php">Resoluciones</a>
                                                </li>
                                                <li><a
                                                        href="<?php echo $root_path ?>Legislacion/estatutos.php">Estatutos</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Mega Menu  -->
                            </li>
                        </ul>
                    </nav>
                </div>
                <div class="header-right">

                    <!-- Navbar Icons -->
                    <ul class="quick-access">

                        <li class="account-access rbt-user-wrapper d-none d-xl-block">
                            <a href="<?php echo $root_path_main ?>view/Registrarse/login.php"><i
                                    class="feather-user"></i>Ingresar</a>

                        </li>

                        <li class="access-icon rbt-user-wrapper d-block d-xl-none">
                            <a class="rbt-round-btn" href="<?php echo $root_path_main ?>view/Registrarse/login.php"><i
                                    class="feather-user"></i></a>
                        </li>

                    </ul>

                    <div class="rbt-btn-wrapper d-none d-xl-block">
                        <a class="rbt-btn rbt-marquee-btn marquee-auto btn-border-gradient radius-round btn-sm hover-transform-none"
                            href="<?php echo $root_path_main ?>view/Registrarse/registrarse.php">
                            <span data-text="Inscríbase a la Ape">Inscribase a la Ape</span>
                        </a>
                    </div>

                    <!-- Start Mobile-Menu-Bar -->
                    <div class="mobile-menu-bar d-block d-xl-none">
                        <div class="hamberger">
                            <button class="hamberger-button rbt-round-btn">
                                <i class="feather-menu"></i>
                            </button>
                        </div>
                    </div>
                    <!-- Start Mobile-Menu-Bar -->

                </div>

            </div>
        </div>
    </div>
    <!-- Start Side Vav -->
    <div class="rbt-offcanvas-side-menu rbt-category-sidemenu">
        <div class="inner-wrapper">
            <div class="inner-top">
                <div class="inner-title">
                    <h4 class="title">APE</h4>
                </div>
                <div class="rbt-btn-close">
                    <button class="rbt-close-offcanvas rbt-round-btn"><i class="feather-x"></i></button>
                </div>
            </div>
            <nav class="side-nav w-100">
                <ul class="rbt-vertical-nav-list-wrapper vertical-nav-menu">
                    <li>
                        <a href="<?php echo $root_path ?>APE/misionVision.php">Misión y Visión</a>
                    </li>
                    <li>
                        <a href="<?php echo $root_path ?>APE/quienesSomos.php">Quiénes somos</a>
                    </li>
                    <li>
                        <a href="<?php echo $root_path ?>APE/presidenta.php">Presidenta</a>
                    </li>
                    <li>
                        <a href="<?php echo $root_path ?>APE/juntaDirectiva.php">Junta Directiva</a>
                    </li>
                    <li>
                        <a href="<?php echo $root_path ?>APE/filiales.php">Filiales</a>
                    </li>
                    <li>
                        <a href="<?php echo $root_path ?>APE/personalAdministrativo.php">Personal Administrativo</a>
                    </li>
                    <li>
                        <a href="<?php echo $root_path_main ?>docs/website/memoria.pdf">Memoria 2018-2021</a>
                    </li>
                </ul>
                <!-- <div class="read-more-btn">
                    <div class="rbt-btn-wrapper mt--20">
                        <a class="rbt-btn btn-border-gradient radius-round btn-sm hover-transform-none w-100 justify-content-center text-center"
                            href="#">
                            <span>Más Información</span>
                        </a>
                    </div>
                </div> -->
            </nav>
            <div class="rbt-offcanvas-footer">

            </div>
        </div>
    </div>
    <!-- End Side Vav -->
    <a class="rbt-close_side_menu" href="javascript:void(0);"></a>
</header>

<div class="popup-mobile-menu">
    <div class="inner-wrapper">
        <div class="inner-top">
            <div class="content">
                <div class="logo">
                    <a href="<?php echo $root_path_main ?>index.php">
                        <img src="<?php echo $root_path_main ?>public/img/logo_ape.jpg" alt="Education Logo Images">
                    </a>
                </div>
                <div class="rbt-btn-close">
                    <button class="close-button rbt-round-btn"><i class="feather-x"></i></button>
                </div>
            </div>
            <p class="description">Asociación Paraguaya de Enfermería</p>
            <ul class="navbar-top-left rbt-information-list justify-content-start">
                <li>
                    <a href="mailto:ae_paraguay@yahoo.com.ar"><i class="feather-mail"></i>ae_paraguay@yahoo.com.ar</a>
                </li>
                <li>
                    <a href="#"><i class="feather-phone"></i> 021 224 940 / 0983 859952</a>
                </li>
            </ul>
        </div>

        <nav class="mainmenu-nav">
            <ul class="mainmenu">

                <li class="with-megamenu has-menu-child-item position-static">
                    <a href="index.php">Boletines <i class="feather-chevron-down"></i></a>
                    <!-- Start Mega Menu  -->
                    <div class="rbt-megamenu menu-skin-dark">
                        <div class="wrapper">
                            <div class="row row--15 home-plesentation-wrapper single-dropdown-menu-presentation">

                                <!-- Start Single Demo  -->
                                <div class="col-lg-12 col-xl-2 col-xxl-2 col-md-12 col-sm-12 col-12 single-mega-item">
                                    <div class="demo-single">
                                        <div class="inner">
                                            <div class="content">
                                                <h4 class="title"><a
                                                        href="<?php echo $root_path ?>Boletines/boletinApe.php">BOLETÍN
                                                        APE
                                                        - Edición Publicada<span class="btn-icon"><i
                                                                class="feather-arrow-right"></i></span></a></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Single Demo  -->
                                <!-- Start Single Demo  -->
                                <div class="col-lg-12 col-xl-2 col-xxl-2 col-md-12 col-sm-12 col-12 single-mega-item">
                                    <div class="demo-single">
                                        <div class="inner">
                                            <div class="content">
                                                <h4 class="title"><a
                                                        href="http://ape.org.py/wp-content/uploads/2023/10/Censo-Enfermeria_Ene-05.pdf">CENSO<span
                                                            class="btn-icon"><i
                                                                class="feather-arrow-right"></i></span></a></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Single Demo  -->
                            </div>

                            <div class="load-demo-btn text-center">
                                <a class="rbt-btn-link color-white" href="#">Scroll to view more <svg
                                        xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        class="bi bi-arrow-down-up" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5zm-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5z" />
                                    </svg></a>
                            </div>
                        </div>
                    </div>
                    <!-- End Mega Menu  -->
                </li>

                <li class="with-megamenu has-menu-child-item position-static">
                    <a href="index.php">Biblioteca <i class="feather-chevron-down"></i></a>
                    <!-- Start Mega Menu  -->
                    <div class="rbt-megamenu menu-skin-dark">
                        <div class="wrapper">
                            <div class="row row--15 home-plesentation-wrapper single-dropdown-menu-presentation">

                                <!-- Start Single Demo  -->
                                <div class="col-lg-12 col-xl-2 col-xxl-2 col-md-12 col-sm-12 col-12 single-mega-item">
                                    <div class="demo-single">
                                        <div class="inner">
                                            <div class="content">
                                                <h4 class="title"><a
                                                        href="<?php echo $root_path ?>Biblioteca/imagenes.php">Imagenes<span
                                                            class="btn-icon"><i
                                                                class="feather-arrow-right"></i></span></a></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Single Demo  -->
                                <!-- Start Single Demo  -->
                                <div class="col-lg-12 col-xl-2 col-xxl-2 col-md-12 col-sm-12 col-12 single-mega-item">
                                    <div class="demo-single">
                                        <div class="inner">
                                            <div class="content">
                                                <h4 class="title"><a
                                                        href="https://www.youtube.com/channel/UChg1Wpyp1gW0i_utD65dz8g/videos">Videos
                                                        (YouTube)<span class="btn-icon"><i
                                                                class="feather-arrow-right"></i></span></a></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Single Demo  -->
                            </div>
                        </div>
                    </div>
                    <!-- End Mega Menu  -->
                </li>

                <li class="with-megamenu has-menu-child-item position-static">
                    <a href="index.php">TEI <i class="feather-chevron-down"></i></a>
                    <!-- Start Mega Menu  -->
                    <div class="rbt-megamenu menu-skin-dark">
                        <div class="wrapper">
                            <div class="row row--15 home-plesentation-wrapper single-dropdown-menu-presentation">

                                <!-- Start Single Demo  -->
                                <div class="col-lg-12 col-xl-2 col-xxl-2 col-md-12 col-sm-12 col-12 single-mega-item">
                                    <div class="demo-single">
                                        <div class="inner">
                                            <div class="content">
                                                <h4 class="title"><a
                                                        href="<?php echo $root_path ?>TEI/2022.php">2022<span
                                                            class="btn-icon"><i
                                                                class="feather-arrow-right"></i></span></a></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Single Demo  -->
                            </div>

                            <div class="load-demo-btn text-center">
                                <a class="rbt-btn-link color-white" href="#">Scroll to view more <svg
                                        xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        class="bi bi-arrow-down-up" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5zm-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5z" />
                                    </svg></a>
                            </div>
                        </div>
                    </div>
                    <!-- End Mega Menu  -->
                </li>

                <li class="with-megamenu has-menu-child-item position-static">
                    <a href="index.php">Legislación<i class="feather-chevron-down"></i></a>
                    <!-- Start Mega Menu  -->
                    <div class="rbt-megamenu menu-skin-dark">
                        <div class="wrapper">
                            <div class="row row--15 home-plesentation-wrapper single-dropdown-menu-presentation">

                                <!-- Start Single Demo  -->
                                <div class="col-lg-12 col-xl-2 col-xxl-2 col-md-12 col-sm-12 col-12 single-mega-item">
                                    <div class="demo-single">
                                        <div class="inner">
                                            <div class="content">
                                                <h4 class="title"><a
                                                        href="<?php echo $root_path ?>Legislacion/leyesEnfermeria.php">Leyes
                                                        de Enfermería<span class="btn-icon"><i
                                                                class="feather-arrow-right"></i></span></a></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Single Demo  -->
                                <!-- Start Single Demo  -->
                                <div class="col-lg-12 col-xl-2 col-xxl-2 col-md-12 col-sm-12 col-12 single-mega-item">
                                    <div class="demo-single">
                                        <div class="inner">
                                            <div class="content">
                                                <h4 class="title"><a
                                                        href="<?php echo $root_path ?>Legislacion/decretos.php">Decretos<span
                                                            class="btn-icon"><i
                                                                class="feather-arrow-right"></i></span></a></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Single Demo  -->

                                <!-- Start Single Demo  -->
                                <div class="col-lg-12 col-xl-2 col-xxl-2 col-md-12 col-sm-12 col-12 single-mega-item">
                                    <div class="demo-single">
                                        <div class="inner">
                                            <div class="content">
                                                <h4 class="title"><a
                                                        href="<?php echo $root_path ?>Legislacion/resoluciones.php">Resoluciones<span
                                                            class="btn-icon"><i
                                                                class="feather-arrow-right"></i></span></a></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Single Demo  -->
                            </div>

                            <!-- Start Single Demo  -->
                            <div class="col-lg-12 col-xl-2 col-xxl-2 col-md-12 col-sm-12 col-12 single-mega-item">
                                <div class="demo-single">
                                    <div class="inner">
                                        <div class="content">
                                            <h4 class="title"><a
                                                    href="<?php echo $root_path ?>Legislacion/estatutos.php">Estatutos<span
                                                        class="btn-icon"><i class="feather-arrow-right"></i></span></a>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Single Demo  -->
                            <div class="load-demo-btn text-center">
                                <a class="rbt-btn-link color-white" href="#">Scroll to view more <svg
                                        xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        class="bi bi-arrow-down-up" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5zm-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5z" />
                                    </svg></a>
                            </div>
                        </div>
                    </div>
                    <!-- End Mega Menu  -->
                </li>
            </ul>
        </nav>

        <div class="mobile-menu-bottom">
            <div class="rbt-btn-wrapper mb--20">
                <a class="rbt-btn btn-border-gradient radius-round btn-sm hover-transform-none w-100 justify-content-center text-center"
                    href="../../view/Registrarse/registrarse.php">
                    <span>Registrarse</span>
                </a>
            </div>

            <div class="social-share-wrapper">
                <span class="rbt-short-title d-block">Redes Sociales</span>
                <ul class="social-icon social-default transparent-with-border justify-content-start mt--20">
                    <li>
                        <a href="https://www.facebook.com/">
                            <i class="feather-facebook"></i>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.twitter.com">
                            <i class="feather-twitter"></i>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.instagram.com/">
                            <i class="feather-instagram"></i>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.linkdin.com/">
                            <i class="feather-linkedin"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</div>