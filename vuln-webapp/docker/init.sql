CREATE DATABASE IF NOT EXISTS vulnwebapp;
USE vulnwebapp;-
- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: mariadb
-- Erstellungszeit: 18. Mai 2021 um 01:33
-- Server-Version: 10.5.10-MariaDB-1:10.5.10+maria~focal
-- PHP-Version: 7.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Datenbank: `vulnwebapp`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bank`
--

CREATE TABLE `bank` (
  `accountID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `balance` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `bank`
--

INSERT INTO `bank` (`accountID`, `userID`, `balance`) VALUES
(1, 1, 2500),
(2, 2, 600);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `likes`
--

CREATE TABLE `likes` (
  `likeID` int(11) NOT NULL,
  `postingID` int(11) NOT NULL,
  `userID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `likes`
--

INSERT INTO `likes` (`likeID`, `postingID`, `userID`) VALUES
(1, 1, 2),
(2, 1, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `logins`
--

CREATE TABLE `logins` (
  `loginID` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `code` smallint(4) DEFAULT NULL,
  `success` tinyint(1) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `logins`
--

INSERT INTO `logins` (`loginID`, `email`, `code`, `success`, `timestamp`) VALUES
(1, 'user2@example.org', 1234, NULL, '2021-05-18 01:30:05');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `postings`
--

CREATE TABLE `postings` (
  `postingID` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `text` varchar(255) NOT NULL DEFAULT '',
  `userID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `postings`
--

INSERT INTO `postings` (`postingID`, `timestamp`, `text`, `userID`) VALUES
(1, '2021-05-18 01:28:19', 'My first twitter tweet! :-)', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `userID` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `code` smallint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`userID`, `email`, `code`) VALUES
(1, 'raceme@example.org', 22),
(2, 'user2@example.org', 12);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `bank`
--
ALTER TABLE `bank`
  ADD PRIMARY KEY (`accountID`);

--
-- Indizes für die Tabelle `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`likeID`);

--
-- Indizes für die Tabelle `logins`
--
ALTER TABLE `logins`
  ADD PRIMARY KEY (`loginID`);

--
-- Indizes für die Tabelle `postings`
--
ALTER TABLE `postings`
  ADD PRIMARY KEY (`postingID`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `bank`
--
ALTER TABLE `bank`
  MODIFY `accountID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `likes`
--
ALTER TABLE `likes`
  MODIFY `likeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `logins`
--
ALTER TABLE `logins`
  MODIFY `loginID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `postings`
--
ALTER TABLE `postings`
  MODIFY `postingID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;
