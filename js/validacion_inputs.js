(function () {
    "use strict";

    document.addEventListener("DOMContentLoaded", function () {
        // Solamente Letras(con espacios y acentos)
        document.querySelectorAll(".letras").forEach(function (input) {
            input.addEventListener("input", function () {
                this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, "");
            });
        });

        // Solamente Números
        document.querySelectorAll(".numeros").forEach(function (input) {
            input.addEventListener("input", function () {
                this.value = this.value.replace(/[^0-9]/g, "");
            });
        });

        // Números con punto decimal
        document.querySelectorAll(".decimal").forEach(function (input) {
            input.addEventListener("input", function () {
                this.value = this.value.replace(/[^0-9.]/g, "");
                // Solo permitir un punto decimal
                const parts = this.value.split(".");
                if(parts.length > 2) {
                    this.value = parts[0] + "." + parts.slice(1).join("");
                }
            });
        });

        // Letras y Números
        document.querySelectorAll(".alfanumerico").forEach(function (input) {
            input.addEventListener("input", function () {
                this.value = this.value.replace(/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s]/g, "");
            });
        });

        // Letras, Números y -
        document.querySelectorAll(".alfaguion").forEach(function (input) {
            input.addEventListener("input", function () {
                this.value = this.value.replace(/[^a-zA-Z0-9-áéíóúÁÉÍÓÚñÑ\s]/g, "");
            });
        });

        const prefijosValidos = ["0412", "0414", "0424", "0416", "0426"];

        // Teléfono
        document.querySelectorAll(".telefono").forEach(function (input) {
            input.addEventListener("input", function () {
                this.value = this.value.replace(/[^0-9]/g, "");

                if(this.value.length > 11) {
                    this.value = this.value.slice(0, 11);
                }
            });

            input.addEventListener("blur", function () {
                const numero = this.value;
                const prefijo = numero.slice(0, 4);
                if (numero.length !== 11 || !prefijosValidos.includes(prefijo)) {
                    this.setCustomValidity("Número telefónico inválido. Debe comenzar en 0412, 0414, 0424, 0416 o 0426 y tener 11 dígitos.");
                } else {
                    this.setCustomValidity("");
                }
            });
        });
    });
})();