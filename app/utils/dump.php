<?php

include_once '../services/JobQueue.php';
include_once '../services/Job.php';

$jobQueue = new JobQueue();

$startTime = microtime(true);

// Add 10 different jobs to send different emails with unique content
for ($i = 1; $i <= 10; $i++) {
    $jobQueue->addJob(new Job('Send Email', [
        'email' => 'pranabkc191@gmail.com',
        'subject' => 'Message ' . $i,
        'message' => "This is message number $i. Thank you for reading!"
    ]));
}

$endTime = microtime(true);

$totalTime = $endTime - $startTime;

echo "10 jobs added to the queue.\n";
echo "Total time to add 10 jobs: " . number_format($totalTime, 4) . " seconds.\n";


