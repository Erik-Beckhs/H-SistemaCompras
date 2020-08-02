USE [DWVENTAS_VSTA]
GO
/****** Object:  StoredProcedure [dbo].[hsp_rpt_ReportePed03p]    Script Date: 28/7/2020 11:15:36 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

/*--------------------------------------------------------------------
-----------------------------------------------------------------------	
 Stored Procedure: [dbo].[hsp_rpt_ReportePed03p]
 Creation Date: 13/09/2013
 Copyright: HANSA LTDA                                        			
 Written by: GSANCHEZ
 Purpose: Reporte que muestra informacion de Estimado de Pedidos en Intranet
-----------------------------------------------------------------------						
 ----------------------------------------------------------------------- */


ALTER PROCEDURE [dbo].[hsp_rpt_ReportePed03p]
/******  ******/
/* Modificado Por:	jsilva	*/
/* Modificado En:	29/03/2016	*/
/* Cambios:			Agregar WITH (NO LOCK) a las tablas*/
(@Div AS nvarchar(2), @Ame AS nvarchar(4), @Flia nvarchar(6), @Grp nvarchar(8), @SGrp nvarchar(10), @Item AS NVARCHAR(MAX), @IdProd AS NVARCHAR(MAX)) 

AS 

CREATE TABLE #Result (
IdProducto nvarchar(30),
Producto   nvarchar(50),
IdProdFabrica nvarchar(100),
IdFamilia	nvarchar(20),
IdGrupo		nvarchar(20),
IdSubgrupo	nvarchar(20),
IdDivision  nvarchar(2),
IdAmercado	nvarchar(2),
Familia		nvarchar(60),
Grupo		nvarchar(60),
SubGrupo	nvarchar(60),
VentaAnual	int,
VentaSemestral int,
VentaTrimestral int,
/*PromAnual	Int,
PromSemestral	int,
PromTrimestral	int,
Promedio	int,*/
Stock3meses int,
--Almacen nvarchar(10),
--CantStock int,
ctd121180 int,
ctd181360 int,
ctd361720 int,
ctd7211080 int,
ctd1080    int,
TotalRango int,
SegunEstadisticas int,
CostoPP Decimal(16,2),
AcumPromVentas	Decimal(16,2),
CantPedidos Int,
--PrctAporte  Decimal(16,2),

C300 int,
L300 int,
L301 int,
L302 int,
S300 int,
S301 int,
S302 int,
T300 int,
T301 int,
T302 int,
SA_LP00 int,
SA_SC00 int,
SA_CB00 int, 

ZFCB INT,
ZFCJ INT,
ZFLP INT,
ZFPU INT,
ZFSC INT,
ZFWI INT,
PedPen int,
Division nvarchar(50),
Amercado nvarchar(50),
IdProd nvarchar(15)
)

CREATE TABLE #t_item(
IdProducto nvarchar(MAX) collate Latin1_General_CI_AS
)

CREATE TABLE #t_IdProd(
CodProv nvarchar(1000) collate Latin1_General_CI_AS
)

/****** Para los Items ********************/

while charindex(',',@Item) > 0
begin
insert into #t_item select substring(@Item,1,(charindex(',',@Item)-1))
SET @Item = substring(@Item,charindex(',',@Item)+1,len(@Item))
end
insert into #t_item
 SELECT @Item

/******************************************/


/****** Para los Items Proveedor ********************/

while charindex(',',@IdProd) > 0
begin
insert into #t_IdProd select substring(@IdProd,1,(charindex(',',@IdProd)-1))
SET @IdProd = substring(@IdProd,charindex(',',@IdProd)+1,len(@IdProd))
end
insert into #t_IdProd
 SELECT @IdProd
 
/******************************************/


