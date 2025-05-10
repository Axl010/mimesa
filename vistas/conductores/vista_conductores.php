<?php 
    include("../../controladores/crud_conductores.php");
    include("../header.php");
?>

<section class="content-header mb-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center ml-3 mb-3">
            <h2 class="h3 mb-0">
                Conductores
            </h2>
            <a href="crear_conductor.php" class="btn btn-dark btn-sm mr-3 info d-flex align-items-center justify-content-center" tabindex="1">
                <i class="fa fa-plus mr-2"></i>Agregar Conductor    
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
                                    <th class="text-center bg-thead">Nombre</th>
                                    <th class="text-center bg-thead">Estado</th>
                                    <th class="text-center bg-thead">Cédula</th>
                                    <th class="text-center bg-thead">Unido</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                                foreach ($lista_conductores as $conductor) {
                                    // Separar fecha y hora
                                    $fechaCompleta = $conductor['fecha_creacion'];
                                    $fecha = date('d-m-Y', strtotime($fechaCompleta));
                                    $hora = date('H:i:s', strtotime($fechaCompleta));
                                
                                    // Determinar la clase del estado
                                    $estadoClass = $conductor['estado'] === 'activo' ? 'estado-activo' : 'estado-inactivo';
                            ?>
                            <tr class='text-center' data-href="editar_conductor.php?id_conductor=<?= $conductor['id_conductor'] ?>">
                                <td style="vertical-align: middle;text-align:center;"><?= $conductor['nombre'] ?></td>
                                <td style="vertical-align: middle;">
                                    <!-- Mostrar estado en óvalo -->
                                    <span class="<?= $estadoClass ?> general"><?= $conductor['estado'] ?></span>
                                </td>
                                <td style="vertical-align: middle;"><?= $conductor['cedula']?></td>
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