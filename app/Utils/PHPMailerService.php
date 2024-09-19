<?php

namespace App\Utils;

use App\Utils\ResponseUtil;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class PHPMailerService
{
    protected $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->mail->isSMTP(); // PROTOCOLO
        $this->mail->Host       = 'smtp.gmail.com';
        $this->mail->SMTPAuth   = true;
        $this->mail->Username   = 'davidsanse37@gmail.com';
        $this->mail->Password   = 'lgrz xtqg jopt mqiw';
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // TLS puerto = 587
        // $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // SSL puerto = 465
        $this->mail->Port       = 587;
        $this->mail->CharSet    = 'utf-8';
    }

    // ****************************************************************************************************************************
    // *!*   ENVIO DE CORREO PARA RECUPERACION DE CONTRASEÑA:
    // ****************************************************************************************************************************
    public function sendMail($email, $subject, $body)
    {
        try {
            // Configurar el remitente y destinatario
            // $this->mail->setFrom('noreplay', 'Green Thinking');
            $this->mail->setFrom($this->mail->Username, 'Green Thinking');
            $this->mail->addAddress($email);

            // Configurar el correo como HTML
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;

            // // Incrustar múltiples imágenes usando CID
            // $this->mail->addEmbeddedImage(FCPATH . 'assets/img/logo-es.png', 'image1_cid');
            // $this->mail->addEmbeddedImage(FCPATH . 'assets/img/email/esit-academia-header-email.jpg', 'image2_cid');
            // $this->mail->addEmbeddedImage(FCPATH . 'assets/img/laptop_icon.png', 'image3_cid');
            // $this->mail->addEmbeddedImage(FCPATH . 'assets/img/video_icon.png', 'image4_cid');
            // $this->mail->addEmbeddedImage(FCPATH . 'assets/img/social-icon/goes.png', 'image5_cid');
            // $this->mail->addEmbeddedImage(FCPATH . 'assets/img/social-icon/fb.png', 'image6_cid');
            // $this->mail->addEmbeddedImage(FCPATH . 'assets/img/social-icon/x.png', 'image7_cid');
            // $this->mail->addEmbeddedImage(FCPATH . 'assets/img/social-icon/insta.png', 'image8_cid');
            // $this->mail->addEmbeddedImage(FCPATH . 'assets/img/social-icon/yt.png', 'image9_cid');
            // $this->mail->addEmbeddedImage(FCPATH . 'assets/img/social-icon/tiktok.png', 'image10_cid');

            // // Reemplazar los placeholders en el HTML con los CIDs de las imágenes
            // $body = str_replace('@IMG_1', 'cid:image1_cid', $body);
            // $body = str_replace('@IMG_2', 'cid:image2_cid', $body);
            // $body = str_replace('@IMG_3', 'cid:image3_cid', $body);
            // $body = str_replace('@IMG_4', 'cid:image4_cid', $body);
            // $body = str_replace('@IMG_5', 'cid:image5_cid', $body);
            // $body = str_replace('@IMG_6', 'cid:image6_cid', $body);
            // $body = str_replace('@IMG_7', 'cid:image7_cid', $body);
            // $body = str_replace('@IMG_8', 'cid:image8_cid', $body);
            // $body = str_replace('@IMG_9', 'cid:image9_cid', $body);
            // $body = str_replace('@IMG_10', 'cid:image10_cid', $body);

            // // Establecer el cuerpo del correo
            // $this->mail->Body = $body;


            // Incrustar la imagen usando CID
            $this->mail->addEmbeddedImage(FCPATH . 'assets/img/favicon.png', 'image_cid');

            // Reemplazar el src de la imagen en el HTML
            $this->mail->Body = preg_replace('/(<img[^>]+src=")[^"]+("[^>]*>)/i', '$1cid:image_cid$2', $body);

            // Enviar el correo y devolver respuesta (true o false)
            return $this->mail->send();
        } catch (Exception $e) {
            // Manejo de errores
            ResponseUtil::logWithContext(ResponseUtil::setResponse(500, "server_error", 'Exception: ' . $e->getMessage(), []));
            return false;
        }
    }

    public static function ejemploPHPMailer()
    {
        // $html = '<img src="../img/logoletranegra.png" alt="LOGO" style="width: 125px;">';
        // // Definir el nuevo valor del src
        // $newSrc = 'cid:newImage.png';
        // // Reemplazar el contenido de la etiqueta src usando preg_replace
        // $html = preg_replace('/(<img[^>]+src=")[^"]+("[^>]*>)/i', '$1' . $newSrc . '$2', $html);

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'yourusername@example.com';
            $mail->Password = 'yourpassword';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;
            $mail->setFrom('from@example.com', 'Mailer');
            $mail->addAddress('recipient@example.com', 'Joe User');
            $mail->isHTML(true);
            $mail->Subject = 'Here is the subject';
            $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
            $mail->addEmbeddedImage('path/to/image.jpg', 'image_cid');
            $mail->Body    = 'HTML Body with image: <img src="cid:image_cid">';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
