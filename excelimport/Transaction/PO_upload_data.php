<?php 

	include '../../functions.php';
	sec_session_start();
	require_once '../../masterclass.php';
	include("../../header.php");
   global $fname;
    unset($_SESSION['errorlogs']);
if(login_check($mysqli) == false) {
 echo "Mukesh Kumar";
  header('Location:../../index.php');// Redirect to login page!
 
} else
{
	
	$news = new News(); // Create a new News Object
	//$newsRecordSet = $news->getNews($tname);
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
	$mastertype=$_POST['bulkmasters'];
	global $HTTP_POST_FILES,$y,$errlist;
	$errlist="";
	
	ini_set('html_errors',0);
		ini_set('display_errors',0);
	
	//$path= $HTTP_POST_FILES['ufile']['name'];
	$path= $_FILES['ufile']['name'];
	$filetype = substr($path, -3);
	//echo $filetype;
	
		if($filetype =='xls')
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
			ob_start();

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
				//echo"----";	
				ob_end_clean();
				//$file = $_FILES['data.csv']['tmp_name']; 
				$handle = fopen('data.csv',"r"); 
				//loop through the csv file and insert into another csv 
				global $data,$cnt;
				$cnt=0;
			$line=NULL;
			// echo "mukesh";
	// exit;
			
switch($_POST['bulkmasters'])
{


case "Sales":

	
	if($fname=="Purchase_order_upload.xls")
	{
		$cnt1=0; $cnt2=0;
		while ($data = fgetcsv($handle,2000,"*","'"))
		{ 

			$ch1=trim($data[0]);
			$ch2=trim($data[1]);
			$ch3=trim($data[2]);
			$ch4=trim($data[3]);
			$ch5=trim($data[4]);
			$ch6=trim($data[5]);
			$ch7=trim($data[6]);
			$ch8=trim($data[7]);
			$ch9=trim($data[8]);
			$ch10=trim($data[9]);
			$ch11=trim($data[10]);
			$ch12=trim($data[11]);
			$ch13=trim($data[12]);
			$ch14=trim($data[13]);
			$ch15=trim($data[14]);
			$ch16=trim($data[15]);
			$ch17=trim($data[16]);
			$ch18=trim($data[17]);
			$ch19=trim($data[18]);
			$ch20=trim($data[19]);
			$ch21=trim($data[20]);
			$ch22=trim($data[21]);
			$ch23=trim($data[22]);
			$ch24=trim($data[23]);
			$ch25=trim($data[24]);
			$ch26=trim($data[25]);
			$ch27=trim($data[26]);
			$ch28=trim($data[27]);
			$ch29=trim($data[28]);
			$ch30=trim($data[29]);
			$ch31=trim($data[30]);
			$ch32=trim($data[31]);
			$ch33=trim($data[32]);
			$ch34=trim($data[33]);
			$ch35=trim($data[34]);
			$ch36=trim($data[35]);
			$ch37=trim($data[36]);
			$ch38=trim($data[37]);
			$ch39=trim($data[38]);
			$ch40=trim($data[39]);
			$ch41=trim($data[40]);
			$ch42=trim($data[41]);
			$ch43=trim($data[42]);
			$ch44=trim($data[43]);
			$ch45=trim($data[44]);
			$ch46=trim($data[45]);
			$ch47=trim($data[46]);
			$ch48=trim($data[47]);
			$ch49=trim($data[48]);
			$ch50=trim($data[49]);
			$ch51=trim($data[50]);
			$ch52=trim($data[51]);
			$ch53=trim($data[52]);
			$ch54=trim($data[53]);
			$ch55=trim($data[54]);
			$ch56=trim($data[55]);
			$ch57=trim($data[56]);
			$ch58=trim($data[57]);
			$ch59=trim($data[58]);
			$ch60=trim($data[59]);
			$ch61=trim($data[60]);
			$ch62=trim($data[61]);
			$ch63=trim($data[62]);
			$ch64=trim($data[63]);
			$ch65=trim($data[64]);
			$ch66=trim($data[65]);
			$ch67=trim($data[66]);
			
			if($ch1!="")
			{	
				 // $InvoiceNo						= trim(str_replace("&"," and ",$data[4]));
				 $PDSInvoiceNo					= trim(str_replace("&"," and ",$data[8]));
				 $LineNo					= trim(str_replace("&"," and ",$data[37]));
				// $InvoiceProduct				= trim(str_replace("&"," and ",$data[13]));
				
				
				// $readDate= $ch6;
				// $phpexcepDate = $readDate-25569; //to offset to Unix epoch
				// $ex = strtotime("+$phpexcepDate days", mktime(0,0,0,1,1,1970));
				// $ch6 = date('Y-m-d',$ex);
				
				// $readDate= $ch16;
				// $phpexcepDate = $readDate-25569; //to offset to Unix epoch
				// $ex = strtotime("+$phpexcepDate days", mktime(0,0,0,1,1,1970));
				// $ch16 = date('Y-m-d',$ex);
		
		
			
				$Inv_condition="SELECT  * FROM  `purchase_order` WHERE `PDS_number`= '$PDSInvoiceNo' and `line_no`='$LineNo'";
				 // $Inv_condition="SELECT  * FROM  `sales_order`";
				//$Inv_condition="Delete From sales_order ";
			//$Inv_condition1="SELECT * FROM `invoice_details` WHERE `Inv_InvoiceNo`= '$InvoiceNo' and `Inv_ItemDescription`= '$InvoiceProduct'";
			
				$Inv_refer=mysql_query($Inv_condition);
				//$Inv_refer1=mysql_query($Inv_condition1);
				
				 $Inv_myrow1=mysql_num_rows($Inv_refer);
				//$Inv_myrow2=mysql_num_rows($Inv_refer1);
				
				$PDSInvoiceNo=strtoupper($PDSInvoiceNo);
				//$InvoiceProduct=strtoupper($InvoiceProduct);
				
				if($Inv_myrow1>0)							
				{
					
						$user_id = $_SESSION['username'];

						 $Inv_condition3="Delete From purchase_order WHERE `PDS_number`= '$PDSInvoiceNo' and `line_no`='$LineNo'";
						//$Inv_condition3="INSERT INTO `invoice_header`(Inv_Site, Inv_Type, Inv_Status, Inv_InvoiceNo, Inv_InvoiceDate, Inv_CustomerCode, Inv_CustomerName, Inv_TransporterName, Inv_VehicleNo, Inv_City, Inv_State, Inv_NetAmount, Inv_ConsigneeCode,user_id) VALUES ('$ch2','$ch3','$ch4','$ch5','$ch6','$ch7','$ch8','$ch9','$ch10','$ch11','$ch12','$ch34','$ch35','$user_id')";
						// $Inv_condition4="INSERT INTO `invoice_details`(Inv_SlNo1,Inv_InvoiceNo, Inv_InvoiceDate, Inv_ItemCode, Inv_ItemDescription, Inv_BaseQty, Inv_ReqDate, Inv_SalesQty, Inv_BatchQty, Inv_FreeQty, Inv_SalesUOM, Inv_BaseUOM, Inv_BatchNo, Inv_WeightQty, Inv_WeightUOM, Inv_Rate, Inv_Currency, Inv_ExRate, Inv_Amount, Inv_Taxes, Inv_Discount, Inv_Charges, Inv_FreeTax, Inv_HeaderDiscount, Inv_NetAmount,Inv_PDSNo) VALUES ('$ch1','$ch5','$ch6','$ch13','$ch14','$ch15','$ch16','$ch17','$ch18','$ch19','$ch20','$ch21','$ch22','$ch23','$ch24','$ch25','$ch26','$ch27','$ch28','$ch29','$ch30','$ch31','$ch32','$ch33','$ch34','$ch36')";
					 $Inv_condition4="INSERT INTO `purchase_order`(Load_ID, supplier_code, supplier_plant, supplier_name, Plant, Plant_Name, Receiving_place, order_type, PDS_number, ekb_order_no, collect_date, collect_time, arrival_date, arrival_time, Main_route_grp_code, Main_route_order_seq, Sub_route_grp_code, Sub_route_order_seq, crs1_route, crs1_dock, crs1_arv_date, crs1_arv_time, crs1_dpt_date, crs1_dpt_time, crs2_route, crs2_dock, crs2_arv_date, crs2_arv_time, crs2_dpt_date, crs2_dpt_time, crs3_route, crs3_dock, crs3_arv_date, crs3_arv_time, crs3_dpt_date, crs3_dpt_time, supplier_type, line_no, part_no, Part_Name, Kanban_no, Line_addr, Packing_size, unit_qty, pack_qty, Zero_Order, sort_lane, shipping_date, Shipping_Time, Kb_print_date_p, Kb_print_time_p, Kb_print_date_l, Kb_print_time_l, remark, Order_Release_Date, Order_Release_Time, Main_route_date, Bill_out_flag, Shipping_dock, Packing_type, Kanban_Orientation, Dolly_Code, Picker_Route, Cycle_Serial, Packing_Code, TKM_Line_Address, BPA_Number, user_name) VALUES ('$ch1','$ch2','$ch3','$ch4','$ch5','$ch6','$ch7','$ch8','$ch9','$ch10','$ch11','$ch12','$ch13','$ch14','$ch15','$ch16','$ch17','$ch18','$ch19','$ch20','$ch21','$ch22','$ch23','$ch24','$ch25','$ch26','$ch27','$ch28','$ch29','$ch30','$ch31','$ch32','$ch33','$ch34','$ch35','$ch36','$ch37','$ch38','$ch39','$ch40','$ch41','$ch42','$ch43','$ch44','$ch45','$ch46','$ch47','$ch48','$ch49','$ch50','$ch51','$ch52','$ch53','$ch54','$ch55','$ch56','$ch57','$ch58','$ch59','$ch60','$ch61','$ch62','$ch63','$ch64','$ch65','$ch66','$ch67','$user_id')";
						//$execute1=mysql_query($Inv_condition3);
					
						$execute1=mysql_query($Inv_condition3);
						$execute2=mysql_query($Inv_condition4);
						
						if($execute1 && $execute2 )
						$cnt1++;

				}else if ($Inv_myrow1==0)
				{
				$Inv_condition4="INSERT INTO `purchase_order`(Load_ID, supplier_code, supplier_plant, supplier_name, Plant, Plant_Name, Receiving_place, order_type, PDS_number, ekb_order_no, collect_date, collect_time, arrival_date, arrival_time, Main_route_grp_code, Main_route_order_seq, Sub_route_grp_code, Sub_route_order_seq, crs1_route, crs1_dock, crs1_arv_date, crs1_arv_time, crs1_dpt_date, crs1_dpt_time, crs2_route, crs2_dock, crs2_arv_date, crs2_arv_time, crs2_dpt_date, crs2_dpt_time, crs3_route, crs3_dock, crs3_arv_date, crs3_arv_time, crs3_dpt_date, crs3_dpt_time, supplier_type, line_no, part_no, Part_Name, Kanban_no, Line_addr, Packing_size, unit_qty, pack_qty, Zero_Order, sort_lane, shipping_date, Shipping_Time, Kb_print_date_p, Kb_print_time_p, Kb_print_date_l, Kb_print_time_l, remark, Order_Release_Date, Order_Release_Time, Main_route_date, Bill_out_flag, Shipping_dock, Packing_type, Kanban_Orientation, Dolly_Code, Picker_Route, Cycle_Serial, Packing_Code, TKM_Line_Address, BPA_Number, user_name) VALUES ('$ch1','$ch2','$ch3','$ch4','$ch5','$ch6','$ch7','$ch8','$ch9','$ch10','$ch11','$ch12','$ch13','$ch14','$ch15','$ch16','$ch17','$ch18','$ch19','$ch20','$ch21','$ch22','$ch23','$ch24','$ch25','$ch26','$ch27','$ch28','$ch29','$ch30','$ch31','$ch32','$ch33','$ch34','$ch35','$ch36','$ch37','$ch38','$ch39','$ch40','$ch41','$ch42','$ch43','$ch44','$ch45','$ch46','$ch47','$ch48','$ch49','$ch50','$ch51','$ch52','$ch53','$ch54','$ch55','$ch56','$ch57','$ch58','$ch59','$ch60','$ch61','$ch62','$ch63','$ch64','$ch65','$ch66','$ch67','$user_id')";
						//$execute1=mysql_query($Inv_condition3);
				$execute3=mysql_query($Inv_condition5);
				if($execute3)
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
			echo"<script>alert('File $path Imported Successfully','PO_upload_data.php')</script>";
			$news->unlinkfun($path);
		}
		else
		{
			echo"<script>alert('Data not imported from $path!. Please check the file for format / duplication.','PO_upload_data.php')</script>";
			$news->unlinkfun($path);
		}
	}
	else
	{
		echo"<script>alert('File name mismatch','PO_upload_data.php')</script>";
		$news->unlinkfun($path);
		$fname="";
	}	 		  
break;

default : 
echo"<script>alert('Select proper master name','PO_upload_data.php')</script>";
break;
}
}

}
else
{
echo"<script>alert('File $path is not in the required Format','PO_upload_data.php')</script>";
if($path!="")
{
$news->unlinkfun($path);
}
}
	if (!empty($_SESSION['errorlogs'])) 
	{
	//echo $_SESSION['errorlogs'];
	//header('Location:errorlogfile.php');
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=errorlogfile.php">';
	exit;
	}
}

