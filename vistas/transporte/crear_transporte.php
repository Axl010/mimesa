<?php
    include("../../controladores/crud_transporte.php");
    include("../header.php");
?>
<section class="content">
    <div class="container">
        <form id="form" method="POST" action="../../controladores/crud_transporte.php" enctype="multipart/form-data" class="needs-validation" novalidate>
            <div class="row justify-content-center">
                <div class="col-lg-9 col-md-12 col-sm-12 mb-3">
                    <div class="d-flex align-items-center"> 
                        <a href="vista_transporte.php" class="button-back">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <h2 class="h4 ml-1">Agregar Transporte</h2>
                    </div>
                </div>
                <div class="col-lg-6 col-md-8 col-sm-12 mb-3">
                    <div class="card"> 
                        <div class="card-body">
                            <div class="form-group">
                                <label for="codigo" class="form-label">Código *</label>
                                <input type="text" class="form-control form-control-sm" name="codigo" id="codigo" tabindex="1" required/>
                            </div>
                            <div class="form-group">
                                <label for="placa" class="form-label">Placa</label>
                                <input type="text" class="form-control form-control-sm" name="placa" id="placa" tabindex="2"/>
                            </div>
                            <div class="form-group">
                                <label for="tipo" class="form-label">Tipo *</label>
                                <input type="text" class="form-control form-control-sm" name="tipo" id="tipo" tabindex="3" required/>
                            </div>
                            <div class="form-group">
                                <label for="capacidad_carga_kg" class="form-label">Capacidad (kg) *</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" name="capacidad_carga_kg" id="capacidad_carga_kg" tabindex="4" required/>
                            </div>
                            <div class="form-group">
                                <label for="capacidad_paletas" class="form-label">Capacidad (paletas) *</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" name="capacidad_paletas" id="capacidad_paletas" tabindex="5" required/>
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
                                    <option value="activo" selected>Activo</option>
                                    <option value="inactivo">Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-row-reverse mt-4">
                        <input type="submit" class="btn btn-primary btn-sm btn-danger" id="habilitar" name="agregar_transporte" value="Guardar" tabindex="7" disabled>
                        <a href="vista_transporte.php" role="button" class="btn btn-secondary btn-sm btn-reset mr-2" tabindex="8">Cancelar</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
<script src="../../js/habilitar_boton.js"></script>
<script src="../../js/validacion_inputs.js"></script>
<?php include("../footer.php"); ?> 