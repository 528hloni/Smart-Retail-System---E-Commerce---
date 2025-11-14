-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Generation Time: Nov 14, 2025 at 04:52 PM
-- Server version: 8.0.43
-- PHP Version: 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wheels_of_fortune_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int NOT NULL,
  `user_id` int NOT NULL,
  `rim_id` int NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `date_added` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` enum('active','checked_out','abandoned') COLLATE utf8mb4_general_ci DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`, `rim_id`, `quantity`, `date_added`, `status`) VALUES
(25, 3, 19, 1, '2025-11-14 14:28:38', 'checked_out'),
(26, 3, 23, 1, '2025-11-14 14:28:44', 'checked_out'),
(27, 3, 23, 1, '2025-11-14 18:19:50', 'checked_out');

-- --------------------------------------------------------

--
-- Table structure for table `failed_payments`
--

CREATE TABLE `failed_payments` (
  `failed_payment_id` int NOT NULL,
  `payment_id` int NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_general_ci DEFAULT 'Payment not reflected after 48 hours',
  `report_date` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `failed_payments`
--

INSERT INTO `failed_payments` (`failed_payment_id`, `payment_id`, `reason`, `report_date`) VALUES
(1, 7, 'Payment not reflected after 48 hours', '2025-11-13 02:01:19'),
(2, 7, 'Payment not reflected after 48 hours', '2025-11-13 02:05:18'),
(3, 8, 'Payment not reflected after 48 hours', '2025-11-13 02:05:29'),
(4, 12, 'Payment not reflected after 48 hours', '2025-11-14 12:37:24');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int NOT NULL,
  `user_id` int NOT NULL,
  `order_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('Pending','Completed','Cancelled') COLLATE utf8mb4_general_ci DEFAULT 'Pending',
  `payment_status` enum('Paid','Unpaid','Refunded','Failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Unpaid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `order_date`, `total_amount`, `status`, `payment_status`) VALUES
(1, 1, '2025-11-06 14:23:00', 28000.00, 'Completed', 'Paid'),
(2, 2, '2025-11-06 10:05:00', 10000.00, 'Cancelled', 'Refunded'),
(3, 1, '2025-11-06 11:10:00', 8000.00, 'Completed', 'Paid'),
(4, 1, '2025-11-11 12:23:24', 26000.00, 'Cancelled', 'Refunded'),
(5, 3, '2025-11-12 19:13:52', 9000.00, 'Pending', 'Unpaid'),
(6, 3, '2025-11-12 19:13:55', 9000.00, 'Pending', 'Unpaid'),
(7, 3, '2025-11-12 19:14:00', 9000.00, 'Pending', 'Unpaid'),
(8, 3, '2025-11-12 19:15:40', 9000.00, 'Pending', 'Unpaid'),
(9, 3, '2025-11-12 19:21:54', 9000.00, 'Cancelled', 'Refunded'),
(10, 3, '2025-11-13 01:39:42', 20000.00, 'Completed', 'Paid'),
(11, 3, '2025-11-13 01:46:38', 8000.00, 'Cancelled', 'Failed'),
(12, 3, '2025-11-13 01:48:25', 8000.00, 'Cancelled', 'Failed'),
(13, 3, '2025-11-13 13:55:26', 10000.00, 'Completed', 'Paid'),
(14, 3, '2025-11-14 08:45:04', 56000.00, 'Cancelled', 'Refunded'),
(15, 22, '2025-11-14 11:34:00', 8000.00, 'Completed', 'Paid'),
(16, 2, '2025-11-14 11:35:12', 18000.00, 'Cancelled', 'Failed'),
(17, 3, '2025-11-14 11:36:13', 8000.00, 'Cancelled', 'Refunded'),
(18, 3, '2025-11-14 14:29:07', 18000.00, 'Completed', 'Paid'),
(19, 3, '2025-11-14 18:22:34', 9000.00, 'Completed', 'Paid');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `item_id` int NOT NULL,
  `order_id` int NOT NULL,
  `rim_id` int NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`item_id`, `order_id`, `rim_id`, `quantity`, `unit_price`) VALUES
(25, 18, 19, 1, 9000.00),
(26, 18, 23, 1, 9000.00),
(27, 19, 23, 1, 9000.00);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int NOT NULL,
  `order_id` int NOT NULL,
  `payment_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `amount` decimal(10,2) NOT NULL,
  `method` enum('Credit Card','EFT','Cash','PayPal','Card') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('Pending','Successful','Failed','Refunded') COLLATE utf8mb4_general_ci DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `order_id`, `payment_date`, `amount`, `method`, `status`) VALUES
