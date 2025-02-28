<?php

require_once 'Command.php';

$cli = new Command();

if ($argc > 1) {
    echo "Arguments received:\n";
    foreach (array_slice($argv, 1) as $index => $arg) {
        $cli->getCommand($arg);        
    }
} else {
    echo "No arguments provided.\n";
}

?>
