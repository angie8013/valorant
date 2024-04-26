-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-04-2024 a las 18:39:18
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `valorant`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_batalla`
--

CREATE TABLE `detalle_batalla` (
  `id_detalle` int(11) NOT NULL,
  `id_jugador_atacante` varchar(15) NOT NULL,
  `id_jugador_atacado` varchar(15) NOT NULL,
  `id_sala` int(11) NOT NULL,
  `id_mundo` int(11) NOT NULL,
  `id_arma` int(11) NOT NULL,
  `id_agente` int(11) NOT NULL,
  `puntos_vida` int(11) NOT NULL DEFAULT 100
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_batalla`
--

INSERT INTO `detalle_batalla` (`id_detalle`, `id_jugador_atacante`, `id_jugador_atacado`, `id_sala`, `id_mundo`, `id_arma`, `id_agente`, `puntos_vida`) VALUES
(45, 'prueba', '', 48, 1, 2, 0, 100),
(46, 'prueba', '', 49, 1, 0, 0, 100),
(47, 'prueba', '', 50, 1, 0, 0, 100),
(48, 'prueba', '', 51, 1, 2, 0, 100),
(49, 'prueba', '', 52, 1, 2, 0, 100),
(50, 'prueba', '', 53, 1, 0, 0, 100),
(82, 'on', '', 78, 2, 0, 0, 100);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `detalle_batalla`
--
ALTER TABLE `detalle_batalla`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_jugador` (`id_jugador_atacante`),
  ADD KEY `id_sala` (`id_sala`),
  ADD KEY `id_arma` (`id_arma`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `detalle_batalla`
--
ALTER TABLE `detalle_batalla`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
