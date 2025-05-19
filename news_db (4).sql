-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 19, 2025 at 06:14 AM
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
-- Database: `news_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `email`, `password`) VALUES
(1, 'Admin Finias', 'adminfinias@yahoo.com', '$2y$10$ud8SemsmGWuxs0CV2MbHW.q6tmtLAX6QU4gtL57iwy0FmKFEp2pnW'),
(3, 'Japhsam Simulator', 'adminjaphsam@gmail.com', '$2y$10$Wcg55GFr.Fc.RBnVSF2wGOvPA8hdBOKTjoz2oZLTX0hSS4.NO1fMS');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `content_sw` text DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `published_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `content`, `content_sw`, `category`, `image`, `author`, `published_at`) VALUES
(1, 'AI Revolution: The Future of Smart Machines', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla facilisi. Vestibulum nec tortor nec urna commodo tincidunt. Integer vitae diam id odio volutpat venenatis. Suspendisse potenti. Quisque id ipsum a ligula tincidunt fermentum et eu metus.', NULL, 'Technology', 'uploads/img_67e8cf9702922.avif', 'Finias', '2025-03-30 04:59:03'),
(2, 'AI Revolution: The Future of Smart Machines', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla facilisi. Vestibulum nec tortor nec urna commodo tincidunt. Integer vitae diam id odio volutpat venenatis. Suspendisse potenti. Quisque id ipsum a ligula tincidunt fermentum et eu metus.', NULL, 'Technology', 'uploads/img_67e8d3904d87d.avif', 'ali mnyika', '2025-03-30 05:16:00'),
(3, 'Champion League Final: A Historic Match!', 'Pellentesque a metus id erat fermentum lacinia. Nulla facilisi. Duis volutpat, justo nec fermentum pharetra, arcu risus scelerisque magna, at posuere nulla augue et lectus. Cras vitae tellus ut elit maximus tincidunt.', NULL, 'Sports', 'uploads/img_67e8d3c877a60.avif', 'japhsam', '2025-03-30 05:16:56'),
(9, '80 Years of Freedom: The resistance leader who played in the first-ever Netherlands game', 'On May 5th, the Netherlands celebrates 80 years of freedom. Precisely 80 years after the liberation from Nazi Germany, the once war-torn country remembers those who fell and those who stood up during the most challenging times in its modern history.\r\nEven under Nazi Germany’s ruthless rule, sport kept the Dutch people going. Football remained largely untouched by the occupiers and attracted thousands in attendance.\r\n\r\nBut the pitches, courts, and fields also harboured heroes of the Dutch resistance, who fought for the country’s freedom by giving shelter to Jewish families, sabotaging German war efforts, being the extension of the Dutch government-in-exile in London, and standing up to oppression.\r\n\r\nDuring the week in which we celebrate 80 years of freedom, we tell the stories of the resistance heroes who lived double lives in and away from the theatre of their sport.', NULL, 'Politics', '../uploads/news_68216723ed0ac.png', 'japhsam', '2025-05-12 03:12:35');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'Finias Petro Fikiri', 'finiasfikiri@gmail.com', '$2y$10$bCTmKTgZ4gzEI2ff.dsRV.xiIb/qCvUtT3dGAp4naFqntlhhAgr32', '2025-03-06 11:17:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
