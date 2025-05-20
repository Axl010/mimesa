<?php
    session_start();

    // Si el usuario ya está logueado, redirige al dashboard
    if(isset($_SESSION['id_usuario'])) {
        header("Location: vistas/productos/vista_productos.php");
        exit();
    }

    include("controladores/crud_login.php");
?>
<!doctype html>
<html>
    <head> 
        <meta charset="utf-8">
        <meta name="author" content="">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>PASANTIAS</title>

        <link rel="stylesheet" href="css/login-style.css"> 
        <!-- Style Bootstrap-->
        <link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.css.map">
    </head>
    <body>
        <div class="container-login">
            <div class="wrap-login">
                <form class="login-form validate-form" id="formlogin" method="POST">
                    <img class="logo" src="img/logo-mimesa.png">
                    <div class="wrap-input100" data-validate = "Usuario incorrecto">
                        <input class="input100" type="text" id="usuario" name="usuario" placeholder="Usuario" tabindex="1">
                        <span class="focus-efecto"></span>
                    </div>
                    
                    <div class="wrap-input100">
                        <input class="input100" type="password" id="password" name="password" autocomplete="off" placeholder="Contraseña" tabindex="2">
                        <span class="focus-efecto"></span>
                    </div>
                    <div class="container-login-form-btn mt-3 mb-3">
                        <div class="wrap-login-form-btn">
                            <div class="login-form-bgbtn"></div>
                            <button type="submit" name="sesion" class="login-form-btn" tabindex="3">INICIAR SESIÓN</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Bootstrap core JavaScript-->
        <script src="plugins/jquery/jquery.min.js"></script>
        <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!--Sweet Alert-->
        <script src="plugins/sweetalert2/sweetalert2.all.min.js"></script>

        <!-- Mensaje -->
        <?php                  
            //Verificar si se ha enviado un mensaje
            if(isset($_GET['mensaje'])){
                //verificar si es la primera vez que se envia un mensaje
                if(!isset($_SESSION['mensaje_mostrado']) || $_SESSION['mensaje_mostrado']) {
                    $_SESSION['mensaje_mostrado'] = $_GET['mensaje'];
                    $icono = "success"; // Por defecto
                    // Verificar el tipo de mensaje y asignar el icono correspondiente
                    if(strpos($_GET['mensaje'],'Error') !== false){
                        $icono = "error";
                    } elseif(strpos($_GET['mensaje'], 'Ingresa') !== false){
                        $icono = "warning";
                    } 
        ?>
            <script>
                Swal.fire({
                    icon:"<?php echo $icono; ?>", 
                    title:"<?php echo $_GET['mensaje']; ?>",
                    timer: 1500,
                    showConfirmButton: false,
                    customClass: {
                        popup: "style-swal"
                    }
                }).then(() => {
                    window.location.href = "<?php echo $_SERVER['PHP_SELF']; ?>";
                });
            </script> 
        <?php }}?>
    </body>
</html>