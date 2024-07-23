function init() {
    $.post("../../controller/usuario.php?op=cantidadesTramites", function(data) {
        data = JSON.parse(data);
        $('#lbltramitesrealizados').html(data.lbltramitesrealizados);
    });

    $.post("../../controller/usuario.php?op=cantidadUsuarios", function(data) {
        data = JSON.parse(data);
        $('#cantidad_usuarios').html(data.cantidad_usuarios);
    });

    $.post("../../controller/usuario.php?op=cantidadPublicaciones", function(data) {
        data = JSON.parse(data);
        $('#cantidad_publicaciones').html(data.cantidad_publicaciones);
    });

    $.post("../../controller/usuario.php?op=cantidadesTramitesSistema", function(data) {
        data = JSON.parse(data);
        $('#cantidad_tramites').html(data.cantidad_tramites);
    });
}

$(document).ready(function() {
    var usuario_id = $('#user_idx').val();

    $.post("../../controller/usuario.php?op=grafico", { usuario_id: usuario_id }, function(data) {
        data = JSON.parse(data);

        new Morris.Bar({
            element: 'divgrafico',
            data: data,
            xkey: 'nom',
            ykeys: ['total'],
            labels: ['Value'],
            barColors: ["#1AB244"],
        });
    });

    $('#amigos-header').on('click', function() {
        $('.amigos-section').toggleClass('expanded');
    });

    $('#searchInput').on('keyup', function() {
        var searchTerm = $(this).val().toLowerCase();
        $('.friends-list-item').each(function() {
            var userName = $(this).find('.user-card-row-name a').text().toLowerCase();
            if (userName.includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});

init();