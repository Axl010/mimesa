<?php 
    include("../../controladores/crud_clientes.php");
    include("../header.php");
?>

<section class="content-header mb-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center ml-3 mb-3">
            <h2 class="h3 mb-0">
                Clientes
            </h2>
            <a href="crear_cliente.php" class="btn btn-dark btn-sm mr-3 info d-flex align-items-center justify-content-center" tabindex="1">
                <i class="fa fa-plus mr-2"></i>Agregar Cliente
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
                                    <th class="text-left bg-thead">Tipo Cliente</th>
                                    <th class="text-left bg-thead">Nombre</th>
                                    <th class="text-left bg-thead">Razón Social</th>
                                    <th class="text-left bg-thead">Documento</th>
                                    <th class="text-left bg-thead">Dirección</th>
                                    <th class="text-left bg-thead">Teléfono</th>
                                    <th class="text-left bg-thead">Email</th>
                                    <th class="text-center bg-thead">Estado</th>
                                    <th class="text-left bg-thead">Región</th>
                                    <th class="text-center bg-thead">Fecha Registro</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                                foreach ($lista_clientes as $cliente) {
                                    // Formatear fecha de registro
                                    $fechaRegistro = !empty($cliente['fecha_registro']) ? 
                                        date('d-m-Y', strtotime($cliente['fecha_registro'])) : '';
                                    
                                    // Determinar la clase del estado
                                    $estadoClass = $cliente['estado'] === 'activo' ? 'estado-activo' : 'estado-inactivo';
                                    
                                    // Combinar tipo y número de documento
                                    $documento = !empty($cliente['documento_tipo']) ? 
                                        $cliente['documento_tipo'] . ': ' . $cliente['documento_numero'] : 
                                        $cliente['documento_numero'];
                            ?>
                            <tr>
                                <td><?= $cliente['id_cliente'] ?></td>
                                <td><?= ucfirst($cliente['tipo_cliente']) ?></td>
                                <td>
                                    <a href="editar_cliente.php?id=<?= $cliente['id_cliente']?>" class="enlace">  
                                        <?= htmlspecialchars($cliente['nombre']) ?>
                                    </a>
                                </td>
                                <td><?= htmlspecialchars($cliente['razon_social'] ?? '') ?></td>
                                <td><?= htmlspecialchars($documento ?? '') ?></td>
                                <td><?= htmlspecialchars($cliente['direccion'] ?? '') ?></td>
                                <td><?= htmlspecialchars($cliente['telefono'] ?? '') ?></td>
                                <td><?= htmlspecialchars($cliente['email'] ?? '') ?></td>
                                <td class="text-center">
                                    <!-- Mostrar estado en óvalo -->
                                    <span class="<?= $estadoClass ?> general"><?= ucfirst($cliente['estado']) ?></span>
                                </td>
                                <td><?= htmlspecialchars($cliente['region'] ?? '') ?></td>
                                <td class="text-center"><?= $fechaRegistro ?></td>
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

<script>
    // Hacer que las filas de la tabla sean clickeables para editar
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            row.addEventListener('click', function(e) {
                // No navegar si se hizo clic en un enlace
                if (e.target.tagName === 'A' || e.target.closest('a')) {
                    return;
                }
                
                // Obtener el ID del cliente desde el enlace en la tercera columna
                const nombreLink = this.querySelector('td:nth-child(3) a');
                if (nombreLink) {
                    window.location.href = nombreLink.getAttribute('href');
                }
            });
            
            // Cambiar el cursor al pasar sobre la fila
            row.style.cursor = 'pointer';
        });
    });
</script>

<?php include("../footer.php"); ?>