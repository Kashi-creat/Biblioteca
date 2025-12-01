<?php 
include '../includes/conexion.php'; 
include '../includes/login_check.php';

$id = intval($_GET['id'] ?? 0);
$hoy = date("Y-m-d");

if ($id > 0) {
    // Obtener libro asociado al préstamo
    $res = $conn->query("SELECT id_libro FROM prestamo WHERE id_prestamo=$id");
    if ($row = $res->fetch_assoc()) {
        $id_libro = $row['id_libro'];

        // Insertar devolución (nuevo registro)
        $sql = "INSERT INTO devolucion (id_prestamo, fecha_devolucion_real) 
                VALUES ($id, '$hoy')";
        if ($conn->query($sql)) {
            // Cambiar estado del libro a disponible
            $conn->query("UPDATE libro SET estado='disponible' WHERE id_libro=$id_libro");
        } else {
            // Error handling, not redirecting for simplicity
            echo "Error al registrar devolución: " . $conn->error;
            exit;
        }
    }
}

// Redirige al index del módulo usando el router principal
header("Location: ../index.php?view=prestamos&msg=devolucion_registrada");
exit;
?>