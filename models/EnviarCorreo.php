<?php
require_once dirname(__FILE__) . "/../vendor/autoload.php";
require_once dirname(__FILE__) . "/../vendor/phpmailer/phpmailer/src/Exception.php";
require_once dirname(__FILE__) . "/../vendor/phpmailer/phpmailer/src/PHPMailer.php";
require_once dirname(__FILE__) . "/../vendor/phpmailer/phpmailer/src/SMTP.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception; 

class EnviarCorreo {
    private $smtpHost;
    private $smtpPort;
    private $smtpUsername;
    private $smtpPassword;
    private $smtpEncryption;
    private $mail;
    private $SMTPAuth;

    // Constructor de la clase
    public function __construct() {
            $this->smtpEncryption = 'ssl';
            $this->smtpHost =  'smtp.mail.yahoo.com';
            $this->smtpPort = 465;
            $this->SMTPAuth = true;
            $this->smtpUsername ='ae_paraguay@yahoo.com.ar';
            $this->smtpPassword = 'xqhjuympvgargjal';
        
    }

    // Método para enviar correo de confirmación
    public function enviarCorreoConfirmacion($correo, $nombres_apellidos, $ci, $telefono, $token) {
        try {
           // Configuración de PHPMailer
            $mail = new PHPMailer(true);
            $mail->IsSMTP();
            $mail->SMTPDebug = 0;
            $mail->SMTPSecure = $this->smtpEncryption;
            $mail->Host = $this->smtpHost;
            $mail->Port = $this->smtpPort;
            $mail->SMTPAuth = true;
            $mail->CharSet = 'UTF-8';
            $mail->Username = $this->smtpUsername;
            $mail->Password = $this->smtpPassword;
            $mail->isHTML(true);

            $mail->setFrom('ae_paraguay@yahoo.com.ar', 'Ape');
            $mail->Subject = "Confirmación de Registro";
            $mail->addAddress($correo);
            $mail->msgHTML($this->generarContenidoCorreo($nombres_apellidos, $ci, $telefono, $token));

            // Envía el correo electrónico
            if ($mail->send()) {
                // Si el correo se envía correctamente, realizar otras acciones si es necesario
                return true;
            } else {
                // Si hay un error al enviar el correo, puedes manejarlo aquí
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

     // Método para enviar correo de reseteo
     public function enviarCorreoReseteo($correo,$token) {
        try {
           // Configuración de PHPMailer
            $mail = new PHPMailer(true);
            $mail->IsSMTP();
            $mail->SMTPDebug = 0;
            $mail->SMTPSecure = $this->smtpEncryption;
            $mail->Host = $this->smtpHost;
            $mail->Port = $this->smtpPort;
            $mail->SMTPAuth = true;
            $mail->CharSet = 'UTF-8';
            $mail->Username = $this->smtpUsername;
            $mail->Password = $this->smtpPassword;
            $mail->isHTML(true);

            $mail->setFrom('ae_paraguay@yahoo.com.ar', 'Ape');
            $mail->Subject = "Notificación de Restablecimiento de Contraseña";
            $mail->addAddress($correo);
            $mail->msgHTML($this->generarContenidoCorreoReseteo( $token,$correo));

            // Envía el correo electrónico
            if ($mail->send()) {
                // Si el correo se envía correctamente, realizar otras acciones si es necesario
                return true;
            } else {
                // Si hay un error al enviar el correo, puedes manejarlo aquí
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    // Método para generar el contenido del correo electrónico
    private function generarContenidoCorreo($nombres_apellidos, $ci, $telefono, $token) {

        // URL de confirmación de cuenta
        $confirm_url = Conectar::ruta() . "view/Registrarse/confirmacion_cuenta.php?token=" . $token;

        // Contenido del correo
        $mailContent = '<div style="width:100%; background:#eee; font-family:Arial, sans-serif; padding-bottom:40px;">
                        <center>
                            <img style="padding:10px; width:5%" src="http://www.dagalu.com.py/vistas/img/plantilla/icon-email.png">
                        </center>

                        <div style="max-width:600px; margin:auto; background:white; padding:20px; border-radius:10px; box-shadow:0 0 10px rgba(0, 0, 0, 0.1);">
                        
                            <center>
                                <h2 style="font-weight:bold; color:#333; margin:0 0 20px;">Confirmación de registro</h2>
                            </center>

                            <p style="font-weight:normal; color:#666; margin:0 0 10px;">¡Hola ' . $nombres_apellidos . ',</p>
                            
                            <p style="font-weight:normal; color:#666; margin:0 0 10px;">Tu registro ha sido completado exitosamente. Haz clic en el siguiente enlace para confirmar tu cuenta:</p>
                            
                            <p style="font-weight:normal; color:#666; margin:0 0 10px;"><a href="' . $confirm_url . '" style="color:#007bff; text-decoration:none;">Confirmar cuenta</a></p>

                            <ul style="margin:10px 0; padding:0; list-style:none;">
                                <li style="font-weight:bold; color:#333; margin-bottom:10px;">Nombre y Apellido: ' . $nombres_apellidos . '</li>
                                <li style="font-weight:bold; color:#333; margin-bottom:10px;">Ci.: ' . $ci . '</li>
                                <li style="font-weight:bold; color:#333; margin-bottom:10px;">Teléfono: ' . $telefono . '</li>
                            </ul>

                            <hr style="border:1px solid #ccc; margin:20px 0;">

                            <p style="font-weight:normal; color:#666; margin:0 0 20px;">¡Gracias por registrarte en nuestro servicio!</p>
                            
                            <p style="font-weight:normal; color:#666; margin:0 0 20px;">Saludos cordiales,<br>APE</p>

                        </div>
                        
                    </div>';

        return $mailContent;
    }
  
    // Método para generar el contenido del correo electrónico
    private function generarContenidoCorreoReseteo( $token,$correo) {

        // URL de confirmación de cuenta
        $confirm_url = Conectar::ruta() . "view/Registrarse/reset_password.php?m=3&correo=". $correo."&token=".$token;

        // Contenido del correo
        $mailContent = '<div style="width:100%; background:#eee; font-family:Arial, sans-serif; padding-bottom:40px;">
                        <center>
                            <img style="padding:10px; width:5%" src="http://www.dagalu.com.py/vistas/img/plantilla/icon-email.png">
                        </center>

                        <div style="max-width:600px; margin:auto; background:white; padding:20px; border-radius:10px; box-shadow:0 0 10px rgba(0, 0, 0, 0.1);">
                        
                            <center>
                                <h2 style="font-weight:bold; color:#333; margin:0 0 20px;">Reseteo de Contraseña</h2>
                            </center>
                             
                            <p style="font-weight:normal; color:#666; margin:0 0 10px;">Estas recibiendo este correo por que hemos recibido una petición de reseteo de contraseña para tu cuenta.</p>
                            
                            <p style="font-weight:normal; color:#666; margin:0 0 10px;"><a href="' . $confirm_url . '" style="color:#007bff; text-decoration:none;">Cambiar Contraseña</a></p>

                         
                            <hr style="border:1px solid #ccc; margin:20px 0;">

                            <p style="font-weight:normal; color:#666; margin:0 0 20px;">Gracias.</p>
                            
                            <p style="font-weight:normal; color:#666; margin:0 0 20px;">Saludos cordiales,<br>APE</p>

                        </div>
                        
                    </div>';

        return $mailContent;
    }
}



