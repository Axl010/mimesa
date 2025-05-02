<?php 
    include("../../controladores/crud_productos.php");
    include("../header.php");
?>
    <section class="content-header">
        <div class="d-flex flex-wrap justify-content-between align-items-center ml-3 mb-3">
            <h2 class="h3 mb-0">
                Productos
            </h2>
            <a href="crear_producto.php" class="btn btn-dark btn-sm mr-3 info d-flex align-items-center justify-content-center" tabindex="1">
                <i class="fa fa-plus mr-2"></i>Agregar Producto
            </a>
        </div>
        <!-- <div class="d-flex flex-column align-items-end mt-2">
            <a href="../../vista_pedidos/pedidos/view_mesas.php" class="btn btn-success btn-sm mr-3 info" tabindex="1">Hacer Pedido</a>
        </div> -->
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card my-2 mx-3">
                    <div class="my-3 custom-container">
                        <div class="table-responsive">
                            <table id="table" class="table table-hover nowrap custom-table" cellspacing="0" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-left bg-thead"></th>
                                        <th class="text-left bg-thead">Título</th>
                                        <th class="text-center bg-thead">Estado</th>
                                        <th class="text-center bg-thead">Inventario</th>
                                        <th class="text-center bg-thead">Precio</th>
                                        <th class="text-center bg-thead">Fecha de Creación</th>
                                    </tr>
                                </thead>
                                <script>document.querySelector('thead').classList.add('table-blue');</script>
                                <tbody>
                                    <?php 
                                    foreach ($lista_productos as $producto) {
                                        // Ruta de la imagen
                                        $fotoUrl = !empty($producto['foto']) ? $producto['foto'] : '../../photos/default_producto.jpg'; // Foto predeterminada si no hay imagen
                                        // Separar fecha y hora
                                        $fechaCompleta = $producto['fecha_creacion'];
                                        $fecha = date('Y-m-d', strtotime($fechaCompleta));
                                        $hora = date('H:i:s', strtotime($fechaCompleta));

                                        // Determinar el estado
                                        $estadoClass = $producto['estado'] === 'activo' ? 'estado-activo' : 'estado-inactivo';
                                        $estadoStock = $producto['stock'] <= 0 ? 'stock-bajo' : 'stock-alto';
                                    ?>
                                    <tr class='text-center' data-href="editar_producto.php?id_producto=<?= $producto['id_producto'] ?>">
                                        <td width="40" style="vertical-align: middle;">
                                            <img src="<?= $fotoUrl ?>" alt="Foto del producto" class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                        </td>
                                        <td style="vertical-align: middle;text-align:left;">
                                            <a href="editar_producto.php?id_producto=<?= $producto['id_producto']?>" class="enlace">
                                                <?= $producto['producto'] ?>
                                            </a>
                                        </td>
                                        <td style="vertical-align: middle;">
                                            <!-- Mostrar estado en óvalo -->
                                            <span class="<?= $estadoClass ?> general"><?= $producto['estado'] ?></span>
                                        </td>
                                        <td style="vertical-align: middle;" class="<?= $estadoStock ?>"><?= $producto['stock'] . " en stock"?></td>
                                        <td style="vertical-align: middle;"><?= $producto['precio'] . " $" ?></td>
                                        <td style="vertical-align: middle;">
                                            <div><?= $fecha ?></div>
                                            <div style="font-size: 0.9em; color: #666;"><?= $hora ?></div>
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
<?php include("../footer.php") ?>