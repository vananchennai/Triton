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
    $width = array(100,60);
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
		// $this->Cell($width[2],6,$row[2],'LR',0,'L',$fill);
		// $this->Cell($width[3],6,$row[3],'LR',0,'L',$fill);

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
    $this->Cell(150,10,'J.K. Fenner (India) Limited',1,0,'C');
    // Line break
    $this->Ln(10);
	 $this->Cell(50);
	
    // Title
    $this->Cell(150,10,'Product Master',0,0,'C');
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
$header = array('Product Code / ','UOM');
$header2 = array('Product Description','');
$pdf->SetFont('Arial','',7);
$pdf->AddPage();
$pdf->FancyTable($header,$data,$header2);
$pdf->Output('ProductMaster.pdf', 'D');
// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

$pdf->Output('ProductMaster.pdf', 'D');
unlink('testFile.txt');
}

elseif($type == 'TallyExcel'){
	$query=$_SESSION['query'];
	$result = mysql_query($query);
	require('../../PHPExcel/Classes/PHPExcel.php');

	$objPHPExcel = new PHPExcel();

	// Set the active Excel worksheet to sheet 0 
	$objPHPExcel->getActiveSheet()->setTitle('Product Mapping');
	$objPHPExcel->setActiveSheetIndex(0);  
	// Initialise the Excel row number 
	$rowCount = 1;  

	//start of printing column names as names of MySQL fields  
	$column = 'A';
	$column1 = 'B';
	$column2= 'C';
	$column3 ='D';
	 $objPHPExcel->getActiveSheet()->setCellValue($column.$rowCount, 'Fenner Product Code');
	 $objPHPExcel->getActiveSheet()->setCellValue($column1.$rowCount, 'Fenner Product Description');
	 $objPHPExcel->getActiveSheet()->setCellValue($column3.$rowCount, 'Tally Product Description');
	 $objPHPExcel->getActiveSheet()->setCellValue($column2.$rowCount, 'Tally Product Code');
	$rowCount = 2;  
	while($row = mysql_fetch_row($result))  
	{  
	   // $column = 'A';
	    $objPHPExcel->getActiveSheet()->setCellValue($column.$rowCount, $row[0]);
	    $objPHPExcel->getActiveSheet()->setCellValue($column1.$rowCount, $row[1]);
	    $objPHPExcel->getActiveSheet()->setCellValue($column2.$rowCount, '');
	    $objPHPExcel->getActiveSheet()->setCellValue($column3.$rowCount, '');
	    $rowCount++;
	} 

	// Redirect output to a client’s web browser (Excel5) 
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename=ProductMapping.xls');
	header("Pragma: no-cache");
	header("Expires: 0"); 
	ob_end_clean();
	ob_start();
	$objWriter->save('php://output');
	exit;
}
elseif($type=='Excel')
{
	$query=$_SESSION['query'];
	header('Content-type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=ProductMaster.xls");
    header("Pragma: no-cache");
    header("Expires: 0"); 
	$myquery = mysql_query($query);
	$table = "<table border='2' cellspacing='1'>
	<tr border='2'  style='height:40px; bordercolor:#FF0000; font-weight:50px;'>
	<td colspan='2' align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>J.K. Fenner (India) Limited</td>
	</tr>
	<tr border='2'  style='height:30px; bordercolor:#FF0000; font-weight:50px;'>
	<td colspan='2' align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Product Master</td>
	</tr>
	<tr border='2' style='bordercolor;#FF0000; height:30px;font-weight:20px;'>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:40px;font-weight:bold;'>Product Code/Product Description</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:40px;font-weight:bold;'>UOM </td>
	</tr>";
	
	while( $myrecord = mysql_fetch_array($myquery))
    {
	$table .="
	<tr  border='2' style='bordercolor;#000000; height:30px; font-weight:20px;'>
	<td align='center' style='width:200px; bordercolor;#FF0000; height:30px;'>".$myrecord[0]."</td>
	<td align='center' style=' width:400px; height=30px; bordercolor=#000000;'>".$myrecord[3]."</td>";
	/* $groupgg="SELECT * FROM producttypemaster where ProductTypeCode='".$myrecord['ProductType']."'";
	   $groupgg1 = mysql_query($groupgg);
	 $cntnog=mysql_num_rows($groupgg1);
	 if($cntnog==1)
	  {
		   	$groupgg2 = mysql_fetch_array($groupgg1);
			$testtemp=$groupgg2['ProductTypeName'];
	   }
	   else
	   {
		   $testtemp ="";
	   } */
	
	// $table .="<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord[2]."</td>
	
	
	// <td align='center' style='width:200px; bordercolor;#FF0000; height:30px;'>".$myrecord[3]."</td></tr>";
	/*$group="SELECT * FROM productuom where productuomcode='".$myrecord['UOM']."'";
	   $gro = mysql_query($group);
	 $cnt=mysql_num_rows($gro);
	 if($cnt==1)
	  {
		   	$g2 = mysql_fetch_array($gro);
			$test=$g2['productuom'];
	   }
	   else
	   {
		   $test ="";
	   }*/
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
	<td colspan='2' align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>J.K. Fenner (India) Limited</td>
	</tr>
	<tr border='2'  style='height:30px; bordercolor:#FF0000; font-weight:50px;'>
	<td colspan='2' align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Product Master</td>
	</tr>
	<tr border='2' style='bordercolor;#FF0000; height:30px;font-weight:20px;'>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Product Code/Product Description</td>
<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>UOM</td>
	</tr>";
	
	while( $myrecord = mysql_fetch_array($myquery))
    {
	$table .="
	<tr  border='2' style='bordercolor;#000000; height:30px; font-weight:20px;'>
	<td align='center' style='width:200px; bordercolor;#FF0000; height:30px;'>".$myrecord[0]."</td>
	<td align='center' style=' width:400px; height=30px; bordercolor=#000000;'>".$myrecord[3]."</td>";
	 /* $groupgg="SELECT * FROM productgroupmaster where ProductCode='".$myrecord['ProductGroupCode']."'";
	   $groupgg1 = mysql_query($groupgg);
	 $cntnog=mysql_num_rows($groupgg1);
	 if($cntnog==1)
	  {
		   	$groupgg2 = mysql_fetch_array($groupgg1);
			$testtemp=$groupgg2['ProductGroupName'];
	   }
	   else
	   {
		   $testtemp ="";
	   } 
 */	
	// $table .="<td align='center' style='width:200px; height=30px; bordercolor=#000000;'>".$myrecord[2]."</td>
	
	
	// <td align='center' style='width:200px; bordercolor;#FF0000; height:30px;'>".$myrecord[3]."</td></tr>";
	/*$group="SELECT * FROM productuom where productuomcode='".$myrecord['UOM']."'";
	   $gro = mysql_query($group);
	 $cnt=mysql_num_rows($gro);
	 if($cnt==1)
	  {
		   	$g2 = mysql_fetch_array($gro);
			$test=$g2['productuom'];
	   }
	   else
	   {
		   $test ="";
	   }*/
}
echo $table;
}
?>
