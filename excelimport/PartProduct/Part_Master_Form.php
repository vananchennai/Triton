<?php 
include '../../functions.php';
sec_session_start();
require_once '../../masterclass.php';
include("../../header.php");

if(login_check($mysqli) == false) {
header('Location:../../index.php');// Redirect to login page!
} else
{
	global $ttname,$result12,$ProductCode,$ProductDescription,$UOM ,$ProductType,$warrantyapplicable,$EnableSerialno,$ServiceCompensation,$IdentificationCode,$serialnodigits,$salestype,$proratalogic,$logic,$Status,$scode,$i,$j,$sname;
	$scode = 'PartNo';
	$sname = 'PartName';
	$tname	= "partmaster";
	require_once '../../searchfun.php';		
$stname="productmasterupload";
require_once '../../masterclass.php'; // Include The News Class
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
	alert("you are not allowed to do this action!",'Part_Master_Form.php');//document.location='pricelistmaster.php';	
	</script>
	<? }
	if(isset($_GET['permiss']))
	{
	?>
	<script type="text/javascript">
	alert("you are not allowed to do this action!",'Part_Master_Form.php');//document.location='pricelistmaster.php';	
	</script>
	<? }
	
if(isset($_POST['Save'])) // If the submit button was clicked
{
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
$post['PartNo'] = strtoupper(str_replace('&', 'and',$_POST['PartNumber']));
$post['PartName'] = strtoupper(str_replace('&', 'and',$_POST['PartName']));
$post['Customer'] = strtoupper(str_replace('&', 'and',$_POST['CustomerSelection']));
$post['userid'] = $_SESSION['username'];
$PartNumber=strtoupper(str_replace('&', 'and',$_POST['PartNumber']));
$CustomerSelection=strtoupper(str_replace('&', 'and',$_POST['CustomerSelection']));
$PartName = $_POST['PartName'];
$HMILPartNumber = $post['HIMLorToyotoPartNumber']= $_POST['HMILPartNumber'];
		$POMonth = $post['POMonth']=$_POST['POMonth'];
		$PONo = $post['PONo']=$_POST['PONo'];
		$ShopCode =$post['ShopCode']=$_POST['ShopCode'];
		$TariffNo = $post['TariffNo']=$_POST['TariffNo'];
		$Location= $post['Location']=$_POST['Location'];
		$GateNo = $post['GateNo']=$_POST['GateNo'];
		$ContainerType= $post['ContainerType']=$_POST['ContainerType'];
		$StuffingQty = $post['StuffingQty']=$_POST['StuffingQty'];
		$HSNNo						= $post['HSNNo']					= $_POST['HSNNo'];
		date_default_timezone_set ("Asia/Calcutta");
		$post['insertdate']= date("y/m/d : H:i:s", time());
		$post['updatedate']= date("y/m/d : H:i:s", time());
	if((((!empty($_POST['PartNumber'])&&!empty($_POST['CustomerSelection'])&&!empty($_POST['HMILPartNumber']) )&& ($_POST['CustomerSelection']='TOYOTA'))||
	
	((($_POST['PartNumber'])&&!empty($_POST['CustomerSelection'])&&!empty($_POST['HMILPartNumber']) &&!empty($_POST['POMonth'])&&!empty($_POST['PONo'])&&!empty($_POST['ShopCode'])&&!empty($_POST['TariffNo'])&&!empty($_POST['Location'])&&!empty($_POST['GateNo'])&&!empty($_POST['ContainerType']) &&!empty($_POST['StuffingQty'])) && ($_POST['CustomerSelection']='HMIL'))))
	{
	
    $p1=strtoupper( preg_replace('/\s+/', '',$_POST['PartNumber']));
	$p2=strtoupper( preg_replace('/\s+/', '',$post['PartName']));
	$p3=strtoupper( preg_replace('/\s+/', '',$post['CustomerSelection']));
	
	$cnduplicate=0;
	$repqry="SELECT REPLACE( `PartNo` ,  ' ',  '' ) AS PartNo, REPLACE(  `PartName` ,  ' ',  '' ) AS PartName FROM partmaster where PartNo= '".$p1."' or PartName = '".$p2."' ";//and CustomerSelection = '".$p3."'";
	$repres= mysql_query($repqry) or die (mysql_error());
	$cnduplicate=mysql_num_rows($repres);
		  
		
		if($cnduplicate>0 || ($PartNumber == $post['Partno']))
		{  
			echo '<script type="text/javascript"> alert("Duplicate entry!");</script>'; 
		}
		else
		{	
		// $HIMLorToyotoPartNumber = $post['HIMLorToyotoPartNumber']=$_POST['HMILPartNumber'];
		// $POMonth = $post['POMonth']=$_POST['POMonth'];
		// $PONo = $post['PONo']=$_POST['PONo'];
		// $ShopCode =$post['ShopCode']=$_POST['ShopCode'];
		// $TariffNo = $post['TariffNo']=$_POST['TariffNo'];
		// $Location= $post['Location']=$_POST['Location'];
		// $GateNo = $post['GateNo']=$_POST['GateNo'];
		// $ContainerType= $post['ContainerType']=$_POST['ContainerType'];
		// $StuffingQty = $post['StuffingQty']=$_POST['StuffingQty'];
		
			$news->addNews($post,$tname);
			echo '<script type="text/javascript">alert("Created Sucessfully!","Part_Master_Form.php");</script>';				
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


if(isset($_POST['Update'])) // If the submit button was clicked
{
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
$post['PartNo'] = strtoupper(str_replace('&', 'and',$_POST['PartNumber']));
$post['PartName'] = strtoupper(str_replace('&', 'and',$_POST['PartName']));
$post['Customer'] = strtoupper(str_replace('&', 'and',$_POST['CustomerSelection']));
$post['userid'] = $_SESSION['username'];
$PartNumber=strtoupper(str_replace('&', 'and',$_POST['PartNumber']));
$CustomerSelection=strtoupper(str_replace('&', 'and',$_POST['CustomerSelection']));
$PartName = $_POST['PartName'];
$HMILPartNumber = $post['HIMLorToyotoPartNumber']= $_POST['HMILPartNumber'];
		$POMonth = $post['POMonth']=$_POST['POMonth'];
		$PONo = $post['PONo']=$_POST['PONo'];
		$ShopCode =$post['ShopCode']=$_POST['ShopCode'];
		$TariffNo = $post['TariffNo']=$_POST['TariffNo'];
		$Location= $post['Location']=$_POST['Location'];
		$GateNo = $post['GateNo']=$_POST['GateNo'];
		$ContainerType= $post['ContainerType']=$_POST['ContainerType'];
		$StuffingQty = $post['StuffingQty']=$_POST['StuffingQty'];
		$HSNNo						= $post['HSNNo']					= $_POST['HSNNo'];
	date_default_timezone_set ("Asia/Calcutta");
	$post['updatedate']= date("y/m/d : H:i:s", time());

	if((((!empty($_POST['PartNumber'])&&!empty($_POST['CustomerSelection'])&&!empty($_POST['HMILPartNumber']) )&& ($_POST['CustomerSelection']='TOYOTA'))||
	
	((($_POST['PartNumber'])&&!empty($_POST['CustomerSelection'])&&!empty($_POST['HMILPartNumber']) &&!empty($_POST['POMonth'])&&!empty($_POST['PONo'])&&!empty($_POST['ShopCode'])&&!empty($_POST['TariffNo'])&&!empty($_POST['Location'])&&!empty($_POST['GateNo'])&&!empty($_POST['ContainerType']) &&!empty($_POST['StuffingQty'])) && ($_POST['CustomerSelection']='HMIL'))))
	{ 
		$wherecon = "PartNo ='".$_POST['PartNumber']."'";
		$news->editNews($post,$tname,$wherecon);
		echo '<script type="text/javascript">alert("Updated Sucessfully!","Part_Master_Form.php");</script>';	
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
$result=mysql_query("SELECT * FROM partmaster where PartNo ='".$prmaster."' ");
$myrow1 = mysql_num_rows($result);

	if($myrow1==0)	{ ?><script type="text/javascript">alert("No Data Found!!",'Part_Master_Form.php');</script><? }
	else
	{
	//PartNo, PartName, Customer, HIMLorToyotoPartNumber, POMonth, PONo, ShopCode, TariffNo, Location, GateNo, ContainerType, StuffingQty, userid, insertdate, updatedate;
	$myrow = mysql_fetch_array($result);
	$PartNumber					 = $myrow['PartNo'];
	$PartName					=$myrow['PartName'];
	$CustomerSelection					=$myrow['Customer'];
	$HMILPartNumber				=$myrow['HIMLorToyotoPartNumber'];
	$POMonth					 = $myrow['POMonth'];
	//$HIMLorToyotoPartNumber = $myrow['HIMLorToyotoPartNumber'];
	$PONo = $myrow['PONo'];
	$ShopCode = $myrow['ShopCode'];
	$TariffNo = $myrow['TariffNo'];
	$Location = $myrow['Location'];
	$GateNo = $myrow['GateNo'];
	$ContainerType = $myrow['ContainerType'];
	//$ContainerType = $myrow['ContainerType'];
	$StuffingQty = $myrow['StuffingQty'];
	$HSNNo						= $myrow['HSNNo'];
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
	alert("Select data to delete!",'Part_Master_Form.php');
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
			$wherecon= " PartNo = '".$checkbox[$i]."'";
			$mtabl='partmaster';
			$repqry1="select PartNo
			FROM partmaster
			WHERE PartNo='".$checkbox[$i]."'";
			$repres= mysql_query($repqry1) or die (mysql_error());
			$myrow1 = mysql_num_rows($repres);
        
			if($myrow1!=0)	
			{
				$news->deleteNews($tname,$wherecon);
				?>
				 <script type="text/javascript">
				 alert("Deleted  Successfully!!",'Part_Master_Form.php');
				 </script>
				 <?
			// $mkrow = mysql_query("SELECT Status FROM productmasterupload where Code='".$checkbox[$i]."'  and Status !='0'");
			// $val=mysql_num_rows($mkrow);
			
				// if($val==0)
				// {
				// $wherec= "Code='".$checkbox[$i]."'";
				// $news->deleteNews($stname,$wherec); 
				
				// $wherecon= "ProductCode ='".$checkbox[$i]."'";
		        // $news->deleteNews($mtabl,$wherecon);
				// $news->deleteNews($tname,$wherecon);
		
				
				
				// }
				// else
				// {
				// }
			}
			
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
header('Location:Part_Master_Form.php');
}

?>

<script type="text/javascript">


function popup(mylink)
{
if (! window.focus)return true;
var href;
if (typeof(mylink) == 'string')
   href=mylink;
else
   href=mylink.href;
window.open(href, '_blank');
return false;
}

function addRow(tableID) {
 
            var table = document.getElementById(tableID);
 
            var rowCount = table.rows.length;
			//alert(rowCount);
            var row = table.insertRow(rowCount-1);
			var delRow = parseInt(rowCount) - 2;
            var colCount = table.rows[1].cells.length;
            for(var i=0; i<colCount; i++) {
 
                var newcell = row.insertCell(i);
				var deleteBtn = '';
				if(i == (colCount-1)) {
					htmlVal = "<img src='del_img.jpg' style='cursor: pointer;' onclick='removeRow(this, \"add\");'/>";   
				} else {
					htmlVal = table.rows[1].cells[i].innerHTML;
				}
                newcell.innerHTML = htmlVal;
                //alert(newcell.childNodes);
				
				var controlType = newcell.childNodes[0].type;
                switch(controlType) {
                    case "text":
                            newcell.childNodes[0].value = "";
                            break;
                    
                    case "checkbox":
                            newcell.childNodes[0].checked = false;
                            break;
                    case "select-one":
                            newcell.childNodes[0].selectedIndex = 0;
                            break;
                }
            }	
			$('input#EnableSerialn:last').focus();
        }

		function removeRow(src, type){
			var del = true;
			if(type == 'edit') {
				var del = confirm('Are you want to remove selected row?');
			}
			if(del) {
				var sourceTableID = 'dataTable';       
				var oRow = src.parentElement.parentElement;  
				document.getElementById(sourceTableID).deleteRow(oRow.rowIndex);  
			}
		}
 
        function deleteRow(tableID, row) {
            try {
            var table = document.getElementById(tableID);
            var rowCount = table.rows.length;
			if(rowCount <= 3) {
				alert("Cannot delete all the rows.");
			}
			else
					{
						
						 if(document.getElementById('dataTable').rows[rowCount-2].getElementsByTagName("INPUT")[0].value!=""||document.getElementById('dataTable').rows[rowCount-2].getElementsByTagName("INPUT")[1].value!=""||document.getElementById('dataTable').rows[rowCount-2].getElementsByTagName("INPUT")[2].value!="")
						 {
							  var deleting= confirm("Do you really want to delete the row containing information??");
						    if (deleting== true)
							{
							   table.deleteRow(rowCount-2);
							}
							else
							{
								
								if(document.getElementById('dataTable').rows[rowCount-2].getElementsByTagName("INPUT")[0].value!="")
								{
									if(document.getElementById('dataTable').rows[rowCount-2].getElementsByTagName("INPUT")[1].value!="")
								{
									document.getElementById('dataTable').rows[rowCount-2].getElementsByTagName("INPUT")[2].focus();
								}
								else
								{
								document.getElementById('dataTable').rows[rowCount-2].getElementsByTagName("INPUT")[1].focus();
								}
								}
								else
								{
								document.getElementById('dataTable').rows[rowCount-2].getElementsByTagName("Select")[0].focus();
								}
								
							}
						 }
						 else
						 {
						  		table.deleteRow(rowCount-2);
						 }
					}
			
			
            }
			catch(e) {
                alert(e);
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

function validateProductDescription(key)
{
	var object = document.getElementById('ProductDescription');
	if (object.value.length <100 || key.keycode==8 || key.keycode==46)
{
	
return true;
}
else
{
	alert("Enter only 100 characters");
	toutfun(object);
	
return false;
}
}

function validateIdentificationCode(key)
{
	var object = document.getElementById('IdentificationCode');
	if (object.value.length <15 || key.keycode==8 || key.keycode==46)
{
	
return true;
}
else
{
	alert("Enter only 15 characters");
	toutfun(object);
	
return false;
}
}

function validateEnableSerialno(key)
{
	var object = document.getElementById('EnableSerialn');
	if (object.value.length <15 || key.keycode==8 || key.keycode==46)
{
	
return true;
}
else
{
	alert("Enter only 15 characters");
	toutfun(object);
	
return false;
}
}

function numericFilter(txb) {
   txb.value = txb.value.replace(/[^\0-9]/ig, "");
}

	  
	  // Set the present object
var present = {};
$('#SalesType dummyv').each(function(){
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
	document.form1.ProductDescription.focus();
}
function getFuncs1()
{
	//SetVis(); 
	document.form1.ProductCode.focus();
}
</script>
<title><?php echo $_SESSION['title']; ?>|| Product Master</title>
</head>
 <?php 
  if(empty($_SESSION['codesval']) && empty($_SESSION['namesval']))
{
 if(!empty($_GET['edi'])){?>
 
 <body class="default" onLoad="return getFuncs()">

<? }else{?>


<body class="default" onLoad="return getFuncs1()">

 <? } 
}else{?>
<body class="default" onLoad="document.form1.codes.focus()">

 <? } ?>
 <center>

 <?php include("../../menu.php") ?>




<!--Third Block - Container-->
<div style="width:100%; height:auto; float:none; padding-top:50px;">
     <div style="width:1200px; height:auto; float:none; margin-top:8px;" class="container">
                  
          <div style="width:1180px; height:auto; float:left; margin-bottom:10px; margin-left:15px;" id="center">  
            <!-- form id start-->
             <form method="POST" action="<?php $_PHP_SELF ?>" name="form1" id="frm1">
            <div style="width:1180px; height:auto; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;">
                    
						<div style="width:1180px; height:25px; float:left;  margin-left:0px;" class="head">
						<p>Part Master</p>
						</div>
              <!-- main row 1 start-->     
                    <div style="width:1180px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
							<!-- Column One -->   
				    <div style="width:350px; height:auto; padding-bottom:5px; float:left; " class="cont">
								<!--Row1 -->  
								<div style="width:120px; height:30px; float:left; margin-top:5px; margin-left:3px;">
								<label>Part Number</label><label style="color:#F00;">*</label>
								</div>
								<div style="width:150px; height:30px; float:left;  margin-top:5px; margin-left:3px;">
								<?php if(!empty($_GET['edi']))
								{?>
								<input type="text" name="PartNumber"  style="border-style:hidden; background:#f5f3f1; text-transform:uppercase;" readonly="readonly" id="PartNumber" onKeyPress="return validateProductCode(event)"   value="<?php echo $PartNumber; ?>" onChange="return codetrim(this)">
								<? } 
								else { ?>
								<input type="text" name="PartNumber"  id="PartNumber" onKeyPress="return validateProductCode(event)" value="<?php echo $PartNumber; ?>" onChange="return codetrim(this)" maxlength="50" style="text-transform:uppercase;">
								<?
								}
								?>
								</div>
								<div style="width:120px; height:30px; float:left;  margin-top:5px; margin-left:3px;">
								<label>Part Name</label><label style="color:#F00;">*</label>
								</div>
								<div style="width:150px; height:30px; float:left;  margin-top:5px; margin-left:3px;">
								<input type="text" name="PartName"  id="PartName" onKeyPress="return validateProductCode(event)" value="<?php echo $PartName; ?>" onChange="return codetrim(this)" maxlength="50" style="text-transform:uppercase;">
								</div>
								<div style="width:120px; height:30px; float:left; margin-top:5px; margin-left:3px;">
								<label>Customer</label><label style="color:#F00;">*</label>
								</div>
								<div style="width:150px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
								<select name="CustomerSelection">

								<option value="<?php echo $CustomerSelection;?>"><? if(!empty($CustomerSelection)){ echo $CustomerSelection;}else{?> ----Select---- <? } ?></option> 
								<option value="Toyota">Toyota</option>
								<option value="HMIL">HMIL</option>
								</select>
								</div>

								<div style="width:120px; height:30px; float:left; margin-top:5px; margin-left:3px;">
								<label>HMIL Part Number</label><label style="color:#F00;">*</label>
								</div>
								<div style="width:150px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
								<input type="text"  name="HMILPartNumber" value="<?php echo $HMILPartNumber;?>" maxlength="15" onKeyPress="return validatee1(event)"/>
								</div>
								<div class="hmil_elemnt" style="width:120px; height:30px; float:left; margin-top:5px; margin-left:3px;">
							<label>HSN No.</label><label style="color:#F00;">*</label>
							</div>
							<div class="hmil_elemnt" style="width:150px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
							<input type="text"  name="HSNNo" value="<?php echo $HSNNo;?>" maxlength="10" onKeyPress="return validatee1(event)"/>
							</div>
								
								
								</div>   
							
							
							
								<div style="width:350px; height:auto; padding-bottom:5px; float:left; " class="cont">
								
								<div style="width:120px; height:30px; float:left; margin-top:5px; margin-left:3px;">
								<label>P.O.No.</label><label style="color:#F00;"></label>
								</div>
								<div style="width:150px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
								<input type="text"  name="PONo" value="<?php echo $PONo;?>" maxlength="15" onKeyPress="return validatee1(event)"/>
								</div>
					
								
								<div style="width:120px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Shop Code</label><label style="color:#F00;"></label>
                               </div>
                              <div style="width:150px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                   <input type="text"  name="ShopCode" value="<?php echo $ShopCode;?>"  maxlength="15" onKeyPress="return validatee(event)"/>                             
                               </div>
                     
                               <div style="width:120px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Tariff No</label><label style="color:#F00;"></label>
                               </div>
                              <div style="width:150px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                   <input type="text"  name="TariffNo" value="<?php echo $TariffNo;?>"  maxlength="15" onKeyPress="return validatee(event)"/>
                                 
                               </div>
 							<!--Row1 end-->
                            
                            <!--Row1 -->  
                             <div style="width:120px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Location</label><label style="color:#F00;"></label>
                               </div>
                              <div style="width:150px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                 <input type="text"  name="Location" value="<?php echo $Location;?>" maxlength="15" onKeyPress="return validatee1(event)"/>
                               </div>
							   
							 
							   
							    
							   
							   
							    </div>
                               
						<div style="width:350px; height:auto; padding-bottom:5px; float:left; " class="cont">
							<div style="width:120px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Container Type</label><label style="color:#F00;"></label>
                               </div>
                              <div style="width:150px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                 <input type="text"  name="ContainerType" value="<?php echo $ContainerType;?>" maxlength="15" onKeyPress="return validatee1(event)"/>
                               </div>
							<div style="width:120px; height:30px; float:left; margin-top:5px; margin-left:3px;">
					  
                                  <label>Stuffing Qty</label><label style="color:#F00;"></label>
                               </div>
                              <div style="width:150px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                 <input type="text"  name="StuffingQty" value="<?php echo $StuffingQty;?>" maxlength="15" onKeyPress="return validatee1(event)"/>
                               </div>
							   <div style="width:120px; height:30px; float:left; margin-top:5px; margin-left:3px;">
								<label>P.O.Month</label><label style="color:#F00;"></label>
								</div>
								<div style="width:150px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
								<select name="POMonth" >
								<option value="<?php echo $POMonth;?>"><? if(!empty($POMonth)){ echo $POMonth;}else{?> ----Select---- <? } ?></option>
								<option value="Jan">Jan</option>
								<option value="Feb">Feb</option>
								<option value="March">March</option>
								<option value="April">April</option>
								<option value="May">May</option>
								<option value="Jun">Jun</option>
								<option value="Jul">Jul</option>
								<option value="Aug">Aug</option>
								<option value="Sept">Sept</option>
								<option value="Oct">Oct</option>
								<option value="Nov">Nov</option>
								<option value="Dec">Dec</option>
								</select>
								
								</div>
								
								  <div style="width:120px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Gate No.</label><label style="color:#F00;"></label>
                               </div>
                              <div style="width:150px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                 <input type="text"  name="GateNo" value="<?php echo $GateNo;?>" maxlength="15" onKeyPress="return validatee1(event)"/>
                               </div>
							 
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
                                  <input type="text" name="ServiceCompensation" onKeyUp="numericFilter(this)" value="<?php /*?><?php echo $ServiceCompensation; ?><?php */?>"/>
                               </div> -->
 							<!--Row1 end--> 
                            
                            <!--Row2 -->  
                                   
                     <!-- col2 end--> 
                                                                       
                    </div>
                  
                
				</div>
                          
                            
                   
                   
    
	
                <!-- main row 1 end-->
                
                  <!--Main row 2 start-->
                <div style="width:1120px; height:60px; float:left; margin-left:8px; margin-top:10px;">     
                   
					<div style="width:225px; height:50px; float:left;  margin-left:130px; margin-top:-3px;" id="center1">
                    
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
                                                   
		       
                         
               <div style="width:620px; height:50px; float:left;  margin-left:25px; margin-top:0px;" class="cont" id="center2">
                              <!--Row1 -->  
                               <div style="width:95px; height:30px; float:left; margin-left:3px; margin-top:16px;" >
                                <label>Part No</label>
                               </div>
                               <div style="width:130px; height:30px;  float:left; margin-left:3px; margin-top:16px;">
                                 <input type="text" name="codes" id="codes" onKeyPress="searchKeyPress(event);" value="<? echo $_SESSION['codesval']?>"/>
                               </div>
                             <!--Row1 end-->  
                             
                               <!--Row2 -->  
                               <div style="width:140px; height:30px; float:left; margin-left:3px; margin-top:16px;">
                                  <label>Product Description</label>
                               </div>
                               <div style="width:130px; height:30px;  float:left; margin-left:3px; margin-top:16px;" >
                                 <input type="text"   name="names" onKeyPress="searchKeyPress(event);" value="<? echo $_SESSION['namesval']?>"/>
                               </div>
                               <div style="width:83px; height:32px; float:left; margin-top:16px;">
                                <input id="Search" type="submit" name="Search" value="Search" class="button"/>
                               </div>  
                               </div>
                               </div>	
                          <!--Row2 end-->
                         
          <!--  grid start here-->
             
              <div style="width:1120px; height:auto; padding-bottom:8px; margin-top:20px; float:left; margin-left:19px; overflow:auto;" class="grid">
                    
                  <table id="datatable1" align="center" class="sortable" border="1" width="870px">
    <tr style="white-space:nowrap;"> 
 	 <?  

	 if(($row['deleterights'])=='Yes')
		{
	?>    
     <td class="sorttable_nosort" style="font-weight:bold; text-align:center" width="8px">
     <input type='checkbox' id="checkall" name='checkall' onclick='checkedAll(frm1);'></td>
   	<? 
   		}
    if(($row['editrights'])=='Yes') 
	  	{ 
	 ?>
     <td class="sorttable_nosort" style="font-weight:bold; text-align:center" width="12px">Action</td>
      <? 
		} 
	  ?>
        <td style="font-weight:bold; width:auto; text-align:center;">Part No</td>
		<td style="font-weight:bold; width:auto; text-align:center;">Part Name</td>
        <!-- <td style="font-weight:bold; width:auto; text-align:center;">Product Description</td> -->
        <!--  <td style="font-weight:bold; width:auto; text-align:center;">Pricelist Description</td>-->
        <!-- <td style="font-weight:bold; width:auto; text-align:center;">Product Group Code</td> -->
        <td style="font-weight:bold; width:auto; text-align:center;">Customer</td>
      <!--  <td style="font-weight:bold; width:auto; text-align:center;">View</td>-->
  
  </tr>
 <?php
      // This while will loop through all of the records as long as there is another record left. 
      while( $record = mysql_fetch_array($query))
    { // Basically as long as $record isn't false, we'll keep looping.
      // You'll see below here the short hand for echoing php strings.
      // <?=$record[key] - will display the value for that array.
     
    ?>
    
     <tr style="white-space:nowrap;">
      <?  
	 if(($row['deleterights'])=='Yes')
		{
	?> 
     <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center"><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<? echo $record['PartNo']; ?>"  onchange="test();"></td>
       	<? 
   		}
    if(($row['editrights'])=='Yes') 
	  	{ 
	 ?>
     <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center"> <a style="color:#0360B2" name="edit" href="Part_Master_Form.php?edi=<?= $record['PartNo'];?>" class="doSomething">Edit</a></td>
     <? 
		} 
	  ?>
    <td  bgcolor="#FFFFFF" style='text-align:left'>
        <?=$record['PartNo']?>
    </td>
      <td  bgcolor="#FFFFFF"  align="left">
        <?=$record['PartName']?>
    </td> 
  <!-- <td  bgcolor="#FFFFFF"> -->
   <?php /* $pgvv= mysql_query("select * from productgroumaster where ProductGroup='".$record['ProductGroup']."' ")  ;
		$record1vv= mysql_fetch_array($pgvv);
       echo $record1vv['ProductGroup']; */ ?> 
        <?//=$record['ProductGroupCode']?>
    <!-- </td> -->
     
    <td  bgcolor="#FFFFFF"  style='text-align:left'>
    <?php /*$pg1ff= mysql_query("select * from productuom where productuomcode='".$record['UOM']."' ")  ;
		$record11ff = mysql_fetch_array($pg1ff);
       echo $record11ff['productuom'];*/ ?>
        <?=$record['Customer']?>
        
    </td>
<!--     <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center"><a style="color:#0360B2" HREF="view" onClick="return popup('productgrid.php?<? /* echo 'ProductCode='; echo $record['ProductCode'];  */?>', 'notes')">View</a></td>-->
    </tr>  
  <?php
      }
  ?>
                 <?php
  if(isset($_POST['Search']))
{
if($myrow1==0)	
{?>
		<? echo '<tr ><td colspan="11" align="center" bgcolor="#FFFFFF" style="color:#F00"  >No Records Found</td></tr>'; ?>	
<? } }?>
</table>
 </div> 
               <?php include("../../paginationdesign.php") ?>
  <!--div style="width:366px; height:50px; float:right;  margin-right:15px; margin-top:0px;" class="cont" >
         					
                               <div style="width:70px; height:32px; float:left; margin-left:25px; margin-top:20px;">
                              Export As
             				
                               </div> 
<div style="width:83px; height:32px; float:left; margin-left:5px; margin-top:12px;">
                                <select name="Type">
                                   <option value="PDF">PDF</option>
                                    <option value="Excel">Excel</option>
                                     <option value="Document">Document</option>
            </select>
             				
          </div>
                               <div style="width:85px; height:32px; float:left; margin-top:18px;">
             					  <input type="submit" name="PDF" value="Export" class="button"/>
								 
                  </div >
				  <div style="width:95px; height:32px; float:right; margin-top:18px;">
				   <input type="submit" name="Excel" value="TallyExport" class="button"/>
                   </div>
				  </div-->
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
<div style="padding-bottom:50px">
<div id="footer-wrap1">
  <?php include("../../footer.php") ?>
</div>
</div>
</center>
    </body>
</html>
<?
}
?>
	