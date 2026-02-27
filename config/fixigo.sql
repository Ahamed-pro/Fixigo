-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Feb 27, 2026 at 01:02 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fixigo`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'AhamedAdmin', 'ahamedadmin@gmail.com', '$2y$10$S3TPG9viwDFoXr38bM7E0.2pcsm6V6ZdF4wjsHyogJaF7H1IIarzu', '2026-02-23 18:04:14'),
(2, 'AnasAdmin', 'anasadmin@gmail.com', '$2y$10$tG2nbjYhTLfra9WuH/FbZuru.uoUQr7Mze.adKbUmmVXvVkw0to0y', '2026-02-23 18:04:14'),
(3, 'ZumairAdmin', 'zumairadmin@gmail.com', '$2y$10$JdX0N7qEkhK9xS1JQx6gBOpPxTAdE40SCIgzS/A1y1zQa6BjfhWlK', '2026-02-23 18:04:15'),
(4, 'NadhaAdmin', 'nadhaadmin@gmail.com', '$2y$10$e4h10uuPDfv0mXSQ.6GB2.47siUphSJkiYXjYp7YcgxMmvqfyeRrO', '2026-02-23 18:04:15'),
(5, 'KiwiyashiniAdmin', 'kiwiyashiniadmin@gmail.com', '$2y$10$rG4o07NrKa/ELmjOniONHe9eN27SBT/5oq8rBpweRBdK.3it2/vZW', '2026-02-23 18:04:15');

-- --------------------------------------------------------

--
-- Table structure for table `card_payments`
--

CREATE TABLE `card_payments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `card_number` varchar(25) NOT NULL,
  `expiry` varchar(7) NOT NULL,
  `cardholder_name` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_status` varchar(20) DEFAULT 'paid',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `Name` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `card_payments`
--

INSERT INTO `card_payments` (`id`, `user_id`, `card_number`, `expiry`, `cardholder_name`, `amount`, `payment_status`, `created_at`, `Name`) VALUES
(1, 16, '2456 7885 7562 6745', '02 / 30', 'Nakash', '4999.00', 'paid', '2026-02-23 08:52:20', NULL),
(7, 27, '1234 5687 9544 1553', '02 / 30', 'Anaas', '4999.00', 'paid', '2026-02-24 15:28:49', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `chat_conversations`
--

CREATE TABLE `chat_conversations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `workshop_id` int(11) NOT NULL,
  `last_message` text DEFAULT NULL,
  `last_at` datetime DEFAULT current_timestamp(),
  `user_unread` int(11) DEFAULT 0,
  `ws_unread` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_conversations`
--

INSERT INTO `chat_conversations` (`id`, `user_id`, `workshop_id`, `last_message`, `last_at`, `user_unread`, `ws_unread`, `created_at`) VALUES
(1, 1, 5, 'it cost 2000lkr', '2026-02-27 14:32:49', 1, 0, '2026-02-27 14:31:53');

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL,
  `conversation_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `sender_type` enum('user','workshop') NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_id`, `sender_type`, `message`, `is_read`, `created_at`) VALUES
(1, 1, 1, 'user', 'hi', 1, '2026-02-27 14:31:58'),
(2, 1, 1, 'user', 'what is the coset for tyre change', 1, '2026-02-27 14:32:10'),
(3, 1, 16, 'workshop', 'it cost 2000lkr', 0, '2026-02-27 14:32:49');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `topic` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `first_name`, `last_name`, `email`, `phone`, `topic`, `message`, `is_read`, `created_at`) VALUES
(1, 'Nakash', 'Ahamed', 'nakashuzzi02@gmail.com', '+94783648783', 'Other', 'Really appreciate the website u i🤙👍', 0, '2026-02-25 22:03:20'),
(2, 'Mohamed', 'Nawfar', 'mrlezenzore@gmail.com', '+94783612345', 'Emergency SOS', 'Emergency service integration is developed well. its very helpful ❤.', 0, '2026-02-25 22:12:06');

-- --------------------------------------------------------

--
-- Table structure for table `emergency_requests`
--

CREATE TABLE `emergency_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `landmark` varchar(255) NOT NULL,
  `location` text NOT NULL,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `emergency_type` enum('accident','medical','police','other') NOT NULL,
  `status` enum('new','handled') DEFAULT 'new',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emergency_requests`
--

INSERT INTO `emergency_requests` (`id`, `user_id`, `name`, `phone`, `landmark`, `location`, `latitude`, `longitude`, `emergency_type`, `status`, `created_at`) VALUES
(1, 1, 'Ahamed', '+94 783355127', 'next to hospital', '7.302300, 80.635422', NULL, NULL, 'police', 'new', '2026-02-24 09:14:52'),
(2, 1, 'zumair', '+94 783355127', 'next to hospital', '7.302300, 80.635422', NULL, NULL, 'police', 'new', '2026-02-24 09:17:52'),
(3, 1, 'himas', '+94 783355127', 'next to hospital', 'D. S. Senanayake Veediya, Mahaiyawa, Kandy, Central Province', NULL, NULL, 'medical', 'new', '2026-02-24 09:40:31'),
(4, 1, 'Ahamed', '+94 783355127', 'next to hospital', '6.993161, 81.063889', NULL, NULL, 'medical', 'new', '2026-02-24 09:43:10'),
(5, 1, 'Final', '+94997788661', 'next to hospital', 'School Lane, කැස්බැව, Western Province', NULL, NULL, 'medical', 'new', '2026-02-24 11:11:29'),
(6, 1, 'Rimas', '+94 780000127', 'next to hospital', 'School Lane, කැස්බැව, Western Province', NULL, NULL, 'medical', 'new', '2026-02-24 11:13:24'),
(7, 1, 'Umar', '+94 888888888', 'next to hospital', 'D. S. Senanayake Veediya, Mahaiyawa, Kandy, Central Province', NULL, NULL, 'medical', 'new', '2026-02-24 12:11:47'),
(8, 1, 'Ahamed', '+94 888988888', 'next to hospital', '6.993161, 81.063889', NULL, NULL, 'medical', 'new', '2026-02-24 12:28:14'),
(9, 1, 'Nawfar', '+94 949494949', 'next to hospital', '6.993158, 81.063911', NULL, NULL, 'medical', 'new', '2026-02-24 13:51:51'),
(10, 1, 'Nakeeb', '+94 321654123', 'next to hospital', '7.302300, 80.635422', NULL, NULL, 'medical', 'new', '2026-02-24 14:01:52');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(150) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `expires_at`, `created_at`) VALUES
(6, 'nakashuzzi02@gmail.com', '034de053988ada500776e15e611c5609de35adf180ac1b725c5ed088f4cfc32a', '2026-02-26 21:06:02', '2026-02-26 20:36:02'),
(7, 'nakashuzzi02@gmail.com', 'eda4beeab9093f10ce4d12d5fde94cfbaf7f1dcc8b9599254f3eecd3d184c9fc', '2026-02-26 21:06:45', '2026-02-26 20:36:45'),
(8, 'nakashuzzi02@gmail.com', '89161514775e3c9135ec992e42c3462413cc6d4b206d93f1e69fc7af74b1df0b', '2026-02-26 21:07:48', '2026-02-26 20:37:48');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(150) NOT NULL,
  `rating` tinyint(4) NOT NULL CHECK (`rating` between 1 and 5),
  `category` varchar(80) DEFAULT 'General',
  `review_text` text NOT NULL,
  `is_approved` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `name`, `email`, `rating`, `category`, `review_text`, `is_approved`, `created_at`) VALUES
(1, 1, 'mohomed', 'mohomed@gmail.com', 5, 'Emergency SOS', 'I had to use the SOS feature late at night when my vehicle suddenly broke down on the roadside. The response was incredibly fast and professional. Within minutes of submitting the emergency request, I received confirmation that assistance was on the way.', 1, '2026-02-26 20:06:47');

-- --------------------------------------------------------

--
-- Table structure for table `service_requests`
--

CREATE TABLE `service_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(150) NOT NULL,
  `user_phone` varchar(30) NOT NULL,
  `workshop_id` int(11) NOT NULL,
  `workshop_name` varchar(150) NOT NULL,
  `service_type` varchar(100) NOT NULL,
  `location` varchar(255) NOT NULL DEFAULT '',
  `description` text DEFAULT NULL,
  `status` enum('pending','accepted','ignored') NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_requests`
--

INSERT INTO `service_requests` (`id`, `user_id`, `user_name`, `user_phone`, `workshop_id`, `workshop_name`, `service_type`, `location`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'mr Ahamed', '94', 4, 'HimasAuto', 'Electrical Issue', '6/7 Gurandhawatha, Badulla', 'Car got Smoked suddenly!', 'accepted', '2026-02-23 22:26:50', '2026-02-23 22:27:47'),
(2, 2, 'Zarook zumair', '94', 5, 'Nakashpaints', 'Tyre Change', '6/7 Gurandhawatha, Badulla', 'need a full body update!', 'accepted', '2026-02-24 22:12:59', '2026-02-24 22:13:30'),
(3, 2, 'Zarook zumair', '94', 5, 'Nakash paintss', 'Brake Service', '6/7 Gurandhawatha, Badulla', 'jghukhgv', 'ignored', '2026-02-25 14:34:06', '2026-02-25 14:35:19'),
(4, 2, 'Zarook zumair', '94', 5, 'Nakash paintss', 'Electrical Issue', '6/7 Gurandhawatha, Badulla', 'vhkghk', 'accepted', '2026-02-25 14:38:31', '2026-02-25 14:39:16'),
(5, 1, 'mr Ahamed', '94', 5, 'Nakash paints', 'Wheel Alignment', '6/7 Gurandhawatha, Badulla', 'need a update', 'accepted', '2026-02-26 13:38:41', '2026-02-26 13:39:11'),
(6, 30, 'fathima nadha', '94', 4, 'HimasAuto', 'AC Repair', '6/7 Gurandhawatha, Badulla', 'got burned', 'accepted', '2026-02-26 22:28:00', '2026-02-26 22:29:32');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `account_type` enum('user','workshop') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `phone`, `password`, `created_at`, `account_type`) VALUES
(1, 'mr Ahamed', 'ahamed@gmail.com', '+94783648784', '$2y$10$pd9gxF1WX9o.4X7hxMgOmOUyHdcCX6y7pG2QuI8/bJCmK4/yw9UIW', '2026-02-22 10:44:20', 'user'),
(2, 'Zarook zumair', 'zumair@gmail.com', '+94783612345', '$2y$10$c8A1.MZWJg2EWr7WEe7y7upUAbj8Q.YVQwU3ti2NY50FXRKGKCj9S', '2026-02-22 15:30:39', 'user'),
(3, 'Anas Nawfar', 'anas@gmail.com', '+9478789456', '$2y$10$813oJdoi2jb6zsdZGa57quyRzJaXbsCjN9niUbbzDMb/tfOiXcw0C', '2026-02-22 15:50:16', 'user'),
(4, 'Kamal Perera', 'Kamal1@gmail.com', '+94 774536561', '$2y$10$/ogJGQJeov1rMVtxNvwazetc9KqXcBoyGNq4PRfYUzJ732ClgEXLm', '2026-02-22 16:52:15', 'user'),
(5, 'Test Test', 'Test2@gmail.com', '+94 4445689103', '$2y$10$JvrbNxWfpTQR3F969QFSbuwcLvG/aXggONcbG5C3U.pObAkc.zBZq', '2026-02-22 23:40:30', 'user'),
(7, 'Sineth Cabs', 'sineth@gmail.com', '+94 798645361', '$2y$10$wYA3XImGhlP4ZjeA8PfUTeTeh56CnVsokwg.n.lxWrJezxZXXS2cW', '2026-02-23 00:08:40', 'workshop'),
(9, 'Master Bro', 'master@gmail.com', '+94 4445557', '$2y$10$kQLQSLyUdxY1i2AvDwzUE.Qjn7DzVHke/cjUEyl1yInb5Si76R1QG', '2026-02-23 01:43:15', 'workshop'),
(11, 'Rimas Rock', 'rimas@gmail.com', '+94112233445', '$2y$10$Q4H/trM6efPxc0dPoNkFNOFwv1XBqRRlVLbfIK2.1GglwslX0GaYi', '2026-02-23 02:51:03', 'workshop'),
(12, 'Himas Bro', 'himas@gmail.com', '+94 332211445', '$2y$10$AO/m6aeMZzG9NJDHJoxCBeSwl0casX8aMb8nHHdQg48RX6BfZ1Fka', '2026-02-23 03:05:53', 'workshop'),
(16, 'Nakash Ahamed', 'nakash@gmail.com', '+94 999999897', '$2y$10$HMnE.LleWFAkBwph1ak.MOsWquhxM/YyUqFN41XkaIEx/byWlRPve', '2026-02-23 08:52:20', 'workshop'),
(18, 'Riyas Mohamed', 'riyas@gmail.com', '+94 778899445', '$2y$10$9f4zcnkO3SpKh588zKzjHOIqpEub2DWnaYpYvqRcYCU56wFe3cs46', '2026-02-24 06:42:38', 'user'),
(27, 'Anaas Nawwfar', 'mohomed3012@gmail.com', '+94 000000001', '$2y$10$oWCt1FY2l0mcV2i6E3eRcuDCq.Z3SkgtAD3MjenFoBZBWAJumkmPa', '2026-02-24 15:28:49', 'workshop'),
(28, 'Test Test', 'nakashuzzi02@gmail.com', '+945555555555', '$2y$10$l7X6TmyBhpoMDLCIHclTe.GM8Z.jXsWulSFN6JNSXjauvP42QY14i', '2026-02-25 17:14:57', 'user'),
(30, 'fathima nadha', 'fathimanadhar@gmail.com', '+94 120000000', '$2y$10$jJEJQvNGhm2dw1UkTeBRUuEsUfU1Z1uUmOo60oBhaHJtKpMDcQqPe', '2026-02-26 16:56:05', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `workshops`
--

CREATE TABLE `workshops` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(120) NOT NULL,
  `email` varchar(120) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `workshop_name` varchar(150) NOT NULL,
  `business_reg` varchar(60) DEFAULT NULL,
  `district` varchar(60) NOT NULL,
  `specialisation` varchar(80) NOT NULL,
  `address` varchar(255) NOT NULL,
  `payment_status` enum('pending','paid') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workshops`
