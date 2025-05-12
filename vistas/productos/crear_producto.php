<?php
    include("../../controladores/crud_productos.php");
    include("../header.php");
?>
<!-- Main content -->
<section class="content">   
    <div class="container">
        <form id="form" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            <div class="row justify-content-center">
                <div class="col-lg-9 col-md-12 col-sm-12 mb-3">
                    <div class="d-flex align-items-center"> 
                        <a href="vista_productos.php" class="button-back">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <h2 class="h4 ml-1">Agregar Producto</h2>
                    </div>
                </div>
                <div class="col-lg-6 col-md-8 col-sm-12 mb-3">
                    <div class="card"> 
                        <div class="card-body">
                            <div class="form-group">
                                <label for="sku" class="form-label">Codigo SKU</label>
                                <input type="text" class="form-control form-control-sm" name="sku" id="sku" tabindex="1" placeholder="Código único de producto"/>
                            </div>
                            <div class="form-group">
                                <label for="producto" class="form-label">Título *</label>
                                <input type="text" class="form-control form-control-sm alfaguion" name="nombre" id="producto" tabindex="2" required/>
                            </div>
                            <div class="form-group">
                                <label for="descripcion" class="form-label">Descripción *</label>
                                <textarea class="form-control form-control-sm" name="descripcion" id="descripcion" rows="3" tabindex="4"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="peso" class="form-label">Peso de Caja (kg) *</label>
                                <input type="text" class="form-control form-control-sm decimal" name="peso" id="peso" tabindex="5" placeholder="0.00" required/>
                            </div>
                            <div class="form-group">
                                <label for="stock" class="form-label">Cantidad en Stock *</label>
                                <input type="number" class="form-control form-control-sm" name="stock" id="stock" tabindex="6" min="0" placeholder="0" required/>
                            </div>
                            <div class="form-group">
                                <label for="cantidad_por_paleta" class="form-label">Cantidad por Paleta *</label>
                                <input type="number" class="form-control form-control-sm" name="cantidad_por_paleta" id="cantidad_por_paleta" tabindex="7" min="1" placeholder="1" required/>
                            </div>
                            <div class="form-group">
                                <label for="foto" class="form-label">Foto</label>
                                <input type="file" class="form-control form-control-sm" name="foto" id="foto" accept="image/*" tabindex="8">
                            </div>
                            <!-- Vista previa de la imagen -->
                            <div class="form-group vista-img">
                                <label class="form-label">Vista previa</label>
                                <img id="imgPreview" class="mx-auto d-block text-center" src="../../photos/productos/default_producto.jpg" alt="Selecciona una Imagen"/>                                                              
                            </div>
                            <!-- <div class="form-group">
                                <label for="id_categoria" class="form-label">Categoría</label>
                                <select class="form-select form-select-sm buscador" id="id_categoria" name="id_categoria" tabindex="5" data-placeholder="Selecciona una categoría">
                                    <option value="" disabled selected></option>
                                    <?php foreach($lista_categorias as $categoria){?>
                                        <option value="<?= $categoria['id_categoria'];?>"><?= $categoria['titulo']?></option>
                                        <?php }?>
                                    </select>
                                </div> -->
                            </div><!--/.card body -->
                        </div>    
                    </div><!--/.col -->
                    
                    <div class="col-lg-3 col-md-4 col-sm-12">
                        <div class="card">
                            <div class="card-body">
                            <div class="form-group">
                                <label for="estado" class="form-label bold">Estado</label>
                                <select class="form-select form-select-sm" id="estado" name="estado" tabindex="9">
                                    <option value="activo" selected>Activo</option>
                                    <option value="inactivo">Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-row-reverse mt-4">
                        <input type="submit" class="btn btn-primary btn-sm btn-confirmar" id="habilitar" name="agregar_producto" value="Guardar" tabindex="9" disabled>
                        <a href="view_productos.php" role="button" class="btn btn-secondary btn-sm btn-reset  mr-2" tabindex="10">Cancelar</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section><!-- /.content -->
<script src="../../js/habilitar_boton.js"></script>
<script src="../../js/validacion_inputs.js"></script> 
<script>
    // Vista previa de la imagen
    document.getElementById('foto').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imgPreview').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
</script>
<?php include("../footer.php")?>