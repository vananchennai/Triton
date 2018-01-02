<?php 
ini_set('memory_limit', '-1');
include '../../functions.php';
sec_session_start();
require_once '../../masterclass.php';
include("../../header.php");
	require_once '../../paginationfunction.php';
if(login_check($mysqli) == false) 
{
	header('Location:../../index.php');// Redirect to login pa
}else{
	//This block to authenticate the user whether he has the rights to access this page 
	$pagename = "Location Wise Stock Summary";
	// Authentication block ends 
     require_once 'Authentication_Rights.php';
if (isset($_POST['Cancel']))
 {
unset($_SESSION['form_controls']);
    ?>
    <script type="text/javascript">
        document.location='lwssreport.php';
    </script>
    <?
}
if (isset($_POST['Excel'])) 
{
   header('Location:Export_lwssreport.php');
   echo '<META HTTP-EQUIV="Refresh" Content="0; URL=Export_lwssreport.php">';
   exit;
}
include("inc/common_functions.php");
?> 

<script src="inc/multiselect.js" type="text/javascript"></script>

<link href="reportstyle.css" rel="stylesheet" type="text/css" />

<title><?php echo $_SESSION['title']; ?> |&nbsp;Location Wise Stock Summary</title>
</head>

<body><center>

 <?php include("../../menu.php") ?>

 
    <!--Third Block - Container-->
    <div style="width:100%; height:auto; float:none;">
        <div style="width:980px; height:auto; float:none; margin-top:8px;" class="container">

            <div style="width:950px; height:auto; float:left; margin-bottom:10px; margin-left:15px;" id="center">  
                <!-- form id start-->
          <form method="POST" action="<?php // $_PHP_SELF            ?>">
           <? require_once'all_list.php' ?>
                    <div style="width:930px; height:auto;   min-height: 180px; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;">


                        <div style="width:930px; height:25px; float:left;  margin-left:0px;" class="head">
                            <p>Location Wise Stock Summary </p>
                        </div>
                    <div style="width:925px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
                            <!-- col1 -->   
                            <div style="width:800px; height:auto; padding-bottom:5px; float:left; " class="cont">
                                <!--Row1 -->
                                <div style="float:left;width:400px">

                                    <!--Row1 end-->
 								<div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                        <label>From Date</label><label style="color:#F00;">*</label>
                                  </div>
                                    <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                        <input type="text" name="SRP_FromDate" id="rp_frdate" value="<?php echo $_POST['SRP_FromDate']; ?>" readonly />
                                    </div>
                                     <? if (($authen_row['usertype'])== 'Others') { 
                                        require_once 'second_div_element.php';

                                     }else{
                                        require_once 'first_div_element.php';
                                     }

                                      ?>
                                
                                </div>
                                <!-- col1 end -->
                                <div style="float:left;width:400px">
                                 <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                        <label>To Date</label><label style="color:#F00;">*</label>
                                  </div>
                                    <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                        <input type="text" name="SRP_ToDate" id="rp_todate" value="<?php echo $_POST['SRP_ToDate']; ?>" readonly />
                                    </div>
                                    <? if (($authen_row['usertype'])== 'Others') { 
                                        require_once 'first_div_element.php';

                                     }else{
                                        require_once 'second_div_element.php';
                                     }

                                      ?>
                                    
                                </div>
                                <div style="clear:both"></div>


                            </div>

            </div>
                        <div style="width:930px; height:60px; float:left; margin-left:8px; margin-top:8px;">

                            <div style="width:340px; height:50px; float:left;  margin-left:4px; margin-top:0px;" id="center1">



                                <div style="width:95px; height:32px; float:left;  margin-left:35px; margin-top:16px;">
                                    <input id="get_report_btn" name="Get" type="submit" class="button" value="Get Report">
                                </div>
                                <div style="width:85px; height:32px; float:left;  margin-left:75px;margin-top:16px;">
                                    <input id="cancel_btn" name="Cancel" type="submit" class="button" value="Cancel">
                                </div>                     

                            </div>	

                        </div>

                        <?php $table_data_height = '';
                        if (isset($_POST['Get'])) 
						{
                            $table_data_height = " height:auto; ";
                        } ?>
                        
                       <div style="width:917px; <?php echo $table_data_height ?> padding-bottom:8px; margin-top:20px; float:left; margin-left:10px; overflow:auto;" class="grid">
                           


                                <?php
                                if (isset($_POST['Get'])) 
								{
								
									 foreach ($_POST['region'] as $selectedOption){
										if($selectedOption!="0")
										{
									  	$reg =  $reg ."Region = '" .$selectedOption."'  OR ";
										}
									} 

									if(isset($_POST['branch'])){
                                        $brn1 = $brn;
                                        $brn = NULL;
                                        foreach ($_POST['branch'] as $selectedOption1){
                                            if($selectedOption1!="0")
                                            {
                                            $brn = $brn . "Branch = '" .$selectedOption1."'  OR ". "\n";
                                            }else{
                                                $brn = $brn1;
                                                break;
                                            }
                                        }
                                    }
                                    if(isset($_POST['primaryfranchise'])) {
                                    	$pfname1 = $pfname;
                                    	$pfname = "";
	                                    foreach ($_POST['primaryfranchise'] as $selectedOption3){
											if($selectedOption3!="0")
											{
											$pfname =  $pfname. "PrimaryFranchise = '" .$selectedOption3."'  OR ". "\n";	
											}else{
												$pfname = $pfname1;
											}
										}
									}
									if(isset($_POST['franchise'])) 
					                  {
					                    $fname1 = $fname;
					                    $fname=NULL;
	  									foreach ($_POST['franchise'] as $selectedOption2){
	  										if($selectedOption2!="0")
	  										{
	  										 	$fname = $fname . "Franchisecode = '" .$selectedOption2."'  OR ". "\n";
					  						}else{
					                        	$fname = $fname1;
					                        	break;
					                      }
	  									}
					                  }
									//$reg =	substr($reg, 0, -4);
									$brn =	substr($brn, 0, -4);
									$reg =	substr($reg, 0, -3);
	    							$fname =	substr($fname, 0, -4);
	    							$pfname = substr($pfname, 0, -4);
									if($reg!=NULL)
									{
										$grystr = "(".$reg.") AND ";
									}
									if($brn!=NULL)
									{
										$grystr=$grystr. "(".$brn.") AND ";
									}
									if($pfname!=NULL)
									{
										$grystr=$grystr. "(".$pfname.") AND ";
									}
									if($fname!=NULL)
									{
										$grystr=$grystr. "(".$fname.") AND ";
									}
											
									 $grystrres = substr($grystr, 0, -4);
									if($_POST['SRP_FromDate']!="" && $_POST['SRP_ToDate']!="")
									{
										$dbtodateto=date("Y-m-d",strtotime($_POST['SRP_ToDate'])) ;
										$dbfromdatefrom=date('Y-m-d',strtotime($_POST['SRP_FromDate']));
											if($grystrres!=NULL)
											{
												$grystrres= str_replace("'","''",$grystrres);
												$grystrres= "'".$grystrres."'";
												
											}
											else
											{
												$grystrres= "'1'";
											}
											/* if($reg!=NULL)
											{
												$reg= str_replace("'","''",$reg);
												$reg= "'".$reg."'";
												
											}
											else
											{
												$reg= "'1'";
											} */
											
											//
											// if($brn!=NULL)
											// {
											// 	$brn= str_replace("'","''",$brn);
											// 	$brn= "'".$brn."'";
												
											// }
											// else
											// {
											// 	$brn= "'1'";
											// }
								$dbfromdatefrom="'". $dbfromdatefrom."'";
								$dbtodateto="'".$dbtodateto."'";
							    $qry ="CALL r_lwssreport($dbfromdatefrom,$dbtodateto,$grystrres);";
								$_SESSION['form_controls'] = $qry ; 
								}
								else
								{
									?>
										<script type="text/javascript">
                                        alert("Enter Mandatory Fields!");
                                        </script>
                                     <?
								}
									$_GET["page"] ="1";

								}
								if(!empty($_SESSION['form_controls']))
								{
								// Log information
										require_once '../../weblog.php';
										weblogfun("Report Access", "Sales Report -> ".$pagename);
									$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
									$startpoint = ($page * $limit) - $limit;
									$statement = $_SESSION['form_controls'];
									$qry_exec=mysql_query($statement);
									$myrow1 = mysql_num_rows($qry_exec);
									$starvalue = $myrow1;
									// echo $statement;
									$headarray = array();
									$totalarray=array();
									//$result = mysql_query($qry_exec);
									$i = 0;
									
									?>
                                     <table align="center"  bgcolor="#ffffFF" border="1" width="900px" cellpadding="20%">
									<? if($myrow1!=0)
									{?>
                                <tr class="record_header" bgcolor="#8DB5F4" style="white-space:nowrap;">
                                    <?
									// Field header
									while ($i < mysql_num_fields($qry_exec))
									{
									$fld = mysql_fetch_field($qry_exec, $i);
									$headarray[$i]=$fld->name;
									
									$totalarray[$i]=0;
										
									?>
                                         <td style="font-weight:bold; width:auto; text-align:center;"><? echo $fld->name; ?></td>
                                        <?
									$i++;
									}
									?>
                                    </tr>
                                    <?
									// Field values								
									
									$j = 0;
									$s = 0;
									$prevvalue;
									$tarray;
									$rarray;
									$gtotal;
									$stavalue;

									while ($record[] = mysql_fetch_array($qry_exec));									
									for($ik=$startpoint;$ik<$limit+$startpoint;$ik++)
									{
										if($record[$ik][$headarray[0]]!=NULL)
										{
											if($j==0)
											{
												$stavalue = $record[$ik][$headarray[1]];
											}
											else if($j>0)
											{
												if($stavalue != $record[$ik][$headarray[1]])
												{
													?>
												 <tr class="record_data" style="white-space:nowrap;">
												 <td></td>
												 
														 <td style="font-weight:bold; width:auto; text-align:center;"><? echo $stavalue; ?></td>
														 <td></td>
														 <td></td>
														<td style="font-weight:bold; width:auto; text-align:center;"><? echo $tarray; ?></td>
														
														
												 </tr>	
													 <?
													 $rarray = $rarray+$tarray;
													 $tarray=0;
													 $stavalue=$record[$ik][$headarray[1]];
												}
											}
											if($s==0)
											{
												$prevvalue = $record[$ik][$headarray[0]];
											}
											else if($s>0)
											{
												if($prevvalue != $record[$ik][$headarray[0]])
												{
													?>
												 <tr class="record_data" style="white-space:nowrap;">
												 
														 <td style="font-weight:bold; width:auto; text-align:center;"><? echo $prevvalue; ?></td>
														 <td></td>
														 <td></td>
														 <td></td>
														
														 <td style="font-weight:bold; width:auto; text-align:center;"><? echo $rarray; ?></td>
												 </tr>	
													 <?
													 $gtotal = $gtotal + $rarray;
													 $rarray = 0;
													 $prevvalue=$record[$ik][$headarray[0]];
												}
											}
											?>
											 <tr class="record_data" style="white-space:nowrap;">
											 <td></td>
											 <td></td>
											<?
											for($i=2;$i<sizeof($headarray);$i++)
											{
												if ( $i>2)
												{
													$tarray = $tarray +  $record[$ik][$headarray[$i]];
												}
												?>
                                                <td style=" width:auto; text-align:center;"><? echo $record[$ik][$headarray[$i]]; ?></td>
												<?
											}
										?>
										</tr>
										<?
										
										//
										
										$j++;
										$s++;
										}
									}
									?>
                                     <tr class="record_data" style="white-space:nowrap;">
                                     <td></td>
									 <td style="font-weight:bold; width:auto; text-align:center;"><? echo $stavalue; ?></td>
                                     <td></td>
									 <td></td>
                                     <td style="font-weight:bold; width:auto; text-align:center;"><? echo $tarray; ?></td>
                                    <?
									$rarray = $rarray+$tarray;
                                    $tarray=0;
                                    ?>
                                    </tr>
									<tr class="record_data" style="white-space:nowrap;">
                                     
									 <td style="font-weight:bold; width:auto; text-align:center;"><? echo $prevvalue; ?></td>
                                     <td></td>
									 <td></td>
                                    <td></td>
                                     <td style="font-weight:bold; width:auto; text-align:center;"><? echo $rarray; ?></td>
                                    <?
									$gtotal = $gtotal + $rarray;
                                    $rarray=0;
                                    ?>
                                    </tr>
									
                                    <?
									}
									// This while will loop through all of the records as long as there is another record left. 
									if($myrow1==0)
									{
										unset($_SESSION['form_controls']);
								 		echo '<tr class="no_records"><td colspan="111" align="center">No Records Found</td></tr>';
									}
								
                                ?>
						 </table>
						 <? } ?>
						 </div>
                        <br/>
                        <?
						

                       	if(!empty($_SESSION['form_controls']))
								{
									echo pagination($starvalue,$statement,$limit,$page);
									?>
                        
                        <div style="width:260px; height:50px; float:right;  margin-right:15px; margin-top:0px;" class="cont" >

                            <div style="width:70px; height:32px; float:left; margin-left:25px; margin-top:20px;">
                                Export As

                            </div> 
                            <div style="width:83px; height:32px; float:left; margin-left:5px; margin-top:12px;">
                                <select name="Type">
                                   <?php /*?> <option value="PDF">PDF</option><?php */?>
                                    <option value="Excel">CSV</option>
                                </select>
                            </div>  
                            <div style="width:63px; height:32px; float:right; margin-top:18px;">
                                <input id="export_btn" type="submit" name="Excel" value="Export" class="button"/>
                            </div ></div>
                        <?php } ?>
                    </div>
                    <!--Main row 2 end-->

                    <!-- form id start end-->  
                </form>			 
            </div> 

        </div>       
    </div>
    <!--Third Block - Menu -Container -->


    <!--Footer Block -->
    <div id="footer-wrap1">
<?php include("../../footer.php"); ?>
    </div>
<!--Footer Block - End-->
</center></body>
</html>
<? } ?>

<script>
	// Set the present object
	var present = {};
	$('#Period option').each(function(){
	// Get the text of the current option
	var text = $(this).text();
	// Test if the text is already present in the object
	if(present[text]){
	// If it is then remove it
	$(this).remove();
	}else{
	// Otherwise, place it in the object
	present[text] = true;
	}
	});
</script>