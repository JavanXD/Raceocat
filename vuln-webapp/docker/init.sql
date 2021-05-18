CREATE DATABASE IF NOT EXISTS vulnwebapp;
USE vulnwebapp;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET GLOBAL time_zone = 'Europe/Berlin';

CREATE TABLE `bank` (
  `accountID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `balance` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `bank` (`accountID`, `userID`, `balance`) VALUES
(1, 1, 2500),
(2, 2, 600);

CREATE TABLE `likes` (
  `likeID` int(11) NOT NULL,
  `postingID` int(11) NOT NULL,
  `userID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `likes` (`likeID`, `postingID`, `userID`) VALUES
(1, 1, 2),
(2, 1, 1);

CREATE TABLE `logins` (
  `loginID` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `code` smallint(4) DEFAULT NULL,
  `success` tinyint(1) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `logins` (`loginID`, `email`, `code`, `success`, `timestamp`) VALUES
(1, 'user2@example.org', 1234, NULL, '2021-05-18 01:30:05');

CREATE TABLE `postings` (
  `postingID` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `text` varchar(255) NOT NULL DEFAULT '',
  `userID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `postings` (`postingID`, `timestamp`, `text`, `userID`) VALUES
(1, '2021-05-18 01:28:19', 'My first twitter tweet! :-)', 1);

CREATE TABLE `user` (
  `userID` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `code` smallint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `user` (`userID`, `email`, `code`) VALUES
(1, 'raceme@example.org', 22),
(2, 'user2@example.org', 12);

ALTER TABLE `bank`
  ADD PRIMARY KEY (`accountID`);

ALTER TABLE `likes`
  ADD PRIMARY KEY (`likeID`);

ALTER TABLE `logins`
  ADD PRIMARY KEY (`loginID`);

ALTER TABLE `postings`
  ADD PRIMARY KEY (`postingID`);

ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`);

ALTER TABLE `bank`
  MODIFY `accountID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `likes`
  MODIFY `likeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `logins`
  MODIFY `loginID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `postings`
  MODIFY `postingID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;
