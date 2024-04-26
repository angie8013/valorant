-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-04-2024 a las 02:09:48
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

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
-- Estructura de tabla para la tabla `agente`
--

CREATE TABLE `agente` (
  `id_agente` int(11) NOT NULL,
  `agente` blob NOT NULL,
  `nombre` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `agente`
--

INSERT INTO `agente` (`id_agente`, `agente`, `nombre`) VALUES
(1, '', 'Skye'),
(2, '', 'Omen'),
(3, '', 'Viper'),
(4, '', 'Yoru'),
(5, '', 'Reyna');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `arma`
--

CREATE TABLE `arma` (
  `id_arma` int(11) NOT NULL,
  `arma` blob NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `balas` int(2) NOT NULL,
  `daño` int(2) NOT NULL,
  `tipo_arma` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `arma`
--

INSERT INTO `arma` (`id_arma`, `arma`, `nombre`, `balas`, `daño`, `tipo_arma`) VALUES
(1, '', 'Vandal', 25, 65, '3'),
(2, '', 'Clasicc', 15, 26, '2'),
(3, '', 'Cuchillo', 0, 75, '4');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_batalla`
--

CREATE TABLE `detalle_batalla` (
  `id_detalle` int(11) NOT NULL,
  `id_jugador` int(11) NOT NULL,
  `id_jugador_atacado` int(11) NOT NULL,
  `id_sala` int(11) NOT NULL,
  `id_arma` int(11) NOT NULL,
  `id_agente` int(11) NOT NULL,
  `puntos_vida` int(11) NOT NULL DEFAULT 100
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_batalla`
--

INSERT INTO `detalle_batalla` (`id_detalle`, `id_jugador`, `id_jugador_atacado`, `id_sala`, `id_arma`, `id_agente`, `puntos_vida`) VALUES
(1, 1, 2, 1, 2, 3, 100);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

CREATE TABLE `estado` (
  `id_estado` int(11) NOT NULL,
  `estado` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado`
--

INSERT INTO `estado` (`id_estado`, `estado`) VALUES
(1, 'Activo'),
(2, 'Inactivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jugador`
--

CREATE TABLE `jugador` (
  `id_jugador` int(11) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `username` varchar(15) NOT NULL,
  `contrasena` varchar(500) NOT NULL,
  `foto` blob NOT NULL DEFAULT '',
  `id_rol` int(11) NOT NULL,
  `id_estado` int(11) NOT NULL DEFAULT 2,
  `puntos` int(4) NOT NULL,
  `id_puntos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `jugador`
--

INSERT INTO `jugador` (`id_jugador`, `correo`, `nombre`, `username`, `contrasena`, `foto`, `id_rol`, `id_estado`, `puntos`, `id_puntos`) VALUES
(1, 'an', 'an ', 'an', 'f8e2d6e4ba038b58c4454de4cfad8e3072f9196f92707fba7c9ba8e1cb07d9ca055f4a39ab919c9f57e78049993e17a9876eb64b50f25981fe5ca9d706868727', 0x30, 1, 1, 0, 0),
(3, 'on ', 'on', 'on', 'b7da843eec64c93cb7bbee2e84e7f530bb7c9b637f0286fe5a6edc72a61a6e2193c45884fd6b8e13cb319f29d602315c4bcf70c3f74ac22224f3aace6e1f20ae', 0x30, 2, 1, 0, 1),
(4, 'in', 'in', 'in', 'e884a3a10f4c921d370c4b70f5af451e6e12ffad35c96f6e61221f9cf2efbabdd06db3edeb93bdd1182549d9d94bd86dbaa4205ba9b26721524e9c420e58c834', 0x30, 0, 2, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mundo`
--

CREATE TABLE `mundo` (
  `id_mundo` int(11) NOT NULL,
  `mundo` blob NOT NULL,
  `nombre` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mundo`
--

INSERT INTO `mundo` (`id_mundo`, `mundo`, `nombre`) VALUES
(1, '', 'Lotus'),
(2, '', 'Haven'),
(3, '', 'Pearl');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `puntos`
--

CREATE TABLE `puntos` (
  `id_puntos` int(11) NOT NULL,
  `puntos` int(11) NOT NULL,
  `nivel` int(11) NOT NULL,
  `rango` varchar(30) NOT NULL,
  `rango_img` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `puntos`
--

INSERT INTO `puntos` (`id_puntos`, `puntos`, `nivel`, `rango`, `rango_img`) VALUES
(1, 0, 1, 'Bronce', ''),
(2, 250, 1, 'Plata', ''),
(3, 500, 2, 'Oro', ''),
(4, 750, 3, 'Diamante', ''),
(5, 1000, 4, 'Ascendente', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rec_contra`
--

CREATE TABLE `rec_contra` (
  `id_jugador` varchar(100) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `fecha` date NOT NULL,
  `user_action` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `rol` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `rol`) VALUES
(1, 'admin'),
(2, 'jugador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sala`
--

CREATE TABLE `sala` (
  `id_sala` int(11) NOT NULL,
  `id_mundo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_arma`
--

CREATE TABLE `tipo_arma` (
  `id_tipo_arma` int(11) NOT NULL,
  `tipo_arma` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_arma`
--

INSERT INTO `tipo_arma` (`id_tipo_arma`, `tipo_arma`) VALUES
(1, 'Pistolas'),
(2, 'Subfusil'),
(3, 'Rifles                 '),
(4, 'Cuchillo');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `agente`
--
ALTER TABLE `agente`
  ADD PRIMARY KEY (`id_agente`);

--
-- Indices de la tabla `arma`
--
ALTER TABLE `arma`
  ADD PRIMARY KEY (`id_arma`);

--
-- Indices de la tabla `detalle_batalla`
--
ALTER TABLE `detalle_batalla`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_jugador` (`id_jugador`),
  ADD KEY `id_sala` (`id_sala`),
  ADD KEY `id_arma` (`id_arma`);

--
-- Indices de la tabla `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indices de la tabla `jugador`
--
ALTER TABLE `jugador`
  ADD PRIMARY KEY (`id_jugador`);

--
-- Indices de la tabla `mundo`
--
ALTER TABLE `mundo`
  ADD PRIMARY KEY (`id_mundo`);

--
-- Indices de la tabla `puntos`
--
ALTER TABLE `puntos`
  ADD PRIMARY KEY (`id_puntos`);

--
-- Indices de la tabla `rec_contra`
--
ALTER TABLE `rec_contra`
  ADD PRIMARY KEY (`id_jugador`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `sala`
--
ALTER TABLE `sala`
  ADD PRIMARY KEY (`id_sala`),
  ADD KEY `id_mundo` (`id_mundo`);

--
-- Indices de la tabla `tipo_arma`
--
ALTER TABLE `tipo_arma`
  ADD PRIMARY KEY (`id_tipo_arma`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `agente`
--
ALTER TABLE `agente`
  MODIFY `id_agente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `arma`
--
ALTER TABLE `arma`
  MODIFY `id_arma` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `detalle_batalla`
--
ALTER TABLE `detalle_batalla`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `estado`
--
ALTER TABLE `estado`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `jugador`
--
ALTER TABLE `jugador`
  MODIFY `id_jugador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `mundo`
--
ALTER TABLE `mundo`
  MODIFY `id_mundo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `puntos`
--
ALTER TABLE `puntos`
  MODIFY `id_puntos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipo_arma`
--
ALTER TABLE `tipo_arma`
  MODIFY `id_tipo_arma` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
