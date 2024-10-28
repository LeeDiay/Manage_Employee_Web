<nav class="pcoded-navbar">
    <div class="pcoded-inner-navbar main-menu">
        <?php if ($session_role == 'Manager'  || $session_role == 'Admin') : ?>
            <ul class="pcoded-item pcoded-left-item">
                <li class="<?php echo ($page_name == 'dashboard') ? 'active' : ''; ?>">
                    <a href="index.php">
                        <span class="pcoded-micon"><i class="feather icon-home"></i></span>
                        <span class="pcoded-mtext">Trang chủ</span>
                    </a>
                </li>
            </ul>
            <ul class="pcoded-item pcoded-left-item">
                <li class="<?php echo ($page_name == 'department') ? 'active' : ''; ?>">
                    <a href="department.php">
                        <span class="pcoded-micon"><i class="feather icon-monitor"></i></span>
                        <span class="pcoded-mtext">Phòng ban</span>
                    </a>
                </li>
                <li class="pcoded-hasmenu <?php echo ($page_name == 'staff' || $page_name == 'new_staff' || $page_name == 'staff_list') ? 'active pcoded-trigger' : ''; ?>">
                    <a href="javascript:void(0)">
                        <span class="pcoded-micon"><i class="feather icon-users"></i></span>
                        <span class="pcoded-mtext">Nhân viên</span>
                    </a>
                    <ul class="pcoded-submenu">
                        <li class="<?php echo ($page_name == 'new_staff') ? 'active' : ''; ?>">
                            <a href="new_staff.php">
                                <span class="pcoded-mtext">Thêm nhân viên mới</span>
                            </a>
                        </li>
                        <li class="<?php echo ($page_name == 'staff_list') ? 'active' : ''; ?>">
                            <a href="staff_list.php">
                                <span class="pcoded-mtext">Quản lí nhân viên</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="<?php echo ($page_name == 'leave_type') ? 'active' : ''; ?>">
                    <a href="leave_type.php">
                        <span class="pcoded-micon"><i class="feather icon-shuffle"></i></span>
                        <span class="pcoded-mtext">Loại nghỉ phép</span>
                    </a>
                </li>
                <li class="pcoded-hasmenu <?php echo ($page_name == 'leave' || $page_name == 'apply_leave' || $page_name == 'leave_request' || $page_name == 'my_leave') ? 'active pcoded-trigger' : ''; ?>">
                    <a href="javascript:void(0)">
                        <span class="pcoded-micon"><i class="feather icon-shuffle"></i></span>
                        <span class="pcoded-mtext">Nghỉ phép</span>
                    </a>
                    <ul class="pcoded-submenu">
                        <li class="<?php echo ($page_name == 'apply_leave') ? 'active' : ''; ?>">
                            <a href="apply_leave.php">
                                <span class="pcoded-mtext">Nộp đơn nghỉ phép</span>
                            </a>
                        </li>
                         <li class="<?php echo ($page_name == 'my_leave') ? 'active' : ''; ?>">
                            <a href="my_leave.php">
                                <span class="pcoded-mtext">Đơn của tôi</span>
                            </a>
                        </li>
                        <?php if ($session_role == 'Manager' || $session_role == 'Admin') : ?>
                            <li class="<?php echo ($page_name == 'leave_request') ? 'active' : ''; ?>">
                                <a href="leave_request.php?leave_status=0">
                                    <span class="pcoded-mtext">Tất cả các đơn</span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
                <li class="pcoded-hasmenu <?php echo ($page_name == 'task' || $page_name == 'new_task' || $page_name == 'task_list') ? 'active pcoded-trigger' : ''; ?>">
                    <a href="javascript:void(0)">
                        <span class="pcoded-micon"><i class="feather icon-users"></i></span>
                        <span class="pcoded-mtext">Quản lí nhiệm vụ</span>
                    </a>
                    <ul class="pcoded-submenu">
                        <li class="<?php echo ($page_name == 'new_task') ? 'active' : ''; ?>">
                            <a href="new_task.php">
                                <span class="pcoded-mtext">Thêm nhiệm vụ mới</span>
                            </a>
                        </li>
                        <li class="<?php echo ($page_name == 'task_list') ? 'active' : ''; ?>">
                            <a href="task_list.php">
                                <span class="pcoded-mtext">Danh sách nhiệm vụ</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="pcoded-hasmenu <?php echo ($page_name == 'attendance' || $page_name == 'my_attendance') ? 'active pcoded-trigger' : ''; ?>">
                    <a href="javascript:void(0)">
                        <span class="pcoded-micon"><i class="feather icon-clock"></i></span>
                        <span class="pcoded-mtext">Chấm công</span>
                    </a>
                    <ul class="pcoded-submenu">
                        <li class="<?php echo ($page_name == 'attendance') ? 'active' : ''; ?>">
                            <a href="attendance.php">
                                <span class="pcoded-mtext">Quản lí công</span>
                            </a>
                        </li>
                        <li class="<?php echo ($page_name == 'my_attendance') ? 'active' : ''; ?>">
                            <a href="my_attendance.php">
                                <span class="pcoded-mtext">Công của tôi</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        <?php endif; ?>   
        <?php if ($session_role == 'Staff') : ?>
            <ul class="pcoded-item pcoded-left-item">
                <li class="<?php echo ($page_name == 'dashboard') ? 'active' : ''; ?>">
                    <a href="index.php">
                        <span class="pcoded-micon"><i class="feather icon-home"></i></span>
                        <span class="pcoded-mtext">Trang chủ</span>
                    </a>
                </li>
            </ul>
            <ul class="pcoded-item pcoded-left-item">
                <li class="<?php echo ($page_name == 'department') ? 'active' : ''; ?>">
                    <a href="department.php">
                        <span class="pcoded-micon"><i class="feather icon-monitor"></i></span>
                        <span class="pcoded-mtext">Phòng ban</span>
                    </a>
                </li>
                <li class="pcoded-hasmenu <?php echo ($page_name == 'staff' || $page_name == 'staff_list') ? 'active pcoded-trigger' : ''; ?>">
                    <a href="javascript:void(0)">
                        <span class="pcoded-micon"><i class="feather icon-users"></i></span>
                        <span class="pcoded-mtext">Nhân viên</span>
                    </a>
                    <ul class="pcoded-submenu">
                        <li class="<?php echo ($page_name == 'staff_list') ? 'active' : ''; ?>">
                            <a href="staff_list.php">
                                <span class="pcoded-mtext">Danh sách nhân viên</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="pcoded-hasmenu <?php echo ($page_name == 'leave' || $page_name == 'apply_leave'|| $page_name == 'my_leave'|| $page_name == 'supervisee_leave_request') ? 'active pcoded-trigger' : ''; ?>">
                    <a href="javascript:void(0)">
                        <span class="pcoded-micon"><i class="feather icon-shuffle"></i></span>
                        <span class="pcoded-mtext">Nghỉ phép</span>
                    </a>
                    <ul class="pcoded-submenu">
                        <li class="<?php echo ($page_name == 'apply_leave') ? 'active' : ''; ?>">
                            <a href="apply_leave.php">
                                <span class="pcoded-mtext">Nộp đơn nghỉ phép</span>
                            </a>
                        </li>
                         <li class="<?php echo ($page_name == 'my_leave') ? 'active' : ''; ?>">
                            <a href="my_leave.php">
                                <span class="pcoded-mtext">Đơn của tôi</span>
                            </a>
                        </li>
                        <?php if ($session_role == 'Staff' && $session_supervisor == '1') : ?>
                            <li class="<?php echo ($page_name == 'supervisee_leave_request') ? 'active' : ''; ?>">
                                <a href="supervisee_leave_request.php?leave_status=0">
                                    <span class="pcoded-mtext">Quản lí đơn</span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
                <li class="pcoded-hasmenu <?php echo ($page_name == 'task' || $page_name == 'new_task' || $page_name == 'my_task_list') ? 'active pcoded-trigger' : ''; ?>">
                    <a href="javascript:void(0)">
                        <span class="pcoded-micon"><i class="feather icon-users"></i></span>
                        <span class="pcoded-mtext">Quản lí nhiệm vụ</span>
                    </a>
                    <ul class="pcoded-submenu">
                        <?php if ($session_role == 'Staff' && $session_supervisor == '1') : ?>
                         <li class="<?php echo ($page_name == 'new_task') ? 'active' : ''; ?>">
                            <a href="new_task.php">
                                <span class="pcoded-mtext">Thêm nhiệm vụ mới</span>
                            </a>
                        </li>
                        <?php endif; ?>
                        <li class="<?php echo ($page_name == 'my_task_list') ? 'active' : ''; ?>">
                            <a href="my_task_list.php">
                                <span class="pcoded-mtext">Nhiệm vụ của tôi</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="<?php echo ($page_name == 'my_attendance') ? 'active' : ''; ?>">
                    <a href="my_attendance.php">
                        <span class="pcoded-micon"><i class="feather icon-clock"></i></span>
                        <span class="pcoded-mtext">Công của tôi</span>
                    </a>
                </li>
            </ul>
        <?php endif; ?>    
    </div>
</nav>
