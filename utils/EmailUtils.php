<?php

namespace Utils;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Utils\Env;
class EmailUtils {
function SendErrorEmail($boxId, $zone, $errorMessage) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = Env::get('SMTP_HOST');
        $mail->SMTPAuth = true;
        $mail->Username = Env::get('SMTP_USER');
        $mail->Password = Env::get('SMTP_PASS');
        $mail->SMTPSecure = Env::get('SMTP_SECURE');
        $mail->Port = Env::get('SMTP_PORT');
        
        // Recipients
        $mail->setFrom(Env::get('MAIL_FROM'), Env::get('MAIL_FROM_NAME'));
        $mail->addAddress('phu@expressinmusic.com', 'Phu');

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Error updating prayer time';
        $mail->Body    = "An error occurred while updating prayer times:<br><br>" .
                         "Box ID: $boxId<br>" .
                         "Prayer Zone: $zone<br>" .
                         "Error Message: $errorMessage";
        
        $mail->send();
        echo 'Error notification email has been sent.';
    } catch (Exception $e) {
        echo 'Error notification email could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }
}

}