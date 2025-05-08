<?php

namespace App\Services;

class SendMail
{
    public static function sendMail($to, $subject, $templateName, $data = [])
    {
        $templatePath = self::resolveTemplatePath($templateName);
        $message = self::renderTemplate($templatePath, $data);
        $jobQueue = new JobQueue();
        $jobQueue->addJob(new Job('Send Email', [
            'email' => $to,
            'subject' => $subject,
            'message' => $message,
            'is_html' => true, 
        ]));

        $jobQueue->executeJobs();
  
    }

    private static function resolveTemplatePath($templateName)
    {
        if (!str_ends_with($templateName, '.php')) {
            $templateName .= '.php';
        }

   
        return realpath(__DIR__ . '/../views/mail/' . $templateName);
    }

    private static function renderTemplate($templatePath, $data)
    {
        if (!file_exists($templatePath)) {
            throw new \Exception("Email template not found: " . $templatePath);
        }

        extract($data);

        ob_start();
        include $templatePath;
        return ob_get_clean();
    }
}
