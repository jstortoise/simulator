-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 03, 2019 at 04:08 AM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simulator`
--

-- --------------------------------------------------------

--
-- Table structure for table `fields`
--

CREATE TABLE `fields` (
  `id` int(11) NOT NULL,
  `field_id` varchar(255) DEFAULT NULL,
  `field_label` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `type` int(11) DEFAULT 0,
  `description` longtext NOT NULL,
  `new_field` tinyint(1) NOT NULL DEFAULT 0,
  `editable` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `fields`
--

INSERT INTO `fields` (`id`, `field_id`, `field_label`, `value`, `unit`, `type`, `description`, `defaultfield`, `editable`) VALUES
(1, 'f7', 'Type de contrat', 'CDI', NULL, 1, 'sdasd', 1, 0),
(2, 'f8', 'feuille de paye', '30', '€', 0, '', 1, 1),
(3, 'f9', 'Mutuelle', '20,30,40', '€', 1, 'testing', 1, 0),
(5, 'f10', 'Assurance salarié', '26', '€', 0, 'asdas', 1, 1),
(6, 'f11', 'Assurance ESN', '100', '€', 0, '', 1, 1),
(7, 'f13', 'URSAAF', '1200', '€', 0, '', 1, 0),
(8, 'f14', 'AG2R', '235', '€', 0, '', 1, 1),
(9, 'f15', 'Outils', '50', '€', 0, '', 1, 1),
(10, 'f16', 'Admin CDI', '200,300', '€', 1, '', 1, 1),
(11, 'f24', 'Réserve salaire ratio', '0.25', 'mois', 0, '', 1, 0),
(12, 'f27', 'Réserve employabilité', '400', '€', 0, '', 1, 0),
(13, 'f1', 'Forfait Client jour actuel', '500', '€', 0, 'testing', 1, 0),
(14, 'f4', 'Reduction Client', '12', '%', 0, 'testing', 1, 1),
(15, 'f5', 'Forfait Client jour avec remise de base', '440', '€', 0, 'testing', 1, 1),
(16, 'f18', 'Demande Spoc', NULL, NULL, 0, 'testing', 1, 1),
(17, 'f20', 'Recouvrement', NULL, NULL, 0, 'testing', 1, 0),
(18, 'f32', 'Forfait client jour final', '1140', NULL, 0, 'testing', 0, 1),
(19, 'f19', 'Remise Spoc', '500', '€\r\n', 0, 'testing', 1, 1),
(20, 'f21', 'Remise recouvrement', '200', '€', 0, 'testing', 1, 1),
(21, 'f6', 'Salaire mensuel actuel net', '3100', '€', 0, 'testing', 1, 0),
(22, 'f2', 'Nombre de jour travaillé', '216', 'jour', 0, 'testing', 1, 1),
(23, 'f35', 'KKK', '12,23,123', '$', 1, 'testing', 0, 0),
(24, 'f3', 'Nombre de RTT', '11', 'jour', 0, 'testing', 1, 0),
(25, 'f17', 'Fees TT', '1312.5547445255472', '€', 0, 'testing', 1, 1),
(26, 'f23', 'Réserve salaire en mois', '3', 'mois', 0, '', 1, 0),
(27, 'f30', 'Salaire mensuel cible initial', '10937.956204379561', '€', 0, 'testing', 1, 1),
(28, 'f31', 'Salaire mensuel cible après 3 mois', '13672.445255474453', '€', 0, 'testing', 1, 1),
(39, 'f36', 'field5', '12,23,56', '$', 1, 'testing', 0, 0),
(40, 'f37', 'ddd', '28', '%', 0, 'qweqeqewqeqe', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `formulas`
--

CREATE TABLE `formulas` (
  `id` int(11) NOT NULL,
  `field_id` varchar(255) DEFAULT NULL,
  `formula` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `formulas`
--

INSERT INTO `formulas` (`id`, `field_id`, `formula`) VALUES
(1, 'f5', 'f1*(1-f4)'),
(2, 'f12', '5*f5'),
(3, 'f17', '0.12*f30'),
(4, 'f19', '-f18*500'),
(5, 'f21', '-f20*200'),
(6, 'f25', 'f30*f24'),
(7, 'f27', 'f26*400'),
(8, 'f30', '(f33-f8-f9-f10-f11-f12-f13-f14-f15-f16-f27)/(1+0.12+f24)'),
(9, 'f31', 'f33-f8-f9-f10-f11-f12-f13-f14-f15-f16-f17-f27'),
(10, 'f32', 'f5-f19-f21'),
(11, 'f33', 'f32*(f2-f3)/12'),
(12, 'f34', '100*(f31-f6)/f6');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `username` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`username`, `password`, `role`) VALUES
('admin', 'admin', '0'),
('user', 'user', '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `fields`
--
ALTER TABLE `fields`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `type` (`type`);
ALTER TABLE `fields` ADD FULLTEXT KEY `description` (`value`);

--
-- Indexes for table `formulas`
--
ALTER TABLE `formulas`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`username`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `fields`
--
ALTER TABLE `fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `formulas`
--
ALTER TABLE `formulas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
