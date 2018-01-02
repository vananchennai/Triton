<?php 
	include '../../functions.php';
	sec_session_start();
	require_once '../../masterclass.php';
	include("../../header.php");
	require_once '../../paginationfunction.php';
	$scode = 'Franchisecode';
	$sname = 'Franchisename'; 
	$tname = 'franchisemaster';
	$ttname	= "pgroupmapping";
	require_once '../../searchfun.php';
	$news = new News(); // Create a new News Object
	$pagename = "Product Group Mapping";
	$validuser = $_SESSION['username'];
	//$slectedgroupcode = array();
	
	//Page Verification Code and User Verification
	$selectvar =mysql_query( "select * from userrights where userid = '$validuser' and screen = '$pagename'");
	$row = mysql_fetch_array($selectvar);
	if (($row['viewrights'])== 'No')// validate the view right for user
	{
		header("location:/".$_SESSION['mainfolder']."/home/home/master.php");
	}
	if(isset($_POST['permiss']))
    {
		echo '<script type="text/javascript">alert("you are not allowed to do this action!","productgroupmapping.php");</script>';
	}
	if(isset($_GET['permiss']))
	{
			echo '<script type="text/javascript">alert("you are not allowed to do this action!","productgroupmapping.php");</script>';
	}
	
	global $distributor;
	
	?>

	
	<?
	// UPDATE Function - Tries to alter the values assigned for user
	if(isset($_POST['Update']))
	{	
		$post['distributor'] = isset($_POST['distributor']) ? $_POST['distributor'] : NULL;
		//$post['usertype'] = isset($_POST['usertype']) ? $_POST['usertype'] : NULL;
		//$usertype = $post['usertype'];
	/* 	if($post['usertype']=='Others')
			{	
				$sel_count= 0;
				foreach ($_POST['branch'] as $sel_value){
					if($sel_value!="")
					{
						$sel_count=$sel_count+1;
					}
				}
				if($sel_count==0)
				{
					echo '<script type="text/javascript">alert("Enter mandatory values!");</script>';
					$_POST['usertype']=NULL;
				}
			} */
			
		
			
				//$news->deleteNews('pgroupmapping',"distributorcode ='".$_POST['distributor']	."'");
				// $news->deleteNews('reportrights_fsub',"userid ='".$_POST['userid']	."'");
			/* foreach ($_POST['branch'] as $sel_value){
			//echo $sel_value;
				if($sel_value!="")
				{
					$sub_post['distributor'] = isset($_POST['distributor']) ? $_POST['distributor'] : NULL;
					//$sub_post['branch'] = $sel_value;
					$news->addNews($sub_post,'reportrights_sub');
				}
			} */
			// if($post['usertype']=='Others'){
			// 	foreach ($_POST['franchise'] as $sel_value){
			// 	//echo $sel_value;
			// 		if($sel_value!="")
			// 		{
			// 			$sub_post1['userid'] = isset($_POST['userid']) ? $_POST['userid'] : NULL;
			// 			$sub_post1['franchise'] = $sel_value;
					
			// 			// echo $sel_value;
			// 			// $sub_post1['branch'] = $bname;
			// 			$news->addNews($sub_post1,'reportrights_fsub');
			// 		}
			// 	}
			// }
			$stname='productmasterupload';
			$newselectedgroup = "";
			$primary_dis_code = mysql_query("SELECT PrimaryFranchise FROM franchisemaster WHERE Franchisecode='".$_POST['distributor']."'");
			while($primary_result = mysql_fetch_array($primary_dis_code)){
				$pri_dis_code = $primary_result['PrimaryFranchise'];
			}
			if(!empty($_POST['distributor'])){
				$delete_user_rights = mysql_query("DELETE FROM pgroupmapping WHERE distributorcode ='".$_POST['distributor']."'");
		    	// echo "DELETE FROM reportrights WHERE userid ='".$_POST['userid']."'";
		    	$master = mysql_query("select ProductCode,ProductGroup from productgroupmaster");
		    	//var_dump($slectedgroupcode);
		    	date_default_timezone_set ("Asia/Calcutta");
				$Insdate= date("Y-m-d");
				while($master_result = mysql_fetch_array($master)){  
					$screename = trim(str_replace(" ","",$master_result['ProductCode']));
					// echo isset($_POST[$screename])."\t";
				
					if(isset($_POST[$screename])){ $check= 'Yes'; }else{ $check = 'No'; };
					//echo $check."\n";
					$insert_query = "insert into pgroupmapping(distributorcode,PrimaryFranchise, productgroupcode, mapping) VALUES('".$_POST['distributor']."','".$pri_dis_code."','".$master_result['ProductCode']."','".$check."')";
					//echo $insert_query;
					$master_insert = mysql_query($insert_query);
					// for($i=0;$i<count($slectedgroupcode);$i++){
						$selectgroup = array();
						$selectgroup = explode("~",$_SESSION['selectgroupcode']);
						if(!in_array($master_result['ProductCode'],$selectgroup)){
						// if($slectedgroupcode[$i] != $master_result['ProductCode']){
							if($check == "Yes"){
								$newselectedgroup = $newselectedgroup."'".$master_result['ProductCode']."',";
								$insert_query = "insert into productgroupupload(Franchiseecode, PrimaryFranchise, Masters, Code, Status, InsertDate) VALUES('".$_POST['distributor']."','".$pri_dis_code."','productgroupmaster','".$master_result['ProductCode']."','0','".$Insdate."')";
								$insert_pgupload = mysql_query($insert_query);
							}
						}
					//}
					
						
					
					// $resultproduct="SELECT a.ProductCode, a.ProductDescription, a.ProductGroupCode, a.productuom FROM productmaster_view  a Left join pgroupmapping p on  p.distributorcode='".$_POST['distributor']."' WHERE a.ProductGroupCode='".$master_result['ProductCode']."' and p.mapping='YES' and   NOT EXISTS(SELECT  null FROM productmasterupload d WHERE d.Code=a.ProductCode and d.Franchiseecode='".$_POST['distributor']."')Group By a.ProductCode;";
					// $sqlproduct= mysql_query($resultproduct) or die (mysql_error());
		   //          $productproductlist=null;
		   //          $prtCount=null;
		   //          $prtCount=mysql_num_rows($sqlproduct);
					
					// while($rowproduct = mysql_fetch_array($sqlproduct))
					// {
					// $ProductCode=$rowproduct['ProductCode'];
		   //          $ProductDescription=$rowproduct['ProductDescription'];
					// $ProductGroupCode=$rowproduct['ProductGroupCode'];
					// $UOM=$rowproduct['productuom'];
					// date_default_timezone_set ("Asia/Calcutta");
					// $Insdate= date("Y/m/d");
					// /* echo $ProductCode;
					// exit; */ 
					// echo '<script type="text/javascript">alert("'.$ProductCode.'","productgroupmapping.php");</script>';
					// $insert_uploadquery = "insert into productmasterupload(Franchiseecode, Masters, Code, Status, InsertDate) VALUES('".$_POST['distributor']."','productmaster','".$ProductCode."','0','".$Insdate."')";
					// $master_insert = mysql_query($insert_uploadquery);
					// }
					
					
					
				}
				if(!empty($newselectedgroup)){
					$newselectedgroup = substr($newselectedgroup, 0, -1);
					$newselectedgroup = '"'.$newselectedgroup.'"';
					$dis_code = "'".$_POST['distributor']."'";
					$pri_dis_code = "'".$pri_dis_code."'";
					$proqry = "CALL sp_productupload($newselectedgroup,$dis_code,$pri_dis_code);";
					$qry_exe = mysql_query($proqry);
				}

				// exit;
				echo '<script type="text/javascript">alert("Updated Sucessfully..!!","productgroupmapping.php");</script>';
			}
			else{
				echo '<script type="text/javascript">alert("Please Enter the mandatory values..!!","productgroupmapping.php");</script>';
			}
		
	}

