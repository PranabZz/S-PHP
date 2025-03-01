<?php



session_start();

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


function dd($arr)
{
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
    die();
}