<?php
require_once("../../config/conexion.php");
require_once("../../models/Notificacion.php");
$notificacion = new Notificacion();

$key = "mi_key_secret";
$cipher = "aes-256-cbc";
$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
$notif_nuevas = 0;
$active = "";
$datos = $notificacion->get_notificaciones_x_usu($_SESSION["usuario_id"]);
if(count( $datos ) > 0) {
    $notif_nuevas = $datos[0]["no_leidas"];
}
if ($notif_nuevas > 0) {
    $active = "dropdown-toggle active";
    if ($notif_nuevas == 1) {
        $style = "height:70px !important";
    } elseif ($notif_nuevas == 2) {
        $style = "height:140px !important";
    } else {
        $style = "height:210px !important";

    }

} else {
    $active = "";
    $style = "height:0px !important";
}
?>
<div class="dropdown dropdown-notification notif" id="header_notificaciones" onclick="actualizarNotificaciones(<?php echo $notif_nuevas?>)">
    <a href="#" class="header-alarm <?php echo $active ?>" id="dd-notification" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        <i class="font-icon-alarm"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-right dropdown-menu-notif" aria-labelledby="dd-notification"
        >
        <div class="dropdown-menu-notif-header">
            Notificaciones
            <?php
            if($notif_nuevas > 0) {
            ?>
            <span class="label label-pill label-danger">
                <?php
                echo $notif_nuevas
                    ?>
            </span>
            <?php
            }
            ?>
        </div>
        <div class="dropdown-menu-notif-list">
            <div class="dropdown-menu-notif-item">
                <?php
                foreach ($datos as $row) {
                    $cifrado = openssl_encrypt($row["notificante"], $cipher, $key, OPENSSL_RAW_DATA, $iv);
                    $textoCifrado = base64_encode($iv . $cifrado);
                    ?>
                    <a href="../Mensajes/abrirChat.php?ID=<?php echo $textoCifrado ?>" class="mess-item">
                        <span class="avatar-preview avatar-preview-32"><img src="../../public/img/logo_ape.jpg" alt=""></span>
                        <span class="mess-item-name">
                            <?php echo $row["nombre_notificante"] ?>
                        </span>
                        
                    </a>
                    <span class="mess-item-txt">
                            <?php echo $row["mensaje_notificacion"] ?>
                        </span>
                        <?php 
                        
                        date_default_timezone_set('America/Asuncion');
                        $d1 = new DateTime(date('Y-m-d H:i:s'));
                        $d2 = new DateTime($row["fecha_notificacion"]);
                        $difHora = $d1->diff($d2);
                        ?>
                        <div class="color-blue-grey-lighter">
                            Hace <?php echo $difHora->format('%h horas, %i minutos') . PHP_EOL; ?></div>
                    <?php
                } ?>
            </div>

        </div>
        <div class="dropdown-menu-notif-more">
            <a href="#">Ver más</a>
        </div>
    </div>
</div>