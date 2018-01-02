<?php 
	include '../../functions.php';
	sec_session_start();
	require_once '../../masterclass.php';
	include("../../header.php");
   global $fname;
    unset($_SESSION['errorlogs']);
if(login_check($mysqli) == false) {
 
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
		
switch($_POST['bulkmasters'])
{


case "PartMaster":

	
	if($fname=="PARTMASTER.xls")
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
			
			

			$PartNumber			= trim(strtoupper(str_replace("&"," and ",$data[0])));
			$Customer				= trim(strtoupper(str_replace("&"," and ",$data[2])));
			
			
			if(($Customer="TOYOTA" && ($ch1!="" && $ch2!="" && $ch3!="" && $ch4!="")) || ($Customer="HMIL" && ($ch1!="" && $ch2!="" && $ch3!="" && $ch4!="" && $ch5!="" && $ch6!="" && $ch7!="" && $ch8!="" && $ch9!="" && $ch10!="" && $ch11!="" && $ch12!="")))
			{
			$Customername				= trim(strtoupper(str_replace("&"," and ",$data[2])));
			$PartNumberValue			= trim(strtoupper(str_replace("&"," and ",$data[0])));
			If($Customername=="TOYOTA" || $Customername=="HMIL")
			{
			    $Inv_condition="SELECT  * FROM  `partmaster` WHERE `PartNo`= '$PartNumberValue' and Customer='$Customername'";
				$Inv_refer=mysql_query($Inv_condition);
				$Inv_myrow1=mysql_num_rows($Inv_refer);
				
				if($Inv_myrow1==0)							
				{
						$user_id = $_SESSION['username'];
						date_default_timezone_set ("Asia/Calcutta");
						$dateofinsert= date("y/m/d : H:i:s", time());
						
						$Inv_condition3="INSERT INTO `partmaster`(PartNo, PartName, Customer, HIMLorToyotoPartNumber, POMonth, PONo, ShopCode, TariffNo, Location, GateNo, ContainerType, StuffingQty, userid, insertdate) VALUES ('$PartNumberValue','$ch2','$Customername','$ch4','$ch5','$ch6','$ch7','$ch8','$ch9','$ch10','$ch11','$ch12','$user_id','$dateofinsert')";
						$execute1=mysql_query($Inv_condition3);
						
						if($execute1)
						$cnt1++;
				}
				else if($Inv_myrow1>0)
				{
						date_default_timezone_set ("Asia/Calcutta");
						$dateofupdate= date("y/m/d : H:i:s", time());
						
						$Inv_condition3="Update `partmaster` SET PartNo='$PartNumberValue', PartName='$ch2', Customer='$Customername', HIMLorToyotoPartNumber='$ch4', POMonth='$ch5', PONo='$ch5', ShopCode='$ch6', TariffNo='$ch7', Location='$ch8', GateNo='$ch9', ContainerType='$ch10', StuffingQty='$ch11', userid='$user_id', updatedate='$dateofupdate' where `PartNo`= '$PartNumberValue' and Customer='$Customername'";
						$execute2=mysql_query($Inv_condition3);
						if($execute2)
						$cnt1++;
				}
				}
				else
				{
					$_SESSION['errorlogs']=$_SESSION['errorlogs']."\n `".$ch1."` Customer Name should be HMIL /TOYOTA \n";
				}
			}
			else
			{
				 $_SESSION['errorlogs']=$_SESSION['errorlogs']."\n `".$ch1."` Missing mandatory or code & name are same.";
			}
		}
		//echo $cnt1;
		
	if($cnt1>0)
		{
		    $tvalues['userid']=$validuser;
		    $tblname='fileloadedstatus';
		    $tvalues['master_name']='Product';
		    $tvalues['file_name']=$fname;
		    $news->addNews($tvalues,$tblname);
			echo"<script>alert('File $path Imported Successfully','Bulkupload.php')</script>";
			$news->unlinkfun($path);
		}
		else
		{
			echo"<script>alert('Data not imported from $path!. Please check the file for format / duplication.','Bulkupload.php')</script>";
			$news->unlinkfun($path);
		}
	}
	else
	{
		echo"<script>alert('File name mismatch','Bulkupload.php')</script>";
		$news->unlinkfun($path);
		$fname="";
	}	 		  
break;		
		

