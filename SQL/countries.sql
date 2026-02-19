-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: 19.02.2026 klo 07:26
-- Palvelimen versio: 10.6.23-MariaDB-0ubuntu0.22.04.1
-- PHP Version: 8.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aircup`
--

-- --------------------------------------------------------

--
-- Rakenne taululle `countries`
--

CREATE TABLE `countries` (
  `country_id` int(11) NOT NULL,
  `country_name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Vedos taulusta `countries`
--

INSERT INTO `countries` (`country_id`, `country_name`) VALUES
(1, 'Suomi'),
(2, 'Ruotsi'),
(3, 'Viro'),
(4, 'Latvia'),
(5, 'Norja'),
(6, 'Liettua'),
(7, 'Tanska'),
(8, 'Puola'),
(9, 'Itävalta'),
(10, 'Italia'),
(11, 'Tsekki'),
(12, 'Saksa'),
(13, 'Ranska'),
(14, 'Belgia'),
(15, 'Hollanti'),
(16, 'Espanja'),
(17, 'Portugal'),
(18, 'Slovakia'),
(19, 'Slovenia'),
(20, 'Unkari'),
(21, 'Sveitsi'),
(22, 'Kreikka'),
(23, 'Romania'),
(24, 'Ukraina'),
(25, 'Serbia'),
(26, 'Bulgaria'),
(27, 'Kroatia'),
(28, 'Albania'),
(29, 'Moldova'),
(30, 'Bosnia ja Herzegovina'),
(31, 'Iso-Britannia'),
(32, 'Irlanti'),
(33, 'Pohjois Makedonia'),
(34, 'Luxembourg'),
(35, 'Montenegro'),
(36, 'Malta'),
(37, 'Islanti'),
(38, 'Tunisia'),
(39, 'Algeria'),
(40, 'Marokko'),
(41, 'Libya'),
(42, 'Egypti');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`country_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `country_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
