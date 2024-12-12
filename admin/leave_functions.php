<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
session_start();
include('../includes/config.php');
include('../sendmail.php');


function isValidEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function getAvailableDays($empId, $leaveTypeId)
{
    global $conn;

    
    $sql = "SELECT available_days FROM employee_leave_types WHERE emp_id = ? AND leave_type_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $empId, $leaveTypeId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        return $row['available_days'];
    } else {
        return 0;
    }
}

function calculateBusinessDays($startDate, $endDate)
{
    $startDate = new DateTime($startDate);
    $endDate = new DateTime($endDate);
    $days = $startDate->diff($endDate)->days + 1;

    $businessDays = 0;
    for ($i = 0; $i < $days; $i++) {
        $currentDate = (clone $startDate)->add(new DateInterval('P' . $i . 'D'));
        if ($currentDate->format('N') < 6) {
            $businessDays++;
        }
    }

    return $businessDays;
}

function getEmployeeName($empId)
{
    global $conn;
    $query = "SELECT CONCAT(first_name, ' ', IFNULL(middle_name, ''), ' ', last_name) AS name FROM tblemployees WHERE emp_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $empId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        return $row['name'];
    }
    return null;
}

function getLeaveTypeDescription($leaveTypeId)
{
    global $conn;
    $query = "SELECT leave_type FROM tblleavetype WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $leaveTypeId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        return $row['leave_type'];
    }
    return null;
}

function getSupervisorInfo($empId)
{
    global $conn;
    $query = "SELECT s.email_id AS supervisor_email, CONCAT(s.first_name, ' ', IFNULL(s.middle_name, ''), ' ', s.last_name) AS supervisor_name
              FROM tblemployees e
              JOIN tblemployees s ON e.supervisor_id = s.emp_id
              WHERE e.emp_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $empId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        return [
            'email' => $row['supervisor_email'],
            'name' => $row['supervisor_name']
        ];
    }
    return null;
}

function insertLeaveRequest($empId, $leaveTypeId, $startDate, $endDate, $numberDays, $remarks, $sickFile)
{
    global $conn;

    $availableDays = getAvailableDays($empId, $leaveTypeId);
    if ($availableDays < $numberDays) {
        echo json_encode(['status' => 'error', 'message' => 'Not enough available days.']);
        exit;
    }

    $sickFileName = null;
    if (!empty($sickFile['name'])) {
        $fileTmpPath = $sickFile['tmp_name'];
        $fileName = $sickFile['name'];
        $fileSize = $sickFile['size'];
        $fileType = $sickFile['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $allowedfileExtensions = ['pdf', 'jpg', 'jpeg', 'png'];

        if (in_array($fileExtension, $allowedfileExtensions)) {
            $uploadFileDir = '../sick/files/';
            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $sickFileName = $newFileName;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error moving the file to the upload directory.']);
                exit;
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions)]);
            exit;
        }
    }

    $insertQuery = "INSERT INTO tblleave (leave_type_id, requested_days, from_date, to_date, created_date, leave_status, empid, remarks, sick_file)
                    VALUES (?, ?, ?, ?, NOW(), 0, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insertQuery);
    if ($stmt === false) {
        error_log("Failed to prepare statement: " . mysqli_error($conn));
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare the SQL statement.']);
        exit;
    }

    mysqli_stmt_bind_param($stmt, 'iississ', $leaveTypeId, $numberDays, $startDate, $endDate, $empId, $remarks, $sickFileName);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        $supervisorInfo = getSupervisorInfo($empId);
        $senderName = getEmployeeName($empId);
        $leaveType = getLeaveTypeDescription($leaveTypeId);

        if ($supervisorInfo && isValidEmail($supervisorInfo['email'])) {
            $emailSent = sendLeaveApplicationEmail($supervisorInfo['email'], $senderName, $startDate, $endDate, $leaveType, $supervisorInfo['name']);
            if (!$emailSent) {
                echo json_encode(['status' => 'success', 'message' => 'Leave application was submitted successfully, but we encountered an issue sending the notification email to your supervisor.']);
                error_log("Leave application submitted successfully, but notification email failed.");
            } else {
                echo json_encode(['status' => 'success', 'message' => 'Your leave request has been submitted successfully, and a notification email has been sent to your supervisor.']);
            }
        } else {
            echo json_encode(['status' => 'success', 'message' => 'Leave submitted successfully, but the supervisor’s email address is not valid for receiving messages.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to submit leave request.']);
    }
    exit;
}

