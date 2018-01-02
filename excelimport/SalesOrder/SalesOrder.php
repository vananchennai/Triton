<?php 
//header('Content-Type: application/json');
include '../../functions.php';
sec_session_start();
//require_once '../../masterclass.php';
include("../../header.php");

if(login_check($mysqli) == false) 
{
header('Location:../../index.php');// Redirect to login page!
} else
{
	global $ttname,$result12,$ProductCode,$ProductDescription,$UOM ,$ProductType,$warrantyapplicable,$EnableSerialno,$ServiceCompensation,$IdentificationCode,$serialnodigits,$salestype,$proratalogic,$logic,$Status,$scode,$i,$j,$sname;
	$scode = 'ProductCode';
	$sname = 'ProductDescription';
	$tname	= "sales_invoice_header";
	require_once '../../searchfun.php';		
$stname="productmasterupload";
require_once '../../paginationfunction.php';
$news = new News(); // Create a new News Object

$pagename = "Productsdetails";
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
	alert("you are not allowed to do this action!",'productdetails.php');//document.location='pricelistmaster.php';	
	</script>
	<? }
	if(isset($_GET['permiss']))
	{
	?>
	<script type="text/javascript">
	alert("you are not allowed to do this action!",'productdetails.php');//document.location='pricelistmaster.php';	
	</script>
	<? }
	
if(isset($_POST['Save'])) // If the submit button was clicked
{
// echo $_POST['SRP_FromDate'];
// exit;


	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
	// Basic_ value, Assesseble_Value    basic_Amount,assessble_Amount
	$Sno =array();
	$EkbOrderNo = array();
	$CustomerPartno = array();
	$PartIdNo = array();
	$PartDescription = array();
	$Qty = array();
	$Basic = array();
	$Assesseble=array();
	$BasicAmt = array();
	$AssessebleAmt=array();
	
	
	
	$i = 0;
    foreach ($_POST['s_no'] as $selectedOption5){
    $Sno[$i] = $selectedOption5;
    $i++;
  }
  
  $i = 0;
    foreach ($_POST['ekb_order'] as $selectedOption1){
    $EkbOrderNo[$i] =  $selectedOption1;
    $i++;
  }
  $i = 0;
    foreach ($_POST['customer_part_no'] as $selectedOption1){
    $CustomerPartno[$i] =  $selectedOption1;
    $i++;
  }
  $i = 0;
    foreach ($_POST['part_no'] as $selectedOption1){
    $PartIdNo[$i] =  $selectedOption1;
    $i++;
  }
  $i = 0;
    foreach ($_POST['part_description'] as $selectedOption1){
    $PartDescription[$i] =  $selectedOption1;
    $i++;
  }
  $i = 0;
    foreach ($_POST['qty'] as $selectedOption1){
    $Qty[$i] =  $selectedOption1;
    $i++;
  }	
  
  $i = 0;
    foreach ($_POST['basic_value'] as $selectedOption1){
    $Basic[$i] =  $selectedOption1;
    $i++;
  }	
  
  $i = 0;
    foreach ($_POST['assessble_value'] as $selectedOption1){
    $Assesseble[$i] =  $selectedOption1;
    $i++;
  }	
   $i = 0;
    foreach ($_POST['basic_Amount'] as $selectedOption1){
    $BasicAmt[$i] =  $selectedOption1;
    $i++;
  }	
   $i = 0;
    foreach ($_POST['assessble_Amount'] as $selectedOption1){
    $AssessebleAmt[$i] =  $selectedOption1;
    $i++;
  }	
  
  $i = 0;
		$count = count($Sno);
		$j = 0;
		// echo $Assesseble[$i];
		// exit;
		if((($_POST['InvoiceNo'])&&!empty($_POST['SRP_FromDate']) &&!empty($_POST['PDSNumber'])&&!empty($_POST['ED'])&&!empty($_POST['VAT']) && $count >0))
	{
		
		for($i=0;$i<$count;$i++){
	
			$statement = "insert into sales_invoice_details (Invoice_No, Invoice_Date, PDS_Number, S_No, EKB_Order_No, Customer_Part_No, Part_ID_No, Part_Description, Invoice_Qty, Basic_value, Assesseble_Value, Basics_Amount, Assesseble_Amount) VALUES('".$_POST['InvoiceNo']."','".$_POST['SRP_FromDate']."','".$_POST['PDSNumber']."','".trim($Sno[$i])."','".trim($EkbOrderNo[$i])."','".trim($CustomerPartno[$i])."','".trim($PartIdNo[$i])."','".trim($PartDescription[$i])."','".trim($Qty[$i])."','".trim($Basic[$i])."','".trim($Assesseble[$i])."','".trim($BasicAmt[$i])."','".trim($AssessebleAmt[$i])."')";
	   		$execute1= mysql_query($statement);
			
			}
}			
   			
				



 $post['Invoice_No'] = $_POST['InvoiceNo'];//strtoupper(str_replace('&', 'and',$_POST['PartNumber']));
 $post['Invoice_Date'] = $_POST['SRP_FromDate'];//strtoupper(str_replace('&', 'and',$_POST['PartName']));

	//$post['userid'] = $_SESSION['username'];


	//if((((!empty($_POST['InvoiceNo'])&&!empty($_POST['SRP_FromDate'])&&!empty($_POST['Basic']) )&& ($_POST['PDSNumber']))||
	
	 // if((($_POST['InvoiceNo'])&&!empty($_POST['SRP_FromDate'])&&!empty($_POST['Basic']) &&!empty($_POST['PDSNumber'])&&!empty($_POST['Narration'])&&!empty($_POST['TRINPartNo'])&&!empty($_POST['ED'])&&!empty($_POST['Assessble'])&&!empty($_POST['VAT'])))
	// {
	
	if((($_POST['InvoiceNo'])&&!empty($_POST['SRP_FromDate']) &&!empty($_POST['PDSNumber'])&&!empty($_POST['ED'])&&!empty($_POST['VAT']) && $count >0))
	{
	
    

		//$post['TRIN_Part_No']=$_POST['TRINPartNo'];
		$post['PDS_Number']=$_POST['PDSNumber'];
		//$post['Basic_Value']=$_POST['Basic'];
		$post['Narration']=$_POST['Narration'];
		$post['ED_Value']=$_POST['ED'];
		//$post['Assessble_Value']=$_POST['Assessble'];
		$post['VAT_Value']=$_POST['VAT'];
	
	
		
			$news->addNews($post,$tname);
			echo '<script type="text/javascript">alert("Created Sucessfully!","SalesOrder.php");</script>';				
		//
		
	}
	else
	{
	?>
	<script type="text/javascript">
	alert("Enter Mandatory Fields!");//document.location='pricelistmaster.php';
	</script>
	<?
	}
}


