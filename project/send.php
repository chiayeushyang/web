<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if(isset($_POST["send"]) && $_POST["name"] != "" && $_POST["email"] != "" && $_POST["phone"]  != "" && $_POST["msg"] != "")  {
    $mail = new PHPMailer(true);

    $mail -> isSMTP();
    $mail -> Host = 'smtp.gmail.com';
    $mail -> SMTPAuth = true;
    $mail -> Username = "yeushyang020825@gmail.com"; //send gmail
    $mail -> Password = "qzsvsfyrfqwyruuo"; // gmail app password
    $mail -> SMTPSecure = "ssl";
    $mail -> Port = 465;

    $context = 
    "
    <html>
    <head>
    </head>
    <body>
    <p>From email:<br><b>{$_POST['email']}</b></p>
    <p>Sender name:<br><b>{$_POST['name']}</b></p>
    <p>Phone:<br><b>{$_POST['phone']}</b></p>
    <p>Message:<br><b>{$_POST['msg']}</b></p>
    </body>
    </html>
    ";

    $mail -> setFrom("yeushyang020825@gmail.com"); // send gmail
 
    $mail -> addAddress("yeushyang020825@gmail.com");
    $mail -> isHTML(true);

    $mail -> Subjet = $_POST["name"] . $_POST["phone"];
    $mail -> Body = $context;

    $msg = wordwrap($mail->Body, 70);
    $mail -> send();

    header("Location: contact.php?message=mail_success");
} else {
    header("Location: contact.php?message=mail_empty");
}
?>
