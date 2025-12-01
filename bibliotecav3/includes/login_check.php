<?php
// Verifica si NO hay una sesión activa.
// PHP_SESSION_NONE es una constante que indica que no hay sesión activa.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Lógica de verificación de login: si no hay 'user_id' en la sesión, redirige al login.
if (!isset($_SESSION['user_id'])) {
  // Asegúrate de que la ruta de redirección es correcta para tu nuevo árbol
  header("Location: /bibliotecav3/login.php"); 
  exit;
}