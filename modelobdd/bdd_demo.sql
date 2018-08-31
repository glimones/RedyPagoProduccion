-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 03-05-2017 a las 13:58:35
-- Versión del servidor: 5.6.35
-- Versión de PHP: 7.0.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de datos: `pagomedios`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ciudades`
--

CREATE TABLE `ciudades` (
  `id` int(11) NOT NULL,
  `nombre` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `identificacion` varchar(30) NOT NULL,
  `telefonos` varchar(45) NOT NULL,
  `direccion` text NOT NULL,
  `email` varchar(850) NOT NULL,
  `nombres` varchar(700) NOT NULL,
  `apellidos` varchar(700) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `empresa_id`, `identificacion`, `telefonos`, `direccion`, `email`, `nombres`, `apellidos`) VALUES
(1, 1, '1717797433001', '2288208', 'Su casita', 'info@abitmedia.com', 'Carlos', 'Crespo'),
(2, 1, '1717797433002', '2288285', 'Pruebas', 'multimedia@abitmedia.com', 'Johana', 'Perez'),
(3, 1, '1717797433003', '2288208', 'mi casa', 'correo@correo.com', 'Jorge', 'Salazar'),
(4, 1, '0502010051', '0995819008', 'Juan de Azcaray ', 'aristia07@hotmail.com', 'Liz', 'Jaramillo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contactos`
--