if(isset($_POST['Update'])) // If the submit button was clicked
{
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
$post['ProductCode'] = strtoupper(trim($_POST['ProductCode']));
$post['ProductDescription'] = strtoupper(trim($_POST['ProductDescription']));
$post['ProductGroupCode'] = $_POST['ProductGroup'];
$post['Status'] = $_POST['Status'];
$post['UOMCode'] = $_POST['UOM'];
$post['user_id'] = $_SESSION['username'];


$ProductCode=strtoupper(trim($_POST['ProductCode']));
$ProductDescription=strtoupper(trim($_POST['ProductDescription']));
$ProductGroup=$_POST['ProductGroup'];
$Status=$_POST['Status'];
$UOM=$_POST['UOM'];
date_default_timezone_set ("Asia/Calcutta");
$post['m_date']= date("y/m/d : H:i:s", time());

	if(!empty($_POST['ProductCode'])&&!empty($_POST['UOM'])&&!empty($_POST['ProductDescription'])&&!empty($_POST['ProductGroup']))
	{	 
	$codenamedcheck=0;
	if($_SESSION['ProductDescriptionold']!=$ProductDescription)
	{
		$p2=strtoupper( preg_replace('/\s+/', '',$post['ProductDescription']));
		$repqry="SELECT REPLACE(  `ProductDescription` ,  ' ',  '' ) AS ProductDescription  FROM  `productmaster` where ProductDescription = '".$p2."' or ProductDescription = '".$post['ProductDescription']."' or ProductCode = '".$p2."' or ProductCode = '".$post['ProductDescription']."'";
		$repres= mysql_query($repqry) or die (mysql_error());
		$codenamedcheck=mysql_num_rows($repres);
	}
	
	if($codenamedcheck>0 || ($ProductCode == $ProductDescription))
	{
		?>
		<script type="text/javascript"> alert("Duplicate entry!");</script>
		<?
		}
		else
		{	
			$wherecon = "ProductCode ='".$post['ProductCode']."'";
			$spost['ProductCode']        = strtoupper(trim($_POST['ProductCode']));
			$spost['ProductDescription'] = strtoupper(trim($_POST['ProductDescription']));
			$spost['ProductGroupCode'] = $_POST['ProductGroup'];
			$spost['UOMCode'] = $_POST['UOM'];
			$spost['user_id'] = $_SESSION['username'];
			date_default_timezone_set ("Asia/Calcutta");
		    $spost['m_date']= date("y/m/d : H:i:s", time());
			$news->editNews($spost,$tname,"ProductCode ='".$spost['ProductCode']."'");
			$trans_sp_qry="CALL sp_t_upload('".$stname."','productdetails','".$post['ProductCode']."','".$_POST['ProductGroup']."','UPDATE');";
			mysql_query($trans_sp_qry) or die (mysql_error());
			echo '<script type="text/javascript">alert("Updated Sucessfully!","SalesOrder.php");</script>';	
			unset($_SESSION['ProductDescriptionold']);
		}
	}
	else
	{
	?>
	<script type="text/javascript">
	alert("Enter Mandatory Fields!");//document.location='pricelistmaster.php';
	</script>
	<?
	}
}

