<?php 
session_start();
include 'connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function send_password_reset_email($get_name, $get_email, $token) {
    $mail = new PHPMailer(true);

    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->isSMTP();
    $mail->SMTPAuth   = true;
    $mail->Host       = 'smtp.gmail.com';
    $mail->Username   = 'angelikamae310@gmail.com';
    $mail->Password   = 'luuo utho ldln ztbs';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;

    $mail->setFrom('angelikamae310@gmail.com', 'Dhen\'s Kitchen Support');
    $mail->addAddress($get_email);

    $mail->isHTML(true);
    $mail->Subject = 'Password Reset Request';
    $mail->Body    = "Hello $get_name,<br><br>To reset your password, please click the link below:<br>
                      <a href='http://localhost/decadhen/Homepage/password-change.php?token=$token&email=$get_email'>Reset Password</a><br><br>
                      If you did not request this, please ignore this email.";

    $mail->send();
}

if(isset($_POST['resetPassword'])) {
    $email = $_POST['email'];
    $token = md5(rand());

    // Check if email exists
    $check_email = "SELECT UserEmail, UserName FROM decadhen.users WHERE UserEmail = ?";
    $check_email_run = sqlsrv_query($conn, $check_email, [$email]);

    if($check_email_run && $row = sqlsrv_fetch_array($check_email_run, SQLSRV_FETCH_ASSOC)) {
        $get_name = $row['UserName'];
        $get_email = $row['UserEmail'];

        $update_token = "UPDATE decadhen.users SET token = ? WHERE UserEmail = ?";
        $update_token_run = sqlsrv_query($conn, $update_token, [$token, $email]);

        if($update_token_run) {
            send_password_reset_email($get_name, $get_email, $token);
            $_SESSION['status'] = "Password reset link has been sent to your email.";
            header("Location: password-reset.php");
            exit(0);
        }
    } else {
        $_SESSION['status'] = "Email not found!";
        header("Location: password-reset.php");
        exit(0);
    }
}

if(isset($_POST['changePassword'])) {
    $email = $_POST['email'];
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if($new_password === $confirm_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $update_password = "UPDATE decadhen.users SET UserPassword = ?, token = NULL WHERE UserEmail = ? AND token = ?";
        $update_password_run = sqlsrv_query($conn, $update_password, [$hashed_password, $email, $token]);

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