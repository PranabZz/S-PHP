<?php



if (!isset($_SESSION))
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


function asset($path)
{
    return '/public/' . ltrim($path, '/');
}


function sanitizeHtml($input)
{
    if (is_array($input)) {
        return array_map('sanitizeHtml', $input);
    }

    // Safely cast to string to avoid null warnings
    $input = (string) $input;

    $input = html_entity_decode($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');

    $allowedTags = '<p><br><b><strong><i><em><ul><ol><li><a><img><blockquote><span><div><h1><h2><h3><h4><h5><h6>';

    // Remove script and style tags
    $input = preg_replace('#<(script|style)\b[^>]*>.*?</\1>#is', '', $input);

    // Strip tags except allowed ones
    $clean = strip_tags($input, $allowedTags);

    // Remove event handlers and inline styles
    $clean = preg_replace('/(<[^>]+)(\s*on\w+\s*=\s*(".*?"|\'.*?\'|[^\s>]+))/i', '$1', $clean);
    $clean = preg_replace('/(<[^>]+)(\s*style\s*=\s*(".*?"|\'.*?\'|[^\s>]+))/i', '$1', $clean);

    // Clean href/src attributes
    $clean = preg_replace_callback('/(<[^>]+?\s(?:href|src)\s*=\s*)(["\']?)(.*?)(\2)/i', function ($matches) {
        $attrStart = $matches[1];
        $quote = $matches[2];
        $url = trim($matches[3]);

        if (stripos($attrStart, 'src=') !== false && preg_match('/^data:image\/(png|jpeg|jpg|gif|webp);base64,[a-z0-9\/+=]+$/i', $url)) {
            return $matches[0];
        }

        if (preg_match('/^(javascript:|data:)/i', $url)) {
            return $attrStart . $quote . '#' . $quote;
        }

        return $matches[0];
    }, $clean);

    return trim($clean);
}



function csrf()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token']) . '">';
}

function validateCsrfToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function redirect($url, $message = "")
{
    if (!empty($message)) {
        $_SESSION['message'] = $message;
    }
    header("Location: " . $url);
}


function loadEnv($filePath)
{
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


loadEnv(__DIR__ . '/../.env');

function env($key, $default = null)
{
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