//EDIT
if(!empty($_GET['edi']))
{
	$prmaster =$_GET['edi'];
	
	$userid=$prmaster;
 	
	$post['distributor'] = $prmaster;
	
	 // $i=array("Serial Number History","Data Exchange","Purchase Order","Purchase Report","Purchase Summary","Purchase Returns", "Sales Register","Sales Report","Weekly Sales Report","Retailer Category Detailed","Retailer Category Summary","Sales Returns","Stock Ledger","ServiceCallRegister","Warranty Administration","Service Compensation Claim","SAP Upload",);
	// $j=$i;
/* 	$sub_qry =mysql_query("SELECT b.branchname from reportrights_sub rrs LEFT JOIN branch b ON rrs.branch=b.branchcode where rrs.userid = '".$post['userid']."'");
	// $sub_fqry =mysql_query("SELECT f.Franchisecode as Franchisecode from reportrights_fsub rrs LEFT JOIN franchisemaster f ON rrs.franchise=f.Franchisecode where rrs.userid = '".$post['userid']."'");
	$sub_fbqry =mysql_query("SELECT f.branchname as branchname from reportrights_sub rrs LEFT JOIN view_fbr f ON rrs.branch=f.Branch where rrs.userid = '".$post['userid']."' GROUP BY f.branchname");
	// echo "SELECT f.branchname as branchname from reportrights_sub rrs LEFT JOIN view_fbr f ON rrs.branch=f.Branch where rrs.userid = '".$post['userid']."' GROUP BY f.branchname";
	$sele_bran = mysql_query("SELECT b.branchname from reportrights_sub rrs LEFT JOIN branch b ON rrs.branch=b.branchcode where rrs.userid = '".$post['userid']."'");
	while ($rowlist = mysql_fetch_assoc($sele_bran)) {
		$sele_bran_val = $sele_bran_val. "branchname = '" .$rowlist['branchname']."'  OR ". "\n";
	} 

	$sele_bran_val = substr($sele_bran_val, 0, -4); 
	while ($sub_row[] = mysql_fetch_array($sub_qry));
	// while ($sub_frow[] = mysql_fetch_array($sub_fqry));
	while ($sub_fbrow[] = mysql_fetch_array($sub_fbqry));*/
/* 	$usertypeqry = mysql_query("SELECT distributorcode  FROM pgroupmapping where distributorcode='".$userid."'");
	while ($userrow[] = mysql_fetch_array($usertypeqry));
	$usertype = $userrow[0][0]; */
	
}
		// var_dump($sub_fbrow);

