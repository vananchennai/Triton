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
function FancyTable($header, $data,$header2)
{
    // Colors, line width and bold font
    $this->SetFillColor(255,0,0);
    $this->SetTextColor(255);
    $this->SetDrawColor(128,0,0);
    $this->SetLineWidth(.3);
    $this->SetFont('','B');
    // Header
    $width = array(30,40,20,25,25,25,25);
    for($i=0;$i<count($header);$i++)
        $this->Cell($width[$i],5,$header[$i],0,0,'C',true);
    $this->Ln();
	
   for($i=0;$i<count($header2);$i++)
        $this->Cell($width[$i],5,$header2[$i],0,0,'C',true);
    $this->Ln();
    // Color and font restoration
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('Arial','',7);
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
		$this->Cell($width[6],6,$row[6],'LR',0,'L',$fill);
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
    $this->Cell(50);
	
    // Title
    $this->Cell(90,10,'Amara Raja Batteries',1,0,'C');
    // Line break
    $this->Ln(10);
	 $this->Cell(50);
	
    // Title
    $this->Cell(90,10,'Pro-rata Logic',0,0,'C');
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
$header = array('Category', 'Logic', 'Effective', 'Minimum','Warranty Months','Warranty Months ','Discount');
$header2 = array('', 'Code', 'Date', '%','Minimum','Maximum','');
$pdf->SetFont('Arial','',7);
$pdf->AddPage();
$pdf->FancyTable($header,$data,$header2);
$pdf->Output('ProrataLogic.pdf', 'D');
// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

$pdf->Output('ProrataLogic.pdf', 'D');
unlink('testFile.txt');
}
elseif($type=='Excel')
{
	$query=$_SESSION['query'];
	header('Content-type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=ProrataLogic.xls");
    header("Pragma: no-cache");
    header("Expires: 0"); 
	$myquery = mysql_query($query);
	$table = "<table border='2' cellspacing='1'>
	<tr border='2'  style='height:40px; bordercolor:#FF0000; font-weight:50px;'>
	<td colspan='7' align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Amara Raja Batteries</td>
	</tr>
	<tr border='2'  style='height:30px; bordercolor:#FF0000; font-weight:50px;'>
	<td colspan='7' align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Prorata Logic</td>
	</tr>
	<tr border='2' style='bordercolor;#FF0000; height:30px;font-weight:20px;'>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Category</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Logic Code</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Effective Date</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Minimum%</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Warranty Months Minimum</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Warranty Months Maximum</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Discount</td>
	</tr>";
	
	while( $myrecord = mysql_fetch_array($myquery))
    {
	$table .="
	<tr  border='2' style='bordercolor;#000000; height:30px; font-weight:20px;'>
	<td align='center' style='width:200px; bordercolor;#FF0000; height:30px;'>".$myrecord[0]."</td>
	<td align='center' style=' width:400px; height=30px; bordercolor=#000000;'>".$myrecord[1]."</td>";
	$effdate= date('d/m/Y', strtotime($myrecord['2']));
	if($effdate=='01/01/1970')
	{
		$effdate='00/00/0000';
	}
	$table .="<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$effdate."</td>
	<td align='center' style='width:200px; bordercolor;#FF0000; height:30px;'>".$myrecord[3]."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord[4]."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord[5]."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord[6]."</td>
	</tr>";
}
echo $table;

}
elseif($type=='Document')
{
	$query=$_SESSION['query'];
	header('Content-type: application/vnd.ms-doc');
    header("Content-Disposition: attachment; filename=ProductMaster.doc");
    header("Pragma: no-cache");
    header("Expires: 0"); 
	
	$myquery = mysql_query($query);
	$table = "<table border='2' cellspacing='1'>
	<tr border='2'  style='height:40px; bordercolor:#FF0000; font-weight:50px;'>
	<td colspan='7' align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Amara Raja Batteries</td>
	</tr>
	<tr border='2'  style='height:30px; bordercolor:#FF0000; font-weight:50px;'>
	<td colspan='7' align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Product Master</td>
	</tr>
	<tr border='2' style='bordercolor;#FF0000; height:30px;font-weight:20px;'>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Category</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Logic Code</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Effective Date</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Minimum%</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Warranty Months Minimum</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Warranty Months Maximum</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Discount</td>
	</tr>";
	
	while( $myrecord = mysql_fetch_array($myquery))
    {
	$table .="
	<tr  border='2' style='bordercolor;#000000; height:30px; font-weight:20px;'>
	<td align='center' style='width:200px; bordercolor;#FF0000; height:30px;'>".$myrecord[0]."</td>
	<td align='center' style=' width:400px; height=30px; bordercolor=#000000;'>".$myrecord[1]."</td>";
	$effdate= date('d/m/Y', strtotime($myrecord['2']));
	if($effdate=='01/01/1970')
	{
		$effdate='00/00/0000';
	}
	$table .="<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$effdate."</td>
	<td align='center' style='width:200px; bordercolor;#FF0000; height:30px;'>".$myrecord[3]."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord[4]."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord[5]."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord[6]."</td>
	</tr>";
}
echo $table;
}
?>
