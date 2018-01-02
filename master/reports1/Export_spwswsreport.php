<?php
include '../../functions.php';
sec_session_start();

require_once '../../masterclass.php';
$news = new News();
//include("inc/common_functions.php");


$controls = $_SESSION['form_controls'];
// Database Connection


// Fetch Record from Database

$output = "";
$table = ""; // Enter Your Table Name 
$sql = mysql_query($controls);
if (!$sql) {
    echo 'Could not run query: ' . mysql_error();
    exit;
}
 $columns_total = mysql_num_fields($sql);

// Get The Field Name

for ($i = 0; $i < $columns_total; $i++) {
$heading = mysql_field_name($sql, $i);
$output .= '"'.$heading.'",';
}
$output .="\n"; 


	
									
// Get Records from the table

 while ($row = mysql_fetch_array($sql)) {
for ($i = 0; $i < $columns_total; $i++) {
$output .='"'.$row["$i"].'",';
}
$output .="\n";
} 
  
  
 
		/* if($record[$ik][$headarray[0]]!=NULL)
		{
			if($j==0)
			{
				$prevvalue = $record[$ik][$headarray[0]];
				
			}
			else if($j>0)
			{
				if($prevvalue != $record[$ik][$headarray[0]])
				{  
				 $output .='"'.$prevvalue.'",';
				for($a=2;$a<sizeof($totalarray);$a++)
				{
				$output .='"'.$totalarray[$a].'",';
				$totalarray[$a]=0;
				}
				$prevvalue=$record[$ik][$headarray[0]];
				}
			}
			for($i=1;$i<sizeof($headarray);$i++)
		    {
			if ( $i>1)
			{
				$totalarray[$i] = $totalarray[$i] +  $record[$ik][$headarray[$i]];
			}
                $output .='"'.$record[$ik][$headarray[$i]].'",';
			}
			$j++;		
										}
										$output .="\n";
										
									} */
  



 
// Download the file

$filename = "SupProductwiseStockistwiseSalesReport-".date('Y-m-d').".csv";
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$filename);

echo $output;
exit;

?>

