document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('form');
    const btnGuardar = document.getElementById('habilitar');
    const inputs = form.querySelectorAll('input, select, textarea');

    // Guarda una referencia de los valores iniciales
    const originalData = Array.from(inputs).map(input => ({ name: input.name, value: input.value || "" }));

    // Verifica si hay cambios en los campos
    function checkChanges() {
        let isChanged = false;

        inputs.forEach(input => {
            const original = originalData.find(item => item.name === input.name)?.value || "";
            if (input.type === "file") {
                if (input.files.length > 0) isChanged = true;
            } else if (original !== input.value) {
                isChanged = true;
            }
        });

        btnGuardar.disabled = !isChanged; // Habilitar si hay cambios, deshabilitar si no hay cambios
    }

    // Agrega eventos de escucha a los campos
    inputs.forEach(input => {
        input.addEventListener('input', checkChanges);
        input.addEventListener('change', checkChanges);
    });
});

function previewImage(event) {
    const imgPreview = document.getElementById('imgPreview');
    imgPreview.src = URL.createObjectURL(event.target.files[0]);
}