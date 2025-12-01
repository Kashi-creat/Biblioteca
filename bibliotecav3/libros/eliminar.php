<?php 
include '../includes/conexion.php'; 
include '../includes/login_check.php';
include '../includes/rol_check.php';
requireRole(['docente']);

$id_libro = intval($_GET['id'] ?? 0);

if ($id_libro > 0) {
    // 1. Eliminar devoluciones asociadas a préstamos de este libro
    // (Esto es más seguro que el código original que parecía eliminar TODAS las devoluciones)
    $conn->query("DELETE d FROM devolucion d JOIN prestamo p ON d.id_prestamo = p.id_prestamo WHERE p.id_libro=$id_libro");
    
    // 2. Eliminar préstamos asociados
    $conn->query("DELETE FROM prestamo WHERE id_libro=$id_libro");

    // 3. Eliminar bajas asociadas
    $conn->query("DELETE FROM baja WHERE id_libro=$id_libro");
    
    // 4. Eliminar relación libro_autor
    $conn->query("DELETE FROM libro_autor WHERE id_libro=$id_libro");

    // 5. Finalmente eliminar el libro
    if ($conn->query("DELETE FROM libro WHERE id_libro=$id_libro")) {
        // Redirige al index del módulo usando el router principal
        header("Location: ../index.php?view=libros&msg=libro_eliminado");
        exit;
    } else {
        echo "❌ Error al eliminar libro: " . $conn->error;
    }
} else {
    header("Location: ../index.php?view=libros");
}
?>