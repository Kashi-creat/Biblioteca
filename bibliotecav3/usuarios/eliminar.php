<?php 
include '../includes/conexion.php'; 
include '../includes/login_check.php';
include '../includes/rol_check.php';
requireRole(['docente']);

$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    // Nota: Es crucial asegurarse de que no haya préstamos activos
    // asociados a este usuario antes de eliminarlo, o que la DB
    // maneje las restricciones (ON DELETE CASCADE).

    // Se asume que las FK de tu DB no lo impiden, o que están
    // configuradas para un borrado en cascada (CASCADE).
    if ($conn->query("DELETE FROM usuario WHERE id_usuario=$id")) {
        // Redirige al index del módulo usando el router principal
        header("Location: ../index.php?view=usuarios&msg=usuario_eliminado");
        exit;
    } else {
        echo "❌ Error al eliminar el usuario: " . $conn->error;
    }
} else {
    header("Location: ../index.php?view=usuarios");
}
?>