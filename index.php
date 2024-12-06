<?php
    // Kết nối đến cơ sở dữ liệu
    include('includes/config.php');

    // Lấy dữ liệu nhân viên
    $query = "SELECT first_name, last_name, image_path FROM tblemployees WHERE role = 'Staff' ORDER BY emp_id LIMIT 5"; // Lấy 5 nhân viên xuất sắc nhất
    $result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Công ty phần mềm Đức Anh</title>
    <link rel="icon" href="uploads\images\logo.avif" type="image/png" />
    <meta name="description" content="">
    <meta name="keywords" content="">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap&subset=vietnamese"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&subset=vietnamese&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&subset=vietnamese&display=swap"
        rel="stylesheet">
    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="assets/css/main.css" rel="stylesheet">

</head>

<body class="index-page">

    <header id="header" class="header d-flex align-items-center fixed-top">
        <div class="container-fluid position-relative d-flex align-items-center justify-content-between">
            <a href="index.php" class="logo d-flex align-items-center me-auto me-xl-0">
                <!-- Uncomment the line below if you also wish to use an image logo -->
                <img src="uploads\images\logo.png" alt="">
                <h1 class="sitename">Công ty phần mềm Đức Anh</h1>
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="#hero" class="active">Trang chủ</a></li>
                    <li><a href="#about">Giới thiệu</a></li>
                    <li><a href="#services">Dịch vụ</a></li>
                    <li><a href="#team">Con người</a></li>
                    <li><a href="#contact">Liên hệ</a></li>
                </ul>
            </nav>

            <div class="header-login">
                <button class="btn btn-info login-button" data-toggle="modal" data-target="#loginModal">Đăng
                    nhập</button>
            </div>

        </div>
    </header>

    <main class="main">

        <!-- Hero Section -->
        <section id="hero" class="hero section dark-background">
            <div class="container text-center">
                <div class="row justify-content-center" data-aos="zoom-out">
                    <div class="col-lg-8">
                        <img src="uploads\images\logo.png" alt="" class="img-fluid mb-5">
                        <h2>Công ty phần mềm Đức Anh</h2>
                        <p>"Giải pháp thông minh, nâng tầm giá trị"</p>
                        <!-- Button đăng nhập -->
                        <button type="button" class="btn-get-started" data-toggle="modal" data-target="#loginModal">
                            Đăng nhập </button>
                    </div>
                </div>
            </div>

        </section>
        <!-- /Hero Section -->

        <!-- About Section -->
        <section id="about" class="about section">
            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>GIỚI THIỆU</h2>
                <p>Công ty Phần mềm Đức Anh chuyên cung cấp các giải pháp công nghệ thông tin tiên tiến, hỗ trợ khách
                    hàng trong hành trình chuyển đổi số và nâng cao hiệu quả kinh doanh.</p>
            </div><!-- End Section Title -->

            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row gy-4">
                    <div class="col-lg-6">
                        <img src="assets/img/about.jpg" class="img-fluid" alt="">
                    </div>
                    <div class="col-lg-6 content">
                        <h3>Đối tác công nghệ đáng tin cậy</h3>
                        <p class="fst-italic">
                            Với đội ngũ kỹ sư giàu kinh nghiệm và tận tâm, chúng tôi cam kết mang đến các sản phẩm phần
                            mềm chất lượng cao, đáp ứng nhu cầu đa dạng từ hệ thống quản lý doanh nghiệp, thương mại
                            điện tử, đến ứng dụng di động.
                        </p>
                        <ul>
                            <li><i class="bi bi-check2-all"></i> <span>Chuyển đổi số giúp tăng trưởng hiệu quả kinh
                                    doanh.</span></li>
                            <li><i class="bi bi-check2-all"></i> <span>Phát triển các giải pháp công nghệ phù hợp với
                                    nhu cầu khách hàng.</span></li>
                            <li><i class="bi bi-check2-all"></i> <span>Đồng hành cùng khách hàng trong mọi giai đoạn
                                    phát triển.</span></li>
                        </ul>
                        <p>
                            Đức Anh không chỉ là đối tác công nghệ mà còn là người đồng hành đáng tin cậy trong sự phát
                            triển và đổi mới của khách hàng.
                        </p>
                    </div>
                </div>

            </div>
        </section><!-- /About Section -->


        <!-- Clients Section -->
        <section id="clients" class="clients section">
            <div class="container">
                <div class="swiper init-swiper">
                    <script type="application/json" class="swiper-config">
                    {
                        "loop": true,
                        "speed": 600,
                        "autoplay": {
                            "delay": 5000
                        },
                        "slidesPerView": "auto",
                        "pagination": {
                            "el": ".swiper-pagination",
                            "type": "bullets",
                            "clickable": true
                        },
                        "breakpoints": {
                            "320": {
                                "slidesPerView": 2,
                                "spaceBetween": 40
                            },
                            "480": {
                                "slidesPerView": 3,
                                "spaceBetween": 60
                            },
                            "640": {
                                "slidesPerView": 4,
                                "spaceBetween": 80
                            },
                            "992": {
                                "slidesPerView": 6,
                                "spaceBetween": 120
                            }
                        }
                    }
                    </script>
                    <div class="swiper-wrapper align-items-center">
                        <div class="swiper-slide"><img src="uploads\images\partner1.jpg" class="img-fluid" alt=""></div>
                        <div class="swiper-slide"><img src="uploads\images\partner2.png" class="img-fluid" alt=""></div>
                        <div class="swiper-slide"><img src="assets/img/clients/client-3.png" class="img-fluid" alt="">
                        </div>
                        <div class="swiper-slide"><img src="assets/img/clients/client-4.png" class="img-fluid" alt="">
                        </div>
                        <div class="swiper-slide"><img src="assets/img/clients/client-5.png" class="img-fluid" alt="">
                        </div>
                        <div class="swiper-slide"><img src="assets/img/clients/client-6.png" class="img-fluid" alt="">
                        </div>
                        <div class="swiper-slide"><img src="assets/img/clients/client-7.png" class="img-fluid" alt="">
                        </div>
                        <div class="swiper-slide"><img src="assets/img/clients/client-8.png" class="img-fluid" alt="">
                        </div>
                    </div>
                </div>

            </div>

        </section>
        <!-- /Clients Section -->

        <!-- Services Section -->
        <section id="services" class="services section">
            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>DỊCH VỤ</h2>
                <p>Chúng tôi cung cấp các dịch vụ phần mềm chất lượng cao, đáp ứng nhu cầu đa dạng của khách hàng trong
                    từng lĩnh vực.</p>
            </div><!-- End Section Title -->

            <div class="container">

                <div class="row gy-4">

                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="service-item d-flex">
                            <div class="icon flex-shrink-0"><i class="bi bi-briefcase"></i></div>
                            <div>
                                <h4 class="title"><a href="index.php" class="stretched-link">Phát Triển Phần Mềm</a>
                                </h4>
                                <p class="description">Chúng tôi cung cấp các giải pháp phần mềm tùy chỉnh, phù hợp với
                                    từng yêu cầu cụ thể của khách hàng.</p>
                            </div>
                        </div>
                    </div><!-- End Service Item -->

                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                        <div class="service-item d-flex">
                            <div class="icon flex-shrink-0"><i class="bi bi-card-checklist"></i></div>
                            <div>
                                <h4 class="title"><a href="index.php" class="stretched-link">Tư Vấn Công Nghệ</a></h4>
                                <p class="description">Chúng tôi cung cấp dịch vụ tư vấn công nghệ, giúp khách hàng lựa
                                    chọn giải pháp tối ưu cho doanh nghiệp của mình.</p>
                            </div>
                        </div>
                    </div><!-- End Service Item -->

                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="300">
                        <div class="service-item d-flex">
                            <div class="icon flex-shrink-0"><i class="bi bi-bar-chart"></i></div>
                            <div>
                                <h4 class="title"><a href="index.php" class="stretched-link">Quản Lý Dự Án</a></h4>
                                <p class="description">Chúng tôi giúp bạn quản lý các dự án phần mềm từ giai đoạn lập kế
                                    hoạch đến khi hoàn thành, đảm bảo chất lượng và tiến độ.</p>
                            </div>
                        </div>
                    </div><!-- End Service Item -->

                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="400">
                        <div class="service-item d-flex">
                            <div class="icon flex-shrink-0"><i class="bi bi-binoculars"></i></div>
                            <div>
                                <h4 class="title"><a href="index.php" class="stretched-link">Kiểm Thử Phần Mềm</a></h4>
                                <p class="description">Chúng tôi cung cấp dịch vụ kiểm thử phần mềm, giúp đảm bảo chất
                                    lượng và hiệu suất của các sản phẩm phần mềm trước khi ra mắt.</p>
                            </div>
                        </div>
                    </div><!-- End Service Item -->

                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="500">
                        <div class="service-item d-flex">
                            <div class="icon flex-shrink-0"><i class="bi bi-brightness-high"></i></div>
                            <div>
                                <h4 class="title"><a href="index.php" class="stretched-link">Phát Triển Ứng Dụng Di
                                        Động</a></h4>
                                <p class="description">Chúng tôi phát triển ứng dụng di động cho cả nền tảng Android và
                                    iOS, giúp khách hàng tiếp cận người dùng nhanh chóng và hiệu quả.</p>
                            </div>
                        </div>
                    </div><!-- End Service Item -->

                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="600">
                        <div class="service-item d-flex">
                            <div class="icon flex-shrink-0"><i class="bi bi-calendar4-week"></i></div>
                            <div>
                                <h4 class="title"><a href="index.php" class="stretched-link">Hỗ Trợ Sau Bán Hàng</a>
                                </h4>
                                <p class="description">Chúng tôi cung cấp dịch vụ hỗ trợ và bảo trì phần mềm sau khi bàn
                                    giao, giúp khách hàng vận hành mượt mà các hệ thống phần mềm của mình.</p>
                            </div>
                        </div>
                    </div><!-- End Service Item -->

                </div>

            </div>

        </section>

        <!-- /Services Section -->

        <!-- Testimonials Section -->
        <section id="testimonials" class="testimonials section dark-background">

            <img src="assets/img/testimonials-bg.jpg" class="testimonials-bg" alt="">

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="swiper init-swiper">
                    <script type="application/json" class="swiper-config">
                    {
                        "loop": true,
                        "speed": 600,
                        "autoplay": {
                            "delay": 5000
                        },
                        "slidesPerView": "auto",
                        "pagination": {
                            "el": ".swiper-pagination",
                            "type": "bullets",
                            "clickable": true
                        }
                    }
                    </script>
                    <div class="swiper-wrapper">

                        <div class="swiper-slide">
                            <div class="testimonial-item">
                                <img src="assets/img/testimonials/testimonials-1.jpg" class="testimonial-img" alt="">
                                <h3>Lê Đức Anh</h3>
                                <h4>Ceo &amp; Founder</h4>
                                <div class="stars">
                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                        class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                        class="bi bi-star-fill"></i>
                                </div>
                                <p>
                                    <i class="bi bi-quote quote-icon-left"></i>
                                    <span>Tôi rất hài lòng</span>
                                    <i class="bi bi-quote quote-icon-right"></i>
                                </p>
                            </div>
                        </div><!-- End testimonial item -->

                        <div class="swiper-slide">
                            <div class="testimonial-item">
                                <img src="assets/img/testimonials/testimonials-2.jpg" class="testimonial-img" alt="">
                                <h3>Đức Anh Lê</h3>
                                <h4>Doanh nhân</h4>
                                <div class="stars">
                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                        class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                        class="bi bi-star-fill"></i>
                                </div>
                                <p>
                                    <i class="bi bi-quote quote-icon-left"></i>
                                    <span>Tôi cũng hài lòng.</span>
                                    <i class="bi bi-quote quote-icon-right"></i>
                                </p>
                            </div>
                        </div><!-- End testimonial item -->
                    </div>

                </div>

            </div>

        </section><!-- /Testimonials Section -->

        <!-- Team Section -->
        <section id="team" class="team section">
            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>CON NGƯỜI</h2>
                <p>Các thành viên xuất sắc của công ty chúng tôi trong tháng 11</p>
            </div><!-- End Section Title -->

            <div class="site-section slider-team-wrap">
                <div class="container">
                    <div class="slider-nav d-flex justify-content-end mb-3">
                        <a href="#" class="js-prev js-custom-prev"><i class="bi bi-arrow-left-short"></i></a>
                        <a href="#" class="js-next js-custom-next"><i class="bi bi-arrow-right-short"></i></a>
                    </div>

                    <div class="swiper init-swiper" data-aos="fade-up" data-aos-delay="100">
                        <script type="application/json" class="swiper-config">
                        {
                            "loop": true,
                            "speed": 600,
                            "autoplay": {
                                "delay": 5000
                            },
                            "slidesPerView": "1",
                            "pagination": {
                                "el": ".swiper-pagination",
                                "type": "bullets",
                                "clickable": true
                            },
                            "navigation": {
                                "nextEl": ".js-custom-next",
                                "prevEl": ".js-custom-prev"
                            },
                            "breakpoints": {
                                "640": {
                                    "slidesPerView": 2,
                                    "spaceBetween": 30
                                },
                                "768": {
                                    "slidesPerView": 3,
                                    "spaceBetween": 30
                                },
                                "1200": {
                                    "slidesPerView": 3,
                                    "spaceBetween": 30
                                }
                            }
                        }
                        </script>

                        <div class="swiper-wrapper">
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <div class="swiper-slide">
                                <div class="team">
                                    <div class="pic">
                                        <img src="<?php echo 'uploads/images/' . basename($row['image_path']); ?>"
                                            alt="Nhân viên" class="img-fluid">
                                    </div>
                                    <h3>
                                        <a href="#"><span><?php echo $row['first_name']; ?></span>
                                            <?php echo $row['last_name']; ?></a>
                                    </h3>
                                    <span class="d-block position">Nhân viên xuất sắc tháng 11</span>
                                    <p>Thành viên tích cực và xuất sắc trong công việc, đóng góp nhiều thành tựu đáng kể
                                        cho dự án.</p>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>

                    </div>
                </div>
            </div>
        </section><!-- /Team Section -->


        <!-- Contact Section -->
        <section id="contact" class="contact section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>LIÊN HỆ</h2>
                <p>Hãy liên hệ với chúng tôi để được tư vấn một cách đầy đủ nhất</p>
            </div><!-- End Section Title -->

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="mb-4" data-aos="fade-up" data-aos-delay="200">
                    <iframe style="border:0; width: 100%; height: 270px;"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3725.2924013039146!2d105.78484157621108!3d20.98091298065658!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135accdd8a1ad71%3A0xa2f9b16036648187!2zSOG7jWMgdmnhu4duIEPDtG5nIG5naOG7hyBCxrB1IGNow61uaCB2aeG7hW4gdGjDtG5n!5e0!3m2!1svi!2s!4v1730045489594!5m2!1svi!2s"
                        frameborder="0" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div><!-- End Google Maps -->

                <div class="row gy-4">

                    <div class="col-lg-4">
                        <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="300">
                            <i class="bi bi-geo-alt flex-shrink-0"></i>
                            <div>
                                <h3>Địa chỉ</h3>
                                <p>Km10 - Nguyễn Trãi - Hà Đông - Hà Nội</p>
                            </div>
                        </div><!-- End Info Item -->

                        <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="400">
                            <i class="bi bi-telephone flex-shrink-0"></i>
                            <div>
                                <h3>Số điện thoại</h3>
                                <p>0941312568</p>
                            </div>
                        </div><!-- End Info Item -->

                        <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="500">
                            <i class="bi bi-envelope flex-shrink-0"></i>
                            <div>
                                <h3>EmaiL</h3>
                                <p>leducanh1503.works@gmail.com</p>
                            </div>
                        </div><!-- End Info Item -->

                    </div>

                    <div class="col-lg-8">
                        <form action="forms/contact.php" method="post" class="php-email-form" data-aos="fade-up"
                            data-aos-delay="200">
                            <div class="row gy-4">

                                <div class="col-md-6">
                                    <input type="text" name="name" class="form-control" placeholder="Nhập tên của bạn"
                                        required="">
                                </div>

                                <div class="col-md-6 ">
                                    <input type="email" class="form-control" name="email" placeholder="Nhập Email"
                                        required="">
                                </div>

                                <div class="col-md-12">
                                    <input type="text" class="form-control" name="subject" placeholder="Chủ đề"
                                        required="">
                                </div>

                                <div class="col-md-12">
                                    <textarea class="form-control" name="message" rows="6" placeholder="Nội dung"
                                        required=""></textarea>
                                </div>

                                <div class="col-md-12 text-center">
                                    <div class="loading">Loading</div>
                                    <div class="error-message"></div>
                                    <div class="sent-message">Your message has been sent. Thank you!</div>

                                    <button type="submit">Gửi</button>
                                </div>

                            </div>
                        </form>
                    </div><!-- End Contact Form -->

                </div>

            </div>

        </section><!-- /Contact Section -->

    </main>

    <footer id="footer" class="footer dark-background">

        <div class="container footer-top">
            <div class="row gy-4">
                <div class="col-lg-4 col-md-6 footer-about">
                    <a href="index.php" class="d-flex align-items-center">
                        <span class="sitename">Công ty phần mềm Đức Anh</span>
                    </a>
                    <div class="footer-contact pt-3">
                        <p>Km10 - Nguyễn Trãi - Hà Đông - Hà Nội</p>
                        <p class="mt-3"><strong>Số điện thoại:</strong> <span>0941312568</span></p>
                        <p><strong>Email:</strong> <span>leducanh1503.works@gmail.com</span></p>
                    </div>
                </div>

                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Thông tin</h4>
                    <ul>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Trang chủ</a></li>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Về chúng tôi</a></li>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Dịch vụ</a></li>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Điều khoản dịch vụ</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Dịch vụ của chúng tôi</h4>
                    <ul>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Thiết kế website</a></li>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Phát triển website</a></li>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Quản lý sản phẩm</a></li>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Marketing</a></li>
                    </ul>
                </div>

                <div class="col-lg-4 col-md-12">
                    <h4>Theo dõi chúng tôi</h4>
                    <p>Hãy theo dõi các fanpage của chúng tôi để cập nhật những thông tin nhanh nhất!</p>
                    <div class="social-links d-flex">
                        <a href="https://www.facebook.com/lee.diay.5/"><i class="bi bi-facebook"></i></a>
                        <a href="https://www.facebook.com/lee.diay.5/"><i class="bi bi-instagram"></i></a>
                        <a href="https://www.facebook.com/lee.diay.5/"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>

            </div>
        </div>
        <!-- Login Modal -->
        <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="loginModalLabel">
                            Đăng Nhập
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="email" id="email" class="form-control" placeholder="Địa Chỉ Email Của Bạn"
                                required />
                        </div>
                        <div class="form-group">
                            <input type="password" id="password" class="form-control" placeholder="Mật Khẩu" required />
                        </div>
                        <div class="text-right">
                            <a href="forgot_password.php">Quên Mật Khẩu?</a>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Đóng
                        </button>
                        <button type="button" id="login-form" class="btn btn-primary">
                            Xác nhận
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="container copyright text-center mt-4">
            <p>© <span>Bản quyền thuộc về</span> <strong class="px-1 sitename">Công ty phần mềm Đức Anh.</strong>
                <span>Mọi quyền đều được bảo lưu.</span></p>
        </div>

    </footer>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
    <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>

    <!-- Main JS File -->
    <script src="assets/js/main.js"></script>

</body>

</html>

<script>
$('#login-form').click(function(event) {
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
        success: function(response) {
            if (response.status === 'success') {
                let titleMessage = response.message;
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
        error: function() {
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