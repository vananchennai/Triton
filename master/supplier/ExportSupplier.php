<?php
include '../../functions.php';
sec_session_start();
 require_once '../../masterclass.php';
$news = new News();  
require('../../fpdf.php');
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
function FancyTable($header, $data)
{
    // Colors, line width and bold font
    $this->SetFillColor(255,0,0);
    $this->SetTextColor(255);
    $this->SetDrawColor(128,0,0);
    $this->SetLineWidth(.3);
    $this->SetFont('','B');
    // Header
    $width = array(20,25,75,35,15,25);
    for($i=0;$i<count($header);$i++)
        $this->Cell($width[$i],7,$header[$i],1,0,'C',true);
    $this->Ln();
    // Color and font restoration
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('Arial','',8);
    // Data
    $fill = false;
    foreach($data as $row)
    {
		$this->Cell($width[0],6,$row[0],'LR',0,'L',$fill);
		$this->Cell($width[1],6,$row[1],'LR',0,'L',$fill);
		$this->Cell($width[2],6,$row[2],'LR',0,'L',$fill);
		$this->Cell($width[3],6,$row[3],'LR',0,'L',$fill);
		$this->Cell($width[4],6,$row[4],'LR',0,'L',$fill);
		$this->Cell($width[5],6,$row[5],'LR',0,'L',$fill);
		
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
    $this->SetFont('Arial','B',12);
    // Move to the right
    $this->Cell(50);
	
    // Title
    $this->Cell(90,10,'Amara Raja Batteries',1,0,'C');
    // Line break
    $this->Ln(10);
	 $this->Cell(50);
	
    // Title
    $this->Cell(90,10,'ARBL Warehouse Master',0,0,'C');
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
$header = array('Code', 'Warehouse Name', 'Address','Branch','Pin Code','PAN/ITNo');
$pdf->SetFont('Arial','',8);
$pdf->AddPage();
$pdf->FancyTable($header,$data);
$pdf->Output();
// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',10);

$pdf->Output();
unlink('testFile.txt');
}
elseif($type=='Excel')
{
	$query=$_SESSION['query'];
	header('Content-type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=ARBLWarehouseMaster.xls");
    header("Pragma: no-cache");
    header("Expires: 0"); 
	$myquery = mysql_query($query);
	$table = "<table border='2' cellspacing='1'>
		<tr border='2'  style='height:40px; bordercolor:#FF0000; font-weight:50px;'>
		<td colspan='6' align='center' border='1'  style='height:40px; bordercolor:#FF0000; font-weight:50px;'>AmaraRaja Batteries</td></tr>
	<tr border='2'  style='height:30px; bordercolor:#FF0000; font-weight:50px;'>
	<td colspan='6' align='center' border='1'  style='height:30px; bordercolor:#FF0000; font-weight:50px;'>ARBL Warehouse Master</td></tr>
	
	<tr border='2' style='bordercolor;#FF0000; height:30px;font-weight:20px;'>
	<td align='center' style='width:200px; bordercolor;#FF0000; height:30px;font-weight:20px;'>ARBL Warehouse Code</td>
	<td align='center' style='width:200px; bordercolor;#FF0000; height:30px;font-weight:20px;'>ARBL Warehouse Name</td>
	<td align='center' style='width:200px; bordercolor;#FF0000; height:30px;font-weight:20px;'>Address</td>
	<td align='center' style='width:200px; bordercolor;#FF0000; height:30px;font-weight:20px;'>Branch</td>
	<td align='center' style='width:200px; bordercolor;#FF0000; height:30px;font-weight:20px;'>Pin Code</td>
	<td align='center' style='width:200px; bordercolor;#FF0000; height:30px;font-weight:20px;'>PAN/IT No</td>
	</tr>";
	
	while( $myrecord = mysql_fetch_array($myquery))
    {
	$table .="<tr  border='2' style='bordercolor;#000000; height:40px; font-weight:20px;'>
	<td align='center' style='width:200px; bordercolor;#FF0000; height:30px;font-weight:20px;'>".$myrecord[0]."</td>
	<td align='center' style=' width:200px; height=20px; bordercolor=#000000;'>".$myrecord[1]."</td>
	<td align='center' style='width:200px; height=20px; bordercolor=#000000;'>".$myrecord[2]."</td>";
	
	   $groupselct="SELECT branchname FROM branch where branchcode='".$myrecord['Branch']."'";
	   $groupselct1 = mysql_query($groupselct);
	   $cntno=mysql_num_rows($groupselct1);
	   if($cntno==1)
   {
		   	$groupselct12 = mysql_fetch_array($groupselct1);
			$testtempp=$groupselct12['branchname'];
	   }
	    else
	   {
		   $testtempp ="";
	   }
	$table .="<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$testtempp."</td>
	<td align='center' style='width:200px; height=20px; bordercolor=#000000;'>".$myrecord[6]."</td>
	<td align='center' style='width:200px; height=20px; bordercolor=#000000;'>".$myrecord[7]."</td>
	</tr>";
}
echo $table;

}
elseif($type=='Document')
{
	$query=$_SESSION['query'];
	header('Content-type: application/vnd.ms-doc');
    header("Content-Disposition: attachment; filename=ARBLWarehouseMaster.doc");
    header("Pragma: no-cache");
    header("Expires: 0"); 
	
	$myquery = mysql_query($query);
		$table = "<table border='2' cellspacing='1'>
		<tr border='2'  style='height:40px; bordercolor:#FF0000; font-weight:50px;'>
		<td colspan='6' align='center' border='1'  style='height:40px; bordercolor:#FF0000; font-weight:50px;'>AmaraRaja Batteries</td></tr>
	<tr border='2'  style='height:30px; bordercolor:#FF0000; font-weight:50px;'>
	<td colspan='6' align='center' border='1'  style='height:30px; bordercolor:#FF0000; font-weight:50px;'>ARBL Warehouse Master</td></tr>
	
	<tr border='2' style='bordercolor;#FF0000; height:30px;font-weight:20px;'>
	<td align='center' style='width:200px; bordercolor;#FF0000; height:30px;font-weight:20px;'>ARBL Warehouse Code</td>
	<td align='center' style='width:200px; bordercolor;#FF0000; height:30px;font-weight:20px;'>ARBL Warehouse Name</td>
	<td align='center' style='width:200px; bordercolor;#FF0000; height:30px;font-weight:20px;'>Address</td>
	<td align='center' style='width:200px; bordercolor;#FF0000; height:30px;font-weight:20px;'>Branch</td>
	<td align='center' style='width:200px; bordercolor;#FF0000; height:30px;font-weight:20px;'>Pin Code</td>
	<td align='center' style='width:200px; bordercolor;#FF0000; height:30px;font-weight:20px;'>PAN/IT No</td>
	</tr>";
	
	while( $myrecord = mysql_fetch_array($myquery))
    {
	$table .="<tr  border='2' style='bordercolor;#000000; height:40px; font-weight:20px;'>
	<td align='center' style='width:200px; bordercolor;#FF0000; height:30px;font-weight:20px;'>".$myrecord[0]."</td>
	<td align='center' style=' width:200px; height=20px; bordercolor=#000000;'>".$myrecord[1]."</td>
	<td align='center' style='width:200px; height=20px; bordercolor=#000000;'>".$myrecord[2]."</td>";
	
	   $groupselct="SELECT branchname FROM branch where branchcode='".$myrecord['Branch']."'";
	   $groupselct1 = mysql_query($groupselct);
	   $cntno=mysql_num_rows($groupselct1);
	   if($cntno==1)
   {
		   	$groupselct12 = mysql_fetch_array($groupselct1);
			$testtempp=$groupselct12['branchname'];
	   }
	    else
	   {
		   $testtempp ="";
	   }
	$table .="<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$testtempp."</td>
	<td align='center' style='width:200px; height=20px; bordercolor=#000000;'>".$myrecord[6]."</td>
	<td align='center' style='width:200px; height=20px; bordercolor=#000000;'>".$myrecord[7]."</td>
	</tr>";
}
echo $table;
}
?>