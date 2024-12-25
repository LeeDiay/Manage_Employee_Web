<!DOCTYPE html>
<html lang="en">

<head>
    <title>Lấy lại mật khẩu</title>
    <link rel="icon" href="uploads\images\logo.avif" type="image/png" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="./files/assets/images/favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,800" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="./files/bower_components/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="./files/assets/icon/themify-icons/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="./files/assets/icon/feather/css/feather.css">
    <link rel="stylesheet" type="text/css" href="./files/assets/icon/icofont/css/icofont.css">
    <link rel="stylesheet" type="text/css" href="./files/assets/css/style.css">
</head>


<?php include('includes/config.php'); ?>

<body class="fix-menu">
    <!-- Pre-loader start -->
    <?php include('includes/loader.php') ?>
    <!-- Pre-loader end -->

    <section class="login-block">
        <!-- Container-fluid starts -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <!-- Reset password card start -->
                    <form method="POST" class="md-float-material form-material">
                        <div class="auth-box card">
                            <div class="card-block">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3 class="text-center"><i class="feather icon-lock text-primary f-60 p-t-15 p-b-20 d-block"></i></h3>
                                    </div>
                                </div>
                                <div class="form-group form-primary position-relative">
                                    <input type="password" id="new_password" name="new_password" class="form-control password" 
                                        required="" placeholder="Nhập mật khẩu mới" style="padding-right: 40px;">
                                    <span class="form-bar"></span>
                                    <span id="toggleNewPassword" class="position-absolute" 
                                        style="right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                                        <i class="fa fa-eye" style="color: black;"></i>
                                    </span>
                                </div>

                                <div class="form-group form-primary position-relative">
                                    <input type="password" id="confirm_password" name="confirm_password" class="form-control password" 
                                        required="" placeholder="Nhập lại mật khẩu" style="padding-right: 40px;">
                                    <span class="form-bar"></span>
                                    <span id="toggleConfirmPassword" class="position-absolute" 
                                        style="right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                                        <i class="fa fa-eye" style="color: black;"></i>
                                    </span>
                                </div>

                                <!-- CSS -->
                                <style>
                                    .fa-eye, .fa-eye-slash {
                                        color: black; /* Đặt màu đen cho icon */
                                    }

                                    .position-relative {
                                        position: relative;
                                    }

                                    .form-control {
                                        padding-right: 40px; /* Tạo khoảng trống để icon không đè lên chữ */
                                    }

                                    #toggleNewPassword, #toggleConfirmPassword {
                                        right: 10px; /* Đặt icon ở góc phải */
                                        top: 50%;
                                        transform: translateY(-50%);
                                        position: absolute;
                                        cursor: pointer;
                                    }
                                </style>

                                <!-- JavaScript -->
                                <script>
                                    // Hiện/Ẩn mật khẩu cho trường "Nhập mật khẩu mới"
                                    const toggleNewPassword = document.getElementById('toggleNewPassword');
                                    const newPasswordField = document.getElementById('new_password');

                                    toggleNewPassword.addEventListener('click', () => {
                                        const type = newPasswordField.getAttribute('type') === 'password' ? 'text' : 'password';
                                        newPasswordField.setAttribute('type', type);

                                        // Đổi icon
                                        toggleNewPassword.innerHTML = type === 'text' 
                                            ? '<i class="fa fa-eye-slash" style="color: black;"></i>' 
                                            : '<i class="fa fa-eye" style="color: black;"></i>';
                                    });

                                    // Hiện/Ẩn mật khẩu cho trường "Nhập lại mật khẩu"
                                    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
                                    const confirmPasswordField = document.getElementById('confirm_password');

                                    toggleConfirmPassword.addEventListener('click', () => {
                                        const type = confirmPasswordField.getAttribute('type') === 'password' ? 'text' : 'password';
                                        confirmPasswordField.setAttribute('type', type);

                                        // Đổi icon
                                        toggleConfirmPassword.innerHTML = type === 'text' 
                                            ? '<i class="fa fa-eye-slash" style="color: black;"></i>' 
                                            : '<i class="fa fa-eye" style="color: black;"></i>';
                                    });
                                </script>

                                <!-- Font Awesome -->
                                <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />

                                <input type="hidden" id="token" name="token" value="<?php echo $_GET['token']; ?>">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button id="reset-password" type="submit" class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20"><i class="icofont icofont-lock"></i> Xác nhận </button>
                                    </div>
                                </div>

                                <p class="text-inverse text-right">Quay lại <a href="index.php">Đăng nhập</a></p>

                            </div>
                        </div>
                    </form>
                    <!-- Reset password card end -->
                </div>
                <!-- end of col-sm-12 -->
            </div>
            <!-- end of row -->
        </div>
        <!-- end of container-fluid -->
    </section>
    <!-- Required Jquery -->
    <script type="text/javascript" src="./files/bower_components/jquery/js/jquery.min.js"></script>
    <script type="text/javascript" src="./files/bower_components/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="./files/bower_components/jquery-slimscroll/js/jquery.slimscroll.js"></script>
    <script type="text/javascript" src="./files/assets/js/common-pages.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script type="text/javascript">
        $('#reset-password').click(function(event) {
            event.preventDefault(); // prevent the default form submission
            var newPassword = $('#new_password').val();
            var confirmPassword = $('#confirm_password').val();
            var resetToken = $('#token').val();  // Lấy token từ input ẩn

            if (newPassword.trim() === '' || confirmPassword.trim() === '') {
                Swal.fire({
                    icon: 'warning',
                    text: 'Please fill in all fields',
                    confirmButtonColor: '#ffc107',
                    confirmButtonText: 'OK'
                });
                return;
            }

            if (newPassword !== confirmPassword) {
                Swal.fire({
                    icon: 'warning',
                    text: 'Passwords do not match',
                    confirmButtonColor: '#ffc107',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Nếu mọi thứ đều ổn, gửi AJAX request đến reset_pass_function.php
            var data = {
                new_password: newPassword,
                confirm_password: confirmPassword,
                token: resetToken,  // Gửi token kèm theo
                action: "reset_password"
            };

            $.ajax({
                url: 'forgot_password_function.php',
                type: 'post',
                data: data,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: response.message,
                            confirmButtonColor: '#01a9ac',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location = 'index.php'; // Redirect to login page
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
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                }
            });
        });

    </script>

</body>

</html>
