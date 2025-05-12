$(document).ready(function () {
    let urlEdicion = ''; // Variable global para almacenar la URL de edición

    $('#table').DataTable({
        "pageLength": 25,
        "lengthMenu": [
            [5, 10, 25, 50, 100],
            [5, 10, 25, 50, 100]
        ],
        order: [], // Desactiva el ordenamiento automático
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

        // Verificar si estamos en la página de usuarios
        if (window.location.pathname.includes('vista_usuarios.php')) {
            // Redirigir a la URL especificada en el atributo data-href
            urlEdicion = $(this).data('href');
            if (urlEdicion) {
                // Mostrar el modal de verificación
                var modal = new bootstrap.Modal(document.getElementById('modalVerificacion'));
                modal.show();
            }
        } else {
            // Para otras páginas, mantener el comportamiento original
            const href = $(this).data('href');
            if (href) {
                window.location.href = href;
            }
        }
    });

    // Manejar la verificación de contraseña
    $('#btnVerificar').on('click', function() {
        const password = $('#password').val();
        
        if (!password) {
            mostrarAdvertencia('Por favor, ingrese su contraseña');
            return;
        }
        
        // Enviar la contraseña al servidor para verificación
        $.ajax({
            url: '../../controladores/crud_usuario.php',
            type: 'POST',
            data: {
                action: 'verificar_password',
                password: password
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#modalVerificacion').modal('hide');
                    if (urlEdicion) {
                        window.location.href = urlEdicion;
                    }
                } else {
                    mostrarError('Contraseña incorrecta');
                    $('#password').val('').focus();
                }
            },
            error: function() {
                mostrarError('Error al verificar la contraseña');
            }
        });
    });

    // Limpiar el formulario cuando se cierra el modal
    $('#modalVerificacion').on('hidden.bs.modal', function() {
        $('#password').val('');
        urlEdicion = ''; // Limpiar la URL de edición
    });
});
