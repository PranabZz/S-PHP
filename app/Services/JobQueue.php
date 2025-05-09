<?php

namespace App\Services;
use Exception;

class JobQueue
{
    private $queueFile = __DIR__ . '/jobs.json';

    public function addJob(Job $job)
    {
        $jobs = $this->getJobs();

        $jobs[] = [
            'id' => uniqid('job_', true),
            'taskName' => $job->getTaskName(),
            'taskData' => $job->getTaskData(),
        ];

        $this->saveJobs($jobs);
    }

    public function getJobs()
    {
        if (!file_exists($this->queueFile)) {
            return [];
        }

        $jobs = json_decode(file_get_contents($this->queueFile), true);

        return is_array($jobs) ? $jobs : [];
    }

    private function saveJobs(array $jobs)
    {
        $json = json_encode($jobs, JSON_PRETTY_PRINT);

        if ($json === false) {
            throw new Exception("Failed to encode jobs to JSON: " . json_last_error_msg());
        }

        $result = @file_put_contents($this->queueFile, $json);

        if ($result === false) {
            $error = error_get_last();
            throw new Exception("Failed to write to file '{$this->queueFile}': " . ($error['message'] ?? 'Unknown error'));
        }
    }


    public function removeJobById(string $id)
    {
        $jobs = $this->getJobs();

        $jobs = array_filter($jobs, function ($job) use ($id) {
            return $job['id'] !== $id;
        });

        $this->saveJobs(array_values($jobs));
    }

    public function executeJobs()
    {
        $jobs = $this->getJobs();

        foreach ($jobs as $job) {
            if (!isset($job['taskName'], $job['taskData'], $job['id'])) {
                continue;
            }

            $jobInstance = new Job($job['taskName'], $job['taskData']);

            $_SESSION['message'] = 'Mail sent successfully please check your email';
            $_SESSION['type'] = 'success';
            $jobInstance->execute();

            $this->removeJobById($job['id']);
        }
    }
}
