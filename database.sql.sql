-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 05, 2026 at 04:07 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `warehouse_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `shipments`
--

CREATE TABLE `shipments` (
  `id` int(11) NOT NULL,
  `shipment_code` varchar(20) DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `status` enum('pending','in-transit','delivered') DEFAULT 'pending',
  `origin` text DEFAULT NULL,
  `destination` text DEFAULT NULL,
  `iv_shipment` text DEFAULT NULL,
  `tag_shipment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `delivered_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shipments`
--

INSERT INTO `shipments` (`id`, `shipment_code`, `warehouse_id`, `user_id`, `status`, `origin`, `destination`, `iv_shipment`, `tag_shipment`, `created_at`, `delivered_at`) VALUES
(1, 'SHIP-99001', 1, 1, 'pending', 'Olongapo City', 'AzPZ64DBk87086s1bkouoNjLg69SKH7J', '0bc1aaeecad2c75c33657c7c', '919d9c540c4826908d8cb70d118c73db', '2026-03-05 14:45:19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email_encrypted` text DEFAULT NULL,
  `phone_encrypted` text DEFAULT NULL,
  `iv_user` blob DEFAULT NULL,
  `tag_user` blob DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password_hash`, `email_encrypted`, `phone_encrypted`, `iv_user`, `tag_user`, `role`, `created_at`) VALUES
(1, 'admin_test', '$2y$10$x73kFwNmTWJSiAA2BFLIeuI5dxcKYYsJ2.yGSnvP4txomyeGRRz6O', 'xLv3rJ0JgI00KUVFD5hagWB3Rw==', NULL, 0x653331393363323236333639353334633436636533373261, 0x3362626530396430303533626364333434326334636439383363623238303461, 'admin', '2026-03-05 14:05:27');

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE `warehouses` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `warehouses`
--

INSERT INTO `warehouses` (`id`, `name`, `location`, `capacity`) VALUES
(1, 'Olongapo Hub', 'Subic Bay', 500),
(2, 'Olongapo Hub', 'Subic Bay', 500);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `shipments`
--
ALTER TABLE `shipments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `shipment_code` (`shipment_code`),
  ADD KEY `warehouse_id` (`warehouse_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `shipments`
--
ALTER TABLE `shipments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `shipments`
--
ALTER TABLE `shipments`
  ADD CONSTRAINT `shipments_ibfk_1` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`),
  ADD CONSTRAINT `shipments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
