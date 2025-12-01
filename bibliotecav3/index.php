<?php
ob_start();

include 'includes/conexion.php'; 
include 'includes/login_check.php'; 

// ----------------------------------------------------
// 1. CONFIGURACIÓN DEL LAYOUT Y ROUTER
// ----------------------------------------------------

$usuario_logueado = $_SESSION['user_name'] ?? 'Usuario';
$rol = $_SESSION['user_type'] ?? 'estudiante';
$avatar_url = "https://ui-avatars.com/api/?name=" . urlencode($usuario_logueado) . "&background=8B4513&color=fff";
$current_view = $_GET['view'] ?? 'inicio';

// Define las rutas base para los archivos de módulo
$routes = [
    'inicio'          => 'views/dashboard.php', // Crearemos un dashboard simple
    
    // Libros
    'libros'          => 'libros/index.php',
    'libros-crear'    => 'libros/crear.php',
    'libros-editar'   => 'libros/editar.php',
    'libros-detalles' => 'libros/detalles.php',

    // Préstamos
    'prestamos'       => 'prestamos/index.php',
    'prestamos-crear' => 'prestamos/crear.php',

    // Bajas (Solo docente)
    'bajas'           => 'bajas/index.php',
    'bajas-crear'     => 'bajas/crear.php',

    // Usuarios (Solo docente)
    'usuarios'        => 'usuarios/index.php',
    'usuarios-crear'  => 'usuarios/crear.php',
    'usuarios-editar' => 'usuarios/editar.php',
];

// Lógica de navegación basada en el rol
if ($rol == "docente") {
    $menu_items = [
        ['nombre' => 'Inicio', 'icono' => '🏠', 'view' => 'inicio'],
        ['nombre' => 'Libros', 'icono' => '📚', 'view' => 'libros'],
        ['nombre' => 'Usuarios', 'icono' => '👥', 'view' => 'usuarios'],
        ['nombre' => 'Préstamos', 'icono' => '📖', 'view' => 'prestamos'],
        ['nombre' => 'Bajas', 'icono' => '🗑️', 'view' => 'bajas'],
    ];
} else { // Estudiante
    $menu_items = [
        ['nombre' => 'Inicio', 'icono' => '🏠', 'view' => 'libros'],
        ['nombre' => 'Préstamos', 'icono' => '📖', 'view' => 'prestamos'],
        ['nombre' => 'Nuevo Préstamo', 'icono' => '➕', 'view' => 'prestamos-crear'],
    ];
}

// ----------------------------------------------------
// 2. FUNCIÓN DE RENDERIZADO DE CONTENIDO (DEL MÓDULO)
// ----------------------------------------------------
function render_content($view, $routes) {
    if (isset($routes[$view]) && file_exists($routes[$view])) {
        // Incluye el archivo del módulo (que solo tiene PHP y el HTML del contenido)
        include $routes[$view];
    } else {
        // Dashboard por defecto o vista de error 404
        echo '<div class="p-6">
                <h1 class="text-4xl font-serif font-bold text-red-600 mb-4">Página no encontrada (404)</h1>
                <p class="text-gray-600">La vista solicitada no existe.</p>
              </div>';
    }
}

// ----------------------------------------------------
// 3. ESTRUCTURA HTML PRINCIPAL (TAILWIND LAYOUT)
// ----------------------------------------------------
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca - <?= ucwords(str_replace('-', ' ', $current_view)) ?></title>
    <link href="public/css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <script src="public/js/main.js" defer></script>
