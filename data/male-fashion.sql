-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 23, 2024 lúc 11:46 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `male-fashion`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT 'Admin',
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `email`, `password`, `role`, `status`, `created_at`) VALUES
(2, 'Supper Admin', 'admin@gmail.com', '$2y$10$B14PUwN6zvApNy8ikz3LB.AK6MmF1z0F0glmqbtAgcDTGoULp.HxG', 'Super Admin', 1, '2024-10-13 08:59:08'),
(15, 'nhanvien1', 'nhanvien1@gmail.com', '$2y$10$QBU/UlK4kxIPBPSXfu8oqeSE6BZRIyF45pNalsXfSUaRdIVMaeYPC', 'Admin', 1, '2024-10-16 14:01:47'),
(16, 'nhanvien2', 'nhanvien2@gmail.com', '$2y$10$Yw5ZHfCRiq936ZhSuu7a1OnyU5UHWf5Y3CI0SSccOqmXHFFjC0UVu', 'Editor', 1, '2024-10-16 14:02:52'),
(17, 'nhanvien3 ', 'nhanvien3@gmail.com', '$2y$10$Vcvh8dSepUmr8nT7U7cwPeuBda0kyOSXxa..LvHHe4OPDBAJbuMU2', 'Viewer', 1, '2024-10-16 14:04:01'),
(21, 'employee1', 'employee1@example.com', 'hashed_password1', 'Admin', 1, '2024-10-16 16:36:51'),
(23, 'employee3', 'employee3@example.com', 'hashed_password3', 'Admin', 1, '2024-10-16 16:36:51');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `brands`
--

