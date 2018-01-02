<?php
 
//Import the PhpJasperLibrary
include_once('PhpJasperLibrary/tcpdf/tcpdf.php');
include_once("PhpJasperLibrary/PHPJasperXML.inc.php");
include '../functions.php';
// sec_session_start();
 // require_once '../../masterclass.php';
// include("../header.php");
 include'../../PhpJasperLibraryTriton/Toyota_Print_page.php';
include '../Rupees_In_Words.php';
sec_session_start();


 $Invoice_No=$_SESSION['bulkmasters'] ;
 // $Main_Basic_Value=0;
  $total_bed_amt1 = $_SESSION['total_bed_amt'];
  $Vat_cst1 = $_SESSION['Vat_cst'];
 $Grand_Total=$total_bed_amt1+$Vat_cst1;
 
 // echo "Mukesh";
 // exit;

 

 
 
 
 // $Basic_Amount1 = $_SESSION['basice_amt'];
 // $Assesseble_Amount1 = $_SESSION['Assessble_amt'];
 


$server="localhost";
$db="triton";

$user="root";
$pass="";
$version="0.8b";
$pgport=3306;
$pchartfolder="./class/pchart2";



  $xml =  simplexml_load_file("HMIL/TKM_Toyoto_INVOICE.jrxml");////Main File

			
 
$PHPJasperXML = new PHPJasperXML();

 $PHPJasperXML->arrayParameter=array("para"=>"'".$Invoice_No."'","bed"=>"". $_SESSION['bed']."",
 "vat"=>"".$_SESSION['vat']."","PerThousandRate"=>"".$_SESSION['PerThousandRate']."",
 "TKM_ItemAmt"=>"".$_SESSION['TKM_ItemAmt']."",
 "Sales_bedamt"=>"".$_SESSION['Sales_bedamt']."",
 "Sales_Subtotalamt"=>"".$_SESSION['Sales_Subtotalamt']."",
 "BarCodeTest"=>"".$_SESSION['BarCodeTest']."",
 "Sales_Grandamt"=>"".$_SESSION['Sales_Grandamt']."",
 "total"=>"".$_SESSION['total']."","CurrentTime"=>"".$_SESSION['currentdate']."");

$PHPJasperXML->xml_dismantle($xml);
 
$PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
$PHPJasperXML->outpage("I");    //page output method I:standard output  D:Download file  $_SESSION['PerThousandRate']
 
 
?>