-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 02, 2016 at 04:55 
-- Server version: 10.1.9-MariaDB
-- PHP Version: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `UDashboard`
--

-- --------------------------------------------------------

--
-- Table structure for table `Category`
--

CREATE TABLE `Category` (
  `id` int(11) NOT NULL COMMENT 'Categorías en las que se agrupan las métricas',
  `name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Category`
--

INSERT INTO `Category` (`id`, `name`) VALUES
(1, 'Productividad'),
(2, 'Finanzas');

-- --------------------------------------------------------

--
-- Table structure for table `Dashboard`
--

CREATE TABLE `Dashboard` (
  `id` int(11) NOT NULL,
  `org` int(11) NOT NULL,
  `title` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `GraphDash`
--

CREATE TABLE `GraphDash` (
  `id` int(11) NOT NULL,
  `dashboard` int(11) NOT NULL,
  `graphic` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Graphic`
--

CREATE TABLE `Graphic` (
  `id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `metorg` int(11) NOT NULL,
  `min_year` int(11) NOT NULL,
  `max_year` int(11) NOT NULL,
  `position` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `GraphType`
--

CREATE TABLE `GraphType` (
  `id` int(11) NOT NULL,
  `description` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `GraphType`
--

INSERT INTO `GraphType` (`id`, `description`) VALUES
(1, 'Barra'),
(2, 'Línea');

-- --------------------------------------------------------

--
-- Table structure for table `Measure`
--

CREATE TABLE `Measure` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `MetOrg`
--

CREATE TABLE `MetOrg` (
  `id` int(11) NOT NULL,
  `org` int(11) NOT NULL,
  `metric` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Metric`
--

CREATE TABLE `Metric` (
  `id` int(11) NOT NULL COMMENT 'Métricas que son creadas con sus respectivas unidades y cotas, para luego poder asociarlas a una  organización en el árbol e ingresar las mediciones.',
  `category` int(11) NOT NULL,
  `unit` int(11) NOT NULL,
  `name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Organization`
--

CREATE TABLE `Organization` (
  `id` int(11) NOT NULL COMMENT 'Organización tipo árbol de DCC/áreas/unidades',
  `parent` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL,
  `name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Organization`
--

INSERT INTO `Organization` (`id`, `parent`, `type`, `name`) VALUES
(0, 0, 1, 'DCC'),
(1, 1, 2, 'DCC'),
(2, 1, 2, 'Pregrado'),
(3, 1, 2, 'Postgrado'),
(4, 1, 2, 'Extensión'),
(5, 1, 2, 'Investigación'),
(6, 2, 2, 'Titulación'),
(7, 2, 2, 'Prácticas Profesionales'),
(8, 2, 2, 'Docencia'),
(9, 3, 2, 'MTI'),
(10, 3, 2, 'MSc'),
(11, 3, 2, 'PostDoct'),
(12, 3, 2, 'Doctorado'),
(13, 4, 2, 'PEC'),
(14, 4, 2, 'NIC'),
(15, 4, 2, 'Proyectos Externos'),
(16, 5, 2, 'Proyectos'),
(17, 5, 2, 'Publicaciones'),
(18, 5, 2, 'Redes'),
(19, 0, 1, 'Gestión'),
(20, 0, 1, 'Sistemas'),
(21, 0, 1, 'Infraestructura'),
(22, 0, 1, 'Comunicaciones'),
(23, 22, 1, 'Interna'),
(24, 22, 1, 'Externa'),
(25, 21, 1, 'Inventario'),
(26, 21, 1, 'Mantenimiento'),
(27, 19, 1, 'Administración'),
(28, 19, 1, 'Adquisición'),
(29, 19, 1, 'Finanzas'),
(30, 19, 1, 'RRHH'),
(31, 20, 1, 'Plataforma TI'),
(32, 20, 1, 'Aplicativos'),
(33, 20, 1, 'Memoria Organizacional');

-- --------------------------------------------------------

--
-- Table structure for table `OrgType`
--

CREATE TABLE `OrgType` (
  `id` int(11) NOT NULL,
  `name` varchar(45) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `OrgType`
--

INSERT INTO `OrgType` (`id`, `name`) VALUES
(1, 'Soporte'),
(2, 'Operación');

-- --------------------------------------------------------

--
-- Table structure for table `Permits`
--

CREATE TABLE `Permits` (
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
-- Dumping data for table `Permits`
--

INSERT INTO `Permits` (`user`, `director`, `visualizer`, `assistant_unidad`, `in_charge_unidad`, `in_charge_unidad_finances`, `finances_assistant_unidad`, `dcc_assistant`) VALUES
('183562967', 1, 0, '', '', '', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `State`
--

CREATE TABLE `State` (
  `id` int(11) NOT NULL,
  `name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `State`
--

INSERT INTO `State` (`id`, `name`) VALUES
(-1, 'por_borrar'),
(0, 'no_validado'),
(1, 'validado');

-- --------------------------------------------------------

--
-- Table structure for table `Unit`
--

CREATE TABLE `Unit` (
  `id` int(11) NOT NULL,
  `name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'tipos de unidades.\nej: nº de papers, $, nº alumnos, cursos, etc…'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
  `id` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT 'RUT usuario',
  `name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`id`, `name`) VALUES
('183562967', 'Felipe Rodríguez');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Category`
--
ALTER TABLE `Category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Dashboard`
--
ALTER TABLE `Dashboard`
  ADD PRIMARY KEY (`id`,`org`),
  ADD KEY `fk_Dashboards_Tree-org1_idx` (`org`);

--
-- Indexes for table `GraphDash`
--
ALTER TABLE `GraphDash`
  ADD PRIMARY KEY (`id`,`dashboard`,`graphic`),
  ADD KEY `fk_graficoDashboard_Dashboards1_idx` (`dashboard`),
  ADD KEY `fk_graficoDashboard_graficos1_idx` (`graphic`);

--
-- Indexes for table `Graphic`
--
ALTER TABLE `Graphic`
  ADD PRIMARY KEY (`id`,`type`,`metorg`),
  ADD KEY `fk_graficos_graficos-tipos1_idx` (`type`),
  ADD KEY `fk_graficos_Metric-org1_idx` (`metorg`);

--
-- Indexes for table `GraphType`
--
ALTER TABLE `GraphType`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Measure`
--
ALTER TABLE `Measure`
  ADD PRIMARY KEY (`id`,`metorg`,`state`),
  ADD KEY `fk_Mediciones_Metric-org1_idx` (`metorg`),
  ADD KEY `fk_Measure_State1_idx` (`state`);

--
-- Indexes for table `MetOrg`
--
ALTER TABLE `MetOrg`
  ADD PRIMARY KEY (`id`,`org`,`metric`),
  ADD KEY `fk_Metric-org_Tree-org1_idx` (`org`),
  ADD KEY `fk_Metric-org_Metrics1_idx` (`metric`);

--
-- Indexes for table `Metric`
--
ALTER TABLE `Metric`
  ADD PRIMARY KEY (`id`,`category`,`unit`),
  ADD KEY `fk_Metrics_Metric-cat1_idx` (`category`),
  ADD KEY `fk_Metrics_Metric-units1_idx` (`unit`);

--
-- Indexes for table `Organization`
--
ALTER TABLE `Organization`
  ADD PRIMARY KEY (`id`,`parent`,`type`),
  ADD KEY `fk_Tree-org_Tree-org_idx` (`parent`),
  ADD KEY `fk_Tree-org_Tree_Tipo1_idx` (`type`);

--
-- Indexes for table `OrgType`
--
ALTER TABLE `OrgType`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Permits`
--
ALTER TABLE `Permits`
  ADD PRIMARY KEY (`user`);

--
-- Indexes for table `State`
--
ALTER TABLE `State`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Unit`
--
ALTER TABLE `Unit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Category`
--
ALTER TABLE `Category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Categorías en las que se agrupan las métricas', AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `Dashboard`
--
ALTER TABLE `Dashboard`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `GraphDash`
--
ALTER TABLE `GraphDash`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Graphic`
--
ALTER TABLE `Graphic`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `GraphType`
--
ALTER TABLE `GraphType`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `Measure`
--
ALTER TABLE `Measure`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `MetOrg`
--
ALTER TABLE `MetOrg`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Metric`
--
ALTER TABLE `Metric`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Métricas que son creadas con sus respectivas unidades y cotas, para luego poder asociarlas a una  organización en el árbol e ingresar las mediciones.';
--
-- AUTO_INCREMENT for table `Organization`
--
ALTER TABLE `Organization`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Organización tipo árbol de DCC/áreas/unidades', AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT for table `Unit`
--
ALTER TABLE `Unit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `Dashboard`
--
ALTER TABLE `Dashboard`
  ADD CONSTRAINT `fk_dash_org_id` FOREIGN KEY (`org`) REFERENCES `Organization` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `GraphDash`
--
ALTER TABLE `GraphDash`
  ADD CONSTRAINT `fk_graficoDashboard_Dashboards1` FOREIGN KEY (`dashboard`) REFERENCES `Dashboard` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_graficoDashboard_graficos1` FOREIGN KEY (`graphic`) REFERENCES `Graphic` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Graphic`
--
ALTER TABLE `Graphic`
  ADD CONSTRAINT `fk_graficos_Metric-org1` FOREIGN KEY (`metorg`) REFERENCES `MetOrg` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_graficos_graficos-tipos1` FOREIGN KEY (`type`) REFERENCES `GraphType` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Measure`
--
ALTER TABLE `Measure`
  ADD CONSTRAINT `fk_Measure_State1` FOREIGN KEY (`state`) REFERENCES `State` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Mediciones_Metric-org1` FOREIGN KEY (`metorg`) REFERENCES `MetOrg` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `MetOrg`
--
ALTER TABLE `MetOrg`
  ADD CONSTRAINT `fk_Metric-org_Metrics1` FOREIGN KEY (`metric`) REFERENCES `Metric` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Metric-org_Tree-org` FOREIGN KEY (`org`) REFERENCES `Organization` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Metric`
--
ALTER TABLE `Metric`
  ADD CONSTRAINT `fk_Metrics_Metric-cat1` FOREIGN KEY (`category`) REFERENCES `Category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Metrics_Metric-units1` FOREIGN KEY (`unit`) REFERENCES `Unit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Organization`
--
ALTER TABLE `Organization`
  ADD CONSTRAINT `fk_Tree-org_Tree-org` FOREIGN KEY (`parent`) REFERENCES `Organization` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Tree-org_Tree_Tipo1` FOREIGN KEY (`type`) REFERENCES `OrgType` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Permits`
--
ALTER TABLE `Permits`
  ADD CONSTRAINT `fk_User_Permits` FOREIGN KEY (`user`) REFERENCES `User` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
