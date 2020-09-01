<?php //require_once("../../../JavaBridge/java/Java.inc");
#require_once("http://localhost:8080/JavaBridgeTemplate721/java/Java.inc"); #DEV
require_once("http://compras-qas.hansa.com.bo:8080/JavaBridge/java/Java.inc"); #QAS
?>
<?php
  $driver = java ("java.lang.Class");
  $driver->forName("com.mysql.jdbc.Driver");

  $manager = java ("java.sql.DriverManager");
  $conn = $manager->getConnection("jdbc:mysql://192.168.1.81/suitecrm", "suitecrmuser", "suitecrmuser");
  
  $id = $_GET['id'] ? $_GET['id'] : "";
  $name = "Reporte";
  $nameprov = "Gerencial";
  $jcm = new JavaClass("net.sf.jasperreports.engine.JasperCompileManager");
  $report = $jcm->compileReport("/var/www/report/repgerencialV1.jrxml");
  
  $parameters = new Java("java.util.HashMap");
  $parameters->put('oc', $id);

  $jfm = new JavaClass("net.sf.jasperreports.engine.JasperFillManager");
  $print = $jfm->fillReport($report, $parameters, $conn);
  
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Transfer-Encoding: binary');
  header('Content-disposition: attachment; filename="Reporte Base Instalada_' . round(microtime(true) * 1000) . '.xlsx"');
  header('Pragma: no-cache');
  header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
  header('Expires: 0');

  set_time_limit(120);
  java_set_file_encoding("UTF-8");

  $out = new java("java.io.ByteArrayOutputStream");
  $exporter = new java("net.sf.jasperreports.engine.export.ooxml.JRXlsxExporter");
  $exParm = java("net.sf.jasperreports.engine.JRExporterParameter");
  $exXlsParm = java("net.sf.jasperreports.engine.export.JRXlsExporterParameter");
  $exporter->setParameter($exParm->JASPER_PRINT, $print);
  $exporter->setParameter($exParm->OUTPUT_STREAM, $out);
  //$exporter->setParameter($exXlsParm->IS_ONE_PAGE_PER_SHEET, true);
  //$exporter->setParameter($exXlsParm->IS_DETECT_CELL_TYPE, true);
  $exporter->exportReport();
  $out->close();
  echo java_cast($out->toByteArray(), "S");
?>