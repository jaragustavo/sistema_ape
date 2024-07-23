
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
        max-width: 42ch;
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
                        <div class="col-lg-12" style="padding: 0 0 0 0 !important;">
                            <!-- Start Breadcrumb Area  -->
                            <ul class="page-list">
                                <li class="rbt-breadcrumb-item"><a href="../../index.php">Inicio</a></li>
                                <li>
                                    <div class="icon-right"><i class="feather-chevron-right"></i></div>
                                </li>
                                <li class="rbt-breadcrumb-item active">Congresos</li>
                            </ul>
                            <!-- End Breadcrumb Area  -->

                            <div class=" title-wrapper">
                                <h1 class="title mb--0">Congresos</h1>
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
            <div class="row g-5" style="margin: 0 -15px !important;" >
                <!-- Start Single Product  -->
                <div class="col-lg-5 col-md-6 col-12">
                    <div class="rbt-default-card style-three rbt-hover">
                        <div class="inner"  >
                            <div class="content pt--0 pb--10">
                                <h2 class="title"><a
                                        href="">XVI Congreso Paraguayo de Enfermería</a></h2>

                                <span class="team-form">
                                    <span class="location">
                                        “Inscriciones abiertas - XVI Congreso Paraguayo de Enfermeria - Hotel Excelsior - 1,2 y 3 de agosto 2024. Consultas al 
                                        021 224 940 / 0983 859952.”
                                    </span>
                                    <span class="read-more">Leer más</span>
                                </span>

                            </div>

                            <div class="rbt-card-img">
                                <a href="../../assets/assets-main/images/congresos/xvi-congreso-enfermeria.jpg">
                                    <img src="../../assets/assets-main/images/congresos/xvi-congreso-enfermeria.jpg"
                                        alt="Card image" style="max-width: 100%;">
                                    <div class="rbt-badge-3 bg-white">
                                        <span>Agosto</span>
                                        <span>2024</span>
                                    </div>
                                </a>
                            </div>
                        
                            <div class="rbt-card-body">
                            <br>
                                <ul class="rbt-meta">
                                    <li><i class="feather-map-pin"></i>Hotel Excelsior, Asunción</li>
                                    <li><i class="feather-clock"></i>1, 2 y 3 de agosto</li>
                                </ul>
                                <br>  
                            
                                    <a class="rbt-btn btn-border hover-icon-reverse btn-sm radius-round text-center" 
                                        href="../../assets/assets-main/images/congresos/xvi-congreso-enfermeria-inversion.jpg" 
                                        target="_blank">
                                        <span class="icon-reverse-wrapper">
                                            <span class="btn-text">Inscripciones</span>
                                            <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                            <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                        </span>
                                    </a>
                             
                            </div>
                          
                       </div>
                    </div>
                </div>
                <!-- End Single Product  -->

  <!-- Start Category Box Layout  -->
                    <!-- <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <a class="rbt-cat-box rbt-cat-box-1 text-center" href="course-filter-one-toggle.html">
                            <div class="inner">
                                <div class="icons">
                                <img src="../../assets/assets-main/images/congresos/xvi-congreso-enfermeria.jpg"alt="Icons Images">
                                </div>
                                <div class="content">
                                    <h5 class="title">XVI Congreso Paraguayo de Enfermería</h5>
                                    <div class="read-more-btn">
                                        <span class="rbt-btn-link">25 Courses<i class="feather-arrow-right"></i></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div> -->
                    <!-- End Category Box Layout  -->
              
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