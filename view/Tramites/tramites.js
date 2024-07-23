var tabla;
//Se utiliza para listar los documentos del usuario
var idEncrypted = "";
var arrayFiles = [];
var arrayId = [];

function init() {

    actualizar_img();

}

$(document).ready(function() {

    var currentURL = window.location.href;
    var match_code = currentURL.match(/[\?&]permiso=([^&]*)/);

    if (match_code && match_code[1] && match_code[1] == 'R') {
        var guardar_datos_btn = document.getElementById("guardar_borrador_btn");
        var enviar_solicitud_btn = document.getElementById("enviar_solicitud_btn");

        guardar_datos_btn.style.display = "none";
        enviar_solicitud_btn.style.display = "none";
    }


    // Inicializar Select2 para los selects de departamentos y ciudades
    $('#departamento_id').select2();
    $('#ciudad_id').select2();

    // Obtener referencias a los elementos
    const departamentoSelect = document.getElementById('departamento_id');
    const departamentoInput = document.getElementById('departamento_nombre');
    const ciudadSelect = document.getElementById('ciudad_id');
    const ciudadInput = document.getElementById('ciudad_nombre');
    const formaCobroSelect = document.getElementById('forma_cobro');
    const formaCobroNombreInput = document.getElementById('forma_cobro_nombre');
    const bancoSelect = document.getElementById('banco');
    const bancoNombreNombreInput = document.getElementById('banco_nombre');
    const filialSelect = document.getElementById('filial');
    const filialNombreInput = document.getElementById('filial_nombre');


    // Evento de cambio en el select de departamento para cargar ciudades
    $('#departamento_id').change(function() {
        const selectedOption = departamentoSelect.options[departamentoSelect.selectedIndex];
        departamentoInput.value = selectedOption.text;
        var departamento_id = $(this).val();
        cargarCiudades(departamento_id);
    });

    // // Evento de cambio en el select de forma cobro
    // $('#forma_cobro').change(function() {
    //     const selectedOption = formaCobroSelect.options[formaCobroSelect.selectedIndex];
    //     formaCobroNombreInput.value = selectedOption.text;
    // });

    // // Evento de cambio en el select de banco
    // $('#banco').change(function() {
    //     const selectedOption = bancoSelect.options[bancoSelect.selectedIndex];
    //     bancoNombreNombreInput.value = selectedOption.text;
    // });
    // // Evento de cambio en el select de filial
    // $('#filial').change(function() {
    //     const selectedOption = filialSelect.options[filialSelect.selectedIndex];
    //     filialNombreInput.value = selectedOption.text;
    // });
    // Evento de cambio en el select de ciudades
    $('#ciudad_id').change(function() {
        const selectedOption = ciudadSelect.options[ciudadSelect.selectedIndex];
        ciudadInput.value = selectedOption.text;
    });

    // Cargar departamentos al cargar la página
    cargarDepartamentos();

    // Función para cargar departamentos desde el archivo JSON
    function cargarDepartamentos() {

        $.getJSON('../../json/departamentos.json', function(data) {
            // Guardar los datos de departamentos globalmente para su uso posterior
            departamentosData = data;

            // Obtener el select de departamentos
            var departamentoSelect = $('#departamento_id');
            departamentoSelect.empty(); // Limpiar opciones actuales

            // Agregar opción por defecto
            departamentoSelect.append(new Option('Seleccione Departamento', ''));

            // Iterar sobre los datos y agregar opciones al select
            data.forEach(function(departamento) {
                departamentoSelect.append(new Option(departamento.departamento_nombre, parseInt(departamento.departamento_id)));
            });

            // Llamar a cargarDatosPersonales después de cargar los departamentos
            cargarDatosPersonales();
        });

    }
    // Función para cargar datos personales desde el servidor
    function cargarDatosPersonales() {

        tramite_id = $('#tramite_id').val();

        if (tramite_id != "" && tramite_id.trim() != "undefined") {
            $.ajax({
                url: '../../controller/tramite.php?op=obtener_datos_tramite',
                type: 'POST',
                data: { tramite_id: tramite_id },
                success: function(response) {
                    try {
                        if (!response) {
                            throw new Error('Respuesta vacía del servidor');
                        }

                        var jsonData = JSON.parse(response);

                        var datos = JSON.parse(jsonData.datos);

                        if (datos && datos.departamento_id && datos.ciudad_id) {
                            var departamento_id = datos.departamento_id;
                            $('#departamento_id').val(departamento_id).trigger('change');
                            cargarCiudades(departamento_id, datos.ciudad_id);
                        } else {
                            console.error('La respuesta JSON no contiene los campos necesarios');
                        }
                    } catch (error) {
                        console.error('Error al parsear la respuesta JSON:', error);
                        console.log('Respuesta del servidor (no parseada):', response); // Imprime la respuesta en caso de error
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error al obtener los datos del trámite:', error);
                }
            });



        } else {
            $.post("../../controller/usuario.php?op=mostrarDatosPersonales", function(response) {
                var jsArray = JSON.parse(response);

                if (jsArray.length > 0) {
                    var element = jsArray[0]; // Tomar el primer elemento (suponiendo uno solo)

                    // Cargar los valores en los campos del formulario
                    $('#nombre').val(element.nombre);
                    $('#apellido').val(element.apellido);
                    $('#documento_identidad').val(element.documento_identidad);
                    $('#telefono').val(element.telefono);
                    $('#direccion_domicilio').val(element.direccion_domicilio);

                    // Establecer el departamento seleccionado y cargar ciudades
                    var departamento_id = element.departamento_id;
                    $('#departamento_id').val(departamento_id).trigger('change');

                    // Cargar ciudades basadas en el departamento seleccionado
                    cargarCiudades(departamento_id, element.ciudad_id);
                }
            });


        }


    }

    // Función para cargar ciudades basado en el departamento seleccionado
    function cargarCiudades(departamento_id, ciudad_id) {

        var ciudadSelect = $('#ciudad_id');
        ciudadSelect.empty(); // Limpiar opciones actuales
        ciudadSelect.append(new Option('Seleccione Ciudad', ''));

        $('#ciudad_nombre').val("");

        // Encontrar el departamento seleccionado en los datos de departamentos
        var selectedDepartamento = departamentosData.find(d => parseInt(d.departamento_id) == parseInt(departamento_id));

        if (selectedDepartamento) {
            // Iterar sobre las ciudades del departamento seleccionado y agregarlas al select
            selectedDepartamento.ciudades.forEach(function(ciudad) {
                ciudadSelect.append(new Option(ciudad.ciudad_nombre, parseInt(ciudad.ciudad_id)));
            });

            // Establecer la ciudad seleccionada, si existe

            if (ciudad_id) {
                ciudad_id = parseInt(ciudad_id); // Convertir a entero
                ciudadSelect.val(ciudad_id).trigger('change');

                // Actualizar el valor del input ciudad_nombre
                const selectedOption = ciudadSelect.find('option:selected');
                if (selectedOption.length > 0) {
                    ciudadInput.value = selectedOption.text();
                }
            }
        }

    }

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

    // // Check if a match is found
    var currentURL = window.location.href;
    // Use a regular expression to extract the ID from the URL
    var match_code = currentURL.match(/[\?&]code=([^&]*)/);
    /* TODO: Llenar Combo trámites */
    $.post("../../controller/tramite.php?op=comboAyuda", function(data) {
        $('#ayuda_nueva').html(data);
    });

    if (match_code) {
        $('#tramite_code').val(match_code[1]);

        /* TODO: Llenar Combo estados de trámites */
        $.post("../../controller/tramite.php?op=comboEstadosTramites", { tramite_code: match_code[1] }, function(data) {
            $('#estado_tramite').html(data);
        });

        /* TODO: Títulos del trámite */
        $.post("../../controller/tramite.php?op=cargarTitulo", { titulo: match_code[1] }, function(data) {
            $('.tramite_nombre').html(data);
        });
        // Llenar combo Estado Civil
        $.post("../../controller/tramite.php?op=comboEstadoCivil", function(data) {
            $('#estado_civil').html(data);
        });
        // Llenar combo países
        $.post("../../controller/tramite.php?op=comboPaises", function(data) {
            $('#pais').html(data);
        });
        // LLenar combo departamentos para la residencia permanente
        $.post("../../controller/tramite.php?op=comboDepartamentos", { pais: "Paraguay" }, function(data) {
            $('#departamento_residencia').html(data);
        });
        // Llenar combo países
        $.post("../../controller/documentoAcademico.php?op=comboInstituciones", function(data) {
            $('#institucion_acad').html(data);
            $('#institucion_postgrado').html(data);
        });

        /* TODO: Llenar ciudades */
        $.post("../../controller/tramite.php?op=comboCiudades", function(data) {
            $('#ciudad_solicitante').html(data);
        });

        /* TODO: Llenar filiales */
        $.post("../../controller/tramite.php?op=comboFiliales", function(data) {
            $('#filial').html(data);
        });

        /* TODO: Llenar bancos */
        $.post("../../controller/tramite.php?op=comboBancos", function(data) {
            $('#banco').html(data);
        });

        /* TODO: Llenar tipos de cuentas bancarias */
        $.post("../../controller/tramite.php?op=comboTiposCuentas", function(data) {
            $('#tipo_cuenta').html(data);
        });
        // Now execute the second AJAX request
        var match_id = currentURL.match(/[\?&]ID=([^&]*)/);
        if (match_id) {
            idEncrypted = match_id[1];
            $('#idEncrypted').val(match_id[1]);
            var currentURL = window.location.href;
            if (currentURL.includes("solicitudSolidaridad")) {

                // Now execute the second AJAX request
                var currentURL = window.location.href; // Mover la declaración de currentURL aquí para asegurar que esté definida

                var match_id = currentURL.match(/[\?&]ID=([^&]*)/);
                if (match_id) {
                    var idEncrypted = match_id[1];
                    $('#idEncrypted').val(idEncrypted);

                    if (currentURL.includes("solicitudSolidaridad")) {
                        $.post("../../controller/tramite.php?op=mostrarSolicSolidaridad", { idSolicitud: idEncrypted }, function(data) {
                            try {
                                // Asegurarse de que data es un string JSON válido
                                if (typeof data === "string") {
                                    var jsonData = JSON.parse(data);

                                    // Verificar si jsonData tiene el campo 'datos'
                                    if (jsonData && jsonData.datos) {
                                        var datos = JSON.parse(jsonData.datos); // Parsear la cadena JSON anidada

                                        Object.keys(datos).forEach(function(key) {
                                            var value = datos[key];

                                            switch (key) {
                                                case "banco":
                                                    $.post("../../controller/tramite.php?op=comboBancos", function(data) {
                                                        $('#' + key).val(value).trigger('change');
                                                    });
                                                    break;
                                                case "tipo_cuenta":
                                                    $('#' + key).val(value).trigger('change');
                                                    break;
                                                case "forma_cobro":
                                                    $('#forma_cobro').val(value).trigger('change');
                                                    mostrarFormsPago();
                                                    break;
                                                case "filial":
                                                    $.post("../../controller/tramite.php?op=comboFiliales", function(data) {
                                                        $('#' + key).val(value).trigger('change');
                                                    });
                                                    break;
                                                case "estado_tramite_id":
                                                    if (value == 5 || value == 1 || value == 4) {
                                                        $("#guardar_borrador_btn").show();
                                                        $("#enviar_solicitud_btn").show();
                                                    } else {
                                                        $("#guardar_borrador_btn").hide();
                                                        $("#enviar_solicitud_btn").hide();
                                                    }
                                                    break;
                                                default:
                                                    // Asegúrate de que el elemento HTML con el ID exista
                                                    if ($('#' + key).length > 0) {
                                                        $('#' + key).val(value);
                                                    } else {
                                                        console.warn('Elemento HTML con ID ' + key + ' no encontrado.');
                                                    }
                                                    break;
                                            }
                                        });
                                    } else {
                                        console.log('No se encontraron datos válidos en la respuesta.');
                                    }
                                } else {
                                    console.error('La respuesta no es una cadena JSON válida.');
                                }
                            } catch (error) {
                                console.error('Error al parsear los datos JSON:', error);
                            }
                        }).fail(function(jqXHR, textStatus, errorThrown) {
                            console.error('Error en la solicitud AJAX para mostrarSolicSolidaridad:', textStatus, errorThrown);
                        });



                    } else {
                        console.log('La URL actual no contiene "solicitudSolidaridad".');
                    }
                } else {
                    console.log('No se encontró ningún ID en la URL.');
                }



            } else if (currentURL.includes("solicitudAyuda")) {
                $.post("../../controller/tramite.php?op=observacionAyuda", { idEncrypted: idEncrypted }, function(data) {
                    $('#observacion').summernote('code', data);
                });
            } else {
                $.post("../../controller/tramite.php?op=observacionTramite", { idEncrypted: idEncrypted }, function(data) {
                    $('#observacion').summernote('code', data);
                    cargarTramite(idEncrypted);
                });
            }
        }




    } else {

        tabla_tramites = $('#tramites_data').dataTable({
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
                url: '../../controller/tramite.php?op=listar_x_usu',
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

        tabla = $('#ayuda_data').dataTable({
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
                url: '../../controller/tramite.php?op=listar_ayuda_x_usu',
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
        $.post("../../controller/tramite.php?op=comboTramites", function(data) {
            $('#tramite_nuevo').html(data);
            $('#tramite').html(data);
        });


    }


    /*=============================================
    AGREGAR MULTIMEDIA CON DROPZONE
    =============================================*/

    $(".multimediaFisica").dropzone({
        url: "plugins/dropzone/dropzone.js",
        addRemoveLinks: true,
        acceptedFiles: "image/jpeg, image/png, application/pdf",
        maxFilesize: 5,
        maxFiles: 1,
        init: function() {
            this.on("addedfile", function(file) {
                arrayFiles.push({ id: id, value: file });
                arrayId.push(id);
            })
            this.on("removedfile", function(file) {
                var index = arrayFiles.indexOf(file);
                arrayFiles.splice(index, 1);
                arrayId.splice(index);

            })

        }

    })
    if (idEncrypted == "") {
        var guardar_datos_btn = document.getElementById("guardar_borrador_btn");
        var enviar_solicitud_btn = document.getElementById("enviar_solicitud_btn");
        guardar_datos_btn.style.display = "block";
        enviar_solicitud_btn.style.display = "block";
    }

});
var id = 0;

function cargarIdDoc(idDiv) {
    id = idDiv;
}

function mostrarFormsPago() {
    var selectElement = document.getElementById("forma_cobro");
    // Get the selected option's value
    var selectedValue = selectElement.value;
    var bloque_transferencia = document.getElementById("bloque_transferencia");
    var bloque_filial = document.getElementById("bloque_filial");
    if (selectedValue == 1) {
        bloque_transferencia.style.display = "block";
        bloque_filial.style.display = "none";
    } else if (selectedValue == 2) {
        bloque_transferencia.style.display = "none";
        bloque_filial.style.display = "block";
    } else {
        bloque_transferencia.style.display = "none";
        bloque_filial.style.display = "none";
    }

}

function abrirNuevoTramite() {
    var tramite = $('#tramite_nuevo').val();
    if ($("#tramite_nuevo").val() != undefined && $("#tramite_nuevo").val() > 0) {
        $.post("../../controller/tramite.php?op=code", { tramite_id: tramite }, function(data) {
            // data = JSON.parse(data);
            url = data;
            window.location.replace(url + '?code=' + tramite);
        });
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
    // var tramite = $('option:selected', this).attr('url');L
    // var codeTramite = 0;

}

function abrirNuevaAyuda() {
    var tramite = $('#ayuda_nueva').val();
    if ($("#ayuda_nueva").val() != undefined && $("#ayuda_nueva").val() > 0) {
        $.post("../../controller/tramite.php?op=code", { tramite_id: tramite }, function(data) {
            url = data;
            window.location.replace(url + '?code=' + tramite);
        });
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

function cargarDptos() {
    var pais = $('#pais').val();
    $.post("../../controller/tramite.php?op=comboDepartamentos", { pais: pais }, function(data) {
        $('#departamento').html(data);
    });
}

function cargarBarrios() {
    var ciudad = $('#ciudad_solicitante').val();
    $.post("../../controller/tramite.php?op=comboBarrios", { ciudad: ciudad }, function(data) {
        $('#barrio_solicitante').html(data);
    });
}

function cargarCiudades(dpto_id) {
    var departamento = 0;
    if (dpto_id == "departamento") {
        departamento = $('#departamento').val();
    } else {
        departamento = $('#departamento_residencia').val();
    }
    $.post("../../controller/tramite.php?op=comboCiudades", { departamento: departamento }, function(data) {
        if (dpto_id == "departamento") {
            $('#ciudad').html(data);
        } else {
            $('#ciudad_residencia').html(data);
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
    // guardarSolicitud(estado_tramite);

}

async function guardarSolicitud(estado_tramite) {
    await guardarDocsTramites(estado_tramite);
    /* TODO: Array del form Documento Personal */
    if ($("#tipo_solicitud").val() == "solidaridad") {
        if (!validateForm("datos_solicitante_form") || !validateForm("datos_desembolso_form")) {
            Swal.fire({
                title: "Error",
                text: "Hay campos vacíos que debe completar.",
                icon: "error",
                showCancelButton: true,
                confirmButtonColor: "#3d85c6",
                confirmButtonText: "OK"
            });
            return; // Stop further execution if form validation fails
        }
        var formData = new FormData($("#datos_solicitante_form")[0]);
        var formDataDesembolso = new FormData($("#datos_desembolso_form")[0]);

        function appendFormDataFields(sourceFormData, destinationFormData) {
            for (var pair of sourceFormData.entries()) {
                destinationFormData.append(pair[0], pair[1]);
            }
        }

        // Append fields from formData1 to formData2
        appendFormDataFields(formDataDesembolso, formData);
        formData.append('tipo_solicitud', $('#tipo_solicitud').val());
        formData.append('observacion', null);

    } else if ($("#tipo_solicitud").val() == "tramite") {
        var formData = new FormData($("#inscripcion_registro_form")[0]);
    }

    formData.append('tramite_code', $('#tramite_code').val());
    formData.append('tiposDocumentos', JSON.stringify(arrayId));
    formData.append('estado_tramite', estado_tramite);

    /* TODO: Guardar Trámite */
    if ($('#idEncrypted').val() == "") {
        $.ajax({
            url: "../../controller/tramite.php?op=insert",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {

                if (data = "Documento agregado") {
                    Swal.fire({
                        title: "¡Listo!",
                        text: "Registrado Correctamente",
                        icon: "success",
                        showCancelButton: true,
                        confirmButtonColor: "#3d85c6",
                        confirmButtonText: "OK"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.replace('listarTramites.php');
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
        formData.append('idEncrypted', $('#idEncrypted').val());
        $.ajax({
            url: "../../controller/tramite.php?op=update",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {

                Swal.fire({
                    title: "¡Listo!",
                    text: "Modificado Correctamente",
                    icon: "success",
                    showCancelButton: true,
                    confirmButtonColor: "#3d85c6",
                    confirmButtonText: "OK"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.replace('listarTramites.php');
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
                });
            }
        });
    }

    // }
}

function guardarSolicitudAyuda(estado_tramite) {

    /* TODO: Array del form Documento Personal */
    var formData = new FormData($("#datos_solicitud_form")[0]);
    if ($('#observacion').val() == "") {
        Swal.fire({
            title: "Error",
            text: "Debe completar la descripción de la situación.",
            icon: "error",
            showCancelButton: true,
            confirmButtonColor: "#3d85c6",
            confirmButtonText: "OK"
        });
        return; // Stop further execution if form validation fails
    }
    formData.append('tramite_code', $('#tramite_code').val());
    formData.append('estado_tramite', estado_tramite);
    /* TODO: Guardar Trámite */
    if ($('#idEncrypted').val() == "") {
        $.ajax({
            url: "../../controller/tramite.php?op=insertSolicitudAyuda",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {

                if (data = "Documento agregado") {
                    Swal.fire({
                        title: "¡Listo!",
                        text: "Registrado Correctamente",
                        icon: "success",
                        showCancelButton: true,
                        confirmButtonColor: "#3d85c6",
                        confirmButtonText: "OK"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.replace('listarSolicitudesAyuda.php');
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
        formData.append('idEncrypted', $('#idEncrypted').val());
        $.ajax({
            url: "../../controller/tramite.php?op=updateSolicitudAyuda",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {

                Swal.fire({
                    title: "¡Listo!",
                    text: "Modificado Correctamente",
                    icon: "success",
                    showCancelButton: true,
                    confirmButtonColor: "#3d85c6",
                    confirmButtonText: "OK"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.replace('listarSolicitudesAyuda.php');
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
                });
            }
        });
    }

    // }
}


/*=============================================
SUBIENDO LOS ARCHIVOS
=============================================*/
function actualizar_img() {
    $(".nuevaImagen").change(function() {
        var imagen = this.files[0];
        /*=============================================
        VALIDAMOS EL FORMATO DEL ARCHIVO SEA PDF, JPG O PNG
        =============================================*/

        if (imagen["type"] != "image/jpeg" && imagen["type"] != "image/png" && imagen["type"] != "application/pdf") {
            $(".nuevaImagen").val("");
            Swal.fire({
                title: "Error al subir la imagen",
                text: "¡La imagen debe estar en formato PDF, JPG o PNG!",
                confirmButtonText: "¡Cerrar!"
            });

        } else if (imagen["size"] > 2000000) {
            $(".nuevaImagen").val("");
            Swal.fire({
                title: "Error al subir la imagen",
                text: "¡La imagen no debe pesar más de 2MB!",
                confirmButtonText: "¡Cerrar!"
            });
        } else {
            var datosImagen = new FileReader;
            datosImagen.readAsDataURL(imagen);
            $(datosImagen).on("load", function(event) {
                var rutaImagen = event.target.result;
                $(".previsualizar").attr("src", rutaImagen);
            });
        }
    });
}

/* TODO: Link para poder ver el tramite guardado */
$(document).on("click", ".btn-inline", function() {
    const ciphertext = $(this).data("ciphertext");
    window.location.replace('editarInscripcionRegistro.php?ID=' + ciphertext + '');
});

$(document).on("click", ".btn-abrir-solicitud", function() {
    const ciphertext = $(this).data("ciphertext");
    window.location.replace('editarInscripcionRegistro.php?ID=' + ciphertext + '');
});

//Cuando el trámite es una solicitud de solidaridad
$(document).on("click", ".btn-ver-solSolidaridad", function() {

    const ciphertext = $(this).data("ciphertext");
    var buttonElement = document.getElementById(ciphertext);

    // Get the value of the data-code attribute using getAttribute()
    var codeValue = buttonElement.getAttribute('code');
    window.location.replace('solicitudSolidaridad.php?ID=' + ciphertext + '&code=' + codeValue + '&permiso=R');
});

//Cuando el trámite es una solicitud de solidaridad
$(document).on("click", ".btn-editar-solSolidaridad", function() {
    const ciphertext = $(this).data("ciphertext");
    var buttonElement = document.getElementById(ciphertext);

    // Get the value of the data-code attribute using getAttribute()
    var codeValue = buttonElement.getAttribute('code');
    window.location.replace('solicitudSolidaridad.php?ID=' + ciphertext + '&code=' + codeValue);
});

$(document).on("click", ".btn-abrir-solAyuda", function() {
    const ciphertext = $(this).data("ciphertext");
    var buttonElement = document.getElementById(ciphertext);

    // Get the value of the data-code attribute using getAttribute()
    var codeValue = buttonElement.getAttribute('code');
    window.location.replace('solicitudAyuda.php?ID=' + ciphertext + '&code=' + codeValue);
});

$(document).on("click", ".btn-ver-observaciones", function() {
    const ciphertext = $(this).data("ciphertext");
    window.location.replace('verObservaciones.php?ID=' + ciphertext + '');

});

function cargarTramite(tramite_gestionado_id) {
    /* TODO: Mostramos informacion del documento en inputs */
    $.post("../../controller/tramite.php?op=mostrar", { tramite_gestionado_id: tramite_gestionado_id }, function(data) {
        try {
            // Parse the JSON response
            var jsArray = JSON.parse(data);

            // Iterate through the array using forEach
            jsArray.forEach(function(element) {
                // Access each element in the array here
                $('.tramite_nombre').html(element.nombre_tramite);
                $('#tramite_code').val(element.tramite_id);

            });
        } catch (error) {
            console.error("Error parsing JSON:", error);
        }
    });
}

function enviarSolicitud(estado_tramite) {
    // Se validan que todos los campos tengan algún valor

    // ---------------
    // Se guardan los datos o se editan si fue modificado
    guardarDocsTramites(estado_tramite);


}

function cargarReserva() {

}

/*=============================================
ELIMINAR LA SOLICITUD, DOCUMENTOS Y FORMULARIO
=============================================*/
$(document).on("click", ".btn-delete-row", function() {
    var ciphertext = $(this).data("ciphertext");
    var currentURL = window.location.href;
    if (currentURL.includes("listarSolicitudesAyuda")) {
        var option = "SolicitudAyuda";
    } else {
        var option = "";
    }

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
            $.post("../../controller/tramite.php?op=delete" + option, { ciphertext: ciphertext }, function(e) {

                if (e == "ok") {
                    Swal.fire({
                        title: e,
                        text: "El documento se eliminó correctamente.",
                        icon: "success",
                        showCancelButton: true,
                        confirmButtonColor: "#3d85c6",
                        confirmButtonText: "OK"
                    });
                    tabla_tramites.ajax.reload(); // Reload the table

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

function validateForm(formulario) {
    var isEmpty = false;
    var nombreCampo = '';
    if (formulario == "datos_desembolso_form") {
        console.log("bloque datos_desembolso_form");
        var div;
        if ($('#forma_cobro').val() == '1') {
            console.log("bloque transferencia");
            div = "bloque_transferencia";
        } else if ($('#forma_cobro').val() == '2') {
            console.log("bloque filial");
            div = "bloque_filial";
        }

        var divElement = document.getElementById(div);
        if (divElement) {
            var elements = divElement.querySelectorAll('input[required], select[required], textarea[required]');

            elements.forEach(function(element) {
                if (!element.value.trim()) {
                    nombreCampo = element.getAttribute('placeholder') || element.getAttribute('name') || element.id;
                    console.log("Campo vacío: " + nombreCampo);
                    isEmpty = true;
                }
            });
        }
    } else {
        var form = document.getElementById(formulario);
        if (form) {
            var elements = form.elements;

            for (var i = 0; i < elements.length; i++) {
                var element = elements[i];
                // Check if element is input, select, or textarea and is required
                if ((element.tagName === "INPUT" || element.tagName === "SELECT" || element.tagName === "TEXTAREA") && element.required) {
                    // Check if element is empty
                    if (!element.value.trim()) {
                        nombreCampo = element.getAttribute('placeholder') || element.getAttribute('name') || element.id;
                        console.log("Campo vacío: " + nombreCampo);
                        isEmpty = true;
                        break;
                    }
                }
            }
        }
    }

    return !isEmpty;
}

function cargarBotones() {
    var permisos = $("#permisos").val();
    let lettersArray = permisos.split(/-/);
    lettersArray.forEach(function(element) {
        if (element == "M") {
            var guardar_datos_btn = document.getElementById("guardar_borrador_btn");
            var enviar_solicitud_btn = document.getElementById("enviar_solicitud_btn");
            guardar_datos_btn.style.display = "block";
            enviar_solicitud_btn.style.display = "block";
        }
    });

    return;
}