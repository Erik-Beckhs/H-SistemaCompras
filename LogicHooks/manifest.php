<?php
/*************************************
Project: Logic Hooks Manifest
Original Dev: Juan Jose Silva, 07 2017
Modificado Dev: Limberg Alon Espejo, 05 2018

Desc: Manifest file for installing logic hook

The contents of this file are governed by the GNU General Public License (GPL).
A copy of said license is available here: http://www.gnu.org/copyleft/gpl.html
This code is provided AS IS and WITHOUT WARRANTY OF ANY KIND.
necesita actualizacion...
*************************************/

global $sugar_config;

$upload_dir = $sugar_config['upload_dir'];
$manifest = array(
 'acceptable_sugar_versions' => array(
  'regex_matches' => array(
   0 => '6\.*'
  ),
 ),
 'acceptable_sugar_flavors' => array(
  0 => 'CE',
  1 => 'PRO',
  2 => 'ENT',
 ),
 'name'    => 'Logic Hook Compras',
 'description'  => 'Logic Hook Compras installation package.',
 'is_uninstallable' => true,
 'author'   => 'lalcon',
 'published_date' => 'May 18 , 2018',
 'version'   => '2.0.0',
 'type'    => 'module',
 );

$installdefs = array(
 'id'  => 'APR_LogicHook',
 'mkdir' => array(
     //SCO_Aprobadores
     array('path' => 'custom/modules/SCO_Aprobadores'),
     //SCO_Contactos
     array('path' => 'custom/modules/SCO_Contactos'),
     //SCO_Despachos
     array('path' => 'custom/modules/SCO_Despachos'),
     //SCO_Distribuidor
     array('path' => 'custom/modules/SCO_Distribuidor'),
     //SCO_documentos
     array('path' => 'custom/modules/SCO_documentos'),
     //SCO_Embarrque
     array('path' => 'custom/modules/SCO_Embarque'),
     //SCO_Eventos
     array('path' => 'custom/modules/SCO_Eventos'),
     //SCO_OrdenCompra
     array('path' => 'custom/modules/SCO_OrdenCompra'),
     //SCO_PlandePagos
     array('path' => 'custom/modules/SCO_PlandePagos'),
     //SCO_Productos
     array('path' => 'custom/modules/SCO_Productos'),
     //SCO_ProductosCompras
     array('path' => 'custom/modules/SCO_ProductosCompras'),
     //SCO_ProductosDespachos
     array('path' => 'custom/modules/SCO_ProductosDespachos'),
     //SCO_Proveedor
     array('path' => 'custom/modules/SCO_Proveedor'),
    ),
 'copy' => array(
      //LOGIC HOOKS
      //CONTROLLERS

      //SCO_Aprobadores
      array(
        'from' => '<basepath>/custom/modules/SCO_Aprobadores/correlativo.php',
        'to'   => 'custom/modules/SCO_Aprobadores/correlativo.php',
      ),
      array(
        'from' => '<basepath>/custom/modules/SCO_Aprobadores/correlativoDeleted.php',
        'to'   => 'custom/modules/SCO_Aprobadores/correlativoDeleted.php',
      ),
      array(
        'from' => '<basepath>/custom/modules/SCO_Aprobadores/datosAprobadores.php',
        'to'   => 'custom/modules/SCO_Aprobadores/datosAprobadores.php',
      ),
      //SCO_Contactos
      array(
        'from' => '<basepath>/custom/modules/SCO_Contactos/datosContactos.php',
        'to'   => 'custom/modules/SCO_Contactos/datosContactos.php',
      ),
      //SCO_Despachos
      array(
        'from' => '<basepath>/custom/modules/SCO_Despachos/proddes.php',
        'to'   => 'custom/modules/SCO_Despachos/proddes.php',
      ),
      array(
        'from' => '<basepath>/custom/modules/SCO_Despachos/prodelimina.php',
        'to'   => 'custom/modules/SCO_Despachos/prodelimina.php',
      ),
      //SCO_Distribuidor
      array(
        'from' => '<basepath>/custom/modules/SCO_Distribuidor/nomdist.php',
        'to'   => 'custom/modules/SCO_Distribuidor/nomdist.php',
      ),
      //SCO_documentos
      array(
        'from' => '<basepath>/custom/modules/SCO_documentos/creafol.php',
        'to'   => 'custom/modules/SCO_documentos/creafol.php',
      ),
      array(
        'from' => '<basepath>/custom/modules/SCO_documentos/Documentos.php',
        'to'   => 'custom/modules/SCO_documentos/Documentos.php',
      ),
      //SCO_Embarque
      array(
        'from' => '<basepath>/custom/modules/SCO_Embarque/controller.php',
        'to'   => 'custom/modules/SCO_Embarque/controller.php',
      ),
      array(
        'from' => '<basepath>/custom/modules/SCO_Embarque/despacho.php',
        'to'   => 'custom/modules/SCO_Embarque/despacho.php',
      ),
      array(
        'from' => '<basepath>/custom/modules/SCO_Embarque/despacho_delte_r.php',
        'to'   => 'custom/modules/SCO_Embarque/despacho_delte_r.php',
      ),
      array(
        'from' => '<basepath>/custom/modules/SCO_Embarque/evento.php',
        'to'   => 'custom/modules/SCO_Embarque/evento.php',
      ),
      array(
        'from' => '<basepath>/custom/modules/SCO_Embarque/NotificaEmbarque.php',
        'to'   => 'custom/modules/SCO_Embarque/NotificaEmbarque.php',
      ),
      array(
        'from' => '<basepath>/custom/modules/SCO_Embarque/NotificaEmbarqueDelet.php',
        'to'   => 'custom/modules/SCO_Embarque/NotificaEmbarqueDelet.php',
      ),
      array(
        'from' => '<basepath>/custom/modules/SCO_Embarque/documentosdespachos_delte_r.php',
        'to'   => 'custom/modules/SCO_Embarque/documentosdespachos_delte_r.php',
      ),
      array(
        'from' => '<basepath>/custom/modules/SCO_Embarque/productosdespachos.php',
        'to'   => 'custom/modules/SCO_Embarque/productosdespachos.php',
      ),
      array(
        'from' => '<basepath>/custom/modules/SCO_Embarque/documentosdespachos.php',
        'to'   => 'custom/modules/SCO_Embarque/documentosdespachos.php',
      ),
      array(
        'from' => '<basepath>/custom/modules/SCO_Embarque/productosdespachos_delte_r.php',
        'to'   => 'custom/modules/SCO_Embarque/productosdespachos_delte_r.php',
      ),
      //SCO_Eventos
      array(
        'from' => '<basepath>/custom/modules/SCO_Eventos/fechas.php',
        'to'   => 'custom/modules/SCO_Eventos/fechas.php',
      ),
      array(
        'from' => '<basepath>/custom/modules/SCO_Eventos/NotificaEvento.php',
        'to'   => 'custom/modules/SCO_Eventos/NotificaEvento.php',
      ),
      //SCO_OrdenCompra
      array(
        'from' => '<basepath>/custom/modules/SCO_OrdenCompra/clonar.php',
        'to'   => 'custom/modules/SCO_OrdenCompra/clonar.php',
      ),
      array(
        'from' => '<basepath>/custom/modules/SCO_OrdenCompra/contap.php',
        'to'   => 'custom/modules/SCO_OrdenCompra/contap.php',
      ),
      array(
        'from' => '<basepath>/custom/modules/SCO_OrdenCompra/controller.php',
        'to'   => 'custom/modules/SCO_OrdenCompra/controller.php',
      ),
      array(
        'from' => '<basepath>/custom/modules/SCO_OrdenCompra/datosap.php',
        'to'   => 'custom/modules/SCO_OrdenCompra/datosap.php',
      ),
      array(
        'from' => '<basepath>/custom/modules/SCO_OrdenCompra/datosco.php',
        'to'   => 'custom/modules/SCO_OrdenCompra/datosco.php',
      ),
      array(
        'from' => '<basepath>/custom/modules/SCO_OrdenCompra/datoso.php',
        'to'   => 'custom/modules/SCO_OrdenCompra/datoso.php',
      ),
      array(
        'from' => '<basepath>/custom/modules/SCO_OrdenCompra/deselimina.php',
        'to'   => 'custom/modules/SCO_OrdenCompra/deselimina.php',
      ),
      array(
        'from' => '<basepath>/custom/modules/SCO_OrdenCompra/jquery.jexcel.css',
        'to'   => 'custom/modules/SCO_OrdenCompra/jquery.jexcel.css',
      ),
      array(
        'from' => '<basepath>/custom/modules/SCO_OrdenCompra/jquery.jexcel.js',
        'to'   => 'custom/modules/SCO_OrdenCompra/jquery.jexcel.js',
      ),
      array(
        'from' => '<basepath>/custom/modules/SCO_OrdenCompra/Notifica.php',
        'to'   => 'custom/modules/SCO_OrdenCompra/Notifica.php',
      ),
      array(
        'from' => '<basepath>/custom/modules/SCO_OrdenCompra/viewoc.php',
        'to'   => 'custom/modules/SCO_OrdenCompra/viewoc.php',
      ),
      //SCO_PlandePagos
      array(
        'from' => '<basepath>/custom/modules/SCO_PlandePagos/PlanPagos.php',
        'to'   => 'custom/modules/SCO_PlandePagos/PlanPagos.php',
      ),
      array(
        'from' => '<basepath>/custom/modules/SCO_PlandePagos/PlanPagos2.php',
        'to'   => 'custom/modules/SCO_PlandePagos/PlanPagos2.php',
      ),
      array(
        'from' => '<basepath>/custom/modules/SCO_PlandePagos/DeletePP.php',
        'to'   => 'custom/modules/SCO_PlandePagos/DeletePP.php',
      ),
      //SCO_Productos
      array(
        'from' => '<basepath>/custom/modules/SCO_Productos/Deproductos.php',
        'to'   => 'custom/modules/SCO_Productos/Deproductos.php',
      ),
      array(
        'from' => '<basepath>/custom/modules/SCO_Productos/Productos.php',
        'to'   => 'custom/modules/SCO_Productos/Productos.php',
      ),
      //SCO_ProductosCompras
      array(
        'from' => '<basepath>/custom/modules/SCO_ProductosCompras/controller.php',
        'to'   => 'custom/modules/SCO_ProductosCompras/controller.php',
      ),
      //SCO_ProductosDespachos
      array(
        'from' => '<basepath>/custom/modules/SCO_ProductosDespachos/productosdespachos.php',
        'to'   => 'custom/modules/SCO_ProductosDespachos/productosdespachos.php',
      ),
      //SCO_Proveedor
      array(
        'from' => '<basepath>/custom/modules/SCO_Proveedor/ProveedorView.php',
        'to'   => 'custom/modules/SCO_Proveedor/ProveedorView.php',
      ),
      array(
        'from' => '<basepath>/custom/modules/SCO_Proveedor/controller.php',
        'to'   => 'custom/modules/SCO_Proveedor/controller.php',
      ),

      //VISTAS

      //SCO_Despachos
      array(
        'from' => '<basepath>/modules/SCO_Despachos/views/view.detail.php',
        'to'   => 'modules/SCO_Despachos/views/view.detail.php',
      ),
      array(
        'from' => '<basepath>/modules/SCO_Despachos/views/view.edit.php',
        'to'   => 'modules/SCO_Despachos/views/view.edit.php',
      ),
      array(
        'from' => '<basepath>/modules/SCO_Despachos/crearNuevoDespachoDividido.php',
        'to'   => 'modules/SCO_Despachos/crearNuevoDespachoDividido.php',
      ),
      array(
        'from' => '<basepath>/modules/SCO_Despachos/despachosIntangibles.php',
        'to'   => 'modules/SCO_Despachos/despachosIntangibles.php',
      ),
      array(
        'from' => '<basepath>/modules/SCO_Despachos/dividirdespacho.php',
        'to'   => 'modules/SCO_Despachos/dividirdespacho.php',
      ),
      array(
        'from' => '<basepath>/modules/SCO_Despachos/estado.php',
        'to'   => 'modules/SCO_Despachos/estado.php',
      ),
      array(
        'from' => '<basepath>/modules/SCO_Despachos/javajs.js',
        'to'   => 'modules/SCO_Despachos/javajs.js',
      ),
      array(
        'from' => '<basepath>/modules/SCO_Despachos/mod_transporte.php',
        'to'   => 'modules/SCO_Despachos/mod_transporte.php',
      ),
      array(
        'from' => '<basepath>/modules/SCO_Despachos/NotificaDespacho.php',
        'to'   => 'modules/SCO_Despachos/NotificaDespacho.php',
      ),
      array(
        'from' => '<basepath>/modules/SCO_Despachos/paginado.js',
        'to'   => 'modules/SCO_Despachos/paginado.js',
      ),
      //SCO_documentos
      array(
        'from' => '<basepath>/modules/SCO_documentos/views/view.edit.php',
        'to'   => 'modules/SCO_documentos/views/view.edit.php',
      ),
      array(
        'from' => '<basepath>/modules/SCO_documentos/all.css',
        'to'   => 'modules/SCO_documentos/all.css',
      ),
      array(
        'from' => '<basepath>/modules/SCO_documentos/cargaArchivos.php',
        'to'   => 'modules/SCO_documentos/cargaArchivos.php',
      ),
      array(
        'from' => '<basepath>/modules/SCO_documentos/es.js',
        'to'   => 'modules/SCO_documentos/es.js',
      ),
      array(
        'from' => '<basepath>/modules/SCO_documentos/fileinput.css',
        'to'   => 'modules/SCO_documentos/fileinput.css',
      ),
      array(
        'from' => '<basepath>/modules/SCO_documentos/fileinput.js',
        'to'   => 'modules/SCO_documentos/fileinput.js',
      ),
      array(
        'from' => '<basepath>/modules/SCO_documentos/jquery.filedrop.js',
        'to'   => 'modules/SCO_documentos/jquery.filedrop.js',
      ),
      array(
        'from' => '<basepath>/modules/SCO_documentos/sortable.js',
        'to'   => 'modules/SCO_documentos/sortable.js',
      ),
      array(
        'from' => '<basepath>/modules/SCO_documentos/theme.css',
        'to'   => 'modules/SCO_documentos/theme.css',
      ),
      array(
        'from' => '<basepath>/modules/SCO_documentos/theme.js',
        'to'   => 'modules/SCO_documentos/theme.js',
      ),
      array(
        'from' => '<basepath>/modules/SCO_documentos/theme.js',
        'to'   => 'modules/SCO_documentos/theme.min.js',
      ),
      //SCO_Embarque
      array(
        'from' => '<basepath>/modules/SCO_Embarque/views/view.detail.php',
        'to'   => 'modules/SCO_Embarque/views/view.detail.php',
      ),
      array(
        'from' => '<basepath>/modules/SCO_Embarque/views/view.edit.php',
        'to'   => 'modules/SCO_Embarque/views/view.edit.php',
      ),
      array(
        'from' => '<basepath>/modules/SCO_Embarque/javajs.js',
        'to'   => 'modules/SCO_Embarque/javajs.js',
      ),
      //SCO_Eventos
      array(
        'from' => '<basepath>/modules/SCO_Eventos/views/view.detail.php',
        'to'   => 'modules/SCO_Eventos/views/view.detail.php',
      ),
      array(
        'from' => '<basepath>/modules/SCO_Eventos/fecha_eventos.php',
        'to'   => 'modules/SCO_Eventos/fecha_eventos.php',
      ),
      //SCO_OrdenCompra
      array(
        'from' => '<basepath>/modules/SCO_OrdenCompra/views/view.detail.php',
        'to'   => 'modules/SCO_OrdenCompra/views/view.detail.php',
      ),
      array(
        'from' => '<basepath>/modules/SCO_OrdenCompra/views/view.edit.php',
        'to'   => 'modules/SCO_OrdenCompra/views/view.edit.php',
      ),
      array(
        'from' => '<basepath>/modules/SCO_OrdenCompra/calculodesc.php',
        'to'   => 'modules/SCO_OrdenCompra/calculodesc.php',
      ),
      array(
        'from' => '<basepath>/modules/SCO_OrdenCompra/verificarEstado.php',
        'to'   => 'modules/SCO_OrdenCompra/verificarEstado.php',
      ),
      array(
        'from' => '<basepath>/modules/SCO_OrdenCompra/ordencompra.php',
        'to'   => 'modules/SCO_OrdenCompra/ordencompra.php',
      ),
      //Aprobadores
      array(
        'from' => '<basepath>/modules/SCO_Aprobadores/views/view.edit.php',
        'to'   => 'modules/SCO_Aprobadores/views/view.edit.php',
      ),
      //Plan de Pagos
      array(
        'from' => '<basepath>/modules/SCO_PlandePagos/views/view.edit.php',
        'to'   => 'modules/SCO_PlandePagos/views/view.edit.php',
      ),
      //SCO_Problema
      array(
        'from' => '<basepath>/modules/SCO_Problema/views/view.edit.php',
        'to'   => 'modules/SCO_Problema/views/view.edit.php',
      ),
      //SCO_Productos
      array(
        'from' => '<basepath>/modules/SCO_Productos/views/view.edit.php',
        'to'   => 'modules/SCO_Productos/views/view.edit.php',
      ),
      array(
        'from' => '<basepath>/modules/SCO_Productos/buscanombreprod.php',
        'to'   => 'modules/SCO_Productos/buscanombreprod.php',
      ),
      array(
        'from' => '<basepath>/modules/SCO_Productos/buscap.php',
        'to'   => 'modules/SCO_Productos/buscap.php',
      ),
      array(
        'from' => '<basepath>/modules/SCO_ProductosCotizados/CotizacionesList.php',
        'to'   => 'modules/SCO_ProductosCotizados/CotizacionesList.php',
      ),
      array(
        'from' => '<basepath>/modules/SCO_Productos/buscaproy.php',
        'to'   => 'modules/SCO_Productos/buscaproy.php',
      ),
      array(
        'from' => '<basepath>/modules/SCO_Productos/items.js',
        'to'   => 'modules/SCO_Productos/items.js',
      ),
      array(
        'from' => '<basepath>/modules/SCO_Productos/jquery.bdt.min.js',
        'to'   => 'modules/SCO_Productos/jquery.bdt.min.js',
      ),
      array(
        'from' => '<basepath>/modules/SCO_Productos/productos.js',
        'to'   => 'modules/SCO_Productos/productos.js',
      ),

      //SCO_ProductosCompras
      array(
        'from' => '<basepath>/modules/SCO_ProductosCompras/views/view.edit.php',
        'to'   => 'modules/SCO_ProductosCompras/views/view.edit.php',
      ),
      //SCO_ProductosDespachos
      array(
        'from' => '<basepath>/modules/SCO_ProductosDespachos/views/view.edit.php',
        'to'   => 'modules/SCO_ProductosDespachos/views/view.edit.php',
      ),
      array(
        'from' => '<basepath>/modules/SCO_ProductosDespachos/cantidad.php',
        'to'   => 'modules/SCO_ProductosDespachos/cantidad.php',
      ),
      //SCO_Riesgo
      array(
        'from' => '<basepath>/modules/SCO_Riesgo/views/view.edit.php',
        'to'   => 'modules/SCO_Riesgo/views/view.edit.php',
      ),
      array(
        'from' => '<basepath>/modules/SCO_Riesgo/NotificaProblema.php',
        'to'   => 'modules/SCO_Riesgo/NotificaProblema.php',
      ),
      array(
        'from' => '<basepath>/modules/SCO_Riesgo/riegoproblema.php',
        'to'   => 'modules/SCO_Riesgo/riegoproblema.php',
      ),

    ),

  //Logic Hooks
 'logic_hooks' => array(
      array(
       'module'  => 'SCO_Aprobadores',
       'hook'    => 'after_save',
       'order'   => 1,
       'description' => 'datosAprobadores',
       'file'   => 'custom/modules/SCO_Aprobadores/datosAprobadores.php',
       'class'   => 'CldatosA',
       'function'  => 'Fndatosa',
      ),
      array(
       'module'  => 'SCO_Aprobadores',
       'hook'    => 'after_save',
       'order'   => 2,
       'description' => 'correlativoAprobadores',
       'file'   => 'custom/modules/SCO_Aprobadores/correlativo.php',
       'class'   => 'Clcorrelativo',
       'function'  => 'Fncorrelativo',
      ),
      array(
       'module'  => 'SCO_Aprobadores',
       'hook'    => 'after_relationship_delete',
       'order'   => 1,
       'description' => 'correlativoAprobadoresDeleted',
       'file'   => 'custom/modules/SCO_Aprobadores/correlativoDeleted.php',
       'class'   => 'ClcorrelativoDel',
       'function'  => 'FncorrelativoDel',
      ),
      array(
       'module'  => 'SCO_Contactos',
       'hook'    => 'after_save',
       'order'   => 1,
       'description' => 'datosContactos',
       'file'   => 'custom/modules/SCO_Contactos/datosContactos.php',
       'class'   => 'CldatosC',
       'function'  => 'Fndatosc',
      ),

      array(
       'module'  => 'SCO_Despachos',
       'hook'    => 'before_save',
       'order'   => 1,
       'description' => 'carga_de_productos_de_OC_a_Despachos',
       'file'   => 'custom/modules/SCO_Despachos/proddes.php',
       'class'   => 'Clproddes',
       'function'  => 'Fnproddes',
      ),
      array(
       'module'  => 'SCO_Despachos',
       'hook'    => 'after_relationship_delete',
       'order'   => 1,
       'description' => 'Eliminar_relacion_de_productos_despachos',
       'file'   => 'custom/modules/SCO_Despachos/prodelimina.php',
       'class'   => 'Clprodelimina',
       'function'  => 'Fnprodelimina',
      ),

      array(
       'module'  => 'SCO_Distribuidor',
       'hook'    => 'before_save',
       'order'   => 1,
       'description' => 'Distribuidor',
       'file'   => 'custom/modules/SCO_Distribuidor/nomdist.php',
       'class'   => 'Clnomdist',
       'function'  => 'Fnnomdist',
      ),

      array(
       'module'  => 'SCO_documentos',
       'hook'    => 'before_save',
       'order'   => 1,
       'description' => 'documentooc',
       'file'   => 'custom/modules/SCO_documentos/Documentos.php',
       'class'   => 'Documentos',
       'function'  => 'Fndocs',
      ),

      array(
       'module'  => 'SCO_Embarque',
       'hook'    => 'before_save',
       'order'   => 1,
       'description' => 'datos evento y dropdown',
       'file'   => 'custom/modules/SCO_Embarque/evento.php',
       'class'   => 'Clevento',
       'function'  => 'Fnevento',
      ),
      array(
       'module'  => 'SCO_Embarque',
       'hook'    => 'after_relationship_add',
       'order'   => 1,
       'description' => 'agregando_relacion_para_cambio_de_estado_despacho',
       'file'   => 'custom/modules/SCO_Embarque/despacho.php',
       'class'   => 'Cldespacho',
       'function'  => 'Fndespacho',
      ),
      array(
       'module'  => 'SCO_Embarque',
       'hook'    => 'after_relationship_add',
       'order'   => 1,
       'description' => 'agregando_relacion_con_productosdespachos_de_los_despachos',
       'file'   => 'custom/modules/SCO_Embarque/productosdespachos.php',
       'class'   => 'Clproductosdespachos',
       'function'  => 'Fnproductosdespachos',
      ),
      array(
       'module'  => 'SCO_Embarque',
       'hook'    => 'after_relationship_delete',
       'order'   => 1,
       'description' => 'Eliminar_relacion_de_documentos_despachos_con_id_de_despacho',
       'file'   => 'custom/modules/SCO_Embarque/documentosdespachos_delte_r.php',
       'class'   => 'Cldocumentosdespachos_delte_r',
       'function'  => 'Fndocumentosdespachos_delte_r',
      ),
      array(
       'module'  => 'SCO_Embarque',
       'hook'    => 'after_relationship_add',
       'order'   => 1,
       'description' => 'agregando_relacion_de_documentos_de_los_despachos_a_embarques',
       'file'   => 'custom/modules/SCO_Embarque/documentosdespachos.php',
       'class'   => 'Cldocumentosdespachos',
       'function'  => 'Fndocumentosdespachos',
      ),
      array(
       'module'  => 'SCO_Embarque',
       'hook'    => 'after_relationship_delete',
       'order'   => 1,
       'description' => 'Eliminar_relacion_de_despachos',
       'file'   => 'custom/modules/SCO_Embarque/despacho_delte_r.php',
       'class'   => 'Cldespacho_delte_r',
       'function'  => 'Fndespacho_delte_r',
      ),
      array(
       'module'  => 'SCO_Embarque',
       'hook'    => 'after_relationship_delete',
       'order'   => 1,
       'description' => 'Eliminar_relacion_de_Embarque_despachos_con_id_de_despacho',
       'file'   => 'custom/modules/SCO_Embarque/productosdespachos_delte_r.php',
       'class'   => 'Clproductosdespachos_delte_r',
       'function'  => 'Fnproductosdespachos_delte_r',
      ),

      array(
       'module'  => 'SCO_Eventos',
       'hook'    => 'after_save',
       'order'   => 1,
       'description' => 'eventos_fecha',
       'file'   => 'custom/modules/SCO_Eventos/fechas.php',
       'class'   => 'Clfechas',
       'function'  => 'Fnfechas',
      ),

      array(
       'module'  => 'SCO_OrdenCompra',
       'hook'    => 'after_save',
       'order'   => 1,
       'description' => 'datosOrdenCompra',
       'file'   => 'custom/modules/SCO_OrdenCompra/datoso.php',
       'class'   => 'Cldatoso',
       'function'  => 'Fndatoso',
      ),
      array(
       'module'  => 'SCO_OrdenCompra',
       'hook'    => 'after_save',
       'order'   => 2,
       'description' => 'datosContactos',
       'file'   => 'custom/modules/SCO_OrdenCompra/datosco.php',
       'class'   => 'Cldatosco',
       'function'  => 'Fndatosco',
      ),
      array(
       'module'  => 'SCO_OrdenCompra',
       'hook'    => 'after_save',
       'order'   => 3,
       'description' => 'datosAprobadores',
       'file'   => 'custom/modules/SCO_OrdenCompra/datosap.php',
       'class'   => 'Cldatosap',
       'function'  => 'Fndatosap',
      ),
      array(
       'module'  => 'SCO_OrdenCompra',
       'hook'    => 'after_save',
       'order'   => 4,
       'description' => 'contactosAprobadores',
       'file'   => 'custom/modules/SCO_OrdenCompra/contap.php',
       'class'   => 'Clcontap',
       'function'  => 'Fncontap',
      ),
      array(
       'module'  => 'SCO_OrdenCompra',
       'hook'    => 'after_save',
       'order'   => 4,
       'description' => 'notificacionAprobadores',
       'file'   => 'custom/modules/SCO_OrdenCompra/Notifica.php',
       'class'   => 'Clnotifica',
       'function'  => 'Fnnotifica',
      ),
      array(
       'module'  => 'SCO_OrdenCompra',
       'hook'    => 'before_save',
       'order'   => 1,
       'description' => 'clonarOrdenCompraModulos',
       'file'   => 'custom/modules/SCO_OrdenCompra/clonar.php',
       'class'   => 'Clclonar',
       'function'  => 'Fnclonar',
      ),
      array(
       'module'  => 'SCO_OrdenCompra',
       'hook'    => 'after_ui_frame',
       'order'   => 1,
       'description' => 'vistas de orden de compra con js',
       'file'   => 'custom/modules/SCO_OrdenCompra/viewoc.php',
       'class'   => 'Clviewoc',
       'function'  => 'Fnviewoc',
      ),
      array(
       'module'  => 'SCO_OrdenCompra',
       'hook'    => 'after_relationship_delete',
       'order'   => 1,
       'description' => 'Eliminar_relacion_de_despachos',
       'file'   => 'custom/modules/SCO_OrdenCompra/deselimina.php',
       'class'   => 'Cldeselimina',
       'function'  => 'Fndeselimina',
      ),

      array(
       'module'  => 'SCO_PlandePagos',
       'hook'    => 'before_save',
       'order'   => 1,
       'description' => 'datosPP',
       'file'   => 'custom/modules/SCO_PlandePagos/PlanPagos.php',
       'class'   => 'PlanPagos',
       'function'  => 'Fndatospp',
      ),
      array(
       'module'  => 'SCO_PlandePagos',
       'hook'    => 'after_save',
       'order'   => 1,
       'description' => 'datosPP2',
       'file'   => 'custom/modules/SCO_PlandePagos/PlanPagos2.php',
       'class'   => 'PlanPagos2',
       'function'  => 'Fndatospp2',
      ),
      array(
       'module'  => 'SCO_PlandePagos',
       'hook'    => 'after_relationship_delete',
       'order'   => 1,
       'description' => 'datosPP eliminando la relacion',
       'file'   => 'custom/modules/SCO_PlandePagos/DeletePP.php',
       'class'   => 'ClDeletePP',
       'function'  => 'FnDeletePP',
      ),

      array(
       'module'  => 'SCO_Productos',
       'hook'    => 'before_save',
       'order'   => 1,
       'description' => 'Productos',
       'file'   => 'custom/modules/SCO_Productos/Productos.php',
       'class'   => 'ClProductos',
       'function'  => 'FnProductos',
      ),
      array(
       'module'  => 'SCO_Productos',
       'hook'    => 'before_save',
       'order'   => 2,
       'description' => 'Productos a',
       'file'   => 'custom/modules/SCO_Productos/Deproductos.php',
       'class'   => 'ClDeproductos',
       'function'  => 'FnDeproductos',
      ),

      array(
       'module'  => 'SCO_ProductosDespachos',
       'hook'    => 'before_save',
       'order'   => 2,
       'description' => 'Productos a',
       'file'   => 'custom/modules/SCO_ProductosDespachos/productosdespachos.php',
       'class'   => 'Clproductosdespachos',
       'function'  => 'Fnproductosdespachos',
      ),

      array(
       'module'  => 'SCO_Proveedor',
       'hook'    => 'after_ui_frame',
       'order'   => 1,
       'description' => 'vistas de proveedor con js',
       'file'   => 'custom/modules/SCO_Proveedor/ProveedorView.php',
       'class'   => 'ProveedorView',
       'function'  => 'fnview',
      ),
    ),
 );

?>
