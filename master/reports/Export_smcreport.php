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
            $tarray[$i]=0;			 
			$tarrayval[$i]=0;
			$table .= '"'. $headarray[$i] .'",';        	
			$i++;
		}
		$table .= "\n";
		$j = 0;
		$s = 0;
		$t = 0;
		$prevvalue;
		$stavalue;
		$stsegvalue;
	    //$qry_exec = mysql_query($controls);
		while ($record = mysql_fetch_array($qry_exec))									
		//for($ik=$startpoint;$ik<$limit+$startpoint;$ik++)
		{
		
			if($record[$headarray[0]]!=NULL)
			{
			
			
				  
			   if($s==0)
				{
					$stavalue = $record[$headarray[1]];
				}
				else if($s>0)
		          {
				  if($stavalue != $record[$headarray[1]] or $prevvalue != $record[$headarray[0]])
					{
					$table.=','.$stavalue.',,';
					
					for($b=3;$b<sizeof($tarray);$b++)
					 {
					 $table.='"'.$tarray[$b].'",';	
					 
					 $tarray[$b]=0;
					 
					 }
					 $table.="\n ";	
					 $stavalue=$record[$headarray[1]];
					 
					}
				  }
				
				if($j==0)
				{
					$prevvalue = $record[$headarray[0]];
				}
				else if($j>0)
				{
					if($prevvalue != $record[$headarray[0]])
					{
						$table.=''. $prevvalue .',,,';// Total Value line start
						for($a=3;$a<sizeof($totalarray);$a++)
						{
							$table.='"'. $totalarray[$a] .'",';															
							$totalarray[$a]=0;
						}
						 $table.="\n ";			 
						 $prevvalue=$record[$headarray[0]];
					}
				}
				$table.=",";
				for($i=2;$i<sizeof($headarray);$i++)
				{
				
					if ( $i>2)
					{
						$totalarray[$i] = $totalarray[$i] +  $record[$headarray[$i]];
						$tarray[$i] = $tarray[$i] +  $record[$headarray[$i]];
						//$tarrayval[$i] = $tarrayval[$i] +  $record[$headarray[$i]];
					}

					$table.=',"'. $record[$headarray[$i]].'"';
				}
				$table.="\n";
				$t++;
				$s++;
				$j++;		
			}
		}
		/* $table.=',,"'.$stsegvalue.'",';
						 for($b=4;$b<sizeof($tarrayval);$b++)
                                    {
									$table.=',"'. $tarrayval[$b].'"';	
									$tarrayval[$b]=0;
									}
						    $table.="\n ";
 */
		$table.=',"'. $stavalue.'",';
		
				         for($b=3;$b<sizeof($tarray);$b++)
                                    {
									$table.=',"'. $tarray[$b].'"';	
									$tarray[$b]=0;
									}
						    $table.="\n ";
		$table.=''. $prevvalue .',,';	
							
							 for($a=3;$a<sizeof($totalarray);$a++)
                                    {
									$table.=',"'.$totalarray[$a].'"';	
							        $totalarray[$a]=0;
									}
										
							
						}
						
				
	
	

// Download the file

$filename = "SMCReport-".date('Y-m-d').".csv";
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$filename);

echo $table;
exit;

?>

