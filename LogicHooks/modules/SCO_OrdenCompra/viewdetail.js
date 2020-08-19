var d = new Date();
var n = d.getTime();

function showreport(id){
	var url1 = "/modules/reportes/ordencompra.php?id="+id+"&ejec="+n;
	window.open(url1,"","width=1220,height=650");
}
function imprimir(id,nombre,orc_nomcorto){
	var url2 = "/modules/reportes/descargaoc.php?id="+id+"&name="+nombre+"&nameprov="+orc_nomcorto+"&ejec="+n;
	window.open(url2,"","");
}
function showReporteGerencialDiv03(id){
	var url1 = "/modules/reportes/reporteGerencialDiv03.php?id="+id+"&ejec="+n;
	window.open(url1,"","width=1220,height=650");
}
function descargaReporteGerencialDiv03(id,nombre,orc_nomcorto){
	var url2 = "/modules/reportes/descargaReporteGerencialDiv03.php?id="+id+"&name="+nombre+"&nameprov="+orc_nomcorto+"&ejec="+n;
	window.open(url2,"","");
}