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
	$scode = 'ProductCode';
	$sname = 'ProductDescription';
	$tname	= "productmaster";
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
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
$post['ProductCode'] = strtoupper(str_replace('&', 'and',$_POST['ProductCode']));
$post['ProductDescription'] = strtoupper(str_replace('&', 'and',$_POST['ProductDescription']));
$post['ProductGroupCode'] = $_POST['ProductGroup'];
$post['UOMCode'] = $_POST['UOM'];
$post['user_id'] = $_SESSION['username'];
$ProductCode=strtoupper(str_replace('&', 'and',$_POST['ProductCode']));
$ProductDescription=strtoupper(str_replace('&', 'and',$_POST['ProductDescription']));
$ProductGroup=$_POST['ProductGroup'];
$UOM=$_POST['UOM'];

	if(!empty($_POST['ProductCode'])&&!empty($_POST['UOM'])&&!empty($_POST['ProductDescription'])&&!empty($_POST['ProductGroup'])&& $_POST['ProductCode']!=$_POST['ProductDescription'])
	{ 
	
			
    $p1=strtoupper( preg_replace('/\s+/', '',$post['ProductCode']));
	$p2=strtoupper( preg_replace('/\s+/', '',$post['ProductDescription']));
	$cnduplicate=0;
	$repqry="SELECT REPLACE( `ProductCode` ,  ' ',  '' ) AS ProductCode, REPLACE(  `ProductDescription` ,  ' ',  '' ) AS ProductDescription FROM productmaster where ProductDescription = '".$p2."' or ProductDescription = '".$post['ProductDescription']."' or ProductCode = '".$p2."' or ProductCode = '".$post['ProductDescription']."' or ProductCode = '".$p1."' or ProductCode = '".$post['ProductCode']."' or ProductDescription = '".$p1."' or ProductDescription = '".$post['ProductCode']."'";
	$repres= mysql_query($repqry) or die (mysql_error());
	$cnduplicate=mysql_num_rows($repres);
		  
		
		if($cnduplicate>0 || ($ProductCode == $ProductDescription))
		{  
			echo '<script type="text/javascript"> alert("Duplicate entry!");</script>'; 
		}
		else
		{	
			$news->addNews($post,$tname);
			$trans_sp_qry="CALL sp_t_upload('".$stname."','productdetails','".$post['ProductCode']."','".$_POST['ProductGroup']."','INSERT');";
			mysql_query($trans_sp_qry) or die (mysql_error());
			echo '<script type="text/javascript">alert("Created Sucessfully!","productdetails.php");</script>';	
			
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
			echo '<script type="text/javascript">alert("Updated Sucessfully!","productdetails.php");</script>';	
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

	if($myrow1==0)	{ ?><script type="text/javascript">alert("No Data Found!!",'productdetails.php');</script><? }
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
	alert("Select data to delete!",'productdetails.php');
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
				alert("Deleted  Successfully!!",'productdetails.php');
				</script>
				<?
				
				}
				else
				{
				?>
				<script type="text/javascript">
				alert("You Can't delete already send to Distributor ",'productdetails.php');
				</script>
				<?	
				}
			}
			else
			{
			?>
			<script type="text/javascript">
			alert("you can't delete already used in other masters!",'productdetails.php');//document.location='pricelistmaster.php';
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
		alert("Deleted  Successfully!",'productdetails.php');
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
	var object = document.getElementById('ProductCode');
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
<div style="width:100%; height:auto; float:none;">
     <div style="width:980px; height:auto; float:none; margin-top:8px;" class="container">
                  
          <div style="width:950px; height:auto; float:left; margin-bottom:10px; margin-left:15px;" id="center">  
            <!-- form id start-->
             <form method="POST" action="<?php $_PHP_SELF ?>" name="form1" id="frm1">
            <div style="width:930px; height:auto; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;">
                    
						<div style="width:930px; height:25px; float:left;  margin-left:0px;" class="head">
						<p>Product Master</p>
						</div>
              <!-- main row 1 start-->     
                    <div style="width:925px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
                     <!-- col1 -->   
                           <div style="width:400px; height:auto; padding-bottom:5px; float:left; " class="cont">
                             <!--Row1 -->  
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Product Code</label><label style="color:#F00;">*</label>
                               </div>
                               <div style="width:200px; height:30px; float:left;  margin-top:5px; margin-left:3px;">
                               
                              <?php if(!empty($_GET['edi']))
							{?>
                            <input type="text" name="ProductCode"  style="border-style:hidden; background:#f5f3f1; text-transform:uppercase;" readonly="readonly" id="ProductCode" onKeyPress="return validateProductCode(event)"   value="<?php echo $ProductCode; ?>" onChange="return codetrim(this)">
                            <? } 
							else { ?>
                                   <input type="text" name="ProductCode"  id="ProductCode" onKeyPress="return validateProductCode(event)" value="<?php echo $ProductCode; ?>" onChange="return codetrim(this)" maxlength="50" style="text-transform:uppercase;">
                                   <?
							}
							?>
                              
                               </div>
 							<!--Row1 end--> 
                            
                            <!--Row2 -->  
                               <div style="width:145px; height:30px; float:left;  margin-top:5px; margin-left:3px;">
                                  <label>Product Description</label><label style="color:#F00;">*</label>
                               </div>
                               
                               <div style="width:200px; height:65px;  float:left; margin-top:5px; margin-left:3px;">
                            	<textarea  rows="4" cols="20" name="ProductDescription" id="ProductDescription" onKeyPress="return validateProductDescription(event)" style="text-transform:uppercase;width:174px;"  onChange="return trim(this)"><?php echo $ProductDescription; ?></textarea>
                               </div>
                             <!--Row2 end-->  
                            
                       <div style="width:145px; height:30px; float:left;  margin-top:5px; margin-left:3px;">
                                  <label>Product Group Code</label><label style="color:#F00;">*</label>
                               </div>
                               <div style="width:200px; height:30px;  float:left; margin-top:5px; margin-left:3px;">
                                
                               <select name="ProductGroup" id="ProductGroup" >
                                       <option value="<?php echo $ProductGroup1;?>"><? if(!empty($ProductGroup)){ echo $ProductGroup;}else{?> ----Select---- <? } ?></option>
                                     <?
                                                                              
                                        $ques = mysql_query("SELECT ProductCode,ProductGroup FROM productgroupmaster order by ProductGroup asc");
                                       Echo $ques; 
                                     while( $record = mysql_fetch_array($ques))
                                     { 
									 //if($ProductType!=$record['ProductGroup'] && $ProductGroup !=$record['ProductGroup'] )
									 if($ProductGroup !=$record['ProductGroup'] )
									  {       
                                      echo "<option value=\"".$record['ProductCode']."\">".$record['ProductGroup']."\n ";    
									  }
									 }
                                    ?>
                                          </select>
                               
                               
                               </div>  
                               
                             <!--Row3 end-->  
                             
                               
                           </div>                             
                     <!-- col1 end --> 
                     
                     <!-- col2 -->   
                           <div style="width:400px; height:auto; float:left; padding-bottom:5px; margin-left:2px;" class="cont">
                           
                            
                               
                                 	<div style="width:145px; height:30px; float:left;  margin-top:5px; margin-left:3px;">
                                  <label>UOM</label><label style="color:#F00;">*</label>
                               </div>
                               <div style="width:200px; height:30px;  float:left; margin-top:5px; margin-left:3px;">
                                   <select name="UOM" id="UOMid" >
                                       <option value="<?php echo $UOM?>"><? if(!empty($UOM)){ echo $UOM; }else{?> ----Select---- <? } ?></option>
                                     <?                                      
                                     $ques = mysql_query("SELECT productuomcode FROM productuom order by productuomcode asc");
                                     while( $record = mysql_fetch_array($ques))
                                     { 
									  if($UOM!=$record['productuomcode'])
									  {   
                                      echo "<option value=\"".$record['productuomcode']."\">".$record['productuomcode']."\n ";                      
									  }
									 }
                                    ?>
                                          </select>
                             
                               </div>
                               
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
                <div style="width:1000px; height:60px; float:left; margin-left:8px; margin-top:10px;">     
                   
					<div style="width:225px; height:50px; float:left;  margin-left:14px; margin-top:-3px;" id="center1">
                    
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
                                                   
		       
                         
               <div style="width:640px; height:50px; float:left;  margin-left:25px; margin-top:0px;" class="cont" id="center2">
                              <!--Row1 -->  
                               <div style="width:95px; height:30px; float:left; margin-left:3px; margin-top:16px;" >
                                <label>Product Code</label>
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
             
              <div style="width:900px; height:auto; padding-bottom:8px; margin-top:20px; float:left; margin-left:19px; overflow:auto;" class="grid">
                    
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
        <td style="font-weight:bold; width:auto; text-align:center;">Product Code/Product Description</td>
        <!-- <td style="font-weight:bold; width:auto; text-align:center;">Product Description</td> -->
        <!--  <td style="font-weight:bold; width:auto; text-align:center;">Pricelist Description</td>-->
        <!-- <td style="font-weight:bold; width:auto; text-align:center;">Product Group Code</td> -->
        <td style="font-weight:bold; width:auto; text-align:center;">UOM</td>
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
     <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center"><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<? echo $record['ProductCode']; ?>"  onchange="test();"></td>
       	<? 
   		}
    if(($row['editrights'])=='Yes') 
	  	{ 
	 ?>
     <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center"> <a style="color:#0360B2" name="edit" href="productdetails.php?edi=<?= $record['ProductCode'];?>" class="doSomething">Edit</a></td>
     <? 
		} 
	  ?>
    <td  bgcolor="#FFFFFF" style='text-align:left'>
        <?=$record['ProductCode']?>
    </td>
     <!-- <td  bgcolor="#FFFFFF"  align="left">
        <?=$record['ProductDescription']?>
    </td> -->
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
        <?=$record['UOMCode']?>
        
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
  <div style="width:366px; height:50px; float:right;  margin-right:15px; margin-top:0px;" class="cont" >
         					
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
				  </div>
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
