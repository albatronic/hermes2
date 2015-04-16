# CAMBIOS EN LA BD PARA LA GESTION DE TALLAS Y CLORES


# FAMILIAS
ALTER TABLE  `familias`
ADD  `ConTallas` TINYINT( 1 ) NOT NULL DEFAULT  '0' COMMENT  'Abstract,ValoresSN,IDTipo' AFTER  `Caducidad` ;

# ARTICULOS
ALTER TABLE  `articulos`
ADD  `ConTallasColores` TINYINT( 1 ) NOT NULL DEFAULT  '0' COMMENT  'Abstract,ValoresSN,IDTipo' AFTER  `UrlAmigable`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `colores`
--

CREATE TABLE IF NOT EXISTS `colores` (
  `IDColor` int(11) NOT NULL AUTO_INCREMENT,
  `Color` varchar(20) NOT NULL,
  `Selector` varchar(15),
  PRIMARY KEY (`IDColor`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Colores disponibles' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tallas`
--

CREATE TABLE IF NOT EXISTS `tallas` (
  `IDTalla` int(11) NOT NULL AUTO_INCREMENT,
  `Talla` varchar(20) NOT NULL,
  PRIMARY KEY (`IDTalla`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Tallas disponibles' AUTO_INCREMENT=1 ;