<?php 
	include '../../functions.php';
	sec_session_start();
	require_once '../../masterclass.php';
	include("../../header.php");

if(login_check($mysqli) == false) {
 
  header('Location:../../index.php');// Redirect to login page!
 
} else
{
	global $MakeNo,$MakeName,$tname,$scode,$sname;
	$scode = 'MakeNo';
	$sname = 'MakeName';
	$tname	= "vehiclemakemaster";
	require_once '../../searchfun.php';
	$stname="productvehiclemakeupload";
	require_once '../../masterclass.php'; // Include The News Class
	require_once '../../paginationfunction.php';
	$news = new News(); // Create a new News Object
	$newsRecordSet = $news->getNews($tname);
	
	$pagename = "Vehiclemake";
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
			alert("you are not allowed to do this action!",'vehiclemakemaster.php');	
			</script>
         <?
		
	}
	if(isset($_GET['permiss']))
	{
	?>
            <script type="text/javascript">
			alert("you are not allowed to do this action!",'vehiclemakemaster.php');	
			</script>
         <?
		
	}//Page Verification Code and User Verification
	
	if(isset($_POST['Save'])) // If the submit button was clicked
    {
		unset($_SESSION['codesval']);
		unset($_SESSION['namesval']);
		$post['MakeNo']   =strtoupper(str_replace('&', 'and',$_POST['MakeNo']));
		$post['MakeName'] =strtoupper(str_replace('&', 'and',$_POST['MakeName']));
		
        $MakeNo = $post['MakeNo'];
		$MakeName = $post['MakeName'];
        
        // This will make sure its displayed
		 
		 if(!empty($_POST['MakeNo'])&&!empty($_POST['MakeName']))
		 {   
		$p1=strtoupper( preg_replace('/\s+/', '',$post['MakeNo']));
	$p2=strtoupper( preg_replace('/\s+/', '',$post['MakeName']));
	$cnduplicate=0;
	$repqry="SELECT REPLACE( `MakeNo` ,  ' ',  '' ) AS MakeNo, REPLACE(  `MakeName` ,  ' ',  '' ) AS MakeName FROM vehiclemakemaster where MakeName = '".$p2."' or MakeName = '".$post['MakeName']."' or MakeNo = '".$p2."' or MakeNo = '".$post['MakeName']."' or MakeNo = '".$p1."' or MakeNo = '".$post['MakeNo']."' or MakeName = '".$p1."' or MakeName = '".$post['MakeNo']."'";
	$repres= mysql_query($repqry) or die (mysql_error());
	$cnduplicate=mysql_num_rows($repres);
		if($cnduplicate>0 || ($post['MakeNo']==$post['MakeName']))
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
		
		$spost['Code']  =$post['MakeNo'];
		$spost['Masters']="vehiclemakemaster";
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
			alert("Created Sucessfully!",'vehiclemakemaster.php');
			//setInterval(function(){document.location='vehiclemakemaster.php';},2000);
			</script>
         <?
		}
		}
		else
		{
			?>
            <script type="text/javascript">
			alert("Enter Mandatory Fields!");//document.location='vehiclemakemaster.php';
			</script>
            <?
		}
    }
	 if(isset($_POST['Update'])) // If the submit button was clicked
    {
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
		$post['MakeNo']   =strtoupper(str_replace('&', 'and',$_POST['MakeNo']));
		$post['MakeName'] =strtoupper(str_replace('&', 'and',$_POST['MakeName']));
		
        $MakeNo = $post['MakeNo'];
		$MakeName = $post['MakeName'];
		
		$result="SELECT MakeName FROM vehiclemakemaster where MakeNo ='".$_POST['MakeNo']."'";
			$sql1 = mysql_query($result) or die (mysql_error());
			$myrow = mysql_fetch_array($sql1);
		  // $existinggroup = $myrow[0];
        // This will make sure its displayed
		if(!empty($_POST['MakeNo'])&&!empty($_POST['MakeName']))
		{ 
		$codenamedcheck=0;
		if($_SESSION['vehmakesession']!=$MakeName)
		{
		$p2=strtoupper( preg_replace('/\s+/', '',$post['MakeName']));
		$repqry="SELECT REPLACE(  `MakeName` ,  ' ',  '' ) AS MakeName  FROM  `vehiclemakemaster` where MakeName = '".$p2."' or MakeName = '".$post['MakeName']."' or MakeNo = '".$p2."' or MakeNo = '".$post['MakeName']."'";
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
				$result="SELECT * FROM vehiclemakemaster where MakeNo ='".$_POST['MakeNo']."'";
		$sql1 = mysql_query($result) or die (mysql_error());
 		$myrow1 = mysql_num_rows($sql1);
		if($myrow1==0)	
		{
			?>
            <script type="text/javascript">
			alert("You are not allowed to save a new record using Update!");
			</script>
   			<?
		}
		else
		{
			$post['user_id'] = $_SESSION['username'];
		date_default_timezone_set ("Asia/Calcutta");
		$post['m_date']= date("y/m/d : H:i:s", time());
		
			$wherecon= "MakeNo='".$post['MakeNo']."'";
			$news->editNews($post,$tname,$wherecon);
			
			
			$mkrow = mysql_query("SELECT Status,Franchiseecode FROM productvehiclemakeupload where Code='".$post['MakeNo']."'");
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
			$wherecon= "Code='".$post['MakeNo']."' AND Franchiseecode='".$val['Franchiseecode']."'";
			$news->editNews($spost1,$stname,$wherecon);
			
			}	
			unset($_SESSION['vehmakesession']);
									
			?>
            <script type="text/javascript">
			alert("Updated Sucessfully!",'vehiclemakemaster.php');
			//setInterval(function(){document.location='vehiclemakemaster.php';},2000);
			//document.location='vehiclemakemaster.php';
			</script>
            <?
					
		}
		}
		}
		else
		{
			?>
            <script type="text/javascript">
			alert("Enter Mandatory Fields!");//document.location='vehiclemakemaster.php';
			</script>
            <?
		}
	}
	
