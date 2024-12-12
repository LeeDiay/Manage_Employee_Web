<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
session_start();
include('../includes/config.php');

function resizeImage($sourcePath, $destinationPath, $width, $height) {
     if (!function_exists('imagecreatefromjpeg') || !function_exists('imagejpeg')) {
        throw new Exception('GD library is not available');
    }
    
    list($originalWidth, $originalHeight) = getimagesize($sourcePath);
    $src = imagecreatefromjpeg($sourcePath);
    $dst = imagecreatetruecolor($width, $height);
    
    
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $width, $height, $originalWidth, $originalHeight);
    
    
    imagejpeg($dst, $destinationPath);
    
    
    imagedestroy($src);
    imagedestroy($dst);
}

function updateStaffRecords($edit_id, $firstname, $lastname, $middlename, $contact, $designation, $department, $email, $password, $gender, $is_supervisor, $role, $staff_id, $image_path) {
    global $conn;

    if (empty($department) || empty($firstname) || empty($lastname) || empty($contact) || empty($designation) || empty($email)) {
        $response = array('status' => 'error', 'message' => 'Vui lòng điền đầy đủ thông tin');
        echo json_encode($response);
        exit;
    }

    
    if ($image_path !== null && isset($image_path['name']) && !empty($image_path['name'])) {
        
        $image_upload_dir = '../uploads/images/';
        $image_name = $staff_id . '_' . basename($image_path['name']);
        $image_target_path = $image_upload_dir . $image_name;

        if (!move_uploaded_file($image_path['tmp_name'], $image_target_path)) {
            $response = array('status' => 'error', 'message' => 'Không thể upload file!');
            echo json_encode($response);
            exit;
        }

         
        resizeImage($image_target_path, $image_target_path, 230, 230);

        
        $old_image_path = ''; 
        $stmt = mysqli_prepare($conn, "SELECT image_path FROM tblemployees WHERE emp_id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $edit_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $old_image_path);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        if (!empty($old_image_path) && file_exists($old_image_path)) {
            unlink($old_image_path); 
        }

    } else {
        $image_target_path = ''; 
    }

    
    if (empty($password)) {
        $password_param = ''; 
    } else {
        $password_param = md5($password);
    }

    
    if (empty($image_target_path) && empty($password_param)) {
        
        $stmt = mysqli_prepare($conn, "UPDATE tblemployees SET department=?, first_name=?, last_name=?, middle_name=?, phone_number=?, designation=?, email_id=?, gender=?, role=?, staff_id=?, is_supervisor=? WHERE emp_id=?");
        mysqli_stmt_bind_param($stmt, 'isssssssssii', $department, $firstname, $lastname, $middlename, $contact, $designation, $email, $gender, $role, $staff_id, $is_supervisor, $edit_id);
    } elseif (empty($image_target_path)) {
        
        $stmt = mysqli_prepare($conn, "UPDATE tblemployees SET department=?, first_name=?, last_name=?, middle_name=?, phone_number=?, designation=?, email_id=?, password=?, gender=?, role=?, staff_id=?, is_supervisor=? WHERE emp_id=?");
        mysqli_stmt_bind_param($stmt, 'issssssssssii', $department, $firstname, $lastname, $middlename, $contact, $designation, $email, $password_param, $gender, $role, $staff_id, $is_supervisor, $edit_id);
    } elseif (empty($password_param)) {
        
        $stmt = mysqli_prepare($conn, "UPDATE tblemployees SET department=?, first_name=?, last_name=?, middle_name=?, phone_number=?, designation=?, email_id=?, gender=?, role=?, image_path=?, staff_id=?, is_supervisor=? WHERE emp_id=?");
        mysqli_stmt_bind_param($stmt, 'issssssssssii', $department, $firstname, $lastname, $middlename, $contact, $designation, $email, $gender, $role, $image_target_path, $staff_id, $is_supervisor, $edit_id);
    } else {
        
        $stmt = mysqli_prepare($conn, "UPDATE tblemployees SET department=?, first_name=?, last_name=?, middle_name=?, phone_number=?, designation=?, email_id=?, password=?, gender=?, role=?, image_path=?, staff_id=?, is_supervisor=? WHERE emp_id=?");
        mysqli_stmt_bind_param($stmt, 'isssssssssssii', $department, $firstname, $lastname, $middlename, $contact, $designation, $email, $password_param, $gender, $role, $image_target_path, $staff_id, $is_supervisor, $edit_id);
    }

    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $response = array('status' => 'success', 'message' => 'Cập nhật thông tin nhân viên thành công');
        echo json_encode($response);
        exit;
    } else {
        $response = array('status' => 'error', 'message' => 'Cập nhật thông tin thất bại');
        echo json_encode($response);
        exit;
    }
}