(1, 1, '2025-11-10 03:26:10', 28500.00, 'EFT', 'Successful'),
(2, 2, '2025-11-10 03:26:10', 10000.00, 'Credit Card', 'Refunded'),
(3, 3, '2025-11-10 03:26:10', 19000.00, 'PayPal', 'Successful'),
(4, 4, '2025-11-11 12:24:54', 26000.00, 'EFT', 'Refunded'),
(5, 9, '2025-11-12 19:21:54', 9000.00, 'Card', 'Refunded'),
(6, 10, '2025-11-13 01:39:42', 20000.00, 'Card', 'Successful'),
(7, 11, '2025-11-13 01:46:38', 8000.00, 'Card', 'Failed'),
(8, 12, '2025-11-13 01:48:25', 8000.00, 'Card', 'Failed'),
(9, 13, '2025-11-13 13:55:27', 10000.00, 'Card', 'Successful'),
(10, 14, '2025-11-14 08:45:04', 56000.00, 'Card', 'Refunded'),
(11, 15, '2025-11-14 11:34:00', 8000.00, 'Card', 'Successful'),
(12, 16, '2025-11-14 11:35:12', 18000.00, 'Card', 'Failed'),
(13, 17, '2025-11-14 11:36:13', 8000.00, 'Card', 'Refunded'),
(14, 18, '2025-11-14 14:29:07', 18000.00, 'Card', 'Successful'),
(15, 19, '2025-11-14 18:22:35', 9000.00, 'Card', 'Successful');

-- --------------------------------------------------------

--
-- Table structure for table `refunds`
--

