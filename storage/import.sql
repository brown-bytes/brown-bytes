-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 24, 2018 at 11:08 PM
-- Server version: 10.1.32-MariaDB
-- PHP Version: 5.6.36

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `brown_bytes`
--

-- --------------------------------------------------------

--
-- Table structure for table `cms__users`
--

CREATE TABLE `cms__users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(70) NOT NULL,
  `email` varchar(70) NOT NULL,
  `password` varchar(255) NOT NULL,
  `verify` varchar(255) NOT NULL,
  `last_login` datetime NOT NULL,
  `admin` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0',
  `api_private` varchar(255) NOT NULL,
  `created_on` datetime NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cms__users`
--

INSERT INTO `cms__users` (`id`, `name`, `email`, `password`, `verify`, `last_login`, `admin`, `status`, `api_private`, `created_on`, `timestamp`) VALUES
(1, 'Scott Huson', 'scotth@brown.edu', '', '', '0000-00-00 00:00:00', 0, 0, '', '0000-00-00 00:00:00', '2018-06-14 06:16:02'),
(2, 'Scott Huson', 'scott_huson@brown.edu', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', '5b22361f07aa4', '2018-06-14 17:32:15', 1, 3, '52c45011fa3771bed4559d940e2e00fe', '2018-06-14 17:32:15', '2018-06-14 11:01:55');

-- --------------------------------------------------------

--
-- Table structure for table `cms__user_address`
--

CREATE TABLE `cms__user_address` (
  `id` int(11) NOT NULL,
  `address` varchar(50) NOT NULL,
  `anonymous` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cms__user_address`
--

INSERT INTO `cms__user_address` (`id`, `address`, `anonymous`, `user_id`, `timestamp`) VALUES
(1, '3b3cd606db09c5e52c3b4a0416f1ea1e', 0, 2, '2018-07-16 00:53:03'),
(2, '45c1dd679b967dde66999bcc086dd9ab', 0, 2, '2018-07-16 00:54:08'),
(3, '21fccd58b44ce8dfa1388095c4469432', 0, 2, '2018-07-16 00:57:54'),
(4, '925dfaba4f84a0a5c3d84545eddb892a', 0, 2, '2018-07-16 00:58:21');

-- --------------------------------------------------------

--
-- Table structure for table `plugin__comment`
--

CREATE TABLE `plugin__comment` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `anonymous` int(11) NOT NULL,
  `content` tinytext NOT NULL,
  `offer_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `plugin__comment`
--

INSERT INTO `plugin__comment` (`id`, `user_id`, `anonymous`, `content`, `offer_id`, `timestamp`) VALUES
(1, 1, 1, 'this is a test', 2, '2018-08-20 01:50:58'),
(2, 2, 0, 'this is comment 2', 2, '2018-08-16 23:55:58');

-- --------------------------------------------------------

--
-- Table structure for table `plugin__location`
--

CREATE TABLE `plugin__location` (
  `id` int(11) NOT NULL,
  `visible` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `points` int(11) NOT NULL,
  `swipes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `plugin__location`
--

INSERT INTO `plugin__location` (`id`, `visible`, `title`, `timestamp`, `points`, `swipes`) VALUES
(1, 1, 'Andrews Commons', '2018-07-11 19:01:51', 1, 1),
(2, 1, 'The Sharpe Refactory', '2018-08-22 17:50:20', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `plugin__offer`
--

CREATE TABLE `plugin__offer` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `location` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `duration` int(11) NOT NULL,
  `anonymous` int(11) NOT NULL DEFAULT '0',
  `created` int(11) NOT NULL,
  `expires` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `plugin__offer`
--

INSERT INTO `plugin__offer` (`id`, `title`, `location`, `user_id`, `duration`, `anonymous`, `created`, `expires`, `timestamp`) VALUES
(2, 'Free points edited!', 1, 2, 24, 0, 1535136966, 1535140831, '2018-08-24 18:56:42');

-- --------------------------------------------------------

--
-- Table structure for table `plugin__product`
--

CREATE TABLE `plugin__product` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `access` int(11) NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `plugin__transaction`
--

CREATE TABLE `plugin__transaction` (
  `id` int(11) NOT NULL,
  `sender_address` varchar(255) NOT NULL,
  `recipient_address` varchar(255) NOT NULL,
  `description` tinytext NOT NULL,
  `amount` decimal(10,0) NOT NULL,
  `location_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `platform` int(11) NOT NULL DEFAULT '1',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `txn_id` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `plugin__transaction`
--

INSERT INTO `plugin__transaction` (`id`, `sender_address`, `recipient_address`, `description`, `amount`, `location_id`, `status`, `platform`, `timestamp`, `txn_id`) VALUES
(9, '2741dde1f0359dc44656a8c0ff9cb343', '', 'testing', '2', 1, 0, 1, '2018-07-16 00:34:11', '5b4be80350611'),
(10, 'fbefd835dfadbbbc7e93cd0a5b507918', '', 'testing', '2', 1, 0, 1, '2018-07-16 00:35:20', '5b4be8484d723'),
(11, '57ba18db6410ec9d0303c265b1223adb', '', 'testing', '2', 1, 0, 1, '2018-07-16 00:35:39', '5b4be85b841a6'),
(12, 'dff5f5fcbf2cb17427a82687c246bd00', '', 'testing', '2', 1, 0, 1, '2018-07-16 00:35:55', '5b4be86b78366'),
(13, 'ca29819f27a56332cbc22e2a9369de2b', '', 'testing', '10', 1, 0, 1, '2018-07-16 00:37:33', '5b4be8cd4341b'),
(14, '5658407897223e6a02ace810b4e31f4f', '', 'testing', '10', 1, 0, 1, '2018-07-16 00:37:50', '5b4be8decbe25'),
(15, '72f86551fd0358d28b87433109d7eff6', '', 'testing', '10', 1, 0, 1, '2018-07-16 00:38:08', '5b4be8f009cf3'),
(16, 'dc7338ade3e100654d807de6a4be9013', '', 'testing', '10', 1, 0, 1, '2018-07-16 00:40:21', '5b4be97517eec'),
(17, '44ffaa0f4b76a6a0b283223ec86725a9', '', 'testing', '10', 1, 0, 1, '2018-07-16 00:41:03', '5b4be99f0306b'),
(18, '098c0212c87d0e23fb8c900f04c0430c', '', 'testing', '10', 1, 0, 1, '2018-07-16 00:45:16', '5b4bea9c82a86'),
(19, 'b5fce0454ead4bfaee83e716b6a3cad0', '', 'testing', '20', 1, 0, 1, '2018-07-16 00:46:55', '5b4beaff3afd2'),
(20, 'cbb10c6203a538efb5e2560a283e99c2', '', 'testing', '29', 1, 0, 1, '2018-07-16 00:49:53', '5b4bebb153353'),
(21, 'bbc9aff0a030737cfe6e74e2f7b98762', '', 'testing', '29', 1, 0, 1, '2018-07-16 00:50:37', '5b4bebdda2aa2'),
(22, '57ad490b398fb30e30bc36f2c50add49', '', 'testing', '10', 1, 0, 1, '2018-07-16 00:51:05', '5b4bebf964715'),
(23, '3b3cd606db09c5e52c3b4a0416f1ea1e', '', 'testing', '19', 1, 0, 1, '2018-07-16 00:53:03', '5b4bec6f248b4'),
(24, '45c1dd679b967dde66999bcc086dd9ab', '', 'testing2', '10', 1, 0, 1, '2018-07-16 00:54:08', '5b4becb0b54b6'),
(25, '21fccd58b44ce8dfa1388095c4469432', '', 'testing2', '10', 1, 0, 1, '2018-07-16 00:57:54', '5b4bed92f0ad6'),
(26, '925dfaba4f84a0a5c3d84545eddb892a', '', 'testing2', '10', 1, 0, 1, '2018-07-16 00:58:21', '5b4bedad4117e');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cms__users`
--
ALTER TABLE `cms__users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms__user_address`
--
ALTER TABLE `cms__user_address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plugin__comment`
--
ALTER TABLE `plugin__comment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plugin__location`
--
ALTER TABLE `plugin__location`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plugin__offer`
--
ALTER TABLE `plugin__offer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plugin__product`
--
ALTER TABLE `plugin__product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plugin__transaction`
--
ALTER TABLE `plugin__transaction`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cms__users`
--
ALTER TABLE `cms__users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cms__user_address`
--
ALTER TABLE `cms__user_address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `plugin__comment`
--
ALTER TABLE `plugin__comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `plugin__location`
--
ALTER TABLE `plugin__location`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `plugin__offer`
--
ALTER TABLE `plugin__offer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `plugin__product`
--
ALTER TABLE `plugin__product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plugin__transaction`
--
ALTER TABLE `plugin__transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