if(isset($_POST['Cancel']))
{	
	$_SESSION['codesval']=NULL;
	$_SESSION['namesval']=NULL;
	header('Location:productgroupmapping.php');
}
?>
<script type="text/javascript">
function all_columncheck()
{
	var flag = 0;
	$("#pagelist option").each(function()
	{
    		var ele = $(this).val();
    		opele = '#'+ele;
    		if(!$(opele).is(':checked')){
    			flag++;
    		}
    		if(flag == 0){
    			$('input[name=allselect1]').attr('checked',true);
    		}else{
    			$('input[name=allselect1]').attr('checked',false);
    		}
    		// $(opele).attr('checked', false);
    	});
	// if($('input[id=snh_right]').is(':checked')&&$('input[id=de_right]').is(':checked')&&$('input[id=po_right]').is(':checked')&&$('input[id=pr_right]').is(':checked')&&$('input[id=ps_right]').is(':checked')&&$('input[id=prs_right]').is(':checked')&&$('input[id=srr_right]').is(':checked')&&$('input[id=sr_right]').is(':checked')&&$('input[id=wsr_right]').is(':checked')&&$('input[id=rcd_right]').is(':checked')&&$('input[id=rcs_right]').is(':checked')&&$('input[id=srs_right]').is(':checked')&&$('input[id=sl_right]').is(':checked')&&$('input[id=scr_right]').is(':checked')&&$('input[id=wa_right]').is(':checked')&&$('input[id=scc_right]').is(':checked')&&$('input[id=su_right]').is(':checked'))
	// {
	// 	$('input[name=allselect1]').attr('checked',true);
	// }
	// else
	// {
	// 	$('input[name=allselect1]').attr('checked',false);
	// }
}


