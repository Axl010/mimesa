<?php
    include("../../controladores/crud_conductores.php");
    include("../header.php");
?>
<section class="content">
    <div class="container">
        <form id="form" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            <div class="row justify-content-center">
                <div class="col-lg-9 col-md-12 col-sm-12 mb-3">
                    <div class="d-flex align-items-center"> 
                        <a href="vista_conductores.php" class="button-back">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <h2 class="h4 ml-1">Agregar Conductor</h2>
                    </div>
                </div>
                <div class="col-lg-6 col-md-8 col-sm-12 mb-3">
                    <div class="card"> 
                        <div class="card-body">
                            <div class="form-group">
                                <label for="nombre" class="form-label">Nombre *</label>
                                <input type="text" class="form-control form-control-sm alfaguion" name="nombre" id="nombre" tabindex="1" required/>
                            </div>
                            <div class="form-group">
                                <label for="cedula" class="form-label">Cédula *</label>
                                <input type="text" class="form-control form-control-sm numeros" name="cedula" id="cedula" tabindex="2" required/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for=estado" class="form-label bold">Estado</label>
                                <select class="form-select form-select-sm" id=estado" name=estado" tabindex="3">
                                    <option value="activo" selected>Activo</option>
                                    <option value="inactivo">Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-row-reverse mt-4">
                        <input type="submit" class="btn btn-primary btn-sm btn-danger" id="habilitar" name="agregar_conductor" value="Guardar" tabindex="4" disabled>
                        <a href="vista_conductores.php" role="button" class="btn btn-secondary btn-sm btn-reset  mr-2" tabindex="5">Cancelar</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
<script src="../../js/habilitar_boton.js"></script> 
<script src="../../js/validacion_inputs.js"></script> 
<?php include("../footer.php"); ?>
