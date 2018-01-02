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


case "PartMaster":

	
	if($fname=="PartMaster.xls")
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
			// $ch13=trim($data[12]);
			// $ch14=trim($data[13]);
			
			
			if($ch1!="" && $ch2!="" && $ch3!="" && $ch4!="")
			{	
				
				$PartNo					= trim(str_replace("&"," and ",$data[0]));
				$Inv_condition="SELECT  * FROM  `partmaster` WHERE `PartNo`= '$PartNo'";
				$Inv_refer=mysql_query($Inv_condition);
				$Inv_myrow1=mysql_num_rows($Inv_refer);

				$PartNo=strtoupper($PartNo);	
				date_default_timezone_set ("Asia/Calcutta");
				$insertdate= date("y/m/d : H:i:s", time());
				$updatedate= date("y/m/d : H:i:s", time());				
				if($Inv_myrow1>0)							
				{
					
						$user_id = $_SESSION['username'];
						$Inv_condition3="Delete From partmaster WHERE `PartNo`= '$PartNo'";
						$Inv_condition4="INSERT INTO `partmaster`(PartNo, PartName, Customer, HIMLorToyotoPartNumber, POMonth, PONo, ShopCode, TariffNo, Location, GateNo, ContainerType, StuffingQty, userid,insertdate,updatedate) VALUES ('$ch1','$ch2','$ch3','$ch4','$ch5','$ch6','$ch7','$ch8','$ch9','$ch10','$ch11','$ch12','$user_id','$insertdate','$updatedate')";
						$execute1=mysql_query($Inv_condition3);
						$execute2=mysql_query($Inv_condition4);
						if($execute1 && $execute2 )
						$cnt1++;

				}else if ($Inv_myrow1 == 0)
				{
				$Inv_condition5="INSERT INTO `partmaster`(PartNo, PartName, Customer, HIMLorToyotoPartNumber, POMonth, PONo, ShopCode, TariffNo, Location, GateNo, ContainerType, StuffingQty, userid,insertdate,updatedate) VALUES ('$ch1','$ch2','$ch3','$ch4','$ch5','$ch6','$ch7','$ch8','$ch9','$ch10','$ch11','$ch12','$user_id','$insertdate','$updatedate')";
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
		//exit;		
		if($cnt1>0 || $cnt2>0)
		{
		   $tvalues['userid']=$validuser;
		    $tblname='fileloadedstatus';
		    $tvalues['master_name']='Product';
		    $tvalues['file_name']=$fname;
		    $news->addNews($tvalues,$tblname);
			echo"<script>alert('File $path Imported Successfully','Part_Master_Upload.php')</script>";
			$news->unlinkfun($path);
		}
		else
		{
			echo"<script>alert('Data not imported from $path!. Please check the file for format / duplication.','Part_Master_Upload.php')</script>";
			$news->unlinkfun($path);
		}
	}
	else
	{
		echo"<script>alert('File name mismatch','Part_Master_Upload.php')</script>";
		$news->unlinkfun($path);
		$fname="";
	}	 		  
break;

default : 
echo"<script>alert('Select proper master name','Part_Master_Upload.php')</script>";
break;
}
}

}
else
{
echo"<script>alert('File $path is not in the required Format','Part_Master_Upload.php')</script>";
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
						<p>Import Part Master</p>
						</div>
                       <div style="width:900px; height:auto; padding-bottom:5px; float:left; " class="cont">
                        <div style="width:155px; height:30px;  float:left; margin-top:5px; margin-left:3px;">
                                 <label>Select Part Master</label><label style="color:#F00;">*</label>
                               </div>
                               <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                    <select name="bulkmasters">
                                  
                                   	 <option value=""><--Select--></option> 
									 <option value="PartMaster">PartMaster</option>
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
