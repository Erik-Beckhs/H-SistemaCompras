<?php 
//require_once("../../../JavaBridge/java/Java.inc");
require_once("http://compras.hansa.com.bo:8080/JavaBridge/java/Java.inc");
?>
<?php
	$driver = java ("java.lang.Class");
  $driver->forName("com.mysql.jdbc.Driver");

  $manager = java ("java.sql.DriverManager");
  $conn = $manager->getConnection("jdbc:mysql://192.168.1.71/suitecrm", "suitecrmuser", "suitecrmuser");
  
  $id = $_GET['id'] ? $_GET['id'] : "";
  $jcm = new JavaClass("net.sf.jasperreports.engine.JasperCompileManager");
  $report = $jcm->compileReport("/var/www/report/ocV1.2.0.jrxml");
  
  $parameters = new Java("java.util.HashMap");
  $parameters->put('oc', $id);

  $jfm = new JavaClass("net.sf.jasperreports.engine.JasperFillManager");
  $print = $jfm->fillReport($report, $parameters, $conn);
  
  header('Content-type: application/pdf');
  #header('Content-disposition: attachment; filename="oc_' . round(microtime(true) * 1000) . '.pdf"');
  #header('Pragma: no-cache');
  header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
  #header('Expires: 0');
  
  $out = new java("java.io.ByteArrayOutputStream");
  $exporter = new java("net.sf.jasperreports.engine.export.JRPdfExporter");
  $exParm = java("net.sf.jasperreports.engine.JRExporterParameter");
  $exXlsParm = java("net.sf.jasperreports.engine.export.JRPdfExporterParameter"); 
  $exporter->setParameter($exParm->JASPER_PRINT, $print); 
  $exporter->setParameter($exParm->OUTPUT_STREAM, $out); 
  #$exporter->setParameter($exXlsParm->IS_ONE_PAGE_PER_SHEET, true); 
  #$exporter->setParameter($exXlsParm->IS_DETECT_CELL_TYPE, true); 
  $exporter->exportReport();
  $out->close();
  
  echo java_cast($out->toByteArray(), "S");
?>