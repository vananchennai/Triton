<?php
	session_start();
	if (!isset($_SESSION['username']))
	{
	header('Location:../../index.php');
	}
    require_once '../../masterclass.php'; // Include The News Class
	$news = new News(); // Create a new News Object
	$newsRecordSet = $news->getNews();

	
if(isset($_POST['Submit']))
{
	$mastertype=$_POST['bulkmasters'];
	global $HTTP_POST_FILES,$y;
	//$path= $HTTP_POST_FILES['ufile']['name'];
	$path= $_FILES['ufile']['name'];
	$filetype = substr($path, -3);
		if($filetype =='xls')
		{
			if(copy($_FILES['ufile']['tmp_name'], $path))
			{
			require_once '.\phpExcelReader\Excel\reader.php';
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
			if(($mastertype == 'Product Segment'&&$y=='4')||($mastertype == 'Product Details'&&$y=='11')||($mastertype == 'Product Type'&&$y=='5')||($mastertype == 'Product Mapping'&&$y=='3')||($mastertype == 'PriceList'&&$y=='13')||($mastertype == 'Retailer category'&&$y=='2')||($mastertype == 'Vehicle Make'&&$y=='3')||($mastertype == 'Retailer'&&$y=='14')||($mastertype == 'Vehicle Model'&&$y=='5')||($mastertype == 'ARBL Warehouse'&&$y=='8')||($mastertype == 'Vehicle Segment'&&$y=='3')||($mastertype == 'Franchisee'&&$y=='13')||($mastertype == 'Failure Mode'&&$y=='3'))
			{
				$fp = fopen("data.csv",'w');
				fwrite($fp,ob_get_contents());
				fclose($fp);
				//echo"----";	0
				ob_end_clean();
				//$file = $_FILES['data.csv']['tmp_name']; 
				$handle = fopen('data.csv',"r"); 
				//loop through the csv file and insert into another csv 
				global $data;
			
			switch($_POST['bulkmasters'])
			{
				case "Sales":
				
					ob_start();
					while ($data = fgetcsv($handle,2000,"*","'"))
					{ 
					$Inv_SNo.”*”. $Inv_Site.”*”. $Inv_Type.”*”. $Inv_Status.”*”. $Inv_InvoiceNo.”*”. $Inv_InvoiceDate.”*”. $Inv_CustomerCode.”*”. $Inv_CustomerName.”*”. $Inv_TransporterName.”*”. $Inv_SNo.”*”. $Inv_SNo.”*”. $Inv_SNo.”*”. $Inv_SNo.”*”. $Inv_SNo.”*”. $Inv_SNo.”*”. $Inv_SNo.”*”. $Inv_SNo.”*”. $Inv_SNo.”*”. $Inv_SNo.”*”. $Inv_SNo.”*”. $Inv_SNo.”*”.
$Inv_SNo.”*”. $Inv_SNo.”*”. $Inv_SNo.”*”. $Inv_SNo.”*”. $Inv_SNo.”*”. $Inv_SNo.”*”. $Inv_SNo.”*”.
$Inv_SNo.”*”. $Inv_SNo.”*”. $Inv_SNo.”*”. $Inv_SNo.”*”. $Inv_SNo.”*”. $Inv_SNo.”*”. $Inv_SNo.”*”.

					
					$Inv_SNo						=$data[0];
					$Inv_Site						=$data[1];                
					$Inv_Type	                    =$data[2];
					$Inv_Status	                    =$data[3];
					$Inv_InvoiceNo                  =$data[4];
					$Inv_InvoiceDate                =$data[5];
					$Inv_CustomerCode               =$data[6];
					$Inv_CustomerName               =$data[7];
					$Inv_TransporterName            =$data[8];
					$Inv_VehicleNo                  =$data[9];
					$Inv_City                       =$data[10];
					$Inv_State                      =$data[11];
					$Inv_ItemCode                   =$data[12];
					$Inv_ItemDescription            =$data[13];
					$Inv_BaseQty                    =$data[14];
					$Inv_ReqDate                    =$data[15];
					$Inv_SalesQty                   =$data[16];
					$Inv_BatchQty                   =$data[17];
					$Inv_FreeQty                    =$data[18];
					$Inv_SalesUOM                   =$data[19];
					$Inv_BaseUOM                    =$data[20];
					$Inv_BatchNo                    =$data[21];
					$Inv_WeightQty                  =$data[22];
					$Inv_WeightUOM                  =$data[23];
					$Inv_Rate                       =$data[24];
					$Inv_Currency	                =$data[25];
					$Inv_ExRate                     =$data[26];
					$Inv_Amount	                    =$data[27];
					$Inv_Taxes	                    =$data[28];
					$Inv_Discount	                =$data[29];
					$Inv_Charges	                =$data[30];
					$Inv_FreeTax					=$data[31];
					$Inv_HeaderDiscount				=$data[32];
					$Inv_NetAmount					=$data[33];
					$Inv_ConsigneeCode				=$data[34];
					 

					
					
						 if($Inv_InvoiceNo!="")
						 {
							echo  $ProductSegmentCode."*".$ProductSegment."*".$ProductGroup."\n";
						 }
				 
					}  		 
					$tname="productsegmentmaster";
					$news->unlinkfun($tname,$handle,$path);
					break; 
					
				
					
					default : 
					echo"<script>alert('Select proper master name')</script>";
					break;
			  }
		}
			else
			{
					ob_end_clean();
				echo"<script>alert('File : $path    has invalid column structure for the master : $mastertype')</script>";
				
			}
		}
	}
	else
	{
		echo"<script>alert('File $path is not in the reqiured Format')</script>";
	}
}


