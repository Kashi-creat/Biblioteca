<?php 
include './includes/conexion.php'; 
include './includes/login_check.php';
include './includes/rol_check.php';
requireRole(['docente']);

// Procesamiento de formularios de Lógica y Redirecciones
if ($_POST) {
    $accion = $_POST['accion'] ?? '';

    // Lógica para guardar un NUEVO LIBRO
    if ($accion == "guardar_libro") {
      $titulo = $conn->real_escape_string($_POST['titulo']);
      $edicion = $conn->real_escape_string($_POST['edicion']);
      $id_autor = intval($_POST['id_autor']);
      $id_editorial = intval($_POST['id_editorial']);
      $anio = intval($_POST['ano_publicacion']);
      $id_area = intval($_POST['id_area_tematica']);
      $isbn = $conn->real_escape_string($_POST['ISBN']);
      $codigo = intval($_POST['codigo_clasficiacion'] ?? 0);

      $sql = "INSERT INTO libro (titulo, id_editorial, ano_publicacion, edicion, ISBN, id_area_tematica, codigo_clasificacion, estado)
              VALUES ('$titulo', $id_editorial, $anio, '$edicion', '$isbn', $id_area, $codigo, 'disponible')";
      if ($conn->query($sql)) {
        $id_libro = $conn->insert_id;
        $conn->query("INSERT INTO libro_autor (id_libro, id_autor) VALUES ($id_libro, $id_autor)");
        header("Location: index.php?msg=ok");
        echo "<div class='alert alert-success mt-3'>Libro agregado correctamente</div>";
      } else {
        echo "<div class='alert alert-danger mt-3'>Error: " . $conn->error . "</div>";
      }
    }
    
    // Lógica para guardar NUEVO AUTOR
    if ($accion == "nuevo_autor") {
        $nombre_autor = $conn->real_escape_string($_POST['nombre_autor']);
        if ($conn->query("INSERT INTO autor (nombre_autor) VALUES ('$nombre_autor')")) {
            // Redirigir para evitar reenvío de formulario y mostrar mensaje flash
            header("Location: index.php?view=libros&msg=autor_guardado");
            exit;
        } 
    }

    // Lógica para guardar NUEVA EDITORIAL
    if ($accion == "nueva_editorial") {
        // CORRECCIÓN: Se usa 'nombre_editorial' ya que es el 'name' del input en el HTML
        $nombre_editorial = $conn->real_escape_string($_POST['nombre_editorial']);
        if ($conn->query("INSERT INTO editorial (nombre_editorial) VALUES ('$nombre_editorial')")) {
            // Redirigir para evitar reenvío de formulario y mostrar mensaje flash
            header("Location: index.php?view=libros&msg=editorial_guardada");
            exit;
        } 
    }
    
    // Lógica para guardar NUEVA AREA
    if ($accion == "nueva_area") {
        // CORRECCIÓN: Se usa 'nombre_area' ya que es el 'name' del input en el HTML
        $nombre_area = $conn->real_escape_string($_POST["nombre_area"]);
        
        if ($conn->query("INSERT INTO area_tematica (nombre_area) VALUES ('$nombre_area')")) {
            // CORRECCIÓN: Cambiado el mensaje de redirección a 'area_guardada'
            header("Location: ../index.php?view=libros&msg=area_guardada"); 
            exit;
        } 
    }
}
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

