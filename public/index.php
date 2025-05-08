<?php

require_once '../sphp/function.php';
require_once '../vendor/autoload.php';

// Autoloader
spl_autoload_register(function ($class) {
    $base_dir = __DIR__ . '/../'; 
    $class_parts = explode('\\', $class);
    $class_parts[0] = strtolower($class_parts[0]); // app
    $class_parts[1] = strtolower($class_parts[1]); // core
    $class_path = implode('/', $class_parts) . '.php';
    $file = $base_dir . $class_path;
    if (file_exists($file)) {
        require_once $file;
    } else {
        exit("Error: Unable to load class '$class'. Expected file at $file not found.");
    }
});


$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);


if (strpos($requestUri, '/api') === 0) {
    require_once __DIR__ . '/../app/router/api.php';
    
} else {
    require_once __DIR__ . '/../app/router/web.php';
  
}
