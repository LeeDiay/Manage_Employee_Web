<?php include('../includes/header.php')?>
<?php include('../includes/utils.php')?>

<?php

if (!isset($_SESSION['slogin']) || !isset($_SESSION['srole'])) {
    header('Location: ../index.php');
    exit();
}


$userRole = $_SESSION['srole'];
if ($userRole !== 'Manager' && $userRole !== 'Admin') {
    header('Location: ../index.php');
    exit();
}




$leaveStatusFilter = isset($_GET['leave_status']) ? $_GET['leave_status'] : 'Show all';


$selectedLeaveStatus = null;
$selectedLeaveStatusName = 'Show all';

if ($leaveStatusFilter !== 'Show all') {
    switch ($leaveStatusFilter) {
        case '0':
            $selectedLeaveStatusName = 'Pending';
            break;
        case '1':
            $selectedLeaveStatusName = 'Approved';
            break;
        case '2':
            $selectedLeaveStatusName = 'Cancelled';
            break;
        case '3':
            $selectedLeaveStatusName = 'Recalled';
            break;
        case '4':
            $selectedLeaveStatusName = 'Rejected';
            break;
    }
}
?>

<?php

$userId = $_SESSION['slogin'];


$sql = "SELECT l.leave_type_id, l.from_date, l.to_date, l.requested_days, lt.leave_type
        FROM tblleave l
        JOIN tblleavetype lt ON l.leave_type_id = lt.id
        WHERE l.empid = ? AND l.leave_status = 1";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$leaveData = [];
while ($row = mysqli_fetch_assoc($result)) {
    $leaveData[] = $row;
}


$availableDays = [];
$sql = "SELECT leave_type_id, available_days FROM employee_leave_types WHERE emp_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

while ($row = mysqli_fetch_assoc($result)) {
    $availableDays[$row['leave_type_id']] = $row['available_days'];
}


$assignDays = [];
$sql = "SELECT id, assign_days FROM tblleavetype";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    $assignDays[$row['id']] = $row['assign_days'];
}


$leaveSummary = [];


foreach ($leaveData as $leave) {
    $leaveTypeId = $leave['leave_type_id'];
    $requestedDays = $leave['requested_days'];
    
    if (!isset($leaveSummary[$leaveTypeId])) {
        $leaveSummary[$leaveTypeId] = [
            'type' => $leave['leave_type'],
            'total' => $assignDays[$leaveTypeId] ?? 0,
            'remaining' => $availableDays[$leaveTypeId] ?? 0,
            'used' => 0
        ];
    }
 
    $leaveSummary[$leaveTypeId]['used'] = $leaveSummary[$leaveTypeId]['total'] - $leaveSummary[$leaveTypeId]['remaining'];

}


$leaveTypeCount = !empty($leaveSummary) ? '(' . count($leaveSummary) . ')' : '';

?>

