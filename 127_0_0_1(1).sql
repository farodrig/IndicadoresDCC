-- phpMyAdmin SQL Dump
-- version 4.4.3
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 09-06-2015 a las 00:40:38
-- Versión del servidor: 5.6.24
-- Versión de PHP: 5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `Udashboard`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Category`
--

CREATE TABLE IF NOT EXISTS `Category` (
  `id` int(11) NOT NULL COMMENT 'Categorías en las que se agrupan las métricas',
  `name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `Category`
--

INSERT INTO `Category` (`id`, `name`) VALUES
(1, 'Productividad'),
(2, 'Finanzas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Dashboard`
--

CREATE TABLE IF NOT EXISTS `Dashboard` (
  `id` int(11) NOT NULL,
  `org` int(11) NOT NULL,
  `title` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `Dashboard`
--

INSERT INTO `Dashboard` (`id`, `org`, `title`) VALUES
(1, 2, 'Dashboard Pregrado'),
(6, 3, 'Dashboard docencia'),
(7, 1, 'Dashboard DCC'),
(9, 21, 'Dashboard Que tal'),
(10, 0, 'Dashboard DCC'),
(11, 7, 'Dashboard Postgrado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `GraphDash`
--

CREATE TABLE IF NOT EXISTS `GraphDash` (
  `id` int(11) NOT NULL,
  `dashboard` int(11) NOT NULL,
  `graphic` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `GraphDash`
--

INSERT INTO `GraphDash` (`id`, `dashboard`, `graphic`) VALUES
(1, 1, 1),
(2, 1, 2),
(5, 1, 11),
(8, 1, 14),
(19, 1, 25),
(20, 1, 26),
(21, 1, 27),
(3, 6, 9),
(6, 6, 12),
(7, 7, 13),
(9, 7, 15),
(11, 7, 16),
(15, 7, 21),
(22, 7, 28),
(17, 9, 17),
(18, 11, 24),
(23, 11, 29);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Graphic`
--

CREATE TABLE IF NOT EXISTS `Graphic` (
  `id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `metorg` int(11) NOT NULL,
  `min_year` int(11) NOT NULL,
  `max_year` int(11) NOT NULL,
  `position` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `Graphic`
--

INSERT INTO `Graphic` (`id`, `type`, `metorg`, `min_year`, `max_year`, `position`) VALUES
(1, 2, 1, 2000, 2015, 1),
(2, 1, 2, 2013, 2015, 0),
(9, 1, 5, 1999, 2019, 1),
(11, 2, 5, 1996, 2025, 1),
(12, 1, 14, 2000, 2015, 1),
(13, 1, 15, 2006, 2015, 1),
(14, 2, 14, 2000, 2026, 1),
(15, 1, 5, 2015, 2015, 1),
(16, 2, 19, 2005, 2008, 1),
(17, 2, 19, 2005, 2015, 1),
(18, 2, 19, 2005, 2015, 1),
(19, 2, 17, 2005, 2015, 1),
(20, 2, 17, 2005, 2015, 1),
(21, 2, 12, 2005, 2015, 1),
(22, 2, 17, 2005, 2015, 1),
(23, 2, 19, 2005, 2015, 1),
(24, 1, 19, 2005, 2015, 0),
(25, 2, 6, 2005, 2015, 0),
(26, 2, 12, 2005, 2015, 0),
(27, 2, 16, 2015, 2016, 1),
(28, 2, 2, 2016, 2016, 0),
(29, 2, 21, 5000, 5000, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `GraphType`
--

CREATE TABLE IF NOT EXISTS `GraphType` (
  `id` int(11) NOT NULL,
  `description` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `GraphType`
--

INSERT INTO `GraphType` (`id`, `description`) VALUES
(1, 'Barra'),
(2, 'Linea');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Measure`
--

CREATE TABLE IF NOT EXISTS `Measure` (
  `id` int(11) NOT NULL,
  `metorg` int(11) NOT NULL,
  `state` int(11) NOT NULL,
  `value` double DEFAULT NULL,
  `target` double DEFAULT NULL,
  `expected` double DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `updater` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dateup` datetime DEFAULT NULL,
  `validator` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dateval` datetime DEFAULT NULL,
  `old_value` double DEFAULT NULL,
  `old_target` double DEFAULT NULL,
  `old_expected` double DEFAULT NULL,
  `modified` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `Measure`
--

INSERT INTO `Measure` (`id`, `metorg`, `state`, `value`, `target`, `expected`, `year`, `updater`, `dateup`, `validator`, `dateval`, `old_value`, `old_target`, `old_expected`, `modified`) VALUES
(49, 14, 1, 6, 6, 9, 2001, '18.292.316-8', '2015-06-06 19:25:11', NULL, NULL, 6, 6, 9, 1),
(50, 1, 1, 153646, 2, 165, 2000, '18.292.316-8', '2015-06-06 19:14:18', NULL, NULL, 153646, 2, 165, 0),
(51, 2, 1, 164165, 414654, 1564, 2000, '18.292.316-8', '2015-06-06 19:14:19', NULL, NULL, 164165, 414654, 1564, 0),
(52, 14, 1, 30, 20, 50, 2010, '17.586.757-0', '2015-06-06 19:16:34', NULL, NULL, 20, 20, 50, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `MetOrg`
--

CREATE TABLE IF NOT EXISTS `MetOrg` (
  `id` int(11) NOT NULL,
  `org` int(11) NOT NULL,
  `metric` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `MetOrg`
--

INSERT INTO `MetOrg` (`id`, `org`, `metric`) VALUES
(17, 0, 20),
(15, 1, 18),
(1, 2, 1),
(2, 2, 2),
(6, 2, 9),
(12, 2, 15),
(16, 2, 19),
(5, 3, 4),
(14, 3, 17),
(13, 7, 16),
(21, 7, 24),
(19, 21, 22);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Metric`
--

CREATE TABLE IF NOT EXISTS `Metric` (
  `id` int(11) NOT NULL COMMENT 'Métricas que son creadas con sus respectivas unidades y cotas, para luego poder asociarlas a una  organización en el árbol e ingresar las mediciones.',
  `category` int(11) NOT NULL,
  `unit` int(11) NOT NULL,
  `name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `Metric`
--

INSERT INTO `Metric` (`id`, `category`, `unit`, `name`) VALUES
(1, 1, 1, 'egresados'),
(2, 1, 1, 'Ingresados'),
(4, 1, 1, 'alumnos destacados'),
(9, 1, 2, 'Pregrado'),
(15, 1, 9, 'Camila'),
(16, 1, 10, 'Lunes'),
(17, 2, 11, 'Sueldos'),
(18, 1, 12, 'La'),
(19, 1, 13, 'La'),
(20, 1, 14, 'Camil'),
(22, 1, 16, 'Uno'),
(24, 1, 20, 'Chico De Las Poesías'),
(25, 1, 23, 'Iohasfh');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Organization`
--

CREATE TABLE IF NOT EXISTS `Organization` (
  `id` int(11) NOT NULL COMMENT 'Organización tipo árbol de DCC/áreas/unidades',
  `parent` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL,
  `name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `Organization`
--

INSERT INTO `Organization` (`id`, `parent`, `type`, `name`) VALUES
(0, 0, 1, 'DCC'),
(1, 1, 2, 'DCC'),
(2, 1, 2, 'Pregrado'),
(3, 2, 2, 'docencia'),
(4, 2, 2, 'titulacion'),
(5, 2, 2, 'practicas profesionales'),
(7, 1, 2, 'Postgrado'),
(17, 0, 1, 'Computadores'),
(18, 0, 1, 'Camila'),
(21, 7, 2, 'Que tal');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `OrgType`
--

CREATE TABLE IF NOT EXISTS `OrgType` (
  `id` int(11) NOT NULL,
  `name` varchar(45) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `OrgType`
--

INSERT INTO `OrgType` (`id`, `name`) VALUES
(1, 'Soporte'),
(2, 'Operación'),
(3, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Permits`
--

CREATE TABLE IF NOT EXISTS `Permits` (
  `user` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `director` int(11) NOT NULL DEFAULT '0' COMMENT 'Director',
  `visualizer` int(11) NOT NULL DEFAULT '0' COMMENT 'Visualizador',
  `assistant_unidad` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Asistente de unidad',
  `in_charge_unidad` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'encargado unidad',
  `in_charge_unidad_finances` text COLLATE utf8_unicode_ci NOT NULL,
  `finances_assistant_unidad` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'asistente finanzas unidad',
  `dcc_assistant` int(11) NOT NULL DEFAULT '0' COMMENT 'asistente dcc'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `Permits`
--

INSERT INTO `Permits` (`user`, `director`, `visualizer`, `assistant_unidad`, `in_charge_unidad`, `in_charge_unidad_finances`, `finances_assistant_unidad`, `dcc_assistant`) VALUES
('1-1', 1, 0, '-1', '-1', '-1', '-1', 0),
('1-2', 0, 1, '-1', '-1', '-1', '-1', 0),
('1-3', 0, 0, '-1', '3', '-1', '-1', 0),
('1-4', 0, 0, '-1', '-1', '3', '-1', 0),
('1-5', 0, 0, '3', '-1', '-1', '-1', 0),
('1-6', 0, 0, '-1', '-1', '-1', '3', 0),
('1-7', 0, 0, '-1', '-1', '-1', '-1', 1),
('17.586.757-0', 0, 1, '-1', '-1', '3', '-1', 0),
('18.292.316-8', 1, 0, '-1', '-1', '-1', '-1', 0),
('18356296-7', 1, 1, '-1', '-1', '-1', '-1', 0),
('20.584.236-5', 0, 1, '-1', '-1', '-1', '-1', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `State`
--

CREATE TABLE IF NOT EXISTS `State` (
  `id` int(11) NOT NULL,
  `name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `State`
--

INSERT INTO `State` (`id`, `name`) VALUES
(0, 'no_validado'),
(1, 'validado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Unit`
--

CREATE TABLE IF NOT EXISTS `Unit` (
  `id` int(11) NOT NULL,
  `name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'tipos de unidades.\nej: nº de papers, $, nº alumnos, cursos, etc…'
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `Unit`
--

INSERT INTO `Unit` (`id`, `name`) VALUES
(1, 'alumnos por año'),
(2, 'Número Alumnos'),
(3, ''),
(4, 'Sajdl'),
(5, 'Sd'),
(8, 'Gaaa'),
(9, 'Lunes'),
(10, 'La'),
(11, 'Pesos Chilenos'),
(12, 'Lala'),
(13, 'Ladk'),
(14, 'Dias Trabajados'),
(15, 'M'),
(16, 'Numeros'),
(17, 'Kashfjk'),
(18, 'Jkhsfkj'),
(20, 'Número De Poemas'),
(21, 'Chao'),
(22, 'Pesos'),
(23, 'Ugdskuhv');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `User`
--

CREATE TABLE IF NOT EXISTS `User` (
  `id` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT 'RUT usuario',
  `name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `User`
--

INSERT INTO `User` (`id`, `name`) VALUES
('1-1', 'Director'),
('1-2', 'Visualizador'),
('1-3', 'Encargado de Docencia'),
('1-4', 'Encargado de Finanzas de Docencia'),
('1-5', 'Asistente de Docencia'),
('1-6', 'Asistente de Finanzas de Docencia'),
('1-7', 'Asistente DCC'),
('17.586.757-0', 'Catalina Alvarez'),
('18.292.316-8', 'Camila Alvarez'),
('18356296-7', ' '),
('20.584.236-5', 'Francisca Alvarez');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `Category`
--
ALTER TABLE `Category`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `Dashboard`
--
ALTER TABLE `Dashboard`
  ADD PRIMARY KEY (`id`,`org`),
  ADD KEY `fk_Dashboards_Tree-org1_idx` (`org`);

--
-- Indices de la tabla `GraphDash`
--
ALTER TABLE `GraphDash`
  ADD PRIMARY KEY (`id`,`dashboard`,`graphic`),
  ADD KEY `fk_graficoDashboard_Dashboards1_idx` (`dashboard`),
  ADD KEY `fk_graficoDashboard_graficos1_idx` (`graphic`);

--
-- Indices de la tabla `Graphic`
--
ALTER TABLE `Graphic`
  ADD PRIMARY KEY (`id`,`type`,`metorg`),
  ADD KEY `fk_graficos_graficos-tipos1_idx` (`type`),
  ADD KEY `fk_graficos_Metric-org1_idx` (`metorg`);

--
-- Indices de la tabla `GraphType`
--
ALTER TABLE `GraphType`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `Measure`
--
ALTER TABLE `Measure`
  ADD PRIMARY KEY (`id`,`metorg`,`state`),
  ADD KEY `fk_Mediciones_Metric-org1_idx` (`metorg`),
  ADD KEY `fk_Measure_State1_idx` (`state`);

--
-- Indices de la tabla `MetOrg`
--
ALTER TABLE `MetOrg`
  ADD PRIMARY KEY (`id`,`org`,`metric`),
  ADD KEY `fk_Metric-org_Tree-org1_idx` (`org`),
  ADD KEY `fk_Metric-org_Metrics1_idx` (`metric`);

--
-- Indices de la tabla `Metric`
--
ALTER TABLE `Metric`
  ADD PRIMARY KEY (`id`,`category`,`unit`),
  ADD KEY `fk_Metrics_Metric-cat1_idx` (`category`),
  ADD KEY `fk_Metrics_Metric-units1_idx` (`unit`);

--
-- Indices de la tabla `Organization`
--
ALTER TABLE `Organization`
  ADD PRIMARY KEY (`id`,`parent`,`type`),
  ADD KEY `fk_Tree-org_Tree-org_idx` (`parent`),
  ADD KEY `fk_Tree-org_Tree_Tipo1_idx` (`type`);

--
-- Indices de la tabla `OrgType`
--
ALTER TABLE `OrgType`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `Permits`
--
ALTER TABLE `Permits`
  ADD PRIMARY KEY (`user`);

--
-- Indices de la tabla `State`
--
ALTER TABLE `State`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `Unit`
--
ALTER TABLE `Unit`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `Category`
--
ALTER TABLE `Category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Categorías en las que se agrupan las métricas',AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `Dashboard`
--
ALTER TABLE `Dashboard`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT de la tabla `GraphDash`
--
ALTER TABLE `GraphDash`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT de la tabla `Graphic`
--
ALTER TABLE `Graphic`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT de la tabla `GraphType`
--
ALTER TABLE `GraphType`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `Measure`
--
ALTER TABLE `Measure`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=53;
--
-- AUTO_INCREMENT de la tabla `MetOrg`
--
ALTER TABLE `MetOrg`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT de la tabla `Metric`
--
ALTER TABLE `Metric`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Métricas que son creadas con sus respectivas unidades y cotas, para luego poder asociarlas a una  organización en el árbol e ingresar las mediciones.',AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT de la tabla `Organization`
--
ALTER TABLE `Organization`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Organización tipo árbol de DCC/áreas/unidades',AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT de la tabla `Unit`
--
ALTER TABLE `Unit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=24;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `Dashboard`
--
ALTER TABLE `Dashboard`
  ADD CONSTRAINT `fk_dash_org_id` FOREIGN KEY (`org`) REFERENCES `Organization` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `GraphDash`
--
ALTER TABLE `GraphDash`
  ADD CONSTRAINT `fk_graficoDashboard_Dashboards1` FOREIGN KEY (`dashboard`) REFERENCES `Dashboard` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_graficoDashboard_graficos1` FOREIGN KEY (`graphic`) REFERENCES `Graphic` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `Graphic`
--
ALTER TABLE `Graphic`
  ADD CONSTRAINT `fk_graficos_Metric-org1` FOREIGN KEY (`metorg`) REFERENCES `MetOrg` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_graficos_graficos-tipos1` FOREIGN KEY (`type`) REFERENCES `GraphType` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `Measure`
--
ALTER TABLE `Measure`
  ADD CONSTRAINT `fk_Measure_State1` FOREIGN KEY (`state`) REFERENCES `State` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Mediciones_Metric-org1` FOREIGN KEY (`metorg`) REFERENCES `MetOrg` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `MetOrg`
--
ALTER TABLE `MetOrg`
  ADD CONSTRAINT `fk_Metric-org_Metrics1` FOREIGN KEY (`metric`) REFERENCES `Metric` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Metric-org_Tree-org` FOREIGN KEY (`org`) REFERENCES `Organization` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `Metric`
--
ALTER TABLE `Metric`
  ADD CONSTRAINT `fk_Metrics_Metric-cat1` FOREIGN KEY (`category`) REFERENCES `Category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Metrics_Metric-units1` FOREIGN KEY (`unit`) REFERENCES `Unit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `Organization`
--
ALTER TABLE `Organization`
  ADD CONSTRAINT `fk_Tree-org_Tree-org` FOREIGN KEY (`parent`) REFERENCES `Organization` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Tree-org_Tree_Tipo1` FOREIGN KEY (`type`) REFERENCES `OrgType` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `Permits`
--
ALTER TABLE `Permits`
  ADD CONSTRAINT `fk_User_Permits` FOREIGN KEY (`user`) REFERENCES `User` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
