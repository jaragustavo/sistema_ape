<?php
require_once "../../models/Publicacion.php";
$publicaciones = Publicacion::get_publicaciones_x_usuario($_GET["ID"]);
$info_perfil = Publicacion::get_info_perfil($_GET["ID"]);
foreach ($publicaciones as $key => $value) {
    ?>
    <style>
        .abrir-archivo:hover {
            opacity: 1;
        }
    </style>
    <article class="box-typical profile-post">
        <div class="profile-post-header">
            <div class="user-card-row">
                <div class="tbl-row">
                    <div class="tbl-cell tbl-cell-photo">
                        <a href="#">
                            <img src="../<?php if ($info_perfil[0]['foto_perfil'] == null || $info_perfil[0]['foto_perfil'] == '') {
                                            echo "../assets/assets-main/images/icons/user2.png";
                                        } else {
                                            echo $info_perfil[0]['foto_perfil'];
                                        }?>" alt="">
                        </a>
                    </div>
                    <div class="tbl-cell">
                        <div class="user-card-row-name"><a href="#">
                                <?php echo $info_perfil[0]["usuario_perfil_nombre"] ?>
                            </a></div>
                        <div class="color-blue-grey-lighter">
                            <?php echo $value["fecha_publicacion"] ?>
                        </div>
                    </div>
                </div>
            </div>
            <a href="#" class="shared">
                <i class="font-icon font-icon-share"></i>
            </a>
        </div>
        <div class="profile-post-content">
            <?php
            if ($value["adjuntos"] > 0) {
                ?>
                <p class="profile-post-content-note">Añadió
                    <?php echo $value["adjuntos"] ?> nuevo(s) adjunto(s)
                </p>
                <?php
            }
            ?>
            <p>
                <?php echo '<p> ' . nl2br($value["texto"]) . '</p>' ?>

            </p>
        </div>
        <?php
        require_once "../../models/Publicacion.php";
        $adjuntos = Publicacion::get_adjuntos_x_publicacion($value["publicacion_id"]);

        ?>
        <div class="profile-post-gall-fluid profile-post-gall-grid" data-columns style="padding:25px; margin-top:-25px;">
            <?php
            foreach ($adjuntos as $key_doc => $value_doc) {
                $file_info = new finfo(FILEINFO_MIME_TYPE);
                $mime_type = $file_info->buffer(file_get_contents($value_doc["url_doc"]));
                ?>
                <div class="col">

                    <?php
                    if ($mime_type == "image/png" || $mime_type == "image/jpg" || $mime_type == "image/jpeg") {
                        ?>
                        <a class="fancybox" rel="gall-1" href="<?php echo $value_doc["url_doc"] ?>">
                            <img src="<?php echo $value_doc["url_doc"] ?>" alt="">
                        </a>
                        <?php
                    } else {
                        // $mime_type = $value_doc["mime_type"];
                        $url_doc = $value_doc["url_doc"];

                        if (strpos($mime_type, 'video/mp4') !== false) {
                            // If it's an MP4 video
                            echo '<video controls preload="none" width="200">';
                            echo '<source src="' . $url_doc . '" type="' . $mime_type . '">';
                            echo 'Your browser does not support the video tag.';
                            echo '</video>';
                        } elseif (strpos($mime_type, 'audio/mp3') !== false) {
                            // If it's an MP3 audio
                            echo '<audio controls preload="none" style="width: 200px;">';
                            echo '<source src="' . $url_doc . '" type="' . $mime_type . '">';
                            echo 'Your browser does not support the audio tag.';
                            echo '</audio>';
                        } elseif (strpos($mime_type, 'application/pdf') !== false) {
                            // If it's a PDF document
                            echo '<iframe src="' . $url_doc . '" width="200" height="200"></iframe>';
                        } else {
                            // For other file types, just provide a link
                            echo '<a href="' . $url_doc . '" target="_blank">Open Document</a>';
                        }
                        ?>

                        
                        <?php
                    }
                    ?>
                </div>
                <?php
            }
            ?>
        </div>
        <div class="box-typical-footer profile-post-meta" id="counters<?php echo $value['publicacion_id'] ?>">
            <?php
                require_once "../../models/Publicacion.php";
                $likes_comments = Publicacion::count_likes_comments_x_publicacion($value["publicacion_id"], $_SESSION["usuario_id"]);
                $style = '';
                if($likes_comments[1] > 0){
                    $style = 'style="color:#00a8ff"';
                }
            ?>
            <a class="meta-item" <?php echo $style ?>  onclick="likePublicacion(<?php echo $value['publicacion_id'] ?>)">
                <i class="font-icon font-icon-heart"></i>

                <?php 
                $texto = '';
                if($likes_comments[0] == 1){
                    $texto = 'like';
                }
                else{
                    $texto = 'likes';
                }
                echo $likes_comments[0]. " " . $texto ?> 
                
            </a>
            <a href="#" class="meta-item">
                <i class="font-icon font-icon-comment"></i>
                <?php 
                $texto = '';
                if($likes_comments[2] == 1){
                    $texto = 'comentario';
                }
                else{
                    $texto = 'comentarios';
                }
                echo $likes_comments[2]. " " . $texto ?> 
            </a>
        </div>
        <div id="comentarios_post<?php echo $value['publicacion_id'] ?>">
        <?php 
            if($likes_comments[2]>0){
                require_once "../../models/Publicacion.php";
                $comentarios = Publicacion::get_comentarios_x_post($value["publicacion_id"]);
                foreach($comentarios as $comentario){
            ?>
                    <div class="comment-rows-container hover-action scrollable-block">
                        <div class="comment-row-item">
                            <div class="avatar-preview avatar-preview-32">
                                <a href="#">
                                    <!-- Foto comentario -->
                                    <img src="../<?php if ($comentario['foto_perfil'] == null || $comentario['foto_perfil'] == '') {
                                            echo "../assets/assets-main/images/icons/user2.png";
                                        } else {
                                            echo $comentario["foto_perfil"];
                                        } ?>" alt="">
                                </a>
                            </div>
                            <div class="tbl comment-row-item-header">
                                <div class="tbl-row">
                                    <!-- Nombre comentario -->
                                    <div class="tbl-cell tbl-cell-name"><?php echo $comentario["nombre_usuario_comentario"] ?></div> 
                                    <!-- fecha comentario -->
                                    <div class="tbl-cell tbl-cell-date"><?php echo $comentario["fecha_comentario"] ?></div>
                                </div>
                            </div>
                            <div class="comment-row-item-content">
                                <!-- Comentario -->
                                <p><?php echo $comentario["texto_comentario"] ?></p>
                            </div>
                        </div><!--.comment-row-item-->
                    </div><!--.comment-rows-container-->
            <?php 
                }       
            }
        ?>
        </div>
        <input type="text" id="nuevo_comentario<?php echo $value['publicacion_id'] ?>" class="write-something" placeholder="Deja un comentario" />
        <div class="box-typical-footer">
            <div class="tbl">
                <div class="tbl-row">
                    <div class="tbl-cell tbl-cell-action">
                        <button type="button" class="btn btn-rounded" style="float: right;"
                        onclick="enviarComentario(<?php echo $value['publicacion_id'] ?>)">Send</button>
                    </div>
                </div>
            </div>
        </div>
    </article>
    <?php
}
?>