<ul class="nav-right">
    <li class="header-notification">
        <div class="dropdown-primary dropdown">
        </div>
    </li>
    <li class="user-profile header-notification">
        <div class="dropdown-primary dropdown">
            <div class="dropdown-toggle" data-toggle="dropdown">
                <?php
                    $image_src = !empty($session_image) ? $session_image : '..\files\assets\images\avatar-4.jpg';
                    echo '<img src="' . $image_src . '" class="img-radius" alt="User-Profile-Image">';
                ?>
                <span><?php echo $session_sfirstname . ' ' . $session_smiddlename . ' ' . $session_slastname; ?></span>
                <i class="feather icon-chevron-down"></i>
            </div>
            <ul class="show-notification profile-notification dropdown-menu"
                data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                <li>
                    <a href="staff_detailed.php?id=<?= $session_id ?>&view=2">
                        <i class="feather icon-user"></i> Trang cá nhân
                    </a>
                </li>
                <!-- <li>
                    <a href="../lock_screen.php">
                        <i class="feather icon-lock"></i> Lock Screen
                    </a>
                </li> -->
                <li>
                    <a href="../logout.php">
                        <i class="feather icon-log-out"></i> Đăng xuất
                    </a>
                </li>
            </ul>

        </div>
    </li>
</ul>