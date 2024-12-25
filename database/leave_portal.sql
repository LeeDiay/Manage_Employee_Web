-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 22, 2024 lúc 10:17 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `leave_portal`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `UserName` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `updationDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Đang đổ dữ liệu cho bảng `admin`
--

INSERT INTO `admin` (`id`, `UserName`, `Password`, `updationDate`) VALUES
(1, 'admin', '63c7585e4ab54f3544fd4ef36c8a0f89', '2024-11-02 02:05:10');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `employee_leave_types`
--

CREATE TABLE `employee_leave_types` (
  `id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `leave_type_id` int(11) NOT NULL,
  `available_days` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `employee_leave_types`
--

INSERT INTO `employee_leave_types` (`id`, `emp_id`, `leave_type_id`, `available_days`) VALUES
(1, 4, 3, 1),
(2, 4, 2, 0),
(3, 4, 4, 12),
(4, 1, 3, 3),
(6, 1, 1, 24),
(7, 1, 2, 0),
(8, 5, 3, 4);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tblattendance`
--

CREATE TABLE `tblattendance` (
  `attendance_id` int(11) NOT NULL,
  `staff_id` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `time_in` time NOT NULL,
  `time_out` time DEFAULT NULL,
  `total_hours` time GENERATED ALWAYS AS (timediff(`time_out`,`time_in`)) STORED,
  `status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tblattendance`
--

INSERT INTO `tblattendance` (`attendance_id`, `staff_id`, `date`, `time_in`, `time_out`, `status`) VALUES
(5, 'LLM 003', '2024-10-10', '17:13:43', '17:14:11', ''),
(6, 'LLM 001', '2024-10-12', '02:09:54', NULL, ''),
(7, 'LLM 001', '2024-10-13', '10:05:53', '13:56:01', ''),
(8, 'LLM 003', '2024-10-13', '13:55:44', '17:39:34', ''),
(9, 'LLM 001', '2024-10-27', '13:36:26', '13:37:43', ''),
(21, 'LLM 003', '2024-11-08', '11:11:42', NULL, ''),
(22, 'LLM 001', '2024-11-08', '11:16:10', '11:16:16', ''),
(23, 'LLM 003', '2024-11-09', '08:21:03', '21:19:05', ''),
(24, 'LLM 001', '2024-11-09', '23:24:42', '23:25:06', ''),
(25, 'LLM 001', '2024-11-13', '11:07:33', '11:07:43', '');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbldepartments`
--

