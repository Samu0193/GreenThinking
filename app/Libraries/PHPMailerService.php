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
        $this->mail->Port       = 465;
        $this->mail->CharSet    = 'utf-8';
    }

    public function sendMail($email, $subject, $body)
    {
        try {
            $this->mail = new PHPMailer(true);
            $this->mail->isSMTP();
            $this->mail->Host       = 'smtp.gmail.com';
            $this->mail->SMTPAuth   = true;
            $this->mail->Username   = 'davidsanse37@gmail.com';
            $this->mail->Password   = 'lgrz xtqg jopt mqiw';
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mail->Port       = 465;
            $this->mail->CharSet    = 'utf-8';

            $this->mail->setFrom('davidsanse37@gmail.com', 'Mailer');
            $this->mail->addAddress($email);
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->addEmbeddedImage(FCPATH . 'assets/img/logoGT.jpeg', 'image_cid');
            $this->mail->Body = str_replace('../img/logoGT.jpeg', 'cid:image_cid', $body);
            // $this->mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            // $this->mail->setFrom('noreply', 'Green Thinking');
            // $this->mail->addAddress('davidsanse37@gmail.com', 'Samu User');
            // $this->mail->isHTML(true);
            // $this->mail->Subject = 'Here is the subject';
            // $this->mail->Body    = 'This is the HTML message body <b>in bold!</b>';
            // $this->mail->addEmbeddedImage('path/to/image.jpg', 'image_cid');
            // $this->mail->Body    = 'HTML Body with image: <img src="cid:image_cid">';
            // $this->mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            // $this->mail->send();

            return $this->mail->send();
        } catch (Exception $e) {
            return false;
        }
    }
}
