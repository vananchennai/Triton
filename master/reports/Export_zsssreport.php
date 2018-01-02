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
		$j = 0;
		$s = 0;
		$prevvalue;
		$tarray;
		$rarray;
		$gtotal;
		$stavalue;
	    //$qry_exec = mysql_query($controls);
		while ($record = mysql_fetch_array($qry_exec))									
		//for($ik=$startpoint;$ik<$limit+$startpoint;$ik++)
		{
			if($record[$headarray[0]]!=NULL)
			{
				if($j==0)
				{
					$stavalue = $record[$headarray[1]];
				}
				else if($j>0)
				{
					if($stavalue != $record[$headarray[1]])
					{
						
						$table.=',"'. $stavalue .'",,';// Total Value line start
						$table.='"'. $tarray .'",,';
						$table.="\n ";
						$rarray = $rarray+$tarray;
						$tarray=0;
						$stavalue=$record[$headarray[1]];
					}
				}
				if($s==0)
				{
					$prevvalue = $record[$headarray[0]];
				}
				else if($s>0)
				{
					if($prevvalue != $record[$headarray[0]])
					{
						$table.=''. $prevvalue .',,,';// Total Value line start
						$table.='"'. $rarray .'",,';
						$table.="\n ";
						$gtotal = $gtotal + $rarray;
						$rarray = 0;
						$prevvalue=$record[$headarray[0]];
					}
				}
				$table.=',';
				for($i=2;$i<sizeof($headarray);$i++)
				{
					if ( $i>2)
					{
						$tarray= $tarray +  $record[$headarray[$i]];
					}

					$table.=',"'. $record[$headarray[$i]] .'"';
				}
				$table.="\n";
				$j++;
				$s++;		
			}
		}
		$table.=',"'. $stavalue .'",';
		$table.=',"'. $tarray.'"';	
		$table.="\n";
		$rarray = $rarray+$tarray;
        $tarray=0;
		$table.='"'. $prevvalue .'",';
		$table.=',,"'. $rarray.'"';
		$table.="\n";
		$gtotal = $gtotal + $rarray;
        $rarray=0;
		$table.='"Grand Total",';
		$table.=',,"'. $gtotal.'"';
	}
	

// Download the file

$filename = "ZSWiseReport-".date('Y-m-d').".csv";
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$filename);

echo $table;
exit;

?>

