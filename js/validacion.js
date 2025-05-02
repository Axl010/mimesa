// Activar la validación personalizada de Bootstrap
(function () {
    'use strict';

    window.addEventListener('load', function () {
        // Selecciona todos los formularios a los que queremos aplicar la validación de Bootstrap
        var forms = document.getElementsByClassName('needs-validation');

        // Itera sobre los formularios y evita su envío si no son válidos
        Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

