$(document).ready(function() {

    // Cargar las profesiones al cargar la página
    $.post("../../controller/usuario.php?op=comboProfesiones", function(data) {
        $('#profesion_id').html(data);

        // Cargar los establecimientos de salud al cargar la página
        cargarEstablecimientosSalud();

        // Llamar a cargarDatosProfesionales 
        cargarDatosProfesionales();
    });

    $('.agregar-estudio').click(function() {
        agregarFilaEstudio();
    });

    $('.agregar-trabajo').click(function() {
        agregarFilaTrabajo();

        // Recargar los establecimientos de salud en todos los select
        cargarEstablecimientosSalud();
    });

    // Evento para eliminar fila
    $(document).on('click', '.eliminar-fila', function() {
        $(this).closest('.trabajo-row').remove();
    });

    $(document).on('click', '.eliminar-fila-estudio', function() {
        $(this).closest('.estudio-row').remove();
    });

    actualizar_img();
});

// Función para cargar datos profesionales desde el servidor
function cargarDatosProfesionales() {
    $.post("../../controller/usuario.php?op=mostrarDatosProfesionales", function(response) {
        try {
            var jsArray = JSON.parse(response);

            if (jsArray && jsArray.length > 0) {
                var element = jsArray[0]; // Tomar el primer elemento (suponiendo uno solo)

                // Cargar los valores en los campos del formulario
                $('#profesion_id').val(element.profesion_id).trigger('change');

                // Asegúrate de que jsonDatosProfesionales sea un objeto y no una cadena
                var jsonDatosProfesionales = element.jsonDatosProfesionales;
                if (typeof jsonDatosProfesionales === 'string') {
                    jsonDatosProfesionales = JSON.parse(jsonDatosProfesionales);
                }

                $('#lugar_egreso').val(jsonDatosProfesionales.lugar_egreso);
                $('#anio_egreso').val(jsonDatosProfesionales.anio_egreso);

                // Cargar los lugares de trabajo
                var lugaresTrabajo = jsonDatosProfesionales.lugares_trabajo;
                for (var i = 0; i < lugaresTrabajo.length; i++) {
                    if (i > 0) {
                        agregarFilaTrabajo(); // Agregar fila adicional si es necesario
                    }
                }

                // Cargar los estudios
                var estudios = jsonDatosProfesionales.estudios;
                for (var i = 0; i < estudios.length; i++) {
                    if (i > 0) {
                        agregarFilaEstudio(); // Agregar fila adicional si es necesario
                    }
                }

                // Recargar los establecimientos de salud en todos los select y luego asignar valores
                cargarEstablecimientosSalud(function() {
                    for (var i = 0; i < lugaresTrabajo.length; i++) {
                        var filaTrabajo = $('.trabajo-row').eq(i);
                        filaTrabajo.find('select[name="lugar_trabajo[]"]').val(lugaresTrabajo[i].lugar_trabajo).trigger('change');
                        filaTrabajo.find('select[name="tipo_contrato[]"]').val(lugaresTrabajo[i].tipo_contrato).trigger('change');
                        filaTrabajo.find('select[name="vinculo[]"]').val(lugaresTrabajo[i].vinculo).trigger('change');
                    }
                });

                // Asignar valores a los estudios sin función adicional
                for (var i = 0; i < estudios.length; i++) {
                    var filaEstudio = $('.estudio-row').eq(i);
                    filaEstudio.find('select[name="titulo[]"]').val(estudios[i].titulo).trigger('change');
                    filaEstudio.find('input[name="titulo_descripcion[]"]').val(estudios[i].titulo_descripcion);
                }
            } else {
                console.error("La respuesta no contiene datos válidos:", response);
            }
        } catch (e) {
            console.error("Error al procesar la respuesta:", e, response);
        }
    });
}

function cargarEstablecimientosSalud(callback) {


    $.post("../../controller/usuario.php?op=comboEstablecimientosSalud", function(data) {
        $('select[name="lugar_trabajo[]"]').each(function() {
            if ($(this).children().length === 0) { // Si el select aún no tiene opciones cargadas
                $(this).html(data);

            }
        });

        // Inicializar select2 después de cargar las opciones en los select existentes
        $('select[name="lugar_trabajo[]"]').select2();
        // Llamar al callback después de cargar las opciones
        if (typeof callback === 'function') {
            callback();
        }
    });


}

