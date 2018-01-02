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
    $this->SetLineWidth(.6);
    //$this->SetFont('','B');
	$this->SetFont('Arial','',8);
    // Header
    $width = array(25,68,20,22,25,20);
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
    $this->SetFont('Arial','B',15);
    // Move to the right
    $this->Cell(50);
	
    // Title
    $this->Cell(90,10,'J.K. Fenner (India) Limited',1,0,'C');
    // Line break
    $this->Ln(10);
	 $this->Cell(50);
	
    // Title
    $this->Cell(90,10,'DSR Code Mapping Master',0,0,'C');
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
$header = array('Distributor Code', 'Retailer Name','Retailer Code','DSR Code','DSR Name','DSR Location');
$pdf->SetFont('Arial','',12);
$pdf->AddPage();
$pdf->FancyTable($header,$data);
$pdf->Output('DSRMapping.pdf', 'D');
// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',8);

$pdf->Output('DSRMapping.pdf', 'D');
unlink('testFile.txt');
}

elseif($type == 'TallyExcel'){
	$query=$_SESSION['query'];
	$result = mysql_query($query);
	require('../../PHPExcel/Classes/PHPExcel.php');

	$objPHPExcel = new PHPExcel();

	// Set the active Excel worksheet to sheet 0 
	$objPHPExcel->getActiveSheet()->setTitle('DSR Retailer Mapping');
	$objPHPExcel->setActiveSheetIndex(0);  
	// Initialise the Excel row number 
	$rowCount = 1;  

	//start of printing column names as names of MySQL fields  
	$column = 'A';
	$column1 = 'B';
	$column2= 'C';
	$column3 ='D';
	$column4 ='E';
	$column5 ='F';
	
	 $objPHPExcel->getActiveSheet()->setCellValue($column.$rowCount, 'Distributor Code');
	 $objPHPExcel->getActiveSheet()->setCellValue($column1.$rowCount, 'Retailer Name');
	 $objPHPExcel->getActiveSheet()->setCellValue($column2.$rowCount, 'Retailer Code');
	 $objPHPExcel->getActiveSheet()->setCellValue($column3.$rowCount, 'DSR Code');
	 $objPHPExcel->getActiveSheet()->setCellValue($column4.$rowCount, 'DSR Name');
	 $objPHPExcel->getActiveSheet()->setCellValue($column5.$rowCount, 'DSR Location');
	$rowCount = 2;  
	while($row = mysql_fetch_row($result))  
	{  
	   // $column = 'A';
	    $objPHPExcel->getActiveSheet()->setCellValue($column.$rowCount, $row[0]);
	    $objPHPExcel->getActiveSheet()->setCellValue($column1.$rowCount, $row[1]);
	    $objPHPExcel->getActiveSheet()->setCellValue($column2.$rowCount, $row[2]);
	    $objPHPExcel->getActiveSheet()->setCellValue($column3.$rowCount, $row[3]);
		$objPHPExcel->getActiveSheet()->setCellValue($column4.$rowCount, $row[4]);
	    $objPHPExcel->getActiveSheet()->setCellValue($column5.$rowCount, $row[5]);
	    $rowCount++;
	} 

	// Redirect output to a client’s web browser (Excel5) 
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename=DSRRetailerMapping.xls');
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
	$result = mysql_query($query);
	require('../../PHPExcel/Classes/PHPExcel.php');

	$objPHPExcel = new PHPExcel();

	// Set the active Excel worksheet to sheet 0 
	$objPHPExcel->getActiveSheet()->setTitle('DSR Retailer Mapping');
	$objPHPExcel->setActiveSheetIndex(0);  
	// Initialise the Excel row number 
	$rowCount = 1;  

	//start of printing column names as names of MySQL fields  
	$column = 'A';
	$column1 = 'B';
	$column2= 'C';
	$column3 ='D';
	$column4 ='E';
	$column5 ='F';
	
	 $objPHPExcel->getActiveSheet()->setCellValue($column.$rowCount, 'Distributor Code');
	 $objPHPExcel->getActiveSheet()->setCellValue($column1.$rowCount, 'Retailer Name');
	 $objPHPExcel->getActiveSheet()->setCellValue($column2.$rowCount, 'Retailer Code');
	 $objPHPExcel->getActiveSheet()->setCellValue($column3.$rowCount, 'DSR Code');
	 $objPHPExcel->getActiveSheet()->setCellValue($column4.$rowCount, 'DSR Name');
	 $objPHPExcel->getActiveSheet()->setCellValue($column5.$rowCount, 'DSR Location');
	$rowCount = 2;  
	while($row = mysql_fetch_row($result))  
	{  
	   // $column = 'A';
	    $objPHPExcel->getActiveSheet()->setCellValue($column.$rowCount, $row[0]);
	    $objPHPExcel->getActiveSheet()->setCellValue($column1.$rowCount, $row[1]);
	    $objPHPExcel->getActiveSheet()->setCellValue($column2.$rowCount, $row[2]);
	    $objPHPExcel->getActiveSheet()->setCellValue($column3.$rowCount, $row[3]);
		$objPHPExcel->getActiveSheet()->setCellValue($column4.$rowCount, $row[4]);
	    $objPHPExcel->getActiveSheet()->setCellValue($column5.$rowCount, $row[5]);
	    $rowCount++;
	} 

	// Redirect output to a client’s web browser (Excel5) 
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename=DSRRetailerMappingAll.xls');
	header("Pragma: no-cache");
	header("Expires: 0"); 
	ob_end_clean();
	ob_start();
	$objWriter->save('php://output');
	exit;
}
elseif($type=='Document')
{
	$query=$_SESSION['query'];
	header('Content-type: application/vnd.ms-doc');
    header("Content-Disposition: attachment; filename=DSRRetailerMapping.doc");
    header("Pragma: no-cache");
    header("Expires: 0"); 
	$myquery = mysql_query($query);
	$table = "<table border='2' cellspacing='1'>
	<tr border='6'  style='height:40px; bordercolor:#FF0000; font-weight:50px;'>
	<td colspan='6' align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>J.K. Fenner (India) Limited</td></tr>
	<tr border='6'  style='height:30px; bordercolor:#FF0000; font-weight:50px;'>
	<td colspan='6' align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>DSR Retailer Mapping Master</td>
	</tr>
	<tr border='3' style='bordercolor;#FF0000; height:30px;font-weight:20px;'>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Distributor Code</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Retailer Name</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>Retailer Code</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>DSR Code</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>DSR Name</td>
	<td align='center' bgcolor='#CCCCCC' style='width:150px; bordercolor;#FF0000; height:30px;font-weight:bold;'>DSR Location</td>";
	
	
	while( $myrecord = mysql_fetch_array($myquery))
    {
	$table .="</tr><tr  border='2' style='bordercolor;#000000; height:30px; font-weight:20px;'>
	<td align='center' style='width:200px; bordercolor;#FF0000; height:30px;font-weight:20px;'>".$myrecord[0]."</td>
	<td align='center' style=' width:200px; height=20px; bordercolor=#000000;'>".$myrecord[1]."</td>
	<td align='center' style=' width:200px; height=20px; bordercolor=#000000;'>".$myrecord[2]."</td>
	<td align='center' style=' width:200px; height=20px; bordercolor=#000000;'>".$myrecord[3]."</td>
	<td align='center' style=' width:200px; height=20px; bordercolor=#000000;'>".$myrecord[4]."</td>
	<td align='center' style=' width:200px; height=20px; bordercolor=#000000;'>".$myrecord[5]."</td>
	</tr>";
}
echo $table;
}
?>
