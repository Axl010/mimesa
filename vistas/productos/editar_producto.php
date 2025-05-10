<?php
    include_once("../../controladores/crud_productos.php");
    include("../header.php");
?>
    <!-- Main content -->
    <section class="content">
        <div class="container">
            <form id="form" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                <div class="row justify-content-center">
                    <div class="col-lg-9 col-md-12 col-sm-12 mb-3"> 
                        <div class="d-flex align-items-center flex-wrap gap-1"> 
                            <a href="vista_productos.php" class="button-back">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                            <div class="d-flex align-items-center">
                                <h2 class="h4 mb-1 align-middle"><?= htmlspecialchars($producto['nombre']) ?></h2>
                                <span class="<?= $producto['estado'] == 'activo' ? 'estado-activo' : 'estado-inactivo' ?> general text-center px-2 py-1 ml-2 align-middle">
                                    <?= htmlspecialchars($producto['estado']) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-8 col-sm-12 mb-3">
                        <div class="card"> 
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="sku" class="form-label">SKU</label>
                                    <input type="text" class="form-control form-control-sm" name="sku" id="sku" tabindex="1" value="<?= htmlspecialchars($producto['sku'] ?? '') ?>" placeholder="Código único de producto"/>
                                </div>
                                <div class="form-group">
                                    <label for="producto" class="form-label">Título</label>
                                    <input type="text" class="form-control form-control-sm alfaguion" name="nombre" id="producto" tabindex="2" value="<?= htmlspecialchars($producto['nombre']) ?>" required/>
                                </div>
                                <div class="form-group">
                                    <label for="descripcion" class="form-label">Descripción *</label>
                                    <textarea class="form-control form-control-sm" name="descripcion" id="descripcion" rows="3" tabindex="4"><?= htmlspecialchars($producto['descripcion']) ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="peso" class="form-label">Peso (kg) *</label>
                                    <input type="text" class="form-control form-control-sm decimal" name="peso" id="peso" value="<?= htmlspecialchars($producto['peso']) ?>" tabindex="5" placeholder="0.00" required/>
                                </div>
                                <div class="form-group">
                                    <label for="foto" class="form-label">Foto</label>
                                    <input type="file" class="form-control form-control-sm" name="foto" id="foto" accept="image/*" tabindex="6">
                                </div>
                                <!-- Vista previa de la imagen -->
                                <div class="form-group vista-img">
                                    <label class="form-label">Vista previa</label>
                                    <!-- Muestra la imagen de la base de datos o la predeterminada -->
                                    <img id="imgPreview" class="mx-auto d-block border border-secondary text-center rounded" src="<?= !empty($producto['foto']) ? htmlspecialchars($producto['foto']) : '../../photos/productos/default_producto.jpg' ?>" alt="Selecciona una Imagen" />
                                </div>
                                <!-- <div class="form-group">
                                    <label for="id_categoria" class="form-label">Categoría</label>
                                    <select class="form-select form-select-sm buscador" id="id_categoria" name="id_categoria" tabindex="4" data-placeholder="Selecciona una categoría">
                                        <option value="" disabled selected></option>
                                        <?php foreach($lista_categorias as $categoria){?>
                                        <option value="<?= $categoria['id_categoria'];?>"<?= $categoria['id_categoria'] == $producto['id_categoria'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($categoria['titulo']) ?>
                                        </option>
                                        <?php }?>
                                    </select>
                                </div> -->
                                <div class="form-group">
                                    <label for="stock" class="form-label">Cantidad en Stock *</label>
                                    <input type="number" class="form-control form-control-sm" name="stock" id="stock" value="<?= htmlspecialchars($producto['stock']) ?>" min="0" placeholder="0" required/>
                                </div>
                            </div><!--/.card body -->
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-4 col-sm-12">
                        <div class="card"> 
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="estado" class="form-label">Estado</label>
                                    <select class="form-select form-select-sm" id="estado" name="estado" tabindex="7">
                                        <option value="activo" <?= $producto['estado'] == 'activo' ? 'selected' : '' ?>>Activo</option>
                                        <option value="inactivo" <?= $producto['estado'] == 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-row-reverse mt-4">
                            <input type="submit" class="btn btn-primary btn-sm btn-confirmar" id="habilitar" name="editar_producto" value="guardar" tabindex="8" disabled>
                            <a href="vista_productos.php" role="button" class="btn btn-secondary btn-sm btn-reset mr-2" tabindex="9">Cancelar</a>
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