<?php 
// @ob_start();
include '../../functions.php';
sec_session_start();
 require_once '../../masterclass.php';
include("../../header.php");
// Include database connection and functions here.

if(login_check($mysqli) == false) {
 
  header('Location:../../index.php');// Redirect to login page!
 
} else
{
	global $effectivedate,$tname ;
	// require_once("inc/common_functions.php");
	$tname = "branchtarget";
	require_once '../../searchfun.php';
	require_once '../../paginationfunction.php';
	$news = new News(); // Create a new News Object
/* need to modify the script block */

//Authentication Block Starts here

	$pagename = "Branch Target";
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
			alert("you are not allowed to do this action!",'branch_target.php');
			</script>
         <?
		
	}
	if(isset($_GET['permiss']))
	{
	?>
            <script type="text/javascript">
			alert("you are not allowed to do this action!",'branch_target.php');
			</script>
         <?
		
	}
/* need to modify the script block */

//Export AS CSV file starts here

$_SESSION['type']=NULL;
if(isset($_POST['PDF']))
{
$select=$_POST['Type'];
if(!empty($_SESSION['code_branch']) && !empty($_SESSION['search_date'])){
			$result =mysql_query("Select branchcode from branch where branchname like'%".$_SESSION['code_branch']."%'");
			$rownum = mysql_num_rows($result);
			if($rownum > 0){

				while($row= mysql_fetch_array($result)){
					$bname .= "'".$row['branchcode']."',";
				}
			$bname = substr($bname, 0, -1);
			}else{
				$bname = "''";
			}
			$branchtarget_sub ="SELECT * FROM branchtarget_sub where BranchCode IN(".$bname.") OR MonthYear='".$_SESSION['search_date']."'";
}else if(!empty($_SESSION['code_branch'])&&empty($_SESSION['search_date'])){
	$result =mysql_query("Select branchcode from branch where branchname like'%".$_SESSION['code_branch']."%'");
	$rownum = mysql_num_rows($result);
	if($rownum > 0){
		while($row= mysql_fetch_array($result)){
			$bname .= "'".$row['branchcode']."',";
		}
		$bname = substr($bname, 0, -1);
	}else{
		$bname = "''";
	}
	$branchtarget_sub ="SELECT * FROM branchtarget_sub where BranchCode IN(".$bname.")";
}else if(!empty($_SESSION['search_date'])&&empty($_SESSION['code_branch'])){
	$branchtarget_sub ="SELECT * FROM branchtarget_sub where MonthYear ='".$_SESSION['search_date']."'";
}else{
	$branchtarget_sub = "SELECT * FROM branchtarget_sub";
	}
if($select=='CSV')
{
	$_SESSION['type']='CSV';
	$_SESSION['query']=$branchtarget_sub;
	header('Location:Exportbranch_target.php');
 }	
unset($_SESSION['code_branch']);
unset($_SESSION['search_date']);
}
//Export AS CSV file ends here

//Save functionality starts here

