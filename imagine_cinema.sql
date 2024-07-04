-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 01, 2024 at 05:34 PM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `imagine_cinema`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_username` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_username`, `password`) VALUES
('admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `username` varchar(250) NOT NULL,
  `user_email` varchar(250) NOT NULL,
  `seats` int(11) NOT NULL,
  `booking_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `movie_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `movie_id`, `username`, `user_email`, `seats`, `booking_time`, `movie_name`) VALUES
(9, 0, 7, 'Pamilerin', 'pamilerin@gmail.com', 12, '2024-07-01 20:16:21', 'STARS'),
(10, 0, 12, 'Pamilerin', 'pamilerin@gmail.com', 100, '2024-07-01 20:16:45', 'ADVENTURE'),
(11, 0, 9, 'GeeSam', 'geesam@gmail.com', 1, '2024-07-01 20:17:42', 'GUY'),
(12, 0, 11, 'GeeSam', 'geesam@gmail.com', 20, '2024-07-01 20:17:59', 'FREE GUY'),
(13, 0, 11, 'GeeSam ', 'geesam@gmail.com', 90, '2024-07-02 00:31:17', 'FREE GUY');

-- --------------------------------------------------------

--
-- Table structure for table `movie`
--

CREATE TABLE `movie` (
  `movie_id` int(11) NOT NULL,
  `filename` varchar(250) NOT NULL,
  `names` varchar(250) NOT NULL,
  `showtime` datetime NOT NULL,
  `total_seats` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `movie`
--

INSERT INTO `movie` (`movie_id`, `filename`, `names`, `showtime`, `total_seats`) VALUES
(7, '../assets/images/star wars.jpg', 'STARS', '2024-07-04 19:40:00', 45),
(9, '../assets/images/free guy.jpg', 'GUY', '2024-06-27 07:50:00', 900),
(10, '../assets/images/blade runner.jpg', 'RUNNER', '2024-06-27 21:41:00', 88),
(11, '../assets/images/free guy.jpg', 'FREE GUY', '2024-07-31 07:09:00', 10),
(12, '../assets/images/adventures.jpg', 'ADVENTURE', '2024-07-30 03:12:00', 10);

-- --------------------------------------------------------

--
-- Table structure for table `temp_updates`
--

CREATE TABLE `temp_updates` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `created_at`) VALUES
(1, 'GEESAM', '1111', '2024-06-26 13:15:19'),
(2, 'Testing', '1111', '2024-06-26 13:45:54'),
(3, 'Mich', '1111', '2024-06-28 06:55:36'),
(4, 'Pamilerin', '1111', '2024-07-01 11:12:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_username`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_bookings_username` (`username`);

--
-- Indexes for table `movie`
--
ALTER TABLE `movie`
  ADD PRIMARY KEY (`movie_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `movie`
--
ALTER TABLE `movie`
  MODIFY `movie_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `fk_bookings_username` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `process_temp_updates` ON SCHEDULE EVERY 1 MINUTE STARTS '2024-06-28 00:55:49' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
    UPDATE bookings b
    JOIN temp_updates t ON b.id = t.booking_id
    SET b.user_id = t.user_id;

    DELETE FROM temp_updates;
END$$

DELIMITER ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
