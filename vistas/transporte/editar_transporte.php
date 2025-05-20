<?php
    include("../../controladores/crud_transporte.php");
    include("../header.php");

    // Verificar si se recibió un ID
    if (!isset($_GET['id_vehiculo'])) {
        header("Location: vista_transporte.php?mensaje=Error: ID no proporcionado");
        exit();
    }

    // Obtener los datos del vehículo
    $id_vehiculo = $_GET['id_vehiculo'];
    $consulta = $conexion->prepare("SELECT * FROM vehiculos WHERE id_vehiculo = :id_vehiculo");
    $consulta->bindParam(':id_vehiculo', $id_vehiculo);
    $consulta->execute();
    $transporte = $consulta->fetch(PDO::FETCH_ASSOC);

    if (!$transporte) {
        header("Location: vista_transporte.php?mensaje=Error: Transporte no encontrado");
        exit();
    }
?>

<section class="content">
    <div class="container">
        <form id="form" method="POST" action="../../controladores/crud_transporte.php" enctype="multipart/form-data" class="needs-validation" novalidate>
            <input type="hidden" name="id_vehiculo" value="<?= $transporte['id_vehiculo'] ?>">
            <div class="row justify-content-center">
                <div class="col-lg-9 col-md-12 col-sm-12 mb-3">
                    <div class="d-flex align-items-center"> 
                        <a href="vista_transporte.php" class="button-back">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <h2 class="h4 ml-1">Editar Vehículo</h2>
                    </div>
                </div>
                <div class="col-lg-6 col-md-8 col-sm-12 mb-3">
                    <div class="card"> 
                        <div class="card-body">
                            <div class="form-group">
                                <label for="codigo" class="form-label">Código *</label>
                                <input type="text" class="form-control form-control-sm" name="codigo" id="codigo" value="<?= htmlspecialchars($transporte['codigo']) ?>" tabindex="1" required/>
                            </div>
                            <div class="form-group">
                                <label for="placa" class="form-label">Placa</label>
                                <input type="text" class="form-control form-control-sm" name="placa" id="placa" value="<?= htmlspecialchars($transporte['placa']) ?>" tabindex="2"/>
                            </div>
                            <div class="form-group">
                                <label for="tipo" class="form-label">Tipo *</label>
                                <input type="text" class="form-control form-control-sm" name="tipo" id="tipo" value="<?= htmlspecialchars($transporte['tipo']) ?>" tabindex="3" required/>
                            </div>
                            <div class="form-group">
                                <label for="capacidad_carga_kg" class="form-label">Capacidad (kg) *</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" name="capacidad_carga_kg" id="capacidad_carga_kg" value="<?= htmlspecialchars($transporte['capacidad_carga_kg']) ?>" tabindex="4" required/>
                            </div>
                            <div class="form-group">
                                <label for="capacidad_paletas" class="form-label">Capacidad (paletas) *</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" name="capacidad_paletas" id="capacidad_paletas" value="<?= htmlspecialchars($transporte['capacidad_paletas']) ?>" tabindex="5" required/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="estado" class="form-label bold">Estado</label>
                                <select class="form-select form-select-sm" id="estado" name="estado" tabindex="6">
                                    <option value="activo" <?= $transporte['estado'] === 'activo' ? 'selected' : '' ?>>Activo</option>
                                    <option value="inactivo" <?= $transporte['estado'] === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-row-reverse mt-4">
                        <input type="submit" class="btn btn-primary btn-sm btn-danger" name="editar_transporte" value="Guardar" tabindex="7">
                        <a href="vista_transporte.php" role="button" class="btn btn-secondary btn-sm btn-reset mr-2" tabindex="8">Cancelar</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<script src="../../js/validacion_inputs.js"></script>
<?php include("../footer.php"); ?> 