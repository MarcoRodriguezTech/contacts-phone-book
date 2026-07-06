-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 06, 2026 at 08:36 AM
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
-- Database: `emergency_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `number` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_favorite` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `number`, `email`, `description`, `is_favorite`) VALUES
(1, 'National Emergency Hotline', '911', 'info@911.gov.ph', 'Main national emergency hotline for police, fire, and medical assistance.', 1),
(2, 'NDRRMC (Disaster Response)', '(02) 8911-1406', 'opcen@ndrrmc.gov.ph', 'National Disaster Risk Reduction and Management Council.', 1),
(3, 'PNP - National Police', '117', 'feedback@pnp.gov.ph', 'Philippine National Police emergency hotline.', 1),
(4, 'BFP - Bureau of Fire Protection', '(02) 8426-0219', 'ofc@bfp.gov.ph', 'National fire emergency response and rescue services.', 1),
(5, 'Philippine Red Cross', '143', 'communication@redcross.org.ph', 'Blood coordination, disaster rescue, and ambulance services.', 1),
(6, 'MMDA (Metro Manila Dev Auth)', '136', 'pmo@mmda.gov.ph', 'Traffic updates, road emergencies, and flood monitoring.', 0),
(7, 'DOH - Dept of Health', '(02) 8651-7800', 'callcenter@doh.gov.ph', 'Health emergencies and national hospital coordination.', 0),
(8, 'PCG - Philippine Coast Guard', '(02) 8527-3877', 'pcg@coastguard.gov.ph', 'Maritime search and rescue, sea-based emergencies.', 0),
(9, 'Philippine General Hospital (PGH)', '(02) 8554-8400', 'pgh@up.edu.ph', 'Major public referral hospital located in Manila.', 0),
(10, 'St. Luke Medical Center - QC', '(02) 8723-0101', 'info.qc@stluke.com.ph', 'Emergency room and trauma center - Quezon City.', 0),
(11, 'St. Luke Medical Center - BGC', '(02) 8789-7700', 'info.bgc@stluke.com.ph', 'Emergency room and trauma center - Global City.', 0),
(12, 'Makati Medical Center', '(02) 8888-8999', 'mmc@makatimed.net.ph', 'Emergency and trauma services in Makati.', 0),
(13, 'The Medical City - Pasig', '(02) 8988-1000', 'mail@themedicalcity.com', 'Main hospital hotline and emergency services.', 0),
(14, 'Lung Center of the Philippines', '(02) 8924-6101', 'lcp@lungcenter.gov.ph', 'Specialized respiratory and emergency medical facility.', 0),
(15, 'National Kidney & Transplant Inst', '(02) 8981-0300', 'info@nkti.gov.ph', 'Specialized renal and emergency care.', 0),
(16, 'Philippine Heart Center', '(02) 8925-2401', 'director@phc.gov.ph', 'Cardiovascular emergency care and surgery.', 0),
(17, 'East Avenue Medical Center', '(02) 8928-0611', 'eamc@doh.gov.ph', 'Public trauma and general emergency hospital.', 0),
(18, 'San Lazaro Hospital', '(02) 8732-3776', 'slh@doh.gov.ph', 'Specialized infectious disease emergency hospital.', 0),
(19, 'Amang Rodriguez Memorial Center', '(02) 8941-5854', 'armmc@doh.gov.ph', 'Public emergency hospital serving Marikina/Rizal.', 0),
(20, 'Rizal Medical Center', '(02) 8865-8400', 'rmc@doh.gov.ph', 'Public emergency hospital serving Pasig/Rizal.', 0),
(21, 'PNP Anti-Kidnapping Group', '(02) 8724-7329', 'akg@pnp.gov.ph', 'Specialized police unit for kidnapping emergencies.', 0),
(22, 'PNP Highway Patrol Group', '(02) 8723-0401', 'hpg@pnp.gov.ph', 'Highway accidents, carnapping, and road security.', 0),
(23, 'NBI - National Bureau of Investigation', '(02) 8523-8231', 'crmd@nbi.gov.ph', 'Investigative assistance and major crime reporting.', 0),
(24, 'PAGASA (Weather Bureau)', '(02) 8284-0800', 'information@pagasa.dost.gov.ph', 'Typhoon tracking, weather warnings, and flood alerts.', 0),
(25, 'PHIVOLCS (Earthquake/Volcano)', '(02) 8426-1468', 'phivolcs_opcen@phivolcs.dost.gov.ph', 'Earthquake monitoring and volcanic eruption alerts.', 0),
(26, 'DSWD (Social Welfare)', '(02) 8931-8101', 'osec@dswd.gov.ph', 'Disaster relief distribution and social services emergency.', 0),
(27, 'Meralco (Power Outages/Hazards)', '16211', 'customercare@meralco.com.ph', 'Electrical fires, downed wires, and power emergencies.', 0),
(28, 'Maynilad Water (Water Emergencies)', '1626', 'customer.care@mayniladwater.com.ph', 'Major water leaks, pipe bursts, and supply issues.', 0),
(29, 'Manila Water Hotline', '1627', 'customer@manilawater.com.ph', 'Water line ruptures and critical supply emergencies.', 0),
(30, 'QC Action Center', '122', 'qchelpline@quezoncity.gov.ph', 'Local city government emergencies in Quezon City.', 0),
(31, 'Manila City Hall Action Center', '(02) 8527-4950', 'manila_help@manila.gov.ph', 'Local city government emergencies in Manila City.', 0),
(32, 'Pasig City Emergency Unit (RED)', '(02) 8643-0000', 'pasigred@pasig.gov.ph', 'Pasig City local rescue and disaster team.', 0),
(33, 'Makati Rescue', '(02) 816-8000', 'makati_rescue@makati.gov.ph', 'Makati City local rescue and medical dispatch.', 0),
(34, 'Taguig City Emergency Hotline', '(02) 8628-0000', 'taguigrescue@taguig.gov.ph', 'Taguig Command Center for emergencies and rescue.', 0),
(35, 'Mandaluyong Rescue', '(02) 8533-2225', 'mandaluyong_rescue@mandaluyong.gov.ph', 'Local disaster response for Mandaluyong.', 0),
(36, 'Marikina Rescue', '161', 'marikina_rescue@marikina.gov.ph', 'Marikina City swift flood and medical rescue.', 0),
(37, 'Parañaque Cleanliness & Rescue', '(02) 8820-7383', 'pque_rescue@paranaque.gov.ph', 'Parañaque City disaster and medical response.', 0),
(38, 'Las Piñas Emergency Command', '(02) 8552-8224', 'lp_rescue@laspinas.gov.ph', 'Local command center for Las Piñas.', 0),
(39, 'Pasay City Police Station', '(02) 8831-1544', 'pasay_police@pnp.gov.ph', 'Local police response for Pasay City.', 0),
(40, 'Caloocan Rescue Team', '(02) 8888-2256', 'caloocan_rescue@caloocan.gov.ph', 'Disaster and medical emergencies in Caloocan.', 0),
(41, 'Malabon City Disaster Command', '(02) 8921-6009', 'malabon_mdrrmo@malabon.gov.ph', 'Flood and rescue updates for Malabon.', 0),
(42, 'Navotas Rescue Center', '(02) 8281-8531', 'navotas_rescue@navotas.gov.ph', 'Coastal and local emergencies in Navotas.', 0),
(43, 'Valenzuela Alert Center', '(02) 8352-5000', 'alert@valenzuela.gov.ph', 'Unified command center for Valenzuela City.', 0),
(44, 'San Juan City Emergency Command', '(02) 8723-9541', 'sanjuan_rescue@sanjuan.gov.ph', 'Local police, fire, and medical dispatch for San Juan.', 0),
(45, 'Muntinlupa Rescue', '(02) 8925-4351', 'muntinlupa_rrmo@muntinlupa.gov.ph', 'Disaster response team for Muntinlupa City.', 0),
(46, 'Pateros Rescue Team', '(02) 8642-5159', 'pateros_rescue@pateros.gov.ph', 'Local emergency response for Pateros municipality.', 0),
(47, 'Chinese General Hospital', '(02) 8711-4141', 'info@cghmc.com.ph', 'Emergency room and medical services in Manila.', 0),
(48, 'Capitol Medical Center', '(02) 8372-3826', 'info@capitolmed.com.ph', 'Emergency medical and trauma department in QC.', 0),
(49, 'Our Lady of Lourdes Hospital', '(02) 8716-8001', 'ollh@ollh.com.ph', 'Emergency medical facility located in Sta. Mesa.', 0),
(50, 'Chong Hua Hospital (Cebu City)', '(032) 255-8000', 'info@chonghua.com.ph', 'Primary emergency and critical care hospital in Cebu.', 0),
(51, 'Blank Slot #1', '000-0000', '', 'Click edit to update this slot.', 0),
(52, 'Blank Slot #2', '000-0000', '', 'Click edit to update this slot.', 0),
(53, 'Blank Slot #3', '000-0000', '', 'Click edit to update this slot.', 0),
(54, 'Blank Slot #4', '000-0000', '', 'Click edit to update this slot.', 0),
(55, 'Blank Slot #5', '000-0000', '', 'Click edit to update this slot.', 0),
(56, 'Blank Slot #6', '000-0000', '', 'Click edit to update this slot.', 0),
(57, 'Blank Slot #7', '000-0000', '', 'Click edit to update this slot.', 0),
(58, 'Blank Slot #8', '000-0000', '', 'Click edit to update this slot.', 0),
(59, 'Blank Slot #9', '000-0000', '', 'Click edit to update this slot.', 0),
(60, 'Blank Slot #10', '000-0000', '', 'Click edit to update this slot.', 0),
(61, 'Blank Slot #11', '000-0000', '', 'Click edit to update this slot.', 0),
(62, 'Blank Slot #12', '000-0000', '', 'Click edit to update this slot.', 0),
(63, 'Blank Slot #13', '000-0000', '', 'Click edit to update this slot.', 0),
(64, 'Blank Slot #14', '000-0000', '', 'Click edit to update this slot.', 0),
(65, 'Blank Slot #15', '000-0000', '', 'Click edit to update this slot.', 0),
(66, 'Blank Slot #16', '000-0000', '', 'Click edit to update this slot.', 0),
(67, 'Blank Slot #17', '000-0000', '', 'Click edit to update this slot.', 0),
(68, 'Blank Slot #18', '000-0000', '', 'Click edit to update this slot.', 0),
(69, 'Blank Slot #19', '000-0000', '', 'Click edit to update this slot.', 0),
(70, 'Blank Slot #20', '000-0000', '', 'Click edit to update this slot.', 0),
(71, 'Blank Slot #21', '000-0000', '', 'Click edit to update this slot.', 0),
(72, 'Blank Slot #22', '000-0000', '', 'Click edit to update this slot.', 0),
(73, 'Blank Slot #23', '000-0000', '', 'Click edit to update this slot.', 0),
(74, 'Blank Slot #24', '000-0000', '', 'Click edit to update this slot.', 0),
(75, 'Blank Slot #25', '000-0000', '', 'Click edit to update this slot.', 0),
(76, 'Blank Slot #26', '000-0000', '', 'Click edit to update this slot.', 0),
(77, 'Blank Slot #27', '000-0000', '', 'Click edit to update this slot.', 0),
(78, 'Blank Slot #28', '000-0000', '', 'Click edit to update this slot.', 0),
(79, 'Blank Slot #29', '000-0000', '', 'Click edit to update this slot.', 0),
(80, 'Blank Slot #30', '000-0000', '', 'Click edit to update this slot.', 0),
(81, 'Blank Slot #31', '000-0000', '', 'Click edit to update this slot.', 0),
(82, 'Blank Slot #32', '000-0000', '', 'Click edit to update this slot.', 0),
(83, 'Blank Slot #33', '000-0000', '', 'Click edit to update this slot.', 0),
(84, 'Blank Slot #34', '000-0000', '', 'Click edit to update this slot.', 0),
(85, 'Blank Slot #35', '000-0000', '', 'Click edit to update this slot.', 0),
(86, 'Blank Slot #36', '000-0000', '', 'Click edit to update this slot.', 0),
(87, 'Blank Slot #37', '000-0000', '', 'Click edit to update this slot.', 0),
(88, 'Blank Slot #38', '000-0000', '', 'Click edit to update this slot.', 0),
(89, 'Blank Slot #39', '000-0000', '', 'Click edit to update this slot.', 0),
(90, 'Blank Slot #40', '000-0000', '', 'Click edit to update this slot.', 0),
(91, 'Blank Slot #41', '000-0000', '', 'Click edit to update this slot.', 0),
(92, 'Blank Slot #42', '000-0000', '', 'Click edit to update this slot.', 0),
(93, 'Blank Slot #43', '000-0000', '', 'Click edit to update this slot.', 0),
(94, 'Blank Slot #44', '000-0000', '', 'Click edit to update this slot.', 0),
(95, 'Blank Slot #45', '000-0000', '', 'Click edit to update this slot.', 0),
(96, 'Blank Slot #46', '000-0000', '', 'Click edit to update this slot.', 0),
(97, 'Blank Slot #47', '000-0000', '', 'Click edit to update this slot.', 0),
(98, 'Blank Slot #48', '000-0000', '', 'Click edit to update this slot.', 0),
(99, 'Blank Slot #49', '000-0000', '', 'Click edit to update this slot.', 0),
(100, 'Blank Slot #50', '000-0000', '', 'Click edit to update this slot.', 0),
(101, 'Blank Slot #51', '000-0000', '', 'Click edit to update this slot.', 0),
(102, 'Blank Slot #52', '000-0000', '', 'Click edit to update this slot.', 0),
(103, 'Blank Slot #53', '000-0000', '', 'Click edit to update this slot.', 0),
(104, 'Blank Slot #54', '000-0000', '', 'Click edit to update this slot.', 0),
(105, 'Blank Slot #55', '000-0000', '', 'Click edit to update this slot.', 0),
(106, 'Blank Slot #56', '000-0000', '', 'Click edit to update this slot.', 0),
(107, 'Blank Slot #57', '000-0000', '', 'Click edit to update this slot.', 0),
(108, 'Blank Slot #58', '000-0000', '', 'Click edit to update this slot.', 0),
(109, 'Blank Slot #59', '000-0000', '', 'Click edit to update this slot.', 0),
(110, 'Blank Slot #60', '000-0000', '', 'Click edit to update this slot.', 0),
(111, 'Blank Slot #61', '000-0000', '', 'Click edit to update this slot.', 0),
(112, 'Blank Slot #62', '000-0000', '', 'Click edit to update this slot.', 0),
(113, 'Blank Slot #63', '000-0000', '', 'Click edit to update this slot.', 0),
(114, 'Blank Slot #64', '000-0000', '', 'Click edit to update this slot.', 0),
(115, 'Blank Slot #65', '000-0000', '', 'Click edit to update this slot.', 0),
(116, 'Blank Slot #66', '000-0000', '', 'Click edit to update this slot.', 0),
(117, 'Blank Slot #67', '000-0000', '', 'Click edit to update this slot.', 0),
(118, 'Blank Slot #68', '000-0000', '', 'Click edit to update this slot.', 0),
(119, 'Blank Slot #69', '000-0000', '', 'Click edit to update this slot.', 0),
(120, 'Blank Slot #70', '000-0000', '', 'Click edit to update this slot.', 0),
(121, 'Blank Slot #71', '000-0000', '', 'Click edit to update this slot.', 0),
(122, 'Blank Slot #72', '000-0000', '', 'Click edit to update this slot.', 0),
(123, 'Blank Slot #73', '000-0000', '', 'Click edit to update this slot.', 0),
(124, 'Blank Slot #74', '000-0000', '', 'Click edit to update this slot.', 0),
(125, 'Blank Slot #75', '000-0000', '', 'Click edit to update this slot.', 0),
(126, 'Blank Slot #76', '000-0000', '', 'Click edit to update this slot.', 0),
(127, 'Blank Slot #77', '000-0000', '', 'Click edit to update this slot.', 0),
(128, 'Blank Slot #78', '000-0000', '', 'Click edit to update this slot.', 0),
(129, 'Blank Slot #79', '000-0000', '', 'Click edit to update this slot.', 0),
(130, 'Blank Slot #80', '000-0000', '', 'Click edit to update this slot.', 0),
(131, 'Blank Slot #81', '000-0000', '', 'Click edit to update this slot.', 0),
(132, 'Blank Slot #82', '000-0000', '', 'Click edit to update this slot.', 0),
(133, 'Blank Slot #83', '000-0000', '', 'Click edit to update this slot.', 0),
(134, 'Blank Slot #84', '000-0000', '', 'Click edit to update this slot.', 0),
(135, 'Blank Slot #85', '000-0000', '', 'Click edit to update this slot.', 0),
(136, 'Blank Slot #86', '000-0000', '', 'Click edit to update this slot.', 0),
(137, 'Blank Slot #87', '000-0000', '', 'Click edit to update this slot.', 0),
(138, 'Blank Slot #88', '000-0000', '', 'Click edit to update this slot.', 0),
(139, 'Blank Slot #89', '000-0000', '', 'Click edit to update this slot.', 0),
(140, 'Blank Slot #90', '000-0000', '', 'Click edit to update this slot.', 0),
(141, 'Blank Slot #91', '000-0000', '', 'Click edit to update this slot.', 0),
(142, 'Blank Slot #92', '000-0000', '', 'Click edit to update this slot.', 0),
(143, 'Blank Slot #93', '000-0000', '', 'Click edit to update this slot.', 0),
(144, 'Blank Slot #94', '000-0000', '', 'Click edit to update this slot.', 0),
(145, 'Blank Slot #95', '000-0000', '', 'Click edit to update this slot.', 0),
(146, 'Blank Slot #96', '000-0000', '', 'Click edit to update this slot.', 0),
(147, 'Blank Slot #97', '000-0000', '', 'Click edit to update this slot.', 0),
(148, 'Blank Slot #98', '000-0000', '', 'Click edit to update this slot.', 0),
(149, 'Blank Slot #99', '000-0000', '', 'Click edit to update this slot.', 0),
(150, 'Blank Slot #100', '000-0000', '', 'Click edit to update this slot.', 0),
(151, 'Blank Slot #101', '000-0000', '', 'Click edit to update this slot.', 0),
(152, 'Blank Slot #102', '000-0000', '', 'Click edit to update this slot.', 0),
(153, 'Blank Slot #103', '000-0000', '', 'Click edit to update this slot.', 0),
(154, 'Blank Slot #104', '000-0000', '', 'Click edit to update this slot.', 0),
(155, 'Blank Slot #105', '000-0000', '', 'Click edit to update this slot.', 0),
(156, 'Blank Slot #106', '000-0000', '', 'Click edit to update this slot.', 0),
(157, 'Blank Slot #107', '000-0000', '', 'Click edit to update this slot.', 0),
(158, 'Blank Slot #108', '000-0000', '', 'Click edit to update this slot.', 0),
(159, 'Blank Slot #109', '000-0000', '', 'Click edit to update this slot.', 0),
(160, 'Blank Slot #110', '000-0000', '', 'Click edit to update this slot.', 0),
(161, 'Blank Slot #111', '000-0000', '', 'Click edit to update this slot.', 0),
(162, 'Blank Slot #112', '000-0000', '', 'Click edit to update this slot.', 0),
(163, 'Blank Slot #113', '000-0000', '', 'Click edit to update this slot.', 0),
(164, 'Blank Slot #114', '000-0000', '', 'Click edit to update this slot.', 0),
(165, 'Blank Slot #115', '000-0000', '', 'Click edit to update this slot.', 0),
(166, 'Blank Slot #116', '000-0000', '', 'Click edit to update this slot.', 0),
(167, 'Blank Slot #117', '000-0000', '', 'Click edit to update this slot.', 0),
(168, 'Blank Slot #118', '000-0000', '', 'Click edit to update this slot.', 0),
(169, 'Blank Slot #119', '000-0000', '', 'Click edit to update this slot.', 0),
(170, 'Blank Slot #120', '000-0000', '', 'Click edit to update this slot.', 0),
(171, 'Blank Slot #121', '000-0000', '', 'Click edit to update this slot.', 0),
(172, 'Blank Slot #122', '000-0000', '', 'Click edit to update this slot.', 0),
(173, 'Blank Slot #123', '000-0000', '', 'Click edit to update this slot.', 0),
(174, 'Blank Slot #124', '000-0000', '', 'Click edit to update this slot.', 0),
(175, 'Blank Slot #125', '000-0000', '', 'Click edit to update this slot.', 0),
(176, 'Blank Slot #126', '000-0000', '', 'Click edit to update this slot.', 0),
(177, 'Blank Slot #127', '000-0000', '', 'Click edit to update this slot.', 0),
(178, 'Blank Slot #128', '000-0000', '', 'Click edit to update this slot.', 0),
(179, 'Blank Slot #129', '000-0000', '', 'Click edit to update this slot.', 0),
(180, 'Blank Slot #130', '000-0000', '', 'Click edit to update this slot.', 0),
(181, 'Blank Slot #131', '000-0000', '', 'Click edit to update this slot.', 0),
(182, 'Blank Slot #132', '000-0000', '', 'Click edit to update this slot.', 0),
(183, 'Blank Slot #133', '000-0000', '', 'Click edit to update this slot.', 0),
(184, 'Blank Slot #134', '000-0000', '', 'Click edit to update this slot.', 0),
(185, 'Blank Slot #135', '000-0000', '', 'Click edit to update this slot.', 0),
(186, 'Blank Slot #136', '000-0000', '', 'Click edit to update this slot.', 0),
(187, 'Blank Slot #137', '000-0000', '', 'Click edit to update this slot.', 0),
(188, 'Blank Slot #138', '000-0000', '', 'Click edit to update this slot.', 0),
(189, 'Blank Slot #139', '000-0000', '', 'Click edit to update this slot.', 0),
(190, 'Blank Slot #140', '000-0000', '', 'Click edit to update this slot.', 0),
(191, 'Blank Slot #141', '000-0000', '', 'Click edit to update this slot.', 0),
(192, 'Blank Slot #142', '000-0000', '', 'Click edit to update this slot.', 0),
(193, 'Blank Slot #143', '000-0000', '', 'Click edit to update this slot.', 0),
(194, 'Blank Slot #144', '000-0000', '', 'Click edit to update this slot.', 0),
(195, 'Blank Slot #145', '000-0000', '', 'Click edit to update this slot.', 0),
(196, 'Blank Slot #146', '000-0000', '', 'Click edit to update this slot.', 0),
(197, 'Blank Slot #147', '000-0000', '', 'Click edit to update this slot.', 0),
(198, 'Blank Slot #148', '000-0000', '', 'Click edit to update this slot.', 0),
(199, 'Blank Slot #149', '000-0000', '', 'Click edit to update this slot.', 0),
(200, 'Blank Slot #150', '000-0000', '', 'Click edit to update this slot.', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