function addStaffRecord($firstname, $lastname, $middlename, $contact, $designation, $department, $email, $password, $role, $is_supervisor, $staff_id, $gender, $image_path) {
    global $conn;

    if (empty($department) || empty($firstname) || empty($lastname) || empty($contact) ||
        empty($designation) || empty($email) || empty($password) || empty($role) || empty($image_path)) {
        $response = array('status' => 'error', 'message' => 'Vui lòng điền đầy đủ thông tin');
        echo json_encode($response);
        exit;
    }

    
    $stmt = mysqli_prepare($conn, "SELECT emp_id FROM tblemployees WHERE email_id=?");
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $num_rows = mysqli_stmt_num_rows($stmt);
    mysqli_stmt_close($stmt);

    if ($num_rows > 0) {
        $response = array('status' => 'error', 'message' => 'Email đã tồn tại');
        echo json_encode($response);
        exit;
    }

    
    $image_upload_dir = '../uploads/images/';
    $image_name = $staff_id . '_' . basename($image_path['name']);
    $image_target_path = $image_upload_dir . $image_name;

    if (!move_uploaded_file($image_path['tmp_name'], $image_target_path)) {
        $response = array('status' => 'error', 'message' => 'Upload ảnh thất bại');
        echo json_encode($response);
        exit;
    }

     
    resizeImage($image_target_path, $image_target_path, 230, 230);

    
    $password_param = md5($password);
    $stmt = mysqli_prepare($conn, "INSERT INTO tblemployees (department, first_name, last_name, middle_name, phone_number, designation, email_id, password, gender, image_path, role, staff_id, is_supervisor) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'isssssssssssi', $department, $firstname, $lastname, $middlename, $contact, $designation, $email, $password_param, $gender, $image_target_path, $role, $staff_id, $is_supervisor);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $response = array('status' => 'success', 'message' => 'Thêm nhân viên thành công');
        echo json_encode($response);
        exit;
    } else {
        $response = array('status' => 'error', 'message' => 'Thêm nhân viên thất bại');
        echo json_encode($response);
        exit;
    }
}

