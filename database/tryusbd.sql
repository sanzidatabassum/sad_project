-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 15, 2025 at 04:19 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30
SET
  SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET
  time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;

/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;

/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;

/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tryusbd`
--
-- --------------------------------------------------------
--
-- Admin table
-- Login: pranto@gmail.com, pranto123
CREATE TABLE
  `admin` (
    `id` int (11) NOT NULL AUTO_INCREMENT,
    `full_name` varchar(100) NOT NULL,
    `mobile` varchar(20) NOT NULL,
    `email` varchar(100) NOT NULL UNIQUE,
    `password` varchar(255) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

INSERT INTO
  `admin` (`full_name`, `mobile`, `email`, `password`)
VALUES
  (
    'Admin',
    '01867610022',
    'pranto@gmail.com',
    '$2y$10$TKh8H1.PfunDb5Jigte/vuFDo5Iu5/Ow/CJqFDBylfLFcmNhuwDSi'
  );

-- Table structure for table `contact_messages`
--
CREATE TABLE
  `contact_messages` (
    `id` int (11) NOT NULL,
    `name` varchar(100) NOT NULL,
    `phone` varchar(20) NOT NULL,
    `email` varchar(100) DEFAULT NULL,
    `message` text NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp()
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--
INSERT INTO
  `contact_messages` (
    `id`,
    `name`,
    `phone`,
    `email`,
    `message`,
    `created_at`
  )
VALUES
  (
    1,
    'Bishwaprotap RAy',
    '01788974534',
    'baburay214@gmail.com',
    'rrr',
    '2025-01-10 16:57:45'
  ),
  (
    2,
    'Bishwaprotap RAy',
    '01788974534',
    'baburay214@gmail.com',
    ';llllllllll',
    '2025-01-10 17:29:14'
  ),
  (
    3,
    'Bishwaprotap RAy',
    '01788974534',
    'baburay214@gmail.com',
    'eeeeeeeeeeeeeeeeeeeeeeeeee',
    '2025-01-10 17:37:01'
  );

-- --------------------------------------------------------
--
-- Table structure for table `orders`
--
CREATE TABLE
  `orders` (
    `id` int (11) NOT NULL,
    `customer_name` varchar(100) NOT NULL,
    `phone` varchar(20) NOT NULL,
    `email` varchar(100) DEFAULT NULL,
    `address` text NOT NULL,
    `delivery_location` varchar(20) NOT NULL,
    `subtotal` decimal(10, 2) NOT NULL,
    `delivery_charge` decimal(10, 2) NOT NULL,
    `total_amount` decimal(10, 2) NOT NULL,
    `order_date` datetime NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `status` varchar(20) DEFAULT 'pending'
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--
INSERT INTO
  `orders` (
    `id`,
    `customer_name`,
    `phone`,
    `email`,
    `address`,
    `delivery_location`,
    `subtotal`,
    `delivery_charge`,
    `total_amount`,
    `order_date`,
    `created_at`,
    `status`
  )
VALUES
  (
    1,
    'Bishwaprotap RAy',
    '01788974534',
    'baburay214@gmail.com',
    'House 62, Road 10 ,Sector 10 , Uttara Dhaka',
    'outside',
    163992.00,
    110.00,
    164102.00,
    '2025-01-10 22:46:55',
    '2025-01-10 16:46:55',
    'pending'
  ),
  (
    2,
    'Bishwaprotap',
    '01788974534',
    'baburay214@gmail.com',
    '10\r\n62',
    'outside',
    117996.00,
    110.00,
    118106.00,
    '2025-01-10 22:47:58',
    '2025-01-10 16:47:58',
    'completed'
  ),
  (
    3,
    'Bishwaprotap RAy',
    '01788974534',
    'baburay214@gmail.com',
    'House 62, Road 10 ,Sector 10 , Uttara Dhaka',
    'inside',
    52998.00,
    60.00,
    53058.00,
    '2025-01-10 22:49:50',
    '2025-01-10 16:49:50',
    'pending'
  ),
  (
    4,
    'Bishwaprotap RAy',
    '01788974534',
    'baburay214@gmail.com',
    'House 62, Road 10 ,Sector 10 , Uttara Dhaka',
    'inside',
    54998.00,
    60.00,
    55058.00,
    '2025-01-10 23:29:05',
    '2025-01-10 17:29:05',
    'pending'
  ),
  (
    5,
    'rrrrrrrrrrrr',
    '01788974534',
    'baburay214@gmail.com',
    'House 62, Road 10 ,Sector 10 , Uttara Dhaka',
    'inside',
    82997.00,
    60.00,
    83057.00,
    '2025-01-10 23:37:25',
    '2025-01-10 17:37:25',
    'completed'
  ),
  (
    6,
    'rrrrrr',
    '01788974534',
    'baburay214@gmail.com',
    'House 62, Road 10 ,Sector 10 , Uttara Dhaka',
    'outside',
    10000.00,
    110.00,
    30109.00,
    '2025-01-10 23:52:05',
    '2025-01-10 17:52:05',
    'completed'
  ),
  (
    7,
    'Bishwaprotap RAy',
    '01788974534',
    'baburay214@gmail.com',
    'House 62, Road 10 ,Sector 10 , Uttara Dhaka',
    'outside',
    36997.00,
    110.00,
    37107.00,
    '2025-01-11 00:03:43',
    '2025-01-10 18:03:43',
    'pending'
  ),
  (
    8,
    'Bishwaprotap RAy',
    '01788974534',
    'baburay214@gmail.com',
    'House 62, Road 10 ,Sector 10 , Uttara Dhaka',
    'inside',
    40998.00,
    60.00,
    41058.00,
    '2025-01-11 00:18:01',
    '2025-01-10 18:18:01',
    'completed'
  ),
  (
    9,
    'Bishwaprotap Ray',
    '01788974534',
    '22203157@iubat.edu',
    'IUBAT',
    'inside',
    57998.00,
    60.00,
    58058.00,
    '2025-01-13 16:03:31',
    '2025-01-13 10:03:31',
    'pending'
  ),
  (
    10,
    'Bishwaprotap Ray',
    '01788974534',
    '22203157@iubat.edu',
    'IUBAT',
    'inside',
    52998.00,
    60.00,
    53058.00,
    '2025-01-14 13:09:19',
    '2025-01-14 07:09:19',
    'completed'
  );

-- --------------------------------------------------------
--
-- Table structure for table `order_items`
--
CREATE TABLE
  `order_items` (
    `id` int (11) NOT NULL,
    `order_id` int (11) NOT NULL,
    `product_id` int (11) NOT NULL,
    `product_name` varchar(100) NOT NULL,
    `quantity` int (11) NOT NULL,
    `price` decimal(10, 2) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp()
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--
INSERT INTO
  `order_items` (
    `id`,
    `order_id`,
    `product_id`,
    `product_name`,
    `quantity`,
    `price`,
    `created_at`
  )
VALUES
  (
    1,
    1,
    1,
    'Sony WH-1000XM4 Wireless Headphones',
    2,
    10000.00,
    '2025-01-10 16:46:55'
  ),
  (
    2,
    1,
    3,
    'Samsung Galaxy Watch 5',
    2,
    27999.00,
    '2025-01-10 16:46:55'
  ),
  (
    3,
    1,
    4,
    'Anker PowerCore 26800mAh',
    2,
    4999.00,
    '2025-01-10 16:46:55'
  ),
  (
    4,
    1,
    7,
    'Samsung Galaxy Buds2 Pro',
    2,
    18999.00,
    '2025-01-10 16:46:55'
  ),
  (
    5,
    2,
    1,
    'Sony WH-1000XM4 Wireless Headphones',
    2,
    10000.00,
    '2025-01-10 16:47:58'
  ),
  (
    6,
    2,
    3,
    'Samsung Galaxy Watch 5',
    1,
    27999.00,
    '2025-01-10 16:47:58'
  ),
  (
    7,
    2,
    21,
    'Fitbit Sense 2',
    1,
    10000.00,
    '2025-01-10 16:47:58'
  ),
  (
    8,
    3,
    2,
    'aaaaaaa',
    1,
    24999.00,
    '2025-01-10 16:49:50'
  ),
  (
    9,
    3,
    3,
    'Samsung Galaxy Watch 5',
    1,
    27999.00,
    '2025-01-10 16:49:50'
  ),
  (
    10,
    4,
    1,
    'Sony WH-1000XM4 Wireless Headphones',
    1,
    10000.00,
    '2025-01-10 17:29:05'
  ),
  (
    11,
    4,
    2,
    'Apple AirPods Pro',
    1,
    24999.00,
    '2025-01-10 17:29:05'
  ),
  (
    12,
    5,
    1,
    'Sony WH-1000XM4 Wireless Headphones',
    1,
    10000.00,
    '2025-01-10 17:37:25'
  ),
  (
    13,
    5,
    2,
    'Apple AirPods Pro',
    1,
    24999.00,
    '2025-01-10 17:37:25'
  ),
  (
    14,
    5,
    3,
    'Samsung Galaxy Watch 5',
    1,
    27999.00,
    '2025-01-10 17:37:25'
  ),
  (
    15,
    6,
    1,
    'Sony WH-1000XM4 Wireless Headphones',
    1,
    10000.00,
    '2025-01-10 17:52:05'
  ),
  (
    16,
    7,
    12,
    'ROMOSS 30000mAh Power Bank',
    1,
    3999.00,
    '2025-01-10 18:03:43'
  ),
  (
    17,
    7,
    8,
    'Mi Power Bank 20000mAh',
    1,
    2999.00,
    '2025-01-10 18:03:43'
  ),
  (
    18,
    7,
    1,
    'Sony WH-1000XM4 Wireless Headphones',
    1,
    10000.00,
    '2025-01-10 18:03:43'
  ),
  (
    19,
    8,
    20,
    'UGREEN 145W Power Bank',
    1,
    7999.00,
    '2025-01-10 18:18:01'
  ),
  (
    20,
    8,
    15,
    'Huawei Watch GT 3 Pro',
    1,
    32999.00,
    '2025-01-10 18:18:01'
  ),
  (
    21,
    9,
    1,
    'Sony WH-1000XM4 Wireless Headphones',
    1,
    10000.00,
    '2025-01-13 10:03:31'
  ),
  (
    22,
    9,
    3,
    'Samsung Galaxy Watch 5',
    1,
    27999.00,
    '2025-01-13 10:03:31'
  ),
  (
    23,
    10,
    2,
    'Apple AirPods Pro',
    1,
    24999.00,
    '2025-01-14 07:09:19'
  ),
  (
    24,
    10,
    3,
    'Samsung Galaxy Watch 5',
    1,
    27999.00,
    '2025-01-14 07:09:19'
  );

-- --------------------------------------------------------
--
-- Table structure for table `products`
--
CREATE TABLE
  `products` (
    `id` int (11) NOT NULL,
    `name` varchar(255) NOT NULL,
    `description` text DEFAULT NULL,
    `price` decimal(10, 2) NOT NULL,
    `image` varchar(255) NOT NULL,
    `category` varchar(100) DEFAULT NULL,
    `status` enum ('available', 'upcoming', 'out_of_stock') DEFAULT 'available',
    `rating` decimal(3, 2) DEFAULT 0.00,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp()
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `products`
--
INSERT INTO
  `products` (
    `id`,
    `name`,
    `description`,
    `price`,
    `image`,
    `category`,
    `status`,
    `rating`,
    `created_at`
  )
VALUES
  (
    1,
    'Sony WH-1000XM4 Wireless Headphones',
    'Industry-leading noise cancellation with Dual Noise Sensor technology',
    10000.00,
    'https://m.media-amazon.com/images/I/71o8Q5XJS5L._AC_SL1500_.jpg',
    'headphones',
    'available',
    4.80,
    '2025-01-10 17:48:12'
  ),
  (
    2,
    'Apple AirPods Pro',
    'Active Noise Cancellation for immersive sound',
    24999.00,
    'https://m.media-amazon.com/images/I/71bhWgQK-cL._AC_SL1500_.jpg',
    'earbuds',
    'available',
    4.70,
    '2025-01-10 17:48:12'
  ),
  (
    3,
    'Samsung Galaxy Watch 5',
    'Advanced health monitoring with elegant design',
    27999.00,
    'https://m.media-amazon.com/images/I/61aVQDazNHL._AC_SL1500_.jpg',
    'smartwatch',
    'available',
    4.60,
    '2025-01-10 17:48:12'
  ),
  (
    4,
    'Anker PowerCore 26800mAh',
    'High-capacity portable charger with 3 USB ports',
    4999.00,
    'https://m.media-amazon.com/images/I/61pBvlYz5FL._AC_SL1500_.jpg',
    'powerbank',
    'available',
    4.50,
    '2025-01-10 17:48:12'
  ),
  (
    5,
    'Apple Watch Series 8',
    'Advanced health features with Always-On Retina display',
    45999.00,
    'https://m.media-amazon.com/images/I/71XMTLtZd5L._AC_SL1500_.jpg',
    'smartwatch',
    'upcoming',
    4.90,
    '2025-01-10 17:48:12'
  ),
  (
    6,
    'Google Pixel Buds Pro',
    'Premium sound quality with Active Noise Cancellation',
    19999.00,
    'https://m.media-amazon.com/images/I/61PnHlc0HCL._AC_SL1500_.jpg',
    'earbuds',
    'available',
    4.40,
    '2025-01-10 17:48:12'
  ),
  (
    7,
    'Samsung Galaxy Buds2 Pro',
    'Intelligent Active Noise Cancellation with premium sound',
    18999.00,
    'https://m.media-amazon.com/images/I/51j1kKqYHqL._AC_SL1500_.jpg',
    'earbuds',
    'upcoming',
    4.60,
    '2025-01-10 17:48:12'
  ),
  (
    8,
    'Mi Power Bank 20000mAh',
    'Fast charging power bank with dual USB ports',
    2999.00,
    'https://m.media-amazon.com/images/I/71lVwl3q-kL._AC_SL1500_.jpg',
    'powerbank',
    'available',
    4.30,
    '2025-01-10 17:48:12'
  ),
  (
    9,
    'Bose QuietComfort 45',
    'World-class noise cancellation with premium comfort',
    32999.00,
    'https://m.media-amazon.com/images/I/51JbsHSktkL._AC_SL1500_.jpg',
    'headphones',
    'available',
    4.70,
    '2025-01-10 17:48:12'
  ),
  (
    10,
    'Nothing Ear (2)',
    'Hi-Res Audio Certified with Dual Connection',
    14999.00,
    'https://m.media-amazon.com/images/I/61WY2tV2C2L._AC_SL1500_.jpg',
    'earbuds',
    'available',
    4.50,
    '2025-01-10 17:48:12'
  ),
  (
    11,
    'Garmin Venu 2 Plus',
    'Advanced fitness tracking with built-in GPS',
    39999.00,
    'https://m.media-amazon.com/images/I/71SN8i4-fIL._AC_SL1500_.jpg',
    'smartwatch',
    'available',
    4.80,
    '2025-01-10 17:48:12'
  ),
  (
    12,
    'ROMOSS 30000mAh Power Bank',
    '30W PD Fast Charging with LED Display',
    3999.00,
    'https://m.media-amazon.com/images/I/71EMi-0ZOEL._AC_SL1500_.jpg',
    'powerbank',
    'available',
    4.40,
    '2025-01-10 17:48:12'
  ),
  (
    13,
    'JBL Tune 760NC Headphones',
    'Active Noise Cancelling headphones with deep bass',
    12999.00,
    'https://m.media-amazon.com/images/I/61HXCeozUjL._AC_SL1500_.jpg',
    'headphones',
    'available',
    4.50,
    '2025-01-10 17:48:12'
  ),
  (
    14,
    'OnePlus Buds Pro 2',
    'Spatial Audio with Dynamic Head Tracking',
    16999.00,
    'https://m.media-amazon.com/images/I/61nScEBtJhL._AC_SL1500_.jpg',
    'earbuds',
    'available',
    4.60,
    '2025-01-10 17:48:12'
  ),
  (
    15,
    'Huawei Watch GT 3 Pro',
    'Premium design with comprehensive health monitoring',
    32999.00,
    'https://m.media-amazon.com/images/I/61YVqHdFRxL._AC_SL1500_.jpg',
    'smartwatch',
    'available',
    4.70,
    '2025-01-10 17:48:12'
  ),
  (
    16,
    'Baseus 65W Power Bank',
    '20000mAh with PD Fast Charging',
    5999.00,
    'https://m.media-amazon.com/images/I/71BkL4RhvmL._AC_SL1500_.jpg',
    'powerbank',
    'available',
    4.60,
    '2025-01-10 17:48:12'
  ),
  (
    17,
    'Jabra Elite 85h',
    'SmartSound Audio with Advanced ANC',
    24999.00,
    'https://m.media-amazon.com/images/I/61ZDwiW1CxL._AC_SL1500_.jpg',
    'headphones',
    'available',
    4.40,
    '2025-01-10 17:48:12'
  ),
  (
    18,
    'Xiaomi Redmi Watch 3',
    '1.75\" AMOLED Display with GPS',
    8999.00,
    'https://m.media-amazon.com/images/I/61SOib7YdLL._AC_SL1500_.jpg',
    'smartwatch',
    'available',
    4.30,
    '2025-01-10 17:48:12'
  ),
  (
    19,
    'Soundcore Liberty Air 2 Pro',
    'Targeted Active Noise Cancellation',
    9999.00,
    'https://m.media-amazon.com/images/I/61J6+xcVexL._AC_SL1500_.jpg',
    'earbuds',
    'available',
    4.50,
    '2025-01-10 17:48:12'
  ),
  (
    20,
    'UGREEN 145W Power Bank',
    '25000mAh with LED Display',
    7999.00,
    'https://m.media-amazon.com/images/I/71MkFRb+hpL._AC_SL1500_.jpg',
    'powerbank',
    'available',
    4.70,
    '2025-01-10 17:48:12'
  ),
  (
    21,
    'Fitbit Sense 2',
    'Advanced Health Metrics with ECG App',
    10000.00,
    'https://m.media-amazon.com/images/I/71J8VhpsPBL._AC_SL1500_.jpg',
    'smartwatch',
    'available',
    4.40,
    '2025-01-10 17:48:12'
  ),
  (
    22,
    'Sennheiser HD 450BT',
    'Active Noise Cancellation with Deep Bass',
    14999.00,
    'https://m.media-amazon.com/images/I/71p1vhsqjWL._AC_SL1500_.jpg',
    'headphones',
    'available',
    4.60,
    '2025-01-10 17:48:12'
  );

--
-- Indexes for dumped tables
--
--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages` ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders` ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items` ADD PRIMARY KEY (`id`),
ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products` ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--
--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages` MODIFY `id` int (11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders` MODIFY `id` int (11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 11;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items` MODIFY `id` int (11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 25;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products` MODIFY `id` int (11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 23;

--
-- Constraints for dumped tables
--
--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items` ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;

/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- --------------------------------------------------------
--
-- Table structure for table `admin`
--
CREATE TABLE
  `admin` (
    `id` int (11) NOT NULL AUTO_INCREMENT,
    `full_name` varchar(100) NOT NULL,
    `mobile` varchar(20) NOT NULL,
    `email` varchar(100) NOT NULL UNIQUE,
    `password` varchar(255) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Default admin: pranto@gmail.com / pranto123
--
INSERT INTO
  `admin` (`full_name`, `mobile`, `email`, `password`)
VALUES
  (
    'Admin',
    '01867610022',
    'pranto@gmail.com',
    '$2y$10$TKh8H1.PfunDb5Jigte/vuFDo5Iu5/Ow/CJqFDBylfLFcmNhuwDSi'
  );