function getAllEmployeeEmails()
{
    global $conn;
    $query = "SELECT email_id FROM tblemployees";
    $result = mysqli_query($conn, $query);
    $emails = [];
    while ($row = mysqli_fetch_assoc($result)) {
        if (isValidEmail($row['email_id'])) {
            $emails[] = $row['email_id'];
        }
    }
    return $emails;
}

function updateStatus($id, $status)
{
    global $conn;

    
    $leaveQuery = "SELECT empid, leave_type_id, from_date, to_date, requested_days, leave_status FROM tblleave WHERE id = ?";
    $stmt = mysqli_prepare($conn, $leaveQuery);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $leaveResult = mysqli_stmt_get_result($stmt);

    if ($leaveResult && mysqli_num_rows($leaveResult) > 0) {
        $leaveData = mysqli_fetch_assoc($leaveResult);
        $empId = $leaveData['empid'];
        $leaveTypeId = $leaveData['leave_type_id'];
        $fromDate = $leaveData['from_date'];
        $toDate = $leaveData['to_date'];
        $requestedDays = $leaveData['requested_days'];
        $currentStatus = $leaveData['leave_status'];

        if ($status == 3 && $currentStatus == 1) {  
            $remainingDays = calculateBusinessDays($fromDate, $toDate);
            $updateLeaveTypeQuery = "UPDATE employee_leave_types 
                                     SET available_days = available_days + ? 
                                     WHERE emp_id = ? AND leave_type_id = ?";
            $stmt = mysqli_prepare($conn, $updateLeaveTypeQuery);
            mysqli_stmt_bind_param($stmt, 'iii', $remainingDays, $empId, $leaveTypeId);
            mysqli_stmt_execute($stmt);
        } elseif ($status == 1) {  
            $updateLeaveTypeQuery = "UPDATE employee_leave_types 
                                     SET available_days = available_days - ? 
                                     WHERE emp_id = ? AND leave_type_id = ?";
            $stmt = mysqli_prepare($conn, $updateLeaveTypeQuery);
            mysqli_stmt_bind_param($stmt, 'iii', $requestedDays, $empId, $leaveTypeId);
            mysqli_stmt_execute($stmt);
        }

        
        $updateQuery = "UPDATE tblleave SET leave_status = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $updateQuery);
        mysqli_stmt_bind_param($stmt, 'ii', $status, $id);
        $result = mysqli_stmt_execute($stmt);

        $employeeEmails = getAllEmployeeEmails();
        $senderName = getEmployeeName($empId);
        $leaveType = getLeaveTypeDescription($leaveTypeId);

        if ($result) {
            if ($status == 1 || $status == 3) {
                if (sendLeaveNotification($employeeEmails, $senderName, $fromDate, $toDate, $leaveType, $status)) {
                    $response = array('status' => 'success', 'message' => 'Leave status updated and notifications sent successfully.');
                } else {
                    $response = array('status' => 'success', 'message' => 'Leave status updated, but failed to send notifications.');
                }
            } else {
                $response = array('status' => 'success', 'message' => 'Leave status updated successfully.');
            }
            echo json_encode($response);
            exit;
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to update leave status.');
            echo json_encode($response);
            exit;
        }
    } else {
        $response = array('status' => 'error', 'message' => 'Leave request not found.');
        echo json_encode($response);
        exit;
    }
}

