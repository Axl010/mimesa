        </div>
            <!-- Footer -->
            <footer class="sticky-footer bg-footer mt-4">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <img src="../../img/logo.png" alt="Logo" class="mx-auto" style="width:150px; padding:0; margin-top:8px">
                    </div>
                </div>
            </footer>
        </div> <!-- End of Content Wrapper -->
    </div> <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-sign-out"> Cerrar Sesion</i></h5>
                    <button class="btn btn-danger" type="button" data-dismiss="modal">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="modal-body">Estas seguro de cerrar la sesión?</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-danger" href="../../controladores/logout.php">Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="../../plugins/jquery/jquery.min.js"></script>
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- DataTable JS/Responsive -->
    <script src="../../plugins/datatable/jquery-3.5.1.js"></script>
    <script src="../../plugins/datatable/jquery.dataTables.min.js"></script>
    <script src="../../plugins/datatable/dataTables.bootstrap5.min.js"></script>
    <script src="../../plugins/datatable/dataTables.responsive.min.js"></script>
    <script src="../../plugins/datatable/responsive.bootstrap5.min.js"></script>
    <!-- Bootstrap - Validación de Formularios -->
    <script src="../../js/validacion.js"></script>
    <!--Sweet Alert-->
    <script src="../../plugins/sweetalert2/sweetalert2.all.min.js"></script>
    <!-- JS Personalizado -->
    <script src="../../js/dataTable.js"></script>
    <!-- <script src="../../js/estados.js"></script> -->

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
                } elseif(strpos($_GET['mensaje'], 'existe') !== false){
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