if(isset($_POST['Save'])) // If the submit button was clicked
{
	unset($_SESSION['code_branch']);
	unset($_SESSION['search_date']);
	$ptype =array();
	$tarqty = array();
	$branchcode = array();
	$region = $_POST['region'];
	$branchname = $_POST['branch'];
	$Producttype = $_POST['Producttype'];
	$tarqty	= $_POST['tarqty'];
	foreach ($_POST['region'] as $selectedOption){
	    if($selectedOption!="0")
	    {
	      $reg =  $selectedOption;
	    }
	    else
	    {
	       break;
	    }                   
	 }
    foreach ($_POST['branch'] as $selectedOption1){
      if($selectedOption1!="0")
      {
        $brn= $selectedOption1;
      }
      else
      {
         break;
      }
    }
$i = 0;
   foreach ($_POST['Producttype'] as $selectedOption5){
    $ptype[$i] =  $selectedOption5;
    $i++;
  }
foreach ($_POST['branchcode'] as $selectedOption5){
	$branchcode = $selectedOption5;
}
$i = 0;
foreach ($_POST['tarqty'] as $selectedOption5){
	$tarqty[$i] = $selectedOption5;
	$i++;
}
		$test="";
		$test1="";
		$insflag =0 ;
		$effectivedate=$_POST['effectivedate'];
		if($brn==''||$brn=='0'||$effectivedate==''||$reg==''||$reg=='0'){ ?>
			<script type="text/javascript">
   				alert("Enter Mandatory Fields!");
   			</script>
	<?	}
		$check_data = mysql_query("select * from branchtarget where BranchCode='".$branchcode."' AND MonthYear='".$effectivedate."'");
		$check_row = mysql_num_rows($check_data);
		if ($check_row>0) { ?>
		 	<script type="text/javascript">
   				alert("Duplicate Entry",'branch_target.php');
   			</script>
		 <? }
		
   		$count = count($ptype);
   		$j = 0;
   		if($brn==''||$brn=='0'||$effectivedate==''||$reg==''||$reg=='0'||$check_row>0){
   		}else{
   		if($count == 1){
   			if($tarqty[0]==''||$tarqty[0]<=0||$ptype[0]=='0'||$ptype[0]==''){ ?>
   				<script type="text/javascript">
				alert("Enter Mandatory Fields!");
			</script>

   		 <? $insflag =1 ; }
   		}
   		if($insflag != 1){
	   		$user_id = $_SESSION['username'];
			date_default_timezone_set ("Asia/Calcutta");
			$m_date = date("y/m/d : H:i:s", time());
	   		$statement = "insert into branchtarget(BranchCode, MonthYear, user_id, m_date) VALUES('".trim($branchcode)."','".$effectivedate."','".trim($user_id)."','".trim($m_date)."')";
	   		$repres= mysql_query($statement) or die (mysql_error());
   		}
   		for($i=0;$i<$count;$i++){
   			// echo $tarqty[$i] .' ' .$ptype[$i]."\n";
   			$target_regex = '/^([1-9]\d{0,10})$/';
   			if($tarqty[$i]==''||$tarqty[$i]<=0||$ptype[$i]=='0'||$ptype[$i]=='' ||$tarqty[$i]=='0' || !preg_match($target_regex,$tarqty[$i])){

   				$not_insert[$j][0] = $ptype[$i];
   				$not_insert[$j][1] = $tarqty[$i];
   				$not_insert[$j][2] = "Check All Mandatory Fields";
   				$j++;
   			}
   			else{
   				for($k=0;$k<$i;$k++){
   				 	if($ptype[$k]==$ptype[$i]){
   				 		$flag = 1;
   				 		break;
   				 	}else{
   				 		$flag = 0;
   				 	}
   				 }
   				 if($flag == 0){
   				  $statement = "insert into branchtarget_sub(BranchCode, MonthYear, ProductTypeCode, Target) VALUES('".trim($branchcode)."','".$effectivedate."','".trim($ptype[$i])."',".trim($tarqty[$i]).")";
   				  $repres= mysql_query($statement) or die (mysql_error());
   				//  $_SESSION['retailer_code_error'] .= "Product Type : ".$ptype[$i]." , Target : ".$tarqty[$i]."  Saved \n";
   				}else{
   					$not_insert[$j][0] = $ptype[$i];
					$not_insert[$j][1] = $tarqty[$i];
					$not_insert[$j][2] = "Repeated Entry";
   					$j++;
   				}
   			}

   		} 
   		$insert_count  = count($not_insert);
   		if($insert_count >0 ){
   			for($i=0;$i<$insert_count;$i++){
   				if($insflag != 1)
   					$_SESSION['retailer_code_error'] .= "Product Type : ".$not_insert[$i][0]." , Target Qty : ".$not_insert[$i][1].' '.$not_insert[$i][2]. "  ---------------------------------\n";
			} 
			if($count == $insert_count){ ?>
			<script type="text/javascript">
				alert("Enter Mandatory Fields!");
			</script>
			<? 
			$delete_result = mysql_query("delete from branchtarget where BranchCode='".trim($branchcode)."' AND MonthYear='".$effectivedate."'") or die("Deletion Error");
			}else{ ?>
				<script type="text/javascript">
   				alert("Created Sucessfully!",'branch_target.php');
   			</script>
			<? } ?>
   
   	<? }else{ ?>
   			<script type="text/javascript">
   				alert("Created Sucessfully!",'branch_target.php');
   			</script>
   		<? }
   	}
		
}
//Save functionality ends here	

//Update functionality starts here

