<?php 
    include("../../controladores/crud_transferencias.php");

    // Definir fechas para el formulario
    $fecha_actual = date('Y-m-d');
    $fecha_min = date('Y-m-d', strtotime('-1 month')); // Un mes atrás como ejemplo

    include("../header.php");
?>
<section class="content">   
    <div class="custom-container">
        <div class="row justify-content-center">
            <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                <div class="d-flex align-items-center"> 
                    <a href="vista_transferencias.php" class="button-back">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h2 class="h4 ml-1">Agregar Transferencia</h2>
                </div>
            </div>
            <div class="col-lg-8 col-md-7 col-sm-12 mb-3">
                <div class="card"> 
                    <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Productos</h5>
                                <div class="peso-info">
                                    <span class="badge bg-mimosa-green fs-5 me-2 p-2">Peso total: <span id="pesoTotal" class="fw-bold">0</span> kg</span>
                                    <span class="badge bg-warning fs-5 p-2">Capacidad: <span id="capacidadVehiculo" class="fw-bold">0</span> kg</span>
                                </div>
                            </div>
                            <table class="table table-hover styled-table" cellspacing="0" style="width:100%">
                                <thead class="table-dark">
                                    <tr>
                                        <th>C</th>
                                        <th>Producto</th>
                                        <th>Peso</th>
                                        <th style="width: 50px;"></th>
                                    </tr>
                                </thead>
                                <tbody id="tablaProductos">
                                    
                                </tbody>
                            </table>
                    </div>
                </div>
            </div><!--/.col -->
            <div class="col-lg-4 col-md-5 col-sm-12">
                <div class="card sticky-card">
                    <div class="card-body">
                    <form id="form" method="POST" class="needs-validation" novalidate>
                        <div class="form-group">
                            <label for="fecha_despacho">Fecha Despacho*</label>
                            <input type="date" class="form-control" name="fecha_despacho" value="<?= $fecha_actual ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="id_mesa" class="form-label">Vehículo *</label>
                            <select class="form-select form-select-sm buscador" name="id_vehiculo" data-placeholder="Selecciona una vehiculo" required>
                                <option value="" disabled selected></option>
                                <?php foreach ($lista_vehiculos as $vehiculo) { ?>
                                    <option value="<?= $vehiculo['id_vehiculo']; ?>" 
                                        data-capacidad="<?= $vehiculo['capacidad_carga_kg']; ?>">
                                        <?= $vehiculo['tipo']; ?> (Cap: <?= $vehiculo['capacidad_carga_kg']; ?> kg)
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="id_conductor" class="form-label">Conductor *</label>
                            <select class="form-select form-select-sm buscador" name="id_conductor" data-placeholder="Selecciona un conductor" required>
                                <option value="" disabled selected></option>
                                <?php foreach ($lista_conductores as $conductor) { ?>
                                    <option value="<?= $conductor['id_conductor']; ?>" 
                                        data-cedula="<?= $conductor['cedula']; ?>">
                                        <?= $conductor['nombre']; ?> (CI: <?= $conductor['cedula']; ?>)
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="origen" class="form-label">Origen *</label>
                            <input type="text" class="form-control form-control-sm" value="Planta Valencia" name="origen" id="origen" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="id_cliente" class="form-label">Cliente *</label>
                            <select class="form-select form-select-sm buscador" name="id_cliente" id="id_cliente" data-placeholder="Selecciona un cliente" required>
                                <option value="" disabled selected></option>
                                <?php foreach ($lista_clientes as $cliente) { 
                                    // Preparar texto para mostrar (nombre, documento, razón social si existe)
                                    $clienteTexto = $cliente['nombre'];
                                    if (!empty($cliente['razon_social'])) {
                                        $clienteTexto .= ' - ' . $cliente['razon_social'];
                                    }
                                    if (!empty($cliente['documento_tipo']) && !empty($cliente['documento_numero'])) {
                                        $clienteTexto .= ' (' . $cliente['documento_tipo'] . ': ' . $cliente['documento_numero'] . ')';
                                    }
                                ?>
                                    <option value="<?= $cliente['id_cliente']; ?>" 
                                        data-nombre="<?= htmlspecialchars($cliente['nombre']); ?>">
                                        <?= htmlspecialchars($clienteTexto); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="id_producto">Producto *</label>
                            <select class="form-select form-select-sm buscador" id="id_producto" name="id_producto" data-placeholder="Selecciona un producto">
                                <option value="" disabled selected></option>
                                <?php foreach ($lista_productos as $producto) { ?>
                                    <option value="<?= $producto['id_producto']; ?>" 
                                    data-nombre="<?= $producto['nombre']; ?>" 
                                    data-stock="<?= $producto['stock']?>"
                                    data-peso="<?= $producto['peso'] ?? 0 ?>"
                                    data-stock-original='<?= $producto['stock']?>'>
                                        <?= $producto['nombre']; ?> (Stock: <?= $producto['stock']; ?>)
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        
                        <div class="form-group d-flex justify-content-between gap-3">
                            <input type="number" name="cantidad" class="form-control numeros" placeholder="Cantidad" min="1">
                            <button type="button" id="agregarProducto" class="btn btn-sm btn-warning" style="font-weight: 500;">Agregar</button>
                        </div>
                        
                        <div class="form-group">
                            <label for="observacion" class="form-label">Observaciones:</label>
                            <textarea class="form-control form-control-sm" name="observacion" id="observacion" rows="3"></textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <a type="button" href="vista_transferencias.php" class="btn btn-sm btn-secondary mr-3 btn-cancelar">Cancelar</a>
                            <button type="submit" name="confirmar_transferencia" class="btn btn-sm btn-confirmar btn-confirmar">Confirmar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> 
</section><!-- /.content -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        // Array para almacenar los productos agregados
        let productosAgregados = [];
        let pesoTotal = 0;
        let capacidadVehiculo = 0;
        
        // Manejar el cambio de vehículo seleccionado
        $("select[name='id_vehiculo']").change(function() {
            const vehiculoId = $(this).val();
            if (vehiculoId) {
                const vehiculoOption = $(this).find("option:selected");
                // Obtener la capacidad de carga del vehículo seleccionado desde data attribute
                capacidadVehiculo = parseFloat(vehiculoOption.data("capacidad")) || 0;
                
                // Actualizar los elementos de la UI
                $("#capacidadVehiculo").text(capacidadVehiculo.toFixed(2));
                
                // Verificar si el peso total supera la capacidad
                verificarCapacidad();
            } else {
                capacidadVehiculo = 0;
                $("#capacidadVehiculo").text("0");
            }
        });
        
        // Función para verificar si el peso total supera la capacidad
        function verificarCapacidad() {
            if (capacidadVehiculo <= 0) {
                $("#pesoTotal").closest(".badge").removeClass("bg-mimosa-green").addClass("bg-danger");
                mostrarAdvertencia("El vehículo seleccionado no tiene capacidad de carga definida.");
            } else if (pesoTotal > capacidadVehiculo) {
                $("#pesoTotal").closest(".badge").removeClass("bg-mimosa-green").addClass("bg-danger");
                mostrarAdvertencia("¡El peso total supera la capacidad del vehículo!");
            } else {
                $("#pesoTotal").closest(".badge").removeClass("bg-danger").addClass("bg-mimosa-green");
            }
        }
        
        // Función para actualizar el peso total
        function actualizarPesoTotal() {
            pesoTotal = 0;
            productosAgregados.forEach(producto => {
                pesoTotal += parseFloat(producto.peso) * parseFloat(producto.cantidad);
            });
            
            $("#pesoTotal").text(pesoTotal.toFixed(2));
            verificarCapacidad();
        }
        
        // Manejar el evento de clic en el botón "Agregar"
        $("#agregarProducto").click(function() {
            // Obtener el producto seleccionado
            const productoSelect = $("#id_producto");
            const idProducto = productoSelect.val();
            const nombreProducto = productoSelect.find("option:selected").data("nombre");
            const stockProducto = productoSelect.find("option:selected").data("stock");
            const pesoProducto = productoSelect.find("option:selected").data("peso") || 0;
            
            // Obtener la cantidad ingresada o usar 1 como valor por defecto
            let cantidad = $("input[name='cantidad']").val();
            // Si no hay valor o es 0, usar 1 como valor por defecto
            if (!cantidad || parseInt(cantidad) <= 0) {
                cantidad = "1";
            }
            
            // Validar que se haya seleccionado un producto y una cantidad
            if (!idProducto) {
                mostrarAdvertencia("Por favor, seleccione un producto.");
                return;
            }
            
            if (parseInt(cantidad) > parseInt(stockProducto)) {
                mostrarError("La cantidad total no puede superar el stock disponible (" + stockProducto + ").");
                return;
            }
            
            // Verificar si el producto ya fue agregado
            const productoExistente = productosAgregados.find(p => p.id === idProducto);
            
            if (productoExistente) {
                // Actualizar la cantidad del producto existente
                const nuevaCantidad = parseInt(productoExistente.cantidad) + parseInt(cantidad);
                
                if (nuevaCantidad > parseInt(stockProducto)) {
                    mostrarError("La cantidad total no puede superar el stock disponible (" + stockProducto + ").");
                    return;
                }
                
                productoExistente.cantidad = nuevaCantidad;
                
                // Actualizar la fila en la tabla
                $("#producto-" + idProducto + " .cantidad-valor").text(nuevaCantidad);
                $("#producto-" + idProducto + " td:nth-child(3)").text((pesoProducto * nuevaCantidad).toFixed(2) + " kg");
            } else {
                // Agregar el producto al array
                productosAgregados.push({
                    id: idProducto,
                    nombre: nombreProducto,
                    cantidad: cantidad,
                    peso: pesoProducto
                });
                
                // Crear fila en la tabla
                const fila = `
                    <tr id="producto-${idProducto}">
                        <td>
                            <div class="d-flex align-items-center">
                                <button type="button" class="btn btn-sm btn-outline-secondary disminuir-cantidad me-1" data-id="${idProducto}">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <span class="cantidad-valor">${cantidad}</span>
                                <button type="button" class="btn btn-sm btn-outline-secondary aumentar-cantidad ms-1" data-id="${idProducto}">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </td>
                        <td>${nombreProducto}</td>
                        <td>${(pesoProducto * cantidad).toFixed(2)} kg</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger eliminar-producto" data-id="${idProducto}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                
                $("#tablaProductos").append(fila);
            }
            
            // Actualizar el peso total
            actualizarPesoTotal();
            
            // Limpiar los campos
            $("input[name='cantidad']").val("");
            productoSelect.val("").trigger("change");
            
            // Adjuntar los eventos a todos los botones
            actualizarEventosBotones();
            
            // Crear campos ocultos para el envío del formulario
            actualizarCamposOcultos();
        });
        
        // Función para actualizar los campos ocultos con los datos de los productos
        function actualizarCamposOcultos() {
            // Eliminar campos ocultos anteriores
            $(".producto-campo-oculto").remove();
            
            // Crear campos ocultos para cada producto
            productosAgregados.forEach((producto, index) => {
                $("#form").append(`
                    <input type="hidden" class="producto-campo-oculto" name="productos[${index}][id]" value="${producto.id}">
                    <input type="hidden" class="producto-campo-oculto" name="productos[${index}][cantidad]" value="${producto.cantidad}">
                    <input type="hidden" class="producto-campo-oculto" name="productos[${index}][peso]" value="${producto.peso}">
                `);
            });
            
            // Asegurarnos de que se incluyan el origen y cliente como campos ocultos también
            // Los valores se tomarán automáticamente de los campos del formulario
        }
        
        // Función para manejar los eventos de los botones
        function actualizarEventosBotones() {
            // Eliminar producto
            $(".eliminar-producto").off("click").on("click", function() {
                const idEliminar = $(this).data("id");
                
                // Eliminar del array
                productosAgregados = productosAgregados.filter(p => p.id !== idEliminar.toString());
                
                // Eliminar de la tabla
                $("#producto-" + idEliminar).remove();
                
                // Actualizar el peso total
                actualizarPesoTotal();
                
                // Actualizar campos ocultos
                actualizarCamposOcultos();
            });
            
            // Aumentar cantidad
            $(".aumentar-cantidad").off("click").on("click", function() {
                const idProducto = $(this).data("id");
                
                // Buscar el producto en el array
                const producto = productosAgregados.find(p => p.id === idProducto.toString());
                if (!producto) return;
                
                // Buscar stock disponible
                let stockDisponible = 0;
                let pesoProducto = 0;
                $("#id_producto option").each(function() {
                    if ($(this).val() === idProducto) {
                        stockDisponible = parseInt($(this).data("stock"));
                        pesoProducto = parseFloat($(this).data("peso")) || 0;
                        return false; // Salir del loop
                    }
                });
                
                // Si no encontramos el stock en las opciones, podemos usar el stock original del producto
                if (stockDisponible === 0) {
                    const opcionesProductos = <?php echo json_encode($lista_productos); ?>;
                    const productoInfo = opcionesProductos.find(p => p.id_producto === idProducto);
                    if (productoInfo) {
                        stockDisponible = parseInt(productoInfo.stock);
                        pesoProducto = parseFloat(productoInfo.peso) || 0;
                    }
                }
                
                // Validar stock
                if (parseInt(producto.cantidad) >= stockDisponible) {
                    mostrarAdvertencia("No puede aumentar más la cantidad. Stock máximo: " + stockDisponible);
                    return;
                }
                
                // Aumentar cantidad
                producto.cantidad = parseInt(producto.cantidad) + 1;
                
                // Actualizar la tabla
                $("#producto-" + idProducto + " .cantidad-valor").text(producto.cantidad);
                $("#producto-" + idProducto + " td:nth-child(3)").text((pesoProducto * producto.cantidad).toFixed(2) + " kg");
                
                // Actualizar peso total y campos ocultos
                actualizarPesoTotal();
                actualizarCamposOcultos();
            });
            
            // Disminuir cantidad
            $(".disminuir-cantidad").off("click").on("click", function() {
                const idProducto = $(this).data("id");
                
                // Buscar el producto en el array
                const producto = productosAgregados.find(p => p.id === idProducto.toString());
                if (!producto) return;
                
                // Validar cantidad mínima
                if (parseInt(producto.cantidad) <= 1) {
                    // Si solo queda 1, preguntar si desea eliminar
                    confirmar("¿Desea eliminar este producto de la lista?", function() {
                        // Eliminar del array
                        productosAgregados = productosAgregados.filter(p => p.id !== idProducto.toString());
                        
                        // Eliminar de la tabla
                        $("#producto-" + idProducto).remove();
                        
                        // Actualizar peso total y campos ocultos
                        actualizarPesoTotal();
                        actualizarCamposOcultos();
                    });
                    return;
                }
                
                // Disminuir cantidad
                producto.cantidad = parseInt(producto.cantidad) - 1;
                
                // Actualizar la tabla
                $("#producto-" + idProducto + " .cantidad-valor").text(producto.cantidad);
                $("#producto-" + idProducto + " td:nth-child(3)").text((pesoProducto * producto.cantidad).toFixed(2) + " kg");
                
                // Actualizar peso total y campos ocultos
                actualizarPesoTotal();
                actualizarCamposOcultos();
            });
        }
        
        // Manejar el envío del formulario
        $("#form").submit(function(e) {
            // Verificar que haya productos agregados
            if (productosAgregados.length === 0) {
                e.preventDefault();
                mostrarError("Agrega un producto.");
                return false;
            }
            
            // Verificar que se haya seleccionado un vehículo
            const vehiculo = $("select[name='id_vehiculo']").val();
            if (!vehiculo) {
                e.preventDefault();
                mostrarAdvertencia("Seleccione un vehículo.");
                return false;
            }
            
            // Verificar que se haya seleccionado una fecha
            const fecha = $("input[name='fecha_despacho']").val();
            if (!fecha) {
                e.preventDefault();
                mostrarAdvertencia("Por favor, seleccione una fecha de despacho.");
                return false;
            }
            
            // Verificar que se haya ingresado el origen
            const origen = $("input[name='origen']").val();
            if (!origen) {
                e.preventDefault();
                mostrarAdvertencia("Por favor, ingrese el origen de la transferencia.");
                return false;
            }
            
            // Verificar que se haya ingresado el cliente
            const cliente = $("select[name='id_cliente']").val();
            if (!cliente) {
                e.preventDefault();
                mostrarAdvertencia("Por favor, seleccione un cliente.");
                return false;
            }
            
            // Verificar que el peso total no supere la capacidad del vehículo
            if (capacidadVehiculo <= 0) {
                e.preventDefault();
                mostrarError("El vehículo seleccionado no tiene capacidad de carga.");
                return false;
            } else if (pesoTotal > capacidadVehiculo) {
                e.preventDefault();
                mostrarError("El peso total (" + pesoTotal.toFixed(2) + " kg) supera la capacidad del vehículo (" + capacidadVehiculo + " kg).");
                return false;
            }
            
            // Si todo está correcto, los campos ocultos ya están creados y el formulario se enviará normalmente
        });
    });
</script>
<script src="../../js/notificaciones.js"></script>
<script src="../../js/validacion_inputs.js"></script>
<?php include("../footer.php")?>