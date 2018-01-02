<?php 
	include '../../functions.php';
	sec_session_start();
	require_once '../../masterclass.php';
	include("../../header.php");

if(login_check($mysqli) == false) {
 
  header('Location:../../index.php');// Redirect to login page!
 
} else
{
    if (!isset($_SESSION['pagecount']))
		{
	 	$limit = 10;
		}
		else
	  	{
	    $limit =$_SESSION['pagecount'] ;
	 	}
    $tname	= "suppliermaster";
    require_once '../../masterclass.php'; // Include The News Class
	require_once '../../paginationfunction.php';
	$news = new News(); // Create a new News Object
	$newsRecordSet = $news->getNews($tname);
	global $SupplierCode,$SupplierName ,$ProductType,$Address,$Branch,$PinCode,$PanItNo, $City,$State,$search1, $search2;
	
	
	
	$pagename = "Supplier Master";
	$validuser = $_SESSION['username'];
	$selectvar =mysql_query( "select * from userrights where userid = '$validuser' and screen = '$pagename'");//$news->getNewsById($validuser,$pagename);
	$row = mysql_fetch_array($selectvar);
  
 	if (($row['viewrights'])== 'No')
	{
		?>
            <script type="text/javascript">
			alert("you are not allowed to view this page!");document.location='/amararaja/home/home/master.php';
			//setInterval(function(){document.location='/amararaja/home/home/master.php';},2000);
			//document.location='/amararaja/home/home/master.php';	
			</script>
         <?
	
	}
	if(isset($_POST['permiss'])) // If the submit button was clicked
    {
		?>
            <script type="text/javascript">
			alert("you are not allowed to do this action!",'suppliermaster.php');
		//	setInterval(function(){document.location='suppliermaster.php';},2000);
			//document.location='suppliermaster.php';	
			</script>
         <?
		
	}
	if(isset($_GET['permiss']))
	{
	?>
            <script type="text/javascript">
			alert("you are not allowed to do this action!",'suppliermaster.php');
			//setInterval(function(){document.location='suppliermaster.php';},2000);
			//document.location='suppliermaster.php';	
			</script>
         <?
		
	}

	 if(isset($_POST['Save'])) // If the submit button was clicked
    {
		//global $SupplierCode,$SupplierName ,$ProductType,$Address,$Branch,$PinCode,$PanItNo, $search1, $search2;
		$post['SupplierCode']  =$_POST['SupplierCode'];
	 $post['SupplierName'] =$_POST['SupplierName'];
	 $post['Address']=$_POST['Address'];
	 $countrysmallCode=mysql_query("SELECT * FROM branch where branchname='".$_POST['Branch']."'");
			$countrysmallfetch=mysql_fetch_array($countrysmallCode);
			
			$post['Branch'] = $countrysmallfetch['branchcode'];
	 //$post['Branch']=($_POST['Branch']);
	 $post['PinCode'] =$_POST['PinCode'];
	$post['PanItNo'] =$_POST['PanItNo'];
	
	$post['city']=$_POST['City'];
	$City=$_POST['City'];
	$statesmallCode=mysql_query("SELECT * FROM state where statename='".$_POST['State']."'");
			$statesmallfetch=mysql_fetch_array($statesmallCode);
			
			$post['state'] = $statesmallfetch['statecode'];
	//$post['state']=$_POST['State'];
	$State=$_POST['State'];
		
        $SupplierCode = $_POST['SupplierCode'];
		$SupplierName = $_POST['SupplierName'];
		$Address = $_POST['Address'];
        $Branch = $_POST['Branch'];
		$PinCode = $_POST['PinCode'];
       $PanItNo = $_POST['PanItNo'];
		
		if(!empty($_POST['SupplierCode'])&&!empty($_POST['SupplierName'])&&!empty($_POST['Address'])&&!empty($_POST['Branch'])&&!empty($_POST['PinCode']))
		{  
		
		$result="SELECT * FROM suppliermaster where SupplierCode ='".$post['SupplierCode']."'";
	
		$sql1 = mysql_query($result) or die (mysql_error());
 
		$myrow1 = mysql_num_rows($sql1);//mysql_fetch_array($retval);
		if($myrow1>0)
		{
		?>
            <script type="text/javascript">
			alert("Duplicate entry!");//document.location='suppliermaster.php';
			</script>
         <?
		}
		else
		{
			$post['user_id'] = $_SESSION['username'];
				date_default_timezone_set ("Asia/Calcutta");
				$post['m_date']= date("y/m/d : H:i:s", time());
		  $news->addNews($post,$tname);
		?>
            <script type="text/javascript">
			alert("Created Sucessfully!",'suppliermaster.php');
			//setInterval(function(){document.location='suppliermaster.php';},2000);
			//document.location='suppliermaster.php';
			</script>
         <?
		}
		}
		else
		{
			?>
            <script type="text/javascript">
			alert("Enter Mandatory Fields!");//document.location='suppliermaster.php';
			</script>
            <?
		}
    }
	 if(isset($_POST['Update'])) // If the submit button was clicked
    {
		$post['SupplierCode']  =$_POST['SupplierCode'];
	 $post['SupplierName'] =$_POST['SupplierName'];
	 $post['Address']=$_POST['Address'];
	// $post['Branch']=($_POST['Branch']);
	  $countrysmallCode=mysql_query("SELECT * FROM branch where branchname='".$_POST['Branch']."'");
			$countrysmallfetch=mysql_fetch_array($countrysmallCode);
			
			$post['Branch'] = $countrysmallfetch['branchcode'];
	 $post['PinCode'] =$_POST['PinCode'];
	$post['PanItNo'] =$_POST['PanItNo'];
	$post['city']=$_POST['City'];
	$City=$_POST['City'];
	$statesmallCode=mysql_query("SELECT * FROM state where statename='".$_POST['State']."'");
			$statesmallfetch=mysql_fetch_array($statesmallCode);
			
			$post['state'] = $statesmallfetch['statecode'];
	//$post['state']=$_POST['State'];
	$State=$_POST['State'];
		
        $SupplierCode = $_POST['SupplierCode'];
		$SupplierName = $_POST['SupplierName'];
		$Address = $_POST['Address'];
        $Branch = $_POST['Branch'];
		$PinCode = $_POST['PinCode'];
        $PanItNo = $_POST['PanItNo'];
		
        // This will make sure its displayed
		if(!empty($_POST['SupplierCode'])&&!empty($_POST['SupplierName'])&&!empty($_POST['Address'])&&!empty($_POST['Branch'])&&!empty($_POST['PinCode']))
		{ 
		$result="SELECT * FROM suppliermaster where SupplierCode ='".$post['SupplierCode']."'";
	
		$sql1 = mysql_query($result) or die (mysql_error());
 
		$myrow1 = mysql_num_rows($sql1);//mysql_fetch_array($retval);
		if($myrow1==0)
		{
		?>
            <script type="text/javascript">
			alert("You are not allowed to save a record using Update!");//document.location='suppliermaster.php';
			
			</script>
         <?
		}
		else
		{
			$post['user_id'] = $_SESSION['username'];
				date_default_timezone_set ("Asia/Calcutta");
				$post['m_date']= date("y/m/d : H:i:s", time());
			$wherecon= "SupplierCode ='".$post['SupplierCode']."'";
			$news->editNews($post,$tname,$wherecon);
						
			?>
            <script type="text/javascript">
			alert("Updated Sucessfully!",'suppliermaster.php');
			//setInterval(function(){document.location='suppliermaster.php';},2000);
			</script>
            <?
					
		}
		}
		else
		{
			?>
            <script type="text/javascript">
			alert("Enter Mandatory Fields!");//document.location='suppliermaster.php';
			</script>
            <?
		}
	}
	
/// EDIT LINK FUNCTION 
if(!empty($_GET['SupplierCode']))
{
  $prmaster  = $_GET['SupplierCode'];
$result=mysql_query("SELECT * FROM suppliermaster where SupplierCode ='".$prmaster."'");
$myrow1 = mysql_num_rows($result);//mysql_fetch_array($retval);

		if($myrow1==0)	
		{
			?>
            <script type="text/javascript">
			alert("No Data Found!",'suppliermaster.php');
			</script>
   			<?
		}
		else
		{
			$myrow = mysql_fetch_array($result);
			$SupplierCode = $myrow['SupplierCode'];
			$SupplierName = $myrow['SupplierName'];
			$Address = $myrow['Address'];
			$pgg= mysql_query("select branchname from branch where branchcode='".$myrow['Branch']."' ")  ;
			$record11 = mysql_fetch_array($pgg);
			$Branch = $record11['branchname'];
			// $Branch = $myrow['Branch'];
			$PinCode = $myrow['PinCode'];
			$PanItNo = $myrow['PanItNo'];
			$City=$myrow['city'];
			$Statepgg= mysql_query("select statename from state where statecode='".$myrow['state']."' ")  ;
			$Staterecord11 = mysql_fetch_array($Statepgg);
			$State = $Staterecord11['statename'];
	       //$State=$myrow['state'];
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
			alert("Select data to delete!",'suppliermaster.php');
			</script>
			<?
	}

else
{
		 $checkbox = $_POST['checkbox']; //from name="checkbox[]"
		 $countCheck = count($_POST['checkbox']);
		for($i=0;$i<$countCheck;$i++)
		{
		$prodidd = $checkbox[$i];
		///$prodid= $_POST['checkbox'];
		$wherecon= "SupplierCode ='".$checkbox[$i]."'";
		$news->deleteNews($tname,$wherecon);
		}
			?>
            <script type="text/javascript">
			alert("Deleted  Successfully!",'suppliermaster.php');
			//setInterval(function(){document.location='suppliermaster.php';},2000);
			//document.location='suppliermaster.php';
			</script>
   			<?
}
}


	   $page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
    	//$limit = 10;
    	$startpoint = ($page * $limit) - $limit;
		$starvalue = "";
        $statement = "suppliermaster"; 
        $query = mysql_query("SELECT * FROM {$statement} order by Id desc LIMIT {$startpoint} , {$limit}");


if(isset($_POST['Search']))
{
if(isset($_POST['codes'])||isset($_POST['names']))
{
	$search1=$_POST['codes'];
	$search2=$_POST['names'];
	
	if(empty($_POST['codes'])&&empty($_POST['names']))
	{
		?>
		    <script type="text/javascript">
			alert("Please enter some search criteria!");
			setTimeout(function() { $('#confirm .yes').trigger('click'); $.modal.close(); codes.focus(); }, 2000);
			</script>
			<?
	}
	else
	{
	if(!empty($_POST['codes'])&&!empty($_POST['names']))
	{
		$condition="SELECT * FROM suppliermaster WHERE SupplierCode like'".$_POST['codes']."%' OR SupplierName like'".$_POST['names']."%'";
		$suppliermaster=$condition;
	}
	else if(!empty($_POST['codes'])&&empty($_POST['names']))
	{
		$condition="SELECT * FROM suppliermaster WHERE SupplierCode like'".$_POST['codes']."%'";
		$suppliermaster=$condition;
	}
	else if(!empty($_POST['names'])&&empty($_POST['codes']))
	{
		$condition="SELECT * FROM suppliermaster WHERE SupplierName like'".$_POST['names']."%'";
		$suppliermaster=$condition;
	}
	else
	{
		
		$condition="SELECT * FROM suppliermaster WHERE 1";
		$suppliermaster=$condition;
	}
	
	$refer=mysql_query($condition);
	$myrow1 = mysql_num_rows($refer);
	//mysql_fetch_array($query);
	
	$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
    	$limit = 10;
    	$startpoint = ($page * $limit) - $limit;
        //to make pagination
        //$statement =  ;
		$starvalue = $myrow1;
		 //show records
       $query = mysql_query("{$condition} LIMIT {$startpoint} , {$limit}");
	   
		if($myrow1==0)	
		{
			?>
		     <script type="text/javascript">
			alert("Data not found!");
			setTimeout(function() { $('#confirm .yes').trigger('click'); $.modal.close(); codes.focus(); }, 2000);
			</script>
			<?
		
		}
	}
}

}

$_SESSION['type']=NULL;
	$suppliermaster='select * from suppliermaster';

if(isset($_POST['PDF']))
{
//	header('Content-type: application/vnd.ms-excel');
//    header("Content-Dispos..ition: attachment; filename=test.xls");
//    header("Pragma: no-cache");
//    header("Expires: 0");

$select=$_POST['Type'];
if(!empty($_POST['codes'])&&!empty($_POST['names']))
	{
		$condition="SELECT * FROM suppliermaster WHERE SupplierCode like'".$_POST['codes']."%' OR SupplierName like'".$_POST['names']."%'ORDER BY Id DESC";
		$suppliermaster=$condition;
	}
	else if(!empty($_POST['codes'])&&empty($_POST['names']))
	{
		$condition="SELECT * FROM suppliermaster WHERE SupplierCode like'".$_POST['codes']."%'ORDER BY Id DESC";
		$suppliermaster=$condition;
	}
	else if(!empty($_POST['names'])&&empty($_POST['codes']))
	{
		$condition="SELECT * FROM suppliermaster WHERE SupplierName like'".$_POST['names']."%'ORDER BY Id DESC";
		$suppliermaster=$condition;
	}
	else
	{
		
		$condition="SELECT * FROM suppliermaster ORDER BY Id DESC";
		$suppliermaster=$condition;
	}
if($select=='PDF')
{
	$_SESSION['type']='PDF';
	$_SESSION['query']=$suppliermaster;
	$myFile = "testFile.txt";
	$fh = fopen($myFile, 'w') or die("can't open file");
	$stringData =NULL;
	$myquery = mysql_query($suppliermaster);
	while( $myrecord = mysql_fetch_array($myquery))
   {
	   $groupselct="SELECT branchname FROM branch where branchcode='".$myrecord['Branch']."'";
	   $groupselct1 = mysql_query($groupselct);
	   $cntno=mysql_num_rows($groupselct1);
	   if($cntno==1)
   {
		   	$groupselct12 = mysql_fetch_array($groupselct1);
			$testtempp=$groupselct12['branchname'];
	   }
	    else
	   {
		   $testtempp ="";
	   }
$stringData =$myrecord['SupplierCode']."\t ;".$myrecord['SupplierName']."\t ;".$myrecord['Address']."\t ;". $testtempp."\t ;".$myrecord['PinCode']."\t ;".$myrecord['PanItNo']."\t\n";
		fwrite($fh, $stringData);
			
	}
//	
	fclose($fh);
	header('Location:ExportSupplier.php');
}
elseif($select=='Excel')
{
	$_SESSION['type']='Excel';
	$_SESSION['query']=$suppliermaster;

	header('Location:ExportSupplier.php');
}
elseif($select=='Document')
{
	$_SESSION['type']='Document';
	$_SESSION['query']=$suppliermaster;
	header('Location:ExportSupplier.php');
}
	
}
if(isset($_POST['Cancel']))
{
	header('Location:suppliermaster.php');
}

?>
<script>
function validatecity(key)
{
var object = document.getElementById('City');
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
function test()//Used for hide the delete button while click the check  box
				{
					var a=0;
					var addbutton = document.getElementById('addbutton');
					var checkall = document.getElementById('checkall')
					var table = document.getElementById('datatable1');
					var rowCount = table.rows.length;
					 if(rowCount >1)
					 {
						
						 for(var i=1;i < rowCount;i++)
						 {
						 if(document.getElementById('datatable1').rows[i].getElementsByTagName("INPUT")[0].checked==true)
							{
								 // delbutton.style.visibility = 'visible'; 
								 a++;
								 
							}
						 }
							
					 }
					 if(a>0)
					 {
						
						//delbutton.style.visibility = 'visible';
						//addbutton.style.visibility='hidden';
						addbutton.value="Delete";
						addbutton.name="Delete";
						
						 
					 }
					 else
					 {
						// addbutton.style.visibility='visible';
						 // delbutton.style.visibility='hidden';
						 	addbutton.value="Save";
							addbutton.name="Save";
						  checkall.checked= false; 
					 }
		}

checked=false;
function checkedAll (frm1) {
	var aa= document.getElementById('frm1');
	var addbutton = document.getElementById('addbutton');
	var table = document.getElementById('datatable1');
	var rowCount = table.rows.length;
	 if (rowCount >1 && checked == false)
          {
           checked = true
		   // delbutton.style.visibility = 'visible';
			//addbutton.style.visibility='hidden';
			addbutton.value="Delete";
			addbutton.name="Delete";
			// addbutton.style.width='1';
			//addbutton.style.height='1';
		   
          }
        else
          {
          checked = false
		// delbutton.style.visibility = 'hidden';
		  // addbutton.style.visibility='visible'; 
			//addbutton.add();
			addbutton.value="Save";
			addbutton.name="Save";
			//document.location='productgroupmaster.php';
		 
          }
	for (var i =0; i < aa.elements.length; i++) 
	{
	 aa.elements[i].checked = checked;
	 //delbutton.style.visibility = 'visible'; 
	}
      }

function ValidateSupplierCode(key)
{
	var object = document.getElementById('SupplierCode');
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

function ValidateSupplierName(key)
{
	var object = document.getElementById('SupplierName');
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

function ValidateAddress(key)
{
	var object = document.getElementById('Address');
	if (object.value.length <250 || key.keycode==8 || key.keycode==46)
{
	
return true;
}
else
{
	alert("Enter only 250 characters");
	toutfun(object);
return false;
}
}

function Validate5(obj){
   
    if (obj.value.length>19 || key.keycode==8 || key.keycode==46){
        alert("The field cannot contain more than 20 characters!")
        return false
    }else {
        return true
    }
}
 function isPincode(evt) 
        {  var object = document.getElementById('th');         
            var le=document.getElementById("th").value.length;           
            if( le < 6 )
            {
                   alert("Enter six digit pincode!");
				   toutfun(object);
				   return false;
				  
            }      
            return true;                      
        }   
function numericFilter(txb) {
   txb.value = txb.value.replace(/[^\0-9]/ig, "");
}

</script> 
<title>Amara Raja || ARBL Warehouse Master</title>
</head>
<?php if(!empty($_GET['SupplierCode'])){?>
 
 <body class="default" onLoad="document.myForm.SupplierName.focus()">

<? }else{?>


<body class="default" onLoad="document.myForm.SupplierCode.focus()">

 <? } ?>
 <center>

<?php include("../../menu.php")?>
<!--Third Block - Container-->
<div style="width:100%; height:auto; float:none;">
     <div style="width:980px; height:auto; float:none; margin-top:8px;" class="container">
                  
          <div style="width:950px; height:auto; float:left; margin-bottom:10px; margin-left:15px;" id="center">  
            <!-- form id start-->
            <form method="POST" action="<?php $_PHP_SELF ?>" id="frm1" name="myForm">
            <div style="width:930px; height:auto;   min-height: 250px; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;">
                    
						<div style="width:930px; height:25px; float:left;  margin-left:0px;" class="head">
						<p>ARBL Warehouse Master</p>
						</div>
              <!-- main row 1 start-->     
                    <div style="width:925px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
                     <!-- col1 -->   
                           <div style="width:300px; height:auto; padding-bottom:5px; float:left; " class="cont">
                            <!--Row1 -->  
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Warehouse Code</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                        
                              <?php if(!empty($_GET['SupplierCode']))
							{?>
                          <input type="text" name="SupplierCode" id="SupplierCode" readonly="readonly" style="border-style:hidden; background:#f5f3f1;" onKeyPress="return ValidateSupplierCode(event)"  value="<?php echo $SupplierCode; ?>" onChange="return codetrim(this)"/>
                            <? } 
							else { ?>
                                 <input type="text" name="SupplierCode" id="SupplierCode" onKeyPress="return ValidateSupplierCode(event)"  value="<?php echo $SupplierCode; ?>" maxlength="15" onChange="return codetrim(this)"/>
                                   <?
							}
							?>
                                 
                               </div>
 							<div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Warehouse Name</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                   <input type="text" maxlength="50" name="SupplierName" id="SupplierName" value="<?php echo $SupplierName; ?>"onKeyPress="return ValidateSupplierName(event)" onChange="return trim(this)"/>
                               </div>
                     			<div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Address</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:65px;  float:left;  margin-top:5px; margin-left:3px;">
                                   <textarea rows="4" cols="14" name="Address" id="Address" maxlength="250" onKeyPress="return ValidateAddress(event)" onChange="return trim(this)"><?php echo $Address; ?></textarea>
                               </div>
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Branch</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                
                                     <select name="Branch" id="Branch" >
                                       <option value="<?php echo $Branch;?>"><? if(!empty($Branch)){ echo $Branch;}else{?> ----Select---- <? } ?></option>
                                     <?
                                                                                
                                        $que = mysql_query("SELECT branchname FROM branch  ");
                                       
                                     while( $record = mysql_fetch_array($que))
                                     {
									  if($Branch!=$record['branchname'])
									  {	      
                                       echo "<option value=\"".$record['branchname']."\">".$record['branchname']."\n ";                      
									  }
                                     }
                                    ?>
                                          </select>
                               </div>
                                
                                                          
                           </div>                             
                     <!-- col1 end --> 
                     
                     <!-- col2 -->   
                           <div style="width:300px; height:auto; float:left; padding-bottom:5px; margin-left:2px;" class="cont">                                      <!--Row1 -->  
                              <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>City</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                   <input type="text" name="City"  id="City" value="<?php echo $City; ?>" maxlength="50" onKeyUp="return validatecity(event)" onChange="return trim(this)"/>
                               </div> 
                                <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>State</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                
                                     <select name="State" id="State" >
                                       <option value="<?php echo $State;?>"><? if(!empty($State)){ echo $State;}else{?> ----Select---- <? } ?></option>
                                     <?
                                                                                
                                        $que = mysql_query("SELECT statename FROM state");
                                       
                                     while( $record = mysql_fetch_array($que))
                                     {
									  if($State!=$record['statename'])
									  {	      
                                       echo "<option value=\"".$record['statename']."\">".$record['statename']."\n "; 
									  }
                                     }
                                    ?>
                                          </select>
                               </div> 
                              
                                <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Pin Code</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                   <input type="text" name="PinCode" id="th" value="<?php echo $PinCode; ?>" onKeyUp="numericFilter(this)" maxlength="6" onChange="return trim(this)"/>
                               </div>
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>PAN/IT No.</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                   <input type="text" name="PanItNo"  onkeypress="return Validate5(this)" value="<?php echo $PanItNo; ?>" onChange="return trim(this)" onFocus="return isPincode(event)"/>
                               </div>              
                           </div>                             
                     <!-- col2 end--> 
                     
                     <!-- col3 -->   
                           <div style="width:300px; height:auto; float:left; padding-bottom:5px; margin-left:2px;" class="cont">
                               
                           
                            </div>      
                     <!-- col3 --> 
                                                              
                    </div>
                  
                
				</div>
                <!-- main row 1 end-->
                
                
                    <!--Main row 2 start-->
                <div style="width:1000px; height:60px; float:left; margin-left:8px; margin-top:-35px;">
                             
					<div style="width:235px; height:50px; float:left;  margin-left:14px; margin-top:0px;" id="center1">
						   
                         <div style="width:100px; height:32px; float:left; margin-top:16px; margin-left:10px;" >
                    <?php      if(!empty($_GET['SupplierCode']))
					{?>
						<input name="<?php if(($row['editrights'])=='Yes') echo 'Update'; else echo 'permiss'; ?>" type="submit" class="button" value="Update" id="addbutton">
					<? }
					else
					{?>
						<input name="<?php if(($row['addrights'])=='Yes') echo 'Save'; else echo 'permiss'; ?>" type="submit" class="button" id="addbutton" value="Save" >
				          <? } ?>
				           </div>
                           
                          <div style="width:100px; height:32px; float:left;margin-top:16px; margin-left:10px; ">
						  <input name="Cancel" type="submit" class="button" value="Reset">
				           </div>              
                                                   
				     </div>	
                         
                          <div style="width:640px; height:50px; float:left;  margin-left:25px; margin-top:0px;" class="cont" id="center2">
                              <!--Row1 -->  
                               <div style="width:110px; height:30px; float:left; margin-left:3px; margin-top:12px;" >
                                  <label>Warehouse Code</label>
                               </div>
                               <div style="width:145px; height:30px;  float:left; margin-left:3px; margin-top:16px;">
                                 <input type="text" name="codes" id="codes" value="<? echo $search1 ?>"/>
                               </div>
                             <!--Row1 end-->  
                             
                               <!--Row2 -->  
                               <div style="width:110px; height:30px; float:left; margin-left:15px; margin-top:12px;">
                                  <label>Warehouse Name</label>
                               </div>
                               <div style="width:145px; height:30px;  float:left; margin-left:3px; margin-top:16px;" >
                                  <input type="text" name="names" value="<? echo $search2 ?>"/>
                               </div>
                             <!--Row2 end-->
                             
                             <div style="width:83px; height:32px; float:left; margin-left:15px; margin-top:16px;">
                                <input type="submit" name="Search" class="button1" value="">
                               </div>  
                          </div> 
                </div>
                <div style="width:930px; height:auto; padding-bottom:8px; margin-top:20px; float:left; margin-left:10px; overflow:auto;" class="grid">
            
                  <table id="datatable1" align="center" class="sortable" bgcolor="#FF0000" border="1" width="900px"><tr>
        <?  if(($row['deleterights'])=='Yes')
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
   							<td style=" font-weight:bold;">ARBL Warehouse Code</td>
  							<td style=" font-weight:bold;">ARBL Warehouse Name</td>
   							<td style=" font-weight:bold;">Address</td>
   							<td style=" font-weight:bold;">Branch</td>
                            <td style=" font-weight:bold;">City</td>
                            <td style=" font-weight:bold;">State</td>
   							<td style=" font-weight:bold;">PinCode</td>
   							<td style=" font-weight:bold;">PanItNo</td></tr>
   
 <?php
      // This while will loop through all of the records as long as there is another record left. 
      while( $record = mysql_fetch_array($query))
    { // Basically as long as $record isn't false, we'll keep looping.
      // You'll see below here the short hand for echoing php strings.
      // <?=$record[key] - will display the value for that array.
    ?>
    
     <tr>
      <?  if(($row['deleterights'])=='Yes')
	 {
	?> 
     <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center"><input name="checkbox[]" type="checkbox" id="checkbox[]" onChange="test();" value="<? echo $record['SupplierCode'];?>"></td>
	  <? 
  	 }
    if(($row['editrights'])=='Yes') 
	  { 
	 ?>
     <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center" > <a style="color:#FF2222" name="edit" href="suppliermaster.php?SupplierCode=<?= $record['SupplierCode'] ?>">Edit</a></td>
      <? 
	  } 
	  ?>
    <td  bgcolor="#FFFFFF"> <?=$record['SupplierCode']?> </td>
     <td  bgcolor="#FFFFFF"> <?=$record['SupplierName']?></td>
    <td  bgcolor="#FFFFFF"  > <?=$record['Address']?></td>
     <td  bgcolor="#FFFFFF" > 
      <? $check1= mysql_query("select branchname from branch where branchcode='".$record['Branch']."' ")  ;
		$check1record = mysql_fetch_array($check1);
       echo $check1record['branchname']; 
	 ?>
	
     </td>
      <td  bgcolor="#FFFFFF" > <?=$record['city']?></td>
       <td  bgcolor="#FFFFFF" > 
       <? $check2= mysql_query("select statename from state where statecode='".$record['state']."' ")  ;
		$check2record = mysql_fetch_array($check2);
       echo $check2record['statename']; 
	 ?>
	   
       </td>
    <td  bgcolor="#FFFFFF"  ><?=$record['PinCode']?> </td>
    <td  bgcolor="#FFFFFF"><?=$record['PanItNo']?></td>
   </tr>
  <?php
      }
  ?>
</table>
</div>
<br /> <div style="width:600px; height:50px; float:left;  margin-left:15px; margin-top:0px;"  >
  <div style="margin-left:10px; margin-top:16px;"><?php
			echo pagination($starvalue,$statement,$limit,$page);
		?></div></div>
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
                <!--Main row 2 end-->
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