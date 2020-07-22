CREATE DEFINER=`root`@`%` PROCEDURE `sp_consolidacion_backorder`(
fecha_de varchar(50),
fecha_hasta varchar(50),
division varchar(50),
aMercado varchar(50)
)
BEGIN
SELECT 
oc.id,
oc.name as nombrecompra,
oc.orc_fechaord,
case oc.orc_tipo 
when '1' then 'Compra' 
when '2' then 'Servicio'  
end as orc_tipo,
case oc.orc_tipoo 
when '1' then 'Local' 
when '2' then 'Internacional'  
end as orc_tipoo,
oc.orc_nomcorto,
SUM(p.pro_saldos) as totalSaldo,
SUM(p.pro_subtotal) as subtotal
FROM suitecrm.sco_ordencompra as oc
INNER JOIN suitecrm.sco_productos_co as p
ON oc.id = p.pro_idco
WHERE (oc.orc_fechaord BETWEEN fecha_de AND fecha_hasta)
AND oc.orc_estado = 1
AND oc.orc_tipo = 1
AND oc.orc_tipoo = 2
AND p.pro_saldos > 0
AND oc.deleted = 0
AND ( division = '' OR division = oc.orc_division)
AND ( aMercado = '' OR aMercado = oc.idamercado_c)
GROUP BY oc.id
ORDER BY oc.orc_fechaord desc;
END