function all_checked()
{
	if($('input[id=allselect1]').is(':checked'))
	{
		$("#pagelist option").each(function()
		{
    		var ele = $(this).val();
    		opele = '#'+ele;
    		$(opele).attr('checked', true);
    	});
	}
	else
	{
		$("#pagelist option").each(function()
		{
    		var ele = $(this).val();
    		opele = '#'+ele;
			if($(opele).is(':disabled')){
    			//flag++;
				$(opele).attr('checked', true);
    		}else{
			$(opele).attr('checked', false);
			}
    		
    	});
	}
}

 </script>
 <!-- <script src="multiselect.js" type="text/javascript"></script> -->
<title>SSV || Product Group Mapping</title>
</head>
 <?php
  
if(empty($_SESSION['codesval']) && empty($_SESSION['namesval']))
{ ?> <body class="default" > <? } 
else
{ ?> <body class="default" onLoad="document.form1.codes.focus()"> <? } ?><center>
<?php include("../../menu.php") ?>

<!--Third Block - Container-->
<div style="width:100%; height:auto; float:none;">
     <div style="width:980px; height:auto; float:none; margin-top:8px;" class="container">
         <div style="width:950px; height:auto; float:left; margin-bottom:10px; margin-left:15px;" id="center">  
			<!-- form id start-->
			<form method="POST" name="form1" action="<?php $_PHP_SELF ?>">
				  <table id="default" style=" height:10px; display:none;" >
            <tr>
                <td>
                                      
                                        
                                        
                                    <select  name="forlist" id="forlist">
                                     <?
                                                                                
                                        $que = mysql_query("SELECT branchcode,Franchisecode,Franchisename FROM `view_rptfrnfin`");
                                     while( $record = mysql_fetch_array($que))
                                     {
									        
                                      echo "<option value=\"".$record['branchcode']."~".$record['Franchisecode']."~".$record['Franchisename']."\">".$record['branchcode']."~".$record['Franchisename']."~".$record['Franchisecode']."\n "; 
									  }
                              		 
                                    ?>
                                          </select>
                </td>
                <td>
             				<select  name="pagelist" id="pagelist">
                                     <?
                                                                                
                                        $master = mysql_query("select ProductCode from productgroupmaster");
                                       
                                     while( $record = mysql_fetch_array($master))
                                     {
                                     	$screename = trim(str_replace(" ","",$record['ProductCode']));
                          
                                      echo "<option value=\"".$screename."\">".$screename."\n "; 
                    				 }
                                   
                                    ?>
                            </select>	
             	</td>
                                      
                                      </tr>
                                     
                                     
                                      
            </table>
				<div style="width:930px; height:auto;   min-height: 250px; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;">
					<div style="width:930px; height:25px; float:left;  margin-left:0px;" class="head">
						<p>Product Group Mapping Master</p>
					</div>
					<!-- main row 1 start-->     
					<div style="width:925px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
						<!-- col1 -->   
                        <div style="width:300px; height:auto; padding-bottom:5px; float:left; " class="cont">
							<!--Row1 -->  
                            <div style="width:100px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Distributor Code:</label>
                            </div>
                            <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
								<input type="text" name="distributor" style="border-style:hidden; background:#f5f3f1;" readonly="readonly" value="<?php echo $userid;?>"/>
                            </div>
                            
							<!--Row1 end-->                       
                         </div>                             
						<!-- col1 end --> 
						

                             <!-- col2 end--> 
                             <!---Franchise Start---->
                            
                             	 <!-- <div id="div_franchise" class="cont" <?/*  echo $frnstyle;  */?> >
                                
                                   <div style="width:80px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                        <label>Franchisee</label><label style="color:#F00;">*</label>
                                    </div>
                                    <div style="width:185px; height:100px;  float:left;  margin-top:5px; margin-left:3px; ">
                                    	 <? //var_dump($sub_frow); ?>
                                         <select  name='franchise[]' id='franchise'  multiple="multiple" onChange="allfranchisee();">
                                            
                                            <?
                                            // $sele_bran_val
