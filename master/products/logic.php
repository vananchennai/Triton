<?php 
	include '../../functions.php';
	sec_session_start();
	require_once '../../masterclass.php';// Include database connection and functions here.
	include("../../header.php");
	if(login_check($mysqli) == false) {
		header('Location:../../index.php');// Redirect to login page!
	} else{
	global $result12,$category,$logiccode ,$minimum ,$min,$max,$discount,$effectivedate,$scode,$sname,$tname,$mtname,$i;
	$scode = 'logiccode';
	$sname = 'effectivedate';
	$tname	= "logicmaster";
	$_POST['names'] = $news->dateformat($_POST['names']);
	require_once '../../searchfun.php';
	require_once '../../paginationfunction.php';
	$mtname="proratalogic";
	$news = new News(); // Create a new News Object
	$newsRecordSet = $news->getNews($tname);
	$pagename = "proratalogic";
	$validuser = $_SESSION['username'];
	$selectvar =mysql_query( "select * from userrights where userid = '$validuser' and screen = '$pagename'");
	$row = mysql_fetch_array($selectvar);
		if (($row['viewrights'])== 'No')
		{
			header("location:/".$_SESSION['mainfolder']."/home/home/master.php"); 
		}
		if(isset($_POST['permiss'])){ // If the submit button was clicked
			?>
			<script type="text/javascript">
			alert("you are not allowed to do this action!",'logic.php');
			</script>
			<? }
		if(isset($_GET['permiss'])){
			?>
			<script type="text/javascript">
			alert("you are not allowed to do this action!",'logic.php');
			</script>
			<? }
			
//SAVE Function			
if(isset($_POST['Save']))
{ 
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
	$category = $_POST['category'];
	$logiccode = trim($_POST['logiccode']);
	$effectivedate =$_POST['effectivedate'];
	$minimum = trim($_POST['minimum']);
	$post['category'] = $_POST['category'];
	$post['logiccode'] = str_replace('&', 'and',$_POST['logiccode']);
	$post['effectivedate'] = $news->dateformat($_POST['effectivedate']);
	
	if(!empty($_POST['category']) && !empty($_POST['logiccode']) && !empty($_POST['effectivedate']))
	{
		$result="SELECT * FROM logicmaster where logiccode ='".$post['logiccode']."' and effectivedate ='".$post['effectivedate']."'";
		$sql1 = mysql_query($result) or die (mysql_error());
		$myrow1 = mysql_num_rows($sql1);//mysql_fetch_array($retval);
		if($myrow1>0)
		{
			?><script type="text/javascript">alert("Duplicate entry!");</script><? 
		} 
		else
		{   
		if($category =='Tubular Batteries')
		{
			if(!empty($_POST['logiccode'])&&!empty($_POST['effectivedate']))
			{
			$post['category'] = $_POST['category'];
			$post['logiccode'] = str_replace('&', 'and',$_POST['logiccode']);
			$post['effectivedate'] = $news->dateformat($_POST['effectivedate']);
			
			$min = $_POST['min'];
			$max = $_POST['max'];
			$discount = $_POST['discount'];
			$numcnt=count($_POST['min']);
				for($i=0;$i<$numcnt;$i++)
				{
					$spost['category'] = $_POST['category'];
					$spost['logiccode'] = str_replace('&', 'and',$_POST['logiccode']);
					$spost['effectivedate'] = $news->dateformat($_POST['effectivedate']);
					$spost['min'] = trim($min[$i]);
					$spost['max'] = trim($max[$i]);
					$spost['discount'] = trim($discount[$i]);
					if($i==0)
					{
						
						 $post['user_id'] = $_SESSION['username'];
						 date_default_timezone_set ("Asia/Calcutta");
						 $post['m_date']= date("y/m/d : H:i:s", time());
						 
						$news->addNews($post,$tname);
					}
					$news->addNews($spost,$mtname);
				}
				?><script type="text/javascript">alert("Saved Sucessfully!",'logic.php');</script><?
			}
			else
			{
			?>
			<script type="text/javascript">
			alert("Enter Mandatory Fields!");  
			</script>
			<? 
			}
	 }
	 else if($category =='4W Batteries' || $category =='2W Batteries')
	 {
		if(!empty($_POST['logiccode'])&&!empty($_POST['minimum'])&&!empty($_POST['effectivedate']))
		{
			$post['category'] = $_POST['category'];
			$post['logiccode'] = str_replace('&', 'and',$_POST['logiccode']);
			$post['effectivedate'] = $news->dateformat($_POST['effectivedate']);
			$post['minimum'] = $_POST['minimum'];
			
						 $post['user_id'] = $_SESSION['username'];
						 date_default_timezone_set ("Asia/Calcutta");
						 $post['m_date']= date("y/m/d : H:i:s", time());
						 
			$news->addNews($post,$tname);
			
			?><script type="text/javascript">alert("Created Sucessfully!",'logic.php');</script><?
		} 
		else 
		{
		?><script type="text/javascript"> alert("Enter Mandatory Fields!");  </script><?
		}
	 }
	}
	}
	else 
	{
	?><script type="text/javascript">alert("Select the category!");</script><? 
	}
}

//UPDATE Function			
if(isset($_POST['Update'])) 
{
		unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
	$category = $_POST['category'];
	$logiccode = trim($_POST['logiccode']);
	$effectivedate =$_POST['effectivedate'];
	$minimum = trim($_POST['minimum']);
	
						 $post['user_id'] = $_SESSION['username'];
						 date_default_timezone_set ("Asia/Calcutta");
						 $post['m_date']= date("y/m/d : H:i:s", time());
						 
	if($category =='Tubular Batteries')
	{
		if(!empty($_POST['logiccode'])&&!empty($_POST['effectivedate'])&&!empty($_POST['min'])&&!empty($_POST['max'])&&!empty($_POST['discount']))
		{
		$post['category'] = $_POST['category'];
		$post['logiccode'] = trim($_POST['logiccode']);
		$post['effectivedate'] = $news->dateformat($_POST['effectivedate']);
		
		
		$min = $_POST['min'];
		$max = $_POST['max'];
		$discount = $_POST['discount'];
		$numcnt=count($_POST['min']);
			for($i=0;$i<$numcnt;$i++)
			{
				$spost['category'] = $_POST['category'];
				$spost['logiccode'] = trim($_POST['logiccode']);
				$spost['effectivedate'] = $news->dateformat($_POST['effectivedate']);
				$spost['min'] = trim($min[$i]);
				$spost['max'] = trim($max[$i]);
				$spost['discount'] = trim($discount[$i]);
				if($i==0)
				{
				$wherecon=" logiccode ='".$post['logiccode']."' AND effectivedate ='".$post['effectivedate']."'";
				$news->deleteNews($mtname,$wherecon);
				$news->editNews($post,$tname,$wherecon);
				}
				$news->addNews($spost,$mtname);
			}
			?><script type="text/javascript">alert("Updated Sucessfully!",'logic.php');</script><?
		}
		else
		{
		?>
		<script type="text/javascript">
		alert("Enter Mandatory Fields!"); </script>
		<? 
		}
 }
	else if($category =='4W Batteries' || $category =='2W Batteries')
	{
	if(!empty($_POST['logiccode'])&&!empty($_POST['minimum'])&&!empty($_POST['effectivedate']))
	{
	$post['category'] = $_POST['category'];
	$post['logiccode'] = $_POST['logiccode'];
	$post['effectivedate'] = $news->dateformat($_POST['effectivedate']);
	$post['minimum'] = $_POST['minimum'];
	
	$wherecon=" logiccode ='".$_POST['logiccode']."' AND effectivedate ='".$post['effectivedate']."'";
	$news->editNews($post,$tname,$wherecon);
	
	?><script type="text/javascript">alert("Updated Sucessfully!",'logic.php');</script><?
	} 
	else 
	{
	?><script type="text/javascript"> alert("Enter Mandatory Fields!");</script><?
	}
	}
		
}
	
	
	
	
  
/// EDIT LINK FUNCTION 
if(!empty($_GET['logiccode']))
{
		unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
$prmaster	= $_GET['logiccode'];
$effdat1		= $_GET['effectivedate'];
$result=mysql_query("SELECT * FROM logicmaster where logiccode ='".$prmaster."' AND effectivedate ='".$effdat1."'");
$myrow1 = mysql_num_rows($result);//mysql_fetch_array($retval);
	if($myrow1==0)	
	{
	  ?>
	  <script type="text/javascript">
	  alert("No Data Found!");//document.location='logic.php';
	  </script>
	  <?
	}
	else
  	{
	$myrow = mysql_fetch_array($result);
	$category = $myrow['category'];
	$logiccode = $myrow['logiccode'];
	$effectivedate = date("d/m/Y",strtotime($myrow['effectivedate']));
	$minimum = $myrow['minimum'];
	
	$result1="SELECT * FROM proratalogic where logiccode ='".$prmaster."' AND effectivedate ='".$effdat1."'";
	$result12=mysql_query($result1);
  	}
$prmaster = NULL;
}        


// Delete Function
if(isset($_POST['Delete']))
{
		unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
$checkbox = $_POST['checkbox']; //from name="checkbox[]"
$countCheck = count($_POST['checkbox']);
   for($i=0;$i<$countCheck;$i++)
   {
	   $prodidd = $checkbox[$i];
		  $newvar=explode("~",$prodidd);
		  $var1=$newvar[0];
		  $var2=$newvar[1];
	$repqry1="SELECT logiccode from logicmaster where logiccode in(select proratalogic from productmaster where proratalogic='".$var1."') ";
	$repres= mysql_query($repqry1) or die (mysql_error());
	$myrow1 = mysql_num_rows($repres);//mysql_fetch_array($retval);
		if($myrow1==0)	
		{
			$wherecon= "logiccode ='".$var1."' AND effectivedate ='".$var2."'";
			$news->deleteNews($tname,$wherecon);
			$news->deleteNews($mtname,$wherecon);
			?>
			<script type="text/javascript">
			alert("Deleted  Successfully!",'logic.php');
			</script>
			<?	
		
		}
		else
		{
		
		?>
		<script type="text/javascript">
		alert("you can't delete already used in other masters!",'logic.php');//document.location='logic.php';
		</script>
		<?
		}
   }
}

//Export Function

$_SESSION['type']=NULL;
$productmaster='select * from proratalogic_view';

if(isset($_POST['PDF']))
{

$select=$_POST['Type'];
$test1 = $news->dateformat($_POST['names']);
		  if(!empty($_POST['codes'])&&!empty($_POST['names']))
		  {
		  $condition="SELECT * FROM proratalogic_view WHERE logiccode like'%".$_POST['codes']."%' AND effectivedate= '".$test1."'";
		  }
		  else if(!empty($_POST['codes'])&&empty($_POST['names']))
		  {
		  $condition="SELECT * FROM proratalogic_view WHERE logiccode like'%".$_POST['codes']."%'";
		  }
		  else if(!empty($_POST['names'])&&empty($_POST['codes']))
		  {
		  $condition="SELECT * FROM proratalogic_view WHERE effectivedate ='".$test1."'";
		  }
		  else
		  {
		  $condition="SELECT * FROM proratalogic_view WHERE 1";
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
$effdate= date('d/m/Y', strtotime($myrecord['2']));
if($effdate=='01/01/1970')
	{
		$effdate='00/00/0000';
	}
$stringData =$myrecord[0]."\t ;".$myrecord[1]."\t ;".$effdate."\t ;".$myrecord[3]."\t;".$myrecord[4]."\t ;".$myrecord[5]."\t;".$myrecord[6]."\t;\n";
fwrite($fh, $stringData);
}
//	
fclose($fh);
header('Location:Exportlogic.php');
}
elseif($select=='Excel')
{
$_SESSION['type']='Excel';
$_SESSION['query']=$productmaster;

header('Location:Exportlogic.php');
}
elseif($select=='Document')
{
$_SESSION['type']='Document';
$_SESSION['query']=$productmaster;
header('Location:Exportlogic.php');
}

}

