<?php
include '../../functions.php';
sec_session_start();
require_once '../../masterclass.php';
$news = new News();  
//require('../../fpdfa3.php');
 // Create a new News Object
$search1 = $_SESSION['query'];

global $c1,$c1prd,$c1date,$no1,$date1,$no2,$date2,$no3,$date3,$serialqry1;
		$c1=$c1prd=$c1date=$no1=$date1=$no2=$date2=$no3=$date3="";
			$result="SELECT * FROM serialnumbermaster where BatterySlNo ='".$search1."'";
		//echo $result;
		$row2 = mysql_query($result) ;
		$numrows2 = mysql_num_rows($row2);
		if($numrows2>0)
		{
			$rows2 = mysql_fetch_array($row2);
			$batterystatus=$rows2['batterystatus'];
			$oldbattery=$rows2['oldbatteryno'];
			//echo $batterystatus;
			if($batterystatus=="REPLACE")
				{
					$serialqry1="SELECT * FROM serialnumbermaster where BatterySlNo ='".$oldbattery."'";
					
					//$qry1="SELECT BatterySlNo,batterystatus FROM serialnumbermaster where batterystatus ='".$batterystatus."' and oldbatteryno ='".$oldbattery."'";
					//echo $qry;
					$sql25 = mysql_query($serialqry1) ;
					$myrows4= mysql_num_rows($sql25);
					if($myrows4>0)
					{
					$rows45 = mysql_fetch_array($sql25);
					$batterystatus1=$rows45['batterystatus'];
					$oldbattery1=$rows45['oldbatteryno'];
						if($batterystatus1=="REPLACE")
						{
							$serialqry1="SELECT * FROM serialnumbermaster where BatterySlNo ='".$oldbattery1."'";
							$sql26 = mysql_query($serialqry1) ;
							$myrows45= mysql_num_rows($sql26);
							$rows46 = mysql_fetch_array($sql26);
							$batterystatus2=$rows46['batterystatus'];
							$oldbattery2=$rows46['oldbatteryno'];
								if($batterystatus2=="REPLACE")
								{
										$serialqry1="SELECT * FROM serialnumbermaster where BatterySlNo ='".$oldbattery2."'";
										$sql27 = mysql_query($serialqry1) ;
										$myrows47= mysql_num_rows($sql27);
										$rows47 = mysql_fetch_array($sql27);
										$batterystatus3=$rows47['batterystatus'];
										$oldbattery3=$rows47['oldbatteryno'];
										if($batterystatus1=="REPLACE")
										{
										$serialqry1="SELECT * FROM serialnumbermaster where BatterySlNo ='".$oldbattery3."'";
																	
											$sql222 = mysql_query($serialqry1) ;
											$myrows477= mysql_num_rows($sql222);
											$rows477 = mysql_fetch_array($sql222);
											$batterystatus4=$rows477['batterystatus'];
											$oldbattery4=$rows477['oldbatteryno'];
												if($batterystatus4=="REPLACE")
												{
												$serialqry1="SELECT * FROM serialnumbermaster where BatterySlNo ='".$oldbattery4."'";
												$sql223 = mysql_query($serialqry1) ;
												$myrows473= mysql_num_rows($sql223);
												$rows4773 = mysql_fetch_array($sql223);
												$batterystatus5=$rows4773['batterystatus'];
												$oldbattery5=$rows4773['oldbatteryno'];
														if($batterystatus5=="REPLACE")
														{
														$serialqry1="SELECT * FROM serialnumbermaster where BatterySlNo ='".$oldbattery5."'";
														}
														else
														{
															$serialqry1="SELECT * FROM serialnumbermaster where BatterySlNo ='".$oldbattery4."'";
														}
											
												}
										}
										else
										{
										$serialqry1="SELECT * FROM serialnumbermaster where BatterySlNo ='".$oldbattery2."'";
										}
								
								
								
								}
								else
								{
								$serialqry1="SELECT * FROM serialnumbermaster where BatterySlNo ='".$oldbattery1."'";
								}
						
						}
						
						else if($batterystatus=="NEW")
						{
						$serialqry1="SELECT * FROM serialnumbermaster where BatterySlNo ='".$oldbattery."'";
		
						}
						
					//echo $newbattery;
					
					}
				}
				else
				{
				$serialqry1="SELECT * FROM serialnumbermaster where BatterySlNo ='".$search1."'";
				}
		
			
			
		}
		
		//$serialqry1="SELECT * FROM serialnumbermaster where BatterySlNo ='".$search1."' ORDER BY DateofSale";
		//echo $result;
		$serialr1 = mysql_query($serialqry1) ;
		$numrows = mysql_num_rows($serialr1);
		//echo $numrows;
		if($numrows>0)
		{
			$serialrs1 = mysql_fetch_array($serialr1);
				
				$c1=$serialrs1['BatterySlNo'];
				$c1prd=$serialrs1['ProductCode'];
				$c1date=$serialrs1['DateofSale'];
				//echo $c1."<br>";
				//echo $c1date."<br>";
				$serialqry2="SELECT * FROM serialnumbermaster where oldbatteryno ='".$c1."' ORDER BY DateofSale";
				$serialr2 = mysql_query($serialqry2) ;
				$numrows1 = mysql_num_rows($serialr2);
				if($numrows1>0)
				{
					
					for($i=0;$i<5;$i++)
					{
					
					if($i==0 && !empty($c1))
						{
							$serialqry3="SELECT * FROM serialnumbermaster where oldbatteryno ='".$c1."' ORDER BY DateofSale";
							$serialr3 = mysql_query($serialqry3) ;
							$serialrs3 = mysql_fetch_array($serialr3);
							$no1=$serialrs3['BatterySlNo'];
							$date1=$serialrs3['DateofSale'];
							$qryBtry=$serialrs3['BatterySlNo'];						
						}
					if($i==1 && !empty($no1))
						{
							$serialqry3="SELECT * FROM serialnumbermaster where oldbatteryno ='".$qryBtry."' ORDER BY DateofSale";
							$serialr3 = mysql_query($serialqry3) ;
							$serialrs3 = mysql_fetch_array($serialr3);
							$no2=$serialrs3['BatterySlNo'];
							$date2=$serialrs3['DateofSale'];
							$qryBtry=$serialrs3['BatterySlNo'];
						}					
					if($i==2 && !empty($no2))
						{
							$serialqry3="SELECT * FROM serialnumbermaster where oldbatteryno ='".$qryBtry."' ORDER BY DateofSale";
							$serialr3 = mysql_query($serialqry3) ;
							$serialrs3 = mysql_fetch_array($serialr3);
							$no3=$serialrs3['BatterySlNo'];
							$date3=$serialrs3['DateofSale'];
							$qryBtry3=$serialrs3['BatterySlNo'];
						}
						if($i==3 && !empty($no3))
						{
							
							$serialqry4="SELECT * FROM serialnumbermaster where oldbatteryno ='".$qryBtry3."' ORDER BY DateofSale";
							
							$serialr4 = mysql_query($serialqry4) ;
							$numrows4 = mysql_num_rows($serialr4);
							if($numrows4>0)
							{
							
							$serialrs6 = mysql_fetch_array($serialr4);
							$no4=$serialrs6['BatterySlNo'];
							$date4=$serialrs6['DateofSale'];
							$qryBtry4=$serialrs6['BatterySlNo'];
							}
							
						}///// 5th Replace
						if($i==4 && !empty($no4))
						{
							
							$serialqry5="SELECT * FROM serialnumbermaster where oldbatteryno ='".$qryBtry4."' ORDER BY DateofSale";
							
							$serialr5 = mysql_query($serialqry5) ;
							$numrows5 = mysql_num_rows($serialr5);
							if($numrows5>0)
							{
							
							$serialrs6 = mysql_fetch_array($serialr5);
							$no5=$serialrs6['BatterySlNo'];
							$date5=$serialrs6['DateofSale'];
							$qryBtry5=$serialrs6['BatterySlNo'];
							}
							
						}
					}
				}
		}
