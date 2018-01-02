<?php
 
//Import the PhpJasperLibrary
include_once('PhpJasperLibrary/tcpdf/tcpdf.php');
include_once("PhpJasperLibrary/PHPJasperXML.inc.php");
include '../functions.php';
// sec_session_start();
 // require_once '../../masterclass.php';
 //include("../header.php");
 include'../../PhpJasperLibraryTriton/TMK_Print_page.php';
include '../Rupees_In_Words.php';
sec_session_start();


 $Invoice_No=$_SESSION['bulkmasters'] ;
 
  
 
 // $Main_Basic_Value=0;
/*   $total_bed_amt1 = $_SESSION['total_bed_amt'];
  $Vat_cst1 = $_SESSION['Vat_cst'];
 $Grand_Total=$total_bed_amt1+$Vat_cst1; */

 
 
 
 
 
 // $Basic_Amount1 = $_SESSION['basice_amt'];
 // $Assesseble_Amount1 = $_SESSION['Assessble_amt'];
 


$server="localhost";
$db="triton";

$user="root";
$pass="";
$version="0.8b";
$pgport=3306;
$pchartfolder="./class/pchart2";

// echo $_SESSION['BarCodeTest'];
// exit;



  $xml =  simplexml_load_file("HMIL/TKM_HMIL_INVOICE.jrxml");////Main File

			
 
$PHPJasperXML = new PHPJasperXML();

$PHPJasperXML->arrayParameter=array("para"=>"'".$Invoice_No."'","bed"=>"'". $_SESSION['bed']."'","BarCodeTest"=>"".$_SESSION['BarCodeTest']."",
"currentyear"=>"".$_SESSION['currentyear']."","total"=>"'".$_SESSION['total']."'",
"boxno"=>"".$_SESSION['boxno']."",
"CurrentTime"=>"".$_SESSION['currentdate']."");
$PHPJasperXML->xml_dismantle($xml);
$PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
$PHPJasperXML->outpage("I");    //page output method I:standard output  D:Download file
 
 
?>