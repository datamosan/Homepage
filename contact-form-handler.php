<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'connection.php'; // Include your database connection
require 'vendor/autoload.php';

if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['subject']) && isset($_POST['message'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $em = "Invalid email format";
        header("Location: contact.php?error=$em");
        exit();
    }
    
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'angelikamae310@gmail.com';                     //SMTP username
        $mail->Password   = 'luuo utho ldln ztbs';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom($email, $name); //Set sender's email and name
        $mail->addAddress('angelikamae310@gmail.com', 'Dhen\'s Kitchen');     //Add a recipient

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body = '
            <div style="padding: 32px 0;">
            <table style="margin: 0 auto; background: #fff; border-radius: 12px; box-shadow: 0 4px 24px rgba(32,178,170,0.10); min-width: 600px; font-family: Segoe UI, Arial, sans-serif; border-collapse: separate; border-spacing: 0; overflow: hidden;">
                <tr>
                <th colspan="2" style="background: #82b690; color: #fff; padding: 18px 32px; font-size: 1.3em; border-radius: 12px 12px 0 0; letter-spacing: 1px;">
                    Contact Form Submission
                </th>
                </tr>
                <tr>
                <td style="padding: 12px 24px; color: #222; font-weight: 600;">Name:</td>
                <td style="padding: 12px 24px; color: #444;">' . htmlspecialchars($name) . '</td>
                </tr>
                <tr style="background: #f7fafa;">
                <td style="padding: 12px 24px; color: #222; font-weight: 600;">Email:</td>
                <td style="padding: 12px 24px; color: #444;">' . htmlspecialchars($email) . '</td>
                </tr>
                <tr>
                <td style="padding: 12px 24px; color: #222; font-weight: 600;">Phone:</td>
                <td style="padding: 12px 24px; color: #444;">' . htmlspecialchars($phone) . '</td>
                </tr>
                <tr style="background: #f7fafa;">
                <td style="padding: 12px 24px; color: #222; font-weight: 600;">Subject:</td>
                <td style="padding: 12px 24px; color: #444;">' . htmlspecialchars($subject) . '</td>
                </tr>
                <tr>
                <td style="padding: 12px 24px; color: #222; font-weight: 600; vertical-align: top;">Message:</td>
                <td style="padding: 12px 24px; color: #444;">' . nl2br(htmlspecialchars($message)) . '</td>
                </tr>
                <tr>
                <td colspan="2" style="background: #e97578; color: #fff; text-align: center; padding: 10px 0; border-radius: 0 0 12px 12px; font-size: 0.95em;">
                    &copy; ' . date('Y') . ' Dhen\'s Kitchen
                </td>
                </tr>
            </table>
            </div>
        ';

        $mail->send();
        $_SESSION['status'] = 'Your message has been sent. We will get back to you soon!';
        header("Location: contact.php");
        exit();
        } catch (Exception $e) {
            $_SESSION['error'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            header("Location: contact.php");
            exit();
        }
}
?>