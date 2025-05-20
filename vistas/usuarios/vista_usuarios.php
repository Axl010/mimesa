<?php 
    include("../../controladores/crud_usuario.php");
    include("../header.php");
?>
<section class="content-header mb-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center ml-3 mb-3">
            <h2 class="h3 mb-0">
                Usuarios
            </h2>
            <a href="crear_usuario.php" class="btn btn-dark btn-sm mr-3 info d-flex align-items-center justify-content-center" tabindex="1">
                <i class="fa fa-plus mr-2"></i>Agregar Usuario
            </a>
        </div>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card my-2 mx-3">
                <div class="my-3 custom-container">
                    <div class="table-responsive">
                        <table id="table" class="table table-hover nowrap custom-table" cellspacing="0" style="width:100%">
                            <thead class="table-primary">
                                <tr>
                                    <th class="text-left bg-thead"></th>
                                    <th class="text-left bg-thead">Nombre</th>
                                    <th class="text-left bg-thead">Usuario</th>
                                    <th class="text-center bg-thead">Estado</th>
                                    <th class="text-center bg-thead">Teléfono</th>
                                    <th class="text-center bg-thead">Última Conexion</th>
                                    <th class="text-center bg-thead">Unido</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                                foreach ($lista_usuarios as $usuario) {
                                    // Ruta de la imagen
                                    $fotoUrl = !empty($usuario['foto_usuario']) ? $usuario['foto_usuario'] : '../../photos/usuarios/default_user.png'; // Foto predeterminada si no hay imagen
                                    
                                    // Separar fecha y hora
                                    $fechaCompleta = $usuario['fecha_creacion'];
                                    $fechaConexion = $usuario['ultima_conexion'];

                                    $fecha = date('d-m-Y', strtotime($fechaCompleta));
                                    $hora = date('H:i:s', strtotime($fechaCompleta));
                                    $conexion = date('d-m-Y', strtotime($fechaConexion));
                                    $horaConexion = date('H:i:s', strtotime($fechaConexion));
                                
                                    // Determinar la clase del estado
                                    $estadoClass = $usuario['estado'] === 'activo' ? 'estado-activo' : 'estado-inactivo';
                            ?>
                            <tr class='text-center' data-href="editar_usuario.php?id_usuario=<?= $usuario['id_usuario'] ?>">
                                <td width="40" style="vertical-align: middle;">
                                    <img src="<?= $fotoUrl ?>" alt="Foto del usuario" class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                </td>
                                <td style="vertical-align: middle;text-align:left;">
                                    <a href="javascript:void(0);" class="enlace" data-url="editar_usuario.php?id_usuario=<?= $usuario['id_usuario']?>">  
                                        <?= $usuario['nombre'] ?>
                                    </a>
                                </td>
                                <td style="vertical-align: middle;text-align:left;"><?= $usuario['usuario'] ?></td>
                                <td style="vertical-align: middle;">
                                    <!-- Mostrar estado en óvalo -->
                                    <span class="<?= $estadoClass ?> general"><?= $usuario['estado'] ?></span>
                                </td>
                                <td style="vertical-align: middle;"><?= $usuario['telefono']?></td>
                                <td style="vertical-align: middle;">
                                <?php if(!empty($fechaConexion)){?>
                                    <div><?= $conexion ?></div>
                                    <div class="hora"><?= $horaConexion ?></div>
                                <?php }?>
                                </td>
                                <td style="vertical-align: middle;">
                                    <div><?= $fecha ?></div>
                                    <div class="hora"><?= $hora ?></div>
                                </td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../js/notificaciones.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let urlDestino = '';
    let notificacionMostrada = false;
    let idUsuarioEditar = '';
    
    // Manejar clic en el enlace o en la fila
    $('.enlace, tr[data-href]').on('click', function(e) {
        e.preventDefault();
        if ($(this).hasClass('enlace')) {
            urlDestino = $(this).data('url');
            idUsuarioEditar = urlDestino.split('=')[1];
        } else {
            urlDestino = $(this).data('href');
            idUsuarioEditar = urlDestino.split('=')[1];
        }
        const modal = new bootstrap.Modal(document.getElementById('modalVerificacion'));
        modal.show();
    });

    // Función para verificar la contraseña
    function verificarPassword() {
        const password = $('#password').val();
        
        $.ajax({
            url: '../../controladores/crud_usuario.php',
            type: 'POST',
            data: {
                action: 'verificar_password',
                password: password,
                id_usuario: idUsuarioEditar
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    window.location.href = urlDestino;
                } else {
                    if (!notificacionMostrada) {
                        notificacionMostrada = true;
                        mostrarError('Contraseña incorrecta');
                        setTimeout(() => {
                            notificacionMostrada = false;
                        }, 3000);
                    }
                    $('#password').val('').focus();
                }
            },
            error: function() {
                if (!notificacionMostrada) {
                    notificacionMostrada = true;
                    mostrarError('Error al verificar la contraseña');
                    setTimeout(() => {
                        notificacionMostrada = false;
                    }, 3000);
                }
            }
        });
    }

    // Manejar el botón de verificar
    $('#btnVerificar').on('click', verificarPassword);

    // Manejar la tecla Enter en el campo de contraseña
    $('#password').on('keypress', function(e) {
        if (e.which === 13) { // 13 es el código de la tecla Enter
            e.preventDefault();
            verificarPassword();
        }
    });

    // Limpiar el campo de contraseña cuando se cierra el modal
    $('#modalVerificacion').on('hidden.bs.modal', function() {
        $('#password').val('');
        notificacionMostrada = false;
    });

    // Enfocar el campo de contraseña cuando el modal se muestre
    $('#modalVerificacion').on('shown.bs.modal', function() {
        $('#password').focus();
    });
});
</script>

<!-- Modal de verificación de contraseña -->
<div class="modal fade" id="modalVerificacion" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Verificación de Contraseña</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="password">Ingrese su contraseña:</label>
                    <input type="password" class="form-control" id="password" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnVerificar">Verificar</button>
            </div>
        </div>
    </div>
</div>

<?php include("../footer.php"); ?>