// EDIT LINK FUNCTION 

if(!empty($_GET['edi']))
{
		unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
$prmaster  = $_GET['edi'];
$result=mysql_query("SELECT * FROM productmaster where ProductCode ='".$prmaster."' ");
$myrow1 = mysql_num_rows($result);

	if($myrow1==0)	{ 
	?><script type="text/javascript">alert("No Data Found!!",'SalesOrder.php');</script><?
	}
	else
	{
	$myrow = mysql_fetch_array($result);
	$ProductCode = $myrow['ProductCode'];
	$ProductDescription=$myrow['ProductDescription'];
	$_SESSION['ProductDescriptionold']=$myrow['ProductDescription'];
	$ProductGroup = $myrow['ProductGroupCode'];
    $pgg= mysql_query("select ProductGroup from productgroupmaster where ProductCode='".$myrow['ProductGroupCode']."' ")  ;
	$record11 = mysql_fetch_array($pgg);
	$ProductGroup = $record11['ProductGroup'];
	$ProductGroup1 = $myrow['ProductGroupCode'];
	$UOM = $myrow['UOMCode'];
	$result1="SELECT * FROM productmaster where ProductCode ='".$prmaster."' ";
	$result12=mysql_query($result1);
	}
$prmaster = NULL;
}



// Check if delete button active, start this 
if(isset($_POST['Delete']))
{
		unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
	if(!isset($_POST['checkbox']))
	{
	?>
	<script type="text/javascript">
	alert("Select data to delete!",'SalesOrder.php');
	</script>
	<?
	}
	else
	{
	$checkbox = $_POST['checkbox']; //from name="checkbox[]"
	$countCheck = count($_POST['checkbox']);
	$message=NULL;
		for($i=0;$i<$countCheck;$i++)
		{
		     $prodidd = $checkbox[$i];
		     $wherecon= " ProductCode = '".$checkbox[$i]."'";
		     $mtabl='productmaster';
		     $repqry1="select ProductCode
		     FROM productmaster
		     WHERE ProductCode='".$checkbox[$i]."' 
		     and exists(
		     SELECT productcode
		     FROM pricelistmaster
		     WHERE productcode=  '".$checkbox[$i]."') ";
		     $repres= mysql_query($repqry1) or die (mysql_error());
		     $myrow1 = mysql_num_rows($repres);
        
			if($myrow1==0)	
			{
			
			$mkrow = mysql_query("SELECT Status FROM productmasterupload where Code='".$checkbox[$i]."'  and Status !='0'");
			$val=mysql_num_rows($mkrow);
			
				if($val==0)
				{
				$wherec= "Code='".$checkbox[$i]."'";
				$news->deleteNews($stname,$wherec); 
				
				$wherecon= "ProductCode ='".$checkbox[$i]."'";
		        $news->deleteNews($mtabl,$wherecon);
				$news->deleteNews($tname,$wherecon);
		
				?>
				<script type="text/javascript">
				alert("Deleted  Successfully!!",'SalesOrder.php');
				</script>
				<?
				
				}
				else
				{
				?>
				<script type="text/javascript">
				alert("You Can't delete already send to Distributor ",'SalesOrder.php');
				</script>
				<?	
				}
			}
			else
			{
			?>
			<script type="text/javascript">
			alert("you can't delete already used in other masters!",'SalesOrder.php');//document.location='pricelistmaster.php';
			</script>
			<?
			$message =  $message+'".$checkbox[$i]."'+",";
			}
		}
		if($message!=NULL)
		{
		} 
		else
		{
		?>
		<script type="text/javascript">
		alert("Deleted  Successfully!",'SalesOrder.php');
		//setInterval(function(){document.location='pricelistmaster.php';},2000);
		//document.location='pricelistmaster.php';
		</script>
		<?
		}
	}
}


	$_SESSION['type']=NULL;
	$productmaster='select * from productmaster';
	if(isset($_POST['Excel']))
	{
	$productmaster = "SELECT * FROM productmaster order by m_date";
	$_SESSION['type']='TallyExcel';
	$_SESSION['query']=$productmaster;
	header('Location:ExportProduct.php');
	}
	if(isset($_POST['PDF']))
	{

	$select=$_POST['Type'];
	if(!empty($_POST['codes'])&&!empty($_POST['names']))
	{
	$condition="SELECT * FROM productmaster WHERE ProductCode like'".$_POST['codes']."%' OR ProductDescription like'".
	$_POST['names']."%' order by m_date";

	}
	else if(!empty($_POST['codes'])&&empty($_POST['names']))
	{
	$condition="SELECT * FROM productmaster WHERE ProductCode like'".$_POST['codes']."%'  order by m_date";

	}
	else if(!empty($_POST['names'])&&empty($_POST['codes']))
	{
	$condition="SELECT * FROM productmaster WHERE ProductDescription like'".$_POST['names']."%'  order by m_date";

	}
	else
	{

	$condition="SELECT * FROM productmaster order by m_date";

	}
	$productmaster=$condition;
	if($select=='PDF')
	{
			$_SESSION['type']='PDF';
			$_SESSION['query']=$productmaster;
			//$pricelistmaster;
			$myFile = "testFile.txt";
			$fh = fopen($myFile, 'w') or die("can't open file");
			$stringData =NULL;
			$myquery = mysql_query($productmaster);
			while( $myrecord = mysql_fetch_array($myquery))
			{
			
				$groupgg="SELECT * FROM producttypemaster where ProductTypeCode='".$myrecord['ProductType']."'";
				$groupgg1 = mysql_query($groupgg);
				$cntnog=mysql_num_rows($groupgg1);
				if($cntnog==1)
				{
					$groupgg2 = mysql_fetch_array($groupgg1);
					$testtemp=$groupgg2['ProductTypeName'];
				}
				else
				{
					$testtemp ="";
				}
				/* $group="SELECT * FROM productuom where productuomcode='".$myrecord['UOM']."'";
				$gro = mysql_query($group);
				$cnt=mysql_num_rows($gro);
				if($cnt==1)
				{
				$g2 = mysql_fetch_array($gro);
				$test=$g2['productuom'];
				}
				else
				{
				$test ="";
				}*/
				$stringData =$myrecord[0]."\t ;".$myrecord[3]."\t;\n";
				fwrite($fh, $stringData);
		
		
			}
			//	
			fclose($fh);
			header('Location:ExportProduct.php');
	}
			elseif($select=='Excel')
			{
			$_SESSION['type']='Excel';
			$_SESSION['query']=$productmaster;
			header('Location:ExportProduct.php');
			}
			elseif($select=='Document')
	{
	$_SESSION['type']='Document';
	$_SESSION['query']=$productmaster;
	header('Location:ExportProduct.php');
	}

}

