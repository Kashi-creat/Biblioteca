document.addEventListener('DOMContentLoaded', function() {
    // 1. Lógica para el Menú Lateral (Sidebar)
    const sidebar = document.getElementById('sidebar');
    const menuBtn = document.getElementById('menu-btn');

    if (menuBtn && sidebar) {
        menuBtn.addEventListener('click', function() {
            // Alterna la clase que oculta la sidebar en móvil.
            sidebar.classList.toggle('-translate-x-full');
            // Opcional: También podrías querer cambiar el icono del botón aquí.
        });
    }

    // 2. Lógica para el Menú de Usuario (Dropdown) - Mantenida para completar la funcionalidad
    const userMenuToggle = document.getElementById('user-menu-toggle');
    const userMenu = document.getElementById('user-menu');

    if (userMenuToggle && userMenu) {
        userMenuToggle.addEventListener('click', function() {
            userMenu.classList.toggle('hidden');
        });
        
        document.addEventListener('click', function(event) {
            if (!userMenuToggle.contains(event.target) && !userMenu.contains(event.target)) {
                userMenu.classList.add('hidden');
            }
        });
    }
    
    // 3. Manejo de mensajes flash
    const flashMessages = document.querySelectorAll('.fixed.top-4.right-4');
    flashMessages.forEach(msg => {
        setTimeout(() => {
            msg.style.opacity = '0';
            msg.style.transition = 'opacity 0.5s ease-out';
            setTimeout(() => msg.remove(), 500);
        }, 3000);
    });
});

// Función para la doble confirmación antes de eliminar TODO
function confirmarDoble() {
    // Primera confirmación: advertencia general
    let confirmacion1 = confirm("⚠️ ADVERTENCIA: Estás a punto de ELIMINAR TODOS los registros. ¿Estás seguro de continuar?");

    // Si la primera confirmación es aceptada (true)
    if (confirmacion1) {
        // Segunda confirmación: requiere más atención, quizás con un mensaje más fuerte
        let confirmacion2 = confirm("🚨 CONFIRMACIÓN FINAL: Esta acción es IRREVERSIBLE y vaciará la tabla. Presiona Aceptar para ELIMINAR DEFINITIVAMENTE.");
        
        // Si ambas son aceptadas, devuelve true y el navegador sigue el enlace (eliminar_todo.php)
        return confirmacion2;
    } 
    
    // Si la primera confirmación es cancelada, devuelve false e interrumpe la acción
    return false;
}