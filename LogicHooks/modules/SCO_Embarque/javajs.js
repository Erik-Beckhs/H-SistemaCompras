$("#emb_origen").change(function(){
    if($(this).children(":selected").val() != ''){
    	var origen = $(this).val();
    	//debugger;
		$.ajax({
			type: "get",
			url: "index.php?to_pdf=true&module=SCO_Despachos&action=mod_transporte&id=1",
			data: {origen},
			success: function(data) {
				var modtrans = $.parseJSON(data);
        		$("#emb_modtra").find("option").remove().end();
        		$("#emb_modtra").append("<option value=''>Seleccione MT</option>");
        		$("#emb_diastran").val('');
        		if(modtrans){
	        		modtrans = modtrans.split("|");
	        		for(var i = 0; i < modtrans.length-1; i++){
	        			$("#emb_modtra").append("<option value='"+modtrans[i]+"'>"+modtrans[i]+"</option>");
	        		}
	        	}else{
	        		$("#emb_modtra").find("option").remove().end()
	        	}
    		}
    	});
    }
});

$("#emb_modtra").change(function(){
    if($(this).children(":selected").val() != ''){
    	var modtrans = $(this).val();
    	var origen = $("#emb_origen").val();
    	//debugger;
		$.ajax({
			type: "get",
			url: "index.php?to_pdf=true&module=SCO_Despachos&action=mod_transporte&id=2",
			data: {modtrans,origen},
			success: function(data) {
				//debugger;
				var modtrans = $.parseJSON(data);
				$("#emb_diastran").val(modtrans);
    		}
    	});
    }
});
