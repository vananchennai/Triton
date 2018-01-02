		<?php

 include '../../functions.php';
		sec_session_start();
 require_once '../../masterclass.php';
 include("../../header.php");
 global $fname;
 unset($_SESSION['errorlogs']);
	if(login_check($mysqli) == false)
	{
			header('Location:../../index.php');
	}
	else
	{
				$news = new News(); 
				$pagename = "masterimport";
				$validuser = $_SESSION['username'];
				$selectvar =mysql_query( "select * from userrights where userid = '$validuser' and screen = '$pagename'");
				$row = mysql_fetch_array($selectvar);

		if (($row['viewrights'])== 'No')
		{
				header("location:/".$_SESSION['mainfolder']."/home/home/master.php");
		}
				if(isset($_POST['permiss'])) // If the submit button was clicked
				{
					?>
					<script type="text/javascript">
					alert("you are not allowed to do this action!",'Bulkupload.php');
					</script>
					<?
				}
				
		if(isset($_POST['Submit']))
		{
					$Mastertype=$_POST['MasterTypeName'];
					$Importtype=$_POST['SheetFileName'];
			
					global $HTTP_POST_FILES,$y,$errlist;
					$errlist="";
					ini_set('html_errors',0);
					ini_set('display_errors',0);
					$Path= $_FILES['ufile']['name'];
					$filetype = substr($Path, -3);
			if($filetype == 'xls')
			{
			
				if(copy($_FILES['ufile']['tmp_name'], $path))
				{
					include 'reader.php';
					$fname= $path;
					
					$excel = new Spreadsheet_Excel_Reader();
					$excel->setOutputEncoding('CP1251');
					$excel->read($path);
					$x=2;
					$sep = "*";
					//ob_start();
						while($x<=$excel->sheets[0]['numRows'])
						{
							$y=1;
							$row="";
								while($y<=$excel->sheets[0]['numCols'])
								{
									$cell = isset($excel->sheets[0]['cells'][$x][$y]) ? $excel->sheets[0]['cells'][$x][$y] : '';
									$row.=($row=="")?"".$cell."":"".$sep."".$cell."";
									$y++;
								} 
							echo $row."\n"; 
							$x++;
						}
					$fp = fopen("data.csv",'w');
					fwrite($fp,ob_get_contents());
					fclose($fp);
					//ob_end_clean();
					$handle = fopen('data.csv',"r"); 
					global $data,$cnt;
					$cnt=0;
					$line=NULL;
				
				
					Switch($_POST['MasterTypeName'])
					{
					
						case "CustomerMaster":
						
							if($fname=="CustomerMaster.xls")
							{
								$cnt1=0; $cnt2=0;
								while ($data = fgetcsv($handle,2000,"*","'"))
								{ 
									echo $CM1=trim($data[0]);
									echo $CM2=trim($data[1]);
									$CM3=trim($data[2]);
									$CM4=trim($data[3]);
									$CM5=trim($data[4]);
									$CM6=trim($data[5]);
									$CM7=trim($data[6]);
									$CM8=trim($data[7]);
									$CM9=trim($data[8]);
									$CM10=trim($data[9]);
									$CM11=trim($data[10]);
									$CM12=trim($data[11]);
									if($ch1!="" && $ch2!="")
									{
										$CustomerName	= trim(str_replace("&"," and ",$data[0]));
										$Customer_Condition="SELECT  * FROM  `customer_details` WHERE `Customer_Name`= '$CustomerName'";
										$Customer_Refer=mysql_query($Customer_Condition);
										$Customer_myrow=mysql_num_rows($Customer_Refer);
										$CustomerName=strtoupper($CustomerName);	
										date_default_timezone_set ("Asia/Calcutta");
										$insertdate= date("y/m/d : H:i:s", time());
										$updatedate= date("y/m/d : H:i:s", time());
										if($Customer_myrow>0)							
										{
											$user_id = $_SESSION['username'];
											$Customer_Condition1="Delete From customer_details WHERE `Customer_Name`= '$CustomerName'";
											$Customer_Condition2="INSERT INTO `customer_details`(Customer_Name, Customer_Code, Customer_Address, Customer_TINNo, Customer_CSTNo, Customer_Range, Customer_Division, Customer_CINNo, Customer_PANNo, Customer_VATNo, Customer_ECCNo, Customer_LSTNo, user_id, insertdate, update_date) VALUES ('$CM1','$CM2','$CM3','$CM4','$CM5','$CM6','$CM7','$CM8','$CM9','$CM10','$CM11','$CM12','$user_id','$insertdate','$updatedate')";
											$Customer_Execute1=mysql_query($Customer_Condition1);
											$Customer_Execute2=mysql_query($Customer_Condition2);
											if($Customer_Execute1 && $Customer_Execute2 )
											$cnt1++;
										}
										else if ($Inv_myrow1 == 0)
										{
											$Customer_Condition3="INSERT INTO `customer_details`(Customer_Name, Customer_Code, Customer_Address, Customer_TINNo, Customer_CSTNo, Customer_Range, Customer_Division, Customer_CINNo, Customer_PANNo, Customer_VATNo, Customer_ECCNo, Customer_LSTNo, user_id, insertdate, update_date) VALUES ('$CM1','$CM2','$CM3','$CM4','$CM5','$CM6','$CM7','$CM8','$CM9','$CM10','$CM11','$CM12','$user_id','$insertdate','$updatedate')";
											$Customer_Execute3=mysql_query($Customer_Condition3);
											if($Customer_Execute3)
											$cnt1++;
										}
										else
										{
											$_SESSION['errorlogs']=$_SESSION['errorlogs']."\n `".$ch5."` Duplicate Entry \n";
										}
									}
									else
									{
										$_SESSION['errorlogs']=$_SESSION['errorlogs']."\n `".$ch5."` Missing mandatory or code & name are same.";
									}
								}
								echo $cnt1;
								if($cnt1>0 || $cnt2>0)
								{
								   $tvalues['userid']=$validuser;
									$tblname='fileloadedstatus';
									$tvalues['master_name']='Product';
									$tvalues['file_name']=$fname;
									$news->addNews($tvalues,$tblname);
									echo"<script>alert('File $path Imported Successfully','Master_Excel_Import_All.php')</script>";
									$news->unlinkfun($path);
								}
								else
								{
									echo"<script>alert('Data not imported from $path!. Please check the file for format / duplication.','Master_Excel_Import_All.php')</script>";
									$news->unlinkfun($path);
								}
							}
							else
							{
								echo"<script>alert('File name mismatch','Master_Excel_Import_All.php')</script>";
								$news->unlinkfun($path);
								$fname="";
							}	 		  
						break;
						//Case "TaxMaster"
						default : 
							echo"<script>alert('Select proper master name','Master_Excel_Import_All.php')</script>";
						break;
					}
				}

			}
			else
			{
				echo"<script>alert('File $path is not in the required Format','Master_Excel_Import_All.php')</script>";
				if($path!="")
				{
					$news->unlinkfun($path);
				}
			}
			break;
		
			if (!empty($_SESSION['errorlogs'])) 
			{
				echo '<META HTTP-EQUIV="Refresh" Content="0; URL=errorlogfile.php">';
				exit;
			}
		}
		
		
	if (!empty($_SESSION['errorlogs'])) 
	{
	//echo $_SESSION['errorlogs'];
	//header('Location:errorlogfile.php');
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=errorlogfile.php">';
	//exit;
	}

?>

<title><?php echo $_SESSION['title']; ?></title>
</head>
<body><center>
<?php include("../../menu.php") ?>
<div style="width:100%; height:auto; float:none;">
	<div style="width:980px; height:auto; float:none; margin-top:8px;" class="container">
		<div style="width:950px; height:auto; float:left; margin-bottom:10px; margin-left:15px;" id="center">  
			<form method="POST" action="<?php $_PHP_SELF ?>" enctype="multipart/form-data">
				<div style="width:930px; height:auto; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;">
					<div style="width:930px; height:40px; float:left;  margin-left:0px;" class="head">
						<p>Master Excel Import</p>
					</div>
					<div style="width:900px; height:auto; padding-bottom:5px; float:left;margin-left:50px; " class="cont" >
						<div style="width:155px; height:30px;  float:left; margin-top:5px; margin-left:3px;">
							<label>Select Type</label><label style="color:#F00;">*</label>
						</div>
						<div style="width:200px; height:25px;  float:left;  margin-top:5px; margin-left:3px;">
							<select name="SheetFileName">
								<option value=""><--Select--></option> 
								<option value="Excel">Excel File</option>
								<option value="CSV">CSV File</option> 
								<option value="Text">Text File</option> 								
							</select>
						</div>
					</div>
					
					<div style="width:900px; height:auto; padding-bottom:5px; float:left;margin-left:50px; " class="cont">
						<div style="width:155px; height:30px;  float:left; margin-top:5px; margin-left:3px;">
							<label>Select Part Master</label><label style="color:#F00;">*</label>
						</div>
						<div style="width:200px; height:25px;  float:left;  margin-top:5px; margin-left:3px;">
							<select name="MasterTypeName">
								<option value=""><--Select--></option> 
								<option value="PartMaster">Part Master</option> 
								<option value="CustomerMaster">Customer Master</option> 
								<option value="TaxMaster">Tax Master</option> 
								<option value="RateMaster">Rate Master</option> 
								<option value="TransportMaster">Transport Master</option> 
								<option value="VehicleMaster">Vehicle Master</option> 								
							</select>
						</div>
					</div>
				</div>
				<div style="width:925px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
					<div style="width:900px; height:auto; padding-bottom:5px; float:left; " class="cont">
						<div style="width:70px; height:32px; float:left; margin-top:16px; margin-left:15px;">
							<div style="width:560px; height:50px; float:left;  margin-left:185px; margin-top:0px;" class="cont" id="center2">
								<div style="width:70px; height:40px; float:left; margin-left:3px; margin-top:10px;" >
									<h1 style="size:60px;"> <label>Select file</label></h1>
								</div>
								<div style="width:195px; height:30px;  float:left; margin-left:3px; margin-top:16px;">
									<input name="ufile" type="file" id="ufile" accept="application/vnd.ms-excel"/>
								</div>  
								<div style="width:40px; height:30px; float:right; margin-right:150px; margin-top:16px;">
									<input name="<?php if(($row['editrights'])=='Yes') echo 'Submit'; else echo 'permiss'; ?>" type="submit" class="button" value="Import">
								</div>
							</div> 
						</div>                              
					</div>
				</div>
			</form>               
		</div> 
	</div>       
</div>
<div id="footer-wrap1">
<?php include("../../footer.php") ?>
</div>
</center></body>
</html>
<?
}
?>
