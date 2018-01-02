<?php 
	include '../../functions.php';
	sec_session_start();
	require_once '../../masterclass.php';
	include("../../header.php");

if(login_check($mysqli) == false) {
 
  header('Location:../../index.php');// Redirect to login page!
 
} else
{
	global $segmentcode,$segmentname,$tname,$scode,$sname,$proname;
	$scode = 'segmentcode';
	$sname = 'segmentname';
	$tname	= "vehiclesegmentmaster";
	require_once '../../searchfun.php';
	$stname="productvehiclesegmentupload";
	$mtable1="productvehiclesegmentupload";
	require_once '../../masterclass.php'; // Include The News Class
	require_once '../../paginationfunction.php';
	$news = new News(); // Create a new News Object
	$newsRecordSet = $news->getNews($tname);
	$_SESSION['type']=NULL;
	$ffquery='select * from vehiclesegmentmaster';
	$pagename = "Vehiclesegment";
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
			alert("you are not allowed to do this action!",'vehiclesegmentmaster.php');	
			</script>
         <?
		
	}
	if(isset($_GET['permiss']))
	{
	?>
            <script type="text/javascript">
			alert("you are not allowed to do this action!",'vehiclesegmentmaster.php');	
			</script>
         <?
		
	}//Page Verification Code and User Verification
	
	
	
	
	
    if(isset($_POST['Save'])) // If the submit button was clicked
    {
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
		$post['segmentcode'] =strtoupper(str_replace('&', 'and',$_POST['segmentcode']));
		$post['segmentname'] =strtoupper(str_replace('&', 'and',$_POST['segmentname']));
	 
        $segmentcode = $post['segmentcode'];
		$segmentname = $post['segmentname'];
       
 
        // This will make sure its displayed
		if(!empty($_POST['segmentcode'])&&!empty($_POST['segmentname']))
		{   
		$p1=strtoupper( preg_replace('/\s+/', '',$post['segmentcode']));
	$p2=strtoupper( preg_replace('/\s+/', '',$post['oemname']));
	$cnduplicate=0;
	$repqry="SELECT REPLACE( `segmentcode` ,  ' ',  '' ) AS segmentcode, REPLACE(  `segmentname` ,  ' ',  '' ) AS segmentname FROM vehiclesegmentmaster where segmentname = '".$p2."' or segmentname = '".$post['segmentname']."' or segmentcode = '".$p2."' or segmentcode = '".$post['segmentname']."' or segmentcode = '".$p1."' or segmentcode = '".$post['segmentcode']."' or segmentname = '".$p1."' or segmentname = '".$post['segmentcode']."'";
	$repres= mysql_query($repqry) or die (mysql_error());
	$cnduplicate=mysql_num_rows($repres);
		if($cnduplicate>0 || ($post['segmentcode']==$post['segmentname']))
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
		 
		$spost['Code']  =$post['segmentcode'];
		$spost['Masters']="vehiclesegmentmaster";
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
			alert("Created Sucessfully!",'vehiclesegmentmaster.php');
			//setInterval(function(){document.location='vehiclesegmentmaster.php';},2000);
			//document.location='vehiclesegmentmaster.php';
			</script>
         <?
		}
		}
		else
		{
			?>
            <script type="text/javascript">
			alert("Enter Mandatory Fields!");//document.location='vehiclesegmentmaster.php';
			</script>
            <?
		}
    }
	 if(isset($_POST['Update'])) // If the submit button was clicked
    {
		unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
		$post['segmentcode'] =strtoupper(str_replace('&', 'and',$_POST['segmentcode']));
		$post['segmentname'] =strtoupper(str_replace('&', 'and',$_POST['segmentname']));
	 
        $segmentcode = $post['segmentcode'];
		$segmentname = $post['segmentname'];
     
 
        // This will make sure its displayed
		if(!empty($_POST['segmentcode'])&&!empty($_POST['segmentname']))
		{ 
		$codenamedcheck=0;
		if($_SESSION['vehsegmentsession']!=$segmentname)
		{
		$p2=strtoupper( preg_replace('/\s+/', '',$post['segmentname']));
		$repqry="SELECT REPLACE(  `segmentname` ,  ' ',  '' ) AS segmentname  FROM  `vehiclesegmentmaster` where segmentname = '".$p2."' or segmentname = '".$post['segmentname']."' or segmentcode = '".$p2."' or segmentcode = '".$post['segmentname']."'";
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
				$myrow1=0;
		$result="SELECT * FROM vehiclesegmentmaster where segmentcode ='".$post['segmentcode']."'";
	
		
			$sql1 = mysql_query($result) or die (mysql_error());
 
		
			$myrow1 = mysql_num_rows($sql1);//mysql_fetch_array($retval);
			if($myrow1==0)
			{
			?>
            <script type="text/javascript">
			
			alert("You are not allowed to save a new record using update!");
			</script>
			<?
			}
			else{
				$post['user_id'] = $_SESSION['username'];
		date_default_timezone_set ("Asia/Calcutta");
		$post['m_date']= date("y/m/d : H:i:s", time());
		
			$wherecon= "segmentcode ='".$post['segmentcode']."'";
			$news->editNews($post,$tname,$wherecon);
			
			$mkrow = mysql_query("SELECT Status,Franchiseecode FROM productvehiclesegmentupload where Code='".$post['segmentcode']."'");
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
			$wherecon= "Code='".$post['segmentcode']."' AND Franchiseecode='".$val['Franchiseecode']."'";
			$news->editNews($spost1,$stname,$wherecon);
			}	
				unset($_SESSION['vehsegmentsession']);		
						?>
            <script type="text/javascript">
			alert("Updated Sucessfully!",'vehiclesegmentmaster.php');
			//setInterval(function(){document.location='vehiclesegmentmaster.php';},2000);
			//document.location='vehiclesegmentmaster.php';
			</script>
            <?
					
				}	
			}
		}
		else
		{
			?>
            <script type="text/javascript">
			alert("Enter Mandatory Fields!");//document.location='vehiclesegmentmaster.php';
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
$result=mysql_query("SELECT * FROM vehiclesegmentmaster where segmentcode ='".$prmaster."'");

$myrow1 = mysql_num_rows($result);//mysql_fetch_array($retval);

		if($myrow1==0)	
		{
			?>
            <script type="text/javascript">
			alert("No Data Found!!",'vehiclesegmentmaster.php');
			</script>
   			<?
		}
		else
		{
		   $myrow = mysql_fetch_array($result);
		
		   $segmentcode = $myrow['segmentcode'];
		   $segmentname = $myrow['segmentname'];
		  $_SESSION['vehsegmentsession']= $myrow['segmentname'];
		 
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
			alert("Select data to delete!",'vehiclesegmentmaster.php');
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
		$repqry1="select segmentcode FROM vehiclesegmentmaster WHERE segmentcode='".$prodidd."' and EXISTS( SELECT segmentname FROM  `vehiclemodel` WHERE segmentname='".$prodidd."')";
		$repres= mysql_query($repqry1) or die (mysql_error());
		$myrow1 = mysql_num_rows($repres);//mysql_fetch_array($retval);

		if($myrow1==0)	
		{
					$mkrow = mysql_query("SELECT Status FROM productvehiclesegmentupload where Code='".$prodidd."' and Status !='0'");
					$val=mysql_num_rows($mkrow);
					if($val==0)
					{
					$wherec= "Code='".$prodidd."'";
					$news->deleteNews($stname,$wherec);	
					
					$wherecon= "segmentcode ='".$prodidd."'";
					$news->deleteNews($tname,$wherecon);
					
					?>
					<script type="text/javascript">
					alert("Deleted  Successfully!",'vehiclesegmentmaster.php');
					</script>
					<?		
					}
					else
					{
					?>
					<script type="text/javascript">
					alert("You Can't delete already send to Franchisee ",'vehiclesegmentmaster.php');
					</script>
					<?	
					}
		}
		else
		{

		?>
            <script type="text/javascript">
			alert("you can't delete already used in other masters!",'vehiclesegmentmaster.php');
			</script>
   			<?
		}
		}
}
}


