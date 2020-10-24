<?php

require_once('include/MVC/View/views/view.html.php');

#customAOS_ProductsViewPlan_inventario
class SCO_OrdenCompraViewReporteindustria extends ViewHtml
{
    public function display(){
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

		$cabecera='       		
			<link rel="stylesheet" href="custom/modules/Home/Dashlets/ReporteDiv01/JexcelV4/jsuites.css" type="text/css" />
			<link rel="stylesheet" href="custom/modules/Home/Dashlets/ReporteDiv01/JexcelV4/jexcel.css" type="text/css" />
			<link rel="stylesheet" href="custom/modules/Home/Dashlets/ReporteDiv01/JexcelV4/style.css" type="text/css" />
       	';
		$js = '
       		<script src="custom/modules/Home/Dashlets/ReporteDiv01/JexcelV4/jexcel.js"></script>
			<script src="custom/modules/Home/Dashlets/ReporteDiv01/JexcelV4/jsuites.js"></script>
			<script src="custom/modules/Home/Dashlets/ReporteDiv01/ReporteGerencial.js"></script>
       	';
		$filtro = '					
			<input id="usuarioNombre" type="hidden" value="'.$usuarioNombre.'" disabled>
			<input id="usuarioDivision" type="hidden" value="'.$usuarioDivision.'" disabled>
			<input id="usuarioAmercado" type="hidden" value="'.$usuarioAmercado.'" disabled>
			<input id="usuarioRol" type="hidden" value="'.$usuarioRol.'" disabled>
				
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
			</div>
			';
		$html = '
       		<!--input type="text" name="" id="input" class="form-control" value="" required="required" pattern="" title=""-->
			
			<div id="spreadsheet"></div>

			<textarea id="log" style="width:100%;height:100px;"></textarea>
			<input type="button" onclick="document.getElementById(\'log\').value =JSON.stringify(document.getElementById(\'spreadsheet\').jexcel.getJson())" value="Get JSON">
       ';
       	echo $cabecera.$filtro.$html.$js;
    }
}