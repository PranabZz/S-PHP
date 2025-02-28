<?php

include 'Job.php';
include 'JobQueue.php';

$jobQueue = new JobQueue();

$startTime = microtime(true);

for ($i = 1; $i <= 10000; $i++) {
    $jobQueue->addJob(new Job('Send Email ' . $i, ['email' => 'user' . $i . '@example.com', 'subject' => 'Welcome ' . $i]));
}

$endTime = microtime(true);

$totalTime = $endTime - $startTime;

echo "10000 jobs added to the queue.\n";
echo "Total time to add 10000 jobs: " . number_format($totalTime, 4) . " seconds.\n";


