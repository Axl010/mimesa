<?php 
    include("../../controladores/crud_transferencias.php");
    include("../header.php");
?>

<section class="content-header mb-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center ml-3 mb-3">
            <h2 class="h3 mb-0">
                Pedidos
            </h2>
            <div class="d-flex">
                <button id="btnImprimir" class="btn btn-info btn-sm mr-2 d-flex align-items-center justify-content-center" title="Imprimir Reporte">
                    <i class="fas fa-print mr-2"></i>Imprimir
                </button>
                <a href="crear_transferencia.php" class="btn btn-dark btn-sm mr-3 info d-flex align-items-center justify-content-center" tabindex="1">
                    <i class="fa fa-plus mr-2"></i>Agregar Pedido
                </a>
            </div>
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
                                    <th class="text-left bg-thead">Carga</th>
                                    <th class="text-left bg-thead">Cliente</th>
                                    <th class="text-left bg-thead">Origen</th>
                                    <th class="text-left bg-thead">Destino</th>
                                    <th class="text-left bg-thead">Vehículo</th>
                                    <th class="text-center bg-thead">Cant. Productos</th>
                                    <th class="text-center bg-thead">Peso Total</th>
                                    <th class="text-center bg-thead">Total Paletas</th>
                                    <th class="text-center bg-thead">Estado</th>
                                    <th class="text-left bg-thead">Fecha Creación</th>
                                    <th class="text-left bg-thead">Fecha Despacho</th>
                                    <th class="text-center bg-thead">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                                foreach ($transferencias as $transferencia) {
                                    // Formatear fechas
                                    $fechaCreacion = date('d-m-Y H:i', strtotime($transferencia['fecha_creacion']));
                                    $fechaDespacho = date('d-m-Y', strtotime($transferencia['fecha_despacho']));
                                    
                                    // Determinar la clase del estado
                                    $estadoClass = $transferencia['estado'] === 'facturado' ? 'estado-activo' : 
                                                 ($transferencia['estado'] === 'torre de control' ? 'estado-pendiente' : 'estado-inactivo');
                            ?>
                            <tr data-id="<?= $transferencia['id_transferencia'] ?>">
                                <td><?= $transferencia['id_transferencia'] ?></td>
                                <td><?= htmlspecialchars($transferencia['nombre_cliente']) ?></td>
                                <td><?= htmlspecialchars($transferencia['origen']) ?></td>
                                <td><?= htmlspecialchars($transferencia['destino'] ?? '') ?></td>
                                <td><?= htmlspecialchars($transferencia['tipo_vehiculo']) ?></td>
                                <td class="text-center"><?= $transferencia['cantidad_productos'] ?></td>
                                <td class="text-center"><?= number_format($transferencia['peso_total'], 2) ?> kg</td>
                                <td class="text-center"><?= number_format($transferencia['total_paletas'], 2) ?></td>
                                <td class="text-center">
                                    <span class="<?= $estadoClass ?> general"><?= ucfirst($transferencia['estado']) ?></span>
                                </td>
                                <td><?= $fechaCreacion ?></td>
                                <td><?= $fechaDespacho ?></td>
                                <td class="text-center">
                                    <?php if ($transferencia['estado'] === 'torre de control') { ?>
                                        <button class="btn btn-success btn-sm aceptar-transferencia" data-id="<?= $transferencia['id_transferencia'] ?>" title="Aceptar Transferencia">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm cancelar-transferencia" data-id="<?= $transferencia['id_transferencia'] ?>" title="Cancelar Transferencia">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    <?php } elseif ($transferencia['estado'] === 'cancelado') { ?>
                                        <button class="btn btn-warning btn-sm restaurar-transferencia" data-id="<?= $transferencia['id_transferencia'] ?>" title="Restaurar Transferencia">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    <?php } ?>
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

<!-- Modal para el reporte de impresión -->
<div class="modal fade" id="modalImpresion" tabindex="-1" role="dialog" aria-labelledby="modalImpresionLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalImpresionLabel">Reporte de Transferencia</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="contenidoImpresion">
                <!-- El contenido se cargará dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnImprimirReporte">Imprimir</button>
            </div>
        </div>
    </div>
</div>
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script> -->
<script src="../../js/notificaciones.js"></script>
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

        // Función para actualizar el estado de una transferencia
        function actualizarEstadoTransferencia(id_transferencia, nuevoEstado) {
            return $.ajax({
                url: '../../controladores/crud_transferencias.php',
                type: 'POST',
                data: {
                    action: 'actualizar_estado_transferencia',
                    id_transferencia: id_transferencia,
                    nuevo_estado: nuevoEstado
                },
                dataType: 'json'
            });
        }

        // Manejar el clic en la fila
        $('#table tbody').on('click', 'tr', function(e) {
            // Si el clic fue en un botón de acción o en el botón responsive, no expandir la fila
            if ($(e.target).closest('.aceptar-transferencia, .cancelar-transferencia, .restaurar-transferencia, .dtr-control, .dtr-data').length || 
                $(e.target).hasClass('dtr-control')) {
                return;
            }

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
                            '<th>Descripción</th>' +
                            '<th class="text-center">Cantidad</th>' +
                            '<th class="text-center">Peso Caja</th>' +
                            '<th class="text-center">Peso Total</th>' +
                            '<th class="text-center">Paletas</th>' +
                            '</tr></thead><tbody>';

                        response.productos.forEach(function(producto) {
                            var fotoUrl = producto.foto ? '../../uploads/productos/' + producto.foto : '../../photos/productos/default_producto.jpg';
                            var pesoUnitario = parseFloat(producto.peso_unitario);
                            var pesoTotal = pesoUnitario * parseInt(producto.cantidad);
                            
                            productosHtml += '<tr>' +
                                '<td class="text-center"><img src="' + fotoUrl + '" class="producto-foto" alt="' + producto.nombre + '"></td>' +
                                '<td>' + producto.nombre + '</td>' +
                                '<td class="text-center">' + producto.cantidad + '</td>' +
                                '<td class="text-center">' + pesoUnitario.toFixed(2) + ' kg</td>' +
                                '<td class="text-center">' + pesoTotal.toFixed(2) + ' kg</td>' +
                                '<td class="text-center">' + producto.paletas + '</td>' +
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

        // Manejar clic en botón de aceptar transferencia
        $(document).on('click', '.aceptar-transferencia', function(e) {
            e.stopPropagation();
            var id_transferencia = $(this).data('id');
            var button = $(this);
            
            confirmar('¿Estás seguro de aceptar esta transferencia?', 
                function() {
                    actualizarEstadoTransferencia(id_transferencia, 'facturado')
                        .then(function(response) {
                            if (response.error) {
                                throw new Error(response.mensaje);
                            }
                            location.reload();
                        })
                        .catch(function(error) {
                            mostrarError('Error al actualizar el estado: ' + error.message);
                        });
                }
            );
        });

        // Manejar clic en botón de cancelar transferencia
        $(document).on('click', '.cancelar-transferencia', function(e) {
            e.stopPropagation();
            var id_transferencia = $(this).data('id');
            var button = $(this);
            
            confirmar('¿Estás seguro de cancelar esta transferencia?', 
                function() {
                    actualizarEstadoTransferencia(id_transferencia, 'cancelado')
                        .then(function(response) {
                            if (response.error) {
                                throw new Error(response.mensaje);
                            }
                            location.reload();
                        })
                        .catch(function(error) {
                            mostrarError('Error al actualizar el estado: ' + error.message);
                        });
                }
            );
        });

        // Manejar clic en botón de restaurar transferencia
        $(document).on('click', '.restaurar-transferencia', function(e) {
            e.stopPropagation();
            var id_transferencia = $(this).data('id');
            var button = $(this);
            
            confirmar('¿Estás seguro de restaurar esta transferencia?', 
                function() {
                    actualizarEstadoTransferencia(id_transferencia, 'torre de control')
                        .then(function(response) {
                            if (response.error) {
                                throw new Error(response.mensaje);
                            }
                            location.reload();
                        })
                        .catch(function(error) {
                            mostrarError('Error al actualizar el estado: ' + error.message);
                        });
                }
            );
        });

        // Función para cargar el reporte de impresión
        function cargarReporteImpresion(id_transferencia) {
            $.ajax({
                url: '../../controladores/crud_transferencias.php',
                type: 'POST',
                data: {
                    action: 'get_productos_transferencia',
                    id_transferencia: id_transferencia
                },
                dataType: 'json',
                success: function(response) {
                    if (response.error) {
                        mostrarError(response.mensaje);
                        return;
                    }

                    // Obtener la información de la transferencia
                    var tr = $('tr[data-id="' + id_transferencia + '"]');
                    var transferencia = response.info_transferencia;

                    // Crear el contenido del reporte
                    var contenido = `
                        <div class="reporte">
                            <div class="pagina">
                                <div class="reporte-header">
                                    <h4>REPORTE DE TRANSFERENCIA</h4>
                                    <p>Fecha: ${new Date().toLocaleDateString()}</p>
                                </div>
                                
                                <div style="margin-bottom: 20px;">
                                    <table style="width: 200px; border-collapse: collapse;">
                                        <tr style="background-color: #e9ecef !important;">
                                            <td style="font-weight: bold; padding: 3px;">CARGA</td>
                                            <td style="padding: 3px;">${id_transferencia}</td>
                                        </tr>
                                    </table>
                                </div>

                                <table class="reporte-tabla">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre Cliente</th>
                                            <th>Código SKU</th>
                                            <th>Descripción SKU</th>
                                            <th>Zona de Entrega</th>
                                            <th>Can Pedido</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                    `;

                    // Variable para el total
                    let totalCantidad = 0;

                    // Agregar los productos
                    response.productos.forEach(function(producto) {
                        totalCantidad += parseInt(producto.cantidad);
                        contenido += `
                            <tr>
                                <td>${id_transferencia}</td>
                                <td>${transferencia.nombre_cliente || '-'}</td>
                                <td>${producto.codigo_sku || '-'}</td>
                                <td>${producto.nombre}</td>
                                <td>${transferencia.destino || '-'}</td>
                                <td>${producto.cantidad}</td>
                            </tr>
                        `;
                    });

                    // Agregar fila de total
                    contenido += `
                            <tr style="background-color: #e9ecef !important;">
                                <td colspan="5" style="text-align: left; font-weight: bold;">Grand Total</td>
                                <td style="font-weight: bold;">${totalCantidad}</td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="tabla-vehiculo">
                        <thead>
                            <tr>
                                <th>TRANSPORTE</th>
                                <th>CHOFER</th>
                                <th>CEDULA</th>
                                <th>PLACA</th>
                                <th>TIPO</th>
                                <th>VEHICULO</th>
                                <th>LG</th>
                                <th>OBSERV</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>TRANSPORTE</td>
                                <td>${transferencia.nombre_conductor || '-'}</td>
                                <td>${transferencia.cedula_conductor || '-'}</td>
                                <td>${transferencia.placa_vehiculo || '-'}</td>
                                <td>${transferencia.tipo_vehiculo || '-'}</td>
                                <td>${transferencia.tipo_vehiculo || '-'}</td>
                                <td>-</td>
                                <td>${transferencia.observacion || '-'}</td>
                            </tr>
                        </tbody>
                    </table>
                    `;

                    contenido += `
                        </div>
                        <div class="pagina">
                            <div class="reporte-header">
                                <h4>REPORTE DE CARGA</h4>
                                <p>Fecha: ${new Date().toLocaleDateString()}</p>
                            </div>
                            
                            <div style="margin-bottom: 20px;">
                                <table style="width: 200px; border-collapse: collapse;">
                                    <tr style="background-color: #e9ecef;">
                                        <td style="font-weight: bold; padding: 3px;">CARGA</td>
                                        <td style="padding: 3px;">${id_transferencia}</td>
                                    </tr>
                                </table>
                            </div>

                            <table class="reporte-tabla">
                                <thead>
                                    <tr>
                                        <th>Código SKU</th>
                                        <th>Descripción SKU</th>
                                        <th>Cant. Pedido</th>
                                        <th>Paletas Completas</th>
                                        <th>Parciales</th>
                                        <th>Total Paletas</th>
                                        <th>Toneladas</th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;

                    // Variables para los totales de la segunda hoja
                    let totalCantidadSegunda = 0;
                    let totalPaletasCompletas = 0;
                    let totalParciales = 0;
                    let totalPaletas = 0;
                    let totalToneladas = 0;

                    // Agregar los productos
                    response.productos.forEach(function(producto) {
                        var paletasCompletas = Math.floor(producto.cantidad / producto.cantidad_por_paleta);
                        var parciales = (producto.cantidad % producto.cantidad_por_paleta) / producto.cantidad_por_paleta;
                        var totalPaletasProducto = paletasCompletas + parciales;
                        var toneladas = parseFloat(producto.peso_total.replace(',', '')) / 1000;
                        
                        totalCantidadSegunda += parseInt(producto.cantidad);
                        totalPaletasCompletas += paletasCompletas;
                        totalParciales += parciales;
                        totalPaletas += totalPaletasProducto;
                        totalToneladas += toneladas;
                        
                        contenido += `
                            <tr>
                                <td>${producto.codigo_sku || '-'}</td>
                                <td>${producto.nombre}</td>
                                <td>${producto.cantidad}</td>
                                <td>${paletasCompletas}</td>
                                <td>${parciales.toFixed(2)}</td>
                                <td>${totalPaletasProducto.toFixed(2)}</td>
                                <td>${toneladas.toFixed(3)}</td>
                            </tr>
                        `;
                    });

                    // Agregar fila de totales
                    contenido += `
                            <tr style="background-color: #e9ecef !important;">
                                <td colspan="2" style="text-align: left; font-weight: bold;">Grand Total</td>
                                <td style="font-weight: bold;">${totalCantidadSegunda}</td>
                                <td style="font-weight: bold;">${totalPaletasCompletas}</td>
                                <td style="font-weight: bold;">${totalParciales.toFixed(2)}</td>
                                <td style="font-weight: bold;">${totalPaletas.toFixed(2)}</td>
                                <td style="font-weight: bold;">${totalToneladas.toFixed(3)}</td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="tabla-vehiculo">
                        <thead>
                            <tr>
                                <th>TRANSPORTE</th>
                                <th>CHOFER</th>
                                <th>CEDULA</th>
                                <th>PLACA</th>
                                <th>TIPO</th>
                                <th>VEHICULO</th>
                                <th>LG</th>
                                <th>OBSERV</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>TRANSPORTE</td>
                                <td>${transferencia.nombre_conductor || '-'}</td>
                                <td>${transferencia.cedula_conductor || '-'}</td>
                                <td>${transferencia.placa_vehiculo || '-'}</td>
                                <td>${transferencia.tipo_vehiculo || '-'}</td>
                                <td>${transferencia.tipo_vehiculo || '-'}</td>
                                <td>-</td>
                                <td>${transferencia.observacion || '-'}</td>
                            </tr>
                        </tbody>
                    </table>
                    `;

                    contenido += `
                        </div>
                    </div>
                    `;

                    $('#contenidoImpresion').html(contenido);
                    var modal = new bootstrap.Modal(document.getElementById('modalImpresion'));
                    modal.show();
                },
                error: function(xhr, status, error) {
                    mostrarError('Error al cargar el reporte: ' + error);
                }
            });
        }

        // Manejar clic en el botón de imprimir
        $('#btnImprimir').on('click', function() {
            var selectedRow = $('tr.selected');
            if (selectedRow.length === 0) {
                mostrarAdvertencia('Por favor, selecciona un pedido para imprimir');
                return;
            }
            
            // Verificar si la transferencia está cancelada
            var estado = selectedRow.find('td:eq(8) span').text().toLowerCase();
            if (estado === 'cancelado') {
                mostrarError('No puedes imprimir un pedido cancelado');
                return;
            }
            
            var id_transferencia = selectedRow.data('id');
            cargarReporteImpresion(id_transferencia);
        });

        // Manejar clic en el botón de imprimir reporte
        $('#btnImprimirReporte').on('click', function() {
            // Asegurarse de que el contenido esté listo antes de imprimir
            setTimeout(function() {
                window.print();
            }, 100);
        });

        // Agregar selección de fila
        $('#table tbody').on('click', 'tr', function(e) {
            // Si el clic fue en un botón de acción o en el botón responsive, no seleccionar la fila
            if ($(e.target).closest('.aceptar-transferencia, .cancelar-transferencia, .restaurar-transferencia, .dtr-control, .dtr-data').length || 
                $(e.target).hasClass('dtr-control')) {
                return;
            }

            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
            } else {
                $('#table tbody tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }
        });
    });
</script>

<?php include("../footer.php"); ?>