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
	$validuser = $_SESSION['username'];
	$authen_qry =mysql_query( "select access_right,usertype from reportrights where userid = '$validuser' and r_screen = '$pagename'");
	$authen_row = mysql_fetch_array($authen_qry);
	if (($authen_row['access_right'])== 'No')
	{
		header("location:/".$_SESSION['mainfolder']."/home/home/master.php");
	}
	// assigned branch 
	$branch_qry =mysql_query( "select branch from reportrights_sub where userid = '".$_SESSION['username']."'") or mysql_error();
		while ($branch_row = mysql_fetch_array($branch_qry)) {
			$authen_branch = $authen_branch ."'". $branch_row['branch']."', ";
			}
		$authen_branch =	substr($authen_branch, 0, -2);
		$authen_branch = "(".$authen_branch.")";
		
		$general_qry =mysql_query( "SELECT branchname FROM branch WHERE branchcode IN $authen_branch") or mysql_error();
		while ($general_row = mysql_fetch_array($general_qry)) {
			$brn = $brn . "branchname = '" . $general_row['branchname']."'  OR ". "\n";
			}
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
          <table id="default" style=" height:10px; display:none;" >
            <tr>
                <td>
                                    <select  name="forlist" id="forlist">
                                     <?
                                                                                
                                        $que = mysql_query("SELECT branchname,Franchisename FROM `view_rptfrnfin`");
                                       
                                     while( $record = mysql_fetch_array($que))
                                     {
									        
                                      echo "<option value=\"".$record['branchname']."~".$record['Franchisename']."\">".$record['branchname']."~".$record['Franchisename']."\n "; 
									  }
                              		 
                                    ?>
                                          </select>
                                      </td>
                                       <td>
                                    <select  name="productslist1" id="productslist1">
                                     <?
                                                                                
                                        $que = mysql_query("SELECT Distinct(ProductGroup),psegmentname FROM `view_rptproductfin`");
                                       
                                     while( $record = mysql_fetch_array($que))
                                     {   
                                      //echo "<option value=\"".$record['pgroupname']."~".$record['psegmentname']."\">".$record['pgroupname']."~".$record['psegmentname']."\n "; 
									   echo "<option value=\"".$record['ProductGroup']."~".$record['psegmentname']."\">".$record['ProductGroup']."~".$record['psegmentname']."\n "; 
									  }
                              		 
                                    ?>
                                          </select>
                                      </td>
                                      
                                      <td>
                                    <select  name="productsseglist1" id="productsseglist1">
                                     <?
                                                                                
                                     $que = mysql_query("SELECT Distinct(psegmentname),ProductTypeName FROM `view_rptproductfin`");
                                       
                                     while( $record = mysql_fetch_array($que))
                                     {   
                                     echo "<option value=\"".$record['psegmentname']."~".$record['ProductTypeName']."\">".$record['psegmentname']."~".$record['ProductTypeName']."\n "; 
									 }
                              		 
                                    ?>
                                          </select>
                                      </td>
                                   
                                      </tr>
                                      <tr>
                                             <td>
                                    <select  name="productscodlist" id="productscodlist">
                                     <?
                                                                                
                                        $que = mysql_query("SELECT ProductTypeName,ProductCode FROM `view_rptproductfin`");
                                       
                                     while( $record = mysql_fetch_array($que))
                                     {   
                                      echo "<option value=\"".$record['ProductTypeName']."~".$record['ProductCode']."\">".$record['ProductTypeName']."~".$record['ProductCode']."\n "; 
									  }
                              		 
                                    ?>
                                          </select>
                                      </td>
                                      </tr>
                                     
                                     
                                      
            </table>
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
 								<div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:45px;">
                                        <label>From Date</label><label style="color:#F00;">*</label>
                                  </div>
                                    <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                        <input type="text" name="SRP_FromDate" id="rp_frdate" value="<?php echo $_POST['SRP_FromDate']; ?>" readonly />
                                    </div>
                                    <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:45px;">
                                        <label>Branch</label>
                                  </div>
                                    <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                        <select name='branch[]' id='branch' multiple="multiple" onChange="drpfunc1();">
                                            <option value="0">.All Branches.</option>
                                            <?php
                                            $add_qry = '';
                                            
                                            $branch_select = ($_POST['branch']) ? $_POST['branch'] : '';

                                            $list = mysql_query("SELECT branchcode, branchname FROM branch WHERE branchcode IN $authen_branch order by branchname asc");
                                            while ($row_list = mysql_fetch_assoc($list)) {
                                                $selected = '';

                                                if ($row_list['branchname'] == $branch_select) {
                                                    $selected = ' selected ';
                                                }
                                                ?>
                                                <option value="<? echo $row_list['branchname']; ?>"<? echo $selected; ?>>
    <? echo $row_list['branchname']; ?>
                                                </option>

                                                <?
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:45px;">
                                        <label>Productgroup</label>
                                    </div>
                                    <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                        <select name='Productgroup[]' id = 'Productgroup' multiple="multiple" onChange="drpfunc2();">
                                            <option value="0">.All Productgroup.</option>
                                            <?
                                            $Productgroup_select = ($_POST['Productgroup']) ? $_POST['Productgroup'] : '';
										
                                            
										   $qry="SELECT ProductGroup FROM productgroupmaster ORDER BY ProductGroup asc";
										   
										   $list1 = mysql_query($qry);
                                            while ($row_list1 = mysql_fetch_assoc($list1)) {
                                                $selected = '';

                                                if ($row_list1['ProductGroup'] == $Productgroup_select) {
                                                    $selected = ' selected ';
                                                }
                                                ?>
												  <option value="<? echo $row_list1['ProductGroup']; ?>"<? echo $selected; ?>>
                                                    <? echo $row_list1['ProductGroup']; ?>                                         	
                                                </option>

                                                <?
                                            }
                                            ?>
                                        </select>
 

                                    </div>
                                    <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:45px;">
                                        <label>ProductType</label>
                                    </div>
                                    <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                        <select name='Producttype[]' id="Producttype" multiple="multiple" onChange="drpfunc4();">
                                            <option value="0">.All Producttype.</option>
                                            <?
                                            $Producttype_select = ($_POST['Producttype']) ? $_POST['Producttype'] : '';
											
										   $list = mysql_query("SELECT distinct (ProductTypeName)as ProductTypeName FROM producttypemaster ORDER BY ProductTypeName asc");
                                            while ($row_list = mysql_fetch_assoc($list)) {
                                                $selected = '';

                                                if ($row_list['ProductTypeName'] == $Producttype_select) {
                                                    $selected = ' selected ';
                                                }
                                                ?>
												
                                                
                                                   <option value="<? echo $row_list['ProductTypeName']; ?>"<? echo $selected; ?>>
                                                    <? echo $row_list['ProductTypeName']; ?> 
														
                                                </option>

                                                <?
                                            }
                                            ?>
                                        </select>
                                    </div>   
                                </div>
                                <!-- col1 end -->
                                <div style="float:left;width:400px">
                                 <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:45px;">
                                        <label>To Date</label><label style="color:#F00;">*</label>
                                  </div>
                                    <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                        <input type="text" name="SRP_ToDate" id="rp_todate" value="<?php echo $_POST['SRP_ToDate']; ?>" readonly />
                                    </div>
                                 <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:45px;">
                                        <label>Franchisee Name</label>
                                    </div>
                                    <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                        <select name='franchise[]' id='franchise' multiple="multiple" onChange="allfranchisee();">
                                            <option value="0">.All Franchisees.</option>
                                            <?
                                            if ($branch_select) {
                                                $add_qry .= " AND Branch = '" . $branch_select."'";
                                            }

                                            $franchise_select = ($_POST['franchise']) ? $_POST['franchise'] : '';
                                            $list = mysql_query("SELECT Franchisecode, Franchisename FROM franchisemaster WHERE Branch IN $authen_branch order by Franchisename asc");
                                            while ($row_list = mysql_fetch_assoc($list)) {
                                                $selected = '';
                                                if ($row_list['Franchisename'] == $franchise_select) {
                                                    $selected = ' selected ';
                                                }
                                                ?>
                                                <option value="<? echo $row_list['Franchisename']; ?>" <? echo $selected; ?>><? echo trim($row_list['Franchisename']); ?></option>
                                                <?
                                            }
                                            ?>
                                        </select>
                                    </div>									
									<div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:45px;">
                                        <label>Productsegment</label>
                                    </div>
									
                                    <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                       <select name='Productsegment[]' id = 'Productsegment' multiple="multiple" onChange="drpfunc3();">
                                            <option value="0">.All Productsegment.</option>
                                            <?
                                            $Productsegment_select = ($_POST['Productsegment']) ? $_POST['Productsegment'] : '';
											//$qry="SELECT distinct (pgroupname)as pgroupname FROM view_productdtetails ORDER BY pgroupname DESC";
                                        
										   $list2 = mysql_query("SELECT distinct (ProductSegment)as ProductSegment FROM productsegmentmaster ORDER BY ProductSegment asc");
                                            while ($row_list2 = mysql_fetch_assoc($list2)) {
                                                $selected = '';

                                                if ($row_list2['ProductSegment'] == $Productsegment_select) {
                                                    $selected = ' selected ';
                                                }
                                                ?>
												
                                                <option value="<? echo $row_list2['ProductSegment']; ?>"<? echo $selected; ?>>
                                                    <? echo $row_list2['ProductSegment']; ?> 
                                                
														
                                                </option>

                                                <?
                                            }
                                            ?>
                                      </select>
 

                                    </div>
									
									
                                    <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:45px;">
                                        <label>Product Code</label>
                                    </div>
                                    <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                        <select name='productcode[]' id="productcode" multiple="multiple" onChange="allproductcode();">
                                            <option value="0">.All Products.</option>
                                            <?
                                            $product_select = ($_POST['productcode']) ? $_POST['productcode'] : '';

                                            $list = mysql_query("SELECT distinct (ProductCode) FROM productmaster WHERE ProductCode !='' order by ProductCode asc");
                                            while ($row_list = mysql_fetch_assoc($list)) {
                                                $selected = '';

                                                if ($row_list['ProductCode'] == $product_select) {
                                                    $selected = ' selected ';
                                                }
                                                ?>
                                                <option value="<? echo $row_list['ProductCode']; ?>"<? echo $selected; ?>>
                                                    <? echo $row_list['ProductCode']; ?>
                                                </option>

                                                <?
                                            }
                                            ?>
                                        </select>


                                    </div>
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
                                    <td style="font-weight:bold; width:auto; text-align:center;">Franchisee Code</td>
                                    <td style="font-weight:bold; width:auto; text-align:center;">Franchisee Name</td>
                                     <td style="font-weight:bold; width:auto; text-align:center;">Sales Return No</td>
                                     <td style="font-weight:bold; width:auto; text-align:center;">Sales Return Date</td>
                                     <td style="font-weight:bold; width:auto; text-align:center;">Customer Name</td>
                                    <td style="font-weight:bold; width:auto; text-align:center;">Product Group</td>                                    
                                    <td style="font-weight:bold; width:auto; text-align:center;">Product Segment</td>  
                                     <td style="font-weight:bold; width:auto; text-align:center;">Product Type</td>
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
									
									if(isset($_POST['branch'])) 
									{
										$brn=NULL;								
										foreach ($_POST['branch'] as $selectedOption1){
										if($selectedOption1!="0")
											{
											$brn = $brn . "branchname = '" .$selectedOption1."'  OR ". "\n";
											}
										}
									}
									foreach ($_POST['franchise'] as $selectedOption2){
										if($selectedOption2!="0")
										{
										$fname = $fname . "franchisename = '" .$selectedOption2."'  OR ". "\n";
										}
									}
									
									foreach ($_POST['Productgroup'] as $selectedOption3){
										if($selectedOption3!="0")
										{
										$pgrp =  $pgrp. "pgroupname = '" .$selectedOption3."'  OR ". "\n";									
										}
									}
									
									foreach ($_POST['Productsegment'] as $selectedOption4){
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
									}
									
									foreach ($_POST['productcode'] as $selectedOption6){
										if($selectedOption6!="0")
										{
										$pcode =  $pcode. "productcode = '" .$selectedOption6."'  OR ". "\n";
										}
									}
									
										 $brn =	substr($brn, 0, -4);										
										 $fname =	substr($fname, 0, -4);
										 $pgrp =	substr($pgrp, 0, -4);
										 $pseg =	substr($pseg, 0, -4);
										 $ptype =	substr($ptype, 0, -4);
										 $pcode =	substr($pcode, 0, -4);
										
											
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
											if($pseg!=NULL)
											{
												$grystr=$grystr. "(".$pseg.") AND ";
											}
											if($ptype!=NULL)
											{
												$grystr=$grystr. "(".$ptype.") AND ";
											}
											if($pcode!=NULL)
											{
												$grystr=$grystr. "(".$pcode.") AND ";
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
											echo '<td>' . $record[$i]['franchisecode'] . '</td>';
											echo '<td>' . $record[$i]['franchisename'] . '</td>';
											echo '<td>' . $record[$i]['salesRetno'] . '</td>';
											echo '<td>' . $salesdate . '</td>';
											echo '<td>' . $record[$i]['retailername'] . '</td>';
											echo '<td>' . $record[$i]['pgroupname'] . '</td>';
											echo '<td>' . $record[$i]['psegmentname'] . '</td>';
											echo '<td>' . $record[$i]['ptypename'] . '</td>';
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

