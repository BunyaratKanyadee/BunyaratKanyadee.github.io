-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 09. Mai 2025 um 09:34
-- Server-Version: 10.4.32-MariaDB
-- PHP-Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `rehaklinik0`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `benutzer` (Login-Accounts)
--

CREATE TABLE `benutzer` (
  `benutzer_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `patient`
--

CREATE TABLE `patient` (
  `patient_id` int(11) NOT NULL,
  `vorname` varchar(50) DEFAULT NULL,
  `nachname` varchar(50) DEFAULT NULL,
  `geburtsdatum` date DEFAULT NULL,
  `versichertennummer` varchar(20) DEFAULT NULL,
  `kontaktinfo` text DEFAULT NULL,
  `therapieart` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `termin`
--

CREATE TABLE `termin` (
  `termin_id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `therapeut_id` int(11) DEFAULT NULL,
  `therapieart_id` int(11) DEFAULT NULL,
  `datum` date DEFAULT NULL,
  `uhrzeit_start` time DEFAULT NULL,
  `uhrzeit_ende` time DEFAULT NULL,
  `raum` varchar(20) DEFAULT NULL,
  `notizen` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `therapeut`
--

CREATE TABLE `therapeut` (
  `therapeut_id` int(11) NOT NULL,
  `vorname` varchar(50) DEFAULT NULL,
  `nachname` varchar(50) DEFAULT NULL,
  `fachgebiet` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `therapieart`
--

CREATE TABLE `therapieart` (
  `therapieart_id` int(11) NOT NULL,
  `bezeichnung` varchar(100) DEFAULT NULL,
  `beschreibung` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `benutzer`
--
ALTER TABLE `benutzer`
  ADD PRIMARY KEY (`benutzer_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indizes für die Tabelle `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`patient_id`);

--
-- Indizes für die Tabelle `termin`
--
ALTER TABLE `termin`
  ADD PRIMARY KEY (`termin_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `therapeut_id` (`therapeut_id`),
  ADD KEY `therapieart_id` (`therapieart_id`);

--
-- Indizes für die Tabelle `therapeut`
--
ALTER TABLE `therapeut`
  ADD PRIMARY KEY (`therapeut_id`);

--
-- Indizes für die Tabelle `therapieart`
--
ALTER TABLE `therapieart`
  ADD PRIMARY KEY (`therapieart_id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `benutzer`
--
ALTER TABLE `benutzer`
  MODIFY `benutzer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `patient`
--
ALTER TABLE `patient`
  MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `termin`
--
ALTER TABLE `termin`
  MODIFY `termin_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `therapeut`
--
ALTER TABLE `therapeut`
  MODIFY `therapeut_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `therapieart`
--
ALTER TABLE `therapieart`
  MODIFY `therapieart_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `termin`
--
ALTER TABLE `termin`
  ADD CONSTRAINT `termin_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`patient_id`),
  ADD CONSTRAINT `termin_ibfk_2` FOREIGN KEY (`therapeut_id`) REFERENCES `therapeut` (`therapeut_id`),
  ADD CONSTRAINT `termin_ibfk_3` FOREIGN KEY (`therapieart_id`) REFERENCES `therapieart` (`therapieart_id`);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
