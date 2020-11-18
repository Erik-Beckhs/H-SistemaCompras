<?php
/**
*Esta clase realiza operaciones matemÃ¡ticas.
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license ruta: /var/www/html/custom/modules/SCO_Despachos
*/
class Clproddes
{
  static $already_ran = false;
  function Fnproddes($bean, $event, $arguments)
  {
    if(self::$already_ran == true) return;
    self::$already_ran = true;
    //Obteniendo el id de la Orden de Compra
    $bean->load_relationship('sco_despachos_sco_ordencompra');
    $relatedBeans = $bean->sco_despachos_sco_ordencompra->getBeans();
    reset($relatedBeans);
    $parentBean = current($relatedBeans);
    $idoc = $parentBean->id;
    //Cargando datos a campos de Despachos
    $bean->des_origen = $bean->des_orig;
    $bean->des_modtrans = $bean->des_modtra;
    $bean->des_diastrans = $bean->des_diasllegada;

    //pobla usuario actual logeado
    global $current_user;
    $id_usuario = $current_user->id;
    $bean->assigned_user_id = $current_user->id;
    $bean->iddivision_c = $current_user->iddivision_c;
    $bean->idregional_c = $current_user->idregional_c;
    $bean->idamercado_c = $current_user->idamercado_c;
    //cargando realacion de DESPACHOS con PRODUCTOS DESPACHOS
    //Obteniedno Id de la ORDEN DE COMPRA
    $desp = "SELECT count(*) as cantidad
      FROM sco_despachos_sco_productosdespachos_c
      where sco_despachos_sco_productosdespachossco_despachos_ida = '".$bean->id."'
      and deleted = 0";
    $obj_des = $GLOBALS['db']->query($desp, true);
    $row_des = $GLOBALS['db']->fetchByAssoc($obj_des);
    //Validando cantidad
    if ($row_des["cantidad"] == 0){
        if($bean->assigned_user_id == '')
        $bean->assigned_user_id = $id_usuario;
        //Query para no tener problemas con los Beans de Despachos
        $cantidad_prd = "SELECT SUM(pro_cantidad) as total_cantidad FROM sco_productos_co WHERE pro_idco = '".$idoc."'; ";
        $obj_pro = $GLOBALS['db']->query($cantidad_prd, true);
        $row_pro = $GLOBALS['db']->fetchByAssoc($obj_pro);
        //Query, total de cantidades de PRODUCTOS DESPACHOS
        $total_cant = "SELECT IFNULL(SUM(pd.prdes_cantidad),0) as cantidad_pr
        FROM sco_productosdespachos as pd
        INNER JOIN sco_despachos_sco_productosdespachos_c as dpd
        ON pd.id = dpd.sco_despachos_sco_productosdespachossco_productosdespachos_idb
        INNER JOIN sco_despachos_sco_ordencompra_c as d_oc
        ON d_oc.sco_despachos_sco_ordencomprasco_despachos_idb = dpd.sco_despachos_sco_productosdespachossco_despachos_ida
        WHERE d_oc.sco_despachos_sco_ordencomprasco_ordencompra_ida = '".$idoc."'
        AND pd.deleted = 0
        AND dpd.deleted = 0; ";
        $obj_total_cant = $GLOBALS['db']->query($total_cant, true);
        $row_total_cant = $GLOBALS['db']->fetchByAssoc($obj_total_cant);
        //Query, Total de cantidades en transito de la tabla sco_productos_co
        $total_canttransito = "SELECT SUM(pro_canttrans) as Cantidad_transito
        FROM suitecrm.sco_productos_co
        WHERE pro_idco = '".$idoc."';";

        //Creando correlativo del nombre despachos 001
        $query_oc = "SELECT SUM(orc_1) as correlativo_despacho FROM sco_ordencompra WHERE deleted = 0";
        $obj_oc = $GLOBALS['db']->query($query_oc, true);
        $row_oc = $GLOBALS['db']->fetchByAssoc($obj_oc);
        $correlativo_despacho = $row_oc['correlativo_despacho'];
        //Sumando el correlativo en el MODULO OREN DE COMPRA
        $bean_oc = BeanFactory::getBean('SCO_OrdenCompra', $idoc);
        $bean_oc->orc_1 = $correlativo_despacho + 1;
        $bean_oc->save();
        //Query para obtener los datos dela tabla SCO_PRODUCTOS_CO de acuerdo al id del Orden de Compra
        $query = "SELECT * FROM sco_productos_co WHERE pro_idco = '".$idoc."' AND deleted = 0; ";
        $obj = $GLOBALS['db']->query($query, true);
        //Condicion, la cantidad de productos menos la cantidad total de productos despachos sea dif de 0
        if(($row_total_cant['cantidad_pr'] - $row_pro['total_cantidad']) != 0)
        {
          while($row = $GLOBALS['db']->fetchByAssoc($obj)){
            if($row["pro_saldos"] > 0 || $row["pro_canttrans"] == 0){
                //Operacion, resta de cantidad del producto con la cantidad en transito
                $cant_new = $row["pro_saldos"] + $row["pro_canttrans"];
                $cant_new = abs($cant_new);
                //Creando un nuevo registro con los bean en el modulo de PRODUCTOS DESPACHOS
                $desprobean = BeanFactory::newBean('SCO_ProductosDespachos');
                $desprobean->name                       = $row["pro_nombre"];
                //Se usara el campo descripcion para guardar los descuentos en valor de la tabla sco_producto_co pro_descval
                $desprobean->description                = $row["pro_descval"];
                $desprobean->prdes_descripcion          = $row["pro_descripcion"];
                $desprobean->prdes_codaio               = $row["pro_codaio"];
                $desprobean->prdes_cantidad             = $row["pro_saldos"];
                $desprobean->prdes_unidad               = $row["pro_preciounid"];

                $desprobean->sco_produccf02compras_ida  = $row["pro_idpro"];
                $desprobean->prdes_idproductos_co       = $row["id"];
                //logeo de usuario
                $desprobean->assigned_user_id           = $current_user->id;
                $desprobean->iddivision_c               = $current_user->iddivision_c;
                $desprobean->idregional_c               = $current_user->idregional_c;
                $desprobean->idamercado_c               = $current_user->idamercado_c;
                $desprobean->prdes_desc                 = $row["pro_descpor"];
                $desprobean->prdes_descmonto            = $row["pro_descval"];
                $desprobean->prdes_nomproyco            = $row["pro_nomproyco"];
                $desprobean->prdes_tipocotiza           = $row["pro_tipocotiza"];
                $desprobean->prdes_idproductocotiazdo   = $row["pro_idproductocotizado"];
                //validando la cantidad asignada al campo de cantidad del modulo de PRODUCTOS DESPACHOS
                if($desprobean->prdes_cantidad > 0){
                    $desprobean->save();
                }
                //Creando ID para pa relacion
                $id_despro_rel = create_guid();
                $id_pc_pd = create_guid();
                //Creado la relacion de DESPACHOS con PRODUCTOS DESPACHOS
                $query2 = "INSERT INTO sco_despachos_sco_productosdespachos_c
                  (id, date_modified, deleted, sco_despachos_sco_productosdespachossco_despachos_ida, sco_despachos_sco_productosdespachossco_productosdespachos_idb)
                  VALUES
                  ('".$id_despro_rel."', '".$bean->des_fechacrea."', '0', '".$bean->id."', '".$desprobean->id."');";
                $obj2 = $bean->db->query($query2, true);

                //Creando la relacion de PRODUCTOS COMPRAS con DESPACHOS
                $r_pc_des = "INSERT INTO sco_productoscompras_sco_despachos_c
                  (id, date_modified, deleted, sco_productoscompras_sco_despachossco_productoscompras_ida, sco_productoscompras_sco_despachossco_despachos_idb, id_productodespacho)
                  VALUES
                  ('".$id_pc_pd."', '".$bean->des_fechacrea."', '0', '".$row['pro_idpro']."', '".$bean->id."', '".$desprobean->id."');";
                $obj_pc_des = $bean->db->query($r_pc_des, true);

                //**Actualizando la tabla de SCO_PRODUCTOS_CO los campos de cantidad transito, y saldos
                $query_p = "UPDATE sco_productos_co
                SET pro_canttrans = '".$cant_new."',  pro_saldos = '0' WHERE id = '".$row["id"]."';";
                $obj_p = $GLOBALS['db']->query($query_p, true);

                //**Acualizando el modulo PRODUCTOS COTIZADOS campos de cantidad y saldos
                $bean_prodcotiza = BeanFactory::getBean('SCO_ProductosCotizados', $row["id"]);
                $bean_prodcotiza->pro_canttrans = $cant_new;
                $bean_prodcotiza->pro_saldos = '0';
                $bean_prodcotiza->save();

                $bean->save();
            }else{
              echo "<script>alert('No Existen cantidades para este despacho 0');</script>";
            }
          }
        }else{
          echo "<script>alert('No Existen cantidades para este despacho');</script>";
          #die(SugarApplication::redirect('index.php?module=SCO_Despachos&action=DetailView&record='.$bean->id));
          exit();
        }
    }
  }
}
?>
