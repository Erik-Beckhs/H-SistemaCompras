CREATE DEFINER=`root`@`%` PROCEDURE `sp_consolidacion_filtro`(
sco_proveedor_id_c varchar(50),
pcv_numerocotizacion varchar(50),
pcv_codigoproveedor varchar(50),
pcv_clienteaio varchar(50),
pcv_familia varchar(50),
pcv_plzentrega int
)
BEGIN
SELECT
pc.id,
pc.pcv_codigoproveedor,
pc.sco_proveedor_id_c,
pc.pcv_cantidadsaldo,
pc.pcv_numerocotizacion,
pc.pcv_familia,
pc.name,
pc.pcv_preciofob,
pc.pcv_nombreproveedor,
pc.pcv_descripcion,
pc.pcv_vendedor,
pc.pcv_cantidad,
pc.pcv_cliente,
pc.pcv_clienteaio,
pc.pcv_proveedoraio,
pc.sco_productoscompras_id_c
FROM suitecrm.sco_productoscotizadosventa pc
WHERE
(sco_proveedor_id_c = '' OR sco_proveedor_id_c = pc.sco_proveedor_id_c) AND
(pcv_numerocotizacion = '' OR pcv_numerocotizacion = pc.pcv_numerocotizacion) AND
(pcv_codigoproveedor = '' OR pcv_codigoproveedor = pc.pcv_codigoproveedor) AND
(pcv_clienteaio = '' OR pcv_clienteaio = pc.pcv_clienteaio) AND
(pcv_familia = '' OR pcv_familia = pc.pcv_familia) AND
(pcv_plzentrega = '' OR pcv_plzentrega = pc.pcv_plzentrega) AND
pc.pcv_cantidadsaldo > 0 AND
pc.deleted = 0;
END