if(isset($_POST['Cancel']))
{
		unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
header('Location:productdetails.php');
}
$maxqry = mysql_query("select max(Id)as maxid FROM sales_invoice_header");
$maxrow = mysql_fetch_array($maxqry);
$vouqry = mysql_query("SELECT Prefix_Name,Financial_Year FROM prefix_master");
$vourow = mysql_fetch_array($vouqry);
$maxid = $maxrow['maxid'];
if($maxid == ""){
	$maxid = 001;
}else{
	$maxid = $maxid+1;
}
$prefix = $vourow['Prefix_Name'].'-'.$vourow['Financial_Year'];
 $max_prefix = $prefix.'-'.$maxid;
// exit;

?>

<script type="text/javascript">

var line_no_array = [];
 function deleteRow(id) {
	var deletecolumnid = "si_"+id;
	var deleting= confirm("Do you really want to delete the row containing information??");
	if (deleting== true)
	{
	  $("#"+deletecolumnid).remove();
	}
	else
	{
	}
	
}
function CaluculateAmount(){
	//var count = $("#number_of_rows").val();
	var count = line_no_array.length;
	var i =0;
	var total = 0.00;
	for(i=0;i<count;i++){
		var qtyid= "#qty_"+line_no_array[i+1].toString();
		var baseid= "base_"+line_no_array[i+1].toString();
		var assessableid= "assessable_"+line_no_array[i+1].toString();
		var qty = $(qtyid).val();
		//var qy1 = $("#qty_1").val();
		var assessable = $("#"+assessableid).val();
		var basic = $("#"+baseid).val();
		var amount =  parseFloat(qty).toFixed(2) * parseFloat(assessable).toFixed(2);
		//amount = parseFloat(amount).toFixed(2);
		//total =parseFloat(total).toFixed(2);
		var sum = (total + amount);
		total = parseFloat(sum).toFixed(2);
		//total = parseFloat(total).toFixed(2);
	}
	//total = parseFloat(total).toFixed(2)
	$("#Assessble").val(total);
	
}