// EDIT LINK FUNCTION 

if(!empty($_GET['edi']))
{
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
$prmaster =$_GET['edi'];
$result=mysql_query("SELECT * FROM vehiclemakemaster where MakeNo ='".$prmaster."'");

$myrow1 = mysql_num_rows($result);//mysql_fetch_array($retval);

		if($myrow1==0)	
		{
			?>
            <script type="text/javascript">
			alert("No Data Found!",'vehiclemakemaster.php');
			</script>
   			<?
		}
		else
		{
		   $myrow = mysql_fetch_array($result);
		   $MakeNo = $myrow['MakeNo'];
		   $MakeName = $myrow['MakeName'];
		   $_SESSION['vehmakesession']= $myrow['MakeName'];
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
			alert("Select data to delete!",'vehiclemakemaster.php');
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
			 
				
		$repqry1="select MakeNo FROM vehiclemakemaster WHERE MakeNo='".$prodidd."' and EXISTS( SELECT MakeName FROM  `vehiclemodel` WHERE MakeName='".$prodidd."')";
		$repres= mysql_query($repqry1) or die (mysql_error());
		$myrow1 = mysql_num_rows($repres);//mysql_fetch_array($retval);

		if($myrow1==0)	
		{
					$mkrow = mysql_query("SELECT Status FROM productvehiclemakeupload where Code='".$prodidd."' and Status !='0'");
					$val=mysql_num_rows($mkrow);
					if($val==0)
					{
					$wherec= "Code='".$prodidd."' ";
					$news->deleteNews($stname,$wherec);	
					
					$wherecon= "MakeNo ='".$prodidd."'";
					$news->deleteNews($tname,$wherecon);
					
					?>
					<script type="text/javascript">
					alert("Deleted  Successfully!",'vehiclemakemaster.php');
					</script>
					<?		
					}
					else
					{
					?>
					<script type="text/javascript">
					alert("You Can't delete already send to Franchisee ",'vehiclemakemaster.php');
					</script>
					<?	
					}
		}
		else
		{

		?>
            <script type="text/javascript">
			alert("you can't delete already used in other masters!",'vehiclemakemaster.php');
			</script>
   			<?
		}
		}
}
}



