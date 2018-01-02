<?php 
ob_start();
	include '../../functions.php';
	sec_session_start();
	require_once '../../masterclass.php';
	include("../../header.php");
	include '../../Rupees_In_Words.php';
	require_once '../../paginationfunction.php';
   //global $fname;
    unset($_SESSION['errorlogs']);
	// include '../../master/reports/inc/common_functions.php';
	include("common_functions.php");
if(login_check($mysqli) == false) 
{
	header('Location:../../index.php');// Redirect to login page!
} else
{
 //require_once 'Authentication_Rights.php';
	
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
		$BarCode=$_POST['InvoiceNo'];
		 $CountBarcode=count($	);
		 //$BasicAmt = array();
		 // $i = 0;
		// foreach ($_POST['s_no'] as $selectedOption5){
		// $Sno[$i] = $selectedOption5;
		// $i++;
		// }
		
		
		 
		 
		 
		 
		for($i=0;$i<$CountBarcode;$i++){
		// echo $BarCode[0];
		// echo $BarCode[1];
		// exit;
		
		 $BarcodeValue="";
		
		$_SESSION['InvoiceNo'] = $BarCode[$i];//$_POST['InvoiceNo'];
		if (!empty ($BarCode[$i])){//($BarCode)){
		
		$DutyQuery= "SELECT * FROM invoice_total WHERE Invoice_No='".$BarCode[$i]."'";
		 $DutyResult = mysql_query($DutyQuery);
		 $DutyDetails = mysql_fetch_array($DutyResult);
		 $obj_no_to_words = new number_to_words();
		$bed_words_in_rupees = $obj_no_to_words->convert_number_to_words($DutyDetails['Ed_amount']);
		 $bed_words_in_rupees = ucwords($bed_words_in_rupees);
		 $vat_words_in_rupees = $obj_no_to_words->convert_number_to_words( $DutyDetails['Vat_cst']);
		 $vat_words_in_rupees = ucwords($vat_words_in_rupees);
		  $gt_words_in_rupees = $obj_no_to_words->convert_number_to_words( $DutyDetails['total_bed_amt']+$DutyDetails['Vat_cst']);
		  $gt_words_in_rupees = ucwords($gt_words_in_rupees);
		$_SESSION['bed'] = $bed_words_in_rupees;
		$_SESSION['vat'] = $vat_words_in_rupees;
		 $_SESSION['total'] = $gt_words_in_rupees;
		 
		 $condition1= "SELECT id.PDS_Number, id.Invoice_No, id.Invoice_Date, id.Part_ID_No, id.Invoice_Qty, it.Ed_amount, it.Vat_cst FROM sales_invoice_details id LEFT JOIN invoice_total it ON id.Invoice_No=it.Invoice_No WHERE id.Invoice_No='".$BarCode[$i]."'";
		$Invoiceresult = mysql_query($condition1);
		$flag = 0;
		 while($result = mysql_fetch_array($Invoiceresult)){
		
			if($flag == 0){
				$BarcodeValue .= $result['PDS_Number'].",";
				$BarcodeValue .= $result['Invoice_No'].",";
				$BarcodeValue .= $result['Invoice_Date'].",";
				// $BarcodeValue .= $result['Grand_Total'].",";
				$BarcodeValue .= $result['Ed_amount'].",";
				$BarcodeValue .= "0.00".",";
				$BarcodeValue .= "0.00".",";
				$BarcodeValue .= "0.00".",";
				$BarcodeValue .= $result['Vat_cst'].",";
				$BarcodeValue .= "1/1"."~";	
			}
			$BarcodeValue .= $result['Part_ID_No'].",".$result['Invoice_Qty']."~";
			$flag++;
		 }
		 $BarcodeValue1 = str_replace(" ","%20",$BarcodeValue);
		
		
		
			
					$url = "http://localhost:81/SSV/tcpdf_min/QR_barcode.php?D=".$BarcodeValue1."";//$BarcodeValue;
					$img = '../../Barcode/'.$BarCode[$i].'.png';
					file_put_contents($img, file_get_contents($url));
			
					//header('Location:../../PhpJasperLibraryTriton/TMK_invoice_file.php');
					
	}
	
	//
	else{
		echo"<script>alert('Select proper master name','TMK_Print_page.php')</script>";
	}
	}
	
	}
	
    $GetAmountvalue="SELECT total_bed_amt, Vat_cst from invoice_total WHERE Invoice_No='".$_SESSION['InvoiceNo']."'";
    $GetAmountvalueresult = mysql_query($GetAmountvalue);

   while($result = mysql_fetch_array($GetAmountvalueresult)){
	
				$total_bed_amt = $result['total_bed_amt'];
				$Vat_cst = $result['Vat_cst'];
				
						
			}
		$_SESSION['total_bed_amt'] = $total_bed_amt;
		$_SESSION['Vat_cst'] = $Vat_cst;
		
		if(isset($_POST['Cancel']))
{
		unset($_SESSION['codesval']);
		unset($_SESSION['Period']);
		unset($_SESSION['namesval']);
	header('Location:TMK_Print_Page.php');
// C:\xampp\htdocs\SSV\excelimport\TransactionBarcode\TMK_Print_Page.php
}
		
			
			
	ob_flush();

?>


<script type="text/javascript">