/*                                             $list = mysql_query("SELECT Franchisecode,Franchisename FROM view_fbr WHERE ".$sele_bran_val." ORDER BY Franchisename ASC");
                                            while ($row_list = mysql_fetch_assoc($list)) {
                                                $selected = '';
                                                   foreach ($sub_frow as $franchise_select) {
													if ($row_list['Franchisecode'] == $franchise_select['Franchisecode']) {
                                                    $selected = ' selected ';
													}
													unset($franchise_select);
												}
                                                ?>
                                                <option value="<? echo $row_list['Franchisecode']; ?>" <? echo $selected; ?>><? echo trim($row_list['Franchisename']); ?></option>
                                                <?
                                            } */
                                            ?>
                                        </select>
                                        
                                    </div>
                                <!--Row2 2FIELD end-->  
                                 <!-- </div> -->
 


                             <!--Franchise End ------>    
                             </div>
						<div style="width:600px; height:60px;"></div>
							<!-- col2 -->   
							<div style="width:300px; height:auto; float:left; padding-bottom:5px; margin-left:2px;" class="cont">                      
	<table  width="750" style=" float:left; margin-left:80px; border-color:#058AFF; border-bottom-color:#058AFF; border-left-color:#058AFF; border-right-color:#058AFF; border-top-color:#058AFF;" border="2" >
		<?            
				$master_query = mysql_query("select ProductCode,ProductGroup from productgroupmaster order by ProductGroup ");
		     	$report_query = mysql_query("select * from pgroupmapping where distributorcode = '".$userid."'");
				$master_rows = mysql_num_rows($master_query);
		     	$report_rows = mysql_num_rows($report_query);
		     	$rights_unchecked = 0;
		     	if($master_rows !=  $report_rows){
		     		$allselect = '';
		     	}else{
		     		while($master_result[] = mysql_fetch_array($master_query));
					while($report_result[] = mysql_fetch_array($report_query));
					for($i=0;$i<$master_rows;$i++){
		     			$flag = 0;
		     			for($j=0;$j<$report_rows;$j++){
		     				if($master_result[$i]['ProductCode']== $report_result[$j]['productgroupcode']) { 
		     					$screename = trim(str_replace(" ","",$master_result[$i]['ProductCode'])); 
								if($report_result[$j]['mapping'] == 'Yes'){
									$rights = 'checked="checked"';
								}else{
									$rights_unchecked++;
								}
			     				$flag = 1;
								break;
		     				}
		     			}
		     			if($flag == 0){
		     				$allselect = '';
		     				break;
		     			}
		     		}
	     			if($rights_unchecked == 0){
	     				$allselect = 'checked="checked"';
	     			}else{
	     				$allselect = '';
	     			}
		     	}
