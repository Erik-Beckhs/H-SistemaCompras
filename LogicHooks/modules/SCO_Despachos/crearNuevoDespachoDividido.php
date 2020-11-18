<?php
/**
*Esta clase cumple la funcion de dividir despachos.
*
*@author Reynaldo Kantuta <rkantuta@hansa.com.bo>
*@copyright 2018
*@license ruta: /var/www/html/modules/SCO_Despachos
*/
if(!defined('sugarEntry'))define('sugarEntry', true);
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');
// recibimos los datos del nuevo despacho
/*
$idPro = $_POST["idPro"];
$data = array();
for ($i=0; $i <count($idPro) ; $i++) {
  $query = "SELECT * from sco_productosdespachos where id = '".$idPro[$i]."'";
  $obj = $GLOBALS['db']->query($query, true);
  while($row = $GLOBALS['db']->fetchByAssoc($obj))
  {
      $data[] = $row["name"];
  }
}
echo json_encode($data);
*/
global $current_user;
$idUser = $current_user->id;
$dateFC = date_create(date("Y-m-d H:i:s"));
if(isset($_POST["idPro"]))
{
  $saldo = $_POST["saldo"];
  $nuevoTotal = $_POST["nuevoTotal"];
  $original = $_POST["original"];
  $idPro = $_POST["idPro"];
  $idOc = $_POST["idOc"];
  $origen = $_POST["origen"];
  $mod_trans = $_POST["mod_trans"];
  $dias_trans = $_POST["dias_trans"];
  //$prdes_idproductos_co = $_POST["prdes_idproductos_co"];
  $idDespacho = $_POST["idDespacho"];
  // creamos el nuevo depacho
  //Creando ID para el nuevo despacho
  $id_despacho = create_guid();
  //Obtenemos los datos del despacho
  $queryProDespachos = "SELECT * FROM sco_despachos where id = '".$idDespacho[0]."'";
  $productos_despachos = $GLOBALS['db']->query($queryProDespachos, true);
  $rowProductosDespachos = $GLOBALS['db']->fetchByAssoc($productos_despachos);
  //creamos una copia del despacho
  $insertarDespacho = "INSERT INTO sco_despachos
    (id,name,date_entered,created_by,des_origen,des_modtrans,des_diastrans,des_diasllegada,des_orig,des_modtra,assigned_user_id,iddivision_c,idregional_c,idamercado_c,des_fechacrea,des_fechaprev,des_prioridad)
    VALUES
    (
      '".$id_despacho."',
      '".$rowProductosDespachos["name"]."_1"."',
      '".date_format($dateFC, 'Y-m-d H:m:s')."',
      '".$idUser."',
      '".$origen."',
      '".$mod_trans."',
      '".$dias_trans."',
      '".$dias_trans."',
      '".$origen."',
      '".$mod_trans."',
      '".$rowProductosDespachos["assigned_user_id"]."',
      '".$rowProductosDespachos["iddivision_c"]."',
      '".$rowProductosDespachos["idregional_c"]."',
      '".$rowProductosDespachos["idamercado_c"]."',
      '".date_format($dateFC, 'Y-m-d H:m:s')."',
      '".$rowProductosDespachos["des_fechaprev"]."',
      '".$rowProductosDespachos["des_prioridad"]."'
    )";
  $GLOBALS['db']->query($insertarDespacho, true);
  //Crear relaciÃ³n entre orden de compra y despachos
  $idRelDespachoOc = create_guid();
  $query2 = "INSERT INTO sco_despachos_sco_ordencompra_c
    (id,deleted,sco_despachos_sco_ordencomprasco_ordencompra_ida,sco_despachos_sco_ordencomprasco_despachos_idb)
    VALUES
    ('".$idRelDespachoOc."', '0', '".$idOc[0]."', '".$id_despacho."')";
  $GLOBALS['db']->query($query2, true);
  // Validamos si la cantidad de items no esta vacia
  if (count($idPro) > 0){
    for ($i=0; $i < count($idPro); $i++) {
      if($original[$i] == $nuevoTotal[$i] && $original[$i] > 0){
        $queryPrd = "SELECT * from sco_productosdespachos where id = '".$idPro[$i]."' AND deleted = 0";
        $objs = $GLOBALS['db']->query($queryPrd, true);
        while($row = $GLOBALS['db']->fetchByAssoc($objs))
        {
          //Generando el ID de PROUDCTO DESPACHO
          $id_ProductoDespacho = create_guid();
          //Insertamos el nuevo PRODUCTO DESPACHO
          $productoDespacho = "INSERT INTO sco_productosdespachos
            (
            id, 
            name,
            prdes_codaio,
            prdes_cantidad,
            prdes_unidad,
            prdes_idproductos_co,
            assigned_user_id,
            prdes_descripcion,
            iddivision_c,
            idregional_c,
            idamercado_c,
            prdes_desc,
            prdes_descmonto,
            prdes_nomproyco,
            prdes_tipocotiza,
            prdes_idproductocotiazdo
            )
            VALUES
            (
              '".$id_ProductoDespacho."',
              '".$row["name"]."',
              '".$row["prdes_codaio"]."',
              '".$nuevoTotal[$i]."',
              '".$row["prdes_unidad"]."',
              '".$row["prdes_idproductos_co"]."',
              '".$current_user->id."',
              '".$row["prdes_descripcion"]."',
              '".$current_user->iddivision_c."',
              '".$current_user->idregional_c."',
              '".$current_user->idamercado_c."',
              '".$row["prdes_desc"]."',
              '".$row["prdes_descmonto"]."',
              '".$row["prdes_nomproyco"]."',
              '".$row["prdes_tipocotiza"]."',
              '".$row["prdes_idproductocotiazdo"]."'
            )";
          $GLOBALS['db']->query($productoDespacho, true);
          //Creando ID para pa relacion
          $id_despro_rel = create_guid();
          //Creado la relacion de DESPACHOS con PRODUCTOS DESPACHOS
          $relacionProDes = "INSERT INTO sco_despachos_sco_productosdespachos_c
            (id, date_modified, deleted, sco_despachos_sco_productosdespachossco_despachos_ida, sco_despachos_sco_productosdespachossco_productosdespachos_idb)
            VALUES
            (
              '".$id_despro_rel."',
              '".$row["date_entered"]."',
              '0',
              '".$id_despacho."',
              '".$id_ProductoDespacho."'
            )";
          $GLOBALS['db']->query($relacionProDes, true);
          //Creando la relacion de PRODUCTOS COMPRAS con DESPACHOS
          $id_pc_pd = create_guid();
          $r_pc_des = "INSERT INTO sco_productoscompras_sco_despachos_c
            (id, date_modified, deleted, sco_productoscompras_sco_despachossco_productoscompras_ida, sco_productoscompras_sco_despachossco_despachos_idb, id_productodespacho)
            VALUES
            (
              '".$id_pc_pd."',
              '".$row["date_entered"]."',
              '0',
              '".$row["prdes_idproductos_co"]."',
              '".$id_despacho."',
              '".$id_ProductoDespacho."'
            )";
          $GLOBALS['db']->query($r_pc_des, true);
        //Eliminando productos del despacho
        $ProDespachos = "UPDATE sco_productosdespachos SET deleted = '1' WHERE id = '".$row["id"]."'";
        $GLOBALS['db']->query($ProDespachos, true);
        }
      }
      if($original[$i] > $nuevoTotal[$i] && $nuevoTotal[$i] > 0) {
          //Extraemos la informacion de los productos del despacho del cual se quiere dividir
          $queryPrd = "SELECT * from sco_productosdespachos where id = '".$idPro[$i]."'";
          $objs = $GLOBALS['db']->query($queryPrd, true);
          //Vaciamos la consulta SQL
          while($row = $GLOBALS['db']->fetchByAssoc($objs)){
            //Generando el ID de PROUDCTO DESPACHO
            $id_ProductoDespacho = create_guid();
            //Insertamos el nuevo PRODUCTO DESPACHO
            $productoDespacho = "INSERT INTO sco_productosdespachos
              (id, 
              name,
              prdes_codaio,
              prdes_cantidad,
              prdes_unidad,
              prdes_idproductos_co,
              assigned_user_id,
              prdes_descripcion,
              iddivision_c,
              idregional_c,
              idamercado_c,
              prdes_desc,
              prdes_descmonto,
              prdes_nomproyco,
              prdes_tipocotiza,
              prdes_idproductocotiazdo)
              VALUES
              (
                '".$id_ProductoDespacho."',
                '".$row["name"]."',
                '".$row["prdes_codaio"]."',
                '".$nuevoTotal[$i]."',
                '".$row["prdes_unidad"]."',
                '".$row["prdes_idproductos_co"]."',
                '".$current_user->id."',
                '".$row["prdes_descripcion"]."',
                '".$current_user->iddivision_c."',
                '".$current_user->idregional_c."',
                '".$current_user->idamercado_c."',
                '".$row["prdes_desc"]."',
                '".$row["prdes_descmonto"]."',
                '".$row["prdes_nomproyco"]."',
                '".$row["prdes_tipocotiza"]."',
                '".$row["prdes_idproductocotiazdo"]."'
              )";
            $GLOBALS['db']->query($productoDespacho, true);
            //Creando ID para la relacion
            $id_despro_rel = create_guid();
            //Creado la relacion de DESPACHOS con PRODUCTOS DESPACHOS
            $relacionProDes = "INSERT INTO sco_despachos_sco_productosdespachos_c
              (id, date_modified, deleted, sco_despachos_sco_productosdespachossco_despachos_ida, sco_despachos_sco_productosdespachossco_productosdespachos_idb)
              VALUES
              (
                '".$id_despro_rel."',
                '".$row["date_entered"]."',
                '0',
                '".$id_despacho."',
                '".$id_ProductoDespacho."'
              )";
            $GLOBALS['db']->query($relacionProDes, true);
            //Creando la relacion de PRODUCTOS COMPRAS con DESPACHOS
            $id_pc_pd = create_guid();
            $r_pc_des = "INSERT INTO sco_productoscompras_sco_despachos_c
              (id, date_modified, deleted, sco_productoscompras_sco_despachossco_productoscompras_ida, sco_productoscompras_sco_despachossco_despachos_idb, id_productodespacho)
              VALUES
              (
                '".$id_pc_pd."',
                '".$row["date_entered"]."',
                '0',
                '".$row["prdes_idproductos_co"]."',
                '".$id_despacho."',
                '".$id_ProductoDespacho."'
              )";
            $GLOBALS['db']->query($r_pc_des, true);
            //Descontar producto
            $NuevoSaldo = $original[$i] - $nuevoTotal[$i];
            $ProDespachos = "UPDATE sco_productosdespachos SET prdes_cantidad = '".$NuevoSaldo."' WHERE id = '".$row["id"]."'";
            $GLOBALS['db']->query($ProDespachos, true);
          }
      }
    }
    $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
    $link = array($host."index.php?action=ajaxui#ajaxUILoc=index.php%3Fmodule%3DSCO_Despachos%26action%3DDetailView%26record%3D".$id_despacho);
    echo json_encode($link);
  }
  else {
    $error = array('error');
    echo json_encode($error);
  }
}
else {
  $error = array('error');
  echo json_encode($error);
}
 ?>
