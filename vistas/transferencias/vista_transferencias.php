<?php 
    include("../../controladores/crud_transferencias.php");
    include("../header.php");
?>

<section class="content-header mb-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center ml-3 mb-3">
            <h2 class="h3 mb-0">
                Transferencias
            </h2>
            <a href="crear_transferencia.php" class="btn btn-dark btn-sm mr-3 info d-flex align-items-center justify-content-center" tabindex="1">
                <i class="fa fa-plus mr-2"></i>Agregar Transferencia
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
                                    <th class="text-left bg-thead">ID</th>
                                    <th class="text-left bg-thead">Cliente</th>
                                    <th class="text-left bg-thead">Origen</th>
                                    <th class="text-left bg-thead">Destino</th>
                                    <th class="text-left bg-thead">Vehículo</th>
                                    <th class="text-center bg-thead">Cant. Productos</th>
                                    <th class="text-center bg-thead">Peso Total</th>
                                    <th class="text-center bg-thead">Estado</th>
                                    <th class="text-left bg-thead">Fecha Creación</th>
                                    <th class="text-left bg-thead">Fecha Despacho</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                                foreach ($transferencias as $transferencia) {
                                    // Formatear fechas
                                    $fechaCreacion = date('d-m-Y H:i', strtotime($transferencia['fecha_creacion']));
                                    $fechaDespacho = date('d-m-Y', strtotime($transferencia['fecha_despacho']));
                                    
                                    // Determinar la clase del estado
                                    $estadoClass = $transferencia['estado'] === 'completada' ? 'estado-activo' : 
                                                 ($transferencia['estado'] === 'pendiente' ? 'estado-pendiente' : 'estado-inactivo');
                            ?>
                            <tr data-id="<?= $transferencia['id_transferencia'] ?>">
                                <td><?= $transferencia['id_transferencia'] ?></td>
                                <td><?= htmlspecialchars($transferencia['nombre_cliente']) ?></td>
                                <td><?= htmlspecialchars($transferencia['origen']) ?></td>
                                <td><?= htmlspecialchars($transferencia['destino'] ?? '') ?></td>
                                <td><?= htmlspecialchars($transferencia['tipo_vehiculo']) ?></td>
                                <td class="text-center"><?= $transferencia['cantidad_productos'] ?></td>
                                <td class="text-center"><?= number_format($transferencia['peso_total'], 2) ?> kg</td>
                                <td class="text-center">
                                    <span class="<?= $estadoClass ?> general"><?= ucfirst($transferencia['estado']) ?></span>
                                </td>
                                <td><?= $fechaCreacion ?></td>
                                <td><?= $fechaDespacho ?></td>
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

<style>
    .productos-row {
        background-color: #f8f9fa;
    }
    .productos-row td {
        padding: 1rem !important;
    }
    .productos-table {
        width: 100%;
        margin-bottom: 0;
    }
    .productos-table th {
        background-color: #e9ecef;
    }
    .loading-spinner {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 1rem;
    }
    .producto-foto {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 4px;
    }
</style>
<script src="../../plugins/jquery/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        // Función para obtener los productos de una transferencia
        function getProductosTransferencia(id_transferencia) {
            return $.ajax({
                url: '../../controladores/crud_transferencias.php',
                type: 'POST',
                data: {
                    action: 'get_productos_transferencia',
                    id_transferencia: id_transferencia
                },
                dataType: 'json'
            });
        }

        // Manejar el clic en la fila
        $('#table tbody').on('click', 'tr', function() {
            var tr = $(this);
            var id_transferencia = tr.data('id');
            var row = $('#table').DataTable().row(tr);

            // Si ya está expandida, la cerramos
            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            } else {
                // Mostrar spinner de carga
                row.child('<div class="loading-spinner"><div class="spinner-border text-primary" role="status"><span class="sr-only">Cargando...</span></div></div>').show();
                tr.addClass('shown');

                // Obtener los productos
                getProductosTransferencia(id_transferencia)
                    .then(function(response) {
                        if (response.error) {
                            throw new Error(response.mensaje);
                        }

                        var productosHtml = '<table class="table table-sm productos-table">' +
                            '<thead><tr>' +
                            '<th>Foto</th>' +
                            '<th>Producto</th>' +
                            '<th class="text-center">Precio</th>' +
                            '<th class="text-center">Cantidad</th>' +
                            '<th class="text-center">Peso Unitario</th>' +
                            '<th class="text-center">Peso Total</th>' +
                            '</tr></thead><tbody>';

                        response.forEach(function(producto) {
                            var fotoUrl = producto.foto ? '../../uploads/productos/' + producto.foto : '../../assets/img/no-image.png';
                            productosHtml += '<tr>' +
                                '<td class="text-center"><img src="' + fotoUrl + '" class="producto-foto" alt="' + producto.nombre + '"></td>' +
                                '<td>' + producto.nombre + '</td>' +
                                '<td class="text-center">' + producto.precio + '</td>' +
                                '<td class="text-center">' + producto.cantidad + '</td>' +
                                '<td class="text-center">' + producto.peso_unitario + ' kg</td>' +
                                '<td class="text-center">' + producto.peso_total + ' kg</td>' +
                                '</tr>';
                        });

                        productosHtml += '</tbody></table>';
                        row.child(productosHtml).show();
                    })
                    .catch(function(error) {
                        console.error('Error:', error);
                        row.child('<div class="alert alert-danger m-3">' + error.message + '</div>').show();
                    });
            }
        });
    });
</script>

<?php include("../footer.php"); ?>