<?php
/**
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license ruta: /var/www/html/modules/views/SCO_Embarque
*/
if (! defined ( 'sugarEntry' ) || ! sugarEntry) die ( 'Not A Valid Entry Point' );

require_once ('include/MVC/View/views/view.edit.php');
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');

class SCO_EventosViewEdit extends ViewEdit {

  function SCO_EventosViewEdit() {
    parent::ViewEdit (); 
    
    $this->useForSubpanel = true;
  }
  function display(){
 	  //QUery, ordenando los nombres del modulo de CNF_EVENTOS_list
  	$query ="SELECT name FROM suitecrm.sco_cnf_eventos_list WHERE tipo = 0 AND deleted = 0;";
  	$obj = $GLOBALS['db']->query($query, true);
  	$cont = 1;
  	$origen = [];  
  	#echo "<script>alert('".$this->bean->emb_orig.$this->bean->emb_transp."')</script>";
  	while($row = $GLOBALS['db']->fetchByAssoc($obj))
  	{  		  
  	  	$cont++;
 	   	  array_push($origen, $row['name']);                           
  	}
     //QUery, ordenando los nombres del modulo de CNF_EVENTOS_list
  	$query2 ="SELECT name FROM suitecrm.sco_cnf_eventos_list WHERE tipo = 1 AND deleted = 0;";
  	$obj2 = $GLOBALS['db']->query($query2, true);
  	$cont = 1;
  	$aduana = [];  
  	#echo "<script>alert('".$this->bean->emb_orig.$this->bean->emb_transp."')</script>";
  	while($row2 = $GLOBALS['db']->fetchByAssoc($obj2))
  	{  		  
  	  	$cont++;
 	   	  array_push($aduana, $row2['name']);                           
  	}
    echo "<script>
       var trans = '".$this->bean->transportistaotros."';
       var arr = '".json_encode($origen)."';
       var aduana = '".$this->bean->agenciaaduanera."';    
       var arr2 = '".json_encode($aduana)."';
       console.log(arr); 
       function pegarOtros(){
          var trans2 = document.getElementById(\"otros\");         
          $('input[name=transportistaotros]').val(trans2.value);
        }
       function pegarAduana(){
          var aduana = document.getElementById(\"aduana\");         
          $('input[name=agenciaaduanera]').val(aduana.value);
        }                
    </script>";
  
    echo '<style>
      #transportistaotros, 
      #agenciaaduanera {
        width: 180px;      
      }
      #otros, #aduana{
        width: 180px;      
      }
  
      input[name=transportistaotros],
      input[name=agenciaaduanera]  {
        disabled: true;
         pointer-events: none;
         background: #eee;
      }
    </style>';   
  	echo '<script src="/modules/SCO_Embarque/javajs.js" type="text/javascript" ></script>';
    echo "<script>        
    window.onload = function(){
        arr = arr.replace('[','');
        arr = arr.replace(']','');
        arr = arr.split(',');
        console.log(arr);   
        var html = ''
        for(var i = 0; i<arr.length; i++){
          console.log(arr[i].replace(/['\"]+/g,''));
          valor = arr[i].replace(/['\"]+/g,'')
          html +='<option value=\"'+valor+'\">'+valor+'</option>';
        }        
        $(\"#transportistaotros\").before('<select id=\"otros\" name=\"otros\" onchange=\"pegarOtros();\">'+html+'</select><span style=\"margin-right: 15px;\"></span>');
        
        arr2 = arr2.replace('[','');
        arr2 = arr2.replace(']','');
        arr2 = arr2.split(',');
        console.log(arr2);   
        var html2 = ''
        for(var i = 0; i<arr2.length; i++){
          console.log(arr2[i].replace(/['\"]+/g,''));
          valor = arr2[i].replace(/['\"]+/g,'')
          html2 +='<option value=\"'+valor+'\">'+valor+'</option>';
        }
        $(\"#agenciaaduanera\").before('<select id=\"aduana\" name=\"aduana\" onchange=\"pegarAduana();\">'+html2+'</select><span style=\"margin-right: 15px;\"></span>');
        console.log('captura de arreglo' + arr);
        
       
    }
    </script>";
   
     
  	parent::display();
  }

}