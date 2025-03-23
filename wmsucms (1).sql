-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 23, 2025 at 03:54 PM
-- Server version: 11.4.5-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wmsucms`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `email`, `password`) VALUES
(1, 'cornelio.vaniel38@gmail.com', '$2y$10$HhZEuNhnrXIe7DZHu0rixuDldWjpc6DOqmqMRK.p11t67i1ZoVIky');

-- --------------------------------------------------------

--
-- Table structure for table `generalelements`
--

CREATE TABLE `generalelements` (
  `elementID` int(11) NOT NULL,
  `indicator` varchar(255) NOT NULL,
  `elemType` enum('text','image') NOT NULL,
  `content` text DEFAULT NULL,
  `imagePath` varchar(255) DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `generalelements`
--

INSERT INTO `generalelements` (`elementID`, `indicator`, `elemType`, `content`, `imagePath`, `description`, `createdAt`, `updatedAt`) VALUES
(1, 'Navbar', 'image', NULL, '/WMSU-HOMEPAGE/imgs/WMSU-Logo.png', 'navbar-logo', '2025-03-18 10:14:30', '2025-03-18 10:14:48'),
(2, 'Navbar', 'text', 'WESTERN MINDANAO STATE UNIVERSITY', NULL, 'navbar-header', '2025-03-18 10:14:30', '2025-03-18 10:37:36'),
(3, 'Navbar', 'text', 'A Smart Research University by 2040', NULL, 'navbar-tagline', '2025-03-18 10:14:30', '2025-03-18 10:37:37'),
(4, 'Navbar', 'text', 'HOME', NULL, 'navbar-list-item', '2025-03-18 10:14:30', '2025-03-19 11:22:33'),
(5, 'Navbar', 'text', 'ABOUT US', NULL, 'navbar-list-item', '2025-03-18 10:14:30', '2025-03-19 11:22:36'),
(6, 'Navbar', 'text', 'ACADEMIC', NULL, 'navbar-list-item', '2025-03-18 10:14:30', '2025-03-19 11:22:37'),
(7, 'Navbar', 'text', 'ADMINISTRATION', NULL, 'navbar-list-item', '2025-03-18 10:14:30', '2025-03-19 11:22:38'),
(8, 'Navbar', 'text', 'RESEARCH', NULL, 'navbar-list-item', '2025-03-18 10:14:30', '2025-03-19 11:22:38'),
(9, 'Navbar', 'text', 'OTHER LINKS', NULL, 'navbar-list-item', '2025-03-18 10:14:30', '2025-03-19 11:22:39'),
(10, 'Navbar', 'text', 'College of Law', NULL, 'academics-items', '2025-03-18 10:37:29', '2025-03-18 10:37:29'),
(11, 'Navbar', 'text', 'College of Agriculture', NULL, 'academics-items', '2025-03-18 10:37:29', '2025-03-18 10:37:29'),
(12, 'Navbar', 'text', 'College of Architecture', NULL, 'academics-items', '2025-03-18 10:37:29', '2025-03-18 10:37:29'),
(13, 'Navbar', 'text', 'College of Liberal Arts', NULL, 'academics-items', '2025-03-18 10:37:29', '2025-03-18 10:37:29'),
(14, 'Navbar', 'text', 'College of Nursing', NULL, 'academics-items', '2025-03-18 10:37:29', '2025-03-18 10:37:29'),
(15, 'Navbar', 'text', 'College of Asian and Islamic Studies', NULL, 'academics-items', '2025-03-18 10:37:29', '2025-03-18 10:37:29'),
(16, 'Navbar', 'text', 'College of Science and Mathematics', NULL, 'academics-items', '2025-03-18 10:37:29', '2025-03-18 10:37:29'),
(17, 'Navbar', 'text', 'College of Computing Studies', NULL, 'academics-items', '2025-03-18 10:37:29', '2025-03-18 10:37:29'),
(18, 'Navbar', 'text', 'College of Forestry and Environmental Studies', NULL, 'academics-items', '2025-03-18 10:37:29', '2025-03-18 10:37:29'),
(19, 'Navbar', 'text', 'College of Criminal Justice Education', NULL, 'academics-items', '2025-03-18 10:37:29', '2025-03-18 10:37:29'),
(20, 'Navbar', 'text', 'College of Home Economics', NULL, 'academics-items', '2025-03-18 10:37:29', '2025-03-18 10:37:29'),
(21, 'Navbar', 'text', 'College of Engineering', NULL, 'academics-items', '2025-03-18 10:37:29', '2025-03-18 10:37:29'),
(22, 'Navbar', 'text', 'College of Medicine', NULL, 'academics-items', '2025-03-18 10:37:29', '2025-03-18 10:37:29'),
(23, 'Navbar', 'text', 'College of Public Administration and Development Studies', NULL, 'academics-items', '2025-03-18 10:37:29', '2025-03-18 10:37:29'),
(24, 'Navbar', 'text', 'College of Sports Science and Physical Education', NULL, 'academics-items', '2025-03-18 10:37:29', '2025-03-18 10:37:29'),
(25, 'Navbar', 'text', 'College of Social Work and Community Development', NULL, 'academics-items', '2025-03-18 10:37:29', '2025-03-18 10:37:29'),
(26, 'Navbar', 'text', 'College of Teaching Education', NULL, 'academics-items', '2025-03-18 10:37:29', '2025-03-18 10:37:29'),
(27, 'Subnav', 'text', 'College Department', NULL, 'Subnav-button-1', '2025-03-12 21:40:52', '2025-03-18 10:52:17'),
(28, 'Subnav', 'text', 'Basic Education Department', NULL, 'Subnav-button-2', '2025-03-12 21:40:52', '2025-03-18 10:52:19'),
(29, 'Subnav', 'text', 'External Studies Unit', NULL, 'Subnav-button-3', '2025-03-12 21:40:52', '2025-03-18 10:52:21'),
(30, 'Subnav', 'text', 'Admissions', NULL, 'Subnav-button-4', '2025-03-12 21:40:52', '2025-03-18 10:52:23'),
(31, 'Navbar', 'text', 'WMSU ESU - Alicia', NULL, 'esu-list-items', '2025-03-19 14:38:16', '2025-03-19 14:38:16'),
(32, 'Navbar', 'text', 'WMSU ESU - Aurora', NULL, 'esu-list-items', '2025-03-19 14:38:16', '2025-03-19 14:38:16'),
(33, 'Navbar', 'text', 'WMSU ESU - Diplahan', NULL, 'esu-list-items', '2025-03-19 14:38:16', '2025-03-19 14:38:16'),
(34, 'Navbar', 'text', 'WMSU ESU - Imelda', NULL, 'esu-list-items', '2025-03-19 14:38:16', '2025-03-19 14:38:16'),
(35, 'Navbar', 'text', 'WMSU ESU - Ipil', NULL, 'esu-list-items', '2025-03-19 14:38:16', '2025-03-19 14:38:16'),
(36, 'Navbar', 'text', 'WMSU ESU - Mabuhay', NULL, 'esu-list-items', '2025-03-19 14:38:16', '2025-03-19 14:38:16'),
(37, 'Navbar', 'text', 'WMSU ESU - Molave', NULL, 'esu-list-items', '2025-03-19 14:40:04', '2025-03-19 14:40:04'),
(38, 'Navbar', 'text', 'WMSU ESU - Naga', NULL, 'esu-list-items', '2025-03-19 14:40:04', '2025-03-19 14:40:04'),
(39, 'Navbar', 'text', 'WMSU ESU - Olutanga', NULL, 'esu-list-items', '2025-03-19 14:40:04', '2025-03-19 14:40:04'),
(40, 'Navbar', 'text', 'WMSU ESU - Pagadian City', NULL, 'esu-list-items', '2025-03-19 14:40:04', '2025-03-19 14:40:04'),
(41, 'Navbar', 'text', 'WMSU ESU - Siay', NULL, 'esu-list-items', '2025-03-19 14:40:04', '2025-03-19 14:40:04'),
(42, 'Navbar', 'text', 'WMSU ESU - Tungawan', NULL, 'esu-list-items', '2025-03-19 14:40:04', '2025-03-19 14:40:04'),
(43, 'Subnav', 'text', 'Admission Guide', NULL, 'admissions-list-items', '2025-03-12 21:40:52', '2025-03-19 15:03:33'),
(44, 'Subnav', 'text', 'Enrollment Procedure', NULL, 'admissions-list-items', '2025-03-12 21:40:52', '2025-03-19 15:03:34'),
(45, 'Navbar', 'text', 'College of Liberal Arts', NULL, 'academics-items', '2025-03-18 10:37:29', '2025-03-18 10:37:29'),
(46, 'Navbar', 'text', 'Online Registration', NULL, 'admissions-list-items', '2025-03-12 21:40:52', '2025-03-23 03:09:40');

-- --------------------------------------------------------

--
-- Table structure for table `linking`
--

CREATE TABLE `linking` (
  `linkID` int(11) NOT NULL,
  `linkFor` int(11) NOT NULL,
  `indicator` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `linking`
--

INSERT INTO `linking` (`linkID`, `linkFor`, `indicator`, `link`, `description`) VALUES
(94, 10, 'Navbar', '/WMSU-HOMEPAGE/page/academics/law.php', 'academics-items'),
(95, 11, 'Navbar', '/WMSU-HOMEPAGE/page/academics/agriculture.php', 'academics-items'),
(96, 12, 'Navbar', '/WMSU-HOMEPAGE/page/academics/architecture.php', 'academics-items'),
(97, 13, 'Navbar', '/WMSU-HOMEPAGE/page/academics/liberalArts.php', 'academics-items'),
(98, 14, 'Navbar', '/WMSU-HOMEPAGE/page/academics/nursing.php', 'academics-items'),
(99, 15, 'Navbar', '/WMSU-HOMEPAGE/page/academics/asianAndIslamic.php', 'academics-items'),
(100, 16, 'Navbar', '/WMSU-HOMEPAGE/page/academics/CSM.php', 'academics-items'),
(101, 17, 'Navbar', '/WMSU-HOMEPAGE/page/academics/CCS.php', 'academics-items'),
(102, 18, 'Navbar', '/WMSU-HOMEPAGE/page/academics/forestryAndEnvironmental.php', 'academics-items'),
(103, 19, 'Navbar', '/WMSU-HOMEPAGE/page/academics/crim.php', 'academics-items'),
(104, 20, 'Navbar', '/WMSU-HOMEPAGE/page/academics/homeEcon.php', 'academics-items'),
(105, 21, 'Navbar', '/WMSU-HOMEPAGE/page/academics/engineering.php', 'academics-items'),
(106, 22, 'Navbar', '/WMSU-HOMEPAGE/page/academics/medicine.php', 'academics-items'),
(107, 23, 'Navbar', '/WMSU-HOMEPAGE/page/academics/publicAdmin.php', 'academics-items'),
(108, 24, 'Navbar', '/WMSU-HOMEPAGE/page/academics/sportsScience.php', 'academics-items'),
(109, 25, 'Navbar', '/WMSU-HOMEPAGE/page/academics/socialWork.php', 'academics-items'),
(110, 31, 'Navbar', '#', 'esu-list-items'),
(111, 32, 'Navbar', '#', 'esu-list-items'),
(112, 33, 'Navbar', '#', 'esu-list-items'),
(113, 34, 'Navbar', '#', 'esu-list-items'),
(114, 35, 'Navbar', '#', 'esu-list-items'),
(115, 36, 'Navbar', '#', 'esu-list-items'),
(116, 37, 'Navbar', '#', 'esu-list-items'),
(117, 38, 'Navbar', '#', 'esu-list-items'),
(118, 39, 'Navbar', '#', 'esu-list-items'),
(119, 40, 'Navbar', '#', 'esu-list-items'),
(120, 41, 'Navbar', '#', 'esu-list-items'),
(121, 42, 'Navbar', '#', 'esu-list-items'),
(122, 43, 'Navbar', '/WMSU-HOMEPAGE/page/admissions/admissionGuide.php', 'admissions-list-items'),
(123, 44, 'Navbar', '/WMSU-HOMEPAGE/page/admissions/enrollment.php', 'admissions-list-items'),
(124, 46, 'Navbar', '/WMSU-HOMEPAGE/page/admissions/onlineReg.php', 'admissions-list-items');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `ID` int(11) NOT NULL,
  `pageName` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`ID`, `pageName`, `address`, `createdAt`, `updatedAt`) VALUES
(1, 'Homepage', '', '2025-03-12 08:15:04', '2025-03-12 08:15:04'),
(2, 'About Us Page', '', '2025-03-12 08:15:04', '2025-03-12 08:15:04'),
(3, 'Academics Page', '', '2025-03-12 08:15:04', '2025-03-12 08:15:04'),
(4, 'Research Page', '', '2025-03-12 08:15:25', '2025-03-12 08:15:25'),
(5, 'Linkages', '', '2025-03-12 08:15:25', '2025-03-12 08:15:25');

-- --------------------------------------------------------

--
-- Table structure for table `page_sections`
--

CREATE TABLE `page_sections` (
  `sectionID` int(11) NOT NULL,
  `pageID` int(11) NOT NULL,
  `subpage` int(11) DEFAULT NULL,
  `indicator` varchar(255) NOT NULL,
  `elemType` enum('text','image') DEFAULT NULL,
  `content` text DEFAULT NULL,
  `imagePath` varchar(255) DEFAULT NULL,
  `alt` varchar(255) DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `page_sections`
--

INSERT INTO `page_sections` (`sectionID`, `pageID`, `subpage`, `indicator`, `elemType`, `content`, `imagePath`, `alt`, `description`, `createdAt`, `updatedAt`) VALUES
(5, 3, 1, 'Carousel Element', 'image', NULL, '../../imgs/ccs-logo-proc.png', NULL, 'carousel-logo', '2025-03-13 05:40:52', '2025-03-18 01:15:34'),
(6, 3, 1, 'Carousel Element', 'text', 'College of Computing Studies', NULL, NULL, 'carousel-logo-text', '2025-03-13 05:40:52', '2025-03-23 03:34:39'),
(7, 3, 1, 'Carousel Element', 'image', NULL, '../../imgs/ccs1.jpg', NULL, 'carousel-img', '2025-03-13 05:40:52', '2025-03-18 00:45:42'),
(8, 3, 1, 'Carousel Element', 'image', NULL, '../../imgs/ccs2.jpg', NULL, 'carousel-img', '2025-03-13 05:40:52', '2025-03-18 00:45:44'),
(9, 3, 1, 'Carousel Element', 'image', NULL, '../../imgs/ccs3.jpg', NULL, 'carousel-img', '2025-03-13 05:40:52', '2025-03-18 00:45:47'),
(10, 3, 1, 'Card Element Front', 'image', NULL, '../../imgs/ccs1.jpg', NULL, 'card-front-img', '2025-03-13 05:40:52', '2025-03-18 01:17:28'),
(11, 3, 1, 'Card Element Front', 'image', NULL, '../../imgs/ccs2.jpg', NULL, 'card-front-img', '2025-03-13 05:40:52', '2025-03-18 01:17:30'),
(12, 3, 1, 'Card Element Front', 'image', NULL, '../../imgs/ccs3.jpg', NULL, 'card-front-img', '2025-03-13 05:40:52', '2025-03-18 01:17:36'),
(13, 3, 1, 'Card Element Front', 'text', 'College Goals', NULL, NULL, 'card-front-title', '2025-03-13 05:40:52', '2025-03-18 01:17:46'),
(14, 3, 1, 'Card Element Front', 'text', 'College Mission', NULL, NULL, 'card-front-title', '2025-03-13 05:40:52', '2025-03-18 01:17:48'),
(15, 3, 1, 'Card Element Front', 'text', 'College Vision', NULL, NULL, 'card-front-title', '2025-03-13 05:40:52', '2025-03-18 01:17:49'),
(16, 3, 1, 'Card Element Back', 'text', 'The college shall provide academic excellence in the field of Information and Communication Technology, with emphasis on the following goals:', NULL, NULL, 'card-back-head', '2025-03-13 05:40:52', '2025-03-18 01:17:58'),
(17, 3, 1, 'Card Element Back', 'text', 'NaN', NULL, NULL, 'card-back-head', '2025-03-13 05:40:52', '2025-03-18 01:17:59'),
(18, 3, 1, 'Card Element Back', 'text', 'NaN', NULL, NULL, 'card-back-head', '2025-03-13 05:40:52', '2025-03-18 01:18:00'),
(19, 3, 1, 'Card Element Back', 'text', 'produce quality, excellent and environmentally proactive graduates imbued with gender responsiveness..&nbsp;', NULL, NULL, 'CG-list-item', '2025-03-13 05:40:52', '2025-03-16 06:24:56'),
(20, 3, 1, 'Card Element Back', 'text', 'Achieve the highest level of accreditation and center of excellence imbued with outcomes-based education.&nbsp;', NULL, NULL, 'CG-list-item', '2025-03-13 05:40:52', '2025-03-16 06:24:58'),
(21, 3, 1, 'Card Element Back', 'text', 'Partner with national and international industries as an outlet for research development and extension.&nbsp;', NULL, NULL, 'CG-list-item', '2025-03-13 05:40:52', '2025-03-16 06:25:00'),
(22, 3, 1, 'Card Element Back', 'text', 'Support faculty members through faculty development programs to be competitive with the highest global standards.', NULL, NULL, 'CG-list-item', '2025-03-13 05:40:52', '2025-03-16 06:25:02'),
(23, 3, 1, 'Card Element Back', 'text', 'NaN', NULL, NULL, 'CM-list-item', '2025-03-13 05:40:52', '2025-03-16 06:25:04'),
(24, 3, 1, 'Card Element Back', 'text', 'NaN', NULL, NULL, 'CV-list-item', '2025-03-13 05:40:52', '2025-03-18 01:18:17'),
(25, 3, 1, 'Accordion Courses Undergrad', 'text', 'Undergraduate Programs', NULL, NULL, 'program-header', '2025-03-13 05:40:52', '2025-03-18 05:14:26'),
(26, 3, 1, 'Accordion Courses Grad', 'text', 'Graduate Programs', NULL, NULL, 'program-header', '2025-03-13 05:40:52', '2025-03-18 05:14:30'),
(27, 3, 1, 'Accordion Courses Undergrad', 'text', 'Bachelor of Science in Computer Science (BSCS)', NULL, NULL, 'course-header', '2025-03-17 23:49:37', '2025-03-18 08:07:04'),
(32, 3, 1, 'Accordion Courses Undergrad', 'text', 'Utilize effectively the concepts of computer science theories and methodologies and adapt new technologies and ideas.', NULL, NULL, 'undergrad-course-list-items-1', '2025-03-17 23:49:37', '2025-03-18 05:24:32'),
(33, 3, 1, 'Accordion Courses Undergrad', 'text', 'Work cohesively with a team to successfully complete projects.', NULL, NULL, 'undergrad-course-list-items-1', '2025-03-17 23:49:37', '2025-03-18 05:24:34'),
(34, 3, 1, 'Accordion Courses Undergrad', 'text', 'Pursue personal development and lifelong learning through research and graduate studies.', NULL, NULL, 'undergrad-course-list-items-1', '2025-03-17 23:49:37', '2025-03-18 05:24:34'),
(35, 3, 1, 'Accordion Courses Undergrad', 'text', 'Communicate effectively with the computing community and society through oral and written correspondence.', NULL, NULL, 'undergrad-course-list-items-1', '2025-03-17 23:49:37', '2025-03-18 05:24:36'),
(36, 3, 1, 'Accordion Courses', 'text', 'Program Objectives/Outcomes:', NULL, NULL, 'course outcomes', '2025-03-13 05:40:52', '2025-03-18 08:07:01'),
(37, 3, 1, 'Accordion Courses Undergrad', 'text', 'Bachelor of Science in Information Technology (BSIT)', NULL, NULL, 'course-header', '2025-03-17 23:49:37', '2025-03-18 05:14:37'),
(38, 3, 1, 'Accordion Courses Undergrad', 'text', 'Utilizes computing theories, methodologies, and mathematical concepts.', NULL, NULL, 'undergrad-course-list-items-2', '2025-03-17 23:53:54', '2025-03-18 05:24:39'),
(39, 3, 1, 'Accordion Courses Undergrad', 'text', 'Adapts new technologies and ideas in IT-based solutions.', NULL, NULL, 'undergrad-course-list-items-2', '2025-03-17 23:53:54', '2025-03-18 05:24:40'),
(40, 3, 1, 'Accordion Courses Undergrad', 'text', 'Solves complex computing problems through proper research.', NULL, NULL, 'undergrad-course-list-items-2', '2025-03-17 23:53:54', '2025-03-18 05:24:41'),
(41, 3, 1, 'Accordion Courses Undergrad', 'text', 'Works effectively as a team member or leader in IT projects.', NULL, NULL, 'undergrad-course-list-items-2', '2025-03-17 23:53:54', '2025-03-18 05:24:42'),
(42, 3, 1, 'Accordion Courses Undergrad', 'text', 'Uses technical resources efficiently to solve computing problems.', NULL, NULL, 'undergrad-course-list-items-2', '2025-03-17 23:53:54', '2025-03-18 05:24:43'),
(43, 3, 1, 'Accordion Courses Undergrad', 'text', 'Analyzes ethical and social issues in IT and their impact.', NULL, NULL, 'undergrad-course-list-items-2', '2025-03-17 23:53:54', '2025-03-18 05:24:44'),
(44, 3, 1, 'Accordion Courses Undergrad', 'text', 'Produces IT-related research relevant to local and global concerns.', NULL, NULL, 'undergrad-course-list-items-2', '2025-03-17 23:53:54', '2025-03-18 05:24:45'),
(45, 3, 1, 'Accordion Courses Undergrad', 'text', 'Integrates IT-based projects for societal improvement and development.', NULL, NULL, 'undergrad-course-list-items-2', '2025-03-17 23:53:54', '2025-03-18 05:24:47'),
(46, 3, 1, 'Accordion Courses Undergrad', 'text', 'Engages in professional growth through self-learning and postgraduate studies.', NULL, NULL, 'undergrad-course-list-items-2', '2025-03-17 23:53:54', '2025-03-18 05:24:48'),
(47, 3, 1, 'Accordion Courses Grad', 'text', 'Master in Information Technology (MIT)', NULL, NULL, 'course-header', '2025-03-17 23:53:54', '2025-03-18 05:15:01'),
(48, 3, 1, 'Accordion Courses Grad', 'text', 'Demonstrate advanced IT knowledge and skills in a specialized, interdisciplinary, or multidisciplinary field of learning for professional practice.', NULL, NULL, 'grad-course-list-items-3', '2025-03-17 23:59:26', '2025-03-18 05:25:06'),
(49, 3, 1, 'Accordion Courses Grad', 'text', 'Utilize effectively advanced knowledge and skills in Information Technology through research and software application development that address IT-related problems of the organization and recognize gender responsiveness.', NULL, NULL, 'grad-course-list-items-3', '2025-03-17 23:59:26', '2025-03-18 05:25:07'),
(50, 3, 1, 'Accordion Courses Grad', 'text', 'Apply a significant level of expertise-based autonomy and accountability to professional leadership for innovation and research in a specialized, interdisciplinary, or multidisciplinary field.', NULL, NULL, 'grad-course-list-items-3', '2025-03-17 23:59:26', '2025-03-18 05:25:09'),
(51, 3, 1, 'Accordion Courses Grad', 'text', 'Pursue lifelong learning through research with a highly substantial degree of independence in individual work or teams of interdisciplinary or multidisciplinary settings.', NULL, NULL, 'grad-course-list-items-3', '2025-03-17 23:59:26', '2025-03-18 05:25:09'),
(52, 3, 2, 'Carousel Element', 'text', 'College of Nursing', NULL, NULL, 'carousel-logo-text', '2025-03-20 12:06:40', '2025-03-23 03:34:20'),
(53, 3, 2, 'Carousel Element', 'image', NULL, '../../imgs/cn-proc.png', NULL, 'carousel-img', '2025-03-20 12:06:40', '2025-03-23 03:34:19'),
(54, 3, 2, 'Carousel Element', 'image', NULL, '../../imgs/cn2.jpg', NULL, 'carousel-img', '2025-03-20 12:06:40', '2025-03-20 12:17:57'),
(55, 3, 2, 'Carousel Element', 'image', NULL, '../../imgs/cn3.jpg', NULL, 'carousel-img', '2025-03-20 12:06:40', '2025-03-20 12:18:01'),
(56, 3, 2, 'Carousel Element', 'image', NULL, '../../imgs/cn-proc.png', NULL, 'carousel-logo', '2025-03-20 12:06:40', '2025-03-20 12:06:40'),
(57, 3, 2, 'Card Element Front', 'text', 'College Goals', NULL, NULL, 'card-front-title', '2025-03-20 12:08:31', '2025-03-20 12:08:31'),
(58, 3, 2, 'Card Element Front', 'text', 'College Mission', NULL, NULL, 'card-front-title', '2025-03-20 12:08:31', '2025-03-20 12:08:31'),
(59, 3, 2, 'Card Element Front', 'text', 'College Vision', NULL, NULL, 'card-front-title', '2025-03-20 12:08:31', '2025-03-20 12:08:31'),
(60, 3, 2, 'Card Element Front', 'image', NULL, '../../imgs/cn-card-1.jpg', NULL, 'card-front-img', '2025-03-20 12:08:40', '2025-03-20 12:18:21'),
(61, 3, 2, 'Card Element Front', 'image', NULL, '../../imgs/cn-card-2.jpg', NULL, 'card-front-img', '2025-03-20 12:08:40', '2025-03-20 12:18:26'),
(62, 3, 2, 'Card Element Front', 'image', NULL, '../../imgs/cn-card-3.jpg', NULL, 'card-front-img', '2025-03-20 12:08:40', '2025-03-20 12:18:31'),
(63, 3, 2, 'Card Element Back', 'text', 'The College envisions itself to be the center of distinctive nursing education fostering the development of graduates who are values-oriented, socially responsive, and globally competitive.', NULL, NULL, 'card-back-head', '2025-03-20 12:08:57', '2025-03-21 23:35:30'),
(64, 3, 2, 'Card Element Back', 'text', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.', NULL, NULL, 'CG-list-item', '2025-03-20 12:08:57', '2025-03-21 23:35:36'),
(65, 3, 2, 'Card Element Back', 'text', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.', NULL, NULL, 'CG-list-item', '2025-03-20 12:08:57', '2025-03-21 23:35:37'),
(66, 3, 2, 'Accordion Courses Grad', 'text', 'Graduate Programs', NULL, NULL, 'program-header', '2025-03-20 12:09:51', '2025-03-20 12:09:51'),
(67, 3, 2, 'Accordion Courses Grad', 'text', 'Master in Nursing (MN)', NULL, NULL, 'course-header', '2025-03-20 12:09:51', '2025-03-20 12:09:51'),
(68, 3, 2, 'Accordion Courses Grad', 'text', 'Master of Arts in Nursing (MaN)', NULL, NULL, 'course-header', '2025-03-20 12:09:51', '2025-03-20 12:09:51'),
(70, 3, 2, 'Accordion Courses Undergrad', 'text', 'Undergraduate Programs', NULL, NULL, 'program-header', '2025-03-20 12:11:00', '2025-03-20 12:11:00'),
(71, 3, 2, 'Accordion Courses Undergrad', 'text', 'Bachelor of Science in Nursing (BSN)', NULL, NULL, 'course-header', '2025-03-20 12:11:00', '2025-03-20 12:11:00'),
(72, 3, 3, 'Carousel Element', 'text', 'College of Science and Mathematics', NULL, NULL, 'carousel-logo-text', '2025-03-21 10:02:28', '2025-03-21 10:02:28'),
(73, 3, 3, 'Carousel Element', 'image', NULL, '../../imgs/csm-proc.png', NULL, 'carousel-logo', '2025-03-21 10:02:28', '2025-03-21 10:06:05'),
(74, 3, 3, 'Carousel Element', 'image', NULL, '../../imgs/csm1.jpg', NULL, 'carousel-img', '2025-03-21 10:02:28', '2025-03-21 10:02:28'),
(75, 3, 3, 'Carousel Element', 'image', NULL, '../../imgs/csm2.jpg', NULL, 'carousel-img', '2025-03-21 10:02:28', '2025-03-21 10:02:28'),
(76, 3, 3, 'Carousel Element', 'image', NULL, '../../imgs/csm3.jpg', NULL, 'carousel-img', '2025-03-21 10:02:28', '2025-03-21 10:02:28'),
(77, 3, 3, 'Card Element Front', 'text', 'College Goals', NULL, NULL, 'card-front-title', '2025-03-21 10:02:28', '2025-03-21 10:02:28'),
(78, 3, 3, 'Card Element Front', 'text', 'College Vision', NULL, NULL, 'card-front-title', '2025-03-21 10:02:28', '2025-03-21 10:02:28'),
(79, 3, 3, 'Card Element Front', 'image', NULL, '../../imgs/csm-card1.jpg', NULL, 'card-front-img', '2025-03-21 10:02:28', '2025-03-21 10:02:28'),
(80, 3, 3, 'Card Element Front', 'image', NULL, '../../imgs/csm-card2.jpg', NULL, 'card-front-img', '2025-03-21 10:02:28', '2025-03-21 10:02:28'),
(81, 3, 3, 'Card Element Front', 'image', NULL, '../../imgs/csm-card3.jpg', NULL, 'card-front-img', '2025-03-21 10:02:28', '2025-03-21 10:02:28'),
(82, 3, 3, 'Card Element Back', 'text', 'To provide opportunities for students to become science-oriented professionals equipped with advanced scientific knowledge, skills, and desirable moral and scientific values enabling them to take leadership roles in meeting the needs of industries and work market demands.', NULL, NULL, 'card-back-head', '2025-03-21 10:02:28', '2025-03-21 10:02:28'),
(83, 3, 3, 'Card Element Back', 'text', 'Conduct relevant and innovative research in pure and applied sciences that shall contribute to new knowledge and obtain products for science and technology advancement with regional and national significance and with international recognition.', NULL, NULL, 'CG-list-item', '2025-03-21 10:02:28', '2025-03-21 10:02:28'),
(84, 3, 3, 'Card Element Back', 'text', 'Provide avenues for the utilization of research findings, products, and technology to impact the community towards the enhancement of the environment and quality of life.', NULL, NULL, 'CG-list-item', '2025-03-21 10:02:28', '2025-03-21 10:02:28'),
(85, 3, 3, 'Accordion Courses Undergrad', 'text', 'Undergraduate Programs', NULL, NULL, 'program-header', '2025-03-21 10:02:28', '2025-03-21 10:02:28'),
(86, 3, 3, 'Accordion Courses Undergrad', 'text', 'Bachelor of Science in Biology (BSBIO)', NULL, NULL, 'course-header', '2025-03-21 10:02:28', '2025-03-21 10:02:28'),
(87, 3, 3, 'Accordion Courses Undergrad', 'text', 'Bachelor of Science in Chemistry (BSCHEM)', NULL, NULL, 'course-header', '2025-03-21 10:02:28', '2025-03-21 10:02:28'),
(88, 3, 3, 'Accordion Courses Undergrad', 'text', 'Bachelor of Science in Mathematics (BSMATH)', NULL, NULL, 'course-header', '2025-03-21 10:02:28', '2025-03-21 10:02:28'),
(89, 3, 3, 'Accordion Courses Undergrad', 'text', 'Bachelor of Science in Physics (BSPHYS)', NULL, NULL, 'course-header', '2025-03-21 10:02:28', '2025-03-21 10:02:28'),
(90, 3, 3, 'Accordion Courses Undergrad', 'text', 'Bachelor of Science in Statistics (BSSTAT)', NULL, NULL, 'course-header', '2025-03-21 10:02:28', '2025-03-21 10:02:28'),
(91, 3, 3, 'Card Element Back', 'text', 'NaN', NULL, NULL, 'card-back-head', '2025-03-21 10:02:28', '2025-03-21 10:02:28'),
(92, 3, 3, 'Card Element Back', 'text', 'NaN', NULL, NULL, 'card-back-head', '2025-03-21 10:02:28', '2025-03-21 10:02:28'),
(93, 3, 3, 'Card Element Front', 'text', 'College Mission', NULL, NULL, 'card-front-title', '2025-03-21 10:02:28', '2025-03-21 10:02:28'),
(94, 3, 4, 'Carousel Element', 'text', 'College of Agriculture', NULL, NULL, 'carousel-logo-text', '2025-03-21 10:16:03', '2025-03-21 10:16:03'),
(95, 3, 4, 'Carousel Element', 'image', NULL, '../../imgs/agriculture-logo-proc.png', NULL, 'carousel-logo', '2025-03-21 10:16:03', '2025-03-21 10:28:00'),
(96, 3, 4, 'Carousel Element', 'image', NULL, '../../imgs/agriculture1.jpg', NULL, 'carousel-img', '2025-03-21 10:16:03', '2025-03-21 10:16:03'),
(97, 3, 4, 'Carousel Element', 'image', NULL, '../../imgs/agriculture2.jpg', NULL, 'carousel-img', '2025-03-21 10:16:03', '2025-03-21 10:16:03'),
(98, 3, 4, 'Carousel Element', 'image', NULL, '../../imgs/agriculture3.jpg', NULL, 'carousel-img', '2025-03-21 10:16:03', '2025-03-21 10:16:03'),
(99, 3, 4, 'Card Element Front', 'text', 'College Goals', NULL, NULL, 'card-front-title', '2025-03-21 10:16:03', '2025-03-21 10:16:03'),
(100, 3, 4, 'Card Element Front', 'text', 'As a National University/College of Agriculture in Region IX under National Agriculture and Fisheries Education System (NAFES) of Republic Act (RA) No. 8435 of AFMA, the WMSU-CA has the following goals:', NULL, NULL, 'card-front-content', '2025-03-21 10:16:03', '2025-03-21 10:16:03'),
(101, 3, 4, 'Card Element Back', 'text', 'Excellent and relevant agricultural instruction in both undergraduate and graduate degree programs;', NULL, NULL, 'card-back-list', '2025-03-21 10:16:03', '2025-03-21 10:16:03'),
(102, 3, 4, 'Card Element Back', 'text', 'Relevant and quality researches geared towards agricultural productivity and conservation of the natural resources;', NULL, NULL, 'card-back-list', '2025-03-21 10:16:03', '2025-03-21 10:16:03'),
(103, 3, 4, 'Card Element Back', 'text', 'Appropriate and comprehensive community-based extension services to improve the quality of life;', NULL, NULL, 'card-back-list', '2025-03-21 10:16:03', '2025-03-21 10:16:03'),
(104, 3, 4, 'Card Element Back', 'text', 'Efficient and responsive human resources for entrepreneurial undertaking;', NULL, NULL, 'card-back-list', '2025-03-21 10:16:03', '2025-03-21 10:16:03'),
(105, 3, 4, 'Card Element Back', 'text', 'Sustainable product development and innovation.', NULL, NULL, 'card-back-list', '2025-03-21 10:16:03', '2025-03-21 10:16:03'),
(106, 3, 4, 'Accordion Courses Undergrad', 'text', 'Undergraduate Programs', NULL, NULL, 'program-header', '2025-03-21 10:16:03', '2025-03-21 10:16:03'),
(107, 3, 4, 'Accordion Courses Undergrad', 'text', 'Bachelor of Science in Agriculture', NULL, NULL, 'course-header', '2025-03-21 10:16:03', '2025-03-21 10:16:03'),
(108, 3, 4, 'Accordion Courses Undergrad', 'text', 'To produce professionals with emerging knowledge and skills in Science and Technology in animal and crop production relevant to community and industry needs;', NULL, NULL, 'undergrad-course-list-items-1', '2025-03-21 10:16:03', '2025-03-21 10:23:04'),
(109, 3, 4, 'Accordion Courses Undergrad', 'text', 'To develop faculty and students’ potential on research activity to become managers and scientists for increased agricultural productivity;', NULL, NULL, 'undergrad-course-list-items-1', '2025-03-21 10:16:03', '2025-03-21 10:23:06'),
(110, 3, 4, 'Accordion Courses Undergrad', 'text', 'To equip faculty, students, and staff with community organizing and technology transfer skills for community developments;', NULL, NULL, 'undergrad-course-list-items-1', '2025-03-21 10:16:03', '2025-03-21 10:23:07'),
(111, 3, 4, 'Accordion Courses Undergrad', 'text', 'To develop and enhance the interest of the students to become skilled producers of sustainable agricultural products;', NULL, NULL, 'undergrad-course-list-items-1', '2025-03-21 10:16:03', '2025-03-21 10:23:10'),
(112, 3, 4, 'Accordion Courses Undergrad', 'text', 'To serve and cater students who have the learning and love to care for animals and plants, its production, and improvements.', NULL, NULL, 'undergrad-course-list-items-1', '2025-03-21 10:16:03', '2025-03-21 10:23:11'),
(113, 3, 4, 'Accordion Courses Undergrad', 'text', 'Bachelor of Science in Food Technology', NULL, NULL, 'course-header', '2025-03-21 10:16:03', '2025-03-21 10:16:03'),
(114, 3, 4, 'Accordion Courses Undergrad', 'text', 'Understand and apply basic elements of sanitation and quality assurance programs to assure food safety.', NULL, NULL, 'undergrad-course-list-items-2', '2025-03-21 10:16:03', '2025-03-21 10:23:15'),
(115, 3, 4, 'Accordion Courses Undergrad', 'text', 'Evaluate the microbiological, physical, chemical, sensory, and functional properties of food.', NULL, NULL, 'undergrad-course-list-items-2', '2025-03-21 10:16:03', '2025-03-21 10:23:18'),
(116, 3, 4, 'Accordion Courses Undergrad', 'text', 'Create new product ideas, concepts, and procedures leading to innovative food technologies.', NULL, NULL, 'undergrad-course-list-items-2', '2025-03-21 10:16:03', '2025-03-21 10:23:21'),
(117, 3, 4, 'Accordion Courses Undergrad', 'text', 'Bachelor of Science in Agribusiness', NULL, NULL, 'course-header', '2025-03-21 10:16:03', '2025-03-21 10:16:03'),
(118, 3, 4, 'Accordion Courses Undergrad', 'text', 'To produce competitive agribusiness entrepreneurs with knowledge and skills in business management.', NULL, NULL, 'undergrad-course-list-items-3', '2025-03-21 10:16:03', '2025-03-21 10:23:25'),
(119, 3, 4, 'Accordion Courses Undergrad', 'text', 'To equip students with appropriate business research tools applicable to current business situations.', NULL, NULL, 'undergrad-course-list-items-3', '2025-03-21 10:16:03', '2025-03-21 10:23:26'),
(120, 3, 4, 'Accordion Courses Undergrad', 'text', 'To develop students with business acumen that can assist the various stakeholders in realizing their business potentials.', NULL, NULL, 'undergrad-course-list-items-3', '2025-03-21 10:16:03', '2025-03-21 10:23:27'),
(121, 3, 4, 'Accordion Courses Undergrad', 'text', 'To train students in actual business ventures as well as in the application of management principles and modern technologies in agriculture.', NULL, NULL, 'undergrad-course-list-items-3', '2025-03-21 10:16:03', '2025-03-21 10:23:28'),
(122, 3, 4, 'Accordion Courses Undergrad', 'text', 'Bachelor of Agricultural Technology', NULL, NULL, 'course-header', '2025-03-21 10:16:03', '2025-03-21 10:16:03'),
(123, 3, 4, 'Accordion Courses Undergrad', 'text', 'To equip students to become technicians and professionals with abilities needed to make practical application of theoretical knowledge.', NULL, NULL, 'undergrad-course-list-items-4', '2025-03-21 10:16:03', '2025-03-21 10:23:32'),
(124, 3, 4, 'Accordion Courses Undergrad', 'text', 'To develop and produce graduates who will become successful agribusiness operators, entrepreneurs, and managers.', NULL, NULL, 'undergrad-course-list-items-4', '2025-03-21 10:16:03', '2025-03-21 10:23:33'),
(125, 3, 4, 'Accordion Courses Undergrad', 'text', 'To equip students with appropriate knowledge and skills for community development work.', NULL, NULL, 'undergrad-course-list-items-4', '2025-03-21 10:16:03', '2025-03-21 10:23:34'),
(126, 3, 4, 'Accordion Courses Undergrad', 'text', 'To develop, establish, and sustain agricultural technology-based enterprises.', NULL, NULL, 'undergrad-course-list-items-4', '2025-03-21 10:16:03', '2025-03-21 10:23:34'),
(127, 3, 4, 'Accordion Courses Undergrad', 'text', 'To utilize appropriate agricultural technologies for dissemination and commercialization.', NULL, NULL, 'undergrad-course-list-items-4', '2025-03-21 10:16:03', '2025-03-21 10:23:36'),
(128, 3, 4, 'Accordion Courses Grad', 'text', 'Graduate Programs', NULL, NULL, 'program-header', '2025-03-21 10:16:03', '2025-03-21 10:16:03'),
(129, 3, 4, 'Accordion Courses Grad', 'text', 'Master of Science in Agronomy', NULL, NULL, 'course-header', '2025-03-21 10:16:03', '2025-03-21 10:16:03'),
(130, 3, 4, 'Accordion Courses Grad', 'text', 'To equip faculty, students and staff with advance skills and approaches in community organizing and technology transfer of various R&D outputs for community development.', NULL, NULL, 'grad-course-list-items-1', '2025-03-21 10:16:03', '2025-03-21 10:23:44'),
(131, 3, 4, 'Accordion Courses Grad', 'text', 'To develop students with advance knowledge and skills as producers of sustainable agricultural systems and products, and environmental conservation.', NULL, NULL, 'grad-course-list-items-1', '2025-03-21 10:16:03', '2025-03-21 10:23:46'),
(132, 3, 4, 'Accordion Courses Grad', 'text', 'Master in Food Processing and Management', NULL, NULL, 'course-header', '2025-03-21 10:16:03', '2025-03-21 10:16:03'),
(133, 3, 4, 'Accordion Courses Grad', 'text', 'Relate statistical principles to QMS.', NULL, NULL, 'grad-course-list-items-2', '2025-03-21 10:16:03', '2025-03-21 10:23:49'),
(134, 3, 4, 'Accordion Courses Grad', 'text', 'Plan and manage food analysis laboratory.', NULL, NULL, 'grad-course-list-items-2', '2025-03-21 10:16:03', '2025-03-21 10:23:50'),
(135, 3, 4, 'Accordion Courses Grad', 'text', 'Utilize laboratory techniques for identification of microorganisms.', NULL, NULL, 'grad-course-list-items-2', '2025-03-21 10:16:03', '2025-03-21 10:23:51'),
(136, 3, 4, 'Accordion Courses Grad', 'text', 'Utilize laboratory techniques common to basic and applied food chemistry.', NULL, NULL, 'grad-course-list-items-2', '2025-03-21 10:16:03', '2025-03-21 10:23:52'),
(137, 3, 4, 'Accordion Courses Grad', 'text', 'Be a collaborator, practice the values of integrity, commitment, and respect.', NULL, NULL, 'grad-course-list-items-2', '2025-03-21 10:16:03', '2025-03-21 10:23:53'),
(138, 3, 4, 'Accordion Courses Grad', 'text', 'Be initiative-taking, striving for continuous professional and self-improvement.', NULL, NULL, 'grad-course-list-items-2', '2025-03-21 10:16:03', '2025-03-21 10:23:54'),
(139, 3, 4, 'Card Element Front', 'image', NULL, '../../imgs/agriculture3.jpg', NULL, 'card-front-img', '2025-03-21 10:16:03', '2025-03-21 10:16:03'),
(140, 3, 4, 'Card Element Front', 'text', 'College Vision', NULL, NULL, 'card-front-title', '2025-03-21 10:16:03', '2025-03-21 10:16:03'),
(141, 3, 4, 'Card Element Front', 'text', 'College Mission', NULL, NULL, 'card-front-title', '2025-03-21 10:16:03', '2025-03-21 10:16:03'),
(142, 3, 4, 'Card Element Front', 'image', NULL, '../../imgs/agriculture2.jpg', NULL, 'card-front-img', '2025-03-21 10:16:03', '2025-03-21 10:16:03'),
(143, 3, 4, 'Card Element Front', 'image', NULL, '../../imgs/agriculture1.jpg', NULL, 'card-front-img', '2025-03-21 10:16:03', '2025-03-21 10:16:03'),
(144, 3, 16, 'Carousel Element', 'text', 'College of Liberal Arts', NULL, NULL, 'carousel-logo-text', '2025-03-21 23:07:42', '2025-03-21 23:07:42'),
(145, 3, 16, 'Carousel Element', 'image', NULL, '../../imgs/cla-proc.png', NULL, 'carousel-logo', '2025-03-21 23:07:42', '2025-03-21 23:15:15'),
(146, 3, 16, 'Carousel Element', 'image', NULL, '../../imgs/cla1.jpg', NULL, 'carousel-img', '2025-03-21 23:07:42', '2025-03-21 23:07:42'),
(147, 3, 16, 'Carousel Element', 'image', NULL, '../../imgs/cla2.jpg', NULL, 'carousel-img', '2025-03-21 23:07:42', '2025-03-21 23:07:42'),
(148, 3, 16, 'Carousel Element', 'image', NULL, '../../imgs/cla3.jpg', NULL, 'carousel-img', '2025-03-21 23:07:42', '2025-03-21 23:07:42'),
(149, 3, 16, 'Card Element Front', 'text', 'College Vision', NULL, NULL, 'card-front-title', '2025-03-21 23:07:42', '2025-03-21 23:07:42'),
(150, 3, 16, 'Card Element Front', 'text', 'College Mission', NULL, NULL, 'card-front-title', '2025-03-21 23:07:42', '2025-03-21 23:07:42'),
(151, 3, 16, 'Card Element Front', 'text', 'College Goals', NULL, NULL, 'card-front-title', '2025-03-21 23:07:42', '2025-03-21 23:07:42'),
(152, 3, 16, 'Card Element Back', 'text', 'NaN', NULL, NULL, 'card-back-head', '2025-03-21 23:07:42', '2025-03-21 23:07:42'),
(153, 3, 16, 'Card Element Back', 'text', 'NaN', NULL, NULL, 'card-back-head', '2025-03-21 23:07:42', '2025-03-21 23:07:42'),
(154, 3, 16, 'Card Element Back', 'text', 'NaN', NULL, NULL, 'card-back-head', '2025-03-21 23:07:42', '2025-03-21 23:07:42'),
(155, 3, 16, 'Accordion Courses Undergrad', 'text', 'Bachelor of Arts in English', NULL, NULL, 'course-header', '2025-03-21 23:07:42', '2025-03-21 23:20:36'),
(156, 3, 16, 'Accordion Courses Undergrad', 'text', 'Bachelor of Arts in Political Science', NULL, NULL, 'course-header', '2025-03-21 23:07:42', '2025-03-21 23:20:37'),
(157, 3, 16, 'Accordion Courses Undergrad', 'text', 'Bachelor of Arts in History', NULL, NULL, 'course-header', '2025-03-21 23:07:42', '2025-03-21 23:20:40'),
(158, 3, 16, 'Accordion Courses Undergrad', 'text', 'Bachelor of Arts in Psychology', NULL, NULL, 'course-header', '2025-03-21 23:07:42', '2025-03-21 23:20:39'),
(160, 3, 16, 'Card Element Front', 'image', NULL, '../../imgs/cla1.jpg', NULL, 'card-front-img', '2025-03-21 23:07:42', '2025-03-21 23:19:12'),
(161, 3, 16, 'Card Element Front', 'image', NULL, '../../imgs/cla2.jpg', NULL, 'card-front-img', '2025-03-21 23:07:42', '2025-03-21 23:19:12'),
(162, 3, 16, 'Card Element Front', 'image', NULL, '../../imgs/cla3.jpg', NULL, 'card-front-img', '2025-03-21 23:07:42', '2025-03-21 23:19:12'),
(163, 3, 2, 'Card Element Back', 'text', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.', NULL, NULL, 'CV-list-item', '2025-03-20 12:08:57', '2025-03-21 23:36:27'),
(164, 3, 2, 'Card Element Back', 'text', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.', NULL, NULL, 'CM-list-item', '2025-03-20 12:08:57', '2025-03-21 23:36:27'),
(165, 3, 2, 'Card Element Back', 'text', 'NaN', NULL, NULL, 'card-back-head', '2025-03-20 12:08:57', '2025-03-21 23:35:30'),
(166, 3, 2, 'Card Element Back', 'text', 'NaN', NULL, NULL, 'card-back-head', '2025-03-20 12:08:57', '2025-03-21 23:35:30'),
(167, 3, 18, 'onlinereg-section', 'image', NULL, '../../imgs/ocho.jpg', NULL, 'section-img', '2025-03-20 12:08:57', '2025-03-23 02:02:11'),
(168, 3, 18, 'onlinereg-section', 'image', NULL, '../../imgs/ocho.jpg', NULL, 'section-img', '2025-03-20 12:08:57', '2025-03-23 02:02:10'),
(169, 3, 18, 'onlinereg-section', 'image', NULL, '../../imgs/admin-office1.jpg', NULL, 'section-img', '2025-03-20 12:08:57', '2025-03-23 02:02:08'),
(170, 3, 18, 'onlinereg-section', 'image', NULL, '../../imgs/admin-office2.jpg', NULL, 'section-img', '2025-03-20 12:08:57', '2025-03-23 02:02:06'),
(174, 3, 18, 'onlinereg-section', 'text', 'UNDERGRADUATE', NULL, NULL, 'section-title', '2025-03-20 12:08:57', '2025-03-23 02:02:06'),
(175, 3, 18, 'onlinereg-section', 'text', 'Western Mindanao State University offers a variety of undergraduate programs that provide students with the knowledge and skills needed for professional success. With diverse fields such as Engineering, Medicine, and Liberal Arts, WMSU ensures academic excellence and prepares students to meet local and global challenges', NULL, NULL, 'section-content', '2025-03-20 12:08:57', '2025-03-23 02:02:06'),
(176, 3, 18, 'onlinereg-section', 'text', 'GRADUATE', NULL, NULL, 'section-title', '2025-03-20 12:08:57', '2025-03-23 02:02:06'),
(177, 3, 18, 'onlinereg-section', 'text', 'WMSU’s graduate programs focus on advancing professional and academic careers through research and specialized knowledge. These programs equip students to become leaders in their fields, contributing to societal development and addressing complex issues with innovative solutions.', NULL, NULL, 'section-content', '2025-03-20 12:08:57', '2025-03-23 02:02:06'),
(178, 3, 18, 'onlinereg-section', 'text', 'The MD Program strives to develop medical doctors who are competent, adaptable, and skilled in communication, leadership, and collaboration within healthcare settings. The program emphasizes clinical competence, professional ethics, and commitment to both personal and professional development.', NULL, NULL, 'section-content', '2025-03-20 12:08:57', '2025-03-23 02:02:06'),
(179, 3, 18, 'onlinereg-section', 'text', 'MEDICINE', NULL, NULL, 'section-title', '2025-03-20 12:08:57', '2025-03-23 02:02:06'),
(180, 1, NULL, 'Wmsu News', 'text', 'WMSU Ranks Among Top Universities in the Philippines', NULL, NULL, 'news-title', '2025-03-23 04:19:13', '2025-03-23 04:49:11'),
(181, 1, NULL, 'Wmsu News', 'text', 'New Smart Research Hub for Students and Faculty', NULL, NULL, 'news-title', '2025-03-23 04:19:13', '2025-03-23 04:49:10'),
(182, 1, NULL, 'Wmsu News', 'text', 'WMSU Strengthens Global Partnerships for Academic Excellence', NULL, NULL, 'news-title', '2025-03-23 04:19:13', '2025-03-23 04:49:09'),
(183, 1, NULL, 'Wmsu News', 'text', 'Students Win Big at National Science and Technology Fair', NULL, NULL, 'news-title', '2025-03-23 04:19:13', '2025-03-23 04:49:09'),
(184, 1, NULL, 'Wmsu News', 'text', 'Western Mindanao State University (WMSU) has been recognized as one of the top universities in the country, highlighting its commitment to quality education, research, and community development.', NULL, NULL, 'news-content', '2025-03-23 04:19:13', '2025-03-23 04:49:08'),
(185, 1, NULL, 'Wmsu News', 'text', 'WMSU leads its state-of-the-art Smart Research Hub, providing students and faculty with advanced facilities to support academic research, innovation, and collaboration.', NULL, NULL, 'news-content', '2025-03-23 04:19:13', '2025-03-23 04:49:07'),
(186, 1, NULL, 'Wmsu News', 'text', 'The university has signed new agreements with international institutions to expand exchange programs, research collaborations, and scholarship opportunities for students.', NULL, NULL, 'news-content', '2025-03-23 04:19:13', '2025-03-23 04:49:06'),
(187, 1, NULL, 'Wmsu News', 'image', NULL, '../imgs/news1.png', 'WMSU-Rankings', 'news-img', '2025-03-23 04:19:13', '2025-03-23 04:49:06'),
(188, 1, NULL, 'Wmsu News', 'image', NULL, '../imgs/news2.png', 'SMART Research Hub', 'news-img', '2025-03-23 04:19:13', '2025-03-23 04:49:05'),
(189, 1, NULL, 'Wmsu News', 'image', NULL, '../imgs/news3.png', 'Global-Partnerships', 'news-img', '2025-03-23 04:19:13', '2025-03-23 04:49:04'),
(190, 1, NULL, 'Wmsu News', 'image', NULL, '../imgs/news4.png', 'Science Fair', 'news-img', '2025-03-23 04:19:13', '2025-03-23 04:49:03'),
(191, 1, NULL, 'Research Archives', 'text', 'Artificial Intelligence in Education: Improving Learning Through Smart Tutoring Systems', NULL, NULL, 'research-title', '2025-03-23 04:19:13', '2025-03-23 04:23:38'),
(192, 1, NULL, 'Research Archives', 'text', 'Lead Researcher: Engr. John Dela Cruz, College of Computing Studies', NULL, NULL, 'research-author', '2025-03-23 04:19:13', '2025-03-23 04:23:38'),
(193, 1, NULL, 'Research Archives', 'text', 'As artificial intelligence (AI) continues to reshape various industries, its role in education has become increasingly significant. This study explores the implementation of AI-powered Smart Tutoring Systems (STS) to enhance student learning experiences. By leveraging machine learning algorithms, natural language processing, and adaptive learning models, these systems provide personalized guidance tailored to each student\'s learning pace and comprehension level.', NULL, NULL, 'research-description', '2025-03-23 04:19:13', '2025-03-23 04:23:38'),
(194, 1, NULL, 'Research Archives', 'text', 'Published: December 2023', NULL, NULL, 'research-pub-date', '2025-03-23 04:19:13', '2025-03-23 04:23:38'),
(195, 1, NULL, 'About WMSU', 'text', 'Learn how WMSU shapes future leaders, explore our rich history, and become part of a vibrant academic community', NULL, NULL, 'about-description', '2025-03-23 04:19:13', '2025-03-23 04:49:11'),
(196, 1, NULL, 'About WMSU', 'text', 'History of WMSU', NULL, NULL, 'about-links', '2025-03-23 04:19:13', '2025-03-23 04:49:11'),
(197, 1, NULL, 'About WMSU', 'text', 'Leadership and Governance', NULL, NULL, 'about-links', '2025-03-23 04:19:13', '2025-03-23 04:49:11'),
(198, 1, NULL, 'About WMSU', 'text', 'Mission and Vision', NULL, NULL, 'about-links', '2025-03-23 04:19:13', '2025-03-23 04:49:11'),
(199, 1, NULL, 'About WMSU', 'text', 'WMSU in the Community', NULL, NULL, 'about-links', '2025-03-23 04:19:13', '2025-03-23 04:49:11'),
(200, 1, NULL, 'Pres Corner', 'text', 'President\'s Report 1st Quarter 2024', NULL, NULL, 'report-title', '2025-03-23 04:19:13', '2025-03-23 05:46:32'),
(201, 1, NULL, 'Pres Corner', 'text', 'President\'s Report 1st Quarter 2023', NULL, NULL, 'report-title', '2025-03-23 04:19:13', '2025-03-23 04:49:09'),
(202, 1, NULL, 'Pres Corner', 'text', 'President\'s Final Report', NULL, NULL, 'report-title', '2025-03-23 04:19:13', '2025-03-23 04:49:09'),
(203, 1, NULL, 'Pres Corner', 'text', 'President\'s Report 4th Quarter', NULL, NULL, 'report-title', '2025-03-23 04:19:13', '2025-03-23 04:49:09'),
(204, 1, NULL, 'Pres Corner', 'text', 'President\'s Report Year 2', NULL, NULL, 'report-title', '2025-03-23 04:19:13', '2025-03-23 04:49:09'),
(205, 1, NULL, 'Pres Corner', 'text', 'April 11, 2024', NULL, NULL, 'report-date', '2025-03-23 04:19:13', '2025-03-23 05:12:05'),
(206, 1, NULL, 'Pres Corner', 'text', 'April 11, 2024', NULL, NULL, 'report-date', '2025-03-23 04:19:13', '2025-03-23 05:12:05'),
(207, 1, NULL, 'Pres Corner', 'text', 'April 11, 2024', NULL, NULL, 'report-date', '2025-03-23 04:19:13', '2025-03-23 05:12:05'),
(208, 1, NULL, 'Pres Corner', 'text', 'April 11, 2024', NULL, NULL, 'report-date', '2025-03-23 04:19:13', '2025-03-23 05:12:05'),
(209, 1, NULL, 'Pres Corner', 'text', 'April 11, 2024', NULL, NULL, 'report-date', '2025-03-23 04:19:13', '2025-03-23 05:12:05'),
(210, 1, NULL, 'Services', 'text', 'Freshman Online Pre-Admission', NULL, NULL, 'service-title', '2025-03-23 04:19:13', '2025-03-23 05:12:05'),
(211, 1, NULL, 'Services', 'text', 'Online Registration (Old Student)', NULL, NULL, 'service-title', '2025-03-23 04:19:13', '2025-03-23 05:12:05'),
(212, 1, NULL, 'Services', 'text', 'Online Advising', NULL, NULL, 'service-title', '2025-03-23 04:19:13', '2025-03-23 05:12:05'),
(213, 1, NULL, 'Services', 'text', 'Online Enlistment', NULL, NULL, 'service-title', '2025-03-23 04:19:13', '2025-03-23 05:12:05'),
(214, 1, NULL, 'Services', 'image', '', '../imgs/Freshman-icon.png', NULL, 'service-imgs', '2025-03-23 04:19:13', '2025-03-23 05:12:05'),
(215, 1, NULL, 'Services', 'image', '', '../imgs/Old Student-icon.png', NULL, 'service-imgs', '2025-03-23 04:19:13', '2025-03-23 05:12:05'),
(216, 1, NULL, 'Services', 'image', '', '../imgs/Advising-icon.png', NULL, 'service-imgs', '2025-03-23 04:19:13', '2025-03-23 05:12:05'),
(217, 1, NULL, 'Services', 'image', '', '../imgs/Enlistment-icon.png', NULL, 'service-imgs', '2025-03-23 04:19:13', '2025-03-23 05:12:05');

-- --------------------------------------------------------

--
-- Table structure for table `subpages`
--

CREATE TABLE `subpages` (
  `subpageID` int(11) NOT NULL,
  `pagesID` int(11) NOT NULL,
  `subPageName` varchar(255) NOT NULL,
  `subPagePath` varchar(255) NOT NULL,
  `imagePath` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subpages`
