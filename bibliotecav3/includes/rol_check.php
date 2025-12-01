<?php
function requireRole($roles = []) {
    $userRole = strtolower(trim($_SESSION['user_type'] ?? ''));
    $allowed  = array_map(fn($r) => strtolower(trim($r)), $roles);

    if (!in_array($userRole, $allowed)) {
        // Guardamos el mensaje en sesión y devolvemos al index
        $_SESSION['flash_error'] = "Acceso denegado: debes ser " . implode(" o ", $roles) . " para entrar aquí.";
        header("Location: /biblioteca/index.php");
        exit;
    }
}