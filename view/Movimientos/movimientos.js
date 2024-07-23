function init() {

    actualizar_img();
    $("#aprobacion_final").hide();
    $("#obs_comite").hide();
}
idEncrypted = 0;

$(document).ready(function() {
    // Extraer el ID del registro a modificar
    var currentURL = window.location.href;
    // Use a regular expression to extract the ID from the URL
    var match = currentURL.match(/[\?&]ID=([^&]*)/);
    // // Check if a match is found
    if (match) {
        // Extracted ID is in match[1]
        idEncrypted = match[1];
        cargarTramite(idEncrypted);
        $('#idEncrypted').val(match[1]);
        $.post("../../controller/movimiento.php?op=cargarTitulo", { idTramiteGestionado: match[1] }, function(data) {
            $('.tramite_nombre').html(data);
        });
    }
    // Cargar Tramites para la busqueda
    // $.post("../../controller/tramite.php?op=comboTramitesTodos", function(data) {
    //     $('#tramiteSelect').html(data);
    // });


});


document.addEventListener('DOMContentLoaded', function() {


    $('#searchInput').on('input', function() {
        let searchText = $(this).val().toLowerCase();

        $('tbody .table-tramites').each(function() {
            let rowText = $(this).text().toLowerCase();

            if (rowText.includes(searchText)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    $('#searchAsignada').on('input', function() {
        let searchText = $(this).val().toLowerCase();

        $('tbody .table-asignadas').each(function() {
            let rowText = $(this).text().toLowerCase();

            if (rowText.includes(searchText)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});


// Función para que el usuario se asigne trámites en la bandeja del área en la que se encuentra
function asignarmeTramites() {
    // Get all selected checkboxes
    var selectedRows = $(".tramite_area_checkbox:checked").map(function() {
        return this.id;
    }).get();
    $.post("../../controller/movimiento.php?op=asignarmeTramites", { selectedRows: selectedRows }, function(data) {
        if (data == "ok") {
            document.location.reload(true);
        } else {
            alert(data);
        }

    });
}

// Abrir el trámite para su verificación
$(document).on("click", ".btn-open-solicitud", function() {
    const ciphertext = $(this).data("ciphertext");
    window.location.replace('revisarTramiteSolicitado.php?ID=' + ciphertext + '');
});

function cargarBarrios() {
    var ciudad = $('#ciudad_solicitante').val();
    $.post("../../controller/tramite.php?op=comboBarrios", { ciudad: ciudad }, function(data) {
        $('#barrio_solicitante').html(data);
    });
}

function cargarTramite(tramite_gestionado_id) {

    $('.observacion').summernote({
        height: 150,
        lang: "es-ES",
        popover: {
            image: [],
            link: [],
            air: []
        },
        callbacks: {
            onImageUpload: function(image) {
                console.log("Image detect...");
                myimagetreat(image[0]);
            },
            onPaste: function(e) {
                console.log("Text detect...");
            }
        },
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']]
        ]
    });
    $.post("../../controller/movimiento.php?op=cargarObs", { tramite_gestionado_id: tramite_gestionado_id }, function(data) {
        data = JSON.parse(data);
        $('#observacion').summernote('code', data.observacion);
        $('#observacion_inscripcion').summernote('code', data.observacion_inscripcion);
    });

    /* TODO: Llenar bancos */
    $.post("../../controller/tramite.php?op=comboBancos", function(data) {
        $('#banco').html(data);
    });

    /* TODO: Llenar tipos de cuentas bancarias */
    $.post("../../controller/tramite.php?op=comboTiposCuentas", function(data) {
        $('#tipo_cuenta').html(data);
    });
}

function enviarObservaciones(estado_tramite_gestionado) {

    var formData = new FormData();

    // Loop through all selects with the class 'estado_documento'
    var estadosDocs = {};
    document.querySelectorAll('.estado_documento').forEach(function(select) {
        var selectId = select.id;
        var selectValue = select.value;
        estadosDocs[selectId] = selectValue;
    });

    // Obtener los valores de los checkboxes y agregarlos al formData
    var tramiteJsonRequisito = {
        antiguedad_requerida: document.getElementById('antiguedad_requerida').checked,
        edad_requerida: document.getElementById('edad_requerida').checked,
        sub_recibido: document.getElementById('sub_recibido').checked,
        documentos_completos: document.getElementById('documentos_completos').checked,
        solidaridad_al_dia: document.getElementById('solidaridad_al_dia').checked,
        obligaciones_al_dia: document.getElementById('obligaciones_al_dia').checked,
        presentacion_a_tiempo: document.getElementById('presentacion_a_tiempo').checked
    };

    formData.append('estadosDocs', JSON.stringify(estadosDocs));
    formData.append('observacion', $("#observacion").val());
    formData.append('idTramiteGestionado', idEncrypted);
    formData.append('estadoTramiteGestionado', estado_tramite_gestionado);
    // Agregar tramite_json_requisito al formData
    formData.append('tramite_json_requisito', JSON.stringify(tramiteJsonRequisito));

    $.ajax({
        type: "POST",
        url: "../../controller/movimiento.php?op=enviarObservaciones",
        data: formData,
        processData: false, // Important: prevent jQuery from transforming the data
        contentType: false, // Important: let the server handle the content type
        success: function(data) {

            if (data = "ok") {
                Swal.fire({
                    title: "¡Listo!",
                    text: "Registrado Correctamente",
                    icon: "success",
                    showCancelButton: true,
                    confirmButtonColor: "#3d85c6",
                    confirmButtonText: "OK"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.replace('listarSolicitudes.php');
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

        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });

}

function aprobarSolicitud(estado_tramite, estado_doc) {
    var formData = new FormData();
    formData.append('observacion', $("#observacion").val());
    formData.append('idTramiteGestionado', idEncrypted);
    formData.append('estado_tramite', estado_tramite);
    formData.append('estado_doc', estado_doc);

    $.ajax({
        type: "POST",
        url: "../../controller/movimiento.php?op=aprobarSolicitud",
        data: formData,
        processData: false, // Important: prevent jQuery from transforming the data
        contentType: false, // Important: let the server handle the content type
        success: function(data) {

            if (data = "ok") {
                Swal.fire({
                    title: "¡Listo!",
                    text: "Registrado Correctamente",
                    icon: "success",
                    showCancelButton: true,
                    confirmButtonColor: "#3d85c6",
                    confirmButtonText: "OK"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.replace('listarSolicitudes.php');
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

        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });

}

/*=============================================
ADMINISTRAR INSCRIPCIONES
=============================================*/


function aprobarInscripciones(estado_tramite){
    var selectedRows = $(".tramite_area_checkbox:checked").map(function () {
        return this.id;
    }).get();
    $.post("../../controller/movimiento.php?op=aprobarInscripciones", { selectedRows: selectedRows, estado_tramite: estado_tramite }, function (data) {
        if (data == "ok") {
            document.location.reload(true);
        }
        else {
            alert(data);
        }

    });
}

function rechazarInscripciones(estado_tramite){
    var selectedRows = $(".tramite_area_checkbox:checked").map(function () {
        return this.id;
    }).get();
    $.post("../../controller/movimiento.php?op=rechazarInscripciones", { selectedRows: selectedRows, estado_tramite: estado_tramite }, function (data) {
        if (data == "ok") {
            document.location.reload(true);
        }
        else {
            alert(data);
        }

    });
}

function aprobarInscripcion(estado_tramite){
    $.post("../../controller/movimiento.php?op=aprobarInscripcion", { idEncrypted, estado_tramite }, function (data) {
        if (data == "ok") {
            window.location.replace('../Movimientos/administrarInscripciones.php'); 
        }
        else {
            alert(data);
        }

    });
}

function rechazarInscripcion(estado_tramite){
    $.post("../../controller/movimiento.php?op=rechazarInscripcion", {idEncrypted, estado_tramite}, function (data) {
        if (data == "ok") {
            window.location.replace('../Movimientos/administrarInscripciones.php'); 
        }
        else {
            alert(data);
        }

    });
}

$(document).on("click", ".btn-abrir-inscripcion", function () {
    const ciphertext = $(this).data("ciphertext");
    var buttonElement = document.getElementById(ciphertext);
    var codeValue = buttonElement.getAttribute('code');
    window.location.replace('../Certificaciones/inscripcion.php?ID=' + ciphertext + '&code=' + codeValue);
});



$(document).on("click", ".btn-abrir-inscripcion-curso", function() {
    const ciphertext = $(this).data("ciphertext");
    var buttonElement = document.getElementById(ciphertext);
    var codeValue = buttonElement.getAttribute('code');
    window.location.replace('../Certificaciones/inscripcionCurso.php?ID=' + ciphertext + '&code=' + codeValue);
});

function cargarInscripcion(tramite_gestionado_id) {
    /* TODO: Mostramos informacion del documento en inputs */
    $.post("../../controller/certificacion.php?op=mostrar", { tramite_gestionado_id: tramite_gestionado_id }, function (data) {
        try {
            // Parse the JSON response
            var jsArray = JSON.parse(data);
            // Check if the array is not empty
            if (jsArray.length > 0) {
                // Use the keys of the first element to dynamically set values
                $('#observacion').summernote('code', jsArray[0]['observacion']);
                $('#seccion_curso').val(jsArray[0]['curso_id']).trigger('change');
            }
        } catch (error) {
            console.error("Error parsing JSON:", error);
        }
    });
}