?>
		<tr style="border-color:#058AFF; background-color:#058AFF;">
			<td style="border-color:#058AFF; text-align:center;" width="80px"><h4  style="color:white;">Product Group Code</h4></td>
			<td style="border-color:#058AFF; text-align:center;" width="80px"><h4  style="color:white;">Product Group Name</h4></td>
			<td style="border-color:#058AFF;"width="40px"><div align="justify" style="margin-top:2px;"><h4   style=" text-align:left;color:white;">
			<center>Select All</center><center>
			<input style="text-align:center" type="checkbox" name="allselect1" id="allselect1" <?php echo $allselect; ?> onClick="all_checked()" /></center>
			</h4></td>
       	</tr>
       <?	if(empty($userid)){ 
     		$report_query = mysql_query("select ProductCode,ProductGroup from productgroupmaster order by ProductGroup");
     			while($report_result = mysql_fetch_array($report_query)){  ?>
        			<tr style="border-color:#058AFF; background-color:#F9F9F9;">
					<td style="border-color:#058AFF;text-align:justify;"><h4  style="color:Black;"><? echo ucwords($report_result['ProductCode']); ?></h4></td>
					<td style="border-color:#058AFF;text-align:justify;"><h4  style="color:Black;"><? echo ucwords($report_result['ProductGroup']); ?></h4></td>
					<? $screename = trim(str_replace(" ","",$report_result['ProductCode'])); ?>
						<td style="border-color:#058AFF;text-align:center;"><input type="checkbox" id="<? echo $screename; ?>" name="<? echo $screename; ?>"  value="" onclick="all_columncheck()" /></td>

					</tr>

     	<? 		} 

     		}else{
     			$master_query = mysql_query("select ProductCode,ProductGroup from productgroupmaster order by ProductGroup");
		     	// $user_query = "select p.pagename as screen,u.viewrights,u.addrights,u.deleterights,u.editrights from userrights u left join pagename p on p.pagename = u.screen where p.pagetype='Master' and u.userid='".$userid."' order by p.sorting"; 
		     	$report_query = mysql_query("select * from pgroupmapping where distributorcode = '".$userid."'");
		     	// echo $user_query;
		     	// echo "<br>select pagename from pagename where pagetype='Master' order by sorting";
		     	// $reportrights_query = mysql_query($report_query);
		     	$master_rows = mysql_num_rows($master_query);
		     	$report_rows = mysql_num_rows($report_query);
		     	// echo $master_rows.'<br>'.$user_rows;
		     		
		     		while($master_result[] = mysql_fetch_array($master_query));
					while($report_result[] = mysql_fetch_array($report_query));
					$k =0;
					$slectedgroupcode = array();
					for($i=0;$i<$master_rows;$i++){
		     			$flag = 0;
		     			for($j=0;$j<$report_rows;$j++){
		     				if($master_result[$i]['ProductCode']== $report_result[$j]['productgroupcode']) { ?>
		     					<tr style="border-color:#058AFF; background-color:#F9F9F9;">
								<td style="border-color:#058AFF;text-align:justify;"><h4  style="color:Black;"><? echo ucwords($master_result[$i]['ProductCode']); ?></h4></td>
								<td style="border-color:#058AFF;text-align:justify;"><h4  style="color:Black;"><? echo ucwords($master_result[$i]['ProductGroup']); ?></h4></td>
								<? $screename = trim(str_replace(" ","",$master_result[$i]['ProductCode'])); 
									if($report_result[$j]['mapping'] == 'Yes'){
										$rights = 'checked="checked"';
										//$disabled='';
										$disable = 'disabled="true"';
										$slectedgroupcode[$k] = $master_result[$i]['ProductCode'];
										$k++;
									}else{
										$rights = '';
										$disable = '';
									}?>
								<td style="border-color:#058AFF;text-align:center;"><input type="checkbox" id="<? echo $screename; ?>" name="<? echo $screename; ?>"  value="" <? echo $rights;?> <? echo $disable; ?> onclick="all_columncheck()" /></td>

								</tr>

		     			<?	$flag = 1;
							break;

		     				}
		     			}
		     			if($flag == 0){
		     				$screename = trim(str_replace(" ","",$master_result[$i]['ProductCode'])); ?>
		     				<tr style="border-color:#058AFF; background-color:#F9F9F9;">
							<td style="border-color:#058AFF;text-align:justify;"><h4  style="color:Black;"><? echo ucwords($master_result[$i]['ProductCode']); ?></h4></td>
							<td style="border-color:#058AFF;text-align:justify;"><h4  style="color:Black;"><? echo ucwords($master_result[$i]['ProductGroup']); ?></h4></td>
							<td style="border-color:#058AFF;text-align:center;"><input type="checkbox" id="<? echo $screename; ?>" name="<? echo $screename; ?>"  value=""  onclick="all_columncheck()" /></td>
							</tr>


		     		<?
		     			}
		     		}
		     		$_SESSION['selectgroupcode'] = implode("~",$slectedgroupcode);	
		     	
     		}	?>
        
	</table>   
                </div>                             
				<!-- col2 end--> 

				<!-- col3 -->   
				<div style="width:300px; height:auto; float:left; padding-bottom:5px; margin-left:2px;" class="cont">
				</div>      
				<!-- col3 --> 

			</div>
		</div>
		<!-- main row 1 end-->
		<!--Main row 2 start-->
		<div style="width:1000px; height:60px; float:left; margin-left:8px; margin-top:8px;">
			<div style="width:235px; height:50px; float:left;  margin-left:5px; margin-top:0px;" id="center1">
				<div style="width:90px; height:32px; float:left; margin-top:16px; margin-left:25px;">
					<input name="<?php if(($row['editrights'])=='Yes') echo 'Update'; else echo 'permiss'; ?>" type="submit" id="updatebutton" class="button" value="Update" >
				</div>	
				<div style="width:90px; height:32px; float:left;margin-top:16px; ">
					<input name="Cancel" type="submit" class="button" value="Reset">
				</div>                          
			</div>	
			<div style="width:640px; height:50px; float:left;  margin-left:75px; margin-top:0px;" class="cont" id="center2">
                              <!--Row1 -->  
							  <br/>
                               <div style="width:100px; height:32px; margin-left:20px; float:left;"  class="cont">
						     <label>Distributor Code </label>
				           </div>	
                           
                           <div style="width:130px; height:32px; margin-left:10px; float:left;" class="cont">
						    <input type="text" name="codes" id="codes" onKeyPress="searchKeyPress(event);" value="<? echo $_SESSION['codesval']?>"/>
				           </div> 
                           
                            <div style="width:110px; height:32px; margin-left:20px; float:left;" class="cont">
						     <label>Distributor Name</label>
				           </div>	
                           
                           <div style="width:130px; height:32px; margin-left:10px; float:left;" class="cont">
						    <input type="text" name="names" onKeyPress="searchKeyPress(event);" value="<? echo $_SESSION['namesval']?>"/>
				           </div> 
						   
						   <div style="width:80px; height:32px; margin-left:10px; float:left;">
						  <input name="Search" type="submit" id="Search" class="button" value="Search">
				           </div>    
                          </div> 
		</div><!--Main row 2 end-->
		<!--  grid start here-->
        <div style="width:950px; height:auto; padding-bottom:8px; margin-top:20px; float:left; margin-left:13px;" class="grid">
			<table align="center" class="sortable" bgcolor="#FF0000" border="1" width="950px">
				 <tr>
					 <td class="sorttable_nosort" style="font-weight:bold; text-align:center;" width="12px">Action</td>
					 <td style="font-weight:bold;">Distributor Code</td>
					 <td style="font-weight:bold;">Distributor Name</td>
				</tr>
