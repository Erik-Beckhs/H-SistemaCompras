<?php
/**
*Esta clase realiza el correlativo de Aprobadores para listas
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license /var/www/html/custom/modules/SCO_Aprobadores
*/
class Clcorrelativo
{
  //static $already_ran = false;
  static $cnt_reg = 11;
  static $ult_rid = "";

  function Fncorrelativo($bean, $event, $arguments)
  {
    #Obteniendo Id OC
    $id_p = $bean->id;
    $bean->load_relationship('sco_ordencompra_sco_aprobadores');
    $relatedBeans = $bean->sco_ordencompra_sco_aprobadores->getBeans();
    reset($relatedBeans);
    $parentBean = current($relatedBeans);
    $idoc = $parentBean->id;
    #Generando los numeros de saltos e incrementos
    #Obtenemos la cantidad de aprobadores
    $queryApro = "SELECT count(sco_ordencompra_sco_aprobadoressco_aprobadores_idb) as total
                  FROM suitecrm.sco_ordencompra_sco_aprobadores_c
                  where deleted = 0 and  sco_ordencompra_sco_aprobadoressco_ordencompra_ida = '$idoc'";
    $resultsApro = $GLOBALS['db']->query($queryApro, true);
    $rowApro = $GLOBALS['db']->fetchByAssoc($resultsApro);
    #Obteniendo la cantidad de decimales sobrantes
    $decimales = ($rowApro["total"]+1) / 7;
    #Declaramos la cantidad de
    $valSalto = 0;
    $incremento = 0;
    // La funciÃ³n intval nos extrae solo los numeros enteros de un numero decimal
    $saldos = $decimales - (intval($decimales));
    $saldoFirmas = $saldos * 10;
    if (intval($saldoFirmas) > 1){
      //echo $saldoFirmas." Existe 1 firma abajo => Necesario validar 7 y 5";
      $valSalto = 7;
      $incremento = 5;
    }
    else{
      //echo $saldoFirmas." No hay firmas de 1 => Necesario validar 8 y 4";
      $valSalto = 8;
      $incremento = 4;
    }
    #Generando la numeraciÃ³n correlativa
    $Numeracion = array();
    if ($rowApro["total"] == 0) {
      $Numeracion[1] = 11;
    }
    else {
      for ($i=0; $i < ($rowApro["total"]+1) ; $i++) {
        if($i == 0)
        {
          $Numeracion[$i] = 10;
        }else
        {
          $Numeracion[$i] = $Numeracion[$i - 1] + 1;
          $salto = $Numeracion[$i] % 10;
          if($salto == $valSalto)
          {
            $Numeracion[$i] = $Numeracion[$i - 1] + $incremento;
          }
        }
      }
    }

    #asignamos los correlativos a la lista de aprobadores
    $index = 1;

      #Modificando los correlativos ella lista de Aprobadores
      $query = "SELECT ap.id,ap.apr_correlativo from sco_aprobadores ap
                inner join sco_ordencompra_sco_aprobadores_c ocap on ocap.sco_ordencompra_sco_aprobadoressco_aprobadores_idb = ap.id
                where ocap.sco_ordencompra_sco_aprobadoressco_ordencompra_ida =  '$idoc' and ocap.deleted = 0
                order by ap.date_modified";
      $results = $GLOBALS['db']->query($query, true);
      while ($row = $GLOBALS['db']->fetchByAssoc($results)) {
        #Actualizamos el correlativo de la lista de aprobadores
        $query_Ap = "UPDATE sco_aprobadores
                    SET apr_correlativo = '".$Numeracion[$index]."'
                    WHERE id = '".$row["id"]."' " ;
        $obj_p = $GLOBALS['db']->query($query_Ap, true);
        $index++;
      }
      if(self::$ult_rid == $bean->id) return;
      self::$ult_rid = $bean->id;
  /*
    if(self::$ult_rid == $bean->id) return;
    self::$ult_rid = $bean->id;

    if($row['ultimo'] == null)
    {
      self::$cnt_reg = 11;
    }else
    {
      self::$cnt_reg = $row['ultimo'] + 1;
      $salto = self::$cnt_reg % 10;
      if($salto == $valSalto)
      {
        self::$cnt_reg = $row['ultimo'] + $incremento;
      }
    }
*/
    //llena correlativo
    $bean->apr_correlativo = $Numeracion[$index];

    $bean->save();
  }
}
?>
