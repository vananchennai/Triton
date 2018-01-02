<?php
include '../../functions.php';
sec_session_start();
require_once '../../masterclass.php';
//include("inc/common_functions.php");
$news = new News();

$controls =  $_SESSION['form_controls'];

	if(!empty($controls))
	{
		$i = 0;
		$table = ""; 
		$qry_exec = mysql_query($controls);                                   
		while ($i < mysql_num_fields($qry_exec))
		{
			$fld = mysql_fetch_field($qry_exec, $i);
			$headarray[$i]=$fld->name;									
			$totalarray[$i]=0;							
			$table .= '"'. $headarray[$i] .'",';        	
			$i++;
		}
		$table .= "\n";
		
		$prevvalue;
	    //$qry_exec = mysql_query($controls);
		while ($record = mysql_fetch_array($qry_exec))									
		//for($ik=$startpoint;$ik<$limit+$startpoint;$ik++)
		{
			if($record[$headarray[0]]!=NULL)
			{
				
				
				for($i=0;$i<sizeof($headarray);$i++)
				{
					if ( $i>0)
					{
						$totalarray[$i] = $totalarray[$i] +  $record[$headarray[$i]];
					}

					$table.='"'. $record[$headarray[$i]] .'",';
				}
				$table.="\n";
						
			}
		}
						
				
	}
	

// Download the file

$filename = "Month Wise Synch Status - Transaction Download-".date('Y-m-d').".csv";
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$filename);

echo $table;
exit;

?>

