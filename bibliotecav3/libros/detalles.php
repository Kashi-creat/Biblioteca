<?php 
include './includes/conexion.php'; 
include './includes/login_check.php';
// Nota: La restricción de rol debe ir aquí, aunque el menú lo oculte para el estudiante.
// Si deseas que los estudiantes también vean detalles, elimina requireRole.
include './includes/rol_check.php';
requireRole(['docente', 'estudiante']);

$id = intval($_GET['id'] ?? 0);
if ($id === 0) {
    header("Location: index.php?view=libros");
    exit;
}

// Consulta para obtener todos los detalles del libro
$sql = "SELECT L.titulo, L.ISBN, L.codigo_clasificacion, L.observaciones, L.edicion, L.ano_publicacion, L.estado,
               GROUP_CONCAT(A.nombre_autor SEPARATOR ', ') AS autores, 
               E.nombre_editorial AS editorial,
               ATE.nombre_area AS area
        FROM libro L
        LEFT JOIN libro_autor LA ON LA.id_libro = L.id_libro
        LEFT JOIN autor A ON A.id_autor = LA.id_autor
        JOIN editorial E ON E.id_editorial = L.id_editorial
        JOIN area_tematica ATE ON ATE.id_area_tematica = L.id_area_tematica
        WHERE L.id_libro=$id
        GROUP BY L.id_libro";

$res = $conn->query($sql);
if (!$libro = $res->fetch_assoc()) {
    header("Location: ../index.php?view=libros&msg=error_no_encontrado");
    exit;
}

// Lógica para el color del estado
$estado_clase = match($libro['estado']) {
    'disponible' => 'bg-green-500',
    'prestado'   => 'bg-yellow-500',
    'baja'       => 'bg-red-500',
    default      => 'bg-gray-500',
};
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

<div class="p-6 max-w-2xl mx-auto bg-gray-900 shadow-xl rounded-lg">
  <h1 class="text-3xl font-serif font-bold text-zinc-300 mb-6">📖 Detalles: <?= htmlspecialchars($libro['titulo']) ?></h1>
  
  <div class="space-y-4">
    <div class="flex items-center space-x-2">
        <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full text-white <?= $estado_clase ?>">
            <?= ucfirst($libro['estado']) ?>
        </span>
    </div>
    
    <div class="border border-gray-200 rounded-lg overflow-hidden">
        <dl class="divide-y divide-gray-100">
            <div class="px-4 py-3 bg-gray-800 grid grid-cols-3 gap-4">
                <dt class="text-sm font-medium text-gray-500">Título</dt>
                <dd class="mt-1 text-sm text-zinc-300 col-span-2"><?= htmlspecialchars($libro['titulo']) ?></dd>
            </div>
            <div class="px-4 py-3 grid grid-cols-3 gap-4">
                <dt class="text-sm font-medium text-gray-500">Autor(es)</dt>
                <dd class="mt-1 text-sm text-zinc-300 col-span-2"><?= htmlspecialchars($libro['autores']) ?></dd>
            </div>
            <div class="px-4 py-3 bg-gray-800 grid grid-cols-3 gap-4">
                <dt class="text-sm font-medium text-gray-500">Editorial</dt>
                <dd class="mt-1 text-sm text-zinc-300 col-span-2"><?= htmlspecialchars($libro['editorial']) ?></dd>
            </div>
            <div class="px-4 py-3 grid grid-cols-3 gap-4">
                <dt class="text-sm font-medium text-gray-500">Área Temática</dt>
                <dd class="mt-1 text-sm text-zinc-300 col-span-2"><?= htmlspecialchars($libro['area']) ?></dd>
            </div>
            <div class="px-4 py-3 bg-gray-800 grid grid-cols-3 gap-4">
                <dt class="text-sm font-medium text-gray-500">Año / Edición</dt>
                <dd class="mt-1 text-sm text-zinc-300 col-span-2"><?= htmlspecialchars($libro['ano_publicacion']) ?> / <?= htmlspecialchars($libro['edicion']) ?></dd>
            </div>
            <div class="px-4 py-3 grid grid-cols-3 gap-4">
                <dt class="text-sm font-medium text-gray-500">ISBN</dt>
                <dd class="mt-1 text-sm text-zinc-300 col-span-2"><?= htmlspecialchars($libro['ISBN']) ?></dd>
            </div>
            <div class="px-4 py-3 bg-gray-800 grid grid-cols-3 gap-4">
                <dt class="text-sm font-medium text-gray-500">Código Clasificación</dt>
                <dd class="mt-1 text-sm text-zinc-300 col-span-2"><?= htmlspecialchars($libro['codigo_clasificacion']) ?></dd>
            </div>
            <div class="px-4 py-3 grid grid-cols-3 gap-4">
                <dt class="text-sm font-medium text-gray-500">Observaciones</dt>
                <dd class="mt-1 text-sm text-zinc-300 col-span-2 whitespace-pre-wrap"><?= htmlspecialchars($libro['observaciones']) ?></dd>
            </div>
        </dl>
    </div>
  </div>

  <a href="index.php?view=libros" class="mt-6 inline-block bg-gray-500 text-white font-semibold py-2 px-4 rounded-lg hover:bg-gray-600 transition duration-150 shadow-md">
    Volver
  </a>
</div>
</body>