if(isset($_POST['Search']))
{
if(isset($_POST['codes'])||isset($_POST['names']))
{
	$search1=$_POST['codes'];
	$search2=$_POST['names'];
	$_SESSION['codesval']=$_POST['codes'];
	$_SESSION['namesval']=$_POST['names'];
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
		$condition="SELECT * FROM vehiclemakemaster WHERE MakeNo like'".$_POST['codes']."%' or MakeName like'".
		$_POST['names']."%'";
		$ffquery=$condition;
	}
	else if(!empty($_POST['codes'])&&empty($_POST['names']))
	{
		$condition="SELECT * FROM vehiclemakemaster WHERE MakeNo like'".$_POST['codes']."%'";
		$ffquery=$condition;
	}
	else if(!empty($_POST['names'])&&empty($_POST['codes']))
	{
		$condition="SELECT * FROM vehiclemakemaster WHERE MakeName like'".$_POST['names']."%'";
		$ffquery=$condition;
	}
	else
	{
		
		$condition="SELECT * FROM vehiclemakemaster WHERE 1";
		$ffquery=$condition;
	}
	
	$refer=mysql_query($condition);
	$myrow1 = mysql_num_rows($refer);
	//mysql_fetch_array($query);
	
	$page = (int) (!isset($_GET["page"]) ? 1 :1);
    	//$limit = $myrow1+1000;
    	$startpoint = ($page * $limit) - $limit;
        //to make pagination
        $statement = "vehiclemakemaster";
		 //show records
		 $starvalue= $myrow1;
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
else
{
if(empty($_SESSION['codesval']) && empty($_SESSION['namesval']))
{		
	   $page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
    	//$limit = 10;
    	$startpoint = ($page * $limit) - $limit;
        $statement = "vehiclemakemaster"; 
		$starvalue = "";
        $query = mysql_query("SELECT * FROM {$statement} order by id desc LIMIT {$startpoint} , {$limit}");
}
else
	{
	if(!empty($_SESSION['codesval'])&&!empty($_SESSION['namesval']))
	{
		$condition="SELECT * FROM vehiclemakemaster WHERE MakeNo like'".$_SESSION['codesval']."%' or MakeName like'".
		$_SESSION['namesval']."%'";
		$ffquery=$condition;
	}
	else if(!empty($_SESSION['codesval'])&&empty($_SESSION['namesval']))
	{
		$condition="SELECT * FROM vehiclemakemaster WHERE MakeNo like'".$_SESSION['codesval']."%'";
		$ffquery=$condition;
	}
	else if(!empty($_SESSION['namesval'])&&empty($_SESSION['codesval']))
	{
		$condition="SELECT * FROM vehiclemakemaster WHERE MakeName like'".$_SESSION['namesval']."%'";
		$ffquery=$condition;
	}
	else
	{
		
		$condition="SELECT * FROM vehiclemakemaster WHERE 1";
		$ffquery=$condition;
	}
	
	$refer=mysql_query($condition);
	$myrow1 = mysql_num_rows($refer);
	//mysql_fetch_array($query);
	
	$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
    	//$limit = $myrow1+1000;
    	$startpoint = ($page * $limit) - $limit;
        //to make pagination
        $statement = "vehiclemakemaster";
		 //show records
		 $starvalue= $myrow1;
       $query = mysql_query("{$condition} LIMIT {$startpoint} , {$limit}");
}
}



$_SESSION['type']=NULL;
	$ffquery='select * from vehiclemakemaster';
if(isset($_POST['PDF']))
{
//	header('Content-type: application/vnd.ms-excel');
//    header("Content-Dispos..ition: attachment; filename=test.xls");
//    header("Pragma: no-cache");
//    header("Expires: 0");

$select=$_POST['Type'];
if(!empty($_POST['codes'])&&!empty($_POST['names']))
	{
		$condition="SELECT * FROM vehiclemakemaster WHERE MakeNo like'".$_POST['codes']."%' AND MakeName like'".
		$_POST['names']."%'order by id DESC";
		$ffquery=$condition;
	}
	else if(!empty($_POST['codes'])&&empty($_POST['names']))
	{
		$condition="SELECT * FROM vehiclemakemaster WHERE MakeNo like'".$_POST['codes']."%'order by id DESC";
		$ffquery=$condition;
	}
	else if(!empty($_POST['names'])&&empty($_POST['codes']))
	{
		$condition="SELECT * FROM vehiclemakemaster WHERE MakeName like'".$_POST['names']."%'order by id DESC";
		$ffquery=$condition;
	}
	else
	{
		
		$condition="SELECT * FROM vehiclemakemaster order by id DESC";
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
$stringData =$myrecord['MakeNo']."\t ;".$myrecord['MakeName']."\t\n";
		fwrite($fh, $stringData);
			
	}
//	
	fclose($fh);
	header('Location:ExportVehicleMake.php');
}
elseif($select=='Excel')
{
	$_SESSION['type']='Excel';
	$_SESSION['query']=$ffquery;

	header('Location:ExportVehicleMake.php');
}
elseif($select=='Document')
{
	$_SESSION['type']='Document';
	$_SESSION['query']=$ffquery;
	header('Location:ExportVehicleMake.php');
}
	
}

if(isset($_POST['Cancel']))
{
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
	header('Location:vehiclemakemaster.php');
}

?>

<script type="text/javascript">

