<?php 
//activamos almacenamiento en el buffer
ob_start();
if (strlen(session_id())<1) 
session_start(['name' => 'STR']);

if(!isset($_SESSION['token_str'])){
  echo "Debe ingresar al sistema correctamente para visualizar el reporte";
}else{

if ($_SESSION['type_str']=="Administrador" OR $_SESSION['type_str']=="Técnico") {

$ajaxRequest=true; 

//incluimos el archivo factura
require_once "../Core/generalConfig.php";
require_once "../Helpers/Helpers.php";

require_once "itemsVoucher.php";

$id=decryption($_GET['id']); 

//obtenemos los datos de la cabecera de la venta actual
require_once "../Models/empresaModels.php";

$empresa = new empresaModels(); 

//$rsptav=$venta->show_model($_GET["id"]);
$insEmpresa = $empresa->datos_empresa_model();

//recorremos todos los valores que obtengamos
$regE  = $insEmpresa->fetchObject();

//establecemos los datos de la empresa
$logo=$regE->empresa_logo;
$ext_logo="png";
$anulado = "../Assets/images/pordefecto/cancelado.png";
$ext_anulado="png";
$empresa=$regE->empresa_nombre;
$ruc=$regE->empresa_ruc;
$direccion=$regE->empresa_direccion;
$telefono=$regE->empresa_celular;
$email=$regE->empresa_correo;

//obtenemos los datos de la cabecera de la venta actual
require_once "../Models/ventasModels.php";

$ventas = new ventasModels(); 
//$rsptav=$venta->show_model($_GET["id"]);
$insVentas = $ventas->ventacabecera($id);

//recorremos todos los valores que obtengamos
$regv  = $insVentas->fetchObject();

//configuracion de la factura
$pdf = new PDF_Invoice('p','mm','A4');
$pdf->AddPage();

//Verificamos si el comprobante esta anulado
if($regv->venta_estado == "0"){
  $pdf->anulado($anulado,$ext_anulado);
}

//enviamos datos de la empresa al metodo addSociete de la clase factura
$pdf->addSociete(utf8_decode($empresa),
                utf8_decode("R.U.C: ").$ruc."\n".
                utf8_decode("Dirección: ").utf8_decode($direccion)."\n".
                utf8_decode("Celular: ").$telefono."\n".
                "Email: ".$email,$logo,$ext_logo);
                

$pdf->fact_dev("$regv->comprobante_nombre ","$regv->venta_serie - $regv->venta_numComprobante");
$pdf->temporaire( "" );
$pdf->addDate(substr($regv->venta_fecha,0,10));

//enviamos los datos del cliente al metodo addClientAddresse de la clase factura
$pdf->addClientAdresse(utf8_decode($regv->cliente_nombre),
                        utf8_decode("Dirección: ").utf8_decode($regv->cliente_direccion), 
                       "Dni: ".$regv->cliente_dni, 
                       "Correo: ".$regv->cliente_correo, 
                       "Celular: ".$regv->cliente_celular);

//establecemos las columnas que va tener lña seccion donde mostramos los detalles de la venta
$cols=array( "CODIGO"=>23,
	         utf8_decode("MEDICAMENTO")=>78,
	         "CANTIDAD"=>22,
	         "P.U."=>25,
	         "DSCTO"=>20,
	         "SUBTOTAL"=>22);
$pdf->addCols( $cols);
$cols=array( "CODIGO"=>"L",
             utf8_decode("MEDICAMENTO")=>"L",
             "CANTIDAD"=>"C",
             "P.U."=>"R",
             "DSCTO"=>"R",
             "SUBTOTAL"=>"C" );
$pdf->addLineFormat( $cols);
$pdf->addLineFormat($cols); 

//actualizamos el valor de la coordenada "y" quie sera la ubicacion desde donde empecemos a mostrar los datos 
$y=85;

//obtenemos todos los detalles del a venta actual
$insVentaDetalle = $ventas->ventadetalles($id);

while($regd=$insVentaDetalle->fetchObject()){
  $line = array( "CODIGO"=>"$regd->prod_codigoin",
                 utf8_decode("MEDICAMENTO")=>utf8_decode("$regd->prod_nombre "),
                 "CANTIDAD"=>"$regd->detalleVenta_cantidad",
                 "P.U."=>"$regd->detalleVenta_precioV",  
                 "DSCTO"=>"$regd->detalleVenta_descuento",         
                 "SUBTOTAL"=>"$regd->subtotal");
  $size = $pdf->addLine( $y, $line );
  $y += $size +2; 

}  

/*aqui falta codigo de letras*/
require_once "Letras.php";
$V = new EnLetras();
 
$total=$regv->venta_total; 
$V=new EnLetras(); 
$V->substituir_un_mil_por_mil = true;

 $con_letra=strtoupper($V->ValorEnLetras($total," SOLES")); 
$pdf->addCadreTVAs("SON ".utf8_decode($con_letra));

//mostramos el impuesto
$pdf->addTVAs( $regv->venta_impuesto, $regv->venta_total, "$regE->empresa_moneda "." $regE->empresa_simbolo");
$pdf->addCadreEurosFrancs("$regE->empresa_impuesto"." $regv->venta_impuesto %");
$pdf->Output($regv->comprobante_nombre.'-'.substr($regv->venta_fecha,0,10).'.pdf' ,'I');
//$pdf->Output();

}else{
echo "No tiene permiso para visualizar el reporte";
}

}

ob_end_flush();