function GetInvoiceNumber(){
	//var pds = SRP_FromDate;SRP_ToDate
	var FromDate = $(".from_date").val();
	var ToDate =$(".to_date").val();
	//alert(FromDate);
	
	var Date_Data = {FromDateValue : FromDate,TODateValue : ToDate};
	if( FromDate.trim() != "" && ToDate.trim() != ""){
	 // alert(FromDate);
		var url = "GetPDS.php";
		  $.ajax({
			type: 'POST',
			url: 'GetPDSDetails.php',
			data: Date_Data,
			dataType: 'json',
			success: function( resp ) {
			   var deatils = resp.Datedetails;
			  
			   //var i =0;
			   var InvoiceNumber = document.getElementById('InvoiceNo');	
				InvoiceNumber.length = 0;
				for (i = 0; i < deatils.length; i++) 
					{
						InvoiceValue = deatils[i].Invoice_No;
						InvoiceNumber.options.add(new Option(InvoiceValue,InvoiceValue));	
						
					}

			}
		});
	}
}	



</script>

<title><?php echo $_SESSION['title']; ?></title>
</head>

<body><center>

<?php include("../../menu.php") ?>

<!--Third Block - Container-->
<div style="width:100%; height:auto; float:none;">
        <div style="width:980px; height:auto; float:none; margin-top:8px;" class="container">
		
		

            <div style="width:950px; height:auto; float:left; margin-bottom:10px; margin-left:15px;" id="center">  
                <!-- form id start-->
                <form method="POST" action="<?php // $_PHP_SELF          ?>">
                	  <!--? require_once'all_list.php' ?-->
					 
                <div style="width:930px; height:auto;   min-height: 180px; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;">
					<div style="width:930px; height:25px; float:left;  margin-left:0px;" class="head">
                            <p>QR Print Invoice</p>
                        </div>
						<table  id="default" style="display:none;"  >
						<tr><td>
						<input type="text" name="curdate" id="curdate" value="<?php echo date('Y-m-d'); ?>" />
						</td></tr></table>
                        <!-- main row 1 start-->     
                        <div style="width:925px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
                            <!-- col1 -->   
                        <div style="width:800px; height:auto; padding-bottom:5px; float:left; " class="cont">
                        <!--Row1 --> 
								
						<div style="float:left;width:400px;height:auto">
				      <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                        <label>Period</label><label style="color:#F00;">*</label>
                                  </div>
						   <div style="width:190px; height:30px;;  float:left;  margin-top:5px; margin-left:3px;">
						   <select name='Period' id="Period" onChange="datefun();GetInvoiceNumber()">
								<!--option value="<--? if(!empty($_POST['Period'])){ echo $_POST['Period'];}else{?> 0<--? } ?></"><-? if(!empty($_POST['Period'])){ echo $_POST['Period'];}else{?> --Select-- <-? } ?></option-->
								<option value=""><--Select--></option> 
								<option value="Only Today">Only Today</option>
								<option value="Only Yesterday">Only Yesterday</option>
								<option value="Custom">Custom</option>
							</select>
							  </div>
							  <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
							   <label>Select Invoice No</label><label style="color:#F00;">*</label>
                                </div>
								 <div style="width:190px; height:60px;;  float:left;  margin-top:5px; margin-left:3px;">
                                        
										<select name="InvoiceNo[]" id="InvoiceNo" multiple="multiple"  />
                                  
                                   	 <option value=""><--Select--></option> 
											<!--!?
                                            $triton_name = ($_POST['Invoice_No']) ? $_POST['Invoice_No'] : '';

                                            $list = mysql_query("SELECT Invoice_No FROM sales_invoice_header ");

                                            while ($row_list = mysql_fetch_assoc($list)) {
                                                $selected = '';

                                                if ($row_list['Invoice_No'] == $triton_name) {
                                                    $selected = ' selected ';
                                                }
                                                ?-->
                                           <option value="<? echo $InvoiceNo; ?>"<? echo $InvoiceNo; ?>> <? echo $InvoiceNo; ?> </option>
											<!--?
                                            }
                                            ?-->                

                                 
                                                                         
                                                        
                                   </select>
										
                                    </div>
								  
								 
                                </div>
                                <div style="float:left;width:400px;height:auto">
								
								<div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                        <label>From Date</label><label style="color:#F00;">*</label>
                                  </div>
                                    <div style="width:190px; height:30px;;  float:left;  margin-top:5px; margin-left:3px;">
                                        <input type="hidden" name="SRP_FromDate" class="from_date" id="rp_frdate" onChange="GetInvoiceNumber();" value="<?php echo $_POST['SRP_FromDate']; ?>"/>
										<input type="text" name="SRP_FromDate" class="from_date" id="frdate" onChange="GetInvoiceNumber();" readonly="readonly"  value="<?php echo $_POST['SRP_FromDate'];   ?>"/>
                                    </div>
									
                                 <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                        <label>To Date</label><label style="color:#F00;">*</label>
                                  </div>
                                    <div style="width:190px; height:30px;;  float:left;  margin-top:5px; margin-left:3px;">
                                        <input type="hidden" name="SRP_ToDate" class="to_date" id="rp_todate" onChange="GetInvoiceNumber();" value="<?php echo $_POST['SRP_ToDate']; ?>" />
										<input type="text" name="SRP_ToDate" class="to_date" id="todate" onChange="GetInvoiceNumber();" readonly="readonly" value="<?php echo $_POST['SRP_ToDate']; ?>" />
                                    </div>
                               
                                    
                                </div>
                                <div style="clear:both"></div>

                            </div>
							</div>

                        
						</div>
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
}
?>