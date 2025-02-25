<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
session_start();
include('../includes/config.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



$searchQuery = isset($_POST['searchQuery']) ? $_POST['searchQuery'] : '';
$leaveStatusFilter = isset($_POST['leaveStatusFilter']) ? $_POST['leaveStatusFilter'] : 'Show all';

$userId = $_SESSION['slogin'];


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

if (!empty($userId)) {
    $conditions[] = "e.emp_id = $userId";
}


if (!empty($searchQuery)) {
    
    $searchQueryEscaped = mysqli_real_escape_string($conn, $searchQuery);
    $conditions[] = "(e.first_name LIKE '%$searchQueryEscaped%' OR e.last_name LIKE '%$searchQueryEscaped%' 
                    OR e.designation LIKE '%$searchQueryEscaped%' OR lt.leave_type LIKE '%$searchQueryEscaped%')";
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
        $leaveStatusText = ($leave['leave_status'] == 0) ? 'Pending' : 
                           (($leave['leave_status'] == 1) ? 'Approved' : 
                           (($leave['leave_status'] == 2) ? 'Cancelled' : 
                           (($leave['leave_status'] == 3) ? 'Recalled' : 'Rejected')));
        $leaveTypeName = isset($leaveTypes[$leave['leave_type_id']]) ? $leaveTypes[$leave['leave_type_id']] : 'Unknown';

        $badgeClass = '';
        $iconClass = '';
        switch ($leave['leave_status']) {
            case 0:
                $badgeClass = 'bg-primary';
                $iconClass = 'fa fa-hourglass-start';
                break;
            case 1:
                $badgeClass = 'bg-success';
                $iconClass = 'fa fa-check-circle';
                break;
            case 2:
                $badgeClass = 'badge-warning';
                $iconClass = 'fa fa-times-circle';
                break;
            case 3:
                $badgeClass = 'badge-warning';
                $iconClass = 'fa fa-undo-alt';
                break;
            case 4:  
                $badgeClass = 'badge-danger';
                $iconClass = 'fa fa-ban';
                break;
            default:
                $badgeClass = 'badge-warning';
                $iconClass = 'fa fa-question-circle';
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
                                    <i class="label ' . $badgeClass . ' ' . $iconClass . ' fa-2x"></i>
                                </a>
                                <div class="media-body media-middle">
                                    <div class="company-name">
                                        <p>' . $leaveTypeName . '</p>
                                        <span class="text-muted f-14">Tạo vào ngày ' . $postingDate . '</span>
                                    </div>
                                    <div class="job-badge">
                                        <label class="label ' . $badgeClass . '">' . $leaveStatusText . '</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-block">
                            <p class="text-muted">Yêu cầu nghỉ phép này dành cho khoảng thời gian từ: <strong>' . $fromDate . '</strong> đến: <strong>' . $toDate . '</strong></p>
                            <div class="job-meta-data"><i class="icofont icofont-safety"></i>Số ngày yêu cầu: ' . $leave['requested_days'] . '</div>
                            <div class="text-right">
                               <div class="dropdown-secondary dropdown">
                                    <button class="btn btn-primary btn-mini dropdown-toggle waves-effect waves-light" type="button" id="dropdown1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Hành động</button>
                                    <div class="dropdown-menu" aria-labelledby="dropdown1" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                       <a class="dropdown-item waves-light waves-effect review-btn" href="#" data-toggle="modal" data-target="#detailed-leave" data-submission-date="' . $leave['created_date'] . '" data-expiry-date="' . $leave['to_date'] . '" data-start-date="' . $leave['from_date'] . '" data-leave-reason="' . $leave['remarks'] . '" data-leave-remaing="' . $leave['available_days'] . '" data-leave-staff="' . $leave['first_name'] . ' ' . $leave['middle_name'] . ' ' . $leave['last_name'] . '" data-leave-type="' . $leaveTypeName . '" data-leave-status="' . $leaveStatusText . '" data-leave-id="' . $leave['id'] . '" data-requested-days="' . $leave['requested_days'] . '">
                                            <span class="point-marker bg-danger"></span>Xem chi tiết
                                        </a>
                                        <a class="dropdown-item waves-light waves-effect" href="apply_leave.php?id=' . $leave['id'] . '&edit=1"><span class="point-marker bg-danger"></span>Sửa yêu cầu</a>
                                    </div>
                                    <!-- end of dropdown menu -->
                                </div>
                            </div></div>
                            </div>
                    </div>';
    }
}
?>