--

INSERT INTO `subpages` (`subpageID`, `pagesID`, `subPageName`, `subPagePath`, `imagePath`) VALUES
(1, 3, 'College of Computing Studies', '\\WMSU-HOMEPAGE\\page\\academics\\CCS.php', '../../imgs/ccs-logo-proc.png'),
(2, 3, 'College of Nursing', '\\WMSU-HOMEPAGE\\page\\academics\\CN.php', '../../imgs/cn-proc.png'),
(3, 3, 'College of Science and Mathematics', '\\WMSU-HOMEPAGE\\page\\academics\\CSM.php', '../../imgs/csm-proc.png'),
(4, 3, 'College of Agriculture', '\\WMSU-HOMEPAGE\\page\\academics\\CAgri.php', '../../imgs/agriculture-logo-proc.png'),
(5, 3, 'College of Law', '#', '../../imgs/cl-proc.png'),
(6, 3, 'College of Criminal Justice Education', '#', '../../imgs/ccje-proc.png'),
(7, 3, 'College of Engineering and Technology', '#', '../../imgs/cet-proc.png'),
(8, 3, 'College of Public Administration and Development Studies', '#', '../../imgs/cpads-proc.png'),
(9, 3, 'College of Social Work and Community Development', '#', '../../imgs/cswcd-proc.png'),
(10, 3, 'College of Teacher Education', '#', '../../imgs/cte-proc.png'),
(11, 3, 'College of Asian and Islamic Studies', '#', '../../imgs/cais-proc.png'),
(12, 3, 'College of Forestry and Environmental Studies', '#', '../../imgs/cfes-proc.png'),
(13, 3, 'College of Home Economics', '#', '../../imgs/che-proc.png'),
(14, 3, 'College of Medicine', '#', '../../imgs/cm-proc.png'),
(15, 3, 'College of Sports Science and Physical Education', '#', '../../imgs/ccspe-proc.png'),
(16, 3, 'College of Liberal Arts', '\\WMSU-HOMEPAGE\\page\\academics\\CLA.php', '../../imgs/cla-proc.png'),
(17, 3, 'College of Architecture', '#', '../../imgs/coa-proc.png'),
(18, 3, 'Online Registration ', '\\WMSU-HOMEPAGE\\page\\admissions\\OnlineReg.php', NULL),
(22, 3, 'Subpage Online Registration', '\\WMSU-HOMEPAGE\\page\\admissions\\under.php', '../../imgs/ocho.png'),
(23, 3, 'Subpage Online Registration', '\\WMSU-HOMEPAGE\\page\\admissions\\grad.php', '../../imgs/Admin-Office2.jpg'),
(24, 3, 'Subpage Online Registration', '\\WMSU-HOMEPAGE\\page\\admissions\\med_form.php', '../../imgs/Admin-Office1.jpg'),
(25, 1, 'History of WMSU', '#', NULL),
(26, 1, 'Leadership and Governance', '#', NULL),
(27, 1, 'Mission and Vision', '#', NULL),
(28, 1, 'WMSU in the Community', '#', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `generalelements`
--
ALTER TABLE `generalelements`
  ADD PRIMARY KEY (`elementID`);

--
-- Indexes for table `linking`
--
ALTER TABLE `linking`
  ADD PRIMARY KEY (`linkID`),
  ADD KEY `linking_general_elements` (`linkFor`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `page_sections`
--
ALTER TABLE `page_sections`
  ADD PRIMARY KEY (`sectionID`),
  ADD KEY `page_section_to_pages` (`pageID`),
  ADD KEY `page_section_to_subpages` (`subpage`);

--
-- Indexes for table `subpages`
--
ALTER TABLE `subpages`
  ADD PRIMARY KEY (`subpageID`),
  ADD KEY `subpages_pages` (`pagesID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `generalelements`
--
ALTER TABLE `generalelements`
  MODIFY `elementID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `linking`
--
ALTER TABLE `linking`
  MODIFY `linkID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `page_sections`
--
ALTER TABLE `page_sections`
  MODIFY `sectionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=218;

--
-- AUTO_INCREMENT for table `subpages`
--
ALTER TABLE `subpages`
  MODIFY `subpageID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `linking`
--
ALTER TABLE `linking`
  ADD CONSTRAINT `linking_general_elements` FOREIGN KEY (`linkFor`) REFERENCES `generalelements` (`elementID`);

--
-- Constraints for table `page_sections`
--
ALTER TABLE `page_sections`
  ADD CONSTRAINT `page_section_to_pages` FOREIGN KEY (`pageID`) REFERENCES `pages` (`ID`),
  ADD CONSTRAINT `page_section_to_subpages` FOREIGN KEY (`subpage`) REFERENCES `subpages` (`subpageID`);

--
-- Constraints for table `subpages`
--
ALTER TABLE `subpages`
  ADD CONSTRAINT `subpages_pages` FOREIGN KEY (`pagesID`) REFERENCES `pages` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
