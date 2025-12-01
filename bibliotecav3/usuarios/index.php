<?php 
include './includes/conexion.php'; 
include './includes/login_check.php';
include './includes/rol_check.php';
requireRole(['docente']);

// Buscador de usuarios
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
  <h1 class="text-4xl font-serif font-bold text-white mb-6">👤 Gestión de Usuarios</h1>
  
  <a href="index.php?view=usuarios-crear" class="bg-principal text-white font-semibold py-2 px-4 rounded-lg hover:bg-principal/90 transition duration-150 shadow-md inline-block mb-6">
      ➕ Nuevo Usuario
  </a>

  <div class="bg-white shadow-xl rounded-lg overflow-hidden">
      <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-900 border-b border-gray-300">
                  <tr>
                      <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-white">ID</th>
                      <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-white">Nombre</th>
                      <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-white">Tipo</th>
                      <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-white">DNI/Matrícula</th>
                      <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-white">Acciones</th>
                  </tr>
              </thead>
              <tbody class="bg-gray-900 divide-y divide-gray-200">
                <?php
                $res = $conn->query("SELECT * FROM usuario WHERE nombre_completo LIKE '$buscador'");
                while($row = $res->fetch_assoc()) {
                  // Clase para el badge de tipo
                  $tipo_clase = $row['tipo_usuario'] === 'docente' ? 'bg-principal text-white' : 'bg-blue-100 text-blue-800';

                  echo "<tr class='hover:bg-gray-800 transition duration-150'>";
                  echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-white'>{$row['id_usuario']}</td>";
                  echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-white'>{$row['nombre_completo']}</td>";
                  echo "<td class='px-6 py-4 whitespace-nowrap text-sm'>";
                  echo "<span class='inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold {$tipo_clase}'>" . ucfirst($row['tipo_usuario']) . "</span>";
                  echo "</td>";
                  echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-white'>{$row['dni_o_matricula']}</td>";
                  echo "<td class='px-6 py-4 whitespace-nowrap text-center text-sm font-medium'>";
                  echo "<a href='index.php?view=usuarios-editar&id={$row['id_usuario']}' class='text-blue-600 hover:text-blue-800 transition duration-150 hover:underline mr-3'>Editar</a>";
                  echo "<a href='usuarios/eliminar.php?id={$row['id_usuario']}' class='text-red-600 hover:text-red-800 transition duration-150 hover:underline' onclick=\"return confirm('¿Seguro que deseas eliminar a {$row['nombre_completo']}?')\">Eliminar</a>";
                  echo "</td>";
                  echo "</tr>";
                }
                ?>
              </tbody>
          </table>
      </div>
  </div>
</div>