<?php



if(!isset($_SESSION)) session_start();

error_reporting(E_ALL & ~E_WARNING);
ini_set('display_errors', 1);

register_shutdown_function(function () {

    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE])) {
        
        error_log("Fatal Error: " . $error['message'] . " in " . $error['file'] . " on line " . $error['line']);
        
        $errorMessage = urlencode("Error: " . $error['message'] . " in " . $error['file'] . " on line " . $error['line']);
        
        header("Location: error.html?error=$errorMessage");
        
        exit;
    }

});

function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

function csrf() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token']) . '">';
}

function validateCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function redirect($url, $message = "")
{
    if (!empty($message)) {
        $_SESSION['message'] = $message;
    }
    header("Location:" . $url);
}

function loadEnv($filePath) {
    if (!file_exists($filePath)) {
        dd("ENV FILE NOT FOUND at $filePath");
    }

    $envData = [];

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);

        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }

        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);

        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
        putenv("$key=$value");

        $envData[$key] = $value;
    }


    return true;
}


loadEnv(__DIR__ . '/.env');

function env($key, $default = null) {
    if (isset($_ENV[$key])) {
        return $_ENV[$key];
    }

    $value = getenv($key);
    return $value !== false ? $value : $default;
}


function dd($arr)
{
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
    die();
}