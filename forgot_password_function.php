<?php
require "vendor/autoload.php";
require 'includes/config.php'; // Kết nối database từ tệp config
require 'includes/credentials.php'; // Thông tin đăng nhập email

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    if ($action === 'request_reset') {
        $email = $_POST['email'];

        // Kiểm tra xem email có tồn tại trong cơ sở dữ liệu không
        $stmt = mysqli_prepare($conn, "SELECT email_id FROM tblemployees WHERE email_id = ?"); 
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $count = mysqli_num_rows($result);

        if ($count > 0) {
            // Nếu email tồn tại, tạo token đặt lại mật khẩu
            $resetToken = bin2hex(random_bytes(16));

            // Lưu token vào cơ sở dữ liệu
            $updateStmt = mysqli_prepare($conn, "UPDATE tblemployees SET token = ? WHERE email_id = ?");
            mysqli_stmt_bind_param($updateStmt, "ss", $resetToken, $email);
            mysqli_stmt_execute($updateStmt);
            
            // Gửi email đặt lại mật khẩu
            if (sendPasswordResetEmail($email, $resetToken)) {
                // Phản hồi JSON cho yêu cầu AJAX
                echo json_encode(["status" => "success", "message" => "Check your email to reset your password."]);
            } else {
                // Phản hồi JSON cho yêu cầu AJAX
                echo json_encode(["status" => "error", "message" => "Failed to send reset password email."]);
            }
            
            mysqli_stmt_close($updateStmt); // Đóng câu lệnh chuẩn bị
        } else {
            // Nếu email không tồn tại
            echo json_encode(["status" => "error", "message" => "Email not found in our records."]);
        }

    } elseif ($action === 'reset_password') {
        $token = $_POST['token'];
        $newPassword = md5($_POST['new_password']); // Mã hóa mật khẩu mới bằng md5

        // Kiểm tra token hợp lệ
        $stmt = mysqli_prepare($conn, "SELECT email_id FROM tblemployees WHERE token = ?");
        mysqli_stmt_bind_param($stmt, "s", $token);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $count = mysqli_num_rows($result);

        if ($count > 0) {
            // Cập nhật mật khẩu mới
            $updateStmt = mysqli_prepare($conn, "UPDATE tblemployees SET password = ?, token = NULL WHERE token = ?");
            mysqli_stmt_bind_param($updateStmt, "ss", $newPassword, $token);
            mysqli_stmt_execute($updateStmt);
            echo json_encode(["status" => "success", "message" => "Password has been reset successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Invalid or expired token."]);
        }
        
        mysqli_stmt_close($stmt); // Đóng câu lệnh chuẩn bị
    }

    mysqli_close($conn); // Đóng kết nối
}

function sendPasswordResetEmail($email, $resetToken) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = EMAIL;
        $mail->Password = PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom(EMAIL, 'Employee Management System');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request';
        $resetLink = "http://127.0.0.1/Management/forgot_password_token.php?token=$resetToken";
        $mail->Body = "<p>Click <a href='$resetLink'>here</a> to reset your password.</p>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        return false;
    }
}
?>
