<?php

namespace Sphp\Services;
use Exception;

class SMTPMailer
{
    private static $instance = null;
    private $smtpHost;
    private $smtpPort;
    private $smtpUsername;
    private $smtpPassword;
    private $connection;
    private $timeout = 30;

    private function __construct($smtpHost, $smtpPort, $smtpUsername, $smtpPassword)
    {
        $this->smtpHost = $smtpHost;
        $this->smtpPort = (int)$smtpPort;
        $this->smtpUsername = $smtpUsername;
        $this->smtpPassword = $smtpPassword;
        $this->connection = null;
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            $config = include '../../sphp/function.php';

            $smtpHost = env('MAIL_HOST', $config['smtpHost'] ?? 'smtp.gmail.com');
            $smtpPort = env('MAIL_PORT', $config['smtpPort'] ?? 587);
            $smtpUsername = env('MAIL_USERNAME', $config['smtpUsername'] ?? '');
            $smtpPassword = env('MAIL_PASSWORD', $config['smtpPassword'] ?? '');

            $notSetVariables = [];
            if (!$smtpHost) $notSetVariables[] = 'smtpHost';
            if (!$smtpPort) $notSetVariables[] = 'smtpPort';
            if (!$smtpUsername) $notSetVariables[] = 'smtpUsername';
            if (!$smtpPassword) $notSetVariables[] = 'smtpPassword';

            
            if (!empty($notSetVariables)) {
                throw new Exception("SMTP configuration is incomplete. Missing: " . implode(', ', $notSetVariables));
            }

            self::$instance = new self(
                $smtpHost,
                $smtpPort,
                $smtpUsername,
                $smtpPassword
            );
        }
        return self::$instance;
    }

    private function connect()
    {
        if ($this->connection !== null) {
            return;
        }

        $this->connection = @fsockopen(
            $this->smtpHost,
            $this->smtpPort,
            $errno,
            $errstr,
            $this->timeout
        );

        if (!$this->connection) {
            throw new Exception("SMTP connection failed: $errstr ($errno)");
        }

        stream_set_timeout($this->connection, $this->timeout);
        $this->checkResponse('220');

        $this->sendCommand("HELO " . ($_SERVER['SERVER_NAME'] ?? 'localhost'));
        $this->checkResponse('250');

        // Start TLS
        $this->sendCommand("STARTTLS");
        $this->checkResponse('220');

        // Upgrade the connection to TLS
        if (!stream_socket_enable_crypto(
            $this->connection,
            true,
            STREAM_CRYPTO_METHOD_TLS_CLIENT
        )) {
            throw new Exception("Failed to start TLS encryption");
        }

        // Send HELO again after TLS
        $this->sendCommand("HELO " . ($_SERVER['SERVER_NAME'] ?? 'localhost'));
        $this->checkResponse('250');
    }

    private function authenticate()
    {
        $this->sendCommand("AUTH LOGIN");
        $this->checkResponse('334');
        
        $this->sendCommand(base64_encode($this->smtpUsername));
        $this->checkResponse('334');
        
        $this->sendCommand(base64_encode($this->smtpPassword));
        $this->checkResponse('235');
    }

    public function sendEmail($fromEmail, $toEmail, $subject, $body)
    {
        if (!$this->isValidEmail($fromEmail) || !$this->isValidEmail($toEmail)) {
            throw new Exception("Invalid email address format");
        }

        try {
            $this->connect();
            $this->authenticate();

            $this->sendCommand("MAIL FROM:<$fromEmail>");
            $this->checkResponse('250');

            $this->sendCommand("RCPT TO:<$toEmail>");
            $this->checkResponse('250');

            $this->sendCommand("DATA");
            $this->checkResponse('354');

            $emailContent = "Subject: $subject\r\n" .
                          "From: $fromEmail\r\n" .
                          "To: $toEmail\r\n" .
                          "Date: " . date('r') . "\r\n" .
                          "Content-Type: text/html; charset=UTF-8\r\n\r\n" .
                          $body . "\r\n.\r\n";

            $this->sendCommand($emailContent);
            $this->checkResponse('250');

            $this->sendCommand("QUIT");
            $this->checkResponse('221');

            return true;
        } catch (Exception $e) {
            error_log("SMTPMailer Error: " . $e->getMessage());
            throw $e;
        } finally {
            $this->disconnect();
        }
    }

    private function sendCommand($command)
    {
        if (!$this->connection) {
            throw new Exception("No active SMTP connection");
        }
        fputs($this->connection, "{$command}\r\n");
    }

    private function getResponse()
    {
        $response = '';
        while ($line = fgets($this->connection, 515)) {
            $response .= $line;
            if (substr($line, 3, 1) === ' ') {
                break;
            }
        }
        return $response;
    }

    private function checkResponse($expectedCode)
    {
        $response = $this->getResponse();
        if (substr($response, 0, 3) !== $expectedCode) {
            throw new Exception("SMTP error - Expected $expectedCode, got: $response");
        }
    }

    private function isValidEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    private function disconnect()
    {
        if ($this->connection) {
            @fclose($this->connection);
            $this->connection = null;
        }
    }

    public function __destruct()
    {
        $this->disconnect();
    }

    private function __clone() {}
}
