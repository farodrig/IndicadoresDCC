-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-06-2015 a las 16:36:50
-- Versión del servidor: 5.6.21
-- Versión de PHP: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `u`
--

--
-- Volcado de datos para la tabla `category`
--

INSERT INTO `Category` (`id`, `name`) VALUES
(1, 'Productividad'),
(2, 'Finanzas');

--
-- Volcado de datos para la tabla `graphtype`
--

INSERT INTO `GraphType` (`id`, `description`) VALUES
(1, 'Barra'),
(2, 'Línea');

--


--
-- Volcado de datos para la tabla `orgtype`
--

INSERT INTO `OrgType` (`id`, `name`) VALUES
(1, 'Soporte'),
(2, 'Operación');



--
-- Volcado de datos para la tabla `state`
--

INSERT INTO `State` (`id`, `name`) VALUES
(0, 'no_validado'),
(1, 'validado');


-- Volcado de datos para la tabla `organization`
--

INSERT INTO `Organization` (`id`, `parent`, `type`, `name`) VALUES
(0, 0, 1, 'DCC'),
(1, 1, 2, 'DCC');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
