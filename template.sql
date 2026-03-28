-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 28, 2026 at 06:48 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `template`
--

-- --------------------------------------------------------

--
-- Table structure for table `contents`
--

CREATE TABLE `contents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order` int(11) NOT NULL DEFAULT 1,
  `topic_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `periods` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contents`
--

INSERT INTO `contents` (`id`, `order`, `topic_id`, `name`, `periods`, `created_at`, `updated_at`) VALUES
(2, 3, 1, 'Chuyển động biến đổi', 8, '2026-03-23 04:59:50', '2026-03-26 11:18:12'),
(3, 1, 2, 'Sơ lược về sự phát triển của vật lí học', 4, '2026-03-23 04:59:50', '2026-03-25 04:52:13'),
(4, 2, 2, 'Giới thiệu các lĩnh vực nghiên cứu trong vật lí học', 3, '2026-03-23 04:59:50', '2026-03-25 04:52:20'),
(7, 1, 5, 'Giới thiệu mục đích học tập môn Vật lý', 4, '2026-03-23 13:27:22', '2026-03-23 13:27:22'),
(9, 1, 6, 'Dao động điều hoà', 12, '2026-03-23 20:26:16', '2026-03-25 05:25:02'),
(10, 1, 6, 'Dao động tắt dần - cộng hưởng', 2, '2026-03-23 20:27:09', '2026-03-25 05:24:53'),
(11, 2, 1, 'Mô tả chuyển động', 8, '2026-03-24 06:38:28', '2026-03-26 11:18:03'),
(12, 4, 8, 'Ba định luật Newton về chuyển động', 6, '2026-03-24 06:42:06', '2026-03-26 11:19:25'),
(13, 5, 8, 'Một số lực trong thực tiễn', 4, '2026-03-24 06:43:00', '2026-03-26 11:18:51'),
(14, 6, 8, 'Cân bằng lực - Moment lực', 5, '2026-03-24 06:45:31', '2026-03-26 11:19:36'),
(15, 7, 8, 'Khối lượng riêng - Áp suất chất lỏng', 3, '2026-03-24 06:46:55', '2026-03-26 11:19:53'),
(16, 8, 9, 'Công và năng lượng', 3, '2026-03-24 06:47:40', '2026-03-26 11:20:12'),
(17, 9, 9, 'Động năng và thế năng', 5, '2026-03-24 06:48:20', '2026-03-26 11:20:21'),
(18, 10, 9, 'Công suất và hiệu suất', 2, '2026-03-24 06:48:59', '2026-03-26 11:20:27'),
(19, 12, 10, 'Định nghĩa động lượng', 2, '2026-03-24 06:49:47', '2026-03-26 11:20:43'),
(20, 13, 10, 'Bảo toàn động lượng', 2, '2026-03-24 06:50:31', '2026-03-26 11:20:48'),
(21, 14, 10, 'Động lượng và va chạm', 2, '2026-03-24 06:51:04', '2026-03-26 11:20:54'),
(22, 15, 11, 'Động học của chuyển động tròn đều', 2, '2026-03-24 06:52:02', '2026-03-26 11:21:09'),
(23, 16, 11, 'Gia tốc hướng tâm và lực hướng tâm', 2, '2026-03-24 06:52:51', '2026-03-26 11:21:14'),
(24, 16, 12, 'Biến dạng kéo và biến dạng nén - Đặc tính của lò xo.', 2, '2026-03-24 06:54:27', '2026-03-26 11:21:27'),
(25, 17, 12, 'Định luật Hook', 2, '2026-03-24 06:56:41', '2026-03-26 11:21:32'),
(30, 1, 63, 'Các khái niệm cơ bản về nhiệt động lực học', 4, '2026-03-25 04:17:42', '2026-03-27 02:38:50'),
(32, 3, 2, 'Giới thiệu các ứng dụng của vật lí trong một số ngành nghề', 3, '2026-03-25 04:51:58', '2026-03-25 04:52:26'),
(33, 1, 21, 'Xác định phương hướng', 3, '2026-03-25 04:53:13', '2026-03-25 04:54:23'),
(34, 2, 21, 'Đặc điểm chuyển động nhìn thấy của một số thiên thể trên nền trời sao', 3, '2026-03-25 04:53:36', '2026-03-25 04:54:34'),
(35, 3, 21, 'Một số hiện tượng thiên văn', 4, '2026-03-25 04:53:53', '2026-03-26 11:10:28'),
(36, 1, 22, 'Sự cần thiết phải bảo vệ môi trường', 5, '2026-03-25 04:55:57', '2026-03-25 04:56:50'),
(37, 2, 22, 'Vật lí với giáo dục bảo vệ môi trường', 10, '2026-03-25 04:56:26', '2026-03-25 04:56:57'),
(38, 1, 7, 'Mô tả sóng', 3, '2026-03-25 05:32:35', '2026-03-25 06:28:07'),
(39, 2, 7, 'Sóng dọc và sóng ngang', 2, '2026-03-25 05:32:50', '2026-03-25 05:37:35'),
(40, 3, 7, 'Sóng điện từ', 2, '2026-03-25 05:33:01', '2026-03-25 05:36:28'),
(41, 4, 7, 'Giao thoa sóng kết hợp', 4, '2026-03-25 05:33:16', '2026-03-25 05:37:49'),
(42, 5, 7, 'Sóng dừng', 3, '2026-03-25 05:33:30', '2026-03-25 05:37:55'),
(43, 6, 7, 'Đo tốc độ truyền âm', 2, '2026-03-25 05:33:46', '2026-03-25 05:37:18'),
(44, 1, 29, 'Lực điện tương tác giữa các điện tích', 2, '2026-03-25 07:45:30', '2026-03-25 07:48:17'),
(45, 2, 29, 'Khái niệm điện trường', 4, '2026-03-25 07:45:49', '2026-03-25 07:48:27'),
(46, 3, 29, 'Điện trường đều', 3, '2026-03-25 07:46:26', '2026-03-25 07:49:20'),
(47, 4, 29, 'Điện thế và thế năng điện', 5, '2026-03-25 07:47:07', '2026-03-25 07:49:27'),
(48, 5, 29, 'Tụ điện và điện dung', 4, '2026-03-25 07:47:46', '2026-03-25 07:48:57'),
(49, 1, 30, 'Cường độ dòng điện', 4, '2026-03-25 14:16:02', '2026-03-25 14:20:25'),
(50, 2, 30, 'Mạch điện và điện trở', 6, '2026-03-25 14:16:23', '2026-03-25 14:20:42'),
(51, 3, 30, 'Năng lượng điện, công suất điện', 4, '2026-03-25 14:16:47', '2026-03-25 14:20:38'),
(52, 1, 48, 'Sự chuyển thể', 3, '2026-03-25 14:36:58', '2026-03-25 14:39:24'),
(53, 2, 48, 'Nội năng, định luật 1 của nhiệt động lực học', 4, '2026-03-25 14:37:14', '2026-03-25 14:39:20'),
(54, 3, 48, 'Thang nhiệt độ, nhiệt kế', 3, '2026-03-25 14:37:28', '2026-03-25 14:39:05'),
(55, 4, 48, 'Nhiệt dung riêng, nhiệt nóng chảy riêng, nhiệt hoá hơi riêng', 4, '2026-03-25 14:38:02', '2026-03-25 14:39:11'),
(56, 1, 49, 'Mô hình động học phân tử chất khí', 2, '2026-03-25 14:50:46', '2026-03-25 14:52:19'),
(57, 2, 49, 'Phương trình trạng thái', 5, '2026-03-25 14:51:03', '2026-03-25 14:53:40'),
(58, 3, 49, 'Áp suất khí theo mô hình động học phân tử', 3, '2026-03-25 14:51:43', '2026-03-25 14:53:35'),
(59, 4, 49, 'Động năng phân tử', 2, '2026-03-25 14:51:58', '2026-03-25 14:53:13'),
(60, 1, 50, 'Khái niệm từ trường', 6, '2026-03-25 17:06:35', '2026-03-25 17:07:33'),
(61, 2, 50, 'Lực từ tác dụng lên đoạn dây dẫn mang dòng điện; Cảm ứng từ', 6, '2026-03-25 17:06:54', '2026-03-25 17:07:45'),
(62, 3, 50, 'Từ thông; Cảm ứng điện từ', 6, '2026-03-25 17:07:15', '2026-03-25 17:07:54'),
(63, 1, 51, 'Cấu trúc hạt nhân', 6, '2026-03-25 17:15:46', '2026-03-25 17:16:28'),
(64, 2, 51, 'Độ hụt khối và năng lượng liên kết hạt nhân', 6, '2026-03-25 17:16:02', '2026-03-25 17:16:34'),
(65, 3, 51, 'Sự phóng xạ và chu kì bán rã', 4, '2026-03-25 17:16:21', '2026-03-25 17:16:49'),
(66, 1, 39, 'Khái niệm trường hấp dẫn', 3, '2026-03-26 14:32:21', '2026-03-26 14:34:59'),
(67, 2, 39, 'Lực hấp dẫn', 3, '2026-03-26 14:32:44', '2026-03-26 14:35:03'),
(68, 3, 39, 'Cường độ trường hấp dẫn', 5, '2026-03-26 14:33:03', '2026-03-26 14:35:09'),
(69, 4, 39, 'Thế hấp dẫn và thế năng hấp dẫn', 4, '2026-03-26 14:33:27', '2026-03-26 14:35:15'),
(70, 1, 40, 'Biến điệu', 3, '2026-03-26 21:48:59', '2026-03-26 21:49:44'),
(71, 2, 40, 'Tín hiệu tương tự và tín hiệu số', 4, '2026-03-26 21:49:15', '2026-03-26 21:49:51'),
(72, 3, 40, 'Suy giảm tín hiệu', 3, '2026-03-26 21:49:36', '2026-03-26 21:49:57'),
(73, 1, 41, 'Khuếch đại thuật toán', 3, '2026-03-26 21:53:44', '2026-03-26 21:54:48'),
(74, 2, 41, 'Thiết bị đầu ra', 4, '2026-03-26 21:53:57', '2026-03-26 21:54:53'),
(75, 3, 41, 'Thiết bị cảm biến (sensing devices)', 3, '2026-03-26 21:54:18', '2026-03-26 21:54:57'),
(76, 1, 60, 'Các đặc trưng của dòng điện xoay chiều', 4, '2026-03-27 00:26:00', '2026-03-27 00:28:11'),
(77, 2, 60, 'Máy biến áp', 3, '2026-03-27 00:27:37', '2026-03-27 00:28:16'),
(78, 3, 60, 'Chỉnh lưu dòng điện xoay chiều', 3, '2026-03-27 00:27:53', '2026-03-27 00:28:20'),
(79, 1, 61, 'Bản chất và cách tạo ra tia X', 2, '2026-03-27 00:33:18', '2026-03-27 00:34:33'),
(80, 2, 61, 'Chẩn đoán bằng tia X', 3, '2026-03-27 00:33:32', '2026-03-27 00:34:41'),
(81, 3, 61, 'Chẩn đoán bằng siêu âm', 2, '2026-03-27 00:33:48', '2026-03-27 00:34:48'),
(82, 4, 61, 'Chụp cắt lớp, cộng hưởng từ', 3, '2026-03-27 00:34:21', '2026-03-27 00:34:53'),
(83, 1, 62, 'Hiệu ứng quang điện và năng lượng của photon', 5, '2026-03-27 00:35:24', '2026-03-27 00:39:17'),
(84, 2, 62, 'Lưỡng tính sóng hạt', 2, '2026-03-27 00:36:08', '2026-03-27 00:38:50'),
(85, 3, 62, 'Quang phổ vạch của nguyên tử', 4, '2026-03-27 00:37:07', '2026-03-27 00:39:13'),
(86, 4, 62, 'Vùng năng lượng', 4, '2026-03-27 00:37:28', '2026-03-27 00:39:04'),
(87, 1, 23, 'Lý thuyết về sai số', 1, '2026-03-27 00:55:09', '2026-03-27 00:55:09'),
(88, 2, 23, 'Các dụng cụ thí nghiệm cơ bản', 1, '2026-03-27 00:55:22', '2026-03-27 00:55:22'),
(89, 3, 23, 'Các phương pháp xử lí số liệu', 2, '2026-03-27 00:55:40', '2026-03-27 00:56:10'),
(90, 1, 24, 'Hệ quy chiếu. Vận tốc và gia tốc trong chuyển động cong.', 2, '2026-03-27 00:56:55', '2026-03-27 00:59:05'),
(91, 2, 24, 'Động lượng, bảo toàn động lượng.', 2, '2026-03-27 00:57:11', '2026-03-27 00:59:09'),
(92, 3, 24, 'Động năng và thế năng, năng lượng và công', 2, '2026-03-27 00:57:27', '2026-03-27 00:59:13'),
(93, 4, 24, 'Các định luật Kepler', 2, '2026-03-27 00:57:51', '2026-03-27 00:59:16'),
(94, 5, 24, 'Chuyển động trong trường hấp dẫn', 2, '2026-03-27 00:58:16', '2026-03-27 00:59:20'),
(95, 6, 24, 'Định luật vạn vật hấp dẫn. Bài toán hai vật và nhiều vật.', 2, '2026-03-27 00:58:36', '2026-03-27 00:59:23'),
(96, 7, 24, 'Chuyển động của vệ tinh nhân tạo và trạm vũ trụ', 2, '2026-03-27 00:58:57', '2026-03-27 00:59:27'),
(97, 1, 25, 'Chuyển động của một vật rắn.', 2, '2026-03-27 00:59:57', '2026-03-27 01:01:08'),
(98, 2, 25, 'Vận tốc góc', 3, '2026-03-27 01:00:13', '2026-03-27 01:01:23'),
(99, 3, 25, 'Gia tốc góc', 3, '2026-03-27 01:00:27', '2026-03-27 01:01:19'),
(100, 1, 26, 'Phương trình động lực học vật rắn. Cân bằng chất điểm và cân bằng vật rắn.', 2, '2026-03-27 01:02:14', '2026-03-27 01:34:27'),
(101, 2, 26, 'Mô men quán tính của vật rắn.', 2, '2026-03-27 01:02:31', '2026-03-27 01:34:32'),
(102, 3, 26, 'Động năng và thế năng, năng lượng và công.', 3, '2026-03-27 01:02:50', '2026-03-27 01:34:49'),
(103, 4, 26, 'Mô men động lượng.', 3, '2026-03-27 01:03:06', '2026-03-27 01:34:56'),
(104, 1, 27, 'Khối tâm. Hệ quy chiếu khối tâm.', 3, '2026-03-27 01:04:00', '2026-03-27 01:04:50'),
(105, 2, 27, 'Hệ quy chiếu có gia tốc', 3, '2026-03-27 01:04:19', '2026-03-27 01:05:00'),
(106, 3, 27, 'Lực quán tính', 2, '2026-03-27 01:04:42', '2026-03-27 01:05:06'),
(107, 1, 28, 'Khảo sát bài toán va chạm', 3, '2026-03-27 01:06:09', '2026-03-27 01:46:03'),
(108, 2, 28, 'Nghiệm lại ba định luật Newton', 0, '2026-03-27 01:06:23', '2026-03-27 01:45:51'),
(109, 3, 28, 'Xác định mô men quán tính của trụ đặc và lực ma sát trong ổ trục quay', 0, '2026-03-27 01:06:54', '2026-03-27 01:44:39'),
(110, 4, 28, 'Xác định nhiệt dung riêng của chất rắn bằng nhiệt lượng kế', 4, '2026-03-27 01:07:11', '2026-03-27 01:46:07'),
(111, 5, 28, 'Đo độ nhớt của chất lỏng bằng phương pháp Stock', 0, '2026-03-27 01:07:27', '2026-03-27 01:45:31'),
(112, 6, 28, 'Khảo sát quy luật dao động của con lắc vật lí', 4, '2026-03-27 01:07:50', '2026-03-27 01:46:12'),
(113, 7, 28, 'Xác định hằng số hấp dẫn', 0, '2026-03-27 01:08:09', '2026-03-27 01:45:37'),
(114, 1, 44, 'Trường điện', 5, '2026-03-27 01:51:34', '2026-03-27 01:53:12'),
(115, 2, 44, 'Trường từ', 5, '2026-03-27 01:51:53', '2026-03-27 01:53:24'),
(116, 1, 42, 'Dao động', 3, '2026-03-27 01:54:03', '2026-03-27 02:14:44'),
(117, 2, 42, 'Sóng', 3, '2026-03-27 01:54:18', '2026-03-27 02:14:48'),
(118, 1, 43, 'Sự truyền sáng', 3, '2026-03-27 01:54:53', '2026-03-27 01:55:32'),
(119, 2, 43, 'Tương tác của ánh sáng với môi trường', 2, '2026-03-27 01:55:11', '2026-03-27 01:55:37'),
(120, 3, 43, 'Mắt. Các dụng cụ quang', 3, '2026-03-27 01:55:24', '2026-03-27 01:55:43'),
(121, 1, 45, 'Mạch điện', 4, '2026-03-27 01:56:54', '2026-03-27 01:57:15'),
(122, 2, 45, 'Bán dẫn', 4, '2026-03-27 01:57:10', '2026-03-27 01:57:19'),
(123, 1, 46, 'Nguyên lí tương đối và phép biến đổi Lorentz', 3, '2026-03-27 01:57:45', '2026-03-27 01:58:08'),
(124, 2, 46, 'Giải thích các hiện tượng bằng thuyết tương đối', 5, '2026-03-27 01:58:03', '2026-03-27 01:58:12'),
(125, 1, 47, 'Khảo sát các quy luật của mạch điện RLC nối tiếp.', 4, '2026-03-27 01:58:51', '2026-03-27 02:02:26'),
(126, 2, 47, 'Xác định chiết suất lăng kính bằng phổ giác kế.', 0, '2026-03-27 01:59:04', '2026-03-27 02:01:13'),
(127, 3, 47, 'Xác định điện trở của linh kiện điện', 0, '2026-03-27 01:59:17', '2026-03-27 02:01:21'),
(128, 4, 47, 'Khảo sát sự phụ thuộc điện trở theo nhiệt độ', 4, '2026-03-27 01:59:31', '2026-03-27 02:02:30'),
(129, 5, 47, 'Khảo sát hiệu ứng Hall', 0, '2026-03-27 01:59:50', '2026-03-27 02:01:38'),
(130, 6, 47, 'Khảo sát đường đặc trưng V-A của các linh kiện điện', 3, '2026-03-27 02:00:05', '2026-03-27 02:02:36'),
(131, 7, 47, 'Xác định độ cứng của vật liệu đàn hồi bằng phương pháp dao động', 0, '2026-03-27 02:00:20', '2026-03-27 02:01:57'),
(132, 8, 47, 'Đo hằng số thời gian trong quá trình phóng điện và tích điện của tụ điện', 0, '2026-03-27 02:00:39', '2026-03-27 02:02:02'),
(133, 9, 47, 'Khảo sát tính chất sắt từ của vật liệu', 0, '2026-03-27 02:00:58', '2026-03-27 02:02:07'),
(134, 3, 42, 'Giao thoa, nhiễu xạ', 2, '2026-03-27 02:14:34', '2026-03-27 02:14:52'),
(135, 2, 63, 'Các định luật nhiệt động học', 4, '2026-03-27 02:38:13', '2026-03-27 02:38:59'),
(136, 3, 63, 'Lí thuyết động học của các chất khí lí tưởng', 4, '2026-03-27 02:38:33', '2026-03-27 02:39:03'),
(137, 1, 64, 'Dạng tích phân của các phương trình Maxwell', 4, '2026-03-27 02:39:26', '2026-03-27 02:41:09'),
(138, 2, 64, 'Sự truyền sóng điện từ qua các môi trường', 3, '2026-03-27 02:39:39', '2026-03-27 02:40:44'),
(139, 3, 64, 'Định luật Planck', 3, '2026-03-27 02:39:56', '2026-03-27 02:40:48'),
(140, 4, 64, 'Định luật dịch chuyển Wien', 3, '2026-03-27 02:40:10', '2026-03-27 02:40:52'),
(141, 5, 64, 'Định luật Stefan- Boltzmann', 3, '2026-03-27 02:40:26', '2026-03-27 02:40:56'),
(142, 1, 65, 'Lưỡng tính sóng hạt', 4, '2026-03-27 02:41:39', '2026-03-27 04:29:02'),
(143, 2, 65, 'Nguyên lí bất định Heisenberg', 4, '2026-03-27 02:41:53', '2026-03-27 04:29:06'),
(144, 1, 66, 'Quang phổ phát xạ và hấp thụ', 3, '2026-03-27 02:43:55', '2026-03-27 02:44:28'),
(145, 2, 66, 'Nguyên lí Pauli', 3, '2026-03-27 02:44:08', '2026-03-27 02:44:32'),
(146, 3, 66, 'Phân tích phổ trong khoa học vật liệu', 2, '2026-03-27 02:44:21', '2026-03-27 02:44:36'),
(147, 1, 67, 'Xác định hằng số khí', 4, '2026-03-27 02:45:16', '2026-03-27 02:53:26'),
(148, 2, 67, 'Xác định hằng số Boltzmann', 0, '2026-03-27 02:45:28', '2026-03-27 02:48:30'),
(149, 3, 67, 'Xác định nhiệt độ dựa vào lí thuyết vật đen tuyệt đối', 4, '2026-03-27 02:45:42', '2026-03-27 02:53:31'),
(150, 4, 67, 'Xác định hiệu suất của pin quang điện', 0, '2026-03-27 02:45:59', '2026-03-27 02:48:52'),
(151, 5, 67, 'Khảo sát tính chất của tia phóng xạ', 0, '2026-03-27 02:46:14', '2026-03-27 02:48:42'),
(152, 6, 67, 'Xác định quãng đường tự do trung bình', 0, '2026-03-27 02:46:31', '2026-03-27 02:49:00'),
(153, 7, 67, 'Xác định hằng số Planck', 0, '2026-03-27 02:47:07', '2026-03-27 02:49:08'),
(154, 8, 67, 'Xác định bước sóng phát xạ của Natri', 3, '2026-03-27 02:47:32', '2026-03-27 02:53:36'),
(155, 9, 67, 'Xác định hằng số Avogadro', 0, '2026-03-27 02:47:50', '2026-03-27 02:49:19');

-- --------------------------------------------------------

--
-- Table structure for table `objectives`
--

CREATE TABLE `objectives` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `content_id` bigint(20) UNSIGNED NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `objectives`
--

