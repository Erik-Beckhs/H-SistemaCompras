<?php
/**
*Esta clase es una modificacion a la vista de edicion del modulo de SCO_ORDENCOMPRA.
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license /var/www/html/custom/modules/SCO_OrdenCompra
*/
class Clviewoc
{
  function Fnviewoc()
  {
    switch ($GLOBALS['app']->controller->action)
    {
      case "EditView":
      global $current_user;
      $division = $current_user->iddivision_c;
    	//QUery, ordenando los nombres del modulo de CNF_EVENTOS
    	$query ="SELECT * FROM suitecrm.sco_cnf_comentarios where cnf_division = '$division' ";
    	$obj = $GLOBALS['db']->query($query, true);
    	#echo "<script>alert('".$this->bean->emb_orig.$this->bean->emb_transp."')</script>";
      $Comentarios = '';
    	while($row = $GLOBALS['db']->fetchByAssoc($obj))
    	{
        $Comentarios = $row["cnf_comentarios"];
    	}
      $com = base64_encode($Comentarios);
      echo "<script>
        var comentario = '".$com."';

      if($('#orc_observaciones').val() == '')
      {
          $('#orc_observaciones').val(b64_to_utf8(comentario));
      }
      function b64_to_utf8( str ) {
        return decodeURIComponent(escape(window.atob( str )));
      }
      </script>";
        $js = "
            <script>
            $('#orc_verco').on('click',function()
            {
              if($('#orc_verco')[0].checked) {
                //Desbloqueamos el campo de busqueda para clonar ordenes de compra
                $('#orc_occ').prop('disabled', false);
                $('#name').prop('disabled', false);
                $('#orc_solicitado').prop('disabled', false);
                //Bloqueamos los demas campos
                $('#orc_tipo').prop('disabled', true);
                $('#orc_fechaord').prop('disabled', true);
                $('#orc_tipoo').prop('disabled', true);
                $('#orc_observaciones').prop('disabled', true);
                $('#sco_proveedor_sco_ordencompra_name').prop('disabled', true);
                $('#sco_ordencompra_contacts_name').prop('disabled', true);
                $('#orc_tcinco').prop('disabled', true);
                $('#orc_tcmoneda').prop('disabled', true);
                $('#orc_tclugent').prop('disabled', true);
                $('#orc_tcmulta').prop('disabled', true);
                $('#orc_tiempo').prop('disabled', true);
                $('#orc_tccertor').prop('disabled', true);
                $('#orc_tcforpag').prop('disabled', true);
                $('#orc_tcgarantia').prop('disabled', true);
              }
              else{
                //Bloqueamos el campo de busqueda para clonar ordenes de compra
                $('#orc_occ').prop('disabled', true);
                //Desbloqueamos los demas campos
                $('#name').prop('disabled', false);
                $('#orc_tipo').prop('disabled', false);
                $('#orc_fechaord').prop('disabled', false);
                $('#orc_tipoo').prop('disabled', false);
                $('#orc_observaciones').prop('disabled', false);
                $('#orc_solicitado').prop('disabled', false);
                $('#sco_proveedor_sco_ordencompra_name').prop('disabled', false);
                $('#sco_ordencompra_contacts_name').prop('disabled', false);
                $('#orc_tcinco').prop('disabled', false);
                $('#orc_tcmoneda').prop('disabled', false);
                $('#orc_tclugent').prop('disabled', false);
                $('#orc_tcmulta').prop('disabled', false);
                $('#orc_tiempo').prop('disabled', false);
                $('#orc_tccertor').prop('disabled', false);
                $('#orc_tcforpag').prop('disabled', false);
                $('#orc_tcgarantia').prop('disabled', false);
              }
            })
            function ocudaenv()
            {
              if($('#orc_decop')[0].checked)
              {
                $('#orc_denomemp_label').hide();
                $('#orc_denomemp').hide();
                $('#orc_defax_label').hide();
                $('#orc_defax').hide();
                $('#orc_depobox_label').hide();
                $('#orc_depobox').hide();
                $('#orc_depais_label').hide();
                $('#orc_depais').hide();
                $('#orc_detelefono_label').hide();
                $('#orc_detelefono').hide();
                $('#orc_dedireccion_label').hide();
                $('#orc_dedireccion').hide();
              } else {
                $('#orc_denomemp_label').fadeIn();
                $('#orc_denomemp').fadeIn();
                $('#orc_defax_label').fadeIn();
                $('#orc_defax').fadeIn();
                $('#orc_depobox_label').fadeIn();
                $('#orc_depobox').fadeIn();
                $('#orc_depais_label').fadeIn();
                $('#orc_depais').fadeIn();
                $('#orc_detelefono_label').fadeIn();
                $('#orc_detelefono').fadeIn();
                $('#orc_dedireccion_label').fadeIn();
                $('#orc_dedireccion').fadeIn();
              }
            }
            //$('#orc_productos_label').hide();
            //$('#orc_productos').hide();
            $('#orc_denomemp_label').hide();
            $('#orc_denomemp').hide();
            $('#orc_defax_label').hide();
            $('#orc_defax').hide();
            $('#orc_depobox_label').hide();
            $('#orc_depobox').hide();
            $('#orc_depais_label').hide();
            $('#orc_depais').hide();
            $('#orc_detelefono_label').hide();
            $('#orc_detelefono').hide();
            $('#orc_dedireccion_label').hide();
            $('#orc_dedireccion').hide();
            $('#orc_decop').change(ocudaenv);

            $('#orc_occ').prop('disabled', true);
            $('#btn_orc_occ').prop('disabled', true);
            $('#detailpanel_1 #orc_decop').change(function () {
              if($(this).is(':checked')){
                //$('#orc_occ').prop('disabled', false);
                //$('#btn_orc_occ').prop('disabled', false);
                $('#orc_observaciones').prop('disabled', true);
                //$('#orc_fechaord').prop('disabled', true);
                //$('#orc_tipo').prop('disabled', true);
                //$('#orc_tipoo').prop('disabled', true);
                //$('#orc_solicitado').prop('disabled', true);
                //$('#orc_solicitado').val('');
                $('#user_id1_c').val('');
                //$('#btn_orc_solicitado').prop('disabled', true);
                //$('#sco_proveedor_sco_ordencompra_name').prop('disabled', true);
                $('#sco_proveedor_sco_ordencompra_name').val('');
                $('#sco_proveedor_sco_ordencomprasco_proveedor_ida').val('');
                //$('#btn_sco_proveedor_sco_ordencompra_name').prop('disabled', true);
                //$('#sco_ordencompra_contacts_name').prop('disabled', true);
                $('#sco_ordencompra_contacts_name').val('');
                $('#sco_ordencompra_contactscontacts_ida').val('');
                //$('#btn_sco_ordencompra_contacts_name').prop('disabled', true);
                //$('#orc_decop').prop('disabled', true);
                //$('#orc_tcinco').prop('disabled', true);
                //$('#orc_tclugent').prop('disabled', true);
                //$('#orc_tcforpag').prop('disabled', true);
                //$('#orc_tcgarantia').prop('disabled', true);
                //$('#orc_tcmoneda').prop('disabled', true);
                //$('#orc_tcmulta').prop('disabled', true);
                //$('#orc_tccertor').prop('disabled', true);
              }else{
                //$('#orc_occ').prop('disabled', true);
                //$('#btn_orc_occ').prop('disabled', true);
                $('#orc_observaciones').prop('disabled', false);
                $('#orc_fechaord').prop('disabled', false);
                $('#orc_tipo').prop('disabled', false);
                $('#orc_tipoo').prop('disabled', false);
                //$('#orc_solicitado').prop('disabled', false);
                //$('#btn_orc_solicitado').prop('disabled', false);
                $('#sco_proveedor_sco_ordencompra_name').prop('disabled', false);
                $('#btn_sco_proveedor_sco_ordencompra_name').prop('disabled', false);
                $('#sco_ordencompra_contacts_name').prop('disabled', false);
                $('#btn_sco_ordencompra_contacts_name').prop('disabled', false);
                //$('#orc_decop').prop('disabled', false);
                //$('#orc_tcinco').prop('disabled', false);
                //$('#orc_tclugent').prop('disabled', false);
                //$('#orc_tcforpag').prop('disabled', false);
                //$('#orc_tcgarantia').prop('disabled', false);
                //$('#orc_tcmoneda').prop('disabled', false);
                //$('#orc_tcmulta').prop('disabled', false);
                //$('#orc_tccertor').prop('disabled', false);
              }
            });
            </script>";
        echo "<style> :disabled{ backgorund:rgb(235, 235, 228); color:#aaa;} input[type='text']:disabled { background: rgb(235, 235, 228); color:#aaa;} select:disabled{ background: rgb(235, 235, 228); color:#aaa;} </style>";
        echo $js;

      break;
    }
  }
}
?>
