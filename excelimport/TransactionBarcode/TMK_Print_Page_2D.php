<?php 
	include '../../functions.php';
	sec_session_start();
	require_once '../../masterclass.php';
	include("../../header.php");
	include '../../Rupees_In_Words.php';
	include '../../Convert amount to Word.php';
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
		
		if (!empty($BarCode)){
//********************************************************************************************************************


		$OrderNo		= strtoupper($_POST['OrderNo']);
		$OrderDate		=$_POST['SRP_FromDate'];
		$ESugamNo		=strtoupper($_POST['ESugamNo']);
		$PDSNo			=substr(strtoupper($_POST['PDSNo']),0,2). ' ' . substr(strtoupper($_POST['PDSNo']), 2);
	
		
		// if (!empty($OrderNo) && !empty($OrderDate) && !empty($ESugamNo) && !empty($PDSNo))
		// {
		
		$_SESSION['OrderNo'] = $_POST['OrderNo'];
		if($OrderNo!="" || $OrderDate!="" || $ESugamNo!="" || $PDSNo!=""){
		$UpdateQuery= "UPDATE tkm_invoice_details SET TKM_OrderNo='".$OrderNo."',TKM_OrderDate='".$OrderDate."',TKM_ESugamNo='".$ESugamNo."',TKM_PDSNo='".$PDSNo."' WHERE TKM_VchNo ='".$BarCode."'";
		$Invoiceresult1 = mysql_query($UpdateQuery);
		
		unset($OrderNo);
		unset($_POST['SRP_FromDate']);
		unset($ESugamNo);
		unset($PDSNo);
		}
	
		//********************************************************************************************************************
	
		 $DutyQuery= "SELECT * FROM tkm_invoice_detail_view tkmv left join tkm_invoice_details tkm on tkm.TKM_VchNo= tkmv.vTKM_VchNo WHERE vTKM_VchNo='".$_POST['bulkmasters']."'";
		 $DutyResult = mysql_query($DutyQuery);
		 $DutyDetails = mysql_fetch_array($DutyResult);
		 
		 // $obj_no_to_words = new number_to_words();
		// $bed_words_in_rupees = $obj_no_to_words->convert_number_to_words($DutyDetails['vTKM_ExciseAmount']);
		 // $bed_words_in_rupees = ucwords($bed_words_in_rupees);
		 // $vat_words_in_rupees = $obj_no_to_words->convert_number_to_words( $DutyDetails['vTKM_VatAmt']);
		 // $vat_words_in_rupees = ucwords($vat_words_in_rupees);
		  // $gt_words_in_rupees = $obj_no_to_words->convert_number_to_words( $DutyDetails['vTKM_GrandTotal']);//+$DutyDetails['Vat_cst']);
		  // $gt_words_in_rupees = ucwords($gt_words_in_rupees);
		  
		 $_SESSION['TKM_ItemAmt']=number_format((float)(round($DutyDetails["TKM_ItemAmt"])), 2, '.', '');
		 $_SESSION['Sales_bedamt']=number_format((float)(round($DutyDetails["vTKM_CGSTAmt"])), 2, '.', '');
		 $_SESSION['Sales_Subtotalamt']=number_format((float)(round($DutyDetails["vTKM_SubTotal"])), 2, '.', '');
		 $_SESSION['Sales_Vatamt']=number_format((float)(round($DutyDetails["vTKM_VatAmt"])), 2, '.', '');
		 $_SESSION['Sales_Grandamt']=number_format((float)(round($DutyDetails['vTKM_GSTGrandToatalAmt'])), 2, '.', '');
		
		  
		    $obj_Number_to_Word = new Convert_Number_Into_Word();
			$bed_words_in_rupees = $obj_Number_to_Word->array_words($_SESSION['Sales_bedamt']);
			$bed_words_in_rupees = ucwords($bed_words_in_rupees);
			
			  $vat_words_in_rupees = $obj_Number_to_Word->array_words($_SESSION['Sales_Vatamt']);
			   $vat_words_in_rupees = ucwords($vat_words_in_rupees);
			  
			
			$gt_words_in_rupees = $obj_Number_to_Word->array_words($_SESSION['Sales_Grandamt']);
			 $gt_words_in_rupees = ucwords($gt_words_in_rupees);
			 
		  
		$_SESSION['bed'] = str_replace('And','',$bed_words_in_rupees);
		
		 $_SESSION['vat'] = str_replace('And','',$vat_words_in_rupees);
		 $_SESSION['total']= str_replace('And','',$gt_words_in_rupees);
		  
		   $DutyQuery1= "SELECT * FROM tkm_invoice_details WHERE TKM_VchNo='".$_POST['bulkmasters']."'";
		    $DutyResult1 = mysql_query($DutyQuery1);
		 $DutyDetails1 = mysql_fetch_array($DutyResult1);
		 $_SESSION['PerThousandRate']=$DutyDetails1['TKM_ItemRate']*1000;
		 
		 
		 // $Test="GD8-64F-HU";
		 // echo  str_replace('-', '',$Test);
		 // exit;
		
		$condition1= "SELECT pt.HSNNo,tkm.TKM_PDSNo, tkm.TKM_VchNo, tkm.TKM_Vch_Date, tkm.TKM_ItemCode, tkm.TKM_ItemQty, tkmv.vTKM_ExciseAmount,vTKM_IGSTAmt, vTKM_SGSTAmt, vTKM_CGSTAmt, vTKM_GSTGrandToatalAmt, tkmv.vTKM_VatAmt,tkmv.vTKM_GrandTotal FROM tkm_invoice_details tkm LEFT JOIN tkm_invoice_detail_view tkmv ON tkm.TKM_VchNo=tkmv.vTKM_VchNo LEFT JOIN partmaster pt ON tkm.TKM_ItemCode=pt.PartNo WHERE tkm.TKM_VchNo='".$BarCode."'";
		$Invoiceresult = mysql_query($condition1);
		$flag = 0;
		 while($result = mysql_fetch_array($Invoiceresult)){
		
			if($flag == 0){
				$BarcodeValue .= $result['TKM_PDSNo'].",";
				$BarcodeValue .= $result['TKM_VchNo'].",";
				
				$BarDate=	$result['TKM_Vch_Date'];
				$BarDateFormat = new DateTime($BarDate);

				$BarcodeValue .= $BarDateFormat->format('dmy').",";
				$BarcodeValue .= $result['vTKM_GSTGrandToatalAmt'].",";//$result['vTKM_GSTGrandToatalAmt'].",";
				$BarcodeValue .=  $result['vTKM_SGSTAmt'].",";//$result['vTKM_ExciseAmount'].",";
				$BarcodeValue .= $result['vTKM_SGSTAmt'].",";
				$BarcodeValue .= "0.00".",";
				$BarcodeValue .= "0.00".",";
				$BarcodeValue .= "1/1"."~";	
			}
			 $BarcodeValue .= $result['TKM_ItemCode'].",".$result['TKM_ItemQty'].",".$result['HSNNo']."~";
			$flag++;
		 }
			echo 	$BarcodeValue1 = str_replace(" ","%20",$BarcodeValue);
			
			$BarCodeTest=  str_replace("/","",$BarCode);
			$_SESSION['BarCodeTest'] = $BarCodeTest;
		 
		
		
		 
					$url = "http://localhost/Triton/tcpdf_min/2D_Barcode.php?D=".$BarcodeValue1."";//$BarcodeValue1;
					//$url = "http://localhost:8080/Triton/tcpdf_min/2D_Barcode.php?D=".$BarcodeValue1."";//$BarcodeValue1;
					$img = '../../Barcode/'.$BarCodeTest.'.png';
					file_put_contents($img, file_get_contents($url)); 
   
					header('Location:../../PhpJasperLibraryTriton/TMK_invoice_file_2D.php'); 
	// }
	// else{
	
	// echo"<script>alert('Insert all the fields','TMK_Print_Page_2D.php')</script>";
	
	// }
	}
	else{
	echo"<script>alert('Select proper master name','TMK_Print_Page_2D.php')</script>";
	}
	}