INSERT INTO `objectives` (`id`, `content_id`, `description`, `created_at`, `updated_at`) VALUES
(12, 7, '<p>N&ecirc;u được đối tượng nghi&ecirc;n cứu của Vật l&iacute; học v&agrave; mục ti&ecirc;u của m&ocirc;n Vật l&iacute;.</p>', '2026-03-24 06:32:37', '2026-03-24 06:32:37'),
(13, 7, '<p>Ph&acirc;n t&iacute;ch được một số ảnh hưởng của vật l&iacute; đối với cuộc sống, đối với sự ph&aacute;t triển của khoa học,<br>c&ocirc;ng nghệ v&agrave; kĩ thuật.</p>', '2026-03-24 06:32:53', '2026-03-24 06:32:53'),
(14, 7, '<p>N&ecirc;u được v&iacute; dụ chứng tỏ kiến thức, kĩ năng vật l&iacute; được sử dụng trong một số lĩnh vực kh&aacute;c nhau.</p>', '2026-03-24 06:33:49', '2026-03-24 06:33:49'),
(15, 7, '<p>N&ecirc;u được một số v&iacute; dụ về phương ph&aacute;p nghi&ecirc;n cứu vật l&iacute; (phương ph&aacute;p thực nghiệm v&agrave; phương ph&aacute;p l&iacute; thuyết).</p>', '2026-03-24 06:34:13', '2026-03-24 06:34:13'),
(16, 7, '<p>M&ocirc; tả được c&aacute;c bước trong tiến tr&igrave;nh t&igrave;m hiểu thế giới tự nhi&ecirc;n dưới g&oacute;c độ vật l&iacute;.</p>', '2026-03-24 06:34:41', '2026-03-24 06:34:41'),
(17, 7, '<p>Thảo luận để n&ecirc;u được: Một số loại sai số đơn giản hay gặp khi đo c&aacute;c đại lượng vật l&iacute; v&agrave; c&aacute;ch khắc phục ch&uacute;ng.</p>', '2026-03-24 06:35:05', '2026-03-24 06:35:05'),
(18, 7, '<p>Thảo luận để n&ecirc;u được: C&aacute;c quy tắc an to&agrave;n trong nghi&ecirc;n cứu v&agrave; học tập m&ocirc;n Vật l&iacute;.</p>', '2026-03-24 06:35:35', '2026-03-24 06:35:35'),
(19, 11, '<p>Lập luận để r&uacute;t ra được c&ocirc;ng thức t&iacute;nh tốc độ trung b&igrave;nh, định nghĩa được tốc độ theo một phương.</p>', '2026-03-24 07:13:53', '2026-03-24 07:13:53'),
(20, 11, '<p>Từ h&igrave;nh ảnh hoặc v&iacute; dụ thực tiễn, định nghĩa được độ dịch chuyển.</p>', '2026-03-24 07:14:18', '2026-03-24 07:14:18'),
(21, 11, '<p>So s&aacute;nh được qu&atilde;ng đường đi được v&agrave; độ dịch chuyển.</p>', '2026-03-24 07:14:34', '2026-03-24 07:14:34'),
(22, 11, '<p>Dựa v&agrave;o định nghĩa tốc độ theo một phương v&agrave; độ dịch chuyển, r&uacute;t ra được c&ocirc;ng thức t&iacute;nh v&agrave; địnhcnghĩa được vận tốc.</p>', '2026-03-24 07:14:57', '2026-03-24 07:15:25'),
(23, 11, '<p>Thực hiện th&iacute; nghiệm (hoặc dựa tr&ecirc;n số liệu cho trước), vẽ được đồ thị độ dịch chuyển &ndash; thời gian trong chuyển động thẳng.</p>', '2026-03-24 07:15:55', '2026-03-24 07:15:55'),
(24, 11, '<p>T&iacute;nh được tốc độ từ độ dốc của đồ thị độ dịch chuyển &ndash; thời gian.</p>', '2026-03-24 07:16:19', '2026-03-24 07:16:19'),
(25, 11, '<p>X&aacute;c định được độ dịch chuyển tổng hợp, vận tốc tổng hợp.</p>', '2026-03-24 07:16:39', '2026-03-24 07:16:39'),
(26, 11, '<p>Vận dụng được c&ocirc;ng thức t&iacute;nh tốc độ, vận tốc.</p>', '2026-03-24 07:16:52', '2026-03-24 07:16:52'),
(27, 11, '<p>Thảo luận để thiết kế phương &aacute;n hoặc lựa chọn phương &aacute;n v&agrave; thực hiện phương &aacute;n, đo được tốc độ bằng dụng cụ thực h&agrave;nh.</p>', '2026-03-24 07:17:11', '2026-03-24 07:17:11'),
(28, 11, '<p>M&ocirc; tả được một v&agrave;i phương ph&aacute;p đo tốc độ th&ocirc;ng dụng v&agrave; đ&aacute;nh gi&aacute; được ưu, nhược điểm của ch&uacute;ng.</p>', '2026-03-24 07:17:36', '2026-03-24 07:17:36'),
(29, 2, '<p>Thực hiện th&iacute; nghiệm v&agrave; lập luận dựa v&agrave;o sự biến đổi vận tốc trong chuyển động thẳng, r&uacute;t ra được c&ocirc;ng thức t&iacute;nh gia tốc; n&ecirc;u được ý nghĩa, đơn vị của gia tốc.</p>', '2026-03-24 07:24:15', '2026-03-24 07:24:15'),
(30, 2, '<p>Thực hiện th&iacute; nghiệm (hoặc dựa tr&ecirc;n số liệu cho trước), vẽ được đồ thị vận tốc &ndash; thời gian trong chuyển động thẳng.</p>', '2026-03-24 07:24:34', '2026-03-24 07:24:34'),
(31, 2, '<p>Vận dụng đồ thị vận tốc &ndash; thời gian để t&iacute;nh được độ dịch chuyển v&agrave; gia tốc trong một số trường hợp đơn giản.</p>', '2026-03-24 07:24:54', '2026-03-24 07:24:54'),
(32, 2, '<p>R&uacute;t ra được c&aacute;c c&ocirc;ng thức của chuyển động thẳng biến đổi đều (kh&ocirc;ng được d&ugrave;ng t&iacute;ch ph&acirc;n).</p>', '2026-03-24 07:25:05', '2026-03-24 07:25:05'),
(33, 2, '<p>Vận dụng được c&aacute;c c&ocirc;ng thức của chuyển động thẳng biến đổi đều.</p>', '2026-03-24 07:25:18', '2026-03-24 07:25:18'),
(34, 2, '<p>M&ocirc; tả v&agrave; giải th&iacute;ch được chuyển động khi vật c&oacute; vận tốc kh&ocirc;ng đổi theo một phương v&agrave; c&oacute; gia tốc kh&ocirc;ng đổi theo phương vu&ocirc;ng g&oacute;c với phương n&agrave;y.</p>', '2026-03-24 07:25:36', '2026-03-24 07:25:36'),
(35, 2, '<p>Thảo luận để thiết kế phương &aacute;n hoặc lựa chọn phương &aacute;n v&agrave; thực hiện phương &aacute;n, đo được gia tốc<br>rơi tự do bằng dụng cụ thực h&agrave;nh.</p>', '2026-03-24 07:25:50', '2026-03-24 07:25:50'),
(36, 2, '<p>Thực hiện được dự &aacute;n hay đề t&agrave;i nghi&ecirc;n cứu t&igrave;m điều kiện n&eacute;m vật trong kh&ocirc;ng kh&iacute; ở độ cao n&agrave;o<br>đ&oacute; để đạt độ cao hoặc tầm xa lớn nhất.</p>', '2026-03-24 07:26:09', '2026-03-24 07:26:09'),
(37, 12, '<p>Thực hiện th&iacute; nghiệm, hoặc sử dụng số liệu cho trước để r&uacute;t ra được gia tốc &nbsp;<span class=\"math-tex\">\\(a\\)</span> tỉ lệ thuận với lực &nbsp;<span class=\"math-tex\">\\(F\\)</span>, gia tốc &nbsp;<span class=\"math-tex\">\\(a\\)</span> tỉ lệ nghịch với khối lượng &nbsp;<span class=\"math-tex\">\\(m\\)</span>, từ đ&oacute; r&uacute;t ra được biểu thức &nbsp;<span class=\"math-tex\">\\(a=\\frac{F}{m}\\)</span> hoặc &nbsp;<span class=\"math-tex\">\\(F = ma\\)</span> (định luật 2 Newton).</p>', '2026-03-24 07:30:35', '2026-03-24 07:31:13'),
(38, 12, '<p>Từ kết quả đ&atilde; c&oacute; (lấy từ th&iacute; nghiệm hay sử dụng số liệu cho trước), hoặc lập luận dựa v&agrave;o &nbsp;<span class=\"math-tex\">\\(a=\\frac{F}{m}\\)</span>, n&ecirc;u được khối lượng l&agrave; đại lượng đặc trưng cho mức qu&aacute;n t&iacute;nh của vật.</p>', '2026-03-24 07:32:11', '2026-03-24 07:32:11'),
(39, 12, '<p>Ph&aacute;t biểu định luật 1 Newton v&agrave; minh hoạ được bằng v&iacute; dụ cụ thể.</p>', '2026-03-24 07:32:32', '2026-03-24 07:32:32'),
(40, 12, '<p>Vận dụng được mối li&ecirc;n hệ đơn vị dẫn xuất với 7 đơn vị cơ bản của hệ SI.</p>', '2026-03-24 07:32:42', '2026-03-24 07:32:42'),
(41, 12, '<p>N&ecirc;u được: trọng lực t&aacute;c dụng l&ecirc;n vật l&agrave; lực hấp dẫn giữa Tr&aacute;i Đất v&agrave; vật; trọng t&acirc;m của vật l&agrave; điểm đặt của trọng lực t&aacute;c dụng v&agrave;o vật; trọng lượng của vật được t&iacute;nh bằng t&iacute;ch khối lượng của vật với gia tốc rơi tự do.</p>', '2026-03-24 07:33:06', '2026-03-24 07:33:06'),
(42, 12, '<p>M&ocirc; tả được bằng v&iacute; dụ thực tế về lực bằng nhau, kh&ocirc;ng bằng nhau.</p>', '2026-03-24 07:33:24', '2026-03-24 07:33:24'),
(43, 12, '<p>M&ocirc; tả được một c&aacute;ch định t&iacute;nh chuyển động rơi trong trường trọng lực đều khi c&oacute; sức cản của kh&ocirc;ng kh&iacute;.</p>', '2026-03-24 07:33:41', '2026-03-24 07:33:41'),
(44, 12, '<p>Thực hiện được dự &aacute;n hay đề t&agrave;i nghi&ecirc;n cứu ứng dụng sự tăng hay giảm sức cản kh&ocirc;ng kh&iacute; theo h&igrave;nh dạng của vật.</p>', '2026-03-24 07:33:58', '2026-03-24 07:33:58'),
(45, 12, '<p>Ph&aacute;t biểu được định luật 3 Newton, minh hoạ được bằng v&iacute; dụ cụ thể; vận dụng được định luật 3 Newton trong một số trường hợp đơn giản.</p>', '2026-03-24 07:34:17', '2026-03-24 07:34:17'),
(46, 13, '<p>M&ocirc; tả được bằng v&iacute; dụ thực tiễn v&agrave; biểu diễn được bằng h&igrave;nh vẽ: Trọng lực; Lực ma s&aacute;t; Lực cản khi một vật chuyển động trong nước (hoặc trong kh&ocirc;ng kh&iacute;); Lực n&acirc;ng (đẩy l&ecirc;n tr&ecirc;n) của nước; Lực căng d&acirc;y.</p>', '2026-03-24 07:35:07', '2026-03-24 07:35:07'),
(47, 13, '<p>Giải th&iacute;ch được lực n&acirc;ng t&aacute;c dụng l&ecirc;n một vật ở trong trong nước (hoặc trong kh&ocirc;ng kh&iacute;).</p>', '2026-03-24 07:35:20', '2026-03-24 07:35:20'),
(48, 14, '<p>D&ugrave;ng h&igrave;nh vẽ, tổng hợp được c&aacute;c lực tr&ecirc;n một mặt phẳng.</p>', '2026-03-24 07:35:42', '2026-03-24 07:35:42'),
(49, 14, '<p>D&ugrave;ng h&igrave;nh vẽ, ph&acirc;n t&iacute;ch được một lực th&agrave;nh c&aacute;c lực th&agrave;nh phần vu&ocirc;ng g&oacute;c.</p>', '2026-03-24 07:35:57', '2026-03-24 07:35:57'),
(50, 14, '<p>Thảo luận để thiết kế phương &aacute;n hoặc lựa chọn phương &aacute;n v&agrave; thực hiện phương &aacute;n, tổng hợp được hai lực đồng quy bằng dụng cụ thực h&agrave;nh.</p>', '2026-03-24 07:36:12', '2026-03-24 07:36:12'),
(51, 14, '<p>N&ecirc;u được kh&aacute;i niệm moment lực, moment ngẫu lực; N&ecirc;u được t&aacute;c dụng của ngẫu lực l&ecirc;n một vật chỉ l&agrave;m quay vật.</p>', '2026-03-24 07:36:27', '2026-03-24 07:36:27'),
(52, 14, '<p>Ph&aacute;t biểu v&agrave; vận dụng được quy tắc moment cho một số trường hợp đơn giản trong thực tế.</p>', '2026-03-24 07:36:50', '2026-03-24 07:36:50'),
(53, 14, '<p>Thảo luận để r&uacute;t ra được điều kiện để vật c&acirc;n bằng: lực tổng hợp t&aacute;c dụng l&ecirc;n vật bằng kh&ocirc;ng v&agrave; tổng moment lực t&aacute;c dụng l&ecirc;n vật (đối với một điểm bất k&igrave;) bằng kh&ocirc;ng.</p>', '2026-03-24 07:37:07', '2026-03-24 07:37:07'),
(54, 14, '<p>Thảo luận để thiết kế phương &aacute;n hoặc lựa chọn phương &aacute;n v&agrave; thực hiện phương &aacute;n, tổng hợp được<br>hai lực song song bằng dụng cụ thực h&agrave;nh.</p>', '2026-03-24 07:37:22', '2026-03-24 07:37:22'),
(55, 15, '<p>N&ecirc;u được khối lượng ri&ecirc;ng của một chất l&agrave; khối lượng của một đơn vị thể t&iacute;ch của chất đ&oacute;.</p>', '2026-03-24 07:37:54', '2026-03-24 07:37:54'),
(56, 15, '<p>Th&agrave;nh lập v&agrave; vận dụng được phương tr&igrave;nh &Delta;p = &rho;g&Delta;h trong một số trường hợp đơn giản; đề xuất thiết kế được m&ocirc; h&igrave;nh minh hoạ.</p>', '2026-03-24 07:38:16', '2026-03-24 07:38:16'),
(57, 16, '<p>Chế tạo m&ocirc; h&igrave;nh đơn giản minh hoạ được định luật bảo to&agrave;n năng lượng, li&ecirc;n quan đến một số dạng năng lượng kh&aacute;c nhau.</p>', '2026-03-24 07:38:55', '2026-03-24 07:38:55'),
(58, 16, '<p>Tr&igrave;nh b&agrave;y được v&iacute; dụ chứng tỏ c&oacute; thể truyền năng lượng từ vật n&agrave;y sang vật kh&aacute;c bằng c&aacute;ch thực hiện c&ocirc;ng.</p>', '2026-03-24 07:39:10', '2026-03-24 07:39:10'),
(59, 16, '<p>N&ecirc;u được biểu thức t&iacute;nh c&ocirc;ng bằng t&iacute;ch của lực t&aacute;c dụng v&agrave; độ dịch chuyển theo phương của lực, n&ecirc;u được đơn vị đo c&ocirc;ng l&agrave; đơn vị đo năng lượng (với 1 J = 1 Nm); T&iacute;nh được c&ocirc;ng trong một số trường hợp đơn giản.</p>', '2026-03-24 07:39:30', '2026-03-24 07:39:30'),
(60, 17, '<p>Từ phương tr&igrave;nh chuyển động thẳng biến đổi đều với vận tốc ban đầu bằng kh&ocirc;ng, r&uacute;t ra được động năng của vật c&oacute; gi&aacute; trị bằng c&ocirc;ng của lực t&aacute;c dụng l&ecirc;n vật.</p>', '2026-03-24 07:40:07', '2026-03-24 07:40:07'),
(61, 17, '<p>N&ecirc;u được c&ocirc;ng thức t&iacute;nh thế năng trong trường trọng lực đều, vận dụng được trong một số trường hợp đơn giản.</p>', '2026-03-24 07:40:22', '2026-03-24 07:40:22'),
(62, 17, '<p>Ph&acirc;n t&iacute;ch được sự chuyển ho&aacute; động năng v&agrave; thế năng của vật trong một số trường hợp đơn giản.</p>', '2026-03-24 07:40:33', '2026-03-24 07:40:33'),
(63, 17, '<p>N&ecirc;u được kh&aacute;i niệm cơ năng; ph&aacute;t biểu được định luật bảo to&agrave;n cơ năng v&agrave; vận dụng được định luật bảo to&agrave;n cơ năng trong một số trường hợp đơn giản.</p>', '2026-03-24 07:40:45', '2026-03-24 07:40:45'),
(64, 18, '<p>Từ một số t&igrave;nh huống thực tế, thảo luận để n&ecirc;u được ý nghĩa vật l&iacute; v&agrave; định nghĩa c&ocirc;ng suất.</p>', '2026-03-24 07:41:13', '2026-03-24 07:41:13'),
(65, 18, '<p>Vận dụng được mối li&ecirc;n hệ c&ocirc;ng suất (hay tốc độ thực hiện c&ocirc;ng) với t&iacute;ch của lực v&agrave; vận tốc trong một số t&igrave;nh huống thực tế.</p>', '2026-03-24 07:41:26', '2026-03-24 07:41:26'),
(66, 18, '<p>Từ t&igrave;nh huống thực tế, thảo luận để n&ecirc;u được định nghĩa hiệu suất, vận dụng được hiệu suất trong một số trường hợp thực tế.</p>', '2026-03-24 07:41:41', '2026-03-24 07:41:41'),
(67, 19, '<p>Từ t&igrave;nh huống thực tế, thảo luận để n&ecirc;u được ý nghĩa vật l&iacute; v&agrave; định nghĩa động lượng.</p>', '2026-03-24 07:42:17', '2026-03-24 07:42:17'),
(68, 20, '<p>Thực hiện th&iacute; nghiệm v&agrave; thảo luận, ph&aacute;t biểu được định luật bảo to&agrave;n động lượng trong hệ k&iacute;n.</p>', '2026-03-24 07:42:37', '2026-03-24 07:42:37'),
(69, 20, '<p>Vận dụng được định luật bảo to&agrave;n động lượng trong một số trường hợp đơn giản.</p>', '2026-03-24 07:42:48', '2026-03-24 07:42:48'),
(70, 21, '<p>R&uacute;t ra được mối li&ecirc;n hệ giữa lực tổng hợp t&aacute;c dụng l&ecirc;n vật v&agrave; tốc độ thay đổi của động lượng (lực tổng hợp t&aacute;c dụng l&ecirc;n vật l&agrave; tốc độ thay đổi của động lượng của vật).</p>', '2026-03-24 07:43:16', '2026-03-24 07:43:16'),
(71, 21, '<p>Thực hiện th&iacute; nghiệm v&agrave; thảo luận được sự thay đổi năng lượng trong một số trường hợp va chạm đơn giản.</p>', '2026-03-24 07:43:34', '2026-03-24 07:43:34'),
(72, 21, '<p>Thảo luận để giải th&iacute;ch được một số hiện tượng đơn giản.</p>', '2026-03-24 07:43:46', '2026-03-24 07:43:46'),
(73, 21, '<p>Thảo luận để thiết kế phương &aacute;n hoặc lựa chọn phương &aacute;n, thực hiện phương &aacute;n, x&aacute;c định được tốc độ v&agrave; đ&aacute;nh gi&aacute; được động lượng của vật trước v&agrave; sau va chạm bằng dụng cụ thực h&agrave;nh.</p>', '2026-03-24 07:44:03', '2026-03-24 07:44:03'),
(74, 22, '<p>Từ t&igrave;nh huống thực tế, thảo luận để n&ecirc;u được định nghĩa radian v&agrave; biểu diễn được độ dịch chuyển g&oacute;c theo radian.</p>', '2026-03-24 07:45:08', '2026-03-24 07:45:08'),
(75, 22, '<p>Vận dụng được kh&aacute;i niệm tốc độ g&oacute;c.</p>', '2026-03-24 07:45:21', '2026-03-24 07:45:21'),
(76, 23, '<p>Vận dụng được biểu thức gia tốc hướng t&acirc;m &nbsp;<span class=\"math-tex\">\\[a=r\\omega^2=\\frac{v^2}{r}\\]</span>.</p>', '2026-03-24 07:47:27', '2026-03-24 07:47:27'),
(77, 23, '<p>Vận dụng được biểu thức lực hướng t&acirc;m &nbsp;<span class=\"math-tex\">\\[F=m\\omega^2r=\\frac{mv^2}{r}\\]</span>.</p>', '2026-03-24 07:48:51', '2026-03-24 07:48:51'),
(78, 23, '<p>Thảo luận v&agrave; đề xuất giải ph&aacute;p an to&agrave;n cho một số t&igrave;nh huống chuyển động tr&ograve;n trong thực tế.</p>', '2026-03-24 07:49:11', '2026-03-24 07:49:11'),
(79, 24, '<p>Thực hiện th&iacute; nghiệm đơn giản (hoặc sử dụng t&agrave;i liệu đa phương tiện), n&ecirc;u được sự biến dạng k&eacute;o, biến dạng n&eacute;n; m&ocirc; tả được c&aacute;c đặc t&iacute;nh của l&ograve; xo: giới hạn đ&agrave;n hồi, độ d&atilde;n, độ cứng.</p>', '2026-03-24 07:49:59', '2026-03-24 07:49:59'),
(80, 25, '<p>Thảo luận để thiết kế phương &aacute;n hoặc lựa chọn phương &aacute;n v&agrave; thực hiện phương &aacute;n, t&igrave;m mối li&ecirc;n hệ giữa lực đ&agrave;n hồi v&agrave; độ biến dạng của l&ograve; xo, từ đ&oacute; ph&aacute;t biểu được định luật Hooke.</p>', '2026-03-24 07:50:27', '2026-03-24 07:50:27'),
(81, 25, '<p>Vận dụng được định luật Hooke trong một số trường hợp đơn giản.</p>', '2026-03-24 07:50:39', '2026-03-24 07:50:39'),
(83, 9, '<p>&ndash; Thực hiện th&iacute; nghiệm đơn giản tạo ra được dao động v&agrave; m&ocirc; tả được một số v&iacute; dụ đơn giản về dao động tự do.</p>', '2026-03-25 05:26:03', '2026-03-25 05:26:03'),
(84, 9, '<p>D&ugrave;ng đồ thị li độ &ndash; thời gian c&oacute; dạng h&igrave;nh sin (tạo ra bằng th&iacute; nghiệm, hoặc h&igrave;nh vẽ cho trước), n&ecirc;u được định nghĩa: bi&ecirc;n độ, chu k&igrave;, tần số, tần số g&oacute;c, độ lệch pha.</p>', '2026-03-25 05:26:35', '2026-03-25 05:26:35'),
(85, 9, '<p>Vận dụng được c&aacute;c kh&aacute;i niệm: bi&ecirc;n độ, chu k&igrave;, tần số, tần số g&oacute;c, độ lệch pha để m&ocirc; tả dao động điều ho&agrave;.</p>', '2026-03-25 05:27:21', '2026-03-25 05:27:21'),
(86, 9, '<p>Sử dụng đồ thị, ph&acirc;n t&iacute;ch v&agrave; thực hiện ph&eacute;p t&iacute;nh cần thiết để x&aacute;c định được: độ dịch chuyển, vận tốc v&agrave; gia tốc trong dao động điều ho&agrave;.</p>', '2026-03-25 05:27:59', '2026-03-25 05:27:59'),
(87, 9, '<p>Vận dụng được c&aacute;c phương tr&igrave;nh về li độ v&agrave; vận tốc, gia tốc của dao động điều ho&agrave;.</p>', '2026-03-25 05:28:26', '2026-03-25 05:28:26'),
(88, 9, '<p>Vận dụng được phương tr&igrave;nh &nbsp;<span class=\"math-tex\">\\(a=-\\omega^2x\\)</span> của dao động điều ho&agrave;.</p>', '2026-03-25 05:29:34', '2026-03-25 05:29:34'),
(89, 9, '<p>Sử dụng đồ thị, ph&acirc;n t&iacute;ch v&agrave; thực hiện ph&eacute;p t&iacute;nh cần thiết để m&ocirc; tả được sự chuyển ho&aacute; động năng v&agrave; thế năng trong dao động điều ho&agrave;.</p>', '2026-03-25 05:30:08', '2026-03-25 05:30:08'),
(90, 10, '<p>N&ecirc;u được v&iacute; dụ thực tế về dao động tắt dần, dao động cưỡng bức v&agrave; hiện tượng cộng hưởng.</p>', '2026-03-25 05:31:00', '2026-03-25 05:31:10'),
(91, 10, '<p>Thảo luận, đ&aacute;nh gi&aacute; được sự c&oacute; lợi hay c&oacute; hại của cộng hưởng trong một số trường hợp cụ thể.</p>', '2026-03-25 05:31:32', '2026-03-25 05:31:32'),
(92, 38, '<p>Từ đồ thị độ dịch chuyển &ndash; khoảng c&aacute;ch (tạo ra bằng th&iacute; nghiệm, hoặc h&igrave;nh vẽ cho trước), m&ocirc; tả được s&oacute;ng qua c&aacute;c kh&aacute;i niệm bước s&oacute;ng, bi&ecirc;n độ, tần số, tốc độ v&agrave; cường độ s&oacute;ng.</p>', '2026-03-25 05:40:06', '2026-03-25 05:40:06'),
(93, 38, '<p>Từ định nghĩa của vận tốc, tần số v&agrave; bước s&oacute;ng, r&uacute;t ra được biểu thức &nbsp;<span class=\"math-tex\">\\(v=\\lambda f\\).</span></p>', '2026-03-25 05:41:08', '2026-03-25 05:41:08'),
(94, 38, '<p>Vận dụng được biểu thức &nbsp;<span class=\"math-tex\">\\(v=\\lambda f\\).</span></p>', '2026-03-25 05:42:26', '2026-03-25 05:42:26'),
(95, 38, '<p>N&ecirc;u được v&iacute; dụ chứng tỏ s&oacute;ng truyền năng lượng.</p>', '2026-03-25 05:43:21', '2026-03-25 05:43:21'),
(96, 38, '<p>Sử dụng m&ocirc; h&igrave;nh s&oacute;ng giải th&iacute;ch được một số t&iacute;nh chất đơn giản của &acirc;m thanh v&agrave; &aacute;nh s&aacute;ng.</p>', '2026-03-25 05:43:41', '2026-03-25 05:43:41'),
(97, 38, '<p>Thực hiện th&iacute; nghiệm (hoặc sử dụng t&agrave;i liệu đa phương tiện), thảo luận để n&ecirc;u được mối li&ecirc;n hệ c&aacute;c đại lượng đặc trưng của s&oacute;ng với c&aacute;c đại lượng đặc trưng cho dao động của phần tử m&ocirc;i trường.</p>', '2026-03-25 05:44:05', '2026-03-25 05:44:05'),
(101, 39, '<p>Quan s&aacute;t h&igrave;nh ảnh (hoặc t&agrave;i liệu đa phương tiện) về chuyển động của phần tử m&ocirc;i trường, thảo luận để so s&aacute;nh được s&oacute;ng dọc v&agrave; s&oacute;ng ngang.</p>', '2026-03-25 07:37:49', '2026-03-25 07:37:49'),
(102, 39, '<p>Thảo luận để thiết kế phương &aacute;n hoặc lựa chọn phương &aacute;n v&agrave; thực hiện phương &aacute;n, đo được tần số của s&oacute;ng &acirc;m bằng dao động k&iacute; hoặc dụng cụ thực h&agrave;nh.</p>', '2026-03-25 07:38:13', '2026-03-25 07:38:13'),
(103, 40, '<p>N&ecirc;u được trong ch&acirc;n kh&ocirc;ng, tất cả c&aacute;c s&oacute;ng điện từ đều truyền với c&ugrave;ng tốc độ.</p>', '2026-03-25 07:38:52', '2026-03-25 07:38:52'),
(104, 40, '<p>Liệt k&ecirc; được bậc độ lớn bước s&oacute;ng của c&aacute;c bức xạ chủ yếu trong thang s&oacute;ng điện từ.</p>', '2026-03-25 07:39:21', '2026-03-25 07:39:21'),
(105, 41, '<p>Thực hiện (hoặc m&ocirc; tả) được th&iacute; nghiệm chứng minh sự giao thoa hai s&oacute;ng kết hợp bằng dụng cụ thực h&agrave;nh sử dụng s&oacute;ng nước (hoặc s&oacute;ng &aacute;nh s&aacute;ng).</p>', '2026-03-25 07:39:54', '2026-03-25 07:39:54'),
(106, 41, '<p>Ph&acirc;n t&iacute;ch, đ&aacute;nh gi&aacute; kết quả thu được từ th&iacute; nghiệm, n&ecirc;u được c&aacute;c điều kiện cần thiết để quan s&aacute;t được hệ v&acirc;n giao thoa.</p>', '2026-03-25 07:40:13', '2026-03-25 07:40:13'),
(107, 41, '<p>Vận dụng được biểu thức &nbsp;<span class=\"math-tex\">\\(i=\\frac{D\\lambda}{a}\\)</span>&nbsp; cho giao thoa &aacute;nh s&aacute;ng qua hai khe hẹp.</p>', '2026-03-25 07:41:41', '2026-03-27 08:57:05'),
(108, 42, '<p>Thực hiện th&iacute; nghiệm tạo s&oacute;ng dừng v&agrave; giải th&iacute;ch được sự h&igrave;nh th&agrave;nh s&oacute;ng dừng.</p>', '2026-03-25 07:42:21', '2026-03-25 07:42:21'),
(109, 42, '<p>Sử dụng h&igrave;nh ảnh (tạo ra bằng th&iacute; nghiệm, hoặc h&igrave;nh vẽ cho trước), x&aacute;c định được n&uacute;t v&agrave; bụng của s&oacute;ng dừng.</p>', '2026-03-25 07:42:44', '2026-03-25 07:42:44'),
(110, 42, '<p>Sử dụng c&aacute;c c&aacute;ch biểu diễn đại số v&agrave; đồ thị để ph&acirc;n t&iacute;ch, x&aacute;c định được vị tr&iacute; n&uacute;t v&agrave; bụng của s&oacute;ng dừng.</p>', '2026-03-25 07:43:09', '2026-03-25 07:43:09'),
(111, 43, '<p>Thảo luận để thiết kế phương &aacute;n hoặc lựa chọn phương &aacute;n v&agrave; thực hiện phương &aacute;n, đo được tốc độ truyền &acirc;m bằng dụng cụ thực h&agrave;nh.</p>', '2026-03-25 07:44:10', '2026-03-25 07:44:10'),
(112, 44, '<p>Thực hiện th&iacute; nghiệm hoặc bằng v&iacute; dụ thực tế, m&ocirc; tả được sự h&uacute;t (hoặc đẩy) của một điện t&iacute;ch v&agrave;o một điện t&iacute;ch kh&aacute;c.</p>', '2026-03-25 07:50:38', '2026-03-25 07:50:38'),
(113, 44, '<p>Ph&aacute;t biểu được định luật Coulomb v&agrave; n&ecirc;u được đơn vị đo điện t&iacute;ch.</p>', '2026-03-25 07:50:55', '2026-03-25 07:50:55'),
(114, 44, '<p>Sử dụng biểu thức&nbsp;</p>\r\n<p class=\"text-center math-tex\">\\[F=\\frac{\\left|q_1q_2\\right|}{4\\pi\\varepsilon_0 r^2}\\]</p>\r\n<p>t&iacute;nh v&agrave; m&ocirc; tả được lực tương t&aacute;c giữa hai điện t&iacute;ch điểm đặt trong ch&acirc;n kh&ocirc;ng (hoặc trong kh&ocirc;ng kh&iacute;).</p>', '2026-03-25 07:53:11', '2026-03-27 08:56:13'),
(115, 45, '<p>N&ecirc;u được kh&aacute;i niệm điện trường l&agrave; trường lực được tạo ra bởi điện t&iacute;ch, l&agrave; dạng vật chất tồn tại quanh điện t&iacute;ch v&agrave; truyền tương t&aacute;c giữa c&aacute;c điện t&iacute;ch.</p>', '2026-03-25 07:55:59', '2026-03-25 07:55:59'),
(116, 45, '<p>Sử dụng biểu thức &nbsp;<span class=\"math-tex\">\\[ E=\\frac{\\left|Q\\right|}{4\\pi\\varepsilon_0r^2}, \\]</span>&nbsp;t&iacute;nh v&agrave; m&ocirc; tả được cường độ điện trường do một điện t&iacute;ch điểm Q đặt trong ch&acirc;n kh&ocirc;ng hoặc trong kh&ocirc;ng kh&iacute; g&acirc;y ra tại một điểm c&aacute;ch n&oacute; một khoảng r.</p>', '2026-03-25 07:58:14', '2026-03-25 07:58:38'),
(117, 45, '<p>N&ecirc;u được ý nghĩa của cường độ điện trường v&agrave; định nghĩa được cường độ điện trường tại một điểm được đo bằng tỉ số giữa lực t&aacute;c dụng l&ecirc;n một điện t&iacute;ch dương đặt tại điểm đ&oacute; v&agrave; độ lớn của điện t&iacute;ch đ&oacute;.</p>', '2026-03-25 07:59:22', '2026-03-25 07:59:22'),
(118, 45, '<p>D&ugrave;ng dụng cụ tạo ra (hoặc vẽ) được điện phổ trong một số trường hợp đơn giản.</p>', '2026-03-25 07:59:51', '2026-03-25 07:59:51'),
(119, 45, '<p>Vận dụng được biểu thức:</p>\r\n<p class=\"text-center math-tex\">\\[ E=\\frac{\\left|Q\\right|}{4\\pi\\varepsilon_0r^2}. \\]</p>', '2026-03-25 08:02:00', '2026-03-25 08:02:00'),
(120, 46, '<p>Sử dụng biểu thức</p>\r\n<p class=\"text-center math-tex\">\\[ E=\\frac{U}{d}, \\]</p>\r\n<p>t&iacute;nh được cường độ của điện trường đều giữa hai bản phẳng nhiễm điện đặt song song, x&aacute;c định được lực t&aacute;c dụng l&ecirc;n điện t&iacute;ch đặt trong điện trường đều.</p>', '2026-03-25 08:04:43', '2026-03-25 08:04:43'),
(121, 46, '<p>Thảo luận để m&ocirc; tả được t&aacute;c dụng của điện trường đều l&ecirc;n chuyển động của điện t&iacute;ch bay v&agrave;o điện trường đều theo phương vu&ocirc;ng g&oacute;c với đường sức v&agrave; n&ecirc;u được v&iacute; dụ về ứng dụng của hiện tượng n&agrave;y.</p>', '2026-03-25 08:05:24', '2026-03-25 08:05:24'),
(122, 47, '<p>Thảo luận qua quan s&aacute;t h&igrave;nh ảnh (hoặc t&agrave;i liệu đa phương tiện) n&ecirc;u được điện thế tại một điểm trong điện trường đặc trưng cho điện trường tại điểm đ&oacute; về thế năng, được x&aacute;c định bằng c&ocirc;ng dịch chuyển một đơn vị điện t&iacute;ch dương từ v&ocirc; cực về điểm đ&oacute;; thế năng của một điện t&iacute;ch q trong điện trường đặc trưng cho khả năng sinh c&ocirc;ng của điện trường khi đặt điện t&iacute;ch q tại điểm đang x&eacute;t.</p>', '2026-03-25 08:06:27', '2026-03-25 08:06:27'),
(123, 47, '<p>Vận dụng được mối li&ecirc;n hệ thế năng điện với điện thế: &nbsp;<span class=\"math-tex\">\\[ V=\\frac{A}{q}, \\]</span> v&agrave; mối li&ecirc;n hệ cường độ điện trường với điện thế.</p>', '2026-03-25 08:07:52', '2026-03-25 08:08:47'),
(124, 48, '<p>Định nghĩa được điện dung v&agrave; đơn vị đo điện dung (fara).</p>', '2026-03-25 08:09:40', '2026-03-25 08:09:40'),
(125, 48, '<p>Vận dụng được (kh&ocirc;ng y&ecirc;u cầu thiết lập) c&ocirc;ng thức điện dung của bộ tụ điện gh&eacute;p nối tiếp, gh&eacute;p song song.</p>', '2026-03-25 08:10:02', '2026-03-25 08:10:02'),
(126, 48, '<p>Thảo luận để x&acirc;y dựng được biểu thức t&iacute;nh năng lượng tụ điện.</p>', '2026-03-25 08:10:20', '2026-03-25 08:10:20'),
(127, 48, '<p>Lựa chọn v&agrave; sử dụng th&ocirc;ng tin để x&acirc;y dựng được b&aacute;o c&aacute;o t&igrave;m hiểu một số ứng dụng của tụ điện trong cuộc sống.</p>', '2026-03-25 08:10:44', '2026-03-25 08:10:44'),
(128, 49, '<p>Thực hiện th&iacute; nghiệm (hoặc dựa v&agrave;o t&agrave;i liệu đa phương tiện), n&ecirc;u được cường độ d&ograve;ng điện đặc trưng cho t&aacute;c dụng mạnh yếu của d&ograve;ng điện v&agrave; được x&aacute;c định bằng điện lượng chuyển qua tiết diện &nbsp;thẳng của vật dẫn trong một đơn vị thời gian.</p>', '2026-03-25 14:21:57', '2026-03-25 14:21:57'),
(129, 49, '<p>Vận dụng được biểu thức &nbsp;<span class=\"math-tex\">\\( I=Snve \\)</span>&nbsp; cho d&acirc;y dẫn c&oacute; d&ograve;ng điện, với &nbsp;<span class=\"math-tex\">\\( n \\)</span>&nbsp; l&agrave; mật độ hạt mang điện, &nbsp;<span class=\"math-tex\">\\( S \\)</span>&nbsp; l&agrave; tiết diện thẳng của d&acirc;y, &nbsp;<span class=\"math-tex\">\\( v \\)</span>&nbsp; l&agrave; tốc độ dịch chuyển của hạt mang điện t&iacute;ch &nbsp;<span class=\"math-tex\">\\( e \\)</span>&nbsp;.</p>', '2026-03-25 14:23:36', '2026-03-25 14:23:36'),
(130, 49, '<p>Định nghĩa được đơn vị đo điện lượng coulomb l&agrave; lượng điện t&iacute;ch chuyển qua tiết diện thẳng của d&acirc;y dẫn trong 1 s khi c&oacute; cường độ d&ograve;ng điện 1 A chạy qua d&acirc;y dẫn.</p>', '2026-03-25 14:24:06', '2026-03-25 14:24:06'),
(131, 50, '<p>Định nghĩa được điện trở, đơn vị đo điện trở v&agrave; n&ecirc;u được c&aacute;c nguy&ecirc;n nh&acirc;n ch&iacute;nh g&acirc;y ra điện trở.</p>', '2026-03-25 14:24:33', '2026-03-25 14:24:33'),
(132, 50, '<p>Vẽ ph&aacute;c v&agrave; thảo luận được về đường đặc trưng I &ndash; U của vật dẫn kim loại ở nhiệt độ x&aacute;c định.</p>', '2026-03-25 14:24:52', '2026-03-25 14:24:52'),
(133, 50, '<p>M&ocirc; tả được sơ lược ảnh hưởng của nhiệt độ l&ecirc;n điện trở của đ&egrave;n sợi đốt, điện trở nhiệt (thermistor).</p>', '2026-03-25 14:25:14', '2026-03-25 14:25:14'),
(134, 50, '<p>Ph&aacute;t biểu được định luật Ohm cho vật dẫn kim loại.</p>', '2026-03-25 14:25:31', '2026-03-25 14:25:31'),
(135, 50, '<p>Định nghĩa được suất điện động qua năng lượng dịch chuyển một điện t&iacute;ch đơn vị theo v&ograve;ng k&iacute;n.</p>', '2026-03-25 14:25:51', '2026-03-25 14:25:51'),
(136, 50, '<p>M&ocirc; tả được ảnh hưởng của điện trở trong của nguồn điện l&ecirc;n hiệu điện thế giữa hai cực của nguồn.</p>', '2026-03-25 14:26:11', '2026-03-25 14:26:11'),
(137, 50, '<p>So s&aacute;nh được suất điện động v&agrave; hiệu điện thế.</p>', '2026-03-25 14:26:29', '2026-03-25 14:26:29'),
(138, 50, '<p>Thảo luận để thiết kế phương &aacute;n hoặc lựa chọn phương &aacute;n v&agrave; thực hiện phương &aacute;n, đo được suất điện động v&agrave; điện trở trong của pin hoặc acquy (battery hoặc accumulator) bằng dụng cụ thực h&agrave;nh.</p>', '2026-03-25 14:26:53', '2026-03-25 14:26:53'),
(139, 51, '<p>N&ecirc;u được năng lượng điện ti&ecirc;u thụ của đoạn mạch được đo bằng c&ocirc;ng của lực điện thực hiện khi dịch chuyển c&aacute;c điện t&iacute;ch; c&ocirc;ng suất ti&ecirc;u thụ năng lượng điện của một đoạn mạch l&agrave; năng lượng điện m&agrave; đoạn mạch ti&ecirc;u thụ trong một đơn vị thời gian.</p>', '2026-03-25 14:35:51', '2026-03-25 14:35:51'),
(140, 51, '<p>T&iacute;nh được năng lượng điện v&agrave; c&ocirc;ng suất ti&ecirc;u thụ năng lượng điện của đoạn mạch.</p>', '2026-03-25 14:36:09', '2026-03-25 14:36:09'),
(141, 52, '<p>Sử dụng m&ocirc; h&igrave;nh động học ph&acirc;n tử, n&ecirc;u được sơ lược cấu tr&uacute;c của chất rắn, chất lỏng, chất kh&iacute;.</p>', '2026-03-25 14:40:47', '2026-03-25 14:40:47'),
(142, 52, '<p>Giải th&iacute;ch được sơ lược một số hiện tượng vật l&iacute; li&ecirc;n quan đến sự chuyển thể: sự n&oacute;ng chảy, sự ho&aacute; hơi.</p>', '2026-03-25 14:41:05', '2026-03-25 14:41:05'),
(143, 53, '<p>Thực hiện th&iacute; nghiệm, n&ecirc;u được: mối li&ecirc;n hệ nội năng của vật với năng lượng của c&aacute;c ph&acirc;n tử tạo n&ecirc;n vật, định luật 1 của nhiệt động lực học.</p>', '2026-03-25 14:41:32', '2026-03-25 14:41:32'),
(144, 53, '<p>Vận dụng được định luật 1 của nhiệt động lực học trong một số trường hợp đơn giản.</p>', '2026-03-25 14:41:49', '2026-03-25 14:41:49'),
(145, 54, '<p>Thực hiện th&iacute; nghiệm đơn giản, thảo luận để n&ecirc;u được sự ch&ecirc;nh lệch nhiệt độ giữa hai vật tiếp x&uacute;c nhau c&oacute; thể cho ta biết chiều truyền năng lượng nhiệt giữa ch&uacute;ng; từ đ&oacute; n&ecirc;u được khi hai vật tiếp x&uacute;c với nhau, ở c&ugrave;ng nhiệt độ, sẽ kh&ocirc;ng c&oacute; sự truyền năng lượng nhiệt giữa ch&uacute;ng.</p>', '2026-03-25 14:42:32', '2026-03-25 14:42:32'),
(146, 54, '<p>Thảo luận để n&ecirc;u được mỗi độ chia &nbsp;<span class=\"math-tex\">\\( \\left(1^\\circ \\text{C}\\right) \\)</span> trong thang Celsius bằng 1/100 của khoảng c&aacute;ch giữa nhiệt độ tan chảy của nước tinh khiết đ&oacute;ng băng v&agrave; nhiệt độ s&ocirc;i của nước tinh khiết (ở &aacute;p suất &nbsp;ti&ecirc;u chuẩn), mỗi độ chia (1 K) trong thang Kelvin bằng 1/(273,16) của khoảng c&aacute;ch giữa nhiệt độ kh&ocirc;ng tuyệt đối v&agrave; nhiệt độ điểm m&agrave; nước tinh khiết tồn tại đồng thời ở thể rắn, lỏng v&agrave; hơi (ở &aacute;p suất ti&ecirc;u chuẩn).</p>', '2026-03-25 14:43:18', '2026-03-25 14:44:45'),
(147, 54, '<p>N&ecirc;u được nhiệt độ kh&ocirc;ng tuyệt đối l&agrave; nhiệt độ m&agrave; tại đ&oacute; tất cả c&aacute;c chất c&oacute; động năng chuyển động nhiệt của c&aacute;c ph&acirc;n tử hoặc nguy&ecirc;n tử bằng kh&ocirc;ng v&agrave; thế năng của ch&uacute;ng l&agrave; tối thiểu.</p>', '2026-03-25 14:45:09', '2026-03-25 14:45:09'),
(148, 54, '<p>Chuyển đổi được nhiệt độ đo theo thang Celsius sang nhiệt độ đo theo thang Kelvin v&agrave; ngược lại.</p>', '2026-03-25 14:45:27', '2026-03-25 14:45:27'),
(149, 55, '<p>N&ecirc;u được định nghĩa nhiệt dung ri&ecirc;ng, nhiệt n&oacute;ng chảy ri&ecirc;ng, nhiệt ho&aacute; hơi ri&ecirc;ng.</p>', '2026-03-25 14:46:25', '2026-03-25 14:46:25'),
(150, 55, '<p>Thảo luận để thiết kế phương &aacute;n hoặc lựa chọn phương &aacute;n v&agrave; thực hiện phương &aacute;n, đo được nhiệt dung ri&ecirc;ng, nhiệt n&oacute;ng chảy ri&ecirc;ng, nhiệt ho&aacute; hơi ri&ecirc;ng bằng dụng cụ thực h&agrave;nh.</p>', '2026-03-25 14:46:47', '2026-03-25 14:46:47'),
(151, 56, '<p>Ph&acirc;n t&iacute;ch m&ocirc; h&igrave;nh chuyển động Brown, n&ecirc;u được c&aacute;c ph&acirc;n tử trong chất kh&iacute; chuyển động hỗn loạn.</p>', '2026-03-25 15:00:13', '2026-03-25 15:00:13'),
(152, 56, '<p>Từ c&aacute;c kết quả thực nghiệm hoặc m&ocirc; h&igrave;nh, thảo luận để n&ecirc;u được c&aacute;c giả thuyết của thuyết động học ph&acirc;n tử chất kh&iacute;.</p>', '2026-03-25 15:00:33', '2026-03-25 15:00:33'),
(155, 57, '<p>Thực hiện th&iacute; nghiệm khảo s&aacute;t được định luật Boyle: Khi giữ kh&ocirc;ng đổi nhiệt độ của một khối lượng kh&iacute; x&aacute;c định th&igrave; &aacute;p suất g&acirc;y ra bởi kh&iacute; tỉ lệ nghịch với thể t&iacute;ch của n&oacute;.</p>', '2026-03-25 15:01:59', '2026-03-25 15:01:59'),
(156, 57, '<p>Thực hiện th&iacute; nghiệm minh hoạ được định luật Charles: Khi giữ kh&ocirc;ng đổi &aacute;p suất của một khối lượng kh&iacute; x&aacute;c định th&igrave; thể t&iacute;ch của kh&iacute; tỉ lệ với nhiệt độ tuyệt đối của n&oacute;.</p>', '2026-03-25 15:02:21', '2026-03-25 15:02:21'),
(157, 57, '<p>Sử dụng định luật Boyle v&agrave; định luật Charles r&uacute;t ra được phương tr&igrave;nh trạng th&aacute;i của kh&iacute; l&iacute; tưởng.</p>', '2026-03-25 15:02:38', '2026-03-25 15:02:38'),
(158, 57, '<p>Vận dụng được phương tr&igrave;nh trạng th&aacute;i của kh&iacute; l&iacute; tưởng.</p>', '2026-03-25 15:02:57', '2026-03-25 15:02:57'),
(159, 58, '<p>Giải th&iacute;ch được chuyển động của c&aacute;c ph&acirc;n tử ảnh hưởng như thế n&agrave;o đến &aacute;p suất t&aacute;c dụng l&ecirc;n &nbsp;th&agrave;nh b&igrave;nh v&agrave; từ đ&oacute; r&uacute;t ra được hệ thức</p>\r\n<p class=\"text-center math-tex\">\\[\\frac{1}{3}nm\\overline{v^2}\\]</p>\r\n<p>&nbsp;với n l&agrave; số ph&acirc;n tử trong một đơn vị thể t&iacute;ch (d&ugrave;ng m&ocirc; h&igrave;nh va chạm một chiều đơn giản, rồi mở rộng ra cho trường hợp ba chiều bằng c&aacute;ch sử dụng hệ thức</p>\r\n<p class=\"text-center math-tex\">\\[\\frac{1}{3}\\overline{v^2}=\\overline{v_x^2}\\]</p>\r\n<p>kh&ocirc;ng y&ecirc;u cầu chứng minh một c&aacute;ch ch&iacute;nh x&aacute;c v&agrave; chi tiết).</p>', '2026-03-25 17:01:25', '2026-03-27 11:03:14'),
(160, 59, '<p>N&ecirc;u được biểu thức hằng số Boltzmann, &nbsp;<span class=\"math-tex\">\\( k=\\frac{R}{N_A} \\).</span></p>', '2026-03-25 17:03:10', '2026-03-25 17:03:24'),
(161, 59, '<p>So s&aacute;nh</p>\r\n<p class=\"text-center math-tex\">\\[ pV=\\frac{1}{3}Nm\\overline{v^2} \\]</p>\r\n<p>với</p>\r\n<p class=\"text-center math-tex\">\\[ pV=nRT \\]</p>\r\n<p>r&uacute;t ra được động năng tịnh tiến trung b&igrave;nh của ph&acirc;n tử tỉ lệ&nbsp;với nhiệt độ T.</p>', '2026-03-25 17:05:49', '2026-03-25 17:05:49'),
(162, 60, '<p>Thực hiện th&iacute; nghiệm tạo ra được c&aacute;c đường sức từ bằng c&aacute;c dụng cụ đơn giản.</p>', '2026-03-25 17:08:39', '2026-03-25 17:08:39'),
(163, 60, '<p>N&ecirc;u được từ trường l&agrave; trường lực g&acirc;y ra bởi d&ograve;ng điện hoặc nam ch&acirc;m, l&agrave; một dạng của vật chất tồn tại xung quanh d&ograve;ng điện hoặc nam ch&acirc;m m&agrave; biểu hiện cụ thể l&agrave; sự xuất hiện của lực từ t&aacute;c dụng l&ecirc;n một d&ograve;ng điện hay một nam ch&acirc;m đặt trong đ&oacute;.</p>', '2026-03-25 17:09:01', '2026-03-25 17:09:01'),
(164, 61, '<p>Thực hiện th&iacute; nghiệm để m&ocirc; tả được hướng của lực từ t&aacute;c dụng l&ecirc;n đoạn d&acirc;y dẫn mang d&ograve;ng điện đặt trong từ trường.</p>', '2026-03-25 17:09:35', '2026-03-25 17:09:35'),
(165, 61, '<p>X&aacute;c định được độ lớn v&agrave; hướng của lực từ t&aacute;c dụng l&ecirc;n đoạn d&acirc;y dẫn mang d&ograve;ng điện đặt trong từ trường.</p>', '2026-03-25 17:09:55', '2026-03-25 17:09:55'),
(166, 61, '<p>Định nghĩa được cảm ứng từ B v&agrave; đơn vị tesla.</p>', '2026-03-25 17:10:14', '2026-03-25 17:10:14'),
(167, 61, '<p>N&ecirc;u được đơn vị cơ bản v&agrave; dẫn xuất để đo c&aacute;c đại lượng từ.</p>', '2026-03-25 17:10:28', '2026-03-25 17:10:28'),
(168, 61, '<p>Thảo luận để thiết kế phương &aacute;n, lựa chọn phương &aacute;n, thực hiện phương &aacute;n, đo được (hoặc m&ocirc; tả được phương ph&aacute;p đo) cảm ứng từ bằng c&acirc;n &ldquo;d&ograve;ng điện&rdquo;.</p>', '2026-03-25 17:10:52', '2026-03-25 17:10:52'),
(169, 61, '<p>Vận dụng được biểu thức t&iacute;nh lực F = BILsin&theta;.</p>', '2026-03-25 17:11:24', '2026-03-25 17:11:24'),
(170, 62, '<p>Định nghĩa được từ th&ocirc;ng v&agrave; đơn vị weber.</p>', '2026-03-25 17:11:58', '2026-03-25 17:11:58'),
(171, 62, '<p>Tiến h&agrave;nh c&aacute;c th&iacute; nghiệm đơn giản minh hoạ được hiện tượng cảm ứng điện từ.</p>', '2026-03-25 17:12:12', '2026-03-25 17:12:12'),
(172, 62, '<p>Vận dụng được định luật Faraday v&agrave; định luật Lenz về cảm ứng điện từ.</p>', '2026-03-25 17:12:27', '2026-03-25 17:12:27'),
(173, 62, '<p>Giải th&iacute;ch được một số ứng dụng đơn giản của hiện tượng cảm ứng điện từ.</p>', '2026-03-25 17:12:43', '2026-03-25 17:12:43'),
(174, 62, '<p>M&ocirc; tả được m&ocirc; h&igrave;nh s&oacute;ng điện từ v&agrave; ứng dụng để giải th&iacute;ch sự tạo th&agrave;nh v&agrave; lan truyền của c&aacute;c s&oacute;ng điện từ trong thang s&oacute;ng điện từ.</p>', '2026-03-25 17:13:04', '2026-03-25 17:13:04'),
(175, 62, '<p>Thảo luận để thiết kế phương &aacute;n (hoặc m&ocirc; tả được phương ph&aacute;p) tạo ra d&ograve;ng điện xoay chiều.</p>', '2026-03-25 17:13:21', '2026-03-25 17:13:21'),
(176, 62, '<p>N&ecirc;u được: chu k&igrave;, tần số, gi&aacute; trị cực đại, gi&aacute; trị hiệu dụng của cường độ d&ograve;ng điện v&agrave; điện &aacute;p xoay chiều.</p>', '2026-03-25 17:13:41', '2026-03-25 17:13:41'),
(177, 62, '<p>Thảo luận để n&ecirc;u được một số ứng dụng của d&ograve;ng điện xoay chiều trong cuộc sống, tầm quan trọng của việc tu&acirc;n thủ quy tắc an to&agrave;n khi sử dụng d&ograve;ng điện xoay chiều trong cuộc sống.</p>', '2026-03-25 17:14:07', '2026-03-25 17:14:07'),
(178, 63, '<p>R&uacute;t ra được sự tồn tại v&agrave; đ&aacute;nh gi&aacute; được k&iacute;ch thước của hạt nh&acirc;n từ ph&acirc;n t&iacute;ch kết quả th&iacute; nghiệm t&aacute;n xạ hạt &alpha;.</p>', '2026-03-25 17:17:38', '2026-03-25 17:17:38'),
(179, 63, '<p>Biểu diễn được k&iacute; hiệu hạt nh&acirc;n của nguy&ecirc;n tử bằng số nucleon v&agrave; số proton.</p>', '2026-03-25 17:17:52', '2026-03-25 17:17:52'),
(180, 63, '<p>M&ocirc; tả được m&ocirc; h&igrave;nh đơn giản của nguy&ecirc;n tử gồm proton, neutron v&agrave; electron.</p>', '2026-03-25 17:18:07', '2026-03-25 17:18:07'),
(181, 64, '<p>Viết được đ&uacute;ng phương tr&igrave;nh ph&acirc;n r&atilde; hạt nh&acirc;n đơn giản.</p>', '2026-03-25 17:18:29', '2026-03-25 17:18:29'),
(182, 64, '<p>Thảo luận hệ thức &nbsp;<span class=\"math-tex\">\\( E=mc^2 \\)</span> , n&ecirc;u được li&ecirc;n hệ giữa khối lượng v&agrave; năng lượng.</p>', '2026-03-25 17:19:11', '2026-03-25 17:19:11'),
(183, 64, '<p>N&ecirc;u được mối li&ecirc;n hệ giữa năng lượng li&ecirc;n kết ri&ecirc;ng v&agrave; độ bền vững của hạt nh&acirc;n.</p>', '2026-03-25 17:19:29', '2026-03-25 17:19:29'),
(184, 64, '<p>N&ecirc;u được sự ph&acirc;n hạch v&agrave; sự tổng hợp hạt nh&acirc;n.</p>', '2026-03-25 17:19:59', '2026-03-25 17:19:59'),
(185, 64, '<p>Thảo luận để đ&aacute;nh gi&aacute; được vai tr&ograve; của một số ng&agrave;nh c&ocirc;ng nghiệp hạt nh&acirc;n trong đời sống.</p>', '2026-03-25 17:20:22', '2026-03-25 17:20:22'),
(190, 65, '<p>N&ecirc;u được bản chất tự ph&aacute;t v&agrave; ngẫu nhi&ecirc;n của sự ph&acirc;n r&atilde; ph&oacute;ng xạ.</p>', '2026-03-25 17:26:48', '2026-03-25 17:26:48'),
(191, 65, '<p>Định nghĩa được độ ph&oacute;ng xạ, hằng số ph&oacute;ng xạ v&agrave; vận dụng được li&ecirc;n hệ H = &lambda;N.</p>', '2026-03-25 17:27:02', '2026-03-25 17:27:02'),
(192, 65, '<p>Vận dụng được c&ocirc;ng thức &nbsp;<span class=\"math-tex\">\\( x=x_0e^{-\\lambda t} \\)</span>, với x l&agrave; độ ph&oacute;ng xạ, số hạt chưa ph&acirc;n r&atilde; hoặc tốc độ số hạt đếm được.</p>', '2026-03-25 17:28:08', '2026-03-25 17:28:08'),
(193, 65, '<p>Định nghĩa được chu k&igrave; b&aacute;n r&atilde;.</p>', '2026-03-25 17:28:25', '2026-03-25 17:28:25'),
(194, 65, '<p>M&ocirc; tả được sơ lược một số t&iacute;nh chất của c&aacute;c ph&oacute;ng xạ &alpha;, &beta; v&agrave; &gamma;.</p>', '2026-03-25 17:28:42', '2026-03-25 17:28:42'),
(195, 65, '<p>Nhận biết được dấu hiệu vị tr&iacute; c&oacute; ph&oacute;ng xạ th&ocirc;ng qua c&aacute;c biển b&aacute;o.</p>', '2026-03-25 17:29:01', '2026-03-25 17:29:01'),
(196, 65, '<p>N&ecirc;u được c&aacute;c nguy&ecirc;n tắc an to&agrave;n ph&oacute;ng xạ; tu&acirc;n thủ quy tắc an to&agrave;n ph&oacute;ng xạ.</p>', '2026-03-25 17:29:21', '2026-03-25 17:29:21'),
(197, 3, '<p class=\"p1\">Thảo luận, đề xuất, chọn phương &aacute;n v&agrave; thực hiện được Nhiệm vụ học tập để n&ecirc;u được sơ lược sự ra đời v&agrave; những th&agrave;nh tựu ban đầu của vật l&iacute; thực nghiệm.</p>', '2026-03-26 11:01:48', '2026-03-26 11:01:48'),
(198, 3, '<p>Thảo luận, đề xuất, chọn phương &aacute;n v&agrave; thực hiện được Nhiệm vụ học tập để n&ecirc;u được sơ lược vai tr&ograve; của cơ học Newton đối với sự ph&aacute;t triển của Vật l&iacute; học.</p>', '2026-03-26 11:02:34', '2026-03-26 11:02:34'),
(199, 3, '<p>Thảo luận, đề xuất, chọn phương &aacute;n v&agrave; thực hiện được Nhiệm vụ học tập để liệt k&ecirc; được một số nh&aacute;nh nghi&ecirc;n cứu ch&iacute;nh của vật l&iacute; cổ điển.</p>', '2026-03-26 11:03:08', '2026-03-26 11:03:08'),
(200, 3, '<p>Thảo luận, đề xuất, chọn phương &aacute;n v&agrave; thực hiện được Nhiệm vụ học tập để n&ecirc;u được sự khủng hoảng của vật l&iacute; cuối thế kỉ XIX, tiền đề cho sự ra đời của vật l&iacute; hiện đại.</p>', '2026-03-26 11:03:37', '2026-03-26 11:03:37'),
(201, 3, '<p>Thảo luận, đề xuất, chọn phương &aacute;n v&agrave; thực hiện được Nhiệm vụ học tập để&nbsp; liệt k&ecirc; được một số lĩnh vực ch&iacute;nh của vật l&iacute; hiện đại.</p>', '2026-03-26 11:04:02', '2026-03-26 11:04:02'),
(202, 4, '<p class=\"p1\">N&ecirc;u được đối tượng nghi&ecirc;n cứu; liệt k&ecirc; được một v&agrave;i m&ocirc; h&igrave;nh l&iacute; thuyết đơn giản, một số phương ph&aacute;p thực nghiệm của một số lĩnh vực ch&iacute;nh của vật l&iacute; hiện đại.</p>', '2026-03-26 11:06:01', '2026-03-26 11:06:01'),
(203, 4, '<p class=\"p1\">Thảo luận, đề xuất, chọn phương &aacute;n v&agrave; thực hiện được Nhiệm vụ học tập t&igrave;m hiểu về c&aacute;c m&ocirc; h&igrave;nh, l&iacute; thuyết khoa học đ&atilde; ph&aacute;t triển v&agrave; được &aacute;p dụng để cải thiện c&aacute;c c&ocirc;ng nghệ hiện tại cũng như ph&aacute;t triển c&aacute;c c&ocirc;ng nghệ mới.</p>', '2026-03-26 11:06:25', '2026-03-26 11:06:25'),
(204, 32, '<p class=\"p1\">M&ocirc; tả được v&iacute; dụ thực tế về việc sử dụng kiến thức vật l&iacute; trong một số lĩnh vực (Qu&acirc;n sự; C&ocirc;ng nghiệp hạt nh&acirc;n; Kh&iacute; tượng; N&ocirc;ng nghiệp, L&acirc;m nghiệp; T&agrave;i ch&iacute;nh; Điện tử; Cơ kh&iacute;, tự động ho&aacute;; Th&ocirc;ng tin, truyền th&ocirc;ng; Nghi&ecirc;n cứu khoa học).</p>', '2026-03-26 11:07:23', '2026-03-26 11:07:23'),
(205, 33, '<p class=\"p1\">X&aacute;c định được tr&ecirc;n bản đồ sao (hoặc bằng dụng cụ thực h&agrave;nh) vị tr&iacute; của c&aacute;c ch&ograve;m sao: Gấu lớn, Gấu nhỏ, Thi&ecirc;n Hậu.</p>', '2026-03-26 11:09:20', '2026-03-26 11:09:20'),
(206, 33, '<p class=\"p1\">X&aacute;c định được vị tr&iacute; sao Bắc Cực tr&ecirc;n nền trời sao.</p>', '2026-03-26 11:09:36', '2026-03-26 11:09:36'),
(207, 34, '<p class=\"p1\">Sử dụng m&ocirc; h&igrave;nh hệ Mặt Trời, thảo luận để n&ecirc;u được một số đặc điểm cơ bản của chuyển động nh&igrave;n thấy của Mặt Trời, Mặt Trăng, Kim Tinh v&agrave; Thuỷ Tinh tr&ecirc;n nền trời sao.</p>', '2026-03-26 11:11:05', '2026-03-26 11:11:05'),
(208, 34, '<p class=\"p1\">D&ugrave;ng m&ocirc; h&igrave;nh nhật t&acirc;m của Copernic giải th&iacute;ch được một số đặc điểm quan s&aacute;t được của Mặt Trời, Mặt Trăng, Kim Tinh v&agrave; Thuỷ Tinh tr&ecirc;n nền trời sao.</p>', '2026-03-26 11:11:24', '2026-03-26 11:11:24'),
(209, 35, '<p class=\"p1\">D&ugrave;ng ảnh (hoặc t&agrave;i liệu đa phương tiện), thảo luận để giải th&iacute;ch được một c&aacute;ch sơ lược v&agrave; định t&iacute;nh c&aacute;c hiện tượng: nhật thực, nguyệt thực, thuỷ triều.</p>', '2026-03-26 11:11:53', '2026-03-26 11:11:53'),
(210, 36, '<p class=\"p1\">Thảo luận, đề xuất, chọn phương &aacute;n v&agrave; thực hiện được Nhiệm vụ học tập t&igrave;m hiểu sự cần thiết bảo vệ m&ocirc;i trường trong chiến lược ph&aacute;t triển của c&aacute;c quốc gia.</p>', '2026-03-26 11:13:22', '2026-03-26 11:13:22'),
(211, 36, '<p>Thảo luận, đề xuất, chọn phương &aacute;n v&agrave; thực hiện được Nhiệm vụ học tập t&igrave;m hiểu vai tr&ograve; của c&aacute; nh&acirc;n v&agrave; cộng đồng trong bảo vệ m&ocirc;i trường.</p>', '2026-03-26 11:13:48', '2026-03-26 11:13:48'),
(212, 37, '<p class=\"p1\">Thảo luận, đề xuất, chọn phương &aacute;n v&agrave; thực hiện được Nhiệm vụ học tập t&igrave;m hiểu t&aacute;c động của việc sử dụng năng lượng hiện nay đối với m&ocirc;i trường, kinh tế v&agrave; kh&iacute; hậu Việt Nam.</p>', '2026-03-26 11:14:52', '2026-03-26 11:14:52'),
(213, 37, '<p>Thảo luận, đề xuất, chọn phương &aacute;n v&agrave; thực hiện được Nhiệm vụ học tập t&igrave;m hiểu sơ lược về c&aacute;c chất &ocirc; nhiễm trong nhi&ecirc;n liệu ho&aacute; thạch, mưa axit, năng lượng hạt nh&acirc;n, sự suy giảm tầng ozon, sự biến đổi kh&iacute; hậu.</p>', '2026-03-26 11:15:24', '2026-03-26 11:15:24'),
(214, 37, '<p class=\"p1\">Thảo luận, đề xuất, chọn phương &aacute;n v&agrave; thực hiện được Nhiệm vụ học tập t&igrave;m hiểu ph&acirc;n loại năng lượng ho&aacute; thạch v&agrave; năng lượng t&aacute;i tạo.</p>', '2026-03-26 11:16:26', '2026-03-26 11:16:26'),
(215, 37, '<p>Thảo luận, đề xuất, chọn phương &aacute;n v&agrave; thực hiện được Nhiệm vụ học tập t&igrave;m hiểu vai tr&ograve; của năng lượng t&aacute;i tạo.</p>', '2026-03-26 11:16:53', '2026-03-26 11:16:53'),
(216, 37, '<p>Thảo luận, đề xuất, chọn phương &aacute;n v&agrave; thực hiện được Nhiệm vụ học tập t&igrave;m hiểu&nbsp;một số c&ocirc;ng nghệ cơ bản để thu được năng lượng t&aacute;i tạo.</p>', '2026-03-26 11:17:15', '2026-03-26 11:17:15'),
(217, 66, '<p class=\"p1\">N&ecirc;u được v&iacute; dụ chứng tỏ tồn tại lực hấp dẫn của Tr&aacute;i Đất.</p>', '2026-03-26 14:36:12', '2026-03-26 14:36:12'),
(218, 66, '<p class=\"p1\">Thảo luận (qua h&igrave;nh vẽ, t&agrave;i liệu đa phương tiện), n&ecirc;u được: Mọi vật c&oacute; khối lượng đều tạo ra một trường hấp dẫn xung quanh n&oacute;; Trường hấp dẫn l&agrave; trường lực được tạo ra bởi vật c&oacute; khối lượng, l&agrave; dạng vật chất tồn tại quanh một vật c&oacute; khối lượng v&agrave; t&aacute;c dụng lực hấp dẫn l&ecirc;n vật c&oacute; khối lượng đặt trong n&oacute;.</p>', '2026-03-26 14:36:39', '2026-03-26 14:36:39'),
(219, 67, '<p class=\"p1\">N&ecirc;u được: Khi x&eacute;t trường hấp dẫn ở một điểm ngo&agrave;i quả cầu đồng nhất, khối lượng của quả cầu c&oacute; thể xem như tập trung ở t&acirc;m của n&oacute;.</p>', '2026-03-26 14:37:12', '2026-03-26 14:37:12'),
(220, 67, '<p class=\"p1\">Vận dụng được định luật Newton về hấp dẫn&nbsp;</p>\r\n<p class=\"text-center math-tex\">\\[ F=\\frac{Gm_1m_2}{r^2} \\]</p>\r\n<p class=\"p1\">cho một số trường hợp chuyển động đơn giản trong trường hấp dẫn.</p>', '2026-03-26 14:38:26', '2026-03-26 14:38:26'),
(221, 68, '<p class=\"p1\">N&ecirc;u được định nghĩa cường độ trường hấp dẫn.</p>', '2026-03-26 14:39:01', '2026-03-26 14:39:01'),
(222, 68, '<p class=\"p1\">Từ định luật hấp dẫn v&agrave; định nghĩa cường độ trường hấp dẫn, r&uacute;t ra được phương tr&igrave;nh &nbsp;<span class=\"math-tex\">\\[ g=\\frac{GM}{r^2} \\]</span> cho trường hợp đơn giản.</p>', '2026-03-26 14:40:13', '2026-03-26 14:40:42'),
(223, 68, '<p class=\"p1\">Vận dụng được phương tr&igrave;nh&nbsp;</p>\r\n<p class=\"text-center math-tex\">\\[ g=\\frac{GM}{r^2} \\]</p>\r\n<p class=\"p1\">để đ&aacute;nh gi&aacute; một số hiện tượng đơn giản về trường hấp dẫn.</p>', '2026-03-26 21:44:09', '2026-03-26 21:44:09'),
(224, 68, '<p class=\"p1\">N&ecirc;u được tại mỗi vị tr&iacute; ở gần bề mặt của Tr&aacute;i Đất, trong một phạm vi độ cao kh&ocirc;ng lớn lắm, g l&agrave; hằng số.</p>', '2026-03-26 21:44:51', '2026-03-26 21:44:51'),
(225, 69, '<p class=\"p1\">Thảo luận (qua h&igrave;nh ảnh, t&agrave;i liệu đa phương tiện) để n&ecirc;u được định nghĩa thế hấp dẫn tại một điểm trong trường hấp dẫn.</p>', '2026-03-26 21:46:30', '2026-03-26 21:46:30'),
(226, 69, '<p class=\"p1\">Vận dụng được phương tr&igrave;nh&nbsp;</p>\r\n<p class=\"text-center math-tex\">\\[ \\varphi=\\frac{GM}{r} \\]</p>\r\n<p class=\"p1\">trong trường hợp đơn giản.</p>', '2026-03-26 21:47:31', '2026-03-26 21:47:31'),
(227, 69, '<p class=\"p1\">Giải th&iacute;ch được sơ lược chuyển động của vệ tinh địa tĩnh, r&uacute;t ra được c&ocirc;ng thức t&iacute;nh tốc độ vũ trụ cấp 1.</p>', '2026-03-26 21:48:13', '2026-03-26 21:48:13'),
(228, 70, '<p class=\"p1\">So s&aacute;nh được biến điệu bi&ecirc;n độ (AM) v&agrave; biến điệu tần số (FM).</p>', '2026-03-26 21:50:56', '2026-03-26 21:50:56'),
(229, 70, '<p class=\"p1\">Liệt k&ecirc; được tần số v&agrave; bước s&oacute;ng được sử dụng trong c&aacute;c k&ecirc;nh truyền th&ocirc;ng kh&aacute;c nhau.</p>', '2026-03-26 21:51:13', '2026-03-26 21:51:13'),
(230, 70, '<p class=\"p1\">Thảo luận để r&uacute;t ra được ưu, nhược điểm tương đối của k&ecirc;nh AM v&agrave; k&ecirc;nh FM.</p>', '2026-03-26 21:51:29', '2026-03-26 21:51:29'),
(231, 71, '<p class=\"p1\">M&ocirc; tả được c&aacute;c ưu điểm của việc truyền dữ liệu dưới dạng số so với việc truyền dữ liệu dưới dạng tương tự.</p>', '2026-03-26 21:51:58', '2026-03-26 21:51:58'),
(232, 71, '<p class=\"p1\">Thảo luận để r&uacute;t ra được: sự truyền giọng n&oacute;i hoặc &acirc;m nhạc li&ecirc;n quan đến chuyển đổi tương tự &ndash; số (ADC) trước khi truyền v&agrave; chuyển đổi số &ndash; tương tự (DAC) khi nhận.</p>', '2026-03-26 21:52:23', '2026-03-26 21:52:23'),
(233, 71, '<p class=\"p1\">M&ocirc; tả được sơ lược hệ thống truyền kĩ thuật số về chuyển đổi tương tự &ndash; số v&agrave; số &ndash; tương tự.</p>', '2026-03-26 21:52:39', '2026-03-26 21:52:39'),
(234, 72, '<p class=\"p1\">Thảo luận được ảnh hưởng của sự suy giảm t&iacute;n hiệu đến chất lượng t&iacute;n hiệu được truyền; n&ecirc;u được độ suy giảm t&iacute;n hiệu t&iacute;nh theo dB v&agrave; t&iacute;nh theo dB tr&ecirc;n một đơn vị độ d&agrave;i.</p>', '2026-03-26 21:53:09', '2026-03-26 21:53:09'),
(235, 73, '<p class=\"p1\">Thảo luận, đề xuất, chọn phương &aacute;n v&agrave; thực hiện được Dự &aacute;n t&igrave;m hiểu ph&acirc;n loại cảm biến (sensor) theo: nguy&ecirc;n tắc hoạt động, phạm vi sử dụng, hiệu quả kinh tế.</p>', '2026-03-26 21:56:24', '2026-03-26 21:56:24');
INSERT INTO `objectives` (`id`, `content_id`, `description`, `created_at`, `updated_at`) VALUES
(236, 73, '<p>Thảo luận, đề xuất, chọn phương &aacute;n v&agrave; thực hiện được Dự &aacute;n t&igrave;m hiểu&nbsp;nguy&ecirc;n tắc hoạt động của: điện trở phụ thuộc &aacute;nh s&aacute;ng (LDR), điện trở nhiệt.</p>', '2026-03-26 21:57:11', '2026-03-26 21:57:11'),
(237, 73, '<p>Thảo luận, đề xuất, chọn phương &aacute;n v&agrave; thực hiện được Dự &aacute;n t&igrave;m hiểu nguy&ecirc;n tắc hoạt động của sensor sử dụng: điện trở phụ thuộc &aacute;nh s&aacute;ng (LDR), điện trở nhiệt.</p>', '2026-03-26 21:57:48', '2026-03-26 21:57:48'),
(238, 73, '<p>Thảo luận, đề xuất, chọn phương &aacute;n v&agrave; thực hiện được Dự &aacute;n t&igrave;m hiểu&nbsp;t&iacute;nh chất cơ bản của bộ khuếch đại thuật to&aacute;n (op-amp) l&iacute; tưởng.</p>', '2026-03-26 21:58:15', '2026-03-26 21:58:15'),
(239, 74, '<p class=\"p1\">Thảo luận, đề xuất, chọn phương &aacute;n v&agrave; thực hiện được Dự &aacute;n t&igrave;m hiểu ba thiết bị đầu ra Nguy&ecirc;n tắc hoạt động của mạch op-amp &ndash; relays.</p>', '2026-03-26 21:59:16', '2026-03-26 21:59:16'),
(240, 74, '<p>Thảo luận, đề xuất, chọn phương &aacute;n v&agrave; thực hiện được Dự &aacute;n t&igrave;m hiểu ba thiết bị đầu ra nguy&ecirc;n tắc hoạt động của mạch op-amp &ndash; LEDs (light-emitting diode).</p>', '2026-03-26 21:59:52', '2026-03-26 21:59:52'),
(241, 74, '<p>Thảo luận, đề xuất, chọn phương &aacute;n v&agrave; thực hiện được Dự &aacute;n t&igrave;m hiểu ba thiết bị đầu ra nguy&ecirc;n tắc hoạt động của mạch op-amp &ndash; CMs (calibrated meter).</p>', '2026-03-26 22:00:19', '2026-03-26 22:00:19'),
(242, 74, '<p>Thảo luận, đề xuất, chọn phương &aacute;n v&agrave; thực hiện được Dự &aacute;n t&igrave;m hiểu ba thiết bị đầu ra thiết kế được một số mạch điện ứng dụng đơn giản c&oacute; sử dụng thiết bị đầu ra.</p>', '2026-03-26 22:00:43', '2026-03-26 22:00:43'),
(243, 75, '<p class=\"p1\">Tham quan thực tế (hoặc qua t&agrave;i liệu đa phương tiện), thảo luận để n&ecirc;u được một số ứng dụng ch&iacute;nh của thiết bị cảm biến v&agrave; nguy&ecirc;n tắc hoạt động của thiết bị cảm biến.</p>', '2026-03-26 22:01:24', '2026-03-26 22:01:24'),
(244, 76, '<p>Thảo luận để thiết kế phương &aacute;n, chọn phương &aacute;n, thực hiện phương &aacute;n, đo được (hoặc m&ocirc; tả được phương ph&aacute;p đo): tần số, điện &aacute;p xoay chiều bằng dụng cụ thực h&agrave;nh.</p>', '2026-03-27 00:28:57', '2026-03-27 00:28:57'),
(245, 76, '<p>N&ecirc;u được: c&ocirc;ng suất toả nhiệt trung b&igrave;nh tr&ecirc;n điện trở thuần bằng một nửa c&ocirc;ng suất cực đại của d&ograve;ng điện xoay chiều h&igrave;nh sin (chạy qua điện trở thuần n&agrave;y).</p>', '2026-03-27 00:29:13', '2026-03-27 00:29:13'),
(246, 76, '<p>M&ocirc; tả được bằng biểu thức đại số hoặc đồ thị: cường độ d&ograve;ng điện, điện &aacute;p xoay chiều; so s&aacute;nh được gi&aacute; trị hiệu dụng v&agrave; gi&aacute; trị cực đại.</p>', '2026-03-27 00:29:27', '2026-03-27 00:29:27'),
(247, 76, '<p>Thảo luận để thiết kế phương &aacute;n hoặc lựa chọn phương &aacute;n v&agrave; thực hiện phương &aacute;n, khảo s&aacute;t được đoạn mạch xoay chiều RLC mắc nối tiếp bằng dụng cụ thực h&agrave;nh.</p>', '2026-03-27 00:31:01', '2026-03-27 00:31:01'),
(248, 77, '<p>N&ecirc;u được nguy&ecirc;n tắc hoạt động của m&aacute;y biến &aacute;p.</p>', '2026-03-27 00:31:20', '2026-03-27 00:31:20'),
(249, 77, '<p>N&ecirc;u được ưu điểm của d&ograve;ng điện v&agrave; điện &aacute;p xoay chiều trong truyền tải năng lượng điện về phương diện khoa học v&agrave; kinh tế.</p>', '2026-03-27 00:31:34', '2026-03-27 00:31:34'),
(250, 77, '<p>Thảo luận để đ&aacute;nh gi&aacute; được vai tr&ograve; của m&aacute;y biến &aacute;p trong việc giảm hao ph&iacute; năng lượng điện khi truyền d&ograve;ng điện đi xa.</p>', '2026-03-27 00:31:46', '2026-03-27 00:31:46'),
(251, 78, '<p>Thực hiện th&iacute; nghiệm, vẽ được đồ thị biểu diễn quan hệ giữa d&ograve;ng điện chạy qua diode b&aacute;n dẫn v&agrave; điện &aacute;p giữa hai cực của n&oacute;.</p>', '2026-03-27 00:32:12', '2026-03-27 00:32:12'),
(252, 78, '<p>Vẽ được mạch chỉnh lưu nửa chu k&igrave; sử dụng diode.</p>', '2026-03-27 00:32:23', '2026-03-27 00:32:23'),
(253, 78, '<p>Vẽ được mạch chỉnh lưu cả chu k&igrave; sử dụng cầu chỉnh lưu.</p>', '2026-03-27 00:32:32', '2026-03-27 00:32:32'),
(254, 78, '<p>So s&aacute;nh được đồ thị chỉnh lưu nửa chu k&igrave; v&agrave; chỉnh lưu cả chu k&igrave;.</p>', '2026-03-27 00:32:43', '2026-03-27 00:32:43'),
(255, 79, '<p>N&ecirc;u được c&aacute;ch tạo ra tia X, c&aacute;ch điều khiển tia X, sự suy giảm tia X.</p>', '2026-03-27 00:40:20', '2026-03-27 00:40:20'),
(256, 79, '<p>Thảo luận để đ&aacute;nh gi&aacute; được vai tr&ograve; của tia X trong đời sống v&agrave; trong khoa học.</p>', '2026-03-27 00:40:33', '2026-03-27 00:40:33'),
(257, 80, '<p>M&ocirc; tả được sơ lược c&aacute;ch chụp ảnh bằng tia X.</p>', '2026-03-27 00:40:54', '2026-03-27 00:40:54'),
(258, 80, '<p>Từ tranh ảnh (t&agrave;i liệu đa phương tiện) thảo luận để r&uacute;t ra được một số c&aacute;ch cải thiện ảnh chụp bằng tia X: giảm liều chiếu, cải thiện độ sắc n&eacute;t, cải thiện độ tương phản.</p>', '2026-03-27 00:41:07', '2026-03-27 00:41:07'),
(259, 81, '<p>N&ecirc;u được sơ lược c&aacute;ch tạo si&ecirc;u &acirc;m.</p>', '2026-03-27 00:41:28', '2026-03-27 00:41:28'),
(260, 81, '<p>N&ecirc;u được sơ lược c&aacute;ch tạo ra h&igrave;nh ảnh si&ecirc;u &acirc;m c&aacute;c cấu tr&uacute;c b&ecirc;n trong cơ thể.</p>', '2026-03-27 00:41:40', '2026-03-27 00:41:40'),
(261, 81, '<p>Từ tranh ảnh (t&agrave;i liệu đa phương tiện) thảo luận để đ&aacute;nh gi&aacute; được vai tr&ograve; của si&ecirc;u &acirc;m trong đời sống v&agrave; trong khoa học.&nbsp;</p>', '2026-03-27 00:42:18', '2026-03-27 00:42:18'),
(262, 82, '<p>M&ocirc; tả được sơ lược c&aacute;ch chụp ảnh cắt lớp.</p>', '2026-03-27 00:42:37', '2026-03-27 00:42:37'),
(263, 82, '<p>Thực hiện dự &aacute;n hay đề t&agrave;i nghi&ecirc;n cứu, thiết kế được một m&ocirc; h&igrave;nh chụp cắt lớp đơn giản.</p>', '2026-03-27 00:42:47', '2026-03-27 00:42:47'),
(264, 82, '<p>N&ecirc;u được sơ lược nguy&ecirc;n l&iacute; chụp cộng hưởng từ.</p>', '2026-03-27 00:43:01', '2026-03-27 00:43:01'),
(265, 83, '<p>N&ecirc;u được t&iacute;nh lượng tử của bức xạ điện từ, năng lượng photon.</p>', '2026-03-27 00:43:29', '2026-03-27 00:43:29'),
(266, 83, '<p>Vận dụng được c&ocirc;ng thức t&iacute;nh năng lượng photon, &nbsp;<span class=\"math-tex\">\\( E=hf \\)</span>.</p>', '2026-03-27 00:44:34', '2026-03-27 00:44:34'),
(267, 83, '<p>N&ecirc;u được hiệu ứng quang điện l&agrave; bằng chứng cho t&iacute;nh chất hạt của bức xạ điện từ, giao thoa v&agrave; nhiễu xạ l&agrave; bằng chứng cho t&iacute;nh chất s&oacute;ng của bức xạ điện từ.</p>', '2026-03-27 00:44:51', '2026-03-27 00:44:51'),
(268, 83, '<p>M&ocirc; tả được kh&aacute;i niệm giới hạn quang điện, c&ocirc;ng tho&aacute;t.</p>', '2026-03-27 00:45:57', '2026-03-27 00:45:57'),
(269, 83, '<p>Giải th&iacute;ch được hiệu ứng quang điện dựa tr&ecirc;n năng lượng photon v&agrave; c&ocirc;ng tho&aacute;t.</p>', '2026-03-27 00:46:13', '2026-03-27 00:46:13'),
(270, 83, '<p>Giải th&iacute;ch được: động năng ban đầu cực đại của quang điện tử kh&ocirc;ng phụ thuộc cường độ ch&ugrave;m s&aacute;ng, cường độ d&ograve;ng quang điện b&atilde;o ho&agrave; tỉ lệ với cường độ ch&ugrave;m s&aacute;ng chiếu v&agrave;o.</p>', '2026-03-27 00:46:35', '2026-03-27 00:46:35'),
(271, 83, '<p>Vận dụng được phương tr&igrave;nh Einstein để giải th&iacute;ch c&aacute;c định luật quang điện.</p>', '2026-03-27 00:46:47', '2026-03-27 00:46:47'),
(272, 83, '<p>Ước lượng được năng lượng của c&aacute;c bức xạ điện từ cơ bản trong thang s&oacute;ng điện từ.</p>', '2026-03-27 00:47:00', '2026-03-27 00:47:00'),
(273, 83, '<p>Thảo luận để thiết kế phương &aacute;n hoặc lựa chọn phương &aacute;n v&agrave; thực hiện phương &aacute;n, khảo s&aacute;t được<br>d&ograve;ng quang điện bằng dụng cụ thực h&agrave;nh.</p>', '2026-03-27 00:47:12', '2026-03-27 00:47:12'),
(274, 84, '<p>M&ocirc; tả (hoặc giải th&iacute;ch) được t&iacute;nh chất s&oacute;ng của electron bằng hiện tượng nhiễu xạ electron.</p>', '2026-03-27 00:47:37', '2026-03-27 00:47:37'),
(275, 84, '<p>Vận dụng được c&ocirc;ng thức bước s&oacute;ng de Broglie: &lambda; = h/p với p l&agrave; động lượng của hạt.</p>', '2026-03-27 00:47:49', '2026-03-27 00:47:49'),
(276, 85, '<p>M&ocirc; tả được sự tồn tại của c&aacute;c mức năng lượng dừng của nguy&ecirc;n tử.</p>', '2026-03-27 00:48:16', '2026-03-27 00:48:16'),
(277, 85, '<p>Giải th&iacute;ch được sự tạo th&agrave;nh vạch quang phổ.</p>', '2026-03-27 00:48:28', '2026-03-27 00:48:28'),
(278, 85, '<p>So s&aacute;nh được quang phổ ph&aacute;t xạ v&agrave; quang phổ vạch hấp thụ.</p>', '2026-03-27 00:48:39', '2026-03-27 00:48:39'),
(279, 85, '<p>Vận dụng được biểu thức chuyển mức năng lượng &nbsp;<span class=\"math-tex\">\\( hf=E_1-E_2 \\)</span>.</p>', '2026-03-27 00:49:21', '2026-03-27 00:49:21'),
(280, 86, '<p>N&ecirc;u được c&aacute;c v&ugrave;ng năng lượng trong chất rắn theo m&ocirc; h&igrave;nh v&ugrave;ng năng lượng đơn giản.</p>', '2026-03-27 00:49:44', '2026-03-27 00:49:44'),
(281, 86, '<p>Sử dụng được l&iacute; thuyết v&ugrave;ng năng lượng đơn giản để giải th&iacute;ch được: Sự phụ thuộc v&agrave;o nhiệt độ của điện trở kim loại v&agrave; b&aacute;n dẫn kh&ocirc;ng pha tạp; Sự phụ thuộc của điện trở của c&aacute;c điện trở quang (LDR) v&agrave;o cường độ s&aacute;ng.</p>', '2026-03-27 00:50:00', '2026-03-27 00:50:00'),
(282, 87, '<p>Tr&igrave;nh b&agrave;y được c&aacute;c loại sai số trong thực h&agrave;nh th&iacute; nghiệm vật l&iacute;.</p>', '2026-03-27 01:09:36', '2026-03-27 01:09:36'),
(283, 88, '<p>Biết chọn v&agrave; sử dụng được c&aacute;c dụng cụ đo độ d&agrave;i ph&ugrave; hợp trong một b&agrave;i thực h&agrave;nh cụ thể.</p>', '2026-03-27 01:09:54', '2026-03-27 01:09:54'),
(284, 89, '<p>Biết sử dụng c&aacute;c phương ph&aacute;p xử l&iacute; số liệu như: Hồi quy tuyến t&iacute;nh, &ocirc; bao sai số &hellip;</p>', '2026-03-27 01:10:18', '2026-03-27 01:10:18'),
(285, 90, '<p>Sử dụng được hệ quy chiếu trong m&ocirc; tả chuyển động của vật.</p>', '2026-03-27 01:10:49', '2026-03-27 01:10:49'),
(286, 90, '<p>Thiết lập được c&aacute;c phương tr&igrave;nh về độ dịch chuyển, vận tốc, gia tốc khi chuyển đổi hệ quy chiếu.</p>', '2026-03-27 01:11:07', '2026-03-27 01:11:07'),
(287, 90, '<p>Giải được c&aacute;c b&agrave;i tập về c&aacute;c dạng chuyển động thẳng, chuyển động cong v&agrave; chuyển động<br>tr&ograve;n.</p>', '2026-03-27 01:11:21', '2026-03-27 01:11:21'),
(288, 91, '<p>M&ocirc; tả được động lượng của một chất điểm v&agrave; hệ chất điểm, định luật bảo to&agrave;n v&agrave; vận dụng giải được c&aacute;c b&agrave;i tập cụ thể.</p>', '2026-03-27 01:11:46', '2026-03-27 01:11:46'),
(289, 91, '<p>Giải th&iacute;ch được phương tr&igrave;nh chuyển động của vật c&oacute; khối lượng biến đổi.</p>', '2026-03-27 01:11:58', '2026-03-27 01:11:58'),
(290, 92, '<p>M&ocirc; tả được động năng v&agrave; thế năng cho c&aacute;c trường lực đơn giản v&agrave; vận dụng giải được c&aacute;c b&agrave;i tập cụ thể.</p>', '2026-03-27 01:12:20', '2026-03-27 01:12:20'),
(291, 92, '<p>Giải th&iacute;ch v&agrave; t&iacute;nh c&aacute;c b&agrave;i tập li&ecirc;n quan đến bảo to&agrave;n cơ năng, năng lượng.</p>', '2026-03-27 01:12:31', '2026-03-27 01:12:31'),
(292, 92, '<p>Vận dụng được kiến thức về c&ocirc;ng cơ học v&agrave; c&ocirc;ng suất; để giải quyết c&aacute;c b&agrave;i tập cụ thể.</p>', '2026-03-27 01:12:42', '2026-03-27 01:12:42'),
(293, 93, '<p>Giải được c&aacute;c b&agrave;i tập về c&aacute;c định luật Kepler.</p>', '2026-03-27 01:13:16', '2026-03-27 01:13:16'),
(294, 94, '<p>Viết được c&ocirc;ng thức t&iacute;nh thế năng của vật trong trường hấp dẫn.</p>', '2026-03-27 01:13:38', '2026-03-27 01:13:38'),
(295, 94, '<p>Giải được c&aacute;c b&agrave;i tập trong trường lực xuy&ecirc;n t&acirc;m cụ thể.</p>', '2026-03-27 01:13:50', '2026-03-27 01:13:50'),
(296, 95, '<p>M&ocirc; tả v&agrave; giải th&iacute;ch được chuyển động của c&aacute;c vật trong trường hấp dẫn với trường hợp b&agrave;i to&aacute;n hai vật v&agrave; ba vật.</p>', '2026-03-27 01:14:43', '2026-03-27 01:14:43'),
(297, 96, '<p>T&iacute;nh được c&aacute;c vận tốc vũ trụ cấp 1, cấp 2 v&agrave; 3 với một thi&ecirc;n thể x&aacute;c định.</p>', '2026-03-27 01:15:02', '2026-03-27 01:15:02'),
(298, 97, '<p>M&ocirc; tả được chuyển động song phẳng.</p>', '2026-03-27 01:31:14', '2026-03-27 01:31:14'),
(299, 97, '<p>M&ocirc; tả được chuyển động của một vật rắn; vận tốc v&agrave; gia tốc của c&aacute;c điểm vật chất của c&aacute;c vật rắn đang quay.</p>', '2026-03-27 01:31:29', '2026-03-27 01:31:29'),
(300, 97, '<p>Vận dụng được kiến thức để giải được c&aacute;c b&agrave;i tập cụ thể.</p>', '2026-03-27 01:31:40', '2026-03-27 01:31:40'),
(301, 98, '<p>Vận dụng được kiến thức để giải được c&aacute;c b&agrave;i tập cụ thể.</p>', '2026-03-27 01:31:57', '2026-03-27 01:31:57'),
(302, 99, '<p>Vận dụng được kiến thức để giải được c&aacute;c b&agrave;i tập cụ thể.</p>', '2026-03-27 01:32:13', '2026-03-27 01:32:13'),
(303, 100, '<p>Vận dụng được phương tr&igrave;nh động lực học vật rắn để giải c&aacute;c b&agrave;i tập cụ thể.</p>', '2026-03-27 01:33:07', '2026-03-27 01:33:07'),
(304, 100, '<p>Viết được điều kiện c&acirc;n bằng: c&acirc;n bằng lực, v&agrave; c&acirc;n bằng m&ocirc; men v&agrave; &aacute;p dụng để giải được c&aacute;c b&agrave;i tập li&ecirc;n quan.</p>', '2026-03-27 01:33:23', '2026-03-27 01:33:23'),
(305, 100, '<p>Tr&igrave;nh b&agrave;y được: Lực ph&aacute;p tuyến, lực căng, lực ma s&aacute;t nghỉ tĩnh v&agrave; trượt động; định luật Hooke, ứng suất, biến dạng, v&agrave; m&ocirc; đun Young. &Aacute;p dụng để giải được c&aacute;c b&agrave;i tập li&ecirc;n quan.</p>', '2026-03-27 01:33:37', '2026-03-27 01:33:37'),
(306, 100, '<p>M&ocirc; tả được sự c&acirc;n bằng bền v&agrave; kh&ocirc;ng bền.</p>', '2026-03-27 01:33:49', '2026-03-27 01:33:49'),
(307, 101, '<p>Hiểu v&agrave; m&ocirc; tả được kh&aacute;i niệm m&ocirc; men qu&aacute;n t&iacute;nh cho c&aacute;c vật thể đơn giản. T&iacute;nh được m&ocirc; men qu&aacute;n t&iacute;nh của một vật cụ thể bằng t&iacute;ch ph&acirc;n.</p>', '2026-03-27 01:35:36', '2026-03-27 01:35:36'),
(308, 101, '<p>Vận dụng định l&iacute; trục song song (định l&iacute; Huyghens - Steiner) để giải quyết c&aacute;c b&agrave;i tập cụ thể.</p>', '2026-03-27 01:35:50', '2026-03-27 01:35:50'),
(309, 102, '<p>M&ocirc; tả được động năng cho chuyển động v&agrave; quay. Vận dụng giải được c&aacute;c b&agrave;i tập thực tế.</p>', '2026-03-27 01:36:10', '2026-03-27 01:36:10'),
(310, 102, '<p>Giải th&iacute;ch v&agrave; t&iacute;nh to&aacute;n được c&aacute;c b&agrave;i tập li&ecirc;n quan đến bảo to&agrave;n cơ năng, năng lượng.</p>', '2026-03-27 01:36:20', '2026-03-27 01:36:20'),
(311, 102, '<p>Vận dụng được kiến thức về c&ocirc;ng cơ học v&agrave; c&ocirc;ng suất; để giải quyết c&aacute;c b&agrave;i tập cụ thể.</p>', '2026-03-27 01:36:33', '2026-03-27 01:36:33'),
(312, 103, '<p>Vận dụng được kh&aacute;i niệm m&ocirc; men động lượng để giải quyết c&aacute;c b&agrave;i tập cụ thể.</p>', '2026-03-27 01:36:58', '2026-03-27 01:36:58'),
(313, 104, '<p>Sử dụng được hệ quy chiếu khối t&acirc;m để giải c&aacute;c b&agrave;i tập cụ thể.</p>', '2026-03-27 01:37:28', '2026-03-27 01:37:28'),
(314, 105, '<p>Giải được c&aacute;c b&agrave;i tập về c&acirc;n bằng hoặc chuyển động của một vật trong hệ quy chiếu c&oacute; gia tốc.</p>', '2026-03-27 01:37:51', '2026-03-27 01:37:51'),
(315, 106, '<p>Lập được kế hoạch, tiến h&agrave;nh nghi&ecirc;n cứu v&agrave; b&aacute;o c&aacute;o nội dung t&igrave;m hiểu về lực qu&aacute;n t&iacute;nh v&agrave; một số ứng dụng trong thực tiễn.</p>', '2026-03-27 01:38:16', '2026-03-27 01:38:16'),
(316, 107, '<p>Biết chọn dụng cụ ph&ugrave; hợp v&agrave; sử dụng được c&aacute;c dụng cụ: Đồng hồ đo thời gian; nhiệt kế; c&acirc;n ch&iacute;nh x&aacute;c &hellip;</p>', '2026-03-27 01:47:07', '2026-03-27 01:47:07'),
(317, 107, '<p>Ph&acirc;n t&iacute;ch xử l&iacute; được số liệu thực nghiệm.</p>', '2026-03-27 01:47:21', '2026-03-27 01:47:21'),
(318, 107, '<p>Vẽ được đồ thị thực nghiệm v&agrave; đồ thị tuyến t&iacute;nh ho&aacute;.</p>', '2026-03-27 01:47:33', '2026-03-27 01:47:33'),
(319, 107, '<p>Tr&igrave;nh b&agrave;y được b&aacute;o c&aacute;o th&iacute; nghiệm.</p>', '2026-03-27 01:47:48', '2026-03-27 01:47:48'),
(320, 108, '<p>- Biết chọn dụng cụ ph&ugrave; hợp v&agrave; sử dụng được c&aacute;c dụng cụ: Đồng hồ đo thời gian; nhiệt kế; c&acirc;n ch&iacute;nh x&aacute;c &hellip;</p>\r\n<p>- Ph&acirc;n t&iacute;ch xử l&iacute; được số liệu thực nghiệm</p>\r\n<p>- Vẽ được đồ thị thực nghiệm v&agrave; đồ thị tuyến t&iacute;nh ho&aacute;</p>\r\n<p>- Tr&igrave;nh b&agrave;y được b&aacute;o c&aacute;o th&iacute; nghiệm.</p>', '2026-03-27 01:49:07', '2026-03-27 01:49:07'),
(321, 109, '<p>- Biết chọn dụng cụ ph&ugrave; hợp v&agrave; sử dụng được c&aacute;c dụng cụ: Đồng hồ đo thời gian; nhiệt kế; c&acirc;n ch&iacute;nh x&aacute;c &hellip;</p>\r\n<p>- Ph&acirc;n t&iacute;ch xử l&iacute; được số liệu thực nghiệm</p>\r\n<p>- Vẽ được đồ thị thực nghiệm v&agrave; đồ thị tuyến t&iacute;nh ho&aacute;</p>\r\n<p>- Tr&igrave;nh b&agrave;y được b&aacute;o c&aacute;o th&iacute; nghiệm.</p>', '2026-03-27 01:49:27', '2026-03-27 01:49:27'),
(322, 110, '<p>- Biết chọn dụng cụ ph&ugrave; hợp v&agrave; sử dụng được c&aacute;c dụng cụ: Đồng hồ đo thời gian; nhiệt kế; c&acirc;n ch&iacute;nh x&aacute;c &hellip;</p>\r\n<p>- Ph&acirc;n t&iacute;ch xử l&iacute; được số liệu thực nghiệm</p>\r\n<p>- Vẽ được đồ thị thực nghiệm v&agrave; đồ thị tuyến t&iacute;nh ho&aacute;</p>\r\n<p>- Tr&igrave;nh b&agrave;y được b&aacute;o c&aacute;o th&iacute; nghiệm.</p>', '2026-03-27 01:49:44', '2026-03-27 01:49:44'),
(323, 111, '<p>- Biết chọn dụng cụ ph&ugrave; hợp v&agrave; sử dụng được c&aacute;c dụng cụ: Đồng hồ đo thời gian; nhiệt kế; c&acirc;n ch&iacute;nh x&aacute;c &hellip;</p>\r\n<p>- Ph&acirc;n t&iacute;ch xử l&iacute; được số liệu thực nghiệm</p>\r\n<p>- Vẽ được đồ thị thực nghiệm v&agrave; đồ thị tuyến t&iacute;nh ho&aacute;</p>\r\n<p>- Tr&igrave;nh b&agrave;y được b&aacute;o c&aacute;o th&iacute; nghiệm.</p>', '2026-03-27 01:49:54', '2026-03-27 01:49:54'),
(324, 112, '<p>- Biết chọn dụng cụ ph&ugrave; hợp v&agrave; sử dụng được c&aacute;c dụng cụ: Đồng hồ đo thời gian; nhiệt kế; c&acirc;n ch&iacute;nh x&aacute;c &hellip;</p>\r\n<p>- Ph&acirc;n t&iacute;ch xử l&iacute; được số liệu thực nghiệm</p>\r\n<p>- Vẽ được đồ thị thực nghiệm v&agrave; đồ thị tuyến t&iacute;nh ho&aacute;</p>\r\n<p>- Tr&igrave;nh b&agrave;y được b&aacute;o c&aacute;o th&iacute; nghiệm.</p>', '2026-03-27 01:50:18', '2026-03-27 01:50:18'),
(325, 113, '<p>- Biết chọn dụng cụ ph&ugrave; hợp v&agrave; sử dụng được c&aacute;c dụng cụ: Đồng hồ đo thời gian; nhiệt kế; c&acirc;n ch&iacute;nh x&aacute;c &hellip;</p>\r\n<p>- Ph&acirc;n t&iacute;ch xử l&iacute; được số liệu thực nghiệm</p>\r\n<p>- Vẽ được đồ thị thực nghiệm v&agrave; đồ thị tuyến t&iacute;nh ho&aacute;</p>\r\n<p>- Tr&igrave;nh b&agrave;y được b&aacute;o c&aacute;o th&iacute; nghiệm.</p>', '2026-03-27 01:50:30', '2026-03-27 01:50:30'),
(326, 116, '<p>Thiết lập được phương tr&igrave;nh chuyển động của chất điểm v&agrave; vật rắn dao động điều h&ograve;a.</p>', '2026-03-27 02:10:48', '2026-03-27 02:10:48'),
(327, 116, '<p>Giải th&iacute;ch hiện tượng v&agrave; t&iacute;nh to&aacute;n sự suy giảm theo h&agrave;m mũ của dao động tắt dần.</p>', '2026-03-27 02:11:00', '2026-03-27 02:11:00'),
(328, 116, '<p>Giải th&iacute;ch hiện tượng v&agrave; t&iacute;nh to&aacute;n sự suy giảm theo h&agrave;m mũ của dao động tắt dần.</p>', '2026-03-27 02:11:12', '2026-03-27 02:11:12'),
(329, 116, '<p>Giải th&iacute;ch dao động tự do của mạch LC.</p>', '2026-03-27 02:11:23', '2026-03-27 02:11:23'),
(330, 116, '<p>So s&aacute;nh sự tương tự giữa c&aacute;c hệ dao động cơ học v&agrave; điện.</p>', '2026-03-27 02:11:33', '2026-03-27 02:11:33'),
(331, 116, '<p>M&ocirc; tả c&aacute;ch tạo ra dao động điều h&ograve;a trong mạch LC.</p>', '2026-03-27 02:11:45', '2026-03-27 02:11:45'),
(332, 117, '<p>Tr&igrave;nh b&agrave;y sự lan truyền của s&oacute;ng điều h&ograve;a v&agrave; viết phương tr&igrave;nh s&oacute;ng.</p>', '2026-03-27 02:12:15', '2026-03-27 02:12:15'),
(333, 117, '<p>M&ocirc; tả sự phụ thuộc của pha v&agrave;o kh&ocirc;ng gian v&agrave; thời gian.</p>', '2026-03-27 02:12:29', '2026-03-27 02:12:29'),
(334, 117, '<p>Tr&igrave;nh b&agrave;y c&aacute;c kh&aacute;i niệm bước s&oacute;ng, vector s&oacute;ng, tốc độ pha v&agrave; tốc độ nh&oacute;m.</p>', '2026-03-27 02:12:42', '2026-03-27 02:12:42'),
(335, 117, '<p>Giải th&iacute;ch hiện tượng sự suy giảm theo h&agrave;m mũ của bi&ecirc;n độ s&oacute;ng.</p>', '2026-03-27 02:13:01', '2026-03-27 02:13:01'),
(336, 117, '<p>Giải th&iacute;ch hiệu ứng Doppler cổ điển.</p>', '2026-03-27 02:13:27', '2026-03-27 02:13:27'),
(337, 117, '<p>&Aacute;p dụng nguy&ecirc;n l&iacute; Fermat v&agrave; định luật Snell để giải c&aacute;c b&agrave;i tập về s&oacute;ng.</p>', '2026-03-27 02:13:51', '2026-03-27 02:13:51'),
(338, 117, '<p>Giải th&iacute;ch v&agrave; t&iacute;nh to&aacute;n tốc độ s&oacute;ng &acirc;m, v&agrave; m&ocirc; tả hiện tượng n&oacute;n Mach.</p>', '2026-03-27 02:14:03', '2026-03-27 02:14:03'),
(339, 134, '<p>Giải th&iacute;ch c&aacute;c hiện tượng ph&aacute;ch, s&oacute;ng dừng.</p>', '2026-03-27 02:15:19', '2026-03-27 02:15:19'),
(340, 134, '<p>&Aacute;p dụng nguy&ecirc;n l&iacute; Huygens để giải th&iacute;ch c&aacute;c hiện tượng giao thoa v&agrave; nhiễu xạ.</p>', '2026-03-27 02:15:30', '2026-03-27 02:15:30'),
(341, 134, '<p>Giải th&iacute;ch hiện tượng giao thoa do m&agrave;ng mỏng v&agrave; t&iacute;nh to&aacute;n điều kiện cho v&acirc;n cực tiểu v&agrave; cực đại.</p>', '2026-03-27 02:15:44', '2026-03-27 02:15:44'),
(342, 134, '<p>Giải th&iacute;ch hiện tượng nhiễu xạ qua một khe v&agrave; hai khe.</p>', '2026-03-27 02:15:54', '2026-03-27 02:15:54'),
(343, 134, '<p>M&ocirc; tả c&aacute;c đặc điểm của c&aacute;ch tử nhiễu xạ.</p>', '2026-03-27 02:16:04', '2026-03-27 02:16:04'),
(344, 134, '<p>&Aacute;p dụng được định luật phản xạ Bragg.</p>', '2026-03-27 02:16:16', '2026-03-27 02:16:16'),
(345, 118, '<p>Giải th&iacute;ch kh&aacute;i niệm quang th&ocirc;ng v&agrave; sự li&ecirc;n tục của quang th&ocirc;ng.<br><br></p>', '2026-03-27 02:17:12', '2026-03-27 02:17:12'),
(346, 118, '<p>Tr&igrave;nh b&agrave;y được kh&aacute;i niệm độ rọi (lux).</p>', '2026-03-27 02:17:24', '2026-03-27 02:17:24'),
(347, 118, '<p>T&iacute;nh to&aacute;n được cường độ s&aacute;ng (candela).</p>', '2026-03-27 02:17:33', '2026-03-27 02:17:33'),
(348, 118, '<p>Sự truyền s&aacute;ng qua c&aacute;c dụng cụ quang (lăng k&iacute;nh, thấu k&iacute;nh, gương cầu, hệ quang học).</p>', '2026-03-27 02:17:45', '2026-03-27 02:17:45'),
(349, 119, '<p>Giải th&iacute;ch chiết suất của c&aacute;c vật liệu.</p>', '2026-03-27 02:18:05', '2026-03-27 02:18:05'),
(350, 119, '<p>Giải th&iacute;ch sự t&aacute;n sắc v&agrave; ti&ecirc;u hao của s&oacute;ng điện từ.</p>', '2026-03-27 02:18:14', '2026-03-27 02:18:14'),
(351, 119, '<p>M&ocirc; tả hiện tượng ph&acirc;n cực tuyến t&iacute;nh.</p>', '2026-03-27 02:18:24', '2026-03-27 02:18:24'),
(352, 119, '<p>T&iacute;nh to&aacute;n được g&oacute;c Brewster.</p>', '2026-03-27 02:18:44', '2026-03-27 02:18:44'),
(353, 119, '<p>M&ocirc; tả nguy&ecirc;n l&iacute; hoạt động của c&aacute;c ph&acirc;n cực kế.</p>', '2026-03-27 02:18:56', '2026-03-27 02:18:56'),
(354, 119, '<p>&Aacute;p dụng định luật Malus trong c&aacute;c b&agrave;i tập thực tế.</p>', '2026-03-27 02:19:07', '2026-03-27 02:19:07'),
(355, 120, '<p>Giải th&iacute;ch sự điều tiết của mắt, c&aacute;c biện ph&aacute;p sửa tật của mắt bằng c&aacute;c dụng cụ quang.</p>', '2026-03-27 02:19:30', '2026-03-27 02:19:30'),
(356, 120, '<p>Giải th&iacute;ch nguy&ecirc;n l&iacute; hoạt động của k&iacute;nh thi&ecirc;n văn v&agrave; k&iacute;nh hiển vi.</p>', '2026-03-27 02:19:40', '2026-03-27 02:19:40'),
(357, 120, '<p>T&iacute;nh to&aacute;n số bội gi&aacute;c của k&iacute;nh thi&ecirc;n văn v&agrave; k&iacute;nh hiển vi.</p>', '2026-03-27 02:19:50', '2026-03-27 02:19:50'),
(358, 120, '<p>M&ocirc; tả nguy&ecirc;n l&iacute; hoạt động v&agrave; ứng dụng của giao thoa kế v&agrave; m&aacute;y quang phổ.</p>', '2026-03-27 02:20:00', '2026-03-27 02:20:00'),
(359, 114, '<p>Giải th&iacute;ch được kh&aacute;i niệm trường tĩnh điện v&agrave; chứng minh n&oacute; l&agrave; một trường thế.</p>', '2026-03-27 02:23:37', '2026-03-27 02:23:37'),
(360, 114, '<p>Vận dụng được định l&iacute; Ostrogradski-Gauss trong giải c&aacute;c b&agrave;i tập tĩnh điện.</p>', '2026-03-27 02:23:47', '2026-03-27 02:23:47'),
(361, 114, '<p>Sử dụng phương ph&aacute;p ảnh điện trong giải c&aacute;c b&agrave;i tập.</p>', '2026-03-27 02:23:57', '2026-03-27 02:23:57'),
(362, 115, '<p>T&iacute;nh to&aacute;n được lực Lorentz t&aacute;c dụng l&ecirc;n hạt mang điện trong trường từ.</p>', '2026-03-27 02:24:18', '2026-03-27 02:24:18'),
(363, 115, '<p>&Aacute;p dụng được định luật Biot-Savart để t&iacute;nh to&aacute;n trường từ do d&ograve;ng điện sinh ra.</p>', '2026-03-27 02:24:29', '2026-03-27 02:24:29'),
(364, 115, '<p>Giải th&iacute;ch được trường từ tr&ecirc;n trục của một v&ograve;ng d&acirc;y tr&ograve;n v&agrave; c&aacute;c hệ thống đối xứng đơn giản như d&acirc;y thẳng, v&ograve;ng tr&ograve;n v&agrave; ống d&acirc;y d&agrave;i.</p>', '2026-03-27 02:24:43', '2026-03-27 02:24:43'),
(365, 115, '<p>Tr&igrave;nh b&agrave;y được t&iacute;nh chất v&agrave; ứng dụng của c&aacute;c loại vật liệu từ: thuận từ, nghịch từ, sắt từ.</p>', '2026-03-27 02:24:54', '2026-03-27 02:24:54'),
(366, 115, '<p>T&iacute;nh to&aacute;n v&agrave; giải th&iacute;ch được năng lượng của một lưỡng cực từ trong trường từ.</p>', '2026-03-27 02:25:03', '2026-03-27 02:25:03'),
(367, 115, '<p>M&ocirc; tả được m&ocirc; men lưỡng cực của một v&ograve;ng d&ograve;ng điện.</p>', '2026-03-27 02:25:13', '2026-03-27 02:25:13'),
(368, 121, '<p>Giải th&iacute;ch được kh&aacute;i niệm điện trở tuyến t&iacute;nh v&agrave; &aacute;p dụng được định luật Ohm dưới dạng vi ph&acirc;n v&agrave; t&iacute;ch ph&acirc;n.</p>', '2026-03-27 02:25:58', '2026-03-27 02:25:58'),
(369, 121, '<p>&Aacute;p dụng được định luật Kirchhoff trong c&aacute;c b&agrave;i tập mạch điện.</p>', '2026-03-27 02:26:09', '2026-03-27 02:26:09'),
(370, 121, '<p>Ph&acirc;n t&iacute;ch được c&aacute;c phần tử phi tuyến dựa tr&ecirc;n đặc t&iacute;nh V-I cho trước.</p>', '2026-03-27 02:26:19', '2026-03-27 02:26:19'),
(371, 121, '<p>T&iacute;nh to&aacute;n được năng lượng lưu trữ trong tụ điện v&agrave; cuộn cảm.</p>', '2026-03-27 02:26:28', '2026-03-27 02:26:28'),
(372, 121, '<p>Giải th&iacute;ch được hiện tượng hỗ cảm v&agrave; t&iacute;nh to&aacute;n được c&aacute;c tham số li&ecirc;n quan.</p>', '2026-03-27 02:26:38', '2026-03-27 02:26:38'),
(373, 121, '<p>T&iacute;nh to&aacute;n được hằng số thời gian cho mạch RL v&agrave; RC.</p>', '2026-03-27 02:26:48', '2026-03-27 02:26:48'),
(374, 121, '<p>Giải th&iacute;ch được kh&aacute;i niệm bi&ecirc;n độ phức trong mạch xoay chiều.</p>', '2026-03-27 02:26:59', '2026-03-27 02:26:59'),
(375, 121, '<p>T&iacute;nh được trở kh&aacute;ng (điện trở, dung kh&aacute;ng, cảm kh&aacute;ng, tổng trở) của mạch RLC.</p>', '2026-03-27 02:27:09', '2026-03-27 02:27:09'),
(376, 122, '<p>M&ocirc; tả được qu&aacute; tr&igrave;nh tạo ra chất b&aacute;n dẫn loại p v&agrave; loại n, v&agrave; vai tr&ograve; của điện tử v&agrave; lỗ trống trong mỗi loại.</p>', '2026-03-27 02:27:38', '2026-03-27 02:27:38'),
(377, 122, '<p>Giải th&iacute;ch v&agrave; &aacute;p dụng được phương tr&igrave;nh li&ecirc;n tục v&agrave; phương tr&igrave;nh khuếch t&aacute;n d&ograve;ng điện trongchất&nbsp; b&aacute;n dẫn.</p>', '2026-03-27 02:27:50', '2026-03-27 02:27:50'),
(378, 122, '<p>M&ocirc; tả được cấu tr&uacute;c của li&ecirc;n kết pn v&agrave; v&ugrave;ng suy giảm mật độ điện t&iacute;ch.</p>', '2026-03-27 02:28:00', '2026-03-27 02:28:00'),
(379, 122, '<p>Giải th&iacute;ch được mối quan hệ giữa d&ograve;ng điện v&agrave; điện &aacute;p trong li&ecirc;n kết pn, v&agrave; ph&acirc;n t&iacute;ch được đặc tuyến V-I.</p>', '2026-03-27 02:28:15', '2026-03-27 02:28:15'),
(380, 122, '<p>Giải th&iacute;ch được cấu tr&uacute;c v&agrave; nguy&ecirc;n l&iacute; hoạt động của transistor lưỡng cực (BJT), bao gồm c&aacute;c qu&aacute; tr&igrave;nh khuếch đại v&agrave; chuyển mạch.</p>', '2026-03-27 02:28:26', '2026-03-27 02:28:26'),
(381, 122, '<p>Giải th&iacute;ch được cấu tr&uacute;c v&agrave; nguy&ecirc;n l&iacute; hoạt động của transistor hiệu ứng trường (FET).</p>', '2026-03-27 02:28:37', '2026-03-27 02:28:37'),
(382, 122, '<p>Ph&acirc;n t&iacute;ch được đặc tuyến I-V của FET v&agrave; &aacute;p dụng v&agrave;o c&aacute;c b&agrave;i tập thực tế.</p>', '2026-03-27 02:28:48', '2026-03-27 02:28:48'),
(383, 123, '<p>Tr&igrave;nh b&agrave;y được nguy&ecirc;n l&iacute; tương đối v&agrave; ph&eacute;p biến đổi Lorentz cho thời gian v&agrave; tọa độ kh&ocirc;ng gian.</p>', '2026-03-27 02:29:19', '2026-03-27 02:29:19'),
(384, 123, '<p>Tr&igrave;nh b&agrave;y ph&eacute;p biến đổi Lorentz cho năng lượng v&agrave; động lượng.</p>', '2026-03-27 02:29:29', '2026-03-27 02:29:29'),
(385, 123, '<p>Tr&igrave;nh b&agrave;y nguy&ecirc;n l&iacute; tương đương khối lượng - năng lượng.</p>', '2026-03-27 02:29:42', '2026-03-27 02:29:42'),
(386, 123, '<p>&Aacute;p dụng được t&iacute;nh bất biến của khoảng kh&ocirc;ng-thời gian v&agrave; của khối lượng nghỉ.</p>', '2026-03-27 02:29:53', '2026-03-27 02:29:53'),
(387, 124, '<p>Giải được c&aacute;c b&agrave;i tập về cộng vận tốc song song.</p>', '2026-03-27 02:30:14', '2026-03-27 02:30:14'),
(388, 124, '<p>Giải th&iacute;ch được hiện tượng d&atilde;n thời gian, co chiều d&agrave;i.</p>', '2026-03-27 02:30:26', '2026-03-27 02:30:26'),
(389, 124, '<p>Giải th&iacute;ch v&agrave; t&iacute;nh to&aacute;n được năng lượng v&agrave; động lượng của photon v&agrave; hiệu ứng Doppler tương đối.</p>', '2026-03-27 02:30:50', '2026-03-27 02:30:50'),
(390, 124, '<p>Lập được kế hoạch, tiến h&agrave;nh nghi&ecirc;n cứu v&agrave; b&aacute;o c&aacute;o nội dung t&igrave;m hiểu về thuyết tương đối.</p>', '2026-03-27 02:31:02', '2026-03-27 02:31:02'),
(391, 125, '<p>- Lựa chọn v&agrave; sử dụng được c&aacute;c dụng cụ đo như ampe kế, v&ocirc;n kế, đồng hồ đo điện trở, m&aacute;y hiện s&oacute;ng, v&agrave; c&aacute;c dụng cụ th&iacute; nghiệm<br>kh&aacute;c để thu thập dữ liệu ch&iacute;nh x&aacute;c.</p>\r\n<p>- Lắp r&aacute;p v&agrave; kiểm tra mạch điện.</p>\r\n<p>- Ph&acirc;n t&iacute;ch v&agrave; khắc phục được một số sự cố kỹ thuật trong c&aacute;c mạch điện v&agrave; thiết bị th&iacute; nghiệm.</p>\r\n<p>- Thực hiện được c&aacute;c ph&eacute;p đo, thu thập dữ liệu đo.</p>\r\n<p>- Ph&acirc;n t&iacute;ch xử l&iacute; được số đo: lập bảng dữ liệu, vẽ được đồ thị thực nghiệm, đồ thị tuyến t&iacute;nh ho&aacute;&hellip;</p>\r\n<p>- Tr&igrave;nh b&agrave;y được b&aacute;o c&aacute;o th&iacute; nghiệm.</p>', '2026-03-27 02:33:32', '2026-03-27 02:33:32'),
(392, 126, '<p>- Lựa chọn v&agrave; sử dụng được c&aacute;c dụng cụ đo như ampe kế, v&ocirc;n kế, đồng hồ đo điện trở, m&aacute;y hiện s&oacute;ng, v&agrave; c&aacute;c dụng cụ th&iacute; nghiệm<br>kh&aacute;c để thu thập dữ liệu ch&iacute;nh x&aacute;c.</p>\r\n<p>- Lắp r&aacute;p v&agrave; kiểm tra mạch điện.</p>\r\n<p>- Ph&acirc;n t&iacute;ch v&agrave; khắc phục được một số sự cố kỹ thuật trong c&aacute;c mạch điện v&agrave; thiết bị th&iacute; nghiệm.</p>\r\n<p>- Thực hiện được c&aacute;c ph&eacute;p đo, thu thập dữ liệu đo.</p>\r\n<p>- Ph&acirc;n t&iacute;ch xử l&iacute; được số đo: lập bảng dữ liệu, vẽ được đồ thị thực nghiệm, đồ thị tuyến t&iacute;nh ho&aacute;&hellip;</p>\r\n<p>- Tr&igrave;nh b&agrave;y được b&aacute;o c&aacute;o th&iacute; nghiệm.</p>', '2026-03-27 02:33:43', '2026-03-27 02:33:43'),
(393, 127, '<p>- Lựa chọn v&agrave; sử dụng được c&aacute;c dụng cụ đo như ampe kế, v&ocirc;n kế, đồng hồ đo điện trở, m&aacute;y hiện s&oacute;ng, v&agrave; c&aacute;c dụng cụ th&iacute; nghiệm<br>kh&aacute;c để thu thập dữ liệu ch&iacute;nh x&aacute;c.</p>\r\n<p>- Lắp r&aacute;p v&agrave; kiểm tra mạch điện.</p>\r\n<p>- Ph&acirc;n t&iacute;ch v&agrave; khắc phục được một số sự cố kỹ thuật trong c&aacute;c mạch điện v&agrave; thiết bị th&iacute; nghiệm.</p>\r\n<p>- Thực hiện được c&aacute;c ph&eacute;p đo, thu thập dữ liệu đo.</p>\r\n<p>- Ph&acirc;n t&iacute;ch xử l&iacute; được số đo: lập bảng dữ liệu, vẽ được đồ thị thực nghiệm, đồ thị tuyến t&iacute;nh ho&aacute;&hellip;</p>\r\n<p>- Tr&igrave;nh b&agrave;y được b&aacute;o c&aacute;o th&iacute; nghiệm.</p>', '2026-03-27 02:33:57', '2026-03-27 02:33:57'),
(394, 128, '<p>- Lựa chọn v&agrave; sử dụng được c&aacute;c dụng cụ đo như ampe kế, v&ocirc;n kế, đồng hồ đo điện trở, m&aacute;y hiện s&oacute;ng, v&agrave; c&aacute;c dụng cụ th&iacute; nghiệm<br>kh&aacute;c để thu thập dữ liệu ch&iacute;nh x&aacute;c.</p>\r\n<p>- Lắp r&aacute;p v&agrave; kiểm tra mạch điện.</p>\r\n<p>- Ph&acirc;n t&iacute;ch v&agrave; khắc phục được một số sự cố kỹ thuật trong c&aacute;c mạch điện v&agrave; thiết bị th&iacute; nghiệm.</p>\r\n<p>- Thực hiện được c&aacute;c ph&eacute;p đo, thu thập dữ liệu đo.</p>\r\n<p>- Ph&acirc;n t&iacute;ch xử l&iacute; được số đo: lập bảng dữ liệu, vẽ được đồ thị thực nghiệm, đồ thị tuyến t&iacute;nh ho&aacute;&hellip;</p>\r\n<p>- Tr&igrave;nh b&agrave;y được b&aacute;o c&aacute;o th&iacute; nghiệm.</p>', '2026-03-27 02:34:11', '2026-03-27 02:34:11'),
(395, 129, '<p>- Lựa chọn v&agrave; sử dụng được c&aacute;c dụng cụ đo như ampe kế, v&ocirc;n kế, đồng hồ đo điện trở, m&aacute;y hiện s&oacute;ng, v&agrave; c&aacute;c dụng cụ th&iacute; nghiệm<br>kh&aacute;c để thu thập dữ liệu ch&iacute;nh x&aacute;c.</p>\r\n<p>- Lắp r&aacute;p v&agrave; kiểm tra mạch điện.</p>\r\n<p>- Ph&acirc;n t&iacute;ch v&agrave; khắc phục được một số sự cố kỹ thuật trong c&aacute;c mạch điện v&agrave; thiết bị th&iacute; nghiệm.</p>\r\n<p>- Thực hiện được c&aacute;c ph&eacute;p đo, thu thập dữ liệu đo.</p>\r\n<p>- Ph&acirc;n t&iacute;ch xử l&iacute; được số đo: lập bảng dữ liệu, vẽ được đồ thị thực nghiệm, đồ thị tuyến t&iacute;nh ho&aacute;&hellip;</p>\r\n<p>- Tr&igrave;nh b&agrave;y được b&aacute;o c&aacute;o th&iacute; nghiệm.</p>', '2026-03-27 02:34:26', '2026-03-27 02:34:26'),
(396, 130, '<p>- Lựa chọn v&agrave; sử dụng được c&aacute;c dụng cụ đo như ampe kế, v&ocirc;n kế, đồng hồ đo điện trở, m&aacute;y hiện s&oacute;ng, v&agrave; c&aacute;c dụng cụ th&iacute; nghiệm<br>kh&aacute;c để thu thập dữ liệu ch&iacute;nh x&aacute;c.</p>\r\n<p>- Lắp r&aacute;p v&agrave; kiểm tra mạch điện.</p>\r\n<p>- Ph&acirc;n t&iacute;ch v&agrave; khắc phục được một số sự cố kỹ thuật trong c&aacute;c mạch điện v&agrave; thiết bị th&iacute; nghiệm.</p>\r\n<p>- Thực hiện được c&aacute;c ph&eacute;p đo, thu thập dữ liệu đo.</p>\r\n<p>- Ph&acirc;n t&iacute;ch xử l&iacute; được số đo: lập bảng dữ liệu, vẽ được đồ thị thực nghiệm, đồ thị tuyến t&iacute;nh ho&aacute;&hellip;</p>\r\n<p>- Tr&igrave;nh b&agrave;y được b&aacute;o c&aacute;o th&iacute; nghiệm.</p>', '2026-03-27 02:34:38', '2026-03-27 02:34:38'),
(397, 131, '<p>- Lựa chọn v&agrave; sử dụng được c&aacute;c dụng cụ đo như ampe kế, v&ocirc;n kế, đồng hồ đo điện trở, m&aacute;y hiện s&oacute;ng, v&agrave; c&aacute;c dụng cụ th&iacute; nghiệm<br>kh&aacute;c để thu thập dữ liệu ch&iacute;nh x&aacute;c.</p>\r\n<p>- Lắp r&aacute;p v&agrave; kiểm tra mạch điện.</p>\r\n<p>- Ph&acirc;n t&iacute;ch v&agrave; khắc phục được một số sự cố kỹ thuật trong c&aacute;c mạch điện v&agrave; thiết bị th&iacute; nghiệm.</p>\r\n<p>- Thực hiện được c&aacute;c ph&eacute;p đo, thu thập dữ liệu đo.</p>\r\n<p>- Ph&acirc;n t&iacute;ch xử l&iacute; được số đo: lập bảng dữ liệu, vẽ được đồ thị thực nghiệm, đồ thị tuyến t&iacute;nh ho&aacute;&hellip;</p>\r\n<p>- Tr&igrave;nh b&agrave;y được b&aacute;o c&aacute;o th&iacute; nghiệm.</p>', '2026-03-27 02:34:59', '2026-03-27 02:34:59'),
(398, 132, '<p>- Lựa chọn v&agrave; sử dụng được c&aacute;c dụng cụ đo như ampe kế, v&ocirc;n kế, đồng hồ đo điện trở, m&aacute;y hiện s&oacute;ng, v&agrave; c&aacute;c dụng cụ th&iacute; nghiệm<br>kh&aacute;c để thu thập dữ liệu ch&iacute;nh x&aacute;c.</p>\r\n<p>- Lắp r&aacute;p v&agrave; kiểm tra mạch điện.</p>\r\n<p>- Ph&acirc;n t&iacute;ch v&agrave; khắc phục được một số sự cố kỹ thuật trong c&aacute;c mạch điện v&agrave; thiết bị th&iacute; nghiệm.</p>\r\n<p>- Thực hiện được c&aacute;c ph&eacute;p đo, thu thập dữ liệu đo.</p>\r\n<p>- Ph&acirc;n t&iacute;ch xử l&iacute; được số đo: lập bảng dữ liệu, vẽ được đồ thị thực nghiệm, đồ thị tuyến t&iacute;nh ho&aacute;&hellip;</p>\r\n<p>- Tr&igrave;nh b&agrave;y được b&aacute;o c&aacute;o th&iacute; nghiệm.</p>', '2026-03-27 02:35:23', '2026-03-27 02:35:23'),
(399, 133, '<p>- Lựa chọn v&agrave; sử dụng được c&aacute;c dụng cụ đo như ampe kế, v&ocirc;n kế, đồng hồ đo điện trở, m&aacute;y hiện s&oacute;ng, v&agrave; c&aacute;c dụng cụ th&iacute; nghiệm<br>kh&aacute;c để thu thập dữ liệu ch&iacute;nh x&aacute;c.</p>\r\n<p>- Lắp r&aacute;p v&agrave; kiểm tra mạch điện.</p>\r\n<p>- Ph&acirc;n t&iacute;ch v&agrave; khắc phục được một số sự cố kỹ thuật trong c&aacute;c mạch điện v&agrave; thiết bị th&iacute; nghiệm.</p>\r\n<p>- Thực hiện được c&aacute;c ph&eacute;p đo, thu thập dữ liệu đo.</p>\r\n<p>- Ph&acirc;n t&iacute;ch xử l&iacute; được số đo: lập bảng dữ liệu, vẽ được đồ thị thực nghiệm, đồ thị tuyến t&iacute;nh ho&aacute;&hellip;</p>\r\n<p>- Tr&igrave;nh b&agrave;y được b&aacute;o c&aacute;o th&iacute; nghiệm.</p>', '2026-03-27 02:35:32', '2026-03-27 02:35:32'),
(400, 147, '<p>- Lựa chọn v&agrave; sử dụng được c&aacute;c dụng cụ đo như ampe kế, v&ocirc;n kế, cảm biến nhiệt độ, cảm biến &aacute;nh s&aacute;ng, c&aacute;c dụng cụ th&iacute; nghiệm kh&aacute;c để thu thập dữ liệu ch&iacute;nh x&aacute;c.</p>\r\n<p>- Ph&acirc;n t&iacute;ch xử l&iacute; được số liệu thực nghiệm.</p>\r\n<p>- Vẽ được đồ thị thực nghiệm v&agrave; đồ thị tuyến t&iacute;nh ho&aacute;.</p>\r\n<p>- Tr&igrave;nh b&agrave;y được b&aacute;o c&aacute;o th&iacute; nghiệm.</p>', '2026-03-27 02:51:03', '2026-03-27 02:51:03'),
(401, 148, '<p>- Lựa chọn v&agrave; sử dụng được c&aacute;c dụng cụ đo như ampe kế, v&ocirc;n kế, cảm biến nhiệt độ, cảm biến &aacute;nh s&aacute;ng, c&aacute;c dụng cụ th&iacute; nghiệm kh&aacute;c để thu thập dữ liệu ch&iacute;nh x&aacute;c.</p>\r\n<p>- Ph&acirc;n t&iacute;ch xử l&iacute; được số liệu thực nghiệm.</p>\r\n<p>- Vẽ được đồ thị thực nghiệm v&agrave; đồ thị tuyến t&iacute;nh ho&aacute;.</p>\r\n<p>- Tr&igrave;nh b&agrave;y được b&aacute;o c&aacute;o th&iacute; nghiệm.</p>', '2026-03-27 02:51:13', '2026-03-27 02:51:13'),
(402, 149, '<p>- Lựa chọn v&agrave; sử dụng được c&aacute;c dụng cụ đo như ampe kế, v&ocirc;n kế, cảm biến nhiệt độ, cảm biến &aacute;nh s&aacute;ng, c&aacute;c dụng cụ th&iacute; nghiệm kh&aacute;c để thu thập dữ liệu ch&iacute;nh x&aacute;c.</p>\r\n<p>- Ph&acirc;n t&iacute;ch xử l&iacute; được số liệu thực nghiệm.</p>\r\n<p>- Vẽ được đồ thị thực nghiệm v&agrave; đồ thị tuyến t&iacute;nh ho&aacute;.</p>\r\n<p>- Tr&igrave;nh b&agrave;y được b&aacute;o c&aacute;o th&iacute; nghiệm.</p>', '2026-03-27 02:51:24', '2026-03-27 02:51:24'),
(403, 150, '<p>- Lựa chọn v&agrave; sử dụng được c&aacute;c dụng cụ đo như ampe kế, v&ocirc;n kế, cảm biến nhiệt độ, cảm biến &aacute;nh s&aacute;ng, c&aacute;c dụng cụ th&iacute; nghiệm kh&aacute;c để thu thập dữ liệu ch&iacute;nh x&aacute;c.</p>\r\n<p>- Ph&acirc;n t&iacute;ch xử l&iacute; được số liệu thực nghiệm.</p>\r\n<p>- Vẽ được đồ thị thực nghiệm v&agrave; đồ thị tuyến t&iacute;nh ho&aacute;.</p>\r\n<p>- Tr&igrave;nh b&agrave;y được b&aacute;o c&aacute;o th&iacute; nghiệm.</p>', '2026-03-27 02:51:37', '2026-03-27 02:51:37'),
(404, 151, '<p>- Lựa chọn v&agrave; sử dụng được c&aacute;c dụng cụ đo như ampe kế, v&ocirc;n kế, cảm biến nhiệt độ, cảm biến &aacute;nh s&aacute;ng, c&aacute;c dụng cụ th&iacute; nghiệm kh&aacute;c để thu thập dữ liệu ch&iacute;nh x&aacute;c.</p>\r\n<p>- Ph&acirc;n t&iacute;ch xử l&iacute; được số liệu thực nghiệm.</p>\r\n<p>- Vẽ được đồ thị thực nghiệm v&agrave; đồ thị tuyến t&iacute;nh ho&aacute;.</p>\r\n<p>- Tr&igrave;nh b&agrave;y được b&aacute;o c&aacute;o th&iacute; nghiệm.</p>', '2026-03-27 02:51:49', '2026-03-27 02:51:49'),
(405, 151, '<p>- Lựa chọn v&agrave; sử dụng được c&aacute;c dụng cụ đo như ampe kế, v&ocirc;n kế, cảm biến nhiệt độ, cảm biến &aacute;nh s&aacute;ng, c&aacute;c dụng cụ th&iacute; nghiệm kh&aacute;c để thu thập dữ liệu ch&iacute;nh x&aacute;c.</p>\r\n<p>- Ph&acirc;n t&iacute;ch xử l&iacute; được số liệu thực nghiệm.</p>\r\n<p>- Vẽ được đồ thị thực nghiệm v&agrave; đồ thị tuyến t&iacute;nh ho&aacute;.</p>\r\n<p>- Tr&igrave;nh b&agrave;y được b&aacute;o c&aacute;o th&iacute; nghiệm.</p>', '2026-03-27 02:52:02', '2026-03-27 02:52:02'),
(406, 152, '<p>- Lựa chọn v&agrave; sử dụng được c&aacute;c dụng cụ đo như ampe kế, v&ocirc;n kế, cảm biến nhiệt độ, cảm biến &aacute;nh s&aacute;ng, c&aacute;c dụng cụ th&iacute; nghiệm kh&aacute;c để thu thập dữ liệu ch&iacute;nh x&aacute;c.</p>\r\n<p>- Ph&acirc;n t&iacute;ch xử l&iacute; được số liệu thực nghiệm.</p>\r\n<p>- Vẽ được đồ thị thực nghiệm v&agrave; đồ thị tuyến t&iacute;nh ho&aacute;.</p>\r\n<p>- Tr&igrave;nh b&agrave;y được b&aacute;o c&aacute;o th&iacute; nghiệm.</p>', '2026-03-27 02:52:24', '2026-03-27 02:52:24'),
(407, 153, '<p>- Lựa chọn v&agrave; sử dụng được c&aacute;c dụng cụ đo như ampe kế, v&ocirc;n kế, cảm biến nhiệt độ, cảm biến &aacute;nh s&aacute;ng, c&aacute;c dụng cụ th&iacute; nghiệm kh&aacute;c để thu thập dữ liệu ch&iacute;nh x&aacute;c.</p>\r\n<p>- Ph&acirc;n t&iacute;ch xử l&iacute; được số liệu thực nghiệm.</p>\r\n<p>- Vẽ được đồ thị thực nghiệm v&agrave; đồ thị tuyến t&iacute;nh ho&aacute;.</p>\r\n<p>- Tr&igrave;nh b&agrave;y được b&aacute;o c&aacute;o th&iacute; nghiệm.</p>', '2026-03-27 02:52:36', '2026-03-27 02:52:36'),
(408, 154, '<p>- Lựa chọn v&agrave; sử dụng được c&aacute;c dụng cụ đo như ampe kế, v&ocirc;n kế, cảm biến nhiệt độ, cảm biến &aacute;nh s&aacute;ng, c&aacute;c dụng cụ th&iacute; nghiệm kh&aacute;c để thu thập dữ liệu ch&iacute;nh x&aacute;c.</p>\r\n<p>- Ph&acirc;n t&iacute;ch xử l&iacute; được số liệu thực nghiệm.</p>\r\n<p>- Vẽ được đồ thị thực nghiệm v&agrave; đồ thị tuyến t&iacute;nh ho&aacute;.</p>\r\n<p>- Tr&igrave;nh b&agrave;y được b&aacute;o c&aacute;o th&iacute; nghiệm.</p>', '2026-03-27 02:52:49', '2026-03-27 02:52:49'),
(409, 155, '<p>- Lựa chọn v&agrave; sử dụng được c&aacute;c dụng cụ đo như ampe kế, v&ocirc;n kế, cảm biến nhiệt độ, cảm biến &aacute;nh s&aacute;ng, c&aacute;c dụng cụ th&iacute; nghiệm kh&aacute;c để thu thập dữ liệu ch&iacute;nh x&aacute;c.</p>\r\n<p>- Ph&acirc;n t&iacute;ch xử l&iacute; được số liệu thực nghiệm.</p>\r\n<p>- Vẽ được đồ thị thực nghiệm v&agrave; đồ thị tuyến t&iacute;nh ho&aacute;.</p>\r\n<p>- Tr&igrave;nh b&agrave;y được b&aacute;o c&aacute;o th&iacute; nghiệm.</p>', '2026-03-27 02:52:59', '2026-03-27 02:52:59'),
(410, 30, '<p>Giải th&iacute;ch được c&aacute;c kh&aacute;i niệm c&acirc;n bằng nhiệt v&agrave; qu&aacute; tr&igrave;nh thuận nghịch.</p>', '2026-03-27 02:54:26', '2026-03-27 02:54:26'),
(411, 30, '<p>Định nghĩa v&agrave; m&ocirc; tả được kh&aacute;i niệm entropy; t&iacute;nh to&aacute;n được sự thay đổi entropy trong c&aacute;c qu&aacute; tr&igrave;nh nhiệt động học.</p>', '2026-03-27 02:54:45', '2026-03-27 02:54:45'),
(412, 30, '<p>Ph&acirc;n loại v&agrave; giải th&iacute;ch được c&aacute;c hệ thống mở, đ&oacute;ng v&agrave; c&ocirc; lập.</p>', '2026-03-27 02:54:59', '2026-03-27 02:54:59'),
(413, 135, '<p>Vận dụng được định luật thứ nhất của nhiệt động học để giải quyết c&aacute;c b&agrave;i tập cụ thể.</p>', '2026-03-27 02:55:21', '2026-03-27 02:55:21'),
(414, 135, '<p>Vận dụng được định luật thứ hai của nhiệt động học trong c&aacute;c b&agrave;i tập thực tế.</p>', '2026-03-27 02:55:31', '2026-03-27 02:55:31'),
(415, 135, '<p>M&ocirc; tả được chu kỳ Carnot; t&iacute;nh to&aacute;n được hiệu suất của chu kỳ Carnot tr&ecirc;n kh&iacute; l&iacute; tưởng.</p>', '2026-03-27 02:55:42', '2026-03-27 02:55:42'),
(416, 135, '<p>Giải th&iacute;ch v&agrave; ph&acirc;n t&iacute;ch được hiệu suất của c&aacute;c động cơ nhiệt kh&ocirc;ng l&iacute; tưởng.</p>', '2026-03-27 02:55:52', '2026-03-27 02:55:52'),
(417, 136, '<p>Hiểu v&agrave; m&ocirc; tả được l&iacute; thuyết động học của c&aacute;c chất kh&iacute; l&iacute; tưởng; t&iacute;nh to&aacute;n được c&aacute;c đại lượng li&ecirc;n quan như số Avogadro, hệ số Boltzmann v&agrave; hằng số kh&iacute;.</p>', '2026-03-27 02:56:11', '2026-03-27 02:56:11'),
(418, 136, '<p>M&ocirc; tả v&agrave; giải th&iacute;ch được chuyển động tịnh tiến của c&aacute;c ph&acirc;n tử v&agrave; &aacute;p suất trong chất kh&iacute; l&iacute; tưởng.</p>', '2026-03-27 02:56:23', '2026-03-27 02:56:23'),
(419, 136, '<p>Giải th&iacute;ch v&agrave; t&iacute;nh to&aacute;n được c&aacute;c bậc tự do tịnh tiến, quay v&agrave; dao động của ph&acirc;n tử.</p>', '2026-03-27 02:56:35', '2026-03-27 02:56:35'),
(420, 136, '<p>Vận dụng được ph&acirc;n bố chuẩn trong c&aacute;c hệ thống nhiệt động học.</p>', '2026-03-27 02:56:51', '2026-03-27 02:56:51'),
(421, 136, '<p>Vận dụng quy luật của c&aacute;c qu&aacute; tr&igrave;nh đẳng nhiệt, đẳng &aacute;p, đẳng t&iacute;ch v&agrave; đoạn nhiệt để giải b&agrave;i tập.</p>', '2026-03-27 02:57:05', '2026-03-27 02:57:05'),
(422, 136, '<p>Giải th&iacute;ch v&agrave; t&iacute;nh to&aacute;n được nhiệt dung của c&aacute;c qu&aacute; tr&igrave;nh đẳ,m ng &aacute;p v&agrave; đẳng t&iacute;ch.</p>', '2026-03-27 02:57:18', '2026-03-27 02:57:18'),
(423, 137, '<p>Tr&igrave;nh b&agrave;y được dạng t&iacute;ch ph&acirc;n của hệ phương tr&igrave;nh Maxwell.</p>', '2026-03-27 04:22:48', '2026-03-27 04:22:48'),
(424, 137, '<p>Vận dụng c&aacute;c phương tr&igrave;nh Maxwell để giải c&aacute;c b&agrave;i tập.</p>', '2026-03-27 04:22:57', '2026-03-27 04:22:57'),
(425, 137, '<p>Giải th&iacute;ch v&agrave; &aacute;p dụng được kh&aacute;i niệm d&ograve;ng điện xo&aacute;y trong c&aacute;c b&agrave;i tập điện từ học.</p>', '2026-03-27 04:23:11', '2026-03-27 04:23:11'),
(426, 137, '<p>Tr&igrave;nh b&agrave;y v&eacute;ctơ mật độ d&ograve;ng năng lượng (Poynting) trong truyền s&oacute;ng điện từ.</p>', '2026-03-27 04:23:23', '2026-03-27 04:23:23'),
(427, 138, '<p>Giải th&iacute;ch v&agrave; t&iacute;nh to&aacute;n được độ điện thẩm v&agrave; từ thẩm của c&aacute;c vật liệu điện v&agrave; từ.</p>', '2026-03-27 04:23:43', '2026-03-27 04:23:43'),
(428, 138, '<p>Giải th&iacute;ch được kh&aacute;i niệm độ điện thẩm v&agrave; từ thẩm tương đối của c&aacute;c vật liệu điện v&agrave; từ.</p>', '2026-03-27 04:26:09', '2026-03-27 04:26:09'),
(429, 138, '<p>T&iacute;nh to&aacute;n v&agrave; giải th&iacute;ch được mật độ năng lượng của c&aacute;c trường điện v&agrave; từ.</p>', '2026-03-27 04:26:28', '2026-03-27 04:26:28'),
(430, 138, '<p>Tr&igrave;nh b&agrave;y được đường cong t&aacute;n sắc của qu&aacute; tr&igrave;nh truyền s&oacute;ng điện từ qua c&aacute;c m&ocirc;i trường.</p>', '2026-03-27 04:26:38', '2026-03-27 04:26:38'),
(431, 139, '<p>Giải th&iacute;ch được định luật Planck.</p>', '2026-03-27 04:27:00', '2026-03-27 04:27:00'),
(432, 139, '<p>M&ocirc; tả c&aacute;c ứng dụng của định luật Planck trong c&aacute;c hiện tượng vật l&iacute;.</p>', '2026-03-27 04:27:12', '2026-03-27 04:27:12'),
(433, 140, '<p>Ph&aacute;t biểu được định luật dịch chuyển Wien.</p>', '2026-03-27 04:27:33', '2026-03-27 04:27:33'),
(434, 140, '<p>&Aacute;p dụng được định luật dịch chuyển Wien trong c&aacute;c b&agrave;i tập.</p>', '2026-03-27 04:27:49', '2026-03-27 04:27:49'),
(435, 141, '<p>Ph&aacute;t biểu được định luật Stefan-Boltzmann.</p>', '2026-03-27 04:28:05', '2026-03-27 04:28:05'),
(436, 141, '<p>&Aacute;p dụng được định luật Stefan-Boltzmann trong c&aacute;c b&agrave;i tập.</p>', '2026-03-27 04:28:14', '2026-03-27 04:28:14'),
(437, 142, '<p>Tr&igrave;nh b&agrave;y được bước s&oacute;ng de Broglie</p>', '2026-03-27 04:29:35', '2026-03-27 04:29:35'),
(438, 142, '<p>Giải th&iacute;ch mối quan hệ giữa tần số v&agrave; năng lượng, giữa vector s&oacute;ng v&agrave; động lượng.</p>', '2026-03-27 04:29:47', '2026-03-27 04:29:47'),
(439, 142, '<p>T&iacute;nh to&aacute;n c&aacute;c mức năng lượng của nguy&ecirc;n tử Hydrogen.</p>', '2026-03-27 04:29:57', '2026-03-27 04:29:57'),
(440, 143, '<p>Tr&igrave;nh b&agrave;y được c&aacute;c nguy&ecirc;n l&iacute; bất định Heisenberg.</p>', '2026-03-27 04:30:13', '2026-03-27 04:30:13'),
(441, 144, '<p>Giải th&iacute;ch phổ ph&aacute;t xạ v&agrave; hấp thụ của c&aacute;c nguy&ecirc;n tử hydrogen v&agrave; c&aacute;c nguy&ecirc;n tử kh&aacute;c một c&aacute;ch định t&iacute;nh.</p>', '2026-03-27 04:30:54', '2026-03-27 04:30:54'),
(442, 144, '<p>M&ocirc; tả phổ ph&aacute;t xạ v&agrave; hấp thụ của c&aacute;c ph&acirc;n tử do dao động ph&acirc;n tử.</p>', '2026-03-27 04:31:14', '2026-03-27 04:31:14'),
(443, 144, '<p>T&iacute;nh to&aacute;n độ rộng phổ v&agrave; tuổi thọ của c&aacute;c trạng th&aacute;i k&iacute;ch th&iacute;ch.</p>', '2026-03-27 04:31:29', '2026-03-27 04:31:29'),
(444, 145, '<p>Tr&igrave;nh b&agrave;y được nguy&ecirc;n l&iacute; Pauli</p>', '2026-03-27 04:31:46', '2026-03-27 04:31:46'),
(445, 145, '<p>&Aacute;p dụng nguy&ecirc;n l&iacute; loại trừ Pauli cho c&aacute;c hạt Fermi.</p>', '2026-03-27 04:32:01', '2026-03-27 04:32:01'),
(446, 145, '<p>Ph&acirc;n biệt c&aacute;c hạt electron, neutrino electron, proton, neutron, photon về mặt điện t&iacute;ch v&agrave; spin.</p>', '2026-03-27 04:32:13', '2026-03-27 04:32:13'),
(447, 146, '<p>Lập kế hoạch, thực hiện t&igrave;m hiểu v&agrave; b&aacute;o c&aacute;o kết quả về c&aacute;c phương ph&aacute;p ph&acirc;n t&iacute;ch phổ (phổ tia X, phổ Raman..) trong khoa học vật liệu.</p>', '2026-03-27 04:32:37', '2026-03-27 04:32:37');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Vật Lí', NULL, '2026-03-28 05:38:22', '2026-03-28 05:38:22'),
(2, 'Toán học', NULL, '2026-03-28 05:38:22', '2026-03-28 05:38:22'),
(3, 'Hóa học', NULL, '2026-03-28 05:38:22', '2026-03-28 05:38:22');

