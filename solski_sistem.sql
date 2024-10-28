-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gostitelj: 127.0.0.1
-- Čas nastanka: 28. okt 2024 ob 15.18
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
  `datum_objave` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Odloži podatke za tabelo `gradiva`
--

INSERT INTO `gradiva` (`ID_gradiva`, `ID_predmeta`, `ID_ucitelja`, `naslov_gradiva`, `pot_do_datoteke`, `datum_objave`) VALUES
(3, 2, 3, 'Prešernove pesmi', 'uploads/presernove_pesmi.pdf', '2024-10-28 10:26:32'),
(4, 4, 3, 'Periodni sistem elementov', 'uploads/periodni_sistem.pdf', '2024-10-28 10:26:32'),
(5, 5, 3, 'Srednji vek v Evropi', 'uploads/srednji_vek.pdf', '2024-10-28 10:26:32');

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

--
-- Odloži podatke za tabelo `komentarji_gradiva`
--

INSERT INTO `komentarji_gradiva` (`ID_komentarja`, `ID_gradiva`, `ID_avtorja`, `vsebina`, `datum_komentarja`) VALUES
(1, 4, 6, 'Zdravo', '2024-10-28 11:57:38');

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
(2, 1, 6, 'skret', '2024-10-28 14:49:30');

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
(6, 1, 1, 6, 'Naloga iz algebraičnih enačb', 'uploads/assignments/Žvegler Miha – Naloga iz algebraičnih enačb.pdf', '2024-10-28 14:35:00');

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
  `rok_oddaje` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Odloži podatke za tabelo `naloge_predmet`
--

INSERT INTO `naloge_predmet` (`ID_naloge_predmet`, `ID_predmeta`, `naslov_naloge`, `opis`, `datum_objave`, `rok_oddaje`) VALUES
(1, 1, 'Naloga iz algebraičnih enačb', 'Rešite naslednje enačbe...', '2024-10-28 12:59:25', '2024-12-31 23:59:59'),
(2, 2, 'Esej o Prešernu', 'Napišite esej o življenju in delu Franceta Prešerna...', '2024-10-28 12:59:25', '2024-11-30 23:59:59');

-- --------------------------------------------------------

--
-- Struktura tabele `predmeti`
--

CREATE TABLE `predmeti` (
  `ID_predmeta` int(11) NOT NULL,
  `ime_predmeta` varchar(100) NOT NULL,
  `opis_predmeta` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Odloži podatke za tabelo `predmeti`
--

INSERT INTO `predmeti` (`ID_predmeta`, `ime_predmeta`, `opis_predmeta`) VALUES
(1, 'Matematika', 'Osnovni in napredni koncepti matematike.'),
(2, 'Slovenščina', 'Učenje slovenskega jezika in književnosti.'),
(3, 'Fizika', 'Osnove fizikalnih zakonitosti.'),
(4, 'Kemija', 'Spoznavanje kemijskih elementov in reakcij.'),
(5, 'Zgodovina', 'Pregled svetovne in slovenske zgodovine.');

-- --------------------------------------------------------

--
-- Struktura tabele `predmeti_razredi`
--

CREATE TABLE `predmeti_razredi` (
  `ID_predmeta` int(11) NOT NULL,
  `ID_razreda` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Odloži podatke za tabelo `predmeti_razredi`
--

INSERT INTO `predmeti_razredi` (`ID_predmeta`, `ID_razreda`) VALUES
(1, 1),
(1, 2),
(2, 1),
(2, 2),
(3, 1),
(4, 2),
(5, 1),
(5, 2);

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
(1, '1.A'),
(2, '1.B'),
(3, '2.A'),
(4, '2.B'),
(5, '3.A');

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
(4, 3),
(4, 5),
(5, 1),
(5, 2),
(5, 4),
(6, 1),
(6, 2),
(6, 3),
(6, 4),
(6, 5);

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
(4, 1),
(6, 2);

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
(3, 2),
(3, 4),
(3, 5);

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
(2, 1, 1),
(2, 3, 1),
(3, 2, 2),
(3, 4, 2),
(3, 5, 1),
(3, 5, 2);

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
(3, 'ucitelj2', '$2y$10$iTHlEzhhHuN.0BY/6wjplO2TkOcpphOCA7WbxZXGray.axjk6AaGu', 'Simona', 'Horvat', 'simona.horvat@example.com', 'učitelj'),
(4, 'ucenec1', '$2y$10$HoTQC6HMGIMjuuqVlS3cLOTPYDgPF1X1wErcx/6GpuTy.l7omRFmu', 'Luka', 'Zupan', 'luka.zupan@example.com', 'učenec'),
(5, 'ucenec2', '$2y$10$NP0EEb6Xv1ICkCRSTFM1.eLjsPv7BE/gDwL23QwUOomOJGZ2uldJy', 'Nina', 'Petek', 'nina.petek@example.com', 'učenec'),
(6, 'mihazvegler', '$2y$10$YSKzXJBh92fUj4Rlg.cRz.LwEU1C3arUZlD6pbZWolBPvcK3/xIHy', 'Miha', 'Žvegler', 'miha.zvegler1@gmail.com', 'učenec');

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
  MODIFY `ID_gradiva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT tabele `komentarji_gradiva`
--
ALTER TABLE `komentarji_gradiva`
  MODIFY `ID_komentarja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT tabele `komentarji_naloge`
--
ALTER TABLE `komentarji_naloge`
  MODIFY `ID_komentarja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT tabele `naloge`
--
ALTER TABLE `naloge`
  MODIFY `ID_naloge` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT tabele `naloge_predmet`
--
ALTER TABLE `naloge_predmet`
  MODIFY `ID_naloge_predmet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT tabele `predmeti`
--
ALTER TABLE `predmeti`
  MODIFY `ID_predmeta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT tabele `razredi`
--
ALTER TABLE `razredi`
  MODIFY `ID_razreda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT tabele `uporabniki`
--
ALTER TABLE `uporabniki`
  MODIFY `ID_uporabnika` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
