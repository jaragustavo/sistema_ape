var tabla;
//Se utiliza para listar los documentos del usuario
var usu_id = $('#user_idx').val();
var idEncrypted = "";

function init() {
    $("#doc_academico_form").on("submit",function(e){
        guardaryeditar(e);
    });
    actualizar_img();
}

$(document).ready(function() {
    $('#dato_adic').summernote({
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
    $('#viewuser').hide();

    tabla = $('#datos_academicos_data').dataTable({
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
            url: '../../controller/documentoAcademico.php?op=listar_x_usu',
            type: "post",
            dataType: "json",
            data: { usu_id: usu_id },
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

    /* TODO: Llenar Combo tipos de Documento */
    $.post("../../controller/tipoDocumento.php?op=comboAcademicos",function(data, status){
        $('#tipo_documento').html(data);
    });

    $.post("../../controller/documentoAcademico.php?op=comboInstituciones",function(data, status){
        $('#institucion_educativa').html(data);
    });
    
    var currentURL = window.location.href;
    // Use a regular expression to extract the ID from the URL
    var match = currentURL.match(/[\?&]ID=([^&]*)/);
    // // Check if a match is found
    if (match) {
        // Extracted ID is in match[1]
        idEncrypted = match[1];
        cargarDocumentoAcademico(idEncrypted);
    }
});

/* TODO: Link para poder ver el detalle de documento academico */
$(document).on("click", ".btn-inline", function() {
    const ciphertext = $(this).data("ciphertext");
    window.location.replace('editarDocsAcademicos.php?ID=' + ciphertext + '');    
});

/* TODO: Link para poder ver el documento academico */
$(document).on("click", ".btn-open-pdf", function() {
    const ciphertext = $(this).data("ciphertext");
    window.open(ciphertext);  
});

/* TODO:Filtro avanzado */
$(document).on("click", "#btnfiltrar", function() {

    var tipo_documento = $('#tipo_documento').val();
    var institucion = $('#institucion_educativa').val();

    listardatatable(tipo_documento, institucion);

});

function listardatatable(tipo_documento, institucion) {
    tabla = $('#datos_academicos_data').dataTable({
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
            url: '../../controller/documentoAcademico.php?op=listar_filtro',
            type: "post",
            dataType: "json",
            data: { tipo_documento: tipo_documento, institucion: institucion },
            error: function(e) {
                console.log(e.responseText);
            }
        },
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
    }).DataTable().ajax.reload();
}

$(document).on("click", "#btntodo", function() {

    tabla = $('#datos_academicos_data').dataTable({
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
            url: '../../controller/documentoAcademico.php?op=listar_x_usu',
            type: "post",
            dataType: "json",
            data: { usu_id: usu_id },
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

});

/* TODO: Link para poder ver el eliminar el Documento academico */
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
            $.post("../../controller/documentoAcademico.php?op=delete", {ciphertext : ciphertext}, function(e){
                
                if(e == "Documento eliminado"){
                    Swal.fire({
                        title: e,
                        text: "El documento se eliminó correctamente.",
                        icon: "success",
                        showCancelButton: true,
                        confirmButtonColor: "#3d85c6",
                        confirmButtonText: "OK"
                    });
                    tabla.ajax.reload();
                }
                else{
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

function guardaryeditar(e){
    e.preventDefault();
    /* TODO: Array del form Documento Academico */
    var formData = new FormData($("#doc_academico_form")[0]);
    /* TODO: validamos si los campos tienen informacion antes de guardar */

    if (($('#dato_adic').summernote('isEmpty') || $('#imagen').val()=='' 
    || $('#tipo_documento').val() == 0|| $('#institucion_educativa').val() == 0) 
    && $('#imagenmuestra').length == 0){
        Swal.fire("Advertencia!", "Campos Vacios", "warning");
    }else{

        /* TODO: Guardar Documento Academico */
        if(idEncrypted == ""){
            $.ajax({
                url: "../../controller/documentoAcademico.php?op=insert",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data){
                    /* TODO: Limpiar campos */
                    $('#imagen').val('');
                    $('#dato_adic').summernote('reset');
                    $('#fecha').val('');
                    $('#tipo_documento').val('');
                    
                    if(data= "Documento agregado"){
                        Swal.fire({
                            title: "¡Listo!",
                            text: "Registrado Correctamente",
                            icon: "success",
                            showCancelButton: true,
                            confirmButtonColor: "#3d85c6",
                            confirmButtonText: "OK"
                        }).then((result) => {    
                            if (result.isConfirmed) {    
                                window.location.replace('listarDocsAcademicos.php'); 
                            }
                        });
                    }
                    else{
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
        else{

            $.ajax({
                url: "../../controller/documentoAcademico.php?op=update&img="+$('#imagenmuestra').length,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data){
                    console.log(data);
                    if(data= "Documento modificado"){
                        Swal.fire({
                            title: "¡Listo!",
                            text: data,
                            icon: "success",
                            showCancelButton: true,
                            confirmButtonColor: "#3d85c6",
                            confirmButtonText: "OK"
                        }).then((result) => {   
                            if (result.isConfirmed) {    
                                window.location.replace('listarDocsAcademicos.php'); 
                            }
                        });
                    }
                    else{
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

function cargarDocumentoAcademico(doc_academico_id){
    /* TODO: Mostramos informacion del documento en inputs */
    $.post("../../controller/documentoAcademico.php?op=mostrar", { doc_academico_id: doc_academico_id }, function(data) {
        data = JSON.parse(data);
        $('#tipo_documento').val(data.tipo_documento);
        $('#tipo_documento').trigger('change');
        $('#institucion_educativa').val(data.institucion_educativa);
        $('#institucion_educativa').trigger('change');
        $('#imagenmuestra').attr("src",data.documento_ruta);
        $('#dato_adic').summernote('code', data.dato_adic);

        $('#idEncrypted').val(doc_academico_id);
    });
}

/*=============================================
SUBIENDO EL ARCHIVO
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

init();