if(isset($_POST['PDF']))
{
//	header('Content-type: application/vnd.ms-excel');
//    header("Content-Dispos..ition: attachment; filename=test.xls");
//    header("Pragma: no-cache");
//    header("Expires: 0");

$select=$_POST['Type'];
if(!empty($_POST['codes'])&&!empty($_POST['names']))
	{
		$condition="SELECT * FROM vehiclesegmentmaster WHERE segmentcode like'".$_POST['codes']."%' OR segmentname like'".
		$_POST['names']."%'order by id DESC";
		$ffquery=$condition;
	}
	else if(!empty($_POST['codes'])&&empty($_POST['names']))
	{
		$condition="SELECT * FROM vehiclesegmentmaster WHERE segmentcode like'".$_POST['codes']."%'order by id DESC";
		$ffquery=$condition;
		
	}
	else if(!empty($_POST['names'])&&empty($_POST['codes']))
	{
		$condition="SELECT * FROM vehiclesegmentmaster WHERE segmentname like'".$_POST['names']."%'order by id DESC";
		$ffquery=$condition;
	}
	else
	{
		
		$condition="SELECT * FROM vehiclesegmentmaster order by id DESC";
		$ffquery=$condition;
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
$stringData =$myrecord['segmentcode']."\t ;".$myrecord['segmentname']."\t\n";
		fwrite($fh, $stringData);
			
	}
//	
	fclose($fh);
	header('Location:ExportVehicleSegment.php');
}
elseif($select=='Excel')
{
	$_SESSION['type']='Excel';
	$_SESSION['query']=$ffquery;

	header('Location:ExportVehicleSegment.php');
}
elseif($select=='Document')
{
	$_SESSION['type']='Document';
	$_SESSION['query']=$ffquery;
	header('Location:ExportVehicleSegment.php');
}
	
}	

if(isset($_POST['Cancel']))
{
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
	header('Location:vehiclesegmentmaster.php');
}

?>
<script type="text/javascript">

