<?php
if (session_status() === PHP_SESSION_NONE) session_start();
session_unset();
session_destroy();

// Redirige a la pantalla de login (que ahora debe estar en la raíz)
header("Location: ../login.php"); 
exit;
?>