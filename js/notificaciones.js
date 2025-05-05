/**
 * Notificaciones.js - Sistema de notificaciones con SweetAlert2
 * Proporciona funciones para mostrar diferentes tipos de notificaciones
 */

// Función para mostrar notificaciones de éxito
function mostrarExito(mensaje) {
    Swal.fire({
        title: '',
        text: mensaje,
        icon: 'success',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        customClass: {
            popup: 'swal2-show',
            container: 'swal2-container'
        }
    });
}

// Función para mostrar notificaciones de error
function mostrarError(mensaje) {
    Swal.fire({
        title: '',
        text: mensaje,
        icon: 'error',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        customClass: {
            popup: 'swal2-show',
            container: 'swal2-container'
        }
    });
}

// Función para mostrar notificaciones de advertencia
function mostrarAdvertencia(mensaje) {
    Swal.fire({
        title: '',
        text: mensaje,
        icon: 'warning',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        customClass: {
            popup: 'swal2-show',
            container: 'swal2-container'
        }
    });
}

// Función para mostrar notificaciones informativas
function mostrarInfo(mensaje) {
    Swal.fire({
        title: '',
        text: mensaje,
        icon: 'info',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        customClass: {
            popup: 'swal2-show',
            container: 'swal2-container'
        }
    });
}

// Función para confirmar acciones
function confirmar(mensaje, callbackSi, callbackNo) {
    Swal.fire({
        text: mensaje,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí',
        cancelButtonText: 'No'
    }).then((result) => {
        if (result.isConfirmed && typeof callbackSi === 'function') {
            callbackSi();
        } else if (typeof callbackNo === 'function') {
            callbackNo();
        }
    });
} 