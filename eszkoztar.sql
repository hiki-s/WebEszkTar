-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2020. Máj 07. 20:39
-- Kiszolgáló verziója: 10.1.35-MariaDB
-- PHP verzió: 7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `eszkoztar`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `eszkozok`
--

CREATE TABLE `eszkozok` (
  `id` int(100) NOT NULL,
  `nev` varchar(40) NOT NULL,
  `tipus` varchar(30) NOT NULL,
  `darab` int(100) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- A tábla adatainak kiíratása `eszkozok`
--

INSERT INTO `eszkozok` (`id`, `nev`, `tipus`, `darab`) VALUES
(1, 'Kamera 1', 'Nikon', 1),
(2, 'Kamera 2', 'Cannon', 1),
(3, 'SD KÃ¡rtya', '32GB (Kingston)', 4),
(4, 'SD KÃ¡rtya', '64GB (Sony)', 3);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `felhasznalok`
--

CREATE TABLE `felhasznalok` (
  `username` varchar(20) DEFAULT NULL,
  `nev` varchar(40) DEFAULT NULL,
  `neptunKod` varchar(8) NOT NULL,
  `email` varchar(30) DEFAULT NULL,
  `csapat` varchar(30) DEFAULT NULL,
  `jelszo` varchar(16) DEFAULT NULL,
  `regE` int(2) NOT NULL DEFAULT '1',
  `adminE` int(2) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- A tábla adatainak kiíratása `felhasznalok`
--

INSERT INTO `felhasznalok` (`username`, `nev`, `neptunKod`, `email`, `csapat`, `jelszo`, `regE`, `adminE`) VALUES
('Admin', 'Admin', '', NULL, NULL, 'admin', 2, 2);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `igenyles`
--

CREATE TABLE `igenyles` (
  `igId` int(100) NOT NULL,
  `id` int(100) NOT NULL,
  `eszkozNev` varchar(40) NOT NULL,
  `tipus` varchar(40) NOT NULL,
  `mennyit` int(30) NOT NULL,
  `mikor` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ettol` datetime NOT NULL,
  `eddig` datetime NOT NULL,
  `kicsoda` varchar(40) NOT NULL,
  `elvitte` int(2) NOT NULL DEFAULT '0',
  `megjegyzes` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `eszkozok`
--
ALTER TABLE `eszkozok`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `igenyles`
--
ALTER TABLE `igenyles`
  ADD PRIMARY KEY (`igId`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `eszkozok`
--
ALTER TABLE `eszkozok`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT a táblához `igenyles`
--
ALTER TABLE `igenyles`
  MODIFY `igId` int(100) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
