-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 26, 2024 at 12:41 PM
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
-- Database: `mrs`
--

-- --------------------------------------------------------

--
-- Table structure for table `approved`
--

CREATE TABLE `approved` (
  `approved_id` int(11) NOT NULL,
  `r_id` int(11) NOT NULL,
  `motor_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `r_dstntn` varchar(35) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `r_date` varchar(35) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `r_hr` varchar(35) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `r_ampm` varchar(35) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `date_approved` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `motors`
--

CREATE TABLE `motors` (
  `motor_id` int(11) NOT NULL,
  `b_name` varchar(35) NOT NULL,
  `m_quantity` varchar(35) NOT NULL,
  `b_model` varchar(35) NOT NULL,
  `b_img` varchar(255) NOT NULL,
  `b_price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `motors`
--

INSERT INTO `motors` (`motor_id`, `b_name`, `m_quantity`, `b_model`, `b_img`, `b_price`) VALUES
(56, 'Honda', '30', 'x-blade', '../boat_image/image_2024-05-24-16-58-43_6650ab23091ee.jpg', 300000),
(58, 'Honda', '25', 'SP 125 Drum', '../boat_image/image_2024-05-24-16-57-17_6650aacd81509.jpg', 140000),
(59, 'Honda', '100', 'SP 125 Disc', '../boat_image/image_2024-05-24-16-55-52_6650aa782f169.jpg', 150000),
(60, 'Honda', '10', 'CB300R First Look 2024', '../boat_image/image_2024-05-24-16-53-47_6650a9fb485fc.jpg', 300000),
(61, 'Honda', '20', 'CB1000R Neo Sports Cafe ABS 2024', '../boat_image/image_2024-05-24-16-51-50_6650a986c8e60.jpg', 1600000),
(62, 'Honda', '15', 'CRF1000L AFRICA TWIN ', '../boat_image/image_2024-05-24-16-49-00_6650a8dc772d2.jpg', 1250000),
(64, 'Honda', '15', 'CBR 250R', '../boat_image/image_2024-05-24-16-46-19_6650a83b433c3.jpg', 350000),
(65, 'Honda', '15', 'CBR 650R', '../boat_image/image_2024-05-24-16-44-56_6650a7e8482da.jpg', 735000),
(66, 'Honda', '19', 'CBR1000RR', '../boat_image/image_2024-05-24-16-43-04_6650a778e4712.jpg', 1000000),
(74, 'Honda', '15 ', 'CBR1000RR-R Fireblade SP', '../boat_image/image_2024-05-24-15-57-23_66509cc36455d.jpg', 1050000);

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

CREATE TABLE `reservation` (
  `r_id` int(11) NOT NULL,
  `motor_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `r_dstntn` varchar(35) NOT NULL,
  `r_date` varchar(35) NOT NULL,
  `r_hr` varchar(35) NOT NULL,
  `r_ampm` varchar(35) NOT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_fN` varchar(50) NOT NULL,
  `user_mN` varchar(50) NOT NULL,
  `user_lN` varchar(50) NOT NULL,
  `user_address` varchar(255) NOT NULL,
  `tour_contact` varchar(15) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `user_pass` varchar(35) NOT NULL,
  `user_type` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_fN`, `user_mN`, `user_lN`, `user_address`, `tour_contact`, `user_name`, `user_pass`, `user_type`) VALUES
(27, 'Bimbo', 'Escodero', 'Villaganas', 'Tankulan', '09876543211', 'Bimbo', 'c805e9627031e47fc07d04460fb757e6', '1'),
(28, 'Armar Jun', 'Polgarinas', 'Acuzar', 'Agusan', '0977777777', 'Armar', '4161daae9b81b82eed321c392d6fef39', '2'),
(29, 'User1', 'User1', 'User1', 'Sample Address', '09090909491', 'User1', '6b908b785fdba05a6446347dae08d8c5', '3');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `approved`
--
ALTER TABLE `approved`
  ADD PRIMARY KEY (`approved_id`),
  ADD KEY `r_id` (`r_id`);

--
-- Indexes for table `motors`
--
ALTER TABLE `motors`
  ADD PRIMARY KEY (`motor_id`);

--
-- Indexes for table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`r_id`),
  ADD KEY `b_id` (`motor_id`),
  ADD KEY `tour_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `approved`
--
ALTER TABLE `approved`
  MODIFY `approved_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `motors`
--
ALTER TABLE `motors`
  MODIFY `motor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `r_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `approved`
--
ALTER TABLE `approved`
  ADD CONSTRAINT `approved_ibfk_1` FOREIGN KEY (`r_id`) REFERENCES `reservation` (`r_id`);

--
-- Constraints for table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`motor_id`) REFERENCES `motors` (`motor_id`),
  ADD CONSTRAINT `reservation_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