-- --------------------------------------------------------

--
-- Table structure for table `topics`
--

CREATE TABLE `topics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subject_id` bigint(20) UNSIGNED DEFAULT NULL,
  `topic_type_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `grade` tinyint(4) NOT NULL,
  `order` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `total_periods` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `topics`
--

INSERT INTO `topics` (`id`, `subject_id`, `topic_type_id`, `name`, `grade`, `order`, `total_periods`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Động học', 10, 2, 16, '2026-03-23 04:59:50', '2026-03-24 04:28:13'),
(2, 1, 2, 'Vật lý trong một số ngành nghề', 10, 16, 10, '2026-03-23 04:59:50', '2026-03-24 04:52:52'),
(5, 1, 1, 'Mở đầu', 10, 1, 4, '2026-03-23 07:27:11', '2026-03-23 07:27:11'),
(6, 1, 1, 'Dao động', 11, 1, 14, '2026-03-23 18:37:46', '2026-03-24 05:19:33'),
(7, 1, 1, 'Sóng', 11, 2, 16, '2026-03-23 18:38:21', '2026-03-24 05:19:53'),
(8, 1, 1, 'Động lực học', 10, 3, 18, '2026-03-24 04:28:52', '2026-03-24 04:28:52'),
(9, 1, 1, 'Công, Năng lượng, Công suất', 10, 4, 10, '2026-03-24 04:29:44', '2026-03-24 04:29:44'),
(10, 1, 1, 'Động lượng', 10, 5, 6, '2026-03-24 04:30:32', '2026-03-24 04:30:32'),
(11, 1, 1, 'Chuyển động tròn', 10, 6, 4, '2026-03-24 04:31:20', '2026-03-24 04:31:20'),
(12, 1, 1, 'Biến dạng vật rắn', 10, 7, 4, '2026-03-24 04:32:11', '2026-03-24 04:32:11'),
(13, 1, 1, 'Kiểm tra giữa kì 1', 10, 9, 1, '2026-03-24 04:39:16', '2026-03-24 04:42:48'),
(14, 1, 1, 'Ôn tập giữa kì 1', 10, 8, 1, '2026-03-24 04:43:19', '2026-03-24 04:43:19'),
(15, 1, 1, 'Ôn tập cuối kì 1', 10, 10, 1, '2026-03-24 04:43:55', '2026-03-24 04:47:23'),
(16, 1, 1, 'Kiểm tra cuối kì 1', 10, 11, 1, '2026-03-24 04:46:24', '2026-03-24 04:47:39'),
(17, 1, 1, 'Ôn tập giữa kì 2', 10, 12, 1, '2026-03-24 04:47:58', '2026-03-24 04:47:58'),
(18, 1, 1, 'Kiểm tra giữa kì 2', 10, 13, 1, '2026-03-24 04:48:20', '2026-03-24 04:48:20'),
(19, 1, 1, 'Ôn tập cuối kì 2', 10, 14, 1, '2026-03-24 04:48:44', '2026-03-24 04:48:44'),
(20, 1, 1, 'Kiểm tra cuối kì 2', 10, 15, 1, '2026-03-24 04:49:01', '2026-03-24 04:49:01'),
(21, 1, 2, 'Trái Đất và bầu trời', 10, 17, 10, '2026-03-24 04:54:08', '2026-03-24 04:54:08'),
(22, 1, 2, 'Vật lý với giáo dục về bảo vệ môi trường', 10, 18, 15, '2026-03-24 04:55:45', '2026-03-24 04:55:45'),
(23, 1, 3, 'Lý thuyết đo lường', 10, 19, 4, '2026-03-24 05:10:01', '2026-03-24 05:10:22'),
(24, 1, 3, 'Cơ học chất điểm. Chuyển động các thiên thể', 10, 20, 14, '2026-03-24 05:11:09', '2026-03-24 05:11:09'),
(25, 1, 3, 'Động học vật rắn', 10, 21, 8, '2026-03-24 05:11:34', '2026-03-24 05:14:18'),
(26, 1, 3, 'Động lực học vật rắn', 10, 22, 10, '2026-03-24 05:12:35', '2026-03-24 05:12:35'),
(27, 1, 3, 'Hệ qui chiếu phi quán tính', 10, 23, 8, '2026-03-24 05:15:07', '2026-03-24 05:15:07'),
(28, 1, 3, 'Thực hành thí nghiệm 10 (Chọn 3 trong 10 nội dung)', 10, 24, 11, '2026-03-24 05:16:01', '2026-03-24 05:16:01'),
(29, 1, 1, 'Trường Điện (Điện trường)', 11, 3, 18, '2026-03-24 05:20:40', '2026-03-24 05:20:40'),
(30, 1, 1, 'Dòng điện - Mạch điện', 11, 4, 14, '2026-03-24 05:21:15', '2026-03-24 05:21:15'),
(31, 1, 1, 'Ôn tập giữa kì 1', 11, 5, 1, '2026-03-24 05:23:28', '2026-03-24 05:23:28'),
(32, 1, 1, 'Kiểm tra giữa kì 1', 11, 6, 1, '2026-03-24 05:23:48', '2026-03-24 05:23:48'),
(33, 1, 1, 'Ôn tập cuối kì 1', 11, 7, 1, '2026-03-24 05:24:33', '2026-03-24 05:25:37'),
(34, 1, 1, 'Kiểm tra cuối kì 1', 11, 8, 1, '2026-03-24 05:25:03', '2026-03-24 05:25:58'),
(35, 1, 1, 'Ôn tập giữa kì 2', 11, 9, 1, '2026-03-24 05:26:44', '2026-03-24 05:26:44'),
(36, 1, 1, 'Kiểm tra giữa kì 2', 11, 10, 1, '2026-03-24 05:27:13', '2026-03-24 05:27:13'),
(37, 1, 1, 'Ôn tập cuối kì 2', 11, 11, 1, '2026-03-24 05:28:10', '2026-03-24 05:28:10'),
(38, 1, 1, 'Kiểm tra cuối kì 2', 11, 12, 1, '2026-03-24 05:28:26', '2026-03-24 05:28:26'),
(39, 1, 2, 'Trường Hấp dẫn', 11, 13, 15, '2026-03-24 05:44:34', '2026-03-24 05:44:34'),
(40, 1, 2, 'Truyền thông tin bằng sóng vô tuyến', 11, 14, 10, '2026-03-24 05:45:13', '2026-03-24 05:45:31'),
(41, 1, 2, 'Mở đầu về điện tử học', 11, 15, 10, '2026-03-24 05:46:52', '2026-03-24 05:46:52'),
(42, 1, 3, 'Dao động và sóng', 11, 16, 8, '2026-03-24 05:48:00', '2026-03-24 05:48:00'),
(43, 1, 3, 'Quang học', 11, 17, 8, '2026-03-24 05:48:35', '2026-03-24 05:48:35'),
(44, 1, 3, 'Trường điện - Trường từ', 11, 18, 10, '2026-03-24 05:49:13', '2026-03-24 05:49:41'),
(45, 1, 3, 'Mạch điện', 11, 19, 8, '2026-03-24 05:50:18', '2026-03-24 05:50:18'),
(46, 1, 3, 'Thuyết tương đối', 11, 20, 8, '2026-03-24 05:50:54', '2026-03-24 05:50:54'),
(47, 1, 3, 'Thực hành thí nghiệm vật lí 11 (Tự chọn tối thiểu 3 nội dung trong 11 nội dung)', 11, 21, 11, '2026-03-24 05:53:56', '2026-03-24 05:53:56'),
(48, 1, 1, 'Vật lý nhiệt', 12, 1, 14, '2026-03-24 05:59:26', '2026-03-24 05:59:26'),
(49, 1, 1, 'Khí lý tưởng', 12, 2, 12, '2026-03-24 06:00:51', '2026-03-24 06:00:51'),
(50, 1, 1, 'Từ trường', 12, 3, 18, '2026-03-24 06:01:40', '2026-03-24 06:01:40'),
(51, 1, 1, 'Vật lý hạt nhân và phóng xạ', 12, 4, 16, '2026-03-24 06:02:22', '2026-03-24 06:02:22'),
(52, 1, 1, 'Ôn tập giữa kì 1', 12, 5, 1, '2026-03-24 06:03:18', '2026-03-24 06:03:18'),
(53, 1, 1, 'Kiểm tra giữa kì 1', 12, 6, 1, '2026-03-24 06:03:39', '2026-03-24 06:03:39'),
(54, 1, 1, 'Ôn tập cuối kì 1', 12, 7, 2, '2026-03-24 06:05:17', '2026-03-24 06:05:31'),
(55, 1, 1, 'Kiểm tra cuối kì 1', 12, 8, 1, '2026-03-24 06:06:44', '2026-03-24 06:06:44'),
(56, 1, 1, 'Ôn tập giữa kì 2', 12, 9, 1, '2026-03-24 06:07:24', '2026-03-24 06:07:24'),
(57, 1, 1, 'Kiểm tra giữa kì 2', 12, 10, 1, '2026-03-24 06:07:54', '2026-03-24 06:08:10'),
(58, 1, 1, 'Ôn tập cuối kì 2', 12, 11, 2, '2026-03-24 06:08:48', '2026-03-24 06:08:48'),
(59, 1, 1, 'Kiểm tra cuối kì 2', 12, 12, 1, '2026-03-24 06:09:07', '2026-03-24 06:09:07'),
(60, 1, 2, 'Dòng điện xoay chiều', 12, 13, 10, '2026-03-24 06:11:12', '2026-03-24 06:11:30'),
(61, 1, 2, 'Một số ứng dụng Vật lí trong chuẩn đoán y học', 12, 14, 10, '2026-03-24 06:12:50', '2026-03-24 06:13:29'),
(62, 1, 2, 'Vật lý lượng tử', 12, 15, 15, '2026-03-24 06:15:17', '2026-03-24 06:15:17'),
(63, 1, 3, 'Nhiệt động lực học', 12, 16, 12, '2026-03-24 06:16:50', '2026-03-24 06:18:12'),
(64, 1, 3, 'Bức xạ điện từ', 12, 17, 16, '2026-03-24 06:18:36', '2026-03-24 06:18:36'),
(65, 1, 3, 'Vật lý lượng tử', 12, 18, 8, '2026-03-24 06:19:17', '2026-03-24 06:19:30'),
(66, 1, 3, 'Cấu trúc vật chất', 12, 19, 8, '2026-03-24 06:20:23', '2026-03-24 06:20:40'),
(67, 1, 3, 'Thực hành thí nghiệm 12 (Tự chọn tối thiểu 3 nội dung trong các nội dung thực hành)', 12, 20, 11, '2026-03-24 06:21:22', '2026-03-24 06:21:22');

-- --------------------------------------------------------

--
-- Table structure for table `topic_types`
--

CREATE TABLE `topic_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `topic_types`
--