<div class="p-6 max-w-4xl mx-auto">
  <h1 class="text-4xl font-serif font-bold text-white mb-6">➕ Nuevo Libro</h1>
  
  <div class="bg-gray-900 p-6 rounded-lg shadow-xl border border-gray-600">
    <form method="POST" class="space-y-6">
      <input type="hidden" name="accion" value="guardar_libro">
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-medium text-white mb-1">Título</label>
          <input type="text" name="titulo" class="w-full p-3 border border-gray-300 text-zinc-300 rounded-lg focus:ring-principal focus:border-principal shadow-sm" required>
        </div>

        <div>
          <label class="block text-sm font-medium text-white mb-1">Edición</label>
          <input type="text" name="edicion" class="w-full p-3 border border-gray-300 text-zinc-300 rounded-lg focus:ring-principal focus:border-principal shadow-sm">
        </div>

        <div>
          <label class="block text-sm font-medium text-white mb-1">Autor</label>
          <select name="id_autor" class="w-full p-3 border border-gray-300 text-zinc-300 rounded-lg focus:ring-principal focus:border-principal shadow-sm" required>
            <option value="">Seleccione un autor</option>
            <?php
            $res = $conn->query("SELECT id_autor, nombre_autor FROM autor");
            while($row = $res->fetch_assoc()){
              echo "<option value='{$row['id_autor']}'>{$row['nombre_autor']}</option>";
            }
            ?>
          </select>
        </div>
        
        <div>
          <label class="block text-sm font-medium text-white mb-1">Editorial</label>
          <select name="id_editorial" class="w-full p-3 border border-gray-300 text-zinc-300 rounded-lg focus:ring-principal focus:border-principal shadow-sm" required>
            <option value="">Seleccione una editorial</option>
            <?php
            $res = $conn->query("SELECT id_editorial, nombre_editorial FROM editorial");
            while($row = $res->fetch_assoc()){
              echo "<option value='{$row['id_editorial']}'>{$row['nombre_editorial']}</option>";
            }
            ?>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-white mb-1">Año de Publicación</label>
          <input type="number" name="ano_publicacion" class="w-full p-3 border border-gray-300 text-zinc-300 rounded-lg focus:ring-principal focus:border-principal shadow-sm">
        </div>
        
        <div>
          <label class="block text-sm font-medium text-white mb-1">Área Temática</label>
          <select name="id_area_tematica" class="w-full p-3 border border-gray-300 text-zinc-300 rounded-lg focus:ring-principal focus:border-principal shadow-sm" required>
            <option value="">Seleccione un área</option>
            <?php
            $res = $conn->query("SELECT id_area_tematica, nombre_area FROM area_tematica");
            while($row = $res->fetch_assoc()){
              echo "<option value='{$row['id_area_tematica']}'>{$row['nombre_area']}</option>";
            }
            ?>
          </select>
        </div>
        
        <div>
          <label class="block text-sm font-medium text-white mb-1">ISBN</label>
          <input type="text" name="ISBN" class="w-full p-3 border border-gray-300 text-zinc-300 rounded-lg focus:ring-principal focus:border-principal shadow-sm">
        </div>

        <div>
          <label class="block text-sm font-medium text-white mb-1">Código de Clasificación</label>
          <input type="text" name="codigo_clasificacion" class="w-full p-3 border border-gray-300 text-zinc-300 rounded-lg focus:ring-principal focus:border-principal shadow-sm">
        </div>
      </div>
      
      <div>
        <label class="block text-sm font-medium text-white mb-1">Observaciones</label>
        <textarea name="observaciones" rows="3" class="w-full p-3 border border-gray-300 text-zinc-300 rounded-lg focus:ring-principal focus:border-principal shadow-sm"></textarea>
      </div>

      <div class="flex space-x-4">
        <button type="submit" class="w-full bg-principal text-white font-semibold py-3 px-4 rounded-lg hover:bg-principal/90 transition duration-150 shadow-md">
            Guardar Libro
        </button>
        <a href="index.php?view=libros" class="w-full text-center bg-gray-500 text-white font-semibold py-3 px-4 rounded-lg hover:bg-gray-600 transition duration-150 shadow-md">
            Volver
        </a>
      </div>
    </form>
  </div>
  
  <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6 ">
    <div class="bg-gray-900 p-4 rounded-lg shadow-md border border-gray-600">
      <h3 class="text-lg font-semibold text-white mb-3">➕ Nuevo Autor</h3>
      <form method="POST">
        <input type="hidden" name="accion" value="nuevo_autor">
        <input type="text" name="nombre_autor" placeholder="Nombre del Autor" class="w-full p-2 border border-gray-300 text-zinc-300 rounded-lg focus:ring-principal focus:border-principal shadow-sm mb-3" required>
        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition duration-150">Guardar Autor</button>
      </form>
    </div>
    <div class="bg-gray-900 p-4 rounded-lg shadow-md border border-gray-600">
      <h3 class="text-lg font-semibold text-white mb-3">➕ Nueva Editorial</h3>
      <form method="POST">
        <input type="hidden" name="accion" value="nueva_editorial">
        <input type="text" name="nombre_editorial" placeholder="Nombre de Editorial" class="w-full p-2 border border-gray-300 text-zinc-300 rounded-lg focus:ring-principal focus:border-principal shadow-sm mb-3" required>
        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition duration-150">Guardar Editorial</button>
      </form>
    </div>
    <div class="bg-gray-900 p-4 rounded-lg shadow-md border border-gray-600">
      <h3 class="text-lg font-semibold text-white mb-3">➕ Nueva Área</h3>
      <form method="POST">
        <input type="hidden" name="accion" value="nueva_area">
        <input type="text" name="nombre_area" placeholder="Nombre del Área" class="w-full p-2 border border-gray-300 text-zinc-300 rounded-lg focus:ring-principal focus:border-principal shadow-sm mb-3" required>
        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition duration-150">Guardar Área</button>
      </form>
    </div>
  </div>
</div>
</body>