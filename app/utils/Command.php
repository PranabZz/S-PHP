<?php
class Command
{
    public $command = [
        'up' => 'cd ../../ && php -S localhost:8000',
        'work' => 'php worker.php',
    ];

    public function getCommand($stm)
    {
        if (isset($this->command[$stm])) {
            $commandExist = $this->command[$stm];
            echo "Starting worker in background: " . $commandExist . "\n";
            shell_exec($commandExist);
        } else {
            echo "Command not found.\n";
        }
    }
}