<!DOCTYPE html>
<html lang="en">

<head>
    <title>Adminty - Premium Admin Template by Colorlib</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="#">
    <meta name="keywords" content="Admin, Responsive, Landing, Bootstrap, App, Template, Mobile, iOS, Android, apple, creative app">
    <meta name="author" content="#">
    <link rel="icon" href="..\files\assets\images\favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,800" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href=".\files\bower_components\bootstrap\css\bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href=".\files\assets\icon\themify-icons\themify-icons.css">
    <link rel="stylesheet" type="text/css" href=".\files\assets\icon\icofont\css\icofont.css">
    <link rel="stylesheet" type="text/css" href=".\files\assets\css\style.css">
    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body class="fix-menu">
    <div class="theme-loader">
        <div class="ball-scale">
            <div class='contain'>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
            </div>
        </div>
    </div>

    <section class="login-block">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <form class="md-float-material form-material" id="resetPasswordForm">
                        <div class="auth-box card">
                            <div class="card-block">
                                <div class="row m-b-20">
                                    <div class="col-md-12">
                                        <h3 class="text-left">Recover your password</h3>
                                    </div>
                                </div>
                                <div class="form-group form-primary">
                                    <input type="email" name="email" class="form-control" required="" placeholder="Your Email Address">
                                    <span class="form-bar"></span>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20">Reset Password</button>
                                    </div>
                                </div>
                                <p class="f-w-600 text-right"><a href="index.php">Back to Login.</a></p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script type="text/javascript" src=".\files\bower_components\jquery\js\jquery.min.js"></script>
    <script type="text/javascript" src=".\files\bower_components\jquery-ui\js\jquery-ui.min.js"></script>
    <script type="text/javascript" src=".\files\bower_components\popper.js\js\popper.min.js"></script>
    <script type="text/javascript" src=".\files\bower_components\bootstrap\js\bootstrap.min.js"></script>
    <script type="text/javascript" src=".\files\bower_components\jquery-slimscroll\js\jquery.slimscroll.js"></script>
    <script type="text/javascript" src=".\files\bower_components\modernizr\js\modernizr.js"></script>
    <script type="text/javascript" src=".\files\bower_components\modernizr\js\css-scrollbars.js"></script>
    <script type="text/javascript" src=".\files\bower_components\i18next\js\i18next.min.js"></script>
    <script type="text/javascript" src=".\files\bower_components\i18next-xhr-backend\js\i18nextXHRBackend.min.js"></script>
    <script type="text/javascript" src=".\files\bower_components\i18next-browser-languagedetector\js\i18nextBrowserLanguageDetector.min.js"></script>
    <script type="text/javascript" src=".\files\bower_components\jquery-i18next\js\jquery-i18next.min.js"></script>
    <script type="text/javascript" src=".\files\assets\js\common-pages.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Bắt sự kiện submit của form
            $('#resetPasswordForm').on('submit', function(e) {
                e.preventDefault(); // Ngăn chặn hành động submit mặc định

                // Hiển thị cảnh chờ (loading) bằng SweetAlert
                Swal.fire({
                    title: 'Please wait...',
                    text: 'Processing your request',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading(); // Hiển thị loader
                    }
                });

                // Thêm action vào dữ liệu gửi đi
                var formData = $(this).serialize() + '&action=request_reset'; // Gửi email và action

                $.ajax({
                    type: 'POST',
                    url: 'forgot_password_function.php', // Đường dẫn đến file PHP xử lý
                    data: formData, // Gửi dữ liệu từ form
                    dataType: 'json', // Kiểu dữ liệu phản hồi
                    success: function(response) {
                        Swal.close(); // Đóng cảnh chờ khi có phản hồi

                        // Hiển thị thông báo kết quả bằng SweetAlert
                        if (response.status === 'success') {
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function() {
                        Swal.close(); // Đóng cảnh chờ khi xảy ra lỗi

                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred while processing your request.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
    </script>

</body>

</html>
