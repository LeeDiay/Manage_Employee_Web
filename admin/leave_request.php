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


$userRole = $_SESSION['srole'];
$userId = $_SESSION['slogin'];
$userDepartment = $_SESSION['department'];
$isSupervisor = $_SESSION['is_supervisor'];


$query = "
    SELECT l.id, l.leave_type_id, l.requested_days, l.from_date, l.to_date, l.remarks, l.created_date, l.reviewed_by, l.reviewed_date, l.leave_status, l.empid, e.first_name, e.middle_name, e.last_name 
    FROM tblleave l
    JOIN tblemployees e ON l.empid = e.emp_id
    JOIN tblleavetype lt ON l.leave_type_id = lt.id
";

$conditions = [];

if ($userRole !== 'Admin') {
    if ($userRole === 'Manager') {
        $conditions[] = "e.department = '$userDepartment' AND l.empid != $userId";
    } elseif ($isSupervisor == 1) {
        $conditions[] = "e.supervisor_id = $userId AND l.empid != $userId";
    }
}

if ($leaveStatusFilter !== 'Show all') {
    $conditions[] = "l.leave_status = ?";
    $selectedLeaveStatus = $leaveStatusFilter; 
}


if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}


$stmt = mysqli_prepare($conn, $query);

if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars(mysqli_error($conn)));
}

if ($leaveStatusFilter !== 'Show all') {
    
    mysqli_stmt_bind_param($stmt, "i", $selectedLeaveStatus);
}


if (!mysqli_stmt_execute($stmt)) {
    die('Execute failed: ' . htmlspecialchars(mysqli_stmt_error($stmt)));
}

mysqli_stmt_store_result($stmt);
mysqli_stmt_bind_result($stmt, $id, $leave_type_id, $requested_days, $from_date, $to_date, $remarks, $created_date, $reviewed_by, $reviewed_date, $leave_status, $empid, $first_name, $middle_name, $last_name);

$leaveData = [];

while (mysqli_stmt_fetch($stmt)) {
    $leaveData[] = [
        'id' => $id,
        'leave_type_id' => $leave_type_id,
        'requested_days' => $requested_days,
        'from_date' => $from_date,
        'to_date' => $to_date,
        'remarks' => $remarks,
        'created_date' => $created_date,
        'reviewed_by' => $reviewed_by,
        'reviewed_date' => $reviewed_date,
        'leave_status' => $leave_status,
        'empid' => $empid,
        'first_name' => $first_name,
        'middle_name' => $middle_name,
        'last_name' => $last_name
    ];
}

mysqli_stmt_close($stmt);

$leaveStatusMap = [
    0 => 'Pending',
    1 => 'Approved',
    2 => 'Cancelled',
    3 => 'Recalled',
    4 => 'Rejected'
];


