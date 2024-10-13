<!DOCTYPE html>
<html lang="en">

<head>
    <title>Reset Password</title>
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
                                <div class="form-group form-primary">
                                    <input type="password" id="new_password" name="new_password" class="form-control password" required="" placeholder="New Password">
                                    <span class="form-bar"></span>
                                </div>
                                <div class="form-group form-primary">
                                    <input type="password" id="confirm_password" name="confirm_password" class="form-control password" required="" placeholder="Confirm Password">
                                    <span class="form-bar"></span>
                                </div>
                                <input type="hidden" id="token" name="token" value="<?php echo $_GET['token']; ?>">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button id="reset-password" type="submit" class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20"><i class="icofont icofont-lock"></i> Reset Password </button>
                                    </div>
                                </div>

                                <p class="text-inverse text-right">Back to <a href="index.php">Login</a></p>

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
