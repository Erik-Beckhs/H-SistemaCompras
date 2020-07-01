<?php

require_once('include/MVC/View/views/view.html.php');

#customAOS_ProductsViewPlan_inventario
class SCO_ConsolidacionViewCreacion extends ViewHtml
{
    public function display(){

	   	$head = '	   	   		
				<link rel="stylesheet" href="modules/SCO_Consolidacion/consolidacion.css?'.time().'" type="text/css" />	 
				<link rel="stylesheet" href="modules/SCO_Consolidacion/consolidacionProductosList.css?'.time().'" type="text/css" />	 
	   			<link href="modules/SCO_Consolidacion/smart_wizard_all.css?'.time().'" rel="stylesheet" type="text/css" />
	   			<link href="modules/SCO_Consolidacion/css-loader.css?'.time().'" rel="stylesheet" type="text/css" />  		   			   		
	   			';
	   	$head .= '<div class="moduleTitle">
				<h2 class="module-title-text"> Crear </h2>
				<span class="utils"></span><div class="clear"></div></div>
				<div class="loader loader-default" data-text="Enviando datos"></div>';		

		$steps = '
				<!-- SmartWizard html -->
				<div id="smartwizard">
				    <ul class="nav">
				        <li class="nav-item">
				          <span class="nav-link" href="#step-1">
				            <strong><span class="suitepicon suitepicon-module-aos-product-categories"></span></strong> <br>Consolidacion
				          </span>
				        </li>
				        <li class="nav-item">
				          <span class="nav-link" href="#step-2">
				            <strong><span class="suitepicon suitepicon-module-aor-reports"></span></strong> <br>Orden de Compra
				          </span>
				        </li>            
				    </ul>
				    <div class="tab-content">
				        <div id="step-1" class="tab-pane" role="tabpanel" aria-labelledby="step-1">
							<div id="consolidacion"></div>						
				        </div>
				        <div id="step-2" class="tab-pane" role="tabpanel" aria-labelledby="step-2">		
				        	<div id="ordeCompra"></div>	
				        	<div id="ventanaModal"></div>			            
				        </div>
				    </div>
				</div>
				';

       	
      	$footer = '
				<script type="text/javascript" src="modules/SCO_Consolidacion/jquery.smartWizard.js?'.time().'"></script> 
				<script src="modules/SCO_Consolidacion/consolidacionProductosList.js?'.time().'"></script>
				<script src="modules/SCO_Consolidacion/consolidacion.js?'.time().'"></script>
				<script src="modules/SCO_Consolidacion/jquery.validate.js?'.time().'"></script>
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
				                animation: "slide-horizontal", // Effect on navigation, none/fade/slide-horizontal/slide-vertical/slide-swing
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
