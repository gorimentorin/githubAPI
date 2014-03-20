-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Servidor: localhost:3306
-- Tiempo de generación: 20-03-2014 a las 13:19:49
-- Versión del servidor: 5.5.34
-- Versión de PHP: 5.4.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `githubapi`
--
CREATE DATABASE IF NOT EXISTS `githubapi` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `githubapi`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `buildphonegapapps`
--

CREATE TABLE IF NOT EXISTS `buildphonegapapps` (
  `id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `version` int(11) NOT NULL,
  `qr` varchar(255) NOT NULL,
  `android_url` varchar(255) DEFAULT NULL,
  `ios_url` varchar(255) DEFAULT NULL,
  `winphone_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `busqueda`
--

CREATE TABLE IF NOT EXISTS `busqueda` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `busqueda` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `total_count` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Se almacenan las busquedas' AUTO_INCREMENT=155 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `owner`
--

CREATE TABLE IF NOT EXISTS `owner` (
  `id` int(11) NOT NULL,
  `login` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Usuarios GitHub';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phonegap`
--

CREATE TABLE IF NOT EXISTS `phonegap` (
  `nombre` varchar(255) NOT NULL,
  `codigo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `repositorio`
--

CREATE TABLE IF NOT EXISTS `repositorio` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `owner_id` varchar(255) NOT NULL,
  `private` tinyint(1) DEFAULT NULL,
  `html_url` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `fork` tinyint(1) DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  `forks_url` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `pushed_at` datetime NOT NULL,
  `git_url` varchar(255) NOT NULL,
  `svn_url` varchar(255) NOT NULL,
  `homepage` varchar(255) NOT NULL,
  `size` int(11) NOT NULL,
  `stargazers_count` int(11) NOT NULL,
  `watchers_count` int(11) NOT NULL,
  `language` varchar(255) NOT NULL,
  `has_issues` tinyint(1) DEFAULT NULL,
  `has_downloads` tinyint(1) DEFAULT NULL,
  `has_wiki` tinyint(1) DEFAULT NULL,
  `forks_count` int(11) NOT NULL,
  `mirror_url` varchar(255) DEFAULT NULL,
  `open_issues_count` int(11) NOT NULL,
  `forks` int(11) NOT NULL,
  `open_issues` int(11) NOT NULL,
  `watchers` int(11) NOT NULL,
  `default_branch` varchar(255) NOT NULL,
  `master_branch` varchar(255) NOT NULL,
  `score` float NOT NULL,
  `json` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Informacion sobre los repositorios de GitHub';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resultadobusquedarepositorio`
--

CREATE TABLE IF NOT EXISTS `resultadobusquedarepositorio` (
  `id_busqueda` int(11) NOT NULL,
  `id_repositorio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Relaciona las busquedas con el repositorio';

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