function validatesegmentcode(key)
{
	var object = document.getElementById('segmentcode');
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
function validatesegmentname(key)
{
	var object = document.getElementById('segmentname');
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
<title><?php echo $_SESSION['title']; ?> || Vehicle Segment Master</title>
</head>
 <?php 
  if(empty($_SESSION['codesval']) && empty($_SESSION['namesval']))
{
 if(!empty($_GET['edi'])){?>
 
 <body class="default" onLoad="document.form1.segmentname.focus()">

<? }else{?>


<body class="default" onLoad="document.form1.segmentcode.focus()">

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
						<p>Vehicle Segment Master</p>
						</div>
              <!-- main row 1 start-->     
                    <div style="width:925px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
                     <!-- col1 -->   
                          <div style="width:400px; height:auto; padding-bottom:5px; float:left; " class="cont">
                             <!--Row1 -->  
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Vehicle Segment Code</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                  <input type="text" name="segmentcode" id="segmentcode"   <? if(!empty($_GET['edi'])) { ?> readonly="readonly" style="border-style:hidden; background:#f5f3f1;text-transform:uppercase;" <? } else { ?>style="text-transform:uppercase;"  <? } ?>  onkeypress="return validatesegmentcode(event)" value="<?php echo $segmentcode;?>" onChange="return codetrim(this)" maxlength="15" />
                               </div>
 							<!--Row1 end-->                         
                            <!--Row2 -->  
                               <div style="width:145px; height:30px; float:left;  margin-top:5px; margin-left:3px;">
                                  <label>Vehicle Segment Name</label><label style="color:#F00;">*</label>
                               </div>
                                <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                        <input type="text" name="segmentname" id="segmentname" maxlength="50" onKeyPress="return validatesegmentname(event)"  value="<?php echo $segmentname;?>" onChange="return trim(this)" style="text-transform:uppercase;" />
                               </div>
                             <!--Row2 end-->    
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
                <div style="width:1000px; height:60px; float:left; margin-left:8px; margin-top:-130px;">
                             
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
                         
                          <div style="width:640px; height:50px; float:left;   margin-left:25px; margin-top:0px;" class="cont" id="center2">
                              <!--Row1 -->  
                               <div style="width:60px; height:30px; float:left; margin-left:20px; margin-top:16px;" >
                                   <label>Code</label>
                               </div>
                               <div style="width:145px; height:30px;  float:left; margin-left:10px; margin-top:16px;">
                                 <input type="text" name="codes" id="codes" onKeyPress="searchKeyPress(event);" value="<? echo $_SESSION['codesval']?>"/>
                               </div>
                             <!--Row1 end-->  
                             
                               <!--Row2 -->  
                               <div style="width:60px; height:30px; float:left; margin-left:20px; margin-top:16px;">
                                  <label>Name</label>
                               </div>
                               <div style="width:145px; height:30px;  float:left; margin-left:10px; margin-top:16px;" >
                                  <input type="text" name="names" onKeyPress="searchKeyPress(event);" value="<? echo $_SESSION['namesval']?>"/>
                               </div>
                             <!--Row2 end-->
                             
                             <div style="width:83px; height:32px; float:left; margin-left:10px; margin-top:16px;">
                                <input type="submit" name="Search" id="Search" value="Search" class="button"/>
                               </div>  
                       </div>
                </div>
                
                <!--Main row 2 end-->
             <div style="width:900px; height:auto; padding-bottom:8px; margin-top:-50px; float:left; margin-left:20px; overflow:auto;" class="grid">
                                <table align="center" id="datatable1" class="sortable" bgcolor="#FF0000" border="1" width="870px">
     <tr>
     <?  if(($row['deleterights'])=='Yes')
	 {
	?>  
     <td class="sorttable_nosort" style=" font-weight:bold; text-align:center" width="8px">
     <input type='checkbox' name='checkall' id="checkall"  onclick='checkedAll(frm1);'></td>
      <? 
   }
    if(($row['editrights'])=='Yes') 
	  { 
	 ?>
     <td class="sorttable_nosort" style=" font-weight:bold; text-align:center" width="12px">Action</td>
      <? 
	  } 
	  ?>
     <td style=" font-weight:bold;">Vehicle Segment Code</td>
     <td style=" font-weight:bold;">Vehicle Segment Name</td>
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
	 { ?>
     <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center" ><input name="checkbox[]" type="checkbox" id="checkbox[]" onChange="test();"  value="<? echo $record['segmentcode']; ?>"></td>
        <? } 
	 if(($row['editrights'])=='Yes') 
	 { 
	 ?>
       <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center" > <a style="color:#0360B2" name="edit" href="vehiclesegmentmaster.php?<?php if(($row['editrights'])=='Yes') { echo 'edi='; echo $record['segmentcode'];} else echo 'permiss'; ?>">Edit</a></td>
     <? 
	  } 
	  ?>
    <td  bgcolor="#FFFFFF"> <?=$record['segmentcode']?> </td>
     <td  bgcolor="#FFFFFF"><?=$record['segmentname']?></td>
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