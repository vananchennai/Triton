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
	$pagename = "Non Moving Product";
	// Authentication block ends 
     require_once 'Authentication_Rights.php';
if (isset($_POST['Cancel']))
 {
unset($_SESSION['form_controls']);
    ?>
    <script type="text/javascript">
        document.location='NonMovingProduct.php';
    </script>
    <?
}
if (isset($_POST['Excel'])) 
{
  // $_SESSION['export_header'] = '"Franchisee Code","Franchisee Name","Product Code","Product Description","Product Group","Quantity",';
  $_SESSION['filename'] = 'NonMovingProduct-';
  header('Location:Export_Reports.php');
  echo '<META HTTP-EQUIV="Refresh" Content="0; URL=Export_Reports.php">';
  exit;
}
include("inc/common_functions.php");
?> 

<script src="inc/multiselect.js" type="text/javascript"></script>

<link href="reportstyle.css" rel="stylesheet" type="text/css" />

<title><?php echo $_SESSION['title']; ?> |&nbsp;Non Moving Product Report</title>
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
                            <p>Non Moving Product Report </p>
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
                                        require_once 'second_moving_div_element.php';

                                     }else{
                                        require_once 'first_moving_div_element.php';
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
                                        require_once 'first_moving_div_element.php';

                                     }else{
                                        require_once 'second_moving_div_element.php';
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

                                <!-- <tr class="record_header" style="white-space:nowrap;"> -->
                                    <!-- <td style="font-weight:bold; width:auto; text-align:center;">Region</td>
                                    <td style="font-weight:bold; width:auto; text-align:center;">Branch</td>
                                    <td style="font-weight:bold; width:auto; text-align:center;">Primary Distributor</td>
                                    -->
                                     <!-- <td style="font-weight:bold; width:auto; text-align:center;">Distributor Code</td> -->
                                    <!-- <td style="font-weight:bold; width:auto; text-align:center;">Distributor Name</td> -->
                                  <!--    <td style="font-weight:bold; width:auto; text-align:center;">Invoice No</td>
                                     <td style="font-weight:bold; width:auto; text-align:center;">Invoice Date</td>
                                     <td style="font-weight:bold; width:auto; text-align:center;">Customer Name</td>
                                     <td style="font-weight:bold; width:auto; text-align:center;">Location</td>
                                     <td style="font-weight:bold; width:auto; text-align:center;">Tertiary Code</td> -->
                                    <!-- <td style="font-weight:bold; width:auto; text-align:center;">Product Group</td>                                     -->
                                    <!--<td style="font-weight:bold; width:auto; text-align:center;">Product Segment</td>  
                                     <td style="font-weight:bold; width:auto; text-align:center;">Product Type</td>-->
                                     <!-- <td style="font-weight:bold; width:auto; text-align:center;">Product Code</td>  -->
                                     <!-- <td style="font-weight:bold; width:auto; text-align:center;">Product Description</td>                                    											 -->
                                     <!-- <td style="font-weight:bold; width:auto; text-align:center;">Sales Voucher Type</td> -->
                                    <!-- <td style="font-weight:bold; width:auto; text-align:center;">Quantity</td> -->
                            <!--         <td style="font-weight:bold; width:auto; text-align:center;">Net Amount</td>
                                     <td style="font-weight:bold; width:auto; text-align:center;">Tax Amount</td>
                                      <td style="font-weight:bold; width:auto; text-align:center;">Gross Amount</td> -->
                                <!-- </tr> -->



                                <?php
								
								
								
								
								
                                if (isset($_POST['Get'])) 
								{
									
									foreach ($_POST['region'] as $selectedOption){
										if($selectedOption!="0")
										{
									  	$reg =  $reg ."regionname = '" .$selectedOption."'  OR ";
									  	$regions = "Region='".$selectedOption."'";
										}
									}
									
									if(isset($_POST['branch'])){
                                        $brn1 = $brn;
                                        $brn = NULL;
                                        foreach ($_POST['branch'] as $selectedOption1){
                                            if($selectedOption1!="0")
                                            {
                                            $brn = $brn . "branchname = '" .$selectedOption1."'  OR ". "\n";
                                            $branchs = "Branch='".$selectedOption1."'";
                                            }else{
                                                $brn = $brn1;
                                                break;
                                            }
                                        }
                                    }
									//  if(isset($_POST['primaryfranchise'])) {
         //                            	$pfname = "";
	        //                             foreach ($_POST['primaryfranchise'] as $selectedOption3){
									// 		if($selectedOption3!="0")
									// 		{
									// 		$pfname =  $pfname. "pd_code = '" .$selectedOption3."'  OR ". "\n";	
									// 		}
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
	  										 	$frans= "Franchisecode='".$selectedOption2."'";
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
										$prodgrp =" WHERE p.ProductGroupCode='".$selectedOption3."'";								
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
									
									// foreach ($_POST['productcode'] as $selectedOption6){
									// 	if($selectedOption6!="0")
									// 	{
									// 	$pcode =  $pcode. "productcode = '" .$selectedOption6."'  OR ". "\n";
									// 	}
									// }
									
									// foreach ($_POST['Voucher'] as $selectedOption7){
									// 	if($selectedOption7!="0")
									// 	{
									// 	$pvouc = $pvouc. "VoucherType = '" .$selectedOption7."'  OR ". "\n";
									// 	}
									// }
									
									 	 $reg =	substr($reg, 0, -3);
										 $brn =	substr($brn, 0, -4);										
										 $fname =	substr($fname, 0, -4);
										 $pgrp =	substr($pgrp, 0, -4);
										 $pseg =	substr($pseg, 0, -4);
										 $ptype =	substr($ptype, 0, -4);
										 // $pcode =	substr($pcode, 0, -4);
										 // $pvouc =	substr($pvouc, 0, -4);
										 // $pfname = substr($pfname, 0, -4);
											if($reg!=NULL)
											{
												$grystr = "(".$reg.") AND ";
											}
											
											if($brn!=NULL)
											{
												$grystr=$grystr. "(".$brn.") AND ";
											}
											// if($pfname!=NULL)
											// {
											// 	$grystr=$grystr. "(".$pfname.") AND ";
											// }
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
											// if($pcode!=NULL)
											// {
											// 	$grystr=$grystr. "(".$pcode.") AND ";
											// }
											// if($pvouc!=NULL)
											// {
											// 	$grystr=$grystr. "(".$pvouc.") AND ";
											// }
											
									  $grystrres = substr($grystr, 0, -4);
									  $limit_cond = $_POST['Limit'];
									  // echo $limit;
									  // if($limit_cond == 0 || $limit_cond == "0"){
									  // 	$limit_cond = "''";
									  // }else{
									  // 	$limit_cond = "LIMIT 0,".$limit_cond;
									  // 	$limit_cond="'".$limit_cond."'";
									  // }
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
										$reporttype ="'N'";
										//$qry = "SELECT rs.franchisecode As `Distributor Code`,rs.franchisename As `Distributor Name`,rs.productcode As `Product Code` ,rs.productdes As `Product Description`, pg.ProductGroup AS `Product Group`,sum(rs.quantity) AS Quantity FROM r_salesreport rs LEFT JOIN productgroupmaster pg ON pg.ProductCode=rs.pgroupname WHERE voucherstatus='ACTIVE' AND salesdates BETWEEN ".$dbfromdatefrom." AND ".$dbtodateto." AND ".$grystrres." GROUP BY `Product Code` ORDER BY Quantity DESC ". $limit_cond;
										 $qry ="CALL r_fsmoving($dbfromdatefrom,$dbtodateto,$grystrres,'',$reporttype);";
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
									weblogfun("Report Access", "Sales Dashboard -> ".$pagename);
									$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
									$startpoint = ($page * $limit) - $limit;
									$statement = $_SESSION['form_controls'];
									if($frans==NULL)
									{
									 $frans = '1'; 
									}
									if($branchs==NULL)
									{
									 $branchs = '1'; 
									}
									if($regions==NULL)
									{
									 $regions = '1'; 
									}
									
									$stmtqry = "SELECT p.ProductCode, p.ProductDescription,pg.ProductGroup FROM productmaster p LEFT JOIN productgroupmaster pg ON p.ProductGroupCode=pg.ProductCode ".$prodgrp;
									$stmtexe = mysql_query($stmtqry);
									// echo $stmtqry;
									// exit;
									$prodrow = mysql_num_rows($stmtexe);
									$productrow = array();
									while($productrow[] = mysql_fetch_array($stmtexe));
									// var_dump($productrow);
									$frnqry = "SELECT Franchisecode,Franchisename FROM franchisemaster WHERE $regions AND $branchs AND $frans order by Franchisecode ";
									// echo $frnqry;
									$frnexe = mysql_query($frnqry);
									$frnrows = mysql_num_rows($frnexe);
									$frnrow = array();
									while ($frnrow[] = mysql_fetch_array($frnexe));
									// exit;
                                    // echo $statement;	
                                    
									$qry_exec=mysql_query($statement);
									$myrow1 = mysql_num_rows($qry_exec);
									$starvalue = $myrow1;
									$record = array();
									$currentfrncode = '';
									$prevfrncode = '';
									while ($record[] = mysql_fetch_array($qry_exec));
									$output .='"Franchisee Code","Franchisee Name","Product Group","Product Code","Product Description",';
									// for($i=0;$i<$myrow1;$i++)
									// if($myrow1>0){
									// exit;
									$output .="\n";
									$flag = 0;
									// echo $productrow[0]['ProductGroup'];
									// $productrow[0]['ProductCode'];
									// $productrow[0]['ProductDescription'];
									// exit;
									$procod = array();
									$count = 0;
									for($f=0;$f<$frnrows;$f++){
										$franchisesel = $frnrow[$f]['Franchisecode'];
										$franchisenamesel =$frnrow[$f]['Franchisename'];
										$procod = array();
										for($i=0,$k=0;$i<$myrow1;$i++){
											$currentfrncode = $record[$i]['franchisecode'];
											$currentproductcode = $record[$i]['Product Code'];
											if($currentfrncode == $franchisesel){
												$flag++;
												$procod[$k] = $currentproductcode;
												$k++;
											}
										}
										if($flag != 0){
											for($j=0;$j<$prodrow;$j++){
												if(!in_array($productrow[$j]['ProductCode'],$procod)){
													
													$output .= '"'.$franchisesel.'",';
													$output .= '"'.$franchisenamesel.'",';
													$output .= '"'.$productrow[$j]['ProductGroup'].'",';
													$output .= '"'.$productrow[$j]['ProductCode'].'",';
													$output .= '"'.$productrow[$j]['ProductDescription'].'"';
													// $count++;
												$output .="\n";
												}
											}
										}
										else{
											for($j=0;$j<$prodrow;$j++){
													
												$output .= '"'.$franchisesel.'",';
												$output .= '"'.$franchisenamesel.'",';
												$output .= '"'.$productrow[$j]['ProductGroup'].'",';
												$output .= '"'.$productrow[$j]['ProductCode'].'",';
												$output .= '"'.$productrow[$j]['ProductDescription'].'"';
												$output .="\n";
											}
										}
										$flag = 0;
									}
									$_SESSION['filename'] = 'NonMovingProduct-';
									$_SESSION['form_controls1']= $output;
									// echo $output;
									// exit;
				                    header('Location:Export_NonmovingProduct.php');
				                    echo '<META HTTP-EQUIV="Refresh" Content="0; URL=Export_NonmovingProduct.php">';
								// 		for($i=$startpoint;$i<$limit+$startpoint;$i++)
								// 		{
											
								// 				echo '<tr class="record_data" style="white-space:nowrap;">';
								// 				// echo '<td>' . $record[$i]['regionname'] . '</td>';
								// 				// echo '<td>' . $record[$i]['branchname'] . '</td>';
								// 				// echo '<td>' . $record[$i]['pd_code'] . '</td>';
								// 				// echo '<td>' . $record[$i]['Distributor Code'] . '</td>';
								// 				// echo '<td>' . $record[$i]['Distributor Name'] . '</td>';
								// 				// echo '<td>' . $record[$i]['salesno'] . '</td>';
								// 				// echo '<td>' . $salesdate . '</td>';
								// 				// echo '<td>' . $record[$i]['retailername'] . '</td>';
								// 				// echo '<td>' . $record[$i]['location'] . '</td>';
								// 				// echo '<td>' . $record[$i]['tertiary_code'] . '</td>';
								// 				echo '<td>' . $record[$i]['ProductGroup'] . '</td>';
								// 				/*echo '<td>' . $record[$i]['psegmentname'] . '</td>';
								// 				echo '<td>' . $record[$i]['ptypename'] . '</td>';*/
								// 				echo '<td>' . $record[$i]['ProductCode'] . '</td>';
								// 				echo '<td>' . $record[$i]['ProductDescription'] . '</td>';
								// 				// echo '<td>' . $record[$i]['VoucherType'] . '</td>';
								// 				// echo '<td>' . $record[$i]['Quantity'] . '</td>';
								// 				// echo '<td>' . $record[$i]['amount'] . '</td>';
								// 				// echo '<td>' . $record[$i]['TaxAmount'] . '</td>';
								// 				// echo '<td>' . $record[$i]['grossamt'] . '</td>';
								// 				echo '</tr>';
								// 		}
								// 	}
								// 	// if($myrow1==0)
								// 	else	
								// 	{
								// 		unset($_SESSION['form_controls']);
								//  		echo '<tr class="no_records"><td colspan="111" align="center">No Records Found</td></tr>';
								// 	}
								}
                                ?>


                            </table>


                        </div>
                        <br />
                     <?
						

        //                	if(!empty($_SESSION['form_controls']))
								// {
								// 	echo pagination($starvalue,$statement,$limit,$page);
									?>

                        <!-- <div style="width:260px; height:50px; float:right;  margin-right:15px; margin-top:0px;" class="cont" >

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
                            </div ></div> -->
                        <?php //} ?>
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

<script type="text/javascript">
  $('#get_report_btn').click(function(){
  	var franchise_select = $('#franchise').val();
  	var region_select = $('#region').val();
  	var branch_select = $('#branch').val();
  	var Productgroup_select = $('#Productgroup').val();
  	if(franchise_select== "0" && region_select=="0" && branch_select=="0" && Productgroup_select=="0"){
  		alert("Enter Mandatory Fields !");
      	return false;
  	}else{
  		return true;
  	}

  });
</script>