<?php 
include './includes/conexion.php'; 
include './includes/login_check.php';
include './includes/rol_check.php';
requireRole(['docente']);

if ($_POST) {
    $nombre = $conn->real_escape_string($_POST['nombre'] ?? '');
    $tipo   = $conn->real_escape_string($_POST['tipo'] ?? 'estudiante');
    $dni    = $conn->real_escape_string($_POST['dni'] ?? '');
    $pass_raw = $_POST['password'] ?? '';
    $password_hash = password_hash($pass_raw, PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuario (nombre_completo, tipo_usuario, dni_o_matricula, password) 
            VALUES ('$nombre', '$tipo', '$dni', '$password_hash')";
            
    if ($conn->query($sql)) {
      // Redirige al index del módulo usando el router principal
      header("Location: index.php?view=usuarios&msg=usuario_guardado");
      exit;
    } else {
      echo "<div class='p-6'><div class='bg-red-100 border-l-4 border-red-500 text-red-700 p-4' role='alert'>❌ Error al crear usuario: " . $conn->error . "</div></div>";
    }
}
?>

<div class="p-6 max-w-lg mx-auto bg-gray-900 shadow-xl rounded-lg">
  <h1 class="text-3xl font-serif font-bold text-white mb-6">➕ Nuevo Usuario</h1>
  <form method="POST" class="space-y-4">
    <div class="mb-4">
      <label class="block text-sm font-medium text-white mb-1">Nombre Completo</label>
      <input type="text" name="nombre" class="w-full p-3 border border-gray-300 bg-gray-900 text-zinc-300 rounded-lg focus:ring-principal focus:border-principal shadow-sm" required>
    </div>
    <div class="mb-4">
      <label class="block text-sm font-medium text-white mb-1">Contraseña</label>
      <input type="password" name="password" class="w-full p-3 border border-gray-300 bg-gray-900 text-zinc-300 rounded-lg focus:ring-principal focus:border-principal shadow-sm" required>
    </div>
    <div class="mb-4">
      <label class="block text-sm font-medium text-white mb-1">Tipo</label>
      <select name="tipo" class="w-full p-3 border border-gray-300 bg-gray-900 text-zinc-300  rounded-lg focus:ring-principal focus:border-principal shadow-sm" required>
        <option value="estudiante" selected>Estudiante</option>
        <option value="docente">Docente</option>
      </select>
    </div>
    <div class="mb-4">
      <label class="block text-sm font-medium text-white mb-1">DNI / Matrícula</label>
      <input type="text" name="dni" class="w-full p-3 border border-gray-300 bg-gray-900 text-zinc-300 rounded-lg focus:ring-principal focus:border-principal shadow-sm" required>
    </div>
    
    <div class="flex space-x-4">
        <button type="submit" class="w-full bg-principal text-white font-semibold py-2 px-4 rounded-lg hover:bg-principal/90 transition duration-150 shadow-md">
            Guardar Usuario
        </button>
        <a href="index.php?view=usuarios" class="w-full text-center bg-gray-500 text-white font-semibold py-2 px-4 rounded-lg hover:bg-gray-600 transition duration-150 shadow-md">
            Volver
        </a>
    </div>
  </form>
</div>