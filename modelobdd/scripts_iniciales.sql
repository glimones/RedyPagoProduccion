INSERT INTO `pagomedios`.`ciudades` (`id`, `nombre`) VALUES (NULL, 'Quito'), (NULL, 'Guayaquil'), (NULL, 'Cuenca');

INSERT INTO `pagomedios`.`empresas` (`id`, `ruc`, `razon_social`, `logo`, `contacto_cedula`, `contacto_nombres`, `contacto_apellidos`, `ciudad_id`, `direccion`, `email`, `actividades`, `url_comercio_electronico`, `fecha_afiliacion`, `id_commerce`, `id_acquirer`, `id_wallet`, `llave_vpos`, `llave_wallet`, `ambiente`, `estado`) VALUES (NULL, '1717797433001', 'Abitmedia', 'abitmedia.png', '1717797433', 'Juan Carlos ', 'Cedillo Crespo', '1', 'Amazonas N24-66 y Joaquín Pinto', 'info@abitmedia.com', 'Desarrollo de software', 'https://abitmedia.com/', '2017-04-30 13:00:00', NULL, NULL, NULL, NULL, NULL, NULL, '1');

INSERT INTO `pagomedios`.`usuarios` (`id`, `empresa_id`, `nombres`, `apellidos`, `email`, `clave`, `estado`, `token`, `es_super`, `es_admin`, `fecha_creacion`) VALUES (NULL, NULL, 'Juan Carlos', 'Cedillo Crespo', 'info@abitmedia.com', '$2y$13$6ITjsI.c4mvjFkpPEiNY3ehFRPwkaZhYJNcLQ4DZyn8ZoguDNOdAm', '1', NULL, '1', '0', '2017-04-27 22:59:59');
INSERT INTO `pagomedios`.`usuarios` (`id`, `empresa_id`, `nombres`, `apellidos`, `email`, `clave`, `estado`, `token`, `es_super`, `es_admin`, `fecha_creacion`) VALUES (NULL, '1', 'Juan Carlos', 'Cedillo Crespo', 'ventas@abitmedia.com', '$2y$13$6ITjsI.c4mvjFkpPEiNY3ehFRPwkaZhYJNcLQ4DZyn8ZoguDNOdAm', '1', NULL, '0', '1', '2017-04-30 00:00:00');

INSERT INTO `suscripciones` (`id`, `empresa_id`, `fecha`) VALUES (NULL, '1', '2020-01-01');



INSERT INTO `procesamientos` (`id`, `nombre`)
VALUES
	(1, 'VPOS1'),
	(2, 'Payme');


update empresas set adquirente_id = 1;
update empresas set procesamiento_id = 2;