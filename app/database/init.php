<?php

require_once __DIR__ . '/../../' . 'sphp/function.php';
require_once  __DIR__ . '/../../' . 'sphp/core/Database.php';

use Sphp\Core\Database;

function setupDatabaseFromSqlFiles($sqlDir) {
    try {
        // Database config
        $config = [
            'host' => env('DB_HOST'),
            'port' => env('DB_PORT'),
            'database' => env('DB_DATABASE'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD')
        ];

        $pdo = new PDO("mysql:host={$config['host']};port={$config['port']}", $config['username'], $config['password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbName = $config['database'];
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName`");
        $pdo->exec("USE `$dbName`");

        $db = new Database($config);

        $db->query("
            CREATE TABLE IF NOT EXISTS `migrations` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `migration` VARCHAR(255) NOT NULL,
                `executed_at` DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");

        $sqlFiles = array_filter(
            scandir($sqlDir),
            fn($file) => 
                is_file($sqlDir . DIRECTORY_SEPARATOR . $file) && 
                pathinfo($file, PATHINFO_EXTENSION) === 'sql' &&
                preg_match('/^\d{14}_/', $file) // Must start with 14-digit timestamp
        );

        if (empty($sqlFiles)) {
            echo "No valid .sql migration files found in $sqlDir\n";
            return;
        }

        sort($sqlFiles);

        $executed = $db->query("SELECT `migration` FROM `migrations`");
        $executedFiles = array_column($executed, 'migration');

        foreach ($sqlFiles as $sqlFile) {
            if (in_array($sqlFile, $executedFiles)) {
                echo "Skipping already executed migration: $sqlFile\n";
                continue;
            }

            $filePath = $sqlDir . DIRECTORY_SEPARATOR . $sqlFile;
            $sql = file_get_contents($filePath);

            if ($sql === false) {
                echo "Failed to read $sqlFile\n";
                continue;
            }

            if (trim($sql) === '') {
                echo "Skipping empty file: $sqlFile\n";
                continue;
            }

            echo "Executing $sqlFile...\n";
            
            $db->query($sql);

            $db->query("INSERT INTO `migrations` (`migration`) VALUES (:migration)", [
                'migration' => $sqlFile
            ]);

            echo "Successfully executed and recorded $sqlFile\n";
        }

        echo "Database migration complete.\n";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage() . "\n";
        exit(1);
    }
}

$sqlDir = __DIR__;
setupDatabaseFromSqlFiles($sqlDir);