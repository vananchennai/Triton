<?php 
ini_set('memory_limit', '-1');
include '../../functions.php';
sec_session_start();
require_once '../../masterclass.php';
include("../../header.php");

if(login_check($mysqli) == false) 
{
	header('Location:../../index.php');// Redirect to login page!
} else
{
require_once '../../paginationdesign1.php';


//This block to authenticate the user whether he has the rights to access this page 
	$pagename = "Retailer Category Summary";
	  require_once 'Authentication_Rights.php';
	// Authentication block ends 
		
	// Export function 
if (isset($_POST['Excel'])) {
	header('Location:Export_rcsreport.php');
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=Export_rcsreport.php">';
	exit;
}

//echo $qry;

if (isset($_POST['Cancel'])) {
    unset($_SESSION['form_controls']);
	?>
    <script type="text/javascript">
        document.location='rcsreport.php';
    </script>
    <?
}
include("inc/common_functions.php");
//include("inc/common_functions.php");
?> 
 <script src="inc/multiselect.js" type="text/javascript"></script>
<script type="text/javascript">  

    $(function() {
        $("#PS_FromDate").datepicker({ 
		changeYear:true,
		maxDate: '0',
		changeMonth: true, 
      	numberOfMonths: 1,
		dateFormat:'dd-mm-yy'});
    });  
</script>
<link href="reportstyle.css" rel="stylesheet" type="text/css" />

<title><?php echo $_SESSION['title']; ?> |&nbsp;Retailer Category Summary Report</title>
</head>

<body><center>

 <?php include("../../menu.php") ?>
 
   <div style="width:100%; height:auto; float:none;">
        <div style="width:980px; height:auto; float:none; margin-top:8px;" class="container">

            <div style="width:950px; height:auto; float:left; margin-bottom:10px; margin-left:15px;" id="center">  
                <!-- form id start-->
                <form method="POST" action="<?php // $_PHP_SELF          ?>">
                
                <table id="default" style=" height:10px; display:none;" >
                  <tr>
                   <td>
                      <select  name="rclist" id="rclist">
                       <?
                          $que = mysql_query("SELECT f.Franchisename ,r.RetailerName,r.Category, r.franchiseeme, r.retailerclassification, r.geographical, r.retailercategory1, r.retailercategory2, r.retailercategory3
      FROM `retailermaster` r
      LEFT JOIN franchisemaster f on (Franchisecode= SPLIT_STR(r.RetailerCode,'-',1) ) ;");
                         
                       while( $record = mysql_fetch_array($que))
                       {
            
                        echo "<option value=\"".$record['Franchisename']."~".$record['RetailerName']."~".$record['Category']."~".$record['franchiseeme']."~".$record['retailerclassification']."~".$record['geographical']."~".$record['retailercategory1']."~".$record['retailercategory2']."~".$record['retailercategory3']."\">".$record['Franchisename']."~".$record['RetailerName']."~".$record['Category']."~".$record['franchiseeme']."~".$record['retailerclassification']."~".$record['geographical']."~".$record['retailercategory1']."~".$record['retailercategory2']."~".$record['retailercategory3']."\n "; 
      }
                		 
                      ?>
                      </select>
                   </td>
                  </tr>
              </table>
              <? require_once'all_list.php' ?>  
                    <div style="width:930px; height:auto;   min-height: 180px; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;">


                        <div style="width:930px; height:25px; float:left;  margin-left:0px;" class="head">
                            <p>Retailer Category Summary Report </p>
                        </div>
                        <!-- main row 1 start-->     
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
									foreach ($_POST['region'] as $selectedOption)
									{
										if($selectedOption!="0")
										{
 											$reg =  $reg ."regionname = '" .$selectedOption."'  OR ";
										}
									}
									foreach ($_POST['branch'] as $selectedOption1)
									{
										if($selectedOption1!="0")
										{
											$brn = $brn . "branchname = '" .$selectedOption1."'  OR ". "\n";
										}
									}
									
									foreach ($_POST['franchise'] as $selectedOption2)
									{
										if($selectedOption2!="0")
										{
											$fname = $fname . "franchisename = '" .$selectedOption2."'  OR ". "\n";
										}
									}
									foreach ($_POST['RetailerName'] as $selectedOption3)
									{
										if($selectedOption3!="0")
										{
											$rname = $rname. "retailername = '" .$selectedOption3."'  OR ". "\n";
										}
									}
                  foreach ($_POST['RetailerCategory'] as $selectedOption5){
                    if($selectedOption5!="0")
                    {
                      $rcat = $rcat. "rcategory = '" .$selectedOption5."'  OR ". "\n";
                    }
                  }
									// foreach ($_POST['rclass'] as $selectedOption4){
									// 	if($selectedOption4!="0")
									// 	{
									// 		$rclass = $rclass. "rclass = '" .$selectedOption4."'  OR ". "\n";
									// 	}
									// }
									// foreach ($_POST['franchiseeme'] as $selectedOption6){
									// 	if($selectedOption6!="0")
									// 	{
									// 		$fme = $fme. "franchiseeme = '" .$selectedOption6."'  OR ". "\n";
									// 	}
									// }
									foreach ($_POST['Productgroup'] as $selectedOption7){
										if($selectedOption7!="0")
										{
										$pgrp =  $pgrp. "pgroupname = '" .$selectedOption7."'  OR ". "\n";	
										}
									}
									// foreach ($_POST['Productsegment'] as $selectedOption8){
									// 	if($selectedOption8!="0")
									// 	{
									// 	$pseg=  $pseg. "psegmentname = '" .$selectedOption8."'  OR ". "\n";
									// 	}
									// }
									// foreach ($_POST['Producttype'] as $selectedOption9){
									// 	if($selectedOption9!="0")
									// 	{
									// 	$ptype =  $ptype. "ptypename = '" .$selectedOption9."'  OR ". "\n";  
									// 	}
									// }
									
									foreach ($_POST['productcode'] as $selectedOption10){
										if($selectedOption10!="0")
										{
										$pcode =  $pcode. "productcode = '" .$selectedOption10."'  OR ". "\n";
										}
									}
									
									foreach ($_POST['Voucher'] as $selectedOption11){
										if($selectedOption11!="0")
										{
										$pvouc = $pvouc. "vtype = '" .$selectedOption11."'  OR ". "\n";
										}
									}
									// foreach ($_POST['rc1'] as $selectedOption12){
									// 	if($selectedOption12!="0")
									// 	{
									// 	$rc1 = $rc1. "rc1 = '" .$selectedOption12."'  OR ". "\n";
									// 	}
									// }
									// foreach ($_POST['rc2'] as $selectedOption13){
									// 	if($selectedOption13!="0")
									// 	{
									// 	$rc2 = $rc2. "rc2 = '" .$selectedOption13."'  OR ". "\n";
									// 	}
									// }
									// foreach ($_POST['rc3'] as $selectedOption14){
									// 	if($selectedOption14!="0")
									// 	{
									// 	$rc3 = $rc3. "rc3 = '" .$selectedOption14."'  OR ". "\n";
									// 	}
									// }
									// foreach ($_POST['geographical'] as $selectedOption15){
									// 	if($selectedOption15!="0")
									// 	{
									// 	$geographical = $geographical. "geographical = '" .$selectedOption15."'  OR ". "\n";
									// 	}
									// }
									
									$reg 			=	substr($reg, 0, -3);
									$brn 			=	substr($brn, 0, -4);										
									$fname 			=	substr($fname, 0, -4);
									$rname 			=	substr($rname, 0, -4);
									//$rclass 		=	substr($rclass, 0, -4);
									$rcat			=	substr($rcat, 0, -4);
									//$fme			=	substr($fme, 0, -4);
									$pgrp 			=	substr($pgrp, 0, -4);
									//$pseg 			=	substr($pseg, 0, -4);
									//$ptype 			=	substr($ptype, 0, -4);
									$pcode 			=	substr($pcode, 0, -4);
									$pvouc 			=	substr($pvouc, 0, -4);
									// $rc1 			=	substr($rc1, 0, -4);
									// $rc2 			=	substr($rc2, 0, -4);
									// $rc3 			=	substr($rc3, 0, -4);
									// $geographical	=	substr($geographical, 0, -4);	
											if($reg!=NULL)
											{
												$grystr = "(".$reg.") AND ";
											}
											
											if($brn!=NULL)
											{
												$grystr=$grystr. "(".$brn.") AND ";
											}
											if($fname!=NULL)
											{
												$grystr=$grystr. "(".$fname.") AND ";
											}
											if($rname!=NULL)
											{
												$grystr=$grystr. "(".$rname.") AND ";
											}
											// if($rclass!=NULL)
											// {
											// 	$grystr=$grystr. "(".$rclass.") AND ";
											// }
											if($rcat!=NULL)
											{
												$grystr=$grystr. "(".$rcat.") AND ";
											}
											// if($fme!=NULL)
											// {
											// 	$grystr=$grystr. "(".$fme.") AND ";
											// }
											if($pgrp!=NULL)
											{
												$grystr=$grystr. "(".$pgrp.") AND ";
											}
											// if($pseg!=NULL)
											// {
											// 	$grystr=$grystr. "(".$pseg.") AND ";
											// }
											// if($ptype!=NULL)
											// {
											// 	$grystr=$grystr. "(".$ptype.") AND ";
											// }
											if($pcode!=NULL)
											{
												$grystr=$grystr. "(".$pcode.") AND ";
											}
											if($pvouc!=NULL)
											{
												$grystr=$grystr. "(".$pvouc.") AND ";
											}
											// if($rc1!=NULL)
											// {
											// 	$grystr=$grystr. "(".$rc1.") AND ";
											// }
											// if($rc2!=NULL)
											// {
											// 	$grystr=$grystr. "(".$rc2.") AND ";
											// }
											// if($rc3!=NULL)
											// {
											// 	$grystr=$grystr. "(".$rc3.") AND ";
											// }
											// if($geographical!=NULL)
											// {
											// 	$grystr=$grystr. "(".$geographical.") AND ";
											// }
									 $grystrres = substr($grystr, 0, -4);
									if($_POST['SRP_FromDate']!="" && $_POST['SRP_ToDate']!="")
									{
									 if($grystrres!=NULL)
									{
										$grystrres= str_replace("'","''",$grystrres);
										$grystrres= "'".$grystrres."'";
									}
									else
									{
										$grystrres= "'1'";
									}
									$dbtodateto=date("Y-m-d",strtotime($_POST['SRP_ToDate'])) ;
									$dbfromdatefrom=date('Y-m-d',strtotime($_POST['SRP_FromDate']));
									 $dbfromdatefrom="'". $dbfromdatefrom."'";
									 $dbtodateto="'".$dbtodateto."'";
									$qry ="CALL r_MWSreport($dbfromdatefrom,$dbtodateto,$grystrres);";
								    // echo $qry;
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
									//echo $limit;
									//echo $startpoint;
									$statement = $_SESSION['form_controls'];
									$qry_exec=mysql_query($statement);
									$myrow1 = mysql_num_rows($qry_exec);
									$starvalue = $myrow1;
									
									$headarray = array();
									$totalarray=array();
									//$result = mysql_query($qry_exec);
									$i = 0;
									
									?>
                                     <table align="center"  bgcolor="#ffffFF" border="1" width="900px" cellpadding="20%">
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
									///////////////////////////////////////////////////////////////////////////////////////////////////
									///////////////////////////////////////////////////////////////////////////////////////////////////
									/*while ($record = mysql_fetch_array($qry_exec))
									{
										$headarray[$i] = $record['dmonth'] ;
										$headpcarray[$i] = $record['productcode'].'~'.$record['ptypename'] ;
										$datearray[$i] = $record['dmonth'] ;
										$pcarray[$i] = $record['productcode'];
										$headgrparray[$i] = $record['ptypename'];
										$ptyarray[$i] = $record['ptypename'];
										$qtyarray[$i] = $record['qty'];
										
										$i++;
															
									}*/
								/*	$headarray = array_keys(array_count_values($headarray));
									$headarray = array_unique($headarray);
									$headgrparray = array_keys(array_count_values($headgrparray));
									$headpcarray = array_keys(array_count_values($headpcarray));*/
									///////////////////////////////////////////////////////////////////////////////////////////////////
									
									
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
												if ( $i>1)
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
									
									/*
									 <table align="center"  bgcolor="#ffffFF" border="1" width="900px" cellpadding="20%">
                                <tr class="record_header" style="white-space:nowrap;">
                                    <td style="font-weight:bold; width:auto; text-align:center;">Region</td>
                                    <td style="font-weight:bold; width:auto; text-align:center;">Branch</td>
                                   <td style="font-weight:bold; width:auto; text-align:center;">Franchisee Name</td>
                                    <td style="font-weight:bold; width:auto; text-align:center;">Retailer Name</td>
                                    <td style="font-weight:bold; width:auto; text-align:center;">Sales Voucher Type</td>
                                    <td style="font-weight:bold; width:auto; text-align:center;">Product Group</td>                                    
                                    <td style="font-weight:bold; width:auto; text-align:center;">Product Segment</td>  
                                    <td style="font-weight:bold; width:auto; text-align:center;">Product Type</td>
                                    <td style="font-weight:bold; width:auto; text-align:center;">Product Code</td> 
                                    <td style="font-weight:bold; width:auto; text-align:center;">Retailer Category</td>
                                    <td style="font-weight:bold; width:auto; text-align:center;">Retailer Classification</td>
                                    <td style="font-weight:bold; width:auto; text-align:center;">Franchisee.M.E</td>
                                    <td style="font-weight:bold; width:auto; text-align:center;">Geography</td>
                                    <td style="font-weight:bold; width:auto; text-align:center;">Retailer Category 1</td>
                                    <td style="font-weight:bold; width:auto; text-align:center;">Retailer Category 2</td>
                                    <td style="font-weight:bold; width:auto; text-align:center;">Retailer Category 3</td>
                                    <td style="font-weight:bold; width:auto; text-align:center;">Quantity</td>
                                </tr>*/
									
									
									
									
									////////////////////////////////////////////////////////////////////////////////////////
									/*for($aa=0;$aa<sizeof($headgrparray);$aa++)
									{
										echo '<tr class="record_data" style="white-space:nowrap;">';
										
											echo '<td>' . $headgrparray[$aa]. '</td>';
											echo '</tr>';
											
									
										for($i=0;$i<sizeof($headpcarray);$i++)
										{
											list($pocode, $potype) = explode('~',$headpcarray[$i]);
											$ttl=0;
											$Prtype = 
												if($potype==$headgrparray[$aa])
												{
													echo '<tr class="record_data" style="white-space:nowrap;">';
													echo '<td></td>';
													echo '<td>' . $pocode. '</td>';
													for($j=0;$j<sizeof($headarray);$j++)
													{
														$z=0;
														 for($k=0;$k<sizeof($qtyarray);$k++)
														 {
															if($headarray[$j] == $datearray[$k] && $pocode == $pcarray[$k])
															{
																echo '<td>' . $qtyarray[$k]. '</td>';
																$ttl=$ttl+$qtyarray[$k];
																$z++;
															}
														 }
														 if($z==0)
														 {
															 echo '<td>0</td>';
														 }
													}
													echo '<td>' . $ttl. '</td>';
													echo '</tr>';
												}
												
										}
									
									}*/
									
									///////////////////////////////////////////////////////////////////////////////////////////////////////
									// This while will loop through all of the records as long as there is another record left. 
									if($myrow1==0)
									{
										unset($_SESSION['form_controls']);
								 		echo '<tr class="no_records"><td colspan="111" align="center">No Records Found</td></tr>';
									}
								}
                                ?>
						 </table>
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
                                    <option value="Excel">CSV	</option>
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
<?php 
}
//}
//} ?>
