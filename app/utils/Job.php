<?php

class Job
{
    public $taskName;
    public $taskData;

    public function __construct($taskName, $taskData)
    {
        $this->taskName = $taskName;
        $this->taskData = $taskData;
    }

    // Example of a method that executes a task
    public function execute()
    {
        echo "Executing task: " . $this->taskName . "\n";
    }

    
}
