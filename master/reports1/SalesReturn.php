<?php 
include '../../functions.php';
sec_session_start();
require_once '../../masterclass.php';
include("../../header.php");
	require_once '../../paginationfunction.php';
if(login_check($mysqli) == false) 
{
	header('Location:../../index.php');// Redirect to login page!
}

else
{

//This block to authenticate the user whether he has the rights to access this page 
	$pagename = "Sales Returns";
	require_once 'Authentication_Rights.php';
    
	// Authentication block ends 
		
	// Export function 
if (isset($_POST['Excel'])) 
{
   header('Location:Export_SalesReturn.php');
   echo '<META HTTP-EQUIV="Refresh" Content="0; URL=Export_SalesReturn.php">';
   exit;
}

//echo $qry;

if (isset($_POST['Cancel']))
 {
unset($_SESSION['form_controls']);
    ?>
    <script type="text/javascript">
        document.location='SalesReturn.php';
    </script>
    <?
}
include("inc/common_functions.php");
?> 

<script src="inc/multiselect.js" type="text/javascript"></script>

<link href="reportstyle.css" rel="stylesheet" type="text/css" />

<title><?php echo $_SESSION['title']; ?> |&nbsp;Sales Returns</title>
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
                            <p>Sales Returns </p>
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
} 
?>
                        <div style="width:917px; <?php echo $table_data_height ?> padding-bottom:8px; margin-top:20px; float:left; margin-left:10px; overflow:auto;" class="grid">

                            <table align="center" border="1" cellpadding="20%"  bgcolor="#ffffFF">

                                <tr class="record_header" style="white-space:nowrap;">
                                    <td style="font-weight:bold; width:auto; text-align:center;">Region</td>
                                    <td style="font-weight:bold; width:auto; text-align:center;">Branch</td>
                                    <td style="font-weight:bold; width:auto; text-align:center;">Primary Distributor</td>
                                    <td style="font-weight:bold; width:auto; text-align:center;">Distributor Code</td>
                                    <td style="font-weight:bold; width:auto; text-align:center;">Distributor Name</td>
                                     <td style="font-weight:bold; width:auto; text-align:center;">Sales Return No</td>
                                     <td style="font-weight:bold; width:auto; text-align:center;">Sales Return Date</td>
                                     <td style="font-weight:bold; width:auto; text-align:center;">Customer Name</td>
                                      <td style="font-weight:bold; width:auto; text-align:center;">Location</td>
                                     <td style="font-weight:bold; width:auto; text-align:center;">Tertiary Code</td>
                                    <td style="font-weight:bold; width:auto; text-align:center;">Product Group</td>                                    
                                   <!-- <td style="font-weight:bold; width:auto; text-align:center;">Product Segment</td>  
                                     <td style="font-weight:bold; width:auto; text-align:center;">Product Type</td>-->
                                     <td style="font-weight:bold; width:auto; text-align:center;">Product Code</td> 
                                     <td style="font-weight:bold; width:auto; text-align:center;">Product Description</td>                                    											
                                     <td style="font-weight:bold; width:auto; text-align:center;">Voucher Type</td>
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
									 if(isset($_POST['primaryfranchise'])) {
                                    	$pfname = "";
	                                    foreach ($_POST['primaryfranchise'] as $selectedOption3){
											if($selectedOption3!="0")
											{
											$pfname =  $pfname. "pd_code = '" .$selectedOption3."'  OR ". "\n";	
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
	  										 	$fname = $fname . "franchisename = '" .$selectedOption2."'  OR ". "\n";
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
									
									/*foreach ($_POST['Productsegment'] as $selectedOption4){
										if($selectedOption4!="0")
										{
										$pseg=  $pseg. "psegmentname = '" .$selectedOption4."'  OR ". "\n";
										}
									}
									
									foreach ($_POST['Producttype'] as $selectedOption5){
										if($selectedOption5!="0")
										{
										$ptype =  $ptype. "ptypename = '" .$selectedOption5."'  OR ". "\n";
										}
									}*/
									
									foreach ($_POST['productcode'] as $selectedOption6){
										if($selectedOption6!="0")
										{
										$pcode =  $pcode. "productcode = '" .$selectedOption6."'  OR ". "\n";
										}
									}
									
								/*	foreach ($_POST['Voucher'] as $selectedOption7){
										if($selectedOption7!="0")
										{
										$pvouc = $pvouc. "VoucherType = '" .$selectedOption7."'  OR ". "\n";
										}
									}
								*/	
									 	 $reg =	substr($reg, 0, -3);
										 $brn =	substr($brn, 0, -4);										
										 $fname =	substr($fname, 0, -4);
										 $pgrp =	substr($pgrp, 0, -4);
										 $pseg =	substr($pseg, 0, -4);
										 $ptype =	substr($ptype, 0, -4);
										 $pcode =	substr($pcode, 0, -4);
										 $pfname = substr($pfname, 0, -4);
									//	 $pvouc =	substr($pvouc, 0, -4);
										
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
											if($pfname!=NULL)
											{
												$grystr=$grystr. "(".$pfname.") AND ";
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
									/*		if($pvouc!=NULL)
											{
												$grystr=$grystr. "(".$pvouc.") AND ";
											}
									*/		
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
										$qry ="CALL r_salereturns($dbfromdatefrom,$dbtodateto,$grystrres);";
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
										weblogfun("Report Access", "Stock Report -> ".$pagename);
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
											$salesdate='';
											if($record[$i]['salesRetdates'] != NULL)
											{
												$salesdate = date('d-m-Y',strtotime($record[$i]['salesRetdates'])) ;
											}
											echo '<tr class="record_data" style="white-space:nowrap;">';
											echo '<td>' . $record[$i]['regionname'] . '</td>';
											echo '<td>' . $record[$i]['branchname'] . '</td>';
											echo '<td>' . $record[$i]['pd_code'] . '</td>';
											echo '<td>' . $record[$i]['franchisecode'] . '</td>';
											echo '<td>' . $record[$i]['franchisename'] . '</td>';
											echo '<td>' . $record[$i]['salesRetno'] . '</td>';
											echo '<td>' . $salesdate . '</td>';
											echo '<td>' . $record[$i]['retailername'] . '</td>';
											echo '<td>' . $record[$i]['location'] . '</td>';
											echo '<td>' . $record[$i]['tertiary_code'] . '</td>';
											echo '<td>' . $record[$i]['pgroupname'] . '</td>';
											/*echo '<td>' . $record[$i]['psegmentname'] . '</td>';
											echo '<td>' . $record[$i]['ptypename'] . '</td>';*/
											echo '<td>' . $record[$i]['productcode'] . '</td>';
											echo '<td>' . $record[$i]['productdes'] . '</td>';
											echo '<td>' . $record[$i]['VoucherType'] . '</td>';
											echo '<td>' . $record[$i]['quantity'] . '</td>';
											echo '<td>' . $record[$i]['amount'] . '</td>';
											echo '<td>' . $record[$i]['TaxAmount'] . '</td>';
											echo '<td>' . $record[$i]['grossamt'] . '</td>';
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
              <!--Main row 2 end-->
</div>
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

