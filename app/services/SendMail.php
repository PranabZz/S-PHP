<?php

namespace App\Services;


class SendMail
{
    public static function sendMail($to, $subject, $message)
    {
        $jobQueue = new JobQueue();
        $jobQueue->addJob(new Job('Send Email', [
            'email' => $to,
            'subject' => $subject,
            'message' => $message,
        ]));
    }
}