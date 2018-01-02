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
	global $statecode,$statename,$country,$tname,$scode,$sname,$region;
	$scode = 'statecode';
	$sname = 'statename';
	$tname	= "state";
	require_once '../../searchfun.php';
    require_once '../../masterclass.php'; // Include The News Class
	require_once '../../paginationfunction.php';
	$news = new News(); // Create a new News Object
	$newsRecordSet = $news->getNews($tname);
	
	$pagename = "State Master";
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
			alert("you are not allowed to do this action!",'statemaster.php');
			//setInterval(function(){document.location='statemaster.php';},2000);
			//document.location='statemaster.php';	
			</script>
         <?
		
	}
	if(isset($_GET['permiss']))
	{
	?>
            <script type="text/javascript">
			alert("you are not allowed to do this action!");
			
			//document.location='statemaster.php';	
			</script>
         <?
		
	}
	
    if(isset($_POST['Save'])) // If the submit button was clicked
    {
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
        $post['statecode'] = str_replace('&', 'and',$_POST['statecode']);
		$post['statename'] = str_replace('&', 'and',$_POST['statename']);
		//  $post['region'] = $_POST['region'];
       // $post['country'] = $_POST['country'];
		
		$statecode=str_replace('&', 'and',$_POST['statecode']);
		$statename=str_replace('&', 'and',$_POST['statename']);
		//$region=$_POST['region'];
		//$country=$_POST['country'];
	
        // This will make sure its displayed
		if(!empty($_POST['statecode'])&&!empty($_POST['statename']))
{	
			$p1=strtoupper( preg_replace('/\s+/', '',$post['statecode']));
	$p2=strtoupper( preg_replace('/\s+/', '',$post['statename']));
	$cnduplicate=0;
	$repqry="SELECT REPLACE( `statecode` ,  ' ',  '' ) AS statecode, REPLACE(  `statename` ,  ' ',  '' ) AS statename FROM state where statename = '".$p2."' or statename = '".$post['statename']."' or statecode = '".$p2."' or statecode = '".$post['statename']."' or statecode = '".$p1."' or statecode = '".$post['statecode']."' or statename = '".$p1."' or statename = '".$post['statecode']."'";
	$repres= mysql_query($repqry) or die (mysql_error());
	$cnduplicate=mysql_num_rows($repres);
		if($cnduplicate>0 || ($post['statecode']==$post['statename']))
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
		?>
            <script type="text/javascript">
			alert("Created Sucessfully!",'statemaster.php');
			//setInterval(function(){document.location='statemaster.php';},2000);
			</script>
            <?
        }
}
		else
		{
			?>
            <script type="text/javascript">
			alert("Enter Mandatory Fields!");
			//document.location='statemaster.php';
			</script>
            <?
		}
    }
	 if(isset($_POST['Update'])) // If the submit button was clicked
    {
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
	      $post['statecode'] = $_POST['statecode'];
		$post['statename'] = str_replace('&', 'and',$_POST['statename']);
		  //$post['region'] = $_POST['region'];
        //$post['country'] = $_POST['country'];
		
		$statecode=$_POST['statecode'];
		$statename=str_replace('&', 'and',$_POST['statename']);
		//$region=$_POST['region'];
		//$country=$_POST['country'];
	
        // This will make sure its displayed
		if(!empty($_POST['statecode'])&&!empty($_POST['statename']))
{	   
$codenamedcheck=0;
		if($_SESSION['stsessionval']!=$statename)
		{
		$p2=strtoupper( preg_replace('/\s+/', '',$post['statename']));
		$repqry="SELECT REPLACE(  `statename` ,  ' ',  '' ) AS statename  FROM  `state` where statename = '".$p2."' or statename = '".$post['statename']."' or statecode = '".$p2."' or statecode = '".$post['statename']."'";
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
			$result="SELECT * FROM state where statecode ='".$_POST['statecode']."'";
	    $sql1 = mysql_query($result) or die (mysql_error());
 		$myrow1 = mysql_num_rows($sql1);//mysql_fetch_array($retval);
		if($myrow1>0)
		{
			$post['user_id'] = $_SESSION['username'];
						 date_default_timezone_set ("Asia/Calcutta");
						 $post['m_date']= date("y/m/d : H:i:s", time());	
						$wherecon= "statecode ='".$post['statecode']."'";
						$news->editNews($post,$tname,$wherecon);
						
						unset($_SESSION['stsessionval']);
						?>
            <script type="text/javascript">
			alert("Updated Sucessfully!",'statemaster.php');
			//setInterval(function(){document.location='statemaster.php';},2000);
			</script>
            <?
		}
		else
		{
			?>
            <script type="text/javascript">
			alert("You are not allowed to save a new record using update!");
			</script>
         <?
		}
	
			}
					
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
	
/// EDIT LINK FUNCTION 
if(!empty($_GET['edi']))
{
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
$prmaster =$_GET['edi'];

//$cont->connect();
$result=mysql_query("SELECT * FROM state where statecode ='".$prmaster."'");

$myrow1 = mysql_num_rows($result);//mysql_fetch_array($retval);

		if($myrow1==0)	
		{
			?>
            <script type="text/javascript">
			alert("No Data Found!",'statemaster.php');
			</script>
   			<?
		}
		else
		{
		   $myrow = mysql_fetch_array($result);
		
		   $statecode = $myrow['statecode'];
		   $statename = $myrow['statename'];
		  // $region = $myrow['region'];
		  // $country = $myrow['country'];
		  $_SESSION['stsessionval']= $myrow['statename'];
		 
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
			alert("Select data to delete!",'statemaster.php');
			</script>
			<?
	}

else
{
		 $checkbox = $_POST['checkbox']; //from name="checkbox[]"
		 $countCheck = count($_POST['checkbox']);
		for($i=0;$i<$countCheck;$i++)
		{
		//$prodidd = $checkbox[$i];
		///$prodid= $_POST['checkbox'];
		$prodidd = $checkbox[$i];
			$newvar=explode("~",$prodidd);
			$var1=$newvar[0];
			$var2=$newvar[1];
/* 			$q1="select statecode
FROM state
WHERE statecode='".$var1."' and EXISTS(
 SELECT State
FROM  `franchisemaster` 
WHERE State='".$var1."'
)
OR exists(
SELECT State
FROM pricelistlinking
WHERE State=  '".$var1."')
OR exists(
SELECT state
FROM suppliermaster
WHERE state=  '".$var1."')
OR exists(
SELECT state
FROM employeemaster
WHERE state=  '".$var1."')"; */

$q1="select statecode
FROM state
WHERE statecode='".$var1."' and EXISTS(
 SELECT State
FROM  `franchisemaster` 
WHERE State='".$var1."'
)
OR exists(
SELECT state
FROM employeemaster
WHERE state=  '".$var1."')";
 $repres= mysql_query($q1) or die (mysql_error());
 $myrow1 = mysql_num_rows($repres);
 if($myrow1==0)
 {
		$wherecon= "statecode ='".$var1."'";
		$news->deleteNews($tname,$wherecon);
	
			?>
            <script type="text/javascript">
			alert("Deleted  Successfully!",'statemaster.php');
			//setInterval(function(){document.location='statemaster.php';},2000);
			//document.location='statemaster.php';
			</script>
   			<?
}
else
{
	
	 						?>
           					<script type="text/javascript">
							alert("you can't delete already used in other forms!",'statemaster.php');
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
	header('Location:statemaster.php');
}

?>



<script type="text/javascript">

				
function validatestatecode(key)
{
var object = document.getElementById('statecode');
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

function validatestatename(key)
{
var object = document.getElementById('statename');
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
<title><?php echo $_SESSION['title']; ?> || State Master</title>
</head>
<?php 
 if(empty($_SESSION['codesval']) && empty($_SESSION['namesval']))
{
if(!empty($_GET['edi'])){?>
 
 <body class="default" onLoad="document.form1.statename.focus()">

<? }else{?>


<body class="default" onLoad="document.form1.statecode.focus()">

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
						<p>State Master</p>
						</div>
              <!-- main row 1 start-->     
                    <div style="width:925px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
                     <!-- col1 -->   
                           <div style="width:400px; height:auto; padding-bottom:5px; float:left; " class="cont">
                             <!--Row1 -->  
                               <div style="width:100px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>State Code</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:245px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                              <?
                              if(!empty($_GET['edi']))
                              {
                              ?>
                              <input type="text" name="statecode" style="border-style:hidden; background:#f5f3f1;" readonly="readonly" value="<?php echo $statecode;?>" id="zipsearch1" onChange="return codetrim(this)" />
                             <? } 
							else 
							{ 
							?>
                              
                              <input type="text" name="statecode" id ="statecode" maxlength="15" value="<?php echo $statecode;?>"  onchange="return codetrim(this)" onKeyUp="return validatestatecode(event)" />
                             <?
							}
							?>
                               </div>
 							<!--Row1 end-->
                            
                             <!--Row1 -->  
                              <div style="width:100px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>State Name</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:245px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                              <input type="text" name="statename" value="<?php echo $statename;?>" id="statename" maxlength="50" onChange="return trim(this)" onKeyUp="return validatestatename(event)" />
                              </div>
 							<!--Row1 end-->
                             
 							<!--Row2 end--> 
                                                  
                          </div>                              
                     <!-- col1 end --> 
                     
                     <!-- col2 -->   
                             
                     <!-- col2 end--> 
                     
                     <!-- col3 -->   
                           <div style="width:300px; height:auto; float:left; padding-bottom:5px; margin-left:2px;" class="cont">
                            </div>      
                     <!-- col3 --> 
                                                              
                    </div>
                  
                
				</div>
                <!-- main row 1 end-->
                
                
                <!--Main row 2 start-->
                <div style="width:930px; height:60px; float:left; margin-left:7px; margin-top:-95px;">
                             
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
                               <div style="width:90px; height:30px; float:left; margin-left:20px; margin-top:16px;" >
                                  <label>StateCode</label>
                               </div>
                               <div style="width:145px; height:30px;  float:left; margin-left:3px; margin-top:16px;">
                                  <input type="text" name="codes" id="codes" onKeyPress="searchKeyPress(event);" value="<? echo $_SESSION['codesval'] ?>"/>
                               </div>
                             <!--Row1 end-->  
                             
                               <!--Row2 -->  
                               <div style="width:90px; height:30px; float:left; margin-left:20px; margin-top:16px;">
                                  <label>StateName</label>
                               </div>
                               <div style="width:145px; height:30px;  float:left; margin-left:3px; margin-top:16px;" >
                                  <input type="text" name="names" onKeyPress="searchKeyPress(event);" value="<? echo $_SESSION['namesval']?>"/>
                               </div>
                             <!--Row2 end-->
                             
                             <div style="width:90px; height:32px; float:left; margin-top:16px; margin-left:16px;">
                                <input type="submit" name="Search" id="Search" value="Search" class="button"/>
                           </div>  
                          </div> 
                </div>
                
                <!--Main row 2 end-->
                
            
             <!-- form id start end-->  
             <div style="width:930px; height:auto; padding-bottom:8px; margin-top:-15px; float:left; margin-left:10px; overflow:auto;" class="grid">
                   
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
     <td style="font-weight:bold;">State Code</td>
     <td style="font-weight:bold;">State Name</td>
    <!-- <td style="font-weight:bold;">Region</td>
     <td style="font-weight:bold;">Country</td>--></tr>
     
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
     <td  style="font-weight:bold; text-align:center;" bgcolor="#FFFFFF"><input name="checkbox[]" type="checkbox" id="checkbox[]" onChange="test();" value="<? echo $record['statecode']."~".$record['statename'];?>"></td>
     <? 
   }
    if(($row['editrights'])=='Yes') 
	  { 
	 ?>
  <td style="font-weight:bold; text-align:center" bgcolor="#FFFFFF"> <a style="color:#0360B2" name="edit" href="statemaster.php?edi=<?= $record['statecode']; ?>">Edit</a></td>
 	<? 
 	  } 
	  ?>
    <td  bgcolor="#FFFFFF">
        <?=$record['statecode']?>
    </td>
     <td  bgcolor="#FFFFFF"  align="left" valign="top">
        <?=$record['statename']?>
    </td>
 <?php
      }
  ?>
  
<?php
  if(isset($_POST['Search']))
{
if($myrow1==0)	
{?>
		<? echo '<tr ><td colspan="21" align="center" bgcolor="#FFFFFF" style="color:#F00"  >No Records Found</td></tr>'; ?>	
<? } }?>
</table>
</div>
<?php include("../../paginationdesign.php")?>

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