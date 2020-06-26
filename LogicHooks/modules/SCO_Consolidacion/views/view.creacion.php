<?php

require_once('include/MVC/View/views/view.html.php');

#customAOS_ProductsViewPlan_inventario
class SCO_ConsolidacionViewCreacion extends ViewHtml
{
    public function display(){

	   	$head = '	   	   		
				   <link rel="stylesheet" href="modules/SCO_Consolidacion/consolidacion.css?'.time().'" type="text/css" />	 
				   <link rel="stylesheet" href="modules/SCO_Consolidacion/consolidacionProductosList.css?'.time().'" type="text/css" />	 
	   			<link href="modules/SCO_Consolidacion/smart_wizard_all.css" rel="stylesheet" type="text/css" />  			
	   			';
	   	$head .= '<div class="moduleTitle">
				<h2 class="module-title-text"> Crear </h2>
				<span class="utils"></span><div class="clear"></div></div>';

		$htmlOrdenCompra = '<div id="capa"><div>';
		$htmlConsolidacion = '<div id="capa1"><div>';
		
		$steps = '
				<!-- SmartWizard html -->
				<div id="smartwizard">
				    <ul class="nav">
				        <li class="nav-item">
				          <a class="nav-link" href="#step-1">
				            <strong><span class="suitepicon suitepicon-module-outcomebymonthdashlet"></span></strong> <br>Consolidacion
				          </a>
				        </li>
				        <li class="nav-item">
				          <a class="nav-link" href="#step-2">
				            <strong><span class="suitepicon suitepicon-module-outcomebymonthdashlet"></span></strong> <br>Orden de Compra
				          </a>
				        </li>            
				    </ul>
				    <div class="tab-content">
				        <div id="step-1" class="tab-pane" role="tabpanel" aria-labelledby="step-1">
							<h3>DATOS DE CONSOLIDACION</h3>
							'.$htmlConsolidacion.'
				            
				        </div>
				        <div id="step-2" class="tab-pane" role="tabpanel" aria-labelledby="step-2">		            
				            '.$htmlOrdenCompra.'
				        </div>

				    </div>
				</div>
				';

       	
      	$footer = '
				<script src="modules/SCO_Consolidacion/consolidacion.js?'.time().'"></script>
				<script src="modules/SCO_Consolidacion/consolidacionProductosList.js?'.time().'"></script>
    			<script type="text/javascript" src="modules/SCO_Consolidacion/jquery.smartWizard.js"></script>
    			';
       
		echo $head.$steps.$footer;

    }
}
