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
	$pagename = "ServiceCallRegister";
	$validuser = $_SESSION['username'];
	$authen_qry =mysql_query( "select access_right,usertype from reportrights where userid = '$validuser' and r_screen = '$pagename'");
	$authen_row = mysql_fetch_array($authen_qry);
	if (($authen_row['access_right'])== 'No')
	{
		header("location:/".$_SESSION['mainfolder']."/home/home/master.php");
	}
	if (($authen_row['usertype'])== 'Others')
	{
		header("location:branch_ServicecallRegister.php");
	}
	// Authentication block ends 
		
	// Export function 
if (isset($_POST['Excel'])) 
{
		header('Location:Export_SCRegister.php');
        echo '<META HTTP-EQUIV="Refresh" Content="0; URL=Export_SCRegister.php">';
        exit;
}

if (isset($_POST['Cancel'])) {
	unset($_SESSION['form_controls']);
    ?>
    <script type="text/javascript">
        document.location='ServicecallRegister.php';
    </script>
    <?
}
include("inc/common_functions.php");
?> 
<script src="inc/multiselect.js" type="text/javascript"></script>

<link href="reportstyle.css" rel="stylesheet" type="text/css" />
<title><?php echo $_SESSION['title']; ?> |&nbsp;Service Call Register </title>
</head>

