var tabla;
//Se utiliza para listar los documentos del usuario
var idEncrypted = "";
var arrayFiles = [];
var arrayId = [];

function init() {

}

$(document).ready(function() {
    var currentURL = window.location.href;
    var match_cuestionario = currentURL.match(/[\?&]cuestionario.php([^&]*)/);
    var match_trabajoPractico = currentURL.match(/[\?&]trabajoPractico.php([^&]*)/);

    if (match_cuestionario == null || match_trabajoPractico == null) {
        $('#observacion').summernote({
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


        tabla = $('#concursos_data').dataTable({
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
                url: '../../controller/concursos.php?op=listar_x_usu',
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

    }



    /* TODO: Llenar Combo trámites */

    $.post("../../controller/concursos.php?op=comboTramitesConcursos", { tipo_solicitud: $("#tipo_solicitud").val() }, function(data) {
        $('#tramite_nuevo').html(data);

    });


    // Use a regular expression to extract the ID from the URL
    var match_code = currentURL.match(/[\?&]code=([^&]*)/);
    if (match_code) {
        // Extracted ID is in match_code[1]
        tramite_code = match_code[1];

        $('#tramite_code').val(tramite_code);

        $.post("../../controller/concursos.php?op=getTipoSolicitud", { tramite_code: tramite_code }, function(data) {
            $('#tipo_solicitud').val(data);
            $.post("../../controller/concursos.php?op=comboCursos", { tipo_solicitud: $("#tipo_solicitud").val(), tramite_code: tramite_code }, function(data) {
                $('#seccion_curso').html(data);
            });
            cancelarReturnPage();
        });
    }

    // Use a regular expression to extract the ID from the URL
    var match = currentURL.match(/[\?&]ID=([^&]*)/);
    if (match) {
        // Extracted ID is in match[1]
        idEncrypted = match[1];


        $.post("../../controller/concursos.php?op=comboCursos", {
            tipo_solicitud: $("#tipo_solicitud").val(),
            tramite_code: $('#tramite_code').val()
        }, function(data) {

            $('#seccion_curso').html(data);
            cargarInscripcion(idEncrypted);

        });

        $('#idEncrypted').val(idEncrypted);

    } else {
        match = currentURL.match(/[\?&]IDCURSO=([^&]*)/);
        if (match) {
            // Extracted ID is in match[1]
            idEncrypted = match[1];

            cargarCurso(idEncrypted);

            $('#idEncrypted').val(idEncrypted);

            var cancelarSeccionLink = document.getElementById("cancelarSeccion");

            // Add a click event listener to the anchor tag
            if (cancelarSeccionLink != null && cancelarSeccionLink != "") {
                cancelarSeccionLink.addEventListener("click", function(event) {
                    // Construct the URL with the ID
                    var url = "administrarSecciones.php?IDCURSO=" + idEncrypted;

                    // Set the href attribute of the anchor tag
                    this.setAttribute("href", url);
                });
            }

            tabla = $('#secciones_data').dataTable({
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
                    url: '../../controller/concursos.php?op=listar_secciones_x_curso',
                    type: "post",
                    data: { idEncrypted: idEncrypted },
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


        } else {
            match = currentURL.match(/[\?&]IDSECCION=([^&]*)/);
            if (match) {
                // Extracted ID is in match[1]
                idEncrypted = match[1];
                cargarSeccion(idEncrypted);

                $('#idEncrypted').val(idEncrypted);

            } else {
                $('#idEncrypted').val("");
            }

        }

    }

});
var id = 0;

function cargarIdDoc(idDiv) {
    id = idDiv;

}

function cargarDptos(callback) {
    var pais = $('#pais_nacimiento').val();

    $.post("../../controller/tramite.php?op=comboDepartamentos", { pais: pais }, function(data) {
        $('#departamento_nacimiento').html(data);

        // Call the callback function if provided
        if (typeof callback === "function") {
            callback();
        }
    });
}

function abrirNuevoCurso() {
    if ($("#tramite_nuevo").val() != undefined && $("#tramite_nuevo").val() > 0) {
        window.location.replace('inscripcion.php?code=' + $('#tramite_nuevo').val() + '');

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

function cargarBarrios(ciudad) {
    if (ciudad == "") {
        var ciudad = $('#ciudad_residencia').val();
    }
    $.post("../../controller/tramite.php?op=comboBarrios", { ciudad: ciudad }, function(data) {
        $('#barrio_residencia').html(data);
    });
}

function cargarCiudades(dpto_id) {
    var departamento = 0;
    if (dpto_id == "departamento_nacimiento") {
        departamento = $('#departamento_nacimiento').val();
    } else if (dpto_id == "departamento_residencia") {
        departamento = $('#departamento_residencia').val();
    } else if (dpto_id == "departamento_publico") {
        departamento = $('#departamento_publico').val();
    } else if (dpto_id == "departamento_privado") {
        departamento = $('#departamento_privado').val();
    }
    $.post("../../controller/tramite.php?op=comboCiudades", { departamento: departamento }, function(data) {
        if (dpto_id == "departamento_nacimiento") {
            $('#ciudad_nacimiento').html(data);
        } else if (dpto_id == "departamento_residencia") {
            $('#ciudad_residencia').html(data);
        } else if (dpto_id == "departamento_publico") {
            $('#ciudad_publico').html(data);
        } else if (dpto_id == "departamento_privado") {
            $('#ciudad_privado').html(data);
        }

    });
}

function cargarCategorias(callback) {
    $.post("../../controller/certificacion.php?op=comboCategoriasCursos", function(data) {
        $('#categoria_curso').html(data);
        // Call the callback function if provided
        if (typeof callback === "function") {
            callback();
        }
    });
}


async function guardarDocsTramites() {

    for (var i = 0; i < arrayFiles.length; i++) {

        var datosMultimedia = new FormData();
        datosMultimedia.append("id", arrayFiles[i].id);
        datosMultimedia.append("file", arrayFiles[i].value);
        datosMultimedia.append('tramite_code', $('#tramite_code').val());
        datosMultimedia.append('tramite_gestionado_idEncrypted', $('#idEncrypted').val());

        await $.ajax({
            url: "../../controller/tramite.php?op=insertDocumentos",
            method: "POST",
            data: datosMultimedia,
            cache: false,
            contentType: false,
            processData: false
        });
    }
    return;

}

async function guardarSolicitud(estado_tramite) {

    await guardarDocsTramites();

    /* TODO: Array del form inscripción */
    var formData = new FormData($("#inscripcion_form")[0]);
    formData.append('tiposDocumentos', JSON.stringify(arrayId));
    formData.append('estado_tramite', estado_tramite);
    formData.append('nombre_autor', $('#nombre_autor').val());
    formData.append('institucion_autor', $('#institucion_autor').val());
    formData.append('pais', $('#pais').val());
    formData.append('documento_identidad', $('#documento_identidad').val());
    formData.append('tipo_vinculo', $('#tipo_vinculo').val());
    formData.append('telefono', $('#telefono').val());
    formData.append('correo', $('#correo').val());
    formData.append('titulo_investigacion', $('#titulo_investigacion').val());
    formData.append('anio_trabajo', $('#anio_trabajo').val());
    formData.append('observacion', $('#observacion').val());


    /* TODO: Guardar inscripción */
    if ($("#idEncrypted").val() == "") {

        await $.ajax({
            url: "../../controller/concursos.php?op=insert_tramite_concurso",
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
                            if ($("#tipo_solicitud").val() == 'CONCUROS') {
                                window.location.replace('listarConcursos.php');
                            } else {
                                window.location.replace('listarConcursos.php');
                            }
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
        formData.append("idEncrypted", $("#idEncrypted").val());
        await $.ajax({

            url: "../../controller/concursos.php?op=update",
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
                            if ($("#tipo_solicitud").val() == 'CERT') {
                                window.location.replace('listarCertificacionesDisponibles.php');
                            } else {
                                window.location.replace('listarConcursos.php');
                            }
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

$(document).on("click", ".btn-abrir-inscripcion", function() {
    const ciphertext = $(this).data("ciphertext");
    var buttonElement = document.getElementById(ciphertext);
    var codeValue = buttonElement.getAttribute('code');

    window.location.replace('inscripcion.php?ID=' + ciphertext + '&code=' + codeValue);
});

$(document).on("click", ".btn-ver-observaciones", function() {
    const ciphertext = $(this).data("ciphertext");
    window.location.replace('verObservaciones.php?ID=' + ciphertext + '');

});

function cargarInscripcion(tramite_gestionado_id) {
    /* TODO: Mostramos informacion del documento en inputs */
    $.post("../../controller/certificacion.php?op=mostrar", { tramite_gestionado_id: tramite_gestionado_id }, function(data) {
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

function enviarSolicitud(estado_tramite) {
    // Se validan que todos los campos tengan algún valor

    // ---------------
    // Se guardan los datos o se editan si fue modificado
    guardarSolicitud(estado_tramite);


}

/*=============================================
ELIMINAR LA SOLICITUD, DOCUMENTOS Y FORMULARIO
=============================================*/
$(document).on("click", ".btn-delete-row", function() {
    var ciphertext = $(this).data("ciphertext");
    var estado_tramite = 10;

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
            $.post("../../controller/concursos.php?op=delete", { ciphertext: ciphertext, estado_tramite_id: estado_tramite }, function(e) {
                // Depuración: mostrar el valor de 'e'
                console.log("Response from server:", e);

                // Asegurarse de que 'e' no tiene espacios en blanco ni saltos de línea
                var response = e.trim();

                if (response === "ok") {
                    Swal.fire({
                        title: 'Eliminado',
                        text: "El documento se eliminó correctamente.",
                        icon: "success",
                        showCancelButton: false, // Cambiar a false si no necesitas un botón de cancelar aquí
                        confirmButtonColor: "#3d85c6",
                        confirmButtonText: "OK"
                    });
                    tabla.ajax.reload();
                } else {
                    Swal.fire({
                        title: "Error",
                        text: response,
                        icon: "error",
                        showCancelButton: false, // Cambiar a false si no necesitas un botón de cancelar aquí
                        confirmButtonColor: "#3d85c6",
                        confirmButtonText: "OK"
                    });
                }
            });
        }
    })
});

init();



$('.verifyButton').click(function() {
    var button = $(this);

    // Add loading icon
    button.html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Verificando...');

    // Simulate verification process (you can replace this with your actual verification logic)
    setTimeout(function() {
        var isVerified = Math.random() < 0.5; // Simulating success or failure

        // Change button text and icon based on verification result
        if (isVerified) {
            button.removeClass('btn-default').addClass('btn-success');
            button.html('<span class="glyphicon glyphicon-ok" style="color:#9bcc86"></span> Verificado');
        } else {
            button.removeClass('btn-default').addClass('btn-danger');
            button.html('<span class="glyphicon glyphicon-remove" style="color:#df7e7e"></span> No encontrado');
        }
    }, 2000); // Simulating a 2-second verification process
});


/*=============================================
ENTREGAS TRABAJOS PRÁCTICOS Y CUESTIONARIOS
=============================================*/
var doc_guardado = "";

async function entregarTP() {
    var carpeta = "seccion" + $("#seccion_id").val() + "/TP" + $("#tarea_id").val() + "/";

    // Wait for the guardarDocumento function to complete
    await guardarDocumento(carpeta);

    var formData = new FormData($("#trabajo_practico_form")[0]);
    formData.append("doc_guardado", doc_guardado);
    formData.append("seccion_id", $("#seccion_id").val());
    formData.append("tarea_id", $("#tarea_id").val());

    /* TODO: Guardar tp */
    $.ajax({
        url: "../../controller/certificacion.php?op=insertTrabajoPractico",
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
                        window.location.reload();
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

async function guardarDocumento(carpeta) {
    try {
        await Promise.all(arrayFiles.map(async(file) => {
            const datosMultimedia = new FormData();
            datosMultimedia.append("file", file);
            datosMultimedia.append("carpeta", carpeta);
            datosMultimedia.append("tarea_id", $("#tarea_id").val());
            datosMultimedia.append("curso_id", $("#curso_id").val());
            try {
                await $.ajax({
                    url: "../../controller/certificacion.php?op=insertDocumentosVarios",
                    method: "POST",
                    data: datosMultimedia,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        console.log(data);
                        doc_guardado = data;
                    }
                });
            } catch (error) {
                // Handle errors if necessary
                console.error("Error al guardar el documento del trámite:", error);
                throw error; // Re-throw the error to break out of Promise.all
            }
        }));
    } catch (error) {
        // Handle any error that might occur during the process
        console.error("Error al guardar el documento del trámite:", error);
    }
}

function corregirTP() {
    var formData = new FormData($("#correccion_form")[0]);
    $.ajax({
        url: "../../controller/instructor.php?op=corregirTP",
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
                        window.location.reload();
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

function startTimer(duration) {
    let timer = duration,
        hours, minutes, seconds;
    const display = document.getElementById('timer');

    const countdown = setInterval(() => {
        hours = Math.floor(timer / 3600);
        minutes = Math.floor((timer % 3600) / 60);
        seconds = timer % 60;

        hours = hours < 10 ? '0' + hours : hours;
        minutes = minutes < 10 ? '0' + minutes : minutes;
        seconds = seconds < 10 ? '0' + seconds : seconds;

        display.textContent = hours + ':' + minutes + ':' + seconds;

        if (--timer < 0) {
            clearInterval(countdown);
            display.textContent = '00:00:00';
        }
    }, 1000);
}

function comenzarCuestionario() {
    var return_message = 'ok';
    if (document.getElementById("intentos_permitidos").textContent > document.getElementById("intentos_realizados_c").textContent) {
        document.getElementById("inicio_cuestionario").style.display = "none";
        document.getElementById("seccion_preguntas").style.display = "block";
        $.ajax({
            url: "../../controller/certificacion.php?op=insertCuestionario",
            type: "POST",
            data: { tarea_id: $("#tareaEncrypted").val() }
        });

        // Append the timer div to the countdown div
        var temporizador = document.getElementById('countdown');
        var divTimer = document.createElement('div');
        divTimer.id = 'timer';
        divTimer.textContent = '00:00:00';
        temporizador.appendChild(divTimer);

        // Get the time limit from the hidden input
        const minutes = document.getElementById('tiempo_limite').value;

        // Start the timer
        startTimer(minutes * 60);
    } else {
        Swal.fire({
            title: "Ya no tiene intentos disponibles",
            text: "Ya no puede volver a rendir",
            icon: "success",
            showCancelButton: true,
            confirmButtonColor: "#3d85c6",
            confirmButtonText: "OK"
        })
        return_message = 'error';
    }


    return return_message;
}


function entregarCuestionario() {
    var formData = new FormData($("#cuestionario_form")[0]);
    formData.append("seccion_id", $("#seccion_id").val());
    formData.append("tarea_id", $("#tarea_id").val());

    // Collect exercise data
    const exercises = document.querySelectorAll('.ejercicio');
    const exerciseData = [];

    exercises.forEach(exercise => {
        const exerciseId = exercise.id;
        const options = [];
        const exerciseBox = exercise.closest('.comments-box');

        if (exerciseBox.querySelector('.form-check-input[type="checkbox"]')) {
            // Multiple choice options
            exerciseBox.querySelectorAll('.form-check-input[type="checkbox"]').forEach(option => {
                options.push({
                    id: option.id,
                    checked: option.checked
                });
            });
        } else if (exerciseBox.querySelector('.form-check-input[type="radio"]')) {
            // Single choice or true/false options
            const selectedOption = exerciseBox.querySelector('.form-check-input[type="radio"]:checked');
            if (selectedOption) {
                if (selectedOption.name == 'v_f') {
                    options.push({
                        id: selectedOption.id,
                        text: selectedOption.true_false
                    });
                } else {
                    exerciseBox.querySelectorAll('.form-check-input[type="radio"]').forEach(option => {
                        options.push({
                            id: option.id,
                            checked: option.checked
                        });
                    });
                }

            }
        } else if (exerciseBox.querySelector('input[type="text"]')) {
            // Short answer or completion type
            const textInput = exerciseBox.querySelector('input[type="text"]');
            options.push({
                id: exerciseId,
                text: textInput.value
            });
        }

        exerciseData.push({
            exerciseId: exerciseId,
            options: options
        });
    });

    // Append the exercise data to the formData object
    formData.append("exercise_data", JSON.stringify(exerciseData));
    console.log(JSON.stringify(exerciseData));
    // Send the AJAX request
    $.ajax({
        url: "../../controller/certificacion.php?op=guardarCuestionario",
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
                        window.location.reload();
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