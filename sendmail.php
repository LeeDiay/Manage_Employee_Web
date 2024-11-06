<?php
require "vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'includes/credentials.php';

function setupMailer()
{
    $mail = new PHPMailer(true);
    $mail->SMTPDebug = SMTP::DEBUG_OFF; // Disable debug output
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = EMAIL;
        $mail->Password = PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;
        $mail->setFrom(EMAIL, 'Hệ thống quản lí nhân viên Đức Anh');
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        return $mail;
    } catch (Exception $e) {
        error_log("Mailer setup failed: {$mail->ErrorInfo}");
    }
    return null;
}

function sendEmail($mail, $to, $subject, $body)
{
    try {
        $mail->clearAddresses(); // Clear previous recipients
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AltBody = strip_tags($body);
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Không thể gửi mail. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}

function sendPasswordResetEmail($email, $resetToken)
{
    $mail = setupMailer();
    if (!$mail) {
        return false;
    }
    $resetLink = "http://localhost/Management/reset_password.php?token=$resetToken";
    $subject = "Password Reset Request";
    $body = "
        <p>Hello,</p>
        <p>We received a request to reset your password. You can reset it by clicking the link below:</p>
        <p><a href='$resetLink'>Reset Password</a></p>
        <p>If you did not request this, please ignore this email.</p>
        <p>Thank you!</p>
    ";
    return sendEmail($mail, $email, $subject, $body);
}

function sendLeaveApplicationEmail($supervisorEmail, $name, $from, $to, $type, $supervisorName)
{
    $mail = setupMailer();
    if (!$mail) {
        return false;
    }
    $redirectLink = "http://localhost/Management/index.php";
    $subject = "Đơn xin nghỉ phép $type";
    $body = "
        <p>Chào $supervisorName,</p>
        <p>$name đã nộp đơn xin nghỉ phép $type từ $from đến $to.</p>
        <p>Vui lòng đăng nhập vào Cổng Đăng ký Nghỉ phép để duyệt đơn tại</p><p><a href='$redirectLink'>Đường dẫn</a></p>
        <p>Xin cảm ơn.</p>
    ";
    return sendEmail($mail, $supervisorEmail, $subject, $body);
}


function sendLeaveNotification($employeeEmails, $employeeName, $fromDate, $toDate, $leaveType, $status)
{
    $mail = setupMailer();
    if (!$mail) {
        return false;
    }

    $subject = $status == 1 ? "Thông báo phê duyệt nghỉ phép" : "Thông báo thu hồi nghỉ phép";
    $body = $status == 1 ? "
        <p>Xin chào,</p>
        <p>Chúng tôi vui mừng thông báo rằng đơn xin nghỉ phép $leaveType của $employeeName từ $fromDate đến $toDate đã được phê duyệt.</p>
        <p>Xin cảm ơn.</p>
    " : "
        <p>Xin chào,</p>
        <p>Chúng tôi rất tiếc thông báo rằng đơn xin nghỉ phép $leaveType của $employeeName từ $fromDate đến $toDate đã bị thu hồi.</p>
        <p>Xin cảm ơn.</p>
    ";
    $success = true;
    foreach ($employeeEmails as $email) {
        if (!sendEmail($mail, $email, $subject, $body)) {
            $success = false;
            error_log("Không thể gửi thông báo nghỉ phép tới: $email");
        }
    }
    return $success;
}

