CREATE DEFINER=`root`@`%` PROCEDURE `sp_consolidacion_backorder`(
fecha_de varchar(50),
fecha_hasta varchar(50)
)
BEGIN
SELECT 
oc.id,
oc.name as nombrecompra,
oc.orc_fechaord,
p.pro_nombre as nombrepro,
p.pro_descripcion,
p.pro_unidad
FROM suitecrm.sco_ordencompra as oc
INNER JOIN suitecrm.sco_productos_co as p
ON oc.id = p.pro_idco
WHERE oc.orc_fechaord BETWEEN fecha_de AND fecha_hasta 
AND oc.orc_estado = 1
AND oc.orc_tipo = 1
AND p.pro_saldos > 0
ORDER BY oc.orc_fechaord desc;
END