<?php
/**
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2020
*@license ruta: /var/www/html/include/generic/SugarWidgets/
*/
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');

/**
*Descripcion: Esta clase visualiza la nueva lista de productos relacionado a una Orden de Compra.
*/
class SugarWidgetSubPanelProductosList extends SugarWidgetField
{
	  function displayHeaderCell($layout_def){
        return '<a style="font-weight: normal;color:#fff;">Items</a>';
    }
    
    function displayList($layout_def)
    {
      $id = $layout_def['fields']['ID'];
      $beanp = BeanFactory::getBean('SCO_Productos', $id);
      
      $arr1 = $beanp->description;      
      //DESCOMPONIENDO EL ARRAY QUE SE ENCUENTRA EN EL CAMPO DE description del modulo SCO_PRODUCTOS.
      $arr1 = str_replace("[[", "", $arr1);
  	 	$arr1 = str_replace("]]", "", $arr1);
	    $arr1 = str_replace("[", "", $arr1);
	    $arr1 = str_replace("],", "|", $arr1);
	    $arr1 = str_replace("&quot;","'",$arr1);
	    $arr1 = str_replace("','", "~", $arr1);
     	$arr1 = str_replace("'", "", $arr1);
	    $arr2 = explode("|", $arr1);         
	    $arridoc = array_pop($arr2); 
      $beanOc = BeanFactory::getBean('SCO_OrdenCompra', $arridoc); 
      $ocEstado = $beanOc->orc_estado;
      $moneda = $beanOc->orc_tcmoneda;        
	    $arrprec = array_pop($arr2);
        $totales = explode(",", $arrprec);         
	    $arrprec = explode(",", $arrprec);
      $tbody = "";
      $nr = 0;
      
      #Despliegue de cantidades de productos
      $cantidades_p = "SELECT                        
                      pro_nombre,
                      pro_descripcion,
                      pro_unidad,
                      pro_cantidad,
                      pro_preciounid,
                      pro_descval,
                      pro_descpor,
                      pro_subtotal,
                      pro_saldos,
                      pro_canttrans,
                      pro_cantresivida,
                      pro_nomproyco,
                      pro_tipocotiza,
                      pro_codaio
                    FROM sco_productos_co
                    WHERE pro_idco = '".$arridoc."' 
                    AND deleted = 0 
                    ";
      $obj_cantidades_p = $GLOBALS['db']->query($cantidades_p, true);
      #Variables para los campos
      $arr_nombre = array();
      $arr_pro_codaio = array();
      $arr_descripcion = array();
      $arr_unidad = array();
      $arr_cantidadProducto = array();
      $arr_preciounid  = array();
      $arr_descval = array();
      $arr_descpor = array();
      $arr_subtotal = array();
      $arr_cantidades_p = array();
      $arr_recibido_p = array();
      $arr_saldos_p = array();
      $arr_nomProy = array();
      $arr_nomProyTipo = array();

      while($row_cantidades_p = $GLOBALS['db']->fetchByAssoc($obj_cantidades_p)){
        array_push($arr_nombre, $row_cantidades_p['pro_nombre']);
        array_push($arr_pro_codaio, $row_cantidades_p['pro_codaio']);
        array_push($arr_descripcion, $row_cantidades_p['pro_descripcion']);
        array_push($arr_unidad, $row_cantidades_p['pro_unidad']);        
        array_push($arr_cantidadProducto, $row_cantidades_p['pro_cantidad']);
        array_push($arr_preciounid, $row_cantidades_p['pro_preciounid']);
        array_push($arr_descval, $row_cantidades_p['pro_descval']);
        array_push($arr_descpor, $row_cantidades_p['pro_descpor']);
        array_push($arr_subtotal, $row_cantidades_p['pro_subtotal']);

        array_push($arr_cantidades_p, $row_cantidades_p['pro_canttrans']);
        array_push($arr_recibido_p, $row_cantidades_p['pro_cantresivida']);
        array_push($arr_saldos_p, $row_cantidades_p['pro_saldos']);
          
        array_push($arr_nomProy, $row_cantidades_p['pro_nomproyco']);
        array_push($arr_nomProyTipo, $row_cantidades_p['pro_tipocotiza']);
      }
      #var_dump($arr_cantidades_p);       
      #-------------------------------------#
      
      echo "<span style='display:none;'id='idOrdeCompra'>".$beanp->description."</span>";
	    for ($i=0; $i<count($arr2); $i++)
			{
        $nr++;
		    $textfila = $arr2[$i];
		    $fila = explode("~", $textfila);
		    $idpc =  str_replace("&gt;",">",str_replace("&lt;","<",str_replace("**","\"",$fila[0])));
        $descr = str_replace("&aacute;","Ã¡",
														str_replace("&eacute;","Ã©",
														str_replace("&iacute;","Ã­",
														str_replace("&oacute;","Ã³",
														str_replace("&uacute;","Ãº",
														str_replace("&Aacute;","Ã",
														str_replace("&Eacute;","Ã‰",
														str_replace("&Oacute;","Ã“",
														str_replace("&Iacute;","Ã",
														str_replace("&Uacute;","Ãš",
														str_replace("&ntilde;","Ã±",
														str_replace("&Ntilde;","Ã‘",
														str_replace("&acute;","Â´",
														str_replace("&gt;",">",
														str_replace("&lt;","<",
														str_replace("**","\"",$fila[1])))))))))))))
													)
												)
											);
		    $unid = $fila[2];
		    $cant = $fila[3];
		    $prec = $fila[4];
		    $dscp = $fila[5];
		    $dscv = $fila[6];
		    $stot = $fila[7];
		    $idpo = $fila[8];
		    $idpro = $fila[9];
		    $idproy = $fila[10];
		    $tipoProy = $fila[11];
				$aio = $fila[12];
        
        if($ocEstado == 1 || $ocEstado == 6 ){
          $filaDato .= "<tr>
          <td style='background:#fff;'>".$nr."</td>
          ";
          if(trim($idpc) != ''){
            $filaDato .= "<td><a href=\"index.php?module=SCO_ProductosCompras&action=DetailView&record=".$idpro."\">".$idpc."</a></td>";
          }else{
            $filaDato .= "<td style='background:#ffa8a8;'><a href=\"index.php?module=SCO_ProductosCompras&action=DetailView&record=".$idpro."\">".$idpc."</a></td>";
          }   
                 
          $filaDato .= "
          <td>".$arr_pro_codaio[$i]."</td>
          <td>".$arr_descripcion[$i]."</td>
          <td>".$arr_unidad[$i]."</td>
          <td>".$arr_cantidadProducto[$i]."</td>
          <td>".$arr_preciounid[$i]."</td>
          <td>".$arr_descpor[$i]."</td>
          <td>".$arr_descval[$i]."</td>
          <td>".$arr_subtotal[$i]."</td>
          <td>".$arr_nomProy[$i]."</td>
          <td style='Background:#fff;color:green;'>".$arr_cantidades_p[$i]."</td>
          <td style='Background:#fff;'>".$arr_recibido_p[$i]."</td>          
          <td style='Background:#fff; '><span class='text-danger'>".$arr_saldos_p[$i]."</span></td>
          
          </tr>";
        }else{
          $filaDato .= "<tr>
          <td style='Background:#fff;'>".$nr."</td>
          ";
          if(trim($idpc) != ''){
            $filaDato .= "<td><a href=\"index.php?module=SCO_ProductosCompras&action=DetailView&record=".$idpro."\">".$idpc."</a></td>";
          }else{
            $filaDato .= "<td style='background:#ffa8a8;'><a href=\"index.php?module=SCO_ProductosCompras&action=DetailView&record=".$idpro."\">".$idpc."</a></td>";
          }          
          $filaDato .= "
          <td>".$aio."</td>
          <td>".$descr."</td>
          <td>".$unid."</td>
          <td>".$cant."</td>
          <td>".$prec."</td>
          <td>".$dscp."</td>
          <td>".$dscv."</td>
          <td>".$stot."</td>
          <td>".$idpo."</td>
          </tr>";  
        }
      }
      
      $style = "<style>
      #list_subpanel_sco_ordencompra_sco_productos .list tr .oddListRowS1 td .footable-first-visible{display: none;}
      #idpro{margin-bottom: 3px;background: #FFF;font-size: 12px;}
      #idpro:hover {background: #FFF;}
      #idpro thead tr th{background: #e6e6e6;color:#555;padding: 5px;border-bottom: 1px solid #ccc;}
      #idpro tfoot tr td {background:#FFF;}
      #bodyData tr td{background: #ffffffaf;}
      </style>";

      $htmlFoot .= '<tfoot>';
			$htmlFoot .= '<tr>';
			$htmlFoot .= '<td></td><td><b>SubTotal</b></td><td>'.$totales[0].' <b>'.$moneda.'</b></td>';
			$htmlFoot .= '</tr>';
			$htmlFoot .= '<tr>';
			$htmlFoot .= '<td></td><td><b>Descuento Valor</b></td> <td>'.$totales[1].' </td>';
			$htmlFoot .= '</tr>';
			$htmlFoot .= '<tr>';
			$htmlFoot .= '<td></td><td><b>Descuento %</b></td><td>'.$totales[2].' %</td>';
			$htmlFoot .= '</tr>';
			$htmlFoot .= '<tr>';
			$htmlFoot .= '<td></td><td><b>Total</b></td><td>'.$totales[3].' <b>'.$moneda.'</b></td>';
			$htmlFoot .= '</tr>';
      $htmlFoot .= '</tfoot>';
      
      if($ocEstado == 1 || $ocEstado== 6 ){
        $html = "
        <table id='idpro' class='table table-hover table-striped' >
        <thead style='background:#707d84;  ' >
        <tr>
        <th style='width: 1%;'>#</th>
        <th style='width: 10%;'>Cod Pro</th>
        <th style='width: 5%;'>AIO</th>
        <th style='width: 40%;'>Descripcion</th>
        <th style='width: 5%;'>Unidad</th>
        <th style='width: 5%;'>Cantidad</th>
        <th style='width: 5%;'>Prec Uni</th>
        <th style='width: 5%;'>Desc %</th>
        <th style='width: 5%;'>Desc valor</th>
        <th style='width: 5%;'>Sub total</th>
        <th style='width: 5%;'>Proy C/O</th>
        <th style='width: 7%;'>Transito</th>
        <th style='width: 7%;'>Recibido</th>
        <th style='width: 7%;'>Saldos</th>       
        </tr>
        </thead>
        <tbody id='bodyData'>
        ".$filaDato."
        </tbody>
        ".$htmlFoot."
        </table>
        ";
      }else{
        $html = "
        <table id='idpro' class='table table-hover table-striped' >
        <thead style='background:#707d84;  ' >
        <tr>
        <th style='width: 1%;'>#</th>
        <th style='width: 10%;'>Cod Pro</th>
        <th style='width: 5%;'>AIO</th>
        <th style='width: 40%;'>Descripcion</th>
        <th style='width: 5%;'>Unidad</th>
        <th style='width: 5%;'>Cantidad</th>
        <th style='width: 5%;'>Prec Uni</th>
        <th style='width: 5%;'>Desc %</th>
        <th style='width: 5%;'>Desc valor</th>
        <th style='width: 5%;'>Sub total</th>
        <th style='width: 5%;'>Proy C/O</th>
        </tr>
        </thead>
        <tbody id='bodyData'>
        ".$filaDato."
        </tbody>
        ".$htmlFoot."
        </table>
        ";      
      }                     
       return $style.$html.$tbody;
    }
}
