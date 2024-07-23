var tabla;
//Se utiliza para listar los documentos del usuario
var idEncrypted = "";
var arrayFiles = [];
var arrayId = [];

function init() {

}

$(document).ready(function() {

    var currentURL = window.location.href;
    // Use a regular expression to extract the ID from the URL
    var match_code = currentURL.match(/[\?&]code=([^&]*)/);
    if (match_code) {
        // Extracted ID is in match_code[1]
        tramite_code = match_code[1];


        $('#tramite_code').val(tramite_code);

    }

    tabla = $('#reservas_data').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'rtip',
        lengthChange: false,
        colReorder: true,
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ],
        "ajax": {
            url: '../../controller/tramite.php?op=listar_reservas_x_usu',
            type: "post",
            dataType: "json",
            error: function(e) {
                console.log(e.responseText);
            }
        },
        "ordering": false,
        "bDestroy": true,
        "responsive": true,
        "bInfo": true,
        "iDisplayLength": 10,
        "autoWidth": false,
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        }
    }).DataTable();

    /* TODO: Llenar Combo trámites */
    $.post("../../controller/tramite.php?op=comboTramitesSedeSocial", function(data) {
        $('#tramite_nuevo').html(data);
        $('#tramite').html(data);
    });

    /* TODO: Llenar Combo estados de trámites */
    $.post("../../controller/tramite.php?op=comboEstadosTramites", { tramite_code: tramite_code }, function(data) {
        $('#estado_tramite').html(data);
    });

    $.post("../../controller/tramite.php?op=comboLocales", function(data) {
        $('#local').html(data);
    });

    // Extraer el ID del registro a modificar
    var currentURL = window.location.href;

    var match = currentURL.match(/[\?&]code=([^&]*)/);

    if (match) {
        // Extracted ID is in match[1]
        tramite_id = match[1];

        //   $('#idEncrypted').val(idEncrypted);

        var tramite_id = $('#tramite_code').val();

        $.post("../../controller/tramite.php?op=obtener_datos_tramite", { tramite_id: tramite_id }, function(data) {
            try {

                var response = JSON.parse(data); // Parsea la respuesta JSON

                if (response && response.datos) {
                    var datos = JSON.parse(response.datos); // Parsea los datos internos JSON
                    console.log('Datos a asignar:', datos); // Verifica los datos que se asignarán

                    for (var key in datos) {
                        if (datos.hasOwnProperty(key)) {
                            $('#' + key).val(datos[key]);
                        }
                    }
                } else {
                    console.error('Error: No se recibieron datos válidos.');
                }
            } catch (e) {
                console.error('Error al procesar datos:', e);
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error('Error en la solicitud AJAX:', textStatus, errorThrown);
        });

    }
    if (idEncrypted == "") {
        var targetElement = document.getElementById("guardar_datos_btn");
        // Change its styles
        targetElement.style.display = "block";
    }

});
var id = 0;

function cargarIdDoc(idDiv) {
    id = idDiv;
}

async function guardarDocsTramites(estado_tramite) {
    try {
        await Promise.all(arrayFiles.map(async(file) => {
            const datosMultimedia = new FormData();
            datosMultimedia.append("id", file.id);
            datosMultimedia.append("file", file.value);
            datosMultimedia.append('tramite_code', $('#tramite_code').val());

            try {
                await $.ajax({
                    url: "../../controller/tramite.php?op=insertDocumentos",
                    method: "POST",
                    data: datosMultimedia,
                    cache: false,
                    contentType: false,
                    processData: false
                });
            } catch (error) {
                // Handle errors if necessary
                console.error("Error al guardar el documento del trámite:", error);
                throw error; // Re-throw the error to break out of Promise.all
            }
        }));

        // All AJAX requests are complete
        guardarSolicitud(estado_tramite);

    } catch (error) {
        // Handle any error that might occur during the process
        console.error("Error al guardar el documento del trámite:", error);
    }
}