// Reset Function
if(isset($_POST['Cancel']))
{
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
  header('Location:logic.php');
}

?>

<script type="text/javascript">
$(function() {
	
$("#start").datepicker({ changeYear:true,minDate:'0',yearRange: '2006:3050',dateFormat:'dd/mm/yy',defaultDate: null});
$("#searchdate").datepicker({ changeYear:true, yearRange: '2006:3050',dateFormat:'dd/mm/yy',defaultDate: null});


});



//      For New Window(old code)  //
/*function popup(mylink, windowname)
{
if (! window.focus)return true;
var href;
if (typeof(mylink) == 'string')
   href=mylink;
else
   href=mylink.href;
window.open(href, windowname, 'width=600,height=255,scrollbars=yes, resizable=0,fullscreen=no,location=no,menubar=no');
return false;
}*/

//    End here  //



//      For New tab  //
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
 // End here  //


 function addRow(tableID) {
 			
            var table = document.getElementById(tableID);
			//document.getElementById(min).innerHTML=0;
           
		/*var val1 = table.rows[0].cells[0].value;
		alert(val1);*/
			/*var val1 = table.rows[3].cells[0].value;
			alert(val1);
			if(val1 =='')
			{
			alert("hi");
			}
			else
			{
				alert("Else loop")
			}*/
			
			var rowCount = table.rows.length;
			//not allowing if empty space
			//start here
			
			var ttlrow = table.rows.length-3;
			//alert(ttlrow);
			 //document.form1.max.focus();
			var maxat=table.rows[ttlrow+1].getElementsByTagName("input")[0].value;
			
			var mainat=table.rows[ttlrow+1].getElementsByTagName("input")[1].value;
			var dis=table.rows[ttlrow+1].getElementsByTagName("input")[2].value;
			//the loop starts here
			if(maxat!="" && mainat!="" && dis!="")
			{
				
			
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
				
			//the addition of one value starts here
			var ttlrw = table.rows.length-3;
			for(var t=0;t<ttlrw;t++)
			{
				var intialval=table.rows[t+1].getElementsByTagName("input")[1].value;
				if(intialval!="")
				{
				var a=parseInt(intialval, 10) ;
				a++;
				table.rows[t+2].getElementsByTagName("input")[0].value=a;
				}
			}
			
			//the addition of one value ends here
			$('#'+tableID+' input#max:last').focus();
			//not allowing if empty space
			//loop ends here
			}
		 }
		
		function wmaxval()
		{
			var table = document.getElementById('dataTable');
			var rowCount = table.rows.length;
			var ttlrw = table.rows.length-3;
			for(var t=0;t<ttlrw;t++)
			{
				var intialval=table.rows[t+2].getElementsByTagName("input")[0].value;
				var secval=table.rows[t+2].getElementsByTagName("input")[1].value;
				if(intialval>=secval)
				{
				var a=parseInt(intialval, 10) ;
				a++;
				table.rows[t+2].getElementsByTagName("input")[1].value=a;
				}
			}
			for(var t=0;t<ttlrw;t++)
			{
				var intialval=table.rows[t+1].getElementsByTagName("input")[1].value;
				if(intialval!="")
				{
				var a=parseInt(intialval, 10) ;
				a++;
				table.rows[t+2].getElementsByTagName("input")[0].value=a;
				var firstvalval=table.rows[t+2].getElementsByTagName("input")[0].value;
				var secval=table.rows[t+2].getElementsByTagName("input")[1].value;
				if(a>=secval)
				{
				table.rows[t+2].getElementsByTagName("input")[1].value=a+1;
				}
				}
			}
			//table.rows[t+2].getElementsByTagName("input")[1].value=a;
		}

		function removeRow(src, type)
		{
			var del = true;
			if(type == 'edit') 
			{
				var del = confirm('Are you want to remove selected row?');
			}
			if(del)
			 {
				var sourceTableID = 'dataTable';       
				var oRow = src.parentElement.parentElement;  
				document.getElementById(sourceTableID).deleteRow(oRow.rowIndex);  
			}
			wmaxval();
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



function SetVis()
{
	try
   {
var a = document.getElementById('category').value
			

if(a =="Tubular Batteries")
{
var div1 = document.getElementById('div_txt1'); 
var div2 = document.getElementById('div_txt2'); 
div2.style.visibility = 'visible';
div1.style.visibility = 'hidden';
//document.form1.max.focus();

}
else if(a =="4W Batteries"||a =="2W Batteries" || a=="")
{
var div1 = document.getElementById('div_txt1'); 
var div2 = document.getElementById('div_txt2'); 
div1.style.visibility = 'visible';
div2.style.visibility = 'hidden';
}
else 
{
var div1 = document.getElementById('div_txt1'); 
var div2 = document.getElementById('div_txt2'); 
div1.style.visibility = 'visible';
div2.style.visibility = 'visible';
}
}
catch(Exception)
{alert("Error");}
}


function validate(key)
{
	
var object = document.getElementById('logiccode');

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

function getFuncs()
{
	SetVis(); 
	
	//document.form1.effectivedate.focus();
}

function getFuncs1()
{
	SetVis(); 
	//addRow();
	document.form1.category.focus();
	//document.form1.max.focus();
	//addRow();
	
}
function myfuc()
{
	document.form1.max.focus();
}

</script> 
<title><?php echo $_SESSION['title']; ?>|| Logic Master</title>
</head>
<?php 
 if(empty($_SESSION['codesval']) && empty($_SESSION['namesval']))
{
if(!empty($_GET['logiccode'])) {?>
 
 <body class="default" onLoad="return getFuncs()">

<? }else{?>


<body class="default" onLoad="return getFuncs1()">

 <? } 
}else{?>
<body class="default" onLoad="document.form1.codes.focus()">

 <? } ?><center>

<?php include("../../menu.php")?>

<!--Third Block - Container-->
<div style="width:100%; height:auto; float:none;">
   <div style="width:980px; height:auto; float:none; margin-top:8px;" class="container">
				
		<div style="width:950px; height:auto; float:left; margin-bottom:10px; margin-left:15px;" id="center" >  
		  <!-- form id start-->
		  <form method="POST" action="<?php $_PHP_SELF ?>" name="form1" id="frm1">
		  <div style="width:930px; height:auto;   min-height: 250px; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;">
				  
					  <div style="width:930px; height:25px; float:left;  margin-left:0px;" class="head">
					  <p>Pro-rata Logic</p>
					  </div>
			<!-- main row 1 start-->       
				  <div style="width:945px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
				   <!-- col1 -->   
						 <div style="width:350px; height:175px; padding-bottom:5px; float:left; " class="cont">
						   <!--Row1 -->  
						   
							  <div style="width:105px; height:30px; float:left;  margin-top:5px; margin-left:3px;" >
								<label>Category </label><label style="color:#F00;">*</label>
							 </div>
							  <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px; " >
								 <?php if(!empty($_GET['logiccode']))
						  {?>
						  <input type="text" name="category" id="category" style="border-style:hidden; background:#f5f3f1;" readonly="readonly" value="<?=$category?>" />
							  <? } 
						  else { ?>
							
								  <select name="category" id="category"  onchange="SetVis() ">
								  <option value="<?php echo $category;?>">
								  <? if(!empty($category)){ echo $category;}
								  else{?> ----Select---- <? } ?></option>
                                  <option value="2W Batteries">2W Batteries</option>
                                  <option value="4W Batteries">4W Batteries</option>  
								  <option value="Tubular Batteries">Tubular Batteries</option>                                
								   
                                                                    
																   
								 </select>
										
										<?php
						  }
						  ?>
							
							 </div>
						    <!--Row1 end--> 
						  
						  <!--Row2 --> 
							 <div style="width:105px; height:30px; float:left; margin-top:5px; margin-left:3px;">
								<label>Logic Code</label><label style="color:#F00;">*</label>
							 </div> 
							<div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
							 <?php if(!empty($_GET['logiccode'])) {?>
						  	 <input type="text" name="logiccode" id="logiccode" style="border-style:hidden; background:#f5f3f1;" readonly="readonly" value="<? echo $logiccode?>" />
							 <? }  else { ?>
							<input type="text" name="logiccode" id="logiccode" value="<? echo $logiccode?>" onKeyPress="return validate(event)" onChange="return codetrim(this)" />
							<?php }?>
							 </div>
						 <!--Row2 end-->   
                          
							<!--Row3 --> 
						   <div style="width:105px; height:30px; float:left; margin-top:5px; margin-left:3px;">
								<label>Effective Date</label><label style="color:#F00;">*</label>
							 </div> 
								  <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                   <?php if(!empty($_GET['logiccode'])) {?>
						  	 <input type="text" name="effectivedate" onFocus="max.focus();"  readonly="readonly" value="<?php echo $effectivedate;?>" style="border-style:hidden; background:#f5f3f1;" />
							 <? }  else { ?>
							<input type="text" name="effectivedate" readonly="readonly" onChange="myfuc();" value="<?php echo $effectivedate;?>" id="start"  onkeypress="start" />
							<?php }?>
			
		</div> 
        		 <!--Row3 end-->   
                          
							<!--Row4 --> 
							 <div id="div_txt1">
							 <div style="width:105px; height:30px; float:left;  margin-top:5px; margin-left:3px;">
								<label>Minimum %</label><label style="color:#F00;">*</label>
							 </div>
							  <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
								<input type="text" name="minimum" onChange="return trim(this)" onKeyUp="this.value = minmax(this.value, 1, 100)" value="<?php echo $minimum;?>" />
							 </div>
							 </div>
						 
						  <!--Row4 end--> 
								 </div>
    <!-- col1 end --> 
				   
				   <!-- col2 -->   
						 <div style="width:500px; height:auto; padding-bottom:5px; float:left; " id="div_txt2">
                          <div style="width:500px; height:200px; overflow:auto; float:left;  margin-top:8px; margin-left:5px;">
							<TABLE  id="dataTable"  width="400" border='1'>

    <tr>   
   <td style="width:70px; font-weight:bold;" align="center">Warranty Months Minimum<label style="color:#F00;">*</label></td>
    <td style="width:70px; font-weight:bold;" align="center">Warranty Months Maximum<label style="color:#F00;">*</label></td>
     <td style="width:70px; font-weight:bold;" align="center">Discount%<label style="color:#F00;">*</label></td>
	<td style="width:20px; font-weight:bold;" align="center">&nbsp;</td>
    </tr>
    <?
	if($result12!="")
	{
	while($myrow1 = mysql_fetch_array($result12))
			{
			
	     $min= $myrow1['min'];
		 
		 $max= $myrow1['max'];
		 $discount= $myrow1['discount'];
		 $i++;
	?>
        <TR>
            <TD style="width:70px;"><INPUT  style="width:120px; border-style:hidden; background:#f5f3f1;" readonly="readonly" type="text" name="min[]" id="minm" onChange="return trim(this)" onKeyUp="this.value = minmax(this.value, 0, 100)"  value="<?php echo  $min; ?>"  /></TD>
            <TD style="width:70px;"><INPUT  style="width:120px;" type="text" name="max[]" id="max" onBlur="wmaxval()" onChange="return trim(this)" onKeyUp="this.value = minmax(this.value, 1, 100)" value="<?php echo $max; ?>" /></TD>
            <TD style="width:70px;"><INPUT style="width:120px;" type="text" name="discount[]" onChange="return trim(this)" onKeyUp="this.value = minmax(this.value, 0, 100)" id="discount" value="<?php echo $discount; ?>" class="table_last_field" /></TD>
			
			<TD style="width:20x;" class="remove_btn">
			<?php if($i>1) { ?> <img src="del_img.jpg" style='cursor: pointer;' onclick='removeRow(this, "edit");'/>
			<?php } ?>
			</TD>
			
        </TR>
        <?
				}
		}
		else
		{
		?>
        <TR>
         	<TD style="width:70px;"><INPUT  style="width:120px; border-style:hidden; background:#f5f3f1; " readonly="readonly" type="text" name="min[]" onChange="return trim(this)" onKeyUp="this.value = minmax(this.value, 0, 100)" id="minm" value="<?php echo 0;?>" /></TD>
            <TD style="width:70px;"><INPUT  style="width:120px;" type="text" name="max[]" onBlur="wmaxval()" onChange="return trim(this)" onKeyUp="this.value = minmax(this.value, 1, 100)" id="max" /></TD>
            <TD style="width:70px;"><INPUT style="width:120px;" type="text" name="discount[]" onChange="return trim(this)" onKeyUp="this.value = minmax(this.value, 0, 100)" id="discount" class="table_last_field" /></TD>
			<TD style="width:20px;" class="remove_btn">&nbsp;</TD>
        </TR>
        <?
		}
		?>
		<tr><td colspan="7" style="height: 0px;"></td></tr>
    </TABLE>	 
						</div>
                        </div>
				   <!-- col2 end--> 
				   
				   <!-- col3 -->   
							  
				   <!-- col3 --> 
															
				  </div>
				
			  
			  </div>
			  <!-- main row 1 end-->
			  
			  
			  <!--Main row 2 start-->
			  <div style="width:1000px; height:60px; float:left; margin-left:8px; margin-top:-5px;">
						   
				<div style="width:235px; height:50px; float:left;  margin-left:14px; margin-top:0px;" id="center1">
						   
                          <div style="width:100px; height:32px; float:left; margin-top:16px; margin-left:10px;" >
                    <?php      if(!empty($_GET['logiccode']))
					{?>
						<input name="<?php if(($row['editrights'])=='Yes') echo 'Update'; else echo 'permiss'; ?>" type="submit" class="button" value="Update" id="addbutton">
					<? }
					else
					{?>
						<input name="<?php if(($row['addrights'])=='Yes') echo 'Save'; else echo 'permiss'; ?>" type="submit" class="button" id="addbutton" value="Save" >
				          <? } ?>
				           </div>
                          
                           <div style="width:100px; height:32px; float:left;margin-top:16px; margin-left:10px;">
						  <input name="Cancel" type="submit" class="button" value="Reset">
				           </div>                         
												 
				   </div>	
					   
						<div style="width:640px; height:50px; float:left;  margin-left:25px; margin-top:0px;" class="cont" id="center2">
							<!--Row1 -->  
							 <div style="width:80px; height:30px; float:left; margin-left:10px; margin-top:16px;" >
							  <label>Logic Code</label>
							 </div>
							 <div style="width:145px; height:30px;  float:left; margin-left:10px; margin-top:16px;">
							   <input type="text" name="codes" id="codes" onKeyPress="searchKeyPress(event);" value="<? echo $_SESSION['codesval'] ?>"/>
							 </div>
						   <!--Row1 end-->  
						   
							 <!--Row2 -->  
							 <div style="width:80px; height:30px; float:left; margin-left:10px; margin-top:9px;">
								<label>Effective Date</label>
							 </div>
							 <div style="width:145px; height:30px;  float:left; margin-left:10px; margin-top:16px;" >
							   <input type="text"  id="searchdate" name="names" onKeyPress="searchKeyPress(event);" value="<? 
							   $dummydate=date('d/m/Y', strtotime($_SESSION['namesval']));
							    if($dummydate=='01/01/1970'){echo $dummydate='';}else{echo $dummydate;}?>"/>
							 </div>
						   <!--Row2 end-->
						   
						   <div style="width:83px; height:32px; float:left; margin-left:10px; margin-top:16px;">
							  <input type="submit" name="Search" id="Search" value="Search" class="button"/>
							 </div>  
						</div> 
			  </div>
				
			  <!--Main row 2 end-->
				  <!--  grid start here-->
  <div style="width:930px; height:auto; overflow:auto; padding-bottom:8px; margin-top:20px; float:left; margin-left:10px;" class="grid">
				 
				<table id="datatable1" align="center" class="sortable" border="1" width="900px">
  <tr > 
     <?  if(($row['deleterights'])=='Yes')
	 {
	?> 
<td class="sorttable_nosort" style=" font-weight:bold; text-align:center">
<input type='checkbox' id="checkall" name='checkall' onclick='checkedAll(frm1);'></td>
<? 
   }
    if(($row['editrights'])=='Yes') 
	  { 
	 ?>
<td class="sorttable_nosort" width="10" style=" font-weight:bold; text-align:center">Action</td>
<? 
	  } 
	  ?>
<td style=" font-weight:bold;">Category </td>
<td style=" font-weight:bold;">Logic Code</td>
<td style=" font-weight:bold;">Effective Date</td>
<td style=" font-weight:bold;">Minimum</td>
<td style=" font-weight:bold; text-align:center">View</td>
</tr>
<?php
	// This while will loop through all of the records as long as there is another record left. 
	while( $record = mysql_fetch_array($query))
  { // Basically as long as $record isn't false, we'll keep looping.
	// You'll see below here the short hand for echoing php strings.
	// <?=$record[key] - will display the value for that array.
	$mfgdate= date('d/m/Y', strtotime($record['effectivedate']));
	
	if($mfgdate=='01/01/1970')
	{
		$mfgdate='00/00/0000';
	}
	
  ?>
  
   <tr>
   <?  if(($row['deleterights'])=='Yes')
	 { 
	 ?>
   <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center"><input name="checkbox[]" onChange="test();" type="checkbox" id="checkbox[]" value="<? echo $record['logiccode']."~".$record['effectivedate']; ?>"></td>
   <? } 
	 if(($row['editrights'])=='Yes') 
	 { 
	 ?>
   <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center" > <a style="color:#0360B2" name="edit" onClick="SetVis();"    href="logic.php?<?php if(($row['editrights'])=='Yes') { echo 'logiccode='; echo $record['logiccode']; echo '&effectivedate='; echo $record['effectivedate'];} else echo 'permiss'; ?>">Edit</a></td>
    <? 
	  } 
	  ?>
        <td  bgcolor="#FFFFFF"  ><?=$record['category']?></td>
        <td  bgcolor="#FFFFFF"><?=$record['logiccode']?>  </td>
        <td  bgcolor="#FFFFFF"> <?=$mfgdate?>  </td>
        <td  bgcolor="#FFFFFF"  ><?=$record['minimum']?></td>
        <? if(($record['minimum'])==0){ ?>
    <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center"><a style="color:#0360B2" HREF="view" onClick="return popup('logicgrid.php?<? echo 'logiccode='; echo $record['logiccode']; echo '&effectivedate='; echo $record['effectivedate']; ?>')">View</a></td>
     <? }
	 else{ ?>
      <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center"><label style="color:#FFD9D7" >View</label></td>
        
       <? } ?>
    </tr>  
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

<br />

<?php include("../../paginationdesign.php")?>
			   <div style="width:260px; height:50px; float:right;  margin-right:15px; margin-top:0px;" class="cont" >
						  
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
							 <div style="width:63px; height:32px; float:right; margin-top:18px;">
								<input type="submit" name="PDF" value="Export" class="button"/>
							 </div ></div>  
			 </div> 
			 
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
<?
}
?>
