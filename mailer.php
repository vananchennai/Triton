<?php
require 'PHPMailer/PHPMailerAutoload.php';

$mail = new PHPMailer;
date_default_timezone_set ("Asia/Calcutta");
echo $trial=date("Y-m-d H:i:s",time());
$mail->isSMTP(); 
$mail->Host = 'smtp.gmail.com';    
$mail->SMTPAuth = true;
$mail->Username = 'jkfsfenner@gmail.com';
$mail->Password = 'fenneradmin';
$mail->SMTPSecure = 'tls';

$mail->From = 'jkfsfenner@gmail.com';
$mail->FromName = 'Fenner Support';
$mail->addAddress('sankargowri55@gmail.com');

$mail->isHTML(true);

$mail->Subject = 'Test Mail Subject!';
$mail->Body    = 'This is SMTP Email Test';

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
 } else {
    echo 'Message has been sent';
}
?>