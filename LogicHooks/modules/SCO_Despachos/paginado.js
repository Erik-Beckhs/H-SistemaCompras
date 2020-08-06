var ProductosNuevos = new Array();
function pinta(num,inicio){//funciï¿½n creada por Jorge Zazo de la Encarnaciï¿½n http://www.forosdelweb.com/1201706-post4.html
	//Modificaciones Ariel Oliva codigosutiles.com
	var pagNow = inicio;
	var limSup;
	var numPaginasFSt = "";
	var DatoI;
	var DatoF;
	var pagAnt;
	var pagSig;
	var rutaIma=""; //Ruta base de las imagenes
	var numPaginas = misDatos.length /num; //Detecto el nï¿½mero "entero" de pï¿½ginas
	numPaginas = numPaginas.toString();
	numPaginas = numPaginas.split(".");
	numPaginasF = eval(numPaginas[0]);

	if (misDatos.length % num != 0){ //Si el resultado de la divisiï¿½n anterior no es exacto le aï¿½ado manualmente una pï¿½gina mï¿½s
		numPaginasF ++;
	}


	if((pagNow + 1) != numPaginasF){//Establezco el nï¿½mero de datos a mostrar si la ï¿½ltima pï¿½gina no tiene el mismo nï¿½mero de datos
		limSup = -1;
	} else {
		limSup = (misDatos.length - (numPaginasF * num))-1;
	}

	DatoI = pagNow * num;//Establezco el dato inicial y el dato final de la paginaciï¿½n
	DatoF = DatoI + (num+limSup);


	if (pagNow == 0){//Establezco cual es la pï¿½gina anterior y la siguiente
		pagAnt = 0;
	} else {
		pagAnt = pagNow - 1;
	}
	if (pagNow == (numPaginasF-1)){
		pagSig = pagNow;
	} else {
		pagSig = pagNow + 1;
	}

	for (i=0;i<numPaginasF;i++){//Pinto la cadena con el nï¿½mero de pï¿½ginas y sus correspondientes enlaces
		//numPaginasFSt += "<li><a href='javascript:pinta("+ num +","+ i +");'>"+ (i+1) +"</a><li> ";
		numPaginasFSt += "<li><a href='#' onClick='pinta("+ num +","+ i +");return false'>"+ (i+1) +"</a><li> ";
	}

	if (primera == 0){//Establezco si es la primera vez que se crean los elementos
		var creo = document.createElement("span");
		document.getElementById("contenido").appendChild(creo);
		var cadena = "<table  class='table-bordered'><thead><tr>";
			for (i=0;i<encabeza.length;i++){//crea cabeceras
				cadena = cadena + "<th class='encabezado'>&nbsp;"+encabeza[i]+"&nbsp;</th>";
			}
			cadena = cadena + "</tr></thead><tbody>";
			for (DatoI;DatoI<=DatoF;DatoI++){//Pinto todos los elementos con cada dato...
				if(misDatos[DatoI] != undefined)
				{
					cadena = cadena + "<tr id='"+DatoI+"'>";
					for(j=1;j<=encabeza.length;j++){
						var obtDatos = "misDatos[DatoI].dato"+j;
						if (j == 3) {
							cadena = cadena + "<td nowrap='nowrap'>&nbsp;"+misDatos[DatoI].dato7+ "&nbsp;</td>";
							cadena = cadena + //"<td nowrap='nowrap'>&nbsp;"+eval(obtDatos) + "&nbsp;</td>";
							"<td><div col-lg-12>"+
									"<div class='col-lg-6 col-md-6 col-xs-6'>"+
										"<input class='form-control col-lg-12' id='"+DatoI+"saldo' type='number' name='saldo[]' value='"+eval(obtDatos)+"' style='width:70px;height:25px' readonly>"+
									"</div>"+
									"<div class='col-lg-6 col-md-6 col-xs-6'>"+
										"<input class='form-control col-lg-12' id='"+DatoI+"nuevoTotal' type='number' name='nuevoTotal[]' onkeyup='recalcular("+DatoI+")' min='0' max='"+eval(obtDatos)+"' style='width:70px;height:25px'>"+
									"</div>"+
									"<input id='"+DatoI+"original' type='hidden' name='original[]' value='"+eval(obtDatos)+"'>"+
									"<input id='"+DatoI+"idPro' type='hidden' name='idPro[]' value='"+misDatos[DatoI].dato4+"'>"+
							"<div></td>";
						}
						if (j == 4) {
							cadena = cadena + "<td nowrap='nowrap'><center><button type='button' class='btn btn-success btn-xs' id='"+DatoI+"btn' onClick='moverItem("+DatoI+")'>&raquo;</button></center></td>";
						}
						if (j == 2) {
							cadena = cadena + "<td nowrap='nowrap' style ='width: 250px'>&nbsp;"+eval(obtDatos) + "&nbsp;</td>";
						}
						if(j!= 3 && j != 4 && j != 5 && j != 6 && j != 2){
							cadena = cadena + "<td nowrap='nowrap'>&nbsp;"+eval(obtDatos) + "&nbsp;</td>";
						}
					}
					cadena = cadena + "</tr>"
				}
			}
			cadena = cadena + "</tbody></table>";
			contenido.innerHTML = cadena;
		var pagina = document.createElement("span");//... y la paginaciï¿½n.
		document.getElementById("paginacion").appendChild( pagina);
		paginacion.innerHTML += "<center><ul class='pagination'>"+
				"<li><a href='#'  onClick='javascript:pinta("+ num +",0);return false'>&larr; Primero</a></li>"+
				"<li><a href='#' onClick='javascript:pinta("+ num +","+ pagAnt +");return false'>&laquo;</a></li>"+
			 numPaginasFSt+
			 "<li><a href='#' onClick='javascript:pinta("+ num +","+ pagSig +");return false'>&raquo;</a></li>"+
			 "<li><a href='#' onClick='javascript:pinta("+ num +","+(numPaginasF-1)+");return false'>Ultimo &rarr;</a></li></ul></center>";
		primera = 1;
	} else {
		borra();//Borro las capas ya pintadas en la primera vez
		var creo = document.createElement("span");
		document.getElementById("contenido").appendChild(creo);
		var cadena = "<table class='table-bordered'><thead><tr>";
			for (i=0;i<encabeza.length;i++){//crea cabeceras
				cadena = cadena + "<th class='encabezado'>&nbsp;"+encabeza[i]+"&nbsp;</th>";
			}
			cadena = cadena + "</tr></thead><tbody>";
			for (DatoI;DatoI<=DatoF;DatoI++){//Pinto todos los elementos con cada dato...
				if(misDatos[DatoI] != undefined)
				{
					cadena = cadena + "<tr id='"+DatoI+"'>";
					for(j=1;j<=encabeza.length;j++){
						var obtDatos = "misDatos[DatoI].dato"+j;
						if (j == 3) {
							cadena = cadena + "<td nowrap='nowrap'>&nbsp;"+misDatos[DatoI].dato7+ "&nbsp;</td>";
							cadena = cadena + //"<td nowrap='nowrap'>&nbsp;"+eval(obtDatos) + "&nbsp;</td>";
							"<td><div col-lg-12>"+
									"<div class='col-lg-6 col-md-6 col-xs-6'><input class='form-control col-lg-12' id='"+DatoI+"saldo' type='number' name='saldo[]' value='"+eval(obtDatos)+"' style='width:70px;height:25px' readonly></div>"+
									"<div class='col-lg-6 col-md-6 col-xs-6'><input class='form-control col-lg-12' id='"+DatoI+"nuevoTotal' type='number' name='nuevoTotal[]' onkeyup='recalcular("+DatoI+")' min='0' max='"+eval(obtDatos)+"' style='width:70px;height:25px'></div>"+
									"<input id='"+DatoI+"original' type='hidden' name='original[]' value='"+eval(obtDatos)+"'>"+
									"<input id='"+DatoI+"idPro' type='hidden' name='idPro[]' value='"+misDatos[DatoI].dato4+"'>"+
							"<div></td>";
						}
						if (j == 4) {
							cadena = cadena + "<td nowrap='nowrap'><center><button type='button' class='btn btn-defaul btn-xs' id='"+DatoI+"btn' onClick='moverItem("+DatoI+")'>&raquo;</button></center></td>";
						}
						if (j == 2) {
							cadena = cadena + "<td nowrap='nowrap' style ='width: 250px'>&nbsp;"+eval(obtDatos) + "&nbsp;</td>";
						}
						if(j!= 3 && j != 4 && j != 5 && j != 6 && j != 2){
							cadena = cadena + "<td nowrap='nowrap'>&nbsp;"+eval(obtDatos) + "&nbsp;</td>";
						}
						//cadena = cadena + "<td nowrap='nowrap' class='conBordes'>&nbsp;"+eval(obtDatos) + "&nbsp;</td>";
					}
					cadena = cadena + "</tr>";
				}
			}
			cadena = cadena + "</tbody></table>";
			contenido.innerHTML = cadena;
		var pagina = document.createElement("span");//... y la paginaciï¿½n.
		document.getElementById("paginacion").appendChild( pagina);
		paginacion.innerHTML += "<center><ul class='pagination'>"+
				"<li><a href='#' onClick='javascript:pinta("+ num +",0);return false'>&larr; Primero</a></li>"+
				"<li><a href='#' onClick='javascript:pinta("+ num +","+ pagAnt +");return false'>&laquo;</a></li>"+
			 numPaginasFSt+
			 "<li><a href='#' onClick='javascript:pinta("+ num +","+ pagSig +");return false'>&raquo;</a></li>"+
			 "<li><a href='#' onClick='javascript:pinta("+ num +","+(numPaginasF-1)+");return false'>Ultimo &rarr;</a></li></ul></center>";
	}
}

