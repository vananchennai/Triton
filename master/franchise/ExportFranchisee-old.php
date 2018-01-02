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
    $width = array(13,30,23,12,50,10,18,16,30,20,15,15,15,15);
    for($i=0;$i<count($header);$i++)
        $this->Cell($width[$i],5,$header[$i],0,0,'C',true);
    $this->Ln();
	
    for($i=0;$i<count($header2);$i++)
        $this->Cell($width[$i],5,$header2[$i],0,0,'C',true);
    $this->Ln();
    // Color and font restoration
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('Arial','',6);
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
		$this->Cell($width[10],6,$row[10],'LR',0,'L',$fill);
		$this->Cell($width[11],6,$row[11],'LR',0,'L',$fill);
		$this->Cell($width[12],6,$row[12],'LR',0,'L',$fill);
		$this->Cell($width[13],6,$row[13],'LR',0,'L',$fill);
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
    $this->Cell(90,10,'Distributor Master',0,0,'C');
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
$header = array('Distributor', 'Distributor', 'Contact', 'Designation', 'Address' ,'Pincode' ,'Tel.No','Mob.No','Email','Branch','State','Region','Tin No','Cst No');
$header2 = array('Code', 'Name','Person','','','','','','','','','','','');
$pdf->SetFont('Arial','B',7);
$pdf->AddPage();
$pdf->FancyTable($header,$data,$header2);
$pdf->Output('DistributorMaster.pdf', 'D');
// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',6);

$pdf->Output('DistributorMaster.pdf', 'D');
unlink('testFile.txt');
}
elseif($type=='Excel')
{
	$query=$_SESSION['query'];
	header('Content-type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=DistributorMaster.xls");
    header("Pragma: no-cache");
    header("Expires: 0"); 
	$myquery = mysql_query($query);
	$table = "<table border='2' cellspacing='1'>
<tr border='2' align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>
	<td colspan='16' align='center' border='1'  style='height:40px; bordercolor:#FF0000; font-weight:50px;'>J.K. Fenner (India) Limited</td>
	</tr>
	<tr border='2'  align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>
	<td colspan='16' align='center' border='1'  style='height:30px; bordercolor:#FF0000; font-weight:50px;'>Distributor Master</td>
	</tr>
	<tr border='2' align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>
	
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Distributor Code</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Distributor Name</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Contact Person</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Designation</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Address</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Pincode</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Telephone No</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Mobile No</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Email</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Branch</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>State</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Region</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Country</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>City</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Tin No</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Cst No</td>
	</tr>";
	
	while( $myrecord = mysql_fetch_array($myquery))
    {
	$table .="
	<tr  border='2' style='bordercolor;#000000; height:40px; font-weight:20px;'>
	<td align='center' style='width:200px; bordercolor;#FF0000; height:30px;'>".$myrecord[0]."</td>
	<td align='center' style=' width:400px; height=30px; bordercolor=#000000;'>".$myrecord[1]."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord[2]."</td>
	<td align='center' style='width:200px; bordercolor;#FF0000; height:30px;'>".$myrecord[3]."</td>
	<td align='center' style=' width:200px; height=30px; bordercolor=#000000;'>".$myrecord[4]."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord[5]."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord[6]."</td>
	<td align='center' style=' width:200px; height=30px; bordercolor=#000000;'>".$myrecord[7]."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord[8]."</td>";
	 $groupselct="SELECT branchname FROM branch where branchcode='".$myrecord['Branch']."'";
	   $groupselct1 = mysql_query($groupselct);
	   $cntno1=mysql_num_rows($groupselct1);
	   if($cntno1==1)
   		{
		   	$groupselct12 = mysql_fetch_array($groupselct1);
			$testtempp=$groupselct12['branchname'];
	   }
	    else
	   {
		   $testtempp ="";
	   }
	   $stateselct="SELECT statename FROM state where statecode='".$myrecord['State']."'";
	   $stateselct1 = mysql_query($stateselct);
	   $cntno2=mysql_num_rows($stateselct1);
	   if($cntno2==1)
   		{
		   	$stateselct12 = mysql_fetch_array($stateselct1);
			$statetempp=$stateselct12['statename'];
	   }
	    else
	   {
		   $statetempp ="";
	   }
	    $regionselct="SELECT RegionName FROM region where RegionCode='".$myrecord['Region']."'";
	   $regionselct1 = mysql_query($regionselct);
	   $cntno3=mysql_num_rows($regionselct1);
	   if($cntno3==1)
   		{
		   	$regionselct12 = mysql_fetch_array($regionselct1);
			$regiontempp=$regionselct12['RegionName'];
	   }
	    else
	   {
		   $regiontempp ="";
	   }
	    $countryselct="SELECT countryname FROM countrymaster where countrycode='".$myrecord['Country']."'";
	   $countryselct1 = mysql_query($countryselct);
	   $cntno4=mysql_num_rows($countryselct1);
	   if($cntno4==1)
   		{
		   	$countryselct12 = mysql_fetch_array($countryselct1);
			$countrytempp=$countryselct12['countryname'];
	   }
	    else
	   {
		   $countrytempp ="";
	   }
	$table .="<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$testtempp."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$statetempp."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$regiontempp."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$countrytempp."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord[13]."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord[14]."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord[15]."</td>
	</tr>";
}
echo $table;

}
elseif($type=='Document')
{
	$query=$_SESSION['query'];
	header('Content-type: application/vnd.ms-doc');
    header("Content-Disposition: attachment; filename=DistributorMaster.doc");
    header("Pragma: no-cache");
    header("Expires: 0"); 
	
	$myquery = mysql_query($query);
	$table = "<table border='2' cellspacing='1'>
<tr border='2'  style='height:40px; bordercolor:#FF0000; font-weight:50px;'>
	<td colspan='16' align='center' border='1'  style='height:40px; bordercolor:#FF0000; font-weight:50px;'>J.K. Fenner (India) Limited</td>
	</tr>
	<tr border='2'  style='height:30px; bordercolor:#FF0000; font-weight:50px;'>
	<td colspan='16' align='center' border='1'  style='height:30px; bordercolor:#FF0000; font-weight:50px;'>Distributor Master</td>
	</tr>
	<tr border='2' style='bordercolor;#FF0000; height:30px;font-weight:20px;'>
	
		<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Distributor Code</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Distributor Name</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Contact Person</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Designation</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Address</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Pincode</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Telephone No</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Mobile No</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Email</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Branch</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>State</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Region</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Country</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>City</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Tin No</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Cst No</td>
	</tr>";
	
	while( $myrecord = mysql_fetch_array($myquery))
    {
	$table .="
	<tr  border='2' style='bordercolor;#000000; height:30px; font-weight:20px;'>
	<td align='center' style='width:200px; bordercolor;#FF0000; height:30px;'>".$myrecord[0]."</td>
	<td align='center' style=' width:400px; height=30px; bordercolor=#000000;'>".$myrecord[1]."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord[2]."</td>
	<td align='center' style='width:200px; bordercolor;#FF0000; height:30px;'>".$myrecord[3]."</td>
	<td align='center' style=' width:200px; height=30px; bordercolor=#000000;'>".$myrecord[4]."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord[5]."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord[6]."</td>
	<td align='center' style=' width:200px; height=30px; bordercolor=#000000;'>".$myrecord[7]."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord[8]."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord[9]."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord[10]."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord[11]."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord[12]."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord[13]."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord[14]."</td>
	<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord[15]."</td>
	</tr>";
}
echo $table;
}
?>
