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
function FancyTable($header, $data)
{
    // Colors, line width and bold font
    $this->SetFillColor(255,0,0);
    $this->SetTextColor(255);
    $this->SetDrawColor(128,0,0);
    $this->SetLineWidth(.2);
    $this->SetFont('','B');
    // Header
    $width = array(30,60,70,70,50);
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
    $this->SetFont('Arial','B',8);
    // Move to the right
    $this->Cell(40);
	
    // Title
    $this->Cell(190,10,'Amara Raja Batteries',1,0,'C');
    // Line break
    $this->Ln(10);
	 $this->Cell(40);
	
    // Title
    $this->Cell(190,10,'Vehicle Model Master',0,0,'C');
	$this->Ln(10);
}
// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
}
}

$type=$_SESSION['type'];
if($type=='PDF')
{
$pdf = new PDF();
$data = $pdf->LoadData('testFile.txt');
$header = array('Model Code', 'Model Name', 'Make Name', 'Segment Name', 'Product Group');
$pdf->SetFont('Arial','',10);
$pdf->AddPage();
$pdf->FancyTable($header,$data);
$pdf->Output('VehicleModelMaster.pdf', 'D');
// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',8);

$pdf->Output('VehicleModelMaster.pdf', 'D');
unlink('testFile.txt');
}
elseif($type=='Excel')
{
	$query=$_SESSION['query'];
	header('Content-type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=VehicleModelMaster.xls");
    header("Pragma: no-cache");
    header("Expires: 0"); 
	$myquery = mysql_query($query);
	$table = "<table border='2' cellspacing='1'><tr border='2'  style='height:40px; bordercolor:#FF0000; font-weight:50px;'>
	<td colspan='5' align='center' bgcolor='#CCCCCC' style='width:auto; bordercolor;#FF0000; height:30px;font-weight:bold;'>AmaraRaja Batteries</td></tr>
	
	<tr border='2'  style='height:30px; bordercolor:#FF0000; font-weight:50px;'>
	<td  colspan='5'align='center' bgcolor='#CCCCCC' style='width:auto; bordercolor;#FF0000; height:30px;font-weight:bold;'>Vehicle Model Master</td>
	</tr>
	<tr border='2'  style='bordercolor;#FF0000; height:30px;font-weight:20px;white-space:nowrap;'>
	<td align='center' bgcolor='#CCCCCC' style='width:auto; bordercolor;#FF0000; height:30px;font-weight:bold;'>Model Code</td>
	<td align='center' bgcolor='#CCCCCC' style='width:auto; bordercolor;#FF0000; height:30px;font-weight:bold;'>Model Name</td>
	<td align='center' bgcolor='#CCCCCC' style='width:auto; bordercolor;#FF0000; height:30px;font-weight:bold;'>Make Name</td>
	<td align='center' bgcolor='#CCCCCC' style='width:auto; bordercolor;#FF0000; height:30px;font-weight:bold;'>Segment Name</td>
	<td align='center' bgcolor='#CCCCCC' style='width:auto; bordercolor;#FF0000; height:30px;font-weight:bold;'>Product Group</td>
	</tr>";
	
	while( $myrecord = mysql_fetch_array($myquery))
    {
	$table .="<tr  border='2' style='bordercolor;#000000; height:30px; font-weight:20px;white-space:nowrap;'><td style='bordercolor;#FF0000; height:30px;'>".$myrecord[0]."</td>
	<td style='bordercolor;#FF0000; height:30px;'>".$myrecord[1]."</td>";
	 $groupgg="SELECT MakeName FROM vehiclemakemaster where MakeNo='".$myrecord['MakeName']."'";
	   $groupgg1 = mysql_query($groupgg);
	 $cntnog=mysql_num_rows($groupgg1);
	 if($cntnog==1)
	  {
		   	$groupgg2 = mysql_fetch_array($groupgg1);
			$testtemp=$groupgg2['MakeName'];
	   }
	   else
	   {
		   $testtemp ="";
	   }
	$table .="<td style='bordercolor;#FF0000; height:30px;'>".$testtemp."</td>";
	$group="SELECT segmentname FROM vehiclesegmentmaster where segmentcode='".$myrecord['segmentname']."'";
	   $gg1 = mysql_query($group);
	 $nog=mysql_num_rows($gg1);
	 if($nog==1)
	  {
		   	$group2 = mysql_fetch_array($gg1);
			$test=$group2['segmentname'];
	   }
	   else
	   {
		   $test ="";
	   }
	$table .="<td style='bordercolor;#FF0000; height:30px;'>".$test."</td>";
	$groupselct="SELECT ProductGroup FROM productgroupmaster where ProductCode='".$myrecord['ProductGroup']."'";
	   $groupselct1 = mysql_query($groupselct);
	   $cntno=mysql_num_rows($groupselct1);
	   if($cntno==1)
   {
		   	$groupselct12 = mysql_fetch_array($groupselct1);
			$testtempp=$groupselct12['ProductGroup'];
	   }
	    else
	   {
		   $testtempp ="";
	   }
	
	$table .="<td style='bordercolor;#FF0000; height:30px;'>". $testtempp."</td>
	</tr>";
}
echo $table;

}
elseif($type=='Document')
{
	$query=$_SESSION['query'];
	header('Content-type: application/vnd.ms-doc');
    header("Content-Disposition: attachment; filename=VehicleModelMaster.doc");
    header("Pragma: no-cache");
    header("Expires: 0"); 
	$myquery = mysql_query($query);
	$table = "<table border='2' cellspacing='1'><tr border='2'  style='height:40px; bordercolor:#FF0000; font-weight:50px;'>
	<td colspan='5' align='center' bgcolor='#CCCCCC' style='width:auto; bordercolor;#FF0000; height:30px;font-weight:bold;'>AmaraRaja Batteries</td></tr>
	
	<tr border='2'  style='height:30px; bordercolor:#FF0000; font-weight:50px;'>
	<td  colspan='5' align='center' bgcolor='#CCCCCC' style='width:auto; bordercolor;#FF0000; height:30px;font-weight:bold;'>Vehicle Model Master</td>
	</tr>
	<tr border='2'  style='bordercolor;#FF0000; height:30px;font-weight:20px;'>
	<td align='center' bgcolor='#CCCCCC' style='width:auto; bordercolor;#FF0000; height:30px;font-weight:bold;'>Model Code</td>
	<td align='center' bgcolor='#CCCCCC' style='width:auto; bordercolor;#FF0000; height:30px;font-weight:bold;'>Model Name</td>
	<td align='center' bgcolor='#CCCCCC' style='width:auto; bordercolor;#FF0000; height:30px;font-weight:bold;'>Make Name</td>
	<td align='center' bgcolor='#CCCCCC' style='width:auto; bordercolor;#FF0000; height:30px;font-weight:bold;'>Segment Name</td>
	</tr>";
	
	while( $myrecord = mysql_fetch_array($myquery))
    {
	$table .="<tr  border='2' style='bordercolor;#000000; height:30px; font-weight:20px;'><td style='bordercolor;#FF0000; height:30px;'>".$myrecord[0]."</td>
	<td style='bordercolor;#FF0000; height:30px;'>".$myrecord[1]."</td>";
	 $groupgg="SELECT MakeName FROM vehiclemakemaster where MakeNo='".$myrecord['MakeName']."'";
	   $groupgg1 = mysql_query($groupgg);
	 $cntnog=mysql_num_rows($groupgg1);
	 if($cntnog==1)
	  {
		   	$groupgg2 = mysql_fetch_array($groupgg1);
			$testtemp=$groupgg2['MakeName'];
	   }
	   else
	   {
		   $testtemp ="";
	   }
	$table .="<td style='bordercolor;#FF0000; height:30px;'>".$testtemp."</td>";
	$group="SELECT segmentname FROM vehiclesegmentmaster where segmentcode='".$myrecord['segmentname']."'";
	   $gg1 = mysql_query($group);
	 $nog=mysql_num_rows($gg1);
	 if($nog==1)
	  {
		   	$group2 = mysql_fetch_array($gg1);
			$test=$group2['segmentname'];
	   }
	   else
	   {
		   $test ="";
	   }
	$table .="<td style='bordercolor;#FF0000; height:30px;'>".$test."</td>";
	$groupselct="SELECT ProductGroup FROM productgroupmaster where ProductCode='".$myrecord['ProductGroup']."'";
	   $groupselct1 = mysql_query($groupselct);
	   $cntno=mysql_num_rows($groupselct1);
	   if($cntno==1)
   {
		   	$groupselct12 = mysql_fetch_array($groupselct1);
			$testtempp=$groupselct12['ProductGroup'];
	   }
	    else
	   {
		   $testtempp ="";
	   }
	
	$table .="<td style='bordercolor;#FF0000; height:30px;'>". $testtempp."</td>
	</tr>";
}
echo $table;
}
?>