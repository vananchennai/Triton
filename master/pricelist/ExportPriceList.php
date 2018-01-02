<?php
include '../../functions.php';
sec_session_start();
require_once '../../masterclass.php';
$news = new News();  
require('../../fpdfa3.php');
 // Create a new News Object

class PDF extends FPDF
{

function LoadData($file)
{
    // Read file lines 
    $lines = file($file);
    $data = array();
    foreach($lines as $line)
        $data[] = explode(';',trim($line));
		//print_r($data);
    return $data;

}
function FancyTable($header, $data,$header2)
{
    // Colors, line width and bold font
    $this->SetFillColor(255,0,0);
    $this->SetTextColor(255);
    $this->SetDrawColor(128,0,0);
    $this->SetLineWidth(.3);
    $this->SetFont('','B');
    // Header
    $width = array(23,35,16,16,43,63,20,20,20,20);
    for($i=0;$i<count($header);$i++)
        $this->Cell($width[$i],5,$header[$i],0,0,'C',true);
    $this->Ln();
	
    for($i=0;$i<count($header2);$i++)
        $this->Cell($width[$i],5,$header2[$i],0,0,'C',true);
    $this->Ln();
    // Color and font restoration
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('Arial','',8);
    // Data
    $fill = false;
    foreach($data as $row)
    {
		$this->Cell($width[0],8,$row[0],'LR',0,'L',$fill);
		$this->Cell($width[1],8,$row[1],'LR',0,'L',$fill);
		$this->Cell($width[2],8,$row[2],'LR',0,'L',$fill);
		$this->Cell($width[3],8,$row[3],'LR',0,'L',$fill);
		$this->Cell($width[4],8,$row[4],'LR',0,'L',$fill);
		$this->Cell($width[5],8,$row[5],'LR',0,'L',$fill);
		$this->Cell($width[6],8,$row[6],'LR',0,'R',$fill);
		$this->Cell($width[7],8,$row[7],'LR',0,'R',$fill);
		$this->Cell($width[8],8,$row[8],'LR',0,'R',$fill);
		$this->Cell($width[9],8,$row[9],'LR',0,'R',$fill);
		
        $this->Ln();
        $fill = !$fill;
    }
    // Closing line
    $this->Cell(array_sum($width),0,'','T');
}




// Page header
function Header()
{
    // Logo
  //  $this->Image('logo_amaron.png',10,6,30);
    // Arial bold 15
	$this->SetFillColor(128,0,0);
    $this->SetTextColor(0);
    $this->SetDrawColor(128,0,0);
    $this->SetFont('Arial','B',15);
    // Move to the right
    $this->Cell(90);
	
    // Title
    $this->Cell(90,10,'J.K. Fenner (India) Limited',1,0,'C');
    // Line break
    $this->Ln(10);
	 $this->Cell(90);
	
    // Title
    $this->Cell(90,10,'Price List Master',0,0,'C');
	$this->Ln(10);
}
// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',10);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
}
}

