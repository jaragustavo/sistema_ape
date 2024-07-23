<?php
require_once ("../../config/conexion.php");
require_once ("../../models/Usuario.php");
//Registra que el usuario se encuentra activo dentro del sistema
$usuario = new Usuario();
$usuario->logout();
/* TODO: Destruir Session */
session_destroy();
/* TODO: Luego de cerrar session enviar a la pantalla de login */
header("Location:" . Conectar::ruta() . "index.php");
exit();
?>