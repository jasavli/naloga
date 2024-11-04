-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gostitelj: 127.0.0.1
-- Čas nastanka: 04. nov 2024 ob 21.16
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
(17, 'Matematika', 'Osnove matematike in računanja', 'Matematika_vpis'),
(18, 'Fizika', 'Osnove fizikalnih zakonitosti', 'Fizika_vpis'),
(19, 'Kemija', 'Uvod v kemijo in kemijske reakcije', 'Kemija_vpis'),
(20, 'Biologija', 'Osnove biologije in življenjskih procesov', 'Biologija_vpis'),
(21, 'Zgodovina', 'Svetovna zgodovina in pomembni dogodki', 'Zgodovina_vpis'),
(22, 'Geografija', 'Geografija sveta in naravne značilnosti', 'Geografija_vpis'),
(23, 'Literatura', 'Študij klasične in sodobne literature', 'Literatura_vpis'),
(24, 'Računalništvo', 'Uvod v programiranje in računalniške sisteme', 'Računalništvo_vpis'),
(25, 'Športna vzgoja', 'Telesne aktivnosti in športi', 'Športna_vzgoja_vpis'),
(26, 'Likovna umetnost', 'Uvod v umetnost in oblikovanje', 'Likovna_umetnost_vpis');

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
(1, 'R1A'),
(2, 'R1B'),
(3, 'R2A'),
(4, 'R2B'),
(5, 'R3A'),
(6, 'R3B'),
(7, 'R4A'),
(8, 'R4B');

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
(54, 17),
(54, 18),
(55, 17),
(55, 18),
(56, 17),
(56, 18),
(57, 17),
(57, 18),
(58, 17),
(58, 18),
(59, 17),
(59, 18),
(60, 17),
(60, 18),
(61, 17),
(61, 18),
(62, 17),
(62, 18),
(63, 17),
(63, 18),
(64, 19),
(64, 20),
(65, 19),
(65, 20),
(66, 19),
(66, 20),
(67, 19),
(67, 20),
(68, 19),
(68, 20),
(69, 19),
(69, 20),
(70, 19),
(70, 20),
(71, 19),
(71, 20),
(72, 19),
(72, 20),
(73, 19),
(73, 20),
(74, 21),
(74, 22),
(75, 21),
(75, 22),
(76, 21),
(76, 22),
(77, 21),
(77, 22),
(78, 21),
(78, 22),
(79, 21),
(79, 22),
(80, 21),
(80, 22),
(81, 21),
(81, 22),
(82, 21),
(82, 22),
(83, 21),
(83, 22),
(84, 23),
(84, 24),
(85, 23),
(85, 24),
(86, 23),
(86, 24),
(87, 23),
(87, 24),
(88, 23),
(88, 24),
(89, 23),
(89, 24),
(90, 23),
(90, 24),
(91, 23),
(91, 24),
(92, 23),
(92, 24),
(93, 23),
(93, 24),
(94, 25),
(94, 26),
(95, 25),
(95, 26),
(96, 25),
(96, 26),
(97, 25),
(97, 26),
(98, 25),
(98, 26),
(99, 25),
(99, 26),
(100, 25),
(100, 26),
(101, 25),
(101, 26),
(102, 25),
(102, 26),
(103, 25),
(103, 26),
(104, 17),
(104, 19),
(105, 17),
(105, 19),
(106, 17),
(106, 19),
(107, 17),
(107, 19),
(108, 17),
(108, 19),
(109, 17),
(109, 19),
(110, 17),
(110, 19),
(111, 17),
(111, 19),
(112, 17),
(112, 19),
(113, 17),
(113, 19),
(114, 18),
(114, 20),
(115, 18),
(115, 20),
(116, 18),
(116, 20),
(117, 18),
(117, 20),
(118, 18),
(118, 20),
(119, 18),
(119, 20),
(120, 18),
(120, 20),
(121, 18),
(121, 20),
(122, 18),
(122, 20),
(123, 18),
(123, 20),
(124, 21),
(124, 23),
(125, 21),
(125, 23),
(126, 21),
(126, 23),
(127, 21),
(127, 23),
(128, 21),
(128, 23),
(129, 21),
(129, 23),
(130, 21),
(130, 23),
(131, 21),
(131, 23),
(132, 21),
(132, 23),
(133, 21),
(133, 23),
(134, 22),
(134, 24),
(135, 22),
(135, 24),
(136, 22),
(136, 24),
(137, 22),
(137, 24),
(138, 22),
(138, 24),
(139, 22),
(139, 24),
(140, 22),
(140, 24),
(141, 22),
(141, 24),
(142, 22),
(142, 24),
(143, 22),
(143, 24),
(144, 25),
(144, 26),
(145, 25),
(145, 26),
(146, 25),
(146, 26),
(147, 25),
(147, 26),
(148, 25),
(148, 26),
(149, 25),
(149, 26),
(150, 25),
(150, 26),
(151, 25),
(151, 26),
(152, 25),
(152, 26),
(153, 25),
(153, 26);

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
(54, 1),
(55, 1),
(56, 1),
(57, 1),
(58, 1),
(59, 1),
(60, 1),
(61, 1),
(62, 1),
(63, 1),
(64, 2),
(65, 2),
(66, 2),
(67, 2),
(68, 2),
(69, 2),
(70, 2),
(71, 2),
(72, 2),
(73, 2),
(74, 3),
(75, 3),
(76, 3),
(77, 3),
(78, 3),
(79, 3),
(80, 3),
(81, 3),
(82, 3),
(83, 3),
(84, 4),
(85, 4),
(86, 4),
(87, 4),
(88, 4),
(89, 4),
(90, 4),
(91, 4),
(92, 4),
(93, 4),
(94, 5),
(95, 5),
(96, 5),
(97, 5),
(98, 5),
(99, 5),
(100, 5),
(101, 5),
(102, 5),
(103, 5),
(104, 6),
(105, 6),
(106, 6),
(107, 6),
(108, 6),
(109, 6),
(110, 6),
(111, 6),
(112, 6),
(113, 6),
(114, 7),
(115, 7),
(116, 7),
(117, 7),
(118, 7),
(119, 7),
(120, 7),
(121, 7),
(122, 7),
(123, 7),
(124, 8),
(125, 8),
(126, 8),
(127, 8),
(128, 8),
(129, 8),
(130, 8),
(131, 8),
(132, 8),
(133, 8),
(134, 1),
(135, 1),
(136, 1),
(137, 1),
(138, 1),
(139, 1),
(140, 1),
(141, 1),
(142, 1),
(143, 1),
(144, 2),
(145, 3),
(146, 4),
(147, 5),
(148, 6),
(149, 7),
(150, 8),
(151, 1),
(152, 2),
(153, 3);

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
(34, 17),
(34, 18),
(35, 19),
(35, 20),
(36, 21),
(36, 22),
(37, 23),
(37, 24),
(38, 17),
(38, 19),
(39, 18),
(39, 20),
(40, 21),
(40, 23),
(41, 22),
(41, 24),
(42, 17),
(42, 18),
(43, 19),
(43, 20),
(44, 21),
(44, 22),
(45, 23),
(45, 24),
(46, 17),
(46, 19),
(47, 18),
(47, 20),
(48, 21),
(48, 23),
(49, 22),
(49, 24),
(50, 17),
(50, 18),
(51, 19),
(51, 20),
(52, 21),
(52, 22),
(53, 23),
(53, 24);

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
(34, 17, 1),
(34, 17, 2),
(34, 18, 1),
(34, 18, 2),
(35, 19, 3),
(35, 19, 4),
(35, 20, 3),
(35, 20, 4),
(36, 21, 5),
(36, 21, 6),
(36, 22, 5),
(36, 22, 6),
(37, 23, 7),
(37, 23, 8),
(37, 24, 7),
(37, 24, 8),
(38, 25, 1),
(38, 25, 2),
(38, 25, 3),
(38, 25, 4),
(38, 25, 5),
(38, 25, 6),
(38, 25, 7),
(38, 25, 8),
(39, 26, 1),
(39, 26, 2),
(39, 26, 3),
(39, 26, 4),
(39, 26, 5),
(39, 26, 6),
(39, 26, 7),
(39, 26, 8),
(40, 17, 1),
(40, 19, 3),
(41, 18, 2),
(41, 20, 4),
(42, 21, 6),
(42, 22, 5),
(43, 23, 7),
(43, 24, 8),
(44, 17, 1),
(44, 26, 8),
(45, 18, 4),
(45, 25, 5),
(46, 19, 2),
(46, 20, 3),
(47, 21, 6),
(47, 26, 7),
(48, 22, 5),
(48, 24, 8),
(49, 17, 2),
(49, 25, 6),
(50, 23, 3),
(50, 26, 7),
(51, 18, 1),
(51, 20, 4),
(52, 19, 3),
(52, 24, 8),
(53, 21, 1),
(53, 22, 5);

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
(33, 'admin', '$2y$10$Jy5Pcan8TEJzKQ/usYyRy.1ujkFNgqJe1pjX8HZ2wAoq.0u//oLeK', 'Admin', 'Administrator', 'admin@primer.si', 'administrator'),
(34, 'ucitelj1', '$2y$10$7sCfwuWwzq61J2IuwRJ0aOC/gSytvHj5mIwSNrCRqu9Ccrstpcvfm', 'Marko', 'Novak', 'marko.novak@primer.si', 'učitelj'),
(35, 'ucitelj2', '*5C1E49371F81011293700811FA60B5129BDD7C4D', 'Ana', 'Kranjc', 'ana.kranjc@primer.si', 'učitelj'),
(36, 'ucitelj3', '*F60C1A7CDF51300EDF8C92F32B29F39CD985AA4C', 'Boris', 'Horvat', 'boris.horvat@primer.si', 'učitelj'),
(37, 'ucitelj4', '*94E4D2932AC53911E48885E608A7B3F8F7D6AF5D', 'Maja', 'Zupan', 'maja.zupan@primer.si', 'učitelj'),
(38, 'ucitelj5', '*53EC194DCF03CA3314341CC1BC1009DA88B295C5', 'Peter', 'Vidmar', 'peter.vidmar@primer.si', 'učitelj'),
(39, 'ucitelj6', '*C7F95F44E8F401BEF01C189870EF7A3F3E5EA8BC', 'Mateja', 'Golob', 'mateja.golob@primer.si', 'učitelj'),
(40, 'ucitelj7', '*1A7335013A13F1F8C890A09AE3183473E8B3B64D', 'Simona', 'Kovač', 'simona.kovac@primer.si', 'učitelj'),
(41, 'ucitelj8', '*F04726C0C10B301AD3046E7BAF28C93A16A50B77', 'Jože', 'Kos', 'joze.kos@primer.si', 'učitelj'),
(42, 'ucitelj9', '*805E7B7EE43D563CCA2E578D6B83C7C85BA1735C', 'Nina', 'Breznik', 'nina.breznik@primer.si', 'učitelj'),
(43, 'ucitelj10', '*D3163CEEB101FE78799F5F327265CD7B1426BFAF', 'Tomaž', 'Jereb', 'tomaz.jereb@primer.si', 'učitelj'),
(44, 'ucitelj11', '*EBBEC3EB44E89CC82832EA347F458AA078FB7006', 'Urška', 'Mejak', 'urska.mejak@primer.si', 'učitelj'),
(45, 'ucitelj12', '*A5625D254A7A3CAB8C488F46444948CC10744749', 'Ivan', 'Petek', 'ivan.petek@primer.si', 'učitelj'),
(46, 'ucitelj13', '*C05F2C9139463F6CC9EDC0BD1CFB15FAF4EC2AE0', 'Lara', 'Golob', 'lara.golob@primer.si', 'učitelj'),
(47, 'ucitelj14', '*D48F7972B3A7AFDCDA5307445164709984099985', 'Jure', 'Tomc', 'jure.tomc@primer.si', 'učitelj'),
(48, 'ucitelj15', '*70330BBF552464C69E8A939B1B5925439FB3DAA0', 'Tina', 'Hribar', 'tina.hribar@primer.si', 'učitelj'),
(49, 'ucitelj16', '*25F134E239B6F9324ADC409078363F17CB62FA93', 'Alenka', 'Sever', 'alenka.sever@primer.si', 'učitelj'),
(50, 'ucitelj17', '*AE060928F953C0CB505ED86DE0A8DCC244706692', 'Robert', 'Pivec', 'robert.pivec@primer.si', 'učitelj'),
(51, 'ucitelj18', '*125BEDB7D21B3E90976DCF3922754BF60E531053', 'Barbara', 'Rozman', 'barbara.rozman@primer.si', 'učitelj'),
(52, 'ucitelj19', '*667D7B2CD4CDEAA4D34AAC4B7D268977C60E5E23', 'Aleš', 'Hrast', 'ales.hrast@primer.si', 'učitelj'),
(53, 'ucitelj20', '*D4DBBE9A8397C9F872695BBC28B3831ED780F367', 'Mojca', 'Božič', 'mojca.bozic@primer.si', 'učitelj'),
(54, 'ucenec1', '$2y$10$sWIZGHDKTMr9UzcsALe9NeOLaHgRpDIBR7zbs0lkgYDmJOA4Cy3c6', 'Luka', 'Bevc', 'luka.bevc@primer.si', 'učenec'),
(55, 'ucenec2', '*EFB4B6B78948B1335EC272DFAB5CDBF31FFA8A9F', 'Miha', 'Kralj', 'miha.kralj@primer.si', 'učenec'),
(56, 'ucenec3', '*155ADEF738D9E8285E7F9D18300B7F6ABCC0FD47', 'Tanja', 'Kosec', 'tanja.kosec@primer.si', 'učenec'),
(57, 'ucenec4', '*1E9150404CDC29849CC40F85205EF4A1E7BC1809', 'Sara', 'Dolenc', 'sara.dolenc@primer.si', 'učenec'),
(58, 'ucenec5', '*A896CE5077A13D69223E9B473876973E5B7735E0', 'Anže', 'Grilc', 'anze.grilc@primer.si', 'učenec'),
(59, 'ucenec6', '*4FCC05E6E149C44B340760097A04924AAC9E41CD', 'Ana', 'Zajc', 'ana.zajc@primer.si', 'učenec'),
(60, 'ucenec7', '*32FE60FC76DFB0F81086EFC803F7CC9350857F66', 'Nejc', 'Trček', 'nejc.trcek@primer.si', 'učenec'),
(61, 'ucenec8', '*AAF6220DDC363CC60178989597FDF85A405F2072', 'Petra', 'Oblak', 'petra.oblak@primer.si', 'učenec'),
(62, 'ucenec9', '*C3227CA64DF7DAB0B7DFCF255307D48A69AE4502', 'Rok', 'Debeljak', 'rok.debeljak@primer.si', 'učenec'),
(63, 'ucenec10', '*BCB57E39776F0782EEF1D7AC7944EDF1A6574685', 'Nika', 'Kos', 'nika.kos@primer.si', 'učenec'),
(64, 'ucenec11', 'password_hash', 'Alenka', 'Novak', 'alenka11@example.com', 'učenec'),
(65, 'ucenec12', 'password_hash', 'Bojan', 'Kovac', 'bojan12@example.com', 'učenec'),
(66, 'ucenec13', 'password_hash', 'Cvetka', 'Hribar', 'cvetka13@example.com', 'učenec'),
(67, 'ucenec14', 'password_hash', 'David', 'Kranjc', 'david14@example.com', 'učenec'),
(68, 'ucenec15', 'password_hash', 'Eva', 'Oman', 'eva15@example.com', 'učenec'),
(69, 'ucenec16', 'password_hash', 'Franc', 'Vidmar', 'franc16@example.com', 'učenec'),
(70, 'ucenec17', 'password_hash', 'Goran', 'Pirc', 'goran17@example.com', 'učenec'),
(71, 'ucenec18', 'password_hash', 'Helena', 'Zupan', 'helena18@example.com', 'učenec'),
(72, 'ucenec19', 'password_hash', 'Ivan', 'Golob', 'ivan19@example.com', 'učenec'),
(73, 'ucenec20', 'password_hash', 'Jana', 'Horvat', 'jana20@example.com', 'učenec'),
(74, 'ucenec21', 'password_hash', 'Katja', 'Kovac', 'katja21@example.com', 'učenec'),
(75, 'ucenec22', 'password_hash', 'Leon', 'Kos', 'leon22@example.com', 'učenec'),
(76, 'ucenec23', 'password_hash', 'Maja', 'Krajnc', 'maja23@example.com', 'učenec'),
(77, 'ucenec24', 'password_hash', 'Nina', 'Zajc', 'nina24@example.com', 'učenec'),
(78, 'ucenec25', 'password_hash', 'Oskar', 'Kolar', 'oskar25@example.com', 'učenec'),
(79, 'ucenec26', 'password_hash', 'Petra', 'Petrovič', 'petra26@example.com', 'učenec'),
(80, 'ucenec27', 'password_hash', 'Rok', 'Logar', 'rok27@example.com', 'učenec'),
(81, 'ucenec28', 'password_hash', 'Simona', 'Kavčič', 'simona28@example.com', 'učenec'),
(82, 'ucenec29', 'password_hash', 'Tomaž', 'Rupnik', 'tomaz29@example.com', 'učenec'),
(83, 'ucenec30', 'password_hash', 'Urška', 'Novak', 'urska30@example.com', 'učenec'),
(84, 'ucenec31', 'password_hash', 'Viktor', 'Oblak', 'viktor31@example.com', 'učenec'),
(85, 'ucenec32', 'password_hash', 'Zoran', 'Golob', 'zoran32@example.com', 'učenec'),
(86, 'ucenec33', 'password_hash', 'Žana', 'Jarc', 'zana33@example.com', 'učenec'),
(87, 'ucenec34', 'password_hash', 'Aleksander', 'Petrič', 'aleksander34@example.com', 'učenec'),
(88, 'ucenec35', 'password_hash', 'Brigita', 'Vidmar', 'brigita35@example.com', 'učenec'),
(89, 'ucenec36', 'password_hash', 'Cene', 'Lah', 'cene36@example.com', 'učenec'),
(90, 'ucenec37', 'password_hash', 'Domen', 'Šmid', 'domen37@example.com', 'učenec'),
(91, 'ucenec38', 'password_hash', 'Erik', 'Gorenc', 'erik38@example.com', 'učenec'),
(92, 'ucenec39', 'password_hash', 'Filip', 'Hrovat', 'filip39@example.com', 'učenec'),
(93, 'ucenec40', 'password_hash', 'Gabrijela', 'Jelen', 'gabrijela40@example.com', 'učenec'),
(94, 'ucenec41', 'password_hash', 'Hana', 'Tomšič', 'hana41@example.com', 'učenec'),
(95, 'ucenec42', 'password_hash', 'Iztok', 'Kovač', 'iztok42@example.com', 'učenec'),
(96, 'ucenec43', 'password_hash', 'Jure', 'Žagar', 'jure43@example.com', 'učenec'),
(97, 'ucenec44', 'password_hash', 'Klemen', 'Kaplan', 'klemen44@example.com', 'učenec'),
(98, 'ucenec45', 'password_hash', 'Lara', 'Hladnik', 'lara45@example.com', 'učenec'),
(99, 'ucenec46', 'password_hash', 'Miran', 'Dolenc', 'miran46@example.com', 'učenec'),
(100, 'ucenec47', 'password_hash', 'Neža', 'Božič', 'neza47@example.com', 'učenec'),
(101, 'ucenec48', 'password_hash', 'Oleg', 'Grebenc', 'oleg48@example.com', 'učenec'),
(102, 'ucenec49', 'password_hash', 'Pavel', 'Komar', 'pavel49@example.com', 'učenec'),
(103, 'ucenec50', 'password_hash', 'Renata', 'Praprotnik', 'renata50@example.com', 'učenec'),
(104, 'ucenec51', 'password_hash', 'Sabina', 'Kotnik', 'sabina51@example.com', 'učenec'),
(105, 'ucenec52', 'password_hash', 'Teja', 'Kozar', 'teja52@example.com', 'učenec'),
(106, 'ucenec53', 'password_hash', 'Uroš', 'Lesjak', 'uros53@example.com', 'učenec'),
(107, 'ucenec54', 'password_hash', 'Vesna', 'Drenik', 'vesna54@example.com', 'učenec'),
(108, 'ucenec55', 'password_hash', 'Zala', 'Majer', 'zala55@example.com', 'učenec'),
(109, 'ucenec56', 'password_hash', 'Žiga', 'Pogačar', 'ziga56@example.com', 'učenec'),
(110, 'ucenec57', 'password_hash', 'Blaž', 'Medved', 'blaz57@example.com', 'učenec'),
(111, 'ucenec58', 'password_hash', 'Darja', 'Jereb', 'darja58@example.com', 'učenec'),
(112, 'ucenec59', 'password_hash', 'Ema', 'Koren', 'ema59@example.com', 'učenec'),
(113, 'ucenec60', 'password_hash', 'Franci', 'Berk', 'franci60@example.com', 'učenec'),
(114, 'ucenec61', 'password_hash', 'Gregor', 'Petek', 'gregor61@example.com', 'učenec'),
(115, 'ucenec62', 'password_hash', 'Hilda', 'Jesenovec', 'hilda62@example.com', 'učenec'),
(116, 'ucenec63', 'password_hash', 'Ines', 'Kosmač', 'ines63@example.com', 'učenec'),
(117, 'ucenec64', 'password_hash', 'Jakob', 'Omerzel', 'jakob64@example.com', 'učenec'),
(118, 'ucenec65', 'password_hash', 'Katarina', 'Pavlič', 'katarina65@example.com', 'učenec'),
(119, 'ucenec66', 'password_hash', 'Liza', 'Šibila', 'liza66@example.com', 'učenec'),
(120, 'ucenec67', 'password_hash', 'Matej', 'Krašovec', 'matej67@example.com', 'učenec'),
(121, 'ucenec68', 'password_hash', 'Nikolaj', 'Perko', 'nikolaj68@example.com', 'učenec'),
(122, 'ucenec69', 'password_hash', 'Olga', 'Dolinar', 'olga69@example.com', 'učenec'),
(123, 'ucenec70', 'password_hash', 'Petra', 'Vrhovec', 'petra70@example.com', 'učenec'),
(124, 'ucenec71', 'password_hash', 'Robi', 'Rožič', 'robi71@example.com', 'učenec'),
(125, 'ucenec72', 'password_hash', 'Silva', 'Gojko', 'silva72@example.com', 'učenec'),
(126, 'ucenec73', 'password_hash', 'Tamara', 'Kodrič', 'tamara73@example.com', 'učenec'),
(127, 'ucenec74', 'password_hash', 'Valentin', 'Kuhar', 'valentin74@example.com', 'učenec'),
(128, 'ucenec75', 'password_hash', 'Zvonka', 'Peršak', 'zvonka75@example.com', 'učenec'),
(129, 'ucenec76', 'password_hash', 'Željko', 'Šmid', 'zeljko76@example.com', 'učenec'),
(130, 'ucenec77', 'password_hash', 'Anže', 'Kozole', 'anze77@example.com', 'učenec'),
(131, 'ucenec78', 'password_hash', 'Brina', 'Rok', 'brina78@example.com', 'učenec'),
(132, 'ucenec79', 'password_hash', 'Ciril', 'Štorg', 'ciril79@example.com', 'učenec'),
(133, 'ucenec80', 'password_hash', 'Darijan', 'Nagode', 'darijan80@example.com', 'učenec'),
(134, 'ucenec81', 'password_hash', 'Eva', 'Bezjak', 'eva81@example.com', 'učenec'),
(135, 'ucenec82', 'password_hash', 'Fani', 'Močnik', 'fani82@example.com', 'učenec'),
(136, 'ucenec83', 'password_hash', 'Goran', 'Hrastar', 'goran83@example.com', 'učenec'),
(137, 'ucenec84', 'password_hash', 'Helena', 'Rebernik', 'helena84@example.com', 'učenec'),
(138, 'ucenec85', 'password_hash', 'Ivana', 'Petrič', 'ivana85@example.com', 'učenec'),
(139, 'ucenec86', 'password_hash', 'Jernej', 'Javornik', 'jernej86@example.com', 'učenec'),
(140, 'ucenec87', 'password_hash', 'Karmen', 'Pirc', 'karmen87@example.com', 'učenec'),
(141, 'ucenec88', 'password_hash', 'Luka', 'Erjavec', 'luka88@example.com', 'učenec'),
(142, 'ucenec89', 'password_hash', 'Milan', 'Šolar', 'milan89@example.com', 'učenec'),
(143, 'ucenec90', 'password_hash', 'Nina', 'Jager', 'nina90@example.com', 'učenec'),
(144, 'ucenec91', 'password_hash', 'Oton', 'Godec', 'oton91@example.com', 'učenec'),
(145, 'ucenec92', 'password_hash', 'Petra', 'Gajšek', 'petra92@example.com', 'učenec'),
(146, 'ucenec93', 'password_hash', 'Rok', 'Repnik', 'rok93@example.com', 'učenec'),
(147, 'ucenec94', 'password_hash', 'Saša', 'Bužek', 'sasa94@example.com', 'učenec'),
(148, 'ucenec95', 'password_hash', 'Tatjana', 'Jug', 'tatjana95@example.com', 'učenec'),
(149, 'ucenec96', 'password_hash', 'Urban', 'Breznik', 'urban96@example.com', 'učenec'),
(150, 'ucenec97', 'password_hash', 'Vesna', 'Plestenjak', 'vesna97@example.com', 'učenec'),
(151, 'ucenec98', 'password_hash', 'Zdravko', 'Grošelj', 'zdravko98@example.com', 'učenec'),
(152, 'ucenec99', 'password_hash', 'Živa', 'Merkač', 'ziva99@example.com', 'učenec'),
(153, 'ucenec100', 'password_hash', 'Alen', 'Lamut', 'alen100@example.com', 'učenec');

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
  MODIFY `ID_gradiva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT tabele `komentarji_naloge`
--
ALTER TABLE `komentarji_naloge`
  MODIFY `ID_komentarja` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT tabele `naloge`
--
ALTER TABLE `naloge`
  MODIFY `ID_naloge` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT tabele `naloge_predmet`
--
ALTER TABLE `naloge_predmet`
  MODIFY `ID_naloge_predmet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT tabele `predmeti`
--
ALTER TABLE `predmeti`
  MODIFY `ID_predmeta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT tabele `razredi`
--
ALTER TABLE `razredi`
  MODIFY `ID_razreda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT tabele `uporabniki`
--
ALTER TABLE `uporabniki`
  MODIFY `ID_uporabnika` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=154;

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