<?php
      // This while will loop through all of the records as long as there is another record left. 
      // Basically as long as $record isn't false, we'll keep looping.
      // You'll see below here the short hand for echoing php strings.
      // <?=$record[key] - will display the value for that array.

		while( $record = mysql_fetch_array($query))
		{
?>				<tr>
					<td style="font-weight:bold; text-align:center" bgcolor="#FFFFFF"> <a style="color:#0360B2" name="edit" href="productgroupmapping.php? <?php if(($row['editrights'])=='Yes'){ echo 'edi=';  echo $record['Franchisecode']; } else echo 'permiss'; ?> " >Edit</a></td>
					<td  bgcolor="#FFFFFF"><?=$record['Franchisecode']?></td>
<?php 
		/* $empqry= mysql_query("select Franchisecode from franchisemaster where Franchisecode='".$record['empcode']."' ")  ;
		$emprec = mysql_fetch_array($empqry);  */
?>
					<td  bgcolor="#FFFFFF" ><?=$record['Franchisename']?></td>
					
				</tr>
<?php 	}
		if(isset($_POST['Search']))
		{
			if($myrow1==0)	
			{ 
				echo '<tr ><td colspan="11" align="center" bgcolor="#FFFFFF" style="color:#F00"  >No Records Found</td></tr>'; 
			} 
		}
?>
			</table>
		</div> <?php include("../../paginationdesign.php") ?> <!--  grid end here-->
		 <script>// all scripts used to eliminate duplication in dropdown.
			 
                                    // Set the present object
                                    var present = {};
         //                            $('#usertype option').each(function(){
         //                            // Get the text of the current option
         //                            var text = $(this).text();
         //                            // Test if the text is already present in the object
         //                            if(present[text]){
         //                            // If it is then remove it
         //                            $(this).remove();
         //                            }else{
         //                            // Otherwise, place it in the object
         //                            present[text] = true;
         //                            }
									// enablebranch();
         //                            });
									var present = {};
                                    $('#usertype option').each(function(){
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
	</form><!-- form id start end-->      
        </div> 
	</div>       
</div><!--Third Block - Menu -Container -->
<!--Footer Block -->
<div id="footer-wrap1"> <?php include("../../footer.php") ?> </div><!--Footer Block - End-->
</center></body>
</html>

<script type="text/javascript">
 $(document).ready(function(){
	$("#updatebutton").click(function() {
		$("#pagelist option").each(function()
			{
				var ele = $(this).val();
				var opele = '#'+ele;
				$(opele).removeAttr("disabled");
			});
	   
	});
});
</script>