?>




<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../../css/style.css" />
<link href="../../css/menu.css" rel="stylesheet" type="text/css">
<link href="../../css/grid.css" rel="stylesheet" type="text/css">
<link href="../../css/reveal.css" rel="stylesheet" type="text/css">
<link href="../../css/A_red.css" type="text/css" rel="stylesheet" />
<link href="../../css/pagination.css" type="text/css" rel="stylesheet" />
<link href="../../css/menu.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../../js/jquery.reveal.js"></script>
<script src="../../js/sorttable.js"></script>
<script type="text/javascript">
function nospaces(t){
if(t.value.match(/\s/g)){
alert('Sorry, you are not allowed to enter any spaces');
t.value=t.value.replace(/\s/g,'');
}
}
</script>
<title>Amaron&nbsp;|&nbsp;Home</title>
</head>

<body><center>

<!--First Block - Logo-->
<div style="width:100%; height:auto; float:none;">
     <div style="width:980px; height:130px; float:none;">
           <div style="width:250px; height:130px; float:left; background-image:url(../../img/logo_amaron.png);">
          
           </div>
     </div>       
</div>
<!--First Block - End-->


<!--Second Block - Menu-->
<div style="width:100%; height:50px; float:none; background:url(../../img/menubg.jpg) repeat-x;">
     <div style="width:980px; height:50px; float:none;"> 
       <?php include("../../menu.php")?>
     </div>       
</div>
<!--Second Block - Menu -End -->

<!--Third Block - Container-->
<div style="width:100%; height:auto; float:none;">
     <div style="width:980px; height:auto; float:none; margin-top:8px;" class="container">
                  
          <div style="width:950px; height:auto; float:left; margin-bottom:10px; margin-left:15px;" id="center">  
            <!-- form id start-->
             <form method="POST" action="<?php $_PHP_SELF ?>" enctype="multipart/form-data">
            <div style="width:930px; height:auto; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;">
                    
						<div style="width:930px; height:40px; float:left;  margin-left:0px;" class="head">
						<p>Upload Masters</p>
						</div>
                       <div style="width:900px; height:auto; padding-bottom:5px; float:left; " class="cont">
                        <div style="width:123px; height:30px;  float:left; margin-top:5px; margin-left:3px;">
                                 <label>Select Master Name</label><label style="color:#F00;">*</label>
                               </div>
                               <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                    <select name="bulkmasters">
                                   <option value=""><--Select--></option>  
                                    <option value="Product Segment">Product Segment</option>                                
                                    <option value="Product Type">Product Type</option>                                   
                                    <option value="Product Details">Product Details</option>                                   
                                    <option value="Product Warranty">Product Warranty</option>                                     
                                    <option value="Product Mapping">Product Mapping</option> 
                                    <option value="PriceList">PriceList</option>                                
                                    <option value="Retailer category">Retailer category</option>                                   
                                    <option value="Retailer">Retailer</option>                                   
                                    <option value="Vehicle Make">Vehicle Make</option>                                     
                                    <option value="Vehicle Segment">Vehicle Segment</option> 
                                    <option value="Vehicle Model">Vehicle Model</option>                                
                                    <option value="ARBL Warehouse">ARBL Warehouse</option>                                   
                                    <option value="Franchisee">Franchisee</option>                                   
                                    <option value="Failure Mode">Failure Mode</option>                                     
                                                        
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
                                 <input type="submit" name="Submit" value="Import to Database" class="button"/>
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
        <?php include("../../footer.php")?>
  </div>
<!--Footer Block - End-->
</center></body>
</html>