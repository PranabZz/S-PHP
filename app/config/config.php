<?php

/* 
    Here we keep our database host and the database we will be using for the project
*/

function loadEnv($filePath) {
    if (!file_exists($filePath)) {
        return false;
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);

        if (!array_key_exists($key, $_ENV)) {
            $_ENV[$key] = $value;
        }
    }

    return true;
}

loadEnv( '../../.env');


return [
    'host' => $_ENV['DB_HOST'] ?? 'localhost',       
    'database' => $_ENV['DB_DATABASE'] ?? 'start',   
    'username' => $_ENV['DB_USERNAME'] ?? 'root',    
    'password' => $_ENV['DB_PASSWORD'] ?? 'root',    
    'smtpHost' => $_ENV['MAIL_HOST'] ?? 'smtp.gmail.com', 
    'smtpPort' => $_ENV['MAIL_PORT'] ?? 587,         // Default to 587 if not found
    'smtpUsername' => $_ENV['MAIL_USERNAME'] ?? '',  // Default to empty string if not found
    'smtpPassword' => $_ENV['MAIL_PASSWORD'] ?? '',  // Default to empty string if not found
];
 