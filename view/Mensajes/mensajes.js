var usuario_chat = 0;
let usuarios_sistema = Array();

$(document).ready(function () {
    //si se abrió el chat desde las notificaciones, es necesario cargar el chat directamente. 
    var currentURL = window.location.href;
    var match_chat_id = currentURL.match(/[\?&]ID=([^&]*)/);

    if (match_chat_id) {
        $.post("../../controller/mensaje.php?op=user_info", { match_chat_id: match_chat_id[1] }, function (data) {
            var user_info = JSON.parse(data);
            console.log(user_info['user_id']);

            cargarChat(user_info['user_id'], user_info['user_info']);
        });
    }
    document.getElementById("header_chat").style.display = "none"; //show
    document.getElementById("body_chat").style.display = "none"; //show
    document.getElementById("escribir_mensaje").style.display = "none"; //show
    $.post("../../controller/mensaje.php?op=usuariosSistema", function (data) {
        // Parse the JSON response
        var usuarios_sistema = JSON.parse(data);

        $.typeahead({
            input: "#search-friends",
            order: "asc",
            minLength: 1,
            source: {
                data: usuarios_sistema.map(function (user) {
                    return {
                        display: user.name,
                        id: user.id,
                        foto_perfil: user.foto_perfil

                    };
                })
            },
            template: function (query, item) {
                if (item.foto_perfil == null || item.foto_perfil == "") {
                    item.foto_perfil = "../assets/assets-main/images/icons/user2.png";
                }
                return '<div class="typeahead-suggestion">' +
                    '<img src="../' + item.foto_perfil + '" class="profile-picture" />' +
                    '<span>' + item.display + '</span>' +
                    '</div>';
            },
            callback: {
                onClickAfter: function (node, a, item, event) {
                    var selectedUser = usuarios_sistema.find(function (user) {
                        return user.name === item.display;
                    });
                    if (selectedUser) {
                        cargarChat(selectedUser.id, selectedUser.name);
                    }
                }
            }
        });
    });
});