CREATE TABLE `tbldepartments` (
  `id` int(11) NOT NULL,
  `department_name` varchar(255) NOT NULL,
  `department_desc` text DEFAULT NULL,
  `creation_date` datetime NOT NULL,
  `last_modified_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbldepartments`
--

INSERT INTO `tbldepartments` (`id`, `department_name`, `department_desc`, `creation_date`, `last_modified_date`) VALUES
(1, 'Phòng IT', 'Phát triển hệ thống phần mềm', '2024-08-05 21:59:01', '2024-12-07 07:31:01'),
(2, 'Phòng Nhân sự', 'Quản lí nhân sự của công ty', '2024-08-05 21:59:56', '2024-11-06 23:18:59'),
(4, 'Phòng kế toán', 'Chức năng tài chính của công ty', '2024-10-28 23:18:25', '2024-11-07 22:35:37');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tblemployees`
--

CREATE TABLE `tblemployees` (
  `emp_id` int(11) NOT NULL,
  `department` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `email_id` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `staff_id` varchar(20) NOT NULL,
  `is_supervisor` int(11) NOT NULL DEFAULT 0,
  `password_reset` tinyint(1) NOT NULL DEFAULT 0,
  `lock_unlock` tinyint(1) NOT NULL DEFAULT 0,
  `date_created` datetime DEFAULT current_timestamp(),
  `supervisor_id` int(11) DEFAULT NULL,
  `can_be_assigned` enum('YES','NO') NOT NULL DEFAULT 'YES',
  `token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tblemployees`
--

INSERT INTO `tblemployees` (`emp_id`, `department`, `first_name`, `last_name`, `middle_name`, `phone_number`, `designation`, `email_id`, `password`, `gender`, `image_path`, `role`, `staff_id`, `is_supervisor`, `password_reset`, `lock_unlock`, `date_created`, `supervisor_id`, `can_be_assigned`, `token`) VALUES
(1, 2, 'Nguyễn', 'Oanh', 'Phương', '000000000', 'Managing Director', 'admin@gmail.com', '19d58a3d7aafeec30d72a02f2b0b2ae7', 'Female', '../uploads/images/LLM 005_user-img.jpg', 'Admin', 'LLM 001', 1, 1, 0, '2024-08-05 22:02:37', NULL, 'NO', ''),
(2, 1, 'Nguyễn', 'Hải Anh', '', '0000000001', 'Mobile App Developer', 'haianh@gmail.com', '5e543256c480ac577d30f76f9120eb74', 'Male', '../uploads/images/LLM 002_f-2.jpg', 'Staff', 'NV002', 0, 1, 0, '2024-08-11 09:21:32', 3, 'YES', ''),
(3, 1, 'Phạm', 'Trung Thành', '', '0000000011', 'Senior Mobile App Developer', 'haohanbeo334@gmail.com', '5e543256c480ac577d30f76f9120eb74', 'Male', '../uploads/images/LLM 003_f-3.jpg', 'Staff', 'LLM 003', 1, 1, 0, '2024-08-11 19:56:20', NULL, 'YES', ''),
(4, 1, 'Lê', 'Đức Anh', '', '0941312568', 'SEC Manager', 'leducanh1503.works@gmail.com', '907d0fb275435ab0b835daaab2e4bc39', 'Male', '../uploads/images/test1.png', 'Admin', 'NV004', 1, 1, 0, '2024-10-10 22:30:51', 3, 'YES', ''),
(5, 4, 'Lê ', 'Giang', 'Hữu', '0941312568', 'Staff', 'haohanbeo333@gmail.com', '19d58a3d7aafeec30d72a02f2b0b2ae7', 'Male', '../uploads/images/LLM 001_f-1.jpg', 'Staff', 'NV005', 0, 0, 0, '2024-11-07 22:21:23', NULL, 'YES', '');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tblleave`
--

CREATE TABLE `tblleave` (
  `id` int(11) NOT NULL,
  `leave_type_id` int(11) NOT NULL,
  `requested_days` int(11) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `leave_status` int(11) NOT NULL DEFAULT 0,
  `empid` int(11) NOT NULL,
  `remarks` text DEFAULT NULL,
  `sick_file` varchar(255) DEFAULT NULL,
  `reviewed_by` int(11) DEFAULT NULL,
  `reviewed_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tblleave`
--

INSERT INTO `tblleave` (`id`, `leave_type_id`, `requested_days`, `from_date`, `to_date`, `created_date`, `leave_status`, `empid`, `remarks`, `sick_file`, `reviewed_by`, `reviewed_date`) VALUES
(1, 3, 3, '2024-10-16', '2024-10-18', '2024-10-13 20:38:03', 1, 4, '221', NULL, NULL, NULL),
(2, 3, 1, '2024-11-07', '2024-11-07', '2024-10-27 20:25:47', 3, 1, '', NULL, NULL, NULL),
(3, 1, 4, '2024-11-15', '2024-11-20', '2024-11-06 22:31:14', 0, 1, 'ấ', NULL, NULL, NULL),
(4, 1, 5, '2024-12-06', '2024-12-12', '2024-12-06 23:35:15', 0, 1, 'hehe', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tblleavetype`
--

CREATE TABLE `tblleavetype` (
  `id` int(11) NOT NULL,
  `leave_type` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `creation_date` datetime NOT NULL,
  `assign_days` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tblleavetype`
--

INSERT INTO `tblleavetype` (`id`, `leave_type`, `description`, `creation_date`, `assign_days`, `status`) VALUES
(1, 'Nghỉ phép thường niên', 'Thời gian nghỉ làm được trả lương', '2024-08-05 22:25:08', 30, 1),
(2, 'Nghỉ phép bệnh tật', 'Sức khỏe không ổn định', '2024-10-10 17:49:44', 2, 1),
(3, 'Phép nghỉ lễ', 'Nghỉ lễ trong năm', '2024-10-10 15:53:02', 7, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tblsalary`
--

CREATE TABLE `tblsalary` (
  `salary_id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `base_salary` decimal(10,2) NOT NULL,
  `total_hours` decimal(10,2) DEFAULT 0.00,
  `leave_days` int(11) DEFAULT 0,
  `final_salary` decimal(10,2) DEFAULT 0.00,
  `date_generated` date NOT NULL DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tblsalary`
--

INSERT INTO `tblsalary` (`salary_id`, `emp_id`, `base_salary`, `total_hours`, `leave_days`, `final_salary`, `date_generated`) VALUES
(1, 1, 15000000.00, 21.00, 1, 14500000.00, '2024-11-07');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbltask`
--

CREATE TABLE `tbltask` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `assigned_to` int(11) NOT NULL,
  `assigned_by` int(11) NOT NULL,
  `status` enum('Pending','In Progress','Completed') DEFAULT 'Pending',
  `priority` enum('Low','Medium','High') DEFAULT 'Medium',
  `start_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbltask`
--

INSERT INTO `tbltask` (`id`, `title`, `description`, `assigned_to`, `assigned_by`, `status`, `priority`, `start_date`, `due_date`, `created_at`, `updated_at`) VALUES
(2, 'Test', 'Thông báo demo', 2, 1, 'Pending', 'High', '2024-08-12', '2024-08-16', '2024-08-11 09:42:01', '2024-12-06 16:33:02'),
(3, 'Đăng nhập bị lỗi', 'Code lại chức năng đăng nhập', 2, 1, 'Completed', 'Medium', '2024-08-13', '2024-08-19', '2024-08-11 09:42:01', '2024-12-06 16:32:26'),
(6, 'App ngưng hoạt động', 'Sửa lại phần chat', 3, 1, 'Completed', 'Medium', '2024-08-13', '2024-08-19', '2024-08-11 19:58:20', '2024-12-06 16:33:22'),
(8, 'báo cáo', 'Thực hiện deadline được giao đúng hạn', 2, 3, 'Completed', 'High', '1977-01-04', '2024-10-10', '2024-10-10 16:22:44', '2024-12-06 16:33:37');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_message`
--

CREATE TABLE `tbl_message` (
  `msg_id` int(11) NOT NULL,
  `incoming_msg_id` text NOT NULL,
  `outgoing_msg_id` text NOT NULL,
  `text_message` text NOT NULL,
  `curr_date` text NOT NULL,
  `curr_time` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_message`
--

INSERT INTO `tbl_message` (`msg_id`, `incoming_msg_id`, `outgoing_msg_id`, `text_message`, `curr_date`, `curr_time`) VALUES
(1, '10', '9', 'Hello', 'October 10, 2022 ', '6:04 am'),
(2, '9', '10', 'Hi', 'October 13, 2023 ', '8:37 pm');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `employee_leave_types`
--
ALTER TABLE `employee_leave_types`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `tblattendance`
--
ALTER TABLE `tblattendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Chỉ mục cho bảng `tbldepartments`
--
ALTER TABLE `tbldepartments`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `tblemployees`
--
ALTER TABLE `tblemployees`
  ADD PRIMARY KEY (`emp_id`),
  ADD UNIQUE KEY `staff_id` (`staff_id`);

--
-- Chỉ mục cho bảng `tblleave`
--
ALTER TABLE `tblleave`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `tblleavetype`
--
ALTER TABLE `tblleavetype`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `tblsalary`
--
ALTER TABLE `tblsalary`
  ADD PRIMARY KEY (`salary_id`),
  ADD KEY `emp_id` (`emp_id`);

--
-- Chỉ mục cho bảng `tbltask`
--
ALTER TABLE `tbltask`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assigned_to` (`assigned_to`),
  ADD KEY `assigned_by` (`assigned_by`);

--
-- Chỉ mục cho bảng `tbl_message`
--
ALTER TABLE `tbl_message`
  ADD PRIMARY KEY (`msg_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `employee_leave_types`
--
ALTER TABLE `employee_leave_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `tblattendance`
--
ALTER TABLE `tblattendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT cho bảng `tbldepartments`
--
ALTER TABLE `tbldepartments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `tblemployees`
--
ALTER TABLE `tblemployees`
  MODIFY `emp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `tblleave`
--
ALTER TABLE `tblleave`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `tblleavetype`
--
ALTER TABLE `tblleavetype`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `tblsalary`
--
ALTER TABLE `tblsalary`
  MODIFY `salary_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `tbltask`
--
ALTER TABLE `tbltask`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `tbl_message`
--
ALTER TABLE `tbl_message`
  MODIFY `msg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `tblattendance`
--
ALTER TABLE `tblattendance`
  ADD CONSTRAINT `tblattendance_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `tblemployees` (`staff_id`);

--
-- Các ràng buộc cho bảng `tblsalary`
--
ALTER TABLE `tblsalary`
  ADD CONSTRAINT `tblsalary_ibfk_1` FOREIGN KEY (`emp_id`) REFERENCES `tblemployees` (`emp_id`);

--
-- Các ràng buộc cho bảng `tbltask`
--
ALTER TABLE `tbltask`
  ADD CONSTRAINT `tbltask_ibfk_1` FOREIGN KEY (`assigned_to`) REFERENCES `tblemployees` (`emp_id`),
  ADD CONSTRAINT `tbltask_ibfk_2` FOREIGN KEY (`assigned_by`) REFERENCES `tblemployees` (`emp_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
