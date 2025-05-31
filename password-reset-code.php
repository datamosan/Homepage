<?php 
session_start();
include 'connection.php'; // Include your database connection file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader (created by composer, not included with PHPMailer)
require 'vendor/autoload.php';

function send_password_reset_email($get_name, $get_email, $token) {
    $mail = new PHPMailer(true); // <-- Add this line

    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication

    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->Username   = 'angelikamae310@gmail.com';                     //SMTP username
    $mail->Password   = 'luuo utho ldln ztbs';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('angelikamae310@gmail.com', 'Dhen\'s Kitchen Support'); //Set sender's email and name
    $mail->addAddress($get_email);     //Add a recipient

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Password Reset Request';
    $mail->Body    = "Hello $get_name,<br><br>To reset your password, please click the link below:<br>
                      <a href='http://localhost/decadhen/Homepage/password-change.php?token=$token&email=$get_email'>Reset Password</a><br><br>
                      If you did not request this, please ignore this email.";

    $mail->send();
    echo 'Message has been sent';
}


if(isset($_POST['resetPassword'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $token = md5(rand());

    // FIXED COLUMN NAMES
    $check_email = "SELECT UserEmail, UserName FROM users WHERE UserEmail='$email' LIMIT 1";
    $check_email_run = mysqli_query($conn, $check_email);

    if(mysqli_num_rows($check_email_run) > 0) 
    {
        $row = mysqli_fetch_array($check_email_run);
        $get_name = $row['UserName']; 
        $get_email = $row['UserEmail'];

        $update_token = "UPDATE users SET token='$token' WHERE UserEmail='$email'";
        $update_token_run = mysqli_query($conn, $update_token);

        if($update_token_run) {
            send_password_reset_email($get_name, $get_email, $token);
            $_SESSION['status'] = "Password reset link has been sent to your email.";
            header("Location: password-reset.php");
            exit(0);
        }
    }
    else 
    {
        $_SESSION['status'] = "Email not found!";
        header("Location: password-reset.php");
        exit(0);
    }
}

if(isset($_POST['changePassword'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $token = mysqli_real_escape_string($conn, $_POST['token']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    if($new_password === $confirm_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $update_password = "UPDATE users SET UserPassword='$hashed_password', token=NULL WHERE UserEmail='$email' AND token='$token'";
        $update_password_run = mysqli_query($conn, $update_password);

        if($update_password_run) {
            $_SESSION['status'] = "Password changed successfully!";
            header("Location: login.php");
            exit(0);
        } else {
            $_SESSION['status'] = "Failed to change password!";
            header("Location: password-change.php?email=$email&token=$token");
            exit(0);
        }
    } else {
        $_SESSION['status'] = "Passwords do not match!";
        header("Location: password-change.php?email=$email&token=$token");
        exit(0);
    }
}
?>