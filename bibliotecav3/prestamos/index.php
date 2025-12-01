<?php 
include './includes/conexion.php'; 
include './includes/login_check.php';

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
  <h1 class="text-4xl font-serif font-bold text-white mb-6">📖 Préstamos</h1>
  
  <?php if($_SESSION['user_type'] === 'docente'): ?>
    <a href="index.php?view=prestamos-crear" class="bg-principal text-white font-semibold py-2 px-4 rounded-lg hover:bg-principal/90 transition duration-150 shadow-md inline-block mb-6">
        ➕ Nuevo Préstamo
    </a>
  <?php endif; ?>

  <div class="bg-white shadow-xl rounded-lg overflow-hidden">
      <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-900 border-b border-gray-300">
                  <tr>
                      <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-white">ID</th>
                      <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-white">Usuario</th>
                      <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-white">Libro</th>
                      <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-white">F. Préstamo</th>
                      <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-white">F. Devolución Estimada</th>
                      <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-white">Estado / F. Real</th>
                      <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-white">Acciones</th>
                  </tr>
              </thead>
              <tbody class="bg-gray-900 divide-y divide-gray-200">
                <?php
                if($_SESSION['user_type'] === 'estudiante'){
                  $user_id = $_SESSION['user_id'];
                  $sql = "SELECT 
                          p.id_prestamo, 
                          u.nombre_completo, 
                          l.titulo, 
                          p.fecha_prestamo, 
                          p.fecha_devolucion_estimada, 
                          d.fecha_devolucion_real
                        FROM prestamo p
                        JOIN usuario u ON u.id_usuario = p.id_usuario
                        JOIN libro l ON l.id_libro = p.id_libro
                        LEFT JOIN devolucion d ON d.id_prestamo = p.id_prestamo
                        WHERE u.id_usuario = $user_id
                        ORDER BY p.fecha_prestamo DESC";
                }else {
                  $sql = "SELECT 
                          p.id_prestamo, 
                          u.nombre_completo, 
                          l.titulo, 
                          p.fecha_prestamo, 
                          p.fecha_devolucion_estimada, 
                          d.fecha_devolucion_real
                        FROM prestamo p
                        JOIN usuario u ON u.id_usuario = p.id_usuario
                        JOIN libro l ON l.id_libro = p.id_libro
                        LEFT JOIN devolucion d ON d.id_prestamo = p.id_prestamo
                        WHERE l.titulo LIKE '$buscador'
                        ORDER BY p.fecha_prestamo DESC";
                }
                $res = $conn->query($sql);
                while($row = $res->fetch_assoc()) {
                  $es_devuelto = !is_null($row['fecha_devolucion_real']);
                  $es_docente = $_SESSION['user_type'] === 'docente';
                  $es_atrasado = !$es_devuelto && (strtotime($row['fecha_devolucion_estimada']) < time());

                  $clase_fila = '';
                  if ($es_atrasado) {
                    $clase_fila = 'hover:bg-fuchsia-950';
                  } elseif ($es_devuelto) {
                    $clase_fila = 'hover:bg-sky-900';
                  } else{
                    $clase_fila = 'hover:bg-gray-800';
                  }
                  
                  echo "<tr class='transition duration-150 {$clase_fila}'>";
                  echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-white'>{$row['id_prestamo']}</td>";
                  echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-white'>{$row['nombre_completo']}</td>";
                  echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-white'>{$row['titulo']}</td>";
                  echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-white'>{$row['fecha_prestamo']}</td>";
                  echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-white'>";
                  if ($es_atrasado) {
                      echo "<span class='font-bold text-red-600'>{$row['fecha_devolucion_estimada']} ⚠️</span>";
                  } else {
                      echo $row['fecha_devolucion_estimada'];
                  }
                  echo "</td>";
                  
                  echo "<td class='px-6 py-4 whitespace-nowrap text-center text-sm font-medium'>";
                  if (!$es_devuelto) {
                    echo "<a href='prestamos/devolver.php?id={$row['id_prestamo']}' class='bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700 transition duration-150'>Devolver</a>";
                  } elseif ($es_devuelto) {
                    echo "<span class='inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold bg-green-200 text-green-800'>{$row['fecha_devolucion_real']}</span>";
                  } else {
                    echo "<span class='inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold bg-yellow-200 text-yellow-800'>Pendiente</span>";
                  }
                  echo "</td>";

                  echo "<td class='px-6 py-4 whitespace-nowrap text-center text-sm font-medium'>";
                  if ($es_docente && !$es_devuelto) {
                    echo "<a href='prestamos/eliminar.php?id={$row['id_prestamo']}' class='text-red-600 hover:text-red-800 transition duration-150 hover:underline' onclick=\"return confirm('¿Seguro que deseas eliminar el préstamo (solo si no se ha devuelto)?')\">Eliminar</a>";
                  } else {
                    echo "N/A";
                  }
                  echo "</td>";
                  echo "</tr>";
                }
                ?>
              </tbody>
          </table>
      </div>
  </div>
</div>