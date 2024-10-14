-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gostitelj: 127.0.0.1
-- Čas nastanka: 14. okt 2024 ob 18.56
-- Različica strežnika: 10.4.32-MariaDB
-- Različica PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Zbirka podatkov: `spletnaucilnica`
--

-- --------------------------------------------------------

--
-- Struktura tabele `administrator`
--

CREATE TABLE `administrator` (
  `id_administratorja` int(11) NOT NULL,
  `ime` varchar(50) NOT NULL,
  `priimek` varchar(50) NOT NULL,
  `tel_st` varchar(20) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `kabinet` varchar(50) DEFAULT NULL,
  `geslo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabele `gradiva`
--

CREATE TABLE `gradiva` (
  `id_gradiva` int(11) NOT NULL,
  `naslov_gradiva` varchar(150) DEFAULT NULL,
  `navodilo` varchar(500) DEFAULT NULL,
  `datoteke` varchar(100) DEFAULT NULL,
  `id_ucenca` int(11) NOT NULL,
  `id_ucitelja` int(11) NOT NULL,
  `rok_oddaje` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabele `oddanenaloge`
--

CREATE TABLE `oddanenaloge` (
  `id_naloge` int(11) NOT NULL,
  `id_gradiva` int(11) NOT NULL,
  `datum_oddaje` datetime DEFAULT NULL,
  `komentarji` varchar(500) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'V obdelavi'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabele `predmet`
--

CREATE TABLE `predmet` (
  `id_predmeta` int(11) NOT NULL,
  `ime_predmeta` varchar(50) NOT NULL,
  `id_ucenca` int(11) NOT NULL,
  `id_ucitelja` int(11) NOT NULL,
  `st_ur` int(11) NOT NULL,
  `ocena` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabele `razred`
--

CREATE TABLE `razred` (
  `id_razreda` varchar(3) NOT NULL,
  `id_ucitelja` int(11) NOT NULL,
  `st_ucencev` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Odloži podatke za tabelo `razred`
--

INSERT INTO `razred` (`id_razreda`, `id_ucitelja`, `st_ucencev`) VALUES
('R4A', 1, 25);

-- --------------------------------------------------------

--
-- Struktura tabele `ucenec`
--

CREATE TABLE `ucenec` (
  `id_ucenca` int(11) NOT NULL,
  `ime` varchar(50) NOT NULL,
  `priimek` varchar(50) NOT NULL,
  `mail` varchar(50) DEFAULT NULL,
  `tel_st` varchar(20) DEFAULT NULL,
  `emso` varchar(13) NOT NULL,
  `id_razreda` varchar(3) NOT NULL,
  `geslo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Odloži podatke za tabelo `ucenec`
--

INSERT INTO `ucenec` (`id_ucenca`, `ime`, `priimek`, `mail`, `tel_st`, `emso`, `id_razreda`, `geslo`) VALUES
(1, 'Miha', 'Žvegler', 'miha.zvegler1@gmail.com', '091231223', '0908006500009', 'r4a', '$2y$10$2drptHsyNBEkTwwBY5I3GOvWvCxRGCBzF.VzVsTCUhb/Ff7vB3CMa'),
(3, 'Miha', 'Žvegler', 'miho.zvegal@gmail.com', NULL, '', 'R4A', '$2y$10$QykXMqBF.A7bBZ2zih5LzOiP2CpSEYh5Q68rpPITBffcRGN67hfHi'),
(4, 'as', 'as', 'aa@as.com', NULL, '', 'r4a', '$2y$10$6IX4Gkqq0sf9CWRCuqZZze7d48r8aX3SGGE1tUGU4N/xewtlaPiFq');

-- --------------------------------------------------------

--
-- Struktura tabele `ucenecpredmet`
--

CREATE TABLE `ucenecpredmet` (
  `id_ucenca` int(11) NOT NULL,
  `id_predmeta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabele `ucitelj`
--

CREATE TABLE `ucitelj` (
  `id_ucitelja` int(11) NOT NULL,
  `ime` varchar(50) NOT NULL,
  `priimek` varchar(50) NOT NULL,
  `tel_st` varchar(20) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `kabinet` varchar(50) NOT NULL,
  `geslo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Odloži podatke za tabelo `ucitelj`
--

INSERT INTO `ucitelj` (`id_ucitelja`, `ime`, `priimek`, `tel_st`, `mail`, `kabinet`, `geslo`) VALUES
(1, 'Marko', 'Novak', '040123456', 'marko.novak@example.com', '101', 'hashed_password_1'),
(2, 'Ana', 'Kovač', '041234567', 'ana.kovac@example.com', '102', 'hashed_password_2'),
(3, 'Mateja', 'Breznik', '051234567', 'mateja.breznik@example.com', '103', 'hashed_password_3'),
(4, 'Ivan', 'Horvat', '031123456', 'ivan.horvat@example.com', '104', 'hashed_password_4'),
(5, 'Jure', 'Mlakar', '070123456', 'jure.mlakar@example.com', '105', 'hashed_password_5');

-- --------------------------------------------------------

--
-- Struktura tabele `zgodovinaoddaj`
--

CREATE TABLE `zgodovinaoddaj` (
  `id_zgodovine` int(11) NOT NULL,
  `id_naloge` int(11) NOT NULL,
  `datum_oddaje` datetime NOT NULL,
  `komentarji` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indeksi zavrženih tabel
--

--
-- Indeksi tabele `administrator`
--
ALTER TABLE `administrator`
  ADD PRIMARY KEY (`id_administratorja`);

--
-- Indeksi tabele `gradiva`
--
ALTER TABLE `gradiva`
  ADD PRIMARY KEY (`id_gradiva`),
  ADD KEY `id_ucenca` (`id_ucenca`),
  ADD KEY `id_ucitelja` (`id_ucitelja`);

--
-- Indeksi tabele `oddanenaloge`
--
ALTER TABLE `oddanenaloge`
  ADD PRIMARY KEY (`id_naloge`),
  ADD KEY `id_gradiva` (`id_gradiva`);

--
-- Indeksi tabele `predmet`
--
ALTER TABLE `predmet`
  ADD PRIMARY KEY (`id_predmeta`),
  ADD KEY `id_ucenca` (`id_ucenca`),
  ADD KEY `id_ucitelja` (`id_ucitelja`);

--
-- Indeksi tabele `razred`
--
ALTER TABLE `razred`
  ADD PRIMARY KEY (`id_razreda`),
  ADD KEY `id_ucitelja` (`id_ucitelja`);

--
-- Indeksi tabele `ucenec`
--
ALTER TABLE `ucenec`
  ADD PRIMARY KEY (`id_ucenca`),
  ADD KEY `id_razreda` (`id_razreda`);

--
-- Indeksi tabele `ucenecpredmet`
--
ALTER TABLE `ucenecpredmet`
  ADD PRIMARY KEY (`id_ucenca`,`id_predmeta`),
  ADD KEY `id_predmeta` (`id_predmeta`);

--
-- Indeksi tabele `ucitelj`
--
ALTER TABLE `ucitelj`
  ADD PRIMARY KEY (`id_ucitelja`);

--
-- Indeksi tabele `zgodovinaoddaj`
--
ALTER TABLE `zgodovinaoddaj`
  ADD PRIMARY KEY (`id_zgodovine`),
  ADD KEY `id_naloge` (`id_naloge`);

--
-- AUTO_INCREMENT zavrženih tabel
--

--
-- AUTO_INCREMENT tabele `ucenec`
--
ALTER TABLE `ucenec`
  MODIFY `id_ucenca` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT tabele `zgodovinaoddaj`
--
ALTER TABLE `zgodovinaoddaj`
  MODIFY `id_zgodovine` int(11) NOT NULL AUTO_INCREMENT;

--
-- Omejitve tabel za povzetek stanja
--

--
-- Omejitve za tabelo `gradiva`
--
ALTER TABLE `gradiva`
  ADD CONSTRAINT `gradiva_ibfk_1` FOREIGN KEY (`id_ucenca`) REFERENCES `ucenec` (`id_ucenca`),
  ADD CONSTRAINT `gradiva_ibfk_2` FOREIGN KEY (`id_ucitelja`) REFERENCES `ucitelj` (`id_ucitelja`);

--
-- Omejitve za tabelo `oddanenaloge`
--
ALTER TABLE `oddanenaloge`
  ADD CONSTRAINT `oddanenaloge_ibfk_1` FOREIGN KEY (`id_gradiva`) REFERENCES `gradiva` (`id_gradiva`);

--
-- Omejitve za tabelo `predmet`
--
ALTER TABLE `predmet`
  ADD CONSTRAINT `predmet_ibfk_1` FOREIGN KEY (`id_ucenca`) REFERENCES `ucenec` (`id_ucenca`),
  ADD CONSTRAINT `predmet_ibfk_2` FOREIGN KEY (`id_ucitelja`) REFERENCES `ucitelj` (`id_ucitelja`);

--
-- Omejitve za tabelo `razred`
--
ALTER TABLE `razred`
  ADD CONSTRAINT `razred_ibfk_1` FOREIGN KEY (`id_ucitelja`) REFERENCES `ucitelj` (`id_ucitelja`);

--
-- Omejitve za tabelo `ucenec`
--
ALTER TABLE `ucenec`
  ADD CONSTRAINT `ucenec_ibfk_1` FOREIGN KEY (`id_razreda`) REFERENCES `razred` (`id_razreda`);

--
-- Omejitve za tabelo `ucenecpredmet`
--
ALTER TABLE `ucenecpredmet`
  ADD CONSTRAINT `ucenecpredmet_ibfk_1` FOREIGN KEY (`id_ucenca`) REFERENCES `ucenec` (`id_ucenca`),
  ADD CONSTRAINT `ucenecpredmet_ibfk_2` FOREIGN KEY (`id_predmeta`) REFERENCES `predmet` (`id_predmeta`);

--
-- Omejitve za tabelo `zgodovinaoddaj`
--
ALTER TABLE `zgodovinaoddaj`
  ADD CONSTRAINT `zgodovinaoddaj_ibfk_1` FOREIGN KEY (`id_naloge`) REFERENCES `oddanenaloge` (`id_naloge`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
