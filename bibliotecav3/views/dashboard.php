<?php
include 'includes/conexion.php'; 
include 'includes/login_check.php'; 
?>

<div class="p-6">
    <h1 class="text-4xl font-serif font-bold text-white mb-4">
        Bienvenido al Sistema de Biblioteca
    </h1>
    <p class="text-lg text-gray-400 mb-8">
        Desde aquí puedes gestionar los recursos de la biblioteca de manera centralizada.
    </p>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-principal">
            <h3 class="text-lg font-semibold text-gray-600 mb-1">Total Libros</h3>
            <p class="text-3xl font-bold text-principal">
                <?php
                $res = $conn->query("SELECT COUNT(*) AS total FROM libro");
                echo $res->fetch_assoc()['total'] ?? 0;
                ?>
            </p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-green-500">
            <h3 class="text-lg font-semibold text-gray-600 mb-1">Libros Disponibles</h3>
            <p class="text-3xl font-bold text-green-600">
                <?php
                $res = $conn->query("SELECT COUNT(*) AS total FROM libro WHERE estado='disponible'");
                echo $res->fetch_assoc()['total'] ?? 0;
                ?>
            </p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-yellow-500">
            <h3 class="text-lg font-semibold text-gray-600 mb-1">Préstamos Activos</h3>
            <p class="text-3xl font-bold text-yellow-600">
                <?php
                $res = $conn->query("SELECT COUNT(*) AS total FROM prestamo p LEFT JOIN devolucion d ON p.id_prestamo = d.id_prestamo WHERE d.id_devolucion IS NULL");
                echo $res->fetch_assoc()['total'] ?? 0;
                ?>
            </p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-blue-500">
            <h3 class="text-lg font-semibold text-gray-600 mb-1">Total Usuarios</h3>
            <p class="text-3xl font-bold text-blue-600">
                <?php
                $res = $conn->query("SELECT COUNT(*) AS total FROM usuario");
                echo $res->fetch_assoc()['total'] ?? 0;
                ?>
            </p>
        </div>
    </div>
</div>