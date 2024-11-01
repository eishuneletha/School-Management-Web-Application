<?php
use PHPMailer\PHPMailer\PHPMailer;

 

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

 

$mail = new PHPMailer();
$mail->SMTPDebug = 4;                               // Enable verbose debug output
$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'your_gmail@gmail.com';                 // SMTP username
$mail->Password = 'your_password';                           // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
//$mail->Port = 465;                                    // TCP port to connect to
$mail->Port = 587;  
$mail->setFrom('your_gmail@gmail.com', 'Your Name');
$mail->addReplyTo('your_gmail@gmail.com');
$mail->addAddress('email_to_send@anydomain.com', 'Name');     // Add a recipient

 

//$mail->addCC('cc1@gmail.com', 'CC name');
//$mail->addBCC('bcc1@example.com', 'BCC name');
                         
$mail->Subject = 'PHP Mailer testing program';
$mail->Body    = 'This is a testing PHP email sending program. <b>Enjoy!</b>';
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}
?>