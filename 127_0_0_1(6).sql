-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-05-2015 a las 21:11:14
-- Versión del servidor: 5.6.21
-- Versión de PHP: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `udashboard`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `category`
--

CREATE TABLE IF NOT EXISTS `category` (
`id` int(11) NOT NULL COMMENT 'Categorías en las que se agrupan las métricas',
  `name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'Productividad'),
(2, 'Finanzas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dashboard`
--

CREATE TABLE IF NOT EXISTS `dashboard` (
`id` int(11) NOT NULL,
  `org` int(11) NOT NULL,
  `title` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `dashboard`
--

INSERT INTO `dashboard` (`id`, `org`, `title`) VALUES
(1, 2, 'Dashboard Pregrado'),
(6, 3, 'Dashboard docencia'),
(7, 1, 'Dashboard DCC'),
(8, 7, 'Dashboard Que tal');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `graphdash`
--

CREATE TABLE IF NOT EXISTS `graphdash` (
`id` int(11) NOT NULL,
  `dashboard` int(11) NOT NULL,
  `graphic` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `graphdash`
--

INSERT INTO `graphdash` (`id`, `dashboard`, `graphic`) VALUES
(1, 1, 1),
(2, 1, 2),
(5, 1, 11),
(8, 1, 14),
(3, 6, 9),
(6, 6, 12),
(7, 7, 13),
(9, 7, 15),
(11, 7, 16),
(10, 8, 16);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `graphic`
--

CREATE TABLE IF NOT EXISTS `graphic` (
`id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `metorg` int(11) NOT NULL,
  `min_year` int(11) NOT NULL,
  `max_year` int(11) NOT NULL,
  `position` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `graphic`
--

INSERT INTO `graphic` (`id`, `type`, `metorg`, `min_year`, `max_year`, `position`) VALUES
(1, 2, 1, 2013, 2015, 1),
(2, 1, 2, 2013, 2015, 0),
(9, 1, 5, 2000, 2015, 0),
(11, 2, 5, 2008, 2015, 1),
(12, 1, 14, 2000, 2015, 1),
(13, 1, 15, 2006, 2015, 0),
(14, 2, 14, 2000, 2026, 1),
(15, 1, 5, 2015, 2015, 1),
(16, 2, 19, 2005, 2015, 1),
(17, 2, 19, 2005, 2015, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `graphtype`
--

CREATE TABLE IF NOT EXISTS `graphtype` (
`id` int(11) NOT NULL,
  `description` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `graphtype`
--

INSERT INTO `graphtype` (`id`, `description`) VALUES
(1, 'Barra'),
(2, 'Linea');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `measure`
--

CREATE TABLE IF NOT EXISTS `measure` (
`id` int(11) NOT NULL,
  `metorg` int(11) NOT NULL,
  `state` int(11) NOT NULL,
  `value` double DEFAULT NULL,
  `target` int(11) DEFAULT NULL,
  `expected` int(11) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `updater` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dateup` datetime DEFAULT NULL,
  `validator` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dateval` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `measure`
--

INSERT INTO `measure` (`id`, `metorg`, `state`, `value`, `target`, `expected`, `year`, `updater`, `dateup`, `validator`, `dateval`) VALUES
(1, 1, 1, 20, 11, 20, 2015, '18.292.316-8', '2015-05-15 21:32:12', NULL, NULL),
(2, 1, 1, 11, 12, 13, 2014, NULL, NULL, NULL, NULL),
(3, 1, 1, 12, 13, 14, 2013, '18.292.316-8', '2015-05-09 21:37:42', NULL, NULL),
(4, 1, 1, 15, 20, 23, 2010, '18.292.316-8', '2015-05-09 18:11:53', NULL, NULL),
(5, 1, 1, 20, 25, 30, 2012, '18.292.316-8', '2015-05-10 10:49:17', NULL, NULL),
(10, 2, 1, 15, 10, 30, 2012, '18.292.316-8', '2015-05-10 10:49:17', NULL, NULL),
(26, 2, 1, 20, 21, 22, 2013, '18.292.316-8', '2015-05-10 11:52:20', NULL, NULL),
(27, 2, 1, 15, 20, 0, 2015, '18.292.316-8', '2015-05-10 11:25:57', NULL, NULL),
(30, 5, 0, 15, 25, 30, 2000, '20.584.236-5', '2015-05-20 20:36:00', NULL, NULL),
(33, 5, 1, 200, 200, 200, 2015, '18.292.316-8', '2015-05-11 10:29:45', NULL, NULL),
(34, 5, 0, 200, 200, 200, 2014, '18.292.316-8', '2015-05-11 10:30:06', NULL, NULL),
(35, 5, 1, 20, 10, 20, 2013, '18.292.316-8', '2015-05-13 00:02:03', NULL, NULL),
(36, 14, 1, 15, 20, 30, 2000, '18.292.316-8', '2015-05-17 20:12:55', NULL, NULL),
(37, 1, 1, 15, 20, 25, 2016, '18.292.316-8', '2015-05-20 14:53:49', NULL, NULL),
(38, 2, 1, 23, 23, 25, 2016, '18.292.316-8', '2015-05-20 14:53:49', NULL, NULL),
(39, 16, 1, 0, 20, 15, 2016, '18.292.316-8', '2015-05-20 14:54:15', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metorg`
--

CREATE TABLE IF NOT EXISTS `metorg` (
`id` int(11) NOT NULL,
  `org` int(11) NOT NULL,
  `metric` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `metorg`
--

INSERT INTO `metorg` (`id`, `org`, `metric`) VALUES
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
(19, 21, 22);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metric`
--

CREATE TABLE IF NOT EXISTS `metric` (
`id` int(11) NOT NULL COMMENT 'Métricas que son creadas con sus respectivas unidades y cotas, para luego poder asociarlas a una  organización en el árbol e ingresar las mediciones.',
  `category` int(11) NOT NULL,
  `unit` int(11) NOT NULL,
  `name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `metric`
--

INSERT INTO `metric` (`id`, `category`, `unit`, `name`) VALUES
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
(22, 1, 16, 'Uno');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `organization`
--

CREATE TABLE IF NOT EXISTS `organization` (
`id` int(11) NOT NULL COMMENT 'Organización tipo árbol de DCC/áreas/unidades',
  `parent` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL,
  `name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `organization`
--

INSERT INTO `organization` (`id`, `parent`, `type`, `name`) VALUES
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
-- Estructura de tabla para la tabla `orgtype`
--

CREATE TABLE IF NOT EXISTS `orgtype` (
  `id` int(11) NOT NULL,
  `name` varchar(45) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `orgtype`
--

INSERT INTO `orgtype` (`id`, `name`) VALUES
(1, 'Soporte'),
(2, 'Operación'),
(3, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permits`
--

CREATE TABLE IF NOT EXISTS `permits` (
  `user` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `director` int(11) NOT NULL DEFAULT '0' COMMENT 'Director',
  `visualizer` int(11) NOT NULL DEFAULT '0' COMMENT 'Visualizador',
  `assistant_unidad` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Asistente de unidad',
  `in_charge_unidad` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'encargado unidad',
  `finances_assistant_unidad` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'asistente finanzas unidad',
  `dcc_assistant` int(11) NOT NULL DEFAULT '0' COMMENT 'asistente dcc'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `permits`
--

INSERT INTO `permits` (`user`, `director`, `visualizer`, `assistant_unidad`, `in_charge_unidad`, `finances_assistant_unidad`, `dcc_assistant`) VALUES
('17.586.757-0', 0, 1, '-1', '-1', '-1', 0),
('18.292.316-8', 1, 0, '-1', '-1', '-1', 0),
('20.584.236-5', 0, 1, '3 4', '-1', '-1', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `state`
--

CREATE TABLE IF NOT EXISTS `state` (
  `id` int(11) NOT NULL,
  `name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `state`
--

INSERT INTO `state` (`id`, `name`) VALUES
(0, 'no_validado'),
(1, 'validado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unit`
--

CREATE TABLE IF NOT EXISTS `unit` (
`id` int(11) NOT NULL,
  `name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'tipos de unidades.\nej: nº de papers, $, nº alumnos, cursos, etc…'
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `unit`
--

INSERT INTO `unit` (`id`, `name`) VALUES
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
(18, 'Jkhsfkj');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT 'RUT usuario',
  `name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`id`, `name`) VALUES
('17.586.757-0', 'Catalina Alvarez'),
('18.292.316-8', 'Camila Alvarez'),
('20.584.236-5', 'Francisca Alvarez');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `category`
--
ALTER TABLE `category`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `dashboard`
--
ALTER TABLE `dashboard`
 ADD PRIMARY KEY (`id`,`org`), ADD KEY `fk_Dashboards_Tree-org1_idx` (`org`);

--
-- Indices de la tabla `graphdash`
--
ALTER TABLE `graphdash`
 ADD PRIMARY KEY (`id`,`dashboard`,`graphic`), ADD KEY `fk_graficoDashboard_Dashboards1_idx` (`dashboard`), ADD KEY `fk_graficoDashboard_graficos1_idx` (`graphic`);

--
-- Indices de la tabla `graphic`
--
ALTER TABLE `graphic`
 ADD PRIMARY KEY (`id`,`type`,`metorg`), ADD KEY `fk_graficos_graficos-tipos1_idx` (`type`), ADD KEY `fk_graficos_Metric-org1_idx` (`metorg`);

--
-- Indices de la tabla `graphtype`
--
ALTER TABLE `graphtype`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `measure`
--
ALTER TABLE `measure`
 ADD PRIMARY KEY (`id`,`metorg`,`state`), ADD KEY `fk_Mediciones_Metric-org1_idx` (`metorg`), ADD KEY `fk_Measure_State1_idx` (`state`);

--
-- Indices de la tabla `metorg`
--
ALTER TABLE `metorg`
 ADD PRIMARY KEY (`id`,`org`,`metric`), ADD KEY `fk_Metric-org_Tree-org1_idx` (`org`), ADD KEY `fk_Metric-org_Metrics1_idx` (`metric`);

--
-- Indices de la tabla `metric`
--
ALTER TABLE `metric`
 ADD PRIMARY KEY (`id`,`category`,`unit`), ADD KEY `fk_Metrics_Metric-cat1_idx` (`category`), ADD KEY `fk_Metrics_Metric-units1_idx` (`unit`);

--
-- Indices de la tabla `organization`
--
ALTER TABLE `organization`
 ADD PRIMARY KEY (`id`,`parent`,`type`), ADD KEY `fk_Tree-org_Tree-org_idx` (`parent`), ADD KEY `fk_Tree-org_Tree_Tipo1_idx` (`type`);

--
-- Indices de la tabla `orgtype`
--
ALTER TABLE `orgtype`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `permits`
--
ALTER TABLE `permits`
 ADD PRIMARY KEY (`user`);

--
-- Indices de la tabla `state`
--
ALTER TABLE `state`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `unit`
--
ALTER TABLE `unit`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `category`
--
ALTER TABLE `category`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Categorías en las que se agrupan las métricas',AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `dashboard`
--
ALTER TABLE `dashboard`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT de la tabla `graphdash`
--
ALTER TABLE `graphdash`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT de la tabla `graphic`
--
ALTER TABLE `graphic`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT de la tabla `graphtype`
--
ALTER TABLE `graphtype`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `measure`
--
ALTER TABLE `measure`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=40;
--
-- AUTO_INCREMENT de la tabla `metorg`
--
ALTER TABLE `metorg`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT de la tabla `metric`
--
ALTER TABLE `metric`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Métricas que son creadas con sus respectivas unidades y cotas, para luego poder asociarlas a una  organización en el árbol e ingresar las mediciones.',AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT de la tabla `organization`
--
ALTER TABLE `organization`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Organización tipo árbol de DCC/áreas/unidades',AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT de la tabla `unit`
--
ALTER TABLE `unit`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `dashboard`
--
ALTER TABLE `dashboard`
ADD CONSTRAINT `fk_dash_org_id` FOREIGN KEY (`org`) REFERENCES `organization` (`id`);

--
-- Filtros para la tabla `graphdash`
--
ALTER TABLE `graphdash`
ADD CONSTRAINT `fk_graficoDashboard_Dashboards1` FOREIGN KEY (`dashboard`) REFERENCES `dashboard` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_graficoDashboard_graficos1` FOREIGN KEY (`graphic`) REFERENCES `graphic` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `graphic`
--
ALTER TABLE `graphic`
ADD CONSTRAINT `fk_graficos_Metric-org1` FOREIGN KEY (`metorg`) REFERENCES `metorg` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_graficos_graficos-tipos1` FOREIGN KEY (`type`) REFERENCES `graphtype` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `measure`
--
ALTER TABLE `measure`
ADD CONSTRAINT `fk_Measure_State1` FOREIGN KEY (`state`) REFERENCES `state` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_Mediciones_Metric-org1` FOREIGN KEY (`metorg`) REFERENCES `metorg` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `metorg`
--
ALTER TABLE `metorg`
ADD CONSTRAINT `fk_Metric-org_Metrics1` FOREIGN KEY (`metric`) REFERENCES `metric` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_Metric-org_Tree-org` FOREIGN KEY (`org`) REFERENCES `organization` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `metric`
--
ALTER TABLE `metric`
ADD CONSTRAINT `fk_Metrics_Metric-cat1` FOREIGN KEY (`category`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_Metrics_Metric-units1` FOREIGN KEY (`unit`) REFERENCES `unit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `organization`
--
ALTER TABLE `organization`
ADD CONSTRAINT `fk_Tree-org_Tree-org` FOREIGN KEY (`parent`) REFERENCES `organization` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_Tree-org_Tree_Tipo1` FOREIGN KEY (`type`) REFERENCES `orgtype` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `permits`
--
ALTER TABLE `permits`
ADD CONSTRAINT `fk_User_Permits` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