function validateMakeNo(key)
{
	var object = document.getElementById('MakeNo');
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
function validateMakeName(key)
{
	var object = document.getElementById('MakeName');
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
<title><?php echo $_SESSION['title']; ?> || Vehicle Make Master</title>
</head>
 <?php 
  if(empty($_SESSION['codesval']) && empty($_SESSION['namesval']))
{
 if(!empty($_GET['edi'])){?>
 
 <body class="default" onLoad="document.form1.MakeName.focus()">

<? }else{?>


<body class="default" onLoad="document.form1.MakeNo.focus()">

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
						<p>Vehicle Make Master</p>
						</div>
              <!-- main row 1 start-->     
                    <div style="width:925px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
                     <!-- col1 -->   
                           <div style="width:400px; height:auto; padding-bottom:5px; float:left; " class="cont">
                             <!--Row1 -->  
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Vehicle Make Code</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                               <?php if(!empty($_GET['edi']))
							{?>
                           <input type="text" name="MakeNo" id="MakeNo" readonly="readonly" style="border-style:hidden; background:#f5f3f1;text-transform:uppercase;"  value="<?php echo $MakeNo;?>" onKeyPress="return validateMakeNo(event)" onChange="return codetrim(this)" />
                            <? } 
							else { ?>
                                 <input type="text" name="MakeNo" id="MakeNo"  value="<?php echo $MakeNo;?>" maxlength="15" onKeyPress="return validateMakeNo(event)" onChange="return codetrim(this)" style="text-transform:uppercase;" />
                                   <?
							}
							?>
                                   
                               </div>
 							<!--Row1 end-->                         
                            <!--Row2 -->  
                               <div style="width:145px; height:30px; float:left;  margin-top:5px; margin-left:3px;">
                                  <label>Vehicle Make Name</label><label style="color:#F00;">*</label>
                               </div>
                                <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                      <input type="text" name="MakeName" id="MakeName" value="<?php echo $MakeName;?>" maxlength="50" onKeyPress="return validateMakeName(event)" onChange="return trim(this)" style="text-transform:uppercase;" />
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
                         
                          <div style="width:640px; height:50px; float:left;  margin-left:25px; margin-top:0px;" class="cont" id="center2">
                              <!--Row1 -->  
                               <div style="width:80px; height:30px; float:left; margin-left:20px; margin-top:9px;" >
                                   <label>Vehicle Make Code</label>
                               </div>
                               <div style="width:145px; height:30px;  float:left; margin-left:10px; margin-top:16px;">
                                 <input type="text" name="codes" id="codes" onKeyPress="searchKeyPress(event);" value="<? echo $_SESSION['codesval'] ?>"/>
                               </div>
                             <!--Row1 end-->  
                             
                               <!--Row2 -->  
                               <div style="width:80px; height:30px; float:left; margin-left:20px; margin-top:9px;">
                                  <label>Vehicle Make Name</label>
                               </div>
                               <div style="width:145px; height:30px;  float:left; margin-left:10px; margin-top:16px;" >
                                  <input type="text" name="names" onKeyPress="searchKeyPress(event);" value="<? echo $_SESSION['namesval'] ?>"/>
                               </div>
                             <!--Row2 end-->
                             
                             <div style="width:83px; height:32px; float:left; margin-top:16px;">
                                <input type="submit" name="Search" id="Search" value="Search" class="button"/>
                               </div>  
                          </div> 
                </div>
                
                <!--Main row 2 end-->
            <div style="width:900px; height:auto; padding-bottom:8px; margin-top:-50px; float:left; margin-left:25px; overflow:auto;" class="grid">  
           <table align="center" id="datatable1" class="sortable" border="1" width="900px" style="overflow:auto;">   
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
     <td class="sorttable_nosort" style=" font-weight:bold; text-align:center" width="12px">Action</td>
      <? 
	  } 
	  ?>
     <td style=" font-weight:bold;">Vehicle Make  Code</td>
     <td style=" font-weight:bold;">Vehicle Make Name</td></tr>
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
     <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center"><input name="checkbox[]" onChange="test();" type="checkbox" id="checkbox[]" value="<? echo $record['MakeNo'];?>"></td>
    <? } 
	 if(($row['editrights'])=='Yes') 
	 { 
	 ?>
     <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center"> <a style="color:#0360B2" name="edit" href="vehiclemakemaster.php?<?php if(($row['editrights'])=='Yes') { echo 'edi='; echo $record['MakeNo'];} else echo 'permiss'; ?>">Edit</a></td>
      <? 
	  } 
	  ?>
     <td  bgcolor="#FFFFFF" ><?=$record['MakeNo']?> </td>
     <td  bgcolor="#FFFFFF" ><?=$record['MakeName']?></td>
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