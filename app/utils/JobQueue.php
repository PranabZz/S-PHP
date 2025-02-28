<?php

class JobQueue
{
    private $queueFile = 'job_queue.json';

    public function addJob(Job $job)
    {
        $jobs = $this->getJobs();
        $jobs[] = $job; 

        file_put_contents($this->queueFile, json_encode($jobs));
    }

    public function getJobs()
    {
        if (!file_exists($this->queueFile)) {
            return [];
        }

        $jobs = json_decode(file_get_contents($this->queueFile), true);

        return $jobs ?: [];
    }

    // Remove a job from the queue (after processing it)
    public function removeJob($index)
    {
        $jobs = $this->getJobs();
        unset($jobs[$index]);

        // Re-index the array and save back to the file
        $jobs = array_values($jobs);
        file_put_contents($this->queueFile, json_encode($jobs));
    }
}
