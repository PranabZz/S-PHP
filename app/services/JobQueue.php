<?php

namespace App\Services;


class JobQueue
{
    private $queueFile = __DIR__ . '/jobs.json';

    // Add a job to the queue
    public function addJob(Job $job)
    {
        $jobs = $this->getJobs();
        
        // Add only the necessary data (task name and task data) to the queue
        $jobs[] = [
            'taskName' => $job->getTaskName(),
            'taskData' => $job->getTaskData(),
        ];

        // Save the jobs to the file
        file_put_contents($this->queueFile, json_encode($jobs));
    }

    // Retrieve all jobs from the queue
    public function getJobs()
    {
        if (!file_exists($this->queueFile)) {
            return [];
        }

        $jobs = json_decode(file_get_contents($this->queueFile), true);
        return $jobs ?: [];
    }

    // Remove a job from the queue by its index
    public function removeJob($index)
    {
        $jobs = $this->getJobs();
        unset($jobs[$index]);

        // Re-index the array and save it back to the file
        $jobs = array_values($jobs);
        file_put_contents($this->queueFile, json_encode($jobs));
    }

    // Execute all jobs in the queue
    public function executeJobs()
    {
        $jobs = $this->getJobs();

        foreach ($jobs as $index => $job) {
            // Recreate the Job instance from the stored data
            $jobInstance = new Job($job['taskName'], $job['taskData']);

            echo "Executing job: " . $job['taskName'] . "\n";
            $jobInstance->execute();

            // After executing the job, remove it from the queue
            $this->removeJob($index); 
        }
    }
}
