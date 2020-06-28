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

		$steps = '
				<!-- SmartWizard html -->
				<div id="smartwizard">
				    <ul class="nav">
				        <li class="nav-item">
				          <span class="nav-link" href="#step-1">
				            <strong><span class="suitepicon suitepicon-module-outcomebymonthdashlet"></span></strong> <br>Consolidacion
				          </span>
				        </li>
				        <li class="nav-item">
				          <span class="nav-link" href="#step-2">
				            <strong><span class="suitepicon suitepicon-module-outcomebymonthdashlet"></span></strong> <br>Orden de Compra
				          </span>
				        </li>            
				    </ul>
				    <div class="tab-content">
				        <div id="step-1" class="tab-pane" role="tabpanel" aria-labelledby="step-1">
								<div class="container-fluid">
								<div class="row">
								    <div class="col-sm-3">
								        <div class="input-group">
								            <div class="input-group">
								                <span class="input-group-addon">Fabricante</span>
								                <select class="form-control filter" id="idFabricante" name="idFabricante">
								                    <option value="00" selected="selected">Todo</option>
								                    </select>
								            </div>
								        </div>
								    </div>
								    <div class="col-sm-3">
								        <div class="input-group">
								            <div class="input-group">
								                <span class="input-group-addon">Nro. cotizaci&oacute;n: </span>
								                <select class="form-control filter" id="nroCotizacion" name="nroCotizacion">
								                    <option value="00" selected="selected">Todo</option>
								                    <div id="nroCotizacionOption"></div>
								                </select>
								            </div>
								        </div>
								    </div>
								    <div class="col-sm-3">
								        <div class="input-group">
								            <div class="input-group">
								                <span class="input-group-addon">Código AIO</span>
								                <select class="form-control filter" id="codAioProduct" name="codAioProduct">
								                    <option value="00" selected="selected">Todo</option>
								                    <div id="codAioProductOption"></div>
								                </select>
								            </div>
								        </div>
								    </div>
								    <div class="col-sm-3">
								        <div class="input-group">
								            <div class="input-group">
								                <span class="input-group-addon">Cliente</span>
								                <select class="form-control filter" id="idCliente" name="idCliente">
								                    <option value="00" selected="selected">Todo</option>
								                </select>
								            </div>
								        </div>
								    </div>
								    <div class="col-sm-3">
								        <div class="input-group">
								            <div class="input-group">
								                <span class="input-group-addon">Familia</span>
								                <select class="form-control filter" id="aMercado" name="aMercado">
								                    <option value="00" selected="selected">Todo</option>
								                </select>
								            </div>
								        </div>
								    </div>
								</div>

								<div class="row">
								    <div class=" col-md-6">
								        <div class="tabla1">
								        </div>
								    </div>
								    <div class="col-md-6">
								        <div class="tabla2">
								        </div>
								    </div>
								</div>
								</div>
								
				        </div>
				        <div id="step-2" class="tab-pane" role="tabpanel" aria-labelledby="step-2">		
				        	  <div id="ordeCompra"><div>			            
				        </div>
				    </div>
				</div>
				';

       	
      	$footer = '
				<script type="text/javascript" src="modules/SCO_Consolidacion/jquery.smartWizard.js?'.time().'"></script> 
				<script src="modules/SCO_Consolidacion/consolidacion.js?'.time().'"></script>				    			
    			<script src="modules/SCO_Consolidacion/consolidacionProductosList.js?'.time().'"></script>   			  			
				<script type="text/javascript">
				    $(document).ready(function(){
				        // Toolbar extra buttons
				        var btnFinish = $("<button></button>").text("Finish")
				                                         .addClass("btn btn-info")
				                                         .on("click", function(){ alert("Finish Clicked"); });
				        var btnCancel = $("<button></button>").text("Cancel")
				                                         .addClass("btn btn-danger")
				                                         .on("click", function(){ $("#smartwizard").smartWizard("reset"); });

				        // Step show event
				        $("#smartwizard").on("showStep", function(e, anchorObject, stepNumber, stepDirection, stepPosition) {
				            $("#prev-btn").removeClass("disabled");
				            $("#next-btn").removeClass("disabled");
				            if(stepPosition === "first") {
				                $("#prev-btn").addClass("disabled");
				            } else if(stepPosition === "last") {
				                $("#next-btn").addClass("disabled");
				            } else {
				                $("#prev-btn").removeClass("disabled");
				                $("#next-btn").removeClass("disabled");
				            }
				        });

				        // Smart Wizard
				        $("#smartwizard").smartWizard({
				            selected: 0,
				            theme: "default", //default, arrows, dots, dark
				            transition: {
				                animation: "slide-vertical", // Effect on navigation, none/fade/slide-horizontal/slide-vertical/slide-swing
				            },
				            toolbarSettings: {
				                toolbarPosition: "both" // both bottom
				                //toolbarExtraButtons: [btnFinish, btnCancel]
				            }
				        });

				        // External Button Events
				        $("#reset-btn").on("click", function() {
				            // Reset wizard
				            $("#smartwizard").smartWizard("reset");
				            return true;
				        });

				        $("#prev-btn").on("click", function() {
				            // Navigate previous
				            $("#smartwizard").smartWizard("prev");
				            return true;
				        });

				        $("#next-btn").on("click", function() {
				            // Navigate next
				            $("#smartwizard").smartWizard("next");
				            return true;
				        });


				        // Demo Button Events
				        $("#got_to_step").on("change", function() {
				            // Go to step
				            var step_index = $(this).val() - 1;
				            $("#smartwizard").smartWizard("goToStep", step_index);
				            return true;
				        });

				        $("#is_justified").on("click", function() {
				            // Change Justify
				            var options = {
				              justified: $(this).prop("checked")
				            };

				            $("#smartwizard").smartWizard("setOptions", options);
				            return true;
				        });

				        $("#animation").on("change", function() {
				            // Change theme
				            var options = {
				              transition: {
				                  animation: $(this).val()
				              },
				            };
				            $("#smartwizard").smartWizard("setOptions", options);
				            return true;
				        });

				        $("#theme_selector").on("change", function() {
				            // Change theme
				            var options = {
				              theme: $(this).val()
				            };
				            $("#smartwizard").smartWizard("setOptions", options);
				            return true;
				        });

				    });
				</script>
    			';
       
		echo $head.$steps.$footer;

    }
}
