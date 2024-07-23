var tabla;
var idEncrypted = "";
var arrayFiles = [];
var arrayId = [];

function init() {

}

$(document).ready(function () {
    $('.descripcion').summernote({
        height: 150,
        lang: "es-ES",
        popover: {
            image: [],
            link: [],
            air: []
        },
        callbacks: {
            onImageUpload: function (image) {
                console.log("Image detect...");
                myimagetreat(image[0]);
            },
            onPaste: function (e) {
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

    $('#observacion').summernote({
        height: 150,
        lang: "es-ES",
        popover: {
            image: [],
            link: [],
            air: []
        },
        callbacks: {
            onImageUpload: function (image) {
                console.log("Image detect...");
                myimagetreat(image[0]);
            },
            onPaste: function (e) {
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

    $('#aprendizaje').summernote({
        height: 150,
        lang: "es-ES",
        popover: {
            image: [],
            link: [],
            air: []
        },
        callbacks: {
            onImageUpload: function (image) {
                console.log("Image detect...");
                myimagetreat(image[0]);
            },
            onPaste: function (e) {
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

    $('#addOption').on('click', function () {
        var numOptions = $('#fieldset_mc .inline').length;
        var newOptionNumber = numOptions + 1;

        var newOption = `
            <div class="inline">
                <input type="checkbox" name="multiple${newOptionNumber}">
                <input type="text" class="form-control" name="multiple${newOptionNumber}" placeholder="Opción ${newOptionNumber}">
            </div>
            <br>
        `;
        $('#fieldset_mc').append(newOption);
    });

    $('#removeOption').on('click', function () {
        var numOptions = $('#fieldset_mc .inline').length;
        if (numOptions > 1) {
            $('#fieldset_mc .inline').last().next('br').remove(); // Remove the <br> after the last option
            $('#fieldset_mc .inline').last().remove(); // Remove the last option
        }
    });

    $('#addRadio').on('click', function () {
        var numOptions = $('#fieldset_sc .inline').length;
        var newOptionNumber = numOptions + 1;
        var newOption = `
            <div class="inline">
                <input type="radio" name="optionsRadios${newOptionNumber}" id="radio${newOptionNumber}">
                <input type="text" class="form-control" name="simple${newOptionNumber}" placeholder="Opción ${newOptionNumber}">
            </div>
            <br>
        `;
        $('#fieldset_sc').append(newOption);
    });

    $('#removeRadio').on('click', function () {
        var numOptions = $('#fieldset_sc .inline').length;
        if (numOptions > 1) {
            $('#fieldset_sc .inline').last().next('br').remove(); // Remove the <br> after the last option
            $('#fieldset_sc .inline').last().remove(); // Remove the last option
        }
    });


    $.post("../../controller/certificacion.php?op=comboTramitesCapacitaciones", function (data) {
        $('#tipo_tramite').html(data);
    });

    tabla = $('#mis_cursos_data').dataTable({
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
            url: '../../controller/instructor.php?op=listar_cursos_x_instructor',
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

    var currentURL = window.location.href;
    match = currentURL.match(/[\?&]IDSECCION=([^&]*)/);
    matchTarea = currentURL.match(/[\?&]IDTAREA=([^&]*)/);
    if (match) {
        // Extracted ID is in match[1]
        idEncryptedSeccion = match[1];
        $('#idEncryptedSeccion').val(idEncryptedSeccion);

        tabla = $('#tareas_data').dataTable({
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
                url: '../../controller/instructor.php?op=listar_tareas_x_seccion',
                type: "post",
                data: { seccion_id: match[1] },
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
    }
    else if (matchTarea) {
        console.log(matchTarea[1]);
        cargarTarea(matchTarea[1]);
    }

    $.post("../../controller/instructor.php?op=comboCategoriasCursos", function (data) {
        $('#categoria_curso').html(data);
    });

    if (window.location.href.indexOf("tarea.php") > -1) {
        $(".multimediaFisica").dropzone({
            url: "../Tramites/plugins/dropzone/dropzone.js",
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg, image/png, application/pdf",
            maxFilesize: 10,
            maxFiles: 1,
            init: function () {
                this.on("addedfile", function (file) {
                    arrayFiles.push({ id: id, value: file });
                    arrayId.push(id);
                });
                this.on("removedfile", function (file) {
                    var index = arrayFiles.indexOf(file);
                    arrayFiles.splice(index, 1);
                    arrayId.splice(index);
                });
            }
        });

    }
    var currentURL = window.location.href;
    // Use a regular expression to extract the ID from the URL
    var match_code = currentURL.match(/[\?&]code=([^&]*)/);
    if (match_code) {
        // Extracted ID is in match_code[1]
        tramite_code = match_code[1];

        $('#tramite_code').val(tramite_code);

        $.post("../../controller/certificacion.php?op=getTipoSolicitud", { tramite_code: tramite_code }, function (data) {
            $('#tipo_solicitud').val(data);
            $.post("../../controller/certificacion.php?op=comboCursos", { tipo_solicitud: $("#tipo_solicitud").val(), tramite_code: tramite_code }, function (data) {
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
        $.post("../../controller/certificacion.php?op=comboCursos", { tipo_solicitud: $("#tipo_solicitud").val(), tramite_code: tramite_code }, function (data) {
            $('#seccion_curso').html(data);
            cargarInscripcion(idEncrypted);

        });

        $('#idEncrypted').val(idEncrypted);
        console.log(idEncrypted);

    }
    else {
        match = currentURL.match(/[\?&]IDCURSO=([^&]*)/);
        if (match) {
            // Extracted ID is in match[1]
            idEncrypted = match[1];
            $.post("../../controller/certificacion.php?op=comboTramitesCapacitaciones", function (data) {
                $('#tipo_tramite').html(data);
                cargarCurso(idEncrypted);
            });

            $('#idEncrypted').val(idEncrypted);

            var cancelarSeccionLink = document.getElementById("cancelarSeccion");

            // Add a click event listener to the anchor tag
            if (cancelarSeccionLink != null && cancelarSeccionLink != "") {
                cancelarSeccionLink.addEventListener("click", function (event) {
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
                    url: '../../controller/instructor.php?op=listar_secciones_x_curso',
                    type: "post",
                    data: { idEncrypted: idEncrypted },
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


        }
        else {
            match = currentURL.match(/[\?&]IDSECCION=([^&]*)/);
            if (match) {
                // Extracted ID is in match[1]
                idEncrypted = match[1];
                cargarSeccion(idEncrypted);
                if (window.location.href.indexOf("tarea.php") <= -1) {
                    $('#idEncrypted').val(idEncrypted);
                }

            }
            else {
                $('#idEncrypted').val("");
            }

        }

    }
});

function cancelarReturnPage() {
    var targetElement = document.getElementById("cancelarMateriales");
    // Change its styles
    if (targetElement) {
        targetElement.href = "administrarSecciones.php?IDCURSO="+ document.getElementById("idEncryptedCurso").value;
    }

}

var id = 0;
function cargarIdDoc(idDiv) {
    id = idDiv;
}

/*=============================================
CURSOS
=============================================*/


/* TODO: Link para poder ver el curso */
$(document).on("click", ".btn-editar-curso", function () {
    const ciphertext = $(this).data("ciphertext");
    window.location.replace('editarCurso.php?IDCURSO=' + ciphertext + '');
});

$(document).on("click", ".btn-sections", function () {
    const ciphertext = $(this).data("ciphertext");
    window.location.replace('administrarSecciones.php?IDCURSO=' + ciphertext + '');
});

$(document).on("click", ".btn-delete-curso", function () {
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
            $.post("../../controller/instructor.php?op=deleteCurso", { ciphertext: ciphertext }, function (e) {
                console.log(e);
                if (e == "Curso eliminado") {
                    Swal.fire({
                        title: e,
                        text: "El curso se eliminó correctamente.",
                        icon: "success",
                        showCancelButton: true,
                        confirmButtonColor: "#3d85c6",
                        confirmButtonText: "OK"
                    });
                    tabla.ajax.reload();
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

function openCurso(curso_id) {
    window.location.replace('curso.php?IDCURSO=' + curso_id + '');
}
var imagen_portada = "";

async function guardarCurso() {
    /* TODO: Array del form inscripción */
    var formData = new FormData($("#datos_curso_form")[0]);
    imagen_portada = "";

    await guardarPortadaCurso(); // Wait for guardarPortadaCurso to complete
    console.log(imagen_portada);
    formData.append('imagen_portada', imagen_portada);
    formData.append('descripcion', $('#descripcion').val());
    formData.append('aprendizaje', $('#aprendizaje').val());

    if ($('#idEncrypted').val() == "") {
        $.ajax({
            url: "../../controller/instructor.php?op=insertCurso",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function (data) {
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
                            window.location.replace('cursosInstructores.php');
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
        formData.append('curso_id', $('#idEncrypted').val());
        $.ajax({
            url: "../../controller/instructor.php?op=updateCurso",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function (data) {
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
                            window.location.replace('cursosInstructores.php');
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
    /* TODO: Guardar inscripción */
}

async function guardarPortadaCurso() {
    try {
        await Promise.all(arrayFiles.map(async (file) => {
            const datosMultimedia = new FormData();
            datosMultimedia.append("file", file);
            datosMultimedia.append("curso_id", $("#idEncrypted").val());
            try {
                await $.ajax({
                    url: "../../controller/instructor.php?op=insertPortadaCurso",
                    method: "POST",
                    data: datosMultimedia,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (data) {
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

function cargarCategorias(callback) {
    $.post("../../controller/instructor.php?op=comboCategoriasCursos", function (data) {
        $('#categoria_curso').html(data);
        // Call the callback function if provided
        if (typeof callback === "function") {
            callback();
        }
    });
}

function cargarCurso(curso_id) {

    /* TODO: Mostramos informacion del documento en inputs */
    $.post("../../controller/instructor.php?op=mostrarCurso", { curso_id: curso_id }, function (data) {
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
                        // Set the value for the corresponding element ID
                        if (key == 'tipo_tramite') {
                            $('#' + key).val(element[key]).trigger('change');
                        }
                        if (key == "categoria_curso") {
                            cargarCategorias(function () {
                                $('#' + key).val(element[key]).trigger('change');
                            });
                        }
                        else if (key == "descripcion" || key == "aprendizaje") {
                            $('#' + key).summernote('code', element[key]);
                        }
                        else if (key == "imagen_portada") {
                            $('#' + key).attr('href', '../' + element[key]);
                        }
                        else {
                            $('#' + key).val(element[key]);
                        }

                        document.addEventListener("DOMContentLoaded", function () {
                            // Fetch the URL from your database
                            var imageUrl = "../docs/documents/cursos_instructor/5781264/202403211651-.PNG";

                            // Get the element
                            var multimediaFisica = document.getElementById("multimediaFisica");

                            // Set the background image
                            multimediaFisica.style.backgroundImage = "url('" + imageUrl + "')";
                        });

                    });
                });
            }
        } catch (error) {
            console.error("Error parsing JSON:", error);
        }
    });

}



/*=============================================
SECCIONES
=============================================*/

function nuevaSeccion() {
    window.location.replace('nuevaSeccion.php?IDCURSO=' + $("#idEncrypted").val() + '');
}

$(document).on("click", ".btn-editar-seccion", function () {
    const ciphertext = $(this).data("ciphertext");
    window.location.replace('editarSeccion.php?IDSECCION=' + ciphertext + '');
});

$(document).on("click", ".btn-materiales", function () {
    const ciphertext = $(this).data("ciphertext");
    window.location.replace('administrarDocsSecciones.php?IDSECCION=' + ciphertext + '');
});

$(document).on("click", ".btn-tasks", function () {
    const ciphertext = $(this).data("ciphertext");
    window.location.replace('../Instructores/administrarTareas.php?IDSECCION=' + ciphertext + '');
});

function guardarSeccion() {

    /* TODO: Array del form inscripción */
    var formData = new FormData($("#datos_seccion_form")[0]);
    var currentURL = window.location.href
    // Check if the URL contains "IDCURSO"
    if (currentURL.includes("IDCURSO")) {
        formData.append('curso_id', $('#idEncrypted').val());
        $.ajax({
            url: "../../controller/instructor.php?op=insertSeccion",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function (data) {
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
                            window.location.replace('administrarSecciones.php?IDCURSO=' + $('#idEncrypted').val());
                        }
                    });
                }
                else {
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
    else {
        formData.append('seccion_id', $('#idEncrypted').val());
        $.ajax({
            url: "../../controller/instructor.php?op=updateSeccion",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function (data) {
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

                            window.location.replace('administrarSecciones.php?IDCURSO=' + $('#idEncryptedCurso').val());
                        }
                    });
                }
                else {
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
    /* TODO: Guardar inscripción */
}

function cargarSeccion(seccion_id) {

    /* TODO: Mostramos informacion del documento en inputs */
    $.post("../../controller/instructor.php?op=getCursoId", { seccion_id: seccion_id }, function (data) {
        $("#curso_id").val(data);
        var targetElement = document.getElementById("cancelar");
        if(targetElement != null){
            targetElement.href = "administrarSecciones.php?IDCURSO=" + data;
        }
    });
    return "ok";
}

$(document).on("click", ".btn-delete-seccion", function () {
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
            $.post("../../controller/instructor.php?op=deleteSeccion", { seccion_id: ciphertext }, function (e) {

                if (e == "ok") {
                    Swal.fire({
                        title: e,
                        text: "La sección se eliminó correctamente.",
                        icon: "success",
                        showCancelButton: true,
                        confirmButtonColor: "#3d85c6",
                        confirmButtonText: "OK"
                    });
                    tabla.ajax.reload();
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

async function guardarMateriales() {
    try {
        await Promise.all(arrayFiles.map(async (file) => {
            const datosMultimedia = new FormData();
            datosMultimedia.append("file", file);
            datosMultimedia.append("seccion_id", idEncryptedSeccion);
            try {

                await $.ajax({
                    url: "../../controller/instructor.php?op=insertMaterialesLeccion",
                    method: "POST",
                    data: datosMultimedia,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (data) {
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
                                    // window.location.replace('administrarSecciones.php?IDCURSO='+$("#curso_id").val());
                                    $("#materiales_seccion").load(" #materiales_seccion" + " > *");
                                    $("#datos_solicitud_form").load(" #datos_solicitud_form" + " > *");
                                }
                            });
                        }
                        else {
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
            } catch (error) {
                // Handle errors if necessary
                console.error("Error al guardar los documentos:", error);
                throw error; // Re-throw the error to break out of Promise.all
            }
        }));

    } catch (error) {
        // Handle any error that might occur during the process
        console.error("Error al guardar los documentos:", error);
    }
}

function eliminarMaterialSeccion(material_id, archivo) {
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
            $.post("../../controller/instructor.php?op=deleteMaterial", { material_id: material_id, archivo: archivo }, function (e) {
                console.log(e);
                if (e == "ok") {
                    Swal.fire({
                        title: e,
                        text: "El documento se eliminó correctamente.",
                        icon: "success",
                        showCancelButton: true,
                        confirmButtonColor: "#3d85c6",
                        confirmButtonText: "OK"
                    });
                    $("#materiales_seccion").load(" #materiales_seccion" + " > *");
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


/*=============================================
LECCIONES
=============================================*/
var video_leccion = "";
async function guardarLeccion() {

    /* TODO: Array del form inscripción */
    var formData = new FormData($("#datos_leccion_form")[0]);
    video_leccion = "";
    await guardarVideoLeccion();
    formData.append('video_leccion', video_leccion);
    formData.append('descripcion', $('#descripcion').val());
    var currentURL = window.location.href
    // Check if the URL contains "IDSECCION"
    if (currentURL.includes("IDSECCION")) {
        formData.append('seccion_id', idEncryptedSeccion);
        if ($('#idEncryptedLeccion').val() == "") {
            $.ajax({
                url: "../../controller/instructor.php?op=insertLeccion",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
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
                                var form = $("#datos_leccion_form")[0];
                                for (var i = 0; i < form.elements.length; i++) {
                                    var element = form.elements[i];

                                    // Check if the element is an input, textarea, or select
                                    if (element.tagName === 'INPUT' || element.tagName === 'TEXTAREA' || element.tagName === 'SELECT') {
                                        // Reset the value of the element
                                        element.value = '';
                                    }
                                }
                                $("#lecciones_area").load(" #lecciones_area" + " > *");
                                $("#datos_leccion_form").load(" #datos_leccion_form" + " > *");
                                close_bloque_leccion();
                            }
                        });
                    }
                    else {
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

        else {
            formData.append("leccion_id", $('#idEncryptedLeccion').val());
            $.ajax({
                url: "../../controller/instructor.php?op=updateLeccion",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
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
                                $("#lecciones_area").load(" #lecciones_area" + " > *");
                                $("#datos_leccion_form").load(" #datos_leccion_form" + " > *");
                                close_bloque_leccion();
                            }
                        });
                    }
                    else {
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
}

async function guardarVideoLeccion() {
    try {
        await Promise.all(arrayFiles.map(async (file) => {
            const datosMultimedia = new FormData();
            datosMultimedia.append("file", file.value);
            datosMultimedia.append('seccion_id', $('#idEncrypted').val());
            datosMultimedia.append('idEncryptedCurso', $('#idEncryptedCurso').val());

            try {
                await $.ajax({
                    url: "../../controller/instructor.php?op=insertVideoLeccion",
                    method: "POST",
                    data: datosMultimedia,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        // console.log(data);
                        video_leccion = data;
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
function openBloqueLeccion() {
    var targetElement = document.getElementById("bloque_leccion");
    // Change its styles
    targetElement.style.display = "block";
    var form = $("#datos_leccion_form")[0];
    for (var i = 0; i < form.elements.length; i++) {
        var element = form.elements[i];
        // Check if the element is an input, textarea, or select
        if (element.tagName === 'INPUT' || element.tagName === 'SELECT') {
            // Reset the value of the element
            element.value = '';
        }
        if (element.tagName === 'TEXTAREA') {
            //No entra, porque está fuera del form
            // Clear the contents of the Summernote instance
            $('#descripcion').summernote('reset');
        }
    }
    $('#descripcion').summernote('reset');
}

function mostrarLeccion(leccion_id) {

    openBloqueLeccion();
    $('#idEncryptedLeccion').val(leccion_id);
    $.post("../../controller/instructor.php?op=mostrarLeccion", { leccion_id: leccion_id }, function (data) {
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
                        if (key == "descripcion") {
                            $('#' + key).summernote('code', element[key]);
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

}

function cargarBotones() {
    var permisos = $("#permisos").val();
    let lettersArray = permisos.split(/-/);
    lettersArray.forEach(function (element) {
        if (element == "M") {
            var enviar_inscripcion_btn = document.getElementById("enviar_inscripcion_btn");
            enviar_inscripcion_btn.style.display = "block";
        }
    });

    return;
}

$(document).on("click", ".btn-delete-leccion", function () {
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
            $.post("../../controller/instructor.php?op=deleteLeccion", { leccion_id: ciphertext }, function (e) {

                if (e == "ok") {
                    Swal.fire({
                        title: e,
                        text: "La lección se eliminó correctamente.",
                        icon: "success",
                        showCancelButton: true,
                        confirmButtonColor: "#3d85c6",
                        confirmButtonText: "OK"
                    });
                    $("#lecciones_area").load(" #lecciones_area" + " > *");
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

/*=============================================
TAREAS
=============================================*/
function validateForm(formulario) {

    var form = document.getElementById(formulario);
    var elements = form.querySelectorAll('input[required], select[required], textarea[required]');
    var isEmpty = false;

    for (var i = 0; i < elements.length; i++) {
        var element = elements[i];

        // Check if element is input, select, or textarea
        if (element.tagName === "INPUT" || element.tagName === "SELECT" || element.tagName === "TEXTAREA") {
            // Check if element is required and is empty
            if (!element.value.trim()) {
                isEmpty = true;
                break;
            }
        }
    }

    return !isEmpty;
}
function nuevaTarea() {
    window.location.replace('tarea.php?IDSECCION=' + $("#idEncrypted").val() + '');
}
var adjuntoTP = "";
async function guardarTarea() {
    if (validateForm('datos_tarea_form')) {
        $("#total_puntos").attr('disabled', false);
        var formData = new FormData($("#datos_tarea_form")[0]);
        if ($('#tipo_tarea').val() == 1) {
            await guardarAdjuntoTarea("/adjuntosTP/");
        }

        formData.append('adjuntoTP', adjuntoTP);
        formData.append('descripcion', $('#descripcion').val());
        formData.append('tiempo_limite', $('#tiempo_limite').val());
        formData.append('seccion_id', idEncryptedSeccion);

        if ($('#idEncrypted').val() == "") {
            $.ajax({
                url: "../../controller/instructor.php?op=insertTarea",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
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
                                window.location.replace('administrarTareas.php?IDSECCION=' + $('#idEncryptedSeccion').val());
                            }
                        });
                    }
                    else {
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
        else {
            formData.append('tarea_id', $('#idEncrypted').val());
            $.ajax({
                url: "../../controller/instructor.php?op=updateTarea",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
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
                                window.location.replace('administrarTareas.php?IDSECCION=' + $('#idEncryptedSeccion').val());
                            }
                        });
                    }
                    else {
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
    else{
        Swal.fire({
            title: "Hay campos vacíos",
            text: 'Debe completar todos los campos dentro del formulario',
            icon: "error",
            showCancelButton: true,
            confirmButtonColor: "#3d85c6",
            confirmButtonText: "OK"
        });
    }
}

async function guardarAdjuntoTarea(carpeta) {
    try {
        await Promise.all(arrayFiles.map(async (file) => {
            const datosMultimedia = new FormData();
            datosMultimedia.append("file", file.value);
            datosMultimedia.append("carpeta", carpeta);
            datosMultimedia.append('seccion_id', $("#idEncryptedSeccion").val());
            try {
                await $.ajax({
                    url: "../../controller/instructor.php?op=insertAdjuntoTP",
                    method: "POST",
                    data: datosMultimedia,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        console.log(data);
                        adjuntoTP = data;
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

$(document).on("click", ".btn-editar-tarea", function () {
    const ciphertext = $(this).data("ciphertext");
    window.location.replace('tarea.php?IDTAREA=' + ciphertext + '');

});

function cargarTarea(tarea_id) {
    $.post("../../controller/instructor.php?op=mostrarTarea", { tarea_id: tarea_id }, function (data) {
        try {
            $('#idEncrypted').val(tarea_id);
            // console.log($('#idEncrypted').val());
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
                        // Set the value for the corresponding element ID
                        if (key == "tipo_tarea") {
                            $('#' + key).val(element[key]).trigger('change');
                        }
                        else if (key == "descripcion") {
                            $('#' + key).summernote('code', element[key]);
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

}

$(document).on("click", ".btn-delete-tarea", function () {
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
            $.post("../../controller/instructor.php?op=deleteTarea", { tarea_id: ciphertext }, function (e) {

                if (e == "ok") {
                    Swal.fire({
                        title: e,
                        text: "La tarea se eliminó correctamente.",
                        icon: "success",
                        showCancelButton: true,
                        confirmButtonColor: "#3d85c6",
                        confirmButtonText: "OK"
                    });
                    tabla.ajax.reload();
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

$(document).on("click", ".btn-ver-entregas", function () {
    const ciphertext = $(this).data("ciphertext");
    window.location.replace('../Instructores/revisarTareas.php?IDTAREA=' + ciphertext + '');
});


$(document).on("click", ".btn-ver-entrega", function () {
    const ciphertext = $(this).data("ciphertext");
    window.location.replace('../Certificaciones/trabajoPractico.php?IDENTREGA=' + ciphertext + '');

});



//------------------ EJERCICIOS ----------------------

function openBloqueEjercicio() {
    var targetElement = document.getElementById("bloque_ejercicio");
    // Change its styles
    targetElement.style.display = "block";
    var form = $("#datos_ejercicio_form")[0];
    for (var i = 0; i < form.elements.length; i++) {
        var element = form.elements[i];
        // Check if the element is an input, textarea, or select
        if (element.tagName === 'INPUT' || element.tagName === 'SELECT') {
            // Reset the value of the element
            element.value = '';
        }
        if (element.tagName === 'TEXTAREA') {
            document.getElementById("texto_ejercicio").value = "";
        }
    }

}

function mostrarEjercicio(ejercicio_id) {

    openBloqueEjercicio();
    $('#idEncryptedEjercicio').val(ejercicio_id);
    $.post("../../controller/instructor.php?op=mostrarEjercicio", { ejercicio_id: ejercicio_id }, function (data) {
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
                        if (key == "descripcion") {
                            $('#' + key).summernote('code', element[key]);
                        }
                        else if (key == "tipo_ejercicio") {
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

}

var imagenEjercicio = "";
function guardarEjercicio() {
    let formData = new FormData(document.getElementById('datos_ejercicio_form'));
    formData.append('tarea_id', $("#idEncrypted").val());
    formData.append('total_puntos', $("#total_puntos").val());
    formData.append('imagen_url', imagenEjercicio);
    let additionalData = {};
    let tipoEjercicio = document.getElementById('tipo_ejercicio').value;

    if (tipoEjercicio === 'seleccion_multiple') {
        let options = document.querySelectorAll('#multiple_choice input[type="text"]');
        options.forEach((option, index) => {
            let isCorrect = document.querySelector(`#multiple_choice input[type="checkbox"][name="multiple${index + 1}"]`).checked;
            additionalData[`multiple${index + 1}`] = option.value;
            additionalData[`multiple${index + 1}_correct`] = isCorrect;
        });
    } else if (tipoEjercicio === 'seleccion_simple') {
        let options = document.querySelectorAll('#simple_choice input[type="text"]');
        options.forEach((option, index) => {
            let isCorrect = document.querySelector(`#simple_choice input[type="radio"][name="optionsRadios"][id="radio${index + 1}"]`).checked;
            additionalData[`radio${index + 1}`] = option.value;
            additionalData[`radio${index + 1}_correct`] = isCorrect;
        });
    } else if (tipoEjercicio === 'verdadero_falso') {
        let isCorrect = document.querySelector(`#true_false input[type="radio"][name="trueFalseOption"]:checked`).id;
        formData.append('respuesta_correcta', isCorrect);
    } else if (tipoEjercicio === 'completar') {
        let answer = document.querySelector('#complete input[name="complete"]').value;
        formData.append('respuesta_correcta', answer);
    } else if (tipoEjercicio === 'respuesta_corta') {
        let answer = document.querySelector('#short_answer input[name="short_answer"]').value;
        formData.append('respuesta_correcta', answer);
    }

    // Collecting form data
    formData.append('additional_data', JSON.stringify(additionalData));
    $.ajax({
        url: "../../controller/instructor.php?op=insertEjercicio",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (data) {
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
                        $("#ejercicios_area").load(" #ejercicios_area" + " > *");
                        var total_puntos = parseFloat($("#total_puntos").val()) + parseFloat($("#puntaje").val());
                        // Set the new value
                        var total_puntos = parseFloat($("#total_puntos").val()) || 0;
                        var puntaje = parseFloat($("#puntaje").val()) || 0;

                        // Add the values
                        var new_total_puntos = total_puntos + puntaje;

                        // Set the new value
                        $("#total_puntos").val(new_total_puntos);
                        close_bloque_ejercicio();
                    }
                });
            }
            else {
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

