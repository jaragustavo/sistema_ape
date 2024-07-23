<?php
    require_once("../../config/conexion.php");
    require_once("../../models/Mensaje.php");
    $mensaje = new Mensaje();

    $key="mi_key_secret";
    $cipher="aes-256-cbc";
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
    $mensajes_nuevos = 0;
    $active = "";
    $datos=$mensaje->get_mensajes_x_usu($_SESSION["usuario_id"]);
    $mensajes_nuevos=count($datos);

    if($mensajes_nuevos>0){
        $active = "dropdown-toggle active";
        if($mensajes_nuevos==1){
            $style="height:70px !important";
        }
        elseif($mensajes_nuevos==2){
            $style="height:140px !important";
        }
        else{
            $style="height:210px !important";

        }
        
    }
    else{
        $active = "";
        $style="height:0px !important";
    }
?>
<div class="dropdown dropdown-notification messages" id="header_mensajes">
    <a href="#"
        
        class="header-alarm <?php echo $active ?>"
        
        id="dd-messages"
        data-toggle="dropdown"
        aria-haspopup="true"
        aria-expanded="false">
        <i class="font-icon-mail"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-right dropdown-menu-messages" aria-labelledby="dd-messages">
        <div class="dropdown-menu-messages-header">
            <ul class="nav" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active"
                        data-toggle="tab"
                        href="../Mensajes/listarMensajes.php"
                        role="tab">
                        Mensajes nuevos
                        <span class="label label-pill label-danger">
                            <?php 
                            echo $mensajes_nuevos
                            ?>
                        </span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="tab-content" >
            <div class="tab-pane active" id="tab-incoming" role="tabpanel" style="<?php echo $style ?>">
                <div class="dropdown-menu-messages-list" >
                    <?php
                        foreach($datos as $row){              
                            $cifrado = openssl_encrypt($row["remitente_id"], $cipher, $key, OPENSSL_RAW_DATA, $iv);
                            $textoCifrado = base64_encode($iv . $cifrado);     
                    ?>
                    <a href="../Mensajes/abrirChat.php?ID=<?php echo $textoCifrado ?>" class="mess-item">
                        <span class="avatar-preview avatar-preview-32"><img src="" alt=""></span>
                        <span class="mess-item-name"><?php echo $row["nombre_remitente"] ?></span>
                        <span class="mess-item-txt"><?php echo $row["mensaje"] ?></span>
                    </a>
                    <?php
                        }?>
                </div>
            </div>
        </div>
        <div class="dropdown-menu-notif-more">
            <a href="../Mensajes/listarMensajes.php" class="actualizarMensajes" 
               usuario_id="<?php echo $_SESSION["usuario_id"]?>">
                Ver todos los mensajes
            </a>
        </div>
    </div>
</div>