<body>
    <!-- Pre-loader start -->
     <?php include('../includes/loader.php')?>
    <!-- Pre-loader end -->
    <div id="pcoded" class="pcoded">
        <div class="pcoded-overlay-box"></div>
        <div class="pcoded-container navbar-wrapper">

            <?php include('../includes/topbar.php')?>

            <div class="pcoded-main-container">
                <div class="pcoded-wrapper">

                   <?php $page_name = "my_leave"; ?>
                   <?php include('../includes/sidebar.php')?>
                   
                    <div class="pcoded-content">
                        <div class="pcoded-inner-content">
                            <!-- Main-body start -->
                            <div class="main-body">
                                <div class="page-wrapper">
                                    <!-- Page-header start -->
                                    <div class="page-header">
                                        <div class="row align-items-end">
                                            <div class="col-lg-8">
                                                <div class="page-header-title">
                                                    <div class="d-inline">
                                                        <h4>Yêu cầu nghỉ phép của tôi</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Page-header end -->
                                    <!-- Page-body start -->
                                    <div class="page-body">
                                        <div class="row">
                                            <!-- My leave start -->
                                            <div class="card col-xl-12 col-md-12">
                                                <div class="card-header">
                                                    <h5>Tóm tắt <?php echo $leaveTypeCount; ?></h5>
                                                    <div class="card-header-right">
                                                        <ul class="list-unstyled card-option">
                                                            <li><i class="feather icon-maximize full-card"></i></li>
                                                            <li><i class="feather icon-minus minimize-card"></i></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="row card-block">
                                                    <?php if (!empty($leaveSummary)): ?>
                                                        <!-- My leave type start -->
                                                        <?php foreach ($leaveSummary as $leaveTypeId => $summary): ?>
                                                            <div class="col-xl-4 col-md-12">
                                                                <div class="card statustic-card">
                                                                    <div class="card-header">
                                                                        <h5><?= htmlspecialchars($summary['type']) ?></h5>
                                                                    </div>
                                                                    <div class="card-block text-center">
                                                                        <div class="row">
                                                                            <div class="col">
                                                                                <span class="d-block text-c-blue f-30" style="font-weight: bold;"><?= $summary['total'] ?></span>
                                                                                <p class="m-b-0 text-c-blue">Tổng</p>
                                                                            </div>
                                                                            <div class="col">
                                                                                <span class="d-block text-c-green f-30" style="font-weight: bold;"><?= $summary['remaining'] ?></span>
                                                                                <p class="m-b-0 text-c-green">Còn lại</p>
                                                                            </div>
                                                                            <div class="col">
                                                                                <span class="d-block text-c-pink f-30" style="font-weight: bold;"><?= $summary['used'] ?></span>
                                                                                <p class="m-b-0 text-c-pink">Đã sử dụng</p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="progress">
                                                                            <div class="progress-bar bg-c-blue" style="width: <?= ($summary['total'] > 0 ? ($summary['used'] / $summary['total'] * 100) : 0) ?>%"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                        <!-- My leave type end -->
                                                    <?php else: ?>
                                                        <div class="col-12 text-center">
                                                            <div class="alert" style="color: #0c5460; background-color: #d1ecf1; border-color: #bee5eb;" role="alert">
                                                                <i class="fa fa-info-circle fa-3x"></i>
                                                                <p class="m-b-0">Chưa có loại nghỉ phép nào!</p>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <!-- My leave end -->

                                            <div class="col-xl-12 col-md-12 filter-bar">
                                                <!-- Nav Filter tab start -->
                                                <nav class="navbar navbar-light bg-faded m-b-30 p-10">
                                                    <ul class="nav navbar-nav">
                                                        <li class="nav-item active">
                                                            <a class="nav-link" href="#!">Lọc theo trạng thái: <span class="sr-only">(current)</span></a>
                                                        </li>
                                                        <!-- Your existing HTML for the dropdown -->
                                                        <li class="nav-item dropdown">
                                                            <a class="nav-link dropdown-toggle" href="#!" id="bystatus" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i class="icofont icofont-home"></i> <?php echo $selectedLeaveStatusName; ?>
                                                            </a>
                                                            <div class="dropdown-menu" aria-labelledby="bystatus">
                                                                <a class="dropdown-item <?php echo ($selectedLeaveStatusName === 'Show all') ? 'active' : ''; ?>" href="?leave_status=Show all">Xem tất cả</a>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item <?php echo ($selectedLeaveStatusName === 'Pending') ? 'active' : ''; ?>" href="?leave_status=0">Pending</a>
                                                                <a class="dropdown-item <?php echo ($selectedLeaveStatusName === 'Approved') ? 'active' : ''; ?>" href="?leave_status=1">Approved</a>
                                                                <a class="dropdown-item <?php echo ($selectedLeaveStatusName === 'Cancelled') ? 'active' : ''; ?>" href="?leave_status=2">Cancelled</a>
                                                                <a class="dropdown-item <?php echo ($selectedLeaveStatusName === 'Recalled') ? 'active' : ''; ?>" href="?leave_status=3">Recalled</a>
                                                                <a class="dropdown-item <?php echo ($selectedLeaveStatusName === 'Rejected') ? 'active' : ''; ?>" href="?leave_status=4">Rejected</a>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                    <div class="nav-item nav-grid">
                                                       <div class="input-group">
                                                            <input type="text" class="form-control" id="searchInput" placeholder="Tìm kiếm...">
                                                            <span class="input-group-addon" id="basic-addon1"><i class="icofont icofont-search"></i></span>
                                                        </div>
                                                    </div>
                                                    <!-- end of by priority dropdown -->
                                                </nav>
                                            </div>
                                            <div id="leaveMain" class="col-xl-12 col-md-12">
                                                <div id="leaveContainer" class="job-card card-columns">
                                                    <!-- Populate it from leave_functions.php -->
                                                </div>
                                            </div>
                                            <!-- Detailed Leave start -->
                                            <div id="detailed-leave" class="modal fade" role="dialog">
                                                <div class="modal-dialog">
                                                    <div class="login-card card-block login-card-modal">
                                                        <form class="md-float-material">
                                                            <div class="card m-t-15">
                                                                <div class="auth-box card-block">
                                                                <div class="row m-b-20">
                                                                    <div class="col-md-12 confirm">
                                                                        <h3 class="text-center txt-primary"><i class="icofont icofont-check-circled text-primary"></i>  Chi tiết</h3>
                                                                    </div>
                                                                </div>
                                                                <input hidden type="text" class="form-control leave-id" name="leave-id">
                                                                <p class="text-inverse text-left m-t-15 f-16"><b>Xin chào <span id="modalReviewer"></span></b>, </p>
                                                                <p id="modalMessage" class="text-inverse text-left m-b-20"></p>
                                                                <ul class="text-inverse text-left m-b-30">
                                                                    <li><strong>Loại nghỉ phép: </strong> <span id="modalLeaveType"></span></li>
                                                                    <li><strong>Số ngày yêu cầu: </strong> <span id="modalRequestedDays"></span></li>
                                                                    <li><strong>Số ngày còn lại: </strong> <span id="modalRemaing"></span></li>
                                                                    <li><strong>Trạng thái đơn: </strong> <span id="modalLeaveStatus"></span></li>
                                                                </ul>
                                                                <div class="card-block">
                                                                    <div class="row" id="radioButtonsContainer">
                                                                        <!-- options will be dynamically inserted here -->
                                                                    </div>
                                                                    
                                                                </div>
                                                                <div class="row m-t-15">
                                                                    <div class="col-md-12">
                                                                        <button type="button" class="btn btn-primary btn-md btn-block waves-effect text-center">Cập nhật</button>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                <div class="col-md-12">
                                                                    <p class="text-inverse text-left m-b-0 m-t-10"></p>
                                                                    <p class="text-inverse text-left"><b></b></p>
                                                                </div>
                                                            </div>        
                                                            </div>
                                                            </div>
                                                        </form>
                                                        <!-- end of form -->
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Detailed Leave end-->
                                        </div>
                                    </div>
                                    <!-- Page-body end -->
                                </div>
                                <div id="styleSelector"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
    <!-- Required Jquery -->
    <?php include('../includes/scripts.php')?>

    <script type="text/javascript">
        $(document).ready(function() {
            
            var selectedStatus = '<?php echo $selectedLeaveStatusName; ?>';
            
            console.log('RESPONSE HERE: ' + selectedStatus);
            function fetchStaff() {
                var searchQuery = $('#searchInput').val(); 
                var leaveStatusFilter = (selectedStatus === 'Show all') ? '' : selectedStatus; 
                
                $.ajax({
                    url: 'my_leave_function.php', 
                    type: 'POST',
                    data: { searchQuery: searchQuery, leaveStatusFilter: leaveStatusFilter },
                    
                    success: function(response) {
                        
                        $('#leaveContainer').empty();

                        console.log('RESPONSE HERE: ' + response);

                        
                        if (response.includes('files/assets/images/no_data.png')) {
                            console.log('No data image found in the response.');
                            
                            
                            $('#leaveMain').removeClass().addClass('col-sm-12');

                            
                            $('#leaveContainer').removeClass();

                            
                            $('#leaveContainer').append(response);
                        } else {
                            
                            $('#leaveContainer').append(response);
                        }
                    }
                });
            }
            
            $('#searchInput').on('keyup', function() {
                fetchStaff();
            });

            
            $('#bystatus .dropdown-item').on('click', function(event) {
                event.preventDefault();
                
                selectedStatus = $(this).text().trim();
                $('#bystatus').text(selectedStatus);

                
                fetchStaff();
            });

            
            fetchStaff();
        });

        $(document).ready(function() {
            
            function formatDate(date) {
                var day = date.getDate();
                var month = date.toLocaleString('default', { month: 'long' });
                var year = date.getFullYear();
                
                
                var suffix = 'th';
                if (day % 10 === 1 && day !== 11) {
                    suffix = 'st';
                } else if (day % 10 === 2 && day !== 12) {
                    suffix = 'nd';
                } else if (day % 10 === 3 && day !== 13) {
                    suffix = 'rd';
                }
                
                return day + suffix + ' ' + month + ', ' + year;
            }

            
            $(document).on('click', '.review-btn', function() {
                
                var leaveType = $(this).data('leave-type');
                var reason = $(this).data('leave-reason');
                var remaing = $(this).data('leave-remaing');
                var requestedDays = $(this).data('requested-days');
                var staff = $(this).data('leave-staff');
                var leaveStatus = $(this).data('leave-status');
                var leaveId = $(this).data('leave-id');
                var startDate = new Date($(this).data('start-date')); 
                var endDate = new Date($(this).data('expiry-date')); 
                var submissionDate = new Date($(this).data('submission-date'));
                var reviewer = '<?php echo ($session_sfirstname ? $session_sfirstname : '') . " " . ($session_smiddlename ? $session_smiddlename : '') . " " . ($session_slastname ? $session_slastname : ''); ?>';
                
                
                var statusMap = {
                    "Pending": 0,
                    "Approved": 1,
                    "Cancelled": 2,
                    "Recalled": 3,
                    "Rejected": 4
                };
                
                
                var leaveStatusValue = statusMap[leaveStatus];

                
                $('#modalLeaveType').text(leaveType);
                $('#modalRequester').text(staff);
                $('#modalReviewer').text(reviewer);
                $('#modalRequestedDays').text(requestedDays);
                $('#modalRemaing').text(remaing);
                $('#modalLeaveStatus').text(leaveStatus);
                $('#modalLeaveId').text(leaveId);

                $('.leave-id').val(leaveId);

                
                $('#radioButtonsContainer').empty();

                var today = new Date();

                console.log("COMPARE: " + (today < startDate) +startDate +today );
                console.log("COMPARE: " +endDate);

                var formattedSubmissionDate = formatDate(submissionDate);
                var formattedStartDate = formatDate(startDate);
                var formattedEndDate = formatDate(endDate);

                switch (leaveStatus) {
                    case "Pending":
                        $('#modalLeaveStatus').addClass('text-primary');
                        break;
                    case "Approved":
                        $('#modalLeaveStatus').addClass('text-success');
                        break;
                    case "Cancelled":
                        $('#modalLeaveStatus').addClass('text-warning');
                        break;
                    case "Recalled":
                        $('#modalLeaveStatus').addClass('text-info');
                        break;
                    case "Rejected":
                        $('#modalLeaveStatus').addClass('text-danger');
                        break;
                    default:
                        
                        break;
                }

                var modalMessage;
                switch (leaveStatusValue) {
                    case 0: 
                        if (today > endDate) {
                            modalMessage = "Yêu cầu nghỉ phép của bạn được nộp vào <b>" + formattedSubmissionDate + "</b> từ <b>" + formattedStartDate + "</b> đến <b>" + formattedEndDate + "</b> đang chờ xử lý nhưng thời gian nghỉ phép được yêu cầu đã trôi qua. Đã quá muộn để phê duyệt hoặc từ chối yêu cầu này.";
                        } else {
                            modalMessage = "Đây là yêu cầu nghỉ phép đang chờ xử lý của bạn được gửi vào <b>" + formattedSubmissionDate + "</b> trong khoảng thời gian từ <b>" + formattedStartDate + "</b> đến <b>" + formattedEndDate + "</b>. Vui lòng nhắc nhở người giám sát của bạn nếu yêu cầu nghỉ phép này cần thời gian để xem xét.";
                        }
                        break;
                    case 1: 
                        if (today < startDate) {
                            modalMessage = "Yêu cầu nghỉ phép của bạn được nộp vào <b>" + formattedSubmissionDate + "</b> trong khoảng thời gian từ <b>" + formattedStartDate + "</b> đến <b>" + formattedEndDate + "</b> đã được phê duyệt. Bạn có thể chọn thu hồi phê duyệt nếu cần.";
                        } else if (today >= startDate && today <= endDate) {
                            modalMessage = "Yêu cầu nghỉ phép của bạn được nộp vào <b>" + formattedSubmissionDate + "</b> trong khoảng thời gian từ <b>" + formattedStartDate + "</b> đến <b>" + formattedEndDate + "</b> hiện đang được tiến hành.";
                        } else {
                            modalMessage = "Yêu cầu nghỉ phép của bạn được nộp vào <b>" + formattedSubmissionDate + "</b> trong khoảng thời gian từ <b>" + formattedStartDate + "</b> đến <b>" + formattedEndDate + "</b> đã được hoàn thành.";
                        }
                        break;
                    case 2: 
                        modalMessage = "Yêu cầu nghỉ phép của bạn được nộp vào <b>" + formattedSubmissionDate + "</b> trong khoảng thời gian từ <b>" + formattedStartDate + "</b> đến <b>" + formattedEndDate + "</b> đã bị hủy bỏ.";
                        break;
                    case 3: 
                        modalMessage = "Yêu cầu nghỉ phép của bạn được nộp vào <b>" + formattedSubmissionDate + "</b> trong khoảng thời gian từ <b>" + formattedStartDate + "</b> đến <b>" + formattedEndDate + "</b> đã được thu hồi.";
                        break;
                    case 4: 
                        modalMessage = "Yêu cầu nghỉ phép của bạn được nộp vào <b>" + formattedSubmissionDate + "</b> trong khoảng thời gian từ <b>" + formattedStartDate + "</b> đến <b>" + formattedEndDate + "</b> đã bị từ chối.";
                        break;
                    default:
                        modalMessage = "Bạn sắp xem xét yêu cầu nghỉ phép đã gửi vào <b>" + formattedSubmissionDate + "</b> trong khoảng thời gian từ <b>" + formattedStartDate + "</b> đến <b>" + formattedEndDate + "</b>. Vui lòng xem xét cẩn thận các chi tiết và quyết định xem nên chấp thuận hay từ chối yêu cầu.";
                }
                $('#modalMessage').html(modalMessage);
                
                
                if (leaveStatusValue === 0) { 
                    if (today <= endDate) {
                        $('#radioButtonsContainer').append(`
                            <select name="select" id="select" class="form-control form-control-primary">
                                <option value="0" selected>Pending</option>
                                <option value="2">Cancelled</option>
                            </select>
                        `);
                    } else {
                        $('#radioButtonsContainer').append(`
                            <select name="select" id="select" class="form-control form-control-primary" disabled>
                                <option value="0" selected>Pending</option>
                            </select>
                        `);
                    }
                } 
                
                else {
                    $('#radioButtonsContainer').append(`
                        <select name="select" id="select" class="form-control form-control-primary" disabled>
                            <option value="${leaveStatusValue}" selected>${leaveStatus}</option>
                        </select>
                    `);
                }

                
                var updateButtonHTML;
                if (leaveStatusValue === 0) { 
                    if (today > endDate) {
                        updateButtonHTML = '<button type="button" class="btn btn-disabled btn-md btn-block waves-effect text-center status-update" disabled>Yêu cầu này đã được <b style="color: #eb3422;"> thông qua </b></button>';
                    } else {
                        updateButtonHTML = '<button type="button" class="btn btn-primary btn-md btn-block waves-effect text-center status-update">Cập nhật</button>';
                    }
                } else if (leaveStatusValue === 1) { 
                    if (today >= startDate && today <= endDate) {
                        updateButtonHTML = '<button type="button" class="btn btn-primary btn-md btn-block waves-effect text-center status-update">Cập nhật</button>';
                    } else if (today < startDate) {
                        updateButtonHTML = '<button type="button" class="btn btn-primary btn-md btn-block waves-effect text-center status-update">Cập nhật</button>';
                    } else {
                        updateButtonHTML = '<button type="button" class="btn btn-disabled btn-md btn-block waves-effect text-center status-update" disabled>Yêu cầu đã <b style="color: #eb3422;"> hết hạn </b></button>';
                    }
                } else if (leaveStatusValue === 2) { 
                    if (today < startDate) {
                        updateButtonHTML = '<button type="button" class="btn btn-primary btn-md btn-block waves-effect text-center status-update">Cập nhật</button>';
                    } else {
                        updateButtonHTML = '<button type="button" class="btn btn-disabled btn-md btn-block waves-effect text-center status-update" disabled>Yêu cầu đã <b style="color: #eb3422;"> bị hủy bỏ. </b></button>';
                    }
                } else if (leaveStatusValue === 4) { 
                    updateButtonHTML = '<button type="button" class="btn btn-disabled btn-md btn-block waves-effect text-center status-update" disabled>Yêu cầu đã <b style="color: #eb3422;"> bị từ chối </b></button>';
                } else if (leaveStatusValue === 3) { 
                    updateButtonHTML = '<button type="button" class="btn btn-disabled btn-md btn-block waves-effect text-center status-update" disabled>Yêu cầu đã <b style="color: #eb3422;"> bị thu hồi </b></button>';
                }

                
                $('.row.m-t-15 .col-md-12').html(updateButtonHTML);

                function performInitialCheck() {
                    var stat = $('#select').val();
                    console.log("COMPARE: " + stat);
                    
                    if (leaveStatusValue == stat) {
                        
                        $('.status-update').prop('disabled', true).removeClass('btn-primary').addClass('btn-disabled');
                    } else {
                        
                        $('.status-update').prop('disabled', false).removeClass('btn-disabled').addClass('btn-primary');
                    }
                }

                
                $('#select').val(leaveStatusValue);

                
                performInitialCheck();

                
                $('#select').change(performInitialCheck);

            });
        });
    </script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-23581568-13');
    </script>

</body>

</html>
