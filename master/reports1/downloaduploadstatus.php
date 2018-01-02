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
////////////////////////////////////////////////

	//This block to authenticate the user whether he has the rights to access this page 
	$pagename = "Data Exchange";
	require_once 'Authentication_Rights.php';
	// $validuser = $_SESSION['username'];
	// $authen_qry =mysql_query( "select access_right,usertype from reportrights where userid = '$validuser' and r_screen = '$pagename'");
	// $authen_row = mysql_fetch_array($authen_qry);
	// if (($authen_row['access_right'])== 'No')
	// {
	// 	header("location:/".$_SESSION['mainfolder']."/home/home/master.php");
	// }
	// if (($authen_row['usertype'])== 'Others')
	// {
	// 	header("location:branch_downupstatus.php");
	// }
	// Authentication block ends 



if (isset($_POST['Cancel'])) {
		unset($_SESSION['form_controls']);
	?>
    <script type="text/javascript">
        document.location='downloaduploadstatus.php';
    </script>
    <?
}
include("inc/common_functions.php");
?> 

<script src="inc/multiselect.js"></script> 
<link href="reportstyle.css" rel="stylesheet" type="text/css" />
<title><?php echo $_SESSION['title']; ?> |&nbsp;Data Exchange status Reports</title>
</head>

<body><center>

   <?php include("../../menu.php") ?>

    <!--Third Block - Container-->
    <div style="width:100%; height:auto; float:none;">
        <div style="width:980px; height:auto; float:none; margin-top:8px;" class="container">

            <div style="width:950px; height:auto; float:left; margin-bottom:10px; margin-left:15px;" id="center">  
                <!-- form id start-->
                <form method="POST" name="form1" action="<?php // $_PHP_SELF          ?>">
                	  <? require_once'all_list.php' ?>
                    <div style="width:930px; height:auto;   min-height: 180px; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;">


                        <div style="width:930px; height:25px; float:left;  margin-left:0px;" class="head">
                            <p>Data Exchange status Reports</p>
                        </div>
                      <div style="width:930px; height:300; float:left;  margin-left:0px;"  >
	<!-- <div style="width:800px; height:auto; padding-bottom:5px; float:left; " class="cont"> -->
<div style="float:left;width:445px">
	 <? if (($authen_row['usertype'])== 'Branch') { 
            require_once 'second_div_element.php';

         }else{
            require_once 'first_div_element.php';
         }

          ?>
</div>
<div style="float:left;width:400px">
	 <? if (($authen_row['usertype'])== 'Branch') { 
        require_once 'first_div_element.php';

     }else{
        require_once 'second_div_element.php';
     }

      ?>
      <div style="width:100px; height:100px;  float:left;  margin-top:5px; margin-left:3px; ">
				<label>Order By</label>
		</div>
		<div align="left" style="width:145px; height:100px; float:left;  margin-top:5px; margin-left:45px; ">
			
			<input type="radio" name="orderby" id="franchisename" value="Franchisename" />
				<label>Distributor Name</label><br>
			<input type="radio" name="orderby" id="Region" value="RegionName"/>
				<label>Region</label><br>
			<input type="radio" name="orderby" id="Branch" value="branchname"/>
				<label>Branch</label><br>
			<input type="radio" name="orderby" id="DownloadDate" value="downloaddate"/>
				<label>Download Date</label><br>
			<input type="radio" name="orderby" id="UploadDate" value="Uploaddate"/>
				<label>Upload Date</label><br>
		</div>
		<div align="left" style="width:145px; height:100px; float:left;  margin-top:-100px; margin-left:340px; ">
			
			<input type="radio" name="order" id="asc" value="asc" />
				<label>Ascending</label><br>
			<input type="radio" name="order" id="desc" value="desc"/>
				<label>Descending</label>
		</div>