function guardarSolicitud(estado_tramite) {

    /* TODO: Array del form solicitud */
    var formData = new FormData($("#datos_reserva_form")[0]);
    formData.append("tramite_code", $('#tramite_code').val());
    formData.append("tramite_id", $('#idEncrypted').val());
    formData.append("estado_tramite_id", estado_tramite);
    /* TODO: Guardar solicitud */
    if ($('#idEncrypted').val() == "") {

        $.ajax({
            url: "../../controller/tramite.php?op=insertReserva",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                if (data == "ok") {
                    Swal.fire({
                        title: "¡Listo!",
                        text: "Registrado Correctamente",
                        icon: "success",
                        showCancelButton: true,
                        confirmButtonColor: "#3d85c6",
                        confirmButtonText: "OK"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.replace('listarActividadesSociales.php');
                        }
                    });
                } else {
                    Swal.fire({
                        title: "Error",
                        text: data,
                        icon: "error",
                        showCancelButton: true,
                        confirmButtonColor: "#3d85c6",
                        confirmButtonText: "OK"
                    });
                }

            }
        });
    } else {
        formData.append('idSolicitud', $('#idEncrypted').val());
        $.ajax({
            url: "../../controller/tramite.php?op=updateReserva",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                if (data == "ok") {
                    Swal.fire({
                        title: "¡Listo!",
                        text: "Cambio realizado exitosamente",
                        icon: "success",
                        showCancelButton: true,
                        confirmButtonColor: "#3d85c6",
                        confirmButtonText: "OK"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.replace('listarActividadesSociales.php');
                        }
                    });
                } else {
                    Swal.fire({
                        title: "Error",
                        text: data,
                        icon: "error",
                        showCancelButton: true,
                        confirmButtonColor: "#3d85c6",
                        confirmButtonText: "OK"
                    });
                }

            }
        });
    }
}


/* TODO: Link para poder ver el tramite guardado */
$(document).on("click", ".btn-inline", function() {
    const ciphertext = $(this).data("ciphertext");
    window.location.replace('editarInscripcionRegistro.php?ID=' + ciphertext + '');
});

function abrirNuevaSolicitud() {
    if ($("#tramite_nuevo").val() != undefined && $("#tramite_nuevo").val() > 0) {
        window.location.replace('solicitudSedeSocial.php?code=' + $('#tramite_nuevo').val() + '');

    } else {
        Swal.fire({
            title: 'Debe elegir una opción',
            text: "",
            icon: "error",
            showCancelButton: true,
            confirmButtonColor: "#3d85c6",
            confirmButtonText: "OK"
        });
    }
}

$(document).on("click", ".btn-abrir-reserva", function() {
    const ciphertext = $(this).data("ciphertext");
    var buttonElement = document.getElementById(ciphertext);
    var codeValue = buttonElement.getAttribute('code');
    window.location.replace('solicitudSedeSocial.php?ID=' + ciphertext + '&code=' + codeValue);
});

function enviarSolicitud(estado_tramite) {
    // Se validan que todos los campos tengan algún valor

    // ---------------
    // Se guardan los datos o se editan si fue modificado
    guardarDocsTramites(estado_tramite);


}

/*=============================================
ELIMINAR LA SOLICITUD, DOCUMENTOS Y FORMULARIO
=============================================*/
$(document).on("click", ".btn-delete-row", function() {
    var ciphertext = $(this).data("ciphertext");
    Swal.fire({
        title: '¿Desea eliminarlo?',
        text: "No podrás revertir esta acción.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar.'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../../controller/tramite.php?op=deleteReserva", { ciphertext: ciphertext }, function(e) {

                if (e == "ok") {
                    Swal.fire({
                        title: e,
                        text: "El documento se eliminó correctamente.",
                        icon: "success",
                        showCancelButton: true,
                        confirmButtonColor: "#3d85c6",
                        confirmButtonText: "OK"
                    });
                    tabla.ajax.reload();
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
});

init();

/*=============================================
CURSOS
=============================================*/


async function guardarPortadaCurso() {
    try {
        await Promise.all(arrayFiles.map(async(file) => {
            const datosMultimedia = new FormData();
            datosMultimedia.append("file", file.value);
            try {
                await $.ajax({
                    url: "../../controller/certificacion.php?op=insertPortadaCurso",
                    method: "POST",
                    data: datosMultimedia,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        console.log(data);
                        imagen_portada = data;
                    }
                });
            } catch (error) {
                // Handle errors if necessary
                console.error("Error uploading document:", error);
                throw error; // Re-throw the error to break out of Promise.all
            }
        }));

    } catch (error) {
        // Handle any error that might occur during the process
        console.error("Error in guardarDocsTramites:", error);
    }
}

function cargarBotones() {
    var permisos = $("#permisos").val();
    let lettersArray = permisos.split(/-/);
    lettersArray.forEach(function(element) {
        if (element == "M") {
            var targetElement = document.getElementById("guardar_datos_btn");
            // Change its styles
            targetElement.style.display = "block";
        }
    });

    return;
}