-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 27, 2024 at 01:59 PM
-- Server version: 8.0.36
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `examplanningsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `assignment`
--

CREATE TABLE `assignment` (
  `AssignmentID` int NOT NULL,
  `ScheduleID` int DEFAULT NULL,
  `AssistantID` int DEFAULT NULL,
  `ExamID` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assignment`
--

INSERT INTO `assignment` (`AssignmentID`, `ScheduleID`, `AssistantID`, `ExamID`) VALUES
(22, NULL, 16, 11),
(23, NULL, 25, 11),
(24, NULL, 48, 12),
(25, NULL, 16, 12),
(26, NULL, 25, 12),
(27, NULL, 48, 13),
(28, 3, 16, NULL),
(29, 55, 16, NULL),
(30, 2, 48, NULL),
(31, 14, 25, NULL),
(32, NULL, 17, 14),
(33, NULL, 24, 14),
(34, NULL, 49, 15),
(35, NULL, 17, 15),
(36, 23, 49, NULL),
(37, 22, 24, NULL),
(38, NULL, 18, 16),
(39, NULL, 23, 16),
(40, NULL, 50, 16),
(41, NULL, 18, 17),
(42, NULL, 23, 17),
(43, 44, 23, NULL),
(44, 45, 50, NULL),
(45, 8, 19, NULL),
(46, 21, 18, NULL),
(47, NULL, 19, 18),
(48, NULL, 22, 18),
(49, NULL, 54, 19),
(50, NULL, 19, 19),
(51, 47, 22, NULL),
(52, 31, 54, NULL),
(53, NULL, 20, 20),
(54, NULL, 21, 21),
(55, NULL, 52, 21),
(56, NULL, 20, 21),
(57, 11, 21, NULL),
(58, 10, 21, NULL),
(59, 50, 52, NULL),
(60, 53, 60, NULL),
(61, NULL, 60, 22),
(62, 54, 65, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `CourseID` int NOT NULL,
  `Code` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `DepartmentID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`CourseID`, `Code`, `Name`, `DepartmentID`) VALUES
(1, 'CSE211', 'Data Structures', 1),
(2, 'CSE101', 'Computer Engineering Concepts and Algorithms', 1),
(3, 'LAW131', 'Legal Terminology I', 4),
(4, 'INDD124', 'Maquette and Prototyping', 5),
(5, 'CSE221', 'Principles of Logic Design', 1),
(6, 'INDD101', 'Clay Modelling', 5),
(7, 'ED103', 'Guidance in Education', 3),
(8, 'MED409\r\n', 'Public Health', 2),
(9, 'MED518', 'Child Psychiatry', 2),
(10, 'EDEN405', 'Young Learners in TEFL', 3),
(11, 'LAW474', 'Introduction to US Law II', 4),
(15, 'MED501', 'Orthopaedics and Traumatology', 2),
(16, 'EDEN306', 'Sociolinguistics & English Education', 3),
(17, 'LAW235', 'Common Law of Contracts', 4),
(18, 'INDD321', 'Design Theories', 5),
(19, 'BME252', 'Biomechanics', 6),
(20, 'BME324', 'Biomedical Sensors and Transducers', 6);

-- --------------------------------------------------------

--
-- Table structure for table `courseschedule`
--

CREATE TABLE `courseschedule` (
  `ScheduleID` int NOT NULL,
  `CourseID` int NOT NULL,
  `Date` date NOT NULL,
  `StartTime` time NOT NULL,
  `EndTime` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courseschedule`
--

INSERT INTO `courseschedule` (`ScheduleID`, `CourseID`, `Date`, `StartTime`, `EndTime`) VALUES
(1, 1, '2024-05-27', '08:00:00', '09:30:00'),
(2, 1, '2024-05-28', '09:00:00', '10:30:00'),
(3, 1, '2024-05-29', '10:00:00', '12:00:00'),
(4, 2, '2024-05-27', '11:00:00', '12:30:00'),
(5, 2, '2024-05-29', '12:00:00', '13:30:00'),
(6, 2, '2024-05-30', '13:00:00', '14:00:00'),
(7, 3, '2024-05-27', '14:00:00', '15:30:00'),
(8, 3, '2024-05-28', '15:00:00', '17:00:00'),
(9, 3, '2024-05-30', '16:00:00', '17:30:00'),
(10, 4, '2024-05-27', '17:00:00', '18:30:00'),
(11, 4, '2024-05-29', '18:00:00', '19:30:00'),
(12, 4, '2024-05-31', '19:00:00', '20:30:00'),
(13, 5, '2024-05-27', '08:00:00', '09:00:00'),
(14, 5, '2024-05-28', '09:00:00', '10:00:00'),
(15, 5, '2024-05-30', '10:00:00', '11:00:00'),
(16, 6, '2024-05-27', '11:00:00', '12:30:00'),
(17, 6, '2024-05-29', '12:00:00', '14:00:00'),
(18, 6, '2024-05-31', '13:00:00', '14:30:00'),
(19, 7, '2024-05-27', '14:00:00', '15:30:00'),
(20, 7, '2024-05-28', '15:00:00', '16:30:00'),
(21, 7, '2024-05-30', '16:00:00', '17:30:00'),
(22, 8, '2024-05-27', '17:00:00', '18:30:00'),
(23, 8, '2024-05-29', '18:00:00', '19:30:00'),
(24, 8, '2024-05-31', '19:00:00', '20:30:00'),
(25, 9, '2024-05-27', '08:00:00', '09:00:00'),
(26, 9, '2024-05-28', '09:00:00', '10:00:00'),
(27, 9, '2024-05-30', '10:00:00', '11:00:00'),
(28, 10, '2024-05-27', '11:00:00', '12:00:00'),
(29, 10, '2024-05-29', '12:00:00', '13:00:00'),
(30, 10, '2024-05-31', '13:00:00', '14:00:00'),
(31, 11, '2024-05-27', '14:00:00', '15:00:00'),
(32, 11, '2024-05-28', '15:00:00', '16:00:00'),
(33, 11, '2024-05-30', '16:00:00', '17:00:00'),
(40, 15, '2024-05-27', '17:00:00', '18:00:00'),
(41, 15, '2024-05-28', '18:00:00', '19:00:00'),
(42, 15, '2024-05-30', '19:00:00', '20:00:00'),
(43, 16, '2024-05-27', '08:00:00', '09:30:00'),
(44, 16, '2024-05-29', '09:00:00', '10:30:00'),
(45, 16, '2024-05-31', '10:00:00', '12:00:00'),
(46, 17, '2024-05-27', '11:00:00', '12:30:00'),
(47, 17, '2024-05-28', '12:00:00', '13:30:00'),
(48, 17, '2024-05-30', '13:00:00', '14:30:00'),
(49, 18, '2024-05-27', '14:00:00', '15:30:00'),
(50, 18, '2024-05-29', '15:00:00', '16:30:00'),
(51, 18, '2024-05-31', '16:00:00', '17:30:00'),
(52, 19, '2024-05-27', '17:00:00', '18:30:00'),
(53, 19, '2024-05-28', '18:00:00', '19:30:00'),
(54, 19, '2024-05-30', '19:00:00', '20:30:00'),
(55, 5, '2024-05-31', '16:00:00', '17:30:00'),
(57, 20, '2024-05-30', '08:00:00', '10:00:00'),
(58, 20, '2024-05-27', '14:00:00', '16:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `DepartmentID` int NOT NULL,
  `DepartmentName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `FacultyID` int NOT NULL,
  `HeadID` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`DepartmentID`, `DepartmentName`, `FacultyID`, `HeadID`) VALUES
(1, 'Computer Engineering', 1, 15),
(2, 'Medicine (Bachelor)', 3, 32),
(3, 'English Language Teaching Program', 5, 33),
(4, 'Law (Bachelor)', 2, 55),
(5, 'Industrial Design (Bachelor)', 4, 35),
(6, 'Biomedical Engineering', 1, 63);

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `EmployeeID` int NOT NULL,
  `Name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Role` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `departmentId` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `AssistantScore` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`EmployeeID`, `Name`, `Role`, `username`, `password`, `departmentId`, `AssistantScore`) VALUES
(15, 'Ömer Dikyol', 'Head of Department', 'odikyol', '$2y$10$SHNeAkfAHNIZyP4z5UNqbe45DtkKvDSzxew3rRO04yXUw17k5/OV2', '1', 0),
(16, 'Selma Tekin', 'Assistant', 'stekin', '$2y$10$vBgCjb6/eUw.6TRdAr1dE.xPqiCORm2t2l/8vCS.tp6I6hxGa4EM.', '1', 2),
(17, 'Sevim Koç', 'Assistant', 'skoc', '$2y$10$h2.E4Q6U5DCIpzDvYORfaOxHWGZi9mzcdEnFH2TmTTa3XHv1UsTA2', '2', 2),
(18, 'Leyla Şimşek', 'Assistant', 'lsimsek', '$2y$10$a9HoaNLORIXI9V9.sGdnjurU6y9z8VXQr8gDb0gHwYo9L97T4/dTC', '3', 2),
(19, 'Sevgi Işık', 'Assistant', 'sisik', '$2y$10$1qtJaHbU90V5sc0ro5o79.H6HZvwiwS63Vssjwsy6sDvaVUBQBep.', '4', 2),
(20, 'İbrahim Korkmaz', 'Assistant', 'ikorkmaz', '$2y$10$BRqx3XbFVpBMw5/yB4PKNOXHRmHc87RmkYyUkhr74taVptWCkZPyS', '5', 2),
(21, 'Fatih Taş', 'Assistant', 'ftas', '$2y$10$bR5pWAwgjqvG..FS0ZUjxuo.ef4Ocis5yY6s.S4.Z1NfsbAlzzXWa', '5', 1),
(22, 'Mahmut Turan', 'Assistant', 'mturan', '$2y$10$URJPbM2EoUWy5Yfr4fW17O906YnxMM8cQOFDzD8rovn02A31MAUiW', '4', 1),
(23, 'Zeynep Polat', 'Assistant', 'zpolat', '$2y$10$uKqL79LmvORazPhXKJ85yOLoH/VEs9VsOWozJtaSGpDXqYAiMeLam', '3', 2),
(24, 'Hatice Tekin', 'Assistant', 'htekin', '$2y$10$.KUyNRM.Ttaly.l2bvvZAO9tRi7oyzjDrzkUqbqgMrmJM8gnh9Mrm', '2', 1),
(25, 'Elif Yıldız', 'Assistant', 'eyildiz', '$2y$10$qPfThnClIvrizJaEfStIFuNuvkAaKgpdxsPx2p.sGweL2/9m.qnp6', '1', 2),
(26, 'Ramazan Şahin', 'Secretary', 'rsahin', '$2y$10$VlLxFjx/or3uvw/IYLE8decaSWfOUf34TfVqTd/KHzaiFByGA3Jfe', '5', 0),
(27, 'Mehmet Ali Arslan', 'Secretary', 'marslan', '$2y$10$r2LtuRBAvaYjfCeJPDArVea.dJSFpXNKf5K6aepcP.q0QPbRKzBnS', '3', 0),
(28, 'Furkan Öztürk', 'Secretary', 'fozturk', '$2y$10$ByvVcevyM3D6Wr4WBBfa5OhPyG7GdrGYljrCEs6aVV.avYvg.mv1u', '1', 0),
(29, 'Halime Yavuz', 'Secretary', 'hyavuz', '$2y$10$MCAFiUoDmIbnWsxPmJNKsO25cvvWdrKbuZUpot4pd2oSXL.vTtXD.', '2', 0),
(31, 'Ayten Ünal', 'Secretary', 'aunal', '$2y$10$zVGOcNbLEgyM7.9iGvTpte.TMzHOqszvqcRc3q4NEDWFfWu6CI9zC', '4', 0),
(32, 'Rabia Yavuz', 'Head of Department', 'ryavuz', '$2y$10$r7gHrgUjyRRQhm/7ng3Ge.FkyIw1GbULz.vsrJCXGtmTdwcg2faHC', '2', 0),
(33, 'Elif Aslan', 'Head of Department', 'easlan', '$2y$10$7EAh0ppTRiiBwWWcAc8Luusk2tgS20dz0VLLuvvhgLsWV8WZfLapG', '3', 0),
(35, 'Mehmet Kurt', 'Head of Department', 'mkurt', '$2y$10$GIYn3hRIZSsZ3d1b.u96euJ7brOKlV4KcUWmpEZTM93l5dR4j0lZW', '5', 0),
(36, 'Recep Acar', 'Head of Secretary', 'racar', '$2y$10$M88EJ0AnaKrd8RXTsyyB5eFHlN4l8F9Z4QXOht/FS3BRtU1nJudki', '1', 0),
(37, 'Yunus Turan', 'Head of Secretary', 'yturan', '$2y$10$fux3baNA2LfFyrB20nmvDuxHy63QmvjmiibYbLrlg5dPvdZhe5p1C', '2', 0),
(38, 'Leyla Korkmaz', 'Head of Secretary', 'lkorkmaz', '$2y$10$co7hxnug8iPuJA0Dpp4GneM7THNpUwKfhLGYOPt33DLcSCiAP134O', '3', 0),
(39, 'Leyla Can', 'Head of Secretary', 'lcan', '$2y$10$JqrdhRM4N6sVvqapfl/YU./L89HSfp6vBMETwZsvk9Icl1o4OxkPe', '4', 0),
(40, 'Orhan Sarı', 'Head of Secretary', 'osari', '$2y$10$dkoyrefL6v6j6NZRupmeCuF6tc0jKc.DHf15EimDxWOHPB0v7QKG6', '5', 0),
(42, 'Ali Tekin', 'Dean', 'atekin', '$2y$10$t9Bv4t3p1DzxAsr6xA1FWubtEMOmITDsWfOPNTDVjP69aOQigFmKm', NULL, 0),
(43, 'Hasan Kara', 'Dean', 'hkara', '$2y$10$NJ5a7cTZmRKsopcm0OffQuMRO/ZOQGgEX5WWAxphXuBdTuJnNMrri', NULL, 0),
(44, 'Yusuf Aydın', 'Dean', 'yaydin', '$2y$10$KQdbITqo3IApR3q0I/0Y/esUzbP.DbEZ0N0uqztk9VaUwZc3S224y', NULL, 0),
(45, 'Kadriye Yılmaz', 'Dean', 'kyilmaz', '$2y$10$E3bXQYegCIrzRuX6pEF8.eCucL9gjOjxNUkJkS2qyZyeYvHJemXZG', NULL, 0),
(46, 'Fatih Yıldırım', 'Dean', 'fyildirim', '$2y$10$sObAWxNHUz48mkvt2i5fFeXRAkauj6FzX9pjkeh1BNSBGN1ba1IvG', NULL, 0),
(47, 'Berk Akbey', 'Admin', 'admin', '$2y$10$AHUt6YOoM9cF4p.dMPykb.9H4.U5fTcabgfLo1hrCBa6Fxz9Bmo3K', NULL, 0),
(48, 'Enes Çelik', 'Assistant', 'ecelik', '$2y$10$UO//t3RTT8XlO/mFlvrsa.3Cez8fffefyig7DyEKCFDva9oHK2Vni', '1', 2),
(49, 'Muhammed Can', 'Assistant', 'mcan', '$2y$10$SRn5ZtjZLStrmZYI4kctle7axKYb9X9M7hhlCMToDklHg9rIV68mu', '2', 1),
(50, 'Esra Gül', 'Assistant', 'egul', '$2y$10$O7N99nPr/J8gwRDIyFSPGOvG3alazuYEZrrL/zoSRXMXADOh1/wrm', '3', 1),
(52, 'Ebru Güneş', 'Assistant', 'egunes', '$2y$10$50cuA/N4MhuNwfo79w3XP.okxHte9OqbHMtopouv.YyUa4ZCGNKxa', '5', 1),
(54, 'Orhan Polat', 'Assistant', 'opolat', '$2y$10$Na/TOOokEcuvUsYDQCzOduIfYRx.hPAr4FiUZrNK.hfDw44pMdC3K', '4', 1),
(55, 'Ayten Şen', 'Head of Department', 'asen', '$2y$10$ZJl2f96CiFaTUS0fhfz1I.u01cBp17jNlcTY9ryNEwyOejLumUh3O', '4', 0),
(60, 'Cemal Aksoy', 'Assistant', 'caksoy', '$2y$10$wkPTLe4UAYIco3FlTk7JGuIArG9y3WZtLiEkUbZAk0ViUMOFj1YpC', '6', 1),
(62, 'Arif Sarı', 'Secretary', 'asari', '$2y$10$A0KPka7DZM1uUp/rZq2d0.BMHVux7ZUZpg12OKxqlF88aOk9/6vT6', '6', 0),
(63, 'Yasin Keskin', 'Head of Department', 'ykeskin', '$2y$10$MVvZUaUzicYJoi9ANwV.fOgpXDKc9g81zDR6wH4sAy3jl8/6zcBC2', '6', 0),
(64, 'Hava Arslan', 'Head of Secretary', 'harslan', '$2y$10$xbucaQBbVK1en9kP/Nw6n.49PBiIn4OuvGjW30xy2O1bRQoSwsobi', '6', 0),
(65, 'Cansu Konat', 'Assistant', 'ckonat', '$2y$10$MZKv3KlJBjV.GX784PtWsesuCyuEnmWT7UCsqv6y7xxFieNqpkKmy', '6', 0);

-- --------------------------------------------------------

--
-- Table structure for table `exam`
--

CREATE TABLE `exam` (
  `ExamID` int NOT NULL,
  `CourseID` int NOT NULL,
  `ExamDate` date NOT NULL,
  `NumberOfClasses` int NOT NULL DEFAULT '1',
  `AssistantsNeeded` int NOT NULL,
  `StartTime` time NOT NULL,
  `EndTime` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exam`
--

INSERT INTO `exam` (`ExamID`, `CourseID`, `ExamDate`, `NumberOfClasses`, `AssistantsNeeded`, `StartTime`, `EndTime`) VALUES
(11, 2, '2024-05-30', 2, 2, '11:00:00', '13:30:00'),
(12, 1, '2024-05-28', 1, 3, '16:00:00', '18:00:00'),
(13, 5, '2024-05-31', 2, 1, '14:00:00', '15:30:00'),
(14, 8, '2024-05-31', 1, 2, '16:00:00', '18:00:00'),
(15, 15, '2024-05-28', 2, 2, '18:00:00', '20:30:00'),
(16, 10, '2024-05-30', 2, 3, '12:00:00', '14:00:00'),
(17, 16, '2024-05-27', 2, 2, '14:30:00', '16:00:00'),
(18, 3, '2024-05-31', 2, 2, '16:00:00', '18:00:00'),
(19, 17, '2024-05-29', 2, 2, '20:00:00', '21:30:00'),
(20, 18, '2024-05-31', 2, 1, '15:00:00', '17:30:00'),
(21, 6, '2024-05-29', 2, 3, '16:00:00', '18:30:00'),
(22, 20, '2024-05-30', 2, 1, '14:00:00', '15:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `FacultyID` int NOT NULL,
  `FacultyName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `DeanID` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`FacultyID`, `FacultyName`, `DeanID`) VALUES
(1, 'Engineering', 46),
(2, 'Law', 42),
(3, 'Medicine', 43),
(4, 'Architecture', 44),
(5, 'Education', 45);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assignment`
--
ALTER TABLE `assignment`
  ADD PRIMARY KEY (`AssignmentID`),
  ADD KEY `ScheduleID` (`ScheduleID`),
  ADD KEY `ExamID` (`ExamID`) USING BTREE,
  ADD KEY `AssistantID` (`AssistantID`) USING BTREE;

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`CourseID`),
  ADD KEY `DepartmentID` (`DepartmentID`);

--
-- Indexes for table `courseschedule`
--
ALTER TABLE `courseschedule`
  ADD PRIMARY KEY (`ScheduleID`),
  ADD KEY `CourseID` (`CourseID`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`DepartmentID`),
  ADD KEY `FacultyID` (`FacultyID`),
  ADD KEY `HeadID` (`HeadID`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`EmployeeID`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `exam`
--
ALTER TABLE `exam`
  ADD PRIMARY KEY (`ExamID`),
  ADD KEY `CourseID` (`CourseID`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`FacultyID`),
  ADD KEY `DeanID` (`DeanID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assignment`
--
ALTER TABLE `assignment`
  MODIFY `AssignmentID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `CourseID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `courseschedule`
--
ALTER TABLE `courseschedule`
  MODIFY `ScheduleID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `DepartmentID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `EmployeeID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `exam`
--
ALTER TABLE `exam`
  MODIFY `ExamID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `FacultyID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assignment`
--
ALTER TABLE `assignment`
  ADD CONSTRAINT `assignment_ibfk_2` FOREIGN KEY (`AssistantID`) REFERENCES `employee` (`EmployeeID`),
  ADD CONSTRAINT `fk_assistant` FOREIGN KEY (`AssistantID`) REFERENCES `employee` (`EmployeeID`),
  ADD CONSTRAINT `fk_exam` FOREIGN KEY (`ExamID`) REFERENCES `exam` (`ExamID`);

--
-- Constraints for table `course`
--
ALTER TABLE `course`
  ADD CONSTRAINT `course_ibfk_1` FOREIGN KEY (`DepartmentID`) REFERENCES `department` (`DepartmentID`);

--
-- Constraints for table `courseschedule`
--
ALTER TABLE `courseschedule`
  ADD CONSTRAINT `CourseSchedule_ibfk_1` FOREIGN KEY (`CourseID`) REFERENCES `course` (`CourseID`);

--
-- Constraints for table `department`
--
ALTER TABLE `department`
  ADD CONSTRAINT `department_ibfk_1` FOREIGN KEY (`FacultyID`) REFERENCES `faculty` (`FacultyID`),
  ADD CONSTRAINT `department_ibfk_2` FOREIGN KEY (`HeadID`) REFERENCES `employee` (`EmployeeID`);

--
-- Constraints for table `exam`
--
ALTER TABLE `exam`
  ADD CONSTRAINT `exam_ibfk_1` FOREIGN KEY (`CourseID`) REFERENCES `course` (`CourseID`);

--
-- Constraints for table `faculty`
--
ALTER TABLE `faculty`
  ADD CONSTRAINT `faculty_ibfk_1` FOREIGN KEY (`DeanID`) REFERENCES `employee` (`EmployeeID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
