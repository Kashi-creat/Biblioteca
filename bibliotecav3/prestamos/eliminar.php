<?php 
include '../includes/conexion.php'; 
include '../includes/login_check.php';
include '../includes/rol_check.php';
requireRole(['docente']);

$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    // Buscar id_libro para revertir el estado
    $res = $conn->query("SELECT id_libro FROM prestamo WHERE id_prestamo=$id");
    $row = $res->fetch_assoc();
    $id_libro = $row['id_libro'];
    
    // Eliminar devoluciones relacionadas (por si acaso)
    $conn->query("DELETE FROM devolucion WHERE id_prestamo=$id");
    
    // Eliminar préstamo
    if ($conn->query("DELETE FROM prestamo WHERE id_prestamo=$id")) {
        // Revertir el estado del libro (se asume que si se elimina el préstamo,
        // fue un error de registro y el libro debe volver a "disponible").
        $conn->query("UPDATE libro SET estado='disponible' WHERE id_libro=$id_libro");
    }

    // Redirige al index del módulo usando el router principal
    header("Location: ../index.php?view=prestamos&msg=prestamo_eliminado");
    exit;
} else {
    header("Location: ../index.php?view=prestamos");
}
?>