$leaveStatusCounts = array_fill_keys(array_keys($leaveStatusMap), 0);
foreach ($leaveData as $leave) {
    $leaveStatus = $leave['leave_status'];
    if (isset($leaveStatusCounts[$leaveStatus])) {
        $leaveStatusCounts[$leaveStatus]++;
    } else {
        
        $leaveStatusCounts['Unknown']++;
    }
}
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
                  <?php $page_name = "leave_request"; ?>
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
                                                      <h4>Quản lí đơn nghỉ phép</h4>
                                                      <span>Xác minh và phản hồi yêu cầu nghỉ phép</span>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                                  <!-- Page-header end -->
                                    <!-- Page body start -->
                                    <div class="page-body">
                                      <div class="row">
                                            <div class="col-lg-12 filter-bar">
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
                                        </div>  
                                        <div class="row">
                                            <!-- Left column start -->
                                            <div id="leaveMain" class="col-lg-9">
                                                <div id="leaveContainer" class="job-card card-columns">
                                                    <!-- Populate it from leave_functions.php -->
                                                </div>
                                            </div>
                                            <!-- Left column end -->
                                            <!-- Right column start -->
                                            <div id="leaveInformation" class="col-lg-3">
                                                <!-- Leave Status card start -->
                                                <div class="card job-right-header">
                                                    <div class="card-header">
                                                        <h5>Thông tin trạng thái nghỉ phép</h5>
                                                        <!-- <div class="card-header-right">
                                                            <label class="label label-danger">Add</label>
                                                        </div> -->
                                                    </div>
                                                    <div class="card-block">
                                                        <form action="#">
                                                             <?php 
                                                                   foreach ($leaveStatusCounts as $status => $count) {
                                                                        if (isset($leaveStatusMap[$status])) {
                                                                            $leaveStatus = $leaveStatusMap[$status];
                                                                        } else {
                                                                            $leaveStatus = 'Unknown';
                                                                        }
                                                                        echo '<div class="checkbox-fade fade-in-primary">
                                                                                <label>
                                                                                    <input type="checkbox" value="" checked="checked" disabled>
                                                                                    <span class="cr">
                                                                                        <i class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                                                    </span>
                                                                                </label>
                                                                                <div> <a href="leave_request.php?leave_status='.$status.'">' . $leaveStatus . ' <span class="text-muted">(' . $count . ')</span> <a/></div>
                                                                            </div>';
                                                                    }
                                                                ?>
                                                            
                                                        </form>
                                                    </div>

                                                </div>
                                                <!-- Leave Status card end -->
                                            </div>
                                        </div>
                                        <!-- Right column end -->

                                        <!-- confirm mail start -->
                                        <div id="confirm-mail" class="modal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <div class="login-card card-block login-card-modal">
                                                    <form class="md-float-material">
                                                        <div class="card m-t-15">
                                                            <div class="auth-box card-block">
                                                            <div class="row m-b-20">
                                                                <div class="col-md-12 confirm">
                                                                    <h3 class="text-center txt-primary"><i class="icofont icofont-check-circled text-primary"></i>  Xem chi tiết</h3>
                                                                </div>
                                                            </div>
                                                            <input hidden type="text" class="form-control leave-id" name="leave-id">
                                                            <p class="text-inverse text-left m-t-15 f-16"><b>Xin chào <span id="modalReviewer"></span></b>, </p>
                                                            <p id="modalMessage" class="text-inverse text-left m-b-20"></p>
                                                            <ul class="text-inverse text-left m-b-30">
                                                                <li><strong>Kiểu nghỉ phép: </strong> <span id="modalLeaveType"></span></li>
                                                                <li><strong>Số ngày yêu cầu: </strong> <span id="modalRequestedDays"></span></li>
                                                                <li><strong>Số ngày còn lại: </strong> <span id="modalRemaing"></span></li>
                                                                <li><strong>Trạng thái: </strong> <span id="modalLeaveStatus"></span></li>
                                                            </ul>
                                                            <div class="card-block">
                                                                <div class="row" id="radioButtonsContainer">
                                                                    <!-- options will be dynamically inserted here -->
                                                                </div>
                                                                <!-- <span class="input-group-addon" id="basic-addon1"><i class="icofont icofont-verification-check"></i></span>
                                                                <input type="text" class="form-control" value="https:
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
                                        <!-- Confirm mail end-->

                                    </div>
                                </div>
                                    <!-- Page body start -->
                                </div>
                            </div>
                            <!-- Main-body end -->
                            <div id="styleSelector">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


<!-- Required Jquery -->
<?php include('../includes/scripts.php')?>