</div>
<!-- </div> -->
</div>
                        <!-- Main row 1 end-->
   						<div style="width:930px; height:60px; float:left; margin-left:15px; margin-top:8px;">
							<div style="width:340px; height:50px; float:left;  margin-left:4px; margin-top:0px;" id="center1">
								 <div style="width:95px; height:32px; float:left;  margin-left:35px; margin-top:16px;">
                                    <input id="get_report_btn" name="Get" type="submit" class="button" value="Get Report">
                                </div>
                                <div style="width:85px; height:32px; float:left;  margin-left:75px;margin-top:16px;">
                                    <input id="cancel_btn" name="Cancel" type="submit" class="button" value="Cancel">
                                </div>                     
							</div>	
						</div>

                        <!--Main row 2 start-->
                      
                        <?php $table_data_height = ''; if (isset($_POST['Get'])) { $table_data_height = " height:inherit; "; } else { $table_data_height = " height:500px; "; } ?>
                        <div style="width:917px; <?php echo $table_data_height ?> padding-bottom:8px; margin-top:20px; float:left; margin-left:10px; overflow:auto;" class="grid">

                              <table align="center"  class= "sortable"  bgcolor="#FFFFFF" border="1" width="900px" cellpadding="15%" >

                                <tr class="record_header" bgcolor="#C3D9FF" style="white-space:nowrap;"> 
                                    <td style="font-weight:bold;">Distributor Code</td>   
									<td style="font-weight:bold;">Distributor Name</td>	
									<td style="font-weight:bold;">Region Name</td>									
                                    <td style="font-weight:bold;">Branch Name</td>
                                    <td style="font-weight:bold;">Master Download date</td>
                                    <td style="font-weight:bold;">Transaction Upload date  </td>
                                  
                                                                
                                </tr>
                                
                                <?php
/***********************/

$order1=$_POST['orderby'];
$order2=$_POST['order'];
if($order1!='')
$order3=" order by " . $order1 . " " . $order2;

/************************/