INSERT INTO `topic_types` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Cơ bản', 'Nội dung cốt lõi', '2026-03-23 04:59:50', '2026-03-23 04:59:50'),
(2, 'Nâng cao', 'Chuyên đề học tập tự chọn (học sinh lớp không chuyên có thể chọn học hay không chuyên đề này).', '2026-03-23 04:59:50', '2026-03-23 06:13:30'),
(3, 'Chuyên', 'Bắt buộc đối với học sinh các lớp chuyên Vật lý.', '2026-03-23 04:59:50', '2026-03-23 04:59:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contents`
--
ALTER TABLE `contents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contents_topic_id_foreign` (`topic_id`);

--
-- Indexes for table `objectives`
--
ALTER TABLE `objectives`
  ADD PRIMARY KEY (`id`),
  ADD KEY `objectives_content_id_foreign` (`content_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `topics`
--
ALTER TABLE `topics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `topics_topic_type_id_foreign` (`topic_type_id`),
  ADD KEY `fk_topics_subject` (`subject_id`);

--
-- Indexes for table `topic_types`
--
ALTER TABLE `topic_types`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contents`
--
ALTER TABLE `contents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=156;

--
-- AUTO_INCREMENT for table `objectives`
--
ALTER TABLE `objectives`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=448;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `topics`
--
ALTER TABLE `topics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `topic_types`
--
ALTER TABLE `topic_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contents`
--
ALTER TABLE `contents`
  ADD CONSTRAINT `contents_topic_id_foreign` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `objectives`
--
ALTER TABLE `objectives`
  ADD CONSTRAINT `objectives_content_id_foreign` FOREIGN KEY (`content_id`) REFERENCES `contents` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `topics`
--
ALTER TABLE `topics`
  ADD CONSTRAINT `fk_topics_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`),
  ADD CONSTRAINT `topics_topic_type_id_foreign` FOREIGN KEY (`topic_type_id`) REFERENCES `topic_types` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