<body><center>

 <?php include("../../menu.php") ?>

    <!--Third Block - Container-->
    <div style="width:100%; height:auto; float:none;">
        <div style="width:980px; height:auto; float:none; margin-top:8px;" class="container">

            <div style="width:950px; height:auto; float:left; margin-bottom:10px; margin-left:15px;" id="center">  
                <!-- form id start-->
                <form method="POST" action="<?php $_PHP_SELF ?>">
                <table id="default" style=" height:10px; display:none;" >
            <tr>
                <td>
                                    <select  name="emplist" id="emplist">
                                     <?
                                                                                
                                        $que = mysql_query("SELECT Distinct(RegionName),branchname FROM `view_rptfrnfin`");
                                       
                                     while( $record = mysql_fetch_array($que))
                                     {
									        
                                      echo "<option value=\"".$record['RegionName']."~".$record['branchname']."\">".$record['RegionName']."~".$record['branchname']."\n "; 
									  }
                              		 
                                    ?>
                                          </select>
                                      </td>
                                        
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
                   


                        <div style="width:930px; height:25px; float:left;  margin-left:0px;" class="head">
                            <p>ServiceCallRegister Report</p>
                        </div>
                        <!-- main row 1 start-->     
                        <div style="width:925px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
                            <!-- col1 -->   
                            <div style="width:800px; height:auto; padding-bottom:5px; float:left; " class="cont">
                                <!--Row1 -->
                                <div style="float:left;width:400px">

                                    <!--Row1 end-->
 								 <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:45px;">
                                        <label>From Date</label><label style="color:#F00;">*</label>
                                    </div>
                                    <div style="width:190px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                        <input type="text" name="SCR_FromDate" id="rp_frdate" value="<?php echo $_POST['SCR_FromDate']; ?>" readonly/>
                                    </div>
                                   
                                    <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:45px;">
                                        <label>Region</label>
                                    </div>
                                    <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                    
									<select name='region[]' id='region' onChange="drpfunc();" multiple="multiple" >
									   <option value="0">.All Regions.</option>
									  <?
                                            $region_select = ($_POST['region']) ? $_POST['region'] : '';

                                            $list = mysql_query("SELECT regioncode, regionname FROM region order by regionname asc");

                                            while ($row_list = mysql_fetch_assoc($list)) {
                                                $selected = '';

                                                if ($row_list['regionname'] == $region_select) {
                                                    $selected = ' selected ';
                                                }
                                                ?>
									  <option value="<? echo $row_list['regionname']; ?>"<? echo $selected; ?>> <? echo $row_list['regionname']; ?> </option>
									  <?
                                            }
                                            ?>
								    </select>
									
                                    </div>
                                   
                                    <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:45px;">
                                        <label>Franchisee Name</label>
                                    </div>
                                    <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                        <select name='franchise[]' id='franchise' multiple="multiple" onChange="allfranchisee();">
                                            <option value="0">.All Franchisees.</option>
                                            <?
                                            $add_qry = '';
                                            if ($region_select) {
                                                $add_qry = " AND Region = '" . $region_select."'";
                                            }
                                            if ($branch_select) {
                                                $add_qry .= " AND Branch = '" . $branch_select."'";
                                            }

                                            $franchise_select = ($_POST['franchise']) ? $_POST['franchise'] : '';
                                            $list = mysql_query("SELECT Franchisecode, Franchisename FROM franchisemaster order by Franchisename asc");
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
                                           echo $qry; 
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
                                 <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:45px;">
                                      <label>Failure Mode</label>
                                    </div>
                                     <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                       <select name='failuremodedescription[]' id="failuremodedescription" multiple="multiple" onChange="allfailuremodedescription();">
                                            <option value="">-- Select --</option>
                                            <?
                                            $scfno_select = ($_POST['failuremodedescription']) ? $_POST['failuremodedescription'] : '';
                                            $list = mysql_query("SELECT failuremodedescription FROM failuremode order by failuremodedescription asc");
                                            while ($row_list = mysql_fetch_assoc($list)) {
                                                $selected = '';
                                                if ($row_list['failuremodedescription'] == $scfno_select) {
                                                    $selected = ' selected ';
                                                }
                                                ?>
                                                <option value="<? echo $row_list['failuremodedescription']; ?>" <? echo $selected; ?>><? echo trim($row_list['failuremodedescription']); ?></option>
                                                <?
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div style="float:left;width:400px">
                                 <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:45px;">
                                        <label>To Date</label><label style="color:#F00;">*</label>
                                    </div>
                                    <div style="width:190px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                        <input type="text" name="SCR_ToDate" id="rp_todate" value="<?php echo $_POST['SCR_ToDate']; ?>" readonly />
                                    </div>
                                 <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:45px;">
                                        <label>Branch</label>
                                  </div>
                                    <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                        <select name='branch[]' id='branch' multiple="multiple" onChange="drpfunc1();">
                                            <option value="0">.All Branches.</option>
                                            <?php
                                            $add_qry = '';
                                            if ($region_select) {
                                                $add_qry = " AND region = '" . $region_select."'";
                                            }
                                            $branch_select = ($_POST['branch']) ? $_POST['branch'] : '';

                                            $list = mysql_query("SELECT branchcode, branchname FROM branch order by branchname asc");
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
										
                                            
										   $qry="SELECT distinct (ProductGroup)as ProductGroup FROM productgroupmaster ORDER BY ProductGroup asc";
										   
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
                                     
                                     <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:45px;">
                                        <label>Vehicle Model</label>
                                    </div>
                                    <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                     <select name='modelname[]' id="modelname" multiple="multiple" onChange="allmodelname();">
                                          <option value="0">.All Vehicle Model.</option>
                                            <?
                                            $modelname_select = ($_POST['modelname']) ? $_POST['modelname'] : '';
											
										   $list = mysql_query("SELECT modelname FROM vehiclemodel ORDER BY modelname asc");
                                            while ($row_list = mysql_fetch_assoc($list)) {
                                                $selected = '';

                                                if ($row_list['modelname'] == $modelname_select) {
                                                    $selected = ' selected ';
                                                }
                                                ?>
												<option value="<? echo $row_list['modelname']; ?>"<? echo $selected; ?>>
                                                    <? echo $row_list['modelname']; ?> 
														
                                                </option>

                                                <?
                                            }
                                            ?>
                                        </select>
                                     </div>
                                     
                                    <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:45px;">
                                      <label>Decision </label>
                                    </div>
                                    <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                        <select name='Decision[]' id="Decision" multiple="multiple" onChange="allDecision();">
                                            <option value="0">-- Select --</option>
                                            <option value="Recharge">Recharge</option>
                                            <option value="Reject">Reject</option>
                                            <option value="Replace Free - 2W">Replace Free - 2W</option>
                                            <option value="Replace Free - 4W">Replace Free - 4W</option>
                                            <option value="OE Free Replace - 2W">OE Free Replace - 2W</option>
                                             <option value="OE Free Replace - 4W">OE Free Replace - 4W</option>
                                            <option value="Replace Free - Tubular">Replace Free - Tubular</option>
                                             <option value="Special Case">Special Case</option>
                                            <option value="Replace Prorata - 2W">Replace Prorata - 2W</option>
                                             <option value="Replace Prorata - 4W">Replace Prorata - 4W</option>
                                            <option value="Replace Prorata - Tubular">Replace Prorata - Tubular</option>
                                        </select>
                                  </div>
                                  
                                  
                                <div style="clear:both"></div>

                            </div>
                        </div>



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
			<table align="center"  bgcolor="#ffffFF" border="1" width="900px" cellpadding="20%">
			<tr class="record_header" style="white-space:nowrap;">
			<td style="font-weight:bold;  width:auto; text-align:center;">Region</td>
			<td style="font-weight:bold;  width:auto; text-align:center;">Branch</td>
			<td style="font-weight:bold;  width:auto; text-align:center;">Franchisee Code</td>
			<td style="font-weight:bold;  width:auto; text-align:center;">Franchisee Name</td>
			<td style="font-weight:bold;  width:auto; text-align:center;">Retailer Name</td>
			<td style="font-weight:bold;  width:auto; text-align:center;">SCF No</td>
			<td style="font-weight:bold;  width:auto; text-align:center;">SCF Date</td>
			<td style="font-weight:bold;  width:auto; text-align:center;">Complaint Date</td>
			<td style="font-weight:bold;  width:auto; text-align:center;">Category</td>
			<td style="font-weight:bold;  width:auto; text-align:center;">Date of Sale</td> 
			<td style="font-weight:bold;  width:auto; text-align:center;">Battery SI No.</td>
			<td style="font-weight:bold;  width:auto; text-align:center;">Product Code</td>
			<td style="font-weight:bold;  width:auto; text-align:center;">Product Type</td>
			<td style="font-weight:bold;  width:auto; text-align:center;">Product Segment</td> 
			<td style="font-weight:bold;  width:auto; text-align:center;">Product Group</td>
			<td style="font-weight:bold;  width:auto; text-align:center;">Bty. Mfg. Date</td>
			<td style="font-weight:bold;  width:auto; text-align:center;">Customer Name</td>
			<td style="font-weight:bold;  width:auto; text-align:center;">City</td>
			<td style="font-weight:bold;  width:auto; text-align:center;">Phone No.</td>
			<td style="font-weight:bold;  width:auto; text-align:center;">Vehicle Model</td>
			<td style="font-weight:bold;  width:auto; text-align:center;">Vehicle Make</td>
			<td style="font-weight:bold;  width:auto; text-align:center;">Vehicle Segment</td>
			<td style="font-weight:bold;  width:auto; text-align:center;">Vehicle  Reg. No.</td>
			<td style="font-weight:bold;  width:auto; text-align:center;">Engine Type</td>
			<td style="font-weight:bold;  width:auto; text-align:center;">Life Served (In Days)</td>
			<td style="font-weight:bold;  width:auto; text-align:center;">KM Run</td>
			<td style="font-weight:bold;  width:auto; text-align:center;">Failure Mode</td>
			<td style="font-weight:bold;  width:auto; text-align:center;">Decision</td>
			<td style="font-weight:bold;  width:auto; text-align:center;">Decision Date</td>
			<td style="font-weight:bold;  width:auto; text-align:center;">Closure Date</td>
			<td style="font-weight:bold;  width:auto; text-align:center;">TAT - 2</td>
			<td style="font-weight:bold;  width:auto; text-align:center;">Setteled in Days</td> 
			<td style="font-weight:bold;  width:auto; text-align:center;">New Battery SI No.</td> 
			<td style="font-weight:bold;  width:auto; text-align:center;">New Product Code</td> 
			<td style="font-weight:bold;  width:auto; text-align:center;">New Product Type</td> 
			<td style="font-weight:bold;  width:auto; text-align:center;">New Product Segment</td> 
			<td style="font-weight:bold;  width:auto; text-align:center;">New Product Group</td> 
			<td style="font-weight:bold;  width:auto; text-align:center;">Lead Time in days</td> 
			<td style="font-weight:bold;  width:auto; text-align:center;">Service Compensation </td> 
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
									
									foreach ($_POST['branch'] as $selectedOption1){
										if($selectedOption1!="0")
										{
										$brn = $brn . "branchname = '" .$selectedOption1."'  OR ". "\n";
										}
									}
									
									foreach ($_POST['franchise'] as $selectedOption2){
										if($selectedOption2!="0")
										{
										$fname = $fname . "franchiseename = '" .$selectedOption2."'  OR ". "\n";
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
										$pseg=  $pseg. " psegmentname = '" .$selectedOption4."'  OR ". "\n"; 
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
									foreach ($_POST['Decision'] as $selectedOption7){
										if($selectedOption7!="0")
										{
										$pdesc = $pdesc. "decision = '" .$selectedOption7."'  OR ". "\n";
										}
									}
									
									foreach ($_POST['failuremodedescription'] as $selectedOption8)
									{
										if($selectedOption8!="0")
										{
										$pfmode = $pfmode. "failuremode = '" .$selectedOption8."'  OR ". "\n";
										}
									}
									foreach ($_POST['modelname'] as $selectedOption9){
										if($selectedOption9!="0")
										{
										$vmodel = $vmodel. "vmodel = '" .$selectedOption9."'  OR ". "\n";
										}
									}
									
									$reg =	substr($reg, 0, -3);
									$brn =	substr($brn, 0, -4);										
									$fname =	substr($fname, 0, -4);
									$pgrp =	substr($pgrp, 0, -4);
									$pseg =	substr($pseg, 0, -4);
									$ptype =	substr($ptype, 0, -4);
									$pcode =	substr($pcode, 0, -4);
									$pdesc=	substr($pdesc, 0, -4);
									$pfmode=	substr($pfmode, 0, -4);
									$vmodel =	substr($vmodel, 0, -4);
										
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
											if($pdesc!=NULL)
											{
												$grystr=$grystr. "(".$pdesc.") AND ";
											}
											if($pfmode!=NULL)
											{
												$grystr=$grystr. "(".$pfmode.") AND ";
											}
											if($vmodel!=NULL)
											{
												$grystr=$grystr. "(".$vmodel.") AND ";
											}
											
									 $grystrres = substr($grystr, 0, -4);
									
										if($_POST['SCR_FromDate']!="" && $_POST['SCR_ToDate']!="")
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
									$dbtodateto=date("Y-m-d",strtotime($_POST['SCR_ToDate'])) ;
									$dbfromdatefrom=date('Y-m-d',strtotime($_POST['SCR_FromDate']));
									 $dbfromdatefrom="'". $dbfromdatefrom."'";
									 $dbtodateto="'".$dbtodateto."'";
									$qry ="CALL servicecall($dbfromdatefrom,$dbtodateto,$grystrres);";
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
										weblogfun("Report Access", "Service Report -> ".$pagename);
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
											echo '<tr class="record_data" style="white-space:nowrap;">';
											echo '<td>' . $record[$i]['regionname']. '</td>';
											echo '<td>' . $record[$i]['branchname'] . '</td>';
											echo '<td>' . $record[$i]['franchisecode'] . '</td>';
											echo '<td>' . $record[$i]['franchiseename'] . '</td>';
											echo '<td>' . $record[$i]['retailername'] . '</td>';
											echo '<td>' . $record[$i]['SCFNo'] . '</td>';											
											echo '<td>' . $record[$i]['SCFDate'] . '</td>';
											echo '<td>' . $record[$i]['complaintdate'] . '</td>';
											echo '<td>' . $record[$i]['category'] . '</td>';
											echo '<td>' . $record[$i]['dateofsale'] . '</td>';
											echo '<td>' . $record[$i]['battery_slno'] . '</td>';
											echo '<td>' . $record[$i]['productcode'] . '</td>';
											echo '<td>' . $record[$i]['ptypename'] . '</td>';
											echo '<td>' . $record[$i]['psegmentname'] . '</td>';
											echo '<td>' . $record[$i]['pgroupname'] . '</td>';
											echo '<td>' . $record[$i]['manufacturedate'] . '</td>';
											echo '<td>' . $record[$i]['customername'] . '</td>';
											echo '<td>' . $record[$i]['city'] . '</td>';
											echo '<td>' . $record[$i]['phoneno'] . '</td>';
											echo '<td>' . $record[$i]['vmodel'] . '</td>';											
											echo '<td>' . $record[$i]['vmake'] . '</td>';
											echo '<td>' . $record[$i]['vsegment'] . '</td>';
											echo '<td>' . $record[$i]['vregno'] . '</td>';
											echo '<td>' . $record[$i]['enginetype'] . '</td>';
											echo '<td>' . $record[$i]['lifeserved'] . '</td>';
											echo '<td>' . $record[$i]['kmrun'] . '</td>';
											echo '<td>' . $record[$i]['failuremode'] . '</td>';
											echo '<td>' . $record[$i]['decision'] . '</td>';
											echo '<td>' . $record[$i]['decisiondate'] . '</td>';
											echo '<td>' . $record[$i]['closuredate'] . '</td>';
											echo '<td>' . $record[$i]['tat2'] . '</td>';
											echo '<td>' . $record[$i]['setteled'] . '</td>';
											echo '<td>' . $record[$i]['newbatterysno'] . '</td>';
											echo '<td>' . $record[$i]['newproductcode'] . '</td>';
											echo '<td>' . $record[$i]['newptypename'] . '</td>';
											echo '<td>' . $record[$i]['newpsegmentname'] . '</td>';
											echo '<td>' . $record[$i]['newpgroupname'] . '</td>';
											echo '<td>' . $record[$i]['leadtime'] . '</td>';
											echo '<td>' . $record[$i]['scompvalue'] . '</td>';
											echo '</tr>';
										}
									}// This while will loop through all of the records as long as there is another record left. 
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

