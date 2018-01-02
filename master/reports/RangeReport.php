<?php
include '../../functions.php';
sec_session_start();
require_once '../../masterclass.php';
include("../../header.php");
require_once '../../paginationfunction.php';
if(login_check($mysqli) == false) 
{
	header('Location:../../index.php');// Redirect to login page!
} else
{

//This block to authenticate the user whether he has the rights to access this page 
	$pagename = "Range Report";
	require_once 'Authentication_Rights.php';
	// Authentication block ends 
		
	// Export function 
if (isset($_POST['Excel'])) {
	header('Location:Export_RangeReport.php');
    echo '<META HTTP-EQUIV="Refresh" Content="0; URL=Export_RangeReport.php">';
    exit;
}

if (isset($_POST['Cancel'])) {
	unset($_SESSION['form_controls']);
    ?>
    <script type="text/javascript">
        document.location='RangeReport.php';
    </script>
    <?
}
include("inc/common_functions.php");
?>
 <script src="inc/multiselect.js" type="text/javascript"></script>

<title><?php echo $_SESSION['title']; ?> |&nbsp;Range Report</title>
<link href="reportstyle.css" rel="stylesheet" type="text/css" />
</head>

<body><center>

 <?php include("../../menu.php") ?>

    <!--Third Block - Container-->
    <div style="width:100%; height:auto; float:none;">
        <div style="width:980px; height:auto; float:none; margin-top:8px;" class="container">

            <div style="width:950px; height:auto; float:left; margin-bottom:10px; margin-left:15px;" id="center">  
                <!-- form id start-->
                <form method="POST" action="<?php // $_PHP_SELF          ?>">
				        <? require_once'all_list.php' ?>
				
                <div style="width:930px; height:auto;   min-height: 180px; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;">
					<div style="width:930px; height:25px; float:left;  margin-left:0px;" class="head">
                            <p>Range Report</p>
                        </div>
						<table  id="default" style="display:none;"  >
            <tr><td>
<input type="text" name="curdate" id="curdate" value="<?php echo date('Y-m-d'); ?>" />
</td></tr></table>
                        <!-- main row 1 start-->     
                        <div style="width:925px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
                            <!-- col1 -->   
                            <div style="width:800px; height:auto; padding-bottom:5px; float:left; " class="cont">
                                <!--Row1 -->  

                                
				<div style="float:left;width:400px">
				      <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                  <label>Period</label><label style="color:#F00;">*</label>
              </div>
						   <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
						   <select name='Period' id="Period" onChange="SetDateValue();">
								<option value="<? if(!empty($_POST['Period'])){ echo $_POST['Period'];}else{?> 0<? } ?>"><? if(!empty($_POST['Period'])){ echo $_POST['Period'];}else{?> --Select-- <? } ?></option>
								<option value="Current Month">Current Month</option>
								<option value="Last Month">Last Month</option>
								<option value="Custom">Custom</option>
								</select>
							  </div>
							  <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:45px;">
                                    </div>
								  
								  <? if (($authen_row['usertype'])== 'Others') { 
                      require_once 'second_div_element.php';

                   }else{
                      require_once 'first_div_element.php';
                   }

                    ?>
                                   
                                </div>
								
								
								
								
								
                                <div style="float:left;width:400px">
				           <div id= "div_txt1" > 
									   <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
										   <label>Year(yyyy)</label><label style="color:#F00;">*</label>
									   </div>
										  
										  <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
										         <input type="text" name="dw_YrPick" id="dw_YrPick" value="" maxlength="4"/>
												 
										  </div>
										  


									
					               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                   <label>Month</label><label style="color:#F00;">*</label>
								   </div>
								   <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
								   <select name='Month' id="Month" Onchange="customdate();">
								        <option value="">--Select--</option>
										<option value="01">January</option>
										<option value="02">February</option>
										<option value="03">March</option>
										<option value="04">April</option>
										<option value="05">May</option>
										<option value="06">June</option>
										<option value="07">July</option>
										<option value="08">August</option>
										<option value="09">September</option>
										<option value="10">October</option>
										<option value="11">November</option>
										<option value="12">December</option>
									</select>
									  </div>
									  </div>
                                <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                    </div>
								
                                    <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                        
										<input type="hidden" name="SRP_FromDate" id="frdate" readonly="readonly" value="<?php echo $_POST['SRP_FromDate'];   ?>"/>
                                    </div>


                                    <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                        
										<input type="hidden" name="SRP_ToDate" id="todate" readonly="readonly" value="<?php echo $_POST['SRP_ToDate']; ?>" />
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
                        <!-- Main row 1 end-->

                        <!--Main row 2 start-->
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
                      $reg =  $reg ."regionname = '" .$selectedOption."'  OR ";
                    }
                  }
                  
                  if(isset($_POST['branch'])){
                      $brn1 = $brn;
                      $brn = NULL;
                      foreach ($_POST['branch'] as $selectedOption1){
                          if($selectedOption1!="0")
                          {
                          $brn = $brn . "bm.branchcode = '" .$selectedOption1."'  OR ". "\n";
                          }else{
                              $brn = $brn1;
                              break;
                          }
                      }
                  }
                   if(isset($_POST['primaryfranchise'])) 
				   {
                    $pfname1 =$pfname;
                    $pfname = "";
                    foreach ($_POST['primaryfranchise'] as $selectedOption3)
					{
                      if($selectedOption3!="0")
                      {
                      $pfname =  $pfname. "pd_code = '" .$selectedOption3."'  OR ". "\n"; 
                      }
					  else
					  {
                        $pfname =$pfname1;
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
				  if(isset($_POST['dsr'])){
                      $dsr1 = $dsr;
                      $dsr = NULL;
                      foreach ($_POST['dsr'] as $selectedOption1){
                          if($selectedOption1!="0")
                          {
                          $dsr = $dsr . "tertiary_code = '" .$selectedOption1."'  OR ". "\n";
                          }else{
                              $dsr = $dsr1;
                              break;
                          }
                      }
                  }
				   
				   $reg =  substr($reg, 0, -3);
                   $brn = substr($brn, 0, -4);                    
                   $pfname = substr($pfname, 0, -4);
                   $fname = substr($fname, 0, -4);
				   $dsr=substr($dsr,0,-4);
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
                  if($dsr!=NULL)
                  {
                    $grystr=$grystr. "(".$dsr.") AND ";
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
								$dbfromdatefrom="'". $dbfromdatefrom."'";
								$dbtodateto="'".$dbtodateto."'";
								echo $qry ="CALL r_rangereport($dbfromdatefrom,$dbtodateto,$grystrres);";
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
										weblogfun("Report Access", "Admin Report -> ".$pagename);
									$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
									$startpoint = ($page * $limit) - $limit;
									$statement = $_SESSION['form_controls'];
                  // echo $statement;
									$qry_exec=mysql_query($statement);
									$myrow1 = mysql_num_rows($qry_exec);
									$starvalue = $myrow1;
									
									$headarray = array();
									$totalarray=array();
									//$result = mysql_query($qry_exec);
									$i = 0;
									
									?>
                                     <table align="center"  bgcolor="#ffffFF" border="1" width="900px" cellpadding="20%">
								<?
								if($myrow1>0)
									 {
                                ?>
							      <tr class="record_header" style="white-space:nowrap;">
								  <?
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
									
									$j = 0;
									$prevvalue;

									while ($record[] = mysql_fetch_array($qry_exec));									
									for($ik=$startpoint;$ik<$limit+$startpoint;$ik++)
									{
										if($record[$ik][$headarray[0]]!=NULL)
										{
											if($j==0)
											{
												$prevvalue = $record[$ik][$headarray[0]];
												
											}
											else if($j>0)
											{
												if($prevvalue != $record[$ik][$headarray[0]])
												{
													?>
												 <tr class="record_data" style="white-space:nowrap;">
												 
														 <td style="font-weight:bold; width:auto; text-align:center;"><? echo $prevvalue; ?></td>
														 <td></td>
														  
														<?
														for($a=2;$a<sizeof($totalarray);$a++)
														{
															?>
														 <td style="font-weight:bold; width:auto; text-align:center;"><? echo $totalarray[$a]; ?></td>
														<?
														$totalarray[$a]=0;
														}
														?>
												 </tr>	
													 <?
													 $prevvalue=$record[$ik][$headarray[0]];
												}
											}
											?>
											 <tr class="record_data" style="white-space:nowrap;">
											 <td></td>
											 
											<?
											for($i=1;$i<sizeof($headarray);$i++)
											{
												if ( $i>5)
												{
													$totalarray[$i] = $totalarray[$i] +  $record[$ik][$headarray[$i]];
												}
												?>
                                                <td style=" width:auto; text-align:center;"><? echo $record[$ik][$headarray[$i]]; ?></td>
												<?
											}
										?>
										</tr>
										<?
										$j++;		
										}
									}
									?>
									
									
                                     <tr class="record_data" style="white-space:nowrap;">
                                     <td style="font-weight:bold; width:auto; text-align:center;"><? echo $prevvalue; ?></td>
                                     <td></td>
									 <td></td>
									 <td></td>
									 <td></td>
									 <td></td>
									 <td></td>
									 <td></td>
									 
                                    <?
                                    for($a=8;$a<sizeof($totalarray);$a++)
                                    {
                                        ?>
                                     <td style="font-weight:bold; width:auto; text-align:center;"><? echo $totalarray[$a]; ?></td>
                                    <?
                                    $totalarray[$a]=0;
                                    }
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
	SetDateValue();
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
