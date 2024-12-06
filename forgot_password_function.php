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
                echo json_encode(["status" => "success", "message" => "Vui lòng kiểm tra email để khôi phục mật khẩu"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Gửi email thất bại."]);
            }

            mysqli_stmt_close($updateStmt); // Đóng câu lệnh chuẩn bị
        } else {
            echo json_encode(["status" => "error", "message" => "Không tìm thấy email của bạn trong hệ thống"]);
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
            echo json_encode(["status" => "success", "message" => "Khôi phục mật khẩu thành công."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Token không hợp lệ hoặc hết hạn"]);
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
        $mail->CharSet = 'UTF-8';

        $mail->setFrom(EMAIL, 'Hệ thống quản lí nhân viên Đức Anh');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Yêu cầu khôi phục mật khẩu';

        // Lấy giá trị host động từ header
        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '127.0.0.1';

        // Tạo đường dẫn khôi phục mật khẩu
        $resetLink = "http://$host/Management/forgot_password_token.php?token=$resetToken";
        $mail->Body = "Xin chào bạn, có phải bạn vừa thực hiện khôi phục mật khẩu?<p>Bấm vào liên kết dưới đây để tiến hành khôi phục mật khẩu: </p>$resetLink<p>";
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        return false;
    }
}
?>
