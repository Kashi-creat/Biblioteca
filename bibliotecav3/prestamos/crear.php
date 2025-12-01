<?php 
include './includes/conexion.php'; 
include './includes/login_check.php';

// Si es estudiante, no puede acceder al formulario si ya está en el menú
if($_SESSION['user_type'] === 'docente') {
    // Si es docente no necesita restricción, pero se puede añadir
}

// Lógica de creación de préstamo
if ($_POST) {
    $usuario = intval($_POST['usuario'] ?? 0);
    $libro = intval($_POST['libro'] ?? 0);
    $fecha_estimada = $conn->real_escape_string($_POST['fecha_estimada'] ?? '');
    $hoy = date("Y-m-d");

    $sql = "INSERT INTO prestamo (id_usuario, id_libro, fecha_prestamo, fecha_devolucion_estimada) 
            VALUES ($usuario, $libro, '$hoy', '$fecha_estimada')";
            
    if ($conn->query($sql)) {
      // Cambiar estado del libro a prestado
      $conn->query("UPDATE libro SET estado='prestado' WHERE id_libro=$libro");

      // Redirige al index del módulo usando el router principal
      header("Location: index.php?view=prestamos&msg=prestamo_registrado");
      exit;
    } else {
      echo "<div class='p-6'><div class='bg-red-100 border-l-4 border-red-500 text-red-700 p-4' role='alert'>❌ Error al registrar préstamo: " . $conn->error . "</div></div>";
    }
}
?>

<div class="p-6 max-w-lg mx-auto bg-gray-900 shadow-xl rounded-lg">
  <h1 class="text-3xl font-serif font-bold text-white mb-6">➕ Nuevo Préstamo</h1>
  <form method="POST" class="space-y-4">
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-1">Usuario</label>
      <select name="usuario" class="w-full p-3 bg-gray-900 border border-gray-300 text-zinc-300 rounded-lg focus:ring-principal focus:border-principal shadow-sm" required>
        <option value="">-- Seleccionar --</option>
        <?php
        $user_id = $_SESSION['user_id'];
        // Si es estudiante, solo se muestra a sí mismo (para evitar que preste a otros)
        $where_user = ($_SESSION['user_type'] === 'estudiante') ? "WHERE id_usuario = $user_id" : "";
        
        $res = $conn->query("SELECT id_usuario, nombre_completo, tipo_usuario FROM usuario $where_user");
        while($u = $res->fetch_assoc()) {
          $selected = ($u['id_usuario'] == $user_id && $_SESSION['user_type'] === 'estudiante') ? 'selected' : '';
          echo "<option value='{$u['id_usuario']}' {$selected}>{$u['nombre_completo']} ({$u['tipo_usuario']})</option>";
        }
        ?>
      </select>
    </div>
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-1">Libro</label>
      <select name="libro" class="w-full p-3 bg-gray-900 border border-gray-300 text-zinc-300 rounded-lg focus:ring-principal focus:border-principal shadow-sm" required>
        <option value="">-- Seleccionar --</option>
        <?php
        $sql = "SELECT l.id_libro, l.titulo, GROUP_CONCAT(a.nombre_autor SEPARATOR ', ') AS autores
                FROM libro l
                LEFT JOIN libro_autor la ON l.id_libro = la.id_libro
                LEFT JOIN autor a ON a.id_autor = la.id_autor
                WHERE l.estado='disponible'
                GROUP BY l.id_libro
                ORDER BY l.titulo ASC";
        $res = $conn->query($sql);
        while($l = $res->fetch_assoc()) {
          echo "<option value='{$l['id_libro']}'>{$l['titulo']} ({$l['autores']})</option>";
        }
        ?>
      </select>
    </div>
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Devolución Estimada</label>
      <input type="date" name="fecha_estimada" class="w-full p-3 bg-gray-900 border border-gray-300 text-zinc-300 rounded-lg focus:ring-principal focus:border-principal shadow-sm" required min="<?= date('Y-m-d') ?>">
    </div>
    
    <div class="flex space-x-4">
        <button type="submit" class="w-full bg-principal text-white font-semibold py-2 px-4 rounded-lg hover:bg-principal/90 transition duration-150 shadow-md">
            Registrar Préstamo
        </button>
        <a href="index.php?view=prestamos" class="w-full text-center bg-gray-500 text-white font-semibold py-2 px-4 rounded-lg hover:bg-gray-600 transition duration-150 shadow-md">
            Volver
        </a>
    </div>
  </form>
</div>