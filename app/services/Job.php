<?php

namespace App\Services;

require_once './SMTPMailer.php';

// use App\Services\SMTPMailer;
use Exception;

class Job
{
    private $taskName;
    private $taskData;

    public function __construct($taskName, $taskData)
    {
        $this->taskName = $taskName;
        $this->taskData = $taskData;
    }

    public function getTaskName()
    {
        return $this->taskName;
    }

    public function getTaskData()
    {
        return $this->taskData;
    }

    // Execute the job
    public function execute()
    {
        // Check the task name to handle specific tasks
        if ($this->taskName === 'Send Email') {
            $this->sendEmail();
        }
    }

    private function sendEmail()
    {
        // Extract task data for the email
        $to = $this->taskData['email'];
        $subject = $this->taskData['subject'];
        $message = $this->taskData['message'];

        $mailer = SMTPMailer::getInstance();

        try {
            $mailer->sendEmail('no-reply@example.com', $to, $subject, $message);
            echo "Email sent successfully to $to with subject: $subject\n";
        } catch (Exception $e) {
            echo "Failed to send email to $to with subject: $subject. Error: " . $e->getMessage() . "\n";
        }
    }
}
