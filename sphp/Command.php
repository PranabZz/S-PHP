<?php

/**
 * Command Line Interface Handler
 *  Given by GPT and modified by Pranab
 *
 * Handles CLI commands for creating files and running server processes
 */
class Command
{
    /**
     * Available system commands
     * 
     * @var array<string, string>
     */
    private array $commands = [
        'up' => 'cd public && php -S localhost:8000',
        'work' => 'php ' . __DIR__ . '/../app/services/work',
        'migrate' => 'php ' . __DIR__ . '/../app/database/init.php'
    ];


    /**
     * Available file creation commands
     * 
     * @var array<string, array>
     */
    private array $fileCommands = [
        'views' => [
            'path' => '../app/views/{name}.php',
            'template' => '<!-- View File: {basename} -->',
            'type' => null,
            'namespace' => null
        ],
        'migration' => [
            'path' => '../app/database/{date}_{name}.sql',
            'template' => '--Your sql code',
            'type' => null,
            'namespace' => null
        ],
        'controller' => [
            'path' => '../app/controllers/{name}.php',
            'type' => 'Controller',
            'namespace' => 'Sphp\\Controllers',
            'baseClass' => 'Sphp\\Controllers\\Controller'
        ],
        'middleware' => [
            'path' => '../app/middleware/{name}.php',
            'type' => 'Middleware',
            'namespace' => 'Sphp\\Middleware',
            'baseClass' => 'Sphp\\Middleware\\Middleware'
        ],
        'model' => [
            'path' => '../app/models/{name}.php',
            'type' => 'Model',
            'namespace' => 'Sphp\\Models',
            'baseClass' => 'Sphp\\Core\\Models'
        ]
    ];

    /**
     * Execute the requested command
     * 
     * @param string $input Command string from CLI
     * @return void
     */
    public function execute(string $input): void
    {
        $parts = explode(' ', trim($input));
        $type = $parts[0] ?? null;
        $name = $parts[1] ?? null;

        if (empty($type)) {
            $this->showError("Invalid command! Use: php do [command] [name]");
            return;
        }

        if ($type === 'help') {
            $this->showHelp();
            return;
        }

        if (isset($this->commands[$type])) {
            $this->runCommand($type);
            return;
        }

        if (isset($this->fileCommands[$type])) {
            $this->createFile($type, $name);
            return;
        }

        $this->showError("Command not found. Type 'php do help' to see available commands.");
    }

    /**
     * Run system command
     * 
     * @param string $cmd Command key
     * @return void
     */
    private function runCommand(string $cmd): void
    {
        $this->showSuccess("Running: " . $this->commands[$cmd]);
        // Use passthru to show command output in real-time
        passthru($this->commands[$cmd]);
    }

    /**
     * Create file based on command type
     * 
     * @param string $type File type to create
     * @param string|null $name Name/path for the file
     * @return void
     */
    private function createFile(string $type, ?string $name): void
    {
        if (empty($name)) {
            $this->showError("Missing name parameter! Use: php do {$type} [name]");
            return;
        }

        $config = $this->fileCommands[$type];
        $date = date('YmdHis');
        $filePath = str_replace('{date}', $date, $config['path']);

        $filePath = str_replace('{name}', $name, $filePath);
        $fullPath = $this->getFullPath($filePath);
        $dirPath = dirname($fullPath);

        // Create directory structure if needed
        if (!is_dir($dirPath)) {
            if (!mkdir($dirPath, 0777, true)) {
                $this->showError("Failed to create directory: {$dirPath}");
                return;
            }
            $this->showInfo("Created directory: {$dirPath}");
        }

        // Check if file already exists
        if (file_exists($fullPath)) {
            $this->showInfo("File already exists: {$fullPath}");
            return;
        }

        // Generate content based on type
        if ($type === 'views' || $type === 'migration') {
            $basename = basename($name);
            $content = str_replace('{basename}', $basename, $config['template']);
        } else {
            list($namespace, $className, $baseClassName, $baseClassFullName) = $this->resolveNamespace($name, $config);
            $content = $this->generateClassContent($namespace, $className, $baseClassName, $baseClassFullName, $config['type']);
        }

        // Write file
        if (file_put_contents($fullPath, $content) === false) {
            $this->showError("Failed to write file: {$fullPath}");
            return;
        }

        $this->showSuccess("Created {$type} file: {$fullPath}");
    }

    /**
     * Resolve namespace and class information based on path
     * 
     * @param string $path Path containing class name and potential subnamespace
     * @param array $config Configuration for the file type
     * @return array Array containing [namespace, className, baseClassName, baseClassFullName]
     */
    private function resolveNamespace(string $path, array $config): array
    {
        // Extract class name from path
        $className = basename($path);

        // Check if path contains directories (indicating a sub-namespace)
        $pathParts = explode('/', $path);
        $className = array_pop($pathParts); // Remove and get the last part (class name)

        // Default namespace is the base one from config
        $namespace = $config['namespace'];

        // If we have subdirectories, extend the namespace
        if (!empty($pathParts)) {
            $subNamespace = implode('\\', array_map('ucfirst', $pathParts));
            $namespace .= '\\' . $subNamespace;
        }
        // Get base class information
        $baseClassFullName = $config['baseClass'];
        $baseClassParts = explode('\\', $baseClassFullName);
        $baseClassName = end($baseClassParts);
        return [$namespace, $className, $baseClassName, $baseClassFullName];
    }

    /**
     * Generate class content with namespace and use statements
     * 
     * @param string $namespace Namespace for the class
     * @param string $className Class name
     * @param string $baseClassName Parent class name (short)
     * @param string $baseClassFullName Parent class full name with namespace
     * @param string $type Class type
     * @return string Generated class content
     */
    private function generateClassContent(string $namespace, string $className, string $baseClassName, string $baseClassFullName, string $type): string
    {
        $content = "<?php\n\nnamespace {$namespace};\n\n";
        $content .= "use {$baseClassFullName};\n\n";
        $content .= "/**\n * {$className} {$type}\n */\nclass {$className} extends {$baseClassName}\n{\n    // TODO: Implement {$type} functionality\n}\n";
        return $content;
    }

    /**
     * Get full file path
     * 
     * @param string $path Relative path
     * @return string Absolute path
     */
    private function getFullPath(string $path): string
    {
        return rtrim(__DIR__, '/') . '/' . ltrim($path, '/');
    }

    /**
     * Show help information
     * 
     * @return void
     */
    private function showHelp(): void
    {
        echo "📌 Available Commands:\n";
        echo "🔹 php do up               → Start PHP built-in server (localhost:8000)\n";
        echo "🔹 php do work             → Run job worker\n";
        echo "🔹 php do views path       → Create a view file (e.g., 'do views home/index')\n";
        echo "🔹 php do controller Name  → Create a controller (e.g., 'do controller User' or 'do controller Admin/User')\n";
        echo "🔹 php do middleware Name  → Create a middleware\n";
        echo "🔹 php do model Name       → Create a model\n";
        echo "🔹 php do help             → Show this help menu\n";
        echo "🔹 php do migration Name   → Create a migration file\n";
    }

    /**
     * Show success message
     * 
     * @param string $message Message to display
     * @return void
     */
    private function showSuccess(string $message): void
    {
        echo "🚀 {$message}\n";
    }

    /**
     * Show information message
     * 
     * @param string $message Message to display
     * @return void
     */
    private function showInfo(string $message): void
    {
        echo "ℹ️ {$message}\n";
    }

    /**
     * Show error message
     * 
     * @param string $message Message to display
     * @return void
     */
    private function showError(string $message): void
    {
        echo "❌ {$message}\n";
    }
}