function AmountCalculation(line_no){
	var qtyid= "#qty_"+line_no.toString();
	var baseid= "base_"+line_no.toString();
	var assessableid = "assessable_"+line_no.toString();
	var baseamountid = "basic_amount_"+line_no.toString();
	var assessableamountid = "assessable_amount_"+line_no.toString();
	var qty = $(qtyid).val();
		//var qy1 = $("#qty_1").val();
	var assessable = $("#"+assessableid).val();
	var basic = $("#"+baseid).val();
	var basic_amount = qty  *basic;
	basic_amount = parseFloat(basic_amount).toFixed(2);
	var assesable_amount = qty  *assessable;
	assesable_amount = parseFloat(assesable_amount).toFixed(2);
	$("#"+baseamountid).val(basic_amount);
	$("#"+assessableamountid).val(assesable_amount);
}
function getpdsdetails(){
	var pds = $("#PDSNumber").val();
	var data = {pdsnumber : pds};
	if( pds.trim() != ""){
		var url = "GetPDS.php";
		  $.ajax({
			type: 'POST',
			url: 'GetPDSDetails.php',
			data: data,
			dataType: 'json',
			success: function( resp ) {
			   var deatils = resp.pdsdetails;
			   var i =0;
			   var pdsdetails_div  = '<tr> ';
					//pdsdetails_div += '<input type="hidden" name="number_of_rows" id="number_of_rows" value="'+deatils[i].line_no+'"' />
					pdsdetails_div += '<td  style=" font-weight:bold; width:10px; text-align:center;">S.No<label style="color:#F00;">*</label></td> ';
					pdsdetails_div += '<td  style=" font-weight:bold; width:30px; text-align:center;">Ekb Order No<label style="color:#F00;">*</label></td>';
					pdsdetails_div += '<td  style=" font-weight:bold; width:30px; text-align:center;">Customer Part No<label style="color:#F00;">*</label></td>';
					pdsdetails_div += '<td  style=" font-weight:bold; width:30px; text-align:center;">Part Id No<label style="color:#F00;">*</label></td>';
					pdsdetails_div += '<td  style=" font-weight:bold; width:30px; text-align:center;">Part Description<label style="color:#F00;">*</label></td>';
					pdsdetails_div += '<td  style=" font-weight:bold; width:30px; text-align:center;">PO Qty<label style="color:#F00;">*</label></td>';
				    pdsdetails_div += '<td  style=" font-weight:bold; width:90px; text-align:center;">Basic Price<label style="color:#F00;">*</label></td>';
					pdsdetails_div += '<td  style=" font-weight:bold; width:90px; text-align:center;">Assessible Price<label style="color:#F00;">*</label></td>';
					pdsdetails_div += '<td  style=" font-weight:bold; width:90px; text-align:center;">Basic Amount<label style="color:#F00;">*</label></td>';
					pdsdetails_div += '<td  style=" font-weight:bold; width:90px; text-align:center;">Accessible Amount<label style="color:#F00;">*</label></td>';
					pdsdetails_div += '<td  style=" font-weight:bold; width:10px;">&nbsp;</td>';
					pdsdetails_div +=' </tr>';
			   for(i=0;i<deatils.length;i++){
					line_no_array[i] = i;
					pdsdetails_div += '<TR id="si_'+deatils[i].line_no+'"><TD style="text-align:center">';
					pdsdetails_div += '<input type="text" style="width:40px;" name="s_no[]" readonly id="si_no" value="'+deatils[i].line_no+'" />';
					pdsdetails_div += '</TD><TD style="text-align:center">'
					pdsdetails_div += '<input type="text" style="width:90px;" name="ekb_order[]" readonly id="ekb_order" value="'+deatils[i].ekb_order_no+'" />';
					pdsdetails_div += '</TD><TD style="text-align:center">'
					pdsdetails_div += '<input type="text" style="width:90px;" name="customer_part_no[]" readonly id="customer_part_no" value="'+deatils[i].HIMLorToyotoPartNumber+'" />';	
					pdsdetails_div += '</TD><TD style="text-align:center">'
					pdsdetails_div += '<input type="text" style="width:95px;" name="part_no[]" readonly id="part_no" value="'+deatils[i].part_no+'" />';	
					pdsdetails_div += '</TD><TD style="text-align:center">'
					pdsdetails_div += '<input type="text" style="width:400px;" name="part_description[]" readonly id="part_description" value="'+deatils[i].Part_Name+'" />';	
					pdsdetails_div += '</TD><TD style="text-align:center">'
					pdsdetails_div += '<input type="text" style="width:50px;" name="qty[]" Onchange="AmountCalculation('+deatils[i].line_no+')" id="qty_'+deatils[i].line_no+'" value="'+deatils[i].unit_qty+'" />';
					pdsdetails_div += '</TD><TD style="text-align:center">'
					var BasicValue = deatils[i].Basic_Value;
					var AssessableValue = deatils[i].Assessable_Value;
					if(AssessableValue == null){
						AssessableValue = 0.00;
					}else{
						AssessableValue = parseFloat(AssessableValue).toFixed(2);
					}
					if(BasicValue == null){
						BasicValue = 0.00;
					}else{
						BasicValue = parseFloat(BasicValue).toFixed(2);
					}
					pdsdetails_div += '<input type="text" style="width:90px;"  name="basic_value[]" id="base_'+deatils[i].line_no+'" value="'+BasicValue+'" />';
					pdsdetails_div += '</TD><TD style="text-align:right;">'
					pdsdetails_div += '<input type="text" style="width:90px;"  name="assessble_value[]" id="assessable_'+deatils[i].line_no+'" value="'+AssessableValue+'" />';
					
					pdsdetails_div += '</TD><TD style="text-align:right;">'
					pdsdetails_div += '<input type="text" style="width:90px;"  name="basic_Amount[]" id="basic_amount_'+deatils[i].line_no+'" value="'+BasicValue*deatils[i].unit_qty+'" />';
					pdsdetails_div += '</TD><TD style="text-align:right;">'
					pdsdetails_div += '<input type="text" style="width:90px;"  name="assessble_Amount[]" id="assessable_amount_'+deatils[i].line_no+'" value="'+AssessableValue*deatils[i].unit_qty+'" />';
					
					pdsdetails_div += ' </TD><TD  class="remove_btn"><img src="del_img.jpg" style="cursor: pointer; width:20px;" onclick="deleteRow('+deatils[i].line_no+');"/></TD>';
					pdsdetails_div += '</TR>';
				}
				$("#dataTable").empty();
				$("#dataTable").html(pdsdetails_div);
				CaluculateAmount();
			}	
		  });
	}
	
	
}




	

