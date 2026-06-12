<?php


session_start();

// Load configuration and helpers
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/helpers.php';

// Simple autoloader for classes
spl_autoload_register(function (string $class): void {
  $paths = [
    __DIR__ . '/../src/Controller/' . $class . '.php',
    __DIR__ . '/../src/Entity/' . $class . '.php',
    __DIR__ . '/../src/Repository/' . $class . '.php',
    __DIR__ . '/../src/Enum/' . $class . '.php',
  ];

  foreach ($paths as $path) {
    if (file_exists($path)) {
      require_once $path;
      return;
    }
  }
});

// Parse route: controller/action
$route = $_GET['route'] ?? 'dashboard';
$parts = explode('/', $route);
$controllerName = ucfirst($parts[0] ?? 'dashboard') . 'Controller';
$action = $parts[1] ?? 'index';

// Public routes that don't require login
$publicRoutes = ['auth/login', 'auth/authenticate'];

// Protect all pages except login
if (!in_array($route, $publicRoutes, true) && !isLoggedIn()) {
  redirect('auth/login');
}

// Dispatch to controller
if (!class_exists($controllerName)) {
  http_response_code(404);
  echo 'Page non trouvée.';
  exit;
}

$controller = new $controllerName();

if (!method_exists($controller, $action)) {
  http_response_code(404);
  echo 'Action non trouvée.';
  exit;
}

$controller->$action();