if(isset($_POST['Update'])) // If the submit button was clicked
{
	$ptype =array();
	$tarqty = array();
	$branchcode = array();
	$region = $_POST['region'];
	$branchname = $_POST['branch'];
	$Producttype = $_POST['Producttype'];
	$tarqty	= $_POST['tarqty'];
	foreach ($_POST['region'] as $selectedOption){
	    if($selectedOption!="0")
	    {
	      $reg =  $selectedOption;
	    }
	    else
	    {
	       break;
	    }                   
	 }
    foreach ($_POST['branch'] as $selectedOption1){
      if($selectedOption1!="0")
      {
        $brn= $selectedOption1;
      }
      else
      {
         break;
      }
    }
$i = 0;
   foreach ($_POST['Producttype'] as $selectedOption5){
    $ptype[$i] =  $selectedOption5;
    $i++;
  }
foreach ($_POST['branchcode'] as $selectedOption5){
	$branchcode = $selectedOption5;
}
$i = 0;
foreach ($_POST['target'] as $selectedOption5){
	$tarqty[$i] = $selectedOption5;
	$i++;
}
		$test="";
		$test1="";
		$effectivedate=$_POST['effectivedate'];
   		$count = count($ptype);
   		$j = 0;
   		$insflag = 0;
		if($count == 1){
			if($tarqty[0]==''||$tarqty[0]<=0||$ptype[0]=='0'||$ptype[0]==''){ ?>
				<script type="text/javascript">
					alert("Enter Mandatory Fields!");
				</script>

	   		  <? //exit;
	   		  $insflag = 1;
			}
   		}
   		if($insflag != 1){
	   		$delete_result = mysql_query("delete from branchtarget_sub where BranchCode='".$branchcode."' AND MonthYear='".$effectivedate."'") or die("Deletion Error");
	   		 $user_id = $_SESSION['username'];
			 date_default_timezone_set ("Asia/Calcutta");
			 $m_date = date("y/m/d : H:i:s", time());
	   		$upadate_result = mysql_query("update branchtarget set user_id='".$user_id."',m_date ='".$m_date."' where BranchCode='".$branchcode."' AND MonthYear='".$effectivedate."'" );
	   	}
   		for($i=0;$i<$count;$i++){
   			// echo $tarqty[$i]. "\t".$ptype[$i]."\n";
   			// if($tarqty[$i]==''||$tarqty[$i]==0||$ptype[$i]=='0'||$ptype[$i]==''){
   			$target_regex = '/^([1-9]\d{0,10})$/';

   			if($ptype[$i]=='0' ||$ptype[$i]==''||$tarqty[$i]=='0'||$tarqty[$i]=='' || $tarqty[$i]<=0 || !preg_match($target_regex,$tarqty[$i])){
   				echo $tarqty[$i];
   				$not_insert[$j][0] = $ptype[$i];
   				$not_insert[$j][1] = $tarqty[$i];
   				$not_insert[$j][2] = "Check All Columns";
   				$j++;
   			}
   			else{
   				for($k=0;$k<$i;$k++){
   				 	if($ptype[$k]==$ptype[$i]){
   				 		$flag = 1;
   				 		break;
   				 	}else{
   				 		$flag = 0;
   				 	}
   				 }
   				 if($flag == 0){
   				  $statement = "insert into branchtarget_sub(BranchCode, MonthYear, ProductTypeCode, Target) VALUES('".trim($branchcode)."','".$effectivedate."','".trim($ptype[$i])."',".trim($tarqty[$i]).")";
   				  $repres= mysql_query($statement) or die (mysql_error());
   				  //$_SESSION['retailer_code_error'] .= "Product Type : ".$ptype[$i]." , Target : ".$tarqty[$i]."  Updated \n";
   				}else{
   					$not_insert[$j][0] = $ptype[$i];
					$not_insert[$j][1] = $tarqty[$i];
					$not_insert[$j][2] = "Repeated Entry";
   					$j++;
   				}
   			}

   		}
   	// }
   		$insert_count  = count($not_insert);
   			if($insert_count >0 ){
   			for($i=0;$i<$insert_count;$i++){
   				if($insflag != 1)
   					$_SESSION['retailer_code_error'] .= "Product Type : ".$not_insert[$i][0]." , Target Qty : ".$not_insert[$i][1].' '.$not_insert[$i][2]." - Failed \n";
			} 
			if($count == $insert_count){ ?>
			<script type="text/javascript">
				alert("Enter Mandatory Fields!");
			</script>
			<? }else{ ?>
				<script type="text/javascript">
   				alert("Updated Sucessfully!",'branch_target.php');
   			</script>
			<? } ?>
  
   	<? }else{ ?>
   			<script type="text/javascript">
   				alert("Updated Sucessfully!",'branch_target.php');
   			</script>
   		<? }
}
//Update functionality ends here
	
/// EDIT LINK FUNCTION 

