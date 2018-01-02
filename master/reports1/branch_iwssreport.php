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
	$pagename = "Item Wise Sales Summary Report";
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
		
	
	// Authentication block ends 
		
	// Export function 
if (isset($_POST['Excel'])) {
	header('Location:Export_iwssreport.php');
    echo '<META HTTP-EQUIV="Refresh" Content="0; URL=Export_iwssreport.php">';
    exit;
}

if (isset($_POST['Cancel'])) {
	unset($_SESSION['form_controls']);
    ?>
    <script type="text/javascript">
        document.location='branch_iwssreport.php';
    </script>
    <?
}
include("inc/common_functions.php");
?>
 <script src="inc/multiselect.js" type="text/javascript"></script>

<title><?php echo $_SESSION['title']; ?> |&nbsp;Item Wise Sales Summary Report </title>
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
                <div style="width:930px; height:auto;   min-height: 180px; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;">
					<div style="width:930px; height:25px; float:left;  margin-left:0px;" class="head">
                            <p>Item Wise Sales Summary Report</p>
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
				      <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:45px;">
                                        <label>Period</label>
                                  </div>
						   <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
						   <select name='Period' id="Period" onChange="datefun();">
								<option value="0">-- Select --</option>
								<option value="Today">Today</option>
								<option value="Yesterday">Yesterday</option>
								<option value="Only Today">Only Today</option>
								<option value="Only Yesterday">Only Yesterday</option>
								<option value="Current Calender Year">Current Calender Year</option>
								<option value="Last Calender Year">Last Calender Year</option>
								<option value="Current Week">Current Week</option>
								<option value="Last Week">Last Week</option>
								<option value="Current Month">Current Month</option>
								<option value="Last Month">Last Month</option>
								<option value="Current Quarter">Current Quarter</option>
								<option value="Last Quarter">Last Quarter</option>
								<option value="Current Financial Year">Current Financial Year</option>
								<option value="Last Financial Year">Last Financial Year</option>
								<option value="Custom">Custom</option>
							</select>
							  </div>
							  <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:45px;">
                                    </div>
								  
								  <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:45px;">
                                        <label>Franchisee Name</label>
                                    </div>
                                    <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                       <select name='franchise[]' id='franchise' multiple="multiple" onChange="allfranchisee();">
                                            <option value="0">.All Franchisees.</option>
                                            <?
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
												$fname = $fname . "franchisename = '" . $row_list['Franchisename']."'  OR ". "\n";
                                            }
                                            ?>
                                        </select>
                                    </div>				
                                    <!--Row1 end-->
                                   
                                </div>
                                <div style="float:left;width:400px">
								
								<div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:45px;">
                                        <label>From Date</label><label style="color:#F00;">*</label>
                                  </div>
                                    <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                        <input type="hidden" name="SRP_FromDate" id="rp_frdate" value="<?php echo $_POST['SRP_FromDate']; ?>"/>
										<input type="text" name="SRP_FromDate" id="frdate" readonly="readonly" value="<?php echo $_POST['SRP_FromDate'];   ?>"/>
                                    </div>
									
                                 <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:45px;">
                                        <label>To Date</label><label style="color:#F00;">*</label>
                                  </div>
                                    <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                        <input type="hidden" name="SRP_ToDate" id="rp_todate" value="<?php echo $_POST['SRP_ToDate']; ?>" />
										<input type="text" name="SRP_ToDate" id="todate" readonly="readonly" value="<?php echo $_POST['SRP_ToDate']; ?>" />
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
								if(isset($_POST['franchise'])) 
									{
										$fname=NULL;								
										foreach ($_POST['franchise'] as $selectedOption2){
										if($selectedOption2!="0")
											{
											$fname = $fname . "franchisename = '" .$selectedOption2."'  OR ". "\n";
											}
										}
									}
									foreach ($_POST['productcode'] as $selectedOption6){
										if($selectedOption6!="0")
										{
										$pcode =  $pcode. "productcode = '" .$selectedOption6."'  OR ". "\n";
										}
									}
									$fname =	substr($fname, 0, -4);
									$pcode =	substr($pcode, 0, -4);
									if($fname!=NULL)
									{
										$grystr=$grystr. "(".$fname.") AND ";
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
											if($fname!=NULL)
											{
												$fname= str_replace("'","''",$fname);
												$fname= "'".$fname."'";
												
											}
											else
											{
												$fname= "'1'";
											}
										$dbfromdatefrom="'". $dbfromdatefrom."'";
										$dbtodateto="'".$dbtodateto."'";
										$qry ="CALL r_iwssreport($dbfromdatefrom,$dbtodateto,$grystrres,$fname);";
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
									 $columns_total = mysql_num_fields($qry_exec);
									$myrow1 = mysql_num_rows($qry_exec);
									$starvalue = $myrow1;
									$record = array();
									$headarray = array();
									$totalarray=array();?>
									
<table align="center"  bgcolor="#ffffFF" border="1" width="900px" cellpadding="20%">
                                <tr class="record_header" bgcolor="#8DB5F4" style="white-space:nowrap;">
														<?php		// Get The Field Name
for ($i = 0; $i < $columns_total; $i++) {
$heading = mysql_field_name($qry_exec, $i);
$headarray[$i]=$heading;
?>
 <td style="font-weight:bold; width:auto; text-align:center;"><?=$heading ?></td>
<? } ?>
                                </tr>
<?php
			// Get Records from the table
while ($record[] = mysql_fetch_array($qry_exec));
//while ($row = mysql_fetch_array($qry_exec)) 
//{
	for($ik=$startpoint;$ik<$limit+$startpoint;$ik++)
	{
	If($record[$ik][$headarray[0]]!= NULL)
	{
	?><tr class="record_data" style="white-space:nowrap;"><?php
	
for ($i = 0; $i < $columns_total; $i++) {
	
?>
<td style="font-weight:bold; width:auto; text-align:center;"><?=$record[$ik][$headarray[$i]]; ?></td>
<? } ?> </tr>
<? }
}
													
									
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

