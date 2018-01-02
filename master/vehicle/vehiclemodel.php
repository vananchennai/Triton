<?php 
	include '../../functions.php';
	sec_session_start();
	require_once '../../masterclass.php';
	include("../../header.php");

if(login_check($mysqli) == false) {
 
  header('Location:../../index.php');// Redirect to login page!
 
} else
{
	global $modelcode,$modelname,$MakeName,$segmentname,$scode,$sname;
	$scode = 'modelcode';
	$sname = 'modelname';
	$tname	= "vehiclemodel";
	require_once '../../searchfun.php';
	$stname="productvehiclemodelupload";
	$mtable1="productvehiclemodelupload";
    require_once '../../masterclass.php'; // Include The News Class
	require_once '../../paginationfunction.php';
	$news = new News(); // Create a new News Object
	$newsRecordSet = $news->getNews($tname);
    
	$pagename = "Vehiclemodel";
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
			alert("you are not allowed to do this action!",'vehiclemodel.php');	
			</script>
         <?
		
	}
	if(isset($_GET['permiss']))
	{
	?>
            <script type="text/javascript">
			alert("you are not allowed to do this action!",'vehiclemodel.php');	
			</script>
         <?
		
	}//Page Verification Code and User Verification
	
	if(isset($_POST['Save'])) // If the submit button was clicked
    {
			unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
		$post['modelcode']  =strtoupper(str_replace('&', 'and',$_POST['modelcode']));
		$post['modelname']  =strtoupper(str_replace('&', 'and',$_POST['modelname']));
	// $post['MakeName']=($_POST['MakeName']);
	 $smallCode=mysql_query("SELECT MakeNo FROM vehiclemakemaster where MakeName='".$_POST['MakeName']."'");
			$smallfetch=mysql_fetch_array($smallCode);
			
			$post['MakeName'] = $smallfetch['MakeNo'];
	// $post['segmentname']=($_POST['segmentname']);
	$small=mysql_query("SELECT segmentcode FROM vehiclesegmentmaster where segmentname='".$_POST['segmentname']."'");
			$fetch=mysql_fetch_array($small);
			
		$post['segmentname'] = $fetch['segmentcode'];
        $modelcode = $post['modelcode'];
		$modelname = $post['modelname'];
        $MakeName = $_POST['MakeName'];
		$segmentname = $_POST['segmentname'];
			$productgroup=$_POST['productgroup'];//$_POST['productgroup'];
			$smallCode=mysql_query("SELECT * FROM productgroupmaster where ProductGroup='".$_POST['productgroup']."'");
			$smallfetch=mysql_fetch_array($smallCode);
			$post['ProductGroup'] = $smallfetch['ProductCode'];
 
        // This will make sure its displayed
		if(!empty($_POST['modelcode'])&&!empty($_POST['modelname'])&&!empty($_POST['MakeName'])&&!empty($_POST['segmentname'])&&!empty($_POST['productgroup']))
		{   
			$p1=strtoupper( preg_replace('/\s+/', '',$post['modelcode']));
	$p2=strtoupper( preg_replace('/\s+/', '',$post['modelname']));
	$cnduplicate=0;
	$repqry="SELECT REPLACE( `modelcode` ,  ' ',  '' ) AS modelcode, REPLACE(  `modelname` ,  ' ',  '' ) AS modelname FROM vehiclemodel where modelname = '".$p2."' or modelname = '".$post['modelname']."' or modelcode = '".$p2."' or modelcode = '".$post['modelname']."' or modelcode = '".$p1."' or modelcode = '".$post['modelcode']."' or modelname = '".$p1."' or modelname = '".$post['modelcode']."'";
	$repres= mysql_query($repqry) or die (mysql_error());
	$cnduplicate=mysql_num_rows($repres);
		
		if($cnduplicate>0 || ($post['modelcode']==$post['modelname']))
		{
		?>
            <script type="text/javascript">
			alert("Duplicate entry!");
			</script>
         <?
		}
		
		else
		{
			$post['user_id'] = $_SESSION['username'];
		date_default_timezone_set ("Asia/Calcutta");
		$post['m_date']= date("y/m/d : H:i:s", time());
		
	 $news->addNews($post,$tname);
	 
	 	$spost['Code']  =$post['modelcode'];
		$spost['Masters']="vehiclemodel";
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
			alert("Created Sucessfully!",'vehiclemodel.php');
			//setInterval(function(){document.location='vehiclemodel.php';},2000);
			//document.location='vehiclemodel.php';
			</script>
         <?
		}
		}
		else
		{
			?>
            <script type="text/javascript">
			alert("Enter Mandatory Fields!");///document.location='vehiclemodel.php';
			</script>
            <?
		}
    }
	 if(isset($_POST['Update'])) // If the submit button was clicked
    {
			unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
		$post['modelcode']  =strtoupper(str_replace('&', 'and',$_POST['modelcode']));
		$post['modelname']  =strtoupper(str_replace('&', 'and',$_POST['modelname']));
	 //$post['MakeName']=($_POST['MakeName']);
	// $post['segmentname']=($_POST['segmentname']);
		  $smallCode=mysql_query("SELECT MakeNo FROM vehiclemakemaster where MakeName='".$_POST['MakeName']."'");
			$smallfetch=mysql_fetch_array($smallCode);
			
			$post['MakeName'] = $smallfetch['MakeNo'];
	// $post['segmentname']=($_POST['segmentname']);
	$small=mysql_query("SELECT segmentcode FROM vehiclesegmentmaster where segmentname='".$_POST['segmentname']."'");
			$fetch=mysql_fetch_array($small);
			
			$post['segmentname'] = $fetch['segmentcode'];
		 $modelcode = $post['modelcode'];
		$modelname = $post['modelname'];
        $MakeName = $_POST['MakeName'];
		$segmentname = $_POST['segmentname'];
			$productgroup=$_POST['productgroup'];//$_POST['productgroup'];
			$smallCode=mysql_query("SELECT * FROM productgroupmaster where ProductGroup='".$_POST['productgroup']."'");
			$smallfetch=mysql_fetch_array($smallCode);
 $post['ProductGroup'] = $smallfetch['ProductCode'];
        // This will make sure its displayed
		if(!empty($_POST['modelcode'])&&!empty($_POST['modelname'])&&!empty($_POST['MakeName'])&&!empty($_POST['segmentname'])&&!empty($_POST['productgroup']))
		{ 
		$codenamedcheck=0;
		if($_SESSION['vehmodelsession']!=$modelname)
		{
		$p2=strtoupper( preg_replace('/\s+/', '',$post['modelname']));
		$repqry="SELECT REPLACE(  `modelname` ,  ' ',  '' ) AS modelname  FROM  `vehiclemodel` where modelname = '".$p2."' or modelname = '".$post['modelname']."' or modelcode = '".$p2."' or modelcode = '".$post['modelname']."'";
		$repres= mysql_query($repqry) or die (mysql_error());
		$codenamedcheck=mysql_num_rows($repres);
		}
			if($codenamedcheck>0)
			{
			?>
            <script type="text/javascript">
			alert("Duplicate entry!");
			</script>
       	  	<?
			}
		else
		{
			$post['user_id'] = $_SESSION['username'];
		date_default_timezone_set ("Asia/Calcutta");
		$post['m_date']= date("y/m/d : H:i:s", time());
		
			$wherecon= "modelcode ='".$post['modelcode']."'";
			$news->editNews($post,$tname,$wherecon);
			
			
				$mkrow = mysql_query("SELECT Status,Franchiseecode FROM productvehiclemodelupload where Code='".$post['modelcode']."'");
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
			$wherecon= "Code='".$post['modelcode']."' AND Franchiseecode='".$val['Franchiseecode']."'";
			$news->editNews($spost1,$stname,$wherecon);
			}	
			unset($_SESSION['vehmodelsession']);
						
						?>
            <script type="text/javascript">
			alert("Updated Sucessfully!",'vehiclemodel.php');
		//	setInterval(function(){document.location='vehiclemodel.php';},2000);
			</script>
            <?
		}
					
		}
		else
		{
			?>
            <script type="text/javascript">
			alert("Enter Mandatory Fields!");//document.location='vehiclemodel.php';
			</script>
            <?
		}
	}
	
