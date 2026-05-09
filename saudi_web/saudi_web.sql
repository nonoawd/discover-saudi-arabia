-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: May 07, 2026 at 08:35 PM
-- Server version: 8.0.44
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `saudi_web`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- --------------------------------------------------------

--
-- Table structure for table `places`
--

CREATE TABLE `places` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `region` varchar(100) NOT NULL,
  `category` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `activities` text,
  `landmarks` text,
  `main_image` varchar(255) DEFAULT NULL,
  `gallery_image1` varchar(255) DEFAULT NULL,
  `gallery_image2` varchar(255) DEFAULT NULL,
  `gallery_image3` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `places`
--

INSERT INTO `places` (`id`, `name`, `region`, `category`, `description`, `activities`, `landmarks`, `main_image`, `gallery_image1`, `gallery_image2`, `gallery_image3`, `created_at`) VALUES
(1, 'الرياض', 'الرياض', 'وسطى', 'الرياض هي عاصمة المملكة العربية السعودية ومركزها الاقتصادي. تتميز بمزيج رائع من التراث العريق والحداثة المتطورة، وتضم أبرز المعالم التجارية والتاريخية.', 'التسوق في المتاجر العالمية, زيارة المناطق التاريخية, الاستمتاع بالحياة الليلية', 'برج المملكة, حي طريف, بوليفارد الرياض', '69fcd4e6b9734.jpeg', '69fcd4e6b99fa.jpg', '69fcd4e6b9a77.jpg', '69fcd4e6b9ae1.jpg', '2026-05-06 20:00:37'),
(2, 'مكة المكرمة', 'مكة المكرمة', 'غربية', 'مكة المكرمة هي أقدس بقاع الأرض، حيث يتوافد إليها ملايين المسلمين من حول العالم لأداء فريضتي الحج والعمرة. تحتضن المسجد الحرام والكعبة المشرفة.', 'أداء العمرة والحج, زيارة المسجد الحرام, التعرف على التاريخ الإسلامي', 'المسجد الحرام, الكعبة المشرفة, برج الساعة', '69fcd6c916e17.jpg', '69fcd6c916f26.jpeg', '69fcd6c916fa0.jpeg', '69fcd6c91709b.jpeg', '2026-05-06 20:00:37'),
(3, 'العُلا', 'العُلا', 'غربية', 'العُلا مدينة أثرية ساحرة في شمال غرب المملكة، تشتهر بتضاريسها الصخرية الفريدة ومواقعها الأثرية القديمة كمدائن صالح.', 'مدائن صالح, جولات الصحراء, الاستمتاع بالطبيعة الصحراوية', 'مدائن صالح, جبل الفيل, العُلا القديمة', '69fcd73b98305.jpg', '69fcd73b98458.jpeg', '69fcd73b98539.jpg', '69fcd73b985dc.jpg', '2026-05-06 20:00:37'),
(4, 'الخبر', 'الخبر', 'شرقية', 'الخبر مدينة ساحلية حديثة تقع على ساحل الخليج العربي، تتميز بمراكز التسوق الحديثة والمطاعم المتنوعة.', 'المشي على الشاطئ, ركوب الدراجات, الاستمتاع بالمأكولات البحرية', 'كورنيش الخبر, مركز الملك عبدالعزيز (إثراء), مركز الأمير سلطان للعلوم والتقنية', '69fcd7dc99029.jpeg', '69fcd7dc9915c.jpg', '69fcd7dc9923b.jpg', '69fcd7dc99348.jpg', '2026-05-06 20:00:37'),
(5, 'أبها', 'أبها', 'جنوبية', 'أبها تقع في منطقة عسير الجبلية، تشتهر بطبيعتها الخلابة ومناخها المعتدل.', 'التمتع بالمناظر الخضراء, طبيعة الجنوب الباردة, التنزه في الجبال', 'تلفريك أبها, جبل السودة, ممشى الضباب', '69fcd8c8bd40b.webp', '69fcd8c8bd575.jpeg', '69fcd8c8bd669.jpg', '69fcd8c8bd72d.jpg', '2026-05-06 20:00:37'),
(6, 'تبوك', 'تبوك', 'شمالية', 'تبوك مدينة في شمال غرب المملكة، تضم مواقع أثرية مهمة وطبيعة متنوعة تشمل الجبال والشواطئ والصحراء.', 'زيارة المواقع التاريخية, المناظر الطبيعية الجبلية, الرياضات البحرية', 'قلعة تبوك, شاطئ شرما, مسجد التوبة', '69fcd98178e07.jpeg', '69fcd98179130.jpg', '69fcd981792ea.jpeg', '69fcd98179374.jpg', '2026-05-06 20:00:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `places`
--
ALTER TABLE `places`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `places`
--
ALTER TABLE `places`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
