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

require_once '../../paginationfunction.php';
$news = new News(); // Create a new News Object

//This block to authenticate the user whether he has the rights to access this page 
	$pagename = "Purchase Report";
	// Authentication block ends 
	require_once 'Authentication_Rights.php';
	
	// Export function 
if (isset($_POST['Excel'])) 
{
    header('Location:Export_PReport.php');
    echo '<META HTTP-EQUIV="Refresh" Content="0; URL=Export_PReport.php">';
    exit;
}

if (isset($_POST['Cancel'])) 
{
	unset($_SESSION['form_controls']);
   ?><script type="text/javascript"> document.location='PurchaseReport.php';</script><?
}
include("inc/common_functions.php");
?> 
<script src="inc/multiselect.js"></script> 

<link href="reportstyle.css" rel="stylesheet" type="text/css" />
<title><?php echo $_SESSION['title']; ?> |&nbsp;Purchase Report</title>
</head>

<body><center>


<?php include("../../menu.php") ?>
    <!--Second Block - Menu -End -->

    <!--Third Block - Container-->
    <div style="width:100%; height:auto; float:none;">
        <div style="width:980px; height:auto; float:none; margin-top:8px;" class="container">

            <div style="width:950px; height:auto; float:left; margin-bottom:10px; margin-left:15px;" id="center">  
                <!-- form id start-->
                <form method="POST" action="<?php // $_PHP_SELF        ?>">
                   <? require_once'all_list.php' ?>
                    <div style="width:930px; height:auto;   min-height: 180px; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;">


                        <div style="width:930px; height:25px; float:left;  margin-left:0px;" class="head">
                            <p>Purchase Report</p>
                        </div>
                        
                        <!-- main row 1 start-->     
                        <div style="width:925px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
                            <!-- col1 -->   
                            <div style="width:800px; height:auto; padding-bottom:5px; float:left; " class="cont">
                                <!--Row1 -->
                                
                                <div style="float:left;width:400px;">
                                <!--Fromdate -->
                                   <div style="width:145px; height:auto; float:left; margin-top:5px; margin-left:3px;">
                                        <label>From Date</label><label style="color:#F00;">*</label>
                                    </div>
                                    <div style="width:185px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                        <input type="text" name="PS_FromDate" id="rp_frdate" value="<?php echo $_POST['PS_FromDate']; ?>" readonly />
                                    </div>

                                      <? if (($authen_row['usertype'])== 'Others') { 
                                        require_once 'second_div_element.php';

                                     }else{
                                        require_once 'first_div_element.php';
                                     }

                                      ?>
                                 	<!--Fromdate end-->
                                 </div>
                                <!--Row1 end-->
                                
								<!--Row1 2FIELD-->
                                <div style="float:left;width:400px;">
                                    <!--Todate -->
                                     <div style="width:145px; height:auto; float:left; margin-top:5px; margin-left:3px;">
                                        <label>To Date</label><label style="color:#F00;">*</label>
                                    </div>
                                   <div style="width:185px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                        <input type="text" name="PS_ToDate" id="rp_todate" value="<?php echo $_POST['PS_ToDate']; ?>" readonly />
                                    </div>

                                    <? if (($authen_row['usertype'])== 'Others') { 
                                        require_once 'first_div_element.php';

                                     }else{
                                        require_once 'second_div_element.php';
                                     }

                                      ?>

                                    <!--Todate end-->
                                 <!--Row1 2FIELD end-->
                                 </div>
                             <!-- col1 end-->     
                             </div>
                          <!-- main row 1 end-->     
                          </div>
                            
                          
                        <!-- main row 2 start-->     
                      
                          <!-- main row 3 start-->     
            
                              <!-- main row 4 start-->     
                       
					   
              
                     
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
if (isset($_POST['Get'])) {
    $table_data_height = " height:auto; ";
} ?>
                        <div style="width:917px; <?php echo $table_data_height ?> padding-bottom:8px; margin-top:20px; float:left; margin-left:10px; overflow:auto;" class="grid">

                            <table align="center" border="1" width="900px" cellpadding="20%"  bgcolor="#ffffFF">

                                <tr class="record_header" style="white-space:nowrap;">
                                    <td style="font-weight:bold; width:auto; text-align:center;">Region</td>
                                    <td style="font-weight:bold; width:auto; text-align:center;">Branch</td>
                                    <td style="font-weight:bold; width:auto; text-align:center;">Distributor Code</td>
                                    <td style="font-weight:bold; width:auto; text-align:center;">Distributor Name</td>
									  <td style="font-weight:bold; width:auto; text-align:center;">GRN Number</td>
									   <td style="font-weight:bold; width:auto; text-align:center;">GRN Date</td>
									    <!--<td style="font-weight:bold; width:auto; text-align:center;">PO No/DC No</td>-->
                                    <td style="font-weight:bold; width:auto; text-align:center;">Product Group</td>                                    
                                   <!-- <td style="font-weight:bold; width:auto; text-align:center;">Product Segment</td>  
                                     <td style="font-weight:bold; width:auto; text-align:center;">Product Type</td> -->
                                     <td style="font-weight:bold; width:auto; text-align:center;">Product Code</td>                                   
                                    <td style="font-weight:bold; width:auto; text-align:center;">Purchase Voucher Type</td>
                                    <td style="font-weight:bold; width:auto; text-align:center;">Quantity</td>
                                    <td style="font-weight:bold; width:auto; text-align:center;">Net Amount</td>
                                     <td style="font-weight:bold; width:auto; text-align:center;">Tax Amount</td>
									  <td style="font-weight:bold; width:auto; text-align:center;">Gross Amount</td>
                                </tr>



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
                                            $brn = $brn . "branchname = '" .$selectedOption1."'  OR ". "\n";
                                            }else{
                                                $brn = $brn1;
                                                break;
                                            }
                                        }
                                    }

									// foreach ($_POST['branch'] as $selectedOption1){
									// if($selectedOption1!="0")
									// 	{
									// 	$brn = $brn . "branchname = '" .$selectedOption1."'  OR ". "\n";
									// 	}
									// }
									
									if(isset($_POST['franchise'])) 
					                  {
					                    $fname1 = $fname;
					                    $fname=NULL;
	  									foreach ($_POST['franchise'] as $selectedOption2){
	  										if($selectedOption2!="0")
	  										{
	  										 	$fname = $fname . "franchisecode = '" .$selectedOption2."'  OR ". "\n";
					  						}else{
					                        	$fname = $fname1;
					                        	break;
					                      }
	  									}
					                  }
									
									foreach ($_POST['Productgroup'] as $selectedOption3){
										if($selectedOption3!="0")
										{
										$pgrp =  $pgrp. "pgroupname = '" .$selectedOption3."'  OR ". "\n";
										}
									}
									
									// foreach ($_POST['Productsegment'] as $selectedOption4){
									// 	if($selectedOption4!="0")
									// 	{
									// 	$pseg=  $pseg. "psegmentname = '" .$selectedOption4."'  OR ". "\n";
									// 	}
									// }
									
									// foreach ($_POST['Producttype'] as $selectedOption5){
									// 	if($selectedOption5!="0")
									// 	{
									// 	$ptype =  $ptype. "ptypename = '" .$selectedOption5."'  OR ". "\n";
									// 	}
									// }
									
									foreach ($_POST['productcode'] as $selectedOption6){
										if($selectedOption6!="0")
										{
										$pcode =  $pcode. "productcode = '" .$selectedOption6."'  OR ". "\n";
										}
									}
									foreach ($_POST['Voucher'] as $selectedOption7){
										if($selectedOption7!="0")
										{
										$pvouc = $pvouc. "vouchertype = '" .$selectedOption7."'  OR ". "\n";
										}
									}
									
									 	 $reg =	substr($reg, 0, -3);
										 $brn =	substr($brn, 0, -4);										
										 $fname =	substr($fname, 0, -4);
										 $pgrp =	substr($pgrp, 0, -4);
										 // $pseg =	substr($pseg, 0, -4);
										 // $ptype =	substr($ptype, 0, -4);
										 $pcode =	substr($pcode, 0, -4);
										 $pvouc =	substr($pvouc, 0, -4);
									
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
											if($pgrp!=NULL)
											{
												$grystr=$grystr. "(".$pgrp.") AND ";
											}
											/*if($pseg!=NULL)
											{
												$grystr=$grystr. "(".$pseg.") AND ";
											}
											if($ptype!=NULL)
											{
												$grystr=$grystr. "(".$ptype.") AND ";
											}*/
											if($pcode!=NULL)
											{
												$grystr=$grystr. "(".$pcode.") AND ";
											}
											if($pvouc!=NULL)
											{
												$grystr=$grystr. "(".$pvouc.") AND ";
											}
											
									$grystrres = substr($grystr, 0, -4);
									if($_POST['PS_FromDate']!="" && $_POST['PS_ToDate']!="")
									{
										$dbtodateto=date("Y-m-d",strtotime($_POST['PS_ToDate'])) ;
										$dbfromdatefrom=date('Y-m-d',strtotime($_POST['PS_FromDate']));
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
										$qry ="CALL r_purchasereport($dbfromdatefrom,$dbtodateto,$grystrres);";
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
										weblogfun("Report Access", "Purchase Report -> ".$pagename);
									$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
									$startpoint = ($page * $limit) - $limit;
									$statement = $_SESSION['form_controls'];
                                    // echo $statement;
									$qry_exec=mysql_query($statement);
									$myrow1 = mysql_num_rows($qry_exec);
									$starvalue = $myrow1;
									$record = array();
									while ($record[] = mysql_fetch_array($qry_exec));
									for($i=$startpoint;$i<$limit+$startpoint;$i++)
									{
										if($record[$i]['regionname']!=NULL)
										{
											$podate='';
											if($record[$i]['purchasedate'] != NULL)
											{
												$podate = date('d-m-Y',strtotime($record[$i]['purchasedate'])) ;
											}
											echo '<tr class="record_data" style="white-space:nowrap;">';
											echo '<td>' . $record[$i]['regionname'] . '</td>';
											echo '<td>' . $record[$i]['branchname'] . '</td>';
											echo '<td>' . $record[$i]['franchisecode'] . '</td>';
											echo '<td>' . $record[$i]['franchisename'] . '</td>';
											echo '<td>' . $record[$i]['purchasenumber'] . '</td>';
											echo '<td>' . $podate . '</td>';
											/*echo '<td>' . $record[$i]['PO'] . '</td>';*/
											echo '<td>' . $record[$i]['pgroupname'] . '</td>';
											/*echo '<td>' . $record[$i]['psegmentname'] . '</td>';
											echo '<td>' . $record[$i]['ptypename'] . '</td>';*/
											echo '<td>' . $record[$i]['productcode'] . '</td>';
											echo '<td>' . $record[$i]['vouchertype'] . '</td>';
											echo '<td>' . $record[$i]['quantity'] . '</td>';
											echo '<td>' . round($record[$i]['NetAmount'],2) . '</td>';
											echo '<td>' . round($record[$i]['taxamount'],2) . '</td>';
											echo '<td>' . round($record[$i]['grossamt'],2) . '</td>';
											echo '</tr>';
										}
									}
									if($myrow1==0)
									{
										unset($_SESSION['form_controls']);
								 		echo '<tr class="no_records"><td colspan="111" align="center">No Records Found</td></tr>';
									}
								}
                                ?>
							 </table>
							</div>
                        <br />
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
<?
}
?>

