<?php

class SMTPMailer
{
    private static $instance = null;  // Singleton instance
    private $smtpHost;
    private $smtpPort;
    private $smtpUsername;
    private $smtpPassword;
    private $connection;

    // Private constructor to prevent multiple instances
    private function __construct($smtpHost, $smtpPort, $smtpUsername, $smtpPassword)
    {
        $this->smtpHost = $smtpHost;
        $this->smtpPort = $smtpPort;
        $this->smtpUsername = $smtpUsername;
        $this->smtpPassword = $smtpPassword;
        $this->connection = null;
    }

    // Singleton pattern to get the instance
    public static function getInstance()
    {
        if (self::$instance === null) {
            $config = include 'config.php';
            self::$instance = new SMTPMailer(
                $config['smtpHost'],
                $config['smtpPort'],
                $config['smtpUsername'],
                $config['smtpPassword']
            );
        }
        return self::$instance;
    }

    // Connect to the SMTP server
    private function connect()
    {
        // Reconnect if the connection is not established
        if ($this->connection === null) {
            $this->connection = fsockopen($this->smtpHost, $this->smtpPort, $errno, $errstr, 10);

            if (!$this->connection) {
                throw new Exception("Connection failed: $errstr ($errno)");
            }

            // Read server response
            $response = fgets($this->connection, 515);
            if (substr($response, 0, 3) != '220') {
                throw new Exception("Connection failed with response: " . $response);
            }

            // Send HELO command
            fputs($this->connection, "HELO " . $_SERVER['SERVER_NAME'] . "\r\n");
            fgets($this->connection, 515);
        }
    }

    // Authenticate with the SMTP server
    private function authenticate()
    {
        // Authentication process
        fputs($this->connection, "AUTH LOGIN\r\n");
        fgets($this->connection, 515);

        fputs($this->connection, base64_encode($this->smtpUsername) . "\r\n");
        fgets($this->connection, 515);

        fputs($this->connection, base64_encode($this->smtpPassword) . "\r\n");
        fgets($this->connection, 515);
    }

    // Send an email
    public function sendEmail($fromEmail, $toEmail, $subject, $body)
    {
        try {
            // Connect and authenticate only if the connection is not established
            $this->connect();
            $this->authenticate();

            // Send MAIL FROM command
            fputs($this->connection, "MAIL FROM: <$fromEmail>\r\n");
            fgets($this->connection, 515);

            // Send RCPT TO command
            fputs($this->connection, "RCPT TO: <$toEmail>\r\n");
            fgets($this->connection, 515);

            // Send DATA command
            fputs($this->connection, "DATA\r\n");
            fgets($this->connection, 515);

            // Send email headers and body
            $emailContent = "Subject: $subject\r\n";
            $emailContent .= "From: <$fromEmail>\r\n";
            $emailContent .= "To: <$toEmail>\r\n";
            $emailContent .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
            $emailContent .= "\r\n";
            $emailContent .= $body;
            $emailContent .= "\r\n.\r\n";

            fputs($this->connection, $emailContent);
            fgets($this->connection, 515);

            // Send QUIT command
            fputs($this->connection, "QUIT\r\n");
            fgets($this->connection, 515);

            echo "Email sent successfully.\n";
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }

    public function __destruct()
    {
        if ($this->connection) {
            fclose($this->connection);
        }
    }
}
