var tabla;

var certificaciones_tabla;
//Se utiliza para listar los documentos del usuario
var idEncrypted = "";
var arrayFiles = [];
var arrayId = [];

function init() {

}


$(document).ready(function() {


    $('.verifyButton').on('click', function() {

        var button = $(this);

        // Add loading icon
        button.html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Verificando...');

        var tipoDocumentoId = $(this).closest('.agregarMultimedia').find('.multimediaFisica').data('tipo-documento-id');
        // Simulate verification process (you can replace this with your actual verification logic)
        setTimeout(function() {
            var isVerified = Math.random() < 0.5; // Simulating success or failure
            //  event.stopPropagation();
            cargarIdDoc(tipoDocumentoId, button);
            // Change button text and icon based on verification result

        }, 2000); // Simulating a 2-second verification process


    });


    // Seleccionar todos los elementos .multimediaFisica y configurar Dropzone para cada uno
    $(".multimediaFisica").each(function() {

        let tipoDocumentoId = $(this).data('tipo-documento-id');
        let dropzoneElement = this; // Elemento Dropzone actual
        // Verificar si el elemento ya tiene una instancia de Dropzone
        if (dropzoneElement.dropzone) {
            // Si ya tiene una instancia, saltar a la siguiente iteración
            return;
        }
        let dropzoneInstance = new Dropzone(dropzoneElement, {
            url: "../Tramites/plugins/dropzone/dropzone.js",
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg, image/png, application/pdf",
            maxFilesize: 10,
            maxFiles: 1,
            dictRemoveFile: "Eliminar archivo",
            dictDefaultMessage: "Arrastra archivos aquí para subirlos",

            init: function() {
                let currentDropzone = this; // Guardar la instancia de Dropzone en una variable local

                // Evento al agregar un archivo
                this.on("addedfile", function(file) {
                    // Verificar si ya existe un elemento con el mismo id en arrayFiles
                    let existingIndex = arrayFiles.findIndex(item => item.id == tipoDocumentoId);

                    if (existingIndex !== -1) {

                        // Verificar si hay archivos en el dropzone antes de intentar eliminar
                        if (dropzoneInstance.files.length > 0) {
                            // Eliminar el archivo existente de Dropzone
                            dropzoneInstance.removeFile(dropzoneInstance.files[0]);
                        }

                        // Eliminar el elemento existente de arrayFiles y arrayId
                        arrayFiles.splice(existingIndex, 1);
                        arrayId = arrayId.filter(item => item !== tipoDocumentoId);
                        console.log('Elemento existente eliminado del arrayFiles y arrayId:', tipoDocumentoId);
                    }

                    // Insertar el nuevo elemento en arrayFiles y arrayId
                    arrayFiles.push({ id: tipoDocumentoId, value: file });
                    arrayId.push(tipoDocumentoId);

                    console.log('Archivo añadido:', file, 'ID Documento:', tipoDocumentoId);
                    console.log('Array de archivos:', arrayFiles);
                });

                // Evento al eliminar un archivo
                this.on("removedfile", function(file) {
                    console.log('Archivo eliminado:', file);

                    // Eliminar todos los elementos con el mismo id de arrayFiles y arrayId
                    arrayFiles = arrayFiles.filter(item => item.id !== tipoDocumentoId);
                    arrayId = arrayId.filter(item => item !== tipoDocumentoId);

                    console.log('Elementos eliminados del arrayFiles y arrayId con ID:', tipoDocumentoId);
                    console.log('Array de archivos:', arrayFiles);

                    // // Restaurar el mensaje en .dz-message
                    const elementosMultimedia = document.querySelectorAll(`.multimediaFisica[data-tipo-documento-id="${tipoDocumentoId}"]`);

                    elementosMultimedia.forEach(elemento => {
                        const mensaje = elemento.querySelector('.dz-message');
                        if (mensaje) {
                            mensaje.textContent = "Arrastrar o dar click para subir imágenes.";
                        } else {
                            console.error('No se encontró el elemento .dz-message en:', elemento);
                        }
                    });
                });
            }
        });
    });

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

        $('#aprendizaje').summernote({
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

        $('.descripcion').summernote({
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

        certificaciones_tabla = $('#certificaciones_data').dataTable({
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
                url: '../../controller/certificacion.php?op=listar_x_usu',
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

        tabla = $('#cursos_data').dataTable({
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
                url: '../../controller/certificacion.php?op=listar_cursos_x_usu',
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




    // Llenar combo Estado Civil
    $.post("../../controller/tramite.php?op=comboEstadoCivil", function(data) {
        $('#estado_civil').html(data);
    });
    // Llenar combo países
    $.post("../../controller/tramite.php?op=comboPaises", function(data) {
        $('#pais_nacimiento').html(data);
        $('#pais_titulo').html(data);
        $('#pais_postgrado').html(data);
    });
    // LLenar combo departamentos para la residencia permanente
    $.post("../../controller/tramite.php?op=comboDepartamentos", { pais: "Paraguay" }, function(data) {
        $('#departamento_residencia').html(data);
        $('#departamento_publico').html(data);
        $('#departamento_privado').html(data);
    });
    // Llenar combo países
    $.post("../../controller/documentoAcademico.php?op=comboInstituciones", function(data) {
        $('#institucion_titulo').html(data);
        $('#institucion_postgrado').html(data);
    });

    // Llenar combo titulos
    $.post("../../controller/certificacion.php?op=comboTitulos", function(data) {
        $('#titulo_obtenido').html(data);
        $('#titulo_postgrado').html(data);
    });

    cargarCategorias();

    /* TODO: Llenar Combo trámites */
    $.post("../../controller/certificacion.php?op=comboTramites", { tipo_solicitud: $("#tipo_solicitud").val() }, function(data) {
        $('#tramite_nuevo').html(data);
        $('#tramite').html(data);
    });

    $.post("../../controller/certificacion.php?op=comboTramitesCapacitaciones", function(data) {
        $('#tipo_tramite').html(data);
    });

    /* TODO: Llenar Combo trámites */
    $.post("../../controller/tramite.php?op=comboCursos", function(data) {
        $('#curso_nuevo').html(data);
    });

    /* TODO: Llenar Combo estados de trámites */
    $.post("../../controller/certificacion.php?op=comboEstadosTramites", function(data) {
        $('#estado_tramite').html(data);
    });

    // Use a regular expression to extract the ID from the URL
    var match_code = currentURL.match(/[\?&]code=([^&]*)/);
    if (match_code) {
        // Extracted ID is in match_code[1]
        tramite_code = match_code[1];

        $('#tramite_code').val(tramite_code);

        $.post("../../controller/certificacion.php?op=getTipoSolicitud", { tramite_code: tramite_code }, function(data) {
            $('#tipo_solicitud').val(data);
            $.post("../../controller/certificacion.php?op=comboCursos", { tipo_solicitud: $("#tipo_solicitud").val(), tramite_code: tramite_code }, function(data) {
                $('#seccion_curso').html(data);
            });
            // cancelarReturnPage();
        });
    }

    // Use a regular expression to extract the ID from the URL
    var match = currentURL.match(/[\?&]ID=([^&]*)/);
    if (match) {
        // Extracted ID is in match[1]
        idEncrypted = match[1];
        $.post("../../controller/certificacion.php?op=comboCursos", { tipo_solicitud: $("#tipo_solicitud").val(), tramite_code: tramite_code }, function(data) {
            $('#seccion_curso').html(data);
            cargarInscripcion(idEncrypted);

        });

        $('#idEncrypted').val(idEncrypted);
        console.log(idEncrypted);

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
                    url: '../../controller/certificacion.php?op=listar_secciones_x_curso',
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

function cargarIdDoc(tipoDocumentoId, button) {

    id = tipoDocumentoId;

    if (tipoDocumentoId == 1) { // Suponiendo que tipo_documento_id 1 corresponde a cédula de identidad
        fetch('../../controller/usuario.php?op=mostrar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    'id': $('#usuario_id').val()
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {

                id = tipoDocumentoId;

                if (data && data.foto_ci) {

                    const elementosMultimedia = document.querySelectorAll(`.multimediaFisica[data-tipo-documento-id="${tipoDocumentoId}"]`);

                    elementosMultimedia.forEach(elemento => {

                        const mensaje = elemento.querySelector('.dz-message');

                        if (mensaje) {

                            //  console.log('Imagen actualizada:', data.foto_ci);

                            let dropzoneInstance = Dropzone.forElement(elemento);

                            // Crear un archivo ficticio
                            fetch(`../${data.foto_ci}`)
                                .then(res => res.blob())
                                .then(blob => {
                                    let mockFile = new File([blob], `imagen_documento.jpg`, { type: blob.type, lastModified: new Date().getTime() });

                                    mockFile.upload = {
                                        uuid: generateUUID(),
                                        progress: 100,
                                        total: blob.size,
                                        bytesSent: blob.size
                                    };
                                    mockFile.status = Dropzone.SUCCESS;
                                    mockFile.accepted = true;

                                    dropzoneInstance.emit("addedfile", mockFile);
                                    dropzoneInstance.emit("thumbnail", mockFile, URL.createObjectURL(blob));
                                    dropzoneInstance.emit("complete", mockFile);
                                    // Agregar el archivo ficticio a Dropzone (esto asegura que aparezca en la lista de archivos)
                                    dropzoneInstance.files.push(mockFile);
                                    // Añadir a arrayFiles y arrayId
                                    let existingIndex = arrayFiles.findIndex(item => item.id === tipoDocumentoId);

                                    if (existingIndex !== -1) {
                                        // Si existe, actualizar el valor
                                        arrayFiles[existingIndex].value = mockFile;

                                    } else {
                                        // Si no existe, agregar un nuevo objeto
                                        arrayFiles.push({ id: tipoDocumentoId, value: mockFile });
                                        arrayId.push(tipoDocumentoId);
                                    }

                                    // Poner en blanco el mensaje dentro de .dz-message
                                    mensaje.textContent = "";

                                    button.removeClass('btn-default').addClass('btn-success');
                                    button.html('<span class="glyphicon glyphicon-ok" style="color:#9bcc86"></span>');

                                    console.log('Archivo ficticio añadido:', mockFile, 'ID Documento:', tipoDocumentoId);
                                    console.log('Array de archivos:', arrayFiles);
                                });
                        } else {
                            console.error('No se encontró el elemento .dz-message en:', elemento);
                        }
                    });
                } else {
                    console.log('La respuesta no contiene datos válidos para la imagen.');

                    button.removeClass('btn-default').addClass('btn-danger');
                    button.html('<span class="glyphicon glyphicon-remove" style="color:#df7e7e" title="No encontrado"></span>');
                }
            })
            .catch(error => {
                console.error('Error al cargar los datos:', error);
            });
    }

    if (tipoDocumentoId == 16) { // Suponiendo que tipo_documento_id 16 corresponde a registro profesional

        fetch('../../controller/usuario.php?op=mostrar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    'id': $('#usuario_id').val()
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {

                id = tipoDocumentoId;

                if (data && data.foto_registro_profesional) {

                    const elementosMultimedia = document.querySelectorAll(`.multimediaFisica[data-tipo-documento-id="${tipoDocumentoId}"]`);

                    elementosMultimedia.forEach(elemento => {

                        const mensaje = elemento.querySelector('.dz-message');

                        if (mensaje) {

                            //  console.log('Imagen actualizada:', data.foto_ci);

                            let dropzoneInstance = Dropzone.forElement(elemento);

                            // Crear un archivo ficticio
                            fetch(`../${data.foto_registro_profesional}`)
                                .then(res => res.blob())
                                .then(blob => {
                                    let mockFile = new File([blob], `imagen_documento.jpg`, { type: blob.type, lastModified: new Date().getTime() });

                                    mockFile.upload = {
                                        uuid: generateUUID(),
                                        progress: 100,
                                        total: blob.size,
                                        bytesSent: blob.size
                                    };
                                    mockFile.status = Dropzone.SUCCESS;
                                    mockFile.accepted = true;

                                    dropzoneInstance.emit("addedfile", mockFile);
                                    dropzoneInstance.emit("thumbnail", mockFile, URL.createObjectURL(blob));
                                    dropzoneInstance.emit("complete", mockFile);
                                    // Agregar el archivo ficticio a Dropzone (esto asegura que aparezca en la lista de archivos)
                                    dropzoneInstance.files.push(mockFile);
                                    // Añadir a arrayFiles y arrayId
                                    let existingIndex = arrayFiles.findIndex(item => item.id === tipoDocumentoId);

                                    if (existingIndex !== -1) {
                                        // Si existe, actualizar el valor
                                        arrayFiles[existingIndex].value = mockFile;

                                    } else {
                                        // Si no existe, agregar un nuevo objeto
                                        arrayFiles.push({ id: tipoDocumentoId, value: mockFile });
                                        arrayId.push(tipoDocumentoId);
                                    }

                                    // Poner en blanco el mensaje dentro de .dz-message
                                    mensaje.textContent = "";

                                    button.removeClass('btn-default').addClass('btn-success');
                                    button.html('<span class="glyphicon glyphicon-ok" style="color:#9bcc86"></span>');

                                    console.log('Archivo ficticio añadido:', mockFile, 'ID Documento:', tipoDocumentoId);
                                    console.log('Array de archivos:', arrayFiles);
                                });
                        } else {
                            console.error('No se encontró el elemento .dz-message en:', elemento);
                        }
                    });
                } else {
                    console.log('La respuesta no contiene datos válidos para la imagen.');

                    button.removeClass('btn-default').addClass('btn-danger');
                    button.html('<span class="glyphicon glyphicon-remove" style="color:#df7e7e" title="No encontrado"></span>');
                }
            })
            .catch(error => {
                console.error('Error al cargar los datos:', error);
            });
    }
    button.removeClass('btn-default').addClass('btn-danger');
    button.html('<span class="glyphicon glyphicon-remove" style="color:#df7e7e" title="No encontrado"></span>');
}
// Función para generar UUID
function generateUUID() {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = Math.random() * 16 | 0,
            v = c == 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
    });
}
// Función para mostrar el mensaje si no hay imágenes
function mostrarMensajeSiVacio() {
    $('.dz-clickable').each(function() {
        var $container = $(this);
        if ($container.children().length === 0) {
            $container.find('.dz-message').show(); // Mostrar el mensaje
        } else {
            $container.find('.dz-message').hide(); // Ocultar el mensaje si hay imágenes u otros contenidos
        }
    });
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

        window.location.replace('inscripcionCurso.php?code=' + $('#tramite_nuevo').val() + '');
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

async function guardarDocsTramites(estado_tramite) {
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

    await guardarDocsTramites(estado_tramite);

    /* TODO: Array del form inscripción */
    var formData = new FormData($("#inscripcion_form")[0]);
    formData.append('tramite_code', $('#tramite_code').val());
    formData.append('tiposDocumentos', JSON.stringify(arrayId));
    formData.append('estado_tramite', estado_tramite);
    formData.append('nombre', $('#nombre').val());
    formData.append('especialidad', $('#especialidad').val());
    formData.append('correo', $('#correo').val());
    formData.append('telefono', $('#telefono').val());
    formData.append('documento_identidad', $('#documento_identidad').val());
    formData.append('fecha_nacimiento', $('#fecha_nacimiento').val());
    formData.append('observacion', $('#observacion').val());


    /* TODO: Guardar inscripción */
    if ($("#idEncrypted").val() == "") {
        $.ajax({
            url: "../../controller/certificacion.php?op=insert_tramite_certificacion",
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
                                window.location.replace('listarCursosDisponibles.php');
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
        $.ajax({
            url: "../../controller/certificacion.php?op=update",
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
                                window.location.replace('listarCursosDisponibles.php');
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

async function guardarInscripcion(estado_tramite) {

    await guardarDocsTramites(estado_tramite);

    /* TODO: Array del form inscripción */
    var formData = new FormData($("#inscripcion_form")[0]);
    formData.append('tramite_code', $('#tramite_code').val());
    formData.append('tiposDocumentos', JSON.stringify(arrayId));
    formData.append('estado_tramite', estado_tramite);
    formData.append('nombre', $('#nombre').val());
    formData.append('especialidad', $('#especialidad').val());
    formData.append('correo', $('#correo').val());
    formData.append('telefono', $('#telefono').val());
    formData.append('documento_identidad', $('#documento_identidad').val());
    formData.append('fecha_nacimiento', $('#fecha_nacimiento').val());
    formData.append('observacion', $('#observacion').val());


    /* TODO: Guardar inscripción */
    if ($("#idEncrypted").val() == "") {
        $.ajax({
            url: "../../controller/certificacion.php?op=insert",
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
                                window.location.replace('listarCursosDisponibles.php');
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
        $.ajax({
            url: "../../controller/certificacion.php?op=update",
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
                                window.location.replace('listarCursosDisponibles.php');
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
    //guardarDocsTramites(estado_tramite);


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
            $.post("../../controller/certificacion.php?op=delete", { ciphertext: ciphertext, estado_tramite_id: estado_tramite }, function(e) {

                if (e == "ok") {
                    Swal.fire({
                        title: 'Eliminado',
                        text: "El documento se eliminó correctamente.",
                        icon: "success",
                        showCancelButton: true,
                        confirmButtonColor: "#3d85c6",
                        confirmButtonText: "OK"
                    });
                    certificaciones_tabla.ajax.reload();
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



$('.verifyButton1').click(function() {

    var button = $(this);

    // Add loading icon
    button.html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Verificando...');

    // Simulate verification process (you can replace this with your actual verification logic)
    setTimeout(function() {
        var isVerified = Math.random() < 0.5; // Simulating success or failure

        // Change button text and icon based on verification result
        if (isVerified) {
            button.removeClass('btn-default').addClass('btn-success');
            button.html('<span class="glyphicon glyphicon-ok" style="color:#9bcc86"></span>');
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