CREATE TABLE `contactos` (
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

CREATE TABLE `empresas` (
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

--
-- Volcado de datos para la tabla `empresas`
--

INSERT INTO `empresas` (`id`, `ruc`, `razon_social`, `logo`, `contacto_cedula`, `contacto_nombres`, `contacto_apellidos`, `ciudad_id`, `direccion`, `email`, `actividades`, `url_comercio_electronico`, `fecha_afiliacion`, `id_commerce`, `id_acquirer`, `id_wallet`, `llave_vpos`, `llave_wallet`, `ambiente`, `estado`) VALUES
(1, '1717797433001', 'Abitmedia', 'abitmedia.png', '1717797433', 'Juan Carlos ', 'Cedillo Crespo', 1, 'Amazonas N24-66 y Joaquín Pinto', 'info@abitmedia.com', 'Desarrollo de software', 'https://abitmedia.com/', '2017-04-30 13:00:00', '8226', '123', NULL, NULL, NULL, 'Test', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `para` varchar(850) DEFAULT NULL,
  `asunto` varchar(850) DEFAULT NULL,
  `html` text,
  `fecha` datetime DEFAULT NULL,
  `estado` enum('Sin enviar','En espera','Rebotado','Enviado') DEFAULT 'Sin enviar'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
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
  `estado` enum('Pago pendiente','No autorizado','Pagado','Depositado') DEFAULT NULL,
  `url_pago` varchar(2000) DEFAULT NULL,
  `token` text,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_pago` datetime DEFAULT NULL,
  `descripcion` varchar(400) DEFAULT NULL,
  `purchase_operation_number` varchar(9) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `cliente_id`, `empresa_id`, `usuario_id`, `a_pagar`, `tarjeta`, `numero_pedido`, `authorization_result`, `authorization_code`, `error_code`, `payment_reference_code`, `reserved_22`, `reserved_23`, `estado`, `url_pago`, `token`, `fecha_creacion`, `fecha_pago`, `descripcion`, `purchase_operation_number`) VALUES
(4, 1, 1, 2, '114.00', NULL, '000000004', NULL, NULL, NULL, NULL, NULL, NULL, 'Pago pendiente', NULL, '2y-13-jkzcddeytrv7fwrhhytkhum3ysmofmbodw-t9qtlcncbleo-kwjyo1493743708', '2017-05-02 11:48:26', NULL, 'Pago de página web 1/2', '888759962'),
(5, 2, 1, 2, '12400.00', NULL, '000000005', NULL, NULL, NULL, NULL, NULL, NULL, 'Pago pendiente', NULL, 'pagomediosabitmedia-2y-13-ulz19roauvujgyrdvkw0-u0lvusqwoe0vc8x1hy43mdwd-n8weipc1493752298', '2017-05-02 14:11:36', NULL, 'Prueba de monto grande', '601386933'),
(6, 1, 1, 2, '1000.00', NULL, '000000006', NULL, NULL, NULL, NULL, NULL, NULL, 'Pago pendiente', NULL, 'pagomediosabitmedia-2y-13-v2pa5vxaasd79i-lwdovwuopbgetlrnybwikeefz9bn-lkqhs-z1i1493752338', '2017-05-02 14:12:15', NULL, 'Prueba 2', '038570331'),
(7, 1, 1, 2, '1200.60', NULL, '000000007', NULL, NULL, NULL, NULL, NULL, NULL, 'Pago pendiente', NULL, '2y-13-uoosc07kcbwie1q4skrwxoul2tq3dw6v1bxnv0gjpsddc4wfeojja1493821311', '2017-05-03 09:21:49', NULL, 'Pago por Crusero a Galapagos', '508924403'),
(8, 4, 1, 2, '100.00', NULL, '000000008', NULL, NULL, NULL, NULL, NULL, NULL, 'Pago pendiente', NULL, '2y-13-nl5dyg8b5spzopyssasnv-gdw3imnmllfo8pm8wgr6m3ug9lbrsey1493821811', '2017-05-03 09:30:09', NULL, 'Viaje ', '885954600'),
(9, 4, 1, 2, '1.00', NULL, '000000009', NULL, NULL, NULL, NULL, NULL, NULL, 'Pago pendiente', NULL, '2y-13-whhiyz3paohh189lwpc3qebrr-cezjmg2vwvlcu-hg12yvw0mnrtq1493831699', '2017-05-03 12:14:56', NULL, 'viaje', '852618273');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `suscripciones`
--

CREATE TABLE `suscripciones` (
  `id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `suscripciones`
--

INSERT INTO `suscripciones` (`id`, `empresa_id`, `fecha`) VALUES
(1, 1, '2020-01-01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `empresa_id`, `nombres`, `apellidos`, `email`, `clave`, `estado`, `token`, `es_super`, `es_admin`, `fecha_creacion`) VALUES
(1, NULL, 'Juan Carlos', 'Cedillo Crespo', 'info@abitmedia.com', '$2y$13$6ITjsI.c4mvjFkpPEiNY3ehFRPwkaZhYJNcLQ4DZyn8ZoguDNOdAm', 1, NULL, 1, 0, '2017-04-27 22:59:59'),
(2, 1, 'Juan Carlos', 'Cedillo Crespo', 'ventas@abitmedia.com', '$2y$13$svTBs.4/27Eg2sAcHKVECeCk353nQABS9biwe/wuSvCuQIHpkeRCW', 1, NULL, 0, 1, '2017-04-30 00:00:00'),
(4, 1, 'Paul', 'Barragan', 'multimedia@abitmedia.com', '$2y$13$1wq2ZBakjy5QWI1fEyq4U.Sp/o2behJimDmJOwTU.NpQm4hgwTjv2', 1, NULL, 0, 1, '2017-05-02 15:12:33'),
(5, 1, 'Liz ', 'Jaramillo', 'ventas@abitmedia com', '$2y$13$Se6hsdfDzFEkKkiE9y7RhO0B4sXnFMdDltZM3MPBpL4ocZ6Qf3Qza', 1, NULL, 0, 1, '2017-05-03 11:48:56');

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_clientes_empresas1_idx` (`empresa_id`);

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
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_notificaciones_clientes1_idx` (`cliente_id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pedidos_clientes1_idx` (`cliente_id`),
  ADD KEY `fk_pedidos_empresas1_idx` (`empresa_id`),
  ADD KEY `fk_pedidos_usuarios1_idx` (`usuario_id`);

--
-- Indices de la tabla `suscripciones`
--
ALTER TABLE `suscripciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_suscripciones_empresas1_idx` (`empresa_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `contactos`
--
ALTER TABLE `contactos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `empresas`
--
ALTER TABLE `empresas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT de la tabla `suscripciones`
--
ALTER TABLE `suscripciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD CONSTRAINT `fk_clientes_empresas1` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

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
-- Filtros para la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD CONSTRAINT `fk_notificaciones_clientes1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `fk_pedidos_clientes1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pedidos_empresas1` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pedidos_usuarios1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `suscripciones`
--
ALTER TABLE `suscripciones`
  ADD CONSTRAINT `fk_suscripciones_empresas1` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_empresas1` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
