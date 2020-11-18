CREATE TABLE `sco_reporte_gerencial01` (
  `IdProducto` varchar(45) NOT NULL,
  `CodigoProveedor` varchar(45) DEFAULT NULL,
  `Descripcion` varchar(45) DEFAULT NULL,
  `PrecioVta` varchar(45) DEFAULT NULL,
  `SaldoStock` int(11) DEFAULT NULL,
  `StockRango180` varchar(45) DEFAULT NULL,
  `SalidaAutorizada` varchar(45) DEFAULT NULL,
  `Venta3AnioAtras` varchar(45) DEFAULT NULL,
  `Venta2AnioAtras` varchar(45) DEFAULT NULL,
  `Venta1AnioAtras` varchar(45) DEFAULT NULL,
  `Venta0AnioAtras` varchar(45) DEFAULT NULL,
  `Division` varchar(45) DEFAULT NULL,
  `AreaMercado` varchar(45) DEFAULT NULL,
  `Familia` varchar(45) DEFAULT NULL,
  `Grupo` varchar(45) DEFAULT NULL,
  `SubGrupo` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`IdProducto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