</head>
<body class="flex h-screen overflow-hidden">

    <aside id="sidebar" class="fixed bg-principal text-white top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
        <a href="index.php" class="text-white flex items-center space-x-2 px-4">
            <span class="text-3xl py-6 font-serif font-extrabold tracking-widest">BIBLIOTECA</span>
        </a>

        <nav>
            <?php foreach ($menu_items as $item): ?>
                <?php $is_active = $current_view === $item['view']; ?>
                <a href="index.php?view=<?= $item['view'] ?>" class="block py-2.5 px-4 mb-3 rounded transition duration-200 hover:bg-white hover:text-principal <?= $is_active ? 'bg-white text-principal font-semibold shadow-md' : 'text-gray-100 hover:bg-white/10' ?>">
                    <span class="mr-3"><?= $item['icono'] ?></span>
                    <?= $item['nombre'] ?>
                </a>
            <?php endforeach; ?>
        </nav>
        
        <?php if ($rol == 'docente' && $current_view == 'inicio'): ?>
            <div class="px-4 pt-6 border-t border-principal-dark/30 mt-10">
                <h3 class="text-sm font-semibold mb-2 text-zinc-300 uppercase">Utilidades Excel</h3>
                <form action="excel.php" method="POST" enctype="multipart/form-data" class="space-y-2">
                    <label class="block text-sm text-zinc-300">Importar Libros</label>
                    <input type="file" name="archivo_excel" accept=".xls,.xlsx" required class="w-full text-xs text-gray-100 border border-gray-500 rounded p-1 file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-white/10 file:text-white hover:file:bg-white/20">
                    <button type="submit" name="importar" class="w-full bg-yellow-600 text-white text-sm font-semibold py-1.5 rounded-lg hover:bg-yellow-700 transition duration-150">Subir</button>
                </form>

                <form action="excel.php" method="POST" class="mt-4">
                    <label class="block text-sm text-zinc-300">Exportar Libros</label>
                    <button type="submit" name="exportar" class="w-full bg-green-600 text-white text-sm font-semibold py-1.5 rounded-lg hover:bg-green-700 transition duration-150">Descargar</button>
                </form>
            </div>
        <?php endif; ?>
    </aside>

    <div class="flex-1 flex flex-col overflow-hidden">
        
        <header class="flex items-center justify-between p-4 bg-fondo-base border-b border-black shadow-sm sticky top-0 z-10">
             
            <button data-drawer-target="sidebar" data-drawer-toggle="sidebar" aria-controls="sidebar" type="button" class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                <span class="sr-only">Open sidebar</span>
                <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                </svg>
            </button>

            <div class="hidden md:block">
                <h1 class="text-2xl font-serif font-bold text-white">
                    <?= ucwords(str_replace('-', ' ', $current_view)) ?>
                </h1>
            </div>


            <!-- Buscador de Libros -->
            <?php if ($current_view == "libros"): ?>
            
            <div class="flex justify-center p-4 bg-[#0e1a40]">
                <div class="w-full max-w-xl">
                    <form class="flex items-center shadow-lg rounded-full overflow-hidden" action="index.php?view=libros" method="post">
                        
                        <input type="text" placeholder="Busca un titulo..." class="w-full px-6 py-3 text-lg bg-principal text-secundario focus:outline-none focus:ring-2 focus:ring-[#946b2d] placeholder:text-[#5d5d5d]/70 transition duration-150 ease-in-out" name="buscador">
                        
                        <button type="submit" class="flex items-center justify-center p-3 text-white bg-[#222f5b] hover:bg-[#946b2d] transition duration-300 ease-in-out" aria-label="Buscar">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                        
                    </form>
                </div>
            </div>

            <?php endif; ?>
            <!-- Buscador de Libros fin -->
            
            <!-- Buscador de Usuarios -->
            <?php if ($current_view == "usuarios"): ?>
            
            <div class="flex justify-center p-4 bg-[#0e1a40]">
                <div class="w-full max-w-xl">
                    <form class="flex items-center shadow-lg rounded-full overflow-hidden" action="index.php?view=usuarios" method="post">
                        
                        <input type="text" placeholder="Busca un nombre..." class="w-full px-6 py-3 text-lg bg-principal text-secundario focus:outline-none focus:ring-2 focus:ring-[#946b2d] placeholder:text-[#5d5d5d]/70 transition duration-150 ease-in-out" name="buscador">
                        
                        <button type="submit" class="flex items-center justify-center p-3 text-white bg-[#222f5b] hover:bg-[#946b2d] transition duration-300 ease-in-out" aria-label="Buscar">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                        
                    </form>
                </div>
            </div>

            <?php endif; ?>
            <!-- Buscador de Usuarios fin -->
                
            <!-- Buscador de Prestamos -->
            <?php if ($current_view == "prestamos"): ?>
            
            <div class="flex justify-center p-4 bg-[#0e1a40]">
                <div class="w-full max-w-xl">
                    <form class="flex items-center shadow-lg rounded-full overflow-hidden" action="index.php?view=prestamos" method="post">
                        
                        <input type="text" placeholder="Busca un libro..." class="w-full px-6 py-3 text-lg bg-principal text-secundario focus:outline-none focus:ring-2 focus:ring-[#946b2d] placeholder:text-[#5d5d5d]/70 transition duration-150 ease-in-out" name="buscador">
                        
                        <button type="submit" class="flex items-center justify-center p-3 text-white bg-[#222f5b] hover:bg-[#946b2d] transition duration-300 ease-in-out" aria-label="Buscar">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                        
                    </form>
                </div>
            </div>

            <?php endif; ?>
            <!-- Buscador de Prestamos fin -->

            <!-- Buscador de Bajas -->
            <?php if ($current_view == "bajas"): ?>
            
            <div class="flex justify-center p-4 bg-[#0e1a40]">
                <div class="w-full max-w-xl">
                    <form class="flex items-center shadow-lg rounded-full overflow-hidden" action="index.php?view=bajas" method="post">
                        
                        <input type="text" placeholder="Busca un libro..." class="w-full px-6 py-3 text-lg bg-principal text-secundario focus:outline-none focus:ring-2 focus:ring-[#946b2d] placeholder:text-[#5d5d5d]/70 transition duration-150 ease-in-out" name="buscador">
                        
                        <button type="submit" class="flex items-center justify-center p-3 text-white bg-[#222f5b] hover:bg-[#946b2d] transition duration-300 ease-in-out" aria-label="Buscar">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                        
                    </form>
                </div>
            </div>

            <?php endif; ?>
            <!-- Buscador de Bajas fin -->

            <div class="flex items-center space-x-4 relative">
                <div class="flex items-center space-x-2 cursor-pointer p-2 rounded-full hover:bg-secundario/90 transition duration-150" id="user-menu-toggle">
                    <img src="<?= $avatar_url ?>" alt="Avatar" class="w-8 h-8 rounded-full border-2 border-marron">
                    <span class="hidden sm:inline text-white font-medium"><?= $usuario_logueado ?></span>
                </div>

                <div id="user-menu" class="absolute right-0 top-12 mt-2 w-48 bg-principal rounded-lg shadow-xl py-1 hidden transition duration-150 z-20">
                    <div class="border-t border-black my-1"></div>
                    <a href="includes/logout.php" class="block px-4 py-2 text-red-600 hover:bg-red-900">
                        🚪 Cerrar Sesión
                    </a>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-fondo-base">
            <div class="p-4 sm:ml-64">
                <?php render_content($current_view, $routes); ?>
            </div>
        </main>
    </div>

    <?php
    // Manejo de mensajes Flash (errores de rol, etc.)
    if (isset($_SESSION['flash_error'])): ?>
        <div class="fixed top-4 right-4 bg-red-600 text-white p-4 rounded-lg shadow-xl z-50">
            <?= htmlspecialchars($_SESSION['flash_error']) ?>
        </div>
        <?php unset($_SESSION['flash_error']);
    endif;
    
    // Manejo de mensajes Flash (éxito)
    if (isset($_GET['msg'])): 
        $msg = $_GET['msg'];
        $message = match($msg) {
            'baja_registrada' => '✅ Baja registrada correctamente.',
            'baja_eliminada' => '✅ Baja anulada correctamente.',
            'libro_guardado' => '✅ Libro guardado/actualizado correctamente.',
            'libro_eliminado' => '✅ Libro y sus relaciones eliminados correctamente.',
            'prestamo_registrado' => '✅ Préstamo registrado. El libro está ahora como prestado.',
            'devolucion_registrada' => '✅ Devolución registrada. El libro está ahora disponible.',
            'usuario_guardado' => '✅ Usuario guardado/actualizado correctamente.',
            'usuario_eliminado' => '✅ Usuario eliminado correctamente.',
            default => '✅ Operación realizada con éxito.',
        };
        $color = match($msg) {
            'baja_eliminada', 'libro_eliminado', 'usuario_eliminado' => 'bg-red-600',
            default => 'bg-green-600',
        };
        ?>
        <div class="fixed top-4 right-4 text-white p-4 rounded-lg shadow-xl z-50 <?= $color ?>">
            <?= $message ?>
        </div>
    <?php endif; ?>

</body>
</html>