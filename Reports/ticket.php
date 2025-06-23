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

require('../Assets/vendor/fpdf181/fpdf.php');

$id=decryption($_GET['id']); 

//obtenemos los datos de la cabecera de la venta actual
require_once "../Models/empresaModels.php";

$empresa = new empresaModels(); 

//$rsptav=$venta->show_model($_GET["id"]);
$insEmpresa = $empresa->datos_empresa_model();

//recorremos todos los valores que obtengamos
$regE  = $insEmpresa->fetchObject();

//establecemos los datos de la empresa
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

//instanciamos la clase para generar el documento pdf

$pdf = new FPDF('P','mm',array(80,150)); // Tamaño tickt 80mm x 150 mm (largo aprox)
//$pdf = new FPDF();

$pdf->AddPage();
$pdf->SetFont('Helvetica','',12);
$pdf->Cell(60,4,$empresa,0,1,'C');
$pdf->SetFont('Helvetica','',8);
$pdf->Cell(60,4,'R.U.C.: '.$ruc,0,1,'C');
$pdf->Cell(60,4,utf8_decode('Dirección: ').$direccion,0,1,'C');
$pdf->Cell(60,4,'Celular : '.$telefono,0,1,'C');
$pdf->Cell(60,4,'Correo : '.$email,0,1,'C');
 
// DATOS FACTURA        
$pdf->Ln(5);
$pdf->Cell(60,4,'Fecha : '.substr($regv->venta_fecha,0,10),0,1,'');
$pdf->Cell(60,4,'Cliente : '.utf8_decode($regv->cliente_nombre),0,1,'');
$pdf->Cell(60,4,'Vendedor : '.utf8_decode($regv->usuario_nombre),0,1,'');
$pdf->Ln(3);
$pdf->Cell(60,4,'FOLIO: '.$regv->venta_serie."-".$regv->venta_numComprobante,0,1,'');

$pdf->SetFont('Helvetica', 'B', 7);
$pdf->Cell(30, 10, utf8_decode('Medicamento'), 0);
$pdf->Cell(5, 10, 'Cant.',0,0,'R');
$pdf->Cell(10, 10, 'P.U',0,0,'R');
$pdf->Cell(15, 10, 'Subtotal',0,0,'R');
$pdf->Ln(8);
$pdf->Cell(60,0,'','T');
$pdf->Ln(1);
 
// PRODUCTOS
$insVentaDetalle = $ventas->ventadetalles($id);
$cantidad=0;
while($regd=$insVentaDetalle->fetchObject()){
$pdf->SetFont('Helvetica', '', 7);
$pdf->MultiCell(30,4,$regd->prod_nombre,0,'L'); 
$pdf->Cell(35, -5, $regd->detalleVenta_cantidad,0,0,'R');
$pdf->Cell(10, -5, $regE->empresa_simbolo.formatMoney($regd->detalleVenta_precioV),0,0,'R');
$pdf->Cell(15, -5, $regE->empresa_simbolo.formatMoney($regd->subtotal),0,0,'R');
$pdf->Ln(3);
$cantidad+=$regd->detalleVenta_cantidad;
}
$pdf->Ln(6);
$pdf->Cell(60,0,'','T');
$pdf->Ln(2);    
$pdf->Cell(25, 10, 'TOTAL :', 0);    
$pdf->Cell(20, 10, '', 0);
$pdf->Cell(15, 10, $regE->empresa_simbolo.formatMoney($regv->venta_total),0,0,'R');
$pdf->Ln(5);    
$pdf->Cell(25, 10, utf8_decode('N° DE PRODUCTOS : ').$cantidad, 0);    

// PIE DE PAGINA
$pdf->Ln(10);
$pdf->Cell(60,0,'GRACIAS POR SU COMPRA',0,1,'C');

 
$pdf->Output($regv->comprobante_nombre.'-'.substr($regv->venta_fecha,0,10).'.pdf','i');
}else{
    echo "No tiene permiso para visualizar el reporte";
}
    
} 
ob_end_flush();