$(document).ready(function () {
    $('#table').DataTable({
        "pageLength": 25,
        "lengthMenu": [
            [5, 10, 25, 50, 100],
            [5, 10, 25, 50, 100]
        ],
        order: [], // // Desactiva el ordenamiento automático
        "responsive": true,
        "language": {
            "url": "../../plugins/datatable/es-ES.json"
        },
    });
    
    // Evento para redirigir al hacer clic en una fila
    $('#table tbody').on('click', 'tr', function (e) {
        // Ignorar clics en enlaces, imágenes, o el botón "+" de DataTables
        if ($(e.target).closest('td a, td button, td img, .dtr-control').length) {
            return;
        }

        // Redirigir a la URL especificada en el atributo data-href
        const href = $(this).data('href');
        console.log(`Redirigiendo a: ${href}`); // Para depuración
        if (href) {
            window.location.href = href;
        }
    });
});
