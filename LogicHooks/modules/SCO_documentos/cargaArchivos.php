<?php
/**
*Esta clase realiza operaciones matemáticas.
*
*@author Reynaldo Kantuta <rkantuta@hansa.com.bo>
*@copyright 2018
*@license ruta: /var/www/html/modules/SCO_Documentos
*/
if(!defined('sugarEntry'))define('sugarEntry', true);
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');

	$archivos = $_FILES['filename_file'];
	$idoc = $_GET['idoc'];
	//$tipo = 'corización';
  $c = 0;
    $id_Documento = create_guid();

    $file = $_FILES["filename_file"];
    $carpetaAdjunta="upload/";
    // El nombre y nombre temporal del archivo que vamos para adjuntar
    $nombreArchivo=$id_Documento;
    $nombreTemporal=$file["tmp_name"];
    $file_mime_type=$file["type"];
    $filename = $file["name"];
    //$nombreArchivo
    $rutaArchivo=$carpetaAdjunta.$nombreArchivo;
    move_uploaded_file($nombreTemporal,$rutaArchivo);
    //Generando el ID de DOCUMENTO
    $insertarDocumetos = "INSERT INTO sco_documentos
      (id,name,date_entered,date_modified,doc_fecha,doc_tipo,file_mime_type,filename)
      VALUES
      (
        '".$id_Documento."',
        '".$filename."',
        '".date("Y-m-d")."',
        '".date("Y-m-d")."',
        '".date("Y-m-d")."',
        '',
        '".$file_mime_type."',
        '".$filename."'
      );";
    $GLOBALS['db']->query($insertarDocumetos, true);
		//Registramos la relación de los archivos con la orden de compra
		$id_relacion = create_guid();
		$insertarRel = "INSERT INTO sco_ordencompra_sco_documentos_c
      (id,date_modified,deleted,sco_ordencompra_sco_documentossco_ordencompra_ida,sco_ordencompra_sco_documentossco_documentos_idb)
      VALUES
      (
        '".$id_relacion."',
        '".date("Y-m-d")."',
				'0',
        '".$idoc."',
        '".$id_Documento."'
      );";
    $GLOBALS['db']->query($insertarRel, true);
    $resp = array('registrado');
    echo json_encode($resp);

?>
