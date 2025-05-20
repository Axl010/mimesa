<?php 
    include("../../controladores/crud_transporte.php");
    include("../header.php");
?>

<section class="content-header mb-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center ml-3 mb-3">
            <h2 class="h3 mb-0">
                Vehículos
            </h2>
            <a href="crear_transporte.php" class="btn btn-dark btn-sm mr-3 info d-flex align-items-center justify-content-center" tabindex="1">
                <i class="fa fa-plus mr-2"></i>Agregar Vehículo    
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
                                    <th class="text-center bg-thead">Código</th>
                                    <th class="text-center bg-thead">Placa</th>
                                    <th class="text-center bg-thead">Tipo</th>
                                    <th class="text-center bg-thead">Capacidad (kg)</th>
                                    <th class="text-center bg-thead">Capacidad (paletas)</th>
                                    <th class="text-center bg-thead">Estado</th>
                                    <th class="text-center bg-thead">Unido</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                                foreach ($lista_transporte as $transporte) {
                                    // Separar fecha y hora
                                    $fechaCompleta = $transporte['fecha_creacion'];
                                    $fecha = date('d-m-Y', strtotime($fechaCompleta));
                                    $hora = date('H:i:s', strtotime($fechaCompleta));
                                
                                    // Determinar la clase del estado
                                    $estadoClass = $transporte['estado'] === 'activo' ? 'estado-activo' : 'estado-inactivo';
                            ?>
                            <tr class='text-center' data-href="editar_transporte.php?id_vehiculo=<?= $transporte['id_vehiculo'] ?>">
                                <td style="vertical-align: middle;"><?= $transporte['codigo'] ?></td>
                                <td style="vertical-align: middle;"><?= $transporte['placa'] ?></td>
                                <td style="vertical-align: middle;"><?= $transporte['tipo'] ?></td>
                                <td style="vertical-align: middle;"><?= $transporte['capacidad_carga_kg'] ?></td>
                                <td style="vertical-align: middle;"><?= $transporte['capacidad_paletas'] ?></td>
                                <td style="vertical-align: middle;">
                                    <span class="<?= $estadoClass ?> general"><?= $transporte['estado'] ?></span>
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
<?php include("../footer.php"); ?> 