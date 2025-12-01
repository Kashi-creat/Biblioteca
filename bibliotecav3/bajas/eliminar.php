<?php 
include '../includes/conexion.php'; 
include '../includes/login_check.php';
include '../includes/rol_check.php';
requireRole(['docente']);

$id_baja = intval($_GET['id'] ?? 0); // id de la baja

if ($id_baja > 0) {
    // Buscar libro asociado a esa baja
    $res = $conn->query("SELECT id_libro FROM baja WHERE id_baja=$id_baja");
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $id_libro = $row['id_libro'];

        // Eliminar baja
        if ($conn->query("DELETE FROM baja WHERE id_baja=$id_baja")) {
            // Volver a poner libro disponible
            $conn->query("UPDATE libro SET estado='disponible' WHERE id_libro=$id_libro");

            // Redirige al index del módulo usando el router principal
            header("Location: ../index.php?view=bajas&msg=baja_eliminada");
            exit;
        } else {
            // Error handling, not redirecting for simplicity
            echo "❌ Error al eliminar la baja: " . $conn->error;
        }
    } else {
        echo "❌ No se encontró la baja.";
    }
} else {
    header("Location: ../index.php?view=bajas");
}
?>