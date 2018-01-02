<?php
include '../../functions.php';
sec_session_start();
require_once '../../masterclass.php';
$news = new News();  
require('../../fpdfa3.php');
 // Create a new News Object

/*class PDF extends FPDF
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
    $this->SetFont('Arial','B',8 );
    // Header
    $width = array(30,40,30,40,65,40,30);
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
    $this->Cell(90);
	
    // Title
    $this->Cell(90,10,'Amara Raja Batteries',1,0,'C');
    // Line break
    $this->Ln(10);
	 $this->Cell(90);
	
    // Title
    $this->Cell(90,10,'Tertiary Sales',0,0,'C');
	$this->Ln(10);
}
// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',7);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
}
}

$type=$_SESSION['type'];
if($type=='PDF')
{
$pdf = new PDF();
$data = $pdf->LoadData('testFile.txt');
$header = array( 'TertiarySales','Battery', 'DOS','Product' ,'Product' ,'Cust.','Franchisee');
$header2 = array('EntryDate','SlNo','','Code','Desc.','Name','Name');
$pdf->SetFont('Arial','',7);
$pdf->AddPage();
$pdf->FancyTable($header,$data,$header2);
$pdf->Output('TertiarySales.pdf', 'D');
// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

$pdf->Output('TertiarySales.pdf', 'D');
unlink('testFile.txt');
}*/
$type=$_SESSION['type'];
if($type == 'Excel' || $type == 'Document')
{
	$query=$_SESSION['query'];
	$myquery = mysql_query($query);
	$table = "<table border='2' cellspacing='1'>

	<tr style='white-space:nowrap;'>
	
	<td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000; height:30px;font-weight:bold;'>Category</td>
	<td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000; height:30px;font-weight:bold;'>Tertiary Sales Entry Date</td>
	<td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000; height:30px;font-weight:bold;'>Battery Sl No.</td>
	<td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000; height:30px;font-weight:bold;'>Date Of Sale</td>
	<td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000; height:30px;font-weight:bold;'>Sales Invoice No.</td>
	<td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000; height:30px;font-weight:bold;'>Battery Status</td>
	<td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000; height:30px;font-weight:bold;'>Old Product Code</td>
	<td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000; height:30px;font-weight:bold;'>Old Product Description</td>
	<td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000; height:30px;font-weight:bold;'>Old Battery Sl No.</td>
	<td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000; height:30px;font-weight:bold;'>Manufacturing Date</td>
	<td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000; height:30px;font-weight:bold;'>Product Code</td>
	<td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000; height:30px;font-weight:bold;'>Product Description</td>
	<td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000; height:30px;font-weight:bold;'>Customer Name</td>
	<td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000; height:30px;font-weight:bold;'>Customer Address</td>
	<td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000; height:30px;font-weight:bold;'>City</td>
	<td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000; height:30px;font-weight:bold;'>Customer Phone No.</td>
	<td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000; height:30px;font-weight:bold;'>Retailer Code</td>
	<td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000; height:30px;font-weight:bold;'>Franchisee Code</td>
	<td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000; height:30px;font-weight:bold;'>Vehicle or InverterModel</td>
	<td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000; height:30px;font-weight:bold;'>Vehicle or InverterMak</td>
	<td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000; height:30px;font-weight:bold;'>Vehicle Segment</td>
	<td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000; height:30px;font-weight:bold;'>Enginetype</td>
	<td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000; height:30px;font-weight:bold;'>Vehicle No.</td>
	<td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000; height:30px;font-weight:bold;'>OEM Name</td>
	</tr>";
	
	while( $myrecord = mysql_fetch_array($myquery))
    {
	$table .="
	<tr  style='white-space:nowrap;'>
	<td style=' bordercolor;#FF0000; height:30px;'>".$myrecord['Category']."</td>
	<td style=' bordercolor;#FF0000; height:30px;'>".$myrecord['TertiarySalesEntryDate']."</td>
	<td style=' bordercolor;#FF0000; height:30px;'>".'#'.$myrecord['BatterySlNo']."</td>
	<td style=' bordercolor;#FF0000; height:30px;'>".$myrecord['DateofSale']."</td>
	<td style=' bordercolor;#FF0000; height:30px;'>".$myrecord['salesinvoiceno']."</td>
	<td style=' bordercolor;#FF0000; height:30px;'>".$myrecord['batterystatus']."</td>
	<td style=' bordercolor;#FF0000; height:30px;'>".$myrecord['oldProductCode']."</td>";
	
	$oldprodescselct="select ProductDescription from productmaster where ProductCode='".$myrecord['oldProductCode']."'";
	   $oldprodescselct1 = mysql_query($oldprodescselct);
	   $oldprodesccntno1=mysql_num_rows($oldprodescselct1);
	   if($oldprodesccntno1==1)
   		{
		   	$oldprodescselct12 = mysql_fetch_array($oldprodescselct1);
			$oldprodesc=$oldprodescselct12['ProductDescription'];
	   }
	    else
	   {
		   $oldprodesc ="";
	   }
	   
	$Productgroupselct="SELECT ProductDescription FROM productmaster where ProductCode='".$myrecord['ProductCode']."'";
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

/*	$RetailerNameselct="SELECT RetailerName FROM retailermaster where RetailerCode='".$myrecord['RetailerName']."'";
	   $RetailerNameselct1 = mysql_query($RetailerNameselct);
	   $RetailerNamecntno1=mysql_num_rows($RetailerNameselct1);
	   if($RetailerNamecntno1==1)
   		{
		   	$RetailerNameselct12 = mysql_fetch_array($RetailerNameselct1);
			$RetailerName=$RetailerNameselct12['RetailerName'];
	   }
	    else
	   {
		   $RetailerName ="";
	   }	   

	$FranchiseeNameselct="SELECT Franchisename FROM franchisemaster where Franchisecode='".$myrecord['FranchiseeName']."'";
	   $FranchiseeNameselct1 = mysql_query($FranchiseeNameselct);
	   $FranchiseeNamecntno1=mysql_num_rows($FranchiseeNameselct1);
	   if($FranchiseeNamecntno1==1)
   		{
		   	$FranchiseeNameselct12 = mysql_fetch_array($FranchiseeNameselct1);
			$FranchiseeName=$FranchiseeNameselct12['Franchisename'];
	   }
	    else
	   {
		   $FranchiseeName ="";
	   }*/
	  
	$modelnameselct="SELECT modelname FROM vehiclemodel where modelcode='".$myrecord['VehicleorInverterModel']."'";
	   $modelnameselct1 = mysql_query($modelnameselct);
	   $modelnamecntno1=mysql_num_rows($modelnameselct1);
	   if($modelnamecntno1==1)
   		{
		   	$modelnameselct12 = mysql_fetch_array($modelnameselct1);
			$modelname=$modelnameselct12['modelname'];
	   }
	    else
	   {
		   $modelname ="";
	   }	 
	
	$MakeNameselct="SELECT MakeName FROM vehiclemakemaster where MakeNo='".$myrecord['VehicleorInverterMake']."'";
	   $MakeNameselct1 = mysql_query($MakeNameselct);
	   $MakeNamecntno1=mysql_num_rows($MakeNameselct1);
	   if($MakeNamecntno1==1)
   		{
		   	$MakeNameselct12 = mysql_fetch_array($MakeNameselct1);
			$MakeName=$MakeNameselct12['MakeName'];
	   }
	    else
	   {
		   $MakeName ="";
	   }
	   
	$segmentnameselct="SELECT segmentname FROM vehiclesegmentmaster where segmentcode='".$myrecord['VehicleSegment']."'";
	   $segmentnameselct1 = mysql_query($segmentnameselct);
	   $segmentnamecntno1=mysql_num_rows($segmentnameselct1);
	   if($segmentnamecntno1==1)
   		{
		   	$segmentnameselct12 = mysql_fetch_array($segmentnameselct1);
			$segmentname=$segmentnameselct12['segmentname'];
	   }
	    else
	   {
		   $segmentname ="";
	   }
	   
	   $oemnameselct="SELECT oemname FROM oemmaster where oemcode='".$myrecord['oemname']."'";
	   $oemnameselct1 = mysql_query($oemnameselct);
	   $oemnamecntno1=mysql_num_rows($oemnameselct1);
	   if($oemnamecntno1==1)
   		{
		   	$oemnameselct12 = mysql_fetch_array($oemnameselct1);
			$oemname=$oemnameselct12['oemname'];
	   }
	    else
	   {
		   $oemname ="";
	   }
	   if(empty($myrecord['oldbatteryno']))
	   {
		 $oldbatteryno= ''; 
	   }
	   else
	   {
		   $oldbatteryno='#'.$myrecord['oldbatteryno'];
	   }
	   
	$table .="
	<td style=' bordercolor;#FF0000; height:30px;'>".$oldprodesc."</td>
	<td style=' bordercolor;#FF0000; height:30px;'>".$oldbatteryno."</td>
	<td style=' bordercolor;#FF0000; height:30px;'>".$myrecord['ManufacturingDate']."</td>
	<td style=' bordercolor;#FF0000; height:30px;'>".$myrecord['ProductCode']."</td>
	<td style=' bordercolor;#FF0000; height:30px;'>".$Producttesttempp."</td>
	<td style=' bordercolor;#FF0000; height:30px;'>".$myrecord['CustomerName']."</td>	
	<td style=' bordercolor;#FF0000; height:30px;'>".$myrecord['CustomerAddress']."</td>
	<td style=' bordercolor;#FF0000; height:30px;'>".$myrecord['City']."</td>
	<td style=' bordercolor;#FF0000; height:30px;'>".$myrecord['CustomerPhoneNo']."</td>	
	<td style=' bordercolor;#FF0000; height:30px;'>".$myrecord['RetailerName']."</td>	
	<td style=' bordercolor;#FF0000; height:30px;'>".$myrecord['FranchiseeName']."</td>
	<td style=' bordercolor;#FF0000; height:30px;'>".$modelname."</td>	
	<td style=' bordercolor;#FF0000; height:30px;'>".$MakeName."</td>		
	<td style=' bordercolor;#FF0000; height:30px;'>".$segmentname."</td>
	<td style=' bordercolor;#FF0000; height:30px;'>".$myrecord['Enginetype']."</td>
	<td style=' bordercolor;#FF0000; height:30px;'>".$myrecord['VehicleNo']."</td>
	<td style=' bordercolor;#FF0000; height:30px;'>".$oemname."</td>
	</tr>";
}
}

   if ($type == 'Excel') {
        header('Content-type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=TertiarySales.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $table;
        exit();
    } else if ($type == 'Document') {
        header('Content-type: application/vnd.ms-doc');
        header("Content-Disposition: attachment; filename=TertiarySales.doc");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $table;
        exit();
    }
?>
