-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 28, 2024 at 12:19 PM
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
-- Database: `noodle`
--

-- --------------------------------------------------------

--
-- Table structure for table `letniki`
--

CREATE TABLE `letniki` (
  `id` int(11) NOT NULL,
  `ime` varchar(50) NOT NULL,
  `program_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `letniki`
--

INSERT INTO `letniki` (`id`, `ime`, `program_id`) VALUES
(3, 'Testni letnik 1', 3);

-- --------------------------------------------------------

--
-- Table structure for table `naloge`
--

CREATE TABLE `naloge` (
  `id` int(11) NOT NULL,
  `naslov` varchar(200) NOT NULL,
  `opis` text NOT NULL,
  `datum_oddaje` date DEFAULT NULL,
  `predmet_id` int(11) DEFAULT NULL,
  `avtor_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `novice`
--

CREATE TABLE `novice` (
  `id` int(11) NOT NULL,
  `naslov` varchar(200) NOT NULL,
  `vsebina` text NOT NULL,
  `datum` datetime DEFAULT current_timestamp(),
  `avtor_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oddane_naloge`
--

CREATE TABLE `oddane_naloge` (
  `id` int(11) NOT NULL,
  `naloga_id` int(11) DEFAULT NULL,
  `dijak_id` int(11) DEFAULT NULL,
  `datoteka` varchar(255) DEFAULT NULL,
  `ocena` int(11) DEFAULT NULL,
  `datum_oddaje` datetime DEFAULT current_timestamp(),
  `datoteka_pot` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `predmeti`
--

CREATE TABLE `predmeti` (
  `id` int(11) NOT NULL,
  `ime` varchar(100) NOT NULL,
  `letnik_id` int(11) DEFAULT NULL,
  `kljuc` varchar(50) DEFAULT NULL,
  `profesor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `predmeti`
--

INSERT INTO `predmeti` (`id`, `ime`, `letnik_id`, `kljuc`, `profesor_id`) VALUES
(6, 'prof', 3, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `prijave_predmetov`
--

CREATE TABLE `prijave_predmetov` (
  `id` int(11) NOT NULL,
  `dijak_id` int(11) DEFAULT NULL,
  `predmet_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `programi`
--

CREATE TABLE `programi` (
  `id` int(11) NOT NULL,
  `ime` varchar(100) NOT NULL,
  `sola_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programi`
--

INSERT INTO `programi` (`id`, `ime`, `sola_id`) VALUES
(3, 'Testni program 1', 3);

-- --------------------------------------------------------

--
-- Table structure for table `sole`
--

CREATE TABLE `sole` (
  `id` int(11) NOT NULL,
  `ime` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sole`
--

INSERT INTO `sole` (`id`, `ime`) VALUES
(3, 'Testna Šola 1l');

-- --------------------------------------------------------

--
-- Table structure for table `uporabniki`
--

CREATE TABLE `uporabniki` (
  `id` int(11) NOT NULL,
  `uporabnisko_ime` varchar(50) NOT NULL,
  `geslo` varchar(255) NOT NULL,
  `ime` varchar(50) DEFAULT NULL,
  `priimek` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `tip` enum('dijak','profesor','skrbnik') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `uporabniki`
--

INSERT INTO `uporabniki` (`id`, `uporabnisko_ime`, `geslo`, `ime`, `priimek`, `email`, `tip`) VALUES
(11, 'admin', '$2y$10$3JXVgbz6w3j9y0bnb4VKRe96BL6t4ork9B7Exmk64rJo6op6UDuJ.', 'Admin', 'Skrbnik', 'admin@noodle.si', 'skrbnik'),
(12, 'dijak', '$2y$10$KCilXmuMYvEMALuWFj4qWOUJUAtapv3Ulk4kkeIEjSeTp3Hfkc2R6', 'dijak', 'dijak', 'dijak@dijak', 'dijak'),
(13, 'prof', '$2y$10$6b9hiQ/mvmyX2m.KTL0GQe6hgs5nTYZwgYt6h7OlnG1zyTVhCnlqi', 'prof', 'prof', 'prof@1', 'profesor');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `letniki`
--
ALTER TABLE `letniki`
  ADD PRIMARY KEY (`id`),
  ADD KEY `program_id` (`program_id`);

--
-- Indexes for table `naloge`
--
ALTER TABLE `naloge`
  ADD PRIMARY KEY (`id`),
  ADD KEY `predmet_id` (`predmet_id`),
  ADD KEY `avtor_id` (`avtor_id`);

--
-- Indexes for table `novice`
--
ALTER TABLE `novice`
  ADD PRIMARY KEY (`id`),
  ADD KEY `avtor_id` (`avtor_id`);

--
-- Indexes for table `oddane_naloge`
--
ALTER TABLE `oddane_naloge`
  ADD PRIMARY KEY (`id`),
  ADD KEY `naloga_id` (`naloga_id`),
  ADD KEY `dijak_id` (`dijak_id`);

--
-- Indexes for table `predmeti`
--
ALTER TABLE `predmeti`
  ADD PRIMARY KEY (`id`),
  ADD KEY `letnik_id` (`letnik_id`);

--
-- Indexes for table `prijave_predmetov`
--
ALTER TABLE `prijave_predmetov`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dijak_id` (`dijak_id`,`predmet_id`),
  ADD KEY `predmet_id` (`predmet_id`);

--
-- Indexes for table `programi`
--
ALTER TABLE `programi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sola_id` (`sola_id`);

--
-- Indexes for table `sole`
--
ALTER TABLE `sole`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uporabniki`
--
ALTER TABLE `uporabniki`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uporabnisko_ime` (`uporabnisko_ime`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `letniki`
--
ALTER TABLE `letniki`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `naloge`
--
ALTER TABLE `naloge`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `novice`
--
ALTER TABLE `novice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `oddane_naloge`
--
ALTER TABLE `oddane_naloge`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `predmeti`
--
ALTER TABLE `predmeti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `prijave_predmetov`
--
ALTER TABLE `prijave_predmetov`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `programi`
--
ALTER TABLE `programi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sole`
--
ALTER TABLE `sole`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `uporabniki`
--
ALTER TABLE `uporabniki`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `letniki`
--
ALTER TABLE `letniki`
  ADD CONSTRAINT `letniki_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `programi` (`id`);

--
-- Constraints for table `naloge`
--
ALTER TABLE `naloge`
  ADD CONSTRAINT `naloge_ibfk_1` FOREIGN KEY (`predmet_id`) REFERENCES `predmeti` (`id`),
  ADD CONSTRAINT `naloge_ibfk_2` FOREIGN KEY (`avtor_id`) REFERENCES `uporabniki` (`id`);

--
-- Constraints for table `novice`
--
ALTER TABLE `novice`
  ADD CONSTRAINT `novice_ibfk_1` FOREIGN KEY (`avtor_id`) REFERENCES `uporabniki` (`id`);

--
-- Constraints for table `oddane_naloge`
--
ALTER TABLE `oddane_naloge`
  ADD CONSTRAINT `oddane_naloge_ibfk_1` FOREIGN KEY (`naloga_id`) REFERENCES `naloge` (`id`),
  ADD CONSTRAINT `oddane_naloge_ibfk_2` FOREIGN KEY (`dijak_id`) REFERENCES `uporabniki` (`id`);

--
-- Constraints for table `predmeti`
--
ALTER TABLE `predmeti`
  ADD CONSTRAINT `predmeti_ibfk_1` FOREIGN KEY (`letnik_id`) REFERENCES `letniki` (`id`);

--
-- Constraints for table `prijave_predmetov`
--
ALTER TABLE `prijave_predmetov`
  ADD CONSTRAINT `prijave_predmetov_ibfk_1` FOREIGN KEY (`dijak_id`) REFERENCES `uporabniki` (`id`),
  ADD CONSTRAINT `prijave_predmetov_ibfk_2` FOREIGN KEY (`predmet_id`) REFERENCES `predmeti` (`id`);

--
-- Constraints for table `programi`
--
ALTER TABLE `programi`
  ADD CONSTRAINT `programi_ibfk_1` FOREIGN KEY (`sola_id`) REFERENCES `sole` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
