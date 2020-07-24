<?php
/**
*Esta clase arma la estructura del backorder (DOM) y extrae datos de la DB de Compras
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2020
*@license ruta: /var/www/html/modules/SCO_OrdenCompra
*/
require_once ('include/MVC/View/views/view.html.php');

class SCO_OrdenCompraViewBackorder extends ViewHtml {
	public function display() {
		global $current_user, $db, $current_language, $mod_strings;
		#Datos de Usuario
		$usuarioNombre = $current_user->name;       
		$usuarioDivision = $current_user->iddivision_c;
		$usuarioAmercado = $current_user->idamercado_c;

		#Datos de usuario extrayendo su ROL
		$roles = ACLRole::getUserRoleNames($current_user->id);
		$usuarioRol = $current_user->user_name;   

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
				<link rel="stylesheet" href="modules/SCO_OrdenCompra/BackOrder/bootstrap-datetimepicker.min.css?'.time().'">
';
		$head .= '
				<div class="moduleTitle">
				<h2 class="module-title-text"> Reporte BackOrder </h2>
				<span class="utils"></span><div class="clear"></div></div>
				<div class="loader loader-default" data-text="Cargando"></div>';
		$js = '	<script src="modules/SCO_OrdenCompra/BackOrder/backorder.js?'.time().'"></script>
				<script src="modules/SCO_OrdenCompra/BackOrder/bootstrap-datetimepicker.min.js?'.time().'"></script>';
		$body = '		
			<input id="usuarioNombre" type="hidden" value="'.$usuarioNombre.'" disabled>
			<input id="usuarioDivision" type="hidden" value="'.$usuarioDivision.'" disabled>
			<input id="usuarioAmercado" type="hidden" value="'.$usuarioAmercado.'" disabled>
			<input id="usuarioRol" type="hidden" value="'.$usuarioRol.'" disabled>
			<div id="ventanaModal"></div>	
			<div class="container-fluid">
				<div class="row filtro">													
					<div class="col-sm-3">
						<div class="input-group">
							<div class="input-group">
                              <span class="input-group-addon">Division</span>               
                              <select class="form-control" id="division" name="division">
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
					<div class="col-sm-1">
					</div>
					<div class="col-sm-2">
						<div class="input-group">	
							<span class="input-group-addon">Fecha de: </span>				
					 		<input type="text" class="form-control custom-input" id="datepicker">
						</div>
					</div>	
					<div class="col-sm-2">
						<div class="input-group">	
							<span class="input-group-addon">Hasta: </span>				
					 		<input type="text" class="form-control custom-input" id="datepicker2">
						</div>
					</div>
					<div class="col-sm-1">
						<button type="button" class="btn btn-sm btn-info" id="buscarBackOrder">
							<i class="glyphicon glyphicon-search"></i>
						</button>
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
									<th>Estado</th>
									<th>Tipo origen</th>
									<th>Tipo</th>
									<th>Total saldo</th>
									<th>Precio subtotal</th>									
									<th>Items</th>
								</tr>
							</thead>
							<tbody id="tablaBackOrder">
								<tr><td></td><td><span class="label label-default badge-cinfo" style="color:#FFF;">Ingrese datos en el filtro</span></td></tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			';
		echo $head.$js.$body;
	}
}