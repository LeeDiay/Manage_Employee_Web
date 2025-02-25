<?php include('../includes/header.php')?>
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
                <?php $page_name = "new_staff"; ?>
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
                                                    <h4>Thêm nhân viên</h4>
                                                 </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                <!-- Page-header end -->
                                   
                                    <!-- Page body start -->
                                    <div class="page-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <?php
                                                    if(isset($_GET['edit']) && $_GET['edit'] == 1 && isset($_GET['id'])) {
                                                        $id = $_GET['id'];
                                                        $stmt = mysqli_prepare($conn, "SELECT * FROM tblemployees WHERE emp_id = ?");
                                                        mysqli_stmt_bind_param($stmt, "i", $id);
                                                        mysqli_stmt_execute($stmt);
                                                        $result = mysqli_stmt_get_result($stmt);
                                                        $row = mysqli_fetch_assoc($result);
                                                    }
                                                ?>
                                                <div class="card">
                                                    <div class="card-block">
                                                        <div class="row">
                                                            <div class="col-sm-6 mobile-inputs">
                                                                <h4 class="sub-title">Thông tin cá nhân</h4>
                                                                <input type="hidden" id="edit_id" name="edit_id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : 'null'; ?>">
                                                                <form enctype="multipart/form-data">
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12">
                                                                            <label for="userName-2" class="block">Chọn ảnh đại diện *</label>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <input type="file" id="image_path" name="image_path" class="form-control">
                                                                        </div>
                                                                        
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12">
                                                                            <label for="userName-2" class="block">Họ *</label>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <input type="text" id="firstname" name="firstname" autocomplete="off" class="form-control" placeholder="" value="<?php echo isset($row['first_name']) ? $row['first_name'] : ''; ?>">
                                                                        </div>
                                                                        
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12">
                                                                            <label for="userName-2" class="block">Tên đệm</label>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <input type="text" id="middlename" name="middlename" autocomplete="off" class="form-control" placeholder="" value="<?php echo isset($row['middle_name']) ? $row['middle_name'] : ''; ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12">
                                                                            <label for="userName-2" class="block">Tên *</label>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <input type="text" id="lastname" name="lastname" autocomplete="off" class="form-control" placeholder="" value="<?php echo isset($row['last_name']) ? $row['last_name'] : ''; ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12">
                                                                            <label for="userName-2" class="block">Số điện thoại *</label>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <input type="tel" id="contact" name="contact" autocomplete="off" class="form-control" placeholder="" value="<?php echo isset($row['phone_number']) ? $row['phone_number'] : ''; ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12">
                                                                            <label for="userName-2" class="block">Vị trí *</label>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <input type="text" id="designation" name="designation" autocomplete="off" class="form-control" placeholder="" value="<?php echo isset($row['designation']) ? $row['designation'] : ''; ?>">
                                                                        </div>
                                                                    </div>
                                                                    <h4 class="sub-title">Giới tính *</h4>
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12">
                                                                            <div class="form-radio">
                                                                                <div class="radio radiofill radio-inline">
                                                                                    <label>
                                                                                        <input type="radio" name="gender" value="Female" <?php echo (isset($row['gender']) && $row['gender'] === 'Female') ? 'checked="checked"' : ''; ?>>
                                                                                        <i class="helper"></i>Nữ
                                                                                    </label>
                                                                                </div>
                                                                                <div class="radio radiofill radio-inline">
                                                                                    <label>
                                                                                        <input type="radio" name="gender" value="Male" <?php echo (isset($row['gender']) && $row['gender'] === 'Male') ? 'checked="checked"' : ''; ?>>
                                                                                        <i class="helper"></i>Nam
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <div class="col-sm-6 mobile-inputs">
                                                                <h4 class="sub-title">Thông tin trong công ty</h4>
                                                                <div class="form-group row">
                                                                    <div class="col-sm-12">
                                                                        <label for="userName-2" class="block">Phòng ban *</label>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <select class="js-example-disabled-results col-sm-12" name="department" id="department" required>
                                                                            <?php
                                                                                
                                                                                if (!empty($row['department'])) {
                                                                                        
                                                                                        $stmt = mysqli_prepare($conn, "SELECT id, department_name FROM tbldepartments WHERE id = ?");
                                                                                        mysqli_stmt_bind_param($stmt, "i", $row['department']);
                                                                                        mysqli_stmt_execute($stmt);
                                                                                        mysqli_stmt_bind_result($stmt, $id, $name);
                                                                                        mysqli_stmt_fetch($stmt);
                                                                                        mysqli_stmt_close($stmt);
                                                                                        
                                                                                        echo '<option value="' . $id . '" selected>' . $name . '</option>';
                                                                                        
                                                                                        $stmt = mysqli_prepare($conn, "SELECT id, department_name, department_desc FROM tbldepartments");
                                                                                        mysqli_stmt_execute($stmt);
                                                                                        mysqli_stmt_store_result($stmt);
                                                                                        mysqli_stmt_bind_result($stmt, $id, $name, $description);
                                                                                        while (mysqli_stmt_fetch($stmt)) {
                                                                                            echo '<option value="' . $id . '">' . $name . '</option>';
                                                                                        }
                                                                                        mysqli_stmt_close($stmt);
                                                                                } else {
                                                                                    
                                                                                        echo '<option value="" disabled selected>Chọn phòng ban</option>';
                                                                                        
                                                                                        $stmt = mysqli_prepare($conn, "SELECT id, department_name, department_desc FROM tbldepartments");
                                                                                        mysqli_stmt_execute($stmt);
                                                                                        mysqli_stmt_store_result($stmt);
                                                                                        mysqli_stmt_bind_result($stmt, $id, $name, $description);
                                                                                        while (mysqli_stmt_fetch($stmt)) {
                                                                                            echo '<option value="' . $id . '">' . $name . '</option>';
                                                                                        }
                                                                                        mysqli_stmt_close($stmt);
                                                                                }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <div class="col-sm-12">
                                                                        <label for="userName-2" class="block">Mã nhân viên *</label>
                                                                    </div>
                                                                    <div class="col-sm-10">
                                                                        <input type="text" id="staff_id" name="staff_id" readonly autocomplete="off" class="form-control" placeholder="" value="<?php echo isset($row['staff_id']) ? $row['staff_id'] : ''; ?>">
                                                                    </div>
                                                                    <div class="col-sm-2">
                                                                        <i id="generate" class="fa fa-cog btn btn-primary" style="cursor: pointer; padding: 10px; border-radius: 5px;" aria-hidden="true" title="Generate ID"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <div class="col-sm-12">
                                                                        <label for="userName-2" class="block">Email *</label>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <input type="email" id="email" name="email" autocomplete="off" class="form-control" placeholder="" value="<?php echo isset($row['email_id']) ? $row['email_id'] : ''; ?>">
                                                                    </div>
                                                                </div>
                                                                <?php if(!isset($row) || empty($row)): ?>
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12">
                                                                            <label for="userName-2" class="block">Mật khẩu *</label>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <input type="password" placeholder="**********" id="password" name="password" autocomplete="off" class="form-control">
                                                                            <?php if(isset($row) && !empty($row)): ?>
                                                                                <label for="userName" class="block" style="font-style: italic; font-size: 12px;">Để trống nếu bạn không muốn đổi mật khẩu</label>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12">
                                                                            <label for="userName-2" class="block">Xác nhận mật khẩu *</label>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <input type="password" placeholder="**********" id="c_password" name="c_password" autocomplete="off" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>               
                                                                <h4 class="sub-title">Có phải là quản lí? *</h4>
                                                                <div class="form-group row">
                                                                    <div class="col-sm-12">
                                                                        <div class="form-radio">
                                                                            <div class="radio radiofill radio-inline">
                                                                                <label>
                                                                                    <input type="radio" name="is_supervisor" value="1" <?php echo (isset($row['is_supervisor']) && $row['is_supervisor'] == 1) ? 'checked="checked"' : ''; ?>>
                                                                                    <i class="helper"></i>Có
                                                                                </label>
                                                                            </div>
                                                                            <div class="radio radiofill radio-inline">
                                                                                <label>
                                                                                    <input type="radio" name="is_supervisor" value="0" <?php echo (isset($row['is_supervisor']) && $row['is_supervisor'] == 0) ? 'checked="checked"' : ''; ?>>
                                                                                    <i class="helper"></i>Không
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            
                                                                <h4 class="sub-title">Chức vụ</h4>
                                                                <div class="form-group row">
                                                                    <div class="col-sm-12">
                                                                        <div class="form-radio">
                                                                            <div class="radio radiofill radio-inline">
                                                                                <label>
                                                                                    <input type="radio" name="role" value="Staff" <?php echo (isset($row['role']) && $row['role'] === 'Staff') ? 'checked="checked"' : ''; ?>>
                                                                                    <i class="helper"></i>Nhân viên
                                                                                </label>
                                                                            </div>
                                                                            <div class="radio radiofill radio-inline">
                                                                                <label>
                                                                                    <input type="radio" name="role" value="Manager" <?php echo (isset($row['role']) && $row['role'] === 'Manager') ? 'checked="checked"' : ''; ?>>
                                                                                    <i class="helper"></i>Manager
                                                                                </label>
                                                                            </div>
                                                                            <?php if ($session_role == 'Admin'): ?>
                                                                                <div class="radio radiofill radio-inline">
                                                                                    <label>
                                                                                        <input type="radio" name="role" value="Admin" <?php echo (isset($row['role']) && $row['role'] === 'Admin') ? 'checked="checked"' : ''; ?>>
                                                                                        <i class="helper"></i>Admin
                                                                                    </label>
                                                                                </div>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>              
                                                            </div>
                                                       </div>
                                                       <label class="col-sm-5"></label>
                                                       <div class="row">
                                                            <label class="col-sm-5"></label>
                                                            <div class="col-sm-5">
                                                                <?php if(isset($row) && !empty($row)): ?>
                                                                    <button id="staff-update" type="submit" class="btn btn-primary m-b-0">Cập nhật</button>
                                                                <?php else: ?>
                                                                    <button id="staff-add" type="submit" class="btn btn-primary m-b-0">Gửi</button>
                                                                <?php endif; ?>
                                                            </div>
                                                       </div>
                                                    </div>
                                                </div>
                                                <!-- Basic Inputs Validation end -->
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Page body end -->
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
    </div>

    <!-- Required Jquery -->
    <?php include('../includes/scripts.php')?>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-23581568-13');
    </script>
    <script>
      $(document).ready(function() {
        $('#staff-update').click(function(event){
            event.preventDefault();
            (async () => {

                var editId = $('#edit_id').val();

                var requiredFields = ['firstname', 'lastname', 'contact', 'designation', 'department', 'email', 'staff_id'];
                var formData = new FormData();
                var isValid = true;

                for (var i = 0; i < requiredFields.length; i++) {
                    var field = requiredFields[i];
                    var value = $('#' + field).val();
                    
                    
                    if (value.trim() === '') {
                        Swal.fire({
                            icon: 'warning',
                            text: 'Vui lòng điền đầy đủ thông tin',
                            confirmButtonColor: '#ffc107',
                            confirmButtonText: 'OK'
                        });
                        isValid = false;
                        break; 
                    }

                    
                    formData.append(field, value);
                }

                if (!isValid) {
                    return; 
                }

                
                var selectedGender = $('input[name="gender"]:checked').val();
                if (!selectedGender) {
                    Swal.fire({
                        icon: 'warning',
                        text: 'Vui lòng chọn giới tính',
                        confirmButtonColor: '#ffc107',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                formData.append('gender', selectedGender);

                
                var selectedIsSupervisor = $('input[name="is_supervisor"]:checked').val();
                if (!selectedIsSupervisor) {
                    Swal.fire({
                        icon: 'warning',
                        text: 'Vui lòng chọn làm quản lí hay không',
                        confirmButtonColor: '#ffc107',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                formData.append('is_supervisor', selectedIsSupervisor);
                
                
                var selectedRole = $('input[name="role"]:checked').val();
                if (!selectedRole) {
                    Swal.fire({
                        icon: 'warning',
                        text: 'Vui lòng chọn chức vụ',
                        confirmButtonColor: '#ffc107',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                formData.append('role', selectedRole);

                
                var imageFile = $('#image_path')[0].files[0];

                
                var password = $('#password').val();
                var cPassword = $('#c_password').val();
                if (password !== cPassword) {
                    Swal.fire({
                        icon: 'warning',
                        text: 'Mật khẩu không khớp',
                        confirmButtonColor: '#ffc107',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                formData.append('edit_id', editId);
                formData.append('middlename', $('#middlename').val());
                formData.append('password', password); 
                formData.append('image_path', imageFile);
                formData.append('action', 'updateStaff');

                console.log('Data HERE: ' + JSON.stringify(formData));
                
                console.log('Data to be sent:', formData);
                $.ajax({
                    url: 'staff_functions.php',
                    type: 'post',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success:function(response){
                        console.log('Raw Response:', response);
                        response = JSON.parse(response);
                        console.log('RESPONSE HERE: ' + response.status)
                        console.log(`RESPONSE HERE: ${response.message}`);
                        if (response.status == 'success') {
                            Swal.fire({
                                icon: 'success',
                                html: response.message,
                                confirmButtonColor: '#01a9ac',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = "staff_list.php";
                                    
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                text: response.message,
                                confirmButtonColor: '#eb3422',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('AJAX Data HERE: ' + JSON.stringify(formData));
                        console.log("Response from server: " + jqXHR.responseText);
                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                    }
                });
            })()
        })
      });
    </script>
    <script>
        $('#staff-add').click(function(event){
            event.preventDefault(); 
            (async () => {
                
                var requiredFields = ['firstname', 'lastname', 'contact', 'designation', 'department', 'email', 'staff_id'];
                var formData = new FormData();
                var isValid = true;

                for (var i = 0; i < requiredFields.length; i++) {
                    var field = requiredFields[i];
                    var value = $('#' + field).val();
                    
                    
                    if (value.trim() === '') {
                        Swal.fire({
                            icon: 'warning',
                            text: 'Vui lòng điền đầy đủ thông tin',
                            confirmButtonColor: '#ffc107',
                            confirmButtonText: 'OK'
                        });
                        isValid = false;
                        break; 
                    }

                    
                    formData.append(field, value);
                }

                if (!isValid) {
                    return; 
                }

                
                var password = $('#password').val();
                var cPassword = $('#c_password').val();
                if (password === '') {
                    Swal.fire({
                        icon: 'warning',
                        text: 'Mật khẩu không được để trống',
                        confirmButtonColor: '#ffc107',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                
                if (password !== cPassword) {
                    Swal.fire({
                        icon: 'warning',
                        text: 'Mật khẩu không khớp',
                        confirmButtonColor: '#ffc107',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                
                formData.append('password', password);

                
                var selectedGender = $('input[name="gender"]:checked').val();
                if (!selectedGender) {
                    Swal.fire({
                        icon: 'warning',
                        text: 'Vui lòng chọn giới tính',
                        confirmButtonColor: '#ffc107',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                formData.append('gender', selectedGender);

                
                var selectedIsSupervisor = $('input[name="is_supervisor"]:checked').val();
                if (!selectedIsSupervisor) {
                    Swal.fire({
                        icon: 'warning',
                        text: 'Vui lòng chọn làm quản lí hay không',
                        confirmButtonColor: '#ffc107',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                formData.append('is_supervisor', selectedIsSupervisor);

                
                var selectedRole = $('input[name="role"]:checked').val();
                if (!selectedRole) {
                    Swal.fire({
                        icon: 'warning',
                        text: 'Vui lòng chọn chức vụ',
                        confirmButtonColor: '#ffc107',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                formData.append('role', selectedRole);

                
                var imageFile = $('#image_path')[0].files[0];
                if (!imageFile) {
                    Swal.fire({
                        icon: 'warning',
                        text: 'Vui lòng chọn ảnh đại diện',
                        confirmButtonColor: '#ffc107',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                formData.append('middlename', $('#middlename').val());
                formData.append('image_path', imageFile);
                formData.append('action', 'staff-add');                                                                     

                console.log('Data HERE: ' + JSON.stringify(formData));
                $.ajax({
                    url: 'staff_functions.php',
                    type: 'post',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success:function(response){
                        console.log('success function called');
                        response = JSON.parse(response);
                        console.log('RESPONSE HERE: ' + response.status)
                        console.log(`RESPONSE HERE: ${response.message}`);
                        if (response.status == 'success') {
                            Swal.fire({
                                icon: 'success',
                                html: response.message,
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
                                text: response.message,
                                confirmButtonColor: '#eb3422',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('AJAX Data HERE: ' + JSON.stringify(formData));
                        console.log("Response from server: " + jqXHR.responseText);
                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                    }
                });
            })()
        })
    </script>

    <script>
        $(document).ready(function() {
            $('#generate').on('click', function() {
                $.ajax({
                    url: 'generate_id.php',
                    type: 'GET',
                    success: function(response) {
                        $('#staff_id').val(response);
                    },
                    error: function() {
                        alert('Error generating ID');
                    }
                });
            });
        });
    </script>
 </body>

</html>