function guardarDatosProfesionales() {
    // Validar el formulario antes de enviarlo
    if (!validateForm("datos_profesionales_form")) {
        return;
    }

    // Obtener los datos de especialidad, año de egreso y lugar de egreso
    var anioEgreso = $('#anio_egreso').val();
    var lugarEgreso = $('#lugar_egreso').val();

    // Crear un objeto con los datos profesionales
    var datosProfesionales = {
        anio_egreso: anioEgreso,
        lugar_egreso: lugarEgreso,
        lugares_trabajo: [],
        estudios: []
    };

    // Recorrer cada fila de trabajo para obtener lugar de trabajo
    $('.trabajo-row').each(function() {
        var lugarTrabajo = $(this).find('select[name="lugar_trabajo[]"]').val();
        var tipoContrato = $(this).find('select[name="tipo_contrato[]"]').val();
        var vinculo = $(this).find('select[name="vinculo[]"]').val();

        // Agregar cada lugar de trabajo al arreglo dentro de datosProfesionales
        datosProfesionales.lugares_trabajo.push({
            lugar_trabajo: lugarTrabajo,
            tipo_contrato: tipoContrato,
            vinculo: vinculo
        });
    });

    // Recorrer cada fila de estudio para obtener los datos
    $('.estudio-row').each(function() {
        var titulo = $(this).find('select[name="titulo[]"]').val();
        var titulo_descripcion = $(this).find('input[name="titulo_descripcion[]"]').val();

        // Agregar cada estudio al arreglo dentro de datosProfesionales
        datosProfesionales.estudios.push({
            titulo: titulo,
            titulo_descripcion: titulo_descripcion
        });
    });

    // Convertir el objeto a JSON
    var jsonDatosProfesionales = JSON.stringify(datosProfesionales);

    // Crear un objeto FormData para enviar al servidor
    var formData = new FormData($("#datos_profesionales_form")[0]);
    formData.append('jsonDatosProfesionales', jsonDatosProfesionales);

    // Realizar la petición AJAX para guardar los datos
    $.ajax({
        url: "../../controller/usuario.php?op=updateDatosProfesionales",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(data) {
            if (data == "ok") {
                Swal.fire({
                    title: "¡Listo!",
                    text: "Datos guardados correctamente",
                    icon: "success",
                    showCancelButton: false,
                    confirmButtonColor: "#3d85c6",
                    confirmButtonText: "OK"
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            } else {
                Swal.fire({
                    title: "Error",
                    text: "Hubo un problema al guardar los datos",
                    icon: "error",
                    showCancelButton: false,
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

function validateForm(formulario) {


    var form = document.getElementById(formulario);
    var elements = form.elements;
    var isEmpty = false;



    for (var i = 0; i < elements.length; i++) {

        var element = elements[i];

        // Excluir los campos imagen e imagenactual del proceso de validación
        if (element.id === 'imagen' || element.id === 'imagenactual') {
            continue; // Saltar estos campos
        }

        // Excluir los campos con nombres de array del proceso de validación
        if (element.name && element.name.endsWith('[]')) {
            continue; // Saltar estos campos
        }

        // Check if element is input or select
        if (element.tagName === "INPUT" || element.tagName === "SELECT") {


            console.log("Elemento:", element.name, "Valor:", element.value.trim());
            // Check if element is required and is empty
            if (!element.value.trim()) {

                isEmpty = true;
                break;
            }
        }
    }

    if (isEmpty) {
        Swal.fire({
            title: "Error",
            text: "Todos los campos son requeridos",
            icon: "error",
            showCancelButton: false,
            confirmButtonColor: "#3d85c6",
            confirmButtonText: "OK"
        });
    }


    return !isEmpty;
}

function agregarFilaTrabajo() {
    var trabajoRow = `
    <div class="row trabajo-row mt-2">
        <div class="col-md-6" >
            <label for="lugar_trabajo">Lugar de Trabajo</label>
            <select class="form-control" name="lugar_trabajo[]">
                <!-- Opciones se cargarán dinámicamente -->
            </select>
        </div>
        <div class="col-md-3" >
            <label for="tipo_contrato">Contrato</label>
            <select class="form-control" name="tipo_contrato[]">
                <option value=""></option>
                <option value="PER">Permanente</option>
                <option value="CON">Contratado</option>
            </select>
        </div>
        <div class="col-md-2" >
         <label for="vinculo">Vínculo</label>
            <select class="form-control" name="vinculo[]">
                <option value=""></option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
            </select>
        </div>
        <div class="col-md-1"  style="padding: 20px 0px 10px 10px !important">
            <i class="glyphicon glyphicon-trash eliminar-fila"
            style="float: center; color: #e06666; font-size: large; padding: 10px 0px 10px 5px; margin: 0px;"
             cursor: pointer;" aria-hidden="true" title="Eliminar fila"></i>
        </div>
    </div>`;
    $('#trabajo-container').append(trabajoRow);

    // Recargar los establecimientos de salud en los nuevos select
    cargarEstablecimientosSalud(function() {
        // Inicializar select2 en el nuevo select
        $('select[name="lugar_trabajo[]"]').last().select2();
    });

}

function agregarFilaEstudio() {
    var estudioRow = `
    <div class="row estudio-row col-12">
        <div class="col-md-2">
            <div class="form-group">
                <label for="titulo">Titulo</label>
                <select class="form-control" name="titulo[]">
                    <option value=""></option>
                    <option value="Doctorado">Doctorado</option>
                    <option value="Masterado">Masterado</option>
                    <option value="Diplomado">Diplomado</option>
                    <option value="Otro">Otro</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="titulo_descripcion">Descripción</label>
                <input type="text" class="form-control" name="titulo_descripcion[]" placeholder="Descripción">
            </div>
        </div>
        <div class="col-md-1" style="padding: 20px 0px 10px 20px !important">
            <i class="glyphicon glyphicon-trash eliminar-fila-estudio"
            style="float: center; color: #e06666; font-size: large;  padding: 10px 0px 10px 5px; margin: 0px;"
            cursor: pointer;" aria-hidden="true" title="Eliminar fila"></i>
        </div>
    </div>`;
    $('#estudio-container').append(estudioRow);
}

function guardarFotoRegistro() {

    var formData = new FormData();
    var fileInput = document.getElementById('imagen');
    var file = fileInput.files[0];

    formData.append('file', file);

    $.ajax({
        url: '../../controller/usuario.php?op=guardarFotoRegistro',
        type: 'POST',
        data: formData,
        processData: false, // Importante
        contentType: false, // Importante
        success: function(response) {
            var data = JSON.parse(response);
            if (data.status === "ok") {
                // Swal.fire({
                //     title: "Éxito",
                //     text: "La foto de perfil fue actualizada.",
                //     icon: "success",
                //     showCancelButton: true,
                //     confirmButtonColor: "#3d85c6",
                //     confirmButtonText: "OK"
                // });

                // // Actualizar la imagen de perfil con la nueva foto
                // var foto_perfil = document.querySelector('.avatar-preview-128 img');
                // foto_perfil.src = data.new_image_path;
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



/*=============================================
SUBIENDO EL ARCHIVO
=============================================*/
function actualizar_img() {
    $(".nuevaImagen").change(function() {
        var imagen = this.files[0];

        /*=============================================
        VALIDAMOS EL FORMATO DEL ARCHIVO SEA PDF, JPG O PNG
        =============================================*/
        if (imagen.type != "image/jpeg" && imagen.type != "image/png" && imagen.type != "application/pdf") {
            $(".nuevaImagen").val("");
            Swal.fire({
                title: "Error al subir la imagen",
                text: "¡El archivo debe estar en formato PDF, JPG o PNG!",
                confirmButtonText: "¡Cerrar!"
            });
        } else if (imagen.size > 8000000) {
            $(".nuevaImagen").val("");
            Swal.fire({
                title: "Error al subir la imagen",
                text: "¡El archivo no debe pesar más de 8MB!",
                confirmButtonText: "¡Cerrar!"
            });
        } else {
            var datosImagen = new FileReader();
            datosImagen.readAsDataURL(imagen);

            datosImagen.onload = function(event) {
                var rutaImagen = event.target.result;

                if (imagen.type === "application/pdf") {
                    $("#imagenmuestra").hide();
                    $("#pdfmuestra").attr("src", rutaImagen).show();
                } else {
                    $("#pdfmuestra").hide();
                    $("#imagenmuestra").attr("src", rutaImagen).show();
                }
            }; 
        }    
    });
}