function validateProductCode(key)
{
	var object = document.getElementById('PartNumber');
	if (object.value.length <50 || key.keycode==8 || key.keycode==46)
{
	
return true;
}
else
{
	alert("Enter only 50 characters");
	toutfun(object);
return false;
}
}







function numericFilter(txb) {
   txb.value = txb.value.replace(/[^\0-9]/ig, "");
}

	  
	  // Set the present object

/* function SetVis()
{
	try
   {
var a = document.getElementById('warrantyapplicableid').value
			

if(a =="YES")
{
	var div2 = document.getElementById('tabeldiv');
	div2.style.visibility = 'visible';


}
else
{
	var div2 = document.getElementById('tabeldiv');
	div2.style.visibility = 'hidden';
}
}
catch(Exception)
{alert("Error");}
} 
 */
function getFuncs()
{
//	SetVis(); 
	document.form1.SRP_FromDate.focus();
}
function getFuncs1()
{
	//SetVis(); 
	document.form1.InvoiceNo.focus();
}
</script>
<?php include("common_functions.php"); ?>
<title><?php echo $_SESSION['title']; ?>|| Product Master</title>
</head>
 <?php 
  if(empty($_SESSION['codesval']) && empty($_SESSION['namesval']))
{
 if(!empty($_GET['edi'])){?>
 
 <body class="default" onLoad="return getFuncs()">

<? }else{
?>


<body class="default" onLoad="return getFuncs1()">

 <? } 
}else{
?>
<body class="default" onLoad="document.form1.codes.focus()">

 <? } 
 ?>
 <center>

 <?php include("../../menu.php") ?>



