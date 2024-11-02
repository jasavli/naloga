-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gostitelj: 127.0.0.1
-- Čas nastanka: 02. nov 2024 ob 19.43
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
-- Zbirka podatkov: `solski_sistem`
--

-- --------------------------------------------------------

--
-- Struktura tabele `gradiva`
--

CREATE TABLE `gradiva` (
  `ID_gradiva` int(11) NOT NULL,
  `ID_predmeta` int(11) NOT NULL,
  `ID_ucitelja` int(11) NOT NULL,
  `naslov_gradiva` varchar(255) NOT NULL,
  `pot_do_datoteke` varchar(255) NOT NULL,
  `datum_objave` datetime DEFAULT current_timestamp(),
  `opis` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Odloži podatke za tabelo `gradiva`
--

INSERT INTO `gradiva` (`ID_gradiva`, `ID_predmeta`, `ID_ucitelja`, `naslov_gradiva`, `pot_do_datoteke`, `datum_objave`, `opis`) VALUES
(9, 1, 2, 'rešujte naloge 1', 'uploads/materials/Posnetek zaslona 2024-10-19 213923.png', '2024-11-02 14:47:10', ''),
(10, 1, 2, 'rešujte naloge 2', 'uploads/materials/Posnetek zaslona 2024-10-17 143649.png', '2024-11-02 14:47:23', 'rešite vse'),
(11, 1, 2, 'naloge vis', 'uploads/materials/20241010_164527.jpg', '2024-11-02 18:29:43', 'halo'),
(18, 3, 2, 'ay', 'uploads/materials/logo.png', '2024-11-02 18:40:15', NULL),
(19, 3, 2, 'ay', 'uploads/materials/logo.png', '2024-11-02 18:40:34', NULL);

-- --------------------------------------------------------

--
-- Struktura tabele `komentarji_gradiva`
--

CREATE TABLE `komentarji_gradiva` (
  `ID_komentarja` int(11) NOT NULL,
  `ID_gradiva` int(11) NOT NULL,
  `ID_avtorja` int(11) NOT NULL,
  `vsebina` text NOT NULL,
  `datum_komentarja` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabele `komentarji_naloge`
--

CREATE TABLE `komentarji_naloge` (
  `ID_komentarja` int(11) NOT NULL,
  `ID_naloge_predmet` int(11) NOT NULL,
  `ID_avtorja` int(11) NOT NULL,
  `vsebina` text NOT NULL,
  `datum_komentarja` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Odloži podatke za tabelo `komentarji_naloge`
--

INSERT INTO `komentarji_naloge` (`ID_komentarja`, `ID_naloge_predmet`, `ID_avtorja`, `vsebina`, `datum_komentarja`) VALUES
(1, 2, 6, 'Hello', '2024-10-28 14:48:09'),
(2, 1, 6, 'skret', '2024-10-28 14:49:30'),
(3, 3, 6, 'Ne vidim datoteke, kjer so naloge.', '2024-10-30 12:18:30'),
(4, 4, 6, 'hwj', '2024-11-01 14:14:55'),
(5, 5, 6, 'Spremenite rok oddaje.\\r\\n', '2024-11-01 14:38:26'),
(6, 5, 2, 'Seveda', '2024-11-01 14:38:45'),
(7, 5, 2, 'Urejeno', '2024-11-02 12:19:51'),
(8, 5, 2, 'Kako ste?', '2024-11-02 12:20:17'),
(9, 7, 2, 'asdf\\r\\n\\r\\n\\r\\n', '2024-11-02 18:57:52'),
(10, 1, 2, 'sydv\\r\\n\\r\\n\\r\\n', '2024-11-02 19:04:07'),
(11, 8, 2, 'asd ', '2024-11-02 19:07:58');

-- --------------------------------------------------------

--
-- Struktura tabele `naloge`
--

CREATE TABLE `naloge` (
  `ID_naloge` int(11) NOT NULL,
  `ID_naloge_predmet` int(11) NOT NULL,
  `ID_predmeta` int(11) NOT NULL,
  `ID_ucenca` int(11) NOT NULL,
  `naslov_naloge` varchar(255) NOT NULL,
  `pot_do_datoteke` varchar(255) NOT NULL,
  `datum_oddaje` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Odloži podatke za tabelo `naloge`
--

INSERT INTO `naloge` (`ID_naloge`, `ID_naloge_predmet`, `ID_predmeta`, `ID_ucenca`, `naslov_naloge`, `pot_do_datoteke`, `datum_oddaje`) VALUES
(1, 0, 1, 4, 'Reševanje enačb', 'uploads/Zupan Luka – Reševanje enačb.pdf', '2024-10-28 10:26:32'),
(2, 0, 3, 4, 'Sile in gibanje', 'uploads/Zupan Luka – Sile in gibanje.pdf', '2024-10-28 10:26:32'),
(3, 0, 2, 5, 'Analiza pesmi', 'uploads/Petek Nina – Analiza pesmi.pdf', '2024-10-28 10:26:32'),
(4, 0, 4, 6, 'Kemijske reakcije', 'uploads/Petek Nina – Kemijske reakcije.pdf', '2024-10-28 10:26:32'),
(5, 0, 5, 4, 'Rimljani na naših tleh', 'uploads/Zupan Luka – Rimljani na naših tleh.pdf', '2024-10-28 10:26:32'),
(6, 1, 1, 6, 'Naloga iz algebraičnih enačb', 'uploads/assignments/Žvegler Miha – Naloga iz algebraičnih enačb.pdf', '2024-11-01 12:37:55'),
(7, 2, 2, 6, 'Esej o Prešernu', 'uploads/assignments/Žvegler Miha – Esej o Prešernu.pdf', '2024-10-30 12:13:17'),
(8, 3, 1, 6, 'naloge vis', 'uploads/assignments/Žvegler Miha – naloge vis.pdf', '2024-11-01 20:52:08'),
(9, 4, 1, 6, 'naloge vis 2', 'uploads/assignments/Žvegler Miha – naloge vis 2.zip', '2024-11-02 15:00:57'),
(10, 5, 1, 6, 'reši', 'uploads/assignments/Žvegler Miha – reši.pdf', '2024-11-01 14:25:37'),
(11, 6, 3, 6, 'naloge vis 3', 'uploads/assignments/Žvegler Miha – naloge vis 3.pdf', '2024-11-02 15:54:16');

-- --------------------------------------------------------

--
-- Struktura tabele `naloge_predmet`
--

CREATE TABLE `naloge_predmet` (
  `ID_naloge_predmet` int(11) NOT NULL,
  `ID_predmeta` int(11) NOT NULL,
  `naslov_naloge` varchar(255) NOT NULL,
  `opis` text DEFAULT NULL,
  `datum_objave` datetime DEFAULT current_timestamp(),
  `rok_oddaje` datetime DEFAULT NULL,
  `pot_do_datoteke` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Odloži podatke za tabelo `naloge_predmet`
--

INSERT INTO `naloge_predmet` (`ID_naloge_predmet`, `ID_predmeta`, `naslov_naloge`, `opis`, `datum_objave`, `rok_oddaje`, `pot_do_datoteke`) VALUES
(1, 1, 'Naloga iz algebraičnih enačb', 'Rešite naslednje enačbe...', '2024-10-28 12:59:25', '2024-12-31 23:59:59', NULL),
(2, 2, 'Esej o Prešernu', 'Napišite esej o življenju in delu Franceta Prešerna...', '2024-10-28 12:59:25', '2024-11-30 23:59:59', NULL),
(3, 1, 'naloge vis', 'Rešite naloge in jih oddaje v .pdf obliki.', '2024-10-30 12:16:58', '2024-11-14 12:16:00', NULL),
(4, 1, 'naloge vis 2', 'rešite in naložite v .pdf', '2024-11-01 14:07:14', '2024-11-29 14:05:00', 'uploads/assignments/Naloge VIS II.pdf'),
(5, 1, 'reši', 'reši in oddaj', '2024-11-01 14:25:24', '2024-10-31 14:25:00', 'uploads/assignments/Naloge VIS II.pdf'),
(7, 1, 'naloge vis', '\\r\\nopus\\r\\n', '2024-11-02 18:57:34', '2024-11-29 18:57:00', 'uploads/assignments/20241011_191652.jpg'),
(8, 1, 'as', 'fasf\\r\\n\\r\\n\\r\\n', '2024-11-02 19:02:06', '2024-12-06 19:01:00', 'uploads/assignments/20241011_191558.jpg');

-- --------------------------------------------------------

--
-- Struktura tabele `predmeti`
--

CREATE TABLE `predmeti` (
  `ID_predmeta` int(11) NOT NULL,
  `ime_predmeta` varchar(100) NOT NULL,
  `opis_predmeta` text DEFAULT NULL,
  `vpisni_kljuc` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Odloži podatke za tabelo `predmeti`
--

INSERT INTO `predmeti` (`ID_predmeta`, `ime_predmeta`, `opis_predmeta`, `vpisni_kljuc`) VALUES
(1, 'Matematika', 'Osnovni in napredni koncepti matematike.', 'mat1'),
(2, 'Slovenščina', 'Učenje slovenskega jezika in književnosti.', NULL),
(3, 'Fizika', 'Osnove fizikalnih zakonitosti.', 'fizika'),
(4, 'Kemija', 'Spoznavanje kemijskih elementov in reakcij.', NULL),
(5, 'Zgodovina', 'Pregled svetovne in slovenske zgodovine.', NULL),
(6, 'SMV', 'Stroka moderne vsebine', 'smv_VPIS');

-- --------------------------------------------------------

--
-- Struktura tabele `predmeti_razredi`
--

CREATE TABLE `predmeti_razredi` (
  `ID_predmeta` int(11) NOT NULL,
  `ID_razreda` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabele `razredi`
--

CREATE TABLE `razredi` (
  `ID_razreda` int(11) NOT NULL,
  `ime_razreda` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Odloži podatke za tabelo `razredi`
--

INSERT INTO `razredi` (`ID_razreda`, `ime_razreda`) VALUES
(6, 'R1A'),
(7, 'R1B'),
(8, 'R2A'),
(9, 'R2B'),
(10, 'R3A'),
(11, 'R3B'),
(12, 'R4A'),
(13, 'R4B');

-- --------------------------------------------------------

--
-- Struktura tabele `ucenci_predmeti`
--

CREATE TABLE `ucenci_predmeti` (
  `ID_ucenca` int(11) NOT NULL,
  `ID_predmeta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Odloži podatke za tabelo `ucenci_predmeti`
--

INSERT INTO `ucenci_predmeti` (`ID_ucenca`, `ID_predmeta`) VALUES
(4, 1),
(4, 5),
(5, 1),
(5, 2),
(5, 4),
(6, 1),
(6, 3),
(6, 4),
(6, 6),
(9, 1),
(9, 6);

-- --------------------------------------------------------

--
-- Struktura tabele `ucenci_razredi`
--

CREATE TABLE `ucenci_razredi` (
  `ID_ucenca` int(11) NOT NULL,
  `ID_razreda` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Odloži podatke za tabelo `ucenci_razredi`
--

INSERT INTO `ucenci_razredi` (`ID_ucenca`, `ID_razreda`) VALUES
(4, 9),
(6, 6);

-- --------------------------------------------------------

--
-- Struktura tabele `ucitelji_predmeti`
--

CREATE TABLE `ucitelji_predmeti` (
  `ID_ucitelja` int(11) NOT NULL,
  `ID_predmeta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Odloži podatke za tabelo `ucitelji_predmeti`
--

INSERT INTO `ucitelji_predmeti` (`ID_ucitelja`, `ID_predmeta`) VALUES
(2, 1),
(2, 3),
(8, 1),
(8, 3);

-- --------------------------------------------------------

--
-- Struktura tabele `ucitelji_predmeti_razredi`
--

CREATE TABLE `ucitelji_predmeti_razredi` (
  `ID_ucitelja` int(11) NOT NULL,
  `ID_predmeta` int(11) NOT NULL,
  `ID_razreda` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Odloži podatke za tabelo `ucitelji_predmeti_razredi`
--

INSERT INTO `ucitelji_predmeti_razredi` (`ID_ucitelja`, `ID_predmeta`, `ID_razreda`) VALUES
(2, 1, 6),
(2, 1, 7),
(2, 1, 8),
(2, 2, 6),
(2, 2, 7),
(2, 2, 8),
(2, 3, 6),
(2, 3, 7),
(2, 3, 8),
(8, 1, 10),
(8, 3, 10),
(8, 4, 10);

-- --------------------------------------------------------

--
-- Struktura tabele `uporabniki`
--

CREATE TABLE `uporabniki` (
  `ID_uporabnika` int(11) NOT NULL,
  `uporabnisko_ime` varchar(50) NOT NULL,
  `geslo` varchar(255) NOT NULL,
  `ime` varchar(50) NOT NULL,
  `priimek` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `vloga` enum('administrator','učitelj','učenec') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Odloži podatke za tabelo `uporabniki`
--

INSERT INTO `uporabniki` (`ID_uporabnika`, `uporabnisko_ime`, `geslo`, `ime`, `priimek`, `email`, `vloga`) VALUES
(1, 'admin', '$2y$10$.GwLxLmV5tETQe9.eTjlx.qewTQtWR4eZrw7rKrU7IUp6PrVuw5kO', 'Ana', 'Novak', 'admin@example.com', 'administrator'),
(2, 'ucitelj1', '$2y$10$MCTk/syazEDoD0.pU/weV.OEzbQdbFJyUpIcqzXAX8uOYXf.8i.f2', 'Matej', 'Kovač', 'matej.kovac@example.com', 'učitelj'),
(4, 'ucenec1', '$2y$10$HoTQC6HMGIMjuuqVlS3cLOTPYDgPF1X1wErcx/6GpuTy.l7omRFmu', 'Luk', 'Zupan', 'luka.zupan@example.com', 'učenec'),
(5, 'ucenec2', '$2y$10$NP0EEb6Xv1ICkCRSTFM1.eLjsPv7BE/gDwL23QwUOomOJGZ2uldJy', 'Nina', 'Petek', 'nina.petek@example.com', 'učenec'),
(6, 'mihazvegler', '$2y$10$YSKzXJBh92fUj4Rlg.cRz.LwEU1C3arUZlD6pbZWolBPvcK3/xIHy', 'Miha', 'Žvegler', 'miha.zvegler1@gmail.com', 'učenec'),
(8, 'ucitelj5', '$2y$10$TBFxKdCEHHZD/i893lMYje8gFFI7pP7l3Xm1tDEP3DIetnFEoawFS', 'Matic', 'Holobar', 'matic.holobar@primer.com', 'učitelj'),
(9, 'ucenec5', '$2y$10$0u9qeGKK5l1epz7TT6w9he1RZV0/Unqyto5ZEqazpj5lm.OzSiOi2', 'Žiga', 'Plahuta', 'zigi@plahi.com', 'učenec');

--
-- Indeksi zavrženih tabel
--

--
-- Indeksi tabele `gradiva`
--
ALTER TABLE `gradiva`
  ADD PRIMARY KEY (`ID_gradiva`),
  ADD KEY `ID_predmeta` (`ID_predmeta`),
  ADD KEY `ID_ucitelja` (`ID_ucitelja`);

--
-- Indeksi tabele `komentarji_gradiva`
--
ALTER TABLE `komentarji_gradiva`
  ADD PRIMARY KEY (`ID_komentarja`),
  ADD KEY `ID_gradiva` (`ID_gradiva`),
  ADD KEY `ID_avtorja` (`ID_avtorja`);

--
-- Indeksi tabele `komentarji_naloge`
--
ALTER TABLE `komentarji_naloge`
  ADD PRIMARY KEY (`ID_komentarja`),
  ADD KEY `ID_avtorja` (`ID_avtorja`),
  ADD KEY `ID_naloge_predmet` (`ID_naloge_predmet`);

--
-- Indeksi tabele `naloge`
--
ALTER TABLE `naloge`
  ADD PRIMARY KEY (`ID_naloge`),
  ADD KEY `ID_predmeta` (`ID_predmeta`),
  ADD KEY `ID_ucenca` (`ID_ucenca`);

--
-- Indeksi tabele `naloge_predmet`
--
ALTER TABLE `naloge_predmet`
  ADD PRIMARY KEY (`ID_naloge_predmet`),
  ADD KEY `ID_predmeta` (`ID_predmeta`);

--
-- Indeksi tabele `predmeti`
--
ALTER TABLE `predmeti`
  ADD PRIMARY KEY (`ID_predmeta`);

--
-- Indeksi tabele `predmeti_razredi`
--
ALTER TABLE `predmeti_razredi`
  ADD PRIMARY KEY (`ID_predmeta`,`ID_razreda`),
  ADD KEY `ID_razreda` (`ID_razreda`);

--
-- Indeksi tabele `razredi`
--
ALTER TABLE `razredi`
  ADD PRIMARY KEY (`ID_razreda`);

--
-- Indeksi tabele `ucenci_predmeti`
--
ALTER TABLE `ucenci_predmeti`
  ADD PRIMARY KEY (`ID_ucenca`,`ID_predmeta`),
  ADD KEY `ID_predmeta` (`ID_predmeta`);

--
-- Indeksi tabele `ucenci_razredi`
--
ALTER TABLE `ucenci_razredi`
  ADD PRIMARY KEY (`ID_ucenca`,`ID_razreda`),
  ADD KEY `ID_razreda` (`ID_razreda`);

--
-- Indeksi tabele `ucitelji_predmeti`
--
ALTER TABLE `ucitelji_predmeti`
  ADD PRIMARY KEY (`ID_ucitelja`,`ID_predmeta`),
  ADD KEY `ID_predmeta` (`ID_predmeta`);

--
-- Indeksi tabele `ucitelji_predmeti_razredi`
--
ALTER TABLE `ucitelji_predmeti_razredi`
  ADD PRIMARY KEY (`ID_ucitelja`,`ID_predmeta`,`ID_razreda`),
  ADD KEY `ID_predmeta` (`ID_predmeta`),
  ADD KEY `ID_razreda` (`ID_razreda`);

--
-- Indeksi tabele `uporabniki`
--
ALTER TABLE `uporabniki`
  ADD PRIMARY KEY (`ID_uporabnika`),
  ADD UNIQUE KEY `uporabnisko_ime` (`uporabnisko_ime`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT zavrženih tabel
--

--
-- AUTO_INCREMENT tabele `gradiva`
--
ALTER TABLE `gradiva`
  MODIFY `ID_gradiva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT tabele `komentarji_gradiva`
--
ALTER TABLE `komentarji_gradiva`
  MODIFY `ID_komentarja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT tabele `komentarji_naloge`
--
ALTER TABLE `komentarji_naloge`
  MODIFY `ID_komentarja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT tabele `naloge`
--
ALTER TABLE `naloge`
  MODIFY `ID_naloge` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT tabele `naloge_predmet`
--
ALTER TABLE `naloge_predmet`
  MODIFY `ID_naloge_predmet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT tabele `predmeti`
--
ALTER TABLE `predmeti`
  MODIFY `ID_predmeta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT tabele `razredi`
--
ALTER TABLE `razredi`
  MODIFY `ID_razreda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT tabele `uporabniki`
--
ALTER TABLE `uporabniki`
  MODIFY `ID_uporabnika` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Omejitve tabel za povzetek stanja
--

--
-- Omejitve za tabelo `gradiva`
--
ALTER TABLE `gradiva`
  ADD CONSTRAINT `gradiva_ibfk_1` FOREIGN KEY (`ID_predmeta`) REFERENCES `predmeti` (`ID_predmeta`) ON DELETE CASCADE,
  ADD CONSTRAINT `gradiva_ibfk_2` FOREIGN KEY (`ID_ucitelja`) REFERENCES `uporabniki` (`ID_uporabnika`) ON DELETE CASCADE;

--
-- Omejitve za tabelo `komentarji_gradiva`
--
ALTER TABLE `komentarji_gradiva`
  ADD CONSTRAINT `komentarji_gradiva_ibfk_1` FOREIGN KEY (`ID_gradiva`) REFERENCES `gradiva` (`ID_gradiva`) ON DELETE CASCADE,
  ADD CONSTRAINT `komentarji_gradiva_ibfk_2` FOREIGN KEY (`ID_avtorja`) REFERENCES `uporabniki` (`ID_uporabnika`) ON DELETE CASCADE;

--
-- Omejitve za tabelo `komentarji_naloge`
--
ALTER TABLE `komentarji_naloge`
  ADD CONSTRAINT `komentarji_naloge_ibfk_2` FOREIGN KEY (`ID_avtorja`) REFERENCES `uporabniki` (`ID_uporabnika`) ON DELETE CASCADE,
  ADD CONSTRAINT `komentarji_naloge_ibfk_3` FOREIGN KEY (`ID_naloge_predmet`) REFERENCES `naloge_predmet` (`ID_naloge_predmet`) ON DELETE CASCADE;

--
-- Omejitve za tabelo `naloge`
--
ALTER TABLE `naloge`
  ADD CONSTRAINT `naloge_ibfk_1` FOREIGN KEY (`ID_predmeta`) REFERENCES `predmeti` (`ID_predmeta`) ON DELETE CASCADE,
  ADD CONSTRAINT `naloge_ibfk_2` FOREIGN KEY (`ID_ucenca`) REFERENCES `uporabniki` (`ID_uporabnika`) ON DELETE CASCADE;

--
-- Omejitve za tabelo `naloge_predmet`
--
ALTER TABLE `naloge_predmet`
  ADD CONSTRAINT `naloge_predmet_ibfk_1` FOREIGN KEY (`ID_predmeta`) REFERENCES `predmeti` (`ID_predmeta`) ON DELETE CASCADE;

--
-- Omejitve za tabelo `predmeti_razredi`
--
ALTER TABLE `predmeti_razredi`
  ADD CONSTRAINT `predmeti_razredi_ibfk_1` FOREIGN KEY (`ID_predmeta`) REFERENCES `predmeti` (`ID_predmeta`) ON DELETE CASCADE,
  ADD CONSTRAINT `predmeti_razredi_ibfk_2` FOREIGN KEY (`ID_razreda`) REFERENCES `razredi` (`ID_razreda`) ON DELETE CASCADE;

--
-- Omejitve za tabelo `ucenci_predmeti`
--
ALTER TABLE `ucenci_predmeti`
  ADD CONSTRAINT `ucenci_predmeti_ibfk_1` FOREIGN KEY (`ID_ucenca`) REFERENCES `uporabniki` (`ID_uporabnika`) ON DELETE CASCADE,
  ADD CONSTRAINT `ucenci_predmeti_ibfk_2` FOREIGN KEY (`ID_predmeta`) REFERENCES `predmeti` (`ID_predmeta`) ON DELETE CASCADE;

--
-- Omejitve za tabelo `ucenci_razredi`
--
ALTER TABLE `ucenci_razredi`
  ADD CONSTRAINT `ucenci_razredi_ibfk_1` FOREIGN KEY (`ID_ucenca`) REFERENCES `uporabniki` (`ID_uporabnika`) ON DELETE CASCADE,
  ADD CONSTRAINT `ucenci_razredi_ibfk_2` FOREIGN KEY (`ID_razreda`) REFERENCES `razredi` (`ID_razreda`) ON DELETE CASCADE;

--
-- Omejitve za tabelo `ucitelji_predmeti`
--
ALTER TABLE `ucitelji_predmeti`
  ADD CONSTRAINT `ucitelji_predmeti_ibfk_1` FOREIGN KEY (`ID_ucitelja`) REFERENCES `uporabniki` (`ID_uporabnika`) ON DELETE CASCADE,
  ADD CONSTRAINT `ucitelji_predmeti_ibfk_2` FOREIGN KEY (`ID_predmeta`) REFERENCES `predmeti` (`ID_predmeta`) ON DELETE CASCADE;

--
-- Omejitve za tabelo `ucitelji_predmeti_razredi`
--
ALTER TABLE `ucitelji_predmeti_razredi`
  ADD CONSTRAINT `ucitelji_predmeti_razredi_ibfk_1` FOREIGN KEY (`ID_ucitelja`) REFERENCES `uporabniki` (`ID_uporabnika`) ON DELETE CASCADE,
  ADD CONSTRAINT `ucitelji_predmeti_razredi_ibfk_2` FOREIGN KEY (`ID_predmeta`) REFERENCES `predmeti` (`ID_predmeta`) ON DELETE CASCADE,
  ADD CONSTRAINT `ucitelji_predmeti_razredi_ibfk_3` FOREIGN KEY (`ID_razreda`) REFERENCES `razredi` (`ID_razreda`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
