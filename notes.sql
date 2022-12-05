-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 14, 2020 at 08:40 PM
-- Server version: 5.7.11
-- PHP Version: 7.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `notes`
--

-- --------------------------------------------------------

--
-- Table structure for table `пользователи`
--

CREATE TABLE `пользователи` (
  `Код пользователя` int(11) NOT NULL,
  `Логин` varchar(50) NOT NULL,
  `Пароль` varchar(255) NOT NULL,
  `Дата регистрации` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `пользователи`
--

INSERT INTO `пользователи` (`Код пользователя`, `Логин`, `Пароль`, `Дата регистрации`) VALUES
(1, 'Petr', '12345', '2020-03-14 00:00:00'),
(2, 'Ivan', '09876', '2020-03-14 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `пользователи`
--
ALTER TABLE `пользователи`
  ADD PRIMARY KEY (`Код пользователя`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `пользователи`
--
ALTER TABLE `пользователи`
  MODIFY `Код пользователя` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