INSERT INTO #Result
SELECT DISTINCT  T0.IdSubgrupo +'-'+ T0.IdProducto as IdProducto, T0.Producto, T0.IdProdFabrica, 
T0.IdFamilia, T0.IdGrupo, T0.IdSubGrupo, T0.IdDivision, T0.IdSecProducto as IdAmercado,
T4.Familia, T5.Grupo, T6.SubGrupo,
ISNULL(T1.CantidadAnual,0) AS [Venta Anual], 
ISNULL(T1.CantidadSemestral,0) AS [Venta Semestral], 
ISNULL(T1.CantidadTrimestral,0) as [Venta Trimestral],
((((Isnull(T1.CantidadAnual,0)/12) +(ISNULL(T1.CantidadSemestral,0)/6) + (ISNULL(T1.CantidadTrimestral,0) / 3))/3) * 3) as [Stock p 3 Meses], 
ISNULL(T8.Saldo121180,0) as [Ctd 121-180], 
ISNULL(T8.Saldo181360,0) as [Ctd 181-360], 
ISNULL(T8.Saldo1Anio,0) as [Ctd 361-720], 
ISNULL(T8.Saldo2Anio,0) as [Ctd. 721-1080],
ISNULL(T8.Saldo3Anio1,0) as [Ctd. > 1080],
ISNULL((T8.Saldo121180 + T8.Saldo181360 + T8.Saldo1Anio + T8.Saldo2Anio + T8.Saldo3Anio1),0) as [Total Inventario Rango],
CASE
WHEN (SELECT SUM(CantidadFinal) FROM Saldos_Stock TX WITH (NOLOCK) 
	  WHERE TX.IdProducto = T0.IdProducto 
	  AND Mandante = '300') < ((((T1.CantidadAnual/12) +(T1.CantidadSemestral/6) + (T1.CantidadTrimestral / 3))/3) * 3) 
THEN ((((T1.CantidadAnual/12) +(T1.CantidadSemestral/6) + (T1.CantidadTrimestral / 3))/3) * 3) ELSE 0 END AS [Pedido Segun Estadisticas], 
ISNULL(T7.CostoUnitario,0) as [Costo P.P], 0,
ISNULL(T9.StockTransito,0) AS [Ctd Pedidos], 
C300,L300,L301,L302,S300,S301,S302,T300,T301,T302,
SA_LP00, SA_SC00, SA_CB00,
ZFCB,ZFCJ,ZFLP,ZFPU,ZFSC,ZFWI,
PedPen,
T11.Division, T11.Amercado, T0.IdProducto

FROM DIMPRODUCTO T0 WITH (NOLOCK)
LEFT OUTER JOIN (select IdProducto, SUM(CantidadAnual) AS CantidadAnual, SUM(CantidadSemestral) AS CantidadSemestral, 
				SUM(CantidadTrimestral) AS CantidadTrimestral 
				from(Select IdProducto,
					case when FechaVenta >= GetDate() - 365 then Cantidad else 0 end as CantidadAnual,
					case when FechaVenta >= GetDate() - 180 then Cantidad else 0 end  as CantidadSemestral, 
					case when FechaVenta >= GetDate() - 90 then Cantidad	else 0 end  as CantidadTrimestral
					FROM VENTAHANSA WITH (NOLOCK)
					Where FechaVenta >= GetDate() - 365 and (iddivision = @Div OR @Div = '')
					)T0 GROUP BY IdProducto 
				)T1 ON T0.IdProducto = T1.IdProducto