if(!empty($_GET['BranchCode']) && !empty($_GET['MonthYear']))
{
	unset($_SESSION['code_branch']);
	unset($_SESSION['search_date']);
  $edit_branchcode = $_GET['BranchCode'];
  $MonthYear =  $_GET['MonthYear'];
  $branch_result = mysql_query("select b.branchname as branchname,r.RegionName as RegionName from branch b left join region r on b.region=r.RegionCode where b.branchcode='".$edit_branchcode."'");
 while($rows = mysql_fetch_array($branch_result)){
 	$edit_branchname = $rows['branchname'];
 	$edit_regionname = $rows['RegionName'];
 } 
$result=mysql_query("SELECT * FROM branchtarget_sub where BranchCode ='".$edit_branchcode."' AND MonthYear='".$MonthYear."'");
$myrow1 = mysql_num_rows($result);//mysql_fetch_array($retval);
		if($myrow1==0)	
		{
			?>
            <script type="text/javascript">
			alert("No Data Found!!",'branch_target.php');//document.location='branch_target.php';
			</script>
   			<?
		}
		else
		{
	    $result1=mysql_query("SELECT * FROM branchtarget_sub where BranchCode ='".$edit_branchcode."' AND MonthYear='".$MonthYear."'") or die("cannot run ");
		$myrow = mysql_fetch_array($result);
	 	$branchcode = $myrow['BranchCode'];
	 	$MonthYear =  $myrow['MonthYear'];
			$result12=$result1;
			$_SESSION['blistsession']= $myrow['BranchName'];
		}
		$prmaster = NULL;
}
	// Check if delete button active, start this 
	
	if(isset($_POST['Delete']))
{
	if(!isset($_POST['checkbox']))
	{
			?>
		    <script type="text/javascript">
			alert("Select data to delete!",'branch_target.php');//document.location='branch_target.php';
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
		$branchid = $checkbox[$i];
		$brancharray = explode("~",$branchid);
		$delete_branchtarget = mysql_query("delete from branchtarget where BranchCode='".$brancharray[0]."' AND MonthYear='".$brancharray[1]."'") or die("Deletion Faliure in branchtarget");
		$delete_branchtarget_sub = mysql_query("delete from branchtarget_sub where BranchCode='".$brancharray[0]."' AND MonthYear='".$brancharray[1]."'") or die("Deletion Faliure in branchtarget_sub");
		?>
		<script type="text/javascript">
			alert("Deleted  Successfully!",'branch_target.php');
			</script>
		<? }
       
	}
}
//Check if reset button click
if(isset($_POST['Cancel']))
{
	unset($_SESSION['code_branch']);
	unset($_SESSION['search_date']);
	header('Location:branch_target.php');
}

?>

<script type="text/javascript">
$(function() {
    $('#start,#search_date').datepicker( {
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        yearRange: '2000:3050',
        dateFormat: 'mm-yy',
        onClose: function(dateText, inst,selectedDate) { 
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, month, 1));
            $( "#mscrp_todate" ).datepicker( "option", "minDate", selectedDate );
        }
    });
});

