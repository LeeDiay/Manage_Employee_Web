<?php 
date_default_timezone_set('Asia/Ho_Chi_Minh');
session_start();
include('../includes/config.php');

function clockIn($staff_id) {
    global $conn;

    if ($staff_id !== $_SESSION['sstaff_id']) {
        $response = array('status' => 'error', 'message' => 'Mã nhân viên không khớp với phiên của bạn');
        echo json_encode($response);
        exit;
    }

    $currentDate = date('Y-m-d');
    $currentTime = date('H:i:s');

     
    $stmt = mysqli_prepare($conn, "SELECT * FROM tblemployees WHERE staff_id = ?");
    mysqli_stmt_bind_param($stmt, 's', $staff_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 0) {
        $response = array('status' => 'error', 'message' => 'Mã nhân viên không hợp lệ');
        echo json_encode($response);
        exit;
    }

    
    $stmt = mysqli_prepare($conn, "SELECT * FROM tblattendance WHERE staff_id = ? AND DATE(date) = ?");
    mysqli_stmt_bind_param($stmt, 'ss', $staff_id, $currentDate);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $response = array('status' => 'error', 'message' => 'Bạn đã chấm công hôm nay rồi.');
        echo json_encode($response);
        exit;
    }

    
    $stmt = mysqli_prepare($conn, "INSERT INTO tblattendance (staff_id, time_in, date) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'sss', $staff_id, $currentTime, $currentDate);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $response = array('status' => 'success', 'message' => 'Check in thành công.');
        echo json_encode($response);
        exit;
    } else {
        $response = array('status' => 'error', 'message' => 'Check in thất bại.');
        echo json_encode($response);
        exit;
    }
}

function clockOut($staff_id) {
    global $conn;

    if ($staff_id !== $_SESSION['sstaff_id']) {
        $response = array('status' => 'error', 'message' => 'Mã nhân viên không khớp với phiên');
        echo json_encode($response);
        exit;
    }

    $currentDate = date('Y-m-d');
    $currentTime = date('H:i:s');

     
    $stmt = mysqli_prepare($conn, "SELECT * FROM tblemployees WHERE staff_id = ?");
    mysqli_stmt_bind_param($stmt, 's', $staff_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 0) {
        $response = array('status' => 'error', 'message' => 'Mã nhân viên không hợp lệ');
        echo json_encode($response);
        exit;
    }

    
    $stmt = mysqli_prepare($conn, "SELECT * FROM tblattendance WHERE staff_id = ? AND DATE(date) = ? AND time_out IS NULL");
    mysqli_stmt_bind_param($stmt, 'ss', $staff_id, $currentDate);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 0) {
        $response = array('status' => 'error', 'message' => 'Bạn phải Check in trước khi Check out.');
        echo json_encode($response);
        exit;
    }

    
    $stmt = mysqli_prepare($conn, "UPDATE tblattendance SET time_out = ? WHERE staff_id = ? AND DATE(date) = ? AND time_out IS NULL");
    mysqli_stmt_bind_param($stmt, 'sss', $currentTime, $staff_id, $currentDate);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $response = array('status' => 'success', 'message' => 'Check out thành công!');
        echo json_encode($response);
        exit;
    } else {
        $response = array('status' => 'error', 'message' => 'Check out thất bại!');
        echo json_encode($response);
        exit;
    }
}

function deleteAttendance($attendanceId) {
    global $conn;

    $stmt = mysqli_prepare($conn, "DELETE FROM tblattendance WHERE attendance_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $attendanceId);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $response = array('status' => 'success', 'message' => 'Xóa thành công');
    } else {
        $response = array('status' => 'error', 'message' => 'Xóa thất bại');
    }
    echo json_encode($response);
    exit;
}

if(isset($_POST['action'])) {
    if ($_POST['action'] === 'clock_in') {
        $staff_id = $_POST['staff_id'];
        clockIn($staff_id);

    } elseif ($_POST['action'] === 'clock_out') {
        $staff_id = $_POST['staff_id'];
        clockOut($staff_id);

    } elseif ($_POST['action'] === 'delete_attendance') {
        $attendanceId = $_POST['attendance_id'];
        deleteAttendance($attendanceId);
    }
}
?>