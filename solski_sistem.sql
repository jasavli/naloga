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
(7, 'Biologija', 'Študij biologije in naravoslovja.', 'bio1'),
(8, 'Geografija', 'Osnove geografije in naravoslovja.', 'geo2'),
(9, 'Računalništvo', 'Uvod v računalništvo in programiranje.', 'comp123'),
(10, 'Psihologija', 'Osnove psihologije in človeškega vedenja.', 'psy202');
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
(10, 'ucitelj10', '$2y$10$TBFxKdCEHHZD/i893lMYje8gFFI7pP7l3Xm1tDEP3DIetnFEoawFS', 'Tina', 'Novak', 'tina.novak@example.com', 'učitelj'),
(11, 'ucitelj11', '$2y$10$TBFxKdCEHHZD/i893lMYje8gFFI7pP7l3Xm1tDEP3DIetnFEoawFS', 'Igor', 'Kovač', 'igor.kovac@example.com', 'učitelj'),
(12, 'ucitelj12', '$2y$10$TBFxKdCEHHZD/i893lMYje8gFFI7pP7l3Xm1tDEP3DIetnFEoawFS', 'Sara', 'Horvat', 'sara.horvat@example.com', 'učitelj'),
(13, 'ucitelj13', '$2y$10$TBFxKdCEHHZD/i893lMYje8gFFI7pP7l3Xm1tDEP3DIetnFEoawFS', 'Aleš', 'Potočnik', 'ales.potocnik@example.com', 'učitelj'),
(14, 'ucitelj14', '$2y$10$TBFxKdCEHHZD/i893lMYje8gFFI7pP7l3Xm1tDEP3DIetnFEoawFS', 'Katja', 'Vidmar', 'katja.vidmar@example.com', 'učitelj'),
(15, 'ucitelj15', '$2y$10$TBFxKdCEHHZD/i893lMYje8gFFI7pP7l3Xm1tDEP3DIetnFEoawFS', 'Marko', 'Hribar', 'marko.hribar@example.com', 'učitelj'),
(16, 'ucitelj16', '$2y$10$TBFxKdCEHHZD/i893lMYje8gFFI7pP7l3Xm1tDEP3DIetnFEoawFS', 'Lana', 'Jereb', 'lana.jereb@example.com', 'učitelj'),
(17, 'ucitelj17', '$2y$10$TBFxKdCEHHZD/i893lMYje8gFFI7pP7l3Xm1tDEP3DIetnFEoawFS', 'Tomaž', 'Rozman', 'tomaz.rozman@example.com', 'učitelj'),
(18, 'ucitelj18', '$2y$10$TBFxKdCEHHZD/i893lMYje8gFFI7pP7l3Xm1tDEP3DIetnFEoawFS', 'Urška', 'Bregar', 'urska.bregar@example.com', 'učitelj'),
(19, 'ucitelj19', '$2y$10$TBFxKdCEHHZD/i893lMYje8gFFI7pP7l3Xm1tDEP3DIetnFEoawFS', 'Dejan', 'Omerza', 'dejan.omerza@example.com', 'učitelj'),
(20, 'ucitelj20', '$2y$10$TBFxKdCEHHZD/i893lMYje8gFFI7pP7l3Xm1tDEP3DIetnFEoawFS', 'Mojca', 'Kastelic', 'mojca.kastelic@example.com', 'učitelj');
(21, 'ucenec21', '$2y$10$NzSotT8D29ODph7VG0a8ROwVuSuRDbBQeRtvfh/QXmO9b/BJ0dYqW', 'Lara', 'Medved', 'lara.medved@example.com', 'učenec'),
(22, 'ucenec22', '$2y$10$M1wz96cOlU5Oi0FdBn.jFOLq7kdhESg1eNd89lJeuV2eJDhQhns7O', 'Marko', 'Vran', 'marko.vran@example.com', 'učenec'),
(23, 'ucenec23', '$2y$10$cqSiv4u8kwy0lRt5LgEVx.dF6SCzmhLNby2FgI5DDEhnfHt4YPkJ.', 'Klemen', 'Pavlin', 'klemen.pavlin@example.com', 'učenec'),
(24, 'ucenec24', '$2y$10$6Ki/H0gJrm5IEECIq8j4nOqrWZlbgvVk2tGz27Ucz5cdR12OfdfmW', 'Tadej', 'Kovačič', 'tadej.kovacic@example.com', 'učenec'),
(25, 'ucenec25', '$2y$10$hlJl2RwjRAxE1O7DG67lwOCkT5cP1iBKwTm1i8rRUWYaZf4pWQ56a', 'Andrej', 'Košir', 'andrej.kosir@example.com', 'učenec'),
(26, 'ucenec26', '$2y$10$.Bop7FGyT6Ay7A7u3RO.OOa9jQTPIN/HCEalGp5fAfzU7hsh1gIMy', 'Rok', 'Hrastnik', 'rok.hrastnik@example.com', 'učenec'),
(27, 'ucenec27', '$2y$10$8zMpnwhTFAVxX3RWXaOYD.wE8HSFfzLWBRR5RtWe0U1mFY93IqDDm', 'Blaž', 'Jereb', 'blaz.jereb@example.com', 'učenec'),
(28, 'ucenec28', '$2y$10$9RflORyVzE1S9na.TREsoOb7H/dB1tHVekB9DSWPS9RhIRUvhJfzO', 'Saša', 'Rozman', 'sasa.rozman@example.com', 'učenec'),
(29, 'ucenec29', '$2y$10$MvNjSxRkBP4vV1zQv.MiqOHE58bEd0ZZNYHg8T1eAYgk/3MyS.f7a', 'Iztok', 'Zupančič', 'iztok.zupancic@example.com', 'učenec'),
(30, 'ucenec30', '$2y$10$SfTpQHT1lM5/AX2M7CgCQOhMSkT5xP2TPoFmE5LbsuF/3P5JtGDDW', 'Lidija', 'Tomšič', 'lidija.tomsic@example.com', 'učenec'),
(31, 'ucenec31', '$2y$10$3oV7Cek6cbFG5E4M4Zye3OYveO5ldqAxKQUO.TU8UsQdKhVhmIgVW', 'Alenka', 'Robnik', 'alenka.robnik@example.com', 'učenec'),
(32, 'ucenec32', '$2y$10$LQ/tL6B.PjbW9EdDJmM7h.mbbQuP0ek63XcPB05u7Hs3et1GQPo6.', 'Sabina', 'Kralj', 'sabina.kralj@example.com', 'učenec'),
(33, 'ucenec33', '$2y$10$dl3tLxVc9iHjEOJWIHEHQOWQTiUw.pNwoD54Jm74AhDWzCqA3sXQy', 'Borut', 'Zajc', 'borut.zajc@example.com', 'učenec'),
(34, 'ucenec34', '$2y$10$pyv3yzCrUQFET.aFFRmhaObWiYkZ2hMvM6x5JGiI8PB1EpIm6pfLG', 'Domen', 'Lesjak', 'domen.lesjak@example.com', 'učenec'),
(35, 'ucenec35', '$2y$10$rhb3C3Yk9EgIojRPS1hHeOdT62iB2BeX5BvcwW7WlN/GjE4LgQi4u', 'Katja', 'Pintar', 'katja.pintar@example.com', 'učenec'),
(36, 'ucenec36', '$2y$10$u6lXJDZntIWOKS5Rg/.U8e6Rg4FAkQ3mV5fhP/yZJBoSxdNs5avjO', 'Matej', 'Rozina', 'matej.rozina@example.com', 'učenec'),
(37, 'ucenec37', '$2y$10$SPic8xCeVpHRkQda/OjC2.0QWq7WXgOV2n69xZ7w9N8IvCu.T6otG', 'Iva', 'Kovač', 'iva.kovac@example.com', 'učenec'),
(38, 'ucenec38', '$2y$10$02HG5qCemZg/xgBq42VRt.tjTxA8Q7M1GHnFoKn8Xbc7wRoKwZ6HS', 'Jaka', 'Sever', 'jaka.sever@example.com', 'učenec'),
(39, 'ucenec39', '$2y$10$Ed3pOyA7K7ul5vWZ8PK2A.6AK68.m3qUQJc5fdZszZdfYOysKUxzS', 'Maja', 'Rozman', 'maja.rozman@example.com', 'učenec'),
(40, 'ucenec40', '$2y$10$59FyQUnQKWTGcqYIjHGXi.W4jMIxd5EgpFbLQQ5XZ9KnwHRA5SZ96', 'Leon', 'Pivk', 'leon.pivk@example.com', 'učenec'),
(41, 'ucenec41', '$2y$10$u7EwLKmIvS6gA.ybkKNtyewToRg9QHSz6n6O95yI23Oi5ZKTcvx7K', 'Miran', 'Petek', 'miran.petek@example.com', 'učenec'),
(42, 'ucenec42', '$2y$10$Mw4O8iCgZjcGdKr0xpxNOOm8WmQ6RG3RyyJq72FvGiz/RQO/ZfgYy', 'Brina', 'Kolar', 'brina.kolar@example.com', 'učenec'),
(43, 'ucenec43', '$2y$10$RZpoxt4uc2K.9uUN6tW8OuERgGOSBv1HhM0/qS8kOCpx2QttIpGI.', 'Metka', 'Polak', 'metka.polak@example.com', 'učenec'),
(44, 'ucenec44', '$2y$10$JUmC5cDg.bu/A3YMiMc3/.gHEEuWuD92cGrD6uCqlNxsMNhPvwhRa', 'Eva', 'Košir', 'eva.kosir@example.com', 'učenec'),
(45, 'ucenec45', '$2y$10$QXdp7QROdaPusLoTlRV3.eOq69zcnvDG3NYFg2a1O6MkVur.NWTbO', 'Anže', 'Logar', 'anze.logar@example.com', 'učenec'),
(46, 'ucenec46', '$2y$10$hHxQb4kzZjOhjrFdnoTIg.3odjSzchIoUz4uZORVV5AofRmcpooUe', 'David', 'Perko', 'david.perko@example.com', 'učenec'),
(47, 'ucenec47', '$2y$10$yhtKcF45M5j7XXc5V8NqA.Ld8tn/MekALwPUOnZnEQ5rRMkXJZgpG', 'Simona', 'Gruden', 'simona.gruden@example.com', 'učenec'),
(48, 'ucenec48', '$2y$10$3wR.1bZ3clX1n5v4/W9LQ.RgT0wZriRhcUwT6/QwBo9U6RP1AI1fK', 'Nika', 'Erjavec', 'nika.erjavec@example.com', 'učenec'),
(49, 'ucenec49', '$2y$10$aAexsJ0KtfLx1Z5tlYsAo.D4g7SoYyPCckhHaM6Ub3Og0zVjKOhY2', 'Peter', 'Stanko', 'peter.stanko@example.com', 'učenec'),
(50, 'ucenec50', '$2y$10$lc0lDWtNKdBeQ0QxFO7T/.hbMrLgg0vWm/C6hQuOQW8IpQwYP1aYK', 'Nejc', 'Nagode', 'nejc.nagode@example.com', 'učenec'),
(51, 'ucenec51', '$2y$10$WDe.SNXHBCqCVPaA1geOs.9a1E4AKuXo/lXy9H7UT5D3vDG0/MKXK', 'Luka', 'Kočevar', 'luka.kocevar@example.com', 'učenec'),
(52, 'ucenec52', '$2y$10$qXT/WEDDWrfNOfwKRaIW4OqksRmFHEDTbFtFX4qztk7gDj1Bjl8pW', 'Tina', 'Jelen', 'tina.jelen@example.com', 'učenec'),
(53, 'ucenec53', '$2y$10$gyJu6/1w/y4R.ANQZ3/3ieRoCwNZyNh.XryS3XRR9PO9XSKt06HAG', 'Aleš', 'Zupan', 'ales.zupan@example.com', 'učenec'),
(54, 'ucenec54', '$2y$10$F8L6bS/FVqKaRoXbNPfEYex7Hwtn/o/DpRkHz3NG2dZfgYaRLKk7K', 'Sonja', 'Horvat', 'sonja.horvat@example.com', 'učenec'),
(55, 'ucenec55', '$2y$10$EhGyoO3b.CZK/NhPLb2lLOONkiXuRXcdFg5Dq.VR/bOq0LXIsE.YW', 'Uroš', 'Bevc', 'uros.bevc@example.com', 'učenec'),
(56, 'ucenec56', '$2y$10$Z3xJCHszOLUt2A9XcoI6UuHUM8H7J.O7vye8FAi2uNi5FXiW4FVqi', 'Jernej', 'Vrhovec', 'jernej.vrhovec@example.com', 'učenec'),
(57, 'ucenec57', '$2y$10$StFVAc.kbiTn4RI5IqDPLezU7Gc7V4S9Hz6jTYHKNsX0uwNxlFkOC', 'Rok', 'Mlakar', 'rok.mlakar@example.com', 'učenec'),
(58, 'ucenec58', '$2y$10$zPLXf.pHxnsmOg9gAS/WdOiv0B6TtweXoIImhEj3zzHzDGlWMezV6', 'Marina', 'Perko', 'marina.perko@example.com', 'učenec'),
(59, 'ucenec59', '$2y$10$rk9HLb5gQQxKgRGdEUODZO.lY6N7foP/BxtmRlO3S9LUH7rPEV59O', 'Monika', 'Kumer', 'monika.kumer@example.com', 'učenec'),
(60, 'ucenec60', '$2y$10$sgFqP7NQ1Y5hlb0Rx/UDMeX6Z5c4dYsdBCFb/RAoWABusDORP.VzG', 'Matic', 'Vidic', 'matic.vidic@example.com', 'učenec'),
(61, 'ucenec61', '$2y$10$.7ftEdb8zXO7IRvlTpDIIezh6PxsDOc.xiy2h/xXsCe45bXz96jiO', 'Marjan', 'Prevc', 'marjan.prevc@example.com', 'učenec'),
(62, 'ucenec62', '$2y$10$7iZ7hKKI.bRrcD4Bf7.Ssewwmyv2c3d1h6UszX5Kf6j6mDOIzTOr6', 'Maja', 'Mlakar', 'maja.mlakar@example.com', 'učenec'),
(63, 'ucenec63', '$2y$10$0u8dNmZ8iTxP.2jN6b4Cne8UP3ZzRAG5WmPdL0GuvEN5P3EuY3URO', 'Mateja', 'Krpan', 'mateja.krpan@example.com', 'učenec'),
(64, 'ucenec64', '$2y$10$Tj5ekHlNQfDDddz3Sd.joeP/nb4Xbq4jDiZk12KNW/U5J3.5Hx0Jm', 'Klemen', 'Pahor', 'klemen.pahor@example.com', 'učenec'),
(65, 'ucenec65', '$2y$10$L8eEaJzEl/mkaU2TGvGnMOqvTPXxeqczYZ5KLbgEve.kJtTsI8bZW', 'Tjaša', 'Jure', 'tjasa.jure@example.com', 'učenec'),
(66, 'ucenec66', '$2y$10$zB0pebK95uVhAszfhE/X/OyxFxRNGM47yqCVszDGp5Y6tUpMK/ZWi', 'Andrej', 'Hafner', 'andrej.hafner@example.com', 'učenec'),
(67, 'ucenec67', '$2y$10$2RsFXjLFoRaK.MDqUQcSeOIFCdAcyFhJsJ5ZgDaHk1A3ECq9FlC1S', 'Urša', 'Blazic', 'ursa.blazic@example.com', 'učenec'),
(68, 'ucenec68', '$2y$10$D2Fd9klPclY2u/ZpBaB0EeGnLJeK/Q0HxQPHMo.5GuLsXpb9yCAsK', 'Damjan', 'Likar', 'damjan.likar@example.com', 'učenec'),
(69, 'ucenec69', '$2y$10$D.Vc5LSZ9yU8kFfMS7DOse4F3YZcuTbgH78xTGQCCJ.puZP5Fr3gm', 'Laura', 'Soban', 'laura.soban@example.com', 'učenec'),
(70, 'ucenec70', '$2y$10$WBOcLz4bWuRh74RW.riJde1NB/9bh39bVzy2mbAfgQ5Ez8uDzH/Ku', 'Miha', 'Golob', 'miha.golob@example.com', 'učenec'),
(71, 'ucenec71', '$2y$10$6Mlgx3.lFeXZ/ieU1rHZQOEn5OCfTp9EJLC0r3fDh4Ms6lUR3ElyG', 'Lucija', 'Ceh', 'lucija.ceh@example.com', 'učenec'),
(72, 'ucenec72', '$2y$10$O9e/xuPViUosJYQPYL3TgeBi/7MS7ahBi5/zT0TcZLLwjBPiL98SO', 'Tilen', 'Demšar', 'tilen.demsar@example.com', 'učenec'),
(73, 'ucenec73', '$2y$10$Q9UN/ak67O.KjvMBDBbL/utnWbcIPF18oINorWJoNnU8XHAYXP2wO', 'Živa', 'Zorc', 'ziva.zorc@example.com', 'učenec'),
(74, 'ucenec74', '$2y$10$ppNoFvKi.L2Sf6rQDmhC.eODQOuNsW21p/jfyOMvUSevAAHrfsgcO', 'Jasna', 'Urh', 'jasna.urh@example.com', 'učenec'),
(75, 'ucenec75', '$2y$10$UgS5/akJmEZf2Ov/qvHGKuF5F9FCfVSuPoFJDKuMiLWqHcVEReyxe', 'Nina', 'Vidmar', 'nina.vidmar@example.com', 'učenec'),
(76, 'ucenec76', '$2y$10$qIfFyf5hjuy5quG5MPODSe6SxjAp8fwdXU09PVHj7GyUo/WZK/nmO', 'Sašo', 'Jelovac', 'saso.jelovac@example.com', 'učenec'),
(77, 'ucenec77', '$2y$10$N1DzhZ4tb09BSe6DhQBaMeDqGlj/ZJXAlcTgVVvG.x5YPbELyTIgu', 'Metod', 'Lipnik', 'metod.lipnik@example.com', 'učenec'),
(78, 'ucenec78', '$2y$10$x9ZQXWazFbLlqTEru5OHEu7Qj/ySxE7fuRuGbMFE82R0XoN5rIEAW', 'Marko', 'Zajec', 'marko.zajec@example.com', 'učenec'),
(79, 'ucenec79', '$2y$10$.bxlQy8EAsnOzSGg9fqTleVuUnCwbFBM4BaW8Jfg9VByJ7/BW7qs2', 'Silvija', 'Jaklič', 'silvija.jaklic@example.com', 'učenec'),
(80, 'ucenec80', '$2y$10$e9SnkI4pniQZQe1hC3/bWOLBiTVuytwCZ2rczjpyuU75TZQ0tIHTm', 'Igor', 'Urankar', 'igor.urankar@example.com', 'učenec'),
(81, 'ucenec81', '$2y$10$D7FGKu0FZHQVQu4eovELVeTuRsLq5ifxHXFLBDV0GPoTc/zfRpbLK', 'Veronika', 'Kocjan', 'veronika.kocjan@example.com', 'učenec'),
(82, 'ucenec82', '$2y$10$eyGfp.l8VR0.v6JxHyuvReWZR79L7CO4Qfj2NK8xy06p7lzrzjL1W', 'Grega', 'Strel', 'grega.strel@example.com', 'učenec'),
(83, 'ucenec83', '$2y$10$qsl5HDy7V5X.y7/RbkxPOu6xsKTURs/gnbhisFBoZ3k9/F2t34FVK', 'Vesna', 'Debelak', 'vesna.debelak@example.com', 'učenec'),
(84, 'ucenec84', '$2y$10$rxpIQa3BiFb2zOzCNmjeSeO9W8xjL3ohRt.G49uk/QN/l9PPT3/S6', 'Jan', 'Potokar', 'jan.potokar@example.com', 'učenec'),
(85, 'ucenec85', '$2y$10$gLv0lHj3bx5PB/44aFJZJOf60NhRT/omGVxYdjEMon9QXg/kxGU2a', 'Tine', 'Strmec', 'tine.strmec@example.com', 'učenec'),
(86, 'ucenec86', '$2y$10$Lvh.kfBjF8cXS3wGpkzrLOyeMgiLP8tAUmfOHU9iQtkpAkc7v4gRm', 'Sara', 'Krajnik', 'sara.krajnik@example.com', 'učenec'),
(87, 'ucenec87', '$2y$10$QSTFbX5n8cJjx8a8tzZYOusG9z33IxsFhtmORuIVfGhN7bfkCjSt2', 'Žiga', 'Urbanc', 'ziga.urbanc@example.com', 'učenec'),
(88, 'ucenec88', '$2y$10$.5v1Z.9A1y6zCBPXXuf8qu2EXzSbi7V4gD5qkQLr.nk3OKt6d/QBC', 'Miha', 'Vidovič', 'miha.vidovic@example.com', 'učenec'),
(89, 'ucenec89', '$2y$10$fnp2ROq9m/XV1.vFLLP5FOtT7gWHDH6cqZ3V2A2kWkw7GDOr.4pSO', 'Anej', 'Sikošek', 'anej.sikosek@example.com', 'učenec'),
(90, 'ucenec90', '$2y$10$FtWfVFpHYvZKYFZwF2yOKOTbvZeA.Zy2LzxeaxGy0kWCGgF0ES/nm', 'Rok', 'Kos', 'rok.kos@example.com', 'učenec'),
(91, 'ucenec91', '$2y$10$1oVG.aqRY3k5LU9ZYz09p.RExfHhHLqlQAS6k5pYh2PGYAYleZPWa', 'Bor', 'Knez', 'bor.knez@example.com', 'učenec'),
(92, 'ucenec92', '$2y$10$ykg0kz9fGb54m0qLMQbu0O7CzZoG/kZbFW5kP8c8OklkDrnTY44aW', 'Aleks', 'Boštjančič', 'aleks.bostjancic@example.com', 'učenec'),
(93, 'ucenec93', '$2y$10$zWy8Ia5BPe3/oAqFxT3OhuWI5NeUuMjU8vwtslTOVvlZp/uqXXF0a', 'Vita', 'Steblovnik', 'vita.steblovnik@example.com', 'učenec'),
(94, 'ucenec94', '$2y$10$LDcMDo7dX.3/yXndRU0OVeLdm2kPI/p8RAgY2txhbjHrXNYMC/zFS', 'Niko', 'Strgar', 'niko.strgar@example.com', 'učenec'),
(95, 'ucenec95', '$2y$10$ub2U/m0G9m8BBOat1zhCxe12FWbAKJbZlBqN/JvwqrPc19Ad6F2Ku', 'Mihaela', 'Štibelj', 'mihaela.stibelj@example.com', 'učenec'),
(96, 'ucenec96', '$2y$10$aRPtAX4K3Fn5j3TGztkjMuO9BS91Oa/qRvcp6pbMh/R1ksOkMclbO', 'Valentina', 'Šuc', 'valentina.suc@example.com', 'učenec'),
(97, 'ucenec97', '$2y$10$SbXNRcT2lh9PjEF8gZLZcuNGJQk/B3KoZbCjXx7ITBQ9dRvU9ixmS', 'Gaja', 'Fatur', 'gaja.fatur@example.com', 'učenec'),
(98, 'ucenec98', '$2y$10$uXPa5Hzc0qYxHY5uoA1QwePoaRVAcU9IophtREdo3dyDHD6v9puFG', 'Urban', 'Vrhnjak', 'urban.vrhnjak@example.com', 'učenec'),
(99, 'ucenec99', '$2y$10$aekE4sKD2K4rhxtEshjkl.fuWlJ8HRKfv/7wB1sLWsH9/tnTMuPeu', 'Ajda', 'Rogelj', 'ajda.rogelj@example.com', 'učenec'),
(100, 'ucenec100', '$2y$10$tdQF4Z/FtMj4e/U21XmsNOkJQvMLO7Y9Q6QWSPfbV7zD0KZbmSvxC', 'Lara', 'Pečnik', 'lara.pecnik@example.com', 'učenec'),
(101, 'ucenec101', '$2y$10$TXkjPox4tKjo0TcMtI6uO.OfXsiI6J1M/S1b4yQ6FFEFG1RaINpBO', 'Leon', 'Kosmač', 'leon.kosmac@example.com', 'učenec'),
(102, 'ucenec102', '$2y$10$R7t04pE5XjOvBoFyVsMiEe7w9s4XIXjeFzZyNkswtELKgJwYkSoLe', 'Manca', 'Volk', 'manca.volk@example.com', 'učenec'),
(103, 'ucenec103', '$2y$10$ziPQPl4KOie0ZpGkOaHrA.WbX4uEkBRLt68pMLRJAwLUAvh3f3Aae', 'Urh', 'Jesenko', 'urh.jesenko@example.com', 'učenec'),
(104, 'ucenec104', '$2y$10$OsMiNfbJcI2hpWzxG7PF7e5ZV51sm/N8g1I0vJsDhRx6dwgItOo8i', 'Tadeja', 'Flego', 'tadeja.flego@example.com', 'učenec'),
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
