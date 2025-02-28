<?php
include 'Job.php';
include 'JobQueue.php';

$jobQueue = new JobQueue();

echo "No jobs to process.\n";

while (true) {
    $jobs = $jobQueue->getJobs();
    if (empty($jobs)) {

        continue;
    }
    $jobData = $jobs[0];
    $job = new Job($jobData['taskName'], $jobData['taskData']);
    $job->execute();
    $jobQueue->removeJob(0);
    echo "Job processed and removed from the queue.\n";
    sleep(1);
}