<?php
include 'includes/conexion.php'; 
// No se usa login_check.php, sino que se inicia la sesión y se procesa el formulario aquí.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $dni_or_user = $conn->real_escape_string($_POST['dni_or_user'] ?? '');
  $password = $_POST['password'] ?? '';

  // Buscar por dni_o_matricula
  $sql = "SELECT id_usuario, nombre_completo, tipo_usuario, password 
          FROM usuario 
          WHERE dni_o_matricula = '$dni_or_user' LIMIT 1";
          
  $res = $conn->query($sql);
  
  if ($res && $res->num_rows === 1) {
    $row = $res->fetch_assoc();
    if (password_verify($password, $row['password'])) {
      // login ok
      $_SESSION['user_id'] = $row['id_usuario'];
      $_SESSION['user_name'] = $row['nombre_completo'];
      $_SESSION['user_type'] = $row['tipo_usuario'];
      header("Location: index.php");
      exit;
    } else {
      $err = 'Credenciales incorrectas.';
    }
  } else {
    $err = 'Credenciales incorrectas.';
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca - Iniciar Sesión</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="public/css/style.css" rel="stylesheet">
    
    <style>
        #login-bg {
            background-image: url('https://concentra.com.ar/wp-content/uploads/cuantos-libros-hay-en-la-biblioteca-de-hogwarts.webp');
        }
    </style>
</head>
<body class="h-screen flex items-center justify-center">

    <div 
        id="login-bg"
        class="absolute inset-0 bg-cover bg-center"
    >
        <div class="absolute inset-0 bg-gray-900 opacity-70"></div>
    </div>

    <div class="z-10 w-full max-w-md bg-gray-900 opacity-80 rounded-xl shadow-2xl overflow-hidden">
        <div class="p-8">
            <h2 class="text-3xl font-serif font-extrabold text-white text-center mb-2">
                Acceso al Sistema
            </h2>
            <p class="text-center text-zinc-400 mb-8">
                Ingresa tus credenciales
            </p>

            <?php if($err): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <?=htmlspecialchars($err)?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-4">
                    <label for="dni_or_user" class="block text-sm font-medium text-zinc-300 mb-1">DNI / Matrícula</label>
                    <input 
                        type="text" 
                        id="dni_or_user" 
                        name="dni_or_user" 
                        required 
                        class="w-full p-3 bg-gray-800 border border-zinc-600 text-zinc-400 rounded-lg focus:ring-1 focus:ring-principal focus:border-principal shadow-sm"
                    >
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-zinc-300 mb-1">Contraseña</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required 
                        class="w-full p-3 bg-gray-800 border border-zinc-600 text-zinc-400 rounded-lg focus:ring-1 focus:ring-principal focus:border-principal shadow-sm"
                    >
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-principal text-white p-3 rounded-lg font-semibold hover:bg-principal/90 transition duration-150 shadow-lg"
                >
                    Iniciar Sesión
                </button>
            </form>
        </div>
    </div>

</body>
</html>