<?php 
include '../../functions.php';
sec_session_start();
 require_once '../../masterclass.php';
include("../../header.php");
// Include database connection and functions here.

if(login_check($mysqli) == false) {
 
  header('Location:../../index.php');// Redirect to login page!
 
} else
{
	global $tname,$Productcode,$EffectiveDate,$CompensationValue,$search1,$search2,$EffectiveDate1;
	$scode = 'Productcode';
	$sname = 'EffectiveDate';
	$tname	= "servicemaster";
	$_POST['names'] = $news->dateformat($_POST['names']);
	require_once '../../searchfun.php';
    $stname="productserviceupload";
    require_once '../../masterclass.php'; // Include The News Class
	require_once '../../paginationfunction.php';
	$news = new News(); // Create a new News Object
	$newsRecordSet = $news->getNews($tname);
	
		
	$pagename = "Service Compensation Master";
	$validuser = $_SESSION['username'];
	$selectvar =mysql_query( "select * from userrights where userid = '$validuser' and screen = '$pagename'");//$news->getNewsById($validuser,$pagename);
	$row = mysql_fetch_array($selectvar);
  
 	if (($row['viewrights'])== 'No')
	{
		header("location:/".$_SESSION['mainfolder']."/home/home/master.php");
	}
	if(isset($_POST['permiss'])) // If the submit button was clicked
    {
		?>
            <script type="text/javascript">
			alert("you are not allowed to do this action!",'service.php');
			//setInterval(function(){document.location='service.php';},2000);
			//document.location='service.php';	
			</script>
         <?
		
	}
	if(isset($_GET['permiss']))
	{
	?>
            <script type="text/javascript">
			alert("you are not allowed to do this action!",'service.php');
			//setInterval(function(){document.location='service.php';},2000);
			//document.location='service.php';	
			</script>
         <?
		
	}//Page Verification Code and User Verification
    if(isset($_POST['Save'])) // If the submit button was clicked
    {
		unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
	    $post['Productcode'] = $_POST['Productcode'];
		$test=$news->dateformat($_POST['EffectiveDate']);
        $post['EffectiveDate'] = $test;
        $post['CompensationValue'] = floor($_POST['CompensationValue']);
		
		
		$Productcode = $_POST['Productcode'];
		$EffectiveDate = $_POST['EffectiveDate'];
        $CompensationValue = $_POST['CompensationValue'];
        // This will make sure its displayed
		if(!empty($_POST['EffectiveDate'])&&!empty($_POST['Productcode'])&&!empty($_POST['CompensationValue']))
		{   
		$result="SELECT * FROM servicemaster where Productcode  ='".$post['Productcode']."' and EffectiveDate ='".$post['EffectiveDate']."'";
		$sql1 = mysql_query($result) or die (mysql_error());
 		$myrow1 = mysql_num_rows($sql1);//mysql_fetch_array($retval);
			if($myrow1>0)
			{
			?>
            
			<script type="text/javascript">
			alert("Duplicate entry!");//document.location='service.php';	
			</script>
        	
            <?
			}
			else
			{
				
		$post['user_id'] = $_SESSION['username'];
		date_default_timezone_set ("Asia/Calcutta");
		$post['m_date']= date("y/m/d : H:i:s", time());
					
		$news->addNews($post,$tname);
			
		$spost['Code']  =$post['Productcode'];
		$spost['Masters']="servicemaster";
		$spost['Status']="0";
		$spost['InsertDate']=date("Y/m/d");
		$spost['Deliverydae']=date("Y/m/d");
				
		$franqry= mysql_query("SELECT Franchisecode  FROM  `franchisemaster`") or die (mysql_error());
		while($frqry = mysql_fetch_array($franqry))
		  {
			  $spost['Franchiseecode']=$frqry['Franchisecode'];
			  $news->addNews($spost,$stname);
		  }
			
			?>
            <script type="text/javascript">
			alert("Created Sucessfully!",'service.php');
			//setInterval(function(){document.location='service.php';},2000);
			//document.location='service.php';
			</script>
            <?
			}
        }
	
		else
		{
			?>
            <script type="text/javascript">
			alert("Enter Mandatory Fields!");//document.location='service.php';
			</script>
            <?
		}
    }
	 if(isset($_POST['Update'])) // If the submit button was clicked
    {
		unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
		$post['Productcode'] = $_POST['Productcode'];
		$test=$news->dateformat($_POST['EffectiveDate']);
        $post['EffectiveDate'] = $test;
        $post['CompensationValue'] = floor($_POST['CompensationValue']);
 		$Productcode = $_POST['Productcode'];
        $EffectiveDate =$_POST['EffectiveDate'];
        $CompensationValue = floor($_POST['CompensationValue']);
        // This will make sure its displayed	CompensationValue
		if(!empty($_POST['EffectiveDate'])&&!empty($_POST['Productcode'])&&!empty($_POST['CompensationValue']))
		{ 
		/*$wherecon= "Productcode ='".$post['Productcode']."' AND CompensationValue ='".$post['CompensationValue']."'";*/
		
		$result="SELECT * FROM servicemaster where Productcode  ='".$post['Productcode']."' and EffectiveDate ='".$post['EffectiveDate']."'";
		$sql1 = mysql_query($result) or die (mysql_error());
 		$myrow1 = mysql_num_rows($sql1);//mysql_fetch_array($retval);
			if($myrow1==0)
			{
			?>
            <script type="text/javascript">
			alert("You are not allowed to save a new record using Update!");//document.location='service.php';	
			</script>
        	 <?
			}
			else
			{
		$post['user_id'] = $_SESSION['username'];
		date_default_timezone_set ("Asia/Calcutta");
		$post['m_date']= date("y/m/d : H:i:s", time());
		
			$wherecon= "Productcode ='".$post['Productcode']."' and EffectiveDate ='".$post['EffectiveDate']."'";
			$news->editNews($post,$tname,$wherecon);
						
			$mkrow = mysql_query("SELECT Status,Franchiseecode FROM productserviceupload where Code='".$post['Productcode']."'");
			while($val=mysql_fetch_array($mkrow))
			{
					if($val['Status']>0)
					{
						$spost1['Status']=1;
					}
					else
					{
						$spost1['Status']=0;
					}
			$wherecon= "Code='".$post['Productcode']."' AND Franchiseecode='".$val['Franchiseecode']."'";
			$news->editNews($spost1,$stname,$wherecon);
			}
			
			?>
            <script type="text/javascript">
			alert("Updated Sucessfully!",'service.php');
			//setInterval(function(){document.location='service.php';},2000);
			</script>
            <?
					
			}
		}
		else
		{
			?>
            <script type="text/javascript">
			alert("Enter Mandatory Fields!");//document.location='service.php';
			</script>
            <?
		}
	}
	
/// EDIT LINK FUNCTION 
if(!empty($_GET['Productcode']))
{
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
$prmaster =$_GET['Productcode'];
$Manufactu = $_GET['EffectiveDate'];
//$cont->connect();
$result=mysql_query("SELECT * FROM servicemaster where Productcode  ='".$prmaster."' and EffectiveDate ='".$Manufactu."'");

$myrow1 = mysql_num_rows($result);//mysql_fetch_array($retval);

		if($myrow1==0)	
		{
			?>
            <script type="text/javascript">
			alert("No Data Found!",'service.php');
			</script>
   			<?
		}
		else
		{
		   $myrow = mysql_fetch_array($result);
		   $Productcode = $myrow['Productcode'];
		   $EffectiveDate = date("d/m/Y",strtotime($myrow['EffectiveDate']));
		   $CompensationValue = $myrow['CompensationValue'];
		 
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
		alert("Select data to delete!",'service.php');
		</script>
		<?
		}

else
{
		 $checkbox = $_POST['checkbox']; //from name="checkbox[]"
		 $countCheck = count($_POST['checkbox']);
		// echo $countCheck;
		for($i=0;$i<$countCheck;$i++)
		{
			$prodidd = $checkbox[$i];
			$newvar=explode("~",$prodidd);
			$var1=$newvar[0];
			$var2=$newvar[1];
			
			$mkrow = mysql_query("SELECT Status FROM productserviceupload where Code='".$var1."' and Status!='0'");
			$val=mysql_num_rows($mkrow);
					if($val==0)
					{
					$wherec= "Code='".$var1."'";
					$news->deleteNews($stname,$wherec); 
					
					$wherecon= "Productcode ='".$var1."' and EffectiveDate ='".$var2."'";
					$news->deleteNews($tname,$wherecon);	
					?>
					<script type="text/javascript">
					alert("Deleted  Successfully!",'service.php');
					</script>
					<?
					}
					else
					{
					?>
					<script type="text/javascript">
					alert("You Can't delete already send to Franchisee ",'service.php');
					</script>
					<?	
					}
			
}
}
}
	
if(isset($_POST['Cancel']))
{
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
	header('Location:service.php');
}
$_SESSION['type']=NULL;
	$servicemaster='select * from servicemaster';

if(isset($_POST['PDF']))
{
//	header('Content-type: application/vnd.ms-excel');
//    header("Content-Dispos..ition: attachment; filename=test.xls");
//    header("Pragma: no-cache");
//    header("Expires: 0");

$select=$_POST['Type'];$EffectiveDate1=$news->dateformat($_POST['names']);
if(!empty($_POST['codes'])&&!empty($_POST['names']))
	{
		$condition="SELECT * FROM servicemaster WHERE Productcode  like'".$_POST['codes']."%' AND EffectiveDate ='".
		$EffectiveDate1."%'order by id  DESC";
		$servicemaster=$condition;
		
	}
	else if(!empty($_POST['codes'])&&empty($_POST['names']))
	{
		$condition="SELECT * FROM servicemaster WHERE Productcode  like'".$_POST['codes']."%'order by id  DESC";
		$servicemaster=$condition;
	}
	else if(!empty($_POST['names'])&&empty($_POST['codes']))
	{
		$condition="SELECT * FROM servicemaster WHERE EffectiveDate ='".$EffectiveDate1."%'order by id  DESC";
		$servicemaster=$condition;
	}
	else
	{
		
		$condition="SELECT * FROM servicemaster order by id  DESC";
		$servicemaster=$condition;
	}
	$servicemaster=$condition;
if($select=='PDF')
{
	$_SESSION['type']='PDF';
	$_SESSION['query']=$servicemaster;
	$myFile = "testFile.txt";
	$fh = fopen($myFile, 'w') or die("can't open file");
	$stringData =NULL;
	$myquery = mysql_query($servicemaster);
	while( $myrecord = mysql_fetch_array($myquery))
   {
$stringData =$myrecord['Productcode']."\t ;".date("d/m/Y",strtotime($myrecord['EffectiveDate']))."\t;".$myrecord['CompensationValue']."\t\n";
		fwrite($fh, $stringData);
			
	}
//	
	fclose($fh);
	header('Location:ExportServiceMaster.php');
}
elseif($select=='Excel')
{
	$_SESSION['type']='Excel';
	$_SESSION['query']=$servicemaster;

	header('Location:ExportServiceMaster.php');
}
elseif($select=='Document')
{
	$_SESSION['type']='Document';
	$_SESSION['query']=$servicemaster;
	header('Location:ExportServiceMaster.php');
}
	
}


?>

<script type="text/javascript">
$(function() {
  $("#searchdate").datepicker({ changeYear:true, minDate:'0', yearRange: '2006:3050',dateFormat:'dd/mm/yy'});
  $("#serdat").datepicker({ changeYear:true, yearRange: '2006:3050',dateFormat:'dd/mm/yy',defaultDate: null});
});

  
function numericFilter(txb) {
   txb.value = txb.value.replace(/[^\0-9]/ig, "");
}
function validatecompensation(key)
{
var object = document.getElementById('CompensationValue');
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


</script>

 
<title><?php echo $_SESSION['title']; ?>|| Service Compensation Master</title>
</head>
 <?php 
  if(empty($_SESSION['codesval']) && empty($_SESSION['namesval']))
{
 if(!empty($_GET['Productcode'])){?>
 
 <body class="default" onLoad="document.form1.CompensationValue.focus()">

<? }else{?>


<body class="default" onLoad="document.form1.ProductCode.focus()">

 <? } 
}else{?>
<body class="default" onLoad="document.form1.codes.focus()">

 <? } ?>
 <center>
 <form method="POST" action="<?php $_PHP_SELF ?>" name="form1" id="frm1">
<?php include("../../menu.php")?>

<!--Third Block - Container-->
<div style="width:100%; height:auto; float:none;">
     <div style="width:980px; height:auto; float:none; margin-top:8px;" class="container">
                  
          <div style="width:950px; height:auto; float:left; margin-bottom:10px; margin-left:15px;" id="center">  
            <!-- form id start-->
            
            <div style="width:930px; height:auto; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;">
                    
						<div style="width:930px; height:25px; float:left;  margin-left:0px;" class="head">
						<p>Service Compensation Master</p>
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
                           
                            <?php if(!empty($_GET['Productcode'])){?>
							<input type="text" name="Productcode" id="ProductCode" style="border-style:hidden; background:#f5f3f1;" readonly="readonly" value="<?=$Productcode?>" />
								<? }else { ?>
                               <select name="Productcode"  id="ProductCode">
                                       <option value="<?php echo $Productcode;?>"><? if(!empty($Productcode)){ echo $Productcode;}else{?> ----Select---- <? } ?></option>
                                     <? $que = mysql_query("SELECT ProductCode FROM productmaster order by ProductCode asc");
                                       while( $record = mysql_fetch_array($que))
                                     {     
                                      echo "<option value=\"".$record['ProductCode']."\">".$record['ProductCode']."\n ";                                     }
                                    ?>
                                    </select>
                                   <?php } ?>
                              
                               </div>
 							<!--Row1 end--> 
                            
                            <!--Row2 -->  
                               <div style="width:145px; height:30px; float:left;  margin-top:5px; margin-left:3px;">
                                  <label>Effective Date</label><label style="color:#F00;">*</label>
                               </div>
                               <div style="width:200px; height:30px;  float:left; margin-top:5px; margin-left:3px;">
                               <?php if(!empty($_GET['Productcode'])){?>
                             <input type="text" name="EffectiveDate" style="border-style:hidden; background:#f5f3f1;" readonly="readonly" value="<?php echo $EffectiveDate?>"/>
								<? }else { ?>
                                <input type="text" name="EffectiveDate" id="searchdate" readonly="readonly" value="<?php echo $EffectiveDate?>"/>
                                   <?php } ?>
                               
                               </div>
                             <!--Row2 end-->  
                             
                            <!--Row3 -->  
                               <div style="width:145px; height:30px;  float:left; margin-top:5px; margin-left:3px;">
                                 <label>Compensation Value</label><label style="color:#F00;">*</label>
                               </div>
                               <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                <input type="text" name="CompensationValue" id="CompensationValue" maxlength="50" onKeypress="validatecompensation(this)"  value="<?php echo $CompensationValue ?>" onChange="return trim(this),numericFilter(this)"/>
                               </div>
                             <!--Row3 end-->   
                             
                                                              
                           </div>                             
                     <!-- col1 end --> 
                     
                     <!-- col2 -->   
                           <div style="width:200px; height:auto; float:left; padding-bottom:5px; margin-left:2px;" class="cont">                      
                           </div>                             
                     <!-- col2 end--> 
                     
                     <!-- col3 -->   
                           <div style="width:200px; height:auto; float:left; padding-bottom:5px; margin-left:2px;" class="cont">
                            </div>      
                     <!-- col3 --> 
                                                              
                    </div>
                  
                
				</div>
                <!-- main row 1 end-->

                
                <!--Main row 2 start-->
                <div style="width:930px; height:60px; float:left; margin-left:8px; margin-top:8px;">
                             
					<div style="width:235px; height:50px; float:left;  margin-left:14px; margin-top:0px;" id="center1">
						   
                          <div style="width:100px; height:32px; float:left; margin-top:16px; margin-left:10px;" >
                    <?php  if(!empty($_GET['Productcode']))
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
                               <div style="width:100px; height:30px; float:left; margin-left:3px; margin-top:16px;" >
                                  <label>Product Code</label>
                               </div>
                               <div style="width:145px; height:30px;  float:left; margin-left:3px; margin-top:16px;">
                                  <input type="text" name="codes" id="codes" onKeyPress="searchKeyPress(event);" value="<? echo $_SESSION['codesval'] ?>"/>
                               </div>
                             <!--Row1 end-->  
                             
                               <!--Row2 -->  
                               <div style="width:100px; height:30px; float:left; margin-left:3px; margin-top:16px;">
                                  <label>Effective date</label>
                               </div>
                               <div style="width:145px; height:30px;  float:left; margin-left:3px; margin-top:16px;" >
                                  <input type="text" name="names" id="serdat" onKeyPress="searchKeyPress(event);" value="<?  
							   $dummydate=date('d/m/Y', strtotime($_SESSION['namesval']));
							    if($dummydate=='01/01/1970'){echo $dummydate='';}else{echo $dummydate;}?>"/>
                               </div>
                             <!--Row2 end-->
                             
                             <div style="width:83px; height:32px; float:left; margin-top:16px;">
                                <input type="submit" name="Search" id="Search" value="Search" class="button"/>
                               </div>  
                          </div> 
                </div>
       
                <!--Main row 2 end-->
    
             
              <!--  grid start here-->
         
                
               <div style="width:930px; height:auto; padding-bottom:8px; margin-top:20px; float:left; margin-left:10px;" class="grid">
                   
                  <table id="datatable1" align="center" class="sortable" bgcolor="#FF0000" border="1" width="900px">
                <tr>
        <?  if(($row['deleterights'])=='Yes')
	 {
	?>    
     <td class="sorttable_nosort" style=" font-weight:bold; text-align:center" width="8px">
     <input type='checkbox' id="checkall" name='checkall' onclick='checkedAll(frm1);'></td>
       <? 
   }
    if(($row['editrights'])=='Yes') 
	  { 
	 ?>
     <td class="sorttable_nosort"  style=" font-weight:bold; text-align:center" >Action</td>
       <? 
	  } 
	  ?>
     <td style=" font-weight:bold;">Product  Code</td>
     <td style=" font-weight:bold;">Effective Date</td>
     <td style=" font-weight:bold;">Compensation Value</td>
     </tr>
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
     <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center"><input name="checkbox[]" type="checkbox" onChange="test();" id="checkboxs" value="<? echo $record['Productcode']."~".$record['EffectiveDate']; ?>"></td>
      <? 
   }
    if(($row['editrights'])=='Yes') 
	  { 
	 ?>
 	 <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center""> <a style="color:#0360B2" name="edit" id="toggleDisableBtn"  href="service.php?<?php if(($row['editrights'])=='Yes') { echo '&Productcode='; echo $record['Productcode'];echo '&EffectiveDate=';  echo $record['EffectiveDate'];} else echo 'permiss'; ?>">Edit</a></td>
      <? 
	  } 
	  ?>
    <td  bgcolor="#FFFFFF" align="left">
        <?=$record['Productcode']?>
    </td>
     <td  bgcolor="#FFFFFF"  align="left">
        <?=date("d/m/Y",strtotime($record['EffectiveDate'])) ?>
    </td>
    <td  bgcolor="#FFFFFF" align="left">
        <?=$record['CompensationValue']?>
    </td>
   
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
        
       <!--  grid end here-->
              
         <!-- form id start end-->      
          </div> 
          
     </div>       
</div>
<!--Third Block - Menu -Container -->


</form>

<!--Footer Block -->
<div id="footer-wrap1">
        <?php include("../../footer.php")?>
  </div>
<!--Footer Block - End-->
</center></body></html>
<?
}
?>