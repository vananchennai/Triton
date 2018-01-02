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
	// $scode = 'PartNo';
	// $sname = 'PartName';
	$tname	= "prefix_master";
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
	alert("you are not allowed to do this action!",'Prefix_Master_Form.php');//document.location='pricelistmaster.php';	
	</script>
	<? }
	if(isset($_GET['permiss']))
	{
	?>
	<script type="text/javascript">
	alert("you are not allowed to do this action!",'Prefix_Master_Form.php');//document.location='pricelistmaster.php';	
	</script>
	<? }
	
if(isset($_POST['Save'])) // If the submit button was clicked
{
	$post['Prefix_Name'] = $_POST['PrefixName'];
	$post['Financial_Year'] = $_POST['Financialyear'];
	if((($_POST['PrefixName'])&&!empty($_POST['Financialyear'])))
	
		{
	
			$news->addNews($post,$tname);
			echo '<script type="text/javascript">alert("Created Sucessfully!","Prefix_Master_Form.php");</script>';				
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
<div style="width:100%; height:auto; float:none;">
     <div style="width:1200px; height:auto; float:none; margin-top:8px;" class="container">
                  
          <div style="width:1180px; height:auto; float:left; margin-bottom:10px; margin-left:15px;" id="center">  
            <!-- form id start-->
             <form method="POST" action="<?php $_PHP_SELF ?>" name="form1" id="frm1">
            <div style="width:1180px; height:auto; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;">
                    
						<div style="width:1180px; height:25px; float:left;  margin-left:0px;" class="head">
						<p> Invoice Prefix Master</p>
						</div>
              <!-- main row 1 start-->     
                    <div style="width:1180px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
							<!-- Column One -->   
				    <div style="width:350px; height:auto; padding-bottom:5px; float:left; " class="cont">
								<!--Row1 -->  
								<div style="width:120px; height:30px; float:left; margin-top:5px; margin-left:3px;">
								<label>Prefix Name</label><label style="color:#F00;">*</label>
								</div>
								<div style="width:150px; height:30px; float:left;  margin-top:5px; margin-left:3px;">
								<?php if(!empty($_GET['edi']))
								{?>
								<input type="text" name="PrefixName"  style="border-style:hidden; background:#f5f3f1; text-transform:uppercase;" readonly="readonly" id="PartNumber" onKeyPress="return validateProductCode(event)"   value="<?php echo $PrefixName; ?>" onChange="return codetrim(this)">
								<? } 
								else { ?>
								<input type="text" name="PrefixName"  id="PartNumber" onKeyPress="return validateProductCode(event)" value="<?php echo $PrefixName; ?>" onChange="return codetrim(this)" maxlength="50" style="text-transform:uppercase;">
								<?
								}
								?>
								</div>
							
								
								</div>   
							
							
							
								<div style="width:350px; height:auto; padding-bottom:5px; float:left; " class="cont">
								
								<div style="width:120px; height:30px; float:left; margin-top:5px; margin-left:3px;">
								<label>Financial Year</label><label style="color:#F00;">*</label>
								</div>
								<div style="width:150px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
								<input type="text"  name="Financialyear" value="<?php echo $Financialyear;?>" maxlength="15" onKeyPress="return validatee1(event)"/>
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
                <div style="width:1120px; height:60px; float:left; margin-center:8px; margin-top:10px;">     
                   
					<div style="width:225px; height:50px; float:center;   margin-top:-3px;" id="center1">
                    
                        <div style="width:100px; height:32px; float:center; margin-top:16px; " >
                        
                    <?php      if(!empty($_GET['edi']))
					{?>
						<input name="<?php if(($row['editrights'])=='Yes') echo 'Update'; else echo 'permiss'; ?>" type="submit" class="button" value="Update" id="addbutton">
					<? }
					else
					{?>
						<input name="<?php if(($row['addrights'])=='Yes') echo 'Save'; else echo 'permiss'; ?>" type="submit" class="button" id="addbutton" value="Save" >
				          <? } ?>
		              </div>
                           
                           
	              </div>                          
                                                   
		       
                         
              
                      
                          <!--Row2 end-->
                         
          <!--  grid start here-->
             
              
	   
	   
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
	