<?php 
include './includes/conexion.php'; 
include './includes/login_check.php';
include './includes/rol_check.php';
requireRole(['docente']);

// Buscador
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if (isset($_POST['buscador']) && !empty(trim($_POST['buscador']))) {
        $buscador = $_POST['buscador'] . "%";
    }else {
        $buscador = "%";
    }
}else {
    $buscador = "%";
}
// fin buscador
?>

<div class="p-6">
    <h1 class="text-4xl font-serif font-bold text-white mb-6">📉 Registro de Bajas</h1>
    
    <a href="index.php?view=bajas-crear" class="bg-principal text-white font-semibold py-2 px-4 rounded-lg hover:bg-principal/90 transition duration-150 shadow-md inline-block mb-6">
        ➕ Nueva Baja
    </a>

    <div class="bg-white shadow-xl rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-900 border-b border-gray-300">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-zinc-300">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-zinc-300">Libro</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-zinc-300">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-zinc-300">Motivo</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-zinc-300">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-900 divide-y divide-gray-200">
                    <?php
                    $sql = "SELECT b.*, l.titulo 
                            FROM baja b 
                            JOIN libro l ON b.id_libro = l.id_libro
                            WHERE l.titulo LIKE '$buscador'";
                    $res = $conn->query($sql);
                    while($row = $res->fetch_assoc()) {
                        echo "<tr class='hover:bg-gray-800 transition duration-150'>";
                        echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-zinc-300'>{$row['id_baja']}</td>";
                        echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-zinc-300'>{$row['titulo']}</td>";
                        echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-zinc-300'>{$row['fecha_baja']}</td>";
                        echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-zinc-300'>" . ucfirst($row['motivo']) . "</td>";
                        echo "<td class='px-6 py-4 whitespace-nowrap text-center text-sm font-medium'>";
                        echo "<a href='bajas/eliminar.php?id={$row['id_baja']}' class='text-blue-600 hover:text-blue-800 transition duration-150 hover:underline' onclick=\"return confirm('¿Anular la baja del libro {$row['titulo']} y ponerlo disponible de nuevo?')\">Anular Baja</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
