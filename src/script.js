document.addEventListener('DOMContentLoaded', () => {

    // UI
    const AppUI = {
        init() {
            this.iniciarPreloader();
            this.iniciarAlertaBorrado();
        },

        iniciarPreloader() {
            window.addEventListener('load', () => {
                setTimeout(() => document.body.classList.add('loaded'), 200);
            });
        },

        iniciarAlertaBorrado() {
            const btnBorrarCuenta = document.getElementById('btnBorrarCuenta');
            if (!btnBorrarCuenta) return; 

            btnBorrarCuenta.addEventListener('click', () => {
                const mensaje = '¡PELIGRO! ¿Estás seguro de que quieres eliminar tu cuenta? Esta acción es irreversible y borrará todas tus tareas para siempre.';
                if (confirm(mensaje)) {
                    window.location.href = 'borrar_cuenta.php';
                }
            });
        }
    };

    // MODALES
    const ModalManager = {
        init() {
            this.iniciarModalDescripcion();
            this.iniciarModalesSistema();
        },

        // Modal para leer las tareas largas
        iniciarModalDescripcion() {
            const modalOverlay = document.getElementById('descModal');
            if (!modalOverlay) return;

            const modalBody = document.getElementById('modalBodyText');
            const modalTitle = document.getElementById('modalTitleText');

            // EVENT DELEGATION 
            document.body.addEventListener('click', (evento) => {
                if (evento.target.classList.contains('btn-ver-mas')) {
                    modalTitle.textContent = evento.target.getAttribute('data-titulo');
                    modalBody.textContent = evento.target.getAttribute('data-desc');
                    modalOverlay.classList.add('active');
                }
            });

            this.vincularCierre(modalOverlay);
        },

        // Modales automáticos (Bienvenida y Despedida)
        iniciarModalesSistema() {
            const modales = ['welcomeModal', 'goodbyeModal'];

            modales.forEach(id => {
                const modal = document.getElementById(id);
                if (!modal) return;

                // Buscamos el botón principal dentro del modal
                const btnAccion = modal.querySelector('.btn-primary');
                if (btnAccion) {
                    btnAccion.addEventListener('click', () => {
                        modal.classList.remove('active');

                        // Si es el de despedida, limpiamos la URL
                        if (id === 'goodbyeModal') {
                            window.history.replaceState({}, document.title, "login.php");
                        }
                    });
                }
            });
        },

        // Utilidad reutilizable para cerrar modales (con la 'X' o clic fuera)
        vincularCierre(modal) {
            const btnClose = modal.querySelector('.modal-close');

            if (btnClose) {
                btnClose.addEventListener('click', () => modal.classList.remove('active'));
            }

            modal.addEventListener('click', (evento) => {
                if (evento.target === modal) modal.classList.remove('active');
            });
        }
    };

    // Inicio de la aplicacion;
    AppUI.init();
    ModalManager.init();

});