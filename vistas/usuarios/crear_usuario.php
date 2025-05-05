<?php
    include("../../controladores/crud_usuario.php");
    include("../header.php");
?>

<!-- Main content -->
<section class="content">   
    <div class="container">
        <form id="form" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            <div class="row justify-content-center">
                <div class="col-lg-9 col-md-12 col-sm-12 mb-3">
                    <div class="d-flex align-items-center"> 
                        <a href="vista_usuarios.php" class="button-back">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <h2 class="h4 ml-1">Agregar Usuario</h2>
                    </div>
                </div>
                <div class="col-lg-6 col-md-8 col-sm-12 mb-3">
                    <div class="card"> 
                        <div class="card-body">
                            <div class="form-group">
                                <label for="nombre" class="form-label">Nombre *</label>
                                <input type="text" class="form-control form-control-sm letras" name="nombre" id="nombre" tabindex="1" required/>
                            </div>
                            <div class="form-group">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control form-control-sm telefono" name="telefono" id="telefono" placeholder="04247658923" tabindex="2"/>
                            </div>
                            <div class="form-group">
                                <label for="foto" class="form-label">Foto</label>
                                <input type="file" class="form-control form-control-sm" name="foto" id="foto" accept="image/*" tabindex="3" onchange="previewImage(event)"/>
                            </div>
                            <!-- Vista previa de la imagen -->
                            <div class="form-group vista-img">
                                <label class="form-label">Vista previa</label>
                                <img id="imgPreview" class="mx-auto d-block text-center" src="" alt="Selecciona una Foto"/>                                                              
                            </div>
                        </div><!--/.card body -->
                    </div>    
                    <div class="card mt-3"> 
                        <div class="card-body">
                            <h2 class="h5">Datos de Autenticación</h2>
                            <div class="form-group">
                                <label for="usuario" class="form-label">Usuario *</label>
                                <input type="text" class="form-control form-control-sm alfanumerico" name="usuario" id="usuario" tabindex="4" required/>
                            </div>
                            <div class="form-group">
                                <label for="contrasena" class="form-label">Contraseña *</label>
                                <input type="password" class="form-control form-control-sm" name="contrasena" id="contrasena" tabindex="5" required/>
                            </div>
                        </div><!--/.card body -->
                    </div>
                </div><!--/.col -->

                <div class="col-lg-3 col-md-4 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="estado" class="form-label bold">Estado</label>
                                <select class="form-select form-select-sm" id="estado" name="estado" tabindex="7">
                                    <option value="activo" selected>Activo</option>
                                    <option value="inactivo">Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-row-reverse mt-4">
                        <input type="submit" class="btn btn-primary btn-sm btn-confirmar" id="habilitar" name="crear_usuario" value="Guardar" tabindex="8" disabled>
                        <a href="vista_usuarios.php" role="button" class="btn btn-secondary btn-sm btn-reset mr-2" tabindex="9">Cancelar</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section><!-- /.content -->

<script src="../../js/habilitar_boton.js"></script>
<?php include("../footer.php")?>