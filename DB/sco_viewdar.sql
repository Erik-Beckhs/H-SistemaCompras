CREATE TABLE `sco_viewdar` (
  `id` varchar(38) NOT NULL,
  `idamercado_c` varchar(45) DEFAULT NULL,
  `idamercado_c_name` varchar(45) DEFAULT NULL,
  `iddivision_c` varchar(45) DEFAULT NULL,
  `iddivision_c_name` varchar(45) DEFAULT NULL,
  `idregional_c` varchar(45) DEFAULT NULL,
  `idregional_c_name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;