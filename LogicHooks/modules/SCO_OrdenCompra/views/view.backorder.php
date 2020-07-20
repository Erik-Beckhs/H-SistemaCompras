<?php

require_once ('include/MVC/View/views/view.html.php');

class SCO_OrdenCompraViewBackorder extends ViewHtml {
	public function display() {
		global $current_user, $db, $current_language, $mod_strings;
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
					<!--div class="col-sm-3">
						<div class="input-group">
							<div class="input-group">
								<span class="input-group-addon">Cod. proveedor :</span>
								<select class="form-control filter" id="idFabricante" name="idFabricante">
									<option value="" selected="selected">Todo</option>
									</select>
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="input-group">
							<div class="input-group">
								<span class="input-group-addon">Codigo AIO :</span>
								<select class="form-control filter" id="codAioProduct" name="codAioProduct">
									<option value="" selected="selected">Todo</option>
									<div id="codAioProductOption"></div>
								</select>
							</div>
						</div>
					</div-->
				</div>
				</br>
				<div class="row">
					<div class="col-md-12 table-responsive">					
						<table class="list view table-responsive tableCotizacion table-striped">
							<thead>
								<tr>
									<th>#</th>
									<th>Codigo Proveedor</th>
									<th>Codigo AIO</th>
									<th>Descripci&oacute;n</th>
									<th>Cantidad Saldo</th>
									<th>Precio</th>
									<th>Sub Total</th>
									<th>Fecha / OC</th>									
									<th>Orden de Compra</th>
									<th>Consolidado</th>
								</tr>
							</thead>
							<tbody id="tablaBackOrder">
							</tbody>
						</table>
					</div>
				</div>
			</div>
			';
		echo $head.$js.$body;
	}
}