CREATE TABLE `refunds` (
  `refund_id` int NOT NULL,
  `order_id` int NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_general_ci DEFAULT 'Payment received but out of stock',
  `refund_amount` decimal(10,2) NOT NULL,
  `request_date` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `refunds`
--

INSERT INTO `refunds` (`refund_id`, `order_id`, `reason`, `refund_amount`, `request_date`) VALUES
(5, 2, 'Payment received but product(s) out of stock.', 1000.00, '2025-11-11 12:02:40'),
(6, 4, 'Payment received but product(s) out of stock.', 26000.00, '2025-11-11 13:01:46'),
(7, 9, 'Payment received but product(s) out of stock.', 9000.00, '2025-11-13 01:51:49'),
(8, 13, 'Payment received but product(s) out of stock.', 10000.00, '2025-11-13 20:22:20'),
(9, 13, 'Payment received but product(s) out of stock.', 10000.00, '2025-11-13 20:22:35'),
(10, 14, 'Payment received but product(s) out of stock.', 56000.00, '2025-11-14 12:37:04'),
(11, 17, 'Payment received but product(s) out of stock.', 8000.00, '2025-11-14 14:28:17');

-- --------------------------------------------------------

--
-- Table structure for table `rims`
--

CREATE TABLE `rims` (
  `rim_id` int NOT NULL,
  `rim_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `model` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `size_inch` decimal(3,1) NOT NULL,
  `bolt_pattern` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `offset` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `center_bore` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `color` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int NOT NULL DEFAULT '0',
  `image_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rims`
--

INSERT INTO `rims` (`rim_id`, `rim_name`, `model`, `size_inch`, `bolt_pattern`, `offset`, `center_bore`, `color`, `price`, `quantity`, `image_url`) VALUES
(17, 'Mercedes', 'Model KL K415-1', 20.0, 'PCD 5X112', 'ET40', 'CB66.6', 'Silver', 7000.00, 10, 'Uploaded_Images/Rim1.jpg'),
(18, 'Vossen', 'Model DN ART876', 20.0, 'PCD 6X139.7', 'ET10', 'CB 110.5', 'Black', 8000.00, 7, 'Uploaded_Images/Rim2.jpg'),
(19, 'TC Wheels', 'Model E7202', 19.0, 'PCD 5X112', 'ET45', 'CB 66.6', 'Black', 9000.00, 4, 'Uploaded_Images/Rim3.jpg'),
(20, 'Vossen', 'Model DN ART874', 20.0, 'PCD 6X139.7', 'ET10', 'CB 110.5', 'Black', 10000.00, 4, 'Uploaded_Images/Rim4.jpg'),
(21, 'Vossen', 'Model DN ART875', 20.0, 'PCD 6X139.7', 'ET10', 'CB 110.5', 'Satin Grey', 8000.00, 4, 'Uploaded_Images/Rim5.jpg'),
(22, 'Audi', 'Model 967', 22.0, 'PCD 5X100', 'ET26', 'CB66.5', 'Silver', 12000.00, 7, 'Uploaded_Images/Rim6.jpg'),
(23, 'O Z Racing', 'Model IV555', 17.0, 'PCD 5X100/114.3', 'ET30', 'CB73.1', 'Silver', 9000.00, 4, 'Uploaded_Images/Rim7.jpg'),
(24, 'Vw', 'Model 7049', 14.0, 'PCD5X100', 'ET35', 'CB57.1', 'Black', 9000.00, 7, 'Uploaded_Images/Rim8.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `surname` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `id_number` varchar(13) COLLATE utf8mb4_general_ci NOT NULL,
  `date_of_birth` date NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `surname`, `id_number`, `date_of_birth`, `email`, `phone`, `password_hash`, `role`) VALUES
(1, 'John', 'Mokoena', '0305134658986', '2003-05-13', 'john@example.com', '0810507801', '$2y$10$UmPj0NGz62KbXVnyPqZicuBwxIGALzwh7uGRoZtKU7bHJOQ2Ko8sa', 'Customer'),
(2, 'Sarah', 'Nkosi', '9901016584932', '1999-01-01', 'sarah@example.com', '0782745177', '$2y$10$hKUiMHOMPPZtzP2tdGOfte0/vn5SibIKHsMdC/zmLt9lMoqc3uW4O', 'Customer'),
(3, 'Lehlohonolo', 'Letsaba', '0405284638218', '2004-05-28', 'hloniletsaba@gmail.com', '0782965966', '$2y$10$//ddfHWX05errS8vlSalL.Vv9QW2mopza/tXTOjP45QR3lNVlQgXe', 'Customer'),
(19, 'Sam', 'Khumalo', '8501011234567', '1985-01-01', 'sales@example.com', '0810000001', '$2y$10$PT6hUHvYE2uboDZ6dmGkH.hsXJnrY7/TuINNVZ5c09srJHOSAZtRe', 'Sales Associate'),
(20, 'Ingrid', 'Mthembu', '8702027654321', '1987-02-02', 'inventory@example.com', '0810000002', '$2y$10$PT6hUHvYE2uboDZ6dmGkH.hsXJnrY7/TuINNVZ5c09srJHOSAZtRe', 'Inventory Manager'),
(21, 'Peter', 'Dlamini', '9003031122334', '1990-03-03', 'payment@example.com', '0810000003', '$2y$10$PT6hUHvYE2uboDZ6dmGkH.hsXJnrY7/TuINNVZ5c09srJHOSAZtRe', 'Payment Processor'),
(22, 'Nqobile', 'Letsaba', '1037372828293', '2010-05-26', 'nqobile@gmail.com', '0810507801', '$2y$10$EOWbHcptiDrcB5Mpo1UrGOmgDYpjQgKu1KzdzJ1r6NebZnk1BWtlm', 'Customer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `cart_ibfk_2` (`rim_id`);

--
-- Indexes for table `failed_payments`
--
ALTER TABLE `failed_payments`
  ADD PRIMARY KEY (`failed_payment_id`),
  ADD KEY `payment_id` (`payment_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `order_items_ibfk_2` (`rim_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `refunds`
--
ALTER TABLE `refunds`
  ADD PRIMARY KEY (`refund_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `rims`
--
ALTER TABLE `rims`
  ADD PRIMARY KEY (`rim_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `id_number` (`id_number`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `failed_payments`
--
ALTER TABLE `failed_payments`
  MODIFY `failed_payment_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `item_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `refunds`
--
ALTER TABLE `refunds`
  MODIFY `refund_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `rims`
--
ALTER TABLE `rims`
  MODIFY `rim_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`rim_id`) REFERENCES `rims` (`rim_id`) ON DELETE CASCADE;

--
-- Constraints for table `failed_payments`
--
ALTER TABLE `failed_payments`
  ADD CONSTRAINT `failed_payments_ibfk_1` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`payment_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`rim_id`) REFERENCES `rims` (`rim_id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Constraints for table `refunds`
--
ALTER TABLE `refunds`
  ADD CONSTRAINT `refunds_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
