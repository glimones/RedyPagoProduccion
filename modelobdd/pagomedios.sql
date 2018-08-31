-- phpMyAdmin SQL Dump
-- version 4.4.3
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 28-04-2017 a las 00:05:57
-- Versión del servidor: 5.6.24
-- Versión de PHP: 5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `pagomedios`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ciudades`
--

CREATE TABLE IF NOT EXISTS `ciudades` (
  `id` int(11) NOT NULL,
  `nombre` varchar(250) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `ciudades`
--

INSERT INTO `ciudades` (`id`, `nombre`) VALUES
(1, 'Quito'),
(2, 'Guayaquil'),
(3, 'Cuenca');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE IF NOT EXISTS `clientes` (
  `id` int(11) NOT NULL,
  `identificacion` varchar(30) NOT NULL,
  `razon_social` varchar(450) NOT NULL,
  `telefonos` varchar(45) NOT NULL,
  `direccion` text NOT NULL,
  `email` varchar(850) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `identificacion`, `razon_social`, `telefonos`, `direccion`, `email`) VALUES
(1, '1717797433001', 'Juan Carlos Cedillo Crespo', '2288208', 'Amazonas N2466-y Joaquin Pinto', 'info@abitmedia.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contactos`
--

CREATE TABLE IF NOT EXISTS `contactos` (
  `id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `nombres` varchar(450) NOT NULL,
  `apellidos` varchar(450) NOT NULL,
  `telefono` varchar(200) NOT NULL,
  `email` varchar(450) NOT NULL,
  `tipo` enum('Operativo','Comercial','Técnico') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresas`
--

CREATE TABLE IF NOT EXISTS `empresas` (
  `id` int(11) NOT NULL,
  `ruc` varchar(13) NOT NULL,
  `razon_social` varchar(450) NOT NULL,
  `logo` varchar(2000) NOT NULL,
  `contacto_cedula` varchar(10) NOT NULL,
  `contacto_nombres` varchar(250) NOT NULL,
  `contacto_apellidos` varchar(250) NOT NULL,
  `ciudad_id` int(11) NOT NULL,
  `direccion` text NOT NULL,
  `email` varchar(450) NOT NULL,
  `actividades` text NOT NULL,
  `url_comercio_electronico` varchar(450) NOT NULL,
  `fecha_afiliacion` datetime NOT NULL,
  `id_commerce` varchar(20) DEFAULT NULL,
  `id_acquirer` varchar(10) DEFAULT NULL,
  `id_wallet` varchar(45) DEFAULT NULL,
  `llave_vpos` varchar(300) DEFAULT NULL,
  `llave_wallet` varchar(300) DEFAULT NULL,
  `ambiente` enum('Test','Producción') DEFAULT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE IF NOT EXISTS `pedidos` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `a_pagar` decimal(10,2) DEFAULT NULL,
  `tarjeta` enum('Visa','MasterCard') DEFAULT NULL,
  `numero_pedido` varchar(9) DEFAULT NULL,
  `authorization_result` varchar(2) DEFAULT NULL COMMENT '00 transaccion autorizada - 01 Transaccion denegada por emisor - 05 transaccion denegada por VPOS ',
  `authorization_code` varchar(6) DEFAULT NULL COMMENT 'Codigo de autorizacion',
  `error_code` varchar(4) DEFAULT NULL,
  `payment_reference_code` varchar(45) DEFAULT NULL COMMENT 'Numero de la tarjeta enmascarada',
  `reserved_22` varchar(10) DEFAULT NULL COMMENT 'Debito o credito',
  `reserved_23` varchar(10) DEFAULT NULL COMMENT 'Banco emisor',
  `estado` enum('Denegado','Autorizado','Incompleto','Depositado','Liquidado','Inválido','Extornado','Registrado','Extorno Fallido') DEFAULT NULL,
  `url_pago` varchar(2000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL,
  `empresa_id` int(11) DEFAULT NULL,
  `nombres` varchar(200) DEFAULT NULL,
  `apellidos` varchar(200) DEFAULT NULL,
  `email` varchar(450) NOT NULL,
  `clave` varchar(450) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT '1',
  `token` text,
  `es_super` tinyint(4) DEFAULT '0',
  `es_admin` tinyint(4) DEFAULT '1',
  `fecha_creacion` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `empresa_id`, `nombres`, `apellidos`, `email`, `clave`, `estado`, `token`, `es_super`, `es_admin`, `fecha_creacion`) VALUES
(1, NULL, 'Juan Carlos', 'Cedillo Crespo', 'info@abitmedia.com', '$2y$13$6ITjsI.c4mvjFkpPEiNY3ehFRPwkaZhYJNcLQ4DZyn8ZoguDNOdAm', 1, NULL, 1, 0, '2017-04-27 22:59:59');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ciudades`
--
ALTER TABLE `ciudades`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `contactos`
--
ALTER TABLE `contactos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_contactos_empresas1_idx` (`empresa_id`);

--
-- Indices de la tabla `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_empresas_ciudades_idx` (`ciudad_id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pedidos_clientes1_idx` (`cliente_id`),
  ADD KEY `fk_pedidos_empresas1_idx` (`empresa_id`),
  ADD KEY `fk_pedidos_usuarios1_idx` (`usuario_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_usuarios_empresas1_idx` (`empresa_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `ciudades`
--
ALTER TABLE `ciudades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `contactos`
--
ALTER TABLE `contactos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `empresas`
--
ALTER TABLE `empresas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `contactos`
--
ALTER TABLE `contactos`
  ADD CONSTRAINT `fk_contactos_empresas1` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `empresas`
--
ALTER TABLE `empresas`
  ADD CONSTRAINT `fk_empresas_ciudades` FOREIGN KEY (`ciudad_id`) REFERENCES `ciudades` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `fk_pedidos_clientes1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pedidos_empresas1` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pedidos_usuarios1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_empresas1` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
