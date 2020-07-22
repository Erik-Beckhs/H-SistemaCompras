<?php

require_once ('include/MVC/View/views/view.html.php');

class SCO_OrdenCompraViewBackorder extends ViewHtml {
	public function display() {
		global $current_user, $db, $current_language, $mod_strings;

		#Consulta trae datos de una vista sco_viewdar nombres y codigos de division.
       	$query2 = "	SELECT DISTINCT(iddivision_c) as iddivision_c, iddivision_c_name
                	FROM suitecrm.sco_viewdar
                	ORDER BY iddivision_c asc;";
       	$results2 = $GLOBALS['db']->query($query2, true);
        while($row = $GLOBALS['db']->fetchByAssoc($results2))
        {
            $division .= '<option value="'.$row['iddivision_c'].'">'.$row['iddivision_c_name'].'</option>';
        }
        #Consulta trae datos de Area de mercado
       $query = "SELECT DISTINCT(idamercado_c) as idamercado_c, idamercado_c_name, iddivision_c
                 FROM suitecrm.sco_viewdar
                 ORDER BY iddivision_c asc;";
       $results = $GLOBALS['db']->query($query, true);
        while($row = $GLOBALS['db']->fetchByAssoc($results))
        {
            $amercado .= '<option class="'.$row['iddivision_c'].' am" value="'.$row['idamercado_c'].'">'.$row['idamercado_c_name'].'</option>';
        }

		$head = '
				<link rel="stylesheet" href="modules/SCO_OrdenCompra/BackOrder/backorder.css?'.time().'" type="text/css" />
				<link href="modules/SCO_Consolidacion/css-loader.css?'.time().'" rel="stylesheet" type="text/css" />  
	   			';
		$head .= '
				<div class="moduleTitle">
				<h2 class="module-title-text"> Reporte BackOrder </h2>
				<span class="utils"></span><div class="clear"></div></div>
				<div class="loader loader-default" data-text="Cargando"></div>
				';
		$js = '
				<script src="modules/SCO_OrdenCompra/BackOrder/backorder.js?'.time().'"></script>
				';
		$body = '	
			<link href="bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
			<script src="bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
			<div id="ventanaModal"></div>	
			<div class="container-fluid">
				<div class="row filtro">
					<div class="col-sm-2">
						<div class="input-group">	
							<span class="input-group-addon">Fecha de: </span>				
					 		<input type="text" class="form-control" id="datepicker">
						</div>
					</div>	
					<div class="col-sm-2">
						<div class="input-group">	
							<span class="input-group-addon">Hasta: </span>				
					 		<input type="text" class="form-control" id="datepicker2">
						</div>
					</div>								
					<div class="col-sm-2">
						<div class="input-group">
							<div class="input-group">
                              <span class="input-group-addon">Division</span>                                                             
                              <select class="form-control" id="divCompra" name="divCompra">
                                <option value="00" selected="selected">Todo</option>
                                '.$division.'                           
                              </select>  
                            </div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="input-group">
							<div class="input-group">
                              	<span class="input-group-addon">Area de mercado </span>                                                 
                              	<select class="form-control" id="aMercado" name="aMercado">
                                	<option value="00"selected="selected">Todo</option>
                                	'.$amercado.'                     
                              	</select>  
                            </div>
						</div>
					</div>
				</div>
				</br>
				<div class="row">
					<div class="col-md-12 table-responsive">					
						<table class="list view table-responsive tableCotizacion table-striped">
							<thead>
								<tr>
									<th>#</th>
									<th>Orden de Compra</th>
									<th>Fecha Orden</th>
									<th>Proveedor</th>
									<th>Tipo origen</th>
									<th>Tipo</th>
									<th>Total saldo</th>
									<th>Precio subtotal</th>									
									<th>Items</th>
								</tr>
							</thead>
							<tbody id="tablaBackOrder">
								<tr><td><span class="label label-default" style="color:#FFF;">Ingrese datos en el filtro</span></td></tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			';
		echo $head.$js.$body;
	}
}