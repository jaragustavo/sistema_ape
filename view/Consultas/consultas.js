function init() {

    actualizar_img();
}
idEncrypted = 0;

$(document).ready(function () {
    // Extraer el ID del registro a modificar
    var currentURL = window.location.href;
    // Use a regular expression to extract the ID from the URL
    var match = currentURL.match(/[\?&]ID=([^&]*)/);
    // // Check if a match is found

        // Extracted ID is in match[1]
        idEncrypted = match[1];

        tabla = $('#movimientos_tramite_data').dataTable({
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
                url: '../../controller/consulta.php?op=cargarMovimientosTramite',
                type: "post",
                dataType: "json",
                data: {idTramiteGestionado: idEncrypted} ,
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
        $.post("../../controller/consulta.php?op=cargarDatosTramiteGestionado", {idEncrypted: idEncrypted},function (data) {
            data = JSON.parse(data);
            $('#usuario_solicitante').html(data.usuario_solicitante);
            $('#fecha_hora_crea').html(data.fecha_hora_crea);
            $('#fecha_ultimo_mov').html(data.fecha_ultimo_mov);
            $('#nombre_tramite').html(data.nombre_tramite);
            $('#estado_actual').html(data.estado_actual);
            $('#usuario_asignado').html(data.usuario_asignado);
        });
});

// Abrir el trámite para su verificación
$(document).on("click", ".btn-open-tramite", function () {
    const ciphertext = $(this).data("ciphertext");
    window.location.replace('revisarMovimientosTramite.php?ID=' + ciphertext + '');
});



