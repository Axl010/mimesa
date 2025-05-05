<?php
    include("../../controladores/crud_clientes.php");
    include("../header.php");
?>

<!-- Main content -->
<section class="content">   
    <div class="container">
        <form id="form" method="POST" class="needs-validation" novalidate>
            <div class="row justify-content-center">
                <div class="col-lg-9 col-md-12 col-sm-12 mb-3">
                    <div class="d-flex align-items-center"> 
                        <a href="vista_clientes.php" class="button-back">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <h2 class="h4 ml-1">Agregar Cliente</h2>
                    </div>
                </div>
                <div class="col-lg-6 col-md-8 col-sm-12 mb-3">
                    <div class="card"> 
                        <div class="card-body">
                            <h2 class="h5">Datos del Cliente</h2>
                            <div class="form-group">
                                <label for="nombre" class="form-label">Nombre *</label>
                                <input type="text" class="form-control form-control-sm" name="nombre" id="nombre" tabindex="1" required/>
                            </div>
                            <div class="form-group">
                                <label for="razon_social" class="form-label">Razón Social</label>
                                <input type="text" class="form-control form-control-sm" name="razon_social" id="razon_social" tabindex="2"/>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="documento_tipo" class="form-label">Tipo Doc. *</label>
                                        <select class="form-select form-select-sm" name="documento_tipo" id="documento_tipo" tabindex="3" required>
                                            <option value="RIF">RIF</option>
                                            <option value="CI">CI</option>
                                            <option value="Pasaporte">Pasaporte</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="documento_numero" class="form-label">Número Doc. *</label>
                                        <input type="text" class="form-control form-control-sm alfaguion" name="documento_numero" id="documento_numero" placeholder="J-12345678-9" tabindex="4" required/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="direccion" class="form-label">Dirección *</label>
                                <textarea class="form-control form-control-sm" name="direccion" id="direccion" rows="3" tabindex="5" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="region" class="form-label">Región/Estado *</label>
                                <input type="text" class="form-control form-control-sm letras" name="region" id="region" tabindex="6" required/>
                            </div>
                        </div><!--/.card body -->
                    </div>
                    <div class="card mt-3"> 
                        <div class="card-body">
                            <h2 class="h5">Datos de Contacto</h2>
                            <div class="form-group">
                                <label for="telefono" class="form-label">Teléfono Principal *</label>
                                <input type="text" class="form-control form-control-sm telefono" name="telefono" id="telefono" placeholder="04247658923" tabindex="7" required/>
                            </div>
                            <div class="form-group">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control form-control-sm" name="email" id="email" placeholder="ejemplo@dominio.com" tabindex="8"/>
                            </div>
                        </div><!--/.card body -->
                    </div>
                </div><!--/.col -->

                <div class="col-lg-3 col-md-4 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="tipo_cliente" class="form-label">Tipo de Cliente</label>
                                <select class="form-select form-select-sm" id="tipo_cliente" name="tipo_cliente" tabindex="9">
                                    <option value="regular" selected>Regular</option>
                                    <option value="preferencial">Preferencial</option>
                                    <option value="mayorista">Mayorista</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="estado" class="form-label bold">Estado</label>
                                <select class="form-select form-select-sm" id="estado" name="estado" tabindex="10">
                                    <option value="activo" selected>Activo</option>
                                    <option value="inactivo">Inactivo</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="notas" class="form-label">Notas</label>
                                <textarea class="form-control form-control-sm" name="notas" id="notas" rows="3" tabindex="11"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="fecha_registro" class="form-label">Fecha Registro</label>
                                <input type="date" class="form-control form-control-sm" name="fecha_registro" id="fecha_registro" value="<?php echo date('Y-m-d'); ?>" tabindex="12" readonly/>
                                <small class="text-muted">Se establece automáticamente</small>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-row-reverse mt-4">
                        <input type="submit" class="btn btn-primary btn-sm btn-confirmar" id="habilitar" name="crear_cliente" value="Guardar" tabindex="13" disabled>
                        <a href="vista_clientes.php" role="button" class="btn btn-secondary btn-sm btn-reset mr-2" tabindex="14">Cancelar</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section><!-- /.content -->

<script src="../../js/habilitar_boton.js"></script>
<script src="../../js/validacion_inputs.js"></script>
<?php include("../footer.php")?>
