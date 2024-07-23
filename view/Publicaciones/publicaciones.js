function init() {}

var jsonDatosProfesionales;
document.addEventListener("DOMContentLoaded", function() {
    // Verifica si jsonDatosProfesionales está definido y no es nulo
    var currentURL = window.location.href;
    // Use a regular expression to extract the ID from the URL
    var match_code = currentURL.match(/[\?&]index.php([^&]*)/);
    if(!match_code){
        if (typeof jsonDatosProfesionales != 'undefined') {
            // Parsea el JSON contenido en jsonDatosProfesionales
            var datos = JSON.parse(jsonDatosProfesionales);
    
            // Lugar de Egreso
            document.getElementById("lugar_egreso").textContent = "Lugar de Egreso: " + (datos.lugar_egreso || "Pendiente");
            // Agrega la clase 'pendiente' si no hay datos en lugar_egreso
            document.getElementById("lugar_egreso").classList.toggle("pendiente", !datos.lugar_egreso);
    
            // Año Egreso
            document.getElementById("anio_egreso").textContent = "Año Egreso: " + (datos.anio_egreso || "Pendiente");
            // Agrega la clase 'pendiente' si no hay datos en anio_egreso
            document.getElementById("anio_egreso").classList.toggle("pendiente", !datos.anio_egreso);
    
            // Lugares de trabajo
            if (datos.lugares_trabajo && datos.lugares_trabajo.length > 0) {
                var lugaresTrabajoPromises = datos.lugares_trabajo.map(function(lugar) {
                    if (lugar.lugar_trabajo > 0) {
                        return fetchNombreEstablecimiento(lugar.lugar_trabajo);
                    } else {
                        // Si no hay lugar_trabajo válido, devuelve "Pendiente" inmediatamente
                        return Promise.resolve("Pendiente");
                    }
                });
    
                // Espera a que todas las promesas se resuelvan
                Promise.all(lugaresTrabajoPromises).then(function(nombresEstablecimiento) {
                    // Une los nombres de establecimiento separados por coma
                    var lugaresTrabajoTexto = nombresEstablecimiento.join(", ");
                    // Actualiza el texto y agrega la clase 'pendiente' si es necesario
                    document.getElementById("lugares_trabajo").textContent = "Lugar de Trabajo: " + lugaresTrabajoTexto;
                    document.getElementById("lugares_trabajo").classList.toggle("pendiente", lugaresTrabajoTexto.trim() === "Pendiente");
                }).catch(function(error) {
                    // Si hay un error, muestra "Pendiente" y agrega la clase 'pendiente'
                    console.error("Error al obtener nombres de establecimiento:", error);
                    document.getElementById("lugares_trabajo").textContent = "Lugar de Trabajo: Pendiente";
                    document.getElementById("lugares_trabajo").classList.add("pendiente");
                });
            } else {
                // Si no hay datos de lugares_trabajo, muestra "Pendiente" y agrega la clase 'pendiente'
                document.getElementById("lugares_trabajo").textContent = "Lugar de Trabajo: Pendiente";
                document.getElementById("lugares_trabajo").classList.add("pendiente");
            }
    
            // Estudios
            if (datos.estudios && datos.estudios.length > 0) {
                var estudios = datos.estudios.map(function(estudio) {
                    // Crea la cadena de texto para cada estudio
                    return estudio.titulo + " en " + estudio.titulo_descripcion;
                }).join(", ");
    
                // Verifica si la cadena de estudios no está vacía
                if (estudios.trim() !== "en") {
                    // Si hay estudios, actualiza el texto y no agrega la clase 'pendiente'
                    document.getElementById("estudios").textContent = "Estudios: " + estudios;
                } else {
                    // Si no hay estudios válidos, muestra "Pendiente" y agrega la clase 'pendiente'
                    document.getElementById("estudios").textContent = "Estudios: Pendiente";
                    document.getElementById("estudios").classList.add("pendiente");
                }
            } else {
                // Si no hay datos de estudios, muestra "Pendiente" y agrega la clase 'pendiente'
                document.getElementById("estudios").textContent = "Estudios: Pendiente";
                document.getElementById("estudios").classList.add("pendiente");
            }
        } else {
            // Si jsonDatosProfesionales es nulo o no está definido, muestra "Pendiente" en todos los campos y agrega la clase 'pendiente'
            document.getElementById("lugar_egreso").textContent = "Lugar de Egreso: Pendiente";
            document.getElementById("lugar_egreso").classList.add("pendiente");
    
            document.getElementById("anio_egreso").textContent = "Año Egreso: Pendiente";
            document.getElementById("anio_egreso").classList.add("pendiente");
    
            document.getElementById("lugares_trabajo").textContent = "Lugar de Trabajo: Pendiente";
            document.getElementById("lugares_trabajo").classList.add("pendiente");
    
            document.getElementById("estudios").textContent = "Estudios: Pendiente";
            document.getElementById("estudios").classList.add("pendiente");
        }
    }

});


