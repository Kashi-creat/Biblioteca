<?php 
include '../includes/conexion.php'; 
include '../includes/login_check.php';
include '../includes/rol_check.php';
requireRole(['docente']);

// 1. Deshabilitar FK checks temporalmente
$conn->query("SET FOREIGN_KEY_CHECKS = 0;");

// 2. Eliminar TODAS las tablas de relación (la tabla de devolucion fue manejada en el index)
$conn->query("TRUNCATE TABLE devolucion;");
$conn->query("TRUNCATE TABLE prestamo;");
$conn->query("TRUNCATE TABLE baja;");
$conn->query("TRUNCATE TABLE libro_autor;");

// 3. Eliminar todos los libros y reiniciar el AUTO_INCREMENT
if ($conn->query("TRUNCATE TABLE libro")) {
    // Se incluye la eliminación de autores/editoriales si se desea hacer limpieza total
    // $conn->query("TRUNCATE TABLE autor;");
    // $conn->query("TRUNCATE TABLE editorial;");
    // $conn->query("TRUNCATE TABLE area_tematica;");
    
    // 4. Habilitar FK checks
    $conn->query("SET FOREIGN_KEY_CHECKS = 1;");
    
    // Redirige al index del módulo usando el router principal
    header("Location: ../index.php?view=libros&msg=libro_eliminado"); // Usamos el mismo mensaje para notificar la limpieza
    exit;
} else {
    $conn->query("SET FOREIGN_KEY_CHECKS = 1;");
    echo "❌ Error al eliminar todos los libros: " . $conn->error;
}
?>