$type=$_SESSION['type'];
if($type=='PDF')
{
$pdf = new PDF();
$data = $pdf->LoadData('testFile.txt');
$header = array('Pricelist', 'Pricelist',  'Effective', 'Applicable', 'Product' ,'Product' ,'Consumer','Distributor','Retailer','Industrial');
$header2 = array('Code', 'Name', 'Date', 'Till', 'Code' ,'Description' ,'Price','Price','Price','Price');
$pdf->SetFont('Arial','',8);
$pdf->AddPage();
$pdf->FancyTable($header,$data,$header2);
$pdf->Output('Pricelist Master.pdf', 'D');
// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',8);

$pdf->Output('Pricelist Master.pdf', 'D');
unlink('testFile.txt');
}
elseif($type=='Excel')
{
	$query=$_SESSION['query'];
	header('Content-type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=PriceListMaster.xls");
    header("Pragma: no-cache");
    header("Expires: 0"); 
	$myquery = mysql_query($query);
	$table = "<table border='2' cellspacing='1'>
	<tr border='2'  style='height:40px; bordercolor:#FF0000; font-weight:50px;'>
	<td colspan='10' align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>J.K. Fenner (India) Limited</td>
	</tr>
	<tr border='2'  style='height:30px; bordercolor:#FF0000; font-weight:50px;'>
	<td colspan='10' align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Price List Master</td>
	</tr>
	<tr border='2' style='bordercolor;#FF0000; height:30px;font-weight:20px;'>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Price List Code</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Price List Name</td>
   <td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Effective Date</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Applicable Till</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Product Code</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Product Description</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Consumer Price</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Distributor Price</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Retailer Price</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Industrial Price</td>
	</tr>";
	
	while( $myrecord = mysql_fetch_array($myquery))
    {
		 $eft=date("d/m/Y",strtotime($myrecord[2])) ;
      if($eft == '01/01/1970')
      {
      $eft="00/00/0000";
      }
      $aplt=date("d/m/Y",strtotime($myrecord[3])) ;
      if($aplt == '01/01/1970')
      {
      $aplt="00/00/0000";
      }
	$table .="
	<tr  border='2' style='bordercolor;#000000; height:30px; font-weight:20px;'>
	<td align='center' style='width:200px; bordercolor;#FF0000; height:30px;'>".$myrecord[0]."</td>
	<td align='center' style=' width:400px; height=30px; bordercolor=#000000;'>".$myrecord[1]."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$eft."</td>
	<td align='center' style='width:200px; bordercolor;#FF0000; height:30px;'>".$aplt."</td>
	<td align='center' style=' width:200px; height=30px; bordercolor=#000000;'>".$myrecord[4]."</td>";
	$Productgroupselct="SELECT ProductDescription FROM productmaster where ProductCode='".$myrecord['productcode']."'";
	   $Productgroupselct1 = mysql_query($Productgroupselct);
	   $Productcntno1=mysql_num_rows($Productgroupselct1);
	   if($Productcntno1==1)
   		{
		   	$Productgroupselct12 = mysql_fetch_array($Productgroupselct1);
			$Producttesttempp=$Productgroupselct12['ProductDescription'];
	   }
	    else
	   {
		   $Producttesttempp ="";
	   }
	$table .="<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$Producttesttempp."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord[5]."</td>
	<td align='center' style=' width:200px; height=30px; bordercolor=#000000;'>".$myrecord[6]."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord[7]."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord[8]."</td>
	
	</tr>";
}
echo $table;

}
elseif($type=='Document')
{
	$query=$_SESSION['query'];
	header('Content-type: application/vnd.ms-doc');
    header("Content-Disposition: attachment; filename=PriceListMaster.doc");
    header("Pragma: no-cache");
    header("Expires: 0"); 
	
	$myquery = mysql_query($query);
	$table = "<table border='2' cellspacing='1'>
<tr border='2'  style='height:40px; bordercolor:#FF0000; font-weight:50px;'>
	<td colspan='10' align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>J.K. Fenner (India) Limited</td>
	</tr>
	<tr border='2'  style='height:30px; bordercolor:#FF0000; font-weight:50px;'>
	<td colspan='10' align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Price List Master</td>
	</tr>
	<tr border='2' style='bordercolor;#FF0000; height:30px;font-weight:20px;'>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Price List Code</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Price List Name</td>
   
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Effective Date</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Applicable Till</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Product Code</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Product Description</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Consumer Price</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Distributor Price</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Retailer Price</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Industrial Price</td>
	</tr>";
	
	while( $myrecord = mysql_fetch_array($myquery))
    {
		 $eft=date("d/m/Y",strtotime($myrecord[2])) ;
      if($eft == '01/01/1970')
      {
      $eft="00/00/0000";
      }
      $aplt=date("d/m/Y",strtotime($myrecord[3])) ;
      if($aplt == '01/01/1970')
      {
      $aplt="00/00/0000";
      }
	$table .="
	<tr  border='2' style='bordercolor;#000000; height:30px; font-weight:20px;'>
	<td align='center' style='width:200px; bordercolor;#FF0000; height:30px;'>".$myrecord[0]."</td>
	<td align='center' style=' width:400px; height=30px; bordercolor=#000000;'>".$myrecord[1]."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$eft."</td>
	<td align='center' style='width:200px; bordercolor;#FF0000; height:30px;'>".$aplt."</td>
	<td align='center' style=' width:200px; height=30px; bordercolor=#000000;'>".$myrecord[4]."</td>";
	$Productgroupselct="SELECT ProductDescription FROM productmaster where ProductCode='".$myrecord['productcode']."'";
	   $Productgroupselct1 = mysql_query($Productgroupselct);
	   $Productcntno1=mysql_num_rows($Productgroupselct1);
	   if($Productcntno1==1)
   		{
		   	$Productgroupselct12 = mysql_fetch_array($Productgroupselct1);
			$Producttesttempp=$Productgroupselct12['ProductDescription'];
	   }
	    else
	   {
		   $Producttesttempp ="";
	   }
	$table .="<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$Producttesttempp."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord[5]."</td>
	<td align='center' style=' width:200px; height=30px; bordercolor=#000000;'>".$myrecord[6]."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord[7]."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord[8]."</td>
	
	</tr>";
}
echo $table;
}
?>
