<?php 
include './includes/conexion.php'; 
include './includes/login_check.php';
include './includes/rol_check.php';
requireRole(['docente']);

if ($_POST) {
    $libro = intval($_POST['libro']);
    $motivo = $conn->real_escape_string($_POST['motivo']);
    $fecha = date("Y-m-d");

    // Lógica: Insertar en baja y cambiar estado del libro a 'baja'
    $sql_baja = "INSERT INTO baja (id_libro, motivo, fecha_baja) 
                 VALUES ($libro, '$motivo', '$fecha')";
    
    if ($conn->query($sql_baja)) {
      $sql_libro = "UPDATE libro SET estado='baja' WHERE id_libro=$libro";
      $conn->query($sql_libro);

      // Redirige al index del módulo usando el router principal
      header("Location: index.php?view=bajas&msg=baja_registrada");
      exit;
    } else {
      // Manejo de error (simple, puede mejorar)
      echo "<div class='p-6'><div class='bg-red-100 border-l-4 border-red-500 text-red-700 p-4' role='alert'>❌ Error al registrar la baja: " . $conn->error . "</div></div>";
    }
}
?>

<div class="p-6 max-w-lg mx-auto bg-gray-900 shadow-xl rounded-lg">
  <h1 class="text-3xl font-serif font-bold text-zinc-300 mb-6">➕ Registrar Baja</h1>
  <form method="POST" class="space-y-4">
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-1">Libro</label>
      <select name="libro" class="w-full p-3 bg-gray-900 border border-gray-300 text-zinc-300 rounded-lg focus:ring-principal focus:border-principal shadow-sm" required>
        <option value="">-- Seleccionar --</option>
        <?php
        // Mostrar libros que no estén dados de baja
        $sql = "SELECT l.id_libro, l.titulo, l.estado, 
                       GROUP_CONCAT(a.nombre_autor SEPARATOR ', ') AS autores
                FROM libro l
                LEFT JOIN libro_autor la ON l.id_libro = la.id_libro
                LEFT JOIN autor a ON la.id_autor = a.id_autor
                WHERE l.estado = 'disponible' OR l.estado = 'prestado'
                GROUP BY l.id_libro";
        $res = $conn->query($sql);
        while($l = $res->fetch_assoc()) {
          $estado_badge = $l['estado'] === 'prestado' ? ' (PRESTADO)' : '';
          echo "<option value='{$l['id_libro']}'>
                  {$l['titulo']} - {$l['autores']}{$estado_badge}
                </option>";
        }
        ?>
      </select>
    </div>

    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-1">Motivo</label>
      <select name="motivo" class="w-full p-3 bg-gray-900 border border-gray-300 text-zinc-300 rounded-lg focus:ring-principal focus:border-principal shadow-sm" required>
        <option value="deterioro">Deterioro</option>
        <option value="perdida">Pérdida</option>
        <option value="desactualizacion">Desactualización</option>
      </select>
    </div>

    <div class="flex space-x-4">
        <button type="submit" class="w-full bg-red-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-red-700 transition duration-150 shadow-md">
            Registrar Baja
        </button>
        <a href="index.php?view=bajas" class="w-full text-center bg-gray-500 text-white font-semibold py-2 px-4 rounded-lg hover:bg-gray-600 transition duration-150 shadow-md">
            Volver
        </a>
    </div>
  </form>
</div>