LEFT OUTER JOIN DIMFAMILIA T4 ON T0.IdFamilia = T4.IdFamilia
LEFT OUTER JOIN DIMGRUPO T5 ON T0.IdGrupo = T5.IdGrupo
LEFT OUTER JOIN DIMSUBGRUPO T6 ON T0.IdSubgrupo = T6.IdSubGrupo
LEFT OUTER JOIN (SELECT IDPRODUCTO, AVG(COSTOUNITARIO) AS COSTOUNITARIO, 
				SUM(C300) C300, SUM(L300) L300, SUM(L301) L301, SUM(L302) L302, SUM(S300) S300, SUM(S301) S301, SUM(S302) S302, SUM(T300) T300,
				SUM(T301) T301, SUM(T302) T302,
				SUM(SA_LP00) SA_LP00,SUM(SA_SC00) SA_SC00,SUM(SA_CB00) SA_CB00, 
				SUM(ZFCB) ZFCB, SUM(ZFCJ) ZFCJ, SUM(ZFLP) ZFLP, SUM(ZFPU) ZFPU, SUM(ZFSC) ZFSC, SUM(ZFWI) ZFWI,
				SUM(PedPen) PedPen 
				FROM (
						SELECT IDPRODUCTO, COSTOUNITARIO, 
						case Almacen2 when 'C300'then CantidadFinal else 0 end C300, 
						case Almacen2 when 'L300'then CantidadFinal else 0 end L300,
						case Almacen2 when 'L301'then CantidadFinal else 0 end L301,
						case Almacen2 when 'L302'then CantidadFinal else 0 end L302,
						case Almacen2 when 'S300'then CantidadFinal else 0 end S300,
						case Almacen2 when 'S301'then CantidadFinal else 0 end S301,
						case Almacen2 when 'S302'then CantidadFinal else 0 end S302,
						case Almacen2 when 'T300'then CantidadFinal else 0 end T300,
						case Almacen2 when 'T301'then CantidadFinal else 0 end T301,
						case Almacen2 when 'T302'then CantidadFinal else 0 end T302,
							
						case Almacen2 when 'SA LP00'then CantidadFinal else 0 end SA_LP00,
						case Almacen2 when 'SA SC00'then CantidadFinal else 0 end SA_SC00,
						case Almacen2 when 'SA CB00'then CantidadFinal else 0 end SA_CB00,
						case Almacen2 when 'ZFCB'then CantidadFinal else 0 end ZFCB,
						case Almacen2 when 'ZFCJ'then CantidadFinal else 0 end ZFCJ,
						case Almacen2 when 'ZFLP'then CantidadFinal else 0 end ZFLP,
						case Almacen2 when 'ZFPU'then CantidadFinal else 0 end ZFPU,
						case Almacen2 when 'ZFSC'then CantidadFinal else 0 end ZFSC,
						case Almacen2 when 'ZFWI'then CantidadFinal else 0 end ZFWI,
								
						case when Len(Almacen2) = 10 then ISNULL(CantidadFinal,0)  + ISNULL(StockTransito,0) else 0 end PedPen
						FROM SALDOS_STOCK WITH (NOLOCK)
						WHERE (Almacen2 in ('C300','L300','L301','L302','S300','S301','S302','T300','T301','T302',
						'SA LP00','SA SC00','SA CB00','ZFCB','ZFCJ','ZFLP','ZFPU','ZFSC','ZFWI') or Len(Almacen2) = 10) /*and TipoPedTrans = 'NB' */ AND Mandante = '300'
						)T0
						GROUP BY IDPRODUCTO
					)T7 ON T0.IdProducto = T7.IdProducto 
LEFT OUTER JOIN  Antiguedad_Inventarios T8 ON T0.IdProducto = T8.IdProducto AND T8.Mandante = '300'
LEFT OUTER JOIN (Select IdProducto,SUM(StockTransito) as StockTransito 
				from Saldos_Stock WITH (NOLOCK) WHERE TipoPedTrans = 'NB'
				group by IdProducto) T9 ON T0.IdProducto = T9.IdProducto
LEFT OUTER JOIN VIEW_AREAVENTA T11 ON T0.IdDivision = T11.IdDivision and T0.IdSecProducto = T11.IdAmercado