if (isset($_POST['Get'])) 
{		
								// Log information
										require_once '../../weblog.php';
										weblogfun("Report Access", "Stock Report -> ".$pagename);						
	// This foreach will loop through all the specified records . 
	foreach ($_POST['region'] as $selectedOption){
		if($selectedOption!="0")
		{
			$reg =  $reg ."Region = '" .$selectedOption."'  OR ";
		}
	}
									
	foreach ($_POST['branch'] as $selectedOption1){
		if($selectedOption1!="0")
		{
			$brn = $brn . "Branch = '" .$selectedOption1."'  OR ". "\n";
		}
	}
	foreach ($_POST['franchise'] as $selectedOption2){
		if($selectedOption2!="0")
		{
			$fname = $fname . "Franchisecode = '" .$selectedOption2."'  OR ". "\n";
		}
	}
	foreach ($_POST['primaryfranchise'] as $selectedOption2){
		if($selectedOption2!="0")
		{
			$pfname = $pfname . "PrimaryFranchise = '" .$selectedOption2."'  OR ". "\n";
		}
	}

	$fname =	substr($fname, 0, -4);//remove the OR with space.
	$reg =	substr($reg, 0, -3);
	$brn =	substr($brn, 0, -4);
	$pfname =	substr($pfname, 0, -4);

	if($reg!=NULL)
	{
		$grystr = $reg." AND ";
	}
											
	if($brn!=NULL)
	{
		$grystr=$grystr. $brn." AND ";
	}
	
	if($fname!="")
	{
		$grystr=$grystr. $fname." AND ";
	}
	if($pfname!="")
	{
		$grystr=$grystr. $pfname." AND ";
	}
	 $grystrres = substr($grystr, 0, -4);
	if($grystrres!=NULL)
	{
		//$grystrres= str_replace("'","''",$grystrres);
		//$grystrres= "'".$grystrres."'";
	}
	else
	{
		$grystrres= "'1'";
	}
	
			$qry = "SELECT * FROM view_status WHERE $grystrres " . $order3;
			$qry_exec = mysql_query($qry);
			$count=mysql_num_rows($qry_exec);
			if (mysql_num_rows($qry_exec)) 
			{
				while ($qry_obj = mysql_fetch_array($qry_exec)) 
				{
				$total_record++;
				$date2 = ($total_record == 1) ? $qry_obj['downloaddate'] : '';
				$sal_date  = ($qry_obj['downloaddate']!='')?date('d/m/Y', strtotime($qry_obj['downloaddate'])):'NULL';
				$date1 = ($total_record == 1) ? $qry_obj['Uploaddate'] : '';
				$sal_date1  = ($qry_obj['Uploaddate']!='')?date('d/m/Y', strtotime($qry_obj['Uploaddate'])):'NULL';
				echo '<tr class="record_data">';
				echo '<td>' . $qry_obj['Franchisecode'] . ' </td>';
				echo '<td>' . $qry_obj['Franchisename'] . ' </td>';
				echo '<td>' . $qry_obj['RegionName'] . '</td>';
				echo '<td>' . $qry_obj['branchname'] . '</td>';
				echo '<td>' . $sal_date . '</td>';
				echo '<td>' . $sal_date1 . '</td>';
				echo '</tr>';
				}
			}
			if($count==0)
			{
				unset($_SESSION['form_controls']);
		 		echo '<tr class="no_records"><td colspan="111" align="center">No Records Found</td></tr>';
			}
	
}
else
{
	 $qry = "SELECT * FROM view_status WHERE 1 " . $order3;
			$qry_exec = mysql_query($qry);
			//$count=mysql_num_rows($qry_exec);
			if (mysql_num_rows($qry_exec)) 
			{
				while ($qry_obj = mysql_fetch_array($qry_exec)) 
				{
				$total_record++;
				$date2 = ($total_record == 1) ? $qry_obj['downloaddate'] : '';
				$sal_date  = ($qry_obj['downloaddate']!='')?date('d/m/Y', strtotime($qry_obj['downloaddate'])):'NULL';
				$date1 = ($total_record == 1) ? $qry_obj['Uploaddate'] : '';
				$sal_date1  = ($qry_obj['Uploaddate']!='')?date('d/m/Y', strtotime($qry_obj['Uploaddate'])):'NULL';
				echo '<tr class="record_data">';
				echo '<td>' . $qry_obj['Franchisecode'] . ' </td>';
				echo '<td>' . $qry_obj['Franchisename'] . ' </td>';
				echo '<td>' . $qry_obj['RegionName'] . '</td>';
				echo '<td>' . $qry_obj['branchname'] . '</td>';
				echo '<td>' . $sal_date . '</td>';
				echo '<td>' . $sal_date1 . '</td>';
				echo '</tr>';
				}
			}
		/*	if($count==0)
			{
				unset($_SESSION['form_controls']);
		 		echo '<tr class="no_records"><td colspan="111" align="center">No Records Found</td></tr>';
			}
			echo $count;
		*/
}
?>
                            </table>


                        </div>
<!--                        <br /> <div style="width:600px; height:50px; float:left;  margin-left:15px; margin-top:0px;"  >
                            <div style="margin-left:10px; margin-top:16px;"></div></div>-->
                                <br/>
                    <!--            <?php if (isset($_POST['Get'])) 
						{
						?>
                        <div style="width:260px; height:50px; float:right;  margin-right:15px; margin-top:0px;" class="cont" >

                            <div style="width:70px; height:32px; float:left; margin-left:25px; margin-top:20px;">
                                Export As
							</div> 
                            <div style="width:83px; height:32px; float:left; margin-left:5px; margin-top:12px;">
                                <select name="Type">
                                   <?php /*?> <option value="PDF">PDF</option><?php */?>
                                    <option value="Excel">Excel</option>
                                    <option value="Document">Document</option>
								</select>
							</div>  
                            <div style="width:63px; height:32px; float:right; margin-top:18px;">
                                <input id="export_btn" type="submit" name="Excel" value="Export" class="button"/>
                            </div ></div>
                        
                        <?php } ?>-->
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
