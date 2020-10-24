CREATE DEFINER=`root`@`%` PROCEDURE `sp_ordencompra_rep01`(
division varchar(50),
aMercado varchar(50)
)
BEGIN
SELECT *
FROM suitecrm.sco_reporte_gerencial01
WHERE ( division = '' OR division = Division)
AND ( aMercado = '' OR aMercado = AreaMercado);
END