function deleteStaff($id) {
    global $conn;

    
    $old_image_path = ''; 
    $stmt = mysqli_prepare($conn, "SELECT image_path FROM tblemployees WHERE emp_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $old_image_path);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    
    $stmt = mysqli_prepare($conn, "DELETE FROM tblemployees WHERE emp_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        
        if (!empty($old_image_path) && file_exists($old_image_path)) {
            unlink($old_image_path); 
        }

        $response = array('status' => 'success', 'message' => 'Xóa thành công');
        echo json_encode($response);
        exit;
    } else {
        $response = array('status' => 'error', 'message' => 'Xóa thất bại');
        echo json_encode($response);
        exit;
    }
}

function assignLeaveTypes($employeeId, $leaveTypes) {
    global $conn;

    if (empty($employeeId) || empty($leaveTypes) || !is_array($leaveTypes)) {
        $response = array('status' => 'error', 'message' => 'Vui lòng cung cấp ID nhân viên hợp lệ và loại nghỉ phép!');
        echo json_encode($response);
        exit;
    }

    
    mysqli_begin_transaction($conn);

    try {
        
        $existingLeaveTypesQuery = "SELECT leave_type_id, available_days FROM employee_leave_types WHERE emp_id = ?";
        $stmt = mysqli_prepare($conn, $existingLeaveTypesQuery);
        if (!$stmt) {
            throw new Exception('Prepare failed: ' . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($stmt, 'i', $employeeId);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception('Execute failed: ' . mysqli_stmt_error($stmt));
        }
        $result = mysqli_stmt_get_result($stmt);
        $existingLeaveTypes = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $existingLeaveTypes[$row['leave_type_id']] = $row['available_days'];
        }
        mysqli_stmt_close($stmt);

        
        $leaveTypesQuery = "SELECT id, assign_days FROM tblleavetype";
        $leaveTypesResult = mysqli_query($conn, $leaveTypesQuery);
        if (!$leaveTypesResult) {
            throw new Exception('Query failed: ' . mysqli_error($conn));
        }
        $leaveTypesAssignDays = [];
        while ($row = mysqli_fetch_assoc($leaveTypesResult)) {
            $leaveTypesAssignDays[$row['id']] = $row['assign_days'];
        }

        
        foreach ($existingLeaveTypes as $leaveTypeId => $availableDays) {
            if (!in_array($leaveTypeId, $leaveTypes)) {
                
                if ($availableDays == $leaveTypesAssignDays[$leaveTypeId]) {
                    $stmt = mysqli_prepare($conn, "DELETE FROM employee_leave_types WHERE emp_id = ? AND leave_type_id = ?");
                    if (!$stmt) {
                        throw new Exception('Prepare failed: ' . mysqli_error($conn));
                    }
                    mysqli_stmt_bind_param($stmt, 'ii', $employeeId, $leaveTypeId);
                    if (!mysqli_stmt_execute($stmt)) {
                        throw new Exception('Execute failed: ' . mysqli_stmt_error($stmt));
                    }
                    mysqli_stmt_close($stmt);
                }
            }
        }

        
        foreach ($leaveTypes as $leaveTypeId) {
            if (array_key_exists($leaveTypeId, $existingLeaveTypes)) {
                
                if ($existingLeaveTypes[$leaveTypeId] == $leaveTypesAssignDays[$leaveTypeId]) {
                    
                    $stmt = mysqli_prepare($conn, "UPDATE employee_leave_types SET available_days = ? WHERE emp_id = ? AND leave_type_id = ?");
                    if (!$stmt) {
                        throw new Exception('Prepare failed: ' . mysqli_error($conn));
                    }
                    mysqli_stmt_bind_param($stmt, 'iii', $leaveTypesAssignDays[$leaveTypeId], $employeeId, $leaveTypeId);
                    if (!mysqli_stmt_execute($stmt)) {
                        throw new Exception('Execute failed: ' . mysqli_stmt_error($stmt));
                    }
                    mysqli_stmt_close($stmt);
                }
            } else {
                
                $assign_days = $leaveTypesAssignDays[$leaveTypeId];
                $stmt = mysqli_prepare($conn, "INSERT INTO employee_leave_types (emp_id, leave_type_id, available_days) VALUES (?, ?, ?)");
                if (!$stmt) {
                    throw new Exception('Prepare failed: ' . mysqli_error($conn));
                }
                mysqli_stmt_bind_param($stmt, 'iii', $employeeId, $leaveTypeId, $assign_days);
                if (!mysqli_stmt_execute($stmt)) {
                    throw new Exception('Execute failed: ' . mysqli_stmt_error($stmt));
                }
                mysqli_stmt_close($stmt);
            }
        }

        
        mysqli_commit($conn);

        $response = array('status' => 'success', 'message' => 'Thành công');
        echo json_encode($response);
        exit;

    } catch (Exception $e) {
        
        mysqli_rollback($conn);

        $response = array('status' => 'error', 'message' => 'Thất bại: ' . $e->getMessage());
        echo json_encode($response);
        exit;
    }
}

function assignSupervisor($employeeId, $supervisorId) {
    global $conn;

    
    if (empty($employeeId) || empty($supervisorId)) {
        $response = array('status' => 'error', 'message' => 'Vui lòng cung cấp cả ID nhân viên và ID người giám sát!');
        return json_encode($response);
    }

    
    $stmt = mysqli_prepare($conn, "UPDATE tblemployees SET supervisor_id = ? WHERE emp_id = ?");
    mysqli_stmt_bind_param($stmt, 'ii', $supervisorId, $employeeId);
    $result = mysqli_stmt_execute($stmt);

    
    if ($result) {
        $response = array('status' => 'success', 'message' => 'Thành công');
    } else {
        $response = array('status' => 'error', 'message' => 'Thất bại');
    }

    return json_encode($response);
}

if(isset($_POST['action'])) {
    if ($_POST['action'] === 'updateStaff') {
        $edit_id = $_POST['edit_id'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $middlename = $_POST['middlename'];
        $contact = $_POST['contact'];
        $designation = $_POST['designation'];
        $department = $_POST['department'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $gender = $_POST['gender'];
        $role = $_POST['role'];
        $is_supervisor = $_POST['is_supervisor'];
        $staff_id = $_POST['staff_id'];
        if(isset($_FILES['image_path'])) {
            $image_path = $_FILES['image_path'];
        } else {
            $image_path = '';
        }
        $response = updateStaffRecords($edit_id, $firstname, $lastname, $middlename, $contact, $designation, $department, $email, $password, $gender, $is_supervisor, $role, $staff_id, $image_path);
        echo $response;

    } elseif ($_POST['action'] === 'staff-add') {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $middlename = $_POST['middlename'];
        $contact = $_POST['contact'];
        $designation = $_POST['designation'];
        $department = $_POST['department'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $gender = $_POST['gender'];
        $staff_id = $_POST['staff_id'];
        $role = $_POST['role'];
        $is_supervisor = $_POST['is_supervisor'];
        $image_path = $_FILES['image_path'];
        $response = addStaffRecord($firstname, $lastname, $middlename, $contact, $designation, $department, $email, $password, $role, $is_supervisor, $staff_id, $gender, $image_path);
        echo $response;

    } elseif ($_POST['action'] === 'delete-staff') {
        $id = $_POST['id'];
        $response = deleteStaff($id);
        echo $response;
    } elseif (isset($_POST['action']) && $_POST['action'] === 'assign-leave-types') {
        $employeeId = $_POST['employeeId'];
        $leaveTypes = isset($_POST['leaveTypes']) ? $_POST['leaveTypes'] : [];

        
        error_log("Received employeeId: " . $employeeId);
        error_log("Received leaveTypes: " . implode(', ', $leaveTypes));

        assignLeaveTypes($employeeId, $leaveTypes);
        
    } elseif ($_POST['action'] === 'assign-supervisor') {
        $employeeId = $_POST['employeeId'];
        $supervisorId = $_POST['supervisorId'];
        $response = assignSupervisor($employeeId, $supervisorId);
        echo $response;
        exit;
    }
}

?>

<?php

$searchQuery = $_POST['searchQuery'];
$departmentFilter = $_POST['departmentFilter'];

$userRole = $_SESSION['srole'];
$userId = $_SESSION['slogin'];
$userDepartment = $_SESSION['department'];
$isSupervisor = $_SESSION['is_supervisor'];


$sql = "SELECT e.*, d.department_name 
        FROM tblemployees e 
        LEFT JOIN tbldepartments d ON e.department = d.id";

if ($departmentFilter !== '') {
    $sql .= " WHERE d.department_name = '$departmentFilter'";
}
if ($searchQuery !== '') {
    $sql .= ($departmentFilter === '') ? " WHERE" : " AND";
    $sql .= " (e.first_name LIKE '%$searchQuery%' OR e.last_name LIKE '%$searchQuery%' OR e.designation LIKE '%$searchQuery%')";
}


$employeeData = []; 
$result = mysqli_query($conn, $sql);
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $employeeData[] = $row;
    }
}


if (empty($employeeData)) {
    echo '<div class="col-lg-12 text-center">
            <img src="../files/assets/images/no_data.png" class="img-radius" alt="No Data Found" style="width: 200px; height: auto;">
          </div>';
} else {
    foreach ($employeeData as $employee) {
        $imagePath = empty($employee['image_path']) ? '../files/assets/images/user-card/img-round1.jpg' : $employee['image_path'];
        echo '<div class="col-lg-6 col-xl-3 col-md-6">
                <div class="card rounded-card user-card">
                    <div class="card-block">
                        <div class="img-hover">
                            <img class="img-fluid img-radius" src="' . $imagePath . '" alt="round-img">
                            <div class="img-overlay img-radius">
                                <span>
                                    <a href="staff_detailed.php?id=' . $employee['emp_id'] . '&view=2" class="btn btn-sm btn-primary" style="margin-top: 1px;" data-popup="lightbox"><i class="icofont icofont-eye-alt"></i></a>';
                                     
                                    if ($userRole === 'Admin' || ($userRole === 'Manager' && $employee['designation'] !== 'Administrator')) {
                                        echo '<a href="new_staff.php?id=' . $employee['emp_id'] . '&edit=1" class="btn btn-sm btn-primary" data-popup="lightbox" style="margin-left: 8px; margin-top: 1px;"><i class="icofont icofont-edit"></i></a>';
                                        
                                        
                                        if ($employee['designation'] !== 'Administrator') {
                                            echo '<a href="#" class="btn btn-sm btn-primary delete-staff" style="margin-top: 1px;" data-id="' . $employee['emp_id'] . '"><i class="icofont icofont-ui-delete"></i></a>';
                                        }
                                    }

                                echo '</span>
                            </div>
                        </div>
                        <div class="user-content">
                            <h4 class="">' . $employee['first_name'] . ' ' . $employee['middle_name'] . ' ' . $employee['last_name'] . '</h4>
                            <p class="m-b-0 text-muted">' . $employee['designation'] . '</p>
                        </div>
                    </div>
                </div>
            </div>';
    }
}
?>
<!-- staff_detailed.php -->