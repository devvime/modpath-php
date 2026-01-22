<?php

namespace ModPath\Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    protected $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->mail->isSMTP();
        $this->mail->isHTML(true);
        $this->mail->Host = $_ENV['EMAIL_HOST'];
        $this->mail->Port = $_ENV['EMAIL_PORT'];
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $_ENV['EMAIL_USER'];
        $this->mail->Password = $_ENV['EMAIL_PASSWORD'];
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->CharSet = 'UTF-8';
    }

    public function send(array $data): bool
    {
        try {
            $fromName = $data['title'] ?? $data['subject'] ?? 'Mailer';

            $this->mail->setFrom($_ENV['EMAIL_USER'], $fromName);
            $this->mail->Subject = $data['subject'] ?? '';
            $this->mail->AltBody = $data['altbody'] ?? strip_tags($data['msgHTML'] ?? '');

            foreach ($data['recipients'] ?? [] as $recipient) {
                $this->mail->addAddress($recipient['email'], $recipient['name'] ?? '');
            }

            $this->mail->msgHTML($data['msgHTML'] ?? '');

            return $this->mail->send();
        } catch (Exception $e) {
            error_log('Mailer error: ' . $this->mail->ErrorInfo);
            return false;
        }
    }
}