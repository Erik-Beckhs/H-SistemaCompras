<?php
/**
*Esta clase realiza operaciones matemáticas.
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license ruta: /var/www/html/modules/views/SCO_Embarque
*/
if (! defined ( 'sugarEntry' ) || ! sugarEntry) die ( 'Not A Valid Entry Point' );

require_once ('include/MVC/View/views/view.edit.php');
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');

class SCO_EmbarqueViewEdit extends ViewEdit {

  function SCO_EmbarqueViewEdit() {
    parent::ViewEdit ();
    $this->useForSubpanel = true;
  }

  function display(){
  	echo '<script src="/modules/SCO_Embarque/javajs.js" type="text/javascript" ></script>';
  	//QUery, ordenando los nombres del modulo de CNF_EVENTOS
  	$query ="SELECT DISTINCT(name) FROM sco_cnf_eventos ORDER BY name asc";
  	$obj = $GLOBALS['db']->query($query, true);
  	$cont = 1;
  	$origen = [];  
  	#echo "<script>alert('".$this->bean->emb_orig.$this->bean->emb_transp."')</script>";
	while($row = $GLOBALS['db']->fetchByAssoc($obj))
	{
		#$bean_emb = BeanFactory::getBean($)
		echo "<script>
		var origen = '".$this->bean->emb_orig."';
		var transp = '".$this->bean->emb_transp."';
		window.onload = function(){	
			if(origen == ''){
		  		$(\"#emb_origen\").append(\"<option value='".$row['name']."' >".$row['name']."</option>\");
		  	}else{
		  		$(\"#emb_origen\").append(\"<option value='\"+origen+\"' selected>\"+origen+\"</option>\");
		  		$(\"#emb_modtra\").append(\"<option value='\"+transp+\"' selected>\"+transp+\"</option>\");
		  	}		
	  	$(\"#emb_volumen\").css(\"width\",\"70px\");
	  	$(\"#emb_peso\").css(\"width\",\"70px\");
	  	$(\"#emb_unidad\").css(\"width\",\"70px\");
	  	$(\"#emb_diastran\").css({\"width\":\"70px\",\"background\":\"#eee\"});
	  	//$(\"#emb_diastran\").prop('disabled', true);

	  	}
	  	if(origen == ''){
		  	$(\"#emb_origen\").append(\"<option value='".$row['name']."'>".$row['name']."</option>\");	  	
	  	}else{
	  		$(\"#emb_origen\").append(\"<option value='\"+origen+\"' selected>\"+origen+\"</option>\");
	  		$(\"#emb_modtra\").append(\"<option value='\"+transp+\"' selected>\"+transp+\"</option>\");
	  	}	  	
	  	$(\"#emb_volumen\").css(\"width\",\"70px\");
	  	$(\"#emb_peso\").css(\"width\",\"70px\");
	  	$(\"#emb_unidad\").css(\"width\",\"70px\");
	  	$(\"#emb_diastran\").css({\"width\":\"70px\",\"background\":\"#eee\"});
	  	//$(\"#emb_diastran\").prop('disabled', true);
	  	</script>";	
	  	$cont++;
	  	array_push($origen, $row['name']);
	}
  	parent::display();
  }

}