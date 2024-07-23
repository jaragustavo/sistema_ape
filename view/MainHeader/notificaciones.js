/*=============================================
ACTUALIZAR MENSAJES
=============================================*/

var contador_clicks = 0
function actualizarNotificaciones(notif_nuevas) {
    contador_clicks++;
    if (notif_nuevas > 0) {
        if(contador_clicks>1){
            $.post("../../controller/notificacion.php?op=abrirNotificacion", function (data) {
                if (data == "ok") {
                    $("#header_notificaciones").load(location.href + " #header_notificaciones > *");
                }
                else {
                    alert(data);
                }

            });
        }
    }
}