<?php 
include '../../functions.php';
	sec_session_start();
	require_once '../../masterclass.php';
	include("../../header.php");

if(login_check($mysqli) == false) 
{
	header('Location:../../index.php');// Redirect to login page!
} else
{
	
	$tname	= "serialnumbermaster";
   	$news = new News(); // Create a new News Object
	$pagename = "Serial Number History";
	$validuser = $_SESSION['username'];
	$authen_qry =mysql_query( "select access_right from reportrights where userid = '$validuser' and r_screen = '$pagename'");
	$authen_row = mysql_fetch_array($authen_qry);

 	if (($authen_row['access_right'])== 'No')
	{
		header("location:/".$_SESSION['mainfolder']."/home/home/master.php");
	}
	

	global $salesinvoiceno,$TertiarySalesEntryDate,$BatterySlNo,$DateofSale,$Salestype,$ProductCode,$ProductDescription,$CustomerName,$CustomerAddress,$City,$CustomerPhoneNo,$RetailerName,$FranchiseeName,$VehicleorInverterModel,$VehicleorInverterMake,$VehicleSegment,$Enginetype,$VehicleNo,$ManufacturingDate,$checkbox,$tname,$search1,$batterystatus,$oldbatteryno,$serialqry1;
    global $c1,$c1prd,$c1date,$no1,$date1,$no2,$date2,$no3,$date3;
	$c1=$c1prd=$c1date=$no1=$date1=$no2=$date2=$no3=$date3="";

	   

if(isset($_POST['Get']))
{
								// Log information
										require_once '../../weblog.php';
										weblogfun("Report Access", $pagename);
    $search1=$_POST['names'];
if(isset($_POST['names']))
{
	if(empty($_POST['names']))
	{
		echo '<script type="text/javascript">alert("Data not found!","serialnumberreport.php");</script>';
	}
	else
	{
		 
         $search1 = $_POST['names'];
        
	if(!empty($_POST['names']))
	{  
			$result="SELECT * FROM serialnumbermaster where BatterySlNo ='".$search1."'";
		$row2 = mysql_query($result) ;
		$numrows2 = mysql_num_rows($row2);
		if($numrows2>0)
		{
			$rows2 = mysql_fetch_array($row2);
			$batterystatus=$rows2['batterystatus'];
			$oldbattery=$rows2['oldbatteryno'];
			if($batterystatus=="REPLACE")
				{
					$serialqry1="SELECT * FROM serialnumbermaster where BatterySlNo ='".$oldbattery."'";
					
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
									if($myrows47>0)
									{
										$rows47 = mysql_fetch_array($sql27);
										$batterystatus3=$rows47['batterystatus'];
										$oldbattery3=$rows47['oldbatteryno'];
										if($batterystatus3=="REPLACE")
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
												else
												{
												$serialqry1="SELECT * FROM serialnumbermaster where BatterySlNo ='".$oldbattery3."'";
												}
										
										
										}
										else
										{
										$serialqry1="SELECT * FROM serialnumbermaster where BatterySlNo ='".$oldbattery2."'";
										}
								
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
						
				
					
					}
				}
				else
				{
				$serialqry1="SELECT * FROM serialnumbermaster where BatterySlNo ='".$search1."'";
				}
		
			
			
		}
		else
		{
		?>
		    <script type="text/javascript">
			
		//	document.location='serialnumberreport.php';
			alert("Data not found!");
			</script>
			<?
		}
		
		
		//echo $serialqry1;
		
	//	$serialqry1="SELECT * FROM serialnumbermaster where BatterySlNo ='".$search1."' ORDER BY DateofSale";
		//$serialqry1="SELECT * FROM serialnumbermaster where BatterySlNo ='ASDF08D123456' ORDER BY DateofSale";
		//echo $result;
	//	echo serialqry1;
		$serialr1 = mysql_query($serialqry1) ;
		$numrows = mysql_num_rows($serialr1);
		//echo $numrows;
		if($numrows>0)
		{
			$serialrs1 = mysql_fetch_array($serialr1);
				
				$c1=$serialrs1['BatterySlNo'];//1 
				$c1prd=$serialrs1['ProductCode'];
				$c1date=$serialrs1['DateofSale'];
				$status=$serialrs1['batterystatus'];
			//echo $c1."<br>";
				//echo $c1date."<br>";
				$serialqry0="SELECT * FROM serialnumbermaster where oldbatteryno ='".$c1."' ORDER BY DateofSale";
				$serialr2 = mysql_query($serialqry0) ;
				$numrows1 = mysql_num_rows($serialr2);
				if($numrows1>0)
				{
				$serialqry3='';
					
					for($i=0;$i<5;$i++)
					{
					
					
					if($i==0 && !empty($c1))
						{
							$serialqry1="SELECT * FROM serialnumbermaster where oldbatteryno ='".$c1."' ORDER BY DateofSale";
							
							$serialr1 = mysql_query($serialqry1) ;
							$numrows4 = mysql_num_rows($serialr1);
							//$serialrscheck = mysql_fetch_array($serialr1);
							//$c1prd=$serialrs1['ProductCode'];
							if($numrows4>0)
							{
							$serialrs3 = mysql_fetch_array($serialr1);
							$no1=$serialrs3['BatterySlNo'];
							$date1=$serialrs3['DateofSale'];
							$qryBtry1=$serialrs3['BatterySlNo'];	
							}							
						} ///// 2nd Replace
					 if($i==1 && !empty($no1))
						{
						
							$serialqry2="SELECT * FROM serialnumbermaster where oldbatteryno ='".$qryBtry1."' ORDER BY DateofSale";
							
							$serialr2 = mysql_query($serialqry2) ;
							$numrows2 = mysql_num_rows($serialr2);
							if($numrows2>0)
							{
							$serialrs4 = mysql_fetch_array($serialr2);
							$no2=$serialrs4['BatterySlNo'];
							$date2=$serialrs4['DateofSale'];
							$qryBtry2=$serialrs4['BatterySlNo'];
						
							}
						}	///// 3rd Replace				
					if($i==2 && !empty($no2))
						{
							
							$serialqry3="SELECT * FROM serialnumbermaster where oldbatteryno ='".$qryBtry2."' ORDER BY DateofSale";
							
							$serialr3 = mysql_query($serialqry3) ;
							$numrows3 = mysql_num_rows($serialr3);
							if($numrows3>0)
							{
							
							$serialrs5 = mysql_fetch_array($serialr3);
							$no3=$serialrs5['BatterySlNo'];
							$date3=$serialrs5['DateofSale'];
							$qryBtry3=$serialrs5['BatterySlNo'];
							}
							
						}
						///// 4th Replace
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
	}
}

}
}
$_SESSION['type']=NULL;

	//$_SESSION['query']=$serialnumbermasterqry;
	//$serialnumbermasterqry='select * from serialnumbermaster';
if(isset($_POST['PDF']))
{
if(!empty($_POST['names']))
	{     
       
		$serialnumbermasterqry="SELECT * FROM serialnumbermaster WHERE BatterySlNo ='".$_POST['names']."'";
		//echo $condition;
		
		$row2 = mysql_query($serialnumbermasterqry) ;
		$numrows = mysql_num_rows($row2);
		//echo $numrows;
		if($numrows>0)
		{
			$rows2 = mysql_fetch_array($row2);
			$batterystatus=$rows2['batterystatus'];
			$oldbattery=$rows2['oldbatteryno'];
			//echo $batterystatus;
			if($batterystatus=="NEW")
				{
				
				$serialnumbermasterqry="SELECT * FROM serialnumbermaster where BatterySlNo ='".$_POST['names']."'";
				
				}
				else
				{
				$serialnumbermasterqry="SELECT * FROM serialnumbermaster d WHERE EXISTS (SELECT NULL FROM serialnumbermaster c WHERE c.`BatterySlNo` = d.`oldbatteryno`
					)AND EXISTS (SELECT NULL FROM serialnumbermaster a WHERE a.oldbatteryno ='".$_POST['names']."'OR a.BatterySlNo ='".$_POST['names']."' )";
				//echo $condition;
				}
		}
		
	}
	else
	{
		
		$serialnumbermasterqry="SELECT * FROM serialnumbermaster WHERE 1";
	}
$select=$_POST['Type'];
if($select=='PDF')
{
	$_SESSION['type']='PDF';
	$_SESSION['query']=$_POST['names'];
	
	header('Location:Exportsalesregnew.php');
}
elseif($select=='Excel')
{
	$_SESSION['type']='Excel';
	$_SESSION['query']=$_POST['names'];

	header('Location:Exportsalesregnew.php');
}
elseif($select=='Document')
{
	$_SESSION['type']='Document';
	$_SESSION['query']=$_POST['names'];
	header('Location:Exportsalesregnew.php');
}
}


if(isset($_POST['Cancel']))
{
	?>
	 <script type="text/javascript">
	document.location='serialnumberreport.php';
	</script>
	<? 
}
?> 
 
 
    <title><?php echo $_SESSION['title']; ?> || Serial Number History </title>
</head>

<body onLoad="init()"><center>

<?php include("../../menu.php")?>

<!--Third Block - Container-->
<div style="width:100%; height:auto; float:none;">
     <div style="width:980px; height:auto; float:none; margin-top:8px;" class="container">
                  
          <div style="width:950px; height:auto; float:left; margin-bottom:10px; margin-left:15px;" id="center">  
            <!-- form id start-->
			<form method="POST" action="<?php $_PHP_SELF ?>" id="frm1">
            <div style="width:930px; height:auto;   min-height: 70px; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;">

                    
						<div style="width:930px; height:25px; float:left;  margin-left:0px;" class="head">
						<p>Serial Number History</p>
						</div>
              <!-- main row 1 start-->     
                    <div style="width:930px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
                     <!-- col1 -->   
                           <div style="width:400px; height:auto; padding-bottom:5px; float:left; " class="cont">
                           
                             <div style="width:100px; height:32px; float:left; margin-left:20px;" class="cont">
						     <label>Battery Serial No</label>
				           </div>
						   
						   <div style="width:180px; height:32px; margin-left:10px; float:left;" class="cont">
						    <input type="text" name="names"  value="<? echo $search1 ?>"/>
				           </div>    
                                  </div>                             
                     <!-- col1 end --> 
                   </div>
                </div>
                <!-- main row 1 end-->
                
                
                     <!--Main row 2 start-->
                <div style="width:1000px; height:60px; float:left; margin-left:18px; margin-top:8px;">
                             
					<div style="width:235px; height:50px; float:left;  margin-left:14px; margin-top:0px;" id="center1">
						   
               				 <div style="width:100px; height:32px; float:left;margin-top:16px; margin-left:10px;">
						  	<input name="Get" type="submit" class="button" value="Get Report">
				            </div>
                          
                           <div style="width:100px; height:32px; float:left;margin-top:16px; margin-left:10px;">
						  <input name="Cancel" type="submit" class="button" value="Cancel">
				           </div>         
                                                   
				     </div>	
                 </div>
                 
                <div style="width:930px; height:auto; padding-bottom:8px; margin-top:20px; float:left; margin-left:10px; overflow:auto;" class="grid">
                   
                <table align="center" class= "sortable" id="datatable1" bgcolor="#FFFFFF" border="1" width="900px" cellpadding="20%">
     			<tr class="record_header"  bgcolor="#FA8072" style="white-space:nowrap;">
             
                <td style="font-weight:bold;">Battery Product Code</td>
				 <td style="font-weight:bold;">Original Battery Sl. No.</td>
				<td style="font-weight:bold;">Original Battery DateofSale</td>
                <td style="font-weight:bold;">I st  Replacement Sl. No.</td>
                <td style="font-weight:bold;">I st Repl. Date</td>
                <td style="font-weight:bold;">II nd Replacement Sl. No.</td>
                <td style="font-weight:bold;">II nd Repl. Date</td>
                <td style="font-weight:bold;">III rd  Replacement Sl. No.</td>
                <td style="font-weight:bold;">III rd Repl. Date</td>
               <td style="font-weight:bold;">IVth Replacement Sl. No.</td>
                <td style="font-weight:bold;">IVth Repl. Date</td>
                <td style="font-weight:bold;">Vth Replacement Sl. No.</td>
                <td style="font-weight:bold;">Vth Repl. Date</td>


			 </tr>
               <?php
		
              // This while will loop through all of the records as long as there is another record left. 
               if(!empty($c1))
			  { // Basically as long as $record isn't false, we'll keep looping.
				// You'll see below here the short hand for echoing php strings.
				// <?=$record[key] - will display the value for that array.
			   ?>
    
			  <tr style="white-space:nowrap;">
    		 
	          <td  bgcolor="#FFFFFF"><?=$c1prd?></td>
    		  <td  bgcolor="#FFFFFF"><?=$c1?></td>
			  <? if(!empty($c1)){?>
			  <td  bgcolor="#FFFFFF"><?=date("d/m/Y",strtotime($c1date))?></td>
			  <?} else {?>
			  <td  bgcolor="#FFFFFF"></td>
			  <?}?>
			  <td  bgcolor="#FFFFFF"><?=$no1?></td>
			  <? if(!empty($no1)){?>
			  <td  bgcolor="#FFFFFF"><?=date("d/m/Y",strtotime($date1))?></td>
			  <?} else {?>
			  <td  bgcolor="#FFFFFF"></td>
			  <?}?>
	          <td  bgcolor="#FFFFFF"><?=$no2?></td>
			   <? if(!empty($no2)){?>
			  <td  bgcolor="#FFFFFF"><?=date("d/m/Y",strtotime($date2))?></td>
			  <?} else {?>
			  <td  bgcolor="#FFFFFF"></td>
			  <?}?>
               <td  bgcolor="#FFFFFF"><?=$no3?></td>
			    <? if(!empty($no3)){?>
			  <td  bgcolor="#FFFFFF"><?=date("d/m/Y",strtotime($date3))?></td>
			  <?} else {?>
			  <td  bgcolor="#FFFFFF"></td>
			  <?}?>
			 
			  <td  bgcolor="#FFFFFF"><?=$no4?></td>
			    <? if(!empty($no4)){?>
			  <td  bgcolor="#FFFFFF"><?=date("d/m/Y",strtotime($date4))?></td>
			  <?} else {?>
			  <td  bgcolor="#FFFFFF"></td>
			  <?}?>
			  <td  bgcolor="#FFFFFF"><?=$no5?></td>
			    <? if(!empty($no5)){?>
			  <td  bgcolor="#FFFFFF"><?=date("d/m/Y",strtotime($date5))?></td>
			  <?} else {?>
			  <td  bgcolor="#FFFFFF"></td>
			  <?}?>
              </tr>

  <?php
      }
  ?>
</table>


</div>
<br />   <?php if (isset($_POST['Get'])) { ?>
             <div style="width:260px; height:50px; float:right;  margin-right:15px; margin-top:0px;" class="cont" >
         					
                               <div style="width:70px; height:32px; float:left; margin-left:25px; margin-top:20px;">
                              Export As
             				
                               </div> 
                               <div style="width:83px; height:32px; float:left; margin-left:5px; margin-top:12px;">
                                <select name="Type">
                                  
                                    <option value="Excel">Excel</option>
                                     <option value="Document">Document</option>
                                                                   </select>
             				
                               </div>  
                               <div style="width:63px; height:32px; float:right; margin-top:18px;">
             					  <input type="submit" name="PDF" value="Export" class="button"/>
                               </div ></div>
 <?php } ?>
                <!--Main row 2 end-->
            
             <!-- form id start end-->  
</form>			 
          </div> 
          
     </div>       
</div>
<!--Third Block - Menu -Container -->


<!--Footer Block -->
<div id="footer-wrap1">
        <?php include("../../footer.php")?>
  </div>
<!--Footer Block - End-->
</center></body>
</html>
<?
}
?>