// Carga el chat al seleccionar a uno del listado
function cargarChat(chat_id, chat_name) {
    //show
    document.getElementById("header_chat").style.display = "block";
    document.getElementById("body_chat").style.display = "block";
    document.getElementById("escribir_mensaje").style.display = "block";
    usuario_chat = chat_id;
    $.post("../../controller/mensaje.php?op=cargarChat", { chat_id: chat_id }, function (data) {
        const divChat = document.getElementById("listado_mensajes");
        divChat.innerHTML = "";
        if (data) {
            var conversations = JSON.parse(data);
            var nameChat = "";
            var fecha = new Date(conversations[0].fecha);
            m = fecha.getMonth();
            d = fecha.getDate();
            y = fecha.getFullYear();

            fecha = new Date(y, m, d);
            for (var i = 0; i < conversations.length; i++) {
                var row = conversations[i];
                var mydate = new Date(row.fecha);
                m = mydate.getMonth();
                d = mydate.getDate();
                y = mydate.getFullYear();
                mydate = new Date(y, m, d);
                // Se separan los mensajes por fecha
                if (fecha.toDateString() !== mydate.toDateString() || i == 0) {
                    var divFecha = document.createElement("div");
                    var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                    divFecha.innerHTML = '<div class="chat-list-item chat-list-item-date" style="color: #919fa9;">' +
                        mydate.toLocaleDateString("es-ES", options); + '</div>';
                    divChat.appendChild(divFecha);
                    fecha = mydate;
                }


                var divElement = document.createElement("div");
                if (row.foto_perfil == null || row.foto_perfil == "") {
                    row.foto_perfil = "../sistema_ape/assets/assets-main/images/icons/user2.png";
                }
                // Se crea el div dependiendo si es un mensaje recibido o enviado
                if (row.remitente_id != row.usuario_id) {
                    divElement.innerHTML =
                        '<div class="messenger-message-container">' +
                        '<div class="avatar"><img src="../' + row.foto_perfil + '"></div>' +
                        '<div class="messages">' +
                        '<ul><li>' +
                        '<div class="message"><div>' + row.mensaje + '</div></div>' +
                        '<div class="time-ago">' + row.hora + '</div>' +
                        '</li></ul>' +
                        '</div>' +
                        '</div>';
                    nameChat = row.nombre_remitente;

                }
                else {
                    divElement.innerHTML =
                        '<div class="messenger-message-container from bg-blue" style="margin-left: auto;margin-right: 0;">' +
                        '<div class="messages">' +
                        '<ul><li>' +
                        '<div class="time-ago">' + row.hora + '</div>' +
                        '<div class="message"><div>' + row.mensaje + '</div></div>' +
                        '</li></ul>' +
                        '</div>' +
                        '<div class="avatar chat-list-item-photo"><img src="../' + row.foto_perfil + '"></div>' +
                        '</div>';
                    nameChat = row.nombre_destinatario;

                }
                divChat.appendChild(divElement);
            }

            actualizarEstado(row.mensaje_id, row.ind_estado, row.usuario_id, row.remitente_id);
        }

        else {
            nameChat = chat_name;
        }
        var scrollableDiv = document.
            getElementById('body_chat');
        scrollToBottom(scrollableDiv);
        $(".name_chat").text(nameChat);
        $("#chat-list-item").load(location.href + " #chat-list-item");
        $("#header_mensajes").load(location.href + " #header_mensajes");

    });

    function scrollToBottom(scrollableDiv) {
        var bottomElement = scrollableDiv.
            lastElementChild;
        bottomElement
            .scrollIntoView({ behavior: 'smooth', block: 'end' });
    }

    // -------------- Obtener última conexión ------------------
    $.post("../../controller/mensaje.php?op=ultimaConexion", { chat_id: chat_id }, function (data) {
        var datos_conexion = JSON.parse(data);

        //Condición para saber si está conectado o no. Muestra la última conexión o si está en línea
        if (!datos_conexion.conectado) {
            $("#header_chat").removeClass('online');
            // Comparación de la fecha actual con la conexión traída de la Base de Datos
            var q = new Date();
            var m = q.getMonth();
            var d = q.getDate();
            var y = q.getFullYear();

            var date = new Date(y, m, d);

            var mydate = new Date(datos_conexion.fecha_conexion);
            m = mydate.getMonth();
            d = mydate.getDate();
            y = mydate.getFullYear();

            mydate = new Date(y, m, d);
            //Formatear la fecha cuando se muestra completa
            var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            //Si la última conexión fue antes de hoy, se muestra la fecha
            if (date > mydate) {
                document.getElementById('ultima_conexion').innerHTML = 'Última vez ' +
                    mydate.toLocaleDateString("es-ES", options);
            }
            //Si la última conexión fue hoy, se muestra sólo la hora
            else {
                mydate = new Date(datos_conexion.fecha_conexion);
                let hrs = mydate.getHours()
                let mins = mydate.getMinutes()
                if (hrs <= 9)
                    hrs = '0' + hrs
                if (mins < 10)
                    mins = '0' + mins
                const postTime = hrs + ':' + mins
                document.getElementById('ultima_conexion').innerHTML =
                    "Hoy a las " + postTime;
            }
        } else {
            $("#header_chat").addClass('online');
            document.getElementById('ultima_conexion').innerHTML = "En línea";
        }
    });
}
// Actualiza el estado a 'Leido' de todos los mensajes recibidos del chat que se abre
function actualizarEstado(mensaje_id, estado, usuario_id, remitente_id) {
    if (estado == "No leido" && usuario_id != remitente_id) {
        $.post("../../controller/mensaje.php?op=actualizarEstado",
            { mensaje_id: mensaje_id, nuevo_estado: 'Leido' }, function (data) {
                if (data = "ok") {
                    $("#chat-list-item").load(location.href + " #chat-list-item");
                    $("#header_mensajes").load(location.href + " #header_mensajes");
                }
            }
        );
    }
}

const elem = document.getElementById("nuevo_mensaje");

elem.addEventListener("keypress", (event) => {
    if (event.key === 'Enter') { // key code of the keybord key
        event.preventDefault();
        var mensaje_nuevo = document.getElementById("nuevo_mensaje").value;
        if (mensaje_nuevo != "") {
            $.post("../../controller/mensaje.php?op=enviarMensaje",
                { destinatario_id: usuario_chat, nuevo_mensaje: mensaje_nuevo }, function (data) {
                    // console.log(data);
                    var newMsg = JSON.parse(data);
                    mostrarNuevoMensaje(newMsg);

                });
            var scrollableDiv = document.
                getElementById('body_chat');
            scrollToBottom(scrollableDiv);
        }
        document.getElementById("nuevo_mensaje").value = "";
    }
});

function mostrarNuevoMensaje(data) {
    $("#chat-list-item").load(location.href + " #chat-list-item");
    const divChat = document.getElementById("listado_mensajes");
    const divElement = document.createElement("div");
    divElement.innerHTML =
        '<div class="messenger-message-container from bg-blue" style="margin-left: auto;margin-right: 0;">' +
        '<div class="messages">' +
        '<ul><li>' +
        '<div class="time-ago">' + data.hora + '</div>' +
        '<div class="message"><div>' + data.mensaje + '</div>' +
        '</li></ul>' +
        '</div>' +
        '<div class="avatar chat-list-item-photo"><img src="../' + data.foto_perfil + '"></div>' +
        '</div>';
    divChat.appendChild(divElement);
}