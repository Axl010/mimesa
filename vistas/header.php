<?php
    // Incluir el verificador de sesión
    include($_SERVER['DOCUMENT_ROOT'] . "/mimesa/controladores/verificar_sesion.php");
    $current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
    <html lang="es">
        <head>
            <link rel="shortcut icon" href="../../img/logo.png" />
            <meta charset="utf-8">
            <meta name="author" content="">
            <meta name="description" content="Aplicación web para la gestión de inventario y transferencias de productos.">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <title>MIMESA</title> 

            <!-- Estilo de Plantilla -->
            <link rel="stylesheet" href="../../css/sb-admin-2.css">
            <!-- Style Bootstrap-->
            <link rel="stylesheet" href="../../plugins/bootstrap/css/bootstrap.css.map">
            <!-- Style fontawesome-->
            <link rel="stylesheet" type="text/css" href="../../plugins/fontawesome/css/all.min.css">
            <!-- Style datatable -->
            <link rel="stylesheet" href="../../plugins/datatable/bootstrap.min.css">
            <link rel="stylesheet" href="../../plugins/datatable/dataTables.bootstrap5.min.css">
            <link rel="stylesheet" href="../../plugins/datatable/responsive.bootstrap5.min.css">
            <!-- Personal Style -->
            <link rel="stylesheet" href="../../css/pedido_style.css">
            <link rel="stylesheet" href="../../css/style.css">
        </head>
        <body id="page-top">
            <!-- Page Wrapper -->
            <div id="wrapper">
                <!-- Sidebar -->
                <ul class="navbar-nav bg-dark sidebar sidebar-dark accordion" id="accordionSidebar">
                    <!-- Sidebar - Brand -->
                    <div class="sidebar-brand d-flex align-items-center justify-content-center">
                        <div class="sidebar-brand-text">
                            <img src="../../img/logo-mimesa-ovalado.png" class="logo-sidebar" alt="Logo">
                        </div>
                    </div>

                    <!-- Divider -->
                    <hr class="sidebar-divider my-0">

                    <!-- Nav Item - Dashboard -->
                    <li class="nav-item <?php echo ($current_page == 'vista_inicio.php') ? 'active' : ''; ?>">
                        <a class="nav-link" href="../../vistas/inicio/vista_inicio.php">
                            <i class="fas fa-home">
                                <span class="ml-2">Inicio</span>
                            </i>
                        </a>
                    </li>

                    <hr class="sidebar-divider my-0">
                    <li class="nav-item <?php echo ($current_page == 'vista_productos.php') ? 'active' : ''; ?>">
                        <a class="nav-link" href="../../vistas/productos/vista_productos.php">
                            <i class="fa fa-box">
                                <span class="ml-2">Productos</span>
                            </i>
                        </a>
                    </li>

                    <!-- Divider -->
                    <hr class="sidebar-divider my-0">
                    <li class="nav-item <?php echo (in_array($current_page, ['vista_transferencias.php', 'crear_transferencia.php'])) ? 'active' : ''; ?>">
                        <a class="nav-link" href="../../vistas/transferencias/vista_transferencias.php">
                            <i class="fa fa-utensils">
                                <span class="ml-2">Pedidos</span>
                            </i>
                        </a>
                    </li>

                    <hr class="sidebar-divider my-0">
                    <li class="nav-item <?php echo (in_array($current_page, ['vista_completados.php'])) ? 'active' : ''; ?>">
                        <a class="nav-link" href="../../vistas/transferencias/vista_completados.php">
                            <i class="fa fa-file-invoice">
                                <span class="ml-2">Facturados</span>
                            </i>
                        </a>
                    </li>
                    
                    <hr class="sidebar-divider my-0">
                    <li class="nav-item <?php echo ($current_page == 'vista_conductores.php') ? 'active' : ''; ?>">
                        <a class="nav-link" href="../../vistas/conductores/vista_conductores.php">
                            <i class="fa fa-user-tie">
                                <span class="ml-2">Conductores</span>
                            </i>
                        </a>
                    </li>

                    <hr class="sidebar-divider my-0">
                    <li class="nav-item <?php echo ($current_page == 'vista_transporte.php') ? 'active' : ''; ?>">
                        <a class="nav-link" href="../../vistas/transporte/vista_transporte.php">
                            <i class="fa fa-truck">
                                <span class="ml-2">Transporte</span>
                            </i>
                        </a>
                    </li>

                    <hr class="sidebar-divider my-0">
                    <li class="nav-item <?php echo ($current_page == 'vista_clientes.php') ? 'active' : ''; ?>">
                        <a class="nav-link" href="../../vistas/clientes/vista_clientes.php">
                            <i class="fa fa-users">
                                <span class="ml-2">Clientes</span>
                            </i>
                        </a>
                    </li>
                    <hr class="sidebar-divider my-0">
                    <li class="nav-item <?php echo ($current_page == 'vista_usuarios.php') ? 'active' : ''; ?>">
                        <a class="nav-link" href="../../vistas/usuarios/vista_usuarios.php">
                            <i class="fa fa-user-shield">
                                <span class="ml-2">Usuarios</span>
                            </i>
                        </a>
                    </li>
                    <!-- Sidebar Toggler (Sidebar) -->
                    <div class="text-center d-none d-md-inline mt-3">
                        <button class="rounded-circle border-0" id="sidebarToggle"></button>
                    </div>
                </ul>
                <!-- End of Sidebar -->

                <!-- Content Wrapper -->
                <div id="content-wrapper" class="d-flex flex-column">
                    <!-- Main Content -->
                    <div id="content">
                        <!-- Topbar -->
                        <nav class="navbar navbar-expand navbar-light bg-navbar topbar mb-4 static-top shadow">
                            <!--Alternar barra lateral-->
                            <button id="sidebarToggleTop" class="btn btn-link d-md-none ml-0">
                                <i class="fa fa-bars text-primary"></i>
                            </button>
                            <!-- Topbar Navbar -->
                            <ul class="navbar-nav ml-auto">
                                <!-- Nav Item - User Information -->
                                <li class="nav-item dropdown no-arrow mr-3">
                                    <a class="nav-link dropdown-toggle user-menu no-hover" href="#" id="userDropdown" role="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <img class="img-profile rounded-circle"
                                        src="<?= !empty($_SESSION['foto_usuario']) ? $_SESSION['foto_usuario'] : '../../photos/usuarios/default_user.png' ?>">
                                        <span class="ml-2 d-none d-lg-inline text-usuario font-weight-bold small mayuscula"><?= $_SESSION['nombre']; ?><i class="fa fa-angle-down ml-2"></i></span>
                                    </a>
                                    <!-- Dropdown - User Information -->
                                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                        aria-labelledby="userDropdown">
                                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                            <i class="fas fa-sign-out-alt mr-2 text-gray-500"></i>
                                            Salir
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </nav>
                        <!-- End of Topbar -->