function DateFormatConversion($readDate){
		$phpexcepDate = $readDate-25569; //to offset to Unix epoch
		return strtotime("+$phpexcepDate days", mktime(0,0,0,1,1,1970));
	}
?>





<title><?php echo $_SESSION['title']; ?></title>
</head>

<body><center>

<?php include("../../menu.php") ?>

<!--Third Block - Container-->
<div style="width:100%; height:auto; float:none;">
     <div style="width:980px; height:auto; float:none; margin-top:8px;" class="container">
                  
          <div style="width:950px; height:auto; float:left; margin-bottom:10px; margin-left:15px;" id="center">  
            <!-- form id start-->
             <form method="POST" action="<?php $_PHP_SELF ?>" enctype="multipart/form-data">
            <div style="width:930px; height:auto; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;">
                    
						<div style="width:930px; height:40px; float:left;  margin-left:0px;" class="head">
						<p>Import Transaction</p>
						</div>
                       <div style="width:900px; height:auto; padding-bottom:5px; float:left; " class="cont">
                        <div style="width:155px; height:30px;  float:left; margin-top:5px; margin-left:3px;">
                                 <label>Select Transaction Name</label><label style="color:#F00;">*</label>
                               </div>
                               <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                    <select name="bulkmasters">
                                  
                                   	 <option value=""><--Select--></option> 
									 <option value="Sales">Sales</option>
                                   <!--  <option value="Franchisee">Distributor</option>   
                                    <option value="Product Group">Product Group</option>
                                    <option value="Product Details">Product Details</option>
									<option value="DSRMapping">DSR Mapping</option> 
									<option value="Employee Master">Employee Master</option>
									<option value="DSRRetailerMapping">DSR Retailer Mapping</option>                                    
                                  <!--  <option value="PriceList">PriceList</option>  
                                    <option value="Retailer category">Retailer Category</option>                                   
                                    <option value="Retailer">Retailer</option>   -->                                

                                   <!-- <option value="Tertiary Sales">Tertiary Sales Old</option>  -->
                                                                         
                                                        
                                   </select>
                               </div>

				           </div>
                           </div>
                        
                        
              <!-- main row 1 start-->     
                    <div style="width:925px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
                     <!-- col1 -->   

                           <div style="width:900px; height:auto; padding-bottom:5px; float:left; " class="cont">
                             <div style="width:70px; height:32px; float:left; margin-top:16px; margin-left:15px;">
						 
                           	
                              
                           <div style="width:560px; height:50px; float:left;  margin-left:185px; margin-top:0px;" class="cont" id="center2">
                              <!--Row1 -->  
                               <div style="width:70px; height:40px; float:left; margin-left:3px; margin-top:10px;" >
                                 <h1 style="size:60px;"> <label>Select file</label></h1>
                               </div>
                               <div style="width:195px; height:30px;  float:left; margin-left:3px; margin-top:16px;">
                                 <input name="ufile" type="file" id="ufile" accept="application/vnd.ms-excel"/>
                               </div>
                             <!--Row1 end-->  
                             
                               <!--Row2 -->  
                               <div style="width:40px; height:30px; float:right; margin-right:150px; margin-top:16px;">
                               <input name="<?php if(($row['editrights'])=='Yes') echo 'Submit'; else echo 'permiss'; ?>" type="submit" class="button" value="Import">
                               </div>
                            
                          </div> 
                           </div>                             
                     <!-- col1 end --> 
                     
                     <!-- col2 -->   
                                                      
                     <!-- col2 end--> 
                     
                     <!-- col3 -->   
                             
                     <!-- col3end --> 
                                                              
                    </div>
                  
                
				</div>
                <!-- main row 1 end-->
                
                
                <!--Main row 2 start-->

						   
                          
                           
                          
           
                 
                
                <!--Main row 2 end-->
                
              <!--  grid start here-->

       <!--  grid end here-->
          </form>         
         <!-- form id start end-->      
          </div> 
          
     </div>       
</div>
<!--Third Block - Menu -Container -->



<!--Footer Block -->
<div id="footer-wrap1">
        <?php include("../../footer.php") ?>
  </div>
<!--Footer Block - End-->
</center></body>
</html>
<?
}
?>
