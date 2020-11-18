
function color_grilla(id, colorbk)
{
document.getElementById(id).style.color="white";
document.getElementById(id).style.backgroundColor=colorbk;
}

color_grilla('ATXT_OBSAPR', "#32CD32");
document.getElementById("ATXT_OBSAPR").style.backgroundColor="green";
$("ATXT_OBSAPR").css("color", "green");
     getField("ATXT_OBSAPR").style.backgroundColor = "#32CD32";
*/
/*

function pintavalor () {
  
	var costo1 = $("#GRD_PRDCOT").getSummary('TXT_OP1P');
	var costo2 = $("#GRD_PRDCOT").getSummary('TXT_OP2P');
	var costo3 = $("#GRD_PRDCOT").getSummary('TXT_OP3P');

	var grd = $("#GRD_PRDCOT").getNumberRows();
  

	
		if (costo1 > costo2 && costo1 > costo3)
          
        {  
          for (var i=1; i<=grd; i++)
   			 {
               $('#GRD_PRDCOT').getControl(i,6).css("background-color","green");
               $('#GRD_PRDCOT').getControl(i,6).css("color","white");
  			 }
          
//          document.getElementById(costo1).style.backgroundColor="green";
        }
  
  			
  		
  		if  (costo2 > costo1 && costo2 > costo3)
          
        {  
          
          for (var i=1; i<=grd; i++)
            {
                $('#GRD_PRDCOT').getControl(i,8).css("background-color","green");
               $('#GRD_PRDCOT').getControl(i,8).css("color","white");
            }
         
        }
        
        
  		
  		if  (costo3 > costo1 && costo3 > costo2)
          
        { 
          
          for (var i=1; i<=grd; i++)
            {
                $('#GRD_PRDCOT').getControl(i,10).css("background-color","green");
                $('#GRD_PRDCOT').getControl(i,10).css("color","white");
            }
          
        }
  		
}