$type=$_SESSION['type'];
if($type=='Excel'||$type=='Document')
{
	$table .="<table border='2' cellspacing='1'>

     			<tr style='white-space:nowrap;'>
                <td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000; height:30px;font-weight:bold;'>Entered Battery Sl. No.</td>
                <td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000; height:30px;font-weight:bold;'>Battery Product Code</td>
				<td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000; height:30px;font-weight:bold;'>Original Battery Sl. No.</td>
				<td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000; height:30px;font-weight:bold;'>Original Battery DateofSale</td>
                <td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000; height:30px;font-weight:bold;'>I st  Replacement Sl. No.</td>
                <td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000; height:30px;font-weight:bold;'>I st Repl. Date</td>
                <td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000; height:30px;font-weight:bold;'>II nd Replacement Sl. No.</td>
                <td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000; height:30px;font-weight:bold;'>II nd Repl. Date</td>
                <td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000; height:30px;font-weight:bold;'>III rd  Replacement Sl. No.</td>
                <td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000; height:30px;font-weight:bold;'>III rd Repl. Date</td>
                <td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000; height:30px;font-weight:bold;'>IV th  Replacement Sl. No.</td>
                <td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000; height:30px;font-weight:bold;'>IV th Repl. Date</td>
				<td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000; height:30px;font-weight:bold;'>V th  Replacement Sl. No.</td>
                <td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000; height:30px;font-weight:bold;'>V th Repl. Date</td>
			  </tr>";
               
		
              // This while will loop through all of the records as long as there is another record left. 
               if(!empty($c1))
			  { // Basically as long as $record isn't false, we'll keep looping.
				// You'll see below here the short hand for echoing php strings.
				// <?=$record[key] - will display the value for that array.
			   if(empty($c1)){ $c1= ''; } else { $c1='#'.$c1; }
			   if(empty($no1)){ $no1= ''; } else { $no1='#'.$no1; }
 			   if(empty($no2)){ $no2= ''; } else { $no2='#'.$no2; }
			   if(empty($no3)){ $no3= ''; } else { $no3='#'.$no3; }
 			   if(empty($no4)){ $no4= ''; } else { $no4='#'.$no4; }
			   if(empty($no5)){ $no5= ''; } else { $no5='#'.$no5; }
    
			  $table .="<tr style='white-space:nowrap;'>
    		  <td  border='1111'>".$c1."</td>
	          <td  bgcolor='1111'>".$c1prd."</td>
    		  <td  bgcolor='1111'>".$c1."</td>";
			  if(!Empty($c1)){$table .="
			  <td  bgcolor='1111'>".date('d/m/Y',strtotime($c1date))."</td>";}
			  else{$table.="<td  bgcolor='1111'></td>";}
			  $table .="<td  bgcolor='1111'>".$no1."</td>";
			  if(!Empty($no1)){$table .="
              <td  bgcolor='1111'>".date('d/m/Y',strtotime($date1))."</td>";}
			  else{$table.="<td  bgcolor='1111'></td>";}
			  $table .="
	          <td  bgcolor='1111'>".$no2."</td>";
			  if(!Empty($no2)){$table .="
              <td  bgcolor='1111'>".date('d/m/Y',strtotime($date2))." </td>";}
			  else{$table.="<td  bgcolor='1111'></td>";}
			  $table .="
               <td  bgcolor='1111'>".$no3."</td>";
			  if(!Empty($no3)){$table .="
              <td  bgcolor='1111'>".date('d/m/Y',strtotime($date3))." </td>";}
			  else{$table.="<td  bgcolor='1111'></td>";}
			 
				$table .="
               <td  bgcolor='1111'>".$no4."</td>";
			  if(!Empty($no4)){$table .="
              <td  bgcolor='1111'>".date('d/m/Y',strtotime($date4))." </td>";}
			  else{$table.="<td  bgcolor='1111'></td>";}
			$table .="
               <td  bgcolor='1111'>".$no5."</td>";
			  if(!Empty($no5)){$table .="
              <td  bgcolor='1111'>".date('d/m/Y',strtotime($date5))." </td>";}
			  else{$table.="<td  bgcolor='1111'></td>";}




			 $table .="
              </tr>";
			  }
			  else
			  {
				$table .="<tr>
    		  <td border='1111' align='center' style='font-weight:bold; color:#F00;' colspan='14'>No Records Found..!</td></tr>";
			  }
  
$table .="</table>";
}
if($type=='PDF')
{
///
$table .="<table>";
$table.="<tr  bgcolor='#E41E1E'>
	<td  size='16px' border='1111'  color='#ffffff' colspan='10' align='center'><b>Amara Raja Batteries</b></td></tr>
	<tr   bgcolor='#E41E1E' >
	<td size='14px' border='1111'  color='#ffffff' colspan='10' align='center' ><b>Serial Number History Report</b></td>
	</tr>";
     			$table .="<tr bgcolor='#E41E1E' style='white-space:nowrap;'>
                <td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000;font-weight:bold;'>Entered Battery Sl. No. qqqqqqqqqqqqqqqqw wwwwwwwwwwwwwwwwwwww wewerwerwe qweqw</td>
                <td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000;font-weight:bold;'>Battery Product Code</td>
				 <td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000;font-weight:bold;'>Original Battery Sl. No.</td>
				<td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000;font-weight:bold;'>Original Battery DateofSale</td>
                <td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000;font-weight:bold;'>I st  Replacement Sl. No.</td>
                <td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000;font-weight:bold;'>I st Repl. Date</td>
                <td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000;font-weight:bold;'>II nd Replacement Sl. No.</td>
                <td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000;font-weight:bold;'>II nd Repl. Date</td>
                <td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000;font-weight:bold;'>III rd  Replacement Sl. No.</td>
                <td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000;font-weight:bold;'>III rd Repl. Date</td>
                <td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000;font-weight:bold;'>IV th  Replacement Sl. No.</td>
                <td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000;font-weight:bold;'>IV th Repl. Date</td>
			    <td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000;font-weight:bold;'>V th  Replacement Sl. No.</td>
                <td align='center' bgcolor='#CCCCCC' style=' bordercolor;#FF0000;font-weight:bold;'>V th Repl. Date</td>
			   
			   </tr>";
               
		
              // This while will loop through all of the records as long as there is another record left. 
               if(!empty($c1))
			  { // Basically as long as $record isn't false, we'll keep looping.
				// You'll see below here the short hand for echoing php strings.
				// <?=$record[key] - will display the value for that array.
			   
    
			   $table .="<tr>
    		  <td  border='1111'>".$c1."</td>
	          <td  border='1111'>".$c1prd."</td>
    		  <td  border='1111'>".$c1."</td>";
			  if(!Empty($c1)){$table .="
			  <td  border='1111'>".date('d/m/Y',strtotime($c1date))."</td>";}
			  else{$table .="<td  border='1111'></td>";}
			  $table .="<td  border='1111'>".$no1."</td>";
              if(!Empty($no1)){$table .="<td  border='1111'>".date('d/m/Y',strtotime($date1))."</td>";}
			   else{$table .="<td  border='1111'></td>";}
	          $table .="<td  border='1111'>".$no2."</td>";
              if(!Empty($no2)){$table .="<td  border='1111'>".date('d/m/Y',strtotime($date2))." </td>";}
			   else{$table .="<td  border='1111'></td>";}
               $table .="<td  border='1111'>".$no3."</td>";
              if(!Empty($no3)){$table .="<td  border='1111'>".date('d/m/Y',strtotime($date3))." </td>";}
			   else{$table .="<td  border='1111'></td>";}
             
			$table .="<td  border='1111'>".$no4."</td>";
              if(!Empty($no4)){$table .="<td  border='1111'>".date('d/m/Y',strtotime($date4))." </td>";}
			   else{$table .="<td  border='1111'></td>";}
			$table .="<td  border='1111'>".$no5."</td>";
              if(!Empty($no5)){$table .="<td  border='1111'>".date('d/m/Y',strtotime($date5))." </td>";}
			   else{$table .="<td  border='1111'></td>";}

			 $table .="</tr>";
			  }
			  else
			  {
				$table .="<tr>
    		  <td border='1111' align='center'  style='font-weight:bold; color:#F00;' colspan='14'>No Records Found..!</td></tr>";
			  }
 
      
  
$table .="</table>";


///

// Table format Refered by this site: http://www.vanxuan.net/tool/pdftable/ 
    define('FPDF_FONTPATH', 'font/');
    require("inc/pdftable.inc.php");
    $p = new PDFTable();
//$p->AddPage(L);
    $p->setfont('times', '', 10);
    $p->htmltable($table);
    $p->output('SerialNumberHistory.pdf', 'D');
}
elseif($type=='Excel')
{
	$query=$_SESSION['query'];
	header('Content-type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=SerialNumberHistory.xls");
    header("Pragma: no-cache");
    header("Expires: 0");
    echo $table;
    exit();
}
elseif($type=='Document')
{
	$query=$_SESSION['query'];
	header('Content-type: application/vnd.ms-doc');
    header("Content-Disposition: attachment; filename=SerialNumberHistory.doc");
    header("Pragma: no-cache");
    header("Expires: 0");
    echo $table;
    exit();
}
?>
