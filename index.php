<?php
    // Kết nối đến cơ sở dữ liệu
    include('includes/config.php');

    // Lấy dữ liệu nhân viên
    $query = "SELECT first_name, last_name, image_path FROM tblemployees WHERE role = 'Staff' ORDER BY emp_id LIMIT 3"; // Lấy 3 nhân viên xuất sắc nhất
    $result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="vi">
    <head>
        <title>Công ty phần mềm Đức Anh</title>
        <link rel="icon" href="uploads\images\logo.avif" type="image/png" />
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link
            rel="stylesheet"
            href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        />
        <link
            href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap"
            rel="stylesheet"
        />
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
        <head> </head>
    </head>

    <body>
        <div class="hero">
            <img
                src="uploads\images\logo.avif"
                alt="Logo Công Ty"
                class="mb-3 hero-logo"
            />
            <h1 class="hero-title">Công ty phần mềm Đức Anh</h1>
            <p class="hero-subtitle">
                Thành công của bạn là sứ mệnh của chúng tôi.
            </p>
        </div>
        <button
            type="button"
            class="btn btn-primary login-btn"
            data-toggle="modal"
            data-target="#loginModal"
        >
            Đăng nhập
        </button>
        <div class="container mt-5">
            <h2 class="text-center">Về Chúng Tôi</h2>
            <p class="text-center">
                Công ty Phần mềm Đức Anh, thành lập từ năm 2010, tự hào là một
                trong những đơn vị tiên phong trong lĩnh vực phát triển phần mềm
                tại Việt Nam. Chúng tôi chuyên cung cấp giải pháp phần mềm tùy
                chỉnh, phát triển ứng dụng di động và web, cùng với các dịch vụ
                tư vấn công nghệ thông tin. Với đội ngũ kỹ sư và chuyên gia giàu
                kinh nghiệm, Đức Anh cam kết mang đến những sản phẩm chất lượng
                cao, đáp ứng nhu cầu đa dạng của khách hàng. Chúng tôi tin rằng
                sự thành công của khách hàng chính là thành công của chúng tôi.
                Do đó, công ty luôn chú trọng vào việc lắng nghe và hiểu rõ yêu
                cầu của từng khách hàng, từ đó xây dựng các giải pháp tối ưu
                nhất. Bằng việc áp dụng công nghệ mới nhất và quy trình phát
                triển phần mềm hiện đại, Đức Anh không ngừng đổi mới và cải tiến
                để mang lại giá trị tốt nhất cho đối tác và khách hàng.
            </p>

            <h2 class="text-center mt-5">
                Top 3 Nhân Viên Xuất Sắc Nhất Tháng
            </h2>
            <div class="row text-center">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-4">
                    <div class="team-member">
                        <img
                            src="<?php echo 'uploads/images/' . basename($row['image_path']); ?>"
                            alt="Nhân viên"
                        />
                        <p></p>
                        <h4>
                            <?php echo $row['first_name'] . ' ' . $row['last_name']; ?>
                        </h4>
                        <p>Nhân viên xuất sắc tháng 11</p>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <!-- Phần Dịch Vụ Của Chúng Tôi -->
            <div class="container mt-5">
                <h2 class="text-center">Dịch Vụ Của Chúng Tôi</h2>
                <div class="row text-center">
                    <div class="col-md-4">
                        <div class="service-item">
                            <h4>Phát Triển Phần Mềm</h4>
                            <p>
                                Cung cấp giải pháp phần mềm tùy chỉnh theo nhu
                                cầu của khách hàng.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="service-item">
                            <h4>Ứng Dụng Di Động</h4>
                            <p>
                                Phát triển ứng dụng di động trên cả nền tảng
                                Android và iOS.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="service-item">
                            <h4>Tư Vấn CNTT</h4>
                            <p>
                                Cung cấp dịch vụ tư vấn công nghệ thông tin cho
                                doanh nghiệp.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Phần Các Đối Tác -->
            <div class="container mt-5">
                <h2 class="text-center">Các Đối Tác</h2>
                <div class="row text-center">
                    <div class="col-md-4">
                        <img
                            src="uploads/images/partner1.jpg"
                            alt="Đối tác 1"
                            class="img-fluid mb-2"
                        />
                        <p>Viettel Group</p>
                    </div>
                    <div class="col-md-4">
                        <img
                            src="uploads/images/partner1.jpg"
                            alt="Đối tác 2"
                            class="img-fluid mb-2"
                        />
                        <p>Viettel Group</p>
                    </div>
                    <div class="col-md-4">
                        <img
                            src="uploads/images/partner1.jpg"
                            alt="Đối tác 3"
                            class="img-fluid mb-2"
                        />
                        <p>Viettel Group</p>
                    </div>
                </div>
            </div>
            <h2 class="text-center mt-5">Phản Hồi Khách Hàng</h2>
            <div class="feedback text-center">
                <div class="feedback-item mb-4">
                    <blockquote class="blockquote">
                        <p class="mb-0">
                            "Dịch vụ tốt nhất mà tôi từng trải nghiệm!"
                        </p>
                        <footer class="blockquote-footer">
                            Một khách hàng hài lòng
                        </footer>
                    </blockquote>
                </div>
                <div class="feedback-item">
                    <blockquote class="blockquote">
                        <p class="mb-0">
                            "Sản phẩm tuyệt vời, hỗ trợ còn tốt hơn!"
                        </p>
                        <footer class="blockquote-footer">
                            Một khách hàng khác
                        </footer>
                    </blockquote>
                </div>
            </div>
        </div>

        <!-- Login Modal -->
        <div
            class="modal fade"
            id="loginModal"
            tabindex="-1"
            aria-labelledby="loginModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="loginModalLabel">
                            Đăng Nhập
                        </h5>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input
                                type="email"
                                id="email"
                                class="form-control"
                                placeholder="Địa Chỉ Email Của Bạn"
                                required
                            />
                        </div>
                        <div class="form-group">
                            <input
                                type="password"
                                id="password"
                                class="form-control"
                                placeholder="Mật Khẩu"
                                required
                            />
                        </div>
                        <div class="text-right">
                            <a href="forgot_password.php">Quên Mật Khẩu?</a>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal"
                        >
                            Đóng
                        </button>
                        <button
                            type="button"
                            id="login-form"
                            class="btn btn-primary"
                        >
                            Xác nhận
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer">
            <div class="container text-center">
                <p>&copy; 2024 ĐA Company. Bảo lưu mọi quyền.</p>

                <div class="footer-content">
                    <!-- Thông tin liên hệ -->
                    <div class="contact-info">
                        <h5>Thông Tin Liên Hệ</h5>
                        <p>Email: leducanh1503.works@gmail.com</p>
                        <p>Điện thoại: 0941312568</p>
                        <p>Địa chỉ: Hà Đông - Hà Nội</p>
                    </div>

                    <!-- Nhúng Google Maps -->
                    <div class="google-map">
                        <h5>Địa Điểm</h5>
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3725.2924013039146!2d105.78484157621108!3d20.98091298065658!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135accdd8a1ad71%3A0xa2f9b16036648187!2zSOG7jWMgdmnhu4duIEPDtG5nIG5naOG7hyBCxrB1IGNow61uaCB2aeG7hW4gdGjDtG5n!5e0!3m2!1svi!2s!4v1730045489594!5m2!1svi!2s"
                            width="600"
                            height="300"
                            style="border: 0"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                        ></iframe>
                    </div>
                </div>

                <!-- Liên kết đến Fanpage Facebook -->
                <div class="social-media">
                    <h5>Theo Dõi Chúng Tôi Trên Facebook</h5>
                    <a
                        href="https://www.facebook.com/lee.diay.5/"
                        target="_blank"
                    >
                        <img
                            src="https://upload.wikimedia.org/wikipedia/commons/5/51/Facebook_f_logo_%282019%29.svg"
                            alt="Facebook"
                            style="width: 30px; height: 30px"
                        />
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>
<script>
    $('#login-form').click(function (event) {
        event.preventDefault();

        var data = {
            email: $('#email').val(),
            password: $('#password').val(),
            action: "save"
        };

        if (data.email.trim() === '' || data.password.trim() === '') {
            Swal.fire({
                icon: 'warning',
                text: 'Vui lòng điền đầy đủ các trường.',
                confirmButtonColor: '#ffc107',
                confirmButtonText: 'OK'
            });
            return;
        }

        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(data.email)) {
            Swal.fire({
                icon: 'warning',
                text: 'Vui lòng nhập địa chỉ email hợp lệ.',
                confirmButtonColor: '#ffc107',
                confirmButtonText: 'OK'
            });
            return;
        }

        $.ajax({
            url: 'login.php',
            type: 'post',
            data: data,
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    let titleMessage = response.message + " với vai trò " + response.role;
                    if (!response.password_reset) {
                        titleMessage = "Vui lòng đặt lại mật khẩu để tiếp tục.";
                    }
                    Swal.fire({
                        icon: 'success',
                        title: titleMessage,
                        confirmButtonColor: '#01a9ac',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        $('#loginModal').modal('hide');
                        if (!response.password_reset) {
                            window.location = 'reset_password.php';
                        } else if (response.role === 'admin') {
                            window.location = 'admin/index.php';
                        } else if (response.role === 'manager') {
                            window.location = 'admin/index.php';
                        } else if (response.role === 'staff') {
                            window.location = 'staff/index.php';
                        } else {
                            Swal.fire({
                                icon: 'error',
                                text: 'Loại người dùng không hợp lệ hoặc có lỗi',
                                confirmButtonColor: '#eb3422',
                                confirmButtonText: 'Thử lại'
                            });
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        text: response.message,
                        confirmButtonColor: '#eb3422',
                        confirmButtonText: 'Thử lại'
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    text: 'Có lỗi xảy ra, vui lòng thử lại.',
                    confirmButtonColor: '#eb3422',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
    $(document).ready(function() {
        $('#loginModal').on('keypress', function(event) {
            if (event.which === 13) { // Kiểm tra phím Enter
                $('#login-form').click(); // Gọi hàm đăng nhập
            }
        });
    });
</script>
<style>
    body {
        font-family: "Montserrat", sans-serif;
        background-color: #f4f4f4;
    }

    .login-btn {
        position: absolute;
        top: 10px;
        right: 10px;
    }

    .hero {
        background: url("uploads/images/banner.jpg") no-repeat center center;
        background-size: cover;
        color: white;
        padding: 100px 0;
        text-align: center;
    }

    .hero h1 {
        font-weight: 700;
        font-size: 2.5rem;
    }

    .hero p {
        font-weight: 300;
        font-size: 1.25rem;
    }

    .team-member img {
        border-radius: 50%;
        width: 150px;
        height: 150px;
    }

    .team-member {
        margin: 15px;
        text-align: center;
    }

    .feedback {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin: 30px 0;
    }

    .footer {
        background-color: #f8f9fa;
        padding: 20px 0;
        text-align: center;
        border-top: 1px solid #dee2e6;
    }
    .hero {
        background: url("uploads/images/banner.jpg") no-repeat center center;
        background-size: cover;
        color: white;
        padding: 100px 0;
        text-align: center;
        position: relative; /* Thêm vị trí tương đối cho hero */
    }

    .hero-logo {
        border-radius: 50%; /* Bo tròn logo */
        width: 80px; /* Thay đổi kích thước logo nhỏ hơn */
        height: 80px; /* Thay đổi kích thước logo nhỏ hơn */
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5); /* Thêm bóng cho logo */
    }

    .hero-title {
        font-size: 2.5rem; /* Kích thước chữ cho tiêu đề */
        margin: 20px 0; /* Khoảng cách trên và dưới tiêu đề */
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7); /* Thêm bóng cho tiêu đề */
    }

    .hero-subtitle {
        font-size: 1.2rem; /* Kích thước chữ cho phụ đề */
        margin: 10px 0; /* Khoảng cách trên và dưới phụ đề */
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7); /* Thêm bóng cho phụ đề */
    }
    .footer {
        background-color: #f8f9fa; /* Màu nền cho footer */
        padding: 20px 0; /* Padding cho footer */
    }

    .footer-content {
        display: flex; /* Sử dụng Flexbox để chia cột */
        justify-content: space-between; /* Căn giữa khoảng cách giữa hai cột */
        align-items: flex-start; /* Căn chỉnh các cột lên trên */
        margin: 20px 0; /* Khoảng cách giữa các phần tử */
    }

    .contact-info,
    .google-map {
        width: 45%; /* Đặt chiều rộng cho mỗi cột */
    }

    .social-media {
        margin-top: 20px; /* Khoảng cách giữa phần thông tin và phần social media */
    }
</style>
