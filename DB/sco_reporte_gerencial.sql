CREATE TABLE `sco_reporte_gerencial` (
  `IdProducto` varchar(100) COLLATE utf8_bin NOT NULL DEFAULT '',
  `Producto` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `IdProdFabrica` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `VentaSemestral` int(11) DEFAULT NULL,
  `CantPedidos` int(11) DEFAULT NULL,
  `TotalRango` int(11) DEFAULT NULL,
  `IdGrupo` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `IdProd` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `SegunEstadisticas` int(11) DEFAULT NULL,
  `Grupo` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `PromSemestral` int(11) DEFAULT NULL,
  `Stock3meses` int(11) DEFAULT NULL,
  `PedPen` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `VentaTrimestral` int(11) DEFAULT NULL,
  `IdDivision` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `PromTrimestral` int(11) DEFAULT NULL,
  `SubGrupo` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `Amercado` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `Clase` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `Division` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `VentaAnual` int(11) DEFAULT NULL,
  `IdFamilia` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `PromAnual` int(11) DEFAULT NULL,
  `IdAmercado` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `Promedio` int(11) DEFAULT NULL,
  `ctd7211080` int(11) DEFAULT NULL,
  `ctd1080` int(11) DEFAULT NULL,
  `ctd361720` int(11) DEFAULT NULL,
  `ctd121180` int(11) DEFAULT NULL,
  `ctd181360` int(11) DEFAULT NULL,
  `IdSubgrupo` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `ZFCJ` int(11) DEFAULT NULL,
  `ZFCB` int(11) DEFAULT NULL,
  `S302` int(11) DEFAULT NULL,
  `Familia` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `L300` int(11) DEFAULT NULL,
  `L301` int(11) DEFAULT NULL,
  `L302` int(11) DEFAULT NULL,
  `C300` int(11) DEFAULT NULL,
  `S300` int(11) DEFAULT NULL,
  `SA_CB00` int(11) DEFAULT NULL,
  `SA_LP00` int(11) DEFAULT NULL,
  `T300` int(11) DEFAULT NULL,
  `T301` int(11) DEFAULT NULL,
  `S301` int(11) DEFAULT NULL,
  `T302` int(11) DEFAULT NULL,
  `ZFLP` int(11) DEFAULT NULL,
  `ZFSC` int(11) DEFAULT NULL,
  `ZFPU` int(11) DEFAULT NULL,
  `ZFWI` int(11) DEFAULT NULL,
  `SA_SC00` int(11) DEFAULT NULL,
  `CostoPP` decimal(11,2) DEFAULT NULL,
  `PromVentas` decimal(11,2) DEFAULT NULL,
  `AcumPromVentas` decimal(11,2) DEFAULT NULL,
  `Acum2AniosAtras` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `Acum1AniosAtras` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `Acum0AniosAtras` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`IdProducto`),
  KEY `IdProd` (`IdProd`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;