<?php

require_once ("tcpdf_barcodes_2d.php");
if(!empty($_GET['D']))
{
$code = $_GET['D'];
//$type = "PDF417";
$type = "QRCODE";


//$barcodeobj = new TCPDF2DBarcode($code, $type);
$barcodeobj = new TCPDF2DBarcode($code, $type);

$barcodeobj->getBarcodePNG();
}
else
echo "No Value";
?>