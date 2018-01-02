<?php 
	include '../../functions.php';
	sec_session_start();
	require_once '../../masterclass.php';
	include("../../header.php");
	include '../../Rupees_In_Words.php';
   //global $fname;
    unset($_SESSION['errorlogs']);
	
global $host, $uid, $pass, $databname,$fname;
$str        = "";
$data       = array();
$uploadfile = "../../rights.txt";
$file = fopen($uploadfile, "r") or exit("Unable to open file!");
while (!feof($file)) {
    $str = $str . fgetc($file);
}
list($host, $uid, $pass, $databname) = explode('~', trim($str));
fclose($file);
$_SESSION["dbhostname"] = $host;
$_SESSION["dbusername"] = $uid;
$_SESSION["dbpassword"] = $pass;
$_SESSION["databname"]  = $databname;
$news = new News();


	if (isset($_POST['TOYOTAInvoice']))
	{
	 $BarCode=$_POST['bulkmasters'];

 $_SESSION['bulkmasters'] = $_POST['bulkmasters'];
  unset($_SESSION['codesval']);
 unset($_SESSION['namesval']);
 
	$_SESSION['currentdate']=date('d/m/Y h:i:s');
	$_SESSION['currentyear']=date('Y');

		
 
 
 


		if (!empty($BarCode)){
		
		
		$ESugamNo		=strtoupper($_POST['ESugamNo']);
		$ASNNo			=strtoupper($_POST['ASNNo']);
		// if( $ESugamNo!="" && $ASNNo!=""){
		$_SESSION['OrderNo'] = $_POST['OrderNo'];
		if( $ESugamNo!="" && $ASNNo!=""){
		$UpdateQuery= "UPDATE tkm_invoice_details SET TKM_ESugamNo='".$ESugamNo."',TKM_ASNNo='".$ASNNo."' WHERE TKM_VchNo ='".$BarCode."'";
		$Invoiceresult1 = mysql_query($UpdateQuery);
		
		unset($ESugamNo);
		unset($ASNNo);
		}
		
		  $DutyQuery= "SELECT * FROM tkm_invoice_detail_view WHERE vTKM_VchNo='".$_POST['bulkmasters']."'";
		
				$DutyResult = mysql_query($DutyQuery);
				$DutyDetails = mysql_fetch_array($DutyResult);
				$obj_no_to_words = new number_to_words();
				$bed_words_in_rupees = $obj_no_to_words->convert_number_to_words($DutyDetails['vTKM_IGSTAmt']);
				$bed_words_in_rupees = ucwords($bed_words_in_rupees);
				$vat_words_in_rupees = $obj_no_to_words->convert_number_to_words( $DutyDetails['vTKM_CSTAmt']);
				$vat_words_in_rupees = ucwords($vat_words_in_rupees);
				$gt_words_in_rupees = $obj_no_to_words->convert_number_to_words( $DutyDetails['vTKM_GSTGrandToatalAmt']);//+$DutyDetails['VATCST_Amt']);
				$gt_words_in_rupees = ucwords($gt_words_in_rupees);
				$_SESSION['bed'] = $bed_words_in_rupees;
				$_SESSION['vat'] = $vat_words_in_rupees;
				$_SESSION['total'] = $gt_words_in_rupees;
				
			
		
	 	  $condition1= "SELECT * FROM tkm_invoice_details tkm LEFT JOIN tkm_invoice_detail_view tkmv ON tkm.TKM_VchNo=tkmv.vTKM_VchNo left join partmaster ptm on ptm.PartNo=tkm.TKM_ItemCode WHERE tkm.TKM_VchNo='".$BarCode."'";
		$Invoiceresult = mysql_query($condition1);
		
		$flag = 0;
		 while($result = mysql_fetch_array($Invoiceresult)){
		
			
				$BarcodeValue = $result['ShopCode'];
				$BarcodeValue .= $result['PONo'];
				$BarcodeValue .= $result['PartNo']."\r\n";
				$BarcodeValue .= $result['TKM_VchNo']."\t";			
				$HBarDate=	$result['TKM_Vch_Date'];
				$HBarDateFormat = new DateTime($HBarDate);
				$BarcodeValue .= $HBarDateFormat->format('dmY');
				$BarcodeValue .= $result["TKM_ItemQty"]."\t";
				$BarcodeValue .= $result["vTKM_GSTGrandToatalAmt"]."\t";
				$BarcodeValue .= "8481.80.90";
				$BarcodeValue .= "0.00"."\t";
				$BarcodeValue .= $result['vTKM_SGSTAmt']."\t";//"0.00"."\t";
				$BarcodeValue .= "0.00"."\t";
				$BarcodeValue .= "0.00"."\t";
				$BarcodeValue .= $result["TKM_ItemRate"]."\t";
				$BarcodeValue .= $result["TKM_ItemAmt"]."\t";
				$BarcodeValue .= $result['vTKM_CGSTAmt']."\t";
				$BarcodeValue .= "0.00"."\t";
				$BarcodeValue .= "0.00"."\t";
				$BarcodeValue .= $result["TKM_ItemAmt"]."\t";
				$BarcodeValue .= "0.00"."\t";
				$BarcodeValue .= "0.00"."\t";
				$BarcodeValue .= "0.00"."\t";
				$Gstvalue= trim("33AAACT6671P1ZU");
				$BarcodeValue .= $Gstvalue."\t";
							// = $result["TKM_ItemQty"]/1000;	
				$_SESSION['boxno']=(ceil($result["TKM_ItemQty"]/1000));//number_format((float)(round($result["TKM_ItemQty"]/1000)), 0, '.', '');
				
		
			
		 }
		
		
	
		 $BarcodeValue0 = str_replace("\t","%09",$BarcodeValue);
		 $BarcodeValue1 = str_replace("\r\n","%0D",$BarcodeValue0);
		 $BarcodeValue2 = str_replace(" ","%20",$BarcodeValue1);

		 
		 $BarCodeTest=  str_replace("/","",$BarCode);
		 $_SESSION['BarCodeTest'] = $BarCodeTest;
		 
		
	
	//	str_replace
		
		
		 
					$url = "http://localhost/Triton/tcpdf_min/QR_barcode.php?D=".$BarcodeValue2."";//$BarcodeValue;
					$img = '../../Barcode/'.$BarCodeTest.'.png';
					
					file_put_contents($img, file_get_contents($url));
					date_default_timezone_get('Asia/Kolkata');

					header('Location:../../PhpJasperLibraryTriton/TMK_invoice_file.php'); 
	// }
	// else{
	// echo"<script>alert('Insert all the fields','TMK_Print_page.php')</script>";
	
	// }
	}
	else{
	echo"<script>alert('Select proper master name','TMK_Print_page.php')</script>";
	}
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
						<p>QR Print Invoice</p>
						</div>
						
                       <div style="width:930px; height:auto; padding-bottom:5px; float:right; " class="cont">
					    <div style="width:930px; height:auto; padding-bottom:5px; float:right; " class="cont">
                        <div style="width:155px; height:30px;  float:left; margin-top:5px; margin-left:3px;">
                                 <label  >Select Invoice Number</label><label style="color:#F00;">*</label>
                               </div>
                               <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                    <select name="bulkmasters">
                                  
                                   	 <option value=""><--Select--></option> 
											<?
                                            $triton_name = ($_POST['TKM_VchNo']) ? $_POST['TKM_VchNo'] : '';

                                            $list = mysql_query("SELECT TKM_VchNo FROM tkm_invoice_details Where TKM_PartyName= 'Hyundai Motor India Ltd' ORDER BY TKM_VchNo ");

                                            while ($row_list = mysql_fetch_assoc($list)) {
                                                $selected = '';

                                                if ($row_list['TKM_VchNo'] == $triton_name) {
                                                    $selected = ' selected ';
                                                }
                                                ?>
                                           <option value="<? echo $row_list['TKM_VchNo']; ?>"<? echo $selected; ?>> <? echo $row_list['TKM_VchNo']; ?> </option>
											<?
                                            }
                                            ?>                

                                 
                                                                         
                                                        
                                   </select>
                               </div>

				           </div>
						   <div style="width:155px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>E-Sugam No.</label><label style="color:#F00;"></label>
                               </div>
                              <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                   <input type="text"  name="ESugamNo" style="text-transform:uppercase;" value="<?php echo $ESugamNo;?>"  maxlength="12" onKeyPress="return validatee(event)"/>                             
                               </div>
							   
							   <div style="width:155px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>ASN No.</label><label style="color:#F00;"></label>
                               </div>
                              <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                   <input type="text"  name="ASNNo" style="text-transform:uppercase;" value="<?php echo $ASNNo;?>"  maxlength="15" onKeyPress="return validatee(event)"/>  
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
                               
                             <!--Row1 end-->  
                             
                               <!--Row2 -->  
                               <div style="width:40px; height:30px; float:right; margin-right:150px; margin-top:16px;">
                               <input name="<?php if(($row['editrights'])=='Yes') echo 'Cancel'; else echo 'permiss'; ?>" type="submit" class="button" value="Cancel">
                               </div>
							   
							   <div style="width:40px; height:30px; float:left; margin-left:150px; margin-top:16px;">
                              <a href="#" ><input name="TOYOTAInvoice" type="submit" class="button" value="Print"></a>
                               </div>
                            
                          </div> 
                           </div>                                                              
                    </div>
                  
                
				</div>

          </form>         

          </div> 
          
     </div>       
</div>




<!--Footer Block -->
<div id="footer-wrap1">
        <?php include("../../footer.php") ?>
  </div>
<!--Footer Block - End-->
</center></body>
</html>
<?

?>