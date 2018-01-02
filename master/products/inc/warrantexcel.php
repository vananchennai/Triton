
<?php
 session_start();
   require_once '../../../masterclass.php'; 
   $type=$_SESSION['Type'];
$news = new News(); // Create a new News Object
$newsRecordSet = $news->getNews();
if($type=="Excel")
{
	header('Content-type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=test.xls");
    header("Pragma: no-cache");
    header("Expires: 0"); 
	$myquery = mysql_query("SELECT * FROM productwarranty ");
	$table = "<table><tr><td colspan='5' align='center' style='height:30px;font-weight:20px;'>AmaraRaja Batteries</td></tr>";
	$table .="<tr><td>ProductCode</td><td>WarrantyPeriod</td><td>ProRataPeriod</td><td>ManufactureDate</td><td>ApplicableFormDate</td></tr>";
	while( $myrecord = mysql_fetch_array($myquery))
    {
	$table .="<tr><td>".$myrecord['ProductCode']."</td><td>".$myrecord['WarrantyPeriod']."</td><td>".$myrecord['ProRataPeriod']."</td><td>".$myrecord['ManufactureDate']."</td><td>".$myrecord['ApplicableFormDate']."</td></tr>";
	}
	$table .="</table>";
	echo $table;
}
else if($type=="doc")
{
header('Content-type: application/vnd.ms-doc');
    header("Content-Disposition: attachment; filename=test.doc");
    header("Pragma: no-cache");
    header("Expires: 0"); 
	$myquery = mysql_query("SELECT * FROM productwarranty ");
	$table = "<table><tr><td colspan='5' align='center' style='height:30px;font-weight:20px;'>AmaraRaja Batteries</td></tr>";
	$table .="<tr><td>ProductCode</td><td>WarrantyPeriod</td><td>ProRataPeriod</td><td>ManufactureDate</td><td>ApplicableFormDate</td></tr>";
	while( $myrecord = mysql_fetch_array($myquery))
    {
	$table .="<tr><td>".$myrecord['ProductCode']."</td><td>".$myrecord['WarrantyPeriod']."</td><td>".$myrecord['ProRataPeriod']."</td><td>".$myrecord['ManufactureDate']."</td><td>".$myrecord['ApplicableFormDate']."</td></tr>";
	}
	$table .="</table>";
	echo $table;
}
else if($type=="pdf")
{
	if($_SESSION['Type'])
	require('../../../fpdf.php');

class PDF extends FPDF
{

// Load data
function LoadData($file)
{
    // Read file lines 
    $lines = file($file);
    $data = array();
    foreach($lines as $line)
        $data[] = explode(';',trim($line));
		
    return $data;
	//echo $data;
}

// Simple table


// Better table
//function ImprovedTable($header, $data)
//{
//    // Column widths
//    $w = array(40, 35, 40);
//    // Header
//    for($i=0;$i<count($header);$i++)
//        $this->Cell($w[$i],7,$header[$i],1,0,'C');
//    $this->Ln();
//    // Data
//    foreach($data as $row)
//    {
//        $this->Cell($w[0],6,$row[0],'LR');
//       // $this->Cell($w[1],6,$row[1],'LR');
//        //$this->Cell($w[],6,$row[2],'LR');
//       $this->Ln();
//    }
//    // Closing line
//    $this->Cell(array_sum($w),0,'','T');
//}

// Colored table
function FancyTable($header, $data)
{
	$count=count($data);
	//echo $count;
    // Colors, line width and bold font
    $this->SetFillColor(255,0,0);
    $this->SetTextColor(0);
    $this->SetDrawColor(128,0,0);
    $this->SetLineWidth(.3);
    $this->SetFont('','B');
	$this->SetFontSize(9);
	
    // Header
    $w = array(60, 60, 60);
    for($i=0;$i<count($header);$i++)
    $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
    $this->Ln();
    // Color and font restoration
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('','','S');
	$this->SetFontSize(9);
    // Data
    $fill = false;
	$i=0;
    foreach($data as $row)
    {
		if($i<30)
		{
			$this->SetAutoPageBreak('auto',30);
        $this->Cell($w[0],7,$row[0],'LR',0,'L',$fill);
        $this->Cell($w[1],7,$row[1],'LR',0,'L',$fill);
        $this->Cell($w[2],7,$row[2],'LR',0,'L',$fill);
      
        $this->Ln();
        $fill = !$fill;
		$i++;
		$count=$count--;
		}
		else 
		{
			if($count!=0)
			{
		   $this->SetDrawColor(128,0,0);
    $this->SetLineWidth(.3);	
	 $this->SetFillColor(255,0,0);
    //$this->SetTextColor(255);
    $this->SetDrawColor(128,0,0);
    $this->SetLineWidth(.3);
    $this->SetFont('','B');
	
    // Header
      for($i=0;$i<count($header);$i++)
    $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
    $this->Ln();
    // Color and font restoration
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('','','S');
	$this->SetFontSize(9);
			$i=0;
			}
		}
    }
    // Closing line
    $this->Cell(array_sum($w),0,'','T');
}




// Page header
function Header()
{
    // Logo
   // $this->Image('logo.png',10,6,30);
    // Arial bold 15
	$this->Ln(9);
    $this->SetFont('Arial','B',15);
    // Move to the right
    $this->Cell(50);
    // Title
    $this->Cell(90,10,'AmaraRaja Batteries',0,0,'C');
    // Line break
    $this->Ln(24);
}

// Page footer
function Footer()
{
	$this->Line(0,100,0,100);
	$this->SetLineWidth(.03);
	$this->SetDrawColor(128,0,0);
    // Position at 1.5 cm from bottom
    $this->SetY(-19);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
}
}
$pdf = new PDF();
// Column headings
$header = array('ProductSegmentCode', 'ProductSegment', 'ProductGroup');
// Data loading
$myFile = "testFile.txt";
	$fh = fopen($myFile, 'w') or die("can't open file");
	$stringData =NULL;
	$myquery = mysql_query("SELECT * FROM productsegmentmaster ");
	while( $myrecord = mysql_fetch_array($myquery))
    {
		$stringData =$myrecord['ProductSegmentCode']."\t ;".$myrecord['ProductSegment']."\t ;".$myrecord['ProductGroup'].";\n";
		fwrite($fh, $stringData);
			
	}
	
	fclose($fh);


  
//

//
$data = $pdf->LoadData('testFile.txt');
//for($a=0;$a<count($data);$a++)
//{
//	echo $data;
//}
$pdf->SetFont('Arial','',14);
////$myquery = mysql_query("SELECT * FROM productsegmentmaster ");
////$mydata = array($myquery);
//
//$pdf->AddPage();
//$pdf->ImprovedTable($header,$data);
$pdf->AddPage();
$pdf->FancyTable($header,$data);
$pdf->Output();
// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

$pdf->Output();
unlink('testFile.txt');
}
	?>