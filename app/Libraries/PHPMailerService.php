<?php

namespace App\Libraries;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class PHPMailerService
{
    protected $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->mail->isSMTP();
        $this->mail->Host       = 'smtp.gmail.com';
        $this->mail->SMTPAuth   = true;
        $this->mail->Username   = 'davidsanse37@gmail.com';
        $this->mail->Password   = 'lgrz xtqg jopt mqiw';
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port       = 587;
    }

    public function send($to, $subject, $body)
    {
        try {
            $this->mail->setFrom('noreply', 'Green Thinking');
            $this->mail->addAddress($to);
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body    = $body;
            $this->mail->addEmbeddedImage(FCPATH . 'assets/images/logoGT.jpeg', 'image_cid');
            // $this->mail->Body    = 'HTML Body with image: <img src="cid:image_cid">';
            // $this->mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            // $this->mail->send();

            return $this->mail->send();
        } catch (Exception $e) {
            return false;
        }
    }
}
