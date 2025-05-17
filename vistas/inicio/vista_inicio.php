<?php
    include("../../controladores/crud_inicio.php");
    include("../header.php"); 
?>
<section class="content-header container mb-3">
    <div class="col-12 mx-auto">
        <div class="row d-flex align-items-center">
            <div class="col-lg-10 col-md-10 col-sm-7 col-7 text-start">
                <h2 class="h3">Inicio</h2>
            </div>
        </div>
    </div>  
</section>

<div class="container">
    <!-- Primera fila de tarjetas -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-4 col-sm-6 mb-3">
            <a href="../../vistas/productos/vista_productos.php" class="text-decoration-none">
                <div class="card h-100 shadow-sm hover-shadow">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="icon-box bg-primary bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-box fa-2x text-primary"></i>
                                </div>
                                <h6 class="card-title mb-0 text-muted ms-3">Productos Activos</h6>
                            </div>
                            <h4 class="mb-0 fw-bold" id="totalProductos">
                                <?php echo $totalProductos; ?>
                            </h4>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-4 col-md-4 col-sm-6 mb-3">
            <a href="../../vistas/transferencias/vista_completados.php" class="text-decoration-none">
                <div class="card h-100 shadow-sm hover-shadow">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="icon-box bg-success bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-exchange-alt fa-2x text-success"></i>
                                </div>
                                <h6 class="card-title mb-0 text-muted ms-3">Pedidos Completados</h6>
                            </div>
                            <h4 class="mb-0 fw-bold" id="totalTransferencias">
                                <?php echo $totalTransferencias; ?>
                            </h4>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-4 col-md-4 col-sm-6 mb-3">
            <a href="../../vistas/conductores/vista_conductores.php" class="text-decoration-none">
                <div class="card h-100 shadow-sm hover-shadow">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="icon-box bg-info bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-user-tie fa-2x text-info"></i>
                                </div>
                                <h6 class="card-title mb-0 text-muted ms-3">Conductores</h6>
                            </div>
                            <h4 class="mb-0 fw-bold" id="totalConductores">
                                <?php echo $totalConductores; ?>
                            </h4>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Segunda fila de tarjetas -->
    <div class="row">
        <div class="col-xl-6 col-md-6 col-sm-6 mb-3">
            <a href="../../vistas/clientes/vista_clientes.php" class="text-decoration-none">
                <div class="card h-100 shadow-sm hover-shadow">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="icon-box bg-warning bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-users fa-2x text-warning"></i>
                                </div>
                                <h6 class="card-title mb-0 text-muted ms-3">Clientes</h6>
                            </div>
                            <h4 class="mb-0 fw-bold" id="totalClientes">
                                <?php echo $totalClientes; ?>
                            </h4>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-6 col-md-6 col-sm-6 mb-3">
            <a href="../../vistas/usuarios/vista_usuarios.php" class="text-decoration-none">
                <div class="card h-100 shadow-sm hover-shadow">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="icon-box bg-danger bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-user-shield fa-2x text-danger"></i>
                                </div>
                                <h6 class="card-title mb-0 text-muted ms-3">Usuarios</h6>
                            </div>
                            <h4 class="mb-0 fw-bold" id="totalUsuarios">
                                <?php echo $totalUsuarios; ?>
                            </h4>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<style>
.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    transition: all 0.3s ease;
}
.icon-box {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.card {
    border: none;
    border-radius: 10px;
    transition: all 0.3s ease;
}
a:hover {
    color: inherit;
}
</style>

<?php include("../footer.php"); ?>