<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-23581568-13');
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('click', '.status-update', function(event) {
            console.log('Button item clicked');
            event.preventDefault();

            $('.modal').css('z-index', '1050');

            (async () => {
                const { value: formValues } = await Swal.fire({
                    title: 'Bạn chắc chứ?',
                    text: "Bạn muốn cập nhật lại trạng thái!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Chắc chắn!'
                });

                var selectedStatus = $('#select').val();
                const leaveId = $('.leave-id').val();

                console.log('leaveId:', leaveId); 
                console.log('selectedStatus:', selectedStatus); 

                if (formValues) {
                    var data = {
                        id: leaveId,
                        status: selectedStatus,
                        action: "update-leave-status"
                    };

                    console.log('Data HERE: ' + JSON.stringify(data));
                    $.ajax({
                        url: 'leave_functions.php',
                        type: 'post',
                        data: data,
                        success: function(response) {
                            console.log(`RESPONSE HERE: ${response}`);
                            const responseObject = JSON.parse(response);
                            console.log(`RESPONSE: ${response}`);
                            console.log(`RESPONSE HERE: ${responseObject}`);
                            console.log(`RESPONSE HERE: ${responseObject.message}`);
                            if (response && responseObject.status === 'success') {
                                
                                Swal.fire({
                                    icon: 'success',
                                    html: responseObject.message,
                                    confirmButtonColor: '#01a9ac',
                                    confirmButtonText: 'OK'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                });
                                
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    text: responseObject.message,
                                    confirmButtonColor: '#eb3422',
                                    confirmButtonText: 'OK'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log("AJAX error: " + error);
                            console.log('Data HERE: ' + JSON.stringify(data));
                            Swal.fire('Error!', 'Có lỗi xảy ra.', 'error');
                        }

                    });
                }
                
            })()
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        
        $(document).on('click', '.delete-leave', function(event) {
            event.preventDefault();
            const leaveId = $(this).data('id');
            const leaveStatus = $(this).data('status');

            console.log("LEAVE STATUS FOR DELETE HERE " + (leaveStatus == "Pending") +leaveStatus);

            if (leaveStatus !== "Pending" && leaveStatus !== "Cancelled") {
                Swal.fire({
                    icon: 'warning',
                    text: 'Xin vui lòng, bạn chỉ được phép xóa yêu cầu nghỉ phép đang chờ xử lý hoặc bị hủy.',
                    confirmButtonColor: '#ffc107',
                    confirmButtonText: 'OK'
                });
                return;
            }

            (async () => {
                const { value: formValues } = await Swal.fire({
                    title: 'Bạn có chắc không??',
                    text: "Bạn sẽ không thể khôi phục điều này!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Chắc chắn!'
                });

                if (formValues) {
                    var data = {
                        id: leaveId,
                        action: "delete-leave"
                    };

                    $.ajax({
                        url: 'leave_functions.php',
                        type: 'post',
                        data: data,
                        success: function(response) {
                            const responseObject = JSON.parse(response);
                            if (response && responseObject.status === 'success') {
                                
                                Swal.fire({
                                    icon: 'success',
                                    html: responseObject.message,
                                    confirmButtonColor: '#01a9ac',
                                    confirmButtonText: 'OK'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    text: responseObject.message,
                                    confirmButtonColor: '#eb3422',
                                    confirmButtonText: 'OK'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log("AJAX error: " + error);
                            Swal.fire('Error!', 'Có lỗi xảy ra.', 'error');
                        }
                    });
                }
            })();
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        
        var selectedStatus = '<?php echo $selectedLeaveStatusName; ?>';
        
        console.log('RESPONSE HERE: ' + selectedStatus);
        function fetchStaff() {
            var searchQuery = $('#searchInput').val(); 
            var leaveStatusFilter = (selectedStatus === 'Show all') ? '' : selectedStatus; 
            
            $.ajax({
                url: 'leave_functions.php', 
                type: 'POST',
                data: { searchQuery: searchQuery, leaveStatusFilter: leaveStatusFilter },
                
                success: function(response) {
                    
                    $('#leaveContainer').empty();
                     $('#leaveInformation').show();

                    console.log('RESPONSE HERE: ' + response);

                    
                    if (response.includes('files/assets/images/no_data.png')) {
                        console.log('No data image found in the response.');
                        
                        
                        $('#leaveMain').removeClass().addClass('col-sm-12');
                        $('#leaveInformation').hide();

                        
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
                        modalMessage = "Yêu cầu nghỉ phép do <b>" + staff + "</b> gửi vào ngày <b>" + formattedSubmissionDate + "</b> cho khoảng thời gian từ <b>" + formattedStartDate + "</b> đến <b>" + formattedEndDate + "</b> đang chờ xử lý, nhưng thời gian nghỉ phép đã qua. Đã quá muộn để phê duyệt hoặc từ chối yêu cầu này.";
                    } else {
                        modalMessage = "Bạn sắp xem xét yêu cầu nghỉ phép đang chờ xử lý của <b>" + staff + "</b> gửi vào ngày <b>" + formattedSubmissionDate + "</b> cho khoảng thời gian từ <b>" + formattedStartDate + "</b> đến <b>" + formattedEndDate + "</b>. Hãy xem xét kỹ chi tiết và quyết định phê duyệt hoặc từ chối yêu cầu.";
                    }
                    break;
                case 1: 
                    if (today < startDate) {
                        modalMessage = "Yêu cầu nghỉ phép của <b>" + staff + "</b> gửi vào ngày <b>" + formattedSubmissionDate + "</b> cho khoảng thời gian từ <b>" + formattedStartDate + "</b> đến <b>" + formattedEndDate + "</b> đã được phê duyệt. Bạn có thể thu hồi phê duyệt nếu cần.";
                    } else if (today >= startDate && today <= endDate) {
                        modalMessage = "Yêu cầu nghỉ phép của <b>" + staff + "</b> gửi vào ngày <b>" + formattedSubmissionDate + "</b> cho khoảng thời gian từ <b>" + formattedStartDate + "</b> đến <b>" + formattedEndDate + "</b> hiện đang trong quá trình nghỉ.";
                    } else {
                        modalMessage = "Yêu cầu nghỉ phép của <b>" + staff + "</b> gửi vào ngày <b>" + formattedSubmissionDate + "</b> cho khoảng thời gian từ <b>" + formattedStartDate + "</b> đến <b>" + formattedEndDate + "</b> đã hoàn thành.";
                    }
                    break;
                case 2: 
                    modalMessage = "Yêu cầu nghỉ phép của <b>" + staff + "</b> gửi vào ngày <b>" + formattedSubmissionDate + "</b> cho khoảng thời gian từ <b>" + formattedStartDate + "</b> đến <b>" + formattedEndDate + "</b> đã bị hủy.";
                    break;
                case 3: 
                    modalMessage = "Yêu cầu nghỉ phép đã phê duyệt của <b>" + staff + "</b> gửi vào ngày <b>" + formattedSubmissionDate + "</b> cho khoảng thời gian từ <b>" + formattedStartDate + "</b> đến <b>" + formattedEndDate + "</b> đã bị thu hồi.";
                    break;
                case 4: 
                    modalMessage = "Yêu cầu nghỉ phép của <b>" + staff + "</b> gửi vào ngày <b>" + formattedSubmissionDate + "</b> cho khoảng thời gian từ <b>" + formattedStartDate + "</b> đến <b>" + formattedEndDate + "</b> đã bị từ chối.";
                    break;
                default:
                    modalMessage = "Bạn sắp xem xét yêu cầu nghỉ phép của <b>" + staff + "</b> gửi vào ngày <b>" + formattedSubmissionDate + "</b> cho khoảng thời gian từ <b>" + formattedStartDate + "</b> đến <b>" + formattedEndDate + "</b>. Hãy xem xét kỹ chi tiết và quyết định phê duyệt hoặc từ chối yêu cầu.";
            }

            $('#modalMessage').html(modalMessage);
            
            
            if (leaveStatusValue === 0) { 
                if (today <= endDate) {
                    $('#radioButtonsContainer').append(`
                        <select name="select" id="select" class="form-control form-control-primary">
                            <option value="0" selected>Pending</option>
                            <option value="1">Approved</option>
                            <option value="2">Cancelled</option>
                            <option value="4">Rejected</option>
                        </select>
                    `);
                } else {
                    $('#radioButtonsContainer').append(`
                        <select name="select" id="select" class="form-control form-control-primary" disabled>
                            <option value="0" selected>Pending</option>
                        </select>
                    `);
                }
            } else if (leaveStatusValue === 1) { 
                if (today < startDate || (today >= startDate && today <= endDate)) {
                    $('#radioButtonsContainer').append(`
                        <select name="select" id="select" class="form-control form-control-primary">
                            <option value="1" selected>Approved</option>
                            <option value="3">Recalled</option>
                        </select>
                    `);
                } else {
                    $('#radioButtonsContainer').append(`
                        <select name="select" id="select" class="form-control form-control-primary" disabled>
                            <option value="1" selected>Approved</option>
                        </select>
                    `);
                }
            } else if (leaveStatusValue === 2) { 
                if (today < startDate) {
                    $('#radioButtonsContainer').append(`
                        <select name="select" id="select" class="form-control form-control-primary">
                            <option value="2" selected>Cancelled</option>
                            <option value="0">Pending</option>
                        </select>
                    `);
                } else {
                    $('#radioButtonsContainer').append(`
                        <select name="select" id="select" class="form-control form-control-primary" disabled>
                           <option value="2" selected>Cancelled</option>
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
                    updateButtonHTML = '<button type="button" class="btn btn-disabled btn-md btn-block waves-effect text-center status-update" disabled>Yêu cầu này đã <b style="color: #eb3422;"> HẾT HẠN </b></button>';
                } else {
                    updateButtonHTML = '<button type="button" class="btn btn-primary btn-md btn-block waves-effect text-center status-update">Cập nhật</button>';
                }
            } else if (leaveStatusValue === 1) { 
                if (today >= startDate && today <= endDate) {
                    updateButtonHTML = '<button type="button" class="btn btn-primary btn-md btn-block waves-effect text-center status-update">Cập nhật</button>';
                } else if (today < startDate) {
                    updateButtonHTML = '<button type="button" class="btn btn-primary btn-md btn-block waves-effect text-center status-update">Cập nhật</button>';
                } else {
                    updateButtonHTML = '<button type="button" class="btn btn-disabled btn-md btn-block waves-effect text-center status-update" disabled>Yêu cầu này đã <b style="color: #eb3422;"> HẾT HẠN </b></button>';
                }
            } else if (leaveStatusValue === 2) { 
                if (today < startDate) {
                    updateButtonHTML = '<button type="button" class="btn btn-primary btn-md btn-block waves-effect text-center status-update">Cập nhật</button>';
                } else {
                    updateButtonHTML = '<button type="button" class="btn btn-disabled btn-md btn-block waves-effect text-center status-update" disabled>Yêu cầu này đã <b style="color: #eb3422;"> BỊ HỦY </b></button>';
                }
            } else if (leaveStatusValue === 4) { 
                updateButtonHTML = '<button type="button" class="btn btn-disabled btn-md btn-block waves-effect text-center status-update" disabled>Yêu cầu này đã <b style="color: #eb3422;"> BỊ TỪ CHỐI </b></button>';
            } else if (leaveStatusValue === 3) { 
                updateButtonHTML = '<button type="button" class="btn btn-disabled btn-md btn-block waves-effect text-center status-update" disabled>Yêu cầu này đã <b style="color: #eb3422;"> BỊ THU HỒI </b></button>';
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

</body>

</html>