/// EDIT LINK FUNCTION 
if(!empty($_GET['edi']))
{
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
$prmaster =$_GET['edi'];

//$cont->connect();
$result=mysql_query("SELECT * FROM vehiclemodel where modelcode ='".$prmaster."'");

$myrow1 = mysql_num_rows($result);//mysql_fetch_array($retval);

		if($myrow1==0)	
		{
			?>
            <script type="text/javascript">
			alert("No Data Found!",'vehiclemodel.php');
			</script>
   			<?
		}
		else
		{
		   $myrow = mysql_fetch_array($result);
		   $modelcode = $myrow['modelcode'];
		   $modelname = $myrow['modelname'];
		   $pgg= mysql_query("select MakeName from vehiclemakemaster where MakeNo='".$myrow['MakeName']."' ")  ;
		$record11 = mysql_fetch_array($pgg);
        $MakeName = $record11['MakeName'];
		  // $MakeName = $myrow['MakeName'];
		   $pgg1= mysql_query("select segmentname from vehiclesegmentmaster where segmentcode='".$myrow['segmentname']."' ")  ;
		$record111 = mysql_fetch_array($pgg1);
        $segmentname = $record111['segmentname'];
		  // $segmentname = $myrow['segmentname']
		  $_SESSION['vehmodelsession']= $myrow['modelname'];
		  
		  $pgg= mysql_query("select ProductGroup from productgroupmaster where ProductCode='".$myrow['ProductGroup']."' ")  ;
		
		$record11 = mysql_fetch_array($pgg);
      $productgroup = $record11['ProductGroup'];
		  
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
			alert("Select data to delete!",'vehiclemodel.php');
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
		
		$repqry1="select modelcode FROM vehiclemodel WHERE modelcode='".$prodidd."' and EXISTS( SELECT VehicleorInverterModel FROM  `serialnumbermaster` WHERE VehicleorInverterModel='".$prodidd."')";
		$repres= mysql_query($repqry1) or die (mysql_error());
		$myrow1 = mysql_num_rows($repres);//mysql_fetch_array($retval);

		if($myrow1==0)	
		{
			
			$mkrow = mysql_query("SELECT Status FROM productvehiclemodelupload where Code='".$prodidd."' and Status !='0'");
			$val=mysql_num_rows($mkrow);
					if($val==0)
					{
					$wherec= "Code='".$prodidd."' ";
					$news->deleteNews($stname,$wherec);	
					
					$wherecon= "modelcode ='".$prodidd."'";
					$news->deleteNews($tname,$wherecon);
					
					?>
					<script type="text/javascript">
					alert("Deleted  Successfully!",'vehiclemodel.php');
					</script>
					<?
					}
					else
					{
					?>
					<script type="text/javascript">
					alert("You Can't delete already send to Franchisee ",'vehiclemodel.php');
					</script>
					<?	
					}
		}
else
		{

		?>
            <script type="text/javascript">
			alert("you can't delete already used in other masters!",'vehiclemodel.php');
			</script>
   			<?
		}
		}
}
}


