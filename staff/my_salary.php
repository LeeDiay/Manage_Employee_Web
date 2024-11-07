<?php include('../includes/header.php')?>
<?php
// Check if the user is logged in
if (!isset($_SESSION['slogin']) || !isset($_SESSION['srole'])) {
    header('Location: ../index.php');
    exit();
}

// Check if the user has the role of Manager or Admin
$userRole = $_SESSION['srole'];
if ($userRole !== 'Staff') {
    header('Location: ../index.php');
    exit();
}

$emp_id = $_SESSION['slogin'];

// Query to fetch the logged-in user's salary data
$sql = "SELECT s.salary_id, e.first_name, e.middle_name, e.last_name, s.base_salary, s.total_hours, s.leave_days, s.final_salary, s.date_generated
        FROM tblsalary s
        JOIN tblemployees e ON s.emp_id = e.emp_id
        WHERE s.emp_id = ? 
        ORDER BY s.date_generated DESC";


// Chuẩn bị truy vấn
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $emp_id);
$stmt->execute();
$result = $stmt->get_result();

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
                <?php $page_name = "salary_list"; ?>
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
                                                <h4>Bảng lương</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Page-header end -->

                                <!-- Page-body start -->
                                <div class="page-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>Thông tin lương của tôi</h5>
                                                </div>
                                                <div class="card-block">
                                                    <?php
                                                    if ($result->num_rows > 0) {
                                                        echo "<table class='table table-bordered'>
                                                                <thead>
                                                                    <tr>
                                                                        <th>ID Lương</th>
                                                                        <th>Tên Nhân viên</th>
                                                                        <th>Lương cơ bản</th>
                                                                        <th>Tổng giờ làm</th>
                                                                        <th>Số ngày nghỉ</th>
                                                                        <th>Lương thực nhận</th>
                                                                        <th>Ngày tạo</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>";
                                                        while ($row = $result->fetch_assoc()) {
                                                            $empName = $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'];
                                                            echo "<tr>
                                                                    <td>" . $row['salary_id'] . "</td>
                                                                    <td>" . $empName . "</td>
                                                                    <td>" . number_format($row['base_salary'], 0, ',', '.') . " VNĐ" ."</td>
                                                                    <td>" . $row['total_hours'] . "</td>
                                                                    <td>" . $row['leave_days'] . "</td>
                                                                    <td>" . number_format($row['final_salary'], 0, ',', '.') . " VNĐ" ."</td>
                                                                    <td>" . date('d-m-Y', strtotime($row['date_generated'])) . "</td>
                                                                  </tr>";
                                                        }
                                                        echo "</tbody></table>";
                                                    } else {
                                                        echo "<p>Không có dữ liệu lương.</p>";
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Page-body end -->
                            </div>
                        </div>
                        <!-- Main-body end -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Required Jquery -->
<?php include('../includes/scripts.php')?>

</body>
</html>
