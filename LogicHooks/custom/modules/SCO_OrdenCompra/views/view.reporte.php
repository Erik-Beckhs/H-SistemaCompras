<?php

require_once('include/MVC/View/views/view.html.php');

#customAOS_ProductsViewPlan_inventario
class SCO_OrdenCompraViewReporte extends ViewHtml
{
    public function display(){
        #include_once('custom/modules/Home/Dashlets/logisitica/index.html');
       global $current_user, $db, $current_language, $mod_strings;                       
       
       $fecha = date("Y-m-d");
       $fecha = explode('-', $fecha);
       $fecha_ac = $fecha[0]."-".$fecha[1]."-".$fecha[2];
       #Datos de ususario      
       $nombre = $current_user->name;       
       $division = $current_user->iddivision_c;
       $idamercado_c = $current_user->idamercado_c;
       #Traen informacion de roles del usuario.
       $roles = ACLRole::getUserRoleNames($current_user->id);
       $userCrm = $current_user->user_name;    
          
       #Consulta trae datos de usuario
       $query = "SELECT DISTINCT(idamercado_c) as idamercado_c, idamercado_c_name, iddivision_c
                 FROM suitecrm.sco_viewdar
                 ORDER BY iddivision_c asc;";
       $results = $GLOBALS['db']->query($query, true);
        while($row = $GLOBALS['db']->fetchByAssoc($results))
        {
            $famercado .= '<option class="'.$row['iddivision_c'].' am" value="'.$row['idamercado_c'].'">'.$row['idamercado_c_name'].'</option>';
        }
       #Consulta trae datos de una vista sco_viewdar nombres y codigos de division, area mercado, regional.
       $query2 = "SELECT DISTINCT(iddivision_c) as iddivision_c, iddivision_c_name
                 FROM suitecrm.sco_viewdar
                 ORDER BY iddivision_c asc;";
       $results2 = $GLOBALS['db']->query($query2, true);
        while($row = $GLOBALS['db']->fetchByAssoc($results2))
        {
            $fdivision .= '<option value="'.$row['iddivision_c'].'">'.$row['iddivision_c_name'].'</option>';
        }
        
       #var_dump($roles);       
       $error = '<div class="alert alert-danger" role="alert">Permiso denegado!</div>';       
       #Division del logisitico, de acuerdo a su usuario
        if(in_array("ROL_REPORTE_LOGISTICA_DIVISION", $roles ) || in_array("ROL_GERENTE_DIVISION", $roles )){
          $logistico = 1;
        }else{
          $logistico = 0;
        }  
        
       #Cabecera html
       $header = '              
       <link rel="stylesheet" type="text/css" media="all" href="custom/modules/Home/Dashlets/LogisticaDashlet/LogisticaControlDashlet.css?' . time () . '" /> 
       <link rel="stylesheet" type="text/css" media="all" href="custom/modules/Home/Dashlets/LogisticaDashlet/jquery.paginate.css?' . time () . '" /> 
       
       ';
       #Body html 
       #Roles de acerudo a enumaraciones y esto se envia al servicio   
        if(in_array("ROL_REPORTE_LOGISTICA_DIVISION", $roles ) || in_array("ROL_GERENTE_GENERAL", $roles ) || in_array("ROL_TOTAL_REPORTE", $roles )){
          $rol = 1;
          $filtroDiv = '     
          <div class="row">
            <div class="col-sm-6">
                <div class="panel ">
                    <div class="panel-heading" style="text-align: center;padding-top: 0px;">
                      Orden de compra solicitante
                    </div>
                    <div class="panel-body">
                      <div class="row">                         
                         <div class="col-sm-6" style="text-align: right;">   
                            <div class="input-group">
                              <span class="input-group-addon">Division pedido</span>                                                             
                              <select class="form-control" id="divCompra" name="divCompra">
                                <option value="00" selected="selected">Todo</option>
                                '.$fdivision.'                           
                              </select>  
                            </div>                         
                         </div>
                         <div class="col-sm-6" style="text-align: center;"> 
                            <div class="input-group">
                              <span class="input-group-addon">Area de mercado </span>                                                 
                              <select class="form-control" id="aMercado" name="aMercado">
                                <option value="00"selected="selected">Todo</option>
                                '.$famercado.'                     
                              </select>  
                            </div>                         
                         </div>
                      </div>
                    </div>
                </div>  
            </div>   
            <div class="col-sm-6">
                <div class="panel ">
                    <div class="panel-heading"style="text-align: center;padding-top: 0px;">
                      Embarque
                    </div>
                    <div class="panel-body">  
                    <div class="row">                
                        <div class="col-sm-6" style="text-align: right;">                              
                          <div class="input-group">
                            <span class="input-group-addon">Division logistico</span>  
                            <select class="form-control" id="estadoDiv" name="estadoDiv">  
                            <option value="00"selected="selected">Todo</option>      
                              '.$fdivision.'                          
                            </select>    
                          </div>                          
                        </div>
                        <div class="col-sm-6"style="text-align: center;">
                  		    <div class="input-group">
                            <span class="input-group-addon">Estado Embarque </span>
                            <select  class="form-control" id="estadoEmbarque" name="estadoEmbarque">
                              <option value="00"selected="selected">Todo</option>
                              <option value="1">En curso</option>
                              <option value="3">Cerrado</option>             
                            </select>
                          </div>
                       </div>  
                     </div>
                  </div>
                </div>
              </div>  
            </div>
          ';
        }elseif( in_array("ROL_GERENTE_DIVISION", $roles)){
          $rol = 2; 
          $filtroDiv = '
                  <br>                                
                  <div class="col-sm-3">                     
                     <div class="input-group">
                       <span class="input-group-addon">Division </span>
                        <select class="form-control" id="divCompra" name="divCompra" disabled>
                          <option value="00"selected="selected">Todo</option>
                          '.$fdivision.'                          
                        </select>  
                      </div>                            
                   </div> 
                   <div class="col-sm-3" style="text-align: center;"> 
                      <div class="input-group">
                        <span class="input-group-addon">Area de mercado </span>                                                                     
                        <select class="form-control" id="aMercado" name="aMercado">
                          <option value="00"selected="selected">Todo</option>
                          '.$famercado.'                     
                        </select>   
                      </div>                        
                   </div>
                   <div class="col-sm-3"> 
                   </div>  
                   <div class="col-sm-3">
                      <div class="input-group">
                        <span class="input-group-addon">Estado embarque </span>                		    
                        <select class="form-control" id="estadoEmbarque" name="estadoEmbarque">
                          <option value="0"selected="selected">Todo</option>
                          <option value="1">En curso</option>
                          <option value="3">Cerrado</option>             
                        </select>
                      </div>
                   </div>  
                  <br>                 
          ';      
        }elseif( in_array("ROL_REPORTE_AMERCADO", $roles ) ){
          $rol = 3; 
          $filtroDiv = '
                  <br>
                  <div class="col-sm-3">                     
                      <div class="input-group">
                        <span class="input-group-addon">Division </span>  
                        <select class="form-control" id="divCompra" name="divCompra" disabled>
                          <option value="00"selected="selected">Todo</option>
                          '.$fdivision.'                          
                        </select>   
                       </div>                           
                   </div> 
                   <div class="col-sm-3">
                       <div class="input-group">
                         <div class="input-group">                           
                           <span class="input-group-addon">Area de mercado </span>                                                                       
                            <select class="form-control" id="aMercado" name="aMercado" disabled>
                              <option value="00"selected="selected">Todo</option>
                              '.$famercado.'                     
                            </select>  
                          </div>    
                        </div>                          
                   </div>  
                   <div class="col-sm-3">                                  
                   </div>  
                   <div class="col-sm-3">
                     <div class="input-group">                           
                       <span class="input-group-addon">Estado Embarque  </span>                		    
                        <select class="form-control" id="estadoEmbarque" name="estadoEmbarque">
                          <option value="0"selected="selected">Todo</option>
                          <option value="1">En curso</option>
                          <option value="3">Cerrado</option>             
                        </select>
                      </div>
                   </div>                    
                   <br>                 
          ';
        }
       echo '
       <div class="row">
         <div class="col-sm-8">
             <h2 class="titulo">Reporte</h2>
         </div>       
         <div class="col-sm-4">
          <div class="input-group ">
    			    <span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
    			    <input id="buscar" type="text" class="form-control" name="buscar" placeholder="Buscar......">
    			</div>
         </div>                             
         <div class="col-sm-2">
           <div class="input-group" style="display:none;">
             <div class="input-group">    		    
              <input id="usuario" type="text" class="form-control" name="msg" placeholder="'.$nombre.'" disabled>    		    
      		  </div>
    		    <span class="input-group-addon">División</span>
    		    <input id="division" type="text" class="form-control" name="msg"  value="'.$division.'"disabled>
            <input id="logistico" type="text" class="form-control" name="msg"  value="'.$logistico.'"disabled>
            <input id="rol" type="text" class="form-control" name="rol"  value="'.$rol.'"disabled>
            <input id="idamercado_c" type="text" class="form-control" name="idamercado_c"  value="'.$idamercado_c.'"disabled>
    		  </div>            
         </div>
       </div>
       ';
       $modal = '
       <div class="modal fade" id="myModal" role="dialog">
          <div class="modal-dialog">        
              <div class="modal-content">  
                <div class="modal-header bg-primary text-white">
                    <!--<button type="button" class="close" data-dismiss="modal">X</button>-->
                    <center><h4 class="modal-title" style="color:#FFF">Hitorial de comentarios</h4></center>
                </div>     
                <div class="modal-body">                     
                  <div class="text-element content-element circles-list">
        						<ol class="listHistorial">
        							
        						</ol>
        					</div>
                </div>      
              </div>
          </div>
        </div>';       
       $html = '         
<!--Vista de filtros en la cabecera-->                            
       <input type="hidden" id="fecha_ac" value="'.$fecha_ac.'"/>       
       <div class="cabecera">
       <div class="row">         
         '.$filtroDiv.'                       	                 
       </div>
<!--Vista de totales en la cabecera-->
       <div class="row cabecera2">
         <div class="col-sm-3">
           <center>
             <div class="d-flex">
                <div class="wrapper">                  
                  <h2 class="mb-0 font-weight-semibold" >
                    <span class="suitepicon suitepicon-module-outcomebymonthdashlet" style="color: #00a1ff;"></span>
                    <span id="cantCompras">0</span>
                  </h2>
                  <h5 class="mb-0 font-weight-medium text-primary">Ordenes de Compra</h5>
                  <p class="mb-0 text-muted">Cantidad</p>                
                </div>
              </div>
           </center>
         </div>
         <div class="col-sm-3">
           <center>
             <div class="d-flex">
                <div class="wrapper">
                  <h2 class="mb-0 font-weight-semibold" >
                    <span class="suitepicon suitepicon-module-outcomebymonthdashlet" style="color: #00a1ff;"></span> 
                    <span id="cantEmbarques">0</span>
                  </h2>
                  <h5 class="mb-0 font-weight-medium text-primary">Embarques</h5>
                  <p class="mb-0 text-muted">Cantidad</p>
                </div>
              </div>
           </center>
         </div>
         <div class="col-sm-3">
           <center>
             <div class="d-flex">
                <div class="wrapper">
                  <h2 class="mb-0 font-weight-semibold">
                    <span style="color: green;">&#36;</span>
                    <span id="totalCompras">0</span>
                  </h2>
                  <h5 class="mb-0 font-weight-medium text-primary">Ordenes de Compra</h5>
                  <p class="mb-0 text-muted">Total Importe </p>
                </div>
              </div>
           </center>
         </div>
         <div class="col-sm-3">
           <center>
             <div class="d-flex">
                <div class="wrapper">                                  
                  <h2 class="mb-0 font-weight-semibold" >
                    <span style="color: green;">&#36;</span>
                    <span id="totalEmbarque">0</span>
                  </h2>
                  <h5 class="mb-0 font-weight-medium text-primary">Embarques</h5>
                  <p class="mb-0 text-muted">Total Importe</p>
                </div>
              </div>
           </center>
         </div>
       </div>   
<!--Vista de lista de registros tabla-->              
       <div class="" onload="bodyOnloadHandler()">
      	<table style="width:100%;color: #555;" class="table table-striped table-responsive table-bordered table-sm " id="tabla">
            <thead id="myTable">
      		  <tr>
            <th class="tablesorter-header tablesorter-headerUnSorted " id="cellVendedor"><div > # </div></th>
      		  <th class="tablesorter-header tablesorter-headerUnSorted " id="cellVendedor"><div > Nombre Compra (Div.) </div></th>
      		  <th class="tbcabecera" ><div > Proveedor </div></th>
            <th class="tbcabecera" ><div > Incoterm </div></th>
            <th class="tbcabecera" ><div > Pais de origen </div></th>
      		  <th class="tbcabecera" ><div > Modalidad Transporte </div></th>	
            <th class="tbcabecera" ><div > Solicitado por </div></th>	  
      		  <th class="tbcabecera" style="width: 88px;"><div > Fecha Orden Compra </div></th>
            <th class="tbcabecera" style="width: 88px;"><div > Fecha Despacho </div></th>
            <th class="tbcabecera" style="width: 88px;"><div > Fecha Embarque </div></th>            
            <th class="tbcabecera" ><div > Importe Compra </div></th>
            <th class="tbcabecera" ><div > Despacho Parcial </div></th>
            <th class="tbcabecera" ><div > % Despacho Parcial </div></th>
            <th class="tbcabecera" ><div > Importe Embarque </div></th>
      		  <th class="tbcabecera" ><div > Ultimo hito </div></th>
      		  <th class="tbcabecera" style="width: 60px;"><div > Dias transito / Dias Plan. </div></th>
      		  <th class="tbcabecera" style="width: 90px;"><div > Fecha prevista a entrega </div></th>
      		  <th class="tbcabecera" ><div > Nombre del Embarque </div></th>            
      		  <th class="tbcabecera" ><div > Estado del Embarque </div></th>
      		  <th class="tbcabecera" style="width: 150px;"><div > Riesgo /  Problema / Notas </div></th>                        
      		  </tr>
      		</thead>
              <tbody class="contenido">      			
      		</tbody>
            </table>
            
      </div>
      <br>
      <br>            
      
      <script type="text/javascript" src="custom/modules/Home/Dashlets/LogisticaDashlet/LogisticaControlDashlet.js?' . time () . '"></script>                    
      <script> </script>
       ';
       if (in_array("ROL_GERENTE_GENERAL", $roles ) || in_array("ROL_REPORTE_LOGISTICA_DIVISION", $roles ) || in_array("ROL_GERENTE_DIVISION", $roles ) || in_array("ROL_TOTAL_REPORTE", $roles ) ||  $current_user->is_admin) {
         echo $header.$html.$modal;
       }else{
         echo $error;
       }
       echo "<script>
$('.btn-toggle').click(function() {
    $(this).find('.btn').toggleClass('active');  
    
    if ($(this).find('.btn-primary').size()>0) {
    	$(this).find('.btn').toggleClass('btn-primary');
    }
    if ($(this).find('.btn-danger').size()>0) {
    	$(this).find('.btn').toggleClass('btn-danger');
    }
    if ($(this).find('.btn-success').size()>0) {
    	$(this).find('.btn').toggleClass('btn-success');
    }
    if ($(this).find('.btn-info').size()>0) {
    	$(this).find('.btn').toggleClass('btn-info');
    }
    
    $(this).find('.btn').toggleClass('btn-default');
       
});
</script>";
    }
}