$_SESSION['type']=NULL;
	$ffquery='select * from vehiclemodel';

if(isset($_POST['PDF']))
{
//	header('Content-type: application/vnd.ms-excel');
//    header("Content-Dispos..ition: attachment; filename=test.xls");
//    header("Pragma: no-cache");
//    header("Expires: 0");

$select=$_POST['Type'];
if(!empty($_POST['codes'])&&!empty($_POST['names']))
	{
		$condition="SELECT * FROM vehiclemodel WHERE modelcode like'%".$_POST['codes']."%' AND modelname like'".
		$_POST['names']."%'order by id DESC";
		$ffquery=$condition;
	}
	else if(!empty($_POST['codes'])&&empty($_POST['names']))
	{
		$condition="SELECT * FROM vehiclemodel WHERE modelcode like'".$_POST['codes']."%'order by id DESC";
		$ffquery=$condition;
	}
	else if(!empty($_POST['names'])&&empty($_POST['codes']))
	{
		$condition="SELECT * FROM vehiclemodel WHERE modelname like'".$_POST['names']."%'order by id DESC";
		$ffquery=$condition;
	}
	else
	{
		
		$condition="SELECT * FROM vehiclemodel order by id DESC";$ffquery=$condition;
	}
if($select=='PDF')
{
	$_SESSION['type']='PDF';
	$_SESSION['query']=$ffquery;
	$myFile = "testFile.txt";
	$fh = fopen($myFile, 'w') or die("can't open file");
	$stringData =NULL;
	$myquery = mysql_query($ffquery);
	while( $myrecord = mysql_fetch_array($myquery))
   {
	   $groupgg="SELECT MakeName FROM vehiclemakemaster where MakeNo='".$myrecord['MakeName']."'";
	   $groupgg1 = mysql_query($groupgg);
	 $cntnog=mysql_num_rows($groupgg1);
	 if($cntnog==1)
	  {
		   	$groupgg2 = mysql_fetch_array($groupgg1);
			$testtemp=$groupgg2['MakeName'];
	   }
	   else
	   {
		   $testtemp ="";
	   }
	    $group="SELECT segmentname FROM vehiclesegmentmaster where segmentcode='".$myrecord['segmentname']."'";
	   $gg1 = mysql_query($group);
	 $nog=mysql_num_rows($gg1);
	 if($nog==1)
	  {
		   	$group2 = mysql_fetch_array($gg1);
			$test=$group2['segmentname'];
	   }
	   else
	   {
		   $test ="";
	   }
	   $groupselct="SELECT ProductGroup FROM productgroupmaster where ProductCode='".$myrecord['ProductGroup']."'";
	   $groupselct1 = mysql_query($groupselct);
	   $cntno=mysql_num_rows($groupselct1);
	   if($cntno==1)
   {
		   	$groupselct12 = mysql_fetch_array($groupselct1);
			$testtempp=$groupselct12['ProductGroup'];
	   }
	    else
	   {
		   $testtempp ="";
	   }
$stringData =$myrecord['modelcode']."\t ;".$myrecord['modelname']."\t ;".$testtemp."\t ;".$test."\t;".$testtempp."\t\n";
		fwrite($fh, $stringData);
			
	}
//	
	fclose($fh);
	header('Location:ExportVehicleModel.php');
}
elseif($select=='Excel')
{
	$_SESSION['type']='Excel';
	$_SESSION['query']=$ffquery;

	header('Location:ExportVehicleModel.php');
}
elseif($select=='Document')
{
	$_SESSION['type']='Document';
	$_SESSION['query']=$ffquery;
	header('Location:ExportVehicleModel.php');
}
	
}
if(isset($_POST['Cancel']))
{
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
	header('Location:vehiclemodel.php');
}