function popup(mylink)
{
if (! window.focus)return true;
var href;
if (typeof(mylink) == 'string')
   href=mylink;
else
   href=mylink.href;
window.open(href,'_blank');
return false;
}


 function addRow(tableID) {
 
            var table = document.getElementById(tableID);
 
            var rowCount = table.rows.length;
            var srcElem = window.event.srcElement;
			var rowNum = srcElem.parentNode.parentNode.rowIndex ;
			var targetqty =  document.getElementById('dataTable').rows[rowNum].getElementsByTagName("input")[0].value;
			var retaname=document.getElementById('dataTable').rows[rowNum].getElementsByTagName("select")[0];
			var er=retaname.options[retaname.selectedIndex].value;
			if(er == "" || er == 0 || er =='0'){
				document.getElementById('dataTable').rows[rowNum].getElementsByTagName("select")[0].focus();
				return false;	
			}
			if(targetqty == ""){
				document.getElementById('dataTable').rows[rowNum].getElementsByTagName("input")[1].focus();
				return false;
			}
			//alert(tableID);
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
			document.getElementById('dataTable').rows[rowCount-1].getElementsByTagName("select")[0].options.remove(0);
			document.getElementById('dataTable').rows[rowCount-1].getElementsByTagName("select")[0].options.add(new Option("----Select----",""));
			document.getElementById('dataTable').rows[rowCount-1].getElementsByTagName("select")[0].value="";
			
			$('#'+tableID+' select#Producttype:last').focus();

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

function validatePricelistCode(key)
{
	var object = document.getElementById('pricelistcode');
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

function validatePricelistName(key)
{
	var object = document.getElementById('pricelistname');
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

  var element;
 function isDecimal(str){
        if(isNaN(str)){
          if(element=="mrp")
                             {  
                                 document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[1].value='';   
                                    form.num.focus();
                              }
          else if(element=="fprice")
                               {
                                 document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[2].value='';    
                                  }
                                   else if(element=="rprice")
                                   {
                                    document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[3].value='';       
                                     }  
                                      else if(element=="iprice")
                                       {
                                        document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[4].value='';   
                                          }  
                                           form.num.focus();
        }
        else{
        str=parseFloat(str);
                
              if(element=="mrp")
                  {
                  document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[1].value=str.toFixed(2);
                     form.num.focus();
                  }
                  else if(element=="fprice")
                  {
                 document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[2].value=str.toFixed(2);   
                  }
                   else if(element=="rprice")
                  {
                 document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[3].value=str.toFixed(2);  
                  }  
                 else if(element=="iprice")
                  {
                 document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[4].value=str.toFixed(2);  
                  }
            }
            }
function validate()
{
   
    var srcElem = window.event.srcElement;
              element=  srcElem.id;
              
             rowNum = srcElem.parentNode.parentNode.rowIndex ;
         	
                  if(element=="mrp")
                  {
                   var dec=  document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[1].value;
                     
                  }
                  else if(element=="fprice")
                  {
                   var dec=  document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[2].value;    
                  }
                   else if(element=="rprice")
                  {
                   var dec=  document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[3].value;    
                  }  
                 else if(element=="iprice")
                  {
                   var dec=  document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[4].value;    
                  }
                 
                // var dec=document.getElementById('dataTable').rows[rowNum1].getElementsByTagName("select")[1].value;
                 if (dec == "")
                 {
                     
                         if(element=="mrp")
                             {
                                  document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[1].value='';   
                                form.num.focus();
                             }
                              else if(element=="fprice")
                               {
                                 document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[2].value='';    
                                  }
                                   else if(element=="rprice")
                                   {
                                    document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[3].value='';       
                                     }  
                                      else if(element=="iprice")
                                       {
                                        document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[4].value='';   
                                          }  
                           
          form.num.focus();
             
                    return false;
                }
                if (isDecimal(dec)==false)
                {
                   num="";
                   form.num.focus();
                    return false;
                 }
                      return true;
   }

        function getagentids() 
        { 
	
		var srcElem = window.event.srcElement;
		rowNum = srcElem.parentNode.parentNode.rowIndex ;
		var e=  document.getElementById('dataTable').rows[rowNum].getElementsByTagName("select")[0];
		var er=e.options[e.selectedIndex].value;
		//strUser = e.selectedIndex;
		//var resultq=document.getElementById('productdescriptionlist');
		var ddlArray= new Array();
		var ddl = document.getElementById('branchlist');
		//cnt=0;
		var tt;
		for (i = 0; i < ddl.options.length; i++) 
		{
			ddlArray[i] = ddl .options[i].value;
			var ty = ddlArray[i].split("~");
			var p =ty[0];
			var p2 =ty[1];
			
			if(p2==er)
			{
				tt=p;
			}
			else if(er=="")
			{
				tt="";
			}
			
			document.getElementById('dataTable').rows[rowNum].getElementsByTagName("input")[0].value=tt;
		}
		}

</script>
<script type="text/javascript"> 
function fsetvalue()
{
	
	var e = document.getElementById("branch"); 
	var er=e.options[e.selectedIndex].value;
	var ddlArray= new Array();
	var ddl = document.getElementById('branchlist');
	var tt,tt2;
	for (i = 0; i < ddl.options.length; i++) 
	{
		ddlArray[i] = ddl .options[i].value;
		var ty = ddlArray[i].split("~");
		var p  = ty[0];
		var p2 = ty[1];
		
		// console.log(p+''+p2);
		if(p2==er)
		{
			tt  = p;
			
		}
		else if(er=="")
		{
			tt  ="";
			tt2 ="";
		}
		document.getElementById("branchcode").value=tt;
		
	}
}
    </script>
<title>Amara Raja|| Branch Target Master</title>
</head>
 <?php  
 	?>
<? if(!empty($_GET['BranchCode']) && !empty($_GET['MonthYear'])){?>
<body class="default" onLoad="document.form1.Producttype.focus()">
 <? 
}else if(!empty($_GET['codes'])){?>
<body class="default" onLoad="document.form1.codes.focus()">
 <? }else{ ?>
 	<body class="default" onLoad="document.form1.region.focus()">
  <? }
  ?>
 <center>

<?php include("../../menu.php")  ?>
<script src="inc/multiselect.js" type="text/javascript"></script>

<!--Third Block - Container-->
<div style="width:100%; height:auto; float:none;">
     <div style="width:980px; height:auto; float:none; margin-top:8px;" class="container">
                  
          <div style="width:950px; height:auto; float:left; margin-bottom:10px; margin-left:15px;" id="center">  
            <!-- form id start-->
             <form method="POST" action="<?php $_PHP_SELF ?>" name="form1" id="form1"  >
             <table id="default" style="height:10px; display:none;" >
	            <tr>
	                <td>
	                    <select  name="emplist" id="emplist">
	                     <?
	                                                                
	                        $que = mysql_query("SELECT Distinct(RegionName),branchname FROM `view_rptfrnfin`");
	                       
	                     while( $record = mysql_fetch_array($que))
	                     {
	          
	                      echo "<option value=\"".$record['RegionName']."~".$record['branchname']."\">".$record['RegionName']."~".$record['branchname']."\n "; 
	    				 }
	                   
	                    ?>
	                          </select>
	                 </td>
	            </tr>
           	</table>
             <table style="display:none;" >

                                         <tr >
                                         <td>
                                        
    <select  name="branchlist" id="branchlist"  >
    <?
    
    $que = mysql_query("SELECT branchcode, branchname FROM branch order by branchname asc");
    
		while( $record = mysql_fetch_array($que))
		{
			echo "<option value=\"".$record['branchcode']."~".$record['branchname']."\">".$record['branchcode']."~".$record['branchname']."\n "; 
		}
    
    ?>
    </select>
                                      </td>

                                      </tr>
</table>
            <div style="width:930px; height:auto;   min-height: 250px; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;">
                    
						<div style="width:930px; height:25px; float:left;  margin-left:0px;" class="head">
						<p>Branch Target </p>
						</div>
              <!-- main row 1 start-->     
                    <div style="width:925px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
                     <!-- col1 -->   
                           <div style="width:400px; height:auto; padding-bottom:5px; float:left; " class="cont">
                             <!--Row1 -->  
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Region  Name</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                              <?if(empty($_GET['BranchCode']) && empty($_GET['MonthYear'])) { ?>
                              <select name='region[]' id='region' onChange="drpfunc();"  >

                     <option value="0">----Select----</option>
                    <?
                                            // $region_select = ($_POST['region']) ? $_POST['region'] : '';
                    						foreach ($_POST['region'] as $selectedOption2){
                                                if($selectedOption2!="0")
                                                {
                                                    $regionname = $selectedOption2;
                                                }
                                            }
                                            $list = mysql_query("SELECT regioncode, regionname FROM region order by regionname asc");

                                            while ($row_list = mysql_fetch_assoc($list)) {
                                                $selected = '';

                                                if ($row_list['regionname'] == $regionname) {
                                                    $selected = ' selected ';
                                                }
                                                ?>
                    <option value="<? echo $row_list['regionname']; ?>"<? echo $selected; ?>> <? echo $row_list['regionname']; ?> </option>
                    <?
                                            }
                                            ?>
                    </select>
                    <? }else { ?>
                    	<input type="text" id="region"  value="<?php echo $edit_regionname; ?>" name="region[]" style="border-style:hidden; background:#f5f3f1; width:150px;" readonly="readonly" />
                    <? } ?>
                             </div>
                             <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Branch  Name</label><label style="color:#F00;">*</label>
                               </div>
                             	<div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                             		<?if(empty($_GET['BranchCode']) && empty($_GET['MonthYear'])) { ?>
                             		<select name='branch[]' id='branch'  onChange="fsetvalue();">
                                            <option value="0">----Select----</option>
                                       		 <? $list = mysql_query("SELECT branchcode, branchname FROM branch order by branchname asc");
                                            foreach ($_POST['branch'] as $selectedOption2){
                                                if($selectedOption2!="0")
                                                {
                                                    $branchname = $selectedOption2;
                                                }
                                            }
                                            while ($row_list = mysql_fetch_assoc($list)) {
                                                $selected = '';

                                                if ($row_list['branchname'] == $branchname){
                                                    $selected = ' selected ';
                                                
                                                ?>
                                                <option value="<? echo $row_list['branchname']; ?>"<? echo $selected; ?>>
    													<? echo $row_list['branchname']; ?>
                                                </option>

                                                <? }
                                            }
                                            ?>
                                        </select>
                                        <?}else{ ?>
                                  				<input type="text" id="branch"  value="<?php echo $edit_branchname; ?>" name="branch[]" style="border-style:hidden; background:#f5f3f1; width:150px;" readonly="readonly" />      	
                                       <? } ?>

                             	</div>
                           </div>                             
                     <!-- col1 end -->  
                     
                     <!-- col2 -->   
  		<div style="width:400px; overflow:auto; height:auto; float:left; padding-left:150px,padding-bottom:5px; margin-left:100px;" class="cont">
   <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Month &  Year</label><label style="color:#F00;">*</label>
              </div>
                              <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
				
                    <?if(empty($_GET['BranchCode']) && empty($_GET['MonthYear'])) { ?>
                     <input type="text" name="effectivedate" value="<?php if(isset($_POST['effectivedate'])){echo $_POST['effectivedate']; }else{echo $MonthYear;}?>"  id="start"/>
                     <? }else{ ?> 
                     <input type="text" value="<?php echo $MonthYear; ?>" name="effectivedate" style="border-style:hidden; background:#f5f3f1; width:150px;" readonly="readonly" />
                    <? } ?>
			  </div>
			  <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Branch Code</label>
              </div>
              <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
              	<input type="text" id="branchcode"  value="<?php if(isset($_POST['branchcode'])){foreach ($_POST['branchcode'] as $selectedOption5){
	$branchcode = $selectedOption5;
}echo $branchcode;}else{echo $edit_branchcode;} ?>" name="branchcode[]" style="border-style:hidden; background:#f5f3f1; width:150px;" readonly="readonly" />
              </div>                             
            </div>
                   
   <div style="width:925px; height:200px; overflow:auto; float:left;  margin-top:8px; margin-left:5px;">
    <TABLE  id="dataTable"  width="350px;" border='1'>
  	<tr>
	  	<td  style=" font-weight:bold; width:60px; text-align:center;">Product Type<label style="color:#F00;">*</label></td>
	    <td  style=" font-weight:bold; width:60px; text-align:center;">Target Qty<label style="color:#F00;">*</label></td>
		<td  style=" font-weight:bold; width:10px;">&nbsp;</td>
    </tr>
    <?
    
    
	// if(!isset($_POST['Search'])){
	// 	$displaydown = mysql_query("SELECT BranchCode,MonthYear FROM branchtarget order by m_date desc");
	// }
	if($result12!="")
	{
		$i = 0;
	while($mybranchtarget = mysql_fetch_array($result12))
			{
				$productcode='';
	     $target = $mybranchtarget['Target'];
		 $product_type= $mybranchtarget['ProductTypeCode'];	
		 $i++;
	?>
        <TR>
         <TD style='text-align:center'> 
          	 <select name='Producttype[]' id="Producttype"  onChange="drpfunc4();">
                                            <option value="0">----Select----</option>
                                            <?
                       $list = mysql_query("SELECT distinct (ProductTypeName)as ProductTypeName,ProductTypeCode FROM producttypemaster ORDER BY ProductTypeName asc");
                                            while ($row_list = mysql_fetch_assoc($list)) {
                                                $selected = '';

                                                if ($row_list['ProductTypeCode'] == $product_type) {
                                                    $selected = ' selected ';
                                                }
                                                ?>
                        
                                                
                                                   <option value="<? echo $row_list['ProductTypeCode']; ?>"<? echo $selected; ?>>
                                                    <? echo $row_list['ProductTypeName']; ?> 
                            
                                                </option>

                                                <?
                                            }
                                            ?>
                                        </select>

            </TD>
             <TD style='text-align:center'><INPUT type="text"  name="target[]" id="target" style="width:80px;" class="table_last_field"  onChange="validate(this)" value="<?php echo trim($target) ?>" /></TD>
			<TD  class="remove_btn">
			<?php if($i>1) { ?> <img src="del_img.jpg" style='cursor: pointer; width:20px;' onclick='removeRow(this, "edit");'/>
			<?php } ?>
			</TD>
			
        </TR>
        <?
				}
				$productcode='';
		}
		else
		{
		?>
        <TR>
             <TD style='text-align:center'>
                <select name='Producttype[]' id="Producttype"  onChange="drpfunc4();">
                                            <option value="0">----Select----</option>
                                            <?
                                            $Producttype_select = ($_POST['Producttype']) ? $_POST['Producttype'] : '';
                      
                       $list = mysql_query("SELECT distinct (ProductTypeName)as ProductTypeName,ProductTypeCode FROM producttypemaster ORDER BY ProductTypeName asc");
                                            while ($row_list = mysql_fetch_assoc($list)) {
                                                $selected = '';

                                                if ($row_list['ProductTypeName'] == $Producttype_select) {
                                                    $selected = ' selected ';
                                                }
                                                ?>
                        
                                                
                                                   <option value="<? echo $row_list['ProductTypeCode']; ?>"<? echo $selected; ?>>
                                                    <? echo $row_list['ProductTypeName']; ?> 
                            
                                                </option>

                                                <?
                                            }
                                            ?>
                                        </select>
             
            <TD style='text-align:center'><INPUT type="text"  name="tarqty[]" id="tarqty" onChange="validate(this)" style="width:80px;" class="table_last_field"/></TD>
      <TD  class="remove_btn">&nbsp;</TD>
         
        </TR>
        <?
		}
		?>
		<tr><td colspan="7" style="height: 0px;"></td></tr>
         </TABLE></div>
    
			   </div>
               
                <!-- main row 1 end-->
                
                  <!--Main row 2 start-->
                <div style="width:1000px; height:60px; float:left; margin-left:8px; margin-top:10px;">     
                   
					<div style="width:225px; height:50px; float:left;  margin-left:14px; margin-top:-3px;" id="center1">
                    
                        <div style="width:100px; height:32px; float:left; margin-top:16px; margin-left:10px;" >
                        
                    <?php      if(!empty($_GET['BranchCode']))
					{?>
						<input name="<?php if(($row['editrights'])=='Yes') echo 'Update'; else echo 'permiss'; ?>" type="submit" class="button" value="Update" id="addbutton">
					<? }
					else
					{?>
						<input name="<?php if(($row['addrights'])=='Yes') echo 'Save'; else echo 'permiss'; ?>" type="submit" class="button save_button" id="addbutton" value="Save" >
				          <? } ?>
		              </div>
                           
                          <div style="width:80px; height:32px; float:left;margin-top:16px; margin-left:10px; ">
						  <input name="Cancel" type="submit" class="button" value="Reset" class="buttonrest">
		              </div>    
	              </div>                          
                                                   
		       
                         
               <div style="width:640px; height:50px; float:left;  margin-left:25px; margin-top:0px;" class="cont" id="center2">
                              <!--Row1 -->  
                               <div style="width:95px; height:30px; float:left; margin-left:3px; margin-top:16px;" >
                                <label>Branch Name</label>
                               </div>
                               <div style="width:130px; height:30px;  float:left; margin-left:3px; margin-top:16px;">
                                 <input type="text" name="code_branch" id="code_branch" onKeyPress="searchKeyPress(event);" value="<? echo $_SESSION['code_branch']?>"/>
                               </div>
                             <!--Row1 end-->  
                             
                               <!--Row2 -->  
                               <div style="width:95px; height:30px; float:left; margin-left:3px; margin-top:16px;">
                                  <label>Month & Year</label>
                               </div>
                               <div style="width:130px; height:30px;  float:left; margin-left:3px; margin-top:16px;" >
                                 <input type="text"   name="search_date" id='search_date' onKeyPress="searchKeyPress(event);" value="<? echo $_SESSION['search_date']?>"/>
                               </div>
                               <div style="width:83px; height:32px; float:left; margin-top:16px;">
                                <input id="Search" type="submit" name="Search" value="" class="button1 buttonrse"/>
                               </div>  
                               </div>
                               </div>	
                          <!--Row2 end-->
          <!--  grid start here-->
             
              <div style="width:900px; height:auto; padding-bottom:8px; margin-top:20px; float:left; margin-left:19px; overflow:auto;" class="grid">
                    
                  <table id="datatable1" align="center" class="sortable" border="1" width="870px">
    <tr > 
 	 <?  
	 if(($row['deleterights'])=='Yes')
		{
	?>    
     <td class="sorttable_nosort" style="font-weight:bold; text-align:center" width="8px">
     <input type='checkbox' id="checkall" name='checkall' onclick='checkedAll(form1);'></td>
   	<? 
   		}
    if(($row['editrights'])=='Yes') 
	  	{ 
	 ?>
     <td class="sorttable_nosort" style="font-weight:bold; text-align:center" width="12px">Action</td>
      <? 
		} 
	  ?>
   <td style=" font-weight:bold;">Branch Name</td>
  <td style=" font-weight:bold;">Month & Year</td>
  
  </tr>
 <?php
 	// $rownum = mysql_num_rows($displaydown);
 	if($myrow1 > 0){
      while( $record = mysql_fetch_array($query))
    { 
    ?>
    
     <tr>
      <?  
	 if(($row['deleterights'])=='Yes')
		{
	?> 
     <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center"><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<? echo $record['BranchCode'].'~'.$record['MonthYear']; ?>"  onchange="test();"></td>
       	<? 
   		}
    if(($row['editrights'])=='Yes') 
	  	{ 
	 ?>
     <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center"> <a style="color:#FF2222" name="edit" href="branch_target.php?<? echo 'BranchCode=';echo $record['BranchCode'];echo '&MonthYear=';echo $record['MonthYear'];?>">Edit</a></td>
     <? 
		} 
	  ?>
    
     <td  bgcolor="#FFFFFF"  align="left">
     	<? $branch_result = mysql_query("select branchname FROM branch where branchcode = '".$record['BranchCode']."'"); 
     	while($rows = mysql_fetch_array($branch_result)){

     	?>
        <?=$rows['branchname']; } ?>
    </td>
  
    <td  bgcolor="#FFFFFF" align="left">
       <?=$record['MonthYear'] ?>

    </td>
    </tr>  
  <?php
      }
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
  <div style="width:260px; height:50px; float:right;  margin-right:15px; margin-top:0px;" class="cont" >
         					
   <div style="width:70px; height:32px; float:left; margin-left:25px; margin-top:20px;">
  		Export As
	
   </div> 
 <div style="width:83px; height:32px; float:left; margin-left:5px; margin-top:12px;">
    <select name="Type">
       <option value="CSV">CSV</option>
    </select>
	
 </div>
<div style="width:63px; height:32px; float:right; margin-top:18px;">
	  <input type="submit" name="PDF" value="Export" class="button buttonrse"/>
  
</div ></div>
               <!--  grid end here-->
        
             <!-- form id start end-->      
       <br /><br />
       <input type="hidden" value="0" id="last_inc_count" />  
       <!--Third Block - Menu -Container -->
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
$productcode='';
}
?>

<?
if(!empty($_SESSION['retailer_code_error'])){
echo '<META HTTP-EQUIV="Refresh" Content="0; URL=errorlogfile.php">';
    exit;
    
}
?>

 <style>
    .ui-datepicker-calendar { 
        display: none;
        }
    </style>
    <script>

//     $( ".buttonrest" ).click(function() {
    	
//   // $( "#target" ).click();
//   alert('hello');
//   // $( "#form1" ).submit();
//   document.getElementById("form1").reset();
//   document.location='branch_target.php';
// });
    </script>