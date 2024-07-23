function init() {
}

$(document).ready(function () {
    // Extraer el ID del registro a modificar
    var currentURL = window.location.href;
    // Use a regular expression to extract the ID from the URL
    var match = currentURL.match(/[\?&]ID=([^&]*)/);
    if (match) {
        // Extracted ID is in match[1]
        idEncrypted = match[1];
        $('#idEncrypted').val(idEncrypted);
        cargarDatosTramite(idEncrypted);

    }
    tabla = $('#tramites_data').dataTable({
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
            url: '../../controller/administracion.php?op=listar_tramites',
            type: "post",
            dataType: "json",
            error: function (e) {
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


    cargarEstados();

});

function cargarEstados() {
    //Carga del combo estados
    $.post("../../controller/administracion.php?op=comboEstados", { idEncrypted: idEncrypted }, function (data) {
        $('#estado').html(data);
    });
}

$(document).on("click", ".btn-editar-tramite", function () {
    const ciphertext = $(this).data("ciphertext");
    window.location.replace('editarTramite.php?ID=' + ciphertext + '');
});

$(document).on("click", ".btn-delete-estado", function () {
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
            $.post("../../controller/administracion.php?op=deleteEstadoTramite", { ciphertext: ciphertext }, function (e) {

                if (e == "ok") {
                    Swal.fire({
                        title: e,
                        text: "El estado se eliminó correctamente.",
                        icon: "success",
                        showCancelButton: true,
                        confirmButtonColor: "#3d85c6",
                        confirmButtonText: "OK"
                    });
                    $("#datos_estado_tramite_form").load(" #datos_estado_tramite_form > *");
                    $("#estados_area").load(" #estados_area > *");
                    close_bloque_estado();
                }
                else {
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

$(document).on("click", ".btn-editar-estado-tram", function () {
    openBloqueLeccion();
    $.post("../../controller/certificacion.php?op=mostrarLeccion", { leccion_id: leccion_id }, function (data) {
        try {
            // Parse the JSON response
            var jsArray = JSON.parse(data);
            // Check if the array is not empty
            if (jsArray.length > 0) {
                // Use the keys of the first element to dynamically set values
                var keys = Object.keys(jsArray[0]);

                // Iterate through the array using forEach
                jsArray.forEach(function (element) {
                    // Access each element in the array here
                    keys.forEach(function (key) {
                        $('#' + key).val(element[key]);
                    });

                });
            }
        } catch (error) {
            console.error("Error parsing JSON:", error);
        }
    });

});

function cargarDatosTramite(idEncrypted) {
    $.post("../../controller/administracion.php?op=mostrarTramite", { tramite_id: idEncrypted }, function (data) {
        data = JSON.parse(data);
        $('#nombre_tramite').val(data.nombre_tramite);
        $('#idEncryptedEstado').val(data.nombre_tramite);
        $('#tipo_tramite').val(data.tipo_tramite);
        $('#tipo_tramite').trigger('change');
    });
}

function openBloqueEstado() {
    cargarEstados();
    var targetElement = document.getElementById("bloque_estado_tramite");
    // Change its styles
    targetElement.style.display = "block";
    var form = $("#datos_estado_tramite_form")[0];
    for (var i = 0; i < form.elements.length; i++) {
        var element = form.elements[i];
        // Check if the element is an input, textarea, or select
        if (element.tagName === 'INPUT' || element.tagName === 'SELECT') {
            // Reset the value of the element
            element.value = '';
        }
        if (element.tagName === 'TEXTAREA') {
            var summernoteInstance = $('#descripcion').summernote();

            // Clear the contents of the Summernote instance
            summernoteInstance.summernote('reset');
        }
    }

}

function mostrarEstado(estado_tramite_id) {
    openBloqueEstado();
    $.post("../../controller/administracion.php?op=agregarComboEstados", { idEncrypted: estado_tramite_id }, function (data) {
        var estado = JSON.parse(data);
        $('#estado').append('<option value="' + estado["estado_id"] + '" selected>' + estado["nombre_estado"] + '</option>');

        $.post("../../controller/administracion.php?op=mostrarEstadoTramite", { estado_id: estado_tramite_id }, function (data) {
            try {
                // Parse the JSON response
                var jsArray = JSON.parse(data);
                // Check if the array is not empty
                if (jsArray.length > 0) {
                    // Use the keys of the first element to dynamically set values
                    var keys = Object.keys(jsArray[0]);
                    // Iterate through the array using forEach
                    jsArray.forEach(function (element) {
                        // Access each element in the array here
                        keys.forEach(function (key) {
                            if (key == "estado_id") {
                                $('#' + key).val(element[key]).trigger('change');
                            }
                            else {
                                $('#' + key).val(element[key]);
                            }
                        });


                    });
                }
            } catch (error) {
                console.error("Error parsing JSON:", error);
            }
        });
    });


}

/*=============================================
CREA EL NUEVO ESTADO
=============================================*/
const result = 0;

function guardarEstadoTramite() {

    var formData = new FormData($("#datos_estado_tramite_form")[0]);
    formData.append("tramite_id", $("#idEncrypted").val());

    /* TODO: validamos si los campos tienen informacion antes de guardar */
    if ($('#nombre_estado').val() == '' || $('#paso_estado').val() == '' || $('#duracion_estimada').val() == '') {
        Swal.fire("Advertencia!", "Debe completar todos los campos", "warning");
    } else {
        /* TODO: Guardar nuevo estado de trámite */
        if($("#estado_tramite_id").val() == ""){
            $.ajax({
                url: "../../controller/administracion.php?op=insertEstadoTramite",
                method: "POST",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
    
                    if (data == "ok") {
                        $("#datos_estado_tramite_form").load(" #datos_estado_tramite_form > *");
                        $("#estados_area").load(" #estados_area > *");
                        close_bloque_estado();
                    }
    
                }
            });
        }
        else{
            $.ajax({
                url: "../../controller/administracion.php?op=updateEstadoTramite",
                method: "POST",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
    
                    if (data == "ok") {
                        $("#datos_estado_tramite_form").load(" #datos_estado_tramite_form > *");
                        $("#estados_area").load(" #estados_area > *");
                        close_bloque_estado();
                    }
    
                }
            });
        }
        
    }
}

function closePanel() {
    var targetElement = document.getElementById("panel_igualar_estados");
    // Change its styles
    targetElement.style.display = "none";
}

function generalizarEstadosTipoTramite(){
    // var formData = new FormData($("#datos_estado_tramite_form")[0]);
    // formData.append("tipo_tramite", $("#tipo_tramite").val());
    Swal.fire({
        title: '¿Estás seguro de que deseas igualar todos los estados del mismo tipo de trámite?',
        text: "No podrás revertir esta acción.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, estoy seguro.'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../../controller/administracion.php?op=igualarEstadosTipoTramite", { tipo_tramite: $("#tipo_tramite").val(), tramite_id: idEncrypted }, function (e) {

                if (e == "ok") {
                    Swal.fire({
                        title: e,
                        text: "Todos los trámites del mismo tipo a este ya tienen los mismos estados.",
                        icon: "success",
                        showCancelButton: true,
                        confirmButtonColor: "#3d85c6",
                        confirmButtonText: "OK"
                    });
                    window.location.replace('listarTramites.php');
                }
                else {
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

