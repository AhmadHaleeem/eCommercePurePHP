-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 18, 2018 at 04:17 PM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text,
  `Parent` int(11) NOT NULL,
  `Ordering` int(11) NOT NULL,
  `Visibility` tinyint(4) NOT NULL DEFAULT '0',
  `Allow_Comment` tinyint(4) NOT NULL DEFAULT '0',
  `Allow_Ads` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`ID`, `Name`, `Description`, `Parent`, `Ordering`, `Visibility`, `Allow_Comment`, `Allow_Ads`) VALUES
(7, 'Hand Made', 'Hand Made Items', 0, 1, 1, 1, 1),
(8, 'Computers ', 'Computers Item', 0, 2, 0, 0, 0),
(9, 'Cell Phones', 'Cell Phones Items', 0, 3, 1, 1, 1),
(10, 'Clothes', 'Clothes And Fashion', 0, 4, 0, 0, 0),
(11, 'Tools', 'Home Tools', 0, 5, 0, 0, 1),
(12, 'Nokia', 'This is a Good Phone ', 9, 6, 0, 1, 1),
(13, 'Hammer', 'Hammer Description', 11, 3, 0, 1, 1),
(15, 'Good Dress', 'Adidas Dress', 7, 4, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `c_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `status` tinyint(4) NOT NULL,
  `comment_date` date NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`c_id`, `comment`, `status`, `comment_date`, `item_id`, `user_id`) VALUES
(1, 'Thanky For Every Thing', 1, '2017-08-07', 4, 19),
(2, 'This is Very Good Mouse', 1, '2017-08-14', 5, 18),
(3, 'This is Very Good Mobile', 1, '2017-08-23', 2, 18),
(4, 'very Good Speakers', 1, '2017-08-15', 3, 18),
(5, 'Hello Every one Who Like This Offer', 1, '2017-08-12', 6, 21),
(6, 'This is Gaming Very Good', 1, '2017-08-12', 6, 21),
(7, 'This is Comments For Newtwork Canbel', 1, '2017-08-12', 4, 19),
(9, 'Helo ', 1, '2017-08-12', 5, 19),
(11, 'Nice playstation4', 1, '2017-08-16', 7, 13),
(12, 'This is From Canada And I Think That The jacke is Very Beautiful..', 0, '2017-08-13', 9, 21),
(13, 'Good Mobile', 1, '2017-08-14', 2, 7),
(14, 'http://', 0, '2017-08-14', 9, 7),
(16, 'sdfsdfsdfsdfsdfsdfs', 1, '2017-09-09', 12, 21);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `Item_ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text CHARACTER SET utf8mb4 NOT NULL,
  `Price` varchar(255) NOT NULL,
  `Add_Date` date NOT NULL,
  `Country_Made` varchar(255) NOT NULL,
  `Image` varchar(255) NOT NULL,
  `Status` varchar(255) NOT NULL,
  `Rating` smallint(6) NOT NULL,
  `Cat_ID` int(11) NOT NULL,
  `Member_ID` int(11) NOT NULL,
  `Approve` tinyint(4) NOT NULL DEFAULT '0',
  `Tags` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`Item_ID`, `Name`, `Description`, `Price`, `Add_Date`, `Country_Made`, `Image`, `Status`, `Rating`, `Cat_ID`, `Member_ID`, `Approve`, `Tags`) VALUES