<!--Third Block - Container-->
<div style="width:100%; height:auto; float:none;">
     <div style="width:1200px; height:auto; float:none; margin-top:8px;" class="container">
                  
          <div style="width:1200px; height:auto; float:left; margin-bottom:10px; margin-left:15px;" id="center">  
            <!-- form id start-->
             <form method="POST" action="<?php $_PHP_SELF ?>" name="form1" id="frm1">
            <div style="width:1200px; height:auto; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;">
                    
						<div style="width:1200px; height:25px; float:left;  margin-left:0px;" class="head">
						<p>Sales Invoice</p>
						</div>
						<!--Col1-->
               
					<div style="width:300px; height:auto; padding-bottom:8px; float:left; " class="cont">
						<div style="width:100px; height:25px; float:left;  margin-top:5px; margin-left:3px;">
								<label>Invoice No</label><label style="color:#F00;">*</label>
						</div>
						<div style="width:180px; height:25px; float:left;  margin-top:5px; margin-left:1px;">
								<input type="text" name="InvoiceNo" value="<?php echo $max_prefix; ?>" id="InvoiceNo" readonly onKeyPress="return validateProductCode(event)" value="<?php echo $InvoiceNo; ?>" onChange="return codetrim(this)" maxlength="50" style="text-transform:uppercase;">
						</div>
						<!--div style="width:100px; height:25px; float:left;  margin-top:5px; margin-left:3px;">
								<label>TRIN Part No</label><label style="color:#F00;"></label>
						</div>
						<div style="width:180px; height:25px; float:left;  margin-top:5px; margin-left:1px;">
								<input type="text" name="TRINPartNo"  id="TRINPartNo" onKeyPress="return validateProductCode(event)" value="<--?php echo $TRINPartNo; ?>" onChange="return codetrim(this)" maxlength="50" style="text-transform:uppercase;">
						</div-->
						<div style="width:100px; height:25px; float:left;  margin-top:5px; margin-left:3px;">
								<label>PDS Number</label><label style="color:#F00;">*</label>
						</div>
						<div style="width:180px; height:25px; float:left;  margin-top:5px; margin-left:1px;">
								 <select name="PDSNumber" id="PDSNumber" onchange="getpdsdetails();">
								 <option value=""><--Select--></option>
									<?
									$Pdsnumber = ($_POST['PDS_number']) ? $_POST['PDS_number'] : '';
									$Pdsnumberlist = mysql_query("SELECT DISTINCT PDS_number FROM purchase_order");
									while ($row_list = mysql_fetch_assoc($Pdsnumberlist)) {
									$selected = '';
									if ($row_list['PDS_number'] == $Pdsnumber) {
									$selected = ' selected ';
									}
									?>
								    <option value="<? echo $row_list['PDS_number']; ?>"<? echo $selected; ?>> <? echo $row_list['PDS_number']; ?> </option>
								    <?
                                    }
                                    ?> 
									</select>
						</div>
						
						
						
						
						
						
						</div>
						
						<!--Col2-->
						
						<div style="width:300px; height:auto; padding-bottom:8px; float:left; " class="cont">
						
						<div style="width:100px; height:25px; float:left;  margin-top:5px; margin-left:3px;">
								<label>Invoice Date</label><label style="color:#F00;">*</label>
						</div>
						<div style="width:180px; height:25px; float:left;  margin-top:5px; margin-left:1px;">
								<!--input type="text" name="PartName"  id="PartName" onKeyPress="return validateProductCode(event)" value="<!--?php echo $PartName; ?>" onChange="return codetrim(this)" maxlength="50" style="text-transform:uppercase;"-->
							<input type="text" name="SRP_FromDate" id="rp_frdate" title="Select date" value="<?php echo $_POST['SRP_FromDate']; ?>" readonly />
						</div>
						<!--div style="width:100px; height:25px; float:left;  margin-top:5px; margin-left:3px;">
								<label>Basic</label><label style="color:#F00;">*</label>
						</div>
						<div style="width:180px; height:25px; float:left;  margin-top:5px; margin-left:1px;">
								<input type="text" name="Basic"  id="Basic" onKeyPress="return validateProductCode(event)" value="<!--?php echo $Basic; ?>" onChange="return codetrim(this)" maxlength="50" style="text-transform:uppercase;">
						</div-->
						
						<div style="width:105px; height:25px; float:left;  margin-top:5px; margin-left:3px;">
                                  <label>Narration</label><label style="color:#F00;"></label>
                               </div>
                               
                               <div style="width:180px; height:65px;  float:left; margin-top:5px; margin-left:3px;">
                            	<textarea  rows="2" cols="20" name="Narration" id="Narration" onKeyPress="return validateProductDescription(event)" style="text-transform:uppercase;width:174px;"  onChange="return trim(this)"><?php echo $Narration; ?></textarea>
                               </div>
					
						</div>
						<!--Col3-->
						<div style="width:300px; height:auto; padding-bottom:8px; float:left; " class="cont">
						
						<div style="width:100px; height:25px; float:left;  margin-top:5px; margin-left:3px;">
								<label >ED(%)</label><label style="color:#F00;">*</label>
						</div>
						
						<div style="width:180px; height:25px; float:left;  margin-top:5px; margin-left:1px;">
								<input type="text" name="ED" value="12"  id="ED" onKeyPress="return validateProductCode(event)" value="<?php echo $ED; ?>" onChange="return codetrim(this)" maxlength="50" style="text-transform:uppercase;">
						</div>
						<!--div style="width:100px; height:25px; float:left;  margin-top:5px; margin-left:3px;">
								<label>Assessble</label><label style="color:#F00;">*</label>
						</div>
						<div style="width:180px; height:25px; float:left;  margin-top:5px; margin-left:3px;">
								<input type="text" name="Assessble"  id="Assessble" onKeyPress="return validateProductCode(event)" value="<!?php echo $Assessble; ?>" onChange="return codetrim(this)" maxlength="50" style="text-transform:uppercase;">
						</div-->
						
						</div>
						<!--Col4-->
						<div style="width:300px; height:auto; padding-bottom:8px; float:left; " class="cont">
						
						<div style="width:100px; height:25px; float:left;  margin-top:5px; margin-left:3px;">
								<label>VAT(%)</label><label style="color:#F00;">*</label>
						</div>
						<div style="width:180px; height:25px; float:left;  margin-top:5px; margin-left:1px;">
								<input type="text" name="VAT" value="5" id="VAT" onKeyPress="return validateProductCode(event)" value="<?php echo $VAT; ?>" onChange="return codetrim(this)" maxlength="50" style="text-transform:uppercase;">
						</div>
						
						<!--div style="width:100px; height:25px; float:left;  margin-top:5px; margin-left:3px;">
								<label>UOM</label><label style="color:#F00;"></label>
						</div>
						<div style="width:180px; height:25px; float:left;  margin-top:5px; margin-left:3px;">
								<input type="text" name="UOM"  id="UOM" onKeyPress="return validateProductCode(event)" value="<--?php echo $UOM; ?>" onChange="return codetrim(this)" maxlength="50" style="text-transform:uppercase;">
						</div-->
						
						
						</div>
                    
                    
                     <!-- col2 -->   
                           <div style="width:400px; height:auto; float:left; padding-bottom:5px; margin-left:2px;" class="cont">
                           
                               
                             <!--Row5 end-->                         
                               
                               	
                               
                               
                             <!--Row5 end--> 
                           
                             <script>// all scripts used to eliminate duplication in dropdown.
                                    
                                    // Set the present object
                                    var present = {};
                                    $('#SalesTypeid option').each(function(){
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
                                                            
                                    		
									// Set the present object
                                    var present = {};
                                    $('#warrantyapplicableid option').each(function(){
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
									
									// Set the present object
                                    var present = {};
                                    $('#Statusid option').each(function(){
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
                                
                           </div>
                           
                           <!--Row1 -->  
                         <!-- <div style="width:145px; height:30px; float:left;  margin-top:5px; margin-left:3px;">
                                  <label>Services Compensation</label>
                               </div>
                               <div style="width:145px; height:30px;  float:left; margin-top:5px; margin-left:3px;">
                                  <input type="text" name="ServiceCompensation" onKeyUp="numericFilter(this)" value="<!--?php /*?><!--?php echo $ServiceCompensation; ?><!--?php */?>"/>
                               </div> -->
 							<!--Row1 end--> 
                            
                            <!--Row2 -->  
                                   
                     <!-- col2 end--> 
					  <div style="width:1181px; height:300px; overflow:auto; float:left;  margin-top:8px; margin-left:5px;">
    <TABLE  id="dataTable"  width="350px;" border='1'>
  	
	
  
		<tr><td colspan="10" style="height: 0px;"></td></tr>
         </TABLE></div>
                                                                       
                    </div>
                  
                
				</div>
                          
                            
                   
                   
    
	
                <!-- main row 1 end-->
                
                  <!--Main row 2 start-->
                <div style="width:1200px; height:60px; float:left; margin-left:8px; margin-top:10px;">     
                   
					<div style="width:225px; height:50px; float:center; margin-top:-3px;" id="center1">
                    
                        <div style="width:100px; height:32px; float:left; margin-top:16px; margin-left:10px;" >
                        
                    <?php      if(!empty($_GET['edi']))
					{?>
						<input name="<?php if(($row['editrights'])=='Yes') echo 'Update'; else echo 'permiss'; ?>" type="submit" class="button" value="Update" id="addbutton">
					<? }
					else
					{?>
						<input name="<?php if(($row['addrights'])=='Yes') echo 'Save'; else echo 'permiss'; ?>" type="submit" class="button" id="addbutton" value="Save" >
				          <? } ?>
		              </div>
                           
                          <div style="width:80px; height:32px; float:left;margin-top:16px; margin-left:10px; ">
						  <input name="Cancel" type="submit" class="button" value="Reset">
		              </div>    
	              </div>                          

                               </div>	
                          <!--Row2 end-->
                         
          <!--  grid start here-->
             
        
               <?php include("../../paginationdesign.php") ?>

               <!--  grid end here-->
        
             <!-- form id start end-->      
       <br /><br />
       <input type="hidden" value="0" id="last_inc_count" />  
       <!--Third Block - Menu -Container -->
	   
	   
	   
	   <script>// all scripts used to eliminate duplication in dropdown.
			 
                                    // Set the present object
                                    var present = {};
                                    $('#ProductGroup option').each(function(){
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
									
									
    </form>
</div>
</div>
</div>
<!--Footer Block --><!--Footer Block - End-->

<div id="footer-wrap1">
  <?php include("../../footer.php") ?>
</div>
</center></body>
</html>
<?
}
?>
