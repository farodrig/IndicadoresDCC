-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 26, 2016 at 03:31 
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
CREATE DATABASE IF NOT EXISTS `UDashboard` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `UDashboard`;

-- --------------------------------------------------------

--
-- Table structure for table `Action`
--

CREATE TABLE `Action` (
  `id` int(11) NOT NULL,
  `goal` int(11) NOT NULL,
  `userInCharge` varchar(15) CHARACTER SET utf8 NOT NULL,
  `status` int(11) NOT NULL,
  `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `expected_result` text COLLATE utf8_unicode_ci NOT NULL,
  `current_result` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Aggregation_Type`
--

CREATE TABLE `Aggregation_Type` (
  `id` int(11) NOT NULL,
  `name` varchar(45) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Aggregation_Type`
--

INSERT INTO `Aggregation_Type` (`id`, `name`) VALUES
(0, ''),
(1, 'Suma'),
(2, 'Promedio'),
(3, 'Máximo'),
(4, 'Mínimo');

-- --------------------------------------------------------

--
-- Table structure for table `Category`
--

CREATE TABLE `Category` (
  `id` int(11) NOT NULL COMMENT 'Categorías en las que se agrupan las métricas',
  `name` varchar(45) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Category`
--

INSERT INTO `Category` (`id`, `name`) VALUES
(1, 'Productividad'),
(2, 'Finanzas');

-- --------------------------------------------------------

--
-- Table structure for table `Collaborator`
--

CREATE TABLE `Collaborator` (
  `strategy` int(11) NOT NULL,
  `user` varchar(15) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Completion_Status`
--

CREATE TABLE `Completion_Status` (
  `id` int(11) NOT NULL,
  `status` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Completion_Status`
--

INSERT INTO `Completion_Status` (`id`, `status`) VALUES
(1, 'Finalizado'),
(2, 'Realizando'),
(3, 'Fuera de Plazo');

-- --------------------------------------------------------

--
-- Table structure for table `Dashboard`
--

CREATE TABLE `Dashboard` (
  `id` int(11) NOT NULL,
  `org` int(11) NOT NULL,
  `title` varchar(45) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `FODA`
--

CREATE TABLE `FODA` (
  `id` int(11) NOT NULL,
  `org` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `validated` tinyint(1) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `FODA_Type`
--

CREATE TABLE `FODA_Type` (
  `id` int(11) NOT NULL,
  `name` varchar(45) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `FODA_Type`
--

INSERT INTO `FODA_Type` (`id`, `name`) VALUES
(1, 'Fortalezas'),
(2, 'Oportunidades'),
(3, 'Debilidades'),
(4, 'Amenazas');

-- --------------------------------------------------------

--
-- Table structure for table `Function`
--

CREATE TABLE `Function` (
  `id` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `short_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Goal`
--

CREATE TABLE `Goal` (
  `id` int(11) NOT NULL,
  `strategy` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `userInCharge` varchar(15) CHARACTER SET utf8 NOT NULL,
  `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `validated` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp` timestamp NULL DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `comment` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Goal_Item`
--

CREATE TABLE `Goal_Item` (
  `goal` int(11) NOT NULL,
  `item` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Graphic`
--

CREATE TABLE `Graphic` (
  `id` int(11) NOT NULL,
  `dashboard` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `see_x` tinyint(1) NOT NULL DEFAULT '0',
  `min_year` int(11) NOT NULL,
  `max_year` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `display` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Item`
--

CREATE TABLE `Item` (
  `id` int(11) NOT NULL,
  `foda` int(11) NOT NULL,
  `priority` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci
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
  `name` varchar(100) COLLATE utf8_turkish_ci NOT NULL DEFAULT '',
  `y_unit` int(11) NOT NULL,
  `y_name` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `x_unit` int(11) NOT NULL,
  `x_name` varchar(45) COLLATE utf8_turkish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Dumping data for table `Metric`
--

INSERT INTO `Metric` (`id`, `category`, `name`, `y_unit`, `y_name`, `x_unit`, `x_name`) VALUES
(1, 2, 'Gasto Anual', 1, 'Gastos', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `Organization`
--

CREATE TABLE `Organization` (
  `id` int(11) NOT NULL COMMENT 'Organización tipo árbol de DCC/áreas/unidades',
  `parent` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL,
  `name` varchar(45) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Organization`
--

INSERT INTO `Organization` (`id`, `parent`, `type`, `name`) VALUES
(0, 0, 1, 'DCC'),
(1, 1, 2, 'DCC');

-- --------------------------------------------------------

--
-- Table structure for table `OrgType`
--

CREATE TABLE `OrgType` (
  `id` int(11) NOT NULL,
  `name` varchar(45) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `OrgType`
--

INSERT INTO `OrgType` (`id`, `name`) VALUES
(1, 'Soporte'),
(2, 'Operación');

-- --------------------------------------------------------

--
-- Table structure for table `Permit`
--

CREATE TABLE `Permit` (
  `id` int(11) NOT NULL,
  `org` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `resource` int(11) NOT NULL,
  `view` tinyint(1) NOT NULL DEFAULT '0',
  `edit` tinyint(1) NOT NULL DEFAULT '0',
  `validate` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Position`
--

CREATE TABLE `Position` (
  `id` int(11) NOT NULL,
  `org` int(11) NOT NULL,
  `short_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Priority`
--

CREATE TABLE `Priority` (
  `id` int(11) NOT NULL,
  `name` varchar(45) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='High, Medium, Low';

--
-- Dumping data for table `Priority`
--

INSERT INTO `Priority` (`id`, `name`) VALUES
(1, 'Alta'),
(2, 'Media'),
(3, 'Baja');

-- --------------------------------------------------------

--
-- Table structure for table `Resource`
--

CREATE TABLE `Resource` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Resource`
--

INSERT INTO `Resource` (`id`, `name`) VALUES
(1, 'FODA, plan estratégico y valor de métricas productivas'),
(2, 'objetivo y meta de métricas productivas'),
(3, 'valor de métricas financieras'),
(4, 'objetivo y meta de métricas financieras'),
(5, 'posiciones'),
(6, 'configuración'),
(7, 'permisos');

-- --------------------------------------------------------

--
-- Table structure for table `Role`
--

CREATE TABLE `Role` (
  `user` varchar(15) CHARACTER SET utf8 NOT NULL,
  `position` int(11) NOT NULL,
  `initial_date` date NOT NULL,
  `final_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Serie`
--

CREATE TABLE `Serie` (
  `id` int(11) NOT NULL,
  `metorg` int(11) NOT NULL,
  `graphic` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `year_aggregation` int(11) NOT NULL,
  `x_aggregation` int(11) NOT NULL,
  `color` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Serie_Type`
--

CREATE TABLE `Serie_Type` (
  `id` int(11) NOT NULL,
  `name` varchar(45) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Serie_Type`
--

INSERT INTO `Serie_Type` (`id`, `name`) VALUES
(1, 'Barra'),
(2, 'Linea');

-- --------------------------------------------------------

--
-- Table structure for table `State`
--

CREATE TABLE `State` (
  `id` int(11) NOT NULL,
  `name` varchar(45) CHARACTER SET utf8 DEFAULT NULL
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
-- Table structure for table `Strategic_Plan`
--

CREATE TABLE `Strategic_Plan` (
  `id` int(11) NOT NULL,
  `org` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `validated` tinyint(1) NOT NULL DEFAULT '0',
  `deadline` date DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Unit`
--

CREATE TABLE `Unit` (
  `id` int(11) NOT NULL,
  `name` varchar(45) CHARACTER SET utf8 DEFAULT NULL COMMENT 'tipos de unidades.\nej: nº de papers, $, nº alumnos, cursos, etc…'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Unit`
--

INSERT INTO `Unit` (`id`, `name`) VALUES
(0, ''),
(1, 'Pesos');

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
  `id` varchar(15) CHARACTER SET utf8 NOT NULL COMMENT 'RUT usuario',
  `name` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Value`
--

CREATE TABLE `Value` (
  `id` int(11) NOT NULL,
  `metorg` int(11) NOT NULL,
  `state` int(11) NOT NULL,
  `updater` varchar(15) CHARACTER SET utf8 NOT NULL,
  `validator` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  `value` double DEFAULT NULL,
  `x_value` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `target` double DEFAULT NULL,
  `expected` double DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `dateup` datetime DEFAULT NULL,
  `dateval` datetime DEFAULT NULL,
  `proposed_value` double DEFAULT NULL,
  `proposed_target` double DEFAULT NULL,
  `proposed_expected` double DEFAULT NULL,
  `proposed_x_value` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Action`
--
ALTER TABLE `Action`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Action_User1_idx` (`userInCharge`),
  ADD KEY `fk_Action_Completion_Status1_idx` (`status`),
  ADD KEY `fk_Action_Target1` (`goal`);

--
-- Indexes for table `Aggregation_Type`
--
ALTER TABLE `Aggregation_Type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Category`
--
ALTER TABLE `Category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Collaborator`
--
ALTER TABLE `Collaborator`
  ADD PRIMARY KEY (`strategy`,`user`),
  ADD KEY `fk_Strategy_has_User_User1_idx` (`user`),
  ADD KEY `fk_Strategy_has_User_Strategy1_idx` (`strategy`);

--
-- Indexes for table `Completion_Status`
--
ALTER TABLE `Completion_Status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Dashboard`
--
ALTER TABLE `Dashboard`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Dashboard_Organization1_idx` (`org`);

--
-- Indexes for table `FODA`
--
ALTER TABLE `FODA`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQUE` (`org`,`year`);

--
-- Indexes for table `FODA_Type`
--
ALTER TABLE `FODA_Type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Function`
--
ALTER TABLE `Function`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Function_Position1_idx` (`position`);

--
-- Indexes for table `Goal`
--
ALTER TABLE `Goal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Target_User1_idx` (`userInCharge`),
  ADD KEY `fk_Goal_Completion_Status1_idx` (`status`),
  ADD KEY `fk_Target_Strategy1` (`strategy`);

--
-- Indexes for table `Goal_Item`
--
ALTER TABLE `Goal_Item`
  ADD PRIMARY KEY (`goal`,`item`),
  ADD KEY `fk_Target_has_Item_Item1_idx` (`item`),
  ADD KEY `fk_Target_has_Item_Target1_idx` (`goal`);

--
-- Indexes for table `Graphic`
--
ALTER TABLE `Graphic`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Graphic_Dashboard1_idx` (`dashboard`);

--
-- Indexes for table `Item`
--
ALTER TABLE `Item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Item_Priority1_idx` (`priority`),
  ADD KEY `fk_Item_FODA_Type1_idx` (`type`),
  ADD KEY `fk_Item_FODA1` (`foda`);

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
  ADD PRIMARY KEY (`id`,`category`,`y_unit`,`x_unit`),
  ADD KEY `fk_Metrics_Metric-cat1_idx` (`category`),
  ADD KEY `fk_Metrics_Metric-units1_idx` (`y_unit`),
  ADD KEY `fk_Metric_Unit1_idx` (`x_unit`);

--
-- Indexes for table `Organization`
--
ALTER TABLE `Organization`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Tree-org_Tree-org_idx` (`parent`),
  ADD KEY `fk_Tree-org_Tree_Tipo1_idx` (`type`);

--
-- Indexes for table `OrgType`
--
ALTER TABLE `OrgType`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Permit`
--
ALTER TABLE `Permit`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_together` (`org`,`position`,`resource`),
  ADD KEY `fk_User_has_Organization_Organization1_idx` (`org`),
  ADD KEY `fk_Permit_Resouce1_idx` (`resource`),
  ADD KEY `fk_Permit_Position1_idx` (`position`);

--
-- Indexes for table `Position`
--
ALTER TABLE `Position`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Position_Organization1_idx` (`org`);

--
-- Indexes for table `Priority`
--
ALTER TABLE `Priority`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Resource`
--
ALTER TABLE `Resource`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Role`
--
ALTER TABLE `Role`
  ADD PRIMARY KEY (`user`,`position`),
  ADD KEY `fk_Role_Position1_idx` (`position`);

--
-- Indexes for table `Serie`
--
ALTER TABLE `Serie`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_MetOrg_has_Graphic_Graphic1_idx` (`graphic`),
  ADD KEY `fk_MetOrg_has_Graphic_MetOrg1_idx` (`metorg`),
  ADD KEY `fk_Serie_SerieType1_idx` (`type`),
  ADD KEY `fk_Serie_Aggregation_Type1_idx` (`year_aggregation`),
  ADD KEY `fk_Serie_Aggregation_Type2_idx` (`x_aggregation`);

--
-- Indexes for table `Serie_Type`
--
ALTER TABLE `Serie_Type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `State`
--
ALTER TABLE `State`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Strategic_Plan`
--
ALTER TABLE `Strategic_Plan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQUE` (`org`,`year`),
  ADD KEY `fk_Strategic_Plan_Completion_Status1_idx` (`status`);

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
-- Indexes for table `Value`
--
ALTER TABLE `Value`
  ADD PRIMARY KEY (`id`,`metorg`,`state`),
  ADD KEY `fk_Mediciones_Metric-org1_idx` (`metorg`),
  ADD KEY `fk_Measure_State1_idx` (`state`),
  ADD KEY `fk_Value_User1_idx` (`updater`),
  ADD KEY `fk_Value_User2_idx` (`validator`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Action`
--
ALTER TABLE `Action`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `Aggregation_Type`
--
ALTER TABLE `Aggregation_Type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `Category`
--
ALTER TABLE `Category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Categorías en las que se agrupan las métricas', AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `Completion_Status`
--
ALTER TABLE `Completion_Status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `Dashboard`
--
ALTER TABLE `Dashboard`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `FODA`
--
ALTER TABLE `FODA`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `FODA_Type`
--
ALTER TABLE `FODA_Type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `Function`
--
ALTER TABLE `Function`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Goal`
--
ALTER TABLE `Goal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `Graphic`
--
ALTER TABLE `Graphic`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `Item`
--
ALTER TABLE `Item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `MetOrg`
--
ALTER TABLE `MetOrg`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `Metric`
--
ALTER TABLE `Metric`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Métricas que son creadas con sus respectivas unidades y cotas, para luego poder asociarlas a una  organización en el árbol e ingresar las mediciones.', AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `Organization`
--
ALTER TABLE `Organization`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Organización tipo árbol de DCC/áreas/unidades', AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT for table `OrgType`
--
ALTER TABLE `OrgType`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `Permit`
--
ALTER TABLE `Permit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT for table `Position`
--
ALTER TABLE `Position`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `Priority`
--
ALTER TABLE `Priority`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `Resource`
--
ALTER TABLE `Resource`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `Serie`
--
ALTER TABLE `Serie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `Serie_Type`
--
ALTER TABLE `Serie_Type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `State`
--
ALTER TABLE `State`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `Strategic_Plan`
--
ALTER TABLE `Strategic_Plan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `Unit`
--
ALTER TABLE `Unit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `Value`
--
ALTER TABLE `Value`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `Action`
--
ALTER TABLE `Action`
  ADD CONSTRAINT `fk_Action_Completion_Status1` FOREIGN KEY (`status`) REFERENCES `Completion_Status` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Action_Target1` FOREIGN KEY (`goal`) REFERENCES `Goal` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Action_User1` FOREIGN KEY (`userInCharge`) REFERENCES `User` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `Collaborator`
--
ALTER TABLE `Collaborator`
  ADD CONSTRAINT `fk_Strategy_has_User_Strategy1` FOREIGN KEY (`strategy`) REFERENCES `Strategic_Plan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Strategy_has_User_User1` FOREIGN KEY (`user`) REFERENCES `User` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Dashboard`
--
ALTER TABLE `Dashboard`
  ADD CONSTRAINT `fk_Dashboard_Organization1` FOREIGN KEY (`org`) REFERENCES `Organization` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `FODA`
--
ALTER TABLE `FODA`
  ADD CONSTRAINT `fk_FODA_Organization1` FOREIGN KEY (`org`) REFERENCES `Organization` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Function`
--
ALTER TABLE `Function`
  ADD CONSTRAINT `fk_Function_Position1` FOREIGN KEY (`position`) REFERENCES `Position` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Goal`
--
ALTER TABLE `Goal`
  ADD CONSTRAINT `fk_Goal_Completion_Status1` FOREIGN KEY (`status`) REFERENCES `Completion_Status` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Target_Strategy1` FOREIGN KEY (`strategy`) REFERENCES `Strategic_Plan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Target_User1` FOREIGN KEY (`userInCharge`) REFERENCES `User` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `Goal_Item`
--
ALTER TABLE `Goal_Item`
  ADD CONSTRAINT `fk_Target_has_Item_Item1` FOREIGN KEY (`item`) REFERENCES `Item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Target_has_Item_Target1` FOREIGN KEY (`goal`) REFERENCES `Goal` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Graphic`
--
ALTER TABLE `Graphic`
  ADD CONSTRAINT `fk_Graphic_Dashboard1` FOREIGN KEY (`dashboard`) REFERENCES `Dashboard` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Item`
--
ALTER TABLE `Item`
  ADD CONSTRAINT `fk_Item_FODA1` FOREIGN KEY (`foda`) REFERENCES `FODA` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Item_FODA_Type1` FOREIGN KEY (`type`) REFERENCES `FODA_Type` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Item_Priority1` FOREIGN KEY (`priority`) REFERENCES `Priority` (`id`) ON UPDATE CASCADE;

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
  ADD CONSTRAINT `fk_Metric_Unit1` FOREIGN KEY (`x_unit`) REFERENCES `Unit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Metrics_Metric-cat1` FOREIGN KEY (`category`) REFERENCES `Category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Metrics_Metric-units1` FOREIGN KEY (`y_unit`) REFERENCES `Unit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Organization`
--
ALTER TABLE `Organization`
  ADD CONSTRAINT `fk_Tree-org_Tree-org` FOREIGN KEY (`parent`) REFERENCES `Organization` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Tree-org_Tree_Tipo1` FOREIGN KEY (`type`) REFERENCES `OrgType` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Permit`
--
ALTER TABLE `Permit`
  ADD CONSTRAINT `fk_Permit_Position1` FOREIGN KEY (`position`) REFERENCES `Position` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Permit_Resouce1` FOREIGN KEY (`resource`) REFERENCES `Resource` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_User_has_Organization_Organization1` FOREIGN KEY (`org`) REFERENCES `Organization` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Position`
--
ALTER TABLE `Position`
  ADD CONSTRAINT `fk_Position_Organization1` FOREIGN KEY (`org`) REFERENCES `Organization` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Role`
--
ALTER TABLE `Role`
  ADD CONSTRAINT `fk_Role_Position1` FOREIGN KEY (`position`) REFERENCES `Position` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_User_Permits` FOREIGN KEY (`user`) REFERENCES `User` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Serie`
--
ALTER TABLE `Serie`
  ADD CONSTRAINT `fk_MetOrg_has_Graphic_Graphic1` FOREIGN KEY (`graphic`) REFERENCES `Graphic` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_MetOrg_has_Graphic_MetOrg1` FOREIGN KEY (`metorg`) REFERENCES `MetOrg` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Serie_Aggregation_Type1` FOREIGN KEY (`year_aggregation`) REFERENCES `Aggregation_Type` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Serie_Aggregation_Type2` FOREIGN KEY (`x_aggregation`) REFERENCES `Aggregation_Type` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Serie_SerieType1` FOREIGN KEY (`type`) REFERENCES `Serie_Type` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `Strategic_Plan`
--
ALTER TABLE `Strategic_Plan`
  ADD CONSTRAINT `fk_Strategic_Plan_Completion_Status1` FOREIGN KEY (`status`) REFERENCES `Completion_Status` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Strategy_Organization1` FOREIGN KEY (`org`) REFERENCES `Organization` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Value`
--
ALTER TABLE `Value`
  ADD CONSTRAINT `fk_Measure_State1` FOREIGN KEY (`state`) REFERENCES `State` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Mediciones_Metric-org1` FOREIGN KEY (`metorg`) REFERENCES `MetOrg` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Value_User1` FOREIGN KEY (`updater`) REFERENCES `User` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Value_User2` FOREIGN KEY (`validator`) REFERENCES `User` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;


--
-- Metadata for UDashboard
--

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