if (isset($_POST['action']) && $_POST['action'] === 'apply-leave') {
    $empId = $_POST['empId'];
    $leaveTypeId = $_POST['leave_type'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $numberDays = $_POST['number_days'];
    $remarks = isset($_POST['remarks']) ? $_POST['remarks'] : '';
    $sickFile = isset($_FILES['sick_file']) ? $_FILES['sick_file'] : null;

    $response = insertLeaveRequest($empId, $leaveTypeId, $startDate, $endDate, $numberDays, $remarks, $sickFile);

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] === 'update-leave-status') {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $response = updateStatus($id, $status);
    echo $response;
} else if (isset($_POST['action']) && $_POST['action'] === 'delete-leave') {
    $id = $_POST['id'];
    $response = deleteLeave($id);
    echo $response;
}
?>

<?php

$searchQuery = isset($_POST['searchQuery']) ? $_POST['searchQuery'] : '';
$leaveStatusFilter = isset($_POST['leaveStatusFilter']) ? $_POST['leaveStatusFilter'] : 'Show all';

$userRole = $_SESSION['srole'];
$userId = $_SESSION['slogin'];
$userDepartment = $_SESSION['department'];
$isSupervisor = $_SESSION['is_supervisor'];


$statusMap = [
    'Pending' => 0,
    'Approved' => 1,
    'Cancelled' => 2,
    'Recalled' => 3,
    'Rejected' => 4
];


$leaveStatusValue = null;
if ($leaveStatusFilter !== 'Show all' && isset($statusMap[$leaveStatusFilter])) {
    $leaveStatusValue = $statusMap[$leaveStatusFilter];
}


$sql = "SELECT l.*, e.first_name, e.middle_name, e.last_name, e.image_path, e.designation, lt.leave_type, elt.available_days
        FROM tblleave l 
        JOIN tblemployees e ON l.empid = e.emp_id 
        JOIN tblleavetype lt ON l.leave_type_id = lt.id
        JOIN employee_leave_types elt ON l.empid = elt.emp_id AND l.leave_type_id = elt.leave_type_id";


$conditions = [];


if ($leaveStatusValue !== null) {
    $conditions[] = "l.leave_status = $leaveStatusValue";
}


if (!empty($searchQuery)) {
    
    $searchQueryEscaped = mysqli_real_escape_string($conn, $searchQuery);
    $conditions[] = "(e.first_name LIKE '%$searchQueryEscaped%' OR e.last_name LIKE '%$searchQueryEscaped%' 
                    OR e.designation LIKE '%$searchQueryEscaped%' OR lt.leave_type LIKE '%$searchQueryEscaped%')";
}


if ($userRole !== 'Admin') {
    if ($userRole === 'Manager') {
        $conditions[] = "e.department = '$userDepartment' AND l.empid != $userId";
    } elseif ($isSupervisor == 1) {
        $conditions[] = "e.supervisor_id = $userId AND l.empid != $userId";
    }
}


if (count($conditions) > 0) {
    $sql .= ' WHERE ' . implode(' AND ', $conditions);
}


$sql .= " ORDER BY CASE WHEN l.leave_status = 0 THEN 0 ELSE 1 END, l.created_date DESC";


$leaveData = []; 
$result = mysqli_query($conn, $sql);
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $leaveData[] = $row;
    }
}


$leaveTypes = [];
$leaveTypeResult = mysqli_query($conn, "SELECT id, leave_type FROM tblleavetype");
if ($leaveTypeResult && mysqli_num_rows($leaveTypeResult) > 0) {
    while ($row = mysqli_fetch_assoc($leaveTypeResult)) {
        $leaveTypes[$row['id']] = $row['leave_type'];
    }
}


