<?php
// Mantenemos la lógica de backend y seguridad
include './includes/conexion.php'; 
include './includes/login_check.php';
// Nota: La función 'confirmarDoble' (si no está en public/js/main.js)
// debe incluirse en el index.php principal para que funcione el 'Borrar Todos'.

// La consulta para obtener todos los libros, autores y editoriales se mantiene.

// Buscador de libros
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

$sql = "SELECT 
            L.id_libro, 
            L.titulo, 
            GROUP_CONCAT(A.nombre_autor SEPARATOR ', ') AS autor, 
            E.nombre_editorial AS editorial, 
            L.ano_publicacion AS ano, 
            L.edicion, 
            ATE.nombre_area AS area, 
            L.estado 
        FROM libro L 
        LEFT JOIN libro_autor LA ON LA.id_libro = L.id_libro 
        LEFT JOIN autor A ON A.id_autor = LA.id_autor 
        JOIN editorial E ON E.id_editorial = L.id_editorial 
        JOIN area_tematica ATE ON ATE.id_area_tematica = L.id_area_tematica
        WHERE L.titulo LIKE '$buscador'
        GROUP BY L.id_libro
        ORDER BY L.id_libro ASC";
$res = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Libro</title>
    
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <link href="../public/css/style.css" rel="stylesheet"> 

</head>
<body>

<div class="p-6">
    <h1 class="text-4xl font-serif font-bold text-white mb-6">📚 Gestión de Libros</h1>

    <?php if($_SESSION['user_type'] === 'docente'): ?>
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 space-y-3 sm:space-y-0">
            <a href="index.php?view=libros-crear" class="w-full sm:w-auto bg-principal text-white font-semibold py-2 px-4 rounded-lg hover:bg-principal/90 transition duration-150 shadow-md flex items-center justify-center space-x-2">
                ➕ Nuevo Libro
            </a>
            <a href="libros/eliminar_todos.php" onclick="return confirmarDoble()" class="w-full sm:w-auto bg-red-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-red-700 transition duration-150 shadow-md flex items-center justify-center space-x-2"> 
                ❌ Borrar Todos
            </a>
        </div>
    <?php endif; ?>

    <div class="shadow-xl rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                
                <thead class="bg-gray-900 border-b border-gray-300">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-white">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-white">Título</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-white">Autor</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-white hidden sm:table-cell">Editorial</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-white hidden md:table-cell">Año</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-white hidden lg:table-cell">Edición</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-white hidden md:table-cell">Área</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-white">Estado</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-white">Acciones</th>
                    </tr>
                </thead>
                
                <tbody class="bg-gray-900 divide-y divide-gray-500">
                    <?php
                    while($row = $res->fetch_assoc()) {
                        // Lógica para el color del estado con clases de Tailwind
                        $estado_clase = match($row['estado']) {
                            'disponible' => 'bg-green-100 text-green-800 border-green-500',
                            'prestado'   => 'bg-yellow-100 text-yellow-800 border-yellow-500',
                            'baja'       => 'bg-red-100 text-red-800 border-red-500',
                            default      => 'bg-gray-100 text-gray-800 border-gray-500',
                        };
                        $estado_texto = ucfirst($row['estado']);

                        echo "<tr class='hover:bg-gray-800 transition duration-150'>";
                        echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-white'>{$row['id_libro']}</td>";
                        echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-white'>{$row['titulo']}</td>";
                        echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-white'>{$row['autor']}</td>";
                        echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-white hidden sm:table-cell'>{$row['editorial']}</td>";
                        echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-center text-white hidden md:table-cell'>{$row['ano']}</td>";
                        echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-center text-white hidden lg:table-cell'>{$row['edicion']}</td>";
                        echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-center text-white hidden md:table-cell'>{$row['area']}</td>";
                        
                        // Celda de Estado (Badge)
                        echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-center'>";
                        echo "<span class='inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold border {$estado_clase}'>{$estado_texto}</span>";
                        echo "</td>";
                        
                        // Celda de Acciones
                        echo "<td class='px-6 py-4 whitespace-nowrap text-center text-sm font-medium'>";
                        
                        $base_url = "index.php?view=libros-"; // La ruta debe ser relativa a la raíz para que el router la procese

                        echo "<div class='flex items-center justify-center space-x-3'>";
                        // Detalle (para todos)
                        echo "<a href='{$base_url}detalles&id={$row['id_libro']}' class='text-blue-600 hover:text-blue-800 transition duration-150 hover:underline'>Detalles</a>";

                        if($_SESSION['user_type'] === 'docente') {
                            // Separador y acciones de Docente
                            echo "<span class='text-gray-300'>|</span>";
                            echo "<a href='{$base_url}editar&id={$row['id_libro']}' class='text-yellow-600 hover:text-yellow-800 transition duration-150 hover:underline'>Editar</a>";
                            echo "<span class='text-gray-300'>|</span>";
                            echo "<a href='libros/eliminar.php?id={$row['id_libro']}' class='text-red-600 hover:text-red-800 transition duration-150 hover:underline' onclick=\"return confirm('¿Seguro que deseas eliminar el libro {$row['titulo']}?')\">Eliminar</a>";
                        }
                        echo "</div>";
                        
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
<?php $conn->close(); ?>