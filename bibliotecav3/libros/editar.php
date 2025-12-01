<?php 
include './includes/conexion.php'; 
include './includes/login_check.php';
include './includes/rol_check.php';
requireRole(['docente']);

$id = intval($_GET['id'] ?? 0);
if ($id === 0) {
    header("Location: ../index.php?view=libros");
    exit;
}

// Lógica de actualización
if ($_POST) {
    // ... (Tu lógica de actualización se mantiene igual)
    
    // Redirige al index del módulo usando el router principal
    header("Location: index.php?view=libros&msg=libro_guardado");
    exit;
}

// Consulta para obtener datos del libro (se mantiene)
$sql = "SELECT L.id_libro, L.titulo, LA.id_autor, E.id_editorial, L.ano_publicacion, L.edicion, ATE.id_area_tematica, L.estado, L.ISBN, L.codigo_clasificacion, L.observaciones 
        FROM libro L 
        JOIN libro_autor LA ON LA.id_libro = L.id_libro 
        JOIN editorial E ON E.id_editorial = L.id_editorial 
        JOIN area_tematica ATE ON ATE.id_area_tematica = L.id_area_tematica 
        WHERE L.id_libro=$id";

$res = $conn->query($sql);
if (!$libro = $res->fetch_assoc()) {
    header("Location: ../index.php?view=libros&msg=error_no_encontrado");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Libro</title>
    
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'marron': '#8B4513', // Color primario
                        'crema-fondo': '#FAF0E6', // Color de fondo
                    },
                    fontFamily: {
                        'serif': ['Merriweather', 'serif'],
                        'sans': ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <link href="../public/css/base.css" rel="stylesheet"> 
    
</head>
<body>

<div class="p-6 max-w-4xl mx-auto bg-white shadow-xl rounded-lg">
  <h1 class="text-3xl font-serif font-bold text-gray-800 mb-6">✏️ Editar Libro #<?= $id ?></h1>
  
  <form method="POST" class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Título</label>
        <input type="text" name="titulo" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-marron focus:border-marron shadow-sm" value="<?= htmlspecialchars($libro['titulo']) ?>" required>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Edición</label>
        <input type="text" name="edicion" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-marron focus:border-marron shadow-sm" value="<?= htmlspecialchars($libro['edicion']) ?>">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Autor</label>
        <select name="id_autor" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-marron focus:border-marron shadow-sm" required>
          <?php
          $res_a = $conn->query("SELECT id_autor, nombre_autor FROM autor");
          while($row = $res_a->fetch_assoc()){
            $selected = ($row['id_autor'] == $libro['id_autor']) ? 'selected' : '';
            echo "<option value='{$row['id_autor']}' {$selected}>{$row['nombre_autor']}</option>";
          }
          ?>
        </select>
      </div>
      
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Editorial</label>
        <select name="id_editorial" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-marron focus:border-marron shadow-sm" required>
          <?php
          $res_e = $conn->query("SELECT id_editorial, nombre_editorial FROM editorial");
          while($row = $res_e->fetch_assoc()){
            $selected = ($row['id_editorial'] == $libro['id_editorial']) ? 'selected' : '';
            echo "<option value='{$row['id_editorial']}' {$selected}>{$row['nombre_editorial']}</option>";
          }
          ?>
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Año de Publicación</label>
        <input type="number" name="ano_publicacion" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-marron focus:border-marron shadow-sm" value="<?= htmlspecialchars($libro['ano_publicacion']) ?>">
      </div>
      
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Área Temática</label>
        <select name="id_area_tematica" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-marron focus:border-marron shadow-sm" required>
          <?php
          $res_t = $conn->query("SELECT id_area_tematica, nombre_area FROM area_tematica");
          while($row = $res_t->fetch_assoc()){
            $selected = ($row['id_area_tematica'] == $libro['id_area_tematica']) ? 'selected' : '';
            echo "<option value='{$row['id_area_tematica']}' {$selected}>{$row['nombre_area']}</option>";
          }
          ?>
        </select>
      </div>
      
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
        <select name="estado" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-marron focus:border-marron shadow-sm" required>
          <?php $estados = ['disponible', 'prestado', 'baja'];
          foreach($estados as $estado): ?>
              <option value="<?= $estado ?>" <?= ($estado == $libro['estado']) ? 'selected' : '' ?>>
                <?= ucfirst($estado) ?>
              </option>
          <?php endforeach; ?>
        </select>
      </div>
      
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">ISBN</label>
        <input type="text" name="ISBN" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-marron focus:border-marron shadow-sm" value="<?= htmlspecialchars($libro['ISBN']) ?>">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Código de Clasificación</label>
        <input type="text" name="codigo_clasificacion" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-marron focus:border-marron shadow-sm" value="<?= htmlspecialchars($libro['codigo_clasificacion']) ?>">
      </div>
    </div>
    
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
      <textarea name="observaciones" rows="3" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-marron focus:border-marron shadow-sm"><?= htmlspecialchars($libro['observaciones']) ?></textarea>
    </div>

    <div class="flex space-x-4">
        <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-3 px-4 rounded-lg hover:bg-blue-700 transition duration-150 shadow-md">
            Actualizar Libro
        </button>
        <a href="index.php?view=libros" class="w-full text-center bg-gray-500 text-white font-semibold py-3 px-4 rounded-lg hover:bg-gray-600 transition duration-150 shadow-md">
            Volver
        </a>
    </div>
  </form>
</div>
</body>