--

INSERT INTO `workshops` (`id`, `user_id`, `full_name`, `email`, `contact_number`, `workshop_name`, `business_reg`, `district`, `specialisation`, `address`, `payment_status`, `created_at`) VALUES
(3, 11, '', '', '', 'RimasAC', 'Br/1234/pu', 'Kandy', 'AC Repair', '6/7 Gurandhawatha, Badulla', 'paid', '2026-02-23 08:21:03'),
(4, 12, '', '', '', 'HimasAuto', 'Br/1234/pk', 'Moneragala', 'Electrical / Diagnostics', '6/7 Gurandhawatha, Badulla', 'paid', '2026-02-23 08:35:53'),
(5, 16, '', '', '', 'Nakash Art Works', 'Br/1234/pi', 'Badulla', 'Elatrical', '6/7 Gurandhawatha, Badulla', 'paid', '2026-02-23 14:22:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `card_payments`
--
ALTER TABLE `card_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `chat_conversations`
--
ALTER TABLE `chat_conversations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_chat` (`user_id`,`workshop_id`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_conv` (`conversation_id`),
  ADD KEY `idx_sender` (`sender_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emergency_requests`
--
ALTER TABLE `emergency_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_workshop_id` (`workshop_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `workshops`
--
ALTER TABLE `workshops`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `card_payments`
--
ALTER TABLE `card_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `chat_conversations`
--
ALTER TABLE `chat_conversations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `emergency_requests`
--
ALTER TABLE `emergency_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `service_requests`
--
ALTER TABLE `service_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `workshops`
--
ALTER TABLE `workshops`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `card_payments`
--
ALTER TABLE `card_payments`
  ADD CONSTRAINT `card_payments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `workshops`
--
ALTER TABLE `workshops`
  ADD CONSTRAINT `workshops_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