?>

 <script type="text/javascript">
function validatemodelcode(key)
{
	var object = document.getElementById('modelcode');
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

function validatemodelname(key)
{
	var object = document.getElementById('modelname');
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
<title><?php echo $_SESSION['title']; ?> || Vehicle Model Master</title>
</head>
 <?php 
  if(empty($_SESSION['codesval']) && empty($_SESSION['namesval']))
{
 if(!empty($_GET['edi'])){?>
 
 <body class="default" onLoad="document.form1.modelname.focus()">

<? }else{?>


<body class="default" onLoad="document.form1.modelcode.focus()">

 <? } 
}else{?>
<body class="default" onLoad="document.form1.codes.focus()">

 <? } ?>
 
 <center>

<?php include("../../menu.php")?>

<!--Third Block - Container-->
<div style="width:100%; height:auto; float:none;">
     <div style="width:980px; height:auto; float:none; margin-top:8px;" class="container">
                  
          <div style="width:950px; height:auto; float:left; margin-bottom:10px; margin-left:15px;" id="center">  
            <!-- form id start-->
             <form method="POST" action="<?php $_PHP_SELF ?>" name="form1" id="frm1">
            <div style="width:930px; height:auto;   min-height: 250px; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;">
                    
						<div style="width:930px; height:25px; float:left;  margin-left:0px;" class="head">
						<p>Vehicle Model Master</p>
						</div>
              <!-- main row 1 start-->     
                    <div style="width:925px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
                     <!-- col1 -->   
                           <div style="width:400px; height:auto; padding-bottom:5px; float:left; " class="cont">
                             <!--Row1 -->  
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Vehicle Model Code</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                              <?php if(!empty($_GET['edi']))
							{?>
                           <input type="text" name="modelcode" id="modelcode" style="border-style:hidden; background:#f5f3f1;text-transform:uppercase;"   readonly="readonly" onKeyPress="return validatemodelcode(event)" value="<?php echo $modelcode;?>" onChange="return codetrim(this)" />
                            <? } 
							else { ?>
                                 <input type="text" name="modelcode" id="modelcode" maxlength="15"  onKeyPress="return validatemodelcode(event)" value="<?php echo $modelcode;?>" onChange="return codetrim(this)" style="text-transform:uppercase;" />
                                   <?
							}
							?>
                                 
                               </div>
 							<!--Row1 end-->                         
                            <!--Row2 -->  
                               <div style="width:145px; height:30px; float:left;  margin-top:5px; margin-left:3px;">
                                  <label>Vehicle Model Name</label><label style="color:#F00;">*</label>
                               </div>
                                <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                      <input type="text" name="modelname" id="modelname" maxlength="50" onKeyPress="return validatemodelname(event)" value="<?php echo $modelname;?>" onChange="return trim(this)" style="text-transform:uppercase;" />
                               </div>
                             <!--Row2 end--> 
                               <!--Row1 -->  
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Vehicle Make</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                              
                              <select name="MakeName" id="MakeName" >
                                       <option value="<?php echo $MakeName;?>"><? if(!empty($MakeName)){ echo $MakeName;}else{?> ----Select---- <? } ?></option>
                                     <?
                                                                                
                                        $que = mysql_query("SELECT MakeName FROM vehiclemakemaster order by MakeName asc");
                                       
                                     while( $record = mysql_fetch_array($que))
                                     {
									  if($MakeName!=$record['MakeName'])
									  {	      
                                       echo "<option value=\"".$record['MakeName']."\">".$record['MakeName']."\n "; 
									  }
                                     }
                                    ?>
                                          </select>
                              
                              
                              
                              
                              
                               </div>
 							<!--Row1 end-->                         
                            
                           </div>    
                            <!-- col1 end -->                          
                     <!-- col2 -->   
                           <div style="width:400px; height:auto; float:left; padding-bottom:5px; margin-left:2px;" class="cont"> 
                           <!--Row2 -->  
                               <div style="width:145px; height:30px; float:left;  margin-top:5px; margin-left:3px;">
                                  <label>Vehicle Segment</label><label style="color:#F00;">*</label>
                               </div>
                                <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                     
                              <select name="segmentname" id="segmentname" >
                                       <option value="<?php echo $segmentname;?>"><? if(!empty($segmentname)){ echo $segmentname;}else{?> ----Select---- <? } ?></option>
                                     <?
                                                                                
                                        $que = mysql_query("SELECT segmentname FROM vehiclesegmentmaster order by segmentname asc");
                                       
                                     while( $record = mysql_fetch_array($que))
                                     {
									  if($segmentname!=$record['segmentname'])
									  {	      
                                       echo "<option value=\"".$record['segmentname']."\">".$record['segmentname']."\n ";
									  }
                                     }
                                    ?>
                                          </select>
                              
                              
                              
                              
                               </div>
                             <!--Row2 end-->  
                              <!--Row3 -->  
                               <div style="width:145px; height:30px; float:left;  margin-top:5px; margin-left:3px;">
                                 <label>Product Group</label><label style="color:#F00;">*</label>
                               </div>
                                 <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                  <select name="productgroup" id="productgroup">
                                       <option value="<?php echo $productgroup;?>"><? if(!empty($productgroup)){ echo $productgroup;}else{?> ----Select---- <? } ?></option>
                                     <?
                                                                                
                                        $que = mysql_query("SELECT ProductGroup FROM productgroupmaster order by ProductGroup asc");
                                       	
                                     while( $record = mysql_fetch_array($que))
                                     { 
									  if($productgroup!=$record['ProductGroup'])
									  {     
                                       echo "<option value=\"".$record['ProductGroup']."\">".$record['ProductGroup']."\n ";                      
                                      }
									 }
                                    ?>
                                          </select>
                               </div>
                             <!--Row3 end-->                         
                           </div>                             
                     <!-- col2 end--> 
                     
                     <!-- col3 -->   
                                
                     <!-- col3 --> 
                                                                                                     
                    </div>
                  
                
				</div>
                <!-- main row 1 end-->
                
                
             
                    <!--Main row 2 start-->
                <div style="width:1000px; height:60px; float:left; margin-left:8px; margin-top:-60px;">
                             
					<div style="width:235px; height:50px; float:left;  margin-left:14px; margin-top:0px;" id="center1">
						   
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
                          
                           <div style="width:100px; height:32px; float:left;margin-top:16px; margin-left:10px;">
						  <input name="Cancel" type="submit" class="button" value="Reset">
				           </div>   
                                              
                                                   
				     </div>	
                         
                          <div style="width:640px; height:50px; float:left;  margin-left:25px; margin-top:0px;" class="cont" id="center2">
                              <!--Row1 -->  
                               <div style="width:80px; height:30px; float:left; margin-left:20px; margin-top:9px;" >
                                   <label>Vehicle Model Code</label>
                               </div>
                               <div style="width:145px; height:30px;  float:left; margin-left:10px; margin-top:16px;">
                                 <input type="text" name="codes" id="codes" onKeyPress="searchKeyPress(event);" value="<? echo $_SESSION['codesval'] ?>"/>
                               </div>
                             <!--Row1 end-->  
                             
                               <!--Row2 -->  
                               <div style="width:80px; height:30px; float:left; margin-left:20px; margin-top:9px;">
                                  <label>Vehicle Model Name</label>
                               </div>
                               <div style="width:145px; height:30px;  float:left; margin-left:10px; margin-top:16px;" >
                                  <input type="text" name="names" onKeyPress="searchKeyPress(event);" value="<? echo $_SESSION['namesval'] ?>"/>
                               </div>
                             <!--Row2 end-->
                             
                             <div style="width:83px; height:32px; margin-left:10px; float:left; margin-top:16px;">
                                <input type="submit" name="Search" id="Search" value="Search" class="button"/>
                               </div>  
                          </div> 
                </div>
                
                <!--Main row 2 end-->
            <div style="width:900px; height:auto; padding-bottom:8px; margin-top:20px; float:left; margin-left:25px; overflow:auto;" class="grid">  
           <table align="center" id="datatable1" class="sortable" border="1" width="900px" style="overflow:auto;">   
     <tr style="white-space:nowrap;">
      <?  if(($row['deleterights'])=='Yes')
	 {
	?>   
     <td class="sorttable_nosort" style=" font-weight:bold; text-align:center" width="8px">
     <input type='checkbox' name='checkall' id="checkall" onclick='checkedAll(frm1);'></td>
   <? 
   }
    if(($row['editrights'])=='Yes') 
	  { 
	 ?>
     <td class="sorttable_nosort" style=" font-weight:bold; text-align:center" width="12px">Action</td>
      <? 
	  } 
	  ?>
     <td style=" font-weight:bold;">Vehicle Model Code</td>
     <td style=" font-weight:bold;">Vehicle Model Name</td>
     <td style=" font-weight:bold;">Vehicle Make</td>
     <td style=" font-weight:bold;">Vehicle Segment</td>
     <td style=" font-weight:bold;">Product Group </td></tr>
 <?php
      // This while will loop through all of the records as long as there is another record left. 
      while( $record = mysql_fetch_array($query))
    { // Basically as long as $record isn't false, we'll keep looping.
      // You'll see below here the short hand for echoing php strings.
      // <?=$record[key] - will display the value for that array.
    ?>
    
     <tr>
      <?  if(($row['deleterights'])=='Yes')
	 { ?>
     <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center"><input name="checkbox[]" type="checkbox" onChange="test();" id="checkbox[]" value="<? echo $record['modelcode'];?>"></td>
      <? } 
	 if(($row['editrights'])=='Yes') 
	 { 
	 ?>
     <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center"> <a style="color:#0360B2" name="edit" href="vehiclemodel.php?<?php if(($row['editrights'])=='Yes') { echo 'edi='; echo $record['modelcode'];} else echo 'permiss'; ?>">Edit</a></td>
     <? 
	  } 
	  ?>
     <td  bgcolor="#FFFFFF" ><?=$record['modelcode']?> </td>
     <td  bgcolor="#FFFFFF" ><?=$record['modelname']?></td>
     <td  bgcolor="#FFFFFF" >
     <? $check1= mysql_query("select MakeName from vehiclemakemaster where MakeNo='".$record['MakeName']."' ")  ;
		$check1record = mysql_fetch_array($check1);
       echo $check1record['MakeName']; ?> 
	
     </td>
     <td  bgcolor="#FFFFFF" >
   <?  $check2= mysql_query("select segmentname from vehiclesegmentmaster where segmentcode='".$record['segmentname']."' ")  ;
		$check2record = mysql_fetch_array($check2);
       echo $check2record['segmentname']; ?>
	 
     </td>
     
      <td  bgcolor="#FFFFFF" align="left">
    <?php $pg= mysql_query("select ProductGroup from productgroupmaster where ProductCode='".$record['ProductGroup']."' ")  ;
		$record1 = mysql_fetch_array($pg);
       echo $record1['ProductGroup']; ?>
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
             <!-- form id start end-->      
    </form>
          
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