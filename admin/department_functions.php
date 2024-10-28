<?php 
date_default_timezone_set('Asia/Ho_Chi_Minh');
include('../includes/config.php');

function updateDepartment($id, $dname, $description) {
    global $conn;

    if (empty($dname) || empty($description)) {
    $response = array('status' => 'error', 'message' => 'Vui lòng điền đầy đủ thông tin');
    echo json_encode($response);
    exit;
    }

    $currentDateTime = date('Y-m-d H:i:s');
    
    $stmt = mysqli_prepare($conn, "UPDATE tbldepartments SET department_name=?, department_desc=?, last_modified_date=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, 'sssi', $dname, $description, $currentDateTime, $id);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $response = array('status' => 'success', 'message' => 'Cập nhật phòng ban thành công');
        echo json_encode($response);
        exit;
    } else {
        $response = array('status' => 'error', 'message' => 'Cập nhật phòng ban thất bại');
        echo json_encode($response);
        exit;
    }
}

function saveDepartment($dname, $description) {
    global $conn;

    if (empty($dname) || empty($description)) {
        $response = array('status' => 'error', 'message' => 'Vui lòng điền đầy đủ thông tin');
        echo json_encode($response);
        exit;
    }

    // Check if the department already exists
    $stmt = mysqli_prepare($conn, "SELECT * FROM tbldepartments WHERE department_name = ?");
    mysqli_stmt_bind_param($stmt, "s", $dname);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $count = mysqli_num_rows($result);

    if ($count > 0) { 
        $response = array('status' => 'error', 'message' => 'Phòng ban đã tồn tại');
        echo json_encode($response);
        exit;
    } else {
        // Prepare the query to insert a new department with creation_date
        $currentDateTime = date('Y-m-d H:i:s'); // Get the current date and time
        $stmt = mysqli_prepare($conn, "INSERT INTO tbldepartments (department_name, department_desc, creation_date) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sss", $dname, $description, $currentDateTime);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            $response = array('status' => 'success', 'message' => 'Phòng ban đã được thêm thành công');
            echo json_encode($response);
            exit;
        } else {
            $response = array('status' => 'error', 'message' => 'Không thể thêm phòng ban');
            echo json_encode($response);
            exit;
        }
    }
}

function deleteDepartment($id) {
    global $conn;

    $stmt = mysqli_prepare($conn, "DELETE FROM tbldepartments WHERE id=?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $response = array('status' => 'success', 'message' => 'Phòng ban đã được xóa thành công');
        echo json_encode($response);
        exit;
    } else {
        $response = array('status' => 'error', 'message' => 'Không thể xóa phòng ban');
        echo json_encode($response);
        exit;
    }
}


if(isset($_POST['action'])) {
    // Determine which action to perform
    if ($_POST['action'] === 'update') {
        $dname = $_POST['dname'];
        $description = $_POST['description'];
        $id = $_POST['id'];
        $response = updateDepartment($id, $dname, $description);
        echo $response;
    } elseif ($_POST['action'] === 'save') {
        $dname = $_POST['dname'];
        $description = $_POST['description'];
        $response = saveDepartment($dname, $description);
        echo $response;
    } elseif ($_POST['action'] === 'delete') {
        $id = $_POST['id'];
        $response = deleteDepartment($id);
        echo $response;
    }
}
?>