function borra(){
	contenido.innerHTML = "";
	paginacion.innerHTML = "";
}
function recalcular(index) {
  var original = $("#"+index+"original").val();
  var nuevo = $("#"+index+"nuevoTotal").val();
  if ((nuevo*1) > (original*1)) {
    $("#"+index+"nuevoTotal").val(0);
    $("#"+index+"saldo").val(original);
  }
  if ((nuevo*1) <= (original*1)) {
    $("#"+index+"saldo").val((original*1)-(nuevo*1)).css("color","#f5370d");
    $("#"+index+"nuevoTotal").css("color","#2dc44e");
  }
}
function moverItem(index)
{
	var nuevo = $("#"+index+"nuevoTotal").val();
	var original = $("#"+index+"original").val();
	var idPro = $("#"+index+"idPro").val();
	var saldo = (original * 1) - (nuevo * 1);
	var html = "";
	if(ProductosNuevos[index] == undefined)
	{
		if (nuevo == original || nuevo == "" || nuevo == 0) {
			if (nuevo == "" || nuevo == 0) {
				nuevo = original;
			}
			html += "<tr id='n"+index+"'>"+
								"<td>"+misDatos[index].dato1+"</td>"+
								"<td style = 'width: 250px'>"+misDatos[index].dato2+"</td>"+
								"<td><div col-lg-12>"+
										"<div class='col-lg-12'>"+
											"<input class='form-control' type='hidden' name='saldo[]' value='"+saldo+"' size='10' readonly>"+
											"<input class='form-control' type='number' name='nuevoTotal[]' value='"+nuevo+"' min='0' max='"+misDatos[index].dato3+"' style='color:#2dc44e;width:70px;height:25px' readonly>"+
										"</div>"+
										"<input type='hidden' name='original[]' value='"+original+"'>"+
										"<input type='hidden' name='idPro[]' value='"+idPro+"'>"+
										"<input type='hidden' name='idOc[]' value='"+$("#idOrdenCompra").val()+"'>"+
										"<input type='hidden' name='idDespacho[]' value='"+misDatos[index].dato6+"'>"+
								"<div></td>"+
								"<td><button class='btn btn-xs' onClick ='retornarItem("+index+")'>X</button></td>"+
							"</tr>";
			$("#"+index).remove();
			$("#nuevoDespacho").append(html);
			$("#"+index+"btn").remove();
		  $("#"+index+"nuevoTotal").attr("readonly","readonly");
			ProductosNuevos[index] = new datos(misDatos[index].dato1 ,misDatos[index].dato2 ,misDatos[index].dato3 ,misDatos[index].dato4, misDatos[index].dato5 ,misDatos[index].dato6 ,misDatos[index].dato7 );
			delete misDatos[index];
		}
		else {
			html += "<tr id='n"+index+"'>"+
								"<td>"+misDatos[index].dato1+"</td>"+
								"<td style = 'width: 250px'>"+misDatos[index].dato2+"</td>"+
								"<td><div col-lg-12>"+
										"<div class='col-lg-12'>"+
											"<input class='form-control' type='hidden' name='saldo[]' value='"+saldo+"' size='10' readonly>"+
											"<input class='form-control' type='number' name='nuevoTotal[]' value='"+nuevo+"' min='0' max='"+misDatos[index].dato3+"' style='color:#2dc44e;width:70px;height:25px' readonly>"+
										"</div>"+
										"<input type='hidden' name='original[]' value='"+original+"'>"+
										"<input type='hidden' name='idPro[]' value='"+idPro+"'>"+
										"<input type='hidden' name='idOc[]' value='"+$("#idOrdenCompra").val()+"'>"+
										"<input type='hidden' name='idDespacho[]' value='"+misDatos[index].dato6+"'>"+
								"<div></td>"+
								"<td><button class='btn btn-xs' onClick='retornarItem("+index+")' >X</button></td>"+
							"</tr>";
			$("#nuevoDespacho").append(html);
			$("#"+index+"btn").remove();
			$("#"+index+"nuevoTotal").attr("readonly","readonly");
			ProductosNuevos[index] = new datos(misDatos[index].dato1 ,misDatos[index].dato2 ,nuevo ,misDatos[index].dato4, misDatos[index].dato5 ,misDatos[index].dato6 ,misDatos[index].dato7 );
			misDatos[index].dato3 = (misDatos[index].dato3 * 1) - (nuevo * 1);
			//delete misDatos[index];
		}
	}
	else {
		$("#n"+index).remove();
		if (nuevo == "" || nuevo == 0) {
			nuevo = original;
		}
		NuevaCantidad = (ProductosNuevos[index].dato3 * 1) + (nuevo * 1);
		saldo = (saldo * 1) - (ProductosNuevos[index].dato3 * 1);
		ProductosNuevos[index].dato3 = NuevaCantidad;
		html += "<tr id='n"+index+"'>"+
							"<td>"+misDatos[index].dato1+"</td>"+
							"<td style = 'width: 250px'>"+misDatos[index].dato2+"</td>"+
							"<td><div col-lg-12>"+
									"<div class='col-lg-12'>"+
										"<input class='form-control' type='hidden' name='saldo[]' value='"+saldo+"' size='10' readonly>"+
										"<input class='form-control' type='number' name='nuevoTotal[]' value='"+NuevaCantidad+"' min='0' max='"+misDatos[index].dato3+"' style='color:#2dc44e;width:70px;height:25px' readonly>"+
									"</div>"+
									"<input type='hidden' name='original[]' value='"+original+"'>"+
									"<input type='hidden' name='idPro[]' value='"+idPro+"'>"+
									"<input type='hidden' name='idOc[]' value='"+$("#idOrdenCompra").val()+"'>"+
									"<input type='hidden' name='idDespacho[]' value='"+misDatos[index].dato6+"'>"+
							"<div></td>"+
							"<td><button class='btn btn-xs' onClick='retornarItem("+index+")' >X</button></td>"+
						"</tr>";
		$("#nuevoDespacho").append(html);
		if ((nuevo*1) == (original*1)) {
			$("#"+index).remove();
			delete misDatos[index];
		}
		else {
			misDatos[index].dato3 = (misDatos[index].dato3 * 1) - (nuevo*1);
			$("#"+index+"btn").remove();
			$("#"+index+"nuevoTotal").attr("readonly","readonly");
		}
	}
}
function retornarItem(index) {
	if(misDatos[index] == undefined)
		{
			$("#n"+index).remove();
			misDatos[index] = new datos(ProductosNuevos[index].dato1  ,ProductosNuevos[index].dato2  ,ProductosNuevos[index].dato3 ,ProductosNuevos[index].dato4,ProductosNuevos[index].dato5 ,ProductosNuevos[index].dato6 ,ProductosNuevos[index].dato7  );
			delete ProductosNuevos[index];
			pinta(8,0);
		}
		else {
			$("#n"+index).remove();
			nuevaC = ProductosNuevos[index].dato3;
			anteriorC = misDatos[index].dato3;
			var nuevoVal = (nuevaC * 1) + (anteriorC * 1);
			misDatos[index].dato3 = nuevoVal;
			delete ProductosNuevos[index];
			pinta(8,0);
		}
}
