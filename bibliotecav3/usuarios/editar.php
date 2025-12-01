<?php 
include './includes/conexion.php'; 
include './includes/login_check.php';
include './includes/rol_check.php';
requireRole(['docente']);

$id = intval($_GET['id'] ?? 0);
if ($id === 0) {
    header("Location: index.php?view=usuarios");
    exit;
}

// Lógica de actualización
if ($_POST) {
    $nombre = $conn->real_escape_string($_POST['nombre'] ?? '');
    $tipo = $conn->real_escape_string($_POST['tipo'] ?? 'estudiante');
    $dni = $conn->real_escape_string($_POST['dni'] ?? '');

    $sql = "UPDATE usuario SET nombre_completo='$nombre', tipo_usuario='$tipo', 
            dni_o_matricula='$dni'
            WHERE id_usuario=$id";

    if ($conn->query($sql)) {
      // Redirige al index del módulo usando el router principal
      header("Location: index.php?view=usuarios&msg=usuario_guardado");
      exit;
    } else {
      echo "<div class='p-6'><div class='bg-red-100 border-l-4 border-red-500 text-red-700 p-4' role='alert'>❌ Error al actualizar usuario: " . $conn->error . "</div></div>";
    }
}

// Consulta para obtener datos del usuario
$res = $conn->query("SELECT * FROM usuario WHERE id_usuario=$id");
if (!$usuario = $res->fetch_assoc()) {
    header("Location: index.php?view=usuarios&msg=error_no_encontrado");
    exit;
}
?>

<div class="p-6 max-w-lg mx-auto bg-white shadow-xl rounded-lg">
  <h1 class="text-3xl font-serif font-bold text-gray-800 mb-6">✏️ Editar Usuario #<?= $id ?></h1>
  <form method="POST" class="space-y-4">
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Completo</label>
      <input type="text" name="nombre" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-marron focus:border-marron shadow-sm" value="<?= htmlspecialchars($usuario['nombre_completo']) ?>" required>
    </div>
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
      <select name="tipo" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-marron focus:border-marron shadow-sm" required>
        <option value="estudiante" <?php if($usuario['tipo_usuario']=="estudiante") echo "selected"; ?>>Estudiante</option>
        <option value="docente" <?php if($usuario['tipo_usuario']=="docente") echo "selected"; ?>>Docente</option>
      </select>
    </div>
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-1">DNI / Matrícula</label>
      <input type="text" name="dni" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-marron focus:border-marron shadow-sm" value="<?= htmlspecialchars($usuario['dni_o_matricula']) ?>" required>
    </div>
    
    <div class="flex space-x-4">
        <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-150 shadow-md">
            Actualizar Usuario
        </button>
        <a href="index.php?view=usuarios" class="w-full text-center bg-gray-500 text-white font-semibold py-2 px-4 rounded-lg hover:bg-gray-600 transition duration-150 shadow-md">
            Volver
        </a>
    </div>
  </form>
</div>