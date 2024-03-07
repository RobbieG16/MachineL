-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 13, 2024 at 11:54 AM
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
-- Database: `esp_data`
--

-- --------------------------------------------------------

--
-- Table structure for table `avgmonthly1`
--

CREATE TABLE `avgmonthly1` (
  `id` int(11) NOT NULL,
  `avg_nitrogen` decimal(10,2) DEFAULT NULL,
  `avg_potassium` decimal(10,2) DEFAULT NULL,
  `avg_phosphorus` decimal(10,2) DEFAULT NULL,
  `avg_soil_temperature` decimal(10,2) DEFAULT NULL,
  `avg_air_temp` decimal(10,2) DEFAULT NULL,
  `month` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `avgmonthly1`
--

INSERT INTO `avgmonthly1` (`id`, `avg_nitrogen`, `avg_potassium`, `avg_phosphorus`, `avg_soil_temperature`, `avg_air_temp`, `month`) VALUES
(1, 1.58, 2.28, 2.72, 18.55, 16.85, '2024-01'),
(2, 0.56, 0.42, 0.61, 1.58, 0.69, '2024-02');

-- --------------------------------------------------------

--
-- Table structure for table `avgmonthly2`
--

CREATE TABLE `avgmonthly2` (
  `id` int(11) NOT NULL,
  `avg_nitrogen` float DEFAULT NULL,
  `avg_potassium` float DEFAULT NULL,
  `avg_phosphorus` float DEFAULT NULL,
  `avg_soil_temperature` float DEFAULT NULL,
  `avg_air_temp` float DEFAULT NULL,
  `month` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `avgmonthly2`
--

INSERT INTO `avgmonthly2` (`id`, `avg_nitrogen`, `avg_potassium`, `avg_phosphorus`, `avg_soil_temperature`, `avg_air_temp`, `month`) VALUES
(1, 12.2, 1.5, 21.1, 22.6, 15.6, '2024-01'),
(2, 6.7, 5.2, 9.22, 23, 54, '2024-02');

-- --------------------------------------------------------

--
-- Table structure for table `avgmonthly3`
--

CREATE TABLE `avgmonthly3` (
  `id` int(11) NOT NULL,
  `avg_nitrogen` float DEFAULT NULL,
  `avg_potassium` float DEFAULT NULL,
  `avg_phosphorus` float DEFAULT NULL,
  `avg_soil_temperature` float DEFAULT NULL,
  `avg_air_temp` float DEFAULT NULL,
  `month` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `avgmonthly3`
--

INSERT INTO `avgmonthly3` (`id`, `avg_nitrogen`, `avg_potassium`, `avg_phosphorus`, `avg_soil_temperature`, `avg_air_temp`, `month`) VALUES
(1, 2, 21, 5, 7, 20, '2024-01'),
(2, 5.2, 16.2, 21.7, 28.22, 42.1, '2024-02');

-- --------------------------------------------------------

--
-- Table structure for table `avgsensor1`
--

CREATE TABLE `avgsensor1` (
  `id` int(11) NOT NULL,
  `avg_nitrogen` decimal(10,2) DEFAULT NULL,
  `avg_potassium` decimal(10,2) DEFAULT NULL,
  `avg_phosphorus` decimal(10,2) DEFAULT NULL,
  `avg_soil_temperature` decimal(10,2) DEFAULT NULL,
  `avg_air_temp` decimal(10,2) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `avgsensor1`
--

INSERT INTO `avgsensor1` (`id`, `avg_nitrogen`, `avg_potassium`, `avg_phosphorus`, `avg_soil_temperature`, `avg_air_temp`, `date`) VALUES
(1, 0.56, 0.42, 0.61, 0.58, 0.69, '2024-01-01 21:47:00'),
(2, 1.97, 2.41, 2.80, 18.76, 16.97, '2024-02-06 20:50:18'),
(3, 1.88, 2.57, 2.89, 15.04, 13.91, '2024-01-26 20:44:51'),
(4, 1.48, 2.66, 3.20, 25.54, 23.04, '2024-01-28 08:00:00'),
(5, 1.38, 2.62, 3.20, 25.08, 23.04, '2024-01-29 08:00:00'),
(6, 2.18, 3.02, 3.60, 26.28, 23.44, '2024-01-30 08:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `avgsensor2`
--

CREATE TABLE `avgsensor2` (
  `id` int(11) NOT NULL,
  `avg_nitrogen` decimal(10,2) DEFAULT NULL,
  `avg_potassium` decimal(10,2) DEFAULT NULL,
  `avg_phosphorus` decimal(10,2) DEFAULT NULL,
  `avg_soil_temperature` decimal(10,2) DEFAULT NULL,
  `avg_air_temp` decimal(10,2) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `avgsensor2`
--

INSERT INTO `avgsensor2` (`id`, `avg_nitrogen`, `avg_potassium`, `avg_phosphorus`, `avg_soil_temperature`, `avg_air_temp`, `date`) VALUES
(1, 0.45, 0.64, 0.44, 0.71, 0.60, '2024-02-06 21:47:00'),
(2, 1.48, 2.66, 3.20, 25.54, 23.04, '2024-01-26 15:59:59'),
(3, 1.38, 2.62, 3.20, 25.08, 23.04, '2024-01-27 15:59:59'),
(4, 1.18, 2.38, 3.00, 24.64, 22.30, '2024-01-28 15:59:59'),
(5, 1.08, 2.28, 2.90, 24.24, 22.10, '2024-01-29 15:59:59'),
(6, 0.98, 2.18, 2.80, 23.94, 21.90, '2024-01-30 15:59:59');

-- --------------------------------------------------------

--
-- Table structure for table `avgsensor3`
--

CREATE TABLE `avgsensor3` (
  `id` int(11) NOT NULL,
  `avg_nitrogen` decimal(10,2) DEFAULT NULL,
  `avg_potassium` decimal(10,2) DEFAULT NULL,
  `avg_phosphorus` decimal(10,2) DEFAULT NULL,
  `avg_soil_temperature` decimal(10,2) DEFAULT NULL,
  `avg_air_temp` decimal(10,2) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `avgsensor3`
--

INSERT INTO `avgsensor3` (`id`, `avg_nitrogen`, `avg_potassium`, `avg_phosphorus`, `avg_soil_temperature`, `avg_air_temp`, `date`) VALUES
(1, 0.53, 0.53, 0.26, 0.52, 0.61, '2024-01-01 21:47:00'),
(2, 1.18, 2.38, 3.00, 24.34, 22.20, '2024-01-26 15:59:59'),
(3, 1.08, 2.28, 2.90, 24.24, 22.10, '2024-01-27 15:59:59'),
(4, 0.98, 2.18, 2.80, 23.94, 21.90, '2024-01-28 15:59:59'),
(5, 1.08, 2.28, 2.90, 24.24, 22.10, '2024-01-29 15:59:59'),
(6, 0.98, 2.18, 2.80, 23.94, 21.90, '2024-01-30 15:59:59');

-- --------------------------------------------------------

--
-- Table structure for table `overall_data`
--

CREATE TABLE `overall_data` (
  `id` int(11) NOT NULL,
  `all_air_temp` float DEFAULT NULL,
  `all_soil_temperature` float DEFAULT NULL,
  `all_nitrogen` float DEFAULT NULL,
  `all_phosphorus` float DEFAULT NULL,
  `all_potassium` float DEFAULT NULL,
  `reading_date` date NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'Red',
  `hybrid_status` varchar(10) NOT NULL DEFAULT 'Red'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `overall_data`
--

INSERT INTO `overall_data` (`id`, `all_air_temp`, `all_soil_temperature`, `all_nitrogen`, `all_phosphorus`, `all_potassium`, `reading_date`, `status`, `hybrid_status`) VALUES
(1, 33, 23, 21.2, 54, 42, '2024-02-12', 'Green', 'Green'),
(2, 20.7367, 22.88, 1.54333, 3, 2.48333, '2024-01-26', 'Red', 'Red'),
(3, 19.6833, 21.4533, 1.44667, 2.99667, 2.49, '2024-01-27', 'Green', 'Red'),
(4, 22.4133, 24.7067, 1.21333, 3, 2.40667, '2024-01-28', 'Green', 'Green'),
(5, 22.4133, 24.52, 1.18, 3, 2.39333, '2024-01-29', 'Red', 'Red'),
(6, 22.4133, 24.72, 1.38, 3.06667, 2.46, '2024-01-30', 'Red', 'Green'),
(7, 0.65, 0.55, 0.545, 0.435, 0.475, '2024-01-02', 'Red', 'Red'),
(8, 22.62, 24.94, 1.33, 3.1, 2.52, '2024-01-26', 'Red', 'Red'),
(9, 19.6833, 21.4533, 1.44667, 2.99667, 2.49, '2024-01-27', 'Red', 'Red'),
(10, 22.4133, 24.7067, 1.21333, 3, 2.40667, '2024-01-28', 'Red', 'Red'),
(11, 22.4133, 24.52, 1.18, 3, 2.39333, '2024-01-29', 'Red', 'Red'),
(12, 22.4133, 24.72, 1.38, 3.06667, 2.46, '2024-01-30', 'Red', 'Red'),
(13, 8.785, 9.735, 1.21, 1.62, 1.525, '2024-02-07', 'Red', 'Red'),
(14, 0.65, 0.55, 0.545, 0.435, 0.475, '2024-01-02', 'Red', 'Red'),
(15, 22.62, 24.94, 1.33, 3.1, 2.52, '2024-01-26', 'Red', 'Red'),
(16, 19.6833, 21.4533, 1.44667, 2.99667, 2.49, '2024-01-27', 'Red', 'Red'),
(17, 22.4133, 24.7067, 1.21333, 3, 2.40667, '2024-01-28', 'Red', 'Red'),
(18, 22.4133, 24.52, 1.18, 3, 2.39333, '2024-01-29', 'Red', 'Red'),
(19, 22.4133, 24.72, 1.38, 3.06667, 2.46, '2024-01-30', 'Red', 'Red'),
(20, 8.785, 9.735, 1.21, 1.62, 1.525, '2024-02-07', 'Red', 'Red');

-- --------------------------------------------------------

--
-- Table structure for table `rawsensor1`
--

CREATE TABLE `rawsensor1` (
  `id` int(11) NOT NULL,
  `nitrogen` decimal(10,2) DEFAULT NULL,
  `potassium` decimal(10,2) DEFAULT NULL,
  `phosphorus` decimal(10,2) DEFAULT NULL,
  `soil_temperature` decimal(10,2) DEFAULT NULL,
  `air_temp` decimal(10,2) DEFAULT NULL,
  `reading_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rawsensor1`
--

INSERT INTO `rawsensor1` (`id`, `nitrogen`, `potassium`, `phosphorus`, `soil_temperature`, `air_temp`, `reading_time`) VALUES
(1, 2.50, 2.50, 3.10, 25.30, 22.70, '2024-02-07 20:49:51'),
(2, 2.50, 2.70, 3.00, 25.80, 23.20, '2024-01-25 20:50:18'),
(3, 1.80, 2.90, 3.20, 26.20, 23.50, '2024-01-25 18:42:48'),
(4, 1.80, 2.40, 3.40, 24.90, 22.80, '2024-01-25 20:41:34'),
(5, 1.60, 2.80, 3.30, 25.50, 23.00, '2024-01-25 18:42:48'),
(6, 1.10, 2.30, 3.00, 24.70, 22.50, '2024-01-26 18:42:48'),
(7, 1.40, 2.60, 3.10, 25.10, 23.10, '2024-01-26 18:42:48'),
(8, 1.70, 2.90, 3.20, 25.50, 23.40, '2024-01-26 18:42:48'),
(9, 1.20, 2.50, 3.30, 24.80, 22.90, '2024-01-26 18:42:48'),
(10, 1.50, 2.80, 3.40, 25.30, 23.30, '2024-01-26 18:42:48'),
(11, 1.10, 1.10, 1.10, 1.10, 1.10, '2024-01-25 20:26:31'),
(12, 2.50, 2.50, 2.50, 2.50, 2.50, '2024-01-25 20:28:17'),
(13, 1.20, 2.50, 3.10, 25.30, 22.70, '2024-01-28 00:00:00'),
(14, 1.50, 2.70, 3.00, 25.80, 23.20, '2024-01-28 02:00:00'),
(15, 1.80, 2.90, 3.20, 26.20, 23.50, '2024-01-28 04:00:00'),
(16, 1.30, 2.40, 3.40, 24.90, 22.80, '2024-01-28 06:00:00'),
(17, 1.60, 2.80, 3.30, 25.50, 23.00, '2024-01-28 08:00:00'),
(18, 2.50, 2.50, 2.50, 2.50, 2.50, '2024-01-26 20:44:51'),
(19, 2.50, 2.50, 2.50, 2.50, 2.50, '2024-01-26 20:44:51'),
(20, 2.50, 2.50, 2.50, 2.50, 2.50, '2024-01-26 20:44:51'),
(21, 2.50, 2.50, 2.50, 2.50, 2.50, '2024-01-26 20:44:51'),
(22, 1.10, 2.30, 3.00, 24.70, 22.50, '2024-01-29 00:00:00'),
(23, 1.40, 2.60, 3.10, 25.10, 23.10, '2024-01-29 02:00:00'),
(24, 1.70, 2.90, 3.20, 25.50, 23.40, '2024-01-29 04:00:00'),
(25, 1.20, 2.50, 3.30, 24.80, 22.90, '2024-01-29 06:00:00'),
(26, 1.50, 2.80, 3.40, 25.30, 23.30, '2024-01-29 08:00:00'),
(27, 1.10, 4.30, 3.00, 24.70, 22.50, '2024-01-30 00:00:00'),
(28, 5.40, 2.60, 3.10, 25.10, 23.10, '2024-01-30 02:00:00'),
(29, 1.70, 2.90, 5.20, 25.50, 23.40, '2024-01-30 04:00:00'),
(30, 1.20, 2.50, 3.30, 30.80, 22.90, '2024-01-30 06:00:00'),
(31, 1.50, 2.80, 3.40, 25.30, 25.30, '2024-01-30 08:00:00'),
(32, 0.81, 0.57, 0.42, 0.40, 0.73, '2024-01-01 21:47:00'),
(33, 0.45, 0.05, 0.89, 0.30, 0.82, '2024-01-01 21:47:00'),
(34, 0.21, 0.59, 0.33, 0.88, 0.41, '2024-01-01 21:47:00'),
(35, 0.41, 0.80, 0.80, 0.59, 0.54, '2024-01-01 21:47:00'),
(36, 0.94, 0.09, 0.60, 0.75, 0.93, '2024-01-01 21:47:00');

-- --------------------------------------------------------

--
-- Table structure for table `rawsensor2`
--

CREATE TABLE `rawsensor2` (
  `id` int(11) NOT NULL,
  `nitrogen` float DEFAULT NULL,
  `potassium` float DEFAULT NULL,
  `phosphorus` float DEFAULT NULL,
  `soil_temperature` float DEFAULT NULL,
  `air_temp` float DEFAULT NULL,
  `reading_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rawsensor2`
--

INSERT INTO `rawsensor2` (`id`, `nitrogen`, `potassium`, `phosphorus`, `soil_temperature`, `air_temp`, `reading_time`) VALUES
(1, 0.8, 2, 2.7, 23.8, 21.9, '2024-01-29 00:00:00'),
(2, 1.1, 2.3, 2.8, 24.2, 22.2, '2024-01-29 04:00:00'),
(3, 1.4, 2.6, 2.9, 24.6, 22.5, '2024-01-29 08:00:00'),
(4, 0.9, 2.1, 3.1, 24.1, 21.8, '2024-01-29 12:00:00'),
(5, 1.2, 2.4, 3, 24.5, 22.1, '2024-01-29 15:59:59'),
(6, 0.7, 1.9, 2.6, 23.5, 21.7, '2024-01-30 00:00:00'),
(7, 1, 2.2, 2.7, 23.9, 22, '2024-01-30 04:00:00'),
(8, 1.3, 2.5, 2.8, 24.3, 22.3, '2024-01-30 08:00:00'),
(9, 0.8, 2, 3, 23.8, 21.6, '2024-01-30 12:00:00'),
(10, 1.1, 2.3, 2.9, 24.2, 21.9, '2024-01-30 15:59:59'),
(11, 1.2, 2.5, 3.1, 25.3, 22.7, '2024-01-26 00:00:00'),
(12, 1.5, 2.7, 3, 25.8, 23.2, '2024-01-26 04:00:00'),
(13, 1.8, 2.9, 3.2, 26.2, 23.5, '2024-01-26 08:00:00'),
(14, 1.3, 2.4, 3.4, 24.9, 22.8, '2024-01-26 12:00:00'),
(15, 1.6, 2.8, 3.3, 25.5, 23, '2024-01-26 15:59:59'),
(16, 1.1, 2.3, 3, 24.7, 22.5, '2024-01-27 00:00:00'),
(17, 1.4, 2.6, 3.1, 25.1, 23.1, '2024-01-27 04:00:00'),
(18, 1.7, 2.9, 3.2, 25.5, 23.4, '2024-01-27 08:00:00'),
(19, 1.2, 2.5, 3.3, 24.8, 22.9, '2024-01-27 12:00:00'),
(20, 1.5, 2.8, 3.4, 25.3, 23.3, '2024-01-27 15:59:59'),
(21, 0.9, 2.1, 2.8, 24.2, 22.1, '2024-01-28 00:00:00'),
(22, 1.2, 2.4, 2.9, 24.6, 22.4, '2024-01-28 04:00:00'),
(23, 1.5, 2.7, 3, 25, 22.7, '2024-01-28 08:00:00'),
(24, 1, 2.2, 3.2, 24.5, 22, '2024-01-28 12:00:00'),
(25, 1.3, 2.5, 3.1, 24.9, 22.3, '2024-01-28 15:59:59'),
(26, 0.397946, 0.209077, 0.851546, 0.630499, 0.597857, '2024-01-01 21:47:00'),
(27, 0.0977876, 0.695368, 0.183475, 0.831273, 0.605939, '2024-01-01 21:47:00'),
(28, 0.535878, 0.861571, 0.700223, 0.916401, 0.481334, '2024-01-01 21:47:00'),
(29, 0.65747, 0.843349, 0.244334, 0.691622, 0.72511, '2024-01-01 21:47:00'),
(30, 0.550685, 0.578092, 0.238407, 0.457759, 0.573574, '2024-01-01 21:47:00');

-- --------------------------------------------------------

--
-- Table structure for table `rawsensor3`
--

CREATE TABLE `rawsensor3` (
  `id` int(11) NOT NULL,
  `nitrogen` float DEFAULT NULL,
  `potassium` float DEFAULT NULL,
  `phosphorus` float DEFAULT NULL,
  `soil_temperature` float DEFAULT NULL,
  `air_temp` float DEFAULT NULL,
  `reading_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rawsensor3`
--

INSERT INTO `rawsensor3` (`id`, `nitrogen`, `potassium`, `phosphorus`, `soil_temperature`, `air_temp`, `reading_time`) VALUES
(1, 0.9, 2.1, 2.8, 23.9, 22, '2024-01-26 00:00:00'),
(2, 1.2, 2.4, 2.9, 24.3, 22.3, '2024-01-26 04:00:00'),
(3, 1.5, 2.7, 3, 24.7, 22.6, '2024-01-26 08:00:00'),
(4, 1, 2.2, 3.2, 24.2, 21.9, '2024-01-26 12:00:00'),
(5, 1.3, 2.5, 3.1, 24.6, 22.2, '2024-01-26 15:59:59'),
(6, 0.8, 2, 2.7, 23.8, 21.9, '2024-01-27 00:00:00'),
(7, 1.1, 2.3, 2.8, 24.2, 22.2, '2024-01-27 04:00:00'),
(8, 1.4, 2.6, 2.9, 24.6, 22.5, '2024-01-27 08:00:00'),
(9, 0.9, 2.1, 3.1, 24.1, 21.8, '2024-01-27 12:00:00'),
(10, 1.2, 2.4, 3, 24.5, 22.1, '2024-01-27 15:59:59'),
(11, 0.7, 1.9, 2.6, 23.5, 21.7, '2024-01-28 00:00:00'),
(12, 1, 2.2, 2.7, 23.9, 22, '2024-01-28 04:00:00'),
(13, 1.3, 2.5, 2.8, 24.3, 22.3, '2024-01-28 08:00:00'),
(14, 0.8, 2, 3, 23.8, 21.6, '2024-01-28 12:00:00'),
(15, 1.1, 2.3, 2.9, 24.2, 21.9, '2024-01-28 15:59:59'),
(16, 0.8, 2, 2.7, 23.8, 21.9, '2024-01-29 00:00:00'),
(17, 1.1, 2.3, 2.8, 24.2, 22.2, '2024-01-29 04:00:00'),
(18, 1.4, 2.6, 2.9, 24.6, 22.5, '2024-01-29 08:00:00'),
(19, 0.9, 2.1, 3.1, 24.1, 21.8, '2024-01-29 12:00:00'),
(20, 1.2, 2.4, 3, 24.5, 22.1, '2024-01-29 15:59:59'),
(21, 0.7, 1.9, 2.6, 23.5, 21.7, '2024-01-30 00:00:00'),
(22, 1, 2.2, 2.7, 23.9, 22, '2024-01-30 04:00:00'),
(23, 1.3, 2.5, 2.8, 24.3, 22.3, '2024-01-30 08:00:00'),
(24, 0.8, 2, 3, 23.8, 21.6, '2024-01-30 12:00:00'),
(25, 1.1, 2.3, 2.9, 24.2, 21.9, '2024-01-30 15:59:59'),
(26, 0.494594, 0.752247, 0.277453, 0.130525, 0.820263, '2024-01-01 21:47:00'),
(27, 0.709743, 0.0879272, 0.310405, 0.288246, 0.510012, '2024-01-01 21:47:00'),
(28, 0.685321, 0.896571, 0.426891, 0.444743, 0.94304, '2024-01-01 21:47:00'),
(29, 0.380973, 0.0757436, 0.2358, 0.951768, 0.0514406, '2024-01-01 21:47:00'),
(30, 0.401899, 0.855174, 0.0701711, 0.785334, 0.716159, '2024-01-01 21:47:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `avgsensor1`
--
ALTER TABLE `avgsensor1`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `avgsensor2`
--
ALTER TABLE `avgsensor2`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `avgsensor3`
--
ALTER TABLE `avgsensor3`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `overall_data`
--
ALTER TABLE `overall_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rawsensor1`
--
ALTER TABLE `rawsensor1`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rawsensor2`
--
ALTER TABLE `rawsensor2`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rawsensor3`
--
ALTER TABLE `rawsensor3`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `avgsensor1`
--
ALTER TABLE `avgsensor1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `avgsensor2`
--
ALTER TABLE `avgsensor2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `avgsensor3`
--
ALTER TABLE `avgsensor3`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `overall_data`
--
ALTER TABLE `overall_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `rawsensor1`
--
ALTER TABLE `rawsensor1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `rawsensor2`
--
ALTER TABLE `rawsensor2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `rawsensor3`
--
ALTER TABLE `rawsensor3`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