case "Sales":

	
	if($fname=="SalesInvoiceDetails.xls")
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
			
			if($ch1!="" && $ch2!="" && $ch3!="" && $ch4!="" && $ch5!="" && $ch6!="" && $ch7!="" && $ch8!="" && $ch12!="" && $ch13!="" && $ch14!="" && $ch16!="" && $ch18!="" && $ch22!="" && $ch23!="" && $ch24!=""  && $ch25!="" && $ch28!="" && $ch34!="" && $ch35!="")
			{	
				 $InvoiceNo					= trim(str_replace("&"," and ",$data[4]));
				 $InvoiceProduct				= trim(str_replace("&"," and ",$data[13]));
				
				
				$readDate= $ch6;
				$phpexcepDate = $readDate-25569; //to offset to Unix epoch
				$ex = strtotime("+$phpexcepDate days", mktime(0,0,0,1,1,1970));
				$ch6 = date('Y-m-d',$ex);
				
				$readDate= $ch16;
				$phpexcepDate = $readDate-25569; //to offset to Unix epoch
				$ex = strtotime("+$phpexcepDate days", mktime(0,0,0,1,1,1970));
				$ch16 = date('Y-m-d',$ex);
		
		
			
				$Inv_condition="SELECT  * FROM  `invoice_header` WHERE `Inv_InvoiceNo`= '$InvoiceNo'";
			$Inv_condition1="SELECT * FROM `invoice_details` WHERE `Inv_InvoiceNo`= '$InvoiceNo' and `Inv_ItemDescription`= '$InvoiceProduct'";
			
				$Inv_refer=mysql_query($Inv_condition);
				$Inv_refer1=mysql_query($Inv_condition1);
				
				$Inv_myrow1=mysql_num_rows($Inv_refer);
				$Inv_myrow2=mysql_num_rows($Inv_refer1);
				
				$InvoiceNo=strtoupper($InvoiceNo);
				$InvoiceProduct=strtoupper($InvoiceProduct);
				
				if($Inv_myrow1==0 && $Inv_myrow2==0)							
				{
					
						$user_id = $_SESSION['username'];

					
						$Inv_condition3="INSERT INTO `invoice_header`(Inv_Site, Inv_Type, Inv_Status, Inv_InvoiceNo, Inv_InvoiceDate, Inv_CustomerCode, Inv_CustomerName, Inv_TransporterName, Inv_VehicleNo, Inv_City, Inv_State, Inv_NetAmount, Inv_ConsigneeCode,user_id) VALUES ('$ch2','$ch3','$ch4','$ch5','$ch6','$ch7','$ch8','$ch9','$ch10','$ch11','$ch12','$ch34','$ch35','$user_id')";
						 $Inv_condition4="INSERT INTO `invoice_details`(Inv_SlNo1,Inv_InvoiceNo, Inv_InvoiceDate, Inv_ItemCode, Inv_ItemDescription, Inv_BaseQty, Inv_ReqDate, Inv_SalesQty, Inv_BatchQty, Inv_FreeQty, Inv_SalesUOM, Inv_BaseUOM, Inv_BatchNo, Inv_WeightQty, Inv_WeightUOM, Inv_Rate, Inv_Currency, Inv_ExRate, Inv_Amount, Inv_Taxes, Inv_Discount, Inv_Charges, Inv_FreeTax, Inv_HeaderDiscount, Inv_NetAmount,Inv_PDSNo) VALUES ('$ch1','$ch5','$ch6','$ch13','$ch14','$ch15','$ch16','$ch17','$ch18','$ch19','$ch20','$ch21','$ch22','$ch23','$ch24','$ch25','$ch26','$ch27','$ch28','$ch29','$ch30','$ch31','$ch32','$ch33','$ch34','$ch36')";
					
						$execute1=mysql_query($Inv_condition3);
						$execute2=mysql_query($Inv_condition4);
						
						if($execute1 && $execute2)
						$cnt1++;

				}
				
				else if( $Inv_myrow2==0)							
				{
					
						$Inv_condition5="INSERT INTO `invoice_details`(Inv_SlNo1,Inv_InvoiceNo, Inv_InvoiceDate, Inv_ItemCode, Inv_ItemDescription, Inv_BaseQty, Inv_ReqDate, Inv_SalesQty, Inv_BatchQty, Inv_FreeQty, Inv_SalesUOM, Inv_BaseUOM, Inv_BatchNo, Inv_WeightQty, Inv_WeightUOM, Inv_Rate, Inv_Currency, Inv_ExRate, Inv_Amount, Inv_Taxes, Inv_Discount, Inv_Charges, Inv_FreeTax, Inv_HeaderDiscount, Inv_NetAmount,Inv_PDSNo) VALUES ('$ch1','$ch5','$ch6','$ch13','$ch14','$ch15','$ch16','$ch17','$ch18','$ch19','$ch20','$ch21','$ch22','$ch23','$ch24','$ch25','$ch26','$ch27','$ch28','$ch29','$ch30','$ch31','$ch32','$ch33','$ch34','$ch36')";
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
			echo"<script>alert('File $path Imported Successfully','Bulkupload.php')</script>";
			$news->unlinkfun($path);
		}
		else
		{
			echo"<script>alert('Data not imported from $path!. Please check the file for format / duplication.','Bulkupload.php')</script>";
			$news->unlinkfun($path);
		}
	}
	else
	{
		echo"<script>alert('File name mismatch','Bulkupload.php')</script>";
		$news->unlinkfun($path);
		$fname="";
	}	 		  
break;

default : 
echo"<script>alert('Select proper master name','Bulkupload.php')</script>";
break;
}
}

}
else
{
echo"<script>alert('File $path is not in the required Format','Bulkupload.php')</script>";
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
						<p>Import Master</p>
						</div>
                       <div style="width:900px; height:auto; padding-bottom:5px; float:left; " class="cont">
                        <div style="width:155px; height:30px;  float:left; margin-top:5px; margin-left:3px;">
                                 <label>Select Master Name</label><label style="color:#F00;">*</label>
                               </div>
                               <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                    <select name="bulkmasters">
                                  
                                   	 <option value=""><--Select--></option> 
									 
									 <option value="PartMaster">PartMaster</option>
                                                       
                                                        
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