(1, 'Microphone', 'Very Good Microphone', '7', '2017-08-07', 'China', '', '1', 0, 8, 18, 1, ''),
(2, 'Ipone 7plus', 'Very Good Mobile ', '700', '2017-08-07', 'USA', '', '1', 0, 9, 18, 1, ''),
(3, 'Speakers', 'Very Good Speakers', '30', '2017-08-07', 'UK', '', '1', 0, 8, 13, 1, ''),
(4, 'Network Cable', 'Cat 9 Network Cable', '100', '2017-08-07', 'usa', '', '1', 0, 8, 19, 1, ''),
(5, 'Magic Mouse ', 'Apple Magic Mouse ', '150', '2017-08-07', 'USA', '', '1', 0, 8, 19, 1, ''),
(6, 'Gaming', 'This is Middle Description', '100', '2017-08-11', 'USA', '', '1', 0, 8, 21, 1, ''),
(7, 'Play Station', 'This is ps4 Game', '400', '2017-08-11', 'UK', '', '2', 0, 11, 21, 1, ''),
(8, 'Battlefield 1', 'Beautiful Game', '7', '2017-08-13', 'USA', '', '1', 0, 8, 18, 1, ''),
(9, 'Good Jacket', 'This is From Winter Jacket', '120', '2017-08-13', 'Canada', '', '2', 0, 10, 21, 1, ''),
(10, 'Counter Strike GO', 'This is a Good Game ', '4', '2017-08-14', 'Syria', '', '1', 0, 8, 21, 1, 'Action, PLAY, Go\r\n'),
(11, 'Dead Poll', 'This is A Good Movie', '10', '2017-08-15', 'USA', '', '1', 0, 8, 15, 1, 'Johon, Action, Comedy'),
(12, 'Newboy 3', 'Newboy Departement', '22', '2017-08-15', 'Qatar', '', '2', 0, 11, 21, 1, 'OMEGA, GAME, PLAY'),
(13, 'Omega', 'Omega 4 Description', '2', '2017-08-15', 'Germany', '', '4', 0, 12, 7, 1, 'Omega, play, Cosofo');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(255) CHARACTER SET ucs2 NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(255) CHARACTER SET ucs2 NOT NULL,
  `FullName` varchar(255) CHARACTER SET ucs2 NOT NULL,
  `GroupID` int(11) NOT NULL DEFAULT '0',
  `TrustStatus` int(11) NOT NULL DEFAULT '0',
  `RegStatus` int(11) NOT NULL DEFAULT '0',
  `Datee` date NOT NULL,
  `Avatar` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Password`, `Email`, `FullName`, `GroupID`, `TrustStatus`, `RegStatus`, `Datee`, `Avatar`) VALUES
(7, 'Assaf', '601f1889667efaebb33b8c12572835da3f027f78', 'Assaf@gmail.com', 'Assaf shaleesh', 1, 0, 1, '2017-07-26', ''),
(11, 'Doaa', '601f1889667efaebb33b8c12572835da3f027f78', 'Doaa@gmail.com', 'Doaa Azzam', 0, 0, 0, '2017-07-27', ''),
(13, 'Hind', '2eb6c0daf687e773b875e5506f69472d765942a4', 'Hind@gmail.com', 'Hind Rustum', 0, 0, 1, '2017-07-27', ''),
(15, 'Mahmoud', 'c192bf73adb8218f80391fbbd9a760119260e394', 'Mahmoud@gmail.com', 'Mahmoud Sallam', 0, 0, 1, '2017-07-28', ''),
(18, 'Ahmad', '601f1889667efaebb33b8c12572835da3f027f78', 'ahmad@gmail.com', 'Ahmad Khaled', 1, 0, 1, '2017-08-08', ''),
(19, 'Aghyad', '601f1889667efaebb33b8c12572835da3f027f78', 'Aghyad@gmail.com', 'Aghyad AboAldhoor', 0, 0, 1, '2017-08-07', ''),
(21, 'Louay', '601f1889667efaebb33b8c12572835da3f027f78', 'Louay@gmail.com', 'Louay Aldoghri', 0, 0, 1, '2017-08-09', ''),
(22, 'Zahraa', '601f1889667efaebb33b8c12572835da3f027f78', 'Zahraa@gmail.com', 'Zahraa Alrefaye', 0, 0, 1, '2017-08-16', '47210693_Female 5.png'),
(23, 'Suzan', '601f1889667efaebb33b8c12572835da3f027f78', 'Suzan@gmail.com', 'Suzan Alhayek ', 0, 0, 1, '2017-08-16', '86764527_Female.png'),
(24, 'Khattab', 'b02c511a7e80b3a7481d6a6ab22939bb706e5570', 'Khattab@gmail.com', 'Khattab Alzoubi', 0, 0, 1, '2017-08-16', '86944580_Male4.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`c_id`),
  ADD KEY `items_comment` (`item_id`),
  ADD KEY `user_comment` (`user_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`Item_ID`),
  ADD KEY `member_1` (`Member_ID`),
  ADD KEY `cat_1` (`Cat_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `Item_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `items_comment` FOREIGN KEY (`item_id`) REFERENCES `items` (`Item_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_comment` FOREIGN KEY (`user_id`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `cat_1` FOREIGN KEY (`Cat_ID`) REFERENCES `categories` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `member_1` FOREIGN KEY (`Member_ID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