if (empty($leaveData)) {
    echo '<div class="col-sm-12 text-center">
            <img src="../files/assets/images/no_data.png" class="img-radius" alt="No Data Found" style="width: 200px; height: auto;">
          </div>';
} else {
    foreach ($leaveData as $leave) {
        $imagePath = empty($leave['image_path']) ? '../files/assets/images/user-card/img-round1.jpg' : $leave['image_path'];
        $leaveStatusText = ($leave['leave_status'] == 0) ? 'Pending' : (($leave['leave_status'] == 1) ? 'Approved' : (($leave['leave_status'] == 2) ? 'Cancelled' : (($leave['leave_status'] == 3) ? 'Recalled' : 'Rejected')));
        $leaveTypeName = isset($leaveTypes[$leave['leave_type_id']]) ? $leaveTypes[$leave['leave_type_id']] : 'Unknown';

        $badgeClass = '';
        switch ($leave['leave_status']) {
            case 0:
                $badgeClass = 'bg-primary';
                break;
            case 1:
                $badgeClass = 'bg-success';
                break;
            case 2:
                $badgeClass = 'badge-warning';
                break;
            case 3:
                $badgeClass = 'badge-warning';
                break;
            case 4:  
                $badgeClass = 'badge-danger';
                break;
            default:
                $badgeClass = 'badge-warning';
                break;
        }

        
        $fromDate = date('jS F, Y', strtotime($leave['from_date']));
        $toDate = date('jS F, Y', strtotime($leave['to_date']));
        $postingDate = date('jS F, Y', strtotime($leave['created_date']));

        echo '<div class="col-md-15">
                    <div class="card">
                        <div class="card-header">
                            <div class="media">
                                <a class="media-left media-middle" href="#">
                                    <img class="media-object img-60" src="' . $imagePath . '" alt="Employee Image">
                                </a>
                                <div class="media-body media-middle">
                                    <div class="company-name">
                                        <p>' . $leave['first_name'] . ' ' . $leave['middle_name'] . ' ' . $leave['last_name'] . '</p>
                                        <span class="text-muted f-14">Tạo vào ngày ' . $postingDate . '</span>
                                    </div>
                                    <div class="job-badge">
                                        <label class="label ' . $badgeClass . '">' . $leaveStatusText . '</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-block">
                            <h6 class="job-card-desc">Kiểu nghỉ phép: ' . $leaveTypeName . '</h6>
                            <p class="text-muted">Yêu cầu nghỉ phép này dành cho khoảng thời gian từ: <strong>' . $fromDate . '</strong> đến: <strong>' . $toDate . '</strong></p>
                            <div class="job-meta-data"><i class="icofont icofont-safety"></i>Số ngày yêu cầu: ' . $leave['requested_days'] . '</div>
                            <div class="job-meta-data"><i class="icofont icofont-university"></i>Số ngày còn lại: ' . $leave['available_days'] . '</div>
                            <div class="text-right">
                               <div class="dropdown-secondary dropdown">
                                    <button class="btn btn-primary btn-mini waves-effect waves-light review-btn" 
                                        type="button" 
                                        data-toggle="modal" 
                                        data-target="#confirm-mail" 
                                        data-submission-date="' . $leave['created_date'] . '" 
                                        data-expiry-date="' . $leave['to_date'] . '" 
                                        data-start-date="' . $leave['from_date'] . '" 
                                        data-leave-reason="' . $leave['remarks'] . '" 
                                        data-leave-remaining="' . $leave['available_days'] . '" 
                                        data-leave-staff="' . $leave['first_name'] . ' ' . $leave['middle_name'] . ' ' . $leave['last_name'] . '" 
                                        data-leave-type="' . $leaveTypeName . '" 
                                        data-leave-status="' . $leaveStatusText . '" 
                                        data-leave-id="' . $leave['id'] . '" 
                                        data-requested-days="' . $leave['requested_days'] . '">
                                        Xem chi tiết
                                    </button>
                                    <!-- end of dropdown menu -->
                                </div>
                            </div></div>
                            </div>
                    </div>';
    }
}
?>