?>

<title><?php echo $_SESSION['title']; ?></title>
</head>

<body><center>

<?php include("../../menu.php") ?>
<?php include("common_functions.php"); ?>
<!--Third Block - Container-->
<div style="width:100%; height:auto; float:none;">
     <div style="width:980px; height:auto; float:none; margin-top:8px;" class="container">
                  
          <div style="width:950px; height:auto; float:left; margin-bottom:10px; margin-left:15px;" id="center">  
            <!-- form id start-->
             <form method="POST" action="<?php $_PHP_SELF ?>" enctype="multipart/form-data">
            <div style="width:930px; height:auto; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;">
                    
						<div style="width:930px; height:40px; float:left;  margin-left:0px;" class="head">
						<p>2D Print Invoice</p>
						</div>
                       <div style="width:930px; height:auto; padding-bottom:5px; float:right; " class="cont">
					    <div style="width:930px; height:auto; padding-bottom:5px; float:left; " class="cont">
                        <div style="width:155px; height:30px;  float:left; margin-top:5px; margin-left:3px;">
                                 <label  >Select Invoice Number</label><label style="color:#F00;">*</label>
                               </div>
                               <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                    <select name="bulkmasters">
                                  
                                   	 <option value=""><--Select--></option> 
											<?
                                            $triton_name = ($_POST['TKM_VchNo']) ? $_POST['TKM_VchNo'] : '';

                                            $list = mysql_query("SELECT TKM_VchNo FROM tkm_invoice_details Where TKM_PartyName= 'Toyota Kirloskar Motor Pvt Ltd' ");

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
                                  <label>Order No</label><label style="color:#F00;"></label>
                               </div>
                              <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                   <input type="text"  name="OrderNo" style="text-transform:uppercase;" value="<?php echo $OrderNo;?>"  maxlength="12" onKeyPress="return validatee(event)"/>                             
                               </div>
							   
							   <div style="width:155px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Order Date</label><label style="color:#F00;"></label>
                               </div>
                              <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                   <!--input type="text"  name="OrderDate" value="<-?php echo $OrderDate;?>"  maxlength="15" onKeyPress="return validatee(event)"/-->  
								   <input type="text" name="SRP_FromDate" id="rp_frdate" title="Select date" value="<?php echo $_POST['SRP_FromDate']; ?>" readonly />								   
                               </div>
							   
						    </div>
							
							<div style="width:930px; height:auto; padding-bottom:5px; float:left; " class="cont">
							<div style="width:155px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>E-Sugam No</label><label style="color:#F00;"></label>
                               </div>
                              <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                   <input type="text"  name="ESugamNo" style="text-transform:uppercase;" value="<?php echo $ESugamNo;?>"  maxlength="11" onKeyPress="return validatee(event)"/>                             
                               </div>
							   
							   <div style="width:155px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>PDS No</label><label style="color:#F00;"></label>
                               </div>
                              <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                   <input type="text"  name="PDSNo" style="text-transform:uppercase;" value="<?php echo $PDSNo;?>"  maxlength="14" onKeyPress="return validatee(event)"/>                             
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
                               <input name="TOYOTAInvoice" type="submit" class="button" value="Print">
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