CREATE TABLE `brands` (
  `brand_id` int(11) NOT NULL,
  `brand_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `brands`
--

INSERT INTO `brands` (`brand_id`, `brand_name`, `description`, `logo`, `created_at`) VALUES
(3, 'Prada', NULL, NULL, '2024-10-17 15:37:35'),
(4, 'Louis Vuitton', NULL, NULL, '2024-10-17 15:37:47'),
(5, 'Dior', NULL, NULL, '2024-10-17 15:38:04'),
(6, 'Ralph Lauren', NULL, NULL, '2024-10-17 15:38:12');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart_items`
--

CREATE TABLE `cart_items` (
  `cart_item_id` int(11) NOT NULL,
  `cart_id` int(11) DEFAULT NULL,
  `variant_id` int(11) DEFAULT NULL,
  `sku_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `description`, `created_at`) VALUES
(1, 'Thời trang nam', NULL, '2024-09-29 04:20:02'),
(2, 'Thời trang nữ', NULL, '2024-09-29 04:20:02'),
(6, 'Phụ kiên', NULL, '2024-10-19 14:17:32');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `colors`
--

CREATE TABLE `colors` (
  `color_id` int(11) NOT NULL,
  `color_name` varchar(50) NOT NULL,
  `color_code` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `colors`
--

INSERT INTO `colors` (`color_id`, `color_name`, `color_code`) VALUES
(1, 'Đen', 'black'),
(2, 'Trắng', 'white'),
(3, 'Xanh nhạt', 'lightblue'),
(4, 'Đỏ', 'red'),
(5, 'Hồng', 'pink'),
(6, 'Xám', 'grey'),
(7, 'Vàng', 'yellow'),
(8, 'Nâu', 'brown');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `shipping_address` text DEFAULT NULL,
  `billing_address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `customers`
--

INSERT INTO `customers` (`customer_id`, `first_name`, `last_name`, `email`, `phone_number`, `password`, `shipping_address`, `billing_address`, `created_at`) VALUES
(2, 'Nguyễn', 'Hùng', 'Hung@gmail.com', '0961919603', '$2y$10$z.AUck7q1Tra35pDnP4iTOxLp0M.4dYhyH3NxmqQo1swv5fyl1Uhy', 'hoàng mai hà nội ', 'Nam định ', '2024-09-29 16:45:29'),
(3, 'Leroy', 'Bender', 'test@gmail.com', '362-9474', '$2y$10$o/k/d9gRBdHtCcDZKykOl.1dm/V7jbZ02Zo5DxCTCSax4Pn9ao5he', 'hà nội', NULL, '2024-10-07 02:37:01'),
(6, 'John', 'Doe', 'john.doe1@example.com', '0912345678', '$2y$10$1yPCs2cqbgQ3O1xpWKIzTO8et/poREpN36tTkmYsKAiI4aXiqShZO', '123 Main St', '456 Another St', '2024-10-16 16:37:08'),
(9, 'Bob', 'Davis', 'bob.davis4@example.com', '0945678901', 'hashed_password4', '101 Elm St', '202 Birch St', '2024-10-16 16:37:08'),
(10, 'Charlie', 'Miller', 'charlie.miller5@example.com', '0956789012', '$2y$10$WNBK9d6rzjdlo5MJrX4BMeF.wtKHvJpnmZKe4nRL.tiHl2zcccEKS', '202 Spruce St', '303 Poplar St', '2024-10-16 16:37:08'),
(11, 'Daisy', 'Johnson', 'daisy.johnson6@example.com', '0967890123', 'hashed_password6', '303 Fir St', '404 Ash St', '2024-10-16 16:37:08'),
(12, 'Eve', 'White', 'eve.white7@example.com', '0978901234', 'hashed_password7', '404 Palm St', '505 Palm St', '2024-10-16 16:37:08');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `shipping_address` text DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`order_id`, `customer_id`, `order_date`, `total`, `status`, `shipping_address`, `payment_method`) VALUES
(74, 2, '2024-10-22 15:09:50', 280000.00, 'Pending', 'hoàng mai hà nội ', 'COD'),
(75, 2, '2024-10-22 15:14:31', 280000.00, 'Delivered', 'hoàng mai hà nội ', 'COD'),
(78, 2, '2024-10-22 15:34:13', 674000.00, 'Shipped', 'Giao Thủy Nam Định', 'COD'),
(79, 2, '2024-10-22 16:27:59', 674000.00, 'Cancelled', 'hoàng mai hà nội ', 'COD'),
(80, 6, '2024-10-23 05:49:57', 355000.00, 'Pending', '123 Main St', 'COD'),
(81, 10, '2024-10-23 07:22:25', 352000.00, 'Pending', '202 Spruce St', 'COD');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `variant_id` int(11) DEFAULT NULL,
  `sku_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `size` varchar(100) DEFAULT NULL,
  `color` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `variant_id`, `sku_id`, `quantity`, `price`, `size`, `color`) VALUES
(65, 74, 381, 53, 2, 125000.00, NULL, NULL),
(66, 75, 381, 53, 2, 125000.00, NULL, NULL),
(67, 78, 390, 62, 2, 322000.00, NULL, NULL),
(68, 79, 390, 62, 1, 322000.00, NULL, NULL),
(69, 79, 391, 63, 1, 322000.00, NULL, NULL),
(70, 80, 385, 57, 1, 325000.00, NULL, NULL),
(71, 81, 390, 62, 1, 322000.00, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(100) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_best_seller` tinyint(1) DEFAULT 0,
  `is_new_arrival` tinyint(1) DEFAULT 0,
  `is_hot_sale` tinyint(1) DEFAULT 0,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `avg_rating` decimal(3,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `category_id`, `description`, `price`, `product_image`, `created_at`, `is_best_seller`, `is_new_arrival`, `is_hot_sale`, `sale_price`, `brand_id`, `avg_rating`) VALUES
(63, 'polo cổ vịt', 1, 'Thông Tin Về Sản Phẩm\r\n\r\nPHOM DÁNG: Slim / Regular\r\n\r\n- Áo phom Slim có độ ôm vừa vặn, tôn dáng cơ thể mang đến cho người mặc sự trẻ trung và năng động\r\n\r\n- Thiết kế bo cổ và tay áo độc đáo, kết hợp với đường chỉ may chắc chắn và sắc nét góp phần tôn lên sự trẻ trung và thanh lịch của người mặc\r\n\r\nCHẤT LIỆU:\r\n\r\n- Chất liệu Cotton Mercerized cho vải áo bóng mịn, thoáng mát, kháng khuẩn và khử mùi mang tới cảm giác dễ chịu do độ thấm hút mồ hôi nhanh chóng. Với công nghệ xử lý hiện đại, chất liệu vải này sở hữu độ bền vượt trội, hạn chế tối đa hiện tượng xơ, xổ vải, nấm mốc.\r\n\r\n- Vải có khả năng chống nhăn tốt, độ đàn hồi cao giúp cho người mặc luôn thoải mái và dễ dàng vận động.\r\n\r\n- Vải dệt từ 100% Cotton Mercerized mềm mại, bền màu và đồng thời không cầu kỳ trong việc chăm sóc vải.\r\n\r\nMÀU SẮC: Đen\r\n\r\nSIZE: S - M - L - XL - 2XL', 359000.00, 'assets/img/product/6715490e36e3a.jpg', '2024-10-20 11:20:23', 0, 1, 0, NULL, 3, 0.00),
(64, 'Polo cổ cứng ', 1, 'Thông Tin Về Sản Phẩm\r\n\r\nPHOM DÁNG: Slim / Regular\r\n\r\n- Áo phom Slim có độ ôm vừa vặn, tôn dáng cơ thể mang đến cho người mặc sự trẻ trung và năng động\r\n\r\n- Thiết kế bo cổ và tay áo độc đáo, kết hợp với đường chỉ may chắc chắn và sắc nét góp phần tôn lên sự trẻ trung và thanh lịch của người mặc\r\n\r\nCHẤT LIỆU:\r\n\r\n- Chất liệu Cotton Mercerized cho vải áo bóng mịn, thoáng mát, kháng khuẩn và khử mùi mang tới cảm giác dễ chịu do độ thấm hút mồ hôi nhanh chóng. Với công nghệ xử lý hiện đại, chất liệu vải này sở hữu độ bền vượt trội, hạn chế tối đa hiện tượng xơ, xổ vải, nấm mốc.\r\n\r\n- Vải có khả năng chống nhăn tốt, độ đàn hồi cao giúp cho người mặc luôn thoải mái và dễ dàng vận động.\r\n\r\n- Vải dệt từ 100% Cotton Mercerized mềm mại, bền màu và đồng thời không cầu kỳ trong việc chăm sóc vải.\r\n\r\nMÀU SẮC: Đen\r\n\r\nSIZE: S - M - L - XL - 2XL', 359200.00, 'assets/img/product/6718931bb26a7.jpg', '2024-10-20 11:36:12', 0, 0, 1, NULL, 3, 0.00),
(65, 'Polo Cổ mềm ', 1, 'Thông Tin Về Sản Phẩm\r\n\r\nPHOM DÁNG: Slim / Regular\r\n\r\n- Áo phom Slim có độ ôm vừa vặn, tôn dáng cơ thể mang đến cho người mặc sự trẻ trung và năng động\r\n\r\n- Thiết kế bo cổ và tay áo độc đáo, kết hợp với đường chỉ may chắc chắn và sắc nét góp phần tôn lên sự trẻ trung và thanh lịch của người mặc\r\n\r\nCHẤT LIỆU:\r\n\r\n- Chất liệu Cotton Mercerized cho vải áo bóng mịn, thoáng mát, kháng khuẩn và khử mùi mang tới cảm giác dễ chịu do độ thấm hút mồ hôi nhanh chóng. Với công nghệ xử lý hiện đại, chất liệu vải này sở hữu độ bền vượt trội, hạn chế tối đa hiện tượng xơ, xổ vải, nấm mốc.\r\n\r\n- Vải có khả năng chống nhăn tốt, độ đàn hồi cao giúp cho người mặc luôn thoải mái và dễ dàng vận động.\r\n\r\n- Vải dệt từ 100% Cotton Mercerized mềm mại, bền màu và đồng thời không cầu kỳ trong việc chăm sóc vải.\r\n\r\nMÀU SẮC: Đen\r\n\r\nSIZE: S - M - L - XL - 2XL', 453000.00, 'assets/img/product/671548d8969b0.jpg', '2024-10-20 11:41:24', 0, 0, 1, NULL, 3, 0.00),
(66, 'Polo cổ đứng ', 1, 'Thông Tin Về Sản Phẩm\r\n\r\nPHOM DÁNG: Slim / Regular\r\n\r\n- Áo phom Slim có độ ôm vừa vặn, tôn dáng cơ thể mang đến cho người mặc sự trẻ trung và năng động\r\n\r\n- Thiết kế bo cổ và tay áo độc đáo, kết hợp với đường chỉ may chắc chắn và sắc nét góp phần tôn lên sự trẻ trung và thanh lịch của người mặc\r\n\r\nCHẤT LIỆU:\r\n\r\n- Chất liệu Cotton Mercerized cho vải áo bóng mịn, thoáng mát, kháng khuẩn và khử mùi mang tới cảm giác dễ chịu do độ thấm hút mồ hôi nhanh chóng. Với công nghệ xử lý hiện đại, chất liệu vải này sở hữu độ bền vượt trội, hạn chế tối đa hiện tượng xơ, xổ vải, nấm mốc.\r\n\r\n- Vải có khả năng chống nhăn tốt, độ đàn hồi cao giúp cho người mặc luôn thoải mái và dễ dàng vận động.\r\n\r\n- Vải dệt từ 100% Cotton Mercerized mềm mại, bền màu và đồng thời không cầu kỳ trong việc chăm sóc vải.\r\n\r\nMÀU SẮC: Đen\r\n\r\nSIZE: S - M - L - XL - 2XL', 289000.00, 'assets/img/product/671548ca765fb.jpg', '2024-10-20 11:50:09', 0, 1, 0, NULL, 3, 0.00),
(67, 'Áo phông trơn ', 1, 'Thông Tin Về Sản Phẩm\r\n\r\nPHOM DÁNG: Slim / Regular\r\n\r\n- Áo phom Slim có độ ôm vừa vặn, tôn dáng cơ thể mang đến cho người mặc sự trẻ trung và năng động\r\n\r\n- Thiết kế bo cổ và tay áo độc đáo, kết hợp với đường chỉ may chắc chắn và sắc nét góp phần tôn lên sự trẻ trung và thanh lịch của người mặc\r\n\r\nCHẤT LIỆU:\r\n\r\n- Chất liệu Cotton Mercerized cho vải áo bóng mịn, thoáng mát, kháng khuẩn và khử mùi mang tới cảm giác dễ chịu do độ thấm hút mồ hôi nhanh chóng. Với công nghệ xử lý hiện đại, chất liệu vải này sở hữu độ bền vượt trội, hạn chế tối đa hiện tượng xơ, xổ vải, nấm mốc.\r\n\r\n- Vải có khả năng chống nhăn tốt, độ đàn hồi cao giúp cho người mặc luôn thoải mái và dễ dàng vận động.\r\n\r\n- Vải dệt từ 100% Cotton Mercerized mềm mại, bền màu và đồng thời không cầu kỳ trong việc chăm sóc vải.\r\n\r\nMÀU SẮC: Đen\r\n\r\nSIZE: S - M - L - XL - 2XL', 125000.00, 'assets/img/product/671548bd56f58.jpg', '2024-10-20 11:53:13', 1, 0, 0, NULL, 3, 0.00),
(68, 'Polo Nam Fresh Max MPO', 1, 'Thông Tin Về Sản Phẩm\r\n\r\nPHOM DÁNG: Slim / Regular\r\n\r\n- Áo phom Slim có độ ôm vừa vặn, tôn dáng cơ thể mang đến cho người mặc sự trẻ trung và năng động\r\n\r\n- Thiết kế bo cổ và tay áo độc đáo, kết hợp với đường chỉ may chắc chắn và sắc nét góp phần tôn lên sự trẻ trung và thanh lịch của người mặc\r\n\r\nCHẤT LIỆU:\r\n\r\n- Chất liệu Cotton Mercerized cho vải áo bóng mịn, thoáng mát, kháng khuẩn và khử mùi mang tới cảm giác dễ chịu do độ thấm hút mồ hôi nhanh chóng. Với công nghệ xử lý hiện đại, chất liệu vải này sở hữu độ bền vượt trội, hạn chế tối đa hiện tượng xơ, xổ vải, nấm mốc.\r\n\r\n- Vải có khả năng chống nhăn tốt, độ đàn hồi cao giúp cho người mặc luôn thoải mái và dễ dàng vận động.\r\n\r\n- Vải dệt từ 100% Cotton Mercerized mềm mại, bền màu và đồng thời không cầu kỳ trong việc chăm sóc vải.\r\n\r\nMÀU SẮC: Đen\r\n\r\nSIZE: S - M - L - XL - 2XL', 236000.00, 'assets/img/product/671548b16cb70.jpg', '2024-10-20 11:55:44', 0, 0, 1, NULL, 3, 0.00),
(69, 'Thun Nam Green Ex Graphic Typo Outdoor MTS', 1, 'Thông Tin Về Sản Phẩm\r\n\r\nPHOM DÁNG: Slim / Regular\r\n\r\n- Áo phom Slim có độ ôm vừa vặn, tôn dáng cơ thể mang đến cho người mặc sự trẻ trung và năng động\r\n\r\n- Thiết kế bo cổ và tay áo độc đáo, kết hợp với đường chỉ may chắc chắn và sắc nét góp phần tôn lên sự trẻ trung và thanh lịch của người mặc\r\n\r\nCHẤT LIỆU:\r\n\r\n- Chất liệu Cotton Mercerized cho vải áo bóng mịn, thoáng mát, kháng khuẩn và khử mùi mang tới cảm giác dễ chịu do độ thấm hút mồ hôi nhanh chóng. Với công nghệ xử lý hiện đại, chất liệu vải này sở hữu độ bền vượt trội, hạn chế tối đa hiện tượng xơ, xổ vải, nấm mốc.\r\n\r\n- Vải có khả năng chống nhăn tốt, độ đàn hồi cao giúp cho người mặc luôn thoải mái và dễ dàng vận động.\r\n\r\n- Vải dệt từ 100% Cotton Mercerized mềm mại, bền màu và đồng thời không cầu kỳ trong việc chăm sóc vải.\r\n\r\nMÀU SẮC: Đen\r\n\r\nSIZE: S - M - L - XL - 2XL', 321000.00, 'assets/img/product/671548a422fe0.jpg', '2024-10-20 12:25:37', 0, 1, 0, NULL, 3, 0.00),
(70, 'ÁO THUN COUPLE TX', 1, 'Thông Tin Về Sản Phẩm\r\n\r\nPHOM DÁNG: Slim / Regular\r\n\r\n- Áo phom Slim có độ ôm vừa vặn, tôn dáng cơ thể mang đến cho người mặc sự trẻ trung và năng động\r\n\r\n- Thiết kế bo cổ và tay áo độc đáo, kết hợp với đường chỉ may chắc chắn và sắc nét góp phần tôn lên sự trẻ trung và thanh lịch của người mặc\r\n\r\nCHẤT LIỆU:\r\n\r\n- Chất liệu Cotton Mercerized cho vải áo bóng mịn, thoáng mát, kháng khuẩn và khử mùi mang tới cảm giác dễ chịu do độ thấm hút mồ hôi nhanh chóng. Với công nghệ xử lý hiện đại, chất liệu vải này sở hữu độ bền vượt trội, hạn chế tối đa hiện tượng xơ, xổ vải, nấm mốc.\r\n\r\n- Vải có khả năng chống nhăn tốt, độ đàn hồi cao giúp cho người mặc luôn thoải mái và dễ dàng vận động.\r\n\r\n- Vải dệt từ 100% Cotton Mercerized mềm mại, bền màu và đồng thời không cầu kỳ trong việc chăm sóc vải.\r\n\r\nMÀU SẮC: Đen\r\n\r\nSIZE: S - M - L - XL - 2XL', 239000.00, 'assets/img/product/6715489af3708.jpg', '2024-10-20 14:20:47', 0, 1, 0, 220000.00, 3, 0.00),
(71, 'sịp lỏ ', 1, 'ádasd', 325000.00, 'assets/img/product/67154876dbcfc.jpg', '2024-10-20 17:26:04', 0, 1, 0, NULL, 5, 0.00),
(72, 'áo lót ', 1, 'ádasd', 123321.00, 'assets/img/product/6715485399e7a.jpg', '2024-10-20 17:26:44', 0, 0, 0, NULL, 4, 3.33),
(74, 'quần bò nam ', 1, 'Thông Tin Về Sản Phẩm\r\n\r\nPHOM DÁNG: Slim / Regular\r\n\r\n- Áo phom Slim có độ ôm vừa vặn, tôn dáng cơ thể mang đến cho người mặc sự trẻ trung và năng động\r\n\r\n- Thiết kế bo cổ và tay áo độc đáo, kết hợp với đường chỉ may chắc chắn và sắc nét góp phần tôn lên sự trẻ trung và thanh lịch của người mặc\r\n\r\nCHẤT LIỆU:\r\n\r\n- Chất liệu Cotton Mercerized cho vải áo bóng mịn, thoáng mát, kháng khuẩn và khử mùi mang tới cảm giác dễ chịu do độ thấm hút mồ hôi nhanh chóng. Với công nghệ xử lý hiện đại, chất liệu vải này sở hữu độ bền vượt trội, hạn chế tối đa hiện tượng xơ, xổ vải, nấm mốc.\r\n\r\n- Vải có khả năng chống nhăn tốt, độ đàn hồi cao giúp cho người mặc luôn thoải mái và dễ dàng vận động.\r\n\r\n- Vải dệt từ 100% Cotton Mercerized mềm mại, bền màu và đồng thời không cầu kỳ trong việc chăm sóc vải.\r\n\r\nMÀU SẮC: Đen\r\n\r\nSIZE: S - M - L - XL - 2XL', 322000.00, 'assets/img/product/67154a4f2a493.jpg', '2024-10-20 18:22:07', 0, 1, 1, NULL, 3, 0.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_reviews`
--

CREATE TABLE `product_reviews` (
  `review_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `rating` int(1) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product_reviews`
--

INSERT INTO `product_reviews` (`review_id`, `product_id`, `customer_id`, `rating`, `comment`, `created_at`) VALUES
(5, 72, 6, 3, 'Mua cái áo này vì nghĩ sẽ sexy hơn, ai ngờ mặc vào trông như chiếc bao tải... chắc chỉ có cái túi rác mới quyến rũ hơn tôi lúc này!', '2024-10-23 07:01:38'),
(7, 72, 6, 5, 'Mặc cái quần này xong, nhìn từ xa cứ tưởng đang mặc nhầm khăn trải bàn của nhà hàng nào đó... hẹn hò chắc cũng bữa cơm cuối!', '2024-10-23 07:19:48');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_variants`
--

CREATE TABLE `product_variants` (
  `variant_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `size_id` int(11) DEFAULT NULL,
  `color_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product_variants`
--

INSERT INTO `product_variants` (`variant_id`, `product_id`, `size_id`, `color_id`) VALUES
(377, 63, 1, 1),
(378, 64, 2, 7),
(379, 65, 3, 5),
(380, 66, 1, 2),
(381, 67, 1, 1),
(382, 68, 2, 4),
(383, 69, 1, 7),
(384, 70, 1, 1),
(385, 71, 5, 7),
(386, 72, 1, 7),
(387, 68, 1, 1),
(389, 74, 1, 7),
(390, 74, 1, 1),
(391, 74, 4, 4),
(392, 74, 5, 7);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `shopping_cart`
--

CREATE TABLE `shopping_cart` (
  `cart_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sizes`
--

CREATE TABLE `sizes` (
  `size_id` int(11) NOT NULL,
  `size_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `sizes`
--

INSERT INTO `sizes` (`size_id`, `size_name`) VALUES
(1, 'S'),
(2, 'M'),
(3, 'L'),
(4, 'XL'),
(5, 'XXL');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sku`
--

CREATE TABLE `sku` (
  `sku_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `variant_id` int(11) NOT NULL,
  `sku_code` varchar(50) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `sku`
--

INSERT INTO `sku` (`sku_id`, `product_id`, `variant_id`, `sku_code`, `stock`, `price`, `created_at`, `updated_at`) VALUES
(49, 63, 377, 'POLPRSEN9489', 1, 359000.00, '2024-10-20 11:20:23', '2024-10-20 11:20:23'),
(50, 64, 378, 'POLPRMVN6150', 12, 359200.00, '2024-10-20 11:36:12', '2024-10-20 11:36:12'),
(51, 65, 379, 'POLPRLHN0582', 4, 453000.00, '2024-10-20 11:41:24', '2024-10-20 11:41:24'),
(52, 66, 380, 'POLPRSTR7661', 20, 289000.00, '2024-10-20 11:50:09', '2024-10-20 11:50:09'),
(53, 67, 381, 'OPHPRSEN0413', 16, 125000.00, '2024-10-20 11:53:13', '2024-10-22 15:14:31'),
(54, 68, 382, 'POLPRM2148', 20, 236000.00, '2024-10-20 11:55:44', '2024-10-20 11:55:44'),
(55, 69, 383, 'THUPRSVN0749', 23, 321000.00, '2024-10-20 12:25:37', '2024-10-20 12:25:37'),
(56, 70, 384, 'OTHPRSEN2799', 20, 239000.00, '2024-10-20 14:20:47', '2024-10-20 14:20:47'),
(57, 71, 385, 'SPLPRXVN6985', 1, 325000.00, '2024-10-20 17:26:04', '2024-10-23 05:49:57'),
(58, 72, 386, 'OLTPRSVN1976', 12, 123321.00, '2024-10-20 17:26:44', '2024-10-20 17:26:44'),
(59, 68, 387, 'POLPRSEN9941', 0, 0.00, '2024-10-20 18:15:13', '2024-10-20 18:15:13'),
(61, 74, 389, 'QUNPRSVN0927', 20, 322000.00, '2024-10-20 18:22:07', '2024-10-23 05:26:54'),
(62, 74, 390, 'QUNPRSEN8889', 19, 320000.00, '2024-10-21 12:20:01', '2024-10-23 07:22:25'),
(63, 74, 391, 'QUNPRX4824', 19, 320000.00, '2024-10-22 05:11:54', '2024-10-22 16:27:59'),
(64, 74, 392, 'QUNPRXVN5256', 20, 320000.00, '2024-10-23 05:26:54', '2024-10-23 05:26:54');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `wishlists`
--

CREATE TABLE `wishlists` (
  `wishlist_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Chỉ mục cho bảng `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`brand_id`);

--
-- Chỉ mục cho bảng `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`cart_item_id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `variant_id` (`variant_id`),
  ADD KEY `sku_id` (`sku_id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Chỉ mục cho bảng `colors`
--
ALTER TABLE `colors`
  ADD PRIMARY KEY (`color_id`);

--
-- Chỉ mục cho bảng `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Chỉ mục cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `variant_id` (`variant_id`),
  ADD KEY `sku_id` (`sku_id`);

--
-- Chỉ mục cho bảng `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`email`);

--
-- Chỉ mục cho bảng `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `fk_product_brand` (`brand_id`);

--
-- Chỉ mục cho bảng `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Chỉ mục cho bảng `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`variant_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `size_id` (`size_id`),
  ADD KEY `color_id` (`color_id`);

--
-- Chỉ mục cho bảng `shopping_cart`
--
ALTER TABLE `shopping_cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Chỉ mục cho bảng `sizes`
--
ALTER TABLE `sizes`
  ADD PRIMARY KEY (`size_id`);

--
-- Chỉ mục cho bảng `sku`
--
ALTER TABLE `sku`
  ADD PRIMARY KEY (`sku_id`),
  ADD UNIQUE KEY `sku_code` (`sku_code`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `variant_id` (`variant_id`);

--
-- Chỉ mục cho bảng `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`wishlist_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT cho bảng `brands`
--
ALTER TABLE `brands`
  MODIFY `brand_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `cart_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `colors`
--
ALTER TABLE `colors`
  MODIFY `color_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT cho bảng `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT cho bảng `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT cho bảng `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `variant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=393;

--
-- AUTO_INCREMENT cho bảng `shopping_cart`
--
ALTER TABLE `shopping_cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `sizes`
--
ALTER TABLE `sizes`
  MODIFY `size_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `sku`
--
ALTER TABLE `sku`
  MODIFY `sku_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT cho bảng `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `wishlist_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `shopping_cart` (`cart_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`variant_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cart_items_sku` FOREIGN KEY (`sku_id`) REFERENCES `sku` (`sku_id`);

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_sku` FOREIGN KEY (`sku_id`) REFERENCES `sku` (`sku_id`),
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_product_brand` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`brand_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD CONSTRAINT `product_reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  ADD CONSTRAINT `product_reviews_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`);

--
-- Các ràng buộc cho bảng `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_variants_ibfk_2` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`size_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_variants_ibfk_3` FOREIGN KEY (`color_id`) REFERENCES `colors` (`color_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `shopping_cart`
--
ALTER TABLE `shopping_cart`
  ADD CONSTRAINT `shopping_cart_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `sku`
--
ALTER TABLE `sku`
  ADD CONSTRAINT `fk_sku_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  ADD CONSTRAINT `fk_sku_variant` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`variant_id`);

--
-- Các ràng buộc cho bảng `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `wishlists_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlists_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