async function fetchNombreEstablecimiento(lugar_trabajo_id) {
    try {

        const response = await fetch('../../controller/establecimientoSalud.php?op=mostrar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: lugar_trabajo_id })
        });

        if (!response.ok) {
            throw new Error('Respuesta de red incorrecta');
        }

        const text = await response.text();

        if (text.trim() === "") {
            throw new Error('Respuesta vacía del servidor');
        }

        const data = JSON.parse(text);
        return data.nombre_establecimiento;
    } catch (error) {
        console.error('Hubo un problema con la petición Fetch:', error);
        return 'Pendiente';
    }
}

$(document).ready(function() {
    // Get the current URL

    var currentUrl = window.location.href;

    if (currentUrl.indexOf("index.php") === -1) {


        if (currentUrl.indexOf("perfilUsuario.php") !== -1) {


            // The URL contains "perfilUsuario.php"
            var usuario_visitado_id = $("#usuario_visitado_id").val();


            $.post("../../controller/publicacion.php?op=datosPerfilVisitado", { usuario_visitado_id: usuario_visitado_id }, function(data) {
                data = JSON.parse(data);
            });


        } else {

            //Carga todas las ciudades
            $.post("../../controller/publicacion.php?op=comboCiudades", function(data) {
                $('#ciudad_trabajo').html(data);
            });
            //Cargar las profesiones del sistema
            $.post("../../controller/publicacion.php?op=comboProfesiones", function(data) {
                $('#profesion_principal').html(data);
            });
            //Cargar los establecimientos de salud
            $.post("../../controller/publicacion.php?op=comboEstablecimientos", function(data) {
                $('#lugar_trabajo').html(data);
            });


            $.post("../../controller/usuario.php?op=comboProfesiones", function(data) {
                $('#profesion_id').html(data);

                // Cargar los establecimientos de salud al cargar la página
                cargarEstablecimientosSalud();

                // Llamar a cargarDatosProfesionales 
                cargarDatosProfesionales();
            });

            $.post("../../controller/publicacion.php?op=datosPerfil", function(data) {
                data = JSON.parse(data);
                if (data != "error") {
                    $('#nombre_perfil').val(data["nombre_perfil"]);
                    $('#acerca_de_mi').val(data["acerca_de_mi"]);
                    document.getElementById('parrafo_acerca_de_mi').innerText = data["acerca_de_mi"];
                    $('#ciudad_trabajo').val(data["ciudad_trabajo_id"]);
                    var paragraph = document.getElementById("parrafo_ciudad_trabajo");
                    var text = document.createTextNode(data["ciudad_trabajo_nombre"]);
                    paragraph.appendChild(text);
                    $('#ciudad_trabajo').trigger('change');
                    $('#profesion_principal').val(data["profesion_principal_id"]);
                    $('#profesion_principal').trigger('change');
                    $('#educacion').val(data["educacion"]);
                    paragraph = document.getElementById("parrafo_educacion");
                    text = document.createTextNode(data["educacion"]);
                    paragraph.appendChild(text);
                    $('#lugar_trabajo').val(data["lugar_trabajo_id"]);
                    $('#lugar_trabajo').trigger('change');
                    paragraph = document.getElementById("parrafo_lugar_trabajo");
                    text = document.createTextNode(data["lugar_trabajo_nombre"]);
                    paragraph.appendChild(text);
                    if (data["ciudad_trabajo_nombre"] == null) {
                        var element = document.getElementById("div_ciudad_trabajo");
                        element.style.display = "none";
                    }
                    if (data["educacion"] == null) {
                        var element = document.getElementById("div_educacion");
                        element.style.display = "none";
                    }
                    if (data["lugar_trabajo_nombre"] == null) {
                        var element = document.getElementById("div_lugar_trabajo");
                        element.style.display = "none";
                    }
                } else {
                    console.log(data);

                    $.post("../../controller/publicacion.php?op=crearPerfil", function(data) {
                        $('#nombre_perfil').val(data);
                    });
                }
            });
        }
    }
    // Si la página es del perfil de otro usuario, entrará en el if
    $('#amigos-header').on('click', function() {
        $('.amigos-section').toggleClass('expanded');
    });

    $('#searchInput').on('keyup', function() {
        var searchTerm = $(this).val().toLowerCase();
        $('.friends-list-item').each(function() {
            var userName = $(this).find('.user-card-row-name a').text().toLowerCase();
            if (userName.includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

});

/*=============================================
CREA LA NUEVA PUBLICACION
=============================================*/
const result = 0;
async function postear() {
    // JavaScript Program to convert date to number
    // creating the new date
    const d1 = new Date();

    // converting to number
    const nombre_carpeta = d1.getTime();

    // Use Promise.all to wait for all asynchronous operations to complete
    await Promise.all(selectedFiles.map(async(file) => {
        const datosMultimedia = new FormData();
        datosMultimedia.append("file", file);
        datosMultimedia.append("folder_name", nombre_carpeta);

        try {
            await $.ajax({
                url: "../../controller/publicacion.php?op=subirArchivo",
                method: "POST",
                data: datosMultimedia,
                cache: false,
                contentType: false,
                processData: false
            });

        } catch (error) {
            // Handle errors if necessary
            console.error("Error uploading file:", error);
        }
    }));

    // This will be executed after all asynchronous operations are completed
    guardarPublicacion(nombre_carpeta);
}


function guardarPublicacion(nombre_carpeta) {
    var formData = new FormData($("#data_nuevo_post")[0]);
    var e = document.getElementById("visibilitySelect");

    var strUser = e.value;
    if (strUser == "public") {
        formData.append("publico", true);
    } else {
        formData.append("publico", false);
    }
    formData.append("folder_name", nombre_carpeta);
    $.ajax({
        url: "../../controller/publicacion.php?op=subirPublicacion",
        method: "POST",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {

            if (data = "Publicado exitosamente") {
                $.notify({
                    title: "¡Listo!",
                    message: "Se creó la nueva publicación"
                });
                $("#tabs-2-tab-1").load(" #tabs-2-tab-1 > *");
                $("#publicaciones").load(" #publicaciones > *");
            } else {
                $.notify({
                    icon: 'font-icon font-icon-warning',
                    title: '<strong>Error!</strong>',
                    message: 'Hubo un error al intentar crear su publicación. Inténtelo de nuevo.'
                }, {
                    placement: {
                        align: "center"
                    }
                });
                setTimeout(function() {
                    $("#tabs-2-tab-1").load(" #tabs-2-tab-1 > *");
                }, 5000);

            }

        }
    });
}

function deletePublicacion(publicacion_id) {
    Swal.fire({
        title: '¿Desea eliminar la publicación?',
        text: "No podrás revertir esta acción.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar.'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../../controller/publicacion.php?op=deletePublicacion", { publicacion_id: publicacion_id }, function(e) {

                if (e == "ok") {
                    Swal.fire({
                        title: e,
                        text: "El documento se eliminó correctamente.",
                        icon: "success",
                        showCancelButton: true,
                        confirmButtonColor: "#3d85c6",
                        confirmButtonText: "OK"
                    });
                    $("#tabs-2-tab-1").load(" #tabs-2-tab-1 > *");
                    $("#publicaciones").load(" #publicaciones > *");

                } else {
                    Swal.fire({
                        title: "Error",
                        text: e,
                        icon: "error",
                        showCancelButton: true,
                        confirmButtonColor: "#3d85c6",
                        confirmButtonText: "OK"
                    });
                }
            });
        }
    })
}

function likePublicacion(publicacion_id) {
    $.post("../../controller/publicacion.php?op=likePublicacion", { publicacion_id: publicacion_id }, function(data, status) {
        myDiv = 'counters' + publicacion_id;
        if (data == "ok") {
            $("#" + myDiv).load(" #" + myDiv + " > *");
        }
    });

}

function seguirUsuario(usuario_ci) {
    $.post("../../controller/publicacion.php?op=seguirUsuario", { usuario_ci: usuario_ci }, function(data, status) {

        if (data == "ok") {
            $("#profile_side_user").load(" #profile_side_user > *");
        }
    });
}



function guardarDatosPerfil() {
    /* TODO: Array del form Documento Personal */
    var formData = new FormData($("#datos_perfil_form")[0]);

    /* TODO: validamos si los campos tienen informacion antes de guardar */
    if ($('#nombre_perfil').val() == '') {
        Swal.fire("Advertencia!", "Debe tener un nombre de perfil", "warning");
    } else {
        /* TODO: Actualizar datos del perfil */
        $.ajax({
            url: "../../controller/publicacion.php?op=actualizarPerfil",
            method: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {

                if (data == "ok") {
                    $("#tabs-2-tab-4").load(" #tabs-2-tab-4 > *");
                }

            }
        });
    }
}

function guardarFoto() {
    var formData = new FormData();
    var fileInput = document.getElementById('foto_perfil');
    var file = fileInput.files[0];

    formData.append('file', file);

    $.ajax({
        url: '../../controller/publicacion.php?op=guardarFotoPerfil',
        type: 'POST',
        data: formData,
        processData: false, // Importante
        contentType: false, // Importante
        success: function(response) {
            var data = JSON.parse(response);
            if (data.status === "ok") {
                Swal.fire({
                    title: "Éxito",
                    text: "La foto de perfil fue actualizada.",
                    icon: "success",
                    showCancelButton: true,
                    confirmButtonColor: "#3d85c6",
                    confirmButtonText: "OK"
                });

                // Actualizar la imagen de perfil con la nueva foto
                var foto_perfil = document.querySelector('.avatar-preview-128 img');
                foto_perfil.src = data.new_image_path;
            } else {
                Swal.fire({
                    title: "Error",
                    text: data.message,
                    icon: "error",
                    showCancelButton: true,
                    confirmButtonColor: "#3d85c6",
                    confirmButtonText: "OK"
                });
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.fire({
                title: "Error",
                text: "Ocurrió un error al subir el archivo.",
                icon: "error",
                showCancelButton: true,
                confirmButtonColor: "#3d85c6",
                confirmButtonText: "OK"
            });
        }
    });
}

function enviarComentario(publicacion_id) {
    var nuevo_comentario = $('#nuevo_comentario' + publicacion_id).val();
    console.log(nuevo_comentario);
    if (nuevo_comentario == '') {
        Swal.fire("Advertencia!", "Debe escribir algo", "warning");
    } else {
        /* TODO: Actualizar datos del perfil */
        $.ajax({
            url: "../../controller/publicacion.php?op=comentarPosteo",
            method: "POST",
            data: {
                nuevo_comentario: nuevo_comentario,
                publicacion_id: publicacion_id
            },
            success: function(data) {
                if (data == "ok") {
                    $("#nuevo_comentario" + publicacion_id).val("");
                    $("#comentarios_post" + publicacion_id).load(" #comentarios_post" + publicacion_id + "> *");
                    $("#counters" + publicacion_id).load(" #counters" + publicacion_id + "> *");
                }
            }
        });
    }
}