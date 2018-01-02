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
        document.location='rcdreport.php';
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
                            <p>Retailer Category Summary Report </p>
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
                                            if ($region_select) {
                                                $add_qry = " AND region = '" . $region_select."'";
                                            }
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
                                        <label>Retailer Name</label>
                                    </div>
									
                                    <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                       <select name='RetailerName[]' id = 'RetailerName' multiple="multiple">
                                            <option value="0">.All Retailer Name.</option>
                                            <?
                                            $Productsegment_select = ($_POST['RetailerName']) ? $_POST['RetailerName'] : '';
										   $list2 = mysql_query("SELECT distinct (RetailerName) as RetailerName FROM retailermaster ORDER BY RetailerName asc");
                                            while ($row_list2 = mysql_fetch_assoc($list2)) {
                                                $selected = '';

                                                if ($row_list2['RetailerName'] == $Productsegment_select) {
                                                    $selected = ' selected ';
                                                }
                                                ?>
												
                                                <option value="<? echo $row_list2['RetailerName']; ?>"<? echo $selected; ?>>
                                                    <? echo $row_list2['RetailerName']; ?> 
                                                
														
                                                </option>

                                                <?
                                            }
                                            ?>
                                      </select>
                                    </div>
                                    
                                     <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:45px;">
                                        <label>Retailer Category 1</label>
                                    </div>
									
                                    <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                     <select name='rc1[]' id = 'rc1' multiple="multiple" >
                                            <option value="0">.All Retailer Category 1.</option>
                                            <?
                                            $Productgroup_select = ($_POST['retailercategory1']) ? $_POST['retailercategory1'] : '';
										
                                            
										   $qry="SELECT distinct (retailercategory1)as retailercategory1 FROM retailermaster WHERE retailercategory1<>'' ORDER BY retailercategory1 asc;";
										   
										   $list1 = mysql_query($qry);
                                            while ($row_list1 = mysql_fetch_assoc($list1)) {
                                                $selected = '';

                                                if ($row_list1['retailercategory1'] == $Productgroup_select && $row_list1['retailercategory1']!="") {
                                                    $selected = ' selected ';
                                                }
                                                ?>
												  <option value="<? echo $row_list1['retailercategory1']; ?>"<? echo $selected; ?>>
                                                    <? echo $row_list1['retailercategory1']; ?>                                         	
                                                </option>

                                                <?
                                            }
                                            ?>
                                        </select>
                                    </div>
                                     <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:45px;">
                                        <label>Retailer Category 3</label>
                                    </div>
									
                                    <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                       <select name='rc3[]' id = 'rc3' multiple="multiple" >
                                            <option value="0">.All Retailer Category 3.</option>
                                            <?
                                            $Productgroup_select = ($_POST['retailercategory3']) ? $_POST['retailercategory3'] : '';
										
                                            
										   $qry="SELECT distinct (retailercategory3)as retailercategory3 FROM retailermaster WHERE retailercategory3 <> '' ORDER BY retailercategory3 asc;";
										   
										   $list1 = mysql_query($qry);
                                            while ($row_list1 = mysql_fetch_assoc($list1)) {
                                                $selected = '';

                                                if ($row_list1['retailercategory3'] == $Productgroup_select) {
                                                    $selected = ' selected ';
                                                }
                                                ?>
												  <option value="<? echo $row_list1['retailercategory3']; ?>"<? echo $selected; ?>>
                                                    <? echo $row_list1['retailercategory3']; ?>                                         	
                                                </option>

                                                <?
                                            }
                                            ?>
                                        </select>
                                    </div>
                                      <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:45px;">
                                        <label>Franchisee M. E</label>
                                    </div>
                                    <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                        <select name='franchiseeme[]' id = 'franchiseeme' multiple="multiple" >
                                            <option value="0">.All Franchisee M. E.</option>
                                            <?
                                            $Productgroup_select = ($_POST['franchiseeme']) ? $_POST['franchiseeme'] : '';
										
                                            
										   $qry="SELECT distinct (franchiseeme)as franchiseeme FROM retailermaster WHERE franchiseeme <> '' ORDER BY franchiseeme asc;";
										   
										   $list1 = mysql_query($qry);
                                            while ($row_list1 = mysql_fetch_assoc($list1)) {
                                                $selected = '';

                                                if ($row_list1['franchiseeme'] == $Productgroup_select) {
                                                    $selected = ' selected ';
                                                }
                                                ?>
												  <option value="<? echo $row_list1['franchiseeme']; ?>"<? echo $selected; ?>>
                                                    <? echo $row_list1['franchiseeme']; ?>                                         	
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
                                        <label>Sales Voucher Type</label>
                                  </div>
                               <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                        <select name='Voucher[]' id="Voucher" multiple="multiple" onChange="allVoucher();">
                                            <option value="0">-- Select --</option>
                                            <option value="Prorata sales">Prorata Sales</option>
                                            <option value="Regular Sales">Regular Sales</option>
                                            <option value="Scheme Sales">Scheme Sales</option>
                                            <option value="Scrap Sales">Scrap Sales</option>
                                            <option value="warranty sales">Warranty Sales</option>
                                        </select>
                                  </div>
                               </div>
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
                                        <select name='franchise[]' id='franchise' multiple="multiple" onChange="drpfuncretailer();">
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
                                                <option value="<? echo $row_list['Franchisename']; ?>" <? echo $selected; ?>><? echo $row_list['Franchisename']; ?></option>
                                                <?
                                            }
                                            ?>
                                        </select>
                                    </div>		
                                    <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:45px;">
                                        <label>Retailer Category</label>
                                    </div>
                                    <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                        <select name='RetailerCategory[]' id = 'RetailerCategory' multiple="multiple" >
                                            <option value="0">.All Retailer Category.</option>
                                            <?
                                            $Productgroup_select = ($_POST['RetailerCategory']) ? $_POST['RetailerCategory'] : '';
										
                                            
										   $qry="SELECT distinct (RetailerCategory)as RetailerCategory FROM retailercategory ORDER BY RetailerCategory asc";
										   
										   $list1 = mysql_query($qry);
                                            while ($row_list1 = mysql_fetch_assoc($list1)) {
                                                $selected = '';

                                                if ($row_list1['RetailerCategory'] == $Productgroup_select) {
                                                    $selected = ' selected ';
                                                }
                                                ?>
												  <option value="<? echo $row_list1['RetailerCategory']; ?>"<? echo $selected; ?>>
                                                    <? echo $row_list1['RetailerCategory']; ?>                                         	
                                                </option>

                                                <?
                                            }
                                            ?>
                                        </select>
                                     </div>
  <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:45px;">
                                        <label>Retailer Category 2</label>
                                    </div>
									
                                   <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                   <select name='rc2[]' id = 'rc2' multiple="multiple" >
                                            <option value="0">.All Retailer Category 2.</option>
                                            <?
                                            $Productgroup_select = ($_POST['retailercategory2']) ? $_POST['retailercategory2'] : '';
										
                                            
										   $qry="SELECT distinct (retailercategory2)as retailercategory2 FROM retailermaster WHERE retailercategory2 <> '' ORDER BY retailercategory2 asc;";
										   
										   $list1 = mysql_query($qry);
                                            while ($row_list1 = mysql_fetch_assoc($list1)) {
                                                $selected = '';

                                                if ($row_list1['retailercategory2'] == $Productgroup_select) {
                                                    $selected = ' selected ';
                                                }
                                                ?>
												  <option value="<? echo $row_list1['retailercategory2']; ?>"<? echo $selected; ?>>
                                                    <? echo $row_list1['retailercategory2']; ?>                                         	
                                                </option>


                                                <?
                                            }
                                            ?>
                                        </select>
                                    </div>                                   
                                     <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:45px;">
                                        <label>Retailer Classification</label>
                                    </div>
                                     <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                        <select name='rclass[]' id = 'rclass' multiple="multiple" >
                                            <option value="0">.All Retailer Classification.</option>
                                           <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="C">C</option>
                                        </select>
                                     </div>
                                     	<div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:45px;">
                                        <label>Geography</label>
                                    </div>
                                    <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                        <select name='geographical[]' id = 'geographical' multiple="multiple" >
                                            <option value="0">.All Geography .</option>
                                            <?
                                            $Productgroup_select = ($_POST['geographical']) ? $_POST['geographical'] : '';
										
                                            
										   $qry="SELECT distinct (geographical)as geographical FROM retailermaster WHERE geographical <> '' ORDER BY geographical asc;";
										   
										   $list1 = mysql_query($qry);
                                            while ($row_list1 = mysql_fetch_assoc($list1)) {
                                                $selected = '';

                                                if ($row_list1['geographical'] == $Productgroup_select) {
                                                    $selected = ' selected ';
                                                }
                                                ?>
												  <option value="<? echo $row_list1['geographical']; ?>"<? echo $selected; ?>>
                                                    <? echo $row_list1['geographical']; ?>                                         	
                                                </option>

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
									foreach ($_POST['rclass'] as $selectedOption4){
										if($selectedOption4!="0")
										{
											$rclass = $rclass. "rclass = '" .$selectedOption4."'  OR ". "\n";
										}
									}
									foreach ($_POST['RetailerCategory'] as $selectedOption5){
										if($selectedOption5!="0")
										{
											$rcat = $rcat. "rcategory = '" .$selectedOption5."'  OR ". "\n";
										}
									}
									foreach ($_POST['franchiseeme'] as $selectedOption6){
										if($selectedOption6!="0")
										{
											$fme = $fme. "franchiseeme = '" .$selectedOption6."'  OR ". "\n";
										}
									}
									foreach ($_POST['Productgroup'] as $selectedOption7){
										if($selectedOption7!="0")
										{
										$pgrp =  $pgrp. "pgroupname = '" .$selectedOption7."'  OR ". "\n";	
										}
									}
									foreach ($_POST['Productsegment'] as $selectedOption8){
										if($selectedOption8!="0")
										{
										$pseg=  $pseg. "psegmentname = '" .$selectedOption8."'  OR ". "\n";
										}
									}
									foreach ($_POST['Producttype'] as $selectedOption9){
										if($selectedOption9!="0")
										{
										$ptype =  $ptype. "ptypename = '" .$selectedOption9."'  OR ". "\n";  
										}
									}
									
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
									foreach ($_POST['rc1'] as $selectedOption12){
										if($selectedOption12!="0")
										{
										$rc1 = $rc1. "rc1 = '" .$selectedOption12."'  OR ". "\n";
										}
									}
									foreach ($_POST['rc2'] as $selectedOption13){
										if($selectedOption13!="0")
										{
										$rc2 = $rc2. "rc2 = '" .$selectedOption13."'  OR ". "\n";
										}
									}
									foreach ($_POST['rc3'] as $selectedOption14){
										if($selectedOption14!="0")
										{
										$rc3 = $rc3. "rc3 = '" .$selectedOption14."'  OR ". "\n";
										}
									}
									foreach ($_POST['geographical'] as $selectedOption15){
										if($selectedOption15!="0")
										{
										$geographical = $geographical. "geographical = '" .$selectedOption15."'  OR ". "\n";
										}
									}
									
									$brn 			=	substr($brn, 0, -4);										
									$fname 			=	substr($fname, 0, -4);
									$rname 			=	substr($rname, 0, -4);
									$rclass 		=	substr($rclass, 0, -4);
									$rcat			=	substr($rcat, 0, -4);
									$fme			=	substr($fme, 0, -4);
									$pgrp 			=	substr($pgrp, 0, -4);
									$pseg 			=	substr($pseg, 0, -4);
									$ptype 			=	substr($ptype, 0, -4);
									$pcode 			=	substr($pcode, 0, -4);
									$pvouc 			=	substr($pvouc, 0, -4);
									$rc1 			=	substr($rc1, 0, -4);
									$rc2 			=	substr($rc2, 0, -4);
									$rc3 			=	substr($rc3, 0, -4);
									$geographical	=	substr($geographical, 0, -4);	
									
											
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
											if($rclass!=NULL)
											{
												$grystr=$grystr. "(".$rclass.") AND ";
											}
											if($rcat!=NULL)
											{
												$grystr=$grystr. "(".$rcat.") AND ";
											}
											if($fme!=NULL)
											{
												$grystr=$grystr. "(".$fme.") AND ";
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
											if($pvouc!=NULL)
											{
												$grystr=$grystr. "(".$pvouc.") AND ";
											}
											if($rc1!=NULL)
											{
												$grystr=$grystr. "(".$rc1.") AND ";
											}
											if($rc2!=NULL)
											{
												$grystr=$grystr. "(".$rc2.") AND ";
											}
											if($rc3!=NULL)
											{
												$grystr=$grystr. "(".$rc3.") AND ";
											}
											if($geographical!=NULL)
											{
												$grystr=$grystr. "(".$geographical.") AND ";
											}
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
									
									$headarray = array();
									$totalarray=array();
										
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
<? } ?>
