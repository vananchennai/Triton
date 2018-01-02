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
    $this->SetFont('','B',10);
    // Header
    $width = array(30,45,25,35,35,25,13,20,25,20);
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
		$this->Cell($width[0],6,$row[0],'LR',0,'L',$fill);
		$this->Cell($width[1],6,$row[1],'LR',0,'L',$fill);
		$this->Cell($width[2],6,$row[2],'LR',0,'L',$fill);
		$this->Cell($width[3],6,$row[3],'LR',0,'L',$fill);
		$this->Cell($width[4],6,$row[4],'LR',0,'L',$fill);
		$this->Cell($width[5],6,$row[5],'LR',0,'L',$fill);
		$this->Cell($width[6],6,$row[6],'LR',0,'L',$fill);
		$this->Cell($width[7],6,$row[7],'LR',0,'L',$fill);
		$this->Cell($width[8],6,$row[8],'LR',0,'L',$fill);
		$this->Cell($width[9],6,$row[9],'LR',0,'L',$fill);
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
    $this->Cell(90,10,'Retailer Master',0,0,'C');
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
$header = array('Retailer', 'Retailer','Franchisee' ,'Category','Contact','Contact','Credit','Credit','Tin No','Tin Date');
$header2 = array('Code', 'Name','' ,'','Person','No','Days','Limit','','');
$pdf->SetFont('Arial','',9);
$pdf->AddPage();
$pdf->FancyTable($header,$data,$header2);
$pdf->Output('RetailerMaster.pdf', 'D');
// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

$pdf->Output('RetailerMaster.pdf', 'D');
unlink('testFile.txt');
}
elseif($type=='Excel')
{
	$query=$_SESSION['query'];
	header('Content-type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=RetailerMaster.xls");
    header("Pragma: no-cache");
    header("Expires: 0"); 
	$myquery = mysql_query($query);
	$table = "<table border='2' cellspacing='1'>
	<tr border='2'  style='height:40px; bordercolor:#FF0000; font-weight:50px;'>
	<td colspan='16' align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>J.K. Fenner (India) Limited</td>
	</tr>
	<tr border='2'  style='height:30px; bordercolor:#FF0000; font-weight:50px;'>
	<td colspan='16' align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Retailer Master</td>
	</tr>
	<tr border='2' style='bordercolor;#FF0000; height:30px;font-weight:20px;'>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Retailer Code</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>RetailerName</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Franchisee</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Category</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Contact Name</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Contact No</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Credit Days</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Credit Limit</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Tin No</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Tin Date</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Franchisee M.E</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Retailer Classification</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Geography</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Retailer Category 1</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Retailer Category 2 </td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Retailer Category 3</td>
	</tr>";
	
	while( $myrecord = mysql_fetch_array($myquery))
    {
	$table .="
	<tr  border='2' style='bordercolor;#000000; height:30px; font-weight:20px;'>
	<td align='center' style='width:200px; bordercolor;#FF0000; height:30px;'>".$myrecord[0]."</td>
	<td align='center' style=' width:400px; height=30px; bordercolor=#000000;'>".$myrecord[1]."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord[5]."</td>";
	$groupgg="SELECT * FROM retailercategory where CategoryCode='".$myrecord['Category']."'";
	   $groupgg1 = mysql_query($groupgg);
	 $cntnog=mysql_num_rows($groupgg1);
	 if($cntnog==1)
	  {
		   	$groupgg2 = mysql_fetch_array($groupgg1);
			$testtemp=$groupgg2['RetailerCategory'];
	   }
	   else
	   {
		   $testtemp ="";
	   }
	   if($record[12] != NULL)
		 {
			$tindate = date('d-m-Y',strtotime($record[12])) ;
		 }
		 else
		 {
			 $tindate = "";
		 }
	$table .="<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord['Category']."</td>
	<td align='center' style=' width:200px; height=30px; bordercolor=#000000;'>".$myrecord[7]."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord[8]."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord[9]."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord[10]."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord[11]."</td>
	<td align='center' style=' width:200px; height=30px; bordercolor=#000000;'>".$tindate."</td>
	<td align='center' style=' width:200px; height=30px; bordercolor=#000000;'>".$myrecord['franchiseeme']."</td>
	<td align='center' style=' width:200px; height=30px; bordercolor=#000000;'>".$myrecord['retailerclassification']."</td>
	<td align='center' style=' width:200px; height=30px; bordercolor=#000000;'>".$myrecord['geographical']."</td>
	<td align='center' style=' width:200px; height=30px; bordercolor=#000000;'>".$myrecord['retailercategory1']."</td>
	<td align='center' style=' width:200px; height=30px; bordercolor=#000000;'>".$myrecord['retailercategory2']."</td>
	<td align='center' style=' width:200px; height=30px; bordercolor=#000000;'>".$myrecord['retailercategory3']."</td>
	</tr>";
}
echo $table;

}
elseif($type=='Document')
{
	$query=$_SESSION['query'];
	header('Content-type: application/vnd.ms-doc');
    header("Content-Disposition: attachment; filename=RetailerMaster.doc");
    header("Pragma: no-cache");
    header("Expires: 0"); 
	
	$myquery = mysql_query($query);
	$table = "<table border='2' cellspacing='1'>
<tr border='2'  style='height:40px; bordercolor:#FF0000; font-weight:50px;'>
	<td colspan='10' align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>J.K. Fenner (India) Limited</td>
	</tr>
	<tr border='2'  style='height:30px; bordercolor:#FF0000; font-weight:50px;'>
	<td colspan='10' align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Retailer Master</td>
	</tr>
	<tr border='2' style='bordercolor;#FF0000; height:30px;font-weight:20px;'>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Retailer Code</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>RetailerName</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Franchisee</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Category</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Contact Name</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Contact No</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Credit Days</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Credit Limit</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Tin No</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Tin Date</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Franchisee M.E</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Retailer Classification</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Geography</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Retailer Category 1</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Retailer Category 2 </td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Retailer Category 3</td>
	</tr>";
	
	while( $myrecord = mysql_fetch_array($myquery))
    {
	$table .="
	<tr  border='2' style='bordercolor;#000000; height:30px; font-weight:20px;'>
	<td align='center' style='width:200px; bordercolor;#FF0000; height:30px;'>".$myrecord[0]."</td>
	<td align='center' style=' width:400px; height=30px; bordercolor=#000000;'>".$myrecord[1]."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord[5]."</td>";
	$groupgg="SELECT * FROM retailercategory where CategoryCode='".$myrecord['Category']."'";
	   $groupgg1 = mysql_query($groupgg);
	 $cntnog=mysql_num_rows($groupgg1);
	 if($cntnog==1)
	  {
		   	$groupgg2 = mysql_fetch_array($groupgg1);
			$testtemp=$groupgg2['RetailerCategory'];
	   }
	   else
	   {
		   $testtemp ="";
	   }
	   if($record[12] != NULL)
		 {
			$tindate = date('d-m-Y',strtotime($record[12])) ;
		 }
		 else
		 {
			 $tindate = "";
		 }
	$table .="<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord['Category']."</td>
	<td align='center' style=' width:200px; height=30px; bordercolor=#000000;'>".$myrecord[7]."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord[8]."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord[9]."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord[10]."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord[11]."</td>
	<td align='center' style=' width:200px; height=30px; bordercolor=#000000;'>".$tindate."</td>
	</tr>";
}
echo $table;
}
?>