WHERE 
PrdBloqueado IS NULL and (T0.IdDivision = '03' ) AND(T0.IdSecProducto = substring(@Ame,3,2) OR @Ame = '')
AND (T0.IdFamilia = @Flia OR @Flia = '000') AND (T0.IdGrupo = @Grp OR @Grp = '000') AND (T0.IdSubgrupo = @SGrp OR @SGrp = '000') 
AND ((T0.Idproducto IN (SELECT IdProducto FROM #t_item) OR (@Item = '10000000')) 
	OR (T0.IdProdFabrica IN (SELECT CodProv FROM #t_IdProd) OR (@IdProd = ' "0000001')))
--AND T0.Idproducto IN
--('3000986',
--'3005260',
--'3005589',
--'3005815',
--'3003693',
--'3004522',
--'3007656',
--'3007687',
--'3007689',
--'3008068',
--'3008070',
--'3008834',
--'3008708',
--'3009449',
--'3009700',
--'3010103',
--'3010111',
--'3010236',
--'3003492',
--'3010249',
--'3010526',
--'3003748',
--'3010881',
--'3011339',
--'3008886',
--'3011375',
--'3011466',
--'3010527',
--'3012086',
--'3012117',
--'3012120',
--'3012121',
--'3008850',
--'3013836',
--'3012449',
--'3012637',
--'3008833',
--'3005966',
--'3012879',
--'3013092',
--'3013099',
--'3013100',
--'3013102',
--'3013103',
--'3013104',
--'3013084',
--'3012100',
--'3013258',
--'3013721',
--'3014082',
--'3014096',
--'3014097',
--'3014215',
--'3014204',
--'3014204',
--'3012458',
--'3012917',
--'3015228',
--'3015350',
--'3015291',
--'3016377',
--'3017064',
--'3017351',
--'3018941',
--'3018931',
--'3001158')

/**********************************************
Cursor dentro cursor para acumulados de Aporte
**********************************************/

DECLARE @IdFamilia nvarchar(20)
DECLARE FAMILIA CURSOR FOR SELECT distinct IdFamilia FROM #Result
DECLARE @IdProducto nvarchar(30)
DECLARE @PromVentas	Decimal(16,2)
DECLARE @Acum Decimal(16,2)

OPEN FAMILIA
FETCH NEXT FROM FAMILIA INTO @IdFamilia
WHILE (@@Fetch_Status <> -1)
	BEGIN
			SET @Acum = 0
			DECLARE ACUMULADOS CURSOR 
			FOR SELECT IdProducto, SegunEstadisticas * CostoPP 
			FROM #Result
			where 
			IdFamilia = @IdFamilia
			order by 2 desc
			OPEN ACUMULADOS
			FETCH NEXT FROM ACUMULADOS INTO @IdProducto, @PromVentas
				WHILE (@@Fetch_Status <> -1)
				BEGIN
				SET @Acum = @Acum + @PromVentas
				UPDATE  #Result SET AcumPromVentas = @Acum WHERE IdProducto = @IdProducto  
				FETCH NEXT FROM ACUMULADOS INTO @IdProducto, @PromVentas
				end
				CLOSE ACUMULADOS
				DEALLOCATE ACUMULADOS
		FETCH NEXT FROM FAMILIA INTO @IdFamilia
	END
CLOSE FAMILIA
DEALLOCATE FAMILIA



SELECT IdProducto,
Producto,
IdProdFabrica,
IdFamilia,
IdGrupo,
IdSubgrupo,
IdDivision,
IdAmercado,
Familia	,
Grupo,
SubGrupo,
VentaAnual,
VentaSemestral,
VentaTrimestral,
VentaAnual /12		as PromAnual,	
VentaSemestral /6	as PromSemestral,	
VentaTrimestral /3 as PromTrimestral,	
(VentaAnual/12 + VentaSemestral/6 + VentaTrimestral /3 )/3  as Promedio,
Stock3meses,
ctd121180,
ctd181360,
ctd361720,
ctd7211080 ,
ctd1080,
TotalRango,
SegunEstadisticas ,
CostoPP ,
Stock3meses * CostoPP as PromVentas, 
AcumPromVentas,
case when AcumPromVentas < 81 then 'A'
when  AcumPromVentas between 81 and 95 then 'B'
when  AcumPromVentas > 95 then 'C' end as Clase,
ISNULL(L300,0) L300,ISNULL(L301,0) L301,ISNULL(L302,0) L302,
ISNULL(C300,0) C300,ISNULL(S300,0) S300,ISNULL(S301,0) S301,ISNULL(S302,0) S302,
ISNULL(T300,0) T300, ISNULL(T301,0) T301, ISNULL(T302,0) T302, ISNULL(SA_LP00,0) SA_LP00, ISNULL(SA_SC00,0) SA_SC00, ISNULL(SA_CB00,0) SA_CB00,
ISNULL(ZFCB,0) ZFCB, ISNULL(ZFCJ,0) ZFCJ, ISNULL(ZFLP,0) ZFLP, ISNULL(ZFPU,0) ZFPU, ISNULL(ZFSC,0) ZFSC, ISNULL(ZFWI,0) ZFWI,
PedPen,
CantPedidos,
Division,
Amercado, IdProd  FROM #Result
order by idFamilia, PromVentas desc

drop table #Result
drop table #t_item
drop table #t_IdProd

--Exec [dbo].[hsp_rpt_ReportePed03p] '03','','000','000','000','10000000',''

--(@Div AS nvarchar(2), @Ame AS nvarchar(4), @Flia nvarchar(6), @Grp nvarchar(8), @SGrp nvarchar(10), @Item AS NVARCHAR(MAX), @IdProd AS NVARCHAR(MAX)) 